<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

// 視聴ログ   tbl_bookmark  report_user_video_viewed

#[AllowDynamicProperties]
class Bat_oneoff_import_tbl_bookmark extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
	public $_target_path              = '/alflearning-data/__import_data__/CSV/';	// ファイル格納場所
  //public $_target_file_name         = 'LOG_VIEW.csv';								// 対象ファイル名（タブ区切り注意）
	public $_target_file_name_array   = array(
		'LOG_VIEW.csv', 
		'tMORAL_VIEW_2009.csv', 
		'tMORAL_VIEW_2010.csv', 
		'tMORAL_VIEW_2011.csv', 
		'tMORAL_VIEW_2012.csv', 
		'tMORAL_VIEW.csv' 
	);
	
	public $_convert_log_file         = '/alflearning-data/__import_data__/LOG/LOG_VIEW_tMORAL_VIEW_to_tbl_bookmark.txt';

	// 使用テーブル名
//	public $_tbl_bookmark             = 'tbl_bookmark';
//	public $_report_user_video_viewed = 'report_user_video_viewed';
//	public $_video_alfstream_status   = 'video_alfstream_status';
	public $_tbl_bookmark             = 'tbl_bookmark_import';
	public $_report_user_video_viewed = 'report_user_video_viewed_import';
	public $_video_alfstream_status   = 'video_alfstream_status_import';
	
	// 弁護士会マスタ（mtb_bar_association）にない弁護士会コード変換表
	public $_etc_layer_number = array(
							61=>1,		// 関東弁護士会連合会        -> 日本弁護士連合会
							62=>1,		// 近畿弁護士会連合会        -> 日本弁護士連合会
							63=>1,		// 中部弁護士会連合会        -> 日本弁護士連合会
							64=>1,		// 中国地方弁護士会連合会    -> 日本弁護士連合会
							65=>1,		// 九州弁護士会連合会        -> 日本弁護士連合会
							66=>1,		// 東北弁護士会連合会        -> 日本弁護士連合会
							67=>1,		// 北海道弁護士会連合会      -> 日本弁護士連合会
							68=>1,		// 四国弁護士会連合会        -> 日本弁護士連合会
							69=>1,		// NULL                      -> 日本弁護士連合会
							70=>6,		// 横浜弁護士会 県西支部     -> 横浜
							71=>6,		// 横浜弁護士会 川崎支部     -> 横浜
							72=>6,		// 横浜弁護士会 相模原支部   -> 横浜
							73=>7,		// 埼玉県弁護士会 越谷支部   -> 埼玉
							74=>7,		// 埼玉県弁護士会 熊谷支部   -> 埼玉
							75=>7,		// 埼玉県弁護士会 川越支部   -> 埼玉
							76=>12,		// 静岡県弁護士会 沼津支部   -> 静岡県
							77=>12,		// 静岡県弁護士会 浜松支部   -> 静岡県
							78=>14,		// 長野県弁護士会 諏訪在住会 -> 長野県
							79=>18,		// 兵庫県弁護士会 姫路支部   -> 兵庫県
							80=>28,		// 広島弁護士会 福山地区会   -> 広島
							81=>29,		// 山口県弁護士会 宇部地区会 -> 山口県
							82=>29,		// 山口県弁護士会 下関地区   -> 山口県
							83=>29,		// 山口県弁護士会 岩国地区会 -> 山口県
							84=>29,		// 山口県弁護士会 周南地区   -> 山口県
							85=>31,		// 鳥取県弁護士会 米子支部   -> 鳥取県
							86=>33,		// 福岡県弁護士会 筑後部会   -> 福岡県
							87=>33,		// 福岡県弁護士会 北九州部会 -> 福岡県
							88=>42,		// 福島県弁護士会 郡山支部   -> 福島県
							89=>31,		// 鳥取県弁護士会 倉吉支部   -> 鳥取県
							90=>12,		// 静岡県弁護士会 県西支部   -> 静岡県
							99=>1,		// webmaster                 -> 日本弁護士連合会
	);
	
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		// テスト時使用。
		ini_set('display_errors', 'On');
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
	// CSV→DB テーブル格納処理
	//----------------------------------------------
	public function oneoff_import(){
		exec('ps auxw | grep oneoff_import | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo var_dump($outputs);
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		$this->_output_log("START oneoff_import");
		
		// 対象テーブル内容削除
		try{
			$this->db->trans_start();
		  //$this->db->query("TRUNCATE TABLE tbl_bookmark");
		  //$this->db->query("TRUNCATE TABLE report_user_video_viewed");
			$this->db->query("TRUNCATE TABLE ".$this->_tbl_bookmark." ");
			$this->db->query("TRUNCATE TABLE ".$this->_report_user_video_viewed." ");
			$this->db->trans_complete();
			$this->_output_log("対象テーブルの内容初期化、成功");
		}catch(Exception $e){ 
		//	$this->db->trans_rollback();
			$this->_output_log("対象テーブルの内容初期化、例外エラー");
		}
		
		// 複数ファイルをインポート
		$_target_file_name_list = $this->_target_file_name_array;
		for ($i=0 ; $i < count($_target_file_name_list) ; $i++) {
			$_target_file_name = $_target_file_name_list[$i];

			// ファイル存在確認（なければ次配列へ）
			if(!is_file($this->_target_path.$_target_file_name)){
				$this->_output_log("ファイル：".$this->_target_path.$_target_file_name." が見つかりません");
			  //return;
				continue;
			}else{
				$this->_output_log("ファイル：".$this->_target_path.$_target_file_name." 処理開始");
			}
			
			// CSV取込処理
			$fp = fopen($this->_target_path.$_target_file_name, 'r');
			$counter = 0;
			while(!feof($fp)){
				$arrayRecord = $this->fgetcsv($fp, filesize($this->_target_path.$_target_file_name), "\t");
				
				// 配列先頭が数字でない場合は次レコード（物理項目名）
				if(!is_numeric($arrayRecord[0])){
					continue;
				}elseif(( $arrayRecord[0]==134400) && ($_target_file_name=='LOG_VIEW.csv') ){
				// 不具合行をスルー
				$counter = $counter + 1;
				$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] SKIP DATA [SERIAL_ID : ".$arrayRecord[0]." / student_id - video_id : - ]", 1);
					continue;	// 不具合行をスルー
				}else{
				// 数字の場合、取込対象レコードとして取込処理
					
					try{
					  //$this->load->database();
						$this->db->trans_start();
						
						$_convert = array();
						
						// 各データの変換【tbl_bookmark】
						
						// UID（MS_USER.SERIAL_ID）を元に student.student_id を取得
						// 前提条件：student テーブルへのバッチによる投入が終了していること・
						// convert_id_list（DB取込用ID変換履歴テーブル）から、student_id の取得（取得失敗時は数字ゼロ）
						$_convert['student_id'] = $this->_get_convert_now_id('student', $arrayRecord[1]);
						
						
						// eラーニングのビデオID取得
						if($_target_file_name=='LOG_VIEW.csv'){
							// MOVIE_ID（MS_CONTENTS.SERIAL_ID）を元に video.video_id を取得
							// 前提条件：video テーブルへのバッチによる投入が終了していること・
							// convert_id_list（DB取込用ID変換履歴テーブル）から、video_id の取得（取得失敗時は数字ゼロ）
							$_convert['video_id'] = $this->_get_convert_now_id('video_elearning', $arrayRecord[3]);
						// 倫理研修のビデオID取得
						}else{
							// MOVIE_ID（MS_CONTENTS.SERIAL_ID）を元に video.video_id を取得
							// 前提条件：video テーブルへのバッチによる投入が終了していること・
							// convert_id_list（DB取込用ID変換履歴テーブル）から、video_id の取得（取得失敗時は数字ゼロ）
							$_convert['video_id'] = $this->_get_convert_now_id('video_tmoral', $arrayRecord[3]);
						}

					//	if(trim($arrayRecord[8])=='') $arrayRecord[8] = 0;
					//	$_convert['bookmark_time'] = gmdate("H:i:s", $arrayRecord[8]);	// 動画再生時間
						
						if(trim($arrayRecord[7])=='') $arrayRecord[7] = 0;	// VIEW_SEC_MAX
						if(trim($arrayRecord[8])=='') $arrayRecord[8] = 0;	// VIEW_SEC
						if(trim($arrayRecord[5])!=''){						// VIEW_COMP_DATE が空以外
							$_convert['bookmark_time'] = gmdate("H:i:s", $arrayRecord[7]);	// 動画再生時間（VIEW_SEC_MAXを使用）
						}else{
							$_convert['bookmark_time'] = gmdate("H:i:s", $arrayRecord[8]);	// 動画再生時間（VIEW_SECを使用）
						}

						if(trim($arrayRecord[6])=='') $arrayRecord[6] = date('Y/m/d H:i:s');
						$_convert['bookmark_timestamp'] = $arrayRecord[6];				// 登録時間

						// 各データの変換【report_user_video_viewed】
						
						// 再生時間（ビデオ長さ）
						// 値なし→00:00:00。video_alfstream_status テーブルより取得
						$_convert['duration']         = $this->_get_duration_from_video_alfstream_status($_convert['video_id']);
						
						$_convert['duration_reading'] = $_convert['bookmark_time'];			// 視聴済み時間
						$_convert['reading_date']     = $_convert['bookmark_timestamp'];	// 直近再生日時
						
						// 最大視聴率（単位:%）
						$max_s = $this->_time_to_second($_convert['duration']);
						$now_s = $this->_time_to_second($_convert['duration_reading']);
						if($max_s==0){
							$_convert['percent'] = 0;
						}else{
						//	$_convert['percent'] = $now_s * 100 / $max_s;
							$_convert['percent'] = round($now_s * 100 / $max_s);
						}
						
						
						// 視聴完了フラグ 0:未完 / 1:完了
						if(trim($arrayRecord[5])=='') $_convert['complete_flag'] = 0;
						if(trim($arrayRecord[5])!='') $_convert['complete_flag'] = 1;
						
						if($_convert['percent']==100){
							$_convert['complete_flag'] = 1;
						}
						
						// complete_date
						if(trim($arrayRecord[5])=='') $arrayRecord[5] = '0000-00-00 00:00:00';
						$_convert['complete_date'] = $arrayRecord[5];
						
						// regist_at
						if(trim($arrayRecord[4])=='') $arrayRecord[4] = '0000-00-00 00:00:00';
						$_convert['regist_at'] = $arrayRecord[4];
						
						// 更新日時
						$_convert['update_at'] = '0000-00-00 00:00:00';

/*
	
	print var_dump($arrayRecord);
	print "\n";
	print var_dump($_convert);
	print "\n";
	$counter = $counter + 1;
	if($counter == 1){
		continue 2;
	}
*/
						// （出力ログ用）INSERT or UPDATE 判定
						$insert_update_msg = $this->_search_bookmark($_convert['student_id'], $_convert['video_id']);

						//新規 or 更新
						$sql_1 = "INSERT INTO ".$this->_tbl_bookmark." (
									  student_id 
									 ,video_id 
									 ,bookmark_time
									 ,bookmark_timestamp 
								) VALUES(?,?,?,?) on duplicate key update 
									  student_id         = ? 
									 ,video_id           = ? 
									 ,bookmark_time      = ? 
									 ,bookmark_timestamp = ? ";
						
						$sql_2 = "INSERT INTO ".$this->_report_user_video_viewed." (
									  student_id
									 ,video_id
									 ,duration
									 ,duration_reading
									 ,reading_date
									 ,percent
									 ,complete_flag
									 ,complete_date
									 ,regist_at
									 ,update_at
								) VALUES(?,?,?,?,?,?,?,?,?,?) on duplicate key update 
									  student_id       = ? 
									 ,video_id         = ? 
									 ,duration         = ? 
									 ,duration_reading = ? 
									 ,reading_date     = ? 
									 ,percent          = ? 
									 ,complete_flag    = ? 
									 ,complete_date    = ? 
									 ,regist_at        = ? 
									 ,update_at        = ? ";
						
						$this->db->trans_begin();
						
						// INSERT student
						$this->db->query($sql_1, array(
											$_convert['student_id'],
											$_convert['video_id'],
											$_convert['bookmark_time'],
											$_convert['bookmark_timestamp'],
											
											$_convert['student_id'],
											$_convert['video_id'],
											$_convert['bookmark_time'],
											$_convert['bookmark_timestamp'],
										));
						
						$this->db->query($sql_2, array(
											$_convert['student_id'],
											$_convert['video_id'],
											$_convert['duration'],
											$_convert['duration_reading'],
											$_convert['reading_date'],
											$_convert['percent'],
											$_convert['complete_flag'],
											$_convert['complete_date'],
											$_convert['regist_at'],
											$_convert['update_at'],

											$_convert['student_id'],
											$_convert['video_id'],
											$_convert['duration'],
											$_convert['duration_reading'],
											$_convert['reading_date'],
											$_convert['percent'],
											$_convert['complete_flag'],
											$_convert['complete_date'],
											$_convert['regist_at'],
											$_convert['update_at'],
										));
						
						$this->db->trans_complete();
						$counter = $counter + 1;
						$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] ".$insert_update_msg." DATA [SERIAL_ID : ".$arrayRecord[0]." / student_id - video_id : ".$_convert['student_id']." - ".$_convert['video_id']."]", 1);

/*
	if($counter == 50){
		break;
	}
*/

					}catch(Exception $e){ 
						$this->db->trans_rollback();
						$this->_output_log("取込処理例外発生");
					}
				}
			}
			fclose($fp);
		
		}	// ファイル名ループ
		
		// 終了ログ
		$this->_output_log("END oneoff_import");
	}

	//----------------------------------------------
	// ファイルから1行取得＋カンマ区切からの配列変換
	//----------------------------------------------
	function fgetcsv (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$eof = false;
		while ($eof != true) {
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
		
		// csv・tsv の場合、末尾が文字列ゼロの場合、カラム数削減が起こるため、末尾に特定文字を入れて配列数を維持
		$_csv_line .= $d."[EOF]";
		
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}
	//	mb_convert_variables("UTF-8", "SJIS-win", $_csv_data);
		return empty($_line) ? false : $_csv_data;
	}

	//----------------------------------------------
	// SSO側所属弁護士会から、システム側弁護士会IDを取得
	//----------------------------------------------
	function _get_bar_association_id($bar_association = ''){
		$bar_association_id = '';
		
		try{ 
			$query = $this->db->query(
				' SELECT mtb_bar_association.* '.
				'   FROM mtb_bar_association'.
				'  WHERE 1=1'.
				'    AND mtb_bar_association.sso_id = ? '.
				'  LIMIT 0, 1',
				array(
					trim($bar_association),
				)
			);
			
			if ($query->num_rows() > 0) {
				$bar_association    = $query->row_array();		// 複数はresult_array
				$bar_association_id = $bar_association['id'];
			}else{
				$bar_association_id = $this->_etc_layer_number[$bar_association];
			}
		}catch(Exception $e){ 
			$bar_association_id = '';
		}
		
		return $bar_association_id;
	}

	//----------------------------------------------
	// LOG_VIEW.UID（tMORAL_VIEW.UID）をキーに
	// convert_id_list（DB取込用ID変換履歴テーブル）から
	// student.student_id を取得
	//----------------------------------------------
	function _get_convert_now_id($id_kind = '', $old_id = ''){
		$new_id = 0;
		
		try{ 
			$query = $this->db->query(
				' SELECT convert_id_list.* '.
				'   FROM convert_id_list'.
				'  WHERE 1=1'.
				'    AND convert_id_list.id_kind = ? '.
				'    AND convert_id_list.old_id  = ? '.
				'  ORDER BY update_at DESC '.
				'  LIMIT 0, 1',
				array(
					$id_kind,
					$old_id,
				)
			);
			
			if ($query->num_rows() > 0) {
				$table_record = $query->row_array();		// 複数はresult_array
				$new_id       = $table_record['new_id'];
			}
		}catch(Exception $e){ 
			$new_id = 0;
		}
		
		return $new_id;
	}

	//----------------------------------------------
	// ビデオIDから、ビデオ再生時間の取得
	//----------------------------------------------
	function _get_duration_from_video_alfstream_status($video_id = ''){
		$alfstream_duration = '00:00:00';
		
		try{ 
			$query = $this->db->query(
				' SELECT * '.
				'   FROM '.$this->_video_alfstream_status.
				'  WHERE 1=1'.
				'    AND video_id = ? '.
				'  LIMIT 0, 1',
				array(
					trim($video_id),
				)
			);
			
			if ($query->num_rows() > 0) {
				$row_array          = $query->row_array();		// 複数はresult_array
				$alfstream_duration = $row_array['alfstream_duration'];
			}
		}catch(Exception $e){ 
			$alfstream_duration = '00:00:00';
		}
		
		return $alfstream_duration;
	}


	//----------------------------------------------
	// 時：分：秒を秒に変更
	//   '%d/%m/%Y %H:%M:%S';
	//----------------------------------------------
	function _time_to_second($times = '00:00:00') {
		// 時間型でないばあいゼロを返す
		if( !strptime($times, '%H:%M:%S') ){
			return 0;
		}
		
		$t = explode(":", $times);	// コロンで分割
		$h = intval($t[0]);			// 時
		$m = intval($t[1]);			// 分
		$s = intval($t[2]);			// 秒
		
		return ($h*60*60) + ($m*60) + $s;
	}


	//----------------------------------------------
	// tbl_bookmark に同student_id・同video_id があるかを確認
	//----------------------------------------------
	function _search_bookmark($student_id = 0, $video_id = 0){
		$result_data = 'unknown';

		try{ 
			$query = $this->db->query(
				' SELECT * '.
				'   FROM '.$this->_tbl_bookmark.' '.
				'  WHERE 1=1 '.
				'    AND student_id = ? '.
				'    AND video_id    = ? ',
				array(
					$student_id,
					$video_id,
				)
			);
			
			if ($query->num_rows() > 0) {
				$result_data = 'UPDATE';
			}else{
				$result_data = 'INSERT';
			}
		}catch(Exception $e){ 
			$result_data = 'unknown';
		}
		return $result_data;
	}


	//----------------------------------------------
	// ログファイル出力＋画面出力 → ファイル出力を停止
	//----------------------------------------------
	function _output_log($val = "", $display_show = 0){
		try{
			if(!is_file($this->_convert_log_file)){
				write_file($this->_convert_log_file, "\n", 'a+');
				chmod($this->_convert_log_file, 0777);
			}
			if($val==""){
			}else{
				$val = "[".date('Y-m-d H:i:s')."]".$val;
			}
			
			if($display_show!=0){
				print $val."\n";
			}
			write_file($this->_convert_log_file, $val."\n", 'a+');
			
			return true;
		}catch(Exception $e){ 
			return false;
		}
	}
	

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}
/*
	//----------------------------------------------
	// ×登録年月日から、パスポート料金を取得
	// 登録年月日（yyyymmdd）から、パスポートの有無・パスポートの有効期限・パスポート料金を取得
	//----------------------------------------------
	function _get_target_passport($regist_date = ''){
		// 戻り値の初期化
		$result['presence_passport'] = '';		// パスポートの有無（有⇒1、無⇒0）
		$result['exp_date_passport'] = '';		// パスポートの有効期限
		$result['target_passport']   = '';		// パスポート料金
	
		// 登録年月日の正誤確認
		$regist_date = trim($regist_date);
	  //$regist_date = preg_replace("/[\/]/", "-", $regist_date);
		if($regist_date == ''){
			return $result;
		}else{
			// タイムスタンプ変換確認
			if(!strtotime($regist_date)){
				return $result;
			}
		
			$regist_date = date('Y-m-d', strtotime($regist_date));
		
			$array_date = explode("-", $regist_date);
			
			if(!checkdate($array_date[1], $array_date[2], $array_date[0])){
				return $result;
			}
		}
		
		// 登録年月日とシステム日付より、年目を計算
		// 登録年月日≦システム日時の場合、１～６年目の計算。
		// 登録年月日＞システム日時の場合、０年目で計算。価格も１～２年目に対応。
		$array_date = explode("-", $regist_date);
		$year_no    = 0;
		if( $regist_date <= date('Y-m-d') ){
			$year_no = 6;
			for( $i=0; $i<=4; $i++ ){
				$start_date = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2],     $array_date[0] + $i));
				$end_date   = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2] - 1, $array_date[0] + $i + 1));
				
				if( ($start_date <= date('Y-m-d')) && (date('Y-m-d') <= $end_date) ){
					$year_no = $i + 1;
					break;
				}
			}
		}
		
		// 年目に応じての処理
		//                     0～2年目          3～4年目      5年目以降
		// パスポート料金      0円               5000円        10000円
		// パスポート有無      有                無            無
		// パスポート有効期限  登録年月日から    購入月の      購入月の
		//                     2年間             翌年同月末    翌年同月末
		//
		if($year_no < 3){
			// 0～2年目
			$result['presence_passport'] = '1';
			$result['exp_date_passport'] = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2]-1, $array_date[0] + 2));
			$result['target_passport']   = '0円';
		}elseif( ($year_no==3) || ($year_no==4) ){
			// 3～4年目
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '5000円';
		}else{
			// 5年目以降
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '10000円';
		}
		
		return $result;
	}
*/
