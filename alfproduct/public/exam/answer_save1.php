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
require_once(dirname(__FILE__) ."./../../module/exam_error_logger.php");
$_SESSION['wp_page_head_title'] = 'テスト';
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
if (!isset($_GET['ccno'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$ccno = $_GET['ccno'];
if(cmCheckInput($ccno, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
if (!isset($_GET['eid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$eid = $_GET['eid'];
if(cmCheckInput($eid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}

$qid = '';
if(isset($_GET['qid'])){
	$qid = $_GET['qid'];
	if(cmCheckInput($qid, 'CK_NUM')){
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];

$back_url = get_back_url();

$exam_answer_id_list = array();
$exam_answer_id_list_q = array();

// テスト情報・問題一覧を事前取得（トランザクション外）
$exam_list = get_exam($eid, $user_id);

// 採点に必要な情報をmap化（answer_kind/correct_no/answer_point）
$problem_meta = array();
if (!empty($exam_list['problem'])) {
	foreach ($exam_list['problem'] as $p) {
		$correct_no = array();
		if (isset($p['answer_contents_select']['answer_contents'])) {
			foreach ($p['answer_contents_select']['answer_contents'] as $ac) {
				if (isset($ac['correct']) && $ac['correct'] == '1') {
					$correct_no[] = $ac['no'];
				}
			}
		}
		sort($correct_no);
		$problem_meta[$p['exam_problem_id']] = array(
			'answer_kind'  => $p['answer_kind'],
			'correct_no'   => $correct_no,
			'answer_point' => $p['answer_point'],
		);
	}
}

$err_flg = false;
$nowdate = date("Y-m-d H:i:s");
$conn = $objDbConnect->connect;

$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($conn,$eid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."'";
$res_exam_answer_retry = $objDbConnect->query_fetch_arr($sql);
if ($res_exam_answer_retry){
	// SELECT〜INSERT〜DELETEを一貫したトランザクション内で処理
	$objDbConnect->tran_begin();

	// アンケートフラグの取得
	$question_flg = 0;
	$sql = "SELECT exam_id_question FROM rel_product_contents WHERE product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res && $eid == $res['exam_id_question']){
		$question_flg = 1;
	}

	// 解答回数の取得
	$exam_answer_no = 1;
	$sql = "SELECT MAX(exam_answer_no) as mno FROM exam_answer WHERE status=0 AND exam_id='".mysqli_real_escape_string($conn,$eid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."' ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$exam_answer_no += (int)$res["mno"];
	}

	// 既存のexam_answerをexam_problem_id => exam_answer_id でmap化
	$sql = "SELECT exam_answer_id, exam_problem_id FROM exam_answer WHERE status=0 AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND exam_id='".mysqli_real_escape_string($conn,$eid)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."'";
	$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);
	$existing_map = array();
	foreach ($ret_exam_answer as $ea) {
		$existing_map[$ea['exam_problem_id']] = $ea['exam_answer_id'];
	}

	// retryデータをINSERT行・UPDATE行に分割
	$insert_rows = array();
	$update_rows = array();

	foreach ($res_exam_answer_retry as $answer) {
		$exam_problem_id = $answer['exam_problem_id'];

		// 採点（事前構築したproblem_metaを使う）
		$exam_answer_mark = 0;
		$exam_answer_point = 0;
		if (isset($problem_meta[$exam_problem_id])) {
			$meta = $problem_meta[$exam_problem_id];
			$temp_check = array();
			if ($meta['answer_kind'] == '1') {
				$temp_check[] = $answer['exam_answer_contents'];
			} elseif ($meta['answer_kind'] == '2') {
				foreach (explode(',', $answer['exam_answer_contents']) as $val) {
					$temp_check[] = $val;
				}
			} elseif ($meta['answer_kind'] == '3') {
				$temp_check[] = convert_symbole($answer['exam_answer_contents']);
			}
			if ($meta['answer_kind'] == '1' || $meta['answer_kind'] == '2') {
				sort($temp_check);
				if (array_equal_set($temp_check, $meta['correct_no'])) {
					$exam_answer_mark = '1';
					$exam_answer_point = $meta['answer_point'];
				}
			}
		}

		$contents_esc = mysqli_real_escape_string($conn, $answer['exam_answer_contents']);

		if (isset($existing_map[$exam_problem_id])) {
			$update_rows[] = array(
				'exam_answer_id' => $existing_map[$exam_problem_id],
				'contents_esc'   => $contents_esc,
				'mark'           => $exam_answer_mark,
				'point'          => $exam_answer_point,
			);
		} else {
			$insert_rows[] = "("
				. "'" . mysqli_real_escape_string($conn, $eid) . "', "
				. "'" . mysqli_real_escape_string($conn, $exam_problem_id) . "', "
				. "'" . mysqli_real_escape_string($conn, $user_id) . "', "
				. "'" . mysqli_real_escape_string($conn, $exam_answer_no) . "', "
				. "'" . $contents_esc . "', "
				. "'" . mysqli_real_escape_string($conn, $nowdate) . "', "
				. "'" . $exam_answer_mark . "', "
				. "'" . $exam_answer_point . "', "
				. "'0', '0', "
				. "'" . mysqli_real_escape_string($conn, $pid) . "', "
				. "'" . mysqli_real_escape_string($conn, $ccno) . "', "
				. "'" . $question_flg . "'"
				. ")";
		}
	}

	// バルクINSERT（リトライ付き、500ms→1s→2s）
	if (!empty($insert_rows)) {
		$sql = "INSERT INTO exam_answer (exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg) VALUES "
			. implode(',', $insert_rows);

		$insert_ok = false;
		$last_errno = 0;
		$last_error = '';
		$delays_us = array(500000, 1000000, 2000000);
		for ($attempt = 0; $attempt < 3; $attempt++) {
			$ret = $objDbConnect->execute($sql);
			if ($ret) {
				$insert_ok = true;
				break;
			}
			$last_errno = mysqli_errno($conn);
			$last_error = mysqli_error($conn);
			if ($attempt < 2) {
				usleep($delays_us[$attempt]);
			}
		}

		if (!$insert_ok) {
			$err_flg = true;
			exam_error_log('answer_save1.bulk_insert', array(
				'student_id'  => $user_id,
				'exam_id'     => $eid,
				'product_id'  => $pid,
				'contents_no' => $ccno,
				'count'       => count($insert_rows),
				'retries'     => 3,
				'mysql_errno' => $last_errno,
				'mysql_error' => $last_error,
			));
		} else {
			$first_id = mysqli_insert_id($conn);
			for ($i = 0; $i < count($insert_rows); $i++) {
				$exam_answer_id_list[] = $first_id + $i;
			}
		}
	}

	// UPDATE（既存問題の更新）
	if (!$err_flg && !empty($update_rows)) {
		foreach ($update_rows as $u) {
			$sql = "UPDATE exam_answer SET "
				. "exam_answer_no='" . mysqli_real_escape_string($conn, $exam_answer_no) . "', "
				. "exam_answer_contents='" . $u['contents_esc'] . "', "
				. "exam_answer_date='" . mysqli_real_escape_string($conn, $nowdate) . "', "
				. "exam_answer_mark='" . $u['mark'] . "', "
				. "exam_answer_point='" . $u['point'] . "', "
				. "update_at='" . mysqli_real_escape_string($conn, $nowdate) . "' "
				. "WHERE exam_answer_id='" . mysqli_real_escape_string($conn, $u['exam_answer_id']) . "'";
			$ret = $objDbConnect->execute($sql);
			if (!$ret) {
				$err_flg = true;
				exam_error_log('answer_save1.update', array(
					'student_id'     => $user_id,
					'exam_id'        => $eid,
					'product_id'     => $pid,
					'contents_no'    => $ccno,
					'exam_answer_id' => $u['exam_answer_id'],
					'mysql_errno'    => mysqli_errno($conn),
					'mysql_error'    => mysqli_error($conn),
				));
				break;
			}
			$exam_answer_id_list[] = $u['exam_answer_id'];
		}
	}

	$passing_flg = 0;
	if (!$err_flg && !empty($exam_answer_id_list)) {
		$sql = "SELECT criteria_type, criteria_value FROM exam WHERE exam_id='".mysqli_real_escape_string($conn,$eid)."'";
		$ret_exam = $objDbConnect->query_fetch($sql);
		if($ret_exam){
			// 点数
			if($ret_exam['criteria_type']=='1'){
				$my_total_answer_point = 0;
				foreach($exam_answer_id_list as $exam_answer_id){
					$sql = "SELECT exam_answer_point FROM exam_answer WHERE exam_answer_id='".mysqli_real_escape_string($conn,$exam_answer_id)."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						$my_total_answer_point += $ret['exam_answer_point'];
					}
				}
				if($ret_exam['criteria_value']<=$my_total_answer_point){
					$passing_flg = 1;
				}
			// 割合
			} elseif($ret_exam['criteria_type']=='2'){
				$my_total_answer_point = 0;
				foreach($exam_answer_id_list as $exam_answer_id){
					$sql = "SELECT exam_answer_point FROM exam_answer WHERE exam_answer_id='".mysqli_real_escape_string($conn,$exam_answer_id)."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						$my_total_answer_point += $ret['exam_answer_point'];
					}
				}
				$sql = "SELECT SUM(answer_point) AS total_answer_point FROM rel_exam_problem LEFT JOIN exam_problem ON rel_exam_problem.exam_problem_id=exam_problem.exam_problem_id WHERE rel_exam_problem.exam_id='".mysqli_real_escape_string($conn,$eid)."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					$check_value = floor($ret_exam['criteria_value'] / 100 * $ret['total_answer_point']);
					if($check_value<=$my_total_answer_point){
						$passing_flg = 1;
					}
				}
			// 正答数
			} elseif($ret_exam['criteria_type']=='3'){
				$my_total_answer_point = 0;
				foreach($exam_answer_id_list as $exam_answer_id){
					$sql = "SELECT exam_answer_mark FROM exam_answer WHERE exam_answer_id='".mysqli_real_escape_string($conn,$exam_answer_id)."'";
					$ret = $objDbConnect->query_fetch($sql);
					if($ret){
						if($ret['exam_answer_mark']=='1'){
							$my_total_answer_point += 1;
						}
					}
				}
				if($ret_exam['criteria_value']<=$my_total_answer_point){
					$passing_flg = 1;
				}
			}

			foreach($exam_answer_id_list as $exam_answer_id){
				$sql = "UPDATE exam_answer SET passing_flg='".mysqli_real_escape_string($conn,$passing_flg)."', criteria_type='".mysqli_real_escape_string($conn,$ret_exam['criteria_type'])."', criteria_value='".mysqli_real_escape_string($conn,$ret_exam['criteria_value'])."' WHERE exam_answer_id='".mysqli_real_escape_string($conn,$exam_answer_id)."'";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
					exam_error_log('answer_save1.update_passing', array(
						'student_id'     => $user_id,
						'exam_id'        => $eid,
						'exam_answer_id' => $exam_answer_id,
						'mysql_errno'    => mysqli_errno($conn),
						'mysql_error'    => mysqli_error($conn),
					));
				}
			}
		}
	}

	// 全件成功時のみretry DELETE
	if(!$err_flg){
		$sql  = "DELETE FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($conn,$eid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."' ";
		$ret = $objDbConnect->execute($sql);
		if (!$ret){
			$err_flg = true;
			exam_error_log('answer_save1.delete_retry', array(
				'student_id'  => $user_id,
				'exam_id'     => $eid,
				'product_id'  => $pid,
				'contents_no' => $ccno,
				'mysql_errno' => mysqli_errno($conn),
				'mysql_error' => mysqli_error($conn),
			));
		}
	}

	// アンケート（質問なし採点なし。同じバルクINSERT＋INSERT/UPDATE per-problem分岐）
	if(!$err_flg && $qid!=''){
		$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($conn,$qid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."'";
		$res_exam_answer_retry_q = $objDbConnect->query_fetch_arr($sql);
		if ($res_exam_answer_retry_q){
			// 解答回数の取得
			$exam_answer_no_q = 1;
			$sql = "SELECT MAX(exam_answer_no) as mno FROM exam_answer WHERE status=0 AND exam_id='".mysqli_real_escape_string($conn,$qid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."' ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				$exam_answer_no_q += (int)$res["mno"];
			}

			// 既存のexam_answerをmap化
			$sql = "SELECT exam_answer_id, exam_problem_id FROM exam_answer WHERE status=0 AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND exam_id='".mysqli_real_escape_string($conn,$qid)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."'";
			$ret_exam_answer_q = $objDbConnect->query_fetch_arr($sql);
			$existing_map_q = array();
			foreach ($ret_exam_answer_q as $ea) {
				$existing_map_q[$ea['exam_problem_id']] = $ea['exam_answer_id'];
			}

			$insert_rows_q = array();
			$update_rows_q = array();
			foreach ($res_exam_answer_retry_q as $answer) {
				$contents_esc = mysqli_real_escape_string($conn, $answer['exam_answer_contents']);
				if (isset($existing_map_q[$answer['exam_problem_id']])) {
					$update_rows_q[] = array(
						'exam_answer_id' => $existing_map_q[$answer['exam_problem_id']],
						'contents_esc'   => $contents_esc,
					);
				} else {
					$insert_rows_q[] = "("
						. "'" . mysqli_real_escape_string($conn, $qid) . "', "
						. "'" . mysqli_real_escape_string($conn, $answer['exam_problem_id']) . "', "
						. "'" . mysqli_real_escape_string($conn, $user_id) . "', "
						. "'" . mysqli_real_escape_string($conn, $exam_answer_no_q) . "', "
						. "'" . $contents_esc . "', "
						. "'" . mysqli_real_escape_string($conn, $nowdate) . "', "
						. "'0', '0', '0', '0', "
						. "'" . mysqli_real_escape_string($conn, $pid) . "', "
						. "'" . mysqli_real_escape_string($conn, $ccno) . "', "
						. "'1'"
						. ")";
				}
			}

			if (!empty($insert_rows_q)) {
				$sql = "INSERT INTO exam_answer (exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg) VALUES "
					. implode(',', $insert_rows_q);

				$insert_ok_q = false;
				$last_errno_q = 0;
				$last_error_q = '';
				$delays_us = array(500000, 1000000, 2000000);
				for ($attempt = 0; $attempt < 3; $attempt++) {
					$ret = $objDbConnect->execute($sql);
					if ($ret) {
						$insert_ok_q = true;
						break;
					}
					$last_errno_q = mysqli_errno($conn);
					$last_error_q = mysqli_error($conn);
					if ($attempt < 2) {
						usleep($delays_us[$attempt]);
					}
				}

				if (!$insert_ok_q) {
					$err_flg = true;
					exam_error_log('answer_save1.bulk_insert_q', array(
						'student_id'  => $user_id,
						'exam_id'     => $qid,
						'product_id'  => $pid,
						'contents_no' => $ccno,
						'count'       => count($insert_rows_q),
						'retries'     => 3,
						'mysql_errno' => $last_errno_q,
						'mysql_error' => $last_error_q,
					));
				} else {
					$first_id_q = mysqli_insert_id($conn);
					for ($i = 0; $i < count($insert_rows_q); $i++) {
						$exam_answer_id_list_q[] = $first_id_q + $i;
					}
				}
			}

			if (!$err_flg && !empty($update_rows_q)) {
				foreach ($update_rows_q as $u) {
					$sql = "UPDATE exam_answer SET "
						. "exam_answer_no='" . mysqli_real_escape_string($conn, $exam_answer_no_q) . "', "
						. "exam_answer_contents='" . $u['contents_esc'] . "', "
						. "exam_answer_date='" . mysqli_real_escape_string($conn, $nowdate) . "', "
						. "exam_answer_mark='0', "
						. "exam_answer_point='0', "
						. "update_at='" . mysqli_real_escape_string($conn, $nowdate) . "' "
						. "WHERE exam_answer_id='" . mysqli_real_escape_string($conn, $u['exam_answer_id']) . "'";
					$ret = $objDbConnect->execute($sql);
					if (!$ret) {
						$err_flg = true;
						exam_error_log('answer_save1.update_q', array(
							'student_id'     => $user_id,
							'exam_id'        => $qid,
							'exam_answer_id' => $u['exam_answer_id'],
							'mysql_errno'    => mysqli_errno($conn),
							'mysql_error'    => mysqli_error($conn),
						));
						break;
					}
					$exam_answer_id_list_q[] = $u['exam_answer_id'];
				}
			}

			if(!$err_flg && !empty($exam_answer_id_list_q)){
				$sql = "SELECT criteria_type, criteria_value FROM exam WHERE exam_id='".mysqli_real_escape_string($conn,$qid)."'";
				$ret_exam = $objDbConnect->query_fetch($sql);
				if($ret_exam){
					foreach($exam_answer_id_list_q as $exam_answer_id){
						$sql = "UPDATE exam_answer SET passing_flg='".mysqli_real_escape_string($conn,$passing_flg)."', criteria_type='".mysqli_real_escape_string($conn,$ret_exam['criteria_type'])."', criteria_value='".mysqli_real_escape_string($conn,$ret_exam['criteria_value'])."' WHERE exam_answer_id='".mysqli_real_escape_string($conn,$exam_answer_id)."'";
						$ret = $objDbConnect->execute($sql);
						if (!$ret){
							$err_flg = true;
							exam_error_log('answer_save1.update_passing_q', array(
								'student_id'     => $user_id,
								'exam_id'        => $qid,
								'exam_answer_id' => $exam_answer_id,
								'mysql_errno'    => mysqli_errno($conn),
								'mysql_error'    => mysqli_error($conn),
							));
						}
					}
				}
			}

			if(!$err_flg){
				$sql = "DELETE FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($conn,$qid)."' AND student_id='".mysqli_real_escape_string($conn,$user_id)."' AND product_id='".mysqli_real_escape_string($conn,$pid)."' AND contents_no='".mysqli_real_escape_string($conn,$ccno)."' ";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
					exam_error_log('answer_save1.delete_retry_q', array(
						'student_id'  => $user_id,
						'exam_id'     => $qid,
						'product_id'  => $pid,
						'contents_no' => $ccno,
						'mysql_errno' => mysqli_errno($conn),
						'mysql_error' => mysqli_error($conn),
					));
				}
			}
		}
	}

	if(!$err_flg){
		$objDbConnect->commit();
	} else {
		$objDbConnect->rollback();
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();

if ($err_flg) {
	// 失敗時はretryを残したまま確認画面へ。ユーザーが「終了する」を再押下すれば再試行可能
	$redirect = "/exam/confirm1.php?pid=" . urlencode($pid) . "&ccno=" . urlencode($ccno) . "&eid=" . urlencode($eid) . "&save_error=1";
	if ($qid != '') {
		$redirect .= "&qid=" . urlencode($qid);
	}
	header("Location: " . $redirect);
} else {
	header("Location: /product/detail.php?pid=" . $pid);
}
exit();
?>
