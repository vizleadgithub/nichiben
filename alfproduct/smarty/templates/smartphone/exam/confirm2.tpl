<style type="text/css">
.problem .problem_contents_parent{
padding:20px;
clear:both;
display:none;
background-color:#ffffff;
border:dashed 1px #000000;
}
.problem .problem_contents{
padding:10px 0px;
clear:both;
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
.btn_area{
display:none;
text-align:center;
padding:10px;
}
.problem .problem_contents_title{
padding:10px 20px;
clear:both;
position:relative;
background-color:#eeeeee;
padding-right:160px;
word-break:break-all;
font-size:14px;
}
.problem .problem_contents_title .confbtn{
position:absolute;
top:0px;
right:0px;
background-color:#0097dd;
font-size:14px;
width:80px;
height:100%;
text-align:center;
line-height:40px;
}
.problem .problem_contents_title .confbtn a{
color:#ffffff;
text-decoration:none;
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
a.btn_edit{
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
margin-left:350px;
}
</style>

<script type="text/javascript">
function confirmDisp(epid){
	if ($('#problem_contents_'+epid).css('display')=='block') {
		$('#problem_contents_'+epid).hide();
		$('#btn_area_'+epid).hide();
	} else {
		$('#problem_contents_'+epid).show();
		$('#btn_area_'+epid).show();
	}
}
function examFormSubmit(eid,pid,ccno){
	window.open("about:blank","examDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
	document.examForm.target = "examDisp";
	document.examForm.method = "post";
	document.examForm.action = "/exam/answer_check2.php?eid="+eid+"&pid="+pid+"&ccno="+ccno<!--{if $qid!=''}-->+"&qid=<!--{$qid}-->"<!--{/if}-->;
	document.examForm.submit();
}
</script>

<form name="examForm" action="#" method="post">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><!--{$arr_list.exam_name|escape}--></span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<!--{foreach name=loop from=$arr_list.problem item="row" key="key"}-->
			<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
				<div class="problem">
					<div class="problem_contents_title">
						<!--{$row.exam_problem_name|escape|nl2br}-->
						<div class="confbtn"><a href="javascript:void(0)" onclick="confirmDisp(<!--{$row.exam_problem_id}-->);">確認する</a></div>
					</div>
					<div id="problem_contents_<!--{$row.exam_problem_id}-->" class="problem_contents_parent">
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
									<!--{if $row1.no==$answered_info[$row.exam_problem_id].arr_exam_answer_contents[0]}-->
										<div class="problem_content">
											<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled checked><!--{$row_no1}-->.</div>
											<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{else}-->
										<div class="problem_content">
											<div class="content1"><input type="radio" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled><!--{$row_no1}-->.</div>
											<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{/if}-->
								<!--{/foreach}-->
								
							<!--{* 複数形式 *}-->
							<!--{elseif $row.answer_kind==2}-->
								<!--{foreach name=loop1 from=$row.answer_contents_select.answer_contents item="row1" key="key1"}-->
								<!--{assign var=row_no1 value=$smarty.foreach.loop1.iteration}-->
									<!--{if isset($answered_info) && isset($answered_info[$row.exam_problem_id].arr_exam_answer_contents) && array_search($row1.no, $answered_info[$row.exam_problem_id].arr_exam_answer_contents)!==false}-->
										<div class="problem_content">
											<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled checked><!--{$row_no1}-->.</div>
											<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{else}-->
										<div class="problem_content">
											<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled><!--{$row_no1}-->.</div>
											<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{/if}-->
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info[$row.exam_problem_id].arr_exam_answer_contents[0]}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
						</div>
					</div>
					
					<div id="btn_area_<!--{$row.exam_problem_id}-->" class="btn_area">
						<a class="btn_edit" href="/exam/index2.php?pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}-->&eid=<!--{$eid|escape}-->&eno=<!--{$row_no}-->&eflg=1<!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->"><!--{if $btn_type=='2'}-->回答修正画面へ<!--{else}-->解答修正画面へ<!--{/if}--></a>
					</div>
				</div>
				<br>
			<!--{/foreach}-->
			
			<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
			<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration}-->
				<div class="problem">
					<div class="problem_contents_title">
						<!--{$row_q.exam_problem_name|escape|nl2br}-->
						<div class="confbtn"><a href="javascript:void(0)" onclick="confirmDisp(<!--{$row_q.exam_problem_id}-->);">確認する</a></div>
					</div>
					<div id="problem_contents_<!--{$row_q.exam_problem_id}-->" class="problem_contents_parent">
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
									<!--{if $row1_q.no==$answered_info[$row_q.exam_problem_id].arr_exam_answer_contents[0]}-->
										<div class="problem_content">
											<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled checked><!--{$row_no1_q}-->.</div>
											<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{else}-->
										<div class="problem_content">
											<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled><!--{$row_no1_q}-->.</div>
											<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{/if}-->
								<!--{/foreach}-->
								
							<!--{* 複数形式 *}-->
							<!--{elseif $row_q.answer_kind==2}-->
								<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
								<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
									<!--{if isset($answered_info) && isset($answered_info[$row_q.exam_problem_id].arr_exam_answer_contents) && array_search($row1_q.no, $answered_info[$row_q.exam_problem_id].arr_exam_answer_contents)!==false}-->
										<div class="problem_content">
											<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled checked><!--{$row_no1_q}-->.</div>
											<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{else}-->
										<div class="problem_content">
											<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled><!--{$row_no1_q}-->.</div>
											<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
										</div>
										<br style="clear:both;">
									<!--{/if}-->
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row_q.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info[$row_q.exam_problem_id].arr_exam_answer_contents[0]}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
						</div>
					</div>
					
					<div id="btn_area_<!--{$row_q.exam_problem_id}-->" class="btn_area">
						<a class="btn_edit" href="/exam/index2.php?pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}-->&eid=<!--{$eid|escape}-->&eno=<!--{$row_no_q+$eno_max_test}-->&eflg=1<!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->"><!--{if $btn_type_q=='2'}-->回答修正画面へ<!--{else}-->解答修正画面へ<!--{/if}--></a>
					</div>
				</div>
				<br>
			<!--{/foreach}-->
			
			<div style="text-align:center;width:420px;margin-left:246px;">
				<input class="btn2" type="button" onclick="location.href='/exam/index2.php?pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}-->&eid=<!--{$eid|escape}-->&eno=<!--{$eno_max}--><!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->'" value="戻る">
				<input class="btn1" type="button" onclick="examFormSubmit(<!--{$arr_list.exam_id|escape}-->,<!--{$pid|escape}-->,<!--{$ccno|escape}-->);" value="提出する">
			</div>

		</div>
	</div>
</div>
</form>
