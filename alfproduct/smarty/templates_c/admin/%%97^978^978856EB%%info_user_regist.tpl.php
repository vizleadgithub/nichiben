<?php /* Smarty version 2.6.31, created on 2025-02-07 07:58:16
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture_ethics/info_user_regist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture_ethics/info_user_regist.tpl', 57, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "add"){
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
<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'add');return false;" >代替倫理研修権限を付与する</a>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'report');return false;" >レポートにする</a>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'end');return false;" >受講完了する</a>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>