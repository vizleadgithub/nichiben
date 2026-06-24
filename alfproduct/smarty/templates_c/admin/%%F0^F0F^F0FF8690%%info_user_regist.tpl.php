<?php /* Smarty version 2.6.31, created on 2025-02-28 17:20:47
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture/info_user_regist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user_regist.tpl', 59, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user_regist.tpl', 59, false),)), $this); ?>
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