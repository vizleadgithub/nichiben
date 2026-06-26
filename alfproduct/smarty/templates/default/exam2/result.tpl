<style type="text/css">
.problem .problem_title{
padding:5px 0px;
}
.problem .problem_contents{
padding:10px 40px;
}
.problem .problem_contents .problem_content{
padding:20px 0px;
}
.problem .problem_contents .problem_content .content1{
width:5%;
float:left;
}
.problem .problem_contents .problem_content .content2{
width:95%;
float:right;
}
.problem_result{
color:blue;
text-align:center;
}
.problem_result div{
padding:5px 0px;
}
.problem_comment{
background-color:#ececec;
text-align:center;
}
.problem_comment div{
padding:5px 0px;
}


a.btn1{
display: block;
text-align: center;
vertical-align: middle;
background: #0097dd;
font-size: 16px;
line-height: 40px;
height: 40px;
color: #ffffff;
text-decoration: none;
border-radius: 8px;
width:200px;
float:left;
}
a.btn2{
display: block;
text-align: center;
vertical-align: middle;
background: #666666;
font-size: 16px;
line-height: 40px;
height: 40px;
color: #ffffff;
text-decoration: none;
border-radius: 8px;
width:200px;
float:right;
}
</style>
<form name="exam2Form" method="post" id="exam2Form">
	<div style="float:left;width:100%;height:36px;background-image: url( /img/lecture/h2_back.png );">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><!--{$arr_list.exam2_name|escape}--></span>
	</div>

	<div style="float:left;width:100%;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:100%;">
			<div style="text-align:left;padding:10px 10px 30px 10px;font-size:14px;">
				※修正する設問の解答を修正する場合は「解答を修正する」をクリックして下さい。<br>
			</div>
			
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
				<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
				<div class="problem" style="font-size: 14px;">
					<div class="problem_title">
						●設問<!--{$row_no}-->　<!--{$row.exam2_problem_name}-->
					</div>
					<div class="problem_contents">
						<!--{$row.problem_contents|escape|nl2br}-->
					</div>
					<div class="problem_contents">
						<!--{$row.problem_note|escape|nl2br}-->
					</div>
					<div class="problem_title">
						●解答
					</div>
					<div class="problem_contents">
						<!--{* 単一形式 *}-->
						<!--{if $row.answer_kind==1}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="radio" id="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->" name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if $row1.no==$exam2_answer[$row.exam2_problem_id].exam2_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->" name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($exam2_answer) && isset($exam2_answer[$row.exam2_problem_id]) && isset($exam2_answer[$row.exam2_problem_id].exam2_answer_contents) && array_search($row1.no, $exam2_answer[$row.exam2_problem_id].exam2_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" cols="60" rows="10"><!--{$exam2_answer[$row.exam2_problem_id].exam2_answer_contents[0]}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam2_problem_id[]" value="<!--{$row.exam2_problem_id}-->">
					</div>
				</div>
				<hr>
			<!--{/foreach}-->

			<div style="text-align:center;width:420px;margin-left:246px;">
				<a class="btn2" href="javascript:void(0)" onclick="pop_close_reload()">閉じる</a>
				<!--<a class="btn1" href="javascript:void(0)" onclick="pop_get_html('/exam2/resubmit_exec.php?e2id=<!--{$e2id}-->&pid=<!--{$pid}-->');">解答を修正する</a>-->
				<a class="btn1" href="javascript:void(0)" onclick="pop_get_html_sub('/exam2/resubmit_check.php?e2id=<!--{$e2id|escape:'javascript'}-->&pid=<!--{$pid|escape:'javascript'}-->','exam2Form');">解答を修正する</a>
			</div>
		</div>
	</div>
</form>
