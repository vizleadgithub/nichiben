<?php
class Model_video extends CI_Model  
{
	private $video_dir      = '';			//資料アップロードディレクトリ
	private $alfstreamParam	= array(
		'auth_key'	=> '',
		'code'		=> '',
	);

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		$this->load->model('ModelSchoolContract');

		$this->alfstreamParam = $this->ModelSchoolContract->getAlfstreamParam();
	}
	//----------------------------------------------
	//資料一覧取得
	// [2012/10/12]検索条件、講座・タグの追加
	// [2012/11/15]状態をvideo_alfstream_status テーブルから取得するよう修正
	//----------------------------------------------
	function get_material_list($param) {
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
			' SELECT SQL_CALC_FOUND_ROWS video.* '.
			"     , (CASE WHEN video_alfstream_status.alfstream_status IS NULL THEN 'UPLOADING:0%' ELSE video_alfstream_status.alfstream_status END) ".
			'          AS alfstream_status '.
			'   FROM video LEFT JOIN video_alfstream_status ON video.video_id = video_alfstream_status.video_id '.
			'  WHERE video.school_id = ?'.
			'    AND video.status = 0'.
			($param['s_free_word'] ?
				' AND ('.
				' 	video.video_logic_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR video.video_caption LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR video.video_tags    LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND video.video_id IN (SELECT video_id FROM video_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] === 'タグなし') ?
				' AND ( video.video_tags IS NULL OR video.video_tags = "" ) '
				: ''
			).
			( ($param['s_tag']) && ($param['s_tag'] !== 'タグなし') ?
				' AND video.video_tags LIKE "%'.$this->db->escape_like_str($param['s_tag']).'%" '
				: ''
			).
			' ORDER BY video.update_at DESC'.
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
	function get_material($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id'   => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										video.video_id , video.video_name , video.video_logic_name , video.video_caption , video.video_tags 
									  , video.school_id , video.teacher_id , video.page_num , video.original_file_size , video.local_reading_flag
									  , DATE_FORMAT(video.local_reading_open ,  '%Y/%m/%d %H:%i:%s') AS local_reading_open
									  , if(video.local_reading_close IS NULL , '', DATE_FORMAT(video.local_reading_close , '%Y/%m/%d %H:%i:%s') ) AS local_reading_close
									  , if(video.local_reading_close IS NULL , '1', '0' ) AS local_reading_close_flag
									  , video.status , video.added_at , video.update_at , video.idkey
									FROM
										video
									WHERE
										video_id = {$this->db->escape($param['video_id'])}
									AND
										status != 9
								");

		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}

	//----------------------------------------------
	// ビデオ一覧の状態取得
	// [2012/11/15]ALF Stream API直接取得から video_alfstream_status テーブルからの取得に変更
	//             一覧取得時に同時取得するため本メソッドは無効化
	//----------------------------------------------
	/*function getStreamStatus($param){
		$param = array_merge(array(
				'idkey'			=> '',
		), $param);

		$this->load->library('Curl');
		$this->load->helper('json');

		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/', array(
			"authkey"			=> $this->alfstreamParam['auth_key'],
			'q'					=> "idkey:{$param['idkey']}",
			'page'				=> '',
			'per_page'			=> '',
		));

		$decodedContent = json_decode($content);
		return $decodedContent;
	}*/

	//----------------------------------------------
	// [2012/08/06]新規登録
	//----------------------------------------------
	function insert_video($param){
		
		$mainVideoId = 0;
		
		if ($param['update_flg'] == 0){
			// Insert video
			$param = array_merge(array(
					'video_name'			=> 'no value',
					'video_logic_name'		=> 'no value',
					'video_caption'			=> 'no value',
					'tags'					=> 'tag1',
					'school_id'				=> 0,
					'teacher_id'			=> 0,
					'page_num'				=> 0,
					'original_file_size'	=> 0,
					'local_reading_flag'	=> 0,
					'local_reading_open'	=> '',
					'local_reading_close'	=> NULL,
					'status'				=> 0,
					'added_at'				=> date("Y/m/d H:i:s"),
					'update_at'				=> date("Y/m/d H:i:s"),
			), $param);

			$login_teacher_id = $this->libauth->get_teacher_id();
			if($login_teacher_id < 0){
				$login_teacher_id = 0;
			}

			$res = $this->db->query($this->db->insert_string('video', array(
					'video_name'			=> $param['video_name'],
					'video_logic_name'		=> $param['video_logic_name'],
					'video_caption'			=> $param['video_caption'],
					'video_tags'			=> $param['video_tags'],
					'school_id'				=> $param['school_id'],
					'teacher_id'			=> $login_teacher_id,
					'page_num'				=> $param['page_num'],
					'original_file_size'	=> $param['original_file_size'],
					'local_reading_flag'	=> $param['local_reading_flag'],
					'local_reading_open'	=> $param['local_reading_open'],
					'local_reading_close'	=> $param['local_reading_close'],
					'status'				=> $param['status'],
					'added_at'				=> $param['added_at'],
					'update_at'				=> $param['update_at'],
				)
			));

			$mainVideoId = $this->db->insert_id();
		}else{
			//修正（Learning Side）
			$wDate = date('Y/m/d H:i:s');
			$sql = "UPDATE
						video
					SET 
						video_logic_name    = ?,
						video_caption       = ?,
						video_tags          = ?,
						local_reading_flag  = ?,
						local_reading_open  = ?,
						local_reading_close = ?,
						update_at           = ?
					WHERE
						video_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$param['video_logic_name'],
									$param['video_caption'],
									$param['video_tags'],
									$param['local_reading_flag'],
									$param['local_reading_open'],
									$param['local_reading_close'],
									$wDate,
									$param['video_id']
								));
			$this->db->trans_complete();
			
			$mainVideoId = $param['video_id'];
		}
		
		// streamからURL取得
		$this->load->library('Curl');
		$this->load->helper('json');
		
		// [2012/12/26] SSL対応

// [2013/01/11]【inject.http_upload_return_to】に設定するプロトコル指定
// 開発・ステージングの場合「https⇒http⇒https」の場合があるための対応
// 本番の場合「https⇒https⇒https」or「http⇒http⇒http」
if(empty($_SERVER['HTTPS'])){
	$return_protocol = 'http://';
}else{
	$return_protocol = 'https://';
}

		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
				"authkey"						=> $this->alfstreamParam['auth_key'],
				'code'							=> $this->alfstreamParam['code'],
				'title'							=> $param['video_logic_name'],
				'description'					=> $param['video_caption'],
				'tags'							=> $param['video_tags'],
				'meta.test'						=> '',
				'security'						=> 'DEFAULT',
				'process'						=> 'DEFAULT',
				'window_from'					=> '',
				'window_to'						=> '',
				'inject'						=> 'http_upload',
				'inject.http_upload_return_to'	=> $return_protocol.$this->config->item('domain_name_cms').'/cms_video/new_commit',
				'ssl'							=> 'on',
			));
		}else{
			$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
				"authkey"						=> $this->alfstreamParam['auth_key'],
				'code'							=> $this->alfstreamParam['code'],
				'title'							=> $param['video_logic_name'],
				'description'					=> $param['video_caption'],
				'tags'							=> $param['video_tags'],
				'meta.test'						=> '',
				'security'						=> 'DEFAULT',
				'process'						=> 'DEFAULT',
				'window_from'					=> '',
				'window_to'						=> '',
				'inject'						=> 'http_upload',
				'inject.http_upload_return_to'	=> $return_protocol.$this->config->item('domain_name_cms').'/cms_video/new_commit',
			));
		}
/*		$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
			"authkey"						=> $this->alfstreamParam['auth_key'],
			'code'							=> $this->alfstreamParam['code'],
			'title'							=> $param['video_logic_name'],
			'description'					=> $param['video_caption'],
			'tags'							=> $param['video_tags'],
			'meta.test'						=> '',
			'security'						=> 'DEFAULT',
			'process'						=> 'DEFAULT',
			'window_from'					=> '',
			'window_to'						=> '',
			'inject'						=> 'http_upload',
			'inject.http_upload_return_to'	=> 'http://'.$this->config->item('domain_name_cms').'/cms_video/new_commit',
		));	*/
		
		$decodedContent = json_decode($content);
		
		// Update video
		$res = $this->db->query($this->db->update_string('video', array(
				'idkey'	=> $decodedContent->dat->idkey,
			),'video_id='.$mainVideoId
		));
		
		return array(
			'lastInsertId'	=> $mainVideoId,
			'idkey'			=> $decodedContent->dat->idkey,
			'upload_url'	=> $decodedContent->dat->upload_url,
		);
	}




	//----------------------------------------------
	//更新処理
	// [2012/08/20]更新処理後の戻り値をtrueからビデオIDに変更
	//----------------------------------------------
	function update_video($param){

		if ($param['update_flg'] == 0){
			//新規
			$param = array_merge(array(
					'video_name'			=> 'no value',
					'video_logic_name'		=> 'no value',
					'video_caption'			=> 'no value',
					'tags'					=> 'tag1',
					'school_id'				=> 0,
					'teacher_id'			=> 0,
					'page_num'				=> 0,
					'original_file_size'	=> 0,
					'local_reading_flag'	=> 0,
					'local_reading_open'	=> '',
					'local_reading_close'	=> NULL,
					'status'				=> 0,
					'added_at'				=> date("Y/m/d H:i:s"),
					'update_at'				=> date("Y/m/d H:i:s"),
			), $param);

			$login_teacher_id = $this->libauth->get_teacher_id();
			if($login_teacher_id < 0){
				$login_teacher_id = 0;
			}

			$res = $this->db->query($this->db->insert_string('video', array(
					'video_name'			=> $param['video_name'],
					'video_logic_name'		=> $param['video_logic_name'],
					'video_caption'			=> $param['video_caption'],
					'video_tags'			=> $param['video_tags'],
					'school_id'				=> $param['school_id'],
					'teacher_id'			=> $login_teacher_id,
					'page_num'				=> $param['page_num'],
					'original_file_size'	=> $param['original_file_size'],
					'local_reading_flag'	=> $param['local_reading_flag'],
					'local_reading_open'	=> $param['local_reading_open'],
					'local_reading_close'	=> $param['local_reading_close'],
					'status'				=> $param['status'],
					'added_at'				=> $param['added_at'],
					'update_at'				=> $param['update_at'],
				)
			));

			$lastInsertId = $this->db->insert_id();

			$this->video_dir = $this->config->item('video_dir');
			$up_path = $this->video_dir;
			if(!is_dir($up_path)){
				mkdir($up_path, 0777, TRUE);
				chmod($up_path, 0777);
			}

			$this->load->library('upload', array(
				'upload_path'   => $up_path,
				'allowed_types' => '*',
				'overwrite'     => TRUE,
				'remove_spaces' => TRUE,
				'file_name'     => date('Ymd_His').'_'.rand(),
			));
			if(!$this->upload->do_upload('local_file')){
				return false;
			} else {
				$upload_data = $this->upload->data();
				chmod($up_path.'/'.$upload_data['file_name'],0777);

				$res = $this->db->query($this->db->update_string('video', array(
						'video_name'	=> $upload_data['file_name'],
					),'video_id='.$lastInsertId
				));

				$this->load->library('Curl');
				$this->load->helper('json');

				// [2012/12/26] SSL対応
				$content = '';
				if($this->_ssl_server_check() == 'https'){
					$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
						"authkey"			=> $this->alfstreamParam['auth_key'],
						'code'				=> $this->alfstreamParam['code'],
						'title'				=> $param['video_logic_name'],
						'description'		=> $param['video_caption'],
						'tags'				=> $param['video_tags'],
						'meta.test'			=> '',
						'security'			=> 'DEFAULT',
						'process'			=> 'DEFAULT',
						'window_from'		=> '',
						'window_to'			=> '',
						'inject'			=> 'pull',
						'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
						'ssl'				=> 'on',
					));
				}else{
					$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
						"authkey"			=> $this->alfstreamParam['auth_key'],
						'code'				=> $this->alfstreamParam['code'],
						'title'				=> $param['video_logic_name'],
						'description'		=> $param['video_caption'],
						'tags'				=> $param['video_tags'],
						'meta.test'			=> '',
						'security'			=> 'DEFAULT',
						'process'			=> 'DEFAULT',
						'window_from'		=> '',
						'window_to'			=> '',
						'inject'			=> 'pull',
						'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
					));
				}
/*				$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
					"authkey"			=> $this->alfstreamParam['auth_key'],
					'code'				=> $this->alfstreamParam['code'],
					'title'				=> $param['video_logic_name'],
					'description'		=> $param['video_caption'],
					'tags'				=> $param['video_tags'],
					'meta.test'			=> '',
					'security'			=> 'DEFAULT',
					'process'			=> 'DEFAULT',
					'window_from'		=> '',
					'window_to'			=> '',
					'inject'			=> 'pull',
					'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
				));	*/

				$decodedContent = json_decode($content);

				$res = $this->db->query($this->db->update_string('video', array(
						'idkey'	=> $decodedContent->dat->idkey,
					),'video_id='.$lastInsertId
				));

				return array(
					'lastInsertId'	=> $lastInsertId,
				);
			}
		}else{
			//修正（Streaming Side）
			$this->load->library('Curl');
		//	$this->load->helper('json');


			// [2012/12/26] SSL対応
			$content = '';
			if($this->_ssl_server_check() == 'https'){
				$content = $this->curl->simple_put('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$param['idkey'].'', array(
					"authkey"			=> $this->alfstreamParam['auth_key'],
					'code'				=> $this->alfstreamParam['code'],
					'title'				=> $param['video_logic_name'],
					'description'		=> $param['video_caption'],
					'tags'				=> $param['video_tags'],
					'ssl'				=> 'on',
				));
			}else{
				$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$param['idkey'].'', array(
					"authkey"			=> $this->alfstreamParam['auth_key'],
					'code'				=> $this->alfstreamParam['code'],
					'title'				=> $param['video_logic_name'],
					'description'		=> $param['video_caption'],
					'tags'				=> $param['video_tags'],
				));
			}
/*			$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$param['idkey'].'', array(
				"authkey"			=> $this->alfstreamParam['auth_key'],
				'code'				=> $this->alfstreamParam['code'],
				'title'				=> $param['video_logic_name'],
				'description'		=> $param['video_caption'],
				'tags'				=> $param['video_tags'],
			));	*/
			
			//修正（Learning Side）
			$wDate = date('Y/m/d H:i:s');
			$sql = "UPDATE
						video
					SET 
						video_logic_name    = ?,
						video_caption       = ?,
						video_tags          = ?,
						local_reading_flag  = ?,
						local_reading_open  = ?,
						local_reading_close = ?,
						update_at           = ?
					WHERE
						video_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$param['video_logic_name'],
									$param['video_caption'],
									$param['video_tags'],
									$param['local_reading_flag'],
									$param['local_reading_open'],
									$param['local_reading_close'],
									$wDate,
									$param['video_id']
								));
			$this->db->trans_complete();
			
		//	return true;
			return array(
				'lastInsertId'	=> $param['video_id'],
			);
		}
	}
	
	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_material($videoId){
		$this->load->library('Curl');
		$this->load->helper('json');

		$query = $this->db->query(
			' SELECT * FROM video'.
			' WHERE video_id = ?',
			array(
				$videoId,
			)
		);
		$video = $query->row_array();

		// [2013/01/11]video.idkey がない場合の対応
		if( empty($video['idkey']) ){
			$res = $this->db->query($this->db->update_string('video', array(
					'status'	=> 9,
				),'video_id='.$videoId
			));
			return true;
		}

		// [2012/12/26] SSL対応
		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_delete('https://'.$this->config->item('video_stream_api_domain_name')."/v1/media/{$video['idkey']}", array(
				"authkey"			=> $this->alfstreamParam['auth_key'],
				'ssl'				=> 'on',
			));
		}else{
			$content = $this->curl->simple_delete('http://'.$this->config->item('video_stream_api_domain_name')."/v1/media/{$video['idkey']}", array(
				"authkey"			=> $this->alfstreamParam['auth_key'],
			));
		}
/*		$content = $this->curl->simple_delete('http://'.$this->config->item('video_stream_api_domain_name')."/v1/media/{$video['idkey']}", array(
			"authkey"			=> $this->alfstreamParam['auth_key'],
		));	*/

		$decodedContent = json_decode($content);

		if($decodedContent->stat != "200"){
			return false;
		}
		else{
			$res = $this->db->query($this->db->update_string('video', array(
					'status'	=> 9,
				),'video_id='.$videoId
			));
			return true;
		}
	}
	
	//----------------------------------------------
	//未変換資料一覧取得
	//----------------------------------------------
	function get_unchanged_material() {
		$ret = array();
		$query = $this->db->query(
			' SELECT * FROM video'.
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
	//変換済ステータス更新処理
	//----------------------------------------------
	function update_video_convert_status($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id'	=> 0,
							'page_num'		=> 1,
						),
						$param
					);
		$sql = "UPDATE
					video 
				SET
					status = 1,
					page_num = ?
				WHERE 
					video_id = ?";
		$this->db->trans_start();
		$this->db->query($sql, array($param['page_num'], $param['video_id']));
		$this->db->trans_complete();
		
	}

	//----------------------------------------------
	//変換済ステータス更新処理
	//----------------------------------------------
	function new_service($param=array()){
		$param = array_merge(
			array(
				'name'	=> time(),
			),
			$param
		);

		$this->load->library('Curl');
		$this->load->helper('json');

		$query = array_merge($this->config->item('video_stream_api_new_service_param'), array(
			'name'	=> $param['name']
		));

		// [2012/12/26] SSL対応
		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name_admin').'/servicer-add',
				$query,
				array(
					'USERPWD'	=> "{$this->config->item('video_stream_api_basic_id')}:{$this->config->item('video_stream_api_basic_pw')}",
				//	'ssl'		=> 'on',
				)
			);
		}else{
			$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name_admin').'/servicer-add',
				$query,
				array(
					'USERPWD'	=> "{$this->config->item('video_stream_api_basic_id')}:{$this->config->item('video_stream_api_basic_pw')}",
				)
			);
		}
/*		$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name_admin').'/servicer-add',
			$query,
			array(
				'USERPWD'	=> "{$this->config->item('video_stream_api_basic_id')}:{$this->config->item('video_stream_api_basic_pw')}",
			)
		);*/

		$decodedContent = json_decode($content);
		return $decodedContent;
	}

	//----------------------------------------------
	//[2012/07/24]    新規メソッド＋修正メソッド（get_material）
	//----------------------------------------------
	function get_video_history($param){
	
		$this->load->library('Curl');
	
		// 開始日付（システム日付 から 一週間前）
		$startDate = date("Y-m-d", strtotime("-12 week") ); //-1
		// 終了日付（システム日付）
		$endDate = date("Y-m-d");

		// ビデオ視聴履歴の取得
		// [2012/12/26] SSL対応
		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_get('https://'.$this->config->item('video_stream_api_domain_name').'/v1/analyze/log', array(
				"authkey"	=> $this->alfstreamParam['auth_key'],
				'from'		=> $startDate,
				'to'		=> $endDate,
				'idkey'		=> $param['idkey'],
				'ssl'		=> 'on',
			));
		}else{
			$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/analyze/log', array(
				"authkey"	=> $this->alfstreamParam['auth_key'],
				'from'		=> $startDate,
				'to'		=> $endDate,
				'idkey'		=> $param['idkey'],
			));
		}
/*		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/analyze/log', array(
			"authkey"	=> $this->alfstreamParam['auth_key'],
			'from'		=> $startDate,
			'to'		=> $endDate,
			'idkey'		=> $param['idkey'],
		));	*/
		
		
		//モデル読み込み
		$this->load->model('model_student');
		
		$history_array = array();
		$history_data  = explode("\r\n",trim($content));  // 改行分割

		foreach($history_data as $line_data){
			// 0		1		2			3						4						5				6
			// 再生日時	IDKEY	UID(生徒ID)	再生フラグ(1 が再生)	閲覧率(パーセント表示)	UserAgent Type	UserAgent
			$column_data = explode("\t",trim($line_data));
			
			// 配列要素数が１より大きい場合の処理
			if(count($column_data)>1){
				// uid から受講者名を取得
				$student_param = array(
								'student_id' => $column_data[2],
							);
				$temp = $this->model_student->get_name($student_param);
				$temp_student_name = $temp['student_name'];
				
				//出力条件：閲覧率が10%を超える。受講者が有効であること。
				if( (!is_null($temp_student_name)) and ($column_data[4] > 10) ) {
					$temp['date']         = date("Y/m/d H:i:s",$column_data[0]);
					$temp['uid']          = $column_data[2];
					$temp['student_name'] = $temp_student_name;
					$temp['percent']      = $column_data[4];
					
					array_push($history_array, $temp);
				}
			}
		}
		
		return $history_array;
	}

	//----------------------------------------------
	// [2012/08/20]ビデオ講座マスタから講座ID取得
	//----------------------------------------------
	function get_video_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										video_lecture
									WHERE
										video_id = {$this->db->escape($param['video_id'])}
									ORDER BY
										cource_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return null;
		}
	}

	//----------------------------------------------
	// [2012/08/20]ビデオ講座マスタの登録更新処理
	//----------------------------------------------
	function update_video_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM video_lecture
				WHERE
					video_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['video_id'],
							));
		
		//選択受講講座を登録
		foreach($data['video_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						video_lecture
					(
						video_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['video_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// [2012/10/03]ビデオ検索インデックスの登録更新処理
	//----------------------------------------------
	function update_video_search_index($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id' => 0,
							'data'     => array(),
						),
						$param
					);
		$data = $param['data'];

		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM video_search_index 
				WHERE
					video_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['video_id'],
							));

		// 所属講座のループ
		foreach($data['video_lectures'] as $cource_id) {
			// タグのループ
			foreach(explode(",", $data['video_tags']) as $_tag){
				if(strlen($_tag) == 0){
					$sql = "INSERT INTO
								video_search_index
							(
								video_id,
								cource_id,
								video_tag
							)
							VALUES(?,?,NULL)
							";
					$this->db->query($sql, 
										array(
											$param['video_id'],
											$cource_id
										));
				}else{
					$sql = "INSERT INTO
								video_search_index
							(
								video_id,
								cource_id,
								video_tag
							)
							VALUES(?,?,?)
							";
					$this->db->query($sql, 
										array(
											$param['video_id'],
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
			' SELECT video_tags'.
			'   FROM video'.
			'  WHERE school_id   = ? '.
			'    AND status      = 0',
			array(
				$param['school_id'],
			)
		);

		$tagKeys = array();
		foreach($query->result_array() as $row){
			if($row['video_tags']){
				foreach(explode(",", $row['video_tags']) as $_tag){
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
	// [2012/11/20]ビデオチェックボックス用一覧取得
	//----------------------------------------------
	function get_video_checkbox_list($param) {
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//SQL生成
		$sql  = '';
		$sql .= "SELECT video_id ,video_logic_name ";
		$sql .= "  FROM video ";
		$sql .= " WHERE status = 0 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		$sql .= " ORDER BY video_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属のビデオを取得
	//----------------------------------------------
	function get_cource_video($param) {
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
		$sql .= "SELECT video_id, video_logic_name ";
		$sql .= "  FROM video ";
		$sql .= " WHERE status = 0 ";
		$sql .= "   AND school_id = {$this->db->escape($param['school_id'])} ";
		
		if (isset($param['free_word']) && $param['free_word'] != '') {
			$sql .= "   AND ( ";
			$sql .= "        video_logic_name LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR video_caption    LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "     OR video_tags       LIKE '%{$this->db->escape_like_str($param['free_word'])}%' ";
			$sql .= "   )";
		}
		
		$sql .= " ORDER BY video_id ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	//----------------------------------------------
	// [2012/12/07]図書室ビデオ専属紐付けタグ（※）の取得処理
	//             ※exclusive_book_library_video
	//----------------------------------------------
	function get_exclusive_book_library_video($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id' => 0,						// ビデオID
						),
						$param
					);

		//SQL生成
		$sql  = "";
		$sql .= "SELECT video_id, exclusive_no, exclusive_tag, book_library_id, page_num, status, update_at ";
		$sql .= "  FROM exclusive_book_library_video ";
		$sql .= " WHERE 1 = 1 ";
		$sql .= "   AND video_id = {$this->db->escape($param['video_id'])} ";
		$sql .= " ORDER BY exclusive_no ASC ";
		
		$query = $this->db->query($sql);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return null;
		}
	}

	//----------------------------------------------
	// [2012/12/07]図書室ビデオ専属紐付けタグ（※）の登録更新処理
	//             ※exclusive_book_library_video
	//----------------------------------------------
	function update_exclusive_book_library_video($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id' => 0,						// ビデオID
							'exclusive_tag'          => array(),	// 専属タグ
							'exclusive_book_library' => array(),	// 対象図書室ID
							'exclusive_page_number'  => array(),	// 対象図書室内ページNo
							'exclusive_status'       => array(),	// 状態
						),
						$param
					);
		$exclusive_tag          = $param['exclusive_tag'];				// 専属タグ
		$exclusive_book_library = $param['exclusive_book_library'];		// 対象図書室ID
		$exclusive_page_number  = $param['exclusive_page_number'];		// 対象図書室内ページNo
		$exclusive_status       = $param['exclusive_status'];			// 状態

//print var_dump( $param['exclusive_status']);
//		$exclusive_status = array();
//		array_push($exclusive_status, 9, 9, 9, 9, 9);
//		foreach($param['exclusive_status'] as $temp){
//			$exclusive_status[$temp] = 0;
//		}
//		//$data = $param['data'];
//print var_dump( $exclusive_status);

		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM exclusive_book_library_video 
				WHERE
					video_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['video_id'],
							));

		// 配列内の重複内容を削除[2013/01/07]
		for($index_no = 0; $index_no < count($exclusive_tag); $index_no++) {
			$check_flag = 0;
			
			for($chk_count = 0; $chk_count < count($exclusive_tag); $chk_count++) {
				if( ($exclusive_tag[$index_no] == $exclusive_tag[$chk_count]) && ($index_no != $chk_count) ){
					if( ($exclusive_book_library[$index_no] == $exclusive_book_library[$chk_count]) && ($index_no != $chk_count) ){
						if( ($exclusive_page_number[$index_no] == $exclusive_page_number[$chk_count]) && ($index_no != $chk_count) ){
							$exclusive_tag[$chk_count]          = '';
							$exclusive_book_library[$chk_count] = 0;
							$exclusive_page_number[$chk_count]  = -1;
							break;
						}
					}
				}
			}
		}

		$wDate = date('Y/m/d H:i:s');
		$index_no = -1;
		$exclusive_no = -1;
		foreach($exclusive_tag as $exclusive_tag_data){
			$index_no = $index_no + 1;
			// 下記のいずれかに該当の場合、登録なし
			if(strlen($exclusive_tag_data) == 0) continue;			// 専属タグ = 文字列ゼロなら次データ
			if($exclusive_book_library[$index_no] < 1) continue;	// 図書室ID < 1 なら次データ
			if($exclusive_page_number[$index_no] < 0) continue;		// ページNo < 0 なら次データ
		
			// 新規登録処理
			$sql  = '';
			$sql .= 'INSERT INTO exclusive_book_library_video ';
			$sql .= ' (video_id, exclusive_no, exclusive_tag, book_library_id, page_num, status, update_at) ';
			$sql .= ' VALUES ';
			$sql .= ' (?, ?, ?, ?, ?, ?, ?) ';
			
			// ビデオID内連番の設定
			$exclusive_no = $exclusive_no + 1;
			
			$this->db->query($sql, 
								array(
									$param['video_id'],
									$exclusive_no,
									$exclusive_tag_data,
									$exclusive_book_library[$index_no],
									$exclusive_page_number[$index_no],
									$exclusive_status[$index_no],
									$wDate
								));
		}

		$this->db->trans_complete();
	}

	//----------------------------------------------
	// [2013/01/07]図書室ビデオ専属紐付けタグ（※）の重複チェック
	//             ※exclusive_book_library_video
	// 重複あり⇒true、重複なし⇒false
	//----------------------------------------------
	function check_exclusive_book_library_video($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id'               => 0,		// ビデオID
							'exclusive_tag'          => '',		// 専属タグ
							'exclusive_book_library' => 0,		// 対象図書室ID
							'exclusive_page_number'  => 0,		// 対象図書室内ページNo
							'exclusive_status'       => 0,		// 状態
							'school_id'              => 0,		// 学校ID
						),
						$param
					);
		
		//SQL文にて重複チェック
		// 条件：有効ビデオかつ引数のビデオ以外にて、専属タグ・図書室ID・ページ番号が同じもの
		// ただし、ページ番号がゼロの場合、ページ番号を条件から除外する（ゼロ＝全ページ対象のため）
		//SQL生成
		$sql  = "";
		$sql .= "SELECT exclusive_book_library_video.* ";
		$sql .= "  FROM exclusive_book_library_video ";
		$sql .= " WHERE 1=1 ";
		$sql .= "   AND video_id <> ? ";
		$sql .= "   AND video_id IN (SELECT video_id FROM video WHERE status = 0 AND school_id = ?) ";
		$sql .= "   AND exclusive_tag    = ? ";
		$sql .= "   AND book_library_id  = ? ";
		if($param['exclusive_page_number']>0){
			$sql .= "   AND ( (page_num = ?) OR (page_num = 0) ) ";
		}
		
		$query = $this->db->query($sql,
			array(
				$param['video_id'],
				$param['school_id'],
				$param['exclusive_tag'],
				$param['exclusive_book_library'],
				$param['exclusive_page_number'],
			)
		);
		
		//データリターン
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	//----------------------------------------------
	// [2012/12/26] SSLチェック処理
	//----------------------------------------------
	function _ssl_server_check(){
		$return_data = '';
	
		if(getenv('ENVIRONMENT') == 'development' || getenv('ENVIRONMENT') == 'testing'){
			//開発、ステージはssh非対応
			$return_data = 'http';
		}
		else{
			if(empty($_SERVER['HTTPS'])){
				//本番でhttpアクセス
				$return_data = 'http';
			}
			else{
				//本番でhttpsアクセス
				$return_data = 'https';
			}
		}
		
		return $return_data;
	
	}
}

?>
