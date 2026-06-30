<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
$objDbConnect = new DbConnect();
$cat_list = array();
$sql = "SELECT";
$sql.= "  T1.term_id,";
$sql.= "  T1.name";
$sql.= " FROM";
$sql.= "  wp_terms AS T1";
$sql.= "    JOIN";
$sql.= "  wp_term_taxonomy AS T2";
$sql.= "    ON T1.term_id = T2.term_id";
$sql.= " WHERE";
$sql.= "  T2.parent='21'";
$sql.= " ORDER BY T1.slug ASC";
$cat_list = $objDbConnect->query_fetch_arr($sql);
?>
	</div><!-- #main -->

<style type="text/css">
div#footer ul#foot_menu{
list-style:none;
margin-top:20px;
}
div#footer ul#foot_menu li{
float:left;
width:240px;
	line-height:180%;
}
div#footer ul#foot_menu li a{
margin-left:10px;
}
div#footer div#copyright{
clear:both;
text-align:center;
padding-top:10px;
color:#ffffff;
}
</style>

	<div id="footer" role="contentinfo">
		<div class="pagetop"><a href="#top"></a></div>
		<div id="colophon">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	//get_sidebar( 'footer' );
?>

			<ul id="foot_menu">
				<li><img src="/img/ftct_01.gif" alt="・カテゴリマップ" /><br />
					<?php
					if($cat_list){
						foreach($cat_list as $val){
					?>
						<a href="/product/list.php?pcid=<?php echo $val['term_id']; ?>"><?php echo $val['name']; ?></a><br />
					<?php
						}
					}
					?>
				</li>
				<li><img src="/img/ftct_02.gif" alt="・本サイトについて" /><br />
					<a href="<?php site_url(); ?>/policy">プライバシーポリシー</a><br />
					<a href="<?php site_url(); ?>/rule">特定商取引法に基づく表記</a><br />
					<a href="<?php site_url(); ?>/company">株式会社NTTスマートコネクトについて</a><br />
				</li>
				<li><img src="/img/ftct_03.gif" alt="・ご利用ガイド" /><br />
					<a href="<?php site_url(); ?>/first">初めての方へ</a><br />
					<a href="<?php site_url(); ?>/sitemember">本サイト会員について</a><br />
					<a href="<?php site_url(); ?>/agreement">会員規約</a><br />
					<a href="<?php site_url(); ?>/guide">ご利用案内</a><br />
					<a href="<?php site_url(); ?>/personal">個人情報保護方針について</a><br />
					<a href="<?php site_url(); ?>/notice">会社案内</a><br />
				</li>
				<li><img src="/img/ftct_04.gif" alt="・その他" /><br />
					<a href="<?php site_url(); ?>/question">よくある質問</a><br />
					<a href="<?php site_url(); ?>/browser">推奨環境</a><br />
					<a href="<?php site_url(); ?>/inquiry/">お問い合わせ</a><br />
				</li>
			</ul>
			<div id="copyright">
				Copyright (C) On-Tap/株式会社NTTスマートコネクト1996-2013 All Rights Reserved
			</div>
		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>