<?php
class Model_outside_gingerapp extends CI_Model
{
	//----------------------------------------------
	//共通
	//----------------------------------------------
	private $_elm_logon_common = 'al_';

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct(){
		parent::__construct();
	}

	//----------------------------------------------
	// eLearning Manager APIの整合性確認
	// request  : api_key, api_url
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function auth_check($param)
	{
		$param = array_merge(
						array(
							'api_key'   => '',
							'api_url'   => '',
						),
						$param
					);
		
		// URL整形
		$api_url = $param['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/auth_check.cfm';

		// Request整形
		$api_param['api_key'] = $param['api_key'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager コース削除
	// request  : school_id, cource_id
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function course_delete($param)
	{
		$param = array_merge(
						array(
							'shcool_id' => 0,
							'cource_id' => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/course_delete.cfm';

		// Request整形
		$api_param['api_key']   = $school_contract['api_key'];
		$api_param['course_id'] = $param['cource_id'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}
	
	//----------------------------------------------
	// eLearning Manager コース登録
	// request  : school_id, cource_id, cource_name, cource_caption
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function course_insert($param)
	{
		$param = array_merge(
						array(
							'shcool_id'      => 0,
							'cource_id'      => '',
							'cource_name'    => '',
							'cource_caption' => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/course_insert.cfm';

		// Request整形
		$api_param['api_key']      = $school_contract['api_key'];
		$api_param['course_id']    = $param['cource_id'];
		$api_param['course_name']  = $param['cource_name'];
		$api_param['course_guide'] = $param['cource_caption'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager コース更新
	// request  : school_id, cource_id, cource_name, cource_caption, (appear)
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function course_update($param)
	{
		$param = array_merge(
						array(
							'shcool_id'      => 0,
							'cource_id'      => '',
							'cource_name'    => '',
							'cource_caption' => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/course_update.cfm';

		// Request整形
		$api_param['api_key']      = $school_contract['api_key'];
		$api_param['course_id']    = $param['cource_id'];
		$api_param['course_name']  = $param['cource_name'];
		$api_param['course_guide'] = $param['cource_caption'];
	//	$api_param['appear']       = $param['cource_caption'];  // 0:非公開、1:公開（eLM管理画面にて行うため設定せず）
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager ユーザ削除
	// request  : school_id, student_id, student_email
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function user_delete($param)
	{
		$param = array_merge(
						array(
							'shcool_id'				=> 0,
							'student_id'			=> '',
							'student_email'	=> '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/user_delete.cfm';

		// Request整形
		$api_param['api_key'] = $school_contract['api_key'];
		$api_param['logon']   = $param['student_email'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager ユーザ登録
	// request  : school_id, student_id, student_name, student_email
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function user_insert($param)
	{
		$param = array_merge(
						array(
							'shcool_id'     => 0,
							'student_id'    => '',
							'student_name'  => '',
							'student_email' => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/user_insert.cfm';

		// 受講者姓名の分割（先頭から空白を選択、その前後で分割）
		$temp_student_name = $this->_convert_student_name(
			array(
				'student_name'	=> $param['student_name'],
			)
		);

		// Request整形
		$api_param['api_key']     = $school_contract['api_key'];
		$api_param['logon']       = $param['student_email'];		//$this->_elm_logon_common.$param['student_id'];
		$api_param['password']    = '';
		$api_param['last_name']   = $temp_student_name['last_name'];
		$api_param['first_name']  = $temp_student_name['first_name'];
		$api_param['mailaddress'] = $param['student_email'];

		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager ユーザ更新
	// request  : school_id, student_id, student_name, student_email, before_student_email
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//----------------------------------------------
	public function user_update($param)
	{
		$param = array_merge(
						array(
							'shcool_id'				=> 0,
							'student_id'			=> '',
							'student_name'			=> '',
							'student_email'			=> '',
							'before_student_email'	=>'',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/user_update.cfm';

		// 受講者姓名の分割（先頭から空白を選択、その前後で分割）
		$temp_student_name = $this->_convert_student_name(
			array(
				'student_name'	=> $param['student_name'],
			)
		);

		// Request整形
		$api_param['api_key']     = $school_contract['api_key'];
		$api_param['orig_logon']  = $param['before_student_email'];
		$api_param['logon']       = $param['student_email'];
	//	$api_param['password']    = '';
		$api_param['last_name']   = $temp_student_name['last_name'];
		$api_param['first_name']  = $temp_student_name['first_name'];
		$api_param['mailaddress'] = $param['student_email'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager 該当コースのユーザ割当削除
	// request  : school_id, cource_id
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : dat[job_id => ***] ※*** = eLMより発行されるジョブID
	//----------------------------------------------
	public function assign_delete($param)
	{
		$param = array_merge(
						array(
							'shcool_id'     => 0,
							'cource_id'     => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/assign_delete.cfm';

		// Request整形
		$api_param['api_key']   = $school_contract['api_key'];
		$api_param['course_id'] = $param['cource_id'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager 該当コースのユーザ割当
	// request  : school_id, cource_id, student_id(カンマ区切), student_email(カンマ区切)
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : dat[job_id => ***] ※*** = eLMより発行されるジョブID
	//----------------------------------------------
	public function assign_insert($param)
	{
		$param = array_merge(
						array(
							'shcool_id'     => 0,
							'cource_id'     => '',
							'student_id'    => '',
							'student_email' => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/assign_insert.cfm';

		// Request整形
		$api_param['api_key']    = $school_contract['api_key'];
		$api_param['course_id']  = $param['cource_id'];
		$api_param['logons']     = $param['student_email'];
		
//		$convert_student_id = '';
//		if(empty($param['student_id'])){
//			$convert_student_id = '';
//		}else{
//			$array_id = explode(",", $param['student_id']);
//			for ($i = 0; $i< count($array_id); $i++) {
//				$array_id[$i] = $this->_elm_logon_common.$array_id[$i];
//			}
//			$convert_student_id = implode(",", $array_id);
//		}
//		$api_param['logons'] = $convert_student_id;

		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager 該当コースのユーザ割当削除（ユーザベース）
	// request  : school_id, student_id, student_email
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : dat[job_id => ***] ※*** = eLMより発行されるジョブID
	//----------------------------------------------
	public function assign_delete_user($param)
	{
		$param = array_merge(
						array(
							'school_id'		=> 0,
							'student_id'	=> 0,
							'student_email'	=> '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/assign_delete_user.cfm';

		// Request整形
		$api_param['api_key']    = $school_contract['api_key'];
		$api_param['logon']      = $param['student_email'];

		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager 該当コースのユーザ割当（ユーザベース）
	// request  : school_id, student_id, student_email, cource_id(カンマ区切)
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : dat[job_id => ***] ※*** = eLMより発行されるジョブID
	//----------------------------------------------
	public function assign_insert_user($param)
	{
		$param = array_merge(
						array(
							'school_id'		=> 0,
							'student_id'	=> 0,
							'student_email'	=> '',
							'cource_id'		=> '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/assign_insert_user.cfm';

		// Request整形
		$api_param['api_key']    = $school_contract['api_key'];
		$api_param['logon']      = $param['student_email'];
		$api_param['course_ids'] = $param['cource_id'];

		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// eLearning Manager ログイン認証
	// request  : school_id, session_id, back_url, lang_code
	// response : 画面遷移のため必要パラメータを返す ⇒ 受講者側にPOST URLを送る形を作成する予定
	//          : api_url    : DB登録済みApi Key
	//          : api_key    : DB登録済みApi Url
	//          : session_id : AL側セッションID
	//          : back_url   : 戻り先画面URL（AL受講者画面トップのURL）
	//          : lang_code  : ja 固定
	//----------------------------------------------
	public function login($param)
	{
		$param = array_merge(
						array(
							'shcool_id'   => 0,
							'session_id'  => '',
							'back_url'    => '',
							'lang_code'   => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/login.cfm';

		// Request整形
		$api_param['api_url']    = $api_url;
		$api_param['api_key']    = md5($school_contract['api_key'].$param['session_id']);		//strtoupper
		$api_param['session_id'] = $param['session_id'];
		$api_param['back_url']   = $param['back_url'];		// urlencode
		$api_param['lang_code']  = $param['lang_code'];
		
		return $api_param;
	}

	//----------------------------------------------
	// eLearning Manager ジョブの処理状態の問い合わせ
	// request  : school_id, job_ids
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : [dat][statuses][*** => 'published' or 'not published']
	//                      ※*** = 問い合わせしたジョブID
	//----------------------------------------------
	public function job_state($param)
	{
		$param = array_merge(
						array(
							'shcool_id'     => 0,
							'job_ids'       => '',
						),
						$param
					);

		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param(
			array(
				'school_id'	=> $param['school_id'],
			)
		);
		
		// URL 整形
		$api_url = $school_contract['api_url'];
		$api_url = preg_replace('/\/$/', '', $api_url);
		if($api_url != '') $api_url = $api_url.'/eLM/api/job_state.cfm';

		// Request整形
		$api_param['api_key'] = $school_contract['api_key'];
		$api_param['job_ids'] = $param['job_ids'];
		
		// eLearning Manager API 実行
		$elm_contract = $this->_practice_of_api($api_url, $api_param);

		return $elm_contract;
	}

	//----------------------------------------------
	// [private] 学校．契約形態．外部連携情報取得
	// request  : school_id
	// response : api_key : DB登録済みApi Key
	//          : api_url : DB登録済みApi Url
	//----------------------------------------------
	function _get_school_contract_param($param)
	{
		$param = array_merge(
						array(
							'school_id' => 0,
						),
						$param
					);
		$result['api_key'] = '';
		$result['api_url'] = '';
		
		// Load Models
		$this->load->model('model_school_manage');
		
		// 学校情報取得
		$db_data = $this->model_school_manage->get_school($param);
		
		if(count($db_data) > 0){
			$this->load->helper('json');
			$contract_param = obj2arr(json_decode($db_data['contract_param']));
			if(isset($contract_param['outside_elearningmanager'])){
				if(isset($contract_param['outside_elearningmanager']['api_key'])){
					$result['api_key'] = urldecode($contract_param['outside_elearningmanager']['api_key']);
				}
				if(isset($contract_param['outside_elearningmanager']['api_url'])){
					$result['api_url'] = urldecode($contract_param['outside_elearningmanager']['api_url']);
				}
			}
		}
		return $result;
	}

	//----------------------------------------------
	// [private] API 実行
	// request  : api_url, api_param[]
	// response : result  : true(1) or false(0)
	//          : stat    : eLM API Status Code
	//          : message : Result Message
	//          : dat     : datがある場合のみ付加
	//----------------------------------------------
	function _practice_of_api($api_url, $api_param)
	{
		$this->lang->load('error');
	
		$result['result']  = 0;
		$result['stat']    = 901;
		$result['message'] = $this->lang->line_or_def('error_elm_api_901','Api URLが未入力のためAPIが実行できません');

		if($api_url == '') return $result;

		$this->load->helper('json');
		$this->load->library('Curl');
		$content  = $this->curl->simple_post($api_url, $api_param);
	//	$this->curl->debug();
	//	$this->curl->debug_request();
		
		$elm_data = obj2arr(json_decode($content));
		
		if($elm_data['stat'] == 200){
			$result['result']  = 1;
			$result['stat']    = $elm_data['stat'];
			$result['message'] = $this->_convert_message($elm_data);

			if(isset($elm_data['dat'])){
				$result['dat'] = $elm_data['dat'];
			}
		}else{
			$result['result']  = 0;
			$result['stat']    = $elm_data['stat'];
			if(empty($result['stat'])) $result['stat'] = 999;
			$result['message'] = $this->_convert_message($elm_data);
		}
		
		return $result;
	}

	//----------------------------------------------
	// [private] convert eLearning Message
	// request  : param[]
	// response : Result Message
	//----------------------------------------------
	function _convert_message($param){
		$this->lang->load('error');
		
		$stat    = $param['stat'];
		$message = $this->lang->line_or_def('error_elm_api_999','システムエラーです');
		
		if($stat == 200) $message = 'OK';																		// エラーなし
		if($stat == 400) $message = $this->lang->line_or_def('error_elm_api_400','APIの引数に不備があります');	// API引数エラー
		if($stat == 401) $message = $this->lang->line_or_def('error_elm_api_401','API認証に失敗しました');		// API認証エラー
		if($stat == 421) $message = $this->lang->line_or_def('error_elm_api_421','既に登録済みのユーザです');	// 登録済みユーザ
		if($stat == 422) $message = $this->lang->line_or_def('error_elm_api_422','未登録のユーザです');			// 未登録ユーザ
		if($stat == 423) $message = $this->lang->line_or_def('error_elm_api_423','既に登録済みのコースです');	// 登録済みコース
		if($stat == 424) $message = $this->lang->line_or_def('error_elm_api_424','未登録のコースです');			// 未登録コース
		
		return $message;
	}

	//----------------------------------------------
	// [private] Convert Student Name
	// request  : param[]
	// response : last_name  : 
	//          : first_name : 
	//----------------------------------------------
	function _convert_student_name($param){
		$param = array_merge(
						array(
							'student_name' => '',
						),
						$param
					);
		$result['last_name']  = '';
		$result['first_name'] = '';
		
		// 文字列長ゼロならば終了
		if(strlen($param['student_name'])==0){
			return $result;
		}
		
		// 半角空白を全角空白に変換
		$temp_student_name = $param['student_name'];
		$temp_student_name = preg_replace('/\s/i', '　', $temp_student_name);

		
		// 全角空白有無確認
		$split_area = mb_strpos($temp_student_name, '　');
		if( $split_area === false ){
			$result['last_name']  = $param['student_name'];
			$result['first_name'] = '　';
		}else{
			$result['last_name']  = mb_substr($param['student_name'], 0, $split_area);
			$result['first_name'] = mb_substr($param['student_name'], $split_area + 1, 1000);
		}
		
		return $result;
	}
}
?>
