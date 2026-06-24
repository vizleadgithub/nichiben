<?php /* Smarty version 2.6.31, created on 2025-01-25 11:26:25
         compiled from /srv/alfproduct/smarty/templates/smartphone/mypage/favorite_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/mypage/favorite_list.tpl', 25, false),array('modifier', 'mb_substr', '/srv/alfproduct/smarty/templates/smartphone/mypage/favorite_list.tpl', 104, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/smartphone/mypage/favorite_list.tpl', 60, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'mypage/side_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">お気に入りの講座</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">お気に入りの講座</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたがお気に入りに登録した講座を表示しています。<br />
			自由に並び替えや削除ができます。

			<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
				<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:20px;padding: 0;">
					<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
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

					<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
						<?php echo $this->_tpl_vars['pager']; ?>

					</div>

					<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
						<script type="text/javascript">
						<!--
						function select_pagemax(){
							location.href = "" + "?pagemax=" + document.form_pagemax.pagemax.options[document.form_pagemax.pagemax.selectedIndex].value;
						}
						// -->
						</script>
						<form name="form_pagemax" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">
						<select name="pagemax">
							<option value="5"<?php if ($this->_tpl_vars['page_max'] == 5): ?> selected=selected<?php endif; ?>>5</option>
							<option value="10"<?php if ($this->_tpl_vars['page_max'] == 10): ?> selected=selected<?php endif; ?>>10</option>
							<option value="15"<?php if ($this->_tpl_vars['page_max'] == 15): ?> selected=selected<?php endif; ?>>15</option>
							<option value="20"<?php if ($this->_tpl_vars['page_max'] == 20): ?> selected=selected<?php endif; ?>>20</option>
							<option value="0"<?php if ($this->_tpl_vars['page_max'] == 0): ?> selected=selected<?php endif; ?>>すべて</option>
						</select>件ずつ
						<a href="javascript: void(0)" onclick="select_pagemax();return false;" style="vertical-align:top;margin:0px;padding: 0;"><img src="/img/mypage/listing_btn.png" style="vertical-align:top;margin:0;padding-bottom: 8px;"></a>
						</form>
					</div>
				</div>

				<div style="width:650px;height:40px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;clear: both;font-fize:16px;color:#333333;font-weight: bold;margin:0;padding:0;">
					<div style="margin-top:15px;">お気に入りの講座</div>
				</div>
				<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

				<div style="width:650px;height:135px;float:left;clear:both;<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;border-style: none solid solid;margin:0;padding:0;">
										<?php if ($this->_tpl_vars['row']['product_type_add'] == 1): ?>
					<div style="float:left;width:160px;height:115px;text-align:center;background-color:#99e5fd;margin:10px;" class="thumb thumb2">
						<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						<div style="margin-top:5px;">
						<?php if ($this->_tpl_vars['row']['thumbnail_flg'] == 1): ?>
							<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo $this->_tpl_vars['row']['thumbnail']; ?>
&width=155&height=85" alt="" style="" /></a>
						<?php elseif ($this->_tpl_vars['row']['thumbnail_flg'] == 2): ?>
							<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_video_image.php?image=<?php echo $this->_tpl_vars['row']['video_thumbnail']; ?>
&width=155&height=85" alt="" style="" /></a>
						<?php else: ?>
							<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="" /></a>
						<?php endif; ?>
						</div>
					</div>
										<?php elseif ($this->_tpl_vars['row']['product_type_add'] == 2): ?>
					<div style="float:left;width:160px;height:115px;text-align:center;background-color:#8EBD55;margin:10px;" class="thumb thumb2">
						<div style="margin-top:5px;">
						<img src="/img/list/list_img001.jpg" alt="会場研修" />
						<?php if ($this->_tpl_vars['row']['thumbnail'] != ""): ?>
							<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo $this->_tpl_vars['row']['thumbnail']; ?>
&width=155&height=85" alt="" style="" /></a>
						<?php else: ?>
							<?php if ($this->_tpl_vars['row']['echic_flg'] == 1): ?>
								<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=product_ethic.jpg&width=155&height=85" alt="" style="" /></a>
							<?php elseif ($this->_tpl_vars['row']['training_kind_flg'] == 1): ?>
								<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=product_live.jpg&width=155&height=85" alt="" style="" /></a>
							<?php else: ?>
								<a href="./../product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=product_bar_association.jpg&width=155&height=85" alt="" style="" /></a>
							<?php endif; ?>
						<?php endif; ?>
						</div>
						<div style="position:relative;top:20px;">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['icon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						</div>
					</div>
					<?php endif; ?>

				
					<div style="float:right;width:470px;height:135px;text-align:center;">
						<div style="float:left;width:330px;height:125px; border: solid 1px #AB9983;padding-top:10px;border-style: none solid none none;overflow:hidden;">
							<table>
								<tr>
									<th colspan="2" style="text-align:left;vertical-align:top;"><a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="color:#5E4D34;font-size:18px;font-weight:normal;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 70, 'utf-8') : mb_substr($_tmp, 0, 70, 'utf-8')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php if (mb_strlen ( $this->_tpl_vars['row']['product_name'] , 'utf-8' ) > 70): ?>...<?php endif; ?></a></th>
								</tr>
																																					</table>
							
						</div>
						<div style="float:right;width:139px;height:135px;">
							<table style="border: solid 0px #000000;width:100%;height:100%;">
							<tr style="border: solid 1px #AB9983;border-style: none none dotted none;height:55px;font-size:14px;">
								<td colspan="2" style="text-align:center;font-size:14px;padding-top:5px;">お気に入りから<br>
									<form name="form_delete<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" action="favorite.php" method="post">
									<input type="hidden" name="act" value="delete" />
									<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
									<!--<input type="submit" value="削除" />-->
									<a href="javascript:void(0);" onclick="document.form_delete<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.submit();"><img src="/img/mypage/delete_btn.png" alt="削除" /></a>
									</form>
								</td>
							</tr>
							<tr style="border: none;border-style: none;font-size:14px;">
								<td colspan="2" style="text-align:center;font-size:14px;padding-top:5px;">
									この講座を
								</td>
							</tr>
							<tr style="font-size:14px;">
								<td style="text-align:center;font-size:14px;">
									<form name="form_up<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" action="favorite.php" method="post">
									<input type="hidden" name="act" value="up" />
									<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
									<!--<input type="submit" value="↑" />-->
									<a href="javascript:void(0);" onclick="document.form_up<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.submit();" style="color:#5E4D34"><img src="/img/mypage/ic_up.png" alt="上へ" />上へ</a>
									</form>
								</td>
								<td style="text-align:center;font-size:14px;">
									<form name="form_down<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" action="favorite.php" method="post">
									<input type="hidden" name="act" value="down" />
									<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
									<!--<input type="submit" value="↓" />-->
									<a href="javascript:void(0);" onclick="document.form_down<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.submit();" style="color:#5E4D34"><img src="/img/mypage/ic_dn.png" alt="下へ" />下へ</a>
									</form>
								</td>
							</tr>
							</table>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?>

				<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:10px;padding: 0;">
					<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
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

					<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
						<?php echo $this->_tpl_vars['pager']; ?>

					</div>

					<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
					</div>
				</div>
			<?php else: ?>
				<div class="nonProductMsg">
				お気に入りはありません。
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>