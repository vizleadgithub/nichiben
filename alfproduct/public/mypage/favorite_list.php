<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'お気に入り';

if (!st_login_check()){
	header("Location: /login/login.php");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.favoritelist.pagemax"]) && !empty($_SESSION["mypage.favoritelist.pagemax"]) ){
	$pagemax = $_SESSION["mypage.favoritelist.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["mypage.favoritelist.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["mypage.favoritelist.page"]) && !empty($_SESSION["mypage.favoritelist.page"]) ){
	$page = $_SESSION["mypage.favoritelist.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["mypage.favoritelist.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = intval($_GET["pagemax"]);
	$_SESSION["mypage.favoritelist.pagemax"] = intval($pagemax);
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 表示条件を満たさないお気に入りを削除（非公開になった商品をお気に入りから除去）
$_now = date("Y-m-d H:i:s");
$_bar_association_id = mysqli_real_escape_string($objDbConnect->connect, $_SESSION['user']['bar_association_id']);
$sql = "
	DELETE tbl_favorite FROM tbl_favorite
		LEFT JOIN tbl_product
			ON tbl_favorite.product_id = tbl_product.product_id
		LEFT JOIN tbl_product_add
			ON tbl_product.product_id = tbl_product_add.product_id
		LEFT JOIN tbl_product_live_training
			ON tbl_product.product_id = tbl_product_live_training.product_id
	WHERE
		tbl_favorite.member_id = '".$_SESSION['user']['id']."'
		AND NOT (
			tbl_product.del_flg = '0'
			AND (
				  (tbl_product.start_date <= '".$_now."' AND tbl_product.end_date >= '".$_now."')
				OR (tbl_product.start_date <= '".$_now."' AND tbl_product.end_date IS NULL)
				OR (tbl_product.start_date IS NULL        AND tbl_product.end_date >= '".$_now."')
				OR (tbl_product.start_date IS NULL        AND tbl_product.end_date IS NULL)
			)
			AND tbl_product_add.product_type_add IN (1, 2)
			AND (
				  tbl_product_live_training.ethic_flg = 0
				OR tbl_product_live_training.ethic_flg IS NULL
				OR (tbl_product_live_training.ethic_flg = 1 AND tbl_product_live_training.app_flg = 1)
			)
			AND (
				  tbl_product_live_training.target LIKE '%|".$_bar_association_id."|%'
				OR tbl_product_live_training.target IS NULL
			)
		)
";
$objDbConnect->execute($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT * FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' ORDER BY `rank` ";
$ret = $objDbConnect->query_fetch_arr($sql);
$update_index = 0;
if( !empty($ret) ){
	foreach ($ret as $key => $val){
		$sql = "update tbl_favorite set `rank`='".$update_index."' where member_id='".$_SESSION['user']['id']."' AND product_id='".$val["product_id"]."' ";
		$objDbConnect->execute($sql);
		$update_index += 1;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


$all_count = 0;
$sql = _get_sql("COUNT(DISTINCT tbl_product.product_id) AS c");
$ret = $objDbConnect->query_fetch($sql);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}

$objPager->setListMax($all_count);
$objPager->setPagerUrl("?page=");
$pager = $objPager->getPager();
$offset = $objPager->getOffset();

$sql = _get_sql("DATE_FORMAT(tbl_favorite.regist_date, '%Y年%m月%d日') AS regist_date,tbl_product.*,tbl_product_add.product_type_add,tbl_product_elearning.product_flg,tbl_product_live_training.memo1,tbl_product_live_training.memo2,tbl_product_live_training.sponsor,tbl_product_live_training.ethic_flg,tbl_product_live_training.training_kind_flg,RPBA.dates", "ORDER BY tbl_favorite.`rank` ASC");
//var_dump($sql.$offset);
$ret = $objDbConnect->query_fetch_arr($sql.$offset);

$arr_list = array();
foreach ($ret as $key => $val){
	$arr_list[$key] = $val;
	
	// -----
	// 共通
	// -----
	// 掲載期間
	//$arr_list[$key]['disp_start_date'] = date('Y年m月d日', strtotime($val['start_date']));
	//$arr_list[$key]['disp_end_date'] = date('Y年m月d日', strtotime($val['end_date']));
	
	// 消費税込み価格の設定
	//$arr_list[$key]['price_intax'] = $arr_list[$key]['price'] + tax_cal_yen($arr_list[$key]['price']);
	
	// お気に入り
	//$arr_list[$key]['favorite_flg'] = false;      // お気に入りボタン表示フラグ
	//$arr_list[$key]['favorite_icon_flg'] = false; // お気に入りアイコン表示フラグ
	//if ($st_login_check){
	//	$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' AND product_id='".$val['product_id']."' AND del_flg='0'";
	//	$ret = $objDbConnect->query_fetch($sql);
	//	if ($ret['c']==0){
	//		$arr_list[$key]['favorite_flg'] = true;
	//	} else {
	//		$arr_list[$key]['favorite_icon_flg'] = true;
	//	}
	//}
	
	// ---------------
	// e-ラーニング用
	// ---------------
	if ($val['product_type_add'] == 1){
		//$all_play_time = '00:00:00';
		for($i=1; $i<=MAX_CONTENTS; $i++){
			$arr_video_info = array();
			$arr_video_info = get_alf_thumbnail_db($val["contents_contents$i"]);
			
			// サムネイル取得(1つ目の動画)
			if ($i == 1){
				$arr_list[$key]["thumbnail_flg"] = 3; // noimage
				if ($val["thumbnail"] != ""){
					$arr_list[$key]["thumbnail_flg"] = 1; // 手動サムネイル
				} else {
					$arr_list[$key]["thumbnail_flg"] = 2; // 自動サムネイル(ビデオより)
					$arr_list[$key]["video_thumbnail"] = $val["contents_contents$i"].$arr_video_info["contents_thumbnail"][0]["p180"];
				}
			}
			
			// 総再生時間取得
		//	if ($arr_video_info["alfstream_duration"] != ''){
		//		$all_play_time = getTimeAddition($all_play_time, $arr_video_info["alfstream_duration"]);
		//	}
		}
		//$arr_list[$key]['all_play_time'] = time_format_product_list($all_play_time);
		
		// 商品フラグ
		//$icon = '';
		//$str_icon = '';
		//$arr_icon = array();
		//$arr_icon = explode('|', trim($val['product_flg'], '|'));
		//foreach ($arr_icon as $icon){
		//	$str_icon.= $mtb_product_flg[$icon];
		//}
		//$arr_list[$key]['icon'] = $str_icon;
		
	// -----------
	// 会場研修用
	// -----------
	} elseif ($val['product_type_add'] == 2){
		// 研修開催日
		//$arr_list[$key]['disp_dates'] = date('Y年m月d日', strtotime($val['dates']));
		
		// 主催
		//$disp_sponsor = '';
		//if ($val['sponsor'] != ''){
		//	$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
		//	if (count($arr_sponsor) == $sponsor_count){
		//		$disp_sponsor = 'すべての弁護士会';
		//	} else {
		//		foreach ($arr_sponsor as $sponsor){
		//			$disp_sponsor.= $mtb_bar_association[$sponsor].'<br />';
		//		}
		//		$disp_sponsor = rtrim($disp_sponsor, '<br />');
		//	}
		//}
		//$arr_list[$key]['disp_sponsor'] = $disp_sponsor;
		
	} else {
	}
}

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $arr_list);

$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('csrf_token', csrf_token_get());
$template->layout_noside('mypage/favorite_list.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function _get_sql($select_col, $order=''){
	$sql = "SELECT DISTINCT";
	$sql.= "   $select_col";
	$sql.= " FROM";
	$sql.= "   tbl_favorite";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product";
	$sql.= "       ON tbl_favorite.product_id = tbl_product.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product_add";
	$sql.= "       ON tbl_product.product_id = tbl_product_add.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product_elearning";
	$sql.= "       ON tbl_product.product_id = tbl_product_elearning.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product_live_training";
	$sql.= "       ON tbl_product.product_id = tbl_product_live_training.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA";
	$sql.= "       ON tbl_product.product_id = RPBA.product_id";
	$sql.= " WHERE";
	$sql.= "   tbl_favorite.member_id='".$_SESSION['user']['id']."'";
	$sql.= "   AND tbl_favorite.del_flg='0'";
	$sql.= "   AND tbl_product.del_flg='0'";
	$sql.= "   AND (  (tbl_product.start_date<='".date("Y-m-d H:i:s")."' AND tbl_product.end_date>='".date("Y-m-d H:i:s")."')  OR  (tbl_product.start_date<='".date("Y-m-d H:i:s")."' AND tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL AND tbl_product.end_date>='".date("Y-m-d H:i:s")."')  OR  (tbl_product.start_date IS NULL AND tbl_product.end_date IS NULL)  )";
	$sql.= "   AND ( tbl_product_add.product_type_add IN (1,2) )";
	$sql.= "   AND ( (tbl_product_live_training.ethic_flg = 0 OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = 1 AND tbl_product_live_training.app_flg = 1) ) )";
	$sql.= "   AND ( tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL )";
	
	if ($order!=''){
		$sql.= " $order";
	}
	
	return $sql;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>