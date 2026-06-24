<?php
require_once $_SERVER['DOCUMENT_ROOT'].'./../../alfproduct/module/gdthumb.php';
$objThumb = new gdthumb();
$file = '/alflearning-data/alfproduct/thumbnail/'.$_GET['image'];
$objThumb->Main($file, $_GET['width'], $_GET['height'], "", true);
?>