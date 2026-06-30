<?php
$objDbConnect = new DbConnect();

$ret = array();
$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  tbl_product_add.product_type_add,";
$sql.= "  tbl_product_elearning.product_flg";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_add";
$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";
$sql.= "    LEFT JOIN";
$sql.= "  tbl_product_elearning";
$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id";
$sql.= "    LEFT JOIN";
$sql.= "  tbl_product_live_training";
$sql.= "      ON tbl_product.product_id = tbl_product_live_training.product_id";
$sql.= " WHERE";
$sql.= "  tbl_product.del_flg = 0";
$sql.= " AND ( tbl_product_add.product_type_add = 1 )";
$sql.= " AND ( (tbl_product_live_training.ethic_flg = 0 OR tbl_product_live_training.ethic_flg IS NULL) )";
$sql.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
$sql.= " AND ( tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL )";
$sql.= " AND ( (DATE_SUB(tbl_product.end_date, INTERVAL 1 MONTH) <= '".date("Y-m-d")."') )";
if (!st_login_check()){
	$sql.= " AND tbl_product.product_type=2";
}
$sql.= " ORDER BY rand()";
$sql.= " LIMIT 3";
$ret = $objDbConnect->query_fetch_arr($sql);

$mtb_product_flg = get_mtb_product_flg_icon();
?>

<div style="clear:both;height:350px;padding:20px 5px 20px 25px;background-color:#fcfcfc;margin-bottom:30px;" class="product_limit">
<h2>
<img src="/img/h2_end_nearness.png" alt="掲載終了間近の講座" />
<span style="position:relative;bottom:8px;left:180px;"><a href="/product/list_limit.php"><img src="/img/list_btn03.png" alt="一覧へ" /></a></span>
</h2>
<?php if (!empty($ret)){ ?>
	<?php foreach($ret as $val){ ?>
		<div style="width:155px;height:300px;float:left;margin:0;text-align:center;">
			<div class="imagebd">
				<?php
				$img_url = '';
				if ($val["thumbnail"] != ''){
					$img_url = "/resize_image.php?image=".$val["thumbnail"];
				} else {
					if ($val["product_type_add"] == 1){
						$arr_thumbnail = array();
						$arr_thumbnail = get_alf_thumbnail_db($val["contents_contents1"]);
						if ($arr_thumbnail){
							if ($arr_thumbnail["contents_thumbnail"][0]["p180"] != ''){
								$img_url = "/resize_video_image.php?image=".$val["contents_contents1"].$arr_thumbnail["contents_thumbnail"][0]["p180"];
							}
						}
					}
				}
				if ($img_url == ''){
					$img_url = "/resize_image.php?image=noimage.jpg";
				}
				?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="<?php echo $img_url; ?>&width=140&height=117" alt="" /></a>
			</div>
			<div class="name" style="width:140px;height:170px;text-align:left;padding:5px;overflow:auto;">
				<?php
				// カテゴリ
				$term_ids = array();
				if (isset($val['term_id'])){
					$term_ids = explode(',', $val['term_id']);
				}
				if (!empty($term_ids)){
					foreach ($term_ids as $term_id){
						$sql = "SELECT T1.name FROM wp_terms AS T1 LEFT JOIN wp_term_taxonomy AS T2 ON T1.term_id = T2.term_id WHERE T1.term_id = '$term_id' AND T2.parent = 21";
						$arr_wp_terms = $objDbConnect->query_fetch_arr($sql);
						if ($arr_wp_terms){
							echo '<div class="category_title_css">' . $arr_wp_terms[0]['name'] . '</div>';
						}
					}
				}
				
				// アイコン
				$icon = '';
				$arr_icon = array();
				$arr_icon = explode('|', trim($val['product_flg'], '|'));
				$count = 1;
				$all_count = count($arr_icon);
				foreach ($arr_icon as $icon){
					if ($mtb_product_flg[$icon]['icon'] != ''){
						if (file_exists(NICHIBENREN_FRONT_ROOT_DIR . 'img/' . $mtb_product_flg[$icon]['icon'])){
							echo '<img src="/img/' . $mtb_product_flg[$icon]['icon'] . '" alt="' . $mtb_product_flg[$icon]['name'] . '" />';
							$cal = $count % 2;
							if ($cal == 0){
								echo '<br />';
							}
							$count++;
						}
					}
				}
				if ($all_count == 0){
					echo '<br />';
				}
				?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>" style="text-decoration:none;color:#3b2707;font-weight:bold;">
				<?php if(mb_strlen($val['product_name'], 'UTF-8')>24){ echo mb_substr($val['product_name'],0,24,"UTF-8")."..."; } else { echo $val['product_name']; } ?><br />
				単品価格：<?php if($val['price'] == 0){echo '無料';}else{echo number_format($val['price'] + tax_cal_yen($val['price'])).'円';} ?><br />
				掲載期間：
				<?php
				if ($val['start_date']=='' && $val['end_date']==''){
					echo '未定';
				} elseif ($val['start_date']!='' && $val['end_date']==''){
					echo date('Y/m/d', strtotime($val['start_date'])).'～';
				} elseif ($val['start_date']=='' && $val['end_date']!=''){
					echo '～'.date('Y/m/d', strtotime($val['end_date']));
				} else {
					echo '<br />'.date('Y/m/d', strtotime($val['start_date'])).'<br />～'.date('Y/m/d', strtotime($val['end_date']));
				}
				?>
				</a>
			</div>
		</div>
	<?php } ?>
<?php } else { ?>
	<div style="position:relative;">
		<span style="position:absolute;top:130px;left:160px;">該当する講座はありません。</span>
	</div>
<?php } ?>
</div>
