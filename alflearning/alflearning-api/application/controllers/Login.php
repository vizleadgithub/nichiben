<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Login extends CI_Controller {
	var $ci;
	function __construct() {
		parent::__construct();

		$this->load->driver('cache');

		$this->ci =& get_instance();

		$this->load->database();

//		$this->allowIP = $this->config->item('api_allow_ips');	//IPアドレス制限　デフォルトで有効（指定すると上書き可能）
	}


	//----------------------------------------------
	// 受講者情報取得（セッションID使用）
	//----------------------------------------------
	private function getStudentData($sessionId = ''){
		// システム日時取得
		$system_date = date('Y-m-d H:i:s');
		// セッションIDより受講者ID取得
		try{ 
			$query = $this->db->query(
				' SELECT ci_sessions.*'.
				' FROM ci_sessions'.
				' WHERE 1=1'.
				'   AND ci_sessions.id   = ? '.
				'   AND (timestamp + 72000) >= ? '.
				' LIMIT 0, 1',
				array(
					$sessionId,
					$system_date,
				)
			);
			$session = $query->row_array();
			if(!$session){
				return false;
			}
			$userdata = unserialize($session['user_data']);
			
			if(!isset($userdata['student_id']) || !$userdata['student_id']){
				return false;
			}
		}catch(Exception $e){ 
			return false;
		}

		$cacheKey = "_alflearning_api_login_getStudentData-{$userdata['student_id']}";
		if( $tags = $this->cache->memcached->get($cacheKey) ){
//print "get mem[{$cacheKey}]\n";
			return $tags;
		}
//print "get db[{$cacheKey}]\n";

		// 受講者IDより受講者情報の取得
		try{ 
			$query = $this->db->query(
				' SELECT student.* '.
				' FROM student '.
				' WHERE 1=1 '.
				'   AND student.student_id = ? '.
				' LIMIT 0, 1 ',
				array(
					$userdata['student_id'],
				)
			);
			$student = $query->row_array();
			$this->cache->memcached->save($cacheKey, $student, $this->config->item('memcached_time'));

			return $student;
		}catch(Exception $e){ 
			return false;
		}
	}

	//----------------------------------------------
	// 学校情報取得（学校ID使用）
	//----------------------------------------------
	private function getSchoolData($school_id = 0){
		$cacheKey = "_alflearning_api_login_getSchoolData-{$school_id}";

		if( $tags = $this->cache->memcached->get($cacheKey) ){
//print "get mem[{$cacheKey}]\n";
			return $tags;
		}
//print "get db[{$cacheKey}]\n";

		// 学校IDより学校情報の取得
		try{ 
			$query = $this->db->query(
				' SELECT school.* '.
				'   FROM school '.
				'  WHERE 1 =1 '.
				'    AND school.school_id = ? '.
				'    AND school.status    = 0 '.
				'  LIMIT 0, 1 ',
				array(
					$school_id,
				)
			);
			$school = $query->row_array();
			$this->cache->memcached->save($cacheKey, $school, $this->config->item('memcached_time'));

			return $school;
		}catch(Exception $e){ 
			return false;
		}
	}

	//----------------------------------------------
	// 講座情報取得（受講者ID・学校ID使用）
	//----------------------------------------------
	private function getCourceData($student_id = 0, $school_id = 0){
		$cacheKey = "_alflearning_api_login_getCourceData-{$student_id}-{$school_id}";

		if( $tags = $this->cache->memcached->get($cacheKey) ){
//print "get mem[{$cacheKey}]\n";
			return $tags;
		}
//print "get db[{$cacheKey}]\n";

		// システム日時取得
		$system_date = date('Y-m-d H:i:s');

		// 受講者ID・学校IDより講座情報の取得
		try{ 
			$query = $this->db->query(
				' SELECT cource.* '.
				'   FROM cource INNER JOIN student_lecture ON student_lecture.cource_id = cource.cource_id '.
				'  WHERE 1=1 '.
				'    AND cource.status = 0 '.
				'    AND ? BETWEEN cource.cource_open AND cource.cource_close '.
				'    AND student_lecture.student_id = ? '.
				'    AND cource.school_id = ? ',
				array(
					$system_date,
					$student_id,
					$school_id,
				)
			);
			$cource = $query->result_array();
			$this->cache->memcached->save($cacheKey, $cource, $this->config->item('memcached_time'));

			return $cource;
		}catch(Exception $e){ 
			return false;
		}
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		// 変数初期化
		$output        = array();
		$output_status = array(
			'student'	=>	-1,
			'school'	=>	-1,
			'cource'	=>	-1,
		);
		$student = $this->getStudentData($this->input->post('session_id'));
		
		// ログイン受講者情報の設定
		if(!$student){
		//	$output['student']        = array();
			$output_status['student'] = 0;
		}else{
			$output['student'] = array(
				'student_id'				=>	$student['student_id'],
				'student_name'				=>	$student['student_name'],
				'student_email'				=>	$student['student_email'],
			//	'student_password'			=>	$student['student_password'],
			//	'student_password_encrypt'	=>	$student['student_password_encrypt'],
				'student_birthday'			=>	$student['student_birthday'],
				'student_note'				=>	$student['student_note'],
				'school_id'					=>	$student['school_id'],
			//	'status'					=>	$student['status'],
				'update_at'					=>	$student['update_at'],
			);
			$output_status['student'] = 1;
		}
		
		// 受講者所属の学校情報の取得
		if($output_status['student'] == 1){
			$school = $this->getSchoolData($student['school_id']);
			if(!$school){
				$output['school']        = array();
				$output_status['school'] = 0;
			}else{
				$output['school'] = array(
					'school_id'			=>	$school['school_id'],
					'school_name'		=>	$school['school_name'],
					'school_caption'	=>	$school['school_caption'],
					'school_note'		=>	$school['school_note'],
				//	'contract'			=>	$school['contract'],
				//	'contract_param'	=>	$school['contract_param'],
				//	'status'			=>	$school['status'],
					'lang'				=>	$school['lang'],
					'update_at'			=>	$school['update_at'],
				);
				$output_status['school'] = 1;
			}
		}
		
		// 生徒所属の講座情報の取得
		if($output_status['school'] == 1){
			$cource_list = $this->getCourceData($student['student_id'], $student['school_id']);
			if(!$cource_list){
				$output['cource']        = array();
				$output_status['cource'] = 0;
			}else{
				$output['cource'] = array();
				foreach($cource_list as $cource){
					array_push($output['cource'], array(
						'cource_id'			=>	$cource['cource_id'],
						'cource_name'		=>	$cource['cource_name'],
						'cource_open'		=>	$cource['cource_open'],
						'cource_close'		=>	$cource['cource_close'],
						'cource_caption'	=>	$cource['cource_caption'],
						'cource_note'		=>	$cource['cource_note'],
						'school_id'			=>	$cource['school_id'],
					//	'status'			=>	$cource['status'],
						'update_at'			=>	$cource['update_at'],
					));
				}
				$output_status['cource'] = 1;
			}
		}
		
		// statusの設定
		//	├  セッションあり（受講者情報あり）
		//	│    ├  学校あり
		//	│    │    ├  講座あり				200:Logged In
		//	│    │    └  講座なし				502:Cource Not Found
		//	│    └  学校なし						501:School Not Found
		//	└  セッションなし（受講者情報なし）	401:Not Logged In
		$result_status = array();
		if($output_status['student'] < 1){
			//セッションなしor生徒情報なし
			$result_status['status']  = 401;
			$result_status['message'] = 'Not Logged In';
		}elseif($output_status['school'] < 1){
			//学校情報なし
			$result_status['status']  = 501;
			$result_status['message'] = 'School Not Found';
		}elseif($output_status['cource'] < 1){
			//講座情報なし
			$result_status['status']  = 502;
			$result_status['message'] = 'Cource Not Found';
		}else{
			$result_status['status']  = 200;
			$result_status['message'] = 'Logged In';
			
			// セッションの有効時間変更
			$this->db->query($this->db->update_string('ci_sessions', array(
				'timestamp' => time(),
				), 
				'session_id="'.$this->session->userdata('session_id').'"'
			));
		}
		$output['result']  = array(
			'status'	=>	$result_status['status'],
			'message'	=>	$result_status['message'],
		);
		
		// output log
		// IP設定
		$clientIP="";
		if($this->ci->input->server('HTTP_X_FORWARDED_FOR')){
			$clientIP = $this->ci->input->server('HTTP_X_FORWARDED_FOR');
		}
		else if($this->ci->input->server('REMOTE_ADDR')){
			$clientIP = $this->ci->input->server('REMOTE_ADDR');
		}
		$log_message  = $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."/getStudentData"."\n";
		$log_message .= "REQUEST:[remote_addr]=>".$clientIP."\n";
		$log_message .= "REQUEST:[user_agent]=>".$_SERVER["HTTP_USER_AGENT"]."\n";
		$log_message .= "REQUEST POST:[session_id]=>".$this->input->post('session_id')."\n";
		$log_message .= "REQUEST POST:[debug]=>".$this->input->post('debug') ."\n";
		$log_message .= "RESPONSE:[output]=>".json_encode($output);
		log_message('error', $log_message );
		
		// output
		$this->load->helper('json');
//print "〓\n";
//print var_dump(obj2arr(json_decode(json_encode($output))));
//print "\n〓\n";
		if(false){
			// デバッグビュー無効化
		}
		else{
			$this->output->set_header("HTTP/1.0 200 OK");
			$this->output->set_content_type('application/json; charset=utf-8');
			$this->output->set_output(json_encode($output));
		}
	}

}
