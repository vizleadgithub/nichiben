<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("/srv/alfproduct/admin/testlogin/application/libraries/Libauth.php");
include("/srv/alfproduct/module/DbConnect.php");
class Test extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		$input_id = "sano@vizlead.com";
		$input_pw = "sanosano";

		$objDbConnect = new DbConnect();
		$sql = "";
		$sql.= "select ";
		$sql.= "teacher.teacher_id AS teacher_id ";
		$sql.= ",teacher.teacher_password_encrypt AS teacher_password_encrypt ";
		$sql.= ",teacher.teacher_name AS teacher_name ";
		$sql.= ",teacher.teacher_email AS teacher_email ";
		$sql.= ",teacher.teacher_auth AS teacher_auth ";
		$sql.= ",teacher.school_id AS school_id ";
		$sql.= ",school.school_name AS school_name ";
		$sql.= "from ";
		$sql.= "teacher INNER JOIN school ON school.school_id = teacher.school_id ";
		$sql.= "WHERE teacher.teacher_email = '".$input_id."' AND teacher.status = 0";
		$sql.= " AND school.status = 0";
		$ret = $objDbConnect->query_fetch_arr($sql);
var_dump($ret);
		if(0<count($ret)){
			if( $ret[0]["teacher_password_encrypt"]==hash('sha256',$input_pw) ){
				$user = array(	
					'cms_master.login.teacher_id'	=> $ret[0]["teacher_id"],
					'cms_master.login.teacher_name'	=> $ret[0]["teacher_name"],
					'cms_master.login.teacher_email'=> $ret[0]["teacher_email"],
					'cms_master.login.teacher_auth'	=> $ret[0]["teacher_auth"],
					'cms_master.login.school_id'	=> $ret[0]["school_id"],
					'cms_master.login.school_name'	=> $ret[0]["school_name"],
					//'cms_master.login.school'	=> array(),
					'cms_master.login.logged_in'	=> TRUE
				);
var_dump($user);
				$this->load->library('session');
				$this->session->set_userdata($user);
//				header("Location: /mailmagazine/");
//				exit();

//				$test = $this->session->all_userdata();
				print("ok.");
				exit();
			} else {
				print("ng.1");
				exit();
			}
		} else {
			print("ng.2");
			exit();
		}

/*
		$login_id = "sano@vizlead.com";
		$password = "sanosano";
		$this->libauth = new Libauth();

		if ( $this->libauth->login($login_id, $password) ) {
			print("ok.");
			exit();
		} else {
			print("ng.");
			exit();
		}

		//print("test<hr>");
var_dump($_POST["data"]);
		$data = "";
		if( isset($_POST["data"]) && !empty($_POST["data"]) ){
			$data = @unserialize($_POST["data"]);
var_dump($data);
		}
		if(is_array($data) ){
			$this->ci->session->set_userdata($data);
		}
		print("ok");
		exit();
*/		
		//$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */