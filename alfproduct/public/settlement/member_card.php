<?php
include(dirname(__FILE__) ."./../../module/module.php");

$objGMOPaymentProtocol = new GMOPaymentProtocol();
$ret = $objGMOPaymentProtocol->search_member($_SESSION['member_id']);
?>
<br /><br />カード登録<br /><br />


<form name="form_test" method="post" action="member_card_regist.php">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token_get(), ENT_QUOTES, 'UTF-8'); ?>" />
※会員ID：<input type="text" name="member_id" value="<?php echo $_SESSION['member_id']; ?>" /><br>
※カード番号：<input type="text" name="card_no" value="4111111111111111" /><br>
※有効期限(YYMM形式)：<input type="text" name="expire" value="1601" /><br>
継続課金：<input type="checkbox" name="fixed_flag" value="1" checked /><br><br>

カード登録連番：<input type="text" name="card_seq" /><br>
名義人：<input type="text" name="holder_name" /><br>
カード会社名：<input type="text" name="card_name" /><br>
カードパスワード：<input type="text" name="card_pass" /><br>
<br>
<input type="submit" name="btn_submit" value="登録">
</form>
