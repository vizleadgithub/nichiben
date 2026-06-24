<?php /* Smarty version 2.6.31, created on 2025-02-07 08:01:58
         compiled from /srv/alfproduct/smarty/templates/admin/amount_order/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/amount_order/info.tpl', 112, false),)), $this); ?>
<h2></h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>注文ID</th>
			<td>
				<?php echo $this->_tpl_vars['arr_order'][0]['order_no']; ?>

			</td>
		</tr>
		<?php if ($this->_tpl_vars['arr_order'][0]['payment_type'] == '1'): ?>
		<tr>
			<th>決済ID</th>
			<td>
				<?php echo $this->_tpl_vars['arr_order'][0]['order_id']; ?>

			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th style="">登録番号</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_order'][0]['lawyer_number']; ?>

			</td>
		</tr>
		<tr>
			<th style="">氏名</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_order'][0]['student_name']; ?>

			</td>
		</tr>
		<tr>
			<th style="">登録年月日</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_order'][0]['regist_date']; ?>

			</td>
		</tr>
		<tr>
			<th style="">所属弁護士会</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_order'][0]['association_name']; ?>

			</td>
		</tr>
		<tr>
			<th style="">購入日</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_order'][0]['create_date']; ?>

			</td>
		</tr>
		<tr>
			<th style="">請求書</th>
			<td style="">
				<?php if ($this->_tpl_vars['arr_order'][0]['claim_flg'] == '1'): ?>
					希望する
				<?php else: ?>
					希望しない
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th style="">決済方法</th>
			<td style="">
				<?php if ($this->_tpl_vars['arr_order'][0]['payment_type'] == '1'): ?>
					カード
				<?php elseif ($this->_tpl_vars['arr_order'][0]['payment_type'] == '12'): ?>
					銀行振込
				<?php else: ?>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<script type="text/javascript">
	function non_download() {
		var atena = document.getElementById("atena").value;
		if (confirm("宛名は、以下のように発行されます。\n\n" + atena + "\n<?php echo $this->_tpl_vars['arr_order'][0]['student_name']; ?>
 様\n\n発行しても宜しいですか？")){
			document.getElementById('download_btn').disabled = true;
			window.document.downloadForm.submit();
		}
	}
</script>
<div id="download_div">
	<form name="downloadForm" action="pdf.php?oid=<?php echo $this->_tpl_vars['oid']; ?>
" method="post">
	宛名：<input type="text" id="atena" name="atena" size="28" />
	<a id="download_btn" href="javascript: void(0);" onclick="non_download();">領収書発行</a>
	</form>
</div>
<table class="list2">
	<form accept-charset="utf-8" method="post" name="list_form" action="#">
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="order_detail_id" value="">
	</form>
	<tr>
		<th style="width:80px;text-align:left;">商品コードID</th>
		<th style="width:160px;text-align:left;">商品名</th>
		<th style="width:80px;text-align:left;">金額</th>
		<th style="width:120px;text-align:left;">購入日</th>
		<th style="width:100px;text-align:left;">ステータス</th>
		<th style="width:;text-align:left;"></th>
	</tr>
	<?php if (is_array ( $this->_tpl_vars['arr_order_detail'] ) && count ( $this->_tpl_vars['arr_order_detail'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_order_detail']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<tr style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
		<td <?php if ($this->_tpl_vars['row']['payment_status'] != '9'): ?>class="tdc"<?php endif; ?> colspan="6" <?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>style="background-color:#363636;color:#FEFEFE;"<?php endif; ?>>
			<div>
				<div style="width:100%;float:left;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
					<div style="float:left;width:80px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
						<?php if ($this->_tpl_vars['row']['product_type_add'] == '1'): ?>
							<a href="../product/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
						<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '2'): ?>
							<a href="../product_live/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
						<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '3'): ?>
							<a href="../product_ethics/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
						<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '4'): ?>
							<a href="../product_passport/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
						<?php else: ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php endif; ?>
					</div>
					<div style="float:left;width:160px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>"><?php if ($this->_tpl_vars['row']['product_name_TOD'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['row']['product_name_TP'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></div>
					<div style="float:left;width:80px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
円</div>
					<div style="float:left;width:120px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['buy_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
						<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?>未入金
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?>未入金
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?>入金済
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '3'): ?>一部未入金
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '9'): ?>キャンセル
						<?php endif; ?>
					</div>
					<div style="float:left;width:;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
						<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?><!--未入金-->
							<input type="button" value="入金済に変更" onclick="document.list_form.mode.value='pay';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
							<input type="button" value="キャンセル" onclick="document.list_form.mode.value='cancel';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?><!--入金待ち-->
							<input type="button" value="入金済に変更" onclick="document.list_form.mode.value='pay';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
							<input type="button" value="キャンセル" onclick="document.list_form.mode.value='cancel';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?><!--入金済-->
							<input type="button" value="未入金に変更" onclick="document.list_form.mode.value='nopay';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '9'): ?><!--キャンセル-->
							<input type="button" value="キャンセルを取り消す" onclick="document.list_form.mode.value='nocancel';document.list_form.order_detail_id.value='<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_detail_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';document.list_form.submit();">
						<?php endif; ?>
					</div>
				</div>
				<div style="width:100%;float:left;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">入金日：</div>
					<div style="float:left;width:140px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
						<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?><!--未入金-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?><!--入金待ち-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?><!--入金済-->
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['receipt_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '3'): ?><!--一部未入金-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '9'): ?><!--キャンセル-->
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['receipt_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
（キャンセル）
						<?php endif; ?>
					</div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">権限付与日：</div>
					<div style="float:left;width:140px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>">
						<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?><!--未入金-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?><!--入金待ち-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?><!--入金済-->
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['take_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '3'): ?><!--一部未入金-->
						<?php elseif ($this->_tpl_vars['row']['payment_status'] == '9'): ?><!--キャンセル-->
						<?php endif; ?>
					</div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>"></div>
					<div style="float:left;width:;text-align:left;padding: 1px 3px;<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php endif; ?>"></div>
				</div>
			</div>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="6">
			<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
	<?php endif; ?>
</table>