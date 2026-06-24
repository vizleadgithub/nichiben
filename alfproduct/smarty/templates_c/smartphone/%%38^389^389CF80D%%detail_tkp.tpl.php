<?php /* Smarty version 2.6.27, created on 2020-08-28 16:10:54
         compiled from /srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 137, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 209, false),array('modifier', 'count', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 289, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 350, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 351, false),array('modifier', 'mb_truncate', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 2758, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/smartphone/product/detail_tkp.tpl', 2026, false),)), $this); ?>
<script type="text/javascript">
	<?php if (! $this->_tpl_vars['is_sp']): ?>
	<?php else: ?>
				var touchMoveFlag = false;
	<?php endif; ?>
	function playerFormSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			setTimeout(function(){
				w.onLoad = playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}, 1000);
		<?php else: ?>
			if (!touchMoveFlag){
				playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}
			touchMoveFlag = false;
		<?php endif; ?>

	}
	function playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		document.getElementById("hid_vid").value = vid;
		document.getElementById("hid_vid2").value = vid2;
		document.getElementById("hid_codec").value = codec;
		document.getElementById("hid_ftn").value = ftn;
		document.getElementById("hid_ccno").value = ccno;
		document.getElementById("hid_view_btn").value = view_btn;
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			document.playerForm.target = "playerDisp";
		<?php endif; ?>
		document.playerForm.method = "post";
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			document.playerForm.action = "/player/index_tkp.php?term=pc";
		<?php else: ?>
			document.playerForm.action = "/player/index_tkp.php?term=sp";
		<?php endif; ?>
		document.playerForm.submit();
	}
	function playerEthicFormSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			setTimeout(function(){
				w.onLoad = playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}, 1000);
		<?php else: ?>
			if (!touchMoveFlag){
				playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}
			touchMoveFlag = false;
		<?php endif; ?>
	}
	function playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		document.getElementById("hid_vid").value = vid;
		document.getElementById("hid_vid2").value = vid2;
		document.getElementById("hid_codec").value = codec;
		document.getElementById("hid_ftn").value = ftn;
		document.getElementById("hid_ccno").value = ccno;
		document.getElementById("hid_view_btn").value = view_btn;
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			document.playerForm.target = "playerDisp";
		<?php else: ?>
		<?php endif; ?>
		document.playerForm.method = "post";
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			document.playerForm.action = "/player/player_ethic.php?term=pc";
		<?php else: ?>
			document.playerForm.action = "/player/player_ethic.php?term=sp";
		<?php endif; ?>
		document.playerForm.submit();
	}

	function downloadFormSubmit(cdname){
		document.getElementById("hid_cdname").value = cdname;
		document.downloadForm.method = "post";
		document.downloadForm.action = "download.php?PHPSESSID=<?php echo session_id(); ?>";
		document.downloadForm.submit();
	}
	function downloadPopup(){
		<?php if (! $this->_tpl_vars['is_sp']): ?>
			window.open("about:blank","documentDisp","width=675,height=800,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			document.downloadForm.target = "documentDisp";
			document.downloadForm.method = "post";
			document.downloadForm.action = "document.php?PHPSESSID=<?php echo session_id(); ?>";
			document.downloadForm.submit();
		<?php else: ?>
			if (!touchMoveFlag){
				document.downloadForm.method = "post";
				document.downloadForm.action = "document.php?PHPSESSID=<?php echo session_id(); ?>";
				document.downloadForm.submit();
			}
			touchMoveFlag = false;
		<?php endif; ?>
	}
</script>
<style type="text/css">
a.test_btn{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #0097dd;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #ffffff;
	text-decoration: none;
	border-radius: 8px;
	width:230px;
}
a.test_btn_none{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #f2f2f2;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #bebebe;
	text-decoration: none;
	border-radius: 8px;
	width:230px;
}
</style>

<!--
[<?php echo $this->_tpl_vars['product_list']['contents_baisoku_flg1']; ?>
]
[<?php echo $this->_tpl_vars['product_list']['contents_baisoku_flg2']; ?>
]
[<?php echo $this->_tpl_vars['product_list']['contents_baisoku_flg3']; ?>
]
-->
<?php echo $this->_tpl_vars['pankuzu']; ?>


<?php if ($this->_tpl_vars['product_list']['product_type_add'] == 1 && $this->_tpl_vars['buy_flg']): ?>
<input type="button" value="　関連講座　" ontouchmove="touchMoveFlag=true;" onclick="window.location.href='#RelatedCourseBlock';" style="float:right;" />
<?php endif; ?>

<div style="clear:both;margin:0;" class="detail_h2_1">
	<h2><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
詳細</h2>
</div>
<?php if ($this->_tpl_vars['product_list']['product_type_add'] == 1): ?>
		<?php if ($this->_tpl_vars['buy_flg']): ?>
		<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="width:170px;float:left;text-align:center;">
								<div id="rollover">
										<div style="width:155px;">
						<?php if (( $this->_tpl_vars['product_list']['contents_contents1'] != '' && $this->_tpl_vars['product_list']['contents_view_flg1'] ) || ( $this->_tpl_vars['product_list']['contents_contents2'] != '' && $this->_tpl_vars['product_list']['contents_view_flg2'] ) || ( $this->_tpl_vars['product_list']['contents_contents3'] != '' && $this->_tpl_vars['product_list']['contents_view_flg3'] ) || ( $this->_tpl_vars['product_list']['contents_contents4'] != '' && $this->_tpl_vars['product_list']['contents_view_flg4'] ) || ( $this->_tpl_vars['product_list']['contents_contents5'] != '' && $this->_tpl_vars['product_list']['contents_view_flg5'] ) || ( $this->_tpl_vars['product_list']['contents_contents6'] != '' && $this->_tpl_vars['product_list']['contents_view_flg6'] ) || ( $this->_tpl_vars['product_list']['contents_contents7'] != '' && $this->_tpl_vars['product_list']['contents_view_flg7'] ) || ( $this->_tpl_vars['product_list']['contents_contents8'] != '' && $this->_tpl_vars['product_list']['contents_view_flg8'] ) || ( $this->_tpl_vars['product_list']['contents_contents9'] != '' && $this->_tpl_vars['product_list']['contents_view_flg9'] ) || ( $this->_tpl_vars['product_list']['contents_contents10'] != '' && $this->_tpl_vars['product_list']['contents_view_flg10'] ) || ( $this->_tpl_vars['product_list']['contents_contents11'] != '' && $this->_tpl_vars['product_list']['contents_view_flg11'] ) || ( $this->_tpl_vars['product_list']['contents_contents12'] != '' && $this->_tpl_vars['product_list']['contents_view_flg12'] ) || ( $this->_tpl_vars['product_list']['contents_contents13'] != '' && $this->_tpl_vars['product_list']['contents_view_flg13'] ) || ( $this->_tpl_vars['product_list']['contents_contents14'] != '' && $this->_tpl_vars['product_list']['contents_view_flg14'] ) || ( $this->_tpl_vars['product_list']['contents_contents15'] != '' && $this->_tpl_vars['product_list']['contents_view_flg15'] ) || ( $this->_tpl_vars['product_list']['contents_contents16'] != '' && $this->_tpl_vars['product_list']['contents_view_flg16'] ) || ( $this->_tpl_vars['product_list']['contents_contents17'] != '' && $this->_tpl_vars['product_list']['contents_view_flg17'] ) || ( $this->_tpl_vars['product_list']['contents_contents18'] != '' && $this->_tpl_vars['product_list']['contents_view_flg18'] ) || ( $this->_tpl_vars['product_list']['contents_contents19'] != '' && $this->_tpl_vars['product_list']['contents_view_flg19'] ) || ( $this->_tpl_vars['product_list']['contents_contents20'] != '' && $this->_tpl_vars['product_list']['contents_view_flg20'] ) || ( $this->_tpl_vars['product_list']['contents_contents21'] != '' && $this->_tpl_vars['product_list']['contents_view_flg21'] ) || ( $this->_tpl_vars['product_list']['contents_contents22'] != '' && $this->_tpl_vars['product_list']['contents_view_flg22'] ) || ( $this->_tpl_vars['product_list']['contents_contents23'] != '' && $this->_tpl_vars['product_list']['contents_view_flg23'] ) || ( $this->_tpl_vars['product_list']['contents_contents24'] != '' && $this->_tpl_vars['product_list']['contents_view_flg24'] ) || ( $this->_tpl_vars['product_list']['contents_contents25'] != '' && $this->_tpl_vars['product_list']['contents_view_flg25'] ) || ( $this->_tpl_vars['product_list']['contents_contents1so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg1so'] ) || ( $this->_tpl_vars['product_list']['contents_contents2so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg2so'] ) || ( $this->_tpl_vars['product_list']['contents_contents3so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg3so'] ) || ( $this->_tpl_vars['product_list']['contents_contents4so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg4so'] ) || ( $this->_tpl_vars['product_list']['contents_contents5so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg5so'] ) || ( $this->_tpl_vars['product_list']['contents_contents6so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg6so'] ) || ( $this->_tpl_vars['product_list']['contents_contents7so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg7so'] ) || ( $this->_tpl_vars['product_list']['contents_contents8so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg8so'] ) || ( $this->_tpl_vars['product_list']['contents_contents9so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg9so'] ) || ( $this->_tpl_vars['product_list']['contents_contents10so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg10so'] ) || ( $this->_tpl_vars['product_list']['contents_contents11so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg11so'] ) || ( $this->_tpl_vars['product_list']['contents_contents12so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg12so'] ) || ( $this->_tpl_vars['product_list']['contents_contents13so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg13so'] ) || ( $this->_tpl_vars['product_list']['contents_contents14so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg14so'] ) || ( $this->_tpl_vars['product_list']['contents_contents15so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg15so'] ) || ( $this->_tpl_vars['product_list']['contents_contents16so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg16so'] ) || ( $this->_tpl_vars['product_list']['contents_contents17so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg17so'] ) || ( $this->_tpl_vars['product_list']['contents_contents18so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg18so'] ) || ( $this->_tpl_vars['product_list']['contents_contents19so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg19so'] ) || ( $this->_tpl_vars['product_list']['contents_contents20so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg20so'] ) || ( $this->_tpl_vars['product_list']['contents_contents21so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg21so'] ) || ( $this->_tpl_vars['product_list']['contents_contents22so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg22so'] ) || ( $this->_tpl_vars['product_list']['contents_contents23so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg23so'] ) || ( $this->_tpl_vars['product_list']['contents_contents24so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg24so'] ) || ( $this->_tpl_vars['product_list']['contents_contents25so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg25so'] )): ?>
							<div style="text-align:center;">
								<?php unset($this->_sections['contents_contents']);
$this->_sections['contents_contents']['name'] = 'contents_contents';
$this->_sections['contents_contents']['loop'] = is_array($_loop=$this->_tpl_vars['section_max_contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_contents']['start'] = (int)1;
$this->_sections['contents_contents']['show'] = true;
$this->_sections['contents_contents']['max'] = $this->_sections['contents_contents']['loop'];
$this->_sections['contents_contents']['step'] = 1;
if ($this->_sections['contents_contents']['start'] < 0)
    $this->_sections['contents_contents']['start'] = max($this->_sections['contents_contents']['step'] > 0 ? 0 : -1, $this->_sections['contents_contents']['loop'] + $this->_sections['contents_contents']['start']);
else
    $this->_sections['contents_contents']['start'] = min($this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] : $this->_sections['contents_contents']['loop']-1);
if ($this->_sections['contents_contents']['show']) {
    $this->_sections['contents_contents']['total'] = min(ceil(($this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] - $this->_sections['contents_contents']['start'] : $this->_sections['contents_contents']['start']+1)/abs($this->_sections['contents_contents']['step'])), $this->_sections['contents_contents']['max']);
    if ($this->_sections['contents_contents']['total'] == 0)
        $this->_sections['contents_contents']['show'] = false;
} else
    $this->_sections['contents_contents']['total'] = 0;
if ($this->_sections['contents_contents']['show']):

            for ($this->_sections['contents_contents']['index'] = $this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['iteration'] = 1;
                 $this->_sections['contents_contents']['iteration'] <= $this->_sections['contents_contents']['total'];
                 $this->_sections['contents_contents']['index'] += $this->_sections['contents_contents']['step'], $this->_sections['contents_contents']['iteration']++):
$this->_sections['contents_contents']['rownum'] = $this->_sections['contents_contents']['iteration'];
$this->_sections['contents_contents']['index_prev'] = $this->_sections['contents_contents']['index'] - $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['index_next'] = $this->_sections['contents_contents']['index'] + $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['first']      = ($this->_sections['contents_contents']['iteration'] == 1);
$this->_sections['contents_contents']['last']       = ($this->_sections['contents_contents']['iteration'] == $this->_sections['contents_contents']['total']);
?>
									<?php $this->assign('ccno', $this->_sections['contents_contents']['index']); ?>

									<?php $this->assign('contents_contents_key', ((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_contents_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>

									<?php $this->assign('contents_contents_so_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
									<?php $this->assign('contents_contents_name_so_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so_name') : smarty_modifier_cat($_tmp, 'so_name'))); ?>

									<?php $this->assign('contents_thumbnail_key', ((is_array($_tmp='contents_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_teacher_key', ((is_array($_tmp='contents_teacher')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_start_date_key', ((is_array($_tmp='contents_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_end_date_key', ((is_array($_tmp='contents_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_memo_key', ((is_array($_tmp='contents_memo')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_view_flg_key', ((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_baisoku_flg_key', ((is_array($_tmp='contents_baisoku_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('contents_view_flg_so_key', ((is_array($_tmp=((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>

									<?php $this->assign('contents_free_time_key', ((is_array($_tmp='contents_free_time')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('video_thumbnail_key', ((is_array($_tmp='video_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
									<?php $this->assign('comment_flg', 'false'); ?>
							
									<?php if (( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_key']] ) || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_so_key']] )): ?>
										<?php $this->assign('comment_flg', 'true'); ?>
										
										<?php if ($this->_tpl_vars['ccno'] != 1): ?>
											<hr style="clear:both; margin :10px auto; width:90%;" />
										<?php endif; ?>
										
										<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_thumbnail_key']] != ''): ?>
											<img src="/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_thumbnail_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=150&height=150" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')" style="cursor:pointer;" />
										<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_thumbnail_key']] != ''): ?>
											<img src="/resize_video_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_thumbnail_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=150&height=150" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')" style="cursor:pointer;" />
										<?php else: ?>
											<img src="/resize_image.php?image=noimage.jpg&width=150&height=150" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')" style="cursor:pointer;" />
										<?php endif; ?>

									<?php endif; ?>

									<?php break; ?>
								<?php endfor; endif; ?>
							</div>

							<?php if ($this->_tpl_vars['comment_flg']): ?>
								<div style="text-align:center;padding-top:10px;">
									この講座の動画を<br />試聴できます（約５分）
								</div>
							<?php endif; ?>
						
						<?php elseif ($this->_tpl_vars['product_list']['video_thumbnail1'] != ''): ?>
							<img src="/resize_video_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['video_thumbnail1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=150&height=150" alt="" />
						<?php else: ?>
							<img src="/resize_image.php?image=noimage.jpg&width=150&height=150" alt="" />
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div style="width:290px;float:right;">
				<div style="text-align:right;padding-top:10px;">
					<?php if ($this->_tpl_vars['favorite_flg']): ?>
					<form name="favoriteForm" action="/mypage/favorite.php" method="post">
					<input type="hidden" name="act" value="regist" />
					<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
						<input type="image" src="/img/list/favorite_btn.png" /><br />
					</form>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['favorite_icon_flg']): ?>
					<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
					<?php endif; ?>
				</div>

				<div class="detail_title" style="font-size:18px;color:#22730e;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
				<div style="text-align:left;">
					<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
					　（レビュー　<?php echo count($this->_tpl_vars['arr_exam2']); ?>
件）
				</div>

				<div style="text-align:right;">
					<?php $_from = $this->_tpl_vars['product_list']['icon_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['icon_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon']):
        $this->_foreach['icon_loop']['iteration']++;
?>
						<?php $this->assign('icon_cnt', $this->_foreach['icon_loop']['iteration']); ?>
						<?php if ($this->_tpl_vars['icon']['src'] != ''): ?>
							<img src="/img/<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['src'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<?php if ($this->_tpl_vars['icon_cnt'] == 2): ?><br /><?php endif; ?>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</div>

				<center>
					<table style="background-color:#F5F8EF;width:680px;">
						<?php if ($this->_tpl_vars['product_list']['teacher'] != ''): ?>
							<tr style=" border: 2px #FFFFFF solid;">
								<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
								<td style="padding: 3px 10px;text-align:left;">
									<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

									<?php if ($this->_tpl_vars['product_list']['teacher_student_id'] == $this->_tpl_vars['user_id']): ?>
										<?php if ($this->_tpl_vars['student_make_complete'] == 0): ?>
											<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
											<script type="text/javascript">
												function makingHistory(){
													var result = window.confirm('受講履歴を作成します');
													if(result){
														$.ajax({
															type: 'POST',
															url: '/product/complete.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
',
															dataType: 'html',
															success: function(data) {
																location.reload(true);
															},
															error:function() {
																//alert('通信エラーが発生しました。');
																location.reload(true);
															}
														});
													}
												}
											</script>
										<?php else: ?>
											<br><div class="btn_graywhite">受講完了</div>
										<?php endif; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>
						<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">掲載期間</th><td style="padding: 3px 10px;text-align:left;">
							<?php if ($this->_tpl_vars['product_list']['start_date'] != '' && $this->_tpl_vars['product_list']['start_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['product_list']['end_date'] != '' && $this->_tpl_vars['product_list']['end_date'] != '0000-00-00 00:00:00'): ?>
								<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

							<?php elseif ($this->_tpl_vars['product_list']['start_date'] != '' && $this->_tpl_vars['product_list']['start_date'] != '0000-00-00 00:00:00'): ?>
								<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～
							<?php elseif ($this->_tpl_vars['product_list']['end_date'] != '' && $this->_tpl_vars['product_list']['end_date'] != '0000-00-00 00:00:00'): ?>
								～<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

							<?php else: ?>
								未定
							<?php endif; ?>
						</td></tr>
						<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">総時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['all_play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
						<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円(税込)<?php endif; ?></td></tr>-->
						<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">商品説明</th><td style="padding: 3px 10px;text-align:left;word-break:break-all;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td></tr>
					</table>
				</center>

				<!--
				<?php if ($this->_tpl_vars['product_list']['hantei_ari']): ?>
					<?php if (array_search ( '1' , $this->_tpl_vars['product_list']['arr_product_disp_warning_word'] ) !== false): ?>
						<div style="text-align:left;padding-top:20px;color:red;">
							※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
							※各パートのテストに全問正解しないと，次のパートに進むことができません。<br>
							※テストを全て受けて全問正解しないと，受講完了にはなりません。<br>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<?php if (array_search ( '2' , $this->_tpl_vars['product_list']['arr_product_disp_warning_word'] ) !== false): ?>
						<div style="text-align:left;padding-top:20px;color:red;">
							※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
							※テストを全て受けないと，受講完了にはなりません。<br>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				-->

				<?php if (( $this->_tpl_vars['product_list']['contents_contents1so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents2so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents3so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents4so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents5so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents6so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents7so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents8so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents9so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents10so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents11so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents12so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents13so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents14so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents15so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents16so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents17so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents18so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents19so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents20so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents21so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents22so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents23so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents24so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents25so'] != '' )): ?>
					<div style="text-align:left;padding-top:20px;color:#000000;">
						※本講座は，音声のみを聴取することが可能です。ただし，動画を途中まで再生した後に，続きを音声のみ再生すること，及びその逆はできませんので，ご注意ください。また，講座の受講率及び受講完了確認は，動画での視聴を基に記録されます。
						<span style="color:red;">音声のみを最後まで聴取したとしても，講座の受講完了とは記録されませんので，ご注意ください。</span>
					</div>
				<?php endif; ?>
			</div>

			<div style="clear:both;text-align:center;padding:20px;">
				<?php if ($this->_tpl_vars['buy_wait_flg']): ?>
					<img src="/img/lecture/buy_wait.png" alt="購入手続き中" /><br />
				<?php else: ?>
					<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
					<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="hidden" name="hid_product_type_add" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_type_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
						<input type="image" src="/img/button/buy_process_btn.jpg" alt="買い物かごに入れる" /><br />
					</form>
				<?php endif; ?>
			</div>
		</div>

		<?php else: ?>
		<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="text-align:right;padding-top:10px;">
				<?php if ($this->_tpl_vars['favorite_flg']): ?>
				<form name="favoriteForm" action="/mypage/favorite.php" method="post">
				<input type="hidden" name="act" value="regist" />
				<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="image" src="/img/list/favorite_btn.png" /><br />
				</form>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['favorite_icon_flg']): ?>
				<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
				<?php endif; ?>
			</div>

			<div class="detail_title" style="font-size:18px;color:#22730e;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>

			<div style="text-align:left;">
				<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
				　（レビュー　<?php echo count($this->_tpl_vars['arr_exam2']); ?>
件）
			</div>
				
			<div style="text-align:right;">
				<?php $_from = $this->_tpl_vars['product_list']['icon_img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['icon_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['icon_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['icon']):
        $this->_foreach['icon_loop']['iteration']++;
?>
				<?php $this->assign('icon_cnt', $this->_foreach['icon_loop']['iteration']); ?>
					<?php if ($this->_tpl_vars['icon']['src'] != ''): ?>
						<img src="/img/<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['src'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['icon']['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
						<?php if ($this->_tpl_vars['icon_cnt'] == 2): ?><br /><?php endif; ?>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</div>
				
			<center>
				<table style="background-color:#F5F8EF;width:680px;">
					<?php if ($this->_tpl_vars['product_list']['teacher'] != ''): ?>
						<tr style=" border: 2px #FFFFFF solid;">
							<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
							<td style="padding: 3px 10px;text-align:left;">
								<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

								<?php if ($this->_tpl_vars['product_list']['teacher_student_id'] == $this->_tpl_vars['user_id']): ?>
									<?php if ($this->_tpl_vars['student_make_complete'] == 0): ?>
										<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
										<script type="text/javascript">
											function makingHistory(){
												var result = window.confirm('受講履歴を作成します');
												if(result){
													$.ajax({
														type: 'POST',
														url: '/product/complete.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
',
														dataType: 'html',
														success: function(data) {
															location.reload(true);
														},
														error:function() {
															//alert('通信エラーが発生しました。');
															location.reload(true);
														}
													});
												}
											}
										</script>
									<?php else: ?>
										<br><div class="btn_graywhite">受講完了</div>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">掲載期間</th><td style="padding: 3px 10px;text-align:left;">
						<?php if ($this->_tpl_vars['product_list']['start_date'] != '' && $this->_tpl_vars['product_list']['start_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['product_list']['end_date'] != '' && $this->_tpl_vars['product_list']['end_date'] != '0000-00-00 00:00:00'): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php elseif ($this->_tpl_vars['product_list']['start_date'] != '' && $this->_tpl_vars['product_list']['start_date'] != '0000-00-00 00:00:00'): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～
						<?php elseif ($this->_tpl_vars['product_list']['end_date'] != '' && $this->_tpl_vars['product_list']['end_date'] != '0000-00-00 00:00:00'): ?>
							～<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						<?php else: ?>
							未定
						<?php endif; ?>
					</td></tr>
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">総時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['all_play_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
					<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円(税込)<?php endif; ?></td></tr>-->
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">商品説明</th><td style="padding: 3px 10px;text-align:left;word-break:break-all;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td></tr>
				</table>
			</center>

			<?php if ($this->_tpl_vars['product_list']['hantei_ari']): ?>
				<?php if (array_search ( '1' , $this->_tpl_vars['product_list']['arr_product_disp_warning_word'] ) !== false): ?>
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※各パートのテストに全問正解しないと，次のパートに進むことができません。<br>
						※テストを全て受けて全問正解しないと，受講完了にはなりません。<br>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<?php if (array_search ( '2' , $this->_tpl_vars['product_list']['arr_product_disp_warning_word'] ) !== false): ?>
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※テストを全て受けないと，受講完了にはなりません。<br>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if (( $this->_tpl_vars['product_list']['contents_contents1so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents2so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents3so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents4so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents5so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents6so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents7so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents8so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents9so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents10so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents11so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents12so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents13so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents14so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents15so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents16so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents17so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents18so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents19so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents20so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents21so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents22so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents23so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents24so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents25so'] != '' )): ?>
				<div style="text-align:left;padding-top:20px;color:#000000;">
					※本講座は，音声のみを聴取することが可能です。ただし，動画を途中まで再生した後に，続きを音声のみ再生すること，及びその逆はできませんので，ご注意ください。また，講座の受講率及び受講完了確認は，動画での視聴を基に記録されます。
					<span style="color:red;">音声のみを最後まで聴取したとしても，講座の受講完了とは記録されませんので，ご注意ください。</span>
				</div>
			<?php endif; ?>

			<br style="clear:both;" /><div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>

			<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修資料</div>

			<div style="text-align:center;padding:20px 0;">
				<input type="image" src="/img/lecture/dl_btn.png" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();">
			</div>
				
			<?php if (array_search ( '3' , $this->_tpl_vars['product_list']['arr_product_disp_warning_word'] ) !== false): ?>
				<div style="text-align:left;padding-top:20px;color:red;">&nbsp;<!-- 注意文言（イ） --></div>
			<?php endif; ?>
				
			<div>
				<?php if (( $this->_tpl_vars['product_list']['contents_contents1'] != '' && $this->_tpl_vars['product_list']['contents_view_flg1'] ) || ( $this->_tpl_vars['product_list']['contents_contents2'] != '' && $this->_tpl_vars['product_list']['contents_view_flg2'] ) || ( $this->_tpl_vars['product_list']['contents_contents3'] != '' && $this->_tpl_vars['product_list']['contents_view_flg3'] ) || ( $this->_tpl_vars['product_list']['contents_contents4'] != '' && $this->_tpl_vars['product_list']['contents_view_flg4'] ) || ( $this->_tpl_vars['product_list']['contents_contents5'] != '' && $this->_tpl_vars['product_list']['contents_view_flg5'] ) || ( $this->_tpl_vars['product_list']['contents_contents6'] != '' && $this->_tpl_vars['product_list']['contents_view_flg6'] ) || ( $this->_tpl_vars['product_list']['contents_contents7'] != '' && $this->_tpl_vars['product_list']['contents_view_flg7'] ) || ( $this->_tpl_vars['product_list']['contents_contents8'] != '' && $this->_tpl_vars['product_list']['contents_view_flg8'] ) || ( $this->_tpl_vars['product_list']['contents_contents9'] != '' && $this->_tpl_vars['product_list']['contents_view_flg9'] ) || ( $this->_tpl_vars['product_list']['contents_contents10'] != '' && $this->_tpl_vars['product_list']['contents_view_flg10'] ) || ( $this->_tpl_vars['product_list']['contents_contents11'] != '' && $this->_tpl_vars['product_list']['contents_view_flg11'] ) || ( $this->_tpl_vars['product_list']['contents_contents12'] != '' && $this->_tpl_vars['product_list']['contents_view_flg12'] ) || ( $this->_tpl_vars['product_list']['contents_contents13'] != '' && $this->_tpl_vars['product_list']['contents_view_flg13'] ) || ( $this->_tpl_vars['product_list']['contents_contents14'] != '' && $this->_tpl_vars['product_list']['contents_view_flg14'] ) || ( $this->_tpl_vars['product_list']['contents_contents15'] != '' && $this->_tpl_vars['product_list']['contents_view_flg15'] ) || ( $this->_tpl_vars['product_list']['contents_contents16'] != '' && $this->_tpl_vars['product_list']['contents_view_flg16'] ) || ( $this->_tpl_vars['product_list']['contents_contents17'] != '' && $this->_tpl_vars['product_list']['contents_view_flg17'] ) || ( $this->_tpl_vars['product_list']['contents_contents18'] != '' && $this->_tpl_vars['product_list']['contents_view_flg18'] ) || ( $this->_tpl_vars['product_list']['contents_contents19'] != '' && $this->_tpl_vars['product_list']['contents_view_flg19'] ) || ( $this->_tpl_vars['product_list']['contents_contents20'] != '' && $this->_tpl_vars['product_list']['contents_view_flg20'] ) || ( $this->_tpl_vars['product_list']['contents_contents21'] != '' && $this->_tpl_vars['product_list']['contents_view_flg21'] ) || ( $this->_tpl_vars['product_list']['contents_contents22'] != '' && $this->_tpl_vars['product_list']['contents_view_flg22'] ) || ( $this->_tpl_vars['product_list']['contents_contents23'] != '' && $this->_tpl_vars['product_list']['contents_view_flg23'] ) || ( $this->_tpl_vars['product_list']['contents_contents24'] != '' && $this->_tpl_vars['product_list']['contents_view_flg24'] ) || ( $this->_tpl_vars['product_list']['contents_contents25'] != '' && $this->_tpl_vars['product_list']['contents_view_flg25'] ) || ( $this->_tpl_vars['product_list']['contents_contents1so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg1so'] ) || ( $this->_tpl_vars['product_list']['contents_contents2so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg2so'] ) || ( $this->_tpl_vars['product_list']['contents_contents3so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg3so'] ) || ( $this->_tpl_vars['product_list']['contents_contents4so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg4so'] ) || ( $this->_tpl_vars['product_list']['contents_contents5so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg5so'] ) || ( $this->_tpl_vars['product_list']['contents_contents6so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg6so'] ) || ( $this->_tpl_vars['product_list']['contents_contents7so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg7so'] ) || ( $this->_tpl_vars['product_list']['contents_contents8so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg8so'] ) || ( $this->_tpl_vars['product_list']['contents_contents9so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg9so'] ) || ( $this->_tpl_vars['product_list']['contents_contents10so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg10so'] ) || ( $this->_tpl_vars['product_list']['contents_contents11so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg11so'] ) || ( $this->_tpl_vars['product_list']['contents_contents12so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg12so'] ) || ( $this->_tpl_vars['product_list']['contents_contents13so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg13so'] ) || ( $this->_tpl_vars['product_list']['contents_contents14so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg14so'] ) || ( $this->_tpl_vars['product_list']['contents_contents15so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg15so'] ) || ( $this->_tpl_vars['product_list']['contents_contents16so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg16so'] ) || ( $this->_tpl_vars['product_list']['contents_contents17so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg17so'] ) || ( $this->_tpl_vars['product_list']['contents_contents18so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg18so'] ) || ( $this->_tpl_vars['product_list']['contents_contents19so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg19so'] ) || ( $this->_tpl_vars['product_list']['contents_contents20so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg20so'] ) || ( $this->_tpl_vars['product_list']['contents_contents21so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg21so'] ) || ( $this->_tpl_vars['product_list']['contents_contents22so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg22so'] ) || ( $this->_tpl_vars['product_list']['contents_contents23so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg23so'] ) || ( $this->_tpl_vars['product_list']['contents_contents24so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg24so'] ) || ( $this->_tpl_vars['product_list']['contents_contents25so'] != '' && $this->_tpl_vars['product_list']['contents_view_flg25so'] )): ?>
					<div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>
					<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修パート一覧</div>
					<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>

					<?php unset($this->_sections['contents_contents']);
$this->_sections['contents_contents']['name'] = 'contents_contents';
$this->_sections['contents_contents']['loop'] = is_array($_loop=$this->_tpl_vars['section_max_contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_contents']['start'] = (int)1;
$this->_sections['contents_contents']['show'] = true;
$this->_sections['contents_contents']['max'] = $this->_sections['contents_contents']['loop'];
$this->_sections['contents_contents']['step'] = 1;
if ($this->_sections['contents_contents']['start'] < 0)
    $this->_sections['contents_contents']['start'] = max($this->_sections['contents_contents']['step'] > 0 ? 0 : -1, $this->_sections['contents_contents']['loop'] + $this->_sections['contents_contents']['start']);
else
    $this->_sections['contents_contents']['start'] = min($this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] : $this->_sections['contents_contents']['loop']-1);
if ($this->_sections['contents_contents']['show']) {
    $this->_sections['contents_contents']['total'] = min(ceil(($this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] - $this->_sections['contents_contents']['start'] : $this->_sections['contents_contents']['start']+1)/abs($this->_sections['contents_contents']['step'])), $this->_sections['contents_contents']['max']);
    if ($this->_sections['contents_contents']['total'] == 0)
        $this->_sections['contents_contents']['show'] = false;
} else
    $this->_sections['contents_contents']['total'] = 0;
if ($this->_sections['contents_contents']['show']):

            for ($this->_sections['contents_contents']['index'] = $this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['iteration'] = 1;
                 $this->_sections['contents_contents']['iteration'] <= $this->_sections['contents_contents']['total'];
                 $this->_sections['contents_contents']['index'] += $this->_sections['contents_contents']['step'], $this->_sections['contents_contents']['iteration']++):
$this->_sections['contents_contents']['rownum'] = $this->_sections['contents_contents']['iteration'];
$this->_sections['contents_contents']['index_prev'] = $this->_sections['contents_contents']['index'] - $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['index_next'] = $this->_sections['contents_contents']['index'] + $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['first']      = ($this->_sections['contents_contents']['iteration'] == 1);
$this->_sections['contents_contents']['last']       = ($this->_sections['contents_contents']['iteration'] == $this->_sections['contents_contents']['total']);
?>
						<?php $this->assign('ccno', $this->_sections['contents_contents']['index']); ?>

						<?php $this->assign('contents_contents_key', ((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_contents_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>

						<?php $this->assign('contents_contents_so_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
						<?php $this->assign('contents_contents_so_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so_name') : smarty_modifier_cat($_tmp, 'so_name'))); ?>

						<?php $this->assign('contents_thumbnail_key', ((is_array($_tmp='contents_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_teacher_key', ((is_array($_tmp='contents_teacher')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_start_date_key', ((is_array($_tmp='contents_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_end_date_key', ((is_array($_tmp='contents_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_memo_key', ((is_array($_tmp='contents_memo')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_view_flg_key', ((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_baisoku_flg_key', ((is_array($_tmp='contents_baisoku_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('contents_view_flg_so_key', ((is_array($_tmp=((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
						<?php $this->assign('contents_free_time_key', ((is_array($_tmp='contents_free_time')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('video_thumbnail_key', ((is_array($_tmp='video_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('video_duration_key', ((is_array($_tmp='video_duration')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('video_reading_key', ((is_array($_tmp='video_reading')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('video_view_flg_key', ((is_array($_tmp='video_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('video_view_flg_so_key', ((is_array($_tmp=((is_array($_tmp='video_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
						<?php $this->assign('video_complete_flg_key', ((is_array($_tmp='video_complete_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_id_test_key', ((is_array($_tmp='exam_id_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_id_question_key', ((is_array($_tmp='exam_id_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_test_all_answered_key', ((is_array($_tmp='exam_test_all_answered')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_question_all_answered_key', ((is_array($_tmp='exam_question_all_answered')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_test_passing_flg_key', ((is_array($_tmp='exam_test_passing_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('exam_question_passing_flg_key', ((is_array($_tmp='exam_question_passing_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('display_format_test_key', ((is_array($_tmp='display_format_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('display_format_question_key', ((is_array($_tmp='display_format_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('public_flag_test_key', ((is_array($_tmp='public_flag_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('public_flag_question_key', ((is_array($_tmp='public_flag_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('resubmit_flag_test_key', ((is_array($_tmp='resubmit_flag_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('resubmit_flag_question_key', ((is_array($_tmp='resubmit_flag_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('submit_possible_flg_test_key', ((is_array($_tmp='submit_possible_flg_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('submit_possible_flg_question_key', ((is_array($_tmp='submit_possible_flg_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('btn_type_key', ((is_array($_tmp='btn_type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						<?php $this->assign('disp_warning_word_key', ((is_array($_tmp='disp_warning_word')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
						
												<?php if ($this->_tpl_vars['product_list']['product_kind_flg'] == 3): ?>
							<?php $this->assign('ccno_prev', $this->_sections['contents_contents']['index']-1); ?>
							<?php $this->assign('video_complete_flg_key_prev', ((is_array($_tmp='video_complete_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_id_test_key_prev', ((is_array($_tmp='exam_id_test')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_id_question_key_prev', ((is_array($_tmp='exam_id_question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_test_all_answered_key_prev', ((is_array($_tmp='exam_test_all_answered')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_question_all_answered_key_prev', ((is_array($_tmp='exam_question_all_answered')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_test_passing_flg_key_prev', ((is_array($_tmp='exam_test_passing_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							<?php $this->assign('exam_question_passing_flg_key_prev', ((is_array($_tmp='exam_question_passing_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno_prev']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno_prev']))); ?>
							
							<?php if (( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_key']] ) || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_so_key']] )): ?>
								 <?php if ($this->_tpl_vars['ccno'] == 1): ?>
								 	<center>
										<table style="background-color:#F5F8EF;width:680px;">
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
、<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_duration_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_reading_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>未視聴<?php endif; ?></td></tr>
										</table>
									</center>
									
									<center style="float:left;display: inline-block;width: 400px;">
										<div class="detail_btn" style="text-align: left;">
											<!--[ TYPE1 ]-->
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
											<!-- PC -->
											<?php if (! $this->_tpl_vars['is_sp']): ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<?php else: ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<?php endif; ?>
												<?php endif; ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
													<br>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<?php else: ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
											<!-- SP -->
											<?php if ($this->_tpl_vars['is_sp']): ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<?php else: ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<?php endif; ?>
												<?php endif; ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
													<br>
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<?php else: ?>
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										</div>

										<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_complete_flg_key']] == 1): ?>
																						<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
													<div class="detail_btn">
														<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																														<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
															<?php endif; ?>
														<?php else: ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																																		<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																																		<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php else: ?>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																<?php endif; ?>
															<?php else: ?>
																提出期限外のため、受講不可です
															<?php endif; ?>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0): ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
													<div class="detail_btn">
														<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																														<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
">受講済</a>
																														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
																														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
															<?php endif; ?>
														<?php else: ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																																		<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																																		<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																			<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php else: ?>
																			<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php else: ?>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																<?php endif; ?>
															<?php else: ?>
																提出期限外のため、受講不可です
															<?php endif; ?>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_question_key']] == '0'): ?>
													<div class="detail_btn">
														<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_question_key']]): ?>
															<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_question_key']] == '1' )): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '0'): ?>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']]): ?>
																		<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																	<?php else: ?>
																		<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																	<?php endif; ?>
																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '1' || $this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '2'): ?>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']]): ?>
																		<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																	<?php else: ?>
																		<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																	<?php endif; ?>
																<?php endif; ?>
															<?php else: ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '0'): ?>
																	<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '1' || $this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '2'): ?>
																	<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																<?php endif; ?>
															<?php endif; ?>
														<?php else: ?>
															提出期限外のため、受講不可です
														<?php endif; ?>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php else: ?>
											<div class="detail_btn"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?></div>
										<?php endif; ?>
										</center>
										
										<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '1' && array_search ( '3' , $this->_tpl_vars['product_list'][$this->_tpl_vars['disp_warning_word_key']] ) !== false): ?>
											<center style="float:right;width:40%;text-align:center;word-break:break-all;">
												<span>&nbsp;<!-- 注意文言(ウ) --></span>
											</center>
										<?php endif; ?>
										<br style="clear:both;" />
										<br style="clear:both;" />
									<?php else: ?>
										<center>
											<table style="background-color:#F5F8EF;width:680px;">
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
、<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_duration_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_reading_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>未視聴<?php endif; ?></td></tr>
											</table>
										</center>

										<center style="float:left;display: inline-block;width: 400px;">
											<div class="detail_btn" style="text-align: left;">
																								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key_prev']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key_prev']] > 0): ?>
																										<?php if (( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key_prev']] == '1' ) || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_passing_flg_key_prev']] == '1' )): ?>
														<!--[ TYPE2 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<?php if (! $this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>

															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<?php if ($this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


																										<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_complete_flg_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key_prev']] <= 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key_prev']] <= 0): ?>
														<!--[ TYPE3 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<?php if (! $this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>

																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>

															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<?php if ($this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<?php else: ?>


														<!--[ TYPE4 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<?php if (! $this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png);"  >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png);"  >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);"  >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<?php if ($this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<?php endif; ?>
												<?php else: ?>
																										<?php if ($this->_tpl_vars['product_list']['all_passing_flg']): ?>


														<!--[ TYPE5 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<?php if (! $this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>

															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<?php if ($this->_tpl_vars['is_sp']): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																<br>
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php else: ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<?php else: ?>
																												<?php if ($this->_tpl_vars['product_list']['now_passing_contents_no'] > 0 && ( ( $this->_tpl_vars['product_list']['now_passing_contents_no']+1 ) >= $this->_tpl_vars['ccno'] )): ?>


															<!--[ TYPE6 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<?php if (! $this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<?php if ($this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


																												<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_complete_flg_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key_prev']] <= 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key_prev']] <= 0): ?>


															<!--[ TYPE7 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<?php if (! $this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<?php if ($this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


														<?php else: ?>


															<!--[ TYPE8 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<?php if (! $this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png);" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<?php if ($this->_tpl_vars['is_sp']): ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																	<br>
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php else: ?>
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


														<?php endif; ?>
													<?php endif; ?>
												<?php endif; ?>
											</div>

																						<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key_prev']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key_prev']] > 0): ?>
																								<?php if (( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key_prev']] == '1' ) || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key_prev']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_passing_flg_key_prev']] == '1' )): ?>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_complete_flg_key']] == 1): ?>
																												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
																<div class="detail_btn">
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																			<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																			<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																			<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																		<?php endif; ?>
																	<?php else: ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																			<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																																								<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																																								<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																			<?php endif; ?>
																		<?php else: ?>
																			提出期限外のため、受講不可です
																		<?php endif; ?>
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
																<div class="detail_btn">
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																			<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
">受講済</a>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																			<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																			<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
																		<?php endif; ?>
																	<?php else: ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																			<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																																								<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																																								<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																						<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php else: ?>
																						<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																			<?php endif; ?>
																		<?php else: ?>
																			提出期限外のため、受講不可です
																		<?php endif; ?>
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_question_key']] == '0'): ?>
																<div class="detail_btn">
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_question_key']]): ?>
																		<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_question_key']] == '1' )): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '0'): ?>
																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']]): ?>
																					<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																				<?php else: ?>
																					<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																				<?php endif; ?>
																			<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '1' || $this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '2'): ?>
																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']]): ?>
																					<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																				<?php else: ?>
																					<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php else: ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '0'): ?>
																				<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																			<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '1' || $this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '2'): ?>
																				<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php else: ?>
																		提出期限外のため、受講不可です
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														<?php endif; ?>
													<?php else: ?>
														<div class="detail_btn"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?></div>
													<?php endif; ?>
												<?php else: ?>
													<div class="detail_btn"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?></div>
												<?php endif; ?>


																						<?php else: ?>
											<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_complete_flg_key']] == 1): ?>
																								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
														<div class="detail_btn">
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																	<a href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																	<a href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																	<a href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																<?php endif; ?>
															<?php else: ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																	<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&qid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php else: ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																	<?php endif; ?>
																<?php else: ?>
																	提出期限外のため、受講不可です
																<?php endif; ?>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0): ?>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0'): ?>
														<div class="detail_btn">
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '1'): ?>
																																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																	<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
">受講済</a>
																																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																	<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
																																<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																	<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1">受講済</a>
																<?php endif; ?>
															<?php else: ?>
																<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_test_key']]): ?>
																	<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_passing_flg_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_test_key']] == '1' )): ?>
																																				<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '0'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a class="test_btn" href="/exam/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '1'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a class="test_btn" href="/exam/result1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a class="test_btn" href="/exam/index1.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																																				<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_test_key']] === '2'): ?>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_test_all_answered_key']]): ?>
																				<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php else: ?>
																				<a class="test_btn" href="/exam/index2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']]; ?>
&eno=1"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php else: ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?>
																	<?php endif; ?>
																<?php else: ?>
																	提出期限外のため、受講不可です
																<?php endif; ?>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?>
													<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_question_key']] == '0'): ?>
														<div class="detail_btn" style="text-align: left;">
															<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['submit_possible_flg_question_key']]): ?>
																<?php if (! $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_question_all_answered_key']] && $this->_tpl_vars['product_list'][$this->_tpl_vars['resubmit_flag_question_key']] == '1' )): ?>


																	<!--[ TYPE9 ]-->
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
																	<!-- PC -->
																	<?php if (! $this->_tpl_vars['is_sp']): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<?php else: ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																			<br>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<?php else: ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
																	<!-- SP -->
																	<?php if ($this->_tpl_vars['is_sp']): ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<?php else: ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
																			<br>
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<?php else: ?>
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


																<?php else: ?>
																	<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '0'): ?>
																		<a class="test_btn" href="/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
">受講済</a>
																	<?php elseif ($this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '1' || $this->_tpl_vars['product_list'][$this->_tpl_vars['display_format_question_key']] === '2'): ?>
																		<a class="test_btn" href="/exam/result2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']]; ?>
&eno=1">受講済</a>
																	<?php endif; ?>
																<?php endif; ?>
															<?php else: ?>
																提出期限外のため、受講不可です
															<?php endif; ?>
														</div>
													<?php endif; ?>
												<?php endif; ?>
											<?php else: ?>
												<div class="detail_btn"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_test_key']] > 0 || $this->_tpl_vars['product_list'][$this->_tpl_vars['exam_id_question_key']] > 0): ?><a class="test_btn_none" href="javascript:void(0)"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '2'): ?>アンケートに回答する<?php else: ?>テストを受ける<?php endif; ?></a><?php endif; ?></div>
											<?php endif; ?>
									<?php endif; ?>
									</center>
									
									<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['public_flag_test_key']] == '0' && $this->_tpl_vars['product_list'][$this->_tpl_vars['btn_type_key']] == '1' && array_search ( '3' , $this->_tpl_vars['product_list'][$this->_tpl_vars['disp_warning_word_key']] ) !== false): ?>
										<center style="float:right;width:40%;text-align:center;word-break:break-all;">
											<span>&nbsp;<!-- 注意文言(ウ) --></span>
										</center>
									<?php endif; ?>
									<br style="clear:both;" />
									<br style="clear:both;" />
								<?php endif; ?>
							<?php endif; ?>
							
												<?php else: ?>
							<?php if (( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_key']] ) || ( $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] != '' && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_view_flg_so_key']] )): ?>
								<center>
								<table style="background-color:#F5F8EF;width:680px;">
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
、<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_duration_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_reading_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>未視聴<?php endif; ?></td></tr>
								</table>
								</center>
								
								<center style="float:left;display: inline-block;width: 400px;">
									<div class="detail_btn" style="text-align: left;">


										<!--[ TYPE10 ]-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!-- PC -->
										<?php if (! $this->_tpl_vars['is_sp']): ?>
											<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<?php else: ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
												<br>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<?php else: ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!-- SP -->
										<?php if ($this->_tpl_vars['is_sp']): ?>
											<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','','')">
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','','')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<?php else: ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0 && $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
												<br>
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;"><!--1.0倍&nbsp;--></div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_so_key']]): ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<?php else: ?>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


									</div>
								</center>

								<br style="clear:both;" />
								<br style="clear:both;" />
							<?php endif; ?>
						<?php endif; ?>
					<?php endfor; endif; ?>

					<div style="padding:5px;text-align:center;" id="exam3_btn_area">
						※受講状況（受講率等）の表示は，１日１回更新されます。
					</div>
					<br />
				<?php endif; ?>
			</div>

		</div>
	<?php endif; ?>
	
<?php elseif ($this->_tpl_vars['product_list']['product_type_add'] == 2): ?>
	<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
		<div>
			<div style="text-align:right;">
				<?php if ($this->_tpl_vars['favorite_flg']): ?>
					<form name="favoriteForm" action="/mypage/favorite.php" method="post">
						<input type="hidden" name="act" value="regist" />
						<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
						<input type="image" src="/img/list/favorite_btn.png" /><br />
					</form>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['favorite_icon_flg']): ?>
					<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
				<?php endif; ?>
			</div>

			<div class="detail_title" style="font-size:18px;color:#22730e; text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
								<div style="text-align:left;">
					<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
					　（レビュー　<?php echo count($this->_tpl_vars['arr_exam2']); ?>
件）
				</div>

		<table style="background-color:#F5F8EF;width:680px;margin:20px;">
			<?php if ($this->_tpl_vars['product_list']['memo2'] != ''): ?>
			<!--
			<tr style=" border: 2px #FFFFFF solid;">
				<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
				<td style="padding: 3px 10px;text-align:left;">
					<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<?php if ($this->_tpl_vars['product_list']['teacher_student_id'] == $this->_tpl_vars['user_id']): ?>
						<?php if ($this->_tpl_vars['student_make_complete'] == 0): ?>
							<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
							<script type="text/javascript">
								function makingHistory(){
									var result = window.confirm('受講履歴を作成します');
									if(result){
										$.ajax({
											type: 'POST',
											url: '/product/complete.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
',
											dataType: 'html',
											success: function(data) {
												location.reload(true);
											},
											error:function() {
												//alert('通信エラーが発生しました。');
												location.reload(true);
											}
										});
									}
								}
							</script>
						<?php else: ?>
							<br><div class="btn_graywhite">受講完了</div>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			-->
			<?php endif; ?>
			<?php if ($this->_tpl_vars['product_list']['memo2'] != ''): ?>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td></tr>
			<?php endif; ?>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">主催</th><td style="padding: 3px 10px;text-align:left;"><?php echo $this->_tpl_vars['product_list']['disp_sponsor']; ?>
</td></tr>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">受付期間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">開催日</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['disp_dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
		</table>
	</div>

	<div style="padding-top:20px;word-break:break-all;">
		<?php if ($this->_tpl_vars['product_list']['memo1'] != ''): ?>
			■研修の内容<br />
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
<br /><br />
		<?php endif; ?>
		<?php if ($this->_tpl_vars['product_list']['memo3'] != ''): ?>
			■日時詳細<br />
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo3'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
<br /><br />
		<?php endif; ?>
		<?php if ($this->_tpl_vars['product_list']['memo4'] != ''): ?>
			■問い合わせ先<br />
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo4'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
<br /><br />
		<?php endif; ?>
		<?php if ($this->_tpl_vars['product_list']['memo5'] != ''): ?>
			■受講資格/他会員の受講等<br />
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo5'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
<br /><br />
		<?php endif; ?>
		<?php if ($this->_tpl_vars['product_list']['contents'] != ''): ?>
			■備考<br />
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

		<?php endif; ?>
	</div>
	
	<?php if ($this->_tpl_vars['nichibenren_tandoku_flg']): ?>
		<div id="search_info_area" style="padding-top:20px;">
			<form name="search_info" action="<?php echo $this->_tpl_vars['search_url']; ?>
" method="post">
			<input type="hidden" id="act" name="act" value="info_check" />
				<div>
					受講を希望する会場を選択する
				</div>
				<div style="text-align:center;">
					<?php echo smarty_function_html_options(array('name' => 'bar_association_id','options' => $this->_tpl_vars['bar_association'],'selected' => $this->_tpl_vars['bar_association_id'],'onchange' => "search_bar_association_branch()"), $this);?>

					<?php echo smarty_function_html_options(array('name' => 'bar_association_branch_id','options' => $this->_tpl_vars['bar_association_branch'],'selected' => $this->_tpl_vars['bar_association_branch_id']), $this);?>

					<br />
					<input type="image" src="/img/lecture/confirm_btn.png" name="submit">
					<!--<input type="submit" value="選択した研修会場の詳細情報を確認" />-->
				</div>
			</form>
			<?php if ($this->_tpl_vars['bar_association_branch_info']): ?>
				<div>
					<table style="background-color:#F5F8EF;width:450px;">
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">弁護士会名</th><td style="padding: 3px 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</ td>
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;">定員</th><td style="padding: 3px 10px;"><?php if ($this->_tpl_vars['bar_association_branch_info']['capacity'] == 9999): ?>制限なし<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['capacity'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
名<?php endif; ?></ td>
					</tr>
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">会場</th><td style="padding: 3px 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['hall'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</ td>
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;">受付</th><td style="padding: 3px 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['receptionist_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['receptionist_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</ td>
					</tr>
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">備考</th><td colspan="3" style="padding: 3px 10px;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['bar_association_branch_info']['contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
					</tr>
					</table>
				</div>
				
				<div style="text-align:center;padding:20px 0 15px 0;">
					詳細は、各弁護士会にお問い合わせください。
				</div>
				<?php if ($this->_tpl_vars['disp_web_flg']): ?>
					<?php if ($this->_tpl_vars['bar_association_branch_info']['web_flg'] == '1'): ?>
						<div style="clear:both;text-align:center;padding:20px;">
						<?php if ($this->_tpl_vars['capacity_alert_flg']): ?>
							<span style="color:red;">お申込み人数が定員に達したため、お申込みすることができません。</span>
						<?php else: ?>
							<?php if ($this->_tpl_vars['buy_wait_flg']): ?>
								<img src="/img/lecture/buy_wait.png" alt="購入手続き中" />
							<?php else: ?>
								<?php if ($this->_tpl_vars['buy_flg']): ?>
									<?php if ($this->_tpl_vars['kaijo_moushikomi_flg']): ?>
										<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
										<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
										<input type="hidden" name="hid_product_type_add" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_type_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
										<input type="hidden" name="hid_bar_association_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
										<input type="hidden" name="hid_bar_association_branch_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_branch_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
											<input type="image" src="/img/btn/apply_btn.png" alt="この研修を申し込む" /><br />
										</form>
									<?php else: ?>
										<span style="color:red;font-size:18px;">現在お持ちの研修パスポートは，<br />研修開催日までに期限切れとなります。</span>
										<div style="text-align:left;padding-top:10px;">
											１ 現在お申し込みいただける方法<br />
											・弁護士会窓口で申し込む（申込方法は，実施会にお問い合わせください。）<br />
											受講料は，個別にお支払いいただくか，研修実施日までに，研修パスポートを更新（継続購入）してください。<br />
											<br />
											２ 研修パスポートの更新（継続購入）方法<br />
											研修パスポートは，有効期限の１ヶ月前から更新（継続購入）が可能です。<br />
											当サイトトップページの「研修パスポートのご案内」から購入の手続をしてください。
										</div>
									<?php endif; ?>
								<?php else: ?>
									<img src="/img/lecture/buy_fix.png" alt="申込み済" />
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
						</div>
					<?php elseif ($this->_tpl_vars['bar_association_branch_info']['web_flg'] == '2'): ?>
						<div style="clear:both;text-align:center;padding:20px;font-size:18px;color:red;">
							研修を実施しません。
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<script type="text/javascript">
			function search_bar_association_branch(){
				document.getElementById("act").value = "search_branch";
				document.search_info.submit();
			}
			</script>
		</div>
	<?php endif; ?>
</div>
	
<?php elseif ($this->_tpl_vars['product_list']['product_type_add'] == 3): ?>
<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
	<div class="detail_title" style="font-size:18px;color:#22730e; text-align:"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
	<div style="padding:30px 15px;text-align:center;font-size:20px;color:#000000;font-weight:bold;">
		「講義：<?php if ($this->_tpl_vars['all_view_flg']): ?>受講済<?php elseif ($this->_tpl_vars['view_flg']): ?>受講中<?php else: ?>未受講<?php endif; ?>」
		「テスト・解説：<?php if (! $this->_tpl_vars['ethic_status']): ?>未受講<?php elseif ($this->_tpl_vars['ethic_status'] === '2' || $this->_tpl_vars['ethic_status'] === '5' || $this->_tpl_vars['ethic_status'] === '6' || $this->_tpl_vars['ethic_status'] === '7' || $this->_tpl_vars['ethic_status'] === '8'): ?>受講済<?php else: ?>受講中<?php endif; ?>」
	</div>
	<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">商品説明</div>
	<div style="padding:10px 40px;word-break:break-all;">
		<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

	</div>
	<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修資料</div>
	
	<div style="text-align:center;padding:20px 0;">
		<input type="image" src="/img/lecture/dl_btn.png" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();">
		<!--<input type="button" value="資料一覧からダウンロード" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();" />-->
	</div>
	
	<div>
		<?php if (( $this->_tpl_vars['product_list']['contents_contents1'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents2'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents3'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents4'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents5'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents6'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents7'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents8'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents9'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents10'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents11'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents12'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents13'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents14'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents15'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents16'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents17'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents18'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents19'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents20'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents21'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents22'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents23'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents24'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents25'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents1so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents2so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents3so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents4so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents5so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents6so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents7so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents8so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents9so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents10so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents11so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents12so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents13so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents14so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents15so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents16so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents17so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents18so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents19so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents20so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents21so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents22so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents23so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents24so'] != '' ) || ( $this->_tpl_vars['product_list']['contents_contents25so'] != '' )): ?>
			<div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>
			<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修パート一覧</div>
			<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>
			
			<?php unset($this->_sections['contents_contents']);
$this->_sections['contents_contents']['name'] = 'contents_contents';
$this->_sections['contents_contents']['loop'] = is_array($_loop=$this->_tpl_vars['section_max_contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_contents']['start'] = (int)1;
$this->_sections['contents_contents']['show'] = true;
$this->_sections['contents_contents']['max'] = $this->_sections['contents_contents']['loop'];
$this->_sections['contents_contents']['step'] = 1;
if ($this->_sections['contents_contents']['start'] < 0)
    $this->_sections['contents_contents']['start'] = max($this->_sections['contents_contents']['step'] > 0 ? 0 : -1, $this->_sections['contents_contents']['loop'] + $this->_sections['contents_contents']['start']);
else
    $this->_sections['contents_contents']['start'] = min($this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] : $this->_sections['contents_contents']['loop']-1);
if ($this->_sections['contents_contents']['show']) {
    $this->_sections['contents_contents']['total'] = min(ceil(($this->_sections['contents_contents']['step'] > 0 ? $this->_sections['contents_contents']['loop'] - $this->_sections['contents_contents']['start'] : $this->_sections['contents_contents']['start']+1)/abs($this->_sections['contents_contents']['step'])), $this->_sections['contents_contents']['max']);
    if ($this->_sections['contents_contents']['total'] == 0)
        $this->_sections['contents_contents']['show'] = false;
} else
    $this->_sections['contents_contents']['total'] = 0;
if ($this->_sections['contents_contents']['show']):

            for ($this->_sections['contents_contents']['index'] = $this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['iteration'] = 1;
                 $this->_sections['contents_contents']['iteration'] <= $this->_sections['contents_contents']['total'];
                 $this->_sections['contents_contents']['index'] += $this->_sections['contents_contents']['step'], $this->_sections['contents_contents']['iteration']++):
$this->_sections['contents_contents']['rownum'] = $this->_sections['contents_contents']['iteration'];
$this->_sections['contents_contents']['index_prev'] = $this->_sections['contents_contents']['index'] - $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['index_next'] = $this->_sections['contents_contents']['index'] + $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['first']      = ($this->_sections['contents_contents']['iteration'] == 1);
$this->_sections['contents_contents']['last']       = ($this->_sections['contents_contents']['iteration'] == $this->_sections['contents_contents']['total']);
?>
			<?php $this->assign('ccno', $this->_sections['contents_contents']['index']); ?>
			<?php $this->assign('contents_contents_key', ((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_contents_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>

			<?php $this->assign('contents_contents_so_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
			<?php $this->assign('contents_contents_so_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so_name') : smarty_modifier_cat($_tmp, 'so_name'))); ?>

			<?php $this->assign('contents_thumbnail_key', ((is_array($_tmp='contents_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_teacher_key', ((is_array($_tmp='contents_teacher')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_start_date_key', ((is_array($_tmp='contents_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_end_date_key', ((is_array($_tmp='contents_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_memo_key', ((is_array($_tmp='contents_memo')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_view_flg_key', ((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_baisoku_flg_key', ((is_array($_tmp='contents_baisoku_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('contents_view_flg_so_key', ((is_array($_tmp=((is_array($_tmp='contents_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 'so') : smarty_modifier_cat($_tmp, 'so'))); ?>
			<?php $this->assign('contents_free_time_key', ((is_array($_tmp='contents_free_time')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('video_thumbnail_key', ((is_array($_tmp='video_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('video_duration_key', ((is_array($_tmp='video_duration')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('video_reading_key', ((is_array($_tmp='video_reading')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('video_view_flg_key', ((is_array($_tmp='video_view_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			<?php $this->assign('video_complete_flg_key', ((is_array($_tmp='video_complete_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno']))); ?>
			
			<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] != '' || $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] != ''): ?>

				<center>
				<table style="background-color:#F5F8EF;width:680px;">
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
、<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_duration_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['video_reading_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>未視聴<?php endif; ?></td></tr>
				</table>
				</center>
				<div class="detail_btn" style="text-align: left;">
					<center>
						<?php if (! $this->_tpl_vars['is_sp']): ?>
							<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']] > 0): ?>
								<img src="/img/lecture/play_btn_off.png" alt="始めから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','')" style="cursor:pointer;" />
								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
									<img src="/img/lecture/resume_btn_off.png" alt="続きから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','')" style="cursor:pointer;" />
								<?php else: ?>
									<img src="/img/lecture/resume_btn_none.png" alt="続きから再生" style="cursor:pointer;" />
								<?php endif; ?>
							<?php endif; ?>
							<!--[<?php echo $this->_tpl_vars['contents_contents_so_key']; ?>
:<?php echo $this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']]; ?>
]-->
							<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']] > 0): ?>
								<img src="/img/lecture/play_btn_off_so.png" alt="始めから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','start','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" style="cursor:pointer;" />
								<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['video_view_flg_key']]): ?>
									<img src="/img/lecture/resume_btn_off_so.png" alt="続きから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','<?php echo ((is_array($_tmp=$this->_tpl_vars['ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','bookmark','<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_contents_so_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')" style="cursor:pointer;" />
								<?php else: ?>
									<img src="/img/lecture/resume_btn_none_so.png" alt="続きから再生" style="cursor:pointer;" />
								<?php endif; ?>
							<?php endif; ?>
						<?php else: ?>
							<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
						<?php endif; ?>
					</center>
				</div>
				<br />
			<?php endif; ?>
			<?php endfor; endif; ?>
			
			<div style="padding-top:5px;text-align:center;" id="exam3_btn_area">
				※受講状況（受講率等）の表示は，１日１回更新されます。
			</div>
			
			<div style="padding-top:5px;text-align:center;color:red;">
				『テストを受ける』『受講結果を確認する』ボタンが表示されない場合は，<br />更新ボタンを押してください。
			</div>
			<?php if (! $this->_tpl_vars['is_sp']): ?>
			<div style="padding-top:5px;text-align:center;color:red;">
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
			</div>
			<?php endif; ?>

<br />
		<?php endif; ?>
	</div>
	
	<?php if ($this->_tpl_vars['all_view_flg']): ?>
		<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>
		<center>
		<table style="background-color:#F5F8EF;width:300px;">
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:100px;">倫理研修テスト</th><td style="padding: 3px 10px;">
			<?php if (! $this->_tpl_vars['ethic_status']): ?>
				未受講
				<br /><a href="/ethic_treaning?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
			<?php elseif ($this->_tpl_vars['ethic_status'] === '1'): ?>
				受講中
				<br /><a href="/ethic_treaning?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
			<?php elseif ($this->_tpl_vars['ethic_status'] >= '2'): ?>
				受講済
			<?php endif; ?>
			</td></tr>
		</table>
		</center>
		<?php if ($this->_tpl_vars['ethic_status'] === '3' || $this->_tpl_vars['ethic_status'] === '4' || $this->_tpl_vars['ethic_status'] === '5' || $this->_tpl_vars['ethic_status'] === '6' || $this->_tpl_vars['ethic_status'] === '7'): ?>
		<center>
		<table style="background-color:#F5F8EF;width:300px;">
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:100px;">倫理研修追試</th><td style="padding: 3px 10px;">
				<?php if ($this->_tpl_vars['ethic_status'] === '3'): ?>
					未受講
					<br /><a href="/ethic_treaning/retry.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
				<?php elseif ($this->_tpl_vars['ethic_status'] === '4'): ?>
					受講中
					<br /><a href="/ethic_treaning/retry.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
				<?php elseif ($this->_tpl_vars['ethic_status'] === '5' || $this->_tpl_vars['ethic_status'] === '6' || $this->_tpl_vars['ethic_status'] === '7'): ?>
					受講済
				<?php endif; ?>
			</td></tr>
		</table>
		</center>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['ethic_status'] >= '2'): ?>
			<div style="text-align:center;width:100%;border-bottom:solid 1px #000000;margin:5px 0;">
				<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">受講結果</div>
				<br /><a href="/ethic_treaning/result_history.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/lecture/jyukoukekka_kakunin.png" alt="受講結果を確認する" /></a>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div style="text-align:center;width:100%;color:red;font-size:16px;">
			すべての動画を視聴した後、<br />テストを受けることができるようになります。
		</div>
<br />
	<?php endif; ?>
	
</div>
	
<?php elseif ($this->_tpl_vars['product_list']['product_type_add'] == 4): ?>
<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
	<div style="width:168px;float:left;text-align:center;">
		<?php if ($this->_tpl_vars['product_list']['thumbnail'] != ''): ?>
			<img src="/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=150&height=150" alt="" />
		<?php else: ?>
			<img src="/resize_image.php?image=noimage.jpg&width=150&height=150" alt="" />
		<?php endif; ?>
	</div>
	
	<div style="width:300px;float:right;">
		<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
	<table style="background-color:#F5F8EF;float:right;width:290px;">
		<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><?php if ($this->_tpl_vars['product_list']['price_intax'] == 0): ?>無料<?php else: ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円(税込)<?php endif; ?></td></tr>-->
		<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;text-align:left;">備考</th><td style="padding: 3px 10px;text-align:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_list']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td></tr>
	</table>
	</div>
	
	<div style="clear:both;text-align:center;padding:20px;">
		<?php if (! $this->_tpl_vars['passport_flg']): ?>
			<?php if ($this->_tpl_vars['buy_wait_flg']): ?>
				<img src="/img/lecture/buy_wait.png" alt="購入手続き中" /><br />
			<?php else: ?>
				<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
				<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<input type="hidden" name="hid_product_type_add" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['product_type_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="image" src="/img/button/buy_process_btn.jpg" alt="買い物かごに入れる" /><br />
				</form>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
	
<?php endif; ?>



<?php if ($this->_tpl_vars['buy_flg']): ?>
<?php else: ?>
	<div style="clear:both;background-color: #ffffff;" id="exam2_btn_area">
		<?php if ($this->_tpl_vars['product_list']['exam2_id'] > 0): ?>
			<?php if (count ( $this->_tpl_vars['exam2_answer'] ) > 0): ?>
				<!--[回答済]-->
				<?php if ($this->_tpl_vars['exam2']['resubmit_flag'] == 1): ?>
					<!--[再回答可]-->
					<a href="javascript:void(0);" ontouchmove="touchMoveFlag=true;" onclick="pop_get_html('/exam2/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&e2id=<?php echo $this->_tpl_vars['product_list']['exam2_id']; ?>
')" id="exam2_btn">アンケートに答える</a>
				<?php else: ?>
					<!--[再回答不可]-->
				<?php endif; ?>
			<?php else: ?>
				<!--[未回答]-->
				<a href="javascript:void(0);" ontouchmove="touchMoveFlag=true;" onclick="pop_get_html('/exam2/index.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&e2id=<?php echo $this->_tpl_vars['product_list']['exam2_id']; ?>
')" id="exam2_btn">アンケートに答える</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<script type="text/javascript">
		
	</script>
<?php endif; ?>
<script type="text/javascript">
	function pop_get_html(str_url){
		if ($('#exam2_pop_area').css('display') == 'block') {
		} else {
			$('#exam2_pop_area').show();
		}
		$('#exam2_pop_html_area').show();
		$('#exam2_pop_html_area_sub').hide();
		$.ajax({
			type: 'POST',
			url: str_url,
			dataType: 'html',
			success: function(data) {
				$('#exam2_pop_html_area').hide();
				$('#exam2_pop_html_area').html("");
				$('#exam2_pop_html_area').show();
				$('#exam2_pop_html_area').html(data);
			},
			error:function() {
				alert('通信エラーが発生しました。');
			}
		});
	}
	function pop_close(){
		$('#exam2_pop_area').hide();
	}
	function pop_close_reload(){
		//$('#exam2_pop_area').hide();
		location.reload();
	}

	function pop_get_html_sub(str_url,str_form_id){
		var $form = $("#"+ str_form_id);
		$('#exam2_pop_html_area').hide();
		$.ajax({
			type: 'POST',
			data: $form.serialize(),
			url: str_url,
			dataType: 'html',
			success: function(data) {
				$('#exam2_pop_html_area').hide();
				$('#exam2_pop_html_area_sub').html("");
				$('#exam2_pop_html_area_sub').show();
				$('#exam2_pop_html_area_sub').html(data);
			},
			error:function() {
				alert('通信エラーが発生しました。');
			}
		});
	}
	function pop_close_sub(){
		$('#exam2_pop_html_area').show();
		$('#exam2_pop_html_area_sub').hide();
		$('#exam2_pop_html_area_sub').html("");
	}
</script>
<style type="text/css">
	#exam2_btn_area{
		display:block;
		text-align: center;
		background-color: #ffffff;
	}
	#exam2_btn{
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #fff;
		background-color: #756B6B;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;

	}
	#exam2_pop_area{
		display: none;
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 999999;
		background-color: rgba(0, 0, 0, .65);
	}
	#exam2_pop_area_window{
		width: 900px;
		height: 90%;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		border-radius: 15px;
		background-color: #fff;
		overflow: hidden;
		padding: 15px;
	}
	#exam2_pop_html_area{
		/*
		padding:15px;
		overflow-y:auto;
		*/
		width: 100%;
		overflow-y: scroll;
		overflow-x: hidden;
		word-break: break-all;
		word-wrap: break-word;
		display: inline-block;
		height: 100%;
	}
	#exam2_pop_html_area_sub{
		width: 100%;
		overflow-y: scroll;
		overflow-x: hidden;
		word-break: break-all;
		word-wrap: break-word;
		display: none;
		height: 100%;
	}

	a.btn_gray:link,
	a.btn_gray:link,
	a.btn_gray:visited,
	a.btn_gray:hover,
	a.btn_gray:active {
		color: #ffffff;
	}
	.btn_gray {
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #fff;
		background-color: #756B6B;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;
	}

	.btn_graywhite {
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #756B6B;
		background-color: #FFFFFF;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;
		border: solid 1px #756B6B;
		cursor : pointer;
	}

</style>
<div id="exam2_pop_area">
	<div id="exam2_pop_area_window">
		<div id="exam2_pop_html_area">
		</div>
		<div id="exam2_pop_html_area_sub">
		</div>
	</div>
</div>





<a id="review_list" name="review_list"></a>
<div id="list" style="background-color: #ffffff;">

<?php if (! empty ( $this->_tpl_vars['arr_exam2'] )): ?>
	<br><br>
	<p><span style="border-bottom: solid 2px skyblue;"><strong><font size="3" color="skyblue">レビュー一覧</font></strong>　（レビュー　<?php echo count($this->_tpl_vars['arr_exam2']); ?>
件）</span></p>
	<p>本レビューは，みなさまからいただいたアンケートを基に掲載しております。<br>
	レビュー内容（修習期／弁護士経験年数）</p>
	<hr>
	<?php $_from = $this->_tpl_vars['arr_exam2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['exam2_i'] => $this->_tpl_vars['exam2_row']):
?>
		<div>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['exam2_row'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

		</div>
		<hr>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</div>



































<?php if ($this->_tpl_vars['product_list']['product_type_add'] == 1 || $this->_tpl_vars['product_list']['product_type_add'] == 2): ?>
	<div style="clear:both;margin:0;" class="detail_h2_3" id="RelatedCourseBlock">
		<h3>関連講座</h3>
	</div>
	<?php if (empty ( $this->_tpl_vars['arr_related_list'] )): ?>
		<div class="nonProductMsg">
		関連講座はありません。
		</div>
	<?php else: ?>
		<div style="clear:both;">
			<ul style="list-style:none;margin:0;">
			<?php $_from = $this->_tpl_vars['arr_related_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['related_products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['related_products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['related_products']):
        $this->_foreach['related_products']['iteration']++;
?>
				<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><br>
				<!--
				<li style="float:left;width:90px;padding:5px;text-align:center;<?php if (($this->_foreach['related_products']['iteration']-1) == 5): ?>clear:both;<?php endif; ?>">
				<?php if ($this->_tpl_vars['related_products']['thumbnail'] != ''): ?>
					<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=90&height=90" alt="" /></a><br />
				<?php else: ?>
					<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/resize_image.php?image=noimage.jpg&width=90&height=90" alt="" /></a><br />
				<?php endif; ?>
				<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['related_products']['name'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 14, "...") : smarty_modifier_mb_truncate($_tmp, 14, "...")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><br />
				<!--
				単品価格：<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['related_products']['price_intax'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円<br />
				受講期間：
					<?php if ($this->_tpl_vars['related_products']['start_date'] == '' && $this->_tpl_vars['related_products']['end_date'] == ''): ?>
						未定
					<?php else: ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['related_products']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<?php endif; ?>
				</li>
				-->
			<?php endforeach; endif; unset($_from); ?>
			</ul>
		</div>
	<?php endif; ?>
<?php endif; ?>

<form name="playerForm" action="#" method="post">
	<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
	<input type="hidden" name="vid" id="hid_vid" value="" />
	<input type="hidden" name="vid2" id="hid_vid2" value="" />
	<input type="hidden" name="codec" id="hid_codec" value="" />
	<input type="hidden" name="ftn" id="hid_ftn" value="" />
	<input type="hidden" name="ccno" id="hid_ccno" value="" />
	<input type="hidden" name="view_btn" id="hid_view_btn" value="" />
</form>

<form name="downloadForm" action="#" method="post">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
<input type="hidden" name="cdname" id="hid_cdname" value="" />
</form>

<style type="text/css">
.detail_btn {
	text-align: left;
	display: inline-block;
}
.btn_plyer{
	display:block;
	height:30px;
	cursor:pointer;	
	width: 166px;
	margin-bottom: 8px;
	float: left;
	margin-right: 8px;
}
.btn_plyer_text{
	display:block;
	margin-left:30px;
	line-height:30px;
	font-size:12px;
	color:#666666;
}
.btn_plyer:hover{
	opacity:0.6;
}
</style>