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

```
cd tools/stg2-e2e
python test-plan/export-plan.py                      # xlsx(最終更新日時が最新のもの) → test-plan/plan.json（openpyxl 必要。コミットしない）
node run-xss-search.js cms                           # 段階A: S8 検索条件（CMS・商品管理）。student も同様。--full 全バリエーション / --each 1項目ずつ / --only A-0010,...
node run-xss-form.js --site cms --plan-only          # 段階A: 登録系(S1〜S7・S11、入力→確認画面まで)。まず --plan-only で対象・対象外を確認してから実行
node run-xss-http.js                                 # 段階A: 入口以外・HTTP(F-01〜F-09)と重点項目 E-08・E-19・E-47（--only F-03 等で絞り込み。--probe-php は下記）
node run-xss-student-exam.js --accept-writes         # 段階B: 受講者サイトの試験・アンケート（下記）
node report-xss.js                                   # 最新の結果を計画のID単位に集計し、test-results/xss-report-*.csv を出力
```

- **段階A（`run-xss-search.js`・`run-xss-form.js`・`run-xss-http.js`）は GET と確認画面までの送信だけ**（登録・更新・削除・アップロード・メール送信・決済はしない）。DB のダンプは不要。
  - `run-xss-form.js`: 入力画面の各項目に攻撃文字列を入れて「確認」ボタンまで押す（`--site cms|product|student`。product は商品管理(Smarty)、student は受講者サイト。書き込み防止のガードは `xss-form-lib.js` の `submitGuarded`）。画面ごとに検証を通る値が要る場合は `test-plan/form-defaults.json` に追記する。`--check-pages` で、攻撃文字列なしに確認画面まで進めるかだけを事前に調べられる。
- **受講者 SSO（本番と共用）へは通信しない**。ブラウザ側で SSO ドメインへの通信を遮断している。未ログインの受講者サイトはどの URL も SSO へ遷移するため、受講者サイトの確認は保存済みセッション（`node login.js student`）で行い、確認できなかったものは「対象外」と記録する。
- 攻撃文字列の実行用定義は `xss-payloads.js`（正本は xlsx の「付録_攻撃文字列」）。検出ロジックは `detect.js`。
- E-08 は、開発用らしき PHP（`*_dev.php` 等）を既定では**開かず、一覧のみ**出す（PHP は開くと実行され、メール送信等の副作用がありうる）。内容を確認してから `--probe-php` を付ける。
- 受講者サイトは、決済（`settlement/*`）を除き可能な限り自動化する方針。`run-xss-form.js` の `STUDENT_SKIP`／`STUDENT_NOT_FOUND` に、画面ごとに個別調査した除外理由がある（ethic_treaning は対象の項目が csrf_token のみで、item-less プレフィルタにより自動的に対象外になる。player は視聴履歴に記録されるため除外）。

##### 段階B: 受講者サイトの試験・アンケート（`run-xss-student-exam.js`）

CMS・商品管理・会員登録の「確認」画面と異なり、試験・アンケートは確認画面に見える画面（`answer_check*.php` 等）が、表示に進んだ時点で回答を DB へ書き込む（`DbConnect::execute()` で確認済み）。書き込みなしで確認画面へ到達する経路がないため、書き込みを許容して実行する。

- 事前準備: CMS でテスト専用の講座・試験・アンケートを作成し（`STG2_STUDENT_USER` だけを割り当てる。開始日は遠い過去・リマインドなし）、`tools/stg2-e2e/test-plan/student-entry.json` に実際の pid・eid・e2id 等を記入する。未記入のシナリオは自動でスキップされる。
- 対象: `exam_plain`（`/exam/index.php`。書き込みなし）・`exam_choice`（`/exam/index1.php`。書き込みあり）・`exam_freetext`（`/exam/index2.php`→`confirm2.php`。書き込みあり。最初の設問のみ回答し最終提出はしない）・`survey`（`/exam2/index.php`。書き込みなし）。書き込みがあるシナリオは `--accept-writes` が必須。
- 書き込みを行った場合は、実行後に stg2 の DB をダンプから復元する（人が実施）。
- **未対応**: `result*.php`・`resubmit_index*.php`（試験が「採点済み」「再提出待ち」等の状態である必要があり、別途 CMS 側の準備が要る）。
- **計画(xlsx)側の要確認事項**: `/exam2/index1.php`・`confirm1.php`・`confirm2.php`・`answer_check1.php`・`resubmit_index1/2.php`・`result1/2.php`・`resubmit_exec_result1.php` はリポジトリに実体がない（exam2 は `index.php`→`answer_check.php` の1系統のみで、exam(非2)の番号付きファイル構成を誤って複製したとみられる）。`/exam/resubmit_exec_result1.php` も同様に実体がない。

##### 段階B: S12（表示専用の値）・F-10（入力→全画面の自動追跡）（`run-xss-s12.js`）

「どこかの画面で攻撃文字列を登録 → 別の画面で、その値がエスケープされて表示されるか」を見る観点。1画面・1項目の確認では足りないため、(1) `test-plan/s12-entries.json` に書かれた画面で実際に値を登録し、(2) CMS・受講者サイトを広く巡回して、登録した識別マーカーの出現を探す（`s12-crawl-lib.js`）。

```
node run-xss-s12.js --register-only     # 登録のみ。test-results/s12-manifest.json に記録
node run-xss-s12.js --crawl-only        # 保存済み manifest を使って巡回のみ
node run-xss-s12.js                     # 登録→巡回を通しで実行（既定）
```

- **DB へ書き込む（段階B）。実行前に stg2 の DB をダンプすること。** 登録した値は削除せず残る（手動で削除する運用）。
- 登録先は `test-plan/s12-entries.json`（CMS の `newdata` 経由の新規登録のみ。既存レコードは編集しない）。カテゴリ名・講座名・授業名・講師名・課題名・図書室表示名・動画表示名/説明・設問名(exam/exam2)・お知らせタイトル/本文/外部リンク(CMS)、商品名(product_live・product_passport)、受講者氏名(member/regist)の17件を登録する。`xss-form-lib.js` の `submitComplete`（登録ボタンを実際に押す。`run-xss-form.js` の `submitGuarded` とは逆）を使う。
- お知らせ（`cms_information`）は WordPress へ自動投稿される（G-09・E-24・E-25・E-28・E-30 に対応）。巡回は受講者サイトと同一オリジンの WordPress ページも自然にカバーする（別サイト指定は不要）。
- メール送信・決済を伴う入口（inquiry・mailmagazine・settlement）は対象外（CLAUDE.md の手動実施方針に合わせる）。
- 受講者本人の氏名変更（`/mypage/edit.php`）は、削除済みの PHP 関数（`mysql_real_escape_string()`。PHP 8 では未定義）の呼び出しが残っており現状は登録自体が失敗する見込みのため、`blockedBy` 付きで対象外にしてある。
- **まだ実行していない（コードのみ）。** `test-plan/s12-entries.json` に追加すればさらに対象を広げられる。

- **まだ自動化していないもの**（書き込み・メール・決済を伴う、または手動が必要）: S9（ファイルアップロード）・S10（パスワード）、F-10 のうち `s12-entries.json` 未登録の入口、E-02/E-05（購入・決済画面。開くだけで注文が作られうる）、E-04（exam2のAjax応答。実在の回答データとIDの特定が要る）、F-01 のうち受講者側（SSO経由のためログインを自動化しない）、F-04 の Host ヘッダー本体、入口別の表示先（G-01〜G-12。一部は s12-entries.json の G-05 相当でカバー）。実施する場合は、先に DB のダンプ（人が取得）が必要。
  - E-01・E-07 は新規コード不要（E-01 は `check-debug-output.js` の既存の巡回・検出観点と同一。E-07 は cms_video の確認画面が通常の CMS 登録系テスト(S1〜S7。P06 を含む)の対象に既に含まれる）。
  - E-03（受講者レポート）・E-09（WordPressスマホ版。モバイルUA）・E-45（JSON応答のContent-Type）・F-09（セッションID再生成）・F-01のCMS側（ログイン後の戻り先）は `run-xss-http.js` に追加済み。
  - **F-01 の調査中に、CMS ログインの戻り先（backurl）にオープンリダイレクトの脆弱性を発見**（`Login_page.php::_is_valid_backurl()` が `//evil.example/` のようなプロトコル相対URLを誤って許可する）。詳細は `secure_report/stg2自動テストで発見した不具合_2026-10-07.md` の [B-7] を参照。

## アーキテクチャ

### ディレクトリ構成

```
nichiben/
├── alfproduct/
│   ├── module/          # フロントエンド全体で共有する PHP ライブラリ群
│   │   ├── functions.php          # コアの手続き型関数（約 3200 行）
│   │   ├── functions_mst.php      # マスターデータ系ヘルパー関数（約 3100 行）
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

- **alfproduct に MVC なし** — ページは共有関数（`module/`）を include するフラット PHP ファイルです。ビジネスロジックは `functions.php` / `functions_mst.php` に集約されています。
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

