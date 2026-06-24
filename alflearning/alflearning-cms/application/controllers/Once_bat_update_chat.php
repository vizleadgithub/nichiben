<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Once_bat_update_chat extends CI_Controller {

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
	}

	public function go(){
		$this->load->helper('json');

		$query = $this->db->query(
			' SELECT *'.
			' FROM chat'.
			' WHERE message LIKE "==========VOICEPlAY%" AND type IS NULL',
			array()
		);

		$ok = 0;

		foreach($query->result_array() as $chat){
			$params = array();

			$params["type"] = "VOICE_SHARE";

			$params["submit_teacher_id"] = str_replace("master_", "", $chat["user_id"]);
			$params["target_student_id"] = 0;

			if (ereg("mp3", $chat["message"])) { $params["file_type"] = ".mp3"; }
			else { $params["file_type"] = ".m4a"; }

			$chat["message"] = str_replace("==========VOICEPlAY==========", "", $chat["message"]);
			$chat["message"] = str_replace("==========VOICEPlAY(mp3)==========", "", $chat["message"]);

			$message_param = obj2arr(json_decode($chat["message"]));
			if ($message_param) {
				$params["target_chat_id"] = $message_param["chatId"];

				$res = $this->db->query($this->db->update_string('chat', $params,'chat_id='.$chat["chat_id"]))
;
				if ($res) { $ok++; }
			}
		}

		echo "chat data update：".$ok."\n";
	}
}
