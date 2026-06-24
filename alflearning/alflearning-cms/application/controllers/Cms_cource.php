<?php
#[AllowDynamicProperties]
class Cms_cource extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $day_second         = 86400;		//日数計算用、一日秒数
	
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

			if($work_auth['course'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の授業：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
	//	$this->load->model('Modelschoolcontract');
	//	if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
	//		redirect('admin_top');
	//	}
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
		$this->form_validation->set_rules('s_name'      , $this->lang->line_or_def('common_course_name','講座名')               , 'trim|xss_clean');
		$this->form_validation->set_rules('s_open'      , $this->lang->line_or_def('common_public_period_start','公開期間開始') , 'trim|xss_clean');
		$this->form_validation->set_rules('s_close'     , $this->lang->line_or_def('common_public_period_end','公開期間終了')   , 'trim|xss_clean');
		$this->form_validation->set_rules('s_id'        , $this->lang->line_or_def('common_id','ID')                            , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード')            , 'trim|xss_clean');
		$this->form_validation->run();	//バリデーション実行（その実xss対策）

		//講座モデル読み込み
		$this->load->model('model_cource');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('cource_search_cond') ?: array(
				's_name' => '',
				's_open' => '',
				's_close' => '',
				's_id' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_name']      = ($this->input->post('s_name') ?? '');
			$data['s_open']      = ($this->input->post('s_open') ?? '');
			$data['s_close']     = ($this->input->post('s_close') ?? '');
			$data['s_id']        = ($this->input->post('s_id') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('cource_search_cond', $data);
		}

		$data_param = array(
			's_school_id'    => $this->libauth->get_school_id(),
			's_cource_name'  => $data['s_name'],
			's_cource_open'  => $data['s_open'],
			's_cource_close' => $data['s_close'],
			's_cource_id'    => $data['s_id'],
			's_free_word'    => $data['s_free_word'],
			'offset'         => $offset,
			'rowcount'       => $per_page,
		);

		//データ取得
		$cource_list = $this->model_cource->get_cource_search_list($data_param);
		$data['cource_list'] = $cource_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_cource/index';
		$config['total_rows'] = $this->model_cource->get_cource_search_count($data_param);
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
						'view_name'   => 'cms_cource/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/11/20]パラメータ追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['cource']['update_flg']					= 0;
		$data['cource']['cource_id']					= 0;
		$data['cource']['cource_name']					= '';
		$data['cource']['cource_open']					= '1900/01/01 00:00:00';
		$data['cource']['cource_close']					= '2100/12/31 23:59:59';
		$data['cource']['cource_caption']				= '';
		$data['cource']['cource_note']					= '';
		$data['cource']['lecture_students']				= array();
		$data['cource']['lecture_students_name']		= '---';
		$data['cource']['lecture_materials']			= array();
		$data['cource']['lecture_materials_name']		= '---';
		$data['cource']['lecture_book_librarys']		= array();
		$data['cource']['lecture_book_librarys_name']	= '---';
		$data['cource']['lecture_videos']				= array();
		$data['cource']['lecture_videos_name']			= '---';

		//モデル読み込み
		$this->load->model('model_cource');
		
		//データ取得
		$all_count = $this->model_cource->get_effective_items(
			array(
				'school_id' => $this->libauth->get_school_id(),
				)
		);
		$data['cource']['student_all_count']		=$all_count['student_all_count'];
		$data['cource']['material_all_count']		=$all_count['material_all_count'];
		$data['cource']['book_library_all_count']	=$all_count['book_library_all_count'];
		$data['cource']['video_all_count']			=$all_count['video_all_count'];
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_cource/edit',
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
		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['cource_id']) &&  $edit_form_data['cource_id'] <> ''){
			//画面表示用データ設定
			$data['cource'] = $edit_form_data;

			//モデル読み込み
			$this->load->model('model_cource');
			
			//データ取得
			$all_count = $this->model_cource->get_effective_items(
				array(
					'school_id' => $this->libauth->get_school_id(),
					)
			);
			$data['cource']['student_all_count']		=$all_count['student_all_count'];
			$data['cource']['material_all_count']		=$all_count['material_all_count'];
			$data['cource']['book_library_all_count']	=$all_count['book_library_all_count'];
			$data['cource']['video_all_count']			=$all_count['video_all_count'];

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_cource/edit',
							'submenu_idx' => ($data['cource']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cource');
			
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
	//詳細表示フォーム表示
	// [2012/11/20]講座所属受講者ID・受講者名取得処理を追加
	// [2012/11/30]選択された各所属情報をJQuery経由で取得するように変更
	//----------------------------------------------
	function detail($cource_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_cource');
		
		//データ取得用引数設定
		$data_param = array(
						'cource_id' => $cource_id,
					);
		//データ取得
		$db_data = $this->model_cource->get_cource($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['cource']['update_flg']     = 1;
			$data['cource']['cource_id']      = $db_data['cource_id'];
			$data['cource']['cource_name']    = $db_data['cource_name'];
			$data['cource']['cource_open']    = $db_data['cource_open'];
			$data['cource']['cource_close']   = $db_data['cource_close'];
			$data['cource']['cource_caption'] = $db_data['cource_caption'];
			$data['cource']['cource_note']    = $db_data['cource_note'];

			// [2012/11/20]講座所属の各情報の取得
			$data_param = array(
							'school_id' => $this->libauth->get_school_id(),
							'cource_id' => $db_data['cource_id'],
						);

			// 講座所属の受講者ID・受講者名（表示用）の取得
			$data_results = $this->model_cource->get_student_lecture($data_param);
			$data['cource']['lecture_students']      = array();
			$data['cource']['lecture_students_name'] = '---';

			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['cource']['lecture_students'][$idx]    = $data_result['student_id'];
					if($data['cource']['lecture_students_name'] == '---'){
						$data['cource']['lecture_students_name'] = '[No'.$data_result['student_id'].']&nbsp;'.$data_result['student_name'].'&nbsp;&lt;'.$data_result['student_email'].'&gt;';
					}else{
						$data['cource']['lecture_students_name'] .= '<br/>'.'[No'.$data_result['student_id'].']&nbsp;'.$data_result['student_name'].'&nbsp;&lt;'.$data_result['student_email'].'&gt;';
					}
				}
			}

			// 講座所属の資料ID・資料論理名（表示用）の取得
			$data_results = $this->model_cource->get_material_lecture($data_param);
			$data['cource']['lecture_materials']      = array();
			$data['cource']['lecture_materials_name'] = '---';

			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['cource']['lecture_materials'][$idx]    = $data_result['material_id'];
					if($data['cource']['lecture_materials_name'] == '---'){
						$data['cource']['lecture_materials_name'] = '[No'.$data_result['material_id'].']&nbsp;'.$data_result['material_logic_name'];
					}else{
						$data['cource']['lecture_materials_name'] .= '<br/>'.'[No'.$data_result['material_id'].']&nbsp;'.$data_result['material_logic_name'];
					}
				}
			}

			// 講座所属の図書室ID・図書室論理名（表示用）の取得
			$data_results = $this->model_cource->get_book_library_lecture($data_param);
			$data['cource']['lecture_book_librarys']      = array();
			$data['cource']['lecture_book_librarys_name'] = '---';

			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['cource']['lecture_book_librarys'][$idx]    = $data_result['book_library_id'];
					if($data['cource']['lecture_book_librarys_name'] == '---'){
						$data['cource']['lecture_book_librarys_name'] = '[No'.$data_result['book_library_id'].']&nbsp;'.$data_result['book_library_logic_name'];
					}else{
						$data['cource']['lecture_book_librarys_name'] .= '<br/>'.'[No'.$data_result['book_library_id'].']&nbsp;'.$data_result['book_library_logic_name'];
					}
				}
			}

			// 講座所属のビデオID・ビデオ論理名（表示用）の取得
			$data_results = $this->model_cource->get_video_lecture($data_param);
			$data['cource']['lecture_videos']      = array();
			$data['cource']['lecture_videos_name'] = '---';

			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['cource']['lecture_videos'][$idx]    = $data_result['video_id'];
					if($data['cource']['lecture_videos_name'] == '---'){
						$data['cource']['lecture_videos_name'] = '[No'.$data_result['video_id'].']&nbsp;'.$data_result['video_logic_name'];
					}else{
						$data['cource']['lecture_videos_name'] .= '<br/>'.'[No'.$data_result['video_id'].']&nbsp;'.$data_result['video_logic_name'];
					}
				}
			}

			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['cource']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_cource/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_cource/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/11/20]パラメータ追加
	// [2012/11/30]選択された各所属情報をJQuery経由で取得するように変更
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'      , $this->lang->line_or_def('common_flg','flg')                          , 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('cource_id'       , $this->lang->line_or_def('common_id','ID')                            , 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('cource_name'     , $this->lang->line_or_def('common_course_name','講座名')               , 'trim|required|xss_clean');
		$this->form_validation->set_rules('cource_open'     , $this->lang->line_or_def('common_public_period_start','公開期間開始') , 'trim|required|callback_datetime_check|xss_clean');
		$this->form_validation->set_rules('cource_close'    , $this->lang->line_or_def('common_public_period_end','公開期間終了')   , 'trim|required|callback_datetime_check|callback_period_check[cource_open]|xss_clean');
		$this->form_validation->set_rules('cource_caption'  , $this->lang->line_or_def('common_caption','説明')                     , 'trim|xss_clean');
		$this->form_validation->set_rules('cource_note'     , $this->lang->line_or_def('common_note','備考')                        , 'trim|xss_clean');
		$this->form_validation->set_rules('lecture_students'      , $this->lang->line_or_def('common_student','受講者')             , 'xss_clean');
		$this->form_validation->set_rules('lecture_materials'     , $this->lang->line_or_def('common_material','資料')              , 'xss_clean');
		$this->form_validation->set_rules('lecture_book_librarys' , $this->lang->line_or_def('common_book_library','図書室')        , 'xss_clean');
		$this->form_validation->set_rules('lecture_videos'        , $this->lang->line_or_def('common_common_video','ビデオ')        , 'xss_clean');

		// [2012/11/30] [Ajax]選択された受講者IDの整形（取得・ID昇順）
		$lecture_students = $this->input->post('lecture_students_array')?$this->input->post('lecture_students_array'):array();
		sort($lecture_students);

		// [2012/11/30] [Ajax]選択された資料IDの整形
		$lecture_materials = $this->input->post('lecture_materials_array')?$this->input->post('lecture_materials_array'):array();
		sort($lecture_materials);

		// [2012/11/30] [Ajax]選択された図書室IDの整形
		$lecture_book_librarys = $this->input->post('lecture_book_librarys_array')?$this->input->post('lecture_book_librarys_array'):array();
		sort($lecture_book_librarys);

		// [2012/11/30] [Ajax]選択されたビデオIDの整形
		$lecture_videos = $this->input->post('lecture_videos_array')?$this->input->post('lecture_videos_array'):array();
		sort($lecture_videos);

		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['cource']['update_flg']     = $this->input->post('update_flg');
			$data['cource']['cource_id']      = $this->input->post('cource_id');
			$data['cource']['cource_name']    = '';
			$data['cource']['cource_open']    = '';
			$data['cource']['cource_close']   = '';
			$data['cource']['cource_caption'] = '';
			$data['cource']['cource_note']    = '';
			$data['cource']['lecture_students']      = $lecture_students;		//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
			$data['cource']['lecture_materials']     = $lecture_materials;		//$this->input->post('lecture_materials')?$this->input->post('lecture_materials'):array();
			$data['cource']['lecture_book_librarys'] = $lecture_book_librarys;	//$this->input->post('lecture_book_librarys')?$this->input->post('lecture_book_librarys'):array();
			$data['cource']['lecture_videos']        = $lecture_videos;			//$this->input->post('lecture_videos')?$this->input->post('lecture_videos'):array();

			//モデル読み込み
			$this->load->model('model_cource');

			//データ取得
			$all_count = $this->model_cource->get_effective_items(
				array(
					'school_id' => $this->libauth->get_school_id(),
					)
			);
			$data['cource']['student_all_count']		=$all_count['student_all_count'];
			$data['cource']['material_all_count']		=$all_count['material_all_count'];
			$data['cource']['book_library_all_count']	=$all_count['book_library_all_count'];
			$data['cource']['video_all_count']			=$all_count['video_all_count'];

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_cource/edit',
							'submenu_idx' => ($data['cource']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['cource']['update_flg']     = $this->input->post('update_flg');
			$data['cource']['cource_id']      = $this->input->post('cource_id');
			$data['cource']['cource_name']    = $this->input->post('cource_name');
			$data['cource']['cource_open']    = $this->input->post('cource_open');
			$data['cource']['cource_close']   = $this->input->post('cource_close');
			$data['cource']['cource_caption'] = $this->input->post('cource_caption');
			$data['cource']['cource_note']    = $this->input->post('cource_note');
			$data['cource']['lecture_students']      = $lecture_students;		//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
			$data['cource']['lecture_materials']     = $lecture_materials;		//$this->input->post('lecture_materials')?$this->input->post('lecture_materials'):array();
			$data['cource']['lecture_book_librarys'] = $lecture_book_librarys;	//$this->input->post('lecture_book_librarys')?$this->input->post('lecture_book_librarys'):array();
			$data['cource']['lecture_videos']        = $lecture_videos;			//$this->input->post('lecture_videos')?$this->input->post('lecture_videos'):array();

			// 選択された受講者名の取得
			$data['cource']['lecture_students_name'] = '---';
			if($data['cource']['lecture_students']){
				$this->load->model('model_student');
				foreach($data['cource']['lecture_students'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'student_id' => $lecture,
								);
					$temp = $this->model_student->get_name($name_param);

					if($data['cource']['lecture_students_name'] == '---'){
						$data['cource']['lecture_students_name'] = '[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}else{
						$data['cource']['lecture_students_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}
				}
			}

			// 選択された資料論理名の取得
			$data['cource']['lecture_materials_name'] = '---';
			if($data['cource']['lecture_materials']){
				$this->load->model('model_material');
				foreach($data['cource']['lecture_materials'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'material_id' => $lecture,
								);
					$temp = $this->model_material->get_data($name_param);

					if($data['cource']['lecture_materials_name'] == '---'){
						$data['cource']['lecture_materials_name'] = '[No'.$lecture.']&nbsp;'.$temp['material_logic_name'];
					}else{
						$data['cource']['lecture_materials_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['material_logic_name'];
					}
				}
			}

			// 選択された図書室論理名の取得
			$data['cource']['lecture_book_librarys_name'] = '---';
			if($data['cource']['lecture_book_librarys']){
				$this->load->model('model_book_library');
				foreach($data['cource']['lecture_book_librarys'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'book_library_id' => $lecture,
								);
					$temp = $this->model_book_library->get_data($name_param);

					if($data['cource']['lecture_book_librarys_name'] == '---'){
						$data['cource']['lecture_book_librarys_name'] = '[No'.$lecture.']&nbsp;'.$temp['book_library_logic_name'];
					}else{
						$data['cource']['lecture_book_librarys_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['book_library_logic_name'];
					}
				}
			}

			// 選択されたビデオ論理名の取得
			$data['cource']['lecture_videos_name'] = '---';
			if($data['cource']['lecture_videos']){
				$this->load->model('model_video');
				foreach($data['cource']['lecture_videos'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'video_id' => $lecture,
								);
					$temp = $this->model_video->get_material($name_param);

					if($data['cource']['lecture_videos_name'] == '---'){
						$data['cource']['lecture_videos_name'] = '[No'.$lecture.']&nbsp;'.$temp['video_logic_name'];
					}else{
						$data['cource']['lecture_videos_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['video_logic_name'];
					}
				}
			}
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['cource']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_cource/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	// [2012/11/01]外部連携API実行エラー発生時、編集画面へ返す機能を追加
	//----------------------------------------------
	function commit(){
		
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['cource_id']) && $edit_form_data['cource_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			
			//モデル読み込み
			$this->load->model('model_cource');
			
			//データ更新用引数設定
			$data_param = array(
							'data' => $edit_form_data,
						);
			//データ更新（外部連携API実行結果含む）
			$data = $this->model_cource->update_cource($data_param);
			
			if($data['stat'] == 200){
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_cource/commit',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
				
				//セッションデータのクリア
				$this->session->unset_userdata('edit_form_data');
			}else{
				$result_data['cource']      = $edit_form_data;
				$result_data['elm_result']  = $data['result'];
				$result_data['elm_stat']    = $data['stat'];
				$result_data['elm_message'] = $data['message'];
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_cource/edit',
								'submenu_idx' => ($result_data['cource']['update_flg']==0 ? 2 : 3),
								'view_data'   => $result_data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_cource');
			
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
	//データ削除（論理削除）（2012/11/01現在、機能実装なし）
	//----------------------------------------------
	function delete_item($cource_id){
		//モデル読み込み
		$this->load->model('model_cource');
		
		//データ更新用引数設定
		$data_param = array(
						'cource_id' => $cource_id,
					);
		//データ削除
		$data = $this->model_cource->delete_cource($data_param);
		
		//リスト画面表示
		//$this->index();
		header("Location:/cms_cource/");
		exit();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
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
		
		//当月・前月リンク設定
		//現在日時取得
		$now = time();
		//当月・前月の1日を取得
		$thismonth = strtotime(date('Y/m/1', $now));
		$prevmonth = strtotime(date('Y/m/1', $thismonth - (1 * $this->day_second)));
		//各月のパラメータ用数値を設定(yyyymm形式）
		$thismonth_pram = date('Ym', $thismonth);
		$prevmonth_pram = date('Ym', $prevmonth);
		
		//アンカー設定
		$sub_menu[1] = anchor("cms_cource_class/index/" . $thismonth_pram, date('Y年m月', $thismonth));
		$sub_menu[2] = anchor("cms_cource_class/index/" . $prevmonth_pram, date('Y年m月', $prevmonth));
		
		switch($param['submenu_idx']){
			case 1://講座検索
				$sub_menu[3] = anchor("cms_class", "検索");;
				$sub_menu[4] = anchor("cms_class/newdata", "新規登録");
				$sub_menu[5] = "検索";
				$sub_menu[6] = anchor("cms_cource/newdata", "新規登録");
				break;
			
			case 2://新規登録
				$sub_menu[3] = anchor("cms_class", "検索");;
				$sub_menu[4] = anchor("cms_class/newdata", "新規登録");
				$sub_menu[5] = anchor("cms_cource", "検索");
				$sub_menu[6] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[3] = anchor("cms_class", "検索");;
				$sub_menu[4] = anchor("cms_class/newdata", "新規登録");
				$sub_menu[5] = anchor("cms_cource", "検索");
				$sub_menu[6] = anchor("cms_cource/newdata", "新規登録");;
				break;
		}
		return $sub_menu;
	}
	
	//----------------------------------------------
	//ビュー表示
	// [2012/11/20]受講者チェックボックス用データ取得の追加
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

		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//受講者チェックボックス用データ取得
		//$this->load->model('model_student');
		//$param['view_data']['students'] = $this->model_student->get_student_checkbox_list($drop_param);

		//資料チェックボックス用データ取得
		//$this->load->model('model_material');
		//$param['view_data']['materials'] = $this->model_material->get_material_checkbox_list($drop_param);

		//図書室チェックボックス用データ取得
		//$this->load->model('model_book_library');
		//$param['view_data']['book_librarys'] = $this->model_book_library->get_book_library_checkbox_list($drop_param);
				
		//ビデオチェックボックス用データ取得
		//$this->load->model('model_video');
		//$param['view_data']['videos'] = $this->model_video->get_video_checkbox_list($drop_param);
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}
	
	//----------------------------------------------
	//日付形式チェック
	//----------------------------------------------
	function date_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('date_check', $this->lang->line_or_def('error_date','%sの日付の形式が間違っています。'));
		
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
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_datetime','%sの日時の形式が間違っています。'));
		
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
	//期間チェック
	//----------------------------------------------
	function period_check($eddate, $stdate){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('period_check', $this->lang->line_or_def('error_period','期間の指定が間違っています。'));
		
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

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の受講者を取得
	//----------------------------------------------
	function get_cource_student(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					"cource_flag"	=>	$this->input->post('cource_flag')?$this->input->post('cource_flag'):0,
					);
		//print var_dump($drop_param);
//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();

		// 学校に属する受講者ID・受講者名を取得
		$this->load->model('model_student');
		$students = $this->model_student->get_cource_student($drop_param);
		//$param = json_encode($param);
		//print var_dump($param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($students));
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の資料を取得
	//----------------------------------------------
	function get_cource_material(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する資料ID・資料論理名を取得
		$this->load->model('model_material');
		$materials = $this->model_material->get_cource_material($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($materials));
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の図書室を取得
	//----------------------------------------------
	function get_cource_book_library(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する図書室ID・図書室論理名を取得
		$this->load->model('model_book_library');
		$book_librarys = $this->model_book_library->get_cource_book_library($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($book_librarys));
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属のビデオを取得
	//----------------------------------------------
	function get_cource_video(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属するビデオID・ビデオ論理名を取得
		$this->load->model('model_video');
		$videos = $this->model_video->get_cource_video($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($videos));
	}





	//----------------------------------------------
	// [Ajax用]学校所属の課題を取得
	//----------------------------------------------
	function get_cource_issue(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する課題ID・課題名を取得
		$this->load->model('model_issue');
		$issues = $this->model_issue->get_cource_issue($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($issues));
	}

	//----------------------------------------------
	// [Ajax用]学校所属の問題（テスト）を取得
	//----------------------------------------------
	function get_cource_exam(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する問題ID・問題名を取得
		$this->load->model('model_exam');
		$exams = $this->model_exam->get_cource_exam($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exams));
	}
	//----------------------------------------------
	// [Ajax用]学校所属の問題（テスト）を取得
	//----------------------------------------------
	function get_cource_exam2(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する問題ID・問題名を取得
		$this->load->model('model_exam2');
		$exam2s = $this->model_exam2->get_cource_exam2($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exam2s));
	}

	//----------------------------------------------
	// [Ajax用]学校所属の設問を取得
	//----------------------------------------------
	function get_cource_exam_problem(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"   => $this->libauth->get_school_id(),
					"cource_id"   => $this->input->post('cource_id'),
					"free_word"   => $this->input->post('free_word'),
					"cource_flag" => $this->input->post('cource_flag') ? intval($this->input->post('cource_flag')) : 0,
					'bar_association_id' => intval($this->libauth->get_bar_association_id()),
					);
		
		// 学校に属する設問ID・設問名を取得
		$this->load->model('model_exam_problem');
		$exam_problems = $this->model_exam_problem->get_cource_exam_problem($drop_param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exam_problems));
	}
	//----------------------------------------------
	// [Ajax用]学校所属の設問を取得
	//----------------------------------------------
	function get_cource_exam2_problem(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"   => $this->libauth->get_school_id(),
					"cource_id"   => $this->input->post('cource_id'),
					"free_word"   => $this->input->post('free_word'),
					"cource_flag" => $this->input->post('cource_flag') ? $this->input->post('cource_flag') : 0,
					'bar_association_id' => $this->libauth->get_bar_association_id(),
					);
		
		// 学校に属する設問ID・設問名を取得
		$this->load->model('model_exam2_problem');
		$exam2_problems = $this->model_exam2_problem->get_cource_exam2_problem($drop_param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exam2_problems));
	}

	//----------------------------------------------
	// [Ajax用]学校所属の掲示板を取得
	//----------------------------------------------
	function get_cource_bulletin_board(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属する掲示板ID・掲示板論理名を取得
		$this->load->model('model_bulletin_board');
		$bulletin_boards = $this->model_bulletin_board->get_cource_bulletin_board($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($bulletin_boards));
	}

	//----------------------------------------------
	// [Ajax用]学校所属のお知らせを取得
	//----------------------------------------------
	function get_cource_information(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"	=>	$this->libauth->get_school_id(),
					"cource_id"	=>	$this->input->post('cource_id'),
					"free_word"	=>	$this->input->post('free_word'),
					);

		// 学校に属するお知らせID・お知らせ名を取得
		$this->load->model('model_information');
		$informations = $this->model_information->get_cource_information($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($informations));
	}








	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の講師を取得（講座が複数、授業管理にて使用）
	//----------------------------------------------
	function get_cource_teacher_array(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"         => $this->libauth->get_school_id(),
					"cource_id"         => $this->input->post('cource_id'),
					"student_group_id"  => $this->input->post('student_group_id'),
					"free_word"         => $this->input->post('free_word'),
					"cource_flag"       => $this->input->post('cource_flag')?$this->input->post('cource_flag'):0,
					);
		//print var_dump($drop_param);
//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();

		// 学校に属する受講者ID・受講者名を取得
		$this->load->model('model_teacher');
		$teachers = $this->model_teacher->get_cource_teacher_array($drop_param);
		//$param = json_encode($param);
		//print var_dump($param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($teachers));
	}

	//----------------------------------------------
	// [Ajax用]学校所属の受講者を取得（講座が複数、授業管理にて使用）
	//----------------------------------------------
	function get_cource_student_array(){

		$this->load->helper('json');

		$drop_param = array(
					"school_id"         => $this->libauth->get_school_id(),
					"cource_id"         => $this->input->post('cource_id'),
					"student_group_id"  => $this->input->post('student_group_id'),
					"free_word"         => $this->input->post('free_word'),
					"cource_flag"       => $this->input->post('cource_flag')?$this->input->post('cource_flag'):0,
					'bar_association_id' => $this->libauth->get_bar_association_id(),
					);
		//print var_dump($drop_param);
//$this->input->post('lecture_students')?$this->input->post('lecture_students'):array();

		// 学校に属する受講者ID・受講者名を取得
		$this->load->model('model_student');
		$students = $this->model_student->get_cource_student_array($drop_param);
		//$param = json_encode($param);
		//print var_dump($param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($students));
	}


} 

/*End of File program.php*/
