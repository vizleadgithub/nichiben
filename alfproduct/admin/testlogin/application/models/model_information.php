<?php
class Model_information extends CI_Model  
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
	//お知らせ検索結果一覧取得
	// [2012/10/12]取得方式変更（タグ・フリーワード条件追加）
	//----------------------------------------------
	function get_information_search_list($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
				'offset'		=> 0,
				'rowcount'		=> 10,
				's_tag'			=> '',
				's_free_word'	=> '',
			),
			$param
		);
		
		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}
		
		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS '.
			'        information_id, '.
			"        DATE_FORMAT(information_date , '%Y/%m/%d') AS information_date, ".
			'        information_title, '.
			"        DATE_FORMAT(information_open , '%Y%m%d%H%i%s') AS information_open,  ".
			"        DATE_FORMAT(information_close, '%Y%m%d%H%i%s') AS information_close, ".
			'        school_id, '.
			'        show_teacher, '.
			'        show_student '.
			'   FROM information '.
			($this->libauth->get_teacher_id() == -1 ? '  WHERE (school_id = ? OR school_id = 0) ' : '  WHERE school_id = ? ').
			'    AND status    = 0 '.
			$where_service_env.
			($param['s_free_word'] ?
				' AND ('.
				' 	information_title      LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR information_caption LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR information_tags    LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] === 'タグなし') ?
				' AND ( information_tags IS NULL OR information_tags = "" ) '
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] !== 'タグなし') ?
				' AND information_tags LIKE "%'.$this->db->escape_like_str($param['s_tag']).'%" '
				: ''
			).
			' ORDER BY school_id, information.information_date, information_open, information_id'.
			' LIMIT ?, ?',
			array(
				(int) $param['school_id'],
				(int) $param['offset'],
				(int) $param['rowcount'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			$cnt = $this->db->query('SELECT FOUND_ROWS() as rowcount');
			$cnt = $cnt->row_array();
			return array(
				'cnt'	=> $cnt['rowcount'],
				'items'	=> $query->result_array(),
			);
		} else {
			return array(
				'cnt'	=> 0,
				'items'	=> array(),
			);
		}
	}
	//----------------------------------------------
	//お知らせ検索結果一覧件数取得
	//----------------------------------------------
	function get_information_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							"s_school_id" => 0,
//							"s_month"     => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_information_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//お知らせ検索結果一覧SQL生成
	//----------------------------------------------
	function _get_information_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							"s_school_id" => 0,
//							"s_month"     => 0,
						),
						$param
					);

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}

		$sql_select = "SELECT 
							information_id, 
							DATE_FORMAT(information_date , '%Y/%m/%d') AS information_date,
							information_title, 
							DATE_FORMAT(information_open , '%Y%m%d%H%i%s') AS information_open,
							DATE_FORMAT(information_close, '%Y%m%d%H%i%s') AS information_close,
							show_teacher, 
							show_student 
						FROM information 
					";
		$sql_where = " WHERE status = 0".$where_service_env;
		$sql_order = " ORDER BY 
								information.information_date,
								information_open,
								information_id
					";
		
		//学校ID
		if ($param['s_school_id'] != 0) {
			$sql_where .= " AND school_id = {$param['s_school_id']}";
		}
		//年月
//		if ($param['s_month'] != 0) {
//			$sql_where .= " AND DATE_FORMAT(information_date , '%Y%m') = {$param['s_month']}";
//		}
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//管理者トップページ用お知らせ一覧取得
	// [2012/10/05]ORDER BY 変更
	// [2012/12/28]全学校お知らせ対応
	//----------------------------------------------
	function get_information_list_toppage($param) {
		//引数設定
		$param = array_merge(
						array(
							"school_id" => 0,
						),
						$param
					);
		
		//現在日時の取得
		$wnow       = date('Y/m/d H:i:s');

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}
		
		//SQL生成
		$sql_select = "
			SELECT *, DATE_FORMAT(information_date , '%Y/%m/%d') AS information_date
			FROM information
		";
//		$sql_where = "
//			WHERE
//				status = 0
//				AND show_teacher = 1
//				AND school_id = {$param['school_id']}
//				AND '{$wnow}' BETWEEN information_open AND information_close
//		";
//		$sql_order = "
//			ORDER BY
//				information_date DESC,
//				information_open DESC,
//				update_at DESC
//		";
		$sql_where = "
			WHERE
				status = 0
				AND show_teacher = 1
				AND (school_id = {$param['school_id']} OR school_id = 0)
				AND '{$wnow}' BETWEEN information_open AND information_close
		";
		$sql_order = "
			ORDER BY
				school_id ASC,
				information_date DESC,
				information_open DESC,
				update_at DESC
		";
		$sql_limit = "
			LIMIT 0, 10
		";

		$query = $this->db->query($sql_select . $sql_where . $where_service_env . $sql_order . $sql_limit);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	
	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_information($param){
		//引数設定
		$param = array_merge(
						array(
							"information_id" => 0,
						),
						$param
					);

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}

		//SQL投入
		$query = $this->db->query("
									SELECT
										information_id,
										DATE_FORMAT(information_date,  '%Y/%m/%d') as information_date,
										information_title,
										information_caption,
										information_tags,
										DATE_FORMAT(information_open,  '%Y/%m/%d %H:%i:%s') as information_open,
										DATE_FORMAT(information_close, '%Y/%m/%d %H:%i:%s') as information_close,
										school_id,
										show_teacher,
										show_student,
										status,
										update_at
									FROM
										information
									WHERE
										information_id = '{$param['information_id']}'
									AND
										status = 0
								".$where_service_env);
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}
	
	//----------------------------------------------
	//更新処理
	//----------------------------------------------
	function update_information($param){
		//引数設定
		$param = array_merge(
						array(
							"data" => array(),
						),
						$param
					);
		$data = $param['data'];
		
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = '';
		}else{
			$where_service_env = $service_env;
		}
		
		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO
						information 
					(
						information_date,
						information_title,
						information_caption,
						information_tags,
						information_open,
						information_close,
						school_id,
						show_teacher,
						show_student,
						service_env,
						status,
						update_at
					)
					VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['information_date'],
									$data['information_title'],
									$data['information_caption'],
									$data['information_tags'],
									$data['information_open'],
									$data['information_close'],
									$data['school_id'],
									$data['show_teacher'],
									$data['show_student'],
									$where_service_env,
									0,
									$wDate
								));
			//登録idを取得
			$prev_id = $this->db->insert_id();
			
			$this->db->trans_complete();
			
			return array('prev_id' => $prev_id);
		}else{
			//修正
			$sql = "UPDATE
						information
					SET 
						information_date    = ?,
						information_title   = ?,
						information_caption = ?,
						information_tags    = ?,
						information_open    = ?,
						information_close   = ?,
						school_id           = ?,
						show_teacher        = ?,
						show_student        = ?,
						service_env         = ?,
						status              = ?,
						update_at           = ?
					WHERE
						information_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['information_date'],
									$data['information_title'],
									$data['information_caption'],
									$data['information_tags'],
									$data['information_open'],
									$data['information_close'],
									$data['school_id'],
									$data['show_teacher'],
									$data['show_student'],
									$where_service_env,
									0,
									$wDate,
									$data['information_id']
								));
			$this->db->trans_complete();
			
		}
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_information($param){
		//引数設定
		$param = array_merge(
						array(
							"information_id" => 0,
						),
						$param
					);
		$sql = "UPDATE
					information
				SET
					status = 9
				WHERE 
					information_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['information_id']));
		$this->db->trans_complete();
		
	}

	//----------------------------------------------
	// [2012/10/05]お知らせ取得（タグ条件）
	// [2012/12/28]全学校お知らせ対応
	//----------------------------------------------
	function get_information_list_detail($param) {
		//引数設定
		$param = array_merge(
						array(
							"school_id"		=> 0,
							"select_tag"	=> "",
						),
						$param
					);
		
		//現在日時の取得
		$wnow       = date('Y/m/d H:i:s');

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}

		//SQL生成
		$sql = "";
		$sql = $sql."SELECT *, DATE_FORMAT(information_date , '%Y/%m/%d') AS information_date ";
		$sql = $sql."        , DATE_FORMAT(information_open , '%Y/%m/%d %H:%i:%s') AS information_open ";
		$sql = $sql."        , DATE_FORMAT(information_close, '%Y/%m/%d %H:%i:%s') AS information_close ";
		$sql = $sql."  FROM information ";
		$sql = $sql." WHERE	status = 0 ";
		$sql = $sql."   AND show_teacher = 1 ";
	//	$sql = $sql."   AND school_id = {$param['school_id']} ";
		if($param['select_tag']==='タグなし'){
			$sql = $sql."   AND school_id = {$param['school_id']} ";
		}else{
			$sql = $sql."   AND (school_id = {$param['school_id']} OR school_id = 0) ";
		}
		$sql = $sql."   AND '{$wnow}' BETWEEN information_open AND information_close ";

		if($param['select_tag']===""){
			$sql = $sql;
		}elseif($param['select_tag']==='タグなし'){
			$sql = $sql."   AND (information_tags IS NULL OR information_tags ='') ";
		}else{
			$sql = $sql."   AND information_tags LIKE '%{$param['select_tag']}%' ";
		}
		
		$sql = $sql.$where_service_env;
		
	//	$sql = $sql." ORDER BY information_date DESC, information_open DESC, update_at DESC";
		$sql = $sql." ORDER BY school_id ASC, information_date DESC, information_open DESC, update_at DESC";

		if($param['select_tag']===""){
			$sql = $sql." LIMIT 0, 10 ";
		}
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}


	//----------------------------------------------
	// [2012/10/05]お知らせタグ取得
	//----------------------------------------------
	function get_information_tags($param) {
		//引数設定
		$param = array_merge(
						array(
							"school_id"		=> 0,
						),
						$param
					);

		//現在日時の取得
		$wnow       = date('Y/m/d H:i:s');

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}

		// SQL文
		$sql = "";
		$sql = $sql."SELECT information_tags ";
		$sql = $sql."  FROM information ";
		$sql = $sql." WHERE	status = 0 ";
		$sql = $sql."   AND show_teacher = 1 ";
		$sql = $sql."   AND school_id = {$param['school_id']} ";
		$sql = $sql."   AND '{$wnow}' BETWEEN information_open AND information_close ";
		$sql = $sql.$where_service_env;
		
		$query = $this->db->query($sql);
		$tagKeys = array();
		foreach($query->result_array() as $row){
			if($row['information_tags']){
				foreach(explode(",", $row['information_tags']) as $_tag){
					if($_tag === ''){
						continue;
					}
					if(!isset($tagKeys[$_tag])){
						$tagKeys[$_tag] = 0;
					}
					$tagKeys[$_tag]++;
				}
			}
			else{
				if(!isset($tagKeys['タグなし'])){
					$tagKeys['タグなし'] = 0;
				}
				$tagKeys['タグなし']++;
			}
		}
		return $tagKeys;
	}

	//----------------------------------------------------------------------//
	// [2012/10/12]タグドロップダウン用一覧取得
	//----------------------------------------------------------------------//
	function get_tag_dropdown_list($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);

		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = "'.$service_env.'" ';
		}

		$query = $this->db->query(
			' SELECT information_tags'.
			'   FROM information'.
			'  WHERE school_id   = ? '.
			$where_service_env.
			'    AND status      = 0',
			array(
				$param['school_id'],
			)
		);

		$tagKeys = array();
		foreach($query->result_array() as $row){
			if($row['information_tags']){
				foreach(explode(",", $row['information_tags']) as $_tag){
					if($_tag === ''){
						continue;
					}
					if(!isset($tagKeys[$_tag])){
						$tagKeys[$_tag] = 0;
					}
					$tagKeys[$_tag]++;
				}
			}
			else{
				if(!isset($tagKeys['タグなし'])){
					$tagKeys['タグなし'] = 0;
				}
				$tagKeys['タグなし']++;
			}
		}
		return $tagKeys;
	}
}
?>
