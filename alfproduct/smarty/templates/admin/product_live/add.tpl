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

<!--{if $nichibenren_flg}-->
function datesChange(){
	var parent_dates_val = document.getElementById("dates").value;
	var dates_name = "";
	var web_flg_name = "";
	<!--{foreach from=$arr_bar_association item=val}-->
		<!--{foreach from=$val.branch_info item=branch}-->
			<!--{assign var=branch_id value=$branch.id scope="global"}-->
			<!--{assign var=dates value='dates'|cat:$branch_id}-->
			<!--{assign var=web_flg value='web_flg'|cat:$branch_id}-->
			dates_name = "<!--{$dates|escape}-->";
			document.getElementById(dates_name).value = parent_dates_val;
			web_flg_name = "<!--{$web_flg|escape}-->";
			document.getElementById(web_flg_name + "_2").checked = true;
		<!--{/foreach}-->
	<!--{/foreach}-->
}
<!--{/if}-->
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

<!--{if !empty($err_msg)}-->
<div class="error">
<!--{foreach from=$err_msg item=msg}-->
	<!--{$msg|escape}--><br />
<!--{/foreach}-->
</div>
<!--{/if}-->
<form name="form1" action="add.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" id="act" value="confirm" />
<table class="form">
	<!--{if isset($arr_input.mid)}-->
	<tr>
		<th style="vertical-align:middle;">商品ID</th>
		<td>
			<!--{$arr_input.mid|escape}-->
			<input type="hidden" name="mid" id="mid" value="<!--{$arr_input.mid|escape}-->" />
		</td>
	</tr>
	<!--{/if}-->
	<tr>
		<th style="vertical-align:middle;width:200px;">研修種別<span style="color:red;">※</span></th>
		<td colspan = "3">
			<!--{html_options name=training_kind_flg options=$mtb_live_training_type selected=$arr_input.training_kind_flg}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修</th>
		<td colapan="3">
			<label><input type="radio" name="ethic_flg" value="0"<!--{if $arr_input.ethic_flg==0}-->checked<!--{/if}-->>倫理研修対象としない</label>
			<label><input type="radio" name="ethic_flg" value="1"<!--{if $arr_input.ethic_flg==1}-->checked<!--{/if}-->>倫理研修対象とする</label>
		</td>
	</tr>
	<!--{if $nichibenren_flg}-->
	<tr>
		<th style="vertical-align:middle;">Web申込</th>
		<td colapan="3">
			<label><input type="radio" name="web_flg" value="1"<!--{if $arr_input.web_flg==1}-->checked<!--{/if}-->>受け付ける</label>
			<label><input type="radio" name="web_flg" value="0"<!--{if $arr_input.web_flg==0}-->checked<!--{/if}-->>受け付けない(情報の表示のみ)</label>
		</td>
	</tr>
	<!--{/if}-->
<!--{*
	<tr>
		<th style="vertical-align:middle;">公開開始日<span style="color:red;">※</span></th>
		<td colapan="3">
			<input type="text" name="live_start_date" id="live_start_date" class="calendar" value="<!--{$arr_input.live_start_date|escape}-->" />
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">受付期間<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="start_date" id="start_date" class="calendar" value="<!--{$arr_input.start_date|escape}-->" />
			～
			<input type="text" name="end_date" id="end_date" class="calendar" value="<!--{$arr_input.end_date|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講料振り込み期限<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="limit_date" id="limit_date" class="calendar" value="<!--{$arr_input.limit_date|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">開催日<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="dates" id="dates" class="calendar" value="<!--{$arr_input.dates|escape}-->" <!--{if $nichibenren_flg}-->onChange="datesChange()"<!--{/if}--> />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講票ダウンロード</th>
		<td colapan="3">
			<label><input type="radio" name="download_flg" value="1"<!--{if $arr_input.download_flg==1}-->checked<!--{/if}-->>可</label>
			<label><input type="radio" name="download_flg" value="0"<!--{if $arr_input.download_flg==0}-->checked<!--{/if}-->>不可</label>
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
						<!--{foreach from=$arr_input.bar_association_sponsor key=bar_association_id item=bar_association_name}-->
						<option value="<!--{$bar_association_id|escape}-->"><!--{$bar_association_name|escape}--></option>
						<!--{/foreach}-->
						</select>
					</td>
					<td style="padding:15px;text-align:center;">
						<a href="javascript:;" name="on_select" onclick="fnMoveSelect('bar_association_sponsor_unselect','bar_association_sponsor');return false;" style="text-decoration:none;">←</a><br /><br />
						<a href="javascript:;" name="un_select" onclick="fnMoveSelect('bar_association_sponsor','bar_association_sponsor_unselect');return false;" style="text-decoration:none;">→</a>
					</td>
					<td>
						<select name="bar_association_sponsor_unselect[]" id="bar_association_sponsor_unselect" style="width:250px;height:350px;" multiple>
						<!--{foreach from=$arr_input.bar_association_sponsor_unselect key=bar_association_id item=bar_association_name}-->
						<option value="<!--{$bar_association_id|escape}-->"><!--{$bar_association_name|escape}--></option>
						<!--{/foreach}-->
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象者</th>
		<td colapan="3">
			<!--{html_radios name='target_flg' options=$mtb_live_target_flg selected=$arr_input.target_flg}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象<br />※自弁護士会は自動的に受講対象となります。</th>
		<td colapan="3">
			<table>
				<tr>
					<td><label><input type="checkbox" id="all_bar_association_target" name="all_bar_association_target" value="1" <!--{if $arr_input.all_bar_association_target==1}-->checked<!--{/if}--> onClick="allMoveTarget()" />すべての弁護士会を対象とする</label></td>
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
						<!--{foreach from=$arr_input.bar_association_target key=bar_association_id item=bar_association_name}-->
						<option value="<!--{$bar_association_id|escape}-->"><!--{$bar_association_name|escape}--></option>
						<!--{/foreach}-->
						</select>
					</td>
					<td style="padding:15px;text-align:center;">
						<a href="javascript:;" name="on_select" onclick="fnMoveSelect('bar_association_target_unselect','bar_association_target');return false;" style="text-decoration:none;">←</a><br /><br />
						<a href="javascript:;" name="un_select" onclick="fnMoveSelect('bar_association_target','bar_association_target_unselect');document.getElementById('all_bar_association_target').checked=false;return false;" style="text-decoration:none;">→</a>
					</td>
					<td>
						<select name="bar_association_target_unselect[]" id="bar_association_target_unselect" style="width:250px;height:350px;" multiple>
						<!--{foreach from=$arr_input.bar_association_target_unselect key=bar_association_id item=bar_association_name}-->
						<option value="<!--{$bar_association_id|escape}-->"><!--{$bar_association_name|escape}--></option>
						<!--{/foreach}-->
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<!--{$arr_input.product_name|escape}-->" />
		</td>
	</tr>
	<!--{if $nichibenren_flg}-->
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td>
			<input type="text" name="product_code" id="product_code" value="<!--{$arr_input.product_code|escape}-->" />
		</td>
	</tr>
	<!--{/if}-->
	<tr>
		<th style="vertical-align:middle;">研修の内容</th>
		<td>
			<textarea name="memo1" id="memo1" style="width:520px;height:150px;"><!--{$arr_input.memo1|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講義タイトル、講師名</th>
		<td>
			<textarea name="memo2" id="memo2" style="width:520px;height:150px;"><!--{$arr_input.memo2|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">日時詳細</th>
		<td>
			<textarea name="memo3" id="memo3" style="width:520px;"><!--{$arr_input.memo3|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">会場について</th>
		<td>
			<input type="text" name="hall" id="hall" value="<!--{$arr_input.hall|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">定員<br /><span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="capacity" id="capacity" value="<!--{$arr_input.capacity|escape}-->" maxlength="4" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">単品料金(税込)<br /><span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="price" id="price" value="<!--{$arr_input.price|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問い合わせ先</th>
		<td>
			<textarea name="memo4" id="memo4" style="width:520px;height:150px;"><!--{$arr_input.memo4|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講資格/他会員の受講等</th>
		<td>
			<textarea name="memo5" id="memo5" style="width:520px;height:150px;"><!--{$arr_input.memo5|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">備考</th>
		<td>
			<textarea name="contents" id="contents" style="width:520px;height:150px;"><!--{$arr_input.contents|escape}--></textarea>
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
			<!--{foreach from=$arr_category item="row"}-->
				<li>
					<input type="checkbox" name="arr_term_id[]" value="<!--{$row.term_id|escape}-->" id="arr_term_id<!--{$row.term_id|escape}-->" onclick="check_cat('<!--{$row.term_id|escape}-->');"<!--{if in_array($row.term_id,$arr_input.arr_term_id)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row.term_id|escape}-->"><!--{$row.name|escape}--></label>&nbsp;&nbsp;
					<!--{if $row.categorys|@count>0}--><img id="close_<!--{$row.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row.term_id|escape}-->', 'close_<!--{$row.term_id|escape}-->')" alt="" /><!--{/if}-->
				</li>
				<div id="open_<!--{$row.term_id|escape}-->" class="child" style="display:none;">
				<ul style="margin-left:15px;list-style-type:none;">
				<!--{foreach from=$row.categorys item="row2"}-->
					<li>
						→<input type="checkbox" name="arr_term_id[]" value="<!--{$row2.term_id|escape}-->" id="arr_term_id<!--{$row2.term_id|escape}-->" onclick="check_cat('<!--{$row2.term_id|escape}-->');"<!--{if in_array($row2.term_id,$arr_input.arr_term_id)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row2.term_id|escape}-->"><!--{$row2.name|escape}--></label>&nbsp;&nbsp;
						<!--{if $row2.categorys|@count>0}--><img id="close_<!--{$row2.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row2.term_id|escape}-->', 'close_<!--{$row2.term_id|escape}-->')" alt="" /><!--{/if}-->
					</li>
					<div id="open_<!--{$row2.term_id|escape}-->" class="child" style="display:none;">
					<ul style="margin-left:15px;list-style-type:none;">
					<!--{foreach from=$row2.categorys item="row3"}-->
						<li>
							→→<input type="checkbox" name="arr_term_id[]" value="<!--{$row3.term_id|escape}-->" id="arr_term_id<!--{$row3.term_id|escape}-->" onclick="check_cat('<!--{$row3.term_id|escape}-->');"<!--{if in_array($row3.term_id,$arr_input.arr_term_id)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row3.term_id|escape}-->"><!--{$row3.name|escape}--></label>&nbsp;&nbsp;
							<!--{if $row3.categorys|@count>0}--><img id="close_<!--{$row3.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row3.term_id|escape}-->', 'close_<!--{$row3.term_id|escape}-->')" alt="" /><!--{/if}-->
						</li>
						<div id="open_<!--{$row3.term_id|escape}-->" class="child" style="display:none;">
						<ul style="margin-left:15px;list-style-type:none;">
						<!--{foreach from=$row3.categorys item="row4"}-->
							<li>
								→→→<input type="checkbox" name="arr_term_id[]" value="<!--{$row4.term_id|escape}-->" id="arr_term_id<!--{$row4.term_id|escape}-->" onclick="check_cat('<!--{$row4.term_id|escape}-->');"<!--{if in_array($row4.term_id,$arr_input.arr_term_id)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row4.term_id|escape}-->"><!--{$row4.name|escape}--></label>&nbsp;&nbsp;
								<!--{if $row4.categorys|@count>0}--><img id="close_<!--{$row4.term_id|escape}-->" src="/alfproduct/images/hide.gif" onclick="openFolder('open_<!--{$row4.term_id|escape}-->', 'close_<!--{$row4.term_id|escape}-->')" alt="" /><!--{/if}-->
							</li>
							<div id="open_<!--{$row4.term_id|escape}-->" class="child" style="display:none;">
							<ul style="margin-left:15px;list-style-type:none;">
							<!--{foreach from=$row4.categorys item="row5"}-->
								<li>
									→→→→<input type="checkbox" name="arr_term_id[]" value="<!--{$row5.term_id|escape}-->" id="arr_term_id<!--{$row5.term_id|escape}-->" onclick="check_cat('<!--{$row5.term_id|escape}-->');"<!--{if in_array($row5.term_id,$arr_input.arr_term_id)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row5.term_id|escape}-->"><!--{$row5.name|escape}--></label>&nbsp;&nbsp;
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
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php', '', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');" />
			<!--{if isset($arr_input.thumbnail) && $arr_input.thumbnail!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<!--{$arr_input.thumbnail|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail.value='';formSubmit('form1', 'delete_thumbnail.php', '', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	
	<!--{if $nichibenren_flg}-->
	<tr>
		<th colspan="4" id="bar_association_main_title">実施弁護士会</th>
	</tr>
	<!--{foreach from=$arr_bar_association item=val}-->
		<tr>
			<td colspan="4" class="bar_association_title"><!--{$val.name}--></td>
		</tr>
		<!--{foreach from=$val.branch_info item=branch}-->
		<!--{assign var=branch_id value=$branch.id scope="global"}-->
		<!--{assign var=capacity value='capacity'|cat:$branch_id}-->
		<!--{assign var=hall value='hall'|cat:$branch_id}-->
		<!--{assign var=receptionist_start_date value='receptionist_start_date'|cat:$branch_id}-->
		<!--{assign var=receptionist_end_date value='receptionist_end_date'|cat:$branch_id}-->
		<!--{assign var=dates value='dates'|cat:$branch_id}-->
		<!--{assign var=web_flg value='web_flg'|cat:$branch_id}-->
		<!--{assign var=contents value='contents'|cat:$branch_id}-->
		<!--{assign var=entry_number value='entry_number'|cat:$branch_id}-->
		<tr>
			<td>
				<!--{$branch.name}-->
			</td>
			<td>
				定員<span style="color:red;">※</span>：<input type="text" name="<!--{$capacity}-->" value="<!--{$arr_input.$capacity|escape}-->" maxlength="4" /><br />
				会場<span style="color:red;">※</span>：<input type="text" name="<!--{$hall}-->" value="<!--{$arr_input.$hall|escape}-->" /><br />
				受付<span style="color:red;">※</span>：<input type="text" name="<!--{$receptionist_start_date}-->" id="<!--{$receptionist_start_date}-->" class="calendar" value="<!--{$arr_input.$receptionist_start_date|escape}-->" style="width:200px;" />～<input type="text" name="<!--{$receptionist_end_date}-->" id="<!--{$receptionist_end_date}-->" class="calendar" value="<!--{$arr_input.$receptionist_end_date|escape}-->" style="width:200px;" /><br />
				実施日<span style="color:red;">※</span>：<input type="text" name="<!--{$dates}-->" id="<!--{$dates}-->" class="calendar" value="<!--{$arr_input.$dates|escape}-->" /><br />
				<!--{if $nichibenren_flg}-->
				Web申込<span style="color:red;">※</span>：<label><input type="radio" name="<!--{$web_flg}-->" value="1" <!--{if $arr_input.$web_flg==='1'}-->checked<!--{/if}-->  id="<!--{$web_flg}-->_1" />WEB申込可(研修を実施する)</label>　<label><input type="radio" name="<!--{$web_flg}-->" value="0" <!--{if $arr_input.$web_flg==='0'}-->checked<!--{/if}-->  id="<!--{$web_flg}-->_0" />WEB申込不可(研修を実施する)</label>　<label><input type="radio" name="<!--{$web_flg}-->" value="2" <!--{if $arr_input.$web_flg==='2'}-->checked<!--{/if}--> id="<!--{$web_flg}-->_2" />研修を実施しない</label><br />
				<!--{/if}-->
				<!--{if isset($arr_input.mid)}-->
				現状申込数：<!--{$arr_input.$entry_number|escape}--><br />
				<!--{/if}-->
				備考：<textarea name="<!--{$contents}-->" style="width:400px;height:100px;"><!--{$arr_input.$contents|escape}--></textarea>
			</td>
		</tr>
		<!--{/foreach}-->
	<!--{/foreach}-->
	<!--{/if}-->
	
	<!--{section name=related_products loop=$section_related_products start=1}-->
	<tr>
		<th style="vertical-align:middle;">関連商品<!--{$smarty.section.related_products.index}--></th>
		<td>
			<!--{assign var=related_products_key value="related_products"|cat:$smarty.section.related_products.index}-->
			<!--{assign var=related_products_name_key value="related_products"|cat:$smarty.section.related_products.index|cat:"_name"}-->
			<span id="spa_<!--{$related_products_key}-->" ><!--{$arr_input.$related_products_name_key|escape}--></span>
			<input type="hidden" name="<!--{$related_products_key}-->" id="hid_<!--{$related_products_key}-->" value="<!--{$arr_input.$related_products_key|escape}-->" />
			<input type="hidden" name="<!--{$related_products_name_key}-->" id="hid_<!--{$related_products_name_key}-->" value="<!--{$arr_input.$related_products_name_key|escape}-->" />
			<input type="button" value="検索" onclick="searchButton('search_product.php?gid=<!--{$related_products_key}-->')" />
			<!--<div id="delete_<!--{$related_products_key}-->_link">--><a href="javascript:void(0);" onclick="delete_<!--{$related_products_key}-->()">削除</a><!--<div>-->
			<script type="text/javascript">
			function delete_<!--{$related_products_key}-->(){
				document.form1.<!--{$related_products_key}-->.value='';
				document.form1.<!--{$related_products_name_key}-->.value='';
				document.getElementById("spa_<!--{$related_products_key}-->").innerText = '';
				if (typeof document.getElementById("spa_<!--{$related_products_key}-->").textContent!= "undefined") {
					document.getElementById("spa_<!--{$related_products_key}-->").textContent = '';
				}
				//document.getElementById("delete_<!--{$related_products_key}-->_link").style.display="none";
			}
			</script>

		</td>
	</tr>
	<!--{/section}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm', 'bar_association_sponsor', 'bar_association_sponsor_unselect', 'bar_association_target', 'bar_association_target_unselect');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>
