<?
	function view_custom($view, $vars = array(), $return = FALSE){
		$ci =& get_instance();
		$ci->load->helper('device');

//		$rootPath = 'other/';
//		if(isAlfLearningIpadApp()){
			$rootPath = 'ipad/';
//		}
//		else if(isAlfLearningIphoneApp()){
//			$rootPath = 'iphone/';
//		}

		return $ci->load->view($rootPath.$view, $vars, $return);
	}
?>
