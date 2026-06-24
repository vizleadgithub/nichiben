<?php
#[AllowDynamicProperties]
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
		$this->load->model('Modelschoolcontract');

		$this->alfstreamParam = $this->Modelschoolcontract->getAlfstreamParam();
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
			'     , video_alfstream_status.alfstream_duration AS alfstream_duration '.
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
			  , video_alfstream_status.alfstream_status
			  , video_alfstream_status.parent_idkey
			  , video.sound_only 
			  , video.video_popup 
			  , video.type_x15 
			FROM
				video LEFT JOIN video_alfstream_status ON video.video_id = video_alfstream_status.video_id
			WHERE
				video.video_id = {$this->db->escape($param['video_id'])}
			AND
				video.status != 9
		");

		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
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
					'video_name'		=> 'no value',
					'video_logic_name'	=> 'no value',
					'video_caption'		=> 'no value',
					'tags'			=> 'tag1',
					'school_id'		=> 0,
					'teacher_id'		=> 0,
					'page_num'		=> 0,
					'original_file_size'	=> 0,
					'local_reading_flag'	=> 0,
					'local_reading_open'	=> '',
					'local_reading_close'	=> NULL,
					'status'		=> 0,
					'added_at'		=> date("Y/m/d H:i:s"),
					'update_at'		=> date("Y/m/d H:i:s"),
					'sound_only'		=> 0,
					'video_popup'		=> 0,
					'type_x15'		=> 0,
			), $param);

			$login_teacher_id = $this->libauth->get_teacher_id();
			if($login_teacher_id < 0){
				$login_teacher_id = 0;
			}

			$res = $this->db->query($this->db->insert_string('video', array(
					'video_name'		=> $param['video_name'],
					'video_logic_name'	=> $param['video_logic_name'],
					'video_caption'		=> $param['video_caption'],
					'video_tags'		=> $param['video_tags'],
					'school_id'		=> $param['school_id'],
					'teacher_id'		=> $login_teacher_id,
					'page_num'		=> $param['page_num'],
					'original_file_size'	=> $param['original_file_size'],
					'local_reading_flag'	=> $param['local_reading_flag'],
					'local_reading_open'	=> $param['local_reading_open'],
					'local_reading_close'	=> $param['local_reading_close'],
					'status'		=> $param['status'],
					'added_at'		=> $param['added_at'],
					'update_at'		=> $param['update_at'],
					'sound_only'		=> $param['sound_only'],
					'video_popup'		=> $param['video_popup'],
					'type_x15'		=> $param['type_x15'],
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
						update_at           = ?, 
						sound_only          = ?, 
						video_popup         = ?, 
						type_x15            = ? 
					WHERE 
						video_id = ? ";
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
					$param['sound_only'],
					$param['video_popup'],
					$param['type_x15'],

					$param['video_id']
				)
			);
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

		$process_str = "DEFAULT";
		if( trim($param['sound_only'])=="1" ){
			$process_str = "DEFAULTAUX";
		}

		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
				"authkey"			=> $this->alfstreamParam['auth_key'],
				'code'				=> $this->alfstreamParam['code'],
				'title'				=> $param['video_logic_name'],
				'description'			=> $param['video_caption'],
				'tags'				=> $param['video_tags'],
				'meta.test'			=> '',
				'security'			=> 'DEFAULT',
				'processor'			=> $process_str,
				'window_from'			=> '',
				'window_to'			=> '',
				'inject'			=> 'http_upload',
				'inject.http_upload_return_to'	=> $return_protocol.$this->config->item('domain_name_cms').'/cms_video/new_commit',
				'ssl'							=> 'on',
			));
		}else{
			$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
				"authkey"			=> $this->alfstreamParam['auth_key'],
				'code'				=> $this->alfstreamParam['code'],
				'title'				=> $param['video_logic_name'],
				'description'			=> $param['video_caption'],
				'tags'				=> $param['video_tags'],
				'meta.test'			=> '',
				'security'			=> 'DEFAULT',
				'processor'			=> $process_str,
				'window_from'			=> '',
				'window_to'			=> '',
				'inject'			=> 'http_upload',
				'inject.http_upload_return_to'	=> $return_protocol.$this->config->item('domain_name_cms').'/cms_video/new_commit',
			));
		}
		/*
		$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
			"authkey"						=> $this->alfstreamParam['auth_key'],
			'code'							=> $this->alfstreamParam['code'],
			'title'							=> $param['video_logic_name'],
			'description'					=> $param['video_caption'],
			'tags'							=> $param['video_tags'],
			'meta.test'						=> '',
			'security'						=> 'DEFAULT',
			'processor'						=> 'DEFAULT',
			'window_from'					=> '',
			'window_to'						=> '',
			'inject'						=> 'http_upload',
			'inject.http_upload_return_to'	=> 'http://'.$this->config->item('domain_name_cms').'/cms_video/new_commit',
		));
		*/
		
		$decodedContent = json_decode($content);
		
		// Update video
		$res = $this->db->query($this->db->update_string('video', array(
				'idkey'	=> $decodedContent->dat->idkey,
			),'video_id='.$this->db->escape($mainVideoId)
		));
		return array(
			'lastInsertId'	=> $mainVideoId,
			'idkey'		=> $decodedContent->dat->idkey,
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
					'video_name'		=> 'no value',
					'video_logic_name'	=> 'no value',
					'video_caption'		=> 'no value',
					'tags'			=> 'tag1',
					'school_id'		=> 0,
					'teacher_id'		=> 0,
					'page_num'		=> 0,
					'original_file_size'	=> 0,
					'local_reading_flag'	=> 0,
					'local_reading_open'	=> '',
					'local_reading_close'	=> NULL,
					'status'		=> 0,
					'added_at'		=> date("Y/m/d H:i:s"),
					'update_at'		=> date("Y/m/d H:i:s"),
					'sound_only'		=> 0,
					'video_popup'		=> 0,
					'type_x15'		=> 0,
			), $param);

			$login_teacher_id = $this->libauth->get_teacher_id();
			if($login_teacher_id < 0){
				$login_teacher_id = 0;
			}

			$res = $this->db->query($this->db->insert_string('video', array(
					'video_name'		=> $param['video_name'],
					'video_logic_name'	=> $param['video_logic_name'],
					'video_caption'		=> $param['video_caption'],
					'video_tags'		=> $param['video_tags'],
					'school_id'		=> $param['school_id'],
					'teacher_id'		=> $login_teacher_id,
					'page_num'		=> $param['page_num'],
					'original_file_size'	=> $param['original_file_size'],
					'local_reading_flag'	=> $param['local_reading_flag'],
					'local_reading_open'	=> $param['local_reading_open'],
					'local_reading_close'	=> $param['local_reading_close'],
					'status'		=> $param['status'],
					'added_at'		=> $param['added_at'],
					'update_at'		=> $param['update_at'],
					'sound_only'		=> $param['sound_only'],
					'video_popup'		=> $param['video_popup'],
					'type_x15'		=> $param['type_x15'],
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
					),'video_id='.$this->db->escape($lastInsertId)
				));

				$this->load->library('Curl');
				$this->load->helper('json');

				$process_str = "DEFAULT";
				if( trim($param['sound_only'])=="1" ){
					$process_str = "DEFAULTAUX";
				}

				// [2012/12/26] SSL対応
				$content = '';
				if($this->_ssl_server_check() == 'https'){
					$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
						"authkey"		=> $this->alfstreamParam['auth_key'],
						'code'			=> $this->alfstreamParam['code'],
						'title'			=> $param['video_logic_name'],
						'description'		=> $param['video_caption'],
						'tags'			=> $param['video_tags'],
						'meta.test'		=> '',
						'security'		=> 'DEFAULT',
						'processor'		=> $process_str,
						/*'processor'		=> 'DEFAULT',*/
						'window_from'		=> '',
						'window_to'		=> '',
						'inject'		=> 'pull',
						'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
						'ssl'			=> 'on',
					));
				}else{
					$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
						"authkey"		=> $this->alfstreamParam['auth_key'],
						'code'			=> $this->alfstreamParam['code'],
						'title'			=> $param['video_logic_name'],
						'description'		=> $param['video_caption'],
						'tags'			=> $param['video_tags'],
						'meta.test'		=> '',
						'security'		=> 'DEFAULT',
						'processor'		=> $process_str,
						/*'processor'		=> 'DEFAULT',*/
						'window_from'		=> '',
						'window_to'		=> '',
						'inject'		=> 'pull',
						'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
					));
				}
/*				$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/', array(
					"authkey"		=> $this->alfstreamParam['auth_key'],
					'code'			=> $this->alfstreamParam['code'],
					'title'			=> $param['video_logic_name'],
					'description'		=> $param['video_caption'],
					'tags'			=> $param['video_tags'],
					'meta.test'		=> '',
					'security'		=> 'DEFAULT',
					'processor'		=> 'DEFAULT',
					'window_from'		=> '',
					'window_to'		=> '',
					'inject'		=> 'pull',
					'inject.pull_url'	=> $this->config->item('video_stream_api_pull_uri').'/'.$upload_data['file_name'],
				));
				*/
				$decodedContent = json_decode($content);
				$res = $this->db->query($this->db->update_string('video', array(
						'idkey'	=> $decodedContent->dat->idkey,
					),'video_id='.$this->db->escape($lastInsertId)
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
					"authkey"	=> $this->alfstreamParam['auth_key'],
					'code'		=> $this->alfstreamParam['code'],
					'title'		=> $param['video_logic_name'],
					'description'	=> $param['video_caption'],
					'tags'		=> $param['video_tags'],
					'ssl'		=> 'on',
				));
			}else{
				$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$param['idkey'].'', array(
					"authkey"	=> $this->alfstreamParam['auth_key'],
					'code'		=> $this->alfstreamParam['code'],
					'title'		=> $param['video_logic_name'],
					'description'	=> $param['video_caption'],
					'tags'		=> $param['video_tags'],
				));
			}
			/*
			$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$param['idkey'].'', array(
				"authkey"	=> $this->alfstreamParam['auth_key'],
				'code'		=> $this->alfstreamParam['code'],
				'title'		=> $param['video_logic_name'],
				'description'	=> $param['video_caption'],
				'tags'		=> $param['video_tags'],
			));
			*/
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
						update_at           = ?, 
						sound_only          = ?, 
						video_popup         = ?, 
						type_x15            = ? 
					WHERE 
						video_id = ? ";
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
					$param['sound_only'],
					$param['video_popup'],
					$param['type_x15'],

					$param['video_id']
				)
			);
			$this->db->trans_complete();
			//return true;
			return array(
				'lastInsertId'	=> $param['video_id'],
			);
		}
	}

	//----------------------------------------------
	// ビデオ切り出し用新規登録処理
	//----------------------------------------------
	function update_video_moviecut($param){

		//新規
		$param = array_merge(array(
				'video_name'		=> 'no value',
				'video_logic_name'	=> 'no value',
				'video_caption'		=> 'no value',
				'tags'			=> 'tag1',
				'school_id'		=> 0,
				'teacher_id'		=> 0,
				'page_num'		=> 0,
				'original_file_size'	=> 0,
				'local_reading_flag'	=> 0,
				'local_reading_open'	=> '',
				'local_reading_close'	=> NULL,
				'status'		=> 0,
				'added_at'		=> date("Y/m/d H:i:s"),
				'update_at'		=> date("Y/m/d H:i:s"),
				'sound_only'		=> 0,
				'video_popup'		=> 0,
				'type_x15'		=> 0,
		), $param);

		$login_teacher_id = $this->libauth->get_teacher_id();
		if($login_teacher_id < 0){
			$login_teacher_id = 0;
		}

		$res = $this->db->query($this->db->insert_string('video', array(
				'video_name'		=> $param['video_name'],
				'video_logic_name'	=> $param['video_logic_name'],
				'video_caption'		=> $param['video_caption'],
				'video_tags'		=> $param['video_tags'],
				'school_id'		=> $param['school_id'],
				'teacher_id'		=> $login_teacher_id,
				'page_num'		=> $param['page_num'],
				'original_file_size'	=> $param['original_file_size'],
				'local_reading_flag'	=> $param['local_reading_flag'],
				'local_reading_open'	=> $param['local_reading_open'],
				'local_reading_close'	=> $param['local_reading_close'],
				'status'		=> $param['status'],
				'added_at'		=> $param['added_at'],
				'update_at'		=> $param['update_at'],
				'sound_only'		=> $param['sound_only'],
				'video_popup'		=> $param['video_popup'],
				'type_x15'		=> $param['type_x15'],
			)
		));
		
		// ビデオIDの取得
		$mainVideoId = $this->db->insert_id();
		
		// 切り出し範囲開始・終了を秒変換
		$cut_start  = $param['cut_start_hour'] * 60 * 60;
		$cut_start += $param['cut_start_minute'] * 60;
		$cut_start += $param['cut_start_second'] * 1;
		
		$cut_end    = $param['cut_end_hour'] * 60 * 60;
		$cut_end   += $param['cut_end_minute'] * 60;
		$cut_end   += $param['cut_end_second'] * 1;

		$process_str = "DEFAULT";
		if( trim($param['sound_only'])=="1" ){
			$process_str = "DEFAULTAUX";
		}

		// パラメータ準備
		$alfstream_param = array(
			"authkey"		=> $this->alfstreamParam['auth_key'],
			'code'			=> $this->alfstreamParam['code'],
			'title'			=> $param['video_logic_name'],
			'description'		=> $param['video_caption'],
			'tags'			=> $param['video_tags'],
			'meta.test'		=> '',
			'security'		=> 'DEFAULT',
			'processor'		=> $process_str,
			'window_from'		=> '',
			'window_to'		=> '',
			'idkey'			=> $param['idkey'],
			'metadata.@start_time'	=> sprintf('%.2f', $cut_start),
		);
		  //// 切り出し範囲終了時間(秒）+ 1秒 ≧ 切り出し元時間(秒）の場合、切り出し終了時間設定を無しにする（API仕様）
		  //list($hour_data, $minute_data, $second_data) = split(":", $param['video_time']);
		  //if( ($cut_end + 1) >= (($hour_data * 60 * 60) + ($minute_data * 60) + ($second_data * 1)) ){
		  //	// 設定なし
		  //}else{
		  //	$alfstream_param['metadata.@end_time'] = sprintf('%.2f', $cut_end);
		  //}
		$alfstream_param['metadata.@end_time'] = sprintf('%.2f', $cut_end);
		// SSL対応
		if($this->_ssl_server_check() == 'https'){
			$alfstream_param['ssl'] = 'on';
		}
		
		// ALFStream切り出し処理
		$this->load->library('Curl');
		$this->load->helper('json');
		
		$content = '';
		if($this->_ssl_server_check() == 'https'){
			$content = $this->curl->simple_post('https://'.$this->config->item('video_stream_api_domain_name').'/v1/media/convert', $alfstream_param);
		}else{
			$content = $this->curl->simple_post('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/convert', $alfstream_param);
		}
		$decodedContent = json_decode($content);
		
		// Update video
		$res = $this->db->query($this->db->update_string('video', array(
				'idkey'	=> $decodedContent->dat->idkey,
			),'video_id='.$this->db->escape($mainVideoId)
		));

		// return true;
		return array(
			'lastInsertId'	=> $mainVideoId,
		);
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
				),'video_id='.$this->db->escape($videoId)
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
		/*
		$content = $this->curl->simple_delete('http://'.$this->config->item('video_stream_api_domain_name')."/v1/media/{$video['idkey']}", array(
			"authkey"			=> $this->alfstreamParam['auth_key'],
		));
		*/
		$decodedContent = json_decode($content);
		if($decodedContent->stat != "200"){
			return false;
		}
		else{
			$res = $this->db->query($this->db->update_string('video', array(
					'status'	=> 9,
				),'video_id='.$this->db->escape($videoId)
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
				'page_num'	=> 1,
			),
			$param
		);
		$sql = "UPDATE 
				video 
			SET 
				status = 1, 
				page_num = ? 
			WHERE 
				video_id = ? ";
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
		/*
		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/analyze/log', array(
			"authkey"	=> $this->alfstreamParam['auth_key'],
			'from'		=> $startDate,
			'to'		=> $endDate,
			'idkey'		=> $param['idkey'],
		));
		*/
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
			return [];
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
		$sql = "DELETE FROM video_lecture WHERE video_id = ? ;";
		$this->db->query($sql, 
			array(
				$param['video_id'],
			)
		);
		//選択受講講座を登録
		foreach($data['video_lectures'] as $cource_id) {
			$sql = "INSERT INTO 
					video_lecture 
					( 
						video_id, 
						cource_id, 
						update_at 
					)
					VALUES( ?, ?, ? ) 
			";
			$this->db->query($sql, 
				array(
					$param['video_id'],
					(intval($cource_id) ?? 0),
					$wDate
				)
			);
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
		$sql = "DELETE FROM video_search_index WHERE video_id = ? ;";
		$this->db->query($sql, 
			array(
				$param['video_id'],
			)
		);
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
							VALUES( ?, ?, NULL ) 
					";
					$this->db->query($sql, 
						array(
							$param['video_id'],
							$cource_id
						)
					);
				}else{
					$sql = "INSERT INTO 
							video_search_index 
							( 
								video_id, 
								cource_id, 
								video_tag 
							) 
							VALUES( ?, ?, ? ) 
					";
					$this->db->query($sql, 
						array(
							$param['video_id'],
							$cource_id,
							$_tag
						)
					);
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
			' SELECT video_tags '.
			'   FROM video '.
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
			return [];
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
		//$sql .= "   AND sound_only=0 ";
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
			return [];
		}
	}
	//----------------------------------------------
	// [2012/11/30] [Ajax用]学校所属のビデオを取得
	//----------------------------------------------
	function get_cource_video_so($param) {
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
		$sql .= "   AND sound_only=1 ";
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
			return [];
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
			return [];
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
			)
		);
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
		$sql .= "   AND exclusive_tag    like ? ";
		$sql .= "   AND book_library_id  = ? ";
		$sql_val = array(
			$param['video_id'],
			$param['school_id'],
			$param['exclusive_tag'],
			$param['exclusive_book_library'],
		);
		if($param['exclusive_page_number']>0){
			$sql .= "   AND ( (page_num = ?) OR (page_num = 0) ) ";
			$sql_val[] = $param['exclusive_page_number'];
		}
		$query = $this->db->query($sql,$sql_val);
		
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
		$return_data = 'http';
	
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
		if( $return_data != 'https' ){
			if( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) { 
				$return_data = 'https';
			}
		}
		return $return_data;
	
	}

	//----------------------------------------------
	// ALFStream側視聴履歴 の取得
	//----------------------------------------------
	function get_video_reading_history($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'	=> 0,
				'video_id'		=> 0,
			),
			$param
		);
		
		// SQL
		$sql  = '';
		$sql .= 'SELECT  video_alfstream_reading_history.video_id ';
		$sql .= '       ,video.video_logic_name ';
		$sql .= '       ,video_alfstream_reading_history.student_id ';
		$sql .= "       ,if(student.student_name IS NULL, '未ログインユーザ', student.student_name) AS student_name ";
		//$sql .= '       ,student.student_name ';
		$sql .= "       ,DATE_FORMAT(video_alfstream_reading_history.reading_date, '%Y/%m/%d %H:%i:%s') AS reading_date ";
		$sql .= '       ,video_alfstream_reading_history.percent ';
		$sql .= '  FROM (video_alfstream_reading_history LEFT JOIN video ON video_alfstream_reading_history.video_id = video.video_id) ';
		$sql .= '        LEFT JOIN student ON video_alfstream_reading_history.student_id = student.student_id ';
		$sql .= ' WHERE  1=1';
		if($param['student_id'] > 0){
			$sql .= "   AND  video_alfstream_reading_history.student_id = {$this->db->escape($param['student_id'])} ";
		}
		if($param['video_id'] > 0){
			$sql .= "   AND  video_alfstream_reading_history.video_id = {$this->db->escape($param['video_id'])} ";
		}
		$sql .= ' ORDER  BY video_alfstream_reading_history.reading_date DESC ';
		
		//SQL投入
		$query = $this->db->query($sql,array());

		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// ALFStream側ビデオ再生時間の取得
	//----------------------------------------------
	function get_video_alfstream_status($param){
		//引数設定
		$param = array_merge(
						array(
							'video_id'			=> '',
							'contract_param'	=> '',
							'idkey'				=> '',
						),
						$param
					);
		
		$alfstream_status           = array();
		//	$default_alfstream_duration = '00:00:00';
		
		// video_alfstream_status から video_id の取得
		$sql  = '';
		//	$sql .= 'SELECT  video_id ';
		//	$sql .= '       ,alfstream_status ';
		//	$sql .= "       ,if(alfstream_duration IS NULL , '', DATE_FORMAT(alfstream_duration, '%H:%i:%s') ) AS alfstream_duration ";
		//	$sql .= "       ,if(parent_idkey       IS NULL , '', parent_idkey ) AS parent_idkey ";
		//	$sql .= '       ,update_at ';
		//	$sql .= '  FROM  video_alfstream_status ';
		//	$sql .= ' WHERE  1=1';
		//	$sql .= "   AND  alfstream_status = 'ONLINE' ";
		//	$sql .= "   AND  video_id = {$this->db->escape($param['video_id'])} ";
		$sql .= 'SELECT  video_alfstream_status.video_id ';
		$sql .= '       ,video_alfstream_status.alfstream_status ';
		$sql .= "       ,if(video_alfstream_status.alfstream_duration IS NULL , '', DATE_FORMAT(video_alfstream_status.alfstream_duration, '%H:%i:%s') ) AS alfstream_duration ";
		$sql .= "       ,if(video_alfstream_status.parent_idkey IS NULL , '' , video_alfstream_status.parent_idkey) AS parent_idkey ";
		$sql .= "       ,if(video.video_id IS NULL                      , 0  , video.video_id)                      AS parent_video_id ";
		$sql .= "       ,if(video.video_logic_name IS NULL              , '' , video.video_logic_name)              AS parent_video_logic_name ";
		$sql .= '  FROM  video_alfstream_status LEFT JOIN video on video_alfstream_status.parent_idkey = video.idkey ';
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  video_alfstream_status.alfstream_status = 'ONLINE' ";
		$sql .= "   AND  video_alfstream_status.video_id = {$this->db->escape($param['video_id'])} ";
		
		//SQL投入
		$query = $this->db->query($sql, array());
		
		//再生時間があれば取得して返す
		if ($query->num_rows() > 0){
			$db_data = $query->row_array();
		//	if($db_data['alfstream_duration']) {
				$alfstream_status['alfstream_duration']      = $db_data['alfstream_duration'];
				$alfstream_status['parent_idkey']            = $db_data['parent_idkey'];
				$alfstream_status['parent_video_id']         = $db_data['parent_video_id'];
				$alfstream_status['parent_video_logic_name'] = $db_data['parent_video_logic_name'];
			//	$alfstream_duration = $db_data['alfstream_duration'];
			//	return $alfstream_duration;
		//	}
		}else{
			$alfstream_status['alfstream_duration']      = '00:00:00';
			$alfstream_status['parent_idkey']            = '';
			$alfstream_status['parent_video_id']         = 0;
			$alfstream_status['parent_video_logic_name'] = '';
		}
		
		return $alfstream_status;
		/*	
		// 取れなかった場合の対応
		$this->load->library('Curl');
		$this->load->helper('json');

		// 1.school.contract_param から auth_key 取得
		$auth_key       = $param['contract_param']['alfstream']['auth_key'];

		// 2.ALF Stream API を使用してステータス取得
		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/', array(
			"authkey"			=> $auth_key,
			'q'					=> "idkey:{$param['idkey']}",
			'page'				=> '',
			'per_page'			=> '',
		));

		// 3.取得したステータスから再生時間の取得
		$decodedContent     = json_decode($content);
		$alfstream_duration = $decodedContent->dat->items[0]->duration;	//00:00:28

		// 4.video_alfstream_status に取得した再生時間をアップデート
		$sql  = '';
		$sql .= 'UPDATE  video_alfstream_status ';
		$sql .= "   SET  alfstream_duration = ? ";
		$sql .= "       ,update_at          = ? ";
		$sql .= ' WHERE  1=1 ';
		$sql .= "   AND  alfstream_status = 'ONLINE' ";
		$sql .= "   AND  video_id         = ? ";
		$this->db->trans_start();
		$this->db->query($sql, 
			array(
				$alfstream_duration,
				date('Y-m-d H:i:s'),
				$param['video_id'],
			)
		);
		$this->db->trans_complete();
		return $alfstream_duration;
		*/
	}

	//----------------------------------------------
	// チャプター情報の取得
	//----------------------------------------------
	function get_video_chapter($param){
		//引数設定
		$param = array_merge(
			array(
				'video_id'		=> '',
				'contract_param'	=> '',
				'idkey'			=> '',
			),
			$param
		);
		// SQL
		$sql  = '';
		$sql .= 'SELECT  video_id ';
		$sql .= '       ,chapter_time ';
		$sql .= '       ,chapter_name ';
		//	$sql .= '       ,update_at ';
		$sql .= '  FROM video_chapter ';
		$sql .= ' WHERE  1=1';
		$sql .= "   AND  video_id = {$this->db->escape($param['video_id'])} ";
		$sql .= ' ORDER  BY chapter_time ASC ';
		
		//SQL投入
		$query = $this->db->query($sql,array());
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// チャプター情報の更新
	//----------------------------------------------
	function update_video_chapter($param){
		//引数設定
		$param = array_merge(
			array(
				'video_id' => 0,
				'data'       => array(),
			),
			$param
		);
		$data = $param['data'];
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM video_chapter WHERE video_id = ? ;";
		$this->db->query($sql, 
			array(
				$param['video_id'],
			)
		);
		//選択受講講座を登録
		foreach($data as $chapter) {
			if( strlen($chapter['chapter_time']) > 6 ){
				$sql = "INSERT INTO 
						video_chapter 
						( 
							video_id, 
							chapter_time, 
							chapter_name, 
							update_at 
						)
						VALUES( ?, ?, ?, ? ) 
				";
				$this->db->query($sql, 
					array(
						$param['video_id'],
						$chapter['chapter_time'],
						$chapter['chapter_name'],
						date('Y/m/d H:i:s'),
					)
				);
			}
		}
		$this->db->trans_complete();
	}


	//----------------------------------------------
	// 学校内の有効ビデオ数の取得
	//   ※Stream側のステータスは考慮しない
	//----------------------------------------------
	function get_video_count($school_id = 0) {
		//SQL生成
		$query = $this->db->query("
			SELECT * 
			  FROM video 
			 WHERE school_id = {$this->db->escape($school_id)} 
			".
			//   AND sound_only=0 
			"
			   AND status = 0 
			");
		
		//データリターン
		return $query->num_rows();
	}
	//----------------------------------------------
	// 学校内の有効ビデオ数の取得
	//   ※Stream側のステータスは考慮しない
	//----------------------------------------------
	function get_video_count_so($school_id = 0) {
		//SQL生成
		$query = $this->db->query("
			SELECT * 
			  FROM video 
			 WHERE school_id = {$this->db->escape($school_id)} 
			   AND sound_only=1 
			   AND status = 0 
			");
		
		//データリターン
		return $query->num_rows();
	}

	//----------------------------------------------
	// [[Ajax用]講座所属のビデオを取得
	// school_id + cource_id  => 講座所属のビデオ（stream_status は見ない）
	//----------------------------------------------
	function get_cource_video_exclusive($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'  => 0,
				'cource_id'  => '',
				'video_id'   => 0,
			),
			$param
		);
		//SQL生成
		$sql = "SELECT  video.video_id 
		               ,video.video_logic_name 
		               ,video.school_id 
		          FROM  video 
		         WHERE  1 = 1 
		           AND  video.status = 0
		";
		if($param['cource_id'] != ''){
			$sql .= "   AND video.video_id IN (SELECT video_id FROM video_lecture WHERE cource_id IN ({$param['cource_id']}) )";
		}else{
			$sql .= "   AND video_id = {$this->db->escape($param['video_id'])} ";
		}
		$sql .= " ORDER BY video.video_id ASC ";
		$query = $this->db->query($sql);
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}
	function get_cource_video_exclusive_so($param) {
		//引数設定
		$param = array_merge(
			array(
				'school_id'  => 0,
				'cource_id'  => '',
				'video_id'   => 0,
			),
			$param
		);
		//SQL生成
		$sql = "SELECT  video.video_id 
		               ,video.video_logic_name 
		               ,video.school_id 
		          FROM  video 
		         WHERE  1 = 1 
		           AND sound_only=1 
		           AND  video.status = 0
		";
		if($param['cource_id'] != ''){
			$sql .= "   AND video.video_id IN (SELECT video_id FROM video_lecture WHERE cource_id IN ({$param['cource_id']}) )";
		}else{
			$sql .= "   AND video_id = {$this->db->escape($param['video_id'])} ";
		}
		$sql .= " ORDER BY video.video_id ASC ";
		$query = $this->db->query($sql);
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}


	//----------------------------------------------
	// ビデオのサムネイル確認
	//   初回　　：ALFStreamのPosterをALFLearningサーバへ画像保存してURLを返す。
	//   初回以外：ALFLearningサーバの画像のURLを返す。
	//----------------------------------------------
	function get_video_thumbnail_url($video_id = 0 ){
		// パラメータ不足の場合は文字列ゼロを返す
		if( empty($video_id) ){
			return "";
		}
		
		// ビデオIDより詳細情報取得
		$this->load->model('model_video');
		$param = array(
					'video_id' => $video_id,
				);
		$video = $this->get_material($param);
		
		// サムネイルファイル保存先ディレクトリの存在確認
		$upload_dir = $this->config->item('video_dir').'/'.$video['video_id'];
		if(!is_dir($upload_dir)){
			mkdir($upload_dir, 0777, TRUE);
			chmod($upload_dir, 0777);
		}
		
		// サムネイル画像フルパス
		$thumbnail_image_path = $this->config->item('video_dir').'/'.$video['video_id'].'/thumbnail.jpg';

		// ファイルがある場合、次処理へ。ない場合は取得処理へ。
		if ( file_exists( $thumbnail_image_path )) {
			//echo "ファイルが存在する！！";
		} else {
			//echo "ファイルが存在しない！！";
			$this->load->library('Curl');
			$this->load->helper('json');

			$contract_param = obj2arr(json_decode($video['contract_param']));
			$auth_key       = $contract_param['alfstream']['auth_key'];
			$code           = $contract_param['alfstream']['code'];

			$stream_param = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/'.$video['idkey'].'', array(
				"authkey"	=> $auth_key,
			));
			if(!isset($stream_param)){
				$thumbnail_image_path = "";
			}
			
			$decodedContent = json_decode($stream_param);		// 取得値のJSON変換
			$posters        = $decodedContent->dat->posters;	// サムネイルURL群の取得
			if(empty($posters)){
				$thumbnail_image_path = "";
			}else{
				// サムネイル画像の保存
				$stream_thumbnail_data = file_get_contents($posters[0]->url);
				if( !write_file($thumbnail_image_path, $stream_thumbnail_data, 'w') ){
					$thumbnail_image_path = "";
				}else{
					chmod($thumbnail_image_path, 0777);
				}
			}
		}
		
		if($thumbnail_image_path == ""){
			return "";
		}else{
			return '/video_files/'.$video['video_id'].'/thumbnail.jpg';
		}
	}



	//----------------------------------------------
	// 履歴エクスポート、出力対象年月取得
	//----------------------------------------------
	function get_export_video_history_date($param = array()){
		//引数設定
		$param = array_merge(
						array(
							'student_id' => 0,
						),
						$param
					);
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$sql_where = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND video.video_id IN (
								SELECT video_lecture.video_id 
								  FROM video_lecture 
								 WHERE video_lecture.cource_id IN (".$cource_list.")
							) ";
		}
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		//$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND video_alfstream_reading_history.student_id IN (
								SELECT student_lecture.student_id 
								  FROM student_lecture 
								 WHERE student_lecture.cource_id IN (".$cource_list.")
							) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql_where .= " AND video_alfstream_reading_history.student_id IN (
								SELECT rel_student_group .student_id 
								  FROM rel_student_group  
								 WHERE rel_student_group .student_group_id IN (".$student_group_list.")
							) ";
		}
		
		// 対象学校IDの指定（SuperUserの場合、学校ID = 0を条件に追加）
		$target_school_id = $this->libauth->get_school_id();
		$login_teacher_id = $this->libauth->get_teacher_id();
		$work_auth        = $this->libauth->get_teacher_auth();
		if( getenv('URL_SERVICE') == 'mitemo' ){
			if( ($login_teacher_id < 0) || ($work_auth['school_admin'] == 1) ){
				$target_school_id = "0,".$target_school_id;
			}
		}else{
			if($login_teacher_id < 0){
				$target_school_id = "0,".$target_school_id;
			}
		}
		
		// 受講者ID指定ありの場合の対応
		$sql_where_student_id = "";
		if($param['student_id'] > 0){
			$sql_where_student_id .= " AND student.student_id = ".$param['student_id']." ";
		}
		
		//SQL生成
		$query = $this->db->query("
			SELECT  DATE_FORMAT(video_alfstream_reading_history.reading_date, '%Y-%m' ) AS reading_date 
			       ,count(video_alfstream_reading_history.student_id) AS count 
			  FROM  video_alfstream_reading_history 
			        LEFT JOIN video   ON video_alfstream_reading_history.video_id   = video.video_id 
			        LEFT JOIN student ON video_alfstream_reading_history.student_id = student.student_id 
			 WHERE  video.school_id   IN ({$target_school_id}) 
			   AND  student.school_id IN ({$target_school_id}) 
			".$sql_where." 
			".$sql_where_student_id." 
			 GROUP  BY DATE_FORMAT(video_alfstream_reading_history.reading_date, '%Y-%m') 
			 ORDER  BY DATE_FORMAT(video_alfstream_reading_history.reading_date, '%Y-%m') DESC 
		",array(
		));
		
		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

	//----------------------------------------------
	// 履歴エクスポート、出力対象取得
	//----------------------------------------------
	function get_export_video_history($param = array()){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
							'target_date' => '0000-00',
							'output_kind' => 0,
							'student_id'  => '',
						),
						$param
					);
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		$sql_where = '';
		$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND video.video_id IN (
								SELECT video_lecture.video_id 
								  FROM video_lecture 
								 WHERE video_lecture.cource_id IN (".$cource_list.")
							) ";
		}
		
		// ログイン中講師の講座確認
		// 講師かつ講師に講座が付加されている場合は条件追加
		$this->load->model('model_teacher');
		//$cource_list = $this->model_teacher->get_teacher_lecture_string();
		if($cource_list!=''){
			$sql_where .= " AND video_alfstream_reading_history.student_id IN (
								SELECT student_lecture.student_id 
								  FROM student_lecture 
								 WHERE student_lecture.cource_id IN (".$cource_list.")
							) ";
		}
		
		// ログイン中講師の受講者グループ確認
		// 講師かつ講師に受講者グループが付加されている場合は条件追加
		$student_group_list = $this->model_teacher->get_teacher_student_group_string();
		if($student_group_list!=''){
			$sql_where .= " AND video_alfstream_reading_history.student_id IN (
								SELECT rel_student_group .student_id 
								  FROM rel_student_group  
								 WHERE rel_student_group .student_group_id IN (".$student_group_list.")
							) ";
		}
		
		// 対象学校IDの指定（SuperUserの場合、学校ID = 0を条件に追加）
		$target_school_id = $param['school_id'];
		$login_teacher_id = $this->libauth->get_teacher_id();
		//if($login_teacher_id < 0){
		//	$target_school_id = "0,".$target_school_id;
		//}
		if(getenv('URL_SERVICE') == 'mitemo') {
			// mitemo 且つ SuperUser・学校管理者の場合
			$work_auth = $this->libauth->get_teacher_auth();
			if( ($login_teacher_id < 0) || ($work_auth['school_admin'] == 1) ){
				$target_school_id = "0,".$target_school_id;
			}
		}else{
			if(!$login_teacher_id){
				$target_school_id = "0,".$target_school_id;
			}elseif($login_teacher_id < 0){
				$target_school_id = "0,".$target_school_id;
			}
			
		//	// mitemo 以外 且つ SuperUser の場合
		//	if($login_teacher_id < 0){
		//		$target_school_id = "0,".$target_school_id;
		//	}
		}
		
		// 履歴出力対象の学校IDの指定（mitemo対応）
		$sql_where_school_id = " AND video.school_id IN (0, ".$target_school_id.")";
		//$sql_where_school_id = " AND video.school_id IN (0, ".$this->libauth->get_school_id().")";
		if($param['output_kind'] == 1){
			$sql_where_school_id = " AND video.school_id IN (0)";
		}elseif($param['output_kind'] == 2){
			$sql_where_school_id = " AND video.school_id IN (".$target_school_id.")";
		//$sql_where_school_id = " AND video.school_id IN (".$this->libauth->get_school_id().")";
		}
		
		// 受講者ID指定ありの場合の対応
		$sql_where_student_id = "";
		if($param['student_id'] != ''){
			$sql_where_student_id .= " AND student.student_id IN (".$param['student_id'].") ";
		}
		
		//SQL生成
		$query = $this->db->query("
			SELECT  video.school_id 
			       ,video.video_id 
			       ,video.video_logic_name 
			       ,student.student_id 
			       ,student.student_email 
			       ,student.student_name 
			       ,video_alfstream_reading_history.percent 
			       ,video_alfstream_reading_history.reading_date 
			  FROM  video_alfstream_reading_history 
			        LEFT JOIN video   ON video_alfstream_reading_history.video_id   = video.video_id 
			        LEFT JOIN student ON video_alfstream_reading_history.student_id = student.student_id 
			 WHERE  video_alfstream_reading_history.reading_date LIKE ? 
			   AND  video.school_id   IN ({$target_school_id}) 
			   AND  student.school_id IN ({$target_school_id}) 
			        ".$sql_where."
			        ".$sql_where_student_id."
			        ".$sql_where_school_id."
			  ORDER BY video_alfstream_reading_history.reading_date ASC 
			", array(
				$param['target_date']."%",
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

}

?>
