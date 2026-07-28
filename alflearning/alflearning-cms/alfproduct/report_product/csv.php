<?php
//ini_set( 'display_errors', 1 ); 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
// 日弁連フラグ
$login_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
if ($login_bar_association_id == 1){
	$nichibenren_flg = true;  // 日弁連
} else {
	$nichibenren_flg = false; // 日弁連以外の弁護士会
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pid = "";
$search_product_name = "";
$search_product_code = "";
$search_product_type_add = array();
$search_association = "";
$search_start_date = "";
$search_end_date = "";
$search_category = array();
$search_open = "0";
$search_teacher = "";
$search_free = "0";

if( isset($_GET["pid"]) && !empty($_GET["pid"]) ){
	$pid = trim($_GET["pid"]);
}

if( isset($_SESSION["report_product.search_product_name"]) && !empty($_SESSION["report_product.search_product_name"]) ){
	$search_product_name = $_SESSION["report_product.search_product_name"];
}
if( isset($_SESSION["report_product.search_product_code"]) && !empty($_SESSION["report_product.search_product_code"]) ){
	$search_product_code = $_SESSION["report_product.search_product_code"];
}
if( isset($_SESSION["report_product.search_product_type_add"]) && !empty($_SESSION["report_product.search_product_type_add"]) ){
	$search_product_type_add = $_SESSION["report_product.search_product_type_add"];
}
if( isset($_SESSION["report_product.search_association"]) && !empty($_SESSION["report_product.search_association"]) ){
	$search_association = $_SESSION["report_product.search_association"];
}
if( isset($_SESSION["report_product.search_start_date"]) && !empty($_SESSION["product.search_start_date"]) ){
	$search_start_date = $_SESSION["report_product.search_start_date"];
}
if( isset($_SESSION["report_product.search_end_date"]) && !empty($_SESSION["report_product.search_end_date"]) ){
	$search_end_date = $_SESSION["report_product.search_end_date"];
}
if( isset($_SESSION["report_product.search_category"]) && !empty($_SESSION["report_product.search_category"]) ){
	$search_category = $_SESSION["report_product.search_category"];
}
if( isset($_SESSION["report_product.search_open"]) && !empty($_SESSION["report_product.search_open"]) ){
	$search_open = $_SESSION["report_product.search_open"];
}
if( isset($_SESSION["report_product.search_teacher"]) && !empty($_SESSION["report_product.search_teacher"]) ){
	$search_teacher = $_SESSION["report_product.search_teacher"];
}
if( isset($_SESSION["report_product.search_free"]) && !empty($_SESSION["report_product.search_free"]) ){
	$search_free = $_SESSION["report_product.search_free"];
}
if( isset($_SESSION["report_product.search_word"]) && !empty($_SESSION["report_product.search_word"]) ){
	$search_word = $_SESSION["report_product.search_word"];
}
/*
var_dump($search_product_name);
var_dump($search_product_code);
var_dump($search_product_type_add);
var_dump($search_association);
var_dump($search_start_date);
var_dump($search_end_date);
var_dump($search_category);
var_dump($search_open);
var_dump($search_teacher);
var_dump($search_free);
var_dump($search_word);
exit();
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( isset($_GET["aid"]) && !empty($_GET["aid"]) ){
	$aid = $_GET["aid"];
} else {
	$aid = '';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 旧システムデータの場合
if ($aid != ''){
	if ($pid <= 19233) {
		$old_pid = $pid - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$aid = $res_bar_association_branch_id[0]['bar_association_branch_id'];
		} else {
			$aid = '1';
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($pid);
//exit();
$arr_list = array();
if( $pid=="" ){
	//----------------------------------------------------------
	$group = "";
	$order = " ORDER BY product_id DESC ";
	$sql = "";

	$sql = "";
	$sql.= "SELECT ";
	$sql.= " tbl_product.product_id, ";
	$sql.= " tbl_product.product_code, ";
	$sql.= " tbl_product.product_name, ";
	$sql.= " tbl_product_add.product_type_add, ";
	$sql.= " tbl_product_live_training.training_kind_flg, ";
	$sql.= " tbl_product_live_training.ethic_flg, ";
	$sql.= " DATE_FORMAT(tbl_product.start_date,'%Y/%m/%d %H:%i') as start_date, ";
	$sql.= " DATE_FORMAT(tbl_product.end_date,'%Y/%m/%d %H:%i') as end_date, ";
	$sql.= " DATE_FORMAT(rel_product_bar_association_branch.dates,'%Y/%m/%d') as dates, ";
	$sql.= " mtb_bar_association_branch.bar_association_branch_id AS bar_association_branch_id, ";
	$sql.= " mtb_bar_association_branch.bar_association_branch_name AS bar_association_branch_name, ";
	$sql.= " mtb_bar_association.name AS bar_association_name, ";
	$sql.= " rel_product_bar_association_branch.bar_association_branch_id as rel_product_bar_association_branch_bar_association_branch_id, ";

	/*
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add1_count, ";
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 ) AS product_type_add1_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add1_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 $product_type_add_where ) AS product_type_add1_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 ) AS product_type_add2_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 ) AS product_type_add2_end_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE tbl_ethic_question_history.product_id=tbl_product.product_id  ) AS product_type_add3_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE tbl_ethic_question_history.product_id=tbl_product.product_id AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) ) AS product_type_add3_end_count ";
	*/
	$sql.= " 0 AS product_type_add1_count, ";
	$sql.= " 0 AS product_type_add1_end_count, ";
	$sql.= " 0 AS product_type_add2_count, ";
	$sql.= " 0 AS product_type_add2_end_count, ";
	$sql.= " 0 AS product_type_add2_count_kako, ";
	$sql.= " 0 AS product_type_add2_end_count_kako, ";
	$sql.= " 0 AS product_type_add3_count, ";
	$sql.= " 0 AS product_type_add3_end_count ";

	/*
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add1_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 $product_type_add_where ) AS product_type_add1_end_count, ";
	if ($pid<=19233){
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
	} else {
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
	}
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=tbl_product.product_id $product_type_add3_where ) AS product_type_add3_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=tbl_product.product_id AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) $product_type_add3_where ) AS product_type_add3_end_count ";
	*/
	// status ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格）	
	// AND tbl_order_detail.video_complete_flg=1
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 ) AS product_type_add1_count, ";
	//$sql.= " tbl_product_live_training.sponsor ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	//$sql.= " INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id ";
	//$sql.= " INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
	$sql.= " LEFT JOIN rel_product_bar_association_branch ON tbl_product.product_id=rel_product_bar_association_branch.product_id AND tbl_product_add.product_type_add<>3 ";
	$sql.= " LEFT JOIN import_kenshu_count ON tbl_product.product_id=import_kenshu_count.KENSHU_ID + 10000 ";
	$sql.= " LEFT JOIN mtb_bar_association_branch ON rel_product_bar_association_branch.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id ";
	$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association_branch.bar_association_id=mtb_bar_association.id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_product.del_flg=0 ";
	$where.= " AND ( ( import_kenshu_count.ROOT_ID<>0 AND tbl_product_live_training.ethic_flg=0 ) OR tbl_product_live_training.ethic_flg=1 OR import_kenshu_count.ROOT_ID IS NULL ) ";
	$where.= " AND ( rel_product_bar_association_branch.web_flg<>2 OR rel_product_bar_association_branch.web_flg IS NULL ) ";

	//----------------------------------------------------------
	if (!$nichibenren_flg){
		$where.= " and ( tbl_product_live_training.sponsor IS NULL OR tbl_product_live_training.sponsor = '' OR tbl_product_live_training.sponsor like '%|$login_bar_association_id|%' ) ";
	}
	//----------------------------------------------------------
	if( $search_product_name != "" ){
		$where.= " and ( tbl_product.product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_name)."%' ) ";
	}
	//----------------------------------------------------------
	if( $search_product_code != "" ){
		$where.= " and ( tbl_product.product_code like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_code)."%' ) ";
	}
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_product_type_add) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_product_type_add);$i++){
			if($i>0){ $temp_where.= " or "; }
			// 1e-ラーニング
			// 2ライブ実務研修
			// 3倫理研修
			// 4夏期研修
			// 5新規登録弁護士研修
			// 6elライブ
			// tbl_product_add.product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
			// tbl_product_elearning.product_kind_flg 商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
			// tbl_product_live_training.training_kind_flg 研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
			if($search_product_type_add[$i]=="1"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg<>'2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="2"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='1' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="3"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '3' OR ( tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='3' ) ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="4"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="5"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='4' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="6"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg='2' ";
				$temp_where.= " ) ";
			}

		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	if( $search_association != "" ){
		$where.= " and mtb_bar_association.id = '".mysqli_real_escape_string($objDbConnect->connect,$search_association)."' ";
	}
	//----------------------------------------------------------
	if( $search_start_date != "" && $search_end_date != "" ){
		$where.= " and ( ";
			$where.= " ('".$search_start_date."' between  tbl_product.start_date and end_date OR tbl_product.start_date IS NULL) ";
			$where.= " OR ('".$search_end_date."' between  tbl_product.start_date and tbl_product.end_date OR end_date IS NULL) ";
		$where.= " ) ";
	} elseif( $search_start_date != "" ){
		$where.= " and (tbl_product.start_date<='".$search_start_date."' OR tbl_product.start_date IS NULL) ";
		$where.= " and (tbl_product.end_date>='".$search_start_date."' OR tbl_product.end_date IS NULL) ";
	} elseif( $search_end_date != "" ){
		$where.= " and (tbl_product.start_date<='".$search_end_date."' OR tbl_product.start_date IS NULL) ";
		$where.= " and (tbl_product.end_date>='".$search_end_date."' OR tbl_product.end_date IS NULL) ";
	}
	//----------------------------------------------------------
	$cat_where = "";
	if( count($search_category)!=0 ){
		for($i=0;$i<count($search_category);$i++){
			$sql_sub = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent FROM wp_term_taxonomy where taxonomy='category' and parent='".$search_category[$i]."'";
			$temp = $objDbConnect->query_fetch_arr($sql_sub);
			if( count($temp)==0 ){
				$temp_arr_cat[] = $search_category[$i];
			} else {
				$add_cat = 1;
				for($n=0;$n<count($temp);$n++){
					for($m=0;$m<count($search_category);$m++){
						if( $temp[$n]["term_id"]==$search_category[$m] ){
							$add_cat = 0;
						}
					}
				}
				if( $add_cat==1 ){
					$temp_arr_cat[] = $search_category[$i];
				}
			}
		}
		for($i=0;$i<count($temp_arr_cat);$i++){
			if($cat_where !="" ){ $cat_where.= " OR "; }
			$cat_where.= " concat(',',tbl_product.term_id,',') LIKE '%,".$temp_arr_cat[$i].",%' ";
		}
		if($cat_where !="" ){
			$where.= " and ( ";
			$where.= $cat_where;
			$where.= " ) ";
		}
	}
	//----------------------------------------------------------
	if( $search_open == "1" ){//公開前
		$where.= " and (start_date>'".date("Y:m:d H-i-s")."') ";
	}
	if( $search_open == "2" ){//公開中
		$where.= " and ( ('".date("Y:m:d H-i-s")."' between  start_date and end_date) OR ('".date("Y:m:d H-i-s")."'>=start_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND '".date("Y:m:d H-i-s")."'>=start_date) OR (start_date IS NULL AND end_date IS NULL) ) ";
	}
	if( $search_open == "3" ){//終了
		$where.= " and (end_date<'".date("Y:m:d H-i-s")."') ";
	}
	//----------------------------------------------------------
	if( $search_teacher != "" ){
		$where.= " and ( teacher like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' ) ";
	}
	//----------------------------------------------------------
	if( $search_free == "1" ){//有料
		$where.= " and ( price>'0' ) ";
	}
	if( $search_free == "2" ){//無料
		$where.= " and ( price='0' ) ";
	}
	//----------------------------------------------------------
	if( $search_word != "" ){
		$where.= " and ( ";
		$where.= " product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%' OR product_code like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%' OR memo like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%' OR play_time like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%' OR teacher like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents1_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents2_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents3_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents4_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents5_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents6_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents7_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents8_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents9_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_contents10_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_memo10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= " OR contents_teacher10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
		$where.= "  ) ";
	}
	//----------------------------------------------------------
	//旧データは対象外
	$where.= "  
		 /*AND tbl_product.product_id>19233*/
		 AND (
			(
				tbl_product_add.product_type_add=2
				AND tbl_product_live_training.training_kind_flg=1
				AND (  
					SELECT 
						COUNT(*) AS c 
					FROM 
						tbl_order_detail 
					WHERE 
						( 
							tbl_order_detail.payment_status = 1 
							OR tbl_order_detail.payment_status = 2 
							OR tbl_order_detail.payment_status = 3 
						) 
						AND tbl_order_detail.product_type_add=2 
						AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch.bar_association_branch_id 
						AND tbl_order_detail.product_id=tbl_product.product_id 
				)>0
			)
			OR (
				tbl_product_add.product_type_add<>2
				OR tbl_product_live_training.training_kind_flg<>1
			)
		)
	 ";
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
//print("\n<!--\n");
//print("\n[SQL]\n");
//var_dump($sql.$where.$group.$order);
//print("\n\n");
//print("\n-->\n");
//exit();
	if( 0<count($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}

//var_dump($ret);
//exit();

	// 【】各レコード数の再計算 --------------------------------------------------------------
	$arr_list = array();
	$pre_pid = "0";
	for($i=0;$i<count($ret);$i++){
		$pid = $ret[$i]["product_id"];	// 商品ID
		$rel_babid = $ret[$i]["rel_product_bar_association_branch_bar_association_branch_id"];
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add1_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=".$pid." ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add1_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add1_end_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=".$pid." AND tbl_order_detail.video_complete_flg=1 ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add1_end_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_count / end_count
		// [NBR-239] 詳細側(info.php)と条件を統一：非日弁連ログイン時は所属弁護士会で絞る／payment_status=3(仮払い等)は対象外にしてフロント受講履歴(lesson_list2.php)と揃える
		$sql_sub_where2 = " ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=".$rel_babid." AND tbl_order_detail.product_id=".$pid." ";
		if (!$nichibenren_flg){
			$sql_sub_where2.= " AND tbl_order_detail.bar_association_id='".$login_bar_association_id."' ";
		}
		//product_type_add2_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ".$sql_sub_where2;
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add2_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_end_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ".$sql_sub_where2." AND tbl_order_detail.participation_flg=1 ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add2_end_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_count_kako（実施会不問の全期間合計。参考値のため絞り込みカラムは変更せず、payment_status のみフロント基準に統一）
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=".$pid." ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add2_count_kako"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_end_count_kako
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=".$pid." AND tbl_order_detail.participation_flg=1 ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add2_end_count_kako"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		// [NBR-239] 詳細側(info.php)と条件を統一：非日弁連ログイン時は所属弁護士会で絞る
		$sql_sub_where3 = "";
		if (!$nichibenren_flg){
			$sql_sub_where3 = " AND student.bar_association_id='".$login_bar_association_id."' ";
		}
		//product_type_add3_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=".$pid.$sql_sub_where3." ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add3_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add3_end_count
		$sql_sub = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=".$pid." AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7)".$sql_sub_where3." ";
		$ret_sub = $objDbConnect->query_fetch_arr($sql_sub);
		if( !empty($ret_sub) ){
			$ret[$i]["product_type_add3_end_count"] = $ret_sub[0]["c"];
		}
		//+++++++++++++++++++++++++++++++++++++++


		// 【】重複するmember_id（student_id）を探す //
		$sql2 = "";
		$sql2.= "SELECT tbl_order_detail.member_id ";
		$sql2.= "  FROM tbl_order_detail ";
		$sql2.= " WHERE tbl_order_detail.product_id ='".$pid."' ";
		$sql2.= "   AND (tbl_order_detail.product_id, tbl_order_detail.member_id) ";
		$sql2.= "        IN ( ";
		$sql2.= "          SELECT A.product_id, A.member_id ";
		$sql2.= "            FROM tbl_order_detail AS A ";
		$sql2.= "           WHERE A.product_id  ='".$pid."' ";
		$sql2.= "           GROUP BY A.product_id, A.member_id ";
		$sql2.= "          HAVING COUNT(*) > 1 ";
		$sql2.= "       )";

		// product_type_add1_count -----------------------------------------------------------
		if($ret[$i]["product_type_add1_count"]){
			$sql2_where = "";
			$sql2_where.= " AND tbl_order_detail.payment_status   = 2 ";
			$sql2_where.= " AND tbl_order_detail.product_type_add = 1 ";

			$res = $objDbConnect->query_fetch_arr($sql2.$sql2_where);
			
			if($res){
				$duplicate_member_id = "-1";
				for($j=0;$j<count($res);$j++){
					$duplicate_member_id .= ','.$res[$j]["member_id"];
				}
				
				if($duplicate_member_id != "-1"){
					$sql3 = "";
					$sql3.= "SELECT COUNT(*) AS r_count ";
					$sql3.= " FROM tbl_order_detail INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id ";
					$sql3.= "WHERE 1=1 ";
					$sql3.= "  AND tbl_order_detail.product_id ='".$pid."' ";
					$sql3.= "  AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
					
					$res_count = $objDbConnect->query_fetch_arr($sql3.$sql2_where);

					for($k=0;$k<count($res_count);$k++){
						$ret[$i]["product_type_add1_count"] = $res_count[0]['r_count'];
					}
				}
			}
		}
		// product_type_add1_end_count -----------------------------------------------------------
		if($ret[$i]["product_type_add1_end_count"]){
			$sql2_where = "";
			$sql2_where.= " AND tbl_order_detail.payment_status   = 2 ";
			$sql2_where.= " AND tbl_order_detail.product_type_add = 1 ";
			$sql2_where.= " AND tbl_order_detail.video_complete_flg = 1 ";

			$res = $objDbConnect->query_fetch_arr($sql2.$sql2_where);
			
			if($res){
				$duplicate_member_id = "-1";
				for($j=0;$j<count($res);$j++){
					$duplicate_member_id .= ','.$res[$j]["member_id"];
				}
				
				if($duplicate_member_id != "-1"){
					$sql3 = "";
					$sql3.= "SELECT COUNT(*) AS r_count ";
					$sql3.= " FROM tbl_order_detail INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id ";
					$sql3.= "WHERE 1=1 ";
					$sql3.= "  AND tbl_order_detail.product_id ='".$pid."' ";
					$sql3.= " AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
					
					$res_count = $objDbConnect->query_fetch_arr($sql3.$sql2_where);
					
					for($k=0;$k<count($res_count);$k++){
						$ret[$i]["product_type_add1_end_count"] = $res_count[0]['r_count'];
					}
				}
			}
		}
		
		if ($ret[$i]["product_type_add"] == 1){
			$res1 = array();
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " contents_contents1, ";
			$sql.= " contents_contents2, ";
			$sql.= " contents_contents3, ";
			$sql.= " contents_contents4, ";
			$sql.= " contents_contents5, ";
			$sql.= " contents_contents6, ";
			$sql.= " contents_contents7, ";
			$sql.= " contents_contents8, ";
			$sql.= " contents_contents9, ";
			$sql.= " contents_contents10, ";
			$sql.= " contents_contents11, ";
			$sql.= " contents_contents12, ";
			$sql.= " contents_contents13, ";
			$sql.= " contents_contents14, ";
			$sql.= " contents_contents15, ";
			$sql.= " contents_contents16, ";
			$sql.= " contents_contents17, ";
			$sql.= " contents_contents18, ";
			$sql.= " contents_contents19, ";
			$sql.= " contents_contents20, ";
			$sql.= " contents_contents21, ";
			$sql.= " contents_contents22, ";
			$sql.= " contents_contents23, ";
			$sql.= " contents_contents24, ";
			$sql.= " contents_contents25 ";
			$sql.= "FROM ";
			$sql.= " tbl_product ";
			$sql.= "WHERE ";
			$sql.= " product_id='".mysqli_real_escape_string($objDbConnect->connect,  $ret[$i]["product_id"] )."' ";
			$res1 = $objDbConnect->query_fetch_arr($sql);
			$video_count = 0;
			$arr_video_id = array();
			$in_video_id = '';
			if( trim($res1[0]["contents_contents1"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents1"]); }
			if( trim($res1[0]["contents_contents2"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents2"]); }
			if( trim($res1[0]["contents_contents3"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents3"]); }
			if( trim($res1[0]["contents_contents4"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents4"]); }
			if( trim($res1[0]["contents_contents5"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents5"]); }
			if( trim($res1[0]["contents_contents6"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents6"]); }
			if( trim($res1[0]["contents_contents7"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents7"]); }
			if( trim($res1[0]["contents_contents8"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents8"]); }
			if( trim($res1[0]["contents_contents9"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents9"]); }
			if( trim($res1[0]["contents_contents10"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents10"]); }
			if( trim($res1[0]["contents_contents11"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents11"]); }
			if( trim($res1[0]["contents_contents12"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents12"]); }
			if( trim($res1[0]["contents_contents13"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents13"]); }
			if( trim($res1[0]["contents_contents14"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents14"]); }
			if( trim($res1[0]["contents_contents15"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents15"]); }
			if( trim($res1[0]["contents_contents16"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents16"]); }
			if( trim($res1[0]["contents_contents17"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents17"]); }
			if( trim($res1[0]["contents_contents18"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents18"]); }
			if( trim($res1[0]["contents_contents19"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents19"]); }
			if( trim($res1[0]["contents_contents20"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents20"]); }
			if( trim($res1[0]["contents_contents21"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents21"]); }
			if( trim($res1[0]["contents_contents22"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents22"]); }
			if( trim($res1[0]["contents_contents23"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents23"]); }
			if( trim($res1[0]["contents_contents24"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents24"]); }
			if( trim($res1[0]["contents_contents25"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents25"]); }
			if (!empty($arr_video_id)){
				$in_video_id = trim(implode(",",$arr_video_id),",");
				
				// 総受講数
				$arr_count = array();
				$sql = "";
				$sql.= "SELECT";
				$sql.= "   COUNT(T1.student_id) AS product_type_add1_end_count";
				$sql.= " FROM";
				$sql.= "   (";
				$sql.= "     SELECT";
				$sql.= "       report_user_video_viewed.student_id,";
				$sql.= "       COUNT(report_user_video_viewed.student_id) AS product_video_comp_count";
				$sql.= "     FROM";
				$sql.= "       report_user_video_viewed";
				$sql.= "         LEFT JOIN";
				$sql.= "       student";
				$sql.= "         ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
				$sql.= "       AND report_user_video_viewed.complete_flag = 1";
				$sql.= "       AND student.student_id>0 ";
				if (!$nichibenren_flg){
					$sql.= "   AND student.bar_association_id='".$login_bar_association_id."' ";
				}
				$sql.= "     GROUP BY";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "   ) AS T1";
				$sql.= " WHERE";
				$sql.= "   T1.product_video_comp_count = '$video_count'";
				$arr_count = $objDbConnect->query_fetch_arr($sql);
				if ($arr_count){
					$ret[$i]['product_type_add1_end_count'] = $arr_count[0]['product_type_add1_end_count'];
				}
				
				// 受講済み数
				// [NBR-239] 詳細側(info.php)・フロント受講履歴(lesson_list1.php)と条件を統一：
				// 非日弁連ログイン時の弁護士会絞り込み・percent>=1（視聴開始）を追加
				$arr_count = array();
				$sql = "";
				$sql.= " SELECT";
				$sql.= "   COUNT(T1.student_id) AS product_type_add1_count";
				$sql.= " FROM";
				$sql.= "   (";
				$sql.= "     SELECT";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "     FROM";
				$sql.= "       report_user_video_viewed";
				$sql.= "         LEFT JOIN";
				$sql.= "       student";
				$sql.= "         ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
				$sql.= "       AND report_user_video_viewed.percent >= 1";
				$sql.= "       AND student.student_id>0 ";
				if (!$nichibenren_flg){
					$sql.= "       AND student.bar_association_id='".$login_bar_association_id."' ";
				}
				$sql.= "     GROUP BY";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "   ) AS T1";
				$arr_count = $objDbConnect->query_fetch_arr($sql);
				if ($arr_count){
					$ret[$i]['product_type_add1_count'] = $arr_count[0]['product_type_add1_count'];
				}
			}
		}

		$pre_pid = $ret[$i]["product_id"];// 商品ID
		$arr_list[] = $ret[$i];
	}
	// 【】各レコード数の再計算 --------------------------------------------------------------

	$pid = "";
	$ret = $arr_list;

	//----------------------------------------------------------
} else {
	//----------------------------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " tbl_product.product_id, ";
	$sql.= " tbl_product.product_code, ";
	$sql.= " tbl_product.product_name, ";
	$sql.= " tbl_product_live_training.training_kind_flg, ";
	$sql.= " tbl_product_live_training.ethic_flg, ";
	$sql.= " tbl_product_add.product_type_add, "; //商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
	$sql.= " DATE_FORMAT(tbl_product.start_date,'%Y/%m/%d %H:%i') as start_date, ";
	$sql.= " DATE_FORMAT(tbl_product.end_date,'%Y/%m/%d %H:%i') as end_date, ";
	$sql.= " DATE_FORMAT(rel_product_bar_association_branch.dates,'%Y/%m/%d') as dates, ";
	$sql.= " mtb_bar_association_branch.bar_association_branch_name AS bar_association_branch_name, ";
	$sql.= " mtb_bar_association.name AS bar_association_name, ";
	$sql.= " rel_product_bar_association_branch.bar_association_branch_id as rel_product_bar_association_branch_bar_association_branch_id, ";
	$sql.= " tbl_product_elearning.product_kind_flg, "; //商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
	$sql.= " tbl_product_live_training.training_kind_flg, "; //研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
	$sql.= " tbl_product_ethic_training.ethic_group_id, "; //
	if (!$nichibenren_flg){
		$product_type_add_where = " AND tbl_order_detail.bar_association_id = '$login_bar_association_id' ";
		$product_type_add3_where = " AND student.bar_association_id = '$login_bar_association_id' ";
	} else {
		$product_type_add_where = "";
		$product_type_add3_where = "";
	}
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add1_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 $product_type_add_where ) AS product_type_add1_end_count, ";
	// [NBR-239] payment_status=3（仮払い等）はフロント受講履歴(lesson_list2.php)と揃えて対象外にする
	if ($pid<=19233){
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
	} else {
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
	}
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=tbl_product.product_id $product_type_add3_where ) AS product_type_add3_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.product_id=tbl_product.product_id AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) $product_type_add3_where ) AS product_type_add3_end_count ";
	//tbl_product_elearning.product_kind_flg //商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
	//tbl_product_live_training.training_kind_flg //研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
	// status ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格）
	// AND tbl_order_detail.video_complete_flg=1
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 ) AS product_type_add1_count, ";
	//$sql.= " tbl_product_live_training.sponsor ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	//$sql.= " INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id ";
	//$sql.= " INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
	$sql.= " LEFT JOIN tbl_product_ethic_training ON tbl_product.product_id=tbl_product_ethic_training.product_id ";
	$sql.= " LEFT JOIN rel_product_bar_association_branch ON tbl_product.product_id=rel_product_bar_association_branch.product_id ";
	$sql.= " LEFT JOIN mtb_bar_association_branch ON rel_product_bar_association_branch.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id ";
	$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association_branch.bar_association_id=mtb_bar_association.id ";
	$where = "";
	$where.= " WHERE ";
	$where.= " tbl_product.product_id='".mysqli_real_escape_string($objDbConnect->connect,  $pid )."' ";
	// 新システム商品の場合
	if ($pid > 19233) {
		if ($aid != ''){
			$where.= " AND rel_product_bar_association_branch.bar_association_branch_id='".mysqli_real_escape_string($objDbConnect->connect,  $aid )."' ";
		}
	}
	
	$ret = $objDbConnect->query_fetch_arr($sql.$where);



	// 【】各レコード数の再計算 --------------------------------------------------------------
	// ※info.php
	for($i=0;$i<count($ret);$i++){

	//	$pid = $ret[$i]["product_id"];	// 商品ID

		// 【】重複するmember_id（student_id）を探す //
		$sql2 = "";
		$sql2.= "SELECT tbl_order_detail.member_id ";
		$sql2.= "  FROM tbl_order_detail ";
		$sql2.= " WHERE tbl_order_detail.product_id ='".$pid."' ";
		$sql2.= "   AND (tbl_order_detail.product_id, tbl_order_detail.member_id) ";
		$sql2.= "        IN ( ";
		$sql2.= "          SELECT A.product_id, A.member_id ";
		$sql2.= "            FROM tbl_order_detail AS A ";
		$sql2.= "           WHERE A.product_id  ='".$pid."' ";
		$sql2.= "           GROUP BY A.product_id, A.member_id ";
		$sql2.= "          HAVING COUNT(*) > 1 ";
		$sql2.= "       )";

		// product_type_add1_count -----------------------------------------------------------
		if($ret[$i]["product_type_add1_count"]){
			$sql2_where = "";
			$sql2_where.= " AND tbl_order_detail.payment_status   = 2 ";
			$sql2_where.= " AND tbl_order_detail.product_type_add = 1 ";

			$res = $objDbConnect->query_fetch_arr($sql2.$sql2_where);
			
			if($res){
				$duplicate_member_id = "-1";
				for($j=0;$j<count($res);$j++){
					$duplicate_member_id .= ','.$res[$j]["member_id"];
				}
				
				if($duplicate_member_id != "-1"){
					$sql3 = "";
					$sql3.= "SELECT COUNT(*) AS r_count ";
					$sql3.= " FROM tbl_order_detail INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id ";
					$sql3.= "WHERE 1=1 ";
					$sql3.= "  AND tbl_order_detail.product_id ='".$pid."' ";
					$sql3.= "  AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
					
					$res_count = $objDbConnect->query_fetch_arr($sql3.$sql2_where);

					for($k=0;$k<count($res_count);$k++){
						$ret[$i]["product_type_add1_count"] = $res_count[0]['r_count'];
					}
				}
			}
		}
		
		
		
		// product_type_add1_end_count -----------------------------------------------------------
		if($ret[$i]["product_type_add1_end_count"]){
			$sql2_where = "";
			$sql2_where.= " AND tbl_order_detail.payment_status   = 2 ";
			$sql2_where.= " AND tbl_order_detail.product_type_add = 1 ";
			$sql2_where.= " AND tbl_order_detail.video_complete_flg = 1 ";

			$res = $objDbConnect->query_fetch_arr($sql2.$sql2_where);
			
			if($res){
				$duplicate_member_id = "-1";
				for($j=0;$j<count($res);$j++){
					$duplicate_member_id .= ','.$res[$j]["member_id"];
				}
				
				if($duplicate_member_id != "-1"){
					$sql3 = "";
					$sql3.= "SELECT COUNT(*) AS r_count ";
					$sql3.= " FROM tbl_order_detail INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id ";
					$sql3.= "WHERE 1=1 ";
					$sql3.= "  AND tbl_order_detail.product_id ='".$pid."' ";
					$sql3.= " AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
					
					$res_count = $objDbConnect->query_fetch_arr($sql3.$sql2_where);
					
					for($k=0;$k<count($res_count);$k++){
						$ret[$i]["product_type_add1_end_count"] = $res_count[0]['r_count'];
					}
				}
			}
		}

		if ($ret[$i]["product_type_add"] == 1){
			$res1 = array();
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " contents_contents1, ";
			$sql.= " contents_contents2, ";
			$sql.= " contents_contents3, ";
			$sql.= " contents_contents4, ";
			$sql.= " contents_contents5, ";
			$sql.= " contents_contents6, ";
			$sql.= " contents_contents7, ";
			$sql.= " contents_contents8, ";
			$sql.= " contents_contents9, ";
			$sql.= " contents_contents10, ";
			$sql.= " contents_contents11, ";
			$sql.= " contents_contents12, ";
			$sql.= " contents_contents13, ";
			$sql.= " contents_contents14, ";
			$sql.= " contents_contents15, ";
			$sql.= " contents_contents16, ";
			$sql.= " contents_contents17, ";
			$sql.= " contents_contents18, ";
			$sql.= " contents_contents19, ";
			$sql.= " contents_contents20, ";
			$sql.= " contents_contents21, ";
			$sql.= " contents_contents22, ";
			$sql.= " contents_contents23, ";
			$sql.= " contents_contents24, ";
			$sql.= " contents_contents25 ";
			$sql.= "FROM ";
			$sql.= " tbl_product ";
			$sql.= "WHERE ";
			$sql.= " product_id='".mysqli_real_escape_string($objDbConnect->connect,  $ret[$i]["product_id"] )."' ";
			$res1 = $objDbConnect->query_fetch_arr($sql);
			$video_count = 0;
			$arr_video_id = array();
			$in_video_id = '';
			if( trim($res1[0]["contents_contents1"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents1"]); }
			if( trim($res1[0]["contents_contents2"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents2"]); }
			if( trim($res1[0]["contents_contents3"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents3"]); }
			if( trim($res1[0]["contents_contents4"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents4"]); }
			if( trim($res1[0]["contents_contents5"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents5"]); }
			if( trim($res1[0]["contents_contents6"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents6"]); }
			if( trim($res1[0]["contents_contents7"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents7"]); }
			if( trim($res1[0]["contents_contents8"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents8"]); }
			if( trim($res1[0]["contents_contents9"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents9"]); }
			if( trim($res1[0]["contents_contents10"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents10"]); }
			if( trim($res1[0]["contents_contents11"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents11"]); }
			if( trim($res1[0]["contents_contents12"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents12"]); }
			if( trim($res1[0]["contents_contents13"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents13"]); }
			if( trim($res1[0]["contents_contents14"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents14"]); }
			if( trim($res1[0]["contents_contents15"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents15"]); }
			if( trim($res1[0]["contents_contents16"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents16"]); }
			if( trim($res1[0]["contents_contents17"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents17"]); }
			if( trim($res1[0]["contents_contents18"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents18"]); }
			if( trim($res1[0]["contents_contents19"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents19"]); }
			if( trim($res1[0]["contents_contents20"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents20"]); }
			if( trim($res1[0]["contents_contents21"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents21"]); }
			if( trim($res1[0]["contents_contents22"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents22"]); }
			if( trim($res1[0]["contents_contents23"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents23"]); }
			if( trim($res1[0]["contents_contents24"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents24"]); }
			if( trim($res1[0]["contents_contents25"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res1[0]["contents_contents25"]); }
			if (!empty($arr_video_id)){
				$in_video_id = trim(implode(",",$arr_video_id),",");
				
				// 総受講数
				$arr_count = array();
				$sql = "";
				$sql.= "SELECT";
				$sql.= "   COUNT(T1.student_id) AS product_type_add1_end_count";
				$sql.= " FROM";
				$sql.= "   (";
				$sql.= "     SELECT";
				$sql.= "       report_user_video_viewed.student_id,";
				$sql.= "       COUNT(report_user_video_viewed.student_id) AS product_video_comp_count";
				$sql.= "     FROM";
				$sql.= "       report_user_video_viewed";
				$sql.= "         LEFT JOIN";
				$sql.= "       student";
				$sql.= "         ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
				$sql.= "       AND report_user_video_viewed.complete_flag = 1";
				$sql.= "       AND student.student_id>0";
				if (!$nichibenren_flg){
					$sql.= "   AND student.bar_association_id='".$login_bar_association_id."' ";
				}
				$sql.= "     GROUP BY";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "   ) AS T1";
				$sql.= " WHERE";
				$sql.= "   T1.product_video_comp_count = '$video_count'";
				$arr_count = $objDbConnect->query_fetch_arr($sql);
				if ($arr_count){
					$ret[$i]['product_type_add1_end_count'] = $arr_count[0]['product_type_add1_end_count'];
				}
				
				// 受講済み数
				// [NBR-239] 詳細側(info.php)・フロント受講履歴(lesson_list1.php)と条件を統一：
				// 無効な受講生(student未紐付け)の除外・非日弁連ログイン時の弁護士会絞り込み・percent>=1（視聴開始）を追加
				$arr_count = array();
				$sql = "";
				$sql.= " SELECT";
				$sql.= "   COUNT(T1.student_id) AS product_type_add1_count";
				$sql.= " FROM";
				$sql.= "   (";
				$sql.= "     SELECT";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "     FROM";
				$sql.= "       report_user_video_viewed";
				$sql.= "         LEFT JOIN";
				$sql.= "       student";
				$sql.= "         ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       video_id IN($in_video_id)";
				$sql.= "       AND report_user_video_viewed.percent >= 1";
				$sql.= "       AND student.student_id>0";
				if (!$nichibenren_flg){
					$sql.= "       AND student.bar_association_id='".$login_bar_association_id."'";
				}
				$sql.= "     GROUP BY";
				$sql.= "       report_user_video_viewed.student_id";
				$sql.= "   ) AS T1";
				$arr_count = $objDbConnect->query_fetch_arr($sql);
				if ($arr_count){
					$ret[$i]['product_type_add1_count'] = $arr_count[0]['product_type_add1_count'];
				}
			}
		}
	}
	// 【】各レコード数の再計算 --------------------------------------------------------------



	$arr_product = $ret[0];
	//var_dump($arr_product);
	if( 0<count($arr_product) ){
	} else {
		header("Location: /index.php");
		exit();
	}

	//----------------------------------------------------------
	if( $arr_product["product_type_add"]==3 ){
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " video_id ";
		$sql.= "FROM ";
		$sql.= " tbl_ethic_question ";
		$sql.= "WHERE ";
		$sql.= " ethic_group_id='".mysqli_real_escape_string($objDbConnect->connect,  $arr_product["ethic_group_id"] )."' ";
		$res = $objDbConnect->query_fetch_arr($sql);
		$arr_video_id = array();
		for($i=0;$i<count($res);$i++){
			$video_count += 1;
			$arr_video_id[]=trim($res[0]["video_id"]);
		}
	} else {
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " contents_contents1, ";
		$sql.= " contents_contents2, ";
		$sql.= " contents_contents3, ";
		$sql.= " contents_contents4, ";
		$sql.= " contents_contents5, ";
		$sql.= " contents_contents6, ";
		$sql.= " contents_contents7, ";
		$sql.= " contents_contents8, ";
		$sql.= " contents_contents9, ";
		$sql.= " contents_contents10 ";
		$sql.= "FROM ";
		$sql.= " tbl_product ";
		$sql.= "WHERE ";
		$sql.= " product_id='".mysqli_real_escape_string($objDbConnect->connect,  $pid )."' ";
		$res = $objDbConnect->query_fetch_arr($sql);
		$video_count = 0;
		$arr_video_id = array();
		if( trim($res[0]["contents_contents1"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents1"]); }
		if( trim($res[0]["contents_contents2"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents2"]); }
		if( trim($res[0]["contents_contents3"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents3"]); }
		if( trim($res[0]["contents_contents4"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents4"]); }
		if( trim($res[0]["contents_contents5"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents5"]); }
		if( trim($res[0]["contents_contents6"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents6"]); }
		if( trim($res[0]["contents_contents7"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents7"]); }
		if( trim($res[0]["contents_contents8"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents8"]); }
		if( trim($res[0]["contents_contents9"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents9"]); }
		if( trim($res[0]["contents_contents10"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents10"]); }
	}



	// 【】重複するmember_id（student_id）を探す -------------------------------------------------------
	//※index.php の商品一覧、 info.php の商品内購入者一覧に使用
	$sql = "";
	$sql.= "SELECT tbl_order_detail.member_id ";
	$sql.= "  FROM tbl_order_detail ";
	$sql.= " WHERE tbl_order_detail.product_id ='".$pid."' ";
	$sql.= "   AND (tbl_order_detail.product_id, tbl_order_detail.member_id) ";
	$sql.= "        IN ( ";
	$sql.= "          SELECT A.product_id, A.member_id ";
	$sql.= "            FROM tbl_order_detail AS A ";
	$sql.= "           WHERE A.product_id  ='".$pid."' ";
	$sql.= "           GROUP BY A.product_id, A.member_id ";
	$sql.= "          HAVING COUNT(*) > 1 ";
	$sql.= "       )";

	// 【】重複するmember_id（student_id）のカンマ区切文字列を作成 //
	$res = $objDbConnect->query_fetch_arr($sql);
	$duplicate_member_id = "-1";
	for($i=0;$i<count($res);$i++){
		$duplicate_member_id .= ','.$res[$i]["member_id"];
	}
	// 【】重複するmember_id（student_id）を探す -------------------------------------------------------



	//----------------------------------------------------------
	$all_count = 0;
	if( $arr_product["product_type_add"]==3 ){
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " count(*) as c ";
		$sql.= "FROM ";
		$sql.= " tbl_ethic_question_history AS T1 INNER JOIN student AS T2 ON T1.student_id = T2.student_id ";
		$where = "";
		$where.= "WHERE ";
		$where.= " T1.product_id='".$pid."'";
		if (!$nichibenren_flg){
			$where.= " AND T2.bar_association_id='".$login_bar_association_id."'";
		}
		$group = "";
		$ret = $objDbConnect->query_fetch($sql.$where.$group);
		if ($ret){
			$all_count = $ret["c"];
		} else {
			$all_count = 0;
		}
	} else {
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " count(order_detail_id) as c ";
		$sql.= "FROM ";
//		$sql.= " tbl_order_detail ";
		$sql.= " tbl_order_detail INNER JOIN tbl_order on tbl_order_detail.order_id = tbl_order.order_id ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order_detail.product_id='".$pid."'";
		if (!$nichibenren_flg){
			$where.= " AND bar_association_id='".$login_bar_association_id."'";
		}



		// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------
		//if($duplicate_member_id != "-1"){
		//	$where.= " AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
		//}
		// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------



		$group = "";
		$ret = $objDbConnect->query_fetch($sql.$where.$group);
		if ($ret){
			$all_count = $ret["c"];
		} else {
			$all_count = 0;
		}
		//if ($ret){
		//	$all_count = count($ret);
		//} else {
		//	$all_count = 0;
		//}
	}
	//----------------------------------------------------------
	if( $arr_product["product_type_add"]==1 ){
		$sql = "
		SELECT
		  report_user_video_viewed.student_id,
		  MIN(report_user_video_viewed.reading_date) AS start_view_date,
		  MAX(report_user_video_viewed.reading_date) AS end_view_date,
		  MAX(report_user_video_viewed.complete_date) AS complete_date,
		  student.lawyer_number,
		  student.student_name,
		  student.student_email,
		  student.presence_passport,
		  mtb_bar_association.name AS association_name
		FROM
		  report_user_video_viewed
		    LEFT JOIN
		  student
		      ON report_user_video_viewed.student_id = student.student_id
		    LEFT JOIN
		  mtb_bar_association
		      ON student.bar_association_id = mtb_bar_association.id
		";
		//-----------------------------------
		$where = "";
		$where = " WHERE report_user_video_viewed.video_id IN($in_video_id) AND student.student_id>0 ";
		if (!$nichibenren_flg){
			$where.= "   AND student.bar_association_id='".$login_bar_association_id."' ";
		}
		//-----------------------------------
		$group = "";
		$group = " GROUP BY report_user_video_viewed.student_id ";
		//-----------------------------------
		$order = "";
		if( $search_orderby=="1" ){
			$order = " ORDER BY student.lawyer_number ASC ";
		} elseif( $search_orderby=="2" ){
			$order = " ORDER BY student.lawyer_number DESC ";
		} else {
			$order = " ORDER BY student.lawyer_number ASC ";
		}
		//-----------------------------------
		$arr_order = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
		
		// 受講率を算出
		if ($arr_order && count($arr_video_id)>0){
			$temp_arr_order = array();
			
			// 商品に紐付いている動画の総再生時間を取得
			$sql = "
			SELECT
			  SUM(TIME_TO_SEC(alfstream_duration)) AS all_video_duration
			FROM
			  video_alfstream_status
			WHERE
			  video_id IN($in_video_id)
			";
			$ret_video_alfstream_status = $objDbConnect->query_fetch_arr($sql);
			if ($ret_video_alfstream_status){
				$all_video_duration = $ret_video_alfstream_status[0]['all_video_duration']; // 総再生時間
				
				foreach ($arr_order as $key => $val){
					$temp_arr_order[$key] = $val;
					$temp_arr_order[$key]['product_type_add'] = $arr_product["product_type_add"];
					
					$percent = 0;
					$video_duration_reading = 0; // 視聴済み時間
					
					// 商品に紐付いている動画の視聴済み時間を取得
					$sql = "
					SELECT
					  TIME_TO_SEC(duration) AS duration_sec,
					  TIME_TO_SEC(duration_reading) AS duration_reading_sec,
					  complete_flag
					FROM
					  report_user_video_viewed
					WHERE
					  student_id = '".$val['student_id']."'
					  AND video_id IN($in_video_id)
					";
					$ret_report_user_video_viewed = $objDbConnect->query_fetch_arr($sql);
					if ($ret_report_user_video_viewed){
						// 動画を見終わっているかどうかで、視聴済み時間を変更
						// complete_flag=0：視聴済み時間を使用(duration_reading)
						// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
						foreach ($ret_report_user_video_viewed as $aruvv_val){
							if ($aruvv_val['complete_flag'] == '1'){
								$video_duration_reading += $aruvv_val['duration_sec'];
							} else {
								$video_duration_reading += $aruvv_val['duration_reading_sec'];
							}
						}
						$percent = $video_duration_reading / $all_video_duration * 100;
						if (!is_int($percent)){
							$percent = (int)round($percent);
						}
						if ($percent>100){
							$percent = 100;
						}
					}
					$temp_arr_order[$key]['percent'] = $percent;
				}
				
				if (!empty($temp_arr_order)){
					$arr_order = $temp_arr_order;
				}
			}
		}
		
	} else if( $arr_product["product_type_add"]==3 ){
		$sql = "";
		$sql.= "SELECT";
		$sql.= " student.student_id, ";
		$sql.= " student.student_name, ";
		$sql.= " student.student_email, ";
		$sql.= " student.lawyer_number, ";
		$sql.= " student.presence_passport, ";
		$sql.= " student.sub_auth_ethic_training, ";
		$sql.= " mtb_bar_association.name as association_name, ";//弁護士会
		$sql.= " tbl_ethic_question_history.start_date1, ";//1次開始日
		$sql.= " tbl_ethic_question_history.judge_date1, ";//1次判定日時
		$sql.= " tbl_ethic_question_history.start_date2, ";//2次開始日時
		$sql.= " tbl_ethic_question_history.judge_date2, ";//2次判定日時

		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id1, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id2, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id3, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id4, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id5, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id6, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id7, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id8, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id9, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id10, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id11, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id12, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id13, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id14, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id15, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_ethic_branch_id16, "; //解答したethic_branch_id
		$sql.= " tbl_ethic_question_history.answer_date1, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date2, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date3, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date4, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date5, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date6, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date7, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date8, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date9, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date10, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date11, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date12, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date13, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date14, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date15, "; //解答した日時
		$sql.= " tbl_ethic_question_history.answer_date16, "; //解答した日時
		$sql.= " tbl_ethic_question_history.view_flg1, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg2, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg3, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg4, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg5, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg6, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg7, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg8, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg9, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg10, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg11, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg12, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg13, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg14, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg15, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.view_flg16, "; //動画視聴フラグ（0:未視聴 1:視聴中 2:視聴済）
		$sql.= " tbl_ethic_question_history.create_date, "; //作成日時

		$sql.= " tbl_product_ethic_training.ethic_group_id, ";

		$sql.= " tbl_ethic_question_history.status, ";//ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）
		$sql.= " tbl_ethic_question_history.complete_flg, ";//1：完了
		$sql.= " tbl_product_add.product_type_add ";
		$sql.= "FROM ";
		$sql.= " tbl_ethic_question_history ";
		$sql.= " LEFT JOIN student ON tbl_ethic_question_history.student_id=student.student_id ";
		$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
		$sql.= " LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question_history.product_id=tbl_product_ethic_training.product_id ";
		$sql.= " LEFT JOIN tbl_product_add ON tbl_ethic_question_history.product_id = tbl_product_add.product_id ";

		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_ethic_question_history.product_id='".$pid."' ";
		$where.= " AND student.student_id>0 ";
		if (!$nichibenren_flg){
			$where.= " AND student.bar_association_id='".$login_bar_association_id."' ";
		}
		//-----------------------------------
		$order = "";
		if( $search_orderby=="1" ){
			$order = " ORDER BY student.lawyer_number ASC ";
		} elseif( $search_orderby=="2" ){
			$order = " ORDER BY student.lawyer_number DESC ";
		} else {
			$order = " ORDER BY student.lawyer_number ASC ";
		}
		//-----------------------------------
		$arr_order = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);

		for($i=0;$i<count($arr_order);$i++){
			$temp_count = 0;
			if($arr_order[$i]["status"]=="0"){
				$arr_order[$i]["answer_per"] = "0";
			} elseif($arr_order[$i]["status"]=="1"){
				if( trim($arr_order[$i]["answer_ethic_branch_id1"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id2"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id3"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id4"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id5"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id6"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id7"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id8"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id9"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id10"])!="" ){ $temp_count += 1; }
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / 10 * 100));
			} elseif($arr_order[$i]["status"]=="2"){
				$arr_order[$i]["answer_per"] = "100";
			} elseif($arr_order[$i]["status"]=="3"){
				if( trim($arr_order[$i]["answer_ethic_branch_id11"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id12"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id13"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id14"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id15"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id16"])!="" ){ $temp_count += 1; }
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / 6 * 100));
			} elseif($arr_order[$i]["status"]=="4"){
				if( trim($arr_order[$i]["answer_ethic_branch_id11"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id12"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id13"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id14"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id15"])!="" ){ $temp_count += 1; }
				if( trim($arr_order[$i]["answer_ethic_branch_id16"])!="" ){ $temp_count += 1; }
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / 6 * 100));
			} elseif($arr_order[$i]["status"]=="5"){
				$arr_order[$i]["answer_per"] = "100";
			} elseif($arr_order[$i]["status"]=="6"){
				$arr_order[$i]["answer_per"] = "100";
			} elseif($arr_order[$i]["status"]=="7"){
				$arr_order[$i]["answer_per"] = "100";
			} elseif($arr_order[$i]["status"]=="8"){
				$arr_order[$i]["answer_per"] = "100";
			} else {
				$arr_order[$i]["answer_per"] = "0";
			}
		}
	} else {
		$sql = "";
		$sql.= "SELECT";
		$sql.= " tbl_order_detail.order_detail_id, ";//ID
		$sql.= " student.student_id, ";
		$sql.= " student.student_name, ";
		$sql.= " student.student_email, ";
		$sql.= " student.lawyer_number, ";
		$sql.= " student.presence_passport, ";
		$sql.= " student.exp_date_passport, ";
		$sql.= " student.sub_auth_ethic_training, ";
		$sql.= " mtb_bar_association.name as association_name, ";//弁護士会
		$sql.= " tbl_product.product_id, ";//商品ID
		$sql.= " tbl_product.product_code, ";//商品コード
		$sql.= " tbl_product.product_name, ";//商品名
		$sql.= " tbl_product_add.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
		$sql.= " tbl_order_detail.pay_total, ";//金額
		$sql.= " tbl_order.create_date as buy_date, ";//購入日
		$sql.= " tbl_order_detail.payment_status, ";//支払い(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
		$sql.= " tbl_order_detail.receipt_date, ";//入金日
		$sql.= " DATE_FORMAT(tbl_order_detail.payment_date, '%Y/%m/%d') AS payment_date, ";//支払日
		$sql.= " tbl_order_detail.take_date, ";//取り込み日（権限付与日）
		$sql.= " tbl_order_detail.participation_flg, ";//会場研修参加フラグ(0:未参加 1:参加)
		$sql.= " rel_product_bar_association_branch.dates, ";//会場研修実施日
		//var_dump( trim(implode(",",$arr_video_id),",") );
		$sql.= " (  SELECT COUNT(*) AS all_view_count     FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " ) AS all_view_count, "; //視聴動画数
		$sql.= " (  SELECT COUNT(*) AS all_end_view_count FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " and report_user_video_viewed.complete_flag=1  ) AS all_end_view_count, ";//視聴完了動画数
		$sql.= " (   (  SELECT COUNT(*) AS end_view_per FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " and report_user_video_viewed.complete_flag=1  )/".count($arr_video_id)."*100   ) AS end_view_per, ";//視聴完了率
		$sql.= " (  SELECT MAX(percent) AS max_view_per FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " and report_user_video_viewed.complete_flag<>1  ) AS max_view_per, ";//視聴率
		$sql.= " (  SELECT MIN(regist_at)    AS min_date  FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " ) AS start_view_date, "; //視聴開始日
		$sql.= " (  SELECT MAX(reading_date) AS max_date  FROM report_user_video_viewed WHERE report_user_video_viewed.student_id=student.student_id "; if(count($arr_video_id)>0){ $sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) "; } $sql.= " ) AS end_view_date "; //最終視聴日
		$sql.= "FROM ";
		$sql.= " tbl_order_detail ";
		$sql.= " LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id ";
		$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
		$sql.= " LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id ";
		$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";
		$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
		$sql.= " LEFT JOIN rel_product_bar_association_branch ON tbl_order_detail.product_id=rel_product_bar_association_branch.product_id AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch.bar_association_branch_id ";

		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order_detail.product_id='".$pid."' ";
		$where.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) "; // [NBR-239] payment_status=3を除外しヘッダーの総受講者数と統一
		$where.= " AND student.student_id>0 ";
		// 新システム商品の場合
		if ($pid > 19233) {
			if ($aid != ''){
				$where.= " AND rel_product_bar_association_branch.bar_association_branch_id='".mysqli_real_escape_string($objDbConnect->connect,  $aid )."' ";
			}
		}
		if (!$nichibenren_flg){
			$where.= " AND student.bar_association_id='".$login_bar_association_id."' ";
		}


	// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------
	//if($duplicate_member_id != "-1"){
	//	$where.= " AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
	//}
	// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------


		//-----------------------------------
		$order = "";
		if( $search_orderby=="1" ){
			$order = " ORDER BY student.lawyer_number ASC ";
		} elseif( $search_orderby=="2" ){
			$order = " ORDER BY student.lawyer_number DESC ";
		} else {
			$order = " ORDER BY student.lawyer_number ASC ";
		}
		//-----------------------------------
		$arr_order = $objDbConnect->query_fetch_arr($sql.$where.$order);
	}
	//----------------------------------------------------------
}
//var_dump($ret);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( $pid=="" ){
	set_time_limit(0);
	ini_set('max_execution_time', '3600');
	//----------------------------------------------------------
	if (ob_get_level() > 0) {
		ob_end_clean();
	}
	header("Cache-Control: public");
	header('Content-Type: application/csv; charset=Shift_JIS');
	header("Content-Disposition: attachment; filename=report_product_".date("YmdHis").".csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	// 出力ストリームを開く（php://output を使用）
	$fp = fopen('php://output', 'w');

	$row = [
		"商品ID",
		"商品名",
		"商品コード",
		"弁護士会",
		"実施日",
		"総受講者",
		"受講完了者",
		"受付期間(開始)",
		"受付期間(終了)",
	];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ


	for($i=0;$i<count($ret);$i++){
		$row = [];

		if ($ret[$i]["product_type_add"]=="1"){
		}elseif( $ret[$i]["product_type_add"]=="2"){
		}elseif( $ret[$i]["product_type_add"]=="3"){
		}elseif( $ret[$i]["product_type_add"]=="4"){
		}

		$row[] = trim($ret[$i]["product_id"] );
		$row[] = trim($ret[$i]["product_name"] );
		$row[] = trim($ret[$i]["product_code"] );
		$row[] = trim($ret[$i]["bar_association_branch_name"] );
		$row[] = trim($ret[$i]["dates"] );

		if( $ret[$i]["product_type_add"]=="1" ){
			$row[] = trim($ret[$i]["product_type_add1_count"] );
			$row[] = trim($ret[$i]["product_type_add1_end_count"] );
		} elseif( $ret[$i]["product_type_add"]=="2" ){
			$row[] = trim($ret[$i]["product_type_add2_count"] );
			$row[] = trim($ret[$i]["product_type_add2_end_count"] );
		} elseif( $ret[$i]["product_type_add"]=="3" ){
			$row[] = trim($ret[$i]["product_type_add3_count"] );
			$row[] = trim($ret[$i]["product_type_add3_end_count"] );
		} elseif( $ret[$i]["product_type_add"]=="4" ){
			$row[] = "";
			$row[] = "";
		}

		$row[] = trim($ret[$i]["start_date"] );
		$row[] = trim($ret[$i]["end_date"] );

		mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
		fputcsv($fp, $row);
		fflush($fp); // 出力をフラッシュ

	}

	// ストリームを閉じる
	fclose($fp);
	exit;
	//----------------------------------------------------------
} else {
	set_time_limit(0);
	ini_set('max_execution_time', '3600');
	//----------------------------------------------------------
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=report_product_".date("YmdHis").".csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	// 出力ストリームを開く（php://output を使用）
	$fp = fopen('php://output', 'w');

	$row = [];
	$row[] = '商品名';
	$row[] = $arr_product["product_name"];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ


	$row = [];
	$row[] = '商品コード';
	$row[] = $arr_product["product_code"];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	$row[] = '商品種別';
	if( $arr_product["product_type_add"]=="1" ){
		if ($arr_product["product_kind_flg"]=="1" ){
			$row[] = 'e-ラーニング';
		} elseif ($arr_product["product_kind_flg"]=="2" ){
			$row[] = 'e-ライブ';
		} else {
			$row[] = 'e-ラーニング';
		}
	} elseif( $arr_product["product_type_add"]=="2" ){
		if ($arr_product["training_kind_flg"]=="2" ){
			$row[] = '夏季研修';
		} elseif ($arr_product["training_kind_flg"]=="3" ){
			$row[] = '新規登録弁護士研修';
		} else {
			$row[] = 'ライブ実務研修';
		}
	} elseif( $arr_product["product_type_add"]=="3" ){
		$row[] = '代替倫理研修';
	} elseif( $arr_product["product_type_add"]=="4" ){
		$row[] = 'パスポート';
	}
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	$row[] = '弁護士会';
	$row[] = $arr_product["bar_association_branch_name"];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	$row[] = '実施日';
	$row[] = $arr_product["dates"];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	//echo mb_convert_encoding('弁護士会,',  "SJIS-WIN", "UTF-8");
	//echo mb_convert_encoding('"' . $arr_product["bar_association_branch_name"] . '",',  "SJIS-WIN", "UTF-8");
	//echo mb_convert_encoding("\r\n", "SJIS-WIN", "UTF-8");
	$row[] = '総受講者';
	if ($arr_product["product_type_add"]=="1"){
		$row[] = $arr_product["product_type_add1_count"];
		$row[] = '受講完了者';
		$row[] = $arr_product["product_type_add1_end_count"];
	} elseif ($arr_product["product_type_add"]=="2"){
		$row[] = $arr_product["product_type_add2_count"];
		$row[] = '受講完了者';
		$row[] = $arr_product["product_type_add2_end_count"];
	} elseif ($arr_product["product_type_add"]=="3"){
		$row[] = $arr_product["product_type_add3_count"];
		$row[] = '受講完了者';
		$row[] = $arr_product["product_type_add3_end_count"];
	} elseif ($arr_product["product_type_add"]=="4"){
	}
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	$row[] = '公開期間';
	$row[] = $arr_product["start_date"];
	$row[] = $arr_product["end_date"];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	$row = [];
	if ($arr_product["product_type_add"]=="4"){
		$row = [
			'登録番号',
			'氏名',
			'メールアドレス',
			'弁護士会',
			'利用開始日',
			'有効期限',
		];
	} else {
		if ($arr_product["product_type_add"]=="1"){
			$row = [
				'登録番号',
				'氏名',
				'メールアドレス',
				'弁護士会',
				'FP',
				'開始日',
				'最終受講日',
				'受講完了日',
				'受講状況',
			];
		} else {
			$row = [
				'登録番号',
				'氏名',
				'メールアドレス',
				'弁護士会',
				'FP',
				'開始日',
				'最終受講日',
				'受講状況',
			];
		}
	}
	mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
	fputcsv($fp, $row);
	fflush($fp); // 出力をフラッシュ

	for($i=0;$i<count($arr_order);$i++){
		$row = [];
		if ($arr_product["product_type_add"]=="4"){
			//+++++++++++++++++++++++++++++++++++++++++++++
			$row[] = $arr_order[$i]["lawyer_number"];//登録番号
			$row[] = $arr_order[$i]["student_name"];//氏名
			$row[] = $arr_order[$i]["student_email"];//メールアドレス
			$row[] = $arr_order[$i]["association_name"];//弁護士会
			$row[] = $arr_order[$i]["payment_date"];//利用開始日
			$row[] = $arr_order[$i]["exp_date_passport"];//有効期限
			$row[] = "";//開始日
			//echo mb_convert_encoding('"",', "SJIS-WIN", "UTF-8");//最終受講日
			$row[] = "";//受講完了日
			$row[] = "";//受講状況
			//+++++++++++++++++++++++++++++++++++++++++++++
		} elseif ($arr_product["product_type_add"]=="3"){
			//+++++++++++++++++++++++++++++++++++++++++++++
			$row[] = $arr_order[$i]["lawyer_number"];//登録番号
			$row[] = $arr_order[$i]["student_name"];//氏名
			$row[] = $arr_order[$i]["student_email"];//メールアドレス
			$row[] = $arr_order[$i]["association_name"];//弁護士会
			//----------------
			//FP
			if( $arr_order[$i]["presence_passport"]=="1"){
				$row[] = "○";
			} else {
				$row[] = "-";
			}
			//----------------
			//開始日
			if ($arr_order[$i]["start_date1"]!="" && $arr_order[$i]["start_date1"]!="0000-00-00 00:00:00"){
				$row[] = $arr_order[$i]["start_date1"];
			} else {
				$row[] = "-";
			}
			//----------------
			//最終受講日
			if ($arr_order[$i]["judge_date2"]!="" && $arr_order[$i]["judge_date2"]!="0000-00-00 00:00:00"){
				$row[] = $arr_order[$i]["judge_date2"];
			} elseif ($arr_order[$i]["judge_date1"]!="" && $arr_order[$i]["judge_date1"]!="0000-00-00 00:00:00"){
				$row[] = $arr_order[$i]["judge_date1"];
			} else {
				$row[] = "-";
			}
			//----------------
			//受講状況
			if ($arr_order[$i]["status"]=="0" || $arr_order[$i]["status"]==""){
				$row[] = "未受講";
			} elseif ($arr_order[$i]["status"]=="1"){
				$row[] = "受講中";
			} elseif ($arr_order[$i]["status"]=="2"){
				$row[] = "一次○";
			} elseif ($arr_order[$i]["status"]=="3"){
				$row[] = "一次×";
			} elseif ($arr_order[$i]["status"]=="4"){
				$row[] = "一次×";
			} elseif ($arr_order[$i]["status"]=="5"){
				$row[] = "追試○";
			} elseif ($arr_order[$i]["status"]=="6"){
				$row[] = "追試×";
			} elseif ($arr_order[$i]["status"]=="7"){
				$row[] = "レポート";
			} elseif ($arr_order[$i]["status"]=="8"){
				$row[] = "会場";
			} else {
				$row[] = "";
			}
			//----------------
			//+++++++++++++++++++++++++++++++++++++++++++++
		} elseif ($arr_product["product_type_add"]=="2"){
			//+++++++++++++++++++++++++++++++++++++++++++++
			$row[] = $arr_order[$i]["lawyer_number"];//登録番号
			$row[] = $arr_order[$i]["student_name"];//氏名
			$row[] = $arr_order[$i]["student_email"];//メールアドレス
			$row[] = $arr_order[$i]["association_name"];//弁護士会
			//----------------
			//FP
			if( $arr_order[$i]["presence_passport"]=="1"){
				$row[] = "○";
			} else {
				$row[] = "-";
			}
			//----------------
			$row[] = $arr_order[$i]["dates"];//開始日
			//echo mb_convert_encoding('"",', "SJIS-WIN", "UTF-8");//最終受講日
			$row[] = "";//受講完了日
			//----------------
			//受講状況
			if ($arr_order[$i]["participation_flg"]=="1"){
				$row[] = "100%";
			} else {
				$row[] = "0%";
			}
			//----------------
			//+++++++++++++++++++++++++++++++++++++++++++++
		} elseif ($arr_product["product_type_add"]=="1"){
			//+++++++++++++++++++++++++++++++++++++++++++++
			$row[] = $arr_order[$i]["lawyer_number"];//登録番号
			$row[] = $arr_order[$i]["student_name"];//氏名
			$row[] = $arr_order[$i]["student_email"];//メールアドレス
			$row[] = $arr_order[$i]["association_name"];//弁護士会
			//----------------
			//FP
			if( $arr_order[$i]["presence_passport"]=="1"){
				$row[] = "○";
			} else {
				$row[] = "-";
			}
			//----------------
			$row[] = $arr_order[$i]["start_view_date"];//開始日
			$row[] = $arr_order[$i]["end_view_date"];//最終受講日
			$row[] = $arr_order[$i]["complete_date"];//受講完了日
			//----------------
			//受講状況
			$row[] = $arr_order[$i]["percent"].'%';
			//if ($arr_order[$i]["all_end_view_count"]==$video_count){
			//	echo mb_convert_encoding('"100%",',                "SJIS-WIN", "UTF-8");
			//} else {
			//	if ($arr_order[$i]["all_end_view_count"]=="0"){
			//		echo mb_convert_encoding('"0%",',                "SJIS-WIN", "UTF-8");
			//	} else {
			//		if ($arr_order[$i]["max_view_per"]!=""){
			//			echo mb_convert_encoding('"' . $arr_order[$i]["max_view_per"] . '%",',                "SJIS-WIN", "UTF-8");
			//		} else {
			//			echo mb_convert_encoding('"0%",',                "SJIS-WIN", "UTF-8");
			//		}
			//	}
			//}
			//----------------
			//+++++++++++++++++++++++++++++++++++++++++++++
		}
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		mb_convert_variables('SJIS-WIN', 'UTF-8', $row);
		fputcsv($fp, $row);
		fflush($fp); // 出力をフラッシュ
		//+++++++++++++++++++++++++++++++++++++++++++++
	}
	// ストリームを閉じる
	fclose($fp);
	exit;
	//----------------------------------------------------------
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
