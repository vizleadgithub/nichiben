<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT'];

$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}

$isIe = false;
if (strpos($agent, "/MSIE/") !== false){
	$isIe = true;
}
if (strpos($agent, "/Trident/") !== false){
	$isIe = true;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'].'/product/document.php') !== false){
	if (isset($_POST['pid'])){
		$pid = $_POST['pid'];
		
		// androidタブレットの場合はセッションに一時保存
		if ($isAndroidTablet){
			$_SESSION['dl_tmp']['pid'] = $pid;
			$_SESSION['dl_tmp']['cdname'] = $cdname;
		}
		
		if(!cmCheckInput($pid, 'CK_NUM')){
			$sql = "SELECT T1.price, T2.all_contents_download, T2.all_contents_download_before FROM tbl_product AS T1 INNER JOIN tbl_product_add AS T2 ON T1.product_id = T2.product_id WHERE T1.del_flg='0' AND T1.product_id='$pid'";
			$ret = $objDbConnect->query_fetch($sql);
			if ($ret){
				$download_flg = false;
				
				// パスポートユーザーの場合
				if ($_SESSION['user']['presence_passport'] == '1'){
					$download_flg = true;
					
				} else {
					// 無料商品の場合、常にダウンロード可能
					if ($ret['price']==0){
						$download_flg = true;
					}
					// 有料商品の場合、購入済みの時にダウンロード可能
					if (st_login_check()){
						if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $_SESSION['user']['id'])){
							$download_flg = true;
						}
					}
				}
				
				if ($download_flg){
					$file_path = '/alflearning-data/alfproduct/all_contents/'.$ret["all_contents_download"];
					if (is_readable($file_path)){
						$file_size = filesize($file_path);
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="'.mb_convert_encoding($ret["all_contents_download_before"], "SJIS", "UTF-8").'"');
						header('Content-Length: '.$file_size);
						//readfile($file_path);
						// out of memoryエラーが出る場合に出力バッファリングを無効
						while (ob_get_level() > 0) {
							ob_end_clean();
						}
						ob_start();
						// ファイル出力
						if ($file = fopen($file_path, 'rb')) {
							while(!feof($file) and (connection_status() == 0)) {
								echo fread($file, '4096'); //指定したバイト数ずつ出力
								ob_flush();
							}
							ob_flush();
							fclose($file);
						}
						ob_end_clean();
					} else {
						print("download err");
					}
				}
			}
		}
	}
}

if ($isAndroidTablet && isset($_SESSION['dl_tmp']['pid'])){
	$pid = $_SESSION['dl_tmp']['pid'];
	
	$sql = "SELECT T1.price, T2.all_contents_download, T2.all_contents_download_before FROM tbl_product AS T1 INNER JOIN tbl_product_add AS T2 ON T1.product_id = T2.product_id WHERE T1.del_flg='0' AND T1.product_id='$pid'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$file_path = '/alflearning-data/alfproduct/document/'.$ret["all_contents_download"];
		if (is_readable($file_path)){
			$file_size = filesize($file_path);
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$ret["all_contents_download_before"].'"');
			header('Content-Length: '.$file_size);
			//readfile($file_path);
			// out of memoryエラーが出る場合に出力バッファリングを無効
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			ob_start();
			// ファイル出力
			if ($file = fopen($file_path, 'rb')) {
				while(!feof($file) and (connection_status() == 0)) {
					echo fread($file, '4096'); //指定したバイト数ずつ出力
					ob_flush();
				}
				ob_flush();
				fclose($file);
			}
			ob_end_clean();
		} else {
			print("download err");
		}
	}
	
	unset($_SESSION['dl_tmp']);
}

$objDbConnect->close();
exit;
?>
