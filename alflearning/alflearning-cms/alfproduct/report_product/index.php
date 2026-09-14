<?php
//ini_set( 'display_errors', 1 ); 
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

if ( empty($_POST) && empty($_GET) ){
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

//print "<br>1<br>".$sql;

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
//product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$arr_product_type_add = array(
				array("id"=>"1",	"name"=>"e-ラーニング"),
				array("id"=>"2",	"name"=>"ライブ実務研修"),
				array("id"=>"3",	"name"=>"倫理研修"),
				array("id"=>"4",	"name"=>"夏期研修"),
				array("id"=>"5",	"name"=>"新規登録弁護士研修"),
				array("id"=>"6",	"name"=>"e-ライブ"),
			);
$template->assign('arr_product_type_add', $arr_product_type_add);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT id, name, `rank` FROM mtb_bar_association ORDER BY `rank` ASC ";

//print "<br>6<br>".$sql;
$arr_bar_association = $objDbConnect->query_fetch_arr($sql);
$template->assign('arr_bar_association', $arr_bar_association);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
$search_word = "";
$search_word = "";

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["report_product.search_product_name"]) && !empty($_SESSION["report_product.search_product_name"]) ){
		$search_product_name = $_SESSION["report_product.search_product_name"];
	}
	if( isset($_SESSION["report_product.search_product_code"]) && !empty($_SESSION["report_product.search_product_code"]) ){
		$search_product_code = $_SESSION["report_product.search_product_code"];
	}
	if( isset($_SESSION["report_product.search_product_type_add"]) && !empty($_SESSION["report_product.search_product_type_add"]) ){
		$search_product_type_add = $_SESSION["report_product.search_product_type_add"] ?? [];
	}
	if( isset($_SESSION["report_product.search_association"]) && !empty($_SESSION["report_product.search_association"]) ){
		$search_association = $_SESSION["report_product.search_association"];
	}
	if( isset($_SESSION["report_product.search_start_date"]) && !empty($_SESSION["product.search_start_date"]) ){
		$search_start_date = $_SESSION["report_product.search_start_date"];
	}
	if( isset($_SESSION["report_product.search_end_date"]) && !empty($_SESSION["report_product.search_end_date"]) ){
		$search_end_date = $_SESSION["report_product.search_end_date"];
	}
	if( isset($_SESSION["report_product.search_category"]) && !empty($_SESSION["report_product.search_category"]) ){
		$search_category = $_SESSION["report_product.search_category"];
	}
	if( isset($_SESSION["report_product.search_open"]) && !empty($_SESSION["report_product.search_open"]) ){
		$search_open = $_SESSION["report_product.search_open"];
	}
	if( isset($_SESSION["report_product.search_teacher"]) && !empty($_SESSION["report_product.search_teacher"]) ){
		$search_teacher = $_SESSION["report_product.search_teacher"];
	}
	if( isset($_SESSION["report_product.search_free"]) && !empty($_SESSION["report_product.search_free"]) ){
		$search_free = $_SESSION["report_product.search_free"];
	}
	if( isset($_SESSION["report_product.search_word"]) && !empty($_SESSION["report_product.search_word"]) ){
		$search_word = $_SESSION["report_product.search_word"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_product_name = trim($_POST["search_product_name"]);
	$search_product_code = trim($_POST["search_product_code"]);
	$search_product_type_add = $_POST["search_product_type_add"] ?? [];
	$search_association = trim($_POST["search_association"]);
	$search_start_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_start_date"]));
	$search_end_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_end_date"]));
	$search_category = array_filter(($_POST["search_category"]??[]), 'strlen');
	$search_open = trim($_POST["search_open"]);if($search_open == ""){$search_open = "0";}
	$search_teacher = trim($_POST["search_teacher"]);
	$search_free = trim($_POST["search_free"]);if($search_free == ""){$search_free = "0";}
	$search_word = trim($_POST["search_word"]);

	$_SESSION["report_product.search_product_name"] = $search_product_name;
	$_SESSION["report_product.search_product_code"] = $search_product_code;
	$_SESSION["report_product.search_product_type_add"] = $search_product_type_add ?? [];
	$_SESSION["report_product.search_association"] = $search_association;
	$_SESSION["report_product.search_start_date"] = $search_start_date;
	$_SESSION["report_product.search_end_date"] = $search_end_date;
	$_SESSION["report_product.search_category"] = $search_category;
	$_SESSION["report_product.search_open"] = $search_open;
	$_SESSION["report_product.search_teacher"] = $search_teacher;
	$_SESSION["report_product.search_free"] = $search_free;
	$_SESSION["report_product.search_word"] = $search_word;

	$_SESSION["report_product.page"] = 1;
	$_GET["page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["report_product.page"]) && !empty($_SESSION["report_product.page"]) ){
	$page = $_SESSION["report_product.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["report_product.page"] = $page;
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

$sql = "";
$sql.= "SELECT ";
//$sql.= " count(tbl_product.product_id,rel_product_bar_association_branch.bar_association_branch_id) as c ";
$sql.= " tbl_product.product_id, ";
$sql.= " tbl_product_add.product_type_add, ";
$sql.= " tbl_product_live_training.training_kind_flg, ";
$sql.= " tbl_product_live_training.ethic_flg, ";
//$sql.= " rel_product_bar_association.bar_association_id, ";
$sql.= " rel_product_bar_association_branch.bar_association_branch_id, ";
$sql.= " rel_product_bar_association_branch.bar_association_branch_id as rel_product_bar_association_branch_bar_association_branch_id ";
//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count ";
// FROM 句は件数用（COUNT(*)）と一覧用で共有するため変数へ切り出す（F-024 A-1）
$from = "";
$from.= "FROM ";
$from.= " tbl_product ";
//$from.= " INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id ";
//$from.= " INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id ";
$from.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
$from.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
$from.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
$from.= " LEFT JOIN rel_product_bar_association_branch ON tbl_product.product_id=rel_product_bar_association_branch.product_id ";
// 結合条件に計算式（KENSHU_ID + 10000）を書くと索引が使えないため、
// import_kenshu_count 側の生成列 product_id_join（= CAST(KENSHU_ID AS UNSIGNED) + 10000）と突き合わせる（F-024 A-3）
$from.= " LEFT JOIN import_kenshu_count ON tbl_product.product_id=import_kenshu_count.product_id_join ";
$from.= " LEFT JOIN mtb_bar_association_branch ON rel_product_bar_association_branch.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id AND tbl_product_add.product_type_add<>3 ";
$from.= " LEFT JOIN mtb_bar_association ON mtb_bar_association_branch.bar_association_id=mtb_bar_association.id ";
$sql.= $from;
// 件数取得は結合後の行数を DB 側で数える（従来は LIMIT なしで全件フェッチして PHP の count()）
$sql_count = "SELECT COUNT(*) AS c " . $from;
$where = "";
$where.= "WHERE ";
$where.= " tbl_product.del_flg=0 ";
$where.= " AND ( ( import_kenshu_count.ROOT_ID<>0 AND tbl_product_live_training.ethic_flg=0 ) OR tbl_product_live_training.ethic_flg=1 OR import_kenshu_count.ROOT_ID IS NULL ) ";
$where.= " AND ( rel_product_bar_association_branch.web_flg<>2 OR rel_product_bar_association_branch.web_flg IS NULL ) ";
//----------------------------------------------------------
if (!$nichibenren_flg){
	$where.= " and ( tbl_product_live_training.sponsor IS NULL OR tbl_product_live_training.sponsor = '' OR tbl_product_live_training.sponsor like '%|$login_bar_association_id|%' ) ";
}
//----------------------------------------------------------
if( $search_product_name != "" ){
	$where.= " and ( tbl_product.product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_name)."%' ) ";
}
//----------------------------------------------------------
if( $search_product_code != "" ){
	$where.= " and ( tbl_product.product_code like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_code)."%' ) ";
}
//----------------------------------------------------------
$temp_where = "";
if( 0<count($search_product_type_add) ){
	$temp_where.= " and (";
	for($i=0;$i<count($search_product_type_add);$i++){
		if($i>0){ $temp_where.= " or "; }
		// 1e-ラーニング
		// 2ライブ実務研修
		// 3倫理研修
		// 4夏期研修
		// 5新規登録弁護士研修
		// 6elライブ
		// tbl_product_add.product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
		// tbl_product_elearning.product_kind_flg 商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
		// tbl_product_live_training.training_kind_flg 研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
		if($search_product_type_add[$i]=="1"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg<>'2' ";
			$temp_where.= " ) ";
		} elseif($search_product_type_add[$i]=="2"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='1' ";
			$temp_where.= " ) ";
		} elseif($search_product_type_add[$i]=="3"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '3' OR ( tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='3' ) ";
			$temp_where.= " ) ";
		} elseif($search_product_type_add[$i]=="4"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='2' ";
			$temp_where.= " ) ";
		} elseif($search_product_type_add[$i]=="5"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.training_kind_flg='4' ";
			$temp_where.= " ) ";
		} elseif($search_product_type_add[$i]=="6"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg='2' ";
			$temp_where.= " ) ";
		}

	}
	$temp_where.= " ) ";
}
$where.= $temp_where;
//----------------------------------------------------------
if( $search_association != "" ){
	$where.= " and mtb_bar_association.id = '".mysqli_real_escape_string($objDbConnect->connect,$search_association)."' ";
}
//----------------------------------------------------------
if( $search_start_date != "" && $search_end_date != "" ){
	$where.= " and ( ";
		$where.= " ('".$search_start_date."' between  tbl_product.start_date and end_date OR tbl_product.start_date IS NULL) ";
		$where.= " OR ('".$search_end_date."' between  tbl_product.start_date and tbl_product.end_date OR end_date IS NULL) ";
	$where.= " ) ";
} elseif( $search_start_date != "" ){
	$where.= " and (tbl_product.start_date<='".$search_start_date."' OR tbl_product.start_date IS NULL) ";
	$where.= " and (tbl_product.end_date>='".$search_start_date."' OR tbl_product.end_date IS NULL) ";
} elseif( $search_end_date != "" ){
	$where.= " and (tbl_product.start_date<='".$search_end_date."' OR tbl_product.start_date IS NULL) ";
	$where.= " and (tbl_product.end_date>='".$search_end_date."' OR tbl_product.end_date IS NULL) ";
}
//----------------------------------------------------------
$cat_where = "";
if( count($search_category)!=0 ){
	for($i=0;$i<count($search_category);$i++){
		$sql_sub = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent FROM wp_term_taxonomy where taxonomy='category' and parent='".$search_category[$i]."'";
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
//----------------------------------------------------------
if( $search_open == "1" ){//公開前
	$where.= " and (start_date>'".date("Y:m:d H-i-s")."') ";
}
if( $search_open == "2" ){//公開中
	$where.= " and ( ('".date("Y:m:d H-i-s")."' between  start_date and end_date) OR ('".date("Y:m:d H-i-s")."'>=start_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND end_date IS NULL) OR ('".date("Y:m:d H-i-s")."'<=end_date AND '".date("Y:m:d H-i-s")."'>=start_date) OR (start_date IS NULL AND end_date IS NULL) ) ";
}
if( $search_open == "3" ){//終了
	$where.= " and (end_date<'".date("Y:m:d H-i-s")."') ";
}
//----------------------------------------------------------
if( $search_teacher != "" ){
	$where.= " and ( teacher like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' ) ";
}
//----------------------------------------------------------
if( $search_free == "1" ){//有料
	$where.= " and ( price>'0' ) ";
}
if( $search_free == "2" ){//無料
	$where.= " and ( price='0' ) ";
}
//----------------------------------------------------------
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
	$where.= "  ) ";
}
//----------------------------------------------------------
//旧データは対象外
$where.= "  
	 /*AND tbl_product.product_id>19233*/
	 AND (
		(
			tbl_product_add.product_type_add=2
			AND tbl_product_live_training.training_kind_flg=1
			AND EXISTS (
				SELECT 
					1
				FROM 
					tbl_order_detail 
				WHERE 
					( 
						tbl_order_detail.payment_status = 1 
						OR tbl_order_detail.payment_status = 2 
						OR tbl_order_detail.payment_status = 3 
					) 
					AND tbl_order_detail.product_type_add=2 
					AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch.bar_association_branch_id 
					AND tbl_order_detail.product_id=tbl_product.product_id 
			)
		)
		OR (
			tbl_product_add.product_type_add<>2
			OR tbl_product_live_training.training_kind_flg<>1
		)
	)
 ";
//----------------------------------------------------------
$all_count = 0;
//print "<br>8<br>".$sql.$where;
$order = " ORDER BY tbl_product.product_id DESC ";

if ($disp_flg){
	$ret_count = $objDbConnect->query_fetch($sql_count.$where);
	$all_count = isset($ret_count["c"]) ? (int)$ret_count["c"] : 0;

	$objAdminPager->setListMax($all_count);
	$objAdminPager->setPagerUrl("?page=");
	$pager = $objAdminPager->getPager();
	$offset = $objAdminPager->getOffset();
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
	$sql.= " mtb_bar_association_branch.bar_association_branch_id AS bar_association_branch_id, ";
	$sql.= " mtb_bar_association_branch.bar_association_branch_name AS bar_association_branch_name, ";
	$sql.= " mtb_bar_association.name AS bar_association_name, ";
	$sql.= " rel_product_bar_association_branch.bar_association_branch_id as rel_product_bar_association_branch_bar_association_branch_id, ";
	/*
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add1_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.video_complete_flg=1 ) AS product_type_add1_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 ) AS product_type_add2_end_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.product_id=tbl_product.product_id AND tbl_order_detail.participation_flg=1 ) AS product_type_add2_end_count_kako, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE tbl_ethic_question_history.product_id=tbl_product.product_id  ) AS product_type_add3_count, ";
	$sql.= " (  SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE tbl_ethic_question_history.product_id=tbl_product.product_id AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) ) AS product_type_add3_end_count ";
	*/
	$sql.= " 0 AS product_type_add1_count, ";
	$sql.= " 0 AS product_type_add1_end_count, ";
	$sql.= " 0 AS product_type_add2_count, ";
	//$sql.= " (  SELECT COUNT(*) AS c FROM tbl_order_detail WHERE ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) AND tbl_order_detail.product_type_add=2 AND tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch_bar_association_branch_id AND tbl_order_detail.product_id=tbl_product.product_id ) AS product_type_add2_count, ";
	$sql.= " 0 AS product_type_add2_end_count, ";
	$sql.= " 0 AS product_type_add2_count_kako, ";
	$sql.= " 0 AS product_type_add2_end_count_kako, ";
	$sql.= " 0 AS product_type_add3_count, ";
	$sql.= " 0 AS product_type_add3_end_count ";

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
	$sql.= " LEFT JOIN rel_product_bar_association_branch ON tbl_product.product_id=rel_product_bar_association_branch.product_id AND tbl_product_add.product_type_add<>3 ";
	$sql.= " LEFT JOIN import_kenshu_count ON tbl_product.product_id=import_kenshu_count.product_id_join ";
	$sql.= " LEFT JOIN mtb_bar_association_branch ON rel_product_bar_association_branch.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id ";
	$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association_branch.bar_association_id=mtb_bar_association.id ";

	//print $sql.$where.$order.$offset;
	//exit();

	//print "<br>9<br>".$sql.$where.$order.$offset;
	//$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);

	$pagemax = 20;
	$offset = " LIMIT ".$pagemax." OFFSET ".(($page - 1) * $pagemax)." ";

	$arr_disp_list = [];
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//print("\n<!--\n");
//print("\n[SQL]\n");
//var_dump($sql.$where.$order.$offset);
//print("\n\n");
//print("\n-->\n");


	// 【】各レコード数の再計算 --------------------------------------------------------------
	// 表示対象（最大 20 件）の集計を product_id IN (...) でまとめて取得する（F-024 A-4）。
	// 従来は 1 行ごとに COUNT(*) を発行しており、1 画面で最大 300 本弱のクエリが出ていた。
	// 絞り込み条件は [NBR-239] 適用後のものをそのまま踏襲しており、集計値は変わらない。
	$arr_pid = array();
	$arr_pid_old = array();			// 旧システム商品（pid<=19233）: 実施会で絞らない
	$arr_pid_new = array();			// pid>19233: 実施会で絞る
	$arr_pid_elearning = array();
	for($i=0;$i<count($ret);$i++){
		$pid_pre = (int)$ret[$i]["product_id"];
		$arr_pid[] = $pid_pre;
		if ($pid_pre > 19233){ $arr_pid_new[] = $pid_pre; } else { $arr_pid_old[] = $pid_pre; }
		if ($ret[$i]["product_type_add"] == 1){ $arr_pid_elearning[] = $pid_pre; }
	}
	$arr_pid           = array_values(array_unique($arr_pid));
	$arr_pid_old       = array_values(array_unique($arr_pid_old));
	$arr_pid_new       = array_values(array_unique($arr_pid_new));
	$arr_pid_elearning = array_values(array_unique($arr_pid_elearning));

	// [NBR-239] 非日弁連ログイン時の弁護士会絞り込み（ページ内で一定なので条件文字列として使い回す）
	$where_assoc_od = "";
	$where_assoc_st = "";
	if (!$nichibenren_flg){
		$where_assoc_od = " AND tbl_order_detail.bar_association_id='".$login_bar_association_id."' ";
		$where_assoc_st = " AND student.bar_association_id='".$login_bar_association_id."' ";
	}

	$fn_agg_map = function($pid_list, $sql_prefix, $sql_suffix, $with_branch) use ($objDbConnect) {
		$map = array();
		if ( empty($pid_list) ){ return $map; }
		$sql_agg = $sql_prefix.implode(",", $pid_list).$sql_suffix;
		foreach ( (array)$objDbConnect->query_fetch_arr($sql_agg) as $row ){
			$key = $with_branch
				? ((string)$row["product_id"]."-".(string)$row["bar_association_branch_id"])
				: (string)$row["product_id"];
			$map[$key] = $row["c"];
		}
		return $map;
	};

	$od_group  = ") GROUP BY tbl_order_detail.product_id ";
	$od_group2 = ") GROUP BY tbl_order_detail.product_id, tbl_order_detail.bar_association_branch_id ";
	$od_head   = "SELECT tbl_order_detail.product_id, COUNT(*) AS c FROM tbl_order_detail WHERE ";
	$od_head2  = "SELECT tbl_order_detail.product_id, tbl_order_detail.bar_association_branch_id, COUNT(*) AS c FROM tbl_order_detail WHERE ";
	$pay12     = " ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) AND tbl_order_detail.product_type_add=2 ";

	// e ラーニング
	$agg_add1 = $fn_agg_map($arr_pid,
		$od_head." tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.product_id IN (", $od_group, false);
	$agg_add1_end = $fn_agg_map($arr_pid,
		$od_head." tbl_order_detail.payment_status=2 AND tbl_order_detail.product_type_add=1 AND tbl_order_detail.video_complete_flg=1 AND tbl_order_detail.product_id IN (", $od_group, false);

	// ライブ実務研修（旧システム商品：実施会で絞らない）
	$agg_add2_old = $fn_agg_map($arr_pid_old,
		$od_head.$pay12.$where_assoc_od." AND tbl_order_detail.product_id IN (", $od_group, false);
	$agg_add2_end_old = $fn_agg_map($arr_pid_old,
		$od_head.$pay12." AND tbl_order_detail.participation_flg=1 ".$where_assoc_od." AND tbl_order_detail.product_id IN (", $od_group, false);

	// ライブ実務研修（pid>19233：実施会別）。
	// 実施会 ID が NULL の行は除外する（従来は一覧行の実施会 ID が空だとクエリが成立せず件数 0 だったため）
	$agg_add2_new = $fn_agg_map($arr_pid_new,
		$od_head2.$pay12." AND tbl_order_detail.bar_association_branch_id IS NOT NULL ".$where_assoc_od." AND tbl_order_detail.product_id IN (", $od_group2, true);
	$agg_add2_end_new = $fn_agg_map($arr_pid_new,
		$od_head2.$pay12." AND tbl_order_detail.participation_flg=1 AND tbl_order_detail.bar_association_branch_id IS NOT NULL ".$where_assoc_od." AND tbl_order_detail.product_id IN (", $od_group2, true);

	// ライブ実務研修（実施会不問の全期間合計。[NBR-239] でも弁護士会絞り込みは掛けていない）
	$agg_add2_kako = $fn_agg_map($arr_pid,
		$od_head.$pay12." AND tbl_order_detail.product_id IN (", $od_group, false);
	$agg_add2_end_kako = $fn_agg_map($arr_pid,
		$od_head.$pay12." AND tbl_order_detail.participation_flg=1 AND tbl_order_detail.product_id IN (", $od_group, false);

	// 倫理研修
	$eth_head = "SELECT tbl_ethic_question_history.product_id, COUNT(*) AS c FROM tbl_ethic_question_history INNER JOIN student ON tbl_ethic_question_history.student_id = student.student_id WHERE 1=1 ";
	$agg_add3 = $fn_agg_map($arr_pid,
		$eth_head.$where_assoc_st." AND tbl_ethic_question_history.product_id IN (",
		") GROUP BY tbl_ethic_question_history.product_id ", false);
	$agg_add3_end = $fn_agg_map($arr_pid,
		$eth_head.$where_assoc_st." AND (tbl_ethic_question_history.status=2 OR tbl_ethic_question_history.status=5 OR tbl_ethic_question_history.status=7) AND tbl_ethic_question_history.product_id IN (",
		") GROUP BY tbl_ethic_question_history.product_id ", false);

	// e ラーニングの動画 ID（contents_contents1〜25）
	$map_contents = array();
	if ( !empty($arr_pid_elearning) ){
		$sql_agg = "SELECT product_id, ";
		for($n=1;$n<=25;$n++){ $sql_agg.= " contents_contents".$n.", "; }
		$sql_agg.= " 1 AS dummy FROM tbl_product WHERE product_id IN (".implode(",", $arr_pid_elearning).") ";
		foreach ( (array)$objDbConnect->query_fetch_arr($sql_agg) as $row ){
			$map_contents[(string)$row["product_id"]] = $row;
		}
	}

	// 【】重複会員を除外した e ラーニング受講者数・受講完了者数（F-024 A-4）。
	// 従来は 1 行あたり最大 4 本（重複会員の抽出＋除外後の再カウント）を発行していた。
	// 除外する会員は商品ごとに異なるため、商品単位の条件を OR で並べて 1 本にまとめている。
	$fn_adjust_duplicate = function($pid_list, $filter_where) use ($objDbConnect) {
		$adjusted = array();
		if ( empty($pid_list) ){ return $adjusted; }
		$in_list = implode(",", $pid_list);

		// 同一商品で (product_id, member_id) が重複している会員を商品ごとに拾う
		$sql_dup = "";
		$sql_dup.= "SELECT tbl_order_detail.product_id, tbl_order_detail.member_id ";
		$sql_dup.= "  FROM tbl_order_detail ";
		$sql_dup.= " WHERE tbl_order_detail.product_id IN (".$in_list.") ";
		$sql_dup.= "   AND (tbl_order_detail.product_id, tbl_order_detail.member_id) ";
		$sql_dup.= "        IN ( ";
		$sql_dup.= "          SELECT A.product_id, A.member_id ";
		$sql_dup.= "            FROM tbl_order_detail AS A ";
		$sql_dup.= "           WHERE A.product_id IN (".$in_list.") ";
		$sql_dup.= "           GROUP BY A.product_id, A.member_id ";
		$sql_dup.= "          HAVING COUNT(*) > 1 ";
		$sql_dup.= "       )";
		$sql_dup.= $filter_where;

		$arr_dup = array();
		foreach ( (array)$objDbConnect->query_fetch_arr($sql_dup) as $row ){
			$arr_dup[(string)$row["product_id"]][] = $row["member_id"];
		}
		if ( empty($arr_dup) ){ return $adjusted; }

		$arr_cond = array();
		foreach ( $arr_dup as $dup_pid => $arr_member ){
			$duplicate_member_id = "-1";
			for($m=0;$m<count($arr_member);$m++){
				$duplicate_member_id .= ','.$arr_member[$m];
			}
			$arr_cond[] = " ( tbl_order_detail.product_id ='".$dup_pid."' AND NOT (tbl_order.member_id IN (".$duplicate_member_id.") AND tbl_order.web_flg = 1) ) ";
			// 該当商品は必ず再カウント結果で上書きする（一致行が無い場合は 0 件）
			$adjusted[(string)$dup_pid] = 0;
		}

		$sql_cnt = "";
		$sql_cnt.= "SELECT tbl_order_detail.product_id, COUNT(*) AS r_count ";
		$sql_cnt.= " FROM tbl_order_detail INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id ";
		$sql_cnt.= "WHERE 1=1 ";
		$sql_cnt.= "  AND ( ".implode(" OR ", $arr_cond)." ) ";
		$sql_cnt.= $filter_where;
		$sql_cnt.= " GROUP BY tbl_order_detail.product_id ";
		foreach ( (array)$objDbConnect->query_fetch_arr($sql_cnt) as $row ){
			$adjusted[(string)$row["product_id"]] = $row["r_count"];
		}
		return $adjusted;
	};

	// 従来は「集計値が 0 以外の行」だけ重複会員判定に入っていたため、対象商品を同じ条件で絞る
	$pid_dup1     = array();
	$pid_dup1_end = array();
	for($i=0;$i<count($ret);$i++){
		$pid_key_pre = (string)$ret[$i]["product_id"];
		if ( !empty($agg_add1[$pid_key_pre]) ){     $pid_dup1[]     = (int)$ret[$i]["product_id"]; }
		if ( !empty($agg_add1_end[$pid_key_pre]) ){ $pid_dup1_end[] = (int)$ret[$i]["product_id"]; }
	}
	$where_dup1 = "";
	$where_dup1.= " AND tbl_order_detail.payment_status   = 2 ";
	$where_dup1.= " AND tbl_order_detail.product_type_add = 1 ";
	$where_dup1_end = $where_dup1;
	$where_dup1_end.= " AND tbl_order_detail.video_complete_flg = 1 ";

	$adj_add1     = $fn_adjust_duplicate(array_values(array_unique($pid_dup1)),     $where_dup1);
	$adj_add1_end = $fn_adjust_duplicate(array_values(array_unique($pid_dup1_end)), $where_dup1_end);

	$arr_list = array();
	for($i=0;$i<count($ret);$i++){
		$pid = $ret[$i]["product_id"];
		$rel_babid = $ret[$i]["rel_product_bar_association_branch_bar_association_branch_id"];
		$pid_key = (string)$pid;
		// 実施会 ID が入っていない行は従来も件数 0 だったため、キーを作らずスキップする
		$babid_key = ( $rel_babid === null || $rel_babid === "" ) ? null : $pid_key."-".(string)$rel_babid;
		$is_old_pid = ( (int)$pid <= 19233 );
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add1_count
		if( isset($agg_add1[$pid_key]) ){
			$ret[$i]["product_type_add1_count"] = $agg_add1[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add1_end_count
		if( isset($agg_add1_end[$pid_key]) ){
			$ret[$i]["product_type_add1_end_count"] = $agg_add1_end[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_count（[NBR-239] 旧システム商品は実施会で絞らない）
		if( $is_old_pid ){
			if( isset($agg_add2_old[$pid_key]) ){
				$ret[$i]["product_type_add2_count"] = $agg_add2_old[$pid_key];
			}
		} elseif( $babid_key !== null && isset($agg_add2_new[$babid_key]) ){
			$ret[$i]["product_type_add2_count"] = $agg_add2_new[$babid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_end_count
		if( $is_old_pid ){
			if( isset($agg_add2_end_old[$pid_key]) ){
				$ret[$i]["product_type_add2_end_count"] = $agg_add2_end_old[$pid_key];
			}
		} elseif( $babid_key !== null && isset($agg_add2_end_new[$babid_key]) ){
			$ret[$i]["product_type_add2_end_count"] = $agg_add2_end_new[$babid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_count_kako
		if( isset($agg_add2_kako[$pid_key]) ){
			$ret[$i]["product_type_add2_count_kako"] = $agg_add2_kako[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add2_end_count_kako
		if( isset($agg_add2_end_kako[$pid_key]) ){
			$ret[$i]["product_type_add2_end_count_kako"] = $agg_add2_end_kako[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add3_count
		if( isset($agg_add3[$pid_key]) ){
			$ret[$i]["product_type_add3_count"] = $agg_add3[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++
		//product_type_add3_end_count
		if( isset($agg_add3_end[$pid_key]) ){
			$ret[$i]["product_type_add3_end_count"] = $agg_add3_end[$pid_key];
		}
		//+++++++++++++++++++++++++++++++++++++++


		// 【】重複するmember_id（student_id）を除外した件数（ループ前にまとめて算出済み）F-024 A-4
		// product_type_add1_count -----------------------------------------------------------
		if( isset($adj_add1[$pid_key]) ){
			$ret[$i]["product_type_add1_count"] = $adj_add1[$pid_key];
		}

		// product_type_add1_end_count -----------------------------------------------------------
		if( isset($adj_add1_end[$pid_key]) ){
			$ret[$i]["product_type_add1_end_count"] = $adj_add1_end[$pid_key];
		}

		if ($ret[$i]["product_type_add"] == 1){
			// 動画 ID（contents_contents1〜25）はループ前に 1 クエリでまとめて取得済み（F-024 A-4）
			$res1 = isset($map_contents[$pid_key]) ? array($map_contents[$pid_key]) : array();
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
				$sql.= "       AND student.student_id>0"; // [NBR-239] 詳細側(info.php)と条件を統一：無効な受講生(student未紐付け)のログを除外
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
	//print("\n[arr_list]\n");
	//var_dump($arr_list);
	//print("\n\n");
	$arr_disp_list = $arr_list;
	//print("\n[arr_disp_list]\n");
	//var_dump($arr_disp_list);
	//print("\n\n");
	// 【】各レコード数の再計算 --------------------------------------------------------------
}


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("レポート");
$template->admin_comment("月毎の集計レポートを表示します");
$template->admin_school($arr_session["cms_master.login.school_name"]);

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
}
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
$template->admin_school($arr_session["cms_master.login.school_name"]);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('page', $page);
$template->assign('search_product_name', $search_product_name);
$template->assign('search_product_code', $search_product_code);
$template->assign('search_product_type_add', $search_product_type_add);
$template->assign('search_association', $search_association);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('search_category', $search_category);
$template->assign('search_open', $search_open);
$template->assign('search_teacher', $search_teacher);
$template->assign('search_free', $search_free);
$template->assign('search_word', $search_word);

$template->assign('pager', $pager);
//$template->assign('arr_list', $arr_list);
$template->assign('arr_list', $arr_disp_list);


$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

//------------------------------------------------------------
$template->assign('post_data', serialize($_POST));
//------------------------------------------------------------

$template->assign('page_name', 'report_product');
$template->admin_layout('report_product/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
