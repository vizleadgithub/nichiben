<?php
#[AllowDynamicProperties]
class Model_student_group extends CI_Model
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
	//受講者グループ検索結果一覧取得
	//----------------------------------------------
	function get_student_group_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'          => 0,
							's_student_group_name' => '',
							's_student_id'         => 0,
							's_student_group_id'   => 0,
							's_free_word'          => '',
							'offset'               => 0,
							'rowcount'             => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_student_group_selectsql($param);
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
	//受講者グループ検索結果件数取得
	//----------------------------------------------
	function get_student_group_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'          => 0,
							's_student_group_name' => '',
							's_student_id'         => 0,
							's_student_group_id'   => 0,
							's_free_word'          => '',
							'offset'               => 0,
							'rowcount'             => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_student_group_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//受講者グループ検索SQL文作成
	//----------------------------------------------
	function _get_student_group_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'          => 0,
							's_student_group_name' => '',
							's_student_id'         => 0,
							's_student_group_id'   => 0,
							's_free_word'          => '',
							'offset'               => 0,
							'rowcount'             => 0,
						),
						$param
					);
		//SQL生成
		$sql_select = "SELECT 
							 student_group.student_group_id      AS student_group_id 
							,student_group.student_group_name    AS student_group_name 
							,(SELECT count(*) 
							    FROM rel_student_group 
							   WHERE rel_student_group.student_group_id = student_group.student_group_id) AS student_count 
						FROM 
							student_group ";
		$sql_where = " WHERE 
						student_group.status = 0 ";
		$sql_order = " ORDER BY
						student_group.student_group_id ASC";
		
		//学校ID
		$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
		
		//受講者グループ名
		if (isset($param['s_student_group_name']) && $param['s_student_group_name'] != '') {
			$sql_where .= " AND student_group.student_group_name LIKE '%{$this->db->escape_like_str($param['s_student_group_name'])}%'";
		}
		
		//受講者ID
		if (isset($param['s_student_id']) && $param['s_student_id'] != 0) {
			$sql_where .= " AND student_group_id IN ( 
								SELECT student_group_id 
								  FROM rel_student_group 
								 WHERE student_id = {$this->db->escape($param['s_student_id'])} ) ";
		}
		
		//受講者グループID
		if (isset($param['s_student_group_id']) && $param['s_student_group_id'] != '' ) {
			if(is_numeric($param['s_student_group_id'])){
				$sql_where .= " AND student_group.student_group_id = {$this->db->escape($param['s_student_group_id'])}";
			} else {
				$sql_where .= " AND student_group.student_group_id = 0";
			}
		}
		
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= "
				 AND (
					student_group.student_group_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%' 
					OR 
					student_group.student_group_caption LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%' 
				)";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}

	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_student_group($param){
		//引数設定
		$param = array_merge(
						array(
							'student_group_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										 student_group_id
										,student_group_name
										,student_group_caption
										,school_id
										,status
										,update_at
									FROM
										student_group
									WHERE
										student_group_id = {$this->db->escape($param['student_group_id'])}
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
	// 更新処理
	//----------------------------------------------
	function update_student_group($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		//レスポンス用変数
		$return_data['prev_id'] = -1;
		$return_data['result']  = true;
		$return_data['stat']    = 200;
		$return_data['message'] = '';
		
		// DELETE / INSERT rel_student_group 
		$sql_delete_rel_student_group = "DELETE FROM rel_student_group WHERE student_group_id = ?";
		$sql_insert_rel_student_group = "INSERT INTO rel_student_group (student_group_id, student_id, update_at) VALUES (?,?,?)";
		
		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO student_group (
						 student_group_name
						,student_group_caption
						,school_id
						,status
						,update_at
					) VALUES(?,?,?,?,?)";
			
			$this->db->trans_begin();
			
			// INSERT student
			$this->db->query($sql, array(
								$data['student_group_name'],
								$data['student_group_caption'],
								$data['school_id'],
								0,
								$wDate
							));
			// 登録したstudent_group_idを取得
			$return_data['prev_id'] = $this->db->insert_id();

			// DELETE rel_student_group
			$this->db->query($sql_delete_rel_student_group, array(
								$return_data['prev_id']
							));

			// INSERT rel_student_group
			foreach($data['position_students'] as $student_id) {
				$this->db->query($sql_insert_rel_student_group, array(
									$return_data['prev_id'],
									$student_id,
									$wDate
								));
			}
			
			$this->db->trans_commit();
			
			// 登録処理正常終了
			return $return_data;
		}else{
			// 更新
			$sql = "UPDATE student_group
					SET student_group_name     = ?,
						student_group_caption  = ?,
						school_id              = ?,
						status                 = ?,
						update_at              = ?
					WHERE student_group_id = ?";
			
			$this->db->trans_begin();
				
			$this->db->query($sql, array(
								$data['student_group_name'],
								$data['student_group_caption'],
								$data['school_id'],
								0,
								$wDate,
								$data['student_group_id']
							));

			// 更新したstudent_group_idを取得
			$return_data['prev_id'] = $data['student_group_id'];

			// DELETE rel_student_group
			$this->db->query($sql_delete_rel_student_group, array(
								$return_data['prev_id']
							));

			// INSERT student_lecture
			foreach($data['position_students'] as $student_id) {
				$this->db->query($sql_insert_rel_student_group, array(
									$return_data['prev_id'] ,
									$student_id,
									$wDate
								));
			}

			$this->db->trans_commit();

			// 更新処理正常終了
			return $return_data;
		}
	}

	//----------------------------------------------
	// 削除処理
	//----------------------------------------------
	function delete_student_group($param){
		//引数設定
		$param = array_merge(
						array(
							'student_group_id' => 0,
						),
						$param
					);

		//現在日時取得
		$wDate = date('Y/m/d H:i:s');

		//レスポンス用変数
		$return_data['prev_id'] = $param['student_group_id'];
		$return_data['result']  = true;
		$return_data['stat']    = 200;
		$return_data['message'] = '';
		
		//SQL生成
		$sql = "UPDATE student_group 
				SET status = 9, 
					update_at = ? 
				WHERE student_group_id = ? ";
		
		$this->db->trans_begin();
		$this->db->query($sql, array(
							$wDate,
							$param['student_group_id'],
						));
		// DELETE rel_student_group
	  //$this->db->query('DELETE FROM rel_student_group WHERE student_group_id = ?', array(
	  //					$param['student_group_id']
	  //				));
		$this->db->trans_commit();
		
		return $return_data;
	}

	//----------------------------------------------
	// グループIDから所属受講者ID・受講者名の取得
	//----------------------------------------------
	function get_student_group_position_student($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'         => 0,
							'student_group_id'  => 0,
						),
						$param
					);

		$query = $this->db->query(
			'SELECT rel_student_group.student_id, student.student_name, student.student_email '.
			'  FROM rel_student_group LEFT JOIN student ON rel_student_group.student_id = student.student_id '.
			' WHERE student.school_id = ? '.
			'   AND student.status = 0 '.
			'   AND rel_student_group.student_group_id = ? ',
			array(
				(int) $param['school_id'],
				(int) $param['student_group_id'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// 学校所属の有効グループ名を取得（cms_student/index）
	//----------------------------------------------
	function get_student_group_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'			=> 0,
						),
						$param
					);
		
		//SQL生成
		$sql  = "";
		$sql .= "SELECT student_group_id, student_group_name ";
		$sql .= "  FROM student_group ";
		$sql .= " WHERE status = 0 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= " ORDER BY student_group_id ASC ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [ajax]指定受講者が所属するグループ名・受講者IDを取得
	//（cms_cource/edit）         => school_id, select_student_id
	//（cms_class/edit）          => school_id, select_student_id
	//（cms_class/edit_student/） => school_id, select_student_id
	//----------------------------------------------
	function get_student_group_id_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'			=> 0,
							'bar_association_id' => 0,
							'select_student_id'	=> "",
						),
						$param
					);
		
		//SQL生成
		$sql  = "";
		$sql .= "SELECT student_group.student_group_id, student_group.student_group_name, rel_student_group.student_id ";
		$sql .= "  FROM rel_student_group LEFT JOIN student_group ON rel_student_group.student_group_id = student_group.student_group_id LEFT JOIN student ON rel_student_group.student_id=student.student_id";
		$sql .= " WHERE student_group.school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= "   AND student_group.status = 0 ";
		if($param['select_student_id'] != ""){
			$sql .= "   AND rel_student_group.student_id IN ( {$param['select_student_id']} ) ";
		}
		if($param['bar_association_id'] > 1){
			$sql .= ' AND student.bar_association_id = '.$this->db->escape($param['bar_association_id']);
		}
		$sql .= " ORDER BY student_group.student_group_id ASC, rel_student_group.student_id ASC ";

		$query = $this->db->query($sql);
	
		//データリターン
		if ($query->num_rows() > 0) {
		//	return $query->result_array();
			$student_group = array();
			foreach($query->result_array() as $row){
				if($row['student_group_name']){
					if(!isset($student_group[$row['student_group_name']])){
						$student_group[$row['student_group_name']] = ''.$row['student_id'].'';
					}else{
						$student_group[$row['student_group_name']] = $student_group[$row['student_group_name']].','.$row['student_id'].'';
					}
				}
			}
			ksort($student_group);	// キーの昇順（値昇順はsort）
			return $student_group;
		} else {
			return [];
		}
	}
}
?>
