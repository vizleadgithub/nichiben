<?php
class Model_school extends CI_Model  
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
	//学校一覧取得
	// [2012/08/31]取得カラム「contract」追加。
	//----------------------------------------------
	function get_school_list_all() {
		//language設定
		$sql_where = '';
		if(getenv('URL_SERVICE') == 'alfsales'){
			$sql_where = ' AND school.lang = "alfsales" ';
		}elseif(getenv('URL_SERVICE') == 'conference'){
			$sql_where = ' AND school.lang = "conference" ';
		}else{
			$sql_where = ' AND (lang <> "alfsales" AND lang <> "conference") ';
		}
		
		//SQL生成
		$sql   = "SELECT school_id,school_name,school_caption,school_note,contract,status,update_at
					FROM school
					WHERE status = 0".
					$sql_where
		;
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

}
?>
