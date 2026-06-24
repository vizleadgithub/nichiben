<?php
class Model_school_manage extends CI_Model
{
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// 学校検索結果一覧取得（index）
	//----------------------------------------------
	function get_school_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_school_name' => '',
							's_free_word'   => '',
							'offset'        => 0,
							'rowcount'      => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_school_selectsql($param);
		$sql_limit  = " LIMIT
						 {$param['offset']}, {$param['rowcount']}";
		$query = $this->db->query($sql . $sql_limit);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	//----------------------------------------------
	// 学校検索結果件数取得（index）
	//----------------------------------------------
	function get_school_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_school_name' => '',
							's_free_word'   => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_school_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}

	//----------------------------------------------
	// 学校検索結果取得用SQL生成 *
	//----------------------------------------------
	function _get_school_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_school_name' => '',
							's_free_word'   => '',
						),
						$param
					);
		//SQL生成
		$sql_select  = "SELECT school_id ,school_name ,school_caption ,school_note ,contract  ,contract_param ";
		//                    ,status ,update_at
		$sql_select .= "  FROM school ";
		$sql_where   = " WHERE status = 0";
		//$sql_where .= (getenv('URL_SERVICE') == 'conference' ? ' AND school.lang = "conference"' : ' AND school.lang <> "conference"');	//カンファレンス考慮
		if(getenv('URL_SERVICE') == 'alfsales'){
			$sql_where .= ' AND school.lang = "alfsales" ';
		}elseif(getenv('URL_SERVICE') == 'conference'){
			$sql_where .= ' AND school.lang = "conference" ';
		}else{
			$sql_where .= ' AND (lang <> "alfsales" AND lang <> "conference") ';
		}

		$sql_order   = " ORDER BY school_id ";

		//学校ID
		if (isset($param['s_school_id']) && $param['s_school_id'] != '' ) {
			if(is_numeric($param['s_school_id'])){
				$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
			} else {
				$sql_where .= " AND school_id = 0";
			}
		}
		//講師氏名
		if (isset($param['s_school_name']) && $param['s_school_name'] != '') {
			$sql_where .= " AND school_name LIKE '%{$this->db->escape_like_str($param['s_school_name'])}%'";
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (school_name    LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
							 OR  school_caption LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
							 OR  school_note    LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
							 OR  contract       LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
							 OR  contract_param LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%' )";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}

	//----------------------------------------------
	// 一件取得（confrim）
	//----------------------------------------------
	function get_school($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id' => 0,
						),
						$param
					);
		
		//SQL生成
		$school_admin_on  = '%s:12:"school_admin";i:1;%';
		$school_admin_off = '%s:12:"school_admin";i:0;%';
		
		$sql  = "";
		$sql .= "SELECT school.school_id      AS school_id ";
		$sql .= "     , school.school_name    AS school_name ";
		$sql .= "     , school.school_caption AS school_caption ";
		$sql .= "     , school.school_note    AS school_note ";
		$sql .= "     , school.contract       AS contract ";
		$sql .= "     , school.contract_param AS contract_param ";
		$sql .= "     , school.status         AS status ";
		$sql .= "     , school.update_at      AS update_at ";
		$sql .= "     , (SELECT COUNT(*) FROM teacher WHERE teacher.school_id = school.school_id ";
		$sql .= "        AND teacher.status = 0 AND teacher_auth LIKE '".$school_admin_on."')  AS school_admin_count ";
		$sql .= "     , (SELECT COUNT(*) FROM teacher WHERE teacher.school_id = school.school_id ";
		$sql .= "        AND teacher.status = 0 AND teacher_auth LIKE '".$school_admin_off."') AS teacher_count ";
		$sql .= "     , (SELECT COUNT(*) FROM student WHERE student.school_id = school.school_id ";
		$sql .= "        AND student.status = 0) AS student_count ";
		$sql .= "  FROM school ";
		$sql .= " WHERE school_id = ? ";
		$sql .= "   AND status    = 0 ";

		$query = $this->db->query($sql, array(
									$param['school_id'],
								));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}

	//----------------------------------------------
	// 新規登録・更新処理（commit）
	//----------------------------------------------
	function update_school($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在時刻取得
		$wDate = date('Y/m/d H:i:s');

		if ($data['update_flg'] == 0){
			//新規
			//  INSERT INTO school
			$sql  = "";
			$sql .= "INSERT INTO school ( ";
			$sql .= "       school_name ,school_caption ,school_note ,contract ,contract_param ,status, lang ,update_at ";
			$sql .= ") VALUES (? ,? ,? ,? ,? ,? ,?, ?) ";

			$lang = '';
			if(getenv('URL_SERVICE') == 'alfsales'){
				$lang = "alfsales";
			}elseif(getenv('URL_SERVICE') == 'conference'){
				$lang = 'conference';
			}else{
				$lang = 'japanese';
			}

			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['school_name'],
									$data['school_caption'],
									$data['school_note'],
									$data['contract'],
									$data['contract_param'],
									0,
									$lang,
									$wDate
								));
			
			// 学校IDを取得
			$prev_school_id = $this->db->insert_id();
			$this->db->trans_complete();

			//講座の初期ダミーデータ登録
			$this->db->query($this->db->insert_string('cource', array(
				'school_id'         => $prev_school_id,
				'cource_name'		=> '基本講座',
				'cource_open'		=> '1900-01-01 00:00:00',
				'cource_close'		=> '2100-12-31 23:59:59',
				'cource_caption'	=> '基本講座です',
				'cource_note'		=> '',
				'status'			=> '0',
				'update_at'        => date('Y/m/d H:i:s'),
			)));
			
			
			// INSERT INTO teacher
			//[2012/06/11]
			$sql  = "";
			$sql .= "INSERT INTO teacher ( ";
		//	$sql .= "       teacher_name ,teacher_email ,teacher_password ,teacher_password_encrypt ,teacher_auth ,school_id ,status ,update_at ";
		//	$sql .= ") VALUES (? ,? ,? ,? ,? ,? ,? ,?) ";
			$sql .= "       teacher_name ,teacher_email ,teacher_password_encrypt ,teacher_auth ,school_id ,status ,update_at ";
			$sql .= ") VALUES (? ,? ,? ,? ,? ,? ,?) ";

			$new_teacher_password = $this->_get_new_teacher_password();
			$new_teacher_auth     = $this->_get_new_teacher_auth();

			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['school_admin_name'],
									$data['school_admin_email'],
									hash('sha256',$new_teacher_password),
									$new_teacher_auth,
									$prev_school_id,
									0,
									$wDate
								));

			// 先生IDを取得
			$prev_teacher_id = $this->db->insert_id();
			$this->db->trans_complete();

			return array(
				'school_id'        => $prev_school_id,
				'school_name'      => $data['school_name'],
				'teacher_id'       => $prev_teacher_id,
				'teacher_name'     => $data['school_admin_name'],
				'teacher_email'    => $data['school_admin_email'],
				'teacher_password' => $new_teacher_password,
				);
		}else{
			//修正
			//  UPDATE school
			$sql  = "";
			$sql .= "UPDATE school ";
			$sql .= "   SET school_name    = ? ";
			$sql .= "      ,school_caption = ? ";
			$sql .= "      ,school_note    = ? ";
			$sql .= "      ,contract       = ? ";
			$sql .= "      ,contract_param = ? ";
			$sql .= "      ,status         = ? ";
			$sql .= "      ,update_at      = ? ";
			$sql .= " WHERE school_id      = ? ";

			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['school_name'],
									$data['school_caption'],
									$data['school_note'],
									$data['contract'],
									$data['contract_param'],
									0,
									$wDate,
									$data['school_id'],
								));
			$this->db->trans_complete();

			return array(
				'school_id'        => $data['school_id'],
				'school_name'      => $data['school_name'],
				'teacher_id'       => 0,
				'teacher_name'     => '',
				'teacher_email'    => '',
				'teacher_password' => '',
				);
		}
	}

	//----------------------------------------------
	// 論理削除処理 （confrim）
	//----------------------------------------------
	function delete_school($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id' => 0,
						),
						$param
					);
		//SQL生成
		$sql  = "";
		$sql .= "UPDATE school ";
		$sql .= "   SET status = 9 ";
		$sql .= " WHERE school_id = ?";

		$this->db->trans_start();
		$this->db->query($sql, array(
							$param['school_id'])
						);
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// 講師パスワード取得 *
	//----------------------------------------------
	function _get_new_teacher_password(){
		$this->load->helper('string');
		$password = random_string('alnum');
		
		return $password;
	}

	//----------------------------------------------
	// 講師権限取得 *   学校管理者用
	//----------------------------------------------
	function _get_new_teacher_auth(){
		// 学校管理者の権限を作成
		$temp_teacher_auth = array_merge(
			$this->libauth->defaultTeacherAuth,
			array(
				'admin_top'     => 1,
				'school_select' => 0,
				'course'        => 1,
				'course_class'  => 1,
				'student'       => 1,
				'teacher'       => 1,
				'material'      => 1,
				'book_library'  => 1,
				'video'         => 1,
				'information'   => 1,
				'report'        => 1,
				'auth'          => 1,
				'school_admin'  => 1,
				'school_manage'	=> 0,
			)
		);
		
		return serialize($temp_teacher_auth);
	}

	//----------------------------------------------
	// [2012/10/23]契約内容更新（contract_param）
	//----------------------------------------------
	function update_school_contract_param($param){
		//引数設定
		$param = array_merge(
					array(
						'school_id'			=> 0,
						'contract_param'	=> '',
					),
					$param
				);
		
		//現在時刻取得
		$wDate = date('Y/m/d H:i:s');

		//修正
		//  UPDATE school
		$sql  = "";
		$sql .= "UPDATE school ";
		$sql .= "   SET contract_param = ? ";
		$sql .= "      ,update_at      = ? ";
		$sql .= " WHERE school_id      = ? ";

		$this->db->trans_start();
		$this->db->query($sql, 
							array(
								$param['contract_param'],
								$wDate,
								$param['school_id'],
							));
		$this->db->trans_complete();

		return true;
	}

	//----------------------------------------------
	// [2012/11/14]外部連携重複チェック
	// 同じAPI URLを登録できなくするためのチェック
	//----------------------------------------------
	function check_outside_content_param($param){
		//引数設定
		$param = array_merge(
					array(
						'school_id'		=> 0,
						'api_url'		=> '',
					),
					$param
				);

		// like条件の編集
		// API URLに対してURL ENCODE + %のエスケープ処理
		$api_url = '';
		if(strlen($param['api_url']) > 0){
			$api_url = $param['api_url'];
			$api_url = preg_replace('/\/$/', '', $api_url);
			$api_url = '%'.str_replace('%', '\%', urlencode($api_url)).'%';
		}

		$sql  = "";
		$sql .= "SELECT school.* ";
		$sql .= "  FROM school ";
		$sql .= " WHERE school.school_id != ? ";
		$sql .= "   AND school.contract_param LIKE '".$api_url."' ";

		$query = $this->db->query($sql, array(
									$param['school_id'],
								));
		
		//データリターン
		if ($query->num_rows() > 0){
			return true;	// 同じAPI URLを持つ学校あり
		}else{
			return false;	// 同じAPI URLを持つ学校なし
		}
	}

}
?>
