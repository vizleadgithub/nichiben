<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bat_get_alfstream_status extends CI_Controller {

	//----------------------------------------------
	// [2012/09/26]コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// [2012/09/26]メイン処理
	//----------------------------------------------
	public function index(){
	}


	//----------------------------------------------
	// [2012/09/26]ALF Stream側ビデオステータス取得
	// 引数に「1」等を付加して実行すると、日時条件無視
	// [2012/11/15]SQL文修正
	//----------------------------------------------
	public function get_alfstream_status($allCheckFlag = 0){
		exec('ps auxw | grep get_alfstream_status | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			exit();
			return;
		}

		print "[".date('Y-m-d H:i:s')."]"."start\n";

		// システム日時の30分前の取得
		$border_date = date('Y-m-d H:i:s', strtotime("-60 minute"));

		// videoにありvideo_alfstream_statusにないvideo_idを取得
		// [2012/11/15]上記条件に取得済み alfstream_status を追加。
		$sql_video  = '';
		$sql_video .= ' SELECT video.video_id, school.contract_param, video.idkey, video.update_at , video_alfstream_status.alfstream_status ';
		$sql_video .= '   FROM (video INNER JOIN school ON video.school_id = school.school_id) ';
		$sql_video .= '         LEFT JOIN video_alfstream_status ON video.video_id = video_alfstream_status.video_id ';
		$sql_video .= '  WHERE video.status = 0 ';
		$sql_video .= "    AND (    (video_alfstream_status.alfstream_status =    'NEW') ";
		$sql_video .= "          OR (video_alfstream_status.alfstream_status =    'Not Found') ";
		$sql_video .= "          OR (video_alfstream_status.alfstream_status LIKE '%FAILED%') ";
		$sql_video .= "          OR (video_alfstream_status.alfstream_status LIKE 'HTTP Status Code%') ";
		$sql_video .= '          OR (video_alfstream_status.alfstream_status IS    NULL) ';
		$sql_video .= '        ) ';
	//	$sql_video .= ' SELECT video.video_id, school.contract_param, video.idkey, video.update_at ';
	//	$sql_video .= '   FROM video INNER JOIN school ON video.school_id = school.school_id ';
	//	$sql_video .= '  WHERE video.status = 0 ';
	//	$sql_video .= '    AND NOT EXISTS (SELECT video_id FROM video_alfstream_status WHERE video_id = video.video_id) ';
		if($allCheckFlag === 0){
			$sql_video .= '   AND video.update_at >= "'.$border_date.'" ';
		}
		$query_video = $this->db->query(
			$sql_video,
			array()
		);

		// video.video_id のループ
		foreach($query_video->result_array() as $video){
			// ALF Stream 側のVideo Statusを取得
			$alfStream_Status = $this->_getAlfStreamStatus($video);

			// video_alfstream_status.alfstream_status =  NULL ⇒ INSERT video_alfstream_status
			// video_alfstream_status.alfstream_status != NULL ⇒ UPDATE video_alfstream_status
			if( empty($video['alfstream_status']) ) {
				$res = $this->db->query($this->db->insert_string('video_alfstream_status', array(
						'video_id'           => $video['video_id'],
						'alfstream_status'   => $alfStream_Status['stat'],
						'alfstream_duration' => $alfStream_Status['duration'],
						'parent_idkey'       => $alfStream_Status['parent_idkey'] ,
						'update_at'          => date('Y-m-d H:i:s'),
					)));
				print "[".date('Y-m-d H:i:s')."]"."INSERT [video_id / ".$video['video_id']."] [AlfStreamStatus / ".$alfStream_Status['stat']."]\n";
			}else{
				$res = $this->db->query($this->db->update_string('video_alfstream_status', array(
							'alfstream_status'   => $alfStream_Status['stat'],
							'alfstream_duration' => $alfStream_Status['duration'],
							'parent_idkey'       => $alfStream_Status['parent_idkey'] ,
							'update_at'          => date('Y/m/d H:i:s'),
					),'video_id='.$video['video_id']
				));
				print "[".date('Y/m/d H:i:s')."]"."UPDATE [video_id / ".$video['video_id']."] [AlfStreamStatus / ".$alfStream_Status['stat']."]\n";
			}
		//	// video.video_id・ALF Stream Status をvideo_alfstream_statusへ新規登録（ALF Stream Status == NEW は除く）
		//	if( strcmp($alfStream_Status, 'NEW') != 0 ) {
		//		$res = $this->db->query($this->db->insert_string('video_alfstream_status', array(
		//				'video_id'          => $video['video_id'],
		//				'alfstream_status'  => $alfStream_Status['stat'],
		//				'update_at'         => date('Y-m-d H:i:s'),
		//			)));
		//	print "[".date('Y-m-d H:i:s')."]"."INSERT [video_id / ".$video['video_id']."] [AlfStreamStatus / ".$alfStream_Status['stat']."]\n";
		//	}
		}


		// video_alfstream_status のalfstream_statusが「PROCESSING%」「UPLOADING%」に一致するものを取得
		// その際、school.contract_param と video.idkey をあわせて取得
		$sql_status  = '';
		$sql_status .= 'SELECT video_alfstream_status.video_id, school.contract_param, video.idkey ';
		$sql_status .= '  FROM (video_alfstream_status INNER JOIN video ON video_alfstream_status.video_id = video.video_id) ';
		$sql_status .= '        INNER JOIN school ON video.school_id = school.school_id ';
		$sql_status .= " WHERE video_alfstream_status.alfstream_status LIKE 'PROCESSING%' ";
		$sql_status .= "    OR video_alfstream_status.alfstream_status LIKE 'UPLOADING%' ";
		$query_status = $this->db->query(
			$sql_status,
			array()
		);
		//video_alfstream_statusのループ
		foreach($query_status->result_array() as $status){
			// ALF Stream 側のVideo Statusを取得
			$alfStream_Status = $this->_getAlfStreamStatus($status);

			// Update video_alfstream_status
			$res = $this->db->query($this->db->update_string('video_alfstream_status', array(
						'alfstream_status'   => $alfStream_Status['stat'] ,
						'alfstream_duration' => $alfStream_Status['duration'] ?? '00:00:00',
						'parent_idkey'       => $alfStream_Status['parent_idkey'] ?? null,
						'update_at'          => date('Y/m/d H:i:s'),
				),'video_id='.$status['video_id']
			));
			print "[".date('Y/m/d H:i:s')."]"."UPDATE [video_id / ".$status['video_id']."] [AlfStreamStatus / ".$alfStream_Status['stat'] ."]\n";
		}

		print "[".date('Y-m-d H:i:s')."]"."end\n";
	}

	//----------------------------------------------
	// [2012/09/26]
	// 1.school.contract_param から auth_key 取得
	// 2.ALF Stream API にて、video_statusを取得
	// 3.ALF Stream Statusの編集
	//----------------------------------------------
	private function _getAlfStreamStatus($param){
		$param = array_merge(array(
			'video_id'		=> '',
			'contract_param'	=> '',
			'idkey'			=> '',
		), $param);

		$this->load->library('Curl');
		$this->load->helper('json');
		
		// 1.school.contract_param から auth_key 取得
		$contract_param = obj2arr(json_decode($param['contract_param']));
		$auth_key       = $contract_param['alfstream']['auth_key'];

		// 2.ALF Stream API にて、video_statusを取得
		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/', array(
			"authkey"	=> $auth_key,
			'q'		=> "idkey:{$param['idkey']}",
			'page'		=> '',
			'per_page'	=> '',
		));
		$decodedContent = json_decode($content);
//print("[idkey:".$param['idkey']."]\n");
//print("----------------\n");
//var_dump($decodedContent);
//print("\n");
//print("----------------\n");
		// 3.ALF Stream Statusの編集
		$alfStream_Status = array();
		if( $decodedContent->stat == 200 ){
			if( isset($decodedContent->dat->items) && is_array($decodedContent->dat->items) && count($decodedContent->dat->items)>0 ){
				$alfStream_Status['stat']         = $decodedContent->dat->items[0]->stat;
				$alfStream_Status['duration']     = $decodedContent->dat->items[0]->duration;
				$alfStream_Status['parent_idkey'] = $decodedContent->dat->items[0]->parent_idkey;
				if( mb_strlen(trim($alfStream_Status['stat'])) == 0 ){
					$alfStream_Status['stat'] = "Not Found";	// ステータス見つからない場合
				}
			} else {
				$alfStream_Status['stat'] = "Not Found";	// ステータス見つからない場合
			}
		}else{
			$alfStream_Status['stat'] = "HTTP Status Code:".$alfStreamStatus->stat." / ".$decodedContent->dat->items[0]->stat;
			$alfStream_Status['parent_idkey'] = '';
		}

		// 正常登録終了・異常登録終了の通知
		$notice_judge = '';
		if( preg_match('/^ONLINE$/'             , $alfStream_Status['stat']) ) $notice_judge = 'OK';
		if( preg_match('/^.*FAILED.*$/'         , $alfStream_Status['stat']) ) $notice_judge = 'NG';
		if( preg_match('/^HTTP Status Code.*$/' , $alfStream_Status['stat']) ) $notice_judge = 'NG';
		if( preg_match('/^Not Found$/'          , $alfStream_Status['stat']) ) $notice_judge = 'NG';

		if($notice_judge != ''){
			$this->load->model('model_notification');
			$notice_result = $this->model_notification->insert_notification(array(
				'notice_kind'  => 'cms-video',
				'id'           => $param['video_id'],
				'notice_judge' => $notice_judge,
			));
		}
		
		return $alfStream_Status;
	}

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}
