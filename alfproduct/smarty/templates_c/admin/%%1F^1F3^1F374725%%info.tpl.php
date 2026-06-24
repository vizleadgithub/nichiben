<?php /* Smarty version 2.6.31, created on 2025-01-24 10:42:40
         compiled from /srv/alfproduct/smarty/templates/admin/inquiry/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/inquiry/info.tpl', 11, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/admin/inquiry/info.tpl', 35, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>


	<h2>お問い合わせの内容を確認</h2>
	<table class="form">
		<?php if ($this->_tpl_vars['iid'] != ""): ?>
		<tr>
			<th style="width:120px;">ID</th>
			<td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['iid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>投稿日</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>お名前</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>お問い合わせ内容</th>
			<td style="">
				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</td>
		</tr>
	</table>

	<?php if ($this->_tpl_vars['arr_data']['inquiry_status'] == '1'): ?>
	<h2>返信内容を確認</h2>
	<table class="form">
		<tr>
			<th style="width:120px;">ステータス</th>
			<td style="">
				<?php if ($this->_tpl_vars['arr_data']['inquiry_status'] == '3'): ?>対応中
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '2'): ?>対応済
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '1'): ?>返信済
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '0'): ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php else: ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th>返信日時</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['update_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>対応者</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th style="width:120px;">件名</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['return_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>本文</th>
			<td style="">
				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_data']['return_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</td>
		</tr>
	</table>
	<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '2' || $this->_tpl_vars['arr_data']['inquiry_status'] == '3'): ?>
	<h2>返信内容を確認</h2>
	<table class="form">
		<tr>
			<th style="width:120px;">ステータス</th>
			<td style="">
				<?php if ($this->_tpl_vars['arr_data']['inquiry_status'] == '3'): ?>対応中
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '2'): ?>対応済
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '1'): ?>返信済
				<?php elseif ($this->_tpl_vars['arr_data']['inquiry_status'] == '0'): ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php else: ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th>対応者</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
	</table>
	<?php endif; ?>

	<div class="submit">
		<?php if ($this->_tpl_vars['arr_data']['inquiry_status'] == '0' || $this->_tpl_vars['arr_data']['inquiry_status'] == '3'): ?>
		<script type="text/javascript">
			
			function delete_item(msg){
				if(window.confirm( msg )){
					location.href = "delete.php?iid=<?php echo $this->_tpl_vars['iid']; ?>
";
				}
			}
			function edit_item(){
				location.href ="edit.php?iid=<?php echo $this->_tpl_vars['iid']; ?>
";
			}
			function status_item(){
				location.href ="status.php?iid=<?php echo $this->_tpl_vars['iid']; ?>
";
			}
		</script>
		<form>
		<div style="width:45%;float:left;text-align:right;">
			<a href="javascript:void(0);" onclick="location.href ='index.php?page=<?php echo $this->_tpl_vars['page']; ?>
';return false;" /><img src="/alfproduct/images/btn_back.png"></a>
		</div>
		<div style="width:10%;float:left;text-align:center;">&nbsp;
					</div>
		<div style="width:45%;float:left;text-align:left;">
			<a href="javascript:void(0);" onclick="status_item();return false;" /><img src="/alfproduct/images/btn_status.png"></a>
		</div>
				</form>
		<?php else: ?>
		<script type="text/javascript">
			
			function delete_item(msg){
				if(window.confirm( msg )){
					location.href = "delete.php?iid=<?php echo $this->_tpl_vars['mid']; ?>
";
				}
			}
			
			function edit_item(){
				location.href ="edit.php?iid=<?php echo $this->_tpl_vars['iid']; ?>
";
			}
			function status_item(){
				location.href ="status.php?iid=<?php echo $this->_tpl_vars['iid']; ?>
";
			}
		</script>
		<form>
			<a href="javascript:void(0);" onclick="location.href ='index.php?page=<?php echo $this->_tpl_vars['page']; ?>
';return false;" /><img src="/alfproduct/images/btn_back.png"></a>
		</form>
		<?php endif; ?>
	</div>

<br />