<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "add"){
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
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input_2.product_name|escape}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>

<form action="info_user_regist.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<!--{$pid|escape}-->">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->">

<!--{if $res_msg != ""}-->
<div style="color:red;">
	<!--{$res_msg|escape|nl2br}-->
</div>
<!--{/if}-->


<table class="list">
	<tr>
		<th>追加登録番号入力</th>
	</tr>
</table>
<table>
	<tr style="">
		<td>
			<textarea name="lawyer_numbers" style="width:250px; height:300px;"><!--{$lawyer_numbers|escape}--></textarea>
		</td>
	</tr>
</table>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'add');return false;" >代替倫理研修権限を付与する</a>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'report');return false;" >レポートにする</a>
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'end');return false;" >受講完了する</a>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info.php?pid=<!--{$pid}-->';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
