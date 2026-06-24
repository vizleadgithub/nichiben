<?php
#[AllowDynamicProperties]
class Model_applog extends CI_Model
{
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// アプリログテーブルへ保存
	// [2012/11/13]リモート側IPアドレスの取得変更
	//----------------------------------------------
	function insert_log($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0 ,  // 学校ID
							'teacher_id'  => 0 ,  // 講師ID
							'student_id'  => 0 ,  // 受講者ID
							'log_name'    => '',  // ログ名称
							'log_value'   => '',  // ログ内容
							'log_option'  => '',  // ログ予備
							'remote_addr' => '',  // リモート側IPアドレス
							'user_agent'  => '',  // USER AGENT
							'server_name' => '',  // サーバーホスト名
							'php_self'    => '',  // スクリプトファイル名
						),
						$param
					);

		//現在時刻取得
		$wDate = date('Y/m/d H:i:s');

		// [2012/11/13]IP設定
		$clientIP="";
		if($this->input->server('HTTP_X_FORWARDED_FOR')){
			$clientIP = $this->input->server('HTTP_X_FORWARDED_FOR');
		}
		else if($this->input->server('REMOTE_ADDR')){
			$clientIP = $this->input->server('REMOTE_ADDR');
		}
		$param['remote_addr'] = $clientIP;

		$sql  = '';
		$sql .= "INSERT INTO applog ( ";
		$sql .= "       school_id, teacher_id, student_id, log_name, log_value, log_option, remote_addr, user_agent, server_name, php_self, added_at ";
		$sql .= ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$this->db->trans_start();
		$this->db->query($sql, 
							array(
								$param['school_id'],
								$param['teacher_id'],
								$param['student_id'],
								$param['log_name'],
								$param['log_value'],
								$param['log_option'],
								$param['remote_addr'],
								$param['user_agent'],
								$param['server_name'],
								$param['php_self'],
								$wDate
							));
		
		$prev_school_id = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}
	
	//----------------------------------------------
	// アプリログテーブルから、ログイン履歴を取得
	// [2012/08/29]SuperUserのログイン履歴を、講師ログイン時には表示しないように修正
	// [2012/11/13]SuperUserログイン時、ログイン画像登録・メニュー画像登録・外部連携(eLM)登録の
	//             履歴を表示。かつ、表示件数を直近20から100に変更
	//----------------------------------------------
	function get_login_log($school_id = -1, $teacher_id = -1) {
		
		//SQL投入
		$sql = '';
		if($teacher_id == -1){
			$sql .= " SELECT if( applog.teacher_id <= 0, '-', applog.teacher_id ) teacher_id, ";
			$sql .= "        if( applog.teacher_id <= 0, 'Super User', teacher.teacher_name ) teacher_name, ";
			$sql .= '       applog.log_name, ';
			$sql .= '       applog.added_at ';
			$sql .= '  FROM applog LEFT JOIN teacher ON teacher.teacher_id = applog.teacher_id ';
			$sql .= " WHERE applog.log_name IN ('Login Result', 'before school_id', 'School Photo', 'School Menu','Outside eLM') ";
			$sql .= '   AND applog.school_id = ? ';
			$sql .= ' ORDER BY applog.added_at DESC ';
			$sql .= ' LIMIT 100 ';
		}else{
			$sql .= " SELECT if( applog.teacher_id <= 0, '-',          applog.teacher_id ) teacher_id, ";
			$sql .= "        if( applog.teacher_id <= 0, 'Super User', teacher.teacher_name ) teacher_name, ";
			$sql .= '        applog.added_at ';
			$sql .= '  FROM applog LEFT JOIN teacher ON teacher.teacher_id = applog.teacher_id ';
			$sql .= " WHERE applog.log_name IN ('Login Result', 'before school_id') ";
			$sql .= '   AND applog.school_id  = ? ';
			$sql .= '   AND applog.teacher_id > 0 ';
			$sql .= ' ORDER BY applog.added_at DESC ';
			$sql .= ' LIMIT 20 ';
		}
		$query = $this->db->query(
			$sql,
			array(
				$school_id,
			)
		);

		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return null;
		}
	}
}
?>
