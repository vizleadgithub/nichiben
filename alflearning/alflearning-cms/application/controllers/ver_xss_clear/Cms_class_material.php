<?php
#[AllowDynamicProperties]
class Cms_class_material extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	
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
		
		//ログインチェック
		if ( ! $this->libauth->is_logged_in()) {
			//非ログイン時はログインページへ
			redirect('/login_page?backurl='.$_SERVER['REQUEST_URI']);
		} else {
			//権限が無い場合はトップページにリダイレクト(トップページの場合はログアウト）
			$work_auth = $this->libauth->get_teacher_auth();
			if($work_auth['course_class'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の授業：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	//
	//----------------------------------------------
	function index(){
	}
	
	//----------------------------------------------
	//登録済み資料・登録対象資料の一覧表示（index）
	//----------------------------------------------
	function material_select($class_id = 0){

		//表示用変数の初期化
		$data = array();
		$data['delete_checked'] = array();
		$data['insert_checked'] = array();

		//セッションデータ取得（確認画面からの遷移の処理）
		$session_data = $this->session->userdata('edit_form_data');
		if($session_data){
			$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
			if(isset($edit_form_data['delete_checked']) &&  $edit_form_data['delete_checked'] <> ''){
				$data['delete_checked'] = $edit_form_data['delete_checked'];
			}
			if(isset($edit_form_data['insert_checked']) &&  $edit_form_data['insert_checked'] <> ''){
				$data['insert_checked'] = $edit_form_data['insert_checked'];
			}
		}
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');

		// load language
		$this->lang->load('error');

//		//表示用変数の初期化
//		$data = array();
//		$data['delete_checked'] = array();
//		$data['insert_checked'] = array();

		//model Load
		$this->load->model('model_class_material');
		
		//授業管理・資料管理の権限有無確認
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$auth_class_material = $this->_get_auth_class_material($class_id);
		
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_class_material_auth','授業に対して資料を操作する権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			// model Load
			$this->load->model('model_class_material');
			
			// 授業IDの格納
			$data['class_id'] = $class_id;
			
			// 授業情報の格納
			$class_list = $this->model_class_material->get_class_list($class_id);
			$data['class_list'] = $class_list;
			
			// 授業に登録済みの資料一覧の格納
			$param = array(
				'school_id'           => $this->libauth->get_school_id(),
				'login_teacher_id'    => $login_teacher_id,
				'class_id'            => $class_id,
				'auth_class_material' => $auth_class_material,
			);
			$class_material_list = $this->model_class_material->get_class_material_list($param);
			$data['class_material_list'] = $class_material_list;

			// 授業に登録可能な資料一覧の格納
			$material_list = $this->model_class_material->get_material_list($param);
			$data['material_list'] = $material_list;
			
			//ビュー設定引数設定
			$disp_param = array(
				'view_name'   => 'cms_class_material/index',
				'submenu_idx' => 1,
				'view_data'   => $data,
			);
			//ビュー設定
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//登録済み資料の削除・登録対象資料の登録の確認（confirm）
	//----------------------------------------------
	function class_material_confirm(){
		//表示用変数の初期化
		$data = array();

		// get post
		$delete_check = $this->input->post('delete_check');
		$insert_check = $this->input->post('insert_check');
		$class_id     = $this->input->post('class_id');

		//授業管理・資料管理の権限有無確認
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$auth_class_material = $this->_get_auth_class_material($class_id);
		
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_class_material_auth','授業に対して資料を操作する権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//格納（テーブル更新に使用）
			$data['class_id']          = $class_id;
			$data['delete_check_list'] = "";
			$data['insert_check_list'] = "";
			
			# 全てのチェックがない場合は前画面へ戻す
			if((!$delete_check) && (!$insert_check)){
				redirect('cms_class_material/material_select/'.$class_id);
			}else{
				// model Load
				$this->load->model('model_class_material');
				
				if(($delete_check) && ($delete_check!="")){
					$delete_check_list = "";
					foreach($delete_check as $delete_check_id) {
						if($delete_check_list == ""){
							$delete_check_list .= $delete_check_id;
						}else{
							$delete_check_list .= ",".$delete_check_id;
						}
					}
					$data['delete_check_list'] = $delete_check_list;
					
					// 授業に登録済みの資料一覧の取得
					$param = array(
									'school_id'           => $this->libauth->get_school_id(),
									'login_teacher_id'    => $login_teacher_id,
									'class_id'            => $class_id,
									'auth_class_material' => $auth_class_material,
									'delete_check_list'   => $delete_check_list,
								);
					$class_material_list = $this->model_class_material->get_class_material_list($param);
					$data['class_material_list'] = $class_material_list;
				}
				
				if(($insert_check) && ($insert_check != "")){
					$insert_check_list = "";
					foreach($insert_check as $insert_check_id) {
						if($insert_check_list == ""){
							$insert_check_list .= $insert_check_id;
						}else{
							$insert_check_list .= ",".$insert_check_id;
						}
					}
					$data['insert_check_list'] = $insert_check_list;

					// 授業に登録可能な資料一覧の取得
					$param = array(
									'school_id'           => $this->libauth->get_school_id(),
									'login_teacher_id'    => $login_teacher_id,
									'class_id'            => $class_id,
									'auth_class_material' => $auth_class_material,
									'insert_check_list'   => $insert_check_list,
								);
					$material_list = $this->model_class_material->get_material_list($param);
					$data['material_list'] = $material_list;
				}
				
				//セッションへ検証済みデータを書き込み
				$session_data['class_material']['class_id'] = $data['class_id'];
				if(!$delete_check){
					$delete_check = array();
				}
				if(!$insert_check){
					$insert_check = array();
				}
				$session_data['class_material']['delete_checked'] = $delete_check;
				$session_data['class_material']['insert_checked'] = $insert_check;
				$this->session->set_userdata('edit_form_data',serialize($session_data['class_material']));
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_class_material/confirm',
								'submenu_idx' => 1,
								'view_data'   => $data,
							);
				//ビュー設定
				$this->_display_view($disp_param);
			}
		}
	}
	
	//----------------------------------------------
	//登録済み資料の削除・登録対象資料の登録の完了（commit）
	//----------------------------------------------
	function class_material_upload_exec(){
		//表示用変数の初期化
		$data = "";
		
		// load language
		$this->lang->load('error');
		
		// model Load
		$this->load->model('model_class_material');
		
		// get post
		$delete_check_list = $this->input->post('delete_check_list');
		$insert_check_list = $this->input->post('insert_check_list');
		$class_id          = $this->input->post('class_id');

		//授業管理・資料管理の権限有無確認
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$auth_class_material = $this->_get_auth_class_material($class_id);

		// 権限を持たない場合、エラーを返す
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_class_material_auth','授業に対して資料を操作する権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//ログインがSuperUser・学校管理者の場合、授業担当講師を授業マスタから取得
			if($auth_class_material == 1){
				$class_list = $this->model_class_material->get_class_list($class_id);
				if($class_list){
					$login_teacher_id = $class_list[0]['teacher_id'];
				}
			}
			
			// 授業へ登録した資料の削除
			if(($delete_check_list) && ($delete_check_list != "")){
				$array_data = explode(",", $delete_check_list);
				foreach($array_data as $class_material_id) {
				
					// 授業資料マスタから１件取得
					$param_data = array(
						'class_material_id'		=> $class_material_id,
						'login_teacher_id'		=> $this->libauth->get_teacher_id(),
						'auth_class_material'	=> $auth_class_material,
					);
					$db_data = $this->model_class_material->get_class_material($param_data);

					$param = array(
									'class_material_id'	 => $class_material_id,
									'class_id'    		 => $class_id,
									'teacher_id' 		 => $login_teacher_id,
									'student_id'		 => $db_data['student_id'],
								);
					$results = $this->model_class_material->delete_class_material($param);

					// 授業資料削除のログ出力
					$this->_cms_insert_log($class_material_id, '管理画面-授業資料削除');
				}
			}
			
			// 資料を授業資料に登録
			if(($insert_check_list) && ($insert_check_list != "")){
				$array_data = explode(",", $insert_check_list);
				foreach($array_data as $material_id) {
					$param = array(
									'material_id'	 => $material_id,
									'class_id'		 => $class_id,
									'teacher_id'	 => $login_teacher_id,
								);
					$results = $this->model_class_material->insert_class_material($param);

					// 授業資料削除のログ出力
					$this->_cms_insert_log($results, '管理画面-授業資料登録');
				}
			}
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_class_material/commit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//完了フォーム表示
			$this->_display_view($disp_param);
		}
	}

	//----------------------------------------------
	//授業資料ダウンロード（index）
	//  マスタファイル・頁ファイル・履歴ファイル全て
	//----------------------------------------------
	function download_file($class_material_id, $class_id){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//授業管理・資料管理の権限有無確認
		$auth_class_material = $this->_get_auth_class_material($class_id);

		if($auth_class_material == 0){
			//表示用変数の初期化
			$data = "";
			
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_download_auth','ダウンロード権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ取得用引数設定
			$param = array(
				'login_teacher_id'		=> $this->libauth->get_teacher_id(),
				'class_material_id'		=> $class_material_id,
				'auth_class_material'	=> $auth_class_material,
			);
			
			//model Load
			$this->load->model('model_class_material');
			
			//データ取得（ダウンロード元ディレクトリ情報）
			$db_data = $this->model_class_material->get_class_material($param);

			if(!$db_data){
				//表示用変数の初期化
				$data = "";
				
				//戻り先設定
				$data['returnurl']     = site_url('cms_class');
				$data['error_message'] = $this->lang->line_or_def('error_download_auth','ダウンロード権限がありません<br />ログインし直してください');
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'course_class_error',
								'submenu_idx' => 4,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}else{
				// ディレクトリ設定
				$rootDir = $this->config->item('class_material_dir');
				
				$dir_path = "";
				if(file_exists($rootDir."/".$db_data['class_id']."/teacher_".$db_data['teacher_id']."/".$db_data['class_material_id']."/Page1/master-Page1.jpg")){
					// 管理画面の資料管理から選択した資料
					$dir_path = $rootDir."/".$db_data['class_id']."/teacher_".$db_data['teacher_id']."/".$db_data['class_material_id']."/";
				}elseif(file_exists($rootDir."/".$db_data['class_id']."/teacher_".$db_data['teacher_id']."/".$db_data['class_material_id']."/Page1/master.jpg")){
					// 授業画面にて先生が記述したノート
					$dir_path = $rootDir."/".$db_data['class_id']."/teacher_".$db_data['teacher_id']."/".$db_data['class_material_id']."/";
				}elseif(file_exists($rootDir."/".$db_data['class_id']."/student_".$db_data['student_id']."/".$db_data['class_material_id']."/submit/Page1/master.jpg")){
					// 授業画面にて生徒が提出したノート
					$dir_path = $rootDir."/".$db_data['class_id']."/student_".$db_data['student_id']."/".$db_data['class_material_id']."/submit/";
				}else{
					//戻り先設定
					$data['returnurl']     = site_url('cms_class');
					$data['error_message'] = $this->lang->line_or_def('error_not_file','ファイルが見つかりません<br />管理者に問い合わせてください')
											.'<br /><br />'.$this->lang->line_or_def('common_file_name','ファイル名').'&nbsp;:&nbsp;'.$db_data['material_logic_name']
											.'<br />File Name&nbsp;:&nbsp;'.$db_data['material_name'];
					//ビュー設定引数設定
					$disp_param = array(
									'view_name'   => 'course_class_error',
									'submenu_idx' => 4,
									'view_data'   => $data,
								);
					//確認フォーム表示
					$this->_display_view($disp_param);
				}
				// ダウンロード時のファイル名（拡張子なし）の作成
				// └ 論理資料ファイル名
				//    論理資料ファイル名がない場合は、物理資料ファイル名（拡張子なし）
				if($db_data['material_logic_name'] == ''){
					$position  = strrpos($db_data['material_name'], '.');
					$extension = substr($db_data['material_name'], 0, $position - 1);
					$db_data['material_logic_name'] = $extension;
				}

				$result_data = $this->_make_zip_data(
								$dir_path, 
								$db_data['material_name'], 
								$db_data['material_logic_name']);
			}
		}
	}

	//----------------------------------------------
	// 授業管理からの資料登録画面（add_material）
	//----------------------------------------------
	function add_material($class_id = 0){
		//表示用変数の初期化
		$data = array();
		

		//授業管理・資料管理の権限有無確認
		$auth_class_material = $this->_get_auth_class_material($class_id);

		// 権限を持たない場合、エラーを返す
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_class_material_auth','授業に対して資料を操作する権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{

			// 授業IDの格納
			$data['class_id'] = $class_id;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_class_material/add_material',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//ビュー設定
			$this->_display_view($disp_param);
		}
	}

	//----------------------------------------------
	// 授業から直接の資料の登録と資料の登録（commit）
	// [2012/08/20]資料マスタ登録後、資料講座マスタ登録を行う処理を追加
	//----------------------------------------------
	function class_material_upload_exec_quick(){
		
		// load language
		$this->lang->load('error');
		
		// model Load
		$this->load->model('model_class_material');

		// get
		$class_id   = $this->input->post('class_id');
		$list_count = $this->input->post('list_count');
		$school_id  = $this->libauth->get_school_id();
		$result_comment = '';

		// 授業情報の取得
		$class_list = $this->model_class_material->get_class_list($class_id);
		$teacher_id = $class_list[0]['teacher_id'];
		$cource_id  = $class_list[0]['cource_id'];	// [2012/08/20]

		// ループ処理
		for($_count = 0; $_count <= $list_count; $_count++){
			//表示用変数の初期化
			$data = "";
			$last_material_id      = -1;
			$last_class_material_id = -1;
			
			$material_logic_name   = $this->input->post('material_logic_name_'.$_count);
			$local_file            = $_FILES['local_file_'.$_count];
			$material_caption      = $this->input->post('material_caption_'.$_count);

			// ファイル名・ファイルがある場合に処理
			if( (!empty($material_logic_name) ) && ($local_file['error'] == 0 ) ){
				// パラメータセット
				$data['class_material']['material_logic_name'] = $material_logic_name;
				$data['class_material']['local_file']          = 'local_file_'.$_count;
				$data['class_material']['material_caption']    = $material_caption;
				$data['class_material']['school_id']           = $school_id;
				$data['class_material']['teacher_id']          = $teacher_id;
				$data['class_material']['class_id']            = $class_id;

				// 資料マスタへの新規登録＋ファイル登録
				$last_material_id = $this->model_class_material->insert_material_quick($data['class_material']);
				
				// 授業資料マスタへの新規登録＋ファイル登録
				// 前提として、資料マスタ登録＋ファイル登録が成功していること
				if($last_material_id > 0){

					//[2012/08/20]資料講座マスタへの新規登録
					$data_param = array(
									'material_id' => $last_material_id,
									'cource_id'   => $cource_id,
								);
					$this->model_class_material->update_material_lectures($data_param);

					$data['class_material']['material_id']            = $last_material_id;
					$last_class_material_id = $this->model_class_material->insert_class_material_quick($data['class_material']);
				}
			}
		}

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_class_material/add_material_commit',
						'submenu_idx' => 3,
						'view_data'   => $data,
					);
		//完了フォーム表示
		$this->_display_view($disp_param);
	}
/*---------------------------------------------------------------------------------*/

/*
#print "aaa-----";
#print_r($_FILES);

#print "bbb-----";
#print_r($this->input->post());
		// get post
		$material_logic_name_list = $this->input->post('material_logic_name');
		$local_file_list          = $_FILES;  #$this->input->post('local_file');
		$material_caption_list    = $this->input->post('material_caption');
		$class_id                 = $this->input->post('class_id');
		
		$school_id                = $this->libauth->get_school_id();
#print "ccc-----";
#print_r($local_file_list);
#exit();
		// 
		for($list_count = 0; $list_count <= 5 - 1; $list_count++){
			//表示用変数の初期化
			$data = "";
			$local_file;
			
			//処理可能かの確認
			$add_flag = 0;
			if( !empty($material_logic_name_list[$list_count]) ){
				$add_flag += 10;
			}
			
		//	if( $local_file_list['local_file']['error'][$list_count]) == 0 ){

			if( !empty($local_file_list['local_file']['name'][$list_count]) ){
				$add_flag += 1;
				$local_file = array(
					'name'     => $local_file_list['local_file']['name'][$list_count],
					'type'     => $local_file_list['local_file']['type'][$list_count],
					'tmp_name' => $local_file_list['local_file']['tmp_name'][$list_count],
					'error'    => $local_file_list['local_file']['error'][$list_count],
					'size'     => $local_file_list['local_file']['size'][$list_count],
				);
				//	[name]     => Array ( [0] => Microsoft_壁紙_ハロウィン.jpg [1] => [2] => [3] => [4] => ) 
				//	[type]     => Array ( [0] => image/jpeg [1] => [2] => [3] => [4] => ) 
				//	[tmp_name] => Array ( [0] => /tmp/phphgAHQg [1] => [2] => [3] => [4] => ) 
				//	[error]    => Array ( [0] => 0 [1] => 4 [2] => 4 [3] => 4 [4] => 4 ) 
				//	[size]     => Array ( [0] => 1228527 [1] => 0 [2] => 0 [3] => 0 [4] => 0 ) 
			}
			
			if($add_flag==11){
				// 授業情報の取得
				$class_list = $this->model_class_material->get_class_list($class_id);
				
				// パラメータセット
				$data['class_material']['material_logic_name'] = $material_logic_name_list[$list_count];
				$data['class_material']['local_file']          = $local_file;
				$data['class_material']['material_caption']    = $material_caption_list[$list_count];
				$data['class_material']['school_id']           = $school_id;
				$data['class_material']['teacher_id']          = $class_list[0]['teacher_id'];
				$data['class_material']['class_id']            = $class_list[0]['class_id'];
				$data['class_material']['list_count']          = $list_count;

				// 資料マスタへの新規登録＋ファイル登録
				$last_material_id = $this->model_class_material->insert_material_quick($data['class_material']);
print "last_material_id = ";
print $last_material_id;



			}
			
			// ファイル名・ファイルがある場合に処理
			if( (!empty($material_logic_name_list[$list_count])) && (!empty($local_file_list[$list_count])) ){
				
				// 授業情報の取得
				$class_list = $this->model_class_material->get_class_list($class_id);
				
				// パラメータセット
				$data['class_material']['material_logic_name'] = $material_logic_name_list[$list_count];
				$data['class_material']['local_file']          = $local_file_list[$list_count];
				$data['class_material']['material_caption']    = $material_caption_list[$list_count];
				$data['class_material']['school_id']           = $school_id;
				$data['class_material']['teacher_id']          = $class_list[0]['teacher_id'];
				$data['class_material']['class_id']            = $class_list[0]['class_id'];

				// 資料マスタへの新規登録＋ファイル登録
				$last_material_id = $this->model_class_material->insert_material_quick($data['class_material']);
print "last_material_id = ";
print $last_material_id;
				// 授業資料マスタへの新規登録＋ファイル登録
//				$last_class_material_id = $this->model_class_material->insert_class_material_quick($data['class_material']);
//print "last_class_material_id = ";
//print $last_class_material_id;
			}
		}
*/




	
	//----------------------------------------------
	//ダウンロード（ZIP）
	//  指定されたディレクトリ内全て
	//----------------------------------------------
	function _make_zip_data($dir_path, $material_name, $material_logic_name){
		
		//zipライブラリのロード
		$this->load->library('zip');
		
		// 対象ディレクトリの読み込み（相対パス）
		$this->zip->read_dir($dir_path, false);
		
		//zipのダウンロード
		$this->zip->download($material_logic_name.'.zip');
		
		//キャッシュのクリア
		$this->zip->clear_data();
	}

	//----------------------------------------------
	//授業管理・資料管理の権限有無確認
	//  ログインユーザーに授業管理・資料管理の権限が
	//  あるかの確認
	//  [ver2.0] サブ講師にも権限を付加するように修正
	//----------------------------------------------
	function _get_auth_class_material($class_id){
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$work_auth           = $this->libauth->get_teacher_auth();
		$auth_class_material = 0;
		
		if($login_teacher_id < 0){
			//SuperUser
			$auth_class_material = 1;
		}elseif($work_auth['school_admin'] == 1){
			//学校管理者
			$auth_class_material = 1;
		}elseif( ($work_auth['material'] == 1) && ($work_auth['course_class'] == 1) ){
			//講師かつ授業管理・資料管理の権限あり
			
			//選択した授業が担当授業であれば権限付与
			//model Load
			$this->load->model('model_class_material');
			$class_data       = $this->model_class_material->get_class_list($class_id);
			$class_teacher_id = $class_data[0]['teacher_id'];
			if($class_teacher_id == $login_teacher_id){
				$auth_class_material = 1;	//2;
			}else{

				//[ver2.0]サブ講師権限確認
				$this->load->model('model_class');
				$data_param_2 = array(
								'class_id'   => $class_id,
								'teacher_id' => $login_teacher_id,
							);
				if( $this->model_class->check_class_teacher($data_param_2) ){
					//権限あり
					$auth_class_material = 1;
				}

			}
		}else{
			//権限なし
			$auth_class_material = 0;
		}
		
		//戻り値
		return $auth_class_material;
	}
	
	//----------------------------------------------
	//ビュー表示
	//----------------------------------------------
	function _display_view($param) {
		//引数設定
		$param = array_merge(
						array(
							'view_name'   => '',
							'submenu_idx' => 0,
							'view_data'   => array(),
							'sel_month'   => 0,
						),
						$param
					);

		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		$this->load->view($param['view_name'], $param['view_data']);
		
	}

	//----------------------------------------------
	// アプリログ出力
	// ・授業資料の登録、授業資料の削除
	//----------------------------------------------
	function _cms_insert_log($class_material_id = 0, $log_option = '管理画面-授業資料'){
		//アプリログ出力
		$this->load->model('model_applog');
		$clientIP = "";
		if($_SERVER["HTTP_X_FORWARDED_FOR"]){
			$arr_HTTP_X_FORWARDED_FOR = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
			$_SERVER['HTTP_X_FORWARDED_FOR'] = $arr_HTTP_X_FORWARDED_FOR[0];
			$clientIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if($_SERVER["REMOTE_ADDR"]){
			$clientIP = $_SERVER["REMOTE_ADDR"];
		}
		$param = array(
						'school_id'   => $this->libauth->get_school_id() ,   // 学校ID
						'teacher_id'  => $this->libauth->get_teacher_id() ,  // ログイン講師ID
					//	'student_id'  => 0 ,                                 // 受講者ID
						'log_name'    => 'Before school_id' ,                // ログ名称
						'log_value'   => $old_school_id ,                    // ログ内容
						'log_option'  => $log_option ,                       // ログ予備
						'remote_addr' => $clientIP ,           // リモート側IPアドレス
						'user_agent'  => $_SERVER["HTTP_USER_AGENT"] ,       // USER AGENT
						'server_name' => $_SERVER["SERVER_NAME"] ,           // サーバーホスト名
						'php_self'    => $_SERVER["PHP_SELF"] ,              // スクリプトファイル名
					);
		$value_data = $this->model_applog->insert_log($param);
		
		return $value_data;
	}
} 

/*End of File program.php*/
