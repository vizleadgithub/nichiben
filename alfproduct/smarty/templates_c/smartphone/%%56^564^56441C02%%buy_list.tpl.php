<?php /* Smarty version 2.6.27, created on 2013-12-26 11:58:20
         compiled from /srv/alfproduct/smarty/templates/smartphone/mypage/buy_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/mypage/buy_list.tpl', 26, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/smartphone/mypage/buy_list.tpl', 70, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/smartphone/mypage/buy_list.tpl', 66, false),)), $this); ?>
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
	<div style="float:left;margin-right:2px;color:#5E4D34;">購入履歴</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">購入履歴</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたの購入履歴を表示しています。領収書は決済ごとに発行が可能です。<br />
			領収書は「発行」ボタンをクリックして下さい。<br />
			また、領収書は１回の決済につき一度に限り発行が可能ですので紛失等にご注意下さい。<br />
			ブラウザの「戻る」や「閉じる」の操作を行うと，領収証が発行済みとなる場合がありますので御注意ください。
			<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:20px;padding: 0;">
				<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
					<?php if ($this->_tpl_vars['page_max'] == 0): ?>
						全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件
					<?php else: ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
					<?php endif; ?>
				</div>

				<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
					<?php echo $this->_tpl_vars['pager']; ?>

				</div>

				<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
					<script type="text/javascript">
					<!--
					function select_pagemax(){
						location.href = "" + "?pagemax=" + document.form_pagemax.pagemax.options[document.form_pagemax.pagemax.selectedIndex].value;
					}
					// -->
					</script>
					<form name="form_pagemax" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">
					<select name="pagemax">
						<option value="5"<?php if ($this->_tpl_vars['page_max'] == 5): ?> selected=selected<?php endif; ?>>5</option>
						<option value="10"<?php if ($this->_tpl_vars['page_max'] == 10): ?> selected=selected<?php endif; ?>>10</option>
						<option value="15"<?php if ($this->_tpl_vars['page_max'] == 15): ?> selected=selected<?php endif; ?>>15</option>
						<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
						<option value="0"<?php if ($this->_tpl_vars['page_max'] == 0): ?> selected=selected<?php endif; ?>>すべて</option>
					</select>件ずつ
					<a href="javascript: void(0)" onclick="select_pagemax();return false;" style="vertical-align:top;margin:0px;padding: 0;"><img src="/img/mypage/listing_btn.png" style="vertical-align:top;margin:0;padding-bottom: 8px;"></a>
					</form>
				</div>
			</div>

			<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:130px;">購入手続き完了日</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;">決済内容</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">合計(税込)</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:50px;">詳細</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">領収書</th>
				</tr>
				<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
				<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

				<tr style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>">
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><?php if ($this->_tpl_vars['val']['payment_status'] == 2): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['payment_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>-<?php endif; ?></td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php if ($this->_tpl_vars['val']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['val']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>掲載終了しました。<?php endif; ?></td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:right;vertical-align:middle;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['val']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><a href="/mypage/buy_detail.php?oid=<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#796A57;">詳細</a></td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">
						<?php if ($this->_tpl_vars['val']['receipt_flg'] == 1): ?>
							<img src="/img/mypage/issue_btn_comp.png">
						<?php else: ?>
							<?php if ($this->_tpl_vars['val']['payment_type'] != 99): ?>
								<?php if ($this->_tpl_vars['val']['payment_status'] == 2): ?>
									<script type="text/javascript">
										function non_download<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
() {
											if (confirm("宛名を指定して発行したい場合は、詳細ページから発行してください。\n\nこのページから発行した場合、宛名は「<?php echo $_SESSION['user']['name']; ?>
 様」となります。\n\nこのまま発行しても宜しいですか？")){
												document.getElementById('download_btn<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
').disabled = true;
												document.getElementById('download_div<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
').innerHTML = '<img src="/img/mypage/issue_btn_comp.png">';
												window.open("receipt_download.php?oid=<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", "", "width=10,height=10");
											}
										}
									</script>
									<div id="download_div<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
										<a id="download_btn<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" href="javascript: void(0);" onclick="non_download<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['order_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
();" ><img src="/img/mypage/issue_btn.png"></a>
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
				<?php endforeach; endif; unset($_from); ?>
			
			</table>

			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:10px;padding: 0;">
				<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
					<?php if ($this->_tpl_vars['page_max'] == 0): ?>
						全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件
					<?php else: ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
					<?php endif; ?>
				</div>

				<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
					<?php echo $this->_tpl_vars['pager']; ?>

				</div>

				<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
				</div>
			</div>
			<?php else: ?>
				<div class="nonProductMsg">
				購入履歴はありません。
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>