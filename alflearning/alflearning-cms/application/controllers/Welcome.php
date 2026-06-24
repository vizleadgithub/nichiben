<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->database();

		$query = $this->db->query("DESCRIBE ci_sessions");

		foreach ($query->result() as $row) {
			echo $row->Field . '<br>'; // カラム名を出力
		}

		$this->load->library('session');
		$_SESSION["test"] = 1;

		// セッションにデータをセット
		$this->session->set_userdata('test_key', 'test_value');

		// セッションからデータを取得
		echo $this->session->userdata('test_key');

		$this->load->view('welcome_message');
	}
}
