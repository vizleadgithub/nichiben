<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

// 購入履歴  tbl_order  tbl_order_temp  tbl_order_detail

#[AllowDynamicProperties]
class Bat_oneoff_import_tbl_order_update extends CI_Controller {
	//----------------------------------------------
	// 定数
	//----------------------------------------------
	// 使用テーブル名
//	public $_tbl_order_detail         = 'tbl_order_detail';
//	public $_tbl_product              = 'tbl_product';
//	public $_report_user_video_viewed = 'report_user_video_viewed';
	public $_tbl_order_detail         = 'tbl_order_detail_import';
	public $_tbl_product              = 'tbl_product_import';
	public $_report_user_video_viewed = 'report_user_video_viewed_import';

	public $_convert_log_file         = '/alflearning-data/__import_data__/LOG/tbl_order_detail_update.txt';
	
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		// テスト時使用。
		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		print "index ".date('Y/m/d H:i:s')."\n";
	}

	//----------------------------------------------
	// tbl_order_detail.video_complete_flg 更新処理
	//----------------------------------------------
	public function oneoff_update(){
		exec('ps auxw | grep oneoff_update | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
		
		$this->_output_log("START oneoff_update");
		
		$query_order_detail = $this->db->query(
			'SELECT order_detail_id, member_id, product_id, video_complete_flg FROM '.$this->_tbl_order_detail.' ',
			array()
		);
		
		// 上記取得レコード のループ
		$counter = 0;
		foreach($query_order_detail->result_array() as $order_detail){
			
//print "\n[order deail]";
//print var_dump($order_detail);
//print "\n";
			
			// tbl_product から、該当video_id（contents）を取得。
			$contents_list = array();
			
			$query_tbl_product = $this->db->query(
				'SELECT product_id 
					, contents_contents1 
					, contents_contents2 
					, contents_contents3 
					, contents_contents4 
					, contents_contents5 
					, contents_contents6 
					, contents_contents7 
					, contents_contents8 
					, contents_contents9 
					, contents_contents10 
				FROM '.$this->_tbl_product.' WHERE product_id = ? ',
				array(
					$order_detail['product_id'],
				)
			);
			if ($query_tbl_product->num_rows() > 0) {
				$table_record       = $query_tbl_product->row_array();		// 複数はresult_array

//print "\n[tbl_product]";
//print var_dump($table_record);
//print "\n";

				
				if($table_record['contents_contents1'])  $contents_list[] = $table_record['contents_contents1'];
				if($table_record['contents_contents2'])  $contents_list[] = $table_record['contents_contents2'];
				if($table_record['contents_contents3'])  $contents_list[] = $table_record['contents_contents3'];
				if($table_record['contents_contents4'])  $contents_list[] = $table_record['contents_contents4'];
				if($table_record['contents_contents5'])  $contents_list[] = $table_record['contents_contents5'];
				if($table_record['contents_contents6'])  $contents_list[] = $table_record['contents_contents6'];
				if($table_record['contents_contents7'])  $contents_list[] = $table_record['contents_contents7'];
				if($table_record['contents_contents8'])  $contents_list[] = $table_record['contents_contents8'];
				if($table_record['contents_contents9'])  $contents_list[] = $table_record['contents_contents9'];
				if($table_record['contents_contents10']) $contents_list[] = $table_record['contents_contents10'];
			}
			
//print "\n[contents_listt]";
//print var_dump($contents_list);
//print "\n";
			
			// report_user_video_viewed から、視聴完了フラグを取得する
			$check_answer = -1;
			foreach ($contents_list as $value){
				$query_report_user_video_viewed = $this->db->query(
					'SELECT student_id 
						  , video_id 
						  , complete_flag 
					FROM '.$this->_report_user_video_viewed.' 
					WHERE 1=1 
					  AND student_id    = ? 
					  AND video_id      = ? 
					  AND complete_flag = 1 ',
					array(
						$order_detail['member_id'],
						$value,
					)
				);
				
$temp_record_count = $query_report_user_video_viewed->num_rows();
if(!$temp_record_count){
	// 視聴履歴なし
	$check_answer = 0;
	break;
}else{
	// 視聴履歴あり
	$check_answer = $check_answer + 1;
}
//				if ($query_report_user_video_viewed->num_rows() > 0) {
//					// 視聴履歴あり
//					$check_answer = $check_answer + 1;
//				}else{
//					// 視聴履歴なし
//					$check_answer = 0;
//					break;
//				}
			}

//print "\n[check_answer]";
//print $check_answer;
//print "\n";

			// 全てのvideo_id の視聴履歴があり、且つ視聴完了の場合のみ更新処理
			$counter = $counter + 1;
			if($check_answer > 0){
				$this->db->trans_start();
				$this->db->query('UPDATE '.$this->_tbl_order_detail.' 
								SET video_complete_flg        = 1 
								WHERE 1=1 AND order_detail_id = ? ', 
								array(
									$order_detail['order_detail_id']
								));
				$this->db->trans_complete();
				
				$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] UPDATE DATA [order_detail.order_detail_id : ".$order_detail['order_detail_id']."]");
			}else{
				$this->db->trans_start();
				$this->db->query('UPDATE '.$this->_tbl_order_detail.' 
								SET video_complete_flg        = 0 
								WHERE 1=1 AND order_detail_id = ? ', 
								array(
									$order_detail['order_detail_id']
								));
				$this->db->trans_complete();
				
				$this->_output_log("  [".str_pad($counter, 6, '0', STR_PAD_LEFT)."] SKIP DATA   [order_detail.order_detail_id : ".$order_detail['order_detail_id']."]");
			}

//if($counter==10){
//	break;
//}


		}
		
		$this->_output_log("END oneoff_update");

	}

	//----------------------------------------------
	// ログファイル出力＋画面出力 → ファイル出力を停止
	//----------------------------------------------
	function _output_log($val = ""){
		try{
			if(!is_file($this->_convert_log_file)){
				write_file($this->_convert_log_file, "\n", 'a+');
				chmod($this->_convert_log_file, 0777);
			}
			if($val==""){
			}else{
				$val = "[".date('Y-m-d H:i:s')."]".$val;
			}
			
			print $val."\n";
			write_file($this->_convert_log_file, $val."\n", 'a+');
			
			return true;
		}catch(Exception $e){ 
			return false;
		}
	}

	//----------------------------------------------
	// TEST
	//----------------------------------------------
	public function test(){
		print "test ".date('Y/m/d H:i:s')."\n";
	}
}

