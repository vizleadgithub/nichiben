<?php /* Smarty version 2.6.31, created on 2025-03-03 15:45:43
         compiled from /srv/alfproduct/smarty/templates/smartphone/search/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/search/index.tpl', 151, false),array('modifier', 'date_format', '/srv/alfproduct/smarty/templates/smartphone/search/index.tpl', 264, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/search/index.tpl', 353, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/smartphone/search/index.tpl', 179, false),array('function', 'html_checkboxes', '/srv/alfproduct/smarty/templates/smartphone/search/index.tpl', 508, false),)), $this); ?>
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
	.css_cat_icon4{
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
	.css_cat_icon4 {
	  float: left;
	  display: inline-block;
	  border-radius: 1px;
	  border: solid 1px #1BAED9;
	  height: 18px;
	  line-height: 18px;
	  font-size: 14px;
	  padding: 0 5px;
	  background-color: #1BAED9;
	  color: #fff;
	  font-weight: 600;
	  width: 160px;
	  text-align: center;
	}
</style>

<ul style="clear:both;color:#4b3921;font-size:14px;list-style:none;">
	<li style="float:left;"><a href="/">TOP</a></li>
	<li style="float:left;padding:0 5px;"><img style="height:10px;" alt="＞" src="/img/c_ar_2.png"></li>
	<li style="float:left;"><a href="">検索</a></li>
</ul>
<hr />


<h2 class="search_title">検索</h2>






<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->



<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<?php if ($this->_tpl_vars['disp_flg']): ?>
	<div style="clear:both;border:hidden 0px #ffffff;">

	<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>

		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!-- 改ページ -->
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
			<div style=" float:right;width:240px;">
				<?php echo $this->_tpl_vars['pager']; ?>

			</div>
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!-- 一覧表示件数 -->
		<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;height:115px;">
			<h2>
				<img src="/img/top_icon_3.png" alt="新着eラーニング"><span style="vertical-align: top;line-height: 30px;margin-left: 6px;">検索結果</span>
			</h2>
			<div class="selects">
				<form name="form_selects">
					<script type="text/javascript">
					<!--
					function sort_exe(){
						location.href = "" + "?pcid=<?php echo $this->_tpl_vars['pcid']; ?>
<?php echo $this->_tpl_vars['url_plam']; ?>
&pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
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
				<div style="padding-top:15px;text-align:center;">
					弁護士会主催研修は、各弁護士会からの情報に基づいています。
					詳細は各弁護士会にお問い合わせください。
				</div>
			</div>
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->




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
							<!--[<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['search_point'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]-->
							<?php if ($this->_tpl_vars['row']['css_icon_etcmovie'] == 1): ?>
								<img src="/img/list/list_img003.jpg" alt="その他動画" />
							<?php else: ?>
								<img src="/img/list/list_img002.jpg" alt="eラーニング" />
							<?php endif; ?>
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
							<?php if ($this->_tpl_vars['row']['css_icon_etcmovie'] == 1): ?>
								<!--<div class="css_cat_icon4">その他動画</div>-->
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
							<!--[<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['search_point'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]-->
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
							<?php if ($this->_tpl_vars['row']['css_icon_etcmovie'] == 1): ?>
								<div class="css_cat_icon4">その他動画</div>
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
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->

	<?php else: ?>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<div class="nonProductMsg">
			該当する講座及び研修はありません。
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
	<?php endif; ?>


	</div>
<?php endif; ?>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->








<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<form name="search_form" method="post" action="index.php?search=new#main">


	<div style="border: solid 1px #a1c873;padding: 1px;">
		<table style="width:100%;background-color: #ffffff;" class="member_table">
			<tr>
				<th colspan="2" style="width:100px; background: url(/img/mushimegane.png)no-repeat 1% 50%; background-color:#a1c873; color:#ffffff;text-align:left;vertical-align:top;padding:6px 5px 6px 30px;font-size:14px;">ご希望の条件を選択して講座を絞り込むことができます（複数選択可）</th>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">検索条件</th>
				<td style="padding:5px;">
					<label><input type="radio" name="search_type" value="AND" <?php if ($this->_tpl_vars['search_type'] == 'AND'): ?>checked<?php endif; ?> >AND検索</label>
					<label><input type="radio" name="search_type" value="OR" <?php if ($this->_tpl_vars['search_type'] != 'AND'): ?>checked<?php endif; ?> >OR検索</label>
				</td>
			</tr>
			<script type="text/javascript">
				//function search_option_form_open( search_form_type ){
				//	if( search_form_type=="OR" ){
				//		$("#search_option_form").show();
				//	} else {
				//		$("#search_option_form").hide();
				//	}
				//}
			</script>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">キーワード</th>
				<td style="padding:5px;">
					<input type="text" name="search_keyword" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_keyword'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
				</td>
			</tr>
		</table>
	</div>


	<div id="search_option_form" style="border: solid 1px #a1c873;padding: 1px;">
		<table style="width:100%;background-color: #ffffff;" class="member_table">
			<tr>
				<th colspan="2" style="width:100px; background-color:#a1c873; color:#ffffff;text-align:left;vertical-align:top;padding:6px 5px 6px 30px;font-size:14px;">
					▼▼　下記の条件でさらに絞り込む（絞り込みたい事項にチェック）
				</th>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">講座種別</th>
				<td style="padding:5px;">
					<?php echo smarty_function_html_checkboxes(array('name' => 'search_training','options' => $this->_tpl_vars['arr_search_training'],'selected' => $this->_tpl_vars['search_training'],'separator' => '<br />'), $this);?>

				</td>
			</tr>
									<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">eラーニング受講状況</th>
				<td style="padding:5px;">
					<?php echo smarty_function_html_checkboxes(array('name' => 'search_state','options' => $this->_tpl_vars['arr_search_state'],'selected' => $this->_tpl_vars['search_state']), $this);?>

				</td>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">eラーニング掲載期間</th>
				<td style="padding:5px;">
					<ul style="list-style:none;">
					<li style="float:left;"><input type="text" name="search_start_date" id="start_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a></li>
					<li style="float:left;padding:5px;">～</li>
					<li style="float:left;"><input type="text" name="search_end_date" id="end_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">研修開催日</th>
				<td style="padding:5px;">
					<ul style="list-style:none;">
					<li style="float:left;"><input type="text" name="search_start_contents_date" id="start_contents_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_contents_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_contents_date.value='';">クリア</a></li>
					<li style="float:left;padding:5px;">～</li>
					<li style="float:left;"><input type="text" name="search_end_contents_date" id="end_contents_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_contents_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_contents_date.value='';">クリア</a></li>
					</ul>
				</td>
			</tr>
			<tr>
				<script type="text/javascript">
					/* ブラウザ判別 */
					var ie=document.all ? 1 : 0;
					var ns6=document.getElementById&&!document.all ? 1 : 0;
					var opera=window.opera ? 1 : 0;

					/* 子メニューの表示・非表示切替 */
					function openFolder(childObj, parentObj){
						var child="";
						var parent="";
						var sw="/img/list/show.gif"; /* フォルダ表示時のアイコン画像 */
						var hd="/img/list/hide.gif"; /* フォルダ非表示時のアイコン画像 */
						if(ie || ns6 || opera){
							child=ns6 ? document.getElementById(childObj).style : document.all(childObj).style;
							parent=ns6 ? document.getElementById(parentObj) : document.all(parentObj);
							if (child.display=="none"){
								child.display="block";
								parent.src=sw;
							}else{
								child.display="none";
								parent.src=hd;
							}
						}
					}
				</script>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">カテゴリ</th>
				<td style="padding:5px;">
					<input type="button" value="全てにチェックを入れる" onClick="categoryAllCheck(1)" />
					<input type="button" value="全てのチェックを外す" onClick="categoryAllCheck(0)" />
					<!--<input type="button" value="カテゴリ一覧開閉" onClick="opnClz('search_category_list_opclz')" />-->
					<br />
					<div id="search_category_list_opclz">
					<ul style="list-style-type:none;">
					<?php $_from = $this->_tpl_vars['arr_category']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
						<li>
							<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
							<?php if ($this->_tpl_vars['row']['categorys']): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/img/list/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
', 'close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" alt="" /><?php endif; ?>
						</li>
						<div id="open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="child" style="display:none;">
						<ul style="margin-left:15px;list-style-type:none;">
						<?php $_from = $this->_tpl_vars['row']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row2']):
?>
							<li>
								→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row2']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
								<?php if ($this->_tpl_vars['row2']['categorys']): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/img/list/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
', 'close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" alt="" /><?php endif; ?>
							</li>
							<div id="open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="child" style="display:none;">
							<ul style="margin-left:15px;list-style-type:none;">
							<?php $_from = $this->_tpl_vars['row2']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row3']):
?>
								<li>
									→→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row3']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
									<?php if ($this->_tpl_vars['row3']['categorys']): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/img/list/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
', 'close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" alt="" /><?php endif; ?>
								</li>
								<div id="open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="child" style="display:none;">
								<ul style="margin-left:15px;list-style-type:none;">
								<?php $_from = $this->_tpl_vars['row3']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row4']):
?>
									<li>
										→→→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row4']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
										<?php if ($this->_tpl_vars['row4']['categorys']): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/img/list/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
', 'close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" alt="" /><?php endif; ?>
									</li>
									<div id="open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="child" style="display:none;">
									<ul style="margin-left:15px;list-style-type:none;">
									<?php $_from = $this->_tpl_vars['row4']['categorys']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row5']):
?>
										<li>
											→→→→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row5']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
										</li>
									<?php endforeach; endif; unset($_from); ?>
									</ul>
									</div>
								<?php endforeach; endif; unset($_from); ?>
								</ul>
								</div>
							<?php endforeach; endif; unset($_from); ?>
							</ul>
							</div>
						<?php endforeach; endif; unset($_from); ?>
						</ul>
						</div>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
					</div>
				</td>
			</tr>
			<script type="text/javascript">
				var arr_cat_id = [];
				var arr_cat_name = [];
				var arr_par_id = [];
				<?php $_from = $this->_tpl_vars['arr_cat_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
					arr_cat_id[arr_cat_id.length] = "<?php echo $this->_tpl_vars['val']['term_id']; ?>
";
					arr_cat_name[arr_cat_name.length] = "<?php echo $this->_tpl_vars['val']['name']; ?>
";
					arr_par_id[arr_par_id.length] = "<?php echo $this->_tpl_vars['val']['parent']; ?>
";
				<?php endforeach; endif; unset($_from); ?>
				function check_cat(cat_id){
					var temp_i = cat_id;
					if (document.getElementById("arr_term_id"+cat_id).checked ===true){
						while (temp_i!="0" && temp_i!="21" ){
							for (var i=0;i<arr_cat_id.length;i++){
								if(temp_i==arr_cat_id[i]){
									//alert(temp_i + ":" + arr_par_id[i]);
									if(document.getElementById("arr_term_id"+temp_i) != null){
										document.getElementById("arr_term_id"+temp_i).checked = true;
									}
									temp_i = arr_par_id[i];
								}
							}
						}
					} else {
						var select_cat_id = new Array(cat_id);
						while(select_cat_id.length>0){
							var select_par_id = new Array();
							for (var i=0;i<select_cat_id.length;i++){
								for (var n=0;n<arr_par_id.length;n++){
									if (arr_par_id[n]==select_cat_id[i]){
										select_par_id[select_par_id.length] = arr_cat_id[n];
									} 
								}
							}
							for (var i=0;i<select_par_id.length;i++){
								if(document.getElementById("arr_term_id"+select_par_id[i]) != null){
									document.getElementById("arr_term_id"+select_par_id[i]).checked = false;
								}
							}
							select_cat_id = select_par_id;
						}
					}
				}
				
				function categoryAllCheck(flg){
					if (flg==1){
						var str = 'check';
					} else {
						var str = '';
					}
					for(var count=0; count<document.search_form.elements["search_category[]"].length; count++){
						document.search_form.elements["search_category[]"][count].checked = str;
					}
				}
							</script>
		</table>
	</div>

	<div style="padding:20px 0;text-align:center;">
		弁護士会主催研修は各弁護士会からの情報に基づいています。<br />詳細は各弁護士会にお問い合わせ下さい。
	</div>

	<table style="width:100%;">
		<tr>
			<td style="padding:5px;text-align:center;width:100%;">
				<input type="image" src="/img/btn/search.png" name="btn_submit" value="　検索　">
			</td>
		</tr>
	</table>

</form>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->

