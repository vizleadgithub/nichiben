<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
	var ret = true;
	if (formAct == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
	}
	if (ret == true){
		document.getElementById("act").value = formAct;
		document.forms[formName].action = formAction;
		document.forms[formName].submit();
	}
}
function searchButton(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1024,height=980");
}
</script>

<h2>商品の内容を確認</h2>

<form name="form1" action="#" method="post">
<input type="hidden" name="mid" id="mid" value="<!--{$mid}-->" />
<input type="hidden" name="act" id="act" value="" />
<!--{foreach from=$arr_input item=item key=key}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
<!--{/foreach}-->
<!--{foreach from=$arr_input.passport_target item=item}-->
<input type="hidden" name="passport_target[]" value="<!--{$item|escape}-->" />
<!--{/foreach}-->

<table class="form">
	<tr>
		<th style="vertical-align:middle;width:200px;">商品ID</th>
		<td><!--{$mid|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品名</th>
		<td><!--{$arr_input.product_name|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)</th>
		<td><!--{$arr_input.price|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">対象者</th>
		<td>
			<!--{if $arr_input.passport_target != ""}-->
			<!--{foreach from=$arr_input.passport_target item="val"}-->
				・<!--{$arr_passport_target.$val|escape}--><br />
			<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品メイン画像</th>
		<td>
			<!--{if $arr_input.thumbnail==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<!--{if $arr_input.memo==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.memo|escape}-->
			<!--{/if}-->
		</td>
	</tr>
	
<!--{section name=related_products loop=$section_related_products start=1}-->
	<tr<!--{if $smarty.section.related_products.index % 2 != 0}--> style="background: none repeat scroll 0% 0% rgb(246, 246, 243);"<!--{/if}-->>
		<th style="vertical-align:middle;">関連商品<!--{$smarty.section.related_products.index}--></th>
		<td>
			<!--{assign var=related_products_key value="related_products"|cat:$smarty.section.related_products.index}-->
			<!--{assign var=related_products_name_key value="related_products"|cat:$smarty.section.related_products.index|cat:"_name"}-->
			<!--{if $arr_input.$related_products_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$related_products_name_key|escape}-->
			<!--{/if}-->
		</td>
	</tr>
<!--{/section}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'edit');return false;" /><img src="/alfproduct/images/btn_revise.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'info.php', 'delete');return false;" /><img src="/alfproduct/images/btn_delete.png"></a>
</div>
</form>
<a name="page_bottom"></a>
