<!--{$pankuzu}-->

<!--{if $exp_date_passport_flg==0}-->
	<div class="nonProductMsg">
	既に研修パスポートを所持しています。
	</div>
	
<!--{else}-->
	
	<!--{if !empty($arr_list)}-->
		<div class="pager">
			<div style="text-align:left; float:left;width:240px;">
				<!--{if $page_max==0}-->
					全<!--{$all_count|escape}-->件
				<!--{else}-->
					<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
				<!--{/if}-->
			</div>
			<div style="text-align:right; float:right;width:240px;">
				<!--{$pager}-->
			</div>
		</div>



	<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;">
		<h2>パスポート</h2>
		<div class="selects">
			<form name="form_selects">
				<script type="text/javascript">
				<!--
				function sort_exe(){
					location.href = "" + "?pcid=<!--{$pcid}-->&pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
				}
				// -->
				</script>
				<span style="position:relative;bottom:5px;right:10px;">
				<!--{html_options name=sort options=$sort_select selected=$sort}-->に
				<select name="pagemax">
					<option value="5"<!--{if $page_max==5}--> selected=selected<!--{/if}-->>5</option>
					<option value="10"<!--{if $page_max==10}--> selected=selected<!--{/if}-->>10</option>
					<option value="15"<!--{if $page_max==15}--> selected=selected<!--{/if}-->>15</option>
					<option value="20"<!--{if $page_max==20}--> selected=selected<!--{/if}-->>20</option>
					<option value="0"<!--{if $page_max==0}--> selected=selected<!--{/if}-->>すべて</option>
				</select>件ずつ
				<a href="javascript:void(0);" onClick="sort_exe()"><img src="/img/list/permutation_btn.png" alt="並替え" style="position:relative;top:8px;" /></a>
				</span>
			</form>
		</div>
	</div>







		<!--{foreach from=$arr_list item="row"}-->
		<div class="list_box" style="width:468px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="float:left;width:160px;height:115px;text-align:center;background-color:#99e5fd;padding:0;" class="thumb">
				<!--{if $row.thumbnail != ""}-->
					<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=<!--{$row.thumbnail}-->&width=155&height=85" alt="" style="margin-top:5px;" /></a>
				<!--{else}-->
					<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="margin-top:5px;" /></a>
				<!--{/if}-->
			</div>
			
			<div style="text-align:left;margin-left:180px;">
				<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:#523d26;font-size:14px;font-weight:bold;"><!--{$row.product_name|escape}--></a>
			</div>
			
			<div style="float:right;width:290px">
				<table>
					<tr style="background-color:#f5f8ef;">
						<th style="width:60px;text-align:left;vertical-align:top;color:#492323;">単品価格</th>
						<td style="text-align:left;vertical-align:top;width:210px;"><!--{if $row.price_intax==0}-->無料<!--{else}--><!--{$row.price_intax|escape|number_format}-->円(税込)<!--{/if}--></td>
					</tr>
					<tr>
						<td colspan="2" style="height:70px;">&nbsp;</td>
					</tr>
				</table>
				<div style="float:right;width:460px;padding:5px;">
					<div style="text-align:left;width:100%;">
						<!--{$row.memo|escape|nl2br}-->
					</div>
					<div style="text-align:right;width:100%;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				</div>
			</div>
		</div>
		<!--{/foreach}-->
		


		<div class="pager">
			<div style="text-align:left; float:left;width:240px;">
				<!--{if $page_max==0}-->
					全<!--{$all_count|escape}-->件
				<!--{else}-->
					<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
				<!--{/if}-->
			</div>
			<div style="text-align:right; float:right;width:240px;">
				<!--{$pager}-->
			</div>
		</div>
	<!--{else}-->
		<div class="nonProductMsg">
		該当パスポートが登録されていません。
		</div>
	<!--{/if}-->
	
<!--{/if}-->
