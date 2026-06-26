<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

#[AllowDynamicProperties]
class Bat_oneoff_import_student extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
	public $_target_path              = '/alflearning-data/__import_data__/CSV/';	// ファイル格納場所
	public $_target_file_name         = 'MS_USER.csv';								// 対象ファイル名（タブ区切り注意）
	
	public $_convert_log_file         = '/alflearning-data/__import_data__/LOG/MS_USER_to_student.txt';
	
  //public $_target_path              = '/alflearning-data/data_import/takasumi_test/';
	
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
	// CSV→DB テーブル格納処理
	//----------------------------------------------
	public function oneoff_import(){
		exec('ps auxw | grep oneoff_import | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		$this->_output_log("START oneoff_import");

		// 対象テーブル内容削除
		try{
			$this->db->trans_start();
			$this->db->query("TRUNCATE TABLE student ");
			
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'student' ");
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'bar_association_student' ");
			
			$this->_output_log("対象テーブルの内容初期化、成功");
		}catch(Exception $e){ 
		//	$this->db->trans_rollback();
			$this->_output_log("対象テーブルの内容初期化、例外エラー");
		}
		
		// ファイル存在確認
		if(!is_file($this->_target_path.$this->_target_file_name)){
			$this->_output_log("ファイル：".$this->_target_path.$this->_target_file_name." が見つかりません");
			return;
		}
		
		// CSV取込処理
		$fp = fopen($this->_target_path.$this->_target_file_name, 'r');
		$counter = 0;
		while(!feof($fp)){
			$arrayRecord = $this->fgetcsv($fp,  filesize($this->_target_path.$this->_target_file_name), "\t");
			
			// 配列先頭が数字でない場合は次レコード（物理項目名）
			if(!is_numeric($arrayRecord[0])){
				continue;
			}else{
			// 数字の場合、取込対象レコードとして取込処理
				
				try{
				  //$this->load->database();
					$this->db->trans_start();
					
					$_convert = array();
					
					// 各データの変換
					if(trim($arrayRecord[18])=='') $arrayRecord[18] = '氏';
					if(trim($arrayRecord[19])=='') $arrayRecord[19] = '名';
					$_convert['student_name'] = $arrayRecord[18].' '.$arrayRecord[19];			// 生徒氏名
					
					$_convert['student_email'] = '';
					if($arrayRecord[22]!='') $_convert['student_email'] = $arrayRecord[22];		// メールアドレス
					
					$_convert['student_password']         = $arrayRecord[7];					// ログインパスワード
					$_convert['student_password_encrypt'] = hash('sha256', $arrayRecord[7]);	// 暗号化パスワード

					$_convert['school_id'] = $this->config->item('nichibenren_school_id');		// 学校ID

					// DEL_DATE 未設定ならば状態=0。設定ありならば状態=9。
					$_convert['status']    = 0;
					if(trim($arrayRecord[3])!='') $_convert['status'] = 9;
				  //$_convert['status']    = 0;													// 状態
					
					// 最終更新日時（LAST_UPDATE）使用不可ならばシステム日時
					if( strtotime( $arrayRecord[5] )){
						$_convert['update_at'] = date('Y-m-d', strtotime($arrayRecord[5]));
					}else{
						$_convert['update_at'] = date('Y/m/d H:i:s');	// 更新日時
					}
					
					$_convert['regist_at'] = date('Y/m/d H:i:s');		// 入会日時
					
					$_convert['delete_at'] = '0000-00-00 00:00:00';
					if(trim($arrayRecord[3])!='') $_convert['delete_at'] = $arrayRecord[3];		// 退会日時
					
					if(trim($arrayRecord[20])=='') $arrayRecord[20] = 'し';
					if(trim($arrayRecord[21])=='') $arrayRecord[21] = 'めい';
					$_convert['student_name_kana'] = $arrayRecord[20].' '.$arrayRecord[21];		// 生徒氏名カナ
					
					$_convert['mailmagazine_flg'] = 0;
					if($arrayRecord[16]=='0') $_convert['mailmagazine_flg'] = 1;
					if($arrayRecord[16]=='1') $_convert['mailmagazine_flg'] = 0;
				  //$_convert['mailmagazine_flg'] = $arrayRecord[16];		// ( 0：受信する 1：受信しない ) → 0:メルマガ拒否 1:メルマガ許可
					
					$_convert['lawyer_number']    = $arrayRecord[6];	// 登録番号（弁護士番号）
					$_convert['lawyer_division']  = $arrayRecord[14];	// 会員区分
					
					// SSO側所属弁護士会から、システム側弁護士会IDを取得（取得失敗時は文字列ゼロ）
					$_convert['bar_association_id'] = $this->_get_bar_association_id($arrayRecord[17]);
					
					// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得	
					$_convert['regist_date'] = '';
					$_convert['regist_date'] = trim($arrayRecord[1]);	// 登録年月日の変換（TRIM・yyyy-mm-dd変換）
					if($_convert['regist_date'] != ''){
						if(strtotime( $_convert['regist_date'] )){
							$_convert['regist_date'] = date('Y-m-d', strtotime($_convert['regist_date']));
						}
					}
					$temp_param = $this->_get_target_passport($_convert['regist_date']);
					$_convert['target_passport']   = $temp_param['target_passport'];		// 対象パスポート
					$_convert['presence_passport'] = $temp_param['presence_passport'];		// パスポートの有無 0:なし、1:あり
					$_convert['exp_date_passport'] = $temp_param['exp_date_passport'];		// パスポートの有効期限
					
					$_convert['sub_auth_ethic_training'] = 0;		// 代替倫理研修権限 0:禁止、1:許可
					if($arrayRecord[13]=='1') $_convert['sub_auth_ethic_training'] = 1;

/*
print var_dump($arrayRecord);
print "\n";
print var_dump($_convert);
print "\n";

$counter = $counter + 1;
if($counter == 20){
	exit();
}
*/

					
					//新規
					$sql = "INSERT INTO student (
								  student_name 
								 ,student_email 
								 ,student_password
								 ,student_password_encrypt 
								 ,school_id 
								 ,status 
								 ,update_at 
								 ,regist_at 
								 ,delete_at 
								 ,student_name_kana 
								 ,mailmagazine_flg 
								 ,lawyer_number 
								 ,lawyer_division 
								 ,bar_association_id 
								 ,regist_date 
								 ,target_passport 
								 ,presence_passport 
								 ,exp_date_passport 
								 ,sub_auth_ethic_training 
							) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					
					$this->db->trans_begin();
					
					// INSERT student
					$this->db->query($sql, array(
										$_convert['student_name'],
										$_convert['student_email'],
										$_convert['student_password'],
										$_convert['student_password_encrypt'],
										$_convert['school_id'],
										$_convert['status'],
										$_convert['update_at'],
										$_convert['regist_at'],
										$_convert['delete_at'],
										$_convert['student_name_kana'],
										$_convert['mailmagazine_flg'],
										$_convert['lawyer_number'],
										$_convert['lawyer_division'],
										$_convert['bar_association_id'],
										$_convert['regist_date'],
										$_convert['target_passport'],
										$_convert['presence_passport'],
										$_convert['exp_date_passport'],
										$_convert['sub_auth_ethic_training']
									));
					
					// 登録したstudent_idを取得
					$temp_insert_id = $this->db->insert_id();
					
					// DB取込用ID変換履歴テーブル（convert_id_list）へ履歴登録
					$this->db->query('INSERT INTO convert_id_list (id_kind, old_id, new_id, update_at) VALUES (?,?,?,?)', array(
									'student',
									$arrayRecord[0],
									$temp_insert_id,
									date('Y/m/d H:i:s')
								));
					
					// DB取込用ID変換履歴テーブル（convert_id_list）へ履歴登録
					$this->db->query('INSERT INTO convert_id_list (id_kind, old_id, new_id, update_at) VALUES (?,?,?,?)', array(
									'bar_association_student',
									$arrayRecord[17],
									$_convert['bar_association_id'],
									date('Y/m/d H:i:s')
								));
					
					$this->db->trans_complete();
					$counter = $counter + 1;
					$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] INSERT DATA [MS_USER.CEREAL_NO : ".$arrayRecord[0]." / student.student_id : ".$temp_insert_id."]");



				}catch(Exception $e){ 
					$this->db->trans_rollback();
					$this->_output_log("取込処理例外発生");
				}
			}
		}
		fclose($fp);
		
		// 終了ログ
		$this->_output_log("END oneoff_import");
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
	//	mb_convert_variables("UTF-8", "SJIS-win", $_csv_data);
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
				$bar_association_id = $this->_etc_layer_number[$bar_association];
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
	// ログファイル出力＋画面出力 → ファイル出力を停止
	//----------------------------------------------
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
	

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}

