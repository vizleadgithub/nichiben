<?php /* Smarty version 2.6.31, created on 2025-04-02 19:48:42
         compiled from /srv/alfproduct/smarty/templates/default/settlement/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/settlement/index.tpl', 37, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/default/settlement/index.tpl', 72, false),)), $this); ?>
<script type="text/javascript">
function buy_exe(){
	//document.formSettlement.btn_submit.disabled = true;
	
	var payment_type = '';
	for (var i=0; i<document.formSettlement.payment_type.length; i++){
		if (document.formSettlement.payment_type[i].checked){
			payment_type = document.formSettlement.payment_type[i].value;
		}
	}
	
	<?php if ($_SESSION['user']['presence_passport'] == 1 && $this->_tpl_vars['buy_passport'] == 0): ?>
		payment_type = 'pp';
	<?php elseif ($this->_tpl_vars['cart_total_price'] <= 0): ?>
		payment_type = 'pf';
	<?php endif; ?>
	
	if (payment_type != ''){
		//new $pop( 'order_regist.php?pid='+document.formSettlement.pid.value+'&payment_type='+payment_type+'&temp_date='+document.formSettlement.temp_date.value+'&temp_no='+document.formSettlement.temp_no.value, { type: 'iframe', title: '', width: 500, height: 480, modal: true, nomove: true, close: false, varName: 'payment_win' } ); return false;
		document.formSettlement.action = 'order_regist.php?pid='+document.formSettlement.pid.value+'&payment_type='+payment_type+'&temp_date='+document.formSettlement.temp_date.value+'&temp_no='+document.formSettlement.temp_no.value;
		document.formSettlement.submit();
	} else {
		alert('決済方法を選択してください');
	}
}
function buy_close(){
	document.formSettlement.btn_submit.disabled = true;
	location.href = "/mypage/buy_list.php";
	payment_win.close();
}
function buy__close(){
	payment_win.close();
	document.formSettlement.btn_submit.disabled = false;
}
</script>
<form name="formSettlement" method="post" action="#">
<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="temp_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['temp_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="temp_no" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['temp_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="check" value="1"  />

<div>
	<img src="/img/buy/page_step1.jpg" alt="" />
</div>

<?php if ($this->_tpl_vars['err_msg'] != ''): ?>
<div style="clear:both;padding:10px 0;color:red;">
<?php echo ((is_array($_tmp=$this->_tpl_vars['err_msg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

</div>
<?php endif; ?>

<div style="padding:0 30px;background-color:#fcfcfc;border:solid 1px #cccccc;width:900px;">
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">購入内容</div>
	</div>
	
	<?php if (( $_SESSION['user']['presence_passport'] == 1 && ! empty ( $this->_tpl_vars['cart'] ) ) && $this->_tpl_vars['buy_passport'] == 0): ?>
	<div style="color:red">研修パスポートを所持しているため、合計金額は0円となります。</div>
	<?php endif; ?>
	
	<?php if (! empty ( $this->_tpl_vars['cart'] )): ?>
	<table style="width:100%;border-collapse:collapse;border:solid 1px #cccccc;margin-top:20px;">
		<tr style="line-height:5px;">
			<th style="border:solid 1px #cccccc;vertical-align:middle;"><img src="/img/buy/tbl_title1.jpg" alt="削除" /></th>
			<th style="border:solid 1px #cccccc;vertical-align:middle;"><img src="/img/buy/tbl_title2.jpg" alt="商品名" /></th>
			<th style="border:solid 1px #cccccc;vertical-align:middle;"><img src="/img/buy/tbl_title3.jpg" alt="小計(税込)" /></th>
		</tr>
		<?php $_from = $this->_tpl_vars['cart']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['val']):
?>
		<tr>
			<td style="border:solid 1px #cccccc;text-align:center;padding:15px;vertical-align:middle;"><a href="?mode=delete&pid=<?php echo $this->_tpl_vars['key']; ?>
">削除</a></td>
			<td style="border:solid 1px #cccccc;text-align:left;padding:15px;vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td style="border:solid 1px #cccccc;text-align:right;padding:15px;vertical-align:middle;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['val']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<th colspan="2" style="text-align:right;padding:20px;vertical-align:middle;vertical-align:middle;">合計(税込)</th>
			<td style="border:solid 1px #cccccc;text-align:right;padding:20px 15px 20px 20px;vertical-align:middle;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['cart_total_price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		</tr>
	</table>
	<?php else: ?>
	カートは空です。
	<?php endif; ?>
	
	<?php if (! empty ( $this->_tpl_vars['cart'] )): ?>
		<?php if ($_SESSION['user']['presence_passport'] == 1 && $this->_tpl_vars['buy_passport'] == 0): ?>
			<input type="hidden" name="payment_type" value="" />
			
			<div style="clear:both; padding-top:20px;">
			<span style="color:red;font-size:14px;">研修パスポートが適用されています。申込手続きを完了する場合は「購入手続きへ」のボタンを押すと申込が完了します。</span>
			<br />研修の申込を追加する場合は「買い物を続ける」ボタンを押してください。
			</div>
		<?php elseif ($this->_tpl_vars['cart_total_price'] <= 0): ?>
			<input type="hidden" name="payment_type" value="" />
			
			<div style="clear:both; padding-top:20px;">
			<span style="color:red;font-size:14px;">カートの合計金額が0円のため、無料となります。申込手続きを完了する場合は「購入手続きへ」のボタンを押すと申込が完了します。</span>
			<br />研修の申込を追加する場合は「買い物を続ける」ボタンを押してください。
			</div>
		<?php else: ?>
			<div style="padding-top:20px;">
				<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">決済方法の選択</div>
			</div>
			
			<div>
				現在、クレジットカードによる決済はご利用いただけません。<br>
								<div style="margin:5px;">
					<input type="radio" name="payment_type" value="1" id="payment_type_1" disabled ><label for="payment_type_1">クレジットカード決済(VISA・Master)</label>
					<br />&nbsp;※研修パスポート・eラーニングは本サイトの購入手続き完了後、即時ご利用いただけます。
				</div>
				<div style="margin:5px;">
					<input type="radio" name="payment_type" value="12" id="payment_type_12" checked><label for="payment_type_12">銀行振り込み</label>
					<br />&nbsp;※本サイトの購入手続き完了後、15日以内に購入金額の合計額をお振込ください。当会がご入金を確認した後にご利用可能となります。
					<br />&nbsp;&nbsp;お急ぎの場合はクレジットカード決済をご利用ください。
				</div>
				<div style="margin:10px 5px;">
					&nbsp;&nbsp;なお、本サイトの購入手続完了後に決済方法を変更することはできません。
				</div>
				
							</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="pop0" style="clear:both;padding:20px 0px 20px 100px;">
	<div>
		<div style="float:left;"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['back_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/buy/keep_btn.jpg" alt="買い物を続ける" /></a></div>
	<?php if (! empty ( $this->_tpl_vars['cart'] )): ?>
		<div style="float:left;"><input type="button" class="btn_buy_process btnCursor" value="購入手続きへ" name="btn_submit" onclick="buy_exe()" style="margin-left:10px;" /></div>
	<?php endif; ?>
	</div><br style="clear:both;">
	</div>
	
</div>

</form>