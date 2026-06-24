<?php
#[AllowDynamicProperties]
class Model_student_csv_upload extends CI_Model
{
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
	//	$this->load->database();
	}
	
	//----------------------------------------------
	//受講者CSVファイルのアップロード処理
	//  upload_yyyymmdd_hhmmss.csv にリネームして保存
	//  保存先ディレクトリは、global-config に設置する
	//----------------------------------------------
	function student_csv_upload($param){
		// 生徒CSVファイルのアップロード先ディレクトリ設定
		$upload_path = $this->config->item('student_dir');
		if(!is_dir($upload_path)){
			mkdir($upload_path, 0777, TRUE);
			chmod($upload_path, 0777);
		}
		
		// アップロードファイル名の作成
		$upload_file_name = "upload_".date("Ymd_His");	//'.csv';
		
		// アップロード処理
		$this->load->library('upload', array(
			'upload_path'   => $upload_path,
			'allowed_types' => '*',
			'overwrite'     => TRUE,
			'remove_spaces' => TRUE,
			'file_name'     => $upload_file_name,
		));
		if(!$this->upload->do_upload('local_file')){
			return FALSE;
		} else {
			// アップロード成功の場合、パーミッシヨンを777に変更
			$upload_data = $this->upload->data();
			chmod($upload_path.'/'.$upload_data['file_name'],0777);
			
			$local_file_line = $upload_data['file_name'];
		//	$local_file_line = sizeof(file($upload_path.'/'.$upload_data['file_name']));
		//	$local_file_line = TRUE;
			
			return $local_file_line;
		}
	}
}
?>
