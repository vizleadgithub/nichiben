<?php
/**
 * バナーエリア
 */
?>
<?php /*
<div>
<ul style="list-style: none;">
	<li><a href="/mypage/favorite_list.php"><img src="/img/bnr/bnr01.gif" /></a></li>
	<li><a href="/search/"><img src="/img/bnr/bnr02.gif" /></a></li>
	<li><img src="/img/bnr/bnr03.gif" /></li>
	<li><img src="/img/bnr/bnr04.gif" /></li>
	<li><img src="/img/bnr/bnr05.gif" /></li>
	<li><img src="/img/bnr/bnr06.gif" /></li>
	<li><img src="/img/bnr/bnr07.gif" /></li>

</ul>
</div>
*/ ?>

<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp_post = $posts;
$posts = get_posts('numberposts=1&category_name=free_html_top5');
if($posts): foreach($posts as $post): setup_postdata($post);
?>
<div style="width:230px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;">
<?php echo $post->post_content ?>
</div>
<?php 
endforeach; endif;
$posts=$tmp_post;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>