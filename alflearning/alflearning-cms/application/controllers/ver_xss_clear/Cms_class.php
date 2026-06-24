<?php
#[AllowDynamicProperties]
class Cms_class extends CI_Controller {
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
		$this->form_validation->set_rules('s_cource'    , $this->lang->line_or_def('common_course_name','講座名')            , 'trim|xss_clean'); // '講座'
		$this->form_validation->set_rules('s_open'      , $this->lang->line_or_def('common_class_start_time','授業開始時間') , 'trim|xss_clean'); // '公開期間開始'
		$this->form_validation->set_rules('s_close'     , $this->lang->line_or_def('common_class_end_time','授業終了時間')   , 'trim|xss_clean'); // '公開期間終了'
		$this->form_validation->set_rules('s_teacher'   , $this->lang->line_or_def('common_management_teacher','管理講師')   , 'trim|xss_clean'); // [ver2.0]
		$this->form_validation->set_rules('s_id'        , $this->lang->line_or_def('common_id','ID')                         , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード')         , 'trim|xss_clean');
		$this->form_validation->run();	//バリデーション実行（その実xss対策）
		
		//授業モデル読み込み
		$this->load->model('model_class');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('class_search_cond') ?: array(
				's_cource' => '',
				's_open' => '',
				's_close' => '',
				's_teacher' => '',
				's_free_word' => '',
				's_id' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_cource']    = ($this->input->post('s_cource', TRUE) ?? '');
			$data['s_open']      = ($this->input->post('s_open', TRUE) ?? '');
			$data['s_close']     = ($this->input->post('s_close', TRUE) ?? '');
			$data['s_teacher']   = ($this->input->post('s_teacher', TRUE) ?? '');
			$data['s_id']        = ($this->input->post('s_id', TRUE) ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('class_search_cond', $data);
		}

		$data_param = array(
			's_school_id'   => $this->libauth->get_school_id(),
			's_cource'      => $data['s_cource'],
			's_class_open'  => $data['s_open'],
			's_class_close' => $data['s_close'],
			's_teacher'     => $data['s_teacher'],
			's_class_id'    => $data['s_id'],
			's_free_word'   => $data['s_free_word'],
			'offset'        => $offset,
			'rowcount'      => $per_page,
		);
		//データ取得
		$class_list = $this->model_class->get_class_search_list($data_param);
	//	$data['class_list'] = $class_list;
		
		// [2012/11/08]公開期間外の講座に所属する授業の判定処理（view側で色有無判定に使用）
		if($class_list){
			$this->load->model('model_cource');
			$class_list_data = array();
			foreach($class_list as $class) {
				$get_cource_name = $this->model_cource->get_name(array('cource_id'  => $class['cource_id']));
				$class['effective_cource'] = 0;
				if($get_cource_name){
					$class['effective_cource'] = 1;
				}
				array_push($class_list_data, $class);
			}
			$data['class_list'] = $class_list_data;
		}else{
			$data['class_list'] = $class_list;
		}
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_class/index';
		$config['total_rows'] = $this->model_class->get_class_search_count($data_param);
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
						'view_name'   => 'cms_class/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		$login_teacher_id    = $this->libauth->get_teacher_id();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['class']['update_flg']            = 0;
		$data['class']['class_id']              = 0;
		$data['class']['class_type']            = '';
		$data['class']['cource_id']             = '';
		$data['class']['class_date']            = '';
		$data['class']['class_opentime']        = '';
		$data['class']['class_closetime']       = '';
		$data['class']['class_name']            = '';
		$data['class']['teacher_id']            = $login_teacher_id;
		$data['class']['sub_teacher_id']        = array();	// [ver2.0]
		$data['class']['sub_teacher_name']      = '';		// [ver2.0]
		$data['class']['class_caption']         = '';
		$data['class']['lecture_students']      = array();
		$data['class']['lecture_students_name'] = '---';
//		$data['class']['fixed_number']          = '';
		$data['class']['class_note']            = '';
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_class/edit',
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
		// load language
		$this->lang->load('error');

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		//授業管理の権限有無確認（新規作成の修正の場合は、権限チェックなしとする）
		//$auth_class_material = $this->_get_auth_class($edit_form_data['class_id']);
		$auth_class_material = 1;
		if( $edit_form_data['update_flg']!=0){
			$auth_class_material = $this->_get_auth_class($edit_form_data['class_id']);
		}

		// 権限を持たない場合、エラーを返す
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			if(isset($edit_form_data['class_id']) &&  $edit_form_data['class_id'] <> ''){
				//画面表示用データ設定
				$data['class'] = $edit_form_data;
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_class/edit',
								'submenu_idx' => ($data['class']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
				
			}else{
				//セッションデータ無しはエラーフォーム表示
				//戻り先設定
				$data['returnurl'] = site_url('class');
				
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
	// 詳細表示フォーム表示
	// [2012/08/08]授業資料表示内容の修正
	//----------------------------------------------
	function detail($class_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_class');
		// load language
		$this->lang->load('common');
		
		//データ取得用引数設定
		$data_param = array(
						'class_id' => $class_id,
					);
		//データ取得
		$db_data = $this->model_class->get_class($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['class']['update_flg']       = 1;
			$data['class']['class_id']         = $db_data['class_id'];
			$data['class']['cource_id']        = $db_data['cource_id'];
			$data['class']['class_date']       = $db_data['class_date'];
			$data['class']['class_opentime']   = $db_data['class_opentime'];
			$data['class']['class_closetime']  = $db_data['class_closetime'];
			$data['class']['class_type']       = $db_data['class_type'];
			$data['class']['class_name']       = $db_data['class_name'];
			$data['class']['teacher_id']       = $db_data['teacher_id'];
			$data['class']['class_caption']    = $db_data['class_caption'];
//			$data['class']['fixed_number']     = $db_data['fixed_number'];
			$data['class']['lecture_students']      = array();
			$data['class']['lecture_students_name'] = '---';
			$data['class']['class_note']       = $db_data['class_note'];
			
			$data['class']['class_maxtime_flag']     = $db_data['class_maxtime_flag'];
			
			//講座名・講師名を取得
			//データ取得用引数設定
			$data_param = array(
							'class_id'   => $data['class']['class_id'],
							'cource_id'  => $data['class']['cource_id'],
							'teacher_id' => $data['class']['teacher_id'],
						);
			$this->load->model('model_cource');
			$data['class']['cource_name']     = $this->model_cource->get_name($data_param);
			$this->load->model('model_teacher');
			$data['class']['teacher_name']    = $this->model_teacher->get_name($data_param);
			
			//受講者ID取得
			$lectures = $this->model_class->get_student_lecture_class($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['class']['lecture_students'][$idx] = $lecture['student_id'];
				}
			}
			
			//受講者名設定
			$this->load->model('model_student');
			if($data['class']['lecture_students']){
				foreach($data['class']['lecture_students'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'student_id' => $lecture,
								);
					$temp = $this->model_student->get_name($name_param);
					if($data['class']['lecture_students_name'] == '---'){
						$data['class']['lecture_students_name'] = '[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}else{
						$data['class']['lecture_students_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}
				}
			}
			
			//授業登録資料
			$data['class']['class_material'] = array();
			$this->load->model('model_class_material');
			$class_material_names = $this->model_class_material->get_class_material_name($data['class']['class_id']);
			$idx = -1;

			if(isset($class_material_names)){
				// 資料種類名の設定
				$temp_class_material_names = array();
				foreach($class_material_names as $names){
					// teacher_id == -1  AND  submit_flag == 0・・・受講者ノート
					// teacher_id == -1  AND  submit_flag == 1・・・受講者提出
					// student_id == -1  AND  submit_flag == 1・・・講師ノート
					// student_id == -1  AND  submit_flag == 0・・・資料
					if($names['teacher_id'] == -1 and $names['submit_flag'] == 0){
						$names['kinds'] = $this->lang->line_or_def('common_student_note','受講者ノート');
					}elseif($names['teacher_id'] == -1 and $names['submit_flag'] == 1){
						$names['kinds'] = $this->lang->line_or_def('common_student_presentation','受講者提出');
					}elseif($names['student_id'] == -1 and $names['submit_flag'] == 1){
						$names['kinds'] = $this->lang->line_or_def('common_teacher_note','講師ノート');
					}elseif($names['student_id'] == -1 and $names['submit_flag'] == 0){
						$names['kinds'] = $this->lang->line_or_def('common_material','資料');
					}
					
					array_push($temp_class_material_names, $names);
				}
				$data['class']['class_material'] = $temp_class_material_names;
			}
			
			// [ver2.0]授業講師取得
			$data['class']['sub_teacher_id']   = array();
			$data['class']['sub_teacher_name'] = '';
			$class_teachers = $this->model_class->get_class_teacher($data_param);
			if(isset($class_teachers)){
				foreach($class_teachers as $class_teacher){
					if($class_teacher['teacher_id'] != $data['class']['teacher_id']){
						$data['class']['sub_teacher_id'][] = $class_teacher['teacher_id'];
						if($data['class']['sub_teacher_name']==''){
							$data['class']['sub_teacher_name']  = $class_teacher['teacher_name'];
						}else{
							$data['class']['sub_teacher_name'] .= ', '.$class_teacher['teacher_name'];
						}
					}
				}
			}
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['class']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_class/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_class/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/09/13]受講者数チェック（callback_student_count_check）を追加
	// [2012/09/13]変更前受講者数の変数（lecture_students_old）を追加
	// [2012/10/01]授業変更確認画面に登録済み資料情報を付加するよう修正（修正確認時のみ）
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');

		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'      , $this->lang->line_or_def('common_flg','flg')                       , 'trim|numeric');
		$this->form_validation->set_rules('class_id'        , $this->lang->line_or_def('common_id','ID')                         , 'trim|numeric');
		$this->form_validation->set_rules('cource_id'       , $this->lang->line_or_def('common_course_name','講座名')            , 'trim|required');
		$this->form_validation->set_rules('class_type'      , $this->lang->line_or_def('common_class_type','授業タイプ')         , 'trim|required');
		$this->form_validation->set_rules('class_date'      , $this->lang->line_or_def('common_class_date','授業日')             , 'trim|required|callback_date_check');
		$this->form_validation->set_rules('class_opentime'  , $this->lang->line_or_def('common_class_start_time','授業開始時間') , 'trim|required|callback_time_check');
		$this->form_validation->set_rules('class_closetime' , $this->lang->line_or_def('common_class_end_time','授業終了時間')   , 'trim|required|callback_time_check|callback_period_check[class_opentime]|callback__check_classtime');
		$this->form_validation->set_rules('class_name'      , $this->lang->line_or_def('common_class_name','授業名')             , 'trim|required');
		$this->form_validation->set_rules('teacher_id'      , $this->lang->line_or_def('common_teacher','講師')                  , 'trim|required');
		$this->form_validation->set_rules('class_caption'   , $this->lang->line_or_def('common_caption','説明')                  , 'trim');
//		$this->form_validation->set_rules('fixed_number'    , '定員'        , 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('lecture_students', $this->lang->line_or_def('common_student','受講者')                , 'callback_student_count_check');
		$this->form_validation->set_rules('class_note'      , $this->lang->line_or_def('common_note','備考')                     , 'trim');
		
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			$disp_param;
			if($this->_check_during_class($this->input->post('class_id'))==0){
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['class']['update_flg']       = $this->input->post('update_flg');
				$data['class']['class_id']         = $this->input->post('class_id');
				$data['class']['cource_id']        = $this->input->post('cource_id');
				$data['class']['class_type']       = '';
				$data['class']['class_date']       = '';
				$data['class']['class_opentime']   = '';
				$data['class']['class_closetime']  = '';
				$data['class']['class_name']       = '';
				$data['class']['teacher_id']       = $this->input->post('teacher_id');
				$data['class']['sub_teacher_id']   = $this->input->post('sub_teacher_id');	// [ver2.0]
				$data['class']['class_caption']    = '';
			//	$data['class']['fixed_number']     = '';
				$data['class']['lecture_students'] = $this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
				$data['class']['class_note']       = '';

				//講座名・講師名を取得
				//データ取得用引数設定
				$data_param = array(
								'class_id'   => $data['class']['class_id'],
								'cource_id'  => $data['class']['cource_id'],
								'teacher_id' => $data['class']['teacher_id'],
							);
				$this->load->model('model_cource');
				$data['class']['cource_name']     = $this->model_cource->get_name($data_param);
				$this->load->model('model_teacher');
				$data['class']['teacher_name']    = $this->model_teacher->get_name($data_param);

				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_class/edit',
								'submenu_idx' => ($data['class']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
			}else{
				$data['class']['update_flg']       = $this->input->post('update_flg');
				$data['class']['class_id']         = $this->input->post('class_id');
				$data['class']['cource_id']        = $this->input->post('cource_id');
				$data['class']['class_type']       = $this->input->post('class_type');
				$data['class']['class_date']       = $this->input->post('class_date');
				$data['class']['class_opentime']   = $this->input->post('class_opentime');
				$data['class']['class_closetime']  = $this->input->post('class_closetime');
				$data['class']['class_name']       = $this->input->post('class_name');
				$data['class']['teacher_id']       = $this->input->post('teacher_id');
				$data['class']['sub_teacher_id']   = $this->input->post('sub_teacher_id');	// [ver2.0]
				$data['class']['class_caption']    = $this->input->post('class_caption');
			//	$data['class']['fixed_number']     = '';
				$data['class']['lecture_students'] = $this->input->post('lecture_students_old')?$this->input->post('lecture_students_old'):array();
				$data['class']['class_note']       = $this->input->post('class_note');

				$data['class']['class_maxtime_flag']     = $this->input->post('class_maxtime_flag');

				//講座名・講師名を取得・データ取得用引数設定
				$data_param = array(
								'class_id'   => $data['class']['class_id'],
								'cource_id'  => $data['class']['cource_id'],
								'teacher_id' => $data['class']['teacher_id'],
							);
				$this->load->model('model_cource');
				$data['class']['cource_name']     = $this->model_cource->get_name($data_param);
				$this->load->model('model_teacher');
				$data['class']['teacher_name']    = $this->model_teacher->get_name($data_param);

				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_class/edit_student',
								'submenu_idx' => ($data['class']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
			}

			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['class']['update_flg']       = $this->input->post('update_flg');
			$data['class']['class_id']         = $this->input->post('class_id');
			$data['class']['class_type']       = $this->input->post('class_type');
			$data['class']['cource_id']        = $this->input->post('cource_id');
			$data['class']['class_date']       = $this->input->post('class_date');
			$data['class']['class_opentime']   = $this->input->post('class_opentime');

			$class_closetime_to_explode = explode(':', $this->input->post('class_closetime'));
			$class_closetime_to_time = ( $class_closetime_to_explode[0] * 60 * 60) + ($class_closetime_to_explode[1] * 60);
			$data['class']['class_closetime'] = date("Y/m/d H:i", strtotime($data['class']['class_date'] . ' ' . $data['class']['class_opentime']) + $class_closetime_to_time);
//			$data['class']['class_closetime']  = $this->input->post('class_closetime');

			$data['class']['class_name']       = $this->input->post('class_name');
			$data['class']['teacher_id']       = $this->input->post('teacher_id');
			$data['class']['sub_teacher_id'] = $this->input->post('sub_teacher_id');	// [ver2.0]
			$data['class']['class_caption']    = $this->input->post('class_caption');
			$data['class']['fixed_number']     = 0;		//$this->input->post('fixed_number');
			$data['class']['lecture_students'] = $this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
			$data['class']['class_note']       = $this->input->post('class_note');
			
			//講座名・講師名を取得
			//データ取得用引数設定
			$data_param = array(
							'cource_id'  => $data['class']['cource_id'],
							'teacher_id' => $data['class']['teacher_id'],
						);
			$this->load->model('model_cource');
			$data['class']['cource_name']     = $this->model_cource->get_name($data_param);
			$this->load->model('model_teacher');
			$data['class']['teacher_name']    = $this->model_teacher->get_name($data_param);

			// [ver2.0]サブ講師の重複チェック
			$data['class']['sub_teacher_id'] = $this->_sub_teacher_overlap_delete($data['class']['sub_teacher_id'], $data['class']['teacher_id']);

			// [ver2.0]サブ講師名の取得
			$sub_teachers = $this->model_teacher->get_name_multi(array('sub_teacher_id' => $data['class']['sub_teacher_id']));

			$data['class']['sub_teacher_name'] = '';
			if(!empty($sub_teachers)){
				foreach($sub_teachers as $sub_teacher){
					if($data['class']['sub_teacher_name'] == ''){
						$data['class']['sub_teacher_name']  = $sub_teacher['teacher_name'];
					}else{
						$data['class']['sub_teacher_name'] .= ', '.$sub_teacher['teacher_name'];
					}
				}
			}
			//受講者名設定
			$data['class']['lecture_students_name'] = '---';
			$this->load->model('model_student');
			if($this->input->post('lecture_students')){
				foreach($data['class']['lecture_students'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'student_id' => $lecture,
								);
					$temp = $this->model_student->get_name($name_param);
					if($data['class']['lecture_students_name'] == '---'){
						$data['class']['lecture_students_name'] = '[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}else{
						$data['class']['lecture_students_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					}
				}
			}
			
			// [2012/10/01]修正確認時のみ、授業登録資料の取得
			if($data['class']['update_flg']!=0){
				$data['class']['class_material'] = array();
				$this->load->model('model_class_material');
				$class_material_names = $this->model_class_material->get_class_material_name($data['class']['class_id']);
				$idx = -1;

				if(isset($class_material_names)){
					// 資料種類名の設定
					$temp_class_material_names = array();
					foreach($class_material_names as $names){
						// teacher_id == -1  AND  submit_flag == 0・・・受講者ノート
						// teacher_id == -1  AND  submit_flag == 1・・・受講者提出
						// student_id == -1  AND  submit_flag == 1・・・講師ノート
						// student_id == -1  AND  submit_flag == 0・・・資料
						if($names['teacher_id'] == -1 and $names['submit_flag'] == 0){
							$names['kinds'] = $this->lang->line_or_def('common_student_note','受講者ノート');
						}elseif($names['teacher_id'] == -1 and $names['submit_flag'] == 1){
							$names['kinds'] = $this->lang->line_or_def('common_student_presentation','受講者提出');
						}elseif($names['student_id'] == -1 and $names['submit_flag'] == 1){
							$names['kinds'] = $this->lang->line_or_def('common_teacher_note','講師ノート');
						}elseif($names['student_id'] == -1 and $names['submit_flag'] == 0){
							$names['kinds'] = $this->lang->line_or_def('common_material','資料');
						}
						
						array_push($temp_class_material_names, $names);
					}
					$data['class']['class_material'] = $temp_class_material_names;
				}
			}
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['class']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_class/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	//----------------------------------------------
	function commit(){
		
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['class_id']) && $edit_form_data['class_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			//授業開始日時・終了日時を編集
			$edit_form_data['class_open']  = $edit_form_data['class_date'].' '.$edit_form_data['class_opentime'];
			$edit_form_data['class_close'] = $edit_form_data['class_closetime'];

			//モデル読み込み
			$this->load->model('model_class');

			//↓[2012/04/10]授業時間の判断を条件により変更-----
			//  新規登録時（class_id ＝ 0）は入力された開始日時がシステム日時より新しいこと
			//  内容更新時（class_id ≠ 0）は保存済みの開始日時がシステム日時より新しいこと
			$class_time_check = '-1';
			$param_data = array(
							'class_id' => $edit_form_data['class_id'],
						);
			$record_data = $this->model_class->get_class($param_data);
			if( isset($record_data['class_id']) ){
				if( strtotime($record_data['class_date'] .' '. $record_data['class_opentime']) > time() ){
					$class_time_check = '0';
				}else{
					$class_time_check = '1';
				}
			}else{
				$class_time_check = '0';
			}
			//↑[2012/04/10]授業時間の判断を条件により変更-----
			
			// 授業開始前の更新時のみ、classテーブルの更新を行う
			if( $class_time_check === '0' ){
				//データ更新用引数設定
				$data_param = array(
								'data' => $edit_form_data,
							);
				//データ更新
				$data = $this->model_class->update_class($data_param);

				// [ver2.0]授業講師テーブルの更新
				$data_param['data']['class_id'] = $data['prev_id'];
				$data2 = $this->model_class->update_class_teacher($data_param);
				
				// ログ出力（新規or修正）
				if($edit_form_data['update_flg'] === '0'){
					// 授業新規作成のログ出力
					$this->_cms_insert_log($data['prev_id'], '管理画面-授業新規作成');
				}else{
					// 管理画面-授業修正（開始前）のログ出力
					$this->_cms_insert_log($data['prev_id'], '管理画面-授業修正（開始前）');
				}
			}else{
				//データ更新用引数設定
				$data_param = array(
								'data' => $edit_form_data,
							);
				//データ更新
				$data = $this->model_class->update_class($data_param);

				$data['prev_id'] = $edit_form_data['class_id'];
				
				// 授業修正（授業開始後）のログ出力
				$this->_cms_insert_log($data['prev_id'], '管理画面-授業修正（開始後）');
			}
			
			//データ更新用引数再設定
			$data_param = array(
							'class_id' => $data['prev_id'],
							'data'     => $edit_form_data,
						);
			$this->model_class->update_student_lecture_class($data_param);
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_class/commit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//完了フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_class');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//エラーフォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($class_id){
	
		// load language
		$this->lang->load('error');

		//授業管理の権限有無確認
		$auth_class_material = $this->_get_auth_class($class_id);

		// 権限を持たない場合、エラーを返す
		// [ver2.0]サブ講師もエラーとする
		if( ($auth_class_material == 0) || ($auth_class_material == 3) ){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_del_auth','削除権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//モデル読み込み
			$this->load->model('model_class');
			
			//データ更新用引数設定
			$data_param = array(
							'class_id' => $class_id,
						);
			//データ削除
			$data = $this->model_class->delete_class($data_param);
			
			// 授業削除のログ出力
			$this->_cms_insert_log($class_id, '管理画面-授業削除');
			
			//リスト画面表示
			//$this->index();
			header("Location:/cms_class/");
			exit();
			
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
			case 1://授業検索
				$sub_menu[3] = "検索";
				$sub_menu[4] = anchor("cms_class/newdata", "新規登録");
				$sub_menu[5] = anchor("cms_cource", "検索");
				$sub_menu[6] = anchor("cms_cource/newdata", "新規登録");
				break;
			
			case 2://新規登録
				$sub_menu[3] = anchor("cms_class", "検索");
				$sub_menu[4] = "新規登録";
				$sub_menu[5] = anchor("cms_cource", "検索");
				$sub_menu[6] = anchor("cms_cource/newdata", "新規登録");
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
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//講座ドロップダウン用データ取得
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);
		
		//講師ドロップダウン用データ取得
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		$param['view_data']['student_group'] = [];
		//受講者チェックボックス用データ取得
		// [2012/11/30]使用箇所が授業開始後の編集画面のみ。選択済み受講者・授業が所属する講座に属する受講者を表示
		if(isset($param['view_data']['class'])){
			$after_param = array(
						"school_id"    => $this->libauth->get_school_id(),
						"class_id"     => $param['view_data']['class']['class_id'],
						"cource_id"    => $param['view_data']['class']['cource_id'],
						);
			$this->load->model('model_student');
			$param['view_data']['students'] = $this->model_student->get_student_list_after_class($after_param);

			// 選択可能受講者からグループ名を取得
			$student_groups = array();
			if($param['view_data']['students']){
				$student_id_list = '0';
				foreach( $param['view_data']['students'] as $student){
					//$student['student_id']
					//$student['student_groups']
					$check_flag = false;
					foreach( $param['view_data']['class']['lecture_students'] as $lecture){
						if($lecture == $student['student_id']){
							$check_flag = true;
							break;
						}
					}
					// 未選択受講者IDを取得
					if($check_flag == false){
						$student_id_list .= ','.$student['student_id'];
					}
				}
				
				// 未選択受講者のグループ名を取得
				$drop_param = array(
							"school_id"			=>	$this->libauth->get_school_id(),
							"select_student_id"	=>	$student_id_list,
							);
				$this->load->model('model_student_group');
				$student_groups = $this->model_student_group->get_student_group_id_list($drop_param);
				
				ksort($student_groups);		// array()に対して、ksortは実行できないため、値があるときのみ行う
			}
			$param['view_data']['student_group'] = $student_groups;
		}
		
		//自ページ名設定
		$param['view_data']['thispage'] = 'class';
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
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
	//時刻形式チェック
	//----------------------------------------------
	function time_check($date){
		// load language
		$this->lang->load('error');

		//エラーメッセージ設定
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_time','%sの時刻の形式が間違っています。'));
		
		//入力パターン正規表現設定("99:99:99")
		$reg_pat = "^([012]?[0-9])[:\.]([0-6]?[0-9])[:\.]([0-6]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return $this->_checktime($parts[1], $parts[2], $parts[3]);
		} else {
			$date .= ':00';	//秒を足してみる
			if( mb_ereg($reg_pat, $date, $parts) ) {
				//日付形式判定
				return $this->_checktime($parts[1], $parts[2], $parts[3]);
			}
			else{
				return FALSE;
			}
		}
	}
	
	//----------------------------------------------
	//期間チェック
	//----------------------------------------------
	function period_check($eddate, $stdate){
		return true;	//期間の指定は時間になった
//		//エラーメッセージ設定
//		$this->form_validation->set_message('period_check', '期間の指定が間違っています。');
//		
//		//削除セパレータ文字設定("/:- ")
//		$rep_str = array('/', ':', '-', ' ');
//		
//		//入力文字列を取得
//		$start_date = $this->input->post($stdate);
//		$end_date   = $eddate;
//		
//		//セパレータを削除
//		$start_date = str_replace($rep_str,'',$start_date);
//		$end_date   = str_replace($rep_str,'',$end_date);
//		
//		//判定
//		if ($start_date > $end_date){
//			return FALSE;
//		}else{
//			return TRUE;
//		}
	}

	//----------------------------------------------
	//[2012/09/13]受講者数チェック
	//  エラー条件：選択ゼロ、一般授業で９名を超える
	//----------------------------------------------
	function student_count_check(){
		// load language
		$this->lang->load('error');

		$student_array = $this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
		$class_type    = $this->input->post('class_type')?$this->input->post('class_type'):'';
		$student_count = 0;
		
		foreach($student_array as $student_id) {
			$student_count = $student_count + 1;
		}

		if($student_count == 0){
			//エラーメッセージ設定
			$this->form_validation->set_message('student_count_check', $this->lang->line_or_def('error_class_no_select_student','受講者が選択されていません'));
			return false;
		}elseif( ($student_count > 9) && ($class_type === 'school') ){
			//エラーメッセージ設定
			$this->form_validation->set_message('student_count_check', $this->lang->line_or_def('error_class_max_select_student','参加できる受講者数は最大9名です'));
			return false;
		}else{
			return true;
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
	//授業時間チェック
	//  授業開始後の更新において授業時間の短縮を禁止するためのチェック
	//----------------------------------------------
	function _check_classtime($date){
		// load language
		$this->lang->load('error');

		// クラスIDをキーに、新規・更新（授業前）・更新（授業後）を判断
		$result = $this->_check_during_class($this->input->post('class_id'));

		if($result == 0){
			// 新規・更新（授業前）
			return true;
		}else{
			// 更新（授業後）
			// 入力された授業時間から、入力授業終了日時の取得
			$class_closetime_to_explode = explode(':', $this->input->post('class_closetime'));
			$class_closetime_to_time    = ( $class_closetime_to_explode[0] * 60 * 60) + ($class_closetime_to_explode[1] * 60);
			$input_class_closetime      = date("Y/m/d H:i:s", strtotime($this->input->post('class_date') . ' ' . $this->input->post('class_opentime')) + $class_closetime_to_time);
			
			//【入力授業終了日時 < テーブル授業終了日時】ならばエラーとする
			if($input_class_closetime < $result){
				// テーブル上の授業開始日時・授業終了日時から、差を取得。
				$table_class_opentime = date("Y/m/d H:i:s", strtotime($this->input->post('class_date') . ' ' . $this->input->post('class_opentime')));
				$sa_date = strtotime($result) - strtotime($table_class_opentime);
				$sa_date = sprintf("%02d:%s", $sa_date/3600, gmdate("i", $sa_date%3600));
			
				$this->form_validation->set_message('_check_classtime', 
									$this->lang->line_or_def('error_class_closetime','授業開始後の授業時間の短縮はできません')."【最小の指定可能授業時間 >> ".$sa_date."】");	//.
//									'【最小の指定可能授業時間：'.$this->input->post('before_class_closetime').'】');
				return false;
			}else{
				return true;
			}
		}
	}

	//----------------------------------------------
	//更新しようとしている授業が授業開始前・後かを判断
	//  授業開始前:0
	//  授業開始後:テーブルの授業終了時間（日時）
	//----------------------------------------------
	function _check_during_class($class_id){

		//モデル読み込み
		$this->load->model('model_class');

		//クラス取得
		$param = array(
					'class_id' => $class_id,
				);
		$record_data = $this->model_class->get_class($param);
		
		//日時判断
		if( isset($record_data['class_id']) ){
			if( strtotime($record_data['class_date'] .' '. $record_data['class_opentime']) > time() ){
				return 0;   // 更新（授業前）
			}else{
				// DBの授業終了時間（%Y/%m/%d %H:%i:%s）を返す
				return $record_data['class_close_check'];  // 更新（授業後）
			}
		}else{
			return 0;       // 新規
		}
	}
	
	//----------------------------------------------
	//授業管理の権限有無確認
	//  ログインユーザーに授業管理の権限があるかの確認
	//  修正・削除可能かどうか
	//  [ver2.0] サブ講師にも権限を付加するように修正
	//----------------------------------------------
	function _get_auth_class($class_id){
		$login_teacher_id    = $this->libauth->get_teacher_id();
		$work_auth           = $this->libauth->get_teacher_auth();
		$auth_class          = 0;
		
		if($login_teacher_id < 0){
			//SuperUser
			$auth_class = 1;
		}elseif($work_auth['school_admin'] == 1){
			//学校管理者
			$auth_class = 1;
		}elseif( $work_auth['course_class'] == 1 ){
			//講師かつ授業管理の権限あり
			
			//選択した授業が担当授業であれば権限付与
			//model Load
			$this->load->model('model_class');
			$data_param = array(
							'class_id' => $class_id,
						);
			$class_data       = $this->model_class->get_class($data_param);

			if( isset($class_data['teacher_id']) ){
				$class_teacher_id = $class_data['teacher_id'];
				if($class_teacher_id == $login_teacher_id){
					$auth_class = 2;
				}else{

					$data_param_2 = array(
									'class_id'   => $class_id,
									'teacher_id' => $login_teacher_id,
								);
					if( $this->model_class->check_class_teacher($data_param_2) ){
						//限定権限あり
						$auth_class = 3;
					}

				}
			}else{
			//	$auth_class = 2; // 「権限あり講師＋表示しようとした授業がない」は無い
			}
		}else{
			//権限なし
			$auth_class = 0;
		}
		
		//戻り値
		return $auth_class;
	}

	//----------------------------------------------
	// 受講者修正フォーム表示
	// ・授業開始後、受講者を追加するための処理
	// [2012/09/13]変更前受講者数の変数（lecture_students_old）を追加
	//----------------------------------------------
	function edit_student($class_id = 0){

		// load language
		$this->lang->load('error');

		// --- クラスIDがきた場合、DBから授業一式情報を再取得 ---
		if($class_id <> 0){
			//モデル読み込み
			$this->load->model('model_class');
			// load language
			$this->lang->load('common');
			
			//データ取得用引数設定
			$data_param = array(
							'class_id' => $class_id,
						);
			//データ取得
			$db_data = $this->model_class->get_class($data_param);
			
			if(count($db_data) > 0){
				//データ有り時
				//ボタン切り替えフラグ設定
				$data['btn_kirikae_flg'] = 2;
				
				//表示用データ設定
				$data['class']['update_flg']       = 1;
				$data['class']['class_id']         = $db_data['class_id'];
				$data['class']['cource_id']        = $db_data['cource_id'];
				$data['class']['class_date']       = $db_data['class_date'];
				$data['class']['class_opentime']   = $db_data['class_opentime'];
				$data['class']['class_closetime']  = $db_data['class_closetime'];
				$data['class']['class_type']       = $db_data['class_type'];
				$data['class']['class_name']       = $db_data['class_name'];
				$data['class']['teacher_id']       = $db_data['teacher_id'];
				$data['class']['class_caption']    = $db_data['class_caption'];
	//			$data['class']['fixed_number']     = $db_data['fixed_number'];
				$data['class']['lecture_students'] = array();
				$data['class']['lecture_students_old'] = array();
				$data['class']['class_note']       = $db_data['class_note'];
				
				$data['class']['class_maxtime_flag']     = $db_data['class_maxtime_flag'];
				
				//講座名・講師名を取得
				//データ取得用引数設定
				$data_param = array(
								'class_id'   => $data['class']['class_id'],
								'cource_id'  => $data['class']['cource_id'],
								'teacher_id' => $data['class']['teacher_id'],
							);
				$this->load->model('model_cource');
				$data['class']['cource_name']     = $this->model_cource->get_name($data_param);
				$this->load->model('model_teacher');
				$data['class']['teacher_name']    = $this->model_teacher->get_name($data_param);
				
				//受講者取得
				$lectures = $this->model_class->get_student_lecture_class($data_param);
				$idx = -1;
				if(isset($lectures)){
					foreach($lectures as $lecture){
						$idx++;
						$data['class']['lecture_students'][$idx] = $lecture['student_id'];
					}
				}
				
				//受講者名設定
				$data['class']['student_lectures_name'] = array();
				$this->load->model('model_student');
				if($data['class']['lecture_students']){
					foreach($data['class']['lecture_students'] as $idx => $lecture){
						//データ取得用引数設定
						$name_param = array(
										'student_id' => $lecture,
									);
						$temp = $this->model_student->get_name($name_param);
						$data['class']['student_lectures_name'][$idx] = $temp['student_name'];
					}
				}
				
				//授業登録資料
				$data['class']['class_material'] = array();
				$this->load->model('model_class_material');
				$class_material_names = $this->model_class_material->get_class_material_name($data['class']['class_id']);
				$idx = -1;

				if(isset($class_material_names)){
					foreach($class_material_names as $names){
						$save_from = "-";
						if($names['teacher_id'] == -1){
							$save_from = $this->lang->line_or_def('common_student_presentation','受講者提出');
						}elseif($names['submit_flag'] == 0){
							$save_from = $this->lang->line_or_def('common_teacher_material','講師資料');
						}else{
							$save_from = $this->lang->line_or_def('common_teacher_note','講師ノート');
						}
						$idx++;
						$data['class']['class_material'][$idx] = "[".$save_from."]".$names['material_logic_name'];
					}
				}

			// [ver2.0]授業講師取得
			$data['class']['sub_teacher_id']   = array();
			$data['class']['sub_teacher_name'] = '';
			$class_teachers = $this->model_class->get_class_teacher($data_param);
			if(isset($class_teachers)){
				foreach($class_teachers as $class_teacher){
					if($class_teacher['teacher_id'] != $data['class']['teacher_id']){
						$data['class']['sub_teacher_id'][] = $class_teacher['teacher_id'];
						if($data['class']['sub_teacher_name']==''){
							$data['class']['sub_teacher_name']  = $class_teacher['teacher_name'];
						}else{
							$data['class']['sub_teacher_name'] .= ', '.$class_teacher['teacher_name'];
						}
					}
				}
			}

				//セッションデータのクリア
				$this->session->unset_userdata('edit_form_data');
				
				//セッションへDB取得データを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['class']));
			}
		}
		// --- クラスIDがきた場合、DBから授業一式情報を再取得 ---

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		//授業管理の権限有無確認
		$auth_class_material = $this->_get_auth_class($edit_form_data['class_id']);

		// 権限を持たない場合、エラーを返す
		if($auth_class_material == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_class');
			$data['error_message'] = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'course_class_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			if( strtotime($edit_form_data['class_date'] .' '. $edit_form_data['class_opentime']) > time() ){
				// 授業開始日時前に、この機能は使用させない。

				//戻り先設定
				$data['returnurl'] = site_url('admin_top');
				$data['error_message'] = $this->lang->line_or_def('error_unjust_access','不正アクセスを検知しました<br />ログインし直してください');
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'course_class_error',
								'submenu_idx' => 4,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}else{
				if(isset($edit_form_data['class_id']) &&  $edit_form_data['class_id'] <> ''){
					//画面表示用データ設定
					$data['class'] = $edit_form_data;
					
					//ビュー設定引数設定
					$disp_param = array(
									'view_name'   => 'cms_class/edit_student',
									'submenu_idx' => ($data['class']['update_flg']==0 ? 2 : 3),
									'view_data'   => $data,
								);
					//確認フォーム表示
					$this->_display_view($disp_param);
					
				}else{
					//セッションデータ無しはエラーフォーム表示
					//戻り先設定
					$data['returnurl'] = site_url('class');
					
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
	}

	//----------------------------------------------
	// アプリログ出力
	// ・授業登録、授業修正（開始前・開始後）、授業削除
	//----------------------------------------------
	function _cms_insert_log($class_id = 0, $log_option = '管理画面-授業'){
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
	
	//----------------------------------------------
	// [ver2.0]サブ講師配列の重複削除
	// ・同じ講師IDを削除＋管理講師IDを削除
	//----------------------------------------------
	function _sub_teacher_overlap_delete($array, $value){
		
		// 配列内重複値削除（【1,2,2,'',''】->【1,2,''】）
		$array = array_unique($array);
	//print var_dump($array);
		
		// 配列値数精査（【2, ''】->【2,'','','',''】）
		$param = array('','','','','');
		$count = 0;
		foreach ($array as $array_data) {
			if( (!empty($array_data)) && ($array_data != $value) ){
				// 値あり 且つ 管理講師ID以外
				$param[$count] = $array_data;
				$count = $count + 1;
			}
		}
	//print var_dump($param);
		
		return $param;
	}
} 

/*End of File program.php*/
