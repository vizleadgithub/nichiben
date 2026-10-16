---
id: F-102
title: CMS のバッチ処理用コントローラ 29 本がログイン確認・コマンドライン限定なしで Web から起動でき、受講者テーブル全消去を含む処理がある
axis: security
checkpoint: sec-authn-authz
severity: high
evidence_type: measured
files:
  - alflearning/alflearning-cms/application/controllers/Bat_*.php (27 本), Once_bat_*.php (2 本)
  - alflearning/alflearning-cms/application/controllers/Bat_oneoff_import_student.php:58-100 (公開メソッド内で受講者テーブルの全消去と再取込)
  - alflearning/alflearning-cms/application/controllers/Bat_mail.php:83 (メール送信)
  - alflearning/alflearning-cms/application/controllers/Material_convert.php:37-52, Stream_ftp.php:13-28 (同様にログイン確認なし)
  - alflearning/alflearning-cms/application/hooks/RefererCheck.php:19-24 (参照元が空の GET は無条件に通す)
  - alflearning/alflearning-cms/application/config/routes.php (既定のルーティング。バッチ用の経路制限なし)
  - alflearning/alflearning-cms/.htaccess (IP 制限・Basic 認証がコメントアウト)
  - raw/server-config/nichiben-batch/crontab-root.txt:23 (正規の起動はコマンドラインから)
verification:
  - "grep -L '^[^/]*is_cli' alflearning/alflearning-cms/application/controllers/Bat_*.php alflearning/alflearning-cms/application/controllers/Once_bat_*.php | wc -l  # コメントアウトされていない確認行を持たないバッチを数える（29）"
  - "grep -n 'is_cli' alflearning/alflearning-cms/application/controllers/Bat_*.php  # 3 本はいずれもコメントアウト行"
  - "grep -n 'TRUNCATE\\|DELETE FROM' alflearning/alflearning-cms/application/controllers/Bat_oneoff_*.php"
next_level_up: >
  バッチ用コントローラをすべてコマンドライン実行限定にする（または Web 公開ディレクトリの外へ移す）と、認証・認可の high が 1 件外れる。
customer_label: 本来サーバ内部でだけ動かす一括処理が、インターネットから誰でも起動できる状態になっている
---

## 何が起きているか

管理画面（CMS, kenshu-cms）のアプリには、定期実行や一度きりのデータ移行用の「バッチ処理」が通常の画面と同じ置き場所に 29 本あります。
正規の起動はサーバ内のコマンドライン（cron）からですが、**29 本すべてで、コマンドライン以外からの起動を拒む確認が有効になっておらず**（3 本は確認の行がコメントアウトされている）、ログインの確認もありません。
CMS は接続元の制限・Basic 認証がともにコメントアウトされており（`.htaccess`）、インターネットから到達できます。
唯一の共通チェック（`RefererCheck`）は「参照元が空の GET」を無条件に通すため、防御になっていません。

該当する処理の性質（代表）:

| 処理 | 内容 | 根拠 |
|---|---|---|
| 受講者データの一回限り取込 | **受講者テーブルを全件消去**してから CSV を再取込 | `Bat_oneoff_import_student.php:81-97` |
| 受講者・注文・講師の一回限り取込（他 5 本） | 既存データの削除・再登録 | `Bat_oneoff_import_*.php`, `Bat_oneoff_sync_data.php` |
| CSV からの受講者更新 | 受講者テーブルの一括更新（実行時間・メモリ上限を解除） | `Bat_update_student_from_csv.php:3-6` |
| メール送信 | 受講者向けメールの送信 | `Bat_mail.php:83` |
| 教材変換・配信サーバ連携 | 外部コマンド実行を伴う重い処理 | `Material_convert.php`, `Stream_ftp.php` |

```php
// Bat_oneoff_import_student.php:81-97（抜粋）
public function oneoff_import(){
    ...
    $this->db->query("TRUNCATE TABLE student ");
    $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'student' ");
```

## リスク・影響

外部の第三者や誤操作によって、これらの一括処理が意図しないタイミングで起動できます。
受講者データの全消去・旧データでの上書き・メールの重複送信・サーバ資源の枯渇（実行時間とメモリの上限を外した処理の多重起動）が起こりえます。

事業への帰結: 受講者（弁護士会員）全員の登録データが、ログインなしの操作で消去・上書きされうる状態です。復旧まで研修サイト全体が使えなくなります。

## 推奨アクション

バッチ用コントローラを Web から起動できないようにする。役目を終えた一回限りの処理は撤去する。

**規模感の判断材料（必須）:** コード修正＋設定変更・影響範囲: バッチ用コントローラ 29 本（うち一回限り 10 本＋試験用 3 本は撤去候補）・数日〜1 週間規模

1. 一回限り（`Bat_oneoff_*`, `Once_bat_*`, `*_test`）の処理を本番から撤去する
2. 残す定期処理はコマンドライン実行のときだけ動くようにする
3. CMS 全体に接続元制限を戻すか、Web サーバ設定でバッチ用の経路を遮断する

## 検証方法

- 撤去・制限後、Web からバッチの経路にアクセスすると 404／403 になること
- cron からの正規起動が従来どおり動くこと（batch サーバのログで確認）
