<?php /* Smarty version 2.6.31, created on 2025-02-07 07:40:57
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture2/info_user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info_user.tpl', 133, false),array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info_user.tpl', 135, false),)), $this); ?>
<script type="text/javascript">

function formSubmit(formName, mode, pid, aid, odid){
	var ret = false;
	if (mode == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].submit();
		}
	}
}
function formSubmitParticipation(formName, mode, pid, aid, odid, flg){
	var ret = false;
	if (mode == "participation"){
		if (flg == 1){
			ret = confirm("未完了に変更します。");
		} else {
			ret = confirm("完了済に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].elements['flg'].value = flg;
			document.forms[formName].submit();
		}
	}
}
function formSubmitStatus(formName, mode, pid, aid, odid, flg){
	var ret = false;
	if (mode == "status"){
		if (flg == 1){
			ret = confirm("仮入金に変更します。");
		} else if (flg == 2){
			ret = confirm("未入金に変更します。");
		} else if (flg == 3){
			ret = confirm("支払済に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].elements['flg'].value = flg;
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
			<a href="csv.php?type=info_user&pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">CSV取得</a>
			<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
			<a href="csv.php?type=info_user_list&pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">受付用リスト作成</a>
			<a href="info_user_regist.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">個別登録</a>
			<a href="info_user_import.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">CSV取り込み</a>
			<?php endif; ?>
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

<form action="info_user.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="">
<input type="hidden" name="aid" value="">
<input type="hidden" name="atype" value="">
<input type="hidden" name="oid" value="">
<input type="hidden" name="odid" value="">
<input type="hidden" name="flg" value="">
<?php if ($this->_tpl_vars['res'] == 'success'): ?>
<h3 style="color:blue; font-weight:bold">更新しました</h3>
<?php elseif ($this->_tpl_vars['res'] == 'failed'): ?>
<h3 style="color:red; font-weight:bold">更新に失敗しました</h3>
<?php elseif ($this->_tpl_vars['res'] == 'success2'): ?>
<h3 style="color:blue; font-weight:bold">登録しました</h3>
<?php endif; ?>
<table class="list2">
	<tr>
		<th>受付日</th>
		<th>ステイタス</th>
		<th>完了</th>
		<th>登録番号</th>
		<th>氏名</th>
		<th>所属弁護士会</th>
		<th>削除</th>
	</tr>

	<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['create_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>">会場</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>">
		<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
			<?php if ($this->_tpl_vars['row']['participation_flg'] == '1'): ?>
				<a href="javascript:void(0);" onclick="formSubmitParticipation('list_form', 'participation', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
, <?php echo $this->_tpl_vars['row']['order_detail_id']; ?>
, <?php echo $this->_tpl_vars['row']['participation_flg']; ?>
);return false;" >済</a>
			<?php else: ?>
				<a href="javascript:void(0);" onclick="formSubmitParticipation('list_form', 'participation', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
, <?php echo $this->_tpl_vars['row']['order_detail_id']; ?>
, <?php echo $this->_tpl_vars['row']['participation_flg']; ?>
);return false;" >未</a>
			<?php endif; ?>
		<?php else: ?>
			<?php if ($this->_tpl_vars['row']['participation_flg'] == '1'): ?>
				済
			<?php else: ?>
				未
			<?php endif; ?>
		<?php endif; ?>
		</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['mtb_bar_association'][$this->_tpl_vars['row']['bar_association_id']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="javascript:void(0);" onclick="formSubmit('list_form', 'delete', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
, <?php echo $this->_tpl_vars['row']['order_detail_id']; ?>
);return false;" >削除</a></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</table>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>