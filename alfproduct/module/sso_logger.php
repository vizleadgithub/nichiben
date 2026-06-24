<?php
/**
 * SSOログイン処理のログ出力ユーティリティ。
 *
 * 目的: 日弁連SSOから受講者サイトへのログイン各ステップにおいて、
 *       再発時に原因を切り分けられるよう経路情報をログ出力する。
 *
 * ログ出力先: /alflearning-data/sso_log/sso_YYYYMMDD.log
 *             （日付ごとに自動分割。ログローテーションは運用側で実施する）
 *
 * 個人情報の取り扱い:
 *   - 記録する: lawyer_number（弁護士登録番号）、弁護士会ID、student_id、APIステータス
 *   - 記録しない: 氏名（student_name / openName 等）、メールアドレス、ID token そのもの
 *
 * 使用例:
 *   require_once '/srv/alfproduct/module/sso_logger.php';
 *   sso_log('callback_in', ['has_id_token' => 1]);
 */

if (!function_exists('sso_log')) {
	/**
	 * SSO処理ログを1行書き出す。
	 *
	 * 書式:
	 *   [YYYY-MM-DD HH:MM:SS] [txn=xxx] [step=xxx] [key=value] [key=value] ...
	 *
	 * @param string $step    ステップ識別子（例: callback_in, callback_api_response, login_student_lookup）
	 * @param array  $context 追加項目（key => value）。配列・オブジェクトはJSON化される
	 */
	function sso_log($step, $context = array()) {
		$date = date('Ymd');
		$path = '/alflearning-data/sso_log/sso_' . $date . '.log';

		// トレースID: 同一ログイン一連の処理（callback → login_sso_new）を串刺しで追えるようにする
		$trace_id = '-';
		if (isset($_SESSION['sso_transaction_id']) && $_SESSION['sso_transaction_id'] !== '') {
			$trace_id = $_SESSION['sso_transaction_id'];
		} elseif (isset($_SESSION['sso_log_trace_id']) && $_SESSION['sso_log_trace_id'] !== '') {
			$trace_id = $_SESSION['sso_log_trace_id'];
		}

		$line = '[' . date('Y-m-d H:i:s') . ']'
		      . ' [txn=' . $trace_id . ']'
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
