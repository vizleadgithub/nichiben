<?php
#[AllowDynamicProperties]
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
							's_school_id'                   => 0,
							's_name'                        => '',
							's_lawyer_number'               => '',
							's_lawyer_division'             => 0,
							's_email'                       => '',
							's_bar_association'             => '',
							's_free_word'                   => '',
							's_sub_auth_ethic_training_on'  => '',
							's_sub_auth_ethic_training_off' => '',
							'offset'                        => 0,
							'rowcount'                      => 0,
							'order_by'                      => 0,
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
			return [];
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
							's_school_id'                   => 0,
							's_name'                        => '',
							's_lawyer_number'               => '',
							's_lawyer_division'             => 0,
							's_email'                       => '',
							's_bar_association'             => '',
							's_free_word'                   => '',
							's_sub_auth_ethic_training_on'  => '',
							's_sub_auth_ethic_training_off' => '',
							'offset'                        => 0,
							'rowcount'                      => 0,
							'order_by'                      => 0,
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
	// 法学館対応：生年月日が任意になるための対応
	//
	// 日弁連対応（カラム追加）
	//----------------------------------------------
	function _get_student_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'                   => 0,
							's_name'                        => '',
							's_lawyer_number'               => '',
							's_lawyer_division'             => 0,
							's_email'                       => '',
							's_bar_association'             => '',
							's_free_word'                   => '',
							's_sub_auth_ethic_training_on'  => '',
							's_sub_auth_ethic_training_off' => '',
							'offset'                        => 0,
							'rowcount'                      => 0,
							'order_by'                      => 0,
						),
						$param
					);
		
		//SQL生成
		// 受講者ID・受講者名・メールアドレス・登録番号・パスポート有無・代替権限・弁護士会ID
		$sql_select = "SELECT
							 student.student_id
							,student.student_name
							,student.student_email
							,student.lawyer_number
							,student.lawyer_division
							,student.presence_passport
							,student.target_passport
							,student.sub_auth_ethic_training
							,student.bar_association_id
							,(CASE WHEN mtb_bar_association.name IS NULL THEN '' ELSE mtb_bar_association.name END) AS bar_association_name
						FROM
							student LEFT JOIN mtb_bar_association ON student.bar_association_id = mtb_bar_association.id ";
		$sql_where = " WHERE
						student.status = 0";
		$sql_order = " ORDER BY
						student.lawyer_number ASC";
		
		
		if($param['order_by'] != 0){
			$sql_order = " ORDER BY
							student.lawyer_number DESC";
		}
		
		//学校ID
		$sql_where .= " AND student.school_id = {$this->db->escape($param['s_school_id'])}";
		
		//受講者氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND student.student_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		
		// 登録番号
		if (isset($param['s_lawyer_number']) && $param['s_lawyer_number'] != '' ) {
			$sql_where .= " AND student.lawyer_number = {$this->db->escape($param['s_lawyer_number'])}";
		}
		
		// 会員区分
		// 0：条件に入れない。1～5：条件検索、999：1～5以外
		if (isset($param['s_lawyer_division']) && $param['s_lawyer_division'] > 0 ) {
			if($param['s_lawyer_division'] == 999){
				$sql_where .= " AND student.lawyer_division NOT IN ( 1, 2, 3, 4, 5 ) ";
			}else{
				$sql_where .= " AND student.lawyer_division = {$this->db->escape($param['s_lawyer_division'])}";
			}
		}
		
		
		
		//メールアドレス	//*** 法学館対応：携帯メールアドレスも検索対象 ***/
		if (isset($param['s_email']) && $param['s_email'] != '') {
		//	$sql_where .= " AND student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'";
			$sql_where .= " AND (student.student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
								OR student.student_email_mobile LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
			)";
		}
		
		// 弁護士会ID（所属弁護士会）
		if (isset($param['s_bar_association']) && $param['s_bar_association'] != '') {
			$sql_where .= " AND student.bar_association_id = {$this->db->escape($param['s_bar_association'])}";
		}
		

		//フリーワード
		//*** 法学館対応：下記内容も検索対象 ***/
			/*
			student_name_kana（生徒氏名カナ）
			address1（市区町村）
			address2（番地）
			address3（建物名）
			address_overseas1（海外住所・住所1）
			address_overseas2（海外住所・住所2）
			school_name（学校名）
			student_no（伊藤塾塾生番号）
			*/
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
		//	$sql_where .= " AND (student_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
		//						OR student_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
		//	)";
			$sql_where .= " AND   (student.student_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_name_kana LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address3 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address_overseas1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.address_overseas2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.school_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student.student_no LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}

		// 代替権限
		if (isset($param['s_sub_auth_ethic_training_on']) && $param['s_sub_auth_ethic_training_on'] == '1') {
			$sql_where .= " AND student.sub_auth_ethic_training = 1 ";
		}
		if (isset($param['s_sub_auth_ethic_training_off']) && $param['s_sub_auth_ethic_training_off'] == '1') {
			$sql_where .= " AND student.sub_auth_ethic_training = 0 ";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//一件取得
	// 20130926修正 - ログイン管理者の所属弁護士会IDを条件に追加（日弁連所属は除く）
	//----------------------------------------------
	function get_student($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id'          => 0,
							'bar_association_id'  => '',
						),
						$param
					);

		// SQL作成
		$sql  = '';
		$sql .= ' SELECT  student_id ';
		$sql .= '        ,student_name ';				// 氏名
		$sql .= '        ,lawyer_number ';				// 弁護士番号
		$sql .= '        ,lawyer_division ';			// 会員区分
		$sql .= '        ,bar_association_id ';			// 所属弁護士会ID
		$sql .= "        ,IF(regist_date       = '0000-00-00', '', DATE_FORMAT(regist_date,       '%Y/%m/%d')) AS regist_date ";			// 登録年月日
		$sql .= "        ,IF(exp_date_passport = '0000-00-00', '', DATE_FORMAT(exp_date_passport, '%Y/%m/%d')) AS exp_date_passport ";		// パスポートの有効期限
		$sql .= "        ,presence_passport ";			// パスポートの有無 0:なし、1:あり
		$sql .= '        ,target_passport ';			// 対象パスポート
		$sql .= '        ,student_email ';				// メールアドレス
		$sql .= '        ,mailmagazine_flg ';			// 0:メルマガ拒否 1:メルマガ許可（初期値0）
		$sql .= '        ,sub_auth_ethic_training ';	// 代替倫理研修権限 0:なし（禁止）、1:あり（許可）
		$sql .= '   FROM  student ';
		$sql .= '  WHERE  student_id = ? ';
		if($param['bar_association_id'] != ''){
				$sql .= "    AND  bar_association_id = {$param['bar_association_id']} ";
		}

		$sql .= '    AND  status = 0 ';
		
		//SQL投入
		$query = $this->db->query($sql, array(
			$param['student_id'],
		));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
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
			return [];
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
		/*	$sql = "INSERT INTO student (
						student_name,
						student_email,
						student_password_encrypt,
						student_birthday,
						student_note,
						school_id,
						status,
						update_at
					) VALUES(?,?,?,?,?,?,?,?)";	*/

			$sql = "INSERT INTO student (
						 student_name
						,student_email
						,student_password_encrypt
						,student_birthday
						,student_note
						,school_id
						,status
						,update_at
						
						,regist_at
						,delete_at
						,student_name_kana
						,sex
						,country_type
						,zip
						,pref
						,address1
						,address2
						,address3
						,zip_overseas
						,address_overseas1
						,address_overseas2
						,member_type
						,tel1
						,tel2
						,tel3
						,fax1
						,fax2
						,fax3
						,student_email_mobile
						,job
						,job_type
						,school_name
						,school_grade
						,password_question
						,password_answer
						,mailmagazine_flg
						,mailmagazine_ids
						,media_id
						,age
						,student_no
						,user_last_update_date
					) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			
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
								$wDate,
								
								$data['regist_at'],
								$data['delete_at'],
								$data['student_name_kana'],
								$data['sex'],
								$data['country_type'],
								$data['zip'],
								$data['pref'],
								$data['address1'],
								$data['address2'],
								$data['address3'],
								$data['zip_overseas'],
								$data['address_overseas1'],
								$data['address_overseas2'],
								$data['member_type'],
								$data['tel1'],
								$data['tel2'],
								$data['tel3'],
								$data['fax1'],
								$data['fax2'],
								$data['fax3'],
								$data['student_email_mobile'],
								$data['job'],
								$data['job_type'],
								$data['school_name'],
								$data['school_grade'],
								$data['password_question'],
								hash('sha256',$data['password_answer']),
								$data['mailmagazine_flg'],
								$data['mailmagazine_ids'],
								$data['media_id'],
								$data['age'],
								$data['student_no'],
								$data['user_last_update_date'],
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
							update_at        = ?, ";

				$sql .= "	regist_at            = ?,
							delete_at            = ?,
							student_name_kana    = ?,
							sex                  = ?,
							country_type         = ?,
							zip                  = ?,
							pref                 = ?,
							address1             = ?,
							address2             = ?,
							address3             = ?,
							zip_overseas         = ?,
							address_overseas1    = ?,
							address_overseas2    = ?,
							member_type          = ?,
							tel1                 = ?,
							tel2                 = ?,
							tel3                 = ?,
							fax1                 = ?,
							fax2                 = ?,
							fax3                 = ?,
							student_email_mobile = ?,
							job                  = ?,
							job_type             = ?,
							school_name          = ?,
							school_grade         = ?, ";
				
				// パスワード確認の修正がある場合はSQL文追加
				if($data['password_answer_change'] == 1){
				$sql .= "	password_question    = ?,
							password_answer      = ?, ";
				}
							
				$sql .= "	mailmagazine_flg     = ?,
							mailmagazine_ids     = ?,
							media_id             = ?,
							age                  = ?,
							student_no           = ?,
							user_last_update_date= ?
						WHERE student_id = ?";

				if($data['password_answer_change'] == 1){
					$this->db->query($sql, array(
										$data['student_name'],
										$data['student_email'],
										hash('sha256',$data['student_password']),
										$data['student_birthday'],
										$data['student_note'],
										$data['school_id'],
										0,
										$wDate,

										$data['regist_at'],
										$data['delete_at'],
										$data['student_name_kana'],
										$data['sex'],
										$data['country_type'],
										$data['zip'],
										$data['pref'],
										$data['address1'],
										$data['address2'],
										$data['address3'],
										$data['zip_overseas'],
										$data['address_overseas1'],
										$data['address_overseas2'],
										$data['member_type'],
										$data['tel1'],
										$data['tel2'],
										$data['tel3'],
										$data['fax1'],
										$data['fax2'],
										$data['fax3'],
										$data['student_email_mobile'],
										$data['job'],
										$data['job_type'],
										$data['school_name'],
										$data['school_grade'],
										$data['password_question'],
										hash('sha256',$data['password_answer']),
										$data['mailmagazine_flg'],
										$data['mailmagazine_ids'],
										$data['media_id'],
										$data['age'],
										$data['student_no'],
										$data['user_last_update_date'],

										$data['student_id']
									));
				}else{
					$this->db->query($sql, array(
										$data['student_name'],
										$data['student_email'],
										hash('sha256',$data['student_password']),
										$data['student_birthday'],
										$data['student_note'],
										$data['school_id'],
										0,
										$wDate,

										$data['regist_at'],
										$data['delete_at'],
										$data['student_name_kana'],
										$data['sex'],
										$data['country_type'],
										$data['zip'],
										$data['pref'],
										$data['address1'],
										$data['address2'],
										$data['address3'],
										$data['zip_overseas'],
										$data['address_overseas1'],
										$data['address_overseas2'],
										$data['member_type'],
										$data['tel1'],
										$data['tel2'],
										$data['tel3'],
										$data['fax1'],
										$data['fax2'],
										$data['fax3'],
										$data['student_email_mobile'],
										$data['job'],
										$data['job_type'],
										$data['school_name'],
										$data['school_grade'],
										$data['mailmagazine_flg'],
										$data['mailmagazine_ids'],
										$data['media_id'],
										$data['age'],
										$data['student_no'],
										$data['user_last_update_date'],

										$data['student_id']
									));
				}
				
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
							update_at        = ?, ";

				$sql .= "	regist_at            = ?,
							delete_at            = ?,
							student_name_kana    = ?,
							sex                  = ?,
							country_type         = ?,
							zip                  = ?,
							pref                 = ?,
							address1             = ?,
							address2             = ?,
							address3             = ?,
							zip_overseas         = ?,
							address_overseas1    = ?,
							address_overseas2    = ?,
							member_type          = ?,
							tel1                 = ?,
							tel2                 = ?,
							tel3                 = ?,
							fax1                 = ?,
							fax2                 = ?,
							fax3                 = ?,
							student_email_mobile = ?,
							job                  = ?,
							job_type             = ?,
							school_name          = ?,
							school_grade         = ?, ";

				// パスワード確認の修正がある場合はSQL文追加
				if($data['password_answer_change'] == 1){
				$sql .= "	password_question    = ?,
							password_answer      = ?, ";
				}

				$sql .= "	mailmagazine_flg     = ?,
							mailmagazine_ids     = ?,
							media_id             = ?,
							age                  = ?,
							student_no           = ?,
							user_last_update_date= ?
						WHERE student_id = ?";
				$this->db->trans_start();

				if($data['password_answer_change'] == 1){
					$this->db->query($sql, array(
										$data['student_name'],
										$data['student_email'],
										$data['student_birthday'],
										$data['student_note'],
										$data['school_id'],
										0,
										$wDate,

										$data['regist_at'],
										$data['delete_at'],
										$data['student_name_kana'],
										$data['sex'],
										$data['country_type'],
										$data['zip'],
										$data['pref'],
										$data['address1'],
										$data['address2'],
										$data['address3'],
										$data['zip_overseas'],
										$data['address_overseas1'],
										$data['address_overseas2'],
										$data['member_type'],
										$data['tel1'],
										$data['tel2'],
										$data['tel3'],
										$data['fax1'],
										$data['fax2'],
										$data['fax3'],
										$data['student_email_mobile'],
										$data['job'],
										$data['job_type'],
										$data['school_name'],
										$data['school_grade'],
										$data['password_question'],
										hash('sha256',$data['password_answer']),
										$data['mailmagazine_flg'],
										$data['mailmagazine_ids'],
										$data['media_id'],
										$data['age'],
										$data['student_no'],
										$data['user_last_update_date'],

										$data['student_id']
									));
				}else{
					$this->db->query($sql, array(
										$data['student_name'],
										$data['student_email'],
										$data['student_birthday'],
										$data['student_note'],
										$data['school_id'],
										0,
										$wDate,

										$data['regist_at'],
										$data['delete_at'],
										$data['student_name_kana'],
										$data['sex'],
										$data['country_type'],
										$data['zip'],
										$data['pref'],
										$data['address1'],
										$data['address2'],
										$data['address3'],
										$data['zip_overseas'],
										$data['address_overseas1'],
										$data['address_overseas2'],
										$data['member_type'],
										$data['tel1'],
										$data['tel2'],
										$data['tel3'],
										$data['fax1'],
										$data['fax2'],
										$data['fax3'],
										$data['student_email_mobile'],
										$data['job'],
										$data['job_type'],
										$data['school_name'],
										$data['school_grade'],
										$data['mailmagazine_flg'],
										$data['mailmagazine_ids'],
										$data['media_id'],
										$data['age'],
										$data['student_no'],
										$data['user_last_update_date'],

										$data['student_id']
									));
				}

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
			return [];
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
			return [];
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
			return [];
		}
	}

	//----------------------------------------------
	//受講者名取得
	//----------------------------------------------
	function get_name_array($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id_list'   => "0",
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
			 SELECT  student.student_id 
			        ,student.student_name 
			        ,student.student_email 
			   FROM  student 
			  WHERE  student.student_id IN ({$param['student_id_list']})
			   AND   student.status = 0 
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->result_array();
			return $data;
		}else{
			return [];
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
			return [];
		}
	}


	
	//** 法学館用 **//
	//----------------------------------------------
	//各種マスタ、リスト取得
	/*
	mtb_gender            : 性別マスタ
	mtb_country_type      : 国種別マスタ
	mtb_pref              : 都道府県マスタ
	mtb_job               : 職業マスタ
	mtb_job_type          : 業種マスタ
	mtb_school_grade      : 学年マスタ
	mtb_password_question : パスワード忘れ質問マスタ
	mtb_mailmagazine_category : メールマガジンカテゴリマスタ
	mtb_media             : 認知媒体マスタ
	mtb_age               : 年代マスタ
	
	mtb_bar_association   : 弁護士会マスタ（日弁連）
	*/
	//----------------------------------------------
	function get_mtb_list($table_name = '') {
		//引数確認
		if($table_name == ''){
			return [];
		}
		
		// SQL生成
		$sql  = '';
		$sql .= "SELECT id ,name ";
		$sql .= "  FROM {$table_name} ";
		
		if($table_name == 'mtb_bar_association'){
			$sql  = '';
			$sql .= "SELECT  id ";
			$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
			$sql .= "       ,name AS old_name ";
			$sql .= " FROM {$table_name} ";
			$sql .= " WHERE 1=1 ";
			
			$login_teacher_bar_association_id = $this->libauth->get_bar_association_id();
			
			if($login_teacher_bar_association_id > 1){
				$sql .= "   AND id = ".$login_teacher_bar_association_id." ";
			}
			$sql .= " ORDER BY `rank` ASC ";
		}else{
			$sql .= " ORDER BY id ASC ";
		}
		
		// SQL実行
		$query = $this->db->query($sql, array());
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	
	//** 日弁連対応 **//
	//----------------------------------------------
	// 代替倫理研修権限の更新処理
	// [2012/11/01]eLM API対応、API失敗時はロールバックするように変更
	//----------------------------------------------
	function edit_sub_auth_ethic_training($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id'              => 0,
							'lawyer_number'           => '',
							'sub_auth_ethic_training' => '0',
						),
						$param
					);

		//SQL生成
		$sql = "UPDATE student 
				   SET student.sub_auth_ethic_training = ?, 
					   student.update_at               = ? 
				 WHERE student.student_id    = ? 
				   AND student.lawyer_number = ? ";
		
		$this->db->trans_begin();
		$this->db->query($sql, array(
							$param['sub_auth_ethic_training'],
							date('Y/m/d H:i:s'),
							$param['student_id'],
							$param['lawyer_number'],
						));
		$this->db->trans_commit();
		
		return true;
	}


	//----------------------------------------------
	//受講者検索結果一覧取得
	//----------------------------------------------
	function csv_get_student_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id'                   => 0,
							's_name'                        => '',
							's_lawyer_number'               => '',
							's_email'                       => '',
							's_bar_association'             => '',
							's_free_word'                   => '',
							's_sub_auth_ethic_training_on'  => '',
							's_sub_auth_ethic_training_off' => '',
							'offset'                        => '',
							'rowcount'                      => '',
							'order_by'                      => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_student_selectsql($param);
		
		// SQL文の部分置換（パスポート有無、代替倫理研修権限を文字列化、出力項目順番の変更）
		$sql = preg_replace("/\t/u", " ", $sql);
		$sql = preg_replace("/[\r\n]/u", " ", $sql);
		$convert_sql = "SELECT  student.lawyer_number ,student.student_name ,student.student_email ,(CASE WHEN student.presence_passport = '1'       THEN '有'   ELSE '無' END) AS presence_passport ,(CASE WHEN student.sub_auth_ethic_training = '1' THEN '許可' ELSE '禁止' END) AS sub_auth_ethic_training ,(CASE WHEN mtb_bar_association.name IS NULL      THEN ''     ELSE mtb_bar_association.name END) AS bar_association_name FROM";
		$sql = preg_replace("/SELECT.+FROM/u", $convert_sql, $sql, 1);
	  //print $sql;
	  //exit();
	  //$sql_limit  = " LIMIT
	  //				 {$param['offset']}, {$param['rowcount']}";
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}


	//----------------------------------------------
	// 弁護士会IDから、弁護士会名の取得
	//----------------------------------------------
	function get_bar_association_name($bar_association_id = '') {
		//引数確認
		if($bar_association_id == ''){
			return '';
		}
		
		// SQL生成
		$sql  = '';
		$sql .= "SELECT  id ";
		$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
		$sql .= "       ,name AS old_name ";
	  //$sql .= "SELECT id ,name ";
		$sql .= "  FROM mtb_bar_association ";
		$sql .= " WHERE id = ".$bar_association_id." ";
		
		// SQL実行
		$query = $this->db->query($sql, array());
		
		//データリターン
		if ($query->num_rows() > 0) {
			$temp = $query->row_array();
			return $temp['name'];
		} else {
			return '';
		}
	}


	
	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の受講者を取得（講座が複数）
	//----------------------------------------------
	function get_cource_student_array($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'         => 0,
							'cource_id'         => '',
							'student_group_id'  => '',
							'free_word'         => '',
							'cource_flag'       => 0,
							'bar_association_id' => 0,
						),
						$param
					);

		//SQL生成
		$sql  = "";
		$sql .= "SELECT student.student_id, student.student_name, student.student_email ";
		$sql .= "  FROM student ";
		$sql .= " WHERE student.status = 0 ";
		$sql .= "   AND student.school_id = {$this->db->escape($param['school_id'])} ";

/*
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql .= " AND student.student_id IN (SELECT student_lecture.student_id FROM student_lecture WHERE student_lecture.cource_id IN (".$cource_list.")) ";
		}

		// ログイン中講師の受講者グループ
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		//  $this->load->model('model_teacher');
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql .= " AND student.student_id IN (SELECT student_id  FROM rel_student_group WHERE student_group_id IN (".$student_group_list."))";
		}

//		if (isset($param['cource_flag']) && $param['cource_flag'] != 0 && $param['cource_id'] != '' )  {
//			$temp1 = explode('-',$param['cource_id']);  // ハイフン繋ぎを配列変換
//			$temp2 = implode(',', $temp1);  // 配列をカンマ区切に変換
//			$sql .= "   AND student.student_id IN (SELECT student_lecture.student_id FROM student_lecture WHERE student_lecture.cource_id IN (".$temp2.") ) ";
//		}
		if (isset($param['cource_flag']) && $param['cource_flag'] != 0 )  {
			if ($param['cource_id'] != '' ) {
				$temp1 = explode('-',$param['cource_id']);  // ハイフン繋ぎを配列変換
				$temp2 = implode(',', $temp1);  // 配列をカンマ区切に変換
				$sql .= "   AND student.student_id IN (SELECT student_lecture.student_id FROM student_lecture WHERE student_lecture.cource_id IN (".$temp2.") ) ";
			}

			if ($param['student_group_id'] != '' ) {
				$temp1 = explode('-',$param['student_group_id']);  // ハイフン繋ぎを配列変換
				$temp2 = implode(',', $temp1);  // 配列をカンマ区切に変換
				$sql .= "   AND student.student_id IN (SELECT rel_student_group.student_id FROM rel_student_group WHERE rel_student_group.student_group_id IN (".$temp2.") ) ";
			}
		}
*/

		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        student.student_name  LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR student.student_note  LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR student.student_email LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR student.student_send_email LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		if($param['bar_association_id'] > 1){
			$sql .= ' AND student.bar_association_id = '.$this->db->escape($param['bar_association_id']);
		}
		
		$sql .= " ORDER BY student.student_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

}
?>
