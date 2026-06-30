<?php
date_default_timezone_set('Asia/Tokyo');
get_header();

$objDbConnect = new DbConnect();
$sql = "
UPDATE  
   wp_posts  
SET  
  post_status = 'publish' 
WHERE  
      post_name LIKE '%news%' 
  AND post_status = 'future' 
  AND post_type='post' 
  AND post_date<='".date("Y-m-d H:i:s")."' 
";
$tempret = $objDbConnect->execute($sql);
?>

<div style="width:210px;float:left;margin-left:13px;">
	<?php get_sidebar(1); ?>
</div>

<div id="container" style="width:730px;float:left;padding-left:13px;">
	<?php
	// おすすめ講座
	include "nav_search.php";
	?>

	<?php 
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$tmp_post = $posts;
	$posts = get_posts('numberposts=1&category_name=free_html_top1');
	if($posts): foreach($posts as $post): setup_postdata($post);
	?>
	<div style="width:720px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;">
	<?php echo $post->post_content ?>
	</div>
	<?php 
	endforeach; endif;
	$posts=$tmp_post;
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	?>



	<?php
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$tmp_post = $posts;
	$posts = get_posts('numberposts=-1&category_name=pickup');
	$news_count = 0;
	if($posts): 
		?>
		<div class="pickup" style="">
			<h2 style="vertical-align: top;">
				<img src="/img/top_icon_0.png" alt="Pick Up" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#ff0000;">Pick Up</span>
			</h2>
			<div class="news_bd">
				<ul style="list-style:block;margin-top:0px;overflow: auto;/*height: 480px;*/padding-bottom: 10px;background-color: #ffffff;">
					<?php
					foreach($posts as $post): 
						setup_postdata($post);
						?>
						<li style="min-height: 30px;/*max-height: 50px;*/overflow:hidden;color:#ff0000;">
							<div>
								<div style="width:100px;display:inline-block;float:left;">
									<?php
									if( (int)get_the_date('Y')>=2030 ){
									} else {
										echo the_time('Y/m/d');
									}
									?>
								</div>
								<a href="<?php the_permalink(); ?>" style="color:#ff0000;width:600px;float:left;"><?php echo get_the_title(); ?></a>
							</div>
							<br style="clear:both;" />
						</li>
						<?php
						$news_count++;
					endforeach;
					?>
				</ul>
			</div>
		</div>
		<?php
	endif;
	$posts=$tmp_post;
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	?>



	<?php // news ?>
	<div class="news_tab_area" style="border:none;">
		<ul>
		<li class="nav01" style="width: 110px;margin-right: 3px;border-right: solid 1px #CCCCCC;"><a href="#page_area1">おすすめ</a></li>
		<li class="nav02" style="width:150px;margin-right: 3px;border-right: solid 1px #CCCCCC;"><a href="#page_area2">新着eラーニング</a></li>
		<li class="nav03" style="width:160px;margin-right: 3px;border-right: solid 1px #CCCCCC;"><a href="#page_area3">受付中のライブ研修</a></li>
		<li class="nav04" style="width:145px;margin-right: 3px;border-right: solid 1px #CCCCCC;"><a href="#page_area4">弁護士会主催研修</a></li>
		<li class="nav05" style="width:143px;"><a href="#top_product_now">受講中の講座</a></li>
		</ul>
	</div>
	<script type="text/javascript">
		function selected_nav(nav_no){
			/*
			if( nav_no==1 ){
				$(".news_tab_area li").removeClass("selected");
				$(".nav01").addClass("selected");

				//$(".news").hide();
				$(".product_new_elearning").hide();
				$(".product_live_training").hide();
				$(".product_bar_association_live_other").hide();
				$(".product_now").hide();
				$(".news").show();
				//$(".product_new_elearning").show();
				//$(".product_live_training").show();
				//$(".product_bar_association_live_other").show();
				//$(".product_now").show();
			} else if ( nav_no==2 ){
				$(".news_tab_area li").removeClass("selected");
				$(".nav02").addClass("selected");

				$(".news").hide();
				//$(".product_new_elearning").hide();
				$(".product_live_training").hide();
				$(".product_bar_association_live_other").hide();
				$(".product_now").hide();
				//$(".news").show();
				$(".product_new_elearning").show();
				//$(".product_live_training").show();
				//$(".product_bar_association_live_other").show();
				//$(".product_now").show();
			} else if ( nav_no==3 ){
				$(".news_tab_area li").removeClass("selected");
				$(".nav03").addClass("selected");

				$(".news").hide();
				$(".product_new_elearning").hide();
				//$(".product_live_training").hide();
				$(".product_bar_association_live_other").hide();
				$(".product_now").hide();
				//$(".news").show();
				//$(".product_new_elearning").show();
				$(".product_live_training").show();
				//$(".product_bar_association_live_other").show();
				//$(".product_now").show();
			} else if ( nav_no==4 ){
				$(".news_tab_area li").removeClass("selected");
				$(".nav04").addClass("selected");

				$(".news").hide();
				$(".product_new_elearning").hide();
				$(".product_live_training").hide();
				//$(".product_bar_association_live_other").hide();
				$(".product_now").hide();
				//$(".news").show();
				//$(".product_new_elearning").show();
				//$(".product_live_training").show();
				$(".product_bar_association_live_other").show();
				//$(".product_now").show();
			} else if ( nav_no==5 ){
				$(".news_tab_area li").removeClass("selected");
				$(".nav05").addClass("selected");

				$(".news").hide();
				$(".product_new_elearning").hide();
				$(".product_live_training").hide();
				$(".product_bar_association_live_other").hide();
				//$(".product_now").hide();
				//$(".news").show();
				//$(".product_new_elearning").show();
				//$(".product_live_training").show();
				//$(".product_bar_association_live_other").show();
				$(".product_now").show();
			} else {
				$(".news_tab_area li").removeClass("selected");
				$(".nav01").addClass("selected");

				//$(".news").hide();
				$(".product_new_elearning").hide();
				$(".product_live_training").hide();
				$(".product_bar_association_live_other").hide();
				$(".product_now").hide();
				$(".news").show();
				//$(".product_new_elearning").show();
				//$(".product_live_training").show();
				//$(".product_bar_association_live_other").show();
				//$(".product_now").show();
			}
			*/
		}
	</script>
	<style type="text/css">
		.pickup{
			border-top:solid 1px #ff0000;
			border-left:solid 1px #ff0000;
			border-right:solid 1px #ff0000;
			border-bottom:solid 1px #ff0000;
			background-color: #ffffff;
		}

		.news_tab_area {
			/*background-color: #FFFFFF;*/
			background-color: none;
			margin-top: 20px;
		}
		.news_tab_area ul {
			display: flex;
			justify-content: space-between;
			flex-wrap: wrap;
			list-style: none;
			padding: 0;
			margin: 0;
		}
		.news_tab_area li {
			display: flex;
			border-bottom: solid 1px #CCCCCC;
		}
		.news_tab_area li.nav01{
			border-radius: 10px 10px 10px 10px;

			border-top: solid 1px #CCCCCC;
			border-left: solid 1px #CCCCCC;
			background-color: #FFFFFF;
		}
		.news_tab_area li.nav02{
			border-radius: 10px 10px 10px 10px;

			border-top: solid 1px #CCCCCC;
			border-left: solid 1px #CCCCCC;
			background-color: #FFFFFF;
		}
		.news_tab_area li.nav03{
			border-radius: 10px 10px 10px 10px;

			border-top: solid 1px #CCCCCC;
			border-left: solid 1px #CCCCCC;
			background-color: #FFFFFF;
		}
		.news_tab_area li.nav04{
			border-radius: 10px 10px 10px 10px;

			border-top: solid 1px #CCCCCC;
			border-left: solid 1px #CCCCCC;
			background-color: #FFFFFF;
		}
		.news_tab_area li.nav05{
			border-radius: 10px 10px 10px 10px;

			border-top: solid 1px #CCCCCC;
			border-left: solid 1px #CCCCCC;
			border-right: solid 1px #CCCCCC;
			background-color: #FFFFFF;
		}
		.news_tab_area li.selected {
			color:#000000;
			border-bottom: solid 1px #FFFFFF;
			/*
			background-color: #01911b70;
			*/
		}
		.news_tab_area a {
			border-radius: 10px 10px 10px 10px;

			text-align: center;
			/*display: flex;*/
			align-items: center;
			text-decoration: none;
			color: #000000;
			padding: 10px 5px 10px;
			width: 100%;
		}
		.news_tab_area li.selected a {
			border-radius: 10px 10px 10px 10px;

			color:#000000;
			/*
			color: #ffffff;
			background-color: #76A041;
			*/
		}
		/*85B64A*/
		.news_tab_area a:hover {
			color: #ffffff;
			background-color: #76A041;
		}
		.news_tab_area li.selected a:hover {
			color: #ffffff;
			background-color: #76A041;
		}
		.news{
			border-top: hidden;
			border-left:solid 1px #cccccc;
			border-right:solid 1px #cccccc;
			border-bottom:solid 1px #cccccc;
		}

		.input_keyword {
			width: 640px;
			height: 48px;
			font-size: 24px;
			margin-top: 20px;
			background-image: url(/img/btn/btn-search.png);
			background-repeat: no-repeat;
			border: 1px solid rgba(0,0,0,0.25);
			border-radius: 4px;
			padding-left: 60px;
			background: auto;
		}

		.tdc {
			word-break: break-word;
		}
	</style>
<?php
// 代替措置研修許可の場合は問題の商品IDを取得
$ethic_product_id = '';
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	$ethic_product_id = get_ethic_product_id();
}
//print("<!--[ethic_product_id:".$ethic_product_id."]-->");
?>
	<div class="news" style="height:340px;overflow-y: scroll;">
		<h2 style="vertical-align: top;">
			<img src="/img/top_icon_1.png" alt="お知らせ" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#2A7E15;">お知らせ</span>
			<div class="list_btn"><a href="<?php echo home_url(); ?>/archives/category/news" style="top"><img src="/img/list_btn01.png" alt="一覧" /></a></div>
		</h2>
		<div class="news_bd">
		<ul style="list-style:block;margin-top:0px;overflow: auto;padding-bottom: 10px;background-color: #ffffff;">
			<?php if( !empty($ethic_product_id) ){ ?>
				<!-- -->
				<li style="min-height: 18px;max-height: 50px;">
					<div style="display: inline-block;width: 100%;">
						<div style="display: block;width: 75px;float: left;">
							<img src="/img/news_icon01.png" alt="お知らせ" />
						</div>
						<!-- a class="" style="display:block;width:500px;float:left;" href="/product/detail.php?pid=<?php print($ethic_product_id); ?>">日弁連倫理研修はこちら</a -->
						<p class="" style="display:block;width:500px;float:left; color:#3b2707;font-weight:bold;">2026年度日弁連倫理研修（ｅラーニング）は開講準備中です。</p>
					</div>
					<br style="clear:both;" />
				</li>
				<!-- -->
			<?php } ?>

<?php
$objDbConnect = new DbConnect();
$ret = array();
$where = "";
//+++++++++++++++++++++++
$st_login_check = st_login_check();
$where.= " AND tbl_product.del_flg = '0'";
//$where.= " AND (tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL)";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
if (!$st_login_check){
	$where.= " AND tbl_product.product_type = '2'";
}
$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
//+++++++++++++++++++++++
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
//print("<!--[".date("Y-m-d H:i:s")."]-->");
//print("<!--[".$sql."]-->");
$ret = $objDbConnect->query_fetch_arr($sql);
//var_dump($ret);
if (!empty($ret)){
	foreach($ret as $val){
		?>
		<li style="min-height: 18px;/*max-height: 50px;*/overflow:hidden;">
			<?php if($val["topfit"]==0){ ?>
				<div style="display: inline-block;width: 100%;">
					<div style="display: block;width: 75px;float: left;">
						<?php
						//print("<!--[ID:".$val["ID"]."]-->");
						//print("<!--[topfit:".$val["topfit"]."]-->");
						//print("<!--[post_date:".$val["post_date"]."]-->");
						if ($val["status"] == 1){
							echo '<img src="/img/news_icon02.png" alt="更新情報" />';
						} else {
							echo '<img src="/img/news_icon01.png" alt="お知らせ" />';
						}
						?>
					</div>
					<div style="width: 100px;display: block;float: left;">
						<?php
						//print( date("Y/m/d", strtotime($val["post_date"])) );
						?>
					</div>
				</div>
				<div style="display: inline-block;width: 100%;">
					<div style="display: block;width: 75px;float: left;">
						<?php echo get_str_product_type_add($val["product_type_add"]); ?>
					</div>
					<?php if (intval($val["product_id"]) != 0){ ?>
						<a class="" style="display:block;width:500px;float:left;" href="/product/detail.php?pid=<?php echo (int)$val["product_id"]; ?>"><?php echo htmlspecialchars($val["post_title"], ENT_QUOTES, 'UTF-8'); ?></a>
					<?php } else { ?>
						<?php if ($val["url"] != ""){ ?>
							<a class="" style="display:block;width:500px;float:left;" href="<?php echo htmlspecialchars($val["url"], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($val["post_title"], ENT_QUOTES, 'UTF-8'); ?></a>
						<?php } else { ?>
							<a class="" style="display:block;width:500px;float:left;" href="/archives/<?php echo $val["ID"]; ?>"><?php echo $val["post_title"]; ?></a>
						<?php }  ?>
					<?php } ?>
				</div>
				<br style="clear:both;" />
			<?php } else { ?>
				<div style="display: inline-block;width: 100%;">
					<div style="display: block;width: 75px;float: left;">
						<?php
						//print("<!--[ID:".$val["ID"]."]-->");
						//print("<!--[topfit:".$val["topfit"]."]-->");
						//print("<!--[post_date:".$val["post_date"]."]-->");
						if ($val["status"] == 1){
							echo '<img src="/img/news_icon02.png" alt="更新情報" />';
						} else {
							echo '<img src="/img/news_icon01.png" alt="お知らせ" />';
						}
						?>
					</div>
					<?php if (intval($val["product_id"]) != 0){ ?>
						<a class="" style="display:block;width:500px;float:left;" href="/product/detail.php?pid=<?php echo (int)$val["product_id"]; ?>"><?php echo htmlspecialchars($val["post_title"], ENT_QUOTES, 'UTF-8'); ?></a>
					<?php } else { ?>
						<?php if ($val["url"] != ""){ ?>
							<a class="" style="display:block;width:500px;float:left;" href="<?php echo htmlspecialchars($val["url"], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($val["post_title"], ENT_QUOTES, 'UTF-8'); ?></a>
						<?php } else { ?>
							<a class="" style="display:block;width:500px;float:left;" href="/archives/<?php echo $val["ID"]; ?>"><?php echo $val["post_title"]; ?></a>
						<?php }  ?>
					<?php } ?>
				</div>
				<br style="clear:both;" />
			<?php } ?>
		</li>
		<?php
	}
}
?>
			<!--
			<?php
			/*
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$tmp_post = $posts;
			$posts = get_posts('numberposts=-1&category_name=news');
			$news_count = 0;
			$limit = 8; // 表示件数
			if($posts): foreach($posts as $post): setup_postdata($post);
				if($news_count == 0){
					// 受講対象の弁護士会に所属していないユーザーの場合は表示しない
					if ($post->product_type_add == 2 && stripos($post->target, '|'.$_SESSION['user']['bar_association_id'].'|') === false){
					} else {
						?>
						<li style="min-height: 18px;max-height: 50px;overflow:hidden;">
							<div style="display: inline-block;width: 100%;height: 14px;">
								<div style="display: block;width: 75px;float: left;">
									<?php
									if ($post->status == 1){
										echo '<img src="/img/news_icon02.png" alt="更新情報" />';
									} else {
										echo '<img src="/img/news_icon01.png" alt="お知らせ" />';
									}
									?>
								</div>
								<?php if (intval($post->product_id) != 0){ ?>
									<a class="" style="display:block;width:600px;float:left;" href="/product/detail.php?pid=<?php echo $post->product_id; ?>"><?php echo get_the_title(); ?></a>
								<?php } else { ?>
									<a class="" style="display:block;width:600px;float:left;" href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
								<?php } ?>
							</div>
							<br style="clear:both;" />
						</li>
						<?php
						$news_count++;
						if ($news_count == $limit){
							break;
						}
					}
				} else {
					// 受講対象の弁護士会に所属していないユーザーの場合は表示しない
					if ($post->product_type_add == 2 && stripos($post->target, '|'.$_SESSION['user']['bar_association_id'].'|') === false){
					} else {
						?>
						<li style="min-height: 30px;max-height: 48px;overflow:hidden;">
							<div style="display: inline-block;width: 100%;height: 14px;">
								<div style="display: block;width: 75px;float: left;">
									<?php
									if ($post->status == 1){
										echo '<img src="/img/news_icon02.png" alt="更新情報" />';
									} else {
										echo '<img src="/img/news_icon01.png" alt="お知らせ" />';
									}
									?>
								</div>
								<div style="width: 120px;display: block;float: left;">
									<?php
									if( (int)get_the_date('Y')>=2030 ){
									} else {
										echo the_time('Y/m/d');
									}
									?>
								</div>
							</div>
							<div style="display: inline-block;width: 100%;">
								<div style="display: block;width: 75px;float: left;">
									<?php echo get_str_product_type_add($post->product_type_add); ?>
								</div>
								<?php if (intval($post->product_id) != 0){ ?>
									<a class="" style="display:block;width:600px;float:left;" href="/product/detail.php?pid=<?php echo $post->product_id; ?>"><?php echo get_the_title(); ?></a>
								<?php } else { ?>
									<a class="" style="display:block;width:600px;float:left;" href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
								<?php } ?>
							</div>
							<br style="clear:both;" />
						</li>
						<?php
						$news_count++;
						if ($news_count == $limit){
							break;
						}
					}
				}
			endforeach; endif;
			$posts=$tmp_post;
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			*/
			?>
			-->
			<li style="min-height: 30px;max-height: 48px;overflow:hidden;">
				<div style="float:right;"><a href="#header">トップに戻る</a></div>
			</li>
		</ul>
		</div>
	</div>
	<?php
	// おすすめ講座
	include "product_recommend2.php";
	?>
	<?php
	// 新着eラーニング
	include "product_new_elearning.php";
	?>
	<?php
	// 受付中のライブ実務研修
	include "product_live_training.php";
	?>
	
	<?php
	// 弁護士会主催研修講座
	include "product_bar_association_live_other.php";
	?>
	<?php
	// 受講中の講座
	//include "product_now.php";
	?>
	<div class="news" style="min-height: 20px;position: relative;" id="top_product_now">
		<div class="loading"><div class="spinner"></div> 読み込み中...</div>
	</div>
	<script>
		$(document).ready(function() {
			$("a[href^='#']").on("click", function(event) {
				event.preventDefault(); // デフォルトの動作をキャンセル
				var target = $(this.getAttribute("href")); // クリックしたリンクのターゲットIDを取得

				if (target.length) {
					$("html, body").animate({
						scrollTop: target.offset().top
					}, 500); // 0.5秒かけてスクロール
				}
			});
		});
		$(document).ready(function() {
			$.ajax({
				url: "/top_product_now.php", // 取得するHTMLのURL
				type: "GET", // HTTPメソッド（通常GET）
				dataType: "html", // データの種類（HTML）
				success: function(response) {
					$("#top_product_now").empty();
					$("#top_product_now").append(response); // 取得したHTMLを追加
				},
				error: function(xhr, status, error) {
					console.error("エラー:", error);
				}
			});
		});
	</script>
	<hr>
	<style type="text/css">
	.news{
		margin-top:20px;
		border-top: solid 1px #cccccc;
		border-left:solid 1px #cccccc;
		border-right:solid 1px #cccccc;
		border-bottom:solid 1px #cccccc;
	}
	.product_new_elearning{
		margin-top:20px;
		/*display:none;*/
		border-top: solid 1px #cccccc;
		border-left:solid 1px #cccccc;
		border-right:solid 1px #cccccc;
		border-bottom:solid 1px #cccccc;
	}
	.product_live_training{
		margin-top:20px;
		/*display:none;*/
		border-top: solid 1px #cccccc;
		border-left:solid 1px #cccccc;
		border-right:solid 1px #cccccc;
		border-bottom:solid 1px #cccccc;
	}
	.product_bar_association_live_other{
		margin-top:20px;
		/*display:none;*/
		border-top: solid 1px #cccccc;
		border-left:solid 1px #cccccc;
		border-right:solid 1px #cccccc;
		border-bottom:solid 1px #cccccc;
	}
	.product_now{
		margin-top:20px;
		/*display:none;*/
		border-top: solid 1px #cccccc;
		border-left:solid 1px #cccccc;
		border-right:solid 1px #cccccc;
		border-bottom:solid 1px #cccccc;
	}
	</style>



<!--
	
	<div style="text-align:center;padding-bottom:10px;">
		<a href="/training-guide/"><img src="/img/guide_bnr_btn.png" alt="研修ガイド・一覧から探す「研修ステップアップガイド」(PDF)などを掲載" /></a>
	</div>
	
	<div style="text-align:center;">
		<a href="/search/"><img src="/img/search_bnr_btn.png" alt="条件を選択して検索" /></a>
	</div>
	
-->
	<?php // ^^フリーHTMLここ ?>
	<?php 
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$tmp_post = $posts;
	$posts = get_posts('numberposts=1&category_name=free_html_top2');
	if($posts): foreach($posts as $post): setup_postdata($post);
	?>
	<div style="width:500px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;margin-bottom:15px;">
	<?php echo $post->post_content ?>
	</div>
	<?php 
	endforeach; endif;
	$posts=$tmp_post;
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	?>

	<?php // ^^フリーHTMLここ ?>
	<?php 
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$tmp_post = $posts;
	$posts = get_posts('numberposts=1&category_name=free_html_top3');
	if($posts): foreach($posts as $post): setup_postdata($post);
	?>
	<div style="width:500px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;">
	<?php echo $post->post_content ?>
	</div>
	<?php 
	endforeach; endif;
	$posts=$tmp_post;
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	?>
	
	
	<div id="content" role="main">
	<?php
	/* Run the loop to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-index.php and that will be used instead.
	 */
	 //get_template_part( 'loop', 'index' );
	?>
	</div><!-- #content -->
</div><!-- #container -->

<?php
//<div style="width:230px;float:right;">
//	<?php //get_sidebar(2); 
//	<?php //get_sidebar(3); 
//	<?php //get_sidebar(4); 
//</div>
?>

<?php get_footer(); ?>
