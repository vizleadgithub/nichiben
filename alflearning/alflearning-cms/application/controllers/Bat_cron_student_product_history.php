<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

#[AllowDynamicProperties]
class Bat_cron_student_product_history extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
	//$this->_convert_log_file = "/tmp/bat_cron_student_product_history.log";

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		// テスト時使用。
		ini_set('display_errors', is_cli() ? 'On' : 'Off');  // 画面(Web)にはエラーを出さない
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// 受講履歴集計テーブル格納処理
	//   student_product_history を受講者ごとに再集計する。
	//   引数に student_id を渡すとその受講者のみ処理する（確認用）。
	//   例: php index.php bat_cron_student_product_history insert_student_product_history 54752
	//----------------------------------------------
	public function insert_student_product_history($only_student_id = null){
		$max_contents = 25;
		$start_time = time();

		// 実行したSQLをメモリに保持しない（全受講者処理でメモリが増え続けるのを防ぐ）
		$this->db->save_queries = FALSE;

		print "[".date('Y/m/d H:i:s')."]\n";
		print("START insert_student_product_history\n");
		exec('ps auxw | grep insert_student_product_history | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$this->_output_log("START insert_student_product_history");

		// 受講者一覧
		$sql = "SELECT student.student_id FROM student WHERE student.status=0";
		$binds = array();
		if ($only_student_id !== null && $only_student_id !== ''){
			$sql.= " AND student.student_id=?";
			$binds[] = (int)$only_student_id;
		}
		$arr_student_list = $this->db->query($sql, $binds)->result_array();
		$student_total = count($arr_student_list);
		print("[students:".$student_total."]\n");

		// 動画ごとの総再生時間（秒）を一括取得
		$arr_duration = array();
		$query = $this->db->query("SELECT video_id, alfstream_duration FROM video_alfstream_status");
		foreach ($query->result_array() as $row){
			$arr_duration[(string)$row['video_id']] = $this->_time_to_sec($row['alfstream_duration']);
		}

		// 対象商品（動画あり・有効）を一括取得し、動画ID→商品ID の対応表を作成
		$sql = "SELECT TP.product_id";
		for($i=1; $i<=$max_contents; $i++){
			$sql.= ", TP.contents_contents$i";
		}
		$sql.= " FROM tbl_product AS TP LEFT JOIN tbl_product_add AS TPA ON TP.product_id = TPA.product_id";
		$sql.= " WHERE TP.del_flg = 0 AND TPA.product_type_add = '1'";
		$arr_product = array();   // product_id => array(contents1..25)
		$arr_video_product = array(); // video_id => array(product_id => true)
		foreach ($this->db->query($sql)->result_array() as $row){
			$pid = $row['product_id'];
			if (isset($arr_product[$pid])){
				continue;
			}
			$contents = array();
			for($i=1; $i<=$max_contents; $i++){
				$v = $row["contents_contents$i"];
				$contents[$i] = $v;
				if ($v !== null && $v !== ''){
					$arr_video_product[(string)$v][$pid] = true;
				}
			}
			$arr_product[$pid] = $contents;
		}

		$student_count = 0;
		foreach ($arr_student_list as $student_row){
			$student_id = (int)$student_row["student_id"];
			$student_count++;

			// 受講者の視聴データを一括取得
			$query = $this->db->query(
				"SELECT video_id, duration_reading, percent, complete_flag, reading_date, UNIX_TIMESTAMP(reading_date) AS u_reading_date
				 FROM report_user_video_viewed WHERE student_id = ?",
				array($student_id)
			);
			$arr_viewed = array();   // video_id => 視聴データ（先頭1件）
			$arr_complete = array(); // video_id => complete_flag=1 の行があるか
			$arr_rows = array();     // video_id => 全行
			$arr_target = array();   // 受講した(percent>=1)動画を含む商品
			foreach ($query->result_array() as $row){
				$vid = (string)$row['video_id'];
				if (!isset($arr_viewed[$vid])){
					$arr_viewed[$vid] = $row;
				}
				if ((string)$row['complete_flag'] === '1'){
					$arr_complete[$vid] = true;
				}
				$arr_rows[$vid][] = $row;
				if ($row['percent'] !== null && $row['percent'] >= 1 && isset($arr_video_product[$vid])){
					foreach ($arr_video_product[$vid] as $pid => $dummy){
						$arr_target[$pid] = true;
					}
				}
			}
			if (empty($arr_target)){
				continue;
			}

			$arr_insert = array();
			foreach ($arr_target as $pid => $dummy){
				$total_sec = 0;
				$reading_sec = 0;
				$max_u_reading = null;
				// 総再生時間・視聴済み時間（コンテンツが空になるまで）
				for($i=1; $i<=$max_contents; $i++){
					$vid = $arr_product[$pid][$i];
					if ($vid == ''){
						break;
					}
					$vid = (string)$vid;
					if (isset($arr_duration[$vid])){
						$total_sec += $arr_duration[$vid];
					}
					if (isset($arr_viewed[$vid])){
						$reading_sec += $this->_time_to_sec($arr_viewed[$vid]['duration_reading']);
						$u = $arr_viewed[$vid]['u_reading_date'];
						if ($max_u_reading === null || $max_u_reading < $u){
							$max_u_reading = $u;
						}
					}
				}

				// 最終活動日時：未完了(percent 1〜99)の動画の最新視聴日時、なければ最終視聴日の0時
				$last_activity = null;
				for($i=1; $i<=$max_contents; $i++){
					$vid = $arr_product[$pid][$i];
					if ($vid === null || $vid === '' || !isset($arr_rows[(string)$vid])){
						continue;
					}
					foreach ($arr_rows[(string)$vid] as $row){
						if ((string)$row['complete_flag'] === '0' && $row['percent'] !== null && $row['percent'] >= 1 && $row['percent'] <= 99){
							if ($last_activity === null || $last_activity < $row['reading_date']){
								$last_activity = $row['reading_date'];
							}
						}
					}
				}
				if ($last_activity === null){
					if ($max_u_reading === null){
						continue;
					}
					$last_activity = date('Y-m-d', $max_u_reading)." 00:00:00";
				}

				$arr_insert[] = array(
					$student_id,
					$pid,
					$this->_sec_to_time($total_sec),
					$this->_sec_to_time($reading_sec),
					$last_activity,
				);
			}

			// まとめて登録
			foreach (array_chunk($arr_insert, 500) as $chunk){
				$placeholders = array();
				$params = array();
				foreach ($chunk as $r){
					$placeholders[] = "(?,?,?,?,?)";
					$params = array_merge($params, $r);
				}
				$sql = "INSERT INTO student_product_history (student_id, product_id, total_time, viewed_time, last_activity) VALUES "
					.implode(",", $placeholders)
					." ON DUPLICATE KEY UPDATE total_time=VALUES(total_time), viewed_time=VALUES(viewed_time), last_activity=VALUES(last_activity)";
				$this->db->query($sql, $params);
			}

			if ($student_count % 100 == 0){
				print("[".date('Y/m/d H:i:s')."] ".$student_count."/".$student_total." (student_id:".$student_id.")\n");
			}
		}

		// 終了ログ
		$this->_output_log("END insert_student_product_history");
		print "[".date('Y/m/d H:i:s')."] processed:".$student_count." elapsed:".(time()-$start_time)."s\n";
	}

	/**
	 * time型(00:00:00形式)を秒に変換
	 */
	function _time_to_sec($t){
		if ($t === null || $t === ''){
			return 0;
		}
		$a = explode(":", $t);
		return (int)$a[0]*3600 + (int)$a[1]*60 + (int)$a[2];
	}

	/**
	 * 秒をtime型(00:00:00形式)に変換
	 */
	function _sec_to_time($sec){
		$sec = (int)$sec;
		return sprintf('%02d:%02d:%02d', floor($sec/3600), floor($sec/60)%60, $sec%60);
	}





	//----------------------------------------------
	// ログファイル出力＋画面出力 → ファイル出力を停止
	//----------------------------------------------
	function _output_log($val = ""){
print("[".$val."]\n");
//		try{
//			if(!is_file($this->_convert_log_file)){
//				write_file($this->_convert_log_file, "\n", 'a+');
//				chmod($this->_convert_log_file, 0777);
//			}
//			if($val==""){
//			}else{
//				$val = "[".date('Y-m-d H:i:s')."]".$val;
//			}
//			
//			print $val."\n";
//			write_file($this->_convert_log_file, $val."\n", 'a+');
//			
//			return true;
//		}catch(Exception $e){ 
//			return false;
//		}
	}
	

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}

	/**
	 * time型のデータの加算
	 * @param $a time型の時間(00:00:00形式)
	 * @param $b time型の時間(00:00:00形式)
	 */
	function getTimeAddition($a,$b){
		$a_array = explode(":",$a);
		$b_array = explode(":",$b);
		
		// 時、分、秒の合計
		$h_array = $a_array[0]+$b_array[0];
		$i_array = $a_array[1]+$b_array[1];
		$s_array = $a_array[2]+$b_array[2];
		
		$s = $s_array-(floor($s_array/60)*60);  // 秒の計算
		$i = ($i_array+floor($s_array/60))-(floor(($i_array+floor($s_array/60))/60)*60);  // 分の計算
		$h = $h_array+floor(($i_array+floor($s_array/60))/60); // 時間の計算
		
		// 一桁だったら'0'を付加
		if($h<10){$h='0'.$h;}
		if($i<10){$i='0'.$i;}
		if($s<10){$s='0'.$s;}
		
		return $h.":".$i.":".$s;
	}

	/**
	 * time型のデータの減算
	 * @param $a time型の時間(00:00:00形式)
	 * @param $b time型の時間(00:00:00形式)
	 */
	function getTimeSubtraction($a,$b){
		$a_array = explode(":",$a);
		$b_array = explode(":",$b);
		
		// 時、分、秒の合計
		$h_array = $a_array[0]-$b_array[0];
		$i_array = $a_array[1]-$b_array[1];
		$s_array = $a_array[2]-$b_array[2];
		
		$s = $s_array-(floor($s_array/60)*60);  // 秒の計算
		$i = ($i_array+floor($s_array/60))-(floor(($i_array+floor($s_array/60))/60)*60);  // 分の計算
		$h = $h_array+floor(($i_array+floor($s_array/60))/60); // 時間の計算
		
		// 一桁だったら'0'を付加
		if($h<10){$h='0'.$h;}
		if($i<10){$i='0'.$i;}
		if($s<10){$s='0'.$s;}
		
		return $h.":".$i.":".$s;
	}

	/**
	 * time型のデータを変換する(0時間0分0秒)
	 */
	function time_format_product_list($time){
		$arr_time = explode(":",$time);
		return $arr_time[0].'時間'.$arr_time[1].'分'.$arr_time[2].'秒';
	}

}

