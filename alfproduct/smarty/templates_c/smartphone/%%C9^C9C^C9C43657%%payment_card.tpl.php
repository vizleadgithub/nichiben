<?php /* Smarty version 2.6.27, created on 2013-04-25 16:52:37
         compiled from /srv/alfproduct/smarty/templates/smartphone/settlement/payment_card.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/settlement/payment_card.tpl', 48, false),)), $this); ?>
<style type="text/css">

.btn_close{
 background: url(/img/close_b2.gif)no-repeat ;
 width:133px;
 height:30px;
 border:0px;
 text-indent: -9999px;
}
.btn_submit{
 background: url(/img/account_btn2.gif)no-repeat ;
 width:200px;
 height:30px;
 border:0px;
 text-indent: -9999px;
}
</style>
<div style="padding-bottom:20px;">



<?php if ($this->_tpl_vars['err_flg']): ?>
	<div style="color:#999999; font-weight:bold; font-size:14px;padding-top:5px;padding-left:5px;">カード決済の失敗</div>
	<hr style="border: 1px dotted #999999;">

	<div style="text-align:center;margin-top:90px;">
	<span style="color:red;font-size:12px;">決済に失敗しました。<br />お手数ですが再度ご購入をお願いいたします。<br /></span>
	<div style="margin-top:30px;">
	<span style="color:#ff6666;font-size:12px;">下記「閉じる」ボタンをクリックしてください。<br /></span>
	<input type="button" class="btn_close" value="閉じる" onclick="window.close();" />
	</div>
	</div>
	<?php else: ?>
	<div style="color:#999999; font-weight:bold; font-size:14px;padding-top:5px;padding-left:5px;">カード情報の入力</div>
	<hr style="border: 1px dotted #999999;">

	<form name="form_payment" method="post" action="#">
	注文番号：<?php echo ((is_array($_tmp=$this->_tpl_vars['order_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br>
	購入金額：<?php echo ((is_array($_tmp=$this->_tpl_vars['total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
円<br>
	カード番号:<input type="text" name="card_no" value=""><br>
	カード有効期限:
		<select name="expire_m">
			<option value="">--</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select>月　
		<input type="text" name="expire_y" value="" size="4" maxlength="2">年（西暦下2桁）
		<br>
	<img src="/img/card_logo.png" style="width:100%;"><br>
	<br>

<div style="color: #666666;font-size: 12px;margin-left:5px;">
クレジットカード情報の取扱について<br>
・利用目的：お支払い代金決済のため<br>
・取得者：伊藤塾（株式会社法学館）<br>
・提供先：決済代行会社<br>（GMOペイメントゲートウェイ株式会社）<br>
・保存期間：精算手続き完了まで<br>（カード情報を登録した場合を除く）<br>
<br>
<span style="color:#ff6666;">以上の内容で間違いなければ、下記「購入完了」ボタンをクリックしてください。</span>
</div>

<div style="text-align:center;">
	<input type="submit" class="btn_submit" name="btn_submit" value="購入完了" />
</div>
<div style="text-align:center;margin-top:30px;">
	<input type="button" class="btn_close" value="閉じる" onclick="window.close();" />
</div>
	</form>
<?php endif; ?>
</div>