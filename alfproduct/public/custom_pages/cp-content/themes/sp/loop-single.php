<!--[loop-single.php]-->
<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * https://codex.wordpress.org/The_Loop to understand it and
 * https://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.2
 */
?>
<?php
if (isset($_GET["p"])){
	$id = (int)$_GET["p"];
} else {
	$id = trim(strrchr($_SERVER["REQUEST_URI"], "/"), "/");
}

$posts = null;
$posts = get_posts('numberposts=1&include='.$id);
global $post;

$categories_list = get_the_category($id);
$term_id = $categories_list[0]->term_id;
//print_r("[".$id."]");
//print_r("[".$term_id."]");
// -------------------------
// テンプレート切換用フラグ
// -------------------------
// ニュース
if ($term_id == 20){
	$cate_flg = 1;
} else {
	$cate_flg = 0;
}
?>

<?php
// ---------
// ニュース
// ---------
if ($cate_flg == 1){
?>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php
		// 受講対象の弁護士会に所属していないユーザーの場合はnot found.と表示
		if ($post->product_type_add == 2 && stripos($post->target, '|'.$_SESSION['user']['bar_association_id'].'|') === false && $term_id !=99 ){ ?>
			not found.
		<?php
		} elseif ( $term_id == 99 ){ ?>
			<div style="background-color:#fcfcfc;border:solid 1px #ff0000;width:730px;">
				<!-- <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> -->
					<div id="single_title" style="border:none;padding:10px 20px; width:680px;background: url(/img/lecture/h2_back_red.png) no-repeat 50% 95%;">
						<h3 class="entry-title" style="color:#ff0000;font-size:17px;font-weight:bold;margin:0;"><?php the_title(); ?></h3>
					</div>
					<div class="entry-content" style="padding:5px 20px; width:680px;">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php if ($post->product_id != 0){ ?>
							<div style="text-align:right;padding-bottom:10px;">
								<a href="/product/detail.php?pid=<?php echo (int)$post->product_id; ?>" style="color:#796A57;">>>詳細情報を見る</a>
							</div>
						<?php } ?>
					</div><!-- .entry-content -->
			</div>
		<?php } else { ?>
			<div style="background-color:#fcfcfc;border:solid 1px #cccccc;width:730px;">
				<!-- <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> -->
					<div id="single_title" style="border:none;padding:10px 20px; width:680px;">
						<h3 class="entry-title" style="color:#579748;font-size:17px;font-weight:bold;margin:0;"><?php the_title(); ?></h3>
					</div>

					<div class="entry-content" style="padding:5px 20px; width:680px;">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php if ($post->product_id > 0){ ?>
							<div style="text-align:right;padding-bottom:10px;">
								<a href="/product/detail.php?pid=<?php echo (int)$post->product_id; ?>" style="color:#796A57;">>>詳細情報を見る</a>
							</div>
						<?php } else { ?>
							<?php if ($post->url != ""){ ?>
								<div style="text-align:right;padding-bottom:10px;">
									<a href="<?php echo htmlspecialchars($post->url, ENT_QUOTES, 'UTF-8'); ?>" style="color: #796A57;" target="_blank" rel="noopener noreferrer">>>詳細情報を見る</a>
								</div>
							<?php } else { ?>
							<?php } ?>
						<?php } ?>
					</div><!-- .entry-content -->
			</div>
		<?php } ?>
		<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
			<div id="entry-author-info">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
				</div><!-- #author-avatar -->
				<div id="author-description">
					<h2><?php printf( esc_attr__( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
					<?php the_author_meta( 'description' ); ?>
					<div id="author-link">
						<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
							<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
						</a>
					</div><!-- #author-link	-->
				</div><!-- #author-description -->
			</div><!-- #entry-author-info -->
		<?php endif; ?>
		<!-- </div> --><!-- #post-## -->
	<?php endwhile; // end of the loop. ?>

<?php } elseif ( $term_id == 99 ){ ?>
	<?php
	$tmp_post = $posts;
	$posts = get_posts('numberposts=1&include='.$id);
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	?>
			<div style="background-color:#fcfcfc;border:solid 1px #ff0000;width:730px;">
				<!-- <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> -->
				<div id="single_title" style="border:none;padding:10px 20px; width:680px;background: url(/img/lecture/h2_back_red.png) no-repeat 50% 95%;">
					<h3 class="entry-title" style="color:#ff0000;font-size:17px;font-weight:bold;margin:0;"><?php the_title(); ?></h3>
				</div>
				<div class="entry-content" style="padding:5px 20px; width:680px;">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
					<?php if ($post->product_id != 0){ ?>
						<div style="text-align:right;padding-bottom:10px;">
							<a href="/product/detail.php?pid=<?php echo (int)$post->product_id; ?>" style="color:#796A57;">>>詳細情報を見る</a>
						</div>
					<?php } ?>
				</div><!-- .entry-content -->
			</div>
	<?php 
	endwhile; // end of the loop.
	endif;
	$posts=$tmp_post;
	?>
<?php } else { ?>
not found.
<?php } ?>