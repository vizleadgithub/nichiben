<? if (!defined('BASEPATH')) exit('No direct script access allowed');

//=======================================================================//
//= 学校の契約周りの情報を整理して返す
//=======================================================================//
class Modelschoolcontract extends CI_Model{
	private $isSuperUesr = false;
	private $schoolParamator = false;
	private $defaultSchoolParamatorContractParam = array(
		'live'			=> NULL,
		'book_library'	=> NULL,
		'video'			=> NULL,
	);

	//======================================================================//
	//= constract
	//= デフォルトではセッションに入ってるschoolテーブルの情報使う
	//= 引数でshoolテーブル渡したらそれ使う
	//======================================================================//
	function __construct(){
		parent::__construct();

		$this->load->helper('json_helper');
		$this->load->helper('MY_array_helper');

		if($this->session->userdata('cms_master.login.teacher_id') == -1){
			$this->isSuperUesr = true;
		}
		$this->initialize($this->session->userdata('cms_master.login.school') ? $this->session->userdata('cms_master.login.school') : $this->session->userdata);	//管理画面とフロントの取り方が違う
	}

	//======================================================================//
	//= privates
	//======================================================================//
	//----------------------------------------------------------------------//
	//= 
	//----------------------------------------------------------------------//
//	private function decodeJson($param){
//	}

	//======================================================================//
	//= methods
	//======================================================================//
	//----------------------------------------------------------------------//
	//= 学校パラメータ初期化
	//= 途中で学校変えたい時に使う
	//----------------------------------------------------------------------//
	public function initialize($schoolDB=''){
		if(is_numeric($schoolDB)){	//school_idがわたされたらDBから取る
			$query = $this->db->query(
				' SELECT * FROM school'.
				' WHERE school_id = ?'.
				' LIMIT 0, 1',
				array(
					(int) $schoolDB,
				)
			);
			$schoolDB = $query->row_array();
		}
		if(!isset($schoolDB['contract_param']) || !$schoolDB['contract_param']){
			return;
		}

		$this->schoolParamator = $schoolDB;

		if(isset($this->schoolParamator['contract_param']) && is_string($this->schoolParamator['contract_param'])){
			$this->schoolParamator['contract_param'] = ($this->schoolParamator['contract_param'] ? $this->schoolParamator['contract_param'] : '{}');
			$this->schoolParamator['contract_param'] = array_merge(
				$this->defaultSchoolParamatorContractParam,
				obj2arr(json_decode($this->schoolParamator['contract_param']))
			);
		}
	}

	//----------------------------------------------------------------------//
	//= 学校としてそのサービスを契約しているかどうか
	// [2012/10/23]契約形態の種類判断を削除
	//----------------------------------------------------------------------//
	public function enableService($param){
		array_merge(array(
			'serviceKey'	=> 'live',
		), $param);

		//super user
		if($this->isSuperUesr){
			return true;
		}

		//学校削除
		if($this->schoolParamator['status'] == 9){
			return false;
		}

		//fixation以外は何が来るか決まってないので、デモ想定としてひとまず全部OK
	//	if($this->schoolParamator['contract'] != 'fixation'){
	//		return true;
	//	}

		//keyがあるかどうか
		if(!isset($this->schoolParamator['contract_param'][$param['serviceKey']]) || !$this->schoolParamator['contract_param'][$param['serviceKey']]){
			return false;
		}
		
		//各契約形態が "undefined" であればfalseを返す
		if( $this->schoolParamator['contract_param'][$param['serviceKey']]['contract'] == "undefined" ){
			return false;
		}
		
		return true;
	}

	//----------------------------------------------------------------------//
	//= そのサービスを契約履行しているかどうか
	//= オーバーしてたりするとfalseが返る
	//----------------------------------------------------------------------//
	public function avalableService($param){
		//まだちゃんと作ってない
		array_merge(array(
			'serviceKey'	=> 'live',
		), $param);

		if(!$this->enableService($param)){
			return false;
		}

		if(!isset($this->schoolParamator['contract_param'][$param['serviceKey']]) || !$this->schoolParamator['contract_param'][$param['serviceKey']]['stat']){
			return false;
		}

		return true;
	}

	//----------------------------------------------------------------------//
	//= サービスごとの契約内容を取得
	//----------------------------------------------------------------------//
	public function getContractParam($param){
		array_merge(array(
			'serviceKey'	=> 'live',
		), $param);

		if(!$this->enableService($param)){
			return false;
		}

		return $this->schoolParamator['contract_param'][$param['serviceKey']];
	}

	//----------------------------------------------------------------------//
	//= サービスごとの契約内容を取得
	//----------------------------------------------------------------------//
	public function updateContractParam($param){
		$param = array_merge(array(
			'serviceKey'	=> '',
			'contractParam'	=> array(),
		), $param);

		if(!isset($this->schoolParamator['contract_param']) || !$this->schoolParamator['contract_param']){
			die('schoolParamator not found need a initialize');
		}

		if(!$this->enableService($param)){
			return false;
		}

		$param['contractParam']['updated_at'] = time();
		$this->schoolParamator['contract_param'] = array_merge_recursive_distinct($this->schoolParamator['contract_param'], array(
			$param['serviceKey']	=> $param['contractParam'],
		));

		//stat決める
		switch($param['serviceKey']){
			case 'live':
				if($this->schoolParamator['contract_param']['live']['contract'] == 'fixation'){
					$this->schoolParamator['contract_param']['live']['stat'] = 1;
					if($this->schoolParamator['contract_param']['live']['time_now'] > $this->schoolParamator['contract_param']['live']['time']){
						$this->schoolParamator['contract_param']['live']['stat'] = 0;
					}
					if($this->schoolParamator['contract_param']['live']['strage_now'] > $this->schoolParamator['contract_param']['live']['strage']){
						$this->schoolParamator['contract_param']['live']['stat'] = 0;
					}
				}
				break;
		}

		$res = $this->db->query($this->db->update_string('school', array(
			'contract_param'	=> json_encode($this->schoolParamator['contract_param']),
		), 'school_id='.$this->schoolParamator['school_id']));
	}

	//----------------------------------------------------------------------//
	//= アルフストリーム用のパラメータを取得する
	//----------------------------------------------------------------------//
	public function getAlfstreamParam(){
//		if($this->isSuperUesr){
//			return 'isSuperUser';
//		}
		return array_merge(array(
			'auth_key'	=> '',
			'code'		=> '',
		), $this->schoolParamator['contract_param']['alfstream']);
//		return array_merge($this->schoolParamator['alfstream'],array(
//			'auth_key'	=> 'u9i028jmaapdb2im2869azxrkzwcxg8fs0fx29du',
//			'code'		=> '0kwYW5pB',
//		));
	}
}
?>
