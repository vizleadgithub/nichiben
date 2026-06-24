<?php
#[AllowDynamicProperties]
class Model_category extends CI_Model  
{
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	
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
	//一覧取得
	//----------------------------------------------
	function get_category_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'                 => 0,
				'offset'                    => 0,
				'rowcount'                  => 10,
				's_category_name' => '',  // 名
				's_category_id'   => 0,   // ID
				's_free_word'               => '',  // フリーワード
			),
			$param
		);

		$where ="";
		$orderby = " ORDER BY wp_terms.slug ASC";
		if($param["s_category_name"]!=""){
			$where.=" AND wp_terms.name LIKE '%".$this->db->escape_like_str($param["s_category_name"])."%' ";
		}
		if($param["s_free_word"]!=""){
			$where.=" AND wp_terms.name LIKE '%".$this->db->escape_like_str($param["s_free_word"])."%' ";
		}
		if($param["s_category_id"]!="" && intval($param["s_category_id"])>0){
			$where.=" AND wp_terms.term_id = '".$this->db->escape($param["s_category_id"])."' ";
		}
		$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' ".$where.$orderby;
		$query = $this->db->query($sql);
		$category_ids = [];
		if ($query->num_rows() > 0) {
			$mst_category_list = $query->result_array();
			foreach($mst_category_list as $index => $row){
				$category_ids[] = $row["term_id"];
			}
		}

		$category_list = [];
		$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21' GROUP BY wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name ".$orderby;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$category_list = $query->result_array();
			foreach($category_list as $index => $row){
				$parent_name = $row["name"]."＞";
				$category_list[$index]["children"] = [];
				$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent, concat('".$parent_name."',wp_terms.name) as name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent=? GROUP BY wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name ".$orderby;
				$query = $this->db->query($sql, array($row["term_id"]));
				if ($query->num_rows() > 0) {
					$category_list[$index]["children"] = $query->result_array();
					foreach($category_list[$index]["children"] as $index2 => $row2){
						$parent_name2 = $parent_name.$row2["name"]."＞";
						$category_list[$index]["children"][$index2]["children"] = [];
						$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent, concat('".$parent_name2."',wp_terms.name) as name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent=? GROUP BY wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name ".$orderby;
						$query = $this->db->query($sql, array($row2["term_id"]));
						if ($query->num_rows() > 0) {
							$category_list[$index]["children"][$index2]["children"] = $query->result_array();
							foreach($category_list[$index]["children"][$index2]["children"] as $index3 => $row3){
								$parent_name3 = $parent_name2.$row3["name"]."＞";
								$category_list[$index]["children"][$index2]["children"][$index3]["children"] = [];
								$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent, concat('".$parent_name3."',wp_terms.name) as name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent=? GROUP BY wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name ".$orderby;
								$query = $this->db->query($sql, array($row3["term_id"]));
								if ($query->num_rows() > 0) {
									$category_list[$index]["children"][$index2]["children"][$index3]["children"] = $query->result_array();
									foreach($category_list[$index]["children"][$index2]["children"][$index3]["children"] as $index4 => $row4){
										$parent_name4 = $parent_name3.$row4["name"]."＞";
										$category_list[$index]["children"][$index2]["children"][$index3]["children"][$index4]["children"] = [];
										$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent, concat('".$parent_name4."',wp_terms.name) as name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent=? GROUP BY wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name ".$orderby;
										$query = $this->db->query($sql, array($row4["term_id"]));
										if ($query->num_rows() > 0) {
											$category_list[$index]["children"][$index2]["children"][$index3]["children"][$index4]["children"] = $query->result_array();
										}
									}
								}
							}
						}
					}
				}
			}
		}
//var_dump($category_list);

		$temp_category_list = [];
		foreach($category_list as $index => $row){
			if( in_array($row["term_id"],$category_ids) ){
				$temp_category_list[] = [
					"term_id" => $row["term_id"],
					"parent" => $row["parent"],
					"name" => $row["name"],
				];
			}
			foreach($category_list[$index]["children"] as $index2 => $row2){
				if( in_array($row2["term_id"],$category_ids) ){
					//$temp_category_list[] = $row2;
					$temp_category_list[] = [
						"term_id" => $row2["term_id"],
						"parent" => $row2["parent"],
						"name" => $row2["name"],
					];
				}
				foreach($category_list[$index]["children"][$index2]["children"] as $index3 => $row3){
					if( in_array($row3["term_id"],$category_ids) ){
						//$temp_category_list[] = $row3;
						$temp_category_list[] = [
							"term_id" => $row3["term_id"],
							"parent" => $row3["parent"],
							"name" => $row3["name"],
						];
					}
					foreach($category_list[$index]["children"][$index2]["children"][$index3]["children"] as $index4 => $row4){
						if( in_array($row4["term_id"],$category_ids) ){
							//$temp_category_list[] = $row4;
							$temp_category_list[] = [
								"term_id" => $row4["term_id"],
								"parent" => $row4["parent"],
								"name" => $row4["name"],
							];
						}
						foreach($category_list[$index]["children"][$index2]["children"][$index3]["children"][$index4]["children"] as $index5 => $row5){
							if( in_array($row5["term_id"],$category_ids) ){
								//$temp_category_list[] = $row5;
								$temp_category_list[] = [
									"term_id" => $row5["term_id"],
									"parent" => $row5["parent"],
									"name" => $row5["name"],
								];
							}
						}
					}
				}
			}
		}
		
//var_dump($temp_category_list);
		return $temp_category_list;
	}

	function get_root_category_list() {
		// load language
		$this->lang->load('common');
		$where ="";

		$orderby = " ORDER BY wp_terms.slug ASC";
		$category_list = [];
		$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21'".$where.$orderby;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$category_list = $query->result_array();
		}

		return $category_list;
	}

	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_category($param){
		//引数設定
		$param = array_merge(
			array(
				'term_id' => 0,
			),
			$param
		);
		
		//SQL生成
		$query = $this->db->query("
			 SELECT 
			   wp_term_taxonomy.term_id,
			   wp_term_taxonomy.parent,
			   wp_terms.slug,
			   wp_terms.name 
			FROM 
			   wp_term_taxonomy 
			   left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id 
			  WHERE wp_term_taxonomy.term_id  = {$this->db->escape($param['term_id'])}  
		");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//新規登録・更新処理
	//----------------------------------------------
	function update_category($param){
		
		$this->load->helper('json');
		
		//引数設定
		$param = array_merge(
			array(
				'data' => array(),
			),
			$param
		);
		$data = $param['data'];

		$lastInsertId = 0;

		if ($data['update_flg'] == 0){
			//新規
			$sql = "INSERT INTO wp_terms(name, slug, term_group) VALUES( ?, ?, ? )";
			$res = $this->db->query($sql, 
				array(
					$data['name'],
					$data['slug'],
					0,
				)
			);
			$lastInsertId = $this->db->insert_id();
		}else{
			//修正
			$sql = "UPDATE wp_terms SET name=?, slug=? WHERE term_id=?";
			$res = $this->db->query($sql, 
				array(
					$data['name'],
					$data['slug'],
					$data['term_id'],
				)
			);
			$lastInsertId = $data['term_id'];
		}
		
		if($lastInsertId > 0){
			$wDate = date('Y/m/d H:i:s');
			
			$this->db->trans_begin();

			$sql = "
				INSERT INTO wp_term_taxonomy(term_id, taxonomy, parent) 
					VALUES (?, 'category', ?) 
					ON DUPLICATE KEY UPDATE 
					parent = VALUES(parent) 
			";
			$this->db->query(
				$sql, 
				array(
					$lastInsertId,
					$data['parent'],
				)
			);
			$this->db->trans_commit();
		}

		return array(
			'lastInsertId'	=> $lastInsertId,
		);
	}

	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_category($param){
		//引数設定
		$param = array_merge(
			array(
				'term_id'  => 0,
			),
			$param
		);

		# テーブルへの削除
		$sql = "DELETE FROM wp_terms WHERE term_id=?";
		$res = $this->db->query(
			$sql,
			array(
				$param['term_id'],
			)
		);

		return $res;
	}


}
?>
