<?php /* Smarty version 2.6.31, created on 2025-01-24 11:03:14
         compiled from /srv/alfproduct/smarty/templates/admin/product_passport/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_passport/add.tpl', 37, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_passport/add.tpl', 93, false),array('function', 'html_checkboxes', '/srv/alfproduct/smarty/templates/admin/product_passport/add.tpl', 66, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
  document.getElementById("act").value = formAct;
  document.forms[formName].action = formAction;
  document.forms[formName].submit();
}
function searchButton(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1024,height=980");
}
function delete_live_training_product_id(){
	document.form1.live_training_product_id.value='';
	document.form1.live_training_product_name.value='';
	document.getElementById("spa_live_training_product_id").innerText = '';
	if (typeof document.getElementById("spa_live_training_product_id").textContent!= "undefined") {
		document.getElementById("spa_live_training_product_id").textContent = '';
	}
}
</script>

<h2>商品の内容を入力してください</h2>

<?php if (! empty ( $this->_tpl_vars['err_msg'] )): ?>
<div class="error">
<?php $_from = $this->_tpl_vars['err_msg']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
	<?php echo $this->_tpl_vars['msg']; ?>
<br />
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>
<form name="form1" action="add.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" id="act" value="confirm" />

<table class="form">
	<?php if (isset ( $this->_tpl_vars['arr_input']['mid'] )): ?>
	<tr>
		<th style="vertical-align:middle;">商品ID</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['mid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="mid" id="mid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['mid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<th style="vertical-align:middle;">商品名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)<span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="price" id="price" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">対象者<span style="color:red;">※</span></th>
		<td>
			<?php echo smarty_function_html_checkboxes(array('name' => 'passport_target','options' => $this->_tpl_vars['arr_passport_target'],'checked' => ((is_array($_tmp=$this->_tpl_vars['arr_input']['passport_target'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)),'separator' => '<br />'), $this);?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<?php if (isset ( $this->_tpl_vars['arr_input']['thumbnail'] ) && $this->_tpl_vars['arr_input']['thumbnail'] != ""): ?>
				<br />
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<textarea name="memo" id="memo" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
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
	<tr>
		<th style="vertical-align:middle;">関連商品<?php echo $this->_sections['related_products']['index']; ?>
</th>
		<td>
			<?php $this->assign('related_products_key', ((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index']))); ?>
			<?php $this->assign('related_products_name_key', ((is_array($_tmp=((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>
			<span id="spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
			<input type="hidden" name="<?php echo $this->_tpl_vars['related_products_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['related_products_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="hidden" name="<?php echo $this->_tpl_vars['related_products_name_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['related_products_name_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="button" value="検索" onclick="searchButton('search_product.php?gid=<?php echo $this->_tpl_vars['related_products_key']; ?>
')" />
			<!--<div id="delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
_link">--><a href="javascript:void(0);" onclick="delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
()">削除</a><!--<div>-->
			<script type="text/javascript">
			function delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
(){
				document.form1.<?php echo $this->_tpl_vars['related_products_key']; ?>
.value='';
				document.form1.<?php echo $this->_tpl_vars['related_products_name_key']; ?>
.value='';
				document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").innerText = '';
				if (typeof document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").textContent!= "undefined") {
					document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").textContent = '';
				}
				//document.getElementById("delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
_link").style.display="none";
			}
			</script>

		</td>
	</tr>
<?php endfor; endif; ?>
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>