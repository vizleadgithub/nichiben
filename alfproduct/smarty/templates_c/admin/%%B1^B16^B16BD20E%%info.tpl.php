<?php /* Smarty version 2.6.31, created on 2025-10-24 11:41:17
         compiled from /srv/alfproduct/smarty/templates/admin/report_product/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/report_product/info.tpl', 13, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/report_product/info.tpl', 88, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/report_product/info.tpl', 108, false),)), $this); ?>
<script type="text/javascript">
function progressVideo(pid,sid){
	window.open("/alfproduct/report_status/video.php?pid="+pid+"&sid="+sid,"reportVideo","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
function progressExam(pid,sid){
	window.open("/alfproduct/report_status/exam.php?pid="+pid+"&sid="+sid,"reportExam","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
</script>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="pid">
	<input type="hidden" name="search_orderby" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_orderby'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="search_orderby">
	<table class="form">
		<tr>
			<th>商品名</th>
			<td >
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td >
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>商品種別</th>
			<td>
				<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1'): ?>
					<?php if ($this->_tpl_vars['arr_product']['product_kind_flg'] == '1'): ?>e-ラーニング
					<?php elseif ($this->_tpl_vars['arr_product']['product_kind_flg'] == '2'): ?>e-ライブ
					<?php else: ?>e-ラーニング
					<?php endif; ?>
				<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '2'): ?>
					<?php if ($this->_tpl_vars['arr_product']['training_kind_flg'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['mtb_live_training_type'][$this->_tpl_vars['arr_product']['training_kind_flg']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<?php else: ?>ライブ実務研修
					<?php endif; ?>
				<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '3'): ?>代替倫理研修
				<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '4'): ?>パスポート
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th>弁護士会</th>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['bar_association_branch_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>実施日</th>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>総受験者数</th>
			<td>
			<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1'): ?>
				<!--[product_type_add1_count:product_type_add1_end_count]-->
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add1_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				(<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add1_end_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)名
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '2'): ?>
				<!--[product_type_add2_count:product_type_add2_end_count]-->
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add2_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				(<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add2_end_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)名
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '3'): ?>
				<!--[product_type_add3_count:product_type_add3_end_count]-->
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add3_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				(<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['product_type_add3_end_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)名
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '4'): ?>
			<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th>公開期間</th>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php if ($this->_tpl_vars['arr_product']['start_date'] == "" && $this->_tpl_vars['arr_product']['end_date'] == ""): ?><?php else: ?>～<?php endif; ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_product']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
	</table>
</form>
<br />

<a href="csv.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&data=<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['post_data'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&aid=<?php echo ((is_array($_tmp=$this->_tpl_vars['aid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:;">
			登録番号
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '1'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▲</a>
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '2'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▼</a>
		</th>
		<th>氏名/ﾒｰﾙｱﾄﾞﾚｽ</th>
		<th style="width:;">弁護士会</th>
		<th style="width:;">FP</th>
		<th style="width:;">開始日/最終受講日/受講完了日</th>
		<th style="width:;">研修動画/設問</th>
		<th style="width:;">受講状況/合否</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_order']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<!--[<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]--></td>
		<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style=""><?php if ($this->_tpl_vars['row']['presence_passport'] == '1'): ?>○<?php else: ?>-<?php endif; ?></td>
		<td class="tdc" style="">
			<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1'): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['start_view_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['end_view_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br>
				<?php if ($this->_tpl_vars['row']['complete_date'] != "" && $this->_tpl_vars['row']['complete_date'] != "0000-00-00 00:00:00"): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['complete_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php else: ?>
					-
				<?php endif; ?>
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '2'): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br>
				-<br>
				-
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '3'): ?>
				<?php if ($this->_tpl_vars['row']['start_date1'] != "" && $this->_tpl_vars['row']['start_date1'] != "0000-00-00 00:00:00"): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['start_date1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php else: ?>
					-
				<?php endif; ?><br>
				<?php if ($this->_tpl_vars['row']['judge_date2'] != "" && $this->_tpl_vars['row']['judge_date2'] != "0000-00-00 00:00:00"): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['judge_date2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php elseif ($this->_tpl_vars['row']['judge_date1'] != "" && $this->_tpl_vars['row']['judge_date1'] != "0000-00-00 00:00:00"): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['judge_date1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php else: ?>
					-
				<?php endif; ?><br>
				-
			<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '4'): ?>
				-<br>
				-<br>
				-
			<?php endif; ?>
		</td>
		<td class="tdc" style="">
		<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1' && $this->_tpl_vars['arr_product']['product_kind_flg'] == '3'): ?>
			<a href="javascript:void(0)" onclick="progressVideo(<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)">研修動画</a>：<?php if ($this->_tpl_vars['row']['video_comp_count'] == $this->_tpl_vars['video_count']): ?>100%<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['max_percent_video'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%<?php endif; ?>
			<br>
			<a href="javascript:void(0)" onclick="progressExam(<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)">設問</a>：<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['answer_val_exam'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['max_val_exam'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php else: ?>
			-<br>-
		<?php endif; ?>
		</td>
		<td class="tdc" style="">
		<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1'): ?>
			<!--[<?php echo $this->_tpl_vars['row']['all_end_view_count']; ?>
][<?php echo $this->_tpl_vars['video_count']; ?>
]-->
			<?php if ($this->_tpl_vars['row']['all_end_view_count'] == $this->_tpl_vars['video_count']): ?>
				100%
			<?php else: ?>
				<?php if ($this->_tpl_vars['arr_product']['product_kind_flg'] == '3'): ?>
					<?php if ($this->_tpl_vars['row']['video_comp_count'] == $this->_tpl_vars['video_count']): ?>
						100%
					<?php else: ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['percent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%
					<?php endif; ?>
				<?php else: ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['percent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%
				<?php endif; ?>
			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '2'): ?>
			<?php if ($this->_tpl_vars['row']['participation_flg'] == '1'): ?>
				100%
			<?php else: ?>
				0%
			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '3'): ?>
			<!--[<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]-->
			<!--ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）-->
			<?php if ($this->_tpl_vars['row']['status'] == '0' || $this->_tpl_vars['row']['status'] == ""): ?>
				未受講<!--1次未受講-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '1'): ?>
				受講中<!--1次受講中-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '2'): ?>
				一次○<!--1次合格-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '3'): ?>
				一次×<!--1次不合格-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '4'): ?>
				一次×<!--2次受講中-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '5'): ?>
				追試○<!--2次合格-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '6'): ?>
				追試×<!--不合格-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '7'): ?>
				レポート<!--レポート-->
			<?php elseif ($this->_tpl_vars['row']['status'] == '8'): ?>
				会場<!--会場-->
			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['arr_product']['product_type_add'] == '4'): ?>
		<?php endif; ?>
		<br>
		<?php if ($this->_tpl_vars['arr_product']['product_type_add'] == '1' && $this->_tpl_vars['arr_product']['product_kind_flg'] == '3'): ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['gouhi'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php else: ?>
			-
		<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="7">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
