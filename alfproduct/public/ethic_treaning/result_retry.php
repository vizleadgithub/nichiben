<?php
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 

$isPad = false;
if(preg_match("/iPad/", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(preg_match("/iPhone/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPad/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPod/", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(preg_match("/Android/", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '倫理研修代替措置研修';
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$back_url = get_back_url();

if (!isset($_GET['pid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$pid = $_GET['pid'];

if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}

// 正しい倫理研修問題の商品IDかチェック
if ($pid != get_ethic_product_id()){
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];
$err_flg = false;
$product_name = '';

// 倫理措置フラグチェック
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	// ステータスチェック
	$sql = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid' AND status IN(4)";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		if ($res['c']<=0){
			header("Location: /");
			exit();
		}
	}
	
	// 全ての問題に回答しているかチェックを含めて履歴取得
	$sql = "
	SELECT
	  *
	FROM
	  tbl_ethic_question_history
	WHERE
	  student_id = '$user_id'
	  AND product_id = '$pid'
	  AND answer_ethic_branch_id11 IS NOT NULL
	  AND answer_ethic_branch_id12 IS NOT NULL
	  AND answer_ethic_branch_id13 IS NOT NULL
	  AND answer_ethic_branch_id14 IS NOT NULL
	  AND answer_ethic_branch_id15 IS NOT NULL
	  AND answer_ethic_branch_id16 IS NOT NULL
	";
	
	$history_question = $objDbConnect->query_fetch($sql);
	if ($history_question){
		$right_count = 0; // 正解数
		$retry_question_count = 6; // 追試問題数
		for ($i=1; $i<=$retry_question_count; $i++){
			$no = $i + 10; // 通常問題分プラスする
			$sql = "SELECT answer_flg FROM tbl_ethic_branch WHERE ethic_branch_id = '".$history_question["answer_ethic_branch_id$no"]."'";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				if ($res['answer_flg'] == 1){
					$right_count++;
				}
			}
		}
		
		// 4問以上正解で合格
		//if ($right_count >= 4){
		// 0問以上正解で合格
		if ($right_count >= 0){
			$passed_flg = true;
			
			// 商品名の取得
			$sql = "SELECT product_name FROM tbl_product WHERE product_id = '$pid'";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				$product_name = $res['product_name'];
			}
			
		} else {
			$passed_flg = false;
		}
		
		// 履歴の更新
		$judge_date = date('Y-m-d H:i:s');
		$sql = "UPDATE";
		$sql.= "   tbl_ethic_question_history";
		$sql.= " SET";
		if ($passed_flg){
		$sql.= "   status = '5',";
		} else {
		$sql.= "   status = '6',";
		}
		$sql.= "   judge_date2 = '$judge_date'";
		$sql.= " WHERE";
		$sql.= "   student_id = '$user_id'";
		$sql.= "   AND product_id = '$pid'";
		$objDbConnect->execute($sql);
		
		// 表示用に日付フォーマット変更
		$judge_date = date('Y年m月d日 H時i分', strtotime($judge_date));
		
/*
		// 問題グループ名の取得
		$sql = "
		SELECT
		  T2.question_group
		FROM
		  (SELECT ethic_group_id FROM tbl_product_ethic_training WHERE product_id = '$pid') AS T1
		    INNER JOIN
		  tbl_ethic_group AS T2
		      ON T1.ethic_group_id = T2.ethic_group_id
		";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			$question_group = $res['question_group'];
		} else {
			$question_group = '';
		}
*/
		
	} else {
		$err_flg = true;
	}
	
} else {
	$err_flg = true;
}

if ($err_flg){
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('passed_flg', $passed_flg);
$template->assign('judge_date', $judge_date);
//$template->assign('question_group', $question_group);
$template->assign('product_name', $product_name);

$template->layout_noside('ethic_treaning/result_retry.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>