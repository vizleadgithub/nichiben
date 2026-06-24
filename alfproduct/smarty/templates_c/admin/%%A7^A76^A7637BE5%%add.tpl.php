<?php /* Smarty version 2.6.31, created on 2025-03-06 14:49:11
         compiled from /srv/alfproduct/smarty/templates/admin/product_live_branch/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_live_branch/add.tpl', 36, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/admin/product_live_branch/add.tpl', 170, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_live_branch/add.tpl', 280, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
  document.getElementById("act").value = formAct;
  document.forms[formName].action = formAction;
  document.forms[formName].submit();
}
</script>
<style type="text/css">
#bar_association_main_title{
  background-color:#fde9d9 !important;
  border-top:solid 1px #000000;
  font-weight:bold;
}
.bar_association_title{
  background-color:#dbe5f1 !important;
  border-top:solid 1px #000000;
  border-bottom:solid 1px #000000;
}
</style>
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
		<th style="vertical-align:middle;width:200px;">研修種別</th>
		<td colspan = "3">
			<?php echo $this->_tpl_vars['mtb_live_training_type'][$this->_tpl_vars['arr_input']['training_kind_flg']]; ?>

			<input type="hidden" name="training_kind_flg" id="training_kind_flg" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['training_kind_flg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修</th>
		<td colapan="3">
			<?php if ($this->_tpl_vars['arr_input']['ethic_flg'] == 0): ?>
			倫理研修対象としない
			<?php endif; ?>
			<?php if ($this->_tpl_vars['arr_input']['ethic_flg'] == 1): ?>
			倫理研修対象とする
			<?php endif; ?>
			<input type="hidden" name="ethic_flg" id="ethic_flg" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['ethic_flg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受付期間</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;～&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="start_date" id="start_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="hidden" name="end_date" id="end_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講料振り込み期限</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['limit_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="limit_date" id="limit_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['limit_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">開催日</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="dates" id="dates" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講票ダウンロード</th>
		<td colapan="3">
			<?php if ($this->_tpl_vars['arr_input']['download_flg'] == 1): ?>
			可
			<?php endif; ?>
			<?php if ($this->_tpl_vars['arr_input']['download_flg'] == 0): ?>
			不可
			<?php endif; ?>
			<input type="hidden" name="download_flg" id="download_flg" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['download_flg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">主催</th>
		<td colapan="3">
			<?php $_from = $this->_tpl_vars['arr_input']['bar_association_sponsor']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
			・<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
			<input type="hidden" name="bar_association_sponsor[]" id="bar_association_sponsor" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<?php endforeach; endif; unset($_from); ?>
			<?php $_from = $this->_tpl_vars['arr_input']['bar_association_sponsor_unselect']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
			<input type="hidden" name="bar_association_sponsor_unselect[]" id="bar_association_sponsor_unselect" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<?php endforeach; endif; unset($_from); ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象者</th>
		<td colapan="3">
			<?php echo $this->_tpl_vars['mtb_live_target_flg'][$this->_tpl_vars['arr_input']['target_flg']]; ?>

			<input type="hidden" name="target_flg" id="target_flg" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['target_flg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象</th>
		<td colapan="3">
			<?php if ($this->_tpl_vars['arr_input']['all_bar_association_target'] == 1): ?>
				すべての弁護士会を対象とする
				<input type="hidden" name="all_bar_association_target" id="all_bar_association_target" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['all_bar_association_target'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<?php else: ?>
				<?php $_from = $this->_tpl_vars['arr_input']['bar_association_target']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
				・<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
				<input type="hidden" name="bar_association_target[]" id="bar_association_target" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<?php endforeach; endif; unset($_from); ?>
				<?php $_from = $this->_tpl_vars['arr_input']['bar_association_target_unselect']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
				<input type="hidden" name="bar_association_target_unselect[]" id="bar_association_target_unselect" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修名</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="product_name" id="product_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="product_code" id="product_code" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修の内容</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="memo1" id="memo1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講義タイトル、講師名</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="memo2" id="memo2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">日時詳細</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo3'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="memo3" id="memo3" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo3'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">会場について</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['hall'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="hall" id="hall" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['hall'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">定員</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['capacity'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="capacity" id="capacity" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['capacity'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">単品料金(税込)</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="price" id="price" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問い合わせ先</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo4'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="memo4" id="memo4" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo4'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講資格/他会員の受講等</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo5'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="memo5" id="memo5" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo5'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">備考</th>
		<td>
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<input type="hidden" name="contents" id="contents" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品カテゴリ</th>
		<td>
			<ul style="list-style-type:none;">
			<?php $_from = $this->_tpl_vars['arr_category']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<?php if (in_array ( $this->_tpl_vars['row']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></li><?php endif; ?>
				<ul style="margin-left:15px;list-style-type:none;">
				<?php $_from = $this->_tpl_vars['row']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row2']):
?>
					<?php if (in_array ( $this->_tpl_vars['row2']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></li><?php endif; ?>
					<ul style="margin-left:15px;list-style-type:none;">
					<?php $_from = $this->_tpl_vars['row2']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row3']):
?>
						<?php if (in_array ( $this->_tpl_vars['row3']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></li><?php endif; ?>
						<ul style="margin-left:15px;list-style-type:none;">
						<?php $_from = $this->_tpl_vars['row3']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row4']):
?>
							<?php if (in_array ( $this->_tpl_vars['row4']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></li><?php endif; ?>
							<ul style="margin-left:15px;list-style-type:none;">
							<?php $_from = $this->_tpl_vars['row4']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row5']):
?>
								<?php if (in_array ( $this->_tpl_vars['row5']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></li><?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							</ul>
						<?php endforeach; endif; unset($_from); ?>
						</ul>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
				<?php endforeach; endif; unset($_from); ?>
				</ul>
			<?php endforeach; endif; unset($_from); ?>
			</ul>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<?php if (isset ( $this->_tpl_vars['arr_input']['thumbnail'] ) && $this->_tpl_vars['arr_input']['thumbnail'] != ""): ?>
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<?php endif; ?>
		</td>
	</tr>
	
	<tr>
		<th colspan="4" id="bar_association_main_title">実施弁護士会</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_bar_association']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['val']):
?>
		<?php if ($this->_tpl_vars['bar_association_id'] == $this->_tpl_vars['login_bar_association_id']): ?>
			<tr>
				<td colspan="4" class="bar_association_title"><?php echo $this->_tpl_vars['val']['name']; ?>
</td>
			</tr>
			<?php $_from = $this->_tpl_vars['val']['branch_info']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['branch']):
?>
			<?php $this->assign('branch_id', $this->_tpl_vars['branch']['id']); ?>
			<?php $this->assign('capacity', ((is_array($_tmp='capacity')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('hall', ((is_array($_tmp='hall')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('receptionist_start_date', ((is_array($_tmp='receptionist_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('receptionist_end_date', ((is_array($_tmp='receptionist_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('dates', ((is_array($_tmp='dates')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('web_flg', ((is_array($_tmp='web_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('contents', ((is_array($_tmp='contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('entry_number', ((is_array($_tmp='entry_number')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<tr>
				<td>
					<?php echo $this->_tpl_vars['branch']['name']; ?>

				</td>
				<td>
					定員<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['capacity']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['capacity']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength="4"/><br />
					会場<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['hall']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['hall']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
					受付<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['receptionist_start_date']; ?>
" id="<?php echo $this->_tpl_vars['receptionist_start_date']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['receptionist_start_date']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px;" />～<input type="text" name="<?php echo $this->_tpl_vars['receptionist_end_date']; ?>
" id="<?php echo $this->_tpl_vars['receptionist_end_date']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['receptionist_end_date']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px;" /><br />
					実施日<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['dates']; ?>
" id="<?php echo $this->_tpl_vars['dates']; ?>
" class="calendar" value="<?php echo $this->_tpl_vars['arr_input'][$this->_tpl_vars['dates']]; ?>
" /><br />
					<?php if ($this->_tpl_vars['disp_web_flg']): ?>
					Web申込<span style="color:red;">※</span>：<label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="1" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '1'): ?>checked<?php endif; ?> />WEB申込可(研修を実施する)</label><label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="0" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '0'): ?>checked<?php endif; ?> />WEB申込不可(研修を実施する)</label><label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="2" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '2'): ?>checked<?php endif; ?> />研修を実施しない</label><br />
					<?php endif; ?>
					現状申込数：<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['entry_number']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
					備考：<textarea name="<?php echo $this->_tpl_vars['contents']; ?>
" style="width:400px;height:100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
				</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>