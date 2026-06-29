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
.problem .problem_contents_title .pseigo{
position:absolute;
top:0px;
right:80px;
font-size:14px;
width:80px;
height:100%;
text-align:center;
line-height:40px;
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
						<!--{if $row.answer_kind==1 || $row.answer_kind==2}-->
							<div  class="pseigo"><span><!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1}-->正解<!--{else}-->不正解<!--{/if}--></span></div>
						<!--{/if}-->
						<div class="confbtn"><span><a href="javascript:void(0)" onclick="confirmDisp(<!--{$row.exam_problem_id}-->);">確認する</a></span></div>
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
										<div class="content1"><input type="checkbox" id="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->" name="exam_problem_<!--{$row.exam_problem_id}-->[]" value="<!--{$row1.no}-->" disabled <!--{if isset($answered_info) && isset($answered_info[$row.exam_problem_id].exam_answer_contents) && array_search($row1.no, $answered_info[$row.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1}-->.</div>
										<div class="content2"><label for="exam_problem_<!--{$row.exam_problem_id}-->_<!--{$row_no1}-->"><!--{$row1.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_<!--{$row.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info[$row.exam_problem_id].arr_exam_answer_contents[0]}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
						</div>
						<!--{if $row.answer_kind==1 || $row.answer_kind==2}-->
							<div class="problem_contents problem_result">
								<div><!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1}-->正解<!--{else}-->不正解<!--{/if}--></div>
								<!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1 || !$hantei_ari}-->
									<div>正解は「<!--{$answered_info[$row.exam_problem_id].correct_answer_str}-->」、あなたの解答は「<!--{$answered_info[$row.exam_problem_id].exam_answer_contents_str}-->」</div>
								<!--{/if}-->
							</div>
						<!--{/if}-->
						
						<!--{if $row.answer_explain_kind==1}-->
							<!--{if $answered_info[$row.exam_problem_id].exam_answer_mark==1 || !$hantei_ari}-->
								<div class="problem_contents problem_comment">
									<div>解説</div>
									<div style="text-align:left;"><!--{$row.answer_explain_contents|escape|nl2br}--></div>
									<div style="text-align:left;"><!--{$row.answer_explain_note|escape|nl2br}--></div>
								</div>
							<!--{/if}-->
						<!--{/if}-->
					</div>
					
					<div id="btn_area_<!--{$row.exam_problem_id}-->" class="btn_area">
						<a class="btn" href="/exam/index1.php?pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}-->&eid=<!--{$eid|escape}-->&eno=<!--{$row_no}-->&eflg=1<!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->"><!--{if $btn_type=='2'}-->回答修正画面へ<!--{else}-->解答修正画面へ<!--{/if}--></a>
					</div>
				</div>
				<br>
			<!--{/foreach}-->
			
			<!--{foreach name=loop_q from=$arr_list_q.problem item="row_q" key="key_q"}-->
			<!--{assign var=row_no_q value=$smarty.foreach.loop_q.iteration}-->
				<div class="problem">
					<div class="problem_contents_title">
						<!--{$row_q.exam_problem_name|escape|nl2br}-->
						<div class="confbtn"><span><a href="javascript:void(0)" onclick="confirmDisp(<!--{$row_q.exam_problem_id}-->);">確認する</a></span></div>
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
									<div class="problem_content">
										<div class="content1"><input type="radio" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled <!--{if isset($answered_info_q) && isset($answered_info_q[$row_q.exam_problem_id].exam_answer_contents) && $row1_q.no==$answered_info_q[$row_q.exam_problem_id].exam_answer_contents[0]}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* 複数形式 *}-->
							<!--{elseif $row_q.answer_kind==2}-->
								<!--{foreach name=loop1_q from=$row_q.answer_contents_select.answer_contents item="row1_q" key="key1_q"}-->
								<!--{assign var=row_no1_q value=$smarty.foreach.loop1_q.iteration}-->
									<div class="problem_content">
										<div class="content1"><input type="checkbox" id="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->" name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" value="<!--{$row1_q.no}-->" disabled <!--{if isset($answered_info_q) && isset($answered_info_q[$row_q.exam_problem_id].exam_answer_contents) && array_search($row1_q.no, $answered_info_q[$row_q.exam_problem_id].exam_answer_contents)!==false}-->checked<!--{/if}-->><!--{$row_no1_q}-->.</div>
										<div class="content2"><label for="exam_problem_q_<!--{$row_q.exam_problem_id}-->_<!--{$row_no1_q}-->"><!--{$row1_q.word|escape|nl2br}--></label></div>
									</div>
									<br style="clear:both;">
								<!--{/foreach}-->
								
							<!--{* フリー解答 *}-->
							<!--{elseif $row_q.answer_kind==3}-->
								<div class="problem_content"><textarea name="exam_problem_q_<!--{$row_q.exam_problem_id}-->[]" cols="115" rows="10" disabled><!--{$answered_info_q[$row_q.exam_problem_id].arr_exam_answer_contents[0]}--></textarea></div>
								
							<!--{else}-->
								未設定
								
							<!--{/if}-->
						</div>
					</div>
					
					<div id="btn_area_<!--{$row_q.exam_problem_id}-->" class="btn_area">
						<a class="btn" href="/exam/index1.php?pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}-->&eid=<!--{$eid|escape}-->&eno=<!--{$row_no_q+$row_no}-->&eflg=1<!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->"><!--{if $btn_type_q=='2'}-->回答修正画面へ<!--{else}-->解答修正画面へ<!--{/if}--></a>
					</div>
				</div>
				<br>
			<!--{/foreach}-->
			
			<!--{if $hantei_ari}-->
				<div style="text-align:left;padding:10px;font-size:14px;">
※本講座は，テストに全問正解しないと次のパートに進むことができません。<br>
※不正解と表示された設問の解答を修正して下さい（「確認する」→「解答修正画面へ」をクリック）。<br>
				</div>
			<!--{/if}-->
			
			<!--{if $save_error}-->
				<div style="text-align:center;padding:10px;color:#ff0000;font-size:14px;">
					保存処理に失敗しました。もう一度「終了する」ボタンを押してください。<br>
					繰り返し失敗する場合は、しばらく時間をおいてからお試しください。
				</div>
			<!--{/if}-->

			<div style="text-align:center;padding:10px;">
				<a class="btn" href="/exam/answer_save1.php?eid=<!--{$eid|escape}-->&pid=<!--{$pid|escape}-->&ccno=<!--{$ccno|escape}--><!--{if $qid!=''}-->&qid=<!--{$qid|escape}--><!--{/if}-->">終了する</a>
			</div>

		</div>
	</div>
</div>
</form>
