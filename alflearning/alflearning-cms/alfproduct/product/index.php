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
if (!$nichibenren_flg){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}

if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  T1.term_id,";
$sql.= "  T1.name,";
$sql.= "  T2.parent";
$sql.= " FROM";
$sql.= "  wp_terms AS T1";
$sql.= "   JOIN";
$sql.= "  wp_term_taxonomy AS T2";
$sql.= "   ON T1.term_id = T2.term_id";
$sql.= "";
$sql.= " ORDER BY T1.slug ASC";
$ret = $objDbConnect->query_fetch_arr($sql);
$template->assign('arr_cat_list', $ret);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21' ORDER BY wp_terms.slug ASC ";
$arr_category = $objDbConnect->query_fetch_arr($sql);
for($i=0;$i<count($arr_category);$i++){
	$temp = array();
	$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$arr_category[$i]["term_id"]."' ORDER BY wp_terms.slug ASC";
	$temp = $objDbConnect->query_fetch_arr($sql);
	if(0<count($temp)){
		$arr_category[$i]["categorys"] = $temp;
		for($n=0;$n<count($temp);$n++){
			$temp2 = array();
			$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp[$n]["term_id"]."' ORDER BY wp_terms.slug ASC";
			$temp2 = $objDbConnect->query_fetch_arr($sql);
			if(0<count($temp2)){
				$arr_category[$i]["categorys"][$n]["categorys"] = $temp2;
				for($m=0;$m<count($temp2);$m++){
					$temp3 = array();
					$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp2[$m]["term_id"]."' ORDER BY wp_terms.slug ASC";
					$temp3 = $objDbConnect->query_fetch_arr($sql);
					if(0<count($temp3)){
						$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = $temp3;
					} else {
						$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = [];
					}
				}
			} else {
				$arr_category[$i]["categorys"][$n]["categorys"] = [];
			}
		}
		
	} else {
		$arr_category[$i]["categorys"] = [];
	}
}
$template->assign('arr_category', $arr_category);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_product_name = "";
$search_product_code = "";
$search_start_date = "";
$search_end_date = "";
$search_category = array();
$search_open = "0";
$search_teacher = "";
$search_free = "0";
$search_word = "";

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["product.search_product_name"]) && !empty($_SESSION["product.search_product_name"]) ){
		$search_product_name = $_SESSION["product.search_product_name"];
	}
	if( isset($_SESSION["product.search_product_code"]) && !empty($_SESSION["product.search_product_code"]) ){
		$search_product_code = $_SESSION["product.search_product_code"];
	}
	if( isset($_SESSION["product.search_start_date"]) && !empty($_SESSION["product.search_start_date"]) ){
		$search_start_date = $_SESSION["product.search_start_date"];
	}
	if( isset($_SESSION["product.search_end_date"]) && !empty($_SESSION["product.search_end_date"]) ){
		$search_end_date = $_SESSION["product.search_end_date"];
	}
	if( isset($_SESSION["product.search_category"]) && !empty($_SESSION["product.search_category"]) ){
		$search_category = $_SESSION["product.search_category"];
	}
	if( isset($_SESSION["product.search_open"]) && !empty($_SESSION["product.search_open"]) ){
		$search_open = $_SESSION["product.search_open"];
	}
	if( isset($_SESSION["product.search_teacher"]) && !empty($_SESSION["product.search_teacher"]) ){
		$search_teacher = $_SESSION["product.search_teacher"];
	}
	if( isset($_SESSION["product.search_free"]) && !empty($_SESSION["product.search_free"]) ){
		$search_free = $_SESSION["product.search_free"];
	}
	if( isset($_SESSION["product.search_word"]) && !empty($_SESSION["product.search_word"]) ){
		$search_word = $_SESSION["product.search_word"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	csrf_token_verify();
	$search_product_name = isset($_POST["search_product_name"]) ? $_POST["search_product_name"] : "" ;
	$search_product_code = isset($_POST["search_product_code"]) ? $_POST["search_product_code"] : "" ;
	$search_start_date = isset($_POST["search_start_date"]) ? $_POST["search_start_date"] : "" ;
	$search_end_date = isset($_POST["search_end_date"]) ? $_POST["search_end_date"] : "" ;
	$search_category = isset($_POST["search_category"]) && is_array($_POST["search_category"]) ? $_POST["search_category"] : [] ;
	$search_open = isset($_POST["search_open"]) ? $_POST["search_open"] : "0" ;
	$search_teacher = isset($_POST["search_teacher"]) ? $_POST["search_teacher"] : "" ;
	$search_free = isset($_POST["search_free"]) ? $_POST["search_free"] : "0" ;
	$search_word = isset($_POST["search_word"]) ? $_POST["search_word"] : "" ;

	$_SESSION["product.search_product_name"] = $search_product_name;
	$_SESSION["product.search_product_code"] = $search_product_code;
	$_SESSION["product.search_start_date"] = $search_start_date;
	$_SESSION["product.search_end_date"] = $search_end_date;
	$_SESSION["product.search_category"] = $search_category;
	$_SESSION["product.search_open"] = $search_open;
	$_SESSION["product.search_teacher"] = $search_teacher;
	$_SESSION["product.search_free"] = $search_free;
	$_SESSION["product.search_word"] = $search_word;

	$_SESSION["product.page"] = 1;
	$_GET["page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["product.page"]) && !empty($_SESSION["product.page"]) ){
	$page = $_SESSION["product.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["product.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$select_cat = array();
$prev_select_cat = array();
if( 0<count($search_category) ){
	for($i=0;$i<count($search_category);$i++){
		$select_cat[] = $search_category[$i];
	}

	while( count($select_cat)!=count($prev_select_cat) ){
		$cat_where = implode(",",$select_cat);
		$sql = "SELECT term_id,parent FROM wp_term_taxonomy where taxonomy='category' and term_id<>'21' and term_id<>'0' and term_id<>'' and ( term_id in (".$cat_where.") or parent in(".$cat_where.") )";
		$arr_cat = $objDbConnect->query_fetch_arr($sql);
		$prev_select_cat = $select_cat;
		$select_cat = array();
		for($i=0;$i<count($arr_cat);$i++){
			$select_cat[] = $arr_cat[$i]["term_id"];
		}
	}
}
*/
//var_dump( $select_cat );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$sql = "select count(*) as c from tbl_product where del_flg=0 ";

$sql = "select count(tbl_product.product_id) as c from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id where tbl_product.del_flg=0 ";

$where = "";
if( $search_product_name != "" ){
	$where.= " and ( product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_name)."%' ) ";
}
if( $search_product_code != "" ){
	$where.= " and ( product_code like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_code)."%' ) ";
}
if( $search_start_date != "" && $search_end_date != "" ){
	$where.= " and ( ";
		$where.= " ('".$search_start_date."' between  start_date and end_date OR start_date IS NULL) ";
		$where.= " OR ('".$search_end_date."' between  start_date and end_date OR end_date IS NULL) ";
	$where.= " ) ";
} elseif( $search_start_date != "" ){
	$where.= " and (start_date<='".$search_start_date."' OR start_date IS NULL) ";
	$where.= " and (end_date>='".$search_start_date."' OR end_date IS NULL) ";
} elseif( $search_end_date != "" ){
	$where.= " and (start_date<='".$search_end_date."' OR start_date IS NULL) ";
	$where.= " and (end_date>='".$search_end_date."' OR end_date IS NULL) ";
}
$cat_where = "";
if( count($search_category)!=0 ){
	for($i=0;$i<count($search_category);$i++){
		$sql_sub = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent FROM wp_term_taxonomy where taxonomy='category' and parent='".mysqli_real_escape_string($objDbConnect->connect, $search_category[$i])."'";
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
if( $search_open == "1" ){//公開前
	$where.= " and (start_date>'".date("Y:m:d H-i-s")."') ";
}
if( $search_open == "2" ){//公開中
	$where.= " and ( ('".date("Y:m:d H-i-s")."' between  start_date and end_date) OR ('".date("Y:m:d H-i-s")."'>=start_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND '".date("Y:m:d H-i-s")."'>=start_date) OR (start_date IS NULL AND end_date IS NULL) ) ";
}
if( $search_open == "3" ){//終了
	$where.= " and (end_date<'".date("Y:m:d H-i-s")."') ";
}
if( $search_teacher != "" ){
	$where.= " and ( teacher like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' ) ";
}
if( $search_free == "1" ){//有料
	$where.= " and ( price>'0' ) ";
}
if( $search_free == "2" ){//無料
	$where.= " and ( price='0' ) ";
}
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
	$where.= " OR contents_contents11_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents12_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents13_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents14_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents15_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents16_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents17_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents18_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents19_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents20_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents21_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents22_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents23_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents24_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_contents25_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
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
	$where.= " OR contents_memo11 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo12 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo13 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo14 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo15 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo16 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo17 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo18 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo19 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo20 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo21 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo22 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo23 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo24 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_memo25 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
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
	$where.= " OR contents_teacher11 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher12 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher13 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher14 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher15 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher16 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher17 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher18 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher19 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher20 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher21 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher22 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher23 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher24 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= " OR contents_teacher25 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_word)."%'";
	$where.= "  ) ";
}


//echo "[".$sql.$where.$order.$offset."]";
$all_count = 0;
$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY product_id DESC ";
//$sql = "select product_id,product_name,DATE_FORMAT(start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(end_date,'%Y/%m/%d %H:%i') as end_date from tbl_product where del_flg=0 ";

$sql = "select tbl_product.product_id,tbl_product.product_name,DATE_FORMAT(tbl_product.start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(tbl_product.end_date,'%Y/%m/%d %H:%i') as end_date from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id where del_flg=0 ";

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("登録商品一覧");
$template->admin_comment("登録商品を管理します。");

$sidemenu_html = '<ul>';
if ($nichibenren_flg){
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">会場研修*</a></li>
<li class="selected"><a href="./../product/index.php" style="font-size:13px">eラーニング*</a></li>
<li><a href="./../product_ethics/index.php" style="font-size:13px">倫理代替措置研修*</a></li>
';
//<li><a href="./../product_passport/index.php" style="font-size:13px">パスポート*</a></li>
} else {
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">自会主催研修</a></li>
<li><a href="./../product_live_branch/index.php" style="font-size:10px">日弁連主催研修・他会主催研修</a></li>
';
}
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);

//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('page', $page);
$template->assign('search_product_name', $search_product_name);
$template->assign('search_product_code', $search_product_code);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('search_category', $search_category);
$template->assign('search_open', $search_open);
$template->assign('search_teacher', $search_teacher);
$template->assign('search_free', $search_free);
$template->assign('search_word', $search_word);

$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('page_name', 'product');
$template->assign('csrf_token', csrf_token_get());
$template->admin_layout('product/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
