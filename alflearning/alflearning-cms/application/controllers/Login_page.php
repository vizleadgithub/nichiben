<?php
#[AllowDynamicProperties]
class Login_page extends CI_Controller {
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		//headerへブラウザのキャッシュ無効化設定
		$this->output->set_header ("Cache-Control: no-store, no-cache, must-revalidate" );
		$this->output->set_header ("Cache-Control: post-check=0, pre-check=0", false );
		
		//プロファイラ（TRUEでデバッグ）
		$this->output->enable_profiler(FALSE);
		
	}
	
	//----------------------------------------------
	//初期表示
	//----------------------------------------------
	function index() {
		$data['error_msg'] ='';
		$data['login_id'] = '';
		$data['password'] = '';
		
		//ビュー読み込み
		$this->load->view('login/login_page',$data);
	}
	
	//----------------------------------------------
	//ログイン
	//----------------------------------------------
	function login() {
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
	
		//バリデーションルール設定
		$this->form_validation->set_rules('login_id' , $this->lang->line_or_def('common_id','ID')               , 'trim|required');
		$this->form_validation->set_rules('password' , $this->lang->line_or_def('common_password','パスワード') , 'trim');
		if ( ! $this->libauth->is_logged_in()) {
			//未ログイン
			if ($this->form_validation->run() == FALSE) {
				//入力エラー
				$data['error_msg'] = '';
				$data['login_id'] = '';
				$data['password'] = '';
				
				//ビュー読み込み
				$this->load->view('login/login_page',$data);
			} else {
				$login_id = set_value('login_id');
				$password = set_value('password');
				$login_id = ($this->input->post('login_id') ?? '');
				$password = ($this->input->post('password') ?? '');

				// ここでロック解除を先に実施
				$lock_time_sec = $this->config->item('lock_time_sec');
				$sql = "UPDATE cms_login_fail
				        SET login_fail_count = 0
				        WHERE teacher_email = ?
				          AND last_fail_time < ?";
				$this->db->query($sql, [
					$login_id,
					date("Y-m-d H:i:s", time() - $lock_time_sec),
				]);

				// ロック中かチェック
				$lock_time_sec = $this->config->item('lock_time_sec');
				$lock_login_count = $this->config->item('lock_login_count');
				$sql = "";
				$sql.= "SELECT * FROM cms_login_fail WHERE 1=1 AND teacher_email=? AND login_fail_count>=".$lock_login_count." AND last_fail_time>='".date("Y-m-d H:i:s", strtotime("-".$lock_time_sec." seconds"))."'";
				$query = $this->db->query($sql, [
					$login_id,
				]);
				if ($query->num_rows() > 0) {
					$data['error_msg'] = $this->lang->line_or_def('error_login_lock','IDまたはパスワードが正しくありません。規定の回数を超えたためアカウントをロックしました。');
					$data['login_id'] = '';
					$data['password'] = '';
					
					//ビュー読み込み
					$this->load->view('login/login_page',$data);
				} else {
					//ログイン試行
					if ( $this->libauth->login($login_id, $password) ) {
						//ログイン成功！
						$sql = "";
						$sql.= "UPDATE cms_login_fail SET ";
						$sql.= " login_fail_count = ? ";
						$sql.= ",last_fail_time = ? ";
						$sql.= "WHERE teacher_email=? ";
						$query = $this->db->query($sql, [
							0,
							date("Y-m-d H:i:s"),
							$login_id,
						]);
						
						// 管理画面-ログインのログ出力
						$this->_cms_insert_log($this->libauth->get_school_id(), $this->libauth->get_teacher_id(), 'Login Result', 'OK', '管理画面-ログイン');
						
						$this->_redirect_page();
					}else{
						// 現在の失敗回数を取得
						$sql = "SELECT login_fail_count FROM cms_login_fail WHERE teacher_email = ?";
						$query = $this->db->query($sql, [$login_id]);
						$current_count = 0;
						if ($query->num_rows() > 0) {
							$row = $query->row();
							$current_count = (int) $row->login_fail_count;
						}

						// login_fail_count を +1 する（DB更新）
						$sql = "";
						$sql.= "INSERT INTO cms_login_fail (teacher_email, login_fail_count, last_fail_time) ";
						$sql.= "VALUES (?, ?, ?) ";
						$sql.= "ON DUPLICATE KEY UPDATE ";
						$sql.= "login_fail_count = login_fail_count + 1, ";
						$sql.= "last_fail_time = ? ";
						$this->db->query($sql, [
							$login_id,
							1,
							date("Y-m-d H:i:s"),
							date("Y-m-d H:i:s"),
						]);

						// 次でロック対象になるかを判定（例：5回目の失敗）
						$next_count = $current_count + 1;
						if ($next_count >= $lock_login_count) {
							$data['error_msg'] = $this->lang->line_or_def(
								'error_login_locked_now',
								'IDまたはパスワードが正しくありません。規定の回数を超えたためアカウントをロックしました。'
							);
						} else {
							$data['error_msg'] = $this->lang->line_or_def(
								'error_login_nologin2',
								'IDまたはパスワードが正しくありません。規定の回数を間違えるとアカウントがロックされます。'
							);
						}

						$data['login_id'] = '';
						$data['password'] = '';

						//ビュー読み込み
						$this->load->view('login/login_page',$data);
					}
				}
			}
		} else {
			session_regenerate_id(true);
			//ログイン済み
			$this->_redirect_page();
		}
	}
	
	//----------------------------------------------
	//ログアウト
	//----------------------------------------------
	function logout() {
		$school_id  = $this->libauth->get_school_id();
		$teacher_id = $this->libauth->get_teacher_id();
		
		//ログアウト
		$this->libauth->logout();
		
		// 管理画面-ログアウトのログ出力
		$this->_cms_insert_log($school_id, $teacher_id, 'Logout', 'OK', '管理画面-ログアウト');
		
		redirect('login_page');
	}
	
	//----------------------------------------------
	//リダイレクト処理
	//----------------------------------------------
	function _redirect_page() {
		if ( $this->libauth->get_email() == $this->config->item('admin_email') && $this->libauth->get_school_id() == 0 ){
			//管理者で学校ID未設定の場合は学校選択へ
			redirect('school_select');
		} else if($this->input->get('backurl')) {
			//redirect($this->input->get('backurl'));
			// 自サイト内のURLかチェック
			if ($this->_is_valid_backurl($backurl)) {
				redirect($backurl);
			} else {
				// 不正な場合はデフォルトページへ
				redirect('admin_top');
			}
		} else {
			//上記以外は管理者トップ画面へ
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	// アプリログ出力
	// ・ログイン、ログアウト
	//----------------------------------------------
	function _cms_insert_log($school_id, $teacher_id, $log_name, $log_value, $log_option = '管理画面'){
		//アプリログ出力
		$this->load->model('model_applog');
		$clientIP = "";
		if($_SERVER["HTTP_X_FORWARDED_FOR"]){
			$arr_HTTP_X_FORWARDED_FOR = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
			$_SERVER['HTTP_X_FORWARDED_FOR'] = $arr_HTTP_X_FORWARDED_FOR[0];
			$clientIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if($_SERVER["REMOTE_ADDR"]){
			$clientIP = $_SERVER["REMOTE_ADDR"];
		}
		$param = array(
						'school_id'   => $this->libauth->get_school_id() ,   // 学校ID
						'teacher_id'  => $this->libauth->get_teacher_id() ,  // ログイン講師ID
					//	'student_id'  => 0 ,                                 // 受講者ID
						'log_name'    => $log_name ,                // ログ名称
						'log_value'   => $log_value ,                    // ログ内容
						'log_option'  => $log_option ,                       // ログ予備
						'remote_addr' => $clientIP ,           // リモート側IPアドレス
						'user_agent'  => $_SERVER["HTTP_USER_AGENT"] ,       // USER AGENT
						'server_name' => $_SERVER["SERVER_NAME"] ,           // サーバーホスト名
						'php_self'    => $_SERVER["PHP_SELF"] ,              // スクリプトファイル名
					);
		$value_data = $this->model_applog->insert_log($param);
		
		return $value_data;
	}



	// backurlの妥当性を確認する関数
	private function _is_valid_backurl($url) {
		// / で始まる場合（自サイト内へのリダイレクト許可）
		if (preg_match('#^/.*#', $url)) {
			return true;
		}

		// 自サイトのURLの場合（http/https対応）
		$base_url = parse_url(base_url(), PHP_URL_HOST); // 自サイトのホスト名を取得
		$parsed_url = parse_url($url);

		if (isset($parsed_url['host']) && $parsed_url['host'] === $base_url) {
			return true;
		}

		// それ以外は不正なURL
		return false;
	}



} 

/*End of File program.php*/
