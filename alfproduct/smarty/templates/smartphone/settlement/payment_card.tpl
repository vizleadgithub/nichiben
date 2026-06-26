<style type="text/css">
th.table_name{
	text-align:right;
	width:160px;
	color:#79BEDF;
	font-size: 14px;
}
td.table_data{
	text-align:left;
	width:240px;
	padding-left: 30px;
	font-size: 14px;
}
.btn_close{
 background: url(/img/close_b2.gif) no-repeat ;
 width: 133px;
 height: 30px;
 border: 0px;
}
#btn_submit{
 background: url(/img/account_btn2.gif) no-repeat ;
 width: 200px;
 height: 30px;
 border: 0px;

}

.btn input:hover {
opacity:0.7;
filter: alpha(opacity=70);        /* ie lt 8 */
-ms-filter: "alpha(opacity=70)";  /* ie 8 */
-moz-opacity:0.7;                 /* FF lt 1.5, Netscape */
-khtml-opacity: 0.7;              /* Safari 1.x */
}
</style>
<script type="text/javascript">
//  var _gaq = _gaq || [];
//  _gaq.push(['_setAccount', 'UA-4136556-6']);
//  _gaq.push(['_trackPageview']);
//  (function() {
//    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
//    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
//    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
//  })();
</script>

<div>
	<img src="/img/buy/page_step2.png" alt="" />
</div>

<div style="background-color:#fcfcfc;padding:0 30px 30px 30px;border:solid 1px #cccccc;width:900px;">

<!--{if $err_flg}-->
	<div style="color:#999999; font-weight:bold; font-size:14px;padding-top:100px;padding-left:5px;text-align:center;">
		カード決済の失敗
	</div>
	<table style="margin-top:10px;margin-left:225px;margin-bottom:80px;line-height:2em;">
		<tr>
			<td style="width:400px;text-align:center;" class="table_data"><span style="color:#ff0000;">決済に失敗しました。<br />お手数ですが再度ご購入をお願いいたします。<br /></span><td>
		</tr>
		<tr>
			<td style="text-align:center;padding-top:20px;" class="table_data">
				<a href="/">TOPへ戻る</a>
			<!--{*
				<form name="form_payment">
				<input type="button" value="" onclick="payment_close();" class="btn_close" style="cursor: pointer;" />
				</form>
			*}-->
			</td>
		</tr>
	</table>
	<!--{*<input type="button" value="閉じる" onclick="window.close();" />*}-->
	<!--{*
		決済エラー<br>
		<form name="form_payment" method="post" action="#">
		注文番号：<!--{$order_no|escape}--><br>
		購入金額：<!--{$total|escape}-->円<br>
		カード番号:<input type="text" name="card_no" value="<!--{$card_no|escape}-->" autocomplete="off"><br>
		カード有効期限:<input type="text" name="expire_m" value="<!--{$expire_m|escape}-->" size="4">月　<input type="text" name="expire_y" value="<!--{$expire_y|escape}-->" size="4" autocomplete="off">年<br>
		<input type="submit" name="btn_submit" value="Submit" class="btn_close" >
		</form>
	*}-->
<!--{else}-->
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">購入内容</div>
	</div>
	
	<table style="border-collapse:collapse;border:solid 1px #cccccc;line-height:5px;margin-top:20px;">
		<tr>
			<th style="border:solid 1px #cccccc;"><img src="/img/buy/tbl_title2.jpg" alt="商品名" /></th>
			<th style="border:solid 1px #cccccc;"><img src="/img/buy/tbl_title3.jpg" alt="小計(税込)" /></th>
		</tr>
		<!--{foreach from=$cart item=val key=key}-->
		<tr>
			<td style="border:solid 1px #cccccc;text-align:left;padding:15px;"><!--{$val.product_name|escape}--></td>
			<td style="border:solid 1px #cccccc;text-align:right;padding:15px;"><!--{$val.price|escape|number_format}-->円</td>
		</tr>
		<!--{/foreach}-->
		<tr>
			<th style="border:solid 1px #cccccc;text-align:right;padding:20px;vertical-align:middle;">合計(税込)</th>
			<td style="border:solid 1px #cccccc;text-align:right;padding:20px 15px 20px 20px;"><!--{$cart_total_price|escape|number_format}-->円</td>
		</tr>
	</table>
	

	現在、クレジットカードによる決済はご利用いただけません。<br>

	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">カード決済情報</div>
	</div>
	<!--{*
	<form name="form_payment" method="post" action="#">
	*}-->
	<form name="form_payment" method="post" action="/settlement/">
	<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
	<table style="margin-top:0px;line-height:3em;">
		<tr>
			<th class="table_name" style="width:250px;text-align:left;color:#5c9e4b;">注文番号：</th><td class="table_data"><!--{$order_no|escape}--><td>
		</tr>
		<tr>
			<th class="table_name" style="width:250px;text-align:left;color:#5c9e4b;">購入合計金額：</th><td class="table_data"><!--{$total|escape|number_format}-->円<td>
		</tr>
		<tr>
			<th class="table_name" style="width:250px;text-align:left;color:#5c9e4b;">カード番号(ハイフンを抜いて半角で):</th><td class="table_data"><input type="text" name="card_no" value="" style="width:200px;" autocomplete="off"><td>
		</tr>
		<tr>
			<th class="table_name" style="width:250px;text-align:left;color:#5c9e4b;">カード有効期限:</th>
			<td class="table_data">
				<select name="expire_m">
					<option value="">--</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select>月　
				<!--{html_options name=expire_y options=$arr_expire_y}-->年
				<!--{* <input type="text" name="expire_y" value="" size="4" maxlength="2" autocomplete="off">年（西暦下2桁） *}-->
			<td>
		</tr>
		<tr>
			<td colspan="2" style="padding:10px;">
				<img src="/img/card_logo.png" />
			</td>
		</tr>
	</table>
	<div style="color: #666666;font-size:12px;line-height:2em;">
【クレジットカード情報の取扱いについて】<br />
利用目的　研修受講料の決済手続のため<br />
一時取得者　日本弁護士連合会<br />
提供先　決済代行会社　ＧＭＯペイメントゲートウェイ株式会社<br />
保存期間　必要情報入力後，本サイトの購入手続終了まで<br />
本サイトの購入手続が終了いたしました後，カード情報は一切保持しません。<br />
	<br />
	<span style="color:#ff6666;"><a href="http://www.nichibenren.or.jp/copyright/privacy.html" target="_blank" rel="noopener noreferrer">プライパシーポリシー</a>及び<a href="/policy" target="_blank" rel="noopener noreferrer">利用規約</a>に同意の上，以上の内容を確認して「購入を完了する」ボタンをクリックして下さい。</span>
	<br />
入力した決済情報は暗号化され送信されます。
	</div>

<!--{*
	<div style="padding-top:20px;">
	<label><input type="checkbox" name="claim_flg" value="1" /><span style="font-size:14px;">請求書の送付を希望する</span></label>
	</div>
*}-->
	<table style="margin-top:20px;width:480px;">
		<tr>
			<!--{* 
			<td style="width:180px;text-align:right;" class="btn">
				<input type="button" value="" onclick="payment_close();" class="btn_close btnCursor" style="cursor: pointer;" />
			</td>
			<td style="width:40px;">&nbsp;</td>
			*}-->
			<!--{* 
			<td style="width:180px;text-align:left;" class="btn">
				<input type="submit" name="btn_submit" value="" class="btnCursor" style="cursor: pointer;" id="btn_submit" />
			</td>
			*}-->
			<td style="width:180px;text-align:left;" class="btn">
				<input type="button" name="btn_submit" value="" class="btnCursor" style="cursor: pointer;" id="btn_submit" />
			</td>
		</tr>
	</table>
	</form>
<!--{/if}-->

</div>
