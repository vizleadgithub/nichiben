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

<h2>商品の内容を確認</h2>

<form name="form1" action="#" method="post">
<input type="hidden" name="mid" id="mid" value="<!--{$mid|escape}-->" />
<input type="hidden" name="act" id="act" value="" />
<!--{foreach from=$arr_input item=item key=key}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
<!--{/foreach}-->
<!--{foreach from=$arr_term_id item=item}-->
<input type="hidden" name="arr_term_id[]" value="<!--{$item|escape}-->" />
<!--{/foreach}-->
<!--{foreach from=$arr_input.bar_association_sponsor_unselect key=key item=item}-->
<input type="hidden" name="bar_association_sponsor_unselect[]" value="<!--{$key|escape}-->" />
<!--{/foreach}-->
<!--{foreach from=$arr_input.bar_association_target_unselect key=key item=item}-->
<input type="hidden" name="bar_association_target_unselect[]" value="<!--{$key|escape}-->" />
<!--{/foreach}-->

<table class="form">
	<tr>
		<th style="vertical-align:middle;width:200px;">商品ID</th>
		<td><!--{$mid|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;width:200px;">研修種別</th>
		<td>
			<!--{$mtb_live_training_type[$arr_input.training_kind_flg]|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修</th>
		<td>
			<!--{if $arr_input.ethic_flg=='0'}-->倫理研修対象としない<!--{/if}-->
			<!--{if $arr_input.ethic_flg=='1'}-->倫理研修対象とする<!--{/if}-->
		</td>
	</tr>
	<!--{if $nichibenren_flg}-->
	<tr>
		<th style="vertical-align:middle;">Web申込</th>
		<td>
			<!--{if $arr_input.web_flg=='0'}-->受け付けない(情報の表示のみ)<!--{/if}-->
			<!--{if $arr_input.web_flg=='1'}-->受け付ける<!--{/if}-->
		</td>
	</tr>
	<!--{/if}-->
<!--{*
	<tr>
		<th style="vertical-align:middle;">公開開始日</th>
		<td><!--{$arr_input.live_start_date|escape}--></td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">受付期間</th>
		<td><!--{$arr_input.start_date|escape}-->&nbsp;～&nbsp;<!--{$arr_input.end_date|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講料振り込み期限</th>
		<td><!--{$arr_input.limit_date|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">開催日</th>
		<td><!--{$arr_input.dates|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講票ダウンロード</th>
		<td>
			<!--{if $arr_input.download_flg=='1'}-->可<!--{/if}-->
			<!--{if $arr_input.download_flg=='0'}-->不可<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">主催</th>
		<td>
			<!--{foreach from=$arr_input.bar_association_sponsor key=key item=item}-->
			・<!--{$item|escape}--><br />
			<input type="hidden" name="bar_association_sponsor[]" value="<!--{$key|escape}-->" />
			<!--{/foreach}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象者</th>
		<td>
			<!--{$mtb_live_target_flg[$arr_input.target_flg]|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象</th>
		<td>
			<!--{if $arr_input.all_bar_association_target!=""}-->
				すべての弁護士会を対象とする
			<!--{else}-->
				<!--{foreach from=$arr_input.bar_association_target key=key item=item}-->
				・<!--{$item|escape}--><br />
				<input type="hidden" name="bar_association_target[]" value="<!--{$key|escape}-->" />
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修名</th>
		<td><!--{$arr_input.product_name|escape}--></td>
	</tr>
	<!--{if $nichibenren_flg}-->
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td><!--{$arr_input.product_code|escape}--></td>
	</tr>
	<!--{/if}-->
	<tr>
		<th style="vertical-align:middle;">研修の内容</th>
		<td><!--{$arr_input.memo1|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講義タイトル、講師名</th>
		<td><!--{$arr_input.memo2|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">日時詳細</th>
		<td><!--{$arr_input.memo3|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">会場について</th>
		<td><!--{$arr_input.hall|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">定員</th>
		<td><!--{if $arr_input.capacity==9999}-->制限なし<!--{else}--><!--{$arr_input.capacity|escape}--><!--{/if}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">単品料金(税込)</th>
		<td><!--{$arr_input.price|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問い合わせ先</th>
		<td><!--{$arr_input.memo4|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講資格/他会員の受講等</th>
		<td><!--{$arr_input.memo5|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">備考</th>
		<td><!--{$arr_input.contents|escape|nl2br}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品カテゴリ</th>
		<td>
			<ul style="list-style-type:none;">
			<!--{foreach from=$arr_category item="row"}-->
				<!--{if in_array($row.term_id,$arr_term_id)}--><li><!--{$row.name|escape}-->&nbsp;&nbsp;</li><!--{/if}-->
				<ul style="margin-left:15px;list-style-type:none;">
				<!--{foreach from=$row.categorys item="row2"}-->
					<!--{if in_array($row2.term_id,$arr_term_id)}--><li>→<!--{$row2.name|escape}-->&nbsp;&nbsp;</li><!--{/if}-->
					<ul style="margin-left:15px;list-style-type:none;">
					<!--{foreach from=$row2.categorys item="row3"}-->
						<!--{if in_array($row3.term_id,$arr_term_id)}--><li>→→<!--{$row3.name|escape}-->&nbsp;&nbsp;</li><!--{/if}-->
						<ul style="margin-left:15px;list-style-type:none;">
						<!--{foreach from=$row3.categorys item="row4"}-->
							<!--{if in_array($row4.term_id,$arr_term_id)}--><li>→→→<!--{$row4.name|escape}-->&nbsp;&nbsp;</li><!--{/if}-->
							<ul style="margin-left:15px;list-style-type:none;">
							<!--{foreach from=$row4.categorys item="row5"}-->
								<!--{if in_array($row5.term_id,$arr_term_id)}--><li>→→→→<!--{$row5.name|escape}-->&nbsp;&nbsp;</li><!--{/if}-->
							<!--{/foreach}-->
							</ul>
						<!--{/foreach}-->
						</ul>
					<!--{/foreach}-->
					</ul>
				<!--{/foreach}-->
				</ul>
			<!--{/foreach}-->
			</ul>
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
<!--{*
	<tr>
		<th style="vertical-align:middle;">商品サブ画像1</th>
		<td>
			<!--{if $arr_input.thumbnail1==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail1|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像2</th>
		<td>
			<!--{if $arr_input.thumbnail2==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail2|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像3</th>
		<td>
			<!--{if $arr_input.thumbnail3==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail3|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像4</th>
		<td>
			<!--{if $arr_input.thumbnail4==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail4|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像5</th>
		<td>
			<!--{if $arr_input.thumbnail5==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail5|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像6</th>
		<td>
			<!--{if $arr_input.thumbnail6==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail6|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像7</th>
		<td>
			<!--{if $arr_input.thumbnail7==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail7|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品サブ画像8</th>
		<td>
			<!--{if $arr_input.thumbnail8==''}-->
				未設定
			<!--{else}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail8|escape}-->&width=240&height=180" alt="" />
			<!--{/if}-->
		</td>
	</tr>
*}-->
	
	<!--{if $nichibenren_flg}-->
	<tr>
		<th colspan="2" id="bar_association_main_title">実施弁護士会</th>
	</tr>
	<!--{foreach from=$arr_bar_association item=val}-->
		<tr>
			<td colspan="2" class="bar_association_title"><!--{$val.name|escape}--></td>
		</tr>
		<!--{foreach from=$val.branch_info item=branch}-->
		<!--{assign var=branch_id value=$branch.id}-->
		<!--{assign var=capacity value='capacity'|cat:$branch_id}-->
		<!--{assign var=hall value='hall'|cat:$branch_id}-->
		<!--{assign var=receptionist_start_date value='receptionist_start_date'|cat:$branch_id}-->
		<!--{assign var=receptionist_end_date value='receptionist_end_date'|cat:$branch_id}-->
		<!--{assign var=dates value='dates'|cat:$branch_id}-->
		<!--{assign var=web_flg value='web_flg'|cat:$branch_id}-->
		<!--{assign var=contents value='contents'|cat:$branch_id}-->
		<!--{assign var=entry_number value='entry_number'|cat:$branch_id}-->
		<!--{if ($arr_input.$capacity!="" && $arr_input.$hall!="" && $arr_input.$receptionist_start_date!="" && $arr_input.$receptionist_end_date!="" && $arr_input.$dates!="" && $arr_input.$web_flg!="") || $arr_input.$web_flg=="2"}-->
		<tr>
			<td>
				<!--{$branch.name|escape}-->
			</td>
			<td>
				定員：<!--{if $arr_input.$capacity==9999}-->制限なし<!--{else}--><!--{$arr_input.$capacity|escape}--><!--{/if}--><br />
				会場：<!--{$arr_input.$hall|escape}--><br />
				受付：<!--{$arr_input.$receptionist_start_date|escape}-->&nbsp;～&nbsp;<!--{$arr_input.$receptionist_end_date|escape}--><br />
				実施日：<!--{$arr_input.$dates|escape}--><br />
				<!--{if $nichibenren_flg}-->
				Web申込：<!--{if $arr_input.$web_flg==='1'}-->WEB申込可(研修を実施する)<!--{/if}--><!--{if $arr_input.$web_flg==='0'}-->WEB申込不可(研修を実施する)<!--{/if}--><!--{if $arr_input.$web_flg==='2'}-->研修を実施しない<!--{/if}--><br />
				<!--{/if}-->
				現状申込数：<!--{$arr_input.$entry_number|escape}--><br />
				備考：<!--{$arr_input.$contents|escape}-->
			</td>
		</tr>
		<!--{/if}-->
		<!--{/foreach}-->
	<!--{/foreach}-->
	<!--{/if}-->
	
<!--{section name=related_products loop=$section_related_products start=1}-->
	<tr>
		<th style="vertical-align:middle;">関連商品<!--{$smarty.section.related_products.index}--></th>
		<td>
			<!--{assign var=related_products_name_key value="related_products"|cat:$smarty.section.related_products.index|cat:"_name"}-->
			<!--{if $arr_input.$related_products_name_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$related_products_name_key|escape}-->
			<!--{/if}-->
		</td>
	</tr>
<!--{/section}-->

<!--{*
<!--{section name=free_html_area loop=$section_free_html_area start=1}-->
	<tr>
		<th style="vertical-align:middle;">フリーHTMLエリア<!--{$smarty.section.free_html_area.index}--></th>
		<td>
			<!--{assign var=free_html_area_key value="free_html_area"|cat:$smarty.section.free_html_area.index}-->
			<!--{if $arr_input.$free_html_area_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$free_html_area_key|escape|nl2br}-->
			<!--{/if}-->
		</td>
	</tr>
<!--{/section}-->
<!--{section name=free_html_area loop=$section_free_html_area start=1}-->
	<tr>
		<th style="vertical-align:middle;">フリーHTMLエリア<!--{$smarty.section.free_html_area.index}-->（スマートフォン）</th>
		<td>
			<!--{assign var=free_html_area_key value="free_html_area"|cat:$smarty.section.free_html_area.index|cat:"_sp"}-->
			<!--{if $arr_input.$free_html_area_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$free_html_area_key|escape|nl2br}-->
			<!--{/if}-->
		</td>
	</tr>
<!--{/section}-->
*}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'edit');return false;" /><img src="/alfproduct/images/btn_revise.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'info.php', 'delete');return false;" /><img src="/alfproduct/images/btn_delete.png"></a>
</div>
</form>
<a name="page_bottom"></a>
