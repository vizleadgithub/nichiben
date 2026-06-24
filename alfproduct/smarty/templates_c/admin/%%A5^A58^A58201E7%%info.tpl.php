<?php /* Smarty version 2.6.31, created on 2025-01-24 08:18:57
         compiled from /srv/alfproduct/smarty/templates/admin/product_ethics/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_ethics/info.tpl', 24, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/admin/product_ethics/info.tpl', 230, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_ethics/info.tpl', 293, false),)), $this); ?>
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
<?php $_from = $this->_tpl_vars['arr_term_id']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<input type="hidden" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['arr_input']['product_flg']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<input type="hidden" name="product_flg[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
		<th style="vertical-align:middle;">商品コード</th>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)</th>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
&nbsp;&nbsp;</li><?php endif; ?>
				<ul style="margin-left:15px;list-style-type:none;">
				<?php $_from = $this->_tpl_vars['row']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row2']):
?>
					<?php if (in_array ( $this->_tpl_vars['row2']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;</li><?php endif; ?>
					<ul style="margin-left:15px;list-style-type:none;">
					<?php $_from = $this->_tpl_vars['row2']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row3']):
?>
						<?php if (in_array ( $this->_tpl_vars['row3']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;</li><?php endif; ?>
						<ul style="margin-left:15px;list-style-type:none;">
						<?php $_from = $this->_tpl_vars['row3']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row4']):
?>
							<?php if (in_array ( $this->_tpl_vars['row4']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;</li><?php endif; ?>
							<ul style="margin-left:15px;list-style-type:none;">
							<?php $_from = $this->_tpl_vars['row4']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row5']):
?>
								<?php if (in_array ( $this->_tpl_vars['row5']['term_id'] , $this->_tpl_vars['arr_term_id'] )): ?><li>→→→→<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;</li><?php endif; ?>
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
		<th style="vertical-align:middle;">公開</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['publish_flg'] == '0'): ?>しない<?php endif; ?>
			<?php if ($this->_tpl_vars['arr_input']['publish_flg'] == '1'): ?>する<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">公開期間</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['start_date'] != '' && $this->_tpl_vars['arr_input']['end_date'] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php elseif ($this->_tpl_vars['arr_input']['start_date'] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php elseif ($this->_tpl_vars['arr_input']['end_date'] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php else: ?>
				未設定
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">購入後公開期間日数</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['open_period'] == '0'): ?>
				無制限に公開
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['open_period'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
日
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
				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_input']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品再生時間</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['play_time'] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品講師名</th>
		<td>
			<?php if ($this->_tpl_vars['arr_input']['teacher'] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">一括ダウンロード用資料</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['all_contents_download_before'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">年数</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['bar_association_year'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
年目
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修問題</th>
		<td>
			<?php echo $this->_tpl_vars['ethic_group'][$this->_tpl_vars['arr_input']['ethic_group_id']]; ?>

		</td>
	</tr>

<?php unset($this->_sections['contents_loop']);
$this->_sections['contents_loop']['name'] = 'contents_loop';
$this->_sections['contents_loop']['loop'] = is_array($_loop=$this->_tpl_vars['section_contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_loop']['start'] = (int)1;
$this->_sections['contents_loop']['show'] = true;
$this->_sections['contents_loop']['max'] = $this->_sections['contents_loop']['loop'];
$this->_sections['contents_loop']['step'] = 1;
if ($this->_sections['contents_loop']['start'] < 0)
    $this->_sections['contents_loop']['start'] = max($this->_sections['contents_loop']['step'] > 0 ? 0 : -1, $this->_sections['contents_loop']['loop'] + $this->_sections['contents_loop']['start']);
else
    $this->_sections['contents_loop']['start'] = min($this->_sections['contents_loop']['start'], $this->_sections['contents_loop']['step'] > 0 ? $this->_sections['contents_loop']['loop'] : $this->_sections['contents_loop']['loop']-1);
if ($this->_sections['contents_loop']['show']) {
    $this->_sections['contents_loop']['total'] = min(ceil(($this->_sections['contents_loop']['step'] > 0 ? $this->_sections['contents_loop']['loop'] - $this->_sections['contents_loop']['start'] : $this->_sections['contents_loop']['start']+1)/abs($this->_sections['contents_loop']['step'])), $this->_sections['contents_loop']['max']);
    if ($this->_sections['contents_loop']['total'] == 0)
        $this->_sections['contents_loop']['show'] = false;
} else
    $this->_sections['contents_loop']['total'] = 0;
if ($this->_sections['contents_loop']['show']):

            for ($this->_sections['contents_loop']['index'] = $this->_sections['contents_loop']['start'], $this->_sections['contents_loop']['iteration'] = 1;
                 $this->_sections['contents_loop']['iteration'] <= $this->_sections['contents_loop']['total'];
                 $this->_sections['contents_loop']['index'] += $this->_sections['contents_loop']['step'], $this->_sections['contents_loop']['iteration']++):
$this->_sections['contents_loop']['rownum'] = $this->_sections['contents_loop']['iteration'];
$this->_sections['contents_loop']['index_prev'] = $this->_sections['contents_loop']['index'] - $this->_sections['contents_loop']['step'];
$this->_sections['contents_loop']['index_next'] = $this->_sections['contents_loop']['index'] + $this->_sections['contents_loop']['step'];
$this->_sections['contents_loop']['first']      = ($this->_sections['contents_loop']['iteration'] == 1);
$this->_sections['contents_loop']['last']       = ($this->_sections['contents_loop']['iteration'] == $this->_sections['contents_loop']['total']);
?>
<?php $this->assign('contents_thumbnail_key', ((is_array($_tmp='contents_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
<?php $this->assign('contents_contents_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>
<?php $this->assign('contents_free_time_key', ((is_array($_tmp='contents_free_time')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
サムネイル画像</th>
		<td>
			<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_thumbnail_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
コンテンツ</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
無料公開範囲(秒)</th>
		<td>
		<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_free_time_key']] == '0'): ?>
			無料部分なし
		<?php else: ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_free_time_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
公開期間</th>
		<td>
			<?php $this->assign('contents_start_date_key', ((is_array($_tmp='contents_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php $this->assign('contents_end_date_key', ((is_array($_tmp='contents_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			
			
			<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_start_date_key']] != '' && $this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_end_date_key']] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_start_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_end_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php elseif ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_start_date_key']] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_start_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php elseif ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_end_date_key']] != ''): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_end_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php else: ?>
				未設定
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ概要<?php echo $this->_sections['contents_loop']['index']; ?>
</th>
		<td>
			<?php $this->assign('contents_memo_key', ((is_array($_tmp='contents_memo')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_memo_key']] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_memo_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師名<?php echo $this->_sections['contents_loop']['index']; ?>
</th>
		<td>
			<?php $this->assign('contents_teacher_key', ((is_array($_tmp='contents_teacher')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_teacher_key']] == ''): ?>
				未設定
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_teacher_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="#page_bottom">ページの下へ</a>
		</td>
	</tr>
	<?php unset($this->_sections['contents_download']);
$this->_sections['contents_download']['name'] = 'contents_download';
$this->_sections['contents_download']['loop'] = is_array($_loop=$this->_tpl_vars['section_contents_download']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_download']['start'] = (int)1;
$this->_sections['contents_download']['show'] = true;
$this->_sections['contents_download']['max'] = $this->_sections['contents_download']['loop'];
$this->_sections['contents_download']['step'] = 1;
if ($this->_sections['contents_download']['start'] < 0)
    $this->_sections['contents_download']['start'] = max($this->_sections['contents_download']['step'] > 0 ? 0 : -1, $this->_sections['contents_download']['loop'] + $this->_sections['contents_download']['start']);
else
    $this->_sections['contents_download']['start'] = min($this->_sections['contents_download']['start'], $this->_sections['contents_download']['step'] > 0 ? $this->_sections['contents_download']['loop'] : $this->_sections['contents_download']['loop']-1);
if ($this->_sections['contents_download']['show']) {
    $this->_sections['contents_download']['total'] = min(ceil(($this->_sections['contents_download']['step'] > 0 ? $this->_sections['contents_download']['loop'] - $this->_sections['contents_download']['start'] : $this->_sections['contents_download']['start']+1)/abs($this->_sections['contents_download']['step'])), $this->_sections['contents_download']['max']);
    if ($this->_sections['contents_download']['total'] == 0)
        $this->_sections['contents_download']['show'] = false;
} else
    $this->_sections['contents_download']['total'] = 0;
if ($this->_sections['contents_download']['show']):

            for ($this->_sections['contents_download']['index'] = $this->_sections['contents_download']['start'], $this->_sections['contents_download']['iteration'] = 1;
                 $this->_sections['contents_download']['iteration'] <= $this->_sections['contents_download']['total'];
                 $this->_sections['contents_download']['index'] += $this->_sections['contents_download']['step'], $this->_sections['contents_download']['iteration']++):
$this->_sections['contents_download']['rownum'] = $this->_sections['contents_download']['iteration'];
$this->_sections['contents_download']['index_prev'] = $this->_sections['contents_download']['index'] - $this->_sections['contents_download']['step'];
$this->_sections['contents_download']['index_next'] = $this->_sections['contents_download']['index'] + $this->_sections['contents_download']['step'];
$this->_sections['contents_download']['first']      = ($this->_sections['contents_download']['iteration'] == 1);
$this->_sections['contents_download']['last']       = ($this->_sections['contents_download']['iteration'] == $this->_sections['contents_download']['total']);
?>
		<tr<?php if ($this->_sections['contents_download']['index'] % 2 != 0): ?> style="background: none repeat scroll 0% 0% rgb(246, 246, 243);"<?php endif; ?>>
			<th style="vertical-align:middle;">ダウンロード<?php echo $this->_sections['contents_loop']['index']; ?>
-<?php echo $this->_sections['contents_download']['index']; ?>
</th>
			<td>
				<?php $this->assign('contents_download_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_download']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_download']['index']))); ?>
				<?php $this->assign('contents_download_before_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download_before')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_download']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_download']['index']))); ?>
				<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_key']] == ''): ?>
					未設定
				<?php else: ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_before_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<input type="button" value="ダウンロード" onclick="var w=window.open();w.location.href='<?php echo $this->_tpl_vars['document_path']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'" />
				<?php endif; ?>
			</td>
		</tr>
	<?php endfor; endif; ?>
<?php endfor; endif; ?>
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