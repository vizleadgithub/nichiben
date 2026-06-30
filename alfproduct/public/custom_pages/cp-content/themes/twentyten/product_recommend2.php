<?php
//+++++++++++++++++++++++++++++++++++++++++
$objDbConnect = new DbConnect();
$arr_list = array();
//+++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "  T1.* ";
$sql.= " ,T2.product_flg ";
$sql.= "FROM ";
$sql.= " tbl_product AS T1 ";
$sql.= " LEFT JOIN tbl_product_elearning AS T2 ON T1.product_id = T2.product_id ";
$sql.= "WHERE ";
$sql.= " T1.del_flg = 0 ";
$sql.= " AND (   ( T2.product_flg LIKE '%|3|%'  AND (  (T1.start_date<='".date("Y-m-d")."' and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date<='".date("Y-m-d")."' and T1.end_date IS NULL)  OR  (T1.start_date IS NULL and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date IS NULL and T1.end_date IS NULL)  )  )   ) ";
//$sql.= "ORDER BY ";
//$sql.= "  rand() ";
$sql.= "ORDER BY ";
$sql.= "  T1.product_id DESC ";
$sql.= "LIMIT 8 ";
$arr_list = $objDbConnect->query_fetch_arr($sql);
$arr_pids = array();
if( count($arr_list)>0 ){
} else {
	$arr_list = array();
}
foreach($arr_list as $val){
	$arr_pids[] = $val["product_id"];
}
//+++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "  T3.* ";
$sql.= " ,T4.product_flg ";
$sql.= "FROM ";
$sql.= " tbl_product AS T3 ";
$sql.= " LEFT JOIN tbl_product_elearning AS T4 ON T3.product_id = T4.product_id ";
$sql.= "WHERE ";
$sql.= " T3.del_flg = 0 ";
$sql.= " AND (   ( T4.product_flg LIKE '%|1|%'  AND (  (T3.start_date<='".date("Y-m-d")."' and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date<='".date("Y-m-d")."' and T3.end_date IS NULL)  OR  (T3.start_date IS NULL and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date IS NULL and T3.end_date IS NULL)  )  )   ) ";
if( count($arr_pids)>0 ){
	$sql.= " AND T3.product_id NOT IN (".implode(",",$arr_pids).") ";
}
$sql.= "ORDER BY ";
$sql.= "  rand() ";
$sql.= "LIMIT 8 ";
$arr_list2 = $objDbConnect->query_fetch_arr($sql);
foreach($arr_list2 as $val){
	$arr_list[] = $val;
}
//+++++++++++++++++++++++++++++++++++++++++
$mtb_product_flg = get_mtb_product_flg_icon();
//+++++++++++++++++++++++++++++++++++++++++
?>
<div class="news" style="/*height:340px;*/">
	<h2 id="page_area1">
		<img src="/img/top_icon_2.png" alt="おすすめｅラーニング" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#F48C17;">おすすめｅラーニング</span>
		<div class="list_btn"><a href="/product/list_recommend.php" style="top"><img src="/img/list_btn01.png" alt="一覧" /></a></div>
	</h2>
	<div class="news_bd">
		<ul style="list-style:block;margin-top:0px;overflow: auto;/*height: 480px;*/background-color: #ffffff;">
			<?php
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if (!empty($arr_list)){ ?>
				<?php
				$list_count = 0;
				foreach($arr_list as $val){
					if($list_count < 8){
						$list_count += 1;
						?>
						<li style="min-height: 36px;max-height: 48px;overflow:hidden;">
							<div>
								<div style="width:75px;display:inline-block;float:left;">
									<?php
									// アイコン
									$icon_count = 0;
									$icon = '';
									$arr_icon = array();
									$arr_icon = explode('|', trim($val['product_flg'], '|'));
									$count = 1;
									$all_count = count($arr_icon);
									foreach ($arr_icon as $icon){
										if ($mtb_product_flg[$icon]['icon'] == 'status002.png' && $icon_count == 0){
											if (file_exists(NICHIBENREN_FRONT_ROOT_DIR . 'img/' . $mtb_product_flg[$icon]['icon'])){
												echo '<img src="/img/' . $mtb_product_flg[$icon]['icon'] . '" alt="' . $mtb_product_flg[$icon]['name'] . '" />';
												$icon_count = 1;
												//$cal = $count % 2;
												//if ($cal == 0){
													//echo '<br />';
												//}
												//$count++;
											}
										}
									}
									if ($all_count == 0){
										echo '&nbsp;';
									}
									?>
								</div>
								<a style="display:inline-block;text-decoration:none;color:#3b2707;font-weight:bold;float:left;width:630px;overflow: hidden;max-height: 36px;" class="textOverflowTest4" href="/product/detail.php?pid=<?php echo $val['product_id']; ?>">
									<?php echo htmlspecialchars($val['product_name'], ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</div>
						</li>
						<?php
					}
				}
				?>
			<?php } else { ?>
				<li style="height:30px;overflow:hidden;">
					<div>
						<div style="width:75px;display:inline-block;"></div>
						該当する講座はありません。
					</div>
					<br style="clear:both;" />
				</li>
			<?php }
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			?>
			<li style="height:30px;overflow:hidden;">
				<div style="float:right;margin-right:10px;"><a href="#header">トップに戻る</a></div>
			</li>
		</ul>
	</div>
</div>
