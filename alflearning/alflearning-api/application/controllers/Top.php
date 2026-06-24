<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Top extends CI_Controller {
	function __construct() {
		parent::__construct();

//		$this->allowIP = $this->config->item('api_allow_ips');	//IPアドレス制限　デフォルトで有効（指定すると上書き可能）
	}

	public function index(){
		$this->load->view('top');
	}
}
