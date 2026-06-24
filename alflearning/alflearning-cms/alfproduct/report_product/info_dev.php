<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
$template = new Template();
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
$mode = "";
$pid = "";
$product_id = "";
$search_orderby = "1";
if( isset($_GET["pid"]) && !empty($_GET["pid"]) ){
	$pid = intval($_GET["pid"]);
	$product_id = intval($_GET["pid"]);
}
if($product_id == ""){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( isset($_GET["aid"]) && !empty($_GET["aid"]) ){
	$aid = intval($_GET["aid"]);
} else {
	$aid = '';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 旧システムデータの場合
if ($pid <= 19233) {
	//if ($aid == '119' || $aid == '120' || $aid == '121' || $aid == '122' || $aid == '123' || $aid == '124' || $aid == '125' || $aid == '126'){
		$old_pid = $pid - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$aid = $res_bar_association_branch_id[0]['bar_association_branch_id'];
		} else {
			$aid = '1';
		}
	//}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp = get_mtb_live_training_type();
foreach ($tmp as $key => $val){
	$mtb_live_training_type[$key] = $val;
}
$template->assign('mtb_live_training_type', $mtb_live_training_type);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( isset($_SESSION["report_product.info.search_orderby"]) && !empty($_SESSION["report_product.info.search_orderby"]) ){
	$search_orderby = $_SESSION["report_product.info.search_orderby"];
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_orderby = strip_tags(trim($_POST["search_orderby"]));
	$_SESSION["report_product.info.search_orderby"] = $search_orderby;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["report_product.info.page"]) && !empty($_SESSION["report_product.info.page"]) ){
	$page = $_SESSION["report_product.info.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["report_product.info.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= " tbl_product.product_id, ";
$sql.= " tbl_product.product_code, ";
$sql.= " tbl_product.product_name, ";
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
if ($pid <= 19233) {
$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
} else {
$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
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
//var_dump($sql.$where);
$ret = $objDbConnect->query_fetch_arr($sql.$where);




// 【】各レコード数の再計算 --------------------------------------------------------------
for($i=0;$i<count($ret);$i++){

	$pid = $ret[$i]["product_id"];	// 商品ID

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
/*
	// product_type_add2_count -----------------------------------------------------------
	if($ret[$i]["product_type_add2_count"]){
		$sql2_where = "";
		$sql2_where.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) ";
		$sql2_where.= " AND tbl_order_detail.product_type_add = 2 ";
		$sql2_where.= " AND tbl_order_detail.bar_association_branch_id = ".$ret[$i]["rel_product_bar_association_branch_bar_association_branch_id"]." "; 

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
					$ret[$i]["product_type_add2_count"] = $res_count[0]['r_count'];
				}
			}
		}
	}
	// 旧システムデータの場合
	if ($ret[$i]["product_id"] <= 19233) {
		$old_pid = $ret[$i]["product_id"] - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$ret[$i]["product_type_add2_count"] = get_entry_number($res_bar_association_branch_id[0]['bar_association_branch_id'], $ret[$i]["product_id"]);
		}
	} 

	// product_type_add2_end_count -----------------------------------------------------------
	if($ret[$i]["product_type_add2_end_count"]){
		$sql2_where = "";
		$sql2_where.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) ";
		$sql2_where.= " AND tbl_order_detail.product_type_add = 2 ";
		$sql2_where.= " AND tbl_order_detail.bar_association_branch_id = ".$ret[$i]["rel_product_bar_association_branch_bar_association_branch_id"].""; 
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
					$ret[$i]["product_type_add2_end_count"] = $res_count[0]['r_count'];
				}
			}
		}
	}
	// 旧システムデータの場合
	if ($ret[$i]["product_id"] <= 19233) {
		$old_pid = $ret[$i]["product_id"] - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$ret[$i]["product_type_add2_end_count"] = get_comp_number($res_bar_association_branch_id[0]['bar_association_branch_id'], $ret[$i]["product_id"]);
		}
	}
*/
}
// 【】各レコード数の再計算 --------------------------------------------------------------





$arr_product = $ret[0];
//var_dump($arr_product);
if( 0<count($arr_product) ){
} else {
	header("Location: /index.php");
	exit();
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
	if( trim($res[0]["contents_contents11"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents11"]); }
	if( trim($res[0]["contents_contents12"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents12"]); }
	if( trim($res[0]["contents_contents13"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents13"]); }
	if( trim($res[0]["contents_contents14"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents14"]); }
	if( trim($res[0]["contents_contents15"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents15"]); }
	if( trim($res[0]["contents_contents16"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents16"]); }
	if( trim($res[0]["contents_contents17"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents17"]); }
	if( trim($res[0]["contents_contents18"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents18"]); }
	if( trim($res[0]["contents_contents19"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents19"]); }
	if( trim($res[0]["contents_contents20"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents20"]); }
	if( trim($res[0]["contents_contents21"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents21"]); }
	if( trim($res[0]["contents_contents22"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents22"]); }
	if( trim($res[0]["contents_contents23"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents23"]); }
	if( trim($res[0]["contents_contents24"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents24"]); }
	if( trim($res[0]["contents_contents25"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents25"]); }
}



// 【】重複するmember_id（student_id）を探す -------------------------------------------------------
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

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$all_count = 0;
if( $arr_product["product_type_add"]==1 ){
	$in_video_count = count($arr_video_id);
	$in_video_id = trim(implode(",",$arr_video_id),",");
	
	$sql = "";
	$sql.= "SELECT";
	$sql.= "   COUNT(T1.student_id) AS c";
	$sql.= " FROM";
	$sql.= "   (";
	$sql.= "     SELECT";
	$sql.= "       report_user_video_viewed.student_id";
	$sql.= "     FROM";
	$sql.= "       report_user_video_viewed";
	$sql.= "         LEFT JOIN";
	$sql.= "       student";
	$sql.= "           ON report_user_video_viewed.student_id = student.student_id";
	$sql.= "         LEFT JOIN";
	$sql.= "       mtb_bar_association";
	$sql.= "           ON student.bar_association_id = mtb_bar_association.id";
	$sql.= "     WHERE";
	$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
	$sql.= "       AND student.student_id>0";
	if (!$nichibenren_flg){
		$sql.= "   AND student.bar_association_id='".$login_bar_association_id."'";
	}
	$sql.= "     GROUP BY";
	$sql.= "       report_user_video_viewed.student_id";
	$sql.= "   ) AS T1";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$all_count = $ret["c"];
	} else {
		$all_count = 0;
	}
	
} else if( $arr_product["product_type_add"]==3 ){
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
	$sql.= " count(tbl_order_detail.order_detail_id) as c ";
	$sql.= "FROM ";
//	$sql.= " tbl_order_detail ";
	$sql.= " tbl_order_detail INNER JOIN tbl_order on tbl_order_detail.order_id = tbl_order.order_id ";
	$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.product_id='".$pid."'";
	$where.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) ";
	$where.= " AND student.student_id>0 ";
	if (!$nichibenren_flg){
		//$where.= " AND tbl_order_detail.bar_association_id='".$login_bar_association_id."'";
		$where.= " AND student.bar_association_id='".$login_bar_association_id."'";
	}
	// 新システム商品の場合
	if ($pid > 19233) {
		if ($aid != ''){
			$where.= " AND tbl_order_detail.bar_association_branch_id='".mysqli_real_escape_string($objDbConnect->connect,  $aid )."' ";
		}
	}

	// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------
	//if($duplicate_member_id != "-1"){
	//	$where.= "AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
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

$objAdminPager->setListMax($all_count);
$objAdminPager->setPagerUrl("?pid=".$pid."&aid=".$aid."&page=");
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();

//print("[product_type_add:".$arr_product["product_type_add"]."]");
//print("<hr>");
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
//print("[sql1:".$sql.$where.$group.$order.$offset."]");
//print("<hr>");
	$arr_order = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
//print("[arr_order]");
//var_dump($arr_order);
//print("<hr>");
	
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
				
				$percent = 0;
				$video_duration_reading = 0; // 視聴済み時間
				
				$video_comp_count = 0;
				
				// 商品に紐付いている動画の視聴済み時間を取得
				$sql = "
				SELECT
				  student_id,
				  video_id,
				  TIME_TO_SEC(duration) AS duration_sec,
				  TIME_TO_SEC(duration_reading) AS duration_reading_sec,
				  complete_flag
				FROM
				  report_user_video_viewed
				WHERE
				  student_id = '".$val['student_id']."'
				  AND video_id IN($in_video_id)
				";
//print("[sql2:".$sql."]");
//print("<hr>");
				$ret_report_user_video_viewed = $objDbConnect->query_fetch_arr($sql);
//print("[ret_report_user_video_viewed]");
//var_dump($ret_report_user_video_viewed);
//print("<hr>");
				if ($ret_report_user_video_viewed){
					// 動画を見終わっているかどうかで、視聴済み時間を変更
					// complete_flag=0：視聴済み時間を使用(duration_reading)
					// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
					foreach ($ret_report_user_video_viewed as $aruvv_val){
						if ($aruvv_val['complete_flag'] == '1'){
							$video_duration_reading += $aruvv_val['duration_sec'];
							$video_comp_count++;
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
//print("[percent:".$percent."]");
//print("<hr>");
//print("[video_comp_count:".$video_comp_count."]");
//print("<hr>");
				$temp_arr_order[$key]['percent'] = $percent;
				$temp_arr_order[$key]['video_comp_count'] = $video_comp_count;
				
				// 設問付きeラーニングの場合
				if($arr_product['product_kind_flg']=='3'){
					// 研修動画のパーセントを取得(視聴完了していない動画の最大値)
					$temp_arr_order[$key]['max_percent_video'] = 0;
					
					$sql = "
					SELECT
					  MAX(percent) AS max_percent_video
					FROM
					  report_user_video_viewed
					WHERE
					  student_id = '".$val['student_id']."'
					  AND video_id IN($in_video_id)
					  AND complete_flag=0
					";
					$ret_report_user_video_viewed_2 = $objDbConnect->query_fetch($sql);
					if ($ret_report_user_video_viewed_2){
						if(!is_null($ret_report_user_video_viewed_2['max_percent_video'])){
							$temp_arr_order[$key]['max_percent_video'] = $ret_report_user_video_viewed_2['max_percent_video'];
						}
					}
					
					// 全設問数を取得
					$temp_arr_order[$key]['max_val_exam'] = 0;
					
					$sql = "
					SELECT
					  COUNT(*) AS max_val_exam
					FROM
					  rel_product_contents
					WHERE
					  product_id='".$pid."'
					";
					$ret_rel_product_contents = $objDbConnect->query_fetch($sql);
					if ($ret_rel_product_contents){
						$temp_arr_order[$key]['max_val_exam'] = $ret_rel_product_contents['max_val_exam'];
					}
					
					// 設問の解答数を取得
					$temp_arr_order[$key]['answer_val_exam'] = 0;
					
					$sql = "
					SELECT
					  passing_flg,
					  criteria_type,
					  criteria_value
					FROM
					  exam_answer
					WHERE
					  status=0
					  AND product_id='".$pid."'
					  AND student_id='".$val['student_id']."'
					GROUP BY
					  contents_no
					";
					$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);
					if ($ret_exam_answer){
						$temp_arr_order[$key]['answer_val_exam'] = count($ret_exam_answer);
					}
					
					// 合否
					$temp_arr_order[$key]['gouhi'] = '-'; // 判定基準がない場合の表示
					
					$sql = "
					SELECT
					  *
					FROM
					  rel_product_contents
					WHERE
					  product_id='".$pid."'
					";
					$ret_rel_product_contents = $objDbConnect->query_fetch_arr($sql);
					if ($ret_rel_product_contents){
						foreach($ret_rel_product_contents as $val_rpc){
							// 判定基準が設定されているか確認
							if($val_rpc['exam_id_test']>0){
								$sql = "SELECT criteria_type FROM exam WHERE exam_id='".$val_rpc['exam_id_test']."'";
								$ret_exam = $objDbConnect->query_fetch($sql);
								if ($ret_exam){
									if($ret_exam['criteria_type']>0){
										$temp_arr_order[$key]['gouhi'] = '未実施';
										break;
									}
								}
							}
						}
						
						// 合否判定
						if($temp_arr_order[$key]['gouhi']!='-'){
							// すべてのテストを受講済みかどうかチェック
							$product_contents_total = 0;
							$sql = "SELECT COUNT(*) AS count FROM rel_product_contents WHERE product_id='".$pid."' AND exam_id_test>0";
							$res_rel_product_contents = $objDbConnect->query_fetch($sql);
							if($res_rel_product_contents){
								$product_contents_total = $res_rel_product_contents['count'];
							}
							
							$exam_answer_total = 0;
							$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id='".$pid."' AND student_id='".$val['student_id']."' GROUP BY contents_no";
							$res_exam_answer = $objDbConnect->query_fetch_arr($sql);
							if($res_exam_answer){
								$exam_answer_total = count($res_exam_answer);
							}
							
							// すべて受講済みの場合に合否判定する
							if($product_contents_total>0 && $exam_answer_total>0 && ($product_contents_total==$exam_answer_total)){
								$temp_arr_order[$key]['gouhi'] = '合格';
								foreach($res_exam_answer as $val_rea){
									if($val_rea['passing_flg']=='0'){
										$temp_arr_order[$key]['gouhi'] = '不合格';
										break;
									}
								}
							}
						}
					}
				}
			}
			
			if (!empty($temp_arr_order)){
				$arr_order = $temp_arr_order;
			}
		}
	}
	
} else if( $arr_product["product_type_add"]==3 ){
	$sql = "";
	$sql.= "SELECT ";
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
	$sql.= " tbl_ethic_question_history.complete_flg ";//1：完了
	$sql.= "FROM ";
	$sql.= " tbl_ethic_question_history ";
	$sql.= " LEFT JOIN student ON tbl_ethic_question_history.student_id=student.student_id ";
	$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
	$sql.= " LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question_history.product_id=tbl_product_ethic_training.product_id ";

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

		$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question WHERE failure_flg=0 AND ethic_group_id=?";
		$question_count = 0;
		$arr_question = $objDbConnect->query_fetch_arr($sql_sub, [$arr_order[$i]["ethic_group_id"]]);
		if( !empty($arr_question) ){
			$question_count = count($arr_question);
		}
		$arr_order[$i]["question_count1"] = $question_count;

		$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question WHERE failure_flg=1 AND ethic_group_id=?";
		$question_count = 0;
		$arr_question = $objDbConnect->query_fetch_arr($sql_sub, [$arr_order[$i]["ethic_group_id"]]);
		if( !empty($arr_question) ){
			$question_count = count($arr_question);
		}
		$arr_order[$i]["question_count2"] = $question_count;

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
			if( $arr_order[$i]["question_count1"]>0 ){
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / $arr_order[$i]["question_count1"] * 100));
			} else {
				$arr_order[$i]["answer_per"] = 0;
			}
		} elseif($arr_order[$i]["status"]=="2"){
			$arr_order[$i]["answer_per"] = "100";
		} elseif($arr_order[$i]["status"]=="3"){
			if( trim($arr_order[$i]["answer_ethic_branch_id11"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id12"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id13"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id14"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id15"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id16"])!="" ){ $temp_count += 1; }
			//$arr_order[$i]["answer_per"] = trim((int)($temp_count / 6 * 100));
			if( $arr_order[$i]["question_count2"]>0 ){
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / $arr_order[$i]["question_count2"] * 100));
			} else {
				$arr_order[$i]["answer_per"] = 0;
			}
		} elseif($arr_order[$i]["status"]=="4"){
			if( trim($arr_order[$i]["answer_ethic_branch_id11"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id12"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id13"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id14"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id15"])!="" ){ $temp_count += 1; }
			if( trim($arr_order[$i]["answer_ethic_branch_id16"])!="" ){ $temp_count += 1; }
			//$arr_order[$i]["answer_per"] = trim((int)($temp_count / 6 * 100));
			if( $arr_order[$i]["question_count2"]>0 ){
				$arr_order[$i]["answer_per"] = trim((int)($temp_count / $arr_order[$i]["question_count2"] * 100));
			} else {
				$arr_order[$i]["answer_per"] = 0;
			}
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
	$sql.= "SELECT ";
	$sql.= " tbl_order_detail.order_detail_id, ";//ID
	$sql.= " student.student_id, ";
	$sql.= " student.student_name, ";
	$sql.= " student.student_email, ";
	$sql.= " student.lawyer_number, ";
	$sql.= " student.presence_passport, ";
	$sql.= " student.sub_auth_ethic_training, ";
	$sql.= " mtb_bar_association.name as association_name, ";//弁護士会
	$sql.= " tbl_product.product_id, ";//商品ID
	$sql.= " tbl_product.product_code, ";//商品コード
	$sql.= " tbl_product.product_name, ";//商品名
	$sql.= " tbl_product.contents_contents1, ";
	$sql.= " tbl_product.contents_contents2, ";
	$sql.= " tbl_product.contents_contents3, ";
	$sql.= " tbl_product.contents_contents4, ";
	$sql.= " tbl_product.contents_contents5, ";
	$sql.= " tbl_product.contents_contents6, ";
	$sql.= " tbl_product.contents_contents7, ";
	$sql.= " tbl_product.contents_contents8, ";
	$sql.= " tbl_product.contents_contents9, ";
	$sql.= " tbl_product.contents_contents10, ";
	$sql.= " tbl_product.contents_contents11, ";
	$sql.= " tbl_product.contents_contents12, ";
	$sql.= " tbl_product.contents_contents13, ";
	$sql.= " tbl_product.contents_contents14, ";
	$sql.= " tbl_product.contents_contents15, ";
	$sql.= " tbl_product.contents_contents16, ";
	$sql.= " tbl_product.contents_contents17, ";
	$sql.= " tbl_product.contents_contents18, ";
	$sql.= " tbl_product.contents_contents19, ";
	$sql.= " tbl_product.contents_contents20, ";
	$sql.= " tbl_product.contents_contents21, ";
	$sql.= " tbl_product.contents_contents22, ";
	$sql.= " tbl_product.contents_contents23, ";
	$sql.= " tbl_product.contents_contents24, ";
	$sql.= " tbl_product.contents_contents25, ";
	$sql.= " tbl_product_add.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
	$sql.= " tbl_order_detail.pay_total, ";//金額
	$sql.= " tbl_order.create_date as buy_date, ";//購入日
	$sql.= " tbl_order_detail.payment_status, ";//支払い(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
	$sql.= " tbl_order_detail.receipt_date, ";//入金日
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
	$where.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) ";
	$where.= " AND student.student_id>0 ";
	// 新システム商品の場合
	if ($pid > 19233) {
		if ($aid != ''){
			$where.= " AND rel_product_bar_association_branch.bar_association_branch_id='".mysqli_real_escape_string($objDbConnect->connect,  $aid )."' ";
		}
	}

	// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------
	//if($duplicate_member_id != "-1"){
	//	$where.= "AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ";
	//}
	// 【】重複したmember_id（student_id）、且つ「web_flg=1」　に一致しないこと ------------------------------

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
}
//var_dump( $sql.$where.$group.$order );



// eラーニングの場合は、総受講者数の再取得(視聴ログベースで)
if( $arr_product["product_type_add"]==1 ){
	// 総受講数
	$arr_product['product_type_add1_count'] = $all_count;
	
	// 受講済み数
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
	if (!$nichibenren_flg){
		$sql.= "   AND student.bar_association_id='".$login_bar_association_id."' ";
	}
	$sql.= "     GROUP BY";
	$sql.= "       report_user_video_viewed.student_id";
	$sql.= "   ) AS T1";
	$sql.= " WHERE";
	$sql.= "   T1.product_video_comp_count = '$in_video_count'";
	$sql.= " AND student.student_id>0 ";
	$arr_count = $objDbConnect->query_fetch_arr($sql);
	if ($arr_count){
		$arr_product['product_type_add1_end_count'] = $arr_count[0]['product_type_add1_end_count'];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("レポート");
$template->admin_comment("月毎の集計レポートを表示します");
$temp_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
$temp_bar_association_name = "管理者";
if( $temp_bar_association_id>1 ){
	$sql  = '';
	$sql .= "SELECT  id ";
	$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
	$sql .= " FROM mtb_bar_association ";
	$sql .= " WHERE id = ".intval($temp_bar_association_id)." ";
	$headret = $objDbConnect->query_fetch($sql);
	if( isset($headret["name"]) ){
		$temp_bar_association_name = '【'.$headret["name"].'】';
	}
}
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
}

$template->admin_school($arr_session["cms_master.login.school_name"]);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nichibenren_flg){
$sidemenu_html ='<ul>
<li><a href="/cms_report/cms_video/">コンテンツ*</a></li>
<li class="selected"><a href="/alfproduct/report_product/index.php">商品</a></li>
<li><a href="/cms_report/cms_user/">ユーザ</a></li>
<li><a href="/alfproduct/report_all/index.php">全体確認</a></li>
</ul>';
} else {
$sidemenu_html ='<ul>
<li class="selected"><a href="/alfproduct/report_product/index.php">商品</a></li>
<li><a href="/cms_report/cms_user/">ユーザ</a></li>
<li><a href="/alfproduct/report_all/index.php">全体確認</a></li>
</ul>';
}
$template->admin_sidemenu($sidemenu_html);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('aid', $aid);
//------------------------------------------------------------
$template->assign('video_count', $video_count);

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_product', $arr_product);
$template->assign('arr_order', $arr_order);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('search_orderby', $search_orderby);
//------------------------------------------------------------
$template->assign('post_data', serialize($_POST));
//------------------------------------------------------------
$template->assign('page_name', 'report_product');
$template->admin_layout('report_product/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
