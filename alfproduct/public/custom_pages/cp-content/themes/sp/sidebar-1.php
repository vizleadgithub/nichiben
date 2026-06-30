<?php
//require_once "/srv/alfproduct/module/module.php";
/**
 * 商品カテゴリ
 */
$ethic_product_id = '';
//print( "<!--[sub_auth_ethic_training:".$_SESSION['user']['sub_auth_ethic_training']."]-->" );
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	$ethic_product_id = get_ethic_product_id();
}


$productcategory_list = get_product_category();
?>

<div class="lc_st">
	<h3></h3>
	<?php if (empty($productcategory_list)){ ?>
		<?php // 商品カテゴリが登録されていません。 ?>
	<?php } else { ?>
		<?php
		$id_data = 0;
		$id_temp = "";
		foreach ($productcategory_list as $val){
			if( $val['big']['term_id']==$_GET["pcid"] ){
				$id_temp = trim($id_data);
			}
			foreach ($val['small'] as $val2){
				if( $val2['term_id']==$_GET["pcid"] ){
					$id_temp = trim($id_data);
				}
			}
			$id_data = $id_data + 1;
		}
		//print("<!--[id_temp:".$id_temp."]-->");
		?>


		<?php if($ethic_product_id!=''){ ?>
			<!--
			<div onClick="javascript:window.location='/product/detail.php?pid=<?php print($ethic_product_id); ?>'" onmouseover="BigCatBacIn0('rinri')" onmouseout="BigCatBacOut0('rinri')" style="display: block;width: 210px;height: 40px;overflow: hidden;">
				<div id="menu__rinri" style="display:block;cursor: pointer; background-image: url( /img/l_cateback_blue_on.png );position: absolute;width: 210px;height:40px;overflow: hidden;" class="lc_bs_blue">
					<img src="/img/c_ar_blue_off_w.png" id="image<?php echo $id_data ?>" style="z-index:-1;">
				</div>
				<div class="lc_mastitle" id="menu_big_fontrinri" >倫理研修</div>
			</div>
			-->
		<?php } ?> 

		<?php $id_data = 0 ?>
		<?php $menu_big = 0 ?>

		<?php foreach ($productcategory_list as $val){ ?>
			<?php if ($val['big']['term_id']=="519"){ ?>
				<div onClick="menuAco2(<?php echo $id_data ?>)" onmouseover="BigCatBacIn2(<?php echo $menu_big ?>)" onmouseout="BigCatBacOut2(<?php echo $menu_big ?>)" style="display: block;width: 210px;height: 40px;overflow: hidden;">
					<div id="menu_big<?php echo $menu_big ?>" style="display:block;cursor: pointer; background-image: url( /img/l_cateback_blue_on.png );position: absolute;width: 210px;height:40px;overflow: hidden;" class="lc_bs_blue">
						<img src="/img/c_ar_blue_off_w.png" id="image<?php echo $id_data ?>" style="z-index:-1;">
					</div>
					<div class="lc_mastitle" id="menu_big_font<?php echo $menu_big ?>" ><?php echo $val['big']['name']; ?></div>
				</div>
				<ol id="menu<?php echo $id_data ?>"
						 style="display: none;clear:both;border-top:1px solid #eeeeee;border-left:1px solid #eeeeee;border-right:1px solid #eeeeee;background:#ffffff;width:206px;margin-top:0;">
					<li>
						<ul>
							<li class="lc_subtitle_2" id="category<?php echo $val['big']['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val['big']['term_id']; ?>" id="category<?php echo $val['big']['term_id']; ?>_a">すべて</a></li>
							<?php foreach ($val['small'] as $val2){ ?>
								<li class="lc_subtitle_2" id="category<?php echo $val2['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val2['term_id']; ?>" id="category<?php echo $val2['term_id']; ?>_a"><?php echo $val2['name']; ?></a></li>
							<?php } ?>
						</ul>
					</li>
				</ol>
			<?php } ?>
			<?php $menu_big += 1 ?>
			<?php $id_data = $id_data + 1 ?>
		<?php } ?>


		<?php foreach ($productcategory_list as $val){ ?>
			<?php 
			//print("<!--");
			//var_dump($val);
			//print("-->");
			?>
			<?php if ($val['big']['term_group']=="0"){ ?>
				<?php if ($val['big']['term_id']!="300" && $val['big']['term_id']!="519"){ ?>
					<div  onClick="menuAco1(<?php echo $id_data ?>)" onmouseover="BigCatBacIn0(<?php echo $menu_big ?>)" onmouseout="BigCatBacOut0(<?php echo $menu_big ?>)" style="display: block;width: 210px;height: 40px;overflow: hidden;">
						<div id="menu_big<?php echo $menu_big ?>" style="display:block;cursor: pointer;background-image: url(/img/l_cateback_orange_on.png);position: absolute;width: 210px;height:40px;overflow: hidden;" class="lc_bs_orange">
							<img src="/img/c_ar_orange_off_w.png" id="image<?php echo $id_data ?>" style="z-index:-1;">
						</div>
						<div class="lc_mastitle" id="menu_big_font<?php echo $menu_big ?>" ><?php echo $val['big']['name']; ?></div>
					</div>
					<ol id="menu<?php echo $id_data ?>" style="display: none; clear:both; border-top:1px solid #eeeeee; border-left:1px solid #eeeeee; border-right:1px solid #eeeeee; background:#ffffff; width:206px;margin-top:0;">
						<li>
							<ul>
									<li class="lc_subtitle_0" id="category<?php echo $val['big']['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val['big']['term_id']; ?>" id="category<?php echo $val['big']['term_id']; ?>_a">すべて</a></li>
								<?php foreach ($val['small'] as $val2){ ?>
									<li class="lc_subtitle_0" id="category<?php echo $val2['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val2['term_id']; ?>" id="category<?php echo $val2['term_id']; ?>_a"><?php echo $val2['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
					</ol>
				<?php } ?>
			<?php } elseif ($val['big']['term_group']=="1"){ ?>
				<?php if ($val['big']['term_id']!="300" && $val['big']['term_id']!="519"){ ?>
					<div onClick="menuAco1(<?php echo $id_data ?>)" onmouseover="BigCatBacIn1(<?php echo $menu_big ?>)" onmouseout="BigCatBacOut1(<?php echo $menu_big ?>)" style="display: block;width: 210px;height: 40px;overflow: hidden;">
						<div id="menu_big<?php echo $menu_big ?>" style="display:block;cursor:pointer;background-image:url(/img/l_cateback_green_on.png);position: absolute;width: 210px;height:40px;overflow: hidden;" class="lc_bs_green">
							<img src="/img/c_ar_green_off_w.png" id="image<?php echo $id_data ?>" style="z-index:-1;">
						</div>
						<div class="lc_mastitle" id="menu_big_font<?php echo $menu_big ?>" ><?php echo $val['big']['name']; ?></div>
					</div>
					<ol id="menu<?php echo $id_data ?>" style="display: none; clear:both; border-top:1px solid #eeeeee; border-left:1px solid #eeeeee; border-right:1px solid #eeeeee; background:#ffffff; width:206px;margin-top:0;">
						<li>
							<ul>
								<li class="lc_subtitle_1" id="category<?php echo $val['big']['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val['big']['term_id']; ?>" id="category<?php echo $val['big']['term_id']; ?>_a">すべて</a></li>
								<?php foreach ($val['small'] as $val2){ ?>
									<li class="lc_subtitle_1" id="category<?php echo $val2['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val2['term_id']; ?>" id="category<?php echo $val2['term_id']; ?>_a"><?php echo $val2['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
					</ol>
				<?php } ?>
			<?php } else { ?>
				<?php if ($val['big']['term_id']!="300" && $val['big']['term_id']!="519"){ ?>
					<div onClick="menuAco2(<?php echo $id_data ?>)" onmouseover="BigCatBacIn2(<?php echo $menu_big ?>)" onmouseout="BigCatBacOut2(<?php echo $menu_big ?>)" style="display: block;width: 210px;height: 40px;overflow: hidden;">
						<div id="menu_big<?php echo $menu_big ?>" style="display:block;cursor: pointer; background-image: url( /img/l_cateback_blue_on.png );position: absolute;width: 210px;height:40px;overflow: hidden;" class="lc_bs_blue">
							<img src="/img/c_ar_blue_off_w.png" id="image<?php echo $id_data ?>" style="z-index:-1;">
						</div>
						<div class="lc_mastitle" id="menu_big_font<?php echo $menu_big ?>" ><?php echo $val['big']['name']; ?></div>
					</div>
					<ol id="menu<?php echo $id_data ?>"
							 style="display: none;clear:both;border-top:1px solid #eeeeee;border-left:1px solid #eeeeee;border-right:1px solid #eeeeee;background:#ffffff;width:206px;margin-top:0;">
						<li>
							<ul>
								<li class="lc_subtitle_2" id="category<?php echo $val['big']['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val['big']['term_id']; ?>" id="category<?php echo $val['big']['term_id']; ?>_a">すべて</a></li>
								<?php foreach ($val['small'] as $val2){ ?>
									<li class="lc_subtitle_2" id="category<?php echo $val2['term_id']; ?>"><a href="/product/list.php?pcid=<?php echo $val2['term_id']; ?>" id="category<?php echo $val2['term_id']; ?>_a"><?php echo $val2['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
					</ol>
				<?php } ?>
			<?php } ?>
			<?php $menu_big += 1 ?>
			<?php $id_data = $id_data + 1 ?>
		<?php } ?>
	<?php } ?>
	<div onClick="javascript:window.location='/product/list.php?pcid=all';" onmouseover="BigCatBacIn0('all')" onmouseout="BigCatBacOut0('all')" style="display: block;width: 210px;height: 40px;overflow: hidden;">
		<div id="menu_bigall" style="display:block;cursor: pointer;background-image: url( /img/l_cateback_orange_on.png );position: absolute;width: 210px;height:40px;overflow: hidden;<?php if (strpos($_SERVER["REQUEST_URI"], '/product/list.php?pcid=all') !== false){ ?>opacity:1;<?php } else { ?>opacity: 0.6;<?php } ?>" class="lc_bs_orange">
			<img src="/img/c_ar_orange_off_w.png" id="image_all" style="z-index:-1;">
		</div>
		<div class="lc_mastitle" id="menu_big_font_all" >全講座一覧</div>
	</div>
	<ol id="menuall" style="display:none;">
	</ol>
</div>
<style type="text/css">
	.lc_subtitle_0{
		/*background: url(/img/lc_ar_hh_w.png) no-repeat 6%;*/
		width: 180px;
		margin: 0 auto;
		list-style: none;
	}
	.lc_subtitle_0 a{
		margin-top: 0px;
		padding:5px 0px 5px 20px;
		color:#412d11;
		text-decoration: none;
		font-size:12px;
		display:block;
	}
	.lc_subtitle_0 a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #FF9C17;
		/*opacity:0.6;*/
		opacity:1;
	}
	.lc_subtitle_0.select_menu a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #FF9C17;
		opacity:1;
	}


	.lc_subtitle_1{
		/*background: url(/img/lc_ar_hh_w.png) no-repeat 6%;*/
		width: 180px;
		margin: 0 auto;
		list-style: none;
	}
	.lc_subtitle_1 a{
		margin-top: 0px;
		padding:5px 0px 5px 20px;
		color:#412d11;
		text-decoration: none;
		font-size:12px;
		display:block;
	}
	.lc_subtitle_1 a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #8dbd55;
		/*opacity:0.6;*/
		opacity:1;
	}
	.lc_subtitle_1.select_menu a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #8dbd55;
		opacity:1;
	}


	.lc_subtitle_2{
		/*background: url(/img/lc_ar_hh_w.png) no-repeat 6%;*/
		width: 180px;
		margin: 0 auto;
		list-style: none;
	}
	.lc_subtitle_2 a{
		margin-top: 0px;
		padding:5px 0px 5px 20px;
		color:#412d11;
		text-decoration: none;
		font-size:12px;
		display:block;
	}
	.lc_subtitle_2 a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #61A6C3;
		/*opacity:0.6;*/
		opacity:1;
	}
	.lc_subtitle_2.select_menu a:hover {
		color:#ffffff;
		/*background: url(/img/lc_ar_off_w.png) no-repeat 6%;*/
		background-color: #61A6C3;
		opacity:1;
	}

	.lc_bs_orange{
		opacity:0.6;
		display: block;
		cursor: pointer;
		position: absolute;
		width: 220px;
	}
	.lc_bs_orange img {
		margin-right: 20px;
		float: right;
		margin-top: 13px;
	}
	.lc_bs_orange.select_menu{
		opacity:1;
	}
	.lc_bs_green{
		opacity:0.6;
		display: block;
		cursor: pointer;
		position: absolute;
		width: 220px;
	}
	.lc_bs_green img {
		margin-right: 20px;
		float: right;
		margin-top: 13px;
	}
	.lc_bs_green.select_menu{
		opacity:1;
	}
	.lc_bs_blue{
		opacity:0.6;
		display: block;
		cursor: pointer;
		position: absolute;
		width: 220px;
	}
	.lc_bs_blue img {
		margin-right: 20px;
		float: right;
		margin-top: 13px;
	}
	.lc_bs_blue.select_menu{
		opacity:1;
	}
	.lc_bs_all{
		opacity:0.6;
		display: block;
		cursor: pointer;
		position: absolute;
		width: 220px;
	}
	.lc_bs_all.select_menu{
		opacity:1;
	}

	.lc_mastitle {
		color: #000000;
		text-decoration: none;
		display: block;
		padding: 12px 0px 10px 13px;
		font-size: 14px;
		margin: 0px;
		z-index: 99999;
		position: relative;
		cursor: pointer;
		width: 172px;
		float: left;
		overflow: hidden;
	}
</style>
<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$tmp_post = $posts;
//$posts = get_posts('numberposts=1&category_name=free_html_top4');
//if($posts): foreach($posts as $post): setup_postdata($post);
?>
<div style="width:220px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;">
<?php //echo $post->post_content ?>
</div>
<?php 
//endforeach; endif;
//$posts=$tmp_post;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>