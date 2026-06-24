<?php /* Smarty version 2.6.31, created on 2025-02-26 01:36:31
         compiled from /srv/alfproduct/smarty/templates/default/mypage/buy_detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/mypage/buy_detail.tpl', 28, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/default/mypage/buy_detail.tpl', 92, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/default/mypage/buy_detail.tpl', 87, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'mypage/side_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/buy_list.php" style="color:#5E4D34">購入履歴</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">購入履歴詳細</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">購入履歴</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたの購入履歴の内容を表示しています。<br />
			領収書は「発行」ボタンをクリックして下さい。<br />
			また、領収書は１回の決済につき一度に限り発行が可能ですので紛失等にご注意下さい。


			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">注文日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_buy']['order_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">購入手続き完了日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php if ($this->_tpl_vars['arr_buy']['payment_status'] == 2): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_buy']['payment_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>-<?php endif; ?></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">決済内容</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_buy']['disp_payment_type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">決済状況</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_buy']['disp_payment_status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">領収書発行</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;">
					<?php if ($this->_tpl_vars['arr_buy']['receipt_flg'] == 1): ?>
						<img src="/img/mypage/issue_btn_comp.png" alt="発行済" />
					<?php else: ?>
						<?php if ($this->_tpl_vars['arr_buy']['payment_type'] != 99): ?>
							<?php if ($this->_tpl_vars['arr_buy']['payment_status'] == 2): ?>
								<script type="text/javascript">
									function non_download() {
										var atena = document.getElementById("atena").value;
										if (confirm("宛名は、以下のように発行されます。\n\n" + atena + "\n<?php echo $_SESSION['user']['name']; ?>
 様\n\n発行しても宜しいですか？")){
											document.getElementById('download_btn').disabled = true;
											window.document.downloadForm.submit();
											document.getElementById('download_div').innerHTML = '<img src="/img/mypage/issue_btn_comp.png" alt="発行済" />';
										}
									}
								</script>
								<div id="download_div">
									<form name="downloadForm" action="receipt_download.php?oid=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_buy']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" method="post">
									宛名：<input type="text" id="atena" name="atena" size="28" />
									<a id="download_btn" href="javascript: void(0);" onclick="non_download();" ><img src="/img/mypage/issue_btn.png" style="vertical-align:middle;"></a>
									</form>
								</div>
							<?php else: ?>
								未発行
							<?php endif; ?>
						<?php else: ?>
						-
						<?php endif; ?>
					<?php endif; ?>
					</td>
				</tr>
			</table>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:70px;">入金確認</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:100px;">商品種別</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">商品名</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:100px;">小計(税込)</th>
				</tr>


				<?php $_from = $this->_tpl_vars['arr_buy_detail']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

				<tr style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php else: ?><?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?><?php endif; ?>">
					<td style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php else: ?><?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?><?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment_status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
					<td style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php else: ?><?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?><?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_product_type_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
					<td style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php else: ?><?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?><?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;"><?php if ($this->_tpl_vars['row']['del_flg'] === '0'): ?><?php if ($this->_tpl_vars['row']['link'] == '1'): ?><a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php endif; ?><?php if ($this->_tpl_vars['row']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['row']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?><?php if ($this->_tpl_vars['row']['link'] == '1'): ?></a><?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['row']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['row']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?><br />※掲載終了しました。<?php endif; ?></td>
					<td style="<?php if ($this->_tpl_vars['row']['payment_status'] == '9'): ?>background-color:#363636;color:#FEFEFE;<?php else: ?><?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?><?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:right;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
				<tr>
					<th colspan="3" style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:right;">合計</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:right;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['total_price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
				</tr>
			</table>
		</div>
	</div>
</div>