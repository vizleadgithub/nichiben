<?php
#[AllowDynamicProperties]
class Model_information_wp extends CI_Model  
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
				's_free_word'		=> '',
				'free_word'		=> '',
			),
			$param
		);
		
		$where_service_env = '';
		$service_env = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : '';
		if($service_env == ''){
			$where_service_env = ' AND (service_env is NULL OR service_env = "") ';
		}else{
			$where_service_env = ' AND service_env = '.$this->db->escape($service_env).' ';
		}
		
		//SQL生成
		$sql = "";
		$sql.= "
			SELECT 
			  ID,
			  post_author,
			  post_date,
			  post_content,
			  post_title,
			  post_status,
			  comment_status,
			  ping_status,
			  post_name,
			  post_modified,
			  post_parent,
			  post_type,
			  product_type_add,
			  status,
			  product_id,
			  target,

			  open_date,
			  close_date,
			  url,
			  topfit 

			FROM 
			  wp_posts 
			WHERE 
			  1=1
			  AND post_name LIKE '%news%'
			  AND post_parent=0 
			  AND post_type='post' 
			  AND (post_status = 'publish' OR post_status = 'future')
		";
		if($param['s_free_word']!=""){
			$sql.= ' AND ('.
			' 	post_title      LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
			'	OR post_content LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
			' )';
		}
		/*
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
			($param['free_word'] ?
				' AND ('.
				' 	information_title      LIKE '.'"%'.$this->db->escape_like_str($param['free_word']).'%"'.
				'	OR information_caption LIKE '.'"%'.$this->db->escape_like_str($param['free_word']).'%"'.
				'	OR information_tags    LIKE '.'"%'.$this->db->escape_like_str($param['free_word']).'%"'.
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
		*/
		$query = $this->db->query( $sql);
		$cnt = $query->num_rows();

		$sql.= ' ORDER BY post_modified DESC';
		$sql.= ' LIMIT ?, ?';
		$query = $this->db->query( $sql,
			array(
				(int) $param['offset'],
				(int) $param['rowcount'],
			)
		);


		//データリターン
		if ($query->num_rows() > 0) {
			return array(
				'cnt'	=> $cnt,
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
//				"s_month"     => 0,
			),
			$param
		);
/*
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
*/
		$sql_select = "";
		$sql_select.= "
			SELECT 
			  ID,
			  post_author,
			  post_date,
			  post_content,
			  post_title,
			  post_status,
			  comment_status,
			  ping_status,
			  post_name,
			  post_modified,
			  post_parent,
			  post_type,
			  product_type_add,
			  status,
			  product_id,
			  target,

			  open_date,
			  close_date,
			  url,
			  topfit 

			FROM 
			  wp_posts 
			WHERE 
			  1=1
			  AND post_parent=0 
			  AND post_type='post' 
			  AND product_type_add=0
			  AND (post_status = 'publish' OR post_status = 'future')
		";
		$sql_order = " ORDER BY 
				post_modified desc
		";
		
		return $sql_select . $sql_order;
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


		$sql_select = "";
		$sql_select.= "
			SELECT 
			  ID,
			  post_author,
			  post_date,
			  post_content,
			  post_title,
			  post_status,
			  comment_status,
			  ping_status,
			  post_name,
			  post_modified,
			  post_parent,
			  post_type,
			  product_type_add,
			  status,
			  product_id,
			  target,

			  open_date,
			  close_date,
			  url,
			  topfit 

			FROM 
			  wp_posts 
			WHERE 
			  1=1
			  AND post_parent=0 
			  AND post_type='post' 
			  AND (post_status = 'publish' OR post_status = 'future')
			  AND ID = ? 

		";
		//SQL投入
		$query = $this->db->query($sql_select, [$param['information_id']]);
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
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
		
		if( isset($data['information_date']) ){
			if( date("Y-m-d", strtotime($data['information_date']))==date("Y-m-d") ){
				$data['information_date'] = date("Y-m-d", strtotime($data['information_date']))." ".date("H:i:s");
			}
		}

		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO
						wp_posts 
					(
						  post_author,
						  post_date,
						  post_content,
						  post_title,
						  post_status,
						  comment_status,
						  ping_status,

						  post_name,
						  post_modified,
						  post_parent,
						  post_type,
						  product_type_add,
						  status,

						  open_date,
						  close_date,
						  url,
						  topfit 
					) VALUES (

						?, /*  post_author  */
						?, /*  post_date  */
						?, /*  post_content  */
						?, /*  post_title  */
						?, /*  post_status  */
						?, /*  comment_status  */
						?, /*  ping_status  */

						?, /*  post_name  */
						?, /*  post_modified  */
						?, /*  post_parent  */
						?, /*  post_type  */
						?, /*  product_type_add  */
						?, /*  status  */

						?, /*  open_date  */
						?, /*  close_date  */
						?, /*  url  */
						? /*  topfit  */

					) ";
			$this->db->trans_start();
			$this->db->query($sql, 
				array(
					1,
					$data['information_date'],
					$data['information_caption'],
					$data['information_title'],
					'publish',
					'open',
					'open',

					'news',
					date("Y-m-d H:i:s"),
					0,
					'post',
					0,
					$data['information_type'],//0:お知らせ 1:更新情報

					$data['information_open'],
					$data['information_close'],
					$data['information_url'],
					$data['information_topfit'],//topfit
				));
			//登録idを取得
			$prev_id = $this->db->insert_id();
			$sql = "
			INSERT INTO wp_term_relationships
			(
			  object_id,
			  term_taxonomy_id
			)
			VALUES
			(
			  ?,
			  ?
			)";
			$this->db->query($sql, 
				array(
					$prev_id,
					20,
				));


			
			$this->db->trans_complete();
			
			return array('prev_id' => $prev_id);
		}else{
			//修正
			$sql = "UPDATE
					 wp_posts 
				SET 
					  post_author       = ?,
					  post_date         = ?,
					  post_content      = ?,
					  post_title        = ?,
					  post_status       = ?,
					  comment_status    = ?,
					  ping_status       = ?,

					  post_name         = ?,
					  post_modified     = ?,
					  post_parent       = ?,
					  post_type         = ?,
					  status            = ?,

					  open_date         = ?,
					  close_date        = ?,
					  url               = ?,
					  topfit            = ? 
				WHERE 
					ID = ? ";
			$this->db->trans_start();
			$this->db->query($sql, 
				array(
					1,
					$data['information_date'],
					$data['information_caption'],
					$data['information_title'],
					'publish',
					'open',
					'open',

					'news',
					date("Y-m-d H:i:s"),
					0,
					'post',
					$data['information_type'],//0:お知らせ 1:更新情報

					$data['information_open'],
					$data['information_close'],
					$data['information_url'],
					$data['information_topfit'],//topfit

					$data['information_id']
				));
			$sql = "
				INSERT INTO wp_term_relationships (object_id, term_taxonomy_id)
				VALUES (?, ?)
				ON DUPLICATE KEY UPDATE 
				    object_id = VALUES(object_id),
				    term_taxonomy_id = VALUES(term_taxonomy_id)
			";
			$this->db->query($sql, 
				array(
					$data['information_id'],
					20,
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
				wp_posts
			SET
				post_status = 'trash'
			WHERE 
				ID = ?";


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
			$where_service_env = ' AND service_env = '.$this->db->escape($service_env).' ';
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
			$sql = $sql."   AND school_id = {$this->db->escape($param['school_id'])} ";
		}else{
			$sql = $sql."   AND (school_id = {$this->db->escape($param['school_id'])} OR school_id = 0) ";
		}
		$sql = $sql."   AND '{$wnow}' BETWEEN information_open AND information_close ";

		if($param['select_tag']===""){
			$sql = $sql;
		}elseif($param['select_tag']==='タグなし'){
			$sql = $sql."   AND (information_tags IS NULL OR information_tags ='') ";
		}else{
			$sql = $sql."   AND information_tags LIKE '%{$this->db->escape_str($param['select_tag'])}%' ";
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
			return [];
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
			$where_service_env = ' AND service_env = '.$this->db->escape($service_env).' ';
		}

		// SQL文
		$sql = "";
		$sql = $sql."SELECT information_tags ";
		$sql = $sql."  FROM information ";
		$sql = $sql." WHERE	status = 0 ";
		$sql = $sql."   AND show_teacher = 1 ";
		$sql = $sql."   AND school_id = {$this->db->escape($param['school_id'])} ";
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
			$where_service_env = ' AND service_env = '.$this->db->escape($service_env).' ';
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
