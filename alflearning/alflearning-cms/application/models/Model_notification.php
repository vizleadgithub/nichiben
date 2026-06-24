<?php
#[AllowDynamicProperties]
class Model_notification extends CI_Model
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
	// [ajax]通知テーブル取得
	//----------------------------------------------
	function get_notification($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'		=> 0,
							'teacher_id'	=> 0,
						),
						$param
					);

		//SQL生成
		$sql  = "";
		$sql .= "SELECT  notification.notification_id  AS notification_id ";
		$sql .= "       ,notification.school_id        AS school_id ";
		$sql .= "       ,notification.teacher_id       AS teacher_id ";
		$sql .= "       ,teacher.teacher_name          AS teacher_name ";
	//	$sql .= "       ,notification.student_id       AS student_id ";
	//	$sql .= "       ,notification.notice_kind      AS notice_kind ";
		$sql .= "       ,notification.notice_judge     AS notice_judge ";
		$sql .= "       ,notification.notice_caption   AS notice_caption ";
		$sql .= "       ,notification.read_flag        AS read_flag ";
		$sql .= "       ,notification.status           AS status ";
		$sql .= "       ,DATE_FORMAT(notification.added_at, '%m/%d %H:%i') AS added_at ";
	//	$sql .= "       ,notification.update_at        AS update_at ";
		$sql .= " FROM  notification LEFT JOIN teacher ON notification.teacher_id = teacher.teacher_id ";
		$sql .= "WHERE 1=1 ";
		$sql .= "  AND notification.school_id   =  {$this->db->escape($param['school_id'])} ";
		$sql .= "  AND notification.teacher_id  =  {$this->db->escape($param['teacher_id'])} ";
		$sql .= "  AND notification.read_flag   =  1 ";
		$sql .= "  AND notification.status      <> 9 ";
		$sql .= "ORDER BY notification.added_at DESC ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [ajax]通知テーブル更新
	//----------------------------------------------
	function update_notification($param) {
		//引数設定
		$param = array_merge(
						array(
							'notification_id'		=> 0,
						),
						$param
					);
		$update_at = date('Y/m/d H:i:s');
		
		//SQL作成
		$sql  = "";
		$sql .= "UPDATE notification ";
		$sql .= "   SET read_flag       = 0, ";
		$sql .= "       update_at       = {$this->db->escape($update_at)}  ";
		$sql .= " WHERE notification_id = {$this->db->escape($param['notification_id'])} ";
		
		$this->db->trans_start();
		$query = $this->db->query($sql);	// true or false
		$this->db->trans_complete();

		//データリターン
		if ($query) {
			return $query;
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [ajax]通知テーブル更新（一括更新）
	//----------------------------------------------
	function update_notification_all($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'				=> 0,
							'teacher_id'			=> 0,
							'notification_id_list'	=> '0',
						),
						$param
					);
		$update_at = date('Y/m/d H:i:s');
		
		//SQL作成
		$sql  = "";
		$sql .= "UPDATE notification ";
		$sql .= "   SET read_flag  = 0, ";
		$sql .= "       update_at  = {$this->db->escape($update_at)}  ";
		$sql .= " WHERE 1 = 1 ";
		$sql .= "   AND read_flag = 1 ";
		$sql .= "   AND notification_id IN ({$this->db->escape_str($param['notification_id_list'])})" ;
		$sql .= "   AND school_id        = {$this->db->escape($param['school_id'])} ";
		$sql .= "   AND teacher_id       = {$this->db->escape($param['teacher_id'])} ";

		$this->db->trans_start();
		$query = $this->db->query($sql);	// true or false
		$this->db->trans_complete();

		//データリターン
		if ($query) {
			return $query;
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 通知テーブルへ保存
	//   取得パラメータ
	//     通知種類（notice_kind）  => 'follower-issue'
	//     ID      （id）           => 資料ID・授業資料ID・図書室ID・ビデオID
	//     処理結果（notice_judge） => 'OK' or 'NG'
	//
	//     cms-material       OK	[資料ID:?] 【資料論理名】 が正常登録終了しました
	//     cms-material       NG	[資料ID:?] 【資料論理名】 が異常登録終了しました	color: #FB8282;
	//     cms-class-material OK	[授業ID:?] 【授業名】 の [授業資料ID:?] 【授業資料論理名】 が正常登録終了しました
	//     cms-class-material OK	[授業ID:?] 【授業名】 の [授業資料ID:?] 【授業資料論理名】 が正常登録終了しました
	//     cms-book-library   OK	[図書室ID:?] 【図書室論理名】]が正常登録終了しました
	//     cms-book-library   NG	[図書室ID:?] 【図書室論理名】]が異常登録終了しました
	//     cms-video          OK	[ビデオID:?] 【ビデオ論理名】]が正常登録終了しました
	//     cms-video          NG	[ビデオID:?] 【ビデオ論理名】]が異常登録終了しました
	//     follower-issue     --	[課題ID:?] 【受講者名】 が 【課題名】 へ課題を提出しました
	//----------------------------------------------
	function insert_notification($param){
		//引数設定
		$param = array_merge(
						array(
							'id'				=> 0,							// ID 
						//	'notification_id'	=> 0,							// 通知ID
							'school_id'			=> 0,							// 学校ID（各テーブルから取得）
							'teacher_id'		=> 0,							// 講師ID（各テーブルから取得）
							'student_id'		=> 0,							// 受講者ID
							'notice_kind'		=> '',							// 通知種類（follower-issue'）
							'notice_judge'		=> 'OK',						// 通知判定
							'notice_caption'	=> '',							// 通知文
							'read_flag'			=> 1,							// 確認フラグ 1:未確認 0:確認済み
							'status'			=> 0,							// 状態 0:有効 9:削除',
							'added_at'			=> date('Y/m/d H:i:s'),			// 登録日時
							'update_at'			=> date('Y/m/d H:i:s'),			// 更新日時
							
							'local_file_name'       => '',
							'update_file_name'      => '',
							'update_date'           => '',
							'update_time'           => '',
							'user_update_file_name' => '',
							'csv_counter'           => 0,
							'csv_error_counter'     => 0,
							'update_counter'        => 0,
						),
						$param
					);
		
		// 正常終了・異常終了の判定
		$notice_judge_output = "正常登録終了";
		if($param['notice_judge'] === 'NG') $notice_judge_output = "異常登録終了";
		
		// 通知種類により通知文・学校ID・講師IDの取得作成
		if( $param['notice_kind']=='cms-material' ){
			$table_data = $this->_get_material($param['id']);
			
			if(count($table_data) > 0){
				if(isset($table_data['material_logic_name'])){
					$param['notice_caption']  = "[資料ID:{$param['id']}] {$table_data['material_logic_name']} が";
					$param['notice_caption'] .= "{$notice_judge_output}しました";
				}else{
					$param['notice_caption']  = "[資料ID:{$param['id']}] {$notice_judge_output}しました";
				}
				if( isset($table_data['school_id']) )  $param['school_id']  = $table_data['school_id'];
				if( isset($table_data['teacher_id']) ) $param['teacher_id'] = $table_data['teacher_id'];
			}
		}elseif( $param['notice_kind']=='cms-class-material' ){
			$table_data = $this->_get_class_material($param['id']);
			
			if(count($table_data) > 0){
				if( isset($table_data['class_name']) && isset($table_data['material_logic_name']) ){
					$param['notice_caption']  = "[授業ID:{$table_data['class_id']}] {$table_data['class_name']} の ";
					$param['notice_caption'] .= "[授業資料ID:{$param['id']}] {$table_data['material_logic_name']} が";
					$param['notice_caption'] .= "{$notice_judge_output}しました";
				}else{
					$param['notice_caption']  = "[授業資料ID:{$param['id']}] {$notice_judge_output}しました";
				}
				if( isset($table_data['school_id']) )  $param['school_id']  = $table_data['school_id'];
				if( isset($table_data['teacher_id']) ) $param['teacher_id'] = $table_data['teacher_id'];
			}
		}elseif( $param['notice_kind']=='cms-book-library' ){
			$table_data = $this->_get_book_library($param['id']);
			
			if(count($table_data) > 0){
				if(isset($table_data['book_library_logic_name'])){
					$param['notice_caption']  = "[図書室ID:{$param['id']}] {$table_data['book_library_logic_name']} が";
					$param['notice_caption'] .= "{$notice_judge_output}しました";
				}else{
					$param['notice_caption']  = "[図書室ID:{$param['id']}] {$notice_judge_output}しました";
				}
				if( isset($table_data['school_id']) )  $param['school_id']  = $table_data['school_id'];
				if( isset($table_data['teacher_id']) ) $param['teacher_id'] = $table_data['teacher_id'];
			}
		}elseif( $param['notice_kind']=='cms-video' ){
			$table_data = $this->_get_video($param['id']);
			
			// 日弁連対応：【ビデオ】を【コンテンツ】に差し替え。
			if(count($table_data) > 0){
				if(isset($table_data['video_logic_name'])){
					$param['notice_caption']  = "[コンテンツID:{$param['id']}] {$table_data['video_logic_name']} が";
					$param['notice_caption'] .= "{$notice_judge_output}しました";
				}else{
					$param['notice_caption']  = "[コンテンツID:{$param['id']}] {$notice_judge_output}しました";
				}
				if( isset($table_data['school_id']) )  $param['school_id']  = $table_data['school_id'];
				if( isset($table_data['teacher_id']) ) $param['teacher_id'] = $table_data['teacher_id'];
			}

		// 日弁連対応：受講者CSVファイルアップデート（ファイルをあげた履歴を入れる。画面には出さない）
		}elseif( $param['notice_kind']=='cms-student-csv-upload' ){
			$param['notice_caption']  = "{$param['local_file_name']},{$param['update_file_name']}";
			
			$param['read_flag']  = 0;

		// 日弁連対応：受講者CSVファイルアップデート（ファイルを使用してバッチ処理結果を入れる）
		}elseif( $param['notice_kind']=='bat_update_student_from_csv' ){
			if($param['user_update_file_name']==''){
				$param['notice_caption'] = "受講者へのCSV更新処理が完了しました";
			}else{
				$param['notice_caption'] = "ファイル名：{$param['user_update_file_name']} の受講者へのCSV更新処理が完了しました";
			}

			$param['notice_caption'] .= "<br/>　CSV更新日時 {$param['update_date']} {$param['update_time']}　　";
			$param['notice_caption'] .= "処理件数 {$param['update_counter']} 件";
			
			if($param['csv_error_counter']>0){
				$param['notice_caption'] .= "　　※CSVファイル {$param['csv_counter']} 件中 {$param['csv_error_counter']} 件に不具合がありました";
			}
		}
		
		// INSERT
		$sql  = '';
		$sql .= "INSERT INTO notification ( ";
		$sql .= "       school_id, teacher_id, student_id, notice_kind, notice_judge, notice_caption, read_flag, status, added_at, update_at ";
		$sql .= ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$this->db->trans_start();
		$this->db->query($sql, 
							array(
								$param['school_id'],
								$param['teacher_id'],
								$param['student_id'],
								$param['notice_kind'],
								$param['notice_judge'],
								$param['notice_caption'],
								$param['read_flag'],
								$param['status'],
								$param['added_at'],
								$param['update_at']
							));
		
		$prev_school_id = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
			return false;
		}
		return true;
	}

	//----------------------------------------------
	// 資料IDから情報の取得
	//----------------------------------------------
	function _get_material($material_id = 0){
		// SQL生成
		$sql  = "";
		$sql .= 'SELECT  material_id ';
	//	$sql .= '       ,material_name ';
		$sql .= '       ,material_logic_name ';
	//	$sql .= '       ,material_caption ';
		$sql .= '       ,school_id ';
		$sql .= '       ,teacher_id ';
	//	$sql .= '       ,page_num ';
	//	$sql .= '       ,status ';
	//	$sql .= "       ,DATE_FORMAT(added_at , '%Y/%m/%d %H:%i:%s') AS added_at ";
	//	$sql .= "       ,DATE_FORMAT(update_at, '%Y/%m/%d %H:%i:%s') AS update_at ";
		$sql .= '  FROM  material ';
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  material_id = {$this->db->escape($material_id)} ";
		
		// データリターン
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		//	return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 授業資料IDから情報の取得
	//----------------------------------------------
	function _get_class_material($class_material_id = 0){
		// SQL生成
		$sql  = "";
		$sql .= 'SELECT  class_material.class_material_id ';
		$sql .= '       ,class_material.class_id ';
		$sql .= '       ,class.class_name ';
	//	$sql .= '       ,class_material.material_name ';
		$sql .= '       ,class_material.material_logic_name ';
		$sql .= '       ,class.school_id ';
		$sql .= '       ,class_material.teacher_id ';
	//	$sql .= '       ,class_material.student_id ';
	//	$sql .= '       ,class_material.page_num ';
	//	$sql .= '       ,class_material.submit_flag ';
	//	$sql .= '       ,class_material.status ';
	//	$sql .= "       ,DATE_FORMAT(class_material.added_at , '%Y/%m/%d %H:%i:%s') AS added_at ";
	//	$sql .= "       ,DATE_FORMAT(class_material.update_at, '%Y/%m/%d %H:%i:%s') AS update_at ";
		$sql .= '  FROM  class_material LEFT JOIN class ON class_material.class_id = class.class_id ';
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  class_material.class_material_id = {$this->db->escape($class_material_id)} ";
		
		// データリターン
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		//	return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 図書室IDから情報の取得
	//----------------------------------------------
	function _get_book_library($book_library_id = 0){
		// SQL生成
		$sql  = "";
		$sql .= 'SELECT  book_library_id ';
	//	$sql .= '       ,book_library_name ';
		$sql .= '       ,book_library_logic_name ';
	//	$sql .= '       ,book_library_caption ';
	//	$sql .= '       ,book_library_tags ';
		$sql .= '       ,school_id ';
		$sql .= '       ,teacher_id ';
	//	$sql .= '       ,page_num ';
	//	$sql .= '       ,original_file_size ';
	//	$sql .= '       ,local_reading_flag ';
	//	$sql .= '       ,local_reading_open ';
	//	$sql .= '       ,local_reading_close ';
	//	$sql .= '       ,stream_flag ';
	//	$sql .= '       ,status ';
	//	$sql .= "       ,DATE_FORMAT(added_at , '%Y/%m/%d %H:%i:%s') AS added_at ";
	//	$sql .= "       ,DATE_FORMAT(update_at, '%Y/%m/%d %H:%i:%s') AS update_at ";
		$sql .= '  FROM  book_library ';
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  book_library_id = {$this->db->escape($book_library_id)} ";
		
		// データリターン
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		//	return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// ビデオIDから情報の取得
	//----------------------------------------------
	function _get_video($video_id = 0){
		// SQL生成
		$sql  = "";
		$sql .= 'SELECT  video_id ';
	//	$sql .= '       ,video_name ';
		$sql .= '       ,video_logic_name ';
	//	$sql .= '       ,video_caption ';
	//	$sql .= '       ,video_tags ';
		$sql .= '       ,school_id ';
		$sql .= '       ,teacher_id ';
	//	$sql .= '       ,page_num ';
	//	$sql .= '       ,original_file_size ';
	//	$sql .= '       ,local_reading_flag ';
	//	$sql .= '       ,local_reading_open ';
	//	$sql .= '       ,local_reading_close ';
	//	$sql .= '       ,status ';
	//	$sql .= "       ,DATE_FORMAT(added_at  , '%Y/%m/%d %H:%i:%s') AS added_at ";
	//	$sql .= "       ,DATE_FORMAT(update_at , '%Y/%m/%d %H:%i:%s') AS update_at ";
		$sql .= '       ,idkey ';
		$sql .= '  FROM  video ';
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  video_id = {$this->db->escape($video_id)} ";
		
		// データリターン
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row_array();
		//	return $query->result_array();
		} else {
			return [];
		}
	}
}
?>
