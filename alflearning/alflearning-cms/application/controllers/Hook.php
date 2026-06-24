<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Hook {
	function __construct() {
	}

	//======================================================================//
	//= コントローラが呼ばれる直前に呼ばれます。
	//= すべて基本クラスのロード、ルーティング、そしてセキュリティチェックが終わっています。
	//======================================================================//
	public function pre_controller(){
	}

	//======================================================================//
	//= コントローラがインスタンス化された直後で、
	//= メソッドの呼び出しが起こる前に呼ばれます。
	//======================================================================//
	public function post_controller_constructor(){
		$ci =& get_instance();
	
		// Session情報のlast_activityを更新（DBも更新してくれる）
		// Jun Add 2013/07/12
		if ($ci->session->userdata('session_id')) {
			$ci->session->set_userdata(array(
				'last_activity'	=> time() + $ci->config->item('sess_time_to_update'),
			));
		}

	}

	//======================================================================//
	//= アクションメソッドの実行後で、「CI_Output」クラスによる
	//= レスポンス出力処理の直前のフックポイントです。
	//======================================================================//
	public function post_controller(){
	}

	//======================================================================//
	//= CodeIgniterの「CI_Output」クラスによるレスポンス出力処理が終了した後のフックポイントです。
	//= この後に残されたの処理はデータベースの切断処理のみです。(データベースが利用されている場合)
	//= つまり、最終処理になります。
	//======================================================================//
	public function post_system(){

// A PHP Error was encountered
// Severity: Warning
// Message: Cannot modify header information - headers already sent by (output started at /srv/alflearning-cms/system/core/Output.php:383)
// Filename: libraries/Session.php
// Line Number: 671
// がでるので、別場所移動
	}
}
