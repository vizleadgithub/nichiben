<?php
class Model_teacher extends CI_Model
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
	//講師検索結果一覧取得
	//----------------------------------------------
	function get_teacher_search_list($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
							'offset'      => 0,
							'rowcount'    => 0,
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_teacher_selectsql($param);
		$sql_limit  = " LIMIT
						 {$param['offset']}, {$param['rowcount']}";
		$query = $this->db->query($sql . $sql_limit);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	//----------------------------------------------
	//講師検索結果件数取得
	//----------------------------------------------
	function get_teacher_search_count($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
						),
						$param
					);
		//SQL生成
		$sql = $this->_get_teacher_selectsql($param);
		$query = $this->db->query($sql);
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	//講師検索結果取得用SQL生成
	//----------------------------------------------
	function _get_teacher_selectsql($param) {
		//引数設定
		$param = array_merge(
						array(
							's_school_id' => 0,
							's_name'      => '',
							's_email'     => '',
							's_id'        => 0,
							's_free_word' => '',
						),
						$param
					);
		//SQL生成
		$sql_select = "SELECT
							teacher_id,
							teacher_name,
							teacher_email,
							teacher_auth 
						FROM
							teacher ";
		$sql_where = " WHERE
							status = 0";
		$sql_order = " ORDER BY
							teacher_id";
		
		//学校ID
		$sql_where .= " AND school_id = {$this->db->escape($param['s_school_id'])}";
		
		//講師氏名
		if (isset($param['s_name']) && $param['s_name'] != '') {
			$sql_where .= " AND teacher_name LIKE '%{$this->db->escape_like_str($param['s_name'])}%'";
		}
		//メールアドレス
		if (isset($param['s_email']) && $param['s_email'] != '') {
			$sql_where .= " AND teacher_email LIKE '%{$this->db->escape_like_str($param['s_email'])}%'";
		}
		//ID
		if (isset($param['s_id']) && $param['s_id'] != '' ) {
			if(is_numeric($param['s_id'])){
				$sql_where .= " AND teacher_id = {$this->db->escape($param['s_id'])}";
			} else {
				$sql_where .= " AND teacher_id = 0";
			}
		}
		//フリーワード
		if (isset($param['s_free_word']) && $param['s_free_word'] != '') {
			$sql_where .= " AND (teacher_name               LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_introduce        LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_introduce_detail LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
								OR teacher_note             LIKE '%{$this->db->escape_like_str($param['s_free_word'])}%'
			)";
		}
		
		return $sql_select . $sql_where . $sql_order;
	}
	
	//----------------------------------------------
	//一件取得
	//[2012/06/11]
	//----------------------------------------------
	function get_teacher($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										teacher_id,
										teacher_name,
										teacher_email,
										teacher_password_encrypt,
										teacher_auth,
										teacher_introduce,
										teacher_introduce_detail,
										teacher_note,
										school_id,
										status,
										update_at
									FROM
										teacher
									WHERE
										teacher_id = {$this->db->escape($param['teacher_id'])}
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}
	
	//----------------------------------------------
	//更新処理
	// [2012/09/10] 条件によりパスワード含む更新・含まない更新に切り替え処理を追加。
	// [2012/09/10] 条件により同じメールアドレスを持つ講師のパスワードを更新する処理を追加。
	//----------------------------------------------
	function update_teacher($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在時刻取得
		$wDate = date('Y/m/d H:i:s');
		
		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO
						teacher 
					(
						teacher_name,
						teacher_email,
						teacher_password_encrypt,
						teacher_auth,
						teacher_introduce,
						teacher_introduce_detail,
						teacher_note,
						school_id,
						status,
						update_at
					)
					VALUES(?,?,?,?,?,?,?,?,?,?)";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['teacher_name'],
									$data['teacher_email'],
									hash('sha256',$data['teacher_password']),
									$data['teacher_auth'],
									$data['teacher_introduce'],
									$data['teacher_introduce_detail'],
									$data['teacher_note'],
									$data['school_id'],
									0,
									$wDate
								));
			//登録idを取得
			$prev_id = $this->db->insert_id();
			
			$this->db->trans_complete();
			
			return array('prev_id' => $prev_id);
		}else{
			//修正
			if($data['teacher_password_change'] == 1){
				// パスワード含む更新
				$sql = "UPDATE
							teacher
						SET 
							teacher_name             = ?,
							teacher_email            = ?,
							teacher_password_encrypt = ?,
							teacher_auth             = ?,
							teacher_introduce        = ?,
							teacher_introduce_detail = ?,
							teacher_note             = ?,
							school_id                = ?,
							status                   = ?,
							update_at                = ?
						WHERE
							teacher_id = ?";
				$this->db->trans_start();
				$this->db->query($sql, 
									array(
										$data['teacher_name'],
										$data['teacher_email'],
										hash('sha256',$data['teacher_password']),
										$data['teacher_auth'],
										$data['teacher_introduce'],
										$data['teacher_introduce_detail'],
										$data['teacher_note'],
										$data['school_id'],
										0,
										$wDate,
										$data['teacher_id']
									));
				$this->db->trans_complete();

				// 同じメールアドレスを持つ講師のパスワードを全て更新する
				if($data['teacher_password_identity'] == 1){
					$sql = "UPDATE
								teacher
							SET 
								teacher_password_encrypt = ?,
								update_at                = ?
							WHERE
								teacher_email = ?";
					$this->db->trans_start();
					$this->db->query($sql, 
										array(
											hash('sha256',$data['teacher_password']),
											$wDate,
											$data['teacher_email']
										));
					$this->db->trans_complete();
				}

			}else{
				// パスワード除く更新
				$sql = "UPDATE
							teacher
						SET 
							teacher_name             = ?,
							teacher_email            = ?,
							teacher_auth             = ?,
							teacher_introduce        = ?,
							teacher_introduce_detail = ?,
							teacher_note             = ?,
							school_id                = ?,
							status                   = ?,
							update_at                = ?
						WHERE
							teacher_id = ?";
				$this->db->trans_start();
				$this->db->query($sql, 
									array(
										$data['teacher_name'],
										$data['teacher_email'],
										$data['teacher_auth'],
										$data['teacher_introduce'],
										$data['teacher_introduce_detail'],
										$data['teacher_note'],
										$data['school_id'],
										0,
										$wDate,
										$data['teacher_id']
									));
				$this->db->trans_complete();
			}
		
			//修正
/*
			$sql = "UPDATE
						teacher
					SET 
						teacher_name             = ?,
						teacher_email            = ?,
						teacher_password_encrypt = ?,
						teacher_auth             = ?,
						teacher_introduce        = ?,
						teacher_introduce_detail = ?,
						teacher_note             = ?,
						school_id                = ?,
						status                   = ?,
						update_at                = ?
					WHERE
						teacher_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['teacher_name'],
									$data['teacher_email'],
									hash('sha256',$data['teacher_password']),
									$data['teacher_auth'],
									$data['teacher_introduce'],
									$data['teacher_introduce_detail'],
									$data['teacher_note'],
									$data['school_id'],
									0,
									$wDate,
									$data['teacher_id']
								));
			$this->db->trans_complete();
*/
			
		}
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_teacher($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id' => 0,
						),
						$param
					);
		$sql = "UPDATE
					teacher
				SET
					status = 9
				WHERE 
					teacher_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['teacher_id']));
		$this->db->trans_complete();
		
	}
	//----------------------------------------------
	//講師ドロップダウン用一覧取得
	//----------------------------------------------
	function get_teacher_dropdown_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id' => 0,
						),
						$param
					);
		//SQL生成
		$sql = "SELECT 
						teacher_id,
						teacher_name
					FROM 
						teacher
					WHERE 
						status = 0
						AND school_id = {$param['school_id']}
					ORDER BY
						teacher_id
				";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	//----------------------------------------------
	//講師名取得
	//----------------------------------------------
	function get_name($param){
		//引数設定
		$param = array_merge(
						array(
							'teacher_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										teacher_name
									FROM
										teacher
									WHERE
										teacher_id = '{$param['teacher_id']}'
									AND
										status = 0
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			return $data['teacher_name'];
		}else{
			return null;
		}
	}
	
	//----------------------------------------------
	//権限更新処理
	//----------------------------------------------
	function update_teacher_auth($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在時刻取得
		$wDate = date('Y/m/d H:i:s');
		
		//修正
		$sql = "UPDATE
					teacher
				SET 
					teacher_auth   = ?,
					update_at      = ?
				WHERE
					teacher_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, 
							array(
								$data['teacher_auth'],
								$wDate,
								$data['teacher_id']
							));
		$this->db->trans_complete();
	}
	
}
?>
