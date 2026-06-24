<?php
$debag = true;
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 
$school_id = 1;
$nowdate = date("Y-m-d H:i:s");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_GET['pid'])){
	$objDbConnect->close();
	if( $debag ){ print("\n<hr>\nnon pid\n<hr>\n"); }
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	if( $debag ){ print("\n<hr>\npid err\n<hr>\n"); }
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ログインチェック
$st_login_check = st_login_check();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ユーザー情報の取得
$user_list = array();
if ($st_login_check){
	$sql = "SELECT * FROM student WHERE `status`='0' AND student_id='$user_id'";
	$user_list = $objDbConnect->query_fetch_arr($sql);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品詳細の取得
$product_list = array();
$where = " WHERE tbl_product.del_flg = '0'";
$where.= " AND tbl_product.product_id = '$pid'";
$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
$where.= " AND (tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL)";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
if (!$st_login_check){
	$where.= " AND product_type = '2'";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  tbl_product_add.product_type_add,";
$sql.= "  tbl_product_elearning.product_flg,";
$sql.= "  tbl_product_elearning.product_disp_warning_word,";
$sql.= "  tbl_product_elearning.product_kind_flg,";
$sql.= "  tbl_product_live_training.memo1,";
$sql.= "  tbl_product_live_training.memo2,";
$sql.= "  tbl_product_live_training.memo3,";
$sql.= "  tbl_product_live_training.memo4,";
$sql.= "  tbl_product_live_training.memo5,";
$sql.= "  tbl_product_live_training.target,";
$sql.= "  tbl_product_live_training.sponsor,";
$sql.= "  RPBA.contents,";
$sql.= "  RPBA.dates,";
$sql.= "  RPBA.web_flg";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_add";
$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";
$sql.= "    LEFT JOIN";
$sql.= "  tbl_product_elearning";
$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id";
$sql.= "    LEFT JOIN";
$sql.= "  tbl_product_live_training";
$sql.= "      ON tbl_product.product_id = tbl_product_live_training.product_id";
$sql.= "    LEFT JOIN";
$sql.= "  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA";
$sql.= "      ON tbl_product.product_id = RPBA.product_id";
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$product_list = $objDbConnect->query_fetch($sql.$where);
if (!$product_list){
	$objDbConnect->close();
	if( $debag ){ print("\n<hr>\nnon product_list\n<hr>\n"); }
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($product_list);
// -----
// 共通
// -----

// ---------------
// e-ラーニング用
// ---------------
if ($product_list['product_type_add'] == 1 && $product_list['price']<=0 ){
	//+++++++++++++++++++++++++++++++++++++++++++
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " COUNT(tbl_order_detail.order_detail_id) as c ";
	$sql.= "FROM ";
	$sql.= " tbl_order_detail ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order_detail.order_id ";
	$sql.= "WHERE ";
	$sql.= "     tbl_order_detail.member_id='".$_SESSION['user']['id']."' "; 
	$sql.= " AND tbl_order_detail.product_id='".$pid."' ";
	$sql.= " AND tbl_order_detail.payment_status='2' ";
	$ret = $objDbConnect->query_fetch($sql);
	//var_dump($ret);
	if ($ret['c']==0){
		$temp_no = 10001;
		$temp_date = date("Ymd");

		$objDbConnect->tran_begin();
		$sql = "select create_date, no from tbl_order_no where create_date='".date("Y-m-d")."' ORDER BY no DESC LIMIT 1";
		$ret = $objDbConnect->query_fetch_arr($sql);
		if( count($ret)>0 ){
			$temp_no = $ret[0]["no"] + 1;
			$temp_date = date("Ymd");
		} else {
			$temp_no = 10001;
			$temp_date = date("Ymd");
		}
		$_SESSION["payment.temp_no"]   = $temp_no;
		$_SESSION["payment.temp_date"] = $temp_date;

		$sql = "INSERT INTO tbl_order_no( create_date, no ) values('".$temp_date."','".$temp_no."')";
		//var_dump($sql);
		$ret = $objDbConnect->execute($sql);
		if($ret){
			$objDbConnect->commit();
			//================================
			$objDbConnect->tran_begin();
			
			$sql = "insert into tbl_order_temp(order_no,price,tax,payment_type,payment_status,order_date,member_id) values('".$temp_date.$temp_no."','0','0','12','2','".date("Y-m-d H:i:s")."','".$_SESSION['user']['id']."')";
			//var_dump($sql);
			$objDbConnect->execute($sql);
			$order_id = mysqli_insert_id($objDbConnect->connect);
			
			// tbl_order_tempテーブルからtbl_orderに購入データをコピー
			$sql = "INSERT INTO tbl_order SELECT * FROM tbl_order_temp WHERE order_id = '$order_id'";
			$objDbConnect->execute($sql);
			
			$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,update_date,payment_status,payment_date,product_type_add,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$pid."','0','0','1','0','".date("Y-m-d H:i:s")."','1','".date("Y-m-d H:i:s")."','".$product_list['product_type_add']."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$pid),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$pid),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$pid),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$pid),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$pid))";
			//var_dump($sql);
			$objDbConnect->execute($sql);

			$objDbConnect->commit();
			//================================
		} else {
			$objDbConnect->rollback();
			$objDbConnect->close();
			//exit;
		}


	}
	//+++++++++++++++++++++++++++++++++++++++++++
// -----------
// 会場研修用
// -----------
} elseif ($product_list['product_type_add'] == 2){
// -------------------
// 倫理代替措置研修用
// -------------------
} elseif ($product_list['product_type_add'] == 3){
// -------------
// パスポート用
// -------------
} elseif ($product_list['product_type_add'] == 4){
} else {
}








//動画視聴履歴作成
//商品に紐づく動画を取得
$sql = "SELECT ";
$sql.= " product_id, ";
$sql.= " product_type, ";
for($i=1; $i<=MAX_CONTENTS; $i++){
	$sql.= " contents_contents".$i.", ";
}
$sql.= " exam2_id ";
$sql.= "FROM ";
$sql.= " tbl_product ";
$sql.= "WHERE product_id='".$pid."'";
if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
$ret = $objDbConnect->query_fetch_arr($sql);
if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
if($ret){
	foreach($ret as $val){
		//商品のコンテンツループ
		for($i2=1; $i2<=MAX_CONTENTS; $i2++){

			if( $val["contents_contents".$i2]!="" ){
				//動画毎の視聴履歴を登録
				$sql = "INSERT IGNORE INTO ";
				$sql.= "report_user_video_viewed(";
				$sql.= " student_id, ";
				$sql.= " video_id, ";
				$sql.= " duration, ";
				$sql.= " duration_reading, ";
				$sql.= " reading_date, ";
				$sql.= " percent, ";
				$sql.= " complete_flag, ";
				$sql.= " complete_date, ";
				$sql.= " regist_at, ";
				$sql.= " update_at ";
				$sql.= ") VALUES(";
				$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', ";
				$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$val["contents_contents".$i2])."', ";
				$sql.= "(SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_real_escape_string($objDbConnect->connect,$val["contents_contents".$i2])."'), ";
				$sql.= "(SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_real_escape_string($objDbConnect->connect,$val["contents_contents".$i2])."'), ";
				$sql.= "'".date("Y-m-d H:i:s")."', ";
				$sql.= "'100', ";
				$sql.= "'1', ";
				$sql.= "'".date("Y-m-d H:i:s")."', ";
				$sql.= "'".date("Y-m-d H:i:s")."', ";
				$sql.= "'".date("Y-m-d H:i:s")."' ";
				$sql.= ")";
				if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
				$objDbConnect->execute($sql);
				if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }

				$sql = "UPDATE ";
				$sql.= " report_user_video_viewed ";
				$sql.= "SET ";
				$sql.= "  duration_reading=(SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_real_escape_string($objDbConnect->connect,$val["contents_contents".$i2])."') ";
				$sql.= " ,percent=100 ";
				$sql.= " ,complete_flag=1 ";
				$sql.= " ,complete_date='".date("Y-m-d H:i:s")."' ";
				$sql.= "WHERE ";
				$sql.= "     student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' ";
				$sql.= " AND video_id='".mysqli_real_escape_string($objDbConnect->connect,$val["contents_contents".$i2])."' ";

				if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
				$objDbConnect->execute($sql);
				if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
			}


		}
	}

	$sql = "SELECT ";
	$sql.= "  product_id ";
	$sql.= " ,contents_no ";
	$sql.= " ,exam_id_test ";
	$sql.= "FROM ";
	$sql.= " rel_product_contents ";
	$sql.= "WHERE product_id='".$pid."'";
	if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
	$ret2 = $objDbConnect->query_fetch_arr($sql);
	if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
	if($ret2){
		foreach($ret2 as $val2){
		}
	}

}





//テスト回答履歴作成
//商品に紐づくテストの取得
$sql = "SELECT contents_no, exam_id_test FROM rel_product_contents WHERE product_id='".$pid."' AND exam_id_test>0 ORDER BY contents_no ASC";
if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
$ret = $objDbConnect->query_fetch_arr($sql);
if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
if( $debag ){ print("\n<br>\n"); var_dump($ret); print("\n<hr>\n"); }
if($ret){
	//商品のテストループ
	foreach($ret as $val){
		$contents_no = $val['contents_no'];
		$exam_id_test = $val['exam_id_test'];
		$arr_exam_problem = array();
		//テストに紐づく問題の取得
		$sql = "SELECT 
				rel_exam_problem.exam_id, 
				rel_exam_problem.exam_no, 
				rel_exam_problem.exam_problem_id,
				exam.criteria_type, 
				exam.criteria_value,
				exam_problem.exam_problem_name, 
				exam_problem.problem_kind, 
				exam_problem.problem_contents, 
				exam_problem.problem_note, 
				exam_problem.answer_kind, 
				exam_problem.answer_contents, 
				exam_problem.answer_point, 
				exam_problem.answer_explain_kind, 
				exam_problem.answer_explain_contents, 
				exam_problem.answer_explain_note 
			FROM 
				rel_exam_problem 
				LEFT JOIN exam ON rel_exam_problem.exam_id=exam.exam_id 
				LEFT JOIN exam_problem ON rel_exam_problem.exam_problem_id=exam_problem.exam_problem_id 
			WHERE 
				rel_exam_problem.exam_id='".mysqli_real_escape_string($objDbConnect->connect,$exam_id_test)."' 
			ORDER BY rel_exam_problem.exam_no ASC ";
		if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
		$arr_exam_problem = $objDbConnect->query_fetch_arr($sql);
		if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
		if($arr_exam_problem){
			//テストの問題ループ
			foreach($arr_exam_problem as $val2 ){
				//仮解凍情報の作成
				//$school_id
				//$nowdate
				//$pid
				//$user_id
				//$contents_no
				//$exam_id_test
				$temp_answer = array(
					'exam_answer_id'	 => 0,
					'exam_id'		 => $exam_id_test,
					'exam_problem_id'	 => $val2["exam_problem_id"],
					'student_id'		 => $user_id,
					'exam_answer_no'	 => 1,
					'exam_answer_contents'	 => '',
					'exam_answer_date'	 => $nowdate,
					'exam_answer_mark'	 => 1,
					'exam_answer_point'	 => $val2["answer_point"],
					'marked_teacher_id'	 => '0',
					'status'		 => '0',
					'update_at'		 => $nowdate,
					'product_id'		 => $pid,
					'contents_no'		 => $contents_no,
					'question_flg'		 => '0',
					'passing_flg'		 => '1',
					'criteria_type'		 => $val2["criteria_type"],
					'criteria_value'	 => $val2["criteria_value"],
				);
				//同一のexam_answer_idとexam_answer_noの存在チェック
				$sql = "SELECT exam_answer_id, max(exam_answer_no) as exam_answer_no, exam_answer_contents FROM exam_answer WHERE product_id='".$pid."' AND exam_id='".$exam_id_test."' AND exam_problem_id='".$val2["exam_problem_id"]."' LIMIT 1";
				if( $debag ){ var_dump($sql); print("\n<br>\n"); }
				$arr_old_exam_answer = $objDbConnect->query_fetch_arr($sql);
				if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
				if($arr_old_exam_answer){
					//テストの問題ループ
					foreach($arr_old_exam_answer as $val3 ){
						if( $val3["exam_answer_id"]!==null ){
							$temp_answer["exam_answer_id"] = $val3["exam_answer_id"];
							$temp_answer["exam_answer_no"] = $val3["exam_answer_no"] + 1;
							$temp_answer["exam_answer_contents"] = $val3["exam_answer_contents"];
						}
					}
				}
				//exam_answerの登録及び更新
				if( $temp_answer["exam_answer_id"]==0 ){
					//登録
					$sql = "INSERT IGNORE INTO ";
					$sql.= " exam_answer ( ";
					$sql.= " exam_id, ";
					$sql.= " exam_problem_id, ";
					$sql.= " student_id, ";
					$sql.= " exam_answer_no, ";
					$sql.= " exam_answer_contents, ";
					$sql.= " exam_answer_date, ";
					$sql.= " exam_answer_mark, ";
					$sql.= " exam_answer_point, ";
					$sql.= " marked_teacher_id, ";
					$sql.= " `status`, ";
					$sql.= " update_at, ";
					$sql.= " product_id, ";
					$sql.= " contents_no, ";
					$sql.= " question_flg, ";
					$sql.= " passing_flg, ";
					$sql.= " criteria_type, ";
					$sql.= " criteria_value ";
					$sql.= " ) ";
					$sql.= " VALUES ( ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_id"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_problem_id"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["student_id"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_no"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_contents"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_date"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_mark"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_point"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["marked_teacher_id"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["status"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["update_at"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["product_id"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["contents_no"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["question_flg"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["passing_flg"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["criteria_type"])."', ";
					$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["criteria_value"])."' ";
					$sql.= " )";
					if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
					$objDbConnect->execute($sql);
					if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
				} else {
					//更新
					$sql = "UPDATE ";
					$sql.= " exam_answer ";
					$sql.= "SET ";
					$sql.= "exam_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_id"])."', ";
					$sql.= "exam_problem_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_problem_id"])."', ";
					$sql.= "student_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["student_id"])."', ";
					$sql.= "exam_answer_no='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_no"])."', ";
					$sql.= "exam_answer_contents='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_contents"])."', ";
					$sql.= "exam_answer_date='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_date"])."', ";
					$sql.= "exam_answer_contents='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_contents"])."', ";
					$sql.= "exam_answer_date='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_date"])."', ";
					$sql.= "exam_answer_mark='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_mark"])."', ";
					$sql.= "exam_answer_point='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_point"])."', ";
					$sql.= "marked_teacher_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["marked_teacher_id"])."', ";
					$sql.= "`status`='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["status"])."', ";
					$sql.= "update_at='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["update_at"])."', ";
					$sql.= "product_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["product_id"])."', ";
					$sql.= "contents_no='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["contents_no"])."', ";
					$sql.= "question_flg='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["question_flg"])."', ";
					$sql.= "passing_flg='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["passing_flg"])."', ";
					$sql.= "criteria_type='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["criteria_type"])."', ";
					$sql.= "criteria_value='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["criteria_value"])."' ";
					$sql.= "WHERE ";
					$sql.= "exam_answer_id='".mysqli_real_escape_string($objDbConnect->connect,$temp_answer["exam_answer_id"])."' ";
					if( $debag ){ var_dump($sql); print("\n<hr>\n"); }
					$objDbConnect->execute($sql);
					if( $debag ){ print("\n<br>\nsql ok\n<hr>\n"); }
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "UPDATE ";
$sql.= "  tbl_order_detail ";
$sql = "SET ";
$sql = " video_complete_flg=1 ";
$sql = "WHERE ";
$sql.= "        member_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', ";
$sql.= "    AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ";
$objDbConnect->execute($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "INSERT IGNORE INTO ";
$sql.= "  student_make_complete ( ";
$sql.= "    student_id, ";
$sql.= "    product_id ";
$sql.= "  ) VALUES ( ";
$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', ";
$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ";
$sql.= "  ) ";
$objDbConnect->execute($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
print("OK");
exit();
?>