<?php
#[AllowDynamicProperties]
class Cms_ranking extends CI_Controller {
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
		
		//ヘルパー読み込み
		$this->load->helper('json');
		
		//headerへブラウザのキャッシュ無効化設定
		$this->output->set_header ("Cache-Control: no-store, no-cache, must-revalidate" );
		$this->output->set_header ("Cache-Control: post-check=0, pre-check=0", false );
		
		//プロファイラ（TRUEでデバッグ）
		$this->output->enable_profiler(FALSE);
		
		//ログインチェック
		if ( ! $this->libauth->is_logged_in()) {
			//非ログイン時はログインページへ
			redirect('/login_page?backurl='.$_SERVER['REQUEST_URI']);
		} else {
			//権限が無い場合はトップページにリダイレクト(トップページの場合はログアウト）
//			$work_auth = $this->libauth->get_teacher_auth();
//			if($work_auth['exam'] == 0){
//				redirect('admin_top');
//			}
			
			// 日弁連ユーザー以外はトップページにリダイレクト
			if($this->libauth->get_bar_association_id() != 1){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の問題（テスト）：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
//		$this->load->model('Modelschoolcontract');
//		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'exam'))){
//			redirect('admin_top');
//		}
	}
	
	//----------------------------------------------
	//一覧表示
	//----------------------------------------------
	function index($offset=0){
		// load language
		$this->lang->load('common');

		//表示用変数の初期化
		$data = array();

		$this->form_validation->set_rules('result' , $this->lang->line_or_def('result','result') , 'trim');
		$this->form_validation->run();
		$data['get_result'] = strip_tags($this->input->get('result', TRUE) ?? '');

		$this->load->model('model_ranking');
		//データ取得
		$ranking_list = $this->model_ranking->get_ranking_list(array(
			'school_id' 	=> $this->libauth->get_school_id()
		));
		$data['ranking_list'] = $ranking_list;

		$file_list = $this->model_ranking->get_file_list(array(
			'school_id' 	=> $this->libauth->get_school_id()
		));
		$data['file_text1'] = "";
		$data['file_name1'] = "";
		$data['file_path1'] = "";
		$data['file_text2'] = "";
		$data['file_name2'] = "";
		$data['file_path2'] = "";
		$data['file_text3'] = "";
		$data['file_name3'] = "";
		$data['file_path3'] = "";
		foreach($file_list as $index => $value){
			if( $value["no"]==1 ){
				$data['file_text1'] = $value["file_text"];
				$data['file_name1'] = $value["file_name"];
				$data['file_path1'] = $value["file_path"];
			}
			if( $value["no"]==2 ){
				$data['file_text2'] = $value["file_text"];
				$data['file_name2'] = $value["file_name"];
				$data['file_path2'] = $value["file_path"];
			}
			if( $value["no"]==3 ){
				$data['file_text3'] = $value["file_text"];
				$data['file_name3'] = $value["file_name"];
				$data['file_path3'] = $value["file_path"];
			}
		}

		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_ranking/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');

		//表示用変数の初期化
		$data = array();
		$data['get_result'] = "";

		$this->form_validation->set_rules('product_id_1', $this->lang->line_or_def('common_ranking_product_id_1','1位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_2', $this->lang->line_or_def('common_ranking_product_id_2','2位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_3', $this->lang->line_or_def('common_ranking_product_id_3','3位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_4', $this->lang->line_or_def('common_ranking_product_id_4','4位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_5', $this->lang->line_or_def('common_ranking_product_id_5','5位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_6', $this->lang->line_or_def('common_ranking_product_id_6','6位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_7', $this->lang->line_or_def('common_ranking_product_id_7','7位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_8', $this->lang->line_or_def('common_ranking_product_id_8','8位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_9', $this->lang->line_or_def('common_ranking_product_id_9','9位の商品'), 'trim|numeric|required');
		$this->form_validation->set_rules('product_id_10', $this->lang->line_or_def('common_ranking_product_id_10','10位の商品'), 'trim|numeric|required');

		$this->form_validation->set_rules('file_text1', 'file_text1', 'trim');
		$this->form_validation->set_rules('file_name1', 'file_name1', 'trim');
		$this->form_validation->set_rules('file_path1', 'file_path1', 'trim');
		$this->form_validation->set_rules('file_text2', 'file_text2', 'trim');
		$this->form_validation->set_rules('file_name2', 'file_name2', 'trim');
		$this->form_validation->set_rules('file_path2', 'file_path2', 'trim');
		$this->form_validation->set_rules('file_text3', 'file_text3', 'trim');
		$this->form_validation->set_rules('file_name3', 'file_name3', 'trim');
		$this->form_validation->set_rules('file_path3', 'file_path3', 'trim');

		//検証
		if($this->form_validation->run() == FALSE){
			$data['ranking']['product_id_1'] = $this->input->post('product_id_1');
			$data['ranking']['product_id_2'] = $this->input->post('product_id_2');
			$data['ranking']['product_id_3'] = $this->input->post('product_id_3');
			$data['ranking']['product_id_4'] = $this->input->post('product_id_4');
			$data['ranking']['product_id_5'] = $this->input->post('product_id_5');
			$data['ranking']['product_id_6'] = $this->input->post('product_id_6');
			$data['ranking']['product_id_7'] = $this->input->post('product_id_7');
			$data['ranking']['product_id_8'] = $this->input->post('product_id_8');
			$data['ranking']['product_id_9'] = $this->input->post('product_id_9');
			$data['ranking']['product_id_10'] = $this->input->post('product_id_10');
			$data['ranking']['product_name_1'] = $this->input->post('product_name_1');
			$data['ranking']['product_name_2'] = $this->input->post('product_name_2');
			$data['ranking']['product_name_3'] = $this->input->post('product_name_3');
			$data['ranking']['product_name_4'] = $this->input->post('product_name_4');
			$data['ranking']['product_name_5'] = $this->input->post('product_name_5');
			$data['ranking']['product_name_6'] = $this->input->post('product_name_6');
			$data['ranking']['product_name_7'] = $this->input->post('product_name_7');
			$data['ranking']['product_name_8'] = $this->input->post('product_name_8');
			$data['ranking']['product_name_9'] = $this->input->post('product_name_9');
			$data['ranking']['product_name_10'] = $this->input->post('product_name_10');

			$data['ranking']['file_text1'] = $this->input->post('file_text1');
			$data['ranking']['file_name1'] = $this->input->post('file_name1');
			$data['ranking']['file_path1'] = $this->input->post('file_path1');
			$data['ranking']['file_text2'] = $this->input->post('file_text2');
			$data['ranking']['file_name2'] = $this->input->post('file_name2');
			$data['ranking']['file_path2'] = $this->input->post('file_path2');
			$data['ranking']['file_text3'] = $this->input->post('file_text3');
			$data['ranking']['file_name3'] = $this->input->post('file_name3');
			$data['ranking']['file_path3'] = $this->input->post('file_path3');
			$data['file_text1'] = $this->input->post('file_text1');
			$data['file_name1'] = $this->input->post('file_name1');
			$data['file_path1'] = $this->input->post('file_path1');
			$data['file_text2'] = $this->input->post('file_text2');
			$data['file_name2'] = $this->input->post('file_name2');
			$data['file_path2'] = $this->input->post('file_path2');
			$data['file_text3'] = $this->input->post('file_text3');
			$data['file_name3'] = $this->input->post('file_name3');
			$data['file_path3'] = $this->input->post('file_path3');

			$data['ranking_list'] = array();
			for($i=1;$i<=10;$i++){
				$data['ranking_list'][] = array(
					"product_id" => $data['ranking']['product_id_'.$i],
					"product_name" => $data['ranking']['product_name_'.$i],
					"rank" => $i,
				);
			}
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_ranking/index',
							'submenu_idx' => 1,
							'view_data'   => $data,
						);
			//ビュー設定
			$this->_display_view($disp_param);
		} else {
			$data['ranking']['product_id_1'] = $this->input->post('product_id_1');
			$data['ranking']['product_id_2'] = $this->input->post('product_id_2');
			$data['ranking']['product_id_3'] = $this->input->post('product_id_3');
			$data['ranking']['product_id_4'] = $this->input->post('product_id_4');
			$data['ranking']['product_id_5'] = $this->input->post('product_id_5');
			$data['ranking']['product_id_6'] = $this->input->post('product_id_6');
			$data['ranking']['product_id_7'] = $this->input->post('product_id_7');
			$data['ranking']['product_id_8'] = $this->input->post('product_id_8');
			$data['ranking']['product_id_9'] = $this->input->post('product_id_9');
			$data['ranking']['product_id_10'] = $this->input->post('product_id_10');
			$data['ranking']['product_name_1'] = $this->input->post('product_name_1');
			$data['ranking']['product_name_2'] = $this->input->post('product_name_2');
			$data['ranking']['product_name_3'] = $this->input->post('product_name_3');
			$data['ranking']['product_name_4'] = $this->input->post('product_name_4');
			$data['ranking']['product_name_5'] = $this->input->post('product_name_5');
			$data['ranking']['product_name_6'] = $this->input->post('product_name_6');
			$data['ranking']['product_name_7'] = $this->input->post('product_name_7');
			$data['ranking']['product_name_8'] = $this->input->post('product_name_8');
			$data['ranking']['product_name_9'] = $this->input->post('product_name_9');
			$data['ranking']['product_name_10'] = $this->input->post('product_name_10');

			$data['ranking']['file_text1'] = $this->input->post('file_text1');
			$data['ranking']['file_name1'] = $this->input->post('file_name1');
			$data['ranking']['file_path1'] = $this->input->post('file_path1');
			$data['ranking']['file_text2'] = $this->input->post('file_text2');
			$data['ranking']['file_name2'] = $this->input->post('file_name2');
			$data['ranking']['file_path2'] = $this->input->post('file_path2');
			$data['ranking']['file_text3'] = $this->input->post('file_text3');
			$data['ranking']['file_name3'] = $this->input->post('file_name3');
			$data['ranking']['file_path3'] = $this->input->post('file_path3');
			$data['file_text1'] = $this->input->post('file_text1');
			$data['file_name1'] = $this->input->post('file_name1');
			$data['file_path1'] = $this->input->post('file_path1');
			$data['file_text2'] = $this->input->post('file_text2');
			$data['file_name2'] = $this->input->post('file_name2');
			$data['file_path2'] = $this->input->post('file_path2');
			$data['file_text3'] = $this->input->post('file_text3');
			$data['file_name3'] = $this->input->post('file_name3');
			$data['file_path3'] = $this->input->post('file_path3');

			$data['ranking_list'] = array();
			for($i=1;$i<=10;$i++){
				$data['ranking_list'][] = array(
					"product_id" => $data['ranking']['product_id_'.$i],
					"product_name" => $data['ranking']['product_name_'.$i],
					"rank" => $i,
				);
			}

			$data['error_msg'] = "";
			//++++++++++++++++++++++
			if($data['ranking']['product_id_2']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '2位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_3']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '3位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_3']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '3位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_4']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '4位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_4']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '4位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_4']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '4位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_5']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '5位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_5']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '5位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_5']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '5位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_5']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '5位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_6']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '6位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_6']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '6位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_6']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '6位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_6']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '6位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_6']==$data['ranking']['product_id_5']){
				$data['error_msg'] = '6位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_5']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_7']==$data['ranking']['product_id_6']){
				$data['error_msg'] = '7位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_5']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_6']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_8']==$data['ranking']['product_id_7']){
				$data['error_msg'] = '8位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_5']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_6']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_7']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_9']==$data['ranking']['product_id_8']){
				$data['error_msg'] = '9位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_1']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_2']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_3']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_4']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_5']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_6']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_7']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_8']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			if($data['ranking']['product_id_10']==$data['ranking']['product_id_9']){
				$data['error_msg'] = '10位指定の商品が重複しています<br>';
			}
			//++++++++++++++++++++++
			if( $data['error_msg'] != "" ){
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_ranking/index',
								'submenu_idx' => 1,
								'view_data'   => $data,
							);
				//ビュー設定
				$this->_display_view($disp_param);
			} else {
				//セッションへ検証済みデータを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['ranking']));

				////ビュー設定引数設定
				//$disp_param = array(
				//				'view_name'   => 'cms_ranking/index',
				//				'submenu_idx' => 1,
				//				'view_data'   => $data,
				//			);
				////ビュー設定
				//$this->_display_view($disp_param);
				redirect('/cms_ranking/commit');
			}
		}
	}

	function commit(){
		//モデル読み込み
		$this->load->model('model_ranking');

		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		for($i=1;$i<=10;$i++){
			//データ更新用引数再設定
			$data_param = array(
							'product_id' => $edit_form_data['product_id_'.$i],
							'rank'       => $i,
						);
			$this->model_ranking->update_ranking($data_param);
		}
		for($i=1;$i<=3;$i++){
			//データ更新用引数再設定
			$data_param = array(
							'no' => $i,
							'file_text' => $edit_form_data['file_text'.$i],
							'file_name' => $edit_form_data['file_name'.$i],
							'file_path' => $edit_form_data['file_path'.$i],
						);
			$this->model_ranking->update_ranking_add($data_param);
		}
		redirect('/cms_ranking/index?result=ok');
	}

	function pop_search_form(){
	}


	function search_product_name(){
		$this->load->helper('json');

		$param = array(
			"product_name"  => $this->input->post('product_name'),
		);

		$this->load->model('model_ranking');
		$product_list = $this->model_ranking->search_product_name($param);
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($product_list));
	}

	function set_ranking(){
		$param = array(
			"product_id"   => $this->input->post('product_id'),
			"rank"         => $this->input->post('rank'),
		);
		$this->load->model('model_ranking');
		$product_list = $this->model_ranking->set_ranking($param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode(array()));
	}

	/*
	function set_upload_file($no=0){
		//"/alflearning-data/alfproduct/wordpress/uploads/"
		// CREATE TABLE `alflearning`.`ranking_add` (
		//`no` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		//`file_name` VARCHAR( 128 ) NOT NULL ,
		//`file_path` VARCHAR( 256 ) NOT NULL ,
		//`status` INT NOT NULL DEFAULT '0'
		//) ENGINE = InnoDB 
		if( $no==1 || $no==2 || $no==3 ){
			$tmp_file = "";
			$copy_file = "";
			$updir = "/alflearning-data/alfproduct/wordpress/uploads/";
			$tmp_file = @$_FILES['file_data']['tmp_name'];
			$tmp_file_old = @$_FILES['file_data']['name'];
			@list($file_name,$file_type) = explode(".",@$_FILES['file_data']['name']);
			$copy_file = date("YmdHis").uniqid()."." . $file_type;
			if (is_uploaded_file($_FILES["file_data"]["tmp_name"])) {
				if (move_uploaded_file($tmp_file,$updir.$copy_file)) {
					@chmod($updir.$copy_file, 0644);
				} else {
				}
			} else {
			}
			$res_json = json_encode(
				array(
					'no'=>$no,
					'file_name'=>$tmp_file_old,
					'file_path'=>$copy_file,
				)
			);
			$this->output->set_header("HTTP/1.0 200 OK");
			$this->output->set_content_type('application/json; charset=utf-8');
			$this->output->set_output( $res_json );
		}
	}
	*/
	function get_token(){
		$upload_token = uniqid('', true);
		$this->session->set_userdata('upload_token',$upload_token);
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode(['upload_token'=>$upload_token]));
		return;
	}
	function set_upload_file($no=0){
		$upload_token = $this->input->post('upload_token') ?? '';
		$ses_upload_token = $this->session->userdata('upload_token');
		$this->session->set_userdata('upload_token',"");

		if($upload_token == $ses_upload_token && $ses_upload_token!=''){
			$no = $this->input->post('no') ?? 0;
			if( $no==1 || $no==2 || $no==3 ){
				$tmp_file = "";
				$copy_file = "";
				$updir = "/alflearning-data/alfproduct/wordpress/uploads/";

				$tmp_file = @$_FILES['file_data']['tmp_name'];
				$tmp_file_old = @$_FILES['file_data']['name'];

				// ファイル名と拡張子を分離
				@list($file_name, $file_type) = explode(".", strtolower(@$_FILES['file_data']['name']));

				// 許可する拡張子リスト
				$allowed_extensions = ['pdf', 'docx', 'doc', 'zip', 'xls', 'xlsx'];

				// 拡張子チェック
				if (!in_array($file_type, $allowed_extensions)) {
					$res_json = json_encode(
						array(
							'error' => '許可されていないファイルタイプです。',
							'status' => 'error',
						)
					);
					$this->output->set_header("HTTP/1.0 400 Bad Request");
					$this->output->set_content_type('application/json; charset=utf-8');
					$this->output->set_output($res_json);
					return;
				}

				// ファイル名をユニークに生成
				$copy_file = date("YmdHis") . uniqid() . "." . $file_type;

				// ファイルがアップロードされたか確認
				if (is_uploaded_file($_FILES["file_data"]["tmp_name"])) {
					// ファイルの移動
					if (move_uploaded_file($tmp_file, $updir . $copy_file)) {
						@chmod($updir . $copy_file, 0644);

						// アップロード成功時のレスポンス
						$res_json = json_encode(
							array(
								'no' => $no,
								'file_name' => $tmp_file_old,
								'file_path' => $copy_file,
								'status' => 'success',
							)
						);
						$this->output->set_header("HTTP/1.0 200 OK");
						$this->output->set_content_type('application/json; charset=utf-8');
						$this->output->set_output($res_json);
						return;
					} else {
						// ファイル移動失敗時
						$res_json = json_encode(
							array(
								'error' => 'ファイルの移動に失敗しました。',
								'status' => 'error',
							)
						);
						$this->output->set_header("HTTP/1.0 500 Internal Server Error");
						$this->output->set_content_type('application/json; charset=utf-8');
						$this->output->set_output($res_json);
						return;
					}
				} else {
					// アップロードされていない場合
					$res_json = json_encode(
						array(
							'error' => 'ファイルがアップロードされていません。',
							'status' => 'error',
						)
					);
					$this->output->set_header("HTTP/1.0 400 Bad Request");
					$this->output->set_content_type('application/json; charset=utf-8');
					$this->output->set_output(json_encode(["err"=>"3"]));
					return;
				}
			} else {
				$this->output->set_header("HTTP/1.0 400 Bad Request");
				$this->output->set_content_type('application/json; charset=utf-8');
				$this->output->set_output(json_encode(["err"=>"2"]));
				return;
			}
		} else {
			$this->output->set_header("HTTP/1.0 400 Bad Request");
			$this->output->set_content_type('application/json; charset=utf-8');
			$this->output->set_output(json_encode(["err"=>"1"]));
			return;
		}
	}

	//----------------------------------------------
	//ビュー表示
	// [2012/08/20]講座チェックボックス用データ取得を追加
	// [2012/10/12]タグドロップダウン用データ取得を追加
	//----------------------------------------------
	function _display_view($param) {
		//引数設定
		$param = array_merge(
						array(
							'view_name'   => '',
							'submenu_idx' => 0,
							'view_data'   => array(),
						),
						$param
					);

		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}

} 

/*End of File program.php*/
