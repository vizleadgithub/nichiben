<?php
#[AllowDynamicProperties]
class Cms_student_group extends CI_Controller {
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
			if($work_auth['student'] == 0){
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
		$this->form_validation->set_rules('s_student_group_name'  , $this->lang->line_or_def('common_group_name','グループ名')  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_student_id'          , $this->lang->line_or_def('common_student_name','受講者名')  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_id'                  , $this->lang->line_or_def('common_id','ID')                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'           , $this->lang->line_or_def('common_freeword','フリーワード')  , 'trim|xss_clean');
		
		$this->form_validation->run();		//バリデーション実行（その実xss対策）
		
		//受講者モデル読み込み
		$this->load->model('model_student_group');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('student_group_search_cond') ?: array(
				's_student_group_name' => '',
				's_student_id'=>'',
				's_id' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_student_group_name']  = ($this->input->post('s_student_group_name', TRUE) ?? '');
			$data['s_student_id']          = ($this->input->post('s_student_id', TRUE) ?? '');
			$data['s_id']                  = ($this->input->post('s_id', TRUE) ?? '');
			$data['s_free_word']           = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('student_group_search_cond', $data);
		}

		$data_param = array(
			's_school_id'           => $this->libauth->get_school_id(),
			's_student_group_name'  => $data['s_student_group_name'],
			's_student_id'          => $data['s_student_id'],
			's_student_group_id'    => $data['s_id'],
			's_free_word'           => $data['s_free_word'],
			'offset'                => $offset,
			'rowcount'              => $per_page,
		);

		//データ取得
		$student_group_list = $this->model_student_group->get_student_group_search_list($data_param);
		$data['student_group_list'] = $student_group_list;

		//ページネーション設定
		$config['base_url']   = base_url().'/cms_student_group/index';
		$config['total_rows'] = $this->model_student_group->get_student_group_search_count($data_param);
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
						'view_name'   => 'cms_student_group/index',
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
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['student_group']['update_flg']            = 0;
		$data['student_group']['student_group_id']      = 0;
		$data['student_group']['student_group_name']    = '';
		$data['student_group']['student_group_caption'] = '';
		$data['student_group']['position_students']      = array();
		$data['student_group']['position_students_name'] = '';
		
		// 有効な受講者数の取得
		$this->load->model('model_cource');
		$all_count = $this->model_cource->get_effective_items(
			array(
				'school_id' => $this->libauth->get_school_id(),
				)
		);
		$data['student_group']['student_all_count'] =$all_count['student_all_count'];
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student_group/edit',
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
			redirect('/cms_student_group/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['student_group_id']) &&  $edit_form_data['student_group_id'] <> ''){
			//画面表示用データ設定
			$data['student_group'] = $edit_form_data;
			
			// 有効な受講者数の取得
			$this->load->model('model_cource');
			$all_count = $this->model_cource->get_effective_items(
				array(
					'school_id' => $this->libauth->get_school_id(),
					)
			);
			$data['student_group']['student_all_count'] =$all_count['student_all_count'];
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_group/edit',
							'submenu_idx' => ($data['student_group']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('student_group');
			
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
	//----------------------------------------------
	function detail($student_group_id, $result_data = array()){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_student_group');
		
		//データ取得用引数設定
		$data_param = array(
						'student_group_id' => $student_group_id,
					);
		//データ取得
		$db_data = $this->model_student_group->get_student_group($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['student_group']['update_flg']            = 1;
			$data['student_group']['student_group_id']      = $db_data['student_group_id'];
			$data['student_group']['student_group_name']    = $db_data['student_group_name'];
			$data['student_group']['student_group_caption'] = $db_data['student_group_caption'];
			
			// グループ所属の受講者ID・受講者名（表示用）の取得
			$data_param = array(
							'school_id'        => $this->libauth->get_school_id(),
							'student_group_id' => $db_data['student_group_id'],
						);
			$data_results = $this->model_student_group->get_student_group_position_student($data_param);
			$data['student_group']['position_students']      = array();
			$data['student_group']['position_students_name'] = '';
			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['student_group']['position_students'][$idx]    = $data_result['student_id'];
					if($data['student_group']['position_students_name'] != ''){
						$data['student_group']['position_students_name'] .= '<br/>';
					}
					$data['student_group']['position_students_name'] .= '[No'.$data_result['student_id'].']&nbsp;';
					$data['student_group']['position_students_name'] .= $data_result['student_name'].'&nbsp;';
					$data['student_group']['position_students_name'] .= '&lt;'.$data_result['student_email'].'&gt;';
				}
			}
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['student_group']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_group/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_student_group/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'            , $this->lang->line_or_def('common_flg','flg')               , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('student_group_id'      , $this->lang->line_or_def('common_id','ID')                 , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('student_group_name'    , $this->lang->line_or_def('common_group_name','グループ名') , 'trim|xss_clean|required');
		$this->form_validation->set_rules('student_group_caption' , $this->lang->line_or_def('common_caption','説明')          , 'trim|xss_clean');
		$this->form_validation->set_rules('position_students'     , $this->lang->line_or_def('common_student','受講者')        , 'xss_clean');
		
		// [Ajax]選択された受講者IDの整形（取得・ID昇順）
		$position_students = $this->input->post('position_students_array')?$this->input->post('position_students_array'):array();
		sort($position_students);
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['student_group']['update_flg']             = $this->input->post('update_flg');
			$data['student_group']['student_group_id']       = $this->input->post('student_group_id');
			$data['student_group']['student_group_name']     = '';
			$data['student_group']['student_group_caption']  = '';
			$data['student_group']['position_students']      = $position_students;
		  //$data['student_group']['position_students_name'] = '';
			
			// 有効な受講者数の取得
			$this->load->model('model_cource');
			$all_count = $this->model_cource->get_effective_items(
				array(
					'school_id' => $this->libauth->get_school_id(),
					)
			);
			$data['student_group']['student_all_count'] =$all_count['student_all_count'];
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_group/edit',
							'submenu_idx' => ($data['student_group']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
		}else{
			//重複チェック
			$query = $this->db->query(
				' SELECT * FROM student_group '.
				' WHERE student_group_name = ? '.
				' AND school_id = ?'.
				' AND status = 0'.
				' LIMIT 0, 1',
				array(
					$this->input->post('student_group_name'),
					$this->libauth->get_school_id(),
				)
			);
			if(!$this->input->post('update_flg') && $query->row()){
				$data['student_group']['update_flg']             = $this->input->post('update_flg');
				$data['student_group']['student_group_id']       = $this->input->post('student_group_id');
				$data['student_group']['student_group_name']     = '';
				$data['student_group']['student_group_caption']  = '';
				$data['student_group']['position_students']      = $position_students;
			  //$data['student_group']['position_students_name'] = '';
				$data['error_msg']                               = $this->lang->line_or_def('error_registered_group','登録済みのグループ名です');
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student_group/edit',
								'submenu_idx' => ($data['student_group']['update_flg']==0 ? 2 : 3),
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
			$data['student_group']['update_flg']             = $this->input->post('update_flg');
			$data['student_group']['student_group_id']       = $this->input->post('student_group_id');
			$data['student_group']['student_group_name']     = $this->input->post('student_group_name');
			$data['student_group']['student_group_caption']  = $this->input->post('student_group_caption');
			$data['student_group']['position_students']      = $position_students;
			
			// 選択された受講者名の取得
			$this->load->model('model_student');
			$data['student_group']['position_students_name'] = '';
			if($data['student_group']['position_students']){
				$this->load->model('model_student');
				foreach($data['student_group']['position_students'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'student_id' => $lecture,
								);
					$temp = $this->model_student->get_name($name_param);
					if($data['student_group']['position_students_name'] != ''){
						$data['student_group']['position_students_name'] .= '<br/>';
					}
					$data['student_group']['position_students_name'] .= '[No'.$lecture.']&nbsp;';
					$data['student_group']['position_students_name'] .= $temp['student_name'].'&nbsp;';
					$data['student_group']['position_students_name'] .= '&lt;'.$temp['student_email'].'&gt;';
				}
			}
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['student_group']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_group/confirm',
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
		
		if(isset($edit_form_data['student_group_id']) && $edit_form_data['student_group_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			
			//モデル読み込み
			$this->load->model('model_student_group');
			
			
			//データ更新用引数設定
			$data_param = array(
							'data' => $edit_form_data,
						);
			//データ更新（外部連携API実行結果含む）
			$data = $this->model_student_group->update_student_group($data_param);

			if($data['stat'] == 200){
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student_group/commit',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
				
				//セッションデータのクリア
				$this->session->unset_userdata('edit_form_data');
			}else{
				$result_data['student']     = $edit_form_data;
				$result_data['elm_result']  = $data['result'];
				$result_data['elm_stat']    = $data['stat'];
				$result_data['elm_message'] = $data['message'];
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student_group/edit',
								'submenu_idx' => ($result_data['student']['update_flg']==0 ? 2 : 3),
								'view_data'   => $result_data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_student');
			
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
	function delete_item($student_group_id){
		//モデル読み込み
		$this->load->model('model_student_group');
		
		//データ更新用引数再設定
		$data_param = array(
						'student_group_id' => $student_group_id,
					);
		//データ削除
		$data = $this->model_student_group->delete_student_group($data_param);

		if($data['stat'] == 200){
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');

			//戻る
			$_offset = ($this->session->userdata('offset') ? $this->session->userdata('offset') : 0);
			$this->session->unset_userdata('offset');
			redirect("/cms_student_group/index/$_offset/");
		}else{
			$result_data['elm_result']  = $data['result'];
			$result_data['elm_stat']    = $data['stat'];
			$result_data['elm_message'] = $data['message'];
			
			// 詳細画面の表示
			$this->detail($student_group_id, $result_data);
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
				$sub_menu[2] = anchor("cms_student/newdata", "新規登録");
				break;
			
			case 2://新規登録
				$sub_menu[1] = anchor("cms_student", "検索");
				$sub_menu[2] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_student", "検索");
				$sub_menu[2] = anchor("cms_student/newdata", "新規登録");;
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
		
		//受講者ドロップダウン用データ取得
		$param['view_data']['students_dropdown']  = $this->_get_student_list_array($drop_param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}
	
	//----------------------------------------------
	// 受講者名ドロップダウン用配列取得
	//----------------------------------------------
	function _get_student_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_student');
		
		//一覧ドロップダウン生成
		$data['student_list'] = $this->model_student->get_cource_student($param);
		$data['students'] = array();
		if( count($data['student_list']) != 0 ) {
			$data['students'][''] = '';
			foreach ( $data['student_list'] as $student ) {
				$data['students'][$student['student_id']] = $student['student_name'];
			}
		}
		
		return $data['students'];
	}

	//----------------------------------------------
	// 〓 [Ajax用]学校所属の受講者のグループを取得
	//----------------------------------------------
	function get_cource_student_group(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"			=>	$this->libauth->get_school_id(),
					'bar_association_id' => $this->libauth->get_bar_association_id(),
					"select_student_id"	=>	$this->input->post('select_student_id'),
					);
		
		// 学校ID・受講者IDに一致するグループ名・受講者ID群を取得
		$this->load->model('model_student_group');
		$student_group = $this->model_student_group->get_student_group_id_list($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($student_group));
	}

}

/*End of File program.php*/
