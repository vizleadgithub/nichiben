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

<h1>設問の実施状況一覧</h1>
<table>
	<tr>
		<th style="width:60%;">問題名</th>
		<th style="width:20%;">解答状況</th>
		<th style="width:20%;">合否</th>
	</tr>
	<!--{section name=contents_contents loop=$smarty.const.MAX_CONTENTS}-->
	<!--{assign var=ccno value=$smarty.section.contents_contents.iteration}-->
		<!--{if $arr_list.$ccno.exam_name!=''}-->
		<tr>
			<td><!--{$arr_list.$ccno.exam_name|escape}--></td>
			<td><!--{$arr_list.$ccno.exam_problem_answer_count|escape}-->/<!--{$arr_list.$ccno.exam_problem_count|escape}--></td>
			<td><!--{$arr_list.$ccno.gouhi|escape}--></td>
		</tr>
		<!--{/if}-->
	<!--{/section}-->
</table>
<div class="btn_area">
	<a href="javascript:void(0)" onclick="window.close();">閉じる</a>
</div>
