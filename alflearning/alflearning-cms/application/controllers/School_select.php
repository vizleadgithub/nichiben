<?php
#[AllowDynamicProperties]
class School_select extends CI_Controller {
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
			if($work_auth['school_select'] == 0){
				redirect('admin_top');
			}
		}

		$this->load->model('model_school');
	}
	
	//----------------------------------------------
	//初期表示
	//----------------------------------------------
	function index(){
		$data = array();
		
		//表示データ設定
		//ドロップダウン用学校一覧取得
//		$data['schools'] = $this->_get_school_list_array();
		$data['school_list'] = $this->model_school->get_school_list_all();
		//デフォルト値設定
		$data['school_id'] = $this->libauth->get_school_id();
		$data['error_msg'] = '';
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'school_select/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//選択決定
	//----------------------------------------------
	function select(){
		// load language
		$this->lang->load('common');
		
		$data = array();
		
		//検証ルールの設定
		$this->form_validation->set_rules('school_id', $this->lang->line_or_def('common_school','学校'), 'trim|numeric|required|is_natural_no_zero');
		
		//検証
		if ($this->form_validation->run() == FALSE){
			//失敗
			//ドロップダウン用学校一覧取得
//			$data['schools'] = $this->_get_school_list_array();
			$data['school_list'] = $this->model_school->get_school_list_all();
			$data['school_id'] = $this->input->post('school_id');
			$data['error_msg'] = '';
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'school_select/index',
							'submenu_idx' => 1,
							'view_data'   => $data,
						);
			//フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			
			// 変更前学校IDを取得
			$old_school_id = $this->libauth->get_school_id();
			
			//セッションに選択学校IDを保存
			$this->libauth->set_school_id($this->input->post('school_id'));
			
			//選択学校IDから、学校名を取得
			$temp_school_name = $this->libauth->select_school_name($this->input->post('school_id'));
			
			//セッションに取得した学校名を保存
			$this->libauth->set_school_name($temp_school_name);
			
			// 管理画面-ログアウトのログ出力
			$this->_cms_insert_log($old_school_id, '管理画面-学校変更');
			
			//管理者トップ画面へ
			redirect('admin_top');
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
		$sub_menu = array();
		
		switch($param['submenu_idx']){
			case 1://
				$sub_menu[1] = anchor("",'') ;
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
		
		$this->load->view($param['view_name'], $param['view_data']);
		
	}
	
//	//----------------------------------------------
//	//学校一覧ドロップダウン用配列取得
//	// [2012/08/31]契約形態を見て学校名前にDEMOの文字追加
//	//----------------------------------------------
//	function _get_school_list_array(){
//		//モデル読み込み
//		$this->load->model('model_school');
//		
//		//一覧ドロップダウン生成
//		$data['school_list'] = $this->model_school->get_school_list_all();
//		$data['schools'] = array();
//		foreach ( $data['school_list'] as $school ) {
//		//	$data['schools'][$school['school_id']] = $school['school_name'];
//			if($school['contract'] === 'demo'){
//				$data['schools'][$school['school_id']] = '[DEMO]'.$school['school_name'];
//			}else if($school['contract'] === 'presentation'){
//				$data['schools'][$school['school_id']] = '[PRESEN]'.$school['school_name'];
//			}else{
//				$data['schools'][$school['school_id']] = $school['school_name'];
//			}
//		}
//		
//		return $data['schools'];
//	}
	
	//----------------------------------------------
	// アプリログ出力
	// ・学校選択
	//----------------------------------------------
	function _cms_insert_log($old_school_id, $log_option = '管理画面'){
		
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
} 

/*End of File program.php*/
