# CLAUDE.md

このファイルは、リポジトリ内のコードを扱う際に Claude Code (claude.ai/code) へのガイダンスを提供します。

## プロジェクト概要

日本弁護士連合会（日弁連）向け PHP e ラーニングプラットフォームです。3 つのアプリケーションで構成されています。

- **alfproduct** — フロントエンド（カスタム PHP。WordPress は現在未使用だが、過去の WP ソースを一部流用したコードが残存）
- **alflearning-cms** — コンテンツ管理システム（CodeIgniter 3）
- **alflearning-api** — REST API バックエンド（CodeIgniter 3）

### 環境

**OS & Kernel**

```
$ cat /etc/os-release
NAME="Amazon Linux"
VERSION="2023"
ID="amzn"
ID_LIKE="fedora"
VERSION_ID="2023"
PLATFORM_ID="platform:al2023"
PRETTY_NAME="Amazon Linux 2023.8.20250804"
ANSI_COLOR="0;33"
CPE_NAME="cpe:2.3:o:amazon:amazon_linux:2023"
HOME_URL="https://aws.amazon.com/linux/amazon-linux-2023/"
DOCUMENTATION_URL="https://docs.aws.amazon.com/linux/"
SUPPORT_URL="https://aws.amazon.com/premiumsupport/"
BUG_REPORT_URL="https://github.com/amazonlinux/amazon-linux-2023"
VENDOR_NAME="AWS"
VENDOR_URL="https://aws.amazon.com/"
SUPPORT_END="2029-06-30"
$ uname -a
Linux ip-172-31-40-217.ap-northeast-1.compute.internal 6.1.147-172.259.amzn2023.x86_64 #1 SMP PREEMPT_DYNAMIC Tue Jul 29 19:28:53 UTC 2025 x86_64 x86_64 x86_64 GNU/Linux

```

**Apache**

```
$ httpd -v
Server version: Apache/2.4.64 (Amazon Linux)
Server built:   Jul 15 2025 00:00:00
$ cat /etc/httpd/vhost.d/vhost.conf
#NameVirtualHost *:80
#NameVirtualHost *:443
#Listen 80
#NameVirtualHost *:80
#Listen 443

# NameVirtualHost *:443

#========================================================================#
#= HTTPS
#========================================================================#
#------------------------------------------------------------------------#
# product
#------------------------------------------------------------------------#
<VirtualHost *:443>
    ServerName nichibenren-stg2.alfcloud.com
    #ServerName nichibenren-stg.alfcloud.com
    #ServerAlias nichibenren-stg.alfcloud.com kenshu.nichibenren.or.jp nichibenren.alflearning.com nichibenren-prod-alb-1198125528.ap-northeast-1.elb.amazonaws.com nichibenren-stg-alb-1853304167.ap-northeast-1.elb.amazonaws.com

    Include vhost.d/_ssl_.inc

    DocumentRoot /srv/alfproduct/public
    #AmAgent On
    #AmAgentConf /usr/share/httpd/web_agents/apache24_agent/instances/agent_4/config/agent.conf

    Alias /upload/video_thumbnail/ /alflearning-data/video_thumbnail

    #RewriteEngine On
    #RewriteCond %{HTTPS} !=on
    #RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    <Directory /srv/alfproduct/public >
        Options All
        AllowOverride All
        #Allow open access:
        Require all granted
        <LimitExcept GET POST>
            Deny from all
        </LimitExcept>

        <FilesMatch "\.(pl|cgi)$">
            Require all denied
        </FilesMatch>
    </Directory>

    SSLCertificateFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/privkey.pem
    SSLOpenSSLConfCmd DHParameters "/etc/ssl/certs/dhparams.pem"
    #Include /etc/letsencrypt/options-ssl-apache.conf

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#------------------------------------------------------------------------#
# cms
#------------------------------------------------------------------------#
<VirtualHost *:443>
    ServerName cms.nichibenren-stg2.alfcloud.com
    #ServerName cms.nichibenren-stg.alfcloud.com
    #ServerName cms.nichibenren.alfredcore.net
    #ServerAlias kenshu-cms.nichibenren.or.jp cms-nichibenren.alflearning.com nichibenren-stg-cms-alb-1937568370.ap-northeast-1.elb.amazonaws.com

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    Include vhost.d/_ssl_.inc
    Include vhost.d/alflearning/_common_.inc
    Include vhost.d/alflearning/cms.inc

    SSLCertificateFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/privkey.pem
    SSLOpenSSLConfCmd DHParameters "/etc/ssl/certs/dhparams.pem"
    #Include /etc/letsencrypt/options-ssl-apache.conf

    # タイムアウト設定
    Timeout 3600
    ProxyTimeout 3600

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#------------------------------------------------------------------------#
# api
#------------------------------------------------------------------------#
<VirtualHost *:443>
    ServerName api.nichibenren-stg2.alfcloud.com
    #ServerName api.nichibenren-stg.alfcloud.com
    #ServerName api.nichibenren.alfredcore.net
    #ServerAlias kenshu-api.nichibenren.or.jp api-nichibenren.alflearning.com

    Include vhost.d/_ssl_.inc

    #RewriteEngine On
    #RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    Include vhost.d/alflearning/_common_.inc
    Include vhost.d/alflearning/api.inc

    SSLCertificateFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/nichibenren-stg2.alfcloud.com/privkey.pem
    SSLOpenSSLConfCmd DHParameters "/etc/ssl/certs/dhparams.pem"
    #Include /etc/letsencrypt/options-ssl-apache.conf

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#========================================================================#
#= HTTP
#========================================================================#
#------------------------------------------------------------------------#
# default
#------------------------------------------------------------------------#
#<VirtualHost *:80>
#    ServerName any
#    Include vhost.d/_default_.inc
#
#    RewriteEngine On
#    RewriteCond %{REQUEST_METHOD} OPTIONS
#    RewriteRule .* - [R=405,L]
#
#    <Location />
#        Order Deny,Allow
#        Deny from all
#    </Location>
#
#    <Directory /var/www/html >
#        Options All
#        AllowOverride All
#        #Allow open access:
#        Require all granted
#        <LimitExcept GET POST>
#            Deny from all
#        </LimitExcept>
#
#        <FilesMatch "\.(pl|cgi)$">
#            Require all denied
#        </FilesMatch>
#    </Directory>
#
#    <IfModule mod_headers.c>
#        Header set Referrer-Policy "strict-origin-when-cross-origin"
#    </IfModule>
#</VirtualHost>
<VirtualHost *:80>
    ServerName any
    DocumentRoot "/var/www/html"

    <Directory /var/www/html>
        Options All
        AllowOverride All
        Require all granted
        <LimitExcept GET POST>
            Deny from all
        </LimitExcept>

        <FilesMatch "\.(pl|cgi)$">
            Require all denied
        </FilesMatch>
    </Directory>

    <Directory /var/www/html/pma>
        AllowOverride All
        Require all granted
    </Directory>

    <Location />
        Require all granted
    </Location>

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#------------------------------------------------------------------------#
# product
#------------------------------------------------------------------------#
<VirtualHost *:80>
    ServerName nichibenren-stg2.alfcloud.com
    #ServerName nichibenren-stg.alfcloud.com
    #ServerName nichibenren.alfredcore.net
    #ServerAlias nichibenren-stg.alfcloud.com nichibenren.alfredcore.net nichibenren-prod-alb-1198125528.ap-northeast-1.elb.amazonaws.com nichibenren-stg-alb-1853304167.ap-northeast-1.elb.amazonaws.com

    #RewriteEngine On
    #RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    DocumentRoot /srv/alfproduct/public
    #AmAgent On
    #AmAgentConf /usr/share/httpd/web_agents/apache24_agent/instances/agent_4/config/agent.conf

    Alias /upload/video_thumbnail/ /alflearning-data/video_thumbnail

    <Directory /srv/alfproduct/public>
        Options All
        AllowOverride All
        Require all granted
        <LimitExcept GET POST>
            Deny from all
        </LimitExcept>

        <FilesMatch "\.(pl|cgi)$">
            Require all denied
        </FilesMatch>
    </Directory>
    #RewriteEngine on
    #RewriteCond %{SERVER_NAME} =nichibenren-stg2.alfcloud.com
    #RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#------------------------------------------------------------------------#
# cms
#------------------------------------------------------------------------#
<VirtualHost *:80>
    ServerName cms.nichibenren-stg2.alfcloud.com
    #ServerName cms.nichibenren-stg.alfcloud.com
    #ServerName cms.nichibenren.alfredcore.net
    #ServerAlias kenshu-cms.nichibenren.or.jp cms.nichibenren.alfredcore.net nichibenren-stg-cms-alb-1937568370.ap-northeast-1.elb.amazonaws.com

    #RewriteEngine On
    #RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    Include vhost.d/alflearning/_common_.inc
    Include vhost.d/alflearning/cms.inc

    #RewriteEngine on
    #RewriteCond %{SERVER_NAME} =cms.nichibenren-stg2.alfcloud.com
    #RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]

    # タイムアウト設定
    Timeout 3600
    ProxyTimeout 3600

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>

#------------------------------------------------------------------------#
# api
#------------------------------------------------------------------------#
<VirtualHost *:80>
    ServerName api.nichibenren-stg2.alfcloud.com
    #ServerName api.nichibenren-stg.alfcloud.com
    #ServerName api.nichibenren.alfredcore.net
    #ServerAlias kenshu-api.nichibenren.or.jp api-nichibenren.alflearning.com

    #RewriteEngine On
    #RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=405,L]

    Include vhost.d/alflearning/_common_.inc
    Include vhost.d/alflearning/api.inc

    #RewriteEngine on
    #RewriteCond %{SERVER_NAME} =api.nichibenren-stg2.alfcloud.com
    #RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]

    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
    </IfModule>
</VirtualHost>


```


**PHP**

```
$ php -v
PHP 8.3.23 (cli) (built: Jul  1 2025 16:52:12) (NTS gcc x86_64)
Copyright (c) The PHP Group
Zend Engine v4.3.23, Copyright (c) Zend Technologies
    with Zend OPcache v8.3.23, Copyright (c), by Zend Technologies
$ php -m
[PHP Modules]
bcmath
bz2
calendar
Core
ctype
curl
date
dba
dom
enchant
exif
FFI
fileinfo
filter
ftp
gd
gettext
gmp
hash
iconv
intl
json
ldap
libxml
mbstring
mysqli
mysqlnd
odbc
openssl
pcntl
pcre
PDO
pdo_mysql
PDO_ODBC
pdo_pgsql
pdo_sqlite
pgsql
Phar
posix
pspell
random
readline
Reflection
session
shmop
SimpleXML
snmp
soap
sockets
sodium
SPL
sqlite3
standard
sysvmsg
sysvsem
sysvshm
tidy
tokenizer
xml
xmlreader
xmlwriter
xsl
Zend OPcache
zip
zlib

[Zend Modules]
Zend OPcache

$ php --ini
Configuration File (php.ini) Path: /etc
Loaded Configuration File:         /etc/php.ini
Scan for additional .ini files in: /etc/php.d
Additional .ini files parsed:      /etc/php.d/10-opcache.ini,
/etc/php.d/20-bcmath.ini,
/etc/php.d/20-bz2.ini,
/etc/php.d/20-calendar.ini,
/etc/php.d/20-ctype.ini,
/etc/php.d/20-curl.ini,
/etc/php.d/20-dba.ini,
/etc/php.d/20-dom.ini,
/etc/php.d/20-enchant.ini,
/etc/php.d/20-exif.ini,
/etc/php.d/20-ffi.ini,
/etc/php.d/20-fileinfo.ini,
/etc/php.d/20-ftp.ini,
/etc/php.d/20-gd.ini,
/etc/php.d/20-gettext.ini,
/etc/php.d/20-gmp.ini,
/etc/php.d/20-iconv.ini,
/etc/php.d/20-intl.ini,
/etc/php.d/20-ldap.ini,
/etc/php.d/20-mbstring.ini,
/etc/php.d/20-mysqlnd.ini,
/etc/php.d/20-odbc.ini,
/etc/php.d/20-pdo.ini,
/etc/php.d/20-pgsql.ini,
/etc/php.d/20-phar.ini,
/etc/php.d/20-posix.ini,
/etc/php.d/20-pspell.ini,
/etc/php.d/20-shmop.ini,
/etc/php.d/20-simplexml.ini,
/etc/php.d/20-snmp.ini,
/etc/php.d/20-soap.ini,
/etc/php.d/20-sockets.ini,
/etc/php.d/20-sodium.ini,
/etc/php.d/20-sqlite3.ini,
/etc/php.d/20-sysvmsg.ini,
/etc/php.d/20-sysvsem.ini,
/etc/php.d/20-sysvshm.ini,
/etc/php.d/20-tidy.ini,
/etc/php.d/20-tokenizer.ini,
/etc/php.d/20-xml.ini,
/etc/php.d/20-xmlwriter.ini,
/etc/php.d/20-xsl.ini,
/etc/php.d/20-zip.ini,
/etc/php.d/30-mysqli.ini,
/etc/php.d/30-pdo_mysql.ini,
/etc/php.d/30-pdo_odbc.ini,
/etc/php.d/30-pdo_pgsql.ini,
/etc/php.d/30-pdo_sqlite.ini,
/etc/php.d/30-xmlreader.ini

$ php -i | grep -E 'memory_limit|upload_max|post_max|max_execution'
max_execution_time => 0 => 0
memory_limit => 768M => 768M
post_max_size => 2048M => 2048M
upload_max_filesize => 2048M => 2048M

$ composer --version
Composer version 2.8.6 2025-02-25 13:03:50
PHP version 8.3.23 (/usr/bin/php)
Run the "diagnose" command to get more detailed diagnostics output.

```

**ファイル・マウント**

```
$ df -h
ファイルシス           サイズ  使用  残り 使用% マウント位置
devtmpfs                 4.0M     0  4.0M    0% /dev
tmpfs                    978M     0  978M    0% /dev/shm
tmpfs                    392M  504K  391M    1% /run
/dev/xvda1                16G   12G  4.7G   71% /
tmpfs                    978M   11M  968M    2% /tmp
/dev/xvda128              10M  1.3M  8.7M   13% /boot/efi
172.31.21.103:/exports    30G   24G  6.9G   78% /alflearning-data
tmpfs                    196M     0  196M    0% /run/user/1000
$ mount | grep alflearning
172.31.21.103:/exports on /alflearning-data type nfs4 (rw,relatime,vers=4.2,rsize=131072,wsize=131072,namlen=255,hard,proto=tcp,timeo=600,retrans=2,sec=sys,clientaddr=172.31.40.217,local_lock=none,addr=172.31.21.103)

```


## コマンド

**PHP 依存ライブラリのインストール（PDF 系）:**
```
cd alfproduct/module && composer install
```

**マウントディレクトリの確認（ステージング/本番）:**
```
bash alflearning/mountcheck.sh
```

自動テストスイートは存在しません。動作確認はブラウザまたはステージング環境（`nichibenren-stg`/`nichibenren-stg2.alfcloud.com`）へのデプロイで行います。

### stg2 環境へのブラウザ自動テスト（XSS 対応の確認用）

`secure_report/【画面共有で使用した版】XSS対応_全量確認と対応方針_2026-09-29.xlsx`（「7_今後の進め方」のテストの観点）と `secure_report/XSS対応_承認用資料_2026-10-01.xlsx`（許可タグ）の内容を、stg2 に実際にアクセスして確認してよい。

- **対象は stg2 のみ**（`nichibenren-stg2.alfcloud.com` / `cms.nichibenren-stg2.alfcloud.com` / `api.nichibenren-stg2.alfcloud.com`）。本番・stg（旧）には実行しない。
- **受講者 SSO**（OpenAM: `www.nichibenren-member-sso.jp`）は本番と共用の外部環境。`.env.stg2` の検証用アカウント（`STG2_STUDENT_USER`）でログインした場合のみ stg2 へ遷移する仕組みのため、このアカウントでのログインは可。SSO 側では何も変更せず、XSS 用の値も送らない。ログインは最小限にし、取得したセッションを使い回す。
- **stg2 へのテストデータ書き込みは許可済み**。stg2 は独立した環境で本番への影響はない。XSS 用の値を含む登録も可。ただしテスト用と分かる名前（例: `[XSSTEST]` を先頭に付ける）にし、テスト後は削除する。
- **stg2 のコードはリポジトリと同期している前提**で確認してよい（差異が疑われる場合は全量一覧 No.42 を参照）。
- **IP 制限**: 開発用の実行環境からは CMS を含めアクセス可能。
- **認証情報**（Basic 認証・CMS 管理者・受講者テストアカウント）はリポジトリに書かない。リポジトリ直下の `.env.stg2`（`.gitignore` 済み）か環境変数で渡す。キー名:
  - `STG2_STUDENT_URL` / `STG2_CMS_URL` … 確認用URL
  - `STG2_API_URL` … alflearning-api の確認用URL（例: `https://api.nichibenren-stg2.alfcloud.com/`。E-58でのみ使用。未設定ならE-58はスキップされる）
  - `STG2_BASIC_USER` / `STG2_BASIC_PASS` … Basic 認証
  - `STG2_CMS_USER` / `STG2_CMS_PASS` … CMS 管理者
  - `STG2_STUDENT_USER` / `STG2_STUDENT_PASS` … 受講者SSO
  - `STG2_CMS_BAR_USER` / `STG2_CMS_BAR_PASS` … CMS 管理者（単位会）
  - `STG2_CMS_LIMITED_USER` / `STG2_CMS_LIMITED_PASS` … CMS 管理者（権限を限定した管理者）
  - `STG2_STUDENT_ETHIC_USER` / `STG2_STUDENT_ETHIC_PASS` … 受講者（代替倫理研修の権限あり）SSO
  - 値が未記入のアカウントを使うテストはスキップする。
- **メール送信を伴う操作は自動テストしない（手動で実施）**。stg2 には本番からコピーしたデータ（実在の受講者のメールアドレス）がある。対象: 商品管理の注文詳細（amount_order/info.php）・入金アップロード（bank_upload）・メルマガ送信（cron/mailmagazine_submit.php）・問い合わせ（受講者 inquiry/send.php、商品管理 inquiry/regist.php）・決済（settlement/payment_bank・card・free・passport_user、araigae_upload）、CMS のバッチ（Bat_mail・Bat_exam_error_check・Bat_update_student_from_csv_check）。
- **書き込みを伴うテストの前に stg2 の DB をダンプする**。CMS・API・商品管理・受講者サイト・WordPress はすべて同じ DB `alflearning` を使う。画面からの元の値の保存では、ログ（`applog`）・メールのキュー（`queue`）・履歴・関連テーブル（`rel_*`）を戻せないため、DB ごとのダンプから復元する。ダンプの取得・復元は人が行う（自動テストからは実行しない）。
  ```
  mysqldump --single-transaction --routines alflearning > alflearning_before_test_<日付>.sql
  ```
  ダンプファイルには実在の受講者の個人情報が含まれるため、リポジトリ配下に置かない・コミットしない。
- **CMS でテストデータを登録するときの注意**: 授業は開始の1週間前・前日・1時間前に、cron（Bat_mail）が受講者へ通知メールを送る。テスト・アンケートのリマインド設定はメールのキューに登録される。テストデータは、開始日を遠い過去（または遠い未来）にし、リマインドは設定せず、受講者はテスト用アカウントだけを割り当てる。
- **ツール**: Node.js + Playwright（ヘッドレス Chromium）。スクリプトは `tools/stg2-e2e/`。結果は `tools/stg2-e2e/test-results/`、保存したセッションは `tools/stg2-e2e/.auth/`（どちらもコミットしない）。
  ```
  cd tools/stg2-e2e
  npm install && npx playwright install chromium   # 初回のみ
  node login.js                                     # CMS・受講者SSOでログインしセッション保存（cms|student で片方のみ）
  node check-debug-output.js cms 400 --seeds        # 観点3: 確認用出力・PHPエラーの検出（student も同様）
  node summarize.js test-results/debug-output-cms-<日時>.json
  ```
  - `check-debug-output.js` はリンクを GET で辿るだけでフォーム送信はしない。更新系・ログアウト・決済・試験・プレイヤー等の URL はスキップする。CMS は 5 分ごとにセッション ID が更新されるため、切れた場合は自動で再ログインする（受講者 SSO は自動再ログインしない）。CMS の巡回は 10 分以上かかる。
  - 受講者サイトの商品詳細は、無料 e ラーニング商品を開くとテストアカウントに 0 円注文が自動作成される（アプリの仕様）。
- **進め方**: データを書き込まない確認（観点 3: 各画面の HTML ソースに SQL・`var_dump`・`<!--[` 等の確認用出力が無いこと）から始め、書き込みを伴う観点（1・2・4〜7）はその後に行う。

#### 脆弱性テスト計画（`secure_report/XSS対応_脆弱性テスト計画*.xlsx`）の自動実行

計画の各行（ID: A-/B-/C-/D-/E-/F-/G-）を自動実行する。結果は計画の ID ごとの「NG候補」で出る。最終判定（OK/NG）は人が再現確認して xlsx に記入する。

- **`test-plan/plan-fixes.json`**: `secure_report/テストプランレビュー.pdf`（2026-10-07）で指摘された、計画(xlsx)の抜けのうち
  xlsxの改訂を待たずに反映できるものを記録したパッチ（Git管理する。`export-plan.py` が xlsx 読み込み後に自動適用し、
  plan.json にだけ反映する。xlsx 本体は直接編集しない）。内容: 新規行の追加(`add`)・既存行への追記(`modify`)。
  xlsx が正式に改訂されたら、重複する内容は削除してよい。
  また同じ `export-plan.py` が、備考に★が付いた行(エスケープ関数が見当たらない表示の候補。191行)に、
  項目名からのヒューリスティックで期待結果の仮分類(`reviewHint`)を自動付与する（レビュー 4.3。要レビュー前提の仮置き）。

```
cd tools/stg2-e2e
python test-plan/export-plan.py                      # xlsx(最終更新日時が最新のもの) → test-plan/plan.json（openpyxl 必要。コミットしない）
node run-xss-search.js cms                           # 段階A: S8 検索条件（CMS・商品管理）。student も同様。--full 全バリエーション / --each 1項目ずつ / --only A-0010,...
node run-xss-form.js --site cms --plan-only          # 段階A: 登録系(S1〜S7・S9・S10・S11、入力→確認画面まで)。まず --plan-only で対象・対象外を確認してから実行
node run-xss-http.js                                 # 段階A: 入口以外・HTTP(F-01〜F-09)と重点項目 E-04・E-08・E-19・E-47・E-50・E-51・E-52・E-54・E-56・E-57・E-58（--only F-03 等で絞り込み。--probe-php は下記）
node run-xss-student-exam.js --accept-writes         # 段階B: 受講者サイトの試験・アンケート（下記）
node run-xss-data-linked.js --accept-writes          # 段階B: 重点項目 E-10・E-12・E-13・E-14・E-49（実データ紐付けが必要だった項目）
                                                       # ＋段階A相当(読み取りのみ。--accept-writes 不要): E-20・E-60（下記）
node report-xss.js                                   # 最新の結果を計画のID単位に集計し、test-results/xss-report-*.csv を出力
```

- **段階A（`run-xss-search.js`・`run-xss-form.js`・`run-xss-http.js`）は GET と確認画面までの送信だけ**（登録・更新・削除・アップロード・メール送信・決済はしない）。DB のダンプは不要。
  - `run-xss-form.js`: 入力画面の各項目に攻撃文字列を入れて「確認」ボタンまで押す（`--site cms|product|student`。product は商品管理(Smarty)、student は受講者サイト。書き込み防止のガードは `xss-form-lib.js` の `submitGuarded`）。画面ごとに検証を通る値が要る場合は `test-plan/form-defaults.json` に追記する。`--check-pages` で、攻撃文字列なしに確認画面まで進めるかだけを事前に調べられる。
    - S9(ファイルのアップロード)は、項目種別が file の欄に Playwright の `setInputFiles`（ファイル名・中身をメモリ上のバッファで指定。OSのファイル名制限を受けない）で、ファイル名または中身(HTML・SVG)に攻撃文字列を入れる。対象以外の file 欄は無害なダミーファイルで埋める(既定はPDF。拡張子チェックがある画面は `form-defaults.json` で項目名に `"csv"` を指定するとCSV形式のダミーになる。例: `cms_exam_problem_import/edit`)。
    - S10(パスワード)は、通常の往復確認(値が戻らないと「異なる」と検出する)とは期待が逆のため、確認画面・エラー時の再表示に送信した値がそのまま残っていないか(平文表示)を別途検出する。
- **受講者 SSO（本番と共用）へは通信しない**。ブラウザ側で SSO ドメインへの通信を遮断している。未ログインの受講者サイトはどの URL も SSO へ遷移するため、受講者サイトの確認は保存済みセッション（`node login.js student`）で行い、確認できなかったものは「対象外」と記録する。
- 攻撃文字列の実行用定義は `xss-payloads.js`（正本は xlsx の「付録_攻撃文字列」）。検出ロジックは `detect.js`。
- E-08 は、開発用らしき PHP（`*_dev.php` 等）を既定では**開かず、一覧のみ**出す（PHP は開くと実行され、メール送信等の副作用がありうる）。内容を確認してから `--probe-php` を付ける。
- 受講者サイトは、決済（`settlement/*`）を除き可能な限り自動化する方針。`run-xss-form.js` の `STUDENT_SKIP`／`STUDENT_NOT_FOUND` に、画面ごとに個別調査した除外理由がある（ethic_treaning の S2(自由記述)系は対象の項目が csrf_token のみで、item-less プレフィルタにより自動的に対象外になる。S11(URL改ざん)系は `run-xss-student-exam.js` の `ethic` で別途対応）。
  - player（動画プレーヤー）は 2026-10-07 の方針変更により対象外から外した。再生開始で視聴履歴が記録される副作用はあるが、専用のテストデータ・テスト用受講者に限定し、`submitGuarded`（確認・検索系以外のボタンは押さない）にも守られるため許容する方針。記録された履歴は、書き込みテスト後の DB 復元で元に戻す。

##### 段階B: 受講者サイトの試験・アンケート（`run-xss-student-exam.js`）

CMS・商品管理・会員登録の「確認」画面と異なり、試験・アンケートは確認画面に見える画面（`answer_check*.php` 等）が、表示に進んだ時点で回答を DB へ書き込む（`DbConnect::execute()` で確認済み）。書き込みなしで確認画面へ到達する経路がないため、書き込みを許容して実行する。

- 事前準備: CMS でテスト専用の講座・試験・アンケートを作成し（`STG2_STUDENT_USER` だけを割り当てる。開始日は遠い過去・リマインドなし）、`tools/stg2-e2e/test-plan/student-entry.json` に実際の pid・eid・e2id 等を記入する。未記入のシナリオは自動でスキップされる。
- 対象: `exam_plain`（`/exam/index.php`。書き込みなし）・`exam_choice`（`/exam/index1.php`。書き込みあり）・`exam_freetext`（`/exam/index2.php`→`confirm2.php`。書き込みあり。最初の設問のみ回答し最終提出はしない）・`survey`（`/exam2/index.php`。書き込みなし）。書き込みがあるシナリオは `--accept-writes` が必須。
- 回答を1件登録した直後に、`result*.php`（採点結果）・`resubmit_index*.php`（再提出。到達できなければ対象外として記録するだけ）・`confirm1.php`（選択式の下書き回答の確認。読み取り専用）も合わせて読み取り専用で確認する（`RESULT_CHECKS`・`RESUBMIT_CHECKS`・`CONFIRM_CHECKS`）。
- `ethic`（代替倫理研修。`/ethic_treaning/`）: 対象行はすべて URL パラメータ(pid・qid)の改ざん確認（多肢選択のため文字列注入の対象項目はない）。専用の権限を持つ別アカウント(`STG2_STUDENT_ETHIC_USER`)が必要なため、`node login.js student-ethic` で別セッション(`.auth/student-ethic.json`)を作り、`test-plan/student-entry.json` の `"ethic"` に実際の pid・qid を記入する（未設定ならスキップ）。書き込みなしのため `--accept-writes` は不要。
- 書き込みを行った場合は、実行後に stg2 の DB をダンプから復元する（人が実施）。
- **計画(xlsx)側の要確認事項**: `/exam2/index1.php`・`confirm1.php`・`confirm2.php`・`answer_check1.php`・`resubmit_index1/2.php`・`result1/2.php`・`resubmit_exec_result1.php` はリポジトリに実体がない（exam2 は `index.php`→`answer_check.php` の1系統のみで、exam(非2)の番号付きファイル構成を誤って複製したとみられる）。`/exam/resubmit_exec_result1.php` も同様に実体がない（`/exam/confirm1.php`・`confirm2.php` 自体は実在し、それぞれ下書き回答の確認画面として使われている）。

##### 段階B: 重点項目 E-10・E-12・E-13・E-14・E-49（実データ紐付けが必要だった項目。`run-xss-data-linked.js`）

**2026-10-08 重要な発見（[B-13]）**: 本節が依存する`registerMarkerStudent()`(受講者のテスト登録)は、`member/regist.php:505`の`mysql_insert_id()`呼び出し(PHP 7で削除済み・PHP8.3では未定義)により**常に失敗する**ことが判明した。登録自体(`INSERT INTO student`)は成功するが直後にFatal Errorで中断し、以降の講座登録等が行われないため、`/cms_student`の検索結果にも出てこない（＝本節のE-10/E-12/E-13/E-14/E-49・新規追加のB-0015は、現状**実行しても受講者が見つからず「要確認」止まりになる**。過去の「自動化済み」の記録は、実際に受講者登録が機能した状態で検証できていたか要再確認）。[B-13]（`secure_report/stg2自動テストで発見した不具合_2026-10-07.md`）のPHP修正が先に必要。

E-10(講座確認の受講者一覧)・E-12(売上の会員詳細)・E-13(売上の注文詳細)・E-14(受講者検索ポップアップ)は、実在の受講者・注文データが無いと表示を確認できないとされていたが、2026-10-07 の調査で CMS 側の事前セットアップ無しに次の方法で自動生成できることが分かった。

- 受講者: `member/regist.php` でテスト用の氏名(攻撃文字列入り)を登録するだけ(`S12-student-name` と同じ仕組み)。
- 注文: **無料のeラーニング商品の詳細画面を開くと、0円の注文が自動作成される**（アプリの仕様。`alfproduct/public/product/detail.php` の `product_type_add==1 && price<=0` の分岐で確認済み。決済は発生しない）。
- E-10 の受講者一覧: 既存の任意の講座の edit→confirm（**DBへは書き込まない**。段階Aと同じ）で、受講者欄にテスト受講者のIDを追加して送信するだけで確認画面に表示される。講座IDは `/cms_cource/` の一覧から自動取得する。
- E-14: `product/search_student.php` に POST `search_student_name` でテスト受講者を検索し、結果一覧の氏名のエスケープを確認する。
- 必要な設定は `test-plan/data-entries.json` の `free_product_pid`（無料のeラーニング商品のID）だけ（E-12/E-13 用）。未設定なら E-12/E-13 は対象外として記録される。
- 書き込みを伴うため（E-10 も受講者登録は必要）、いずれも `--accept-writes` が必須（段階B）。実行前に stg2 の DB をダンプすること。
- E-49(全量一覧No.49の退行確認。商品検索スマホ版のカテゴリ名): `cms_category/newdata` でテスト専用のカテゴリ(攻撃文字列入りの名前)を登録するだけで確認できる(`cms_category` は `wp_terms`/`wp_term_taxonomy` に直接書き込むため、`S12-cat` の実行有無に関わらず単体で成立する)。スマホ版UAで `/search/index.php` を開き、画面内スクリプト(`arr_cat_name`)のエスケープを確認する（カテゴリ一覧の生成に絞り込み条件が無いため検索操作は不要）。2026-10-08 実装済み。
- B-0015(売上の注文詳細「atena」欄): 手動実施バケットの監査中に発見。確認画面を経由せず別フォーム(`downloadForm`→`pdf.php`。領収書の下書きPDFを画面内表示するだけでメール送信は無い)へ直接POSTされるため`run-xss-form.js`では対象にできず、E-13と同じoidに相乗りする形で`checkAtena()`として実装した(`--only E-12,E-13,B-0015 --accept-writes`。oid特定のためE-12・E-13を含める必要がある)。**上記[B-13]の影響で現状は実行できていない（受講者登録が機能してから再実行すること）。**

##### 段階A相当(読み取りのみ): E-20・E-60(領収書・受講証。`run-xss-data-linked.js`)

E-20(課題確認の提出ファイル名)・E-60(領収書・受講証PDFへの商品名反映)は、2026-10-08 の開発者回答により次の方式で実装した。いずれも**人がデータを用意し、自動テストは読み取りのみ**を行う（`--accept-writes` は不要）。

- **E-20**: 受講者側に課題提出画面が無く(`issue_submit` へのINSERT処理がソース上どこにも無い)自動生成できない。CMS側の表示(`/cms_issue/detail/{issue_id}`。`Cms_issue.php:299,301` 等で無害化済み)はデータさえあれば確認できるため、人が `issue_submit` テーブルへ直接SQLでテスト行を投入する（具体的なSQL・手順は `test-plan/data-entries.json` の `_comment_issue` 参照）。設定: `issue_id`。
- **E-60(領収書・受講証)**: 従来は商品の自前登録→0円注文の自動作成→`amount_order/info.php` の `mode=pay`（入金済みへの変更）までを自動化していたが、**`mode=pay` は受講者へメール送信を伴うことが判明した**（`order8.mail` を `mb_send_mail`。無料会場研修の購入 `payment_free.php` も同様に `order9.mail` を送信する）。CLAUDE.md の方針（メール送信を伴う操作は自動化しない）に反するため、**商品登録・購入・入金確認はすべて人がCMS画面・受講者サイトから手動で行う**方式に変更した（旧 `checkE60` の自動登録・`mode=pay` 自動実行コードは削除済み）。自動テストは、人が用意した注文IDでのダウンロード・確認のみ行う。
  - 領収書: `receipt_order_id`（入金済みの `tbl_order.order_id`）。ダウンロード成功で `receipt_flg` が立ち**1注文につき1回しか再現できない**（再実行には新しい入金済み注文が必要）。
  - 受講証: `ticket_order_detail_id`（会場研修注文の `tbl_order_detail.order_detail_id`）。発行済みフラグの更新処理が無効化されており**何度でも再実行できる**。会場研修は無料(price=0)でも登録でき、0円決済は `payment_free.php` 経由で `payment_status=2` になる（`product_type_add=2` にも対応済み）。
  - 具体的な人の作業手順（商品登録・購入の操作手順、正確な攻撃文字列）は `test-plan/data-entries.json` の `_comment_paid_order` を参照。
  - PDF内のテキスト検出はいずれもベストエフォート（圧縮されていると検出できないことがある）。

**見つからなかったもの**: 無し（E-20・E-60とも、人によるデータ投入を前提に自動化手段が確立した。2026-10-07時点の「機能未実装の疑い」「無料枠が無いと自動化できない」はいずれも解消済み）。

**2026-10-07 の調査で副次的に発見した脆弱性**: E-52(CSV出力)・E-58(API到達性)の調査中に、ログイン確認(`session_check`)が無く未認証でアクセスできるエンドポイントを2件発見（`report_product/csv_file.php`・`csv_file_utf.php`、および alflearning-api の `Csv_download`。後者は氏名・メールアドレスを含む受講者一覧CSVを出力する）。`secure_report/stg2自動テストで発見した不具合_2026-10-07.md` の [B-8]・[B-9] を参照。

**2026-10-08 判明: 自作の自動テストコード自体がメール送信ポリシーに違反していた**。旧 `checkE60` が自動実行していた `amount_order/info.php` の `mode=pay` は、受講者へメール送信を伴うことが開発者回答で判明した。これは該当テストを実行するたびにテスト用アカウントへ実メールを送っていたことを意味する（送信先はテスト用アカウントのみのため実害は小さいと見ている）。上記のとおりコードは削除・再設計済み。

**2026-10-08: `--check-pages` で実際にstg2へアクセスし、未自動化行の具体的な停止理由を調査・一部解消**。S1〜S11の対象画面のうち、確認画面まで進めない画面を洗い出した結果:
- **[B-2]（解消済み）**: `/cms_student/edit`・`/cms_student_sub_auth/edit`（計54行）が、受講講座チェックボックスの検証バグ（`set_rules`に`[]`が無い）で常に確認画面へ進めなかった。`Cms_student.php`・`Cms_student_sub_auth.php`・`Cms_exam2_review.php`の3箇所を修正(詳細は不具合まとめの[B-2]参照)。stg2へのデプロイ後、両画面とも確認画面まで進めることを確認済み。
- **CSV取り込み画面の既定値不足**: `/cms_exam_problem_import/edit`・`/cms_exam2_problem_import/edit`（計12行）が、テストコードのダミーアップロードファイルがPDF固定のため「csv形式のみ有効」エラーで進めなかった。`fillBenignFiles()`に項目ごとのファイル形式指定(`form-defaults.json`で`"csv"`を指定)を追加し解消（stg2で確認画面到達を確認済み。テストコード側のみの変更のためデプロイ待ちは無い）。
- **確認画面が無く直接登録される画面（解消済み。2026-10-08）**: `cms_issue`・`cms_book_library`・`cms_material`・`cms_class_material`・`cms_exam2_download`（計22行）は、newdata→confirmの2段階ではなく直接commit等へ送信する設計のため、書き込みをしない段階Aでは原理的に確認できなかった（`submitGuarded`が安全側に倒して送信を拒否していた）。方針の見直し（決済・メール送信以外は書き込みを許容）を受け、`submitGuarded`に`acceptWrites`オプションを追加し、`run-xss-form.js --accept-writes`で段階B相当として実行できるようにした。
  - 実際のボタンは文言が無い画像ボタン(`<input type=image src="btn_ok.png">`。確認・登録どちらの文言にも一致しない)だったため、安全な確認・検索ボタンが見つからない場合に限り、フォーム内の「本物の送信コントロール」(submit/image。削除・キャンセル等の文言は除く)を最後の手段として押すフォールバックを追加した。
  - `cms_exam2_download/edit`は実際には新規登録画面ではなく、アンケート回答をCSVでダウンロードする画面(`export()`。DBへは書き込まない)と判明。対象3行のうち`product_name`・`product_code`はソース確認(`Model_exam2_export::get_exam2_problem_export_data()`)の結果、SQLのLIKE条件(escape_like_str済み)としてのみ使われ画面・CSVのどちらにも値が出力されないため、反射経路が無いと判断して対象外にした(`run-xss-form.js`の`NO_REFLECTION`)。残り1行(`exam2_problem_lectures_ex[]`)は実行済み。
  - 実行中、ヘッドレスChromiumが偶発的にクラッシュ・応答不能になることがあった(特定の攻撃文字列が原因ではなく、別々の実行で別の箇所で再現したため偶発的な不安定さと判明)。1件の失敗でその画面のテスト全体を失わないよう、`xss-session.js`に`recreatePage()`(ブラウザごと起動し直す)を追加し、`run-xss-form.js`が攻撃文字列1件単位でクラッシュ・タイムアウトを検知して復旧・続行するようにした。また、CSVダウンロード応答を返す画面(上記`cms_exam2_download`等)でダウンロードイベントを放置するとページが不安定になっていたため、ダウンロードは即キャンセルするようにした。
  - 結果: `book_library_logic_name`(A-0018)・`book_library_lectures[]`(A-0020)・`exam2_problem_lectures_ex[]`(A-0134)でP05(`'>svg/onload`系)が「タグ内(属性)」で未エスケープのまま出現するNG候補を検出(人の再現確認待ち。詳細は`test-results/`の実行結果・`report-xss.js`の集計を参照)。他19行はOK。
- **確認ボタンの検出漏れ**: `cms_ranking`・`school_select`・`admin_top/outside_elearningmanager`（計18行）は原因未特定（`pickButton`が候補を見つけられない）。追加調査が必要。
- 商品管理・受講者サイトも同様に洗い出した結果、以下が判明:
  - **計画のURL記載ミス（計画データ不備。計24行、解消済み）**: `/alfproduct/inquiry/form.php`・`/alfproduct/mailmagazine/form.php`が実在しない。画面テンプレートの名前(`form.tpl`)と実際のPHPファイル名(`edit.php`)を取り違えたと見られる。`plan-fixes.json`で該当行の`url`を`edit.php`に修正し、stg2で確認画面到達を確認済み。
  - **`submitGuarded`の安全装置の不具合（重要・修正済み）**: `/member/regist.php`のような、確認・登録を同じURLへの送信で隠し`act`欄により切り替える自己post型フォームは、送信先URLに`regist`等の書き込み系の語が含まれているというだけで、ボタンの中身(文言)を見る前に送信を拒否していた。このため`run-xss-data-linked.js`の`registerMarkerStudent()`(E-10・E-12・E-13・E-14が依存)が、呼び出すたびに確認画面へ進めずに失敗していた可能性が高い(受講者登録ができていなかった)。`xss-form-lib.js`の`submitGuarded`を、ボタン単位の判定(文言・onclick引数)を先に行い、安全なボタンが見つからない場合に限って送信先URLを見る順序に修正。あわせて`/member/regist.php`のフリガナ(全角カタカナ必須)・パスワード(半角英数のみ)がfillBaseline既定値では検証を通らず確認画面に進めなかったため、`form-defaults.json`に専用の既定値を追加。stg2で、マーカー名が確認画面に正しく表示されるところまで確認済み(実際の登録完了(`submitComplete`)は書き込みを伴うため今回は実行していない)。
  - **新しいクラッシュ・情報漏えい発見**: `/alfproduct/mailmagazine/info.php`が、未定義変数(`$arr_err`)参照により入力値に関わらず常にクラッシュし、サーバー内部パスを含むスタックトレースが漏えいすることを発見([B-3]に追記。未修正)。
  - 商品管理の重い3画面(`product/add.php`等)に関連する`product/add_review.php`は、**2026-10-08の調査で「到達経路の無い」オーファンコードと判明し解消**(詳細は下記「405バケットの精査」節・不具合まとめ[B-14]参照)。
  - **GET方式の一覧・並び替えフォームが多数のボタン検出漏れの原因と判明（修正済み。計47行解消）**: `product/list_limit.php`等の「並び替え」「表示件数」は`<select>`のみでボタンが無く、GET送信のため本来は押すボタンが無くても安全に送信してよい画面だった。`submitGuarded`に「ボタンが見つからずGET送信のフォームなら、そのまま送信してよい」という分岐を追加（member/regist.php(27行)・受講者サイトのmypage一覧6画面・商品一覧6画面・cms_student_csv_upload、計47行が解消）。
  - **残り(16行。2026-10-08最終確定。旧「60行」「36行」は405バケットの件数を過小に見積もっていた暫定値だったため、下記のとおり精査し直した)の内訳**: `info_user_import.php`系(`product_lecture`・`product_lecture2`・`product_lecture_ethics`。計9行。到達経路はあるが親画面の実在IDが必要)、実在のIDが見つからない(商品管理4行: amount_user/product/product_ethics/product_passportの各info.php)、`ranking/index.php`(1行。C-0382。お気に入り登録フォームの原因未特定のタイムアウト)、`mailmagazine/info.php`のクラッシュ(1行。[B-3]。チームメイトが`$arr_err`初期化漏れを修正済みだがstg2未デプロイ)、`bank_upload`(1行。既存の手動実施方針どおりで想定内の挙動)。
  - **「実在のIDが見つからない」原因判明・一部解消(2026-10-08)**: `amount_user`・`product`・`product_ethics`・`product_passport`の各`info.php`は、2つの原因が重なって自動検出できていなかった。(1) `index.php`は検索結果一覧を直接返さず検索フォームだけを返す画面で、既存の実在ID探索ロジックは`index.php`へのGETだけを見ていた → 空検索のまま`search_form`を送信してから探すよう修正(`mid`・`pid`に加え`sid`もキー候補に追加)。(2) `info.php`はID無しで開いても`act`・`csrf_token`・`mid`等の項目名自体は(値が空のまま)存在する「空の骨組み」を返すため、項目名の有無だけを見る既存の判定では「実在データが無い」ことを検出できていなかった → `mid`・`pid`・`sid`の**値**が空でないことも確認するよう修正。あわせて、`product`系`info.php`の確認・検索に使えるボタンが「修正」(文言は無く`btn_revise.png`の画像のみ。`onclick="formSubmit('form1','add.php','edit')"`)しか無く、`add.php`側の`act='edit'`処理がテンプレート変数設定のみでDB書き込みが無いことをソースで確認のうえ、安全なボタンとして追加認識させた。**`amount_user`・`product_ethics`・`product_passport`は実行確認済み(OK)。`product`自体は`add.php`(商品管理の中でも特に重い画面)経由になるためタイムアウトし、4行中この1行のみ未解決。**
  - **405バケットの精査(2026-10-08)**: `product/add_review.php`(計画上44行)・`product_live_branch/add.php`(5行+重複行2行。計画データ不備として別途処理済み)は、コード全体(Smartyテンプレート・PHP)のどこからもリンクされていない「到達経路の無い」コントローラと判明(DB書き込みも無い)。`product/add.php`の実際の確認ボタンは自己post方式で`add.php`自身に送信される作りで、`add_review.php`は使われていない。詳細・対応方針の相談は不具合まとめ[B-14]参照。`plan-fixes.json`に経緯を記録し、405のまま「対象外」として扱う方針に確定した(計49行を「405で止まっている未解決」から「到達経路なしで対象外に確定」に移動)。
    - **残る405(`product_lecture`・`product_lecture2`・`product_lecture_ethics`の各`info_user_import.php`。計9行)は、到達経路あり(`info_user.tpl`から「CSV取り込み」ボタンで到達する正規の画面)と確認済みだが、到達には親画面(`info_user.php`)の実在ID(aid/pid)が必要なため、「実在のIDが見つからない」4行と合わせて同系統の未解決として残っている。**
    - **2026-10-08 追加調査・一部解消**: `info_user.php`（`product_lecture`・`product_lecture2`）・`info.php`（`product_lecture_ethics`）への到達経路(index→info→「管理」リンクでaid付きの`info_user.php`へ)を`run-xss-form.js`に実装し、同時に発覚した「項目名(pid・aid等)が個別登録用・CSV取り込み用の小さな付随フォームと本体の`list_form`に重複しており、誤って小さい方を送信対象にしてしまう」バグを`markBestForm()`で修正(`xss-form-lib.js`)。これにより`info_user.php`自身のS11/S12行(product_lecture: B-0320/321、product_lecture2: B-0341/342)と`product_lecture_ethics/info.php`のS11/S12行(B-0360/361)、計6行が確認画面まで到達できるようになった(`--accept-writes`が必要。安全なボタンが無いため)。
**2026-10-09訂正**: 実行直後は hidden項目(aid/atype/flg/mode/odid/oid/pid等)がP05系payloadで確認画面にタグ属性内・未エスケープのまま出現するNG候補として記録していたが、これは**テストハーネス自体の誤検知**と判明(詳細は下記「2026-10-09: inspect()の誤検知バグ」節参照)。自動テストは「未検証(ページ遷移なし)」のまま(なぜページ遷移が起きないかは未調査)だが、**B-0320・B-0341・B-0360は手動テスト(担当: 佐々木、Chrome操作はClaude)で別途確認済み**: 実際にテスト商品・テスト受講者を登録し、hidden項目改ざんを実機で検証した結果、CSRF検証漏れ・`odid`のSQL未エスケープ・配列送信での500エラー(内部パス漏えい)の3件を発見([B-22]参照)。コミット`0bae40c`で修正済みを再テストで確認し、**B-0320・B-0341・B-0360とも最終判定OK**。詳細は`tools/stg2-e2e/test-results/B-0320_テスト用商品登録_結果_2026-10-09.md`(Git管理外)参照。
      `info_user_import.php`自体(CSV取り込み画面。計9行)は、`product_lecture`・`product_lecture2`は到達できるようになったが、実際の「アップロードする」リンクのクリックで30秒タイムアウトが頻発する別の問題が残っており未解決(`submitGuarded`のacceptWritesフォールバックがフォーム全体を1候補として誤判定していたバグは別途修正済みだが、クリック自体の不安定さは未解明)。`product_lecture_ethics/info_user_import.php`(aidの概念が無くinfo.php自身からCSV取り込みフォームを送信する作り)はまだ到達自体ができていない。
  - **「POST方式でボタン検出の原因が未特定」の原因判明・一部解消(2026-10-08)**: `pickButton`が、ヘッダー共通の「ログアウト」リンク(`onclick="logout_confirm(...)"`)を、関数名に含まれる"confirm"という文字列だけで「確認ボタン」と誤判定し、本来の候補を評価する前に誤って選んでしまっていた(`ok()`のDENY_LABEL判定は文言(`c.t`)しか見ておらず、onclickの関数名は見ていなかったため)。DENY_LABELに「ログアウト・logout」を追加して解消。あわせて、対象項目が属する小さな付随フォーム(ファイルアップロード欄等)とは別に、実際の送信先である本体の`<form>`(action が確認系)が存在するが、壊れたHTML(閉じタグ不足等)によりCSSの子孫セレクタでは見つからない画面(cms_ranking)向けに、ブラウザのform IDL属性(`element.form`。DOM上の親子関係ではなく実際の関連付け)で照合するフォールバックを追加した。
    - **cms_ranking(16行)・school_select(2行)は解決**(実行確認済み。school_selectは`--accept-writes`が必要)。
    - **admin_top/outside_elearningmanager(2行)。解決**: 送信すると「システムエラーです(code:999)」になるが、これは入力値に関わらず常に出る(eLearning Manager契約が無いstg2環境固有の制約で、エスケープ確認とは無関係と判断。計画上もapi_key・api_urlの値は攻撃文字列でよい)。エラー画面自体が入力値をそのまま再表示するため、attacker文字列のエスケープ確認はこのまま実行でき、実行確認済み(OK 2件)。
    - **mypage/refusal.php(1行。C-0293)・mypage/refusal_confirm.php(1行。C-0294)。方針により手動**: ボタンは会員退会の実行ボタン(alt="会員退会を行う"。押すと購入履歴等の受講者データが削除される)。メール送信は無いが、新規テストデータの作成ではなく既存アカウントを退会させる操作のため手動対応とする方針に確定。`xss-form-lib.js`のDENY_LABELに「退会」を追加し、誤って自動クリックされないようにした。なお`/mypage/refusal_confirm.php`は404(実在しない)で、refusal.php自体が隠しaction欄による自己postingフォームと判明(計画データ不備の一種。重複行として処理)。
    - **ranking/index.php(3行。うち実テスト対象はC-0382の1行。他2行はS12で別対応)。調査済み・一部判明**: 対象フォーム(`favoriteForm`。お気に入り登録。ボタンを持たず他要素のonclickからJSで直接submit()される作り)向けに、フォーム内にボタンが1つも無い場合は直接`requestSubmit()`する処理を追加した。あわせて`pid`が空のまま送信するとページがハングすることを発見し、`form-defaults.json`に既定値(実在のpid)を追加。ただしテスト手順全体(他フィールドへの一括baseline入力を含む)を通すと依然タイムアウトが再現し、単体の送信だけなら問題が無いことまでは切り分け済み。原因未特定のため引き続き要調査。

**2026-10-08 の調査で副次的に発見した問題**: E-04(アンケート回答一覧)の調査中に、`Cms_exam2_review.php` のクラス名がファイル名と不一致で常に404になっていた問題と、`exam2_set_list()` が `$product_id`・`$exam2_id` を未定義変数のまま使っており常にPHP8のTypeErrorでクラッシュする問題の2件を発見し、どちらも修正した（[B-11]、解消済み。XSSとは別系統）。同じクラス名不一致のパターンが `Bat_get_alfstream_reading_history_mst.php`・`Bat_get_alfstream_reading_history_oneoff.php`・`Bat_report_oneoff_old.php` にも見つかっており未対応（使用有無の確認待ち）。E-59(Ajaxエンドポイント)の調査中に、`player/bookmark.php`・`insert_report_user_video_viewed.php`・`bookmark_delete.php` にログイン確認が無く、任意の`student_id`を指定して他人の視聴履歴・受講完了フラグを改ざんできる認可不備を発見（[B-12]）。

##### 段階B: S12（表示専用の値）・F-10（入力→全画面の自動追跡）（`run-xss-s12.js`）

「どこかの画面で攻撃文字列を登録 → 別の画面で、その値がエスケープされて表示されるか」を見る観点。1画面・1項目の確認では足りないため、(1) `test-plan/s12-entries.json` に書かれた画面で実際に値を登録し、(2) CMS・受講者サイトを広く巡回して、登録した識別マーカーの出現を探す（`s12-crawl-lib.js`）。

```
node run-xss-s12.js --register-only     # 登録のみ。test-results/s12-manifest.json に記録
node run-xss-s12.js --crawl-only        # 保存済み manifest を使って巡回のみ
node run-xss-s12.js                     # 登録→巡回を通しで実行（既定）
```

- **DB へ書き込む（段階B）。実行前に stg2 の DB をダンプすること。** 登録した値は削除せず残る（手動で削除する運用）。
- 登録先は `test-plan/s12-entries.json`（CMS の `newdata` 経由の新規登録のみ。既存レコードは編集しない）。カテゴリ名・講座名・授業名・講師名・課題名・図書室表示名・動画表示名/説明/タグ・教材表示名/説明・設問名(exam/exam2)・お知らせタイトル/本文/外部リンク(CMS)、商品名(product_live・product_passport)、受講者氏名(member/regist)の34件を登録する。`xss-form-lib.js` の `submitComplete`（登録ボタンを実際に押す。`run-xss-form.js` の `submitGuarded` とは逆）を使う。
- お知らせ（`cms_information`）は WordPress へ自動投稿される（G-09・E-24・E-25・E-28・E-30 に対応）。巡回は受講者サイトと同一オリジンの WordPress ページも自然にカバーする（別サイト指定は不要）。
- メール送信・決済を伴う入口（inquiry・mailmagazine・settlement）は対象外（CLAUDE.md の手動実施方針に合わせる）。
- 受講者本人の氏名変更（`/mypage/edit.php`）は、削除済みの PHP 関数（`mysql_real_escape_string()`。PHP 8 では未定義）の呼び出しが残っており現状は登録自体が失敗する見込みのため、`blockedBy` 付きで対象外にしてある。
- 巡回は `<a href>` を辿るだけのため、検索ポップアップ（`window.open`・`onclick` で開く画面。E-11・E-14・E-19 等の表示先）のように通常の巡回では到達しない画面は、`run-xss-s12.js` の `EXTRA_SEEDS` に明示的な開始点として追加している。
- **2026-10-09 実行済み（ダンプなしで実施。ユーザー指示）**: 34件のうち18件を登録（カテゴリ名・講座名・授業名・動画表示名/説明/タグ・設問名/本文/解説/模範解答(exam/exam2)・お知らせタイトル/本文/外部リンク・商品名(product_passport)・受講者氏名/学校名）。残り16件は以下の理由で未登録:
  - `product/add.php`・`product_ethics/add.php`系7件（商品名・備考・講師・検索キーワード等）: 登録処理が`locator('form[data-xss-form]').first()`待ちで毎回タイムアウト（30秒）。他の観点(S1〜S11)でも既知の「商品管理の重い登録画面」と同じ症状で、原因は未特定のまま。
  - 受講者氏名の住所欄(`S12-student-address1`): `member/regist.php`に対象項目が無い(対象外)。
  - `S12-mypage-name`: 既知の不具合([B-0]。`mysql_real_escape_string()`呼び出し)により登録不可(対象外)。
  - CMS巡回(209画面)は完了。受講者サイトの巡回は、ログイン直後の2画面目でSSOへ強制的に戻される現象が複数回・再現性をもって発生し未完了（`STG2_STUDENT_USER`を他プロセスと同時に使っていてセッションを奪い合っている可能性が高い。原因未特定のため別途再実施が必要）。
  - CMS巡回の結果、`cms_video`の動画表示名・タグが`newdata`系画面の「既存動画からタグを選択」候補一覧(`set_tag()`)でonclick属性内に出現したが、**JSのUnicodeエスケープ(`"`等)とHTML属性エスケープの2段階で無害化済みと実機確認し、OKと判断**（詳細は後述の`run-xss-s12.js`誤検知バグの節参照）。
  - **副次的に発見・修正したテストハーネスのバグ**: `detect.js`の`domInjection()`が、on*属性内にマーカー文字列が(エスケープ済みでも)部分一致するだけで「危険」と誤判定していた。マーカーの直後に無害化されていない`"`・`'`・`<`・`>`が続く場合だけを「危険」とみなすよう修正（`s12-crawl-lib.js`・`xss-form-lib.js`の両方の呼び出し元に影響。既存の確認済み結果への影響は未調査）。
  - 残り16件・受講者サイト巡回は、商品管理の重い画面問題の解消とセッション競合の原因特定の後、再実行が必要。

- **まだ自動化していないもの**（書き込み・メール・決済を伴う、または別の機能バグ・認可不備によりXSS確認の対象にならない、もしくは手動が必要）: E-02/E-05（購入・決済画面。開くだけで注文が作られうる）、E-59（Ajaxエンドポイント。2026-10-08調査の結果、応答が固定文字列`"0"`のみで反射経路自体が無いためXSS観点では対象外と判断。ただし調査の過程で、ログイン確認が無く任意の`student_id`で他人の視聴履歴・受講完了フラグを改ざんできる認可不備（[B-12]）を発見）、F-01 のうち受講者側（SSO経由のためログインを自動化しない）、入口別の表示先（G-01〜G-12。一部は s12-entries.json の G-05 相当でカバー）。実施する場合は、先に DB のダンプ（人が取得）が必要。
  - E-10・E-12・E-13・E-14・E-49は、実データ無しで自動生成する方法が見つかり `run-xss-data-linked.js` に実装済み（上記参照）。E-20・E-60(領収書・受講証)は、人によるデータ投入を前提に読み取りのみの確認として実装済み（上記「段階A相当」参照）。
  - E-48・E-55（`alfproduct/admin/`。テスト関連の旧管理画面）は、2026-10-07 のコミット `88d2d63` でディレクトリごと削除されたため、`run-xss-http.js` の `checkE08` に「削除後もURLが到達できないこと」を確認するチェックを追加して対応済み（2026-10-08）。
  - E-04（アンケート回答一覧）: 実体は `/cms_exam2_review/exam2_set_list`。2026-10-08 調査でクラス名の不一致（ファイル名`Cms_exam2_review.php`に対し`class Cms_exam2`のままで、CodeIgniterの`class_exists()`チェックに失敗し常に404）と、`$product_id`・`$exam2_id`が未定義変数のまま使われ常にPHP8のTypeErrorでクラッシュする別のバグ（両方とも[B-11]、解消済み）の2件を発見し、両方とも修正した。`run-xss-http.js`の`checkE04`で「クラッシュしなくなったこと」のみ確認する形で実装済み（実在する回答データが無いため、攻撃文字列の反射確認自体は引き続き未対応。実データが用意できた段階で追加実装すること）。
  - E-01・E-07 は新規コード不要（E-01 は `check-debug-output.js` の既存の巡回・検出観点と同一。E-07 は cms_video の確認画面が通常の CMS 登録系テスト(S1〜S7。P06 を含む)の対象に既に含まれる）。
  - E-03（受講者レポート）・E-09（WordPressスマホ版。モバイルUA）・E-45（JSON応答のContent-Type）・F-09（セッションID再生成）・F-01のCMS側（ログイン後の戻り先）は `run-xss-http.js` に追加済み。F-04 の Host ヘッダー本体も、SNI・証明書は正規のまま Host ヘッダーの値だけ差し替える方法で追加済み（`run-xss-http.js`）。
  - S9（ファイルのアップロード）・S10（パスワード）は `run-xss-form.js` に追加済み（上記参照）。
  - ethic_treaning（代替倫理研修。S11のURLパラメータ改ざん）・試験/アンケートの `result*.php`・`resubmit_index*.php`・`confirm1.php` は `run-xss-student-exam.js` に追加済み（上記参照）。
  - E-21・E-23・E-27（教材の表示名/説明・動画のタグ・コンテンツ検索ポップアップの保存済みデータ）は `s12-entries.json` に教材(`cms_material`)・動画タグ(`video_tags`)を追加して対応。E-32（商品詳細の「主催」。`disp_sponsor`）は、**2026-10-08の静的解析で解消**: 弁護士会マスタ由来の値だが、生成元のPHP側(`product/detail.php`等)で`htmlspecialchars()`により事前にエスケープ済みと確認済み(詳細は下記「静的解析によるモンキーテスト」節参照)。
  - **テストプランレビュー(2026-10-07)起因の追加**（`test-plan/plan-fixes.json` に新規行 E-48〜E-60 を追加。詳細は同ファイル参照）。`run-xss-http.js` に実装済み:
    - E-56(AppScan格納型XSS CMS-H-07〜16のセッション再現)
    - E-50(`product_live/approval_exe.php`。EDU_RB_DEV-46の退行確認。既存の`tamper()`を使用)
    - E-51(`Bat_*`・`Once_bat_*`バッチコントローラの到達性。**実URLへはアクセスせず静的ソース解析のみ**。29本中26本がブラウザから到達可能、うち`Once_bat_reconversion_book_library.php`は`index()`が直接実処理を呼ぶため危険と判明)
    - E-52(CSV/PDF出力。15本のうち、認証無し+無条件のファイル書き込みがある3本([B-8])は除外し、残り12本の未ログイン時ブロック・ログイン時の確認用出力を確認)
    - E-54(SSOの入口。正規のSSO往復が無いと入力値が画面に反射される経路が無いことをソース確認済みのため、クラッシュ・確認用出力の有無のみ確認)
    - E-57(CMSエラー画面。ユーザー入力が画面に出力されないことをソースで確認済みのため、**実アクセスはせず**静的確認の結果をOKとして記録)
    - E-58(alflearning-api。Csv_downloadは[B-9]のため対象外、Login・Top・Sso_update_profileのみ確認。`STG2_API_URL`の設定が必要)
    - E-08(旧ファイル・開発用ファイル)は `backup/` ディレクトリ・`*_review.php` をパターンに追加。S6・S7・S8・S11 の攻撃文字列セットに P02・P03・P05・P06・P12 相当を追加（`xss-payloads.js`）。
    - E-55(`alfproduct/admin/`)・E-48(全量一覧No.48の退行確認): `alfproduct/admin/`(testlogin含む)が `88d2d63`(2026-10-07)でリポジトリから全削除されたため、`checkE08` に削除後の到達不能確認を追加して2026-10-08に実装済み（上記参照）。
    - E-49(全量一覧No.49の退行確認)は `run-xss-data-linked.js` に実装済み（上記参照）。
    - 現状プラン行のみ(未実装・別の問題によりブロック中): E-53(決済。方針により手動)・E-59(Ajaxエンドポイント。調査の結果XSS観点では対象外。[B-12]参照)。
  - **F-01 の調査中に、CMS ログインの戻り先（backurl）にオープンリダイレクトの脆弱性を発見**（`Login_page.php::_is_valid_backurl()` が `//evil.example/` のようなプロトコル相対URLを誤って許可する）。詳細は `secure_report/stg2自動テストで発見した不具合_2026-10-07.md` の [B-7] を参照。

##### 2026-10-08: 「手動実施」バケット(旧95行)の監査

進捗トラッカーの「手動実施95行」のうち「その他66行」が長らく未棚卸しだったため、1行ずつ精査した。結論: **この66行の大半は、過去に実装された別の自動化(exam/exam2/ethic_treaning via `run-xss-student-exam.js`・S12・E-10等のdata-linked)が反映されず、古いまま残っていたプレースホルダだった。** 実際に手動が必要なのは次の通りごく少数:

- メール送信: **B-0016**(`amount_order/info.php`のhidden項目`mode`改ざん。`mode=pay`を誘発しうる)・**B-0048**(`bank_upload/index.php`のcsv_upload。取込処理が入金通知メールを送信)・**B-0049**(同画面の表示確認。csv_upload処理後でないと対象データが存在しないため同様に手動)。
- 決済: `settlement/`配下16行(G-12・E-05・E-53・C-0416〜428)。
- その他: G-01〜12(入口カテゴリの参考記載。個別実行対象ではない)、E-59(情報共有のみ、対応不要)。
- B-0015・B-0017(同じamount_order/info.php内)は、ソース確認(`atena`は`mode=pay`と無関係の別フォーム、`arr_order[0].association_name`等はSmartyの`|escape`修飾子を使用)の結果、自動化可能と判明。B-0015は`checkAtena()`として実装したが、[B-13]の影響で現状未検証。B-0017の`association_name`(弁護士会マスタの選択式)・`product_name_TOD/TP`(別商品名が必要)は動的な反射確認ができないため、ソース確認(`|escape`適用済み)による解決とした。

**副産物の発見**: 受講者側の問い合わせフォームにも、管理画面側(B-0052等)と同じ「テンプレート名(form.tpl)と実ファイル名の取り違え」バグがあった（計画記載の`/inquiry/form.php`は実在せず、実体は`/inquiry/index.php`）。`plan-fixes.json`で修正し、`submitGuarded`の`CONFIRM_ACTION`に`conf.php`パターンを追加（ボタン文言が「送信する」でDENY_LABEL扱いされ、確認画面(`conf.php`)への遷移を安全と判定できていなかったため）。C-0165〜172の7行を解消(3行は実際に自動テスト実行、4行は項目が存在しない/csrf_token単独のため正しく対象外)。

##### 2026-10-08: 「計画データ不備」バケット(60行)の精査

plan.json の全342 URL について、CMS(CI3コントローラ)・商品管理/受講者サイト(alfproduct)それぞれの実ファイル存在をスクリプトで機械チェックし、1件ずつソース確認した。

- **URL修正・解消(計30行。`plan-fixes.json`で修正済み)**:
  - `/search/index20260323.php`(13行) → `/search/index.php`。日付らしき文字列が付いたファイルは存在せず、下書き・旧版のファイル名が紛れ込んだと見られる。E-49で既に使用実績のあるURLで確認済み。
  - `/product/detail_review.php`(16行) → `/product/detail.php`。対象項目(`product_list.free_html_area1`・`contents_baisoku_flg`系・`disp_sponsor`・`exam2_problem_row`等)がすべて`detail.tpl`に実在することをgrepで確認。
  - `/mypage/zip.php`(1行) → `/mypage/input_zip.php`。`member/zip.php`(既知。[既存対応] `member/input_zip.php`が実体)と同種の命名違い。なおinput_zip.phpはAjax専用でフォーム項目が無いため、member/zip.php同様に実行時は「対象項目なし」が正しい結果になる。
- **重複行(計14行。xlsx側での削除・統合を推奨。コード側の対応は不要)**: `product`・`product_ethics`・`product_live`・`product_live_branch`・`product_passport`の各`add_confirm.php`(計10行。B-0154/155・B-0285/286・B-0399/400・B-0427/428・B-0451/452)と、`report_product`の`__info.php`・`_info.php`(計4行。B-0462〜465)は、いずれも実ファイルが存在せず、対象項目は既存の`add.php`・`info.php`自身のS11/S12「まとめ」行(例: product=B-0137/B-0153、report_product=B-0478/479)と完全に重複している。各製品種別ごとに「確認画面」を独立ファイルと誤認してコピー&ペーストしたと見られる。
- **機能削除により対象外(7行)**: `/cms_information_old/edit`・`/`(A-0227〜233)。コントローラは元々存在せず、ビュー(`application/views/cms_information_old/`)も2026-10-08付のコミット`f360754`(「不要ファイルの削除」)で削除済み。計画作成時点では存在した機能が、別エンジニアの整理により退役したと見られる。
- **要個別対応(2行)**:
  - `/login/login_page`(A-0459) → 正しいURLは`/login_page`(CMS, `Login_page.php`)だが、ログイン済みセッションで開くと`/admin_top`へリダイレクトされ、かつ`xss-session.js`の`recover()`が`/login_page`を含むURLを「セッション切れ」と誤検知して再ログインを繰り返す。未ログイン状態での検証方法を別途検討する必要がある。
  - `/mail_templates/class_notification`(A-0483) → URLではなく`application/views/mail_templates/class_notification.php`というビューファイル(メール本文のテンプレート)。ブラウザから直接開けないため、E-51/E-57と同様に静的ソース確認での対応を検討する。
- **対象外のまま変更なし(今回あらためて確認。各既知の原因のとおり)**: exam2の`index1/index2/confirm1/confirm2/answer_check1/resubmit_index1/2/result1/2.php`(9行。架空の番号付きファイル)・`exam/resubmit_exec_result1.php`(1行)・受講者サイトの`login/*.php`(SSO専用でローカル処理なし)・`member/regist_confirm.php`・`reminder/*`。
- WordPress関連の複合記載行(`news/`・`(トップ)`等)は、ファイル存在チェックの手法自体がWordPressの書き換えルーティングに適用できないため今回は対象外。別途、実アクセスでの確認が必要。

##### 2026-10-08: 静的解析によるモンキーテスト(`scan-unescaped-output.js`)

テスト計画(xlsx)には無い観点の補完として追加。Smartyテンプレート(`alfproduct/smarty/templates/default/`。商品管理・受講者サイト)・CI3ビュー(`alflearning-cms/application/views/`。CMS)を、PHP/Smartyを実行せずソースのテキストパターンだけで全件走査し、変数を出力している箇所でエスケープ系の処理(Smarty: `|escape`・`|purify_ethic_html`系、CI3: `htmlspecialchars()`・`set_value()`の2引数形式等)が見当たらないものを候補として列挙する。xlsxの「★」行(191行。備考にエスケープ関数が見当たらない旨の手動メモがある行)と同じ位置づけの、再確認前提の候補リスト。

```
cd tools/stg2-e2e
node scan-unescaped-output.js          # 自由入力らしい変数名(name・title・comment・memo等)に絞り込み・重複除去した候補のみ表示
node scan-unescaped-output.js --all    # 絞り込み無し(IDやスタイル文字列等を含む全候補。ノイズが多い)
node scan-unescaped-output.js --csv    # test-results/unescaped-scan-<日時>.csv に出力
```

- 108本のSmartyテンプレート・179本のCI3ビューを走査し、絞り込み後255件の候補(重複除去済み)を検出。
- **最重要の確認結果**: `disp_sponsor`(商品の「主催」表示。9つのテンプレートファイルに出現。CLAUDE.mdの旧E-32「弁護士会マスタからの選択の可能性が高く要確認」に対応)は、**テンプレート側に`|escape`が無いが、生成元のPHP側(`product/detail.php`等11ファイル)で`htmlspecialchars($mtb_bar_association[$sponsor], ENT_QUOTES, 'UTF-8')`により事前にエスケープ済みと確認**。E-32の懸念は解消(安全)。副次的に、`mypage/favorite_list.php`だけ`disp_sponsor`の生成コードが丸ごとコメントアウトされており、お気に入り一覧で「主催」欄が常に空になる軽微な表示不具合を発見(セキュリティ上の問題ではない)。
- その他の候補(`$return.html`・`$return.formhtml`。exam/exam2の設問HTML。意図的にPHP側で安全なHTMLを組み立てて渡している可能性が高い/`$exam2_problem_row.problem_contents`等。product/detail.tpl内の設問プレビュー部分。既存のexam系テストとは別経路での反射箇所として新規発見)は、個別の目視確認が必要(人手でのレビュー待ち)。
- `_style`で終わる変数(member/regist.tpl・mypage/edit.tpl。23件)はコントローラが生成するCSSスタイル文字列で、変数名にemail/password等を含むため誤って候補に挙がっているだけと判断(フィルタの既知の誤検知)。

##### 2026-10-08: 計画全体の一括実行(段階A＋段階B)を試行、並行実行数に注意が必要と判明

テスト計画の自動化対象を一度に全部実行できるか試した。段階A(`run-xss-search.js`・`run-xss-form.js`・`run-xss-http.js`)は5〜7プロセス並行でも概ね動いたが、段階B(`--accept-writes`系・`run-xss-s12.js`・`run-xss-data-linked.js`)を同時に4本並行実行したところ、**stg2環境自体がHTTP 504(ゲートウェイタイムアウト)で応答不能になり、CMS・受講者サイトとも長時間(1時間以上)復旧しなかった**。単発の素のHTTPリクエスト(Playwright・自分のテストコード一切関与なし)でも504が返ることを確認しており、自分の実行プロセスの問題ではなくstg2サーバー側の過負荷と判断した。**教訓: 書き込みを伴う段階Bのスクリプトは同時に1本ずつ実行する。** 段階Aの読み取り専用スクリプトも、5本以上を同時に走らせると稀にナビゲーションが連鎖的に割り込まれて失敗する(`page.goto`が別のURLへの遷移に割り込まれるエラーが連鎖し、以降のURLが軒並み「対象外」扱いになる。本来の対象外ではなくインフラ起因のため、該当画面は後日単独で再実行して結果を取り直す必要がある)。
この一括実行の過程で、E-08(旧ファイル到達性)でCMS側の`/alfproduct/cron/logs/mailmagazine_submit.log`が認証なしで読めることを新たに発見([B-21]参照)。また`info_user.php`系の確認画面での未エスケープ出現候補(上記「残る405」の項参照)・`search_orderby`パラメータでのJS文脈破壊型payloadの出現(amount_order・product_live)も発見していたが、**2026-10-09、この2つを含む計22件はテストハーネス自体の誤検知と判明し訂正済み**(詳細は下記「2026-10-09: inspect()の誤検知バグ」節参照)。`search_set.php`系4ファイルでの`$_SERVER['PHP_SELF']`のLocationヘッダ未エスケープ(backurlを実際に出力する箇所が見つからず悪用経路未確認)はこの誤検知とは無関係で、引き続き再現確認待ちの候補として扱う。

##### 2026-10-09: inspect()の誤検知バグ(22件のNG候補が全てテストハーネス起因と判明)

手動テストを別途進めていたエンジニアが、修正対応セッションからの指摘として「P05系payload(`');alert('X');//`)で検出された22件のNG候補は、アプリの脆弱性ではなくテストツール側の誤検知」との報告を受け、自分でも検証のうえ確認した。

**原因:** `xss-session.js`の`action()`は、フォーム送信後にページ遷移が起きなかった場合(`navigated: false`)、サーバーの応答ではなく、その時点のブラウザ上のDOM(`page.content()`)を返す。`hidden`・`checkbox`型の入力欄は、JSで`.value`を設定しただけで(サーバーを経由せず)その値がシリアライズされたHTMLの属性にそのまま現れる(ブラウザの仕様。`text`型の入力欄はこの現象が起きないことも実験で確認済み)。この結果、**テストコードが自分で入れた攻撃文字列**が、サーバー側のエスケープとは無関係に「属性内に未エスケープで出現」と誤検知されていた(`<>"`を含まずシングルクォートのみのP05でしか起きないのも、ブラウザの属性値シリアライズが`'`をエスケープしないことと一致)。同様に`domInjection`(DOM上のonclick等のイベント属性を調べる検出)も同じ原因で誤検知することを確認した(cms_book_libraryで実際に発生)。

**裏付け:** 22件とも`status`が`200`固定(`navigated:false`時の`action()`のハードコード値と一致)。Playwrightでの再現実験でも、`<input type=hidden>`・`<input type=checkbox>`は`.value`設定後に`page.content()`へ反映されるが、`<input type=text>`は反映されないことを確認(HTML仕様の「value属性のdirty value flag」による挙動差)。

**対応(`xss-form-lib.js`の`inspect()`):** `res.navigated`が`false`のときは、「未エスケープで出現」「確認用出力」「DOM上のイベント属性/JSリンク」の検出を一切行わず、「未検証(ページ遷移なし)」として要確認扱いにするよう修正。22件全てで再実行し、訂正を確認済み(`node ng-list.js --escape-only`の対象が22件→0件になった)。

**残課題:** なぜこれらの画面で送信してもページ遷移が起きないのか(サーバー応答が得られない理由)は未調査だったが、`amount_order`/`product_live`の`index.php`については原因を特定した(下記)。`product_lecture`系`info_user.php`はB-0320/341/360として手動テストで決着済み([B-22]参照)。CMSの6確認画面(cms_book_library・cms_exam2_download・cms_exam2_problem_import・cms_exam_problem_import・cms_issue・cms_material)は原因未特定のまま残っている(ログに「セッション切れのため再ログインします」が混在しており、送信中のセッション切れ→自動再ログインが遷移検出を妨げている可能性がある)。

**2026-10-09追加調査: `amount_order`/`product_live`の`index.php`(search_orderby)の原因を特定**: `xss-session.js`の`action()`のナビゲーション検出タイムアウト(15秒)を疑ったが、実測した結果、`amount_order/index.php`は検索条件(日付等)を指定しない状態で送信すると**サーバー応答に56秒以上かかる**ことが判明(診断スクリプトで実測)。**この画面は運用上、必ず日付等の検索条件を付けて使う前提であり、無条件検索はそもそも想定外の使い方**とのこと(担当者より確認)。`action()`のタイムアウトを90秒に延長(`goto()`と同じ)したうえで、`test-plan/form-defaults.json`に`amount_order/index.php`・`product_live/index.php`用の日付範囲の既定値を追加。**単体の診断スクリプトでは、日付条件を設定することで応答が142msまで改善することを確認済み**(アプリ側の問題ではなく、想定通りの挙動と確認できた)。ただし、**実際の`run-xss-form.js`本体を通すと、同じ既定値を設定しているはずなのに依然「未検証(ページ遷移なし)」のまま**という謎が残っている(単体の診断では再現しない。ハーネス側の別の問題の可能性。2026-10-09時点で未解決、深追いを保留)。

**2026-10-09 根本原因判明・修正**: 上記の「ナビゲーションが連鎖的に割り込まれて失敗する」現象は、並行実行数とは無関係に、`xss-session.js`の`goto()`/`action()`がページ遷移後の遅延リダイレクト(JSのsetTimeout等)の完了を待たずに次の操作へ進んでいたことが原因と判明(stg2サーバーが安定している状態でも、単独実行で同じ現象が再現した)。`goto()`/`action()`に、遷移後`networkidle`を短時間(2秒)待つ処理を追加し、商品管理・受講者サイトとも大部分で解消を確認済み(`amount_order/info.php`(B-0016/B-0017)のみ、2秒待っても解消しない別要因が残っている)。

**2026-10-09 コードレビューによる不具合まとめ[B-0]〜[B-21]の対応状況確認**: 実際にソースを読んで、各指摘が現在のコードで修正されているか判定した(13/15件が修正済み)。
- **修正済み**: B-7・B-8・B-9・B-12・B-13・B-15・B-16・B-17・B-18・B-19・B-14(該当ファイルごと削除)。
- **修正済みだが軽微な残課題あり**: B-0(`alfproduct/module/functions.php`に移設された`get_bar_association_info()`が、移設後も`$bar_association_branch_id`を未エスケープでSQLに連結している新たな箇所を発見)、B-1(`memo`欄を配列に改ざんすると依然未エスケープのまま`mysqli_real_escape_string()`に渡り例外になりうる)。
- **一部修正**: B-21(ログの書き込み先は`/alflearning-data/`配下に移動済みだが、旧ログファイル`alfproduct/cron/logs/mailmagazine_submit.log`が公開ディレクトリにまだ残存。サーバー上での削除が必要)。
- **未修正**: B-4(array型チェックボックス項目の改ざんで一律500になる件。入力チェック自体は追加されたが(2026-10-08のコミットで`_display_unjust_access()`を呼ぶよう修正)、その`_display_unjust_access()`→`_display_view()`の経路自体が、サブメニュー・ドロップダウン・受講者グループ一覧等の重い依存データ取得を伴ったままで、結局500になる。今回の再テストでも同一のHTTP 500を再現)。

## アーキテクチャ

### ディレクトリ構成

```
nichiben/
├── alfproduct/
│   ├── module/          # フロントエンド全体で共有する PHP ライブラリ群
│   │   ├── functions.php          # コアの手続き型関数（約 3200 行）
│   │   ├── functions_wp.php       # WP 由来のレガシー関数（現在 WP自体 は未使用）
│   │   ├── DbConnect.php          # PDO データベースラッパー
│   │   ├── AlfSession.php         # セッション管理
│   │   ├── GMOPaymentProtocol.php # GMO 決済連携
│   │   ├── Qdmail.php             # SMTP メール送信
│   │   └── vendor/                # Composer: mPDF, FPDI, FPDF, TCPDF
│   ├── public/          # Web ルート — サブディレクトリが各機能モジュール
│   │   ├── customer_pages/  # 講座カタログ・詳細ページ
│   │   ├── product/         # 講座カタログ・詳細ページ
│   │   ├── exam/ exam2/     # 2 種類の試験エンジン
│   │   ├── member/          # 会員登録・管理
│   │   ├── mypage/          # ユーザーダッシュボード
│   │   ├── settlement/      # 決済・チェックアウト
│   │   ├── player/          # 動画プレーヤー
│   │   └── pdf/             # PDF 生成・ダウンロード
│   └── admin/               # alfproduct の管理画面
│
└── alflearning/
    ├── alflearning-global-config.php  # サービス URL・ファイルパス・API キー・
    │                                  # FTP 認証情報の唯一の定義場所
    ├── alflearning-cms/               # CodeIgniter 3 アプリケーション
    │   └── application/
    │       ├── controllers/   # Admin_*（管理 UI）, Bat_*（バッチ）, Api_*（内部 API）
    │       ├── models/        # Model_* — DB エンティティごとに 1 ファイル
    │       ├── views/         # PHP ビューテンプレート
    │       └── config/        # CI 設定・DB 設定・ルーティング
    └── alflearning-api/       # REST エンドポイントを公開する薄い CI3 アプリ
```

### 3 アプリの連携構造

1. **alfproduct/public/** の各ページは `alfproduct/module/functions.php` と `DbConnect.php` を直接 include します。フレームワークは使用せず、各ページが自前で初期化します。サーバー上の実パスは `/srv/alfproduct/public`。
2. CMS（`alflearning-cms`）は MySQL データベースを通じてすべてのコンテンツを管理します。cron から呼び出されるバッチ処理（`Bat_*` コントローラー）も内包しており、Apache タイムアウトは 3600 秒に設定されています。
3. `alflearning-api` は `alfproduct` ページ（HTTP 呼び出し）が利用する REST エンドポイントを提供します。
4. `alflearning-global-config.php` は CMS・API の両方から include され、環境ごとのドメイン・ファイル保存パス・FTP 認証情報・WebSocket URL・API Basic 認証情報を定義します。
5. 動画サムネイル等のアップロードファイルは NFS マウントの `/alflearning-data`（`172.31.21.103:/exports`）に保存されます。Apache の `Alias /upload/video_thumbnail/` でこのパスを公開しています。

### コーディング規約

- **alfproduct に MVC なし** — ページは共有関数（`module/`）を include するフラット PHP ファイルです。ビジネスロジックは `functions.php` に集約されています（`functions_mst.php` は重複していた未使用コードのため `a494b16` で削除済み）。
- **alflearning-cms は CI3 規約に従います** — コントローラーは `CI_Controller`、モデルは `CI_Model` を継承し、`application/config/autoload.php` で自動ロードします。
- **データベース** — alfproduct では PDO（`DbConnect.php`）、CMS では CI の `$this->db` を使用します。PHP 8.3 + mysqli/pdo_mysql 拡張を使用。
- **PDF 生成** — mPDF・FPDI・FPDF・TCPDF の 4 ライブラリが共存しています。編集対象ファイルの既存パターンに合わせて選択してください。
- **セッション** — alfproduct では `AlfSession.php` が管理します。HTTPOnly + Secure + SameSite=Lax を強制しています。
- **タイムゾーン** — 全体で Asia/Tokyo を使用します。

### 環境設定

ドメイン・認証情報・ファイルパスなど環境依存の値はすべて `alflearning/alflearning-global-config.php` に定義されています。アプリケーションコード内にドメイン名やファイルパスをハードコードせず、ここで定義された定数を参照してください。

現在有効なステージングドメイン（stg2 系。stg 系はコメントアウト済み）:
- product: `nichibenren-stg2.alfcloud.com` → `/srv/alfproduct/public`
- CMS: `cms.nichibenren-stg2.alfcloud.com`
- API: `api.nichibenren-stg2.alfcloud.com`


## 現在存在するリスク
2026/06/18 現在以下のファイルでセキュリティリスクのレポートが上がっている。  

### フロント画面側のXSS 優先度:高

`secure_report\検証環境管理者サイト（商品登録画面以外）_セキュリティー・レポート.pdf` 参照


### 管理画面側のXSS 優先度:低

`secure_report\検証環境管理者サイト（商品登録画面のみ）_セキュリティー・レポート.pdf` 参照


### その他セキュリティリスクレポート 優先度:低

`secure_report\検証環境受講者サイト_セキュリティー・レポート.pdf` 参照

