<?php
#[AllowDynamicProperties]
class Model_class_material extends CI_Model  
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
	//授業に登録済みの資料一覧の取得（class_material）
	//----------------------------------------------
	function get_class_material_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'           => 0,
							'login_teacher_id'    => -1,
							'class_id'            => 0,
							'auth_class_material' => 0,
							'delete_check_list'    => '',
						),
						$param
					);
		
		//SQL生成
		$sql = '';
		$sql .= "SELECT class_material.class_material_id   AS class_material_id ";
		$sql .= "      ,class_material.material_name       AS material_name ";
		$sql .= "      ,class_material.material_logic_name AS material_logic_name ";
		$sql .= "      ,if(class_material.teacher_id is null,-1,class_material.teacher_id) AS teacher_id ";
		$sql .= "      ,if(class_material.student_id is null,-1,class_material.student_id) AS student_id ";
		$sql .= "      ,class_material.submit_flag         AS submit_flag ";
		$sql .= "      ,class_material.status              AS status ";
		$sql .= "  FROM class_material ";

	//	$sql .= " WHERE class_material.status      = 1 ";
		$sql .= " WHERE class_material.status      <> 9 ";

	//	$sql .= "   AND class_material.submit_flag = 1 ";
	//	$sql .= "   AND (class_material.student_id > 0 AND class_material.submit_flag = 1 )";
		$sql .= "   AND ( (class_material.submit_flag = 1) OR (class_material.teacher_id > 0 AND class_material.submit_flag = 0 ) ) ";

		$sql .= "   AND class_material.class_id    = ? ";
		if($param['auth_class_material'] == 2){
			$sql .= "   AND class_material.teacher_id     = ".$param['login_teacher_id']." ";
		}
		if(!$param['delete_check_list']==''){
			$sql .= "   AND class_material.class_material_id IN (".$param['delete_check_list'].") ";
		}
		$sql .= " ORDER BY class_material.update_at           DESC ";
		$sql .= "         ,class_material.material_logic_name ASC ";
		
		//Query実行
		$query = $this->db->query($sql, array(
				$param['class_id']
			)); 

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//授業に登録可能な資料一覧の取得（material）
	// [2012/08/20]資料表示条件に講座IDの概念を追加
	//----------------------------------------------
	function get_material_list($param) {
		// load language
		$this->lang->load('common');

		//引数設定
		$param = array_merge(
						array(
							'school_id'           => 0,
							'login_teacher_id'    => -1,
							'class_id'            => 0,		//未使用
							'auth_class_material' => 0,
							'insert_check_list'   => '',
						),
						$param
					);

		//[2012/08/20]生徒講座マスタ・資料講座マスタを元に表示対象資料IDを取得
		$token    = 'material_cource_'.$param['school_id'].'_'.$param['class_id'];
		$userdata = '-1';
		
		try{ 
			$sql  = '';
			$sql .= 'SELECT material_id ';
			$sql .= '  FROM material_lecture ';
			$sql .= ' WHERE cource_id IN (SELECT cource_id FROM class WHERE class_id = ?)';
			
			# Query実行
			$query = $this->db->query($sql, array(
					$param['class_id'],
				)); 
			
			# userdata の確認
			if ($query->num_rows() > 0){
				foreach($query->result_array() as $result_data){
					$userdata = $userdata.','.$result_data['material_id'];
				}
			//	$records = $query->row_array();
			}else{
				$userdata = '-1';
			}
		}catch(Exception $e){ 
			$userdata = '-1';
		}
		
		//SQL生成
		$sql = '';
		$sql .= "SELECT material.material_id         AS material_id ";
		$sql .= "      ,material.material_name       AS material_name ";
		$sql .= "      ,material.material_logic_name AS material_logic_name ";
		$sql .= "      ,material.teacher_id          AS teacher_id ";
		$sql .= "      ,if( teacher.teacher_name IS NULL , '". $this->lang->line_or_def('common_super_user','Super User')."' , teacher.teacher_name ) AS teacher_name ";
		$sql .= "  FROM material LEFT JOIN teacher ON material.teacher_id = teacher.teacher_id ";
		$sql .= " WHERE material.status      = 1 ";
		$sql .= "   AND material.school_id   = ? ";
		$sql .= "   AND material.material_id IN (".$userdata.") ";	// [2012/08/20]
		if($param['auth_class_material'] == 2){
			$sql .= "   AND material.teacher_id    = ".$param['login_teacher_id']." ";
			$sql .= "   AND teacher.teacher_id     = ".$param['login_teacher_id']." ";
			$sql .= "   AND teacher.status         = 0 ";
		}
		if(!$param['insert_check_list']==''){
			$sql .= "   AND material.material_id IN (".$param['insert_check_list'].") ";
		}
		$sql .= " ORDER BY material.update_at           DESC ";
		$sql .= "         ,material.material_logic_name ASC ";
		
		//Query実行
		$query = $this->db->query($sql, array(
				$param['school_id'],
			)); 

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//授業へ登録した資料の削除（class_material）
	//  １レコード単位
	//----------------------------------------------
	function delete_class_material($param) {

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('class_material', array(
				'status'	=> 9,
				'update_at'	=> date('Y/m/d H:i:s'),
			),'class_material_id='.$param['class_material_id']
		));

		# ファイルの物理削除（論理削除成功が条件）
		if($res==1){
			// 先生資料・先生ノート
			exec('rm -rf '.$this->config->item('class_material_dir').'/'.$param['class_id'].'/teacher_'.$param['teacher_id'].'/'.$param['class_material_id'].'/');
			// 生徒から提出されたノート
			exec('rm -rf '.$this->config->item('class_material_dir').'/'.$param['class_id'].'/student_'.$param['student_id'].'/'.$param['class_material_id'].'/submit/');
		}
		return $res;
	}

	//----------------------------------------------
	//授業へ登録した資料の修正（class_material）
	//  授業管理にて、講師を変更した場合の対応
	//  １レコード単位
	//----------------------------------------------
	function change_teacher_class_material($param) {

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('class_material', array(
				'teacher_id' => $param['new_teacher_id'],
				'update_at'  => date('Y/m/d H:i:s'),
			),'class_id='.$param['class_id'].' AND teacher_id='.$param['old_teacher_id']
		));

		# 授業資料のディレクトリ名の変更
		if($res==1){
			$class_material_dir = $this->config->item('class_material_dir').'/'.$param['class_id'];
			$old_search_dir     = $class_material_dir.'/teacher_'.$param['old_teacher_id'].'/';
			$new_search_dir     = $class_material_dir.'/teacher_'.$param['new_teacher_id'].'/';
			
			if(is_dir($old_search_dir)){
				$result = rename($old_search_dir, $new_search_dir);
			}
		}
		return $res;
	}

	//----------------------------------------------
	//資料を授業資料に登録（class_material）
	//  １レコード単位
	//----------------------------------------------
	function insert_class_material($param) {
		//資料IDを元に、資料情報を取得
		$material_data = $this->_get_material($param['material_id']);
		
		//パラメータ設定
		$param = array_merge(array(
		//	'class_material_id'
			'class_id'				=> 0,
			'material_name'			=> $material_data[0]['material_name'],
			'material_logic_name'	=> $material_data[0]['material_logic_name'],
			'teacher_id'			=> 0,
		//	'student_id'
			'page_num'				=> $material_data[0]['page_num'],
			'submit_flag'			=> 0,
			'status'				=> 1,
			'added_at'				=> date("Y/m/d H:i:s"),
			'update_at'				=> date("Y/m/d H:i:s"),
		), $param);

		// 授業資料テーブルへ新規登録
		$res = $this->db->query($this->db->insert_string('class_material', array(
		//	'class_material_id'
			'class_id'				=> $param['class_id'],
			'material_name'			=> $param['material_name'],
			'material_logic_name'	=> $param['material_logic_name'],
			'teacher_id'			=> $param['teacher_id'],
		//	'student_id'
			'page_num'				=> $param['page_num'],
			'submit_flag'			=> $param['submit_flag'],
			'status'				=> $param['status'],
			'added_at'				=> $param['added_at'],
			'update_at'				=> $param['update_at'],
			)
		));

		// get class_material_id
		$class_material_id = $this->db->insert_id();

		// get directory
		$material_dir       = $this->config->item('material_dir').'/';
		$class_material_dir = $this->config->item('class_material_dir').'/';

		# /{class_id}/ ディレクトリの存在確認
		$search_dir = $class_material_dir.$param['class_id'].'/';
		if(!file_exists($search_dir)){
			mkdir($search_dir);
			chmod($search_dir, 0777);
		}

		# /{class_id}/teacher_{teacher_id}/ ディレクトリの存在確認
		$search_dir = $class_material_dir.$param['class_id'].'/teacher_'.$param['teacher_id'].'/';
		if(!file_exists($search_dir)){
			mkdir($search_dir);
			chmod($search_dir, 0777);
		}
		
		# materialディレクトリのコピー（ディレクトリ名変更）
		exec('cp -ar '.$material_dir.$param['material_id'].'/ '.$search_dir.$class_material_id.'/');

		// 正常登録終了の通知
		$this->load->model('model_notification');
		$notice_result = $this->model_notification->insert_notification(array(
			'notice_kind'  => 'cms-class-material',
			'id'           => $class_material_id,
			'notice_judge' => 'OK',
		));

		return $class_material_id;  #"OK";
	}

	//----------------------------------------------
	//資料マスタからの取得（material）
	//----------------------------------------------
	function _get_material($material_id) {
		
		//SQL生成
		$sql = '';
		$sql .= "SELECT material.material_id         AS material_id ";
		$sql .= "      ,material.material_name       AS material_name ";
		$sql .= "      ,material.material_logic_name AS material_logic_name ";
		$sql .= "      ,material.page_num            AS page_num ";
		$sql .= "  FROM material ";
		$sql .= " WHERE material.material_id = ? ";
		
		//Query実行
		$query = $this->db->query($sql, array(
				$material_id,
			)); 

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//授業マスタからの取得（class）
	// [2012/08/20]取得項目に講座IDを追加
	//----------------------------------------------
	function get_class_list($class_id) {
		
		//SQL生成
		$sql = '';
		$sql .= "SELECT class.class_id   AS class_id ";
		$sql .= "      ,class.class_name AS class_name ";
		$sql .= "      ,class.teacher_id AS teacher_id ";
		$sql .= "      ,class.cource_id  AS cource_id ";
		$sql .= "  FROM class ";
		$sql .= " WHERE class.class_id = ? ";
		
		//Query実行
		$query = $this->db->query($sql, array(
				$class_id,
			)); 

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//授業資料マスタからの取得（class_material）
	//----------------------------------------------
	function get_class_material($param) {

		//パラメータ設定
		$param = array_merge(array(
			'login_teacher_id'		=> 0,
			'class_material_id'		=> 0,
			'auth_class_material'	=> 0,
		), $param);
		
		//SQL生成
		$sql = '';
		$sql .= "SELECT class_material.class_material_id   AS class_material_id ";
		$sql .= "      ,class_material.material_name       AS material_name ";
		$sql .= "      ,class_material.material_logic_name AS material_logic_name ";
		$sql .= "      ,if(class_material.teacher_id is null,-1,class_material.teacher_id) AS teacher_id ";
		$sql .= "      ,if(class_material.student_id is null,-1,class_material.student_id) AS student_id ";
		$sql .= "      ,class_material.class_id            AS class_id ";
		$sql .= "  FROM class_material INNER JOIN class ON class_material.class_id = class.class_id ";
	//	$sql .= " WHERE class_material.status            = 1 ";
		$sql .= " WHERE class_material.status            <> 9 ";
	//	$sql .= "   AND class_material.submit_flag       = 1 ";
	//	$sql .= "   AND (class_material.student_id > 0 AND class_material.submit_flag = 1 )";
		$sql .= "   AND ( (class_material.submit_flag = 1) OR (class_material.teacher_id > 0 AND class_material.submit_flag = 0 ) ) ";
		$sql .= "   AND class_material.class_material_id = ? ";
	//	if($param['auth_class_material'] == 2){
	//		$sql .= "   AND class.teacher_id          = ".$param['login_teacher_id']." ";
	//	}
		
		//Query実行
		$query = $this->db->query($sql, array(
				$param['class_material_id']
			)); 

		//return
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//授業資料マスタから資料名を取得（class_material）
	//  授業詳細にて、登録資料名を表示するため
	// [2012/08/08]授業資料、講師ノート、受講者ノート、受講者提出ノートを取得するようSQL文修正
	//----------------------------------------------
	function get_class_material_name($class_id) {

	//	//SQL生成
	//	$sql = '';
	//	$sql .= "SELECT if(class_material.material_logic_name is null,class_material.material_name,class_material.material_logic_name) AS material_logic_name ";
	//	$sql .= "      ,if(class_material.teacher_id is null,-1,class_material.teacher_id) AS teacher_id ";
	//	$sql .= "      ,if(class_material.student_id is null,-1,class_material.student_id) AS student_id ";
	//	$sql .= "      ,class_material.submit_flag AS submit_flag ";
	//	$sql .= "  FROM class_material ";
	//	//$sql .= " WHERE class_material.status      = 1 ";
	//	$sql .= " WHERE class_material.status     <> 9 ";
	//	//$sql .= "   AND class_material.submit_flag = 1 ";
	//	//$sql .= "   AND (class_material.student_id > 0 AND class_material.submit_flag = 1 )";
	//	$sql .= "   AND ( (class_material.submit_flag = 1) OR (class_material.teacher_id > 0 AND class_material.submit_flag = 0 ) ) ";
	//	$sql .= "   AND class_material.class_id    = ? ";
	//	$sql .= " ORDER BY class_material.update_at           DESC ";
	//	$sql .= "         ,class_material.material_logic_name ASC ";
		$sql = '';
		$sql .= "SELECT  '-' AS kinds ";
		$sql .= "      ,class_material.class_material_id AS class_material_id ";
		$sql .= "      ,class_material.class_id AS class_id ";
		$sql .= "      ,if(class_material.material_logic_name is null,class_material.material_name,class_material.material_logic_name) AS material_logic_name ";
		$sql .= "      ,if(class_material.teacher_id is null,-1,class_material.teacher_id) AS teacher_id ";
		$sql .= "      ,if(class_material.student_id is null,-1,class_material.student_id) AS student_id ";
		$sql .= "      ,class_material.submit_flag AS submit_flag ";
		$sql .= "      ,class_material.status AS status ";
		$sql .= "      ,class_material.update_at AS update_at ";
		$sql .= "  FROM class_material ";
		$sql .= " WHERE class_material.status = 1 ";
		$sql .= "   AND class_material.class_id    = ? ";
		$sql .= " ORDER BY class_material.update_at           DESC ";
		$sql .= "         ,class_material.material_logic_name ASC ";
		
		//Query実行
		$query = $this->db->query($sql, array(
				$class_id,
			)); 

		//return
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	//未変換授業資料一覧取得（バッチ用）
	//----------------------------------------------
	function get_unchanged_class_material() {
		$ret = array();
		$query = $this->db->query(
			' SELECT * FROM class_material'.
			' WHERE status = 2',
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
	function update_class_material_convert_status($param){
		//引数設定
		$param = array_merge(
						array(
							'class_material_id'	=> 0,
							'page_num'			=> 1,
							'status'			=> 0,
						),
						$param
					);
		$sql = "UPDATE
					class_material 
				SET
					status = ?,
					page_num = ?
				WHERE 
					class_material_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['status'], $param['page_num'], $param['class_material_id']));
		$this->db->trans_complete();
		
	}




	//----------------------------------------------
	// 資料マスタ新規登録＋ファイル新規登録（add_material）
	//----------------------------------------------
	function insert_material_quick($param){
		
		$param = array_merge(array(
			//	'material_id'
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
			//	'material_id'
				'material_name'			=> $param['material_name'],
				'material_logic_name'	=> $param['material_logic_name'],
				'material_caption'		=> $param['material_caption'],
				'school_id'				=> $param['school_id'],
				'teacher_id'			=> $param['teacher_id'],
				'page_num'				=> $param['page_num'],
				'status'				=> $param['status'],
				'added_at'				=> $param['added_at'],
				'update_at'				=> $param['update_at'],
			)
		));

		// get material_id
		$last_material_id = $this->db->insert_id();

		// get directory
		$material_dir = $this->config->item('material_dir').'/';
		
		$up_path = $material_dir.$last_material_id;
		if(!is_dir($up_path)){
			mkdir($up_path, 0777, TRUE);
			chmod($up_path, 0777);
		}


		$config['upload_path']   = $up_path;
		$config['allowed_types'] = '*';
		$config['overwrite']     = TRUE;
		$config['remove_spaces'] = TRUE; // ?
		$config['file_name']     = 'master';
		$this->load->library('upload');
		$this->upload->initialize($config);
		
//		$this->load->library('upload', array(
//			'upload_path'   => $up_path,
//			'allowed_types' => '*',
//			'overwrite'     => TRUE,
//			'remove_spaces' => TRUE,
//			'file_name'     => 'master',
//		));
	//	$upload_name = $param['upload_name'];
	//	if(!$this->upload->do_upload($upload_name)){
		if(!$this->upload->do_upload($param['local_file'])){
			return -1;
		} else {
			$upload_data = $this->upload->data();
			chmod($up_path.'/'.$upload_data['file_name'],0777);

			$res = $this->db->query($this->db->update_string('material', array(
					'material_name'	=> $upload_data['file_name'],
				),'material_id='.$last_material_id
			));

		//	return array(
		//		'material_id'	=> $last_material_id,
		//	);
		return $last_material_id;
		}
	}



	//----------------------------------------------
	// 授業資料マスタ新規登録＋ファイル新規登録
	//----------------------------------------------
	function insert_class_material_quick($param){

		//資料IDを元に、資料情報を取得
		$material_data = $this->_get_material($param['material_id']);
		
		$param = array_merge(array(
			//	'class_material_id'
				'class_id'				=> 0,
				'material_name'			=> $material_data[0]['material_name'],
				'material_logic_name'	=> $material_data[0]['material_logic_name'],
				'teacher_id'			=> 0,
			//	'student_id'
				'page_num'				=> 0,
				'submit_flag'			=> 0,
				'status'				=> 2,
				'added_at'				=> date("Y/m/d H:i:s"),
				'update_at'				=> date("Y/m/d H:i:s"),
		), $param);

		$res = $this->db->query($this->db->insert_string('class_material', array(
			//	'class_material_id'
				'class_id'				=> $param['class_id'],
				'material_name'			=> $param['material_name'],
				'material_logic_name'	=> $param['material_logic_name'],
				'teacher_id'			=> $param['teacher_id'],
			//	'student_id'
				'page_num'				=> $param['page_num'],
				'submit_flag'			=> $param['submit_flag'],
				'status'				=> $param['status'],
				'added_at'				=> $param['added_at'],
				'update_at'				=> $param['update_at'],
			)
		));
		
		// get class_material_id
		$last_class_material_id = $this->db->insert_id();

		// get directory
		$material_dir       = $this->config->item('material_dir').'/';
		$class_material_dir = $this->config->item('class_material_dir').'/';

		//{class_id}/ ディレクトリの存在確認
		$search_dir = $class_material_dir.$param['class_id'].'/';
		if(!is_dir($search_dir)){
			mkdir($search_dir);
			chmod($search_dir, 0777);
		}

		//{class_id}/teacher_{teacher_id}/ ディレクトリの存在確認
		$search_dir .= 'teacher_'.$param['teacher_id'].'/';
		if(!is_dir($search_dir)){
			mkdir($search_dir);
			chmod($search_dir, 0777);
		}
		
		//{class_id}/teacher_{teacher_id}/{class_material_id}/ ディレクトリの存在確認
		$search_dir .= $last_class_material_id.'/';
		if(!is_dir($search_dir)){
			mkdir($search_dir);
			chmod($search_dir, 0777);
		}
		
		// 資料マスタ配下のファイル存在確認
		$command = 'find '.$material_dir.$param['material_id'].'/'.$material_data[0]['material_name'].' | wc -l';
		$result  = array();
		exec($command, $result);
		if($result[0]==0){
			// 授業資料マスタのstatusを削除（9）に変更（正常なら1）
			$res = $this->db->query($this->db->update_string('class_material', array(
					'status'	=> 9,
					'update_at'	=> date('Y/m/d H:i:s'),
				),'class_material_id='.$last_class_material_id
			));
			return -1;
		}
		
		// 資料マスタ配下のファイルを、授業資料マスタ配下にコピーする
		$command = 'cp -a '.$material_dir.$param['material_id'].'/'.$material_data[0]['material_name'].' '.$search_dir;
		exec($command);

		// 授業資料マスタ配下のファイル存在確認
		$command = 'find '.$search_dir.$material_data[0]['material_name'].' | wc -l';
		$result  = array();
		exec($command, $result);
		if($result[0]==0){
			// 授業資料マスタのstatusを削除（9）に変更（正常なら1）
			$res = $this->db->query($this->db->update_string('class_material', array(
					'status'	=> 9,
					'update_at'	=> date('Y/m/d H:i:s'),
				),'class_material_id='.$last_class_material_id
			));
			return -3;
		}
		
		return $last_class_material_id;
	}


	//----------------------------------------------
	// [2012/08/20]資料講座マスタの登録処理
	//----------------------------------------------
	function update_material_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'material_id' => 0,
							'cource_id'   => 0,
						),
						$param
					);
		
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
		
		// 資料講座マスタへの新規登録
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
								$param['cource_id'],
								$wDate
							));
		
		$this->db->trans_complete();
	}


}
?>
