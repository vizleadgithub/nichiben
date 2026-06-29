<style type="text/css">
.problem .problem_contents{
padding:10px 0px;
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
margin-left:30px;
}
.problem_comment{
background-color:#ececec;
text-align:center;
margin-left:30px;
}
.problem_comment div{
padding:5px 0px;
}
a.btn{
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
margin-left:336px;
}
</style>

<form name="examForm" action="#" method="post">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
			<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
			<!--{if $row_no==$eno}-->
				<!--{$row.exam_problem_name}-->
			<!--{/if}-->
			<!--{/foreach}-->
			
			<!--{if $eno>$eno_max_test}-->
				<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
				<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration}-->
				<!--{if $row_no_q==($eno-$eno_max_test)}-->
					<!--{$row_q.exam_problem_name}-->
				<!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
			
			&nbsp;(<!--{$eno_max}-->問中<!--{$eno}-->問目)&nbsp;解答
		</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
			<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
			<!--{if $row_no==$eno}-->
				<div class="problem">
					<div class="problem_contents">
						<!--{$row.problem_contents|escape|nl2br}-->
					</div>
					<div class="problem_contents">
						<!--{$row.problem_note|escape|nl2br}-->
					</div>
					<div class="problem_contents">
						<!--{* 単一形式 *}-->
						<!--{if $row.answer_kind==1}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled <!--{if isset($answered_info) && isset($answered_info[$row.exam_problem_id].exam_answer_contents) && $row1.no==$answered_info[$row.exam_problem_id].exam_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled <!--{if issset($answered_info) && isset($answered_info[$row.exam_problem_id].exam_answer_contents) && array_search($row1.no, $answered_info[$row.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info[$row.exam_problem_id].exam_answer_contents[0]}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
					</div>
					<!--{if $row.answer_kind==1 || $row.answer_kind==2}-->
						<div class="problem_contents problem_result">
							<div><!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1}-->正解<!--{else}-->不正解<!--{/if}--></div>
							<!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1}-->
								<div>正解は「<!--{$answered_info[$row.exam_problem_id].correct_answer_str}-->」、あなたの解答は「<!--{$answered_info[$row.exam_problem_id].exam_answer_contents_str}-->」</div>
							<!--{/if}-->
						</div>
					<!--{/if}-->
					
					<!--{if $row.answer_explain_kind==1 && $answered_info[$row.exam_problem_id].exam_answer_mark==1}-->
						<div class="problem_contents problem_comment">
							<div>解説</div>
							<div style="text-align:left;"><!--{$row.answer_explain_contents|escape|nl2br}--></div>
							<div style="text-align:left;"><!--{$row.answer_explain_note|escape|nl2br}--></div>
						</div>
					<!--{/if}-->
				</div>
			<!--{/if}-->
			<!--{/foreach}-->
			
			<!--{if $eno>$eno_max_test}-->
				<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
				<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration}-->
				<!--{if $row_no_q==($eno-$eno_max_test)}-->
					<div class="problem">
						<div class="problem_contents">
							<!--{$row_q.problem_contents|escape|nl2br}-->
						</div>
						<div class="problem_contents">
							<!--{$row_q.problem_note|escape|nl2br}-->
						</div>
						<div class="problem_contents">
							<!--{* 単一形式 *}-->
							<!--{if $row_q.answer_kind==1}-->
								<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
								<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
									<div class="problem_content">
										<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled <!--{if isset($answered_info) && isset($answered_info[$row_q.exam_problem_id].exam_answer_contents) && $row1_q.no==$answered_info[$row_q.exam_problem_id].exam_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* 複数形式 *}-->
							<!--{elseif $row_q.answer_kind==2}-->
								<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
								<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
									<div class="problem_content">
										<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled <!--{if isset($answered_info) && isset($answered_info[$row_q.exam_problem_id].exam_answer_contents) && array_search($row1_q.no, $answered_info[$row_q.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row_q.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info[$row_q.exam_problem_id].exam_answer_contents[0]}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
						</div>
					</div>
				<!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
			
			<div style="text-align:center;padding:20px;">
				<a class="btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->">戻る</a>
			</div>
		</div>
	</div>
</div>
</form>
