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

