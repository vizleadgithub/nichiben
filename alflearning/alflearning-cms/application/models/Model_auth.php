<?php
#[AllowDynamicProperties]
class Model_auth extends CI_Model  
{
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
	}
	//----------------------------------------------
	//ログインチェック
	//[2012/05/01][2012/06/11]
	//[2012/09/03]無効化された学校を除外
	//日弁連、弁護士会ID【teacher.bar_association_id】の追加
	//----------------------------------------------
	function login_check($login_id){ 
		// load language
		$this->lang->load('common');
		
		//全権限付加（libauthから初期値取得、全権限付加後シリアライズ）
		$admin_teacher_auth = $this->libauth->defaultTeacherAuth;
		foreach($admin_teacher_auth as $key => $value){
			$admin_teacher_auth[$key] = 1;
		}
		$admin_teacher_auth = serialize($admin_teacher_auth);

		try{ 
			if ( $login_id == $this->config->item('admin_email') ){
				$SQL  = 'SELECT ';
				$SQL .= ' -1                                              AS teacher_id ';
				$SQL .= ',"' . hash('sha256',$this->config->item('admin_password')) . '" AS teacher_password_encrypt ';
				$SQL .= ',"' . $this->lang->line_or_def('common_super_user','Super User') .'" AS teacher_name ';
				$SQL .= ',"' . $this->config->item('admin_email')    . '" AS teacher_email ';
				$SQL .= ",'" . $admin_teacher_auth                   . "' AS teacher_auth ";
				$SQL .= ',0                                               AS school_id ';
				$SQL .= ',""                                              AS school_name ';
				$SQL .= ',1                                               AS bar_association_id ';
			} else {

				$sql_where = '';
				if(getenv('URL_SERVICE') == 'alfsales'){
					$sql_where = ' AND school.lang = "alfsales" ';
				}elseif(getenv('URL_SERVICE') == 'conference'){
					$sql_where = ' AND school.lang = "conference"';
				}else{
					$sql_where = ' AND (school.lang <> "alfsales" AND school.lang <> "conference") ';
				}

				$SQL  = 'SELECT 
							 teacher.teacher_id                    AS teacher_id
							,teacher.teacher_password_encrypt      AS teacher_password_encrypt
							,teacher.teacher_name                  AS teacher_name
							,teacher.teacher_email                 AS teacher_email
							,teacher.teacher_auth                  AS teacher_auth
							,teacher.school_id                     AS school_id 
							,school.school_name                    AS school_name 
							,teacher.bar_association_id            AS bar_association_id ';
				$SQL .= "FROM teacher INNER JOIN school ON school.school_id = teacher.school_id ";
				$SQL .= "WHERE teacher.teacher_email = ? AND teacher.status = 0";
				$SQL .= "  AND school.status = 0";
				$SQL .= $sql_where;
			//	$SQL .= (getenv('URL_SERVICE') == 'conference' ? ' AND school.lang = "conference"' : ' AND school.lang <> "conference"');	//カンファレンス考慮
			}
			log_message('debug', $SQL);
			return $this->db->query($SQL,array($login_id)); 
		}catch(Exception $e){ 
			throw new Exception(); 
		}
	}

	//----------------------------------------------
	//学校名取得
	//----------------------------------------------
	function school_name($school_id){ 
		try{ 
			$SQL  = 'SELECT 
						 school.school_name AS school_name ';
			$SQL .= "FROM school ";
			$SQL .= "WHERE school.school_id = ? AND school.status = 0";

			log_message('debug', $SQL);
			return $this->db->query($SQL, array($school_id)); 
		}catch(Exception $e){ 
			throw new Exception(); 
		}
	}

	//----------------------------------------------
	//学校内容取得
	//----------------------------------------------
	function get_school($school_id){ 
		try{ 
			$SQL  = 'SELECT school.* ';
			$SQL .= "FROM school ";
			$SQL .= "WHERE school.school_id = ? AND school.status = 0";

			log_message('debug', $SQL);
			return $this->db->query($SQL, array($school_id)); 
		}catch(Exception $e){ 
			throw new Exception(); 
		}
	}

	//----------------------------------------------
	// [2012/10/01]講師情報取得
	// 日弁連対応、所属弁護士会IDの追加
	//----------------------------------------------
	function get_teacher_data($teacher_id){ 
		// load language
		$this->lang->load('common');
		
		//全権限付加（libauthから初期値取得、全権限付加後シリアライズ）
		$admin_teacher_auth = $this->libauth->defaultTeacherAuth;
		foreach($admin_teacher_auth as $key => $value){
			$admin_teacher_auth[$key] = 1;
		}
		$admin_teacher_auth = serialize($admin_teacher_auth);

		try{ 
			if ( $teacher_id == -1 ){
				$SQL  = 'SELECT ';
				$SQL .= ' -1                                              AS teacher_id ';
				$SQL .= ',"' . hash('sha256',$this->config->item('admin_password')) . '" AS teacher_password_encrypt ';
				$SQL .= ',"' . $this->lang->line_or_def('common_super_user','Super User') .'" AS teacher_name ';
				$SQL .= ',"' . $this->config->item('admin_email')    . '" AS teacher_email ';
				$SQL .= ",'" . $admin_teacher_auth                   . "' AS teacher_auth ";
				$SQL .= ',0                                               AS school_id ';
				$SQL .= ',1                                               AS bar_association_id ';
				$SQL .= ',""                                              AS school_name ';
			} else {

				$sql_where = '';
				if(getenv('URL_SERVICE') == 'alfsales'){
					$sql_where = ' AND school.lang = "alfsales" ';
				}elseif(getenv('URL_SERVICE') == 'conference'){
					$sql_where = ' AND school.lang = "conference"';
				}else{
					$sql_where = ' AND (school.lang <> "alfsales" AND school.lang <> "conference") ';
				}

				$SQL  = 'SELECT 
							 teacher.teacher_id                    AS teacher_id
							,teacher.teacher_password_encrypt      AS teacher_password_encrypt
							,teacher.teacher_name                  AS teacher_name
							,teacher.teacher_email                 AS teacher_email
							,teacher.teacher_auth                  AS teacher_auth
							,teacher.school_id                     AS school_id 
							,teacher.bar_association_id            AS bar_association_id 
							,school.school_name                    AS school_name ';
				$SQL .= "FROM teacher INNER JOIN school ON school.school_id = teacher.school_id ";
				$SQL .= "WHERE teacher.teacher_id = ? AND teacher.status = 0";
				$SQL .= "  AND school.status = 0";
				$SQL .= $sql_where;
			//	$SQL .= (getenv('URL_SERVICE') == 'conference' ? ' AND school.lang = "conference"' : ' AND school.lang <> "conference"');	//カンファレンス考慮
			}
			log_message('debug', $SQL);
			return $this->db->query($SQL,array($teacher_id)); 
		}catch(Exception $e){ 
			throw new Exception(); 
		}
	}

}
?>
