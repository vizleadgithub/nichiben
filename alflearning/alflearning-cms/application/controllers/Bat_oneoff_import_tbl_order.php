<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

// 購入履歴  tbl_order  tbl_order_temp  tbl_order_detail

#[AllowDynamicProperties]
class Bat_oneoff_import_tbl_order extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
	public $_target_path              = '/alflearning-data/__import_data__/CSV/';	// ファイル格納場所
  //public $_target_file_name         = 'RECEIPT.csv';								// 対象ファイル名（タブ区切り注意）
	public $_target_file_name_array   = array(
		'RECEIPT.csv',
	);

	public $_convert_log_file         = '/alflearning-data/__import_data__/LOG/RECEIPT_to_tbl_order.txt';

	// 使用テーブル名
//	public $_tbl_order           = 'tbl_order';
//	public $_tbl_order_temp      = 'tbl_order_temp';
//	public $_tbl_order_detail    = 'tbl_order_detail';
	public $_tbl_order           = 'tbl_order_import';
	public $_tbl_order_temp      = 'tbl_order_temp_import';
	public $_tbl_order_detail    = 'tbl_order_detail_import';
	
	public $_import_kensyu_log   = 'import_kensyu_log';		// KENSHU_LOG.csv の中身
	
	// tbl_order_detail.product_id（商品ID）用
	// 各金額のパスポートのproduct_idの配列（手動投入）
	public $_passport_product_id = array(
					1000	=> 24,		// パスポート5000円
					5000	=> 23,		// パスポート1000円
					10000	=> 25,		// パスポート10000円
	);

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
			echo var_dump($outputs);
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		$this->_output_log("START oneoff_import");
		
		// 対象テーブル内容削除
		try{
			$this->db->trans_start();
			$this->db->query("TRUNCATE TABLE ".$this->_tbl_order." ");
			$this->db->query("TRUNCATE TABLE ".$this->_tbl_order_temp." ");
			$this->db->query("TRUNCATE TABLE ".$this->_tbl_order_detail." ");
			
			$this->db->query("DELETE FROM convert_id_list WHERE id_kind = 'order' ");
			
			$this->db->trans_complete();
			$this->_output_log("対象テーブルの内容初期化、成功");
		}catch(Exception $e){ 
			$this->db->trans_rollback();
			$this->_output_log("対象テーブルの内容初期化、例外エラー");
		}
		
		// 複数ファイルをインポート
		$_target_file_name_list = $this->_target_file_name_array;
		for ($i=0 ; $i < count($_target_file_name_list) ; $i++) {
			$_target_file_name = $_target_file_name_list[$i];

			// ファイル存在確認（なければ次配列へ）
			if(!is_file($this->_target_path.$_target_file_name)){
				$this->_output_log("ファイル：".$this->_target_path.$_target_file_name." が見つかりません");
			  //return;
				continue;
			}else{
				$this->_output_log("ファイル：".$this->_target_path.$_target_file_name." 処理開始");
			}
			
			// CSV取込処理
			$fp = fopen($this->_target_path.$_target_file_name, 'r');
			$counter = 0;
			while(!feof($fp)){
				$arrayRecord = $this->fgetcsv($fp, filesize($this->_target_path.$_target_file_name), "\t");
				
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

						// 【tbl_order_temp】--------------------------------------------------
						// 注文ID（order_id ・・・ auto_increment）
						
						// order_no
						$_convert['order_no'] = $arrayRecord[5];

						// RECEIPT.UID（MS_USER.SERIAL_ID）を元に student.student_id を取得
						// 前提条件：student テーブルへのバッチによる投入が終了していること・
						// convert_id_list（DB取込用ID変換履歴テーブル）から、student_id の取得（取得失敗時は数字ゼロ）
						$_convert['member_id'] = $this->_get_convert_now_id('student', $arrayRecord[3]);
						
						$_convert['price']      = $arrayRecord[10];		// 販売額
						$_convert['tax']        = 0;					// 税
						$_convert['charge_fee'] = 0;					// 手数料
						
						if(trim($arrayRecord[6])=='0') $_convert['payment_type'] = 12;
						if(trim($arrayRecord[6])=='1') $_convert['payment_type'] = 1;		// 支払い方法
						
						if(trim($arrayRecord[2])=='') $_convert['payment_status'] = '1';
						if(trim($arrayRecord[2])!='') $_convert['payment_status'] = '2';	// 支払い状態
						
						$_convert['order_date'] = '0000-00-00';
						if(trim($arrayRecord[1])!='') $_convert['order_date']   = $arrayRecord[1];	// 注文日
						
						$_convert['payment_date'] = '0000-00-00';
						if(trim($arrayRecord[2])!='') $_convert['payment_date'] = $arrayRecord[2];	// 支払日
						
						$_convert['del_flg']     = 0;							// 1:削除
						$_convert['create_date'] = $_convert['order_date'];		// 登録日
						$_convert['receipt_flg'] = 1;							// 領収書発行フラグ(0：未発行 1：発行済)  ※再発行は不要？
						$_convert['web_flg']     = 1;							// web申込フラグ(0：web以外 1：web申込)
						$_convert['claim_flg']   = $arrayRecord[7];				// 請求書送付フラグ(0:しない 1:する)


/*	
	print var_dump($arrayRecord);
	print "\n";
	print var_dump($_convert);
	print "\n";

	$counter = $counter + 1;
	if($counter == 1){
		continue 2;
	}
*/

						
						//新規
						$sql_1 = "INSERT INTO ".$this->_tbl_order_temp." (
									  order_no
									 ,member_id
									 ,price
									 ,tax
									 ,charge_fee
									 ,payment_type
									 ,payment_status
									 ,order_date
									 ,payment_date
									 ,del_flg
									 ,create_date
									 ,receipt_flg
									 ,web_flg
									 ,claim_flg
								) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						
						$this->db->trans_begin();
						
						$this->db->query($sql_1, array(
											$_convert['order_no'],
											$_convert['member_id'],
											$_convert['price'],
											$_convert['tax'],
											$_convert['charge_fee'],
											$_convert['payment_type'],
											$_convert['payment_status'],
											$_convert['order_date'],
											$_convert['payment_date'],
											$_convert['del_flg'],
											$_convert['create_date'],
											$_convert['receipt_flg'],
											$_convert['web_flg'],
											$_convert['claim_flg'],
										));
						
						// 登録したorder_idを取得
						$temp_order_id = $this->db->insert_id();
						
						// DB取込用ID変換履歴テーブル（convert_id_list）へ履歴登録
						$this->db->query('INSERT INTO convert_id_list (id_kind, old_id, new_id, update_at) VALUES (?,?,?,?)', array(
										'order',
										$arrayRecord[0],
										$temp_order_id,
										date('Y/m/d H:i:s')
									));
						
						// 【tbl_order_detail】-------------------------------------------
						// 注文詳細ID（order_detail_id ・・・ auto_increment）

						$_convert['order_id']   = $temp_order_id;	// 注文ID

					  //$_convert['member_id']		// 顧客ID
						
						// RECEIPT.ITEM_TYPE により、tbl_order_detail.product_id を設定する
						if(trim($arrayRecord[8])=='0'){
							$_convert['product_id'] = $arrayRecord[9];		// RECEIPT.ITEM_ID = MS_COURSE.SERIAL_ID
						}elseif(trim($arrayRecord[8])=='1'){
							$temp_price             = $arrayRecord[10];		// RECEIPT.PRICE
							$temp_product_id        = $this->_passport_product_id[$temp_price];
							
							$_convert['product_id'] = $temp_product_id;
						}elseif(trim($arrayRecord[8])=='2'){
							$_convert['product_id'] = $arrayRecord[9] + 10000;	// RECEIPT.ITEM_ID = MS_COURSE.SERIAL_ID
						}

					  //$_convert['price']									// 単価
						$_convert['sell_price']  = $arrayRecord[10];		// 実売単価
						$_convert['unit']        =1;						// 個数
						$_convert['pay_total']   = $arrayRecord[10];		// 支払総計
						$_convert['create_date'] = $_convert['order_date'];	// 作成日
																			// 更新日
					  //$_convert['payment_status']							// 商品種別
					  //$_convert['payment_date']							// 支払日
						
						// RECEIPT.ITEM_TYPE により、tbl_order_detail.product_type_add を設定する
						// 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
						if(trim($arrayRecord[8])=='0'){
							$_convert['product_type_add'] = 1;
						}elseif(trim($arrayRecord[8])=='1'){
							$_convert['product_type_add'] = 4;
						}elseif(trim($arrayRecord[8])=='2'){
							$_convert['product_type_add'] = 2;
						}

						// 弁護士会ID    （RECEIPT.ITEM_TYPE=2のみ取得）
						// 弁護士会支部ID（RECEIPT.ITEM_TYPE=2のみ取得）
						if(trim($arrayRecord[8])=='2'){
							// RECEIPT.UID は、MS_USER.SERIAL_ID
							// MS_USER.SERIAL_ID から、student_id を取得
						//	$temp_student_id = $this->_get_convert_now_id('student', $arrayRecord[3]);
							
							// student_id から、bar_association_id を取得
						//	$temp_bar_association_id = $this->_get_bar_association_id_from_student($temp_student_id);
						
							$temp_bar_association_id = $this->_get_bar_association_id_from_student($_convert['member_id']);
							
							
							// bar_association_id から、bar_association_branch_id を取得
							$temp_bar_association_branch_id = $this->_get_bar_association_branch_id($temp_bar_association_id);
							
							$_convert['bar_association_id']        = $temp_bar_association_id;
							$_convert['bar_association_branch_id'] = $temp_bar_association_branch_id;
						}else{
							$_convert['bar_association_id']        = NULL;
							$_convert['bar_association_branch_id'] = NULL;
						}

						$_convert['receipt_date'] = $_convert['payment_date'];	// 入金日
						$_convert['ticket_flg']   = 1;							// 受講票フラグ(0:未発行 1:発行済)

						// 会場研修参加フラグ(0:未参加 1:参加)	（RECEIPT.ITEM_TYPE=2のみ取得）
						//RECEIPT.ITEM_TYPEが2の場合、
						//→RECEIPT.UIDと、RECEIPT.ITEM_IDをキーに、KENSHU_LOG を検索。
						//→　一致したKENSHU_LOG.EXEC_DATE がなしの場合、0
						//→　一致したKENSHU_LOG.EXEC_DATE がありの場合、1
						//それ以外はNULL
						if(trim($arrayRecord[8])=='2'){
							$_convert['participation_flg'] = $this->_get_exec_date($arrayRecord[3], $arrayRecord[9]);
						}else{
							$_convert['participation_flg'] = 0;
						}
						$_convert['video_complete_flg'] = 0;	// 登録時点では0固定。商品内の全動画視聴完了時、1に変更
						
						$sql_2 = "INSERT INTO ".$this->_tbl_order_detail." (
									  order_id
									 ,member_id
									 ,product_id
									 ,price
									 ,sell_price
									 ,unit
									 ,pay_total
									 ,create_date
									 ,payment_status
									 ,payment_date
									 ,product_type_add
									 ,bar_association_id
									 ,bar_association_branch_id
									 ,receipt_date
									 ,ticket_flg
									 ,participation_flg
									 ,video_complete_flg
								) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						
					  //$this->db->trans_begin();
						
						$this->db->query($sql_2, array(
											$_convert['order_id'],
											$_convert['member_id'],
											$_convert['product_id'],
											$_convert['price'],
											$_convert['sell_price'],
											$_convert['unit'],
											$_convert['pay_total'],
											$_convert['create_date'],
											$_convert['payment_status'],
											$_convert['payment_date'],
											$_convert['product_type_add'],
											$_convert['bar_association_id'],
											$_convert['bar_association_branch_id'],
											$_convert['receipt_date'],
											$_convert['ticket_flg'],
											$_convert['participation_flg'],
											$_convert['video_complete_flg'],
										));

						// 登録したorder_detail_idを取得
						$temp_order_detail_id = $this->db->insert_id();
						
						$this->db->trans_complete();
						
						$counter = $counter + 1;
						$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] INSERT DATA [RECEIPT.SERIAL_ID : ".$arrayRecord[0]." / order_id - order_detail_id : ".$temp_order_id." - ".$temp_order_detail_id."]");

/*
	print var_dump($arrayRecord);
	print "\n";
	print var_dump($_convert);
	print "\n";
	break;
*/

					}catch(Exception $e){ 
						$this->db->trans_rollback();
						$this->_output_log("取込処理例外発生");
					}
				}
			}
			fclose($fp);
		
		}	// ファイル名ループ
		
		// tbl_order_temp 全レコードを tbl_order へ格納
		try{
		  //$this->load->database();
			$this->db->trans_start();
			$this->db->trans_begin();
			
			$sql_insert = "INSERT INTO ".$this->_tbl_order." SELECT * FROM ".$this->_tbl_order_temp."";
			$this->db->query($sql_insert);
			
			$this->db->trans_complete();
			
			$this->_output_log("  INSERT SUCCESS '".$sql_insert."'");
		}catch(Exception $e){ 
			$this->db->trans_rollback();
			$this->_output_log("テーブル格納処理例外発生");
		}
		
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
	// LOG_VIEW.UID（tMORAL_VIEW.UID）をキーに
	// convert_id_list（DB取込用ID変換履歴テーブル）から
	// student.student_id を取得
	//----------------------------------------------
	function _get_convert_now_id($id_kind = '', $old_id = ''){
		$new_id = 0;
		
		try{ 
			$query = $this->db->query(
				' SELECT convert_id_list.* '.
				'   FROM convert_id_list'.
				'  WHERE 1=1'.
				'    AND convert_id_list.id_kind = ? '.
				'    AND convert_id_list.old_id  = ? '.
				'  ORDER BY update_at DESC '.
				'  LIMIT 0, 1',
				array(
					$id_kind,
					$old_id,
				)
			);
			
			if ($query->num_rows() > 0) {
				$table_record = $query->row_array();		// 複数はresult_array
				$new_id       = $table_record['new_id'];
			}
		}catch(Exception $e){ 
			$new_id = 0;
		}
		
		return $new_id;
	}

	//----------------------------------------------
	// 生徒IDから、弁護士会IDの取得
	//----------------------------------------------
	function _get_bar_association_id_from_student($student_id = ''){
		$bar_association_id = '';
		
		try{ 
			$query = $this->db->query(
				' SELECT student.* '.
				'   FROM student'.
				'  WHERE 1=1'.
				'    AND student.student_id = ? '.
				'  LIMIT 0, 1',
				array(
					trim($student_id),
				)
			);
			
			if ($query->num_rows() > 0) {
				$bar_association    = $query->row_array();		// 複数はresult_array
				$bar_association_id = $bar_association['bar_association_id'];
			}
		}catch(Exception $e){ 
			$bar_association_id = '';
		}
		
		return $bar_association_id;
	}

	//----------------------------------------------
	// 弁護士会IDから、弁護士会支部IDの取得
	//----------------------------------------------
	function _get_bar_association_branch_id($bar_association_id = ''){
		$bar_association_branch_id = '';
		
		try{ 
			$query = $this->db->query(
				' SELECT mtb_bar_association_branch.* '.
				'   FROM mtb_bar_association_branch '.
				'  WHERE 1=1 '.
				'    AND mtb_bar_association_branch.bar_association_id = ? '.
				'  ORDER BY mtb_bar_association_branch.bar_association_branch_id ASC '.
				'  LIMIT 0, 1 ',
				array(
					trim($bar_association_id),
				)
			);
			
			if ($query->num_rows() > 0) {
				$table_record              = $query->row_array();		// 複数はresult_array
				$bar_association_branch_id = $table_record['bar_association_branch_id'];
			}
		}catch(Exception $e){ 
			$bar_association_branch_id = '';
		}
		return $bar_association_branch_id;
	}

	//----------------------------------------------
	// RECEIPT.UIDと、RECEIPT.ITEM_ID をキーに KENSHU_LOG.csv を検索
	// EXEC_DATE の存在有無により戻り値を設定
	//----------------------------------------------
	function _get_exec_date($uid = '', $item_id = ''){
		$participation_flg = '';
		
		try{ 
			$query = $this->db->query(
				' SELECT * '.
				'   FROM '.$this->_import_kensyu_log.' '.
				'  WHERE 1=1'.
				'    AND UID       =  ? '.
				'    AND KENSHU_ID =  ? '.
				"    AND EXEC_DATE != '' ".
				'  LIMIT 0, 1',
				array(
					trim($uid),
					trim($item_id),
				)
			);
			
			if ($query->num_rows() > 0) {
				$participation_flg = '1';
			}else{
				$participation_flg = '0';
			}
		}catch(Exception $e){ 
			$participation_flg = '0';
		}
		
		return $participation_flg;
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

/*
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
*/
