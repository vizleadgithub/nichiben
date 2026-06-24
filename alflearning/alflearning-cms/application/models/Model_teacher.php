<?php
#[AllowDynamicProperties]
class Model_teacher extends CI_Model
{
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
	}
	//----------------------------------------------
	//講師検索結果一覧取得
	//----------------------------------------------
	function get_teacher_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
							'offset'      => 0,
							'rowcount'    => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_teacher_selectsql($param);
		$sql_limit  = " LIMIT
						 {$param['offset']}, {$param['rowcount']}";
		$query = $this->db->query($sql . $sql_limit);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	//----------------------------------------------
	//講師検索結果件数取得
	//----------------------------------------------
	function get_teacher_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_teacher_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//講師検索結果取得用SQL生成
	//----------------------------------------------
	function _get_teacher_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
						),
						$param
					);
		//SQL生成
		$sql_select = "SELECT
							teacher_id,
							teacher_name,
							teacher_email,
							teacher_note, 
							teacher_auth,
							bar_association_id 
						FROM
							teacher ";
		$sql_where = " WHERE
							status = 0";
		$sql_order = " ORDER BY
							teacher_id";
		
		//学校ID
		$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
		
		//弁護士会ID
		$temp_bar_association_id = $this->libauth->get_bar_association_id();
		if( $temp_bar_association_id != 1 ){
			$sql_where .= " AND bar_association_id = {$this->db->escape($temp_bar_association_id)}";
		}

		//講師氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND teacher_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		//メールアドレス
		if (isset($param['s_email']) && $param['s_email'] != '') {
			$sql_where .= " AND teacher_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'";
		}
		//ID
		if (isset($param['s_id']) && $param['s_id'] != '' ) {
			if(is_numeric($param['s_id'])){
				$sql_where .= " AND teacher_id = {$this->db->escape($param['s_id'])}";
			} else {
				$sql_where .= " AND teacher_id = 0";
			}
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (teacher_name               LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_introduce        LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_introduce_detail LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_note             LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//一件取得
	//[2012/06/11]
	//----------------------------------------------
	function get_teacher($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										teacher_id,
										teacher_name,
										teacher_email,
										teacher_password_encrypt,
										teacher_auth,
										teacher_introduce,
										teacher_introduce_detail,
										teacher_note,
										school_id,
										bar_association_id,
										status,
										update_at
									FROM
										teacher
									WHERE
										teacher_id = {$this->db->escape($param['teacher_id'])}
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
		}
	}
	
	//----------------------------------------------
	//更新処理
	// [2012/09/10] 条件によりパスワード含む更新・含まない更新に切り替え処理を追加。
	// [2012/09/10] 条件により同じメールアドレスを持つ講師のパスワードを更新する処理を追加。
	//----------------------------------------------
	function update_teacher($param){
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
			$sql = "INSERT INTO
						teacher 
					(
						teacher_name,
						teacher_email,
						teacher_password_encrypt,
						teacher_auth,
						teacher_introduce,
						teacher_introduce_detail,
						teacher_note,
						bar_association_id,
						school_id,
						status,
						update_at
					)
					VALUES(?,?,?,?,?,?,?,?,?,?,?)";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['teacher_name'],
									$data['teacher_email'],
									hash('sha256',$data['teacher_password']),
									$data['teacher_auth'],
									$data['teacher_introduce'],
									$data['teacher_introduce_detail'],
									$data['teacher_note'],
									$data['bar_association_id'],
									$data['school_id'],
									0,
									$wDate
								));
			//登録idを取得
			$prev_id = $this->db->insert_id();
			
			$this->db->trans_complete();
			
			return array('prev_id' => $prev_id);
		}else{
			//修正
			if($data['teacher_password_change'] == 1){
				// パスワード含む更新
				$sql = "UPDATE
							teacher
						SET 
							teacher_name             = ?,
							teacher_email            = ?,
							teacher_password_encrypt = ?,
							teacher_auth             = ?,
							teacher_introduce        = ?,
							teacher_introduce_detail = ?,
							teacher_note             = ?,
							bar_association_id       = ?,
							school_id                = ?,
							status                   = ?,
							update_at                = ?
						WHERE
							teacher_id = ?";
				$this->db->trans_start();
				$this->db->query($sql, 
									array(
										$data['teacher_name'],
										$data['teacher_email'],
										hash('sha256',$data['teacher_password']),
										$data['teacher_auth'],
										$data['teacher_introduce'],
										$data['teacher_introduce_detail'],
										$data['teacher_note'],
										$data['bar_association_id'],
										$data['school_id'],
										0,
										$wDate,
										$data['teacher_id']
									));
				$this->db->trans_complete();

				$sql = "";
				$sql.= "UPDATE cms_login_fail SET ";
				$sql.= " login_fail_count = ? ";
				$sql.= ",last_fail_time = ? ";
				$sql.= "WHERE teacher_email=? ";
				$query = $this->db->query($sql, [
					0,
					date("Y-m-d H:i:s"),
					$data['teacher_email'],
				]);


				// 同じメールアドレスを持つ講師のパスワードを全て更新する
				if($data['teacher_password_identity'] == 1){
					$sql = "UPDATE
								teacher
							SET 
								teacher_password_encrypt = ?,
								update_at                = ?
							WHERE
								teacher_email = ?";
					$this->db->trans_start();
					$this->db->query($sql, 
										array(
											hash('sha256',$data['teacher_password']),
											$wDate,
											$data['teacher_email']
										));
					$this->db->trans_complete();
				}

			}else{
				// パスワード除く更新
				$sql = "UPDATE
							teacher
						SET 
							teacher_name             = ?,
							teacher_email            = ?,
							teacher_auth             = ?,
							teacher_introduce        = ?,
							teacher_introduce_detail = ?,
							teacher_note             = ?,
							bar_association_id       = ?,
							school_id                = ?,
							status                   = ?,
							update_at                = ?
						WHERE
							teacher_id = ?";
				$this->db->trans_start();
				$this->db->query($sql, 
									array(
										$data['teacher_name'],
										$data['teacher_email'],
										$data['teacher_auth'],
										$data['teacher_introduce'],
										$data['teacher_introduce_detail'],
										$data['teacher_note'],
										$data['bar_association_id'],
										$data['school_id'],
										0,
										$wDate,
										$data['teacher_id']
									));
				$this->db->trans_complete();
			}
		
			//修正
/*
			$sql = "UPDATE
						teacher
					SET 
						teacher_name             = ?,
						teacher_email            = ?,
						teacher_password_encrypt = ?,
						teacher_auth             = ?,
						teacher_introduce        = ?,
						teacher_introduce_detail = ?,
						teacher_note             = ?,
						school_id                = ?,
						status                   = ?,
						update_at                = ?
					WHERE
						teacher_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['teacher_name'],
									$data['teacher_email'],
									hash('sha256',$data['teacher_password']),
									$data['teacher_auth'],
									$data['teacher_introduce'],
									$data['teacher_introduce_detail'],
									$data['teacher_note'],
									$data['school_id'],
									0,
									$wDate,
									$data['teacher_id']
								));
			$this->db->trans_complete();
*/
			
		}
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_teacher($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id' => 0,
						),
						$param
					);
		$sql = "UPDATE
					teacher
				SET
					status = 9
				WHERE 
					teacher_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['teacher_id']));
		$this->db->trans_complete();
		
	}
	//----------------------------------------------
	//講師ドロップダウン用一覧取得
	//----------------------------------------------
	function get_teacher_dropdown_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id' => 0,
							'bar_association_id' => 0,
						),
						$param
					);
		//SQL生成
		$sql = "SELECT 
					teacher_id,
					teacher_name
				FROM 
					teacher
				WHERE 
					status = 0
					AND school_id = {$param['school_id']}".
				($param['bar_association_id'] > 1 ?
					' AND bar_association_id = '.$this->db->escape($param['bar_association_id'])
					: ''
				).
				" ORDER BY
					teacher_id
				";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	//----------------------------------------------
	//講師名取得
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										teacher_name
									FROM
										teacher
									WHERE
										teacher_id = '{$param['teacher_id']}'
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data['teacher_name'];
		}else{
			return [];
		}
	}
	
	//----------------------------------------------
	//権限更新処理
	//----------------------------------------------
	function update_teacher_auth($param){
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
		
		//修正
		$sql = "UPDATE
					teacher
				SET 
					teacher_auth   = ?,
					update_at      = ?
				WHERE
					teacher_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, 
							array(
								$data['teacher_auth'],
								$wDate,
								$data['teacher_id']
							));
		$this->db->trans_complete();
	}
	
	//----------------------------------------------
	// [ver2.0]講師名取得（複数指定）
	//----------------------------------------------
	function get_name_multi($param){
		//引数設定
		$param = array_merge(
						array(
							'sub_teacher_id' => array(),
						),
						$param
					);
		
		// 配列⇒カンマ区切り（引用符付き）
		$where_teacher_id  = "'0'";
		foreach ($param['sub_teacher_id'] as $tmp) {
			if($tmp != ''){
				$where_teacher_id .= ",'".$tmp."'";
			}
		} 
		
		//SQL投入
		$query = $this->db->query("
									SELECT
										teacher_name
									FROM
										teacher
									WHERE
										teacher_id IN ( {$where_teacher_id} )
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// ログイン中講師の講座確認
	// 講座がある場合、カンマ区切文字列で返す
	// なお、講座が削除状態または公開期間外のものは取得対象外とする
	//----------------------------------------------
	function get_teacher_lecture_string(){
		$cource_list = '';
		
		$login_teacher_id   = $this->libauth->get_teacher_id();
		$login_teacher_auth = $this->libauth->get_teacher_auth();
		// SUPER USER・学校管理者以外を対象
		/*
		if( ($login_teacher_id>0) && ($login_teacher_auth['school_admin']==0) ){
			$cource_sql = "
				SELECT rel_teacher_lecture.cource_id 
				      ,cource.cource_name 
				  FROM rel_teacher_lecture 
				       LEFT JOIN cource 
				              ON rel_teacher_lecture.cource_id = cource.cource_id 
				 WHERE cource.status = 0
				   AND {$this->db->escape( date('Y/m/d H:i:s') )} BETWEEN cource.cource_open AND cource.cource_close 
				   AND rel_teacher_lecture.teacher_id = {$this->db->escape($login_teacher_id)} ";
			$query = $this->db->query($cource_sql);
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $list_cource_id){
					if($cource_list == ''){
						$cource_list = $list_cource_id['cource_id'];
					}else{
						$cource_list .= ",".$list_cource_id['cource_id'];
					}
				}
			}
		}
		*/
		if( ($login_teacher_id>0) && ($login_teacher_auth['school_admin']==0) ){
			$cource_sql = "
				SELECT cource.cource_id 
				      ,cource.cource_name 
				  FROM cource 
				 WHERE cource.status = 0
				    ";
			$query = $this->db->query($cource_sql);
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $list_cource_id){
					if($cource_list == ''){
						$cource_list = $list_cource_id['cource_id'];
					}else{
						$cource_list .= ",".$list_cource_id['cource_id'];
					}
				}
			}
		}
		return $cource_list;
	}

	//----------------------------------------------
	// ログイン中講師の受講者グループ
	// 受講者グループがある場合、カンマ区切文字列で返す
	// なお、受講者グループが削除状態のものは取得対象外とする
	//----------------------------------------------
	function get_teacher_student_group_string(){
		$student_group = '';
		
		$login_teacher_id   = $this->libauth->get_teacher_id();
		$login_teacher_auth = $this->libauth->get_teacher_auth();
		// SUPER USER・学校管理者以外を対象
		/*
		if( ($login_teacher_id>0) && ($login_teacher_auth['school_admin']==0) ){
			$student_group_sql = "
						SELECT rel_teacher_student_group.student_group_id 
						      ,student_group.student_group_name 
						  FROM rel_teacher_student_group 
						       LEFT JOIN student_group 
						              ON rel_teacher_student_group.student_group_id = student_group.student_group_id
						 WHERE student_group.status = 0 
						   AND rel_teacher_student_group.teacher_id = {$this->db->escape($login_teacher_id)}";
			$query = $this->db->query($student_group_sql);
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $list_student_group_id){
					if($student_group == ''){
						$student_group = $list_student_group_id['student_group_id'];
					}else{
						$student_group .= ",".$list_student_group_id['student_group_id'];
					}
				}
			}
		}
		*/
		if( ($login_teacher_id>0) && ($login_teacher_auth['school_admin']==0) ){
			$cource_sql = "
				SELECT cource.cource_id 
				      ,cource.cource_name 
				  FROM cource 
				 WHERE cource.status = 0
				    ";
			$query = $this->db->query($cource_sql);
			if ($query->num_rows() > 0) {
				foreach($query->result_array() as $list_cource_id){
					if($cource_list == ''){
						$cource_list = $list_cource_id['cource_id'];
					}else{
						$cource_list .= ",".$list_cource_id['cource_id'];
					}
				}
			}
		}
		return $student_group;
	}

}
?>
