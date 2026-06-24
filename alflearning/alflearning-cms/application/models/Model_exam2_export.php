<?php
#[AllowDynamicProperties]
class Model_exam2_export extends CI_Model  
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
							 WHERE rel_exam2_problem_lecture.cource_id IN (".$this->db->escape_str($cource_list).")
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
			$sql .= "       SELECT rel_exam2_problem_lecture.exam2_problem_id FROM rel_exam2_problem_lecture WHERE rel_exam2_problem_lecture.cource_id IN (".$temp2.") ";
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
	function get_exam2_problem_export_data( $exam2_problem_lectures, $product_code="", $product_name="" ){
		//++++++++++++++++++++++++++++++++++++++++++++
		$arr_csv = array();
		//++++++++++++++++++++++++++++++++++++++++++++
		//exam2_idの一覧
		$exam_ids = array();
		$in_epl = '';
		$sql = ' SELECT exam2_id ';
		$sql.= '   FROM ';
		$sql.= '   rel_exam2_lecture ';
		$sql.= '   WHERE 1=1';
		if( is_array($exam2_problem_lectures) && count($exam2_problem_lectures)>0 ){
			for($i=0;$i<count($exam2_problem_lectures);$i++){
				$row = $exam2_problem_lectures[$i];
				if(is_numeric($row)){
					if($in_epl ==''){
						$in_epl.= $row;
					} else {
						$in_epl.= ','.$row;
					}
				}
			}
			if($in_epl!=''){
				$sql.= ' AND rel_exam2_lecture.cource_id IN( '.$this->db->escape_str($in_epl).' ) ';
			}
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			$exam_ids = $query->result_array();
		}
//var_dump($exam_ids);
		//++++++++++++++++++++++++++++++++++++++++++++
		$product_ids1 = array();
		if( $product_code!="" ){
			$sql = ' SELECT product_id ';
			$sql.= '   FROM ';
			$sql.= '   tbl_product ';
			$sql.= '   WHERE 1=1';
			$search_keyword = explode(  " ",  str_replace( "　", " ", trim($product_code) )  );
			if( count($search_keyword)>0 ){
				$sql.= " AND ( ";
				for($i=0;$i<count($search_keyword);$i++){
					if($i>0){ 
						$sql.= " OR "; 
					}
					$sql.= "  tbl_product.product_code LIKE '%".$this->db->escape_like_str(mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
				}
				$sql.= "  ) ";
			}
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				$arr_temp = $query->result_array();
				foreach ($arr_temp as $row){
					$product_ids1[] = $row["product_id"];
				}
			}
		}
//var_dump($product_ids1);
		//++++++++++++++++++++++++++++++++++++++++++++
		$product_ids2 = array();
		if( $product_name!="" ){
			$sql = ' SELECT product_id ';
			$sql.= '   FROM ';
			$sql.= '   tbl_product ';
			$sql.= '   WHERE 1=1';
			$search_keyword = explode(  " ",  str_replace( "　", " ", trim($product_name) )  );
			if( count($search_keyword)>0 ){
				$sql.= " AND ( ";
				for($i=0;$i<count($search_keyword);$i++){
					if($i>0){ 
						$sql.= " OR "; 
					}
					$sql.= "  tbl_product.product_name LIKE '%".$this->db->escape_like_str(mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
				}
				$sql.= "  ) ";
			}
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				$arr_temp = $query->result_array();
				foreach ($arr_temp as $row){
					$product_ids2[] = $row["product_id"];
				}
			}
		}
//var_dump($product_ids2);
		//++++++++++++++++++++++++++++++++++++++++++++
		if( count($exam_ids)>0 ){
			//++++++++++++++++++++++++++++++++++++++++++++
			//--------------------------------------
			//対象商品の取得
			$sql = "SELECT product_id,product_name,product_code,exam2_id FROM tbl_product WHERE 1=1 ";
			//--------------------------------------
			$sql.= " AND exam2_id IN (";
			for($i1=0;$i1<count($exam_ids);$i1++){ 
				if($i1>0){
					$sql.= ", ";
				}
				$sql.= "".$exam_ids[$i1]["exam2_id"]."";
			}
			$sql.= ") ";
			//--------------------------------------
			if( count($product_ids1)>0 ){
				$sql.= " AND product_id IN (";
				for($i1=0;$i1<count($product_ids1);$i1++){ 
					if($i1>0){
						$sql.= ", ";
					}
					$sql.= "".$product_ids1[$i1]."";
				}
				$sql.= ") ";
			}
			//--------------------------------------
			if( count($product_ids2)>0 ){
				$sql.= " AND product_id IN (";
				for($i1=0;$i1<count($product_ids2);$i1++){ 
					if($i1>0){
						$sql.= ", ";
					}
					$sql.= "".$product_ids2[$i1]."";
				}
				$sql.= ") ";
			}
			//--------------------------------------
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				$arr_csv = $query->result_array();
			}
//var_dump($arr_csv);
			//--------------------------------------
			//++++++++++++++++++++++++++++++++++++++++++++
			//--------------------------------------
			// 商品毎の問題取得
			for($i1=0;$i1<count($arr_csv);$i1++){ 
				$arr_csv[$i1]["exam2"] = array();
				$sql = "SELECT exam2.* FROM exam2 WHERE 1=1 AND exam2_id=".$this->db->escape($arr_csv[$i1]["exam2_id"])." ";
				$query = $this->db->query($sql);
				if ($query->num_rows() > 0){
					$arr_csv[$i1]["exam2"] = $query->result_array();
					$arr_csv[$i1]["exam2"][0]["exam2_problem"] = array();
					$sql = "SELECT exam2_problem.* FROM rel_exam2_problem LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id=exam2_problem.exam2_problem_id WHERE rel_exam2_problem.exam2_id=".$this->db->escape($arr_csv[$i1]["exam2"][0]["exam2_id"])." ORDER BY rel_exam2_problem.exam2_no ASC ";
					$query = $this->db->query($sql);
					if ($query->num_rows() > 0){
						$arr_csv[$i1]["exam2"][0]["exam2_problem"] = $query->result_array();
					}

					$arr_csv[$i1]["exam2"][0]["student"] = array();
					$sql = "SELECT student_id,product_id FROM exam2_answer WHERE exam2_id=".$this->db->escape($arr_csv[$i1]["exam2_id"])." AND product_id=".$this->db->escape($arr_csv[$i1]["product_id"])." GROUP BY student_id,product_id ORDER BY student_id ASC ";
					$query = $this->db->query($sql);
					if ($query->num_rows() > 0){
						$arr_csv[$i1]["exam2"][0]["student"] = $query->result_array();
						for($i2=0;$i2<count($arr_csv[$i1]["exam2"][0]["student"]);$i2++){ 
							$arr_csv[$i1]["exam2"][0]["student"][$i2]["info"] = array();
							$sql = "SELECT ";
							$sql.= " * ";
							$sql.= "FROM ";
							$sql.= " student ";
							$sql.= "WHERE ";
							$sql.= " student_id=".$this->db->escape($arr_csv[$i1]["exam2"][0]["student"][$i2]["student_id"])." ";
							$sql.= "LIMIT 1 ";
							$query = $this->db->query($sql);
							if ($query->num_rows() > 0){
								$arr_csv[$i1]["exam2"][0]["student"][$i2]["info"] = $query->result_array();
							}

							$arr_csv[$i1]["exam2"][0]["student"][$i2]["answer"] = array();
							$sql = "SELECT ";
							$sql.= " exam2_problem_id, student_id, max( exam2_answer_no ) as mean , exam2_answer_contents , exam2_answer_contents_old, exam2_answer_date ";
							$sql.= "FROM ";
							$sql.= " exam2_answer ";
							$sql.= "WHERE ";
							$sql.= " exam2_id=".$this->db->escape($arr_csv[$i1]["exam2_id"])." ";
							$sql.= " AND student_id=".$this->db->escape($arr_csv[$i1]["exam2"][0]["student"][$i2]["student_id"])." ";
							$sql.= " AND product_id=".$this->db->escape($arr_csv[$i1]["exam2"][0]["student"][$i2]["product_id"])." ";
							$sql.= "GROUP BY exam2_id, exam2_problem_id, student_id,product_id ";
							$sql.= "ORDER BY student_id ASC , exam2_problem_id ASC ";
							$query = $this->db->query($sql);
							if ($query->num_rows() > 0){
								$temp_exam2_answer = $query->result_array();
								$temp_answer = array();
								for($problem_i=0;$problem_i<count($arr_csv[$i1]["exam2"][0]["exam2_problem"]);$problem_i++){ 
									$add_flg = 0;
									for($exam2_answer_i=0;$exam2_answer_i<count($temp_exam2_answer);$exam2_answer_i++){ 
										if( $arr_csv[$i1]["exam2"][0]["exam2_problem"][$problem_i]["exam2_problem_id"] == $temp_exam2_answer[$exam2_answer_i]["exam2_problem_id"] ){
											$temp_answer[] = $temp_exam2_answer[$exam2_answer_i];
											$add_flg = 1;
										}
									}
									if( $add_flg == 0 ){
										$temp_answer[] = array(
											"exam2_answer_id"=>"",
											"exam2_id"=>$arr_csv[$i1]["exam2_id"],
											"exam2_problem_id"=>$arr_csv[$i1]["exam2"][0]["exam2_problem"][$problem_i]["exam2_problem_id"],
											"student_id"=>$arr_csv[$i1]["exam2"][0]["student"][$i2]["student_id"],
											"exam2_answer_no"=>"",
											"exam2_answer_contents"=>"",
											"exam2_answer_contents_old"=>"",
											"exam2_answer_date"=>"",
											"exam2_answer_mark"=>"0",
											"exam2_answer_point"=>"0",
										);
									}
								}
								$arr_csv[$i1]["exam2"][0]["student"][$i2]["answer"] = $temp_answer;
							}
						}
					}
				}
			}
			//--------------------------------------
			//++++++++++++++++++++++++++++++++++++++++++++
		}
		//++++++++++++++++++++++++++++++++++++++++++++
		return $arr_csv;
		//++++++++++++++++++++++++++++++++++++++++++++
	}









	function get_exam2_answer_set_list($param){
		//引数設定
		$param = array_merge(
			array(
				'product_id' => 0,
				'exam2_id'   => 0,
			),
			$param
		);
		$arr_exam2 = array();
		$sql = "SELECT exam2.* FROM exam2 WHERE 1=1 AND exam2_id=".$this->db->escape($param["exam2_id"])." ";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			$arr_exam2 = $query->result_array();
			$arr_exam2[0]["exam2_problem"] = array();
			$sql = "SELECT exam2_problem.* FROM rel_exam2_problem LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id=exam2_problem.exam2_problem_id WHERE rel_exam2_problem.exam2_id=".$this->db->escape($arr_exam2[0]["exam2_id"])." ORDER BY rel_exam2_problem.exam2_no ASC ";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				$arr_exam2[0]["exam2_problem"] = $query->result_array();
			}

			$arr_exam2[0]["student"] = array();
			$sql = "SELECT student_id,max(exam2_answer_id) FROM exam2_answer WHERE exam2_id=".$this->db->escape($param["exam2_id"])." AND exam2_answer.product_id=".$this->db->escape($param["product_id"])." AND student_id>0 GROUP BY student_id ORDER BY exam2_answer_id DESC ";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				$arr_exam2[0]["student"] = $query->result_array();
				for($i2=0;$i2<count($arr_exam2[0]["student"]);$i2++){ 
					$arr_exam2[0]["student"][$i2]["info"] = array();
					$sql = "SELECT ";
					$sql.= " * ";
					$sql.= "FROM ";
					$sql.= " student ";
					$sql.= "WHERE ";
					$sql.= " student_id=".$this->db->escape($arr_exam2[0]["student"][$i2]["student_id"])." ";
					$sql.= "LIMIT 1 ";
					$query = $this->db->query($sql);
					if ($query->num_rows() > 0){
						$arr_exam2[0]["student"][$i2]["info"] = $query->result_array();
					}

					$arr_exam2[0]["student"][$i2]["answer"] = array();
					$sql = "SELECT ";
					$sql.= " exam2_answer_id, exam2_problem_id, student_id, max( exam2_answer_no ) as mean, exam2_answer_contents, exam2_answer_contents_old, max(open_review) as open_review_max, update_count ";
					$sql.= "FROM ";
					$sql.= " exam2_answer ";
					$sql.= "WHERE ";
					$sql.= " exam2_id=".$this->db->escape($param["exam2_id"])." ";
					$sql.= " AND exam2_answer.product_id=".$this->db->escape($param["product_id"])." ";
					$sql.= " AND student_id=".$this->db->escape($arr_exam2[0]["student"][$i2]["student_id"])." ";
					$sql.= "GROUP BY exam2_id, exam2_problem_id, student_id ";
					$sql.= "ORDER BY student_id ASC , exam2_problem_id ASC ";
					$query = $this->db->query($sql);
					//if ($query->num_rows() > 0){
					//	$arr_exam2[0]["student"][$i2]["answer"] = $query->result_array();
					//}
					if ($query->num_rows() > 0){
						$temp_exam2_answer = $query->result_array();
						$temp_answer = array();
						for($problem_i=0;$problem_i<count($arr_exam2[0]["exam2_problem"]);$problem_i++){ 
							$add_flg = 0;
							for($exam2_answer_i=0;$exam2_answer_i<count($temp_exam2_answer);$exam2_answer_i++){ 
								if( $arr_exam2[0]["exam2_problem"][$problem_i]["exam2_problem_id"] == $temp_exam2_answer[$exam2_answer_i]["exam2_problem_id"] ){
									$temp_answer[] = $temp_exam2_answer[$exam2_answer_i];
									$add_flg = 1;
								}
							}
							if( $add_flg == 0 ){
								$temp_answer[] = array(
									"exam2_answer_id"=>"",
									"exam2_id"=>$param["exam2_id"],
									"exam2_problem_id"=>$arr_exam2[0]["exam2_problem"][$problem_i]["exam2_problem_id"],
									"student_id"=>$arr_exam2[0]["student"][$i2]["student_id"],
									"exam2_answer_no"=>"",
									"exam2_answer_contents"=>"",
									"exam2_answer_contents_old"=>"",
									"exam2_answer_date"=>"",
									"exam2_answer_mark"=>"0",
									"exam2_answer_point"=>"0",
								);
							}
						}
						$arr_exam2[0]["student"][$i2]["answer"] = $temp_answer;
					}
				}
			}
		}
		return $arr_exam2;
	}

	function get_exam2_answer_set_list_review($param){
		//引数設定
		$param = array_merge(
			array(
				'product_id' => 0,
				'exam2_id'   => 0,
			),
			$param
		);

		$review_list = array();

		$sql = "SELECT";
		$sql.= "  student_id, exam2_answer_review_contents";
		$sql.= " FROM";
		$sql.= "  exam2_answer_review";
		$sql.= " WHERE";
		$sql.= "  status = 0";
		$sql.= "  AND exam2_id = ".$this->db->escape($param["exam2_id"]);
		$sql.= "  AND product_id = ".$this->db->escape($param["product_id"]);
		$sql.= "  AND student_id>0 ";

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			$result = $query->result_array();
			foreach($result as $val){
				$review_list[$val['student_id']] = $val['exam2_answer_review_contents'];
			}
		}

		return $review_list;
	}



}
?>
