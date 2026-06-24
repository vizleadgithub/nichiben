<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//cd cd /srv/alflearning/alflearning-cms
//php index.php bat_student_report student_report 
#[AllowDynamicProperties]
class Bat_student_report extends CI_Controller {

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		ini_set("display_errors",1);
		ini_set('error_reporting',E_ALL);

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
	// 
	//----------------------------------------------
	public function student_report($start_date = '', $end_date = ''){
		print "[".date('Y-m-d H:i:s')."]"."start student_report \n";
		//+++++++++++++++++++++++++++++++++++++++++++++++
		/*
		直近再生日時が登録済み、視聴完了フラグが完了、視聴完了日時が未登録のものに対し、
		視聴完了日時を直近再生日時、更新日を現在以降の時間に更新しています。
		*/
		$this->db->query(
			"UPDATE report_user_video_viewed SET complete_date=reading_date, update_at='".date("Y-m-d H:i:s")."' WHERE reading_date>'0000-00-00 00:00:00' AND complete_flag=1 AND complete_date IS NULL"
		);
		//+++++++++++++++++++++++++++++++++++++++++++++++
		/*
		直近再生日時が「0000-00-00 00:00:00」、視聴完了日時が登録済み、視聴率が100、視聴完了フラグが完了、登録日時が未登録のものに対し、
		直近再生日時を視聴完了日時、登録日時を視聴完了日時、更新日を現在以降の時間に更新しています。
		*/
		$this->db->query(
			"UPDATE report_user_video_viewed SET reading_date=complete_date, update_at='".date("Y-m-d H:i:s")."', regist_at=complete_date WHERE reading_date='0000-00-00 00:00:00' AND complete_date>'0000-00-00 00:00:00' AND percent=100 AND complete_flag=1 AND regist_at IS NULL"
		);
		//+++++++++++++++++++++++++++++++++++++++++++++++
		/*
		動画再生時間が「00:00:00」、動画視聴時間が「00:00:00」、視聴完了日時が登録済み、視聴完了フラグが完了、視聴率が0のものに対し、
		動画再生時間を最新の動画再生時間、動画視聴時間を動画再生時間、視聴率を100、更新日を現在以降の時間に更新しています。
		*/
		$this->db->query(
			"UPDATE report_user_video_viewed SET duration=(select video_alfstream_status.alfstream_duration from video_alfstream_status where video_alfstream_status.video_id=report_user_video_viewed.video_id), duration_reading=(select video_alfstream_status.alfstream_duration from video_alfstream_status where video_alfstream_status.video_id=report_user_video_viewed.video_id), percent=100, update_at='".date("Y-m-d H:i:s")."' WHERE duration='00:00:00' AND duration_reading='00:00:00' AND complete_date>'0000-00-00 00:00:00' AND complete_flag=1 AND percent=0"
		);
		//+++++++++++++++++++++++++++++++++++++++++++++++
		$test_flg = 1;//テスト用に集計対象を限定する場合（範囲の指定はソース内を「対象のユーザを指定する場合」で検索した箇所で）
		$one_day_flg = 1;//集計対象の日付を過去1日に限定

		$add_history_recode_flg = 1;//ベースレコード作成
		$add_history_recode_sub_flg = 1;//サブベース情報作成
		$update_product_flg = 1;//商品情報更新
		$update_exam_flg = 1;//問題情報更新
		$update_question_flg = 1;//アンケート情報更新
		$update_exam_answer_flg = 1;//回答情報更新
		$update_video_history_flg = 1;//視聴履歴更新
		$update_video_history_flg2 = 1;//視聴日時更新
		//+++++++++++++++++++++++++++++++++++++++++++++++
		exec('ps auxw | grep student_report | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		//ベースレコード修正
		print "0[".date('Y-m-d H:i:s')."]"."ベース情報修正\n";
		$sql = "";
		$sql.= "SELECT product_id FROM tbl_student_report WHERE exam_id_test='' AND passing_exam_id<>'' GROUP BY product_id";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			foreach($query->result_array() as $row){
				$sql = "";
				$sql.= "SELECT exam_id_test FROM rel_product_contents WHERE product_id = ? ORDER BY rel_product_contents.contents_no ASC ";
				$query = $this->db->query(
					$sql, 
					[
						$row["product_id"],
					]
				);
				$arr_exam_id_test = [];
				if ($query->num_rows() > 0) {
					foreach($query->result_array() as $row2){
						$arr_exam_id_test[] = $row2["exam_id_test"];
					}
				}
				$sql = "UPDATE tbl_student_report SET exam_id_test='".implode(',', $arr_exam_id_test)."' WHERE product_id='".$row["product_id"]."'";
				print("[".$sql."]\n");
				$this->db->query( $sql );
			}
		}
		//test
		//exit();
		//+++++++++++++++++++++++++++++++++++++++++++++++
		//ベースレコード作成
		print "1[".date('Y-m-d H:i:s')."]"."ベース情報\n";
		if( $add_history_recode_flg == 1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " member_id ";
			$sql.= ",product_id ";
			//$sql.= ",product_name ";
			$sql.= "FROM ";
			$sql.= "tbl_order_detail ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND member_id>0 ";
			if( $test_flg == 1 ){
				//対象のユーザを指定する場合
				//$sql.= " AND member_id>0 ";
				//$sql.= " AND member_id<=15000 ";
				//$sql.= " AND member_id=54427 ";
				//$sql.= " AND member_id=55876 ";
				$sql.= " AND member_id=43918 ";
			}
			if( $one_day_flg == 1 ){
				$sql.= " AND update_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			}
			$sql.= " GROUP BY member_id, product_id ";
			$query = $this->db->query($sql);
			print "1[".date('Y-m-d H:i:s')."]"."row ".$query->num_rows()."\n";
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $row){
					print "1[".date('Y-m-d H:i:s')."]"."[student_id:".$row["member_id"]."][product_id:".$row["product_id"]."]\n";
					$sql = "";
					$sql.= "INSERT IGNORE INTO ";
					$sql.= "tbl_student_report( ";
						$sql.= "  student_id ";
						$sql.= " ,product_id ";
						$sql.= " ,updated_at ";
					$sql.= ") VALUES ( ";
						$sql.= "  ? ";
						$sql.= " ,? ";
						$sql.= " ,? ";
					$sql.= ") ";
					$res = $this->db->query( $sql, 
						array(
							$row["member_id"],
							$row["product_id"],
							date("Y-m-d H:i:s"),
						)
					);
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "2[".date('Y-m-d H:i:s')."]"."サブベース情報\n";
		if( $add_history_recode_sub_flg == 1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " student_id ";
			$sql.= "FROM  ";
			$sql.= " report_user_video_viewed  ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND  student_id>0 ";
			if( $test_flg == 1 ){
				//対象のユーザを指定する場合
				//$sql.= " AND student_id>0 ";
				//$sql.= " AND student_id<=15000 ";

				//$sql.= " AND student_id=54427 ";
				//$sql.= " AND student_id=55876 ";
				$sql.= " AND student_id=43918 ";
			}
			if( $one_day_flg == 1 ){
				$sql.= " AND ( ";
				$sql.= " regist_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " OR update_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " ) ";
			}
			$sql.= " GROUP BY  ";
			$sql.= "  student_id ";
			$query_student = $this->db->query($sql);
			print "2[".date('Y-m-d H:i:s')."]"."query_student row ".$query_student->num_rows()."\n";
			if ($query_student->num_rows() > 0) {
				foreach($query_student->result_array() as $student_row){
					$sql = "";
					$sql.= "   SELECT ";
					$sql.= "    tbl_product.product_id ";
					$sql.= "   FROM ";
					$sql.= "    report_user_video_viewed ";
					$sql.= "    LEFT JOIN tbl_product ON ";
					$sql.= "     ( ";
					$sql.= "      tbl_product.contents_contents1 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents2 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents3 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents4 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents5 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents6 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents7 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents8 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents9 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents10 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents11 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents12 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents13 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents14 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents15 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents16 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents17 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents18 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents19 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents20 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents21 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents22 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents23 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents24 = report_user_video_viewed.video_id ";
					$sql.= "      OR tbl_product.contents_contents25 = report_user_video_viewed.video_id ";
					$sql.= "     ) ";
					$sql.= "   WHERE 1=1 ";
					$sql.= "    AND tbl_product.product_id ";
					$sql.= "    AND report_user_video_viewed.student_id=? ";
					if( $one_day_flg == 1 ){
						$sql.= " AND ( ";
						$sql.= " report_user_video_viewed.regist_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
						$sql.= " OR report_user_video_viewed.update_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
						$sql.= " ) ";
					}
					$sql.= "   GROUP BY ";
					$sql.= "    tbl_product.product_id ";
					$query_product = $this->db->query( $sql, 
						array(
							$student_row["student_id"],
						)
					);
					if ($query_product->num_rows() > 0) {
						foreach($query_product->result_array() as $product_row){
							print "2[".date('Y-m-d H:i:s')."][student_id:".$student_row["student_id"]."][product_id:".$product_row["product_id"]."]"."report_user_video_viewed row ".$query_product->num_rows()."\n";
							$sql = "";
							$sql.= "INSERT IGNORE INTO ";
							$sql.= "tbl_student_report( ";
								$sql.= "  student_id ";
								$sql.= " ,product_id ";
								$sql.= " ,updated_at ";
							$sql.= ") VALUES ( ";
								$sql.= "  ? ";
								$sql.= " ,? ";
								$sql.= " ,? ";
							$sql.= ") ";
							$res = $this->db->query( $sql, 
								array(
									$student_row["student_id"],
									$product_row["product_id"],
									date("Y-m-d H:i:s"),
								)
							);
						}
					}
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "3[".date('Y-m-d H:i:s')."]"."商品情報\n";
		//商品情報
		if( $update_product_flg==1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " tbl_product.product_id ";
			$sql.= ",tbl_product.product_name ";
			$sql.= ",tbl_product.contents_contents1 ";
			$sql.= ",tbl_product.contents_contents2 ";
			$sql.= ",tbl_product.contents_contents3 ";
			$sql.= ",tbl_product.contents_contents4 ";
			$sql.= ",tbl_product.contents_contents5 ";
			$sql.= ",tbl_product.contents_contents6 ";
			$sql.= ",tbl_product.contents_contents7 ";
			$sql.= ",tbl_product.contents_contents8 ";
			$sql.= ",tbl_product.contents_contents9 ";
			$sql.= ",tbl_product.contents_contents10 ";
			$sql.= ",tbl_product.contents_contents11 ";
			$sql.= ",tbl_product.contents_contents12 ";
			$sql.= ",tbl_product.contents_contents13 ";
			$sql.= ",tbl_product.contents_contents14 ";
			$sql.= ",tbl_product.contents_contents15 ";
			$sql.= ",tbl_product.contents_contents16 ";
			$sql.= ",tbl_product.contents_contents17 ";
			$sql.= ",tbl_product.contents_contents18 ";
			$sql.= ",tbl_product.contents_contents19 ";
			$sql.= ",tbl_product.contents_contents20 ";
			$sql.= ",tbl_product.contents_contents21 ";
			$sql.= ",tbl_product.contents_contents22 ";
			$sql.= ",tbl_product.contents_contents23 ";
			$sql.= ",tbl_product.contents_contents24 ";
			$sql.= ",tbl_product.contents_contents25 ";
			$sql.= ",tbl_product.exam2_id ";
			$sql.= ",tbl_product_add.product_type_add ";
			$sql.= "FROM ";
			$sql.= " tbl_product ";
			$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
			$sql.= "WHERE 1=1 ";
			if( $one_day_flg == 1 ){
				$sql.= " AND tbl_product.update_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			}
			$query = $this->db->query($sql);
			print "3[".date('Y-m-d H:i:s')."]"."tbl_product row ".$query->num_rows()."\n";
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $row){
					$video_ids = array();
					for($i=1;$i<=25;$i++){
						if( trim($row["contents_contents".$i])!="" && intval($row["contents_contents".$i])>0 ){
							$video_ids[] = $row["contents_contents".$i];
						}
					}
					$exam2_id = 0;
					if( intval($row["exam2_id"])>0 ){
						$exam2_id = intval($row["exam2_id"]);
					}
					$product_type_add = 0;//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
					if( intval($row["product_type_add"])>0 ){
						$product_type_add = intval($row["product_type_add"]);
					}

					//print "3[".date('Y-m-d H:i:s')."]"."[product_id:".$row["product_id"]."][product_type_add:".$row["product_type_add"]."][video_ids:".implode(",",$video_ids)."][exam2_id:".$exam2_id."]\n";
					$sql = "";
					$sql.= "UPDATE ";
					$sql.= " tbl_student_report ";
					$sql.= "SET ";
						$sql.= "  product_name=? ";
						$sql.= " ,product_type_add=? ";
						$sql.= " ,video_ids=? ";
						$sql.= " ,exam2_id=? ";
						$sql.= " ,updated_at=? ";
					$sql.= "WHERE ";
						$sql.= "  product_id=? ";
					$res = $this->db->query( $sql, 
						array(
							$row["product_name"],
							$row["product_type_add"],
							implode(",",$video_ids),
							$exam2_id,
							date("Y-m-d H:i:s"),

							$row["product_id"],
						)
					);

				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "3-2[".date('Y-m-d H:i:s')."]"."商品情報2\n";
		//商品情報
		if( $update_product_flg==1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " tbl_product.product_id ";
			$sql.= ",tbl_product.product_name ";
			$sql.= ",tbl_product.contents_contents1 ";
			$sql.= ",tbl_product.contents_contents2 ";
			$sql.= ",tbl_product.contents_contents3 ";
			$sql.= ",tbl_product.contents_contents4 ";
			$sql.= ",tbl_product.contents_contents5 ";
			$sql.= ",tbl_product.contents_contents6 ";
			$sql.= ",tbl_product.contents_contents7 ";
			$sql.= ",tbl_product.contents_contents8 ";
			$sql.= ",tbl_product.contents_contents9 ";
			$sql.= ",tbl_product.contents_contents10 ";
			$sql.= ",tbl_product.contents_contents11 ";
			$sql.= ",tbl_product.contents_contents12 ";
			$sql.= ",tbl_product.contents_contents13 ";
			$sql.= ",tbl_product.contents_contents14 ";
			$sql.= ",tbl_product.contents_contents15 ";
			$sql.= ",tbl_product.contents_contents16 ";
			$sql.= ",tbl_product.contents_contents17 ";
			$sql.= ",tbl_product.contents_contents18 ";
			$sql.= ",tbl_product.contents_contents19 ";
			$sql.= ",tbl_product.contents_contents20 ";
			$sql.= ",tbl_product.contents_contents21 ";
			$sql.= ",tbl_product.contents_contents22 ";
			$sql.= ",tbl_product.contents_contents23 ";
			$sql.= ",tbl_product.contents_contents24 ";
			$sql.= ",tbl_product.contents_contents25 ";
			$sql.= ",tbl_product.exam2_id ";
			$sql.= ",tbl_product_add.product_type_add ";
			$sql.= "FROM ";
			$sql.= " tbl_product ";
			$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND tbl_product.product_id IN (SELECT tbl_student_report.product_id FROM tbl_student_report WHERE 1=1 AND tbl_student_report.product_id>0 AND tbl_student_report.product_type_add=0 ) ";
			$query = $this->db->query($sql);
			print "3-2[".date('Y-m-d H:i:s')."]"."tbl_product row ".$query->num_rows()."\n";
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $row){
					$video_ids = array();
					for($i=1;$i<=25;$i++){
						if( trim($row["contents_contents".$i])!="" && intval($row["contents_contents".$i])>0 ){
							$video_ids[] = $row["contents_contents".$i];
						}
					}
					$exam2_id = 0;
					if( intval($row["exam2_id"])>0 ){
						$exam2_id = intval($row["exam2_id"]);
					}
					$product_type_add = 0;//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
					if( intval($row["product_type_add"])>0 ){
						$product_type_add = intval($row["product_type_add"]);
					}

					//print "3-2[".date('Y-m-d H:i:s')."]"."[product_id:".$row["product_id"]."][product_type_add:".$row["product_type_add"]."][video_ids:".implode(",",$video_ids)."][exam2_id:".$exam2_id."]\n";
					$sql = "";
					$sql.= "UPDATE ";
					$sql.= " tbl_student_report ";
					$sql.= "SET ";
						$sql.= "  product_name=? ";
						$sql.= " ,product_type_add=? ";
						$sql.= " ,video_ids=? ";
						$sql.= " ,exam2_id=? ";
						$sql.= " ,updated_at=? ";
					$sql.= "WHERE ";
						$sql.= "  product_id=? ";
					$res = $this->db->query( $sql, 
						array(
							$row["product_name"],
							$row["product_type_add"],
							implode(",",$video_ids),
							$exam2_id,
							date("Y-m-d H:i:s"),

							$row["product_id"],
						)
					);

				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "4[".date('Y-m-d H:i:s')."]"."問題情報\n";
		//問題情報
		if( $update_exam_flg==1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "  product_id ";
			$sql.= "FROM ";
			$sql.= "  tbl_product ";
			$sql.= "WHERE 1=1 ";
			if( $one_day_flg == 1 ){
				$sql.= " AND tbl_product.update_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			}
			$query_product = $this->db->query($sql);
			if ($query_product->num_rows() > 0) {
				foreach($query_product->result_array() as $product_row){
					$sql = "";
					$sql.= "SELECT ";
					$sql.= "  product_id ";
					$sql.= ", GROUP_CONCAT( exam_id_test ) AS exam_id_test ";
					$sql.= "FROM ";
					$sql.= "  rel_product_contents ";
					$sql.= "WHERE 1=1 ";
					$sql.= " AND exam_id_test>0 ";
					$sql.= " AND product_id=? ";
					$sql.= "GROUP BY product_id ";
					$query = $this->db->query($sql, array(
						$product_row["product_id"],
					));
					print "4[".date('Y-m-d H:i:s')."]"."rel_product_contents row ".$query->num_rows()."\n";
					if ($query->num_rows() > 0) {
						foreach($query->result_array() as $row){
							$sql = "";
							$sql.= "UPDATE ";
							$sql.= " tbl_student_report ";
							$sql.= "SET ";
								$sql.= "  exam_id_test=? ";
								$sql.= " ,updated_at=? ";
							$sql.= "WHERE ";
								$sql.= "  product_id=? ";
							$res = $this->db->query( $sql, 
								array(
									$row["exam_id_test"],
									date("Y-m-d H:i:s"),

									$row["product_id"],
								)
							);
						}
					}
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		//アンケート情報
		print "[".date('Y-m-d H:i:s')."]"."アンケート情報\n";
		if( $update_question_flg==1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "  product_id ";
			$sql.= ",GROUP_CONCAT( exam_id_question ) AS exam_id_question ";
			$sql.= "FROM ";
			$sql.= "  rel_product_contents ";
			$sql.= "WHERE 1=1 ";
			$sql.= "  AND exam_id_question>0 ";
			$sql.= "GROUP BY product_id ";
			$query = $this->db->query($sql);
			print "[".date('Y-m-d H:i:s')."]"."row ".$query->num_rows()."\n";
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $row){
					$sql = "";
					$sql.= "UPDATE ";
					$sql.= " tbl_student_report ";
					$sql.= "SET ";
						$sql.= "  exam_id_question=? ";
						$sql.= " ,updated_at=? ";
					$sql.= "WHERE ";
						$sql.= "  product_id=? ";
					$res = $this->db->query( $sql, 
						array(
							$row["exam_id_question"],
							date("Y-m-d H:i:s"),

							$row["product_id"],
						)
					);
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "[".date('Y-m-d H:i:s')."]"."回答情報\n";
		if( $update_exam_answer_flg == 1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "  student_id ";
			$sql.= " ,product_id ";
			$sql.= "FROM ";
			$sql.= " exam_answer ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND question_flg=0 ";
			if( $test_flg == 1 ){
				//対象のユーザを指定する場合
				//$sql.= " AND student_id>0 ";
				//$sql.= " AND student_id<=15000 ";

				//$sql.= " AND student_id=54427 ";
				//$sql.= " AND student_id=55876 ";
				$sql.= " AND student_id=43918 ";
			}
			if( $one_day_flg == 1 ){
				$sql.= " AND exam_answer_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			}
			$sql.= " GROUP BY ";
			$sql.= "  student_id ";
			$sql.= " ,product_id ";
			$query = $this->db->query($sql);

			print "[".date('Y-m-d H:i:s')."]"."exam_answer row ".$query->num_rows()."\n";
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $row){
					$student_id = $row["student_id"];
					$product_id = $row["product_id"];
					$sql = "";
					$sql.= "SELECT ";
					$sql.= "  student_id ";
					$sql.= " ,product_id ";
					$sql.= " ,GROUP_CONCAT( exam_id ) AS passing_exam_id ";
					$sql.= "FROM ";
					$sql.= "( ";
						$sql.= "SELECT ";
						$sql.= "  student_id ";
						$sql.= " ,product_id ";
						$sql.= " ,exam_id ";
						$sql.= "FROM ";
						$sql.= " exam_answer ";
						$sql.= "WHERE 1=1 ";
						$sql.= " AND student_id=? ";
						$sql.= " AND product_id=? ";
						$sql.= " AND passing_flg=1 ";
						$sql.= " AND question_flg=0 ";
						$sql.= "GROUP BY ";
						$sql.= "  student_id ";
						$sql.= " ,product_id ";
						$sql.= " ,exam_id ";
					$sql.= ") AS tbl ";
					$sql.= "WHERE 1=1 ";
					$sql.= " AND student_id=? ";
					$sql.= " AND product_id=? ";
					$sql.= "GROUP BY ";
					$sql.= "  student_id ";
					$sql.= " ,product_id ";
					$query2 = $this->db->query( $sql, 
						array(
							$student_id,
							$product_id,

							$student_id,
							$product_id,
						)
					);
					foreach($query2->result_array() as $row2){
						$sql = "";
						$sql.= "UPDATE ";
						$sql.= " tbl_student_report ";
						$sql.= "SET ";
						$sql.= "  passing_exam_id=? ";
						$sql.= " ,updated_at=? ";
						$sql.= "WHERE 1=1 ";
						$sql.= " AND student_id=? ";
						$sql.= " AND product_id=? ";
						$res = $this->db->query( $sql, 
							array(
								$row2["passing_exam_id"],
								date("Y-m-d H:i:s"),

								$student_id,
								$product_id,
							)
						);
					}
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "[".date('Y-m-d H:i:s')."]"."視聴履歴\n";
		if( $update_video_history_flg == 1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " student_id ";
			$sql.= " ,video_id ";
			$sql.= "FROM  ";
			$sql.= " report_user_video_viewed  ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND  student_id>0 ";
			if( $test_flg == 1 ){
				//対象のユーザを指定する場合
				//$sql.= " AND student_id>0 ";
				//$sql.= " AND student_id<=15000 ";

				//$sql.= " AND student_id=54427 ";
				//$sql.= " AND student_id=55876 ";
				$sql.= " AND student_id=43918 ";
			}
			if( $one_day_flg == 1 ){
				$sql.= " AND ( ";
				$sql.= " regist_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " OR update_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " ) ";
			}
			$sql.= " GROUP BY  ";
			$sql.= "  student_id ";
			//$sql.= " LIMIT 100 ";
			//if( $one_day_flg == 1 ){
			//	$sql.= " AND update_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			//}
			print "[".date('Y-m-d H:i:s')."]".$sql."\n";
			$query_student = $this->db->query($sql);
			print "[".date('Y-m-d H:i:s')."]"."query_student row ".$query_student->num_rows()."\n";
			if ($query_student->num_rows() > 0) {
				foreach($query_student->result_array() as $student_row){
					print "[".date('Y-m-d H:i:s')."]"."row student_id:".$student_row["student_id"]." \n";
					//+++++++++++++++++++++++++++++++++++++++++++++++
					$now_date = date("Y-m-d h:i:s");
					$student_id = $student_row["student_id"];
					//+++++++++++++++++++++++++++++++++++++++++++++++
					//受講者毎、最近の視聴完了したビデオ
					$sql = "";
					$sql.= "SELECT  ";
					$sql.= "  student_id ";
					$sql.= " ,video_id ";
					//$sql.= " ,max(duration) AS duration   ";
					//$sql.= " ,max(duration_reading) AS duration_reading  ";
					//$sql.= " ,max(percent) AS percent ";
					//$sql.= " ,max(complete_flag) AS complete_flag ";
					$sql.= "FROM  ";
					$sql.= " report_user_video_viewed  ";
					$sql.= "WHERE 1=1 ";
					$sql.= " AND  video_id>0 ";
					$sql.= " AND  student_id>0 ";
					$sql.= " AND  student_id=? ";
					if( $one_day_flg == 1 ){
						$sql.= " AND ( ";
						$sql.= "    complete_date >'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
						$sql.= " OR regist_at >'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
						$sql.= " OR update_at>'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
						$sql.= " ) ";
					}
					$sql.= " GROUP BY  ";
					$sql.= "  student_id ";
					$sql.= " ,video_id ";
					//$sql.= " LIMIT 1 ";
					$query1 = $this->db->query($sql, array($student_row["student_id"]));
					print "[".date('Y-m-d H:i:s')."]"."[student_id:".$student_row["student_id"]."]report_user_video_viewed row ".$query1->num_rows()."\n";
					//視聴完了したビデオが含まれる商品毎の履歴
					if ($query1->num_rows() > 0) {
						foreach($query1->result_array() as $row1){
							//var_dump($row1);
							$student_id = $row1["student_id"];
							$video_id = $row1["video_id"];
							//print("[student_id:".$student_id."]");
							//print("[video_id:".$video_id."]");
							//print("\n");
							$sql = "";
							$sql.= "select ";
							$sql.= "  product_id ";
							$sql.= " ,video_ids ";
							$sql.= "FROM ";
							$sql.= " tbl_student_report ";
							$sql.= "WHERE 1=1 ";
							$sql.= " AND student_id=? ";
							$sql.= " AND find_in_set(?, video_ids) ";
							$query2 = $this->db->query( $sql, 
								array(
									$student_id,
									$video_id,
								)
							);
							print("[student_id:".$student_id."][video_id:".$video_id."][query2 num_rows:".$query2->num_rows()."]\n");
							//受講者、商品ごとの集計
							if ($query2->num_rows() > 0) {
								print("[student_id:".$student_id."]");
								print("[video_id:".$video_id."]");
								print("[tbl_student_report row ".$query2->num_rows()."]\n");
								foreach($query2->result_array() as $row2){
									print("tbl_student_report row loop\n");
									//var_dump($row2);
									$product_id = $row2["product_id"];
									$video_ids = $row2["video_ids"];//商品に含まれるビデオID
									//print("[product_id:".$product_id."]");
									//print("[video_ids:".$video_ids."]");
									$arr_video_id = explode ( ",", $video_ids );
									

									// 商品の動画視聴完了数の取得
									if ( !empty($video_ids) && trim($video_ids) != ''){
										$arr_report_user_video_viewed = array();
										$sql = "SELECT GROUP_CONCAT(video_id) AS viewd_video_ids FROM report_user_video_viewed WHERE student_id = ? AND video_id IN(" . $video_ids . ") AND complete_flag = 1";
										$query_record1 = $this->db->query($sql, 
												array(
													$student_id
												)
										);
										print "[".date('Y-m-d H:i:s')."]"."[student_id:".$student_id."]report_user_video_viewed row ".$query_record1->num_rows()."\n";
										//受講者、商品毎の視聴完了したビデオID
										if ($query_record1->num_rows() > 0){
											foreach($query_record1->result_array() as $row3){
												print("report_user_video_viewed viewd_video_ids loop\n");
												//var_dump($row3);
												print("[update][student_id:".$student_id."][product_id:".$product_id."][viewd_video_ids:".$row3["viewd_video_ids"]."]\n");
												$sql = "";
												$sql.= "UPDATE ";
												$sql.= " tbl_student_report ";
												$sql.= "SET ";
												$sql.= "  viewd_video_ids=? ";
												$sql.= " ,updated_at=? ";
												$sql.= "WHERE 1=1 ";
												$sql.= " AND student_id=? ";
												$sql.= " AND product_id=? ";
												$res = $this->db->query( $sql, 
													array(
														$row3["viewd_video_ids"],
														date("Y-m-d H:i:s"),

														$student_id,
														$product_id,
													)
												);
											}
										}

										
										// 全て動画を見終わっていない場合、視聴済み時間の更新
										// complete_flag=0：視聴済み時間を使用(duration_reading)
										// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
										$sql = "SELECT 
											  TIME_TO_SEC(duration) AS duration_sec
											, TIME_TO_SEC(duration_reading) AS duration_reading_sec
											, complete_flag 
										 FROM 
											 report_user_video_viewed 
										WHERE 1=1
											 AND student_id = ? 
											 AND video_id IN (" . $video_ids . ")
										 ";
										$query_record2 = $this->db->query($sql, 
												array(
													$student_id,
												)
										);
										//var_dump($sql);
										//var_dump($student_id);
										if ($query_record2->num_rows() > 0){
											$all_duration_reading = 0;
											foreach ($query_record2->result_array() as $row3){
												print("report_user_video_viewed TIME_TO_SEC loop\n");
												//var_dump($row3);
												if ($row3['complete_flag'] == '1'){
													if( intval($row3['duration_sec'])>0 ){
														$all_duration_reading += intval($row3['duration_sec']);
													}
												} else {
													if( intval($row3['duration_reading_sec'])>0 ){
														$all_duration_reading += intval($row3['duration_reading_sec']);
													}
												}
												print("[update][student_id:".$student_id."][product_id:".$product_id."][all_duration_reading:".$all_duration_reading."]\n");
												$sql = "";
												$sql.= "UPDATE ";
												$sql.= " tbl_student_report ";
												$sql.= "SET ";
												$sql.= "  duration_reading_sec=? ";
												$sql.= " ,updated_at=? ";
												$sql.= "WHERE 1=1 ";
												$sql.= " AND student_id=? ";
												$sql.= " AND product_id=? ";
												$res = $this->db->query( $sql, 
													array(
														$all_duration_reading,
														date("Y-m-d H:i:s"),

														$student_id,
														$product_id,
													)
												);
											}
										}

										//総再生時間
										$sql = "";
										$sql.= "SELECT ";
										$sql.= " TIME_TO_SEC(alfstream_duration) AS duration_sec ";
										$sql.= "FROM ";
										$sql.= " video_alfstream_status ";
										$sql.= "WHERE 1=1 ";
										$sql.= " AND video_id IN (" . $video_ids . ") ";
										$query_record3 = $this->db->query($sql, 
												array(
												)
										);
										if ($query_record3->num_rows() > 0){
											$all_duration = 0;
											foreach ($query_record3->result_array() as $row3){
												//var_dump($row3);
												if( intval($row3['duration_sec'])>0 ){
													$all_duration += intval($row3['duration_sec']);
												}
											}
											print("[update][student_id:".$student_id."][product_id:".$product_id."][all_duration:".$all_duration."]\n");
											$sql = "";
											$sql.= "UPDATE ";
											$sql.= " tbl_student_report ";
											$sql.= "SET ";
											$sql.= "  duration_sec=? ";
											$sql.= " ,updated_at=? ";
											$sql.= "WHERE 1=1 ";
											$sql.= " AND student_id=? ";
											$sql.= " AND product_id=? ";
											$res = $this->db->query( $sql, 
												array(
													$all_duration,
													date("Y-m-d H:i:s"),

													$student_id,
													$product_id,
												)
											);
										}

									}
								}
							}
						}
					}
					//+++++++++++++++++++++++++++++++++++++++++++++++
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "[".date('Y-m-d H:i:s')."]"."視聴時間履歴\n";
		if( $update_video_history_flg2 == 1 ){
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " student_id ";
			$sql.= " ,video_id ";
			$sql.= "FROM  ";
			$sql.= " report_user_video_viewed  ";
			$sql.= "WHERE 1=1 ";
			$sql.= " AND  student_id>0 ";
			if( $test_flg == 1 ){
				//対象のユーザを指定する場合
				//$sql.= " AND student_id>0 ";
				//$sql.= " AND student_id<=15000 ";

				//$sql.= " AND student_id=54427 ";
				//$sql.= " AND student_id=55876 ";
				$sql.= " AND student_id=43918 ";
			}
			if( $one_day_flg == 1 ){
				$sql.= " AND ( ";
				$sql.= " regist_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " OR update_at>'".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
				$sql.= " ) ";
			}
			$sql.= " GROUP BY  ";
			$sql.= "  student_id ";
			$sql.= " ,video_id ";
			//$sql.= " LIMIT 100 ";
			//if( $one_day_flg == 1 ){
			//	$sql.= " AND update_date>='".date("Y-m-d H:i:s",strtotime(" -2 day "))."' ";
			//}
			print "[".date('Y-m-d H:i:s')."]".$sql."\n";
			$query_student = $this->db->query($sql);
			print "[".date('Y-m-d H:i:s')."]"."query_student row ".$query_student->num_rows()."\n";
			if ($query_student->num_rows() > 0) {
				foreach($query_student->result_array() as $student_row){
					//+++++++++++++++++++++++++++++++++++++++++++++++
					$now_date = date("Y-m-d h:i:s");
					$student_id = $student_row["student_id"];
					$video_id = $student_row["video_id"];
					//+++++++++++++++++++++++++++++++++++++++++++++++
					$sql = "";
					$sql.= "select ";
					$sql.= "  product_id ";
					$sql.= " ,video_ids ";
					$sql.= "FROM ";
					$sql.= " tbl_student_report ";
					$sql.= "WHERE 1=1 ";
					$sql.= " AND student_id=? ";
					$sql.= " AND find_in_set(?, video_ids) ";
					print "[".date('Y-m-d H:i:s')."]".$sql."\n";
					$query_product = $this->db->query( $sql, 
						array(
							$student_id,
							$video_id,
						)
					);
					if ($query_product->num_rows() > 0) {
						foreach($query_product->result_array() as $product_row){
							//+++++++++++++++++++++++++++++++++++++++++++++++
							$product_id = $product_row["product_id"];
							$video_ids = $product_row["video_ids"];
							//+++++++++++++++++++++++++++++++++++++++++++++++
							if( trim($video_ids)!="" ){
								$sql = "";
								$sql.= "SELECT  ";
								$sql.= "  sum( TIME_TO_SEC(duration) ) AS duration   ";
								$sql.= " ,sum( TIME_TO_SEC(duration_reading) ) AS duration_reading  ";
								$sql.= " ,max(reading_date) AS reading_date ";
								$sql.= " ,max(complete_date) AS complete_date ";
								$sql.= "FROM  ";
								$sql.= " report_user_video_viewed  ";
								$sql.= "WHERE 1=1 ";
								$sql.= " AND  video_id>0 ";
								$sql.= " AND  student_id>0 ";
								$sql.= " AND  student_id=? ";
								$sql.= " AND  video_id IN (".$video_ids.") ";
								//if( $one_day_flg == 1 ){
								//	$sql.= " AND ( ";
								//	$sql.= "    regist_at>'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
								//	$sql.= " OR complete_date>'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
								//	$sql.= " OR reading_date>'".date("Y-m-d H:i:s",strtotime(" -91 day "))."' ";
								//	$sql.= " ) ";
								//}
								print "[".date('Y-m-d H:i:s')."]".$sql."\n";
								$query1 = $this->db->query($sql, array($student_row["student_id"]));
								//視聴完了したビデオが含まれる商品毎の履歴
								if ($query1->num_rows() > 0) {
									foreach($query1->result_array() as $row1){
										//var_dump($row1);
										$duration = $row1["duration"];
										$duration_reading = $row1["duration_reading"];
										$reading_date = $row1["reading_date"];
										$complete_date = $row1["complete_date"];
										$last_viewd_date = $reading_date;
										if( $last_viewd_date<=$complete_date ){
											$last_viewd_date = $complete_date;
										}
										$sql = "";
										$sql.= "UPDATE ";
										$sql.= " tbl_student_report ";
										$sql.= "SET ";
										$sql.= "  last_viewd_date=? ";
										//$sql.= " ,duration_sec=? ";
										//$sql.= " ,duration_reading_sec=? ";
										$sql.= " ,updated_at=? ";
										$sql.= "WHERE 1=1 ";
										$sql.= " AND student_id=? ";
										$sql.= " AND product_id=? ";
										print "[".date('Y-m-d H:i:s')."]".$sql."\n";
										$res = $this->db->query( $sql, 
											array(
												$last_viewd_date,
												//$duration,
												//$duration_reading,
												date("Y-m-d H:i:s"),

												$student_id,
												$product_id,
											)
										);
									}
								}
							}
						}
					}
					//+++++++++++++++++++++++++++++++++++++++++++++++
				}
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++++
		print "[".date('Y-m-d H:i:s')."]"."end 'student_report'\n";
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
