<?php
require_once $_SERVER['DOCUMENT_ROOT'].'./../module/gdthumb.php';
$objThumb = new gdthumb();
$file = '/alflearning-data/video_thumbnail/'.$_GET['image'];
$objThumb->Main($file, $_GET['width'], $_GET['height'], "", true);
?>