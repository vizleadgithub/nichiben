<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RefererCheck {
    public function check_referer() {
        // 許可するドメイン (自サイトのドメインを指定)
        $allowed_domains = array(
            'https://kenshu-cms.nichibenren.or.jp',   // 本番環境
            'https://cms.nichibenren-stg2.alfcloud.com',      // 開発環境
            'http://kenshu-cms.nichibenren.or.jp',   // 本番環境
            'http://cms.nichibenren-stg2.alfcloud.com',      // 開発環境
        );

        // リファラーを取得
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        // リクエストメソッドを取得 (GET/POST など)
        $request_method = $_SERVER['REQUEST_METHOD'] ?? '';

        // --- 条件の追加 ---
        // 1. リファラーが空で GET の場合は許可
        if (empty($referer) && ($request_method === 'GET' || $request_method === '') ) {
            return;  // リファラーが空 + GET の場合はスルー
        }

        // 2. リファラーが許可されたドメインなら許可
        if ($this->is_allowed_referer($referer, $allowed_domains)) {
            return;
        }

        // それ以外はエラー
        show_error('不正なリクエストです。', 403);
        exit;
    }

    // 許可されたドメインかどうかをチェック
    private function is_allowed_referer($referer, $allowed_domains) {
        foreach ($allowed_domains as $domain) {
            if (strpos($referer, $domain) === 0) {
                return true;
            }
        }
        return false;
    }
}