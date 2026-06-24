<?php
setcookie('time', 1, 0, '/', $_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'],  true);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
require_once(dirname(__FILE__) ."./../../module/session_start.php");
require_once(dirname(__FILE__) ."./../../module/DbConnect.php");
require_once(dirname(__FILE__) ."./../../module/functions.php");
require_once(dirname(__FILE__) ."./../../module/value_check.php");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (isset($_GET['pid']) && isset($_GET['cdname']) && isset($_GET['uid'])){
	$pid = $_GET['pid'];
	$cdname = $_GET['cdname'];
	$uid = $_GET['uid'];
	
	if(!cmCheckInput($pid, 'CK_NUM') && !cmCheckInput($uid, 'CK_NUM')){
		if ($uid != ''){
			if (strpos($cdname, 'contents_download') !== false){
				if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $uid)){
					$sql = "SELECT $cdname FROM tbl_product WHERE product_id='$pid'";
					$ret = $objDbConnect->query_fetch($sql);
					if ($ret){
						$file_path = '/alflearning-data/alfproduct/document/'.$ret["$cdname"];
						if (is_readable($file_path)){
							
							header('Content-type: image/jpeg');
							
							header('Content-Disposition: attachment; filename="'.$ret["$cdname"].'"');
							header('Pragma: no-cache');
							header('Cache-Control: no-cache, must-revalidate');
							header('Connection: close');
							//readfile($file_path);
							// out of memory�G���[���o��ꍇ�ɏo�̓o�b�t�@�����O�𖳌�
							while (ob_get_level() > 0) {
								ob_end_clean();
							}
							ob_start();
							// �t�@�C���o��
							if ($file = fopen($file_path, 'rb')) {
								while(!feof($file) and (connection_status() == 0)) {
									echo fread($file, '4096'); //�w�肵���o�C�g�����o��
									ob_flush();
								}
								ob_flush();
								fclose($file);
							}
							ob_end_clean();
						}
					}
				}
			}
		}
	}
}
$objDbConnect->close();
exit;
?>
