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

$arr_list = array();

// 倫理措置フラグチェック
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	// ステータスチェック
	$sql = "SELECT status FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid' AND status IN(2,3,4,5,6,7)";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$status = $res['status'];
		
	} else {
		header("Location: /");
		exit();
	}
	
	// 設問の取得
	$sql = "
	SELECT
	  T2.ethic_question_id,
	  T2.question,
	  T2.failure_flg,
	  T2.`rank`,
	  T2.reference 
	FROM
	  (SELECT ethic_group_id FROM tbl_product_ethic_training WHERE product_id = '$pid' AND publish_flg = '1') AS T1
	    LEFT JOIN
	  tbl_ethic_question AS T2
	      ON T1.ethic_group_id = T2.ethic_group_id
	ORDER BY
	  T2.failure_flg ASC, T2.`rank` ASC
	";
	$arr_question = $objDbConnect->query_fetch_arr($sql);
	if ($arr_question){
		foreach ($arr_question as $val){
			// 不合格用問題の場合はステータスにより表示・非表示を切り替える
			if ($val['failure_flg']==1 && $status!=5 && $status!=6 && $status!=7){
				break;
			}
			
			$arr_list[$val['ethic_question_id']]['question'] = $val['question'];
			$arr_list[$val['ethic_question_id']]['reference'] = $val['reference'];
			$arr_list[$val['ethic_question_id']]['failure_flg'] = $val['failure_flg'];
			$arr_list[$val['ethic_question_id']]['question_no'] = $val['rank'];
			if ($val['failure_flg'] == 1){
				$arr_list[$val['ethic_question_id']]['question_no'] += 10;
			}
			
			// 問題の取得
			$sql = "
			SELECT
			  ethic_branch_id,
			  question_branch,
			  answer_flg
			FROM
			  tbl_ethic_branch
			WHERE
			  ethic_question_id = '".$val['ethic_question_id']."'
			ORDER BY
			  `rank` ASC
			";
			$arr_question_branch = $objDbConnect->query_fetch_arr($sql);
			
			if ($arr_question_branch){
				foreach ($arr_question_branch as $val2){
					$arr_list[$val['ethic_question_id']]['branch_info'][$val2['ethic_branch_id']]['question_branch'] = $val2['question_branch'];
					$arr_list[$val['ethic_question_id']]['branch_info'][$val2['ethic_branch_id']]['answer_flg'] = $val2['answer_flg'];
				}
				
			}
		}
		
//var_dump($arr_list);
//exit;
		
		// 回答履歴の取得
		$sql = "
		SELECT
		  answer_ethic_branch_id1,
		  answer_ethic_branch_id2,
		  answer_ethic_branch_id3,
		  answer_ethic_branch_id4,
		  answer_ethic_branch_id5,
		  answer_ethic_branch_id6,
		  answer_ethic_branch_id7,
		  answer_ethic_branch_id8,
		  answer_ethic_branch_id9,
		  answer_ethic_branch_id10,
		  answer_ethic_branch_id11,
		  answer_ethic_branch_id12,
		  answer_ethic_branch_id13,
		  answer_ethic_branch_id14,
		  answer_ethic_branch_id15,
		  answer_ethic_branch_id16,
		  answer_date1,
		  answer_date2,
		  answer_date3,
		  answer_date4,
		  answer_date5,
		  answer_date6,
		  answer_date7,
		  answer_date8,
		  answer_date9,
		  answer_date10,
		  answer_date11,
		  answer_date12,
		  answer_date13,
		  answer_date14,
		  answer_date15,
		  answer_date16
		FROM
		  tbl_ethic_question_history
		WHERE
		  student_id = '$user_id'
		  AND product_id = '$pid'
		";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			for ($i=1; $i<=16; $i++){
				$history_answer["answer_ethic_branch_id$i"] = $res["answer_ethic_branch_id$i"];
				if (is_null($res["answer_date$i"])){
					$history_answer["answer_date$i"] = $res["answer_date$i"];
				} else {
					$history_answer["answer_date$i"] = date('Y年m月d日', strtotime($res["answer_date$i"]));
				}
			}
		}
		
//var_dump($history_answer);
//exit;
	}
	
	
} else {
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('arr_list', $arr_list);
$template->assign('history_answer', $history_answer);

$template->layout_noside('ethic_treaning/answer_history_all.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>