<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once '/srv/alfproduct/module/define_list.php';

ini_set( 'display_errors', 0 ); 

#[AllowDynamicProperties]
class Sso_update_profile extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		$this->load->library('Curl');
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		$this->load->helper('json');

		// 戻り値の初期化
		$result_param = array();
		//error_log("--------------\n", 3, "/alflearning-data/test_log/sso.log");
		//error_log("[".date("Y-m-d H:i:s")."] api test1\n", 3, "/alflearning-data/test_log/sso.log");
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 引数確認：トークンIDとユーザーエージェントがなければ終了
		$post_token_id = "";
		$post_user_agent = "";
		if(isset($_REQUEST['token_id']) && isset($_REQUEST['user_agent'])){
			$post_token_id = $_REQUEST['token_id'];
			$post_user_agent = $_REQUEST['user_agent'];
		}else{
			$result_param = $this->_output_data(array(
				'status'		=> 'NG',
				'getCode'		=> '',
				'getMessage'	=> 'not IDtoken or UserAgent', // トークンIDまたはユーザーエージェントなし
				'getFile'		=> basename(__FILE__),
				'getLine'		=> __line__,
			));
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		//error_log("[".date("Y-m-d H:i:s")."] api test2\n", 3, "/alflearning-data/test_log/sso.log");
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// トークンID検証
		if(empty($result_param)){
			$url = NEW_SSO_UPDATE;
			$url.= "?secret=".NEW_SSO_SECRET;
			$url.= "&transactionId=".$this->create_sso_transaction_id();
			$url.= "&IDtoken=".$post_token_id;
			$header = array(
				"User-Agent: $post_user_agent"
			);
			$options =array(
				'http' => array(
					'method' => "GET",
					'header' => $header,
				)
			);
			$result_sso_update = file_get_contents($url, false, stream_context_create($options));
			if($result_sso_update !== false){
				$result_sso_update = json_decode($result_sso_update, true);
				if(isset($result_sso_update['status']) && $result_sso_update['status'] == 'success'){
				}else{
					$result_param = $this->_output_data(array(
						'status'		=> 'NG',
						'getCode'		=> '',
						'getMessage'	=> 'Invalid IDtoken', // 無効なトークンID
						'getFile'		=> basename(__FILE__),
						'getLine'		=> __line__,
					));
				}
			}else{
				$result_param = $this->_output_data(array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'API error(update)', // APIエラー
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				));
			}
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		//error_log("[".date("Y-m-d H:i:s")."] api test3\n", 3, "/alflearning-data/test_log/sso.log");
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// トークンIDから属性取得
		$get_attribute = array();
		if(empty($result_param)){
			$url = NEW_SSO_PROFILE;
			$url.= "?secret=".NEW_SSO_SECRET;
			$url.= "&transactionId=".$this->create_sso_transaction_id();
			$url.= "&IDtoken=".$post_token_id;
			$header = array(
				"User-Agent: $post_user_agent"
			);
			$options =array(
				'http' => array(
					'method' => "GET",
					'header' => $header,
				)
			);
			$result_sso_profile = file_get_contents($url, false, stream_context_create($options));
			if($result_sso_profile !== false){
				$result_sso_profile = json_decode($result_sso_profile, true);
				if(isset($result_sso_profile['userInfo']) && isset($result_sso_profile['status']) && $result_sso_profile['status'] == 'success'){
					// 取得結果を整形
					$profile_userInfo = $result_sso_profile['userInfo'];

					// NULLは空文字に変換
					if(is_null($profile_userInfo['uid'])){
						$profile_userInfo['uid'] = '';
					}
					if(is_null($profile_userInfo['email'])){
						$profile_userInfo['email'] = '';
					}
					if(is_null($profile_userInfo['memberNo'])){
						$profile_userInfo['memberNo'] = '';
					}
					if(is_null($profile_userInfo['affiliation'])){
						$profile_userInfo['affiliation'] = '';
					}
					if(is_null($profile_userInfo['affiliationCd'])){
						$profile_userInfo['affiliationCd'] = '';
					}
					if(is_null($profile_userInfo['openName_family'])){
						$profile_userInfo['openName_family'] = '';
					}
					if(is_null($profile_userInfo['openName_first'])){
						$profile_userInfo['openName_first'] = '';
					}
					if(is_null($profile_userInfo['membershipType'])){
						$profile_userInfo['membershipType'] = '';
					}
					if(is_null($profile_userInfo['registrationDate'])){
						$profile_userInfo['registrationDate'] = '';
					}

					$get_attribute['uid'] = $profile_userInfo['uid']; // ログイン時のID
					$get_attribute['mail'] = $profile_userInfo['email']; // メールアドレス
					$get_attribute['employeenumber'] = $profile_userInfo['memberNo']; // 登録番号
					$get_attribute['bengoshikai'] = $profile_userInfo['affiliation']; // 所属弁護士会or所属部署の文字列
					$get_attribute['bengoshikaicode'] = $profile_userInfo['affiliationCd']; // 弁護士会コード（本システム用コードに変換必要）
					$get_attribute['nongaijiopennamefamily'] = $profile_userInfo['openName_family']; // 公開氏(外字なし)
					$get_attribute['nongaijiopennamefirst'] = $profile_userInfo['openName_first']; // 公開名(外字なし)
					$get_attribute['membersection'] = $profile_userInfo['membershipType']; // 会員区分
					error_log("--------------\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] uid=".$profile_userInfo['uid']."\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] memberNo=".$profile_userInfo['memberNo']."\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] affiliation=".$profile_userInfo['affiliation']."\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] affiliationCd=".$profile_userInfo['affiliationCd']."\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] membershipType=".$profile_userInfo['membershipType']."\n", 3, "/alflearning-data/test_log/sso.log");
					error_log("[".date("Y-m-d H:i:s")."] registrationDate=".$profile_userInfo['registrationDate']."\n", 3, "/alflearning-data/test_log/sso.log");

					if($profile_userInfo['registrationDate'] != ''){
						$get_attribute['bengoshientrydate'] = str_replace('/', '', $profile_userInfo['registrationDate']); // 登録年月日（yyyymmdd 型）
					}else{
						$get_attribute['bengoshientrydate'] = '';
					}

					$get_attribute['token.id'] = $post_token_id; // トークンID

				}else{
					$result_param = $this->_output_data(array(
						'status'		=> 'NG',
						'getCode'		=> '',
						'getMessage'	=> 'Not attribute',	// 属性がない
						'getFile'		=> basename(__FILE__),
						'getLine'		=> __line__,
					));
				}
			}else{
				$result_param = $this->_output_data(array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'API error(profile)', // APIエラー
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				));
			}
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		//error_log("[".date("Y-m-d H:i:s")."] api test4\n", 3, "/alflearning-data/test_log/sso.log");
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 属性から受講者新規登録・更新処理
		if(empty($result_param)){
			$result_param = $this->_output_data( $this->_update_student($get_attribute) );
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		//error_log("[".date("Y-m-d H:i:s")."] api test5\n", 3, "/alflearning-data/test_log/sso.log");
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 結果出力（JSON形式）
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output( json_encode($result_param) );
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		//error_log("[".date("Y-m-d H:i:s")."] api test6\n", 3, "/alflearning-data/test_log/sso.log");
	}

	//----------------------------------------------
	// 受講者の新規登録・更新
	//----------------------------------------------
	function _update_student($param){
		// 引数設定（○が必ずある値）
		//
		//	["token.id"]				=> ○トークンID
		//	["uid"]						=> ○ユーザID
		//	["mail"]					=> ○メールアドレス
		//	["employeenumber"]			=> 　登録番号
		//	["bengoshikai"]				=> 　所属弁護士会名 or 所属部署（会員区分と合わせて参照する）
		//	["bengoshikaicode"]			=> 　弁護士会コード（本システム用コードに変換必要）
		//	["bengoshientrydate"]		=> 　登録年月日
		//	["nongaijiopennamefamily"]	=> ○公開氏(外字なし)
		//	["nongaijiopennamefirst"]	=> ○公開名(外字なし)
		//	["membersection"]			=> 　会員区分(※１)
		//
		// 項目				属性値	意味
		// ※１会員区分		1	会員（弁護士）
		//			2	会員（準会員）
		//			3	会員（沖縄特別会員）
		//			4	会員（沖縄準会員)
		//			5	会員（外国法事務弁護士）
		//			999	その他
		//			0	（空）
		//			（空）	事務局

		$param = array_merge(
			array(
				'token.id'			=> '',		// トークンID
				'uid'				=> '',		// SSOで取得したユーザID（研修サイトのIDではない）
				'mail'				=> '',		// メールアドレス
				'employeenumber'		=> '',		// 登録番号
				'bengoshikai'			=> '',		// 所属弁護士会名 or 所属部署（会員区分と合わせて参照する）
				'bengoshikaicode'		=> '',		// 弁護士会コード（本システム用コードに変換必要）
				'bengoshientrydate'		=> '',		// 登録年月日（yyyymmdd 型）
				'nongaijiopennamefamily'	=> '',		// 公開氏(外字なし)
				'nongaijiopennamefirst'		=> '',		// 公開名(外字なし)
				'membersection'			=> '',		// 会員区分

				// 以下、研修サイト側で追加する値
				'bar_association_id'		=> '0',		// ●弁護士会ID（上記の弁護士会コードから変換した値を格納）
				'student_name'			=> '',		// ●受講者氏名（上記の公開氏名を格納。氏名間半角空白）
				'student_name_kana'		=> '',		// ●受講者氏名カナ※新SSOでは取得不可のため空文字で格納）
				'magazineflag'			=> '1',		// ●メルマガ購読要/不要区分（0:メルマガ拒否 1:メルマガ許可で格納）※新SSOでは取得不可のため1で格納
				'learning_school_id'		=>	$this->config->item('nichibenren_school_id'),	// ●内部処理用学校ID
				'learning_cource_id'		=>	$this->config->item('nichibenren_cource_id'),	// ●内部処理用講座ID
			),
			$param
		);

		// 本システムで必要な値がない場合は、登録・更新ができないためNG
		// 登録番号（値なしはエラー）
		if($param['employeenumber'] == ''){
			return array(
				'status'	=> 'NG',
				'getCode'	=> '',
				'getMessage'	=> 'not found employeenumber (lawyer_number) ',
				'getFile'	=> basename(__FILE__),
				'getLine'	=> __line__,
			);
		}

		// 弁護士会コード
		// ⇒新SSOでは弁護士会コードがなければ無条件で0とする
		// 　0だと現状「日弁連」のbar_association_idを取得することになる
		if($param['bengoshikaicode'] == ''){
			$param['bengoshikaicode'] = 0;
		}

		// 登録年月日
		// ⇒新SSOでは登録年月日がなければ無条件でシステム日付で登録する
		if($param['bengoshientrydate'] == ''){
			$param['bengoshientrydate'] = date("Ymd");
		}elseif( !strtotime(trim($param['bengoshientrydate'])) ){
			return array(
				'status'	=> 'NG',
				'getCode'	=> '',
				'getMessage'	=> 'not date bengoshientrydate (regist_date) ',
				'getFile'	=> basename(__FILE__),
				'getLine'	=> __line__,
			);
		}

		// 新SSOでは登録番号のみをキーに受講者検索
		$update_flag = 0; // 0:INSERT　1:UPDATE
		try{
			$query = $this->db->query(
				' SELECT student.* '.
				'   FROM student'.
				'  WHERE 1=1'.
				'    AND student.status          = 0 '.
				'    AND student.lawyer_number   = ? '.
				' LIMIT 0, 1',
				array(
					$param['employeenumber'],
				)
			);

			if ($query->num_rows() > 0) {
				$update_flag = 1;
			}
		}catch(Exception $e){
			return array(
				'status'	=> 'NG',
				'detail'	=> 'sql-error select_student',
				'getCode'	=> $e->getCode(),
				'getMessage'	=> $e->getMessage(),
				'getFile'	=> $e->getFile(),
				'getLine'	=> $e->getLine(),
			);
		}

		// 氏名を登録
		// ⇒新SSOでは公開氏名だけ取得できるので利用し、カナは取得できないので空文字で登録
		$param['student_name'] = $param['nongaijiopennamefamily']." ".$param['nongaijiopennamefirst'];

		// SSO側所属弁護士会から、システム側弁護士会IDを取得
		$param['bar_association_id'] = $this->_get_bar_association_id($param['bengoshikaicode']);
		if($param['bar_association_id'] == ''){
			return array(
				'status'	=> 'NG',
				'getCode'	=> '',
				'getMessage'	=> 'not found bar_association', // 所属弁護士会が見つからない
				'getFile'	=> basename(__FILE__),
				'getLine'	=> __line__,
			);
		}

		// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得
		$param['bengoshientrydate'] = trim($param['bengoshientrydate']); // 登録年月日の変換（TRIM・yyyy-mm-dd変換）
		if($param['bengoshientrydate'] != ''){
			if(strtotime( $param['bengoshientrydate'] )){
				$param['bengoshientrydate'] = date('Y-m-d', strtotime($param['bengoshientrydate']));
			}
		}
		$temp_param = $this->_get_target_passport($param['bengoshientrydate']);
		$param['presence_passport'] = $temp_param['presence_passport'];
		$param['exp_date_passport'] = $temp_param['exp_date_passport'];
		$param['target_passport']   = $temp_param['target_passport'];
		if($param['presence_passport'] == ''){
			return array(
				'status'	=> 'NG',
				'getCode'	=> '',
				'getMessage'	=> 'not found target_passport', // パスポートが見つからない
				'getFile'	=> basename(__FILE__),
				'getLine'	=> __line__,
			);
		}

		$return_result = array(
			'status'		=> '',
			'detail'		=> '',
			'lawyer_number'		=> '',
			'lawyer_division'	=> '',
			'mail'			=> '',
			'password'		=> '',
			'getCode'		=> '',
			'getMessage'		=> '',
			'getFile'		=> '',
			'getLine'		=> '',
		);

		// 新規登録 or 更新処理
		try{
			if($update_flag == 0){
				/*
				// 新規登録（student・student_lecture）
				$sql = "INSERT INTO student (
					 student_name
					,student_email
					,school_id
					,status
					,update_at
					,regist_at
					,student_name_kana
					,mailmagazine_flg
					,lawyer_number
					,lawyer_division
					,bar_association_id
					,regist_date
					,target_passport
					,presence_passport
					,exp_date_passport
					,sub_auth_ethic_training
					) VALUES(
						?,?,?,?,?,
						?,?,?,?,?,
						?,?,?,?,?,
						?
					)";

				$this->db->trans_begin();

				// INSERT student
				//error_log("INSERT student[".$param['employeenumber'].":".$param['mail']."].");
				$this->db->query(
					$sql, 
					array(
						$param['student_name'],
						$param['mail'],
						$param['learning_school_id'],
						0,
						date('Y/m/d H:i:s'),
						date('Y/m/d H:i:s'),
						$param['student_name_kana'],
						$param['magazineflag'],
						$param['employeenumber'],
						$param['membersection'],
						$param['bar_association_id'],
						$param['bengoshientrydate'],
						$param['target_passport'],
						$param['presence_passport'],
						$param['exp_date_passport'],
						1,
					)
				);
				// 登録したstudent_idを取得
				$prev_id = $this->db->insert_id();

				// DELETE student_lecture
				$this->db->query(
					"DELETE FROM student_lecture WHERE student_id = ?", 
					array(
						$prev_id
					)
				);

				// INSERT student_lecture
				$this->db->query(
					"INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?)", 
					array(
						$prev_id,
						$param['learning_cource_id'],
						date('Y-m-d H:i:s'),
					)
				);
				$this->db->trans_commit();

				$return_result['status']           = 'OK';
				$return_result['detail']           = 'INSERT';
				$return_result['lawyer_number']    = $param['employeenumber'];
				$return_result['lawyer_division']  = $param['membersection'];
				$return_result['mail']             = $param['mail'];
				*/
				$return_result['status']           = 'NG';
				$return_result['detail']           = 'failure insert or update';
				$return_result['lawyer_number']    = $param['employeenumber'];
				$return_result['lawyer_division']  = $param['membersection'];
				$return_result['mail']             = $param['mail'];
				$return_result['getCode']          = "";
				$return_result['getMessage']       = "";
				$return_result['getFile']          = "";
				$return_result['getLine']          = "";
			}else{
				// 更新登録（student）
				/* 20050418 CSVからのみ新規登録更新し、SSOの情報では更新を行わない
				$sql = "UPDATE 
					  student 
					SET 
					  student_name             = ?, 
					  student_email            = ?, 
					  school_id                = ?, 
					  update_at                = ?, 
					  student_name_kana        = ?, 
					  mailmagazine_flg         = ?, 
					  bar_association_id       = ?, 
					  regist_date              = ?, 
					  target_passport          = ?, 
					  sub_auth_ethic_training  = ? 
					WHERE 1=1 
					  AND lawyer_number   = ? ";
				// 対象外 status, regist_at, presence_passport, exp_date_passport, sub_auth_ethic_training
				//error_log("UPDATE student[".$param['employeenumber'].":".$param['mail']."].");
				$this->db->query(
					$sql, 
					array(
						$param['student_name'],
						$param['mail'],
						$param['learning_school_id'],
						date('Y/m/d H:i:s'),
						$param['student_name_kana'],
						$param['magazineflag'],
						$param['bar_association_id'],
						$param['bengoshientrydate'],
						$param['target_passport'],
						1,//sub_auth_ethic_training

						$param['employeenumber'],
					)
				);
				$this->db->trans_commit();
				*/
				$sql = "UPDATE 
					  student 
					SET 
					  update_at                = ?, 
					  regist_date              = ?, 
					  sub_auth_ethic_training  = ? 
					WHERE 1=1 
					  AND lawyer_number   = ? ";
				$this->db->query(
					$sql, 
					array(
						date('Y/m/d H:i:s'),
						$param['bengoshientrydate'],
						1,//sub_auth_ethic_training

						$param['employeenumber'],
					)
				);
				$this->db->trans_commit();

				$return_result['status']           = 'OK';
				$return_result['detail']           = 'UPDATE';
				$return_result['lawyer_number']    = $param['employeenumber'];
				$return_result['lawyer_division']  = $param['membersection'];
				$return_result['mail']             = $param['mail'];
			}
		}catch(Exception $e){
			$return_result['status']           = 'NG';
			$return_result['detail']           = 'failure insert or update';
			$return_result['lawyer_number']    = $param['employeenumber'];
			$return_result['lawyer_division']  = $param['membersection'];
			$return_result['mail']             = $param['mail'];
			$return_result['getCode']          = $e->getCode();
			$return_result['getMessage']       = $e->getMessage();
			$return_result['getFile']          = $e->getFile();
			$return_result['getLine']          = $e->getFile();
		}

		return $return_result;
	}

	//----------------------------------------------
	// 出力用配列変換処理
	//----------------------------------------------
	function _output_data($param){
		// 引数のマージ
		$param = array_merge(
			array(
				'status'		=> '',	// 処理結果（OK or NG）
				'detail'		=> '',	// 処理内容（INSERT or UPDATE）
				'lawyer_number'		=> '',	// 登録番号
				'lawyer_division'	=> '',	// 会員区分
				'mail'			=> '',	// メールアドレス
				'getCode'		=> '',	// 例外コード
				'getMessage'		=> '',	// 例外メッセージ
				'getFile'		=> '',	// 例外が作られたファイルの名前
				'getLine'		=> '',	// 例外が作られた行番号
			),
			$param
		);

		// 出力形式への編集
		$output['result']  = array(
			'status'		=> $param['status'],
			'detail'		=> $param['detail'],
			'lawyer_number'		=> $param['lawyer_number'],
			'lawyer_division'	=> $param['lawyer_division'],
			'mail'			=> $param['mail'],
			'exception'		=> array(
				'getCode'		=> $param['getCode'],
				'getMessage'		=> $param['getMessage'],
				'getFile'		=> $param['getFile'],
				'getLine'		=> $param['getLine'],
			),
		);

		return $output;
	}

	//----------------------------------------------
	// SSO側所属弁護士会から、システム側弁護士会IDを取得
	//----------------------------------------------
	function _get_bar_association_id($bar_association = ''){
		$bar_association_id = '';

		try{
			$query = $this->db->query(
				' SELECT mtb_bar_association.* '.
				'   FROM mtb_bar_association'.
				'  WHERE 1=1'.
				'    AND mtb_bar_association.sso_id = ? '.
				'  LIMIT 0, 1',
				array(
					trim($bar_association),
				)
			);

			if ($query->num_rows() > 0) {
				$bar_association    = $query->row_array(); // 複数はresult_array
				$bar_association_id = $bar_association['id'];
			}
		}catch(Exception $e){
			$bar_association_id = '';
		}

		return $bar_association_id;
	}

	//----------------------------------------------
	// ×登録年月日から、パスポート料金を取得
	// 登録年月日（yyyymmdd）から、パスポートの有無・パスポートの有効期限・パスポート料金を取得
	//----------------------------------------------
	function _get_target_passport($regist_date = ''){
		// 戻り値の初期化
		$result['presence_passport'] = '';		// パスポートの有無（有⇒1、無⇒0）
		$result['exp_date_passport'] = '';		// パスポートの有効期限
		$result['target_passport']   = '';		// パスポート料金

		// 登録年月日の正誤確認
		$regist_date = trim($regist_date);
	  //$regist_date = preg_replace("/[\/]/", "-", $regist_date);
		if($regist_date == ''){
			return $result;
		}else{
			// タイムスタンプ変換確認
			if(!strtotime($regist_date)){
				return $result;
			}

			$regist_date = date('Y-m-d', strtotime($regist_date));

			$array_date = explode("-", $regist_date);

			if(!checkdate($array_date[1], $array_date[2], $array_date[0])){
				return $result;
			}
		}

		// 登録年月日とシステム日付より、年目を計算
		// 登録年月日≦システム日時の場合、１～６年目の計算。
		// 登録年月日＞システム日時の場合、０年目で計算。価格も１～２年目に対応。
		$array_date = explode("-", $regist_date);
		$year_no    = 0;
		if( $regist_date <= date('Y-m-d') ){
			$year_no = 6;
			for( $i=0; $i<=5; $i++ ){
				$start_date = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2],     $array_date[0] + $i));
				if(  $start_date > date('Y-m-d') ){
					$year_no = $i;
					break;
				}
			}
		}

		// 年目に応じての処理
		//                     0～2年目          3～4年目      5年目以降
		// パスポート料金      0円               5000円        10000円
		// パスポート有無      有                無            無
		// パスポート有効期限  登録年月日から    購入月の      購入月の
		//                     2年間             翌年同月末    翌年同月末
		//
		if($year_no < 3){		//3
			// 0～2年目
			$result['presence_passport'] = '1';
			$result['exp_date_passport'] = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2]-1, $array_date[0] + 2));
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '0円';
		}elseif( ($year_no==3) || ($year_no==4)  || ($year_no==5) ){
			// 3～4年目
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '5000円';
		}else{
			// 5年目以降
			$result['presence_passport'] = '0';
			$result['exp_date_passport'] = '0000-00-00';
			$result['target_passport']   = '10000円';
		}

		return $result;
	}

	/**
	 * シングルサインオンに使用するtransactionIdを生成
	 */
	function create_sso_transaction_id(){
		//$_SESSION['sso_transaction_id'] = date("YmdHis").mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
		return date("YmdHis").mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
	}
}
