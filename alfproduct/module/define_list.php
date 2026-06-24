<?php
define('TAX_RATE_YEN', '0'); // 税率(単位：％)
define('TAX_FLAG_YEN', '0'); // 税扱いフラグ 0:0 1:四捨五入 2:切り上げ 3:切り捨て
define('COMMISSION_YEN', '0'); // 商品購入手数料(単位：円)

// アルフストリーム
//define('ALFSTREAM_AUTHKEY', 'h7xsxbm6xpd1kbfyervex71qwuer7vccy8yejth2'); // 認証キー
//define('ALFSTREAM_PLAYER_URL', 'http://api.alfstr-stg.alfredcore.net/v1/play'); // 動画取得URL

define('ALFSTREAM_AUTHKEY', 'ffw7ytnlode8s375mxbg3tuhlnj14ruku2trm8lj'); // 認証キー
//define('ALFSTREAM_AUTHKEY', 'hneeb1zjroqs66pr4ry8lukhtesspbdil1zs80qt'); // stg認証キー
define('ALFSTREAM_PLAYER_URL', 'https://api.alfstream.com/v1/play'); // 動画取得URL

//define('ALFSTREAM_DOMAIN', 'api.alfstr-stg.alfredcore.net');
define('ALFSTREAM_DOMAIN', 'api.alfstream.com');
define('ALFSTREAM_API_URL_HTTP', 'https://' . ALFSTREAM_DOMAIN);
define('ALFSTREAM_API_URL_HTTPS', 'https://' . ALFSTREAM_DOMAIN);
define('ALFSTREAM_ASSET_PATH', '/v1/asset/'); // アセット取得用

//一般側用school_id
define('SCHOOL_ID', '1');

//一般側用cource_id
define('COURCE_ID', '1');

// Codeigniter用
define('ENCRYPTION_KEY', 'aktKoltl234lLutka8993Saki234Dfsl'); // 暗号化キー

// PATH
define('THUMBNAIL_PATH', 'https://'.$_SERVER['SERVER_NAME'].'/alfproduct/upload/thumbnail/'); // 商品サムネイル保存場所
define('CONTENTS_THUMBNAIL_PATH', 'https://'.$_SERVER['SERVER_NAME'].'/alfproduct/upload/thumbnail/'); // 商品コンテンツサムネイル保存場所
define('DOCUMENT_PATH', 'https://'.$_SERVER['SERVER_NAME'].'/alfproduct/upload/document/'); // 商品ダウンロード資料保存場所

// 継続課金バッチ用
define('KEIZOKU_SAVE_DIR', '/var/www00/html/alfproducts/payment/file/monthly_csv/keizokukakin'); // CSV保存先
define('KEIZOKU_PRODUCT_CODE', ''); // 商品コード(設定する時は0000990)
define('KEIZOKU_PRICE', '1000'); // 金額(char 7)
define('KEIZOKU_PRICE_ETC', '0'); // その他金額(char 7)
define('KEIZOKU_FREE_SPACE', ''); // 加盟店自由項目、半角英数記号（除く ^`{|}~&<>"'）と全角文字が使用可能(char 50)

// 洗替用
define('ARAI_SAVE_DIR', '/var/www00/html/alfproducts/payment/file/monthly_csv/araigae'); // CSV保存先
define('ARAI_FREE_SPACE', ''); // 加盟店自由項目、半角英数記号（除く ^`{|}~&<>"'）と全角文字が使用可能(char 50)
define('ARAI_ERR_MAIL_SUBJECT', 'カードの有効性エラー件名'); // 洗替エラーメール件名
define('ARAI_ERR_MAIL_FROM', 'From: err_mail@hougakukan-system'); // 洗替エラーメール送信元
define('ARAI_ERR_MAIL_FROM_JA', 'hougakukan-system'); // 洗替エラーメール送信元

// 商品登録
define('MAX_CONTENTS', '25'); // コンテンツの最大登録数(max 25)
define('MAX_CONTENTS_DOWNLOAD', '10'); // 各コンテンツダウンロードの最大登録数(max 10)
define('MAX_RELATED_PRODUCTS', '10'); // 関連商品の最大登録数(max 10)
define('MAX_FREE_HTML_AREA', '5'); // フリーHTMLエリアの最大登録数(max 5)
define('MAX_PRODUCT_SUB_IMAGE', '8'); // 商品サブ画像最大登録数(max 8)
define('MAX_OPEN_PERIOD', '90'); // 購入後公開期間日数
define('MAX_CONTENTS_FREE_TIME', '3600'); // コンテンツ無料公開範囲(秒)

// 日弁連追加分
define('NICHIBENREN_FRONT_ROOT_DIR', '/srv/alfproduct/public/'); // ルートディレクトリ
//define('SSO_API', 'http://api.nichibenren-stg2.alfredcore.net/openam_sso'); // シングルサインオンapi
define('SSO_API', 'http://api.nichibenren-stg2.alfcloud.com/openam_sso'); // シングルサインオンapi
define('SSO_LOGOUT_API', 'https://www.nichibenren-member-sso.jp/openam/identity/logout'); // シングルサインオン後ログアウトAPI
define('SSO_LOGOUT_BACK_URL', 'https://www.nichibenren.jp/opencms/opencms/jfba-member/jfba-top.html'); // ログアウト後の遷移先
define('SSO_TOKEN_CHECK_API', '	https://www.nichibenren-member-sso.jp/openam/identity/attributes'); // シングルサインオン認証トークンの検証API

// 新シングルサインオン設定
define('NEW_SSO_SECRET', 'n3SMPULGnyUmdKEz'); // 各機能利用時に設定する秘密鍵
define('NEW_SSO_IDTOKEN_SESSION_TIME', 7200); // IDトークンの有効期間を設定（600～7200秒の範囲で設定）
define('NEW_SSO_BASE_URL', 'https://member.nichibenren.or.jp/sso'); // シングルサインオンapiのベースとなるURL
define('NEW_SSO_AUTH', NEW_SSO_BASE_URL.'/auth'); // 認証
define('NEW_SSO_LOGOUT', NEW_SSO_BASE_URL.'/logout'); // ログアウト
define('NEW_SSO_PROFILE', NEW_SSO_BASE_URL.'/profile'); // ログインしているユーザー情報を取得
define('NEW_SSO_UPDATE', NEW_SSO_BASE_URL.'/update'); // IDトークンの有効期限を更新
define('NEW_SSO_CHECK_PASSWORD', NEW_SSO_BASE_URL.'/checkPasswd'); // パスワード確認（重要な会員情報の修正など、再度パスワード確認をするような場面で使用することを想定）
define('NEW_SSO_API_UPDATE_PROFILE', 'http://api.nichibenren-stg2.alfcloud.com/sso_update_profile'); // 認証後にプロフィールを更新するAPI
define('NEW_SSO_AUTH_CALLBACK_URL', 'https://nichibenren-stg2.alfcloud.com/login/sso_auth_callback.php'); // 認証後の戻り先

define('GA_IP_URL', 'https://15.197.165.86/sso_update_profile'); // AWS Global Accelerator stg[15.197.165.86][76.223.86.248] mst[15.197.229.71][99.83.128.205]
define('GA_IP_HOST', 'api.nichibenren-stg2.alfcloud.com');

define('BAR_ASSOCIATION_DUTY_YEAR', '0,3,5,10'); // 倫理研修義務年(カンマ区切り)
define('RECCOMEND_BLOCK_PATH', '/alflearning-data/alfproduct/wordpress/themes/twentyten/product_recommend2.php'); // おすすめ商品ブロックをincludeする際に使用する
define('EXP_DATE_PASSPORT_YEAR', '1'); // パスポート有効期限(年後を設定)(単位：年)
define('PASSPORT_PRICE1', '0'); // パスポート価格(1～2年目)
define('PASSPORT_PRICE2', '5000'); // パスポート価格(3～4年目)
define('PASSPORT_PRICE3', '10000'); // パスポート価格(5年目以降)
define('PLAYER_ALL_READING_JUDGE_TIME', '10'); // 再生完了時間判断用(秒) 総再生時間 - PLAYER_ALL_READING_JUDGE_TIMEが講座完了の判断となる
define('CATEGORY_ICON_IMG_PATH', '/img/category_icons/'); // カテゴリーアイコン置き場
define('CATEGORY_ICON_IMG_DIR', NICHIBENREN_FRONT_ROOT_DIR . CATEGORY_ICON_IMG_PATH); // カテゴリーアイコン置き場
define('CATEGORY_ICON_IMG_EXTENSION', '.png'); // カテゴリーアイコン拡張子
define('PHP_COMMAND', 'php'); // phpコマンド
define('BATCH_PATH', '/srv/alfproduct/module/batch/'); // バッチファイル置き場

define('VIDEO_POP_TIME', '15'); // ポップアップ時間
define('ADMIN_PRODUCT_DOMAIN', 'kenshu-cms.nichibenren.or.jp');
//define('ADMIN_PRODUCT_DOMAIN', 'nichibenren-prod-cms-alb-1042124407.ap-northeast-1.elb.amazonaws.com');
?>
