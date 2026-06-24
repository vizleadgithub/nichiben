<?php /* Smarty version 2.6.31, created on 2025-04-02 19:57:09
         compiled from /srv/alfproduct/smarty/templates/default/ranking/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ranking/index.tpl', 66, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/default/ranking/index.tpl', 224, false),)), $this); ?>
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


<ul style="clear:both;color:#4b3921;font-size:14px;list-style:none;">
	<li style="float:left;"><a href="/">TOP</a></li>
	<li style="float:left;padding:0 5px;"><img style="height:10px;" alt="＞" src="/img/c_ar_2.png"></li>
	<li style="float:left;"><a href="">人気講座ランキング</a></li>
</ul>
<hr />

<h1 class="ranking_title" style="display: inline;"><img src="/img/ranking.png" alt="総合ランキング" />総合ランキング</h1>　　　　<img src="/img/ranking.png" alt="総合ランキング" /><font size="+1">その他のランキングは
	<?php if (! empty ( $this->_tpl_vars['file_list'] )): ?>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<?php $_from = $this->_tpl_vars['file_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
			<?php if ($this->_tpl_vars['row']['file_text'] != ""): ?>
				<a href="/custom_pages/cp-content/uploads/<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['file_path'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" target="_blank" rel="noopener noreferrer">こちら</a>
				<!--<a href="/custom_pages/cp-content/uploads/<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['file_path'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" target="_blank" rel="noopener noreferrer"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['file_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>-->
			<?php endif; ?>
			<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<?php endforeach; endif; unset($_from); ?>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
	<?php endif; ?>
</font>

<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<div style="clear:both;border:hidden 0px #ffffff;">

	<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>


		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<div class="list_box" style="width:720px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php if ($this->_tpl_vars['row']['product_type_add'] == 1): ?>
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;">
							<b><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['rank'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
位</b>
						</div>
						<div style="float:left;text-align:left;display:inline-block;">
							<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;">
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
						<div style="float:right;text-align:right;display:inline-block;">
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
							<div style="width:15%;display:inline-block;float:left;">総時間</div>
							<div style="float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['all_play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">掲載期間</div>
							<div style="display:inline-block;float:left;">
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
							</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
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
						<div style="float:left;text-align:left;display:inline-block;">
							<b><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['rank'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
位</b>
						</div>
						<div style="float:left;text-align:left;display:inline-block;">
							<img src="/img/list/list_img001.jpg" alt="会場研修" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;">
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
						<div style="float:right;text-align:right;display:inline-block;">
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
							<div style="width:15%;display:inline-block;float:left;">受付期間</div>
							<div style="float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">研修開催日</div>
							<div style="display:inline-block;float:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;display:inline-block;float:left;">主催</div>
							<div style="float:left;"><?php echo $this->_tpl_vars['row']['disp_sponsor']; ?>
</div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">講師名</div>
							<div style="display:inline-block;float:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<?php endif; ?>
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
	<?php endif; ?>


</div>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->









