<?php
#[AllowDynamicProperties]
class Cms_school_manage extends CI_Controller
{
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
			if($work_auth['school_manage'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			//  学校管理では使用しない
		//	if( $this->libauth->get_school_id() == 0 ){
		//		redirect('school_select');
		//	}
		}
		$this->load->helper('unit_helper');
	}
	
	//----------------------------------------------
	//一覧表示（index）
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
		$this->form_validation->set_rules('s_school_id'   , $this->lang->line_or_def('common_id','ID')                 , 'trim|xss_clean');
		$this->form_validation->set_rules('s_school_name' , $this->lang->line_or_def('common_school_name','学校名')    , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'   , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim|xss_clean');
		$this->form_validation->run();	//バリデーション実行（その実xss対策）
		
		//モデル読み込み
		$this->load->model('model_school_manage');
		
		if ( !$this->input->post() ){
			$data = $this->session->userdata('school_manage_search_cond') ?: array(
				's_school_id' => '',
				's_school_name' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_school_id']   = ($this->input->post('s_school_id', TRUE) ?? '');
			$data['s_school_name'] = ($this->input->post('s_school_name', TRUE) ?? '');
			$data['s_free_word']   = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('school_manage_search_cond', $data);
		}

		$data_param = array(
			's_school_id'   => $data['s_school_id'],
			's_school_name' => $data['s_school_name'],
			's_free_word'   => $data['s_free_word'],
			'offset'        => $offset,
			'rowcount'      => $per_page,
		);

		//データ取得
		$school_list = $this->model_school_manage->get_school_search_list($data_param);
		$data['school_list'] = $school_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_school_manage/index';
		$config['total_rows'] = $this->model_school_manage->get_school_search_count($data_param);
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
						'view_name'   => 'cms_school_manage/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}

	//----------------------------------------------
	//詳細表示フォーム表示（confirm）
	// [2012/11/01]外部連携表示の追加
	//----------------------------------------------
	function detail($school_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_school_manage');
		$this->load->model('modelschoolcontract');
		$this->modelschoolcontract->initialize($school_id); // DBから取得

		//データ取得用引数設定
		$data_param = array(
						'school_id' => $school_id,
					);
		//データ取得
		$db_data = $this->model_school_manage->get_school($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['school']['update_flg']         = 1;
			$data['school']['school_id']          = $db_data['school_id'];
			$data['school']['school_name']        = $db_data['school_name'];
			$data['school']['school_caption']     = $db_data['school_caption'];
			$data['school']['school_note']        = $db_data['school_note'];
			$data['school']['contract']           = $db_data['contract'];
			$data['school']['contract_param']     = $db_data['contract_param'];
			$data['school']['school_admin_count'] = $db_data['school_admin_count'];
			$data['school']['teacher_count']      = $db_data['teacher_count'];
			$data['school']['student_count']      = $db_data['student_count'];
			
			$data['school']['contract_param_live'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'live'));
			
			if($data['school']['contract_param_live']['time'] == 0){
				$data['school']['contract_param_live']['time_convert'] = 0;		// 未設定によりゼロ値のため、別計算
			}else{
				$data['school']['contract_param_live']['time_convert']   = 
					rtrim(Sec2Disp($data['school']['contract_param_live']['time'], array('dd' => false, 'mm' => false, 'ss' => false)),'時間');
			}
			$data['school']['contract_param_live']['strage_convert'] = 
				rtrim(ConvertUnit($data['school']['contract_param_live']['strage'],2),'G');
			
			$data['school']['contract_param_video'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'video'));
			$data['school']['contract_param_video']['stream_convert'] = 
				rtrim(ConvertUnit($data['school']['contract_param_video']['stream'],2),'G');
			$data['school']['contract_param_video']['strage_convert'] = 
				rtrim(ConvertUnit($data['school']['contract_param_video']['strage'],2),'G');
			
			$data['school']['contract_param_book_library'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'book_library'));
			$data['school']['contract_param_book_library']['stream_convert'] = 
				rtrim(ConvertUnit($data['school']['contract_param_book_library']['stream'],2),'G');
			$data['school']['contract_param_book_library']['strage_convert'] = 
				rtrim(ConvertUnit($data['school']['contract_param_book_library']['strage'],2),'G');
			
			$temp = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'issue'));
			if( $temp ){
				$data['school']['contract_param_issue'] = $temp;
			}else{
				$data['school']['contract_param_issue']['stat']     = 1;
				$data['school']['contract_param_issue']['contract'] = 'undefined';
			}
			
			$data['school']['contract_param_alfstream'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'alfstream'));
			
			$data['school']['contract_param_outside_elearningmanager'] = $this->_get_outside_corporation($db_data['school_id'], 'outside_elearningmanager');
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['school']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_school_manage/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_school_manage/");
			exit();
		}
	}

	//----------------------------------------------
	//新規フォーム表示（edit）
	// [2012/11/01]外部契約（eLM）項目追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['school']['update_flg']         = 0;
		$data['school']['school_id']          = 0;
		$data['school']['school_name']        = '';
		$data['school']['school_caption']     = '';
		$data['school']['school_note']        = '';
		$data['school']['contract']           = '';//'fixation';
		$data['school']['contract_param']     =  $this->_get_new_contract_param();
		$data['school']['school_admin_name']  = '';
		$data['school']['school_admin_email'] = '';

		//初期表示設定（契約内容）
		$this->load->helper('json');
		$contract_param_array = obj2arr(json_decode($data['school']['contract_param']));

		$data['school']['contract_param_live'] = $contract_param_array['live'];

		$data['school']['contract_param_live']['time_convert']   = 
			rtrim(Sec2Disp($data['school']['contract_param_live']['time'], array('dd' => false, 'mm' => false, 'ss' => false)),'時間');
		$data['school']['contract_param_live']['strage_convert'] = 
			rtrim(ConvertUnit($data['school']['contract_param_live']['strage'],2),'G');

		$data['school']['contract_param_video'] = $contract_param_array['video'];
		$data['school']['contract_param_video']['stream_convert'] = 
			rtrim(ConvertUnit($data['school']['contract_param_video']['stream'],2),'G');
		$data['school']['contract_param_video']['strage_convert'] = 
			rtrim(ConvertUnit($data['school']['contract_param_video']['strage'],2),'G');

		$data['school']['contract_param_book_library'] = $contract_param_array['book_library'];
		$data['school']['contract_param_book_library']['stream_convert'] = 
			rtrim(ConvertUnit($data['school']['contract_param_book_library']['stream'],2),'G');
		$data['school']['contract_param_book_library']['strage_convert'] = 
			rtrim(ConvertUnit($data['school']['contract_param_book_library']['strage'],2),'G');

		$data['school']['contract_param_issue'] = $contract_param_array['issue'];

		$data['school']['contract_param_alfstream'] = $contract_param_array['alfstream'];
		$data['school']['contract_param_alfstream']['auth_key'] = $data['school']['contract_param_alfstream']['auth_key'];
		$data['school']['contract_param_alfstream']['code']     = $data['school']['contract_param_alfstream']['code'];
		
		$data['school']['contract_param_outside_elearningmanager'] = $contract_param_array['outside_elearningmanager'];
		$data['school']['contract_param_outside_elearningmanager']['contract'] = $data['school']['contract_param_outside_elearningmanager']['contract'];
		$data['school']['contract_param_outside_elearningmanager']['api_key']  = $data['school']['contract_param_outside_elearningmanager']['api_key'];
		$data['school']['contract_param_outside_elearningmanager']['api_url']  = $data['school']['contract_param_outside_elearningmanager']['api_url'];

		//セッションへDB取得データを書き込み
		$this->session->set_userdata('edit_form_data',serialize($data['school']));
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_school_manage/edit',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//修正フォーム表示（edit）
	//----------------------------------------------
	function edit(){
		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['school_id']) &&  $edit_form_data['school_id'] <> ''){
			//画面表示用データ設定
			$data['school'] = $edit_form_data;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_school_manage/edit',
							'submenu_idx' => ($data['school']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('school_manage');
			
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
	//更新確認フォーム表示（confirm）
	// [2012/10/23]外部連携表示の追加
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');

		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'     , $this->lang->line_or_def('common_flg','flg')                     , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('school_id'      , $this->lang->line_or_def('common_id','ID')                       , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('school_name'    , $this->lang->line_or_def('common_school_name','学校名')          , 'trim|xss_clean|required');
		$this->form_validation->set_rules('school_caption' , $this->lang->line_or_def('common_caption','説明')                , 'trim|xss_clean');
		$this->form_validation->set_rules('school_note'    , $this->lang->line_or_def('common_note','備考')                   , 'trim|xss_clean');
		$this->form_validation->set_rules('contract'       , $this->lang->line_or_def('common_contract_form','契約形態')      , 'trim|xss_clean|required');
	//	$this->form_validation->set_rules('contract_param' , $this->lang->line_or_def('common_contract_contents','契約内容')  , 'trim|xss_clean');
		$this->form_validation->set_rules('live_contract'         , $this->lang->line_or_def('common_class','授業').'-'.$this->lang->line_or_def('common_contract_form','契約形態') , 'trim|xss_clean|required');
		$this->form_validation->set_rules('video_contract'        , $this->lang->line_or_def('common_video','ビデオ').'-'.$this->lang->line_or_def('common_contract_form','契約形態')             , 'trim|xss_clean|required');
		$this->form_validation->set_rules('book_library_contract' , $this->lang->line_or_def('common_book_library','図書室').'-'.$this->lang->line_or_def('common_contract_form','契約形態')                 , 'trim|xss_clean|required');
		$this->form_validation->set_rules('issue_contract'        , $this->lang->line_or_def('common_issue','課題').'-'.$this->lang->line_or_def('common_contract_form','契約形態')                 , 'trim|xss_clean|required');		
		// 授業-契約形態が「未定義」以外のみチェック
		if($this->input->post('live_contract') != 'undefined'){
			$this->form_validation->set_rules('live_time'             , $this->lang->line_or_def('common_class','授業').'-'.$this->lang->line_or_def('common_contract_class_time','契約授業時間') , 'trim|xss_clean|is_natural_no_zero|required');
			$this->form_validation->set_rules('live_strage'           , $this->lang->line_or_def('common_class','授業').'-'.$this->lang->line_or_def('common_contract_storage','契約ストレージ使用量')     , 'trim|xss_clean|is_natural_no_zero|required');
		}
		
		// ビデオ-契約形態が「未定義」以外のみチェック
		if($this->input->post('video_contract') != 'undefined'){
			$this->form_validation->set_rules('video_stream'          , $this->lang->line_or_def('common_video','ビデオ').'-'.$this->lang->line_or_def('common_contract_transfer','契約転送量')           , 'trim|xss_clean|is_natural_no_zero|required');
			$this->form_validation->set_rules('video_strage'          , $this->lang->line_or_def('common_video','ビデオ').'-'.$this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') , 'trim|xss_clean|is_natural_no_zero|required');
		}
		
		// 図書室-契約形態が「未定義」以外のみチェック
		if($this->input->post('book_library_contract') != 'undefined'){
			$this->form_validation->set_rules('book_library_stream'   , $this->lang->line_or_def('common_book_library','図書室').'-'.$this->lang->line_or_def('common_contract_transfer','契約転送量')               , 'trim|xss_clean|is_natural_no_zero|required');
			$this->form_validation->set_rules('book_library_strage'   , $this->lang->line_or_def('common_book_library','図書室').'-'.$this->lang->line_or_def('common_contract_storage','契約ストレージ使用量')     , 'trim|xss_clean|is_natural_no_zero|required');
		}
		
		// 新規登録のみチェック
		if($this->input->post('school_id') == 0){
			$this->form_validation->set_rules('school_admin_name'  , $this->lang->line_or_def('common_teacher_name','講師名')         , 'trim|xss_clean|required|callback_name_check');
			$this->form_validation->set_rules('school_admin_email' , $this->lang->line_or_def('common_mail_address','メールアドレス') , 'trim|xss_clean|required|valid_email');
		}else{
		// 更新登録のみチェック
			$this->form_validation->set_rules('alfstream_auth_key'  , 'AlfStream-auth key'              , 'trim|xss_clean|required');
			$this->form_validation->set_rules('alfstream_auth_code' , 'AlfStream-code'                  , 'trim|xss_clean|required');
		}
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['school']['update_flg']         = $this->input->post('update_flg');
			$data['school']['school_id']          = $this->input->post('school_id');
			$data['school']['school_name']        = '';
			$data['school']['school_caption']     = '';
			$data['school']['school_note']        = '';
			$data['school']['contract']           = '';
		//	$data['school']['contract_param']     = '';
			$data['school']['school_admin_name']  = '';
			$data['school']['school_admin_email'] = '';

			// 受け渡し変数初期化（未定義エラー回避の為）（契約内容）
			$this->load->model('modelschoolcontract');
			$this->modelschoolcontract->initialize($this->input->post('school_id')); // DBから取得
			
			$data['school']['contract_param_live'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'live'));
			$data['school']['contract_param_live']['time_convert'] = $this->input->post('live_time');
			$data['school']['contract_param_live']['strage_convert'] = $this->input->post('live_strage');

			$data['school']['contract_param_video'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'video'));
			$data['school']['contract_param_video']['stream_convert'] = $this->input->post('video_stream');
			$data['school']['contract_param_video']['strage_convert'] = $this->input->post('video_strage');
			
			$data['school']['contract_param_book_library'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'book_library'));
			$data['school']['contract_param_book_library']['stream_convert'] = $this->input->post('book_library_stream');
			$data['school']['contract_param_book_library']['strage_convert'] = $this->input->post('book_library_strage');

			$data['school']['contract_param_issue']['stat']     = 1;
			$data['school']['contract_param_issue']['contract'] = $this->input->post('issue_contract');

			$data['school']['contract_param_alfstream'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'alfstream'));

			$data['school']['contract_param_outside_elearningmanager']['contract'] = $this->input->post('outside_elearningmanager_contract');
			$data['school']['contract_param_outside_elearningmanager']['api_key']  = $this->input->post('outside_elearningmanager_api_key');
			$data['school']['contract_param_outside_elearningmanager']['api_url']  = $this->input->post('outside_elearningmanager_api_url');

			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data', serialize($data['school']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_school_manage/edit',
							'submenu_idx' => ($data['school']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['school']['update_flg']         = $this->input->post('update_flg');
			$data['school']['school_id']          = $this->input->post('school_id');
			$data['school']['school_name']        = $this->input->post('school_name');
			$data['school']['school_caption']     = $this->input->post('school_caption');
			$data['school']['school_note']        = $this->input->post('school_note');
			$data['school']['contract']           = $this->input->post('contract');
			$data['school']['contract_param']     = $this->input->post('contract_param');
			$data['school']['school_admin_name']  = $this->input->post('school_admin_name');
			$data['school']['school_admin_email'] = $this->input->post('school_admin_email');
			
			// 確認画面用データ設定（契約内容）
			$this->load->model('modelschoolcontract');
			$this->modelschoolcontract->initialize($this->input->post('school_id')); // DBから取得
			
			$data['school']['contract_param_live'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'live'));
			$data['school']['contract_param_live']['contract']       = $this->input->post('live_contract');
			$data['school']['contract_param_live']['time_convert']   = $this->input->post('live_time');
			$data['school']['contract_param_live']['strage_convert'] = $this->input->post('live_strage');

			$data['school']['contract_param_video'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'video'));
			$data['school']['contract_param_video']['contract']       = $this->input->post('video_contract');
			$data['school']['contract_param_video']['stream_convert'] = $this->input->post('video_stream');
			$data['school']['contract_param_video']['strage_convert'] = $this->input->post('video_strage');
			
			$data['school']['contract_param_book_library'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'book_library'));
			$data['school']['contract_param_book_library']['contract']       = $this->input->post('book_library_contract');
			$data['school']['contract_param_book_library']['stream_convert'] = $this->input->post('book_library_stream');
			$data['school']['contract_param_book_library']['strage_convert'] = $this->input->post('book_library_strage');

			$data['school']['contract_param_issue'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'issue'));
			$data['school']['contract_param_issue']['stat']     = 1;
			$data['school']['contract_param_issue']['contract'] = $this->input->post('issue_contract');

			$data['school']['contract_param_alfstream'] = $this->modelschoolcontract-> getContractParam(array('serviceKey' => 'alfstream'));
			$data['school']['contract_param_alfstream']['auth_key'] = $this->input->post('alfstream_auth_key');
			$data['school']['contract_param_alfstream']['code']     = $this->input->post('alfstream_auth_code');

			$data['school']['contract_param_outside_elearningmanager'] = $this->_get_outside_corporation($this->input->post('school_id'), 'outside_elearningmanager');
			$data['school']['contract_param_outside_elearningmanager']['contract'] = $this->input->post('outside_elearningmanager_contract');
			$data['school']['contract_param_outside_elearningmanager']['api_key']  = $this->input->post('outside_elearningmanager_api_key');
			$data['school']['contract_param_outside_elearningmanager']['api_url']  = $this->input->post('outside_elearningmanager_api_url');

			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data', serialize($data['school']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_school_manage/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}

	//----------------------------------------------
	//データ更新及び更新完了フォーム表示（commit）
	//----------------------------------------------
	function commit(){
		
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['school_id']) && $edit_form_data['school_id'] != ""){
		//	$this->load->model('model_video');
		//	$res = $this->model_video->new_service(array(
		//		'name'	=> $edit_form_data['school_name'].date("Y-m-d H:i:s"),
		//	));
		//	if($res->stat != 200){
		//		$data['returnurl'] = site_url('cms_teacher');
		//		$disp_param = array(
		//						'view_name'   => 'alfstream_api_error->'.$res->stat,
		//						'submenu_idx' => 3,
		//						'view_data'   => $data,
		//					);
		//		//確認フォーム表示
		//		$this->_display_view($disp_param);
		//		return;
		//	}
		//	$this->load->helper('json');
		//	$edit_form_data['contract_param'] = obj2arr(json_decode($edit_form_data['contract_param']));
		//	$edit_form_data['contract_param']['alfstream']['auth_key']	= $res->dat->apikey;
		//	$edit_form_data['contract_param']['alfstream']['code']		= $res->dat->idkey;
		//	$edit_form_data['contract_param'] = json_encode($edit_form_data['contract_param']);
			$alfstream_auth_key = '';
			$alfstream_code     = '';
			if($edit_form_data['update_flg'] == 0){
				// 新規登録の場合、alfstreamへの新規登録、及びauth key・codeを取得
				$this->load->model('model_video');
				$res = $this->model_video->new_service(array(
					'name'	=> $edit_form_data['school_name'].date("Y-m-d H:i:s"),
				));
				if($res->stat != 200){
					$data['returnurl'] = site_url('cms_teacher');
					$disp_param = array(
									'view_name'   => 'alfstream_api_error->'.$res->stat,
									'submenu_idx' => 3,
									'view_data'   => $data,
								);
					//確認フォーム表示
					$this->_display_view($disp_param);
					return;
				}
				$alfstream_auth_key = $res->dat->apikey;
				$alfstream_code     = $res->dat->idkey;
			}

			// 契約内容の構築（新規登録の場合、上記処理のauth key・codeを格納）
			$this->load->helper('json');
			$edit_form_data['contract_param'] = obj2arr(json_decode($edit_form_data['contract_param']));
			
			$edit_form_data['contract_param']['live']['contract'] = $edit_form_data['contract_param_live']['contract'];
			if($edit_form_data['contract_param_live']['contract']=='undefined'){
				$edit_form_data['contract_param']['live']['time']     = 0;
				$edit_form_data['contract_param']['live']['strage']   = 0;
			}else{
				$edit_form_data['contract_param']['live']['time']     = $edit_form_data['contract_param_live']['time_convert'] * (60 * 60);
				$edit_form_data['contract_param']['live']['strage']   = $edit_form_data['contract_param_live']['strage_convert'] * (1024 * 1024 * 1024 );
			}
			
			$edit_form_data['contract_param']['video']['contract'] = $edit_form_data['contract_param_video']['contract'];
			if($edit_form_data['contract_param_video']['contract']=='undefined'){
				$edit_form_data['contract_param']['video']['stream']   = 0;
				$edit_form_data['contract_param']['video']['strage']   = 0;
			}else{
				$edit_form_data['contract_param']['video']['stream']   = $edit_form_data['contract_param_video']['stream_convert'] * (1024 * 1024 * 1024 );
				$edit_form_data['contract_param']['video']['strage']   = $edit_form_data['contract_param_video']['strage_convert'] * (1024 * 1024 * 1024 );
			}
			
			$edit_form_data['contract_param']['book_library']['contract'] = $edit_form_data['contract_param_book_library']['contract'];
			if($edit_form_data['contract_param_book_library']['contract']=='undefined'){
				$edit_form_data['contract_param']['book_library']['stream']   = 0;
				$edit_form_data['contract_param']['book_library']['strage']   = 0;
			}else{
				$edit_form_data['contract_param']['book_library']['stream']   = $edit_form_data['contract_param_book_library']['stream_convert'] * (1024 * 1024 * 1024 );
				$edit_form_data['contract_param']['book_library']['strage']   = $edit_form_data['contract_param_book_library']['strage_convert'] * (1024 * 1024 * 1024 );
			}

			$edit_form_data['contract_param']['issue']['stat']     = 1;
			$edit_form_data['contract_param']['issue']['contract'] = $edit_form_data['contract_param_issue']['contract'];

			
			if($edit_form_data['update_flg'] == 0){
				$edit_form_data['contract_param']['alfstream']['auth_key'] = $alfstream_auth_key;
				$edit_form_data['contract_param']['alfstream']['code']     = $alfstream_code;
			}else{
				$edit_form_data['contract_param']['alfstream']['auth_key'] = $edit_form_data['contract_param_alfstream']['auth_key'];
				$edit_form_data['contract_param']['alfstream']['code']     = $edit_form_data['contract_param_alfstream']['code'];
			}

			$edit_form_data['contract_param']['outside_elearningmanager']['contract'] = $edit_form_data['contract_param_outside_elearningmanager']['contract'];
			$edit_form_data['contract_param']['outside_elearningmanager']['api_key']  = urlencode($edit_form_data['contract_param_outside_elearningmanager']['api_key']);
			$edit_form_data['contract_param']['outside_elearningmanager']['api_url']  = urlencode($edit_form_data['contract_param_outside_elearningmanager']['api_url']);

			
			$edit_form_data['contract_param'] = json_encode($edit_form_data['contract_param']);

			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
		//	$edit_form_data['school_id'] = $this->libauth->get_school_id();

		//	$edit_form_data['teacher_auth'] = $edit_form_data['teacher_auth'];
			
			//モデル読み込み
			$this->load->model('model_school_manage');
			
			//データ更新用引数設定
			$data_param = array(
							'data' => $edit_form_data,
						);
			//データ更新
			$data = $this->model_school_manage->update_school($data_param);
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_school_manage/commit',
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
	// データ削除（論理削除）
	//----------------------------------------------
	function delete_item($school_id){
		//モデル読み込み
		$this->load->model('model_school_manage');
		
		//データ更新用引数設定
		$data_param = array(
						'school_id' => $school_id,
					);
		//データ削除
		$data = $this->model_school_manage->delete_school($data_param);
		
		//リスト画面表示
		//$this->index();
		header("Location:/cms_school_manage/");
		exit();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
	}

	//----------------------------------------------
	// ビュー表示 *
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
	//	$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		// [2012/08/31]契約形態ドロップダウン用データ取得（学校単位）
		$param['view_data']['school_contract']  = $this->_get_select_contract();

		//契約形態ドロップダウン用データ取得（各コンテンツ単位）
		$param['view_data']['contract_dropdown']  = $this->_get_select_contract_param();

		// [2012/11/01]外部連携、契約有無ドロップダウン用データ取得
		$param['view_data']['outside_contract']  = $this->_get_select_outside_contract();

		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}

	//----------------------------------------------
	// レポート表示 *
	//----------------------------------------------
	function report() {
		// load language
		$this->lang->load('common');
		
		$this->form_validation->set_rules('date' , $this->lang->line_or_def('common_date','date') , 'trim|xss_clean');
		$this->form_validation->run();
		if(!$this->input->get('date')){
			$this->load->view('cms_school_manage/report', array());
		}
		else{
			$where_sql      = '';
			$school_id_list = '0';
			if(getenv('URL_SERVICE') == 'alfsales'){
				$where_sql = ' WHERE lang = "alfsales" ';
			}elseif(getenv('URL_SERVICE') == 'conference'){
				$where_sql = ' WHERE lang = "conference" ';
			}else{
				$where_sql = ' WHERE (lang != "alfsales" AND lang != "conference") ';
			}

			$_reqDate = strip_tags($this->input->get('date', TRUE) ?? '');

			//学校一覧
			$schools = array();
			$query = $this->db->query(
				' SELECT * FROM school'.$where_sql,
				array()
			);
			$items = array();
			foreach($query->result_array() as $row){
				$schools['school_id_'.$row['school_id']] = $row;
				$school_id_list .= ','.$row['school_id'];
			}

			//授業
			$query = $this->db->query(
				' SELECT school_id, type, SUM(value) AS val'.
				' FROM report_live'.
				' WHERE date LIKE ?'.
				' AND school_id IN ('.$school_id_list.')'.
				' GROUP BY school_id, type',
				array(
					"$_reqDate%",
				)
			);
			$items = array();
			foreach($query->result_array() as $row){
				$schools['school_id_'.$row['school_id']]['live_'.$row['type']] = $row['val'];
			}

			//図書
			$query = $this->db->query(
				' SELECT school_id, type, SUM(value) AS val'.
				' FROM report_book_library'.
				' WHERE date LIKE ?'.
				' AND school_id IN ('.$school_id_list.')'.
				' GROUP BY school_id, type',
				array(
					"$_reqDate%",
				)
			);
			$items = array();
			foreach($query->result_array() as $row){
				$schools['school_id_'.$row['school_id']]['book_library_'.$row['type']] = $row['val'];
			}

			//動画
			$query = $this->db->query(
				' SELECT school_id, type, SUM(value) AS val'.
				' FROM report_video'.
				' WHERE date LIKE ?'.
				' AND school_id IN ('.$school_id_list.')'.
				' GROUP BY school_id, type',
				array(
					"$_reqDate%",
				)
			);
			$items = array();
			foreach($query->result_array() as $row){
				$schools['school_id_'.$row['school_id']]['video_'.$row['type']] = $row['val'];
			}

			//csv
			$fileName = "report_$_reqDate.csv";
			$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');

			header('Content-Type: application/x-csv');
			header("Content-Disposition: attachment; filename=$fileName");

			$fp = fopen('php://output', 'w');

			//header clum
			$_headClum = array(
				$this->lang->line_or_def('common_csv_school_id'             , '学校ID'),
				$this->lang->line_or_def('common_csv_school_name'           , '学校名'),
				$this->lang->line_or_def('common_csv_class_strage'          , '授業ストレージ使用量').'(MByte)',
				$this->lang->line_or_def('common_csv_class_time'            , '授業時間'),
				$this->lang->line_or_def('common_csv_video_strage'          , 'ビデオストレージ使用量').'(MByte)',
				$this->lang->line_or_def('common_csv_video_transfer'        , 'ビデオ転送量').'(MByte)',
				$this->lang->line_or_def('common_csv_book_library_strage'   , '図書室ストレージ使用量').'(MByte)',
				$this->lang->line_or_def('common_csv_book_library_transfer' , '図書室転送量').'(MByte)',
			);
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $_headClum);
			fputcsv($fp, $_headClum);

			foreach($schools as $school){
				$data = array(
					$school['school_id'],
					$school['school_name'],
//					(isset($school['live_strage'])			? $school['live_strage']										: 0),
					(isset($school['live_strage'])			? round($school['live_strage'] / (1024 * 1024), 2).'M'			: 0),
					(isset($school['live_time'])			? Sec2Disp($school['live_time'], array('dd' => false), true)	: 0),
//					(isset($school['video_strage'])			? $school['video_strage']										: 0),
					(isset($school['video_strage'])			? round($school['video_strage'] / (1024 * 1024), 2).'M'			: 0),
//					(isset($school['video_stream'])			? $school['video_stream']										: 0),
					(isset($school['video_stream'])			? round($school['video_stream'] / (1024 * 1024), 2).'M'			: 0),
//					(isset($school['book_library_strage'])	? $school['book_library_strage']								: 0),
					(isset($school['book_library_strage'])	? round($school['book_library_strage'] / (1024 * 1024), 2).'M'	: 0),
//					(isset($school['book_library_stream'])	? $school['book_library_stream']								: 0),
					(isset($school['book_library_stream'])	? round($school['book_library_stream'] / (1024 * 1024), 2).'M'	: 0),
				);
				mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $data);
				fputcsv($fp, $data);
			}
			fclose($fp);
		}
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	function _convertStorage($storage) {
		
		if(($storage = 0) || (!$storage)){
			return "0 Byte";
		}
		
		if(($storage / 1024) < 1) {
			return $storage." Byte";
		}elseif(($storage / (1024 * 1024)) < 1) {
			return round(($storage / 1024), 2)." KByte";
		}elseif(($storage / (1024 * 1024 * 1024)) < 1) {
			return round(($storage / (1024 * 1024)), 2)." MByte";
		}else{
			return round(($storage / (1024 * 1024 * 1024)), 2)." GByte";
		}
	}
	

	//----------------------------------------------
	// 契約形態ドロップダウン用配列取得
	//----------------------------------------------
	function _get_select_contract(){
		$this->lang->load('common');

		$data['contract'] = array();
		$data['contract']['']     = '';
		$data['contract']['fixation']     = $this->lang->line_or_def('common_contract_fixation','本契約');
		$data['contract']['demo']         = $this->lang->line_or_def('common_contract_demo','デモ版');
		$data['contract']['presentation'] = $this->lang->line_or_def('common_contract_presentation','プレゼン版');
		
		return $data['contract'];
	}
	
	//----------------------------------------------
	// 個別契約形態ドロップダウン用配列取得
	// [2012/08/31]メソッド名変更（_get_select_contract -> _get_select_contract_param）
	//----------------------------------------------
	function _get_select_contract_param(){
		$this->lang->load('common');

		$data['contract'] = array();
		$data['contract']['fixation']  = $this->lang->line_or_def('common_monthly_basis_contract','月額契約');
		$data['contract']['undefined'] = $this->lang->line_or_def('common_no_setting','未設定');
		
		return $data['contract'];
	}
	
	//----------------------------------------------
	// [2012/11/01]外部連携、契約有無ドロップダウン用配列取得
	//----------------------------------------------
	function _get_select_outside_contract(){
		$this->lang->load('common');

		$data['contract'] = array();
		$data['contract']['fixation']  = $this->lang->line_or_def('common_contract','契約あり');
		$data['contract']['undefined'] = $this->lang->line_or_def('common_no_contract','契約なし');
		
		return $data['contract'];
	}
	
	//----------------------------------------------
	// 契約形態初期値取得（edit）
	// [2012/11/01]外部契約（eLM）項目追加
	//----------------------------------------------
	function _get_new_contract_param(){

		// AlfSales ドメイン時、生授業の初期値を未契約にする
		$contract_param_live_contract = 'fixation';
		$check_language = getenv('URL_SERVICE');
		if( $check_language == 'alfsales' ){
			$contract_param_live_contract = 'undefined';
		}else{
			$contract_param_live_contract = 'fixation';
		}
		
		$contract_param = array();

		$contract_param['live']['stat']               = 1;
		$contract_param['live']['contract']           = $contract_param_live_contract;
		$contract_param['live']['time']               = 200 * (60 * 60);
		$contract_param['live']['strage']             = 30 * (1024 * 1024 * 1024);
		$contract_param['live']['time_now']           = 0;
		$contract_param['live']['strage_now']         = 0;
		$contract_param['live']['updated_at']         = time();

		$contract_param['video']['stat']              = 1;
		$contract_param['video']['contract']          = 'fixation';
		$contract_param['video']['stream']            = 30 * (1024 * 1024 * 1024);
		$contract_param['video']['strage']            = 30 * (1024 * 1024 * 1024);
		$contract_param['video']['stream_now']        = 0;
		$contract_param['video']['strage_now']        = 0;
		$contract_param['video']['updated_at']        = time();

		$contract_param['book_library']['stat']       = 1;
		$contract_param['book_library']['contract']   = 'fixation';
		$contract_param['book_library']['stream']     = 30 * (1024 * 1024 * 1024);
		$contract_param['book_library']['strage']     = 30 * (1024 * 1024 * 1024);
		$contract_param['book_library']['stream_now'] = 0;
		$contract_param['book_library']['strage_now'] = 0;
		$contract_param['book_library']['updated_at'] = time();

		$contract_param['issue']['stat']     = 1;
		$contract_param['issue']['contract'] = 'undefined';

		$contract_param['alfstream']['auth_key']      = '';
		$contract_param['alfstream']['code']          = '';

		$contract_param['outside_elearningmanager']['contract'] = 'undefined';
		$contract_param['outside_elearningmanager']['api_key']  = '';
		$contract_param['outside_elearningmanager']['api_url']  = '';

		$this->load->helper('json');
		return json_encode($contract_param);
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
	// [2012/10/23]外部連携取得
	//----------------------------------------------
	function _get_outside_corporation($school_id = 0, $outside = ''){
		$this->load->model('model_school_manage');

		//データ取得
		$db_data = $this->model_school_manage->get_school(
						array(
							'school_id' => $school_id,
						)
					);
		
		$arr_result['contract'] = 'undefined';
		$arr_result['api_key']  = '';
		$arr_result['api_url']  = '';

		if(count($db_data) > 0){
			$this->load->helper('json');
			$check_contract_param = obj2arr(json_decode($db_data['contract_param']));
			//'outside_elearningmanager'
			if(isset($check_contract_param[$outside])){
				$arr_result['contract'] = $check_contract_param[$outside]['contract'];
				$arr_result['api_key']  = urldecode($check_contract_param[$outside]['api_key']);
				$arr_result['api_url']  = urldecode($check_contract_param[$outside]['api_url']);
			}
		}
		
		return $arr_result;
	}
}

/*End of File program.php*/
