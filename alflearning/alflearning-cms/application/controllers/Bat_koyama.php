<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Bat_koyama extends CI_Controller {

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		ini_set("display_errors",1);
		ini_set('error_reporting',E_ALL);

		//DB接続
		$this->load->database();
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index";
	}

	//----------------------------------------------
	// 
	//----------------------------------------------
	function go(){
		print "start";
		$query = $this->db->query(
			' SELECT *'.
			' FROM tbl_product limit 0,10',
			array()
		);
		foreach($query->result_array() as $tbl_product){
			$ids = array();
			for($i = 1; $i <= 25; $i++){
				if($tbl_product['contents_contents'.$i]){
					array_push($ids, $tbl_product['contents_contents'.$i]);
				}
			}

			if(count($ids)){
				$query = $this->db->query(
					' SELECT *, SUM(TIME_TO_SEC(alfstream_duration)) as alltime '.
					' FROM video_alfstream_status'.
					' WHERE video_id IN ('.implode(',', $ids).') ',
					array()
				);

				$video = $query->row_array();

				$queryString = $this->db->update_string('tbl_product', array(
						'play_time'	=> $video['alltime'],
					),
					'product_id='.$tbl_product["product_id"]
				);

				print $queryString;

	//			$this->db->query($qyery);

				print "\n";
			}
		}
	}
}
