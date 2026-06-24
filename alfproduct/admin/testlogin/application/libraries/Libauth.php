<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Libauth 
{
	public $defaultTeacherAuth = array(
		'admin_top'		=> 1,
		'school_select'	=> 0,
		'course'		=> 1,
		'course_class'	=> 1,
		'student'		=> 1,
		'teacher'		=> 1,
		'material'		=> 1,
		'book_library'	=> 1,
		'video'			=> 1,
		'information'	=> 1,
		'report'		=> 0,
		'auth'			=> 0,
		'school_admin'	=> 0,
		'school_manage'	=> 0,
	);

/*	public $teacherAuthName = array(
		'admin_top'		=> '',
		'school_select'	=> '',
		'course_class'	=> '講座・授業管理',
		'student'		=> '受講者管理',
		'teacher'		=> '講師管理',
		'material'		=> '資料管理',
		'book_library'	=> '図書室管理',
		'video'			=> 'ビデオ管理',
		'information'	=> 'お知らせ管理',
		'report'		=> 'レポート',
		'auth'			=> '権限管理',
		'school_admin'	=> '管理者',
	); */

	/*メニューバー用表示文字*/
/*	public $menubarName = array(
		'admin_top'		=> 'トップ',
		'school_select'	=> '学校選択',
		'course_class'	=> '講座・授業',
		'student'		=> '受講者',
		'teacher'		=> '講師',
		'material'		=> '資料',
		'book_library'	=> '図書室',
		'video'			=> 'ビデオ',
		'information'	=> 'お知らせ',
		'report'		=> 'レポート',
		'auth'			=> '権限',
		'school_admin'	=> '',
	); */

	/*メニューバー用ＵＲＬ*/
	public $menubarUrl = array(
		'admin_top'		=> '/admin_top',
		'school_select'	=> '/school_select',
		'course'		=> '/cms_cource',
		'course_class'	=> '/cms_cource_class/',
		'student'		=> '/cms_student',
		'teacher'		=> '/cms_teacher',
		'material'		=> '/cms_material',
		'book_library'	=> '/cms_book_library',
		'video'			=> '/cms_video',
		'information'	=> '/cms_information',
		'report'		=> '/cms_report',
		'auth'			=> '/cms_auth',
		'school_admin'	=> '',
		'school_manage'	=> '/cms_school_manage',
	);

	function __construct(){
		$this->ci =& get_instance();
		log_message('debug', 'Libauth Initialized');

		//DB:school.langがあったら、現在の言語設定をそれにする
		$_sess = $this->ci->session->userdata;
		if(isset($_sess['cms_master.login.school']) && isset($_sess['cms_master.login.school']['lang'])){
			$a =& get_config();
			$a['language'] = $_sess['cms_master.login.school']['lang'];
		}
		else if(getenv('URL_SERVICE') == 'alfsales'){
			$a =& get_config();
			$a['language'] = getenv('URL_SERVICE');
		}
		else if(getenv('URL_SERVICE') == 'conference'){
			$a =& get_config();
			$a['language'] = getenv('URL_SERVICE');
		}
		else {
			$a =& get_config();
			$a['language'] = 'japanese';
		}
	}

	/* Private function */
	function _set_session($data)
	{
		// Set session data array
		$user = array(						
			'cms_master.login.teacher_id'		=> $data->teacher_id,
			'cms_master.login.teacher_name'		=> $data->teacher_name,
			'cms_master.login.teacher_email'	=> $data->teacher_email,
			'cms_master.login.teacher_auth'		=> $data->teacher_auth,
			'cms_master.login.school_id'		=> $data->school_id,
			'cms_master.login.school_name'		=> $data->school_name,
			'cms_master.login.school'			=> $this->get_school($data->school_id),
			'cms_master.login.logged_in'		=> TRUE
		);
		$this->ci->session->set_userdata($user);
	}

	function _unset_session()
	{
		// Set session data array
		$user = array(
			'cms_master.login.teacher_id'		=> '',
			'cms_master.login.teacher_name'		=> '',
			'cms_master.login.teacher_email'	=> '',
			'cms_master.login.teacher_auth'		=> '',
			'cms_master.login.school_id'		=> '',
			'cms_master.login.school_name'		=> '',
			'cms_master.login.logged_in'		=> ''
		);
		$this->ci->session->set_userdata($user);
	}

	public function get_parse_auth_param($param) {
		return array_merge($this->defaultTeacherAuth, unserialize($param));
	}

	public function get_teacher_id() {
		return $this->ci->session->userdata('cms_master.login.teacher_id');
	}

	public function get_teacher_name() {
		return $this->ci->session->userdata('cms_master.login.teacher_name');
	}

	public function get_email() {
		return $this->ci->session->userdata('cms_master.login.teacher_email');
	}
	
	public function get_teacher_auth() {
		return $this->ci->session->userdata('cms_master.login.teacher_auth');
	}
	
	public function get_school_id() {
		return $this->ci->session->userdata('cms_master.login.school_id');
	}

	//[2012/11/01] 学校の契約形態を配列化して返す（DBから取得）
	public function get_login_school_contract_param($school_id) {
		$this->ci->load->model('model_school_manage', 'model_school_manage');
		$db_data = $this->ci->model_school_manage->get_school(
			array(
				'school_id' =>$school_id,
			)
		);
		
		$CI =& get_instance();
		$CI->load->helper('json');
		return obj2arr(json_decode($db_data['contract_param']));
	}
	
	public function set_school_id($school_id) {
		$this->ci->session->set_userdata('cms_master.login.school_id',$school_id);
		$this->ci->session->set_userdata('cms_master.login.school', $this->get_school($school_id));
		return 1;
	}

	public function get_school_name() {
		return $this->ci->session->userdata('cms_master.login.school_name');
	}
	
	public function set_school_name($school_name) {
		$this->ci->session->set_userdata('cms_master.login.school_name',$school_name);
		return 1;
	}

	/*権限*/
	public function get_teacherAuthName($key) {
		$CI =& get_instance();
		$CI->lang->load('common');
		$temp_teacherAuthName = array(
			'admin_top'		=> '',
			'school_select'	=> '',
			'course'		=> '',
			'course_class'	=> $CI->lang->line_or_def('common_course_class_manage' , '授業管理'),
			'student'		=> $CI->lang->line_or_def('common_student_manage'      , '受講者管理'),
			'teacher'		=> $CI->lang->line_or_def('common_teacher_manage'      , '講師管理'),
			'material'		=> $CI->lang->line_or_def('common_material_manage'     , '資料管理'),
			'book_library'	=> $CI->lang->line_or_def('common_book_library_manage' , '図書室管理'),
			'video'			=> $CI->lang->line_or_def('common_video_manage'        , 'ビデオ管理'),
			'information'	=> $CI->lang->line_or_def('common_information_manage'  , 'お知らせ管理'),
			'report'		=> $CI->lang->line_or_def('common_report_manage'       , 'レポート管理'),
			'auth'			=> $CI->lang->line_or_def('common_auth_manage'         , '権限管理'),
			'school_admin'	=> $CI->lang->line_or_def('common_manager'             , '管理者'),
		);
		return $temp_teacherAuthName[$key];
	}

	/*メニューバー用表示文字＋URLの返却*/
	public function get_user_auth_params() {
		
	/*	$temp_menubarName = $this->$menubarName;
		foreach($this->$menubarName as $name => $value){
			array_push($ret, array(
				'name'	=> $value,
				'url'	=> $this->menubarUrl[$name],
			));
		}
	*/

		// 画面上部のタブ名称設定
		$CI =& get_instance();
		$CI->lang->load('common');
		$temp_menubarName = array(
			'admin_top'		=> $CI->lang->line_or_def('common_menu_admin_top'     , 'トップ'),
			'school_select'	=> $CI->lang->line_or_def('common_menu_school_select' , '学校選択'),
			'course'		=> $CI->lang->line_or_def('common_menu_course'        , '講座'),
			'course_class'	=> $CI->lang->line_or_def('common_menu_course_class'  , '授業'),
			'student'		=> $CI->lang->line_or_def('common_menu_student'       , '受講者'),
			'teacher'		=> $CI->lang->line_or_def('common_menu_teacher'       , '講師'),
			'material'		=> $CI->lang->line_or_def('common_menu_material'      , '資料'),
			'book_library'	=> $CI->lang->line_or_def('common_menu_book_library'  , '図書室'),
			'video'			=> $CI->lang->line_or_def('common_menu_video'         , 'ビデオ'),
			'information'	=> $CI->lang->line_or_def('common_menu_information'   , 'お知らせ'),
			'report'		=> $CI->lang->line_or_def('common_menu_report'        , 'レポート'),
			'auth'			=> $CI->lang->line_or_def('common_menu_auth'          , '権限'),
			'school_admin'	=> '',
			'school_manage'	=> $CI->lang->line_or_def('common_menu_school_manage' , '学校管理'),
			'outside_elm'	=> $CI->lang->line_or_def('common_123' , 'eLM'),
		);

		$ret   = array();
		$auths = $this->get_teacher_auth();
		
		$auths = array_merge($this->defaultTeacherAuth, $auths);

		//サービス未契約の場合パーミッションに関係なく上書き
		$this->ci->load->model('Modelschoolcontract');
		if(!$this->ci->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
			$auths['course_class'] = 0;
			$auths['material'] = 0;
		}
		if(!$this->ci->Modelschoolcontract->enableService(array('serviceKey'=>'video'))){
			$auths['video'] = 0;
		}
		if(!$this->ci->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))){
			$auths['book_library'] = 0;
		}

		// メニューリンクの取得
		$memberUrl = $this->menubarUrl;

		// [2012/11/01]eLearningManagerのリンクの作成
		if($this->ci->session->userdata['cms_master.login.school_id'] > 0){
			if($auths['school_admin'] > 0){
			//	$CI->load->helper('json');
			//	$contract_param = obj2arr(json_decode($this->ci->session->userdata['cms_master.login.school']['contract_param']));
				$contract_param = $this->get_login_school_contract_param($this->ci->session->userdata['cms_master.login.school_id']);
				if(isset($contract_param['outside_elearningmanager'])){
					if($contract_param['outside_elearningmanager']['contract']==='fixation'){
						if(!empty($contract_param['outside_elearningmanager']['api_url'])){
							$auths['outside_elm'] = 1;
							$api_url = urldecode($contract_param['outside_elearningmanager']['api_url']);
							$api_url = preg_replace('/\/$/', '', $api_url);
							$memberUrl['outside_elm'] = $api_url."/eLM/admin/frameset.cfm";
						}
					}
				}
			}
		}

		foreach($auths as $names => $value){
			if($value == 1){
				if( strcmp($names, 'school_admin') != 0){
					array_push($ret, array(
						'auth'	=> $names,
						'name'	=> $temp_menubarName[$names],
						'url'	=> $memberUrl[$names],
					));
				}
			}
		}
		return $ret;
	}

	public function is_logged_in() {
		return $this->ci->session->userdata('cms_master.login.logged_in');
	}
	
	/* Main function */

	// $login is username or email or both depending on setting in config file	
	public function login($teacher_email, $teacher_password)
	{
		// Load Models
		$this->ci->load->model('model_auth', 'model_auth');
			
		// Default return value
		$result = FALSE;

		$this->ci->session->set_userdata(array(
			'enableSchools'	=> '',
		));

		if ( ! empty($teacher_email) AND ! empty($teacher_password)){
			// Get user query
			if ($query = $this->ci->model_auth->login_check($teacher_email) AND $query->num_rows() == 1)
			{
				// Get Teacher Data
				$row = $query->row();

				// unserialize
				$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));

				log_message('debug', print_r($row, true));
				
				// Is password matched with hash in database ?

				//[2012/05/01][2012/06/11]
				if( hash('sha256',$teacher_password) === $row->teacher_password_encrypt ){
					// Log in user 
					$this->_set_session($row);
					
					// Set return value
					$result = TRUE;
				}
			}else if($query->num_rows() > 1){
				$rows = $query->result();
				$enableSchools = array();
				foreach($rows as $row){
					//[2012/05/01][2012/06/11]
					if( hash('sha256',$teacher_password) === $row->teacher_password_encrypt ){
						if($this->ci->input->post('school_select') == $row->school_id){
							$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));
							$this->_set_session($row);
							$result = TRUE;
							$enableSchools = array();
							break;
						}else{
							array_push($enableSchools, $row);
						}
					}
				}
				if(count($enableSchools) == 1){
					$row = $enableSchools[0];
					$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));
					$this->_set_session($row);
					$result = TRUE;
				}
				else if(count($enableSchools)){
					$this->ci->session->set_userdata(array(
						'enableSchools'	=> $enableSchools,
					));
				}
			}else{
				log_message('debug', 'login info not found : teacher_email = $teacher_email');
			}
		}
		
		return $result;
	}

	public function logout()
	{
		$this->_unset_session();
	}

 
	function gen_pass($len = 8)
	{
		// No Zero (for user clarity);
		$pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$str = '';
		for ($i = 0; $i < $len; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $str;
	}

	// Get school name (admin login)
	public function select_school_name($school_id)
	{
		// Load Models
		$this->ci->load->model('model_auth', 'model_auth');
		
		// Default return value
		$result = '';

		if ( ! empty($school_id)){
			// Get user query
			if ($query = $this->ci->model_auth->school_name($school_id) AND $query->num_rows() == 1)
			{
				// Get Teacher Data
				$row = $query->row();

				log_message('debug', print_r($row, true));
				
				// Get School Name
				$result = $row->school_name;
			}else{
				log_message('debug', 'school name not found : school_id = $school_id');
			}
		}
		return $result;
	}

	// Get school name (admin login)
	public function get_school($school_id)
	{
		// Load Models
		$this->ci->load->model('model_auth', 'model_auth');
		
		// Default return value
		$result = '';

		if ( ! empty($school_id)){
			// Get user query
			if ($query = $this->ci->model_auth->get_school($school_id) AND $query->num_rows() == 1)
			{
				// Get Teacher Data
				$row = $query->row_array();
				log_message('debug', print_r($row, true));
				
				// Get School Name
				$result = $row;
			}else{
				log_message('debug', 'school name not found : school_id = $school_id');
			}
		}
		return $result;
	}

	// [2012/10/01] update login session
	public function update_login_session($teacher_id)
	{
		// Load Models
		$this->ci->load->model('model_auth', 'model_auth');
		
		// Default return value
		$result = FALSE;

		if ($query = $this->ci->model_auth->get_teacher_data($teacher_id) AND $query->num_rows() == 1  AND $teacher_id != -1)
		{
			// Get Teacher Data
			$row = $query->row();

			// unserialize
			$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));

			$this->_set_session($row);
			
			// Set return value
			$result = TRUE;
		}
		
		return $result;
	}

}

?>
