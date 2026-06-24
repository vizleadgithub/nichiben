<script type="text/javascript">
	function reminder_window(){
		window.open("/reminder/", "reminder_window", "width=500,height=400,status=0,resizable=0,scrollbars=0,toolbar=0,menubar=0,location=0");	
	}
</script>

<div style="text-align:center;">
	<div class="login_info">
		<h2>会員登録がお済の方</h2>
		<p>会員の方は、登録時に入力されたメールアドレスとパスワードでログインしてください。</p>
		<form name="form1" action="/login/login.php?back_url=<!--{$back_url|escape}-->" method="post">
		<input type="hidden" name="act" value="execute" />
		<table>
			<tr>
				<th>メールアドレス</th><td><input type="text" name="email" value="<!--{if isset($smarty.cookies.login_email)}--><!--{$smarty.cookies.login_email}--><!--{/if}-->" /></td>
			</tr>
			<tr>
				<th>パスワード</th><td><input type="password" name="password" /></td>
			</tr>
		</table>
			<input type="submit" value="ログイン" class="login_btn" />
		</form>
		<div class="pass_reminder">
		パスワードを忘れた方は<a href="javascript:void(0)" onclick="reminder_window();return false;">こちら</a>からパスワードの再発行を行ってください。<br />
		メールアドレスを忘れた方は、お手数ですが、<a href="/inquiry/">お問い合わせページ</a>からお問い合わせください。
		</div>
	</div>
	<div class="regist_info">
		<h2>まだ会員登録されていない方</h2><br />
		<div class="regist_btn"><a href="/member/regist.php">会員登録をする</a></div>
	</div>
</div>
