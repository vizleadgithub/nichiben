<?php
#[AllowDynamicProperties]
class Model_material extends CI_Model  
{
	private $material_dir  = '';			//資料アップロードディレクトリ

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
	//資料一覧取得
	// [2012/10/12]検索条件、講座の追加
	//----------------------------------------------
	function get_material_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
				'offset'		=> 0,
				'rowcount'		=> 10,
				's_cource'		=> 0,
				's_free_word'	=> '',
			),
			$param
		);

		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS material . * '.
			'      , if( teacher.teacher_name IS NULL , "'. $this->lang->line_or_def('common_super_user','Super User').'" '.
			'      , teacher.teacher_name ) AS teacher_name '.
			'   FROM material LEFT JOIN teacher ON teacher.teacher_id = material.teacher_id '.
			'  WHERE material.school_id = ?'.
			'    AND material.status <> 9'.
			($param['s_free_word'] ?
				' AND ('.
				' 	material.material_logic_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR material.material_caption LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND material_id IN (SELECT material_id FROM material_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			' ORDER BY material.update_at DESC'.
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
	//一件取得
	//----------------------------------------------
	function get_data($param){
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(array(
			'school_id'		=> $this->session->userdata['cms_master.login.school_id'],
			'material_id'	=> 0,
		), $param);
		//SQL投入
		$query = $this->db->query(
			' SELECT material . * , if( teacher.teacher_name IS NULL '.
			'      , "'.$this->lang->line_or_def('common_super_user','Super User').'" '.
			'      , teacher.teacher_name ) AS teacher_name '.
			'   FROM material LEFT JOIN teacher ON teacher.teacher_id = material.teacher_id '.
			'  WHERE material.school_id = ?'.
			'    AND material.material_id = ?'.
			'  LIMIT 0, 1',
			array(
				$param['school_id'],
				$param['material_id'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
		}
	}
	
	//----------------------------------------------
	//新規登録・更新処理
	// [2012/08/20]更新処理後の戻り値をtrueから資料IDに変更
	//----------------------------------------------
	function update_material($param){

		if ($param['update_flg'] == 0){
			//新規
			$param = array_merge(array(
					'material_name'			=> 'no value',
					'material_logic_name'	=> 'no value',
					'material_caption'		=> 'no value',
					'school_id'				=> 0,
					'teacher_id'			=> 0,
					'page_num'				=> 0,
					'status'				=> 0,
					'added_at'				=> date("Y/m/d H:i:s"),
					'update_at'				=> date("Y/m/d H:i:s"),
			), $param);

			$res = $this->db->query($this->db->insert_string('material', array(
					'material_name'				=> $param['material_name'],
					'material_logic_name'		=> $param['material_logic_name'],
					'material_caption'			=> $param['material_caption'],
					'school_id'					=> $param['school_id'],
					'teacher_id'				=> $param['teacher_id'],
					'page_num'					=> $param['page_num'],
					'status'					=> $param['status'],
					'added_at'					=> $param['added_at'],
					'update_at'					=> $param['update_at'],
				)
			));

			$lastInsertId = $this->db->insert_id();

			$this->material_dir = $this->config->item('material_dir');
			$up_path = $this->material_dir.'/'.$lastInsertId;
			if(!is_dir($up_path)){
				mkdir($up_path, 0777, TRUE);
				chmod($up_path, 0777);
			}

			$this->load->library('upload', array(
				'upload_path'   => $up_path,
				'allowed_types' => '*',
				'overwrite'     => TRUE,
				'remove_spaces' => TRUE,
				'file_name'     => 'master',
			));
			if(!$this->upload->do_upload('local_file')){
				return false;
			} else {
				$upload_data = $this->upload->data();
				chmod($up_path.'/'.$upload_data['file_name'],0777);

				$res = $this->db->query($this->db->update_string('material', array(
						'material_name'	=> $upload_data['file_name'],
					),'material_id='.$lastInsertId
				));

				return array(
					'lastInsertId'	=> $lastInsertId,
				);
			}
		}else{
			//修正
			$wDate = date('Y/m/d H:i:s');
			$sql = "UPDATE
						material
					SET 
						material_logic_name = ?,
						material_caption    = ?,
						update_at           = ?
					WHERE
						material_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$param['material_logic_name'],
									$param['material_caption'],
									$wDate,
									$param['material_id']
								));
			$this->db->trans_complete();
			
		//	return true;
			return array(
				'lastInsertId'	=> $param['material_id'],
			);
		}
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_item($param){
		//引数設定
		$param = array_merge(
			array(
				'material_id' => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('material', array(
				'status'	=> 9,
				'update_at'	=> date('Y/m/d H:i:s'),
			),'material_id='.$param['material_id']
		));

		# ファイルの物理削除（論理削除成功が条件）
		if($res==1){
			$this->load->helper('file');
			$command_text = 'rm -rf '.$this->config->item('material_dir').'/'.$param['material_id'];
			exec($command_text);
		}
		return $res;
	}
	
	//----------------------------------------------
	//未変換資料一覧取得（バッチ用）
	//----------------------------------------------
	function get_unchanged_material() {
		$ret = array();
		$query = $this->db->query(
			' SELECT * FROM material'.
			' WHERE status = 0',
			array(
			)
		);
		foreach($query->result_array() as $row){
			array_push($ret, $row);
		}

		return $ret;
	}
	
	//----------------------------------------------
	//変換済ステータス更新処理（バッチ用）
	//----------------------------------------------
	function update_material_convert_status($param){
		//引数設定
		$param = array_merge(
						array(
							'material_id'	=> 0,
							'page_num'		=> 1,
							'status'		=> 0,
						),
						$param
					);
		$sql = "UPDATE
					material 
				SET
					status = ?,
					page_num = ?
				WHERE 
					material_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['status'], $param['page_num'], $param['material_id']));
		$this->db->trans_complete();
		
	}
	
	//----------------------------------------------
	//本ダウンロード権利確認＋物理ファイル名取得
	// SuperUser:フルアクセス
	// 学校管理者:学校内資料フルアクセス
	// 講師:自分が作成した資料のみ ⇒ 資料管理の権限のある講師[2012/08/10]
	//---------------------------------------------- 
	function get_material_filename($param){
		try{ 
			//全権限付加（libauthから初期値取得、全権限付加後シリアライズ）
			$admin_teacher_auth = $this->libauth->defaultTeacherAuth;
			foreach($admin_teacher_auth as $key => $value){
				$admin_teacher_auth[$key] = 1;
			}
			$admin_teacher_auth = serialize($admin_teacher_auth);
			
			//引数設定
			$param = array_merge(
						array(
							'login_teacher_id' => -1,
							'material_id'      => 0,
						),
						$param
					);
			
			//SQL文作成（SuperUserと、それ以外）
			$sql = '';
			if($param['login_teacher_id'] > 0){
				$sql .= "SELECT material.teacher_id    AS teacher_id ";
				$sql .= "      ,material.material_name AS material_name ";
				$sql .= "      ,if(material.material_logic_name IS NULL , '', material.material_logic_name ) AS material_logic_name ";
				$sql .= "      ,teacher.teacher_auth AS teacher_auth ";
				$sql .= "  FROM material INNER JOIN teacher ON material.school_id = teacher.school_id ";
				$sql .= " WHERE material.status      <> 9 ";
				$sql .= "   AND teacher.status       =  0 ";
				$sql .= "   AND material.material_id =  ? ";
				$sql .= "   AND teacher.teacher_id   =  ? ";
			}else{
				$sql = '';
				$sql .= "SELECT material.teacher_id    AS teacher_id ";
				$sql .= "      ,material.material_name AS material_name ";
				$sql .= "      ,if(material.material_logic_name IS NULL , '', material.material_logic_name ) AS material_logic_name ";
				$sql .= "      ,'" . $admin_teacher_auth. "' AS teacher_auth ";
				$sql .= "  FROM material ";
				$sql .= " WHERE material.status      <> 9 ";
				$sql .= "   AND material.material_id =  ? ";
				$sql .= "   AND material.material_id <> ? ";
			}
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['material_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				$row_array = $query->row_array();
				$work_auth = unserialize($row_array['teacher_auth']);

				// SuperUser：フルアクセスのため、権限有効
				// 学校管理者権限：学校内フルアクセスのため、権限有効
				$download_check = 0;
				if($work_auth['school_admin'] == 1){
					$download_check = 1;
				}else{
			//	// 一般講師：資料権限あり＋作成した講師の場合に、権限有効
			//		if(($work_auth['material'] == 1) && ($row_array['teacher_id'] == $param['login_teacher_id'])){
				// 一般講師：資料権限を持つ講師の場合に、権限有効
					if($work_auth['material'] == 1){
						$download_check = 1;
					}
				}
				
				if($download_check == 1){
					// レコードあり＋権限あり：権限有効
					$return_data['material_name']       = $row_array['material_name'];
					$return_data['material_logic_name'] = $row_array['material_logic_name'];
					$return_data['remarks']             = '';
					return $return_data;
				}else{
					// レコードあり＋権限なし：権限無効
					$return_data['material_name']       = '';
					$return_data['material_logic_name'] = '';
					$return_data['remarks']             = 'no-auth';
					return $return_data;
				}
			}else{
				// レコードなし：権限無効
				$return_data['material_name']       = '';
				$return_data['material_logic_name'] = '';
				$return_data['remarks']             = 'no-data';
				return $return_data;
			}
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['material_name']       = '';
			$return_data['material_logic_name'] = '';
			$return_data['remarks']                 = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
	
	//----------------------------------------------
	//資料修正・削除、権限確認
	// SuperUser:フルアクセス
	// 学校管理者:学校内資料フルアクセス
	// 講師:自分が作成した資料のみ ⇒ 資料管理の権限のある講師[2012/08/10]
	//----------------------------------------------
	function material_edit_delete_auth_check($param){
		try{ 
			//引数設定
			$param = array_merge(
				array(
					'login_teacher_id' => $this->session->userdata['cms_master.login.teacher_id'],
					'material_id'  => 0,
				),
				$param
			);
			
			// Super User権限(-1)：フルアクセスのため、権限有効
			if($param['login_teacher_id'] <= 0){
				$return_data['auth_edit_delete'] = 1;
				$return_data['remarks']     = '';
				return $return_data;
			}
			
			//SQL文作成
			$sql = '';
			$sql .= "SELECT material.teacher_id  AS teacher_id ";
			$sql .= "      ,teacher.teacher_auth AS teacher_auth ";
			$sql .= "  FROM material INNER JOIN teacher ON material.school_id = teacher.school_id ";
			$sql .= " WHERE material.status      <> 9 ";
			$sql .= "   AND teacher.status       =  0 ";
			$sql .= "   AND material.material_id =  ? ";
			$sql .= "   AND teacher.teacher_id   =  ? ";
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['material_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				// レコードあり
				$row_array = $query->row_array();
				
				// 権限（新）より権限有無を確認
				$work_auth  = unserialize($row_array['teacher_auth']);

				// 学校管理者権限：学校内フルアクセスのため、権限有効
				if($work_auth['school_admin'] == 1){
					$return_data['auth_edit_delete'] = 1;
				}else{
			//	// 一般講師：資料権限あり＋作成した講師の場合に、権限有効
			//		if(($work_auth['material'] == 1) && ($row_array['teacher_id'] == $param['login_teacher_id'])){
				// 一般講師：資料権限を持つ講師の場合に、権限有効
					if($work_auth['material'] == 1){
						$return_data['auth_edit_delete'] = 1;
					}else{
						$return_data['auth_edit_delete'] = 0;
					}
				}
				
				$return_data['remarks'] = '';
				return $return_data;
			}else{
				// レコードなし：権限無効
				$return_data['auth_edit_delete']   = 0;
				$return_data['remarks']       = 'no-data';
				return $return_data;
			}
		
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['auth_edit_delete'] = 0;
			$return_data['remarks']     = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
	
	//----------------------------------------------
	// [2012/08/20]資料講座マスタから講座ID取得
	//----------------------------------------------
	function get_material_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'material_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										material_lecture
									WHERE
										material_id = {$this->db->escape($param['material_id'])}
									ORDER BY
										cource_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// [2012/08/20]資料講座マスタの登録更新処理
	//----------------------------------------------
	function update_material_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'material_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM material_lecture
				WHERE
					material_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['material_id'],
							));
		
		//選択受講講座を登録
		foreach($data['material_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						material_lecture
					(
						material_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['material_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// [2012/11/20]資料チェックボックス用一覧取得
	//----------------------------------------------
	function get_material_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//SQL生成
		$sql  = '';
		$sql .= "SELECT material_id ,material_logic_name ";
		$sql .= "  FROM material ";
		$sql .= " WHERE status <> 9 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= " ORDER BY material_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の資料を取得
	//----------------------------------------------
	function get_cource_material($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'	=> 0,
							'cource_id'	=> 0,
							'free_word'	=> '',
						),
						$param
					);

		//SQL生成
		$sql  = "";
		$sql .= "SELECT material_id ,material_logic_name ";
		$sql .= "      ,IF( INSTR( material_name, '.' ) >0, SUBSTRING( material_name, INSTR( material_name, '.' ) +1 ) , 'unknown' ) AS ext ";
		$sql .= "  FROM material ";
		$sql .= " WHERE status <> 9 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";

		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        material_logic_name LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR material_caption    LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		$sql .= " ORDER BY material_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

}
?>
