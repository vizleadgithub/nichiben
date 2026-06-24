<?php
#[AllowDynamicProperties]
class Cms_notification extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
//	private $day_second            = 86400;			//日数計算用、一日秒数
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		//headerへブラウザのキャッシュ無効化設定
		$this->output->set_header ("Cache-Control: no-store, no-cache, must-revalidate" );
		$this->output->set_header ("Cache-Control: post-check=0, pre-check=0", false );
		
		//プロファイラ（TRUEでデバッグ）
		$this->output->enable_profiler(FALSE);
		
	}

	//----------------------------------------------
	// [Ajax用]通知情報の取得
	//----------------------------------------------
	function get_notification(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"		=>	$this->libauth->get_school_id(),
					"teacher_id"	=>	$this->libauth->get_teacher_id(),
					);
		//print var_dump($drop_param);
//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();

		// 通知情報の取得
		$this->load->model('model_notification');
		$notifications = $this->model_notification->get_notification($drop_param);
		//$param = json_encode($param);
		//print var_dump($param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($notifications));
	}

	//----------------------------------------------
	// [Ajax用]通知テーブルの更新
	//----------------------------------------------
	function update_notification(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"notification_id"	=>	$this->input->post('notification_id'),
					);
		
		// 通知情報の取得
		$this->load->model('model_notification');
		$notifications = $this->model_notification->update_notification($drop_param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($notifications));
	}

	//----------------------------------------------
	// [ajax]通知テーブル更新（一括更新）
	//----------------------------------------------
	function update_notification_all(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"				=>	$this->libauth->get_school_id(),
					"teacher_id"			=>	$this->libauth->get_teacher_id(),
					"notification_id_list"	=>	$this->input->post('notification_id_list'),
					);
		
		// 通知情報の取得
		$this->load->model('model_notification');
		$notifications = $this->model_notification->update_notification_all($drop_param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($notifications));
	}

} 

/*End of File program.php*/
