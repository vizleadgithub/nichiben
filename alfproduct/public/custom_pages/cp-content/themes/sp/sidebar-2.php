<?php
/**
 * ログイン
 */
?>
<div class="login">
	<h3></h3>
<div class="profile">
<?php if(st_login_check()){ ?>
<p style="margin:15px 0;text-align:center;font-size:18px;"><?php echo htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8').'様'; ?></p>

<?php /* if($_SESSION['user']['presence_passport']==1){ ?>
<div style="margin:2px 5px 2px 10px;">研修パスポート有効期限</div>
<div class="timelimit"><?php echo $_SESSION['user']['exp_date_passport']; if($_SESSION['user']['passport_alert']){echo '　<img src="/img/attention.png" alt="【！】" />' ;} ?></div>
<?php } */ ?>

<div style="margin:12px 5px 2px 10px;">あなたの倫理研修義務年</div>
<div class="timelimit"><?php if($_SESSION['user']['bar_association_duty_year']){echo htmlspecialchars((string)$_SESSION['user']['bar_association_duty_year'], ENT_QUOTES, 'UTF-8');}else{echo '完了しています';} ?></div>

<?php if (contents_limit_user_check()){ ?>
<p style="margin:15px 0;text-align:center;color:red;">
	<?php if ($_SESSION['user']['presence_passport']==1){ ?>
		<a href="/mypage/limit_list2.php"><img src="/img/limit_bnr.png" alt="掲載終了間近の研修があります詳細はこちら" /></a>
	<?php } else { ?>
		<a href="/mypage/limit_list1.php"><img src="/img/limit_bnr.png" alt="掲載終了間近の研修があります詳細はこちら" /></a>
	<?php } ?>

</p>
<?php } ?>




<div style="margin:0;text-align:center;height:35px;"><a href="/mypage" style="display:block;"><img src="/img/rc_navbtn001.png" alt="マイページ" /></a></div>
<div style="margin:0;text-align:center;height:35px;"><a href="/mypage/favorite_list.php"><img src="/img/rc_navbtn002.png" alt="お気に入り一覧" /></a></div>
<div style="margin:0;text-align:center;height:43px;"><a href="/mypage/ticket_list.php" style="margin:0;"><img src="/img/rc_navbtn003.png" alt="受講票ダウンロード" /></a></div>

</div>










<?php } else { ?>
<form name="form1" action="https://<?php echo htmlspecialchars($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8'); ?>/login/login.php" method="post">
<input type="hidden" name="action" value="execute" />
	<p>
		email<br />
		<input type="text" name="email" style="width:96%;" value="<?php if(isset($_COOKIE['login_email'])){echo htmlspecialchars($_COOKIE['login_email'], ENT_QUOTES, 'UTF-8');} ?>" />
	</p>
	<p>
		password<br />
		<input type="password" name="password" style="width:96%;" />
	</p>
	<input type="submit" value="ログイン" class="login_btn_top" />
</form>

<div class="login_nr">
	<a href="/member/regist.php">新規ユーザー登録はこちら</a>
</div>



<div class="login_nr">
	<a href="javascript:void(0)" onclick="reminder_window();return false;">パスワードを忘れた方はこちら</a>
	<script type="text/javascript">
		function reminder_window(){
			window.open("/reminder/", "reminder_window", "width=500,height=400,status=0,resizable=0,scrollbars=0,toolbar=0,menubar=0,location=0");	
		}
	</script>
</div>
<?php } ?>
</div>








<?php if(st_login_check()){ ?>
<?php $start_date = get_bar_association_live_update_date(); ?>

<div class="lawyer_info">
<h3></h3>
<div class="lawyer_info_main">
<p>あなたの弁護士会が主催する<br />研修はこちら</p>
<p><?php if ($start_date){echo '更新日：'.$start_date;} ?></p>
<p><a href="/product/list_bar_association_live_other.php"><img src="/img/lawyer_training_btn.png" alt="弁護士会主催研修" /></a></p>
</div>
</div>


<?php /*
<div style="padding-top:10px;padding:10px;">
<a href="/product/list_passport.php"><img src="/img/passport_bnr.png" alt="研修パスポートのご案内" /></a>
</div>
*/ ?>

<?php } ?>