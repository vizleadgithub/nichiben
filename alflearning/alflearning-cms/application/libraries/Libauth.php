<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Libauth 
{
	public $defaultTeacherAuth = array(
		'admin_top'			=> 1,
		'school_select'			=> 0,
		'course'			=> 1,
		'course_class'			=> 0,
		'student'			=> 1,
		'teacher'			=> 1,
		'material'			=> 0,
		'book_library'			=> 0,
		'video'				=> 1,
		'issue'				=> 1,
		'information'			=> 0,
		'auth'				=> 0,
		'school_admin'			=> 0,
		'school_manage'			=> 0,

		//'outside_elm'			=> 0,
		'alfproduct_product'		=> 0,
		'alfproduct_product_lecture'	=> 0,
		'report'			=> 0,
		'alfproduct_amount_user'	=> 0,
		'alfproduct_mailmagazine'	=> 0,
		'exam' 				=> 1,
		'exam2' 			=> 1,
		'ranking' 			=> 1,
		'category' 			=> 1,
		'alfproduct_inquiry'		=> 0,
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
		'school_select'		=> '/school_select',
		'course'		=> '/cms_cource',
		'course_class'		=> '/cms_cource_class/',
		'student'		=> '/cms_student',
		'teacher'		=> '/cms_teacher',
		'material'		=> '/cms_material',
		'book_library'		=> '/cms_book_library',
		'video'			=> '/cms_video',
		'issue'			=> '/cms_issue',
		'information'		=> '/cms_information',
		'report'		=> '/cms_report',
		'auth'			=> '/cms_auth',
		'school_admin'		=> '',
		'school_manage'		=> '/cms_school_manage',
		'exam'			=> '/cms_exam',
		'exam2'			=> '/cms_exam2',
		'ranking'		=> '/cms_ranking',
		'category'		=> '/cms_category',
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
	// 日弁連、弁護士会ID【teacher.bar_association_id】の追加
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
			'cms_master.login.school'		=> $this->get_school($data->school_id),
			'cms_master.login.logged_in'		=> TRUE,
			'cms_master.login.bar_association_id'	=> $data->bar_association_id
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
			'cms_master.login.logged_in'		=> '',
			'cms_master.login.bar_association_id'	=> ''
		);
		
		$this->ci->session->set_userdata($user);
		$this->ci->session->sess_destroy();
		setcookie("ci_session", "", time() - 30);
		$_SESSION = array();
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
		session_destroy();
	}

	public function get_parse_auth_param($param) {
		//return array_merge($this->defaultTeacherAuth, unserialize($param));
		$userParam = @unserialize($param); // @で notice 抑制（必要に応じて）
		if (!is_array($userParam)) {
			$userParam = [];
		}
		return array_merge($this->defaultTeacherAuth, $userParam);

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

	/* 日弁連、ログイン中管理者の弁護士会IDの取得 */
	public function get_bar_association_id() {
		return $this->ci->session->userdata('cms_master.login.bar_association_id');
	}

	/* 日弁連、ログイン中管理者の所属弁護士会名の取得 */
	public function get_bar_association_name() {
		$bar_association_id = $this->ci->session->userdata('cms_master.login.bar_association_id');
		
		$this->ci->load->model('model_student', 'model_student');
		$bar_association_name = $this->ci->model_student->get_bar_association_name($bar_association_id);
		
		return $bar_association_name;
	}


	/*権限*/
	public function get_teacherAuthName($key) {
		$CI =& get_instance();
		$CI->lang->load('common');
		$temp_teacherAuthName = array(
			'admin_top'	=> '',
			'school_select'	=> '',
			'course'	=> '',
			'course_class'	=> $CI->lang->line_or_def('common_course_class_manage' , '授業管理'),
			'student'	=> $CI->lang->line_or_def('common_student_manage'      , '受講者管理'),
			'teacher'	=> $CI->lang->line_or_def('common_teacher_manage'      , '講師管理'),
			'material'	=> $CI->lang->line_or_def('common_material_manage'     , '資料管理'),
			'book_library'	=> $CI->lang->line_or_def('common_book_library_manage' , '図書室管理'),
			'video'		=> $CI->lang->line_or_def('common_video_manage'        , 'ビデオ管理'),
			'issue'		=> $CI->lang->line_or_def('common_issue_manage'        , '課題管理'),
			'information'	=> $CI->lang->line_or_def('common_information_manage'  , 'お知らせ管理'),
			'report'	=> $CI->lang->line_or_def('common_report_manage'       , 'レポート管理'),
			'auth'		=> $CI->lang->line_or_def('common_auth_manage'         , '権限管理'),
			'school_admin'	=> $CI->lang->line_or_def('common_manager'             , '管理者'),
			'exam'		=> $CI->lang->line_or_def('common_exam_manage'         , '問題（テスト）管理'),
			'exam2'		=> $CI->lang->line_or_def('common_exam2_manage'        , 'アンケート管理'),
			'ranking'	=> $CI->lang->line_or_def('common_ranking_manage'      , 'ランキング管理'),
			'category'	=> $CI->lang->line_or_def('common_category_manage'     , 'カテゴリ管理'),
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
		// 法学館対応「商品・集計・メルマガ・お問い合わせ」追加
		$CI =& get_instance();
		$CI->lang->load('common');
		$temp_menubarName = array(
			'admin_top'		=> $CI->lang->line_or_def('common_menu_admin_top'     , 'トップ'),
			'school_select'		=> $CI->lang->line_or_def('common_menu_school_select' , '学校選択'),
			'course'		=> $CI->lang->line_or_def('common_menu_course'        , '講座'),
			'course_class'		=> $CI->lang->line_or_def('common_menu_course_class'  , '授業'),
			'student'		=> $CI->lang->line_or_def('common_menu_student'       , '受講者'),
			'teacher'		=> $CI->lang->line_or_def('common_menu_teacher'       , '講師'),
			'material'		=> $CI->lang->line_or_def('common_menu_material'      , '資料'),
			'book_library'		=> $CI->lang->line_or_def('common_menu_book_library'  , '図書室'),
			'video'			=> $CI->lang->line_or_def('common_menu_video'         , 'ビデオ')."*",
			'issue'			=> $CI->lang->line_or_def('common_menu_issue'         , '課題'),
			'information'		=> $CI->lang->line_or_def('common_menu_information'   , 'お知らせ'),
			'auth'			=> $CI->lang->line_or_def('common_menu_auth'          , '権限'),
			'school_admin'		=> '',
			'school_manage'		=> $CI->lang->line_or_def('common_menu_school_manage' , '学校管理'),
			'outside_elm'		=> $CI->lang->line_or_def('common_123' , 'eLM'),

			'alfproduct_product'		=> $CI->lang->line_or_def('common_hogaku' , '商品'),
			'alfproduct_product_lecture'	=> $CI->lang->line_or_def('common_hogaku' , '講座管理'),
			'report'			=> $CI->lang->line_or_def('common_menu_report'        , 'レポート'),
			'alfproduct_amount_user'	=> $CI->lang->line_or_def('common_hogaku' , '集計'),
			'alfproduct_mailmagazine'	=> $CI->lang->line_or_def('common_hogaku' , 'メルマガ')."*",
			'alfproduct_inquiry'		=> $CI->lang->line_or_def('common_hogaku' , 'お問い合わせ')."*",
			'exam'				=> $CI->lang->line_or_def('common_menu_exam', '問題'),
			'exam2'				=> $CI->lang->line_or_def('common_menu_exam2', 'アンケート'),
			'ranking'			=> $CI->lang->line_or_def('common_menu_ranking', 'ランキング'),
			'category'			=> $CI->lang->line_or_def('common_menu_category', 'カテゴリ'),
		);
		
		$ret   = array();
		$auths = $this->get_teacher_auth();
		
		if (!is_array($auths)) {
			$auths = [];
		}
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

		if(!$this->ci->Modelschoolcontract->enableService(array('serviceKey'=>'issue'))){
			$auths['issue'] = 0;
		}

		// 法学館対応　お知らせ・権限を非表示固定
		//$auths['information'] = 0;
		//$auths['category'] = 0;
		$auths['auth'] = 0;
		
		// 日弁連対応　講座を非表示固定
		$auths['course'] = 0;

		// [20131118-NICHIBENREN_KENSHU-106]コンテンツ（ビデオ）は、管理者（日弁連）以外表示しないように修正
		if( $this->get_bar_association_id() != 1 && $this->get_bar_association_id() != '1' ){
			$auths['video'] = 0;
			$auths['exam'] = 0;
			$auths['exam2'] = 0;
			$auths['ranking'] = 0;
			$auths['information'] = 0;
			$auths['category'] = 0;
		} else {
			$auths['video'] = 1;
			$auths['exam'] = 1;
			$auths['exam2'] = 1;
			$auths['ranking'] = 1;
			$auths['information'] = 1;
			$auths['category'] = 1;
		}

		// 法学館対応　リンク追加
		$auths['alfproduct_product']         = 1;
		$auths['alfproduct_product_lecture'] = 1;
		$auths['alfproduct_amount_user']     = 1;
		$auths['alfproduct_mailmagazine']    = 0;
		$auths['alfproduct_inquiry']         = 0;
		// [20131023-NICHIBENREN_KENSHU-80]メルマガ・お問い合わせは、管理者（日弁連）以外表示しないように修正
		if($this->get_bar_association_id() == 1){
			$auths['alfproduct_mailmagazine'] = 0;
			$auths['alfproduct_inquiry']      = 1;
		}
		
		// メニューリンクの取得
		$memberUrl = $this->menubarUrl;

		// 法学館対応　リンク追加
		$memberUrl['alfproduct_product']         = '/alfproduct/product_live/';
		//  $memberUrl['alfproduct_product']         = '/alfproduct/product/';
		//  // [20131030]商品タブのリンク先を、管理者（日弁連）以外は変更するように対応
		//  if($this->get_bar_association_id() != 1){
		//       $memberUrl['alfproduct_product']         = '/alfproduct/product_live/';
		//  }
		
		$memberUrl['alfproduct_product_lecture'] = '/alfproduct/product_lecture/';
		$memberUrl['alfproduct_amount_user']     = '/alfproduct/amount_user/';
		$memberUrl['alfproduct_mailmagazine']    = '/alfproduct/mailmagazine/';
		$memberUrl['alfproduct_inquiry']         = '/alfproduct/inquiry/';
		
		// 日弁連対応　リンク追加
		$memberUrl['alfproduct_amount_user']  = '/alfproduct/amount_order/';

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
				if (!is_array($row->teacher_auth)) {
					$teacher_auth = @unserialize($row->teacher_auth);

					// もしシリアライズ文字列でなかった場合も空配列にフォールバック
					if ($teacher_auth === false && $row->teacher_auth !== 'b:0;') {
						$teacher_auth = [];
					}
				} else {
					$teacher_auth = $row->teacher_auth;
				}
				$row->teacher_auth = array_merge($this->defaultTeacherAuth, $teacher_auth);

				log_message('debug', print_r($row, true));
				
				// Is password matched with hash in database ?

				//[2012/05/01][2012/06/11]
				if( hash('sha256',$teacher_password) === $row->teacher_password_encrypt ){
					// Log in user 
					session_regenerate_id(true);
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
							if (!is_array($row->teacher_auth)) {
								$row->teacher_auth = [];
							}
							$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));
							session_regenerate_id(true);
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
					if (!is_array($row->teacher_auth)) {
						$row->teacher_auth = [];
					}
					$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));
					session_regenerate_id(true);
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

	/**
	 * DBのteacherレコードとセッションを比較し、乖離があればセッションを最新化する。
	 * 他の管理者がログイン中のユーザーの属性（弁護士会ID・所属学校等）を変更した場合に
	 * 次のリクエスト時点でセッションを自動的に更新するために使用する。
	 * @return bool 更新した場合 true、変更なし or スキップの場合 false
	 */
	public function sync_session_from_db()
	{
		$teacher_id = $this->get_teacher_id();
		// 未ログインまたはSuperUser(-1)はスキップ
		if (empty($teacher_id) || (int)$teacher_id === -1) {
			return false;
		}

		$this->ci->load->model('model_auth', 'model_auth');
		$query = $this->ci->model_auth->get_teacher_data($teacher_id);
		if (!$query || $query->num_rows() != 1) {
			return false;
		}

		$row = $query->row();

		// セッション値とDB値を比較（bar_association_id / school_id が主要な判定対象）
		$session_bar    = (string)$this->get_bar_association_id();
		$db_bar         = (string)$row->bar_association_id;
		$session_school = (string)$this->get_school_id();
		$db_school      = (string)$row->school_id;

		if ($session_bar !== $db_bar || $session_school !== $db_school) {
			// 乖離あり → セッションをDB値で上書き
			$this->update_login_session($teacher_id);
			return true;
		}

		return false;
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
			//if (!is_array($row->teacher_auth)) {
			//	$row->teacher_auth = [];
			//}
			//$row->teacher_auth = array_merge($this->defaultTeacherAuth, unserialize($row->teacher_auth));

			$auth = $row->teacher_auth;
			$authArr = [];
			if (is_array($auth)) {
				$authArr = $auth;
			} elseif (is_string($auth)) {
				// まず JSON を疑う（将来的にも安全）
				$tmp = json_decode($auth, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
					$authArr = $tmp;
				} else {
					// JSON でなければ PHP の serialize とみなして復元（クラス禁止で安全側に）
					$tmp = @unserialize($auth, ['allowed_classes' => false]);
					if ($tmp !== false && is_array($tmp)) {
						$authArr = $tmp;
					}
				}
			}
			$authArr = array_map(static fn($v) => (int)$v, $authArr);

			$row->teacher_auth = array_merge($this->defaultTeacherAuth, $authArr);

			$this->_set_session($row);
			
			// Set return value
			$result = TRUE;
		}
		
		return $result;
	}

}

?>
