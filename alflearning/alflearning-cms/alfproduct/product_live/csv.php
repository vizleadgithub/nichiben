<?php
ini_set('display_errors', 1);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$get_mtb_bar_association = get_mtb_bar_association();
foreach ($get_mtb_bar_association as $key => $val){
	$mtb_bar_association[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp = get_mtb_live_training_type();
foreach ($tmp as $key => $val){
	$mtb_live_training_type[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp = get_mtb_data('mtb_live_target_flg');
foreach ($tmp as $val){
	$mtb_live_target_flg[$val['id']] = $val['name'];
}
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
					}
				}
			}
		}
		
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_product_name = "";
$search_product_code = "";
$search_start_date = "";
$search_end_date = "";
$search_category = array();
$search_open = "0";
$search_teacher = "";
$search_free = "0";
$search_dates_start = "";
$search_dates_end = "";
$search_bar_association = "";

if( isset($_SESSION["product_live.search_product_name"]) && !empty($_SESSION["product_live.search_product_name"]) ){
	$search_product_name = $_SESSION["product_live.search_product_name"];
}
if( isset($_SESSION["product_live.search_product_code"]) && !empty($_SESSION["product_live.search_product_code"]) ){
	$search_product_code = $_SESSION["product_live.search_product_code"];
}
if( isset($_SESSION["product_live.search_start_date"]) && !empty($_SESSION["product_live.search_start_date"]) ){
	$search_start_date = $_SESSION["product_live.search_start_date"];
}
if( isset($_SESSION["product_live.search_end_date"]) && !empty($_SESSION["product_live.search_end_date"]) ){
	$search_end_date = $_SESSION["product_live.search_end_date"];
}
if( isset($_SESSION["product_live.search_category"]) && !empty($_SESSION["product_live.search_category"]) ){
	$search_category = $_SESSION["product_live.search_category"];
}
if( isset($_SESSION["product_live.search_open"]) && !empty($_SESSION["product_live.search_open"]) ){
	$search_open = $_SESSION["product_live.search_open"];
}
if( isset($_SESSION["product_live.search_teacher"]) && !empty($_SESSION["product_live.search_teacher"]) ){
	$search_teacher = $_SESSION["product_live.search_teacher"];
}
if( isset($_SESSION["product_live.search_free"]) && !empty($_SESSION["product_live.search_free"]) ){
	$search_free = $_SESSION["product_live.search_free"];
}
if( isset($_SESSION["product_live.search_word"]) && !empty($_SESSION["product_live.search_word"]) ){
	$search_word = $_SESSION["product_live.search_word"];
}
if( isset($_SESSION["product_live.search_dates_start"]) && !empty($_SESSION["product_live.search_dates_start"]) ){
	$search_dates_start = $_SESSION["product_live.search_dates_start"];
}
if( isset($_SESSION["product_live.search_dates_end"]) && !empty($_SESSION["product_live.search_dates_end"]) ){
	$search_dates_end = $_SESSION["product_live.search_dates_end"];
}
if( isset($_SESSION["product_live.search_bar_association"]) && !empty($_SESSION["product_live.search_bar_association"]) ){
	$search_bar_association = $_SESSION["product_live.search_bar_association"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "
SELECT
  COUNT(tbl_product.product_id) AS c
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
    INNER JOIN
  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA
      ON tbl_product.product_id = RPBA.product_id
WHERE
  tbl_product.del_flg = 0
";

$where = "";

// ログインユーザーが日弁連以外の場合は弁護士会IDで絞る
if (!$nichibenren_flg){
	$where.= " and ( RPBA.bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$login_bar_association_id)."' ) ";
}

if( $search_product_name != "" ){
	$where.= " and ( product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_name)."%' ) ";
}
if( $search_product_code != "" ){
	$where.= " and ( product_code like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_code)."%' ) ";
}
if( $search_dates_start != "" && $search_dates_end != "" ){
	$where.= " and ('".$search_dates_start."' <= RPBA.dates AND '".$search_dates_end."' >= RPBA.dates) ";
} elseif( $search_dates_start != "" ){
	$where.= " and (RPBA.dates >= '".$search_dates_start."') ";
} elseif( $search_dates_end != "" ){
	$where.= " and (RPBA.dates <= '".$search_dates_end."') ";
}
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
	//$where.= " and ( teacher like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher1 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher3 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher4 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher5 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher6 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher7 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher8 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher9 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' OR contents_teacher10 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' ) ";
	$where.= " and ( tbl_product_live_training.memo2 like '%".mysqli_real_escape_string($objDbConnect->connect,$search_teacher)."%' ) ";
}
if( $search_free == "1" ){//有料
	$where.= " and ( price>'0' ) ";
}
if( $search_free == "2" ){//無料
	$where.= " and ( price='0' ) ";
}
/*
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
*/
if( $search_bar_association != "" ){
	$where.= " and ( RPBA.bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$search_bar_association)."' ) ";
}

$order = " ORDER BY CASE WHEN RPBA.dates < '".date('Y-m-d H:i:s')."' THEN 1 ELSE 0 END, RPBA.dates ASC ";

$sql ="
SELECT
  tbl_product.product_id,
  tbl_product.product_name,
  tbl_product.price,
  tbl_product.term_id,
  DATE_FORMAT(tbl_product.start_date, '%Y/%m/%d %H:%i') AS start_date,
  DATE_FORMAT(tbl_product.end_date, '%Y/%m/%d %H:%i') AS end_date,
  tbl_product_live_training.training_kind_flg,
  tbl_product_live_training.ethic_flg,
  tbl_product_live_training.sponsor,
  tbl_product_live_training.target,
  tbl_product_live_training.target_flg,
  tbl_product_live_training.memo1,
  tbl_product_live_training.memo2,
  tbl_product_live_training.memo3,
  tbl_product_live_training.memo4,
  tbl_product_live_training.memo5,
  RPBA.bar_association_id,
  RPBA.hall,
  RPBA.contents,
  RPBA.capacity,
  DATE_FORMAT(RPBA.dates, '%Y/%m/%d') AS dates
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
    INNER JOIN
  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA
      ON tbl_product.product_id = RPBA.product_id
WHERE
  tbl_product.del_flg = 0
";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// CSVヘッダ
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=product_live_".date("YmdHis").".csv");

echo mb_convert_encoding("研修種別,倫理研修,開催日,主催,受講対象者,受講対象,商品名（研修名）,研修の内容,講義タイトル・講師名,日時詳細,会場について,定員,商品価格（税込）,問い合わせ先,受講資格・他会員の受講等,備考,商品カテゴリ\r\n", "SJIS", "UTF-8");
for($i=0;$i<count($ret);$i++){
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $mtb_live_training_type[$ret[$i]["training_kind_flg"]] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$disp_ethic_flg = '倫理研修対象としない';
	if ($ret[$i]["ethic_flg"] == 1){
		$disp_ethic_flg = '倫理研修対象とする';
	}
	echo mb_convert_encoding('"' . $disp_ethic_flg . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["dates"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$disp_sponsor = '';
	if ($ret[$i]["sponsor"] != ''){
		$arr_sponsor = array();
		$arr_sponsor = explode('|', trim($ret[$i]["sponsor"], '|'));
		foreach ($arr_sponsor as $baid){
			if (isset($mtb_bar_association[$baid])){
				$disp_sponsor.= $mtb_bar_association[$baid] . '/';
			}
		}
		$disp_sponsor = rtrim($disp_sponsor, '/');
	}
	echo mb_convert_encoding('"' . $disp_sponsor . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $mtb_live_target_flg[$ret[$i]["target_flg"]] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$disp_target = '';
	if ($ret[$i]["target"] != ''){
		$arr_target = array();
		$arr_target = explode('|', trim($ret[$i]["target"], '|'));
		foreach ($arr_target as $baid){
			if (isset($mtb_bar_association[$baid])){
				$disp_target.= $mtb_bar_association[$baid] . '/';
			}
		}
		$disp_target = rtrim($disp_target, '/');
	}
	echo mb_convert_encoding('"' . $disp_target . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["product_name"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["memo1"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["memo2"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["memo3"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["hall"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["capacity"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["price"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["memo4"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["memo5"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["contents"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$disp_term_name = '';
	if ($ret[$i]["term_id"] != ''){
		$sql = "SELECT name FROM wp_terms WHERE term_id IN(".$ret[$i]["term_id"].") ORDER BY slug ASC";
		$arr_term_name = $objDbConnect->query_fetch_arr($sql);
		if ($arr_term_name){
			foreach ($arr_term_name as $val){
				$disp_term_name.= $val['name'] . '/';
			}
			$disp_term_name = rtrim($disp_term_name, '/');
		}
	}
	echo mb_convert_encoding('"' . $disp_term_name . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	//++++++++++++++++++++
}
exit;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
