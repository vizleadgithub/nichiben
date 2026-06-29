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
width:200px;
margin-left:336px;
}
</style>

<script type="text/javascript">
function examFormSubmit(flg){
	if(flg=='exec'){
		<!--{if $eflg=='1'}-->
			document.examForm.action = "/exam/answer_check1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}-->&eno=<!--{$eno}-->&eflg=<!--{$eflg}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->";
		<!--{else}-->
			document.examForm.action = "/exam/answer_check1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}-->&eno=<!--{$eno}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->";
		<!--{/if}-->
	} else {
		document.examForm.action = "/product/detail.php?pid=<!--{$pid}-->";
	}
	document.examForm.submit();
}
</script>

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
			
			(<!--{$eno_max}-->問中<!--{$eno}-->問目)
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
									<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($answered_info) && isset($answered_info.exam_answer_contents) && $row1.no==$answered_info.exam_answer_contents}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($answered_info) && isset($answered_info.arr_exam_answer_contents) && array_search($row1.no, $answered_info.arr_exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10" maxlength="1000"><!--{$answered_info.exam_answer_contents}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam_problem_id[]" value="<!--{$row.exam_problem_id}-->">
					</div>
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
										<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if isset($answered_info) && isset($answered_info.arr_exam_answer_contents) && $row1_q.no==$answered_info.exam_answer_contents}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* 複数形式 *}-->
							<!--{elseif $row_q.answer_kind==2}-->
								<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
								<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
									<div class="problem_content">
										<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if isset($answered_info) && isset($answered_info.arr_exam_answer_contents) && array_search($row1_q.no, $answered_info.arr_exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row_q.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10" maxlength="1000"><!--{$answered_info.exam_answer_contents}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
							<input type="hidden" name="exam_problem_id_q[]" value="<!--{$row_q.exam_problem_id}-->">
						</div>
					</div>
				<!--{/if}-->
				<!--{/foreach}-->
			<!--{/if}-->
			
			<!--{if $eflg=='1'}-->
				<div style="text-align:center;width:420px;margin-left:246px;">
					<!--<a class="btn" href="javascript:void(0)" onclick="javascript:location.href='/exam/confirm1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}-->';" style="margin-left:0px;float:left;background: #666666;">戻る</a>-->
					<input class="btn2" type="button" onclick="javascript:location.href='/exam/confirm1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}-->';" value="戻る" style="display: block;text-align: center;vertical-align: middle;background: #666666;font-size: 16px;line-height: 40px;height: 40px;color: #ffffff;text-decoration: none;border-radius: 8px;width: 200px;float: left;border: none;">
					<a class="btn" href="javascript:void(0)" onclick="examFormSubmit('exec');" style="margin-left:0px;float:right;"><!--{if $btn_type=='2'}-->回答する<!--{else}-->解答を修正する<!--{/if}--></a>
				</div>
			<!--{else}-->
				<div style="text-align:center;padding:20px;">
					<a class="btn" href="javascript:void(0)" onclick="examFormSubmit('exec');"><!--{if $btn_type=='2'}-->回答する<!--{else}-->解答する<!--{/if}--></a>
				</div>
			<!--{/if}-->

		</div>
	</div>
</div>
</form>
