<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_start_buy_date = "";
$search_end_buy_date = "";
$search_monthly = "";
$search_product_name = "";
$search_product_code = "";
$search_product_type_add = array();
$search_payment_type = array();
$search_claim_flg = array();

if( isset($_GET["search"]) && trim($_GET["search"])=="new" ){
} else {
	if( isset($_SESSION["amount_product.search_start_buy_date"]) && !empty($_SESSION["amount_product.search_start_buy_date"]) ){
		$search_start_buy_date = $_SESSION["amount_product.search_start_buy_date"];
	}
	if( isset($_SESSION["amount_product.search_end_buy_date"]) && !empty($_SESSION["amount_product.search_end_buy_date"]) ){
		$search_end_buy_date = $_SESSION["amount_product.search_end_buy_date"];
	}
	if( isset($_SESSION["amount_product.search_monthly"]) && !empty($_SESSION["amount_product.search_monthly"]) ){
		$search_monthly = $_SESSION["amount_product.search_monthly"];
	}
	if( isset($_SESSION["amount_product.search_product_name"]) && !empty($_SESSION["amount_product.search_product_name"]) ){
		$search_product_name = $_SESSION["amount_product.search_product_name"];
	}
	if( isset($_SESSION["amount_product.search_product_code"]) && !empty($_SESSION["amount_product.search_product_code"]) ){
		$search_product_code = $_SESSION["amount_product.search_product_code"];
	}
	if( isset($_SESSION["amount_product.search_product_type_add"]) && !empty($_SESSION["amount_product.search_product_type_add"]) ){
		$search_product_type_add = $_SESSION["amount_product.search_product_type_add"]??[];
	}
	if( isset($_SESSION["amount_product.search_payment_type"]) && !empty($_SESSION["amount_product.search_payment_type"]) ){
		$search_payment_type = $_SESSION["amount_product.search_payment_type"]??[];
		if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
		} else {
			$search_payment_type = array();
		}
	}
	if( isset($_SESSION["amount_product.search_claim_flg"]) && !empty($_SESSION["amount_product.search_claim_flg"]) ){
		$search_claim_flg = $_SESSION["amount_product.search_claim_flg"]??[];
		if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
		} else {
			$search_claim_flg = array();
		}
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_start_buy_date = trim($_POST["search_start_buy_date"]);
	$search_end_buy_date = trim($_POST["search_end_buy_date"]);
	$search_monthly = trim($_POST["search_monthly"]);
	$search_product_name = trim($_POST["search_product_name"]);
	$search_product_code = trim($_POST["search_product_code"]);
	$search_product_type_add = $_POST["search_product_type_add"]??[];
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
		$search_payment_type = $_POST["search_payment_type"]??[];
		$search_claim_flg = $_POST["search_claim_flg"]??[];
	} else {
		$search_payment_type = array();
		$search_claim_flg = array();
	}

	$_SESSION["amount_product.search_start_buy_date"] = $search_start_buy_date;
	$_SESSION["amount_product.search_end_buy_date"] = $search_end_buy_date;
	$_SESSION["amount_product.search_monthly"] = $search_monthly;
	$_SESSION["amount_product.search_product_name"] = $search_product_name;
	$_SESSION["amount_product.search_product_code"] = $search_product_code;
	$_SESSION["amount_product.search_product_type_add"] = $search_product_type_add??[];
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
		$_SESSION["amount_product.search_payment_type"] = $search_payment_type??[];
		$_SESSION["amount_product.search_claim_flg"] = $search_claim_flg??[];
	} else {
		$_SESSION["amount_product.search_payment_type"] = array();
		$_SESSION["amount_product.search_claim_flg"] = array();
	}
	$_SESSION["amount_product.page"] = 1;
	$_GET["page"] = 1;
}

if( isset( $_GET["post_data"]) && $_GET["post_data"] != "" ){
	$temp = unserialize( $_GET["post_data"] );
	if( !is_array($temp) ){
		$temp = unserialize( htmlspecialchars_decode($_GET["post_data"], ENT_QUOTES) );
	}
	$temp_y = trim( $_GET["buy_y"] ?? "" );
	$temp_m = trim( $_GET["buy_m"] ?? "" );
	if(
		is_array($temp)
		&& ctype_digit($temp_y)
		&& ctype_digit($temp_m)
		&& checkdate((int)$temp_m, 1, (int)$temp_y)
	){
		$temp_d = date("t", mktime(0, 0, 0, (int)$temp_m, 1, (int)$temp_y));

		$search_start_buy_date = $temp_y."/".$temp_m."/01 00:00";
		$search_end_buy_date = $temp_y."/".$temp_m."/".$temp_d." 23:00";
		$search_monthly = "";
		$search_product_name = trim($temp["search_product_name"]);
		$search_product_code = trim($temp["search_product_code"]);
		$search_product_type_add = $temp["search_product_type_add"]??[];
		if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
			$search_payment_type = $temp["search_payment_type"]??[];
			$search_claim_flg = $temp["search_claim_flg"]??[];
		} else {
			$search_payment_type = array();
			$search_claim_flg = array();
		}

		$_SESSION["amount_product.search_start_buy_date"] = $search_start_buy_date;
		$_SESSION["amount_product.search_end_buy_date"] = $search_end_buy_date;
		$_SESSION["amount_product.search_monthly"] = "";
		$_SESSION["amount_product.search_product_name"] = $search_product_name;
		$_SESSION["amount_product.search_product_code"] = $search_product_code;
		$_SESSION["amount_product.search_product_type_add"] = $search_product_type_add??[];
		if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
			$_SESSION["amount_product.search_payment_type"] = $search_payment_type??[];
			$_SESSION["amount_product.search_claim_flg"] = $search_claim_flg??[];
		} else {
			$_SESSION["amount_product.search_payment_type"] = array();
			$_SESSION["amount_product.search_claim_flg"] = array();
		}
		$_SESSION["amount_product.page"] = 1;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
	$search_start_buy_date = "";
	$search_end_buy_date = "";
	$search_monthly = "";
	$search_product_name = "";
	$search_product_code = "";
	$search_product_type_add = array();
	$search_payment_type = array();
	$search_claim_flg = array();

	$_SESSION["amount_product.search_start_buy_date"] = $search_start_buy_date;
	$_SESSION["amount_product.search_end_buy_date"] = $search_end_buy_date;
	$_SESSION["amount_product.search_monthly"] = $search_monthly;
	$_SESSION["amount_product.search_product_name"] = $search_product_name;
	$_SESSION["amount_product.search_product_code"] = $search_product_code;
	$_SESSION["amount_product.search_product_type_add"] = $search_product_type_add??[];
	$_SESSION["amount_product.search_payment_type"] = $search_payment_type??[];
	$_SESSION["amount_product.search_claim_flg"] = $search_claim_flg??[];
	$_SESSION["amount_product.page"] = 1;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$page = 1;
if( isset($_SESSION["amount_product.page"]) && !empty($_SESSION["amount_product.page"]) ){
	$page = $_SESSION["amount_product.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_product.page"] = $page;
}
$objAdminPager->setNowPage( $page );
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$arr_product_type_add = array(
				array("id"=>"1",	"name"=>"e-ラーニング"),
				array("id"=>"2",	"name"=>"elライブ"),
				array("id"=>"3",	"name"=>"ライブ実務研修"),
				array("id"=>"4",	"name"=>"倫理研修"),
				array("id"=>"5",	"name"=>"パスポート"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//payment_type 支払い方法(1:カード 12:銀行振込)
$arr_payment_type = array(
				array("id"=>"1",	"name"=>"カード"),
				array("id"=>"12",	"name"=>"銀行振込"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//claim_flg 請求書送付フラグ(0:しない 1:する)
$arr_claim_flg = array(
				array("id"=>"1",	"name"=>"紙"),
				array("id"=>"0",	"name"=>"メール"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$all_pay_total = 0;
$all_buy_count = 0;
if( $search_monthly=="" ){
	//----------------------------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " tbl_product.product_id, ";
	$sql.= " tbl_product.product_name, ";
	$sql.= " tbl_product.product_code, ";
	$sql.= " tbl_product_add.product_type_add, ";
	$sql.= " tbl_product_elearning.product_kind_flg, ";
	$sql.= " tbl_product_live_training.training_kind_flg, ";
	$sql.= " tbl_order_detail.pay_total, ";
	$sql.= " tbl_order_detail.payment_status, ";
	$sql.= " COUNT( tbl_order_detail.product_id ) AS buy_count, ";
	$sql.= " SUM( tbl_order_detail.pay_total ) AS all_pay_total ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id ";
	$sql.= " LEFT JOIN student ON student.student_id=tbl_order_detail.member_id ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
	$sql.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.payment_status='2' ";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//----------------------------------------------------------
	if( $search_start_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date>='".$search_start_buy_date."' ";
	}
	//----------------------------------------------------------
	if( $search_end_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date<='".substr($search_end_buy_date,0,14)."59:59' ";
	}
	//----------------------------------------------------------
	if( $search_product_name != "" ){
		$where.= " and tbl_product.product_name LIKE '%".trim($search_product_name)."%' ";
	}
	//----------------------------------------------------------
	if( $search_product_code != "" ){
		$where.= " and tbl_product.product_code LIKE '%".trim($search_product_code)."%' ";
	}
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_product_type_add) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_product_type_add);$i++){
			if($i>0){ $temp_where.= " or "; }
			// 1e-ラーニング
			// 2elライブ
			// 3ライブ実務研修
			// 4倫理研修
			// 5パスポート
			// tbl_product_add.product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
			// tbl_product_elearning.product_kind_flg 商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
			// tbl_product_live_training.training_kind_flg 研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
			if($search_product_type_add[$i]=="1"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg<>'2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="2"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg='2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="3"){
				//$temp_where.= " ( ";
				//$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg<>'1' ";
				//$temp_where.= " ) ";
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg <> '1' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="4"){
				//$temp_where.= " ( ";
				//$temp_where.= " (tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg='1') ";
				//$temp_where.= " OR tbl_product_add.product_type_add = '3' ";
				//$temp_where.= " ) ";
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '3' OR ( tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg = '1' ) ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="5"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '4' ";
				$temp_where.= " ) ";
			}
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_payment_type) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_payment_type);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.payment_type = '".$search_payment_type[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_claim_flg) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_claim_flg);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.claim_flg = '".$search_claim_flg[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$group = "";
	$group.= " GROUP BY ";
	$group.= " tbl_product.product_id, ";
	$group.= " tbl_product.product_name, ";
	$group.= " tbl_product.product_code, ";
	$group.= " tbl_product_add.product_type_add, ";
	$group.= " tbl_order_detail.pay_total, ";
	$group.= " tbl_order_detail.payment_status ";
	//----------------------------------------------------------
	$order = " ORDER BY COUNT( tbl_order_detail.product_id ) DESC,SUM( tbl_order_detail.pay_total ) DESC ";
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	//var_dump($where);
	//var_dump($where);
	for($i=0;$i<count($ret);$i++){
		$all_pay_total += $ret[$i]["all_pay_total"];
		$all_buy_count += $ret[$i]["buy_count"];
	}
} else {
	//----------------------------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " DATE_FORMAT(tbl_order_detail.create_date,'%Y') as buy_y, ";
	$sql.= " DATE_FORMAT(tbl_order_detail.create_date,'%m') as buy_m, ";
	$sql.= " COUNT( tbl_order_detail.product_id ) AS buy_count, ";
	$sql.= " SUM( tbl_order_detail.pay_total ) AS all_pay_total ";
	//$sql.= " tbl_order_detail.product_id, ";
	//$sql.= " tbl_order_detail.pay_total ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id ";
	$sql.= " LEFT JOIN student ON student.student_id=tbl_order_detail.member_id ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";

	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.payment_status='2' ";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//----------------------------------------------------------
	if( $search_start_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date>='".$search_start_buy_date."' ";
	}
	//----------------------------------------------------------
	if( $search_end_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date<='".substr($search_end_buy_date,0,14)."59:59' ";
	}
	//----------------------------------------------------------
	if( $search_product_name != "" ){
		$where.= " and tbl_product.product_name LIKE '%".trim($search_product_name)."%' ";
	}
	//----------------------------------------------------------
	if( $search_product_code != "" ){
		$where.= " and tbl_product.product_code LIKE '%".trim($search_product_code)."%' ";
	}
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_product_type_add) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_product_type_add);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '".$search_product_type_add[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_payment_type) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_payment_type);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.payment_type = '".$search_payment_type[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( 0<count($search_claim_flg) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_claim_flg);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.claim_flg = '".$search_claim_flg[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$group = "";
	$group.= " GROUP BY ";
	$group.= " DATE_FORMAT(tbl_order_detail.create_date,'%Y'), ";
	$group.= " DATE_FORMAT(tbl_order_detail.create_date,'%m') ";
	//----------------------------------------------------------
	$order = " ORDER BY DATE_FORMAT(tbl_order_detail.create_date,'%Y') DESC,DATE_FORMAT(tbl_order_detail.create_date,'%m') DESC ";
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	//var_dump($sql.$where.$group.$order);
	//var_dump($ret);
	for($i=0;$i<count($ret);$i++){
		$all_pay_total += $ret[$i]["all_pay_total"];
		//$all_pay_total += $ret[$i]["pay_total"];
		$all_buy_count += $ret[$i]["buy_count"];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("売上分析");
$template->admin_comment("売上を集計します。");
$template->admin_school($arr_session["cms_master.login.school_name"]);

if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sidemenu_html ='<ul>
<li><a href="./../amount_order/index.php" style="font-size:13px;">受注履歴</a></li>
<li><a href="./../amount_user/index.php" style="font-size:13px;">会員別売上集計</a></li>
<li class="selected"><a href="./../amount_product/index.php" style="font-size:13px;">売り上げ分析*</a></li>
</ul>';
//<li><a href="./../amount_passport/index.php" style="font-size:13px;">パスポート注文履歴</a></li>
//<li><a href="./../bank_upload/index.php" style="font-size:13px;">銀行振込取込*</a></li>
$template->admin_sidemenu($sidemenu_html);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('arr_product_type_add', $arr_product_type_add);
$template->assign('arr_payment_type', $arr_payment_type);
$template->assign('arr_claim_flg', $arr_claim_flg);

$template->assign('search_start_buy_date', $search_start_buy_date);
$template->assign('search_end_buy_date', $search_end_buy_date);
$template->assign('search_monthly', $search_monthly);
$template->assign('search_product_name', $search_product_name);
$template->assign('search_product_code', $search_product_code);
$template->assign('search_product_type_add', $search_product_type_add);
$template->assign('search_payment_type', $search_payment_type);
$template->assign('search_claim_flg', $search_claim_flg);

$template->assign('arr_list', $ret);
$template->assign('all_count', count($ret) );
$template->assign('all_pay_total', $all_pay_total);
$template->assign('all_buy_count', $all_buy_count);

$template->assign('bar_association_id', $arr_session["cms_master.login.bar_association_id"]);
$post_data = array(
	"search_start_buy_date" => $search_start_buy_date,
	"search_end_buy_date" => $search_end_buy_date,
	"search_monthly" => $search_monthly,
	"search_product_name" => $search_product_name,
	"search_product_code" => $search_product_code,
	"search_product_type_add" => $search_product_type_add,
	"search_payment_type" => $search_payment_type,
	"search_claim_flg" => $search_claim_flg,
);
$template->assign('post_data', serialize($post_data));

$template->assign('page_name', 'amount_product');
$template->admin_layout('amount_product/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
