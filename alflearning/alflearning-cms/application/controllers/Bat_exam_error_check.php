<?
#[AllowDynamicProperties]
class Bat_exam_error_check extends CI_Controller {

	// テスト回答保存エラーログのディレクトリとアラートメール送信先
	const LOG_DIR        = '/alflearning-data/alfproduct/logs';
	const LOG_PREFIX     = 'exam_error-';
	const NOTIFIED_SUFFIX = '.notified';
	const MAIL_FROM      = 'info@alflearning.com';
	const MAIL_TO        = 'mkoyama@edutec.co.jp';
	const MAIL_CC        = '';
	// 本文に含めるログ行の最大数（多すぎると本文が肥大化するため）
	const MAX_LOG_LINES_IN_BODY = 200;

	function __construct(){
		parent::__construct();
		$this->load->library('email');
		$this->load->helper('unit');

		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	public function index(){
	}

	/**
	 * 当日の exam_error-YYYYMMDD.log を検知して、アラートメールを送信する。
	 * 同日に二重送信しないよう、送信完了時に .notified ファイルを作成する。
	 * 想定 cron 登録: cron.php --run=/bat_exam_error_check/run （例: 毎日 08:00）
	 */
	public function run(){
		$date = date('Ymd');
		$log_file = self::LOG_DIR . '/' . self::LOG_PREFIX . $date . '.log';
		$notified_file = $log_file . self::NOTIFIED_SUFFIX;

		// ログファイル無し → 平常運用、何もしない
		if (!file_exists($log_file)) {
			return;
		}

		// 既に通知済み → 二重送信しない
		if (file_exists($notified_file)) {
			return;
		}

		$lines = @file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if (empty($lines)) {
			return;
		}

		$count = count($lines);
		$body_lines = $lines;
		$truncated = false;
		if ($count > self::MAX_LOG_LINES_IN_BODY) {
			$body_lines = array_slice($lines, 0, self::MAX_LOG_LINES_IN_BODY);
			$truncated = true;
		}

		$subject = '[alflearning] テスト回答保存エラー検知 ' . date('Y-m-d') . ' (' . $count . '件)';

		$body  = '本日の設問付きeラーニング テスト回答保存処理でエラーが検知されました。'."\n";
		$body .= "\n";
		$body .= '日付  : ' . date('Y-m-d') . "\n";
		$body .= '件数  : ' . $count . ' 行' . "\n";
		$body .= 'ファイル: ' . $log_file . "\n";
		$body .= "\n";
		$body .= '--- ログ内容（先頭 ' . count($body_lines) . ' 行） ---' . "\n";
		foreach ($body_lines as $l) {
			$body .= $l . "\n";
		}
		if ($truncated) {
			$body .= "\n" . '※ 残り ' . ($count - count($body_lines)) . ' 行はログファイルを参照してください。' . "\n";
		}
		$body .= "\n";
		$body .= '※ このメールは Bat_exam_error_check バッチが自動送信しています。' . "\n";

		$this->email->clear();
		$this->email->initialize(array(
			'charset'  => 'ISO-2022-JP',
			'crlf'     => "\r\n",
			'newline'  => "\r\n",
		));

		$this->email->to(self::MAIL_TO);
		if (self::MAIL_CC !== '') {
			$this->email->cc(self::MAIL_CC);
		}
		$this->email->from(self::MAIL_FROM);
		$this->email->subject(mb_convert_encoding($subject, 'ISO-2022-JP', 'UTF-8'));
		$this->email->message(mb_convert_encoding($body, 'ISO-2022-JP', 'UTF-8'));
		$send_ok = $this->email->send();

		// 送信成功時のみ .notified を作成（失敗時は次回の cron で再送される）
		if ($send_ok) {
			@touch($notified_file);
		}
	}
}
?>
