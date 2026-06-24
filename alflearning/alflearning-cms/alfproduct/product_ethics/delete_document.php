<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
/**
 * ダウンロード資料のアップロード処理
 */
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$err_msg = '';

//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_input = array(
	//'product_type' => $_POST["product_type"],
	'product_name' => $_POST["product_name"],
	'product_code' => $_POST["product_code"],
	'price' => $_POST["price"],
	'discount_code' => $_POST["discount_code"],
	'start_date' => $_POST["start_date"],
	'end_date' => $_POST["end_date"],
	'open_period' => $_POST["open_period"],
	'memo' => $_POST["memo"],
	'term_id' => $_POST["term_id"],
	'play_time' => $_POST["play_time"],
	'teacher' => $_POST["teacher"],
	'ethic_group_id' => $_POST["ethic_group_id"],
	'bar_association_year' => $_POST["bar_association_year"],
	'publish_flg' => $_POST["publish_flg"]
	//'search_word' => $_POST["search_word"],
	//'live_training_product_id' => $_POST["live_training_product_id"],
	//'live_training_product_name' => $_POST["live_training_product_name"],
	//'product_flg' => $_POST["product_flg"]
);

if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["thumbnail"];
} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
} else {
	$arr_input["thumbnail"] = '';
}
if( isset($_POST["thumbnail1"]) && $_POST["thumbnail1"] != '' ){
	$arr_input["thumbnail1"] = $_POST["thumbnail1"];
} elseif(isset($_POST["hid_thumbnail1"]) && $_POST["hid_thumbnail1"] != '' ){
	$arr_input["thumbnail1"] = $_POST["hid_thumbnail1"];
} else {
	$arr_input["thumbnail1"] = '';
}
if( isset($_POST["thumbnail2"]) && $_POST["thumbnail2"] != '' ){
	$arr_input["thumbnail2"] = $_POST["thumbnail2"];
} elseif(isset($_POST["hid_thumbnail2"]) && $_POST["hid_thumbnail2"] != '' ){
	$arr_input["thumbnail2"] = $_POST["hid_thumbnail2"];
} else {
	$arr_input["thumbnail2"] = '';
}
if( isset($_POST["thumbnail3"]) && $_POST["thumbnail3"] != '' ){
	$arr_input["thumbnail3"] = $_POST["thumbnail3"];
} elseif(isset($_POST["hid_thumbnail3"]) && $_POST["hid_thumbnail3"] != '' ){
	$arr_input["thumbnail3"] = $_POST["hid_thumbnail3"];
} else {
	$arr_input["thumbnail3"] = '';
}
if( isset($_POST["thumbnail4"]) && $_POST["thumbnail4"] != '' ){
	$arr_input["thumbnail4"] = $_POST["thumbnail4"];
} elseif(isset($_POST["hid_thumbnail4"]) && $_POST["hid_thumbnail4"] != '' ){
	$arr_input["thumbnail4"] = $_POST["hid_thumbnail4"];
} else {
	$arr_input["thumbnail4"] = '';
}
if( isset($_POST["thumbnail5"]) && $_POST["thumbnail5"] != '' ){
	$arr_input["thumbnail5"] = $_POST["thumbnail5"];
} elseif(isset($_POST["hid_thumbnail5"]) && $_POST["hid_thumbnail5"] != '' ){
	$arr_input["thumbnail5"] = $_POST["hid_thumbnail5"];
} else {
	$arr_input["thumbnail5"] = '';
}
if( isset($_POST["thumbnail6"]) && $_POST["thumbnail6"] != '' ){
	$arr_input["thumbnail6"] = $_POST["thumbnail6"];
} elseif(isset($_POST["hid_thumbnail6"]) && $_POST["hid_thumbnail6"] != '' ){
	$arr_input["thumbnail6"] = $_POST["hid_thumbnail6"];
} else {
	$arr_input["thumbnail6"] = '';
}
if( isset($_POST["thumbnail7"]) && $_POST["thumbnail7"] != '' ){
	$arr_input["thumbnail7"] = $_POST["thumbnail7"];
} elseif(isset($_POST["hid_thumbnail7"]) && $_POST["hid_thumbnail7"] != '' ){
	$arr_input["thumbnail7"] = $_POST["hid_thumbnail7"];
} else {
	$arr_input["thumbnail7"] = '';
}
if( isset($_POST["thumbnail8"]) && $_POST["thumbnail8"] != '' ){
	$arr_input["thumbnail8"] = $_POST["thumbnail8"];
} elseif(isset($_POST["hid_thumbnail8"]) && $_POST["hid_thumbnail8"] != '' ){
	$arr_input["thumbnail8"] = $_POST["hid_thumbnail8"];
} else {
	$arr_input["thumbnail8"] = '';
}

for($i=1; $i<=MAX_CONTENTS; $i++){
	if( isset($_POST["contents_thumbnail$i"]) && $_POST["contents_thumbnail$i"] != ''){
		$arr_input["contents_thumbnail$i"] = $_POST["contents_thumbnail$i"];
	} elseif( isset($_POST["hid_contents_thumbnail$i"]) && $_POST["hid_contents_thumbnail$i"] != '' ){
		$arr_input["contents_thumbnail$i"] = $_POST["hid_contents_thumbnail$i"];
	} else {
		$arr_input["contents_thumbnail$i"] = '';
	}
	$arr_input["contents_contents$i"] = $_POST["contents_contents$i"];
	$arr_input["contents_contents$i".'_name'] = $_POST["contents_contents$i".'_name'];
	$arr_input["contents_free_time$i"] = $_POST["contents_free_time$i"];
	$arr_input["contents_start_date$i"] = $_POST["contents_start_date$i"];
	$arr_input["contents_end_date$i"] = $_POST["contents_end_date$i"];
	$arr_input["contents_memo$i"] = $_POST["contents_memo$i"];
	$arr_input["contents_teacher$i"] = $_POST["contents_teacher$i"];
	
	for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
		if($_POST['contents_download'.$i.'_'.$j] != ''){
			$arr_input['contents_download'.$i.'_'.$j] = $_POST['contents_download'.$i.'_'.$j];
			$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
		} elseif(isset($_POST['hid_contents_download'.$i.'_'.$j])){
			$arr_input['contents_download'.$i.'_'.$j] = $_POST['hid_contents_download'.$i.'_'.$j];
			$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
		} else {
			$arr_input['contents_download'.$i.'_'.$j] = '';
		}
	}
}
for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
	$arr_input["related_products$i"] = $_POST["related_products$i"];
	$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'];
}
/*
for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
	$arr_input["free_html_area$i"] = stripslashes($_POST["free_html_area$i"]);
}
for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
	$arr_input["free_html_area$i_sp"] = stripslashes($_POST["free_html_area$i_sp"]);
}
*/
if( isset($_POST["all_contents_download"]) && $_POST["all_contents_download"] != '' ){
	$arr_input["all_contents_download"] = $_POST["all_contents_download"];
} elseif(isset($_POST["hid_all_contents_download"]) && $_POST["hid_all_contents_download"] != '' ){
	$arr_input["all_contents_download"] = $_POST["hid_all_contents_download"];
} else {
	$arr_input["all_contents_download"] = '';
}
if( isset($_POST["all_contents_download_before"]) && $_POST["all_contents_download_before"] != '' ){
	$arr_input["all_contents_download_before"] = $_POST["all_contents_download_before"];
} else {
	$arr_input["all_contents_download_before"] = '';
}

// カテゴリ
$term_id = '';
$arr_input["term_id"] = $_POST["term_id"];
$arr_input["arr_term_id"] = $_POST["arr_term_id"] ?? [];
if(isset($arr_input["arr_term_id"]) && is_array($arr_input["arr_term_id"])){
	$cnt = 1;
	foreach($arr_input["arr_term_id"] as $val){
		$str_term_id .= $cnt.','.get_product_category_name_html($val).'<br />';
		$term_id .= $val.',';
		$cnt++;
	}
	$arr_input['term_id'] = rtrim($term_id, ',');
	$arr_input['str_term_id'] = rtrim($str_term_id, ',');
}
// 修正時の対象ID
if (isset($_POST['mid'])){
	$arr_input['mid'] = $_POST['mid'];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( trim($_GET["del"])!=""){
	$arr_input[trim($_GET["del"])]="";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>

<form name="form1" action="add.php" method="post">
<input type="hidden" name="act" value="upload" />
<input type="hidden" name="fileupload" value="" />
<?php foreach($arr_input as $key => $val){ ?>
	<?php if( is_array($val) ){ ?>
		<?php foreach($val as $key2 => $val2){ ?>
			<input type="hidden" name="<?php echo $key; ?>[]" value="<?php echo htmlspecialchars($val2); ?>" />
		<?php } ?>
	<?php } else { ?>
		<input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($val); ?>" />
	<?php } ?>
<?php } ?>
<?php foreach($arr_input["arr_term_id"] as $val){ ?>
<!--<input type="hidden" name="arr_term_id[]" value="<?php echo $val; ?>" />-->
<?php } ?>
<?php /* foreach($arr_input["product_flg"] as $val){ ?>
<input type="hidden" name="product_flg[]" value="<?php echo htmlspecialchars($val); ?>" />
<?php } */ ?>
</form>

<script type="text/javascript">
window.onload = document.form1.submit();
</script>
