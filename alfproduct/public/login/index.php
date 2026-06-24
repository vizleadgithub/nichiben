<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header("Content-type: text/html; charset=UTF-8");
header('Location: /logout/logout.php');
exit;
?>