<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '登録情報変更';

if (!st_login_check()){
	header("Location: /login/login.php");
	exit;
} else {
	$user_id="";
	if( is_numeric($_SESSION["user"]["id"]) && $_SESSION["user"]["id"]!="" ){
		$user_id = trim($_SESSION["user"]["id"]);
	}
}
$template = new Template();
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 会員データ登録時以外にマスタデータを取得
if($_POST['act'] != 'complete'){
	// マスタデータ取得
	//$mtb_country_type = get_mtb_data('mtb_country_type');
	$mtb_pref = get_mtb_data('mtb_pref');
	$mtb_gender = get_mtb_data('mtb_gender');
	$mtb_job = get_mtb_data('mtb_job');
	$mtb_job_type = get_mtb_data('mtb_job_type');
	$mtb_password_question = get_mtb_data('mtb_password_question');
	$mtb_mailmagazine_category = get_mtb_data('mtb_mailmagazine_category');
	$mtb_age = get_mtb_data('mtb_age');
	$mtb_school_grade = get_mtb_data('mtb_school_grade');
	
	$template->assign('start_year', 1901);
	$template->assign('end_year', date('Y')+1);
	//$template->assign('mtb_country_type', $mtb_country_type);
	$template->assign('mtb_pref', $mtb_pref);
	$template->assign('mtb_gender', $mtb_gender);
	$template->assign('mtb_job', $mtb_job);
	$template->assign('mtb_job_type', $mtb_job_type);
	$template->assign('mtb_password_question', $mtb_password_question);
	$template->assign('mtb_mailmagazine_category', $mtb_mailmagazine_category);
	$template->assign('mtb_age', $mtb_age);
	$template->assign('mtb_school_grade', $mtb_school_grade);
}

// 初期表示
if(!isset($_POST['act'])){
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "* ";
	$sql.= "FROM ";
	$sql.= "student ";
	$sql.= "WHERE ";
	$sql.= "student_id='".$user_id."' ";
	$ret = $objDbConnect->query_fetch_arr($sql);
	if( count($ret)>0 ){
		$name = explode(" ", mb_str_replace("　"," ",$ret[0]["student_name"]) );
		$name1 = $name[0];
		$name2 = $name[1];
		$kana = explode(" ", mb_str_replace("　"," ",$ret[0]["student_name_kana"]) );
		$kana1 = $kana[0];
		$kana2 = $kana[1];
		$zip = $ret[0]["zip"];
		$zip1 = substr($zip,0,3);
		$zip2 = substr($zip,3,4);
		$pref_id = $ret[0]["pref"];
		$address1 = $ret[0]["address1"];
		$address2 = $ret[0]["address2"];
		$address3 = $ret[0]["address3"];
		$email = $ret[0]["student_email"];
		$email_conf = $ret[0]["student_email"];
		$_SESSION["mypage.edit.password"] = getRandomString();
		$password = $_SESSION["mypage.edit.password"];
		$password_conf = $_SESSION["mypage.edit.password"];
		$_SESSION["mypage.edit.password_question"] = $ret[0]["password_question"];
		$password_question = $_SESSION["mypage.edit.password_question"];
		$password_answer = "";
		$job = $ret[0]["job"];
		$job_type = $ret[0]["job_type"];
		$school_name = $ret[0]["school_name"];
		$school_grade = $ret[0]["school_grade"];
		$age = $ret[0]["age"];
		$student_no = $ret[0]["student_no"];
		$mail_magazine_flag = trim($ret[0]["mailmagazine_flg"]);
		$mail_magazine = '';
		$arr_mail_magazine = explode(",", $ret[0]["mailmagazine_ids"] );
		if(!is_null($arr_mail_magazine) && is_array($arr_mail_magazine)){
			foreach($arr_mail_magazine as $val){
				$mail_magazine .= $val.',';
				$str_mail_magazine .= $mtb_mailmagazine_category[$val]['name'].',';
			}
		} else {
			$mail_magazine = trim($arr_mail_magazine, 'Array');
		}
		$str_mail_magazine = rtrim($str_mail_magazine, ',');
		$gender = $ret[0]["sex"];

		$arr_input = array(
				'name1' => $name1,
				'name2' => $name2,
				'kana1' => $kana1,
				'kana2' => $kana2,
				'zip' => $zip,
				'zip1' => $zip1,
				'zip2' => $zip2,
				'pref_id' => $pref_id,
				'str_pref' => $mtb_pref[$pref_id]['name'],
				'address1' => $address1,
				'address2' => $address2,
				'address3' => $address3,
				'email' => $email,
				'email_conf' => $email_conf,
				'password' => $password,
				'password_conf' => $password_conf,
				'password_question' => $password_question,
				'str_password_question' => $mtb_password_question[$password_question]['name'],
				'password_answer' => $password_answer,
				'job' => $job,
				'str_job' => $mtb_job[$job]['name'],
				'job_type' => $job_type,
				'str_job_type' => $mtb_job_type[$job_type]['name'],
				'school_name' => $school_name,
				'school_grade' => $school_grade,
				'str_school_grade' => $mtb_school_grade[$school_grade]['name'],
				'age' => $age,
				'str_age' => $mtb_age[$age]['name'],
				'student_no' => $student_no,
				'mail_magazine_flag' => trim($mail_magazine_flag),
				'mail_magazine' => $mail_magazine,
				'arr_mail_magazine' => $arr_mail_magazine,
				'str_mail_magazine' => $str_mail_magazine,
				'gender' => $gender
			);
			//var_dump( $arr_input );
		$template->assign('arr_input', $arr_input);
	} else {
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
	$template->layout_noside('mypage/edit.tpl');
	$objDbConnect->close();
	exit();
// 初期表示以外
} else {
	// 入力値取得
	$arr_input = array();
	$name1 = $_POST["name1"];
	$name2 = $_POST["name2"];
	$kana1 = $_POST["kana1"];
	$kana2 = $_POST["kana2"];
	$zip = '';
	$zip1 = '';
	$zip2 = '';
	$address1 = '';
	$address2 = '';
	$address3 = '';
	$zip = $_POST["zip1"].$_POST["zip2"];
	$zip1 = $_POST["zip1"];
	$zip2 = $_POST["zip2"];
	$pref_id = $_POST["pref_id"];
	$address1 = $_POST["address1"];
	$address2 = $_POST["address2"];
	$address3 = $_POST["address3"];
	$email = $_POST["email"];
	$email_conf = $_POST["email_conf"];
	$password = $_POST["password"];
	$password_conf = $_POST["password_conf"];
	$password_question = $_POST["password_question"];
	$password_answer = $_POST["password_answer"];
	$job = $_POST["job"];
	$job_type = $_POST["job_type"];
	$school_name = $_POST["school_name"];
	$school_grade = $_POST["school_grade"];
	$age = $_POST["age"];
	$student_no = $_POST["student_no"];

	//$country_type = $_POST["country_type"];
	//$zip_overseas = '';
	//$address_overseas1 = '';
	//$address_overseas2 = '';
	//if($country_type == 1){
		//$address4 = $_POST["address4"];
	//} elseif($country_type == 2) {
		//$zip_overseas = $_POST["zip_overseas"];
		//$address_overseas1 = $_POST["address_overseas1"];
		//$address_overseas2 = $_POST["address_overseas2"];
	//}
	//$tel1 = $_POST["tel1"];
	//$tel2 = $_POST["tel2"];
	//$tel3 = $_POST["tel3"];
	//$fax1 = $_POST["fax1"];
	//$fax2 = $_POST["fax2"];
	//$fax3 = $_POST["fax3"];
	//$email_mobile = $_POST["email_mobile"];
	//$email_mobile_conf = $_POST["email_mobile_conf"];
	$gender = $_POST["gender"]; //性別
	//$birth_year = $_POST["birth_year"];
	//$birth_month = $_POST["birth_month"];
	//$birth_day = $_POST["birth_day"];
	/*
	$media = '';
	$arr_media = $_POST["media"];
	if(!is_null($arr_media) && is_array($arr_media)){
		foreach($arr_media as $val){
			$media .= $val.',';
			$str_media .= $mtb_media[$val]['name'].',';
		}
		$media = rtrim($media, ',');
		$str_media = rtrim($str_media, ',');
	} else {
		$media = trim($arr_media, 'Array');
	}
	*/
	$mail_magazine_flag = trim($_POST["mail_magazine_flag"]);
	$mail_magazine = '';
	$arr_mail_magazine = $_POST["mail_magazine"];
	if(!is_null($arr_mail_magazine) && is_array($arr_mail_magazine)){
		foreach($arr_mail_magazine as $val){
			$mail_magazine .= $val.',';
			$str_mail_magazine .= $mtb_mailmagazine_category[$val]['name'].',';
		}
		$mail_magazine = rtrim($mail_magazine, ',');
		$str_mail_magazine = rtrim($str_mail_magazine, ',');
	} else {
		$mail_magazine = trim($arr_mail_magazine, 'Array');
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	// 入力チェック
	$err_msg = array();
	if(cmCheckInput($name1, 'CK_KARA')!=0){
		$err_msg['name1'] = '氏名(姓)を入力してください。';
	}
	if(cmCheckInput($name2, 'CK_KARA')!=0){
		$err_msg['name2'] = '氏名(名)を入力してください。';
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($kana1, 'CK_KARA')!=0 ){
		$err_msg['kana1'] = 'フリガナ(セイ)を入力してください。';
	} else {
		if( cmCheckInput($kana1, 'CK_ZEN_KATAKANA')!=0){
			$err_msg['kana1'] = 'フリガナ(セイ)は全角カタカナで入力してください。';
		}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($kana2, 'CK_KARA')!=0 ){
		$err_msg['kana2'] = 'フリガナ(メイ)を入力してください。';
	} else {
		if( cmCheckInput($kana2, 'CK_ZEN_KATAKANA')!=0){
			$err_msg['kana2'] = 'フリガナ(メイ)は全角カタカナで入力してください。';	
		}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($zip, 'CK_KARA')!=0){
		//$err_msg['zip'] = '郵便番号を入力してください。';
	} else {
		if(cmCheckInput($zip, 'CK_NUM')!=0){
			$err_msg['zip'] = '郵便番号は半角数字で入力してください。';
		} elseif(strlen($zip)!=7){
			$err_msg['zip'] = '郵便番号は7桁の半角数字で入力してください。';
		}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($pref_id, 'CK_KARA')!=0){
		$err_msg['pref_id'] = '住所(都道府県)を入力してください。';
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if( cmCheckInput($email, 'CK_KARA')!=0 ){
		$err_msg['email'] = 'メールアドレスを入力してください。';
	} else {
		if( cmCheckInput($email, 'CK_EMAIL')!=0 ){
			$err_msg['email'] = 'メールアドレスはメールアドレス形式で入力してください。';
		}
		$sql = "SELECT student_id FROM student WHERE status='0' AND student_email='$email' and student_id<>'".$user_id."'";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret){
			$err_msg['email'] = '既に登録済みのメールアドレスです。';
		}
	}
	if( cmCheckInput($email_conf, 'CK_EMAIL')!=0 ){
		$err_msg['email_conf'] = '確認メールアドレスはメールアドレス形式で入力してください。';
	}
	if( $email !== $email_conf ){
		$err_msg['email_conf'] = '確認メールアドレスがメールアドレスと一致しません。';
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if( cmCheckInput($password, 'CK_KARA')!=0 ){
		$err_msg['password'] = 'パスワードを入力してください。';
	} else {
		if(cmCheckInput($password, 'CK_EISUJI')){
			$err_msg['password'] = 'パスワードは半角英数で入力してください。';
		} elseif (cmCheckInput($password_conf, 'CK_EISUJI')){
			$err_msg['password_conf'] = '確認用パスワードは半角英数で入力してください。';
		} elseif ($password !== $password_conf){
			$err_msg['password'] = '確認用パスワードがパスワードと一致しません。';
			$err_msg['password_conf'] = '';
		}
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if( cmCheckInput($password_question, 'CK_KARA')!=0 ){
		$err_msg['password_question'] = 'パスワードを忘れた時のヒント(質問)を選択してください。';
	} else {
	}
	if( cmCheckInput($password_answer, 'CK_KARA')!=0 ){
	} else {
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if( cmCheckInput($gender, 'CK_KARA')!=0 ){
		$err_msg['gender'] = '性別を入力してください。';
	} else {
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($age, 'CK_NUM')!=0){
		$err_msg['age'] = '年代を選択してください。';
	} else {
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	if(cmCheckInput($mail_magazine_flag, 'CK_KARA')!=0 ){
		$err_msg['mail_magazine_flag'] = 'メールマガジンについてを選択してください。';
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++
	/*
	if(cmCheckInput($country_type, 'CK_KARA')){
		$err_msg['country_type'] = '国種別を選択してください。';
	} else {
		// 国内
		if($country_type == 1){
			if(cmCheckInput($zip, 'CK_NUM')){
				$err_msg['zip'] = '住所(郵便番号)は半角数字で入力してください。';
			}
			if(cmCheckInput($pref_id, 'CK_KARA')){
				$err_msg['pref_id'] = '住所(都道府県)を入力してください。';
			}
			if(cmCheckInput($address1, 'CK_KARA')){
				$err_msg['address1'] = '住所(市区町村名)を入力してください。';
			}
			if(cmCheckInput($address2, 'CK_KARA')){
				$err_msg['address2'] = '住所(番地)を入力してください。';
			}
			if(cmCheckInput($address3, 'CK_KARA')){
				$err_msg['address3'] = '住所(ビル名)を入力してください。';
			}
		// 国外
		} elseif($country_type == 2){
			if(cmCheckInput($zip_overseas, 'CK_NUM')){
				$err_msg['zip_overseas'] = '海外住所(郵便番号)は半角数字で入力してください。';
			}
			if(cmCheckInput($address_overseas1, 'CK_KARA')){
				$err_msg['address_overseas1'] = '海外住所(市区町村名)を入力してください。';
			}
			if(cmCheckInput($address_overseas2, 'CK_KARA')){
				$err_msg['address_overseas2'] = '海外住所(番地)を入力してください。';
			}
		}
	}
	*/
	//if(cmCheckInput($tel1, 'CK_NUM')){
	//	$err_msg['tel1'] = '電話番号(市外局番)は半角数字で入力してください。';
	//}
	//if(cmCheckInput($tel2, 'CK_NUM')){
	//	$err_msg['tel2'] = '電話番号(市内局番)は半角数字で入力してください。';
	//}
	//if(cmCheckInput($tel3, 'CK_NUM')){
	//	$err_msg['tel3'] = '電話番号(加入者番号)は半角数字で入力してください。';
	//}
	//if(!cmCheckInput($fax1, 'CK_KARA')){
	//	if(cmCheckInput($fax1, 'CK_NUM')){
	//		$err_msg['fax1'] = 'FAX(市外局番)は半角数字で入力してください。';
	//	}
	//}
	//if(!cmCheckInput($fax2, 'CK_KARA')){
	//	if(cmCheckInput($fax2, 'CK_NUM')){
	//		$err_msg['fax2'] = 'FAX(市内局番)は半角数字で入力してください。';
	//	}
	//}
	//if(!cmCheckInput($fax3, 'CK_KARA')){
	//	if(cmCheckInput($fax3, 'CK_NUM')){
	//		$err_msg['fax3'] = 'FAX(加入者番号)は半角数字で入力してください。';
	//	}
	//}
	//if(!cmCheckInput($email_mobile, 'CK_KARA') || !cmCheckInput($email_mobile_conf, 'CK_KARA')){
	//	if(cmCheckInput($email_mobile, 'CK_EMAIL')){
	//		$err_msg['email_mobile'] = 'メールアドレス(携帯)はメールアドレス形式で入力してください。';
	//	}
	//	if(cmCheckInput($email_mobile_conf, 'CK_EMAIL')){
	//		$err_msg['email_mobile_conf'] = 'メールアドレス確認(携帯)はメールアドレス形式で入力してください。';
	//	}
	//	if($email_mobile !== $email_mobile_conf){
	//		$err_msg['email_mobile'] = 'ご入力いただいたメールアドレス(携帯)が一致しません。';
	//		$err_msg['email_mobile_conf'] = '';
	//	}
	//}
	//if(cmCheckInput($gender, 'CK_KARA')){
	//	$err_msg['gender'] = '性別を選択してください。';
	//}
	//if(cmCheckInput($birth_year, 'CK_KARA')){
	//	$err_msg['birth_year'] = '生年月日(年)を入力してください。';
	//}
	//if(cmCheckInput($birth_month, 'CK_KARA')){
	//	$err_msg['birth_month'] = '生年月日(月)を入力してください。';
	//}
	//if(cmCheckInput($birth_day, 'CK_KARA')){
	//	$err_msg['birth_day'] = '生年月日(日)を入力してください。';
	//}

	$template->assign('err_msg', $err_msg);
	
	// 処理分岐
	switch($_POST['act']){
		// 確認
		case 'confirm':
		// 戻る
		case 'back':
			// 入力値配列作成
			$arr_input = array(
					'name1' => $name1,
					'name2' => $name2,
					'kana1' => $kana1,
					'kana2' => $kana2,
					'zip' => $zip,
					'zip1' => $zip1,
					'zip2' => $zip2,
					'pref_id' => $pref_id,
					'str_pref' => $mtb_pref[$pref_id]['name'],
					'address1' => $address1,
					'address2' => $address2,
					'address3' => $address3,
					'email' => $email,
					'email_conf' => $email_conf,
					'password' => $password,
					'password_conf' => $password_conf,
					'password_question' => $password_question,
					'str_password_question' => $mtb_password_question[$password_question]['name'],
					'password_answer' => $password_answer,
					'job' => $job,
					'str_job' => $mtb_job[$job]['name'],
					'job_type' => $job_type,
					'str_job_type' => $mtb_job_type[$job_type]['name'],
					'school_name' => $school_name,
					'school_grade' => $school_grade,
					'str_school_grade' => $mtb_school_grade[$school_grade]['name'],
					'age' => $age,
					'str_age' => $mtb_age[$age]['name'],
					'str_gender' => $mtb_gender[$gender]['name'],
					'student_no' => $student_no,
					'mail_magazine_flag' => trim($mail_magazine_flag),
					'mail_magazine' => $mail_magazine,
					'arr_mail_magazine' => $arr_mail_magazine,
					'str_mail_magazine' => $str_mail_magazine,
					'gender' => $gender,
					//'media' => $media,
					//'arr_media' => $arr_media,
					//'str_media' => $str_media,
					//'country_type' => $country_type,
					//'zip_overseas' => $zip_overseas,
					//'address_overseas1' => $address_overseas1,
					//'address_overseas2' => $address_overseas2,
					//'tel1' => $tel1,
					//'tel2' => $tel2,
					//'tel3' => $tel3,
					//'fax1' => $fax1,
					//'fax2' => $fax2,
					//'fax3' => $fax3,
					//'email_mobile' => $email_mobile,
					//'email_mobile_conf' => $email_mobile_conf,
					//'birth_year' => $birth_year,
					//'birth_month' => $birth_month,
					//'birth_day' => $birth_day,
					//'str_country_type' => $mtb_country_type[$country_type]['name'],
				);
				//var_dump($arr_input);
				$template->assign('arr_input', $arr_input);
				
			// 入力エラーなし
			if(empty($err_msg)){
				if($_POST['act'] == 'back'){
					$template->layout_noside('mypage/edit.tpl');
					$objDbConnect->close();
					exit();
				} else {
					$template->layout_noside('mypage/edit_confirm.tpl');
					$objDbConnect->close();
					exit();
				}
				
			// 入力エラーあり
			} else {
				$template->assign('err_style', 'style="background-color:#ff8888;"');
				$template->layout_noside('mypage/edit.tpl');
				$objDbConnect->close();
				exit();
				
			}
			
			break;
			
		// 完了
		case 'complete':
			$err_flag = 0;
			
			// 改竄なし
			if(empty($err_msg)){
				$birth = $birth_year.'-'.sprintf("%02d", $birth_month).'-'.sprintf("%02d", $birth_day);
				$regist_date = date('Y-m-d H:i:s');
				
				// トランザクション開始
				$objDbConnect->tran_begin();
				
				$sql = "update student set ";
				$sql.= "  student_name='".mysql_real_escape_string($name1)." ".mysql_real_escape_string($name2)."',";
				$sql.= "  student_name_kana='".mysql_real_escape_string($kana1)." ".mysql_real_escape_string($kana2)."',";
				$sql.= "  zip='".mysql_real_escape_string($zip)."',";
				$sql.= "  pref='".mysql_real_escape_string($pref_id)."',";
				$sql.= "  address1='".mysql_real_escape_string($address1)."',";
				$sql.= "  address2='".mysql_real_escape_string($address2)."',";
				$sql.= "  address3='".mysql_real_escape_string($address3)."',";
				$sql.= "  student_email='".mysql_real_escape_string($email)."',";
//var_dump($_SESSION["mypage.edit.password"]);
//var_dump($password);
				if( $_SESSION["mypage.edit.password"]!=$password ) {
					$sql.= "  student_password_encrypt='".hash('sha256', $password)."',";
				}
//exit();
				$sql.= "  password_question='".mysql_real_escape_string($password_question)."',";
				if( $password_answer!="") {
					$sql.= "  password_answer='".hash('sha256', $password_answer)."',";
				}
				$sql.= "  job='".mysql_real_escape_string($job)."',";
				$sql.= "  job_type='".mysql_real_escape_string($job_type)."',";
				$sql.= "  school_name='".mysql_real_escape_string($school_name)."',";
				$sql.= "  school_grade='".mysql_real_escape_string($school_grade)."',";
				$sql.= "  age='".mysql_real_escape_string($age)."',";
				$sql.= "  sex='".mysql_real_escape_string($gender)."',";
				$sql.= "  student_no='".mysql_real_escape_string($student_no)."',";
				$sql.= "  mailmagazine_flg='".mysql_real_escape_string($mail_magazine_flag)."',";
				$sql.= "  mailmagazine_ids='".mysql_real_escape_string($mail_magazine)."',";
				$sql.= "  update_at='".$regist_date."' ";
				//$sql.= "  '$regist_date'";
				//$sql.= "  media_id='".mysql_real_escape_string($media)."',";
				//$sql.= "  school_id='".SCHOOL_ID."',";
				//$sql.= "  member_type='1',";
				//$sql.= "  '$address1',";
				//$sql.= "  '$address2',";
				//$sql.= "  '$address3',";
				//$sql.= "  '$country_type',";
				//$sql.= "  '$zip_overseas',";
				//$sql.= "  '$address_overseas1',";
				//$sql.= "  '$address_overseas2',";
				//$sql.= "  '$tel1',";
				//$sql.= "  '$tel2',";
				//$sql.= "  '$tel3',";
				//$sql.= "  '$fax1',";
				//$sql.= "  '$fax2',";
				//$sql.= "  '$fax3',";
				//$sql.= "  '$email_mobile',";
				//$sql.= "  '$birth',";
				$sql.= " WHERE ";
				$sql.= " student_id='".$user_id."' ";
//var_dump($sql);
//exit();
				$ret1 = $objDbConnect->execute($sql);
				if(!$ret1){
					$err_flag = 1;
				}
				if ($err_flag){
					$objDbConnect->rollback();
				} else {
					$objDbConnect->commit();
					// ユーザー名をセッションにセット
					$_SESSION['user']['name'] = $name1.' '.$name2;
				}
				
			// 改竄あり
			} else {
				$err_flag = 1;
			}
			
			$template->assign('err_flag', $err_flag);
			$template->layout_noside('mypage/edit_complete.tpl');
			$objDbConnect->close();
			exit;
			
			break;
			
		default:
	}
}

function getRandomString($nLengthRequired = 8){
    $sCharList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    mt_srand();
    $sRes = "";
    for($i = 0; $i < $nLengthRequired; $i++) {
        $sRes .= $sCharList{mt_rand(0, strlen($sCharList) - 1)};
    }
    return $sRes;
}

?>
