<?php /* Smarty version 2.6.31, created on 2025-03-06 14:49:52
         compiled from /srv/alfproduct/smarty/templates/admin/product_live/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_live/add.tpl', 58, false),array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_live/add.tpl', 60, false),array('modifier', 'count', '/srv/alfproduct/smarty/templates/admin/product_live/add.tpl', 333, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/admin/product_live/add.tpl', 105, false),array('function', 'html_radios', '/srv/alfproduct/smarty/templates/admin/product_live/add.tpl', 194, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, formAction, formAct, select1, target1, select2, target2){
  $('#' + select1).children().attr({selected: true});
  $('#' + target1).children().attr({selected: true});
  $('#' + select2).children().attr({selected: true});
  $('#' + target2).children().attr({selected: true});
  document.getElementById("act").value = formAct;
  document.forms[formName].action = formAction;
  document.forms[formName].submit();
}
function searchButton(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1024,height=980");
}
function fnMoveSelect(select, target) {
  $('#' + select).children().each(function() {
    if (this.selected) {
      $('#' + target).append(this);
        $(this).attr({selected: false});
      }
  });
  // IE7再描画不具合対策
  if ($.browser.msie && $.browser.version >= 7) {
      $('#' + select).hide();
      $('#' + select).show();
      $('#' + target).hide();
      $('#' + target).show();
  }
}
function allMoveTarget(){
  if (document.getElementById("all_bar_association_target").checked){
    $("#bar_association_target_unselect option").each(function() {
        $(this).attr("selected", "selected");
    });
    fnMoveSelect("bar_association_target_unselect", "bar_association_target");
  } else {
    //$("#bar_association_target option").each(function() {
    //    $(this).attr("selected", "selected");
    //});
    //fnMoveSelect("bar_association_target", "bar_association_target_unselect");
  }
}
function resetTarget(){
  document.getElementById('all_bar_association_target').checked = false;
  $("#bar_association_target option").each(function() {
      $(this).attr("selected", "selected");
  });
  fnMoveSelect("bar_association_target", "bar_association_target_unselect");
}

<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
function datesChange(){
	var parent_dates_val = document.getElementById("dates").value;
	var dates_name = "";
	var web_flg_name = "";
	<?php $_from = $this->_tpl_vars['arr_bar_association']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
		<?php $_from = $this->_tpl_vars['val']['branch_info']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['branch']):
?>
			<?php $this->assign('branch_id', $this->_tpl_vars['branch']['id']); ?>
			<?php $this->assign('dates', ((is_array($_tmp='dates')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			<?php $this->assign('web_flg', ((is_array($_tmp='web_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
			dates_name = "<?php echo ((is_array($_tmp=$this->_tpl_vars['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
";
			document.getElementById(dates_name).value = parent_dates_val;
			web_flg_name = "<?php echo ((is_array($_tmp=$this->_tpl_vars['web_flg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
";
			document.getElementById(web_flg_name + "_2").checked = true;
		<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
}
<?php endif; ?>
</script>
<style type="text/css">
#bar_association_main_title{
  background-color:#fde9d9 !important;
  border-top:solid 1px #000000;
  font-weight:bold;
}
.bar_association_title{
  background-color:#dbe5f1 !important;
  border-top:solid 1px #000000;
  border-bottom:solid 1px #000000;
}
</style>
<h2>商品の内容を入力してください</h2>

<?php if (! empty ( $this->_tpl_vars['err_msg'] )): ?>
<div class="error">
<?php $_from = $this->_tpl_vars['err_msg']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
	<?php echo ((is_array($_tmp=$this->_tpl_vars['msg'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>
<form name="form1" action="add.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" id="act" value="confirm" />
<table class="form">
	<?php if (isset ( $this->_tpl_vars['arr_input']['mid'] )): ?>
	<tr>
		<th style="vertical-align:middle;">商品ID</th>
		<td>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['mid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<input type="hidden" name="mid" id="mid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['mid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<th style="vertical-align:middle;width:200px;">研修種別<span style="color:red;">※</span></th>
		<td colspan = "3">
			<?php echo smarty_function_html_options(array('name' => 'training_kind_flg','options' => $this->_tpl_vars['mtb_live_training_type'],'selected' => $this->_tpl_vars['arr_input']['training_kind_flg']), $this);?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修</th>
		<td colapan="3">
			<label><input type="radio" name="ethic_flg" value="0"<?php if ($this->_tpl_vars['arr_input']['ethic_flg'] == 0): ?>checked<?php endif; ?>>倫理研修対象としない</label>
			<label><input type="radio" name="ethic_flg" value="1"<?php if ($this->_tpl_vars['arr_input']['ethic_flg'] == 1): ?>checked<?php endif; ?>>倫理研修対象とする</label>
		</td>
	</tr>
	<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
	<tr>
		<th style="vertical-align:middle;">Web申込</th>
		<td colapan="3">
			<label><input type="radio" name="web_flg" value="1"<?php if ($this->_tpl_vars['arr_input']['web_flg'] == 1): ?>checked<?php endif; ?>>受け付ける</label>
			<label><input type="radio" name="web_flg" value="0"<?php if ($this->_tpl_vars['arr_input']['web_flg'] == 0): ?>checked<?php endif; ?>>受け付けない(情報の表示のみ)</label>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<th style="vertical-align:middle;">受付期間<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="start_date" id="start_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			～
			<input type="text" name="end_date" id="end_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講料振り込み期限<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="limit_date" id="limit_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['limit_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">開催日<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="dates" id="dates" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['dates'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['nichibenren_flg']): ?>onChange="datesChange()"<?php endif; ?> />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講票ダウンロード</th>
		<td colapan="3">
			<label><input type="radio" name="download_flg" value="1"<?php if ($this->_tpl_vars['arr_input']['download_flg'] == 1): ?>checked<?php endif; ?>>可</label>
			<label><input type="radio" name="download_flg" value="0"<?php if ($this->_tpl_vars['arr_input']['download_flg'] == 0): ?>checked<?php endif; ?>>不可</label>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">主催<br />※自弁護士会は自動的に主催となります。</th>
		<td colapan="3">
			<table>
				<tr>
					<td style="text-align:center;">主催</td>
					<td style="text-align:center;">&nbsp;</td>
					<td style="text-align:center;">候補</td>
				</tr>
				<tr>
					<td>
						<select name="bar_association_sponsor[]" id="bar_association_sponsor" style="width:250px;height:350px;" multiple>
						<?php $_from = $this->_tpl_vars['arr_input']['bar_association_sponsor']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
						<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</td>
					<td style="padding:15px;text-align:center;">
						<a href="javascript:;" name="on_select" onclick="fnMoveSelect('bar_association_sponsor_unselect','bar_association_sponsor');return false;" style="text-decoration:none;">←</a><br /><br />
						<a href="javascript:;" name="un_select" onclick="fnMoveSelect('bar_association_sponsor','bar_association_sponsor_unselect');return false;" style="text-decoration:none;">→</a>
					</td>
					<td>
						<select name="bar_association_sponsor_unselect[]" id="bar_association_sponsor_unselect" style="width:250px;height:350px;" multiple>
						<?php $_from = $this->_tpl_vars['arr_input']['bar_association_sponsor_unselect']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
						<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象者</th>
		<td colapan="3">
			<?php echo smarty_function_html_radios(array('name' => 'target_flg','options' => $this->_tpl_vars['mtb_live_target_flg'],'selected' => $this->_tpl_vars['arr_input']['target_flg']), $this);?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象<br />※自弁護士会は自動的に受講対象となります。</th>
		<td colapan="3">
			<table>
				<tr>
					<td><label><input type="checkbox" id="all_bar_association_target" name="all_bar_association_target" value="1" <?php if ($this->_tpl_vars['arr_input']['all_bar_association_target'] == 1): ?>checked<?php endif; ?> onClick="allMoveTarget()" />すべての弁護士会を対象とする</label></td>
					<td><input type="button" value="　リセットする　" onClick="resetTarget()" /></td>
				</tr>
				<tr>
					<td style="text-align:center;">対象とする</td>
					<td style="text-align:center;">&nbsp;</td>
					<td style="text-align:center;">対象としない</td>
				</tr>
				<tr>
					<td>
						<select name="bar_association_target[]" id="bar_association_target" style="width:250px;height:350px;" multiple>
						<?php $_from = $this->_tpl_vars['arr_input']['bar_association_target']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
						<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</td>
					<td style="padding:15px;text-align:center;">
						<a href="javascript:;" name="on_select" onclick="fnMoveSelect('bar_association_target_unselect','bar_association_target');return false;" style="text-decoration:none;">←</a><br /><br />
						<a href="javascript:;" name="un_select" onclick="fnMoveSelect('bar_association_target','bar_association_target_unselect');document.getElementById('all_bar_association_target').checked=false;return false;" style="text-decoration:none;">→</a>
					</td>
					<td>
						<select name="bar_association_target_unselect[]" id="bar_association_target_unselect" style="width:250px;height:350px;" multiple>
						<?php $_from = $this->_tpl_vars['arr_input']['bar_association_target_unselect']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bar_association_id'] => $this->_tpl_vars['bar_association_name']):
?>
						<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td>
			<input type="text" name="product_code" id="product_code" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<th style="vertical-align:middle;">研修の内容</th>
		<td>
			<textarea name="memo1" id="memo1" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講義タイトル、講師名</th>
		<td>
			<textarea name="memo2" id="memo2" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">日時詳細</th>
		<td>
			<textarea name="memo3" id="memo3" style="width:520px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo3'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">会場について</th>
		<td>
			<input type="text" name="hall" id="hall" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['hall'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">定員<br /><span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="capacity" id="capacity" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['capacity'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength="4" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">単品料金(税込)<br /><span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="price" id="price" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問い合わせ先</th>
		<td>
			<textarea name="memo4" id="memo4" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo4'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講資格/他会員の受講等</th>
		<td>
			<textarea name="memo5" id="memo5" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo5'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">備考</th>
		<td>
			<textarea name="contents" id="contents" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
<script type="text/javascript">
    /* ブラウザ判別 */
    var ie=document.all ? 1 : 0;
    var ns6=document.getElementById&&!document.all ? 1 : 0;
    var opera=window.opera ? 1 : 0;

    /* 子メニューの表示・非表示切替 */
    function openFolder(childObj, parentObj){
        var child="";
        var parent="";
        var sw="/alfproduct/images/show.gif"; /* フォルダ表示時のアイコン画像 */
        var hd="/alfproduct/images/hide.gif"; /* フォルダ非表示時のアイコン画像 */
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
	<tr>
		<th style="vertical-align:middle;">商品カテゴリ<span style="color:red;">※</span></th>
		<td>
			<ul style="list-style-type:none;">
			<?php $_from = $this->_tpl_vars['arr_category']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<li>
					<input type="checkbox" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row']['term_id'] , $this->_tpl_vars['arr_input']['arr_term_id'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
					<?php if (count($this->_tpl_vars['row']['categorys']) > 0): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
						→<input type="checkbox" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row2']['term_id'] , $this->_tpl_vars['arr_input']['arr_term_id'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
						<?php if (count($this->_tpl_vars['row2']['categorys']) > 0): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
							→→<input type="checkbox" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row3']['term_id'] , $this->_tpl_vars['arr_input']['arr_term_id'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
							<?php if (count($this->_tpl_vars['row3']['categorys']) > 0): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
								→→→<input type="checkbox" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row4']['term_id'] , $this->_tpl_vars['arr_input']['arr_term_id'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>&nbsp;&nbsp;
								<?php if (count($this->_tpl_vars['row4']['categorys']) > 0): ?><img id="close_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
									→→→→<input type="checkbox" name="arr_term_id[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row5']['term_id'] , $this->_tpl_vars['arr_input']['arr_term_id'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row5']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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


			<script type="text/javascript">
				var arr_cat_id = [];
				var arr_cat_name = [];
				var arr_par_id = [];
				<?php $_from = $this->_tpl_vars['arr_cat_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
					arr_cat_id[arr_cat_id.length] = "<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
";
					arr_cat_name[arr_cat_name.length] = "<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
";
					arr_par_id[arr_par_id.length] = "<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['parent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
			</script>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php', '', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');" />
			<?php if (isset ( $this->_tpl_vars['arr_input']['thumbnail'] ) && $this->_tpl_vars['arr_input']['thumbnail'] != ""): ?>
				<br />
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail.value='';formSubmit('form1', 'delete_thumbnail.php', '', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');">削除</a>
			<?php endif; ?>
		</td>
	</tr>
	
	<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
	<tr>
		<th colspan="4" id="bar_association_main_title">実施弁護士会</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_bar_association']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
		<tr>
			<td colspan="4" class="bar_association_title"><?php echo $this->_tpl_vars['val']['name']; ?>
</td>
		</tr>
		<?php $_from = $this->_tpl_vars['val']['branch_info']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['branch']):
?>
		<?php $this->assign('branch_id', $this->_tpl_vars['branch']['id']); ?>
		<?php $this->assign('capacity', ((is_array($_tmp='capacity')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('hall', ((is_array($_tmp='hall')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('receptionist_start_date', ((is_array($_tmp='receptionist_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('receptionist_end_date', ((is_array($_tmp='receptionist_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('dates', ((is_array($_tmp='dates')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('web_flg', ((is_array($_tmp='web_flg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('contents', ((is_array($_tmp='contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<?php $this->assign('entry_number', ((is_array($_tmp='entry_number')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['branch_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['branch_id']))); ?>
		<tr>
			<td>
				<?php echo $this->_tpl_vars['branch']['name']; ?>

			</td>
			<td>
				定員<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['capacity']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['capacity']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength="4" /><br />
				会場<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['hall']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['hall']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
				受付<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['receptionist_start_date']; ?>
" id="<?php echo $this->_tpl_vars['receptionist_start_date']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['receptionist_start_date']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px;" />～<input type="text" name="<?php echo $this->_tpl_vars['receptionist_end_date']; ?>
" id="<?php echo $this->_tpl_vars['receptionist_end_date']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['receptionist_end_date']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px;" /><br />
				実施日<span style="color:red;">※</span>：<input type="text" name="<?php echo $this->_tpl_vars['dates']; ?>
" id="<?php echo $this->_tpl_vars['dates']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['dates']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
				<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
				Web申込<span style="color:red;">※</span>：<label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="1" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '1'): ?>checked<?php endif; ?>  id="<?php echo $this->_tpl_vars['web_flg']; ?>
_1" />WEB申込可(研修を実施する)</label>　<label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="0" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '0'): ?>checked<?php endif; ?>  id="<?php echo $this->_tpl_vars['web_flg']; ?>
_0" />WEB申込不可(研修を実施する)</label>　<label><input type="radio" name="<?php echo $this->_tpl_vars['web_flg']; ?>
" value="2" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['web_flg']] === '2'): ?>checked<?php endif; ?> id="<?php echo $this->_tpl_vars['web_flg']; ?>
_2" />研修を実施しない</label><br />
				<?php endif; ?>
				<?php if (isset ( $this->_tpl_vars['arr_input']['mid'] )): ?>
				現状申込数：<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['entry_number']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
				<?php endif; ?>
				備考：<textarea name="<?php echo $this->_tpl_vars['contents']; ?>
" style="width:400px;height:100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
			</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
	
	<?php unset($this->_sections['related_products']);
$this->_sections['related_products']['name'] = 'related_products';
$this->_sections['related_products']['loop'] = is_array($_loop=$this->_tpl_vars['section_related_products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['related_products']['start'] = (int)1;
$this->_sections['related_products']['show'] = true;
$this->_sections['related_products']['max'] = $this->_sections['related_products']['loop'];
$this->_sections['related_products']['step'] = 1;
if ($this->_sections['related_products']['start'] < 0)
    $this->_sections['related_products']['start'] = max($this->_sections['related_products']['step'] > 0 ? 0 : -1, $this->_sections['related_products']['loop'] + $this->_sections['related_products']['start']);
else
    $this->_sections['related_products']['start'] = min($this->_sections['related_products']['start'], $this->_sections['related_products']['step'] > 0 ? $this->_sections['related_products']['loop'] : $this->_sections['related_products']['loop']-1);
if ($this->_sections['related_products']['show']) {
    $this->_sections['related_products']['total'] = min(ceil(($this->_sections['related_products']['step'] > 0 ? $this->_sections['related_products']['loop'] - $this->_sections['related_products']['start'] : $this->_sections['related_products']['start']+1)/abs($this->_sections['related_products']['step'])), $this->_sections['related_products']['max']);
    if ($this->_sections['related_products']['total'] == 0)
        $this->_sections['related_products']['show'] = false;
} else
    $this->_sections['related_products']['total'] = 0;
if ($this->_sections['related_products']['show']):

            for ($this->_sections['related_products']['index'] = $this->_sections['related_products']['start'], $this->_sections['related_products']['iteration'] = 1;
                 $this->_sections['related_products']['iteration'] <= $this->_sections['related_products']['total'];
                 $this->_sections['related_products']['index'] += $this->_sections['related_products']['step'], $this->_sections['related_products']['iteration']++):
$this->_sections['related_products']['rownum'] = $this->_sections['related_products']['iteration'];
$this->_sections['related_products']['index_prev'] = $this->_sections['related_products']['index'] - $this->_sections['related_products']['step'];
$this->_sections['related_products']['index_next'] = $this->_sections['related_products']['index'] + $this->_sections['related_products']['step'];
$this->_sections['related_products']['first']      = ($this->_sections['related_products']['iteration'] == 1);
$this->_sections['related_products']['last']       = ($this->_sections['related_products']['iteration'] == $this->_sections['related_products']['total']);
?>
	<tr>
		<th style="vertical-align:middle;">関連商品<?php echo $this->_sections['related_products']['index']; ?>
</th>
		<td>
			<?php $this->assign('related_products_key', ((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index']))); ?>
			<?php $this->assign('related_products_name_key', ((is_array($_tmp=((is_array($_tmp='related_products')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['related_products']['index']) : smarty_modifier_cat($_tmp, $this->_sections['related_products']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>
			<span id="spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
			<input type="hidden" name="<?php echo $this->_tpl_vars['related_products_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['related_products_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="hidden" name="<?php echo $this->_tpl_vars['related_products_name_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['related_products_name_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['related_products_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="button" value="検索" onclick="searchButton('search_product.php?gid=<?php echo $this->_tpl_vars['related_products_key']; ?>
')" />
			<!--<div id="delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
_link">--><a href="javascript:void(0);" onclick="delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
()">削除</a><!--<div>-->
			<script type="text/javascript">
			function delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
(){
				document.form1.<?php echo $this->_tpl_vars['related_products_key']; ?>
.value='';
				document.form1.<?php echo $this->_tpl_vars['related_products_name_key']; ?>
.value='';
				document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").innerText = '';
				if (typeof document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").textContent!= "undefined") {
					document.getElementById("spa_<?php echo $this->_tpl_vars['related_products_key']; ?>
").textContent = '';
				}
				//document.getElementById("delete_<?php echo $this->_tpl_vars['related_products_key']; ?>
_link").style.display="none";
			}
			</script>

		</td>
	</tr>
	<?php endfor; endif; ?>
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>