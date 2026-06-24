<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include("/srv/alfproduct/module/module.php");
//require_once("/srv/alfproduct/module/session_start.php");
//require_once("/srv/alfproduct/module/DbConnect.php");
//require_once("/srv/alfproduct/module/define_list.php");
//require_once("/srv/alfproduct/module/ci/libraries/Encrypt.php");
//require_once("/srv/alfproduct/module/AlfSession.php");

$_SERVER['HTTPS'] = 'on';
ini_set('display_errors', 0);
// error_reporting(E_ALL & ~E_WARNING);

$student_id = $_SESSION['user']['id'];
if (intval($student_id) > 0) {
	$file_path = "/srv/alfproduct/public/";
	$file_name = "";
	
	if ($_GET["filename"] ?? "") {
		$file_name = trim($_GET["filename"]);
	}
	
	if (!empty($file_name)) {
		$full_path = $file_path . $file_name;
		if (file_exists($full_path)) {
			$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			// MIMEタイプを設定
			$mime_types = [
				"pdf"  => "application/pdf",
				"ppt"  => "application/vnd.ms-powerpoint",
				"pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
				"doc"  => "application/msword",
				"docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"xls"  => "application/vnd.ms-excel",
				"xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"png"  => "image/png",
				"jpg"  => "image/jpeg",
				"zip"  => "application/zip",
			];

			if (isset($mime_types[$extension])) {
				// ヘッダーを設定（ブラウザで開く）
				header("Content-Type: " . $mime_types[$extension]);
				header('Content-Disposition: inline; filename="' . basename($file_name) . '"');
				header("Content-Length: " . filesize($full_path));
				header("Accept-Ranges: bytes");

				// ファイルを出力
				readfile($full_path);
				exit;
			} else {
				echo "Invalid file type.";
			}
		} else {
			echo "File not found.";
		}
	}
} else {
	echo "Unauthorized access.";
}
exit();