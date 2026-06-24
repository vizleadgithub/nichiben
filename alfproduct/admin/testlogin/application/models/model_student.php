<?php
class Model_student extends CI_Model
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
	//受講者検索結果一覧取得
	// [2012/10/12]条件に「講座」追加
	//----------------------------------------------
	function get_student_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'      => 0,
							's_name'           => '',
							's_email'          => '',
							's_student_id'     => 0,
							's_cource'         => 0,
							's_birthday_start' => '',
							's_birthday_end'   => '',
							's_free_word'      => '',
							'offset'           => 0,
							'rowcount'         => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_student_selectsql($param);
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
	//受講者検索結果件数取得
	// [2012/10/12]条件に「講座」追加
	//----------------------------------------------
	function get_student_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'      => 0,
							's_name'           => '',
							's_email'          => '',
							's_student_id'     => 0,
							's_cource'         => 0,
							's_birthday_start' => '',
							's_birthday_end'   => '',
							's_free_word'      => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_student_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//受講者検索結果一覧件数取得
	// [2012/10/12]条件に「講座」追加
	//----------------------------------------------
	function _get_student_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'      => 0,
							's_name'           => '',
							's_email'          => '',
							's_student_id'     => 0,
							's_cource'         => 0,
							's_birthday_start' => '',
							's_birthday_end'   => '',
							's_free_word'      => '',
						),
						$param
					);
		//SQL生成
		$sql_select = "SELECT
							student_id,
							student_name,
							student_email,
							DATE_FORMAT(student_birthday,'%Y/%m/%d') AS student_birthday 
						FROM
							student";
		$sql_where = " WHERE
						status = 0";
		$sql_order = " ORDER BY
						student_id";
		
		//学校ID
		$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
		
		//受講者氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND student_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		//メールアドレス
		if (isset($param['s_email']) && $param['s_email'] != '') {
			$sql_where .= " AND student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'";
		}
		//ID
		if (isset($param['s_student_id']) && $param['s_student_id'] != '' ) {
			if(is_numeric($param['s_student_id'])){
				$sql_where .= " AND student_id = {$this->db->escape($param['s_student_id'])}";
			} else {
				$sql_where .= " AND student_id = 0";
			}
		}

		//講座
		if (isset($param['s_cource']) && $param['s_cource'] != 0) {
			$sql_where .= " AND student_id IN ( SELECT student_id FROM student_lecture WHERE cource_id = {$this->db->escape($param['s_cource'])} ) ";
		}

		//誕生日開始
		if (isset($param['s_birthday_start']) && $param['s_birthday_start'] != '') {
			$sql_where .= " AND student_birthday >= {$this->db->escape($param['s_birthday_start'])}";
		}
		//誕生日終了
		if (isset($param['s_birthday_end']) && $param['s_birthday_end'] != '') {
			$sql_where .= " AND student_birthday <= {$this->db->escape($param['s_birthday_end'])}";
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (student_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_student($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										student_id,
										student_name,
										student_email,
										student_password_encrypt,
										DATE_FORMAT(student_birthday,'%Y/%m/%d') as student_birthday,
										student_note,
										school_id,
										status,
										update_at
									FROM
										student
									WHERE
										student_id = {$this->db->escape($param['student_id'])}
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
	//受講講座取得
	//----------------------------------------------
	function get_student_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										student_lecture
									WHERE
										student_id = {$this->db->escape($param['student_id'])}
									ORDER BY
										cource_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return null;
		}
	}

	//----------------------------------------------
	// 更新処理（ver2：API対応）
	// [2012/11/01]eLM API対応、API失敗時はロールバックするように変更
	// [2012/11/01]上記対応にあわせ、受講者講座テーブルへの更新処理を追加（コミット・ロールバックの関係）
	//----------------------------------------------
	function update_student($param){
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
		
		// DELETE / INSERT student_lecture 
		$sql_delete_student_lecture = "DELETE FROM student_lecture WHERE student_id = ?";
		$sql_insert_student_lecture = "INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?)";
		
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
			$sql = "INSERT INTO student (
						student_name,
						student_email,
						student_password_encrypt,
						student_birthday,
						student_note,
						school_id,
						status,
						update_at
					) VALUES(?,?,?,?,?,?,?,?)";
			
			$this->db->trans_begin();
			
			// INSERT student
			$this->db->query($sql, array(
								$data['student_name'],
								$data['student_email'],
								hash('sha256',$data['student_password']),
								$data['student_birthday'],
								$data['student_note'],
								$data['school_id'],
								0,
								$wDate
							));
			// 登録したstudent_idを取得
			$return_data['prev_id'] = $this->db->insert_id();

			// DELETE student_lecture
			$this->db->query($sql_delete_student_lecture, array(
								$return_data['prev_id']
							));

			// INSERT student_lecture
			foreach($data['student_lectures'] as $cource_id) {
				$this->db->query($sql_insert_student_lecture, array(
									$return_data['prev_id'],
									$cource_id,
									$wDate
								));
			}
			
			if($outside_elearningmanager == false){
				$this->db->trans_commit();
			}else{
				// eLM API 実行
				$content = $this->_connect_elm_api(array(
							'api_flag'				=> 'INSERT',
							'before_student_email'	=> $data['student_email'],
							'data'					=> $data,
							'return_data'			=> $return_data,
				));
				$return_data['result']  = $content['result'];
				$return_data['stat']    = $content['stat'];
				$return_data['message'] = $content['message'];
				
				if($return_data['stat'] == 200){
					$this->db->trans_commit();
				}else{
					$this->db->trans_rollback();
				}
			}
			
			// 登録処理正常終了
			return $return_data;
		}else{
			
			// 更新前メールアドレス取得
			$db_data = $this->get_student(array(
				'student_id' => $data['student_id'],
			));
			
			//修正
			if($data['student_password_change'] == 1){

				$this->db->trans_begin();

				// パスワード含む更新
				$sql = "UPDATE student
						SET student_name     = ?,
							student_email    = ?,
							student_password_encrypt = ?,
							student_birthday = ?,
							student_note     = ?,
							school_id        = ?,
							status           = ?,
							update_at        = ?
						WHERE student_id = ?";
				$this->db->query($sql, array(
									$data['student_name'],
									$data['student_email'],
									hash('sha256',$data['student_password']),
									$data['student_birthday'],
									$data['student_note'],
									$data['school_id'],
									0,
									$wDate,
									$data['student_id']
								));
				
				// 同じメールアドレスを持つ受講者のパスワードを全て更新する（必須）
				$sql = "UPDATE student
						SET student_password_encrypt = ?,
							update_at                = ?
						WHERE student_email = ?";
				$this->db->query($sql, array(
									hash('sha256',$data['student_password']),
									$wDate,
									$data['student_email']
								));

				// 更新したstudent_idを取得
				$return_data['prev_id'] = $data['student_id'];

				// DELETE student_lecture
				$this->db->query($sql_delete_student_lecture, array(
									$return_data['prev_id']
								));

				// INSERT student_lecture
				foreach($data['student_lectures'] as $cource_id) {
					$this->db->query($sql_insert_student_lecture, array(
										$return_data['prev_id'] ,
										$cource_id,
										$wDate
									));
				}

				if($outside_elearningmanager == false){
					$this->db->trans_commit();
				}else{
					// eLM API 実行
					$content = $this->_connect_elm_api(array(
								'api_flag'				=> 'UPDATE',
								'before_student_email'	=> $db_data['student_email'],
								'data'					=> $data,
								'return_data'			=> $return_data,
					));
					$return_data['result']  = $content['result'];
					$return_data['stat']    = $content['stat'];
					$return_data['message'] = $content['message'];
					
					if($return_data['stat'] == 200){
						$this->db->trans_commit();
					}else{
						$this->db->trans_rollback();
					}
				}
				
				// 更新処理正常終了
				return $return_data;
			}else{
				
				$this->db->trans_begin();
				
				// パスワード除く更新
				$sql = "UPDATE student
						SET student_name     = ?,
							student_email    = ?,
							student_birthday = ?,
							student_note     = ?,
							school_id        = ?,
							status           = ?,
							update_at        = ?
						WHERE student_id = ?";
				$this->db->trans_start();
				$this->db->query($sql, array(
									$data['student_name'],
									$data['student_email'],
									$data['student_birthday'],
									$data['student_note'],
									$data['school_id'],
									0,
									$wDate,
									$data['student_id']
								));

				// 更新したstudent_idを取得
				$return_data['prev_id'] = $data['student_id'];

				// DELETE student_lecture
				$this->db->query($sql_delete_student_lecture, array(
									$return_data['prev_id']
								));

				// INSERT student_lecture
				foreach($data['student_lectures'] as $cource_id) {
					$this->db->query($sql_insert_student_lecture, array(
										$return_data['prev_id'] ,
										$cource_id,
										$wDate
									));
				}

				if($outside_elearningmanager == false){
					$this->db->trans_commit();
				}else{
					// eLM API 実行
					$content = $this->_connect_elm_api(array(
								'api_flag'				=> 'UPDATE',
								'before_student_email'	=> $db_data['student_email'],
								'data'					=> $data,
								'return_data'			=> $return_data,
					));
					$return_data['result']  = $content['result'];
					$return_data['stat']    = $content['stat'];
					$return_data['message'] = $content['message'];

					if($return_data['stat'] == 200){
						$this->db->trans_commit();
					}else{
						$this->db->trans_rollback();
					}
				}
				
				// 更新処理正常終了
				return $return_data;
			}
		}
	}
	
	//----------------------------------------------
	// 削除処理
	// [2012/11/01]eLM API対応、API失敗時はロールバックするように変更
	//----------------------------------------------
	function delete_student($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id' => 0,
						),
						$param
					);

		// 更新前メールアドレス取得
		$db_data = $this->get_student($param);

		//現在日時取得
		$wDate = date('Y/m/d H:i:s');

		//レスポンス用変数
		$return_data['prev_id'] = $db_data['student_id'];
		$return_data['result']  = true;
		$return_data['stat']    = 200;
		$return_data['message'] = '';

		// 外部連携、契約あり・なし確認（DBから取得）
		$outside_elearningmanager = false;
		$school_contract_param = $this->libauth->get_login_school_contract_param($db_data['school_id']);
		if(isset($school_contract_param['outside_elearningmanager'])){
			if($school_contract_param['outside_elearningmanager']['contract']==='fixation'){
				$outside_elearningmanager = true;
			}
		}
		
		//SQL生成
		$sql = "UPDATE student 
				SET status = 9, 
					update_at = ? 
				WHERE student_id = ? ";
		
		$this->db->trans_begin();
		$this->db->query($sql, array(
							$wDate,
							$param['student_id'],
						));
		
		if($outside_elearningmanager == false){
			$this->db->trans_commit();
		}else{
			// eLM API 実行
			$content = $this->_connect_elm_api(array(
						'api_flag'				=> 'DELETE',
						'before_student_email'	=> $db_data['student_email'],
						'data'					=> $db_data,
						'return_data'			=> $return_data,
			));
			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message'];

			if($return_data['stat'] == 200){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}
		}
		
		return $return_data;
	}
	
	//----------------------------------------------
	//受講者チェックボックス用一覧取得
	//----------------------------------------------
	function get_student_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//SQL生成
		$sql = "SELECT 
						student_id,
						student_name
					FROM 
						student
					WHERE 
						status = 0
						AND school_id = {$this->db->escape($param['school_id'])}
					ORDER BY
						student_id
				";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	//----------------------------------------------
	// [2012/11/30]受講者チェックボックス用一覧取得
	// ※授業に登録済みの受講者、及び授業が所属する講座に属する受講者を取得
	//----------------------------------------------
	function get_student_list_after_class($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'class_id'    => 0,
							'cource_id'   => 0,
						),
						$param
					);
		//SQL生成
		$sql  = '';
		$sql .= "SELECT student_id, student_name, student_email ";
		$sql .= "  FROM student ";
		$sql .= " WHERE STATUS = 0 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= "   AND (";
		$sql .= "         student_id IN (SELECT student_id FROM student_lecture_class WHERE class_id = {$this->db->escape($param['class_id'])}) ";
		$sql .= "       OR ";
		$sql .= "         student_id IN (SELECT student_id FROM student_lecture WHERE cource_id = {$this->db->escape($param['cource_id'])})";
		$sql .= "       ) ";
		$sql .= " ORDER BY student_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	
	//----------------------------------------------
	//受講者名取得
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										student_name, student_email
									FROM
										student
									WHERE
										student_id = '{$param['student_id']}'
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data;
		}else{
			return null;
		}
	}

	//----------------------------------------------
	// [2012/11/01] eLM APIの実行
	// ユーザ登録・更新・削除、コースのユーザ割当
	// api_flag => INSERT UPDATE DELETE
	//----------------------------------------------
	function _connect_elm_api($param){
		//引数設定
		$param = array_merge(
						array(
							'api_flag'				=> '',
							'before_student_email'	=> '',
							'data'					=> array(),
							'return_data'			=> array(),
						),
						$param
					);
		$api_flag				= $param['api_flag'];
		$before_student_email	= $param['before_student_email'];
		$data					= $param['data'];
		$return_data			= $param['return_data'];

		$this->load->model('model_outside_gingerapp');

		// ユーザ登録・更新・削除
		if($api_flag == 'INSERT'){
			// eLM API 実行（ユーザ登録）
			$request['school_id']     = $data['school_id'];
			$request['student_id']    = $return_data['prev_id'];
			$request['student_name']  = $data['student_name'];
			$request['student_email'] = $data['student_email'];
			$content = $this->model_outside_gingerapp->user_insert($request);
				
			// eLM API 結果取得（ユーザ登録）
			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message']."[user_insert]";
			
			if($return_data['stat'] != 200){
				return $return_data;
			}
		}elseif ($api_flag == 'UPDATE'){
			// eLM API 実行（ユーザ更新）
			$request['school_id']            = $data['school_id'];
			$request['student_id']           = $return_data['prev_id'];
			$request['student_name']         = $data['student_name'];
			$request['student_email']        = $data['student_email'];
			$request['before_student_email'] = $before_student_email;
			$content = $this->model_outside_gingerapp->user_update($request);
			
			// eLM API 結果取得（ユーザ更新）
			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message'];
			
			// 未登録ユーザの場合、ユーザ登録を実行
			if($return_data['stat'] == 422){
				// eLM API 実行（ユーザ登録）
				$request['school_id']     = $data['school_id'];
				$request['student_id']    = $return_data['prev_id'];
				$request['student_name']  = $data['student_name'];
				$request['student_email'] = $data['student_email'];
				$content = $this->model_outside_gingerapp->user_insert($request);
					
				// eLM API 結果取得（ユーザ登録）
				$return_data['result']  = $content['result'];
				$return_data['stat']    = $content['stat'];
				$return_data['message'] = $content['message']."[user_insert]";
			}

			// メールアドレスが更新されるため、値を入れ替え
			$before_student_email = $data['student_email'];
			
			if($return_data['stat'] != 200){
				return $return_data;
			}
		}elseif ($api_flag == 'DELETE'){
			// eLM API 実行（ユーザ削除）
			$request['school_id']     = $data['school_id'];
			$request['student_id']    = $return_data['prev_id'];
			$request['student_email'] = $before_student_email;
			$content = $this->model_outside_gingerapp->user_delete($request);
			
			// eLM API 結果取得（ユーザ削除）
			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message']."[user_delete]";
			
			if($return_data['stat'] != 200){
				return $return_data;
			}
		}else{
			return $return_data;
		}

		if( ($api_flag == 'INSERT') or ($api_flag == 'UPDATE') ){
			// 更新対象受講者所属の講座IDを取得
			$cource_id_list = '';
			$query = $this->db->query(
				'SELECT student_lecture.student_id, student_lecture.cource_id, cource.cource_name, cource.cource_caption '.
				'  FROM student_lecture LEFT JOIN cource ON student_lecture.cource_id = cource.cource_id '.
				' WHERE 1 = 1 AND student_lecture.student_id = ? '.
				' ORDER BY student_lecture.cource_id ASC ',
				array(
					$return_data['prev_id'],
				)
			);
			
			// 対象受講者が所属する講座をeLM APIにて【コース登録】
			foreach($query->result_array() as $row){
				$request['school_id']      = $data['school_id'];
				$request['cource_id']      = $row['cource_id'];
				$request['cource_name']    = $row['cource_name'];
				$request['cource_caption'] = $row['cource_caption'];
				$content = $this->model_outside_gingerapp->course_insert($request);
				
				$return_data['result']  = $content['result'];
				$return_data['stat']    = $content['stat'];
				$return_data['message'] = $content['message']."[course_insert]";
				
				// 正常・登録済みコース 以外をエラーとする
				if($return_data['stat'] == 200){
				}elseif($return_data['stat'] == 423){
				}else{
					return $return_data;
				}
				
				if($cource_id_list == ''){
					$cource_id_list  = $row['cource_id'];
				}else{
					$cource_id_list .= ",".$row['cource_id'];
				}
			}
			
			// 該当コースのユーザ割当削除（ユーザベース）
			$request['school_id']     = $data['school_id'];
			$request['student_id']    = $return_data['prev_id'];
			$request['student_email'] = $before_student_email;
			$content = $this->model_outside_gingerapp->assign_delete_user($request);
			
			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message']."[assign_delete_user]";
			
			if($return_data['stat'] != 200){
				return $return_data;
			}
			
			// 該当コースのユーザ割当（ユーザベース）
			$request['school_id']     = $data['school_id'];
			$request['student_id']    = $return_data['prev_id'];
			$request['student_email'] = $before_student_email;
			$request['cource_id']     = $cource_id_list;
			$content = $this->model_outside_gingerapp->assign_insert_user($request);

			$return_data['result']  = $content['result'];
			$return_data['stat']    = $content['stat'];
			$return_data['message'] = $content['message']."[assign_insert_user]";
			
			if($content['stat']!=200){
				return $return_data;
			}
		}
		
		return $return_data;
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の受講者を取得
	//----------------------------------------------
	function get_cource_student($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'	=> 0,
							'cource_id'	=> 0,
							'free_word'	=> '',
							'cource_flag'	=> 0,
						),
						$param
					);

		//SQL生成
		$sql  = "";
		$sql .= "SELECT student.student_id, student.student_name, student.student_email ";
		$sql .= "  FROM student ";
		$sql .= " WHERE student.status = 0 ";
		$sql .= "   AND student.school_id = {$this->db->escape($param['school_id'])} ";

		if (isset($param['cource_flag']) && $param['cource_flag'] != 0 && $param['cource_id'] > 0 )  {
			$sql .= "   AND student.student_id IN (SELECT student_lecture.student_id FROM student_lecture WHERE student_lecture.cource_id = {$this->db->escape($param['cource_id'])}) ";
		}

		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        student.student_name  LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR student.student_note  LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR student.student_email LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		$sql .= " ORDER BY student.student_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}


}
?>
