<?php /* Smarty version 2.6.31, created on 2025-02-28 17:22:09
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture/info_user_import.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user_import.tpl', 70, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user_import.tpl', 70, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user_import.tpl', 168, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "upload"){
		var oid = document.forms[formName].elements['csv_upload'].value;
		if (oid.length == 0) {
			alert('ファイルを選択してください');
		}
		else {
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "regist"){
		ret = confirm("本当に登録してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == 'change'){
		ret = confirm("本当に受講済みにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
}
</script>

<!--
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>
-->

<!--<h2>検索する内容を入力してください</h2>-->

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th colspan="2">
			研修内容
			</th>
		</tr>
		<tr>
			<th>開催会</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input_2']['bar_association_name']; ?>

				&nbsp;
				<?php echo $this->_tpl_vars['arr_input_2']['bar_association_branch_name']; ?>

			</td>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input_2']['product_name']; ?>

			</td>
		</tr>
		<tr>
			<th>研修実施日</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input_2']['dates']; ?>

			</td>
		</tr>
		<tr>
			<th>料金（テキスト代含む）</th>
			<td style="width:70%">
				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input_2']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円
			</td>
		</tr>
		<tr>
			<th>定員</th>
			<td style="width:70%">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input_2']['entry_number'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 / <?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input_2']['capacity'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 人
			</td>
		</tr>
		<tr>
			<th>WEB申込</th>
			<td style="width:70%">
				<?php if ($this->_tpl_vars['arr_input_2']['web_flg'] == 1): ?>
					受け付ける
				<?php else: ?>
					受け付けない
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="form_csv_upload" enctype="multipart/form-data">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
">
<input type="hidden" name="aid" value="<?php echo $this->_tpl_vars['aid']; ?>
">
<input type="hidden" name="atype" value="<?php echo $this->_tpl_vars['atype']; ?>
">
<input type="hidden" name="oid" value="<?php echo $this->_tpl_vars['oid']; ?>
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

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
">
<input type="hidden" name="aid" value="<?php echo $this->_tpl_vars['aid']; ?>
">
<input type="hidden" name="atype" value="<?php echo $this->_tpl_vars['atype']; ?>
">
<input type="hidden" name="oid" value="<?php echo $this->_tpl_vars['oid']; ?>
">
<input type="hidden" name="ufnn" value="<?php echo $this->_tpl_vars['upload_file_new_name']; ?>
">
	<table class="list">
		<tr>
			<th>登録番号</th>
			<th>氏名</th>
			<th>所属会</th>
			<th>申込日</th>
			<th>取り込み日</th>
		</tr>

		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

		<tr style="">
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['lawyer_number']; ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['student_name']; ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['bar_association_name']; ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['entry_date']; ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['take_date']; ?>
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
	<a href="javascript:void(0);" onclick="window.location='info_user.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
';" /><img src="/alfproduct/images/btn_back.png"></a>
	<?php if ($this->_tpl_vars['mode'] == 'upload' && $this->_tpl_vars['err_msg'] == ""): ?>
		<input type="button" value="申込状況に追加する" onClick="formSubmit('list_form', 'regist');return false;" style="margin-bottom:13px;" />
	<input type="button" value="受講済みにする" onClick="formSubmit('list_form', 'change');return false;" style="margin-bottom:13px;" />
	<?php endif; ?>
</div>
<a name="page_bottom"></a>