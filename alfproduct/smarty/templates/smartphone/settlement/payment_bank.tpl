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
		決済の失敗
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
	
	<div style="padding-top:20px;">
		<div style="color:#525252;font-size:16px;font-weight:bold;margin-bottom:5px;padding:5px 10px 5px 15px;background: url(/img/i_l.png)no-repeat;border-bottom:1px dotted #22730e;">銀行振込に関する注意点</div>
	</div>
	
	<form name="form_payment" method="post" action="#">
	<input type="hidden" name="mode" value="settlement_exe" />
	<table>
		<tr>
			<td>
本サイトの購入手続完了後15日以内に購入金額の合計額をお振込ください。<br />
当会がご入金を確認した後にご利用可能となります。利用可能になり次第メールでお知らせいたします。<br />
<br />
購入手続期限までにお振り込みが確認出来なかった場合，注文は「キャンセル」となります。<br />
<br />
パスポート料金等、申込日により料金に差がある商品を購入される場合はご注意下さい。<br />
<br />
振込の際は会員氏名の前に必ず登録番号を付して入金をお願いいたします。<br />
「登録番号＋会員氏名（フルネーム）」<br />
「弁護士」等の肩書は付さない下さい。<br />
例）９９９９９ニチベンレンタロウ<br />
<br />
振込先<br />
三菱東京ＵＦＪ銀行　東京公務部支店（店番３００）<br />
口座　普通　１００６９９５<br />
名義　日本弁護士連合会　<br />
			</td>
		</tr>
	</table>
<br />
<span style="color:#ff6666;"><a href="http://www.nichibenren.or.jp/copyright/privacy.html" target="_blank" rel="noopener noreferrer">プライパシーポリシー</a>及び<a href="/policy" target="_blank" rel="noopener noreferrer">利用規約</a>に同意の上，以上の内容を確認して「購入を完了する」ボタンをクリックして下さい。</span>
<br />

<div style="padding-top:20px;">
<label><input type="checkbox" name="claim_flg" value="1" /><span style="font-size:14px;">請求書の送付を希望する</span></label>
</div>

	<table style="margin-top:20px;width:480px;">
		<tr>
		<!--{* 
			<td style="width:180px;text-align:right;" class="btn">
				<input type="button" value="" onclick="payment_close();" class="btn_close btnCursor" style="cursor: pointer;" />
			</td>
			<td style="width:40px;">&nbsp;</td>
		*}-->
			<td style="width:180px;text-align:left;" class="btn">
				<input type="submit" name="btn_submit" value="" class="btnCursor" style="cursor: pointer;" id="btn_submit" />
			</td>
		</tr>
	</table>
	</form>
<!--{/if}-->

</div>
