<?php /* Smarty version 2.6.31, created on 2025-04-14 11:24:09
         compiled from /srv/alfproduct/smarty/templates/default/mypage/lesson_list2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/mypage/lesson_list2.tpl', 32, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/default/mypage/lesson_list2.tpl', 55, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/default/mypage/lesson_list2.tpl', 77, false),)), $this); ?>
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
	<div style="float:left;margin-right:2px;color:#5E4D34;">受講履歴(会場研修)</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">受講履歴(会場研修)</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたがこれまで受講した日弁連及び弁護士会主催の講座を表示しています。<br />
			なお、弁護士会が主催した研修については、弁護士会からの報告に基づき表示していますので予めご了承下さい。
			<div style="text-align:center;padding:20px;padding-left:120px;">
			<ul style="list-style:none;width:100%;margin:0 auto;">
				<li style="float:left;padding:0 30px;"><a href="/mypage/lesson_list1.php" style="color:#796A57;"><img src="/img/button/lesson1_btn_off.png" alt="eラーニング" /></a></li>
				<li style="float:left;padding:0 30px;"><a href="/mypage/lesson_list2.php" style="color:#796A57;"><img src="/img/button/lesson2_btn_on.png" alt="会場研修" /></a></li>
			</ul>
			<br style="clear:both;">
			</div>

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
			</div>
			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:20px;padding: 0;">
				<div style="font-size:14px;text-align:right; float:right;width:350px;vertical-align:top;margin-right:20px;padding: 0;">
					<script type="text/javascript">
					<!--
					function select_pagemax(){
						location.href = "" + "?pagemax=" + document.form_pagemax.pagemax.options[document.form_pagemax.pagemax.selectedIndex].value;
					}
					function sort_exe(){
						location.href = "" + "?pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
					}
					// -->
					</script>
										<form name="form_selects" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">
					<?php echo smarty_function_html_options(array('name' => 'sort','options' => $this->_tpl_vars['sort_select'],'selected' => $this->_tpl_vars['sort']), $this);?>
に
					<select name="pagemax">
						<option value="5"<?php if ($this->_tpl_vars['page_max'] == 5): ?> selected=selected<?php endif; ?>>5</option>
						<option value="10"<?php if ($this->_tpl_vars['page_max'] == 10): ?> selected=selected<?php endif; ?>>10</option>
						<option value="15"<?php if ($this->_tpl_vars['page_max'] == 15): ?> selected=selected<?php endif; ?>>15</option>
						<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
						<option value="0"<?php if ($this->_tpl_vars['page_max'] == 0): ?> selected=selected<?php endif; ?>>すべて</option>
					</select>件ずつ
					<a href="javascript: void(0)" onclick="sort_exe();return false;" style="vertical-align:top;margin:0px;padding: 0;"><img src="/img/mypage/listing_btn.png" style="vertical-align:top;margin:0;padding-bottom: 8px;"></a>
					</form>
				</div>
			</div>

			<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:70px;">受講状況</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;">講座名</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">開催日</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">主催会</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">詳細</th>
				</tr>
				<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
				<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

				<tr style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>">
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><?php if ($this->_tpl_vars['val']['participation_flg'] == 1): ?>済<?php else: ?>未<?php endif; ?></td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php if ($this->_tpl_vars['val']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['val']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>掲載終了しました。<?php endif; ?></td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><?php echo $this->_tpl_vars['val']['disp_sponsor']; ?>
</td>
					<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><?php if ($this->_tpl_vars['val']['koukai_flg']): ?><a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#796A57;">講座詳細へ</a><?php else: ?>受付終了<?php endif; ?></td>
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

				<div style="font-size:14px;text-align:right; float:right;width:350px;vertical-align:top;margin-right:20px;padding: 0;">
				</div>
			</div>
			<?php else: ?>
				<div class="nonProductMsg">
				受講履歴(会場研修)はありません。
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>