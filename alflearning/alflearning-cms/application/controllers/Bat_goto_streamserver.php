<?php
//$this->input->is_cli_request()
#[AllowDynamicProperties]
class Bat_goto_streamserver extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	public function index(){
	}

	public function start($fname = 'dev'){
		$query = $this->db->query(
			' SELECT class_id FROM class'.
			' WHERE 1 = 1'.
			' AND class.class_open < ?'.
			' AND class.class_close > ?'.
			' AND class.status <> 9',
			array(
				date("Y-m-d H:i:s", time()+(60*10)),
				date("Y-m-d H:i:s", time()),
			)
		);

		$fName = '/tmp/'.time().'streamserver_send.txt';

		print "start";
		$fp = fopen($fName, 'w');
		$classLists = array();
		foreach($query->result_array() as $row){
			print_r($row);
			fwrite($fp, $row['class_id']."\n");
		}
		fclose($fp);
		print "end";

		`rsync -avz -c $fName koyama@stream1.alfredcore.net:/home/koyama/processcheck_ffmpeg_$fname.txt`;
		`rm -f $fName`;
	}
}
?>
