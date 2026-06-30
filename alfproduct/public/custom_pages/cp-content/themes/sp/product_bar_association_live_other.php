<?php
$objDbConnect = new DbConnect();

$where = " WHERE 1=1 ";
$where.= " AND tbl_product.del_flg='0'";
$where.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";

$where.= " AND tbl_product_add.product_type_add='2' ";
if (!st_login_check()){
} else {
	$where.= " AND tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' ";
}
$where.= " AND tbl_product_live_training.training_kind_flg='5' ";
//$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";

$sql = "";
$sql.= "
SELECT 
 * 
FROM 
 tbl_product 
 INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id 
 INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id 
 INNER JOIN (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA ON tbl_product.product_id = RPBA.product_id 
";
$sql.= $where;
$sql.= " ORDER BY rand()";
$sql.= " LIMIT 8";
$arr_list = $objDbConnect->query_fetch_arr($sql);
print("<!--[".$sql."]-->");
?>
<div class="news" style="/*height:340px;*/">
	<h2 id="page_area4">
		<img src="/img/top_icon_3.png" alt="弁護士会主催研修" /><span style="vertical-align: top;line-height: 30px;margin-left: 6px;color:#2A7E15;">弁護士会主催研修</span>
		<div class="list_btn"><a href="<?php echo home_url(); ?>/product/list_bar_association_live_other.php" style="top"><img src="/img/list_btn01.png" alt="一覧" /></a></div>
	</h2>
	<div class="news_bd">
		<ul style="list-style:block;margin-top:0px;overflow: auto;/*height: 480px;*/background-color: #ffffff;">
			<?php
			//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if (!empty($arr_list)){ ?>
				<?php
				foreach($arr_list as $val){
					?>
					<li style="min-height: 18px;max-height: 36px;overflow:hidden; display: block;">
						<div>
							<div style="width:120px;display:inline-block;float:left;">
								<?php
								if ($val['start_date']=='' && $val['end_date']==''){
									echo '未定';
								} elseif ($val['start_date']!='' && $val['end_date']==''){
									echo date('Y/m/d', strtotime($val['start_date'])).'～';
								} elseif ($val['start_date']=='' && $val['end_date']!=''){
									echo '～'.date('Y/m/d', strtotime($val['end_date']));
								} else {
									echo ''.date('Y/m/d', strtotime($val['start_date'])).'<br />～'.date('Y/m/d', strtotime($val['end_date']));
								}
								?>
							</div>
							<a class="textOverflowTest4" style="display:inline-block;text-decoration:none;color:#3b2707;font-weight:bold;float:left;width:580px;overflow: hidden;max-height: 36px;" href="/product/detail.php?pid=<?php echo $val['product_id']; ?>">
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
