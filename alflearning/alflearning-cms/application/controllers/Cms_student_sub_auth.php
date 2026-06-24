<?php
#[AllowDynamicProperties]
class Cms_student_sub_auth extends CI_Controller {
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
		
//		//ページネーションライブラリのロードとオフセット取得
//		$this->load->library('pagination');
//		$per_page = $this->config->item('pagination_per_page');
		$per_page = 0;
		
		//検証ルールの設定
		$this->form_validation->set_rules('s_name'            , $this->lang->line_or_def('common_name','名前')                                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_lawyer_number'   , $this->lang->line_or_def('common_','登録番号')                                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_email'           , $this->lang->line_or_def('common_mail_address','メールアドレス')                , 'trim|xss_clean');
		$this->form_validation->set_rules('s_bar_association' , $this->lang->line_or_def('common_','所属弁護士会')                              , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'       , $this->lang->line_or_def('common_freeword','フリーワード')                      , 'trim|xss_clean');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_on'       , $this->lang->line_or_def('common_','代替権限あり')             , 'trim|xss_clean');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_off'      , $this->lang->line_or_def('common_','代替権限なし')             , 'trim|xss_clean');
		$this->form_validation->set_rules('order_by'          , $this->lang->line_or_def('common_order_by','並び順')                            , 'trim|xss_clean');
		/*
		$this->form_validation->set_rules('s_id'             , $this->lang->line_or_def('common_id','ID')                                      , 'trim|xss_clean');
		$this->form_validation->set_rules('s_cource'         , $this->lang->line_or_def('common_course_name','講座名')                         , 'trim|xss_clean');
		$this->form_validation->set_rules('s_birthday_start' , $this->lang->line_or_def('common_date_of_birth_range_start','生年月日範囲開始') , 'trim|xss_clean');
		$this->form_validation->set_rules('s_birthday_end'   , $this->lang->line_or_def('common_date_of_birth_range_end','生年月日範囲終了')   , 'trim|xss_clean');
		$this->form_validation->set_rules('s_student_group'  , $this->lang->line_or_def('common_group','グループ')                             , 'trim|xss_clean');
		
		$this->form_validation->set_rules('s_bar_association'  , $this->lang->line_or_def('common_','所属弁護士会')                             , 'trim|xss_clean');
		$this->form_validation->set_rules('s_lawyer_number'    , $this->lang->line_or_def('common_','弁護士番号')                             , 'trim|xss_clean');
		*/
		$this->form_validation->run();		//バリデーション実行（その実xss対策）
		
		//受講者モデル読み込み
		$this->load->model('model_student_sub_auth');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('student_sub_auth_search_cond') ?: array(
				's_name' => '',
				's_lawyer_number'=>'',
				's_email' => '',
				's_bar_association' => '',
				's_free_word' => '',
				's_sub_auth_ethic_training_on' => '',
				's_sub_auth_ethic_training_off' => '',
			);
		} else {
			//データ取得用引数設定
			$data['s_name']				= ($this->input->post('s_name') ?? '');
			$data['s_lawyer_number']		= ($this->input->post('s_lawyer_number') ?? '');
			$data['s_email']			= ($this->input->post('s_email') ?? '');
			$data['s_bar_association']		= ($this->input->post('s_bar_association') ?? '');
			$data['s_free_word']			= ($this->input->post('s_free_word') ?? '');
			$data['s_sub_auth_ethic_training_on']	= ($this->input->post('s_sub_auth_ethic_training_on') ?? 0);
			$data['s_sub_auth_ethic_training_off']	= ($this->input->post('s_sub_auth_ethic_training_off') ?? 0);
			$this->session->set_userdata('student_sub_auth_search_cond', $data);
		}

		// 初期表示時のリスト非表示対応
		$first_show_lawyer_number = $data['s_lawyer_number'];
		if( 
			    ($data['s_name']===NULL)
			 && ($data['s_lawyer_number']===NULL)
			 && ($data['s_email']===NULL)
			 && ($data['s_bar_association']===NULL)
			 && ($data['s_free_word']===NULL)
			 && ($data['s_sub_auth_ethic_training_on']===NULL)
			 && ($data['s_sub_auth_ethic_training_off']===NULL) 
		){
			$first_show_lawyer_number = -1;
		}

		// ID昇順降順の設定（getになければゼロ固定）
		if($this->input->get('order_by')){
			$data['order_by']  = strip_tags($this->input->get('order_by', true) ?? '');
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
						's_email'            => $data['s_email'],
						's_bar_association'  => $data['s_bar_association'],
						's_free_word'        => $data['s_free_word'],
						's_sub_auth_ethic_training_on'   => $data['s_sub_auth_ethic_training_on'],
						's_sub_auth_ethic_training_off'  => $data['s_sub_auth_ethic_training_off'],
						'offset'                         => $offset,
						'rowcount'                       => $per_page,
						'order_by'                       => $data['order_by'],

/*						's_school_id'      => $this->libauth->get_school_id(),
						's_name'           => $data['s_name'],
						's_email'          => $data['s_email'],
						's_student_id'     => $data['s_id'],
						's_cource'         => $data['s_cource'],
						's_birthday_start' => $data['s_birthday_start'],
						's_birthday_end'   => $data['s_birthday_end'],
						's_free_word'      => $data['s_free_word'],
						's_student_group'  => $data['s_student_group'],
						'offset'           => $offset,
						'rowcount'         => $per_page,
						
						'order_by'          => $data['order_by'],
						's_bar_association' => $data['s_bar_association'],
						's_lawyer_number'   => $data['s_lawyer_number'],	*/
					);
		//データ取得
		$student_list = $this->model_student_sub_auth->get_student_search_list($data_param);
		
		$data['student_list'] = $student_list;
		
//		$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1/'.$data['order_by'].'$2', $data['pagination']);
		$data['total_rows'] = is_array($data['student_list']) ? count($data['student_list']) : 0;
		$data['pagination'] = '';
		
		$data['total_rows'] = $this->model_student_sub_auth->get_student_search_count($data_param);
		
		// 初期表示時のみ、代替権限ありを初期選択
		// 前のほうで入れないのは検索条件に使用されるため。
		if($first_show_lawyer_number == -1) $data['s_sub_auth_ethic_training_on'] = 1;

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student_sub_auth/index',
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
	function detail($student_id, $result_data=[]){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_student');
		
		//データ取得用引数設定
		$data_param = array(
						'student_id' => $student_id,
					);
		//データ取得
		$db_data = $this->model_student->get_student($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['student']['update_flg']             = 1;
			$data['student']['student_id']             = $db_data['student_id'];
			$data['student']['student_name']           = $db_data['student_name'];
			$data['student']['student_email']          = $db_data['student_email'];
			$data['student']['student_password']       = '';
			$data['student']['student_password_check'] = '';
			$data['student']['student_password_change']   = 0;
			$data['student']['student_password_identity'] = 0;
			$data['student']['student_birthday']       = $db_data['student_birthday'];
			$data['student']['student_lectures']       = array();
			$data['student']['student_note']           = $db_data['student_note'];
			
			if( array_key_exists('elm_stat', $result_data) ){
				$data['elm_result']  = $result_data['elm_result'];
				$data['elm_stat']    = $result_data['elm_stat'];
				$data['elm_message'] = $result_data['elm_message'];
			}
			
			//受講講座取得
			$lectures = $this->model_student->get_student_lectures($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['student']['student_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			
			//受講講座名設定
			$data['student']['student_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['student']['student_lectures']){
				foreach($data['student']['student_lectures'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				asort($data['student']['student_lectures_name']);    // [2012/08/20]
			}
			
			// 受講者単位の視聴履歴を取得
			$this->load->model('model_video');
			$data['history_data'] = $this->model_video->get_video_reading_history(array(
				'student_id'	=> $db_data['student_id'],
				'video_id'		=> 0,
			));


			$data['student']['lawyer_number']               = $db_data['lawyer_number'];			// 弁護士番号
			$data['student']['bar_association_id']         = $db_data['bar_association_id'];		// 弁護士会ID
			$data['student']['regist_date']               = $db_data['regist_date'];			// 登録年
			$data['student']['target_passport']         = $db_data['target_passport'];		// 対象パスポート
			$data['student']['presence_passport']           = $db_data['presence_passport'];			// パスポートの有無
		//	$data['student']['mailmagazine_flg']            = $db_data['mailmagazine_flg'];			// メールマガジンの可否
			$data['student']['ethic_training']              = $db_data['ethic_training'];			// 倫理研修
			$data['student']['sub_auth_ethic_training']              = $db_data['sub_auth_ethic_training'];			// 代替倫理研修権限

			
			//** 法学館対応 **//
			$data['student']['regist_at']                   = $db_data['regist_at'];			// 入会日時
			$data['student']['delete_at']                   = $db_data['delete_at'];			// 退会日時
			$data['student']['student_name_kana']           = $db_data['student_name_kana'];	// 生徒氏名カナ
			$data['student']['sex']                         = $db_data['sex'];					// 性別 1:男性 2:女性（mtb_gender）
			$data['student']['country_type']                = $db_data['country_type'];			// 国種別 1:国内 2:国外（初期値1）mtb_country_type
			$data['student']['zip']                         = $db_data['zip'];					// 郵便番号
			$data['student']['pref']                        = $db_data['pref'];					// 都道府県ID（mtb_pref テーブル連動）
			$data['student']['address1']                    = $db_data['address1'];				// 市区町村
			$data['student']['address2']                    = $db_data['address2'];				// 番地
			$data['student']['address3']                    = $db_data['address3'];				// 建物名
			$data['student']['zip_overseas']                = $db_data['zip_overseas'];			// 海外郵便番号
			$data['student']['address_overseas1']           = $db_data['address_overseas1'];	// 海外住所・住所1
			$data['student']['address_overseas2']           = $db_data['address_overseas2'];	// 海外住所・住所2
			$data['student']['member_type']                 = $db_data['member_type'];			// 会員属性 1:通常 2:月額課金（初期値1）
			$data['student']['tel1']                        = $db_data['tel1'];					// 電話番号1
			$data['student']['tel2']                        = $db_data['tel2'];					// 電話番号2
			$data['student']['tel3']                        = $db_data['tel3'];					// 電話番号3
			$data['student']['fax1']                        = $db_data['fax1'];					// FAX番号1
			$data['student']['fax2']                        = $db_data['fax2'];					// FAX番号2
			$data['student']['fax3']                        = $db_data['fax3'];					// FAX番号3
			$data['student']['student_email_mobile']        = $db_data['student_email_mobile'];	// 携帯メールアドレス
			$data['student']['job']                         = $db_data['job'];					// 職業ID（mtb_job テーブル連動）
			$data['student']['job_type']                    = $db_data['job_type'];				// 業種ID（mtb_job_type テーブル連動）
			$data['student']['school_name']                 = $db_data['school_name'];			// 学校名
			$data['student']['school_grade']                = $db_data['school_grade'];			// 学年（mtb_school_grade テーブル連動）
			$data['student']['password_question']           = $db_data['password_question'];	// パスワード確認質問（mtb_password_question テーブル連動）
			$data['student']['password_answer']             = '';								// パスワード確認回答	//$db_data['password_answer'];
				$data['student']['password_answer_change']  = 0;								//   ※パスワード確認回答入力フラグ（1：入力必須、0:入力選択可能）
				$data['student']['old_password_question']   = $db_data['password_question'];	//   ※パスワード確認質問（変更前情報を格納）
			$data['student']['mailmagazine_flg']            = $db_data['mailmagazine_flg'];		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
			$data['student']['mailmagazine_ids']            = $db_data['mailmagazine_ids'];		// メルマガID(複数時はカンマ区切りで入れる)
				$data['student']['mailmagazine_ids_array']  = explode(',',$data['student']['mailmagazine_ids']);	//   ※メルマガID（配列）
			$data['student']['media_id']                    = $db_data['media_id'];				// 認知媒体ID（mtb_media）
				$data['student']['media_id_array']          = explode(',',$data['student']['media_id']);	//   ※認知媒体ID（配列）
			$data['student']['age']                         = $db_data['age'];					// 年代（mtb_age）
			$data['student']['student_no']                  = $db_data['student_no'];			// 伊藤塾塾生番号
			$data['student']['user_last_update_date']       = $db_data['user_last_update_date'];		// 最終更新日

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
		$this->form_validation->set_rules('update_flg'            , $this->lang->line_or_def('common_flg','flg')                              , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('student_id'            , $this->lang->line_or_def('common_id','ID')                                , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('student_name'          , $this->lang->line_or_def('common_name','名前')                            , 'trim|xss_clean|required|callback_name_check');
		$this->form_validation->set_rules('student_email'         , $this->lang->line_or_def('common_mail_address','メールアドレス')          , 'trim|xss_clean|required|valid_email');
//		$this->form_validation->set_rules('student_password'      , $this->lang->line_or_def('common_password','パスワード')                  , 'trim|xss_clean|required');
//		$this->form_validation->set_rules('student_password_check', $this->lang->line_or_def('common_password_conf','パスワード（確認入力）') , 'trim|xss_clean|required|matches[student_password]');
		if( ($this->input->post('update_flg') == 0) || (($this->input->post('update_flg') != 0) && ($this->input->post('student_password_change') == 1)) ){
			$this->form_validation->set_rules('student_password'        , $this->lang->line_or_def('common_password','パスワード')                     , 'trim|xss_clean|required');
			$this->form_validation->set_rules('student_password_check'  , $this->lang->line_or_def('common_password_conf','パスワード（確認入力）')    , 'trim|xss_clean|required|matches[student_password]');
		}
	//	$this->form_validation->set_rules('student_birthday'      , $this->lang->line_or_def('common_date_of_birth','生年月日')               , 'trim|xss_clean|required|callback_date_check');
		$this->form_validation->set_rules('student_lectures'      , $this->lang->line_or_def('common_attendance_class','受講講座')            , 'required');
		$this->form_validation->set_rules('student_note'          , $this->lang->line_or_def('common_note','備考')                            , 'trim|xss_clean');
		
		// 法学館対応
		// パスワード確認変更がある場合の処理（新規登録、変更でパスワード確認変更を行う場合）
		if( ($this->input->post('update_flg') == 0) || (($this->input->post('update_flg') != 0) && ($this->input->post('password_answer_change') == 1)) ){
			$this->form_validation->set_rules('password_answer'  , $this->lang->line_or_def('password_answer','パスワード確認回答')    , 'trim|xss_clean|required');
		}
		$this->form_validation->set_rules('student_email_mobile'  , $this->lang->line_or_def('common_hogaku','携帯メールアドレス')          , 'trim|xss_clean|valid_email');
		
		
		
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
		
		$param['view_data']['mtb_ethic_training']          = $this->get_mtb_list('mtb_ethic_training');			// 非テーブル
		$param['view_data']['mtb_sub_auth_ethic_training'] = $this->get_mtb_list('mtb_sub_auth_ethic_training');	// 非テーブル
		$param['view_data']['mtb_bar_association']         = $this->get_mtb_list('mtb_bar_association');
		
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

		// 倫理研修（日弁連対応）
		if($table_name == 'mtb_ethic_training'){
			$data[0] = "禁止";
			$data[1] = "許可";
			return $data;
		}
		// 代替倫理研修権限（日弁連対応）
		if($table_name == 'mtb_sub_auth_ethic_training'){
			$data[0] = "なし";
			$data[1] = "あり";
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




	//----------------------------------------------
	// 日弁連
	// [Ajax用] 代替倫理研修権限 の一括変更
	//----------------------------------------------
	function change_sub_auth(){
		
		$this->load->helper('json');
		
		$drop_param = array(
					"school_id"			=>	$this->libauth->get_school_id(),
					"select_student_id"	=>	$this->input->post('select_student_id'),
					"change_kinds"		=>	$this->input->post('change_kinds'),
					);
		
		// 学校ID・受講者IDに一致するグループ名・受講者ID群を取得
		$this->load->model('model_student_sub_auth');
		$output['result'] = $this->model_student_sub_auth->change_sub_auth($drop_param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($output));
	}

	//----------------------------------------------
	// 日弁連
	// 代替倫理研修権限csvアップ 画面表示
	//----------------------------------------------
	function index_upload($offset=0){
		
		$data = array();
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_student_sub_auth/index_upload',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	// 日弁連
	// 代替倫理研修権限csvアップ 更新処理
	// 
	//----------------------------------------------
	function csv_change_sub_auth(){
		
		$this->load->helper('json');
		
		// 権限付与 or 権限剥奪
		$change_kinds = 'ON';
		if($_POST['status_flag']!='ON') $change_kinds = 'OFF';
		
		// 更新対象の登録番号をcsvから取得
		$fp = fopen($_FILES['uploadfile']['tmp_name'], 'r');
		$select_lawyer_number = '0';
		while(!feof($fp)){
			$row = array();
			$aRecord = $this->fgetcsv($fp, filesize($_FILES['uploadfile']['tmp_name']),",");
			if(is_array($aRecord) && isset($aRecord[0]) && !is_numeric($aRecord[0])){	//title行
				continue;
			}else{
				if( is_array($aRecord) && isset($aRecord[0]) && is_numeric($aRecord[0]) ){
					$select_lawyer_number .= "-".$aRecord[0];
				}
			}
		}
		fclose($fp);
		
		// 登録番号を元に権限更新
		$param = array(
					"school_id"				=>	$this->libauth->get_school_id(),
					"select_lawyer_number"	=>	$select_lawyer_number,
					"change_kinds"			=>	$change_kinds,
					);
		
		$this->load->model('model_student_sub_auth');
		$output = $this->model_student_sub_auth->csv_change_sub_auth($param);
		
		$this->output->set_header("HTTP/1.0 200 OK");
	  //$this->output->set_content_type('Content-type: text/html; charset=utf-8');			// Content-type を指定した場合
	  //$this->output->set_content_type('Content-type: application/json; charset=utf-8');	// IE等ではファイルダウンロードとなるため
	  //$this->output->set_content_type('Content-type: text/plain; charset=utf-8');			// いれていない
		$this->output->set_output(json_encode($output));
	}

	//----------------------------------------------
	// 日弁連
	// ファイルから1行取得＋カンマ区切からの配列変換
	//----------------------------------------------
	function fgetcsv (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$eof = false;
		while ($eof != true) {
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
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


} 

/*End of File program.php*/
