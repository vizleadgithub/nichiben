<?php
#[AllowDynamicProperties]
class Cms_auth extends CI_Controller {
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
			if($work_auth['auth'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
	}
	
	//----------------------------------------------
	//一覧表示
	// [2012/10/12]ページ移動時の検索条件が維持できるよう修正
	//----------------------------------------------
	function index($offset=0){
		// load language
		$this->lang->load('common');
		
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');
		
		//検証ルールの設定
		$this->form_validation->set_rules('s_name'      , $this->lang->line_or_def('common_name','名前')                   , 'trim|xss_clean');
		$this->form_validation->set_rules('s_email'     , $this->lang->line_or_def('common_mail_address','メールアドレス') , 'trim|xss_clean');
		$this->form_validation->set_rules('s_id'        , $this->lang->line_or_def('common_id','ID')                       , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード')       , 'trim|xss_clean');
		$this->form_validation->run();	//バリデーション実行（その実xss対策）
		
		//講師モデル読み込み
		$this->load->model('model_teacher');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('auth_teacher_search_cond') ?: array(
				's_name' => '',
				's_email'=>'',
				's_id' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_name']      = ($this->input->post('s_name') ?? '');
			$data['s_email']     = ($this->input->post('s_email') ?? '');
			$data['s_id']        = ($this->input->post('s_id') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('auth_teacher_search_cond', $data);
		}

		//データ取得
		$data_param = array(
			's_school_id' => $this->libauth->get_school_id(),
			's_name'      => $data['s_name'],
			's_email'     => $data['s_email'],
			's_id'        => $data['s_id'],
			's_free_word' => $data['s_free_word'],
			'offset'      => $offset,
			'rowcount'    => $per_page,
		);
		$teacher_list = $this->model_teacher->get_teacher_search_list($data_param);
		//権限名編集
		foreach($teacher_list as $i => $teacher_list_row){
			//権限
			$work_auth = $this->libauth->get_parse_auth_param($teacher_list[$i]['teacher_auth']);
			$auth['a_cource_class']  = $work_auth['course_class'];
			$auth['a_student']       = $work_auth['student'];
			$auth['a_teacher']       = $work_auth['teacher'];
			$auth['a_material']      = $work_auth['material'];
			$auth['a_information']   = $work_auth['information'];
			$auth['a_book_library']  = $work_auth['book_library'];
			$auth['a_video']         = $work_auth['video'];
			$auth['a_issue']         = $work_auth['issue'];
			
			$auth['a_school_admin']  = $work_auth['school_admin'];
			
			$teacher_list[$i]['auth_names'] = $this->_create_auth_names($work_auth);
		}
		$data['teacher_list'] = $teacher_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_auth/index';
		$config['total_rows'] = $this->model_teacher->get_teacher_search_count($data_param);
		$config['per_page']   = $per_page;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link']  = '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		// [2012/10/12]
		if(count($_GET)){
			$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1?'.http_build_query($_GET).'$2', $data['pagination']);
		}
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_auth/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//修正フォーム表示
	//----------------------------------------------
	function edit($teacher_id = 0){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_teacher');
		
		//データ取得用引数設定
		$data_param = array(
						'teacher_id' => $teacher_id,
					);
		//データ取得
		$db_data = $this->model_teacher->get_teacher($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['teacher']['update_flg']               = 1;
			$data['teacher']['teacher_id']               = $db_data['teacher_id'];
			$data['teacher']['teacher_name']             = $db_data['teacher_name'];
			$data['teacher']['teacher_email']            = $db_data['teacher_email'];
			$data['teacher']['teacher_auth']             = $db_data['teacher_auth'];
			
			//権限
			$work_auth = $this->libauth->get_parse_auth_param($data['teacher']['teacher_auth']);
			$data['teacher']['a_cource_class']  = $work_auth['course_class'];
			$data['teacher']['a_student']       = $work_auth['student'];
			$data['teacher']['a_teacher']       = $work_auth['teacher'];
			$data['teacher']['a_material']      = $work_auth['material'];
			$data['teacher']['a_information']   = $work_auth['information'];
			$data['teacher']['a_book_library']  = $work_auth['book_library'];
			$data['teacher']['a_video']         = $work_auth['video'];
			$data['teacher']['a_issue']         = $work_auth['issue'];
			
			$data['teacher']['a_school_admin']  = $work_auth['school_admin'];
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_auth/edit',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//編集フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_auth/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'     , $this->lang->line_or_def('common_flg','flg')                     , 'trim|numeric');
		$this->form_validation->set_rules('teacher_id'     , $this->lang->line_or_def('common_id','ID')                       , 'trim|numeric');
		$this->form_validation->set_rules('teacher_name'   , $this->lang->line_or_def('common_name','名前')                   , 'trim|required');
		$this->form_validation->set_rules('teacher_email'  , $this->lang->line_or_def('common_mail_address','メールアドレス') , 'trim|required|valid_email');
		$this->form_validation->set_rules('teacher_auth'   , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_cource_class' , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_student'      , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_teacher'      , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_material'     , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_information'  , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_book_library' , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_video'        , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		$this->form_validation->set_rules('a_issue'        , $this->lang->line_or_def('common_practice_authority','実行権限') , 'trim');
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['teacher']['update_flg']     = $this->input->post('update_flg');
			$data['teacher']['teacher_id']     = $this->input->post('teacher_id');
			$data['teacher']['teacher_name']   = '';
			$data['teacher']['teacher_email']  = '';
			$data['teacher']['teacher_auth']   = '';
			$data['teacher']['a_cource_class'] = '';
			$data['teacher']['a_student']      = '';
			$data['teacher']['a_teacher']      = '';
			$data['teacher']['a_material']     = '';
			$data['teacher']['a_information']  = '';
			$data['teacher']['a_book_library'] = '';
			$data['teacher']['a_video']        = '';
			$data['teacher']['a_issue']        = '';
			$data['teacher']['a_school_admin'] = 0;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_auth/edit',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['teacher']['update_flg']               = $this->input->post('update_flg');
			$data['teacher']['teacher_id']               = $this->input->post('teacher_id');
			$data['teacher']['teacher_name']             = $this->input->post('teacher_name');
			$data['teacher']['teacher_email']            = $this->input->post('teacher_email');
			$data['teacher']['teacher_auth']             = $this->input->post('teacher_auth');
			$data['teacher']['a_cource_class']           = $this->input->post('a_cource_class');
			$data['teacher']['a_student']                = $this->input->post('a_student');
			$data['teacher']['a_teacher']                = $this->input->post('a_teacher');
			$data['teacher']['a_material']               = $this->input->post('a_material');
			$data['teacher']['a_information']            = $this->input->post('a_information');
			$data['teacher']['a_book_library']           = $this->input->post('a_book_library');
			$data['teacher']['a_video']                  = $this->input->post('a_video');
			$data['teacher']['a_issue']                  = $this->input->post('a_issue');
			$data['teacher']['a_school_admin']           = $this->input->post('a_school_admin');

			//学校管理者の場合、各権限を固定化
			$a_report = 0;
			$a_auth   = 0;
			if($data['teacher']['a_school_admin'] == 1){ 
				$data['teacher']['a_cource_class'] = 1;
				$data['teacher']['a_student']      = 1;
				$data['teacher']['a_teacher']      = 1;
				$data['teacher']['a_material']     = 1;
				$data['teacher']['a_book_library'] = 1;
				$data['teacher']['a_video']        = 1;
				$data['teacher']['a_issue']        = 1;
				$data['teacher']['a_information']  = 1;
				$a_report = 1;
				$a_auth   = 1;
			}

			//権限設定
			$temp_teacher_auth = array_merge(
				$this->libauth->defaultTeacherAuth,
				array(
					'admin_top'     => 1,
					'school_select' => 0,
					'course_class'  => $data['teacher']['a_cource_class'] ==1 ? 1: 0,
					'student'       => $data['teacher']['a_student']      ==1 ? 1: 0,
					'teacher'       => $data['teacher']['a_teacher']      ==1 ? 1: 0,
					'material'      => $data['teacher']['a_material']     ==1 ? 1: 0,
					'book_library'  => $data['teacher']['a_book_library'] ==1 ? 1: 0,
					'video'         => $data['teacher']['a_video']        ==1 ? 1: 0,
					'issue'         => $data['teacher']['a_issue']        ==1 ? 1: 0,
					'information'   => $data['teacher']['a_information']  ==1 ? 1: 0,
					'report'        => $a_report ==1 ? 1: 0,
					'auth'          => $a_auth   ==1 ? 1: 0,
					'school_admin'  => $data['teacher']['a_school_admin'] ==1 ? 1: 0,
				)
			);
			$data['teacher']['teacher_auth'] = serialize($temp_teacher_auth);
			
			$data['teacher']['authnames'] = $this->_create_auth_names($temp_teacher_auth);
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['teacher']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_auth/confirm',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	// [2012/10/01]更新時、ログインセッションの更新処理を追加
	//----------------------------------------------
	function commit(){
		
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['teacher_id']) && $edit_form_data['teacher_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			
			//モデル読み込み
			$this->load->model('model_teacher');
			
			//データ更新用引数設定
			$data_param = array(
							'data' => $edit_form_data,
						);
			//データ更新
			$data = $this->model_teacher->update_teacher_auth($data_param);

			// [2012/10/01]セッションの更新
			// 自分自身のアカウントを編集した場合のみセッションを更新する
			// （他の管理者を編集した場合に呼ぶと、編集者のセッションが上書きされてしまうため）
			if ((int)$edit_form_data['teacher_id'] === (int)$this->libauth->get_teacher_id()) {
				$result_data = $this->libauth->update_login_session($this->libauth->get_teacher_id());
			}

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_auth/commit',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
			

			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_auth');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 2,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
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
		//メニュー表示用配列初期化
		$sub_menu = array();
		
		switch($param['submenu_idx']){
			case 1://検索
				$sub_menu[1] = "検索";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_teacher", "検索");
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

		//メインメニュー生成
		//$param['view_data']['main_menu'] = $this->_create_main_menu();
		
		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}
	
	//----------------------------------------------
	//権限一覧作成
	// [2012/09/27]学校の契約がない権限を表示しないように修正。
	//----------------------------------------------
	function _create_auth_names($param) {

		$this->load->model('Modelschoolcontract');	// [2012/09/27]

		$work = array();
		foreach($param as $key => $value){
			if($key == 'school_admin' && $value == 1){
				$work = array();
				array_push($work, $this->libauth->get_teacherAuthName($key));
				array_push($work, '　');
				array_push($work, '　');
				break;
			}
			if($key == 'admin_top' || $key == 'school_select' || $key == 'course'){
				continue;
			}
			if(!$value){
				continue;
			}

			if($key == 'course_class' || $key == 'material'){
				if($this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
					array_push($work, $this->libauth->get_teacherAuthName($key));
				}
				continue;
			}
			if($key == 'video'){
				if($this->Modelschoolcontract->enableService(array('serviceKey'=>'video'))){
					array_push($work, $this->libauth->get_teacherAuthName($key));
				}
				continue;
			}
			if($key == 'book_library'){
				if($this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))){
					array_push($work, $this->libauth->get_teacherAuthName($key));
				}
				continue;
			}
			if($key == 'issue'){
				if($this->Modelschoolcontract->enableService(array('serviceKey'=>'issue'))){
					array_push($work, $this->libauth->get_teacherAuthName($key));
				}
				continue;
			}

			if($this->libauth->get_teacherAuthName($key)){
				array_push($work, $this->libauth->get_teacherAuthName($key));
			}
		//	if(isset($this->libauth->teacherAuthName[$key]) && $this->libauth->teacherAuthName[$key]){
		//		array_push($work, $this->libauth->teacherAuthName[$key]);
		//	}
		}

		return $work;
	}
	
} 

/*End of File program.php*/
