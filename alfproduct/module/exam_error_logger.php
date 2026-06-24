<?php
/**
 * テスト回答保存処理のエラーログ出力ユーティリティ。
 *
 * 目的: 設問付きeラーニングでの回答保存（exam_answer_retry → exam_answer）が
 *       リトライしても失敗した場合に、原因切り分けのための情報を残す。
 *
 * ログ出力先: /alflearning-data/alfproduct/logs/exam_error-YYYYMMDD.log
 *             （日付ごとに自動分割。Bat_exam_error_check.php が当日分を検知してアラート送信）
 *
 * 使用例:
 *   require_once '/srv/alfproduct/module/exam_error_logger.php';
 *   exam_error_log('answer_save1.bulk_insert', [
 *       'student_id'  => $user_id,
 *       'exam_id'     => $eid,
 *       'product_id'  => $pid,
 *       'contents_no' => $ccno,
 *       'count'       => count($rows),
 *       'retries'     => 3,
 *       'mysql_errno' => mysqli_errno($conn),
 *       'mysql_error' => mysqli_error($conn),
 *   ]);
 */

if (!defined('EXAM_ERROR_LOG_DIR')) {
	define('EXAM_ERROR_LOG_DIR', '/alflearning-data/alfproduct/logs');
}

if (!function_exists('exam_error_log')) {
	/**
	 * テスト回答保存のエラーログを1行書き出す。
	 *
	 * 書式:
	 *   [YYYY-MM-DD HH:MM:SS] [step=xxx] [key=value] [key=value] ...
	 *
	 * @param string $step    ステップ識別子（例: answer_save1.bulk_insert, answer_save1.update）
	 * @param array  $context 追加項目（key => value）。配列・オブジェクトはJSON化される
	 */
	function exam_error_log($step, $context = array()) {
		$date = date('Ymd');
		$path = EXAM_ERROR_LOG_DIR . '/exam_error-' . $date . '.log';

		$line = '[' . date('Y-m-d H:i:s') . ']'
		      . ' [step=' . $step . ']';
		if (is_array($context)) {
			foreach ($context as $k => $v) {
				if (is_array($v) || is_object($v)) {
					$v = json_encode($v, JSON_UNESCAPED_UNICODE);
				} elseif ($v === null) {
					$v = 'null';
				} elseif ($v === true) {
					$v = 'true';
				} elseif ($v === false) {
					$v = 'false';
				}
				$line .= ' [' . $k . '=' . $v . ']';
			}
		}
		$line .= "\n";
		@error_log($line, 3, $path);
	}
}
