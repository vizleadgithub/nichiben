<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "upload"){
		var oid = document.forms[formName].elements['csv_upload'].value;
		if (oid.length == 0) {
			alert('ファイルを選択してください');
		}
		else {
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "regist"){
		ret = confirm("本当に登録してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == 'change'){
		ret = confirm("本当に受講済みにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
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
				<!--{$arr_input_2.entry_number|number_format|escape}--> / <!--{$arr_input_2.capacity|number_format|escape}--> 人
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
	<div class="submit">
	</div>
</form>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="form_csv_upload" enctype="multipart/form-data">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<!--{$pid}-->">
<input type="hidden" name="aid" value="<!--{$aid}-->">
<input type="hidden" name="atype" value="<!--{$atype}-->">
<input type="hidden" name="oid" value="<!--{$oid}-->">

<!--{*
<!--{if $res == "success"}-->
<h3 style="color:blue; font-weight:bold">アップロードをしました</h3>
<!--{elseif $res == 'failed'}-->
<h3 style="color:red; font-weight:bold">アップロードに失敗しました</h3>
<!--{elseif $res == "success2"}-->
<h3 style="color:blue; font-weight:bold">申込を登録しました</h3>
<!--{elseif $res == 'failed2'}-->
<h3 style="color:red; font-weight:bold">申込の登録に失敗しました</h3>
<!--{elseif $res == "success3"}-->
<h3 style="color:blue; font-weight:bold">受講済みに変更しました</h3>
<!--{elseif $res == 'failed3'}-->
<h3 style="color:red; font-weight:bold">受講済みの変更に失敗しました</h3>
<!--{/if}-->
*}-->

<div style="color:red;">
<!--{$err_msg|escape|nl2br}-->
</div>

<table class="list">
	<tr>
		<th>ファイルをアップロードしてcsv申込を取り込みます</th>
	</tr>
</table>

<table>
	<tr style="">
		<td>
			<input type="file" name="csv_upload" size="30">
			<a href="javascript:void(0);" onclick="formSubmit('form_csv_upload', 'upload');return false;" >アップロードする</a>
		</td>
	</tr>
</table>

</form>

<!--{if $mode == "upload" && $err_msg == ""}-->
<!--{*
<table>
	<tr style="">
		<td>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'regist');return false;" >申込状況に追加する</a>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'change');return false;" >受講済みにする</a>
		</td>
	</tr>
</table>
*}-->

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<!--{$pid}-->">
<input type="hidden" name="aid" value="<!--{$aid}-->">
<input type="hidden" name="atype" value="<!--{$atype}-->">
<input type="hidden" name="oid" value="<!--{$oid}-->">
<input type="hidden" name="ufnn" value="<!--{$upload_file_new_name|escape}-->">
	<table class="list">
		<tr>
			<th>登録番号</th>
			<th>氏名</th>
			<th>所属会</th>
			<th>申込日</th>
			<th>取り込み日</th>
		</tr>

		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<!--{cycle values="0,1" assign="cycle_bg"}-->
		<tr style="">
			<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.lawyer_number|escape}--></td>
			<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.student_name|escape}--></td>
			<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.bar_association_name|escape}--></td>
			<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.entry_date}--></td>
			<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.take_date}--></td>
		</tr>
		<!--{/foreach}-->
		<!--{/if}-->

	</table>
</form>
<!--{/if}-->

<!--{if $res_msg != ""}-->
<div>
	<!--{$res_msg|escape}-->
</div>
<!--{/if}-->



<!--{*
<!--{if $mode == "result"}-->
<br>
<!--{if $res == "success2"}-->
合計<!--{$res_cnt|number_format}-->名を研修登録しました。<br>
<!--{elseif $res == "success3"}-->
合計<!--{$res_cnt|number_format}-->名を受講済みに変更しました。<br>
<!--{/if}-->
<!--{/if}-->
*}-->



<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info_user.php?pid=<!--{$pid}-->&aid=<!--{$aid}-->';" /><img src="/alfproduct/images/btn_back.png"></a>
	<!--{if $mode == "upload" && $err_msg == ""}-->
	<!--{*
		<a href="javascript:void(0);" onclick="formSubmit('list_form', 'regist');return false;" >申込状況に追加する</a>
		<a href="javascript:void(0);" onclick="formSubmit('list_form', 'change');return false;" >受講済みにする</a>
	*}-->
	<input type="button" value="申込状況に追加する" onClick="formSubmit('list_form', 'regist');return false;" style="margin-bottom:13px;" />
	<input type="button" value="受講済みにする" onClick="formSubmit('list_form', 'change');return false;" style="margin-bottom:13px;" />
	<!--{/if}-->
</div>
<a name="page_bottom"></a>
