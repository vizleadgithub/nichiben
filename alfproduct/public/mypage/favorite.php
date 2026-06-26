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
if (strpos($_SERVER['HTTP_REFERER'], '/product/detail.php') === false
 && strpos($_SERVER['HTTP_REFERER'], '/mypage/favorite_list.php') === false
 && strpos($_SERVER['HTTP_REFERER'], '/search/index.php') === false
 && strpos($_SERVER['HTTP_REFERER'], '/product/list') === false){
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
				$sql = "SELECT member_id, product_id, regist_date, del_flg, `rank` FROM tbl_favorite WHERE member_id='".$user_id."' and `rank`<='".$ret[0]["rank"]."' ORDER BY `rank` DESC limit 2";
				$ret = $objDbConnect->query_fetch_arr($sql);
				if( !empty($ret) && count($ret)==2 ){
					$product_id1 = $ret[0]["product_id"];
					$product_id2 = $ret[1]["product_id"];
					$rank1 = $ret[0]["rank"];
					$rank2 = $ret[1]["rank"];
					$sql = "UPDATE tbl_favorite SET `rank`='".$rank1."' WHERE member_id='".$user_id."' AND product_id='".$product_id2."'";
					$objDbConnect->execute($sql);
					$sql = "UPDATE tbl_favorite SET `rank`='".$rank2."' WHERE member_id='".$user_id."' AND product_id='".$product_id1."'";
					$objDbConnect->execute($sql);
				}
			}
			break;
		case 'down':
			$sql = "SELECT `rank` FROM tbl_favorite WHERE member_id='".$user_id."' AND product_id='".$pid."' limit 1";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if( count($ret)>0 ){
				$sql = "SELECT member_id, product_id, regist_date, del_flg, `rank` FROM tbl_favorite WHERE member_id='".$user_id."' and `rank`>='".$ret[0]["rank"]."' ORDER BY `rank` ASC limit 2";
				$ret = $objDbConnect->query_fetch_arr($sql);
				if( !empty($ret) && count($ret)==2 ){
					$product_id1 = $ret[0]["product_id"];
					$product_id2 = $ret[1]["product_id"];
					$rank1 = $ret[0]["rank"];
					$rank2 = $ret[1]["rank"];
					$sql = "UPDATE tbl_favorite SET `rank`='".$rank1."' WHERE member_id='".$user_id."' AND product_id='".$product_id2."'";
					$objDbConnect->execute($sql);
					$sql = "UPDATE tbl_favorite SET `rank`='".$rank2."' WHERE member_id='".$user_id."' AND product_id='".$product_id1."'";
					$objDbConnect->execute($sql);
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
?>
