<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '弁護士会主催研修一覧';
$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$st_login_check = st_login_check();
$mtb_bar_association = get_mtb_bar_association();
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$pcid = $_GET['pcid'];
$pagemax = 5;
if( isset($_SESSION["productlistbarassociationlive.pagemax"]) && !empty($_SESSION["productlistbarassociationlive.pagemax"]) ){
	$pagemax = $_SESSION["productlistbarassociationlive.pagemax"];
}

/*
if(cmCheckInput($pcid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
// カテゴリ存在チェック
$sql = "SELECT parent FROM wp_term_taxonomy WHERE term_id = '$pcid'";
$ret = $objDbConnect->query_fetch($sql);
if (!$ret){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
*/

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["productlistbarassociationlive.page"] = 1;
	$_SESSION["productlistbarassociationlive.sort"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["productlistbarassociationlive.page"]) && !empty($_SESSION["productlistbarassociationlive.page"]) ){
	$page = $_SESSION["productlistbarassociationlive.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["productlistbarassociationlive.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["productlistbarassociationlive.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["productlistbarassociationlive.sort"]) && !empty($_SESSION["productlistbarassociationlive.sort"]) ){
	$sort = $_SESSION["productlistbarassociationlive.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["productlistbarassociationlive.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$where = " WHERE tbl_product.del_flg='0'";
//$where.= " AND CONCAT(',',term_id,',') LIKE '%,".$pcid.",%'";
$where.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
//$where.= " AND tbl_product_live_training.sponsor LIKE '%|".$_SESSION['user']['bar_association_id']."|%'";
$where.= " AND tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%'";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
// 未ログインの場合
if (!st_login_check()){
	$where.= " AND tbl_product.product_type='2'";
}

$sql = "SELECT COUNT(*) AS c FROM tbl_product INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id INNER JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id";

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

$sql = "
SELECT 
	tbl_product.*
	, tbl_product_add.product_type_add 
	, DATE_FORMAT(tbl_product.regist_date,'%Y%m%d') as regist_date_ymd 
FROM 
	tbl_product 
	LEFT JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id 
	INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id 
	INNER JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id";
//var_dump($sql.$where.$order.$offset);
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);

// 主催総数取得
if ($mtb_bar_association){
	$sponsor_count = count($mtb_bar_association);
}

$arr_list = array();
foreach ($ret as $key => $val){
	$arr_list[$key] = $val;
	
	// 掲載期間
	$arr_list[$key]['disp_start_date'] = date('Y年m月d日', strtotime($val['start_date']));
	$arr_list[$key]['disp_end_date'] = date('Y年m月d日', strtotime($val['end_date']));
	
	// 消費税込み価格の設定
	$arr_list[$key]['price_intax'] = $arr_list[$key]['price'] + tax_cal_yen($arr_list[$key]['price']);
	
	// 研修開催日
	if ($val['dates'] != ''){
		$arr_list[$key]['disp_dates'] = date('Y年m月d日', strtotime($val['dates']));
	} else {
		$arr_list[$key]['disp_dates'] = '';
	}
	
	// 主催
	$disp_sponsor = '';
	if ($val['sponsor'] != ''){
		$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
		if (count($arr_sponsor) == $sponsor_count){
			$disp_sponsor = 'すべての弁護士会';
		} else {
			foreach ($arr_sponsor as $sponsor){
				$disp_sponsor.= $mtb_bar_association[$sponsor].'<br />';
			}
			$disp_sponsor = rtrim($disp_sponsor, '<br />');
		}
	}
	$arr_list[$key]['disp_sponsor'] = $disp_sponsor;

	// お気に入り
	$arr_list[$key]['favorite_flg'] = false;      // お気に入りボタン表示フラグ
	$arr_list[$key]['favorite_icon_flg'] = false; // お気に入りアイコン表示フラグ
	if ($st_login_check){
		$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' AND product_id='".$val['product_id']."' AND del_flg='0'";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret['c']==0){
			$arr_list[$key]['favorite_flg'] = true;
		} else {
			$arr_list[$key]['favorite_icon_flg'] = true;
		}
	}

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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

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

$template->layout('product/list_bar_association_live.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>