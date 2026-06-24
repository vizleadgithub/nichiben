<?php
ini_set('display_errors', 0);
#[AllowDynamicProperties]
class Cms_report extends CI_Controller {
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
			if($work_auth['report'] == 0){
				redirect('admin_top');
			}

			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}

		//school session update
		$_sessionSchool = $this->session->userdata('cms_master.login.school');
		$this->load->model('model_auth');
		$query = $this->model_auth->get_school($_sessionSchool['school_id']);
		$row = $query->row_array();
		$this->session->set_userdata(array('cms_master.login.school'=>$row));

		$this->load->helper('unit_helper');
		$this->load->library('LibAmount');
		$this->load->model('modelschoolcontract');
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	function index(){
	//	redirect('/cms_report/cms_class/');
	
		// [20131115] 日弁連以外のユーザ時は【レポート】-【商品】画面へリダイレクト
		if($this->libauth->get_bar_association_id() != 1){
			redirect('/alfproduct/report_product/');
			return;
		}else{
			$user_auths = $this->session->userdata;
			$contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]);
			if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ){
				redirect('/cms_report/cms_class/');
				return;
			}else{
				redirect('/cms_report/cms_video/');
				return;
			}
		}
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	function cms_class($selectMonth = false){

		// 生授業契約がない場合、トップページへリダイレクト
		$user_auths = $this->session->userdata;
		$contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]);
		if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ){
		}else{
			redirect('admin_top');
			return;
		}

		//年月選択済み
		if(!$selectMonth){
			redirect('/cms_report/cms_class/'.date("Y-m").'/');
			return;
		}

		//年月一覧
		$query = $this->db->query(
			' SELECT SUBSTRING(date,1,7) as d FROM report_live'.
			' WHERE report_live.school_id = ? '.
			' GROUP BY d',
			array(
				$this->libauth->get_school_id(),
			)
		);

		$monthList = array();
		foreach($query->result_array() as $row){
			array_push($monthList, $row['d']);
		}

		//report
		$reports = $this->libamount->getClassHistoryReport(array(
			'school_id'	=> $this->libauth->get_school_id(),
			'startdate'	=> date('Y/m/1', strtotime($selectMonth.'-1')),
			'enddate'	=> date("Y/m/t", strtotime($selectMonth.'-1')),
		));

		//選択が今月だった場合の今日以降の授業
		$thisMonth = false;
		if($selectMonth == date('Y-m')){
			$contractParam = $this->modelschoolcontract->getContractParam(array('serviceKey' => 'live'));

			$thisMonthClassReserved = $this->libamount->getClassTimeAmountReserved(array(
				'school_id'	=> $this->libauth->get_school_id(),
			));
			$thisMonth = array(
				'classList'	=> $thisMonthClassReserved['classList'],
				'total'	=> array(
					'time'			=> $contractParam['time_now'],
					'strage'		=> $contractParam['strage_now'],
					'updated_at'	=> $contractParam['updated_at'],
				),
			);
		}

		$this->load->view('cms_report/class', array(
			'selectMonth'	=> $selectMonth,
			'monthList'		=> $monthList,
			'reports'		=> $reports,
			'thisMonth'		=> $thisMonth,
		));
	}

	function cms_video($selectMonth = false){
		// [20131115] 日弁連以外のユーザ時は【レポート】-【商品】画面へリダイレクト
		if($this->libauth->get_bar_association_id() != 1){
			redirect('/alfproduct/report_product/');
			return;
		}

		//年月選択済み
		if(!$selectMonth){
			redirect('/cms_report/cms_video/'.date("Y-m").'/');
			return;
		}

		//年月一覧
		$query = $this->db->query(
			' SELECT SUBSTRING(date,1,7) as d FROM report_video'.
			' WHERE report_video.school_id = ? '.
			' GROUP BY d',
			array(
				$this->libauth->get_school_id(),
			)
		);

		$monthList = array();
		foreach($query->result_array() as $row){
			array_push($monthList, $row['d']);
		}

		//report
		$query = $this->db->query(
			' SELECT * FROM report_video'.
			' WHERE report_video.school_id = ? '.
			' AND date LIKE ?'.
			' ORDER BY date ASC',
			array(
				$this->libauth->get_school_id(),
				"$selectMonth%",
			)
		);

		$reports = array();
		foreach($query->result_array() as $row){
			if(!isset($reports[$row['date']])){
				$reports[$row['date']] = array();
			}
			$reports[$row['date']][$row['type']] = $row['value'];
		}

		$this->load->view('cms_report/video', array(
			'selectMonth'	=> $selectMonth,
			'monthList'		=> $monthList,
			'reports'		=> $reports,
		));
	}

	function cms_book_library($selectMonth = false){
		//年月選択済み
		if(!$selectMonth){
			redirect('/cms_report/cms_book_library/'.date("Y-m").'/');
			return;
		}

		//年月一覧
		$query = $this->db->query(
			' SELECT SUBSTRING(date,1,7) as d FROM report_book_library'.
			' WHERE report_book_library.school_id = ? '.
			' GROUP BY d',
			array(
				$this->libauth->get_school_id(),
			)
		);

		$monthList = array();
		foreach($query->result_array() as $row){
			array_push($monthList, $row['d']);
		}

		//report
		$query = $this->db->query(
			' SELECT * FROM report_book_library'.
			' WHERE report_book_library.school_id = ? '.
			' AND date LIKE ?'.
			' ORDER BY date ASC',
			array(
				$this->libauth->get_school_id(),
				"$selectMonth%",
			)
		);

		$reports = array();
		foreach($query->result_array() as $row){
			if(!isset($reports[$row['date']])){
				$reports[$row['date']] = array();
			}
			$reports[$row['date']][$row['type']] = $row['value'];
		}

		$this->load->view('cms_report/book_library', array(
			'selectMonth'	=> $selectMonth,
			'monthList'		=> $monthList,
			'reports'		=> $reports,
		));
	}
	
	
	//----------------------------------------------
	// 日弁連対応  alfproduct
	//   レポート - ユーザ
	//----------------------------------------------
	function cms_user($offset=0){
		// load language
		$this->lang->load('common');

		$this->session->set_userdata('offset', $offset);	//後で戻ってくる時に使う
		
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');
		
		//検証ルールの設定
		$this->form_validation->set_rules('s_name'            , $this->lang->line_or_def('common_name','名前')                                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_lawyer_number'   , $this->lang->line_or_def('common_','登録番号')                                  , 'trim|xss_clean');
		$this->form_validation->set_rules('s_email'           , $this->lang->line_or_def('common_mail_address','メールアドレス')                , 'trim|xss_clean');
		$this->form_validation->set_rules('s_bar_association' , $this->lang->line_or_def('common_','所属弁護士会')                              , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word'       , $this->lang->line_or_def('common_freeword','フリーワード')                      , 'trim|xss_clean');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_on'       , $this->lang->line_or_def('common_','代替権限あり')             , 'trim|xss_clean');
		$this->form_validation->set_rules('s_sub_auth_ethic_training_off'      , $this->lang->line_or_def('common_','代替権限なし')             , 'trim|xss_clean');
		$this->form_validation->set_rules('order_by'          , $this->lang->line_or_def('common_order_by','並び順')                            , 'trim|xss_clean');
	  //$this->form_validation->set_rules('s_id'              , $this->lang->line_or_def('common_id','ID')                                      , 'trim|xss_clean');
	  //$this->form_validation->set_rules('s_cource'          , $this->lang->line_or_def('common_course_name','講座名')                         , 'trim|xss_clean');
	  //$this->form_validation->set_rules('s_birthday_start'  , $this->lang->line_or_def('common_date_of_birth_range_start','生年月日範囲開始') , 'trim|xss_clean');
	  //$this->form_validation->set_rules('s_birthday_end'    , $this->lang->line_or_def('common_date_of_birth_range_end','生年月日範囲終了')   , 'trim|xss_clean');
	  //$this->form_validation->set_rules('s_student_group'   , $this->lang->line_or_def('common_group','グループ')                             , 'trim|xss_clean');
		
		$this->form_validation->run();		//バリデーション実行（その実xss対策）
		
		//受講者モデル読み込み
		$this->load->model('model_student');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('report_student_search_cond') ?: array(
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
			$data['s_sub_auth_ethic_training_on']	= intval(strip_tags($this->input->post('s_sub_auth_ethic_training_on') ?? 0));
			$data['s_sub_auth_ethic_training_off']	= intval(strip_tags($this->input->post('s_sub_auth_ethic_training_off') ?? 0));
			$this->session->set_userdata('report_student_search_cond', $data);
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
			$data['order_by']  = strip_tags($this->input->get('order_by', TRUE) ?? 0);
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
		$config['base_url']   = base_url().'/cms_report/cms_user';
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



		$array_bar_association = array();
		$result_data = $this->model_student->get_mtb_list('mtb_bar_association');
		if($result_data){
			foreach($result_data as $row){
				$array_bar_association[$row['id']] = $row['name'];
			}
		}
		$data['mtb_bar_association']         = $array_bar_association;


		$this->load->view('cms_report/user', $data);
	}

	//----------------------------------------------
	// 日弁連対応  alfproduct
	//   レポート - ユーザ - 詳細
	//----------------------------------------------
	function cms_user_detail($student_id = 0, $select_detail = 0, $offset = 0){
		//セッションデータのクリア
	  //$this->session->unset_userdata('edit_form_data');

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
			$data['student']['bar_association_id']      = $db_data['bar_association_id'];			// 所属弁護士会ID
			$data['student']['regist_date']             = $db_data['regist_date'];					// 登録年月日
			$data['student']['exp_date_passport']       = $db_data['exp_date_passport'];			// パスポートの有効期限
			$data['student']['presence_passport']       = $db_data['presence_passport'];			// パスポートの有無 0:なし、1:あり
			$data['student']['target_passport']         = $db_data['target_passport'];				// 対象パスポート（パスポート料金）
			$data['student']['student_email']           = $db_data['student_email'];				// メールアドレス
			$data['student']['mailmagazine_flg']        = $db_data['mailmagazine_flg'];				// 0:メルマガ拒否 1:メルマガ許可（初期値0）
			$data['student']['sub_auth_ethic_training'] = $db_data['sub_auth_ethic_training'];		// 代替倫理研修権限 0:なし（禁止）、1:あり（許可）
			
			//セッションへDB取得データを書き込み
			//$this->session->set_userdata('edit_form_data',serialize($data['student']));
			
			// select_detail によりデータ取得model変更
			
			//モデル読み込み
			$this->load->model('model_report');

			//ページネーションライブラリのロードとオフセット取得
			$this->load->library('pagination');
			$per_page = $this->config->item('pagination_per_page');
			
			//データ取得用引数設定
			$data_param = array(
							'student_id'  => $student_id,
							'offset'      => $offset,
							'rowcount'    => $per_page,
						);
			
			$view_name = '';
			switch ($select_detail) {
				case 0:
					// [日弁連]レポート-ユーザ、eラーニング
					$view_name = 'cms_report/user_detail_elearning';
					
					$detail_data = $this->model_report->get_user_elearning($data_param);
					break;
				case 1:
					// [日弁連]レポート-ユーザ、ライブ実務
					$view_name = 'cms_report/user_detail_live_training';
					
					$detail_data = $this->model_report->get_user_live_training($data_param);
					break;
				case 2:
					// [日弁連]レポート-ユーザ、日弁連以外主催
					$view_name = 'cms_report/user_detail_nichibenren_except_host';
					
					$detail_data = $this->model_report->get_user_nichibenren_except_host($data_param);
					break;
				case 3:
					// [日弁連]レポート-ユーザ、倫理研修
					$view_name = 'cms_report/user_detail_ethic_training';
					
					$detail_data = $this->model_report->get_user_ethic_training($data_param);
					print("\n<!--[\n");
					var_dump($detail_data);
					print("\n]-->\n");
					break;
				case 4:
					// [日弁連]レポート-ユーザ、全て
					$view_name = 'cms_report/user_detail_all';
					
					$detail_data = $this->model_report->get_user_all($data_param);
					break;
				default:
					// その他
					$this->cms_user();
					exit();
					break;
			}
			$data['show_record'] = $detail_data['show_record'];
			$data['all_count']   = $detail_data['all_count'];
			
			//ページネーション設定
			$config['base_url']    = base_url().'/cms_report/cms_user_detail/'.$student_id.'/'.$select_detail;
			$config['total_rows']  = $data['all_count'];
			$config['per_page']    = $per_page;
			$config['first_link']  = '&lt;&lt;';
			$config['last_link']   = '&gt;&gt;';
			$config['uri_segment'] = 5; 
			$this->pagination->initialize($config); 
			$data['pagination'] =  $this->pagination->create_links();
			
			// ページング表示用
			$data['start_rows'] = $offset + 1;
			$data['end_rows']   = $data['start_rows'] + count($data['show_record']) - 1;
			$data['total_rows'] = $config['total_rows'];

			// 画面表示 ----------------------------------------------------------------------------
			$array_bar_association = array();
			$result_data = $this->model_student->get_mtb_list('mtb_bar_association');
			if($result_data){
				foreach($result_data as $row){
					$array_bar_association[$row['id']] = $row['name'];
				}
			}
			$data['mtb_bar_association'] = $array_bar_association;
			
			$this->load->view($view_name, $data);
		}else{
			//データ無し時
			//一覧に戻る
			$this->cms_user();
		}
	}
	
}
?>
