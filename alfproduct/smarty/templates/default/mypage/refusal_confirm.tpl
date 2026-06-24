<!--{include file='mypage/side_menu.tpl'}-->
<div style="float:right;width:720px;">
		<div id="single_title" style="margin-bottom:20px;">
			<h3>退会手続き</h3>
			<h6>refusal</h6>
		</div>



	<form name="form1" action="refusal.php" method="post">
	<input type="hidden" name="action" value="complete" />


		<div style="border: solid 1px #47a6d4;padding: 5px;text-align:center;">
			<div style="padding:10px; text-align:left;">
		会員を退会された場合には、現在保存されている購入履歴等、<br />
		すべての情報が削除されますがよろしいでしょうか？<br />
			</div>
			<a onmouseout="chgImg('/img/btn/refusal_not.png','refuse_not');" onmouseover="chgImg('/img/btn/refusal_not.png','refuse_not');" href="refusal.php" /><!--
				--><img id="refuse_not" name="refuse_not" alt="いいえ、退会しません" src="/img/btn/refusal_not.png" /><!--
			--></a>

			<input id="refuse_do" type="image" name="refuse_do" alt="はい、退会します" src="/img/btn/refusal_do.png" />
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