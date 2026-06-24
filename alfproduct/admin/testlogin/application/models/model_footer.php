<?php
class Model_footer extends CI_Model
{
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
	//	$this->load->database();
	}

	//----------------------------------------------
	// 各画面フッター出力
	//----------------------------------------------
	function output_footer($param){
		//引数設定
		$param = array_merge(
						array(
							'company'	=> 'ALF',
							'service'	=> ''   ,
						),
						$param
					);

		// ALF Learning・ALF Conference
		if($param['company'] == 'ALF'){
			if(getenv('URL_SERVICE') == 'alfsales'){
				$return_data = 'ALF Sales '.$this->config->item('alf_learning_ver').' | Powered by Alfredcore,inc';
			}elseif(getenv('URL_SERVICE') == 'conference'){
				$return_data = 'ALF Conference '.$this->config->item('alf_learning_ver').' | Powered by Alfredcore,inc';
			}else{
				$return_data = 'ALF Learning '.$this->config->item('alf_learning_ver').' | Powered by Alfredcore,inc';
			}
		}

		return $return_data;
	}
}
?>
