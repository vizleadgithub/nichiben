<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Outside_gingerapp 
{
	//----------------------------------------------
	//共通
	//----------------------------------------------

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct(){
	
		$this->ci =& get_instance();
		
		log_message('debug', 'Outside_gingerapp Initialized');
	}
	
	//----------------------------------------------
	// [private] 外部連携情報取得
	//----------------------------------------------
	function _get_school_contract_param($param)
	{
		$param = array_merge(
						array(
							'school_id' => 0,
						),
						$param
					);
		$result['api_key'] = '';
		$result['api_url'] = '';
		
		// Load Models
		$this->ci->load->model('model_school_manage', 'model_school_manage');
		
		// 学校情報取得
		$db_data = $this->ci->model_school_manage->get_school($param);
		
		if(count($db_data) > 0){
			$this->ci->load->helper('json');
			$contract_param = obj2arr(json_decode($db_data['contract_param']));
			if(isset($contract_param['outside_elearningmanager'])){
				if(isset($contract_param['outside_elearningmanager']['api_key'])){
					$result['api_key'] = urldecode($contract_param['outside_elearningmanager']['api_key']);
				}
				if(isset($contract_param['outside_elearningmanager']['api_url'])){
					$result['api_url'] = urldecode($contract_param['outside_elearningmanager']['api_url']);
				}
			}
		}
		return $result;
	}
	
	//----------------------------------------------
	// eLearning Manager 契約状態確認
	// request  : school_id
	// response : true or false
	//----------------------------------------------
	public function check_contract($param)
	{
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		
		// 学校．契約形態．外部連携の取得
		$school_contract = $this->_get_school_contract_param($param);
print "〓api_key=>".$school_contract['api_key']."<br/>";
print "〓api_url=>".$school_contract['api_url']."<br/>";
		// eLearning Manager 契約有無確認
		$elm_contract = $this->check_apikey($school_contract);

		return $elm_contract;
	}
	
	//----------------------------------------------
	// eLearning Manager 契約状態確認
	// request  : api_key, api_url
	// response : status（true or false）, message（エラー時挿入）
	//----------------------------------------------
	public function check_apikey($param)
	{
	//	$CI =& get_instance();

		$param = array_merge(
						array(
							'api_key'   => '',
							'api_url'   => '',
						),
						$param
					);
		$result['status']  = false;
		$result['message'] = '';
		
		$make_api_url       = $param['api_url'].'user_delete.cfm';
		$request['api_key'] = $param['api_key'];
		
		if($param['api_url'] != ''){
			$this->ci->load->helper('json');
			$this->ci->load->library('Curl');
print "〓make_api_url=>".$make_api_url."<br/>";
print "〓api_key     =>".$request['api_key']."<br/>";

			$content = $this->ci->Curl->simple_post($make_api_url, $request);
	/*		$elm_data = obj2arr(json_decode($content);
			
			if($elm_data['stat'] == 200){
				$result['status'] = true;
			}else{
				$result['message'] = 'non-contract_api_key';
			}
	*/	
		}else{
			$result['message'] = 'non-data_api_url';
		}

		return $result;
	}
}


?>
