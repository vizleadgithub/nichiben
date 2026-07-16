<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "upload"){
		var csv_upload_value = document.forms[formName].elements['csv_upload'].value;
		if (csv_upload_value.length == 0) {
			alert('ファイルを選択してください');
		}
		else {
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "add"){
		ret = confirm("本当に代替倫理研修権限を付与してもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == "report"){
		ret = confirm("本当にレポートにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
	else if (mode == 'end'){
		ret = confirm("本当に受講済みにしてもよろしいですか？");
		if (ret == true){
			document.forms[formName].elements['mode'].value = mode;
			document.forms[formName].submit();
		}
	}
}
</script>


<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input_2.product_name|escape}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="form_csv_upload" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="pid" value="<!--{$pid|escape}-->">
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
<table>
	<tr style="">
		<td>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'add');return false;" >代替倫理研修権限を付与する</a>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'report');return false;" >レポートにする</a>
			<a href="javascript:void(0);" onclick="formSubmit('list_form', 'end');return false;" >受講済みにする</a>
		</td>
	</tr>
</table>

<form action="info_user_import.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<!--{$pid|escape}-->">
<input type="hidden" name="ufnn" value="<!--{$upload_file_new_name|escape}-->">
	<table class="list">
		<tr>
			<th>登録番号</th>
			<th>氏名</th>
			<th>申込日</th>
			<th>取り込み日</th>
		</tr>

		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<tr style="">
			<td class="tdc" style=""><!--{$row.lawyer_number|escape}--></td>
			<td class="tdc" style=""><!--{$row.student_name|escape}--></td>
			<td class="tdc" style=""><!--{$row.entry_date}--></td>
			<td class="tdc" style=""><!--{$row.take_date}--></td>
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






<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<!--{$pid}-->';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
