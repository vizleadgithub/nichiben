<?php /* Smarty version 2.6.31, created on 2025-02-28 18:32:13
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture_ethics/info_user_import.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture_ethics/info_user_import.tpl', 54, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "upload"){
		var csv_upload_value = document.forms[formName].elements['csv_upload'].value;
		if (csv_upload_value.length == 0) {
			alert('ファイルを選択してください');
		}
		else {
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "add"){
		ret = confirm("本当に代替倫理研修権限を付与してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "report"){
		ret = confirm("本当にレポートにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == 'end'){
		ret = confirm("本当に受講済みにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
}
</script>


<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input_2']['product_name']; ?>

			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="form_csv_upload" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
	<div style="color:red;">
		<?php echo $this->_tpl_vars['err_msg']; ?>

	</div>
	<table class="list">
		<tr>
			<th>ファイルをアップロードしてcsv申込を取り込みます</th>
		</tr>
	</table>
	<table>
		<tr style="">
			<td>
				<input type="file" name="csv_upload" size="30">
				<a href="javascript:void(0);" onclick="formSubmit('form_csv_upload', 'upload');return false;" >アップロードする</a>
			</td>
		</tr>
	</table>
</form>

<?php if ($this->_tpl_vars['mode'] == 'upload' && $this->_tpl_vars['err_msg'] == ""): ?>
<table>
	<tr style="">
		<td>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'add');return false;" >代替倫理研修権限を付与する</a>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'report');return false;" >レポートにする</a>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'end');return false;" >受講済みにする</a>
		</td>
	</tr>
</table>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<input type="hidden" name="ufnn" value="<?php echo $this->_tpl_vars['upload_file_new_name']; ?>
">
	<table class="list">
		<tr>
			<th>登録番号</th>
			<th>氏名</th>
			<th>申込日</th>
			<th>取り込み日</th>
		</tr>

		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<tr style="">
			<td class="tdc" style=""><?php echo $this->_tpl_vars['row']['lawyer_number']; ?>
</td>
			<td class="tdc" style=""><?php echo $this->_tpl_vars['row']['student_name']; ?>
</td>
			<td class="tdc" style=""><?php echo $this->_tpl_vars['row']['entry_date']; ?>
</td>
			<td class="tdc" style=""><?php echo $this->_tpl_vars['row']['take_date']; ?>
</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>

	</table>
</form>
<?php endif; ?>

<?php if ($this->_tpl_vars['res_msg'] != ""): ?>
<div>
	<?php echo $this->_tpl_vars['res_msg']; ?>

</div>
<?php endif; ?>






<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>