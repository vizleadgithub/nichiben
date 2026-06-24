<?php /* Smarty version 2.6.31, created on 2025-02-27 12:07:08
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture/info_user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user.tpl', 127, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user.tpl', 127, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_lecture/info_user.tpl', 187, false),)), $this); ?>
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
			ret = confirm("未受講に変更します。");
		} else {
			ret = confirm("受講済に変更します。");
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
function formSubmitFpFix(formName, mode, pid, aid){
	var ret = false;
	if (mode == "fp_fix"){
		ret = confirm("現在FP（当日FP）の欄を更新します");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
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
			<a href="csv.php?type=info_user_list&pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">受付用リスト作成</a>
			<a href="info_user_regist.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">個別登録</a>
			<a href="info_user_import.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['aid']; ?>
">CSV取り込み</a>
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
	<div style="padding-top:10px;text-align:right;">
		<input type="button" value="　パスポート状況更新　" onclick="formSubmitFpFix('list_form', 'fp_fix', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
);return false;" />
		<?php if ($this->_tpl_vars['disp_fp_fix_date'] != ''): ?>
			<div style="padding:5px 5px 0 0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['disp_fp_fix_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
		<?php endif; ?>
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
		<th>受講</th>
		<th>WEB</th>
		<th>登録番号</th>
		<th>氏名</th>
		<th>所属弁護士会</th>
		<th>申込FP</th>
		<?php if ($this->_tpl_vars['fp_fix_flg']): ?><th>当日FP</th><?php else: ?><th>現在FP</th><?php endif; ?>
		<th>価格</th>
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
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>">
		<?php if ($this->_tpl_vars['row']['payment_type'] == '99'): ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php else: ?>
			<?php if ($this->_tpl_vars['row']['payment_status_reserv'] != ''): ?>
				<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
, <?php echo $this->_tpl_vars['row']['order_detail_id']; ?>
, <?php echo $this->_tpl_vars['row']['payment_status_reserv']; ?>
);return false;" style="color:<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment_reserv_color'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment_reserv'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
			<?php else: ?>
				<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <?php echo $this->_tpl_vars['pid']; ?>
, <?php echo $this->_tpl_vars['aid']; ?>
, <?php echo $this->_tpl_vars['row']['order_detail_id']; ?>
, <?php echo $this->_tpl_vars['row']['payment_status']; ?>
);return false;" style="color:<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment_color'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_payment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
			<?php endif; ?>
		<?php endif; ?>
		</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>">
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
		</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_web'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['mtb_bar_association'][$this->_tpl_vars['row']['bar_association_id']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_fp'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_fp2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
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