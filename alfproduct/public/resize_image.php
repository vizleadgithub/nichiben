<?php
require_once $_SERVER['DOCUMENT_ROOT'].'./../module/gdthumb.php';
$objThumb = new gdthumb();
// 画像のパスは、保存先フォルダの外(../ や先頭の / 、NUL文字)を指せないようにする
$image = isset($_GET['image']) ? (string)$_GET['image'] : '';
if ($image === '' || strpos($image, "\0") !== false || $image[0] === '/' || $image[0] === '\\' || preg_match('#(^|[/\\\\])\.\.([/\\\\]|$)#', $image)) {
	header('HTTP/1.1 400 Bad Request');
	exit;
}
$file = '/alflearning-data/alfproduct/thumbnail/'.$image;
$objThumb->Main($file, (int)($_GET['width'] ?? 0), (int)($_GET['height'] ?? 0), "", true);
?>