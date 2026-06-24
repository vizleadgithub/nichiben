<?php
#[AllowDynamicProperties]
class Model_student_sub_auth extends CI_Model
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
//		$sql_limit  = " LIMIT
//						 {$param['offset']}, {$param['rowcount']}";
		$sql_limit  = "";
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
							 student_id
							,student_name
							,student_email
							,lawyer_number
							,presence_passport
							,sub_auth_ethic_training
							,bar_association_id
						FROM
							student";
		$sql_where = " WHERE
						status = 0";
		$sql_order = " ORDER BY
						lawyer_number ASC";
		
		
		if($param['order_by'] != 0){
			$sql_order = " ORDER BY
							lawyer_number DESC";
		}
		
		//学校ID
		$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
		
		//受講者氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND student_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		
		// 登録番号
		if (isset($param['s_lawyer_number']) && $param['s_lawyer_number'] != '' ) {
			$sql_where .= " AND lawyer_number = {$this->db->escape($param['s_lawyer_number'])}";
		}
		
		//メールアドレス	//*** 法学館対応：携帯メールアドレスも検索対象 ***/
		if (isset($param['s_email']) && $param['s_email'] != '') {
		//	$sql_where .= " AND student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'";
			$sql_where .= " AND (student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
								OR student_email_mobile LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
			)";
		}
		
		// 弁護士会ID（所属弁護士会）
		if (isset($param['s_bar_association']) && $param['s_bar_association'] != '') {
			$sql_where .= " AND bar_association_id = {$this->db->escape($param['s_bar_association'])}";
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
			$sql_where .= " AND (student_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student_note LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student_name_kana LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR address1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR address2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR address3 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR address_overseas1 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR address_overseas2 LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR school_name LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR student_no LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}

		// 代替権限
		if (isset($param['s_sub_auth_ethic_training_on']) && $param['s_sub_auth_ethic_training_on'] == '1') {
			$sql_where .= " AND sub_auth_ethic_training = 1 ";
		}
		if (isset($param['s_sub_auth_ethic_training_off']) && $param['s_sub_auth_ethic_training_off'] == '1') {
			$sql_where .= " AND sub_auth_ethic_training = 0 ";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//一件取得
	// 法学館対応：生年月日が任意になるための対応
	//----------------------------------------------
	function get_student($param){
		//引数設定
		$param = array_merge(
						array(
							'student_id' => 0,
						),
						$param
					);

		// SQL作成
		$sql  = '';
		$sql .= ' SELECT  student_id ';
		$sql .= '        ,student_name ';
		$sql .= '        ,student_email ';
		$sql .= '        ,student_password_encrypt ';
	//	$sql .= "        ,DATE_FORMAT(student_birthday,'%Y/%m/%d') as student_birthday ";
		$sql .= "        ,IF(student_birthday = '0000-00-00', '', DATE_FORMAT(student_birthday, '%Y/%m/%d')) AS student_birthday ";
		$sql .= '        ,student_note ';
		$sql .= '        ,school_id ';
		$sql .= '        ,status ';
		$sql .= '        ,update_at ';

		$sql .= '        ,lawyer_number ';												// 弁護士番号
		$sql .= '        ,bar_association_id ';										// 弁護士会ID
		$sql .= "        ,IF(regist_date = '0000-00-00', '', DATE_FORMAT(regist_date, '%Y/%m/%d')) AS regist_date ";	// 登録年
		$sql .= '        ,target_passport ';											// 対象パスポート
		$sql .= "        ,IF(presence_passport=0, '無', '有') AS presence_passport ";	// パスポートの有無 0:なし、1:あり
		$sql .= '        ,ethic_training ';												// 倫理研修 0:禁止、1:許可
		$sql .= '        ,sub_auth_ethic_training ';									// 代替倫理研修権限 0:なし、1:あり
		
		$sql .= "        ,IF(regist_at = '0000-00-00 00:00:00', '', DATE_FORMAT(regist_at, '%Y/%m/%d %H:%i:%s')) AS regist_at ";	// 入会日時（'0000-00-00 00:00:00' は '' に変換）
		$sql .= "        ,IF(delete_at = '0000-00-00 00:00:00', '', DATE_FORMAT(delete_at, '%Y/%m/%d %H:%i:%s')) AS delete_at ";	// 退会日時（'0000-00-00 00:00:00' は '' に変換）
		$sql .= '        ,student_name_kana ';		// 生徒氏名カナ
		$sql .= '        ,sex ';					// 性別（mtb_gender）
		$sql .= '        ,country_type ';			// 国種別（mtb_country_type）
		$sql .= '        ,zip ';					// 郵便番号
		$sql .= '        ,pref ';					// 都道府県ID（mtb_pref）
		$sql .= '        ,address1 ';				// 市区町村
		$sql .= '        ,address2 ';				// 番地
		$sql .= '        ,address3 ';				// 建物名
		$sql .= '        ,zip_overseas ';			// 海外郵便番号
		$sql .= '        ,address_overseas1 ';		// 海外住所・住所1
		$sql .= '        ,address_overseas2 ';		// 海外住所・住所2
		$sql .= '        ,member_type ';			// 会員属性（1:通常 2:月額課金）
		$sql .= '        ,tel1 ';					// 電話番号1
		$sql .= '        ,tel2 ';					// 電話番号2
		$sql .= '        ,tel3 ';					// 電話番号3
		$sql .= '        ,fax1 ';					// FAX番号1
		$sql .= '        ,fax2 ';					// FAX番号2
		$sql .= '        ,fax3 ';					// FAX番号3
		$sql .= '        ,student_email_mobile ';	// 携帯メールアドレス
		$sql .= '        ,job ';					// 職業ID（mtb_job）
		$sql .= '        ,job_type ';				// 業種ID（mtb_job_type）
		$sql .= '        ,school_name ';			// 学校名
		$sql .= '        ,school_grade ';			// 学年（mtb_school_grade）
		$sql .= '        ,password_question ';		// パスワード確認質問（mtb_password_question）
		$sql .= '        ,password_answer ';		// パスワード確認回答
		$sql .= '        ,mailmagazine_flg ';		// 0:メルマガ拒否 1:メルマガ許可（初期値0）
		$sql .= '        ,mailmagazine_ids ';		// メルマガID(複数時はカンマ区切りで入れる)
		$sql .= '        ,media_id ';				// 認知媒体ID（mtb_media）
		$sql .= '        ,age ';					// 年代（mtb_age）
		$sql .= '        ,student_no ';				// 伊藤塾塾生番号
		$sql .= "        ,IF(user_last_update_date = '0000-00-00 00:00:00', '', DATE_FORMAT(user_last_update_date, '%Y/%m/%d %H:%i:%s')) AS user_last_update_date ";	// 最終更新日（'0000-00-00 00:00:00' は '' に変換）
		$sql .= '   FROM  student ';
		$sql .= '  WHERE  student_id = ? ';
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
			$sql .= " WHERE 1=1 ";
			
			$login_teacher_bar_association_id = $this->libauth->get_bar_association_id();
			
			if($login_teacher_bar_association_id > 1){
				$sql .= "   AND id = ".$login_teacher_bar_association_id." ";
			}
			$sql .= " ORDER BY rank ASC ";
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
	
	//----------------------------------------------
	// 日弁連
	// [Ajax用] 代替倫理研修権限 の一括変更
	//----------------------------------------------
	function change_sub_auth($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'			=> 0,
							'select_student_id'	=> "",
							'change_kinds'		=> "",
						),
						$param
					);
		
		$param['select_student_id'] = str_replace("-", ",", $param['select_student_id']);
		
		//SQL生成
		$sql  = "";
		$sql .= "UPDATE student "; 
		$sql .= "   SET update_at = ? ";
		if($param['change_kinds'] == "ON"){
			$sql .= "     , sub_auth_ethic_training = 1 "; 
		}else{
			$sql .= "     , sub_auth_ethic_training = 0 "; 
		}
		$sql .= " WHERE school_id = ".$param['school_id']." "; 
		$sql .= "   AND student_id IN (".$param['select_student_id'].") "; 
		
		$this->db->trans_begin();
		$this->db->query($sql, array(
							date('Y-m-d H:i:s'),
						));
		$this->db->trans_commit();
		
		return true;
	}

	//----------------------------------------------
	// 日弁連
	// [CSVアップ用] 代替倫理研修権限 の一括変更
	//----------------------------------------------
	function csv_change_sub_auth($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'			=> 0,
							'select_lawyer_number'	=> "",
							'change_kinds'		=> "",
						),
						$param
					);
		// 登録番号の区切り文字置換（- から ,）
		$param['select_lawyer_number'] = "'".str_replace("-", "','", $param['select_lawyer_number'])."'";
		
		//SQL生成（更新）
		$sql  = "";
		$sql .= "UPDATE student "; 
		$sql .= "   SET update_at = ? ";
		if($param['change_kinds'] == "ON"){
			$sql .= "     , sub_auth_ethic_training = 1 "; 
		}else{
			$sql .= "     , sub_auth_ethic_training = 0 "; 
		}
		$sql .= " WHERE school_id = ".$param['school_id']." "; 
		$sql .= "   AND lawyer_number IN ( ".$param['select_lawyer_number']." ) "; 
		
		$this->db->trans_begin();
		$this->db->query($sql, array(
							date('Y-m-d H:i:s'),
						));
		$this->db->trans_commit();
		
		// SQL生成（取得）
/*
SELECT  student_id 
       ,student_name 
       ,student_email 
       ,lawyer_number 
       ,(CASE WHEN presence_passport=1 THEN '○' ELSE '×' END) AS presence_passport 
       ,(CASE WHEN sub_auth_ethic_training=1 THEN '○' ELSE '×' END) AS sub_auth_ethic_training 
       ,(SELECT mtb_bar_association.name from mtb_bar_association where id = student.bar_association_id) as bar_association
  FROM  student 

*/
		$sql  = '';
		$sql .= "SELECT  student_id ";
		$sql .= "       ,student_name ";
		$sql .= "       ,student_email ";
		$sql .= "       ,lawyer_number ";
		$sql .= "       ,(CASE WHEN presence_passport=1 THEN '○' ELSE '－' END) AS presence_passport ";
		$sql .= "       ,(CASE WHEN sub_auth_ethic_training=1 THEN '○' ELSE '－' END) AS sub_auth_ethic_training ";
		$sql .= "       ,(SELECT mtb_bar_association.name from mtb_bar_association where id = student.bar_association_id) as bar_association ";
		$sql .= "  FROM  student ";
		$sql .= " WHERE  school_id = ".$param['school_id']." "; 
		$sql .= "   AND  lawyer_number IN ( ".$param['select_lawyer_number']." ) "; 
		$sql .= " ORDER  BY student.lawyer_number ASC ";
		
		// SQL実行
		$query = $this->db->query($sql, array(
							));
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

}
?>
