<?php /* Smarty version 2.6.27, created on 2024-02-27 12:04:15
         compiled from /srv/alfproduct/smarty/templates/smartphone/product/document.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/product/document.tpl', 6, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/smartphone/product/document.tpl', 19, false),)), $this); ?>
<div>
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">資料一括ダウンロード</div>
	</div>
<?php if ($this->_tpl_vars['product_list']['all_contents_download'] != ''): ?>
	<span style="padding-left:30px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list']['all_contents_download_before'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
	<img src="/img/btn/btn_document_download.png" alt="ダウンロード" align="absmiddle" onclick="downloadAllFormSubmit()" style="cursor:pointer;padding-left:10px;" />
<?php endif; ?>
</div>

<div>
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">個別ダウンロード</div>
	</div>
	<div class="detail_btn">
		<ul>
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
				<?php unset($this->_sections['contents_download']);
$this->_sections['contents_download']['name'] = 'contents_download';
$this->_sections['contents_download']['loop'] = is_array($_loop=$this->_tpl_vars['section_max_contents_download']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_download']['start'] = (int)1;
$this->_sections['contents_download']['show'] = true;
$this->_sections['contents_download']['max'] = $this->_sections['contents_download']['loop'];
$this->_sections['contents_download']['step'] = 1;
if ($this->_sections['contents_download']['start'] < 0)
    $this->_sections['contents_download']['start'] = max($this->_sections['contents_download']['step'] > 0 ? 0 : -1, $this->_sections['contents_download']['loop'] + $this->_sections['contents_download']['start']);
else
    $this->_sections['contents_download']['start'] = min($this->_sections['contents_download']['start'], $this->_sections['contents_download']['step'] > 0 ? $this->_sections['contents_download']['loop'] : $this->_sections['contents_download']['loop']-1);
if ($this->_sections['contents_download']['show']) {
    $this->_sections['contents_download']['total'] = min(ceil(($this->_sections['contents_download']['step'] > 0 ? $this->_sections['contents_download']['loop'] - $this->_sections['contents_download']['start'] : $this->_sections['contents_download']['start']+1)/abs($this->_sections['contents_download']['step'])), $this->_sections['contents_download']['max']);
    if ($this->_sections['contents_download']['total'] == 0)
        $this->_sections['contents_download']['show'] = false;
} else
    $this->_sections['contents_download']['total'] = 0;
if ($this->_sections['contents_download']['show']):

            for ($this->_sections['contents_download']['index'] = $this->_sections['contents_download']['start'], $this->_sections['contents_download']['iteration'] = 1;
                 $this->_sections['contents_download']['iteration'] <= $this->_sections['contents_download']['total'];
                 $this->_sections['contents_download']['index'] += $this->_sections['contents_download']['step'], $this->_sections['contents_download']['iteration']++):
$this->_sections['contents_download']['rownum'] = $this->_sections['contents_download']['iteration'];
$this->_sections['contents_download']['index_prev'] = $this->_sections['contents_download']['index'] - $this->_sections['contents_download']['step'];
$this->_sections['contents_download']['index_next'] = $this->_sections['contents_download']['index'] + $this->_sections['contents_download']['step'];
$this->_sections['contents_download']['first']      = ($this->_sections['contents_download']['iteration'] == 1);
$this->_sections['contents_download']['last']       = ($this->_sections['contents_download']['iteration'] == $this->_sections['contents_download']['total']);
?>
				<?php $this->assign('cdno', $this->_sections['contents_download']['index']); ?>
				<?php $this->assign('contents_download_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['cdno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['cdno']))); ?>
				<?php $this->assign('contents_download_before_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download_before')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['ccno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['ccno'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['cdno']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['cdno']))); ?>
					<?php if ($this->_tpl_vars['product_list'][$this->_tpl_vars['contents_download_key']] != ''): ?>
						<li><?php echo ((is_array($_tmp=$this->_tpl_vars['product_list'][$this->_tpl_vars['contents_download_before_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<img src="/img/btn/btn_document_download.png" alt="資料<?php echo $this->_tpl_vars['cdno']; ?>
ダウンロード" align="absmiddle" onclick="downloadFormSubmit('<?php echo $this->_tpl_vars['contents_download_key']; ?>
','<?php echo $this->_tpl_vars['contents_download_before_key']; ?>
')" style="cursor:pointer;padding-left:10px;" /></li>
					<?php endif; ?>
				<?php endfor; endif; ?>
		<?php endfor; endif; ?>
		</ul>
	</div>
</div>

<div style="text-align:center;padding-top:20px;">
		<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">戻る</a>
</div>

<style type="text/css">
div{
width:98% !important;
}
</style>

<form name="downloadForm" action="#" method="post">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
<input type="hidden" name="cdname" id="hid_cdname" value="" />
<input type="hidden" name="cdname2" id="hid_cdname2" value="" />
</form>
<form name="downloadAllForm" action="#" method="post">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
</form>

<script type="text/javascript">
function downloadFormSubmit(cdname, cdname2){
    document.getElementById("hid_cdname").value = cdname;
    document.getElementById("hid_cdname2").value = cdname2;
    document.downloadForm.method = "post";
    document.downloadForm.action = "download.php?PHPSESSID=<?php echo session_id(); ?>";
    document.downloadForm.submit();
}
function downloadAllFormSubmit(cdname, cdname2){
    document.downloadAllForm.method = "post";
    document.downloadAllForm.action = "download_all.php?PHPSESSID=<?php echo session_id(); ?>";
    document.downloadAllForm.submit();
}
</script>