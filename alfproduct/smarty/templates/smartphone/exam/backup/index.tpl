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

<script type="text/javascript">
function examFormSubmit(eid,pid,ccno){
	window.open("about:blank","examDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
	document.examForm.target = "examDisp";
	document.examForm.method = "post";
	<!--{if $qid!=''}-->
		document.examForm.action = "/exam/answer_check.php?eid="+eid+"&pid="+pid+"&ccno="+ccno+"&qid="+<!--{$qid}-->;
	<!--{else}-->
		document.examForm.action = "/exam/answer_check.php?eid="+eid+"&pid="+pid+"&ccno="+ccno;
	<!--{/if}-->
	document.examForm.submit();
}
</script>

<form name="examForm" action="#" method="post">
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><!--{$arr_list.exam_name|escape}--></span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
			<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
				<div class="problem">
					<div class="problem_title">
						●設問<!--{$row_no}-->　<!--{$row.exam_problem_name}-->
					</div>
					<div class="problem_contents">
						<!--{$row.problem_contents|escape|nl2br}-->
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
									<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if $answered_list.$row_no.answer1==$row1.no}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row.answer_kind==2}-->
							<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
							<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" <!--{if array_search($row1.no, $answered_list.$row_no.answer2)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
									<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10" maxlength="1000"><!--{$answered_list.$row_no.answer3}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam_problem_id[]" value="<!--{$row.exam_problem_id}-->">
					</div>
				</div>
				<hr>
			<!--{/foreach}-->

			<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
			<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration+$row_no}-->
				<div class="problem">
					<div class="problem_title">
						●設問<!--{$row_no_q}-->　<!--{$row_q.exam_problem_name}-->
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
									<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if $answered_list_q.$row_no_q.answer1==$row1_q.no}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
									<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* 複数形式 *}-->
						<!--{elseif $row_q.answer_kind==2}-->
							<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
							<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" <!--{if array_search($row1_q.no, $answered_list_q.$row_no_q.answer2)!==false}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
									<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
								</div>
								<br style="clear:both;">
							<!--{/foreach}-->
							
						<!--{* フリー解答 *}-->
						<!--{elseif $row_q.answer_kind==3}-->
							<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10" maxlength="1000"><!--{$answered_list_q.$row_no_q.answer3}--></textarea></div>
							
						<!--{else}-->
							未設定
							
						<!--{/if}-->
						<input type="hidden" name="exam_problem_id_q[]" value="<!--{$row_q.exam_problem_id}-->">
					</div>
				</div>
				<hr>
			<!--{/foreach}-->
			
			<div style="text-align:center;padding:20px;">
				<a class="btn" href="javascript:void(0)" onclick="examFormSubmit(<!--{$arr_list.exam_id}-->,<!--{$pid}-->,<!--{$ccno}-->)">次へ</a>
			</div>

		</div>
	</div>
</div>
</form>
