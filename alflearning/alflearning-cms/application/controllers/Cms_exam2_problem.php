<?php
#[AllowDynamicProperties]
class Cms_exam2_problem extends CI_Controller {
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

		$this->form_validation->set_rules('s_cource'              , $this->lang->line_or_def('common_course_name','講座名')               , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'           , $this->lang->line_or_def('common_freeword','フリーワード')            , 'trim|xss_clean');
		$this->form_validation->set_rules('s_exam2_problem_group' , $this->lang->line_or_def('common_exam2_problem_group','設問グループ') , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_exam2_problem');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('exam2_problem_search_cond') ?: array(
				's_cource' => '',
				's_free_word' => '',
				's_exam2_problem_group' => '',
			);
		} else {
			//データ取得
			$data['s_cource']              = ($this->input->post('s_cource') ?? '');
			$data['s_free_word']           = ($this->input->post('s_free_word') ?? '');
			$data['s_exam2_problem_group'] = ($this->input->post('s_exam2_problem_group') ?? '');
			$this->session->set_userdata('exam2_problem_search_cond', $data);
		}

		$exam2_problem_list = $this->model_exam2_problem->get_exam2_problem_list(array(
			'school_id'    => $this->libauth->get_school_id(),
			'bar_association_id' => $this->libauth->get_bar_association_id(),
			'offset'       => $offset,
			'rowcount'     => $per_page,
			's_cource'             => $data['s_cource'],              //search
			's_free_word'          => $data['s_free_word'],           //search
			's_exam2_problem_group' => $data['s_exam2_problem_group'],  //search
		));
		$data['exam2_problem_list'] = $exam2_problem_list['items'];
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_exam2_problem/index';
		$config['per_page']   = $per_page;
		$config['total_rows'] = $exam2_problem_list['cnt'];
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
						'view_name'   => 'cms_exam2_problem/index',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	//----------------------------------------------
	function newdata($exam2_problem_id = 0){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');

		//初期表示設定
		$data['exam2_problem']['update_flg']             = 0;
		$data['exam2_problem']['exam2_problem_id']        = 0;
		$data['exam2_problem']['exam2_problem_name']      = '';
		
		$data['exam2_problem']['problem_kind']                   = 1;   // 設問種類 1:テキスト、2:動画、3:図書室
	  //$data['exam2_problem']['problem_contents']               = '';  // 設問内容
		$data['exam2_problem']['problem_contents_text']          = '';  // 設問内容 テキスト
		$data['exam2_problem']['problem_contents_book_library']  = -1;  // 設問内容 図書室ID
		$data['exam2_problem']['problem_contents_video']         = -1;  // 設問内容 ビデオID
		$data['exam2_problem']['problem_note']                   = '';  // 設問備考
		
		$data['exam2_problem']['answer_kind']             = 1;             // 解答種類 1:単一形式、2:複数形式、3:フリー回答
	  //$data['exam2_problem']['answer_contents']         = array();       // 解答内容  JSON型
		$data['exam2_problem']['answer_contents_no']      = array(1,2);    // 解答内容  選択肢順番（1,2,3,…）
		$data['exam2_problem']['answer_contents_word']    = array('','');  // 解答内容  選択肢文言
		$data['exam2_problem']['answer_contents_correct'] = array( 0,0);   // 解答内容  正誤フラグ（1:正解、0:不正解）
		$data['exam2_problem']['answer_contents_text']    = '';            // 解答内容  選択肢フリー回答
		
		$data['exam2_problem']['answer_point']                          = 0;   // 解答配点
		$data['exam2_problem']['answer_explain_kind']                   = 9;   // 解答解説種類 1:テキスト、2:動画、3:図書室、9:なし
		$data['exam2_problem']['answer_explain_contents_text']          = '';  // 解答解説内容 - テキスト
		$data['exam2_problem']['answer_explain_contents_video']         = -1;  // 解答解説内容 - ビデオ
		$data['exam2_problem']['answer_explain_contents_book_library']  = -1;  // 解答解説内容 - 図書室
		$data['exam2_problem']['answer_explain_note']                   = '';  // 解答解説備考

		$data['exam2_problem']['teacher_id']             = $this->libauth->get_teacher_id();
		$data['exam2_problem']['teacher_name']           = '';
		$data['exam2_problem']['exam2_problem_lectures']  = array();  // 所属講座群

		// 設問IDがある場合、情報取得
		if($exam2_problem_id > 0){
			$copy_data = $this->detail($exam2_problem_id, 1);
			
			if(!empty($copy_data)){
				$data['exam2_problem']['update_flg']             = 0;
				$data['exam2_problem']['exam2_problem_id']        = 0;
				$data['exam2_problem']['exam2_problem_name']      = $copy_data['exam2_problem']['exam2_problem_name'];
				
				$data['exam2_problem']['problem_kind']                   = $copy_data['exam2_problem']['problem_kind'];
				$data['exam2_problem']['problem_contents_text']          = $copy_data['exam2_problem']['problem_contents_text'];
				$data['exam2_problem']['problem_contents_book_library']  = $copy_data['exam2_problem']['problem_contents_book_library'];
				$data['exam2_problem']['problem_contents_video']         = $copy_data['exam2_problem']['problem_contents_video'];
				$data['exam2_problem']['problem_note']                   = $copy_data['exam2_problem']['problem_note'];
				
				$data['exam2_problem']['answer_kind']             = $copy_data['exam2_problem']['answer_kind'];
				$data['exam2_problem']['answer_contents_no']      = $copy_data['exam2_problem']['answer_contents_no'];
				$data['exam2_problem']['answer_contents_word']    = $copy_data['exam2_problem']['answer_contents_word'];
				$data['exam2_problem']['answer_contents_correct'] = $copy_data['exam2_problem']['answer_contents_correct'];
				$data['exam2_problem']['answer_contents_text']    = $copy_data['exam2_problem']['answer_contents_text'];

				$data['exam2_problem']['answer_point']                          = $copy_data['exam2_problem']['answer_point'];
				$data['exam2_problem']['answer_explain_kind']                   = $copy_data['exam2_problem']['answer_explain_kind'];
				$data['exam2_problem']['answer_explain_contents_text']          = $copy_data['exam2_problem']['answer_explain_contents_text'];
				$data['exam2_problem']['answer_explain_contents_video']         = $copy_data['exam2_problem']['answer_explain_contents_video'];
				$data['exam2_problem']['answer_explain_contents_book_library']  = $copy_data['exam2_problem']['answer_explain_contents_book_library'];
				$data['exam2_problem']['answer_explain_note']                   = $copy_data['exam2_problem']['answer_explain_note'];

				$data['exam2_problem']['teacher_id']              = $copy_data['exam2_problem']['teacher_id'];
				$data['exam2_problem']['teacher_name']            = '';
				$data['exam2_problem']['exam2_problem_lectures']   = $copy_data['exam2_problem']['exam2_problem_lectures'];
			}
		}

		// mitemo対応（管理講師を裏側で持つ）
		if( getenv('URL_SERVICE')=='mitemo' ){
			$this->load->model('model_teacher');
			$data['exam2_problem']['teacher_id'] = $this->model_teacher->get_teacher_representative_school($this->libauth->get_school_id());
		}

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_exam2_problem/edit',
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
			redirect('/cms_exam2_problem/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		//課題管理の権限有無確認（新規作成の修正の場合は、権限チェックなしとする）
		$auth_exam2_problem = 1;
		if( $edit_form_data['update_flg']!=0){
			$auth_exam2_problem = $this->_get_auth_exam2_problem($edit_form_data['exam2_problem_id']);
		}

		// 権限を持たない場合、エラーを返す
		if($auth_exam2_problem == 0){
			//戻り先設定
			$data['returnurl']       = site_url('cms_exam2_problem');  //.'/detail/'.$edit_form_data['exam2_id'].'/';
			$data['error_message']   = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			$data['select_callview'] = 'exam2_problem';
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'wide_use_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}

		elseif(isset($edit_form_data['exam2_problem_id']) &&  $edit_form_data['exam2_problem_id'] <> ''){
			//画面表示用データ設定
			$data['exam2_problem'] = $edit_form_data;

			//モデル読み込み
		//	$this->load->model('model_exam2_problem');

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_exam2_problem/edit',
							'submenu_idx' => ($data['exam2_problem']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('exam2_problem');
			
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
	//  →詳細画面「複製して新規登録」のためのフラグ追加
	//      フラグありの場合、返却値を変更
	//----------------------------------------------
	function detail($exam2_problem_id, $copy_newdata_flag = 0){

		$this->load->helper('json');
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_exam2_problem');
		
		//データ取得用引数設定
		$data_param = array(
						'exam2_problem_id' => $exam2_problem_id,
					);
		//データ取得
		$db_data = $this->model_exam2_problem->get_exam2_problem($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			// 設問内容の変換
			$problem_contents_text         = '';
			$problem_contents_video        = '';
			$problem_contents_book_library = '';
			if($db_data['problem_kind'] == 1) $problem_contents_text         = $db_data['problem_contents'];
			if($db_data['problem_kind'] == 2) $problem_contents_video        = $db_data['problem_contents'];
			if($db_data['problem_kind'] == 3) $problem_contents_book_library = $db_data['problem_contents'];
			
			// 解答内容のJSON型変換
			$array_answer_contents   = obj2arr(json_decode( $db_data['answer_contents'] ));

			// 解答解説内容の変換
			$answer_explain_contents_text         = '';
			$answer_explain_contents_video        = '';
			$answer_explain_contents_book_library = '';
			if($db_data['answer_explain_kind'] == 1) $answer_explain_contents_text         = $db_data['answer_explain_contents'];
			if($db_data['answer_explain_kind'] == 2) $answer_explain_contents_video        = $db_data['answer_explain_contents'];
			if($db_data['answer_explain_kind'] == 3) $answer_explain_contents_book_library = $db_data['answer_explain_contents'];
			if($db_data['answer_explain_kind'] == 9) $answer_explain_contents_text         = $db_data['answer_explain_contents'];
			
			// 解答内容の変換
			$answer_contents_no      = array();
			$answer_contents_word    = array();
			$answer_contents_correct = array();
			$answer_contents_text    = '';
			
			if( ($db_data['answer_kind']==1) || ($db_data['answer_kind']==2) ){
				
				for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
					$line_contents = (array)$array_answer_contents['answer_contents'][$i];
					
					$answer_contents_no[$i]      = $line_contents['no'];
					$answer_contents_word[$i]    = $line_contents['word'];
					$answer_contents_correct[$i] = $line_contents['correct'];
				}
			}else{
				$line_contents = (array)$array_answer_contents['answer_contents'][0];
				
				$answer_contents_no      = array(1 , 2);
				$answer_contents_word    = array('', '');
				$answer_contents_correct = array(0 , 0);
				$answer_contents_text    = $line_contents['word'];
			}
			
			//表示用データ設定
			$data['exam2_problem']['update_flg']                     = 1;
			$data['exam2_problem']['exam2_problem_id']                = $db_data['exam2_problem_id'];
			$data['exam2_problem']['exam2_problem_name']              = $db_data['exam2_problem_name'];
			$data['exam2_problem']['teacher_id']                     = $db_data['teacher_id'];
			$data['exam2_problem']['teacher_name']                   = $db_data['teacher_name'];
		//	$data['exam2_problem']['exam2_problem_lectures']          = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
			$data['exam2_problem']['problem_kind']                   = $db_data['problem_kind'];
			$data['exam2_problem']['problem_contents_text']          = $problem_contents_text;
			$data['exam2_problem']['problem_contents_video']         = $problem_contents_video;
			$data['exam2_problem']['problem_contents_book_library']  = $problem_contents_book_library;
			$data['exam2_problem']['problem_note']                   = $db_data['problem_note'];
			$data['exam2_problem']['answer_kind']                    = $db_data['answer_kind'];
			$data['exam2_problem']['answer_contents_no']             = $answer_contents_no;
			$data['exam2_problem']['answer_contents_word']           = $answer_contents_word;
			$data['exam2_problem']['answer_contents_correct']        = $answer_contents_correct;
			$data['exam2_problem']['answer_contents_text']           = $answer_contents_text;
			$data['exam2_problem']['answer_point']                   = $db_data['answer_point'];
			
			$data['exam2_problem']['answer_explain_kind']                   = $db_data['answer_explain_kind'];
			$data['exam2_problem']['answer_explain_contents_text']          = $answer_explain_contents_text;
			$data['exam2_problem']['answer_explain_contents_video']         = $answer_explain_contents_video;
			$data['exam2_problem']['answer_explain_contents_book_library']  = $answer_explain_contents_book_library;
			$data['exam2_problem']['answer_explain_note']                   = $db_data['answer_explain_note'];
			
			$data['exam2_problem']['school_id']                      = $db_data['school_id'];
			
			//所属講座取得
			$data['exam2']['exam2_problem_lectures']  = array();
			$lectures = $this->model_exam2_problem->get_exam2_problem_lectures($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['exam2_problem']['exam2_problem_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['exam2_problem']));
			
			//所属講座名設定
			$data['exam2_problem']['exam2_problem_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['exam2_problem']['exam2_problem_lectures']){
				foreach($data['exam2_problem']['exam2_problem_lectures'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['exam2_problem']['exam2_problem_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				//連想キーと要素との関係を維持しつつ配列をソート
				asort($data['exam2_problem']['exam2_problem_lectures_name']);
			}
			
			// 設問IDより所属する設問グループIDを取得
			$this->load->model('model_exam2_problem_group');
			$exam2_problem_group_id = array();
			$exam2_problem_groups = $this->model_exam2_problem_group->get_exam2_problem_group_id_list(array(
				"school_id"               => $this->libauth->get_school_id(),
				"select_exam2_problem_id"  => $db_data['exam2_problem_id'],
				"cource_flag"             => 1,
			));
			if($exam2_problem_groups){
				foreach($exam2_problem_groups as $index => $value){
					$exam2_problem_group_id[] = $value;
				}
			}
			$data['exam2_problem_group_id'] = $exam2_problem_group_id;
			
			// 設問内容 - ビデオ名設定
			$data['exam2_problem']['problem_contents_video_name'] = '';
			if($data['exam2_problem']['problem_kind']==2){
				$this->load->model('model_video');
				$temp_video = $this->model_video->get_material(array(
					'video_id' => $data['exam2_problem']['problem_contents_video'],
				));
				if($temp_video){
					$data['exam2_problem']['problem_contents_video_name'] = '[No'.$temp_video['video_id'].'] '.$temp_video['video_logic_name'];
				}
			}
			
			// 設問内容 - 図書室名設定
			$data['exam2_problem']['problem_contents_book_library_name'] = '';
			if($data['exam2_problem']['problem_kind']==3){
				$this->load->model('model_book_library');
				$temp_book_library = $this->model_book_library->get_data(array(
					"school_id"        => $this->libauth->get_school_id(),
					'book_library_id'  => $data['exam2_problem']['problem_contents_book_library'],
				));
				if($temp_book_library){
					$data['exam2_problem']['problem_contents_book_library_name'] = '[No'.$temp_book_library['book_library_id'].'] '.$temp_book_library['book_library_logic_name'];
				}
			}
			
			// 解答解説内容 - ビデオ名設定
			$data['exam2_problem']['answer_explain_contents_video_name'] = '';
			if($data['exam2_problem']['answer_explain_kind']==2){
				$this->load->model('model_video');
				$temp_video = $this->model_video->get_material(array(
					'video_id' => $data['exam2_problem']['answer_explain_contents_video'],
				));
				if($temp_video){
					$data['exam2_problem']['answer_explain_contents_video_name'] = '[No'.$temp_video['video_id'].'] '.$temp_video['video_logic_name'];
				}
			}
			
			// 解答解説内容 - 図書室名設定
			$data['exam2_problem']['answer_explain_contents_book_library_name'] = '';
			if($data['exam2_problem']['answer_explain_kind']==3){
				$this->load->model('model_book_library');
				$temp_book_library = $this->model_book_library->get_data(array(
					"school_id"        => $this->libauth->get_school_id(),
					'book_library_id'  => $data['exam2_problem']['answer_explain_contents_book_library'],
				));
				if($temp_book_library){
					$data['exam2_problem']['answer_explain_contents_book_library_name'] = '[No'.$temp_book_library['book_library_id'].'] '.$temp_book_library['book_library_logic_name'];
				}
			}
			
			if($copy_newdata_flag==0){
				
				// 修正・削除ボタン表示フラグ
				//   表示対象：SuperUser・学校管理者・設問を作成した講師
				$auth_exam2_problem = $this->_get_auth_exam2_problem($data['exam2_problem']['exam2_problem_id']);
				$data['exam2_problem_edit_delete_flag'] = $auth_exam2_problem;
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem/confirm',
								'submenu_idx' => 3,
								'view_data'   => $data,
								'exam2_problem_id'     => $db_data['exam2_problem_id'],
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}else{
				return $data;
			}
		}else{
			if($copy_newdata_flag==0){
				//データ無し時
				//一覧に戻る
				//$this->index();
				header("Location:/cms_exam2_problem/");
				exit();
			}else{
				return NULL;
			}
		}
	}

	//----------------------------------------------
	//更新確認フォーム表示
	//----------------------------------------------
	function confirm(){

//print var_dump($this->input->post());
//exit();

		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'                           , $this->lang->line_or_def('common_flg','flg')                              , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_problem_id'                      , $this->lang->line_or_def('common_id','ID')                                , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_problem_name'                    , $this->lang->line_or_def('common_exam2_problem_name','設問名')             , 'trim|xss_clean|required');
		$this->form_validation->set_rules('teacher_id'                           , $this->lang->line_or_def('common_management_teacher','管理講師')          , 'required');
		$this->form_validation->set_rules('exam2_problem_lectures[]'                , $this->lang->line_or_def('common_position_course','所属講座')             , 'callback_check_required_checkbox');
		$this->form_validation->set_rules('problem_kind'                         , $this->lang->line_or_def('common_problem_kind','設問種類')                , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('problem_contents_text'                , $this->lang->line_or_def('common_problem_contents','設問内容')            , 'trim|xss_clean');  // テキスト
		$this->form_validation->set_rules('problem_contents_video'               , $this->lang->line_or_def('common_problem_contents','設問内容')            , 'trim|xss_clean');  // ビデオID
		$this->form_validation->set_rules('problem_contents_book_library'        , $this->lang->line_or_def('common_problem_contents','設問内容')            , 'trim|xss_clean');  // 図書室ID
		$this->form_validation->set_rules('problem_note'                         , $this->lang->line_or_def('common_problem_note','設問備考')                , 'trim|xss_clean');
		$this->form_validation->set_rules('answer_kind'                          , $this->lang->line_or_def('common_answer_kind','解答種類')                 , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('answer_contents_text'                 , $this->lang->line_or_def('common_answer_contents','解答内容')             , 'trim|xss_clean');  // テキスト
		$this->form_validation->set_rules('answer_point'                         , $this->lang->line_or_def('common_exam2_answer_points','解答配点')          , 'trim|xss_clean|is_natural');
		$this->form_validation->set_rules('answer_explain_kind'                  , $this->lang->line_or_def('common_answer_explain_kind','解答解説種類')     , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('answer_explain_contents_text'         , $this->lang->line_or_def('common_answer_explain_contents','解答解説内容') , 'trim|xss_clean'); // テキスト
		$this->form_validation->set_rules('answer_explain_contents_video'        , $this->lang->line_or_def('common_answer_explain_contents','解答解説内容') , 'trim|xss_clean'); // ビデオID
		$this->form_validation->set_rules('answer_explain_contents_book_library' , $this->lang->line_or_def('common_answer_explain_contents','解答解説内容') , 'trim|xss_clean'); // 図書室ID
		$this->form_validation->set_rules('answer_explain_note'                  , $this->lang->line_or_def('common_answer_explain_note','解答解説備考')     , 'trim|xss_clean');
		
		// 配列型の所属講座の値をチェック
		$this->load->helper('string_inspection_helper');

		$check_exam2_problem_lectures = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();

		if(!check_array_data_num(array($check_exam2_problem_lectures))){
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
			// 設問種類・設問内容チェック
			$problem_error_msg = $this->_check_problem(
				array(
					'problem_kind'                   => $this->input->post('problem_kind'),
					'problem_contents_text'          => $this->input->post('problem_contents_text'),
					'problem_contents_video'         => $this->input->post('problem_contents_video'),
					'problem_contents_book_library'  => $this->input->post('problem_contents_book_library'),
				)
			);
			
			// 解答解説種類・解答解説内容チェック
			$answer_explain_error_msg = $this->_check_answer_explain(
				array(
					'answer_explain_kind'                   => $this->input->post('answer_explain_kind'),
					'answer_explain_contents_text'          => $this->input->post('answer_explain_contents_text'),
					'answer_explain_contents_video'         => $this->input->post('answer_explain_contents_video'),
					'answer_explain_contents_book_library'  => $this->input->post('answer_explain_contents_book_library'),
				)
			);

			// 解答種類・解答内容チェック
			// 解答内容（選択肢系）のデータ変換
			
			// 解答（正解・不正解チェックボックスの変換→チェックボックスはチェックなしの値がこない
			$answer_contents_no           = $this->input->post('answer_contents_no')?$this->input->post('answer_contents_no'):array();
			$answer_contents_word         = $this->input->post('answer_contents_word')?$this->input->post('answer_contents_word'):array();
			$temp_answer_contents_correct = $this->input->post('answer_contents_correct')?$this->input->post('answer_contents_correct'):array();
			
			$answer_contents_correct       = array();
			foreach($answer_contents_no as $ino => $value){
				$answer_contents_correct[$ino] = "0";  // チェック無効（不正解）
				if(!empty($temp_answer_contents_correct)){
					if (in_array($value, $temp_answer_contents_correct)) {
						$answer_contents_correct[$ino] = "1";  // チェック有効（正解）
					}
				}
			}
			$answer_error_msg = $this->_answer_problem(
				array(
					'answer_kind'              => $this->input->post('answer_kind'),
					'answer_contents_no'       => $answer_contents_no,
					'answer_contents_word'     => $answer_contents_word,
					'answer_contents_correct'  => $answer_contents_correct,
					'answer_contents_text'     => $this->input->post('answer_contents_text'),
				)
			);
			
			//検証
			//if($this->form_validation->run() == FALSE){
			if($this->form_validation->run() == FALSE || ($problem_error_msg != "") || ($answer_error_msg != "") || ($answer_explain_error_msg != "") ){
				//失敗
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['exam2_problem']['update_flg']                     = $this->input->post('update_flg');
				$data['exam2_problem']['exam2_problem_id']                = $this->input->post('exam2_problem_id');
				$data['exam2_problem']['exam2_problem_name']              = '';
				$data['exam2_problem']['teacher_id']                     = $this->input->post('teacher_id');
				$data['exam2_problem']['exam2_problem_lectures']          = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
				$data['exam2_problem']['problem_kind']                   = $this->input->post('problem_kind');
				$data['exam2_problem']['problem_contents_text']          = $this->input->post('problem_contents_text');
				$data['exam2_problem']['problem_contents_video']         = $this->input->post('problem_contents_video');
				$data['exam2_problem']['problem_contents_book_library']  = $this->input->post('problem_contents_book_library');
				$data['exam2_problem']['problem_note']                   = $this->input->post('problem_note');
				$data['exam2_problem']['answer_kind']                    = $this->input->post('answer_kind');
				$data['exam2_problem']['answer_contents_no']             = $answer_contents_no;
				$data['exam2_problem']['answer_contents_word']           = $answer_contents_word;
				$data['exam2_problem']['answer_contents_correct']        = $answer_contents_correct;
				$data['exam2_problem']['answer_contents_text']           = $this->input->post('answer_contents_text');
				$data['exam2_problem']['answer_point']                   = $this->input->post('answer_point');
				$data['exam2_problem']['answer_explain_kind']                   = $this->input->post('answer_explain_kind');
				$data['exam2_problem']['answer_explain_contents_text']          = $this->input->post('answer_explain_contents_text');
				$data['exam2_problem']['answer_explain_contents_video']         = $this->input->post('answer_explain_contents_video');
				$data['exam2_problem']['answer_explain_contents_book_library']  = $this->input->post('answer_explain_contents_book_library');
				$data['exam2_problem']['answer_explain_note']                   = $this->input->post('answer_explain_note');

				// 設問チェック結果
				$data['problem_error_msg']        = $problem_error_msg;
				$data['answer_error_msg']         = $answer_error_msg;
				$data['answer_explain_error_msg'] = $answer_explain_error_msg;
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem/edit',
								'submenu_idx' => ($data['exam2_problem']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}else{
				// 成功
				$data['btn_kirikae_flg'] = 1;

				$data['exam2_problem']['update_flg']                     = $this->input->post('update_flg');
				$data['exam2_problem']['exam2_problem_id']                = $this->input->post('exam2_problem_id');
				$data['exam2_problem']['exam2_problem_name']              = $this->input->post('exam2_problem_name');
				$data['exam2_problem']['teacher_id']                     = $this->input->post('teacher_id');
				$data['exam2_problem']['exam2_problem_lectures']          = $this->input->post('exam2_problem_lectures')?$this->input->post('exam2_problem_lectures'):array();
				$data['exam2_problem']['problem_kind']                   = $this->input->post('problem_kind');
				$data['exam2_problem']['problem_contents_text']          = $this->input->post('problem_contents_text');
				$data['exam2_problem']['problem_contents_video']         = $this->input->post('problem_contents_video');
				$data['exam2_problem']['problem_contents_book_library']  = $this->input->post('problem_contents_book_library');
				$data['exam2_problem']['problem_note']                   = $this->input->post('problem_note');
				$data['exam2_problem']['answer_kind']                    = $this->input->post('answer_kind');
				$data['exam2_problem']['answer_contents_no']             = $answer_contents_no;
				$data['exam2_problem']['answer_contents_word']           = $answer_contents_word;
				$data['exam2_problem']['answer_contents_correct']        = $answer_contents_correct;
				$data['exam2_problem']['answer_contents_text']           = $this->input->post('answer_contents_text');
				$data['exam2_problem']['answer_point']                   = $this->input->post('answer_point');
				$data['exam2_problem']['answer_explain_kind']                   = $this->input->post('answer_explain_kind');
				$data['exam2_problem']['answer_explain_contents_text']          = $this->input->post('answer_explain_contents_text');
				$data['exam2_problem']['answer_explain_contents_video']         = $this->input->post('answer_explain_contents_video');
				$data['exam2_problem']['answer_explain_contents_book_library']  = $this->input->post('answer_explain_contents_book_library');
				$data['exam2_problem']['answer_explain_note']                   = $this->input->post('answer_explain_note');
				
				$data['exam2_problem']['school_id']                      = $this->libauth->get_school_id();

				//セッションへ検証済みデータを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['exam2_problem']));

				// 管理講師名設定
				$this->load->model('model_teacher');
				$temp_teacher = $this->model_teacher->get_name(array(
					'teacher_id' => $data['exam2_problem']['teacher_id'],
				));
				if($temp_teacher){
					$data['exam2_problem']['teacher_name'] = $temp_teacher;
				}

				// 所属講座名設定
				$data['exam2_problem']['exam2_problem_lectures_name'] = array();
				$this->load->model('model_cource');
				if($data['exam2_problem']['exam2_problem_lectures']){
					foreach($data['exam2_problem']['exam2_problem_lectures'] as $idx => $lecture){
						//データ取得用引数設定
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

				// 設問内容 - ビデオ名設定
				$data['exam2_problem']['problem_contents_video_name'] = '';
				if($data['exam2_problem']['problem_kind']==2){
					$this->load->model('model_video');
					$temp_video = $this->model_video->get_material(array(
						'video_id' => $data['exam2_problem']['problem_contents_video'],
					));
					if($temp_video){
						$data['exam2_problem']['problem_contents_video_name'] = $temp_video['video_logic_name'];
					}
				}

				// 設問内容 - 図書室名設定
				$data['exam2_problem']['problem_contents_book_library_name'] = '';
				if($data['exam2_problem']['problem_kind']==3){
					$this->load->model('model_book_library');
					$temp_book_library = $this->model_book_library->get_data(array(
						"school_id"        => $this->libauth->get_school_id(),
						'book_library_id'  => $data['exam2_problem']['problem_contents_book_library'],
					));
					if($temp_book_library){
						$data['exam2_problem']['problem_contents_book_library_name'] = $temp_book_library['book_library_logic_name'];
					}
				}
				
				// 解答解説内容 - ビデオ名設定
				$data['exam2_problem']['answer_explain_contents_video_name'] = '';
				if($data['exam2_problem']['answer_explain_kind']==2){
					$this->load->model('model_video');
					$temp_video = $this->model_video->get_material(array(
						'video_id' => $data['exam2_problem']['answer_explain_contents_video'],
					));
					if($temp_video){
						$data['exam2_problem']['answer_explain_contents_video_name'] = '[No'.$temp_video['video_id'].'] '.$temp_video['video_logic_name'];
					}
				}
				
				// 解答解説内容 - 図書室名設定
				$data['exam2_problem']['answer_explain_contents_book_library_name'] = '';
				if($data['exam2_problem']['answer_explain_kind']==3){
					$this->load->model('model_book_library');
					$temp_book_library = $this->model_book_library->get_data(array(
						"school_id"        => $this->libauth->get_school_id(),
						'book_library_id'  => $data['exam2_problem']['answer_explain_contents_book_library'],
					));
					if($temp_book_library){
						$data['exam2_problem']['answer_explain_contents_book_library_name'] = '[No'.$temp_book_library['book_library_id'].'] '.$temp_book_library['book_library_logic_name'];
					}
				}
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2_problem/confirm',
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

		if(isset($edit_form_data['exam2_problem_id']) && $edit_form_data['exam2_problem_id'] != ""){
			//モデル読み込み
			$this->load->model('model_exam2_problem');
			
			//更新用引数設定
			$update_param = array(
							'data'   => $edit_form_data,
						);

			// 試験テーブル更新
			$exam2_problem_result = $this->model_exam2_problem->update_exam2_problem($update_param);
			
			// 試験講座テーブル更新
			$data_param = array(
							'exam2_problem_id'  => $exam2_problem_result['lastInsertId'],
							'data'             => $update_param['data'],
						);
			$this->model_exam2_problem->update_exam2_problem_lectures($data_param);
			
		//	// 試験受講者・試験受講者グループ更新
		//	$this->model_exam2->update_exam2_student_group($data_param);
			
			//ビュー設定引数設定
			//$data = array();
			$data['exam2_problem_id'] = $exam2_problem_result['lastInsertId'];
			$disp_param = array(
							'view_name'   => 'cms_exam2_problem/commit',
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
			$data['returnurl'] = site_url('exam2_problem');
			
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
	function delete_item($exam2_problem_id){
		// load language
		$this->lang->load('error');

		//モデル読み込み
		$this->load->model('model_exam2_problem');

		//試験管理の権限有無確認（新規作成の修正の場合は、権限チェックなしとする）
		$auth_exam2_problem = 1;
		$auth_exam2_problem = $this->_get_auth_exam2_problem($exam2_problem_id);

		// 削除権限がない場合、エラー画面を表示
		if($auth_exam2_problem == 0){
			//戻り先設定
			$data['returnurl']       = site_url('cms_exam2');
			$data['error_message']   = $this->lang->line_or_def('error_del_auth','削除権限がありません<br />ログインし直してください');
			$data['select_callview'] = 'exam2';
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'wide_use_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ削除
			$data = $this->model_exam2_problem->delete_item(array(
				'exam2_problem_id' => $exam2_problem_id,
			));
			
			//完了フォーム表示
			$this->_display_view(array(
				'view_name'   => 'cms_exam2_problem/commit',
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
		//講座ドロップダウン用データ取得（index）
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);
		
		//設問グループ ドロップダウン用データ取得（index）
		$param['view_data']['exam2_problem_groups_dropdown']  = $this->_get_exam2_problem_group_list_array($drop_param);



		//講師ドロップダウン用データ取得（edit）
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		// 講座チェックボックス用データ取得（edit）
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);
		
		// 設問種類ドロップダウン用データ取得（edit）
		$param['view_data']['problem_kind_list']  = $this->_get_select_problem_kind();

		// 解答種類ドロップダウン用データ取得（edit）
		$param['view_data']['answer_kind_list']  = $this->_get_select_answer_kind();

		// 解答解説種類ドロップダウン用データ取得（edit）
		$param['view_data']['answer_explain_kind_list']  = $this->_get_select_answer_explain_kind();

		// 設問の講座所属に属する図書室（図書室ID・図書室論理名）を取得
		if( isset($param['view_data']['exam2_problem']) ){
			$temp_cource_id = implode(",", $param['view_data']['exam2_problem']['exam2_problem_lectures']);
			
			// 図書室
			$exclusive_param = array(
						"school_id"  => $this->libauth->get_school_id(),
						"cource_id"  => $temp_cource_id,
						);
			$book_library_exclusive = $this->_get_course_book_library_list_array($exclusive_param);
			$param['view_data']['problem_contents_book_library'] = $book_library_exclusive['problem_contents_book_library'];
			
			
			
			// ビデオ
			$video_exclusive = $this->_get_course_video_list_array($exclusive_param);
			$param['view_data']['problem_contents_video'] = $video_exclusive['problem_contents_video'];
		}

		// 状態（公開フラグ）ドロップダウン用データ取得（edit）
	//	$param['view_data']['public_flag_list']  = $this->_get_select_public_flag();
		
		// 再提出フラグ、ドロップダウン用データ取得（edit）
	//	$param['view_data']['resubmit_flag_list']  = $this->_get_select_resubmit_flag();
		
		//受講者グループチェックボックス用データ取得
	//	$this->load->model('model_student_group');
	//	$param['view_data']['student_group_list'] = $this->model_student_group->get_student_group_list($drop_param);
		
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
							'bar_association_id' => $this->libauth->get_bar_association_id(),
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
	// 設問種類ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_problem_kind(){
		$this->lang->load('common');
		
		$data['problem_kind'] = array();
		$data['problem_kind'][1] = $this->lang->line_or_def('common_text'         , 'テキスト');
//		$data['problem_kind'][2] = $this->lang->line_or_def('common_video'        , 'ビデオ');
//		$data['problem_kind'][3] = $this->lang->line_or_def('common_book_library' , '図書室');
		
		return $data['problem_kind'];
	}

	//----------------------------------------------
	// 解答種類ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_answer_kind(){
		$this->lang->load('common');
		
		$data['answer_kind'] = array();
		$data['answer_kind'][1] = $this->lang->line_or_def('common_single_forms',     '単一形式');
		$data['answer_kind'][2] = $this->lang->line_or_def('common_plural_forms',     '複数形式');
		$data['answer_kind'][3] = $this->lang->line_or_def('common_free_exam2_answer', 'フリー解答');
		
		return $data['answer_kind'];
	}

	//----------------------------------------------
	// 解答解説種類ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_answer_explain_kind(){
		$this->lang->load('common');
		
		$data['answer_explain_kind'] = array();
		$data['answer_explain_kind'][9] = $this->lang->line_or_def('common_nothing'      , 'なし');
		$data['answer_explain_kind'][1] = $this->lang->line_or_def('common_text'         , 'テキスト');
//		$data['answer_explain_kind'][2] = $this->lang->line_or_def('common_video'        , 'ビデオ');
//		$data['answer_explain_kind'][3] = $this->lang->line_or_def('common_book_library' , '図書室');
		
		return $data['answer_explain_kind'];
	}

	//----------------------------------------------
	// 講座所属図書室一覧ドロップダウン要配列取得
	//----------------------------------------------
	function _get_course_book_library_list_array($param){
		// load language
		$this->lang->load('msg');
		
		//引数設定
		$param = array_merge(
					array(
						"school_id"  =>	0,
						"cource_id"  =>	'',
					),
					$param
				);
		// 値取得
		$this->load->model('model_book_library');
		$book_library_exclusive = $this->model_book_library->get_cource_book_library_exclusive($param);
		
		// 変数設定
		$data['problem_contents_book_library']     = array();
		$data['problem_contents_book_library'][-1] = $this->lang->line_or_def('msg_exclusive_tag_book_library_select','図書室を選択してください');
		
		if( count($book_library_exclusive) != 0 ) {
			foreach($book_library_exclusive as $book_library){
				$data['problem_contents_book_library'][$book_library['book_library_id']]     = '[No'.$book_library['book_library_id'].'] '.$book_library['book_library_logic_name'];
			}
		}
		
		return array(
			'problem_contents_book_library' => $data['problem_contents_book_library'],
		);
	}

	//----------------------------------------------
	// 講座所属ビデオ一覧ドロップダウン要配列取得
	//----------------------------------------------
	function _get_course_video_list_array($param){
		// load language
		$this->lang->load('msg');
		
		//引数設定
		$param = array_merge(
					array(
						"school_id"  =>	0,
						"cource_id"  =>	'',
					),
					$param
				);
		// 値取得
		$this->load->model('model_video');
		$video_exclusive = $this->model_video->get_cource_video_exclusive($param);
		
		// 変数設定
		$data['problem_contents_video']     = array();
		$data['problem_contents_video'][-1] = $this->lang->line_or_def('msg_exam2_problem_video_select','ビデオを選択してください');
		
		if( count($video_exclusive) != 0 ) {
			foreach($video_exclusive as $video){
				$data['problem_contents_video'][$video['video_id']]     = '[No'.$video['video_id'].'] '.$video['video_logic_name'];
				
			//	$temp = $this->model_video->get_video_thumbnail_url($video['video_id']);
			}
		}
		
		return array(
			'problem_contents_video' => $data['problem_contents_video'],
		);
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
	// 再提出フラグ、ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_resubmit_flag(){
		$this->lang->load('common');
		
		$data['resubmit_flag'] = array();
		$data['resubmit_flag'][0] = $this->lang->line_or_def('common_impossible', '不可');
		$data['resubmit_flag'][1] = $this->lang->line_or_def('common_possible',   '可');
		
		return $data['resubmit_flag'];
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
			$data['cources'][0] = '';
			foreach ( $data['cource_list'] as $cource ) {
				$data['cources'][$cource['cource_id']] = $cource['cource_name'];
			}
		}
		
		return $data['cources'];
	}

	//----------------------------------------------
	//設問グループ一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_exam2_problem_group_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_exam2_problem_group');
		
		//一覧ドロップダウン生成
		$data['exam2_problem_group_list'] = $this->model_exam2_problem_group->get_exam2_problem_dropdown_list($param);
		$data['exam2_problem_groups'] = array();
		if( count($data['exam2_problem_group_list']) != 0 ) {
			$data['exam2_problem_groups'][0] = '';
			foreach ( $data['exam2_problem_group_list'] as $exam2_problem_group ) {
				$data['exam2_problem_groups'][$exam2_problem_group['exam2_problem_group_id']] = $exam2_problem_group['exam2_problem_group_name'];
			}
		}
		
		return $data['exam2_problem_groups'];
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
	//設問管理の権限有無確認
	//  ログインユーザに該当課題の修正・削除権限があるかを確認
	//  Super User：修正・削除ＯＫ
	//  学校管理者：同学校内のみ、修正・削除ＯＫ
	//  一般講師　：自分が管理講師の試験 + 設問管理の権限あり の場合、修正・削除ＯＫ
	//----------------------------------------------
	function _get_auth_exam2_problem($exam2_problem_id = 0){
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
			
			// 設問の再取得
			$this->load->model('model_exam2_problem');
			$exam2_data = $this->model_exam2_problem->get_exam2_problem(array(
							'exam2_problem_id' => $exam2_problem_id,
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
	// [Ajax用]講座所属の図書室を取得
	//----------------------------------------------
	function get_cource_book_library(){
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					"cource_id"  => $this->input->post('cource_id'),
					);

		// 講座に属する図書室ID・図書室論理名を取得
		$this->load->model('model_book_library');
		$book_librarys = $this->model_book_library->get_cource_book_library_exclusive($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($book_librarys));
	}

	//----------------------------------------------
	// [Ajax用]講座所属ビデオを取得
	//----------------------------------------------
	function get_cource_video(){
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					"cource_id"  => $this->input->post('cource_id'),
					);

		// 講座に属するビデオID・ビデオ論理名を取得
		$this->load->model('model_video');
		$videos = $this->model_video->get_cource_video_exclusive($drop_param);

		// サムネイル確認
	//	foreach($videos as $video){
	//		$temp = $this->model_video->get_video_thumbnail_url($video['video_id']);
	//	}

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($videos));
	}




	//----------------------------------------------
	// 設問内容の内容チェック
	//----------------------------------------------
	function _check_problem($param){
		$this->lang->load('error');
		
		//引数設定
		$problem_error_msg = '';
		$param = array_merge(
						array(
							'problem_kind'                   => 0,
							'problem_contents_text'          => '',
							'problem_contents_video'         => 0,
							'problem_contents_book_library'  => 0,
						),
						$param
					);
		if( ($param['problem_kind']==1) && (empty($param['problem_contents_text'])) ){
			if($problem_error_msg!='') $problem_error_msg .= '<br/>';
			$problem_error_msg .= $this->lang->line_or_def('error_no_input_problem_contents','設問内容の入力がありません。');
		}elseif( ($param['problem_kind']==2) && ($param['problem_contents_video']<0) ){
			if($problem_error_msg!='') $problem_error_msg .= '<br/>';
			$problem_error_msg .= $this->lang->line_or_def('error_not_choice_video_problem_contents','設問内容のビデオが選択されていません。');
		}elseif( ($param['problem_kind']==3) && ($param['problem_contents_book_library']<0) ){
			if($problem_error_msg!='') $problem_error_msg .= '<br/>';
			$problem_error_msg .= $this->lang->line_or_def('error_not_choice_book_library_problem_contents','設問内容の図書室が選択されていません。');
		}
		
		return $problem_error_msg;
	}

	//----------------------------------------------
	// 解答解説内容の内容チェック
	//----------------------------------------------
	function _check_answer_explain($param){
		$this->lang->load('error');
		
		//引数設定
		$answer_explain_error_msg = '';
		$param = array_merge(
						array(
							'answer_explain_kind'                   => 0,
							'answer_explain_contents_text'          => '',
							'answer_explain_contents_video'         => 0,
							'answer_explain_contents_book_library'  => 0,
						),
						$param
					);
		if( ($param['answer_explain_kind']==1) && (empty($param['answer_explain_contents_text'])) ){
			if($answer_explain_error_msg!='') $answer_explain_error_msg .= '<br/>';
			$answer_explain_error_msg .= $this->lang->line_or_def('error_no_input_answer_explain','解答解説内容の入力がありません');
		}elseif( ($param['answer_explain_kind']==2) && ($param['answer_explain_contents_video']<0) ){
			if($answer_explain_error_msg!='') $answer_explain_error_msg .= '<br/>';
			$answer_explain_error_msg .= $this->lang->line_or_def('error_not_choice_video_answer_explain','解答解説内容のビデオが選択されていません');
		}elseif( ($param['answer_explain_kind']==3) && ($param['answer_explain_contents_book_library']<0) ){
			if($answer_explain_error_msg!='') $answer_explain_error_msg .= '<br/>';
			$answer_explain_error_msg .= $this->lang->line_or_def('error_not_choice_book_library_answer_explain','解答解説内容の図書室が選択されていません');
		}
		
		return $answer_explain_error_msg;
	}

	//----------------------------------------------
	// 解答内容の内容チェック
	//----------------------------------------------
	function _answer_problem($param){
		$this->lang->load('error');
		
		//引数設定
		$answer_error_msg = '';
		$param = array_merge(
						array(
							'answer_kind'              => 0,
							'answer_contents_no'       => array(),
							'answer_contents_word'     => array(),
							'answer_contents_correct'  => array(),
							'answer_contents_text'     => '',
						),
						$param
					);
		if( ($param['answer_kind'] == 1) || ($param['answer_kind'] == 2) ){
			// 単一形式
			// 複数形式
			
			foreach($param['answer_contents_word'] as $idx => $answer_contents_word){
				if( empty($answer_contents_word) ){
					if($answer_error_msg!='') $answer_error_msg .= '<br/>';
					$answer_error_msg .= (string)($idx + 1).$this->lang->line_or_def('error_answer_contents_no_data', '行目の解答内容の入力がありません。');
				}
			}
			
			$correct = array_sum($param['answer_contents_correct']);  // 正解の数をカウント
			
			//if($correct < 1){
			//	// 正解なし
			//	if($answer_error_msg!='') $answer_error_msg .= '<br/>';
			//	$answer_error_msg .= $this->lang->line_or_def('error_answer_contents_correct_no_check','正解のチェックがありません。');
			//}elseif( ($param['answer_kind'] == 1) && ($correct != 1) ){
			//	// 単一形式かつ正解１つ以外はエラー
			//	if($answer_error_msg!='') $answer_error_msg .= '<br/>';
			//	$answer_error_msg .= $this->lang->line_or_def('error_answer_contents_correct_over_check','単一形式の場合、正解のチェックは１つにしてください。');
			//}
		}elseif($param['answer_kind'] == 3){
			// フリー回答：基本文字列ゼロ。今後文字内容チェックを行う可能性あり。
		}

		return $answer_error_msg;
	}

	//----------------------------------------------
	// [Ajax用]設問詳細を取得
	//----------------------------------------------
	function get_exam2_problem_detail(){
		
		$this->load->helper('json');
		
		$param = array(
					'school_id'        => $this->libauth->get_school_id(),
					'exam2_problem_id'  => ($this->input->post('exam2_problem_id')) ? $this->input->post('exam2_problem_id') : 0,
					);

		// 設問詳細を取得
		$this->load->model('model_exam2_problem');
		$exam2_problems = $this->model_exam2_problem->get_exam2_problem($param);
		// exam2_problem.exam2_problem_id          -- 設問ID
		// exam2_problem.exam2_problem_name        -- 設問名
		// exam2_problem.problem_kind             -- 設問種類 1:テキスト、2:動画、3:図書室
		// exam2_problem.problem_contents         -- 設問内容 テキスト、または、ビデオID・図書室ID
		// exam2_problem.problem_note             -- 設問備考
		// exam2_problem.answer_kind              -- 解答種類 1:単一形式、2:複数形式、3:フリー回答
		// exam2_problem.answer_contents          -- 解答内容 JSON型
		// exam2_problem.answer_point             -- 解答配点
		// exam2_problem.answer_explain_kind      -- 解答解説種類 1:テキスト、2:動画、3:図書室、9:なし
		// exam2_problem.answer_explain_contents  -- 解答解説内容 テキスト、または、ビデオID・図書室ID
		// exam2_problem.answer_explain_note      -- 解答解説備考
		// exam2_problem.school_id                -- 学校ID
		// exam2_problem.teacher_id               -- 講師ID
		// exam2_problem.status                   -- 状態 0:有効 9:削除
		// exam2_problem.update_at                -- 更新日時
		// 
		// teacher.teacher_name                  -- 講師名
		
		$result_data = array();
		
		if($exam2_problems){
			
			// 設問名・管理講師・解答配点
			$result_data['exam2_problems_name']         = $exam2_problems['exam2_problem_name'];
			$result_data['exam2_problems_teacher_name'] = $exam2_problems['teacher_name'];
			$result_data['exam2_problems_problem_kind'] = $exam2_problems['problem_kind'];
			
			// 設問 - 詳細 - 設問内容
			$temp_contents_id = 0;
			$temp_contents    = '';
			if($exam2_problems['problem_kind']== 1){
				// 設問種類→1:テキスト
				$temp_contents = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $exam2_problems['problem_contents']);
			}elseif($exam2_problems['problem_kind'] == 2){
				// 設問種類 → 2：ビデオ
				$this->load->model('model_video');
				$temp_contents_id = $exam2_problems['problem_contents'];
				$db_data          = $this->model_video->get_material(array('video_id' => $temp_contents_id));
				$temp_contents    = $db_data['video_logic_name'];
			}elseif($exam2_problems['problem_kind'] == 3){
				// 設問種類 → 3：図書室
				$this->load->model('model_book_library');
				$temp_contents_id = $exam2_problems['problem_contents'];
				$db_data          = $this->model_book_library->get_data(array('book_library_id' => $temp_contents_id));
				$temp_contents    = $db_data['book_library_logic_name'];
			}
			$result_data['exam2_problems_problem_contents_id'] = $temp_contents_id;
			$result_data['exam2_problems_problem_contents']    = $temp_contents;

			// 設問 - 詳細 - 解答種類（ID）
			$result_data['exam2_problems_answer_kind'] = $exam2_problems['answer_kind'];
					
			// 設問 - 詳細 - 解答内容	
			$temp_answer_contents = '';
			if( ($exam2_problems['answer_kind']==1) || ($exam2_problems['answer_kind']==2) ){
				// 解答種類 → 1：単一形式、2：複数形式
				$array_answer_contents = obj2arr(json_decode( $exam2_problems['answer_contents'] )); // JSON型、選択した番号の文字列
				
				for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
					$line_contents = (array)$array_answer_contents['answer_contents'][$i];
					//$line_contents['no'];
					//$line_contents['word'];
					//$line_contents['correct'];
					$temp_contents = '['.$this->lang->line_or_def('common_non_correct','誤').']';
					if($line_contents['correct']==1) $temp_contents = '['.$this->lang->line_or_def('common_correct','正').']';
							
					$line_contents['word'] = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $line_contents['word']);
					$temp_contents .= $line_contents['word'];
					if($temp_answer_contents != '') $temp_answer_contents .= "<br/>";
					$temp_answer_contents .= $temp_contents;
				}
			}elseif($exam2_problems['answer_kind']==3){
				// 解答種類 → 3：フリー回答
				$array_answer_contents = obj2arr(json_decode( $exam2_problems['answer_contents'] )); // JSON型、選択した番号の文字列
				$line_contents         = (array)$array_answer_contents['answer_contents'][0];
				$line_contents['word'] = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $line_contents['word']);
				$temp_answer_contents = $line_contents['word'];
			}
			$result_data['exam2_problems_answer_contents'] = $temp_answer_contents;
		}else{
			$result_data = array();
		}
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($result_data));
	}

	//----------------------------------------------
	// [Ajax用]解答詳細を取得
	//----------------------------------------------
	function get_exam2_answer_detail(){
	
		$this->load->helper('json');
		
		$param = array(
					'exam2_id'           => ($this->input->post('exam2_id'))                 ? $this->input->post('exam2_id')                 : 0,
					'student_id'        => ($this->input->post('answer_student_id'))       ? $this->input->post('answer_student_id')       : 0,
					'exam2_answer_no'    => ($this->input->post('answer_no'))               ? $this->input->post('answer_no')               : 0,
					'exam2_answer_data'  => ($this->input->post('exam2_answer_data'))        ? $this->input->post('exam2_answer_data')        : "",
				);

		// 解答修正確認画面から戻った際、正誤・解答配点 を編集した状態にする必要がある  例："483/1/23@15/1/23"
		$array_exam2_answer_data = array();
		if($param['exam2_answer_data']!=""){
			// カンマ区切りで配列変換
			$array_data = explode("@", $param['exam2_answer_data'] );
			foreach($array_data as $answer){
				$array_answer = explode("/", $answer );
				
				// 分割結果：要素数3・Index-0が0より大きい・Index-1が0 or 1・Index-2が-1より大きい
				if( (count($array_answer)==3) && ($array_answer[0]>0) && (($array_answer[1]==0) || ($array_answer[1]==1)) && ($array_answer[2]>-1) ){
					$array_exam2_answer_data[$array_answer[0]] = array(
						'exam2_answer_mark'  => $array_answer[1],       // 正誤
						'exam2_answer_point' => $array_answer[2],       // 解答配点
					);
				}
			}
		}

		// 設問詳細を取得
		$this->load->model('model_exam2_answer');
		$detail_result      = $this->model_exam2_answer->get_exam2_answer_detail($param);
		$exam2_answer_detail = $detail_result['items'];
		// exam2_answer.exam2_answer_id        -- 解答ID
		// exam2_answer.exam2_id               -- 問題ID
		// exam2_answer.exam2_problem_id       -- 設問ID
		// exam2_answer.student_id            -- 受講者ID
		// exam2_answer.exam2_answer_no        -- 解答回数
		// exam2_answer.exam2_answer_contents  -- 解答内容
		// exam2_answer.exam2_answer_date      -- 解答日時
		// exam2_answer.exam2_answer_mark      -- 解答結果 0:不正解 1:正解
		// exam2_answer.exam2_answer_point     -- 解答配点
		// exam2_answer.marked_teacher_id     -- 採点講師ID
		// exam2_answer.status                -- 状態 0:有効 9:削除
		// exam2_answer.update_at             -- 更新日時
		// 
		// exam2_problem.exam2_problem_name    -- 設問名
		// exam2_problem.problem_kind         -- 設問種類
		// exam2_problem.problem_contents     -- 設問内容
		// exam2_problem.answer_kind          -- 解答種類
		// exam2_problem.answer_contents      -- 解答内容
		// exam2_problem.answer_point         -- 解答配点
		
		$result_data = array();
		
		if(isset($exam2_answer_detail)){
			foreach($exam2_answer_detail as $exam2_answer){
				
				$temp_result_data = array();
				
				// 設問ID
				$temp_result_data['exam2_problem_id'] = $exam2_answer['exam2_problem_id'];
				
				// 設問名
				$temp_result_data['exam2_problem_name'] = $exam2_answer['exam2_problem_name'];
				
				// 解答ID
				$temp_result_data['exam2_answer_id'] = $exam2_answer['exam2_answer_id'];
				
				// 解答種類
				$temp_result_data['answer_kind'] = $exam2_answer['answer_kind'];
				
				// 解答内容 --- 解答種類が単一・複数の場合→設問テーブルより内容取得
				//              解答種類がテキストの場合→解答テーブルより内容取得
				$temp_answer_contents ='';
				if( ($exam2_answer['answer_kind']==1) || ($exam2_answer['answer_kind']==2) ){
					$array_exam2_answer_contents = explode(',', $exam2_answer['exam2_answer_contents']);      // 受講者が選択した番号
					$array_answer_contents      = obj2arr(json_decode( $exam2_answer['answer_contents'] )); // JSON型、選択した番号の文字列
					
					for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
						$line_contents = (array)$array_answer_contents['answer_contents'][$i];
						// 選択した番号と一致した内容を出力
						if( in_array( $line_contents['no'], $array_exam2_answer_contents) ){
							$line_contents['word'] = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $line_contents['word']);
							
							if($temp_answer_contents != '') $temp_answer_contents .= "<br/>";
							$temp_answer_contents .= $line_contents['word'];
						}
					}
				}elseif($exam2_answer['answer_kind']==3){
					$temp_answer_contents = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $exam2_answer['exam2_answer_contents']);
				}
				$temp_result_data['exam2_answer_contents'] = $temp_answer_contents;
				
				// 正誤（ID）
				$temp_result_data['exam2_answer_mark'] = $exam2_answer['exam2_answer_mark'];
				if( isset($array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark']) ){
					$temp_result_data['exam2_answer_mark'] = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark'];
				}
				
				// 解答配点（解答結果による配点）
				$temp_result_data['exam2_answer_point'] = $exam2_answer['exam2_answer_point'];
				if( isset($array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point']) ){
					$temp_result_data['exam2_answer_point'] = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'];
				}
				
				// 解答配点（設問に設定されている配点）
				$temp_result_data['answer_point'] = $exam2_answer['answer_point'];
				
				array_push($result_data, $temp_result_data);
			}
		}else{
			$temp_result_data = array();
			$temp_result_data['exam2_problem_id'] = 0;
			array_push($result_data, $temp_result_data);
		}
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($result_data));
	}

/* 
	//----------------------------------------------
	// 解答内容の内容チェック
	//----------------------------------------------
	function _check_answer_contents($param){
		$this->lang->load('error');
		
		//引数設定
		$overlap_msg = '';
		$param = array_merge(
						array(
							'answer_kind'              => 0,
							'answer_contents_no'       => array(),
							'answer_contents_word'     => array(),
							'answer_contents_correct'  => array(),
							'answer_contents_text'     => '',
						),
						$param
					);
		
		// フリー回答 -> チェック不要
		if($param['answer_kind'] == 3){
			return $overlap_msg;
		}

		// 単一形式・複数形式
		$answer_contents_no      = $param['answer_contents_no'];
		$answer_contents_word    = $param['answer_contents_word'];
		$answer_contents_correct = $param['answer_contents_correct'];
		$index_no    = -1;
		$check_count = 0;
		foreach($param['answer_contents_no'] as $no){
			$index_no = $index_no + 1;
		}
	}
 */
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
