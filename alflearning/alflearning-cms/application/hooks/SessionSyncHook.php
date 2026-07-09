<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SessionSyncHook
 *
 * post_controller_constructor フックとして登録する。
 * リクエストごとにDBのteacher情報とセッションを比較し、
 * 他の管理者によって属性（弁護士会ID等）が変更された場合にセッションを自動更新する。
 */
class SessionSyncHook
{
	public function sync()
	{
		$CI =& get_instance();

		// libauth がロード済みかつログイン中の場合のみ実行
		if (isset($CI->libauth) && $CI->libauth->is_logged_in()) {
			$CI->libauth->sync_session_from_db();
		}
	}
}
