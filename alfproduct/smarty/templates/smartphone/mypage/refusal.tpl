<!--{include file='mypage/side_menu.tpl'}-->
<div style="float:right;width:720px;">
		<div id="single_title" style="margin-bottom:20px;">
			<h3>退会手続き</h3>
			<h6>refusal</h6>
		</div>



	<form name="form1" action="refusal.php" method="post">
	<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
	<input type="hidden" name="action" value="confirm" />

		<div style="border: solid 1px #47a6d4;padding: 5px;text-align:center;">
			<div style="text-align:left;padding:10px;">
				会員を退会された場合には、現在保存されている購入履歴等、<br>
				すべての情報が削除されますがよろしいでしょうか？<br>
			</div>
			<input id="refusal" type="image" name="refusal" alt="会員退会を行う" src="/img/btn/refusal01.png" />
		</div>

		<script type="text/javascript" >
		function chgImg(fileName,img){
			if (typeof(img) == "object") {
				img.src = fileName;
			} else {
				document.images[img].src = fileName;
			}
		}
		function chgImgImageSubmit(fileName,imgObj){
			imgObj.src = fileName;
		}
		</script>
	</form>

</div>