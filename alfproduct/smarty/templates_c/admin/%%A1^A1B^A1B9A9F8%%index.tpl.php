<?php /* Smarty version 2.6.31, created on 2024-12-20 16:13:41
         compiled from /srv/alfproduct/smarty/templates/admin/report_all/index.tpl */ ?>
	<table class="list">
		<tr>
			<th style="width:;" colspan="2">自会ユーザー</th>
		</tr>
		<tr style="">
			<th width="300">登録ユーザー（登録取り消し含む）</th>
			<td class="tdc" style="text-align:center"><?php echo $this->_tpl_vars['all_student_count']; ?>
</td>
		</tr>
		<tr style="">
			<th>登録取り消し（停止）ユーザー</th>
			<td class="tdc" style="text-align:center"><?php echo $this->_tpl_vars['del_student_count']; ?>
</td>
		</tr>
		<tr style="">
			<th>１ヶ月以内ログインユーザー</th>
			<td class="tdc" style="text-align:center"><?php echo $this->_tpl_vars['month_login_student_count']; ?>
</td>
		</tr>

	</table>