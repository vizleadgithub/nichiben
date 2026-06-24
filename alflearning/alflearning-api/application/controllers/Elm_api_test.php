<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Elm_api_test extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	
	private $_access_url = 'http://demo29.gingerapp.co.jp/eLM/api/';
	private	$_api_key    = 'b9jcW$qs0F';
	
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		$outdata  = '<h2>eLearning Manager API TEST</h2>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.4.1 コース削除</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_course_delete/" method="post">';
		$outdata .= '  api_key&nbsp;<input style="width:200px;" type="text" name="api_key"   value='.$this->_api_key.'><br/><br/>';
		$outdata .= 'course_id&nbsp;<input style="width:600px;" type="text" name="course_id" value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="コース削除" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.4.2 コース登録</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_course_insert/" method="post">';
		$outdata .= '     api_key&nbsp;<input style="width:200px;" type="text" name="api_key"      value='.$this->_api_key.'><br/><br/>';
		$outdata .= '   course_id&nbsp;<input style="width:600px;" type="text" name="course_id"    value=""><br/><br/>';
		$outdata .= ' course_name&nbsp;<input style="width:600px;" type="text" name="course_name"  value=""><br/><br/>';
		$outdata .= 'course_guide&nbsp;<input style="width:600px;" type="text" name="course_guide" value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="コース登録" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.4.3 コース更新</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_course_update/" method="post">';
		$outdata .= '     api_key&nbsp;<input style="width:200px;" type="text" name="api_key"      value='.$this->_api_key.'><br/><br/>';
		$outdata .= '   course_id&nbsp;<input style="width:600px;" type="text" name="course_id"    value=""><br/><br/>';
		$outdata .= ' course_name&nbsp;<input style="width:600px;" type="text" name="course_name"  value=""><br/><br/>';
		$outdata .= 'course_guide&nbsp;<input style="width:600px;" type="text" name="course_guide" value=""><br/><br/>';
		$outdata .= '      appear&nbsp;<input style="width:100px;" type="text" name="appear"       value="">&nbsp;※0:非公開、1:公開<br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="コース更新" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.5.1 ユーザ削除</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_user_delete/" method="post">';
		$outdata .= 'api_key&nbsp;<input style="width:200px;" type="text" name="api_key"  value='.$this->_api_key.'><br/><br/>';
		$outdata .= '  logon&nbsp;<input style="width:600px;" type="text" name="logon"    value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="ユーザ削除" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.5.2 ユーザ登録</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_user_insert/" method="post">';
		$outdata .= '   api_key&nbsp;<input style="width:200px;"t type="text" name="api_key"     value='.$this->_api_key.'><br/><br/>';
		$outdata .= '     logon&nbsp;<input style="width:600px;" type="text" name="logon"       value=""><br/><br/>';
		$outdata .= '  password&nbsp;<input style="width:600px;" type="text" name="password"    value=""><br/><br/>';
		$outdata .= ' last_name&nbsp;<input style="width:600px;" type="text" name="last_name"   value=""><br/><br/>';
		$outdata .= 'first_name&nbsp;<input style="width:600px;" type="text" name="first_name"  value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="ユーザ登録" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.5.3 ユーザ更新</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_user_update/" method="post">';
		$outdata .= '    api_key&nbsp;<input style="width:200px;" type="text" name="api_key"      value='.$this->_api_key.'><br/><br/>';
		$outdata .= ' orig_logon&nbsp;<input style="width:600px;" type="text" name="orig_logon"   value=""><br/><br/>';
		$outdata .= '      logon&nbsp;<input style="width:600px;" type="text" name="logon"        value=""><br/><br/>';
		$outdata .= '   password&nbsp;<input style="width:600px;" type="text" name="password"     value=""><br/><br/>';
		$outdata .= '  last_name&nbsp;<input style="width:600px;" type="text" name="last_name"    value=""><br/><br/>';
		$outdata .= ' first_name&nbsp;<input style="width:600px;" type="text" name="first_name"   value=""><br/><br/>';
		$outdata .= 'mailaddress&nbsp;<input style="width:600px;" type="text" name="mailaddress"  value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="ユーザ更新" /></form>';
		$outdata .= '<hr>';

		$outdata .= '<h3>5.6.1 該当コースのユーザ割当を全て削除（非同期）</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_assign_delete/" method="post">';
		$outdata .= '   api_key&nbsp;<input style="width:200px;" type="text" name="api_key"    value='.$this->_api_key.'><br/><br/>';
		$outdata .= ' course_id&nbsp;<input style="width:600px;" type="text" name="course_id"  value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="実行" /></form>';
		$outdata .= '<hr>';
		$outdata .= '<h3>5.6.2 該当コースのユーザ割当（非同期）</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_assign_insert/" method="post">';
		$outdata .= '   api_key&nbsp;<input style="width:200px;" type="text" name="api_key"    value='.$this->_api_key.'><br/><br/>';
		$outdata .= ' course_id&nbsp;<input style="width:600px;" type="text" name="course_id"  value=""><br/><br/>';
		$outdata .= '    logons&nbsp;<input style="width:200px;" type="text" name="logons"     value="">&nbsp;※カンマ区切りによる複数ID入力可能<br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="実行" /></form>';
		$outdata .= '<hr>';

		$outdata .= '<h3>5.7 ログイン認証（画面遷移）</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_login/" method="post">';
		$outdata .= '   api_key&nbsp;<input style="width:200px;" type="text" name="api_key"    value='.$this->_api_key.'>&nbsp;※「api_key+session_id」をMD5でハッシュ化した32文字。ここでの入力はapi_keyのみ。裏側にて変換を行う。<br/><br/>';
		$outdata .= 'session_id&nbsp;<input style="width:600px;" type="text" name="session_id" value=""><br/><br/>';
		$outdata .= '  back_url&nbsp;<input style="width:600px;" type="text" name="back_url"   value="http://dev-follower.alflearning.com/"><br/><br/>';
		$outdata .= ' lang_code&nbsp;<input style="width:600px;" type="text" name="lang_code"  value=""><br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="実行" /></form>';
		$outdata .= '<hr>';

		$outdata .= '<h3>5.8 ジョブの処理状態の問合せ</h3>';
		$outdata .= '<form action="/elm_api_test/elm_api_job_state/" method="post">';
		$outdata .= '   api_key&nbsp;<input style="width:200px;" type="text" name="api_key"  value='.$this->_api_key.'><br/><br/>';
		$outdata .= '   job_ids&nbsp;<input style="width:200px;" type="text" name="job_ids"  value="">&nbsp;※カンマ区切りによる複数ID入力可能<br/><br/>';
		$outdata .= '<input type="submit" name="submit" value="実行" /></form>';
		$outdata .= '<hr>';

		$this->output->set_header("HTTP/1.0 200 OK");
	//	$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_content_type('text/html; charset=utf-8');
		$this->output->set_output($outdata);
	}

	//----------------------------------------------
	// 結果表示
	//----------------------------------------------
	function _output_display($request_url = '', $request, $content){
	//	$this->load->library('Curl');
		$this->load->helper('json');

		$outdata  = '<h4>API URL</h4>';
		$outdata .= 'API URL&nbsp;:&nbsp;'.$request_url.'<br/>';
		$outdata .= '<hr>';

		$outdata .= '<h4>リクエストパラメータ</h4>';
		foreach($request as $key => $value){
			$outdata .= $key.'&nbsp;:&nbsp;'.$value.'<br/>';
		}
		$outdata .= '<hr>';

		$outdata .= '<h4>レスポンスパラメータ</h4>';
		$outdata .= $content.'<br/>';
		$outdata .= '----------<br/>';
		$outdata .= print_r(obj2arr(json_decode($content)),true ).'<br/>';
		$outdata .= '<hr>';

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('text/html; charset=utf-8');
		$this->output->set_output($outdata);
	}
	
	//----------------------------------------------
	// 5.4.1 コース削除
	//----------------------------------------------
	public function elm_api_course_delete(){
		$this->load->library('Curl');
	//	$this->load->helper('json');
		
		$api_url              = $this->_access_url.'course_delete.cfm';
		$request['api_key']   = $this->input->post('api_key');
		$request['course_id'] = $this->input->post('course_id');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url,$request,$content);
	}
	
	//----------------------------------------------
	// 5.4.2 コース登録
	//----------------------------------------------
	public function elm_api_course_insert(){
		$this->load->library('Curl');
		
		$api_url                 = $this->_access_url.'course_insert.cfm';
		$request['api_key']      = $this->input->post('api_key');
		$request['course_id']    = $this->input->post('course_id');
		$request['course_name']  = $this->input->post('course_name');
		$request['course_guide'] = $this->input->post('course_guide');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url,$request,$content);
	}

	//----------------------------------------------
	// 5.4.3 コース更新
	//----------------------------------------------
	public function elm_api_course_update(){
		$this->load->library('Curl');
		
		$api_url                 = $this->_access_url.'course_update.cfm';
		$request['api_key']      = $this->input->post('api_key');
		$request['course_id']    = $this->input->post('course_id');
		$request['course_name']  = $this->input->post('course_name');
		$request['course_guide'] = $this->input->post('course_guide');
		$request['appear']       = $this->input->post('appear');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url,$request,$content);
	}

	//----------------------------------------------
	// 5.5.1 ユーザ削除
	//----------------------------------------------
	public function elm_api_user_delete(){
		$this->load->library('Curl');
		
		$api_url            = $this->_access_url.'user_delete.cfm';
		$request['api_key'] = $this->input->post('api_key');
		$request['logon']   = $this->input->post('logon');
		
		$content = $this->curl->simple_post($api_url, $request);

		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.5.2 ユーザ登録
	//----------------------------------------------
	public function elm_api_user_insert(){
		$this->load->library('Curl');
		
		$api_url               = $this->_access_url.'user_insert.cfm';
		$request['api_key']    = $this->input->post('api_key');
		$request['logon']      = $this->input->post('logon');
		$request['password']   = $this->input->post('password');
		$request['last_name']  = $this->input->post('last_name');
		$request['first_name'] = $this->input->post('first_name');

		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.5.3 ユーザ更新
	//----------------------------------------------
	public function elm_api_user_update(){
		$this->load->library('Curl');
		
		$api_url                = $this->_access_url.'user_update.cfm';
		$request['api_key']     = $this->input->post('api_key');
		$request['orig_logon']  = $this->input->post('orig_logon');
		$request['logon']       = $this->input->post('logon');
		$request['password']    = $this->input->post('password');
		$request['last_name']   = $this->input->post('last_name');
		$request['first_name']  = $this->input->post('first_name');
		$request['mailaddress'] = $this->input->post('mailaddress');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.6.1 該当コースのユーザ割当を全て削除（非同期）
	//----------------------------------------------
	public function elm_api_assign_delete(){
		$this->load->library('Curl');
		
		$api_url               = $this->_access_url.'assign_delete.cfm';
		$request['api_key']    = $this->input->post('api_key');
		$request['course_id']  = $this->input->post('course_id');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.6.2 該当コースのユーザ割当（非同期）
	//----------------------------------------------
	public function elm_api_assign_insert(){
		$this->load->library('Curl');
		
		$api_url               = $this->_access_url.'assign_insert.cfm';
		$request['api_key']    = $this->input->post('api_key');
		$request['course_id']  = $this->input->post('course_id');
		$request['logons']     = $this->input->post('logons');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.7 ログイン認証（画面遷移）
	//----------------------------------------------
	public function elm_api_login(){
		$this->load->library('Curl');
		
		$api_url               = $this->_access_url.'login.cfm';

		$request['before_api_key'] = $this->input->post('api_key');
		$request['api_key']        = md5($this->input->post('api_key').$this->input->post('session_id'));
		$request['session_id']     = $this->input->post('session_id');
		$request['back_url']       = $this->input->post('back_url');
		$request['lang_code']      = $this->input->post('lang_code');

		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}

	//----------------------------------------------
	// 5.8 ジョブの処理状態の問合せ
	//----------------------------------------------
	public function elm_api_job_state(){
		$this->load->library('Curl');
		
		$api_url            = $this->_access_url.'job_state.cfm';
		$request['api_key'] = $this->input->post('api_key');
		$request['job_ids'] = $this->input->post('job_ids');
		
		$content = $this->curl->simple_post($api_url, $request);
		
		// 確認用画面表示
		$this->_output_display($api_url, $request, $content);
	}


}
