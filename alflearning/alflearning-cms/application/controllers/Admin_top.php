<?php
#[AllowDynamicProperties]
class Admin_top extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	public $benchmark; // ここで事前定義

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
			if($work_auth['admin_top'] == 0){
				redirect('login_page/logout');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
	}
	
	//----------------------------------------------
	//初期表示
	//----------------------------------------------
	function index(){
		$userdata = $this->session->userdata;
		//var_dump($userdata);
		//表示用変数の初期化
		$data = array();
		
		//お知らせモデル読み込み
		$this->load->model('model_information');
		//データ取得用引数設定
		$data_param = array(
						'school_id' => $this->libauth->get_school_id(),
					);
		//データ取得
		$info_list = $this->model_information->get_information_list_toppage($data_param); 
		$data['informations'] = $info_list;
		
		//[2012/06/29]ログイン履歴の取得
		//[2012/08/29]SuperUserのログイン履歴を表示しないように修正
		$this->load->model('model_applog');
		$select_school_id = $this->libauth->get_school_id();
		$login_teacher_id = $this->libauth->get_teacher_id();
		$log_list = $this->model_applog->get_login_log($select_school_id, $login_teacher_id); 
		$data['login_logs'] = $log_list;

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'admin_top/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}

	//----------------------------------------------
	//初期表示
	//----------------------------------------------
	function classes(){
		//表示用変数の初期化
		$data = array();
		
		// 生授業契約がない場合、トップページへリダイレクト
		$user_auths = $this->session->userdata;
		$contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]);
		if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ){
		}else{
			redirect('admin_top');
		}
		
//		//授業モデル読み込み
//		$this->load->model('model_class');
//		//データ取得用引数設定
//		$data_param = array(
//						'school_id'  => $this->libauth->get_school_id(),
//						'teacher_id' => $this->libauth->get_teacher_id(),
//					);
//		//データ取得
//		$class_list = $this->model_class->get_class_list_toppage($data_param);
//		$data['class_list'] = $class_list;
//		
//		//ビュー設定引数設定
//		$disp_param = array(
//						'view_name'   => 'admin_top/classes',
//						'submenu_idx' => 1,
//						'view_data'   => $data,
//					);
//		//ビュー設定
//		$this->_display_view($disp_param);

		$query = $this->db->query(
			' SELECT * FROM class'.
			' LEFT JOIN cource ON class.cource_id = cource.cource_id AND cource.status = 0'.
			' LEFT JOIN teacher ON class.teacher_id = teacher.teacher_id AND teacher.status = 0'.
			' WHERE class.school_id = ? '.
			($this->libauth->get_teacher_id() != -1 ? ' AND class.teacher_id = '.$this->db->escape($this->libauth->get_teacher_id()) : '').
			' AND class.class_close > ?'.
			' AND class.status <> 9'.
			' ORDER BY class.class_open ASC'.
			' LIMIT 0, 10',
			array(
				$this->libauth->get_school_id(),
				date("Y/m/d H:i:s"),
			)
		);

		$classes = array();
		foreach($query->result_array() as $row){
			array_push($classes, $row);
		}

		$this->load->view('admin_top/classes', array(
			'classes'	=> $classes,
		));
	}

	//----------------------------------------------
	//写真登録フォーム表示
	// [2012/10/23]講師が学校管理者権限が無い場合はトップページにリダイレクト
	//----------------------------------------------
	function school_photo(){

		//権限が無い場合はトップページにリダイレクト
		$work_auth = $this->libauth->get_teacher_auth();
		if($work_auth['school_admin'] == 0){
			redirect('admin_top');
		}

		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$this->load->view('admin_top/photo_upload', array(
				'error'	=> 0,
			));
		}
		else{
			//[2012/08/02](ALF Conference)ファイル名に「カンファレンス」を入れるかの判断
			$conference_name = "";
			$service_env = getenv('URL_SERVICE');
			if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
				$conference_name = "_".$service_env;
			}
			
			if($this->input->post('change_type') == 'change'){
				$this->load->library('upload', array(
					'allowed_types'	=> 'png',
					'upload_path'	=> $this->config->item('login_image_dir'),
					'file_name'		=> "background{$conference_name}_{$this->libauth->get_school_id()}.png",
					'overwrite'		=> true,
				));
				if(!$this->upload->do_upload('upload_file')){
					$this->load->view('admin_top/photo_upload', array(
						'error'	=> 'upload_data_error',
					));
					return;
				}
			}
			else if($this->input->post('change_type') == 'default'){
				if(file_exists($this->config->item('login_image_dir').'/'."background{$conference_name}_{$this->libauth->get_school_id()}.png")){
					if(!unlink($this->config->item('login_image_dir').'/'."background{$conference_name}_{$this->libauth->get_school_id()}.png")){
						$this->load->library('upload');
						$this->load->view('admin_top/photo_upload', array(
							'error'	=> 'file_remvoe_error',
						));
						return;
					}
				}
			}
			else{
				$this->load->library('upload');
				$this->load->view('admin_top/photo_upload', array(
					'error'	=> 'change_type_error',
				));
				return;
			}

			// [2012/11/13]管理画面-ログイン画像更新
			$this->_cms_insert_log('School Photo', 'OK', '管理画面-ログイン画像更新');

			$this->load->view('admin_top/photo_upload_result', array(
			));
		}
	}

	//----------------------------------------------
	// 受講者画面メニュー画像登録フォーム表示[2012/07/31]
	// [2012/10/23]講師が学校管理者権限が無い場合はトップページにリダイレクト
	// [2012/11/01]テストボタン追加
	//----------------------------------------------
	function school_menu(){
		
		//権限が無い場合はトップページにリダイレクト
		$work_auth = $this->libauth->get_teacher_auth();
		if($work_auth['school_admin'] == 0){
			redirect('admin_top');
		}

		// 学校管理にて契約していないメニューを表示しないように値を検索
		$data = array();
		$data['menuFlag']['live']    = 1;
		$data['menuFlag']['video']   = 1;
		$data['menuFlag']['library'] = 1;
		$data['menuFlag']['test']    = 1;
		$data['menuFlag']['issue']    = 1;
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
			$data['menuFlag']['live'] = 0;
		}
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'video'))){
			$data['menuFlag']['video'] = 0;
		}
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))){
			$data['menuFlag']['library'] = 0;
		}
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'test'))){
			$data['menuFlag']['test'] = 0;
		}
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'issue'))){
			$data['menuFlag']['issue'] = 0;
		}
		
		// 初期表示及び更新処理
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$this->load->view('admin_top/menu_upload', array(
				'menuFlag'	=> $data['menuFlag'],
				'error'	=> 0,
			));
		}
		else{
			//(ALF Conference)ファイル名に「カンファレンス」を入れるかの判断
			$conference_name = "";
			$service_env = getenv('URL_SERVICE');
			if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
				$conference_name = "_".$service_env;
			}
			
			// 各画像の更新処理
			$updateData = '';
			$updateData['updateFlag']['live']    = '';
			$updateData['updateFlag']['video']   = '';
			$updateData['updateFlag']['library'] = '';
			$updateData['updateFlag']['mypage']  = '';
			$updateData['updateFlag']['test']    = '';
			$updateData['updateFlag']['issue']   = '';
			$updateData['updateFlag']['default'] = '';
			
			$upload_config = array();
			
			// 生授業(live) ----------------------------------------------------------------------------------
			if( $this->input->post('change_type_live') ){
				if($this->input->post('change_type_live') == 'change'){
					$updateData['updateFlag']['live'] = 'success update';
					
					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_live{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);
					
					if(!$this->upload->do_upload('upload_file_live')){
						$updateData['updateFlag']['live'] = 'failure update';
					}
				}else if($this->input->post('change_type_live') == 'default'){
					$updateData['updateFlag']['live'] = 'success default';
					if(file_exists($this->config->item('menu_image_dir').'/'."btn_live{$conference_name}_{$this->libauth->get_school_id()}.png")){
						if(!unlink($this->config->item('menu_image_dir').'/'."btn_live{$conference_name}_{$this->libauth->get_school_id()}.png")){
							$updateData['updateFlag']['live'] = 'failure default';
						}
					}
				}else{
					$updateData['updateFlag']['live'] = 'no action';
				}
			}else{
				$updateData['updateFlag']['live'] = 'no contract';
			}
			
			// ビデオ授業(video) -----------------------------------------------------------------------------
			if( $this->input->post('change_type_video') ){
				if($this->input->post('change_type_video') == 'change'){
					$updateData['updateFlag']['video'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_video{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

					if(!$this->upload->do_upload('upload_file_video')){
						$updateData['updateFlag']['video'] = 'failure update';
					}
				}else if($this->input->post('change_type_video') == 'default'){
					$updateData['updateFlag']['video'] = 'success default';
					if(file_exists($this->config->item('menu_image_dir').'/'."btn_video{$conference_name}_{$this->libauth->get_school_id()}.png")){
						if(!unlink($this->config->item('menu_image_dir').'/'."btn_video{$conference_name}_{$this->libauth->get_school_id()}.png")){
							$updateData['updateFlag']['video'] = 'failure default';
						}
					}
				}else{
					$updateData['updateFlag']['video'] = 'no action';
				}
			}else{
				$updateData['updateFlag']['video'] = 'no contract';
			}
			
			// 図書室(library) -------------------------------------------------------------------------------
			if( $this->input->post('change_type_library') ){
				if($this->input->post('change_type_library') == 'change'){
					$updateData['updateFlag']['library'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_library{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

				//	$this->load->library('upload', array(
				//		'allowed_types'	=> 'png',
				//		'upload_path'	=> $this->config->item('menu_image_dir'),
				//		'file_name'		=> "btn_library_{$this->libauth->get_school_id()}.png",
				//		'overwrite'		=> true,
				//	));
					if(!$this->upload->do_upload('upload_file_library')){
						$updateData['updateFlag']['library'] = 'failure update';
					}
				}else if($this->input->post('change_type_library') == 'default'){
					$updateData['updateFlag']['library'] = 'success default';
					if(file_exists($this->config->item('menu_image_dir').'/'."btn_library{$conference_name}_{$this->libauth->get_school_id()}.png")){
						if(!unlink($this->config->item('menu_image_dir').'/'."btn_library{$conference_name}_{$this->libauth->get_school_id()}.png")){
							$updateData['updateFlag']['library'] = 'failure default';
						}
					}
				}else{
					$updateData['updateFlag']['library'] = 'no action';
				}
			}else{
				$updateData['updateFlag']['library'] = 'no contract';
			}
			
			// マイページ(mypage) ----------------------------------------------------------------------------
			if($this->input->post('change_type_mypage') == 'change'){
				$updateData['updateFlag']['mypage'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_mypage{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

				if(!$this->upload->do_upload('upload_file_mypage')){
					$updateData['updateFlag']['mypage'] = 'failure update';
				}
			}else if($this->input->post('change_type_mypage') == 'default'){
				$updateData['updateFlag']['mypage'] = 'success default';
				if(file_exists($this->config->item('menu_image_dir').'/'."btn_mypage{$conference_name}_{$this->libauth->get_school_id()}.png")){
					if(!unlink($this->config->item('menu_image_dir').'/'."btn_mypage{$conference_name}_{$this->libauth->get_school_id()}.png")){
						$updateData['updateFlag']['mypage'] = 'failure default';
					}
				}
			}else{
				$updateData['updateFlag']['mypage'] = 'no action';
			}
			
			// テスト(test) ----------------------------------------------------------------------------------
			if( $this->input->post('change_type_test') ){
				if($this->input->post('change_type_test') == 'change'){
					$updateData['updateFlag']['test'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_test{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

					if(!$this->upload->do_upload('upload_file_test')){
						$updateData['updateFlag']['test'] = 'failure update';
					}
				}else if($this->input->post('change_type_test') == 'default'){
					$updateData['updateFlag']['test'] = 'success default';
					if(file_exists($this->config->item('menu_image_dir').'/'."btn_test{$conference_name}_{$this->libauth->get_school_id()}.png")){
						if(!unlink($this->config->item('menu_image_dir').'/'."btn_test{$conference_name}_{$this->libauth->get_school_id()}.png")){
							$updateData['updateFlag']['test'] = 'failure default';
						}
					}
				}else{
					$updateData['updateFlag']['test'] = 'no action';
				}
			}else{
				$updateData['updateFlag']['test'] = 'no contract';
			}
			
			// 課題(issue) ----------------------------------------------------------------------------------
			if( $this->input->post('change_type_issue') ){
				if($this->input->post('change_type_issue') == 'change'){
					$updateData['updateFlag']['issue'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_issue{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

					if(!$this->upload->do_upload('upload_file_issue')){
						$updateData['updateFlag']['issue'] = 'failure update';
					}
				}else if($this->input->post('change_type_issue') == 'default'){
					$updateData['updateFlag']['issue'] = 'success default';
					if(file_exists($this->config->item('menu_image_dir').'/'."btn_issue{$conference_name}_{$this->libauth->get_school_id()}.png")){
						if(!unlink($this->config->item('menu_image_dir').'/'."btn_issue{$conference_name}_{$this->libauth->get_school_id()}.png")){
							$updateData['updateFlag']['issue'] = 'failure default';
						}
					}
				}else{
					$updateData['updateFlag']['issue'] = 'no action';
				}
			}else{
				$updateData['updateFlag']['issue'] = 'no contract';
			}
			
			// デフォルト(default) ---------------------------------------------------------------------------
			if($this->input->post('change_type_default') == 'change'){
				$updateData['updateFlag']['default'] = 'success update';

					$upload_config = array(
						'allowed_types'	=> 'png',
						'upload_path'	=> $this->config->item('menu_image_dir'),
						'file_name'		=> "btn_default{$conference_name}_{$this->libauth->get_school_id()}.png",
						'overwrite'		=> true,
					);
					$this->load->library('upload', $upload_config);
					$this->upload->initialize($upload_config);

			//	$this->load->library('upload', array(
			//		'allowed_types'	=> 'png',
			//		'upload_path'	=> $this->config->item('menu_image_dir'),
			//		'file_name'		=> "btn_default_{$this->libauth->get_school_id()}.png",
			//		'overwrite'		=> true,
			//	));
				if(!$this->upload->do_upload('upload_file_default')){
					$updateData['updateFlag']['default'] = 'failure update';
				}
			}else if($this->input->post('change_type_default') == 'default'){
				$updateData['updateFlag']['default'] = 'success default';
				if(file_exists($this->config->item('menu_image_dir').'/'."btn_default{$conference_name}_{$this->libauth->get_school_id()}.png")){
					if(!unlink($this->config->item('menu_image_dir').'/'."btn_default{$conference_name}_{$this->libauth->get_school_id()}.png")){
						$updateData['updateFlag']['default'] = 'failure default';
					}
				}
			}else{
				$updateData['updateFlag']['default'] = 'no action';
			}
			
			// [2012/11/13]管理画面-メニュー画像更新
			$this->_cms_insert_log('School Menu', 'OK', '管理画面-メニュー画像更新');
			
			// 結果表示
			$this->load->view('admin_top/menu_upload_result', array(
				'updateFlag'	=> $updateData['updateFlag'],
			));
		}
	}
	
	//----------------------------------------------
	//更新履歴表示(2012/01/06)
	//----------------------------------------------
	function update_history(){
		$this->load->view('admin_top/update_history', array(
		));
	}

	//----------------------------------------------
	//サブメニュー作成
	//----------------------------------------------
	function _create_sub_menu($param){
		//引数設定
		$param = array_merge(
						array(
							'submenu_idx' => 0,
						),
						$param
					);
		$sub_menu = array();
		
		switch($param['submenu_idx']){
			case 1://
				$sub_menu[1] = anchor("",'') ;
				break;
				
		}
		return $sub_menu;
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
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		$this->load->view($param['view_name'], $param['view_data']);
		
	}
	
	//----------------------------------------------
	// [2012/10/05]お知らせ（インフォメーション）詳細表示
	//----------------------------------------------
	function info_detail($select_tag = ''){
		// タグ有無の確認
		if($select_tag === ''){
			$select_tag = "";
		}else{
			$select_tag = urldecode($select_tag);
		}
		
		//お知らせモデル読み込み
		$this->load->model('model_information');
		//データ取得用引数設定
		$data_param = array(
						'school_id'		=> $this->libauth->get_school_id(),
						'select_tag'	=> $select_tag,
					);
		//データ取得
		$info_list = $this->model_information->get_information_list_detail($data_param); 

		$this->load->view('admin_top/info_detail', array(
			'informations'	=> $info_list,
			'tags'			=> $this->model_information->get_information_tags($data_param),
			'select_tag'	=> $select_tag,
		));
	}


	//----------------------------------------------
	// [2012/10/23]外部連携（eLearning Manager）設定画面
	// [2012/11/14]Api URL重複チェックを追加
	//----------------------------------------------
	function outside_elearningmanager(){
		
		//権限が無い場合はトップページにリダイレクト
		$work_auth = $this->libauth->get_teacher_auth();
		if($work_auth['school_admin'] == 0){
			redirect('admin_top');
		}
		
		//モデル読み込み
		$this->load->model('model_school_manage');
		//データ取得
		$db_data = $this->model_school_manage->get_school(
			array(
				'school_id' => $this->libauth->get_school_id(),
			)
		);
		
		// 初期表示及び更新処理
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$api_key = "";
			$api_url = "";
			if(!empty($db_data)){
				$this->load->helper('json');
				$contract_param = obj2arr(json_decode($db_data['contract_param']));
				if(isset($contract_param['outside_elearningmanager']['api_key'])){
					$api_key = urldecode($contract_param['outside_elearningmanager']['api_key']);
				}
				if(isset($contract_param['outside_elearningmanager']['api_url'])){
					$api_url = urldecode($contract_param['outside_elearningmanager']['api_url']);
				}
			//	$contract_param = json_encode($contract_param);
			}
			
			$this->load->view('admin_top/outside_elearningmanager', array(
				'api_key'	=> $api_key,
				'api_url'	=> $api_url,
				'error'		=> 0,
				'error_msg'	=> '',
			));
		}
		else{
			// 入力値チェック
			
			// URL入力時、URLチェックを行う。
			$check_url = $this->input->post('api_url');
			if(!empty($check_url)){
				if(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $check_url) != 1) {
					$this->load->view('admin_top/outside_elearningmanager', array(
						'api_key'	=> $this->input->post('api_key'),
						'api_url'	=> $this->input->post('api_url'),
						'error'		=> 1,
					));
					return;
				}
			}
			
			// [eLM]APIの整合性確認
			$this->load->model('model_outside_gingerapp');
			$request['api_key'] = $this->input->post('api_key');
			$request['api_url'] = $this->input->post('api_url');
			
			$elm_result = $this->model_outside_gingerapp->auth_check($request);
			
			if($elm_result['stat'] != 200){
				$this->load->view('admin_top/outside_elearningmanager', array(
					'api_key'	=> $this->input->post('api_key'),
					'api_url'	=> $this->input->post('api_url'),
					'error'		=> 2,
					'error_msg'	=> $elm_result['message'].'(code:'.$elm_result['stat'].')',
				));
				return;
			}

			// [2012/11/14]API URL重複チェック
			$this->load->model('model_school_manage');
			$request_url['school_id'] = $this->libauth->get_school_id();
			$request_url['api_url']   = $this->input->post('api_url');
			
			if( $this->model_school_manage->check_outside_content_param($request_url) ){
				$this->load->view('admin_top/outside_elearningmanager', array(
					'api_key'	=> $this->input->post('api_key'),
					'api_url'	=> $this->input->post('api_url'),
					'error'		=> 3,
				));
				return;
			}
			
			// 契約内容（contract_param）の編集
			$this->load->helper('json');
			$contract_param = obj2arr(json_decode($db_data['contract_param']));
			
			// ※Super User 権限で入力する場合、contractがない場合があるための対応
			if(!isset($contract_param['outside_elearningmanager']['contract'])){
				$contract_param['outside_elearningmanager']['contract'] = 'fixation';
			}
			
			$contract_param['outside_elearningmanager']['api_key'] = urlencode($this->input->post('api_key'));
			$contract_param['outside_elearningmanager']['api_url'] = urlencode($this->input->post('api_url'));
			$contract_param = json_encode($contract_param);

			//モデル読み込み
		//	$this->load->model('model_school_manage');
			
			//データ更新用引数設定
			$data_param = array(
							'school_id'			=> $this->libauth->get_school_id(),
							'contract_param'	=> $contract_param,
						);
			//データ更新
			$data = $this->model_school_manage->update_school_contract_param($data_param);

			//セッションに選択学校IDを保存
			$this->libauth->set_school_id($this->libauth->get_school_id());
			

			// [2012/11/13]管理画面-管理画面-外部連携(eLM)更新
			$this->_cms_insert_log('Outside eLM', 'OK', '管理画面-外部連携(eLM)更新');

			// 結果表示
			$this->load->view('admin_top/outside_elearningmanager_result', array(
			));
		}
	}

	//----------------------------------------------
	// [2012/11/13]アプリログ出力
	// ・管理画面：ログイン画像、メニュー画像、外部連携(eLM)
	//----------------------------------------------
	function _cms_insert_log($log_name, $log_value, $log_option){
		
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
