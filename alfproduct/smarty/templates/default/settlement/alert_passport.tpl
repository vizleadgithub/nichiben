<div style="background-color:#fcfcfc;padding:30px;border:solid 1px #cccccc;width:900px;text-align:center;font-weight:bold;font-size:16px;line-height:2em;height:630px;">
	<div style="text-align:right;">
		<img src="/img/passport_bnr.png" alt="研修パスポートのご案内" onClick="formAlertPassportSubmit(1)" style="cursor:pointer;" />
	</div>
	
	<div style="text-align:left;padding:50px 0;">
買い物かごの合計額が研修パスポート料金を超えています。<br />
研修パスポートを購入すると，このサイトに掲載されている日弁連主催の<br />
ライブ実務研修及びｅラーニング全てを一年間何度でも受講できますので研修パスポートの購入をおすすめします。<br />
<br />
研修パスポートを購入した場合，単品で選択した講座は自動的に「マイページ」の「お気に入り講座」に追加されます。<br />
<br />
<span style="color:red;font-size:20px;">ライブ実務研修のお申込みは完了していません。<br />パスポート購入後，「マイページ」の『お気に入りの講座』ページから再度お申込みください。</span><br />
<br />
下のボタンからご希望のボタンをクリックしてください。<br />
<br />
なお，研修パスポートの詳細は右上の「研修パスポートのご案内」ボタンをクリックしてください。
	</div>
	
	<ul style="text-align:center;padding-top:20px;list-style:none;padding-left:90px;">
		<li style="float:left;padding:0 5px;"><a href="javascript:void(0);" onClick="formAlertPassportSubmit(1)"><img src="/img/button/passport_btn.jpg" alt="研修パスポートを購入する" /></a>
		<!--{if $passport_pop_flg==1}-->
			<li style="float:left;padding:0 5px;"><a href="/settlement"><img src="/img/buy/tanpin_btn.png" alt="このまま単品で購入する" /></a></li>
		<!--{else}-->
			<li style="float:left;padding:0 5px;"><a href="#" id="button"><img src="/img/buy/tanpin_btn.png" alt="このまま単品で購入する" /></a></li>
		<!--{/if}-->
	</ul>
</div>


<form name="formAlertPassport" action="/settlement/alert_passport.php" method="post">
<input type="hidden" id="act" name="act" value="" />
<div id="modal">
	<div id="heading">
		単品購入をご希望の方へ
	</div>
	<div id="content">
		<p>日弁連から研修パスポートのご案内がございます。<br />「研修パスポートのご案内」をご確認ください。</p>
		<p style="text-align:center;">
		<input type="button" value="研修パスポートのご案内" onClick="formAlertPassportSubmit(1)" style="width:185px;font-size:14px;" />
		<input type="button" value="このまま単品で購入" onClick="formAlertPassportSubmit(2)" style="width:160px;font-size:14px;" />
		<p style="padding:0;margin:0;">
		<label><input type="checkbox" name="passport_pop_flg" value="1" style="width:20px;" />今後このメッセージを表示しない</label>
		</p>
		</p>
	</div>
</div>
</form>

<link href="/css/jquery-reveal-style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jquery/jquery.reveal.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#button').click(function(e) { // Button which will activate our modal
	   	$('#modal').reveal({ // The item which will be opened with reveal
		  	animation: 'fade',                   // fade, fadeAndPop, none
			animationspeed: 600,                       // how fast animtions are
			closeonbackgroundclick: true,              // if you click background will modal close?
			dismissmodalclass: 'close'    // the class of a button or element that will close an open modal
		});
	return false;
	});
});
function formAlertPassportSubmit(flg){
	if (flg == 1){
		document.getElementById("act").value = "passport";
	} else if (flg == 2){
		document.getElementById("act").value = "buy";
	}
	document.formAlertPassport.submit();
}
</script>
