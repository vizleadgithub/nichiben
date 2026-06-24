<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	echo 'error.1';
	exit();
}

if (!isset($_GET['pid'])){
	$objDbConnect->close();
	echo 'error.2';
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	echo 'error.3';
	exit();
}

if (!isset($_GET['sid'])){
	$objDbConnect->close();
	echo 'error.4';
	exit();
}
$sid = $_GET['sid'];
if(cmCheckInput($sid, 'CK_NUM')){
	$objDbConnect->close();
	echo 'error.5';
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_list = array();

$sql = "SELECT ";
$sql.= "  contents_no, ";
$sql.= "  exam_id_test, ";
$sql.= "  exam_id_question ";
$sql.= " FROM ";
$sql.= "  rel_product_contents ";
$sql.= " WHERE ";
$sql.= "  product_id='$pid' ";
$ret_rel_product_contents = $objDbConnect->query_fetch_arr($sql);
if($ret_rel_product_contents){
	foreach($ret_rel_product_contents as $val){
		// 問題名
		$arr_list[$val['contents_no']]['exam_name'] = '';
		$exam_id = 0;
		if($val['exam_id_test']>0){
			$exam_id = $val['exam_id_test'];
		} elseif($val['exam_id_question']>0){
			$exam_id = $val['exam_id_question'];
		}
		$sql = "SELECT exam_name FROM exam WHERE exam_id='$exam_id'";
		$ret_exam = $objDbConnect->query_fetch($sql);
		if($ret_exam){
			$arr_list[$val['contents_no']]['exam_name'] = $ret_exam['exam_name'];
		}
		
		// 全設問数
		$arr_list[$val['contents_no']]['exam_problem_count'] = 0;
		// 設問解答数
		$arr_list[$val['contents_no']]['exam_problem_answer_count'] = 0;
		if($exam_id>0){
			// テストとアンケート両方設定されている場合
			if($val['exam_id_test']>0 && $val['exam_id_question']>0){
				// 全設問数の取得
				$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='".$val['exam_id_test']."'";
				$ret_rel_exam_problem = $objDbConnect->query_fetch($sql);
				if($ret_rel_exam_problem){
					$arr_list[$val['contents_no']]['exam_problem_count'] += $ret_rel_exam_problem['count'];
				}
				$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='".$val['exam_id_question']."'";
				$ret_rel_exam_problem = $objDbConnect->query_fetch($sql);
				if($ret_rel_exam_problem){
					$arr_list[$val['contents_no']]['exam_problem_count'] += $ret_rel_exam_problem['count'];
				}
				
				// 設問解答数の取得(テスト解答済みならば、アンケートも解答済み)
				$all_exam_answered = get_all_exam_answered($val['exam_id_test'], $sid, $pid, $val['contents_no']);
				if($all_exam_answered){
					$arr_list[$val['contents_no']]['exam_problem_answer_count'] = $arr_list[$val['contents_no']]['exam_problem_count'];
				}
				if($arr_list[$val['contents_no']]['exam_problem_answer_count']==0){
					// 解答済みでない場合は一時保存データを参照
					$sql = "SELECT COUNT(*) AS count FROM exam_answer_retry WHERE exam_id='".$val['exam_id_test']."' AND student_id='".$sid."' AND product_id='".$pid."' AND contents_no='".$val['contents_no']."'";
					$ret_exam_answer_retry = $objDbConnect->query_fetch($sql);
					if($ret_exam_answer_retry){
						$arr_list[$val['contents_no']]['exam_problem_answer_count'] += $ret_exam_answer_retry['count'];
					}
					$sql = "SELECT COUNT(*) AS count FROM exam_answer_retry WHERE exam_id='".$val['exam_id_question']."' AND student_id='".$sid."' AND product_id='".$pid."' AND contents_no='".$val['contents_no']."'";
					$ret_exam_answer_retry = $objDbConnect->query_fetch($sql);
					if($ret_exam_answer_retry){
						$arr_list[$val['contents_no']]['exam_problem_answer_count'] += $ret_exam_answer_retry['count'];
					}
				}
				
			// テストのみ設定されている場合
			} elseif($val['exam_id_test']>0){
				// 全設問数の取得
				$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='".$val['exam_id_test']."'";
				$ret_rel_exam_problem = $objDbConnect->query_fetch($sql);
				if($ret_rel_exam_problem){
					$arr_list[$val['contents_no']]['exam_problem_count'] += $ret_rel_exam_problem['count'];
				}
				
				// 設問解答数の取得
				$all_exam_answered = get_all_exam_answered($val['exam_id_test'], $sid, $pid, $val['contents_no']);
				if($all_exam_answered){
					$arr_list[$val['contents_no']]['exam_problem_answer_count'] = $arr_list[$val['contents_no']]['exam_problem_count'];
				}
				if($arr_list[$val['contents_no']]['exam_problem_answer_count']==0){
					// 解答済みでない場合は一時保存データを参照
					$sql = "SELECT COUNT(*) AS count FROM exam_answer_retry WHERE exam_id='".$val['exam_id_test']."' AND student_id='".$sid."' AND product_id='".$pid."' AND contents_no='".$val['contents_no']."'";
					$ret_exam_answer_retry = $objDbConnect->query_fetch($sql);
					if($ret_exam_answer_retry){
						$arr_list[$val['contents_no']]['exam_problem_answer_count'] += $ret_exam_answer_retry['count'];
					}
				}
				
			// アンケートのみ設定されている場合
			} elseif($val['exam_id_question']>0){
				// 全設問数の取得
				$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='".$val['exam_id_question']."'";
				$ret_rel_exam_problem = $objDbConnect->query_fetch($sql);
				if($ret_rel_exam_problem){
					$arr_list[$val['contents_no']]['exam_problem_count'] += $ret_rel_exam_problem['count'];
				}
				
				// 設問解答数の取得
				$all_exam_answered = get_all_exam_answered($val['exam_id_question'], $sid, $pid, $val['contents_no']);
				if($all_exam_answered){
					$arr_list[$val['contents_no']]['exam_problem_answer_count'] = $arr_list[$val['contents_no']]['exam_problem_count'];
				}
				if($arr_list[$val['contents_no']]['exam_problem_answer_count']==0){
					// 解答済みでない場合は一時保存データを参照
					$sql = "SELECT COUNT(*) AS count FROM exam_answer_retry WHERE exam_id='".$val['exam_id_question']."' AND student_id='".$sid."' AND product_id='".$pid."' AND contents_no='".$val['contents_no']."'";
					$ret_exam_answer_retry = $objDbConnect->query_fetch($sql);
					if($ret_exam_answer_retry){
						$arr_list[$val['contents_no']]['exam_problem_answer_count'] += $ret_exam_answer_retry['count'];
					}
				}
				
			}
		}
		
		// 合否
		$arr_list[$val['contents_no']]['gouhi'] = '-'; // 判定条件なしかアンケートのみ設定されている場合の表示
		if($val['exam_id_test']>0){
			// 判定基準が設定されているか確認
			$sql = "SELECT criteria_type FROM exam WHERE exam_id='".$val['exam_id_test']."'";
			$ret_exam = $objDbConnect->query_fetch($sql);
			if ($ret_exam){
				if($ret_exam['criteria_type']>0){
					$arr_list[$val['contents_no']]['gouhi'] = '未実施';
				}
			}
			if($arr_list[$val['contents_no']]['gouhi']!='-'){
				// 解答済み確認
				$all_exam_answered = get_all_exam_answered($val['exam_id_test'], $sid, $pid, $val['contents_no']);
				if($all_exam_answered){
					// 解答済みの場合に合否判定
					$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id='".$pid."' AND student_id='".$sid."' AND contents_no='".$val['contents_no']."'";
					$res_exam_answer = $objDbConnect->query_fetch($sql);
					if($res_exam_answer){
						if($res_exam_answer['passing_flg']=='1'){
							$arr_list[$val['contents_no']]['gouhi'] = '合格';
						} else {
							$arr_list[$val['contents_no']]['gouhi'] = '不合格';
						}
					}
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('arr_list', $arr_list);

$template->admin_layout_non('report_status/exam.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
