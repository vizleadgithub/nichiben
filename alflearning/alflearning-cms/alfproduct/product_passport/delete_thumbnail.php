<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
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
if( trim($_GET["del"])!=""){
	$arr_input[trim($_GET["del"])]="";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>

<form name="form1" action="add.php" method="post">
<input type="hidden" name="act" value="upload" />
<input type="hidden" name="fileupload" value="" />
<?php foreach($arr_input as $key => $val){ ?>
	<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
<?php } ?>
<?php foreach($arr_input["passport_target"] as $val){ ?>
<input type="hidden" name="passport_target[]" value="<?php echo htmlspecialchars($val); ?>" />
<?php } ?>
</form>

<script type="text/javascript">
window.onload = document.form1.submit();
</script>
