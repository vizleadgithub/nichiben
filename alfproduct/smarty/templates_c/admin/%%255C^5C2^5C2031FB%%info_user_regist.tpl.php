<?php /* Smarty version 2.6.31, created on 2025-02-07 07:41:09
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture2/info_user_regist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info_user_regist.tpl', 72, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "regist"){
		var lawyer_numbers = document.forms[formName].elements['lawyer_numbers'].value;
		if (lawyer_numbers.length == 0) {
			alert('追加する登録番号を入力してください');
		}
		else {
			ret = confirm("本当に登録してもよろしいですか？");
			if (ret == true){
				document.forms[formName].elements['mode'].value = mode;
				document.forms[formName].submit();
			}
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
			<th>研修名</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input_2']['product_name']; ?>

			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>

<form action="info_user_regist.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
">
<input type="hidden" name="aid" value="<?php echo $this->_tpl_vars['aid']; ?>
">
<input type="hidden" name="atype" value="<?php echo $this->_tpl_vars['atype']; ?>
">



<?php if ($this->_tpl_vars['res_msg'] != ""): ?>
<div style="color:red;">
	<?php echo $this->_tpl_vars['res_msg']; ?>

</div>
<?php endif; ?>


<table class="list">
	<tr>
		<th>追加登録番号入力</th>
	</tr>
</table>
<table>
	<tr style="">
		<td>
			<textarea name="lawyer_numbers" style="width:250px; height:300px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lawyer_numbers'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
</table>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'regist');return false;" >追加する</a>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info_user.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>