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
	public function insert_student_product_history(){
		$arr_list = array();

		print "[".date('Y/m/d H:i:s')."]\n";
		print("START insert_student_product_history\n");
		exec('ps auxw | grep insert_student_product_history | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$this->_output_log("START insert_student_product_history");

		$arr_student_list = array();
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " student.student_id ";
		$sql.= "FROM ";
		$sql.= " student ";
		$sql.= "WHERE ";
		$sql.= " 1=1 ";
		$sql.= " AND student.status=0";
		//$sql.= " AND student.student_id=36366";
		//print("[sql0:".$sql."]\n");
		$query = $this->db->query(
			$sql,
			array()
		);
		if ($query->num_rows() > 0) {
			$arr_student_list = $query->result_array();
		}

		$student_id = 0;
		foreach ($arr_student_list as $student_key => $student_row){
			$arr_list = array();
			$student_id = $student_row["student_id"];

			print("===============================================\n");
			print("[student_id:".$student_id."]\n");


			// 受講した動画のvideo_idを取得
			$sql = "
			SELECT
			  video_id
			FROM
			  report_user_video_viewed
			WHERE
			  student_id = ?
			  AND percent >= 1
			";
			//print("[sql1:".$sql."]\n");
			$query = $this->db->query(
				$sql,
				array($student_id)
			);
			if ($query->num_rows() > 0) {
				$res = $query->result_array();
				$in_video_id = '';
				foreach ($res as $val){
					// where文用文字列の作成
					$in_video_id.= $val['video_id'].',';
				}
				
				if ($in_video_id != ''){
					$in_video_id = rtrim($in_video_id, ',');
					print("[in_video_id:".$in_video_id."]\n");
					
					// 受講中のビデオが設定されている商品をベースに取得
					$where = "
					WHERE
					  TP.del_flg = 0
					  AND (
					    TP.contents_contents1 IN($in_video_id)
					    OR TP.contents_contents2 IN($in_video_id)
					    OR TP.contents_contents3 IN($in_video_id)
					    OR TP.contents_contents4 IN($in_video_id)
					    OR TP.contents_contents5 IN($in_video_id)
					    OR TP.contents_contents6 IN($in_video_id)
					    OR TP.contents_contents7 IN($in_video_id)
					    OR TP.contents_contents8 IN($in_video_id)
					    OR TP.contents_contents9 IN($in_video_id)
					    OR TP.contents_contents10 IN($in_video_id)
					    OR TP.contents_contents11 IN($in_video_id)
					    OR TP.contents_contents12 IN($in_video_id)
					    OR TP.contents_contents13 IN($in_video_id)
					    OR TP.contents_contents14 IN($in_video_id)
					    OR TP.contents_contents15 IN($in_video_id)
					    OR TP.contents_contents16 IN($in_video_id)
					    OR TP.contents_contents17 IN($in_video_id)
					    OR TP.contents_contents18 IN($in_video_id)
					    OR TP.contents_contents19 IN($in_video_id)
					    OR TP.contents_contents20 IN($in_video_id)
					    OR TP.contents_contents21 IN($in_video_id)
					    OR TP.contents_contents22 IN($in_video_id)
					    OR TP.contents_contents23 IN($in_video_id)
					    OR TP.contents_contents24 IN($in_video_id)
					    OR TP.contents_contents25 IN($in_video_id)
					  )
					  AND TPA.product_type_add = '1'
					 ";
					
					$sql = "SELECT";
					for($i=1; $i<=25; $i++){
						$sql.= "   TP.contents_contents$i,";
					}
					$sql.= "   TP.product_id,";
					$sql.= "   TP.product_name,";
					$sql.= "   TP.product_name AS product_name_TP,";
					$sql.= "   TP.start_date,";
					$sql.= "   TP.end_date,";
					$sql.= "   TP.thumbnail,";
					$sql.= "   TP.contents_thumbnail1,";
					$sql.= "   ( SELECT MAX(reading_date) FROM report_user_video_viewed WHERE student_id = '".$student_id."' AND video_id IN( 
							TP.contents_contents1,
							TP.contents_contents2,
							TP.contents_contents3,
							TP.contents_contents4,
							TP.contents_contents5,
							TP.contents_contents6,
							TP.contents_contents7,
							TP.contents_contents8,
							TP.contents_contents9,
							TP.contents_contents10,
							TP.contents_contents11,
							TP.contents_contents12,
							TP.contents_contents13,
							TP.contents_contents14,
							TP.contents_contents15,
							TP.contents_contents16,
							TP.contents_contents17,
							TP.contents_contents18,
							TP.contents_contents19,
							TP.contents_contents20,
							TP.contents_contents21,
							TP.contents_contents22,
							TP.contents_contents23,
							TP.contents_contents24,
							TP.contents_contents25 
									 ) AND complete_flag = '0' AND percent >= 1 AND percent <= 99 ) AS TSUB_reading_date,";
					$sql.= " (SELECT TOD.product_name FROM tbl_order_detail AS TOD WHERE TOD.member_id = '".$student_id."' AND TOD.product_id = TP.product_id ORDER BY TOD.order_detail_id DESC LIMIT 1) AS product_name_TOD";
					$sql.= " FROM";
					$sql.= "   tbl_product AS TP";
					$sql.= "     LEFT JOIN";
					$sql.= "   tbl_product_add AS TPA";
					$sql.= "       ON TP.product_id = TPA.product_id";
					//print("<!--[[[ product_now : 1-1 :: ".date("YmdHis")." ]]]-->");
					//print("[sql2:".$sql.$where." ORDER BY TSUB_reading_date DESC"."]\n");
					$query = $this->db->query(
						$sql.$where." ORDER BY TSUB_reading_date DESC",
						array()
					);
					if ($query->num_rows() > 0) {
						$ret = $query->result_array();

						//print("<!--[[[ product_now : 1-2 :: ".date("YmdHis")." ]]]-->");
						$arr_sort = array();
						
						foreach ($ret as $ret_key => $ret_val){
							$arr_list[$ret_key] = $ret_val;
							
							$res_duration_reading   = false; // 視聴済み時間取得用
							$all_alfstream_duration = '00:00:00'; // 総再生時間計算用
							$all_duration_reading   = '00:00:00'; // 総視聴済み時間計算用
							$prev_percent = 0; // 最大閲覧率の最大値判断用
							$first_flg = true;
							$all_complete_flg = true;
							$koukai_flg = false;
							
							for($i=1; $i<=25; $i++){
								if ($ret_val["contents_contents$i"] != ''){
									// 総再生時間
									$sql = "SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".$ret_val["contents_contents$i"]."'";
									//print("<!--[[[ product_now : 2-1 :: ".date("YmdHis")." ]]]-->");
									//print("[sql3:".$sql."]\n");
									$query = $this->db->query(
										$sql,
										array()
									);
									if ($query->num_rows() > 0) {
										$res_alfstream_duration = $query->row_array();
									}
									//print("<!--[[[ product_now : 2-2 :: ".date("YmdHis")." ]]]-->");
									if ($res_alfstream_duration){
										$all_alfstream_duration = $this->getTimeAddition($all_alfstream_duration, $res_alfstream_duration["alfstream_duration"]);
									}
									$arr_list[$ret_key]["total_duration"] = $all_alfstream_duration;
									
									// 視聴済時間の総計
									if ($ret_val["contents_contents$i"] != ''){
										$sql = "SELECT duration_reading, percent, UNIX_TIMESTAMP(reading_date) AS u_reading_date FROM report_user_video_viewed WHERE student_id = '$student_id' AND video_id = '".$ret_val["contents_contents$i"]."'";
										//print("<!--[[[ product_now : 3-1 :: ".date("YmdHis")." ]]]-->");
										//print("[sql4:".$sql."]\n");
										$query = $this->db->query(
											$sql,
											array()
										);
										if ($query->num_rows() > 0) {
											$res_duration_reading = $query->row_array();
										}
										//print("<!--[[[ product_now : 3-2 :: ".date("YmdHis")." ]]]-->");
										if ($res_duration_reading){
											// 視聴時間
											$all_duration_reading = $this->getTimeAddition($all_duration_reading, $res_duration_reading["duration_reading"]);
											// 直近再生日時
											if ($first_flg){
												$u_reading_date = $res_duration_reading["u_reading_date"];
												$first_flg = false;
											} else {
												if ($u_reading_date < $res_duration_reading["u_reading_date"]){
													$u_reading_date = $res_duration_reading["u_reading_date"];
												}
											}
											$arr_list[$ret_key]["reading_date"] = date('Y/m/d', $u_reading_date);
										}
									}
									
									// 全ての講座を見たか
									if ($all_complete_flg){
										$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$student_id."' AND video_id = '".$ret_val["contents_contents$i"]."' AND complete_flag = '1'";
										//print("<!--[[[ product_now : 4-1 :: ".date("YmdHis")." ]]]-->");
										//print("[sql5:".$sql."]\n");
										$query = $this->db->query(
											$sql,
											array()
										);
										if ($query->num_rows() > 0) {
											$res_count = $query->row_array();
										}
										//print("<!--[[[ product_now : 4-2 :: ".date("YmdHis")." ]]]-->");
										if ($res_count['c']==0){
											$all_complete_flg = false;
										}
									}
									
								} else {
									break;
								}
							}
							
							//// 完了済みのデータの場合削除し、ループ続行
							//if ($all_complete_flg){
							//	unset($arr_list[$ret_key]);
							//	continue;
							//}
							
							$arr_list[$ret_key]["all_remaining"] = $this->getTimeSubtraction($all_alfstream_duration, $all_duration_reading);
							$arr_list[$ret_key]["all_duration_reading"] = $all_duration_reading;
							
							// 受講率計算
							if ($all_complete_flg){
								$arr_list[$ret_key]["max_percent"] = 100;
							} else {
								$arr_list[$ret_key]["max_percent"] = 0;
								
								$in_video_id = '';
								$ret_tbl_product = array();
								$sql = "SELECT ";
								for($k=1; $k<=25; $k++){
									$sql.= " contents_contents$k,";
								}
								$sql = rtrim($sql, ',');
								$sql.= " FROM";
								$sql.= "   tbl_product";
								$sql.= " WHERE";
								$sql.= "   product_id = '".$ret_val['product_id']."'";
								//print("<!--[[[ product_now : 5-1 :: ".date("YmdHis")." ]]]-->");
								//print("[sql6:".$sql."]\n");
								$query = $this->db->query(
									$sql,
									array()
								);
								if ($query->num_rows() > 0) {
									$ret_tbl_product = $query->result_array();
								}
								//print("<!--[[[ product_now : 5-2 :: ".date("YmdHis")." ]]]-->");
								if ($ret_tbl_product){
									for($l=1; $l<=25; $l++){
										if ($ret_tbl_product[0]["contents_contents$l"] != ''){
											$in_video_id.= $ret_tbl_product[0]["contents_contents$l"] . ',';
										}
									}
									$in_video_id = rtrim($in_video_id, ',');
									
									$ret_report_user_video_viewed = array();
									$sql = "
									SELECT
									  TIME_TO_SEC(duration) AS duration_sec,
									  TIME_TO_SEC(duration_reading) AS duration_reading_sec,
									  complete_flag
									FROM
									  report_user_video_viewed
									WHERE
									  student_id = '$student_id'
									  AND video_id IN($in_video_id)
									";
									//print("<!--[[[ product_now : 6-1 :: ".date("YmdHis")." ]]]-->");
									//print("[sql7:".$sql."]\n");
									$query = $this->db->query(
										$sql,
										array()
									);
									if ($query->num_rows() > 0) {
										$ret_report_user_video_viewed = $query->result_array();
									}
									//print("<!--[[[ product_now : 6-2 :: ".date("YmdHis")." ]]]-->");
									if ($ret_report_user_video_viewed){
										// 動画を見終わっているかどうかで、視聴済み時間を変更
										// complete_flag=0：視聴済み時間を使用(duration_reading)
										// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
										$video_duration_reading = 0;
										foreach ($ret_report_user_video_viewed as $aruvv_val){
											if ($aruvv_val['complete_flag'] == '1'){
												$video_duration_reading += $aruvv_val['duration_sec'];
											} else {
												$video_duration_reading += $aruvv_val['duration_reading_sec'];
											}
										}
										
										$arr_all_alfstream_duration = array();
										$sql = "SELECT TIME_TO_SEC('$all_alfstream_duration') AS all_alfstream_duration_sec";
										//print("<!--[[[ product_now : 7-1 :: ".date("YmdHis")." ]]]-->");
										//print("[sql8:".$sql."]\n");
										$query = $this->db->query(
											$sql,
											array()
										);
										if ($query->num_rows() > 0) {
											$arr_all_alfstream_duration = $query->result_array();
										}
										//print("<!--[[[ product_now : 7-2 :: ".date("YmdHis")." ]]]-->");
										if ($arr_all_alfstream_duration){
											$percent = $video_duration_reading / $arr_all_alfstream_duration[0]['all_alfstream_duration_sec'] * 100;
											if (!is_int($percent)){
												$arr_list[$ret_key]["max_percent"] = (int)round($percent);
											}
										}
									}
								}
							}
							
							// 公開期間内商品フラグ
							if ($ret_val["start_date"] == '' && $ret_val["end_date"] == ''){
								$koukai_flg = true;
								
							} else if ($ret_val["start_date"] != '' && $ret_val["end_date"] != ''){
								$u_now_date = time();
								$u_start_date = strtotime($ret_val["start_date"]);
								$u_end_date = strtotime($ret_val["end_date"]);
								if ($u_now_date >= $u_start_date && $u_now_date <= $u_end_date){
									$koukai_flg = true;
								}
							} else if ($ret_val["end_date"] != ''){
								$u_now_date = time();
								$u_end_date = strtotime($ret_val["end_date"]);
								if ($u_now_date <= $u_end_date){
									$koukai_flg = true;
								}
							}
							$arr_list[$ret_key]["koukai_flg"] = $koukai_flg;
							
							// テストの合否・進捗(設問付きeラーニングの場合)
							$arr_list[$ret_key]["test_passing"] = '-';
							$arr_list[$ret_key]["test_progress"] = '-';
							$sql = "SELECT product_kind_flg FROM tbl_product_elearning WHERE product_id='".$ret_val['product_id']."'";
							//print("<!--[[[ product_now : 8-1 :: ".date("YmdHis")." ]]]-->");
							//print("[sql9:".$sql."]\n");
							$query = $this->db->query(
								$sql,
								array()
							);
							if ($query->num_rows() > 0) {
								$res_tbl_product_elearning = $query->row_array();
							}
							//print("<!--[[[ product_now : 8-2 :: ".date("YmdHis")." ]]]-->");
							if ($res_tbl_product_elearning){
								if($res_tbl_product_elearning['product_kind_flg']=='3'){
									$arr_list[$ret_key]["test_progress"] = '0%';
									
									// すべてのテストを受講済みかどうかチェック
									$product_contents_total = 0;
									$sql = "SELECT COUNT(*) AS count FROM rel_product_contents WHERE product_id='".$ret_val['product_id']."' AND exam_id_test>0";
									//print("<!--[[[ product_now : 9-1 :: ".date("YmdHis")." ]]]-->");
									//print("[sql10:".$sql."]\n");
									$query = $this->db->query(
										$sql,
										array()
									);
									if ($query->num_rows() > 0) {
										$res_rel_product_contents = $query->row_array();
									}
									//print("<!--[[[ product_now : 9-2 :: ".date("YmdHis")." ]]]-->");
									if($res_rel_product_contents){
										$product_contents_total = $res_rel_product_contents['count'];
									}
									
									$exam_answer_total = 0;
									$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id='".$ret_val['product_id']."' AND student_id='".$student_id."' GROUP BY contents_no";
									//print("<!--[[[ product_now : 10-1 :: ".date("YmdHis")." ]]]-->");
									//print("[sql11:".$sql."]\n");
									$query = $this->db->query(
										$sql,
										array()
									);
									$res_exam_answer = array();
									if ($query->num_rows() > 0) {
										$res_exam_answer = $query->result_array();
									}
									//print("<!--[[[ product_now : 10-2 :: ".date("YmdHis")." ]]]-->");
									$exam_answer_total = 0;
									if( count($res_exam_answer)>0 ){
										$exam_answer_total = count($res_exam_answer);
									}
									
									// すべて受講済みの場合
									$arr_list[$ret_key]["product_contents_total"] = $product_contents_total;
									$arr_list[$ret_key]["exam_answer_total"] = $exam_answer_total;
									if($product_contents_total>0 && $exam_answer_total>0 && ($product_contents_total==$exam_answer_total)){
										// 合格しているか
										$arr_list[$ret_key]["test_passing"] = '合';
										foreach($res_exam_answer as $val_rea){
											if($val_rea['passing_flg']=='0'){
												$arr_list[$ret_key]["test_passing"] = '否';
												break;
											}
										}
										$arr_list[$ret_key]["test_progress"] = '100%';
										
									// 受講済みでなく、テストが設定してある場合は進捗を算出
									} elseif($product_contents_total>0){
										if($exam_answer_total>0){
											$cal_result = $exam_answer_total / $product_contents_total * 100;
											$arr_list[$ret_key]["test_progress"] = round($cal_result).'%';
										}
									}
								}
							}

							unset( $arr_list[$ret_key]["contents_contents1"] );
							unset( $arr_list[$ret_key]["contents_contents2"] );
							unset( $arr_list[$ret_key]["contents_contents3"] );
							unset( $arr_list[$ret_key]["contents_contents4"] );
							unset( $arr_list[$ret_key]["contents_contents5"] );
							unset( $arr_list[$ret_key]["contents_contents6"] );
							unset( $arr_list[$ret_key]["contents_contents7"] );
							unset( $arr_list[$ret_key]["contents_contents8"] );
							unset( $arr_list[$ret_key]["contents_contents9"] );
							unset( $arr_list[$ret_key]["contents_contents10"] );
							unset( $arr_list[$ret_key]["contents_contents11"] );
							unset( $arr_list[$ret_key]["contents_contents12"] );
							unset( $arr_list[$ret_key]["contents_contents13"] );
							unset( $arr_list[$ret_key]["contents_contents14"] );
							unset( $arr_list[$ret_key]["contents_contents15"] );
							unset( $arr_list[$ret_key]["contents_contents16"] );
							unset( $arr_list[$ret_key]["contents_contents17"] );
							unset( $arr_list[$ret_key]["contents_contents18"] );
							unset( $arr_list[$ret_key]["contents_contents19"] );
							unset( $arr_list[$ret_key]["contents_contents20"] );
							unset( $arr_list[$ret_key]["contents_contents21"] );
							unset( $arr_list[$ret_key]["contents_contents22"] );
							unset( $arr_list[$ret_key]["contents_contents23"] );
							unset( $arr_list[$ret_key]["contents_contents24"] );
							unset( $arr_list[$ret_key]["contents_contents25"] );
							unset( $arr_list[$ret_key]["thumbnail"] );
							unset( $arr_list[$ret_key]["contents_thumbnail1"] );
						}
					}
				}
			}

			foreach ($arr_list as $arr_list_key => $arr_list_row){
				if( $arr_list[$arr_list_key]["TSUB_reading_date"]==null ){
					$arr_list[$arr_list_key]["TSUB_reading_date"] = $arr_list[$arr_list_key]["reading_date"]." 00:00:00";
				}
				$sql = "
					INSERT INTO
					 student_product_history (
					  student_id 
					 ,product_id 
					 ,total_time 
					 ,viewed_time 
					 ,last_activity 
					 ) VALUES ( 
					  ? 
					 ,? 
					 ,? 
					 ,? 
					 ,? 
					 ) ON DUPLICATE KEY UPDATE 
					  total_time=? 
					 ,viewed_time=? 
					 ,last_activity=? 
				";
				$this->db->query(
					$sql,
					array(
						$student_id,
						$arr_list[$arr_list_key]["product_id"],
						$arr_list[$arr_list_key]["total_duration"],
						$arr_list[$arr_list_key]["all_duration_reading"],
						$arr_list[$arr_list_key]["TSUB_reading_date"],
						$arr_list[$arr_list_key]["total_duration"],
						$arr_list[$arr_list_key]["all_duration_reading"],
						$arr_list[$arr_list_key]["TSUB_reading_date"],
					)
				);
			}
			//print("\n=============================\n");
			//var_dump($arr_list);
			//print("\n=============================\n");
		}

		// 終了ログ
		$this->_output_log("END insert_student_product_history");
		print "[".date('Y/m/d H:i:s')."]\n";
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

