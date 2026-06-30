<?php
$objDbConnect = new DbConnect();

$arr_list = array();

// ログイン済み
if (st_login_check()){
	$user_id = $_SESSION['user']['id'];
	
	// 購入済み商品のカテゴリ一覧取得
	$sql = "SELECT DISTINCT";
	$sql.= "  T2.product_id,";
	$sql.= "  T3.term_id";
	$sql.= " FROM";
	$sql.= "  tbl_order AS T1";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_order_detail AS T2";
	$sql.= "      ON T1.order_id = T2.order_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product AS T3";
	$sql.= "      ON T2.product_id = T3.product_id";
	$sql.= " WHERE";
	$sql.= "  T1.payment_status='2'";
	$sql.= "  AND (  (T3.start_date<='".date("Y-m-d")."' and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date<='".date("Y-m-d")."' and T3.end_date IS NULL)  OR  (T3.start_date IS NULL and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date IS NULL and T3.end_date IS NULL)  ) ";
	$sql.= "  AND T1.del_flg='0'";
	$sql.= "  AND T2.member_id='".$user_id."'";
	$sql.= "  AND T3.del_flg='0'";
	
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if ($ret){
		// 一意の購入済みproduct_id配列を作成
		$arr_product = array();
		// 一意のterm_id配列を作成
		$arr_category = array();
		
		$count = count($ret);
		for ($i=0; $i<$count; $i++){
			// 商品
			$arr_product[$ret[$i]['product_id']] = $ret[$i]['product_id'];
			
			// カテゴリ(単一の場合)
			if (strpos($ret[$i]['term_id'], ',') === false){
				$arr_category[$ret[$i]['term_id']] = $ret[$i]['term_id'];
				
			// カテゴリ(複数の場合)
			} else {
				$arr_term_id = explode(',', $ret[$i]['term_id']);
				foreach ($arr_term_id as $val){
					$arr_category[$val] = $val;
				}
			}
		}
		
		// カテゴリに属した未購入商品を取得
		$sql = "SELECT DISTINCT";
		$sql.= "  product_id,";
		$sql.= "  product_name,";
		$sql.= "  thumbnail";
		$sql.= " FROM";
		$sql.= "  tbl_product";
		$sql.= " WHERE";
		$sql.= "  (";
		$cnt = 1;
		$count = count($arr_product);
		foreach ($arr_product as $val){
			$sql.= " product_id<>'".$val."'";
			if ($cnt != $count){
				$sql.= " OR";
			}
			$cnt++;
		}
		$sql.= "  ) AND (";
		$cnt = 1;
		$count = count($arr_category);
		foreach ($arr_category as $val){
			$sql.= " CONCAT(',',term_id,',') LIKE '%,".$val.",%'";
			if ($cnt != $count){
				$sql.= " OR";
			}
			$cnt++;
		}
		$sql.= "  )";
		$sql.= "  AND del_flg='0'";
		$sql.= "  AND (  (start_date<='".date("Y-m-d")."' and end_date>='".date("Y-m-d")."')  OR  (start_date<='".date("Y-m-d")."' and end_date IS NULL)  OR  (start_date IS NULL and end_date>='".date("Y-m-d")."')  OR  (start_date IS NULL and end_date IS NULL)  ) ";
		$sql.= " ORDER BY rand()";
		$sql.= " LIMIT 8";
		
		$arr_list = $objDbConnect->query_fetch_arr($sql);

		if( count($arr_list)<8 ){
			// 売上商品をランダムで表示
			$sql = "SELECT DISTINCT";
			$sql.= "  T1.product_id,";
			$sql.= "  T1.product_name,";
			$sql.= "  T1.thumbnail";
			$sql.= " FROM";
			$sql.= "  tbl_product AS T1";
			$sql.= "    LEFT JOIN";
			$sql.= "  tbl_order_detail AS T2";
			$sql.= "      ON T1.product_id = T2.product_id";
			$sql.= " WHERE";
			$sql.= "  T1.del_flg='0'";
			$sql.= "  AND (  (T1.start_date<='".date("Y-m-d")."' and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date<='".date("Y-m-d")."' and T1.end_date IS NULL)  OR  (T1.start_date IS NULL and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date IS NULL and T1.end_date IS NULL)  ) ";
			$sql.= "  and T2.order_detail_id is null ";
			foreach ($arr_list as $val){
				$sql.= " and T1.product_id<>'".$val["product_id"]."'";
			}
			$sql.= " ORDER BY rand()";
			$sql.= " LIMIT 8";
			$arr_list2 = $objDbConnect->query_fetch_arr($sql);
			foreach($arr_list2 as $val){
				if( count($arr_list)<8 ){
					$arr_list[] = $val;
				}
			}
		}

	} else {
		// 売上商品をランダムで表示
		$sql = "SELECT DISTINCT";
		$sql.= "  T1.product_id,";
		$sql.= "  T1.product_name,";
		$sql.= "  T1.thumbnail";
		$sql.= " FROM";
		$sql.= "  tbl_product AS T1";
		$sql.= "    LEFT JOIN";
		$sql.= "  tbl_order_detail AS T2";
		$sql.= "      ON T1.product_id = T2.product_id";
		$sql.= " WHERE";
		$sql.= "  T1.del_flg='0'";
		$sql.= "  AND (  (T1.start_date<='".date("Y-m-d")."' and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date<='".date("Y-m-d")."' and T1.end_date IS NULL)  OR  (T1.start_date IS NULL and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date IS NULL and T1.end_date IS NULL)  ) ";
		$sql.= "  AND T2.member_id<>'".$user_id."'";
		$sql.= " ORDER BY rand()";
		$sql.= " LIMIT 8";
		
		$arr_list = $objDbConnect->query_fetch_arr($sql);
	}
	
// 未ログイン
} else {
	// 売上商品をランダムで表示(一般公開のみ)
	$sql = "SELECT DISTINCT";
	$sql.= "  T1.product_id,";
	$sql.= "  T1.product_name,";
	$sql.= "  T1.thumbnail";
	$sql.= " FROM";
	$sql.= "  tbl_product AS T1";
	$sql.= " WHERE";
	$sql.= "  T1.product_type='2'";
	$sql.= "  AND (  (T1.start_date<='".date("Y-m-d")."' and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date<='".date("Y-m-d")."' and T1.end_date IS NULL)  OR  (T1.start_date IS NULL and T1.end_date>='".date("Y-m-d")."')  OR  (T1.start_date IS NULL and T1.end_date IS NULL)  ) ";
	$sql.= "  AND T1.del_flg='0'";
	$sql.= " ORDER BY rand()";
	$sql.= " LIMIT 8";
	
	$arr_list = $objDbConnect->query_fetch_arr($sql);
}
?>

<div style="clear:both;height: 400px;" class="product_recommend">
<h2>おすすめ講座</h2>
<?php foreach($arr_list as $val){ ?>
	<div style="width:125px;height:160px;float:left;margin:0;text-align:center;">
		<div class="imagebd"><a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_image.php?image=<?php echo $val['thumbnail']; ?>&width=100&height=100" width="100%" alt="" /></a></div>
		<div class="name" style="width:100px;text-align:left;padding:5px;"><a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><?php if(mb_strlen($val['product_name'], 'UTF-8')>14){ echo mb_substr($val['product_name'],0,14,"UTF-8")."..."; } else { echo $val['product_name']; } ?></a></div>
	</div>
<?php } ?>
</div>