<?php
$debag = false;
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 

$isPad = false;
if(preg_match("/iPad/", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(preg_match("/iPhone/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPad/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPod/", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(preg_match("/Android/", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '講座詳細';
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_GET['pid'])){
	$objDbConnect->close();
	header("Location: /?_=1");
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /?_=2");
	exit();
}
if (isset($_GET['pcid'])){
	$pcid = $_GET['pcid'];
	
	if(cmCheckInput($pcid, 'CK_NUM')){
		$objDbConnect->close();
		header("Location: /?_=3");
		exit();
	}
} else {
	$pcid = '';
}
$user_id = $_SESSION['user']['id'];
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mtb_bar_association = get_mtb_bar_association();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$student_make_complete = 0;
$sql = "select * from student_make_complete WHERE student_id='".$user_id."' and product_id='".$pid."'";
//var_dump($sql);
$student_make_complete_list = $objDbConnect->query_fetch_arr($sql);
if( count($student_make_complete_list)>0 ){
	$student_make_complete = 1;
	$template->assign('student_make_complete', $student_make_complete);
}
//var_dump($student_make_complete);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];
$kaijo_moushikomi_flg = true; // パスポート所持ユーザの場合は、会場研修はパスポート有効期限が開催日でも有効の場合購入できる

$back_url = get_back_url();

// ログインチェック
$st_login_check = st_login_check();

// ユーザー情報の取得
$user_list = array();
if ($st_login_check){
	$sql = "SELECT * FROM student WHERE status='0' AND student_id='$user_id'";
	$user_list = $objDbConnect->query_fetch_arr($sql);
}

// 商品詳細の取得
$product_list = array();
$where = " WHERE tbl_product.del_flg = '0'";
$where.= " AND tbl_product.product_id = '$pid'";
if( $_SESSION['debag'] == '1' ){
} else {
	$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
}
$where.= " AND (tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL)";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
if (!$st_login_check){
	$where.= " AND product_type = '2'";
}

$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  DATE_FORMAT(tbl_product.regist_date,'%Y%m%d') as regist_date_ymd,";
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

$product_list = $objDbConnect->query_fetch($sql.$where);
if (!$product_list){
	$objDbConnect->close();
	header("Location: /?_=4");
	exit();
}
//var_dump($product_list);
// -----
// 共通
// -----
// 掲載期間
$product_list['disp_start_date'] = date('Y年m月d日', strtotime($product_list['start_date']));
$product_list['disp_end_date'] = date('Y年m月d日', strtotime($product_list['end_date']));

// お気に入り
$product_list['favorite_flg'] = false;      // お気に入りボタン表示フラグ
$product_list['favorite_icon_flg'] = false; // お気に入りアイコン表示フラグ

// 消費税込み価格の設定
$product_list['price_intax'] = $product_list['price'] + tax_cal_yen($product_list['price']);

//New
$product_list['css_icon_new'] = 0;
if( intval($product_list['regist_date_ymd'])+30>=intval(date("Ymd")) ){
	$product_list['css_icon_new'] = 1;
}

//人気
$product_list['css_icon_ninki'] = 0;
$sql= "SELECT count(`rank`) as c FROM ranking WHERE product_id ='".$product_list['product_id']."' ";
$ret_ninki = $objDbConnect->query_fetch($sql);
if( $ret_ninki["c"]>0 ){
	$product_list['css_icon_ninki'] = 1;
}

//初級
$product_list['css_icon_syokyu'] = 0;
$sql= "SELECT term_id  FROM wp_term_taxonomy WHERE (parent=572 OR term_id=572) and taxonomy='category'";
$ret_syokyu = $objDbConnect->query_fetch_arr($sql);
foreach ($ret_syokyu as $key_syokyu => $val_syokyu){
	if ( false !== strpos( ",".$product_list["term_id"].",", ",".$val_syokyu["term_id"]."," ) ) {
		$product_list['css_icon_syokyu'] = 1;
	}
}

// ---------------
// e-ラーニング用
// ---------------
if ($product_list['product_type_add'] == 1 ){
	$all_play_time = '00:00:00';
	for($i=1; $i<=MAX_CONTENTS; $i++){
		$arr_video_info = array();
		$arr_video_info = get_alf_thumbnail_db($product_list["contents_contents$i"]);
		
		// 動画よりサムネイル取得
		$product_list["video_thumbnail$i"] = $product_list["contents_contents$i"].$arr_video_info["contents_thumbnail"][0]["p180"];
		
		// 各ビデオの再生時間取得
		$product_list["video_duration$i"] = time_format_product_list($arr_video_info["alfstream_duration"]);
		
		// 各ビデオの視聴済時間・残り時間取得
		$sql = "SELECT duration, duration_reading, complete_flag FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents$i"]."'";
		$res1 = $objDbConnect->query_fetch($sql);
		if ($res1){
			$product_list["video_view_flg$i"] = true;
			$product_list["video_reading$i"] = time_format_product_list($res1["duration_reading"]);
			//$product_list["video_remaining$i"] = getTimeSubtraction($res1["duration"], $res1["duration_reading"]);
			$product_list["video_complete_flg$i"] = $res1["complete_flag"];
		} else {
			$product_list["video_view_flg$i"] = false;
			$product_list["video_complete_flg$i"] = 0;
		}

		// 各ビデオの視聴済時間・残り時間取得
		$sql = "SELECT duration, duration_reading, complete_flag FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents".$i."so"]."'";
		$product_list["video_view_flg".$i."so"] = false;
		$product_list["video_complete_flg".$i."so"] = 0;
		if( trim($product_list["contents_contents".$i."so"])!="" ){
			$res1 = $objDbConnect->query_fetch($sql);
			if ($res1){
				$product_list["video_view_flg".$i."so"] = true;
				$product_list["video_reading".$i."so"] = time_format_product_list($res1["duration_reading"]);
				$product_list["video_complete_flg".$i."so"] = $res1["complete_flag"];
			} else {
				$product_list["video_view_flg".$i."so"] = false;
				$product_list["video_complete_flg".$i."so"] = 0;
			}
		}
		$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$_SESSION['user']['id']."' AND video_id='".$product_list["contents_contents".$i."so"]."'";
		$ret_bookmark_so = $objDbConnect->query_fetch($sql);
		if ($ret_bookmark_so){
			$bookmark_time_sec = $ret_bookmark_so['bookmark_time_sec'];
			$product_list["video_view_flg".$i."so"] = true;
			$product_list["video_reading".$i."so"] = time_format_product_list($ret_bookmark_so['bookmark_time_sec']);
		}
		
		// 総再生時間取得
		if ($arr_video_info["alfstream_duration"] != ''){
			$all_play_time = getTimeAddition($all_play_time, $arr_video_info["alfstream_duration"]);
		}
	}
	$product_list['all_play_time'] = time_format_product_list($all_play_time);
	
	// 商品フラグ
	$icon = '';
	$arr_icon = array();
	$arr_icon = explode('|', trim($product_list['product_flg'], '|'));
	$osusume_count = 0;
	foreach ($arr_icon as $icon){
		if( $icon=="2" || $icon=="3" ){
			$osusume_count += 1;
			if( $osusume_count==0 ){
				$product_list['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
				$product_list['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
			}
		} else {
			$product_list['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
			$product_list['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
		}
	}
	
	// お気に入り表示フラグ設定
	if ($st_login_check){
		$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' AND product_id='".$product_list['product_id']."' AND del_flg='0'";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret['c']==0){
			$product_list['favorite_flg'] = true;
		} else {
			$product_list['favorite_icon_flg'] = true;
		}
	}
	
// -----------
// 会場研修用
// -----------
} elseif ($product_list['product_type_add'] == 2){
	// 研修開催日
	$arr_youbi = get_japan_youbi();
	$str_ymd   = date('Y年m月d日', strtotime($product_list['dates']));
	$str_youbi = $arr_youbi[date('w', strtotime($product_list['dates']))];
	$str_time  = date('H:i', strtotime($product_list['dates']));
	if ($str_time == '00:00'){
		$str_time = '';
	}
	$product_list['disp_dates'] = trim($str_ymd.'('.$str_youbi.') '.$str_time);
	
	// 主催
	$disp_sponsor = '';
	if ($product_list['sponsor'] != ''){
		$arr_sponsor = explode('|', trim($product_list['sponsor'], '|'));
		if (count($arr_sponsor) == $sponsor_count){
			$disp_sponsor = 'すべての弁護士会';
		} else {
			foreach ($arr_sponsor as $sponsor){
				$disp_sponsor.= $mtb_bar_association[$sponsor].'<br />';
			}
			$disp_sponsor = rtrim($disp_sponsor, '<br />');
		}
	}
	$product_list['disp_sponsor'] = $disp_sponsor;
	
	// 商品のweb_flgが1の時に、商品自体のweb申込可
	if ($product_list['web_flg'] == '1'){
		$disp_web_flg = true;
	} else {
		$disp_web_flg = false;
	}
	// 日弁連単独主催の場合に、会場選択エリアを表示
	if ($product_list['sponsor'] == '|1|'){
		$nichibenren_tandoku_flg = true;
	} else {
		$nichibenren_tandoku_flg = false;
	}
	
	// パスポートの有効期限が開催日以降も有効かどうかチェックする
	if ($_SESSION['user']['presence_passport'] == '1'){
		$u_exp_date_passport = strtotime($_SESSION['user']['exp_date_passport']);
		$u_dates = strtotime($product_list['dates']);
		if ($u_exp_date_passport < $u_dates){
			$kaijo_moushikomi_flg = false;
		}
	}
	$kaijo_moushikomi_flg = true;
	
	// -------------
	// 弁護士会情報
	// -------------
	$bar_association = _get_bar_association_product($objDbConnect, $product_list['target']);
	$bar_association_branch = array();
	$bar_association_id = '';
	$bar_association_branch_id = '';
	$bar_association_branch_info = false;
	if (isset($_POST['act'])){
		$bar_association_id = $_POST['bar_association_id'];
		$bar_association_branch = _get_bar_association_branch_product($objDbConnect, $bar_association_id);
		
		// 研修情報検索ボタン
		if ($_POST['act'] == 'info_check'){
			$bar_association_branch_id = $_POST['bar_association_branch_id'];
			$bar_association_branch_info = _get_bar_association_branch_info($objDbConnect, $pid, $bar_association_branch_id);
			if ($bar_association_branch_info){
				$bar_association_branch_info['bar_association_name'] = $bar_association[$bar_association_id];
				// 定員越えアラート判断用フラグ
				$entry_number = get_entry_number($bar_association_branch_id, $pid);
				if ($entry_number >= $bar_association_branch_info['capacity']){
					$capacity_alert_flg = true;
				} else {
					$capacity_alert_flg = false;
				}
			}
			
		// 弁護士会onChange
		} else if ($_POST['act'] == 'search_branch') {
		}
	}
	
// -------------------
// 倫理代替措置研修用
// -------------------
} elseif ($product_list['product_type_add'] == 3){
	if ($_SESSION['user']['sub_auth_ethic_training']==='1'){
		$all_play_time = '00:00:00';
		for($i=1; $i<=MAX_CONTENTS; $i++){
			$arr_video_info = array();
			$arr_video_info = get_alf_thumbnail_db($product_list["contents_contents$i"]);
			
			// 動画よりサムネイル取得
			$product_list["video_thumbnail$i"] = $product_list["contents_contents$i"].$arr_video_info["contents_thumbnail"][0]["p180"];
			
			// 各ビデオの再生時間取得
			$product_list["video_duration$i"] = time_format_product_list($arr_video_info["alfstream_duration"]);
			
			// 各ビデオの視聴済時間・残り時間取得
			$sql = "SELECT duration, duration_reading FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents$i"]."'";
			$res1 = $objDbConnect->query_fetch($sql);
			if ($res1){
				$product_list["video_view_flg$i"] = true;
				$product_list["video_reading$i"] = time_format_product_list($res1["duration_reading"]);
				//$product_list["video_remaining$i"] = getTimeSubtraction($res1["duration"], $res1["duration_reading"]);
				$product_list["video_complete_flg$i"] = $res1["complete_flag"];
			} else {
				$product_list["video_view_flg$i"] = false;
				$product_list["video_complete_flg$i"] = 0;
			}

			// 各ビデオの視聴済時間・残り時間取得
			$sql = "SELECT duration, duration_reading, complete_flag FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents".$i."so"]."'";
			$product_list["video_view_flg".$i."so"] = false;
			$product_list["video_complete_flg".$i."so"] = 0;
			if( trim($product_list["contents_contents".$i."so"])!="" ){
				$res1 = $objDbConnect->query_fetch($sql);
				if ($res1){
					$product_list["video_view_flg".$i."so"] = true;
					$product_list["video_reading".$i."so"] = time_format_product_list($res1["duration_reading"]);
					$product_list["video_complete_flg".$i."so"] = $res1["complete_flag"];
				} else {
					$product_list["video_view_flg".$i."so"] = false;
					$product_list["video_complete_flg".$i."so"] = 0;
				}
			}
			$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$_SESSION['user']['id']."' AND video_id='".$product_list["contents_contents".$i."so"]."'";
			$ret_bookmark_so = $objDbConnect->query_fetch($sql);
			if ($ret_bookmark_so){
				$bookmark_time_sec = $ret_bookmark_so['bookmark_time_sec'];
				$product_list["video_view_flg".$i."so"] = true;
				$product_list["video_reading".$i."so"] = time_format_product_list($ret_bookmark_so['bookmark_time_sec']);
			}



			// 総再生時間取得
			if ($arr_video_info["alfstream_duration"] != ''){
				$all_play_time = getTimeAddition($all_play_time, $arr_video_info["alfstream_duration"]);
			}
		}
		$product_list['all_play_time'] = time_format_product_list($all_play_time);
		
		// 受講状況の取得
		$sql = "SELECT status FROM tbl_ethic_question_history WHERE student_id = '".$_SESSION['user']['id']."' AND product_id = '$pid'";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			$ethic_status = $res['status'];
		} else {
			$ethic_status = false;
		}
		
		// 動画を全て視聴したか
		$all_view_flg = false;
		for($i=1; $i<=MAX_CONTENTS; $i++){
			if ($product_list["contents_contents$i"] != ''){
				$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents$i"]."' AND complete_flag = '1'";
				$res = $objDbConnect->query_fetch($sql);
				if ($res){
					if ($res["c"]>=1){
						$all_view_flg = true;
					} else {
						$all_view_flg = false;
						break;
					}
					
				} else {
					$all_view_flg = false;
					break;
				}
			} else {
				break;
			}
		}
		// 視聴中動画が1つでもあるかチェック
		$view_flg = false;
		if (!$all_view_flg){
			for($i=1; $i<=MAX_CONTENTS; $i++){
				$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$product_list["contents_contents$i"]."' AND complete_flag = '0'";
				$res = $objDbConnect->query_fetch($sql);
				if ($res){
					if ($res["c"]>=1){
						$view_flg = true;
						break;
					}
				}
			}
		}
		
		$template->assign('ethic_status', $ethic_status);
		$template->assign('all_view_flg', $all_view_flg);
		
	} else {
		header("Location: /?_=5");
		exit;
	}
	
// -------------
// パスポート用
// -------------
} elseif ($product_list['product_type_add'] == 4){
	$passport_flg = false;
	if ($_SESSION['user']['presence_passport']){
		$passport_flg = true;
	}
	
} else {
}



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


// ---------------
// 関連商品を取得
// ---------------
$arr_related_list = array();
for ($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
	if ($product_list["related_products$i"] != ''){
		$ret = array();
		$sql = "SELECT";
		$sql.= "  product_id,";
		$sql.= "  term_id,";
		$sql.= "  thumbnail,";
		$sql.= "  product_name,";
		$sql.= "  price,";
		$sql.= "  DATE_FORMAT(start_date, '%Y/%m/%d') AS start_date,";
		$sql.= "  DATE_FORMAT(end_date, '%Y/%m/%d') AS end_date";
		$sql.= " FROM";
		$sql.= "  tbl_product";
		$sql.= " WHERE";
		$sql.= "  product_id = '".$product_list["related_products$i"]."'";
		$sql.= "  AND ( (start_date<='".date("Y-m-d")."' AND end_date>='".date("Y-m-d")."') OR (start_date<='".date("Y-m-d")."' AND end_date IS NULL) OR (start_date IS NULL AND end_date>='".date("Y-m-d")."') OR (start_date IS NULL AND end_date IS NULL) )";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret){
			$arr_related_list[$i]['pid'] = $ret['product_id'];
			$arr_related_list[$i]['thumbnail'] = $ret['thumbnail'];
			$arr_related_list[$i]['name'] = $ret['product_name'];
			$arr_related_list[$i]['price'] = $ret['price'];
			$arr_related_list[$i]['start_date'] = $ret['start_date'];
			$arr_related_list[$i]['end_date'] = $ret['end_date'];
			$arr_related_list[$i]['price_intax'] = $ret['price'] + tax_cal_yen($ret['price']);
		}
	}
}

// -------------------
// おすすめ商品を取得
// -------------------
/*
$arr_recommend_list = array();
// ログイン済み
if ($st_login_check){
	// 購入済み商品の商品ID一覧取得
	$sql = "SELECT DISTINCT";
	$sql.= "  T2.product_id";
	$sql.= " FROM";
	$sql.= "  tbl_order AS T1";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_order_detail AS T2";
	$sql.= "      ON T1.order_id = T2.order_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product AS T3";
	$sql.= "      ON T2.product_id = T3.product_id";
	$sql.= " WHERE";
	$sql.= "  T1.payment_status='2'";
	$sql.= "  AND T1.del_flg='0'";
	$sql.= "  AND T2.member_id='".$user_id."'";
	$sql.= "  AND T3.del_flg='0'";
	$sql.= "  AND ( (T3.start_date<='".date("Y-m-d")."' AND T3.end_date>='".date("Y-m-d")."') OR (T3.start_date<='".date("Y-m-d")."' AND T3.end_date IS NULL) OR (T3.start_date IS NULL AND T3.end_date>='".date("Y-m-d")."') OR (T3.start_date IS NULL AND T3.end_date IS NULL) )";
	
	$ret = $objDbConnect->query_fetch_arr($sql);
	if ($ret){
		// 一意の購入済みproduct_id配列を作成
		$arr_product = array();
		$count = count($ret);
		for ($i=0; $i<$count; $i++){
			// 商品
			$arr_product[$ret[$i]['product_id']] = $ret[$i]['product_id'];
		}
	}
	
	// 商品のカテゴリ一覧取得
	$sql = "SELECT";
	$sql.= "  term_id";
	$sql.= " FROM";
	$sql.= "  tbl_product";
	$sql.= " WHERE";
	$sql.= "  product_id='$pid'";
	
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		// カテゴリ(単一の場合)
		if (strpos($ret['term_id'], ',') === false){
			$arr_category[$ret['term_id']] = $ret['term_id'];
			
		// カテゴリ(複数の場合)
		} else {
			$arr_term_id = explode(',', $ret['term_id']);
			foreach ($arr_term_id as $val){
				$arr_category[$val] = $val;
			}
		}
		
		// 閲覧商品カテゴリに属した未購入商品を取得
		$sql = "SELECT DISTINCT";
		$sql.= "  product_id,";
		$sql.= "  product_name,";
		$sql.= "  price,";
		$sql.= "  thumbnail";
		$sql.= " FROM";
		$sql.= "  tbl_product";
		$sql.= " WHERE";
		$sql.= "  del_flg='0'";
		$sql.= "  AND ( (start_date<='".date("Y-m-d")."' AND end_date>='".date("Y-m-d")."') OR (start_date<='".date("Y-m-d")."' AND end_date IS NULL) OR (start_date IS NULL AND end_date>='".date("Y-m-d")."') OR (start_date IS NULL AND end_date IS NULL) )";
		if (!empty($arr_product)){
			$sql.= " AND (";
			$cnt = 1;
			$count = count($arr_product);
			foreach ($arr_product as $val){
				$sql.= " product_id<>'".$val."'";
				if ($cnt != $count){
					$sql.= " OR";
				}
				$cnt++;
			}
			$sql.= " )";
		}
		if (!empty($arr_category)){
			$sql.= " AND (";
			$cnt = 1;
			$count = count($arr_category);
			foreach ($arr_category as $val){
				$sql.= " CONCAT(',',term_id,',') LIKE '%,".$val.",%'";
				if ($cnt != $count){
					$sql.= " OR";
				}
				$cnt++;
			}
			$sql.= " )";
		}
		$sql.= " ORDER BY rand()";
		$sql.= " LIMIT 10";
		
		$arr_recommend_list = $objDbConnect->query_fetch_arr($sql);
		
		if( count($arr_recommend_list)<10 ){
			// 売上商品をランダムで表示
			$sql = "SELECT DISTINCT";
			$sql.= "  T1.product_id,";
			$sql.= "  T1.product_name,";
			$sql.= "  T1.price,";
			$sql.= "  T1.thumbnail";
			$sql.= " FROM";
			$sql.= "  tbl_product AS T1";
			$sql.= "    LEFT JOIN";
			$sql.= "  tbl_order_detail AS T2";
			$sql.= "      ON T1.product_id = T2.product_id";
			$sql.= " WHERE";
			$sql.= "  T1.del_flg='0'";
			$sql.= "  and T2.order_detail_id is null ";
			$sql.= "  AND ( (T1.start_date<='".date("Y-m-d")."' AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date<='".date("Y-m-d")."' AND T1.end_date IS NULL) OR (T1.start_date IS NULL AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date IS NULL AND T1.end_date IS NULL) )";
			foreach ($arr_recommend_list as $val){
				$sql.= " and T1.product_id<>'".$val["product_id"]."'";
			}
			$sql.= " ORDER BY rand()";
			$sql.= " LIMIT 10";
			$arr_recommend_list2 = $objDbConnect->query_fetch_arr($sql);
			foreach($arr_recommend_list2 as $val){
				if( count($arr_recommend_list)<10 ){
					$arr_recommend_list[] = $val;
				}
			}
		}
		
	} else {
		// 売上商品をランダムで表示
		$sql = "SELECT DISTINCT";
		$sql.= "  T1.product_id,";
		$sql.= "  T1.product_name,";
		$sql.= "  T1.price,";
		$sql.= "  T1.thumbnail";
		$sql.= " FROM";
		$sql.= "  tbl_product AS T1";
		$sql.= "    LEFT JOIN";
		$sql.= "  tbl_order_detail AS T2";
		$sql.= "      ON T1.product_id = T2.product_id";
		$sql.= " WHERE";
		$sql.= "  T1.del_flg='0'";
		$sql.= "  AND T2.member_id<>'".$user_id."'";
		$sql.= "  AND ( (T1.start_date<='".date("Y-m-d")."' AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date<='".date("Y-m-d")."' AND T1.end_date IS NULL) OR (T1.start_date IS NULL AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date IS NULL AND T1.end_date IS NULL) )";
		$sql.= " ORDER BY rand()";
		$sql.= " LIMIT 10";
		
		$arr_recommend_list = $objDbConnect->query_fetch_arr($sql);
	}
	
// 未ログイン
} else {
	// 売上商品をランダムで表示(一般公開のみ)
	$sql = "SELECT DISTINCT";
	$sql.= "  T1.product_id,";
	$sql.= "  T1.product_name,";
	$sql.= "  T1.price,";
	$sql.= "  T1.thumbnail";
	$sql.= " FROM";
	$sql.= "  tbl_product AS T1";
	$sql.= " WHERE";
	$sql.= "  T1.product_type='2'";
	$sql.= "  AND T1.del_flg='0'";
	$sql.= "  AND ( (T1.start_date<='".date("Y-m-d")."' AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date<='".date("Y-m-d")."' AND T1.end_date IS NULL) OR (T1.start_date IS NULL AND T1.end_date>='".date("Y-m-d")."') OR (T1.start_date IS NULL AND T1.end_date IS NULL) )";
	$sql.= " ORDER BY rand()";
	$sql.= " LIMIT 10";
	
	$arr_recommend_list = $objDbConnect->query_fetch_arr($sql);
}
// 税込価格のセット
$count = count($arr_recommend_list);
for ($i=0; $i<$count; $i++){
	$arr_recommend_list[$i]['price_intax'] = $arr_recommend_list[$i]['price'] + tax_cal_yen($arr_recommend_list[$i]['price']);
}
*/

// -----------------------
// コンテンツ各種情報取得
// -----------------------
$arr_player_iframe = array();     // プレイヤーiframe配列
$arr_player_thumbnail = array();  // ビデオサムネイル配列

for ($i=1; $i<=MAX_CONTENTS; $i++){
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// -------------------------
	// コンテンツ公開フラグ設定
	// -------------------------
	// 公開日・終了日登録あり ※コンテンツの登録有無は全分岐で見る
	if (($product_list["contents_start_date$i"]=='' || $product_list["contents_start_date$i"]=='0000-00-00 00:00:00')
	 && ($product_list["contents_end_date$i"]=='' || $product_list["contents_end_date$i"]=='0000-00-00 00:00:00')
	 && $product_list["contents_contents$i"]!=''){
	 	// ログイン済み
		if ($st_login_check){
			$product_list["contents_open_flg$i"] = true;
		// 未ログインは無料公開の有無を見る
		} else {
			// 無料商品の場合
			if ($product_list['price']=='0'){
				$product_list["contents_open_flg$i"] = true;
			} else {
				// 有料動画の場合
				if ($product_list["contents_free_time$i"]=='0'){
					$product_list["contents_open_flg$i"] = false;
				} else {
					$product_list["contents_open_flg$i"] = true;
				}
			}
		}
	// 公開日・終了日登録なし
	} elseif ($product_list["contents_start_date$i"]!=''
	       && $product_list["contents_start_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_end_date$i"]!=''
	       && $product_list["contents_end_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents$i"]!=''){
		if (strtotime($product_list["contents_start_date$i"]) <= strtotime(date('Y-m-d H:i:s'))
			&& strtotime($product_list["contents_end_date$i"]) >= strtotime(date('Y-m-d H:i:s'))
			&& $product_list["contents_contents$i"]!=''){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg$i"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg$i"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg$i"] = false;
					} else {
						$product_list["contents_open_flg$i"] = true;
					}
				}
			}
		}
	// 公開日のみ登録あり
	} elseif ($product_list["contents_start_date$i"]!=''
	       && $product_list["contents_start_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents$i"]!=''){
		if (strtotime($product_list["contents_start_date$i"]) <= strtotime(date('Y-m-d H:i:s'))){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg$i"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg$i"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg$i"] = false;
					} else {
						$product_list["contents_open_flg$i"] = true;
					}
				}
			}
		}
	// 終了日のみ登録あり
	} elseif ($product_list["contents_end_date$i"]!=''
	       && $product_list["contents_end_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents$i"]!=''){
		if (strtotime($product_list["contents_end_date$i"]) >= strtotime(date('Y-m-d H:i:s'))){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg$i"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg$i"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg$i"] = false;
					} else {
						$product_list["contents_open_flg$i"] = true;
					}
				}
			}
		}
	// ヒットしなければプレイヤー表示なし
	} else {
		$product_list["contents_open_flg$i"] = false;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// -------------------------
	// コンテンツ公開フラグ設定
	// -------------------------
	// 公開日・終了日登録あり ※コンテンツの登録有無は全分岐で見る
	if (($product_list["contents_start_date$i"]=='' || $product_list["contents_start_date$i"]=='0000-00-00 00:00:00')
	 && ($product_list["contents_end_date$i"]=='' || $product_list["contents_end_date$i"]=='0000-00-00 00:00:00')
	 && $product_list["contents_contents".$i."so"]!=''){
	 	// ログイン済み
		if ($st_login_check){
			$product_list["contents_open_flg".$i."so"] = true;
		// 未ログインは無料公開の有無を見る
		} else {
			// 無料商品の場合
			if ($product_list['price']=='0'){
				$product_list["contents_open_flg".$i."so"] = true;
			} else {
				// 有料動画の場合
				if ($product_list["contents_free_time$i"]=='0'){
					$product_list["contents_open_flg".$i."so"] = false;
				} else {
					$product_list["contents_open_flg".$i."so"] = true;
				}
			}
		}
	// 公開日・終了日登録なし
	} elseif ($product_list["contents_start_date$i"]!=''
	       && $product_list["contents_start_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_end_date$i"]!=''
	       && $product_list["contents_end_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents".$i."so"]!=''){
		if (strtotime($product_list["contents_start_date$i"]) <= strtotime(date('Y-m-d H:i:s'))
			&& strtotime($product_list["contents_end_date$i"]) >= strtotime(date('Y-m-d H:i:s'))
			&& $product_list["contents_contents".$i."so"]!=''){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg".$i."so"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg".$i."so"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg".$i."so"] = false;
					} else {
						$product_list["contents_open_flg".$i."so"] = true;
					}
				}
			}
		}
	// 公開日のみ登録あり
	} elseif ($product_list["contents_start_date$i"]!=''
	       && $product_list["contents_start_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents".$i."so"]!=''){
		if (strtotime($product_list["contents_start_date$i"]) <= strtotime(date('Y-m-d H:i:s'))){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg".$i."so"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg".$i."so"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg".$i."so"] = false;
					} else {
						$product_list["contents_open_flg".$i."so"] = true;
					}
				}
			}
		}
	// 終了日のみ登録あり
	} elseif ($product_list["contents_end_date$i"]!=''
	       && $product_list["contents_end_date$i"]!='0000-00-00 00:00:00'
	       && $product_list["contents_contents".$i."so"]!=''){
		if (strtotime($product_list["contents_end_date$i"]) >= strtotime(date('Y-m-d H:i:s'))){
			// ログイン済み
			if ($st_login_check){
				$product_list["contents_open_flg".$i."so"] = true;
			// 未ログインは無料公開の有無を見る
			} else {
				// 無料商品の場合
				if ($product_list['price']=='0'){
					$product_list["contents_open_flg".$i."so"] = true;
				} else {
					// 有料動画の場合
					if ($product_list["contents_free_time$i"]=='0'){
						$product_list["contents_open_flg".$i."so"] = false;
					} else {
						$product_list["contents_open_flg".$i."so"] = true;
					}
				}
			}
		}
	// ヒットしなければプレイヤー表示なし
	} else {
		$product_list["contents_open_flg".$i."so"] = false;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	




	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// -------------------------
	// 視聴ボタン表示フラグ設定
	// -------------------------
	// 公開済みかつ無料部分ありの場合
	if ($product_list["contents_open_flg$i"] && $product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!=''){
		$product_list["contents_view_flg$i"] = true;
		
	// 公開済みの場合
	} elseif ($product_list["contents_open_flg$i"]) {
		// ログイン済み
		if ($st_login_check){
			// パスポートを所持している場合
			if ($_SESSION['user']['presence_passport']==1){
				$product_list["contents_view_flg$i"] = true;
				
			} else {
				// 無料
				if ($product_list['price']==0){
					$product_list["contents_view_flg$i"] = true;
				// 月額課金ユーザ
				} elseif ($user_list['member_type']==2){
					$product_list["contents_view_flg$i"] = true;
				// 購入済み・視聴期間超え確認
				} else {
					if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id)){
						$product_list["contents_view_flg$i"] = true;
					} else {
						$product_list["contents_view_flg$i"] = false;
					}
				}
			}
			
		// 未ログイン
		} else {
			// 無料かつ一般公開商品またはコンテンツ無料部分あり
			if (($product_list['price']==0 && $product_list['product_type']==2)
			 || ($product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!='')){
				$product_list["contents_view_flg$i"] = true;
			}
		}
		
	// ヒットしなければ視聴ボタン表示なし
	} else {
		$product_list["contents_view_flg$i"] = false;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// -------------------------
	// 視聴ボタン表示フラグ設定
	// -------------------------
	// 公開済みかつ無料部分ありの場合
	if ($product_list["contents_open_flg".$i."so"] && $product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!=''){
		$product_list["contents_view_flg".$i."so"] = true;
	// 公開済みの場合
	} elseif ($product_list["contents_open_flg".$i."so"]) {
		// ログイン済み
		if ($st_login_check){
			// パスポートを所持している場合
			if ($_SESSION['user']['presence_passport']==1){
				$product_list["contents_view_flg".$i."so"] = true;
				
			} else {
				// 無料
				if ($product_list['price']==0){
					$product_list["contents_view_flg".$i."so"] = true;
				// 月額課金ユーザ
				} elseif ($user_list['member_type']==2){
					$product_list["contents_view_flg".$i."so"] = true;
				// 購入済み・視聴期間超え確認
				} else {
					if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id)){
						$product_list["contents_view_flg".$i."so"] = true;
					} else {
						$product_list["contents_view_flg".$i."so"] = false;
					}
				}
			}
			
		// 未ログイン
		} else {
			// 無料かつ一般公開商品またはコンテンツ無料部分あり
			if (($product_list['price']==0 && $product_list['product_type']==2)
			 || ($product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!='')){
				$product_list["contents_view_flg".$i."so"] = true;
			}
		}
		
	// ヒットしなければ視聴ボタン表示なし
	} else {
		$product_list["contents_view_flg".$i."so"] = false;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



	
	// -------------------------
	// 倍速フラグ設定
	// -------------------------
	$product_list["contents_baisoku_flg$i"] = false;
	// 公開済みかつ無料部分ありの場合
	if ($product_list["contents_open_flg$i"] && $product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!=''){
		//+++++++++++++++++++++++++++++++++
		$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
		$arr_ckey = $objDbConnect->query_fetch($sql);
		$get_video_data = array(
			"ckey"   => $arr_ckey['idkey'],
			"uid"    => $_SESSION['user']['id'],
			"coder"  => "x13",
			"playrate" => "1.3",
		);

		if (!empty($get_video_data)){
			$get_alf_player = get_alf_player($get_video_data);
			if ($get_alf_player['err']==1){
				$product_list["contents_baisoku_flg$i"] = false;
			} elseif ($get_alf_player['err']==0){
				$product_list["contents_baisoku_flg$i"] = true;
			} else {
				$product_list["contents_baisoku_flg$i"] = false;
			}
		}
		//---------------------------------
		if( $product_list["contents_baisoku_flg$i"] == false ){
			$get_video_data = array(
				"ckey"   => $arr_ckey['idkey'],
				"uid"    => $_SESSION['user']['id'],
				"coder"  => "180px13",
				"playrate" => "1.3",
			);

			if (!empty($get_video_data)){
				$get_alf_player = get_alf_player($get_video_data);
				if ($get_alf_player['err']==1){
					$product_list["contents_baisoku_flg$i"] = false;
				} elseif ($get_alf_player['err']==0){
					$product_list["contents_baisoku_flg$i"] = true;
				} else {
					$product_list["contents_baisoku_flg$i"] = false;
				}
			}
		}
		//---------------------------------
		//+++++++++++++++++++++++++++++++++
	// 公開済みの場合
	} elseif ($product_list["contents_open_flg$i"]) {
		// ログイン済み
		if ($st_login_check){
			// パスポートを所持している場合
			if ($_SESSION['user']['presence_passport']==1){
				//$product_list["contents_baisoku_flg$i"] = true;
				//+++++++++++++++++++++++++++++++++
				$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
				$arr_ckey = $objDbConnect->query_fetch($sql);
				$get_video_data = array(
					"ckey"   => $arr_ckey['idkey'],
					"uid"    => $_SESSION['user']['id'],
					"coder"  => "x13",
					"playrate" => "1.3",
				);
				if (!empty($get_video_data)){
					$get_alf_player = get_alf_player($get_video_data);
					if ($get_alf_player['err']==1){
						$product_list["contents_baisoku_flg$i"] = false;
					} elseif ($get_alf_player['err']==0){
						$product_list["contents_baisoku_flg$i"] = true;
					} else {
						$product_list["contents_baisoku_flg$i"] = false;
					}
				}
				//---------------------------------
				if( $product_list["contents_baisoku_flg$i"] == false ){
					$get_video_data = array(
						"ckey"   => $arr_ckey['idkey'],
						"uid"    => $_SESSION['user']['id'],
						"coder"  => "180px13",
						"playrate" => "1.3",
					);

					if (!empty($get_video_data)){
						$get_alf_player = get_alf_player($get_video_data);
						if ($get_alf_player['err']==1){
							$product_list["contents_baisoku_flg$i"] = false;
						} elseif ($get_alf_player['err']==0){
							$product_list["contents_baisoku_flg$i"] = true;
						} else {
							$product_list["contents_baisoku_flg$i"] = false;
						}
					}
				}
				//---------------------------------
				//+++++++++++++++++++++++++++++++++
			} else {
				// 無料
				if ($product_list['price']==0){
					//$product_list["contents_baisoku_flg$i"] = true;
					//+++++++++++++++++++++++++++++++++
					$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
					$arr_ckey = $objDbConnect->query_fetch($sql);
					$get_video_data = array(
						"ckey"   => $arr_ckey['idkey'],
						"uid"    => $_SESSION['user']['id'],
						"coder"  => "x13",
						"playrate" => "1.3",
					);
					if (!empty($get_video_data)){
						$get_alf_player = get_alf_player($get_video_data);
						if ($get_alf_player['err']==1){
							$product_list["contents_baisoku_flg$i"] = false;
						} elseif ($get_alf_player['err']==0){
							$product_list["contents_baisoku_flg$i"] = true;
						} else {
							$product_list["contents_baisoku_flg$i"] = false;
						}
					}
					//---------------------------------
					if( $product_list["contents_baisoku_flg$i"] == false ){
						$get_video_data = array(
							"ckey"   => $arr_ckey['idkey'],
							"uid"    => $_SESSION['user']['id'],
							"coder"  => "180px13",
							"playrate" => "1.3",
						);

						if (!empty($get_video_data)){
							$get_alf_player = get_alf_player($get_video_data);
							if ($get_alf_player['err']==1){
								$product_list["contents_baisoku_flg$i"] = false;
							} elseif ($get_alf_player['err']==0){
								$product_list["contents_baisoku_flg$i"] = true;
							} else {
								$product_list["contents_baisoku_flg$i"] = false;
							}
						}
					}
					//---------------------------------
					//+++++++++++++++++++++++++++++++++
				// 月額課金ユーザ
				} elseif ($user_list['member_type']==2){
					//$product_list["contents_baisoku_flg$i"] = true;
					//+++++++++++++++++++++++++++++++++
					$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
					$arr_ckey = $objDbConnect->query_fetch($sql);
					$get_video_data = array(
						"ckey"   => $arr_ckey['idkey'],
						"uid"    => $_SESSION['user']['id'],
						"coder"  => "x13",
						"playrate" => "1.3",
					);
					if (!empty($get_video_data)){
						$get_alf_player = get_alf_player($get_video_data);
						if ($get_alf_player['err']==1){
							$product_list["contents_baisoku_flg$i"] = false;
						} elseif ($get_alf_player['err']==0){
							$product_list["contents_baisoku_flg$i"] = true;
						} else {
							$product_list["contents_baisoku_flg$i"] = false;
						}
					}
					//---------------------------------
					if( $product_list["contents_baisoku_flg$i"] == false ){
						$get_video_data = array(
							"ckey"   => $arr_ckey['idkey'],
							"uid"    => $_SESSION['user']['id'],
							"coder"  => "180px13",
							"playrate" => "1.3",
						);

						if (!empty($get_video_data)){
							$get_alf_player = get_alf_player($get_video_data);
							if ($get_alf_player['err']==1){
								$product_list["contents_baisoku_flg$i"] = false;
							} elseif ($get_alf_player['err']==0){
								$product_list["contents_baisoku_flg$i"] = true;
							} else {
								$product_list["contents_baisoku_flg$i"] = false;
							}
						}
					}
					//---------------------------------
					//+++++++++++++++++++++++++++++++++
				// 購入済み・視聴期間超え確認
				} else {
					if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id)){
						//$product_list["contents_baisoku_flg$i"] = true;
						//+++++++++++++++++++++++++++++++++
						$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
						$arr_ckey = $objDbConnect->query_fetch($sql);
						$get_video_data = array(
							"ckey"   => $arr_ckey['idkey'],
							"uid"    => $_SESSION['user']['id'],
							"coder"  => "x13",
							"playrate" => "1.3",
						);
						if (!empty($get_video_data)){
							$get_alf_player = get_alf_player($get_video_data);
							if ($get_alf_player['err']==1){
								$product_list["contents_baisoku_flg$i"] = false;
							} elseif ($get_alf_player['err']==0){
								$product_list["contents_baisoku_flg$i"] = true;
							} else {
								$product_list["contents_baisoku_flg$i"] = false;
							}
						}
						//---------------------------------
						if( $product_list["contents_baisoku_flg$i"] == false ){
							$get_video_data = array(
								"ckey"   => $arr_ckey['idkey'],
								"uid"    => $_SESSION['user']['id'],
								"coder"  => "180px13",
								"playrate" => "1.3",
							);

							if (!empty($get_video_data)){
								$get_alf_player = get_alf_player($get_video_data);
								if ($get_alf_player['err']==1){
									$product_list["contents_baisoku_flg$i"] = false;
								} elseif ($get_alf_player['err']==0){
									$product_list["contents_baisoku_flg$i"] = true;
								} else {
									$product_list["contents_baisoku_flg$i"] = false;
								}
							}
						}
						//---------------------------------
						//+++++++++++++++++++++++++++++++++
					} else {
						$product_list["contents_baisoku_flg$i"] = false;
					}
				}
			}
			
		// 未ログイン
		} else {
			// 無料かつ一般公開商品またはコンテンツ無料部分あり
			if (($product_list['price']==0 && $product_list['product_type']==2)
			 || ($product_list["contents_free_time$i"]!=0 && $product_list["contents_free_time$i"]!='')){
				//$product_list["contents_baisoku_flg$i"] = true;
				//+++++++++++++++++++++++++++++++++
				$sql = "SELECT idkey, video_logic_name FROM video WHERE video_id='".$product_list["contents_contents$i"]."' limit 1";
				$arr_ckey = $objDbConnect->query_fetch($sql);
				$get_video_data = array(
					"ckey"   => $arr_ckey['idkey'],
					"uid"    => $_SESSION['user']['id'],
					"coder"  => "x13",
					"playrate" => "1.3",
				);
				if (!empty($get_video_data)){
					$get_alf_player = get_alf_player($get_video_data);
					if ($get_alf_player['err']==1){
						$product_list["contents_baisoku_flg$i"] = false;
					} elseif ($get_alf_player['err']==0){
						$product_list["contents_baisoku_flg$i"] = true;
					} else {
						$product_list["contents_baisoku_flg$i"] = false;
					}
				}
				//---------------------------------
				if( $product_list["contents_baisoku_flg$i"] == false ){
					$get_video_data = array(
						"ckey"   => $arr_ckey['idkey'],
						"uid"    => $_SESSION['user']['id'],
						"coder"  => "180px13",
						"playrate" => "1.3",
					);

					if (!empty($get_video_data)){
						$get_alf_player = get_alf_player($get_video_data);
						if ($get_alf_player['err']==1){
							$product_list["contents_baisoku_flg$i"] = false;
						} elseif ($get_alf_player['err']==0){
							$product_list["contents_baisoku_flg$i"] = true;
						} else {
							$product_list["contents_baisoku_flg$i"] = false;
						}
					}
				}
				//---------------------------------
				//+++++++++++++++++++++++++++++++++
			}
		}
		
	// ヒットしなければ視聴ボタン表示なし
	} else {
		$product_list["contents_baisoku_flg$i"] = false;
	}

	// -----------------------------
	// プレイヤー・サムネイルの取得
	// -----------------------------
	if ($product_list["contents_view_flg$i"]){
		// プレイヤー表示用iframe配列の作成
		if ( $isApple || $isAndroid ){
			if( $isAndroidTablet ){
				$arr_player_iframe['frame'][] = '<iframe id="playerFrame'.$i.'" name="playerFrame'.$i.'" src="/product/player_frame.php?pid='.$pid.'&vid='.$product_list["contents_contents$i"].'&cftname=contents_free_time'.$i.'&temp='.date("YmdHis").'" width="180" height="135" scrolling="no" >iframe未対応ブラウザのため動画を表示できません。</iframe>';
			} elseif ( $isPad ){
				$arr_player_iframe['frame'][] = '<iframe id="playerFrame'.$i.'" name="playerFrame'.$i.'" src="/product/player_frame.php?pid='.$pid.'&vid='.$product_list["contents_contents$i"].'&cftname=contents_free_time'.$i.'&temp='.date("YmdHis").'" width="180" height="135" scrolling="no" >iframe未対応ブラウザのため動画を表示できません。</iframe>';
			} else {
				$arr_player_iframe['frame'][] = '<iframe id="playerFrame'.$i.'" name="playerFrame'.$i.'" src="/product/player_frame.php?pid='.$pid.'&vid='.$product_list["contents_contents$i"].'&cftname=contents_free_time'.$i.'&temp='.date("YmdHis").'" width="240" height="180" scrolling="no" >iframe未対応ブラウザのため動画を表示できません。</iframe>';
			}
		} else {
			$arr_player_iframe['frame'][] = '<iframe id="playerFrame'.$i.'" name="playerFrame'.$i.'" src="/product/player_frame.php?pid='.$pid.'&vid='.$product_list["contents_contents$i"].'&cftname=contents_free_time'.$i.'&temp='.date("YmdHis").'" width="180" height="135" scrolling="no" >iframe未対応ブラウザのため動画を表示できません。</iframe>';
		}
		$arr_player_iframe['frame_no'][] = $i;
		
		// サムネイルの取得
		$ret = '';
		$sql = "SELECT * FROM video_alfstream_status WHERE video_id='".$product_list["contents_contents$i"]."'";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret){
			$arr_player_thumbnail_temp["contents_contents$i"] = $ret;
			$arr_player_thumbnail_temp["contents_contents$i"] = json_decode($arr_player_thumbnail_temp["contents_contents$i"]["alfstream_thumbnail"]);
			// 取得画像名にパスを設定する
			$img_no = 0;
			$p180 = '180p';
			$p180x10 = '180px10';
			$p480 = '480p';
			$p480x10 = '480px10';
			foreach ($arr_player_thumbnail_temp["contents_contents$i"] as $contents_thumbnail){
				$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p180'] = $product_list["contents_contents$i"].'/'.$contents_thumbnail->$p180;
				$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p180x10'] = $product_list["contents_contents$i"].'/'.$contents_thumbnail->$p180x10;
				$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p480'] = $product_list["contents_contents$i"].'/'.$contents_thumbnail->$p480;
				$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p480x10'] = $product_list["contents_contents$i"].'/'.$contents_thumbnail->$p480x10;
				$img_no++;
			}
		}
	}
}
// プレイヤー表示数の取得
if (isset($arr_player_iframe['frame'])){
	$player_iframe_count = count($arr_player_iframe['frame']);
} else {
	$player_iframe_count = 0;
}

// -------------------------
// 購入ボタン表示フラグ設定
// -------------------------
$buy_flg = true;
$buy_wait_flg = false;
// ログイン済み

/* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
/* デバッグ */
//print_r("[presence_passport:".$_SESSION['user']['presence_passport']."]");
//print_r("[product_type_add:".$product_list['product_type_add']."]");
//print_r("[price:".$product_list['price']."]");
//print_r("[member_type:".$user_list['member_type']."]");
/* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */

if ($st_login_check){
	// パスポートを所持している場合
	if ($_SESSION['user']['presence_passport']==1 && $product_list['product_type_add']==1){
		$buy_flg = false;
		//+++++++++++++++++++++++++++++++++++++++++++
		$sql = "";
		$sql.= "SELECT ";
		$sql.= " COUNT(tbl_order_detail.order_detail_id) as c ";
		$sql.= "FROM ";
		$sql.= " tbl_order_detail ";
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
				
				$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,update_date,payment_status,payment_date,product_type_add,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$pid."','0','0','1','0','".date("Y-m-d H:i:s")."','2','".date("Y-m-d H:i:s")."','".$product_list['product_type_add']."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$pid),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$pid),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$pid),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$pid),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$pid))";
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
	} else {
		// 無料確認
		if ($product_list['price']==0){
			if ($product_list['product_type_add']!=2){
				$buy_flg = false;
			}
		}
		// 月額課金ユーザ確認
		if ($user_list['member_type']==2){
			if ($product_list['product_type_add']!=2){
				$buy_flg = false;
			}
		// 購入済み・視聴期間超え確認
		} else {
			// 会場研修商品の場合
			if ($product_list['product_type_add'] == '2'){
				$live_flg = true;
			} else {
				$live_flg = false;
			}
			
			if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id, $live_flg)){
				$buy_flg = false;
			} else {
				$buy_flg = true;

				/* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
				/* sano add 20140829 */
				// 無料確認
				if ($product_list['price']==0){
					if ($product_list['product_type_add']!=2){
						$buy_flg = false;
					}
				}
				/* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
			}
		}
	}

	//入金待ちの有無
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " COUNT(tbl_order_detail.order_detail_id) as c ";
	$sql.= "FROM ";
	$sql.= " tbl_order_detail ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
	$sql.= "WHERE ";
	$sql.= " tbl_order_detail.member_id='".$_SESSION['user']['id']."' "; 
	$sql.= " AND tbl_order_detail.product_id='".$pid."' ";
	$sql.= " AND tbl_order_detail.payment_status='1' ";
	$sql.= " AND tbl_order.payment_type='12' ";
	$sql.= " AND (tbl_order.payment_status='1' OR tbl_order.payment_status='3') ";
	//$sql.= " AND tbl_order_detail.create_date>='".date('Y-m-d H:i:s',strtotime("-10 day"))."' ";
	//print_r("<!--[".$sql."]-->");
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret['c']==0){
	} else {
		$buy_wait_flg = true;
	}
	
// 未ログイン
} else {
	// 無料確認
	if ($product_list['price']==0){
		if ($product_list['product_type_add']==1){
			$buy_flg = false;
		}
	}
}

// -------------------------
// お気に入り表示フラグ設定
// -------------------------
$favorite_flg = false;      // お気に入りボタン表示フラグ
$favorite_icon_flg = false; // お気に入りアイコン表示フラグ
if ($st_login_check){
	$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' AND product_id='".$pid."' AND del_flg='0'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret['c']==0){
		$favorite_flg = true;
	} else {
		$favorite_icon_flg = true;
	}
}

// ---------------------------------
// ダウンロードボタン表示フラグ設定
// ---------------------------------
$download_flg = false;
// 無料商品の場合は常に表示
if ($product_list['price']==0){
	$download_flg = true;
}
// 有料商品の場合は購入済みの時に表示
if ($st_login_check){
	if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id)){
		$download_flg = true;
	}
}

// ---------------------------------
// 設問付きeラーニング情報の設定
// ---------------------------------
$product_list["arr_product_disp_warning_word"] = array();;
$product_list["hantei_ari"] = false;
$product_list["now_passing_contents_no"] = '0';
$product_list["all_passing_flg"] = false;
if($product_list['product_kind_flg']=='3'){
	$nowdate = date('Y-m-d H:i:s');
	
	$product_list["arr_product_disp_warning_word"] = explode(',', $product_list["product_disp_warning_word"]);
	
	$product_contents_info = get_product_contents_info($pid);
	if(!empty($product_contents_info)){
		$pci = array();
		foreach($product_contents_info as $val){
			$pci[$val['contents_no']] = $val;
		}
		for ($i=1; $i<=MAX_CONTENTS; $i++){
			if(isset($pci[$i])){
				$product_list["exam_id_test$i"] = $pci[$i]['exam_id_test'];
				$product_list["exam_id_question$i"] = $pci[$i]['exam_id_question'];
				$product_list["btn_type$i"] = $pci[$i]['btn_type'];
				$product_list["disp_warning_word$i"] = explode(',', $pci[$i]['disp_warning_word']);
				$product_list["exam_test_all_answered$i"] = get_all_exam_answered($product_list["exam_id_test$i"], $user_id, $pid, $i);
				$product_list["exam_question_all_answered$i"] = get_all_exam_answered($product_list["exam_id_question$i"], $user_id, $pid, $i);
				
				// テストの判定基準を満たしているかどうか
				$product_list["exam_test_passing_flg$i"] = "0";
				if($product_list["exam_test_all_answered$i"]){
					$product_list["exam_test_passing_flg$i"] = get_exam_answer_passing_flg($product_list["exam_id_test$i"], $user_id, $pid, $i);
				}
				$product_list["exam_question_passing_flg$i"] = "0";
				if($product_list["exam_question_all_answered$i"]){
					$product_list["exam_question_passing_flg$i"] = get_exam_answer_passing_flg($product_list["exam_id_question$i"], $user_id, $pid, $i);
				}
				
				// 問題形式の取得(テスト)
				$product_list["display_format_test$i"] = "";
				$sql = "SELECT display_format, public_flag, resubmit_flag FROM exam WHERE exam_id='".$product_list["exam_id_test$i"]."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					$product_list["display_format_test$i"] = $ret['display_format'];
					$product_list["public_flag_test$i"] = $ret['public_flag'];
					$product_list["resubmit_flag_test$i"] = $ret['resubmit_flag'];
				}
				
				// 問題形式の取得(アンケート)
				$product_list["display_format_question$i"] = "";
				$sql = "SELECT display_format, public_flag, resubmit_flag FROM exam WHERE exam_id='".$product_list["exam_id_question$i"]."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					$product_list["display_format_question$i"] = $ret['display_format'];
					$product_list["public_flag_question$i"] = $ret['public_flag'];
					$product_list["resubmit_flag_question$i"] = $ret['resubmit_flag'];
				}
				
				// 提出期限チェック(テスト)
				if($product_list["exam_id_test$i"]>0){
					$sql = "SELECT exam_open, exam_close FROM exam WHERE exam_id='".$product_list["exam_id_test$i"]."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						if($ret['exam_open']<=$nowdate && $ret['exam_close']>=$nowdate){
							$product_list["submit_possible_flg_test$i"] = true;
						}
					}
				} else {
					$product_list["submit_possible_flg_test$i"] = false;
				}
				
				// 提出期限チェック(アンケート)
				if($product_list["exam_id_question$i"]>0){
					$sql = "SELECT exam_open, exam_close FROM exam WHERE exam_id='".$product_list["exam_id_question$i"]."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						if($ret['exam_open']<=$nowdate && $ret['exam_close']>=$nowdate){
							$product_list["submit_possible_flg_question$i"] = true;
						}
					}
				} else {
					$product_list["submit_possible_flg_question$i"] = false;
				}
				
				// 判定基準が設定されている問題があるかどうか
				if(!$product_list["hantei_ari"]){
					$sql = "SELECT criteria_value FROM exam WHERE exam_id='".$product_list["exam_id_test$i"]."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						if($ret['criteria_value']>0){
							$product_list["hantei_ari"] = true;
						}
					}
				}
				
			} else {
				$product_list["exam_id_test$i"] = "";
				$product_list["exam_id_question$i"] = "";
				$product_list["btn_type$i"] = "";
				$product_list["disp_warning_word$i"] = "";
				$product_list["exam_test_all_answered$i"] = false;
				$product_list["exam_question_all_answered$i"] = false;
				$product_list["exam_test_passing_flg$i"] = "0";
				$product_list["exam_question_passing_flg$i"] = "1";
				$product_list["display_format_test$i"] = "";
				$product_list["display_format_question$i"] = "";
				$product_list["public_flag_test$i"] = "9";
				$product_list["public_flag_question$i"] = "9";
				$product_list["resubmit_flag_test$i"] = "0";
				$product_list["resubmit_flag_question$i"] = "0";
				$product_list["submit_possible_flg_test$i"] = false;
				$product_list["submit_possible_flg_question$i"] = false;
			}
		}
		
		// 合格しているコンテンツ番号の最大値を取得(動画再生ボタン表示判定に使用)
		$sql = "SELECT MAX(contents_no) AS now_passing_contents_no FROM exam_answer WHERE status=0 AND passing_flg=1 AND student_id='".$user_id."' AND product_id='".$pid."'";
		$ret = $objDbConnect->query_fetch($sql);
		if($ret){
			if(is_numeric($ret['now_passing_contents_no'])){
				$product_list["now_passing_contents_no"] = $ret['now_passing_contents_no'];
			}
		}
		
		// すべて合格しているか
		$sql = "SELECT * FROM rel_product_contents WHERE product_id='".$pid."'";
		$ret = $objDbConnect->query_fetch_arr($sql);
		if($ret){
			$all_count = 0;
			foreach($ret as $val){
				if($val['exam_id_test']>0 || $val['exam_id_question']>0){
					$all_count++;
				}
			}
			
			if($all_count>0){
				$sql = "SELECT exam_answer_id FROM exam_answer WHERE status=0 AND passing_flg=1 AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' GROUP BY contents_no";
				$ret = $objDbConnect->query_fetch_arr($sql);
				if($ret){
					if(count($ret)==$all_count){
						$product_list["all_passing_flg"] = true;
					}
				}
			} else {
				$product_list["all_passing_flg"] = true;
			}
		}
	}
} else {
	for ($i=1; $i<=MAX_CONTENTS; $i++){
		$product_list["exam_id_test$i"] = "";
		$product_list["exam_id_question$i"] = "";
		$product_list["btn_type$i"] = "";
		$product_list["disp_warning_word$i"] = "";
		$product_list["exam_test_all_answered$i"] = false;
		$product_list["exam_question_all_answered$i"] = false;
		$product_list["exam_test_passing_flg$i"] = "0";
		$product_list["exam_question_passing_flg$i"] = "1";
		$product_list["display_format_test$i"] = "";
		$product_list["display_format_question$i"] = "";
		$product_list["public_flag_test$i"] = "9";
		$product_list["public_flag_question$i"] = "9";
		$product_list["resubmit_flag_test$i"] = "0";
		$product_list["resubmit_flag_question$i"] = "0";
		$product_list["submit_possible_flg_test$i"] = false;
		$product_list["submit_possible_flg_question$i"] = false;
	}
}

if( $product_list["teacher_student_id"] == $_SESSION['user']['id'] ){
	for ($i=1; $i<=MAX_CONTENTS; $i++){
		$product_list["exam_test_passing_flg$i"] = "1";
		$product_list["exam_question_passing_flg$i"] = "1";
	}
	$product_list["all_passing_flg"] = true;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$exam2 = array();
$exam2_answer = array();
if( $product_list["exam2_id"]>0 ){
	$sql = "SELECT * FROM exam2 WHERE exam2_id='".$product_list["exam2_id"]."'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$exam2 = $res;
		$sql = "SELECT * FROM exam2_answer WHERE exam2_id='".$product_list["exam2_id"]."' AND student_id = '".$_SESSION['user']['id']."' AND status='0' AND product_id='".$product_list["product_id"]."'";
		$exam2_answer = $objDbConnect->query_fetch_arr($sql);
	} else {
		$exam2 = false;
	}
}
$template->assign('exam2', $exam2);
$template->assign('exam2_answer', $exam2_answer);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//引数設定
$param = array(
		'product_id' => mysqli_real_escape_string($objDbConnect->connect,$pid),
		'exam2_id'   => $product_list["exam2_id"],
	);
$arr_exam2 = array();
/*
$sql = "SELECT exam2.* FROM exam2 WHERE 1=1 AND exam2_id=".$param["exam2_id"]." ";
$arr_exam2 = $objDbConnect->query_fetch_arr($sql);
if( count($arr_exam2) > 0){
	$arr_exam2[0]["exam2_problem"] = array();
	$sql = "SELECT exam2_problem.* FROM rel_exam2_problem LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id=exam2_problem.exam2_problem_id WHERE rel_exam2_problem.exam2_id=".$arr_exam2[0]["exam2_id"]." ORDER BY rel_exam2_problem.exam2_no ASC ";
	$arr_exam2[0]["exam2_problem"] = $objDbConnect->query_fetch_arr($sql);
	for($i3=0;$i3<count($arr_exam2[0]["exam2_problem"]);$i3++){ 
		$arr_exam2[0]["exam2_problem"][$i3]["answer_contents_arr"] = json_decode($arr_exam2[0]["exam2_problem"][$i3]["answer_contents"], true);
	}

	$arr_exam2[0]["student"] = array();
	$sql = "SELECT student_id FROM exam2_answer WHERE exam2_id=".$param["exam2_id"]." AND exam2_answer.product_id=".$param["product_id"]." AND open_review=1 GROUP BY student_id ORDER BY student_id ASC ";
	$arr_exam2[0]["student"] = $objDbConnect->query_fetch_arr($sql);
	if ( count($arr_exam2[0]["student"]) > 0 ){
		for($i2=0;$i2<count($arr_exam2[0]["student"]);$i2++){ 
			$arr_exam2[0]["student"][$i2]["info"] = array();
			$sql = "SELECT ";
			$sql.= " * ";
			$sql.= "FROM ";
			$sql.= " student ";
			$sql.= "WHERE ";
			$sql.= " student_id=".$arr_exam2[0]["student"][$i2]["student_id"]." ";
			$sql.= "LIMIT 1 ";
			$arr_exam2[0]["student"][$i2]["info"] = $objDbConnect->query_fetch_arr($sql);

			$arr_exam2[0]["student"][$i2]["answer"] = array();
			$sql = "SELECT ";
			$sql.= " exam2_problem_id, student_id, max( exam2_answer_no ) as mean , exam2_answer_contents ";
			$sql.= "FROM ";
			$sql.= " exam2_answer ";
			$sql.= "WHERE ";
			$sql.= " exam2_id=".$param["exam2_id"]." ";
			$sql.= " AND student_id=".$arr_exam2[0]["student"][$i2]["student_id"]." ";
			$sql.= " AND product_id=".$param["product_id"]." ";
			$sql.= " AND open_review=1 ";
			$sql.= "GROUP BY exam2_id, exam2_problem_id, student_id ";
			$sql.= "ORDER BY student_id ASC , exam2_problem_id ASC ";
			$arr_exam2[0]["student"][$i2]["answer"] = $objDbConnect->query_fetch_arr($sql);
			for($i3=0;$i3<count($arr_exam2[0]["student"][$i2]["answer"]);$i3++){ 
				$arr_exam2[0]["student"][$i2]["answer"][$i3]["exam2_answer_contents_arr"] = explode( ",", $arr_exam2[0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"] );
			}
		}
	}
}
*/
$sql = "SELECT";
$sql.= "  student_id, exam2_answer_review_contents";
$sql.= " FROM";
$sql.= "  exam2_answer_review";
$sql.= " WHERE";
$sql.= "  status = 0";
$sql.= "  AND exam2_id = ".$param["exam2_id"];
$sql.= "  AND product_id = ".$param["product_id"];
$sql.= " ORDER BY";
$sql.= "  create_at DESC";
$arr_exam2_answer_review = $objDbConnect->query_fetch_arr($sql);
foreach($arr_exam2_answer_review as $val){
	$sql = "SELECT";
	$sql.= "  COUNT(exam2_answer_id) AS count";
	$sql.= " FROM";
	$sql.= "  exam2_answer";
	$sql.= " WHERE";
	$sql.= "  status = 0";
	$sql.= "  AND open_review = 1";
	$sql.= "  AND exam2_id = ".$param["exam2_id"];
	$sql.= "  AND product_id = ".$param["product_id"];
	$sql.= "  AND student_id = ".$val['student_id'];
	$arr_exam2_answer = $objDbConnect->query_fetch($sql);
	if($arr_exam2_answer['count']>0){
		$arr_exam2[$val['student_id']] = $val['exam2_answer_review_contents'];
	}
}
$template->assign('arr_exam2', $arr_exam2);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//print("\n<!--[\n");
//var_dump($product_list);
//print("\n]-->\n");
$template->assign('product_list', $product_list);
$template->assign('arr_related_list', $arr_related_list);
$template->assign('arr_recommend_list', $arr_recommend_list);
$template->assign('pcid', $pcid);
$template->assign('pid', $pid);
$template->assign('user_id', $user_id);
$template->assign('THUMBNAIL_PATH', THUMBNAIL_PATH);
$template->assign('CONTENTS_THUMBNAIL_PATH', CONTENTS_THUMBNAIL_PATH);
$template->assign('section_max_related_products', MAX_RELATED_PRODUCTS + 1);
$template->assign('section_max_contents', MAX_CONTENTS + 1);
$template->assign('section_max_contents_download', MAX_CONTENTS_DOWNLOAD + 1);
$template->assign('section_max_product_sub_image', MAX_PRODUCT_SUB_IMAGE + 1);
$template->assign('pankuzu', get_product_pankuzu($objDbConnect, '', $pid));
$template->assign('back_url', $back_url);
$template->assign('view_flg', $view_flg);
$template->assign('buy_flg', $buy_flg);
$template->assign('buy_wait_flg', $buy_wait_flg);
$template->assign('favorite_flg', $favorite_flg);
$template->assign('favorite_icon_flg', $favorite_icon_flg);
$template->assign('download_flg', $download_flg);
$template->assign('arr_player_iframe', $arr_player_iframe);
$template->assign('arr_player_thumbnail', $arr_player_thumbnail);
$template->assign('player_iframe_count', $player_iframe_count);
$template->assign('st_login_check', $st_login_check);
$template->assign('isApple', $isApple);
$template->assign('isAndroid', $isAndroid);
$template->assign('sid', session_id());

$template->assign('bar_association_id', $bar_association_id);
$template->assign('bar_association_branch_id', $bar_association_branch_id);
$template->assign('bar_association', $bar_association);
$template->assign('bar_association_branch', $bar_association_branch);
$template->assign('bar_association_branch_info', $bar_association_branch_info);
$template->assign('info_flg', $info_flg);
$template->assign('capacity_alert_flg', $capacity_alert_flg);
$template->assign('search_url', '/product/detail.php?pid='.$pid.'#search_info_area');

$template->assign('disp_web_flg', $disp_web_flg);
$template->assign('nichibenren_tandoku_flg', $nichibenren_tandoku_flg);
$template->assign('kaijo_moushikomi_flg', $kaijo_moushikomi_flg);

$GLOBALS['meta_description'] = $product_list['memo'];
$GLOBALS['meta_keywords'] = get_product_gategory_meta_keywords($objDbConnect, $pid);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout('product/detail.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();


function _get_bar_association_product($objDbConnect, $target){
	$mtb_bar_association = array('' => '選択してください');
	
	$str_target = '';
	$arr_target = explode('|', trim($target, '|'));
	
	foreach ($arr_target as $val){
		$str_target.= $val.',';
	}
	$str_target = rtrim($str_target, ',');
	
	if( $str_target!="" ){
		$sql = "SELECT id, name FROM mtb_bar_association WHERE id IN ($str_target) ORDER BY `rank` ASC";
		$ret = $objDbConnect->query($sql);
		if($ret){
			while (($row = $objDbConnect->fetch($ret))){
				$mtb_bar_association[$row['id']] = $row['name'];
			}
		}
	}
	return $mtb_bar_association;
}

function _get_bar_association_branch_product($objDbConnect, $bar_association_id){
	$mtb_bar_association_branch = array();
	
	if( $bar_association_id!="" ){
		$sql = "SELECT bar_association_branch_id, bar_association_branch_name FROM mtb_bar_association_branch WHERE bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$bar_association_id)."' ORDER BY `rank` ASC";
		$ret = $objDbConnect->query($sql);
		if($ret){
			while (($row = $objDbConnect->fetch($ret))){
				$mtb_bar_association_branch[$row['bar_association_branch_id']] = $row['bar_association_branch_name'];
			}
		}
	}
	return $mtb_bar_association_branch;
}

function _get_bar_association_branch_info($objDbConnect, $product_id, $bar_association_branch_id){
	$bar_association_branch_info = array();
	if( $bar_association_branch_id!="" ){
		$sql = "SELECT capacity, hall, DATE_FORMAT(receptionist_start_date, '%Y/%m/%d') AS receptionist_start_date, DATE_FORMAT(receptionist_end_date, '%Y/%m/%d') AS receptionist_end_date, contents, web_flg FROM rel_product_bar_association_branch WHERE bar_association_branch_id = '".mysqli_real_escape_string($objDbConnect->connect,$bar_association_branch_id)."' AND product_id = '$product_id'";
		$bar_association_branch_info = $objDbConnect->query_fetch($sql);
	}
	return $bar_association_branch_info;
}
?>