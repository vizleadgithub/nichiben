<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Openam_sso extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
//	private $url_school_id  = 0;

	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		
		$this->load->library('Curl');
		
	//	$this->allowIP = $this->config->item('*');	//IPアドレス制限
		
		// テスト時使用。
	  //ini_set('display_errors', 'On');
	  //ini_set('log_errors', 'On');
	  //ini_set('error_reporting', E_ALL);
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		$this->load->helper('json');
		
		// 戻り値の初期化
		$result_param = array();
		
		// OpenAM REST API の URL取得
		$openam_server_url = $this->config->item('openam_server_url');
		
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 引数確認：トークンIDがなければ終了
		$post_token_id = "";
		if( !isset($_POST['token_id']) ) {
			if( !isset($_GET['token_id']) ) {
				$result_param = $this->_output_data(array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'not token ID',		// トークンIDなし
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				));
			}else{
				$post_token_id = $_GET['token_id'];
			}
		//	$result_param = $this->_output_data(array(
		//		'status'		=> 'NG',
		//		'getCode'		=> '',
		//		'getMessage'	=> 'not token ID',		// トークンIDなし
		//		'getFile'		=> basename(__FILE__),
		//		'getLine'		=> __line__,
		//	));
		}else{
			$post_token_id = $_POST['token_id'];	//'';
		}
		
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 0.ユーザIDとパスワードでトークンID取得 
		// 本来はpost送信にてトークンIDを取得。テスト用ロジックのため、注意
		// "token.id=AQIC5wM2LY4Sfcw1aL6GcDZbCCMwtOXlMJV4BZneYfCh5RE.*AAJTSQACMDEAAlNLABM0Njc1NzgwNTYwOTgwMzA3NDc5* "
//		$get_token_id = $this->curl->simple_get($openam_server_url.'/identity/authenticate', array(
//			"username"	=> '0000',
//			"password"	=> 'test0000',
//		));
//		if($get_token_id){
//			$temp_array = preg_split("/={1}/", $get_token_id);
//			$post_token_id = trim($temp_array[1]);
//		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// トークンID検証
		if(empty($result_param)){
			$chk_token_id = $this->curl->simple_get($openam_server_url.'/identity/isTokenValid', array(
				"tokenid"			=> $post_token_id,
			));
			if(!$chk_token_id){
				$result_param = $this->_output_data(array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'Invalid token ID',	// 無効なトークンID
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				));
			}
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// トークンIDから属性取得
		$get_attribute = array();
		
		if(empty($result_param)){
			$get_attributes = $this->curl->simple_get($openam_server_url.'/identity/attributes', array(
				"subjectid"			=> $post_token_id,
			));
			if(!$get_attributes){
				$result_param = $this->_output_data(array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'Not attribute',	// 属性がない
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				));
			}else{
				// [確認用]OpenAM API 取得結果を格納
error_log(print_r($get_attributes, 1));
				
				// 空白文字を区切り文字として配列格納
				$temp_get_attributes = preg_split("/\s{1}/", $get_attributes);
				$temp_name = '-';
				
				for ($i = 0; $i< count($temp_get_attributes); $i++) {
					// 【=】を持つ行のみ処理対象
					if(preg_match("/=/", $temp_get_attributes[$i])) {
						
						// userdetails.token.id=・・・
						if(preg_match("/userdetails.token.id=/", $temp_get_attributes[$i])){
							$get_attribute["token.id"] = trim( preg_replace("/userdetails.token.id=/", "", $temp_get_attributes[$i]) );
							continue;
						}
						// userdetails.attribute.name=・・・
						if(preg_match("/userdetails.attribute.name=/", $temp_get_attributes[$i])){
							$temp_name = trim( preg_replace("/userdetails.attribute.name=/", "", $temp_get_attributes[$i]) );
							continue;
						}
						// userdetails.attribute.value=・・・
						// value が複数ある場合、カンマ区切りで追加していく
						if(preg_match("/userdetails.attribute.value=/", $temp_get_attributes[$i])){
							if( isset($get_attribute[$temp_name]) ){
								$get_attribute[$temp_name] = $get_attribute[$temp_name].",".trim( preg_replace("/userdetails.attribute.value=/", "", $temp_get_attributes[$i]) );
							}else{
								$get_attribute[$temp_name] = trim( preg_replace("/userdetails.attribute.value=/", "", $temp_get_attributes[$i]) );
							}
							continue;
						}
					}
				}
			}
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------

// 属性情報の確認
//print var_dump($get_attribute);
//print "<br/>";
//exit();

		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 分解した属性から受講者新規登録・更新処理
		if(empty($result_param)){
			// [TEST]事務局-動作確認用
			/*
			unset($get_attribute['membersection']);      // 会員区分 削除	
			unset($get_attribute['bengoshikaicode']);    // 弁護士会コード 削除	
			unset($get_attribute['bengoshientrydate']);  // 登録年月日 削除	

			$get_attribute['employeenumber']         = '623123';                    // 登録番号（00：日弁連　23：福井　09：群馬）
			$get_attribute['mail']                   = 'takasumi3@alfredcore.com';  // mail
			$get_attribute['sn']                     = '四万十';                    // 氏
			$get_attribute['cn']                     = '七五三太';                  // 名
			$get_attribute['namefamilyruby']         = 'しまんと';                  // 氏(かな)
			$get_attribute['namefirstruby']          = 'しめた';                    // 名(かな)
			$get_attribute['nongaijiopennamefamily'] = '高津';                      // 公開氏(外字なし)
			$get_attribute['nongaijiopennamefirst']  = '幸次郎';                    // 公開名(外字なし)
			$get_attribute['opennamefamilyruby']     = 'たかつ';                    // 公開氏(かな)
			$get_attribute['opennamefirstruby']      = 'こうじろう';                // 公開名(かな)
			*/
			$result_param = $this->_output_data( $this->_update_student($get_attribute) );
		}
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------


		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
		// 結果出力（JSON形式）
		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output( json_encode($result_param) );
		// ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ---------- ----------
	}

	//----------------------------------------------
	// 受講者の新規登録・更新
	// [2013/10/08] パスワードを固定から、API取得のパスワードを使用。ただし、hash変換はしない。
	// [2013/11/20] [事務員対応] 弁護士以外（事務員）のための対応
	//----------------------------------------------
	function _update_student($param){
// [確認用]
error_log("start _update_student.");

		//引数設定（○が必ずある値　※弁護士限定）
		// 公開氏名と氏名は、公開氏名を優先登録。
		// 登録番号・弁護士会コード・登録年月日・会員区分いずれかがない場合は、ＮＧとする（左項目がない＝弁護士でないため）
		//
		//	["token.id"]				=> ○トークンID
		//	["uid"]						=> ○ユーザID
		//	["mail"]					=> ○メールアドレス
		//	["userpassword"]			=> ○パスワード
		//	["sn"]						=> ○氏
		//	["cn"]						=> ○名
		//	["destinationIndicator"]	=> ○認証サイトコード(※１)
		//	["employeenumber"]			=> 　登録番号
		//	["bengoshikai"]				=> 　所属弁護士会名
		//	["bengoshikaicode"]			=> 　弁護士会コード
		//	["bengoshientrydate"]		=> 　登録年月日
		//	["namefamilyruby"]			=> ○氏(かな)
		//	["namefirstruby"]			=> ○名(かな)
		//	["nongaijiopennamefamily"]	=> 　公開氏(外字なし)
		//	["nongaijiopennamefirst"]	=> 　公開名(外字なし)
		//	["opennamefamilyruby"]		=> 　公開氏(かな)
		//	["opennamefirstruby"]		=> 　公開名(かな)
		//	["magazineflag"]			=> ○メルマガ購読要/不要区分(※２)
		//	["membersection"]			=> 　会員区分(※３)
		//
		// 項目								属性値	意味
		// ※１ 認証サイトコード			1		会員専用ページ
		//									2		旧会員専用ページ
		//									3		研修総合サイト
		//									4		会員専用ページ、旧会員専用ページ
		//									5		会員専用ページ、研修総合サイト
		//									6		旧会員専用ページ、研修総合サイト
		//									7		会員専用ページ、旧会員専用ページ、研修総合サイト
		// ※ ２メルマガ購読要/不要区分		0		メルマガ購読不要
		//									1		メルマガ購読要
		// ※ ３会員区分					1		会員（弁護士）
		//									2		会員（準会員）
		//									3		会員（沖縄特別会員）
		//									4		会員（沖縄準会員)
		//									5		会員（外国法事務弁護士）
		$param = array_merge(
			array(
				'token.id'					=> '',		//
				'uid'						=> '',		//
				'mail'						=> '',		// メールアドレス
				'userpassword'				=> '',		// パスワード
				'sn'						=> '',		// 氏
				'cn'						=> '',		// 名
				'destinationIndicator'		=> '',		//
				'employeenumber'			=> '',		// 登録番号
				'bengoshikai'				=> '',		//
				'bengoshikaicode'			=> '',		// 弁護士会コード（本システム用コードに変換必要）
				'bengoshientrydate'			=> '',		// 登録年月日（yyyymmdd 型）
				'namefamilyruby'			=> '',		// 氏(かな)
				'namefirstruby'				=> '',		// 名(かな)
				'nongaijiopennamefamily'	=> '',		// 公開氏(外字なし)
				'nongaijiopennamefirst'		=> '',		// 公開名(外字なし)
				'opennamefamilyruby'		=> '',		// 公開氏(かな)
				'opennamefirstruby'			=> '',		// 公開名(かな)
				'magazineflag'				=> '0',		// メルマガ購読要/不要区分（0:メルマガ拒否 1:メルマガ許可 で格納）
				'membersection'				=> '',		// 会員区分（[事務員対応]'0' から '' に変更）
				
				'bar_association_id'		=> '0',		// ●弁護士会ID（上記弁護士会コードを変換したID）
				'student_name'				=> '',		// ●受講者氏名（上記の公開氏名・氏名どちらかを格納。氏名間半角空白）
				'student_name_kana'			=> '',		// ●受講者氏名カナ（上記の公開氏名かな・氏名かなどちらかを格納。氏名間半角空白）
				'learning_school_id'		=>	$this->config->item('nichibenren_school_id'),				// ●内部処理用学校ID
				'learning_cource_id'		=>	$this->config->item('nichibenren_cource_id'),				// ●内部処理用講座ID
			//	'learning_password'			=>	$this->config->item('nichibenren_student_password'),		// ●内部処理用パスワード
			),
			$param
		);
		
		// 本システムで必要な値がない場合は、登録・更新ができないためNG
		//   登録番号（値なしはエラー）
		if($param['employeenumber'] == ''){
			return array(
				'status'		=> 'NG',
				'getCode'		=> '',
				'getMessage'	=> 'not found employeenumber (lawyer_number) ',
				'getFile'		=> basename(__FILE__),
				'getLine'		=> __line__,
			);
		}
		//   会員区分
		//   [事務員対応]処理不要（値あり、そのまま。値なし、文字列ゼロ）
	//	if($param['membersection'] == ''){
	//		return array(
	//			'status'		=> 'NG',
	//			'getCode'		=> '',
	//			'getMessage'	=> 'not found membersection (lawyer_division) ',
	//			'getFile'		=> basename(__FILE__),
	//			'getLine'		=> __line__,
	//		);
	//	}
		//   弁護士会コード
		//   [事務員対応] 値なし、且つ、登録番号が６桁、且つ会員区分値なしの場合、登録番号２桁目～３桁目を使用。
		//   値なし、且つ、上記条件以外はエラー。
		if($param['bengoshikaicode'] == ''){
			if( (mb_strlen(trim($param['employeenumber'])) == 6) && ($param['membersection'] == '') ){
				$temp_code = (int)substr($param['employeenumber'], 1, 2);	// 2～3桁目取得
				$param['bengoshikaicode'] = (string)$temp_code;
			}else{
				return array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'not found bengoshikaicode (bar_association_id) ',
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				);
			}
		}
		//   登録年月日
		//   [事務員対応] 値なし、且つ、登録番号が６桁、且つ会員区分値なしの場合システム日付（yyyymmdd型）を使用。
		//   値なし、且つ、上記条件以外はエラー。
		//   値あり、且つ、タイムスタンプ変換できない場合はエラー。
		if($param['bengoshientrydate'] == ''){
			if( (mb_strlen(trim($param['employeenumber'])) == 6) && ($param['membersection'] == '') ){
				$param['bengoshientrydate'] = date("Ymd");
			}else{
				return array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'not found bengoshientrydate (regist_date) ',
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				);
			}
		}elseif( !strtotime(trim($param['bengoshientrydate'])) ){
				return array(
					'status'		=> 'NG',
					'getCode'		=> '',
					'getMessage'	=> 'not date bengoshientrydate (regist_date) ',
					'getFile'		=> basename(__FILE__),
					'getLine'		=> __line__,
				);
		}
		
		// 登録番号・会員番号をキーに受講者検索
		$update_flag = 0;	// 0:INSERT　1:UPDATE
		
		try{ 
			$query = $this->db->query(
				' SELECT student.* '.
				'   FROM student'.
				'  WHERE 1=1'.
				'    AND student.status          = 0 '.
				'    AND student.lawyer_number   = ? '.
				'    AND student.lawyer_division = ? '.
				' LIMIT 0, 1',
				array(
					$param['employeenumber'],
					$param['membersection'],
				)
			);
			
			if ($query->num_rows() > 0) {
				$update_flag = 1;
			}
		}catch(Exception $e){ 
			return array(
				'status'		=> 'NG',
				'detail'		=> 'sql-error select_student',
				'getCode'		=> $e->getCode(),
				'getMessage'	=> $e->getMessage(),
				'getFile'		=> $e->getFile(),
				'getLine'		=> $e->getLine(),
			);
		}
		
		// 登録する氏名の選別（公開氏名 or 氏名）
		if( ($param['nongaijiopennamefamily'] != '') && ($param['nongaijiopennamefirst'] != '') && ($param['opennamefamilyruby'] != '') && ($param['opennamefirstruby'] != '') ){
			$param['student_name']      = $param['nongaijiopennamefamily']." ".$param['nongaijiopennamefirst'];
			$param['student_name_kana'] = $param['opennamefamilyruby']." ".$param['opennamefirstruby'];
		}else{
			$param['student_name']      = $param['sn']." ".$param['cn'];
			$param['student_name_kana'] = $param['namefamilyruby']." ".$param['namefirstruby'];
		}
		
		// SSO側所属弁護士会から、システム側弁護士会IDを取得
		$param['bar_association_id'] = $this->_get_bar_association_id($param['bengoshikaicode']);
		if($param['bar_association_id'] == ''){
			return array(
				'status'		=> 'NG',
				'getCode'		=> '',
				'getMessage'	=> 'not found bar_association',		// 所属弁護士会が見つからない
				'getFile'		=> basename(__FILE__),
				'getLine'		=> __line__,
			);
		}
		
		
		// 登録年月日から、パスポートの有無・パスポートの有効期限・パスポート料金を取得	
	  //$param['target_passport'] = $this->_get_target_passport($param['bengoshientrydate']);
		$param['bengoshientrydate'] = trim($param['bengoshientrydate']);	// 登録年月日の変換（TRIM・yyyy-mm-dd変換）
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
				'status'		=> 'NG',
				'getCode'		=> '',
				'getMessage'	=> 'not found target_passport',		// パスポートが見つからない
				'getFile'		=> basename(__FILE__),
				'getLine'		=> __line__,
			);
		}



/*
return array(
	'status'		=> 'test',
	'detail'		=> $update_flag,
	'lawyer_number'	=> $param['bar_association_id'],
	'mail'			=> $param['target_passport'],
	'password'		=> '',
	'getCode'		=> '',
	'getMessage'	=> '',
	'getFile'		=> '',
	'getLine'		=> '',
);
*/
//exit();

		$return_result = array(
			'status'			=> '',
			'detail'			=> '',
			'lawyer_number'		=> '',
			'lawyer_division'	=> '',
			'mail'				=> '',
			'password'			=> '',
			'getCode'			=> '',
			'getMessage'		=> '',
			'getFile'			=> '',
			'getLine'			=> '',
		);
		
		// 新規登録 or 更新処理
		try{ 
			if($update_flag == 0){
			// 新規登録（student・student_lecture）
				$sql = "INSERT INTO student (
							 student_name
							,student_email
							,student_password
							,student_password_encrypt
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
						) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				
				$this->db->trans_begin();
				
				// INSERT student
error_log("INSERT student[".$param['employeenumber'].":".$param['mail']."].");
				$this->db->query($sql, array(
									$param['student_name'],
									$param['mail'],
									$param['userpassword'],						// hash('sha256',$param['learning_password']),
									hash('sha256',$param['userpassword']),
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
									0,
								));
				// 登録したstudent_idを取得
				$prev_id = $this->db->insert_id();

				// DELETE student_lecture
				$this->db->query("DELETE FROM student_lecture WHERE student_id = ?", array(
									$prev_id
								));

				// INSERT student_lecture
				$this->db->query("INSERT INTO student_lecture (student_id, cource_id, update_at) VALUES (?,?,?)", array(
									$prev_id,
									$param['learning_cource_id'],
									date('Y-m-d H:i:s'),
								));
				
				$this->db->trans_commit();
				
				$return_result['status']           = 'OK';
				$return_result['detail']           = 'INSERT';
				$return_result['lawyer_number']    = $param['employeenumber'];
				$return_result['lawyer_division']  = $param['membersection'];
				$return_result['mail']             = $param['mail'];
				$return_result['password']         = $param['userpassword'];			// $param['learning_password'];
			}else{
			// 更新登録（student）
				$sql = "UPDATE student
						SET student_name             = ?,
							student_email            = ?,
							student_password         = ?,
							student_password_encrypt = ?,
							school_id                = ?,
							update_at                = ?, 
							student_name_kana        = ?, 
							mailmagazine_flg         = ?, 
							bar_association_id       = ?, 
							regist_date              = ?, 
							target_passport          = ?
						WHERE 1=1
						  AND lawyer_number   = ? 
						  AND lawyer_division = ? ";
						// 対象外 status, regist_at, presence_passport, exp_date_passport, sub_auth_ethic_training
error_log("UPDATE student[".$param['employeenumber'].":".$param['mail']."].");
				$this->db->query($sql, array(
									$param['student_name'],
									$param['mail'],
									$param['userpassword'],								// hash('sha256',$param['learning_password']),
									hash('sha256',$param['userpassword']),
									$param['learning_school_id'],
									date('Y/m/d H:i:s'),
									$param['student_name_kana'],
									$param['magazineflag'],
									$param['bar_association_id'],
									$param['bengoshientrydate'],
									$param['target_passport'],
									
									$param['employeenumber'],
									$param['membersection'],
								));
				$this->db->trans_commit();
				
				$return_result['status']           = 'OK';
				$return_result['detail']           = 'UPDATE';
				$return_result['lawyer_number']    = $param['employeenumber'];
				$return_result['lawyer_division']  = $param['membersection'];
				$return_result['mail']             = $param['mail'];
				$return_result['password']         = $param['userpassword'];			// $param['learning_password'];
			}
		}catch(Exception $e){ 
			$return_result['status']           = 'NG';
			$return_result['detail']           = 'failure insert or update';
			$return_result['lawyer_number']    = $param['employeenumber'];
			$return_result['lawyer_division']  = $param['membersection'];
			$return_result['mail']             = $param['mail'];
			$return_result['password']         = $param['userpassword'];			// $param['learning_password'];
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
				'status'			=> '',		// 処理結果（OK or NG）
				'detail'			=> '',		// 処理内容（INSERT or UPDATE）
				'lawyer_number'		=> '',		// 登録番号
				'lawyer_division'	=> '',		// 会員区分
				'mail'				=> '',		// メールアドレス
			//	'password'			=> '',		// ログインパスワード
				'getCode'			=> '',		// 例外コード
				'getMessage'		=> '',		// 例外メッセージ
				'getFile'			=> '',		// 例外が作られたファイルの名前
				'getLine'			=> '',		// 例外が作られた行番号
			),
			$param
		);
		
		// 出力形式への編集
		$output['result']  = array(
			'status'			=> $param['status'],
			'detail'			=> $param['detail'],
			'lawyer_number'		=> $param['lawyer_number'],
			'lawyer_division'	=> $param['lawyer_division'],
			'mail'				=> $param['mail'],
		//	'password'			=> $param['password'],
			'exception'			=> array(
				'getCode'		=> $param['getCode'],
				'getMessage'	=> $param['getMessage'],
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
				$bar_association    = $query->row_array();		// 複数はresult_array
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
			//	$end_date   = date('Y-m-d', mktime(0, 0, 0, $array_date[1], $array_date[2], $array_date[0] + $i + 1));
				
			//	if( ($start_date <= date('Y-m-d')) && (date('Y-m-d') < $end_date) ){
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
		}elseif( ($year_no==3) || ($year_no==4)  || ($year_no==5) ){	//3 4
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
}


