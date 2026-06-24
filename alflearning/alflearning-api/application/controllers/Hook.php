<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Hook {
	var $ci;

	function __construct() {
		$this->ci =& get_instance();
	}

	//======================================================================//
	//= IPアドレスチェック（指定方法は下記）
	//= *
	//= 211.129.78.63
	//= 211.129.78.63/24
	//= array(上記指定方法可)
	//======================================================================//
	private function _check($p_ip, $p_permit_ip = '127.0.0.1/0'){
		if($p_permit_ip == '*'){
			return true;
		}

		$p_permit_ip = (preg_match('/\//', $p_permit_ip) ? $p_permit_ip : $p_permit_ip.'/0');
		list($ip, $mask_bit) = explode("/", $p_permit_ip);
		$ip_long = ip2long($ip) >> (32 - $mask_bit);
		$p_ip_long = ip2long($p_ip) >> (32 - $mask_bit);
		if ($p_ip_long == $ip_long) {
			return true;
		}
		else {
			return false;
		}
	}

	//======================================================================//
	//= コントローラがインスタンス化された直後で、
	//= メソッドの呼び出しが起こる前に呼ばれます。
	//======================================================================//
	public function post_controller_constructor(){
		$allowIPs = (isset($this->ci->allowIP) ? $this->ci->allowIP : $this->ci->config->item('api_allow_ips'));
		//設定ファイルとクラス両方に指定がなければチェックしない
		if(!$allowIPs){
			return;
		}

		$allowIPs = (is_array($allowIPs) ? $allowIPs : array($allowIPs));

		$clientIP = '';
		if($this->ci->input->server('HTTP_X_FORWARDED_FOR')){
			$clientIP = $this->ci->input->server('HTTP_X_FORWARDED_FOR');
		}
		else if($this->ci->input->server('REMOTE_ADDR')){
			$clientIP = $this->ci->input->server('REMOTE_ADDR');
		}

		if(!$clientIP){
			return;
		}

		foreach($allowIPs as $allowIP){
			if($this->_check($clientIP, $allowIP)){
				return;
			}
		}

		show_error("$clientIP is deny", 404);
	}
}
