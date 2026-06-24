<?php
#[AllowDynamicProperties]
class Model_book_library extends CI_Model  
{
	private $book_library_dir  = '';			//資料アップロードディレクトリ
//	private $admin_name        = "管理者";		//管理者名（common_lang.phpから取得に変更）

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
	// [2012/10/12]検索条件、講座・タグの追加
	//----------------------------------------------
	function get_book_library_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
				'offset'		=> 0,
				'rowcount'		=> 10,
				's_cource'		=> 0,
				's_tag'			=> '',
				's_free_word'	=> '',
			),
			$param
		);

		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS book_library . * , if( teacher.teacher_name IS NULL , "'. $this->lang->line_or_def('common_super_user','Super User').'", teacher.teacher_name ) AS teacher_name '.
			'   FROM book_library LEFT JOIN teacher ON teacher.teacher_id = book_library.teacher_id '.
			'  WHERE book_library.school_id = ?'.
			'    AND book_library.status <> 9'.
			($param['s_free_word'] ?
				' AND ('.
				' 	book_library.book_library_logic_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR book_library.book_library_caption LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR book_library.book_library_tags    LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND book_library_id IN (SELECT book_library_id FROM book_library_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] === 'タグなし') ?
				' AND ( book_library.book_library_tags IS NULL OR book_library.book_library_tags = "" ) '
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] !== 'タグなし') ?
				' AND book_library.book_library_tags LIKE "%'.$this->db->escape_like_str($param['s_tag']).'%" '
				: ''
			).
			' ORDER BY book_library.update_at DESC'.
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
	
	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_data($param){
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(array(
			'school_id'			=> $this->session->userdata['cms_master.login.school_id'],
			'book_library_id'	=> 0,
		), $param);
		//SQL投入
		$query = $this->db->query(
			' SELECT book_library.book_library_id , book_library.book_library_name , book_library.book_library_logic_name '.
			'      , book_library.book_library_caption ,book_library.book_library_tags , book_library.school_id '.
			'      , book_library.teacher_id , book_library.page_num , book_library.original_file_size , book_library.local_reading_flag '.
			'      , DATE_FORMAT(book_library.local_reading_open ,  "%Y/%m/%d %H:%i:%s") AS local_reading_open '.
			'      , if(book_library.local_reading_close IS NULL , "", DATE_FORMAT(book_library.local_reading_close , "%Y/%m/%d %H:%i:%s") ) AS local_reading_close '.
			'      , if(book_library.local_reading_close IS NULL , "1", "0" ) AS local_reading_close_flag '.
			'      , book_library.stream_flag , book_library.status , book_library.added_at , book_library.update_at '.
			'      , if( teacher.teacher_name IS NULL , "'.$this->lang->line_or_def('common_super_user','Super User').'", teacher.teacher_name ) AS teacher_name '.
			'   FROM book_library LEFT JOIN teacher ON teacher.teacher_id = book_library.teacher_id '.
			'  WHERE book_library.school_id = ?'.
			'    AND book_library.book_library_id = ?'.
			'  LIMIT 0, 1',
			array(
				$param['school_id'],
				$param['book_library_id'],
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
	//更新処理
	// [2012/08/20]更新処理後の戻り値をtrueから図書IDに変更
	//----------------------------------------------
	function update_book_library($param){

		if ($param['update_flg'] == 0){
			//新規
			$param = array_merge(array(
					'book_library_name'			=> 'no value',
					'book_library_logic_name'	=> 'no value',
					'book_library_caption'		=> 'no value',
					'book_library_tags'			=> 'no value',
					'school_id'					=> 0,
					'teacher_id'				=> 0,
					'page_num'					=> 0,
					'original_file_size'		=> 0,
					'local_reading_flag'		=> 0,
					'local_reading_open'		=> '',
					'local_reading_close'		=> NULL,
					'status'					=> 0,
					'added_at'					=> date("Y/m/d H:i:s"),
					'update_at'					=> date("Y/m/d H:i:s"),
			), $param);

			$res = $this->db->query($this->db->insert_string('book_library', array(
					'book_library_name'			=> $param['book_library_name'],
					'book_library_logic_name'	=> $param['book_library_logic_name'],
					'book_library_caption'		=> $param['book_library_caption'],
					'book_library_tags'			=> $param['book_library_tags'],
					'school_id'					=> $param['school_id'],
					'teacher_id'				=> $param['teacher_id'],
					'page_num'					=> $param['page_num'],
					'original_file_size'		=> $param['original_file_size'],
					'local_reading_flag'		=> $param['local_reading_flag'],
					'local_reading_open'		=> $param['local_reading_open'],
					'local_reading_close'		=> $param['local_reading_close'],
					'status'					=> $param['status'],
					'added_at'					=> $param['added_at'],
					'update_at'					=> $param['update_at'],
				)
			));

			$lastInsertId = $this->db->insert_id();

			$this->book_library_dir = $this->config->item('book_library_dir');
			$up_path = $this->book_library_dir.'/'.$lastInsertId;
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

				$res = $this->db->query($this->db->update_string('book_library', array(
						'book_library_name'	=> $upload_data['file_name'],
					),'book_library_id='.$lastInsertId
				));

				return array(
					'lastInsertId'	=> $lastInsertId,
				);
			}
		}else{
			//修正
			$wDate = date('Y/m/d H:i:s');
			$sql = "UPDATE
						book_library
					SET 
						book_library_logic_name = ?,
						book_library_caption    = ?,
						book_library_tags       = ?,
						local_reading_flag      = ?,
						local_reading_open      = ?,
						local_reading_close     = ?,
						update_at               = ?
					WHERE
						book_library_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$param['book_library_logic_name'],
									$param['book_library_caption'],
									$param['book_library_tags'],
									$param['local_reading_flag'],
									$param['local_reading_open'],
									$param['local_reading_close'],
									$wDate,
									$param['book_library_id']
								));
			$this->db->trans_complete();
			
		//	return true;
			return array(
				'lastInsertId'	=> $param['book_library_id'],
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
				'book_library_id' => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('book_library', array(
				'status'	=> 9,
				'update_at'	=> date('Y/m/d H:i:s'),
			),'book_library_id='.$param['book_library_id']
		));

		# ファイルの物理削除（論理削除成功が条件）
		if($res==1){
			# Learning側ファイル削除
			$this->load->helper('file');
			$command_text = 'rm -rf '.$this->config->item('book_library_dir').'/'.$param['book_library_id'];
			exec($command_text);
			
			# ALFStream側のファイル削除
			
			# get config
			$stream_ftp       = $this->config->item('stream_ftp');
			$stream_ftp_dir   = $this->config->item('stream_ftp_dir');
			$stream_ftp_user  = $this->config->item('stream_ftp_user');
			$stream_ftp_pass  = $this->config->item('stream_ftp_pass');

			# load FTP
			$this->load->library('ftp');

			$config['hostname'] = $stream_ftp;
			$config['username'] = $stream_ftp_user;
			$config['password'] = $stream_ftp_pass;
			$config['port']     = 21;
			$config['passive']  = FALSE;
			$config['debug']    = TRUE;
			
			# connect FTP
			$this->ftp->connect($config);
			
			$stream_flag = 9;
			#FTP側ディレクトリ確認（/school_{num}）
			$ftp_dir        = $this->ftp->list_files($stream_ftp_dir.'/');
			$hosts_dir_path = $stream_ftp_dir.'/school_'.$this->session->userdata['cms_master.login.school_id'];
			if(!in_array($hosts_dir_path, $ftp_dir)){
				$stream_flag = 13;
			}
			
			#FTP側ディレクトリ確認（/school_{num}/book_library_{num}）
			if($stream_flag < 13){
				$ftp_dir         = $this->ftp->list_files($hosts_dir_path.'/');
				$hosts_dir_path .= '/book_library_'.$param['book_library_id'];
				if(!in_array($hosts_dir_path, $ftp_dir)){
					$stream_flag = 13;
				}
			}
			
			#FTP側ディレクトリ削除
			if($stream_flag < 13){
				# delete_dirによるディレクトリ削除
				$this->ftp->delete_dir($hosts_dir_path.'/');
			}

			# unconnect FTP
			$this->ftp->close(); 
			
			# Streamフラグの更新（9:削除成功 or 11:削除失敗）
			$data_param = array(
							'book_library_id' => $param['book_library_id'],
							'stream_flag'     => $stream_flag,
						);
			//データ更新
			$retdata = $this->model_book_library->update_book_library_transmission_status($data_param);
		}
		return $res;
	}
	
	//----------------------------------------------
	//未変換図書一覧取得（バッチ用）
	//----------------------------------------------
	function get_unchanged_material() {
		$ret = array();
		$query = $this->db->query(
			' SELECT * FROM book_library'.
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
	function update_book_library_convert_status($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id'	=> 0,
							'page_num'			=> 1,
							'status'			=> 0,
						),
						$param
					);
		$sql = "UPDATE
					book_library 
				SET
					status = ?,
					page_num = ?
				WHERE 
					book_library_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['status'], $param['page_num'], $param['book_library_id']));
		$this->db->trans_complete();
	}

	//----------------------------------------------
	//stream未送信図書一覧取得（バッチ用）
	//----------------------------------------------
	function get_untransmission_material() {
		$ret = array();
		$query = $this->db->query(
			' SELECT * FROM book_library'.
			' WHERE status = 1'.
			'   AND stream_flag = 0 ',
			array(
			)
		);
		foreach($query->result_array() as $row){
			array_push($ret, $row);
		}

		return $ret;
	}

	//----------------------------------------------
	//stream送信済ステータス更新処理（バッチ用）
	//----------------------------------------------
	function update_book_library_transmission_status($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id'	=> 0,
							'stream_flag'		=> 0,
						),
						$param
					);
		$sql = "UPDATE 
					book_library 
				SET 
					stream_flag = ? 
				WHERE 
					book_library_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['stream_flag'], $param['book_library_id']));
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// [2012/11/16]図書室pdf再変換一覧取得（バッチ用）
	//----------------------------------------------
	function get_reconversion_book_library() {
		$ret = array();
		$query = $this->db->query(
			' SELECT book_library.book_library_id '.
			'       ,book_library.book_library_name '.
			'       ,book_library.book_library_logic_name '.
			'       ,book_library.school_id '.
			'       ,book_library.teacher_id '.
			'       ,book_library.stream_flag '.
			'       ,book_library.status  '.
			'       ,book_library.update_at '.
			'   FROM book_library  '.
			'  WHERE book_library.stream_flag = 1 '.
			'    AND book_library.status      = 1 '.
			'    AND book_library.school_id IN (SELECT school_id FROM school WHERE status = 0) '.
			"    AND book_library_name NOT LIKE 'master.pdf'  ".
			"    AND book_library_name NOT LIKE 'master.jpg'  ".
			"    AND book_library_name NOT LIKE 'master.jpeg' ".
			"    AND book_library_name NOT LIKE 'master.png'  ".
			"    AND book_library_name NOT LIKE 'master.gif'  ",
			array(
			)
		);
		foreach($query->result_array() as $row){
			array_push($ret, $row);
		}
		
		return $ret;
	}
	
	//----------------------------------------------
	//本ダウンロード権利確認＋物理ファイル名取得
	//---------------------------------------------- 
	function get_book_library_filename($param){
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
							'book_library_id'  => 0,
						),
						$param
					);
			
			//SQL文作成
			$sql = '';
			if($param['login_teacher_id'] > 0){
				$sql .= "SELECT book_library.book_library_name AS book_library_name ";
				$sql .= "      ,if(book_library.book_library_logic_name IS NULL , '', book_library.book_library_logic_name ) AS book_library_logic_name ";
				$sql .= "      ,teacher.teacher_auth AS teacher_auth ";
				$sql .= "  FROM book_library INNER JOIN teacher ON book_library.school_id = teacher.school_id ";
				$sql .= " WHERE book_library.status          <> 9 ";
				$sql .= "   AND teacher.status               =  0 ";
				$sql .= "   AND book_library.book_library_id =  ? ";
				$sql .= "   AND teacher.teacher_id           =  ? ";
			}else{
				$sql = '';
				$sql .= "SELECT book_library.book_library_name AS book_library_name ";
				$sql .= "      ,if(book_library.book_library_logic_name IS NULL , '', book_library.book_library_logic_name ) AS book_library_logic_name ";
				$sql .= "      ,'" . $admin_teacher_auth. "' AS teacher_auth ";
				$sql .= "  FROM book_library ";
				$sql .= " WHERE book_library.status          <> 9 ";
				$sql .= "   AND book_library.book_library_id =  ? ";
				$sql .= "   AND book_library.book_library_id <> ? ";
			}
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['book_library_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				$row_array = $query->row_array();

				$work_auth = unserialize($row_array['teacher_auth']);
				if($work_auth['book_library'] == 1){
					// レコードあり＋権限あり：権限有効
					$return_data['book_library_name']       = $row_array['book_library_name'];
					$return_data['book_library_logic_name'] = $row_array['book_library_logic_name'];
					$return_data['remarks']                 = '';
					return $return_data;
				}else{
					// レコードあり＋権限なし：権限無効
					$return_data['book_library_name']       = '';
					$return_data['book_library_logic_name'] = '';
					$return_data['remarks']                 = 'no-auth';
					return $return_data;
				}
			}else{
				// レコードなし：権限無効
				$return_data['book_library_name']       = '';
				$return_data['book_library_logic_name'] = '';
				$return_data['remarks']                 = 'no-data';
				return $return_data;
			}
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['book_library_name']       = '';
			$return_data['book_library_logic_name'] = '';
			$return_data['remarks']                 = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
	
	//----------------------------------------------
	//本削除、権限確認
	//----------------------------------------------
	function book_library_delete_auth_check($param){
		try{ 
			//引数設定
			$param = array_merge(
				array(
					'login_teacher_id' => $this->session->userdata['cms_master.login.teacher_id'],
					'book_library_id'  => 0,
				),
				$param
			);
			
			// 管理者権限(-1)：フルアクセスのため、権限有効
			if($param['login_teacher_id'] <= 0){
				$return_data['auth_delete'] = 1;
				$return_data['remarks']     = '';
				return $return_data;
			}
			
			//SQL文作成
			$sql = '';
			$sql .= "SELECT teacher.teacher_auth AS teacher_auth ";
			$sql .= "  FROM book_library INNER JOIN teacher ON book_library.school_id = teacher.school_id ";
			$sql .= " WHERE book_library.status          <> 9 ";
			$sql .= "   AND teacher.status               =  0 ";
			$sql .= "   AND book_library.book_library_id =  ? ";
			$sql .= "   AND teacher.teacher_id           =  ? ";
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['book_library_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				// レコードあり
				$row_array = $query->row_array();
				
				// 権限（新）より権限有無を確認
				$work_auth = unserialize($row_array['teacher_auth']);
				
				if($work_auth['book_library'] == 1){
					// 図書権限あり：権限有効
					$return_data['auth_delete'] = 1;
				}else{
					// 図書権限なし：権限無効
					$return_data['auth_delete'] = 0;
				}
				
				$return_data['remarks'] = '';
				return $return_data;
				// レコードあり：取得したteacher_auth に図書権限ありならば、権限有効
				// レコードあり：取得したteacher_auth に図書権限なしならば、権限無効
			}else{
				// レコードなし：権限無効
				$return_data['auth_delete']   = 0;
				$return_data['remarks']       = 'no-data';
				return $return_data;
			}
		
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['auth_delete'] = 0;
			$return_data['remarks']     = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
	
	//----------------------------------------------
	// [2012/08/20]図書室講座マスタから講座ID取得
	//----------------------------------------------
	function get_book_library_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										book_library_lecture
									WHERE
										book_library_id = {$this->db->escape($param['book_library_id'])}
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
	// [2012/08/20]図書室講座マスタの登録更新処理
	//----------------------------------------------
	function update_book_library_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM book_library_lecture
				WHERE
					book_library_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['book_library_id'],
							));
		
		//選択受講講座を登録
		foreach($data['book_library_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						book_library_lecture
					(
						book_library_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['book_library_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// [2012/10/03]図書室検索インデックスの登録更新処理
	//----------------------------------------------
	function update_book_library_search_index($param){
		//引数設定
		$param = array_merge(
						array(
							'book_library_id' => 0,
							'data'            => array(),
						),
						$param
					);
		$data = $param['data'];

		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM book_library_search_index 
				WHERE
					book_library_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['book_library_id'],
							));

		// 所属講座のループ
		foreach($data['book_library_lectures'] as $cource_id) {
			// タグのループ
			foreach(explode(",", $data['book_library_tags']) as $_tag){
				if(strlen($_tag) == 0){
					$sql = "INSERT INTO
								book_library_search_index
							(
								book_library_id,
								cource_id,
								book_library_tag
							)
							VALUES(?,?,NULL)
							";
					$this->db->query($sql, 
										array(
											$param['book_library_id'],
											$cource_id
										));
				}else{
					$sql = "INSERT INTO
								book_library_search_index
							(
								book_library_id,
								cource_id,
								book_library_tag
							)
							VALUES(?,?,?)
							";
					$this->db->query($sql, 
										array(
											$param['book_library_id'],
											$cource_id,
											$_tag
										));
				}
			}
		}
		$this->db->trans_complete();
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
		
		$query = $this->db->query(
			' SELECT book_library_tags'.
			'   FROM book_library'.
			'  WHERE school_id   = ? '.
			'    AND status     <> 9',
			array(
				$param['school_id'],
			)
		);

		$tagKeys = array();
		foreach($query->result_array() as $row){
			if($row['book_library_tags']){
				foreach(explode(",", $row['book_library_tags']) as $_tag){
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

	//----------------------------------------------
	// [2012/11/20]図書室チェックボックス用一覧取得
	//----------------------------------------------
	function get_book_library_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//SQL生成
		$sql  = '';
		$sql .= "SELECT book_library_id ,book_library_logic_name ";
		$sql .= "  FROM book_library ";
		$sql .= " WHERE status <> 9 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= " ORDER BY book_library_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属の図書室を取得
	//----------------------------------------------
	function get_cource_book_library($param) {
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
		$sql .= "SELECT book_library_id ,book_library_logic_name ";
		$sql .= "      ,IF( INSTR( book_library_name, '.' ) >0, SUBSTRING( book_library_name, INSTR( book_library_name, '.' ) +1 ) , 'unknown' ) AS ext ";
		$sql .= "  FROM book_library ";
		$sql .= " WHERE 1 = 1 ";
	//	$sql .= "   AND status <> 9 ";
		$sql .= "   AND status      = 1 ";
		$sql .= "   AND stream_flag = 1 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		
		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        book_library_logic_name LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR book_library_caption    LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR book_library_tags       LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		$sql .= " ORDER BY book_library_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// [2012/12/07] [Ajax用]講座所属の図書室を取得
	// school_id + cource_id       => 講座所属の図書情報
	// school_id + book_library_id => 該当図書の頁数
	//----------------------------------------------
	function get_cource_book_library_exclusive($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
				'cource_id'		=> '',
				'book_library_id'	=> 0,
			),
			$param
		);
		
		//SQL生成
		$sql  = "";
		$sql .= "SELECT book_library_id ,book_library_logic_name ,page_num ";
		$sql .= "      ,IF( INSTR( book_library_name, '.' ) >0, SUBSTRING( book_library_name, INSTR( book_library_name, '.' ) +1 ) , 'unknown' ) AS ext ";
		$sql .= "  FROM book_library ";
		$sql .= " WHERE 1 = 1 ";
	//	$sql .= "   AND status <> 9 ";
		$sql .= "   AND status      = 1 ";
		$sql .= "   AND stream_flag = 1 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";

		if($param['cource_id'] != ''){
		//	$sql .= "   AND book_library_id IN (SELECT book_library_id FROM book_library_lecture WHERE cource_id IN ({$this->db->escape_str($param['cource_id'])}) )";
		//	[2013/01/07]修正
			$sql .= "   AND book_library_id IN (SELECT book_library_id FROM book_library_lecture WHERE cource_id IN ({$this->db->escape_str($param['cource_id'])}) )";
		}else{
			$sql .= "   AND book_library_id = {$this->db->escape($param['book_library_id'])} ";
		}

//		if (isset($param['cource_id']) && $param['cource_id'] != '' && $param['book_library_id'] == 0 )   {
//			$sql .= "   AND book_library_id IN (SELECT book_library_id FROM book_library_lecture WHERE cource_id IN ({$this->db->escape_str($param['cource_id'])}) )";
//		}
//
//		if (isset($param['cource_id']) && $param['cource_id'] == '' && $param['book_library_id'] > 0 )   {
//			$sql .= "   AND book_library_id = {$this->db->escape($param['book_library_id'])} ";
//		}
//print $sql;
		
		$sql .= " ORDER BY book_library_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

//	//----------------------------------------------
//	//資料新規作成、権限確認
//	//----------------------------------------------
//	function material_newdata_auth_check($param){
//		try{ 
//			//引数設定
//			$param = array_merge(
//						array(
//							'login_teacher_id' => -1,
//							'class_id'         => 0,
//						),
//						$param
//					);
//			
//			// 管理者権限(-1)：フルアクセスのため、権限有効
//			if($param['login_teacher_id'] <= 0){
//				$return_data['auth_newdata']   = 1;
//				$return_data['remarks']        = '';
//				return $return_data;
//			}
//			
//			//SQL文作成
//			$sql = '';
//			$sql .= "SELECT class.class_name, teacher.teacher_name, teacher.teacher_auth ";
//			$sql .= "  FROM class INNER JOIN teacher ON teacher.teacher_id = class.teacher_id ";
//			$sql .= " WHERE class.status         =  0 ";
//			$sql .= "   AND teacher.status       =  0 ";
//			$sql .= "   AND class.class_id       =  ? ";
//			$sql .= "   AND teacher.teacher_id   =  ? ";
//			
//			//Query実行
//			$query = $this->db->query($sql, array(
//					$param['class_id'],
//					$param['login_teacher_id']
//				)); 
//			
//			//Data Return
//			if ($query->num_rows() > 0){
//				$row_array = $query->row_array();
//
//				$work_auth = unserialize($row_array['teacher_auth']);
//				if($work_auth['material'] == 0){
//					$return_data['auth_newdata']   = 0;
//					$return_data['remarks']        = '';
//					return $return_data;
//				}else{
//					// レコードあり：ログイン講師が担当する授業、権限有効
//					$return_data['auth_newdata']   = 1;
//					$return_data['remarks']        = '';
//					return $return_data;
//				}
//			}else{
//				// レコードなし：ログイン講師が担当しない授業、権限無効
//				$return_data['auth_newdata']   = 0;
//				$return_data['remarks']        = '';
//				return $return_data;
//			}
//		}catch(Exception $e){ 
//			// 例外発生：権限無効
//			$return_data['auth_newdata']   = 0;
//			$return_data['remarks']       = $e;
//			return $return_data;
//		//	throw new Exception();
//		}
//	}
	
}
?>
