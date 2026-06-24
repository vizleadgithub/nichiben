<?php
#[AllowDynamicProperties]
class Model_exam2_problem extends CI_Model  
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
	//試験設問一覧取得
	//----------------------------------------------
	function get_exam2_problem_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'    => 0,
				'bar_association_id' => 0,
				'offset'       => 0,
				'rowcount'     => 10,
				's_cource'             => 0,
				's_free_word'          => '',
				's_exam2_problem_group' => 0,
			),
			$param
		);

		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$sql_where = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND exam2_problem.exam2_problem_id IN (
							SELECT rel_exam2_problem_lecture.exam2_problem_id 
							  FROM rel_exam2_problem_lecture 
							 WHERE rel_exam2_problem_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
						) ";
		}
		
		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS '.
			'        exam2_problem.exam2_problem_id          AS exam2_problem_id '.
			'       ,exam2_problem.exam2_problem_name        AS exam2_problem_name '.
		//	'       ,exam2_problem.problem_kind             AS problem_kind '.              // 設問種類 1:テキスト、2:動画、3:図書室
		//	'       ,exam2_problem.problem_contents         AS problem_contents '.          // 設問内容 テキスト、または、ビデオID・図書室ID
		//	'       ,exam2_problem.problem_note             AS problem_note '.              // 設問備考
		//	'       ,exam2_problem.answer_kind              AS answer_kind '.               // 解答種類 1:単一形式、2:複数形式、3:フリー回答
		//	'       ,exam2_problem.answer_contents          AS answer_contents '.           // 解答内容 JSON型
			'       ,exam2_problem.answer_point             AS answer_point '.              // 解答配点 
		//	'       ,exam2_problem.answer_explain_kind      AS answer_explain_kind '.       // 解答解説種類 1:テキスト、2:動画、3:図書室、9:なし(初期値）
		//	'       ,exam2_problem.answer_explain_contents  AS answer_explain_contents '.   // 解答解説内容 テキスト、または、ビデオID・図書室ID
		//	'       ,exam2_problem.answer_explain_note`     AS answer_explain_note '.       // 解答解説備考
			'       ,exam2_problem.school_id                AS school_id '.
			'       ,exam2_problem.teacher_id               AS teacher_id '.
			'       ,exam2_problem.status                   AS status '.
			'       ,exam2_problem.update_at                AS update_at '.
			'       ,teacher.teacher_name                  AS teacher_name '.
			'   FROM exam2_problem LEFT JOIN teacher ON teacher.teacher_id = exam2_problem.teacher_id '.
			'  WHERE exam2_problem.school_id = ? '.
			'    AND exam2_problem.status <> 9 '.
			($param['s_free_word'] ?
				' AND ('.
				'    exam2_problem.exam2_problem_name         LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' OR exam2_problem.exam2_problem_id          LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND exam2_problem.exam2_problem_id IN (SELECT exam2_problem_id FROM rel_exam2_problem_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			($param['s_exam2_problem_group'] > 0 ?
				' AND exam2_problem.exam2_problem_id IN (SELECT rel_exam2_problem_group.exam2_problem_id FROM rel_exam2_problem_group WHERE rel_exam2_problem_group.exam2_problem_group_id = '.$this->db->escape($param['s_exam2_problem_group']).' )'
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
			' ORDER BY exam2_problem.exam2_problem_id DESC'.
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
	function get_exam2_problem($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'        => $this->libauth->get_school_id(),
							'exam2_problem_id'  => 0,
						),
						$param
					);
		
		//SQL生成
		$query = $this->db->query("
			SELECT  exam2_problem.exam2_problem_id          AS exam2_problem_id 
			       ,exam2_problem.exam2_problem_name        AS exam2_problem_name 
			       ,exam2_problem.problem_kind             AS problem_kind 
			       ,exam2_problem.problem_contents         AS problem_contents 
			       ,exam2_problem.problem_note             AS problem_note 
			       ,exam2_problem.answer_kind              AS answer_kind 
			       ,exam2_problem.answer_contents          AS answer_contents 
			       ,exam2_problem.answer_point             AS answer_point 
			       ,exam2_problem.answer_explain_kind      AS answer_explain_kind 
			       ,exam2_problem.answer_explain_contents  AS answer_explain_contents 
			       ,exam2_problem.answer_explain_note      AS answer_explain_note 
			       ,exam2_problem.school_id                AS school_id 
			       ,exam2_problem.teacher_id               AS teacher_id 
			       ,exam2_problem.status                   AS status 
			       ,exam2_problem.update_at                AS update_at 
			       ,teacher.teacher_name                  AS teacher_name 
			   FROM exam2_problem LEFT JOIN teacher ON teacher.teacher_id = exam2_problem.teacher_id 
			  WHERE exam2_problem.exam2_problem_id = {$this->db->escape($param['exam2_problem_id'])} 
			    AND exam2_problem.school_id       = {$this->db->escape($param['school_id'])} 
			    AND exam2_problem.status <> 9 
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
	function update_exam2_problem($param){
		
		$this->load->helper('json');
		
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];

		if ($data['update_flg'] == 0){
			//新規
			
			// 設問内容の変換
			$temp_problem_contents = '';
			if($data['problem_kind'] == 1) $temp_problem_contents = $data['problem_contents_text'];
			if($data['problem_kind'] == 2) $temp_problem_contents = $data['problem_contents_video'];
			if($data['problem_kind'] == 3) $temp_problem_contents = $data['problem_contents_book_library'];
			
			// 解答内容のJSON型変換
			$array_answer_contents = array();
			$array_answer_contents['answer_kind'] = $data['answer_kind'];
			if( ($data['answer_kind']==1) || ($data['answer_kind']==2) ){
				$c_index = -1;
				foreach($data['answer_contents_no'] as $ino => $value){
					$c_index = $c_index + 1;
					$array_answer_contents['answer_contents'][$c_index]['no']      = $c_index + 1;
					$array_answer_contents['answer_contents'][$c_index]['word']    = $data['answer_contents_word'][$ino];
					$array_answer_contents['answer_contents'][$c_index]['correct'] = $data['answer_contents_correct'][$ino];
				}
			}else{
				$array_answer_contents['answer_contents'][0]['no']      = 1;
				$array_answer_contents['answer_contents'][0]['word']    = $data['answer_contents_text'];
				$array_answer_contents['answer_contents'][0]['correct'] = 1;
			}
			$json_answer_contents = json_encode($array_answer_contents);
			
			// 解答解説内容の変換
			$temp_answer_explain_contents = '';
			if($data['answer_explain_kind'] == 1) $temp_answer_explain_contents = $data['answer_explain_contents_text'];
			if($data['answer_explain_kind'] == 2) $temp_answer_explain_contents = $data['answer_explain_contents_video'];
			if($data['answer_explain_kind'] == 3) $temp_answer_explain_contents = $data['answer_explain_contents_book_library'];
			if($data['answer_explain_kind'] == 9) $temp_answer_explain_contents = '';
			
			// 解答解説備考の変換（解答解説種類 = 9 の場合、文字列ゼロにする
			if($data['answer_explain_kind'] == 9) $data['answer_explain_note'] = '';
			
			$data = array_merge(array(
					'exam2_problem_name'       => 'no value',
					'problem_kind'            => 0,
					'problem_contents'        => $temp_problem_contents,
					'problem_note'            => '',
					'answer_kind'             => 0,
					'answer_contents'         => $json_answer_contents,
					'answer_point'            => 0,
					'answer_explain_kind'     => 0,
					'answer_explain_contents' => $temp_answer_explain_contents,
					'answer_explain_note'     => '',
					'school_id'               => 0,
					'teacher_id'              => 0,
					'status'                  => 0,
					'update_at'               => date("Y/m/d H:i:s"),
			), $data);

			$res = $this->db->query($this->db->insert_string('exam2_problem', array(
				//	'exam2_problem_id'          => '',                                /* 試験設問ID */
					'exam2_problem_name'        => $data['exam2_problem_name'],        /* 試験設問名 */
					'problem_kind'             => $data['problem_kind'],             /* 設問種類 */
					'problem_contents'         => $data['problem_contents'],         /* 設問内容 */
					'problem_note'             => $data['problem_note'],             /* 設問備考 */
					'answer_kind'              => $data['answer_kind'],              /* 解答種類 */
					'answer_contents'          => $data['answer_contents'],          /* 解答内容 */
					'answer_point'             => $data['answer_point'],             /* 解答配点 */
					'answer_explain_kind'      => $data['answer_explain_kind'],      /* 解答解説種類 */
					'answer_explain_contents'  => $data['answer_explain_contents'],  /* 解答解説内容 */
					'answer_explain_note'      => $data['answer_explain_note'],      /* 解答解説備考 */
					'school_id'                => $data['school_id'],                /* 学校ID */
					'teacher_id'               => $data['teacher_id'],               /* 講師ID */
					'status'                   => $data['status'],                   /* 状態 0:有効 9:削除 */
					'update_at'                => $data['update_at'],                /* 更新日時 */
				)
			));
			
			$lastInsertId = $this->db->insert_id();
			
			return array(
				'lastInsertId'	=> $lastInsertId,
			);
		}else{
			//修正

			
			// 設問内容の変換
			$temp_problem_contents = '';
			if($data['problem_kind'] == 1) $temp_problem_contents = $data['problem_contents_text'];
			if($data['problem_kind'] == 2) $temp_problem_contents = $data['problem_contents_video'];
			if($data['problem_kind'] == 3) $temp_problem_contents = $data['problem_contents_book_library'];
			
			// 解答内容のJSON型変換
			$array_answer_contents = array();
			$array_answer_contents['answer_kind'] = $data['answer_kind'];
			if( ($data['answer_kind']==1) || ($data['answer_kind']==2) ){
				$c_index = -1;
				foreach($data['answer_contents_no'] as $ino => $value){
					$c_index = $c_index + 1;
					$array_answer_contents['answer_contents'][$c_index]['no']      = $c_index + 1;
					$array_answer_contents['answer_contents'][$c_index]['word']    = $data['answer_contents_word'][$ino];
					$array_answer_contents['answer_contents'][$c_index]['correct'] = $data['answer_contents_correct'][$ino];
				}
			}else{
				$array_answer_contents['answer_contents'][0]['no']      = 1;
				$array_answer_contents['answer_contents'][0]['word']    = $data['answer_contents_text'];
				$array_answer_contents['answer_contents'][0]['correct'] = 1;
			}
			$json_answer_contents = json_encode($array_answer_contents);
			
			// 解答解説内容の変換
			$temp_answer_explain_contents = '';
			if($data['answer_explain_kind'] == 1) $temp_answer_explain_contents = $data['answer_explain_contents_text'];
			if($data['answer_explain_kind'] == 2) $temp_answer_explain_contents = $data['answer_explain_contents_video'];
			if($data['answer_explain_kind'] == 3) $temp_answer_explain_contents = $data['answer_explain_contents_book_library'];
			if($data['answer_explain_kind'] == 9) $temp_answer_explain_contents = $data['answer_explain_contents_text'];
			
			// 解答解説備考の変換（解答解説種類 = 9 の場合、文字列ゼロにする
			if($data['answer_explain_kind'] == 9) $data['answer_explain_note'] = '';
			
			$res = $this->db->query($this->db->update_string('exam2_problem', array(
					'exam2_problem_name'        => $data['exam2_problem_name'],     /* 試験設問名 */
					'problem_kind'             => $data['problem_kind'],          /* 設問種類 */
					'problem_contents'         => $temp_problem_contents,         /* 設問内容 */
					'problem_note'             => $data['problem_note'],          /* 設問備考 */
					'answer_kind'              => $data['answer_kind'],           /* 解答種類 */
					'answer_contents'          => $json_answer_contents,          /* 解答内容 */
					'answer_point'             => $data['answer_point'],          /* 解答配点 */
					'answer_explain_kind'      => $data['answer_explain_kind'],   /* 解答解説種類 */
					'answer_explain_contents'  => $temp_answer_explain_contents,  /* 解答解説内容 */
					'answer_explain_note'      => $data['answer_explain_note'],   /* 解答解説備考 */
					'school_id'                => $data['school_id'],             /* 学校ID */
					'teacher_id'               => $data['teacher_id'],            /* 講師ID */
				//	'status'                   => $data['status'],                /* 状態 0:有効 9:削除 */
					'update_at'                => date('Y/m/d H:i:s'),            /* 更新日時 */
				),'exam2_problem_id='.$data['exam2_problem_id']
			));
			// ),'exam2_problem_id='.$param['exam2_problem_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
			
			return array(
				'lastInsertId'	=> $data['exam2_problem_id'],
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
				'exam2_problem_id'  => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('exam2_problem', array(
				'status'     => 9,
				'update_at'  => date('Y/m/d H:i:s'),
			),'exam2_problem_id='.$param['exam2_problem_id']
		));
		// ),'exam2_id='.$param['exam2_id']." AND exam2_name='name' AND update_at = '".$_log->day."' "
		
		return $res;
	}

	//----------------------------------------------
	// 設問講座テーブルから講座ID取得
	//----------------------------------------------
	function get_exam2_problem_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_problem_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										rel_exam2_problem_lecture
									WHERE
										exam2_problem_id = {$this->db->escape($param['exam2_problem_id'])}
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
	// 設問講座テーブルの登録更新処理
	//----------------------------------------------
	function update_exam2_problem_lectures($param){
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
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_exam2_problem_lecture
				WHERE
					exam2_problem_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['exam2_problem_id'],
							));
		
		//選択受講講座を登録
		foreach($data['exam2_problem_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						rel_exam2_problem_lecture
					(
						exam2_problem_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['exam2_problem_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}



	//----------------------------------------------
	// 設問リスト用一覧取得
	//----------------------------------------------
	function get_exam2_problem_ul_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		// SQL生成
		$sql = "
			 SELECT  exam2_problem.exam2_problem_id
			        ,exam2_problem.exam2_problem_name
			        ,exam2_problem.problem_kind
			/*      ,exam2_problem.problem_contents  */
			        ,exam2_problem.answer_kind
			/*      ,exam2_problem.answer_contents   */
			        ,exam2_problem.answer_point
			/*      ,exam2_problem.school_id         */
			        ,exam2_problem.teacher_id
			/*      ,exam2_problem.status            */
			/*      ,exam2_problem.update_at         */
			        ,teacher.teacher_name AS teacher_name 
			   FROM  exam2_problem LEFT JOIN teacher ON exam2_problem.teacher_id = teacher.teacher_id
			  WHERE  exam2_problem.status = 0
			    AND  exam2_problem.school_id = {$this->db->escape($param['school_id'])}
		";
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql .= " AND exam2_problem.exam2_problem_id IN (
							SELECT rel_exam2_problem_lecture.exam2_problem_id 
							  FROM rel_exam2_problem_lecture 
							 WHERE rel_exam2_problem_lecture.cource_id IN (".$cource_list.")
						) ";
		}
		
		$sql .= "ORDER BY exam2_problem.exam2_problem_name ASC ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}




	//----------------------------------------------
	// [Ajax用]学校所属の試験を取得
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
							 WHERE rel_exam2_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
						) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql .= " AND exam2.exam2_id IN (
							SELECT rel_exam2_student_group.exam2_id 
							  FROM rel_exam2_student_group 
							 WHERE rel_exam2_student_group.student_group_id IN (".$this->db->escape_str($student_group_list).")
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
	// [Ajax用]学校所属の設問を取得
	//----------------------------------------------
	function get_cource_exam2_problem($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'cource_id'   => '',  // ハイフンつなぎの複数講座ID
							'free_word'   => '',
							'cource_flag' => 0,
							'bar_association_id' => 0,
						),
						$param
					);
		
		//SQL生成
		$sql  = "";
		$sql .= "SELECT exam2_problem.exam2_problem_id, exam2_problem.exam2_problem_name, exam2_problem.answer_point, teacher.teacher_name ";
		$sql .= "  FROM exam2_problem LEFT JOIN teacher ON exam2_problem.teacher_id = teacher.teacher_id ";
		$sql .= " WHERE exam2_problem.status = 0 ";
		$sql .= "   AND exam2_problem.school_id = {$this->db->escape($param['school_id'])} ";
		
		if (isset($param['cource_flag']) && $param['cource_flag'] != 0 && $param['cource_id'] != '')  {
			$temp1 = explode('-',$param['cource_id']);  // ハイフン繋ぎを配列変換
			$temp2 = implode(',', $temp1);              // 配列をカンマ区切に変換
			$sql .= "   AND exam2_problem.exam2_problem_id IN (";
			$sql .= "       SELECT rel_exam2_problem_lecture.exam2_problem_id FROM rel_exam2_problem_lecture WHERE rel_exam2_problem_lecture.cource_id IN (".$this->db->escape_str($temp2).") ";
			$sql .= "   ) ";
		}

		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        exam2_problem.exam2_problem_name LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR exam2_problem.problem_contents  LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql .= " AND exam2_problem.exam2_problem_id IN (
							SELECT rel_exam2_problem_lecture.exam2_problem_id 
							  FROM rel_exam2_problem_lecture 
							 WHERE rel_exam2_problem_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
						) ";
		}
		
		if($param['bar_association_id'] > 1){
			$sql .= ' AND teacher.bar_association_id = '.$this->db->escape($param['bar_association_id']);
		}
		
		$sql .= " ORDER BY exam2_problem.exam2_problem_id ASC";
		//var_dump($sql);
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}



	//----------------------------------------------
	//試験受講者グループテーブルより、グループIDを取得
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
	//試験受講者グループテーブル更新
	//  →試験受講者をテーブル更新の後、試験受講者グループテーブルの更新
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

		// ↓試験受講者テーブルの更新処理↓ ------------------------------------------
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_exam2_student WHERE exam2_id = ? ";
		$this->db->query($sql, 
							array(
								$param['exam2_id']
							));
		
		//選択受講講座を登録
		foreach($data['lecture_students'] as $student_id) {
			$sql = "INSERT INTO rel_exam2_student (exam2_id, student_id, update_at) VALUES (?, ?, ?) ";
			$this->db->query($sql, 
								array(
									$param['exam2_id'],
									$student_id,
									$wDate
								));
		}
		$this->db->trans_complete();
		// ↑試験受講者テーブルの更新処理↑ ------------------------------------------



		// ↓試験受講者グループテーブルの更新処理↓ ----------------------------------
		//受講者IDをキーに受講者グループIDを取得
		$temp_student_group_id = array();
		foreach($data['lecture_students'] as $student_id) {
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
		
		//試験受講者グループテーブル から 該当授業IDをキーに削除
		$sql = "DELETE FROM rel_exam2_student_group WHERE exam2_id = ? ";
		$this->db->query($sql, 
							array(
								$param['exam2_id']
							));
		
		//試験受講者グループテーブル に、試験IDと受講者グループIDを新規格納
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
	//試験IDから、受講者IDを取得（複数）
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
									SELECT
										student_id
									FROM
										rel_exam2_student
									WHERE
										exam2_id = {$this->db->escape($param['exam2_id'])}
									ORDER BY
										student_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//試験IDから、受講者IDを取得（複数）
	//  ※試験詳細画面、受講者項目に表示する用の受講者ID
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
								 WHERE student_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
							) ";
		}
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql .= " AND rel_exam2_student.student_id IN (
								SELECT rel_student_group .student_id 
								  FROM rel_student_group  
								 WHERE rel_student_group .student_group_id IN (".$this->db->escape_str($student_group_list).")
							) ";
		}

		//SQL投入
		$query = $this->db->query("
									SELECT
										rel_exam2_student.student_id
									FROM
										rel_exam2_student
									WHERE
										rel_exam2_student.exam2_id = {$this->db->escape($param['exam2_id'])}".$sql."
									ORDER BY
										rel_exam2_student.student_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}



	//----------------------------------------------
	// 設問IDより設問名を取得（一件）
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'exam2_problem_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
			 SELECT  exam2_problem.exam2_problem_id
			        ,exam2_problem.exam2_problem_name
			        ,exam2_problem.problem_kind
			        ,exam2_problem.problem_contents  
			        ,exam2_problem.answer_kind
			        ,exam2_problem.answer_contents   
			        ,exam2_problem.answer_point
			/*      ,exam2_problem.school_id         */
			        ,exam2_problem.teacher_id
			/*      ,exam2_problem.status            */
			/*      ,exam2_problem.update_at         */
			        ,teacher.teacher_name AS teacher_name 
			   FROM  exam2_problem LEFT JOIN teacher ON exam2_problem.teacher_id = teacher.teacher_id
			  WHERE  exam2_problem.status = 0
			    AND  exam2_problem.exam2_problem_id = {$this->db->escape($param['exam2_problem_id'])}
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data;
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//試験の権限確認
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





	//----------------------------------------------
	// 図書室ID・学校ID・所属講座IDよりレコード取得
	// ※設問インポートで使用
	//----------------------------------------------
	function check_exam2_problem_book_library($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id'       => 0,
							'exam2_problem_lectures' => array(),
							'school_id'             => 0,
						),
						$param
					);

		$cource_id_list = implode(",", $param['exam2_problem_lectures']);
		
		if( empty($cource_id_list) ){
			$cource_id_list = "0";
		}
		

		//SQL投入
		$query = $this->db->query("
			SELECT * 
			  FROM book_library 
			 WHERE book_library.stream_flag     = 1 
			   AND book_library.status          = 1 
			   AND book_library.school_id       = {$this->db->escape($param['school_id'])} 
			   AND book_library.book_library_id = {$this->db->escape($param['book_library_id'])} 
			   AND book_library.book_library_id IN (SELECT book_library_id FROM book_library_lecture WHERE cource_id IN ({$this->db->escape_str($cource_id_list)}))
		");

		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}
	//----------------------------------------------
	// ビデオID・学校ID・所属講座IDより、レコード取得
	// ※設問インポートで使用
	//----------------------------------------------
	function check_exam2_problem_video($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id'              => 0,
							'exam2_problem_lectures' => array(),
							'school_id'             => 0,
						),
						$param
					);

		$cource_id_list = implode(",", $param['exam2_problem_lectures']);
		
		if( empty($cource_id_list) ){
			$cource_id_list = "0";
		}
		

		//SQL投入
		$query = $this->db->query("
			SELECT * 
			  FROM video 
			 WHERE video.status    = 0 
			   AND video.school_id = {$this->db->escape($param['school_id'])} 
			   AND video.video_id  = {$this->db->escape($param['video_id'])} 
			   AND video.video_id IN (SELECT video_id FROM video_lecture WHERE cource_id IN ({$this->db->escape_str($cource_id_list)}))
			   AND video.video_id IN (SELECT video_id FROM video_alfstream_status WHERE alfstream_status = 'ONLINE')
		");

		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	/**
	 * 設問エクスポートで使用
	 */
	function get_exam2_problem_export_data($exam2_problem_lectures, $exam2_problem_groups){
		$sql = ' SELECT exam2_problem.* ';
		$sql.= '   FROM exam2_problem LEFT JOIN teacher ON teacher.teacher_id = exam2_problem.teacher_id ';
		$sql.= '  WHERE exam2_problem.school_id = 1 ';
		$sql.= '    AND exam2_problem.status <> 9 ';
		if($exam2_problem_lectures){
			$in_epl = '';
			foreach($exam2_problem_lectures as $val){
				if(is_numeric($val)){
					if($in_epl ==''){
						$in_epl.= $val;
					} else {
						$in_epl.= ','.$val;
					}
				}
			}
			if($in_epl!=''){
				$sql.= ' AND exam2_problem.exam2_problem_id IN (SELECT exam2_problem_id FROM rel_exam2_problem_lecture WHERE cource_id IN( '.$this->db->escape_str($in_epl).' ))';
			}
		}
		if($exam2_problem_groups){
			$in_epg = '';
			foreach($exam2_problem_groups as $val){
				if(is_numeric($val)){
					if($in_epg ==''){
						$in_epg.= $val;
					} else {
						$in_epg.= ','.$val;
					}
				}
			}
			if($in_epg!=''){
				$sql.= ' AND exam2_problem.exam2_problem_id IN (SELECT rel_exam2_problem_group.exam2_problem_id FROM rel_exam2_problem_group WHERE rel_exam2_problem_group.exam2_problem_group_id IN( '.$this->db->escape_str($in_epg).' ))';
			}
		}
		$sql.= ' ORDER BY exam2_problem.exam2_problem_id ASC';
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}



}
?>
