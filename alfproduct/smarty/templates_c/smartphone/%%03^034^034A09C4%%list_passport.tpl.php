<?php /* Smarty version 2.6.27, created on 2013-12-26 12:00:22
         compiled from /srv/alfproduct/smarty/templates/smartphone/product/list_passport.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/product/list_passport.tpl', 14, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/smartphone/product/list_passport.tpl', 76, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/product/list_passport.tpl', 84, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/smartphone/product/list_passport.tpl', 38, false),)), $this); ?>
<?php echo $this->_tpl_vars['pankuzu']; ?>


<?php if ($_SESSION['user']['presence_passport'] == '1'): ?>
	<div class="nonProductMsg">
	既に研修パスポートを所持しています。
	</div>
	
<?php else: ?>
	
	<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
		<div class="pager">
			<div style="text-align:left; float:left;width:240px;">
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
			<div style="text-align:right; float:right;width:240px;">
				<?php echo $this->_tpl_vars['pager']; ?>

			</div>
		</div>



	<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;">
		<h2>パスポート</h2>
		<div class="selects">
			<form name="form_selects">
				<script type="text/javascript">
				<!--
				function sort_exe(){
					location.href = "" + "?pcid=<?php echo $this->_tpl_vars['pcid']; ?>
&pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
				}
				// -->
				</script>
				<span style="position:relative;bottom:5px;right:10px;">
				<?php echo smarty_function_html_options(array('name' => 'sort','options' => $this->_tpl_vars['sort_select'],'selected' => $this->_tpl_vars['sort']), $this);?>
に
				<select name="pagemax">
					<option value="5"<?php if ($this->_tpl_vars['page_max'] == 5): ?> selected=selected<?php endif; ?>>5</option>
					<option value="10"<?php if ($this->_tpl_vars['page_max'] == 10): ?> selected=selected<?php endif; ?>>10</option>
					<option value="15"<?php if ($this->_tpl_vars['page_max'] == 15): ?> selected=selected<?php endif; ?>>15</option>
					<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
					<option value="0"<?php if ($this->_tpl_vars['page_max'] == 0): ?> selected=selected<?php endif; ?>>すべて</option>
				</select>件ずつ
				<a href="javascript:void(0);" onClick="sort_exe()"><img src="/img/list/permutation_btn.png" alt="並替え" style="position:relative;top:8px;" /></a>
				</span>
			</form>
		</div>
	</div>







		<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<div class="list_box" style="width:468px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="float:left;width:160px;height:115px;text-align:center;background-color:#99e5fd;padding:0;" class="thumb">
				<?php if ($this->_tpl_vars['row']['thumbnail'] != ""): ?>
					<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo $this->_tpl_vars['row']['thumbnail']; ?>
&width=155&height=85" alt="" style="margin-top:5px;" /></a>
				<?php else: ?>
					<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="margin-top:5px;" /></a>
				<?php endif; ?>
			</div>
			
			<div style="text-align:left;margin-left:180px;">
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#523d26;font-size:14px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
			</div>
			
			<div style="float:right;width:290px">
				<table>
					<tr style="background-color:#f5f8ef;">
						<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">単品価格</th>
						<td style="text-align:left;vertical-align:top;width:210px;"><?php if ($this->_tpl_vars['row']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円(税込)<?php endif; ?></td>
					</tr>
					<tr>
						<td colspan="2" style="height:70px;">&nbsp;</td>
					</tr>
				</table>
				<div style="float:right;width:460px;padding:5px;">
					<div style="text-align:left;width:100%;">
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

					</div>
					<div style="text-align:right;width:100%;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">商品詳細へ</a>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; endif; unset($_from); ?>
		


		<div class="pager">
			<div style="text-align:left; float:left;width:240px;">
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
			<div style="text-align:right; float:right;width:240px;">
				<?php echo $this->_tpl_vars['pager']; ?>

			</div>
		</div>
	<?php else: ?>
		<div class="nonProductMsg">
		該当パスポートが登録されていません。
		</div>
	<?php endif; ?>
	
<?php endif; ?>