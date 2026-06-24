<?php /* Smarty version 2.6.31, created on 2024-12-20 16:04:01
         compiled from /srv/alfproduct/smarty/templates/admin/inquiry/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/inquiry/index.tpl', 48, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>投稿日時</th>
			<td>
				<input type="text" name="search_start_date" id="start_date" value="<?php echo $this->_tpl_vars['search_start_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<?php echo $this->_tpl_vars['search_end_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td>
				<input type="text" name="search_keyword" size="45" value="<?php echo $this->_tpl_vars['search_keyword']; ?>
">
			</td>
		</tr>
		<tr>
			<th>ステータス</th>
			<td>
				<input type="radio" name="search_status" id="search_status0" value=""  <?php if ($this->_tpl_vars['search_status'] == ""): ?>checked="checked"<?php endif; ?>><label for="search_status0">全て</label>　
				<input type="radio" name="search_status" id="search_status1" value="0" <?php if ($this->_tpl_vars['search_status'] == '0'): ?>checked="checked"<?php endif; ?>><label for="search_status1">新規</label>　
								<input type="radio" name="search_status" id="search_status4" value="3" <?php if ($this->_tpl_vars['search_status'] == '3'): ?>checked="checked"<?php endif; ?>><label for="search_status4">対応中</label>　
				<input type="radio" name="search_status" id="search_status3" value="2" <?php if ($this->_tpl_vars['search_status'] == '2'): ?>checked="checked"<?php endif; ?>><label for="search_status3">対応済</label>　
			</td>
		</tr>
		<tr>
			<th>登録番号</th>
			<td>
				<input type="text" name="search_lawyer_number" size="45" value="<?php echo $this->_tpl_vars['search_lawyer_number']; ?>
">
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>登録番号</th>
		<th>お名前</th>
		<th>メールアドレス</th>
		<th style="width:110px;">状態</th>
		<th style="width:110px;">対応者</th>
		<th style="width:133px;">投稿日<br>(返信日)</th>
	</tr>
	<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<tr style="">
		<td class="tdc" ><a href="info.php?iid=<?php echo $this->_tpl_vars['row']['inquiry_id']; ?>
"><?php echo $this->_tpl_vars['row']['inquiry_id']; ?>
</a></td>
		<td class="tdc" ><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['inquiry_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" ><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['inquiry_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" ><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['inquiry_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
 		<td class="tdc" >
			<?php if ($this->_tpl_vars['row']['inquiry_status'] == '3'): ?>対応中
			<?php elseif ($this->_tpl_vars['row']['inquiry_status'] == '2'): ?>対応済
			<?php elseif ($this->_tpl_vars['row']['inquiry_status'] == '1'): ?>返信済
			<?php elseif ($this->_tpl_vars['row']['inquiry_status'] == '0'): ?><span style="color:#ff0000;"><b>新規</b></span>
			<?php else: ?><span style="color:#ff0000;"><b>新規</b></span>
			<?php endif; ?>
		</td>
		<td class="tdc" ><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" >
			<?php echo $this->_tpl_vars['row']['regist_date']; ?>

			<?php if ($this->_tpl_vars['row']['inquiry_status'] == '1'): ?><br>(<?php echo $this->_tpl_vars['row']['update_date']; ?>
)<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="7">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
	<?php endif; ?>
</table>