<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
csrf_token_verify();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pid = $_POST['pid'];
if (!st_login_check()){
	header("Location: /");
	exit;
}
if(cmCheckInput($pid, 'CK_NUM')){
	header("Location: /");
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect = new DbConnect();
$user_id = $_SESSION['user']['id'];
$now_date = date('Y-m-d H:i:s');
if (isset($_POST['act'])){
	$act = $_POST['act'];
	switch ($_POST['act']){
		// 登録
		case 'regist':
			$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$user_id."' AND product_id='".$pid."' AND del_flg='0'";
			$ret = $objDbConnect->query_fetch($sql);
			if ($ret['c']==0){
				$sql = "update tbl_favorite set `rank`=`rank`+1 where member_id='".$user_id."'";
				$objDbConnect->execute($sql);
				$sql = "INSERT INTO tbl_favorite (member_id,product_id,regist_date) VALUES ('".$user_id."','".$pid."','".$now_date."')";
				$objDbConnect->execute($sql);
			}
			break;
		// 削除
		case 'delete':
			//$sql = "UPDATE tbl_favorite SET del_flg='1' WHERE member_id='".$user_id."' AND product_id='".$pid."'";
			$sql = "delete from tbl_favorite WHERE member_id='".$user_id."' AND product_id='".$pid."'";
			$objDbConnect->execute($sql);
			break;
		case 'up':
			$sql = "SELECT `rank` FROM tbl_favorite WHERE member_id='".$user_id."' AND product_id='".$pid."' limit 1";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if( !empty($ret) ){
				$current_rank = $ret[0]['rank'];
				$neighbor = $objDbConnect->query_fetch_arr(_get_visible_neighbor_sql($user_id, $current_rank, 'up'));
				if( !empty($neighbor) ){
					$neighbor_rank = $neighbor[0]['rank'];
					$neighbor_pid  = $neighbor[0]['product_id'];
					$objDbConnect->execute("UPDATE tbl_favorite SET `rank`='".$neighbor_rank."' WHERE member_id='".$user_id."' AND product_id='".$pid."'");
					$objDbConnect->execute("UPDATE tbl_favorite SET `rank`='".$current_rank."' WHERE member_id='".$user_id."' AND product_id='".$neighbor_pid."'");
				}
			}
			break;
		case 'down':
			$sql = "SELECT `rank` FROM tbl_favorite WHERE member_id='".$user_id."' AND product_id='".$pid."' limit 1";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if( !empty($ret) ){
				$current_rank = $ret[0]['rank'];
				$neighbor = $objDbConnect->query_fetch_arr(_get_visible_neighbor_sql($user_id, $current_rank, 'down'));
				if( !empty($neighbor) ){
					$neighbor_rank = $neighbor[0]['rank'];
					$neighbor_pid  = $neighbor[0]['product_id'];
					$objDbConnect->execute("UPDATE tbl_favorite SET `rank`='".$neighbor_rank."' WHERE member_id='".$user_id."' AND product_id='".$pid."'");
					$objDbConnect->execute("UPDATE tbl_favorite SET `rank`='".$current_rank."' WHERE member_id='".$user_id."' AND product_id='".$neighbor_pid."'");
				}
			}
			break;
		default:
	}
}

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
header("Location: ".get_back_url()."#".$act);
exit;

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// favorite_list.php の表示条件と同じフィルタで隣アイテムを1件取得するSQL生成
function _get_visible_neighbor_sql($user_id, $current_rank, $direction) {
	$now = date("Y-m-d H:i:s");
	$bar_association_id = mysqli_real_escape_string($GLOBALS['objDbConnect']->connect, $_SESSION['user']['bar_association_id']);

	if ($direction === 'up') {
		$rank_cond = "tbl_favorite.`rank` < '".$current_rank."'";
		$order     = "ORDER BY tbl_favorite.`rank` DESC";
	} else {
		$rank_cond = "tbl_favorite.`rank` > '".$current_rank."'";
		$order     = "ORDER BY tbl_favorite.`rank` ASC";
	}

	return "
		SELECT tbl_favorite.product_id, tbl_favorite.`rank`
		FROM tbl_favorite
			LEFT JOIN tbl_product
				ON tbl_favorite.product_id = tbl_product.product_id
			LEFT JOIN tbl_product_add
				ON tbl_product.product_id = tbl_product_add.product_id
			LEFT JOIN tbl_product_live_training
				ON tbl_product.product_id = tbl_product_live_training.product_id
		WHERE
			tbl_favorite.member_id = '".$user_id."'
			AND tbl_favorite.del_flg = '0'
			AND tbl_product.del_flg = '0'
			AND (
				  (tbl_product.start_date <= '".$now."' AND tbl_product.end_date >= '".$now."')
				OR (tbl_product.start_date <= '".$now."' AND tbl_product.end_date IS NULL)
				OR (tbl_product.start_date IS NULL        AND tbl_product.end_date >= '".$now."')
				OR (tbl_product.start_date IS NULL        AND tbl_product.end_date IS NULL)
			)
			AND tbl_product_add.product_type_add IN (1, 2)
			AND (
				  tbl_product_live_training.ethic_flg = 0
				OR tbl_product_live_training.ethic_flg IS NULL
				OR (tbl_product_live_training.ethic_flg = 1 AND tbl_product_live_training.app_flg = 1)
			)
			AND (
				  tbl_product_live_training.target LIKE '%|".$bar_association_id."|%'
				OR tbl_product_live_training.target IS NULL
			)
			AND ".$rank_cond."
		".$order."
		LIMIT 1
	";
}
?>
