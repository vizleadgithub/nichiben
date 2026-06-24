<?php /* Smarty version 2.6.27, created on 2013-11-16 10:27:22
         compiled from /srv/alfproduct/smarty/templates/smartphone/product/list_limit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/product/list_limit.tpl', 8, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/product/list_limit.tpl', 126, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/smartphone/product/list_limit.tpl', 29, false),)), $this); ?>
<?php echo $this->_tpl_vars['pankuzu']; ?>




<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
	<div class="pager">
		<div style="text-align:left; float:left;width:244px;">
			<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中） 
		</div>
		<div style="text-align:right; float:right;width:244px;">
			<?php echo $this->_tpl_vars['pager']; ?>

		</div>
	</div>



<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;height:115px;">
	<h2>講座一覧</h2>
	<div class="selects">
		<form name="form_selects">
			<script type="text/javascript">
			<!--
			function sort_exe(){
				location.href = "" + "?pcid=<?php echo $this->_tpl_vars['pcid']; ?>
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
				<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
				<option value="40"<?php if ($this->_tpl_vars['page_max'] == 40): ?> selected=selected<?php endif; ?>>40</option>
			</select>件ずつ
			<a href="javascript:void(0);" onClick="sort_exe()"><img src="/img/list/permutation_btn.png" alt="並替え" style="position:relative;top:8px;" /></a>
			</span>
		</form>
		<div style="padding-top:15px;text-align:center;">
弁護士会主催研修は、各弁護士会からの情報に基づいています。<br />
詳細は各弁護士会にお問い合わせください。
		</div>
	</div>
</div>







	
	<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<div class="list_box" style="width:468px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
		<?php if ($this->_tpl_vars['row']['product_type_add'] == 1): ?>
		<div style="float:left;width:160px;height:115px;text-align:center;background-color:#99e5fd;padding:0;" class="thumb">
			<img src="/img/list/list_img002.jpg" alt="eラーニング" />
			<?php if ($this->_tpl_vars['row']['thumbnail_flg'] == 1): ?>
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo $this->_tpl_vars['row']['thumbnail']; ?>
&width=155&height=85" alt="" style="margin-top:5px;" /></a>
			<?php elseif ($this->_tpl_vars['row']['thumbnail_flg'] == 2): ?>
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_video_image.php?image=<?php echo $this->_tpl_vars['row']['video_thumbnail']; ?>
&width=155&height=85" alt="" style="margin-top:5px;" /></a>
			<?php else: ?>
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="margin-top:5px;" /></a>
			<?php endif; ?>
			
			<div style="position:relative;top:8px;right:5px;background-color:#ffffff;width:170px;height:20px;padding-top:10px;">
			<?php $_from = $this->_tpl_vars['row']['icon_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['icon_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon']):
        $this->_foreach['icon_loop']['iteration']++;
?>
			<?php $this->assign('icon_cnt', $this->_foreach['icon_loop']['iteration']); ?>
				<img src="/img/<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['src'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<?php if ($this->_tpl_vars['icon_cnt'] == 2): ?><br /><?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>
		
		<div>
			<div style="text-align:right;">
			<?php if ($this->_tpl_vars['row']['favorite_flg']): ?>
				<form name="favoriteForm" action="https://<?php echo $_SERVER['SERVER_NAME']; ?>/mypage/favorite.php" method="post">
				<input type="hidden" name="act" value="regist" />
				<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="image" src="/img/list/favorite_btn.png" /><br />
				</form>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['row']['favorite_icon_flg']): ?>
				<img src="/img/list/favorite_btn_comp01.png" alt="お気に入り登録済" />
			<?php endif; ?>
			</div>
		</div>
		
		<div style="text-align:left;margin-left:180px;">
			<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#523d26;font-size:14px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
		</div>
		
		<div style="float:right;width:290px">
			<table>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">講師名</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">総時間</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['all_play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">単品価格</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php if ($this->_tpl_vars['row']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
円(税込)<?php endif; ?></td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">掲載期間</th>
					<td style="text-align:left;vertical-align:top;width:210px;">
						<?php if ($this->_tpl_vars['row']['start_date'] != '' && $this->_tpl_vars['row']['start_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['row']['end_date'] != '' && $this->_tpl_vars['row']['end_date'] != '0000-00-00 00:00:00'): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php elseif ($this->_tpl_vars['row']['start_date'] != '' && $this->_tpl_vars['row']['start_date'] != '0000-00-00 00:00:00'): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～
						<?php elseif ($this->_tpl_vars['row']['end_date'] != '' && $this->_tpl_vars['row']['end_date'] != '0000-00-00 00:00:00'): ?>
							～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php else: ?>
							常に公開
						<?php endif; ?>
					</td>
				</tr>
			</table>
			<div style="float:right;width:460px;padding:5px;">
				<div style="text-align:left;width:100%;">
					<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

				</div>
				<div style="text-align:right;width:100%;">
					<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
					<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">商品詳細へ</a>
				</div>
			</div>
		</div>
		
		<?php elseif ($this->_tpl_vars['row']['product_type_add'] == 2): ?>
		<div style="float:left;width:160px;height:115px;text-align:center;background-color:#8ebd55;padding:0;" class="thumb">
			<img src="/img/list/list_img001.jpg" alt="会場研修" />
			<?php if ($this->_tpl_vars['row']['thumbnail'] != ""): ?>
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo $this->_tpl_vars['row']['thumbnail']; ?>
&width=155&height=85" alt="" style="margin-top:5px;" /></a>
			<?php else: ?>
				<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="margin-top:5px;" /></a>
			<?php endif; ?>
			<div style="position:relative;top:20px;">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['icon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</div>
		</div>
		
		<div>
			<div style="text-align:right;">
			<?php if ($this->_tpl_vars['row']['favorite_flg']): ?>
				<form name="favoriteForm" action="https://<?php echo $_SERVER['SERVER_NAME']; ?>/mypage/favorite.php" method="post">
				<input type="hidden" name="act" value="regist" />
				<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="image" src="/img/list/favorite_btn.png" /><br />
				</form>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['row']['favorite_icon_flg']): ?>
				<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り登録済" />
			<?php endif; ?>
			</div>
		</div>
		
		<div style="text-align:left;margin-left:180px;">
			<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#523d26;font-size:14px;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
		</div>
		
		<div style="float:right;width:290px">
			<table>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">講師名</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">主催</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo $this->_tpl_vars['row']['disp_sponsor']; ?>
</td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">単品価格</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php if ($this->_tpl_vars['row']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
円(税込)<?php endif; ?></td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">受付期間</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr style="background-color:#f5f8ef;">
					<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">研修開催日</th>
					<td style="text-align:left;vertical-align:top;width:210px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['disp_dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
			</table>
			<div style="float:right;width:460px;padding:5px;">
				<div style="text-align:left;width:100%;">
					<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

				</div>
				<div style="text-align:right;width:100%;">
					<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
					<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:57462e;font-size:14px;">商品詳細へ</a>
				</div>
			</div>
		</div>
		
		<?php else: ?>
		講座及び研修情報が存在しません。
	<?php endif; ?>
	
	<div style="text-align:center;">
			<img src="/img/list/dotline.png" alt="" style="width:100%;" />
		</div>
	</div>
	<?php endforeach; endif; unset($_from); ?>


	<div class="pager">
		<div style="text-align:left; float:left;width:244px;">
			<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中） 
		</div>
		<div style="text-align:right; float:right;width:244px;">
			<?php echo $this->_tpl_vars['pager']; ?>

		</div>
	</div>
<?php else: ?>
	該当商品はありません。
<?php endif; ?>