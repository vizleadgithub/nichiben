<?php
class Model_class extends CI_Model  
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
	//授業検索結果一覧取得
	//----------------------------------------------
	function get_class_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_cource'      => 0,
							's_class_open'  => '',
							's_class_close' => '',
							's_teacher'     => 0,
							's_class_id'    => 0,
							's_free_word'   => '',
							'offset'        => 0,
							'rowcount'      => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_class_selectsql($param);
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
	//授業検索結果一覧件数取得
	//----------------------------------------------
	function get_class_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_cource'      => 0,
							's_class_open'  => '',
							's_class_close' => '',
							's_teacher'     => 0,
							's_class_id'    => 0,
							's_free_word'   => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_class_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//授業検索結果一覧SQL生成
	// [2012/10/16]授業開始日・授業終了日のSQL文変更
	//----------------------------------------------
	function _get_class_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'   => 0,
							's_cource'      => 0,
							's_class_open'  => '',
							's_class_close' => '',
							's_teacher'     => 0,
							's_class_id'    => 0,
							's_free_word'   => '',
						),
						$param
					);
		//SQL生成
		$sql_select = "SELECT 
							class.*, 
							DATE_FORMAT(class.class_open , '%Y/%m/%d') AS class_date,
							cource.cource_name, 
							DATE_FORMAT(class.class_open , '%H:%i:%s') AS class_opentime,
							DATE_FORMAT(class.class_close, '%H:%i:%s') AS class_closetime,
							teacher.teacher_name, 
							NULL as class_condition
						FROM class 
							LEFT JOIN cource ON class.cource_id = cource.cource_id AND cource.status = 0
								LEFT JOIN teacher ON class.teacher_id = teacher.teacher_id AND teacher.status = 0
					";
		$sql_where = " WHERE class.status = 0";
//		$sql_order = " ORDER BY class.class_id";
		$sql_order = " ORDER BY class.update_at DESC";
		
		//学校ID
		if (isset($param['s_school_id']) && $param['s_school_id'] != 0) {
			$sql_where .= " AND class.school_id = {$this->db->escape($param['s_school_id'])}";
		}
		//講座
		if (isset($param['s_cource']) && $param['s_cource'] != 0) {
			$sql_where .= " AND class.cource_id = {$this->db->escape($param['s_cource'])}";
		}
		//授業開始日
		if (isset($param['s_class_open']) && $param['s_class_open'] != '') {
		//	$sql_where .= " AND   {$this->db->escape($param['s_class_open'])} BETWEEN class.class_open AND class.class_close";
			$sql_where .= " AND (({$this->db->escape($param['s_class_open'])} BETWEEN class.class_open AND class.class_close)
							 OR  (class.class_open >= {$this->db->escape($param['s_class_open'])}))";
		}
		//授業終了日
		if (isset($param['s_class_close']) && $param['s_class_close'] != '') {
		//	$sql_where .= " AND   {$this->db->escape($param['s_class_close'])} BETWEEN class.class_open AND class.class_close";
			$sql_where .= " AND (({$this->db->escape($param['s_class_close'])} BETWEEN class.class_open AND class.class_close)
							 OR  (class.class_close <= {$this->db->escape($param['s_class_close'])}))";
		}
		//担当
		if (isset($param['s_teacher']) && $param['s_teacher'] != 0) {
			$sql_where .= " AND class.teacher_id = {$this->db->escape($param['s_teacher'])}";
		}
		//ID
		if (isset($param['s_class_id']) && $param['s_class_id'] != '' ) {
			if(is_numeric($param['s_class_id'])){
				$sql_where .= " AND class_id = {$this->db->escape($param['s_class_id'])}";
			} else {
				$sql_where .= " AND class_id = 0";
			}
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (class.class_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR class.class_caption LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR class.class_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
//	//----------------------------------------------
//	//トップページ用授業一覧取得
//	//----------------------------------------------
//	function get_class_list_toppage($param) {
//		//引数設定
//		$param = array_merge(
//						array(
//							'school_id'  => 0,
//							'teacher_id' => 0,
//						),
//						$param
//					);
//		//日付設定
//		$day_second = 86400;
//		$wnow       = date('Y/m/d H:i:s');
//		$wa7day     = date('Y/m/d H:i:s',strtotime($wnow) + ($day_second * 7));
//		
//		//SQL生成
//		$sql_select = "SELECT 
//							class.*, 
//							DATE_FORMAT(class.class_open , '%Y/%m/%d') AS class_date,
//							cource.cource_name, 
//							DATE_FORMAT(class.class_open , '%H:%i:%s') AS class_opentime,
//							DATE_FORMAT(class.class_close, '%H:%i:%s') AS class_closetime,
//							teacher.teacher_name, 
//							NULL as class_condition
//						FROM class 
//							LEFT JOIN cource ON class.cource_id = cource.cource_id AND cource.status = 0
//								LEFT JOIN teacher ON class.teacher_id = teacher.teacher_id AND teacher.status = 0
//					";
//					
//		$sql_where = " WHERE 
//							class.status = 0
//							AND class.school_id = {$this->db->escape($param['school_id'])}
//							AND ( 
//									class_open  BETWEEN {$this->db->escape($wnow)} AND {$this->db->escape($wa7day)}
//									OR 
//									class_close BETWEEN {$this->db->escape($wnow)} AND {$this->db->escape($wa7day)}
//								)
//							";
//		$sql_order = " ORDER BY class.class_open, class.class_close";
//		
//		if ($param['teacher_id'] != -1) {
//			$sql_where .= " AND class.teacher_id = {$this->db->escape($param['teacher_id'])}";
//		}
//		
//		$query = $this->db->query($sql_select . $sql_where . $sql_order);
//		
//		//データリターン
//		if ($query->num_rows() > 0) {
//			return $query->result_array();
//		} else {
//			return null;
//		}
//	}
	
	//----------------------------------------------
	//担当授業一覧取得
	//----------------------------------------------
	function get_class_tantou_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'  => 0,
							'teacher_id' => 0,
							'sel_month'  => 0,
							'offset'     => 0,
							'rowcount'   => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_class_tantou_selectsql($param);
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
	//担当授業一覧件数取得
	//----------------------------------------------
	function get_class_tantou_count($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'  => 0,
							'teacher_id' => 0,
							'sel_month'  => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_class_tantou_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//担当授業一覧SQL生成
	//----------------------------------------------
	function _get_class_tantou_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'  => 0,
							'teacher_id' => 0,
							'sel_month'  => 0,
						),
						$param
					);
		
		//SQL生成
		$sql_select = "SELECT 
							class.*, 
							DATE_FORMAT(class.class_open , '%Y/%m/%d') AS class_date,
							cource.cource_name, 
							DATE_FORMAT(class.class_open , '%H:%i:%s') AS class_opentime,
							DATE_FORMAT(class.class_close, '%H:%i:%s') AS class_closetime,
							teacher.teacher_name, 
							NULL as class_condition
						FROM class 
							LEFT JOIN cource ON class.cource_id = cource.cource_id AND cource.status = 0
								LEFT JOIN teacher ON class.teacher_id = teacher.teacher_id AND teacher.status = 0
					";
		$sql_where = " WHERE class.status = 0";
//		$sql_order = " ORDER BY class.class_id";
		$sql_order = " ORDER BY class.update_at DESC";
		
		//学校ID
		if (isset($param['school_id']) && $param['school_id'] != 0) {
			$sql_where .= " AND class.school_id = {$this->db->escape($param['school_id'])}";
		}
		//担当
		if (isset($param['teacher_id']) && $param['teacher_id'] != 0 && $param['teacher_id'] != -1) {
			$sql_where .= " AND class.teacher_id = {$this->db->escape($param['teacher_id'])}";
		}
		//月
		if (isset($param['sel_month']) && $param['sel_month'] != 0 ) {
			$sql_where .= " AND DATE_FORMAT(class.class_open,'%Y%m') = {$this->db->escape($param['sel_month'])}";
		}
		
		return $sql_select . $sql_where;
	}
	
	//----------------------------------------------
	//一件取得
	// class_close_check→授業開始後更新時のチェック用
	//----------------------------------------------
	function get_class($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										class_id,
										class_name,
										class_type,
										DATE_FORMAT(class_open,  '%Y/%m/%d') as class_date,
										DATE_FORMAT(class_open,  '%H:%i:%s') as class_opentime,
										DATE_FORMAT(class_close, '%H:%i:%s') as class_closetime,
										DATE_FORMAT(class_close, '%Y/%m/%d %H:%i:%s') as class_close_check,
										CASE WHEN TIMEDIFF(class_close, class_open)>='23:59:00' THEN 1 ELSE 0 END  class_maxtime_flag,
										teacher_id,
										class_caption,
										fixed_number,
										class_note,
										school_id,
										cource_id,
										status,
										update_at
									FROM
										class
									WHERE
										class_id = {$this->db->escape($param['class_id'])}
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}
	
	//----------------------------------------------
	//受講者取得
	//----------------------------------------------
	function get_student_lecture_class($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										student_id
									FROM
										student_lecture_class
									WHERE
										class_id = {$this->db->escape($param['class_id'])}
									ORDER BY
										student_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return null;
		}
	}
	
	//----------------------------------------------
	//更新処理
	//----------------------------------------------
	function update_class($param){
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
		
		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO
						class 
					(
						class_type,
						class_name,
						class_open,
						class_close,
						teacher_id,
						class_caption,
						fixed_number,
						class_note,
						school_id,
						cource_id,
						subject_id,
						status,
						update_at
					)
					VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['class_type'],
									$data['class_name'],
									$data['class_open'],
									$data['class_close'],
									$data['teacher_id'],
									$data['class_caption'],
									$data['fixed_number'],
									$data['class_note'],
									$data['school_id'],
									$data['cource_id'],
									0,
									0,
									$wDate
								));
			//登録idを取得
			$prev_id = $this->db->insert_id();
			
			$this->db->trans_complete();
			
			return array('prev_id' => $prev_id);
		}else{

			// 修正前授業情報の取得
			$old_db_data = $this->get_class(array('class_id' => $data['class_id']));

			//修正
			$sql = "UPDATE
						class
					SET 
						class_type    = ?,
						class_name    = ?,
						class_open    = ?,
						class_close   = ?,
						teacher_id    = ?,
						class_caption = ?,
						fixed_number  = ?,
						class_note    = ?,
						school_id     = ?,
						cource_id     = ?,
						subject_id    = ?,
						status        = ?,
						update_at     = ?
					WHERE
						class_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['class_type'],
									$data['class_name'],
									$data['class_open'],
									$data['class_close'],
									$data['teacher_id'],
									$data['class_caption'],
									$data['fixed_number'],
									$data['class_note'],
									$data['school_id'],
									$data['cource_id'],
									0,
									0,
									$wDate,
									$data['class_id']
								));
			$this->db->trans_complete();

			// 授業内容変更時、講師を変更した場合の処理
			if($old_db_data['teacher_id'] != $data['teacher_id']){
				// model Load
				$this->load->model('model_class_material');
				$param_change = array(
								'old_teacher_id'	 => $old_db_data['teacher_id'],
								'new_teacher_id'	 => $data['teacher_id'],
								'class_id'			 => $data['class_id'],
							);
				$results = $this->model_class_material->change_teacher_class_material($param_change);
			}
			
			return array('prev_id' => $data['class_id']);
		}
	}
	
	//----------------------------------------------
	//受講者更新処理
	//----------------------------------------------
	function update_student_lecture_class($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM student_lecture_class
				WHERE
					class_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['class_id']
							));
		
		//選択受講講座を登録
		foreach($data['lecture_students'] as $student_id) {
			$sql = "INSERT INTO
						student_lecture_class
					(
						student_id,
						class_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$student_id,
									$param['class_id'],
									$wDate
								));
		}
		$this->db->trans_complete();
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_class($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id' => 0,
						),
						$param
					);
		$sql = "UPDATE
					class
				SET
					status = 9
				WHERE 
					class_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['class_id']));
		$this->db->trans_complete();
		
	}
	
	//----------------------------------------------
	//授業名取得
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										class_name
									FROM
										class
									WHERE
										class_id = '{$param['class_id']}'
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data['class_name'];
		}else{
			return null;
		}
	}
	//----------------------------------------------
	//講座ID取得
	//----------------------------------------------
	function get_cource_id_with_class($param){
		//引数設定
		$param = array_merge(
						array(
							'class_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										class
									WHERE
										class_id = '{$param['class_id']}'
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data['cource_id'];
		}else{
			return null;
		}
	}

}
?>
