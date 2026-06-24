<?php
#[AllowDynamicProperties]
class Cms_student_csv_upload extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		// グループ名（student_group）重複チェック
		$this->load->helper('string_inspection_helper');
		
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
			
			//日弁連対応
			// 管理者（日本弁護士連合会）以外はトップページにリダイレクト
			$login_bar_association_id = $this->libauth->get_bar_association_id();
			if($login_bar_association_id != 1){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
	}
	
	//----------------------------------------------
	// 受講者CSVアップロード画面
	//----------------------------------------------
	function index($offset=0){
		
		$data = array();
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student_csv_upload/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	// 受講者CSVアップロード処理
	//----------------------------------------------
	function student_csv_upload(){
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
		
		$this->form_validation->set_rules('local_file'   , $this->lang->line_or_def('common_file','ファイル'), 'trim|xss_clean');
		
		if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0)){
			// エラーメッセージの設置
			if( isset($_FILES['local_file']) ) {
				$data['upload_error'] = $upload_error_messages[$_FILES['local_file']['error']];
			}
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_csv_upload/index',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
			return;
		}else{

			if( strtolower($_FILES['local_file']['name']) != "研修サイト向けbengosimeibo.csv" ){
				$data['upload_error'] = "アップロードファイル名が不正です。";
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student_csv_upload/index',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
				
				return;
			}


			$data['local_file'] = $this->input->post('local_file');
			
			
			
			$this->load->model('model_student_csv_upload');
			
			//ファイルのアップロード
			$result_data = $this->model_student_csv_upload->student_csv_upload($data);


			// 受講者CSVファイルの情報取得（ファイル名）
			$local_file_name =  $_FILES['local_file']['name'];
			
			// アップロード結果を通知テーブルへ保存
			$update_file_name = "";
			$notice_judge      = "";
			if(!$result_data){
				$update_file_name = '';
				$notice_judge     = "NG";
			}else{
				$update_file_name = $result_data;
				$notice_judge     = "OK";
			}
			$this->load->model('model_notification');
			$notice_result = $this->model_notification->insert_notification(array(
				'notice_kind'  => 'cms-student-csv-upload',
				'id'           => 0,
				'notice_judge' => $notice_judge,
				'school_id'    => $this->libauth->get_school_id(),
				'teacher_id'   => $this->libauth->get_teacher_id(),
				
				'local_file_name'  => $local_file_name,
				'update_file_name' => $update_file_name,
			));

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student_csv_upload/commit',
							'submenu_idx' => 4,
							'view_data'   => $data,
						//	'class_id'    => $this->input->post('class_id'),
						);
			//完了フォーム表示
			$this->_display_view($disp_param);
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
	// [2012/10/12]講座ドロップダウン追加
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
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
	}
} 

/*End of File program.php*/
