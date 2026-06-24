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
		<th style="vertical-align:middle;">商品名<span style="color:red;">※</span></th>
		<td>
			<input type="text" name="product_name" id="product_name" value="<!--{$arr_input.product_name|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)<span style="color:red;">※半角入力</span></th>
		<td>
			<input type="text" name="price" id="price" value="<!--{$arr_input.price|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">対象者<span style="color:red;">※</span></th>
		<td>
			<!--{html_checkboxes name="passport_target" options=$arr_passport_target checked=$arr_input.passport_target|escape separator='<br />'}-->
		</td>
	</tr>
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
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<textarea name="memo" id="memo" style="width:520px;height:150px;"><!--{$arr_input.memo|escape}--></textarea>
		</td>
	</tr>
	
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
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>
