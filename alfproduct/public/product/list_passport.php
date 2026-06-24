<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '研修パスポート一覧';
$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$st_login_check = st_login_check();
$mtb_bar_association = get_mtb_bar_association();
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



//$objDbConnect->close();
//header("Location: /");
//exit();



// パスポート対象IDの取得
$mtb_passport_target = get_mtb_passport_target();
if (!empty($mtb_passport_target)){
	foreach ($mtb_passport_target as $val){
	//print_r("<!--[start_year:".$val['start_year']."]-->");
	//print_r("<!--[end_year:".$val['end_year']."]-->");
		if ($val['start_year'] != '' && $val['end_year'] != ''){
			if ($_SESSION['user']['year'] >= $val['start_year'] && $_SESSION['user']['year'] <= $val['end_year']){
				$passport_target_id = $val['passport_target_id'];
			}
		} else if ($val['start_year'] != '' && $val['end_year'] == ''){
			if ($_SESSION['user']['year'] >= $val['start_year']){
				$passport_target_id = $val['passport_target_id'];
			}
		}
	}
	//print_r("<!--[".$passport_target_id."]-->\n");
	//print_r("<!--[");
	//var_dump($_SESSION['user']);
	//print_r("]-->");
} else {
	$objDbConnect->close();
	header("Location: /");
	exit;
}

//--------------------------------------------
$passport_regist_date = "";
$passport_exp_date_passport = "";

$sql_passport = "SELECT regist_date FROM student WHERE student_id = '".$_SESSION['user']['id']."' ";
$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
foreach ($ret_passport as $key => $val){
	$passport_regist_date = $val["regist_date"];
}

$sql_passport = "SELECT exp_date_passport FROM student WHERE student_id = '".$_SESSION['user']['id']."' AND exp_date_passport>='".date('Y-m-d')."' ";
$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
foreach ($ret_passport as $key => $val){
	$arr_date = explode('-', $val["exp_date_passport"]);
	$passport_exp_date_passport = date('Y-m-d',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));
}
if( $passport_exp_date_passport == "" ){
	$passport_exp_date_passport = date('Y-m-d',mktime(0, 0, 0, date("m"), date("d")-1, date("Y") ));
}

//+++++++++++++++++++++
//6年目以降か
$arr_date = explode('-', trim($passport_regist_date) );
$temp_rd = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]+5 ));

$arr_date = explode('-', trim($passport_exp_date_passport) );
$temp_ed = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

if( $temp_rd<=$temp_ed ){
	//print_r("6年目以降");
	$passport_target_id = 3;
}
//+++++++++++++++++++++
//3年目以上、6年目未満か
$arr_date = explode('-', trim($passport_regist_date) );
$temp_rd = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]+3 ));
$temp_rd2 = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]+5 ));

$arr_date = explode('-', trim($passport_exp_date_passport) );
$temp_ed = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

//var_dump($temp_rd);
//var_dump($temp_ed);
//var_dump($temp_rd2);
//var_dump($temp_ed);

if( $temp_rd<=$temp_ed && $temp_rd2>$temp_ed ){
	//print_r("3～5年目");
	$passport_target_id = 2;
}
//+++++++++++++++++++++
//1年目以上、3年目未満か
$arr_date = explode('-', trim($passport_regist_date) );
$temp_rd = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0] ));
$temp_rd2 = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]+3 ));

$arr_date = explode('-', trim($passport_exp_date_passport) );
$temp_ed = date('Ymd',mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0]));

if( $temp_rd<=$temp_ed && $temp_rd2>$temp_ed ){
	//print_r("1～2年目");
	$passport_target_id = 1;
}
//+++++++++++++++++++++

//var_dump($temp_rd);
//var_dump($temp_ed);
//--------------------------------------------
//exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["productlistpassport.pagemax"]) && !empty($_SESSION["productlistpassport.pagemax"]) ){
	$pagemax = $_SESSION["productlistpassport.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["productlistpassport.page"] = 1;
	$_SESSION["productlistpassport.sort"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["productlistpassport.page"]) && !empty($_SESSION["productlistpassport.page"]) ){
	$page = $_SESSION["productlistpassport.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["productlistpassport.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["productlistpassport.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["productlistpassport.sort"]) && !empty($_SESSION["productlistpassport.sort"]) ){
	$sort = $_SESSION["productlistpassport.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["productlistpassport.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$where = " WHERE tbl_product.del_flg='0'";
$where.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
$where.= " AND (  tbl_product_passport.passport_target LIKE '%|".$passport_target_id."|%')";
// 未ログインの場合
if (!st_login_check()){
	$where.= " AND tbl_product.product_type='2'";
}

$sql = "SELECT COUNT(*) AS c FROM tbl_product INNER JOIN tbl_product_passport ON tbl_product.product_id = tbl_product_passport.product_id";
$ret = $objDbConnect->query_fetch($sql.$where);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
//$objPager->setPageMax(1);
$objPager->setListMax($all_count);
$objPager->setPagerUrl("?pcid=".$pcid."&sort=".$sort."&pagemax=".$pagemax."&page=");
$pager = $objPager->getPager();
$offset = $objPager->getOffset();
if ($sort == 1){
	$order = " ORDER BY tbl_product.regist_date DESC";
} else if ($sort == 2){
	$order = " ORDER BY tbl_product.regist_date ASC";
} else if ($sort == 3){
	$order = " ORDER BY CASE WHEN tbl_product.end_date IS NULL THEN 1 ELSE 0 END, tbl_product.end_date ASC";
} else {
	$order = " ORDER BY tbl_product.product_id DESC";
}

$sql = "SELECT *,DATE_FORMAT(tbl_product.regist_date,'%Y%m%d') as regist_date_ymd FROM tbl_product INNER JOIN tbl_product_passport ON tbl_product.product_id = tbl_product_passport.product_id";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);

$arr_list = array();
foreach ($ret as $key => $val){
	$arr_list[$key] = $val;
	
	// 消費税込み価格の設定
	$arr_list[$key]['price_intax'] = $arr_list[$key]['price'] + tax_cal_yen($arr_list[$key]['price']);

	//Category
	$arr_list[$key]['css_icon_cat'] = array();
	if( trim($arr_list[$key]['term_id'])!="" ){
		$sql= "SELECT name FROM wp_terms WHERE term_id in (".$arr_list[$key]['term_id'].")";
		$ret_cat = $objDbConnect->query_fetch_arr($sql);
		$arr_list[$key]['css_icon_cat'] = $ret_cat;
	}

	//New
	$arr_list[$key]['css_icon_new'] = 0;
	if( intval($arr_list[$key]['regist_date_ymd'])+30>=intval(date("Ymd")) ){
		$arr_list[$key]['css_icon_new'] = 1;
	}

	//人気
	$arr_list[$key]['css_icon_ninki'] = 0;
	$sql= "SELECT count(`rank`) as c FROM ranking WHERE product_id ='".$arr_list[$key]['product_id']."' ";
	$ret_ninki = $objDbConnect->query_fetch($sql);
	if( $ret_ninki["c"]>0 ){
		$arr_list[$key]['css_icon_ninki'] = 1;
	}
	
	//初級
	$arr_list[$key]['css_icon_syokyu'] = 0;
	$sql= "SELECT term_id  FROM wp_term_taxonomy WHERE (parent=572 OR term_id=572) and taxonomy='category'";
	$ret_syokyu = $objDbConnect->query_fetch_arr($sql);
	foreach ($ret_syokyu as $key_syokyu => $val_syokyu){
		if ( false !== strpos( ",".$arr_list[$key]["term_id"].",", ",".$val_syokyu["term_id"]."," ) ) {
			$arr_list[$key]['css_icon_syokyu'] = 1;
		}
	}

	// 商品フラグ
	$icon = '';
	$arr_icon = array();
	$arr_icon = explode('|', trim($val['product_flg'], '|'));
	$osusume_flg = 0;
	foreach ($arr_icon as $icon){
		if( $mtb_product_flg[$icon]['icon']=="status002.png" && $osusume_flg == 0 ){
			$osusume_flg = 1;
			$arr_list[$key]['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
			$arr_list[$key]['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
		} elseif( $mtb_product_flg[$icon]['icon']=="status002.png" && $osusume_flg == 1 ){
		} else {
			$arr_list[$key]['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
			$arr_list[$key]['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
		}
	}
}


//--------------------------------------------
$exp_date_passport_flg = 0;
$sql_passport = "SELECT (exp_date_passport - INTERVAL 1 MONTH) as exp_date_passport FROM student WHERE student_id = '".$_SESSION['user']['id']."' AND ( ( (exp_date_passport - INTERVAL 1 MONTH)<='".date("Y-m-d")."' AND presence_passport=1) OR presence_passport=0) ";
$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
$arr_passport = array();
foreach ($ret_passport as $key => $val){
	$arr_passport = $val;
}
if( count($arr_passport)>0 ){
	$exp_date_passport_flg=1;
} else {
	$exp_date_passport_flg=0;
}
//--------------------------------------------


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('exp_date_passport_flg', $exp_date_passport_flg);

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $arr_list);
$template->assign('pcid', $pcid);
$template->assign('THUMBNAIL_PATH', THUMBNAIL_PATH);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('pankuzu', get_product_pankuzu($objDbConnect, $pcid));

$template->assign('sort_select', get_sort_selectbox());
$template->assign('sort', $sort);

$template->layout('product/list_passport.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>