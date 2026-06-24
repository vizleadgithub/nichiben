<?php
#[AllowDynamicProperties]
class Model_applog_student extends CI_Model
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
	// 生徒アプリログテーブルから図書室履歴を取得
	//----------------------------------------------
	function get_book_library_log($param){
		//引数設定
		$param = array_merge(array(
			'school_id'			=> $this->session->userdata['cms_master.login.school_id'],
			'book_library_id'	=> 0,
		), $param);
		
		//SQL投入
		$query = $this->db->query(
			' SELECT applog_student.student_id, student.student_name, applog_student.added_at '.
			'   FROM applog_student '.
			'  INNER JOIN student ON student.student_id = applog_student.student_id '.
			"  WHERE applog_student.log_name = 'book_library_id' ".
			'    AND applog_student.school_id = ? '.
			'    AND applog_student.log_value = ? '.
			'    AND student.status           = 0 '.
			'  ORDER BY applog_student.added_at DESC, applog_student.student_id DESC ',
			array(
				$param['school_id'],
				$param['book_library_id'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}
}
?>
