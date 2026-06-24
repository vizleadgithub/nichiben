<?php
#[AllowDynamicProperties]
class Model_queue extends CI_Model  
{
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $status_deleted    = 'DELETED';     // 削除
	private $status_waiting    = 'WAITING';     // 未処理
	private $status_processing = 'PROCESSING';  // 処理中
	private $status_ok         = 'OK';          // 処理正常終了
	private $status_ng         = 'NG';          // 処理異常終了
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		
		$this->load->helper('json');
	}

	//----------------------------------------------
	// レコード取得
	//----------------------------------------------
	function get_queue($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_type'          => '',
				'queue_type_id'       => 0,
				'queue_kind'          => '',
				'queue_kind_detail'   => '',
				'queue_status'        => '',
				'queue_execute_time'  => '',    // date('Y-m-d H:i:s'),
				'queue_limit'         => 0,
				'select_order_by'     => '',
			),
			$param
		);
		
		// SQL作成
		$sql = " SELECT queue.* FROM queue WHERE 1=1 ";
		
		// 分類
		if( (isset($param['queue_type'])) && ($param['queue_type']!='') ) {
			$sql .= " AND queue.queue_type = '{$this->db->escape_str($param['queue_type'])}'";
		}
		
		// 分類ID
		if( (isset($param['queue_type_id'])) && ($param['queue_type_id']!=0) ) {
			$sql .= " AND queue.queue_type_id = {$this->db->escape($param['queue_type_id'])}";
		}
		
		// 種類
		if( (isset($param['queue_kind'])) && ($param['queue_kind']!='') ) {
			$sql .= " AND queue.queue_kind = '{$this->db->escape_str($param['queue_kind'])}'";
		}
		
		// 種類詳細（部分文字列一致）
		if( (isset($param['queue_kind_detail'])) && ($param['queue_kind_detail']!='') ) {
			$sql .= " AND queue.queue_kind_detail LIKE '%{$this->db->escape_str($param['queue_kind_detail'])}%'";
		}
		
		// キュー状態
		if( (isset($param['queue_status'])) && ($param['queue_status']!='') ) {
			$sql .= " AND queue.queue_status = '{$this->db->escape_str($param['queue_status'])}'";
		}
		
		// 実行日時
		if( (isset($param['queue_execute_time'])) && ($param['queue_execute_time']!='') ) {
			$sql .= " AND queue.queue_execute_time <= '{$this->db->escape_str($param['queue_execute_time'])}'";
		}
		
		// 出力順
		if( (isset($param['select_order_by'])) && ($param['select_order_by']!='') ) {
			$sql .= " order by ".$param['select_order_by']." ";
		}
		
		// 取得最大数
		if( (isset($param['queue_limit'])) && (is_numeric($param['queue_limit'])) && ($param['queue_limit']>0) ) {
			$sql .= " LIMIT 0, {$this->db->escape_str($param['queue_limit'])}";
		}
		
		// クエリ実行
		$query = $this->db->query($sql, array());
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();     //  return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// レコード登録
	//----------------------------------------------
	function insert_queue($param) {
		//引数設定
		$param = array_merge(
			array(
				//'queue_id'
				'queue_type'          => '',
				'queue_type_id'       => 0,
				'queue_kind'          => '',
				'queue_kind_detail'   => '{}',
				'queue_execute_time'  => date('Y-m-d H:i:s'),
				'queue_status'        => $this->status_waiting,
				'added_at'            => date('Y-m-d H:i:s'),
				'update_at'           => date('Y-m-d H:i:s'),
			),
			$param
		);

		$this->db->trans_start();
		
		// 新規挿入
		$res = $this->db->query($this->db->insert_string('queue', array(
			//'queue_id',
			'queue_type'          => $param['queue_type'],
			'queue_type_id'       => $param['queue_type_id'],
			'queue_kind'          => $param['queue_kind'],
			'queue_kind_detail'   => $param['queue_kind_detail'],
			'queue_execute_time'  => $param['queue_execute_time'],
			'queue_status'        => $param['queue_status'],
			'added_at'            => $param['added_at'],
			'update_at'           => $param['update_at'],
		)));
		
		$this->db->trans_complete();
		
		//データリターン
		return true;
	}








	//----------------------------------------------
	// レコード更新（WAITING → PROCESSING）
	// ※キー => queue_id
	//----------------------------------------------
	function update_queue_processing($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_id' => '0',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_processing, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),'queue_id IN ('.$this->db->escape_str($param['queue_id']).") AND queue_status = ".$this->db->escape($this->status_waiting)." "
		));
		//$res_stream = $this->db->query($this->db->update_string('report_video', array(
		//		'value'		=> ($_log->traffic ? $_log->traffic : 0),
		//	),'school_id='.$school['school_id']." AND type='stream' AND date = '".$_log->day."' "
		//));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}

	//----------------------------------------------
	// レコード更新（PROCESSING → OK）
	// ※キー => queue_id
	//----------------------------------------------
	function update_queue_ok($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_id' => '0',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_ok, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),'queue_id IN ('.$this->db->escape_str($param['queue_id']).") AND queue_status = ".$this->db->escape($this->status_processing)." "
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}

	//----------------------------------------------
	// レコード更新（PROCESSING → NG）
	// ※キー => queue_id
	//----------------------------------------------
	function update_queue_ng($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_id' => '0',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_ng, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),'queue_id IN ('.$this->db->escape_str($param['queue_id']).") AND queue_status = ".$this->db->escape($this->status_processing)." "
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}

	//----------------------------------------------
	// レコード更新（OK → WAITING）
	// ※キー => queue_id
	//----------------------------------------------
	function update_queue_waiting($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_id' => '0',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_waiting, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),'queue_id IN ('.$this->db->escape_str($param['queue_id']).") AND queue_status = ".$this->db->escape($this->status_ok)." "
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}

	//----------------------------------------------
	// レコード更新（DELETED）
	// ※キー => queue_id
	//----------------------------------------------
	function update_queue_deleted($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_id' => '0',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_deleted, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),'queue_id IN ('.$this->db->escape_str($param['queue_id']).") "
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}



	//----------------------------------------------
	// レコード更新（DELETED）
	// ※キー => queue_type, queue_type_id, queue_kind, queue_status
	//   条件に一致したqueueのstatusを「DELETED」に変更
	//   必須：queue_type（分類）
	//         queue_type_id（分類ID）
	//         queue_kind（種類）
	//   任意：queue_status（現在のキュー状態）、指定がない場合上記３つに一致するqueueが対象。
	//----------------------------------------------
	function update_queue_deleted_type_kind_status($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_type'    => '',
				'queue_type_id' => 0,
				'queue_kind'    => '',
				'queue_status'  => '',
			),
			$param
		);
		
		// クエリ実行条件作成
		// ),"queue_type = '".$param['queue_type']."' AND queue_type_id = ".$param['queue_type_id']." AND queue_kind = '".$param['queue_kind']."' AND queue_status = '".$this->status_waiting."' "
		//
		$query_where  = "";
		$query_where .= " queue_type    = ".$this->db->escape($param['queue_type'])." ";
		$query_where .= " AND ";
		$query_where .= " queue_type_id = ".$this->db->escape($param['queue_type_id'])." ";
		$query_where .= " AND ";
		$query_where .= " queue_kind    = ".$this->db->escape($param['queue_kind'])." ";
		if($param['queue_status']!=''){
			$query_where .= " AND ";
			$query_where .= " queue_status  = ".$this->db->escape($param['queue_status'])." ";
		}
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_deleted, 
				'update_at'     => date('Y-m-d H:i:s'), 
			), $query_where
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}



	//----------------------------------------------
	// レコード取得（授業ID→学校・授業・受講者）
	// ※検索条件
	//   授業受講者テーブル
	//     授業IDが一致 : student_lecture_class.class_id = ? 
	//   授業テーブル
	//     状態が有効  : class.status = 0
	//   受講者テーブル
	//     状態が有効                 : student.status = 0
	//     送信用メールアドレスがある : student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0
	// 学校テーブル
	//     状態が有効 : school.status = 0
	//----------------------------------------------
	function get_queue_class_reminder_mail($param) {
		//引数設定
		$param = array_merge(
			array(
				'class_id'  => 0, 
			),
			$param
		);
		
		// クエリ実行
		$query = $this->db->query("
					 SELECT  student_lecture_class.class_id 
					        ,class.class_name 
					        ,DATE_FORMAT(class.class_open , '%Y/%m/%d %H:%i:%S')  AS class_open 
					        ,DATE_FORMAT(class.class_close , '%Y/%m/%d %H:%i:%S') AS class_close 
					        ,class.school_id 
					        ,school.school_name
					        ,student_lecture_class.student_id
					        ,student.student_name
					        ,student.student_email
					        ,student.student_send_email
					   FROM  student_lecture_class 
					         LEFT JOIN student ON student_lecture_class.student_id = student.student_id 
					         LEFT JOIN class   ON student_lecture_class.class_id   = class.class_id 
					         LEFT JOIN school  ON class.school_id                  = school.school_id 
					  WHERE  student_lecture_class.class_id = ? 
					    AND  class.status                   = 0 
					    AND  student.status                 = 0 
					    AND  (student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0) 
					    AND  school.status                  = 0 
				", array(
					$param['class_id'],
				));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();    //  return $query->row_array();
		}else{
			return [];
		}
	}



	//----------------------------------------------
	// 授業管理 - レコード更新（DELETED）
	//   WAITING 以外を対象外にする（処理済queueもDELETED になるため）
	//----------------------------------------------
	function update_queue_class_deleted($param) {
		//引数設定
		$param = array_merge(
			array(
				'queue_type_id'  => 0,
				'queue_type'     => 'class',
				'queue_kind'     => 'mail',
			),
			$param
		);
		
		$this->db->trans_start();
		
		// クエリ実行
		$res = $this->db->query($this->db->update_string('queue', array(
				'queue_status'  => $this->status_deleted, 
				'update_at'     => date('Y-m-d H:i:s'), 
			),"queue_type = ".$this->db->escape($param['queue_type'])." AND queue_type_id = ".$this->db->escape($param['queue_type_id'])." AND queue_kind = ".$this->db->escape($param['queue_kind'])." AND queue_status = ".$this->db->escape($this->status_waiting)." "
		));
		
		$this->db->trans_complete();
		
		//データリターン
		return $res;
	}

	//----------------------------------------------
	// 授業管理 - レコード登録（WAITING）
	//----------------------------------------------
	function insert_queue_class_waiting($param) {
		//引数設定
		$param = array_merge(
			array(
				//'queue_id'
				'queue_type'          => 'class',
				'queue_type_id'       => 0,
				'queue_kind'          => 'mail',
				'queue_kind_detail'   => '{}',
				'queue_execute_time'  => date('Y-m-d H:i:s'),
				'queue_status'        => $this->status_waiting,
				'added_at'            => date('Y-m-d H:i:s'),
				'update_at'           => date('Y-m-d H:i:s'),
			),
			$param
		);

		$this->db->trans_start();
		
		// 新規挿入
		$res = $this->db->query($this->db->insert_string('queue', array(
			//'queue_id',
			'queue_type'          => $param['queue_type'],
			'queue_type_id'       => $param['queue_type_id'],
			'queue_kind'          => $param['queue_kind'],
			'queue_kind_detail'   => $param['queue_kind_detail'],
			'queue_execute_time'  => $param['queue_execute_time'],
			'queue_status'        => $param['queue_status'],
			'added_at'            => $param['added_at'],
			'update_at'           => $param['update_at'],
		)));
		
		$this->db->trans_complete();
		
		//データリターン
		return true;
	}



	//----------------------------------------------
	// レコード取得（問題ID→学校・問題・受講者）
	// ※検索条件
	//   問題受講者テーブル
	//     問題IDが一致 : rel_exam_student.exam_id = ? 
	//   問題テーブル
	//     状態が有効                   : exam.status = 0
	//     公開フラグが公開             : exam.public_flag = 0
	//     システム日時が提出期限範囲内 : ? BETWEEN exam.exam_open AND exam.exam_close
	//         ※実行日時は提出期限終了日時より前に設定しているため、この範囲外になることはないはず（テーブル直操作除く）
	//   受講者テーブル
	//     状態が有効                 : student.status = 0
	//     送信用メールアドレスがある : student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0
	// 学校テーブル
	//     状態が有効 : school.status = 0
	//----------------------------------------------
	function get_queue_exam_reminder_mail($param) {
		//引数設定
		$param = array_merge(
			array(
				'exam_id'  => 0, 
			),
			$param
		);
		
		// クエリ実行
		$query = $this->db->query("
					 SELECT  rel_exam_student.exam_id 
					        ,exam.exam_name 
					        ,exam.school_id 
					        ,school.school_name
					        ,rel_exam_student.student_id
					        ,student.student_name
					        ,student.student_email
					        ,student.student_send_email
					   FROM  rel_exam_student 
					         LEFT JOIN student ON rel_exam_student.student_id = student.student_id 
					         LEFT JOIN exam    ON rel_exam_student.exam_id    = exam.exam_id 
					         LEFT JOIN school  ON exam.school_id              = school.school_id 
					  WHERE  rel_exam_student.exam_id = ? 
					    AND  exam.status              = 0 
					    AND  exam.public_flag         = 0 
					    AND  ? BETWEEN exam.exam_open AND exam.exam_close 
					    AND  student.status           = 0 
					    AND  (student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0) 
					    AND  school.status            = 0 
				", array(
					$param['exam_id'],
					date('Y-m-d H:i:s'),
				));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();    //  return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// レコード取得（課題ID→学校・問題・受講者）
	// ※検索条件
	//   課題受講者テーブル
	//     課題IDが一致 : rel_issue_student.issue_id = ? 
	//   課題テーブル
	//     状態が有効                   : issue.status = 0
	//     公開フラグが公開             : issue.public_flag = 0
	//     システム日時が提出期限範囲内 : ? BETWEEN issue.issue_open AND issue.issue_close
	//         ※実行日時は提出期限終了日時より前に設定しているため、この範囲外になることはないはず（テーブル直操作除く）
	//   受講者テーブル
	//     状態が有効                 : student.status = 0
	//     送信用メールアドレスがある : student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0
	// 学校テーブル
	//     状態が有効 : school.status = 0
	//----------------------------------------------
	function get_queue_issue_reminder_mail($param) {
		//引数設定
		$param = array_merge(
			array(
				'issue_id'  => 0, 
			),
			$param
		);
		
		// クエリ実行
		$query = $this->db->query("
					 SELECT  rel_issue_student.issue_id 
					        ,issue.issue_name 
					        ,issue.school_id 
					        ,school.school_name
					        ,rel_issue_student.student_id
					        ,student.student_name
					        ,student.student_email
					        ,student.student_send_email
					   FROM  rel_issue_student 
					         LEFT JOIN student ON rel_issue_student.student_id = student.student_id 
					         LEFT JOIN issue    ON rel_issue_student.issue_id    = issue.issue_id 
					         LEFT JOIN school  ON issue.school_id              = school.school_id 
					  WHERE  rel_issue_student.issue_id = ? 
					    AND  issue.status              = 0 
					    AND  issue.public_flag         = 0 
					    AND  ? BETWEEN issue.issue_open AND issue.issue_close 
					    AND  student.status           = 0 
					    AND  (student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0) 
					    AND  school.status            = 0 
				", array(
					$param['issue_id'],
					date('Y-m-d H:i:s'),
				));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();    //  return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// レコード取得（お知らせID→学校・お知らせ・受講者）
	// ※検索条件
	//   お知らせ受講者テーブル
	//     お知らせIDが一致 : rel_information_student.information_id = ? 
	//   お知らせテーブル
	//     状態が有効                   : information.status        = 0
	//     受講者表示対象フラグが有効   : information.show_student  = 1 
	//     学校IDがゼロ以外             : information.school_id    != 0    -- 学校ID「0」は全ての学校の講師・受講者へのお知らせとなるため通知対象外
	//     システム日時が公開期間範囲内 : ? BETWEEN information.information_open AND information.information_close
	//   受講者テーブル
	//     状態が有効                 : student.status = 0
	//     送信用メールアドレスがある : student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0
	// 学校テーブル
	//     状態が有効 : school.status = 0
	//----------------------------------------------
	function get_queue_information_reminder_mail($param) {
		//引数設定
		$param = array_merge(
			array(
				'information_id'  => 0, 
			),
			$param
		);
		
		// クエリ実行
		$query = $this->db->query("
					 SELECT  rel_information_student.information_id
					        ,information.information_title 
					        ,information.school_id 
					        ,school.school_name
					        ,rel_information_student.student_id
					        ,student.student_name
					        ,student.student_email
					        ,student.student_send_email
					   FROM  rel_information_student 
					         LEFT JOIN student     ON rel_information_student.student_id     = student.student_id 
					         LEFT JOIN information ON rel_information_student.information_id = information.information_id 
					         LEFT JOIN school      ON information.school_id                  = school.school_id 
					  WHERE  rel_information_student.information_id = ? 
					    AND  information.status        = 0 
					    AND  information.show_student  = 1    -- 受講者を対象に表示するお知らせ 
					    AND  information.school_id    != 0    -- 学校ID「0」は全ての学校 
					    AND  ? BETWEEN information.information_open AND information.information_close 
					    AND  student.status            = 0 
					    AND  (student.student_send_email IS NOT NULL AND LENGTH(TRIM(student.student_send_email)) > 0) 
					    AND  school.status             = 0 
				", array(
					$param['information_id'],
					date('Y-m-d H:i:s'),
				));
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();    //  return $query->row_array();
		}else{
			return [];
		}
	}


}
?>
