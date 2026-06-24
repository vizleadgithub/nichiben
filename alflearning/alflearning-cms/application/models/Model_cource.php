<?php
#[AllowDynamicProperties]
class Model_cource extends CI_Model  
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
	//講座検索結果一覧取得
	//----------------------------------------------
	function get_cource_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'    => 0,
							's_cource_name'  => '',
							's_cource_open'  => '',
							's_cource_close' => '',
							's_cource_id'    => 0,
							's_free_word'    => '',
							'offset'         => 0,
							'rowcount'       => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_cource_selectsql($param);
		$sql_limit  = " LIMIT
						 {$this->db->escape_str($param['offset'])}, {$this->db->escape_str($param['rowcount'])}";
		
		$query = $this->db->query($sql . $sql_limit);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	//----------------------------------------------
	//講座検索結果一覧件数取得
	//----------------------------------------------
	function get_cource_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'    => 0,
							's_cource_name'  => '',
							's_cource_open'  => '',
							's_cource_close' => '',
							's_cource_id'    => 0,
							's_free_word'    => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_cource_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//講座検索結果一覧SQL生成
	// [2012/10/16]classテーブルJOINの削除
	//             公開開始日・公開終了日のSQL文変更
	//----------------------------------------------
	function _get_cource_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'    => 0,
							's_cource_name'  => '',
							's_cource_open'  => '',
							's_cource_close' => '',
							's_cource_id'    => 0,
							's_free_word'    => '',
						),
						$param
					);
		//SQL作成
		$sql_select = "SELECT 
							cource.cource_id, 
							cource.cource_name, 
							DATE_FORMAT(cource.cource_open , '%Y/%m/%d %H:%i:%s') AS cource_open,
							DATE_FORMAT(cource.cource_close, '%Y/%m/%d %H:%i:%s') AS cource_close
						FROM cource ";
						//	LEFT JOIN (
						//		SELECT school_id, cource_id, count(class_id) as classnum
						//			FROM class 
						//				WHERE status = 0 
						//				GROUP BY school_id, cource_id) as class
						//	ON cource.school_id = class.school_id AND cource.cource_id = class.cource_id";
		$sql_where = " WHERE cource.status = 0";
		$sql_order = " ORDER BY cource.cource_id";
		
		//学校ID
		if (isset($param['s_school_id']) && $param['s_school_id'] != 0) {
			$sql_where .= " AND cource.school_id = {$this->db->escape($param['s_school_id'])}";
		}
		//講座名
		if (isset($param['s_cource_name']) && $param['s_cource_name'] != '') {
			$sql_where .= " AND cource.cource_name LIKE '%{$this->db->escape_like_str($param['s_cource_name'])}%'";
		}
		//公開開始日
		if (isset($param['s_cource_open']) && $param['s_cource_open'] != '') {
		//	$sql_where .= " AND   {$this->db->escape($param['s_cource_open'])} BETWEEN cource.cource_open AND cource.cource_close";
			$sql_where .= " AND (({$this->db->escape($param['s_cource_open'])} BETWEEN cource.cource_open AND cource.cource_close)
							 OR  (cource.cource_open >= {$this->db->escape($param['s_cource_open'])}))";
		}
		//公開終了日
		if (isset($param['s_cource_close']) && $param['s_cource_close'] != '') {
		//	$sql_where .= " AND   {$this->db->escape($param['s_cource_close'])} BETWEEN cource.cource_open AND cource.cource_close";
			$sql_where .= " AND (({$this->db->escape($param['s_cource_close'])} BETWEEN cource.cource_open AND cource.cource_close)
							 OR  (cource.cource_close <= {$this->db->escape($param['s_cource_close'])}))";
		}
		//ID
		if (isset($param['s_cource_id']) && $param['s_cource_id'] != '' ) {
			if(is_numeric($param['s_cource_id'])){
				$sql_where .= " AND cource.cource_id = {$this->db->escape($param['s_cource_id'])}";
			} else {
				$sql_where .= " AND cource.cource_id = 0";
			}
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (cource.cource_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR cource.cource_caption LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR cource.cource_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		return $sql_select . $sql_where;
	}
	
	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_cource($param){
		//引数設定
		$param = array_merge(
						array(
							'cource_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id,
										cource_name,
										DATE_FORMAT(cource_open,  '%Y/%m/%d %H:%i:%s') as cource_open,
										DATE_FORMAT(cource_close, '%Y/%m/%d %H:%i:%s') as cource_close,
										cource_caption,
										cource_note,
										school_id,
										status,
										update_at
									FROM
										cource
									WHERE
										cource_id = {$this->db->escape($param['cource_id'])}
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
	// [2012/11/01]eLM API 機能の追加
	// [2012/11/20]講座登録・更新時に所属受講者・資料・図書室・ビデオを更新
	//----------------------------------------------
	function update_cource($param){
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

		// 外部連携、契約あり・なし確認（DBから取得）
		$outside_elearningmanager = false;
		$school_contract_param = $this->libauth->get_login_school_contract_param($data['school_id']);
		if(isset($school_contract_param['outside_elearningmanager'])){
			if($school_contract_param['outside_elearningmanager']['contract']==='fixation'){
				$outside_elearningmanager = true;
			}
		}
		
		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO
						cource 
					(
						cource_name,
						cource_open,
						cource_close,
						cource_caption,
						cource_note,
						school_id,
						status,
						update_at
					)
					VALUES(?,?,?,?,?,?,?,?)";
			
			$this->db->trans_begin();
			$this->db->query($sql, 
								array(
									$data['cource_name'],
									$data['cource_open'],
									$data['cource_close'],
									$data['cource_caption'],
									$data['cource_note'],
									$data['school_id'],
									0,
									$wDate
								));
			
			//登録idを取得
			$return_data['prev_id'] = $this->db->insert_id();
			
			$data['cource_id'] = $return_data['prev_id'];
			
			if($outside_elearningmanager == false){
				$this->db->trans_commit();
				
				$temp_result = $this->_update_student_lecture($data);			// 受講者講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_material_lecture($data);			// 資料講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_book_library_lecture($data);		// 図書室講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_book_library_search_index($data);	// 図書室検索インデックス更新更新（DELETE-INSERT）
				$temp_result = $this->_update_video_lecture($data);				// ビデオ講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_video_search_index($data);		// ビデオ検索インデックス更新（DELETE-INSERT）
			}else{
				// eLM API 実行
				$this->load->model('model_outside_gingerapp');
				$request['school_id']      = $data['school_id'];
				$request['cource_id']      = $return_data['prev_id'];
				$request['cource_name']    = $data['cource_name'];
				$request['cource_caption'] = $data['cource_caption'];
				$content = $this->model_outside_gingerapp->course_insert($request);

				// eLM API 結果取得
				$return_data['result']  = $content['result'];
				$return_data['stat']    = $content['stat'];
				$return_data['message'] = $content['message'];

				if($return_data['stat']==200){
					$this->db->trans_commit();
					
					$temp_result = $this->_update_student_lecture($data);		// 受講者講座テーブル更新（DELETE-INSERT）
					$content = $this->_connect_elm_api($data);					// [eLM]コースのユーザ割当削除・コースのユーザ割当実行（失敗しても継続）
					$return_data['result']  = $content['result'];
					$return_data['stat']    = $content['stat'];
					$return_data['message'] = $content['message'];
					$temp_result = $this->_update_material_lecture($data);			// 資料講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_book_library_lecture($data);		// 図書室講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_book_library_search_index($data);	// 図書室検索インデックス更新更新（DELETE-INSERT）
					$temp_result = $this->_update_video_lecture($data);				// ビデオ講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_video_search_index($data);		// ビデオ検索インデックス更新（DELETE-INSERT）
				}else{
					$this->db->trans_rollback();
				}
			}
			
			return $return_data;
		}else{
			//修正
			$sql = "UPDATE
						cource
					SET 
						cource_name    = ?,
						cource_open    = ?,
						cource_close   = ?,
						cource_caption = ?,
						cource_note    = ?,
						school_id      = ?,
						status         = ?,
						update_at      = ?
					WHERE
						cource_id = ?";
			
			$this->db->trans_begin();
			$this->db->query($sql, 
								array(
									$data['cource_name'],
									$data['cource_open'],
									$data['cource_close'],
									$data['cource_caption'],
									$data['cource_note'],
									$data['school_id'],
									0,
									$wDate,
									$data['cource_id']
								));
			
			//登録idを取得
			$return_data['prev_id'] = $data['cource_id'];
			
			if($outside_elearningmanager == false){
				$this->db->trans_commit();

				$temp_result = $this->_update_student_lecture($data);			// 受講者講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_material_lecture($data);			// 資料講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_book_library_lecture($data);		// 図書室講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_book_library_search_index($data);	// 図書室検索インデックス更新更新（DELETE-INSERT）
				$temp_result = $this->_update_video_lecture($data);				// ビデオ講座テーブル更新（DELETE-INSERT）
				$temp_result = $this->_update_video_search_index($data);		// ビデオ検索インデックス更新（DELETE-INSERT）
			}else{
				// eLM API 実行
				$this->load->model('model_outside_gingerapp');
				$request['school_id']      = $data['school_id'];
				$request['cource_id']      = $return_data['prev_id'];
				$request['cource_name']    = $data['cource_name'];
				$request['cource_caption'] = $data['cource_caption'];
				$content = $this->model_outside_gingerapp->course_update($request);

				// eLM API 結果取得
				$return_data['result']  = $content['result'];
				$return_data['stat']    = $content['stat'];
				$return_data['message'] = $content['message'];

				// 未登録コースの場合、コース登録を実行
				if($return_data['stat']==424){
					$request['school_id']      = $data['school_id'];
					$request['cource_id']      = $return_data['prev_id'];
					$request['cource_name']    = $data['cource_name'];
					$request['cource_caption'] = $data['cource_caption'];
					$content = $this->model_outside_gingerapp->course_insert($request);

					// eLM API 結果取得
					$return_data['result']  = $content['result'];
					$return_data['stat']    = $content['stat'];
					$return_data['message'] = $content['message'];
				}

				if($return_data['stat']==200){
					$this->db->trans_commit();

					$temp_result = $this->_update_student_lecture($data);		// 受講者講座テーブル更新（DELETE-INSERT）
					$content = $this->_connect_elm_api($data);					// [eLM]コースのユーザ割当削除・コースのユーザ割当実行（失敗しても継続）
					$return_data['result']  = $content['result'];
					$return_data['stat']    = $content['stat'];
					$return_data['message'] = $content['message'];
					$temp_result = $this->_update_material_lecture($data);			// 資料講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_book_library_lecture($data);		// 図書室講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_book_library_search_index($data);	// 図書室検索インデックス更新更新（DELETE-INSERT）
					$temp_result = $this->_update_video_lecture($data);				// ビデオ講座テーブル更新（DELETE-INSERT）
					$temp_result = $this->_update_video_search_index($data);		// ビデオ検索インデックス更新（DELETE-INSERT）
				}else{
					$this->db->trans_rollback();
				}
			}
			
			return $return_data;
		}
	}
	
	//----------------------------------------------
	//削除処理（2012/11/01現在、機能実装なし）
	//----------------------------------------------
	function delete_cource($param){
		//引数設定
		$param = array_merge(
						array(
							'cource_id' => 0,
						),
						$param
					);
		//SQL作成
		$sql = "UPDATE
					cource
				SET
					status = 9
				WHERE 
					cource_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['cource_id']));
		$this->db->trans_complete();
		
	}
	//----------------------------------------------
	//講座ドロップダウン用一覧取得
	// [2012/08/20]ORDER BY変更（cource_id -> cource_name）
	// [2012/09/30]講座開始終了日時の条件追加
	//----------------------------------------------
	function get_cource_dropdown_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		$wDate = date('Y/m/d H:i:s');
		
		//SQL生成
		$sql = "SELECT 
						cource_id,
						cource_name
					FROM 
						cource
					WHERE 
						status = 0
						AND school_id = {$this->db->escape($param['school_id'])}
						AND ? BETWEEN cource_open AND cource_close
					ORDER BY
						cource_name
				";
		
		$query = $this->db->query($sql, array($wDate));
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	//----------------------------------------------
	//受講講座チェックボックス用一覧取得
	// [2012/08/20]ORDER BY変更（cource_id -> cource_name）
	//----------------------------------------------
	function get_cource_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		$wDate = date('Y/m/d H:i:s');
		
		//SQL生成
		$sql = "SELECT 
						cource_id,
						cource_name
					FROM 
						cource
					WHERE 
						status = 0
						AND school_id = {$this->db->escape($param['school_id'])}
						AND ? BETWEEN cource_open AND cource_close
					ORDER BY
						cource_name
				";
		
		$query = $this->db->query($sql, array($wDate));
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	//----------------------------------------------
	//講座名取得
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'cource_id'   => 0,
						),
						$param
					);
		
		$wDate = date('Y/m/d H:i:s');
		
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_name
									FROM
										cource
									WHERE
										cource_id = {$this->db->escape($param['cource_id'])}
									AND
										status = 0
									AND ? BETWEEN cource_open AND cource_close
								"
								, array($wDate));
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data['cource_name'];
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// [2012/11/30]学校IDから有効受講者数・資料数・図書室数・ビデオ数の取得
	//----------------------------------------------
	function get_effective_items($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		$result_data = array();
		
		// ログイン中講師の講座確認 ---------- ---------- ---------- ---------- ----------
		//   講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		
		//   講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		
		// 学校IDをキーに有効受講者総数を取得
		$query = $this->db->query(
			'SELECT * FROM student  WHERE student.school_id = ? AND student.status = 0 ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['student_all_count'] = $query->num_rows();
		
		// 学校IDをキーに有効資料総数を取得
		$query = $this->db->query(
			'SELECT * FROM material WHERE material.school_id = ? AND  material.status <> 9 ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['material_all_count'] = $query->num_rows();
		
		// 学校IDをキーに有効図書室総数を取得
//			'SELECT * FROM book_library WHERE book_library.school_id = ? AND book_library.status <> 9 ',
		$query = $this->db->query(
			'SELECT * FROM book_library WHERE book_library.school_id = ? AND book_library.status = 1 AND book_library.stream_flag = 1 ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['book_library_all_count'] = $query->num_rows();
		
		// 学校IDをキーに有効ビデオ総数を取得
		$query = $this->db->query(
			'SELECT * FROM video WHERE video.school_id = ? AND video.status = 0 ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['video_all_count'] = $query->num_rows();
		
		// 学校IDをキーに有効設問総数を取得 ---------- ---------- ---------- ---------- ----------
		// 講座管理には表示しない
		//$query = $this->db->query(
		//  'SELECT * FROM exam_problem WHERE exam_problem.school_id = ? AND exam_problem.status = 0 ',
		//  array(  (int) $param['school_id'],  )
		//);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$sql_where = '';
		if($cource_list!=''){
			$sql_where .= " AND exam_problem.exam_problem_id IN (
								SELECT rel_exam_problem_lecture.exam_problem_id 
								  FROM rel_exam_problem_lecture 
								 WHERE rel_exam_problem_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
							) ";
		}
		$query = $this->db->query(
			'SELECT * FROM exam_problem WHERE exam_problem.school_id = ? AND exam_problem.status = 0 '.$sql_where.' ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['exam_problem_all_count'] = $query->num_rows();
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$sql_where = '';
		if($cource_list!=''){
			$sql_where .= " AND exam2_problem.exam2_problem_id IN (
								SELECT rel_exam2_problem_lecture.exam2_problem_id 
								  FROM rel_exam2_problem_lecture 
								 WHERE rel_exam2_problem_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
							) ";
		}
		$query = $this->db->query(
			'SELECT * FROM exam2_problem WHERE exam2_problem.school_id = ? AND exam2_problem.status = 0 '.$sql_where.' ',
			array(
				(int) $param['school_id'],
			)
		);
		$result_data['exam2_problem_all_count'] = $query->num_rows();
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		return $result_data;
	}


	//----------------------------------------------
	// [2012/11/20]講座IDから所属受講者ID・受講者名の取得
	//----------------------------------------------
	function get_student_lecture($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'cource_id'   => 0,
						),
						$param
					);

		$query = $this->db->query(
			'SELECT student_lecture.student_id, student.student_name, student.student_email '.
			'  FROM student_lecture LEFT JOIN student ON student_lecture.student_id = student.student_id '.
			' WHERE student.school_id = ? '.
			'  AND student.status = 0  '.
			'  AND student_lecture.cource_id =? ',
			array(
				(int) $param['school_id'],
				(int) $param['cource_id'],
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
	// [2012/11/20]講座IDから所属資料ID・資料論理名の取得
	//----------------------------------------------
	function get_material_lecture($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'cource_id'   => 0,
						),
						$param
					);

		$query = $this->db->query(
			'SELECT material_lecture.material_id, material.material_logic_name '.
			'  FROM material_lecture LEFT JOIN material ON material_lecture.material_id = material.material_id '.
			' WHERE material.school_id = ? '.
			'  AND  material.status <> 9 '.
			'  AND  material_lecture.cource_id = ? ',
			array(
				(int) $param['school_id'],
				(int) $param['cource_id'],
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
	// [2012/11/20]講座IDから所属図書室ID・図書室論理名の取得
	//----------------------------------------------
	function get_book_library_lecture($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'cource_id'   => 0,
						),
						$param
					);

		$query = $this->db->query(
			'SELECT book_library_lecture.book_library_id, book_library.book_library_logic_name '.
			'  FROM book_library_lecture LEFT JOIN book_library ON book_library_lecture.book_library_id = book_library.book_library_id '.
			' WHERE book_library.school_id = ? '.
//			'   AND book_library.status <> 9 '.
			'   AND status      = 1 '.
			'   AND stream_flag = 1 '.
			'   AND book_library_lecture.cource_id = ? ',
			array(
				(int) $param['school_id'],
				(int) $param['cource_id'],
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
	// [2012/11/20]講座IDから所属ビデオID・ビデオ論理名の取得
	//----------------------------------------------
	function get_video_lecture($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'cource_id'   => 0,
						),
						$param
					);

		$query = $this->db->query(
			'SELECT video_lecture.video_id, video.video_logic_name '.
			'  FROM video_lecture LEFT JOIN video ON video_lecture.video_id = video.video_id '.
			' WHERE video.school_id = ? '.
			'   AND video.status = 0 '.
			'   AND video_lecture.cource_id = ? ',
			array(
				(int) $param['school_id'],
				(int) $param['cource_id'],
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
	// [2012/11/20] 受講者講座テーブル更新（commit時）
	//----------------------------------------------
	function _update_student_lecture($data){
		
		// DELETE student_lecture
		$this->db->query('DELETE FROM student_lecture WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT student_lecture
		if(empty($data['lecture_students'])){
			return true;
		}else{
			$wDate = date('Y/m/d H:i:s');
			foreach($data['lecture_students'] as $student_id) {
				$this->db->query('INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?) ', 
								array(
									$student_id,
									$data['cource_id'],
									$wDate
								));
			}
			return true;
		}
	}

	//----------------------------------------------
	// [2012/11/20] 資料講座テーブル更新（commit時）
	//----------------------------------------------
	function _update_material_lecture($data){
		
		// DELETE material_lecture
		$this->db->query('DELETE FROM material_lecture WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT material_lecture
		if(empty($data['lecture_materials'])){
			return true;
		}else{
			$wDate = date('Y/m/d H:i:s');
			foreach($data['lecture_materials'] as $material_id) {
				$this->db->query('INSERT INTO material_lecture (material_id, cource_id, update_at) VALUES (?,?,?) ', 
								array(
									$material_id,
									$data['cource_id'],
									$wDate
								));
			}
			return true;
		}
	}

	//----------------------------------------------
	// [2012/11/20] 図書室講座テーブル更新（commit時）
	//----------------------------------------------
	function _update_book_library_lecture($data){
		
		// DELETE book_library_lecture
		$this->db->query('DELETE FROM book_library_lecture WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT book_library_lecture
		if(empty($data['lecture_book_librarys'])){
			return true;
		}else{
			$wDate = date('Y/m/d H:i:s');
			foreach($data['lecture_book_librarys'] as $book_library_id) {
				$this->db->query('INSERT INTO book_library_lecture (book_library_id, cource_id, update_at) VALUES (?,?,?) ', 
								array(
									$book_library_id,
									$data['cource_id'],
									$wDate
								));
			}
			return true;
		}
	}

	//----------------------------------------------
	// [2012/12/25] 図書室検索インデックス更新（commit時）
	//----------------------------------------------
	function _update_book_library_search_index($data){
		
		// DELETE book_library_search_index
		$this->db->query('DELETE FROM book_library_search_index WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT book_library_search_index
		if(empty($data['lecture_book_librarys'])){
			return true;
		}else{
			foreach($data['lecture_book_librarys'] as $book_library_id) {
				// 図書室IDより図書室のタグ取得、文字列ゼロなら次行へ
				$query = $this->db->query(
					'SELECT book_library.book_library_tags '.
					'  FROM book_library '.
					' WHERE 1 = 1 '.
					'   AND book_library.book_library_id = ? ',
					array(
						$book_library_id,
					)
				);
				$db_data = $query->row_array();
				$book_library_tags = $db_data['book_library_tags'];
				if( empty($book_library_tags) ){
					// 図書室検索インデックスへの新規登録
				//	foreach ($tag_array as $tmp) {
						$this->db->query('INSERT INTO book_library_search_index (book_library_id, cource_id, book_library_tag) VALUES (?,?,NULL) ', 
										array(
											$book_library_id,
											$data['cource_id']
										));
				//	} 
				}else{
					// タグの分割
					$tag_array = explode(",", $book_library_tags);
					
					// 図書室検索インデックスへの新規登録
					foreach ($tag_array as $tmp) {
						$this->db->query('INSERT INTO book_library_search_index (book_library_id, cource_id, book_library_tag) VALUES (?,?,?) ', 
										array(
											$book_library_id,
											$data['cource_id'],
											$tmp
										));
					} 
				}
			}
			return true;
		}
	}

	//----------------------------------------------
	// [2012/11/20] ビデオ講座テーブル更新（commit時）
	//----------------------------------------------
	function _update_video_lecture($data){
		
		// DELETE video_lecture
		$this->db->query('DELETE FROM video_lecture WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT video_lecture
		if(empty($data['lecture_videos'])){
			return true;
		}else{
			$wDate = date('Y/m/d H:i:s');
			foreach($data['lecture_videos'] as $video_id) {
				$this->db->query('INSERT INTO video_lecture (video_id, cource_id, update_at) VALUES (?,?,?) ', 
								array(
									$video_id,
									$data['cource_id'],
									$wDate
								));
			}
			return true;
		}
	}

	//----------------------------------------------
	// [2012/12/25] ビデオ検索インデックス更新（commit時）
	//----------------------------------------------
	function _update_video_search_index($data){
		
		// DELETE video_search_index
		$this->db->query('DELETE FROM video_search_index WHERE cource_id = ? ', 
						array(
							$data['cource_id'],
						));

		// INSERT video_search_index
		if(empty($data['lecture_videos'])){
			return true;
		}else{
			foreach($data['lecture_videos'] as $video_id) {
				// ビデオIDよりビデオのタグ取得、文字列ゼロなら次行へ
				$query = $this->db->query(
					'SELECT video.video_tags '.
					'  FROM video '.
					' WHERE 1 = 1 '.
					'   AND video.video_id = ? ',
					array(
						$video_id,
					)
				);
				$db_data = $query->row_array();
				$video_tags = $db_data['video_tags'];
				if( empty($video_tags) ){
					// ビデオ検索インデックスへの新規登録
				//	foreach ($tag_array as $tmp) {
						$this->db->query('INSERT INTO video_search_index (video_id, cource_id, video_tag) VALUES (?,?,NULL) ', 
										array(
											$video_id,
											$data['cource_id']
										));
				//	} 
				}else{
					// タグの分割
					$tag_array = explode(",", $video_tags);
					
					// ビデオ検索インデックスへの新規登録
					foreach ($tag_array as $tmp) {
						$this->db->query('INSERT INTO video_search_index (video_id, cource_id, video_tag) VALUES (?,?,?) ', 
										array(
											$video_id,
											$data['cource_id'],
											$tmp
										));
					} 
				}
			}
			return true;
		}
	}


	//----------------------------------------------
	// [2012/11/20] eLM APIの実行（commit時）
	// コースのユーザ割当削除およびコースのユーザ割当
	//----------------------------------------------
	function _connect_elm_api($data){
		$this->load->model('model_outside_gingerapp');

		// eLM API 実行（コースのユーザ割当削除）
		$request['school_id'] = $data['school_id'];
		$request['cource_id'] = $data['cource_id'];
		$content = $this->model_outside_gingerapp->assign_delete($request);
			
		// eLM API 結果取得（コースのユーザ割当削除）
		$return_data['result']  = $content['result'];
		$return_data['stat']    = $content['stat'];
		$return_data['message'] = $content['message']."[assign_delete]";
		
		if($return_data['stat'] != 200){
			$return_data['stat']  = 200;
			return $return_data;
		}
		
		// 受講者の選択無しの場合、終了
		// 受講者の選択無しの場合、受講者ID
		$student_id_list    = '';
		$student_email_list = '';
		if(empty($data['lecture_students'])){
			return $return_data;
		}else{
			$this->load->model('model_student');

			foreach($data['lecture_students'] as $idx => $lecture){
				// 受講者IDより受講者情報を取得
				$db_data = $this->model_student->get_student(array('student_id' => $lecture,));
				
				if($student_id_list == ''){
					$student_id_list  = $db_data['student_id'];
				}else{
					$student_id_list .= ",".$db_data['student_id'];
				}

				if($student_email_list == ''){
					$student_email_list  = $db_data['student_email'];
				}else{
					$student_email_list .= ",".$db_data['student_email'];
				}
			}
		}

		// eLM API 実行（コースのユーザ割当）
		$request['school_id']     = $data['school_id'];
		$request['cource_id']     = $data['cource_id'];
		$request['student_id']    = $student_id_list;
		$request['student_email'] = $student_email_list;
		$content = $this->model_outside_gingerapp->assign_insert($request);

		// eLM API 結果取得（コースのユーザ割当）
		$return_data['result']  = $content['result'];
		$return_data['stat']    = $content['stat'];
		$return_data['message'] = $content['message']."[assign_insert]";

		if($return_data['stat'] != 200){
			$return_data['stat']  = 200;
		}
		
		return $return_data;
	}
}
?>
