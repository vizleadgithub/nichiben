<script type="text/javascript">
function formSubmit(formName, mode){
	var ret = true;
	if (mode == "regist"){
		var lawyer_numbers = document.forms[formName].elements['lawyer_numbers'].value;
		if (lawyer_numbers.length == 0) {
			alert('追加する登録番号を入力してください');
		}
		else {
			ret = confirm("本当に登録してもよろしいですか？");
			if (ret == true){
				document.forms[formName].elements['mode'].value = mode;
				document.forms[formName].submit();
			}
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
				<!--{$arr_input_2.bar_association_name}-->
				&nbsp;
				<!--{$arr_input_2.bar_association_branch_name}-->
			</td>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input_2.product_name}-->
			</td>
		</tr>
		<tr>
			<th>研修実施日</th>
			<td style="width:70%">
				<!--{$arr_input_2.dates}-->
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
	<div class="submit">
	</div>
</form>

<form action="info_user_regist.php" accept-charset="utf-8" method="post" name="list_form">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pid" value="<!--{$pid}-->">
<input type="hidden" name="aid" value="<!--{$aid}-->">
<input type="hidden" name="atype" value="<!--{$atype}-->">

<!--{*
<!--{if $res == "success2"}-->
<h3 style="color:blue; font-weight:bold">申込を登録しました</h3>
<!--{elseif $res == 'failed2'}-->
<h3 style="color:red; font-weight:bold">申込の登録に失敗しました</h3>
<!--{/if}-->
*}-->


<!--{if $res_msg != ""}-->
<div style="color:red;">
	<!--{$res_msg}-->
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
<a href="javascript:void(0);" onclick="formSubmit('list_form', 'regist');return false;" >追加する</a>
</form>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='info_user.php?pid=<!--{$pid}-->&aid=<!--{$aid}-->';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
