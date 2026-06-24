<!--{if $nichibenren_flg}-->
<script type="text/javascript">
function formSubmit(formName, mode, sid, pid){
	var ret = false;
	if (mode == "delete"){
		ret = confirm("本当に削除してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['sid'].value = sid;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].submit();
		}
	}
}
function formSubmitComplete(formName, mode, sid, pid, flg){
	var ret = false;
	if (mode == "complete"){
		if (flg == 1){
			ret = confirm("未完了に変更します。");
		} else {
			ret = confirm("完了済に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['sid'].value = sid;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['flg'].value = flg;
			document.forms[formName].submit();
		}
	}
}
function formSubmitStatus(formName, mode, sid, pid, flg){
	var ret = false;
	if (mode == "status"){
		if (flg == 6){
			ret = confirm("レポートに変更します。");
		} else if (flg == 7){
			ret = confirm("追試×に変更します。");
		}
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].elements['sid'].value = sid;
			document.forms[formName].elements['pid'].value = pid;
			document.forms[formName].elements['flg'].value = flg;
			document.forms[formName].submit();
		}
	}
}
</script>
<!--{/if}-->

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
			<!--{if $nichibenren_flg}-->
			<a href="csv.php?type=info&pid=<!--{$pid}-->">CSV取得</a>
			<!--{* <a href="csv.php?type=info_list&pid=<!--{$pid}-->">受付用リスト作成</a> *}-->
			<a href="info_user_regist.php?pid=<!--{$pid}-->">個別登録</a>
			<a href="info_user_import.php?pid=<!--{$pid}-->">CSV取り込み</a>
			<!--{/if}-->
			</th>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input.product_name}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<form action="info.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="sid" value="">
<input type="hidden" name="pid" value="">
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
		<th>完了</th>
		<th>登録番号</th>
		<th>氏名</th>
		<th>所属弁護士会</th>
		<!--{if $nichibenren_flg}-->
		<th>削除</th>
		<!--{/if}-->
	</tr>

	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.create_date|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
			<!--{if $row.status_reserv != ''}-->
				<!--{if $row.status_reserv==6 || $row.status_reserv==7}-->
					<!--{if $nichibenren_flg}-->
						<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <!--{$row.student_id}-->, <!--{$row.product_id}-->, <!--{$row.status_reserv}-->);return false;" ><!--{$row.disp_status_reserv|escape}--></a>
					<!--{else}-->
						<!--{$row.disp_status_reserv|escape}-->
					<!--{/if}-->
				<!--{else}-->
					<!--{$row.disp_status_reserv|escape}-->
				<!--{/if}-->
			<!--{else}-->
				<!--{if $row.status==6 || $row.status==7}-->
					<!--{if $nichibenren_flg}-->
						<a href="javascript:void(0);" onclick="formSubmitStatus('list_form', 'status', <!--{$row.student_id}-->, <!--{$row.product_id}-->, <!--{$row.status}-->);return false;" ><!--{$row.disp_status|escape}--></a>
					<!--{else}-->
						<!--{$row.disp_status|escape}-->
					<!--{/if}-->
				<!--{else}-->
					<!--{$row.disp_status|escape}-->
				<!--{/if}-->
			<!--{/if}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->">
			<!--{if $nichibenren_flg}-->
				<a href="javascript:void(0);" onclick="formSubmitComplete('list_form', 'complete', <!--{$row.student_id}-->, <!--{$row.product_id}-->, <!--{$row.complete_flg}-->);return false;" ><!--{$row.disp_complete|escape}--></a>
			<!--{else}-->
				<!--{$row.disp_complete|escape}-->
			<!--{/if}-->
		</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.lawyer_number|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.student_name|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$mtb_bar_association[$row.bar_association_id]|escape}--></td>
		<!--{if $nichibenren_flg}-->
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="javascript:void(0);" onclick="formSubmit('list_form', 'delete', <!--{$row.student_id}-->, <!--{$row.product_id}-->);return false;" >削除</a></td>
		<!--{/if}-->
	</tr>
	<!--{/foreach}-->
	<!--{/if}-->
</table>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php?page=1';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
