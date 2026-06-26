<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Csv_download extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	
	// 各csvの見出し項目名
	private $header_student_list = array('登録番号', '氏名', 'メールアドレス', 'メールマガジン受け取り可否', 'パスポート有無', '代替倫理研修権限', '弁護士会名', 'パスポート有効期限');

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		
		$this->load->library('Curl');
		
		// custom_fputcsv_helper
		$this->load->helper('custom_fputcsv_helper');
		
	//	$this->allowIP = $this->config->item('*');	//IPアドレス制限
	}

	//----------------------------------------------
	// メイン処理
	// 引数：type			出力csvの種類
	//       csv_filename	出力csvのファイル名
	//       csv_where		出力csvの出力条件
	//----------------------------------------------
	public function index(){
		print("test");
		//exit();
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 出力csvの種類
		$post_type = "student_list";
		if( (isset($_POST['type'])) && (!empty($_POST['type'])) ) {
			$post_type = $_POST['type'];
		}
		
		// 出力csvのファイル名
		$post_csv_filename = "";
		if( (isset($_POST['csv_filename'])) && (!empty($_POST['csv_filename'])) ) {
			$post_csv_filename = $_POST['csv_filename'];
		}else{
			// マイクロ秒（msec）の取得　　例：[$msec = 0.67361700]  [$sec = 1378349457]
			list($msec, $sec) = explode(" ", microtime());
			// マイクロ秒×1000 →整数値の四捨五入
			$microsecond = round($msec * 1000);
			// 年月日時分秒_マイクロ秒（1000倍して整数部分四捨五入）.csv
			$post_csv_filename = date("YmdHis")."_".$microsecond.".csv";
		}
		
		// 出力csvの出力条件
		$post_csv_where = array();
		if( (isset($_POST['csv_where'])) && (!empty($_POST['csv_where'])) ) {
			$post_csv_where = $_POST['csv_where'];
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 出力csvのデータ取得
		$csv_header = array();
		$table_data = array();
		switch ($post_type) {
			case 'student_list':
				$table_data = $this->_student_list($post_csv_where);
				$csv_header = $this->header_student_list;
				break;
			default:
			  //echo "上記以外";
		}
//var_dump($table_data);
//exit();

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// csv出力
		
		// header 設定
		// ※API単体の場合、headerが必要なので記述。
		$post_csv_filename =  mb_convert_encoding($post_csv_filename, 'SJIS-WIN');
		
		header('Content-Type: application/x-csv');
		header("Content-Disposition: attachment; filename=$post_csv_filename");

		//header clum
		if( !empty($csv_header) ){
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_header);
			$csv_headClum = get_csv_format($csv_header, 'SJIS-WIN');
			print $csv_headClum."\r\n";
			
			ob_flush();
			flush();
		}
		
		// body clum
		foreach($table_data as $table_data_record){
			// 連想配列→配列変換
			$csv_data = array();
			foreach ($table_data_record as $key => $value) {
				//array_push($csv_data, $value);
				$csv_data[] = $value;
			}
			mb_convert_variables('SJIS-WIN', mb_internal_encoding(), $csv_data);
			$csv = get_csv_format($csv_data, 'SJIS-WIN');
			print $csv."\r\n";
			
			ob_flush();
			flush();
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
	}


	// ========== ========== ========== ========== ========== ========== ========== ==========
	// 受講者（受講者検索）
	// ========== ========== ========== ========== ========== ========== ========== ==========
	private function _student_list($param){
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
							'order_by'                      => 0,
						),
						$param
					);

		//SQL生成
		// '登録番号', '氏名', 'メールアドレス', 'メールマガジン受け取り可否', 'パスポート有無', '代替倫理研修権限', '弁護士会名'　　mailmagazine_flg 
		$sql_select  = " SELECT ";
		$sql_select .= "  student.lawyer_number ";		// 登録番号（弁護士番号）
		$sql_select .= ", student.student_name ";		// 氏名
		$sql_select .= ", student.student_email ";		// メールアドレス
		$sql_select .= ", (CASE WHEN student.mailmagazine_flg = '1'        THEN '○'   ELSE '－' END) AS mailmagazine_flg";		// メルマガ受取可否（0:メルマガ拒否 1:メルマガ許可）
		$sql_select .= ", (CASE WHEN student.presence_passport = '1'       THEN '○'   ELSE '－' END) AS presence_passport";							// パスポート有無（有無から○－に変更）
		$sql_select .= ", (CASE WHEN student.sub_auth_ethic_training = '1' THEN '許可' ELSE '禁止' END) AS sub_auth_ethic_training";					// 代替権限
		$sql_select .= ", (CASE WHEN mtb_bar_association.name IS NULL      THEN ''     ELSE mtb_bar_association.name END) AS bar_association_name";		// 弁護士会名
		$sql_select .= ", (CASE WHEN student.presence_passport = '1' THEN student.exp_date_passport ELSE '' END) AS exp_date_passport";		// パスポート有効期限
		$sql_select .= " FROM student LEFT JOIN mtb_bar_association ON student.bar_association_id = mtb_bar_association.id ";
		
		$sql_where   = " WHERE student.status = 0 ";
		
		$sql_order   = " ORDER BY student.lawyer_number ASC ";
		if($param['order_by'] != 0){
			$sql_order = " ORDER BY student.lawyer_number DESC ";
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
		
		//メールアドレス
		if (isset($param['s_email']) && $param['s_email'] != '') {
			$sql_where .= " AND (student.student_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
								OR student.student_email_mobile LIKE '%{$this->db->escape_like_str($param['s_email'])}%'
			)";
		}
		
		// 弁護士会ID（所属弁護士会）
		if (isset($param['s_bar_association']) && $param['s_bar_association'] != '') {
			$sql_where .= " AND student.bar_association_id = {$this->db->escape($param['s_bar_association'])}";
		}
		
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
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
//var_dump($sql_select.$sql_where.$sql_order);
//exit();
		// SQL-SELECT文実行
		$query = $this->db->query($sql_select.$sql_where.$sql_order);
		
		// データリターン
		if( $query->num_rows() > 0 ){
			return $query->result_array();
		}else{
			return array();
		}
	}
}


