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
input.btn1{
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
float:right;
border:none;
}
input.btn2{
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
float:left;
border:none;
}
</style>

<script type="text/javascript">
function examFormSubmit(flg){
	if(flg=='prev'){
		document.examForm.action = "/product/detail.php?pid=<!--{$pid}-->";
	} else if(flg=='exec'){
		document.examForm.action = "/exam/resubmit_exec.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->";
	} else {
		document.examForm.action = "/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->";
	}
	document.examForm.submit();
}
</script>

<form name="examForm" action="#" method="post">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<!--{* <!--{if !$question_flg}--><span style="font-size:14px;color:red;font-weight: bold;padding-left: 10px;">採点結果：<!--{$total_score}-->&nbsp;&frasl;&nbsp;<!--{$exam_total_score}-->&nbsp;点</span><!--{/if}--> *}-->
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<!--{if $hantei_ari && !$passing_flg}-->
				<div style="text-align:left;padding:10px 10px 30px 10px;font-size:14px;">
※本講座は，テストに全問正解しないと次のパートに進むことができません。<br>
※不正解と表示された設問の解答を修正して下さい（修正する設問の解答を修正→「解答を修正する」をクリック）。<br>
				</div>
			<!--{/if}-->
			
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
			<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
				<div class="problem">
					<div class="problem_title">
						●設問<!--{$row_no}-->　<!--{$row.exam_problem_name|escape}-->
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
									<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($exam_answer) && isset($exam_answer[$row.exam_problem_id].exam_answer_contents) && $row1.no==$exam_answer[$row.exam_problem_id].exam_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if isset($exam_answer) && isset($exam_answer[$row.exam_problem_id].exam_answer_contents) && array_search($row1.no, $exam_answer[$row.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10"><!--{$exam_answer[$row.exam_problem_id].exam_answer_contents[0]}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam_problem_id[]" value="<!--{$row.exam_problem_id}-->">
					</div>
					<!--{if !$question_flg}-->
						<!--{if $row.answer_kind==1 || $row.answer_kind==2}-->
							<div class="problem_title">
								●採点結果
							</div>
							<div class="problem_contents problem_result">
								<div><!--{if $exam_answer[$row.exam_problem_id].exam_answer_mark==1}-->正解<!--{else}-->不正解<!--{/if}--></div>
								<!--{if $exam_answer[$row.exam_problem_id].exam_answer_mark==1 || !$hantei_ari}-->
									<div>正解は「<!--{$exam_answer[$row.exam_problem_id].correct_answer_str}-->」、あなたの解答は「<!--{$exam_answer[$row.exam_problem_id].exam_answer_contents_str}-->」</div>
								<!--{/if}-->
							</div>
						<!--{/if}-->
						
						<!--{if $row.answer_explain_kind==1}-->
							<!--{if $exam_answer[$row.exam_problem_id].exam_answer_mark==1 || !$hantei_ari}-->
								<div class="problem_contents problem_comment">
									<div>解説</div>
									<div style="text-align:left;"><!--{$row.answer_explain_contents|escape|nl2br}--></div>
									<div style="text-align:left;"><!--{$row.answer_explain_note|escape|nl2br}--></div>
								</div>
							<!--{/if}-->
						<!--{/if}-->
					<!--{/if}-->
				</div>
				<hr>
			<!--{/foreach}-->
			
			<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
			<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration}-->
				<div class="problem">
					<div class="problem_title">
						●設問<!--{$row_no_q+$row_no}-->　<!--{$row_q.exam_problem_name|escape}-->
					</div>
					<div class="problem_contents">
						<!--{$row_q.problem_contents|escape|nl2br}-->
					</div>
					<div class="problem_title">
						●解答
					</div>
					<div class="problem_contents">
						<!--{* 単一形式 *}-->
						<!--{if $row_q.answer_kind==1}-->
							<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
							<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if isset($exam_answer_q) && isset($exam_answer_q[$row_q.exam_problem_id].exam_answer_contents) && $row1_q.no==$exam_answer_q[$row_q.exam_problem_id].exam_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
									<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row_q.answer_kind==2}-->
							<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
							<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if isset($exam_answer_q) && isset($exam_answer_q[$row_q.exam_problem_id].exam_answer_contents) && array_search($row1_q.no, $exam_answer_q[$row_q.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
									<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row_q.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10"><!--{$exam_answer_q[$row_q.exam_problem_id].exam_answer_contents[0]}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam_problem_id_q[]" value="<!--{$row_q.exam_problem_id}-->">
					</div>
				</div>
				<hr>
			<!--{/foreach}-->
			
			<div style="text-align:center;width:420px;margin-left:246px;">
				<input class="btn2" type="button" onclick="examFormSubmit('prev');" value="終了する">
				<input class="btn1" type="button" onclick="examFormSubmit('exec');" value="<!--{if $btn_type=='2'}-->回答を修正する<!--{else}-->解答を修正する<!--{/if}-->">
			</div>
		</div>
	</div>
</div>
</form>
