<?php
#[AllowDynamicProperties]
class Cms_exam2_download extends CI_Controller {
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
		
		$data['exam2_problem']['exam2_problem_lectures'] = array();  // 所属講座群

		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_exam2_download/edit',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
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
				$data['teachers'][$teacher['teacher_id']] = htmlspecialchars($teacher['teacher_name'], ENT_QUOTES, 'UTF-8');
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
		$product_code = $this->input->post('product_code');
		$product_name = $this->input->post('product_name');
//var_dump($product_name);
		$this->load->model('model_exam2_export');
		$export_data = $this->model_exam2_export->get_exam2_problem_export_data( $exam2_problem_lectures_ex,$product_code,$product_name );
//var_dump($export_data);
//exit();
		
		$fileName = 'exam2_export_'.date('YmdHis').'.csv';
		$fileName =  mb_convert_encoding($fileName, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$fileName");
		
		$fp = fopen('php://output', 'w');
		
		// ヘッダ
		$_headClum = array(
			'商品コード',
			'商品名（講座名）',
			'氏名',
			'登録番号',
			'会員区分',
			'所属弁護士会',
		);
		//mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $_headClum);
		//fputcsv($fp, $_headClum);
		
		// データ
		$data = array();
		for($i1=0;$i1<count($export_data);$i1++){
			$row = array();
			$exam2_problem_ids = array();
			$row[] = "商品ID";
			$row[] = "商品コード";
			$row[] = "商品名（講座名）";
			$row[] = "氏名";
			$row[] = "登録番号";
			$row[] = "会員区分";
			$row[] = "所属弁護士会";
			for($i2=0;$i2<count($export_data[$i1]["exam2"][0]["exam2_problem"]);$i2++){
				$exam2_problem_ids[] = $export_data[$i1]["exam2"][0]["exam2_problem"][$i2]["exam2_problem_id"];
				$row[] = $export_data[$i1]["exam2"][0]["exam2_problem"][$i2]["exam2_problem_name"];
			}
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $row);
			$data[] = $row;
			fputcsv($fp, $row);
			
			for($i2=0;$i2<count($export_data[$i1]["exam2"][0]["student"]);$i2++){
				$row = array();
				$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["product_id"];
				$row[] = $export_data[$i1]["product_code"];
				$row[] = $export_data[$i1]["product_name"];
				if( count($export_data[$i1]["exam2"][0]["student"][$i2]["info"])>0 ){
					$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["info"][0]["student_name"];
					$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["info"][0]["lawyer_number"];
					$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["info"][0]["lawyer_division"];
					$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["info"][0]["bar_association_id"];
				} else {
					$row[] = "";
					$row[] = "";
					$row[] = "";
					$row[] = "";
				}
				for($i3=0;$i3<count($export_data[$i1]["exam2"][0]["student"][$i2]["answer"]);$i3++){
					if( trim($export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents_old"])!="" ){
						if( isset($export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"]) ){
							$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"]."[修正前:".$export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents_old"]."]";
						} else {
							$row[] = "";
						}
					} else {
						if( isset($export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"]) ){
							$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["answer"][$i3]["exam2_answer_contents"];
						} else {
							$row[] = "";
						}
					}
				}
				if( 0<count($export_data[$i1]["exam2"][0]["student"][$i2]["answer"]) ){
					$row[] = $export_data[$i1]["exam2"][0]["student"][$i2]["answer"][0]["exam2_answer_date"];
				}
				mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $row);
				$data[] = $row;
				fputcsv($fp, $row);
			}
		}
		fclose($fp);
	}
} 

/*End of File program.php*/
