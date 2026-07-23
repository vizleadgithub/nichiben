<?php
set_time_limit(180);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
date_default_timezone_set('Asia/Tokyo');
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// 日弁連フラグ
$nichibenren_flg = true;  // 日弁連
//$nichibenren_flg = false; // 日弁連以外の弁護士会

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

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$aid = '';
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
$arr_list = array();
if( $pid=="" ){
	//----------------------------------------------------------
	$group = "";
	$order = " ORDER BY product_id DESC ";
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
	$sql.= " mtb_bar_association_branch.bar_association_branch_name AS bar_association_branch_name, ";
	$sql.= " mtb_bar_association.name AS bar_association_name, ";
	$sql.= " rel_product_bar_association_branch.bar_association_branch_id as rel_product_bar_association_branch_bar_association_branch_id, ";
	if (!$nichibenren_flg){
		$product_type_add_where = " AND tbl_order_detail.bar_association_id = '$login_bar_association_id' ";
		$product_type_add3_where = " AND student.bar_association_id = '$login_bar_association_id' ";
	} else {
		$product_type_add_where = "";
		$product_type_add3_where = "";
	}
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add1_count, ";
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 ) AS product_type_add1_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add1_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 $product_type_add_where ) AS product_type_add1_end_count, ";

	// [NBR-239] payment_status=3（仮払い等）はフロント受講履歴(lesson_list2.php)と揃えて対象外にする。create_date条件(過去データ集計用)はそのまま維持
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id $product_type_add_where ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 $product_type_add_where ) AS product_type_add2_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 ) AS product_type_add2_end_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.create_date<'2016-12-01 00:00:00' AND tbl_ethic_question_history.product_id=tbl_product.product_id $product_type_add3_where ) AS product_type_add3_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE tbl_ethic_question_history.create_date<'2016-12-01 00:00:00' AND tbl_ethic_question_history.product_id=tbl_product.product_id AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) $product_type_add3_where ) AS product_type_add3_end_count ";
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
	$sql.= " LEFT JOIN rel_product_bar_association_branch ON tbl_product.product_id=rel_product_bar_association_branch.product_id ";
	$sql.= " LEFT JOIN import_kenshu_count ON tbl_product.product_id=import_kenshu_count.KENSHU_ID + 10000 ";
	$sql.= " LEFT JOIN mtb_bar_association_branch ON rel_product_bar_association_branch.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id ";
	$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association_branch.bar_association_id=mtb_bar_association.id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_product.del_flg=0 ";
	$where.= " AND ( ( import_kenshu_count.ROOT_ID<>0 AND tbl_product_live_training.ethic_flg=0 ) OR tbl_product_live_training.ethic_flg=1 OR import_kenshu_count.ROOT_ID IS NULL ) ";
	$where.= " AND ( rel_product_bar_association_branch.web_flg<>2 OR rel_product_bar_association_branch.web_flg IS NULL ) ";
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	$temp_where = "";
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);

	if( 0<count($ret) ){
	} else {
		print("end");
		exit();
	}

//var_dump($ret);
//exit();

	// 【】各レコード数の再計算 --------------------------------------------------------------
	$arr_list = array();
	$pre_pid = "0";
	for($i=0;$i<count($ret);$i++){

		$pid = $ret[$i]["product_id"];	// 商品ID

		// 旧システムデータで申込み人数0のライブ実務研修は結果から省く
		if ($pid<=19233 && $ret[$i]['product_type_add']=='2' && $ret[$i]['training_kind_flg']=='1' && $ret[$i]['product_type_add2_count']=='0') {
			//unset($ret[$i]);
			continue;
		}

		if ( $pre_pid==$pid && (($ret[$i]['product_type_add']=='2' && $ret[$i]['ethic_flg']=='1') || $ret[$i]['product_type_add']=='3') ) {
			continue;
		}


		// 【】重複するmember_id（student_id）を探す //
		$sql2 = "";
		$sql2.= "SELECT tbl_order_detail.member_id ";
		$sql2.= "  FROM tbl_order_detail ";
		$sql2.= " WHERE tbl_order_detail.create_date<'2016-12-01 00:00:00' AND tbl_order_detail.product_id ='".$pid."' ";
		$sql2.= "   AND (tbl_order_detail.product_id, tbl_order_detail.member_id) ";
		$sql2.= "        IN ( ";
		$sql2.= "          SELECT A.product_id, A.member_id ";
		$sql2.= "            FROM tbl_order_detail AS A ";
		$sql2.= "           WHERE A.create_date<'2016-12-01 00:00:00' AND A.product_id  ='".$pid."' ";
		$sql2.= "           GROUP BY A.product_id, A.member_id ";
		$sql2.= "          HAVING COUNT(*) > 1 ";
		$sql2.= "       )";

		// product_type_add1_count -----------------------------------------------------------
		if($ret[$i]["product_type_add1_count"]){
			$sql2_where = "";
			$sql2_where.= " AND tbl_order_detail.payment_status   = 2 ";
			$sql2_where.= " AND tbl_order_detail.product_type_add = 1 ";
			$sql2_where.= " AND tbl_order_detail.create_date<'2016-12-01 00:00:00' ";
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
			$sql2_where.= " AND tbl_order_detail.create_date<'2016-12-01 00:00:00' ";

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
		
		if ($pid<=19233){
			$ret[$i]["product_type_add2_count"] = $ret[$i]["product_type_add2_count_kako"];
			$ret[$i]["product_type_add2_end_count"] = $ret[$i]["product_type_add2_end_count_kako"];
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
				// [NBR-239] 詳細側(info.php)と条件を統一：無効な受講生(student未紐付け)のログを除外
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
				// student JOIN・無効studentの除外・非日弁連ログイン時の弁護士会絞り込み・percent>=1（視聴開始）を追加
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
				$sql.= "           ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
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

	//print "<br>10<br>".$sql2.$sql2_where;
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
					
	//print "<br>11<br>".$sql3.$sql2_where;
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

	//print "<br>12<br>".$sql2.$sql2_where;
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
					
	//print "<br>13<br>".$sql3.$sql2_where;
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
				// [NBR-239] 詳細側(info.php)と条件を統一：無効な受講生(student未紐付け)のログを除外
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
				// student JOIN・無効studentの除外・非日弁連ログイン時の弁護士会絞り込み・percent>=1（視聴開始）を追加
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
				$sql.= "           ON report_user_video_viewed.student_id = student.student_id";
				$sql.= "     WHERE";
				$sql.= "       report_user_video_viewed.video_id IN($in_video_id)";
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
	print("End");
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
		if( trim($res[0][contents_contents1])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents1"]); }
		if( trim($res[0][contents_contents2])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents2"]); }
		if( trim($res[0][contents_contents3])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents3"]); }
		if( trim($res[0][contents_contents4])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents4"]); }
		if( trim($res[0][contents_contents5])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents5"]); }
		if( trim($res[0][contents_contents6])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents6"]); }
		if( trim($res[0][contents_contents7])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents7"]); }
		if( trim($res[0][contents_contents8])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents8"]); }
		if( trim($res[0][contents_contents9])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents9"]); }
		if( trim($res[0][contents_contents10])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents10"]); }
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
		$where = " WHERE report_user_video_viewed.video_id IN($in_video_id) ";
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
		$sql.= " tbl_ethic_question_history.status, ";//ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）
		$sql.= " tbl_ethic_question_history.complete_flg, ";//1：完了
		$sql.= " tbl_product_add.product_type_add ";
		$sql.= "FROM ";
		$sql.= " tbl_ethic_question_history ";
		$sql.= " LEFT JOIN student ON tbl_ethic_question_history.student_id=student.student_id ";
		$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
		$sql.= " LEFT JOIN tbl_product_add ON tbl_ethic_question_history.product_id = tbl_product_add.product_id ";

		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_ethic_question_history.product_id='".$pid."' ";
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
	//ファイル名
	$sFileName =         date('YmdHis').'.txt';
	//ファイルパス
	$sPath =             '/tmp/'.$sFileName;

	//ファイルの存在確認
	if(file_exists($sPath)){
	    echo '・指定ファイルが既に存在しております'."\n";
	    exit;
	}else{
	    echo '・ファイルの存在確認完了'."\n";
	}

	//ファイルを作成
	if(touch($sPath)){
	    echo '・ファイル作成完了'."\n";
	}else{
	    echo '・ファイル作成失敗'."\n";
	    exit;
	}

	//ファイルのパーティションの変更
	if(chmod($sPath,0644)){
	    echo '・ファイルパーミッション変更完了'."\n";
	}else{
	    echo '・ファイルパーミッション変更失敗'."\n";
	    exit;
	}

	//ファイルをオープン
	//if($filepoint = fopen($sPath,"w")){
	if($filepoint = fopen($sPath,"ab")){
	    echo '・ファイルオープン完了'."\n";
	}else{
	    echo '・ファイルオープン失敗'."\n";
	    exit;
	}

	//ファイルのロック
	if(flock($filepoint, LOCK_EX)){
	    echo '・ファイルロック完了'."\n";
	}else{
	    echo '・ファイルロック失敗'."\n";
	    exit;
	}

	//----------------------------------------------------------
	fwrite($filepoint,  "商品ID,商品名,商品コード,弁護士会,実施日,総受講者,受講完了者,受付期間(開始),受付期間(終了),\r\n");


	for($i=0;$i<count($ret);$i++){
		$temp = "";

		if ($ret[$i]["product_type_add"]=="1"){
		}elseif( $ret[$i]["product_type_add"]=="2"){
		}elseif( $ret[$i]["product_type_add"]=="3"){
		}elseif( $ret[$i]["product_type_add"]=="4"){
		}

		$temp .= '"'. trim($ret[$i]["product_id"] )     . '",';
		$temp .= '"'. trim($ret[$i]["product_name"] )   . '",';
		$temp .= '"'. trim($ret[$i]["product_code"] )   . '",';
		$temp .= '"'. trim($ret[$i]["bar_association_branch_name"] ) . '",';
		$temp .= '"'. trim($ret[$i]["dates"] )          . '",';

		if( $ret[$i]["product_type_add"]=="1" ){
			$temp .= '"'. trim($ret[$i]["product_type_add1_count"] )     . '",';
			$temp .= '"'. trim($ret[$i]["product_type_add1_end_count"] )   . '",';
		} elseif( $ret[$i]["product_type_add"]=="2" ){
			$temp .= '"'. trim($ret[$i]["product_type_add2_count"] )     . '",';
			$temp .= '"'. trim($ret[$i]["product_type_add2_end_count"] )   . '",';
		} elseif( $ret[$i]["product_type_add"]=="3" ){
			$temp .= '"'. trim($ret[$i]["product_type_add3_count"] )     . '",';
			$temp .= '"'. trim($ret[$i]["product_type_add3_end_count"] )   . '",';
		} elseif( $ret[$i]["product_type_add"]=="4" ){
			$temp .= '"",';
			$temp .= '"",';
		}

		$temp .= '"'. trim($ret[$i]["start_date"] )     . '",';
		$temp .= '"'. trim($ret[$i]["end_date"] )       . '",';

		$temp .= "\r\n";

		fwrite($filepoint,  $temp);
	}
	//----------------------------------------------------------

	//ファイルのアンロック
	if(flock($filepoint, LOCK_UN)){
	    echo '・ファイルアンロック完了'."\n";
	}else{
	    echo '・ファイルアンロック失敗'."\n";
	    exit;
	}

	//ファイルを閉じる
	if(fclose($filepoint)){
	    echo '・ファイルクローズ完了'."\n";
	}else{
	    echo '・ファイルクローズ失敗'."\n";
	    exit;
	}

	exit();

} else {
	//----------------------------------------------------------
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=report_product_".date("YmdHis").".csv");

	echo '商品名,';
	echo '"' . $arr_product["product_name"] . '",';
	echo "\r\n";

	echo '商品コード,';
	echo '"' . $arr_product["product_code"] . '",';
	echo "\r\n";

	echo '商品種別,';
	if( $arr_product["product_type_add"]=="1" ){
		if ($arr_product["product_kind_flg"]=="1" ){
			echo 'e-ラーニング,';
		} elseif ($arr_product["product_kind_flg"]=="2" ){
			echo 'e-ライブ,';
		} else {
			echo 'e-ラーニング,';
		}
	} elseif( $arr_product["product_type_add"]=="2" ){
		if ($arr_product["training_kind_flg"]=="2" ){
			echo '夏季研修,';
		} elseif ($arr_product["training_kind_flg"]=="3" ){
			echo '新規登録弁護士研修,';
		} else {
			echo 'ライブ実務研修,';
		}
	} elseif( $arr_product["product_type_add"]=="3" ){
		echo '代替倫理研修,';
	} elseif( $arr_product["product_type_add"]=="4" ){
		echo 'パスポート,';
	}
	echo "\r\n";

	echo '弁護士会,';
	echo '"' . $arr_product["bar_association_branch_name"] . '",';
	echo "\r\n";

	echo '実施日,';
	echo '"' . $arr_product["dates"] . '",';
	echo "\r\n";

	//echo '弁護士会,';
	//echo '"' . $arr_product["bar_association_branch_name"] . '",';
	//echo "\r\n";

	echo '総受講者,';
	if ($arr_product["product_type_add"]=="1"){
		echo '"' . $arr_product["product_type_add1_count"] . '",';
		echo '受講完了者,';
		echo '"' . $arr_product["product_type_add1_end_count"] . '",';
	} elseif ($arr_product["product_type_add"]=="2"){
		echo '"' . $arr_product["product_type_add2_count"] . '",';
		echo '受講完了者,';
		echo '"' . $arr_product["product_type_add2_end_count"] . '",';
	} elseif ($arr_product["product_type_add"]=="3"){
		echo '"' . $arr_product["product_type_add3_count"] . '",';
		echo '受講完了者,';
		echo '"' . $arr_product["product_type_add3_end_count"] . '",';
	} elseif ($arr_product["product_type_add"]=="4"){
	}
	echo "\r\n";

	echo '公開期間,';
	echo '"' . $arr_product["start_date"] . '",';
	echo '"' . $arr_product["end_date"] . '",';
	echo "\r\n";

	echo "\r\n";

	if ($arr_product["product_type_add"]=="4"){
		echo '登録番号,氏名,メールアドレス,弁護士会,利用開始日,有効期限,';
	} else {
		echo '登録番号,氏名,メールアドレス,弁護士会,FP,開始日,最終受講日,受講状況,';
	}
	echo "\r\n";
	for($i=0;$i<count($arr_order);$i++){
		//+++++++++++++++++++++++++++++++++++++++++++++
		echo '"' . $arr_order[$i]["lawyer_number"] . '",';
		//+++++++++++++++++++++++++++++++++++++++++++++
		echo '"' . $arr_order[$i]["student_name"] . '",';
		//+++++++++++++++++++++++++++++++++++++++++++++
		echo '"' . $arr_order[$i]["student_email"] . '",';
		//+++++++++++++++++++++++++++++++++++++++++++++
		echo '"' . $arr_order[$i]["association_name"] . '",';
		//+++++++++++++++++++++++++++++++++++++++++++++
		
		if ($arr_product["product_type_add"]=="4"){
			//+++++++++++++++++++++++++++++++++++++++++++++
			echo '"' . $arr_order[$i]["payment_date"] . '",';
			//+++++++++++++++++++++++++++++++++++++++++++++
			echo '"' . $arr_order[$i]["exp_date_passport"] . '",';
			//+++++++++++++++++++++++++++++++++++++++++++++
			
		} else {
			//+++++++++++++++++++++++++++++++++++++++++++++
			if( $arr_order[$i]["presence_passport"]=="1"){
				echo '"○",';
			} else {
				echo '"-",';
			}
			//+++++++++++++++++++++++++++++++++++++++++++++
			if ($arr_order[$i]["product_type_add"]=="1"){
				echo '"' . $arr_order[$i]["start_view_date"] . '",';
			} elseif ($arr_order[$i]["product_type_add"]=="2"){
				echo '"' . $arr_order[$i]["dates"] . '",';
			} elseif ($arr_order[$i]["product_type_add"]=="3"){
				if ($arr_order[$i]["start_date1"]!="" && $arr_order[$i]["start_date1"]!="0000-00-00 00:00:00"){
					'"' . $arr_order[$i]["start_date1"] . '",';
				} else {
					echo '"-",';
				}
			} elseif ($arr_order[$i]["product_type_add"]=="4"){
				echo '"",';
			} else {
				echo '"",';
			}
			//+++++++++++++++++++++++++++++++++++++++++++++
			if ($arr_order[$i]["product_type_add"]=="1"){
				echo '"' . $arr_order[$i]["end_view_date"] . '",';
			} elseif ($arr_order[$i]["product_type_add"]=="2"){
				echo '"",';
			} elseif ($arr_order[$i]["product_type_add"]=="3"){
				if ($arr_order[$i]["judge_date2"]!="" && $arr_order[$i]["judge_date2"]!="0000-00-00 00:00:00"){
					echo '"' . $arr_order[$i]["judge_date2"] . '",';
				} elseif ($arr_order[$i]["judge_date1"]!="" && $arr_order[$i]["judge_date1"]!="0000-00-00 00:00:00"){
					echo '"' . $arr_order[$i]["judge_date1"] . '",';
				} else {
					echo '"-",';
				}
			} elseif ($arr_order[$i]["product_type_add"]=="4"){
				echo '"",';
			} else {
				echo '"",';
			}
			//+++++++++++++++++++++++++++++++++++++++++++++
			if ($arr_order[$i]["product_type_add"]=="1"){
				echo '"' . $arr_order[$i]["percent"] . '%",';
				//if ($arr_order[$i]["all_end_view_count"]==$video_count){
				//	echo mb_convert_encoding('"100%",',                "SJIS", "UTF-8");
				//} else {
				//	if ($arr_order[$i]["all_end_view_count"]=="0"){
				//		echo mb_convert_encoding('"0%",',                "SJIS", "UTF-8");
				//	} else {
				//		if ($arr_order[$i]["max_view_per"]!=""){
				//			echo mb_convert_encoding('"' . $arr_order[$i]["max_view_per"] . '%",',                "SJIS", "UTF-8");
				//		} else {
				//			echo mb_convert_encoding('"0%",',                "SJIS", "UTF-8");
				//		}
				//	}
				//}
			} elseif ($arr_order[$i]["product_type_add"]=="2"){
				if ($arr_order[$i]["participation_flg"]=="1"){
					echo '"100%",';
				} else {
					echo '"0%",';
				}
			} elseif ($arr_order[$i]["product_type_add"]=="3"){
				if ($arr_order[$i]["status"]=="0" || $arr_order[$i]["status"]==""){
					echo '"未受講",';
				} elseif ($arr_order[$i]["status"]=="1"){
					echo '"受講中",';
				} elseif ($arr_order[$i]["status"]=="2"){
					echo '"一次○",';
				} elseif ($arr_order[$i]["status"]=="3"){
					echo '"一次×",';
				} elseif ($arr_order[$i]["status"]=="4"){
					echo '"一次×",';
				} elseif ($arr_order[$i]["status"]=="5"){
					echo '"追試○",';
				} elseif ($arr_order[$i]["status"]=="6"){
					echo '"追試×",';
				} elseif ($arr_order[$i]["status"]=="7"){
					echo '"レポート",';
				} elseif ($arr_order[$i]["status"]=="8"){
					echo '"会場",';
				} else {
					'"",';
				}
			} elseif ($arr_order[$i]["product_type_add"]=="4"){
				echo '"",';
			} else {
				echo '""';
			}
		}
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++
		echo "\r\n";
		//+++++++++++++++++++++++++++++++++++++++++++++
	}
	//----------------------------------------------------------
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
