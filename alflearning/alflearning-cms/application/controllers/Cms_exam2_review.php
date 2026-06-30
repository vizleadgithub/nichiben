<?php
#[AllowDynamicProperties]
class Cms_exam2 extends CI_Controller {
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
		
		//ヘルパー読み込み
		$this->load->helper('json');
		
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

		$this->form_validation->set_rules('s_cource'    , $this->lang->line_or_def('common_course_name','講座名')    , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_exam2');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('exam2_review_search_cond') ?: array(
				's_cource' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_cource']    = ($this->input->post('s_cource') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('exam2_review_search_cond', $data);
		}

		$exam2_list = $this->model_exam2->get_exam2_list(array(
			'school_id' 	=> $this->libauth->get_school_id(),
			'bar_association_id' => $this->libauth->get_bar_association_id(),
			'offset'    	=> $offset,
			'rowcount'  	=> $per_page,
			's_cource'		=> $data['s_cource'],		//search
			's_free_word'	=> $data['s_free_word'],	//search
		));

		//$data['exam2_list'] = $exam2_list['items'];
		$exam2_table_list = $exam2_list['items'];
		
		//問題受付状態の取得
		$now = date('YmdHis');
		for($i = 0; $i<count($exam2_table_list); $i++){
			if($exam2_table_list[$i]['exam2_open'] <= $now 
					&& $now <= $exam2_table_list[$i]['exam2_close']){
				$exam2_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_now','受付中');
			} else if( $exam2_table_list[$i]['exam2_open'] > $now){
				$exam2_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_plan','受付予定');
			} else if( $exam2_table_list[$i]['exam2_close'] < $now){
				$exam2_table_list[$i]["disp_status"] = $this->lang->line_or_def('common_accept_end','受付終了');
			} else {
				$exam2_table_list[$i]["disp_status"] = "";
			}
		}
		$data['exam2_list'] = $exam2_table_list;
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_exam2/index';
		$config['per_page']   = $per_page;
		$config['total_rows'] = $exam2_list['cnt'];
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
						'view_name'   => 'cms_exam2/index',
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
		$data['exam2']['update_flg']           = 0;
		$data['exam2']['exam2_id']              = 0;
		$data['exam2']['exam2_name']            = '';
		$data['exam2']['exam2_caption']         = '';
		$data['exam2']['exam2_open']            = '1900/01/01 00:00:00';
		$data['exam2']['exam2_close']           = '2100/12/31 23:59:59';
		$data['exam2']['public_flag']          = 0;
		$data['exam2']['resubmit_flag']        = 1;
		$data['exam2']['display_format']       = 0;
		$data['exam2']['marking_public_flag']  = 9;
		$data['exam2']['marking_public_kind']  = 1;
		$data['exam2']['marking_public_open']  = date('Y/m/d H:i:s', strtotime("+1 month"));  // システム日時 + 一か月先
		$data['exam2']['teacher_id']           = $this->libauth->get_teacher_id();
		$data['exam2']['teacher_name']         = '';
		$data['exam2']['exam2_lectures']        = array();  // 所属講座ID
		$data['exam2']['exam2_students']        = array();  // 選択済み受講者ID
		$data['exam2']['exam2_problems']        = array();  // 選択済み設問ID
		$data['exam2']['exam2_remind_time']     = array();
		$data['exam2']['exam2_remind_unit']     = array();
		$data['exam2']['criteria_type']        = 1;
		$data['exam2']['criteria_value']       = '';

		// mitemo対応（管理講師を裏側で持つ）
		if( getenv('URL_SERVICE')=='mitemo' ){
			$this->load->model('model_teacher');
			$data['exam2']['teacher_id'] = $this->model_teacher->get_teacher_representative_school($this->libauth->get_school_id());
		}

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_exam2/edit',
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
		
		//課題管理の権限有無確認（新規作成の修正の場合は、権限チェックなしとする）
		$auth_exam2 = 1;
		if( $edit_form_data['update_flg']!=0){
			$auth_exam2 = $this->_get_auth_exam2($edit_form_data['exam2_id']);
		}
		
		// 権限を持たない場合、エラーを返す
		if($auth_exam2 == 0){
			//戻り先設定
			$data['returnurl']       = site_url('cms_exam2');  //site_url('cms_exam2').'/detail/'.$edit_form_data['exam2_id'].'/';
			$data['error_message']   = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
			$data['select_callview'] = 'exam2';
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'wide_use_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}

		elseif(isset($edit_form_data['exam2_id']) &&  $edit_form_data['exam2_id'] <> ''){
			//画面表示用データ設定
			$data['exam2'] = $edit_form_data;
			
			//モデル読み込み
			$this->load->model('model_exam2');
			
			// 選択済み受講者ID取得
			// 　POSTあり：POSTの値を使用。
			// 　POSTなし：セッション内、問題ID・問題の所属講座IDを使用して、受講者IDをDBより取得。
			// 　　　　　　既存学校の場合、講座IDでフィルターしていたため、講座受講者テーブルに値なし
			// 　　　　　　課題講座IDを取得し、その講座IDに属する生徒を表示
			$data['exam2']['exam2_students']  = array();
			if( $this->input->post('exam2_students') ){
				$data['exam2']['exam2_students'] = $this->input->post('exam2_students');
			}else{
				$param_select_student = array(
								'exam2_id'    => $data['exam2']['exam2_id'],
								'cource_id'  => $data['exam2']['exam2_lectures'],
							);
				$lectures = $this->model_exam2->get_exam2_student($param_select_student);
				$idx = -1;
				if(isset($lectures)){
					foreach($lectures as $lecture){
						$idx++;
						$data['exam2']['exam2_students'][$idx] = $lecture['student_id'];
					}
				}
			}
			
			// 選択済み設問ID取得
			// 　POSTあり：POSTの値を使用。
			// 　POSTなし：セッション内の値を使用して、受講者IDをDBより取得。
			$data['exam2']['exam2_problems'] = array();
			if( $this->input->post('exam2_problems') ){
				$data['exam2']['exam2_problems'] = $this->input->post('exam2_problems');
			}else{
				$exam2_problems = $this->model_exam2->get_exam2_problems($data['exam2']);
				$idx = -1;
				if(isset($exam2_problems)){
					foreach($exam2_problems as $exam2_problem){
						$idx++;
						$data['exam2']['exam2_problems'][$idx] = $exam2_problem['exam2_problem_id'];
					}
				}
			}


			if( !isset($data['exam2']['exam2_remind_time'])){
				// キューテーブルより、通知情報を取得 json
				// ステータスが「WAITING」のものを抽出
				$data['exam2']['exam2_remind_time'] = array();
				$data['exam2']['exam2_remind_unit'] = array();
				$this->load->model('model_queue');
				$queue_list = $this->model_queue->get_queue(array(
					'queue_type'          => 'exam2',
					'queue_type_id'       => $data['exam2']['exam2_id'],
					'queue_kind'          => 'mail',
					'queue_status'        => 'WAITING',
				));
				if($queue_list){
					$this->load->helper('json');
					$count = -1;
					foreach($queue_list as $queue){
						$count = $count + 1;
						$queue_kind_detail = json_decode($queue['queue_kind_detail']);
						
						$data['exam2']['exam2_remind_time'][$count] = $queue_kind_detail->args->TIME;
						$data['exam2']['exam2_remind_unit'][$count] = $queue_kind_detail->args->UNIT;
					}
				}
			}


			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_exam2/edit',
							'submenu_idx' => ($data['exam2']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('exam2');
			
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
	function detail($exam2_id){
		
		// POST取得（解答修正登録から「戻る」押下時に渡される変更値）
		$temp_exam2_answer_data = $this->input->post('exam2_answer_data')?$this->input->post('exam2_answer_data'):array();
		
		// POST値を文字列として取得
		$string_exam2_answer_data = "";
		foreach($temp_exam2_answer_data as $exam2_answer_data){
			// 正規表現で分割 --- [0]全体、[1]解答ID(exam2_answer_id)、[2]解答結果(exam2_answer_mark)、[3]解答配点(exam2_answer_point)
			preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $exam2_answer_data, $temp);
			
			// 解答ID/解答結果/解答配点を文字列保存
			if($string_exam2_answer_data==""){
				$string_exam2_answer_data  = $temp[1]."/".$temp[2]."/".$temp[3];
			}else{
				$string_exam2_answer_data .= "@".$temp[1]."/".$temp[2]."/".$temp[3];
			}
		}
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_exam2');
		
		//データ取得用引数設定
		$data_param = array(
						'exam2_id' => $exam2_id,
					);
		//データ取得
		$db_data = $this->model_exam2->get_exam2($data_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['exam2']['update_flg']           = 1;
			$data['exam2']['exam2_id']              = $db_data['exam2_id'];
			$data['exam2']['exam2_name']            = $db_data['exam2_name'];
			$data['exam2']['exam2_caption']         = $db_data['exam2_caption'];
			$data['exam2']['exam2_open']            = $db_data['exam2_open'];
			$data['exam2']['exam2_close']           = $db_data['exam2_close'];
			$data['exam2']['public_flag']          = $db_data['public_flag'];
			$data['exam2']['resubmit_flag']        = $db_data['resubmit_flag'];
			$data['exam2']['marking_public_flag']  = $db_data['marking_public_flag'];
			$data['exam2']['marking_public_kind']  = $db_data['marking_public_kind'];
			$data['exam2']['marking_public_open']  = ($db_data['marking_public_open'])?$db_data['marking_public_open']:date('Y/m/d H:i:s', strtotime("+1 month"));
			$data['exam2']['teacher_id']           = $db_data['teacher_id'];
			$data['exam2']['teacher_name']         = $db_data['teacher_name'];
			$data['exam2']['display_format']       = $db_data['display_format'];
			$data['exam2']['criteria_type']        = $db_data['criteria_type'];
			$data['exam2']['criteria_value']       = $db_data['criteria_value'];
			
			$data['exam2']['string_exam2_answer_data'] = $string_exam2_answer_data;  // POST値
			
			//所属講座取得
			$data['exam2']['exam2_lectures']  = array();
			$lectures = $this->model_exam2->get_exam2_lectures($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['exam2']['exam2_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			
			// セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['exam2']));
			
			//選択済み受講者ID取得
			// 既存学校の場合、講座IDでフィルターしていたため、講座受講者テーブルに値なし
			// 課題講座IDを取得し、その講座IDに属する生徒を表示
			$data['exam2']['exam2_students']  = array();
			$param_select_student = array(
							'exam2_id'    => $data_param['exam2_id'],
							'cource_id'  => $data['exam2']['exam2_lectures'],
						);
			$lectures = $this->model_exam2->get_exam2_student($param_select_student);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['exam2']['exam2_students'][$idx] = $lecture['student_id'];
				}
			}

			//選択済み設問ID・設問名・管理講師名・解答配点を取得
			$data['exam2']['exam2_problems']                  = array();  // 設問ID
			$data['exam2']['exam2_problems_name']             = array();  // 設問名
			$data['exam2']['exam2_problems_teacher_name']     = array();  // 管理講師名
			$data['exam2']['exam2_problems_answer_point']     = array();  // 解答配点
			
			$exam2_problems = $this->model_exam2->get_exam2_problems($data_param);
			
			$idx = -1;
			if(isset($exam2_problems)){
				foreach($exam2_problems as $exam2_problem){
					$idx++;
					$data['exam2']['exam2_problems'][$idx] = $exam2_problem['exam2_problem_id'];
					
					$data['exam2']['exam2_problems_name'][$idx]         = $exam2_problem['exam2_problem_name'];
					$data['exam2']['exam2_problems_teacher_name'][$idx] = $exam2_problem['teacher_name'];
					$data['exam2']['exam2_problems_answer_point'][$idx] = $exam2_problem['answer_point'];
				}
			}
			
			// 所属講座名設定
			$data['exam2']['exam2_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['exam2']['exam2_lectures']){
				foreach($data['exam2']['exam2_lectures'] as $idx => $lecture){
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['student']['student_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['exam2']['exam2_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				//連想キーと要素との関係を維持しつつ配列をソート
				asort($data['exam2']['exam2_lectures_name']);
			}
			
			// 設問詳細は Ajax経由取得
			
			// 受講者ID取得（問題詳細画面用）
			// データ取得用引数設定
		//	$data['exam2']['lecture_students'] = array();
			$exam2_students_confirm_list = "0";
			$param_select_student = array(
							'exam2_id'    => $data_param['exam2_id'],
							'cource_id'  => $data['exam2']['exam2_lectures'],
						);
			$lectures_confirm = $this->model_exam2->get_exam2_student_confirm($param_select_student);
			$idx = -1;
			if(isset($lectures_confirm)){
				foreach($lectures_confirm as $lecture){
					$idx++;
					$data['exam2']['exam2_students_confirm'][$idx] = $lecture['student_id'];
					
					$exam2_students_confirm_list .= ",".$lecture['student_id'];
				}
			}
			
/*
			//受講者名設定（問題詳細画面用の受講者IDより取得するように変更）
			$data['exam2']['exam2_students_name'] = '---';
			$this->load->model('model_student');
			if($data['exam2']['exam2_students_confirm']){

				$student_list = $this->model_student->get_name_array( array('student_id_list' => $exam2_students_confirm_list) );
				
				foreach($student_list as $idx => $student_data){
					if($data['exam2']['exam2_students_name'] == '---'){
						$data['exam2']['exam2_students_name'] = '[No'.$student_data['student_id'].']&nbsp;'.$student_data['student_name'].'&nbsp;&lt;'.$student_data['student_email'].'&gt;';
					}else{
						$data['exam2']['exam2_students_name'] .= '<br/>'.'[No'.$student_data['student_id'].']&nbsp;'.$student_data['student_name'].'&nbsp;&lt;'.$student_data['student_email'].'&gt;';
					}
				}
				//foreach($data['exam2']['exam2_students_confirm'] as $idx => $lecture){
				//	//データ取得用引数設定
				//	$name_param = array(
				//					'student_id' => $lecture,
				//				);
				//	$temp = $this->model_student->get_name($name_param);
				//	if($data['exam2']['exam2_students_name'] == '---'){
				//		$data['exam2']['exam2_students_name'] = '[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
				//	}else{
				//		$data['exam2']['exam2_students_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
				//	}
				//}
			}
*/
			
			// 設問の解答配点の最大値を取得
			$data['exam2']['max_answer_point'] = 0;        // 解答配点の合計
			$exam2_param = array(
					'school_id'  => $this->libauth->get_school_id(),
					'exam2_id'    => $data_param['exam2_id'],
					);
			$this->load->model('model_exam2_answer');
			$max_answer_point = $this->model_exam2_answer->get_max_answer_point($exam2_param);
			$data['exam2']['max_answer_point'] = $max_answer_point;
			
			// 解答の取得
			//           ['exam2_id']                             // 問題ID
			$data['exam2']['answer_date']             = array();  // 解答日時
			$data['exam2']['answer_student_id']       = array();  // 解答受講者名
			$data['exam2']['answer_student_name']     = array();  // 解答受講者名
			$data['exam2']['answer_no']               = array();  // 解答回数（何回目の解答か）
			$data['exam2']['answer_point_total']      = array();  // 解答配点の合計
			$data['exam2']['exam2_answer_latest_flag'] = array();  // 最新解答フラグ　設問の解答が最新かどうか　1：最新、0：過去
			// 
			$list_result = $this->model_exam2_answer->get_exam2_answer_list($exam2_param);
			//$exam2_answer_list_count = $list_result['cnt'];
			$exam2_answer_list = $list_result['items'];
			
			$check_student_id = ',-1,';
			$idx = -1;
			if(isset($exam2_answer_list)){
				foreach($exam2_answer_list as $exam2_answer){
					$idx++;
					
					// 解答日時・(受講者ID)・受講者名・(解答回数)・解答配点
					$data['exam2']['answer_date'][$idx]         = date('Y/m/d H:i:s', strtotime($exam2_answer['answer_date']));
					$data['exam2']['answer_student_id'][$idx]   = $exam2_answer['answer_student_id'];
					$data['exam2']['answer_student_name'][$idx] = $exam2_answer['answer_student_name'];
					$data['exam2']['answer_no'][$idx]           = $exam2_answer['answer_no'];
					$data['exam2']['answer_point_total'][$idx]  = $exam2_answer['answer_point_total'];
					
					// 最新解答フラグ（check_student_id にない場合は最新解答とする）
					$data['exam2']['exam2_answer_latest_flag'][$idx] = 0;
					if( !strpos($check_student_id, ",".trim($exam2_answer['answer_student_id']).",") ){
						$data['exam2']['exam2_answer_latest_flag'][$idx] = 1;
						$check_student_id .= $exam2_answer['answer_student_id'].',';
					}
				}
			}
			
			// 解答詳細は Ajax経由取得
			
			// 修正・削除ボタン表示フラグ
			//   表示対象：SuperUser・学校管理者・問題を作成した講師
			$auth_exam2 = 1;
			$auth_exam2 = $this->_get_auth_exam2($data['exam2']['exam2_id']);
			$data['exam2_edit_delete_flag'] = $auth_exam2;
			


			// キューテーブルより、通知情報を取得 json
			// ステータスが「WAITING」のものを抽出
			$data['exam2']['exam2_remind_time'] = array();
			$data['exam2']['exam2_remind_unit'] = array();
			$this->load->model('model_queue');
			$queue_list = $this->model_queue->get_queue(array(
				'queue_type'          => 'exam2',
				'queue_type_id'       => $data['exam2']['exam2_id'],
				'queue_kind'          => 'mail',
				'queue_status'        => 'WAITING',
			));
			if($queue_list){
				$this->load->helper('json');
				$count = -1;
				foreach($queue_list as $queue){
					$count = $count + 1;
					$queue_kind_detail = json_decode($queue['queue_kind_detail']);
					
					$data['exam2']['exam2_remind_time'][$count] = $queue_kind_detail->args->TIME;
					$data['exam2']['exam2_remind_unit'][$count] = $queue_kind_detail->args->UNIT;
				}
			}



			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_exam2/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
							'exam2_id'     => $exam2_id,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_exam2/");
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
		$this->form_validation->set_rules('update_flg'    , $this->lang->line_or_def('common_flg','flg')                     , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_id'       , $this->lang->line_or_def('common_id','ID')                       , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('exam2_name'     , $this->lang->line_or_def('common_exam2_name','問題（テスト）名')  , 'trim|xss_clean|required');
		$this->form_validation->set_rules('exam2_lectures' , $this->lang->line_or_def('common_position_course','所属講座')    , 'required');
		$this->form_validation->set_rules('exam2_caption'  , $this->lang->line_or_def('common_caption','説明')                , 'trim|xss_clean');
		$this->form_validation->set_rules('exam2_open'     , $this->lang->line_or_def('common_submit_period','提出期間')      , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('exam2_close'    , $this->lang->line_or_def('common_submit_period','提出期間')      , 'trim|xss_clean|required|callback_datetime_check|callback_period_check[exam2_open]');
		$this->form_validation->set_rules('public_flag'   , $this->lang->line_or_def('common_indication_status','公開設定')  , 'trim|xss_clean|required');
		$this->form_validation->set_rules('resubmit_flag' , $this->lang->line_or_def('common_resubmit','再提出')             , 'trim|xss_clean|required');
		
		$this->form_validation->set_rules('marking_public_flag' , $this->lang->line_or_def('common_marking_public_status','採点公開設定') , 'trim|xss_clean|required');
		$this->form_validation->set_rules('marking_public_kind' , $this->lang->line_or_def('common_marking_public_status','採点公開設定') , 'trim|xss_clean');
		$this->form_validation->set_rules('marking_public_open' , $this->lang->line_or_def('common_marking_public_status','採点公開設定') , 'trim|xss_clean');
		
		$this->form_validation->set_rules('teacher_id'    , $this->lang->line_or_def('common_management_teacher','管理講師') , 'required');
//		$this->form_validation->set_rules('exam2_students' , $this->lang->line_or_def('common_student','受講者')              , 'required');
		$this->form_validation->set_rules('exam2_problems' , $this->lang->line_or_def('common_exam2_problem','設問')           , 'required');

		$this->form_validation->set_rules('display_format'   , $this->lang->line_or_def('common_display_format'  ,'表示形式')  , 'trim|xss_clean|required');

		$this->form_validation->set_rules('criteria_type'  , $this->lang->line_or_def('common_criteria','判定基準') , 'trim|xss_clean|required');
		$this->form_validation->set_rules('criteria_value' , $this->lang->line_or_def('common_criteria','判定基準') , 'trim|xss_clean|required');

		// 配列型の所属講座・受講者・設問の値をチェック
		$this->load->helper('string_inspection_helper');

		$check_exam2_lectures = $this->input->post('exam2_lectures')?$this->input->post('exam2_lectures'):array();
		$check_exam2_students = $this->input->post('exam2_students')?$this->input->post('exam2_students'):array();
		$check_exam2_problems = $this->input->post('exam2_problems')?$this->input->post('exam2_problems'):array();

		if(!check_array_data_num(array($check_exam2_lectures, $check_exam2_students, $check_exam2_problems))){
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
			// 通知チェック
			// time が空の場合は登録対象外
			// time が数値以外・ゼロの場合は登録対象外
			$exam2_remind_time      = array();
			$exam2_remind_unit      = array();
			$temp_exam2_remind_time = $this->input->post('remind_time')?$this->input->post('remind_time'):array();
			$temp_exam2_remind_unit = $this->input->post('remind_unit')?$this->input->post('remind_unit'):array();
			
			
			// 個数が1以上、両方の配列個数が同じ場合
			if( (count($temp_exam2_remind_time)>0) && (count($temp_exam2_remind_time)==count($temp_exam2_remind_unit)) ){
				$remind_count      = -1;
				$remind_check_list = '0,';
				foreach($temp_exam2_remind_time as $ino => $remind_time){
					// 時間がゼロ以上・単位が指定文字の場合、取得
					if(  ((is_numeric($remind_time)) && ($remind_time>0)) && (($temp_exam2_remind_unit[$ino]=='minute') || ($temp_exam2_remind_unit[$ino]=='hour') || ($temp_exam2_remind_unit[$ino]=='day'))  ){
						
						// 重複していないかを確認
						
						if( strpos($remind_check_list,  ','.$remind_time.$temp_exam2_remind_unit[$ino].',')!==FALSE ){
							// 重複あり
						}else{
							// 重複なし
							$remind_check_list .= $remind_time.$temp_exam2_remind_unit[$ino].',';    // リストに追加
							
							$remind_count = $remind_count + 1;
							$exam2_remind_time[$remind_count] = $remind_time;
							$exam2_remind_unit[$remind_count] = $temp_exam2_remind_unit[$ino];
						}
					}
				}
			}





			//検証
			if($this->form_validation->run() == FALSE){
				//失敗
				//受け渡し変数初期化（未定義エラー回避の為）
				$data['exam2']['update_flg']           = $this->input->post('update_flg');
				$data['exam2']['exam2_id']              = $this->input->post('exam2_id');
				$data['exam2']['exam2_name']            = '';
				$data['exam2']['exam2_lectures']        = $this->input->post('exam2_lectures')?$this->input->post('exam2_lectures'):array();
				$data['exam2']['exam2_caption']         = '';
				$data['exam2']['exam2_open']            = '';
				$data['exam2']['exam2_close']           = '';
				$data['exam2']['public_flag']          = '';
				$data['exam2']['resubmit_flag']        = '';
				$data['exam2']['marking_public_flag']  = '';
				$data['exam2']['marking_public_kind']  = '';
				$data['exam2']['marking_public_open']  = '';
				$data['exam2']['teacher_id']           = $this->input->post('teacher_id');
				$data['exam2']['teacher_name']         = '';
				$data['exam2']['exam2_students']        = $this->input->post('exam2_students')?$this->input->post('exam2_students'):array();
				$data['exam2']['exam2_problems']        = $this->input->post('exam2_problems')?$this->input->post('exam2_problems'):array();
				$data['exam2']['exam2_remind_time']     = $exam2_remind_time;
				$data['exam2']['exam2_remind_unit']     = $exam2_remind_unit;
				$data['exam2']['display_format']       = $this->input->post('display_format');
				$data['exam2']['criteria_type']        = '';
				$data['exam2']['criteria_value']       = '';
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2/edit',
								'submenu_idx' => ($data['exam2']['update_flg']==0 ? 2 : 3),
								'view_data'   => $data,
							);
				//編集フォーム再表示
				$this->_display_view($disp_param);
			}else{
				// 成功
				$data['btn_kirikae_flg'] = 1;

				$data['exam2']['update_flg']           = $this->input->post('update_flg');
				$data['exam2']['exam2_id']              = $this->input->post('exam2_id');
				$data['exam2']['exam2_name']            = $this->input->post('exam2_name');
				$data['exam2']['exam2_lectures']        = $this->input->post('exam2_lectures')?$this->input->post('exam2_lectures'):array();
				$data['exam2']['exam2_caption']         = $this->input->post('exam2_caption');
				$data['exam2']['exam2_open']            = $this->input->post('exam2_open');
				$data['exam2']['exam2_close']           = $this->input->post('exam2_close');
				$data['exam2']['public_flag']          = $this->input->post('public_flag');
				$data['exam2']['resubmit_flag']        = $this->input->post('resubmit_flag');
				$data['exam2']['marking_public_flag']  = $this->input->post('marking_public_flag');
				$data['exam2']['marking_public_kind']  = $this->input->post('marking_public_kind');
				$data['exam2']['marking_public_open']  = $this->input->post('marking_public_open');
				$data['exam2']['school_id']            = $this->libauth->get_school_id();
				$data['exam2']['teacher_id']           = $this->input->post('teacher_id');
				$data['exam2']['teacher_name']         = '';
				$data['exam2']['exam2_remind_time']     = $exam2_remind_time;
				$data['exam2']['exam2_remind_unit']     = $exam2_remind_unit;
				$data['exam2']['display_format']       = $this->input->post('display_format');
				$data['exam2']['criteria_type']        = $this->input->post('criteria_type');
				$data['exam2']['criteria_value']       = $this->input->post('criteria_value');

				//セッションへ検証済みデータを書き込み
				$this->session->set_userdata('edit_form_data',serialize($data['exam2']));

				$data['exam2']['exam2_students']    = $this->input->post('exam2_students')?$this->input->post('exam2_students'):array();
				$data['exam2']['exam2_problems']    = $this->input->post('exam2_problems')?$this->input->post('exam2_problems'):array();

				// 管理講師名設定
				$this->load->model('model_teacher');
				$temp_teacher = $this->model_teacher->get_name(array(
					'teacher_id' => $data['exam2']['teacher_id'],
				));
				if($temp_teacher){
					$data['exam2']['teacher_name'] = $temp_teacher;
				}

				// 所属講座名設定
				$data['exam2']['exam2_lectures_name'] = array();
				$this->load->model('model_cource');
				if($data['exam2']['exam2_lectures']){
					foreach($data['exam2']['exam2_lectures'] as $idx => $lecture){
						//データ取得用引数設定
						$name_param = array(
										'cource_id' => $lecture,
									);
						if($this->model_cource->get_name($name_param)){
							$data['exam2']['exam2_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
						}
					}
					//連想キーと要素との関係を維持しつつ配列をソート
					asort($data['exam2']['exam2_lectures_name']);
				}

				// 受講者名設定
				//   各受講者IDで受講者名取得から、複数受講者ID一括で受講者名取得に変更（DBアクセス数軽減）
				$data['exam2']['exam2_students_name'] = '---';
				$this->load->model('model_student');
				if($data['exam2']['exam2_students']){
					$student_id_list = implode(",", $data['exam2']['exam2_students']);
					
					$student_list = $this->model_student->get_name_array( array('student_id_list' => $student_id_list) );
					
					foreach($student_list as $idx => $student_data){
						if($data['exam2']['exam2_students_name'] == '---'){
							$data['exam2']['exam2_students_name'] = '[No'.$student_data['student_id'].']&nbsp;'.$student_data['student_name'].'&nbsp;&lt;'.$student_data['student_email'].'&gt;';
						}else{
							$data['exam2']['exam2_students_name'] .= '<br/>'.'[No'.$student_data['student_id'].']&nbsp;'.$student_data['student_name'].'&nbsp;&lt;'.$student_data['student_email'].'&gt;';
						}
					}
					//foreach($data['exam2']['exam2_students'] as $idx => $lecture){
					//	//データ取得用引数設定
					//	$name_param = array(
					//					'student_id' => $lecture,
					//				);
					//	$temp = $this->model_student->get_name($name_param);
					//	if($data['exam2']['exam2_students_name'] == '---'){
					//		$data['exam2']['exam2_students_name'] = '[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					//	}else{
					//		$data['exam2']['exam2_students_name'] .= '<br/>'.'[No'.$lecture.']&nbsp;'.$temp['student_name'].'&nbsp;&lt;'.$temp['student_email'].'&gt;';
					//	}
					//}
				}

				// 選択済み設問ID・設問名・管理講師名・解答配点を取得
				$data['exam2']['exam2_problems_name']             = array();  // 設問名
				$data['exam2']['exam2_problems_teacher_name']     = array();  // 管理講師名
				$data['exam2']['exam2_problems_answer_point']     = array();  // 解答配点

				$this->load->model('model_exam2_problem');
				if($data['exam2']['exam2_problems']){
					foreach($data['exam2']['exam2_problems'] as $idx => $lecture){
						//データ取得用引数設定
						$name_param = array(
										'exam2_problem_id' => $lecture,
									);
						$data_result = $this->model_exam2_problem->get_name($name_param);
						
						$data['exam2']['exam2_problems_name'][$idx]         = $data_result['exam2_problem_name'];
						$data['exam2']['exam2_problems_teacher_name'][$idx] = $data_result['teacher_name'];
						$data['exam2']['exam2_problems_answer_point'][$idx] = $data_result['answer_point'];
					}
				}

				// 回答情報（概要）の取得
				
				$data['exam2']['max_answer_point']    = 0;        // 解答配点の合計
				//           ['exam2_id']                         // 問題ID
				$data['exam2']['answer_date']         = array();  // 解答日時
				$data['exam2']['answer_student_id']   = array();  // 解答受講者名
				$data['exam2']['answer_student_name'] = array();  // 解答受講者名
				$data['exam2']['answer_no']           = array();  // 解答回数（何回目の解答か）
				$data['exam2']['answer_point_total']  = array();  // 解答配点の合計
				
				$exam2_param = array(
						'school_id'  => $this->libauth->get_school_id(),
						'exam2_id'    => $data['exam2']['exam2_id'],
						);
				
				$this->load->model('model_exam2_answer');
				$max_answer_point = $this->model_exam2_answer->get_max_answer_point($exam2_param);
				$data['exam2']['max_answer_point'] = $max_answer_point;
				
				$list_result = $this->model_exam2_answer->get_exam2_answer_list($exam2_param);
				$exam2_answer_list = $list_result['items'];
				
				$data['exam2']['exam2_answer_latest_flag'] = array();  // 最新解答フラグ
				$check_student_id = ',-1,';
				
				$idx = -1;
				if(isset($exam2_answer_list)){
					foreach($exam2_answer_list as $exam2_answer){
						$idx++;
						$data['exam2']['answer_date'][$idx]         = date('Y/m/d H:i:s', strtotime($exam2_answer['answer_date']));
						$data['exam2']['answer_student_id'][$idx]   = $exam2_answer['answer_student_id'];
						$data['exam2']['answer_student_name'][$idx] = $exam2_answer['answer_student_name'];
						$data['exam2']['answer_no'][$idx]           = $exam2_answer['answer_no'];
						$data['exam2']['answer_point_total'][$idx]  = $exam2_answer['answer_point_total'];
						
						$data['exam2']['exam2_answer_latest_flag'][$idx] = 0;
						if( !strpos($check_student_id, ",".trim($exam2_answer['answer_student_id']).",") ){
							$data['exam2']['exam2_answer_latest_flag'][$idx] = 1;
							$check_student_id .= $exam2_answer['answer_student_id'].',';
						}
						
						// 登録確認・修正確認画面では、解答詳細は不要。
					}
				}
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2/confirm',
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

		if(isset($edit_form_data['exam2_id']) && $edit_form_data['exam2_id'] != ""){

			// 受講者ID・設問IDを、POST取得
			$edit_form_data['exam2_students']    = $this->input->post('exam2_students')?$this->input->post('exam2_students'):array();
			$edit_form_data['exam2_problems']    = $this->input->post('exam2_problems')?$this->input->post('exam2_problems'):array();

			//モデル読み込み
			$this->load->model('model_exam2');
			
			//更新用引数設定
			$update_param = array(
							'data'   => $edit_form_data,
						);

			// 試験テーブル更新
			$exam2_result = $this->model_exam2->update_exam2($update_param);
			
			// 試験講座テーブル更新
			$data_param = array(
							'exam2_id'  => $exam2_result['lastInsertId'],
							'data'     => $update_param['data'],
						);
			$this->model_exam2->update_exam2_lectures($data_param);
			
			// 試験受講者・試験受講者グループ更新
			$this->model_exam2->update_exam2_student_group($data_param);
			
			// 試験設問関係テーブル更新
			$this->model_exam2->update_rel_exam2_problem($data_param);
			


			// 通知登録を行う。
			// 通知機能は未完成のため動作させないように！
		//	$res_remind = $this->exam2_queue_remind($data_param);


			//ビュー設定引数設定
			$data = array();
			$disp_param = array(
							'view_name'   => 'cms_exam2/commit',
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
			$data['returnurl'] = site_url('exam2');
			
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
	function delete_item($exam2_id){
		// load language
		$this->lang->load('error');

		//モデル読み込み
		$this->load->model('model_exam2');

		//試験管理の権限有無確認（新規作成の修正の場合は、権限チェックなしとする）
		$auth_exam2 = 1;
		$auth_exam2 = $this->_get_auth_exam2($exam2_id);

		// 削除権限がない場合、エラー画面を表示
		if($auth_exam2 == 0){
			//戻り先設定
			$data['returnurl']       = site_url('cms_exam2');  // site_url('cms_exam2').'/detail/'.$exam2_id.'/';
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
			$data = $this->model_exam2->delete_item(array(
				'exam2_id' => $exam2_id,
			));
			
			// 通知の無効化
			$this->load->model('model_queue');
			$this->model_queue->update_queue_deleted_type_kind_status(array(
				'queue_type'     => 'exam2',
				'queue_type_id'  => $exam2_id,
				'queue_kind'     => 'mail',
				'queue_status'   => 'WAITING',
			));
			
			//完了フォーム表示
			$this->_display_view(array(
				'view_name'   => 'cms_exam2/commit',
				'submenu_idx' => 4,
				'view_data'   => array(),
				//'class_id'    => $this->input->post('class_id'),
			));

			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}

	//----------------------------------------------
	// 解答修正登録（確認画面）
	//----------------------------------------------
	function exam2_answer_update_confirm($exam2_id = 0){
		
		// 権限を持たない場合、エラーを返す
		$auth_exam2 = $this->_get_auth_exam2($exam2_id);
		
		if($auth_exam2 == 0){
			//戻り先設定
			$data['returnurl']       = site_url('cms_exam2');  //site_url('cms_exam2').'/detail/'.$exam2_id.'/';
			$data['error_message']   = $this->lang->line_or_def('error_edit_auth','修正権限がありません<br />ログインし直してください');
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
			$this->load->model('model_exam2');
			
			// POST取得
			$temp_exam2_answer_data = $this->input->post('exam2_answer_data')?$this->input->post('exam2_answer_data'):array();
			
			// 各値を配列化
			$array_exam2_answer_data = array();
			foreach($temp_exam2_answer_data as $exam2_answer_data){
				// 正規表現で分割 --- [0]全体、[1]解答ID(exam2_answer_id)、[2]解答結果(exam2_answer_mark)、[3]解答配点(exam2_answer_point)
				preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $exam2_answer_data, $temp);
				
				// 解答ID - 連想配列格納
				$array_exam2_answer_data[$temp[1]] = array(
					'exam2_answer_mark'  => $temp[2],     // 正誤
					'exam2_answer_point' => $temp[3],     // 解答配点
				);
			}


			//データ取得用引数設定
			$data_param = array(
							'exam2_id' => $exam2_id,
						);
			//データ取得
			$db_data = $this->model_exam2->get_exam2($data_param);
			
			if(count($db_data) > 0){
				//データ有り時
				
				//表示用データ設定（問題ID・問題名・管理講師ID・管理講師名）
				$data['exam2']['exam2_id']       = $db_data['exam2_id'];
				$data['exam2']['exam2_name']     = $db_data['exam2_name'];
				$data['exam2']['teacher_id']    = $db_data['teacher_id'];
				$data['exam2']['teacher_name']  = $db_data['teacher_name'];
				
				// 設問の解答配点の最大値を取得
				$data['exam2']['max_answer_point']    = 0;        // 解答配点の合計
				$exam2_param = array(
						'school_id'  => $this->libauth->get_school_id(),
						'exam2_id'    => $data_param['exam2_id'],
						);
				$this->load->model('model_exam2_answer');
				$max_answer_point = $this->model_exam2_answer->get_max_answer_point($exam2_param);
				$data['exam2']['max_answer_point'] = $max_answer_point;
				
				
				// 解答の取得
				//           ['exam2_id']                             // 問題ID
				$data['exam2']['answer_date']             = array();  // 解答日時
				$data['exam2']['answer_student_id']       = array();  // 解答受講者名
				$data['exam2']['answer_student_name']     = array();  // 解答受講者名
				$data['exam2']['answer_no']               = array();  // 解答回数（何回目の解答か）
				$data['exam2']['answer_point_total']      = array();  // 解答配点の合計
				$data['exam2']['exam2_answer_latest_flag'] = array();  // 最新解答フラグ　設問の解答が最新かどうか　1：最新、0：過去
				$data['exam2']['exam2_answer_show_flag']   = array();  // 表示フラグ　1：表示、0：非表示（=変更がない）
				// 
				$list_result = $this->model_exam2_answer->get_exam2_answer_list($exam2_param);
				//$exam2_answer_list_count = $list_result['cnt'];
				$exam2_answer_list = $list_result['items'];
				
				$check_student_id = ',-1,';
				$idx = -1;
				if(isset($exam2_answer_list)){
					foreach($exam2_answer_list as $exam2_answer){
						$idx++;
						
						// 解答日時・(受講者ID)・受講者名・(解答回数)・解答配点・表示フラグ
						$data['exam2']['answer_date'][$idx]            = date('Y/m/d H:i:s', strtotime($exam2_answer['answer_date']));
						$data['exam2']['answer_student_id'][$idx]      = $exam2_answer['answer_student_id'];
						$data['exam2']['answer_student_name'][$idx]    = $exam2_answer['answer_student_name'];
						$data['exam2']['answer_no'][$idx]              = $exam2_answer['answer_no'];
						$data['exam2']['answer_point_total'][$idx]     = $exam2_answer['answer_point_total'];
						$data['exam2']['exam2_answer_show_flag'][$idx]  = 1;
						
						// 最新解答フラグ（check_student_id にない場合は最新解答とする）
						$data['exam2']['exam2_answer_latest_flag'][$idx] = 0;
						if( !strpos($check_student_id, ",".trim($exam2_answer['answer_student_id']).",") ){
							$data['exam2']['exam2_answer_latest_flag'][$idx] = 1;
							$check_student_id .= $exam2_answer['answer_student_id'].',';
						}

						// 解答 - 詳細の取得
						$param = array(
							'exam2_id'         => $data_param['exam2_id'],
							'student_id'      => $exam2_answer['answer_student_id'],
							'exam2_answer_no'  => $exam2_answer['answer_no'],
						);
						$detail_result = $this->model_exam2_answer->get_exam2_answer_detail($param);
						//$exam2_answer_detail_count = $detail_result['cnt'];
						$exam2_answer_detail = $detail_result['items'];
	/*
					       exam2_answer.exam2_answer_id        -- 解答ID
					      ,exam2_answer.exam2_id               -- 問題ID
					      ,exam2_answer.exam2_problem_id       -- 設問ID
					      ,exam2_answer.student_id            -- 受講者ID
					      ,exam2_answer.exam2_answer_no        -- 解答回数
					      ,exam2_answer.exam2_answer_contents  -- 解答内容
					      ,exam2_answer.exam2_answer_date      -- 解答日時
					      ,exam2_answer.exam2_answer_mark      -- 解答結果 0:不正解 1:正解
					      ,exam2_answer.exam2_answer_point     -- 解答配点
					      ,exam2_answer.marked_teacher_id     -- 採点講師ID
					      ,exam2_answer.status                -- 状態 0:有効 9:削除
					      ,exam2_answer.update_at             -- 更新日時
					      
				//	      ,exam2_problem.exam2_problem_name     -- 設問名
				//	      ,exam2_problem.problem_kind          -- 設問種類  1:テキスト、2:動画、3:図書室
				//	      ,exam2_problem.problem_contents      -- 設問内容
					      ,exam2_problem.answer_kind           -- 解答種類  1:単一形式、2:複数形式、3:フリー回答
					      ,exam2_problem.answer_contents       -- 解答内容 JSON型
					      ,exam2_problem.answer_point          -- 解答配点
	*/
						$idx_detail = -1;
						$new_answer_point_total = 0;  // POST側 解答配点合計
						$update_flag            = 0;  // 解答結果・解答配点変更フラグ（1：変更あり）
						if(isset($exam2_answer_detail)){
							foreach($exam2_answer_detail as $exam2_answer){
								// POSTされた解答配点の合計を取得
							//	$new_answer_point_total = $new_answer_point_total + $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'];
								
								$new_answer_point_total = $new_answer_point_total;
								if( isset($array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]) ){
									$new_answer_point_total = $new_answer_point_total + $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'];
								}
								

								// 解答結果 or 解答配点が、POSTされた値と異なる場合のみ、出力対象とする
								// 解答結果の確認
								$new_exam2_answer_mark = -1;
							//	if( $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark'] != $exam2_answer['exam2_answer_mark']){
							//		$new_exam2_answer_mark = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark'];
							//		$update_flag = 1;
							//	}
								if( isset($array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]) ){
									if( $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark'] != $exam2_answer['exam2_answer_mark']){
										$new_exam2_answer_mark = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_mark'];
										$update_flag = 1;
									}
								}

								// 解答配点の確認
								$new_exam2_answer_point = -1;
							//	if( $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'] != $exam2_answer['exam2_answer_point']){
							//		$new_exam2_answer_point = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'];
							//		$update_flag = 1;
							//	}
								if( isset($array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]) ){
									if( $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'] != $exam2_answer['exam2_answer_point']){
										$new_exam2_answer_point = $array_exam2_answer_data[ $exam2_answer['exam2_answer_id'] ]['exam2_answer_point'];
										$update_flag = 1;
									}
								}

								
								if( ($new_exam2_answer_mark == -1) && ($new_exam2_answer_point == -1) ){
									continue;
								}
								

								$idx_detail++;
								
								// 解答ID
								$data['exam2_answer'][$idx]['exam2_answer_id'][$idx_detail] = $exam2_answer['exam2_answer_id'];
								
								// 解答種類
								$data['exam2_answer'][$idx]['answer_kind'][$idx_detail] = $exam2_answer['answer_kind'];
								
								// 解答内容 --- 解答種類が単一・複数の場合→設問テーブルより内容取得
								//              解答種類がテキストの場合→解答テーブルより内容取得
								$temp ='';
								if( ($exam2_answer['answer_kind']==1) || ($exam2_answer['answer_kind']==2) ){
									$array_exam2_answer_contents = explode(',', $exam2_answer['exam2_answer_contents']);      // 受講者が選択した番号
									$array_answer_contents      = obj2arr(json_decode( $exam2_answer['answer_contents'] )); // JSON型、選択した番号の文字列
									
									for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
										$line_contents = (array)$array_answer_contents['answer_contents'][$i];
										// 選択した番号と一致した内容を出力
										if( in_array( $line_contents['no'], $array_exam2_answer_contents) ){
											
											$line_contents['word'] = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $line_contents['word']);
											//$line_contents['word'] = $line_contents['word'];
										
											if($temp != '') $temp .= "<br/>";
										//	$temp .= "[".$line_contents['no']."] ".$line_contents['word'];
											$temp .= $line_contents['word'];
										}
										//$answer_contents_no[$i]      = $line_contents['no'];
										//$answer_contents_word[$i]    = $line_contents['word'];
										//$answer_contents_correct[$i] = $line_contents['correct'];
									}
								}elseif($exam2_answer['answer_kind']==3){
									$temp = str_replace(array("\r\n", "\r", "\n"), array("<br/>", "<br/>", "<br/>"), $exam2_answer['exam2_answer_contents']);
									//$temp = $exam2_answer['exam2_answer_contents'];
								}
								$data['exam2_answer'][$idx]['exam2_answer_contents'][$idx_detail] = $temp;
								
								// 解答結果（ID）
								$data['exam2_answer'][$idx]['exam2_answer_mark'][$idx_detail] = $exam2_answer['exam2_answer_mark'];
								
								// 解答配点（解答結果による配点）
								$data['exam2_answer'][$idx]['exam2_answer_point'][$idx_detail] = $exam2_answer['exam2_answer_point'];
								
								// POST側 解答結果（変更なし[-1]の場合、DB側解答結果を格納）
								$data['exam2_answer'][$idx]['new_exam2_answer_mark'][$idx_detail] = $new_exam2_answer_mark;
								if($new_exam2_answer_mark==-1){
									$data['exam2_answer'][$idx]['new_exam2_answer_mark'][$idx_detail] = $exam2_answer['exam2_answer_mark'];
								}
								
								// POST側 解答配点（変更なし[-1]の場合、DB側解答配点を格納）
								$data['exam2_answer'][$idx]['new_exam2_answer_point'][$idx_detail] = $new_exam2_answer_point;
								if($new_exam2_answer_point==-1){
									$data['exam2_answer'][$idx]['new_exam2_answer_point'][$idx_detail] = $exam2_answer['exam2_answer_point'];
								}
								
								// 解答配点（設問に設定されている配点）
								$data['exam2_answer'][$idx]['answer_point'][$idx_detail] = $exam2_answer['answer_point'];
								
							}
							
							// POST側 解答配点合計
							$data['exam2']['new_answer_point_total'][$idx]  = $new_answer_point_total;
							
							if($update_flag == 0){
								$data['exam2']['exam2_answer_show_flag'][$idx] = 0;
							}
						}
					}
				}
				
	//print var_dump($data);
	//exit();
				
				//ビュー設定引数設定
				$disp_param = array(
								'view_name'   => 'cms_exam2/confirm_answer',
								'submenu_idx' => 3,
								'view_data'   => $data,
							);
				//確認フォーム表示
				$this->_display_view($disp_param);
			}else{
				//データ無し時
				//一覧に戻る
				//$this->index();
				header("Location:/cms_exam2/");
				exit();
			}
		}
	}


	//----------------------------------------------
	// 解答修正登録（登録完了画面）
	//----------------------------------------------
	function exam2_answer_update_commit(){

		// POST取得
		$exam2_id               = $this->input->post('exam2_id');
		$temp_exam2_answer_data = $this->input->post('exam2_answer_data')?$this->input->post('exam2_answer_data'):array();

		// 各値を配列化
		$array_exam2_answer_data = array();
		foreach($temp_exam2_answer_data as $exam2_answer_data){
			// 正規表現で分割 --- [0]全体、[1]解答ID(exam2_answer_id)、[2]解答結果(exam2_answer_mark)、[3]解答配点(exam2_answer_point)
			preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $exam2_answer_data, $temp);
			
			// 解答ID - 連想配列格納
			$array_exam2_answer_data[$temp[1]] = array(
				'exam2_answer_mark'  => $temp[2],     // 正誤
				'exam2_answer_point' => $temp[3],     // 解答配点
			);

		}

		//モデル読み込み
		$this->load->model('model_exam2_answer');
		

		$arr_exam2_answer_id = array();

		foreach($array_exam2_answer_data as $index => $value){
			$update_param = array(
							'exam2_id'            => $exam2_id,
							'exam2_answer_id'     => $index,
							'exam2_answer_mark'   => $value['exam2_answer_mark'],
							'exam2_answer_point'  => $value['exam2_answer_point'],
							'marked_teacher_id'  => $this->libauth->get_teacher_id(),  // 注意：課題管理者ではなく、ログイン講師IDを格納
						);
			
			// 解答テーブルの更新
			$result = $this->model_exam2_answer->update_exam2_answer($update_param);

			//更新があったexam2_answer_idを配列にとっておく(guidance用)
			$arr_exam2_answer_id[] = $index;
		}

		//コースで解答済みのログがある場合のみ、合計点をguidance_historyに追加する。
		$this->load->model('model_guidance');
		$this->model_guidance->update_guidance_history_exam2($exam2_id,$arr_exam2_answer_id);

		//完了フォーム表示
		$this->_display_view(array(
			'view_name'   => 'cms_exam2/commit_answer',
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
							'exam2_id'    => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id"  => $this->libauth->get_school_id(),
					'exam2_id'    => $param['exam2_id'],
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
		
		// 再提出フラグ、ドロップダウン用データ取得（edit）
		$param['view_data']['resubmit_flag_list']  = $this->_get_select_resubmit_flag();
		
		//受講者グループチェックボックス用データ取得（edit）
		$this->load->model('model_student_group');
		$param['view_data']['student_group_list'] = $this->model_student_group->get_student_group_list($drop_param);
		
		//設問リスト用一覧取得（edit）
		$this->load->model('model_exam2_problem');
		$param['view_data']['exam2_problems_list'] = $this->model_exam2_problem->get_exam2_problem_ul_list($drop_param);
		
		// 解答結果ドロップダウン用データ取得（confirm）
		$param['view_data']['exam2_answer_mark_list']  = $this->_get_exam2_answer_mark_list();
		
		// 判定基準ドロップダウン用データ取得
		$param['view_data']['criteria_type_list']  = $this->_get_select_criteria_type();
		
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
	// 状態（公開フラグ）ドロップダウン用データ取得
	// 採点公開設定 ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_public_flag(){
		$this->lang->load('common');
		
		$data['public_flag'] = array();
		$data['public_flag'][0] = $this->lang->line_or_def('common_public',     '公開');
		$data['public_flag'][9] = $this->lang->line_or_def('common_non_public', '非公開');
		
		return $data['public_flag'];
	}

	//----------------------------------------------
	// 判定基準ドロップダウン用データ取得
	//----------------------------------------------
	function _get_select_criteria_type(){
		$this->lang->load('common');
		
		$data['criteria_type'] = array();
		$data['criteria_type'][1] = '点数';
		$data['criteria_type'][2] = '割合';
		$data['criteria_type'][3] = '正答数';
		
		return $data['criteria_type'];
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
	// 解答結果、ドロップダウン用データ取得
	//----------------------------------------------
	function _get_exam2_answer_mark_list(){
		$this->lang->load('common');
		
		$data['exam2_answer_mark'] = array();
		$data['exam2_answer_mark'][0] = $this->lang->line_or_def('common_non_correct_answer', '不正解');
		$data['exam2_answer_mark'][1] = $this->lang->line_or_def('common_correct_answer', '正解');
		
		return $data['exam2_answer_mark'];
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
	//[2012/09/13]受講者数チェック
	//  エラー条件：選択ゼロ、一般授業で９名を超える
	//----------------------------------------------
	function student_count_check(){
		// load language
		$this->lang->load('error');

		$student_array = $this->input->post('lecture_students')?$this->input->post('lecture_students'):array();
		$class_type    = $this->input->post('class_type')?$this->input->post('class_type'):'';
		$student_count = 0;
		
		foreach($student_array as $student_id) {
			$student_count = $student_count + 1;
		}

		if($student_count == 0){
			//エラーメッセージ設定
			$this->form_validation->set_message('student_count_check', $this->lang->line_or_def('error_class_no_select_student','受講者が選択されていません'));
			return false;
		}elseif( ($student_count > 9) && ($class_type === 'school') ){
			//エラーメッセージ設定
			$this->form_validation->set_message('student_count_check', $this->lang->line_or_def('error_class_max_select_student','参加できる受講者数は最大9名です'));
			return false;
		}else{
			return true;
		}
	}



	//----------------------------------------------
	// 設問のプレビュー表示（別窓）edit 画面
	//  別窓にて設問の表示イメージを表示する
	//----------------------------------------------
	function preview_exam2_problem($exam2_problem_id = 0){
		// load language
		$this->lang->load('common');
		
		//モデル読み込み
		$this->load->model('model_exam2_problem');
		$this->load->model('model_video');
		
		$this->load->helper('json');
		
		// 変数初期化
		$exam2_problem_name      = '';  // 設問名
		$problem_contents_title = '';  // 設問：タイトル
		$problem_contents_value = '';  // 設問：内容
		$problem_answer_title   = '';  // 解答：タイトル
		$problem_answer_value   = '';  // 解答：内容
		
		//データ取得用引数設定
		$data_param = array(
						'school_id'        => $this->libauth->get_school_id(),
						'exam2_problem_id'  => $exam2_problem_id,
					);
		//データ取得
		$db_data = $this->model_exam2_problem->get_exam2_problem($data_param);
		
		if(count($db_data) > 0){
			// 該当ありの場合
			
			// 設問名の取得
			$exam2_problem_name = '[No'.$db_data['exam2_problem_id'].'] '.$db_data['exam2_problem_name'];
			
			// 設問内容の取得 ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
			$problem_contents_title = $this->lang->line_or_def('common_exam2_problem','設問');
			$problem_contents_value = '';
			
			if($db_data['problem_kind'] == 2){
				// 2:動画
				$problem_contents_title .= '<br/>['.$this->lang->line_or_def('common_video', 'ビデオ').']';
				
				$thum_url = $this->model_video->get_video_thumbnail_url($db_data['problem_contents']);


				$problem_contents_value = '<img id="popup_thumbnail_img" src="'.$thum_url.'" />';

				$this->load->model('model_video');
				$video_data = $this->model_video->get_material(array('video_id' => $db_data['problem_contents']));
				$problem_contents_value .= '<span id="popup_thumbnail_name">'.'[No'.$video_data['video_id'].'] '.$video_data['video_logic_name'].'</span>';
			}elseif($db_data['problem_kind'] == 3){
				// 3:図書室
				$problem_contents_title .= '<br/>['.$this->lang->line_or_def('common_book_library', '図書室').']';
				
				$thum_url = "/book_libraly_files/".$db_data['problem_contents']."/Page1/master-Page1-thum.jpg";
				$problem_contents_value = '<img id="popup_thumbnail_img" src="'.$thum_url.'" />';
				
				$this->load->model('model_book_library');
				$book_library_data = $this->model_book_library->get_data(array('book_library_id' => $db_data['problem_contents']));
				$problem_contents_value .= '<span id="popup_thumbnail_name">'.'[No'.$book_library_data['book_library_id'].'] '.$book_library_data['book_library_logic_name'].'</span>';
			}else{
				// 1:テキスト
				$problem_contents_title .= '<br/>['.$this->lang->line_or_def('common_text', 'テキスト').']';
				
				$problem_contents_value = nl2br($db_data['problem_contents']);
			}
			
			// 解答内容の取得 ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
			$problem_answer_title = $this->lang->line_or_def('common_exam2_answer','解答');
			$problem_answer_value = '';
			
			// 解答内容のJSON型変換
			$array_answer_contents   = obj2arr(json_decode( $db_data['answer_contents'] ));
			
			if( ($db_data['answer_kind'] == 1) || ($db_data['answer_kind'] == 2) ){
				// 1:単一形式、2:複数形式
				if($db_data['answer_kind'] == 1){
					$problem_answer_title .= '<br/>['.$this->lang->line_or_def('common_single_forms', '単一形式').']';
				}else{
					$problem_answer_title .= '<br/>['.$this->lang->line_or_def('common_plural_forms', '複数形式').']';
				}
				
				for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
					$line_contents = (array)$array_answer_contents['answer_contents'][$i];
					$value_no      = $line_contents['no'];
					$value_word    = $line_contents['word'];
					$value_correct = $line_contents['correct'];
					
					if($problem_answer_value != '') $problem_answer_value .= '<br/><hr style="border-top: 1px solid #bbb;">';
					
					if($value_correct == 0){
						$problem_answer_value .= '['.$this->lang->line_or_def('common_non_correct', '誤').'] ';
					}else{
						$problem_answer_value .= '['.$this->lang->line_or_def('common_correct', '正').'] ';
					}
					$problem_answer_value .= nl2br($value_word);
				}
			}else{
				// 3:フリー解答
				$problem_answer_title .= '<br/>['.$this->lang->line_or_def('common_free_exam2_answer', 'フリー解答').']';
				
				$line_contents        = (array)$array_answer_contents['answer_contents'][0];
				$problem_answer_value = nl2br($line_contents['word']);
				if( empty($problem_answer_value) ){
					$problem_answer_value = "　";
				}
			}
		}else{
			//※該当なしの場合
			$exam2_problem_name = $this->lang->line_or_def('common_nothing', 'なし');
			
			$problem_contents_title = $this->lang->line_or_def('common_exam2_problem','設問');
			$problem_contents_value = $this->lang->line_or_def('common_nothing', 'なし');
			$problem_answer_title   = $this->lang->line_or_def('common_exam2_answer','解答');
			$problem_answer_value   = $this->lang->line_or_def('common_nothing', 'なし');
		}

		// ポップアップhtmlの作成
		$html_data  = '';
		$html_data .= '<html><head>';
		$html_data .= '<title>'.$exam2_problem_name.'</title>';
		$html_data .= '<style type="text/css">';
		$html_data .= 'BODY{
		               text-align  : center;
		               font-family :"MeiryoKe_PGothic","メイリオ","Meiryo","Hiragino Kaku Gothic Pro","ヒラギノ角ゴ Pro W3","Osaka",Arial,"ＭＳ Ｐゴシック",sans-serif;
		               }';
		$html_data .= '.problem_contents_title {
		               float      : left;
		               width      : 100px;
		               text-align : left;
		               }';
		$html_data .= '.problem_contents_value {
		               float            : left; 
		               width            : 490px; 
		               padding          : 5px; 
		               text-align       : left;
		               background-color : #f6f6f3;
		               }';
		$html_data .= '#popup_thumbnail_img {
		               border    : 1px solid #000000;
		               height    : auto;
		               max-width : 200px;
		               }';
		$html_data .= '#popup_thumbnail_name {
		               vertical-align : top; 
		               margin         : 0 10px 0 10px;
		               }';
		$html_data .= '#btn_gray_button {
		               background      : url("/static/image/btn_gray.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
		               color           : white;
		               display         : inline-block;
		               font-size       : 13px;
		               font-weight     : bold;
		               height          : 28px;
		               line-height     : 30px;
		               margin          : 0 15px;
		               text-align      : center;
		               text-decoration : none;
		               vertical-align  : middle;
		               width           : 80px;
		               }';
		$html_data .= '</style>';
		$html_data .= '</head>';
		$html_data .= '<body>';
		$html_data .= '<div style="display: inline-block;">';
		$html_data .= '<div class="problem_contents_title">'.$this->lang->line_or_def('common_exam2_problem_name','設問名').'</div>';
		$html_data .= '<div class="problem_contents_value">'.$exam2_problem_name.'</div>';
		$html_data .= '<div style="clear:both;"></div>';
		$html_data .= '</div>';
		$html_data .= '<div style="width:100%;"><hr style="border-top: 1px solid #bbb;"></div>';
		$html_data .= '<div style="display: inline-block;">';
		$html_data .= '<div class="problem_contents_title">'.$problem_contents_title.'</div>';
		$html_data .= '<div class="problem_contents_value">'.$problem_contents_value.'</div>';
		$html_data .= '<div style="clear:both;"></div>';
		$html_data .= '</div>';
		
		$html_data .= '<div style="width:100%;"><hr style="border-top: 1px solid #bbb;"></div>';
		$html_data .= '<div style="display: inline-block;">';
		$html_data .= '<div class="problem_contents_title">'.$problem_answer_title.'</div>';
		$html_data .= '<div class="problem_contents_value">'.$problem_answer_value.'</div>';
		$html_data .= '<div style="clear:both;"></div>';
		$html_data .= '</div>';

		$html_data .= '<div style="width:100%;"><hr style="border-top: 1px solid #bbb;"></div>';
		$html_data .= '<div style="display: inline-block;">';
		$html_data .= '<div class="problem_contents_title">'.$this->lang->line_or_def('common_exam2_answer_points','解答配点').'</div>';
		$html_data .= '<div class="problem_contents_value">'.$db_data['answer_point'].'</div>';
		$html_data .= '<div style="clear:both;"></div>';
		$html_data .= '</div>';

		$html_data .= '<div style="text-align:center;margin-top: 10px;">';
		$html_data .= '<a href="javascript:window.close();" title="close" id="btn_gray_button">';
		$html_data .= $this->lang->line_or_def('common_js_closeText','閉じる');
		$html_data .= '</a></div>';
		$html_data .= '</body></html>';
		
		// リターン
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('text/html; charset=utf-8');
		$this->output->set_output( $html_data );
	}





	//----------------------------------------------
	// 解答の概要CSVダウンロード
	//   $latest をオンにすることにより最新解答のみを対象とする
	//----------------------------------------------
	function answer_csv_download_summary($exam2_id = 0, $latest = 0){
		//モデル読み込み
		$this->load->model('model_exam2');
		$this->load->model('model_exam2_answer');
		
		// custom_fputcsv_helper
		$this->load->helper('custom_fputcsv_helper');
		
// CSVファイル名の作成 ---------------------------------------------------------------
		// 出力csvのファイル名（問題ID）
		$post_csv_filename = $exam2_id;

		// 問題テーブル取得処理
		$data_param = array(
						'exam2_id'   => $exam2_id,
					);
		$db_data = $this->model_exam2->get_exam2($data_param);

		if(count($db_data) > 0){
			// 問題名にファイル名に使用できない文字がある場合のための変換
			$post_csv_filename .= '_'.convert_FileName($db_data['exam2_name']);
			
		//	$post_csv_filename .= '_'.date('Ymd_His', strtotime($db_data['exam2_open']));   // 問題提出期限（前）
		//	$post_csv_filename .= '_'.date('Ymd_His', strtotime($db_data['exam2_close']));  // 問題提出期限（後）
		}
		if($latest == 0){
			$post_csv_filename .= '_'.$this->lang->line_or_def('common_summary','概要');
		}else{
			$post_csv_filename .= '_'.$this->lang->line_or_def('common_latest_summary','最新概要');
		}
		$post_csv_filename .= '.csv';
		
		// 設問の解答配点の最大値を取得
		$max_answer_point   = 0;        // 解答配点の合計
		$exam2_param = array(
				'school_id'  => $this->libauth->get_school_id(),
				'exam2_id'    => $exam2_id,
		);
		$max_answer_point = $this->model_exam2_answer->get_max_answer_point($exam2_param);



		// CSV１行目、項目名
//		$csv_header = array('解答日時', '解答回数', '最新解答', '受講者ID', '受講者名', '取得配点', '最大配点');
		$csv_header    = array();
		$csv_header[0] = $this->lang->line_or_def('common_exam2_answer_datetime',   '解答日時');
		$csv_header[1] = $this->lang->line_or_def('common_exam2_answer_no',         '解答回数');
		$csv_header[2] = $this->lang->line_or_def('common_latest_exam2_answer',     '最新解答');
		$csv_header[3] = $this->lang->line_or_def('common_student_id',             '受講者ID');
		$csv_header[4] = $this->lang->line_or_def('common_student_name',           '受講者名');
		$csv_header[5] = $this->lang->line_or_def('common_get_exam2_answer_points', '取得配点');
		$csv_header[6] = $this->lang->line_or_def('common_max_exam2_answer_points', '最大配点');
		
		// CSV２行目以降、データ取得
		$list_result = $this->model_exam2_answer->get_exam2_answer_list($data_param);
		//$table_data_count = $list_result['cnt'];
		$table_data = $list_result['items'];

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// csv出力
		
		// header 設定
		// ※API単体の場合、headerが必要なので記述。
		//$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		
		// MacOS 且つ FireFox以外は、文字エンコーディングを変更
		// librariesUser_agent.php, config/user_agents.php
		$this->load->library('user_agent');
		if( (!preg_match("/Firefox/i", $this->agent->browser())) && (!preg_match("/Mac/i", $this->agent->platform())) ){
			$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		}
		
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
		$check_student_id = ',-1,';
		foreach($table_data as $table_data_record){
			// 値取得
			$csv_data = array();
			
			$csv_data[0] = $table_data_record['answer_date'];         // 解答日時
			$csv_data[1] = $table_data_record['answer_no'];           // 解答回数
			$csv_data[2] = '';                                        // 最新解答
			$csv_data[3] = $table_data_record['answer_student_id'];   // 受講者ID
			$csv_data[4] = $table_data_record['answer_student_name']; // 受講者名
			$csv_data[5] = $table_data_record['answer_point_total'];  // 取得配点
			$csv_data[6] = $max_answer_point;                         // 最大配点
			
			// 最新解答フラグ（check_student_id にない場合は最新解答とする）
			if( !strpos($check_student_id, ",".trim($table_data_record['answer_student_id']).",") ){
				$csv_data[2] = 'New';
				$check_student_id .= $table_data_record['answer_student_id'].',';
			}
			
			if( ($latest!=0) && ($csv_data[2]=='') ) {
				// 最新の解答のみの場合は出力せず次レコードへ
				continue;
			}

			// 値変換
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_data);
			$csv = get_csv_format($csv_data, 'SJIS-WIN');
			print $csv."\r\n";
			
			ob_flush();
			flush();
		}
	}




	//----------------------------------------------
	// 解答の詳細CSVダウンロード
	//   $latest をオンにすることにより最新解答のみを対象とする
	//----------------------------------------------
	function answer_csv_download_detail($exam2_id = 0, $latest = 0){
		//モデル読み込み
		$this->load->model('model_exam2');
		$this->load->model('model_exam2_answer');
		
		// custom_fputcsv_helper
		$this->load->helper('custom_fputcsv_helper');
		
// CSVファイル名の作成 ---------------------------------------------------------------
		// 出力csvのファイル名（問題ID）
		$post_csv_filename = $exam2_id;

		// 問題テーブル取得処理
		$data_param = array(
						'exam2_id'   => $exam2_id,
					);
		$db_data = $this->model_exam2->get_exam2($data_param);

		if(count($db_data) > 0){
			// 問題名にファイル名に使用できない文字がある場合のための変換
			$post_csv_filename .= '_'.convert_FileName($db_data['exam2_name']);
			
		//	$post_csv_filename .= '_'.date('Ymd_His', strtotime($db_data['exam2_open']));   // 問題提出期限（前）
		//	$post_csv_filename .= '_'.date('Ymd_His', strtotime($db_data['exam2_close']));  // 問題提出期限（後）
		}
		if($latest == 0){
			$post_csv_filename .= '_'.$this->lang->line_or_def('common_detail','詳細');
		}else{
			$post_csv_filename .= '_'.$this->lang->line_or_def('common_latest_detail','最新詳細');
		}
		$post_csv_filename .= '.csv';

		// CSV１行目、項目名
	//	$csv_header = array('解答日時', '解答回数', '最新解答', '受講者ID', '受講者名', '設問ID', '設問名', '設問種類', '設問内容', '解答種類', '解答内容', '解答結果', '解答配点', '正解配点');
		$csv_header     = array();
		$csv_header[0]  = $this->lang->line_or_def('common_exam2_answer_datetime',  '解答日時');
		$csv_header[1]  = $this->lang->line_or_def('common_exam2_answer_no',        '解答回数');
		$csv_header[2]  = $this->lang->line_or_def('common_latest_exam2_answer',    '最新解答');
		$csv_header[3]  = $this->lang->line_or_def('common_student_id',            '受講者ID');
		$csv_header[4]  = $this->lang->line_or_def('common_student_name',          '受講者名');
		$csv_header[5]  = $this->lang->line_or_def('common_exam2_problem_id',       '設問ID');
		$csv_header[6]  = $this->lang->line_or_def('common_exam2_problem_name',     '設問名');
		$csv_header[7]  = $this->lang->line_or_def('common_problem_kind',          '設問種類');
		$csv_header[8]  = $this->lang->line_or_def('common_problem_contents',      '設問内容');
		$csv_header[9]  = $this->lang->line_or_def('common_answer_kind',           '解答種類');
		$csv_header[10] = $this->lang->line_or_def('common_answer_contents',       '解答内容');
		$csv_header[11] = $this->lang->line_or_def('common_exam2_answer_result',    '解答結果');
		$csv_header[12] = $this->lang->line_or_def('common_exam2_answer_points',    '解答配点');
		$csv_header[13] = $this->lang->line_or_def('common_correct_answer_points', '正解配点');

		// CSV２行目以降、データ取得
		$list_result = $this->model_exam2_answer->get_exam2_answer_list($data_param);
		//$table_data_count = $list_result['cnt'];
		$table_data = $list_result['items'];

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// csv出力
		
		// header 設定
		// ※API単体の場合、headerが必要なので記述。
		//$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		
		// MacOS 且つ FireFox以外は、文字エンコーディングを変更
		// librariesUser_agent.php, config/user_agents.php
		$this->load->library('user_agent');
		if( (!preg_match("/Firefox/i", $this->agent->browser())) && (!preg_match("/Mac/i", $this->agent->platform())) ){
			$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		}
		
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
		$check_student_id = ',-1,';
		foreach($table_data as $table_data_record){
			// 値取得
			$csv_data = array();
			
			$csv_data[0] = $table_data_record['answer_date'];         // 解答日時
			$csv_data[1] = $table_data_record['answer_no'];           // 解答回数
			$csv_data[2] = '';                                        // 最新解答
			$csv_data[3] = $table_data_record['answer_student_id'];   // 受講者ID
			$csv_data[4] = $table_data_record['answer_student_name']; // 受講者名
			$csv_data[5] = '';         // 設問ID
			$csv_data[6] = '';         // 設問名
			$csv_data[7] = '';         // 設問種類
			$csv_data[8] = '';         // 設問内容
			$csv_data[9] = '';         // 解答種類
			$csv_data[10] = '';         // 解答内容
			$csv_data[11] = '';         // 解答結果
			$csv_data[12] = '';        // 取得配点
			$csv_data[13] = '';        // 正解配点
			
			// 最新解答フラグ（check_student_id にない場合は最新解答とする）
			if( !strpos($check_student_id, ",".trim($table_data_record['answer_student_id']).",") ){
				$csv_data[2] = 'New';
				$check_student_id .= $table_data_record['answer_student_id'].',';
			}
			
			if( ($latest!=0) && ($csv_data[2]=='') ) {
				// 最新の解答のみの場合は出力せず次レコードへ
				continue;
			}

			// 解答 - 詳細の取得
			$param = array(
				'exam2_id'         => $exam2_id,
				'student_id'      => $table_data_record['answer_student_id'],
				'exam2_answer_no'  => $table_data_record['answer_no'],
			);
			$detail_result = $this->model_exam2_answer->get_exam2_answer_detail($param);
			//$exam2_answer_detail_count = $detail_result['cnt'];
			$exam2_answer_detail = $detail_result['items'];
/*
		       exam2_answer.exam2_answer_id        -- 解答ID
		      ,exam2_answer.exam2_id               -- 問題ID
		      ,exam2_answer.exam2_problem_id       -- 設問ID
		      ,exam2_answer.student_id            -- 受講者ID
		      ,exam2_answer.exam2_answer_no        -- 解答回数
		      ,exam2_answer.exam2_answer_contents  -- 解答内容
		      ,exam2_answer.exam2_answer_date      -- 解答日時
		      ,exam2_answer.exam2_answer_mark      -- 解答結果 0:不正解 1:正解
		      ,exam2_answer.exam2_answer_point     -- 解答配点
		      ,exam2_answer.marked_teacher_id     -- 採点講師ID
		      ,exam2_answer.status                -- 状態 0:有効 9:削除
		      ,exam2_answer.update_at             -- 更新日時
		      
	//	      ,exam2_problem.exam2_problem_name     -- 設問名
	//	      ,exam2_problem.problem_kind          -- 設問種類  1:テキスト、2:動画、3:図書室
	//	      ,exam2_problem.problem_contents      -- 設問内容
		      ,exam2_problem.answer_kind           -- 解答種類  1:単一形式、2:複数形式、3:フリー回答
		      ,exam2_problem.answer_contents       -- 解答内容 JSON型
		      ,exam2_problem.answer_point          -- 解答配点
*/
		//	$idx_detail = -1;
			if(isset($exam2_answer_detail)){
				foreach($exam2_answer_detail as $exam2_answer){
				//	$idx_detail++;
					
					// 配列初期化
					$csv_data_detail = $csv_data;
					
					// 設問ID
					$csv_data_detail[5] = $exam2_answer['exam2_problem_id'];
					
					// 設問名
					$csv_data_detail[6] = $exam2_answer['exam2_problem_name'];
					
					// 設問種類（1:テキスト、2:動画、3:図書室）
					if($exam2_answer['problem_kind']==1) $csv_data_detail[7] = $this->lang->line_or_def('common_text'         , 'テキスト');
					if($exam2_answer['problem_kind']==2) $csv_data_detail[7] = $this->lang->line_or_def('common_video'        , 'ビデオ');
					if($exam2_answer['problem_kind']==3) $csv_data_detail[7] = $this->lang->line_or_def('common_book_library' , '図書室');

					// 設問内容
					$temp = '';
					if($exam2_answer['problem_kind'] == 1){
						$temp = $exam2_answer['problem_contents'];
					}elseif($exam2_answer['problem_kind'] == 2){
						$this->load->model('model_video');
						$temp_id = $exam2_answer['problem_contents'];
						$db_data = $this->model_video->get_material(array('video_id' => $temp_id));
						$temp = "[".$temp_id."]".$db_data['video_logic_name'];
					}elseif($exam2_answer['problem_kind'] == 3){
						$this->load->model('model_book_library');
						$temp_id = $exam2_answer['problem_contents'];
						$db_data = $this->model_book_library->get_data(array('book_library_id' => $temp_id));
						$temp = "[".$temp_id."]".$db_data['book_library_logic_name'];
					}
					$csv_data_detail[8] = $temp;

					// 解答種類（1:単一形式、2:複数形式、3:フリー回答）
					if($exam2_answer['answer_kind']==1) $csv_data_detail[9] = $this->lang->line_or_def('common_single_forms' , '単一形式');
					if($exam2_answer['answer_kind']==2) $csv_data_detail[9] = $this->lang->line_or_def('common_plural_forms' , '複数形式');
					if($exam2_answer['answer_kind']==3) $csv_data_detail[9] = $this->lang->line_or_def('common_free_exam2_answer'  , 'フリー解答');

					// 解答内容 --- 解答種類が単一・複数の場合→設問テーブルより内容取得
					//              解答種類がテキストの場合→解答テーブルより内容取得
					$temp ='';
					if( ($exam2_answer['answer_kind']==1) || ($exam2_answer['answer_kind']==2) ){
						$array_exam2_answer_contents = explode(',', $exam2_answer['exam2_answer_contents']);      // 受講者が選択した番号
						$array_answer_contents      = obj2arr(json_decode( $exam2_answer['answer_contents'] )); // JSON型、選択した番号の文字列
						
						for($i=0; $i < count($array_answer_contents['answer_contents']); $i++) {
							$line_contents = (array)$array_answer_contents['answer_contents'][$i];
							// 選択した番号と一致した内容を出力
							if( in_array( $line_contents['no'], $array_exam2_answer_contents) ){
								$line_contents['word'] = $line_contents['word'];
							
								if($temp != '') $temp .= "\n----- -----\n";
								$temp .= $line_contents['word'];
							}
							//$answer_contents_no[$i]      = $line_contents['no'];
							//$answer_contents_word[$i]    = $line_contents['word'];
							//$answer_contents_correct[$i] = $line_contents['correct'];
						}
					}elseif($exam2_answer['answer_kind']==3){
						$temp = $exam2_answer['exam2_answer_contents'];
					}
					$csv_data_detail[10]  = $temp;

					// 解答結果（0:不正解 1:正解）
					if($exam2_answer['exam2_answer_mark']==0) $csv_data_detail[11] = $this->lang->line_or_def('common_non_correct_answer', '不正解');
					if($exam2_answer['exam2_answer_mark']==1) $csv_data_detail[11] = $this->lang->line_or_def('common_correct_answer'  , '正解');

					// 取得配点（解答結果による配点）
					$csv_data_detail[12]= $exam2_answer['exam2_answer_point'];

					// 解答配点（設問に設定されている配点）
					$csv_data_detail[13] = $exam2_answer['answer_point'];

					// 値変換
					mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_data_detail);
					$csv = get_csv_format($csv_data_detail, 'SJIS-WIN');
					print $csv."\r\n";
					
					ob_flush();
					flush();
				}
			}
		}
	}

	//----------------------------------------------
	//問題管理の権限有無確認
	//  ログインユーザに該当課題の修正・削除権限があるかを確認
	//  Super User：修正・削除ＯＫ
	//  学校管理者：同学校内のみ、修正・削除ＯＫ
	//  一般講師　：自分が管理講師の問題 + 課題管理の権限あり の場合、修正・削除ＯＫ
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
			// 講師、且つ、問題管理
			
			// 問題の再取得
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
	// 問題（テスト） - 通知対応
	//----------------------------------------------
	function exam2_queue_remind($param) {
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
							'data'    => array(),
						),
						$param
					);
		$data = $param['data'];
		
		$this->load->helper('json');
		$this->load->model('model_queue');
	
		// 同問題の通知の無効化
		$this->model_queue->update_queue_deleted_type_kind_status(array(
			'queue_type'     => 'exam2',
			'queue_type_id'  => $param['exam2_id'],
			'queue_kind'     => 'mail',
			'queue_status'   => 'WAITING',
		));

		// 通知時間がある場合のみ、実行
		if( $data['exam2_remind_time'] ){
			
			// 通知内URLの構築（開発・ステは常にSSLなし。本番の場合は管理画面URLから判定）
			//   'development' 'testing' 'production'
			$show_url = $this->config->item('domain_follower').'/exam2/index/'.$param['exam2_id'];
			if(getenv('ENVIRONMENT') == 'production'){
				if(empty($_SERVER['HTTPS'])){
					$show_url = 'http://'.$show_url;
				}else{
					$show_url = 'https://'.$show_url;
				}
			}else{
				$show_url = 'http://'.$show_url;
			}
			
			// 通知Fromメールの構築
			//'no_reply@ email.d2csol.co.jp',
			$from_addr = 'no_reply@'.$_SERVER["SERVER_NAME"];
			
			// 通知時間のループ
			foreach($data['exam2_remind_time'] as $ino => $remind_time){
				
				// 送信日時の検出	'Y-m-d H:i:s'
				$string_sub = " -1 hour";
				$show_time  = "1".$this->lang->line_or_def('common_hours_ago','時間前');
				switch ( $data['exam2_remind_unit'][$ino] ){
				case 'month':
					$string_sub = ' -'.$remind_time.' month';
					$show_time  = $remind_time.$this->lang->line_or_def('common_month_ago','ヶ月前');
					break;
				case 'week':
					$string_sub = ' -'.$remind_time.' week';
					$show_time  = $remind_time.$this->lang->line_or_def('common_weeks_ago','週間前');
					break;
				case 'day':
					$string_sub = ' -'.$remind_time.' day';
					$show_time  = $remind_time.$this->lang->line_or_def('common_days_ago','日前');
					break;
				case 'hour':
					$string_sub = ' -'.$remind_time.' hour';
					$show_time  = $remind_time.$this->lang->line_or_def('common_hours_ago','時間前');
					break;
				case 'minute':
					$string_sub = ' -'.$remind_time.' minute';
					$show_time  = $remind_time.$this->lang->line_or_def('common_minutes_ago','分前');
					break;
				default:
					$string_sub = ' -1 hour';
					$show_time  = "1".$this->lang->line_or_def('common_hours_ago','時間前');
				}
				//$queue_execute_time = date('Y-m-d H:i:s', strtotime($data['exam2_close'] . $string_sub));
				$this->load->helper('string_datetime_helper');
				$queue_execute_time = get_search_datetime($data['exam2_close'], $string_sub);

				// 問題（テスト）終了日時の修正
				//$this->load->helper('string_datetime_helper');
				$format  = $this->lang->line_or_def('common_format_year_month_day_week' , '{YEAR}年{MONTH}月{DAY}日（{WEEK}）')."{HOUR}:{MINUTE}:{SECOND}";
				$args_show_close_date = get_format_datetime($data['exam2_close'], $format);
				
				// 種類詳細の作成（JSON型）
				$queue_kind_detail_array = array();
				$queue_kind_detail_array['exam2_id']                = $param['exam2_id'];
				$queue_kind_detail_array['mail_format']            = "exam2_reminder_before";
				$queue_kind_detail_array['from_addr']              = $from_addr;
				$queue_kind_detail_array['args']                   = array();
				$queue_kind_detail_array['args']['SHOW_TIME']      = $show_time;
				$queue_kind_detail_array['args']['TIME']           = $remind_time;
				$queue_kind_detail_array['args']['UNIT']           = $data['exam2_remind_unit'][$ino];
				$queue_kind_detail_array['args']['SHOW_CLOSE_TIME'] = $args_show_close_date;
				$queue_kind_detail_array['args']['SHOW_URL']       = $show_url;
				
				$queue_kind_detail_json = json_encode($queue_kind_detail_array);

				// 通知登録
				$this->model_queue->insert_queue(array(
					//'queue_id'
					'queue_type'          => 'exam2',
					'queue_type_id'       => $param['exam2_id'],
					'queue_kind'          => 'mail',
					'queue_kind_detail'   => $queue_kind_detail_json,
					'queue_execute_time'  => $queue_execute_time,
					'queue_status'        => 'WAITING',
					//'added_at'
					//'update_at'
				));
			}
		}
		
		return true;
	}



	function exam2_set_list(){
		$this->form_validation->set_rules('product_id' , $this->lang->line_or_def('common_product_id','product_id') , 'trim|xss_clean');
		$this->form_validation->set_rules('exam2_id' , $this->lang->line_or_def('common_exam2_id','exam2_id') , 'trim|xss_clean');
		$this->form_validation->run();

		if ( !$this->input->post() ){
			$data = $this->session->userdata('exam2_review_search_cond') ?: array(
				's_cource' => '',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_cource']    = ($this->input->post('s_cource', TRUE) ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('exam2_review_search_cond', $data);
		}

		$param = array(
			'product_id'=>$product_id,
			'exam2_id'=>$exam2_id,
		);
		$this->load->model('model_exam2_export');
		$export_data = $this->model_exam2_export->get_exam2_answer_set_list( $param );
		$export_data_review = $this->model_exam2_export->get_exam2_answer_set_list_review( $param );
		//var_dump($param);

		// データ
		$arr_data = array();
		$row = array();
		$row[] = "氏名";
		$row[] = "登録番号";
		$row[] = "会員区分";
		$row[] = "所属弁護士会";
		for($i2=0;$i2<count($export_data[0]["exam2_problem"]);$i2++){
			$row[] = $export_data[0]["exam2_problem"][$i2];
		}
		$arr_data[] = $row;
		for($i2=0;$i2<count($export_data[0]["student"]);$i2++){
			$row = array();
			if( count($export_data[0]["student"][$i2]["info"])>0 ){
				$row[] = $export_data[0]["student"][$i2]["info"][0]["student_name"];
				$row[] = $export_data[0]["student"][$i2]["info"][0]["lawyer_number"];
				$row[] = $export_data[0]["student"][$i2]["info"][0]["lawyer_division"];
				$row[] = $export_data[0]["student"][$i2]["info"][0]["bar_association_id"];
			} else {
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
			}
			for($i3=0;$i3<count($export_data[0]["student"][$i2]["answer"]);$i3++){
				$row[] = $export_data[0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"];
			}
			$arr_data[] = $row;
		}
		//var_dump($arr_data);
		$data["export_data"] = $export_data;
		$data["export_data_review"] = $export_data_review;
		$data["answer_list"] = $arr_data;
		$data["product_id"]  = $product_id;
		$data["exam2_id"]    = $exam2_id;

		// 更新回数の取得
		$sql = "SELECT update_count";

		// load language
		$this->lang->load('common');
		//ビュー設定引数設定
		$disp_param = array(
			'view_name'   => 'cms_exam2/answer_set_list',
			'submenu_idx' => 2,
			'view_data'   => $data,
		);
		//ビュー設定
		$this->_display_view($disp_param);
	}

	function exam2_set_answer(){
		$arr_param = $_POST;

		$product_id ="";
		$exam2_id ="";
		$student_ids =array();

		if( isset($arr_param["product_id"]) ){
			$product_id = $arr_param["product_id"];
		}
		if( isset($arr_param["exam2_id"]) ){
			$exam2_id = $arr_param["exam2_id"];
		}
		if( isset($arr_param["student_id"]) ){
			$student_ids = $arr_param["student_id"];
		}
		

		try{ 
			$sql  = '';
			$sql  = 'UPDATE exam2_answer SET open_review=0 WHERE exam2_id=? AND product_id=?';
			# Query実行
			$query = $this->db->query($sql, array(
					$exam2_id,
					$product_id,
				)); 

			if( count($student_ids)>0 ){
				$sql  = '';
				$sql  = 'UPDATE exam2_answer SET open_review=1 WHERE exam2_id=? AND product_id=? AND student_id IN (' .implode(",",$student_ids). ') ';
				# Query実行
				$query = $this->db->query($sql, array(
						$exam2_id,
						$product_id,
					)); 
			}
		}catch(Exception $e){ 
		}
		exit();
	}

	function update_exam2_answer_problem(){
		$arr_param = $_POST;
		$exam2_answer_id ="";
		$exam2_answer_value ="";
		$update_count = 0;
		$res = array( "msg"=>'', "count"=>'' );
		$res["msg"] = '';
		$res["count"] = 0;
		
		if( isset($arr_param["exam2_answer_id"]) ){
			$exam2_answer_id = $arr_param["exam2_answer_id"];
		}
		if( isset($arr_param["exam2_answer_value"]) ){
			$exam2_answer_value = $arr_param["exam2_answer_value"];
		}
		if( isset($arr_param["update_count"]) ){
			$update_count = $arr_param["update_count"];
		}

		try{
			// 受講者側で更新処理がされた場合は、エラーメッセージを表示する
			$sql  = '';
			$sql  = "SELECT update_count FROM exam2_answer WHERE exam2_answer_id=?";
			# Query実行
			$query = $this->db->query($sql, array(
					$exam2_answer_id,
				));
			if ($query->num_rows() > 0){
				$arr_exam2_answer = $query->row_array();
				if($update_count !== $arr_exam2_answer['update_count']){
					//echo json_encode('update_count_error');
					$res["msg"] = 'update_count_error';
					$res["count"] = $update_count;
					echo json_encode($res);
					exit;
				}
			}

			$sql  = '';
			$sql  = "UPDATE exam2_answer SET exam2_answer_contents_old=exam2_answer_contents WHERE exam2_answer_id=? AND exam2_answer_contents<>? AND exam2_answer_contents_old=''";
			# Query実行
			$query = $this->db->query($sql, array(
					$exam2_answer_id,
					$exam2_answer_value,
				)); 

			$sql  = '';
			$sql  = 'UPDATE exam2_answer SET exam2_answer_contents=?, update_count=(update_count+1) WHERE exam2_answer_id=? ';
			# Query実行
			$query = $this->db->query($sql, array(
					$exam2_answer_value,
					$exam2_answer_id,
				)); 
			$res["msg"] = 'OK';
			$res["count"] = $arr_exam2_answer['update_count']+1;
			echo json_encode($res);
			exit;
		}catch(Exception $e){ 
		}
		exit();
	}

	function update_exam2_answer_review_contents(){
		$arr_param = $_POST;

		$student_id ="";
		$exam2_id ="";
		$product_id ="";
		$exam2_answer_review_contents ="";

		if( isset($arr_param["student_id"]) ){
			$student_id = $arr_param["student_id"];
		}
		if( isset($arr_param["exam2_id"]) ){
			$exam2_id = $arr_param["exam2_id"];
		}
		if( isset($arr_param["product_id"]) ){
			$product_id = $arr_param["product_id"];
		}
		if( isset($arr_param["exam2_answer_review_contents"]) ){
			$exam2_answer_review_contents = $arr_param["exam2_answer_review_contents"];
		}

		try{
			$sql  = '';
			$sql  = 'UPDATE exam2_answer_review SET status=9 WHERE student_id=? AND exam2_id=? AND product_id=?';
			# Query実行
			$query = $this->db->query($sql, array(
					$student_id,
					$exam2_id,
					$product_id,
				));

			$sql  = '';
			$sql  = 'INSERT INTO exam2_answer_review (student_id, exam2_id, product_id, exam2_answer_review_contents, create_at) VALUES (?, ?, ?, ?, ?)';
			# Query実行
			$query = $this->db->query($sql, array(
					$student_id,
					$exam2_id,
					$product_id,
					$exam2_answer_review_contents,
					date('Y-m-d H:i:s'),
				));
		}catch(Exception $e){ 
		}
		exit();
	}

} 

/*End of File program.php*/
