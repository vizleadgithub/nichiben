<?php /* Smarty version 2.6.31, created on 2025-03-11 15:24:19
         compiled from /srv/alfproduct/smarty/templates/admin/product_ethics/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_ethics/add.tpl', 33, false),array('modifier', 'count', '/srv/alfproduct/smarty/templates/admin/product_ethics/add.tpl', 132, false),array('modifier', 'cat', '/srv/alfproduct/smarty/templates/admin/product_ethics/add.tpl', 462, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/admin/product_ethics/add.tpl', 427, false),)), $this); ?>
<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
  document.getElementById("act").value = formAct;
  document.forms[formName].action = formAction;
  document.forms[formName].submit();
}
function searchButton(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1024,height=980");
}
function delete_live_training_product_id(){
	document.form1.live_training_product_id.value='';
	document.form1.live_training_product_name.value='';
	document.getElementById("spa_live_training_product_id").innerText = '';
	if (typeof document.getElementById("spa_live_training_product_id").textContent!= "undefined") {
		document.getElementById("spa_live_training_product_id").textContent = '';
	}
}
function contentsOpen(contentsNo){
  var content = document.getElementById("contentsTable" + contentsNo).style.display;
  if (content == "none"){
    document.getElementById("contentsTable" + contentsNo).style.display = "block";
  } else {
    document.getElementById("contentsTable" + contentsNo).style.display = "none";
  }
}
</script>

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
		<th style="vertical-align:middle;">商品名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品コード<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_code" id="product_code" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="price" id="price" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['price'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
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
		<th style="vertical-align:middle;">公開</th>
		<td>
			<label><input type="radio" name="publish_flg" id="publish_flg0" value="0" <?php if ($this->_tpl_vars['arr_input']['publish_flg'] == 0): ?>checked<?php endif; ?> />しない</label>
			<label><input type="radio" name="publish_flg" id="publish_flg1" value="1" <?php if ($this->_tpl_vars['arr_input']['publish_flg'] == 1): ?>checked<?php endif; ?> />する</label>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">公開期間</th>
		<td>
			<input type="text" name="start_date" id="start_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			～
			<input type="text" name="end_date" id="end_date" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">購入後公開期間日数</th>
		<td>
			<select name="open_period" id="open_period">
			<?php unset($this->_sections['open_period']);
$this->_sections['open_period']['name'] = 'open_period';
$this->_sections['open_period']['loop'] = is_array($_loop=$this->_tpl_vars['section_open_period']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['open_period']['start'] = (int)0;
$this->_sections['open_period']['show'] = true;
$this->_sections['open_period']['max'] = $this->_sections['open_period']['loop'];
$this->_sections['open_period']['step'] = 1;
if ($this->_sections['open_period']['start'] < 0)
    $this->_sections['open_period']['start'] = max($this->_sections['open_period']['step'] > 0 ? 0 : -1, $this->_sections['open_period']['loop'] + $this->_sections['open_period']['start']);
else
    $this->_sections['open_period']['start'] = min($this->_sections['open_period']['start'], $this->_sections['open_period']['step'] > 0 ? $this->_sections['open_period']['loop'] : $this->_sections['open_period']['loop']-1);
if ($this->_sections['open_period']['show']) {
    $this->_sections['open_period']['total'] = min(ceil(($this->_sections['open_period']['step'] > 0 ? $this->_sections['open_period']['loop'] - $this->_sections['open_period']['start'] : $this->_sections['open_period']['start']+1)/abs($this->_sections['open_period']['step'])), $this->_sections['open_period']['max']);
    if ($this->_sections['open_period']['total'] == 0)
        $this->_sections['open_period']['show'] = false;
} else
    $this->_sections['open_period']['total'] = 0;
if ($this->_sections['open_period']['show']):

            for ($this->_sections['open_period']['index'] = $this->_sections['open_period']['start'], $this->_sections['open_period']['iteration'] = 1;
                 $this->_sections['open_period']['iteration'] <= $this->_sections['open_period']['total'];
                 $this->_sections['open_period']['index'] += $this->_sections['open_period']['step'], $this->_sections['open_period']['iteration']++):
$this->_sections['open_period']['rownum'] = $this->_sections['open_period']['iteration'];
$this->_sections['open_period']['index_prev'] = $this->_sections['open_period']['index'] - $this->_sections['open_period']['step'];
$this->_sections['open_period']['index_next'] = $this->_sections['open_period']['index'] + $this->_sections['open_period']['step'];
$this->_sections['open_period']['first']      = ($this->_sections['open_period']['iteration'] == 1);
$this->_sections['open_period']['last']       = ($this->_sections['open_period']['iteration'] == $this->_sections['open_period']['total']);
?>
				<option value="<?php echo $this->_sections['open_period']['index']; ?>
" <?php if ($this->_tpl_vars['arr_input']['open_period'] == $this->_sections['open_period']['index']): ?>selected<?php endif; ?>><?php echo $this->_sections['open_period']['index']; ?>
</option>
			<?php endfor; endif; ?>
			</select>
			※0選択時は無制限に公開
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<?php if (isset ( $this->_tpl_vars['arr_input']['thumbnail'] ) && $this->_tpl_vars['arr_input']['thumbnail'] != ""): ?>
				<br />
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<textarea name="memo" id="memo" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['memo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品講師名</th>
		<td>
			<input type="text" name="teacher" id="teacher" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">年数<span style="color:red;">※</span></th>
		<td>
			<?php echo smarty_function_html_options(array('name' => 'bar_association_year','options' => $this->_tpl_vars['bar_association_year'],'selected' => $this->_tpl_vars['arr_input']['bar_association_year']), $this);?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修問題<span style="color:red;">※</span></th>
		<td>
			<?php echo smarty_function_html_options(array('name' => 'ethic_group_id','options' => $this->_tpl_vars['ethic_group'],'selected' => $this->_tpl_vars['arr_input']['ethic_group_id']), $this);?>

		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">一括ダウンロード用資料</th>
		<td>
			<input type="file" name="all_contents_download" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_all_contents.php');" />
			<?php if ($this->_tpl_vars['arr_input']['all_contents_download'] != ''): ?>
				<br />
				ファイル名：<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['all_contents_download_before'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<input type="hidden" name="hid_all_contents_download" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['all_contents_download'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<input type="hidden" name="all_contents_download_before" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['all_contents_download_before'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<a href="javascript:void(0);" onclick="document.form1.hid_all_contents_download.value='';formSubmit('form1', 'delete_all_contents.php');">削除</a>
			<?php endif; ?>
		</td>
	</tr>
	
<tr><td colspan="2">
<?php unset($this->_sections['contents_loop']);
$this->_sections['contents_loop']['name'] = 'contents_loop';
$this->_sections['contents_loop']['loop'] = is_array($_loop=$this->_tpl_vars['section_contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_loop']['start'] = (int)1;
$this->_sections['contents_loop']['show'] = true;
$this->_sections['contents_loop']['max'] = $this->_sections['contents_loop']['loop'];
$this->_sections['contents_loop']['step'] = 1;
if ($this->_sections['contents_loop']['start'] < 0)
    $this->_sections['contents_loop']['start'] = max($this->_sections['contents_loop']['step'] > 0 ? 0 : -1, $this->_sections['contents_loop']['loop'] + $this->_sections['contents_loop']['start']);
else
    $this->_sections['contents_loop']['start'] = min($this->_sections['contents_loop']['start'], $this->_sections['contents_loop']['step'] > 0 ? $this->_sections['contents_loop']['loop'] : $this->_sections['contents_loop']['loop']-1);
if ($this->_sections['contents_loop']['show']) {
    $this->_sections['contents_loop']['total'] = min(ceil(($this->_sections['contents_loop']['step'] > 0 ? $this->_sections['contents_loop']['loop'] - $this->_sections['contents_loop']['start'] : $this->_sections['contents_loop']['start']+1)/abs($this->_sections['contents_loop']['step'])), $this->_sections['contents_loop']['max']);
    if ($this->_sections['contents_loop']['total'] == 0)
        $this->_sections['contents_loop']['show'] = false;
} else
    $this->_sections['contents_loop']['total'] = 0;
if ($this->_sections['contents_loop']['show']):

            for ($this->_sections['contents_loop']['index'] = $this->_sections['contents_loop']['start'], $this->_sections['contents_loop']['iteration'] = 1;
                 $this->_sections['contents_loop']['iteration'] <= $this->_sections['contents_loop']['total'];
                 $this->_sections['contents_loop']['index'] += $this->_sections['contents_loop']['step'], $this->_sections['contents_loop']['iteration']++):
$this->_sections['contents_loop']['rownum'] = $this->_sections['contents_loop']['iteration'];
$this->_sections['contents_loop']['index_prev'] = $this->_sections['contents_loop']['index'] - $this->_sections['contents_loop']['step'];
$this->_sections['contents_loop']['index_next'] = $this->_sections['contents_loop']['index'] + $this->_sections['contents_loop']['step'];
$this->_sections['contents_loop']['first']      = ($this->_sections['contents_loop']['iteration'] == 1);
$this->_sections['contents_loop']['last']       = ($this->_sections['contents_loop']['iteration'] == $this->_sections['contents_loop']['total']);
?>
<div onClick="contentsOpen('<?php echo $this->_sections['contents_loop']['index']; ?>
')" style="cursor:pointer;background-color:#fde9d9 !important;height:25px;padding:10px 0 0 10px;font-size:14px;font-weight:bold;border-top:solid 1px #000000;border-bottom:solid 1px #000000;">
▼コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>

</div>
<table id="contentsTable<?php echo $this->_sections['contents_loop']['index']; ?>
" style="display:none;" />
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
サムネイル画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="contents_thumbnail<?php echo $this->_sections['contents_loop']['index']; ?>
" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<?php $this->assign('contents_thumbnail_key', ((is_array($_tmp='contents_thumbnail')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php if (isset ( $this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_thumbnail_key']] ) && $this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_thumbnail_key']] != ""): ?>
				<br />
				<img src="/alfproduct/resize_image.php?image=<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_thumbnail_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&width=240&height=180" alt="" />
				<input type="hidden" name="hid_contents_thumbnail<?php echo $this->_sections['contents_loop']['index']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_thumbnail_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				<a href="javascript:void(0);" onclick="document.form1.hid_contents_thumbnail<?php echo $this->_sections['contents_loop']['index']; ?>
.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
コンテンツ<?php if ($this->_sections['contents_loop']['index'] == '1'): ?><span style="color:red;">※</span><?php endif; ?></th>
		<td>
			<?php $this->assign('contents_contents_key', ((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php $this->assign('contents_contents_name_key', ((is_array($_tmp=((is_array($_tmp='contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_name') : smarty_modifier_cat($_tmp, '_name'))); ?>

			<!--<span id="spa_<?php echo $this->_tpl_vars['contents_contents_key']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>-->
			<?php if ($this->_tpl_vars['contents_contents_key'] != ""): ?><input type="text" name="<?php echo $this->_tpl_vars['contents_contents_name_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['contents_contents_name_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><?php endif; ?>
			<input type="hidden" name="<?php echo $this->_tpl_vars['contents_contents_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['contents_contents_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_contents_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<!--<input type="hidden" name="<?php echo $this->_tpl_vars['contents_contents_name_key']; ?>
" id="hid_<?php echo $this->_tpl_vars['contents_contents_name_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_contents_name_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />-->
			<input type="button" value="検索" onclick="searchButton('search_contents.php?gid=<?php echo $this->_tpl_vars['contents_contents_key']; ?>
')" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
無料公開範囲(秒)</th>
		<td>
			<?php $this->assign('contents_free_time_key', ((is_array($_tmp='contents_free_time')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<select name="<?php echo $this->_tpl_vars['contents_free_time_key']; ?>
" id="<?php echo $this->_tpl_vars['contents_free_time_key']; ?>
">
				<?php unset($this->_sections['contents_free_time']);
$this->_sections['contents_free_time']['name'] = 'contents_free_time';
$this->_sections['contents_free_time']['loop'] = is_array($_loop=$this->_tpl_vars['section_contents_free_time']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_free_time']['start'] = (int)0;
$this->_sections['contents_free_time']['show'] = true;
$this->_sections['contents_free_time']['max'] = $this->_sections['contents_free_time']['loop'];
$this->_sections['contents_free_time']['step'] = 1;
if ($this->_sections['contents_free_time']['start'] < 0)
    $this->_sections['contents_free_time']['start'] = max($this->_sections['contents_free_time']['step'] > 0 ? 0 : -1, $this->_sections['contents_free_time']['loop'] + $this->_sections['contents_free_time']['start']);
else
    $this->_sections['contents_free_time']['start'] = min($this->_sections['contents_free_time']['start'], $this->_sections['contents_free_time']['step'] > 0 ? $this->_sections['contents_free_time']['loop'] : $this->_sections['contents_free_time']['loop']-1);
if ($this->_sections['contents_free_time']['show']) {
    $this->_sections['contents_free_time']['total'] = min(ceil(($this->_sections['contents_free_time']['step'] > 0 ? $this->_sections['contents_free_time']['loop'] - $this->_sections['contents_free_time']['start'] : $this->_sections['contents_free_time']['start']+1)/abs($this->_sections['contents_free_time']['step'])), $this->_sections['contents_free_time']['max']);
    if ($this->_sections['contents_free_time']['total'] == 0)
        $this->_sections['contents_free_time']['show'] = false;
} else
    $this->_sections['contents_free_time']['total'] = 0;
if ($this->_sections['contents_free_time']['show']):

            for ($this->_sections['contents_free_time']['index'] = $this->_sections['contents_free_time']['start'], $this->_sections['contents_free_time']['iteration'] = 1;
                 $this->_sections['contents_free_time']['iteration'] <= $this->_sections['contents_free_time']['total'];
                 $this->_sections['contents_free_time']['index'] += $this->_sections['contents_free_time']['step'], $this->_sections['contents_free_time']['iteration']++):
$this->_sections['contents_free_time']['rownum'] = $this->_sections['contents_free_time']['iteration'];
$this->_sections['contents_free_time']['index_prev'] = $this->_sections['contents_free_time']['index'] - $this->_sections['contents_free_time']['step'];
$this->_sections['contents_free_time']['index_next'] = $this->_sections['contents_free_time']['index'] + $this->_sections['contents_free_time']['step'];
$this->_sections['contents_free_time']['first']      = ($this->_sections['contents_free_time']['iteration'] == 1);
$this->_sections['contents_free_time']['last']       = ($this->_sections['contents_free_time']['iteration'] == $this->_sections['contents_free_time']['total']);
?>
					<option value="<?php echo $this->_sections['contents_free_time']['index']; ?>
" <?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_free_time_key']] == $this->_sections['contents_free_time']['index']): ?>selected<?php endif; ?>><?php echo $this->_sections['contents_free_time']['index']; ?>
</option>
				<?php endfor; endif; ?>
			</select>
			※0選択時は無料部分なし
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
公開期間</th>
		<td>
			<?php $this->assign('contents_start_date_key', ((is_array($_tmp='contents_start_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php $this->assign('contents_end_date_key', ((is_array($_tmp='contents_end_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>

			<input type="text" name="<?php echo $this->_tpl_vars['contents_start_date_key']; ?>
" id="<?php echo $this->_tpl_vars['contents_start_date_key']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_start_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			～
			<input type="text" name="<?php echo $this->_tpl_vars['contents_end_date_key']; ?>
" id="<?php echo $this->_tpl_vars['contents_end_date_key']; ?>
" class="calendar" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_end_date_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<?php echo $this->_sections['contents_loop']['index']; ?>
説明</th>
		<td>
			<?php $this->assign('contents_memo_key', ((is_array($_tmp='contents_memo')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<?php $this->assign('contents_memo_key_id', ((is_array($_tmp=((is_array($_tmp='hid_contents_contents')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_memo') : smarty_modifier_cat($_tmp, '_memo'))); ?>
			<textarea name="<?php echo $this->_tpl_vars['contents_memo_key']; ?>
" id="<?php echo $this->_tpl_vars['contents_memo_key_id']; ?>
" style="width:520px;height:150px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_memo_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師名<?php echo $this->_sections['contents_loop']['index']; ?>
</th>
		<td>
			<?php $this->assign('contents_teacher_key', ((is_array($_tmp='contents_teacher')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index']))); ?>
			<input type="text" name="<?php echo $this->_tpl_vars['contents_teacher_key']; ?>
" id="<?php echo $this->_tpl_vars['contents_teacher_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_teacher_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="#page_bottom">ページの下へ</a>
		</td>
	</tr>
	<?php unset($this->_sections['contents_download']);
$this->_sections['contents_download']['name'] = 'contents_download';
$this->_sections['contents_download']['loop'] = is_array($_loop=$this->_tpl_vars['section_contents_download']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<tr>
			<th style="vertical-align:middle;">ダウンロード<?php echo $this->_sections['contents_loop']['index']; ?>
-<?php echo $this->_sections['contents_download']['index']; ?>
</th>
			<td>
				<?php $this->assign('contents_download_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_download']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_download']['index']))); ?>
				<?php $this->assign('contents_download_before_key', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='contents_download_before')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_loop']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_loop']['index'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['contents_download']['index']) : smarty_modifier_cat($_tmp, $this->_sections['contents_download']['index']))); ?>
				<input type="file" name="<?php echo $this->_tpl_vars['contents_download_key']; ?>
" size="50" />
				<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_document.php');" />
				<?php if ($this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_key']] != ''): ?>
					<br />
					ファイル名：<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_before_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<input type="hidden" name="hid_<?php echo $this->_tpl_vars['contents_download_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<input type="hidden" name="<?php echo $this->_tpl_vars['contents_download_before_key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input'][$this->_tpl_vars['contents_download_before_key']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
					<a href="javascript:void(0);" onclick="document.form1.hid_<?php echo $this->_tpl_vars['contents_download_key']; ?>
.value='';formSubmit('form1', 'delete_document.php');">削除</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endfor; endif; ?>
</table>
<?php endfor; endif; ?>
</td></tr>

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
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>