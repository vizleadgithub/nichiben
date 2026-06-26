<?php
/**
 * search_○○.phpの検索結果を親画面に反映させる
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
$id = $_GET['id'];
$name = $_GET['name'];
$hid_id_name = 'hid_'.$_GET['gid'];
$spa_id_name = 'spa_'.$_GET['gid'];
$hid_name_name = 'hid_'.$_GET['gid'].'_name';
?>

<script type="text/javascript">
function serchSet(id, name, hid_id_name, spa_id_name, hid_name_name){
	if(!window.opener || window.opener.closed){
		window.close();
	} else{
		window.opener.document.getElementById(hid_id_name).value = id;
		window.opener.document.getElementById(hid_name_name).value = name;
		window.opener.document.getElementById(spa_id_name).innerHTML = name;
		window.close();
	}
}
window.onload = serchSet(<?php echo json_encode($id).", ".json_encode($name).", ".json_encode($hid_id_name).", ".json_encode($spa_id_name).", ".json_encode($hid_name_name); ?>);
</script>
