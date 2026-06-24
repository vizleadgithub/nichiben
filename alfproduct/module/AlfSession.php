<?php
require_once("define_list.php");
require_once("ci/libraries/Encrypt.php");
require_once("DbConnect.php");

class AlfSession extends Smarty {
	public $template_dir_old;

	public $prev_session;
	/**
	* コンストラクタ
	* ディレクトリ指定やデフォルト値の初期設定を行う
	*/
	public function __construct() {
		$this->prev_session = $_SESSION;
	}

	function session_check(){
		$objEncrypt = new CI_Encrypt();
		$objDbConnect = new DbConnect();
		$ci_session = $objEncrypt->decode($_COOKIE["ci_session"]);
		$ci_session = $_COOKIE["ci_session"];
//var_dump($ci_session);
		if(isset($_COOKIE["ci_session"])){
			//$sql = "SELECT * FROM ci_sessions WHERE session_id LIKE '".$arr_cookie["session_id"]."'";
			$sql = "SELECT * FROM ci_sessions WHERE id LIKE '".$ci_session."'";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if(0<count($ret)){
				$temp = array();

				$session_data = [];
//var_dump($ret[0]["data"]);

				$items = $this->parse_session_data($ret[0]["data"]);
//var_dump($items);
				$user_data = $items;
				$_SESSION = array_merge($user_data, $this->prev_session);

				if(is_array($_SESSION)){
					if($_SESSION["cms_master.login.logged_in"]){
						if($_SESSION["cms_master.login.teacher_id"]!=""){
							return $_SESSION;
						} else {
							return false;
						}
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}



// セッションデータを解析する関数
function parse_session_data($data) {
    $result = [];
    $offset = 0;

    while ($offset < strlen($data)) {
        // キーを取り出す
        $pos = strpos($data, '|', $offset);
        if ($pos === false) break;

        $key = substr($data, $offset, $pos - $offset);
        $offset = $pos + 1;

        // 値を取り出す（シリアライズデータとして読む）
        $value = @unserialize(substr($data, $offset), ['allowed_classes' => false]);
        
        // デコードに失敗した場合、直接の値として扱う
        if ($value === false) {
            $nextOffset = strpos($data, ';', $offset);
            if ($nextOffset === false) break;

            $value = substr($data, $offset, $nextOffset - $offset + 1);
            $offset = $nextOffset + 1;
        } else {
            $offset += strlen(serialize($value));
        }

        // 再帰的にシリアライズデータを処理する
        $result[$key] = is_string($value) && @unserialize($value) ? $this->parse_session_data($value) : $value;
    }
    return $result;
}

}
?>
