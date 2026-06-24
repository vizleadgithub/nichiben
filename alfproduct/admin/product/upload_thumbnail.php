<?php
/**
 * コンテンツサムネイル画像のアップロード処理
 */
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
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
	'product_type' => $_POST["product_type"],
	'product_name' => $_POST["product_name"],
	'product_code' => $_POST["product_code"],
	'price' => $_POST["price"],
	'discount_code' => $_POST["discount_code"],
	'start_date' => $_POST["start_date"],
	'end_date' => $_POST["end_date"],
	'open_period' => $_POST["open_period"],
	'memo' => $_POST["memo"],
	'term_id' => $_POST["term_id"],
);

if($_POST["thumbnail"] != ''){
	$arr_input["thumbnail"] = $_POST["thumbnail"];
} elseif(isset($_POST["hid_thumbnail"])){
	$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
} else {
	$arr_input["thumbnail"] = '';
}

for($i=1; $i<=MAX_CONTENTS; $i++){
	if($_POST["contents_thumbnail$i"] != ''){
		$arr_input["contents_thumbnail$i"] = $_POST["contents_thumbnail$i"];
	} elseif(isset($_POST["hid_contents_thumbnail$i"])){
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
for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
	$arr_input["free_html_area$i"] = stripslashes($_POST["free_html_area$i"]);
}
// カテゴリ
$term_id = '';
$arr_input["term_id"] = $_POST["term_id"];
$arr_input["arr_term_id"] = $_POST["arr_term_id"];
if(!is_null($arr_input["arr_term_id"]) && is_array($arr_input["arr_term_id"])){
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

// ファイルアップロード処理開始
$file_form_name = '';

// サムネイル画像
if (isset($_FILES["thumbnail"])){
	if ($_FILES["thumbnail"]["error"] === 0){
		$file_form_name = "thumbnail";
	}
}
// コンテンツサムネイル画像
for ($i=1; $i<=MAX_CONTENTS; $i++){
	if (isset($_FILES["contents_thumbnail$i"])){
		if ($_FILES["contents_thumbnail$i"]["error"] === 0){
			$file_form_name = "contents_thumbnail$i";
			break;
		}
	}
}

// アップロードされたファイルかどうかの確認
if (is_uploaded_file($_FILES[$file_form_name]['tmp_name'])){
	$upload_dir_name = dirname(__FILE__) ."/../../upload/thumbnail";
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
			} else {
				$arr_input[$file_form_name] = $img_name;
			}
			
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
?>

<form name="form1" action="add.php" method="post">
<input type="hidden" name="act" value="upload" />
<?php foreach($arr_input as $key => $val){ ?>
	<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
<?php } ?>
<?php foreach($arr_input["arr_term_id"] as $val){ ?>
<input type="hidden" name="arr_term_id[]" value="<?php echo $val; ?>" />
<?php } ?>
</form>

<script type="text/javascript">
window.onload = document.form1.submit();
</script>
