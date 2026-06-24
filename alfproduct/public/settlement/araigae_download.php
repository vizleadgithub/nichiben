<?php
if (isset($_POST['download'])){
	include(dirname(__FILE__) ."./../../module/module.php");
	
	$objDbConnect = new DbConnect();
	
	$sql = "SELECT member_id FROM tbl_member WHERE del_flag='0' AND fixed_flag='1'";
	$ret = $objDbConnect->query($sql);
	
	while (($row = $objDbConnect->fetch($ret))){
		$arr_monthly[] = $row;
	}
	
	if (!empty($arr_monthly)){
		$cnt = 1;
		$file_name = 'araigae_'.date('YmdHis').'.csv';
		
		foreach ($arr_monthly as $val){
			// CSV書き込み処理
			$csv = '';
			$csv.= '"'.$val["member_id"].'",';  // 会員ID
			$csv.= '"",';                       // カード登録連番(カードの選択だがデフォルトを常に指定)
			$csv.= '"'.ARAI_FREE_SPACE.'"';     // 加盟店自由項目
			$csv.= "\r\n";
			
			echo $csv;
			
			if ($cnt == 99999){
				$cnt = 1;
			} else {
				$cnt++;
			}
		}
		
		header('Content-Disposition:attachment; filename="'.$file_name.'"');
		header('Content-Type:application/octet-stream');
		
		exit;
		
	} else {
		echo '対象データが存在しません。';
	}
}
?>

洗替CSVダウンロード<br />
<form action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="download" value="1" />
<input type="submit" value="ダウンロード" />
</form>
