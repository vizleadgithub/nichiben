<style type="text/css">
body{
font-size:14px;
}
h1{
font-size:16px;
}
tr,th,td{
text-align:left;
}
.btn_area{
text-align:center;
padding:20px 0;
}
</style>

<h1>研修動画の視聴状況一覧</h1>
<table>
	<tr>
		<th style="width:80%;">ファイル名</th>
		<th style="width:20%;">視聴状況</th>
	</tr>
	<!--{section name=contents_contents loop=$smarty.const.MAX_CONTENTS}-->
	<!--{assign var=ccno value=$smarty.section.contents_contents.iteration}-->
		<!--{if $arr_list.$ccno.video_id!=''}-->
		<tr>
			<td><!--{$arr_list.$ccno.video_name|escape}--></td>
			<td><!--{$arr_list.$ccno.video_percent|escape}-->%</td>
		</tr>
		<!--{/if}-->
	<!--{/section}-->
</table>
<div class="btn_area">
	<a href="javascript:void(0)" onclick="window.close();">閉じる</a>
</div>
