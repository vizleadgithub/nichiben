<script type="text/javascript">
function formSubmit(formName, formAction, formAct){
  document.getElementById("act").value = formAct;
  document.forms[formName].action = formAction;
  document.forms[formName].submit();
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
		<th style="vertical-align:middle;width:200px;">研修種別</th>
		<td colspan = "3">
			<!--{$mtb_live_training_type[$arr_input.training_kind_flg]|escape}-->
			<input type="hidden" name="training_kind_flg" id="training_kind_flg" value="<!--{$arr_input.training_kind_flg|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">倫理研修</th>
		<td colapan="3">
			<!--{if $arr_input.ethic_flg==0}-->
			倫理研修対象としない
			<!--{/if}-->
			<!--{if $arr_input.ethic_flg==1}-->
			倫理研修対象とする
			<!--{/if}-->
			<input type="hidden" name="ethic_flg" id="ethic_flg" value="<!--{$arr_input.ethic_flg|escape}-->" />
		</td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">Web申込</th>
		<td colapan="3">
			<!--{if $arr_input.web_flg==0}-->
			受け付けない(情報の表示のみ)
			<!--{/if}-->
			<!--{if $arr_input.web_flg==1}-->
			受け付ける
			<!--{/if}-->
			<input type="hidden" name="web_flg" id="web_flg" value="<!--{$arr_input.web_flg|escape}-->" />
		</td>
	</tr>
*}-->
<!--{*
	<tr>
		<th style="vertical-align:middle;">公開開始日</th>
		<td colapan="3">
			<!--{$arr_input.live_start_date|escape}-->
			<input type="hidden" name="live_start_date" id="live_start_date" value="<!--{$arr_input.live_start_date|escape}-->" />
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">受付期間</th>
		<td>
			<!--{$arr_input.start_date|escape}-->&nbsp;～&nbsp;<!--{$arr_input.end_date|escape}-->
			<input type="hidden" name="start_date" id="start_date" value="<!--{$arr_input.start_date|escape}-->" />
			<input type="hidden" name="end_date" id="end_date" value="<!--{$arr_input.end_date|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講料振り込み期限</th>
		<td>
			<!--{$arr_input.limit_date|escape}-->
			<input type="hidden" name="limit_date" id="limit_date" value="<!--{$arr_input.limit_date|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">開催日</th>
		<td>
			<!--{$arr_input.dates|escape}-->
			<input type="hidden" name="dates" id="dates" value="<!--{$arr_input.dates|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講票ダウンロード</th>
		<td colapan="3">
			<!--{if $arr_input.download_flg==1}-->
			可
			<!--{/if}-->
			<!--{if $arr_input.download_flg==0}-->
			不可
			<!--{/if}-->
			<input type="hidden" name="download_flg" id="download_flg" value="<!--{$arr_input.download_flg|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">主催</th>
		<td colapan="3">
			<!--{foreach from=$arr_input.bar_association_sponsor key=bar_association_id item=bar_association_name}-->
			・<!--{$bar_association_name|escape}--><br />
			<input type="hidden" name="bar_association_sponsor[]" id="bar_association_sponsor" value="<!--{$bar_association_id|escape}-->" />
			<!--{/foreach}-->
			<!--{foreach from=$arr_input.bar_association_sponsor_unselect key=bar_association_id item=bar_association_name}-->
			<input type="hidden" name="bar_association_sponsor_unselect[]" id="bar_association_sponsor_unselect" value="<!--{$bar_association_id|escape}-->" />
			<!--{/foreach}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象者</th>
		<td colapan="3">
			<!--{$mtb_live_target_flg[$arr_input.target_flg]|escape}-->
			<input type="hidden" name="target_flg" id="target_flg" value="<!--{$arr_input.target_flg|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講対象</th>
		<td colapan="3">
			<!--{if $arr_input.all_bar_association_target==1}-->
				すべての弁護士会を対象とする
				<input type="hidden" name="all_bar_association_target" id="all_bar_association_target" value="<!--{$arr_input.all_bar_association_target|escape}-->" />
			<!--{else}-->
				<!--{foreach from=$arr_input.bar_association_target key=bar_association_id item=bar_association_name}-->
				・<!--{$bar_association_name|escape}--><br />
				<input type="hidden" name="bar_association_target[]" id="bar_association_target" value="<!--{$bar_association_id|escape}-->" />
				<!--{/foreach}-->
				<!--{foreach from=$arr_input.bar_association_target_unselect key=bar_association_id item=bar_association_name}-->
				<input type="hidden" name="bar_association_target_unselect[]" id="bar_association_target_unselect" value="<!--{$bar_association_id|escape}-->" />
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修名</th>
		<td>
			<!--{$arr_input.product_name|escape}-->
			<input type="hidden" name="product_name" id="product_name" value="<!--{$arr_input.product_name|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td>
			<!--{$arr_input.product_code|escape}-->
			<input type="hidden" name="product_code" id="product_code" value="<!--{$arr_input.product_code|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">研修の内容</th>
		<td>
			<!--{$arr_input.memo1|escape|nl2br}-->
			<input type="hidden" name="memo1" id="memo1" value="<!--{$arr_input.memo1|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講義タイトル、講師名</th>
		<td>
			<!--{$arr_input.memo2|escape|nl2br}-->
			<input type="hidden" name="memo2" id="memo2" value="<!--{$arr_input.memo2|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">日時詳細</th>
		<td>
			<!--{$arr_input.memo3|escape|nl2br}-->
			<input type="hidden" name="memo3" id="memo3" value="<!--{$arr_input.memo3|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">会場について</th>
		<td>
			<!--{$arr_input.hall|escape}-->
			<input type="hidden" name="hall" id="hall" value="<!--{$arr_input.hall|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">定員</th>
		<td>
			<!--{$arr_input.capacity|escape}-->
			<input type="hidden" name="capacity" id="capacity" value="<!--{$arr_input.capacity|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">単品料金(税込)</th>
		<td>
			<!--{$arr_input.price|escape}-->
			<input type="hidden" name="price" id="price" value="<!--{$arr_input.price|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問い合わせ先</th>
		<td>
			<!--{$arr_input.memo4|escape|nl2br}-->
			<input type="hidden" name="memo4" id="memo4" value="<!--{$arr_input.memo4|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">受講資格/他会員の受講等</th>
		<td>
			<!--{$arr_input.memo5|escape|nl2br}-->
			<input type="hidden" name="memo5" id="memo5" value="<!--{$arr_input.memo5|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">備考</th>
		<td>
			<!--{$arr_input.contents|escape|nl2br}-->
			<input type="hidden" name="contents" id="contents" value="<!--{$arr_input.contents|escape}-->" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品カテゴリ</th>
		<td>
			<ul style="list-style-type:none;">
			<!--{foreach from=$arr_category item="row"}-->
				<!--{if in_array($row.term_id,$arr_term_id)}--><li><!--{$row.name|escape}-->&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<!--{$row.term_id|escape}-->" value="<!--{$row.term_id|escape}-->" /></li><!--{/if}-->
				<ul style="margin-left:15px;list-style-type:none;">
				<!--{foreach from=$row.categorys item="row2"}-->
					<!--{if in_array($row2.term_id,$arr_term_id)}--><li>→<!--{$row2.name|escape}-->&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<!--{$row2.term_id|escape}-->" value="<!--{$row2.term_id|escape}-->" /></li><!--{/if}-->
					<ul style="margin-left:15px;list-style-type:none;">
					<!--{foreach from=$row2.categorys item="row3"}-->
						<!--{if in_array($row3.term_id,$arr_term_id)}--><li>→→<!--{$row3.name|escape}-->&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<!--{$row3.term_id|escape}-->" value="<!--{$row3.term_id|escape}-->" /></li><!--{/if}-->
						<ul style="margin-left:15px;list-style-type:none;">
						<!--{foreach from=$row3.categorys item="row4"}-->
							<!--{if in_array($row4.term_id,$arr_term_id)}--><li>→→→<!--{$row4.name|escape}-->&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<!--{$row4.term_id|escape}-->" value="<!--{$row4.term_id|escape}-->" /></li><!--{/if}-->
							<ul style="margin-left:15px;list-style-type:none;">
							<!--{foreach from=$row4.categorys item="row5"}-->
								<!--{if in_array($row5.term_id,$arr_term_id)}--><li>→→→→<!--{$row5.name|escape}-->&nbsp;&nbsp;<input type="hidden" name="arr_term_id[]" id="arr_term_id<!--{$row5.term_id|escape}-->" value="<!--{$row5.term_id|escape}-->" /></li><!--{/if}-->
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
		<th style="vertical-align:middle;">商品メイン画像<br>横600px × 縦600px</th>
		<td>
			<!--{if isset($arr_input.thumbnail) && $arr_input.thumbnail!=""}-->
				<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.thumbnail|escape}-->&width=240&height=180" alt="" />
				<input type="hidden" name="hid_thumbnail" value="<!--{$arr_input.thumbnail|escape}-->" />
			<!--{/if}-->
		</td>
	</tr>
	
	<tr>
		<th colspan="4" id="bar_association_main_title">実施弁護士会</th>
	</tr>
	<!--{foreach from=$arr_bar_association key=bar_association_id item=val}-->
		<!--{if $bar_association_id == $login_bar_association_id}-->
			<tr>
				<td colspan="4" class="bar_association_title"><!--{$val.name|escape}--></td>
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
			<tr>
				<td>
					<!--{$branch.name|escape}-->
				</td>
				<td>
					定員<span style="color:red;">※</span>：<input type="text" name="<!--{$capacity}-->" value="<!--{$arr_input.$capacity|escape}-->" maxlength="4"/><br />
					会場<span style="color:red;">※</span>：<input type="text" name="<!--{$hall}-->" value="<!--{$arr_input.$hall|escape}-->" /><br />
					受付<span style="color:red;">※</span>：<input type="text" name="<!--{$receptionist_start_date}-->" id="<!--{$receptionist_start_date}-->" class="calendar" value="<!--{$arr_input.$receptionist_start_date|escape}-->" style="width:200px;" />～<input type="text" name="<!--{$receptionist_end_date}-->" id="<!--{$receptionist_end_date}-->" class="calendar" value="<!--{$arr_input.$receptionist_end_date|escape}-->" style="width:200px;" /><br />
					実施日<span style="color:red;">※</span>：<input type="text" name="<!--{$dates}-->" id="<!--{$dates}-->" class="calendar" value="<!--{$arr_input.$dates|escape}-->" /><br />
					<!--{if $disp_web_flg}-->
					Web申込<span style="color:red;">※</span>：<label><input type="radio" name="<!--{$web_flg}-->" value="1" <!--{if $arr_input.$web_flg==='1'}-->checked<!--{/if}--> />WEB申込可(研修を実施する)</label><label><input type="radio" name="<!--{$web_flg}-->" value="0" <!--{if $arr_input.$web_flg==='0'}-->checked<!--{/if}--> />WEB申込不可(研修を実施する)</label><label><input type="radio" name="<!--{$web_flg}-->" value="2" <!--{if $arr_input.$web_flg==='2'}-->checked<!--{/if}--> />研修を実施しない</label><br />
					<!--{/if}-->
					現状申込数：<!--{$arr_input.$entry_number|escape}--><br />
					備考：<textarea name="<!--{$contents}-->" style="width:400px;height:100px;"><!--{$arr_input.$contents|escape}--></textarea>
				</td>
			</tr>
			<!--{/foreach}-->
		<!--{/if}-->
	<!--{/foreach}-->
	
<!--{*
	<!--{section name=related_products loop=$section_related_products start=1}-->
	<tr>
		<th style="vertical-align:middle;">関連商品<!--{$smarty.section.related_products.index}--></th>
		<td>
			<!--{assign var=related_products_key value="related_products"|cat:$smarty.section.related_products.index}-->
			<!--{assign var=related_products_name_key value="related_products"|cat:$smarty.section.related_products.index|cat:"_name"}-->
			<span id="spa_<!--{$related_products_key}-->" ><!--{if $arr_input.$related_products_name_key!=""}--><!--{$arr_input.$related_products_name_key|escape}--><!--{else}-->未設定<!--{/if}--></span>
			<input type="hidden" name="<!--{$related_products_key}-->" id="hid_<!--{$related_products_key}-->" value="<!--{$arr_input.$related_products_key|escape}-->" />
			<input type="hidden" name="<!--{$related_products_name_key}-->" id="hid_<!--{$related_products_name_key}-->" value="<!--{$arr_input.$related_products_name_key|escape}-->" />
		</td>
	</tr>
	<!--{/section}-->
*}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'confirm');return false;" /><img src="/alfproduct/images/btn_confirm.png"></a>
</div>
</form>
<a name="page_bottom"></a>
