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

				// DBとセッションのteacher情報を同期する
				// 他の管理者がログイン中ユーザーの属性（弁護士会ID等）を変更した場合に
				// 次のリクエストでセッションを自動更新する
				$teacher_id = $_SESSION["cms_master.login.teacher_id"] ?? '';
				if ($teacher_id !== '' && (int)$teacher_id !== -1) {
					$escaped_teacher_id = mysqli_real_escape_string($objDbConnect->connect, $teacher_id);
					$sql_sync = "SELECT t.bar_association_id, t.school_id, t.teacher_name, s.school_name"
						. " FROM teacher t"
						. " INNER JOIN school s ON s.school_id = t.school_id"
						. " WHERE t.teacher_id = '$escaped_teacher_id' AND t.status = 0 AND s.status = 0";
					$row_teacher = $objDbConnect->query_fetch($sql_sync);
					if (!empty($row_teacher)) {
						$needs_sync =
							(string)($_SESSION["cms_master.login.bar_association_id"] ?? '') !== (string)$row_teacher['bar_association_id'] ||
							(string)($_SESSION["cms_master.login.school_id"]           ?? '') !== (string)$row_teacher['school_id'];
						if ($needs_sync) {
							$_SESSION["cms_master.login.bar_association_id"] = $row_teacher['bar_association_id'];
							$_SESSION["cms_master.login.school_id"]          = $row_teacher['school_id'];
							$_SESSION["cms_master.login.teacher_name"]       = $row_teacher['teacher_name'];
							$_SESSION["cms_master.login.school_name"]        = $row_teacher['school_name'];
						}
					}
				}

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
