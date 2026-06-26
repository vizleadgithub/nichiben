<style type="text/css">
.problem .problem_title{
padding:10px 0px;
}
.problem .problem_contents{
padding:0px 40px 20px 40px;
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
a.btn{
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
width:250px;
margin-left:310px;
}
</style>
<form name="exam2Form" method="post" id="exam2Form">
	<div style="float:left;width:100%;height:36px;background-image: url( /img/lecture/h2_back.png );">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><!--{$arr_list.exam2_name|escape}--></span>
	</div>

	<div style="float:left;width:100%;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:100%;">
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
									<div class="content1"><input type="radio" id="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->" name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if $answered_list.$row_no.answer1==$row1.no}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->" name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($answered_list) && isset($answered_list.$row_no.answer2) && array_search($row1.no, $answered_list.$row_no.answer2)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam2_problem_<!--{$row.exam2_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam2_problem_<!--{$row.exam2_problem_id}-->[]" cols="60" rows="10" maxlength="1000"><!--{$answered_list.$row_no.answer3}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam2_problem_id[]" value="<!--{$row.exam2_problem_id}-->">
					</div>
				</div>
				<hr>
			<!--{/foreach}-->
			
			<div style="text-align:center;width:100%;display:block;height:auto;">
				<div style="text-align:center;margin:0 auto;width:520px;display:block;height:auto;">
					<a style="float:left; margin-left:0;    margin-right:10px;" class="btn" href="javascript:void(0)" onclick="pop_get_html_sub('/exam2/answer_check.php?e2id=<!--{$arr_list.exam2_id|escape:'javascript'}-->&pid=<!--{$pid|escape:'javascript'}-->','exam2Form')">次へ</a>
					<a style="float:right;margin-left:10px; margin-right:0;" class="btn" href="javascript:void(0)" onclick="pop_close()">閉じる</a>
				</div>
			</div>

		</div>
	</div>
</form>
