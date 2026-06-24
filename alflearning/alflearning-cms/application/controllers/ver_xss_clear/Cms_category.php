<?php
#[AllowDynamicProperties]
class Cms_category extends CI_Controller {
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
//			if($work_auth['exam'] == 0){
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
//		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'exam'))){
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
		
		//$this->form_validation->set_rules('s_id'                      , $this->lang->line_or_def('common_id','ID')                                 , 'trim');
		$this->form_validation->set_rules('s_free_word'               , $this->lang->line_or_def('common_freeword','フリーワード')                 , 'trim|xss_clean');
		$this->form_validation->run();

		//モデル読み込み
		$this->load->model('model_category');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('category_search_cond') ?: array(
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_free_word'] = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('category_search_cond', $data);
		}

		$category_list = $this->model_category->get_category_list(array(
			'school_id'                 => $this->libauth->get_school_id(),
			's_free_word'               => $data['s_free_word'],                //search
		));
		$data['category_list'] = $category_list;

		$this->load->model('model_category');
		$parent_root_category_list = $this->model_category->get_root_category_list();
		$data['parent_root_category_list'] = $parent_root_category_list;
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_category/index',
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
		$data['category']['update_flg']    = 0;
		$data['category']['term_id']       = 0;
		$data['category']['name']          = '';
		$data['category']['slug']          = '';
		$data['category']['parent']        = 21;

		$this->load->model('model_category');
		$parent_root_category_list = $this->model_category->get_root_category_list();
		$data['parent_root_category_list'] = $parent_root_category_list;

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_category/edit',
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
			redirect('/cms_category/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['term_id']) &&  $edit_form_data['term_id'] <> ''){
			//画面表示用データ設定
			$data['category'] = $edit_form_data;

			$this->load->model('model_category');
			$parent_root_category_list = $this->model_category->get_root_category_list();
			$data['parent_root_category_list'] = $parent_root_category_list;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_category/edit',
							'submenu_idx' => ($data['category']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('category');

			$this->load->model('model_category');
			$parent_root_category_list = $this->model_category->get_root_category_list();
			$data['parent_root_category_list'] = $parent_root_category_list;

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
	function detail($term_id){

		$this->load->helper('json');
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_category');
		
		//データ取得用引数設定
		$data_param = array(
			'term_id' => $term_id,
		);
		//データ取得
		$db_data = $this->model_category->get_category($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['category']['update_flg'] = 1;
			$data['category']['term_id']    = $db_data['term_id'];
			$data['category']['name']       = $db_data['name'];
			$data['category']['slug']       = $db_data['slug'];
			$data['category']['parent']     = $db_data['parent'];
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['category']));

			$this->load->model('model_category');
			$parent_root_category_list = $this->model_category->get_root_category_list();
			$data['parent_root_category_list'] = $parent_root_category_list;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_category/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
							'term_id'     => $db_data['term_id'],
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_category/");
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
		$this->form_validation->set_rules('update_flg'   , $this->lang->line_or_def('common_flg','flg')                                     , 'trim|numeric');
		$this->form_validation->set_rules('term_id'      , $this->lang->line_or_def('common_id','ID')                                       , 'trim|numeric');
		$this->form_validation->set_rules('name'         , $this->lang->line_or_def('common_name','カテゴリ名')                             , 'trim|required|max_length[40]');
		$this->form_validation->set_rules('slug'         , $this->lang->line_or_def('common_slug','スラッグ')                             , 'trim|required');
		$this->form_validation->set_rules('parent'       , $this->lang->line_or_def('common_parent','親カテゴリ')                           , 'trim');


		//検証
		if($this->form_validation->run() == FALSE ){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['category']['update_flg'] = $this->input->post('update_flg') ?? 0;
			$data['category']['term_id']    = $this->input->post('term_id') ?? 0;
			$data['category']['name']       = $this->input->post('name') ?? '';
			$data['category']['slug']       = $this->input->post('slug') ?? '';
			$data['category']['parent']     = $this->input->post('name') ?? 21;
			
			$this->load->model('model_category');
			$parent_root_category_list = $this->model_category->get_root_category_list();
			$data['parent_root_category_list'] = $parent_root_category_list;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_category/edit',
							'submenu_idx' => ($data['category']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['category']['update_flg'] = $this->input->post('update_flg') ?? 0;
			$data['category']['term_id']    = $this->input->post('term_id') ?? 0;
			$data['category']['name']       = $this->input->post('name') ?? '';
			$data['category']['slug']       = $this->input->post('slug') ?? '';
			$data['category']['parent']     = $this->input->post('parent') ?? 21;
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['category']));
			
			$this->load->model('model_category');
			$parent_root_category_list = $this->model_category->get_root_category_list();
			$data['parent_root_category_list'] = $parent_root_category_list;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_category/confirm',
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

		if(isset($edit_form_data['term_id']) && $edit_form_data['term_id'] != ""){
			//モデル読み込み
			$this->load->model('model_category');
			
			//更新用引数設定
			$update_param = array(
							'data'   => $edit_form_data,
						);

			// テーブル更新
			$category_result = $this->model_category->update_category($update_param);
			
			//ビュー設定引数設定
			$data = array();
			$disp_param = array(
				'view_name'   => 'cms_category/commit',
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
			$data['returnurl'] = site_url('category');
			
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
	function delete_item($term_id){
		//モデル読み込み
		$this->load->model('model_category');
		
		//データ削除
		$data = $this->model_category->delete_category(array(
			'term_id' => $term_id,
		));
		
		//完了フォーム表示
		$this->_display_view(array(
			'view_name'   => 'cms_category/commit',
			'submenu_idx' => 4,
			'view_data'   => array(),
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
							'exam_problem_id'    => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					'exam_problem_id'    => $param['exam_problem_id'],
					);
		
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
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
	function _get_auth_exam($exam_id = 0){
		$login_teacher_id  = $this->libauth->get_teacher_id();    // ログイン講師ID
		$work_auth         = $this->libauth->get_teacher_auth();  // ログイン講師の権限
		$auth_exam         = 0;                                   // 権限有無フラグ（0：なし、1以上：あり）
		
		if($login_teacher_id < 0){
			// SuperUser
			$auth_exam = 1;
		}elseif($work_auth['school_admin'] == 1){
			// 学校管理者
			$auth_exam = 1;
		//}elseif( ($work_auth['exam'] == 1) || ($work_auth['exam_no_eval'] == 1) ){
		}elseif( (isset($work_auth['exam'])) && ($work_auth['exam'] == 1) ){
			// 講師、且つ、試験管理
			
			// 試験の再取得
			$this->load->model('model_exam');
			$exam_data = $this->model_exam->get_exam(array(
							'exam_id' => $exam_id,
						));
			// 課題の管理講師 = ログイン講師
			if($exam_data['teacher_id'] == $login_teacher_id){
				$auth_exam = 2;
			}
		}else{
			// その他
			$auth_exam = 0;
		}
		
		// 戻り値
		return $auth_exam;
	}


	//----------------------------------------------
	// [Ajax用]学校所属の設問グループを取得
	//----------------------------------------------
	function get_cource_exam_problem_group(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"              => $this->libauth->get_school_id(),
					"select_exam_problem_id" => $this->input->post('select_exam_problem_id'),
					);
		
		// 学校ID・設問IDに一致する設問グループ名・設問ID群を取得
		$this->load->model('model_exam_problem_group');
		$exam_problem_group = $this->model_exam_problem_group->get_exam_problem_group_id_list($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($exam_problem_group));
	}

} 

/*End of File program.php*/
