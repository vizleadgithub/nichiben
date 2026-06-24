<?
#[AllowDynamicProperties]
class Bat_update_student_from_csv_check extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->library('email');
		$this->load->helper('unit');

		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	public function index(){
	}

	public function update_student_from_csv_check(){
		$subject = "";
		$body = "";
		$data1 = "";
		$data2 = "";
		$count1 = 0;
		$count2 = 0;
		$query = $this->db->query(
			' SELECT '.
			'  * '.
			' FROM '.
			'  ( '.
			'    SELECT '.
			'     lawyer_number, '.
			'     count( lawyer_number ) AS c '.
			'    FROM '.
			'     student '.
			//'    WHERE '.
			//'     status=0 '.
			'    GROUP BY '.
			'     lawyer_number '.
			'  ) AS t '.
			' WHERE '.
			'  c >1 ',
			array()
		);
		foreach($query->result_array() as $row){
			$data1 .= "[ ".$row['lawyer_number']." ] : ".$row['c']." 件"."\n";
			$count1 += 1;
		}

		$query = $this->db->query(
			' SELECT '.
			'  * '.
			' FROM '.
			'  ( '.
			'    SELECT '.
			'     lawyer_number, '.
			'     count( lawyer_number ) AS c '.
			'    FROM '.
			'     student '.
			'    WHERE '.
			'     status=0 '.
			'    GROUP BY '.
			'     lawyer_number '.
			'  ) AS t '.
			' WHERE '.
			'  c >1 ',
			array()
		);
		foreach($query->result_array() as $row){
			$data2 .= "[ ".$row['lawyer_number']." ] : ".$row['c']." 件"."\n";
			$count2 += 1;
		}

		if( $data1!="" ){
		}
		if( $data2!="" ){
			$body .= "■本日のバッチ処理による重複チェック結果". "\n";
			$body .= "【想定外重複ユーザ件数】". "\n";
			$body .= "・「status」が「0」と「0」の重複：".$count2."件". "\n";
			$body .= "\n";
			$body .= "■以下、重複登録番号一覧". "\n";
			$body .= "・「status」が「0」と「0」の重複". "\n";
			$body .= $data2."\n";
			$body .= "\n";
		} else {
			$body .= "■本日のバッチ処理による重複チェック結果". "\n";
			$body .= "【想定外重複ユーザ件数】". "\n";
			$body .= "・「status」が「0」と「0」の重複：".$count2."件". "\n";
			$body .= "\n";
			$body .= "■以下、重複登録番号一覧". "\n";
			$body .= "・「status」が「0」と「0」の重複". "\n";
			$body .= "----本日の重複なし----"."\n";
			$body .= "\n";
		}
		if( $data1=="" && $data2=="" ){
		} else {
		}

		$subject = "[".date("Y-m-d H:i:s")."]日弁連重複状況メール";
		$this->email->clear();
		$this->email->initialize(array(
			'charset'	=> 'ISO-2022-JP',
			'crlf'		=> "\r\n",
			'newline'	=> "\r\n"
		));

		$this->email->to('sano@vizlead.com');
		$this->email->cc('aizawa.d2cs@gmail.com,eikun.yokobayashi@d2c.co.jp');
		$this->email->from('info@alflearning.com');
		$this->email->subject( mb_convert_encoding($subject, 'ISO-2022-JP', 'UTF-8') );
		$this->email->message( mb_convert_encoding($body, "ISO-2022-JP", "UTF-8"));
		$this->email->send();
	}
}


?>
