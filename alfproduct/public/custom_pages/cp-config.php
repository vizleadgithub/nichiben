<?php
define('DB_NAME', 'alflearning');
define('DB_USER', 'alflearning');
define('DB_PASSWORD', 'grefvsdj43t5yry');
define('DB_HOST', 'nichibenren-stag-database.cluster-cjxkbcyhrbuv.ap-northeast-1.rds.amazonaws.com');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('AUTH_KEY',         'C`z36w0#@+#n_|M%,/;sKddk]E152+I!.~hJS-lTWk@AB45g&Ii-u{I&n;t}U^a;');
define('SECURE_AUTH_KEY',  'J#uuueR2w)<)x7unY`qNF+tVT#SZ^9Mgzg%ef2Dt VUDrWNKRZ`QY}hfZIx|zg:X');
define('LOGGED_IN_KEY',    'q;*J/%E{Sbint(:sXv^YqLGDPJ%WscxG|io=~Qp-0wenbx#fu(Tn5o4Yav}+Quc)');
define('NONCE_KEY',        'b/zuu7|T?5ioOw}0#gcfA80,=%+o :]8?_YJWWM!npU)s/U2F $*d?RVtbO=IE!T');
define('AUTH_SALT',        '+fi}ca6YpW[ 5ju]:wY[8>o<2Bc{w`>d-|Rw{=;gy]1+/^r(e71q:p#]C#]uVa-y');
define('SECURE_AUTH_SALT', 'Bqu>|G0K^8sUP52u!J=Rrkj|/fm.,;QRw%_|f0yt@w#y-O?1dT>i!|.aWdzQ%):,');
define('LOGGED_IN_SALT',   'R2M-Lrr@I47V$4tQW1K)Kg#36Qw9M=`j(J)0^&Z$F!hFC&uK?:|=tMH(uJ<b@d7%');
define('NONCE_SALT',       'Kav9E@$G;vI`*r-OeZgb-nG)Q+<DcGRy~-m3_y#hw{%8xJLxhr-G+xd^_|Tu<5Xy');
$table_prefix  = 'wp_';
define('WPLANG', 'ja');
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
define('WP_CONTENT_DIR', __DIR__ . '/cp-content');
define('WP_CONTENT_URL', '/cp-content');
require_once(ABSPATH . 'cp-settings.php');

