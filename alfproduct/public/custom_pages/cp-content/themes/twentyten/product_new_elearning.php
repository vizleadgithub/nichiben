<?php
$objDbConnect = new DbConnect();

$ret = array();
$sql = "SELECT";
$sql.= "  *";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_add";
$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_elearning";
$sql.= "    ON tbl_product.product_id = tbl_product_elearning.product_id";

$sql.= " WHERE ";
$sql.= "  tbl_product.del_flg=0 ";
$sql.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
//$sql.= " AND ( DATE_ADD(tbl_product.regist_date, INTERVAL 1 MONTH) >= '".date("Y-m-d")."' OR DATE_ADD(tbl_product.update_date, INTERVAL 1 MONTH) >= '".date("Y-m-d")."') ";
$sql.= " AND DATE_ADD(tbl_product.regist_date, INTERVAL 1 MONTH) >= '".date("Y-m-d")."' ";
$sql.= " AND tbl_product_add.product_type_add=1 ";
if (!st_login_check()){
	$sql.= " AND tbl_product.product_type=2 ";
}

$sql.= " ORDER BY rand()";
$sql.= " LIMIT 8";
$arr_list = $objDbConnect->query_fetch_arr($sql);
$mtb_product_flg = get_mtb_product_flg_icon();
?>
<div class="news" style="/*height:340px;*/">
	<h2 id="page_area2">
		<img src="/img/top_icon_3.png" alt="新着eラーニング" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#2A7E15;">新着eラーニング</span>
		<div class="list_btn"><a href="/product/list_new_training.php" style="top"><img src="/img/list_btn01.png" alt="一覧" /></a></div>
	</h2>
	<div class="news_bd">
		<ul style="list-style:block;margin-top:0px;overflow: auto;/*height: 480px;*/background-color: #ffffff;">
			<?php
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if (!empty($arr_list)){ ?>
				<?php
				foreach($arr_list as $val){
					?>
					<li style="min-height: 18px;max-height: 50px;overflow:hidden;">
						<div>
							<div style="width:75px;display:inline-block;float:left;">
								<img src="/img/status001.png" alt="NEW" />
							</div>
							<a class="textOverflowTest4" style="display:inline-block;text-decoration:none;color:#3b2707;font-weight:bold;float:left;width:630px;overflow: hidden;max-height: 36px;" href="/product/detail.php?pid=<?php echo $val['product_id']; ?>">
								<?php echo $val['product_name']; ?>
							</a>
						</div>
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
				<div style="float:right;margin-right:10px;"><a href="#header">トップに戻る</a></div>
			</li>
		</ul>
	</div>
</div>
