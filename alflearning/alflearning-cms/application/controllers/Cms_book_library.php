<?php
#[AllowDynamicProperties]
class Cms_book_library extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $day_second            = 86400;			//日数計算用、一日秒数
	private $book_library_dir      = '';			//資料アップロードディレクトリ
													//アップロード可能ファイルタイプ
//	private $upload_types          = 'xls|xlsm|doc|docx|ppt|pptx|pdf|jpg|jpeg|gif|png';
	private $upload_types          = '*';	//パワポがうまく取れない
//	private $upload_error_messages = array( 		//アップロードエラー一覧
//										1=> 'ファイルサイズが大きすぎます', 
//											'ファイルサイズが大きすぎます', 
//											'ファイルが途中までしかアップロードされていません', 
//											'ファイルを指定してください', 
//										6=> 'テンポラリフォルダがありません', 
//											'書き込みに失敗しました', 
//											'内部エラーのため、アップロードを中止しました'
//										);
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		// [2012/10/03]
		$this->load->helper('string_inspection_helper');
		
		//資料アップロードディレクトリの指定（appconfig.phpより取得）
		$this->book_library_dir = $this->config->item('book_library_dir');
		
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
			if($work_auth['book_library'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の図書室：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))){
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	//一覧表示
	// [2012/10/12]検索条件、講座・タグの追加
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

		$this->form_validation->set_rules('s_cource'    , $this->lang->line_or_def('common_course_name','講座名')    , 'trim');
		$this->form_validation->set_rules('s_tag'       , $this->lang->line_or_def('common_tag','タグ')              , 'trim');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_book_library');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('book_library_search_cond') ?: array(
				's_cource' => '',
				's_tag'=>'',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_cource']    = ($this->input->post('s_cource') ?? '');
			$data['s_tag']       = ($this->input->post('s_tag') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('book_library_search_cond', $data);
		}

		$book_library_list = $this->model_book_library->get_book_library_list(array(
			'school_id'     => $this->libauth->get_school_id(),
			'offset'        => $offset,
			'rowcount'      => $per_page,
			's_cource'      => $data['s_cource'],	//search
			's_tag'         => $data['s_tag'],	//search
			's_free_word'   => $data['s_free_word'],//search
		));

		$data['book_library_list'] = $book_library_list['items'];
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_book_library/index';
		$config['per_page']   = $per_page;
		$config['total_rows']   = $book_library_list['cnt'];
		$config['first_link']   = '&lt;&lt;';
		$config['last_link']    = '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		// [2012/10/12]
		if(count($_GET)){
			$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1?'.http_build_query($_GET).'$2', $data['pagination']);
		}
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_book_library/index',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/08/20]図書室講座マスタの取得処理の追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//モデル読み込み
		$this->load->model('model_book_library');

		// 権限確認
		$auth_param = array(
			'login_teacher_id' => $this->libauth->get_teacher_id(),
		);
		//データ削除
//		$auth_data = $this->model_book_library->material_newdata_auth_check($auth_param);
//
//		// 権限がない場合、エラー画面を表示
//		if($auth_data['auth_newdata'] == 0){
//			//戻り先設定
//			$data['returnurl']     = site_url('cms_book_library');
//			$data['error_message'] = '新規登録権限がありません<br />ログインし直してください';
//			
//			//ビュー設定引数設定
//			$disp_param = array(
//							'view_name'   => 'material_error',
//							'submenu_idx' => 4,
//							'view_data'   => $data,
//						);
//			//確認フォーム表示
//			$this->_display_view($disp_param);
//		}else{
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
			
			//初期表示設定
			$data['book_library']['update_flg']               = 0;
			$data['book_library']['book_library_id']          = 0;
			$data['book_library']['book_library_name']        = '';
			$data['book_library']['book_library_logic_name']  = '';
			$data['book_library']['book_library_lectures']    = array();	//[2012/08/20]
			$data['book_library']['book_library_caption']     = '';
			$data['book_library']['book_library_tags']        = '';
			$data['book_library']['local_reading_flag']       = '0';
			$data['book_library']['local_reading_open']       = '2000/01/01 00:00:00';
			$data['book_library']['local_reading_close']      = date("Y/m/d H:i:s",strtotime("+1 month"));
			$data['book_library']['local_reading_close_flag'] = '0';
			$data['book_library']['local_file']               = '';
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['book_library']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_book_library/edit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//ビュー設定
			$this->_display_view($disp_param);
//		}
	}
	
	//----------------------------------------------
	//修正フォーム表示
	//----------------------------------------------
	function edit(){
		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['book_library_id']) &&  $edit_form_data['book_library_id'] <> ''){
			//画面表示用データ設定
			$data['book_library'] = $edit_form_data;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_book_library/edit',
							'submenu_idx' => ($data['book_library']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						//	'class_id'    => $data['book_library']['class_id'],
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_book_library');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//詳細表示フォーム表示
	// [2012/08/20]図書室講座マスタの取得処理を追加
	//----------------------------------------------
	function detail($book_library_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_book_library');
		
		//データ取得
		$db_data = $this->model_book_library->get_data(array(
			'book_library_id'	=> $book_library_id,
		));

		if(count($db_data) > 0){
			//[20120615]生徒ログテーブルから図書室閲覧履歴を取得
			$this->load->model('model_applog_student');
			$log_datas = $this->model_applog_student->get_book_library_log(array(
				'book_library_id'	=> $book_library_id,
			));
			$library_logs=array();
			if(count($log_datas) > 0){
				foreach($log_datas as $log_data){
					$temp['student_id']   = $log_data['student_id'];
					$temp['student_name'] = $log_data['student_name'];
					$temp['added_at']     = $log_data['added_at'];
					array_push($library_logs,$temp);
				}
			}
			
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['book_library']['update_flg']               = 1;
			$data['book_library']['book_library_id']          = $db_data['book_library_id'];
			$data['book_library']['book_library_name']        = $db_data['book_library_name'];
			$data['book_library']['book_library_logic_name']  = $db_data['book_library_logic_name'];
			$data['book_library']['book_library_caption']     = $db_data['book_library_caption'];
			$data['book_library']['book_library_tags']        = $db_data['book_library_tags'];
			$data['book_library']['local_reading_flag']       = $db_data['local_reading_flag'];
			$data['book_library']['local_reading_open']       = $db_data['local_reading_open'];
			$data['book_library']['local_reading_close']      = $db_data['local_reading_close'];
			$data['book_library']['local_reading_close_flag'] = $db_data['local_reading_close_flag'];
			$data['book_library']['teacher_name']             = $db_data['teacher_name'];
			$data['book_library']['local_file']               = '';
			
			$data['library_logs'] = $library_logs;

			//[2012/08/20]図書室講座マスタの取得処理
			$data['book_library']['book_library_lectures']       = array();
			//  図書室講座マスタから講座ID取得
			$lectures = $this->model_book_library->get_book_library_lectures($db_data);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['book_library']['book_library_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			//  講座IDの講座名の取得
			$data['book_library']['book_library_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['book_library']['book_library_lectures']){
				foreach($data['book_library']['book_library_lectures'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['book_library']['book_library_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['book_library']['book_library_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				asort($data['book_library']['book_library_lectures_name']);
			}

			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['book_library']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_book_library/confirm',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_book_library/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/08/20]図書室講座マスタ更新処理を追加
	// [2012/10/03]図書室タグ精査の追加・図書室検索インデックス更新処理を追加
	//----------------------------------------------
	function commit(){
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
		$this->form_validation->set_rules('update_flg'              , $this->lang->line_or_def('common_flg','flg')                , 'trim|numeric');
		$this->form_validation->set_rules('book_library_id'         , $this->lang->line_or_def('common_book_library_id','資料ID') , 'trim|numeric');
		$this->form_validation->set_rules('book_library_logic_name' , $this->lang->line_or_def('common_file_name','ファイル名')   , 'trim|required'); // 論理ファイル名
		$this->form_validation->set_rules('book_library_caption'    , $this->lang->line_or_def('common_caption','説明')           , 'trim');
		$this->form_validation->set_rules('book_library_tags'       , $this->lang->line_or_def('common_tag','タグ')               , 'trim');
		$this->form_validation->set_rules('book_library_lectures[]'   , $this->lang->line_or_def('common_position_course','所属講座')  , 'callback_check_required_checkbox');	//[2012/08/20]
		$this->form_validation->set_rules('local_reading_flag'      , $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧')     , 'trim|required');
		$this->form_validation->set_rules('local_reading_open'      , $this->lang->line_or_def('common_local_reading_start','ローカル閲覧開始') , 'trim|required|callback_datetime_check');
		$this->form_validation->set_rules('local_reading_close'     , $this->lang->line_or_def('common_local_reading_end','ローカル閲覧終了') , 'trim|callback_datetime_check|callback_period_check[local_reading_open]');
		$this->form_validation->set_rules('local_file'              , $this->lang->line_or_def('common_file','ファイル')          , 'trim');
		
		//検証（isset($_FILES['local_file']) により資料更新時にファイルチェックを行わない）
		if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0)){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['book_library']['update_flg']               = $this->input->post('update_flg');
			$data['book_library']['book_library_id']          = $this->input->post('book_library_id');
			$data['book_library']['book_library_logic_name']  = $this->input->post('book_library_logic_name');
			$data['book_library']['book_library_caption']     = '';
			$data['book_library']['book_library_tags']        = '';
			$data['book_library']['book_library_lectures']    = $this->input->post('book_library_lectures')?$this->input->post('book_library_lectures'):array();
			$data['book_library']['local_reading_flag']       = '';
			$data['book_library']['local_reading_open']       = '';
			$data['book_library']['local_reading_close']      = '';
			$data['book_library']['local_reading_close_flag'] = $this->input->post('local_reading_close_flag');
			$data['book_library']['local_file']               = '';
			//エラーメッセージ設定
		//	$data['upload_error'] = $this->upload_error_messages[$_FILES['local_file']['error']];
			if( isset($_FILES['local_file']) ) {
				$data['upload_error'] = $upload_error_messages[$_FILES['local_file']['error']];
			}
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_book_library/edit',
							'submenu_idx' => ($data['book_library']['update_flg']==0 ? 3 : 4),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
			return;
		}else{
			//モデル読み込み
			$this->load->model('model_book_library');
			
			//更新用データ設定
			$data['book_library']['update_flg']              = $this->input->post('update_flg');
			$data['book_library']['book_library_id']         = $this->input->post('book_library_id');
			$data['book_library']['book_library_logic_name'] = $this->input->post('book_library_logic_name');
			$data['book_library']['book_library_caption']    = $this->input->post('book_library_caption');
		//	$data['book_library']['book_library_tags']       = $this->input->post('book_library_tags');
			$data['book_library']['book_library_tags']       = tags_inspection($this->input->post('book_library_tags'));
			$data['book_library']['book_library_lectures']   = $this->input->post('book_library_lectures');

			$data['book_library']['local_reading_flag']      = $this->input->post('local_reading_flag');
			if($this->input->post('local_reading_flag') == '0'){
				// ローカル閲覧：禁止
				$data['book_library']['local_reading_open']  = '2000/01/01 00:00:00';
				$data['book_library']['local_reading_close'] = NULL;
			}else{
				// ローカル閲覧：許可
				$data['book_library']['local_reading_open']  = $this->input->post('local_reading_open');
				if($this->input->post('local_reading_close_flag') == '1'){
					//閲覧期限なし
					$data['book_library']['local_reading_close']     = NULL;
				}else{
					//閲覧期限あり
					//[2012/08/20]「0000/00/00 00:00:00」がきた場合、NULL変換　0000/00/00 00:00:00
					$tempDate = $this->input->post('local_reading_close');
					if( strlen(trim($tempDate)) == 0 ){
						$data['book_library']['local_reading_close']     = NULL;
					}else{
						$data['book_library']['local_reading_close']     = $this->input->post('local_reading_close');
					}
				//	$data['book_library']['local_reading_close']     = $this->input->post('local_reading_close');
				}
			}

			$data['book_library']['local_file']              = $this->input->post('local_file');
			$data['book_library']['school_id']			     = $this->libauth->get_school_id();
			if($this->libauth->get_teacher_id() > 0){
				$data['book_library']['teacher_id'] = $this->libauth->get_teacher_id();
			}else{
				$data['book_library']['teacher_id'] = NULL;
			}

			//データ更新
			$retdata = $this->model_book_library->update_book_library($data['book_library']);
			//[2012/08/20]ビデオ講座マスタ更新処理
			$data_param = array(
							'book_library_id' => $retdata['lastInsertId'],
							'data'            => $data['book_library'],
						);
			$this->model_book_library->update_book_library_lectures($data_param);
			$this->model_book_library->update_book_library_search_index($data_param);

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_book_library/commit',
							'submenu_idx' => 4,
							'view_data'   => $data,
						//	'class_id'    => $this->input->post('class_id'),
						);
			//完了フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($book_library_id){
		// load language
		$this->lang->load('error');
		
		//モデル読み込み
		$this->load->model('model_book_library');

		// 権限確認
		$auth_data = $this->model_book_library->book_library_delete_auth_check(array(
			'book_library_id' => $book_library_id,
		));

		// 削除権限がない場合、エラー画面を表示
		if($auth_data['auth_delete'] == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_book_library');
			$data['error_message'] = $this->lang->line_or_def('error_del_auth','削除権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'book_library_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ削除
			$data = $this->model_book_library->delete_item(array(
				'book_library_id' => $book_library_id,
			));
			//資料一覧リスト画面表示
			//完了フォーム表示
			$this->_display_view(array(
				'view_name'   => 'cms_book_library/commit',
				'submenu_idx' => 4,
				'view_data'   => array(),
			//	'class_id'    => $this->input->post('class_id'),
			));

			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
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
			case 1://授業検索
				$sub_menu[1] = "検索";
				break;
			
			case 2://本一覧
				$sub_menu[1] = anchor("cms_book_library", "検索");
				$sub_menu[2] = "一覧";
				$sub_menu[3] = anchor("cms_book_library/newdata/", "新規登録");
				break;
			
			case 3://資料新規登録
				$sub_menu[1] = anchor("cms_book_library/", "検索");;
				$sub_menu[2] = anchor("cms_book_library/", "一覧");
				$sub_menu[3] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_book_library/", "検索");;
				$sub_menu[2] = anchor("cms_book_library/", "一覧");
				$sub_menu[3] = anchor("cms_book_library/newdata/", "新規登録");
		}
		return $sub_menu;
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
						//	'class_id'    => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//講座ドロップダウン用データ取得
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);

		//タグドロップダウン用データ取得
		$param['view_data']['tags_dropdown']  = $this->_get_tag_list_array($drop_param);
		
		//講師ドロップダウン用データ取得
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		//受講者チェックボックス用データ取得
		$this->load->model('model_student');
		$param['view_data']['students'] = $this->model_student->get_student_checkbox_list($drop_param);
		
		//授業名設定
		//モデル読み込み
		$this->load->model('model_class');
		//データ取得用引数設定
	//	$data_param = array(
	//					'class_id' => $param['class_id'],
	//				);
		//データ取得
	//	$param['view_data']['class_name'] = $this->model_class->get_name($param);
	//	$param['view_data']['class_id']   = $param['class_id'];
		
		// [2012/08/20]講座チェックボックス用データ取得
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}
	
	//----------------------------------------------
	// [2012/10/12]タグ一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_tag_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_book_library');
		
		//一覧ドロップダウン生成
		$tags         = $this->model_book_library->get_tag_dropdown_list($param);
		$data['tags'] = array();

		if( count($tags) != 0 ) {
			$data['tags']['']='';
			if(isset($tags['タグなし'])){
				$data['tags']['タグなし'] = 'タグなし';
				unset($tags['タグなし']);
			}
			foreach($tags as $tagKey => $cnt){
				$data['tags'][$tagKey] = $tagKey;
			}
		}

		return $data['tags'];
	}
	
	//----------------------------------------------
	//講座一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_cource_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_cource');
		
		//一覧ドロップダウン生成
		$data['cource_list'] = $this->model_cource->get_cource_dropdown_list($param);
		$data['cources'] = array();
		if( count($data['cource_list']) != 0 ) {
			$data['cources'][''] = '';
			foreach ( $data['cource_list'] as $cource ) {
				$data['cources'][$cource['cource_id']] = $cource['cource_name'];
			}
		}
		
		return $data['cources'];
	}
	
	//----------------------------------------------
	//担当講師一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_teacher_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_teacher');
		
		//一覧ドロップダウン生成
		$data['teacher_list'] = $this->model_teacher->get_teacher_dropdown_list($param);
		$data['teachers'] = array();
		if( count($data['teacher_list']) != 0 ) {
			$data['teachers'][''] = '';
			foreach ( $data['teacher_list'] as $teacher ) {
				$data['teachers'][$teacher['teacher_id']] = $teacher['teacher_name'];
			}
		}
		
		return $data['teachers'];
	}
	
	//----------------------------------------------
	//日付形式チェック
	//----------------------------------------------
	function date_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('date_check', $this->lang->line_or_def('error_date','%sの日付の形式が間違っています。') );
		
		//入力パターン正規表現設定("9999/99/99")
		$reg_pat = "^([0-9]{4})[-/ \.]([01]?[0-9])[-/ \.]([0123]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return checkdate($parts[2], $parts[3], $parts[1]);
		} else {
			return FALSE;
		}
	}
	
	//----------------------------------------------
	//日付時刻形式チェック
	//----------------------------------------------
	function datetime_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_datetime','%sの日時の形式が間違っています。') );
		
		//空白文字列がきた場合はTRUEを返す
		if (empty($date)) {
			return TRUE;
		}
		
		//入力パターン正規表現設定("9999/99/99 99:99:99")
		$reg_pat = "^([0-9]{4})[-/ \.]([01]?[0-9])[-/ \.]([0123]?[0-9])[ ]([012]?[0-9])[:\.]([0-6]?[0-9])[:\.]([0-6]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return checkdate($parts[2], $parts[3], $parts[1]) && $this->_checktime($parts[4], $parts[5], $parts[6]);
		} else {
			return FALSE;
		}
	}
	
	//----------------------------------------------
	//時刻形式チェック
	//----------------------------------------------
	function time_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_time','%sの時刻の形式が間違っています。') );
		
		//入力パターン正規表現設定("99:99:99")
		$reg_pat = "^([012]?[0-9])[:\.]([0-6]?[0-9])[:\.]([0-6]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return $this->_checktime($parts[1], $parts[2], $parts[3]);
		} else {
			return FALSE;
		}
	}
	
	//----------------------------------------------
	//期間チェック
	//----------------------------------------------
	function period_check($eddate, $stdate){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('period_check', $this->lang->line_or_def('error_period','期間の指定が間違っています。') );
		
		//空白文字列がきた場合はTRUEを返す
		if (empty($eddate)) {
			return TRUE;
		}
		
		//削除セパレータ文字設定("/:- ")
		$rep_str = array('/', ':', '-', ' ');
		
		//入力文字列を取得
		$start_date = $this->input->post($stdate);
		$end_date   = $eddate;
		
		//セパレータを削除
		$start_date = str_replace($rep_str,'',$start_date);
		$end_date   = str_replace($rep_str,'',$end_date);
		
		//判定
		if ($start_date > $end_date){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	//----------------------------------------------
	//時間チェック
	//----------------------------------------------
	function _checktime($hour, $min, $sec){
		if ($hour < 0 || $hour > 23){
			return FALSE;
		}
		if ($min < 0 || $min > 59){
			return FALSE;
		}
		if ($sec < 0 || $sec > 59){
			return FALSE;
		}
		return TRUE;
	}

	// コールバック関数
	public function check_required_checkbox($input) {
		if (!is_array($input)) {
			if( !empty($input) ){
				$temp = [$input];
				$input = $temp;
			}
		}
		if (!is_array($input) || count($input) == 0) {
			$this->form_validation->set_message('check_required_checkbox', '所属講座 欄は必須です。');
			return false;
		}
		return true;
	}
	
	//----------------------------------------------
	//本ダウンロード
	//----------------------------------------------
	function download_file($book_library_id){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//データ取得用引数設定
		$param = array(
			'login_teacher_id' => $this->libauth->get_teacher_id(),
			'book_library_id'  => $book_library_id,
		);

		$this->load->model('model_book_library');
		//データ取得
		$db_data = $this->model_book_library->get_book_library_filename($param);

		// 物理資料ファイル名なし：権限無効
		if($db_data['book_library_name'] == ''){
			//戻り先設定
			$data['returnurl']     = site_url('cms_book_library');
			$data['error_message'] = $this->lang->line_or_def('error_download_auth','ダウンロード権限がありません<br />ログインし直してください').$db_data['remarks'];
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'book_library_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			// ダウンロード時のファイル名（拡張子なし）の作成
			// └ 論理資料ファイル名
			//    論理資料ファイル名がない場合は、物理資料ファイル名（拡張子なし）
			if($db_data['book_library_logic_name'] == ''){
				$position  = strrpos($db_data['book_library_name'], '.');
				$extension = substr($db_data['book_library_name'], 0, $position - 1);
				$db_data['book_library_logic_name'] = $extension;
			}
			
			// ファイルフルパス設定
			$file = $this->book_library_dir.'/'.$book_library_id.'/'.$db_data['book_library_name'];
			
			// ダウンロード処理（ファイル存在確認必須）
			if (file_exists($file)) {
				// 圧縮形式にてダウンロード
				$result_data = $this->_make_zip_data(
											$book_library_id, 
											$db_data['book_library_name'], 
											$db_data['book_library_logic_name']);
			}else{
				//戻り先設定
				$data['returnurl']     = site_url('cms_book_library');
				$data['error_message'] = $this->lang->line_or_def('error_not_file','ファイルが見つかりません<br />管理者に問い合わせてください')
										.'<br /><br />'.$this->lang->line_or_def('common_file_name','ファイル名').'&nbsp;:&nbsp;'.$db_data['book_library_logic_name']
										.'<br />File Name&nbsp;:&nbsp;'.$db_data['book_library_name'];
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'book_library_error',
								'submenu_idx' => 4,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}
		}
	}
	
	//----------------------------------------------
	//本ダウンロード（ZIP）
	//  └ 対象はマスタファイルのみ
	//----------------------------------------------
	function _make_zip_data($book_library_id, $book_library_name, $book_library_logic_name){
		
		//zipライブラリのロード
		$this->load->library('zip');
		
		//対象ファイルの内容読み込み
		$data = read_file($this->book_library_dir.'/'.$book_library_id.'/'.$book_library_name);
		
		//zip内ファイル名の設定
	#	$filename = mb_convert_encoding($book_library_name, 'SJIS', 'UTF-8');
		$position  = strrpos($book_library_name, '.');
		$extension = substr($book_library_name, $position, 100);
		$filename  = $book_library_logic_name.$extension;
		$filename  = mb_convert_encoding($filename, 'SJIS', 'UTF-8');
		
		//zipファイルに追加
		$this->zip->add_data($filename, $data);
		
		//zipのダウンロード
		$this->zip->download($book_library_logic_name.'.zip');
		
		//キャッシュのクリア
		$this->zip->clear_data();
	}
	
} 

/*End of File program.php*/
