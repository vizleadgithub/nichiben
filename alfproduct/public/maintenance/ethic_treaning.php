<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'メンテナンス';

$template = new Template();
$template->layout('maintenance/ethic_treaning.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>