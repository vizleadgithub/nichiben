<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
include(dirname(__FILE__) ."./../../module/module.php");

$err_flg = 0;

$member_id = $_POST["member_id"];
$card_no = $_POST["card_no"];
$expire = $_POST["expire"];
if (isset($_POST["fixed_flag"])){
	$fixed_flag = 1;
} else {
	$fixed_flag = 0;
}

$card_seq = $_POST["card_seq"];
$holder_name = $_POST["holder_name"];
$card_name = $_POST["card_name"];
$card_pass = $_POST["card_pass"];

$objDbConnect = new DbConnect();
$objGMOPaymentProtocol = new GMOPaymentProtocol();

// -------------------
// カード情報登録開始
// -------------------
// 一意なidを作成
$sql = "UPDATE tbl_card_check SET id = id+1";
$result1 = $objDbConnect->execute($sql);

if ($result1){
	// 作成したidを取得
	$sql = "SELECT id FROM tbl_card_check";
	$arr_id = $objDbConnect->query_fetch($sql);
	
	if ($arr_id){
echo $arr_id['id'];
echo '<br />';
		$objDbConnect->tran_begin();
		
		// カードの有効性チェック
		$ret1 = $objGMOPaymentProtocol->entry_tran('CHECK'.$arr_id['id'], 1);
var_dump($ret1);
echo '<br />';
		if (isset($ret1["ErrCode"]) || isset($ret1["ErrInfo"])){
			$err_flg = 1;
			echo 'カード有効性エラー';
			
		} else {
			// カード情報登録(GMOサーバ)
			$ret2 = $objGMOPaymentProtocol->save_card($member_id, $card_no, $expire, $fixed_flag, $card_seq, $holder_name, $card_name, $card_pass);
var_dump($ret2);
echo '<br />';
			if (isset($ret2["ErrCode"]) || isset($ret2["ErrInfo"])){
				$err_flg = 1;
				echo 'カード情報登録エラー';
				
			} else {
				// 会員情報更新(自サーバ)
				$sql = "UPDATE tbl_member SET fixed_flag = '$fixed_flag' WHERE member_id = '$member_id'";
				$result2 = $objDbConnect->execute($sql);
				if ($result2){
				} else {
					$err_flg = 1;
					echo '会員情報更新に失敗';
				}
				
			}
		}
		
		if ($err_flg){
			$objDbConnect->rollback();
		} else {
			$objDbConnect->commit();
		}
		
	} else {
		$err_flg = 1;
		echo 'id取得に失敗';
		
	}
	
} else {
	$err_flg = 1;
	echo 'id作成に失敗';
	
}

$objDbConnect->close();

if ($err_flg){
	echo '<br />失敗(代替案を提示する等の対応必要)　遷移処理';
} else {
	echo '<br />成功!　遷移処理';
}

exit;
?>