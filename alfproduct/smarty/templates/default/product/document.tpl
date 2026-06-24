<div>
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">資料一括ダウンロード</div>
	</div>
<!--{if $product_list.all_contents_download != ''}-->
	<span style="padding-left:30px;"><!--{$product_list.all_contents_download_before|escape}--></span>
	<img src="/img/btn/btn_document_download.png" alt="ダウンロード" align="absmiddle" onclick="downloadAllFormSubmit()" style="cursor:pointer;padding-left:10px;" />
<!--{/if}-->
</div>

<div>
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">個別ダウンロード</div>
	</div>
	<div class="detail_btn">
		<ul>
		<!--{section name=contents_contents loop=$section_max_contents start=1}-->
		<!--{assign var=ccno value=$smarty.section.contents_contents.index}-->
		<!--{assign var=contents_contents_key value="contents_contents"|cat:$ccno}-->
				<!--{section name=contents_download loop=$section_max_contents_download start=1}-->
				<!--{assign var=cdno value=$smarty.section.contents_download.index}-->
				<!--{assign var=contents_download_key value="contents_download"|cat:$ccno|cat:"_"|cat:$cdno}-->
				<!--{assign var=contents_download_before_key value="contents_download_before"|cat:$ccno|cat:"_"|cat:$cdno}-->
					<!--{if $product_list.$contents_download_key!=''}-->
						<li><!--{$product_list.$contents_download_before_key|escape}--><img src="/img/btn/btn_document_download.png" alt="資料<!--{$cdno}-->ダウンロード" align="absmiddle" onclick="downloadFormSubmit('<!--{$contents_download_key}-->','<!--{$contents_download_before_key}-->')" style="cursor:pointer;padding-left:10px;" /></li>
					<!--{/if}-->
				<!--{/section}-->
		<!--{/section}-->
		</ul>
	</div>
</div>

<div style="text-align:center;padding-top:20px;">
	<input type="button" value="閉じる" onClick="window.close()" />
</div>

<style type="text/css">
div{
width:98% !important;
}
</style>

<!--{* 資料ダウンロードボタン用form *}-->
<form name="downloadForm" action="#" method="post">
<input type="hidden" name="pid" value="<!--{$pid}-->" />
<input type="hidden" name="cdname" id="hid_cdname" value="" />
<input type="hidden" name="cdname2" id="hid_cdname2" value="" />
</form>
<!--{* 一括資料ダウンロードボタン用form *}-->
<form name="downloadAllForm" action="#" method="post">
<input type="hidden" name="pid" value="<!--{$pid}-->" />
</form>

<script type="text/javascript">
function downloadFormSubmit(cdname, cdname2){
    document.getElementById("hid_cdname").value = cdname;
    document.getElementById("hid_cdname2").value = cdname2;
    document.downloadForm.method = "post";
    document.downloadForm.action = "download.php?PHPSESSID=<!--{php}-->echo session_id();<!--{/php}-->";
    document.downloadForm.submit();
}
function downloadAllFormSubmit(cdname, cdname2){
    document.downloadAllForm.method = "post";
    document.downloadAllForm.action = "download_all.php?PHPSESSID=<!--{php}-->echo session_id();<!--{/php}-->";
    document.downloadAllForm.submit();
}
</script>
