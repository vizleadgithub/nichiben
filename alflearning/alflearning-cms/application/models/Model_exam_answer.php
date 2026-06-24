<?php
#[AllowDynamicProperties]
class Model_exam_answer extends CI_Model  
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

	//--------------------------------------------------
	// 問題IDより設問の解答配点の最大値を取得
	//--------------------------------------------------
	function get_max_answer_point($param){
		//引数設定
		$param = array_merge(
			array(
				'school_id'  => 0,
				'exam_id'    => 0,
			),
			$param
		);
		
		//SQL生成
		$query = $this->db->query("
				SELECT SUM(exam_problem.answer_point) AS answer_point 
				  FROM exam_problem 
				 WHERE exam_problem.school_id = ? 
				   AND exam_problem.exam_problem_id IN ( 
				           SELECT rel_exam_problem.exam_problem_id 
				             FROM rel_exam_problem 
				            WHERE rel_exam_problem.exam_id = ? 
				       ) ",
			array(
				(int) $param['school_id'],
				(int) $param['exam_id'],
			)
		);
		
		//データリターン
		$max_answer_point = -1;
		if($query->num_rows() > 0){
			$temp = $query->row_array();
			$max_answer_point = $temp['answer_point'];
		}else{
			$max_answer_point = 0;
		}
		
		return $max_answer_point;
	}
	
	//--------------------------------------------------
	// 問題IDより解答テーブルより情報取得（解答日時、受講者名、解答配点（正解の合計点））
	//--------------------------------------------------
	function get_exam_answer_list($param){
		//引数設定
		$param = array_merge(
			array(
				'exam_id'           => 0,
				'student_id'        => '',
				'exam_answer_date'  => '',
			),
			$param
		);
		$sql_where = '';
		
		// 受講者IDの指定がある場合は、条件追加
		if($param['student_id'] != ''){
			$sql_where .= " AND exam_answer.student_id IN (".$this->db->escape_str($param['student_id']).") ";
		}
		//if( (int) $param['student_id'] > 0){
		//  $sql_where .= " AND exam_answer.student_id = ".$param['student_id']." ";
		//}
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND exam_answer.student_id IN (
								SELECT student_lecture.student_id 
								  FROM student_lecture 
								 WHERE student_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
							) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql_where .= " AND exam_answer.student_id IN (
								SELECT rel_student_group .student_id 
								  FROM rel_student_group  
								 WHERE rel_student_group .student_group_id IN (".$this->db->escape_str($student_group_list).")
							) ";
		}
		
		// 解答年月指定がある場合
		$where_exam_answer_date = '';
		if($param['exam_answer_date']!=''){
			$where_exam_answer_date = " AND exam_answer.exam_answer_date LIKE '".$this->db->escape_str($param['exam_answer_date'])."%'";
		}
		
		//SQL生成
		$query = $this->db->query("
				SELECT  SQL_CALC_FOUND_ROWS 
				        exam_answer.exam_id                AS exam_id 
				       ,MAX(exam_answer.exam_answer_date)  AS answer_date 
				       ,exam_answer.student_id             AS answer_student_id 
				       ,student.student_name               AS answer_student_name 
				       ,student.student_email              AS answer_student_email 
				       ,exam_answer.exam_answer_no         AS answer_no 
				       ,SUM(exam_answer.exam_answer_point) AS answer_point_total 
				  FROM  exam_answer LEFT JOIN student ON exam_answer.student_id = student.student_id 
				 WHERE  exam_answer.status = 0 
				   AND  exam_answer.exam_id    = ? ".$sql_where.$where_exam_answer_date."
				 GROUP  BY exam_answer.exam_id, exam_answer.student_id, exam_answer.exam_answer_no 
				 ORDER  BY exam_answer.exam_answer_date DESC ,exam_answer.student_id ASC 
				        ",
			array(
				(int) $param['exam_id'],
			)
		);
	
		//データリターン
		if ($query->num_rows() > 0) {
			$cnt = $this->db->query('SELECT FOUND_ROWS() as rowcount');
			$cnt = $cnt->row_array();
			return array(
				'cnt'    => $cnt['rowcount'],
				'items'  => $query->result_array(),
			);
		} else {
			return array(
				'cnt'    => 0,
				'items'  => array(),
			);
		}
	}

	//--------------------------------------------------
	// 問題ID・受講者ID・解答回数より解答テーブルより情報取得
	//（解答日時、設問、解答、正誤、解答配点）
	//--------------------------------------------------
	function get_exam_answer_detail($param){
		//引数設定
		$param = array_merge(
			array(
				'exam_id'         => 0,
				'student_id'      => 0,
				'exam_answer_no'  => 0,
			),
			$param
		);

		//SQL生成
		$query = $this->db->query("
				SELECT  SQL_CALC_FOUND_ROWS 
				       exam_answer.exam_answer_id        -- 解答ID
				      ,exam_answer.exam_id               -- 問題ID
				      ,exam_answer.exam_problem_id       -- 設問ID
				      ,exam_answer.student_id            -- 受講者ID
				      ,exam_answer.exam_answer_no        -- 解答回数
				      ,exam_answer.exam_answer_contents  -- 解答内容
				      ,exam_answer.exam_answer_date      -- 解答日時
				      ,exam_answer.exam_answer_mark      -- 解答結果 0:不正解 1:正解
				      ,exam_answer.exam_answer_point     -- 解答配点
				      ,exam_answer.marked_teacher_id     -- 採点講師ID
				      ,exam_answer.status                -- 状態 0:有効 9:削除
				      ,exam_answer.update_at             -- 更新日時
				      
				      ,exam_problem.exam_problem_name     -- 設問名
				      ,exam_problem.problem_kind          -- 設問種類
				      ,exam_problem.problem_contents      -- 設問内容
				      ,exam_problem.answer_kind           -- 解答種類
				      ,exam_problem.answer_contents       -- 解答内容
				      ,exam_problem.answer_point          -- 解答配点
				      
				      
				  FROM  exam_answer LEFT JOIN rel_exam_problem 
				                           ON exam_answer.exam_id         = rel_exam_problem.exam_id 
				                          AND exam_answer.exam_problem_id = rel_exam_problem.exam_problem_id 
				                          
				                    LEFT JOIN exam_problem ON exam_problem.exam_problem_id = exam_answer.exam_problem_id
				                          
				 WHERE  exam_answer.status         = 0 
				   AND  exam_answer.exam_id        = ? 
				   AND  exam_answer.student_id     = ? 
				   AND  exam_answer.exam_answer_no = ? 
				 ORDER  BY rel_exam_problem.exam_no ASC ",
			array(
				(int) $param['exam_id'],
				(int) $param['student_id'],
				(int) $param['exam_answer_no'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			$cnt = $this->db->query('SELECT FOUND_ROWS() as rowcount');
			$cnt = $cnt->row_array();
			return array(
				'cnt'    => $cnt['rowcount'],
				'items'  => $query->result_array(),
			);
		} else {
			return array(
				'cnt'    => 0,
				'items'  => array(),
			);
		}
	}

	//--------------------------------------------------
	// 解答テーブルへの更新
	//   採点講師ID（marked_teacher_id）には、ログイン講師IDが入る。管理講師IDではない。
	//--------------------------------------------------
	function update_exam_answer($param){
		//引数設定
		$param = array_merge(
						array(
							'exam_id'            => 0,
							'exam_answer_id'     => 0,
							'exam_answer_mark'   => 0,
							'exam_answer_point'  => 0,
							'marked_teacher_id'  => 0,
						),
						$param
					);
		
		$this->db->trans_start();
		$this->db->query("
				UPDATE  exam_answer 
				   SET  exam_answer_mark  = ? 
				       ,exam_answer_point = ? 
				       ,marked_teacher_id = ? 
				       ,update_at         = ? 
				 WHERE  exam_answer_id = ? 
				   AND  exam_id        = ? ",
			array(
				$param['exam_answer_mark'],
				$param['exam_answer_point'],
				$param['marked_teacher_id'],
				date('Y-m-d H:i:s'),
				$param['exam_answer_id'],
				$param['exam_id'],
			)
		);
		$this->db->trans_complete();

		return TRUE;
	}

}
?>
