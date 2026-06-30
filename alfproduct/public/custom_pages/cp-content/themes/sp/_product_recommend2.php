<?php
$objDbConnect = new DbConnect();

$arr_list = array();

$sql = "SELECT";
$sql.= "  *";
$sql.= " FROM";
$sql.= "  tbl_product AS T1";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_elearning AS T2";
$sql.= "      ON T1.product_id = T2.product_id";
$sql.= " WHERE";
$sql.= "  T1.del_flg = 0";
$sql.= "  AND ( T2.product_flg LIKE '%|1|%' OR T2.product_flg LIKE '%|3|%' ) ";
$sql.= "  AND (  (T1.start_date<='".date("Y-m-d")."' and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date<='".date("Y-m-d")."' and T1.end_date IS NULL)  OR  (T1.start_date IS NULL and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date IS NULL and T1.end_date IS NULL)  ) ";
$sql.= " ORDER BY rand()";
$sql.= " LIMIT 8";
$arr_list = $objDbConnect->query_fetch_arr($sql);

$mtb_product_flg = get_mtb_product_flg_icon();
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
				foreach($arr_list as $val){
					?>
					<li style="height:30px;overflow:hidden;">
						<div>
							<div style="width:75px;display:inline-block;">
								<?php
								// アイコン
								$icon = '';
								$arr_icon = array();
								$arr_icon = explode('|', trim($val['product_flg'], '|'));
								$count = 1;
								$all_count = count($arr_icon);
								foreach ($arr_icon as $icon){
									if ($mtb_product_flg[$icon]['icon'] == 'status002.png'){
										if (file_exists(NICHIBENREN_FRONT_ROOT_DIR . 'img/' . $mtb_product_flg[$icon]['icon'])){
											echo '<img src="/img/' . $mtb_product_flg[$icon]['icon'] . '" alt="' . $mtb_product_flg[$icon]['name'] . '" />';
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
							<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>" style="text-decoration:none;color:#3b2707;font-weight:bold;">
								<?php if(mb_strlen($val['product_name'], 'UTF-8')>48){ echo mb_substr($val['product_name'],0,48,"UTF-8")."..."; } else { echo $val['product_name']; } ?>
							</a>
						</div>
						<br style="clear:both;" />
					</li>
					<?php
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
				<div style="float:right;"><a href="#header">トップに戻る</a></div>
			</li>
		</ul>
	</div>
</div>
