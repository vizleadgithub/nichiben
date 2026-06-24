<?php
#[AllowDynamicProperties]
class Model_exam2 extends CI_Model  
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
	//問題一覧取得
	//----------------------------------------------
	function get_exam2_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'    => 0,
				'bar_association_id' => 0,
				'offset'       => 0,
				'rowcount'     => 10,
				's_cource'     => 0,
				's_free_word'  => '',
			),
			$param
		);

		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$sql_where = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND exam2.exam2_id IN (
							SELECT rel_exam2_lecture.exam2_id 
							  FROM rel_exam2_lecture 
							 WHERE rel_exam2_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
						) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql_where .= " AND exam2.exam2_id IN (
							SELECT rel_exam2_student_group.exam2_id 
							  FROM rel_exam2_student_group 
							 WHERE rel_exam2_student_group.student_group_id IN (".$this->db->escape_str($student_group_list).")
						) ";
		}

		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS '.
			'        exam2.exam2_id          AS exam2_id '.
			'       ,exam2.exam2_name        AS exam2_name '.
			'       ,exam2.exam2_caption     AS exam2_caption '.
			"       ,DATE_FORMAT(exam2.exam2_open  , '%Y%m%d%H%i%s') AS exam2_open ".
			"       ,DATE_FORMAT(exam2.exam2_close , '%Y%m%d%H%i%s') AS exam2_close ".
			'       ,exam2.public_flag      AS public_flag '.
			'       ,exam2.school_id        AS school_id '.
			'       ,exam2.teacher_id       AS teacher_id '.
			'       ,exam2.display_format   AS display_format '.
			'       ,exam2.status           AS status '.
			'       ,exam2.update_at        AS update_at '.
			'       ,exam2.criteria_type    AS criteria_type '.
			'       ,exam2.criteria_value   AS criteria_value '.
			'       ,teacher.teacher_name  AS teacher_name '.
			'   FROM exam2 LEFT JOIN teacher ON teacher.teacher_id = exam2.teacher_id '.
			'  WHERE exam2.school_id = ? '.
			'    AND exam2.status <> 9 '.
			($param['s_free_word'] ?
				' AND ('.
				' 	exam2.exam2_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR exam2.exam2_id LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND exam2.exam2_id IN (SELECT exam2_id FROM rel_exam2_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			($param['bar_association_id'] > 1 ?
				' AND teacher.bar_association_id = '.$this->db->escape($param['bar_association_id'])
				: ''
			).
			($sql_where != '' ?
				$sql_where
				: ''
			).
			' ORDER BY exam2.exam2_id DESC'.
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
				'cnt'	=> 0,
				'items'	=> array(),
			);
		}
	}

	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_exam2($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
						),
						$param
					);
		
		//SQL生成
		$query = $this->db->query(
			' SELECT exam2.exam2_id               AS exam2_id '.
			'       ,exam2.exam2_name             AS exam2_name '.
			'       ,exam2.exam2_caption          AS exam2_caption '.
			"       ,DATE_FORMAT(exam2.exam2_open  , '%Y/%m/%d %H:%i:%s')  AS exam2_open ".
			"       ,DATE_FORMAT(exam2.exam2_close , '%Y/%m/%d %H:%i:%s')  AS exam2_close ".
			'       ,exam2.public_flag           AS public_flag '.
			'       ,exam2.resubmit_flag         AS resubmit_flag '.
			'       ,exam2.marking_public_flag   AS marking_public_flag '.
			'       ,exam2.marking_public_kind   AS marking_public_kind '.
			"       ,DATE_FORMAT(exam2.marking_public_open , '%Y/%m/%d %H:%i:%s')  AS marking_public_open ".
			'       ,exam2.school_id             AS school_id '.
			'       ,exam2.teacher_id            AS teacher_id '.
			'       ,exam2.display_format        AS display_format '.
			'       ,exam2.status                AS status '.
			'       ,exam2.update_at             AS update_at '.
			'       ,exam2.criteria_type         AS criteria_type '.
			'       ,exam2.criteria_value        AS criteria_value '.
			'       ,teacher.teacher_name       AS teacher_name '.
			'   FROM exam2 LEFT JOIN teacher ON teacher.teacher_id = exam2.teacher_id '.
			"  WHERE exam2.exam2_id = {$this->db->escape($param['exam2_id'])} ".
			'    AND exam2.status <> 9 '
		);
		
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
	function update_exam2($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];
		
		// 採点公開種類（marking_public_kind）が「1:即時公開」の場合、採点公開日時（marking_public_open）をNULLに変更
		if( $data['marking_public_kind']==1 ) $data['marking_public_open'] = NULL;
		
		if ($data['update_flg'] == 0){
			//新規
			$data = array_merge(array(
					'exam2_name'            => 'no value',
					'exam2_caption'         => '',
					'exam2_open'            => date("Y/m/d H:i:s"),
					'exam2_close'           => date("Y/m/d H:i:s"),
					'public_flag'          => 0,
					'resubmit_flag'        => 1,
					'marking_public_flag'  => 9,
					'marking_public_kind'  => 1,
					'marking_public_open'  => NULL,
					'school_id'            => 0,
					'teacher_id'           => 0,
					'display_format'       => 0,
					'status'               => 0,
					'update_at'            => date("Y/m/d H:i:s"),
					'criteria_type'        => 1,
					'criteria_value'       => 0,
			), $data);
			
			$res = $this->db->query($this->db->insert_string('exam2', array(
				//	'exam2_id'              => '',                            /* 問題ID */
					'exam2_name'            => $data['exam2_name'],            /* 問題名 */
					'exam2_caption'         => $data['exam2_caption'],         /* 説明 */
					'exam2_open'            => $data['exam2_open'],            /* 提出期限開始日時 */
					'exam2_close'           => $data['exam2_close'],           /* 提出期限終了日時 */
					'public_flag'          => $data['public_flag'],          /* 公開フラグ 0:公開 9:非公開 */
					'resubmit_flag'        => $data['resubmit_flag'],        /* 再提出フラグ 0:不可 1:可 */
					'marking_public_flag'  => $data['marking_public_flag'],  /* 採点公開フラグ 0:公開 9:非公開 */
					'marking_public_kind'  => $data['marking_public_kind'],  /* 採点公開種類 1:即時公開 2:時限式公開 */
					'marking_public_open'  => $data['marking_public_open'],  /* 採点公開日時 */
					'school_id'            => $data['school_id'],            /* 学校ID */
					'teacher_id'           => $data['teacher_id'],           /* 講師ID */
					'display_format'       => $data['display_format'],       /* 表示形式 0:一括解答形式 1:一問一答形式 */
					'status'               => $data['status'],               /* 状態 0:有効 9:削除 */
					'update_at'            => $data['update_at'],            /* 更新日時 */
					'criteria_type'        => $data['criteria_type'],          /* 判定基準タイプ 1:点数 2:割合 3:正答数 */
					'criteria_value'       => $data['criteria_value'],          /* 判定基準値 */
				)
			));
			
			$lastInsertId = $this->db->insert_id();
			
			return array(
				'lastInsertId'	=> $lastInsertId,
			);
		}else{
			//修正
			$res = $this->db->query($this->db->update_string('exam2', array(
					'exam2_name'            => $data['exam2_name'],
					'exam2_caption'         => $data['exam2_caption'],
					'exam2_open'            => $data['exam2_open'],
					'exam2_close'           => $data['exam2_close'],
					'public_flag'          => $data['public_flag'],
					'resubmit_flag'        => $data['resubmit_flag'],
					'marking_public_flag'  => $data['marking_public_flag'],
					'marking_public_kind'  => $data['marking_public_kind'],
					'marking_public_open'  => $data['marking_public_open'],
					'school_id'            => $data['school_id'],
					'teacher_id'           => $data['teacher_id'],
					'display_format'       => $data['display_format'],       /* 表示形式 0:一括解答形式 1:一問一答形式 */
				//	'status'               => $data['status'],
					'update_at'            => date('Y/m/d H:i:s'),
					'criteria_type'        => $data['criteria_type'],
					'criteria_value'       => $data['criteria_value'],
				),'exam2_id='.$data['exam2_id']
			));
			// ),'exam2_id='.$param['exam2_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
			
			return array(
				'lastInsertId'	=> $data['exam2_id'],
			);
		}
	}

	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_item($param){
		//引数設定
		$param = array_merge(
			array(
				'exam2_id'  => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('exam2', array(
				'status'     => 9,
				'update_at'  => date('Y/m/d H:i:s'),
			),'exam2_id='.$param['exam2_id']
		));
		// ),'exam2_id='.$param['exam2_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
		
		return $res;
	}

	//----------------------------------------------
	// 問題講座テーブルから講座ID取得
	//----------------------------------------------
	function get_exam2_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										rel_exam2_lecture
									WHERE
										exam2_id = {$this->db->escape($param['exam2_id'])}
									ORDER BY
										cource_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// 問題講座テーブルの登録更新処理
	//----------------------------------------------
	function update_exam2_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id'  => 0,
							'data'     => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_exam2_lecture
				WHERE
					exam2_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['exam2_id'],
							));
		
		//選択受講講座を登録
		foreach($data['exam2_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						rel_exam2_lecture
					(
						exam2_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['exam2_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// 問題設問関係テーブルから設問ID取得
	//   問題ID・問題内順番・設問ID・設問名・解答配点・講師ID・講師名
	//----------------------------------------------
	function get_exam2_problems($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
						),
						$param
					);
		
		//SQL投入
		//$query = $this->db->query("
		//	 SELECT rel_exam2_problem.exam2_id, rel_exam2_problem.exam2_no, rel_exam2_problem.exam2_problem_id 
		//	   FROM rel_exam2_problem 
		//	        LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id = exam2_problem.exam2_problem_id
		//	  WHERE rel_exam2_problem.exam2_id = {$this->db->escape($param['exam2_id'])} 
		//	    AND exam2_problem.status      = 0 
		//	    AND rel_exam2_problem.exam2_problem_id IN (
		//	        SELECT rel_exam2_problem_lecture.exam2_problem_id
		//	          FROM rel_exam2_problem_lecture
		//	         WHERE rel_exam2_problem_lecture.cource_id IN (
		//	               SELECT rel_exam2_lecture.cource_id
		//	                 FROM rel_exam2_lecture
		//	                WHERE rel_exam2_lecture.exam2_id = {$this->db->escape($param['exam2_id'])} 
		//	         )
		//	    )
		//	  ORDER BY rel_exam2_problem.exam2_id ASC, rel_exam2_problem.exam2_no ASC 
		//");
		$query = $this->db->query("
			 SELECT  rel_exam2_problem.exam2_id          AS exam2_id
			        ,rel_exam2_problem.exam2_no          AS exam2_no
			        ,rel_exam2_problem.exam2_problem_id  AS exam2_problem_id
			        ,exam2_problem.exam2_problem_name    AS exam2_problem_name
			        ,exam2_problem.answer_point         AS answer_point
			        ,exam2_problem.teacher_id           AS teacher_id
			        ,teacher.teacher_name              AS teacher_name
			   FROM  rel_exam2_problem 
			         LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id = exam2_problem.exam2_problem_id
			         LEFT JOIN teacher      ON exam2_problem.teacher_id          = teacher.teacher_id
			  WHERE  rel_exam2_problem.exam2_id = {$this->db->escape($param['exam2_id'])} 
			    AND  exam2_problem.status      = 0 
			    AND  rel_exam2_problem.exam2_problem_id IN (
			         SELECT rel_exam2_problem_lecture.exam2_problem_id
			           FROM rel_exam2_problem_lecture
			          WHERE rel_exam2_problem_lecture.cource_id IN (
			                SELECT rel_exam2_lecture.cource_id
			                  FROM rel_exam2_lecture
			                 WHERE rel_exam2_lecture.exam2_id = {$this->db->escape($param['exam2_id'])} 
			          )
			    )
			  ORDER BY rel_exam2_problem.exam2_id ASC, rel_exam2_problem.exam2_no ASC 
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}















	//----------------------------------------------
	// [Ajax用]学校所属の問題を取得
	//----------------------------------------------
	function get_cource_exam2($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'	=> 0,
							'cource_id'	=> 0,
							'free_word'	=> '',
						),
						$param
					);
		
		//SQL生成
		$sql  = "";
		$sql .= "SELECT exam2_id, exam2_name ";
		$sql .= "  FROM exam2 ";
		$sql .= " WHERE status = 0 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		
		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        exam2_name    LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR exam2_caption LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql .= " AND exam2.exam2_id IN (
							SELECT rel_exam2_lecture.exam2_id 
							  FROM rel_exam2_lecture 
							 WHERE rel_exam2_lecture.cource_id IN (".$cource_list.")
						) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql .= " AND exam2.exam2_id IN (
							SELECT rel_exam2_student_group.exam2_id 
							  FROM rel_exam2_student_group 
							 WHERE rel_exam2_student_group.student_group_id IN (".$student_group_list.")
						) ";
		}
		
		$sql .= " ORDER BY exam2_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//問題受講者グループテーブルより、受講者グループIDを取得
	//----------------------------------------------
	function get_exam2_student_group($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										student_group_id
									FROM
										rel_exam2_student_group
									WHERE
										exam2_id = {$this->db->escape($param['exam2_id'])}
									ORDER BY
										student_group_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//問題受講者グループテーブル更新
	//  →問題受講者をテーブル更新の後、問題受講者グループテーブルの更新
	//----------------------------------------------
	function update_exam2_student_group($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
							'data'     => array(),
						),
						$param
					);
		$data = $param['data'];

		//現在日時取得
		$wDate = date('Y/m/d H:i:s');

		// ↓問題受講者テーブルの更新処理↓ ------------------------------------------
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_exam2_student WHERE exam2_id = ? ";
		$this->db->query($sql, 
							array(
								$param['exam2_id']
							));
		
		//選択受講講座を登録
		foreach($data['exam2_students'] as $student_id) {
			$sql = "INSERT INTO rel_exam2_student (exam2_id, student_id, update_at) VALUES (?, ?, ?) ";
			$this->db->query($sql, 
								array(
									$param['exam2_id'],
									$student_id,
									$wDate
								));
		}
		$this->db->trans_complete();
		// ↑問題受講者テーブルの更新処理↑ ------------------------------------------



		// ↓問題受講者グループテーブルの更新処理↓ ----------------------------------
		//受講者IDをキーに受講者グループIDを取得
		$temp_student_group_id = array();
		foreach($data['exam2_students'] as $student_id) {
			//  受講者IDより受講者グループIDを取得
			$student_group_query = $this->db->query("SELECT student_group_id FROM rel_student_group WHERE student_id = ? ", array(
					$student_id
				));
			
			if($student_group_query->num_rows() > 0){
				$row_array = $student_group_query->row_array();
				if(in_array($row_array['student_group_id'], $temp_student_group_id, FALSE)){
				}else{
					$temp_student_group_id[] = $row_array['student_group_id'];
				}
			}
		}

		$this->db->trans_start();
		
		//問題受講者グループテーブル から 該当授業IDをキーに削除
		$sql = "DELETE FROM rel_exam2_student_group WHERE exam2_id = ? ";
		$this->db->query($sql, 
							array(
								$param['exam2_id']
							));
		
		//問題受講者グループテーブル に、問題IDと受講者グループIDを新規格納
		foreach($temp_student_group_id as $student_group_id){
			$sql = "INSERT INTO rel_exam2_student_group (exam2_id,student_group_id,update_at) VALUES(?, ?, ?) ";
			$this->db->query($sql, 
								array(
									$param['exam2_id'],
									$student_group_id,
									$wDate
								));
		}
		
		$this->db->trans_complete();
		// ↑課題受講者グループテーブルの更新処理↑ ----------------------------------
		
	}

	//----------------------------------------------
	//問題設問関係テーブル更新
	//----------------------------------------------
	function update_rel_exam2_problem ($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id' => 0,
							'data'    => array(),
						),
						$param
					);
		$data = $param['data'];

		//現在日時取得
		$wDate = date('Y/m/d H:i:s');

		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_exam2_problem WHERE exam2_id = ? ";
		$this->db->query($sql, 
							array(
								$param['exam2_id']
							));
		
		//問題設問関係テーブルを登録
		$exam2_no_count = 0;
		foreach($data['exam2_problems'] as $exam2_problem_id) {
			$exam2_no_count = $exam2_no_count + 1;
			$sql = "INSERT INTO rel_exam2_problem (exam2_id, exam2_no, exam2_problem_id, update_at) VALUES (?, ?, ?, ?) ";
			$this->db->query($sql, 
								array(
									$param['exam2_id'],
									$exam2_no_count,
									$exam2_problem_id,
									$wDate
								));
		}
		$this->db->trans_complete();


	}

	//----------------------------------------------
	//問題IDから、受講者IDを取得（複数）
	//----------------------------------------------
	function get_exam2_student($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id'    => 0,
							'cource_id'  => array(),
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
			SELECT rel_exam2_student.student_id 
			  FROM rel_exam2_student 
			       LEFT JOIN student ON rel_exam2_student.student_id = student.student_id 
			 WHERE rel_exam2_student.exam2_id = {$this->db->escape($param['exam2_id'])} 
			   AND student.status = 0
			 ORDER BY rel_exam2_student.student_id
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// 受講者IDから、問題ID・問題名を取得（複数）
	//   ※受講者画面-詳細画面で使用。
	//----------------------------------------------
	function get_student_from_exam2($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id'    => '0',
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
							 SELECT  rel_exam2_student.exam2_id 
							        ,exam2.exam2_name 
							   FROM  rel_exam2_student LEFT JOIN exam2 ON rel_exam2_student.exam2_id = exam2.exam2_id
							  WHERE  exam2.status = 0 
							    AND  rel_exam2_student.student_id IN ({$this->db->escape_str($param['student_id'])})
							  GROUP  BY rel_exam2_student.exam2_id 
							  ORDER  BY rel_exam2_student.exam2_id desc
						");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}


	//----------------------------------------------
	//問題IDから、受講者IDを取得（複数）
	//  ※問題詳細画面、受講者項目に表示する用の受講者ID
	//----------------------------------------------
	function get_exam2_student_confirm($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_id'    => 0,
							'cource_id'  => array(),
						),
						$param
					);

		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$sql = '';
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql .= " AND rel_exam2_student.student_id IN (
								SELECT student_lecture.student_id 
								  FROM student_lecture 
								 WHERE student_lecture.cource_id IN (".$cource_list.")
							) ";
		}
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql .= " AND rel_exam2_student.student_id IN (
								SELECT rel_student_group .student_id 
								  FROM rel_student_group  
								 WHERE rel_student_group .student_group_id IN (".$student_group_list.")
							) ";
		}

		//SQL投入
		$query = $this->db->query("
			SELECT rel_exam2_student.student_id 
			  FROM rel_exam2_student 
			       LEFT JOIN student ON rel_exam2_student.student_id = student.student_id 
			 WHERE rel_exam2_student.exam2_id = {$this->db->escape($param['exam2_id'])}".$sql."
			   AND student.status = 0 
			 ORDER BY rel_exam2_student.student_id 
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//問題の権限確認
	// Super User:フルアクセス
	// 学校管理者:学校内資料フルアクセス
	// 講師      :課題管理の権限のある講師
	//----------------------------------------------
	function issue_edit_delete_auth_check($param){
		try{ 
			//引数設定
			$param = array_merge(
				array(
					'login_teacher_id' => $this->session->userdata['cms_master.login.teacher_id'],
					'issue_id'         => 0,
					'eval_flag'        => 0,
				),
				$param
			);
			
			// Super User権限(-1)：フルアクセスのため、権限有効
			if($param['login_teacher_id'] <= 0){
				$return_data['auth_edit_delete'] = 1;
				$return_data['remarks']          = '';
				return $return_data;
			}
			
			//SQL文作成
			$sql = '';
			$sql .= "SELECT issue.teacher_id     AS teacher_id ";
			$sql .= "      ,teacher.teacher_auth AS teacher_auth ";
			$sql .= "  FROM issue INNER JOIN teacher ON issue.school_id = teacher.school_id ";
			$sql .= " WHERE issue.status        <> 9 ";
			$sql .= "   AND teacher.status      =  0 ";
			$sql .= "   AND issue.issue_id      =  ? ";
			$sql .= "   AND teacher.teacher_id  =  ? ";
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['issue_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				// レコードあり
				$row_array = $query->row_array();
				
				// 権限（新）より権限有無を確認
				$work_auth  = unserialize($row_array['teacher_auth']);

				// 学校管理者権限：学校内フルアクセスのため、権限有効
				if($work_auth['school_admin'] == 1){
					$return_data['auth_edit_delete'] = 1;
				}else{
			//	// 一般講師：課題権限あり＋作成した講師の場合に、権限有効
			//		if(($work_auth['issue'] == 1) && ($row_array['teacher_id'] == $param['login_teacher_id'])){

					if( $param['eval_flag'] > 0 ){
					// 一般講師：課題権限を持つ講師の場合に、権限有効
						if( $work_auth['issue'] == 1 ){
							$return_data['auth_edit_delete'] = 1;
						}else{
							$return_data['auth_edit_delete'] = 0;
						}
					}else{
					// 一般講師：課題権限を持つ講師の場合に、権限有効
						if(  ($work_auth['issue'] == 1) || ($work_auth['issue_no_eval'] == 1)  ){
							$return_data['auth_edit_delete'] = 1;
						}else{
							$return_data['auth_edit_delete'] = 0;
						}
					}
				}
				
				$return_data['remarks'] = '';
				return $return_data;
			}else{
				// レコードなし：権限無効
				$return_data['auth_edit_delete']   = 0;
				$return_data['remarks']       = 'no-data';
				return $return_data;
			}
		
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['auth_edit_delete'] = 0;
			$return_data['remarks']     = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
}
?>
