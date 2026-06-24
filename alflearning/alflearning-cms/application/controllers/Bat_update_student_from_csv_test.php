<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

#[AllowDynamicProperties]
class Bat_update_student_from_csv_test extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
//	public $_target_path              = '/alflearning-data/__import_data__/CSV/';	// ファイル格納場所
//	public $_target_file_name         = 'MS_USER.csv';								// 対象ファイル名（タブ区切り注意）
	
//	public $_convert_log_file         = '/alflearning-data/__import_data__/LOG/MS_USER_to_student.txt';
	
  //public $_target_path              = '/alflearning-data/data_import/takasumi_test/';
	
	// 使用テーブル名
	public $_table_student             = 'student';  //'student_batch_test';
	public $_table_student_lecture     = 'student_lecture_batch_test';

	
	// 弁護士会マスタ（mtb_bar_association）にない弁護士会コード変換表
	public $_etc_layer_number = array(
							61=>1,		// 関東弁護士会連合会        -> 日本弁護士連合会
							62=>1,		// 近畿弁護士会連合会        -> 日本弁護士連合会
							63=>1,		// 中部弁護士会連合会        -> 日本弁護士連合会
							64=>1,		// 中国地方弁護士会連合会    -> 日本弁護士連合会
							65=>1,		// 九州弁護士会連合会        -> 日本弁護士連合会
							66=>1,		// 東北弁護士会連合会        -> 日本弁護士連合会
							67=>1,		// 北海道弁護士会連合会      -> 日本弁護士連合会
							68=>1,		// 四国弁護士会連合会        -> 日本弁護士連合会
							69=>1,		// NULL                      -> 日本弁護士連合会
							70=>6,		// 横浜弁護士会 県西支部     -> 横浜
							71=>6,		// 横浜弁護士会 川崎支部     -> 横浜
							72=>6,		// 横浜弁護士会 相模原支部   -> 横浜
							73=>7,		// 埼玉県弁護士会 越谷支部   -> 埼玉
							74=>7,		// 埼玉県弁護士会 熊谷支部   -> 埼玉
							75=>7,		// 埼玉県弁護士会 川越支部   -> 埼玉
							76=>12,		// 静岡県弁護士会 沼津支部   -> 静岡県
							77=>12,		// 静岡県弁護士会 浜松支部   -> 静岡県
							78=>14,		// 長野県弁護士会 諏訪在住会 -> 長野県
							79=>18,		// 兵庫県弁護士会 姫路支部   -> 兵庫県
							80=>28,		// 広島弁護士会 福山地区会   -> 広島
							81=>29,		// 山口県弁護士会 宇部地区会 -> 山口県
							82=>29,		// 山口県弁護士会 下関地区   -> 山口県
							83=>29,		// 山口県弁護士会 岩国地区会 -> 山口県
							84=>29,		// 山口県弁護士会 周南地区   -> 山口県
							85=>31,		// 鳥取県弁護士会 米子支部   -> 鳥取県
							86=>33,		// 福岡県弁護士会 筑後部会   -> 福岡県
							87=>33,		// 福岡県弁護士会 北九州部会 -> 福岡県
							88=>42,		// 福島県弁護士会 郡山支部   -> 福島県
							89=>31,		// 鳥取県弁護士会 倉吉支部   -> 鳥取県
							90=>12,		// 静岡県弁護士会 県西支部   -> 静岡県
							99=>1,		// webmaster                 -> 日本弁護士連合会
	);
	
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		// テスト時使用。
		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// CSVファイル→studentテーブル格納処理
	//----------------------------------------------
	public function update_student_from_csv_test(){
		exec('ps auxw | grep update_student_from_csv_test | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo var_dump($outputs);
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		print "[".date('Y-m-d H:i:s')."]"."START update_student_from_csv"."\n";
		
		// 定数の設定
		$_target_path = $this->config->item('student_dir');  // CSVファイル格納場所（/alflearning-data/student）
		
		// 対象ファイル名の取得
		$_target_file_name_array = array();
		if ($dir = opendir($_target_path)) {
			while (($file = readdir($dir)) !== false) {
				if ($file != "." && $file != "..") {
					// 管理画面からアップロードしたファイル名のみ取得
					if (preg_match("/^upload_\d{8}_\d{6}\.csv$/", $file)) {
						$_target_file_name_array[] = $file;
					}
				}
			}
			closedir($dir);
		  //sort($_target_file_name_array);     // 昇順
			rsort($_target_file_name_array);    // 降順（直近でアップロードしたファイル名を先頭にする）
		}
		
		// ファイル名ループ
		for ($i=0 ; $i < count($_target_file_name_array) ; $i++) {
			$_target_file_name = $_target_file_name_array[$i];
			
			// ファイル名先頭以降は処理対象外のため、ファイル物理削除＋continue
			if($i>0){
				if(is_file($_target_path.'/'.$_target_file_name)){
					unlink($_target_path.'/'.$_target_file_name);
				}
				continue;
			}
			
			// ファイル存在確認（なければ次配列へ）
			if(!is_file($_target_path.'/'.$_target_file_name)){
				print "[".date('Y-m-d H:i:s')."]"."ファイル：".$_target_path.'/'.$_target_file_name." が見つかりません"."\n";
			  //$this->_output_log("ファイル：".$_target_path.$_target_file_name." が見つかりません");
			  //return;
				continue;
			}else{
				print "[".date('Y-m-d H:i:s')."]"."ファイル：".$_target_path.'/'.$_target_file_name." 処理開始"."\n";
			  //$this->_output_log("ファイル：".$_target_path.$_target_file_name." 処理開始");
			}
			
			// 定数の指定：studentテーブル、update_at に格納する日時（処理ファイル単位）
			$_standard_update_at = date('Y-m-d H:i:s');
			
			// CSV取込処理
			$fp = fopen($_target_path.'/'.$_target_file_name, 'r');
			$counter = -1;
			$update_counter = 0;
			while(!feof($fp)){
				$counter = $counter + 1;
				
				$arrayRecord = $this->fgetcsv($fp,  filesize($_target_path.'/'.$_target_file_name), ",");
				
				// CSVファイル末尾の配列要素数は1になるので、処理を飛ばす
				// 通常は使用通りの場合、46になる。
				if(count($arrayRecord)==1){
					continue;
				}
				// [0]登録番号         [1]会員区分         [2]弁護士会コード   ･･･
				// [5]氏かな           [6]名かな           ･･･
				// [13]公開氏かな      [14]公開名かな      [15]氏外字なし     [16]名外字なし  ･･･
				// [21]公開氏外字なし  [22]公開名外字なし  ･･･
				// [44]登録年月日
				
				$_convert = array();
				
				// 各データの変換
				$_convert['lawyer_number']      = trim($arrayRecord[0]);     // 登録番号（弁護士番号）
				$_convert['lawyer_division']    = trim($arrayRecord[1]);     // 会員区分
				$_convert['bar_association_id'] = trim($arrayRecord[2]);     // 弁護士会ID（SSO側）
				$_convert['regist_date']        = trim($arrayRecord[44]);    // 登録年月日
				
				// 登録番号（値なしは対象外）
				if($_convert['lawyer_number']==''){
					$this->_output_error_log(array(
						'val'             => '登録番号なし',
						'local_file_name' => $_target_file_name,
						'line_no'         => $counter + 1,
					));
					continue;
				}
				// 弁護士会コード
				// [事務員対応] 値なし、且つ、登録番号が６桁、且つ会員区分値なしの場合、登録番号２桁目～３桁目を使用。
				// 値なし、且つ、上記条件以外はエラー。
				if($_convert['bar_association_id'] == ''){
					if( (mb_strlen($_convert['lawyer_number']) == 6) && ($_convert['lawyer_division'] == '') ){
						$temp_code = (int)substr($_convert['lawyer_number'], 1, 2);	// 2～3桁目取得
						$_convert['bar_association_id'] = (string)$temp_code;
					}else{
						$this->_output_error_log(array(
							'val'             => '弁護士会コードなし',
							'local_file_name' => $_target_file_name,
							'line_no'         => $counter + 1,
						));
						continue;
					}
				}
				// SSO側所属弁護士会から、システム側弁護士会IDを取得（取得失敗時は文字列ゼロ）
				$temp_bar_association_id = $_convert['bar_association_id'];
				$_convert['bar_association_id'] = $this->_get_bar_association_id($_convert['bar_association_id']);
				if($_convert['bar_association_id'] == ''){
					$this->_output_error_log(array(
						'val'             => '弁護士会コード変換失敗 (CSV側所属弁護士会ID = '.$temp_bar_association_id.')',
						'local_file_name' => $_target_file_name,
						'line_no'         => $counter + 1,
					));
					continue;
				}
				// 登録年月日
				//   [事務員対応] 値なし、且つ、登録番号が６桁、且つ会員区分値なしの場合システム日付（yyyymmdd型）を使用。
				//   値なし、且つ、上記条件以外はエラー。
				//   値あり、且つ、タイムスタンプ変換できない場合はエラー。
				if($_convert['regist_date'] == ''){
					if( (mb_strlen($_convert['lawyer_number']) == 6) && ($_convert['lawyer_division'] == '') ){
						$_convert['regist_date'] = date("Ymd");
					}else{
						$this->_output_error_log(array(
							'val'             => '登録年月日なし',
							'local_file_name' => $_target_file_name,
							'line_no'         => $counter + 1,
						));
						continue;
					}
				}elseif( !strtotime($_convert['regist_date']) ){
					$this->_output_error_log(array(
						'val'             => '登録年月日、不正年月日 (CSV側登録年月日 = '.$_convert['regist_date'].')',
						'local_file_name' => $_target_file_name,
						'line_no'         => $counter + 1,
					));
					continue;
				}
				
				if(  (!trim($arrayRecord[21])=='') && (!trim($arrayRecord[22])=='') && (!trim($arrayRecord[13])=='') && (!trim($arrayRecord[14])=='')  ){
					// 公開氏外字なし・公開名外字あり・公開氏かな・公開名かな、全てがある場合
					$_convert['student_name']      = $arrayRecord[21].' '.$arrayRecord[22];    // 生徒氏名
					$_convert['student_name_kana'] = $arrayRecord[13].' '.$arrayRecord[14];    // 生徒氏名カナ
				}elseif(  (!trim($arrayRecord[15])=='') && (!trim($arrayRecord[16])=='') && (!trim($arrayRecord[5])=='') && (!trim($arrayRecord[6])=='')  ){
					// 公開氏外字なし・公開名外字あり・公開氏かな・公開名かな、全てがある場合
					$_convert['student_name']      = $arrayRecord[15].' '.$arrayRecord[16];    // 生徒氏名
					$_convert['student_name_kana'] = $arrayRecord[5].' '.$arrayRecord[6];      // 生徒氏名カナ
				}else{
					$_convert['student_name']      = '氏 名';    // 生徒氏名
					$_convert['student_name_kana'] = 'し めい';  // 生徒氏名カナ
					
				}
				$_convert['student_email']            = '';                                                   // メールアドレス
				$_convert['student_password']         = $this->config->item('nichibenren_student_password');  // ログインパスワード
				$_convert['student_password_encrypt'] = hash('sha256', $_convert['student_password']);        // 暗号化パスワード
				$_convert['school_id']                = $this->config->item('nichibenren_school_id');         // 学校ID
				$_convert['status']                   = 0;                                                    // 状態
				$_convert['update_at']                = $_standard_update_at;                                 // 更新日時
				$_convert['regist_at']                = date('Y/m/d H:i:s');                                  // 入会日時
				$_convert['delete_at']                = '0000-00-00 00:00:00';                                // 退会日時
				$_convert['mailmagazine_flg']         = 0;                                                    // 0:メルマガ拒否
			  //$_convert['lawyer_number']            = $arrayRecord[0];                                      // 登録番号（弁護士番号）
			  //$_convert['lawyer_division']          = $arrayRecord[1];                                      // 会員区分
			  // SSO側所属弁護士会から、システム側弁護士会IDを取得（取得失敗時は文字列ゼロ）
			  //$_convert['bar_association_id'] = $this->_get_bar_association_id($_convert['bar_association_id']);
				
				// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得	
				//$_convert['regist_date'] = '';
				//$_convert['regist_date'] = trim($arrayRecord[44]);	// 登録年月日の変換（TRIM・yyyy-mm-dd変換）
				//if($_convert['regist_date'] != ''){
				//  if(strtotime( $_convert['regist_date'] )){
						$_convert['regist_date'] = date('Y-m-d', strtotime($_convert['regist_date']));
				//  }
				//}
				$temp_param = $this->_get_target_passport($_convert['regist_date']);
				$_convert['target_passport']   = $temp_param['target_passport'];      // 対象パスポート
				$_convert['presence_passport'] = $temp_param['presence_passport'];    // パスポートの有無 0:なし、1:あり
				$_convert['exp_date_passport'] = $temp_param['exp_date_passport'];    // パスポートの有効期限
				
				$_convert['sub_auth_ethic_training'] = 0;    // 代替倫理研修権限 0:禁止、1:許可

				try{
					$this->db->trans_start();
				//	$this->db->trans_begin();

					$query = $this->db->query("
						SELECT 
						 student_id 
						FROM 
						 student 
						WHERE 
						     lawyer_number='".$_convert['lawyer_number']."' 
						 AND lawyer_division='".$_convert['lawyer_division']."' 
						ORDER BY 
						 student_id ASC 
						LIMIT 1 '
					");
					if ($query->num_rows() > 0) {
						$temp_row = $query->row_array();
						$temp_student_id = $temp_row["student_id"];

						// 受講者テーブルの更新
						$res_student_update = $this->db->query($this->db->update_string($this->_table_student, array(
									'bar_association_id' => $_convert['bar_association_id'],    // 弁護士会ID
									'student_name'       => $_convert['student_name'],          // 氏名
									'student_name_kana'  => $_convert['student_name_kana'],     // 氏名かな
									'status'             => 0,                                  // 状態（有効化にする）
									'update_at'          => $_convert['update_at'],             // 更新日時
							),"student_id='".$temp_student_id."' AND lawyer_number='".$_convert['lawyer_number']."' AND lawyer_division='".$_convert['lawyer_division']."'"
						));
						// 更新した行数、0の場合対象なし
						// なお、ゼロの場合は更新対象値が全て同じ値の場合も出力される。今回は更新日時が必ずシステム日時になるため、ゼロ＝変更行なしと判断できる
						$result_count = $this->db->affected_rows();
						if($result_count > 0){
							// 受講者講座テーブルの更新
							if( ($counter + 1) % 5000 == 0 ) {
								print "[".date('Y-m-d H:i:s')."][LINE:".sprintf("%' 5d", $counter + 1)."] UPDATE student"."\n";
							}
						}
					} else {
						//-----------------
						//20180604 暫定対応
						//$_convert['status'] = 9;
						//-----------------
						// 受講者テーブルの新規登録（更新失敗時）
						$res = $this->db->query($this->db->insert_string($this->_table_student, array(
									'student_name'              => $_convert['student_name'],
									'student_email'             => $_convert['student_email'],
									'student_password'          => $_convert['student_password'],
									'student_password_encrypt'  => $_convert['student_password_encrypt'],
									'school_id'                 => $_convert['school_id'],
									'status'                    => $_convert['status'],
									'update_at'                 => $_convert['update_at'],
									'regist_at'                 => $_convert['regist_at'],
									'delete_at'                 => $_convert['delete_at'],
									'student_name_kana'         => $_convert['student_name_kana'],
									'mailmagazine_flg'          => $_convert['mailmagazine_flg'],
									'lawyer_number'             => $_convert['lawyer_number'],
									'lawyer_division'           => $_convert['lawyer_division'],
									'bar_association_id'        => $_convert['bar_association_id'],
									'regist_date'               => $_convert['regist_date'],
									'target_passport'           => $_convert['target_passport'],
									'presence_passport'         => $_convert['presence_passport'],
									'exp_date_passport'         => $_convert['exp_date_passport'],
									'sub_auth_ethic_training'   => $_convert['sub_auth_ethic_training'],
						)));
						$lastInsertId = $this->db->insert_id();
						
						if( ($counter + 1) % 5000 == 0 ) {
							print "[".date('Y-m-d H:i:s')."][LINE:".sprintf("%' 5d", $counter + 1)."] INSERT student"."\n";
						}
					}
					$this->db->trans_complete();
					
					$update_counter = $update_counter + 1;
					
					// 0.5 秒待つ（CSV1行単位でUPDATE・INSERT後に入れる）
					usleep(500000);
					
				}catch(Exception $e){ 
					$this->db->trans_rollback();
					//$this->_output_log("取込処理例外発生");
				}
			}
			fclose($fp);
			
			sleep(1);
			
			// CSV登録・更新が行われない受講者レコードのstatusを更新する。
			
// 条件1 : 更新日時が古い
// 条件2 : 会員区分が「1～5」のもの（管理画面、受講者画面にある「会員区分」のその他を除外する）
// 会員区分  0：条件に入れない。1～5：条件検索、999：1～5以外
			
			
			try{
				$this->db->trans_start();
				
				// 受講者テーブルの更新（更新日時より古い受講者レコードのstatusを無効（9）に変更
				//$res_student_status = $this->db->query($this->db->update_string($this->_table_student, array(
				//			'status'    => 9,
				//			'update_at' => $_standard_update_at,
				//	),'update_at < '."'".$_standard_update_at."'"
				//));
				$res_student_status = $this->db->query($this->db->update_string($this->_table_student, array(
							'status'    => 9,
							'update_at' => $_standard_update_at,
					),'update_at < '."'".$_standard_update_at."'"." AND lawyer_division IN ('1', '2', '3', '4', '5') "
				));
				
				// 更新レコード数を取得
				$status_update_count = $this->db->affected_rows();

				$this->db->trans_complete();
			}catch(Exception $e){ 
				$this->db->trans_rollback();
				//$this->_output_log("取込処理例外発生");
			}
			
			
			
			// お知らせテーブルから情報取得＋お知らせテーブルに情報登録
			try{
				$query = $this->db->query("
					SELECT * 
					  FROM notification 
					 WHERE notice_kind = 'cms-student-csv-upload' AND notice_caption LIKE '%".$_target_file_name."%'
				");
				
				if ($query->num_rows() > 0) {
					$notification_data = $query->row_array();
					
					// アップロード日時の取得（サーバにアップロードしたファイル名から抽出）upload_20140318_222329.csv
					//preg_match('/^upload_(\d{8})_(\d{6}).csv$/', $_target_file_name, $upload_datetime);
					//$update_date = substr($upload_datetime[1],0,4)."/".substr($upload_datetime[1],4,2)."/".substr($upload_datetime[1],6,2);
					//$update_time = substr($upload_datetime[2],0,2).":".substr($upload_datetime[2],2,2).":".substr($upload_datetime[2],4,2);
					
					// ファイル単位で処理終了したシステム日時を取得
					$update_date = date('Y/m/d');
					$update_time = date('H:i:s');
					
					// アップロードを行った学校IDの取得
					$school_id = 1;
					if($notification_data['school_id'] > 0){
						$school_id = $notification_data['school_id'];
					}
					
					// アップロードを行った講師IDの取得
					$teacher_id = 1;
					if($notification_data['teacher_id']>0){
						$teacher_id = $notification_data['teacher_id'];
					}
					
					// アップロードしたファイルの元名
					$notice_caption = $notification_data['notice_caption'];
					$temp = explode(",", $notice_caption);
					$user_update_file_name = '';
					if(trim($temp[0])!=''){
						$user_update_file_name = $temp[0];
					}
					
					$this->load->model('model_notification');
					$notice_result = $this->model_notification->insert_notification(array(
						'notice_kind'    => 'bat_update_student_from_csv',
						'id'             => 0,
						'notice_judge'   => "OK",
						'school_id'      => $school_id,
						'teacher_id'     => $teacher_id,
						
						'update_date'           => $update_date,
						'update_time'           => $update_time,
						'user_update_file_name' => $user_update_file_name,
						
						'csv_counter'           => $counter,                    // CSV総件数
						'csv_error_counter'     => $counter - $update_counter,  // CSV総件数 - INSERT・UPDATE件数 = CSV不具合件数
						'update_counter'        => $update_counter,             // CSVによる更新件数
						//'update_counter'        => $update_counter + $status_update_count,  // CSV側更新件数 + status無効化件数
						//'counter'               => $counter,
						//'update_counter'        => $update_counter,
					));
				}
			}catch(Exception $e){ 
			}
			
			// CSVファイルのリネーム
			$before_name = $_target_path.'/'.$_target_file_name;
			$temp        = explode(".", $_target_file_name);
			$after_name  = $_target_path.'/'.$temp[0].'_executed_'.date('Ymd_His').'.'.$temp[1];
		  //$after_name  = $_target_path.'/'.'end_'.date('Ymd_His_').$_target_file_name;
			$rename_result = rename($before_name , $after_name);
		}	
		
		// 終了ログ
		print "[".date('Y-m-d H:i:s')."]"."END update_student_from_csv"."\n";
	}

	//----------------------------------------------
	// ファイルから1行取得＋カンマ区切からの配列変換
	//----------------------------------------------
	function fgetcsv (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$eof = false;
		while ($eof != true) {
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
		
		// csv・tsv の場合、末尾が文字列ゼロの場合、カラム数削減が起こるため、末尾に特定文字を入れて配列数を維持
		$_csv_line .= $d."[EOF]";
		
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}
		mb_convert_variables("UTF-8", "SJIS-win", $_csv_data);
		return empty($_line) ? false : $_csv_data;
	}

	//----------------------------------------------
	// SSO側所属弁護士会から、システム側弁護士会IDを取得
	//----------------------------------------------
	function _get_bar_association_id($bar_association = ''){
		$bar_association_id = '';
		
		try{ 
			$query = $this->db->query(
				' SELECT mtb_bar_association.* '.
				'   FROM mtb_bar_association'.
				'  WHERE 1=1'.
				'    AND mtb_bar_association.sso_id = ? '.
				'  LIMIT 0, 1',
				array(
					trim($bar_association),
				)
			);
			
			if ($query->num_rows() > 0) {
				$bar_association    = $query->row_array();		// 複数はresult_array
				$bar_association_id = $bar_association['id'];
			}else{
				if (array_key_exists($bar_association, $this->_etc_layer_number)) {
					$bar_association_id = $this->_etc_layer_number[$bar_association];
				}
			  //$bar_association_id = $this->_etc_layer_number[$bar_association];
			}
		}catch(Exception $e){ 
			$bar_association_id = '';
		}
		
		return $bar_association_id;
	}

	//----------------------------------------------
	// ×登録年月日から、パスポート料金を取得
	// 登録年月日（yyyymmdd）から、パスポートの有無・パスポートの有効期限・パスポート料金を取得
	//----------------------------------------------
	function _get_target_passport($regist_date = ''){
		// 戻り値の初期化
		$result['presence_passport'] = '';		// パスポートの有無（有⇒1、無⇒0）
		$result['exp_date_passport'] = '';		// パスポートの有効期限
		$result['target_passport']   = '';		// パスポート料金
	
		// 登録年月日の正誤確認
		$regist_date = trim($regist_date);
	  //$regist_date = preg_replace("/[\/]/", "-", $regist_date);
		if($regist_date == ''){
			return $result;
		}else{
			// タイムスタンプ変換確認
			if(!strtotime($regist_date)){
				return $result;
			}
		
			$regist_date = date('Y-m-d', strtotime($regist_date));
		
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
			for( $i=0; $i<=5; $i++ ){
				$start_date = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2],     $array_date[0] + $i));
			//	$end_date   = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2], $array_date[0] + $i + 1));
				
			//	if( ($start_date <= date('Y-m-d')) && (date('Y-m-d') < $end_date) ){
				if(  $start_date > date('Y-m-d') ){
					$year_no = $i;
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
		if($year_no < 3){		//3
			// 0～2年目
			$result['presence_passport'] = '1';
			$result['exp_date_passport'] = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2]-1, $array_date[0] + 2));
			$result['target_passport']   = '0円';
		}elseif( ($year_no==3) || ($year_no==4)  || ($year_no==5) ){	//3 4
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
/*
	function _get_target_passport($regist_date = ''){
		// 戻り値の初期化
		$result['presence_passport'] = '';		// パスポートの有無（有⇒1、無⇒0）
		$result['exp_date_passport'] = '';		// パスポートの有効期限
		$result['target_passport']   = '';		// パスポート料金
	
		// 登録年月日の正誤確認
		$regist_date = trim($regist_date);
	  //$regist_date = preg_replace("/[\/]/", "-", $regist_date);
		if($regist_date == ''){
			return $result;
		}else{
			// タイムスタンプ変換確認
			if(!strtotime($regist_date)){
				return $result;
			}
		
			$regist_date = date('Y-m-d', strtotime($regist_date));
		
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
*/

	//----------------------------------------------
	// ログファイル出力＋画面出力
	//----------------------------------------------
	function _output_error_log($param){
		//引数設定
		$param = array_merge(
						array(
							'val'             => '',    // 出力内容
							'local_file_name' => '',    // アップロード保存したCSVファイル名（upload_yyyymmdd_hhmmss.csv）
							'line_no'         => 0,     // CSVファイル内行番号
						),
						$param
					);
		
		$student_dir = $this->config->item('student_dir');  // CSVファイル格納場所（/alflearning-data/student）
		
		$temp     = explode(".", $param['local_file_name']);
		$log_file = $student_dir.'/'.$temp[0].'_error_list.log';

		try{
			if(!is_file($log_file)){
				write_file($log_file, "\n", 'a+');
				chmod($log_file, 0777);
			}
			$val = "[".date('Y-m-d H:i:s')."][LINE:".sprintf("%' 5d", $param['line_no'])."]".$param['val'];
			
			print $val."\n";
			write_file($log_file, $val."\n", 'a+');
			
			return true;
		}catch(Exception $e){ 
			return false;
		}
	}
	

	//----------------------------------------------
	// ログファイル出力＋画面出力 → ファイル出力を停止
	//----------------------------------------------
/*
	function _output_log($val = ""){
		try{
			if(!is_file($this->_convert_log_file)){
				write_file($this->_convert_log_file, "\n", 'a+');
				chmod($this->_convert_log_file, 0777);
			}
			if($val==""){
			}else{
				$val = "[".date('Y-m-d H:i:s')."]".$val;
			}
			
			print $val."\n";
			write_file($this->_convert_log_file, $val."\n", 'a+');
			
			return true;
		}catch(Exception $e){ 
			return false;
		}
	}
*/

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}

