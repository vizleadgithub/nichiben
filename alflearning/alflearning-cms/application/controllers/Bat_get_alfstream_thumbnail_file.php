<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Bat_get_alfstream_thumbnail_file extends CI_Controller {

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// ALF Stream側サムネイル取得処理
	// 引数例：
	//  "設定なし"：ステータス「ONLINE」且つ「サムネイル名」がNULLのものを処理
	//  "1"       ：ステータス「ONLINE」のものを処理（全件再取得）
	//  "1 2"     ：ステータス「ONLINE」且つ「ビデオID = 2」のものを再処理
	//  
	//  使用config  $config['video_thumbnail_dir']    = サムネイル格納ディレクトリ
	//              $config['video_thumbnail_number'] = サムネイル作成数
	//              $config['video_thumbnail_make_interval'] = ALFStream にPoster作成させるための待ち時間（秒）
	
	// 日弁連対応：サムネイルは各プロセッサで開始5秒の画像１つのみ。＋ 動画登録時、開始5秒のPosterが作成されるので、画像変換APIは不要（時間が違う場合は必要）
	
	//----------------------------------------------
	public function get_alfstream_thumbnail_file($allCheckFlag = 0, $selected_video_id = 0){
		exec('ps auxw | grep get_alfstream_thumbnail_file | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		print "[".date('Y-m-d H:i:s')."]"."START 'get_alfstream_thumbnail_file'\n";
		
		// videoテーブルより、有効ビデオの情報を取得（ビデオが削除されていない、ステータスが「ONLINE」、サムネイルファイル名がNULL値）
		$sql_video  = ' SELECT  video.video_id ';
		$sql_video .= '        ,school.contract_param ';
		$sql_video .= '        ,video.idkey ';
		$sql_video .= '        ,video.update_at ';
		$sql_video .= '        ,video_alfstream_status.alfstream_status ';
		$sql_video .= '   FROM (video INNER JOIN school ON video.school_id = school.school_id) ';
		$sql_video .= '         LEFT JOIN video_alfstream_status ON video.video_id = video_alfstream_status.video_id ';
		$sql_video .= '  WHERE  video.status = 0 ';
		$sql_video .= "    AND  video_alfstream_status.alfstream_status = 'ONLINE' ";
		if($allCheckFlag == 0){
			$sql_video .= '    AND  video_alfstream_status.alfstream_thumbnail IS NULL ';
		}
		if($selected_video_id > 0){
			$sql_video .= "    AND  video.video_id = {$this->db->escape($selected_video_id)} ";
		}
		
		$query_video = $this->db->query(
			$sql_video,
			array()
		);
		
		// サムネイル保管ディレクトリの確認及び作成（0777 / apache:apache : /alflearning-data/video_thumbnail）
		$video_thumbnail_dir = $this->config->item('video_thumbnail_dir');
		if(!is_dir($video_thumbnail_dir)){
			mkdir($video_thumbnail_dir, 0777, TRUE);
			chmod($video_thumbnail_dir, 0777);
		}
		
		$this->load->library('Curl');
		$this->load->helper('json');
		$this->load->helper('download');
		$this->load->helper('file');
		
		// 取得したビデオテーブルのレコード のループ
		foreach($query_video->result_array() as $video){
			
			print "[".date('Y-m-d H:i:s')."]"."video_id : ".$video['video_id']." is START\n";
			
			// 1.school.contract_param から auth_key 取得
			$contract_param = obj2arr(json_decode($video['contract_param']));
			$auth_key       = $contract_param['alfstream']['auth_key'];
			$code           = $contract_param['alfstream']['code'];
			

			// 2.ALF Stream API を使用してステータス取得
			$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/', array(
				"authkey"			=> $auth_key,
				'q'					=> "idkey:{$video['idkey']}",
				'page'				=> '',
				'per_page'			=> '',
			));
			
			// 3.取得したステータスから再生時間の取得
			$decodedContent     = json_decode($content);
			$alfstream_duration = $decodedContent->dat->items[0]->duration;	//00:00:28
			
			// 4.サムネイル取得時間の設定（取得時間設定失敗の場合は、次処理へ）
/*
			$thumbnail_position = $this->_get_standard_num(array(
												'alfstream_duration' => $alfstream_duration,
											));
			if(!$thumbnail_position){
				print "[".date('Y-m-d H:i:s')."]"."video_id : ".$video['video_id']." ERROR : Not get alfstream duration\n";
				continue;
			}
*/

$thumbnail_position[0] = '5.00';



			
			// サムネイルファイル保存先ディレクトリの存在確認
			$upload_dir = $video_thumbnail_dir.'/'.$video['video_id'];
			if(!is_dir($upload_dir)){
				mkdir($upload_dir, 0777, TRUE);
				chmod($upload_dir, 0777);
			}else{
				// 存在した場合、ディレクトリ内ファイルを全削除
				delete_files($upload_dir);
			}
			
			// 5.取得サムネイル取得時間分のループ
			$thumbnail_files   = array();	// サムネイル名格納配列
			//$thumbnail_counter = -1;		// サムネイルファイル名に使用（サムネイル種類数）
			$thumbnail_counter = $this->config->item('video_thumbnail_number');	// 例 : 4
			foreach ($thumbnail_position as $position) {
				$thumbnail_counter = $thumbnail_counter - 1;	// 例 : 3 2 1 0
				
/*
				// サムネイル（stream側：Poster）画像変更API
				$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$video['idkey'].'/poster', array(
					'authkey'			=> $auth_key,
					'code'				=> $code,
					'position'			=> $position,
				));
				//print var_dump($http_response_header);
*/
				print "[".date('Y-m-d H:i:s')."]"." > position = ".$position."\n";
				
				$request_timestamp = time();		// サムネイル画像変換API実行時間を取得
				$posters = array();					// サムネイルURL格納変数

				$date_check = false;
				$counter = 1;
				while($date_check == false){
					// NGが連続続いた場合、再度サムネイル画像変換APIを実行。カウンターをリセット。
					if($counter > 5){
						$counter = 1;
/*
						// サムネイル（stream側：Poster）画像変更API
						$content = $this->curl->simple_put('http://'.$this->config->item('video_stream_api_domain_name').'/v1/media/'.$video['idkey'].'/poster', array(
							'authkey'			=> $auth_key,
							'code'				=> $code,
							'position'			=> $position,
						));
						//print var_dump($http_response_header);
*/
						print "[".date('Y-m-d H:i:s')."]"." > position = ".$position."\n";
						
						//$request_timestamp = time();		// サムネイル画像変換API実行時間を取得
					}
					print "[".date('Y-m-d H:i:s')."]"."        <counter = ".$counter.">\n";
					
					// サムネイル画像変換API処理待ちinterval
/*
				//	sleep(10);
					$interval_data = $this->config->item('video_thumbnail_make_interval');
					if( ($interval_data < 1) || ($interval_data > 600) ){
						$interval_data = 10;
					}
					sleep($interval_data);
*/
					
					// サムネイルURL取得API
					$content = NULL;
					$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/asset/'.$video['idkey'].'', array(
						"authkey"	=> $auth_key,
					));
					$decodedContent = json_decode($content);			// 取得値のJSON変換
					$posters        = $decodedContent->dat->posters;	// サムネイルURL群の取得
					
					$date_check = true;
/*
					foreach($posters as $_poster){
						// ALFStreamから画像ファイルの情報取得（headerの情報）
						//	Array
						//	(
						//		[0] => HTTP/1.1 200 OK
						//		[1] => Date: Tue, 09 Apr 2013 01:30:29 GMT
						//		[2] => Server: Apache
						//		[3] => Last-Modified: Tue, 09 Apr 2013 01:30:18 GMT
						//		[4] => ETag: "6ef41650-3876-4d9e37f01135c"
						//		[5] => Accept-Ranges: bytes
						//		[6] => Content-Length: 14454
						//		[7] => Connection: close
						//		[8] => Content-Type: image/jpeg
						//	)
						$data        = file_get_contents($_poster->url);
						$header_data = $http_response_header;
						
						// header にあるLast-Modified から日時を取得（例＞Tue, 09 Apr 2013 02:08:14 GMT）
						$file_timestamp        = strtotime( str_replace("Last-Modified:", "", $header_data[3]) );
						
						// リクエスト時間とファイル時間を比較。リクエスト時間のほうが新しい場合、エラーとする
						if($request_timestamp > $file_timestamp){
							print "[".date('Y-m-d H:i:s')."]"."   > NG [request time:".date("Y-m-d H:i:s", $request_timestamp)." < Last-Modified:".date("Y-m-d H:i:s", $file_timestamp)."]"."\n";
							print "[".date('Y-m-d H:i:s')."]"."        ".$_poster->url."\n";
							$date_check = false;
							$counter = $counter + 1;
							break;
						}else{
							print "[".date('Y-m-d H:i:s')."]"."   > OK [request time:".date("Y-m-d H:i:s", $request_timestamp)." < Last-Modified:".date("Y-m-d H:i:s", $file_timestamp)."]"."\n";
							print "[".date('Y-m-d H:i:s')."]"."        ".$_poster->url."\n";
						}
					}
*/
				}
				
				// サムネイルURL分のループ
				foreach($posters as $_poster){
					// ダウンロード元ファイル名取得（ファイル名は半角のみのため、バイト数にて計算）
					$path_parts = pathinfo($_poster->url);
					$basename   = $path_parts['basename'];	// 例：180p.jpg
					$extension  = $path_parts['extension'];	// 例：jpg
					$file_name  = substr($basename, 0, (strlen($basename) - (strlen($extension) + 1)) );	// 例：180p
					
					// 保存先ファイル名（絶対パス）の設定
					$filename = $upload_dir.'/thumb_'.$video['video_id'].'_'.$thumbnail_counter.'_'.$basename;
					
					// ALFStreamから画像ファイルの情報取得
					$data = file_get_contents($_poster->url);
					
					// ファイルの保存（同名ファイルがある場合、上書き保存）
					if( !write_file($filename, $data,'w') ){
					//	$get_thumbnail_flag = false;
						print "[".date('Y-m-d H:i:s')."]"."ERROR : Not get thumbnail file.\n";
					}else{
						chmod($filename, 0777);
						
						// ファイル名格納
						$thumbnail_files[$thumbnail_counter][$file_name] = 'thumb_'.$video['video_id'].'_'.$thumbnail_counter.'_'.$basename;
					}
				}
			}
		//	print var_dump($thumbnail_files);
			
			// キーによる昇順（サムネイルを時間降順で取得しているため、ファイル名も降順になっている。よって、キーにより昇順を行う）
			ksort($thumbnail_files);
			
			// ファイル名のJSON変換
			$thumbnail_files_json = json_encode($thumbnail_files);
		//	print $thumbnail_files_json;
			
			// ファイル名のテーブル登録
			if( strlen($thumbnail_files_json) > 0 ){
				$res = $this->db->query($this->db->update_string('video_alfstream_status', array(
						'alfstream_duration'	=> $alfstream_duration,
						'alfstream_thumbnail'	=> $thumbnail_files_json,
						'update_at'				=> date('Y-m-d H:i:s'),
					),'video_id='.$video['video_id']
				));
			}
			print "[".date('Y-m-d H:i:s')."]"."video_id : ".$video['video_id']." is END\n";
		}

		print "[".date('Y-m-d H:i:s')."]"."END 'get_alfstream_thumbnail_file'\n";
	}

	//----------------------------------------------
	// サムネイル取得時間の生成
	//   動画時間・サムネイル数を元に、取得対象サムネイル時間を作成
	//   [20130408]サムネイル時間を昇順から降順に切り替え
	//----------------------------------------------
	function _get_standard_num($param){
		
		//引数設定
		$param = array_merge(
						array(
							'alfstream_duration' => '00:00:00',
						),
						$param
					);
		
		// 再生時間を秒に変換
		$tArry = explode(":", $param['alfstream_duration']);
		$hour  = $tArry[0] * 60 * 60;			// 時 -> 秒
		$secnd = $tArry[1] * 60;				// 分 -> 秒
		$mins  = $hour + $secnd + $tArry[2];	// 時 + 分 + 秒
		if($mins == 0) 	return null;
		
		// 分割基準となる数字の取得
		$video_thumbnail_number = $this->config->item('video_thumbnail_number');	// 例 : 4
		$standard_num           = round($mins / ($video_thumbnail_number + 1));		// 例 : 56秒÷(4 + 1) = 11.2 ⇒ 11（四捨五入の整数値）
	//	print "alfstream_duration[".$param['alfstream_duration']."]\n";
	//	print "              mins[".$mins."]\n";
	//	print "      standard_num[".$standard_num."]\n\n";
		
		// 分割基準となる数字を元に、取得対象サムネイル時間（秒）を取得
		$result_data = array();
		for($i = $video_thumbnail_number; $i > 0; $i--) {
			$result_data[] = sprintf( '%.2f', $standard_num * $i );	// 例 : 44.00 33.00 22.00 11.00
		}
		//$result_data = array();
		//for($i = 0; $i < $video_thumbnail_number; $i++) {
		//	$result_data[] = sprintf( '%.2f', $standard_num * ($i + 1) );
		//}
		
	//	print var_dump($result_data)."\n\n";
		return $result_data;
	}

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}
