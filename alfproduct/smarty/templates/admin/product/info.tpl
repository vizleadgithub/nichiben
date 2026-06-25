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
function exam2Button(formAct){
	window.open(formAct, "", "scrollbars=yes,width=1300,height=980");
}
</script>

<h2>商品の内容を確認</h2>

<form name="form1" action="#" method="post">
	<input type="hidden" name="mid" id="mid" value="<!--{$mid}-->" />
	<input type="hidden" name="act" id="act" value="" />

	<!--{foreach from=$arr_input item=item key=key}-->
		<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
	<!--{/foreach}-->

	<!--{foreach from=$arr_term_id item=item}-->
		<input type="hidden" name="arr_term_id[]" value="<!--{$item|escape}-->" />
	<!--{/foreach}-->

	<!--{foreach from=$arr_input.product_flg item=item}-->
		<input type="hidden" name="product_flg[]" value="<!--{$item|escape}-->" />
	<!--{/foreach}-->

	<!--{foreach from=$arr_input.product_disp_warning_word item=item}-->
		<input type="hidden" name="product_disp_warning_word[]" value="<!--{$item|escape}-->" />
	<!--{/foreach}-->

<!--
	<!--{section name=disp_warning_word_loop loop=$smarty.const.MAX_CONTENTS+1 start=1}-->
		<!--{assign var=disp_warning_word_key value="disp_warning_word"|cat:$smarty.section.disp_warning_word_loop.index}-->
		<!--{if $arr_input.$disp_warning_word_key!=''}-->
			<!--{foreach from=$arr_input.$disp_warning_word_key item=item}-->
			<input type="hidden" name="<!--{$disp_warning_word_key}-->[]" value="<!--{$item|escape}-->" />
			<!--{/foreach}-->
		<!--{else}-->
			<input type="hidden" name="<!--{$disp_warning_word_key}-->[]" value="" />
		<!--{/if}-->
	<!--{/section}-->
-->

<table class="form">
	<tr>
		<th style="vertical-align:middle;width:200px;">商品ID</th>
		<td><!--{$mid|escape}--></td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">商品種別</th>
		<td>
			<!--{if $arr_input.product_type=='1'}-->会員専用<!--{/if}-->
			<!--{if $arr_input.product_type=='2'}-->一般公開<!--{/if}-->
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">商品種別</th>
		<td>
			<!--{if $arr_input.product_kind_flg=='0'}-->その他<!--{/if}-->
			<!--{if $arr_input.product_kind_flg=='1'}-->eラーニング<!--{/if}-->
			<!--{if $arr_input.product_kind_flg=='2'}-->e-ライブ<br /><!--{$arr_input.live_training_product_name|escape}--><!--{/if}-->
			<!--{if $arr_input.product_kind_flg=='3'}-->設問付きeラーニング<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品名</th>
		<td><!--{$arr_input.product_name|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品コード</th>
		<td><!--{$arr_input.product_code|escape}--></td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品価格(税込)</th>
		<td><!--{$arr_input.price|escape}--></td>
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
<!--{*
	<tr>
		<th style="vertical-align:middle;">割引コード</th>
		<td>
			<!--{if $arr_input.discount_code==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.discount_code|escape}-->
			<!--{/if}-->
		</td>
	</tr>
*}-->
	<tr>
		<th style="vertical-align:middle;">公開期間</th>
		<td>
			<!--{if $arr_input.start_date!='' && $arr_input.end_date!=''}-->
				<!--{$arr_input.start_date|escape}-->～<!--{$arr_input.end_date|escape}-->
			<!--{elseif $arr_input.start_date!=''}-->
				<!--{$arr_input.start_date|escape}-->
			<!--{elseif $arr_input.end_date!=''}-->
				<!--{$arr_input.end_date|escape}-->
			<!--{else}-->
				未設定
			<!--{/if}-->
		</td>
	</tr>
<!--{*
	<tr>
		<th style="vertical-align:middle;">購入後公開期間日数</th>
		<td>
			<!--{if $arr_input.open_period=='0'}-->
				無制限に公開
			<!--{else}-->
				<!--{$arr_input.open_period|escape}-->日
			<!--{/if}-->
		</td>
	</tr>
*}-->
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
	<tr>
		<th style="vertical-align:middle;">商品説明</th>
		<td>
			<!--{if $arr_input.memo==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.memo|escape|nl2br}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">商品再生時間</th>
		<td>
			<!--{if $arr_input.play_time==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.play_time|escape}-->
			<!--{/if}-->
		</td>
	</tr>

	<tr>
		<th style="vertical-align:middle;">商品講師名</th>
		<td>
			<!--{if $arr_input.teacher==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.teacher|escape}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師受講者</th>
		<td>
			<!--{if $arr_input.teacher_student_id==0}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.teacher_student_name|escape}-->
			<!--{/if}-->
		</td>
	</tr>

	<tr>
		<th style="vertical-align:middle;">フラグ管理</th>
		<td>
			<!--{if $arr_input.product_flg != ""}-->
			<!--{foreach from=$arr_input.product_flg item="val"}-->
				・<!--{$arr_product_flg.$val}--><br />
			<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">注意文言の掲載箇所</th>
		<td>
			<!--{if $arr_input.product_disp_warning_word != ""}-->
			<!--{foreach from=$arr_input.product_disp_warning_word item="val"}-->
				・<!--{$arr_product_disp_warning_word.$val}--><br />
			<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">検索ワード</th>
		<td>
			<!--{$arr_input.search_word|escape|nl2br}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">一括ダウンロード用資料</th>
		<td>
			<!--{$arr_input.all_contents_download_before|escape}-->
		</td>
	</tr>

<!--{section name=contents_loop loop=$section_contents start=1}-->
<!--{* 必須項目で表示・非表示切り替え *}-->
<!--{assign var=contents_thumbnail_key value="contents_thumbnail"|cat:$smarty.section.contents_loop.index}-->
<!--{assign var=contents_contents_name_key value="contents_contents"|cat:$smarty.section.contents_loop.index|cat:"_name"}-->
<!--{assign var=contents_contents_so_name_key value="contents_contents"|cat:$smarty.section.contents_loop.index|cat:"so_name"}-->
<!--{assign var=contents_free_time_key value="contents_free_time"|cat:$smarty.section.contents_loop.index}-->
<!--{*if $arr_input.$contents_thumbnail_key!='' && $arr_input.$contents_contents_name_key!='' && $arr_input.$contents_free_time_key!=''*}-->
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->サムネイル画像</th>
		<td>
			<img src="/alfproduct/resize_image.php?image=<!--{$arr_input.$contents_thumbnail_key|escape}-->&width=240&height=180" alt="" />
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->コンテンツ</th>
		<td>
			<!--{$arr_input.$contents_contents_name_key|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->音声コンテンツ</th>
		<td>
			<!--{$arr_input.$contents_contents_so_name_key|escape}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->無料公開範囲(秒)</th>
		<td>
		<!--{if $arr_input.$contents_free_time_key=='0'}-->
			無料部分なし
		<!--{else}-->
			<!--{$arr_input.$contents_free_time_key|escape}-->
		<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ<!--{$smarty.section.contents_loop.index}-->公開期間</th>
		<td>
			<!--{assign var=contents_start_date_key value="contents_start_date"|cat:$smarty.section.contents_loop.index}-->
			<!--{assign var=contents_end_date_key value="contents_end_date"|cat:$smarty.section.contents_loop.index}-->
			
			
			<!--{if $arr_input.$contents_start_date_key!='' && $arr_input.$contents_end_date_key!=''}-->
				<!--{$arr_input.$contents_start_date_key|escape}-->～<!--{$arr_input.$contents_end_date_key|escape}-->
			<!--{elseif $arr_input.$contents_start_date_key!=''}-->
				<!--{$arr_input.$contents_start_date_key|escape}-->
			<!--{elseif $arr_input.$contents_end_date_key!=''}-->
				<!--{$arr_input.$contents_end_date_key|escape}-->
			<!--{else}-->
				未設定
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">コンテンツ概要<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=contents_memo_key value="contents_memo"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$contents_memo_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$contents_memo_key|escape}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">講師名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=contents_teacher_key value="contents_teacher"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$contents_teacher_key==''}-->
				未設定
			<!--{else}-->
				<!--{$arr_input.$contents_teacher_key|escape}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問題(テスト)名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=exam_id_test_key value="exam_id_test"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$exam_id_test_key=='' || $arr_input.$exam_id_test_key<=0}-->
				未設定
			<!--{else}-->
				<!--{foreach from=$exam_list item=exam}-->
					<!--{if $arr_input.$exam_id_test_key==$exam.exam_id}--><!--{$exam.exam_name}--><!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">問題(アンケート)名<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=exam_id_question_key value="exam_id_question"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$exam_id_question_key=='' || $arr_input.$exam_id_question_key<=0}-->
				未設定
			<!--{else}-->
				<!--{foreach from=$exam_list item=exam}-->
					<!--{if $arr_input.$exam_id_question_key==$exam.exam_id}--><!--{$exam.exam_name}--><!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">ボタンの選択<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=btn_type_key value="btn_type"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$btn_type_key=='' || $arr_input.$btn_type_key<=0}-->
				未設定
			<!--{else}-->
				<!--{foreach from=$btn_type_list item=btn_type_name key=btn_type_id}-->
					<!--{if $arr_input.$btn_type_key==$btn_type_id}--><!--{$btn_type_name}--><!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle;">注意文言の掲載箇所<!--{$smarty.section.contents_loop.index}--></th>
		<td>
			<!--{assign var=disp_warning_word_key value="disp_warning_word"|cat:$smarty.section.contents_loop.index}-->
			<!--{if $arr_input.$disp_warning_word_key==''}-->
				未設定
			<!--{else}-->
				<!--{foreach from=$arr_input.$disp_warning_word_key item=row}-->
					<!--{if $row=='1'}-->
						商品説明下&nbsp;
					<!--{elseif $row=='2'}-->
						商品資料下&nbsp;
					<!--{elseif $row=='3'}-->
						テストボタン右&nbsp;
					<!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="#page_bottom">ページの下へ</a>
		</td>
	</tr>
	<!--{section name=contents_download loop=$section_contents_download start=1}-->
		<tr<!--{if $smarty.section.contents_download.index % 2 != 0}--> style="background: none repeat scroll 0% 0% rgb(246, 246, 243);"<!--{/if}-->>
			<th style="vertical-align:middle;">ダウンロード<!--{$smarty.section.contents_loop.index}-->-<!--{$smarty.section.contents_download.index}--></th>
			<td>
				<!--{assign var=contents_download_key value="contents_download"|cat:$smarty.section.contents_loop.index|cat:"_"|cat:$smarty.section.contents_download.index}-->
				<!--{assign var=contents_download_before_key value="contents_download_before"|cat:$smarty.section.contents_loop.index|cat:"_"|cat:$smarty.section.contents_download.index}-->
				<!--{if $arr_input.$contents_download_key==''}-->
					未設定
				<!--{else}-->
					<!--{$arr_input.$contents_download_before_key|escape}-->
					<input type="button" value="ダウンロード" onclick="var w=window.open();w.location.href='<!--{$document_path}--><!--{$arr_input.$contents_download_key|escape}-->'" />
				<!--{/if}-->
			</td>
		</tr>
	<!--{/section}-->
<!--{*/if*}-->
<!--{/section}-->
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
	<tr>
		<th style="vertical-align:middle;">アンケート</th>
		<td>
			<!--{if $arr_input.exam2_id=='' || $arr_input.exam2_id<=0}-->
				未設定
			<!--{else}-->
				<!--{foreach from=$exam2_list item=exam2}-->
					<!--{if $arr_input.exam2_id==$exam2.exam2_id}--><!--{$exam2.exam2_name}--><!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
		</td>
	</tr>
	<!--{if $arr_input.exam2_id=='' || $arr_input.exam2_id<=0}-->
	<!--{else}-->
		<tr>
			<th style="vertical-align:middle;"></th>
			<td>
				<a href="javascript:void(0);" onclick="exam2Button('/cms_exam2/exam2_set_list?product_id=<!--{$mid}-->&exam2_id=<!--{$arr_input.exam2_id|escape}-->');">アンケートのレビュー表示</a>
			</td>
		</tr>
	<!--{/if}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'add.php', 'edit');return false;" /><img src="/alfproduct/images/btn_revise.png"></a>
	<a href="javascript:void(0);" onclick="formSubmit('form1', 'info.php', 'delete');return false;" /><img src="/alfproduct/images/btn_delete.png"></a>
</div>
</form>
<a name="page_bottom"></a>
