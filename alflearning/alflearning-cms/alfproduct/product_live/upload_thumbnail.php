<?php
/**
 * コンテンツサムネイル画像のアップロード処理
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
ini_set('display_errors', 1);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$err_msg = '';
$mtb_bar_association_branch = get_mtb_bar_association_branch();
//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_input = array(
	'training_kind_flg' => $_POST["training_kind_flg"] ?? "",
	'ethic_flg' => $_POST["ethic_flg"] ?? "",
	'web_flg' => $_POST["web_flg"] ?? "",
	'live_start_date' => $_POST["live_start_date"] ?? "",
	'start_date' => $_POST["start_date"] ?? "",
	'end_date' => $_POST["end_date"] ?? "",
	'limit_date' => $_POST["limit_date"] ?? "",
	'dates' => $_POST["dates"] ?? "",
	'download_flg' => $_POST["download_flg"] ?? "",
	'target_flg' => $_POST["target_flg"] ?? "",
	'product_name' => $_POST["product_name"] ?? "",
	'product_code' => $_POST["product_code"] ?? "",
	'memo1' => $_POST["memo1"] ?? "",
	'memo2' => $_POST["memo2"] ?? "",
	'memo3' => $_POST["memo3"] ?? "",
	'memo4' => $_POST["memo4"] ?? "",
	'memo5' => $_POST["memo5"] ?? "",
	'contents' => $_POST["contents"] ?? "",
	'hall' => $_POST["hall"] ?? "",
	'capacity' => $_POST["capacity"] ?? "",
	'price' => $_POST["price"] ?? "",
	'all_bar_association_target' => $_POST["all_bar_association_target"] ?? "",
);

if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["thumbnail"];
} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
} else {
	$arr_input["thumbnail"] = '';
}
/*
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
*/
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
// 実施弁護士会
foreach ($mtb_bar_association_branch as $val){
	$bar_association_branch_id = $val["bar_association_branch_id"];
	// 定員
	if (isset($_POST["capacity".$bar_association_branch_id])){
		$arr_input["capacity".$bar_association_branch_id] = $_POST["capacity".$bar_association_branch_id];
	}
	// 会場
	if (isset($_POST["hall".$bar_association_branch_id])){
		$arr_input["hall".$bar_association_branch_id] = $_POST["hall".$bar_association_branch_id];
	}
	// 受付(開始)
	if (isset($_POST["receptionist_start_date".$bar_association_branch_id])){
		$arr_input["receptionist_start_date".$bar_association_branch_id] = $_POST["receptionist_start_date".$bar_association_branch_id];
	}
	// 受付(終了)
	if (isset($_POST["receptionist_end_date".$bar_association_branch_id])){
		$arr_input["receptionist_end_date".$bar_association_branch_id] = $_POST["receptionist_end_date".$bar_association_branch_id];
	}
	// 実施日
	if (isset($_POST["dates".$bar_association_branch_id])){
		$arr_input["dates".$bar_association_branch_id] = $_POST["dates".$bar_association_branch_id];
	}
	// Web申込
	if (isset($_POST["web_flg".$bar_association_branch_id])){
		$arr_input["web_flg".$bar_association_branch_id] = $_POST["web_flg".$bar_association_branch_id];
	}
	// 備考
	if (isset($_POST["contents".$bar_association_branch_id])){
		$arr_input["contents".$bar_association_branch_id] = $_POST["contents".$bar_association_branch_id];
	}
}

// カテゴリ
$term_id = '';
$arr_input["term_id"] = $_POST["term_id"] ?? "";
$arr_input["arr_term_id"] = $_POST["arr_term_id"] ?? [];
$str_term_id = "";
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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ファイルアップロード処理開始
$file_form_name = '';

// サムネイル画像
if (isset($_FILES["thumbnail"])){
	if ($_FILES["thumbnail"]["error"] === 0){
		$file_form_name = "thumbnail";
	}
}
/*
if (isset($_FILES["thumbnail1"])){
	if ($_FILES["thumbnail1"]["error"] === 0){
		$file_form_name = "thumbnail1";
	}
}
if (isset($_FILES["thumbnail2"])){
	if ($_FILES["thumbnail2"]["error"] === 0){
		$file_form_name = "thumbnail2";
	}
}
if (isset($_FILES["thumbnail3"])){
	if ($_FILES["thumbnail3"]["error"] === 0){
		$file_form_name = "thumbnail3";
	}
}
if (isset($_FILES["thumbnail4"])){
	if ($_FILES["thumbnail4"]["error"] === 0){
		$file_form_name = "thumbnail4";
	}
}
if (isset($_FILES["thumbnail5"])){
	if ($_FILES["thumbnail5"]["error"] === 0){
		$file_form_name = "thumbnail5";
	}
}
if (isset($_FILES["thumbnail6"])){
	if ($_FILES["thumbnail6"]["error"] === 0){
		$file_form_name = "thumbnail6";
	}
}
if (isset($_FILES["thumbnail7"])){
	if ($_FILES["thumbnail7"]["error"] === 0){
		$file_form_name = "thumbnail7";
	}
}
if (isset($_FILES["thumbnail8"])){
	if ($_FILES["thumbnail8"]["error"] === 0){
		$file_form_name = "thumbnail8";
	}
}
*/

// アップロードされたファイルかどうかの確認
if (is_uploaded_file($_FILES[$file_form_name]['tmp_name'])){
	//$upload_dir_name = dirname(__FILE__) ."/../../upload/thumbnail";
	$upload_dir_name ="/alflearning-data/alfproduct/thumbnail";
	$tmp_name         = $_FILES[$file_form_name]['tmp_name'];
	
	// 拡張子チェック(jpg,png,gifのみ許可)
	if (exif_imagetype($tmp_name) == IMAGETYPE_JPEG
	 || exif_imagetype($tmp_name) == IMAGETYPE_PNG
	 || exif_imagetype($tmp_name) == IMAGETYPE_GIF){
	 	// 画像ファイル名の設定
		if (exif_imagetype($tmp_name) == IMAGETYPE_JPEG){
			$extension = '.jpg';
		} else if (exif_imagetype($tmp_name) == IMAGETYPE_PNG){
			$extension = '.png';
		} else if (exif_imagetype($tmp_name) == IMAGETYPE_GIF){
			$extension = '.gif';
		} else {
			$extension = '';
		}
		$img_name = uniqid(rand()).$extension;
		
		// tmpより指定ディレクトリにファイルを保存
		if (move_uploaded_file($tmp_name, "$upload_dir_name/$img_name")){
			// ファイルの新規登録＆上書き
			if ($file_form_name == 'thumbnail'){
				$arr_input["thumbnail"] = $img_name;
			}
			/*
			elseif ($file_form_name == 'thumbnail1'){
				$arr_input["thumbnail1"] = $img_name;
			} elseif ($file_form_name == 'thumbnail2'){
				$arr_input["thumbnail2"] = $img_name;
			} elseif ($file_form_name == 'thumbnail3'){
				$arr_input["thumbnail3"] = $img_name;
			} elseif ($file_form_name == 'thumbnail4'){
				$arr_input["thumbnail4"] = $img_name;
			} elseif ($file_form_name == 'thumbnail5'){
				$arr_input["thumbnail5"] = $img_name;
			} elseif ($file_form_name == 'thumbnail6'){
				$arr_input["thumbnail6"] = $img_name;
			} elseif ($file_form_name == 'thumbnail7'){
				$arr_input["thumbnail7"] = $img_name;
			} elseif ($file_form_name == 'thumbnail8'){
				$arr_input["thumbnail8"] = $img_name;
			} else {
				$arr_input[$file_form_name] = $img_name;
			}
			*/
			
		} else {
			$err_msg = 'ファイルのアップロードに失敗しました。';
			
		}
		
	} else {
		$err_msg = 'jpg,png,gif形式の画像ファイルをアップロードしてください。';
	}
	
} else {
	$err_msg = 'ファイルをアップロードしてください。';
	
}

if($err_msg != ''){
	$arr_input['err_msg'] = $err_msg;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>

<html>
<body>
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
</form>

<script type="text/javascript">
window.onload = document.form1.submit();
</script>
</body>
</html>
