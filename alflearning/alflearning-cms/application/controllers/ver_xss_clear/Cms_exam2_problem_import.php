<?php
#[AllowDynamicProperties]
class Cms_exam2_problem_import extends CI_Controller {
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
	// 
	//----------------------------------------------
	function index($offset=0){
		$this->edit();
	}

	//----------------------------------------------
	//インポートフォーム表示
	//----------------------------------------------
	function edit(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		$data['exam2_problem']['teacher_id']            = $this->libauth->get_teacher_id();
		$data['exam2_problem']['exam2_problem_lectures'] = array();  // 所属講座群
		$data['exam2_problem']['exam2_problem_groups']   = array();  // 設問グループ
		$data['exam2_problem']['local_file']            = '';

		// mitemo対応（管理講師を裏側で持つ）
		if( getenv('URL_SERVICE')=='mitemo' ){
			$this->load->model('model_teacher');
			$data['exam2_problem']['teacher_id'] = $this->model_teacher->get_teacher_representative_school($this->libauth->get_school_id());
		}

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_exam2_problem_import/edit',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}

	//----------------------------------------------
	//インポート実行画面表示
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//モデル読み込み
	//	$this->load->model('model_exam2_problem');
		
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
		$this->form_validation->set_rules('teacher_id'            , $this->lang->line_or_def('common_management_teacher','管理講師')     , 'required|xss_clean');
		$this->form_validation->set_rules('exam2_problem_lectures[]' , $this->lang->line_or_def('common_position_course','所属講座')        , 'callback_check_required_checkbox');
		//$this->form_validation->set_rules('exam2_problem_groups[]'   , $this->lang->line_or_def('common_exam2_problem_group','設問グループ') , 'xss_clean');
		$this->form_validation->set_rules('local_file'            , $this->lang->line_or_def('common_file','ファイル')                   , 'trim|xss_clean');
		
		// 配列型の所属講座・設問グループの値をチェック
		$this->load->helper('string_inspection_helper');

		$check_exam2_problem_lectures = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
		$check_exam2_problem_groups   = $this->input->post('exam2_problem_groups')?$this->input->post('exam2_problem_groups'):array();

		if(!check_array_data_num(array($check_exam2_problem_lectures, $check_exam2_problem_groups))){
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
			//管理講師と所属講座の関係チェック
			$check_teacher_id                  = $this->input->post('teacher_id')?$this->input->post('teacher_id'):0;
			$check_exam2_problem_lectures       = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
			$check_exam2_problem_lectures_count = count($check_exam2_problem_lectures);
			$lecture_error_msg                 = "";
			$local_file_error_msg              = "";
			
			// 管理講師から所属講座を取得
			$this->load->model('model_teacher');
//			$lectures = $this->model_teacher->get_teacher_lecture(array(
//				'teacher_id' => $check_teacher_id,
//			));
			// 選択した管理講師が所属講座を持つ場合、所属講座内に選択した所属講座がない場合はエラーとする
//			if(isset($lectures)){
//				$check_flag = 0;
//				foreach($lectures as $lecture){
//					if(in_array($lecture['cource_id'], $check_exam2_problem_lectures)) {
//						$check_flag = $check_flag + 1;
//						continue;
//					}
//				}
//				if($check_flag != $check_exam2_problem_lectures_count){
//					$lecture_error_msg = $this->lang->line_or_def('error_teacher_lecture_matching','選択した管理講師が所属していない所属講座が選択されています');
//				}
//			}

			// インポート対象ファイルのチェック（拡張子がcsv以外はエラー）
			if( (isset($_FILES['local_file'])) && ($_FILES['local_file']["error"] == 0) ){
				$ext = strtolower(pathinfo($_FILES['local_file']['name'], PATHINFO_EXTENSION));
				if($ext != 'csv'){
					$local_file_error_msg = $this->lang->line_or_def('error_file_csv_only','ファイルは「csv形式」のみ有効です');
				}
			}
			
			//if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0)){
			if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0) || ($lecture_error_msg != "") || ($local_file_error_msg != "")){
				//失敗
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['exam2_problem']['teacher_id']            = $this->input->post('teacher_id');
				$data['exam2_problem']['exam2_problem_lectures'] = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
				$data['exam2_problem']['exam2_problem_groups']   = $this->input->post('exam2_problem_groups')  ?$this->input->post('exam2_problem_groups')  :array();
				$data['exam2_problem']['local_file']            = '';

				//エラーメッセージ設定
				if (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0) {
					$data['upload_error'] = $upload_error_messages[$_FILES['local_file']['error']];
				}
				$data['lecture_error']    = $lecture_error_msg;
				$data['local_file_error'] = $local_file_error_msg;

				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem_import/edit',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
				
				return;
			}else{
				// 成功
				
				// ファイルを /tmp ディレクトリにコピー
				$this->load->library('upload', array(
					'upload_path'   => '/tmp',
					'allowed_types' => '*',
					'overwrite'     => TRUE,
					'remove_spaces' => TRUE,
					'file_name'     => 'school_'.$this->libauth->get_school_id().'_exam2_problem',
				));
				if(!$this->upload->do_upload('local_file')){
				}else{
					$upload_data = $this->upload->data();
					chmod('/tmp/'.$upload_data['file_name'],0777);
				}

				$data['exam2_problem']['teacher_id']            = $this->input->post('teacher_id');
				$data['exam2_problem']['exam2_problem_lectures'] = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
				$data['exam2_problem']['exam2_problem_groups']   = $this->input->post('exam2_problem_groups')  ?$this->input->post('exam2_problem_groups')  :array();
				$data['exam2_problem']['csv_file_name']         = '/tmp/'.$upload_data['file_name'];
				$data['exam2_problem']['school_id']             = $this->libauth->get_school_id();
				
				//セッションへ検証済みデータを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['exam2_problem']));

				// 管理講師名の取得
				$this->load->model('model_teacher');
				$data_param = array(
								'teacher_id' => $data['exam2_problem']['teacher_id'],
							);
				$db_data = $this->model_teacher->get_teacher($data_param);
				$data['exam2_problem']['teacher_name'] = $db_data['teacher_name'];


				// 所属講座名の取得
				$data['exam2_problem']['exam2_problem_lectures_name'] = array();
				$this->load->model('model_cource');
				if($data['exam2_problem']['exam2_problem_lectures']){
					foreach($data['exam2_problem']['exam2_problem_lectures'] as $idx => $lecture){
						$name_param = array(
										'cource_id' => $lecture,
									);
						if($this->model_cource->get_name($name_param)){
							$data['exam2_problem']['exam2_problem_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
						}
					}
					//連想キーと要素との関係を維持しつつ配列をソート
					asort($data['exam2_problem']['exam2_problem_lectures_name']);
				}

				// 設問グループ名の取得
				$data['exam2_problem']['exam2_problem_groups_name'] = array();
				$this->load->model('model_exam2_problem_group');
				if($data['exam2_problem']['exam2_problem_groups']){
					foreach($data['exam2_problem']['exam2_problem_groups'] as $idx => $exam2_problem_group_id){
						$name_param = array(
										'exam2_problem_group_id' => $exam2_problem_group_id,
									);
						$exam2_problem_group = $this->model_exam2_problem_group->get_exam2_problem_group($name_param);
						if($exam2_problem_group){
							$data['exam2_problem']['exam2_problem_groups_name'][$idx] = $exam2_problem_group['exam2_problem_group_name'];
						}
					}
					//連想キーと要素との関係を維持しつつ配列をソート
					asort($data['exam2_problem']['exam2_problem_groups_name']);
				}
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem_import/confirm',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}
		}
	}

	//----------------------------------------------
	//[Ajax]インポート処理実行
	//----------------------------------------------
	function commit(){
		// load language
		$this->lang->load('msg');
		$this->lang->load('error');
		
		//モデル読み込み
		$this->load->model('model_exam2_problem');
		$this->load->model('model_exam2_problem_group');
		$this->load->helper('json');

		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		// 戻り値初期化
		$import_result = null;
		
		// POSTした学校ID取得
		$select_school_id = $this->input->post('select_school_id')?$this->input->post('select_school_id'):0;
		
		// セッション内学校IDと、POSTした学校IDが同じ場合、取り込み処理
		if(isset($edit_form_data['school_id']) && $edit_form_data['school_id'] == $select_school_id){
			// セッションから各値取得
			$teacher_id            = $edit_form_data['teacher_id'];
			$exam2_problem_lectures = $edit_form_data['exam2_problem_lectures'];
			$exam2_problem_groups   = $edit_form_data['exam2_problem_groups'];
			$csv_file_name         = $edit_form_data['csv_file_name'];
			$school_id             = $edit_form_data['school_id'];
			
			// インポート対象ファイルを開く
			$fp = fopen($csv_file_name, 'r');
			
			$import_result = array();
			$counter       = 0;
			while(!feof($fp)){
				// 行数
				$counter = $counter + 1;
				
				// カンマ区切り文字列を配列化（文字コードも変更）
				$arrayRecord = $this->fgetcsv($fp,  filesize($csv_file_name), ",");
				
				$temp_import_result = array();  // 戻り値情報格納
				$exam2_problem       = array();  // テーブル登録情報格納
				
				// 配列内容確認にserialize($arrayRecord);
				
				// 配列数の確認（個数 10 以上）
				if( count($arrayRecord) < 2 ){
					$temp_import_result['no']      = $counter;
					$temp_import_result['value']   = '--';
					$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_blank_line','空白行');
				}elseif( count($arrayRecord) < 10 ){
					$temp_import_result['no']      = $counter;
					$temp_import_result['value']   = 'NG';
					$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_items_lack','項目数が足りません');
				}
				
				// [0]設問名（入力必須）
				$arrRecordIndex = 0;
				if( count($temp_import_result)==0 ){
					if( !empty($arrayRecord[$arrRecordIndex]) ){
						$exam2_problem['exam2_problem_name'] = $arrayRecord[$arrRecordIndex];
					}else{
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_exam2_problem_name','設問名は必須入力です');
					}
				}
				
				// [1]設問種類（1 or 2 or 3）
				$arrRecordIndex = 1;
				if( count($temp_import_result)==0 ){
					//if( ($arrayRecord[$arrRecordIndex]==1) || ($arrayRecord[$arrRecordIndex]==2) || ($arrayRecord[$arrRecordIndex]==3) ){
					if( ($arrayRecord[$arrRecordIndex]==1) ){
						$exam2_problem['problem_kind'] = $arrayRecord[$arrRecordIndex];
					}else{
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						//$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_problem_kind','設問種類は1、2、3、いづれかが必要です');
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_problem_kind','設問種類は1、が必要です');
					}
				}
				
				// [2]設問内容（入力必須、且つビデオ・図書室の場合は使用可能ビデオ・図書室であること）
				$arrRecordIndex = 2;
				if( count($temp_import_result)==0 ){
					if( empty($arrayRecord[$arrRecordIndex]) ){
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_problem_contents','設問内容は必須入力です');
					}else{
						$exam2_problem['problem_contents_text']         = '';
						$exam2_problem['problem_contents_video']        = 0;
						$exam2_problem['problem_contents_book_library'] = 0;
						
						if( ($exam2_problem['problem_kind']==2) ){
							// ビデオID
							$check_param = array(
											'video_id'              => (int)$arrayRecord[$arrRecordIndex],
											'exam2_problem_lectures' => $exam2_problem_lectures,
											'school_id'             => $school_id,
							);
							$check_result = $this->model_exam2_problem->check_exam2_problem_video($check_param);
							
							if( empty($check_result) ){
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_problem_contents_video_id','設問内容のビデオIDが見つかりません');
							}else{
								$exam2_problem['problem_contents_video'] = $arrayRecord[$arrRecordIndex];
							}
						}elseif( ($exam2_problem['problem_kind']==3) ){
							// 図書室ID
							$check_param = array(
											'book_library_id'       => (int)$arrayRecord[$arrRecordIndex],
											'exam2_problem_lectures' => $exam2_problem_lectures,
											'school_id'             => $school_id,
							);
							
							$check_result = $this->model_exam2_problem->check_exam2_problem_book_library($check_param);
							
							if( empty($check_result) ){
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_problem_contents_book_library_id','設問内容の図書室IDが見つかりません');
							}else{
								$exam2_problem['problem_contents_book_library'] = $arrayRecord[$arrRecordIndex];
							}
						}else{
							// テキスト
							$exam2_problem['problem_contents_text'] = $arrayRecord[$arrRecordIndex];
						}
					}
				}
				
				// [3]設問備考（任意）
				$arrRecordIndex = 3;
				if( count($temp_import_result)==0 ){
					$exam2_problem['problem_note'] = $arrayRecord[$arrRecordIndex];
				}
				
				// [4]解答種類（1 or 2 or 3）
				$arrRecordIndex = 4;
				if( count($temp_import_result)==0 ){
					if( ($arrayRecord[$arrRecordIndex]==1) || ($arrayRecord[$arrRecordIndex]==2) || ($arrayRecord[$arrRecordIndex]==3) ){
						$exam2_problem['answer_kind'] = $arrayRecord[$arrRecordIndex];
					}else{
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_answer_kind','解答種類は1、2、3、いづれかが必要です');
					}
				}
				
				// [5]解答配点（0 以上）
				// 値なしは intval にて、ゼロになるため、ゼロとして登録される
				$arrRecordIndex = 5;
				if( count($temp_import_result)==0 ){
					if( (is_numeric($arrayRecord[$arrRecordIndex])) && (intval($arrayRecord[$arrRecordIndex])>-1) ) {
						$exam2_problem['answer_point'] = intval($arrayRecord[$arrRecordIndex]);
					}else{
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_answer_point','解答配点はゼロ以上の数字入力です');
					}
				}
				
				// [6]解答解説種類（1 or 2 or 3 or 9）
				$arrRecordIndex = 6;
				if( count($temp_import_result)==0 ){
					//if( ($arrayRecord[$arrRecordIndex]==1) || ($arrayRecord[$arrRecordIndex]==2) || ($arrayRecord[$arrRecordIndex]==3) || ($arrayRecord[$arrRecordIndex]==9) ){
					if( ($arrayRecord[$arrRecordIndex]==1) || ($arrayRecord[$arrRecordIndex]==9) ){
						$exam2_problem['answer_explain_kind'] = $arrayRecord[$arrRecordIndex];
					}else{
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = 'NG';
						//$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_answer_explain_kind','解答解説種類は1、2、3、9、いづれかが必要です');
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_ng_answer_explain_kind','解答解説種類は1、9、いづれかが必要です');
					}
				}
				
				// [7]解答解説内容（解答解説種類 = 1 or 2 or 3 → 入力必須、且つビデオ・図書室の場合は使用可能ビデオ・図書室であること。解答解説種類 = 9 → 空白許容）
				$arrRecordIndex = 7;
				if( count($temp_import_result)==0 ){
					$exam2_problem['answer_explain_contents_text']         = '';
					$exam2_problem['answer_explain_contents_video']        = 0;
					$exam2_problem['answer_explain_contents_book_library'] = 0;
					
					if( empty($arrayRecord[$arrRecordIndex]) ){
						if( ($exam2_problem['answer_explain_kind']==9) ){
							// 解答解説種類 ＝ 9 の場合、強制文字列ゼロ
							$exam2_problem['answer_explain_contents_text'] = '';
						}else{
							// 解答解説種類 ≠ 9 の場合、入力必須のため、エラー
							$temp_import_result['no']      = $counter;
							$temp_import_result['value']   = 'NG';
							$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_explain_contents','解答解説種類に指定がある場合、解答解説内容は必須入力です');
						}
					}else{
						if( ($exam2_problem['answer_explain_kind']==2) ){
							// ビデオID
							$check_param = array(
											'video_id'              => (int)$arrayRecord[$arrRecordIndex],
											'exam2_problem_lectures' => $exam2_problem_lectures,
											'school_id'             => $school_id,
							);
							$check_result = $this->model_exam2_problem->check_exam2_problem_video($check_param);
							
							if( empty($check_result) ){
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_explain_contents_video_id','解答解説内容のビデオIDが見つかりません');
							}else{
								$exam2_problem['answer_explain_contents_video'] = $arrayRecord[$arrRecordIndex];
							}
						}elseif( ($exam2_problem['answer_explain_kind']==3) ){
							// 図書室ID
							$check_param = array(
											'book_library_id'       => (int)$arrayRecord[$arrRecordIndex],
											'exam2_problem_lectures' => $exam2_problem_lectures,
											'school_id'             => $school_id,
							);
							
							$check_result = $this->model_exam2_problem->check_exam2_problem_book_library($check_param);
							
							if( empty($check_result) ){
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_explain_contents_book_library_id','解答解説内容の図書室IDが見つかりません');
							}else{
								$exam2_problem['answer_explain_contents_book_library'] = $arrayRecord[$arrRecordIndex];
							}
						}elseif( ($exam2_problem['answer_explain_kind']==9) ){
							// なし（強制文字列ゼロ）
							$exam2_problem['answer_explain_contents_text'] = '';
						}else{
							// テキスト
							$exam2_problem['answer_explain_contents_text'] = $arrayRecord[$arrRecordIndex];
						}
					}
				}
				
				// [8]設問備考（任意）
				$arrRecordIndex = 8;
				if( count($temp_import_result)==0 ){
					if( ($exam2_problem['answer_explain_kind']==9) ){
						// 解答解説種類 ＝ 9 の場合、文字列ゼロ
						$exam2_problem['answer_explain_note'] = '';
					}else{
						// 解答解説種類 ≠ 9 の場合、文字列格納
						$exam2_problem['answer_explain_note'] = $arrayRecord[$arrRecordIndex];
					}
				}
				
				// [9 ～]解答内容（解答種類により対応）
				$arrRecordIndex = 9;
				if( count($temp_import_result)==0 ){
					
					$exam2_problem['answer_contents_no']      = array();  // 選択肢連番（1～）
					$exam2_problem['answer_contents_word']    = array();
					$exam2_problem['answer_contents_correct'] = array();
					$exam2_problem['answer_contents_text']    = '';
					
					if( ($exam2_problem['answer_kind']==1) || ($exam2_problem['answer_kind']==2) ){
						// 解答種類 ＝ 単一形式、複数形式
						
						// インポートする行の項目数が 12 より小さい場合は、解答内容不備エラーとする
						$select_count  = 0;
						$correct_count = 0;
						$contents_error_flag = 0;  // 選択肢正誤があり、選択肢内容がない場合
						
						for($index=$arrRecordIndex + 1; $index<count($arrayRecord); $index = $index + 2){
							// 正誤と内容、両方がある場合処理
							if( (array_key_exists($index, $arrayRecord)) && (array_key_exists($index+1, $arrayRecord)) ){

								if( (trim($arrayRecord[$index])=='') && (trim($arrayRecord[$index+1])=='') ){
									// 正誤、内容ともに空の場合は次へ
									continue;
								}elseif( trim($arrayRecord[$index+1])=='' ){
									// 内容が空の場合、エラー
								//	$temp_import_result['no']      = $counter;
								//	$temp_import_result['value']   = 'NG';
								//	$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_contents_choice','解答内容の選択肢内容がありません');
									$contents_error_flag           = 1;
									break;
								}else{
									// 選択肢数のカウントアップ
									$select_count = $select_count + 1;
									
									// 正誤は空白＝ゼロとみなし、値変更
									if( intval($arrayRecord[$index])>0 ){
										$arrayRecord[$index] = 1;
									}else{
										$arrayRecord[$index] = 0;
									}
									$exam2_problem['answer_contents_no'][$select_count - 1]      = $select_count;
									$exam2_problem['answer_contents_word'][$select_count - 1]    = $arrayRecord[$index+1];
									$exam2_problem['answer_contents_correct'][$select_count - 1] = $arrayRecord[$index];
									
									// 正解数のカウントアップ
									$correct_count = $correct_count + intval($arrayRecord[$index]);
								}
							}
						}
						
						if($contents_error_flag == 0){
							if($select_count == 0){
								// 単一形式・複数形式なのに、選択肢項目がない
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_contents','解答内容の選択肢がありません');
							}elseif($correct_count == 0){
								// 単一形式・複数形式なのに、正解がない
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_contents_correct_answer','解答内容の正解がありません');
							}elseif( ($exam2_problem['answer_kind']==1) && ($correct_count!=1) ) {
								// 単一形式なのに、正解が 1つでない
								$temp_import_result['no']      = $counter;
								$temp_import_result['value']   = 'NG';
								$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_over_answer_contents_correct_answer','単一形式の解答内容の正解は1つです');
							}
						}else{
							// 正誤があって、内容なし
							$temp_import_result['no']      = $counter;
							$temp_import_result['value']   = 'NG';
							$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_no_answer_contents_choice','解答内容の選択肢内容がありません');
						}
					}else{
						// 解答種類 ＝ フリー回答
						if( (array_key_exists($arrRecordIndex, $arrayRecord)) && (!empty($arrayRecord[$arrRecordIndex])) ){
							$exam2_problem['answer_contents_text'] = $arrayRecord[$arrRecordIndex];
						}
					}
				}
				
				// データエラーがない場合、データベースへ登録＋成功情報を返す
				if( count($temp_import_result)==0 ){
				    $exam2_problem['update_flg'] = 0;                                  // 新規登録
				//  $exam2_problem['exam2_problem_name']                                // 設問名
				//  $exam2_problem['problem_kind']                                     // 設問種類
				//  $exam2_problem['problem_contents_text']                            // 設問内容 - テキスト
				//  $exam2_problem['problem_contents_video']                           // 設問内容 - ビデオ
				//  $exam2_problem['problem_contents_book_library']                    // 設問内容 - 図書室
				//	$exam2_problem['problem_note']                                     // 設問備考 
				//	$exam2_problem['answer_kind']                                      // 解答種類 
				//	$exam2_problem['answer_contents_no']                               // 解答内容 - 選択肢連番（1～）
				//	$exam2_problem['answer_contents_word']                             // 解答内容 - 選択肢内容
				//	$exam2_problem['answer_contents_correct']                          // 解答内容 - 選択肢正誤
				//	$exam2_problem['answer_point']                                     // 解答配点
				//  $exam2_problem['answer_explain_kind']                              // 解答解説種類
				//  $exam2_problem['answer_explain_contents_text']                     // 解答解説内容 - テキスト
				//  $exam2_problem['answer_explain_contents_video']                    // 解答解説内容 - ビデオ
				//  $exam2_problem['answer_explain_contents_book_library']             // 解答解説内容 - 図書室
				//	$exam2_problem['answer_explain_note']                              // 解答解説備考 
					$exam2_problem['school_id']             = $school_id;              // 学校ID
					$exam2_problem['teacher_id']            = $teacher_id;             // 講師ID
					$exam2_problem['exam2_problem_lectures'] = $exam2_problem_lectures;  // 所属講座
					$exam2_problem['exam2_problem_groups']   = $exam2_problem_groups;    // 設問グループ（任意）
					
					//更新用引数設定
					$update_param = array(
									'data'   => $exam2_problem,
								);
					// 設問テーブル更新
					$exam2_problem_result = $this->model_exam2_problem->update_exam2_problem($update_param);
					
					// 設問講座テーブル更新
					$data_param = array(
									'exam2_problem_id'  => $exam2_problem_result['lastInsertId'],
									'data'             => $update_param['data'],
								);
					$this->model_exam2_problem->update_exam2_problem_lectures($data_param);
					// 設問グループ関係テーブル新規登録
					$this->model_exam2_problem_group->import_relations_exam2_problem_group($data_param);

					// 成功情報の記載
					$exam2_problem_id = $exam2_problem_result['lastInsertId'];
					
					$message = $this->lang->line_or_def('msg_exam2_problem_import_success' , '設問ID:{exam2_problem_id} で登録されました');
					$message = str_replace("{exam2_problem_id}" , $exam2_problem_id , $message);
					
					$temp_import_result['no']      = $counter;
					$temp_import_result['value']   = 'OK';
					$temp_import_result['message'] = $message;
				}else{
					// １行目でエラーがあった場合、項目名を記載しているヘッダとみなしてメッセージ表示
					if($counter==1){
						$temp_import_result['no']      = $counter;
						$temp_import_result['value']   = '--';
						$temp_import_result['message'] = $this->lang->line_or_def('error_exam2_problem_import_header_line','ヘッダー行');
					}
				}
				array_push($import_result, $temp_import_result);
			
			}
			fclose($fp);
		
		}
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($import_result));
	}

	//----------------------------------------------
	// ファイルから1行取得＋カンマ区切からの配列変換
	// 無限ループ対策を追加
	//----------------------------------------------
	function fgetcsv (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$d_count = 0;
		$eof = false;
		while ($eof != true) {
			$d_count = $d_count + 1;
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
			if($d_count > 1000) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
		
		// csv・tsv の場合、末尾が文字列ゼロの場合、カラム数削減が起こるため、末尾に特定文字を入れて配列数を維持
		$_csv_line .= $d."[EOF]";
		
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}
		mb_convert_variables("UTF-8", "SJIS-win", $_csv_data);
		return empty($_line) ? false : $_csv_data;
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
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					);

		//講師ドロップダウン用データ取得（edit）
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		// 講座チェックボックス用データ取得（edit）
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);
		
		// 設問グループチェックボックス用データ取得（edit）
		$this->load->model('model_exam2_problem_group');
		$param['view_data']['exam2_problem_groups'] = $this->model_exam2_problem_group->get_exam2_problem_checkbox_list($drop_param);
		
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


	/**
	 * エクスポート
	 */
	function export(){
		$this->load->helper('json');
		
		$exam2_problem_lectures_ex = $this->input->post('exam2_problem_lectures_ex');
		$exam2_problem_groups_ex = $this->input->post('exam2_problem_groups_ex');
		
		$this->load->model('model_exam2_problem');
		$export_data = $this->model_exam2_problem->get_exam2_problem_export_data($exam2_problem_lectures_ex, $exam2_problem_groups_ex);
		
		$fileName = 'exam2_problem_'.date('YmdHis').'.csv';
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$fileName");
		
		$fp = fopen('php://output', 'w');
		
		// ヘッダ
		$_headClum = array(
			'設問名',
			'設問種類',
			'設問内容',
			'設問備考',
			'解答種類',
			'解答配点',
			'解答解説種類',
			'解答解説内容',
			'解答解説備考',
			'解答内容-フリー回答',
			'解答内容-選択肢１正誤',
			'解答内容-選択肢１内容',
			'解答内容-選択肢２正誤',
			'解答内容-選択肢２内容',
			'解答内容-選択肢３正誤',
			'解答内容-選択肢３内容',
			'解答内容-選択肢４正誤',
			'解答内容-選択肢４内容',
			'解答内容-選択肢５正誤',
			'解答内容-選択肢５内容',
		);
		mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $_headClum);
		fputcsv($fp, $_headClum);
		
		// データ
		foreach($export_data as $val){
			$data = array();
			$data[] = $val['exam2_problem_name']; // 設問名
			$data[] = $val['problem_kind']; // 設問種類
			$data[] = $val['problem_contents']; // 設問内容
			$data[] = $val['problem_note']; // 設問備考
			$data[] = $val['answer_kind']; // 解答種類
			$data[] = $val['answer_point']; // 解答配点
			$data[] = $val['answer_explain_kind']; // 解答解説種類
			$data[] = $val['answer_explain_contents']; // 解答解説内容
			$data[] = $val['answer_explain_note']; // 解答解説備考
			if($val['answer_contents']){
				$arr_answer_contents = json_decode($val['answer_contents']);
				
				if($arr_answer_contents->answer_kind==1 || $arr_answer_contents->answer_kind==2){
					$data[] = ''; // 解答内容-フリー回答
					foreach($arr_answer_contents->answer_contents as $answer){
						$data[] = $answer->correct; // 解答内容-選択肢n正誤
						$data[] = $answer->word; // 解答内容-選択肢n内容
					}
				} elseif($arr_answer_contents->answer_kind==3){
					$data[] = $arr_answer_contents->answer_contents[0]->word; // 解答内容-フリー回答
				}
			}
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $data);
			fputcsv($fp, $data);
		}
		
		fclose($fp);
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
