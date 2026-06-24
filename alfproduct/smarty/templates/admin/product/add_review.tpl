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

<!--{if !empty($err_msg)}-->
<div class="error">
<!--{foreach from=$err_msg item=msg}-->
	<!--{$msg}--><br />
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
<!--{*
	<tr>
		<th style="vertical-align:middle;width:200px;">商品種別</th>
		<td>
			<label><input type="radio" name="product_type" value="1" <!--{if $arr_input.product_type==1}-->checked<!--{/if}--> />会員専用</label>
			<label><input type="radio" name="product_type" value="2" <!--{if $arr_input.product_type==2}-->checked<!--{/if}--> />一般公開</label>
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;width:200px;">商品種別</th>
		<td>
			<label><input type="radio" name="product_kind_flg" value="1" <!--{if $arr_input.product_kind_flg==1}-->checked<!--{/if}--> />eラーニング</label>
			<label>
			<input type="radio" name="product_kind_flg" value="2" <!--{if $arr_input.product_kind_flg==2}-->checked<!--{/if}--> />e-ライブ
			<span id="spa_live_training_product_id" ><!--{$arr_input.live_training_product_name|escape}--></span>
			<input type="hidden" name="live_training_product_id" id="hid_live_training_product_id" value="<!--{$arr_input.live_training_product_id|escape}-->" />
			<input type="hidden" name="live_training_product_name" id="hid_live_training_product_id_name" value="<!--{$arr_input.live_training_product_name|escape}-->" />
			<input type="button" value="検索" onclick="searchButton('search_elive.php?gid=live_training_product_id')" />
			<a href="javascript:void(0);" onclick="delete_live_training_product_id()">削除</a>
			</label>
			<label><input type="radio" name="product_kind_flg" value="0" <!--{if $arr_input.product_kind_flg==='0'}-->checked<!--{/if}--> />その他</label>
			<label><input type="radio" name="product_kind_flg" value="3" <!--{if $arr_input.product_kind_flg==='3'}-->checked<!--{/if}--> />設問付きeラーニング</label>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<!--{$arr_input.product_name|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品コード<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_code" id="product_code" value="<!--{$arr_input.product_code|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)<span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="price" id="price" value="<!--{$arr_input.price|escape}-->" />
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
			<!--{*
			<!--{foreach from=$productcategory_list item=val1}-->
				<!--{assign var=checked value=''}-->
				<!--{foreach from=$arr_input.arr_term_id item=term}-->
					<!--{if $term == $val1.big.term_id}-->
						<!--{assign var=checked value='checked'}-->
					<!--{/if}-->
				<!--{/foreach}-->
				▼<label><input type="checkbox" name="arr_term_id[]" id="arr_term_id<!--{$val1.big.term_id|escape}-->" value="<!--{$val1.big.term_id|escape}-->" <!--{$checked}--> onclick="check_cat('<!--{$val1.big.term_id|escape}-->');" /><!--{$val1.big.name|escape}--></label><br />
				<!--{foreach from=$val1.small item=val2}-->
					<!--{assign var=checked value=''}-->
					<!--{foreach from=$arr_input.arr_term_id item=term}-->
						<!--{if $term == $val2.term_id}-->
							<!--{assign var=checked value='checked'}-->
						<!--{/if}-->
					<!--{/foreach}-->
					　→<label><input type="checkbox" name="arr_term_id[]" id="arr_term_id<!--{$val2.term_id|escape}-->" value="<!--{$val2.term_id|escape}-->" <!--{$checked}--> onclick="check_cat('<!--{$val2.term_id|escape}-->');" /><!--{$val2.name|escape}--></label>
					<!--{/foreach}-->
					<br />
			<!--{/foreach}-->
			*}-->

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
<!--{*
	<tr>
		<th style="vertical-align:middle;">割引コード</th>
		<td>
			<input type="text" name="discount_code" id="discount_code" value="<!--{$arr_input.discount_code|escape}-->" />
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">公開期間</th>
		<td>
			<input type="text" name="start_date" id="start_date" class="calendar" value="<!--{$arr_input.start_date|escape}-->" />
			～
			<input type="text" name="end_date" id="end_date" class="calendar" value="<!--{$arr_input.end_date|escape}-->" />
		</td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">購入後公開期間日数</th>
		<td>
			<select name="open_period" id="open_period">
			<!--{section name=open_period loop=$section_open_period start=0}-->
				<option value="<!--{$smarty.section.open_period.index}-->" <!--{if $arr_input.open_period==$smarty.section.open_period.index}-->selected<!--{/if}-->><!--{$smarty.section.open_period.index}--></option>
			<!--{/section}-->
			</select>
			※0選択時は無制限に公開
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail) && $arr_input.thumbnail!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<!--{$arr_input.thumbnail|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">商品サブ画像1<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail1" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail1) && $arr_input.thumbnail1!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail1|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail1" value="<!--{$arr_input.thumbnail1|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail1.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像2<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail2" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail2) && $arr_input.thumbnail2!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail2|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail2" value="<!--{$arr_input.thumbnail2|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail2.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像3<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail3" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail3) && $arr_input.thumbnail3!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail3|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail3" value="<!--{$arr_input.thumbnail3|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail3.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像4<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail4" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail4) && $arr_input.thumbnail4!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail4|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail4" value="<!--{$arr_input.thumbnail4|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail4.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像5<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail5" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail5) && $arr_input.thumbnail5!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail5|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail5" value="<!--{$arr_input.thumbnail5|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail5.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像6<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail6" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail6) && $arr_input.thumbnail6!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail6|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail6" value="<!--{$arr_input.thumbnail6|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail6.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像7<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail7" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail7) && $arr_input.thumbnail7!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail7|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail7" value="<!--{$arr_input.thumbnail7|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail7.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像8<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="thumbnail8" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{if isset($arr_input.thumbnail8) && $arr_input.thumbnail8!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail8|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail8" value="<!--{$arr_input.thumbnail8|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_thumbnail8.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<textarea name="memo" id="memo" style="width:520px;height:150px;"><!--{$arr_input.memo|escape}--></textarea>
		</td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">商品再生時間</th>
		<td>
			<input type="text" name="play_time" id="play_time" value="<!--{$arr_input.play_time|escape}-->" />
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">商品講師名</th>
		<td>
			<input type="text" name="teacher" id="teacher" value="<!--{$arr_input.teacher|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師受講者</th>
		<td>
			<span id="spa_teacher_student_id">
			<!--{if $arr_input.teacher_student_id>0}-->
			[<!--{$arr_input.teacher_student.lawyer_number|escape}-->]
			<!--{$arr_input.teacher_student.student_name|escape}-->
			<!--{/if}-->
			</span>
			<input type="hidden" name="teacher_student_id" id="hid_teacher_student_id" value="<!--{$arr_input.teacher_student_id|escape}-->" />
			<input type="hidden" name="teacher_student_name" id="hid_teacher_student_id_name" value="<!--{$arr_input.teacher_student_name|escape}-->" />
			<input type="hidden" name="teacher_student_name" id="hid_teacher_student_id_name" value="" />

			<input type="button" value="検索" onclick="searchButton('search_student.php?gid=teacher_student_id')" />
			<input type="button" value="解除" onclick="deleteTeacherStudent()" />
			<script type="text/javascript">
				function deleteTeacherStudent(){
					document.getElementById("hid_teacher_student_id").value   = '';
					document.getElementById("disp_teacher_student").innerHTML = '';
				}
			</script>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">フラグ管理</th>
		<td>
			<!--{html_checkboxes name="product_flg" options=$arr_product_flg checked=$arr_input.product_flg|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">注意文言の掲載箇所</th>
		<td>
			<!--{html_checkboxes name="product_disp_warning_word" options=$arr_product_disp_warning_word checked=$arr_input.product_disp_warning_word|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">検索ワード<br />(複数登録する場合は改行区切りで入力してください)</th>
		<td>
			<textarea name="search_word" id="search_word" style="width:520px;height:150px;"><!--{$arr_input.search_word|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">一括ダウンロード用資料</th>
		<td>
			<input type="file" name="all_contents_download" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_all_contents.php');" />
			<!--{if $arr_input.all_contents_download != ''}-->
				<br />
				ファイル名：<!--{$arr_input.all_contents_download_before|escape}-->
				<input type="hidden" name="hid_all_contents_download" value="<!--{$arr_input.all_contents_download|escape}-->" />
				<input type="hidden" name="all_contents_download_before" value="<!--{$arr_input.all_contents_download_before|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_all_contents_download.value='';formSubmit('form1', 'delete_all_contents.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	
<tr><td colspan="2">
<!--{section name=contents_loop loop=$section_contents start=1}-->
<div onClick="contentsOpen('<!--{$smarty.section.contents_loop.index}-->')" style="cursor:pointer;background-color:#fde9d9 !important;height:25px;padding:10px 0 0 10px;font-size:14px;font-weight:bold;border-top:solid 1px #000000;border-bottom:solid 1px #000000;">
▼コンテンツ<!--{$smarty.section.contents_loop.index}-->
</div>
<table id="contentsTable<!--{$smarty.section.contents_loop.index}-->" style="display:none;" />
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->サムネイル画像<br>横600px × 縦600px</th>
		<td>
			<input type="file" name="contents_thumbnail<!--{$smarty.section.contents_loop.index}-->" size="50" />
			<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_thumbnail.php');" />
			<!--{assign var=contents_thumbnail_key value="contents_thumbnail"|cat:$smarty.section.contents_loop.index}-->
			<!--{if isset($arr_input.$contents_thumbnail_key) && $arr_input.$contents_thumbnail_key!=""}-->
				<br />
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.$contents_thumbnail_key|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_contents_thumbnail<!--{$smarty.section.contents_loop.index}-->" value="<!--{$arr_input.$contents_thumbnail_key|escape}-->" />
				<a href="javascript:void(0);" onclick="document.form1.hid_contents_thumbnail<!--{$smarty.section.contents_loop.index}-->.value='';formSubmit('form1', 'delete_thumbnail.php');">削除</a>
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->コンテンツ<!--{if $smarty.section.contents_loop.index == '1'}--><span style="color:red;">※</span><!--{/if}--></th>
		<td>
			<!--{assign var=contents_contents_key value="contents_contents"|cat:$smarty.section.contents_loop.index}-->
			<!--{assign var=contents_contents_name_key value="contents_contents"|cat:$smarty.section.contents_loop.index|cat:"_name"}-->
			<!--{if $contents_contents_key!=""}--><input type="text" name="<!--{$contents_contents_name_key}-->" id="hid_<!--{$contents_contents_name_key}-->" value="<!--{$arr_input.$contents_contents_name_key|escape}-->" /><!--{/if}-->
			<input type="hidden" name="<!--{$contents_contents_key}-->" id="hid_<!--{$contents_contents_key}-->" value="<!--{$arr_input.$contents_contents_key|escape}-->" />
			<!--<input type="hidden" name="<!--{$contents_contents_name_key}-->" id="hid_<!--{$contents_contents_name_key}-->" value="<!--{$arr_input.$contents_contents_name_key|escape}-->" />-->
			<input type="button" value="検索" onclick="searchButton('search_contents.php?gid=<!--{$contents_contents_key}-->')" />
		</td>
	</tr>

	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->音声コンテンツ<!--{if $smarty.section.contents_loop.index == '1'}--><!--{/if}--></th>
		<td>
			<!--{assign var=contents_contents_so_key value="contents_contents"|cat:$smarty.section.contents_loop.index|cat:"so"}-->
			<!--{assign var=contents_contents_so_name_key value="contents_contents"|cat:$smarty.section.contents_loop.index|cat:"so_name"}-->
			<!--{if $contents_contents_so_key!=""}--><input type="text" name="<!--{$contents_contents_so_name_key}-->" id="hid_<!--{$contents_contents_so_name_key}-->" value="<!--{$arr_input.$contents_contents_so_name_key|escape}-->" /><!--{/if}-->
			<input type="hidden" name="<!--{$contents_contents_so_key}-->" id="hid_<!--{$contents_contents_so_key}-->" value="<!--{$arr_input.$contents_contents_so_key|escape}-->" />
			<input type="button" value="検索" onclick="searchButton('search_contents_so.php?gid=<!--{$contents_contents_so_key}-->')" />
		</td>
	</tr>

	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->無料公開範囲(秒)</th>
		<td>
			<!--{assign var=contents_free_time_key value="contents_free_time"|cat:$smarty.section.contents_loop.index}-->
			<select name="<!--{$contents_free_time_key}-->" id="<!--{$contents_free_time_key}-->">
				<!--{section name=contents_free_time loop=$section_contents_free_time start=0}-->
					<option value="<!--{$smarty.section.contents_free_time.index}-->" <!--{if $arr_input.$contents_free_time_key==$smarty.section.contents_free_time.index}-->selected<!--{/if}-->><!--{$smarty.section.contents_free_time.index}--></option>
				<!--{/section}-->
			</select>
			※0選択時は無料部分なし
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->公開期間</th>
		<td>
			<!--{assign var=contents_start_date_key value="contents_start_date"|cat:$smarty.section.contents_loop.index}-->
			<!--{assign var=contents_end_date_key value="contents_end_date"|cat:$smarty.section.contents_loop.index}-->

			<input type="text" name="<!--{$contents_start_date_key}-->" id="<!--{$contents_start_date_key}-->" class="calendar" value="<!--{$arr_input.$contents_start_date_key|escape}-->" />
			～
			<input type="text" name="<!--{$contents_end_date_key}-->" id="<!--{$contents_end_date_key}-->" class="calendar" value="<!--{$arr_input.$contents_end_date_key|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->説明</th>
		<td>
			<!--{assign var=contents_memo_key value="contents_memo"|cat:$smarty.section.contents_loop.index}-->
			<!--{assign var=contents_memo_key_id value="hid_contents_contents"|cat:$smarty.section.contents_loop.index|cat:"_memo"}-->
			<textarea name="<!--{$contents_memo_key}-->" id="<!--{$contents_memo_key_id}-->" style="width:520px;height:150px;"><!--{$arr_input.$contents_memo_key|escape}--></textarea>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=contents_teacher_key value="contents_teacher"|cat:$smarty.section.contents_loop.index}-->
			<input type="text" name="<!--{$contents_teacher_key}-->" id="<!--{$contents_teacher_key}-->" value="<!--{$arr_input.$contents_teacher_key|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問題(テスト)名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=exam_id_test_key value="exam_id_test"|cat:$smarty.section.contents_loop.index}-->
			<select name="<!--{$exam_id_test_key}-->" id="<!--{$exam_id_test_key}-->">
				<option value=""></option>
				<!--{foreach from=$exam_list item=exam}-->
					<option value="<!--{$exam.exam_id}-->" <!--{if $arr_input.$exam_id_test_key==$exam.exam_id}-->selected<!--{/if}-->><!--{$exam.exam_name}--></option>
				<!--{/foreach}-->
			</select>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問題(アンケート)名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=exam_id_question_key value="exam_id_question"|cat:$smarty.section.contents_loop.index}-->
			<select name="<!--{$exam_id_question_key}-->" id="<!--{$exam_id_question_key}-->">
				<option value=""></option>
				<!--{foreach from=$exam_list item=exam}-->
					<option value="<!--{$exam.exam_id}-->" <!--{if $arr_input.$exam_id_question_key==$exam.exam_id}-->selected<!--{/if}-->><!--{$exam.exam_name}--></option>
				<!--{/foreach}-->
			</select>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">ボタンの選択<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=btn_type_key value="btn_type"|cat:$smarty.section.contents_loop.index}-->

			<select name="<!--{$btn_type_key}-->" id="<!--{$btn_type_key}-->">
				<option value=""></option>
				<!--{foreach from=$btn_type_list item=btn_type_name key=btn_type_id}-->
					<option value="<!--{$btn_type_id}-->" <!--{if $arr_input.$btn_type_key==$btn_type_id}-->selected<!--{/if}-->><!--{$btn_type_name}--></option>
				<!--{/foreach}-->
			</select>
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">注意文言の掲載箇所</th>
		<td>
			<!--{assign var=disp_warning_word_key value="disp_warning_word"|cat:$smarty.section.contents_loop.index}-->
<!--{*
			<label><input type="checkbox" name="<!--{$disp_warning_word_key}-->[]" value="1" <!--{if array_search('1', $arr_input.$disp_warning_word_key)!==false}-->checked<!--{/if}-->>商品説明下</label>
			<label><input type="checkbox" name="<!--{$disp_warning_word_key}-->[]" value="2" <!--{if array_search('2', $arr_input.$disp_warning_word_key)!==false}-->checked<!--{/if}-->>商品資料下</label>
*}-->
			<label><input type="checkbox" name="<!--{$disp_warning_word_key}-->[]" value="3" <!--{if array_search('3', $arr_input.$disp_warning_word_key)!==false}-->checked<!--{/if}-->>テストボタン右</label>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="#page_bottom">ページの下へ</a>
		</td>
	</tr>
	<!--{section name=contents_download loop=$section_contents_download start=1}-->
		<tr>
			<th style="vertical-align:middle;">ダウンロード<!--{$smarty.section.contents_loop.index}-->-<!--{$smarty.section.contents_download.index}--></th>
			<td>
				<!--{assign var=contents_download_key value="contents_download"|cat:$smarty.section.contents_loop.index|cat:"_"|cat:$smarty.section.contents_download.index}-->
				<!--{assign var=contents_download_before_key value="contents_download_before"|cat:$smarty.section.contents_loop.index|cat:"_"|cat:$smarty.section.contents_download.index}-->
				<input type="file" name="<!--{$contents_download_key}-->" size="50" />
				<input type="button" value="アップロード" onclick="formSubmit('form1', 'upload_document.php');" />
				<!--{if $arr_input.$contents_download_key != ''}-->
					<br />
					ファイル名：<!--{$arr_input.$contents_download_before_key|escape}-->
					<input type="hidden" name="hid_<!--{$contents_download_key}-->" value="<!--{$arr_input.$contents_download_key|escape}-->" />
					<input type="hidden" name="<!--{$contents_download_before_key}-->" value="<!--{$arr_input.$contents_download_before_key|escape}-->" />
					<a href="javascript:void(0);" onclick="document.form1.hid_<!--{$contents_download_key}-->.value='';formSubmit('form1', 'delete_document.php');">削除</a>
				<!--{/if}-->
			</td>
		</tr>
	<!--{/section}-->
</table>
<!--{/section}-->
</td></tr>

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

<!--{*
<!--{section name=free_html_area loop=$section_free_html_area start=1}-->
	<tr>
		<th style="vertical-align:middle;">フリーHTMLエリア<!--{$smarty.section.free_html_area.index}--></th>
		<td>
			<!--{assign var=free_html_area_key value="free_html_area"|cat:$smarty.section.free_html_area.index}-->
			<textarea name="<!--{$free_html_area_key}-->" id="<!--{$free_html_area_key}-->" style="width:520px;height:150px;"><!--{$arr_input.$free_html_area_key|escape}--></textarea>
		</td>
	</tr>
<!--{/section}-->
<!--{section name=free_html_area loop=$section_free_html_area start=1}-->
	<tr>
		<th style="vertical-align:middle;">フリーHTMLエリア<!--{$smarty.section.free_html_area.index}-->（スマートフォン）</th>
		<td>
			<!--{assign var=free_html_area_key value="free_html_area"|cat:$smarty.section.free_html_area.index|cat:"_sp"}-->
			<textarea name="<!--{$free_html_area_key}-->" id="<!--{$free_html_area_key}-->" style="width:520px;height:150px;"><!--{$arr_input.$free_html_area_key|escape}--></textarea>
		</td>
	</tr>
<!--{/section}-->
*}-->
	<tr>
		<th style="vertical-align:middle;">商品アンケート</th>
		<td>
			<!--{assign var=exam2_id_key value="exam2_id"}-->
			<select name="<!--{$exam2_id_key}-->" id="<!--{$exam2_id_key}-->">
				<option value=""></option>
				<!--{foreach from=$exam2_list item=exam2}-->
					<option value="<!--{$exam2.exam2_id}-->" <!--{if $arr_input.$exam2_id_key==$exam2.exam2_id}-->selected<!--{/if}-->><!--{$exam2.exam2_name}--></option>
				<!--{/foreach}-->
			</select>
		</td>
	</tr>
	
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>
