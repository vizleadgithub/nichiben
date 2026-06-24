<?php /* Smarty version 2.6.31, created on 2025-10-30 17:35:21
         compiled from /srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl', 8, false),array('modifier', 'count', '/srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl', 51, false),array('modifier', 'mb_truncate', '/srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl', 224, false),array('function', 'html_options', '/srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl', 196, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_live_branch/index.tpl', 221, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>研修名</th>
			<td colspan = "3">
				<input type="text" name="search_product_name" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td colspan = "3">
				<input type="text" name="search_product_code" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
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
<!-- style="background: none repeat scroll 0% 0% rgb(246, 246, 243);"-->
			<th>カテゴリ</td>
			<td colapan="3">
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
							→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row2']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row2']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
								→→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row3']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row3']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
									→→→<input type="checkbox" name="search_category[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="check_cat('<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');"<?php if (in_array ( $this->_tpl_vars['row4']['term_id'] , $this->_tpl_vars['search_category'] )): ?> checked="checked"<?php endif; ?> ><label for="arr_term_id<?php echo ((is_array($_tmp=$this->_tpl_vars['row4']['term_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
			</td>
		</tr>
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

		<tr>
			<th>研修実施日</th>
			<td colapan="3">
				<input type="text" name="search_dates_start" id="start_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_dates_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_dates_end" id="end_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_dates_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>公開状態</th>
			<td colapan="3">
				<input type="radio" name="search_open" value="0"<?php if ($this->_tpl_vars['search_open'] == '0' || $this->_tpl_vars['search_open'] == ""): ?> checked=checked<?php endif; ?>>指定無し&nbsp;&nbsp;
				<input type="radio" name="search_open" value="1"<?php if ($this->_tpl_vars['search_open'] == '1'): ?> checked=checked<?php endif; ?>>公開前&nbsp;&nbsp;
				<input type="radio" name="search_open" value="2"<?php if ($this->_tpl_vars['search_open'] == '2'): ?> checked=checked<?php endif; ?>>公開中&nbsp;&nbsp;
				<input type="radio" name="search_open" value="3"<?php if ($this->_tpl_vars['search_open'] == '3'): ?> checked=checked<?php endif; ?>>終了&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<th>講師名</th>
			<td colspan = "3">
				<input type="text" name="search_teacher" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_teacher'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>有料・無料</th>
			<td colspan = "3">
				<input type="radio" name="search_free" value="0"<?php if ($this->_tpl_vars['search_free'] == '0' || $this->_tpl_vars['search_free'] == ""): ?> checked=checked<?php endif; ?>>指定無し&nbsp;&nbsp;
				<input type="radio" name="search_free" value="1"<?php if ($this->_tpl_vars['search_free'] == '1'): ?> checked=checked<?php endif; ?>>有料&nbsp;&nbsp;
				<input type="radio" name="search_free" value="2"<?php if ($this->_tpl_vars['search_free'] == '2'): ?> checked=checked<?php endif; ?>>無料&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td colspan = "3">
				<input type="text" name="search_word" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<?php if ($this->_tpl_vars['nichibenren_flg']): ?>
		<tr>
			<th>主催弁護士会</th>
			<td colspan = "3">
				<?php echo smarty_function_html_options(array('name' => 'search_bar_association','options' => $this->_tpl_vars['mtb_bar_association'],'selected' => $this->_tpl_vars['search_bar_association']), $this);?>

			</td>
		</tr>
		<?php endif; ?>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>

<?php if ($this->_tpl_vars['disp_flg']): ?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>研修名</th>
		<th>弁護士会</th>
		<th>実施日</th>
		<th style="width:300px;">受付期間</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="info.php?mid=<?php echo $this->_tpl_vars['row']['product_id']; ?>
"><?php echo $this->_tpl_vars['row']['product_id']; ?>
</a></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 20, "...") : smarty_modifier_mb_truncate($_tmp, 20, "...")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['mtb_bar_association'][$this->_tpl_vars['row']['bar_association_id']]; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['dates']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['start_date']; ?>
<?php if ($this->_tpl_vars['row']['start_date'] != "" && $this->_tpl_vars['row']['end_date'] != ""): ?>～<?php endif; ?><?php echo $this->_tpl_vars['row']['end_date']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="5">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
<?php endif; ?>