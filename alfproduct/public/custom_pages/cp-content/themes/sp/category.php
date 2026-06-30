<?php
?>
<?php
date_default_timezone_set('Asia/Tokyo');

$category = get_queried_object(); // 現在のカテゴリ情報を取得
$category_id = $category->term_id; // カテゴリID
$category_slug = $category->slug; // カテゴリスラッグ
$category_name = $category->name; // カテゴリ名
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

//print("<!--[category_id:".$category_id."]-->\n");
//print("<!--[category_slug:".$category_slug."]-->\n");
//print("<!--[category_name:".$category_name."]-->\n");
//print("<!--[paged:".$paged."]-->\n");

$objDbConnect = new DbConnect();
$sql = "
UPDATE 
   wp_posts  
SET  
  post_status = 'publish'
WHERE  
  AND post_name LIKE '%news%' 
  AND post_status = 'future'
  AND post_type='post' 
  AND post_date<='".date("Y-m-d H:i:s")."'
";
$tempret = $objDbConnect->query_fetch($sql);

$ret = array();
$where = "";
//+++++++++++++++++++++++
$st_login_check = st_login_check();
$where.= " AND tbl_product.del_flg = '0'";
$where.= " AND (tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL)";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
if (!$st_login_check){
	$where.= " AND tbl_product.product_type = '2'";
}
$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
}//+++++++++++++++++++++++
$sql = "
SELECT 
  wp_posts.ID,
  wp_posts.post_author,
  wp_posts.post_date,
  wp_posts.post_content,
  wp_posts.post_title,
  wp_posts.post_status,
  wp_posts.comment_status,
  wp_posts.ping_status,
  wp_posts.post_name,
  wp_posts.post_modified,
  wp_posts.post_parent,
  wp_posts.post_type,
  wp_posts.product_type_add,
  wp_posts.status,
  wp_posts.product_id,
  wp_posts.target,

  wp_posts.open_date,
  wp_posts.close_date,
  wp_posts.url,
  wp_posts.topfit 

FROM 
  wp_posts 
  LEFT JOIN tbl_product ON wp_posts.product_id=tbl_product.product_id 
  LEFT JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id 
  LEFT JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id 
  LEFT JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id 
  LEFT JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id 

WHERE 
  1=1
  AND wp_posts.post_parent=0 
  AND wp_posts.post_name LIKE '%news%' 
  AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'future') 
  AND wp_posts.post_type='post' 
  AND wp_posts.post_date<='".date("Y-m-d H:i:s")."' 
  AND wp_posts.open_date<='".date("Y-m-d H:i:s")."' 
  AND wp_posts.close_date>='".date("Y-m-d H:i:s")."' 

  AND ( 
  wp_posts.product_id=0 
  OR( 
    1=1 
    ".$where." 
    ) 
  ) 

ORDER BY 
  wp_posts.topfit DESC, wp_posts.post_date DESC, wp_posts.ID DESC
limit 8
";
$ret = $objDbConnect->query_fetch_arr($sql);
$max_count = 0;

if( is_array($ret) ){
	$max_count = count($ret);
}
//print("<!--[".$sql."]-->");
$ret = $objDbConnect->query_fetch_arr($sql." LIMIT 10 OFFSET ".( ($paged-1)*10 )." ");
//print("<!--[\n");
//var_dump($ret);
//print("\n]-->\n");


//$slug = trim(strrchr($_SERVER["REQUEST_URI"], "/"), "/");
if(strpos($_SERVER["REQUEST_URI"],'/news')>0){
	$slug = "news";
}
print("<!--[slug:".$slug."]-->\n");
// ニュースのみ表示させる
//if ($slug == 'news'){
//	$category_description = category_description();
//	if ( ! empty( $category_description ) )
//	echo '<div class="archive-meta">' . $category_description . '</div>';
//} else {
//	echo 'not found.';
//}
?>

<?php get_header(); ?>
<div style="width:220px;float:left;margin-left:13px;">
	<?php get_sidebar(1); ?>
</div>
<!--[NewsList]-->
<div id="container" style="width:698px;float:left;padding-right:17px;">
	<div id="content" role="main" style="width:698px;">
		<div id="nav-below" class="navigation">
			<div class="nav-previous">
				<?php 
				if( ( ($paged+1) * 10)<$max_count ){
				?>
					<a href="/archives/category/news/page/<?php print( ($paged+1) ); ?>"><span class="meta-nav">←</span> 古い投稿</a>
				<?php
				}
				?>
			</div>
			<div class="nav-next">
				<?php 
				if( $paged>1 ){
				?>
					<a href="/archives/category/news/page/<?php print( ($paged-1) ); ?>">新しい投稿 <span class="meta-nav">→</span></a>
				<?php
				}
				?>
			</div>
		</div><!-- #nav-below -->
		
		<?php
		if (!empty($ret)){
			foreach($ret as $post){
		?>
			<!--[Test11]-->
			<?php
			// 受講対象の弁護士会に所属していないユーザーの場合は対象でない旨を表示
			$disp_flg = true;
			if (
				$post["product_type_add"] == 2
				 && stripos(
					$post["target"],
					'|'.$_SESSION['user']['bar_association_id'].'|'
				) === false
			){
				$disp_flg = false;
			}
			?>
			<!--[Test12]-->
			<!--[bar_association_id:<?php print($_SESSION['user']['bar_association_id']); ?>]-->
			<!--[target:<?php print($target); ?>]-->
			<div id="post-<?php print($post["ID"]); ?>" <?php post_class(); ?> style="background-color:#fcfcfc;border:solid 1px #cccccc;width:698px;">
				<div id="single_title" style="border:none;padding:10px 20px;width: 658px;">
					<h3 class="entry-title" style="padding:0;margin:0;width: 658px;">
						<?php if (!$disp_flg){ ?>
							<!--[Test13]-->
							<span style="color:#579748;font-size:17px;font-weight:bold;"><?php print(htmlspecialchars($post["post_title"], ENT_QUOTES, 'UTF-8')); ?></span>
						<?php } else { ?>
							<!--[Test14]-->
							<?php if ($post["url"] != ""){ ?>
								<!--<a href="<?php echo $post["url"]; ?>" title="" rel="bookmark" style="color:#579748;font-size:17px;font-weight:bold;">--><span style="color:#579748;font-size:17px;font-weight:bold;"><?php print(htmlspecialchars($post["post_title"], ENT_QUOTES, 'UTF-8')); ?></span><!--</a>-->
							<?php } else { ?>
								<!--<a href="/archives/<?php print($post["ID"]); ?>" title="" rel="bookmark" style="color:#579748;font-size:17px;font-weight:bold;">--><span style="color:#579748;font-size:17px;font-weight:bold;"><?php print(htmlspecialchars($post["post_title"], ENT_QUOTES, 'UTF-8')); ?></span><!--</a>-->
							<?php } ?>
						<?php } ?>
					</h3>
				</div>
				<!--[Test15]-->
				<?php if (!$disp_flg){ ?>
					<div class="entry-summary" style="padding:5px 20px 10px 20px;">
						<div style="text-align:right;">
							受講対象でない会場研修です。
						</div>
					</div><!-- .entry-summary -->
				<?php } else { ?>
					<div class="entry-summary" style="word-wrap: break-word;width: 100%;box-sizing: border-box;padding:5px 20px 10px 20px;">
						<p><?php print( strip_tags($post["post_content"])); ?></p>
						<div style="text-align:right;">
							<?php if ($post["product_id"] > 0){ ?>
								<a href="/archives/<?php print($post["ID"]); ?>" class="page-link" rel="bookmark" style="color: #796A57;font-weight: normal;">>>詳細へ</a>
							<?php } else { ?>
								<?php if ($post["url"] != ""){ ?>
									<a href="<?php print(htmlspecialchars($post["url"], ENT_QUOTES, 'UTF-8')); ?>" class="page-link" rel="noopener noreferrer" style="color: #796A57;font-weight: normal;" target="_blank">>>詳細へ</a>
								<?php } else { ?>
									<a href="/archives/<?php print($post["ID"]); ?>" class="page-link" rel="bookmark" style="color: #796A57;font-weight: normal;">>>詳細へ</a>
								<?php } ?>
							<?php } ?>
						</div>
					</div><!-- .entry-content -->
				<?php } ?>
			</div><!-- #post-## -->
		<?php
			}
		}
		?>

		<div id="nav-below" class="navigation">
			<div class="nav-previous">
				<?php 
				if( ( ($paged+1) * 10)<$max_count ){
				?>
					<a href="/archives/category/news/page/<?php print( ($paged+1) ); ?>"><span class="meta-nav">←</span> 古い投稿</a>
				<?php
				}
				?>
			</div>
			<div class="nav-next">
				<?php 
				if( $paged>1 ){
				?>
					<a href="/archives/category/news/page/<?php print( ($paged-1) ); ?>">新しい投稿 <span class="meta-nav">→</span></a>
				<?php
				}
				?>
			</div>
		</div><!-- #nav-below -->
		<style type="text/css">
			div.nav-previous a, div.nav-next a{
				color:#796A57 !important;
			}
			#nav-below {
				padding: 16px;
				margin: 18px 0 0 0;
			}
		</style>

	</div><!-- #content -->
</div><!-- #container -->


<?php get_footer(); ?>
