<script type="text/javascript">
<!--{*
function formSubmit(formName, mode, pid, aid, atype, oid){
	var ret = true;
	if (mode == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['atype'].value = atype;
			document.forms[formName].elements['oid'].value = oid;
			document.forms[formName].submit();
		}
	}
}
*}-->

function formSubmit(formName, mode, pid, aid, odid){
	var ret = false;
	if (mode == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].submit();
		}
	}
}
function formSubmitParticipation(formName, mode, pid, aid, odid, flg){
	var ret = false;
	if (mode == "participation"){
		if (flg == 1){
			ret = confirm("未受講に変更します。");
		} else {
			ret = confirm("受講済に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].elements['flg'].value = flg;
			document.forms[formName].submit();
		}
	}
}
function formSubmitStatus(formName, mode, pid, aid, odid, flg){
	var ret = false;
	if (mode == "status"){
		if (flg == 1){
			ret = confirm("仮入金に変更します。");
		} else if (flg == 2){
			ret = confirm("未入金に変更します。");
		} else if (flg == 3){
			ret = confirm("支払済に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].elements['odid'].value = odid;
			document.forms[formName].elements['flg'].value = flg;
			document.forms[formName].submit();
		}
	}
}
function formSubmitFpFix(formName, mode, pid, aid){
	var ret = false;
	if (mode == "fp_fix"){
		ret = confirm("現在FP（当日FP）の欄を更新します");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['aid'].value = aid;
			document.forms[formName].submit();
		}
	}
}
</script>

<!--
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>
-->

<!--<h2>検索する内容を入力してください</h2>-->

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th colspan="2">
			研修内容
			<a href="csv.php?type=info_user&pid=<!--{$pid}-->&aid=<!--{$aid}-->">CSV取得</a>
			<a href="csv.php?type=info_user_list&pid=<!--{$pid}-->&aid=<!--{$aid}-->">受付用リスト作成</a>
			<a href="info_user_regist.php?pid=<!--{$pid}-->&aid=<!--{$aid}-->">個別登録</a>
			<a href="info_user_import.php?pid=<!--{$pid}-->&aid=<!--{$aid}-->">CSV取り込み</a>
			</th>
		</tr>
		<tr>
			<th>開催会</th>
			<td style="width:70%">
				<!--{$arr_input_2.bar_association_name|escape}-->
				&nbsp;
				<!--{$arr_input_2.bar_association_branch_name|escape}-->
			</td>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input_2.product_name|escape}-->
			</td>
		</tr>
		<tr>
			<th>研修実施日</th>
			<td style="width:70%">
				<!--{$arr_input_2.dates|escape}-->
			</td>
		</tr>
		<tr>
			<th>料金（テキスト代含む）</th>
			<td style="width:70%">
				<!--{$arr_input_2.price|escape|number_format}-->円
			</td>
		</tr>
		<tr>
			<th>定員</th>
			<td style="width:70%">
				<!--{$arr_input_2.entry_number|number_format}--> / <!--{$arr_input_2.capacity|number_format}--> 人
			</td>
		</tr>
		<tr>
			<th>WEB申込</th>
			<td style="width:70%">
				<!--{if $arr_input_2.web_flg==1}-->
					受け付ける
				<!--{else}-->
					受け付けない
				<!--{/if}-->
			</td>
		</tr>
	</table>
	<div style="padding-top:10px;text-align:right;">
		<input type="button" value="　パスポート状況更新　" onclick="formSubmitFpFix('list_form', 'fp_fix', <!--{$pid}-->, <!--{$aid}-->);return false;" />
		<!--{if $disp_fp_fix_date != ''}-->
			<div style="padding:5px 5px 0 0;"><!--{$disp_fp_fix_date|escape}--></div>
		<!--{/if}-->
	</div>
</form>

<form action="info_user.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="">
<input type="hidden" name="aid" value="">
<input type="hidden" name="atype" value="">
<input type="hidden" name="oid" value="">
<input type="hidden" name="odid" value="">
<input type="hidden" name="flg" value="">
<!--{if $res == "success"}-->
<h3 style="color:blue; font-weight:bold">更新しました</h3>
<!--{elseif $res == "failed"}-->
<h3 style="color:red; font-weight:bold">更新に失敗しました</h3>
<!--{elseif $res == "success2"}-->
<h3 style="color:blue; font-weight:bold">登録しました</h3>
<!--{/if}-->
<table class="list2">
	<tr>
		<th>受付日</th>
		<th>ステイタス</th>
		<th>受講</th>
		<th>WEB</th>
		<th>登録番号</th>
		<th>氏名</th>
		<th>所属弁護士会</th>
		<th>申込FP</th>
		<!--{if $fp_fix_flg}--><th>当日FP</th><!--{else}--><th>現在FP</th><!--{/if}-->
		<th>価格</th>
		<th>削除</th>
	</tr>

	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.create_date|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
		<!--{if $row.payment_type == '99'}-->
			<!--{$row.disp_payment|escape}-->
		<!--{else}-->
			<!--{if $row.payment_status_reserv != ''}-->
				<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <!--{$pid}-->, <!--{$aid}-->, <!--{$row.order_detail_id}-->, <!--{$row.payment_status_reserv}-->);return false;" style="color:<!--{$row.disp_payment_reserv_color|escape}-->;"><!--{$row.disp_payment_reserv|escape}--></a>
			<!--{else}-->
				<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <!--{$pid}-->, <!--{$aid}-->, <!--{$row.order_detail_id}-->, <!--{$row.payment_status}-->);return false;" style="color:<!--{$row.disp_payment_color|escape}-->;"><!--{$row.disp_payment|escape}--></a>
			<!--{/if}-->
		<!--{/if}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
		<!--{if $row.participation_flg == '1'}-->
			<a href="javascript:void(0);" onclick="formSubmitParticipation('list_form', 'participation', <!--{$pid}-->, <!--{$aid}-->, <!--{$row.order_detail_id}-->, <!--{$row.participation_flg}-->);return false;" >済</a>
		<!--{else}-->
			<a href="javascript:void(0);" onclick="formSubmitParticipation('list_form', 'participation', <!--{$pid}-->, <!--{$aid}-->, <!--{$row.order_detail_id}-->, <!--{$row.participation_flg}-->);return false;" >未</a>
		<!--{/if}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.disp_web|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.lawyer_number|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.student_name|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$mtb_bar_association[$row.bar_association_id]|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.disp_fp|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.disp_fp2|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.pay_total|number_format}-->円</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="javascript:void(0);" onclick="formSubmit('list_form', 'delete', <!--{$pid}-->, <!--{$aid}-->, <!--{$row.order_detail_id}-->);return false;" >削除</a></td>
	</tr>
	<!--{/foreach}-->
	<!--{/if}-->
</table>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<!--{$pid}-->';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
