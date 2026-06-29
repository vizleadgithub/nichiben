<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '受付中のライブ実務研修';
$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$st_login_check = st_login_check();
$mtb_bar_association = get_mtb_bar_association();
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["productlistlivetraining.pagemax"]) && !empty($_SESSION["productlistlivetraining.pagemax"]) ){
	$pagemax = $_SESSION["productlistlivetraining.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["productlistlivetraining.page"] = 1;
	$_SESSION["productlistlivetraining.sort"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["productlistlivetraining.page"]) && !empty($_SESSION["productlistlivetraining.page"]) ){
	$page = $_SESSION["productlistlivetraining.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["productlistlivetraining.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["productlistlivetraining.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["productlistlivetraining.sort"]) && !empty($_SESSION["productlistlivetraining.sort"]) ){
	$sort = $_SESSION["productlistlivetraining.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["productlistlivetraining.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$where = " WHERE tbl_product.del_flg='0'";
$where.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
$where.= " AND tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%'";
$where.= " AND tbl_product_live_training.ethic_flg = '0'";
$where.= " AND tbl_product_live_training.sponsor = '|1|'";
// 未ログインの場合
if (!st_login_check()){
	$where.= " AND tbl_product.product_type='2'";
}

$sql = "SELECT COUNT(*) AS c FROM tbl_product INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id INNER JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id";
$sql = "SELECT";
$sql.= "  COUNT(*) AS c";
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

$ret = $objDbConnect->query_fetch($sql.$where);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
//$objPager->setPageMax(1);
$objPager->setListMax($all_count);
$objPager->setPagerUrl("?sort=".$sort."&pagemax=".$pagemax."&page=");

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

$sql = "SELECT * FROM tbl_product INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id INNER JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id";
$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  DATE_FORMAT(tbl_product.regist_date,'%Y%m%d') as regist_date_ymd,";
$sql.= "  tbl_product_add.product_type_add,";
$sql.= "  tbl_product_elearning.product_flg,";
$sql.= "  tbl_product_live_training.memo1,";
$sql.= "  tbl_product_live_training.memo2,";
$sql.= "  tbl_product_live_training.sponsor,";
$sql.= "  RPBA.dates";
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

	// 研修開催日
	$arr_list[$key]['disp_dates'] = date('Y年m月d日', strtotime($val['dates']));
	
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
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $arr_list);
$template->assign('THUMBNAIL_PATH', THUMBNAIL_PATH);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('sort_select', get_sort_selectbox());
$template->assign('sort', $sort);

$template->assign('csrf_token', csrf_token_get());
$template->layout('product/list_live_training.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>