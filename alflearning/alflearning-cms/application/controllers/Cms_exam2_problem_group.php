<?php
#[AllowDynamicProperties]
class Cms_exam2_problem_group extends CI_Controller {
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
//			$work_auth = $this->libauth->get_teacher_auth();
//			if($work_auth['exam2'] == 0){
//				redirect('admin_top');
//			}
			
			// 日弁連ユーザー以外はトップページにリダイレクト
			if($this->libauth->get_bar_association_id() != 1){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の問題（テスト）：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
//		$this->load->model('Modelschoolcontract');
//		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'exam2'))){
//			redirect('admin_top');
//		}
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

		$this->form_validation->set_rules('s_exam2_problem_group_name' , $this->lang->line_or_def('common_exam2_problem_group_name','設問グループ名'), 'trim|xss_clean');
		$this->form_validation->set_rules('s_exam2_problem_id'         , $this->lang->line_or_def('common_exam2_problem_name','設問名')              , 'trim|xss_clean');
		$this->form_validation->set_rules('s_id'                       , $this->lang->line_or_def('common_id','ID')                                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'                , $this->lang->line_or_def('common_freeword','フリーワード')                  , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_exam2_problem_group');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('exam2_problem_group_search_cond') ?: array(
				's_exam2_problem_group_name' => '',
				's_exam2_problem_id' => '',
				's_id' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_exam2_problem_group_name']  = ($this->input->post('s_exam2_problem_group_name') ?? '');
			$data['s_exam2_problem_id']          = ($this->input->post('s_exam2_problem_id') ?? '');
			$data['s_id']                        = ($this->input->post('s_id') ?? '');
			$data['s_free_word']                 = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('exam2_problem_group_search_cond', $data);
		}

		$exam2_problem_group_list = $this->model_exam2_problem_group->get_exam2_problem_group_list(array(
			'school_id'                 => $this->libauth->get_school_id(),
			'offset'                    => $offset,
			'rowcount'                  => $per_page,
			's_exam2_problem_group_name' => $data['s_exam2_problem_group_name'],  //search
			's_exam2_problem_id'         => $data['s_exam2_problem_id'],          //search
			's_exam2_problem_group_id'   => $data['s_id'],                       //search
			's_free_word'               => $data['s_free_word'],                //search
		));
		$data['exam2_problem_group_list'] = $exam2_problem_group_list['items'];
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_exam2_problem_group/index';
		$config['per_page']   = $per_page;
		$config['total_rows'] = $exam2_problem_group_list['cnt'];
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
						'view_name'   => 'cms_exam2_problem_group/index',
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
		$data['exam2_problem_group']['update_flg']                  = 0;
		$data['exam2_problem_group']['exam2_problem_group_id']       = 0;        // 設問グループID
		$data['exam2_problem_group']['exam2_problem_group_name']     = '';       // 設問グループ名
		$data['exam2_problem_group']['exam2_problem_group_caption']  = '';       // 説明
		$data['exam2_problem_group']['position_exam2_problems']      = array();  // 設問ID群
		$data['exam2_problem_group']['position_exam2_problems_name'] = '';       // 設問名

		// 有効な設問総数数の取得 --- model_cource 側、開発限定の機能を付けている
		$this->load->model('model_cource');
		$all_count = $this->model_cource->get_effective_items(
			array(
				'school_id' => $this->libauth->get_school_id(),
				)
		);
		$data['exam2_problem_group']['exam2_problem_all_count'] =$all_count['exam2_problem_all_count'];

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_exam2_problem_group/edit',
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
			redirect('/cms_exam2_problem_group/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['exam2_problem_group_id']) &&  $edit_form_data['exam2_problem_group_id'] <> ''){
			//画面表示用データ設定
			$data['exam2_problem_group'] = $edit_form_data;

			// 有効な設問総数数の取得 --- model_cource 側、開発限定の機能を付けている
			$this->load->model('model_cource');
			$all_count = $this->model_cource->get_effective_items(
				array(
					'school_id' => $this->libauth->get_school_id(),
					)
			);
			$data['exam2_problem_group']['exam2_problem_all_count'] =$all_count['exam2_problem_all_count'];

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_exam2_problem_group/edit',
							'submenu_idx' => ($data['exam2_problem_group']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('exam2_problem_group');
			
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
	function detail($exam2_problem_group_id){

		$this->load->helper('json');
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_exam2_problem_group');
		
		//データ取得用引数設定
		$data_param = array(
						'exam2_problem_group_id' => $exam2_problem_group_id,
					);
		//データ取得
		$db_data = $this->model_exam2_problem_group->get_exam2_problem_group($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['exam2_problem_group']['update_flg']                 = 1;
			$data['exam2_problem_group']['exam2_problem_group_id']      = $db_data['exam2_problem_group_id'];
			$data['exam2_problem_group']['exam2_problem_group_name']    = $db_data['exam2_problem_group_name'];
			$data['exam2_problem_group']['exam2_problem_group_caption'] = $db_data['exam2_problem_group_caption'];
			
			// 設問グループ所属の、設問ID・設問名（表示用）の取得
			$data_param = array(
							'school_id'             => $this->libauth->get_school_id(),
							'exam2_problem_group_id' => $db_data['exam2_problem_group_id'],
						);
			$data_results = $this->model_exam2_problem_group->get_exam2_problem_group_position_exam2_problem($data_param);

			$data['exam2_problem_group']['position_exam2_problems']      = array();
			$data['exam2_problem_group']['position_exam2_problems_name'] = '';
			if(isset($data_results)){
				$idx = -1;
				foreach($data_results as $data_result){
					$idx++;
					$data['exam2_problem_group']['position_exam2_problems'][$idx]    = $data_result['exam2_problem_id'];
					
					if($data['exam2_problem_group']['position_exam2_problems_name'] != ''){
						$data['exam2_problem_group']['position_exam2_problems_name'] .= '<br/>';
					}
					$data['exam2_problem_group']['position_exam2_problems_name'] .= '[No'.$data_result['exam2_problem_id'].']&nbsp;'.$data_result['exam2_problem_name'].'&nbsp;';
					if( getenv('URL_SERVICE')!='mitemo' ){
						$data['exam2_problem_group']['position_exam2_problems_name'] .= '['.$this->lang->line_or_def('common_management_teacher','管理講師').':'.$data_result['teacher_name'].']&nbsp;';
					}
					$data['exam2_problem_group']['position_exam2_problems_name'] .= '['.$this->lang->line_or_def('common_exam2_answer_points','解答配点').':'.$data_result['answer_point'].']';
				}
			}
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['exam2_problem_group']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_exam2_problem_group/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
							'exam2_problem_group_id'     => $db_data['exam2_problem_group_id'],
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_exam2_problem_group/");
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
		$this->form_validation->set_rules('update_flg'                 , $this->lang->line_or_def('common_flg','flg')                                     , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_problem_group_id'      , $this->lang->line_or_def('common_id','ID')                                       , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_problem_group_name'    , $this->lang->line_or_def('common_exam2_problem_group_name_name','設問グループ名') , 'trim|xss_clean|required');
		$this->form_validation->set_rules('exam2_problem_group_caption' , $this->lang->line_or_def('common_caption','説明')                                , 'trim|xss_clean');
		//$this->form_validation->set_rules('position_exam2_problems'     , $this->lang->line_or_def('common_exam2_problem','設問')                           , 'xss_clean');
		
		// [Ajax]選択された設問IDの整形（取得・ID昇順）
		$position_exam2_problems = $this->input->post('position_exam2_problems')?$this->input->post('position_exam2_problems'):array();
		sort($position_exam2_problems);

		// 配列型の所属講座・受講者・設問の値をチェック
		$this->load->helper('string_inspection_helper');

		if(!check_array_data_num(array($position_exam2_problems))){
			$this->lang->load('error');
			$error_data['returnurl']       = site_url('admin_top');    // site_url('login_page/logout'); 
			$error_data['error_message']   = $this->lang->line_or_def('error_unjust_access','不正アクセスを検知しました<br />ログインし直してください');
			$error_data['select_callview'] = 'admin_top';
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'wide_use_error',
							'submenu_idx' => 4,
							'view_data'   => $error_data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			// 設問グループ名重複チェック
			$overlap_error_msg = "";
			$query = $this->db->query("
				 SELECT * 
				   FROM exam2_problem_group 
				  WHERE exam2_problem_group_name = ? 
				    AND school_id = ? 
				    AND status = 0 
				    LIMIT 0, 1",
				array(
					$this->input->post('exam2_problem_group_name'),
					$this->libauth->get_school_id(),
				)
			);
			if(!$this->input->post('update_flg') && $query->row()){
				$overlap_error_msg = $this->lang->line_or_def('error_registered_problem_group','登録済みの設問グループ名です');
			}
			
			//検証
			//if($this->form_validation->run() == FALSE){
			if($this->form_validation->run() == FALSE || ($overlap_error_msg != "") ){
				//失敗
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['exam2_problem_group']['update_flg']                  = $this->input->post('update_flg');
				$data['exam2_problem_group']['exam2_problem_group_id']       = $this->input->post('exam2_problem_group_id');
				$data['exam2_problem_group']['exam2_problem_group_name']     = '';
				$data['exam2_problem_group']['exam2_problem_group_caption']  = '';
				$data['exam2_problem_group']['position_exam2_problems']      = $position_exam2_problems;
				
				// 設問名重複エラーメッセージ追加
				$data['overlap_error_msg']                                 = $overlap_error_msg;
				
				// 有効な設問総数数の取得 --- model_cource 側、開発限定の機能を付けている
				$this->load->model('model_cource');
				$all_count = $this->model_cource->get_effective_items(
					array(
						'school_id' => $this->libauth->get_school_id(),
						)
				);
				$data['exam2_problem_group']['exam2_problem_all_count'] =$all_count['exam2_problem_all_count'];
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem_group/edit',
								'submenu_idx' => ($data['exam2_problem_group']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}else{
				//成功
				//ボタン表示設定を編集確認に設定
				$data['btn_kirikae_flg'] = 1;
				
				//確認画面用データ設定
				$data['exam2_problem_group']['update_flg']                  = $this->input->post('update_flg');
				$data['exam2_problem_group']['exam2_problem_group_id']       = $this->input->post('exam2_problem_group_id');
				$data['exam2_problem_group']['exam2_problem_group_name']     = $this->input->post('exam2_problem_group_name');
				$data['exam2_problem_group']['exam2_problem_group_caption']  = $this->input->post('exam2_problem_group_caption');
				$data['exam2_problem_group']['school_id']                   = $this->libauth->get_school_id();
				$data['exam2_problem_group']['position_exam2_problems']      = $position_exam2_problems;
				
				//セッションへ検証済みデータを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['exam2_problem_group']));
				
				
				
				// 選択された設問名の取得
				$this->load->model('model_exam2_problem');
				$data['exam2_problem_group']['position_exam2_problems_name'] = '';
				if($data['exam2_problem_group']['position_exam2_problems']){
					foreach($data['exam2_problem_group']['position_exam2_problems'] as $idx => $lecture){
						//データ取得用引数設定
						$name_param = array(
										'exam2_problem_id' => $lecture,
									);
						$data_result = $this->model_exam2_problem->get_exam2_problem($name_param);
						if($data['exam2_problem_group']['position_exam2_problems_name'] != ''){
							$data['exam2_problem_group']['position_exam2_problems_name'] .= '<br/>';
						}
						$data['exam2_problem_group']['position_exam2_problems_name'] .= '[No'.$data_result['exam2_problem_id'].']&nbsp;'.$data_result['exam2_problem_name'].'&nbsp;';
						if( getenv('URL_SERVICE')!='mitemo' ){
							$data['exam2_problem_group']['position_exam2_problems_name'] .= '['.$this->lang->line_or_def('common_management_teacher','管理講師').':'.$data_result['teacher_name'].']&nbsp;';
						}
						$data['exam2_problem_group']['position_exam2_problems_name'] .= '['.$this->lang->line_or_def('common_exam2_answer_points','解答配点').':'.$data_result['answer_point'].']';
					}
					
				}
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem_group/confirm',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}
		}
	}

	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	//----------------------------------------------
	function commit(){
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));

		if(isset($edit_form_data['exam2_problem_group_id']) && $edit_form_data['exam2_problem_group_id'] != ""){
			//モデル読み込み
			$this->load->model('model_exam2_problem_group');
			
			//更新用引数設定
			$update_param = array(
							'data'   => $edit_form_data,
						);

			// 設問グループテーブル更新（設問グループ関係テーブルも同時更新）
			$exam2_problem_result = $this->model_exam2_problem_group->update_exam2_problem_group($update_param);
			
			//ビュー設定引数設定
			$data = array();
			$disp_param = array(
							'view_name'   => 'cms_exam2_problem_group/commit',
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
			$data['returnurl'] = site_url('exam2_problem_group');
			
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
	function delete_item($exam2_problem_group_id){
		//モデル読み込み
		$this->load->model('model_exam2_problem_group');
		
		//データ削除
		$data = $this->model_exam2_problem_group->delete_exam2_problem_group(array(
			'exam2_problem_group_id' => $exam2_problem_group_id,
		));
		
		//完了フォーム表示
		$this->_display_view(array(
			'view_name'   => 'cms_exam2_problem_group/commit',
			'submenu_idx' => 4,
			'view_data'   => array(),
			//'class_id'    => $this->input->post('class_id'),
		));

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
							'exam2_problem_id'    => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					'exam2_problem_id'    => $param['exam2_problem_id'],
					);
		
		//設問名ドロップダウン用データ取得（index）
		$param['view_data']['exam2_problem_dropdown']  = $this->_get_exam2_problem_list_array($drop_param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
	}

	//----------------------------------------------
	// 設問名ドロップダウン用配列取得
	//----------------------------------------------
	function _get_exam2_problem_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_exam2_problem');
		
		//一覧ドロップダウン生成
		$data['exam2_problem_list'] = $this->model_exam2_problem->get_cource_exam2_problem($param);
		$data['exam2_problems'] = array();
		if( count($data['exam2_problem_list']) != 0 ) {
			$data['exam2_problems'][''] = '';
			foreach ( $data['exam2_problem_list'] as $exam2_problem ) {
				$data['exam2_problems'][$exam2_problem['exam2_problem_id']] = $exam2_problem['exam2_problem_name'];
			}
		}
		
		return $data['exam2_problems'];
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





	//----------------------------------------------
	//試験管理の権限有無確認
	//  ログインユーザに該当課題の修正・削除権限があるかを確認
	//  Super User：修正・削除ＯＫ
	//  学校管理者：同学校内のみ、修正・削除ＯＫ
	//  一般講師　：自分が管理講師の試験 + 課題管理の権限あり の場合、修正・削除ＯＫ
	//----------------------------------------------
	function _get_auth_exam2($exam2_id = 0){
		$login_teacher_id  = $this->libauth->get_teacher_id();    // ログイン講師ID
		$work_auth         = $this->libauth->get_teacher_auth();  // ログイン講師の権限
		$auth_exam2         = 0;                                   // 権限有無フラグ（0：なし、1以上：あり）
		
		if($login_teacher_id < 0){
			// SuperUser
			$auth_exam2 = 1;
		}elseif($work_auth['school_admin'] == 1){
			// 学校管理者
			$auth_exam2 = 1;
		//}elseif( ($work_auth['exam2'] == 1) || ($work_auth['exam2_no_eval'] == 1) ){
		}elseif( (isset($work_auth['exam2'])) && ($work_auth['exam2'] == 1) ){
			// 講師、且つ、試験管理
			
			// 試験の再取得
			$this->load->model('model_exam2');
			$exam2_data = $this->model_exam2->get_exam2(array(
							'exam2_id' => $exam2_id,
						));
			// 課題の管理講師 = ログイン講師
			if($exam2_data['teacher_id'] == $login_teacher_id){
				$auth_exam2 = 2;
			}
		}else{
			// その他
			$auth_exam2 = 0;
		}
		
		// 戻り値
		return $auth_exam2;
	}


	//----------------------------------------------
	// [Ajax用]学校所属の設問グループを取得
	//----------------------------------------------
	function get_cource_exam2_problem_group(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"              => $this->libauth->get_school_id(),
					"select_exam2_problem_id" => $this->input->post('select_exam2_problem_id'),
					);
		
		// 学校ID・設問IDに一致する設問グループ名・設問ID群を取得
		$this->load->model('model_exam2_problem_group');
		$exam2_problem_group = $this->model_exam2_problem_group->get_exam2_problem_group_id_list($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exam2_problem_group));
	}

} 

/*End of File program.php*/
