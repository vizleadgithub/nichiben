<!--[single.php]-->
<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div style="width:220px;float:left;margin-left:13px;">
			<?php get_sidebar(1); ?>
		</div>
		
		<div id="container" style="width:730px;float:left;padding-right:17px;">
			<div id="content" role="main">

			<?php
			/* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
			get_template_part( 'loop', 'single' );
			?>

			</div><!-- #content -->
		</div><!-- #container -->

		<!--
		<div style="width:230px;float:left;">
			<?php //get_sidebar(2); ?>
			<?php //get_sidebar(3); ?>
		</div>
		-->
<?php get_footer(); ?>
