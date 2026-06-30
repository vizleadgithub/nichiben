<?php
#[AllowDynamicProperties]
class Cms_issue extends CI_Controller {
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
			if($work_auth['issue'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の課題：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'issue'))){
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	//一覧表示
	//----------------------------------------------
	function index($offset=0){
		// load language
		$this->lang->load('common');
		
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');

		$this->form_validation->set_rules('s_cource'    , $this->lang->line_or_def('common_course_name','講座名')    , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_issue');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('issue_search_cond') ?: array(
				's_cource' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_cource']    = ($this->input->post('s_cource') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('issue_search_cond', $data);
		}

		$issue_list = $this->model_issue->get_issue_list(array(
			'school_id' 	=> $this->libauth->get_school_id(),
			'offset'    	=> $offset,
			'rowcount'  	=> $per_page,
			's_cource'		=> $data['s_cource'],		//search
			's_free_word'	=> $data['s_free_word'],	//search
		));

	//	$data['issue_list'] = $issue_list['items'];
		$issue_table_list = $issue_list['items'];
		
		//課題受付状態の取得
		$now = date('YmdHis');
		for($i = 0; $i<count($issue_table_list); $i++){
			if($issue_table_list[$i]['issue_open'] <= $now 
					&& $now <= $issue_table_list[$i]['issue_close']){
				$issue_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_now','受付中');
			} else if( $issue_table_list[$i]['issue_open'] > $now){
				$issue_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_plan','受付予定');
			} else if( $issue_table_list[$i]['issue_close'] < $now){
				$issue_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_end','受付終了');
			} else {
				$issue_table_list[$i]["disp_status"] = "";
			}
		}
		$data['issue_list'] = $issue_table_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_issue/index';
		$config['per_page']   = $per_page;
		$config['total_rows'] = $issue_list['cnt'];
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
						'view_name'   => 'cms_issue/index',
						'submenu_idx' => 2,
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
		$data['issue']['update_flg']           = 0;
		$data['issue']['issue_id']             = 0;
		$data['issue']['issue_name']           = '';
		$data['issue']['issue_caption']        = '';
		$data['issue']['issue_open']           = '1900/01/01 00:00:00';
		$data['issue']['issue_close']          = '2100/12/31 23:59:59';
		$data['issue']['public_flag']          = 0;
		$data['issue']['teacher_id']           = $this->libauth->get_teacher_id();
		$data['issue']['teacher_name']         = '';

		$data['issue']['issue_temp_id']          = array(-1, -1, -1, -1, -1);
		$data['issue']['issue_temp_name']        = array('', '', '', '', '');
		$data['issue']['issue_temp_logic_name']  = array('', '', '', '', '');
		$data['issue']['issue_temp_delete']      = array();
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_issue/edit',
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
		
		if(isset($edit_form_data['issue_id']) &&  $edit_form_data['issue_id'] <> ''){
			//画面表示用データ設定
			$data['issue'] = $edit_form_data;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_issue/edit',
							'submenu_idx' => ($data['issue']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('issue');
			
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
	function detail($issue_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_issue');
		
		//データ取得用引数設定
		$data_param = array(
						'issue_id' => $issue_id,
					);
		//データ取得
		$db_data = $this->model_issue->get_issue($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['issue']['update_flg']    = 1;
			$data['issue']['issue_id']      = $db_data['issue_id'];
			$data['issue']['issue_name']    = $db_data['issue_name'];
			$data['issue']['issue_caption'] = $db_data['issue_caption'];
			$data['issue']['issue_open']    = $db_data['issue_open'];
			$data['issue']['issue_close']   = $db_data['issue_close'];
			$data['issue']['public_flag']   = $db_data['public_flag'];
			$data['issue']['teacher_id']    = $db_data['teacher_id'];
			$data['issue']['teacher_name']  = $db_data['teacher_name'];
			
			//所属講座取得
			$data['issue']['issue_lectures']       = array();
			$lectures = $this->model_issue->get_issue_lectures($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['issue']['issue_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			
			//所属講座名設定
			$data['issue']['issue_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['issue']['issue_lectures']){
				foreach($data['issue']['issue_lectures'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['issue']['issue_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				//連想キーと要素との関係を維持しつつ配列をソート
				asort($data['issue']['issue_lectures_name']);
			}
			
			//課題雛形テーブルの情報取得
			$data['issue']['issue_temp_id']          = array(-1, -1, -1, -1, -1);
			$data['issue']['issue_temp_name']        = array('', '', '', '', '');
			$data['issue']['issue_temp_logic_name']  = array('', '', '', '', '');
			$data['issue']['issue_temp_delete']      = array();
			
			$templates = $this->model_issue->get_issue_template($data_param);
			$idx = -1;
			if(isset($templates)){
				foreach($templates as $template){
					$idx++;
					$data['issue']['issue_temp_id'][$idx]         = $template['issue_temp_id'];
					$data['issue']['issue_temp_name'][$idx]       = $template['issue_temp_name'];
					$data['issue']['issue_temp_logic_name'][$idx] = $template['issue_temp_logic_name'];
				}
			}

			//課題提出テーブルの取得
			$data['issue']['issue_submit_id']           = array();
			$data['issue']['issue_submit_student_id']   = array();
			$data['issue']['issue_submit_student_name'] = array();
			$data['issue']['issue_submit_name']         = array();
			$data['issue']['issue_submit_logic_name']   = array();
			$data['issue']['issue_submit_caption']      = array();
			$data['issue']['issue_submit_date']         = array();
			$data['issue']['issue_submit_status']       = array();
			
			$submits = $this->model_issue->get_issue_submit($data_param);
			$idx = -1;
			if(isset($submits)){
				foreach($submits as $submit){
					// 提出ファイル論理名、拡張子がある場合、ファイル名と拡張子の間に改行を追加
					$ext  = strtolower(pathinfo($submit['issue_submit_logic_name'],PATHINFO_EXTENSION));
					if($ext){
						$name = mb_ereg_replace('.'.$ext,'',$submit['issue_submit_logic_name'],'i');
						$submit['issue_submit_logic_name'] = $name.'<br/>.'.$ext;
					}
					
					$idx++;
					$data['issue']['issue_submit_id'][$idx]           = $submit['issue_submit_id'];
					$data['issue']['issue_submit_student_id'][$idx]   = $submit['student_id'];
					$data['issue']['issue_submit_student_name'][$idx] = $submit['student_name'];
					$data['issue']['issue_submit_name'][$idx]         = $submit['issue_submit_name'];
					$data['issue']['issue_submit_logic_name'][$idx]   = $submit['issue_submit_logic_name'];
					$data['issue']['issue_submit_caption'][$idx]      = $submit['issue_submit_caption'];
					$data['issue']['issue_submit_date'][$idx]         = $submit['issue_submit_date'];
					$data['issue']['issue_submit_status'][$idx]       = $submit['status'];
				}
			}
			//print var_dump($data['issue']);
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['issue']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_issue/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_issue/");
			exit();
		}
	}

	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	//----------------------------------------------
	function commit(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'    , $this->lang->line_or_def('common_flg','flg')                     , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('issue_id'      , $this->lang->line_or_def('common_id','ID')                       , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('issue_name'    , $this->lang->line_or_def('common_issue_name','課題名')           , 'trim|xss_clean|required');
		$this->form_validation->set_rules('issue_lectures[]', $this->lang->line_or_def('common_position_course','所属講座')    , 'callback_check_required_checkbox');
		$this->form_validation->set_rules('issue_caption' , $this->lang->line_or_def('common_caption','説明')                , 'trim|xss_clean');
		$this->form_validation->set_rules('issue_open'    , $this->lang->line_or_def('common_submit_period','提出期間')      , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('issue_close'   , $this->lang->line_or_def('common_submit_period','提出期間')      , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('public_flag'   , $this->lang->line_or_def('common_indication_status','表示状態')  , 'trim|xss_clean|required');
		$this->form_validation->set_rules('teacher_id'    , $this->lang->line_or_def('common_management_teacher','管理講師') , 'trim|xss_clean|required');
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['issue']['update_flg']     = $this->input->post('update_flg');
			$data['issue']['issue_id']       = $this->input->post('issue_id');
			$data['issue']['issue_name']     = '';
			$data['issue']['issue_lectures'] = $this->input->post('issue_lectures')?$this->input->post('issue_lectures'):array();
			$data['issue']['issue_caption']  = '';
			$data['issue']['issue_open']     = '';
			$data['issue']['issue_close']    = '';
			$data['issue']['public_flag']    = '';
			$data['issue']['teacher_id']     = $this->input->post('teacher_id');
			$data['issue']['teacher_name']   = '';
			
			$data['issue']['issue_temp_id']          = $this->input->post('issue_temp_id')?$this->input->post('issue_temp_id'):array(-1,-1,-1,-1,-1);
			$data['issue']['issue_temp_name']        = $this->input->post('issue_temp_name')?$this->input->post('issue_temp_name'):array('','','','','');
			$data['issue']['issue_temp_logic_name']  = $this->input->post('issue_temp_logic_name')?$this->input->post('issue_temp_logic_name'):array('','','','','');
			$data['issue']['issue_temp_delete']      = $this->input->post('issue_temp_delete')?$this->input->post('issue_temp_delete'):array();
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_issue/edit',
							'submenu_idx' => ($data['issue']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['issue']['update_flg']     = $this->input->post('update_flg');
			$data['issue']['issue_id']       = $this->input->post('issue_id');
			$data['issue']['issue_name']     = $this->input->post('issue_name');
			$data['issue']['issue_lectures'] = $this->input->post('issue_lectures')?$this->input->post('issue_lectures'):array();
			$data['issue']['issue_caption']  = $this->input->post('issue_caption');
			$data['issue']['issue_open']     = $this->input->post('issue_open');
			$data['issue']['issue_close']    = $this->input->post('issue_close');
			$data['issue']['public_flag']    = $this->input->post('public_flag');
			$data['issue']['school_id']      = $this->libauth->get_school_id();
			$data['issue']['teacher_id']     = $this->input->post('teacher_id');
			$data['issue']['teacher_name']   = '';
			
			$data['issue']['issue_temp_id']          = $this->input->post('issue_temp_id')?$this->input->post('issue_temp_id'):array(-1,-1,-1,-1,-1);
			$data['issue']['issue_temp_name']        = $this->input->post('issue_temp_name')?$this->input->post('issue_temp_name'):array('','','','','');
			$data['issue']['issue_temp_logic_name']  = $this->input->post('issue_temp_logic_name')?$this->input->post('issue_temp_logic_name'):array('','','','','');
			$data['issue']['issue_temp_delete']      = $this->input->post('issue_temp_delete')?$this->input->post('issue_temp_delete'):array();

			//モデル読み込み
			$this->load->model('model_issue');
			
			//更新用引数設定
			$update_param = array(
							'data'   => $data['issue'],
						);
			
			//課題テーブル更新
			$issue_result = $this->model_issue->update_issue($update_param);
			
			//課題講座テーブル更新
			$data_param = array(
							'issue_id'   => $issue_result['lastInsertId'],
							'data'       => $update_param['data'],
						);
			$this->model_issue->update_issue_lectures($data_param);
			
			// 課題雛形テーブル更新
			$this->model_issue->update_issue_template($data_param);
		
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_issue/commit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($issue_id){
		// load language
		$this->lang->load('error');
		
		//モデル読み込み
		$this->load->model('model_issue');

		// 権限確認
		$auth_data = $this->model_issue->issue_edit_delete_auth_check(array(
			'issue_id' => $issue_id,
		));

		// 削除権限がない場合、エラー画面を表示
		if($auth_data['auth_edit_delete'] == 0){
			//戻り先設定
			$data['returnurl']     = site_url('cms_issue');
			$data['error_message'] = $this->lang->line_or_def('error_del_auth','削除権限がありません<br />ログインし直してください');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'issue_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ削除
			$data = $this->model_issue->delete_item(array(
				'issue_id' => $issue_id,
			));
			
			//課題一覧リスト画面表示
			//完了フォーム表示
			$this->_display_view(array(
				'view_name'   => 'cms_issue/commit',
				'submenu_idx' => 4,
				'view_data'   => array(),
				//'class_id'    => $this->input->post('class_id'),
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
				$sub_menu[1] = anchor("cms_material", "検索");
				$sub_menu[2] = "一覧";
				$sub_menu[3] = anchor("cms_material/newdata/", "新規登録");
				break;
			
			case 3://資料新規登録
				$sub_menu[1] = anchor("cms_material/", "検索");;
				$sub_menu[2] = anchor("cms_material/", "一覧");
				$sub_menu[3] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_material/", "検索");;
				$sub_menu[2] = anchor("cms_material/", "一覧");
				$sub_menu[3] = anchor("cms_material/newdata/", "新規登録");
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
							'class_id'    => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//講座ドロップダウン用データ取得（index）
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);
		
		//講師ドロップダウン用データ取得（edit）
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		// 講座チェックボックス用データ取得（edit）
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);
		
		// 状態（公開フラグ）ドロップダウン用データ取得（edit）
		$param['view_data']['public_flag_list']  = $this->_get_select_public_flag();
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
	}

	//----------------------------------------------
	//担当講師一覧ドロップダウン用データ取得
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
				$data['teachers'][$teacher['teacher_id']] = htmlspecialchars($teacher['teacher_name'], ENT_QUOTES, 'UTF-8');
			}
		}
		
		return $data['teachers'];
	}

	//----------------------------------------------
	// 状態（公開フラグ）ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_public_flag(){
		$this->lang->load('common');
		
		$data['public_flag'] = array();
		$data['public_flag'][0] = $this->lang->line_or_def('common_public',     '公開');
		$data['public_flag'][9] = $this->lang->line_or_def('common_non_public', '非公開');
		
		return $data['public_flag'];
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

} 

/*End of File program.php*/
