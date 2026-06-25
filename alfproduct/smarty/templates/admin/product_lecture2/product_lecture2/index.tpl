<!--
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>
-->

<h2>閲覧する講座を検索して選んでください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>研修名</th>
			<td colspan = "3">
				<input type="text" name="search_product_name" size="45" value="<!--{$search_product_name|escape}-->">
			</td>
		</tr>
		<tr>
			<th>研修コード</th>
			<td colspan = "3">
				<input type="text" name="search_product_code" size="45" value="<!--{$search_product_code|escape}-->">
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
				<!--{foreach from=$arr_category item="row"}-->
					<li>
						<input type="checkbox" name="search_category[]" value="<!--{$row.term_id|escape}-->" id="arr_term_id<!--{$row.term_id|escape}-->" onclick="check_cat('<!--{$row.term_id|escape}-->');"<!--{if in_array($row.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row.term_id|escape}-->"><!--{$row.name|escape}--></label>&nbsp;&nbsp;
						<!--{if $row.categorys|@count>0}--><img id="close_<!--{$row.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row.term_id|escape}-->', 'close_<!--{$row.term_id|escape}-->')" alt="" /><!--{/if}-->
					</li>
					<div id="open_<!--{$row.term_id|escape}-->" class="child" style="display:none;">
					<ul style="margin-left:15px;list-style-type:none;">
					<!--{foreach from=$row.categorys item="row2"}-->
						<li>
							→<input type="checkbox" name="search_category[]" value="<!--{$row2.term_id|escape}-->" id="arr_term_id<!--{$row2.term_id|escape}-->" onclick="check_cat('<!--{$row2.term_id|escape}-->');"<!--{if in_array($row2.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row2.term_id|escape}-->"><!--{$row2.name|escape}--></label>&nbsp;&nbsp;
							<!--{if $row2.categorys|@count>0}--><img id="close_<!--{$row2.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row2.term_id|escape}-->', 'close_<!--{$row2.term_id|escape}-->')" alt="" /><!--{/if}-->
						</li>
						<div id="open_<!--{$row2.term_id|escape}-->" class="child" style="display:none;">
						<ul style="margin-left:15px;list-style-type:none;">
						<!--{foreach from=$row2.categorys item="row3"}-->
							<li>
								→→<input type="checkbox" name="search_category[]" value="<!--{$row3.term_id|escape}-->" id="arr_term_id<!--{$row3.term_id|escape}-->" onclick="check_cat('<!--{$row3.term_id|escape}-->');"<!--{if in_array($row3.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row3.term_id|escape}-->"><!--{$row3.name|escape}--></label>&nbsp;&nbsp;
								<!--{if $row3.categorys|@count>0}--><img id="close_<!--{$row3.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row3.term_id|escape}-->', 'close_<!--{$row3.term_id|escape}-->')" alt="" /><!--{/if}-->
							</li>
							<div id="open_<!--{$row3.term_id|escape}-->" class="child" style="display:none;">
							<ul style="margin-left:15px;list-style-type:none;">
							<!--{foreach from=$row3.categorys item="row4"}-->
								<li>
									→→→<input type="checkbox" name="search_category[]" value="<!--{$row4.term_id|escape}-->" id="arr_term_id<!--{$row4.term_id|escape}-->" onclick="check_cat('<!--{$row4.term_id|escape}-->');"<!--{if in_array($row4.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row4.term_id|escape}-->"><!--{$row4.name|escape}--></label>&nbsp;&nbsp;
									<!--{if $row4.categorys|@count>0}--><img id="close_<!--{$row4.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row4.term_id|escape}-->', 'close_<!--{$row4.term_id|escape}-->')" alt="" /><!--{/if}-->
								</li>
								<div id="open_<!--{$row4.term_id|escape}-->" class="child" style="display:none;">
								<ul style="margin-left:15px;list-style-type:none;">
								<!--{foreach from=$row4.categorys item="row5"}-->
									<li>
										→→→→<input type="checkbox" name="search_category[]" value="<!--{$row5.term_id|escape}-->" id="arr_term_id<!--{$row5.term_id|escape}-->" onclick="check_cat('<!--{$row5.term_id|escape}-->');"<!--{if in_array($row5.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row5.term_id|escape}-->"><!--{$row5.name|escape}--></label>&nbsp;&nbsp;
									</li>
								<!--{/foreach}-->
								</ul>
								</div>
							<!--{/foreach}-->
							</ul>
							</div>
						<!--{/foreach}-->
						</ul>
						</div>
					<!--{/foreach}-->
					</ul>
					</div>
				<!--{/foreach}-->
				</ul>
			</td>
		</tr>
		<script type="text/javascript">
			var arr_cat_id = [];
			var arr_cat_name = [];
			var arr_par_id = [];
			<!--{foreach from=$arr_cat_list item=val}-->
				arr_cat_id[arr_cat_id.length] = "<!--{$val.term_id|escape}-->";
				arr_cat_name[arr_cat_name.length] = "<!--{$val.name|escape}-->";
				arr_par_id[arr_par_id.length] = "<!--{$val.parent|escape}-->";
			<!--{/foreach}-->
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
				<input type="text" name="search_start_date" id="start_date" value="<!--{$search_start_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<!--{$search_end_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>公開状態</th>
			<td colapan="3">
				<input type="radio" name="search_open" value="0"<!--{if $search_open=="0" || $search_open==""}--> checked=checked<!--{/if}-->>指定無し&nbsp;&nbsp;
				<input type="radio" name="search_open" value="1"<!--{if $search_open=="1"}--> checked=checked<!--{/if}-->>公開前&nbsp;&nbsp;
				<input type="radio" name="search_open" value="2"<!--{if $search_open=="2"}--> checked=checked<!--{/if}-->>公開中&nbsp;&nbsp;
				<input type="radio" name="search_open" value="3"<!--{if $search_open=="3"}--> checked=checked<!--{/if}-->>終了&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<th>講師名</th>
			<td colspan = "3">
				<input type="text" name="search_teacher" size="45" value="<!--{$search_teacher|escape}-->">
			</td>
		</tr>
		<tr>
			<th>有料・無料</th>
			<td colspan = "3">
				<input type="radio" name="search_free" value="0"<!--{if $search_free=="0" || $search_free==""}--> checked=checked<!--{/if}-->>指定無し&nbsp;&nbsp;
				<input type="radio" name="search_free" value="1"<!--{if $search_free=="1"}--> checked=checked<!--{/if}-->>有料&nbsp;&nbsp;
				<input type="radio" name="search_free" value="2"<!--{if $search_free=="2"}--> checked=checked<!--{/if}-->>無料&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td colspan = "3">
				<input type="text" name="search_word" size="45" value="<!--{$search_word|escape}-->">
			</td>
		</tr>

		<tr>
			<th>主催弁護士会</th>
			<td colspan = "3">
				<select name="search_bar_association">
					<option value="">--------------------</option>
				<!--{foreach from=$arr_bar_association item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_bar_association}--> selected="selected"<!--{/if}-->><!--{$row.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<!--{if $all_count > 0}-->

<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>研修名</th>
		<th>弁護士会</th>
		<th>研修実施日</th>
		<th style="width:300px;">受付期間</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="info.php?pid=<!--{$row.product_id}-->"><!--{$row.product_id}--></a></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.product_name|mb_truncate:60:"..."|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
		<!--{foreach from=$row.bar_association item="row2"}-->
		<!--{$row2.name|escape}--><br>
		<!--{/foreach}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
		<!--{foreach from=$row.bar_association item="row2"}-->
		<!--{$row2.dates}--><br>
		<!--{/foreach}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
		<!--{foreach from=$row.bar_association item="row2"}-->
		<!--{if strlen($row2.receptionist_start_date) == 0 && strlen($row2.receptionist_end_date) == 0}-->
		-<br>
		<!--{else}-->
		<!--{$row2.receptionist_start_date}-->～<!--{$row2.receptionist_end_date}--><br>
		<!--{/if}-->
		<!--{/foreach}-->
		</td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="5">
<!--{$pager}-->
		</th>
	</tr>

</table>

<!--{/if}-->
