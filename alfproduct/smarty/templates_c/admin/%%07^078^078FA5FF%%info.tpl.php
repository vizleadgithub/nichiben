<?php /* Smarty version 2.6.31, created on 2025-01-24 11:03:42
         compiled from /srv/alfproduct/smarty/templates/admin/product_passport/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_passport/info.tpl', 24, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_passport/info.tpl', 78, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
	var ret = true;
	if (formAct == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
	}
	if (ret == true){
		document.getElementById("act").value = formAct;
		document.forms[formName].action = formAction;
		document.forms[formName].submit();
	}
}
function searchButton(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1024,height=980");
}
</script>

<h2>商品の内容を確認</h2>

<form name="form1" action="#" method="post">
<input type="hidden" name="mid" id="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
" />
<input type="hidden" name="act" id="act" value="" />
<?php $_from = $this->_tpl_vars['arr_input']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<input type="hidden" name="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['arr_input']['passport_target']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<input type="hidden" name="passport_target[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php endforeach; endif; unset($_from); ?>

<table class="form">
	<tr>
		<th style="vertical-align:middle;width:200px;">商品ID</th>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['mid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品名</th>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)</th>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">対象者</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['passport_target'] != ""): ?>
			<?php $_from = $this->_tpl_vars['arr_input']['passport_target']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
				・<?php echo $this->_tpl_vars['arr_passport_target'][$this->_tpl_vars['val']]; ?>
<br />
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['thumbnail'] == ''): ?>
				未設定
			<?php else: ?>
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['memo'] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	
<?php unset($this->_sections['related_products']);
$this->_sections['related_products']['name'] = 'related_products';
$this->_sections['related_products']['loop'] = is_array($_loop=$this->_tpl_vars['section_related_products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['related_products']['start'] = (int)1;
$this->_sections['related_products']['show'] = true;
$this->_sections['related_products']['max'] = $this->_sections['related_products']['loop'];
$this->_sections['related_products']['step'] = 1;
if ($this->_sections['related_products']['start'] < 0)
    $this->_sections['related_products']['start'] = max($this->_sections['related_products']['step'] > 0 ? 0 : -1, $this->_sections['related_products']['loop'] + $this->_sections['related_products']['start']);
else
    $this->_sections['related_products']['start'] = min($this->_sections['related_products']['start'], $this->_sections['related_products']['step'] > 0 ? $this->_sections['related_products']['loop'] : $this->_sections['related_products']['loop']-1);
if ($this->_sections['related_products']['show']) {
    $this->_sections['related_products']['total'] = min(ceil(($this->_sections['related_products']['step'] > 0 ? $this->_sections['related_products']['loop'] - $this->_sections['related_products']['start'] : $this->_sections['related_products']['start']+1)/abs($this->_sections['related_products']['step'])), $this->_sections['related_products']['max']);
    if ($this->_sections['related_products']['total'] == 0)
        $this->_sections['related_products']['show'] = false;
} else
    $this->_sections['related_products']['total'] = 0;
if ($this->_sections['related_products']['show']):

            for ($this->_sections['related_products']['index'] = $this->_sections['related_products']['start'], $this->_sections['related_products']['iteration'] = 1;
                 $this->_sections['related_products']['iteration'] <= $this->_sections['related_products']['total'];
                 $this->_sections['related_products']['index'] += $this->_sections['related_products']['step'], $this->_sections['related_products']['iteration']++):
$this->_sections['related_products']['rownum'] = $this->_sections['related_products']['iteration'];
$this->_sections['related_products']['index_prev'] = $this->_sections['related_products']['index'] - $this->_sections['related_products']['step'];
$this->_sections['related_products']['index_next'] = $this->_sections['related_products']['index'] + $this->_sections['related_products']['step'];
$this->_sections['related_products']['first']      = ($this->_sections['related_products']['iteration'] == 1);
$this->_sections['related_products']['last']       = ($this->_sections['related_products']['iteration'] == $this->_sections['related_products']['total']);
?>
	<tr<?php if ($this->_sections['related_products']['index'] % 2 != 0): ?> style="background: none repeat scroll 0% 0% rgb(246, 246, 243);"<?php endif; ?>>
		<th style="vertical-align:middle;">関連商品<?php echo $this->_sections['related_products']['index']; ?>
</th>
		<td>
			<?php $this->assign('related_products_key', ((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index']))); ?>
			<?php $this->assign('related_products_name_key', ((is_array($_tmp=((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>
			<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_key']] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
<?php endfor; endif; ?>
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'edit');return false;" /><img src="/alfproduct/images/btn_revise.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'info.php', 'delete');return false;" /><img src="/alfproduct/images/btn_delete.png"></a>
</div>
</form>
<a name="page_bottom"></a>