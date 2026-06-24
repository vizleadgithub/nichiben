<?php
#[AllowDynamicProperties]
class Model_ranking extends CI_Model  
{

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		$this->load->model('Modelschoolcontract');
	}
	//----------------------------------------------
	//----------------------------------------------
	function get_ranking_list($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
			),
			$param
		);

		//SQL生成
		$query = $this->db->query(
			' SELECT 
				tbl_product.product_id,
				tbl_product.product_name,
				ranking.`rank` '.
			'   FROM tbl_product INNER JOIN ranking ON tbl_product.product_id = ranking.product_id LIMIT 20'.
			' ',
			array(
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}
	//----------------------------------------------
	function search_product_name($param){
		//引数設定
		$param = array_merge(
			array(
				'product_name' => '',
			),
			$param
		);
		$sql = "";
		$arr_data = array();
		if( $param['product_name']!="" ){
			//SQL生成
			$sql  = "";
			$sql .= "SELECT tbl_product.product_id,tbl_product.product_name ";
			$sql .= "  FROM tbl_product ";
			$sql .= " WHERE del_flg = 0 ";
			$sql .= "   AND product_name LIKE '%{$this->db->escape_like_str($param['product_name'])}%' limit 100";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {
				$arr_data = $query->result_array();
			}
		}
		//データリターン
		return $arr_data;

	}
	//----------------------------------------------
	function set_ranking($param){
		//引数設定
		$param = array_merge(
			array(
				'product_id'	=> 0,
				'rank'		=> 0,
			),
			$param
		);
		$sql = "
			INSERT INTO 
			  ranking ( 
			    product_id, 
			    `rank` 
			  ) VALUES ( 
			    ?, 
			    ? 
			  ) ON DUPLICATE KEY UPDATE 
			    product_id = ?, 
			    `rank` = ? 
		 ";
		$this->db->query($sql, 
			array(
				$param['product_id'],
				$param['rank'],
				$param['product_id'],
				$param['rank'],
			)
		);
	}
	//----------------------------------------------
	function update_ranking($param){
		//引数設定
		$param = array_merge(
			array(
				'product_id'		=> 0,
				'rank'			=> 0,
			),
			$param
		);

		$sql = "
			INSERT INTO 
			  ranking ( 
			    product_id, 
			    `rank` 
			  ) VALUES ( 
			    ?, 
			    ? 
			  ) ON DUPLICATE KEY UPDATE 
			    product_id = ? 
		 ";
		$this->db->query($sql, 
			array(
				$param['product_id'],
				$param['rank'],
				$param['product_id'],
			)
		);
	}
	//----------------------------------------------
	function update_ranking_add($param){
		//引数設定
		$param = array_merge(
			array(
				'no'		=> 0,
				'file_text'	=> '',
				'file_name'	=> '',
				'file_path'	=> '',
			),
			$param
		);

		$sql = "
			INSERT INTO 
			  ranking_add ( 
			    no, 
			    file_text, 
			    file_name, 
			    file_path 
			  ) VALUES ( 
			    ?, 
			    ?, 
			    ?, 
			    ? 
			  ) ON DUPLICATE KEY UPDATE 
			    file_text = ?, 
			    file_name = ?, 
			    file_path = ? 
		 ";
		$this->db->query($sql, 
			array(
				$param['no'],
				$param['file_text'],
				$param['file_name'],
				$param['file_path'],
				$param['file_text'],
				$param['file_name'],
				$param['file_path'],
			)
		);
	}
	//----------------------------------------------
	function get_file_list($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
			),
			$param
		);

		//SQL生成
		$query = $this->db->query(
			' SELECT 
				* '.
			'   FROM ranking_add '.
			' ',
			array(
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}
	//----------------------------------------------
}

?>
