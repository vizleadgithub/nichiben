<?php
#[AllowDynamicProperties]
class Model_exam2_problem_group extends CI_Model  
{
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	
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
	//設問グループ一覧取得
	//----------------------------------------------
	function get_exam2_problem_group_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'                 => 0,
				'offset'                    => 0,
				'rowcount'                  => 10,
				's_exam2_problem_group_name' => '',  // 設問グループ名
				's_exam2_problem_id'         => 0,   // 設問ID
				's_exam2_problem_group_id'   => 0,   // 設問グループID
				's_free_word'               => '',  // フリーワード
			),
			$param
		);

		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
//		$sql_where = '';
		$login_exam2_problem_id = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
//			$sql_where .= " AND exam2_problem.exam2_problem_id IN (
//							SELECT rel_exam2_problem_lecture.exam2_id 
//							  FROM rel_exam2_problem_lecture 
//							 WHERE rel_exam2_problem_lecture.cource_id IN (".$cource_list.")
//						) ";
			$login_exam2_problem_id = $this->_get_exam2_problem_id_string(array('cource_id' => $cource_list));
		}
		
		//SQL生成
		$query = $this->db->query("
			 SELECT SQL_CALC_FOUND_ROWS 
			        exam2_problem_group.exam2_problem_group_id      AS exam2_problem_group_id 
			       ,exam2_problem_group.exam2_problem_group_name    AS exam2_problem_group_name 
			       ,(SELECT count(*) 
			           FROM rel_exam2_problem_group 
			          WHERE rel_exam2_problem_group.exam2_problem_group_id = exam2_problem_group.exam2_problem_group_id) AS exam2_problem_count 
			   FROM exam2_problem_group LEFT JOIN rel_exam2_problem_group ON exam2_problem_group.exam2_problem_group_id=rel_exam2_problem_group.exam2_problem_group_id LEFT JOIN exam2_problem ON rel_exam2_problem_group.exam2_problem_id=exam2_problem.exam2_problem_id
			  WHERE exam2_problem_group.school_id  = ? 
			    AND exam2_problem_group.status    <> 9 
			".
			($login_exam2_problem_id != '' ?
				' AND exam2_problem_group.exam2_problem_group_id IN (SELECT exam2_problem_group_id FROM rel_exam2_problem_group WHERE exam2_problem_id IN ('.$this->db->escape_str($login_exam2_problem_id).'))'
				: ''
			).
			($param['s_exam2_problem_group_name'] ?
				' AND exam2_problem_group.exam2_problem_group_name LIKE '.'"%'.$this->db->escape_like_str($param['s_exam2_problem_group_name']).'%"'
				: ''
			).
			($param['s_exam2_problem_id'] > 0 ?
				' AND exam2_problem_group.exam2_problem_group_id IN (
				      SELECT exam2_problem_group_id FROM rel_exam2_problem_group WHERE exam2_problem_id = '.$this->db->escape($param['s_exam2_problem_id']).' )'
				: ''
			).
			( ((isset($param['s_exam2_problem_group_id'])) && ($param['s_exam2_problem_group_id'] != '')) ?  
				' AND exam2_problem_group.exam2_problem_group_id = '.$this->db->escape($param['s_exam2_problem_group_id']).' '  
				: ''
			).
			($param['s_free_word'] ?
				' AND ('.
				'    exam2_problem_group.exam2_problem_group_name    LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' OR exam2_problem_group.exam2_problem_group_id LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' OR exam2_problem.exam2_problem_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			' GROUP BY exam2_problem_group.exam2_problem_group_id '.
			' ORDER BY exam2_problem_group.exam2_problem_group_id DESC '.
			' LIMIT ?, ?',
			array(
				(int) $param['school_id'],
				(int) $param['offset'],
				(int) $param['rowcount'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			$cnt = $this->db->query('SELECT FOUND_ROWS() as rowcount');
			$cnt = $cnt->row_array();
			return array(
				'cnt'	=> $cnt['rowcount'],
				'items'	=> $query->result_array(),
			);
		} else {
			return array(
				'cnt'   => 0,
				'items' => array(),
			);
		}
	}

	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_exam2_problem_group($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_problem_group_id' => 0,
						),
						$param
					);
		
		//SQL生成
		$query = $this->db->query("
			 SELECT exam2_problem_group.exam2_problem_group_id
			       ,exam2_problem_group.exam2_problem_group_name
			       ,exam2_problem_group.exam2_problem_group_caption
			       ,exam2_problem_group.school_id
			       ,exam2_problem_group.status
			       ,exam2_problem_group.update_at
			   FROM exam2_problem_group
			  WHERE exam2_problem_group.exam2_problem_group_id  = {$this->db->escape($param['exam2_problem_group_id'])}  
			    AND exam2_problem_group.status                <> 9 
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//新規登録・更新処理
	//----------------------------------------------
	function update_exam2_problem_group($param){
		
		$this->load->helper('json');
		
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];

		$lastInsertId = 0;

		if ($data['update_flg'] == 0){
			//新規
			$data = array_merge(array(
					'exam2_problem_group_name'    => 'no value',
					'exam2_problem_group_caption' => '',
					'school_id'                  => 0,
					'status'                     => 0,
					'update_at'                  => date("Y/m/d H:i:s"),
			), $data);
			
			$res = $this->db->query($this->db->insert_string('exam2_problem_group', array(
				//	'exam2_problem_group_id'      => '',                                   /* 設問グループID */
					'exam2_problem_group_name'    => $data['exam2_problem_group_name'],     /* 設問グループ名 */
					'exam2_problem_group_caption' => $data['exam2_problem_group_caption'],  /* 説明 */
					'school_id'                  => $data['school_id'],                   /* 学校ID */
					'status'                     => $data['status'],                      /* 状態 0:有効 9:削除 */
					'update_at'                  => $data['update_at'],                   /* 更新日時 */
				)
			));
			
			$lastInsertId = $this->db->insert_id();
		}else{
			//修正
			$res = $this->db->query($this->db->update_string('exam2_problem_group', array(
					'exam2_problem_group_name'    => $data['exam2_problem_group_name'],     /* 設問グループ名 */
					'exam2_problem_group_caption' => $data['exam2_problem_group_caption'],  /* 説明 */
					'school_id'                  => $data['school_id'],                   /* 学校ID */
				//	'status'                     => $data['status'],                      /* 状態 0:有効 9:削除 */
					'update_at'                  => date('Y/m/d H:i:s'),                  /* 更新日時 */
				),'exam2_problem_group_id='.$data['exam2_problem_group_id']
			));
			// ),'exam2_problem_group_id='.$param['exam2_problem_group_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
			
			$lastInsertId = $data['exam2_problem_group_id'];
		}
		
		
		
		// 設問グループ関係テーブルの更新
		if($lastInsertId > 0){
			$wDate = date('Y/m/d H:i:s');
			
			$this->db->trans_begin();
			
			// DELETE rel_exam2_problem_group
			$this->db->query("
				DELETE FROM rel_exam2_problem_group WHERE exam2_problem_group_id = ? 
				", 
				array(
					$lastInsertId
			));

			// INSERT rel_exam2_problem_group
			foreach($data['position_exam2_problems'] as $exam2_problem_id) {
				$this->db->query("
					INSERT INTO rel_exam2_problem_group (exam2_problem_group_id, exam2_problem_id, update_at) VALUES (?,?,?)
					", 
					array(
						$lastInsertId ,
						$exam2_problem_id,
						$wDate
				));
			}
			$this->db->trans_commit();
		}

		return array(
			'lastInsertId'	=> $lastInsertId,
		);
	}

	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_exam2_problem_group($param){
		//引数設定
		$param = array_merge(
			array(
				'exam2_problem_group_id'  => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('exam2_problem_group', array(
				'status'     => 9,
				'update_at'  => date('Y/m/d H:i:s'),
			),'exam2_problem_group_id='.$param['exam2_problem_group_id']
		));
		// ),'exam2_id='.$param['exam2_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
		
		return $res;
	}

	//----------------------------------------------
	// 設問グループIDから設問ID・設問名の取得
	//----------------------------------------------
	function get_exam2_problem_group_position_exam2_problem($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'              => 0,
							'exam2_problem_group_id'  => 0,
						),
						$param
					);
		$query = $this->db->query("
			SELECT  rel_exam2_problem_group.exam2_problem_id AS exam2_problem_id 
			       ,exam2_problem.exam2_problem_name         AS exam2_problem_name 
			       ,exam2_problem.answer_point              AS answer_point 
			       ,teacher.teacher_name                   AS teacher_name 
			  FROM  rel_exam2_problem_group LEFT JOIN exam2_problem ON rel_exam2_problem_group.exam2_problem_id = exam2_problem.exam2_problem_id 
			                               LEFT JOIN teacher      ON exam2_problem.teacher_id = teacher.teacher_id 
			 WHERE  exam2_problem.school_id = ? 
			   AND  exam2_problem.status    = 0 
			   AND  rel_exam2_problem_group.exam2_problem_group_id = ?
			",
			array(
				(int) $param['school_id'],
				(int) $param['exam2_problem_group_id'],
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
	//講座IDから、設問IDを取得
	//複数の場合、カンマ区切り文字列
	//----------------------------------------------
	function _get_exam2_problem_id_string($param){
		//引数設定
		$param = array_merge(
						array(
							'cource_id'  => "0",
						),
						$param
					);
		$exam2_problem_id_list = '0';
		
		//SQL投入
		$query = $this->db->query("
			SELECT exam2_problem_id
			  FROM rel_exam2_problem_lecture
			 WHERE cource_id IN (".$this->db->escape_str($param['cource_id']).")
			 ORDER BY exam2_problem_id
		");
		
		//データリターン
		if ($query->num_rows() > 0) {
			foreach($query->result_array() as $list_rel_exam2_problem_id){
				if($exam2_problem_id_list != ''){
					$exam2_problem_id_list .= ",";
				}
				$exam2_problem_id_list .= $list_rel_exam2_problem_id['exam2_problem_id'];
			}
		}
		return $exam2_problem_id_list;
	}

	//----------------------------------------------
	// 設問グループドロップダウン用一覧取得（設問index）
	//----------------------------------------------
	function get_exam2_problem_dropdown_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		// SQL生成
		$sql = "
			SELECT exam2_problem_group.exam2_problem_group_id 
		 		  ,exam2_problem_group.exam2_problem_group_name
			  FROM exam2_problem_group
			 WHERE exam2_problem_group.status = 0
			   AND exam2_problem_group.school_id = {$this->db->escape($param['school_id'])}
		";

		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$login_exam2_problem_id = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$login_exam2_problem_id = $this->_get_exam2_problem_id_string(array('cource_id' => $cource_list));
			$sql .= ' AND exam2_problem_group.exam2_problem_group_id IN (SELECT exam2_problem_group_id FROM rel_exam2_problem_group WHERE exam2_problem_id IN ('.$this->db->escape_str($login_exam2_problem_id).'))';
		}
		
		$sql .= " ORDER BY exam2_problem_group.exam2_problem_group_name ASC ";
		
		$query = $this->db->query($sql, array());
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 設問グループチェックボックス用一覧取得（設問インポートedit）
	//   設問グループに所属する設問数も取得
	//----------------------------------------------
	function get_exam2_problem_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		// SQL生成
		$sql = "
			SELECT exam2_problem_group.exam2_problem_group_id 
				  ,exam2_problem_group.exam2_problem_group_name
				  ,(SELECT count(*) 
				      FROM rel_exam2_problem_group 
				     WHERE rel_exam2_problem_group.exam2_problem_group_id = exam2_problem_group.exam2_problem_group_id) AS exam2_problem_count 
			  FROM exam2_problem_group
			 WHERE exam2_problem_group.status = 0
			   AND exam2_problem_group.school_id = {$this->db->escape($param['school_id'])}
		";
		
		$sql .= " ORDER BY exam2_problem_group.exam2_problem_group_name ASC ";
		
		$query = $this->db->query($sql, array());
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 設問グループと設問の関係を作成（設問インポート）
	//----------------------------------------------
	function import_relations_exam2_problem_group($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_problem_id'  => 0,
							'data'             => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//登録
		foreach($data['exam2_problem_groups'] as $exam2_problem_group_id) {
			$sql = "INSERT INTO
						rel_exam2_problem_group
					(
						exam2_problem_group_id,
						exam2_problem_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$exam2_problem_group_id,
									$param['exam2_problem_id'],
									$wDate
								));
		}
		$this->db->trans_complete();
	}



	//----------------------------------------------
	// [ajax]指定設問が所属する設問グループ名・受講者IDを取得
	//（cms_cource/edit）         => school_id, select_exam2_problem_id
	//（cms_exam2/edit）           => school_id, select_exam2_problem_id
	//----------------------------------------------
	function get_exam2_problem_group_id_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'              => 0,
							'select_exam2_problem_id' => "",
							'cource_flag'            => 0,    // 0:設問グループ名=>設問ID,設問ID…、1:設問グループ名=>設問グループID
						),
						$param
					);
		
		//SQL生成
		$sql = "
			 SELECT  exam2_problem_group.exam2_problem_group_id 
			        ,exam2_problem_group.exam2_problem_group_name 
			        ,rel_exam2_problem_group.exam2_problem_id
			 FROM rel_exam2_problem_group LEFT JOIN exam2_problem_group ON rel_exam2_problem_group.exam2_problem_group_id = exam2_problem_group.exam2_problem_group_id
			 WHERE exam2_problem_group.school_id = {$this->db->escape($param['school_id'])} 
			  AND exam2_problem_group.status = 0 
		";

		if($param['select_exam2_problem_id'] != ""){
			$sql .= "   AND rel_exam2_problem_group.exam2_problem_id IN ( {$this->db->escape_str($param['select_exam2_problem_id'])} ) ";
		}
		$sql .= " ORDER BY exam2_problem_group.exam2_problem_group_id ASC, rel_exam2_problem_group.exam2_problem_id ASC ";

		$query = $this->db->query($sql);
	
		//データリターン
		if ($query->num_rows() > 0) {
		//	return $query->result_array();
			$exam2_problem_group = array();
			foreach($query->result_array() as $row){
				if($row['exam2_problem_group_name']){

					if($param['cource_flag']==0){
						if(!isset($exam2_problem_group[$row['exam2_problem_group_name']])){
							$exam2_problem_group[$row['exam2_problem_group_name']] = ''.$row['exam2_problem_id'].'';
						}else{
							$exam2_problem_group[$row['exam2_problem_group_name']] = $exam2_problem_group[$row['exam2_problem_group_name']].','.$row['exam2_problem_id'].'';
						}
					}else{
						$exam2_problem_group[$row['exam2_problem_group_name']] = ''.$row['exam2_problem_group_id'].'';
					}
					//if(!isset($exam2_problem_group[$row['exam2_problem_group_name']])){
					//	$exam2_problem_group[$row['exam2_problem_group_name']] = ''.$row['exam2_problem_id'].'';
					//}else{
					//	$exam2_problem_group[$row['exam2_problem_group_name']] = $exam2_problem_group[$row['exam2_problem_group_name']].','.$row['exam2_problem_id'].'';
					//}
				}
			}
			ksort($exam2_problem_group);	// キーの昇順（値昇順はsort）
			return $exam2_problem_group;
		} else {
			return [];
		}
	}

}
?>
