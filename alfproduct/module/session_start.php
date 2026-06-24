<?php
header("X-XSS-Protection: 1; mode=block");  
// セッション Cookie のセキュリティ強化
ini_set("session.cookie_httponly", 1);  // JavaScript からのアクセスを防止
ini_set("session.cookie_secure", 1);	// HTTPS 接続時のみ Cookie を送信
ini_set("session.cookie_samesite", "Lax");  // SameSite=Lax に設定
$session_temp=null;
// セッションタイムアウト設定(単位：秒)
define('SESSION_TIME_OUT_SEC', 86400);

require_once("DbConnect.php");

if (!ini_get('session.save_handler')) {
	ini_set('session.save_handler', 'user'); 
}

session_set_save_handler(
	function ($savePath, $sessionName) { return true; },
	function () { return true; },
	function ($id) {
		$objDbConnect = new DbConnect();
		$query = "SELECT data FROM ci_sessions WHERE id = '".mysqli_real_escape_string($objDbConnect->connect,$id)."'";
		$result = $objDbConnect->query_fetch($query);
		$session_temp = $result['data'];
		if ($result) {
			error_log("Session Read: " . print_r($result['data'], true)); // デバッグログ
			return $result['data'];
		}
			return $result ? $result['data'] : '';
		},
	function ($id, $data) {
		$objDbConnect = new DbConnect();
		$data = addslashes($data); // SQLインジェクション対策
		$query = "REPLACE INTO ci_sessions (id, timestamp, data) VALUES ('$id', '".time()."', '$data')";
		return $objDbConnect->execute($query);
	},
	function ($id) {
		$objDbConnect = new DbConnect();
		return $objDbConnect->execute("DELETE FROM ci_sessions WHERE id = '$id'");
	},
	function ($maxlifetime) {
		$objDbConnect = new DbConnect();
		return $objDbConnect->execute("DELETE FROM ci_sessions WHERE timestamp < (UNIX_TIMESTAMP() - $maxlifetime)");
	}
);
session_start();
$data = $session_temp; // データベースから取得した値
if( isset($data) && !empty($data) && $data!=null ){
	session_decode($data);
}
