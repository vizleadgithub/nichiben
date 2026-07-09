<?php
#[AllowDynamicProperties]
class Cms_teacher extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
//	private $photo_dir             = '/master/thumbnail/'; //写真アップロードディレクトリ
	private $upload_types          = 'jpg|jpeg|gif|bmp|png'; //アップロード可能ファイルタイプ
	private $max_uplolad_pixel     = array( //アップロード可能最大画像サイズ
		'width'  => 800,
		'height' => 600,
	);
	private $rec_upload_pixel      = array( //推奨画像サイズ
		'width'  => 120,
		'height' => 120,
	);
//	private $upload_error_messages = array( //アップロードエラー一覧
//		1=> 'ファイルサイズが大きすぎます', 
//			'ファイルサイズが大きすぎます', 
//			'ファイルが途中までしかアップロードされていません', 
//			'ファイルを指定してください', 
//		6=> 'テンポラリフォルダがありません', 
//			'書き込みに失敗しました', 
//			'内部エラーのため、アップロードを中止しました'
//	);
	
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
			if($work_auth['teacher'] == 0){
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
		
		$this->session->set_userdata('offset', $offset);	//後で戻ってくる時に使う

		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');
		
		//検証ルールの設定
		$this->form_validation->set_rules('s_name'      , $this->lang->line_or_def('common_name','名前')                   , 'trim');
		$this->form_validation->set_rules('s_email'     , $this->lang->line_or_def('common_mail_address','メールアドレス') , 'trim');
		$this->form_validation->set_rules('s_id'        , $this->lang->line_or_def('common_id','ID')                       , 'trim');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード')       , 'trim');
		$this->form_validation->run();	//バリデーション実行（その実xss対策）
		
		//講師モデル読み込み
		$this->load->model('model_teacher');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('teacher_search_cond') ?: array(
				's_name' => '',
				's_email'=>'',
				's_id' => '',
				's_email' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_name']      = ($this->input->post('s_name') ?? '');
			$data['s_email']     = ($this->input->post('s_email') ?? '');
			$data['s_id']        = ($this->input->post('s_id') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('teacher_search_cond', $data);
		}

		// 初期表示時のリスト非表示対応
		$first_show_id = $data['s_id'];
		if( ($data['s_name']===FALSE) && ($data['s_email']===FALSE) && ($data['s_id']===FALSE) && ($data['s_free_word']===FALSE) ){
			$first_show_id = -1;
		}

		$data_param = array(
			's_school_id' => $this->libauth->get_school_id(),
			's_name'      => $data['s_name'],
			's_email'     => $data['s_email'],
			's_id'        => $first_show_id,
			's_free_word' => $data['s_free_word'],
			'offset'      => $offset,
			'rowcount'    => $per_page,
		);

		//データ取得
		$teacher_list = $this->model_teacher->get_teacher_search_list($data_param);

		//アカウントロックの確認
		$lock_time_sec = $this->config->item('lock_time_sec');
		$lock_login_count = $this->config->item('lock_login_count');
		foreach($teacher_list as $index=>$row){
			$teacher_list[$index]["lock"] = 0;
			$sql= "SELECT * FROM cms_login_fail WHERE 1=1 AND teacher_email=? AND login_fail_count>=".$lock_login_count." AND last_fail_time>='".date("Y-m-d H:i:s", strtotime("-".$lock_time_sec." seconds"))."' ";
			$query = $this->db->query($sql, [
				$teacher_list[$index]["teacher_email"],
			]);
			if ($query->num_rows() > 0) {
				$teacher_list[$index]["lock"] = 1;
			}
		}

		$data['teacher_list'] = $teacher_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_teacher/index';
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
						'view_name'   => 'cms_teacher/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/09/10]teacher_password_change・teacher_password_identity追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['teacher']['update_flg']                = 0;
		$data['teacher']['teacher_id']                = 0;
		$data['teacher']['teacher_name']              = '';
		$data['teacher']['teacher_email']             = '';
		$data['teacher']['teacher_password']          = '';
		$data['teacher']['teacher_password_check']    = '';
		$data['teacher']['teacher_password_change']   = 1;
		$data['teacher']['teacher_password_identity'] = 0;
		$data['teacher']['teacher_auth']              = '';
		$data['teacher']['teacher_introduce']         = '';
		$data['teacher']['teacher_introduce_detail']  = '';
		$data['teacher']['teacher_note']              = '';
		$data['teacher']['bar_association_id']        = $this->libauth->get_bar_association_id();
		$data['teacher']['a_cource_class']            = 1;
		$data['teacher']['a_student']                 = 0;
		$data['teacher']['a_teacher']                 = 0;
		$data['teacher']['a_material']                = 1;
		$data['teacher']['a_book_library']            = 0;
		$data['teacher']['a_video']                   = 0;
		$data['teacher']['a_issue']                   = 0;
		$data['teacher']['a_information']             = 0;
		$data['teacher']['a_school_admin']            = 1;	// 法学館対応、講師新規作成時は管理者をデフォルトにする
		$data['teacher']['a_report']                  = 1;
		$data['teacher']['a_auth']                    = 0;
		
		//実行権限が「変更できません」の場合に表示する文字列
		$temp_teacher_auth = array_merge(
			$this->libauth->defaultTeacherAuth,
			array(
				'admin_top'     => 1,
				'school_select' => 0,
				'course'        => 1,
				'course_class'  => $data['teacher']['a_cource_class'] ==1 ? 1: 0,
				'student'       => $data['teacher']['a_student']      ==1 ? 1: 0,
				'teacher'       => $data['teacher']['a_teacher']      ==1 ? 1: 0,
				'material'      => $data['teacher']['a_material']     ==1 ? 1: 0,
				'book_library'  => $data['teacher']['a_book_library'] ==1 ? 1: 0,
				'video'         => $data['teacher']['a_video']        ==1 ? 1: 0,
				'issue'         => $data['teacher']['a_issue']        ==1 ? 1: 0,
				'information'   => $data['teacher']['a_information']  ==1 ? 1: 0,
				'report'        => $data['teacher']['a_report']       ==1 ? 1: 0,
				'auth'          => $data['teacher']['a_auth']         ==1 ? 1: 0,
				'school_admin'  => $data['teacher']['a_school_admin'] ==1 ? 1: 0,
			)
		);
		$data['teacher']['authname'] = $this->_create_auth_name($temp_teacher_auth);
		
		//セッションへDB取得データを書き込み
		$this->session->set_userdata('edit_form_data',serialize($data['teacher']));
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_teacher/edit',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//修正フォーム表示
	//----------------------------------------------
	function edit(){
		if( $this->session->userdata('edit_form_data')==null && empty($this->session->userdata('edit_form_data')) ){
			redirect('/cms_teacher/');
		}

		// load language
		$this->lang->load('error');

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		//アカウントロックの確認
		$lock_time_sec = $this->config->item('lock_time_sec');
		$lock_login_count = $this->config->item('lock_login_count');
		$edit_form_data["lock"] = 0;
		$sql= "SELECT * FROM cms_login_fail WHERE 1=1 AND teacher_email=? AND login_fail_count>=".$lock_login_count." AND last_fail_time>='".date("Y-m-d H:i:s", strtotime("-".$lock_time_sec." seconds"))."' ";
		$query = $this->db->query($sql, [
			$edit_form_data['teacher_email'],
		]);
		if ($query->num_rows() > 0) {
			$edit_form_data["lock"] = 1;
		}

		//講師管理の権限有無確認
		$auth_teacher = $this->_get_auth_class($edit_form_data['teacher_id']);

		// 権限を持たない場合、エラーを返す
		if($auth_teacher == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_teacher');
			$data['error_message'] = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'teacher_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			if(isset($edit_form_data['teacher_id']) &&  $edit_form_data['teacher_id'] <> ''){
				//画面表示用データ設定
				$data['teacher'] = $edit_form_data;

				//実行権限が「変更できません」の場合に表示する文字列
				$temp_teacher_auth = array_merge(
					$this->libauth->defaultTeacherAuth,
					array(
						'admin_top'     => 1,
						'school_select' => 0,
						'course'        => 1,
						'course_class'  => $data['teacher']['a_cource_class'] ==1 ? 1: 0,
						'student'       => $data['teacher']['a_student']      ==1 ? 1: 0,
						'teacher'       => $data['teacher']['a_teacher']      ==1 ? 1: 0,
						'material'      => $data['teacher']['a_material']     ==1 ? 1: 0,
						'book_library'  => $data['teacher']['a_book_library'] ==1 ? 1: 0,
						'video'         => $data['teacher']['a_video']        ==1 ? 1: 0,
						'issue'         => $data['teacher']['a_issue']        ==1 ? 1: 0,
						'information'   => $data['teacher']['a_information']  ==1 ? 1: 0,
						'report'        => $data['teacher']['a_report']       ==1 ? 1: 0,
						'auth'          => $data['teacher']['a_auth']         ==1 ? 1: 0,
						'school_admin'  => $data['teacher']['a_school_admin'] ==1 ? 1: 0,
					)
				);
				$data['teacher']['authname'] = $this->_create_auth_name($temp_teacher_auth);

				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_teacher/edit',
								'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
				
			}else{
				//セッションデータ無しはエラーフォーム表示
				//戻り先設定
				$data['returnurl'] = site_url('teacher');
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'session_error',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}

		}
		
	}
	
	//----------------------------------------------
	//詳細表示フォーム表示
	// [2012/09/10]teacher_password_change・teacher_password_identity追加
	//----------------------------------------------
	function detail($teacher_id){
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
		
		if( !empty($db_data) ){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['teacher']['update_flg']                = 1;
			$data['teacher']['teacher_id']                = $db_data['teacher_id'];
			$data['teacher']['teacher_name']              = $db_data['teacher_name'];
			$data['teacher']['teacher_email']             = $db_data['teacher_email'];
			$data['teacher']['teacher_password']          = '';  //$db_data['teacher_password'];
			$data['teacher']['teacher_password_check']    = '';  //$db_data['teacher_password'];
			$data['teacher']['teacher_password_change']   = 0;
			$data['teacher']['teacher_password_identity'] = 0;
			$data['teacher']['teacher_password_old']      = '';  //$db_data['teacher_password_old'];
			$data['teacher']['teacher_auth']              = unserialize($db_data['teacher_auth']);
			$data['teacher']['teacher_introduce']         = $db_data['teacher_introduce'];
			$data['teacher']['teacher_introduce_detail']  = $db_data['teacher_introduce_detail'];
			$data['teacher']['teacher_note']              = $db_data['teacher_note'];
			
			//権限設定
			$temp_teacher_auth = $data['teacher']['teacher_auth'];
			//									= $temp_teacher_auth['admin_top'];
			//									= $temp_teacher_auth['school_select'];
			//									= $temp_teacher_auth['course'];
			$data['teacher']['a_cource_class']  = $temp_teacher_auth['course_class'] ?? 0;
			$data['teacher']['a_student']       = $temp_teacher_auth['student'] ?? 0;
			$data['teacher']['a_teacher']       = $temp_teacher_auth['teacher'] ?? 0;
			$data['teacher']['a_material']      = $temp_teacher_auth['material'] ?? 0;
			$data['teacher']['a_information']   = $temp_teacher_auth['information'] ?? 0;
			//									= $temp_teacher_auth['auth'];
			$data['teacher']['a_book_library']  = $temp_teacher_auth['book_library'] ?? 0;
			$data['teacher']['a_video']         = $temp_teacher_auth['video'] ?? 0;

			$data['teacher']['a_report']        = $temp_teacher_auth['report'] ?? 0;
			$data['teacher']['a_auth']          = $temp_teacher_auth['auth'] ?? 0;

			if(isset($temp_teacher_auth['issue'])){
				$data['teacher']['a_issue']  = $temp_teacher_auth['issue'] ?? 0;
			}else{
				$data['teacher']['a_issue']  = 0;
			}
		//	$data['teacher']['a_school_admin']  = $temp_teacher_auth['school_admin'];
			if(isset($temp_teacher_auth['school_admin'])){
				$data['teacher']['a_school_admin']  = $temp_teacher_auth['school_admin'] ?? 0;
			}else{
				$data['teacher']['a_school_admin']  = 0;
			}
			
			$data['teacher']['authname'] = $this->_create_auth_name($temp_teacher_auth);
			
			// 日弁連
			if($_SERVER['HTTP_REFERER']){
				//$data['teacher']['history_back_url'] = $_SERVER['HTTP_REFERER'];
				$data['teacher']['history_back_url'] = "/cms_teacher/";
			}else{
				$data['teacher']['history_back_url'] = 'history.back()';
			}
			$data['teacher']['bar_association_id'] = $db_data['bar_association_id'];

			//アカウントロックの確認
			$lock_time_sec = $this->config->item('lock_time_sec');
			$lock_login_count = $this->config->item('lock_login_count');
			$data['teacher']["lock"] = 0;
			$sql= "SELECT * FROM cms_login_fail WHERE 1=1 AND teacher_email=? AND login_fail_count>=".$lock_login_count." AND last_fail_time>='".date("Y-m-d H:i:s", strtotime("-".$lock_time_sec." seconds"))."' ";
			$query = $this->db->query($sql, [
				$data['teacher']['teacher_email'],
			]);
			if ($query->num_rows() > 0) {
				$data['teacher']["lock"] = 1;
			}
		
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['teacher']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_teacher/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_teacher/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/09/10]teacher_password_change・teacher_password_identity追加
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'              , $this->lang->line_or_def('common_flg','flg')                                 , 'trim|numeric');
		$this->form_validation->set_rules('teacher_id'              , $this->lang->line_or_def('common_id','ID')                                   , 'trim|numeric');
		$this->form_validation->set_rules('teacher_name'            , $this->lang->line_or_def('common_name','名前')                               , 'trim|required|callback_name_check');
		$this->form_validation->set_rules('teacher_email'           , $this->lang->line_or_def('common_mail_address','メールアドレス')             , 'trim|required|valid_email');

		if( 
			    $this->input->post('update_flg') != 0
			 && $this->input->post('teacher_password_change') == 1 
		){
			$this->form_validation->set_rules('teacher_password_old'    , $this->lang->line_or_def('common_password_old','現在のパスワード')           , 'trim|required');
			$this->form_validation->set_rules('teacher_password'        , $this->lang->line_or_def('common_password_new','新パスワード')                   , 'trim|required|callback_password_policy');
			$this->form_validation->set_rules('teacher_password_check'  , $this->lang->line_or_def('common_password_new_conf','新パスワード（確認入力）')  , 'trim|required|matches[teacher_password]');
		}
		if( $this->input->post('update_flg') == 0 ){
			$this->form_validation->set_rules('teacher_password'        , $this->lang->line_or_def('common_password','パスワード')                   , 'trim|required|callback_password_policy');
			$this->form_validation->set_rules('teacher_password_check'  , $this->lang->line_or_def('common_password_conf','パスワード（確認入力）')  , 'trim|required|matches[teacher_password]');
		}

		$this->form_validation->set_rules('teacher_auth'            , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('teacher_introduce'       , $this->lang->line_or_def('common_self_introduction_summary','自己紹介 概要') , 'trim');
		$this->form_validation->set_rules('teacher_introduce_detail', $this->lang->line_or_def('common_self_introduction_detail','自己紹介 詳細')  , 'trim');
		$this->form_validation->set_rules('teacher_note'            , $this->lang->line_or_def('common_note','備考')                               , 'trim');
		$this->form_validation->set_rules('a_cource_class'          , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_student'               , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_teacher'               , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_material'              , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_information'           , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_book_library'          , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_video'                 , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_issue'                 , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_report'                , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		$this->form_validation->set_rules('a_auth'                  , $this->lang->line_or_def('common_practice_authority','実行権限')             , 'trim');
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['teacher']['update_flg']                = $this->input->post('update_flg');
			$data['teacher']['teacher_id']                = $this->input->post('teacher_id');
			$data['teacher']['teacher_name']              = '';
			$data['teacher']['teacher_email']             = '';
			$data['teacher']['teacher_password_old']      = '';
			$data['teacher']['teacher_password']          = '';
			$data['teacher']['teacher_password_check']    = '';
			$data['teacher']['teacher_password_change']   = $this->input->post('teacher_password_change');
			$data['teacher']['teacher_password_identity'] = $this->input->post('teacher_password_identity');
			$data['teacher']['teacher_auth']              = '';
			$data['teacher']['teacher_introduce']         = '';
			$data['teacher']['teacher_introduce_detail']  = '';
			$data['teacher']['teacher_note']              = '';
			$data['teacher']['bar_association_id']        = $this->input->post('bar_association_id');
			$data['teacher']['a_cource_class']            = '';
			$data['teacher']['a_student']                 = '';
			$data['teacher']['a_teacher']                 = '';
			$data['teacher']['a_material']                = '';
			$data['teacher']['a_information']             = '';
			$data['teacher']['a_book_library']            = '';
			$data['teacher']['a_video']                   = '';
			$data['teacher']['a_issue']                   = '';
			$data['teacher']['a_report']                  = '';
			$data['teacher']['a_auth']                    = '';
			$data['teacher']['a_school_admin']            = 1;	// 法学館対応、講師新規作成時は管理者をデフォルトにする

			//ビュー設定引数設定
			$disp_param = array(
				'view_name'   => 'cms_teacher/edit',
				'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
				'view_data'   => $data,
			);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//重複チェック
			$query = $this->db->query(
				' SELECT * FROM teacher'.
				' WHERE teacher_email = ? '.
				' AND teacher.school_id = ?'.
				' AND teacher.status = 0'.
				' LIMIT 0, 1',
				array(
					$this->input->post('teacher_email'),
					$this->libauth->get_school_id(),
				)
			);
			if(!$this->input->post('update_flg') && $query->row()){
				$data['teacher']['update_flg']                = $this->input->post('update_flg');
				$data['teacher']['teacher_id']                = $this->input->post('teacher_id');
				$data['teacher']['teacher_name']              = '';
				$data['teacher']['teacher_email']             = '';
				$data['teacher']['teacher_password_old']      = '';
				$data['teacher']['teacher_password']          = '';
				$data['teacher']['teacher_password_check']    = '';
				$data['teacher']['teacher_password_change']   = $this->input->post('teacher_password_change');
				$data['teacher']['teacher_password_identity'] = $this->input->post('teacher_password_identity');
				$data['teacher']['teacher_auth']              = '';
				$data['teacher']['teacher_introduce']         = '';
				$data['teacher']['teacher_introduce_detail']  = '';
				$data['teacher']['teacher_note']              = '';
				$data['teacher']['bar_association_id']        = $this->input->post('bar_association_id');
				$data['teacher']['a_cource_class']            = '';
				$data['teacher']['a_student']                 = '';
				$data['teacher']['a_teacher']                 = '';
				$data['teacher']['a_material']                = '';
				$data['teacher']['a_information']             = '';
				$data['teacher']['a_book_library']            = '';
				$data['teacher']['a_video']                   = '';
				$data['teacher']['a_issue']                   = '';
				$data['teacher']['a_report']                  = '';
				$data['teacher']['a_auth']                    = '';
				$data['teacher']['a_school_admin']            = 1;	// 法学館対応、講師新規作成時は管理者をデフォルトにする
				
				$data['error_msg']              = $this->lang->line_or_def('error_mailaddress','使用済みのメールアドレスです');
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_teacher/edit',
								'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);

				return;
			}

			
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['teacher']['update_flg']                = $this->input->post('update_flg');
			$data['teacher']['teacher_id']                = $this->input->post('teacher_id');
			$data['teacher']['teacher_name']              = $this->input->post('teacher_name');
			$data['teacher']['teacher_email']             = $this->input->post('teacher_email');
			$data['teacher']['teacher_password_old']      = $this->input->post('teacher_password_old');
			$data['teacher']['teacher_password']          = $this->input->post('teacher_password');
			$data['teacher']['teacher_password_check']    = $this->input->post('teacher_password_check');
			$data['teacher']['teacher_password_change']   = $this->input->post('teacher_password_change');
			$data['teacher']['teacher_password_identity'] = $this->input->post('teacher_password_identity');
			$data['teacher']['teacher_auth']              = $this->input->post('teacher_auth');
			$data['teacher']['teacher_introduce']         = $this->input->post('teacher_introduce');
			$data['teacher']['teacher_introduce_detail']  = $this->input->post('teacher_introduce_detail');
			$data['teacher']['teacher_note']              = $this->input->post('teacher_note');
			$data['teacher']['bar_association_id']        = $this->input->post('bar_association_id');
			$data['teacher']['a_cource_class']            = $this->input->post('a_cource_class');
			$data['teacher']['a_student']                 = $this->input->post('a_student');
			$data['teacher']['a_teacher']                 = $this->input->post('a_teacher');
			$data['teacher']['a_material']                = $this->input->post('a_material');
			$data['teacher']['a_information']             = $this->input->post('a_information');
			$data['teacher']['a_book_library']            = $this->input->post('a_book_library');
			$data['teacher']['a_video']                   = $this->input->post('a_video');
			$data['teacher']['a_issue']                   = $this->input->post('a_issue');
			$data['teacher']['a_school_admin']            = $this->input->post('a_school_admin');
			$data['teacher']['a_report']                  = $this->input->post('a_report');
			$data['teacher']['a_auth']                    = $this->input->post('a_auth');


			//**************************************************************
			if( 
				    $data['teacher']['update_flg'] == 1
				 && $data['teacher']['teacher_password_change'] == 1 
			){
				$sql = "SELECT * FROM teacher WHERE status=0 AND teacher_id=? AND teacher_password_encrypt=?";
				$query = $this->db->query(
					$sql,
					array(
						$data['teacher']['teacher_id'],
						hash('sha256',$data['teacher']['teacher_password_old']),
					)
				);
				if ($query->num_rows() > 0) {
				} else {
					$data['error_msg']  = $this->lang->line_or_def('error_password_old','現在のパスワードが正しくありません');
					
					//ビュー設定引数設定
					$disp_param = array(
						'view_name'   => 'cms_teacher/edit',
						'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
						'view_data'   => $data,
					);
					//編集フォーム再表示
					$this->_display_view($disp_param);

					return;
				}
			}
			//**************************************************************
			if( !empty($data['teacher']['teacher_password']) ){
				$login_id = mb_strtolower( $data['teacher']['teacher_email'] );
				$password = mb_strtolower( $data['teacher']['teacher_password'] );
				if( strpos($password, $login_id) !== false ){
					if( $data['teacher']['update_flg'] != 0 ){
						$data['error_msg']  = $this->lang->line_or_def('error_password_inmail','新パスワードにメールアドレスは使用しないで下さい。');
					} else {
						$data['error_msg']  = $this->lang->line_or_def('error_password_inmail','パスワードにメールアドレスは使用しないで下さい。');
					}
					
					//ビュー設定引数設定
					$disp_param = array(
						'view_name'   => 'cms_teacher/edit',
						'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
						'view_data'   => $data,
					);
					//編集フォーム再表示
					$this->_display_view($disp_param);

					return;
				}
			}
			//**************************************************************
			if( !empty($data['teacher']['teacher_password']) && !empty($data['teacher']['teacher_password_old']) ){
				$teacher_password = mb_strtolower( $data['teacher']['teacher_password'] );
				$teacher_password_old = mb_strtolower( $data['teacher']['teacher_password_old'] );
				if( $teacher_password==$teacher_password_old ){
					$data['error_msg']  = $this->lang->line_or_def('error_password_prev','変更前のパスワードと同じパスワードは設定できません。');
					
					//ビュー設定引数設定
					$disp_param = array(
						'view_name'   => 'cms_teacher/edit',
						'submenu_idx' => ($data['teacher']['update_flg']==0 ? 2 : 3),
						'view_data'   => $data,
					);
					//編集フォーム再表示
					$this->_display_view($disp_param);

					return;
				}
			}
			//**************************************************************
			//アカウントロックの確認
			$lock_time_sec = $this->config->item('lock_time_sec');
			$lock_login_count = $this->config->item('lock_login_count');
			$data['teacher']["lock"] = 0;
			$sql= "SELECT * FROM cms_login_fail WHERE 1=1 AND teacher_email=? AND login_fail_count>=".$lock_login_count." AND last_fail_time>='".date("Y-m-d H:i:s", strtotime("-".$lock_time_sec." seconds"))."' ";
			$query = $this->db->query($sql, [
				$data['teacher']['teacher_email'],
			]);
			if ($query->num_rows() > 0) {
				$data['teacher']["lock"] = 1;
			}

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

				$data['teacher']['a_report']       = 1;
				$data['teacher']['a_auth']         = 1;
				$a_report = 1;
				$a_auth   = 1;
			}
			
			//権限設定
			$temp_teacher_auth = array_merge(
				$this->libauth->defaultTeacherAuth,
				array(
					'admin_top'     => 1,
					'school_select' => 0,
					'course'        => 1,
					'course_class'  => $data['teacher']['a_cource_class'] ==1 ? 1: 0,
					'student'       => $data['teacher']['a_student']      ==1 ? 1: 0,
					'teacher'       => $data['teacher']['a_teacher']      ==1 ? 1: 0,
					'material'      => $data['teacher']['a_material']     ==1 ? 1: 0,
					'book_library'  => $data['teacher']['a_book_library'] ==1 ? 1: 0,
					'video'         => $data['teacher']['a_video']        ==1 ? 1: 0,
					'issue'         => $data['teacher']['a_issue']        ==1 ? 1: 0,
					'information'   => $data['teacher']['a_information']  ==1 ? 1: 0,

					'report'        => $data['teacher']['a_report']       ==1 ? 1: 0,
					'auth'          => $data['teacher']['a_auth']         ==1 ? 1: 0,

					'school_admin'  => $data['teacher']['a_school_admin']  ==1 ? 1: 0,
					'school_manage' => $data['teacher']['a_school_manage'] ==1 ? 1: 0,
					'alfproduct_product' => $data['teacher']['a_alfproduct_product'] ==1 ? 1: 0,
					'alfproduct_product_lecture' => $data['teacher']['a_alfproduct_product_lecture'] ==1 ? 1: 0,
					'alfproduct_amount_user' => $data['teacher']['a_alfproduct_amount_user'] ==1 ? 1: 0,
					'alfproduct_mailmagazine' => $data['teacher']['a_alfproduct_mailmagazine'] ==1 ? 1: 0,
					'exam'          => $data['teacher']['a_exam']         ==1 ? 1: 0,
					'exam2'         => $data['teacher']['a_exam2']        ==1 ? 1: 0,
					'ranking'       => $data['teacher']['a_ranking']      ==1 ? 1: 0,
					'category'      => $data['teacher']['a_category']     ==1 ? 1: 0,
					'alfproduct_inquiry' => $data['teacher']['a_alfproduct_inquiry'] ==1 ? 1: 0,
				)
			);
			
			$data['teacher']['teacher_auth'] = serialize($temp_teacher_auth);
			
			$data['teacher']['authname'] = $this->_create_auth_name($temp_teacher_auth);
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data', serialize($data['teacher']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_teacher/confirm',
							'submenu_idx' => 3,
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

			$edit_form_data['teacher_auth'] = $edit_form_data['teacher_auth'];
			
			//モデル読み込み
			$this->load->model('model_teacher');
			
			//データ更新用引数設定
			$data_param = array(
				'data' => $edit_form_data,
			);

			//データ更新
			$data = $this->model_teacher->update_teacher($data_param);

			// [2012/10/01]セッションの更新
			// 自分自身のアカウントを編集した場合のみセッションを更新する
			// （他の管理者を編集した場合に呼ぶと、編集者のセッションが上書きされてしまうため）
			if ((int)$edit_form_data['teacher_id'] === (int)$this->libauth->get_teacher_id()) {
				$result_data = $this->libauth->update_login_session($this->libauth->get_teacher_id());
			}

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_teacher/commit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_teacher');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($teacher_id){

		// load language
		$this->lang->load('error');

		//講師管理の権限有無確認
		$auth_teacher = $this->_get_auth_class($teacher_id);

		// 権限を持たない場合、エラーを返す
		if($auth_teacher == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_teacher');
			$data['error_message'] = $this->lang->line_or_def('error_del_auth','削除権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'teacher_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//モデル読み込み
			$this->load->model('model_teacher');
			
			//データ更新用引数設定
			$data_param = array(
							'teacher_id' => $teacher_id,
						);
			//データ削除
			$data = $this->model_teacher->delete_teacher($data_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');

			//戻る
			$_offset = ($this->session->userdata('offset') ? $this->session->userdata('offset') : $this->session->userdata('offset'));
			$this->session->unset_userdata('offset');
			redirect("/cms_teacher/index/$_offset/");
		}
	}
	
	//----------------------------------------------
	//写真登録フォーム表示
	//----------------------------------------------
	function photo_upload($teacher_id = 0){

		// load language
		$this->lang->load('error');

		//講師管理の権限有無確認
		$auth_teacher = $this->_get_auth_class($teacher_id);

		// 権限を持たない場合、エラーを返す
		if($auth_teacher == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_teacher');
			$data['error_message'] = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'teacher_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
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
				$data['teacher']['update_flg']   = 1;
				$data['teacher']['teacher_id']   = $db_data['teacher_id'];
				$data['teacher']['teacher_name'] = $db_data['teacher_name'];
				$data['teacher']['upload_file']  = '';
				//セッションへDB取得データを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['teacher']));
				
				//その他表示情報の期化
				$data['upload_errors']     = '';
				$data['max_uplolad_pixel'] = $this->max_uplolad_pixel;
				$data['rec_upload_pixel']  = $this->rec_upload_pixel;
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_teacher/photo_upload',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//写真登録フォーム表示
				$this->_display_view($disp_param);
			}else{
				//データ無し時
				//一覧に戻る
				//$this->index();
				header("Location:/cms_teacher/");
				exit();
			}
		}
	}
	
	//----------------------------------------------
	//写真登録実行
	//----------------------------------------------
	function photo_upload_exec(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//アップロードエラー一覧
		$upload_error_messages = array( 
			1=> $this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
				$this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
				$this->lang->line_or_def('error_file_stop_upload','ファイルが途中までしかアップロードされていません'), 
				$this->lang->line_or_def('error_file_no_select','ファイルを指定してください'), 
			6=> $this->lang->line_or_def('error_file_no_folder','テンポラリフォルダがありません'), 
				$this->lang->line_or_def('error_file_no_write','書き込みに失敗しました'), 
				$this->lang->line_or_def('error_file_internal','内部エラーのため、アップロードを中止しました')
		);
		
		//検証ルールの設定
		$this->form_validation->set_rules('teacher_id'   , $this->lang->line_or_def('common_id','ID')     , 'trim|required|numeric');
		$this->form_validation->set_rules('teacher_name' , $this->lang->line_or_def('common_name','名前') , 'trim');
		
		//検証
		if($this->form_validation->run() == FALSE || ($_FILES['upload_file']['error'] != 0)){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['teacher']['teacher_id']   = $this->input->post('teacher_id');
			$data['teacher']['teacher_name'] = $this->input->post('teacher_name');
			$data['teacher']['upload_file']  = '';
			
			//エラーメッセージ設定
			if(isset($_FILES['upload_file'])){
			//	$data['upload_errors'] = '<div class="error">'.$this->upload_error_messages[$_FILES['upload_file']['error']].'</div>';
				$data['upload_errors'] = '<div class="error">'.$upload_error_messages[$_FILES['upload_file']['error']].'</div>';
			} else {
				$data['upload_errors'] = '';
			}
			
			//その他表示情報の期化
			$data['max_uplolad_pixel'] = $this->max_uplolad_pixel;
			$data['rec_upload_pixel']  = $this->rec_upload_pixel;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_teacher/photo_upload',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//写真登録フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ディレクトリ確認
		//	$up_path = $this->photo_dir;
			$up_path = $this->config->item('teacher_dir').'/'.$this->input->post('teacher_id').'/';
			if(!is_dir($up_path)){
				//存在しなければ作成
				mkdir($up_path, 0777, TRUE);
				chmod($up_path,0777);
			}
			
			//ファイルアップロード実行
			$upload_config = array(
				'upload_path'   => $up_path,
				'file_name'     => 'profile.jpg',
				'allowed_types' => $this->upload_types,
				'max_width'     => $this->max_uplolad_pixel['width'],
				'max_height'    => $this->max_uplolad_pixel['height'],
				'overwrite'     => TRUE,
				'remove_spaces' => TRUE,
			);
			$this->load->library('upload', $upload_config);
			
			if(!$this->upload->do_upload('upload_file')){
				//失敗
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['teacher']['teacher_id']   = $this->input->post('teacher_id');
				$data['teacher']['teacher_name'] = $this->input->post('teacher_name');
				$data['teacher']['upload_file']  = '';
				
				//エラーメッセージ設定
				$data['upload_errors'] = $this->upload->display_errors('<div class="error">', '</div>');
				
				//その他表示情報の期化
				$data['max_uplolad_pixel'] = $this->max_uplolad_pixel;
				$data['rec_upload_pixel']  = $this->rec_upload_pixel;
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_teacher/photo_upload',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//写真登録フォーム再表示
				$this->_display_view($disp_param);
			} else {
				//成功
				$data = array();
				$upload_data = $this->upload->data();
				//パーミッション変更
				chmod($up_path.$upload_data['file_name'],0777);
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_teacher/commit',
								'submenu_idx' => 4,
								'view_data'   => $data,
							);
				//完了フォーム表示
				$this->_display_view($disp_param);
				
				//セッションデータのクリア
				$this->session->unset_userdata('edit_form_data');
			}
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
			case 1://受講者検索
				$sub_menu[1] = "検索";
				$sub_menu[2] = anchor("cms_teacher/newdata", "新規登録");
				break;
			
			case 2://新規登録
				$sub_menu[1] = anchor("cms_teacher", "検索");
				$sub_menu[2] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_teacher", "検索");
				$sub_menu[2] = anchor("cms_teacher/newdata", "新規登録");;
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
		
		$param['view_data']['mtb_bar_association']         = $this->get_mtb_list('mtb_bar_association');
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}
	
	//----------------------------------------------
	//権限一覧作成
	// [2012/09/27]学校の契約がない権限を表示しないように修正。
	//----------------------------------------------
	function _create_auth_name($param) {

		$this->load->model('Modelschoolcontract');	// [2012/09/27]

		$work = array();
		if(is_array($param)){
			foreach($param as $key => $value){
				if($key == 'school_admin' && $value == 1){
					$work = array();
					array_push($work, $this->libauth->get_teacherAuthName($key));
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
				if($key == 'report'){
					if($this->Modelschoolcontract->enableService(array('serviceKey'=>'report'))){
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
		}
		
		return implode(',', $work);
	}
	
	//----------------------------------------------
	//日付形式チェック
	//----------------------------------------------
	function date_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('date_check', $this->lang->line_or_def('error_date','%sの日付の形式が間違っています。') );
		//フォーマット設定
		$date_format = 'Y/m/d';
		
		//日付の変換
		$check_date = date_parse_from_format($date_format, $date);
		
		//判定
		if ($check_date['warning_count'] != 0 || $check_date['error_count'] != 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	//----------------------------------------------
	// [2012/10/25]姓名間チェック
	//----------------------------------------------
	function name_check($name){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('name_check', $this->lang->line_or_def('error_name','%sは性と名の間にスペースを入力してください。'));

		$name = preg_replace('/\s/i', '　', $name);
		
		if(!preg_match('/^.+?　.+$/', $name)){
			return false;
		}
		return true;
	}

	//----------------------------------------------
	// パスワード強度チェック
	// 条件:
	// 1) 8文字以上
	// 2) 半角英数字・半角記号のみ（全角やスペース等は不可）
	// 3) 「大文字・小文字・数字・記号」のうち3種類以上を含む
	//----------------------------------------------
	function password_policy($str)
	{
		if (empty($str)) {
			return true;
		}

		// 言語ファイル（任意）
		$this->lang->load('error');

		// 1) 長さ
		if (strlen($str) < 8) {
			$this->form_validation->set_message(
				__FUNCTION__,
				$this->lang->line_or_def('error_password_length', '%sは8桁以上にしてください。')
			);
			return false;
		}

		// 2) 許可文字種: ASCIIの印字可能文字(0x21〜0x7E)のみ＝半角英数字＋半角記号（スペース不可）
		// もしスペースも許可したい場合は `/^[\x20-\x7E]+$/` に変更してください。
		if (!preg_match('/^[\x21-\x7E]+$/', $str)) {
			$this->form_validation->set_message(
				__FUNCTION__,
				$this->lang->line_or_def('error_password_charset', '%sは英数・大文字・小文字・記号のうち3種類以上を混在させてください。')
			);
			return false;
		}

		// 3) 種類カウント（大文字・小文字・数字・記号のうち3種類以上）
		$classes  = 0;
		$classes += preg_match('/[A-Z]/', $str) ? 1 : 0;// 大文字
		$classes += preg_match('/[a-z]/', $str) ? 1 : 0;// 小文字
		$classes += preg_match('/[0-9]/', $str) ? 1 : 0;// 数字
		$classes += preg_match('/[^A-Za-z0-9]/', $str) ? 1 : 0;// 記号(半角)

		if ($classes < 3) {
			$this->form_validation->set_message(
				__FUNCTION__,
				$this->lang->line_or_def('error_password_complexity', '%sは英数・大文字・小文字・記号のうち3種類以上を混在させてください。')
			);
			return false;
		}

		return true;
	}

	//----------------------------------------------
	//講師管理の権限有無確認
	//  ログインユーザーに授業管理の権限があるかの確認
	//  修正・削除可能かどうか
	//----------------------------------------------
	function _get_auth_class($teacher_id){
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$work_auth           = $this->libauth->get_teacher_auth();
		$auth_teacher          = 0;
		//var_dump($login_teacher_id);
		//var_dump($work_auth);
		if($login_teacher_id < 0){
			//SuperUser
			$auth_teacher = 1;
		}elseif($work_auth['school_admin'] == 1){
			//学校管理者
			$auth_teacher = 1;
		}elseif( $work_auth['teacher'] == 1 ){
			//講師かつ講師管理の権限あり
			
			//選択した講師が自分自身であれば権限付与
			if( $teacher_id > 0 ){
				if($teacher_id == $login_teacher_id){
					$auth_teacher = 1;
				} else {
					//$auth_teacher = 0;
					$auth_teacher = 1;
				}
			}else{
				$auth_teacher = 1;
			}
		}else{
			//権限なし
			//$auth_teacher = 0;
			$auth_teacher = 1;
		}
		//var_dump($auth_teacher);
		//戻り値
		return $auth_teacher;
	}
	

	//** 日弁連対応 **//
	//----------------------------------------------
	// 日弁連
	// 弁護士会マスタ一覧ドロップダウン用配列取得
	//   model_student 使用
	//----------------------------------------------
	function get_mtb_list($table_name = ''){
		// 変数初期化
		$data = array();

		//引数確認（引数なしは空カラムを返す）
		if($table_name == ''){
			$data[''] = '';
			return $data;
		}
		
		// マスタデータ取得
		$this->load->model('model_student');
		$result_data = $this->model_student->get_mtb_list($table_name);

		if($result_data){
			foreach($result_data as $row){
				$data[$row['id']] = $row['name'];
			}
		}
		
		return $data;
	}

} 

/*End of File program.php*/
