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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$err_msg = '';

//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_input = array(
	'product_name' => $_POST["product_name"],
	'price' => $_POST["price"],
	'memo' => $_POST["memo"],
	'passport_target' => $_POST["passport_target"]
);

if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["thumbnail"];
} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
	$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
} else {
	$arr_input["thumbnail"] = '';
}

for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
	$arr_input["related_products$i"] = $_POST["related_products$i"];
	$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'];
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
<input type="hidden" name="fileupload" value="" />
<?php foreach($arr_input as $key => $val){ ?>
	<input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($val); ?>" />
<?php } ?>
<?php foreach($arr_input["passport_target"] as $val){ ?>
<input type="hidden" name="passport_target[]" value="<?php echo htmlspecialchars($val); ?>" />
<?php } ?>
</form>

<script type="text/javascript">
window.onload = document.form1.submit();
</script>
