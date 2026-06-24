<?php
#[AllowDynamicProperties]
class Model_data_import_test extends CI_Model
{
	//----------------------------------------------
	// コンテンツの最大登録数(max 10)
	//----------------------------------------------
	// private $_max_contents = 10;
	private $_max_contents = 25;

	//----------------------------------------------
	// 各コンテンツダウンロードの最大登録数(max 10)
	//----------------------------------------------
	private $_max_contents_download = 10;

	//----------------------------------------------
	// 新旧カテゴリ対比表
	//----------------------------------------------
	private $_term_convert = array(
		"0" => "400",
		"1" => "405",
		"2" => "413",
		"3" => "421",
		"4" => "426",
		"5" => "431",
		"6" => "439",
		"7" => "444",
		"8" => "449",
		"9" => "452",
		"10" => "459",
		"11" => "465",
		"12" => "471",
		"13" => "477",
		"14" => "483",
		"15" => "487",
		"16" => "492",
		"17" => "459,464",
		"18" => NULL,
		"19" => "499",
		"20" => "458",
		"21" => "435",
		"22" => "520",
	);

	//----------------------------------------------
	// 新旧研修種別対比表
	//----------------------------------------------
	private $_training_kind_convert = array(
		"0" => "1",
		"1" => "2",
		"2" => "3",
		"3" => "4",
		"4" => "5",
		"5" => "0",
	);

	//----------------------------------------------
	// 新旧受講対象フラグ対比表
	//----------------------------------------------
	private $_target_flg_convert = array(
		"0" => "1",
		"1" => "2",
		"2" => "3",
	);

	//----------------------------------------------
	// 新旧弁護士会ID対比表
	//----------------------------------------------
	private $_bar_association_id_convert = array(
		"0" => "1",
		"1" => "2",
		"2" => "3",
		"3" => "4",
		"4" => "6",
		"5" => "7",
		"6" => "8",
		"7" => "9",
		"8" => "10",
		"9" => "11",
		"10" => "12",
		"11" => "13",
		"12" => "14",
		"13" => "15",
		"14" => "16",
		"15" => "17",
		"16" => "18",
		"17" => "19",
		"18" => "20",
		"19" => "21",
		"20" => "22",
		"21" => "23",
		"22" => "24",
		"23" => "25",
		"24" => "26",
		"25" => "27",
		"26" => "28",
		"27" => "29",
		"28" => "30",
		"29" => "31",
		"30" => "32",
		"31" => "33",
		"32" => "34",
		"33" => "35",
		"34" => "36",
		"35" => "37",
		"36" => "38",
		"37" => "39",
		"38" => "40",
		"39" => "41",
		"40" => "42",
		"41" => "43",
		"42" => "44",
		"43" => "45",
		"44" => "46",
		"45" => "47",
		"46" => "48",
		"47" => "49",
		"48" => "50",
		"49" => "51",
		"50" => "52",
		"51" => "53",
		"52" => "54",
		"61" => "2,3,4,6,7,8,9,10,11,12,13,14,15",
		"62" => "16,17,18,19,20,21",
		"63" => "22,23,24,25,26,27",
		"64" => "28,29,30,31,32",
		"65" => "33,34,35,36,37,38,39,40",
		"66" => "41,42,43,44,45,46",
		"67" => "47,48,49,50",
		"68" => "51,52,53,54",
		"69" => "1",
		"70" => "6",
		"71" => "6",
		"72" => "6",
		"73" => "7",
		"74" => "7",
		"75" => "7",
		"76" => "12",
		"77" => "12",
		"78" => "14",
		"79" => "18",
		"80" => "28",
		"81" => "29",
		"82" => "29",
		"83" => "29",
		"84" => "29",
		"85" => "31",
		"86" => "33",
		"87" => "33",
		"88" => "42",
		"89" => "31",
		"90" => "12",
		"99" => "1",
	);

	//----------------------------------------------
	// 新弁護士会IDと弁護士会支部IDの紐づき
	//----------------------------------------------
	private $_bar_association_branch_id_convert = array(
		"1" => "1",
		"2" => "2",
		"3" => "3",
		"4" => "4",
		"6" => "6",
		"7" => "6",
		"8" => "6",
		"9" => "6",
		"10" => "6",
		"11" => "7",
		"12" => "7",
		"13" => "7",
		"14" => "7",
		"15" => "8",
		"16" => "8",
		"17" => "8",
		"18" => "9",
		"19" => "9",
		"20" => "9",
		"21" => "9",
		"22" => "10",
		"23" => "11",
		"24" => "11",
		"25" => "11",
		"26" => "12",
		"27" => "12",
		"28" => "12",
		"29" => "13",
		"30" => "14",
		"31" => "14",
		"32" => "14",
		"33" => "14",
		"34" => "14",
		"35" => "15",
		"36" => "16",
		"37" => "16",
		"38" => "16",
		"39" => "17",
		"40" => "18",
		"41" => "18",
		"42" => "18",
		"43" => "18",
		"44" => "19",
		"45" => "20",
		"46" => "21",
		"47" => "22",
		"48" => "22",
		"49" => "22",
		"50" => "22",
		"51" => "22",
		"52" => "23",
		"53" => "23",
		"54" => "24",
		"55" => "25",
		"56" => "26",
		"57" => "27",
		"58" => "27",
		"59" => "28",
		"60" => "28",
		"61" => "28",
		"62" => "28",
		"63" => "28",
		"64" => "29",
		"65" => "29",
		"66" => "29",
		"67" => "29",
		"68" => "29",
		"69" => "29",
		"70" => "30",
		"71" => "30",
		"72" => "30",
		"73" => "31",
		"74" => "31",
		"75" => "32",
		"76" => "33",
		"77" => "33",
		"78" => "33",
		"79" => "33",
		"80" => "33",
		"81" => "34",
		"82" => "34",
		"83" => "35",
		"84" => "35",
		"85" => "36",
		"86" => "37",
		"87" => "37",
		"88" => "38",
		"89" => "39",
		"90" => "40",
		"91" => "41",
		"92" => "42",
		"93" => "42",
		"94" => "42",
		"95" => "42",
		"96" => "42",
		"97" => "42",
		"98" => "42",
		"99" => "43",
		"100" => "44",
		"101" => "45",
		"102" => "46",
		"103" => "46",
		"104" => "46",
		"105" => "46",
		"106" => "47",
		"107" => "47",
		"108" => "47",
		"109" => "47",
		"110" => "48",
		"111" => "49",
		"112" => "50",
		"113" => "50",
		"114" => "51",
		"115" => "51",
		"116" => "52",
		"117" => "53",
		"118" => "54",
	);

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		// DB接続
		$this->load->database();

		// 日付＆CSVヘルパーの読込
		$this->load->helper('date');
		$this->load->helper('CsvParser');

		// 講師ID
		// 一旦、これで
		$this->_teacher_id = 1;
	}

	//----------------------------------------------
	// テーブルデータ 全削除
	//----------------------------------------------
	function import_delete() {
		echo "----------------------------------------------------------------------------------\n";

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		// 商品
		$this->db->query("TRUNCATE TABLE tbl_product_import");
		$this->db->query("TRUNCATE TABLE tbl_product_add_import");
		$this->db->query("TRUNCATE TABLE tbl_product_elearning_import");
		$this->db->query("TRUNCATE TABLE tbl_product_live_training_import");
		$this->db->query("TRUNCATE TABLE tbl_product_ethic_training_import");
		$this->db->query("TRUNCATE TABLE tbl_product_passport_import");

		// 商品＆弁護士会とのリレーション
		$this->db->query("TRUNCATE TABLE rel_product_bar_association_import");
		$this->db->query("TRUNCATE TABLE rel_product_bar_association_branch_import");

		// 動画
		$this->db->query("TRUNCATE TABLE video_import");
		$this->db->query("TRUNCATE TABLE video_alfstream_status_import");
		$this->db->query("TRUNCATE TABLE video_lecture_import");
		$this->db->query("TRUNCATE TABLE video_search_index_import");
		$this->db->query("TRUNCATE TABLE video_alfstream_reading_history_import");

		// 論理研修
		$this->db->query("TRUNCATE TABLE tbl_ethic_group_import");
		$this->db->query("TRUNCATE TABLE tbl_ethic_question_import");
		$this->db->query("TRUNCATE TABLE tbl_ethic_branch_import");
		$this->db->query("TRUNCATE TABLE tbl_ethic_question_history_import");

		// 視聴履歴
		$this->db->query("TRUNCATE TABLE tbl_bookmark_import");
		$this->db->query("TRUNCATE TABLE report_user_video_viewed_import");

		// 購入履歴
		$this->db->query("TRUNCATE TABLE tbl_order_import");
		$this->db->query("TRUNCATE TABLE tbl_order_detail_import");
		$this->db->query("TRUNCATE TABLE tbl_order_no_import");
		$this->db->query("TRUNCATE TABLE tbl_order_temp_import");

		// ユーザ
		// $this->db->query("TRUNCATE TABLE student_import");

		// お気に入り登録
		$this->db->query("TRUNCATE TABLE tbl_favorite_import");

		// メールマガジン
		$this->db->query("TRUNCATE TABLE tbl_mailmagazine_import");
		$this->db->query("TRUNCATE TABLE tbl_mailmagazine_sended_import");

		// その他
		$this->db->query("TRUNCATE TABLE tbl_status_change_ethic_import");
		$this->db->query("TRUNCATE TABLE tbl_status_change_payment_import");

		// DB取込用ID変換履歴テーブル
		// $this->db->query("TRUNCATE TABLE convert_id_list");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_elearning'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_kenshu'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_ethic_training'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_elearning'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'ethic_question'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_tmoral'");
		$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_tmoral_exam_id'");
		// $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'student'");
		// $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'bar_association_student'");
		// $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'teacher'");
		// $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'bar_association'");
		// $this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'order'");

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// ユーザーデータ インポート
	//----------------------------------------------
	function import_ms_user($file_path) {
		// 登録データの生成（初期値）
		$param = array(
			'token.id'		=> '',
			'uid'			=> '',
			'mail'			=> '',			// メールアドレス
			'userpassword'		=> '',			// パスワード（SSOのパスワードのため、暗号化されている可能性大。そのまま使用するかどうかは要検討）
			'sn'			=> '',			// 氏
			'sn_ka'			=> '',			// 氏（カナ）
			'destinationindicator'	=> '',
			'cn'			=> '',			// 名
			'cn_ka'			=> '',			// 名（カナ）
			'inetuserstatus'	=> '',
			'dn'			=> '',
			'objectclass'		=> '',
			'employeenumber'	=> '10000',		// 登録番号（弁護士番号）lawyer_number
			'lawyer_division'	=> '0',			// 会員区分 lawyer_division
			'bar_association'	=> '14',		// 所属弁護士会（SSO）
			'regist_date'		=> '2010-08-01',	// 登録年月日
			'update_date'		=> 'now()',		// 更新年月日
			'presence_passport'	=> '0',			// パスポートの有無（有⇒1、無⇒0）
			'exp_date_passport'	=> '0000-00-00',	// パスポートの有効期限
			'target_passport'	=> '',			// パスポート料金（下記で取得）
			'mailmagazine_flg'	=> '0',			// メールマガジンの可否（メルマガ許可⇒1、メルマガ拒否⇒0）
			'bar_association_id'	=> '',			// 弁護士会ID（下記で取得）
			'status'		=> '0',			// 有効
			'sub_auth_ethic_training' => '0',		// 代替倫理研修権限（許可 ⇒1、禁止 ⇒0）
			'learning_school_id'	=> $this->config->item('nichibenren_school_id'), // 内部処理用学校ID
			'learning_cource_id'	=> $this->config->item('nichibenren_cource_id'), // 内部処理用講座ID
			'learning_password'	=> $this->config->item('nichibenren_student_password'), // 内部処理用パスワード
		);

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		// echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		$line_2 = '';
		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			// 24項目未満はスキップ
			// if (count($values) < 24) { continue; }

			// 有効、氏、名、登録番号（弁護士番号）、弁護士会IDは必須
			// if (strlen($values[4]) == 0 || strlen($values[6]) == 0 || strlen($values[17]) == 0 || strlen($values[18]) == 0 || strlen($values[19]) == 0) { continue; }

			// すでにstudentマスタに存在するかどうかのチェック（lawyer_numberで）
			$query = $this->db->query(
				// ' SELECT * FROM student_import'.
				' SELECT * FROM student'.
				' WHERE lawyer_number = ?'.
				// ' AND lawyer_division = ?'.
				' AND status = 0',
				array(
					$values[6],
					// $values[14],
				)
			);

			// 登録データ生成
			$param = array_merge(
				$param,
				array(
					'mail'			=> $values[22],  // メールアドレス
					'sn'			=> $values[18],  // 氏
					'sn_ka'			=> $values[20],  // 氏（カナ）
					'cn'			=> $values[19],  // 名
					'cn_ka'			=> $values[21],	 // 名（カナ）
					'employeenumber'	=> $values[6], 	 // 登録番号（弁護士番号）lawyer_number
					'lawyer_division'	=> $values[14],	 // 会員区分 lawyer_division
					'bar_association'	=> $values[17],	 // 所属弁護士会（SSO）
					'regist_date'		=> $values[1],	 // 登録年月日
					'update_date'		=> $values[5],	 // 更新年月日
					'presence_passport'	=> '0',		 // パスポートの有無（有⇒1、無⇒0）
					'mailmagazine_flg'	=> $values[16] == 0 ? 1 : 0, 		// メールマガジンの可否（メルマガ許可⇒1、メルマガ拒否⇒0）
					'exp_date_passport'	=> '0000-00-00',  // パスポートの有効期限
					'status' 		=> $values[4],	  // 有効
					'sub_auth_ethic_training' => $valuse[13], // 代替倫理研修権限（許可 ⇒1、禁止 ⇒0）
				)
			);

			// 登録処理
			if ($query->num_rows() == 0) {
				// 弁護士会情報が存在するかチェック
				$query = $this->db->query(
					' SELECT * FROM mtb_bar_association'.
					' WHERE sso_id = ?',
					array(
						$param['bar_association'],
					)
				);
				if ($query->num_rows() > 0) {
					$rows = $query->result_array();
					$param['bar_association_id'] = $rows[0]['id'];

					// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得	
					$temp_param = $this->_get_target_passport($param['regist_date']);
					$param['presence_passport'] = $temp_param['presence_passport'];
					$param['exp_date_passport'] = $temp_param['exp_date_passport'];
					$param['target_passport']   = $temp_param['target_passport'];
					if ($param['target_passport'] != '') {
						/*
						print_r($param);
						echo "\n";
						*/
						$sql = "INSERT INTO student (
									 student_name
									,student_name_kana
									,student_email
									,student_password_encrypt
									,school_id
									,status
									,regist_at
									,update_at
									,mailmagazine_flg
									,lawyer_number
									,lawyer_division
									,bar_association_id
									,regist_date
									,target_passport
									,presence_passport
									,exp_date_passport
									,sub_auth_ethic_training
								) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

						$this->db->trans_begin();

						// INSERT student
						$this->db->query($sql, array(
											$param['sn']."　".$param['cn'],
											$param['sn_ka']."　".$param['cn_ka'],
											$param['mail'],
											hash('sha256',$param['learning_password']),
											$param['learning_school_id'],
											$param['status'],
											$param['regist_date'],
											$param['update_date'],
											$param['mailmagazine_flg'],
											$param['employeenumber'],
											$param['lawyer_division'],
											$param['bar_association_id'],
											$param['regist_date'],
											$param['target_passport'],
											$param['presence_passport'],
											$param['exp_date_passport'],
											$param['sub_auth_ethic_training'],
										));

						// 登録したstudent_idを取得
						$prev_id = $this->db->insert_id();

						// DELETE student_lecture
						$this->db->query("DELETE FROM student_lecture WHERE student_id = ?", array(
											$prev_id
										));

						// INSERT student_lecture
						$this->db->query("INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?)", array(
											$prev_id,
											$param['learning_cource_id'],
											date('Y-m-d H:i:s'),
										));

						$flg = $this->db->trans_commit();

						if ($flg) {
							$regist_cnt++;

							// LOGの書き出し
							$this->_output_log(__FUNCTION__.".log", "[ok:insert] student_id:".$prev_id);
						}
					}
				}
			}

			// 更新処理
			else {
				// 更新するstudent_id
				$rows = $query->result_array();
				$prev_id = $rows[0]['student_id'];

				// 弁護士会情報が存在するかチェック
				$query = $this->db->query(
					' SELECT * FROM mtb_bar_association'.
					' WHERE sso_id = ?',
					array(
						$param['bar_association'],
					)
				);
				if ($query->num_rows() > 0) {
					$param['bar_association_id'] = $rows[0]['id'];

					// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得	
					$temp_param = $this->_get_target_passport($param['regist_date']);
					$param['presence_passport'] = $temp_param['presence_passport'];
					$param['exp_date_passport'] = $temp_param['exp_date_passport'];
					$param['target_passport']   = $temp_param['target_passport'];
					if ($param['target_passport'] != '') {
						/*
						print_r($param);
						echo "\n";
						*/
						$sql = "UPDATE student
								SET student_name             = ?,
									student_name_kana        = ?,
									student_email            = ?,
									student_password_encrypt = ?,
									school_id                = ?,
									status                   = ?,
									regist_at                = ?, 
									update_at                = ?, 
									mailmagazine_flg         = ?, 
									bar_association_id       = ?, 
									regist_date              = ?, 
									target_passport          = ?, 
									presence_passport        = ?, 
									exp_date_passport        = ?, 
									sub_auth_ethic_training  = ?
								WHERE lawyer_number = ?";

						$this->db->trans_begin();

						// UPDAET student
						$this->db->query($sql, array(
											$param['sn']."　".$param['cn'],
											$param['sn_ka']."　".$param['cn_ka'],
											$param['mail'],
											hash('sha256',$param['learning_password']),
											$param['learning_school_id'],
											$param['status'],
											$param['regist_date'],
											$param['update_date'],
											$param['mailmagazine_flg'],
											$param['bar_association_id'],
											$param['regist_date'],
											$param['target_passport'],
											$param['presence_passport'],
											$param['exp_date_passport'],
											$param['sub_auth_ethic_training'],
											$param['employeenumber'],
										));

						// DELETE student_lecture
						$this->db->query("DELETE FROM student_lecture WHERE student_id = ?", array(
											$prev_id
										));

						// INSERT student_lecture
						$this->db->query("INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?)", array(
											$prev_id,
											$param['learning_cource_id'],
											date('Y-m-d H:i:s'),
										));

						$flg = $this->db->trans_commit();

						if ($flg) {
							$update_cnt++;

							// LOGの書き出し
							$this->_output_log(__FUNCTION__.".log", "[ok:update] student_id:".$prev_id);
						}
					}
				}
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		// echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// eラーニング インポート
	//----------------------------------------------
	function import_ms_course($file_path, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("TRUNCATE TABLE tbl_product_import");
			$this->db->query("TRUNCATE TABLE tbl_product_add_import");
			$this->db->query("TRUNCATE TABLE tbl_product_elearning_import");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_elearning'");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_error_1.log");
		$this->_clear_log("_get_contents_download_error_1.log");
		$this->_clear_log("_get_contents_download_error_2.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 15項目未満はスキップ
			// if (count($values) < 15) { continue; }

			// シリアル、カテゴリ、商品CODE、商品名
			// if (strlen($values[0]) == 0 || strlen($values[8]) == 0 || strlen($values[11]) == 0 || strlen($values[12]) == 0) { continue; }

			// if ($values[11] != "E10002") { continue; }

			$param = array(
				"product_id" => $values[0],
				"product_type" => 2,
				"product_name" => $values[12],
				"product_code" => $values[11],
				"price" => $values[10],
				"term_id" => $values[8],
				"start_date" => $values[3],
				"end_date" => $values[4],
				"open_period" => "0",
				"memo" => str_replace("<br>", "\n", $values[14]),
				"play_time" => NULL,
				"regist_date" => $values[1],
				"update_date" => $values[2],
				"del_flg" => "0",
				"im_text_url" => $values[13],
			);

			for ($k = 1; $k <= $this->_max_contents; $k++) {
				$param["contents_free_time".$k] = "0";
			}

			// DEL_DATEが空で無かったらdel_flgを1にする
			if (strlen($param["update_date"]) > 0) {
				$param["del_flg"] = "1";
			}

			// DEL_DATEが空だったらupdate_dateをADD_DATEにする
			if (strlen($param["update_date"]) == 0) {
				$param["update_date"] = $param["regist_date"];
			}

			// 新旧カテゴリ対比表の変換
			$param["term_id"] = $this->_get_category_convert($param["term_id"]);
			/*
			if (strlen($param["term_id"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error] id:".$values[0]);
				continue;
			}
			*/

			// 資料DLページURLから情報を取得
			// _get_contents_download(商品コード,資料URL,index.htmlをWeb上から取得,contenstをWeb上から取得)
			$contents_download = array();
			if (strlen($param["product_code"]) > 0 && strlen($param["im_text_url"]) > 0) {
				$contents_download = $this->_get_contents_download($param["product_code"], $param["im_text_url"], FALSE, TRUE);
			}

			// コンテンツダウンロード設定
			if (count($contents_download) > 0) {
				$cnt = 0;
				for ($k = 1; $k <= $this->_max_contents; $k++) {
					for ($l = 1; $l <= $this->_max_contents_download; $l++) {
						if (isset($contents_download[$cnt]["contents_download"])) {
							$param["contents_download".$k."_".$l] = $contents_download[$cnt]["contents_download"];
							$param["contents_download_before".$k."_".$l] = $contents_download[$cnt]["contents_download_before"];
						}
						else {
							$param["contents_download".$k."_".$l] = NULL;
							$param["contents_download_before".$k."_".$l] = NULL;
						}

						$cnt++;

						// if ($cnt >= count($contents_download)) { break 2; }
					}
				}
			}

			print_r($param);
			echo "\n";

			// 一括コンテンツダウンロード設定
			$param2 = array(
				"product_type_add" => "1",
				"all_contents_download" => "",
				"all_contents_download_before" => "",
			);

			// e-ラーニング商品設定
			$param3 = array(
				"product_kind_flg" => "1",
				"live_training_product_id" => "0",
			);

			if ($values[6] == "0") {
				$param3["product_kind_flg"] = "1";
			}
			else {
				$param3["product_kind_flg"] = "2";
				if ($values[7] > 0) {
					$param3["live_training_product_id"] = $values[7] + 10000;
				}
			}

			// すでにtbl_product_importマスタに存在するかどうかのチェック
			$query = $this->db->query(
				' SELECT * FROM tbl_product_import'.
				' WHERE product_code = ?',
				array(
					$param["product_code"],
				)
			);

			$product_id = "";

			// 不要な変数の削除
			unset($param["im_text_url"]);

			// 登録処理
			if ($query->num_rows() == 0) {
				$res = $this->db->query($this->db->insert_string('tbl_product_import', $param));
				if ($res) {
					$product_id = $this->db->insert_id();
					$regist_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:insert] product_id:".$product_id);
				}
			}

			// 更新処理
			else {
				// 更新するproduct_id
				$rows = $query->result_array();
				$product_id = $rows[0]['product_id'];

				$res = $this->db->query($this->db->update_string('tbl_product_import', $param, 'product_id='.$product_id));
				if ($res) {
					$update_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:update] product_id:".$product_id);
				}
			}

			if ($res && $product_id) {
				// DELETE tbl_product_add
				$this->db->query("DELETE FROM tbl_product_add_import WHERE product_id = ?", array(
									$product_id
								));

				// INSERT tbl_product_add
				$this->db->query("INSERT INTO tbl_product_add_import (product_id, product_type_add, all_contents_download, all_contents_download_before) VALUES (?,?,?,?)", array(
									$product_id,
									$param2["product_type_add"],
									$param2["all_contents_download"],
									$param2["all_contents_download_before"],
								));

				// DELETE tbl_product_elearning
				$this->db->query("DELETE FROM tbl_product_elearning_import WHERE product_id = ?", array(
									$product_id
								));

				// INSERT tbl_product_elearning
				$this->db->query("INSERT INTO tbl_product_elearning_import (product_id, product_kind_flg, live_training_product_id, product_flg, search_word) VALUES (?,?,?,?,?)", array(
									$product_id,
									$param3['product_kind_flg'],
									$param3['live_training_product_id'],
									$param3['product_flg'],
									$param3['search_word'],
								));

				// convert_id_listへ
				$this->_convert_id_list("product_elearning", $values[0], $product_id);
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// 会場研修 インポート
	//----------------------------------------------
	function import_kenshu($file_path, $file_path_2, $file_path_3, $file_path_4, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("TRUNCATE TABLE tbl_product_live_training_import");
			$this->db->query("TRUNCATE TABLE rel_product_bar_association_import");
			$this->db->query("TRUNCATE TABLE rel_product_bar_association_branch_import");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_kenshu'");
		}

		$kenshu_owner1 = array();

		if (!$fpr = @fopen($file_path_2, FOPEN_READ)) {
			return array();
		}

		// echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			continue;

			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			// 2項目未満はスキップ
			// if (count($values) < 2) { continue; }

			// 研修ID、弁護士会ID
			// if (strlen($values[0]) == 0 || strlen($values[1]) == 0) { continue; }

			// 新旧受講対象フラグ対比表
			$bar_association_id = $this->_get_bar_association_id_convert($values[1]);
			if (!$bar_association_id) {
				// echo "no match:".$values[1]."\n";
				continue;
			}

			foreach ($bar_association_id as $k1 => $v1) {
				$check_flg = FALSE;
				foreach ($kenshu_owner1 as $k2 =>$v2) {
					if ($v1 == $v2["bar_association_id"] && $values[0] + 10000 == $v2["product_id"]) {
						$check_flg = TRUE;
						break;
					}
				}
				if (!$check_flg) {
					$pa = array();
					$pa["product_id"] = $values[0] + 10000;
					$pa["bar_association_id"] = $v1;
					$kenshu_owner1[] = $pa;
				}
			}
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		// echo "----------------------------------------------------------------------------------\n";

		$kenshu_owner2 = array();

		if (!$fpr = @fopen($file_path_3, FOPEN_READ)) {
			return array();
		}

		// echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$line_2 = '';
		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			continue;

			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			// 2項目未満はスキップ
			// if (count($values) < 2) { continue; }

			// 研修ID、弁護士会ID
			// if (strlen($values[0]) == 0 || strlen($values[1]) == 0) { continue; }

			// 新旧受講対象フラグ対比表
			$bar_association_id = $this->_get_bar_association_id_convert($values[1]);
			if (!$bar_association_id) {
				// echo "no match:".$values[1]."\n";
				continue;
			}

			foreach ($bar_association_id as $k1 => $v1) {
				$check_flg = FALSE;
				foreach ($kenshu_owner2 as $k2 =>$v2) {
					if ($v1 == $v2["bar_association_id"] && $values[0] + 10000 == $v2["product_id"]) {
						$check_flg = TRUE;
						break;
					}
				}
				if (!$check_flg) {
					$pa = array();
					$pa["product_id"] = $values[0] + 10000;
					$pa["bar_association_id"] = $v1;
					$kenshu_owner2[] = $pa;
				}
			}
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		// echo "----------------------------------------------------------------------------------\n";

		$kenshu_target = array();

		if (!$fpr = @fopen($file_path_4, FOPEN_READ)) {
			return array();
		}

		// echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$line_2 = '';
		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			continue;

			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			// 2項目未満はスキップ
			// if (count($values) < 2) { continue; }

			// 研修ID、弁護士会ID
			// if (strlen($values[0]) == 0 || strlen($values[1]) == 0) { continue; }

			// 新旧受講対象フラグ対比表
			$bar_association_id = $this->_get_bar_association_id_convert($values[1]);
			if (!$bar_association_id) {
				// echo "no match:".$values[1]."\n";
				continue;
			}

			foreach ($bar_association_id as $k1 => $v1) {
				$check_flg = FALSE;
				foreach ($kenshu_target as $k2 =>$v2) {
					if ($v1 == $v2["bar_association_id"] && $values[0] + 10000 == $v2["product_id"]) {
						$check_flg = TRUE;
						break;
					}
				}
				if (!$check_flg) {
					$pa = array();
					$pa["product_id"] = $values[0] + 10000;
					$pa["bar_association_id"] = $v1;
					$kenshu_target[] = $pa;
				}
			}
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		// echo "----------------------------------------------------------------------------------\n";

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_error_1.log");
		$this->_clear_log(__FUNCTION__."_error_2.log");
		$this->_clear_log(__FUNCTION__."_error_3.log");
		$this->_clear_log(__FUNCTION__."_error_4.log");
		$this->_clear_log(__FUNCTION__."_error_5.log");
		$this->_clear_log(__FUNCTION__."_error_6.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		$line_2 = '';
		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 29項目未満はスキップ
			// if (count($values) < 29) { continue; }

			// シリアル、研修名
			// if (strlen($values[0]) == 0 || strlen($values[19]) == 0) { continue; }

			// if ($values[0] != "113" && $values[0] != "117" && $values[0] != "121" && $values[0] != "123") { continue; }

			// if ($values[0] <= "113" || $values[0] >= "500") { continue; }

			// 親か子の判断（ROOT_IDが0以外は子）
			$parent_flg = TRUE;
			if ($values[13] != "0") {
				$parent_flg = FALSE;
			}

			// 子の場合
			if (!$parent_flg) {
				$param = array(
					"product_id" => $values[0] + 10000,
					"product_type" => 2,
					"product_name" => $values[19],
					"product_code" => "KAI".($values[0] + 10000),
					"price" => $values[26],
					"term_id" => "519", // 固定
					"start_date" => $values[7],
					"end_date" => $values[8],
					"memo" => str_replace("<br>", "\n", $values[21]),
					"teacher" => $values[20],
					"regist_date" => $values[2],
					"update_date" => $values[3],
					"del_flg" => "0",
				);

				// DEL_DATEが空で無かったらdel_flgを1にする
				if (strlen($param["update_date"]) > 0) {
					$param["del_flg"] = "1";
				}

				// DEL_DATEが空だったらupdate_dateをADD_DATEにする
				if (strlen($param["update_date"]) == 0) {
					$param["update_date"] = $param["regist_date"];
				}

				echo "【 子 】\n";
				print_r($param);
				echo "\n";

				// 一括コンテンツダウンロード設定
				$param2 = array(
					"product_type_add" => "2",
					"all_contents_download" => "",
					"all_contents_download_before" => "",
				);

				// 会場研修商品設定
				$param3 = array(
					"training_kind_flg" => $values[11],
					"ethic_flg" => $values[15],
					"app_flg" => "0",
					"limit_date" => $values[9],
					"memo1" => str_replace("<br>", "\n", $values[21]),
					"memo2" => str_replace("<br>", "\n", $values[22]),
					"memo3" => str_replace("<br>", "\n", $values[23]),
					"memo4" => str_replace("<br>", "\n", $values[27]),
					"memo5" => str_replace("<br>", "\n", $values[28]),
					"live_start_date" => $values[6],
					"download_flg" => "0",
					"target_flg" => $values[17],
					"sponsor" => "",
					"target" => "",
				);

				// 新旧研修種別対比表
				$param3["training_kind_flg"] = $this->_get_training_kind_convert($param3["training_kind_flg"]);
				if (strlen($param3["training_kind_flg"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_1.log", "[error] id:".$values[0]);
					continue;
				}

				// REG_DATEが空でなければ1
				if (strlen($values[4]) > 0) {
					$param3["app_flg"] = "1";
				}

				// memo2が空でなかったら講師名の前に改行を追加
				if (strlen($param3["memo2"]) > 0) {
					$param3["memo2"] .= "\n" ;
				}
				$param3["memo2"] .= $values[20];

				// 新旧受講対象フラグ対比表
				$param3["target_flg"] = $this->_get_target_flg_convert($param3["target_flg"]);
				if (strlen($param3["target_flg"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_2.log", "[error] id:".$values[0]);
					continue;
				}

				// FROM_IDから弁護士会IDを取得
				$bar_association_id = $this->_get_bar_association_id_convert($values[10]);
				if (!$bar_association_id) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_3.log", "[error] id:".$values[0]);
					continue;
				}

				// sponsor（kenshu_owner1）
				$owner1 = $bar_association_id;
				if ($owner1) {
					$param3["sponsor"] = "|".implode("|", $owner1)."|";
				}

				// sponsor（kenshu_owner2）
				$owner2 = array();

				// target（kenshu_target）
				$target = $bar_association_id;
				if ($target) {
					$param3["target"] = "|".implode("|", $target)."|";
				}

				// エラーログファイル生成
				if (strlen($param3["sponsor"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_5.log", "[error] id:".$values[0]);

					// 全弁護士会
					foreach ($_bar_association_id_convert as $k3 => $v3) {
						$v3 = explode("," , $v3);
						foreach ($v3 as $k4 => $v4) {
							if (!in_array($v4, $owner1)) {
								$owner1[] = $v4;
							}
						}
					}
					$param3["sponsor"] = "|".implode("|", $owner1)."|";
				}
				if (strlen($param3["target"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_6.log", "[error] id:".$values[0]);

					// 全弁護士会
					foreach ($_bar_association_id_convert as $k3 => $v3) {
						$v3 = explode("," , $v3);
						foreach ($v3 as $k4 => $v4) {
							if (!in_array($v4, $target)) {
								$target[] = $v4;
							}
						}
					}
					$param3["target"] = "|".implode("|", $target)."|";
				}

				// すでにtbl_productマスタに存在するかどうかのチェック
				$query = $this->db->query(
					' SELECT * FROM tbl_product_import'.
					' WHERE product_code = ?',
					array(
						$param["product_code"],
					)
				);

				$product_id = "";

				// 登録処理
				if ($query->num_rows() == 0) {
					$res = $this->db->query($this->db->insert_string('tbl_product_import', $param));
					if ($res) {
						$product_id = $this->db->insert_id();
						$regist_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:insert] product_id:".$product_id);
					}
				}

				// 更新処理
				else {
					// 更新するproduct_id
					$rows = $query->result_array();
					$product_id = $rows[0]['product_id'];

					$res = $this->db->query($this->db->update_string('tbl_product_import', $param, 'product_id='.$product_id));
					if ($res) {
						$update_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:update] product_id:".$product_id);
					}
				}

				if ($res && $product_id) {
					// DELETE tbl_product_add
					$this->db->query("DELETE FROM tbl_product_add_import WHERE product_id = ?", array(
										$product_id
									));

					// INSERT tbl_product_add
					$this->db->query("INSERT INTO tbl_product_add_import (product_id, product_type_add, all_contents_download, all_contents_download_before) VALUES (?,?,?,?)", array(
										$product_id,
										$param2["product_type_add"],
										$param2["all_contents_download"],
										$param2["all_contents_download_before"],
									));


					// DELETE tbl_product_live_training
					$this->db->query("DELETE FROM tbl_product_live_training_import WHERE product_id = ?", array(
										$product_id
									));

					// INSERT tbl_product_live_training
					$this->db->query("INSERT INTO tbl_product_live_training_import (product_id, training_kind_flg, ethic_flg, app_flg, limit_date, memo1, memo2, memo3, memo4, memo5, live_start_date, download_flg, target_flg, sponsor, target) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array(
										$product_id,
										$param3['training_kind_flg'],
										$param3['ethic_flg'],
										$param3['app_flg'],
										$param3['limit_date'],
										$param3['memo1'],
										$param3['memo2'],
										$param3['memo3'],
										$param3['memo4'],
										$param3['memo5'],
										$param3['live_start_date'],
										$param3['download_flg'],
										$param3['target_flg'],
										$param3['sponsor'],
										$param3['target'],
									));

					// DELETE tbl_product_live_training
					$this->db->query("DELETE FROM rel_product_bar_association_import WHERE product_id = ?", array(
										$product_id
									));

					// echo "owner1:\n";
					foreach ($owner1 as $k1 => $v1) {
						// 会場研修商品の主催1設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "1",
							"capacity" => $values[25],
							"hall" => str_replace("<br>", "\n", $values[24]),
							"receptionist_start_date" => $values[7],
							"receptionist_end_date" => $values[8],
							"contents" => str_replace("<br>", "\n", $values[29]),
							"update_at" => $values[2],
							"dates" => $values[14],
							"web_flg" => $values[16],
						);

						// WEBOFFERの0と1を逆にして入れる
						if ($param4["web_flg"] == 0) {
							$param4["web_flg"] = 1;
						}
						else {
							$param4["web_flg"] = 0;
						}

						// INSERT rel_product_bar_association
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// echo "owner2:\n";
					foreach ($owner2 as $k1 => $v1) {
						// 会場研修商品の主催2設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "1",
							"capacity" => $values[25],
							"hall" => str_replace("<br>", "\n", $values[24]),
							"receptionist_start_date" => $values[7],
							"receptionist_end_date" => $values[8],
							"contents" => str_replace("<br>", "\n", $values[29]),
							"update_at" => $values[2],
							"dates" => $values[14],
							"web_flg" => $values[16],
						);

						// WEBOFFERの0と1を逆にして入れる
						if ($param4["web_flg"] == 0) {
							$param4["web_flg"] = 1;
						}
						else {
							$param4["web_flg"] = 0;
						}

						// INSERT rel_product_bar_association
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// echo "target:\n";
					foreach ($target as $k1 => $v1) {
						// 会場研修商品の受講対象設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "3",
							"capacity" => "",
							"hall" => "",
							"receptionist_start_date" => "",
							"receptionist_end_date" => "",
							"contents" => "",
							"update_at" => "",
							"dates" => "",
							"web_flg" => "",
						);

						// INSERT rel_product_bar_association
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// DELETE rel_product_bar_association_branch
					$this->db->query("DELETE FROM rel_product_bar_association_branch_import WHERE product_id = ?", array(
										$product_id
									));

					// echo "owner1_branch:\n";
					foreach ($owner1 as $k1 => $v1) {
						// 新弁護士会IDと弁護士会支部IDの紐づき
						$bar_association_branch_id = $this->_get_bar_association_branch_id_convert(array($v1));
						foreach ($bar_association_branch_id as $k2 => $v2) {
							// 会場研修商品の受講対象設定
							$param4 = array(
								"bar_association_branch_id" => $v2,
								"capacity" => $values[25],
								"hall" => str_replace("<br>", "\n", $values[24]),
								"receptionist_start_date" => $values[7],
								"receptionist_end_date" => $values[8],
								"contents" => str_replace("<br>", "\n", $values[29]),
								"update_at" => $values[2],
								"dates" => $values[14],
								"web_flg" => $values[16],
							);

							// WEBOFFERの0と1を逆にして入れる
							if ($param4["web_flg"] == 0) {
								$param4["web_flg"] = 1;
							}
							else {
								$param4["web_flg"] = 0;
							}

							// INSERT rel_product_bar_association
							$this->db->query("INSERT INTO rel_product_bar_association_branch_import (product_id, bar_association_branch_id, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?)", array(
												$product_id,
												$param4['bar_association_branch_id'],
												$param4['capacity'],
												$param4['hall'],
												$param4['receptionist_start_date'],
												$param4['receptionist_end_date'],
												$param4['contents'],
												$param4['update_at'],
												$param4['dates'],
												$param4['web_flg'],
											));
							// print_r($param4);
							// echo "\n";
						}
					}

					// convert_id_listへ
					$this->_convert_id_list("product_kenshu", $values[0], $product_id);
				}

				/*
				// 親がtbl_product_importマスタに存在するかどうかのチェック
				$query = $this->db->query(
					' SELECT * FROM tbl_product_import'.
					' WHERE product_code = ?',
					array(
						"KAI".($values[13] + 10000),
					)
				);
				if ($query->num_rows() == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_3.log", "[error] id:".$values[0]);
					continue;
				}

				// 更新するproduct_id
				$rows = $query->result_array();
				$product_id = $rows[0]['product_id'];

				// FROM_IDから弁護士会IDを取得
				$bar_association_id = $this->_get_bar_association_id_convert($values[10]);
				if (!$bar_association_id) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_4.log", "[error] id:".$values[0]);
					continue;
				}

				echo "【 子 】\n";
				echo "product_id:".$values[0]."\n";
				echo "product_id(parent):".$product_id."\n";
				echo "bar_association_id:\n";
				print_r($bar_association_id);
				echo "\n";

				// 新弁護士会IDと弁護士会支部IDの紐づき
				$bar_association_branch_id = $this->_get_bar_association_branch_id_convert($bar_association_id);
				foreach ($bar_association_branch_id as $k2 => $v2) {
					// 会場研修商品の受講対象設定
					$param4 = array(
						"bar_association_branch_id" => $v2,
						"capacity" => $values[25],
						"hall" => str_replace("<br>", "\n", $values[24]),
						"receptionist_start_date" => $values[7],
						"receptionist_end_date" => $values[8],
						"contents" => str_replace("<br>", "\n", $values[29]),
						"update_at" => $values[2],
						"dates" => $values[14],
						"web_flg" => $values[16],
					);

					// WEBOFFERの0と1を逆にして入れる
					if ($param4["web_flg"] == 0) {
						$param4["web_flg"] = 1;
					}
					else {
						$param4["web_flg"] = 0;
					}

					print_r($param4);
					echo "\n";

					// 親がrel_product_bar_association_importに存在するかどうかのチェック
					$query = $this->db->query(
						' SELECT * FROM rel_product_bar_association_branch_import'.
						' WHERE product_id = ?'.
						' AND bar_association_branch_id = ?',
						array(
							$product_id,
							$param4['bar_association_branch_id'],
						)
					);

					// 登録処理
					if ($query->num_rows() == 0) {
						$this->db->query("INSERT INTO rel_product_bar_association_branch_import (product_id, bar_association_branch_id, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_branch_id'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:child insert] product_id:".$product_id);
					}

					// 更新処理
					else {
						$this->db->query("UPDATE rel_product_bar_association_branch_import SET capacity = ?, hall = ?, receptionist_start_date = ?, receptionist_end_date = ?, contents = ?, update_at = ?, dates = ?, web_flg = ? WHERE product_id = ? AND bar_association_branch_id =?", array(
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
											$product_id,
											$param4['bar_association_branch_id'],
										));

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:child update] product_id:".$product_id);
					}
				}

				// $update_cnt++;
				*/
			}

			// 親の場合
			else {
				/*
				$param = array(
					"product_id" => $values[0] + 10000,
					"product_type" => 2,
					"product_name" => $values[19],
					"product_code" => "KAI".($values[0] + 10000),
					"price" => $values[26],
					"term_id" => "519", // 固定
					"start_date" => $values[7],
					"end_date" => $values[8],
					"memo" => str_replace("<br>", "\n", $values[21]),
					"teacher" => $values[20],
					"regist_date" => $values[2],
					"update_date" => $values[3],
					"del_flg" => "0",
				);

				// DEL_DATEが空で無かったらdel_flgを1にする
				if (strlen($param["update_date"]) > 0) {
					$param["del_flg"] = "1";
				}

				// DEL_DATEが空だったらupdate_dateをADD_DATEにする
				if (strlen($param["update_date"]) == 0) {
					$param["update_date"] = $param["regist_date"];
				}

				echo "【 親 】\n";
				print_r($param);
				echo "\n";

				// 一括コンテンツダウンロード設定
				$param2 = array(
					"product_type_add" => "2",
					"all_contents_download" => "",
					"all_contents_download_before" => "",
				);

				// 会場研修商品設定
				$param3 = array(
					"training_kind_flg" => $values[11],
					"ethic_flg" => $values[15],
					"app_flg" => "0",
					"limit_date" => $values[9],
					"memo1" => str_replace("<br>", "\n", $values[21]),
					"memo2" => str_replace("<br>", "\n", $values[22]),
					"memo3" => str_replace("<br>", "\n", $values[23]),
					"memo4" => str_replace("<br>", "\n", $values[27]),
					"memo5" => str_replace("<br>", "\n", $values[28]),
					"live_start_date" => $values[6],
					"download_flg" => "0",
					"target_flg" => $values[17],
					"sponsor" => "",
					"target" => "",
				);

				// 新旧研修種別対比表
				$param3["training_kind_flg"] = $this->_get_training_kind_convert($param3["training_kind_flg"]);
				if (strlen($param3["training_kind_flg"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_5.log", "[error] id:".$values[0]);
					continue;
				}

				// REG_DATEが空でなければ1
				if (strlen($values[4]) > 0) {
					$param3["app_flg"] = "1";
				}

				// memo2が空でなかったら講師名の前に改行を追加
				if (strlen($param3["memo2"]) > 0) {
					$param3["memo2"] .= "\n" ;
				}
				$param3["memo2"] .= $values[20];

				// 新旧受講対象フラグ対比表
				$param3["target_flg"] = $this->_get_target_flg_convert($param3["target_flg"]);
				if (strlen($param3["target_flg"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_6.log", "[error] id:".$values[0]);
					continue;
				}

				// sponsor（kenshu_owner1）
				$owner1 = array();
				foreach ($kenshu_owner1 as $k2 => $v2) {
					if ($v2["product_id"] == $values[0] + 10000) {
						if (!in_array($v2["bar_association_id"], $owner1)) {
							$owner1[] = $v2["bar_association_id"];
						}
					}
				}
				// sponsor（kenshu_owner2）
				foreach ($kenshu_owner2 as $k2 => $v2) {
					if ($v2["product_id"] == $values[0] + 10000) {
						if (!in_array($v2["bar_association_id"], $owner1)) {
							$owner1[] = $v2["bar_association_id"];
						}
					}
				}
				if ($owner1) {
					$param3["sponsor"] = "|".implode("|", $owner1)."|";
				}

				// target（kenshu_target）
				$target = array();
				foreach ($kenshu_target as $k2 => $v2) {
					if ($v2["product_id"] == $values[0] + 10000) {
						if (!in_array($v2["bar_association_id"], $target)) {
							$target[] = $v2["bar_association_id"];
						}
					}
				}
				if ($target) {
					$param3["target"] = "|".implode("|", $target)."|";
				}

				// エラーログファイル生成
				if (strlen($param3["sponsor"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_1.log", "[error] id:".$values[0]);

					// 全弁護士会
					foreach ($_bar_association_id_convert as $k3 => $v3) {
						$v3 = explode("," , $v3);
						foreach ($v3 as $k4 => $v4) {
							if (!in_array($v4, $owner1)) {
								$owner1[] = $v4;
							}
						}
					}
					$param3["sponsor"] = "|".implode("|", $owner1)."|";
				}
				if (strlen($param3["target"]) == 0) {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_2.log", "[error] id:".$values[0]);

					// 全弁護士会
					foreach ($_bar_association_id_convert as $k3 => $v3) {
						$v3 = explode("," , $v3);
						foreach ($v3 as $k4 => $v4) {
							if (!in_array($v4, $target)) {
								$target[] = $v4;
							}
						}
					}
					$param3["target"] = "|".implode("|", $target)."|";
				}

				// すでにtbl_product_importマスタに存在するかどうかのチェック
				$query = $this->db->query(
					' SELECT * FROM tbl_product_import'.
					' WHERE product_code = ?',
					array(
						$param["product_code"],
					)
				);

				$product_id = "";

				// 登録処理
				if ($query->num_rows() == 0) {
					$res = $this->db->query($this->db->insert_string('tbl_product_import', $param));
					if ($res) {
						$product_id = $this->db->insert_id();
						$regist_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:insert] product_id:".$product_id);
					}
				}

				// 更新処理
				else {
					// 更新するproduct_id
					$rows = $query->result_array();
					$product_id = $rows[0]['product_id'];

					$res = $this->db->query($this->db->update_string('tbl_product_import', $param, 'product_id='.$product_id));
					if ($res) {
						$update_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:update] product_id:".$product_id);
					}
				}

				if ($res && $product_id) {
					// DELETE tbl_product_add
					$this->db->query("DELETE FROM tbl_product_add_import WHERE product_id = ?", array(
										$product_id
									));

					// INSERT tbl_product_add
					$this->db->query("INSERT INTO tbl_product_add_import (product_id, product_type_add, all_contents_download, all_contents_download_before) VALUES (?,?,?,?)", array(
										$product_id,
										$param2["product_type_add"],
										$param2["all_contents_download"],
										$param2["all_contents_download_before"],
									));


					// DELETE tbl_product_live_training_import
					$this->db->query("DELETE FROM tbl_product_live_training_import WHERE product_id = ?", array(
										$product_id
									));

					// INSERT tbl_product_live_training_import
					$this->db->query("INSERT INTO tbl_product_live_training_import (product_id, training_kind_flg, ethic_flg, app_flg, limit_date, memo1, memo2, memo3, memo4, memo5, live_start_date, download_flg, target_flg, sponsor, target) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array(
										$product_id,
										$param3['training_kind_flg'],
										$param3['ethic_flg'],
										$param3['app_flg'],
										$param3['limit_date'],
										$param3['memo1'],
										$param3['memo2'],
										$param3['memo3'],
										$param3['memo4'],
										$param3['memo5'],
										$param3['live_start_date'],
										$param3['download_flg'],
										$param3['target_flg'],
										$param3['sponsor'],
										$param3['target'],
									));

					// DELETE tbl_product_live_training_import
					$this->db->query("DELETE FROM rel_product_bar_association_import WHERE product_id = ?", array(
										$product_id
									));

					// echo "owner1:\n";
					foreach ($owner1 as $k1 => $v1) {
						// 会場研修商品の主催1設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "1",
							"capacity" => $values[25],
							"hall" => str_replace("<br>", "\n", $values[24]),
							"receptionist_start_date" => $values[7],
							"receptionist_end_date" => $values[8],
							"contents" => str_replace("<br>", "\n", $values[29]),
							"update_at" => $values[2],
							"dates" => $values[14],
							"web_flg" => $values[16],
						);

						// WEBOFFERの0と1を逆にして入れる
						if ($param4["web_flg"] == 0) {
							$param4["web_flg"] = 1;
						}
						else {
							$param4["web_flg"] = 0;
						}

						// INSERT rel_product_bar_association_import
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// echo "owner2:\n";
					foreach ($owner2 as $k1 => $v1) {
						// 会場研修商品の主催2設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "1",
							"capacity" => $values[25],
							"hall" => str_replace("<br>", "\n", $values[24]),
							"receptionist_start_date" => $values[7],
							"receptionist_end_date" => $values[8],
							"contents" => str_replace("<br>", "\n", $values[29]),
							"update_at" => $values[2],
							"dates" => $values[14],
							"web_flg" => $values[16],
						);

						// WEBOFFERの0と1を逆にして入れる
						if ($param4["web_flg"] == 0) {
							$param4["web_flg"] = 1;
						}
						else {
							$param4["web_flg"] = 0;
						}

						// INSERT rel_product_bar_association_import
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// echo "target:\n";
					foreach ($target as $k1 => $v1) {
						// 会場研修商品の受講対象設定
						$param4 = array(
							"bar_association_id" => $v1,
							"atype" => "3",
							"capacity" => "",
							"hall" => "",
							"receptionist_start_date" => "",
							"receptionist_end_date" => "",
							"contents" => "",
							"update_at" => "",
							"dates" => "",
							"web_flg" => "",
						);

						// INSERT rel_product_bar_association_import
						$this->db->query("INSERT INTO rel_product_bar_association_import (product_id, bar_association_id, atype, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array(
											$product_id,
											$param4['bar_association_id'],
											$param4['atype'],
											$param4['capacity'],
											$param4['hall'],
											$param4['receptionist_start_date'],
											$param4['receptionist_end_date'],
											$param4['contents'],
											$param4['update_at'],
											$param4['dates'],
											$param4['web_flg'],
										));
						// print_r($param4);
						// echo "\n";
					}

					// DELETE rel_product_bar_association_branch_import
					$this->db->query("DELETE FROM rel_product_bar_association_branch_import WHERE product_id = ?", array(
										$product_id
									));

					// echo "owner1_branch:\n";
					foreach ($owner1 as $k1 => $v1) {
						// 新弁護士会IDと弁護士会支部IDの紐づき
						$bar_association_branch_id = $this->_get_bar_association_branch_id_convert(array($v1));
						foreach ($bar_association_branch_id as $k2 => $v2) {
							// 会場研修商品の受講対象設定
							$param4 = array(
								"bar_association_branch_id" => $v2,
								"capacity" => $values[25],
								"hall" => str_replace("<br>", "\n", $values[24]),
								"receptionist_start_date" => $values[7],
								"receptionist_end_date" => $values[8],
								"contents" => str_replace("<br>", "\n", $values[29]),
								"update_at" => $values[2],
								"dates" => $values[14],
								"web_flg" => $values[16],
							);

							// WEBOFFERの0と1を逆にして入れる
							if ($param4["web_flg"] == 0) {
								$param4["web_flg"] = 1;
							}
							else {
								$param4["web_flg"] = 0;
							}

							// INSERT rel_product_bar_association_import
							$this->db->query("INSERT INTO rel_product_bar_association_branch_import (product_id, bar_association_branch_id, capacity, hall, receptionist_start_date, receptionist_end_date, contents, update_at, dates, web_flg) VALUES (?,?,?,?,?,?,?,?,?,?)", array(
												$product_id,
												$param4['bar_association_branch_id'],
												$param4['capacity'],
												$param4['hall'],
												$param4['receptionist_start_date'],
												$param4['receptionist_end_date'],
												$param4['contents'],
												$param4['update_at'],
												$param4['dates'],
												$param4['web_flg'],
											));
							// print_r($param4);
							// echo "\n";
						}
					}

					// convert_id_listへ
					$this->_convert_id_list("product_kenshu", $values[0], $product_id);
				}
				*/
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// eラーニング動画 インポート
	//----------------------------------------------
	function import_ms_contents($file_path, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("TRUNCATE TABLE video_import");
			$this->db->query("TRUNCATE TABLE video_alfstream_status_import");
			$this->db->query("TRUNCATE TABLE video_lecture_import");
			$this->db->query("TRUNCATE TABLE video_search_index_import");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_elearning'");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_error_1.log");
		$this->_clear_log(__FUNCTION__."_error_2.log");
		$this->_clear_log(__FUNCTION__."_error_3.log");
		$this->_clear_log(__FUNCTION__."_error_4.log");
		$this->_clear_log(__FUNCTION__."_error_5.log");
		$this->_clear_log(__FUNCTION__."_error_6.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		$line_2 = '';
		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 9項目未満はスキップ
			// if (count($values) < 9) { continue; }

			// シリアル、商品ID
			// if (strlen($values[0]) == 0 || strlen($values[3]) == 0) { continue; }

			// 動画名の整形（<br>を改行コードにする）
			$video_logic_name = str_replace("\t", " ", $values[6]);
			$video_logic_name = str_replace("<br>", "\n", $video_logic_name);
			// $video_logic_name = str_replace("mms://fre.sswmt1.smartstream.ne.jp/fre/", "", $video_logic_name);
			// $video_logic_name = str_replace(".wmv", ".flv", $video_logic_name);

			$param = array(
				"video_name" => $values[7],
				"video_logic_name" => $video_logic_name,
				"video_caption" => "",
				// "video_tags" => preg_replace('/[、，, \n]/u', ',', $video_logic_name),
				"video_tags" => "",
				"school_id" => $this->config->item('nichibenren_school_id'),
				"teacher_id" => $this->_teacher_id,
				"page_num" => 0,
				"original_file_size" => 0,
				"local_reading_flag" => 0,
				"local_reading_open" => '2000/01/01 00:00:00',
				"local_reading_close" => NULL,
				"status" => 0,
				// 一旦、テストで昔の日付
				// 昔の日付だと、バッチがチェックしてくれない？
				// "added_at" => date('Y/m/d H:i:s'),
				"added_at" => $values[1],
				"update_at" => date('Y/m/d H:i:s'),
				// 一旦、テストで同じ動画
				// "idkey" => NULL,
				"idkey" => 'bqJNHN4s85YG',
			);

			if (strlen($param["video_name"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error no video_name] id:".$values[0]);
				$param["video_name"] = "no value";
			}

			if (strlen($param["video_logic_name"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error no video_logic_name] id:".$values[0]);
				$param["video_logic_name"] = "no value";
			}

			print_r($param);
			echo "\n";

			// すでにvideo_importマスタに存在するかどうかのチェック
			// （DB取込用ID変換履歴テーブルから）
			$query = $this->db->query(
				' SELECT T1.* FROM video_import T1 INNER JOIN convert_id_list T2 ON (T1.video_id = T2.new_id)'.
				' WHERE T2.old_id = ? AND T2.id_kind = "video_elearning"',
				array(
					$values[0],
				)
			);

			$video_id = "";

			// 登録処理
			if ($query->num_rows() == 0) {
				$res = $this->db->query($this->db->insert_string('video_import', $param));
				if ($res) {
					$video_id = $this->db->insert_id();
					$regist_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:insert] video_id:".$video_id);
				}
			}

			// 更新処理
			else {
				// 更新するvideo_id
				$rows = $query->result_array();
				$video_id = $rows[0]['video_id'];

				$res = $this->db->query($this->db->update_string('video_import', $param, 'video_id='.$video_id));
				if ($res) {
					$update_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:update] video_id:".$video_id);
				}
			}

			if ($res && $video_id) {
				// DELETE video_alfstream_status_import
				$this->db->query("DELETE FROM video_alfstream_status_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_alfstream_status_import
				$param2 = array(
					"video_id" => $video_id,
					"alfstream_status" => "ONLINE", // 一旦、これで
					"alfstream_duration" => $this->_sec_to_time_convert($values[9]),
					"update_at" => date('Y/m/d H:i:s'),
				);

				$this->db->query($this->db->insert_string('video_alfstream_status_import', $param2));

				// DELETE video_lecture_import
				$this->db->query("DELETE FROM video_lecture_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_lecture_import
				$param2 = array(
					"video_id" => $video_id,
					"cource_id" => $this->config->item('nichibenren_cource_id'),
					"update_at" => date('Y/m/d H:i:s'),
				);

				$this->db->query($this->db->insert_string('video_lecture_import', $param2));

				// DELETE video_search_index_import
				$this->db->query("DELETE FROM video_search_index_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_search_index_import
				$param2 = array(
					"video_id" => $video_id,
					"cource_id" => $this->config->item('nichibenren_cource_id'),
					"video_tag" => preg_replace('/[、，, \t\n]/u', ' ', $video_logic_name),
				);

				$this->db->query($this->db->insert_string('video_search_index_import', $param2));

				// 商品の関連付け
				$param3 = array(
					"product_id" => $values[3],
					"contents_contents" => $video_id,
					"contents_contents_name" => $video_logic_name,
				);

				echo "product:\n";
				print_r($param3);
				echo "\n";

				// すでにtbl_product_importマスタに存在するかどうかのチェック
				// （DB取込用ID変換履歴テーブルから）
				$query = $this->db->query(
					' SELECT T1.* FROM tbl_product_import T1 INNER JOIN convert_id_list T2 ON (T1.product_id = T2.new_id)'.
					' WHERE T2.old_id = ? AND T2.id_kind = "product_elearning"',
					array(
						$param3["product_id"],
					)
				);

				$product_id = "";

				// 更新処理
				if ($query->num_rows() > 0) {
					// 更新するproduct_id
					$rows = $query->result_array();
					$product_id = $rows[0]['product_id'];

					$param4 = array();

					$res = "";

					// 並び順を考慮してコンテンツを登録
					$sort_key = floor($values[4]);

					// 0が一個だけある（ID：139）。。。
					if ($sort_key == 0) { $sort_key = 1; }

					if ($this->_max_contents >= $sort_key) {
						$param4["contents_contents".$sort_key] = $param3["contents_contents"];
						$param4["contents_contents".$sort_key."_name"] = $param3["contents_contents_name"];
					}

					if ($param4) {
						$res = $this->db->query($this->db->update_string('tbl_product_import', $param4, 'product_id='.$product_id));
						if (!$res) {
							// LOGの書き出し
							$this->_output_log(__FUNCTION__."_error_3.log", "[error] id:".$values[0]);
							// echo "NG1:".$product_id." ".$param3["contents_contents"]."\n";
						}
					}
					else {
						// LOGの書き出し
						$this->_output_log(__FUNCTION__."_error_4.log", "[error] id:".$values[0]);
						// echo "NG2:".$product_id." ".$param3["contents_contents"]."\n";
					}

					// play_timeの合計値の取得
					$play_time = 0;
					for ($k = 1; $k <= $this->_max_contents; $k++) {
						if ($rows[0]["contents_contents".$k]) {
							$query2 = $this->db->query(
								' SELECT TIME_TO_SEC(alfstream_duration) AS play_time FROM video_alfstream_status_import'.
								' WHERE video_id = ?',
								array(
									$rows[0]["contents_contents".$k],
								)
							);
							if ($query2->num_rows() > 0) {
								$rows2 = $query2->result_array();
								$play_time += $rows2[0]['play_time'];
							}
						}
					}

					// play_timeの整形
					$play_time = $this->_sec_to_time_convert($play_time);

					// play_timeの合計値の設定
					$res = $this->db->query($this->db->update_string('tbl_product_import', array("play_time" => $play_time), 'product_id='.$product_id));
				}
				else {
					// LOGの書き出し
					$this->_output_log(__FUNCTION__."_error_2.log", "[error] id:".$values[0]);
					// echo "no match product\n";
				}

				// convert_id_listへ
				$this->_convert_id_list("video_elearning", $values[0], $video_id);
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// 倫理研修－テスト インポート
	//----------------------------------------------
	function import_tmoral_exam($file_path, $type, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("TRUNCATE TABLE tbl_ethic_group_import");
			$this->db->query("TRUNCATE TABLE tbl_ethic_question_import");
			$this->db->query("TRUNCATE TABLE tbl_ethic_branch_import");
			$this->db->query("TRUNCATE TABLE tbl_product_ethic_training_import");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'product_ethic_training'");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'ethic_question'");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_product.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		if ($type == "tMORAL_EXAM_2009") {
			$product_name = "2009年度代替";
			$old_id = "2009";
		}
		elseif ($type == "tMORAL_EXAM_2010") {
			$product_name = "2010年度代替";
			$old_id = "2010";
		}
		elseif ($type == "tMORAL_EXAM_2011") {
			$product_name = "2011年度代替";
			$old_id = "2011";
		}
		elseif ($type == "tMORAL_EXAM_2012") {
			$product_name = "2012年度代替";
			$old_id = "2012";
		}
		elseif ($type == "tMORAL_EXAM") {
			$product_name = "2013年度代替";
			$old_id = "2013";
		}

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 10項目未満はスキップ
			// if (count($values) < 10) { continue; }

			// シリアル、カテゴリ、タイトル
			// if (strlen($values[0]) == 0 || strlen($values[1]) == 0 || strlen($values[2]) == 0) { continue; }

			$values[1] = $product_name;

			// 並び順
			$query = $this->db->query("SELECT MAX(rank) AS rank FROM tbl_ethic_group_import", array());
			if ($query->num_rows() > 0) {
				$rows = $query->result_array();
				$rank = $rows[0]['rank'] + 1;
			}
			else {
				$rank = 1;
			}

			$param = array(
				"question_group" => $values[1],
				"rank" => $rank,
			);

			/*
			print_r($param);
			echo "\n";
			*/

			// すでにtbl_ethic_group_importマスタに存在するかどうかのチェック
			$query = $this->db->query(
				' SELECT * FROM tbl_ethic_group_import'.
				' WHERE question_group = ?',
				array(
					$param["question_group"],
				)
			);

			$ethic_group_id = "";

			// 登録処理
			if ($query->num_rows() == 0) {
				$res = $this->db->query($this->db->insert_string('tbl_ethic_group_import', $param));
				if ($res) {
					$ethic_group_id = $this->db->insert_id();

					$param2 = array(
						"product_type" => "2",
						"product_name" => $values[1]."倫理研修",
						"product_code" => "ETH".str_pad($ethic_group_id, 5, "0", STR_PAD_LEFT),
						"price" => "0",
						"term_id" => "519",
						"start_date" => NULL,
						"end_date" => NULL,
						"open_period" => "0",
						"memo" => NULL,
						"play_time" => NULL,
						"regist_date" => date("Y-m-d H:i:s"),
						"update_date" => date("Y-m-d H:i:s"),
						"del_flg" => "0",
					);

					for ($k = 1; $k <= $this->_max_contents; $k++) {
						$param2["contents_free_time".$k] = "0";
					}

					// play_timeの合計値
					$play_time = 0;

					// 倫理研修のコンテンツの取得
					// （DB取込用ID変換履歴テーブルから）
					$query2 = $this->db->query(
						' SELECT T1.*, T2.alfstream_duration FROM (video_import T1 INNER JOIN video_alfstream_status_import T2 ON (T1.video_id = T2.video_id)) INNER JOIN convert_id_list T3 ON (T2.video_id = T3.new_id)'.
						' WHERE T3.old_id = ? AND T3.id_kind = "video_tmoral_exam_id"',
						array(
							0,
						)
					);
					if ($query2->num_rows() > 0) {
						$rows2 = $query2->result_array();
						foreach ($rows2 as $k2 => $v2) {
							$play_time +=  $this->_time_to_sec_convert($v2["alfstream_duration"]);

							if ($this->_max_contents >= $k2 + 1) {
								$param2["contents_contents".($k2 + 1)] = $v2["video_id"];
								$param2["contents_contents".($k2 + 1)."_name"] = $v2["video_logic_name"];
							}
						}
					}

					// play_timeの合計値の設定
					$param2["play_time"] = $this->_sec_to_time_convert($play_time);

					// 一括コンテンツダウンロード設定
					$param3 = array(
						"product_type_add" => "3",
						"all_contents_download" => "",
						"all_contents_download_before" => "",
					);

					// 倫理研修商品設定
					$param4 = array(
						"ethic_group_id" => $ethic_group_id,
						"bar_association_year" => "0",
						"publish_flg" => "1",
					);

					echo "【 商品登録 】\n";
					print_r($param2);
					echo "\n";

					$query = $this->db->query(
						' SELECT * FROM tbl_product_import'.
						' WHERE product_code = ?',
						array(
							$param2["product_code"],
						)
					);

					// すでにtbl_product_importマスタに存在するかどうかのチェック
					// （DB取込用ID変換履歴テーブルから）
					$query = $this->db->query(
						' SELECT T1.* FROM tbl_product_import T1 INNER JOIN convert_id_list T2 ON (T1.product_id = T2.new_id)'.
						' WHERE T2.old_id = ? AND T2.id_kind = "product_ethic_training"',
						array(
							$old_id,
						)
					);

					$product_id = "";

					// 登録処理
					if ($query->num_rows() == 0) {
						$res = $this->db->query($this->db->insert_string('tbl_product_import', $param2));
						if ($res) {
							$product_id = $this->db->insert_id();
							// $regist_cnt++;

							// LOGの書き出し
							$this->_output_log(__FUNCTION__."_product.log", "[ok:insert] product_id:".$product_id);
						}
					}

					// 更新処理
					else {
echo "product update\n";
						// 更新するproduct_id
						$rows = $query->result_array();
						$product_id = $rows[0]['product_id'];

						$res = $this->db->query($this->db->update_string('tbl_product_import', $param2, 'product_id='.$product_id));
						if ($res) {
							// $update_cnt++;

							// LOGの書き出し
							$this->_output_log(__FUNCTION__."_product.log", "[ok:update] product_id:".$product_id);
						}
					}

					if ($res && $product_id) {
						// DELETE tbl_product_add
						$this->db->query("DELETE FROM tbl_product_add_import WHERE product_id = ?", array(
											$product_id
										));

						// INSERT tbl_product_add
						$this->db->query("INSERT INTO tbl_product_add_import (product_id, product_type_add, all_contents_download, all_contents_download_before) VALUES (?,?,?,?)", array(
											$product_id,
											$param3["product_type_add"],
											$param3["all_contents_download"],
											$param3["all_contents_download_before"],
										));

						// DELETE tbl_product_ethic_training_import
						$this->db->query("DELETE FROM tbl_product_ethic_training_import WHERE product_id = ?", array(
											$product_id
										));

						// INSERT tbl_product_elearning
						$this->db->query("INSERT INTO tbl_product_ethic_training_import (product_id, ethic_group_id, bar_association_year, publish_flg) VALUES (?,?,?,?)", array(
											$product_id,
											$param4['ethic_group_id'],
											$param4['bar_association_year'],
											$param4['publish_flg'],
										));

						// convert_id_listへ
						$this->_convert_id_list("product_ethic_training", $old_id, $product_id);
					}
				}
			}

			// 更新処理
			else {
				// 更新するethic_group_id
				$rows = $query->result_array();
				$ethic_group_id = $rows[0]['ethic_group_id'];
			}

			if ($ethic_group_id) {
				// 個数
				$query = $this->db->query(
					' SELECT COUNT(*) AS cnt FROM tbl_ethic_question_import'.
					' WHERE ethic_group_id = ?',
					array(
						$ethic_group_id,
					)
				);
				if ($query->num_rows() > 0) {
					$rows = $query->result_array();
					$cnt = $rows[0]['cnt'] + 1;
				}
				else {
					$cnt = 1;
				}

				if ($cnt > 10) {
					$failure_flg = 1;
					$rank = $cnt - 10;
				}
				else {
					$failure_flg = 0;
					$rank = $cnt;
				}

				// video_idの取得（EXAM_IDから）
				// （DB取込用ID変換履歴テーブルから）
				$query = $this->db->query(
					' SELECT T1.* FROM video_import T1 INNER JOIN convert_id_list T2 ON (T1.video_id = T2.new_id)'.
					' WHERE T2.old_id = ? AND T2.id_kind = "video_tmoral_exam_id"',
					array(
						$values[0],
					)
				);

				$video_id = 0;

				if ($query->num_rows() > 0) {
					$rows = $query->result_array();
					$video_id = $rows[0]["video_id"];
				}

				$param2 = array(
					"ethic_group_id" => $ethic_group_id,
					"question" => str_replace("<br>", "\n", $values[3]),
					"failure_flg" => $failure_flg,
					"video_id" => $video_id,
					"rank" => $rank,
				);

				print_r($param2);
				echo "\n";

				// すでにtbl_ethic_questionマスタに存在するかどうかのチェック
				$query = $this->db->query(
					' SELECT * FROM tbl_ethic_question_import'.
					' WHERE ethic_group_id = ?'.
					' AND question = ?',
					array(
						$ethic_group_id,
						$param2["question"],
					)
				);

				// 登録処理
				$ethic_question_id = "";
				if ($query->num_rows() == 0) {
					$res = $this->db->query($this->db->insert_string('tbl_ethic_question_import', $param2));
					if ($res) {
						$ethic_question_id  = $this->db->insert_id();
						$regist_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:insert] ethic_question_id:".$ethic_question_id);
					}
				}

				// 更新処理
				else {
					// 更新するproduct_id
					$rows = $query->result_array();
					$ethic_question_id = $rows[0]['ethic_question_id'];

					// 不要な変数の削除
					unset($param2["rank"]);

					$res = $this->db->query($this->db->update_string('tbl_ethic_question_import', $param2, 'ethic_question_id='.$ethic_question_id));
					if ($res) {
						$update_cnt++;

						// LOGの書き出し
						$this->_output_log(__FUNCTION__.".log", "[ok:update] ethic_question_id:".$ethic_question_id);
					}
				}

				// convert_id_listへ
				$this->_convert_id_list("ethic_question", $values[0], $ethic_question_id);

				if ($ethic_question_id) {
					// DELETE tbl_ethic_branch_import
					$this->db->query("DELETE FROM tbl_ethic_branch_import WHERE ethic_question_id = ?", array(
										$ethic_question_id
									));

					for ($j = 1; $j <= 5; $j++) {
						$param3 = array(
							"ethic_question_id" => $ethic_question_id,
							"question_branch" => str_replace("<br>", "\n", $values[4 + $j]),
							"answer_flg" => $values[4],
							"rank" => $j,
						);
						if ($j == $values[4]) {
							$param3["answer_flg"] = 1;
						}
						else {
							$param3["answer_flg"] = 0;
						}

						/*
						print_r($param3);
						echo "\n";
						*/

						// INSERT tbl_ethic_branch_import
						$this->db->query("INSERT INTO tbl_ethic_branch_import (ethic_question_id, question_branch, answer_flg, rank) VALUES (?,?,?,?)", array(
											$param3['ethic_question_id'],
											$param3['question_branch'],
											$param3['answer_flg'],
											$param3['rank'],
										));
					}
				}
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// 倫理研修－動画 インポート
	//----------------------------------------------
	function import_tmoral_movie($file_path, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			/*
			$this->db->query("TRUNCATE TABLE video_import");
			$this->db->query("TRUNCATE TABLE video_alfstream_status_import");
			$this->db->query("TRUNCATE TABLE video_lecture_import");
			$this->db->query("TRUNCATE TABLE video_search_index_import");
			*/
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_tmoral'");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'video_tmoral_exam_id'");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_error_1.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 6項目未満はスキップ
			// if (count($values) < 6) { continue; }

			// シリアル、商品名
			// if (strlen($values[0]) == 0 || strlen($values[4]) == 0) { continue; }

			// 動画名の整形（<br>を改行コードにする）
			$video_logic_name = str_replace("\t", " ", $values[4]);
			$video_logic_name = str_replace("<br>", "\n", $video_logic_name);

			$param = array(
				"video_name" => $values[5],
				"video_logic_name" => $video_logic_name,
				"video_caption" => "",
				// "video_tags" => preg_replace('/[、，, \n]/u', ',', $video_logic_name),
				"video_tags" => "",
				"school_id" => $this->config->item('nichibenren_school_id'),
				"teacher_id" => $this->_teacher_id,
				"page_num" => 0,
				"original_file_size" => 0,
				"local_reading_flag" => 0,
				"local_reading_open" => '2000/01/01 00:00:00',
				"local_reading_close" => NULL,
				"status" => 0,
				// 一旦、テストで昔の日付
				// 昔の日付だと、バッチがチェックしてくれない？
				// "added_at" => date('Y/m/d H:i:s'),
				"added_at" => $values[1],
				"update_at" => date('Y/m/d H:i:s'),
				// 一旦、テストで同じ動画
				// "idkey" => NULL,
				"idkey" => 'bqJNHN4s85YG',
			);

			if (strlen($param["video_name"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error no video_name] id:".$values[0]);
				$param["video_name"] = "no value";
			}

			if (strlen($param["video_logic_name"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error no video_logic_name] id:".$values[0]);
				$param["video_logic_name"] = "no value";
			}

			print_r($param);
			echo "\n";

			// すでにvideo_importマスタに存在するかどうかのチェック
			// （DB取込用ID変換履歴テーブルから）
			$query = $this->db->query(
				' SELECT T1.* FROM video_import T1 INNER JOIN convert_id_list T2 ON (T1.video_id = T2.new_id)'.
				' WHERE T2.old_id = ? AND T2.id_kind = "video_tmoral"',
				array(
					$values[0],
				)
			);

			$video_id = "";

			// 登録処理
			if ($query->num_rows() == 0) {
				$res = $this->db->query($this->db->insert_string('video_import', $param));
				if ($res) {
					$video_id = $this->db->insert_id();
					$regist_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:insert] video_id:".$video_id);
				}
			}

			// 更新処理
			else {
				// 更新するvideo_id
				$rows = $query->result_array();
				$video_id = $rows[0]['video_id'];

				$res = $this->db->query($this->db->update_string('video_import', $param, 'video_id='.$video_id));
				if ($res) {
					$update_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:update] video_id:".$video_id);
				}
			}

			if ($res && $video_id) {
				// DELETE video_alfstream_status_import
				$this->db->query("DELETE FROM video_alfstream_status_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_alfstream_status_import
				$param2 = array(
					"video_id" => $video_id,
					"alfstream_status" => "ONLINE", // 一旦、これで
					"alfstream_duration" => $this->_sec_to_time_convert($values[6]),
					"update_at" => date('Y/m/d H:i:s'),
				);

				$this->db->query($this->db->insert_string('video_alfstream_status_import', $param2));

				// DELETE video_lecture_import
				$this->db->query("DELETE FROM video_lecture_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_lecture_import
				$param2 = array(
					"video_id" => $video_id,
					"cource_id" => $this->config->item('nichibenren_cource_id'),
					"update_at" => date('Y/m/d H:i:s'),
				);

				$this->db->query($this->db->insert_string('video_lecture_import', $param2));

				// DELETE video_search_index_import
				$this->db->query("DELETE FROM video_search_index_import WHERE video_id = ?", array(
									$video_id
								));

				// INSERT video_search_index_import
				$param2 = array(
					"video_id" => $video_id,
					"cource_id" => $this->config->item('nichibenren_cource_id'),
					"video_tag" => preg_replace('/[、，, \t\n]/u', ' ', $video_logic_name),
				);

				$this->db->query($this->db->insert_string('video_search_index_import', $param2));

				// convert_id_listへ
				$this->_convert_id_list("video_tmoral", $values[0], $video_id);
				$this->_convert_id_list("video_tmoral_exam_id", $values[3], $video_id, FALSE);
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// 倫理研修解答結果 インポート
	//----------------------------------------------
	function import_tmoral_result($file_path, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("TRUNCATE TABLE tbl_ethic_question_history_import");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		if ($type == "tMORAL_RESULT_2009") {
			$product_name = "2009年度代替";
		}
		elseif ($type == "tMORAL_RESULT_2010") {
			$product_name = "2010年度代替";
		}
		elseif ($type == "tMORAL_RESULT_2011") {
			$product_name = "2011年度代替";
		}
		elseif ($type == "tMORAL_RESULT_2012") {
			$product_name = "2012年度代替";
		}
		elseif ($type == "tMORAL_RESULT") {
			$product_name = "2013年度代替";
		}

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 70項目未満はスキップ
			// if (count($values) < 70) { continue; }

			// シリアル、生徒ID
			// if (strlen($values[0]) == 0 || strlen($values[2]) == 0) { continue; }

			// student_idの取得
			// （DB取込用ID変換履歴テーブルから）
			$query = $this->db->query(
				' SELECT T1.* FROM student T1 INNER JOIN convert_id_list T2 ON (T1.student_id = T2.new_id)'.
				' WHERE T2.old_id = ? AND T2.id_kind = "student"',
				array(
					$values[2],
				)
			);

			$student_id = 0;

			if ($query->num_rows() > 0) {
				$rows = $query->result_array();
				$student_id = $rows[0]["student_id"];
			}

			// product_idの取得
			$product_id = 0;
			$query = $this->db->query(
				' SELECT * FROM tbl_product_import'.
				' WHERE product_name = ?',
				array(
					$product_name."倫理研修",
				)
			);
			if ($query->num_rows() > 0) {
				$rows = $query->result_array();
				$product_id = $rows[0]["product_id"];
			}

			// statusの判断
			$status = 0;
			// tMORAL_RESULT.EXAM_DATEが空の場合は1
			if (strlen($values[3]) == 0) {
				$status = 1;
			}
			// tMORAL_RESULT.EXAM_DATEが空でない、かつtMORAL_RESULT.EXAM_RESULT=1の場合は2
			elseif (strlen($values[3]) != 0 && $values[4] == 1) {
				$status = 2;
			}
			// tMORAL_RESULT.EXAM_DATEが空でない、かつtMORAL_RESULT.EXAM_RESULT=0、かつtMORAL_RESULT.EXAM_DATE2が空の場合は3
			elseif (strlen($values[3]) != 0 && $values[4] == 0 && strlen($values[5]) == 0) {
				$status = 3;
			}
			// tMORAL_RESULT.EXAM_DATEが空でない、かつtMORAL_RESULT.EXAM_RESULT=0、かつtMORAL_RESULT.EXAM_DATE2が空でないの場合は4
			elseif (strlen($values[3]) != 0 && $values[4] == 0 && strlen($values[5]) != 0) {
				$status = 4;
			}
			// tMORAL_RESULT.EXAM2_DATEが空でない、かつtMORAL_RESULT.EXAM2_RESULT=1の場合は5
			elseif (strlen($values[5]) != 0 && $values[6] == 1) {
				$status = 5;
			}
			// tMORAL_RESULT.EXAM2_DATEが空でない、かつtMORAL_RESULT.EXAM2_RESULT=0の場合は6
			elseif (strlen($values[5]) != 0 && $values[6] == 0) {
				$status = 6;
			}

			// complete_flgの判断
			$complete_flg = 0;
			// tMORAL_RESULT.EXAM_DATEが空でない、かつtMORAL_RESULT.EXAM_RESULT=1の場合は1
			if (strlen($values[3]) != 0 && $values[4] == 1) {
				$complete_flg = 1;
			}

			// tMORAL_RESULT.EXAM2_DATEが空でない、かつtMORAL_RESULT.EXAM2_RESULT=1の場合は1
			elseif (strlen($values[5]) != 0 && $values[6] == 1) {
				$complete_flg = 1;
			}

			$param = array(
				"student_id" => $student_id,
				"product_id" => $product_id,
				"status" => $status,
				"create_date" => $values[1],
				"start_date1" => $values[10] ? $values[10] : NULL,
				"judge_date1" => $values[3],
				"start_date2" => $values[49] ? $values[49] : NULL,
				"judge_date2" => $values[5],
				"complete_flg" => $complete_flg,
			);

			for ($i = 1; $i <= 16; $i++) {
				$param["answer_ethic_branch_id".$i] = $values[8 + 4 * ($i - 1)];
				$param["answer_date".$i] = $values[10 + 4 * ($i - 1)];
				$param["view_flg".$i] = "0";
			}

			print_r($param);
			echo "\n";

			// すでにtbl_ethic_question_history_importマスタに存在するかどうかのチェック
			$query = $this->db->query(
				' SELECT * FROM tbl_ethic_question_history_import'.
				' WHERE student_id = ?'.
				' AND product_id = ?',
				array(
					$student_id,
					$product_id,
				)
			);

			// 登録処理
			if ($query->num_rows() == 0) {
				$res = $this->db->query($this->db->insert_string('tbl_ethic_question_history_import', $param));
				if ($res) {
					$regist_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:insert] student_id:".$student_id." / product_id:".$product_id);
				}
			}

			// 更新処理
			else {
				$res = $this->db->query($this->db->update_string('tbl_ethic_question_history_import', $param, 'student_id='.$student_id.' AND product_id='.$product_id));
				if ($res) {
					$update_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:update] student_id:".$student_id." / product_id:".$product_id);
				}
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// AVIS動画 インポート
	//----------------------------------------------
	function import_video($file_path, $all_del = 0) {
		// テーブルのデータを全削除
		if ($all_del) {
			$this->db->query("UPDATE video_import SET idkey = NULL");
		}

		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, "\t")) !== false) {
			// 最初の項目行をスキップ
			/*
			if ($i < 1) {
				$i++;
				continue;
			}
			*/

			/*
			// 一旦、10個
			if ($all_cnt >= 10) {
				continue;
			}
			*/

			// 2項目未満はスキップ
			// if (count($values) < 2) { continue; }

			// video_name、idkey
			// if (strlen($values[0]) == 0 || strlen($values[1]) == 0) { continue; }

			$param = array(
				"video_name" => "nichibenren/".$values[0],
				"idkey" => $values[1],
			);

			// すでにtbl_product_importマスタに存在するかどうかのチェック
			$query = $this->db->query(
				' SELECT * FROM video_import'.
				' WHERE video_name = ?',
				array(
					$param["video_name"],
				)
			);

			print_r($param);
			echo "\n";

			// 不要な変数の削除
			unset($param["video_name"]);

			// 更新処理
			if ($query->num_rows() > 0) {
				// 更新するvideo_id（複数ある時がある）
				$rows = $query->result_array();
				foreach ($rows as $k1 => $v1) {
					$res = $this->db->query($this->db->update_string('video_import', $param, 'video_id='.$v1["video_id"]));
					if ($res) {
						$update_cnt++;
					}
				}

				// LOGの書き出し
				$this->_output_log(__FUNCTION__.".log", "[ok:update] video_name:".$values[0]." / idkey:".$values[1]);
			}
			else {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__.".log", "[error:not hit] video_name:".$values[0]." / idkey:".$values[1]);
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// 商品カテゴリ インポート
	//----------------------------------------------
	function import_category($file_path, $all_del = 0) {
		// LOGのクリア
		$this->_clear_log(__FUNCTION__.".log");
		$this->_clear_log(__FUNCTION__."_error_1.log");
		$this->_clear_log(__FUNCTION__."_error_2.log");

		if (!$fpr = @fopen($file_path, FOPEN_READ)) {
			return array();
		}

		echo "----------------------------------------------------------------------------------\n";

		// @flock($fpr, LOCK_SH);

		$all_cnt = 0;
		$regist_cnt = 0;
		$update_cnt = 0;

		while(($values = @fgetcsv($fpr, 0, ",")) !== false) {
			// 最初の項目行をスキップ
			if ($i < 1) {
				$i++;
				continue;
			}

			/*
			// 一旦、7個
			if ($all_cnt >= 7) {
				continue;
			}
			*/

			// 2項目未満はスキップ
			// if (count($values) < 2) { continue; }

			// product_code
			if (strlen($values[3]) == 0) { continue; }

			$param = array(
				"product_code" => "",
				"term_id" => "",
				"terms" => array(),
				"term_ids" => array(),
				"term_ids2" => array(),
			);

			// csvからproduct_codeとterm_idを取得
			foreach($values as $k1 => $v1) {
				if ($k1 == 3) {
					$param["product_code"] = $v1;
					$param["term_id"] = "";
				}
				if ($k1 >= 4 && $k1 <= 13) {
					$v1 = str_replace("　", " ", $v1);
					$v1s = explode(" ", $v1);
					if (count($v1s) >= 2) {
						$a = array();
						$a["term_id"] = $v1s[0] + 0;
						$a["term_name"] = $v1s[1];
						$param["terms"][] = $a;

						if (!in_array($a["term_id"], $param["term_ids"])) {
							$param["term_ids"][] = $a["term_id"];
						}
					}
				}
			}

			// 新カテゴリコードに変換
			foreach($param["term_ids"] as $k1 => $v1) {
				if (!in_array($v1, $param["term_ids2"])) {
					$param["term_ids2"][] = $v1;
				}
			}

			// 新カテゴリコードを並び替え
			sort($param["term_ids2"]);

			// 新カテゴリコードを文字列に変換
			$param["term_id"] = implode(",", $param["term_ids2"]);
			if (strlen($param["term_id"]) == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_1.log", "[error] id:".$values[3]);
				continue;
			}

			print_r($param);
			echo "\n";

			// tbl_product_importマスタに存在するかどうかのチェック
			$product_id = 0;
			$query = $this->db->query(
				' SELECT * FROM tbl_product_import'.
				' WHERE product_code = ?',
				array(
					$param["product_code"],
				)
			);
			if ($query->num_rows() == 0) {
				// LOGの書き出し
				$this->_output_log(__FUNCTION__."_error_2.log", "[error] id:".$values[3]);
				continue;
			}
			else {
				$rows = $query->result_array();
				$product_id = $rows[0]["product_id"];

				unset($param["terms"]);
				unset($param["term_ids"]);
				unset($param["term_ids2"]);

				$res = $this->db->query($this->db->update_string('tbl_product_import', $param, 'product_id='.$product_id));
				if ($res) {
					$update_cnt++;

					// LOGの書き出し
					$this->_output_log(__FUNCTION__.".log", "[ok:update] id:".$values[3]." product_id:".$product_id);
				}
			}

			$all_cnt++;
		}

		// @flock($fpr, LOCK_UN);
		@fclose($fpr);

		echo "----------------------------------------------------------------------------------\n";

		return array($all_cnt, $regist_cnt, $update_cnt);
	}

	//----------------------------------------------
	// ×登録年月日から、パスポート料金を取得
	// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得
	//----------------------------------------------
	function _get_target_passport($regist_date = ''){
		// 戻り値の初期化
		$result['presence_passport'] = '';		// パスポートの有無（有⇒1、無⇒0）
		$result['exp_date_passport'] = '';		// パスポートの有効期限
		$result['target_passport']   = '';		// パスポート料金
	
		// 登録年月日の正誤確認
		$regist_date = trim($regist_date);
		$regist_date = preg_replace("/[\/]/", "-", $regist_date);
		if($regist_date == ''){
			return $result;
		}else{
			$array_date = explode("-", $regist_date);
			
			if(!checkdate($array_date[1], $array_date[2], $array_date[0])){
				return $result;
			}
		}
		
		// 登録年月日とシステム日付より、年目を計算
		// 登録年月日≦システム日時の場合、１～６年目の計算。
		// 登録年月日＞システム日時の場合、０年目で計算。価格も１～２年目に対応。
		$array_date = explode("-", $regist_date);
		$year_no    = 0;
		if( $regist_date <= date('Y-m-d') ){
			$year_no = 6;
			for( $i=0; $i<=4; $i++ ){
				$start_date = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2],     $array_date[0] + $i));
				$end_date   = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2] - 1, $array_date[0] + $i + 1));
				
				if( ($start_date <= date('Y-m-d')) && (date('Y-m-d') <= $end_date) ){
					$year_no = $i + 1;
					break;
				}
			}
		}
		
		// 年目に応じての処理
		//                     0～2年目          3～4年目      5年目以降
		// パスポート料金      0円               5000円        10000円
		// パスポート有無      有                無            無
		// パスポート有効期限  登録年月日から    購入月の      購入月の
		//                     2年間             翌年同月末    翌年同月末
		//
		if($year_no < 3){
			// 0～2年目
			$result['presence_passport'] = '1';
			$result['exp_date_passport'] = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2]-1, $array_date[0] + 2));
			$result['target_passport']   = '0円';
		}elseif( ($year_no==3) || ($year_no==4) ){
			// 3～4年目
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '5000円';
		}else{
			// 5年目以降
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '10000円';
		}
		
		return $result;
	}

	//----------------------------------------------
	// 新旧カテゴリ対比表の変換
	//----------------------------------------------
	function _get_category_convert($old_term_id){
		$new_term_id = "";

		foreach ($this->_term_convert as $k1 => $v1) {
			if ($k1 == $old_term_id) {
				$new_term_id = $v1;
				break;
			}
		}

		return $new_term_id;
	}

	//----------------------------------------------
	// 資料DLページURLから情報を取得
	//----------------------------------------------
	function _get_contents_download($product_code, $im_text_url, $web_index_flg = FALSE, $web_contents_flg = FALSE){
		$contents_download = array();

		// 元ファイル
		$file_path = '/alflearning-data/__import_data__/FILE/'.strtolower($product_code).'/';
		// $file_path = '/alflearning-data/__import_data__/FILE_REAL/'.strtolower($product_code).'/';
		@mkdir($file_path);
		@chmod($file_path, 0777);

		// ダミーファイル
		$file_path_dummy = '/alflearning-data/__import_data__/FILE/';
		$file_name_dummy = '16281873425254c5a962ded.jpg';

		// ハッシュファイル
		$file_path2 = '/alflearning-data/alfproduct/document/'.$product_code.'/';
		@system("rm -rf {$file_path2}"); // ディレクトリ削除
		@mkdir($file_path2);
		@chmod($file_path2, 0777);

		// index.htmlの取得方法
		$im_text_url_index = array();

		if ($web_index_flg) {
			// Webから
			$im_text_url_index[] = $im_text_url.'index.html';
		}
		else {
			// ファイルから
			$im_text_url_index[] = $file_path.'index.html';
			if ($web_contents_flg) {
				// Webから
				$im_text_url_index[] = $im_text_url.'index.html';
			}
		}

		foreach ($im_text_url_index as $k0 => $v0) {
			if ($html = @file_get_contents($v0)) {
				$html_base = $html;
				$html = mb_convert_encoding($html, "UTF-8", "sjis-win");

				// 文字列の整形
				// タブ・改行・Pタグなどを削除
				$html = ereg_replace("\t", "", $html);
				$html = ereg_replace("<br>\n", " ", $html);
				$html = ereg_replace("<\/p>([^<]+)(.*)\n", "</p>\\2", $html);
				$html = ereg_replace("<p>", "", $html);
				$html = ereg_replace("<\/p>", "", $html);

				$htmls = explode("\n", $html);
				$contents_download_before = "";
				foreach ($htmls as $html_key => $html_val) {
					// 論理ファイル名を取得
					preg_match_all('/<td>([^<]+)<\/td>/', $html_val, $mch);
					if ($mch[1]) {
						$contents_download_before = $mch[1][0];
						// echo $contents_download_before."\n";
						continue;
					}

					// ハイパーリンクを取得
					preg_match_all('/<a\s+href=("|\')([^"\'>]+)/', $html_val, $mch);
					if ($mch[2]) {
						$cnt = 0;
						foreach ($mch[2] as $k1 => $v1) {
							if (!eregi("JavaScript:", $v1) && $v1 != "/") {
								// 文字変換
								// $v1 = str_replace("〜", "～", $v1);
								// $v1 = str_replace("−", "－", $v1);

								// おかしい？？
								if ($product_code == "E10002") {
									if (eregi("E10001", $v1)) {
										continue;
									}
								}

								preg_match("/\/?([^\/]+)\.(.+)/is", $v1, $retArr);
								if (count($retArr) != 3) {
									echo "No File Error\n";
									// LOGの書き出し
									$this->_output_log(__FUNCTION__."_error_1.log", "[error] filename:".$v1);
									continue;
								}

								$file_name_before = str_replace("./", "", $v1);

								// 商品コードを小文字にする
								$file_name_before_lower = str_replace("./", "", str_replace($product_code, strtolower($product_code), $v1));

								$file_name_before_base = $retArr[1].".".$retArr[2];
								$extension = $retArr[2];
								$file_name = uniqid(rand()).'.'.$extension;

								// ファイルがあったら
								if (@file_exists($file_path.$file_name_before)) {
									if (!@file_exists($file_path."index.html")) {
										// index.html書き出し
										if ($fpw = @fopen($file_path."index.html", FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
											@fwrite($fpw, $html_base);
											@fclose($fpw);
											@chmod($file_path."index.html", 0777);
										}
									}

									@copy($file_path.$file_name_before, $file_path2.$file_name);
									@chmod($file_path2.$file_name, 0777);

									// echo $file_name_before_base." → Exists OK\n";
									echo $file_name_before_base."（".$contents_download_before.".".$extension."）→ Exists OK\n";

									$pa = array();
									$pa["contents_download"] = $product_code."/".$file_name;
									// $pa["contents_download_before"] = $file_name_before_base;
									$pa["contents_download_before"] = $contents_download_before.".".$extension;

									$contents_download[] = $pa;

									$cnt++;
								}
								elseif (@file_exists($file_path.$file_name_before_lower)) {
									if (!@file_exists($file_path."index.html")) {
										// index.html書き出し
										if ($fpw = @fopen($file_path."index.html", FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
											@fwrite($fpw, $html_base);
											@fclose($fpw);
											@chmod($file_path."index.html", 0777);
										}
									}

									@copy($file_path.$file_name_before_lower, $file_path2.$file_name);
									@chmod($file_path2.$file_name, 0777);

									// echo $file_name_before_base." → Exists OK\n";
									echo $file_name_before_base."（".$contents_download_before.".".$extension."）→ Exists OK\n";

									$pa = array();
									$pa["contents_download"] = $product_code."/".$file_name;
									// $pa["contents_download_before"] = $file_name_before_base;
									$pa["contents_download_before"] = $contents_download_before.".".$extension;

									$contents_download[] = $pa;

									$cnt++;
								}
								else {
									if ($web_contents_flg) {
										if (!@file_exists($file_path."index.html")) {
											// index.html書き出し
											if ($fpw = @fopen($file_path."index.html", FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
												@fwrite($fpw, $html_base);
												@fclose($fpw);
												@chmod($file_path."index.html", 0777);
											}
										}

										// ファイルが無かったらURLから取得する
										$contents = @file_get_contents($im_text_url.rawurlencode($file_name_before));

										// 階層があったらディレクトリ作成
										$file_name_before_2 = explode("/", $file_name_before);
										if (count($file_name_before_2) > 1) {
											foreach ($file_name_before_2 as $k2 => $v2) {
												if ($k2 < count($file_name_before_2) - 1) {
													@mkdir($file_path."/".$v2);
													@chmod($file_path, 0777);
												}
											}
										}

										if ($contents) {
											if ($fpw = @fopen($file_path.$file_name_before, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
												@fwrite($fpw, $contents);
												@fclose($fpw);

												@chmod($file_path.$file_name_before, 0777);

												@copy($file_path.$file_name_before, $file_path2.$file_name);
												@chmod($file_path2.$file_name, 0777);

												// echo $file_name_before_base." → Get OK\n";
												echo $file_name_before_base."（".$contents_download_before.".".$extension."）→ Get OK\n";

												$pa = array();
												$pa["contents_download"] = $product_code."/".$file_name;
												// $pa["contents_download_before"] = $file_name_before_base;
												$pa["contents_download_before"] = $contents_download_before.".".$extension;

												$contents_download[] = $pa;

												$cnt++;
											}
										}
									}
									else {
										echo "No File exists\n";
										// LOGの書き出し
										$this->_output_log(__FUNCTION__."_error_2.log", "[error] filename:".$file_path.$file_name_before);
										/*
										@copy($file_path_dummy.$file_name_dummy, $file_path2.$file_name_dummy);
										@chmod($file_path2.$file_name_dummy, 0777);

										echo $file_name_before_base." → No Exists But OK\n";
										echo $file_name_before_base."（".$contents_download_before.".".$extension."）→ No Exists But OK\n";

										$pa = array();
										$pa["contents_download"] = $product_code."/".$file_name_dummy;
										// $pa["contents_download_before"] = $file_name_before_base;
										$pa["contents_download_before"] = $contents_download_before.".".$extension;

										$contents_download[] = $pa;

										$cnt++;
										*/
									}
								}

								// if ($cnt > 1) { break; }
							}
						}

						continue;
					}
				}

				break;
			}
		}

		return $contents_download;
	}

	//----------------------------------------------
	// 新旧研修種別対比表
	//----------------------------------------------
	function _get_training_kind_convert($old_training_kind){
		$new_training_kind = "";

		foreach ($this->_training_kind_convert as $k1 => $v1) {
			if ($k1 == $old_training_kind) {
				$new_training_kind = $v1;
				break;
			}
		}

		return $new_training_kind;
	}

	//----------------------------------------------
	// 新旧受講対象フラグ対比表
	//----------------------------------------------
	function _get_target_flg_convert($old_target_flg){
		$new_target_flg = "";

		foreach ($this->_target_flg_convert as $k1 => $v1) {
			if ($k1 == $old_target_flg) {
				$new_target_flg = $v1;
				break;
			}
		}

		return $new_target_flg;
	}

	//----------------------------------------------
	// 新旧弁護士会ID対比表
	//----------------------------------------------
	function _get_bar_association_id_convert($old_bar_association_id){
		$new_bar_association_id = "";

		foreach ($this->_bar_association_id_convert as $k1 => $v1) {
			if ($k1 == $old_bar_association_id) {
				$new_bar_association_id = $v1;
				break;
			}
		}

		if (strlen($new_bar_association_id) > 0) {
			$new_bar_association_id = explode("," , $new_bar_association_id);
		}
		else {
			$new_bar_association_id = array();
		}

		return $new_bar_association_id;
	}

	//----------------------------------------------
	// 新弁護士会IDと弁護士会支部IDの紐づき
	//----------------------------------------------
	function _get_bar_association_branch_id_convert($old_bar_association_branch_id){
		$new_bar_association_branch_id = array();

		foreach ($this->_bar_association_branch_id_convert as $k1 => $v1) {
			foreach ($old_bar_association_branch_id as $k2 => $v2) {
				if ($v1 == $v2) {
					if (!in_array($k1 , $new_bar_association_branch_id)) {
						$new_bar_association_branch_id[] = $k1;
					}
				}
			}
		}

		return $new_bar_association_branch_id;
	}

	//----------------------------------------------
	// 秒数を時間型に変換
	//----------------------------------------------
	public function _sec_to_time_convert($sec) {
		return gmdate('H:i:s', $sec);
	}

	//----------------------------------------------
	// 時間型を秒数に変換
	//----------------------------------------------
	public function _time_to_sec_convert($time) {
		$t = explode(":", $time);
		$h = $t[0];
		if (isset($t[1])) {
			$m = $t[1];
		} else {
			$m = "0";
		}
		if (isset($t[2])) {
			$s = $t[2];
		} else {
			$s = "0";
		}

		return ($h*60*60) + ($m*60) + $s;
	}

	//----------------------------------------------
	// DB取込用ID変換履歴テーブルへ
	//----------------------------------------------
	function _convert_id_list($id_kind, $old_id, $new_id, $del_flg = TRUE) {
		if ($del_flg) {
			// DELETE convert_id_list
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = ? AND (old_id = ? OR new_id = ?)", array(
								$id_kind,
								$old_id,
								$new_id,
							));
		}
		else {
			// DELETE convert_id_list
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = ? AND new_id = ?", array(
								$id_kind,
								$new_id,
							));
		}

		// INSERT convert_id_list
		$this->db->query("INSERT INTO convert_id_list (id_kind, old_id, new_id, update_at) VALUES (?,?,?,?)", array(
							$id_kind,
							$old_id,
							$new_id,
							date("Y/m/d H:i:s"),
						));
	}

	//----------------------------------------------
	// LOGのクリア
	//----------------------------------------------
	function _clear_log($filename) {
		if (strlen($filename) > 0) {
			$log_path = '/alflearning-data/__import_data__/LOG/'.$filename;
			if (@file_exists($log_path)) {
				@system("rm -rf {$log_path}");
			}
		}
	}

	//----------------------------------------------
	// LOGの書き出し
	//----------------------------------------------
	function _output_log($filename, $message) {
		if (strlen($filename) > 0) {
			$log_path = '/alflearning-data/__import_data__/LOG/'.$filename;
			if (!@file_exists($log_path)) {
				if ($fpw = @fopen($log_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
					@fputs($fpw, $message."\n");
					@fclose($fpw);
					@chmod($log_path, 0777);
				}
			}
			else {
				if ($fpa = @fopen($log_path, FOPEN_WRITE_CREATE)) {
					@fputs($fpa, $message."\n");
					@fclose($fpa);
				}
			}
		}
	}
}
?>
