<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Bat_get_alfstream_reading_history extends CI_Controller {

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// ALF Stream側視聴履歴取得処理
	// 視聴履歴を取得して、テーブル保存する
	// 引数２つを指定した場合、その日付の視聴履歴を取得する → 且つ、視聴履歴テーブル最新の再生日時より前はテーブル登録をしない
	// 引数設定不足・引数設定なしの場合は、システム日付の前日分を取得 
	//----------------------------------------------
	public function get_alfstream_reading_history($start_date = '', $end_date = ''){
		exec('ps auxw | grep get_alfstream_reading_history | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		print "[".date('Y-m-d H:i:s')."]"."start 'get_alfstream_reading_history'\n";
		
		// 引数の確認
		// １．両方入力あり　２．両方有効日付である　３．前後の関係が正しい
		$check_flag = false;
		if( ($start_date != '') && ($end_date != '') ){
			if( (strtotime($start_date) != false) && (strtotime($end_date) != false) ){
				if( $start_date <= $end_date ){
					$check_flag = true;
				}
			}
		}
		
		print "[".date('Y-m-d H:i:s')."]"."param_start_date : ".$start_date."\n";
		print "[".date('Y-m-d H:i:s')."]"."param_end_date   : ".$end_date."\n";
		
		// 処理開始日・処理終了日が指定なしの場合、システム日付の前日を設定
		if($check_flag){
			// 開始日付・終了日付（指定された日付）
			$start_date = date("Y-m-d", strtotime($start_date) );
			$end_date   = date("Y-m-d", strtotime($end_date)   );

			// 視聴履歴内の最新日時を取得
			$query_history = $this->db->query(
				'SELECT * FROM video_alfstream_reading_history WHERE 1 = 1 ORDER BY reading_date DESC LIMIT 0, 1',
				array()
			);

	//		$border_timestamp = strtotime($start_date.' 00:00:00');
			$start_time       = "T".date("H:i:s", strtotime("-3 hours") );
			$end_time         = "T".date("H:i:s", strtotime("0 hours") );
			if ($query_history->num_rows() > 0) {
				$reading_history  = $query_history->row_array();				// 一行取得
				$reading_date     = $reading_history['reading_date'];			// 再生日時の取得

				print "[".date('Y-m-d H:i:s')."]"."reading_date : ".$reading_date."\n";

				
				$start_date       = date("Y-m-d", strtotime($reading_date));		// 再生日時の日付を、開始日付に差し替え
				$start_time       = "T".date("H:i:s", strtotime($reading_date));	// 再生日時の時間を、開始時間に差し替え
				
	//			$border_timestamp = strtotime($reading_date);					// 再生日時をタイムスタンプ変換
			}
		}else{
			// 開始日付・終了日付（システム日付 の前日）
			$start_date = date("Y-m-d", strtotime("-1 day") );
			$end_date   = date("Y-m-d", strtotime("-1 day") );
		}
		
		print "[".date('Y-m-d H:i:s')."]"."history range from ".$start_date.$start_time." to ".$end_date.$end_time."\n";
	//	if( ($check_flag) && ($border_timestamp) ){
	//		print "[".date('Y-m-d H:i:s')."]"."border_datetime(timestamp) : ".date("Y-m-d H:i:s", $border_timestamp )."(".$border_timestamp.")"."\n";
	//	}

		// ビデオ視聴完了管理レポートを入れるために、
		// video_alfstream_reading_history の 最終再生日時 を取得する
		// （それより前のログをスキップするために。）
		$sql_video_history  = ' SELECT  MAX(reading_date) AS max_reading_date FROM video_alfstream_reading_history ';
		$query_video_history = $this->db->query($sql_video_history);
		$max_reading_date = '';
		if ($query_video_history->num_rows() > 0) {
			$max_reading_date = $query_video_history->row()->max_reading_date;
		}

		// videoテーブルより、有効ビデオの情報を取得（Status = ONLINE のもの）
		$sql_video  = ' SELECT  video.video_id ';
		$sql_video .= '        ,school.contract_param ';
		$sql_video .= '        ,video.idkey ';
		$sql_video .= '        ,video.update_at ';
		$sql_video .= '        ,video_alfstream_status.alfstream_status ';
		$sql_video .= '   FROM (video INNER JOIN school ON video.school_id = school.school_id) ';
		$sql_video .= '         LEFT JOIN video_alfstream_status ON video.video_id = video_alfstream_status.video_id ';
		$sql_video .= '  WHERE  video.status = 0 ';
		$sql_video .= "    AND  video_alfstream_status.alfstream_status = 'ONLINE' ";
		
		$query_video = $this->db->query(
			$sql_video,
			array()
		);
		
		$this->load->library('Curl');
		$this->load->helper('json');
		
		// 上記取得レコード のループ
		foreach($query_video->result_array() as $video){
			// 1.school.contract_param から auth_key 取得
			$contract_param = obj2arr(json_decode($video['contract_param']));
			$auth_key       = $contract_param['alfstream']['auth_key'];
			
			// 2.ALF Stream API にて、閲覧履歴を取得
			$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/analyze/log', array(
				"authkey"	=> $auth_key,
				'from'		=> $start_date.$start_time,
				'to'		=> $end_date.$end_time,
				'idkey'		=> $video['idkey'],
			));
//print var_dump($content); 
			// 3.取得した閲覧履歴をテーブルへ格納
			$history_array = array();
			$history_data  = explode("\r\n",trim($content));  // 改行分割
			
			foreach($history_data as $line_data){
				
				// 視聴履歴なしはコンティニュー
				if($line_data == 1){
					print "[".date('Y-m-d H:i:s')."]"."[video_id:".sprintf("%4d", $video['video_id'])."]"."no-History(".$line_data.")\n";
					continue;
				}
				
				// 0		1		2			3						4						5				6
				// 再生日時	IDKEY	UID(生徒ID)	再生フラグ(1 が再生)	閲覧率(パーセント表示)	UserAgent Type	UserAgent
				// タブによる配列変換
				$column_data = explode("\t",trim($line_data));
				
				// 各タブの値確認（値なしの場合の処理）)
				if( empty($column_data[0]) ) $column_data[0] = 978278461;	// 2001/01/01 01:01:01 
				if( empty($column_data[1]) ) $column_data[1] = '';
				if( empty($column_data[2]) ) $column_data[2] = '';
				if( empty($column_data[3]) ) $column_data[3] = 0;
				if( empty($column_data[4]) ) $column_data[4] = 0;
				if( empty($column_data[5]) ) $column_data[5] = '';
				if( empty($column_data[6]) ) $column_data[6] = '';
				
				// 閲覧率【0%】は保存しない
				if($column_data[4] > 0){
					
					// 引数の２つの日付があり、基準タイムスタンプがある場合
					// 基準タイムスタンプより前の再生日時のレコードの場合、登録処理をスキップ
					// → 再生日時 ≦ 基準タイムスタンプ
	//				if( ($check_flag) && ($border_timestamp) ){
	//					if( $column_data[0] <= $border_timestamp ){
	//						print "[".date('Y-m-d H:i:s')."]"."[video_id:".sprintf("%4d", $video['video_id'])."]"."[student_id:".sprintf("%4d", $column_data[2])."]"."skip(". date("Y/m/d H:i:s", $column_data[0] ) .")\n";
	//						continue;
	//					}
	//				}
					
/*
					$res = $this->db->query($this->db->insert_string('video_alfstream_reading_history', array(
							'video_id'         => $video['video_id'],
							'reading_date'     => date("Y-m-d H:i:s",$column_data[0]),
							'idkey'            => $column_data[1],
							'student_id'       => $column_data[2],
							'reading_flag'     => $column_data[3],
							'percent'          => $column_data[4],
							'user_agent_type'  => $column_data[5],
							'user_agent'       => $column_data[6],
							'update_at'        => date('Y-m-d H:i:s'),
						)));
*/
					$this->db->trans_start();
					$insert_query = $this->db->insert_string('video_alfstream_reading_history', array(
												'video_id'         => $video['video_id'],
												'reading_date'     => date("Y-m-d H:i:s",$column_data[0]),
												'idkey'            => $column_data[1],
												'student_id'       => $column_data[2],
												'reading_flag'     => $column_data[3],
												'percent'          => $column_data[4],
												'user_agent_type'  => $column_data[5],
												'user_agent'       => $column_data[6],
												'update_at'        => date('Y-m-d H:i:s'),
											));
					$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);	// INSERT時、重複エラーの場合、無視するSQL文に変更
					$this->db->query($insert_query);
					$this->db->trans_complete();
					
					// 確認用出力
					$temp_data  = "";
					$temp_data .= "[".date('Y-m-d H:i:s')."]";
					$temp_data .= "[video_id:".sprintf("%4d", $video['video_id'])."]";
					$temp_data .= "[student_id:".sprintf("%4d", $column_data[2])."]";
					$temp_data .= "[reading_date:".date("Y-m-d H:i:s",$column_data[0])."]";
					$temp_data .= "[percent:".sprintf("%3d", $column_data[4])."%]";
					$temp_data .= "\n";
					print $temp_data;
				}
			}
		}

		// 一旦、テストで日付けを代入
		// $max_reading_date = '2013-09-06 13:48:55';
		// $max_reading_date = '';

		// 受講者IDがある and 最終再生日時 より後 のログを取得する
		if ($max_reading_date != "") {
			$sql_video_history  = ' SELECT  video_id,student_id,MAX(percent) AS max_percent, MAX(reading_date) AS max_reading_date ';
			$sql_video_history .= '   FROM video_alfstream_reading_history ';
			$sql_video_history .= '  WHERE  student_id != 0 ';
			$sql_video_history .= "    AND  reading_date > ? ";
			$sql_video_history .= "    GROUP BY video_id,student_id";
			$sql_video_history .= "    ORDER BY reading_date";
			$query_video_history = $this->db->query(
				$sql_video_history,
				array($max_reading_date)
			);
		}
		else {
			$sql_video_history  = ' SELECT  video_id,student_id,MAX(percent) AS max_percent, MAX(reading_date) AS max_reading_date ';
			$sql_video_history .= '   FROM video_alfstream_reading_history ';
			$sql_video_history .= '  WHERE  student_id != 0 ';
			$sql_video_history .= "    GROUP BY video_id,student_id";
			$sql_video_history .= "    ORDER BY reading_date";
			$query_video_history = $this->db->query(
				$sql_video_history,
				array()
			);
		}

		// 上記取得レコード のループ
		foreach($query_video_history->result_array() as $video_history){
			// 授業用資料マスタ＆ALFStream側ビデオステータス情報を取得
			$query_video = $this->db->query('SELECT * FROM video V INNER JOIN video_alfstream_status VA ON (V.video_id = VA.video_id) WHERE V.video_id = ?',
				array($video_history['video_id'])
			);
			// 無かったらスキップ
			if ($query_video->num_rows() == 0) { continue; }

			// 授業用資料マスタ＆ALFStream側ビデオステータス情報を代入
			$video = $query_video->row();

			// すでに登録があるか確認
			$query_report = $this->db->query('SELECT * FROM report_user_video_viewed WHERE video_id = ? AND student_id = ?',
				array($video_history['video_id'], $video_history['student_id'])
			);
			if ($query_report->num_rows() == 0) {
				$param = array(
					'student_id'    => $video_history['student_id'],
					'video_id'    	=> $video_history['video_id'],
					'duration'      => $video->alfstream_duration,
					'duration_reading' => $this->h2sRe($this->h2s($video->alfstream_duration) * $video_history['max_percent'] / 100),
					'reading_date'  => $video_history['max_reading_date'],
					'percent'       => $video_history['max_percent'],
					'regist_at'	=> date('Y-m-d H:i:s'),
					'update_at'     => date('Y-m-d H:i:s'),
				);

				// 視聴完了FLG
				$alfstream_duration = $this->h2s($video->alfstream_duration);
				$duration_reading = $this->h2s($video->alfstream_duration) * $video_history['max_percent'] / 100;
				// 残り10秒で完了FLGが1にする
				if ($duration_reading >= $alfstream_duration - 10) {
					$param['complete_flag'] = 1;
					$param['complete_date'] = date('Y-m-d H:i:s');
				}

				$flg = $this->db->query($this->db->insert_string('report_user_video_viewed', $param));
				if ($flg) {
					// 確認用出力
					$temp_data  = "";
					$temp_data .= "[report_user_video_viewed -> insert ".date('Y-m-d H:i:s')."]";
					$temp_data .= "[video_id:".sprintf("%4d", $video_history['video_id'])."]";
					$temp_data .= "[student_id:".sprintf("%4d", $video_history['student_id'])."]";
					$temp_data .= "[reading_date:".$video_history['max_reading_date']."]";
					$temp_data .= "[percent:".sprintf("%3d", $video_history['max_percent'])."%]";
					$temp_data .= "\n";
					print $temp_data;
				}
			}
			else {
				$max_percent_def = $query_report->row();
				if ($max_percent_def->percent >= $video_history['max_percent']) {
					$param = array(
						'duration'      => $video->alfstream_duration,
						'duration_reading' => $this->h2sRe($this->h2s($video->alfstream_duration) * $video_history['max_percent'] / 100),
						'reading_date'  => $video_history['max_reading_date'],
						// 'percent'       => $video_history['max_percent'],
						'update_at'     => date('Y-m-d H:i:s'),
					);

					// 視聴完了FLG
					$alfstream_duration = $this->h2s($video->alfstream_duration);
					$duration_reading = $this->h2s($video->alfstream_duration) * $max_percent_def->percent / 100;
					// 残り10秒で完了FLGが1にする
					if ($duration_reading >= $alfstream_duration - 10) {
						// $param['complete_flag'] = 1;
					}

					// 記録している％より少なかったら％は変更しない
					$flg = $this->db->query($this->db->update_string('report_user_video_viewed', $param, 'video_id='.$video_history['video_id'].' AND student_id='.$video_history['student_id']));
				}
				else {
					$param = array(
						'duration'      => $video->alfstream_duration,
						'duration_reading' => $this->h2sRe($this->h2s($video->alfstream_duration) * $video_history['max_percent'] / 100),
						'reading_date'  => $video_history['max_reading_date'],
						'percent'       => $video_history['max_percent'],
						'update_at'     => date('Y-m-d H:i:s'),
					);

					// 視聴完了FLG
					$alfstream_duration = $this->h2s($video->alfstream_duration);
					$duration_reading = $this->h2s($video->alfstream_duration) * $video_history['max_percent'] / 100;
					// 残り10秒で完了FLGが1にする
					if ($duration_reading >= $alfstream_duration - 10) {
						// $param['complete_flag'] = 1;
					}

					$flg = $this->db->query($this->db->update_string('report_user_video_viewed', $param, 'video_id='.$video_history['video_id'].' AND student_id='.$video_history['student_id']
					));
				}
				if ($flg) {
					// 確認用出力
					$temp_data  = "";
					$temp_data .= "[report_user_video_viewed -> update ".date('Y-m-d H:i:s')."]";
					$temp_data .= "[video_id:".sprintf("%4d", $video_history['video_id'])."]";
					$temp_data .= "[student_id:".sprintf("%4d", $video_history['student_id'])."]";
					$temp_data .= "[reading_date:".$video_history['max_reading_date']."]";
					$temp_data .= "[percent:".sprintf("%3d", $video_history['max_percent'])."%]";
					$temp_data .= "\n";
					print $temp_data;
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		$this->db->query(
			"UPDATE 
			 report_user_video_viewed 
			SET 
			  complete_flag =1
			 ,complete_date =reading_date 
			WHERE 
			     percent =100 
			 AND complete_flag =0
			 AND complete_date IS NULL "
		);
		//+++++++++++++++++++++++++++++++++++++++++++++++
		$this->db->query(
			"UPDATE 
			 report_user_video_viewed 
			SET 
			  duration_reading=duration 
			WHERE 
			     percent =100 
			 AND duration<>duration_reading "
		);
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "[".date('Y-m-d H:i:s')."]"."end 'get_alfstream_reading_history'\n";
	}

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// 時：分：秒を秒に変更
	//----------------------------------------------
	public function h2s($hours) {
		$t = explode(":", $hours);
		$h = $t[0];
		if (isset($t[1])) {
			$m = $t[1];
		} else {
			$m = "0";
		}
		if (isset($t[2])) {
			$s = $t[2];
		} else {
			$s = "0";
		}

		return ($h*60*60) + ($m*60) + $s;
	}

	//----------------------------------------------
	// 秒を時：分：秒をに変更
	//----------------------------------------------
	public function h2sRe($second) {
		return gmdate('H:i:s', $second);
	}
}
