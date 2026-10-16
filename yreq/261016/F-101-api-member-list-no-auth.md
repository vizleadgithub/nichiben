---
id: F-101
title: API の受講者一覧出力（Csv_download）にログイン確認・接続元制限が無く、会員の氏名・登録番号・メールアドレスを返す
axis: security
checkpoint: sec-authn-authz
severity: high
evidence_type: measured
files:
  - alflearning/alflearning-api/application/controllers/Csv_download.php:16-27 (コンストラクタ。IP 制限の行がコメントアウト、ログイン確認なし)
  - alflearning/alflearning-api/application/controllers/Csv_download.php:36-80 (index。リクエストの値で出力条件を決め、取得結果を画面にそのまま出力)
  - alflearning/alflearning-api/application/controllers/Csv_download.php:154-173 (登録番号・氏名・メールアドレス・弁護士会名等を取得)
  - raw/server-config/nichiben-prod/vhost-inc-files.txt:41-45 (API ディレクトリは Require all granted。接続元の制限なし)
  - source/stg-src/alflearning/alflearning-api/application/controllers/Csv_download.php (stg も同一)
verification:
  - "grep -n 'is_logged_in\\|allowIP\\|session' alflearning/alflearning-api/application/controllers/Csv_download.php  # ログイン確認・接続元制限が無いこと"
  - "サーバ側でアクセスログを確認し、/csv_download への外部からのリクエスト有無を調べる"
next_level_up: >
  この出力口を廃止するか、管理者のログイン確認と接続元制限を必須にすれば、認証・認可の観点の high が 1 件外れる。
customer_label: ログインしていない人でも会員の氏名・登録番号・メールアドレスの一覧を取得できる出力口がある
---

## 何が起きているか

受講者向け API（kenshu-api）に、受講者一覧を出力する処理 `Csv_download` が残っています。
この処理には **ログインの確認も、接続元の制限もありません**（接続元制限の行はコメントアウトされています）。
受け取った条件にもとづいて会員情報を検索し、その結果を **CSV ではなくデバッグ出力（`var_dump`）のまま画面に返して終了** します。
開発途中のテスト出力（`print("test")`）も残っており、作りかけの処理が本番に配置されている状態です。

```php
// Csv_download.php:16-27（抜粋）
function __construct() {
    parent::__construct();
    $this->load->database();
    ...
//  $this->allowIP = $this->config->item('*');  //IPアドレス制限   ← 無効化されている
}

// Csv_download.php:75-80（抜粋）
case 'student_list':
    $table_data = $this->_student_list($post_csv_where);
var_dump($table_data);   // 取得結果をそのまま出力
exit();
```

取得する項目（`_student_list`, 154-163 行）: 登録番号（弁護士番号）・氏名・メールアドレス・メールマガジン可否・パスポート有無と有効期限・代替倫理研修権限・弁護士会名。

サーバ側でも API ディレクトリは `Require all granted` で、接続元の制限はありません（`vhost-inc-files.txt:41-45`）。stg のコードも同一です。

## リスク・影響

インターネット上の誰でも、ログインせずにこの出力口へ到達でき、条件の指定次第で **在籍会員の氏名・登録番号・メールアドレス等の一覧が返ります**。
個人情報保護法上の漏えい事案（本人通知・個人情報保護委員会への報告が必要な規模）に直結します。

事業への帰結: 在籍する弁護士会員（数万人規模と見られます）の氏名・登録番号・メールアドレスが、ログインなしで一括取得できる状態です。漏えいした場合は本人への通知と監督機関への報告が必要となり、日弁連・各弁護士会の信用問題に発展します。

## 推奨アクション

この出力口を本番から外し、必要なら管理画面側のログイン済み機能として作り直す。

**規模感の判断材料（必須）:** コード修正（ファイル 1 本の撤去または認証追加）・影響範囲: API の 1 コントローラ（本番 4 台＋stg）・数日規模

1. 利用実態を確認し、使われていなければ本番・stg から撤去する
2. 残す場合は管理者のログイン確認・権限確認・接続元制限を必須にし、デバッグ出力を除く
3. 過去のアクセスログを確認し、外部からの取得の有無を調べる（取得があれば漏えい対応へ）

## 検証方法

- 撤去後、該当パスへのリクエストが 404 になること
- 残す場合、未ログインでのリクエストがログイン画面へ転送／拒否されること
