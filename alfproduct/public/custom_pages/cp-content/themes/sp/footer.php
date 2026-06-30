	</div><!-- #main -->

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

// 購入フローと倫理研修問題の場合はフッター非表示
if (strpos($_SERVER['SCRIPT_NAME'], '/settlement/') === false){
if (strpos($_SERVER['SCRIPT_NAME'], '/ethic_treaning/') === false){

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

<style type="text/css">
div#footer ul#foot_menu{
list-style:none;
margin-top:20px;
}
div#footer ul#foot_menu li{
float:left;
width:298px;
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
		<div id="colophon">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	//get_sidebar( 'footer' );
?>
			<div class="site_map">
			<ul id="foot_menu">
				<li>
					<div class="fttt"><span class="fs">■</span>&nbsp;総合研修サイト内のご案内</div>
					<div><a href="<?php site_url(); ?>/policy">利用規約</a></div>
					<!--<div><a href="<?php site_url(); ?>/policy_passport">研修パスポート利用規約</a></div>-->
					<div><a href="<?php site_url(); ?>/question">よくある質問</a></div>
					<div><a href="<?php site_url(); ?>/pdf/jfba-kenshu-manual.pdf" target="_blank" rel="noopener noreferrer">総合研修サイトマニュアル</a></div>
					<div><a href="<?php site_url(); ?>/inquiry/">総合研修サイトに関するお問い合わせ</a></div>
					<div><a href="<?php site_url(); ?>/browser">総合研修サイト推奨環境</a></div>
				</li>
				<li>
					<div class="fttt"><span class="fs">■</span>&nbsp;会員専用サイトのご案内</div>
					<div><a href="https://member.nichibenren.or.jp/site_riyojoken.html" target="_blank" rel="noopener noreferrer">サイト利用条件</a></div>
					<div><a href="https://www.nichibenren.or.jp/copyright/privacy.html" target="_blank" rel="noopener noreferrer">個人情報保護方針</a></div>
					<div><a href="<?php site_url(); ?>/sitemap">サイトマップ</a></div>
				</li>
				<li>
					<div class="fttt"><span class="fs">■</span>&nbsp;その他</div>
					<div><a href="<?php site_url(); ?>/pdf/rinri-kenshu-kisoku.pdf" target="_blank" rel="noopener noreferrer">倫理研修規則</a></div>
					<div><a href="<?php site_url(); ?>/pdf/rinri-kenshu-kitei.pdf" target="_blank" rel="noopener noreferrer">倫理研修規程</a></div>
					<div><a href="<?php site_url(); ?>/pdf/shinki-touroku-bengoshi-kenshu-guideline.pdf" target="_blank" rel="noopener noreferrer">新規登録弁護士研修ガイドライン</a></div>
					<div><a href="<?php site_url(); ?>/pdf/keizoku-kenshu-guideline.pdf" target="_blank" rel="noopener noreferrer">継続研修ガイドライン</a></div>
					<div><a href="https://www.jlf.or.jp/work/kenshu/" target="_blank" rel="noopener noreferrer">公益財団法人日弁連法務研究財団の研修に係る御案内（外部サイト）</a></div>
				</li>

			</ul>
			<br style="clear:both;" />
			</div>

			<div id="copyright" style="color:#aaaaaa;">
				Copyright (C) Japan Federation of Bar Associations all rights reserved.
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

}
}
?>

</body>
</html>