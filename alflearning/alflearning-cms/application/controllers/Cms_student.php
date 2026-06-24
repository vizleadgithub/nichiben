<?php
ini_set('display_errors', 0);

#[AllowDynamicProperties]
class Cms_student extends CI_Controller {
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
		

		// custom_fputcsv_helper
		$this->load->helper('custom_fputcsv_helper');

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
	// [2012/10/12]検索条件、講座の追加
	// [2012/10/12]ページ移動時の検索条件が維持できるよう修正
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
		$this->form_validation->set_rules('s_name'            , $this->lang->line_or_def('common_name','名前')                                  , 'trim');
		$this->form_validation->set_rules('s_lawyer_number'   , $this->lang->line_or_def('common_','登録番号')                                  , 'trim');
		$this->form_validation->set_rules('s_lawyer_division' , $this->lang->line_or_def('common_','会員区分')                                  , 'trim');
		$this->form_validation->set_rules('s_email'           , $this->lang->line_or_def('common_mail_address','メールアドレス')                , 'trim');
		$this->form_validation->set_rules('s_bar_association' , $this->lang->line_or_def('common_','所属弁護士会')                              , 'trim');
		$this->form_validation->set_rules('s_free_word'       , $this->lang->line_or_def('common_freeword','フリーワード')                      , 'trim');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_on'       , $this->lang->line_or_def('common_','代替権限あり')             , 'trim');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_off'       , $this->lang->line_or_def('common_','代替権限なし')            , 'trim');
		$this->form_validation->set_rules('order_by'          , $this->lang->line_or_def('common_order_by','並び順')                            , 'trim');
		//$this->form_validation->set_rules('s_id'              , $this->lang->line_or_def('common_id','ID')                                      , 'trim');
		//$this->form_validation->set_rules('s_cource'          , $this->lang->line_or_def('common_course_name','講座名')                         , 'trim');
		//$this->form_validation->set_rules('s_birthday_start'  , $this->lang->line_or_def('common_date_of_birth_range_start','生年月日範囲開始') , 'trim');
		//$this->form_validation->set_rules('s_birthday_end'    , $this->lang->line_or_def('common_date_of_birth_range_end','生年月日範囲終了')   , 'trim');
		//$this->form_validation->set_rules('s_student_group'   , $this->lang->line_or_def('common_group','グループ')                             , 'trim');

		$this->form_validation->run();		//バリデーション実行（その実xss対策）

		//受講者モデル読み込み
		$this->load->model('model_student');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('student_search_cond') ?: array(
				's_name' => '',
				's_lawyer_number'=>'',
				's_lawyer_division' => '',
				's_email' => '',
				's_bar_association' => '',
				's_free_word' => '',
				's_sub_auth_ethic_training_on' => 0,
				's_sub_auth_ethic_training_off' => 0,
			);
		} else {
			//データ取得用引数設定
			$post = $this->input->post(NULL, TRUE);   // 全POST配列取得
			$data['s_name']				= ($this->input->post('s_name') ?? '');
			$data['s_lawyer_number']		= ($this->input->post('s_lawyer_number') ?? '');
			$data['s_lawyer_division']		= ($this->input->post('s_lawyer_division') ?? '');
			$data['s_email']			= ($this->input->post('s_email') ?? '');
			$data['s_bar_association']		= ($this->input->post('s_bar_association') ?? '');
			$data['s_free_word']			= ($this->input->post('s_free_word') ?? '');
			$data['s_sub_auth_ethic_training_on']	= ($this->input->post('s_sub_auth_ethic_training_on') ?? 0);
			$data['s_sub_auth_ethic_training_off']	= ($this->input->post('s_sub_auth_ethic_training_off') ?? 0);
			//$data['s_id']				= FALSE;
			//$data['s_cource']			= FALSE;
			//$data['s_birthday_start']		= FALSE;
			//$data['s_birthday_end']		= FALSE;
			//$data['s_student_group']		= FALSE;
			$this->session->set_userdata('student_search_cond', $data);
		}


		// 初期表示時のリスト非表示対応
		$first_show_lawyer_number = $data['s_lawyer_number'];
		if( ($data['s_name']===FALSE) && ($data['s_lawyer_number']===FALSE) && ($data['s_email']===FALSE) && ($data['s_bar_association']===FALSE) && ($data['s_free_word']===FALSE) && ($data['s_sub_auth_ethic_training_on']===FALSE) && ($data['s_sub_auth_ethic_training_off']===FALSE) ){
			$first_show_lawyer_number = -1;
		}

		// ログイン管理者が日本弁護士連合会（=1）以外は、ログイン管理者の所属弁護士会に強制
		$login_teacher_bar_association_id = $this->libauth->get_bar_association_id();
		if($login_teacher_bar_association_id > 1){
			$data['s_bar_association'] = $login_teacher_bar_association_id;
		}

		// 登録番号昇順降順の設定（getになければゼロ固定）
		if($this->input->get('order_by')){
			$data['order_by']  = intval(strip_tags($this->input->get('order_by', TRUE) ?? 0));
		}else{
			$data['order_by']  = 0;
		}

		// ID昇順降順のURL設定（order_by 項目があれば差し替え。なければ追加）
		if( preg_match('/order_by=\d{1}/', $_SERVER["REQUEST_URI"]) ){
			$data['order_by_asc']  = preg_replace('/order_by=\d{1}/','order_by=0',$_SERVER["REQUEST_URI"]);
			$data['order_by_desc'] = preg_replace('/order_by=\d{1}/','order_by=1',$_SERVER["REQUEST_URI"]);
		}else{
			$data['order_by_asc']  = $_SERVER["REQUEST_URI"].'?order_by=0';
			$data['order_by_desc'] = $_SERVER["REQUEST_URI"].'?order_by=1';
		}
		//print $_SERVER["REQUEST_URI"]."<br/>";
		//print preg_replace('/order_by=\d{1}/','order_by=0',$_SERVER["REQUEST_URI"])."<br/>";
		//print preg_replace('/order_by=\d{1}/','order_by=1',$_SERVER["REQUEST_URI"])."<br/>";

		$data_param = array(
			's_school_id'        => $this->libauth->get_school_id(),
			's_name'             => $data['s_name'],
			's_lawyer_number'    => $first_show_lawyer_number,
			's_lawyer_division'  => $data['s_lawyer_division'],
			's_email'            => $data['s_email'],
			's_bar_association'  => $data['s_bar_association'],
			's_free_word'        => $data['s_free_word'],
			's_sub_auth_ethic_training_on'   => $data['s_sub_auth_ethic_training_on'],
			's_sub_auth_ethic_training_off'  => $data['s_sub_auth_ethic_training_off'],
			'offset'                         => $offset,
			'rowcount'                       => $per_page,
			'order_by'                       => $data['order_by'],
		);
		  //'s_student_id'     => $data['s_id'],
		  //'s_cource'         => $data['s_cource'],
		  //'s_birthday_start' => $data['s_birthday_start'],
		  //'s_birthday_end'   => $data['s_birthday_end'],
		  //'s_student_group'  => $data['s_student_group'],
		
		//データ取得
		$student_list = $this->model_student->get_student_search_list($data_param);
		$data['student_list'] = $student_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_student/index';
		$config['total_rows'] = $this->model_student->get_student_search_count($data_param);
		$config['per_page']   = $per_page;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link']  = '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		// [2012/10/12]
		if(count($_GET)){
			$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1?'.http_build_query($_GET).'$2', $data['pagination']);
		}

		$data['start_rows'] = $offset + 1;//($per_page * $offset) + 1;
		$data['end_rows']   = $data['start_rows'] + count($student_list) - 1;
		$data['total_rows'] = $config['total_rows'];
		
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/09/10]student_password_change・student_password_identity追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['student']['update_flg']             = 0;
		$data['student']['student_id']             = 0;
		$data['student']['student_name']           = '';
		$data['student']['student_email']          = '';
		$data['student']['student_password']       = '';
		$data['student']['student_password_check'] = '';
		$data['student']['student_password_change']   = 1;	
		$data['student']['student_password_identity'] = 0;
		$data['student']['student_birthday']       = '';
		$data['student']['student_lectures']       = array();
		$data['student']['student_note']           = '';
		
		//** 法学館対応 **//
		$data['student']['regist_at']                   = '';		// 入会日時
		$data['student']['delete_at']                   = '';		// 退会日時
		$data['student']['student_name_kana']           = '';		// 生徒氏名カナ
		$data['student']['sex']                         = 1;		// 性別 1:男性 2:女性（mtb_gender）
		$data['student']['country_type']                = 1;		// 国種別 1:国内 2:国外（初期値1）mtb_country_type
		$data['student']['zip']                         = '';		// 郵便番号
		$data['student']['pref']                        = '';		// 都道府県ID（mtb_pref テーブル連動）
		$data['student']['address1']                    = '';		// 市区町村
		$data['student']['address2']                    = '';		// 番地
		$data['student']['address3']                    = '';		// 建物名
		$data['student']['zip_overseas']                = '';		// 海外郵便番号
		$data['student']['address_overseas1']           = '';		// 海外住所・住所1
		$data['student']['address_overseas2']           = '';		// 海外住所・住所2
		$data['student']['member_type']                 = 1;		// 会員属性 1:通常 2:月額課金（初期値1）
		$data['student']['tel1']                        = '';		// 電話番号1
		$data['student']['tel2']                        = '';		// 電話番号2
		$data['student']['tel3']                        = '';		// 電話番号3
		$data['student']['fax1']                        = '';		// FAX番号1
		$data['student']['fax2']                        = '';		// FAX番号2
		$data['student']['fax3']                        = '';		// FAX番号3
		$data['student']['student_email_mobile']        = '';		// 携帯メールアドレス
		$data['student']['job']                         = 0;		// 職業ID（mtb_job テーブル連動）
		$data['student']['job_type']                    = 0;		// 業種ID（mtb_job_type テーブル連動）
		$data['student']['school_name']                 = '';		// 学校名
		$data['student']['school_grade']                = '';		// 学年（mtb_school_grade テーブル連動）
		$data['student']['password_question']           = 0;		// パスワード確認質問（mtb_password_question テーブル連動）
		$data['student']['password_answer']             = '';		// パスワード確認回答
			$data['student']['password_answer_change']  = 1;		//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
			$data['student']['old_password_question']   = 0;		//   ※パスワード確認質問（変更前情報を格納）
		$data['student']['mailmagazine_flg']            = 0;		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
		$data['student']['mailmagazine_ids']            = '';		// メルマガID(複数時はカンマ区切りで入れる)
			$data['student']['mailmagazine_ids_array']  = array();	//   ※メルマガID（配列）
		$data['student']['media_id']                    = 0;		// 認知媒体ID（mtb_media）
			$data['student']['media_id_array']          = array();	//   ※認知媒体ID（配列）
		$data['student']['age']                         = 0;		// 年代（mtb_age）
		$data['student']['student_no']                  = '';		// 伊藤塾塾生番号
		$data['student']['user_last_update_date']       = '';		// 最終更新日
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student/edit',
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
			redirect('/cms_student/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['student_id']) &&  $edit_form_data['student_id'] <> ''){
			//画面表示用データ設定
			$data['student'] = $edit_form_data;

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student/edit',
							'submenu_idx' => ($data['student']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('student');
			
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
	// [2012/08/20]受講講座名表示順を ID昇順から講座名昇順に変更
	// [2012/09/10]student_password_change・student_password_identity追加
	// [2012/11/01]外部連携API実行エラー発生時、詳細画面へ返す機能を追加
	//----------------------------------------------
	function detail($student_id, $result_data = array()){
		$student_id = intval($student_id);

		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_student');
		
		// ログイン管理者が日本弁護士連合会（=1）以外は、ログイン管理者の所属弁護士会に強制
		$bar_association_id = '';
		$login_teacher_bar_association_id = $this->libauth->get_bar_association_id();
		if($login_teacher_bar_association_id > 1){
			$bar_association_id = $login_teacher_bar_association_id;
		}
		
		//データ取得用引数設定
		$data_param = array(
						'student_id'         => $student_id,
						'bar_association_id' => $bar_association_id,
					);
		//データ取得
		$db_data = $this->model_student->get_student($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['student']['update_flg']              = 1;
			$data['student']['student_id']              = $db_data['student_id'];
			$data['student']['student_name']            = $db_data['student_name'];					// 氏名
			$data['student']['lawyer_number']           = $db_data['lawyer_number'];				// 弁護士番号
			$data['student']['lawyer_division']         = $db_data['lawyer_division'];				// 会員区分
			$data['student']['bar_association_id']      = $db_data['bar_association_id'];			// 所属弁護士会ID
			$data['student']['regist_date']             = $db_data['regist_date'];					// 登録年月日
			$data['student']['exp_date_passport']       = $db_data['exp_date_passport'];			// パスポートの有効期限
			$data['student']['presence_passport']       = $db_data['presence_passport'];			// パスポートの有無 0:なし、1:あり
			$data['student']['target_passport']         = $db_data['target_passport'];				// 対象パスポート（パスポート料金）
			$data['student']['student_email']           = $db_data['student_email'];				// メールアドレス
			$data['student']['mailmagazine_flg']        = $db_data['mailmagazine_flg'];				// 0:メルマガ拒否 1:メルマガ許可（初期値0）
			$data['student']['sub_auth_ethic_training'] = $db_data['sub_auth_ethic_training'];		// 代替倫理研修権限 0:なし（禁止）、1:あり（許可）
			
			if($_SERVER['HTTP_REFERER']){
				//$data['student']['history_back_url'] = $_SERVER['HTTP_REFERER'];
				$data['student']['history_back_url'] = "/cms_student/";
			}else{
				$data['student']['history_back_url'] = 'history.back()';
			}
		
			if( array_key_exists('elm_stat', $result_data) ){
				$data['elm_result']  = $result_data['elm_result'];
				$data['elm_stat']    = $result_data['elm_stat'];
				$data['elm_message'] = $result_data['elm_message'];
			}
			
			//受講講座取得
		  //$lectures = $this->model_student->get_student_lectures($data_param);
		  //$idx = -1;
		  //if(isset($lectures)){
		  //	foreach($lectures as $lecture){
		  //		$idx++;
		  //		$data['student']['student_lectures'][$idx] = $lecture['cource_id'];
		  //	}
		  //}
			
			//受講講座名設定
		  //$data['student']['student_lectures_name'] = array();
		  //$this->load->model('model_cource');
		  //if($data['student']['student_lectures']){
		  //	foreach($data['student']['student_lectures'] as $idx => $lecture){
		  //		//データ取得用引数設定
		  //		$name_param = array(
		  //						'cource_id' => $lecture,
		  //					);
		  //	//	$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
		  //		if($this->model_cource->get_name($name_param)){
		  //			$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
		  //		}
		  //	}
		  //	asort($data['student']['student_lectures_name']);    // [2012/08/20]
		  //}
			
			// 受講者単位の視聴履歴を取得
		  //$this->load->model('model_video');
		  //$data['history_data'] = $this->model_video->get_video_reading_history(array(
		  //	'student_id'	=> $db_data['student_id'],
		  //	'video_id'		=> 0,
		  //));

			
			//** 法学館対応 **//
		  //$data['student']['regist_at']                   = $db_data['regist_at'];			// 入会日時
		  //$data['student']['delete_at']                   = $db_data['delete_at'];			// 退会日時
		  //$data['student']['student_name_kana']           = $db_data['student_name_kana'];	// 生徒氏名カナ
		  //$data['student']['sex']                         = $db_data['sex'];					// 性別 1:男性 2:女性（mtb_gender）
		  //$data['student']['country_type']                = $db_data['country_type'];			// 国種別 1:国内 2:国外（初期値1）mtb_country_type
		  //$data['student']['zip']                         = $db_data['zip'];					// 郵便番号
		  //$data['student']['pref']                        = $db_data['pref'];					// 都道府県ID（mtb_pref テーブル連動）
		  //$data['student']['address1']                    = $db_data['address1'];				// 市区町村
		  //$data['student']['address2']                    = $db_data['address2'];				// 番地
		  //$data['student']['address3']                    = $db_data['address3'];				// 建物名
		  //$data['student']['zip_overseas']                = $db_data['zip_overseas'];			// 海外郵便番号
		  //$data['student']['address_overseas1']           = $db_data['address_overseas1'];	// 海外住所・住所1
		  //$data['student']['address_overseas2']           = $db_data['address_overseas2'];	// 海外住所・住所2
		  //$data['student']['member_type']                 = $db_data['member_type'];			// 会員属性 1:通常 2:月額課金（初期値1）
		  //$data['student']['tel1']                        = $db_data['tel1'];					// 電話番号1
		  //$data['student']['tel2']                        = $db_data['tel2'];					// 電話番号2
		  //$data['student']['tel3']                        = $db_data['tel3'];					// 電話番号3
		  //$data['student']['fax1']                        = $db_data['fax1'];					// FAX番号1
		  //$data['student']['fax2']                        = $db_data['fax2'];					// FAX番号2
		  //$data['student']['fax3']                        = $db_data['fax3'];					// FAX番号3
		  //$data['student']['student_email_mobile']        = $db_data['student_email_mobile'];	// 携帯メールアドレス
		  //$data['student']['job']                         = $db_data['job'];					// 職業ID（mtb_job テーブル連動）
		  //$data['student']['job_type']                    = $db_data['job_type'];				// 業種ID（mtb_job_type テーブル連動）
		  //$data['student']['school_name']                 = $db_data['school_name'];			// 学校名
		  //$data['student']['school_grade']                = $db_data['school_grade'];			// 学年（mtb_school_grade テーブル連動）
		  //$data['student']['password_question']           = $db_data['password_question'];	// パスワード確認質問（mtb_password_question テーブル連動）
		  //$data['student']['password_answer']             = '';								// パスワード確認回答	//$db_data['password_answer'];
		  //	$data['student']['password_answer_change']  = 0;								//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
		  //	$data['student']['old_password_question']   = $db_data['password_question'];	//   ※パスワード確認質問（変更前情報を格納）
		  //$data['student']['mailmagazine_flg']            = $db_data['mailmagazine_flg'];		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
		  //$data['student']['mailmagazine_ids']            = $db_data['mailmagazine_ids'];		// メルマガID(複数時はカンマ区切りで入れる)
		  //	$data['student']['mailmagazine_ids_array']  = explode(',',$data['student']['mailmagazine_ids']);	//   ※メルマガID（配列）
		  //$data['student']['media_id']                    = $db_data['media_id'];				// 認知媒体ID（mtb_media）
		  //	$data['student']['media_id_array']          = explode(',',$data['student']['media_id']);	//   ※認知媒体ID（配列）
		  //$data['student']['age']                         = $db_data['age'];					// 年代（mtb_age）
		  //$data['student']['student_no']                  = $db_data['student_no'];			// 伊藤塾塾生番号
		  //$data['student']['user_last_update_date']       = $db_data['user_last_update_date'];		// 最終更新日

			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['student']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_student/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/09/10]student_password_change・student_password_identity追加
	// 法学館対応：生年月日の必須を削除
	//----------------------------------------------
	function confirm(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'            , $this->lang->line_or_def('common_flg','flg')                              , 'trim|numeric');
		$this->form_validation->set_rules('student_id'            , $this->lang->line_or_def('common_id','ID')                                , 'trim|numeric');
		$this->form_validation->set_rules('student_name'          , $this->lang->line_or_def('common_name','名前')                            , 'trim|required|callback_name_check');
		$this->form_validation->set_rules('student_email'         , $this->lang->line_or_def('common_mail_address','メールアドレス')          , 'trim|required|valid_email');
//		$this->form_validation->set_rules('student_password'      , $this->lang->line_or_def('common_password','パスワード')                  , 'trim|required');
//		$this->form_validation->set_rules('student_password_check', $this->lang->line_or_def('common_password_conf','パスワード（確認入力）') , 'trim|required|matches[student_password]');
		if( ($this->input->post('update_flg') == 0) || (($this->input->post('update_flg') != 0) && ($this->input->post('student_password_change') == 1)) ){
			$this->form_validation->set_rules('student_password'        , $this->lang->line_or_def('common_password','パスワード')                     , 'trim|required');
			$this->form_validation->set_rules('student_password_check'  , $this->lang->line_or_def('common_password_conf','パスワード（確認入力）')    , 'trim|required|matches[student_password]');
		}
	//	$this->form_validation->set_rules('student_birthday'      , $this->lang->line_or_def('common_date_of_birth','生年月日')               , 'trim|required|callback_date_check');
		$this->form_validation->set_rules('student_lectures'      , $this->lang->line_or_def('common_attendance_class','受講講座')            , 'required');
		$this->form_validation->set_rules('student_note'          , $this->lang->line_or_def('common_note','備考')                            , 'trim');
		
		// 法学館対応
		// パスワード確認変更がある場合の処理（新規登録、変更でパスワード確認変更を行う場合）
		if( ($this->input->post('update_flg') == 0) || (($this->input->post('update_flg') != 0) && ($this->input->post('password_answer_change') == 1)) ){
			$this->form_validation->set_rules('password_answer'  , $this->lang->line_or_def('password_answer','パスワード確認回答')    , 'trim|required');
		}
		$this->form_validation->set_rules('student_email_mobile'  , $this->lang->line_or_def('common_hogaku','携帯メールアドレス')          , 'trim|valid_email');
		
		
		
		//検証
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['student']['update_flg']                = $this->input->post('update_flg');
			$data['student']['student_id']                = $this->input->post('student_id');
			$data['student']['student_name']              = '';
			$data['student']['student_email']             = '';
			$data['student']['student_password']          = '';
			$data['student']['student_password_check']    = '';
			$data['student']['student_password_change']   = $this->input->post('student_password_change');
			$data['student']['student_password_identity'] = $this->input->post('student_password_identity');
			$data['student']['student_birthday']          = '';
			$data['student']['student_lectures']          = $this->input->post('student_lectures')?$this->input->post('student_lectures'):array();
			$data['student']['student_note']              = '';
				
			$data['student']['regist_at']                   = '';		// 入会日時
			$data['student']['delete_at']                   = '';		// 退会日時
			$data['student']['student_name_kana']           = '';		// 生徒氏名カナ
			$data['student']['sex']                         = 1;		// 性別 1:男性 2:女性（mtb_gender）
			$data['student']['country_type']                = 1;		// 国種別 1:国内 2:国外（初期値1）mtb_country_type
			$data['student']['zip']                         = '';		// 郵便番号
			$data['student']['pref']                        = '';		// 都道府県ID（mtb_pref テーブル連動）
			$data['student']['address1']                    = '';		// 市区町村
			$data['student']['address2']                    = '';		// 番地
			$data['student']['address3']                    = '';		// 建物名
			$data['student']['zip_overseas']                = '';		// 海外郵便番号
			$data['student']['address_overseas1']           = '';		// 海外住所・住所1
			$data['student']['address_overseas2']           = '';		// 海外住所・住所2
			$data['student']['member_type']                 = 1;		// 会員属性 1:通常 2:月額課金（初期値1）
			$data['student']['tel1']                        = '';		// 電話番号1
			$data['student']['tel2']                        = '';		// 電話番号2
			$data['student']['tel3']                        = '';		// 電話番号3
			$data['student']['fax1']                        = '';		// FAX番号1
			$data['student']['fax2']                        = '';		// FAX番号2
			$data['student']['fax3']                        = '';		// FAX番号3
			$data['student']['student_email_mobile']        = '';		// 携帯メールアドレス
			$data['student']['job']                         = 0;		// 職業ID（mtb_job テーブル連動）
			$data['student']['job_type']                    = 0;		// 業種ID（mtb_job_type テーブル連動）
			$data['student']['school_name']                 = '';		// 学校名
			$data['student']['school_grade']                = '';		// 学年（mtb_school_grade テーブル連動）
			$data['student']['password_question']           = $this->input->post('old_password_question');	// パスワード確認質問（mtb_password_question テーブル連動）
			$data['student']['password_answer']             = '';		// パスワード確認回答
				$data['student']['password_answer_change']  = $this->input->post('password_answer_change');	//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
				$data['student']['old_password_question']   = $this->input->post('old_password_question');	//   ※パスワード確認質問（変更前情報を格納）
			$data['student']['mailmagazine_flg']            = 0;		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
			$data['student']['mailmagazine_ids']            = '';		// メルマガID(複数時はカンマ区切りで入れる)
				$data['student']['mailmagazine_ids_array']  = array();	//   ※メルマガID（配列）
			$data['student']['media_id']                    = 0;		// 認知媒体ID（mtb_media）
				$data['student']['media_id_array']          = array();	//   ※認知媒体ID（配列）
			$data['student']['age']                         = 0;		// 年代（mtb_age）
			$data['student']['student_no']                  = '';		// 伊藤塾塾生番号
			$data['student']['user_last_update_date']       = '';		// 最終更新日
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student/edit',
							'submenu_idx' => ($data['student']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//重複チェック
			$query = $this->db->query(
				' SELECT * FROM student'.
				' WHERE student_email = ? '.
				' AND student.school_id = ?'.
				' AND student.status = 0'.
				' LIMIT 0, 1',
				array(
					$this->input->post('student_email'),
					$this->libauth->get_school_id(),
				)
			);
			if(!$this->input->post('update_flg') && $query->row()){
				$data['student']['update_flg']                = $this->input->post('update_flg');
				$data['student']['student_id']                = $this->input->post('student_id');
				$data['student']['student_name']              = '';
				$data['student']['student_email']             = '';
				$data['student']['student_password']          = '';
				$data['student']['student_password_check']    = '';
				$data['student']['student_password_change']   = $this->input->post('student_password_change');
				$data['student']['student_password_identity'] = $this->input->post('student_password_identity');
				$data['student']['student_birthday']          = '';
				$data['student']['student_lectures']          = $this->input->post('student_lectures')?$this->input->post('student_lectures'):array();
				$data['student']['student_note']              = '';
				$data['error_msg']				              = $this->lang->line_or_def('error_mailaddress','使用済みのメールアドレスです');
				
				$data['student']['regist_at']                   = '';		// 入会日時
				$data['student']['delete_at']                   = '';		// 退会日時
				$data['student']['student_name_kana']           = '';		// 生徒氏名カナ
				$data['student']['sex']                         = 1;		// 性別 1:男性 2:女性（mtb_gender）
				$data['student']['country_type']                = 1;		// 国種別 1:国内 2:国外（初期値1）mtb_country_type
				$data['student']['zip']                         = '';		// 郵便番号
				$data['student']['pref']                        = '';		// 都道府県ID（mtb_pref テーブル連動）
				$data['student']['address1']                    = '';		// 市区町村
				$data['student']['address2']                    = '';		// 番地
				$data['student']['address3']                    = '';		// 建物名
				$data['student']['zip_overseas']                = '';		// 海外郵便番号
				$data['student']['address_overseas1']           = '';		// 海外住所・住所1
				$data['student']['address_overseas2']           = '';		// 海外住所・住所2
				$data['student']['member_type']                 = 1;		// 会員属性 1:通常 2:月額課金（初期値1）
				$data['student']['tel1']                        = '';		// 電話番号1
				$data['student']['tel2']                        = '';		// 電話番号2
				$data['student']['tel3']                        = '';		// 電話番号3
				$data['student']['fax1']                        = '';		// FAX番号1
				$data['student']['fax2']                        = '';		// FAX番号2
				$data['student']['fax3']                        = '';		// FAX番号3
				$data['student']['student_email_mobile']        = '';		// 携帯メールアドレス
				$data['student']['job']                         = 0;		// 職業ID（mtb_job テーブル連動）
				$data['student']['job_type']                    = 0;		// 業種ID（mtb_job_type テーブル連動）
				$data['student']['school_name']                 = '';		// 学校名
				$data['student']['school_grade']                = '';		// 学年（mtb_school_grade テーブル連動）
				$data['student']['password_question']           = $this->input->post('old_password_question');		// パスワード確認質問（mtb_password_question テーブル連動）
				$data['student']['password_answer']             = '';		// パスワード確認回答
					$data['student']['password_answer_change']  = $this->input->post('password_answer_change');	//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
					$data['student']['old_password_question']   = $this->input->post('old_password_question');	//   ※パスワード確認質問（変更前情報を格納）
				$data['student']['mailmagazine_flg']            = 0;		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
				$data['student']['mailmagazine_ids']            = '';		// メルマガID(複数時はカンマ区切りで入れる)
					$data['student']['mailmagazine_ids_array']  = array();	//   ※メルマガID（配列）
				$data['student']['media_id']                    = 0;		// 認知媒体ID（mtb_media）
					$data['student']['media_id_array']          = array();	//   ※認知媒体ID（配列）
				$data['student']['age']                         = 0;		// 年代（mtb_age）
				$data['student']['student_no']                  = '';		// 伊藤塾塾生番号
				$data['student']['user_last_update_date']       = '';		// 最終更新日
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student/edit',
								'submenu_idx' => ($data['student']['update_flg']==0 ? 2 : 3),
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
			$data['student']['update_flg']                = $this->input->post('update_flg');
			$data['student']['student_id']                = $this->input->post('student_id');
			$data['student']['student_name']              = $this->input->post('student_name');
			$data['student']['student_email']             = $this->input->post('student_email');
			$data['student']['student_password']          = $this->input->post('student_password');
			$data['student']['student_password_check']    = $this->input->post('student_password_check');
			$data['student']['student_password_change']   = $this->input->post('student_password_change');
			$data['student']['student_password_identity'] = $this->input->post('student_password_identity');
			$data['student']['student_birthday']          = $this->input->post('student_birthday');
			$data['student']['student_lectures']          = $this->input->post('student_lectures') ? $this->input->post('student_lectures') : array();
			$data['student']['student_note']              = $this->input->post('student_note');

			$data['student']['regist_at']               = $this->input->post('regist_at');				// 入会日時
			$data['student']['delete_at']               = $this->input->post('delete_at');				// 退会日時
			$data['student']['student_name_kana']       = $this->input->post('student_name_kana');		// 生徒氏名カナ
			$data['student']['sex']                     = $this->input->post('sex');					// 性別 1:男性 2:女性（mtb_gender）
			$data['student']['country_type']            = $this->input->post('country_type');			// 国種別 1:国内 2:国外（初期値1）mtb_country_type
			$data['student']['zip']                     = $this->input->post('zip');					// 郵便番号
			$data['student']['pref']                    = $this->input->post('pref');					// 都道府県ID（mtb_pref テーブル連動）
			$data['student']['address1']                = $this->input->post('address1');				// 市区町村
			$data['student']['address2']                = $this->input->post('address2');				// 番地
			$data['student']['address3']                = $this->input->post('address3');				// 建物名
			$data['student']['zip_overseas']            = $this->input->post('zip_overseas');			// 海外郵便番号
			$data['student']['address_overseas1']       = $this->input->post('address_overseas1');		// 海外住所・住所1
			$data['student']['address_overseas2']       = $this->input->post('address_overseas2');		// 海外住所・住所2
			$data['student']['member_type']             = $this->input->post('member_type');			// 会員属性 1:通常 2:月額課金（初期値1）
			$data['student']['tel1']                    = $this->input->post('tel1');					// 電話番号1
			$data['student']['tel2']                    = $this->input->post('tel2');					// 電話番号2
			$data['student']['tel3']                    = $this->input->post('tel3');					// 電話番号3
			$data['student']['fax1']                    = $this->input->post('fax1');					// FAX番号1
			$data['student']['fax2']                    = $this->input->post('fax2');					// FAX番号2
			$data['student']['fax3']                    = $this->input->post('fax3');					// FAX番号3
			$data['student']['student_email_mobile']    = $this->input->post('student_email_mobile');	// 携帯メールアドレス
			$data['student']['job']                     = $this->input->post('job');					// 職業ID（mtb_job テーブル連動）
			$data['student']['job_type']                = $this->input->post('job_type');				// 業種ID（mtb_job_type テーブル連動）
			$data['student']['school_name']             = $this->input->post('school_name');			// 学校名
			$data['student']['school_grade']            = $this->input->post('school_grade');			// 学年（mtb_school_grade テーブル連動）
			$data['student']['password_question']       = $this->input->post('password_question');		// パスワード確認質問（mtb_password_question テーブル連動）
			$data['student']['password_answer']         = $this->input->post('password_answer');		// パスワード確認回答
			$data['student']['password_answer_change']  = $this->input->post('password_answer_change');	//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
			$data['student']['old_password_question']   = $this->input->post('old_password_question');	//   ※パスワード確認質問（変更前情報を格納）
			
			// パスワード確認質問変更なしの場合、password_question が空になるので、old_password_question の値を格納
			if($data['student']['password_answer_change']!=1){
				$data['student']['password_question']       = $this->input->post('old_password_question');
			}
			
			$data['student']['mailmagazine_flg']        = $this->input->post('mailmagazine_flg');	// 0:メルマガ拒否 1:メルマガ許可（初期値0）
			$data['student']['mailmagazine_ids_array']  = $this->input->post('mailmagazine_ids_array')?$this->input->post('mailmagazine_ids_array'):array();	//   ※メルマガID（配列）
			$data['student']['mailmagazine_ids']        = implode(',', $data['student']['mailmagazine_ids_array']);	// メルマガID(複数時はカンマ区切りで入れる)

		//	$data['student']['media_id']                = $this->input->post('media_id');			// 認知媒体ID（mtb_media）
			$data['student']['media_id_array']          = $this->input->post('media_id_array')?$this->input->post('media_id_array'):array();	//   ※認知媒体ID（配列）
			$data['student']['media_id']                = implode(',', $data['student']['media_id_array']);	// 認知媒体ID(複数時はカンマ区切りで入れる)

			$data['student']['age']                     = $this->input->post('age');				// 年代（mtb_age）
			$data['student']['student_no']              = $this->input->post('student_no');			// 伊藤塾塾生番号
			$data['student']['user_last_update_date']   = $this->input->post('user_last_update_date');		// 最終更新日
			
			$data['student']['student_lectures_name'] = array();
			//受講講座名設定
			$this->load->model('model_cource');
			if($this->input->post('student_lectures')){
				foreach($this->input->post('student_lectures') as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
					$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
				}
			}
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['student']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student/confirm',
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
		
		if(isset($edit_form_data['student_id']) && $edit_form_data['student_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			
			//モデル読み込み
			$this->load->model('model_student');
			
			
			//データ更新用引数設定
			$data_param = array(
							'data' => $edit_form_data,
						);
			//データ更新（外部連携API実行結果含む）
			$data = $this->model_student->update_student($data_param);

			if($data['stat'] == 200){
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_student/commit',
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
								'view_name'   => 'cms_student/edit',
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
	// [2012/11/01]外部連携API実行エラー発生時、詳細画面へ返す機能を追加
	//----------------------------------------------
	function delete_item($student_id){
		//モデル読み込み
		$this->load->model('model_student');
		
		//データ更新用引数再設定
		$data_param = array(
						'student_id' => $student_id,
					);
		//データ削除（外部連携API実行結果含む）
		$data = $this->model_student->delete_student($data_param);

		if($data['stat'] == 200){
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');

			//戻る
			$_offset = ($this->session->userdata('offset') ? $this->session->userdata('offset') : 0);
			$this->session->unset_userdata('offset');
			redirect("/cms_student/index/$_offset/");
		}else{
			$result_data['elm_result']  = $data['result'];
			$result_data['elm_stat']    = $data['stat'];
			$result_data['elm_message'] = $data['message'];
			
			// 詳細画面の表示
			$this->detail($student_id, $result_data);
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
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//受講講座チェックボックス用データ取得
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);
		
		//講座ドロップダウン用データ取得
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);
		
		//グループドロップダウン用データ取得
		//受講生編集画面時、登録済みグループ名表示にも使用
		$param['view_data']['student_groups_dropdown'] = $this->_get_student_group_list_array($drop_param);
		
		
		//** 法学館用 **//
		/*
		mtb_gender                : 性別マスタ
		mtb_country_type          : 国種別マスタ
		mtb_pref                  : 都道府県マスタ
		mtb_member_type           : [非テーブル]1:通常 2:月額課金
		mtb_job                   : 職業マスタ
		mtb_job_type              : 業種マスタ
		mtb_school_grade          : 学年マスタ
		mtb_password_question     : パスワード忘れ質問マスタ
		mtb_mailmagazine_flg      : [非テーブル]0:メルマガ拒否 1:メルマガ許可
		mtb_mailmagazine_category : メールマガジンカテゴリマスタ
		mtb_media                 : 認知媒体マスタ
		mtb_age                   : 年代マスタ
		*/
	//	$param['view_data']['mtb_gender']                = $this->get_mtb_list('mtb_gender');
	//	$param['view_data']['mtb_country_type']          = $this->get_mtb_list('mtb_country_type');
	//	$param['view_data']['mtb_pref']                  = $this->get_mtb_list('mtb_pref');
	//	$param['view_data']['mtb_member_type']           = $this->get_mtb_list('mtb_member_type');	// 非テーブル
	//	$param['view_data']['mtb_job']                   = $this->get_mtb_list('mtb_job');
	//	$param['view_data']['mtb_job_type']              = $this->get_mtb_list('mtb_job_type');
	//	$param['view_data']['mtb_school_grade']          = $this->get_mtb_list('mtb_school_grade');
	//	$param['view_data']['mtb_password_question']     = $this->get_mtb_list('mtb_password_question');
		$param['view_data']['mtb_mailmagazine_flg']      = $this->get_mtb_list('mtb_mailmagazine_flg');	// 非テーブル
	//	$param['view_data']['mtb_mailmagazine_category'] = $this->get_mtb_list('mtb_mailmagazine_category');
	//	$param['view_data']['mtb_media']                 = $this->get_mtb_list('mtb_media');
	//	$param['view_data']['mtb_age']                   = $this->get_mtb_list('mtb_age');
		
		$param['view_data']['mtb_sub_auth_ethic_training'] = $this->get_mtb_list('mtb_sub_auth_ethic_training');	// 非テーブル
		$param['view_data']['mtb_bar_association']         = $this->get_mtb_list('mtb_bar_association');
		
		$param['view_data']['mtb_lawyer_division']         = $this->get_mtb_list('mtb_lawyer_division');	// 非テーブル
		
		
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
	// [2012/10/25]姓名間チェック
	//----------------------------------------------
	function name_check($name){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('name_check', $this->lang->line_or_def('error_name','%sは性と名の間にスペースを入力してください。'));

		$name = preg_replace('/\s/i', '　', $name);
		
		if(!preg_match('/^.+?　.+$/', $name)){
			return false;
		}
		return true;
	}

	//----------------------------------------------
	// [2012/10/12]講座一覧ドロップダウン用配列取得
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
	// 受講者グループ一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_student_group_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_student_group');
		
		$result_data = $this->model_student_group->get_student_group_list($param);
		$data['student_group'] = array();
		$data['student_group'][''] = '';

		if($result_data){
			foreach($result_data as $row){
				$data['student_group'][$row['student_group_id']] = $row['student_group_name'];
			}
		}
		
		return $data['student_group'];
	}

	//** 法学館対応 **//
	//----------------------------------------------
	// 法学館各マスタ一覧ドロップダウン用配列取得
	//
	// 日弁連対応　文言変更
	//----------------------------------------------
	function get_mtb_list($table_name = ''){
		// 変数初期化
		$data = array();

		//引数確認（引数なしは空カラムを返す）
		if($table_name == ''){
			$data[''] = '';
			return $data;
		}
		
		// 会員属性用
		if($table_name == 'mtb_member_type'){
			$data[1] = "通常";
			$data[2] = "月額課金";
			return $data;
		}
		
		// メルマガ拒否・受取用
		if($table_name == 'mtb_mailmagazine_flg'){
			$data[0] = "受け取らない";	//"メルマガ拒否";
			$data[1] = "受け取る";		//"メルマガ許可";
			return $data;
		}



		// 代替倫理研修権限（日弁連対応）
		if($table_name == 'mtb_sub_auth_ethic_training'){
			$data[0] = "禁止";
			$data[1] = "許可";
			return $data;
		}

		// 会員区分（日弁連対応）
		//  1：弁護士 2：準会員 3：沖縄特別会員 4：沖縄準会員 5：外国法事務弁護士
		if($table_name == 'mtb_lawyer_division'){
			$data[0]   = "";
			$data[1]   = "弁護士";
			$data[2]   = "準会員";
			$data[3]   = "沖縄特別会員";
			$data[4]   = "沖縄準会員";
			$data[5]   = "外国法事務弁護士";
			$data[999] = "事務局";
			/*
			$data[0]   = "";
			$data[1]   = "広報課";
			$data[2]   = "情報システム・施設管理課";
			$data[3]   = "事務局共通";
			$data[4]   = "";
			$data[5]   = "弁護士会";
			$data[21]   = "会員（正会員）";
			$data[24]   = "会員（沖縄特別会員）";
			$data[25]   = "会員（外国特別会員）";
			$data[31]   = "外部委員";
			$data[999] = "その他";
			*/
			return $data;
		}

		// マスタデータ取得
		$this->load->model('model_student');
		$result_data = $this->model_student->get_mtb_list($table_name);

		if($result_data){
			foreach($result_data as $row){
				$data[$row['id']] = $row['name'];
			}
		}
		
		return $data;
	}


	//** 日弁連対応 **//
	//----------------------------------------------
	// 日弁連対応：代替倫理研修権限の更新
	//----------------------------------------------
	function edit_sub_auth_ethic_training($sub_auth_ethic_training){
		// セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['student_id']) &&  $edit_form_data['student_id'] <> ''){
			// セッションからデータ取得
			$data['student'] = $edit_form_data;
			
			// データ更新用引数再設定
			$data_param = array(
							'student_id'              => $data['student']['student_id'],
							'lawyer_number'           => $data['student']['lawyer_number'],
							'sub_auth_ethic_training' => $sub_auth_ethic_training,
						);

			// モデル読み込み
			$this->load->model('model_student');

			// 代替倫理研修権限の更新
			$result = $this->model_student->edit_sub_auth_ethic_training($data_param);

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_student/commit',
							'submenu_idx' => 3,
//							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('student');
			
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
	// 日弁連
	// 受講者、CSVファイルのダウンロード
	//----------------------------------------------
	private $header_student_list = array('登録番号', '氏名', 'メールアドレス', 'メールマガジン受け取り可否', 'パスポート有無', '代替倫理研修権限', '弁護士会名', 'パスポート有効期限');

	function csv_download($parameters = ''){
		set_time_limit(0);
		ini_set('max_execution_time', '3600');

		$before_url = $_SERVER['HTTP_REFERER'];
		$array_url  = preg_split("/[\?\&]{1}/", urldecode($before_url));
		
		// 受講者検索条件の取得
		$data_param = $this->session->userdata('student_search_cond') ?: array(
			's_school_id' => $this->libauth->get_school_id(),
			's_name' => '',
			's_lawyer_number'=>'',
			's_lawyer_division' => '',
			's_email' => '',
			's_bar_association' => '',
			's_free_word' => '',
			's_sub_auth_ethic_training_on' => 0,
			's_sub_auth_ethic_training_off' => 0,
			'order_by' => '',
		);
		//+++++++++++++++++++++++++++++++++++++++
		/*
		// csv出力（API使用）
		if(empty($_SERVER['HTTPS'])){
			$return_protocol = 'http://';
		}else{
			$return_protocol = 'https://';
		}
		
		$fileName = "report_".date("Ymd").".csv";
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$fileName");
		
		// === codeigniter =========================================================================
		$this->load->library('Curl');
		echo $this->curl->simple_post($return_protocol.$this->config->item('domain_name_api').'/csv_download', array(
			'type'			=> 'student_list',
			'csv_filename'		=> $fileName,
			'csv_where'		=> $data_param,
		));
		// === codeigniter =========================================================================
		exit();
		*/
		//+++++++++++++++++++++++++++++++++++++++

		$fileName = "report_".date("Ymd").".csv";
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		$csv_param = [
			'type'		=> 'student_list',
			'csv_filename'	=> $fileName,
			'csv_where'	=> $data_param,
		];
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 出力csvの種類
		$post_type = "";
		if( (isset($csv_param['type'])) && (!empty($csv_param['type'])) ) {
			$post_type = $csv_param['type'];
		}
		
		// 出力csvのファイル名
		$post_csv_filename = "";
		if( (isset($csv_param['csv_filename'])) && (!empty($csv_param['csv_filename'])) ) {
			$post_csv_filename = $csv_param['csv_filename'];
		}else{
			// マイクロ秒（msec）の取得　　例：[$msec = 0.67361700]  [$sec = 1378349457]
			list($msec, $sec) = explode(" ", microtime());
			// マイクロ秒×1000 →整数値の四捨五入
			$microsecond = round($msec * 1000);
			// 年月日時分秒_マイクロ秒（1000倍して整数部分四捨五入）.csv
			$post_csv_filename = date("YmdHis")."_".$microsecond.".csv";
		}
		
		// 出力csvの出力条件
		$post_csv_where = array();
		if( (isset($csv_param['csv_where'])) && (!empty($csv_param['csv_where'])) ) {
			$post_csv_where = $csv_param['csv_where'];
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		foreach ( $data_param as $key => $val ) {
			$post_csv_where[$key] = $val;
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 出力csvのデータ取得
		$csv_header = array();
		$table_data = array();
		$table_data = $this->_student_list($post_csv_where);
		$csv_header = $this->header_student_list;
		//var_dump($table_data);
		//exit();

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// csv出力
		ini_set('memory_limit', '2048M');
/*
		// header 設定
		$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$post_csv_filename");

		//header clum
		if( !empty($csv_header) ){
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_header);
			$csv_headClum = get_csv_format($csv_header, 'SJIS-WIN');
			print $csv_headClum."\r\n";
			
			ob_flush();
			flush();
		}

		// body clum
		foreach($table_data as $table_data_record){
			// 連想配列→配列変換
			$csv_data = array();
			foreach ($table_data_record as $key => $value) {
			  //array_push($csv_data, $value);
				$csv_data[] = $value;
			}
			// 配列に変換
			$csv_data = array_values($table_data_record);
			// 文字コード変換
			mb_convert_variables('SJIS-WIN', 'UTF-8', $csv_data);

			$csv = get_csv_format($csv_data, 'SJIS-WIN');
			print $csv."\r\n";
			
			ob_flush();
			flush();
		}
*/
		// メモリ使用量を減らすため、出力バッファを無効化
		if (ob_get_level() > 0) {
		    ob_end_clean();
		}

		// CSVのヘッダーを設定
		$post_csv_filename = mb_convert_encoding($post_csv_filename, 'SJIS-WIN');

		header('Content-Type: application/csv; charset=Shift_JIS');
		header("Content-Disposition: attachment; filename=\"$post_csv_filename\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		// 出力ストリームを開く（php://output を使用）
		$fp = fopen('php://output', 'w');

		// ヘッダー行を書き込む
		if (!empty($csv_header)) {
		    mb_convert_variables('SJIS-WIN', 'UTF-8', $csv_header);
		    fputcsv($fp, $csv_header);
		    fflush($fp); // 出力をフラッシュ
		}

		$offset = 0;
		while( !empty($table_data) && $offset<100000){
			$table_data = $this->_student_list($post_csv_where, 1000, $offset);
			// データ行を書き込む
			foreach ($table_data as $table_data_record) {
			    // 連想配列 → 配列
			    $csv_data = array_values($table_data_record);
			    mb_convert_variables('SJIS-WIN', 'UTF-8', $csv_data);

			    fputcsv($fp, $csv_data);
			    fflush($fp); // 出力をフラッシュ
			}
			$offset += 1000;
		}

		// ストリームを閉じる
		fclose($fp);
		exit;

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------



/*
		// 遷移元のURL取得（受講者の検索条件を取得するため）
		// http://cms.nichibenren-stg.alfredcore.net/cms_student?order_by=0&s_name=%E9%AB%98%E6%B8%88%E3%80%80%E5%A4%A7%E6%A8%B9%EF%BC%91&s_lawyer_number=1234567890&s_email=takasumi&s_bar_association=5003&s_free_word=%E9%AB%98%E6%B8%88&s_sub_auth_ethic_training_off=1&x=35&y=8
		// array(10) { [0]=> string(53) "http://cms.nichibenren-stg.alfredcore.net/cms_student" [1]=> string(10) "order_by=0" [2]=> string(25) "s_name=高済　大樹１" [3]=> string(26) "s_lawyer_number=1234567890" [4]=> string(16) "s_email=takasumi" [5]=> string(22) "s_bar_association=5003" [6]=> string(18) "s_free_word=高済" [7]=> string(31) "s_sub_auth_ethic_training_off=1" [8]=> string(4) "x=32" [9]=> string(3) "y=9" } 
		$before_url = $_SERVER['HTTP_REFERER'];
		$array_url  = preg_split("/[\?\&]{1}/", urldecode($before_url));
		
		// 受講者検索条件の取得
		$data['s_name']							= '';
		$data['s_lawyer_number']				= '';
		$data['s_lawyer_division']				= 0;
		$data['s_email']						= '';
		$data['s_bar_association']				= '';
		$data['s_free_word']					= '';
		$data['s_sub_auth_ethic_training_on']	= '';
		$data['s_sub_auth_ethic_training_off']	= '';
		$data['order_by']						= 0;

		foreach ($array_url as $val_url) {
		//	$match = NULL;
			if(preg_match('/s_name=(.+)/', $val_url, $match))                        $data['s_name']                        = $match[1];
			if(preg_match('/s_lawyer_number=(.+)/', $val_url, $match))               $data['s_lawyer_number']               = $match[1];
			if(preg_match('/s_lawyer_division=(.+)/', $val_url, $match))             $data['s_lawyer_division']             = $match[1];
			if(preg_match('/s_email=(.+)/', $val_url, $match))                       $data['s_email']                       = $match[1];
			if(preg_match('/s_bar_association=(.+)/', $val_url, $match))             $data['s_bar_association']             = $match[1];
			if(preg_match('/s_free_word=(.+)/', $val_url, $match))                   $data['s_free_word']                   = $match[1];
			if(preg_match('/s_sub_auth_ethic_training_on=(.+)/', $val_url, $match))  $data['s_sub_auth_ethic_training_on']  = $match[1];
			if(preg_match('/s_sub_auth_ethic_training_off=(.+)/', $val_url, $match)) $data['s_sub_auth_ethic_training_off'] = $match[1];
			if(preg_match('/order_by=(.+)/', $val_url, $match))                      $data['order_by']                      = $match[1];
		} 
	  //print var_dump($data);
		
		// 受講者データの取得
		$data_param = array(
						's_school_id'                   => $this->libauth->get_school_id(),
						's_name'                        => $data['s_name'],
						's_lawyer_number'               => $data['s_lawyer_number'],
						's_lawyer_division'             => $data['s_lawyer_division'],
						's_email'                       => $data['s_email'],
						's_bar_association'             => $data['s_bar_association'],
						's_free_word'                   => $data['s_free_word'],
						's_sub_auth_ethic_training_on'  => $data['s_sub_auth_ethic_training_on'],
						's_sub_auth_ethic_training_off' => $data['s_sub_auth_ethic_training_off'],
						'order_by'                      => $data['order_by'],
					);
		
		// csv出力（API使用）
		if(empty($_SERVER['HTTPS'])){
			$return_protocol = 'http://';
		}else{
			$return_protocol = 'https://';
		}
		
		$fileName = "report_".date("Ymd").".csv";
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$fileName");
		
		// === codeigniter =========================================================================
		$this->load->library('Curl');
		echo $this->curl->simple_post($return_protocol.$this->config->item('domain_name_api').'/csv_download', array(
			'type'			=> 'student_list',
			'csv_filename'	=> $fileName,
			'csv_where'		=> $data_param,
		));
		// === codeigniter =========================================================================
*/		
		//=== php ==================================================================================
		/*
		$url = $return_protocol.$this->config->item('domain_name_api').'/csv_download';
		$data = array(
			'type'			=> 'student_list',
			'csv_filename'	=> $fileName,
			'csv_where'		=> $data_param,
		);
		$options = array('http' => array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded',
			'content' => http_build_query($data),
		));
		echo file_get_contents($url, false, stream_context_create($options));
		*/
		//=== php ==================================================================================
	}

	// ========== ========== ========== ========== ========== ========== ========== ==========
	// 受講者（受講者検索）
	// ========== ========== ========== ========== ========== ========== ========== ==========
	private function _student_list($param, $limit=1000, $offset=0){
		//引数設定
		$param = array_merge(
						array(
							's_school_id'                   => 1,
							's_name'                        => '',
							's_lawyer_number'               => '',
							's_lawyer_division'             => 0,
							's_email'                       => '',
							's_bar_association'             => '',
							's_free_word'                   => '',
							's_sub_auth_ethic_training_on'  => '',
							's_sub_auth_ethic_training_off' => '',
							'order_by'                      => 0,
						),
						$param
					);

		//SQL生成
		// '登録番号', '氏名', 'メールアドレス', 'メールマガジン受け取り可否', 'パスポート有無', '代替倫理研修権限', '弁護士会名'　　mailmagazine_flg 
		$sql_select  = " SELECT ";
		$sql_select .= "  student.lawyer_number ";		// 登録番号（弁護士番号）
		$sql_select .= ", student.student_name ";		// 氏名
		$sql_select .= ", student.student_email ";		// メールアドレス
		$sql_select .= ", (CASE WHEN student.mailmagazine_flg = '1'        THEN '○'   ELSE '－' END) AS mailmagazine_flg";		// メルマガ受取可否（0:メルマガ拒否 1:メルマガ許可）
		$sql_select .= ", (CASE WHEN student.presence_passport = '1'       THEN '○'   ELSE '－' END) AS presence_passport";							// パスポート有無（有無から○－に変更）
		$sql_select .= ", (CASE WHEN student.sub_auth_ethic_training = '1' THEN '許可' ELSE '禁止' END) AS sub_auth_ethic_training";					// 代替権限
		$sql_select .= ", (CASE WHEN mtb_bar_association.name IS NULL      THEN ''     ELSE mtb_bar_association.name END) AS bar_association_name";		// 弁護士会名
		$sql_select .= ", (CASE WHEN student.presence_passport = '1' THEN student.exp_date_passport ELSE '' END) AS exp_date_passport";		// パスポート有効期限
		$sql_select .= " FROM student LEFT JOIN mtb_bar_association ON student.bar_association_id = mtb_bar_association.id ";
		
		$sql_where   = " WHERE student.status = 0 ";
		
		$sql_order   = " ORDER BY student.lawyer_number ASC ";
		if($param['order_by'] != 0){
			$sql_order = " ORDER BY student.lawyer_number DESC ";
		}
		
		//学校ID
		$sql_where .= " AND student.school_id = {$this->db->escape($param['s_school_id'])}";
		
		//受講者氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND student.student_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		
		// 登録番号
		if (isset($param['s_lawyer_number']) && $param['s_lawyer_number'] != '' ) {
			$sql_where .= " AND student.lawyer_number = {$this->db->escape($param['s_lawyer_number'])}";
		}

		// 会員区分
		// 0：条件に入れない。1～5：条件検索、999：1～5以外
		if (isset($param['s_lawyer_division']) && $param['s_lawyer_division'] > 0 ) {
			if($param['s_lawyer_division'] == 999){
				$sql_where .= " AND student.lawyer_division NOT IN ( 1, 2, 3, 4, 5 ) ";
			}else{
				$sql_where .= " AND student.lawyer_division = {$this->db->escape($param['s_lawyer_division'])}";
			}
		}
		
		//メールアドレス
		if (isset($param['s_email']) && $param['s_email'] != '') {
			$sql_where .= " AND (student.student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
								OR student.student_email_mobile LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
			)";
		}
		
		// 弁護士会ID（所属弁護士会）
		if (isset($param['s_bar_association']) && $param['s_bar_association'] != '') {
			$sql_where .= " AND student.bar_association_id = {$this->db->escape($param['s_bar_association'])}";
		}
		
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND   (student.student_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_name_kana LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address3 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address_overseas1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address_overseas2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.school_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_no LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		// 代替権限
		if (isset($param['s_sub_auth_ethic_training_on']) && $param['s_sub_auth_ethic_training_on'] == '1') {
			$sql_where .= " AND student.sub_auth_ethic_training = 1 ";
		}
		if (isset($param['s_sub_auth_ethic_training_off']) && $param['s_sub_auth_ethic_training_off'] == '1') {
			$sql_where .= " AND student.sub_auth_ethic_training = 0 ";
		}
		
		// SQL-SELECT文実行
		//var_dump($sql_select.$sql_where.$sql_order);
		$add_limit = " LIMIT ".$limit." OFFSET ".$offset." ";
		$query = $this->db->query($sql_select.$sql_where.$sql_order.$add_limit);
		
		// データリターン
		if( $query->num_rows() > 0 ){
			return $query->result_array();
		}else{
			return array();
		}
	}


/*
	//----------------------------------------------
	// 日弁連
	// 受講者、CSVファイルのダウンロード
	//----------------------------------------------
	function csv_download(){
		$before_url = $_SERVER["HTTP_REFERER"];
		$array_url  = preg_split("/[\?\&]{1}/", urldecode($before_url));
		
		// 受講者検索条件の取得
		$data['s_name']							= '';
		$data['s_lawyer_number']				= '';
		$data['s_email']						= '';
		$data['s_bar_association']				= '';
		$data['s_free_word']					= '';
		$data['s_sub_auth_ethic_training_on']	= '';
		$data['s_sub_auth_ethic_training_off']	= '';
		$data['order_by']						= 0;
		
		foreach ($array_url as $val_url) {
		//	$match = NULL;
			if(preg_match('/s_name=(.+)/', $val_url, $match))                        $data['s_name']                        = $match[1];
			if(preg_match('/s_lawyer_number=(.+)/', $val_url, $match))               $data['s_lawyer_number']               = $match[1];
			if(preg_match('/s_email=(.+)/', $val_url, $match))                       $data['s_email']                       = $match[1];
			if(preg_match('/s_bar_association=(.+)/', $val_url, $match))             $data['s_bar_association']             = $match[1];
			if(preg_match('/s_free_word=(.+)/', $val_url, $match))                   $data['s_free_word']                   = $match[1];
			if(preg_match('/s_sub_auth_ethic_training_on=(.+)/', $val_url, $match))  $data['s_sub_auth_ethic_training_on']  = $match[1];
			if(preg_match('/s_sub_auth_ethic_training_off=(.+)/', $val_url, $match)) $data['s_sub_auth_ethic_training_off'] = $match[1];
			if(preg_match('/order_by=(.+)/', $val_url, $match))                      $data['order_by']                      = $match[1];
		} 
	  //print var_dump($data);
		
		// 受講者データの取得
		$data_param = array(
						's_school_id'                   => $this->libauth->get_school_id(),
						's_name'                        => $data['s_name'],
						's_lawyer_number'               => $data['s_lawyer_number'],
						's_email'                       => $data['s_email'],
						's_bar_association'             => $data['s_bar_association'],
						's_free_word'                   => $data['s_free_word'],
						's_sub_auth_ethic_training_on'  => $data['s_sub_auth_ethic_training_on'],
						's_sub_auth_ethic_training_off' => $data['s_sub_auth_ethic_training_off'],
						'order_by'                      => $data['order_by'],
					);
		
		// 受講者モデル読み込み
		$this->load->model('model_student');
		// データ取得
		$student_list = $this->model_student->csv_get_student_search_list($data_param);
		
		// csv出力
		$fileName = "report_".date("Ymd").".csv";
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$fileName");
		
		//header clum
		$_headClum = array(
			$this->lang->line_or_def('common_'   , '登録番号'),
			$this->lang->line_or_def('common_'   , '氏名'),
			$this->lang->line_or_def('common_'   , 'メールアドレス'),
			$this->lang->line_or_def('common_'   , 'パスポート有無'),
			$this->lang->line_or_def('common_'   , '代替倫理研修権限'),
			$this->lang->line_or_def('common_'   , '弁護士会名'),
		);
		mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $_headClum);
		$csv_headClum = get_csv_format($_headClum, 'SJIS-WIN');
		print $csv_headClum."\r\n";
		
		ob_flush();  
		flush();  

		// body clum
	  //for($a = 0; $a < 100000; $a++) {
		foreach($student_list as $student_val){
			$csv_data = array(
				$student_val['lawyer_number'],
				$student_val['student_name'],
				$student_val['student_email'],
				$student_val['presence_passport'],
				$student_val['sub_auth_ethic_training'],
				$student_val['bar_association_name'],
			);
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_data);
			$csv = get_csv_format($csv_data, 'SJIS-WIN');
			print $csv."\r\n";
			
			ob_flush();  
			flush();  
		}
		  //}
	}
*/

} 

/*End of File program.php*/
