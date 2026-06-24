<?php
#[AllowDynamicProperties]
class Master_photo extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	//private $photo_dir     = '/master/thumbnail/';
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		//headerへブラウザのキャッシュ無効化設定
		$this->output->set_header ("Cache-Control: no-store, no-cache, must-revalidate" );
		$this->output->set_header ("Cache-Control: post-check=0, pre-check=0", false );
	}
	
	//----------------------------------------------
	//index->画像表示へ
	//----------------------------------------------
	function index($teacher_id = 0){
		$this->thumbnail($teacher_id);
	}
	
	//----------------------------------------------
	//画像表示
	//----------------------------------------------
	function thumbnail($teacher_id = 0){
		
		//画像ファイルパス設定
		$file_path = $this->config->item('teacher_dir').'/'.$teacher_id.'/'.'profile.jpg';
		
		$data = read_file($file_path);
		//画像読み込み
		if(!$data){
			//存在しない場合、デフォルトイメージを設定
			$data = read_file('./'.$this->config->item('images_dir').'photo_1.png');
		}
		
		//出力
		$this->output->set_content_type('jpeg');
		$this->output->set_output($data);
	}
}

/*End of File program.php*/
