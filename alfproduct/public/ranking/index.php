<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '総合ランキング';
$objDbConnect = new DbConnect();
$objPager = new Pager();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$st_login_check = st_login_check();
$mtb_bar_association = get_mtb_bar_association();
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_search_training = array(
	'1' => 'eラーニング',
	'2' => '日弁連主催会場研修(ライブ実務研修等)',
	'3' => '弁護士会主催会場研修'
);
$template->assign('arr_search_training', $arr_search_training);

//$arr_search_sponsor = array(
//	'1' => '日弁連',
//	'2' => '弁護士会',
//	'3' => 'ロースクール',
//	'4' => '法務研究財団',
//	'5' => 'その他',
//);
//$template->assign('arr_search_sponsor', $arr_search_sponsor);

$arr_search_state = array(
	'1' => '受講中',
	'2' => '未購入未受講',
	'3' => '購入済未受講',
);
$template->assign('arr_search_state', $arr_search_state);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  *";
$sql.= " FROM";
$sql.= "  ranking_add ";
$sql.= " ORDER BY no ASC ";
$ret_file = $objDbConnect->query_fetch_arr($sql);
$file_list = array();
foreach ($ret_file as $key => $val){
	$file_list[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  tbl_product_add.product_type_add,";
$sql.= "  tbl_product_elearning.product_flg,";
$sql.= "  tbl_product_live_training.memo1,";
$sql.= "  tbl_product_live_training.memo2,";
$sql.= "  tbl_product_live_training.sponsor,";
$sql.= "  RPBA.dates, ";
$sql.= "  ranking.`rank`";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= "    INNER JOIN";
$sql.= "  wp_term_taxonomy";
$sql.= "      ON tbl_product.term_id = wp_term_taxonomy.term_id";
$sql.= "    INNER JOIN ";
$sql.= "  tbl_product_add ";
$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id ";
$sql.= "    LEFT JOIN ";
$sql.= "  tbl_product_elearning ";
$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id ";
$sql.= "    LEFT JOIN ";
$sql.= "  tbl_product_live_training ";
$sql.= "      ON tbl_product.product_id = tbl_product_live_training.product_id ";
$sql.= "    LEFT JOIN ";
$sql.= "  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA";
$sql.= "      ON tbl_product.product_id = RPBA.product_id ";
$sql.= "    INNER JOIN ";
$sql.= "  ranking ";
$sql.= "      ON tbl_product.product_id = ranking.product_id ";
$sql.= "  WHERE `rank`>=1 AND `rank`<=10 ";
$sql.= "  ORDER BY ranking.`rank` ASC ";
$ret = $objDbConnect->query_fetch_arr($sql);
$arr_list = array();
foreach ($ret as $key => $val){
	$arr_list[$key] = $val;
	
	// -----
	// 共通
	// -----
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
	
	// ---------------
	// e-ラーニング用
	// ---------------
	if ($val['product_type_add'] == 1){
		$all_play_time = '00:00:00';
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
			if ($arr_video_info["alfstream_duration"] != ''){
				$all_play_time = getTimeAddition($all_play_time, $arr_video_info["alfstream_duration"]);
			}
		}
		$arr_list[$key]['all_play_time'] = time_format_product_list($all_play_time);
		
	// -----------
	// 会場研修用
	// -----------
	} elseif ($val['product_type_add'] == 2){
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
		
	} else {
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$template->assign('arr_list', $arr_list);
$template->assign('file_list', $file_list);
$template->assign('mtb_product_flg', $mtb_product_flg);
$template->assign('csrf_token', csrf_token_get());
$template->layout('ranking/index.tpl');
$objDbConnect->close();
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>