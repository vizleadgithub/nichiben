<?php
$objDbConnect = new DbConnect();

$ret = array();
$sql = "SELECT";
$sql.= "  *";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= " WHERE";
$sql.= "  del_flg=0";
$sql.= " AND (  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";

if (!st_login_check()){
	$sql.= " AND product_type=2";
}
$sql.= " ORDER BY product_id DESC";
$sql.= " LIMIT 8";
$ret = $objDbConnect->query_fetch_arr($sql);
?>

<div style="clear:both;height:400px;" class="product_new">
<h2>新着講座</h2>
<?php if(empty($ret)){ ?>
新着講座はありません。
<?php } else { ?>
	<?php foreach($ret as $val){ ?>
		<div style="width:125px;height:160px;float:left;margin:0;text-align:center;">
			<div class="imagebd"><a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_image.php?image=<?php echo $val['thumbnail']; ?>&width=100&height=100" width="100%" alt="" /></a></div>
			<div class="name" style="width:100px;text-align:left;padding:5px;"><a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><?php if(mb_strlen($val['product_name'], 'UTF-8')>14){ echo mb_substr($val['product_name'],0,14,"UTF-8")."..."; } else { echo $val['product_name']; } ?></a></div>
		</div>
	<?php } ?>
<?php } ?>
</div>