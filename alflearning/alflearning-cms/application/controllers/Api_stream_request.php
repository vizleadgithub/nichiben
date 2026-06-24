<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Api_stream_request extends CI_Controller {

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

		$return_flag  = -1;
		$now_time     = time();
		$userdata = '';

		# 引数のtoken（session_id）の取得
		$token    = 'no_data';
		$contents = 'no_data';
		if(isset($_GET['token'])) {
			$token = $_GET['token'];
		}
		if(isset($_GET['contents'])) {
			$contents = $_GET['contents'];
		}
//		print "    token = $token<br/>";
//		print "contents = $contents<br/>----------<br/>";

		$this->load->driver('cache');
		if($this->cache->memcached->get($token)){
			# memcashed 取得
			$userdata = $this->cache->memcached->get($token);
//			print date("Y/m/d l H:i:s")."<br/>";
//			print "memcashed = $userdata<br/><br/>";
		}
		else{
			# テーブル取得
			try{ 
				# 引数のtokenをキーにci_sessions からuserdata を取得
				# 条件：last_active + 600（10分*60秒）が現在日時より大きいこと
				$param = array(
							'session_id'   => $token,
							'now_time'     => $now_time,
						 );
				
				$sql  = '';
				$sql .= 'SELECT * ';
				$sql .= '  FROM ci_sessions ';
				$sql .= ' WHERE session_id = ? ';
				$sql .= '   AND (last_activity + 72000) >= ? ';	//session->sess_time_to_updateの設定に依存する
				
				# Query実行
				$query = $this->db->query($sql, array(
						$param['session_id'],
						$param['now_time'],
					)); 
				
				# userdata の確認
				if ($query->num_rows() > 0){
					$records  = $query->row_array();
					$userdata = $records['user_data'];
				}else{
//					print "---database not found<br/>";
					$userdata = '';
				}
			}catch(Exception $e){ 
				$userdata = '';
			//	throw new Exception();
			}
			
			// save memcached（秒数指定なし = 60）
			if(mb_strlen($userdata)>0){
				$this->cache->memcached->save($token, $userdata, 600);
//				print date("Y/m/d l H:i:s")."<br/>";
//				print "---database = $userdata<br/>";
//				print "--memcashed = ".$this->cache->memcached->get($token)."<br/><br/>";
			}
		}

		# 管理画面・マイページ画面でのログイン・権限確認
		if(mb_strlen($userdata)>0){
			$un_userdata = unserialize($userdata);
//			print "=logged_in = ".$un_userdata['cms_master.login.logged_in']."<br/>";
//			print "=school_id = ".$un_userdata['cms_master.login.school_id']."<br/>";
//			print "=teacher_auth.book_library = ".$un_userdata['cms_master.login.teacher_auth']['book_library']."<br/>";
			
			$log_school_id;
			
			$flag_count_cms = 0;

			# ログイン確認
			if(isset($un_userdata['cms_master.login.logged_in'])){
				$flag_count_cms += 1;
			}

			# 学校確認
			if(isset($un_userdata['cms_master.login.school_id'])){
				$log_school_id = intval($un_userdata['cms_master.login.school_id']);
				$pattern = '/^school_'.$un_userdata['cms_master.login.school_id'].'.*$/';
				if( preg_match($pattern, $contents) > 0 ){
					$flag_count_cms += 1;
				}
			}

			# 図書室権限確認
			if(isset($un_userdata['cms_master.login.teacher_auth']['book_library'])){
				if($un_userdata['cms_master.login.teacher_auth']['book_library'] == 1){
					$flag_count_cms += 1;
				}
			}

			$flag_count_follower = 0;

			# ログイン確認
			if(isset($un_userdata['loggedIn'])){
				$flag_count_follower += 1;
			}

			# 学校確認
			if(isset($un_userdata['school_id'])){
				$log_school_id = intval($un_userdata['school_id']);
				$pattern = '/^school_'.$un_userdata['school_id'].'.*$/';
				if( preg_match($pattern, $contents) > 0 ){
					$flag_count_follower += 1;
				}
			}

//			print $flag_count;
			if( ($flag_count_cms == 3) || ($flag_count_follower == 2) ){
				# ログイン状態
				$return_flag = 1;
			}else{
				# ログオフ状態
				$return_flag = -1;
			}
		}else{
			$return_flag = -1;
		}

error_log(print_r($_GET, true));

		//アプリログ出力
		$result_kickback = 'NG';
		if($return_flag > 0){
			$result_kickback = 'OK';
		}
		$this->load->model('model_applog');
		$param = array(
						'school_id'   => $log_school_id,  // 学校ID
					//	'teacher_id'  => 0 ,  // 講師ID
					//	'student_id'  => $this->session->userdata['student_id'] ,  // 受講者ID
						'log_name'    => 'AlfStream kickBack',  // ログ名称
						'log_value'   => $result_kickback,  // ログ内容
						'log_option'  => 'to AlfStream Request',  // ログ予備
						'remote_addr' => $_SERVER["REMOTE_ADDR"],  // リモート側IPアドレス
						'user_agent'  => $_SERVER["HTTP_USER_AGENT"],  // USER AGENT
						'server_name' => $_SERVER["SERVER_NAME"],  // サーバーホスト名
						'php_self'    => $_SERVER["PHP_SELF"],  // スクリプトファイル名
					);
		$value_data = $this->model_applog->insert_log($param);


		# ログイン中の場合のみ、200 OKを返す
		if($return_flag > 0){
			$this->output->set_header("HTTP/1.0 200 OK");
			echo "OK";
		}else{
			$this->output->set_header("HTTP/1.0 404 Not Found");
			echo "NG";
		}
	}
}


