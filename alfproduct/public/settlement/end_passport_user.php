<?php
include(dirname(__FILE__) ."./../../module/module.php");
$template = new Template();
//+++++++++++++++++++++++++++++++++++++++++++++++++++
$order_no = $_SESSION["payment.order_no"];
$order_id = $_SESSION["payment.order_id"];
//+++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('order_id', $order_id);
$template->assign('order_no', $order_no);
//+++++++++++++++++++++++++++++++++++++++++++++++++++
$template->layout_noside('settlement/end_passport_user.tpl');
exit();
?>