<?php
include(dirname(__FILE__) ."./../../module/module.php");

$err_flg = 0;

if ($_POST['act'] == 'file_upload' && isset($_FILES['upfile'])){
	$err_user_data = array();
	
	$objDbConnect = new DbConnect();
	
	// セッションがセットされていた場合は削除する
	if (isset($_SESSION['err_user'])){
		unset($_SESSION['err_user']);
	}
	
	// アップロードファイルかどうかの確認
	if (is_uploaded_file($_FILES['upfile']['tmp_name'])){
		$upload_dir_name = dirname(__FILE__) ."/../file/tmp";
		$tmp_name         = $_FILES['upfile']['tmp_name'];
		$file_name_before = $_FILES['upfile']['name'];
		$file_name        = 'araigae_'.date('YmdHis').'.csv';
		// 拡張子チェック
		$extension = substr(strrchr($file_name_before, '.') ,1);
		if ($extension == 'txt' || $extension == 'csv'){
			// tmpより指定ディレクトリにファイルを保存
			if (move_uploaded_file($tmp_name, "$upload_dir_name/$file_name")){
				// 内容チェック
				$fp = fopen("$upload_dir_name/$file_name", "r");
				if ($fp){
					$cnt = 1;
					while (($csv = fgets($fp))){
						$tmp_data = array();
						$data = array();
						$tmp_data = explode(",", $csv);
						foreach ($tmp_data as $val){
							$data[] = trim($val, '"');
						}
						unset($val);
						
						// カラム数チェック
						if (count($data) == 13){
							// カード有効性チェック
							if ($data[6] != 0){
								$err_flg = 1;
								
								// 有効性がエラーのユーザー情報を取得
								$sql = "SELECT member_id, name, email FROM tbl_member WHERE del_flag = '0' AND member_id = '".$data[0]."'";
								$ret = $objDbConnect->query_fetch($sql);
								
								$err_user_data[] = $ret;
								
								$err_msg[] = '有効性エラー(会員ID:'.$ret['member_id'].'　名前:'.$ret['name'].'　email:'.$ret['email'].')';
							}
							
						} else {
							$err_flg = 1;
							$err_msg[] = $cnt.'行目のカラム数が正しくありません。';
							
						}
						
						$cnt++;
					}
					
					fclose($fp);
					
					// エラーユーザーの保持
					if (!empty($err_user_data)){
						$_SESSION['err_user'] = $err_user_data;
					}
				}
				
				// tmpファイルの削除
				unlink("$upload_dir_name/$file_name");
				
			} else {
				$err_flg = 1;
				$err_msg[] = 'ファイルのアップロードに失敗しました。';
				
			}
		} else {
			$err_flg = 1;
			$err_msg[] = 'txtまたはcsv形式のファイルをアップロードしてください。';
			
		}
	} else {
		$err_flg = 1;
		$err_msg[] = 'ファイルをアップロードしてください。';
		
	}
	
	if ($err_flg){
		echo '<div style="color:red;">';
		foreach ($err_msg as $val){
			echo $val.'<br />';
		}
		echo '</div>';
		
	} else {
		echo '全ユーザーのカード有効性ok!';
	}
	
} elseif ($_POST['act'] == 'send_mail'){
	if (isset($_SESSION['err_user'])){
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		
		foreach ($_SESSION['err_user'] as $val){
			$objQdmail = new Qdmail();
			// 件名設定
			$objQdmail->subject(ARAI_ERR_MAIL_SUBJECT);
			// 送信元設定
			$objQdmail->from(ARAI_ERR_MAIL_FROM, ARAI_ERR_MAIL_FROM_JA);
			// 送信先設定
			$objQdmail->to($val['email']);
			// 本文設定
			$text = '';
			$text = $val['name']."様\r\n\r\n";
			$text.= "本文\r\nてすと。";
			$objQdmail->text($text);
			
			// 送信処理
			$res = $objQdmail->send();
			if ($res){
			} else {
				$err_flg = 1;
				echo '送信失敗ユーザー(会員ID:'.$val['member_id'].'　名前:'.$val['name'].'　email:'.$val['email'].')';
			}
			
		}
		
		if (!$err_flg){
			echo 'メール送信が完了しました。';
		}
		
		// エラーユーザーの削除
		unset($_SESSION['err_user']);
	}
}
?>

<div>洗替CSVアップロード(結果をアップロードするとこ)</div>
<form action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="file_upload" />
<p>ファイル：<input type="file" name="upfile" size="50" /></p>
<input type="submit" value="アップロード" />
</form>

<?php if (isset($_SESSION['err_user'])){ ?>
<div>有効性エラーユーザーに対してメールを送信します。</div>
<form action="#" method="post">
<input type="hidden" name="act" value="send_mail" />
<input type="submit" value="メール送信" />
</form>
<?php } ?>
