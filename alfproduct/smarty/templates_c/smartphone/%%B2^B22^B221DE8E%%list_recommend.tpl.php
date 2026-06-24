<?php /* Smarty version 2.6.31, created on 2025-03-03 17:29:37
         compiled from /srv/alfproduct/smarty/templates/smartphone/product/list_recommend.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/product/list_recommend.tpl', 64, false),array('modifier', 'date_format', '/srv/alfproduct/smarty/templates/smartphone/product/list_recommend.tpl', 166, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/product/list_recommend.tpl', 251, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/smartphone/product/list_recommend.tpl', 90, false),)), $this); ?>
<style type="text/css">
	.css_cat_icon{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #32A60C;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#32A60C;
	}
	.css_cat_icon1{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
	.css_cat_icon2{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
	.css_cat_icon3{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
</style>

<div style="clear:both;text-align:left;color:#4b3921;font-size:14px;">
<a href="/">TOP</a>&nbsp;
<img src="/img/c_ar_off.png" alt="＞" style="height:10px;" />&nbsp;
おすすめｅラーニング
</div>



<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
	<div class="pager">
		<div style="text-align:left; float:left;width:240px;">
			<?php if ($this->_tpl_vars['page_max'] == 0): ?>
				全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
			<?php endif; ?>
		</div>
		<div style="text-align:right; float:right;width:240px;">
			<?php echo $this->_tpl_vars['pager']; ?>

		</div>
	</div>



<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;">
	<h2 id="page_area1">
		<img src="/img/top_icon_2.png" alt="おすすめｅラーニング" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#F48C17;">おすすめｅラーニング</span>
	</h2>
	<div class="selects">
		<form name="form_selects">
			<script type="text/javascript">
			<!--
			function sort_exe(){
				location.href = "" + "?pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
			}
			// -->
			</script>
			<span style="position:relative;bottom:5px;right:10px;">
			<?php echo smarty_function_html_options(array('name' => 'sort','options' => $this->_tpl_vars['sort_select'],'selected' => $this->_tpl_vars['sort']), $this);?>
に
			<select name="pagemax">
				<option value="5"<?php if ($this->_tpl_vars['page_max'] == 5): ?> selected=selected<?php endif; ?>>5</option>
				<option value="10"<?php if ($this->_tpl_vars['page_max'] == 10): ?> selected=selected<?php endif; ?>>10</option>
				<option value="15"<?php if ($this->_tpl_vars['page_max'] == 15): ?> selected=selected<?php endif; ?>>15</option>
				<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
				<option value="0"<?php if ($this->_tpl_vars['page_max'] == 0): ?> selected=selected<?php endif; ?>>すべて</option>
			</select>件ずつ
			<a href="javascript:void(0);" onClick="sort_exe()"><img src="/img/list/permutation_btn.png" alt="並替え" style="position:relative;top:8px;" /></a>
			</span>
		</form>
	</div>
</div>








		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<div class="list_box" style="width:698px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php if ($this->_tpl_vars['row']['product_type_add'] == 1): ?>
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;width:162px;margin-top:8px;">
							<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;margin-top:8px;">
							<?php $_from = $this->_tpl_vars['row']['icon_img']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['icon_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon']):
        $this->_foreach['icon_loop']['iteration']++;
?>
								<?php $this->assign('icon_cnt', $this->_foreach['icon_loop']['iteration']); ?>
								<?php if ($this->_tpl_vars['icon']['src'] != ''): ?>
									<img src="/img/<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['src'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="display:inline-block;float:left;" />
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							<?php $_from = $this->_tpl_vars['row']['css_icon_cat']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['icon_loop_i'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop_i']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon_row']):
        $this->_foreach['icon_loop_i']['iteration']++;
?>
								<!--<div class="css_cat_icon"><?php echo ((is_array($_tmp=$this->_tpl_vars['icon_row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>-->
							<?php endforeach; endif; unset($_from); ?>
							<?php if ($this->_tpl_vars['row']['css_icon_new'] == 1): ?>
								<div class="css_cat_icon1">NEW</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['css_icon_ninki'] == 1): ?>
								<div class="css_cat_icon2">人気</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['css_icon_syokyu'] == 1): ?>
								<div class="css_cat_icon3">初級</div>
							<?php endif; ?>
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<?php if ($this->_tpl_vars['row']['favorite_flg']): ?>
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
									<input type="image" src="/img/list/favorite_btn.png" />
								</form>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['favorite_icon_flg']): ?>
								<img src="/img/list/favorite_btn_comp01.png" alt="お気に入り登録済" />
							<?php endif; ?>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#523d26;font-size:14px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">総時間</div>
							<div style="float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['all_play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;"><!--掲載期間-->掲載日</div>
							<div style="display:inline-block;float:left;">
								<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y/%m/%d") : smarty_modifier_date_format($_tmp, "%Y/%m/%d")); ?>

								<?php echo '<?php'; ?>
 /*
								<?php if ($this->_tpl_vars['row']['start_date'] != '' && $this->_tpl_vars['row']['start_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['row']['end_date'] != '' && $this->_tpl_vars['row']['end_date'] != '0000-00-00 00:00:00'): ?>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

								<?php elseif ($this->_tpl_vars['row']['start_date'] != '' && $this->_tpl_vars['row']['start_date'] != '0000-00-00 00:00:00'): ?>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～
								<?php elseif ($this->_tpl_vars['row']['end_date'] != '' && $this->_tpl_vars['row']['end_date'] != '0000-00-00 00:00:00'): ?>
									～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

								<?php else: ?>
									未定
								<?php endif; ?>
								*/ <?php echo '?>'; ?>

							</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;overflow:hidden;height:36px;" class="textOverflowTest4">
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php elseif ($this->_tpl_vars['row']['product_type_add'] == 2): ?>
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;width:162px;margin-top:8px;">
							<img src="/img/list/list_img001.jpg" alt="会場研修" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;margin-top:8px;">
							<?php $_from = $this->_tpl_vars['row']['icon_img']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['icon_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon']):
        $this->_foreach['icon_loop']['iteration']++;
?>
								<?php $this->assign('icon_cnt', $this->_foreach['icon_loop']['iteration']); ?>
								<?php if ($this->_tpl_vars['icon']['src'] != ''): ?>
									<img src="/img/<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['src'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="display:inline-block;float:left;" />
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							<?php $_from = $this->_tpl_vars['row']['css_icon_cat']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['icon_loop_i'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop_i']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon_row']):
        $this->_foreach['icon_loop_i']['iteration']++;
?>
								<!--<div class="css_cat_icon"><?php echo ((is_array($_tmp=$this->_tpl_vars['icon_row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>-->
							<?php endforeach; endif; unset($_from); ?>
							<?php if ($this->_tpl_vars['row']['css_icon_new'] == 1): ?>
								<div class="css_cat_icon1">NEW</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['css_icon_ninki'] == 1): ?>
								<div class="css_cat_icon2">人気</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['css_icon_syokyu'] == 1): ?>
								<div class="css_cat_icon3">初級</div>
							<?php endif; ?>
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<?php if ($this->_tpl_vars['row']['favorite_flg']): ?>
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
									<input type="image" src="/img/list/favorite_btn.png" />
								</form>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['row']['favorite_icon_flg']): ?>
								<img src="/img/list/favorite_btn_comp01.png" alt="お気に入り登録済" />
							<?php endif; ?>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#523d26;font-size:14px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">受付期間</div>
							<div style="float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;">研修開催日</div>
							<div style="display:inline-block;float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">主催</div>
							<div style="float:left;"><?php echo $this->_tpl_vars['row']['disp_sponsor']; ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;">講師名</div>
							<div style="display:inline-block;float:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;overflow:hidden;height:36px;" class="textOverflowTest4">
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php else: ?>
					<div class="nonProductMsg">
					講座及び研修情報が存在しません。
					</div>
				<?php endif; ?>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<div style="text-align:center;">
					<img src="/img/list/dotline.png" alt="" style="width:100%;" />
				</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
			</div>
		<?php endforeach; endif; unset($_from); ?>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->



	<div class="pager">
		<div style="text-align:left; float:left;width:240px;">
			<?php if ($this->_tpl_vars['page_max'] == 0): ?>
				全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件
			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
			<?php endif; ?>
		</div>
		<div style="text-align:right; float:right;width:240px;">
			<?php echo $this->_tpl_vars['pager']; ?>

		</div>
	</div>
<?php else: ?>
	<div class="nonProductMsg">
	該当商品はありません。
	</div>
<?php endif; ?>