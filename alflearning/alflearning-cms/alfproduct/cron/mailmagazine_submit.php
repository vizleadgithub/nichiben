#!/usr/bin/php
<?php
date_default_timezone_set('Asia/Tokyo');
//print(date("Y-m-d H:i:00"));
//exit();
//配信日を確認して、メールの送信を行う。
//cronにて、1時間毎に起動する様、設定してください。
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'配信開始'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
define('FROMADDRESS', 'KENSHUmaster@nichibenren.or.jp');
define('FROMNAME', 'JFBA総合研修サイトメールマガジン');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(  realpath( dirname(__FILE__) ."/../../../../alfproduct/module/DbConnect.php")  );
/* */ //print_r("Test1\n"); /* */
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
/* */ //print_r("Test2\n"); /* */
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = "";
if( isset($argv[1]) && !empty($argv[1]) ){
	$mid = $argv[1];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_mail = array();
$sql = "select * from tbl_mailmagazine where del_flg=0 ";
$where = "";
$where.= " and submit_datetime<='".date("Y-m-d H:i:00")."' ";
$where.= " and submit_status='0' ";
if ($mid != ""){
	$where.= " and mailmagazine_id='".$mid."' ";
}
$arr_mail = $objDbConnect->query_fetch_arr($sql.$where);
/* */ //print_r("\n++++++++++++++++++++++++++++++++++++++++++\n"); /* */
/* */ //var_dump($sql.$where); /* */
/* */ //var_dump($arr_mail); /* */
/* */ //print_r("\n++++++++++++++++++++++++++++++++++++++++++\n"); /* */
if(!$arr_mail){
	//@error_log(date("Y-m-d H:i:s").':'.'配信情報の取得に失敗しました。['.$sql.']'."\n", 3, realpath( dirname(__FILE__)."/logs/" ).'/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
	@error_log(date("Y-m-d H:i:s").':'.'配信情報の取得に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
} else {
	for($i=0;$i<count($arr_mail);$i++){
		$submit_datetime = $arr_mail[$i]["submit_datetime"];
		//$temp_arr = unserialize( $arr_mail[$i]["target_parameter"] );
		//$member_type   = $temp_arr["member_type"];
		//$sex	   = $temp_arr["sex"];
		//$pref	  = $temp_arr["pref"];
		$member_type	= $arr_mail[$i]["target_parameter_member_type"];
		$sex		= $arr_mail[$i]["target_parameter_sex"];
		$pref		= $arr_mail[$i]["target_parameter_pref"];
		$age		= $arr_mail[$i]["target_parameter_age"];
		$job		= $arr_mail[$i]["target_parameter_job"];
		$job_type	= $arr_mail[$i]["target_parameter_job_type"];
		$school_grade	= $arr_mail[$i]["target_parameter_school_grade"];

		$bar_association_id	 = $arr_mail[0]["target_parameter_bar_association_id"];
		$start_regist_date	 = $arr_mail[0]["target_parameter_start_regist_date"];
		$end_regist_date	 = $arr_mail[0]["target_parameter_end_regist_date"];
		$start_lawyer_number	 = $arr_mail[0]["target_parameter_start_lawyer_number"];
		$end_lawyer_number	 = $arr_mail[0]["target_parameter_end_lawyer_number"];
		$post_all		 = $arr_mail[0]["target_parameter_post_all"];

		$mailmagazine_category = explode(",",$arr_mail[$i]["target_parameter_mailmagazine_category"]);
		$mailmagazine_category_sub = trim($arr_mail[$i]["target_parameter_mailmagazine_category"]);



		$school_id       = $arr_mail[$i]["school_id"];
		$submit_datetime = $arr_mail[$i]["submit_datetime"];
		$mail_title      = $arr_mail[$i]["mail_title"];
		$mail_body       = $arr_mail[$i]["mail_body"];
		$sql = "";
		$sql.= "update tbl_mailmagazine set ";
		$sql.= " submit_status='1' ";
		$sql.= " ,submit_datetime='".date("Y-m-d H:i:00")."' ";
		$sql.= " ,update_date='".date("Y-m-d H:i:00")."' ";
		$sql.= " where mailmagazine_id='".mysqli_real_escape_string($objDbConnect->connect, $arr_mail[$i]["mailmagazine_id"])."' ";
		$ret = $objDbConnect->execute($sql);
		if(!$ret){
			//@error_log(date("Y-m-d H:i:s").':'.'更新に失敗しました。['.$sql.']'."\n", 3, realpath( dirname(__FILE__)."/logs/" ).'/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
			@error_log(date("Y-m-d H:i:s").':'.'更新に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
		} else {
			$arr_member = array();
			$sql = "select * from student where student.status=0 and mailmagazine_flg=1 ";
			if( (int)$school_id>=0 ){
				$sql.= " and school_id=".$school_id." ";
			}

			$where = "";
			if( $post_all == "" || $post_all == "0" ){
/*
				//会員種別の条件
				if( $member_type=="2" ){
					$where.= " and student.member_type='1' ";
				} elseif( $member_type=="3" ){
					$where.= " and student.member_type='2' ";
				} else {
				}
				//性別の条件
				if( $sex=="2" ){
					$where.= " and student.sex='1' ";
				} elseif( $sex=="3" ){
					$where.= " and student.sex='2' ";
				} else {
				}
				//都道府県の条件
				if( $pref=="" || $pref=="0" ){
				} else {
					$where.= " and student.pref='".$pref."' ";
				}
				if( $age == "" || $age=="0" ){
				} else {
					$where.= " and student.age='".$age."' ";
				}
				if( $job == "" || $job=="0" ){
				} else {
					$where.= " and student.job='".$job."' ";
				}
				if( $job_type == "" || $job_type=="0" ){
				} else {
					$where.= " and student.job_type='".$job_type."' ";
				}
				if( $school_grade == "" || $school_grade=="0" ){
				} else {
					$where.= " and student.school_grade='".$school_grade."' ";
				}

				//カテゴリの条件
				if (trim($mailmagazine_category_sub)!=""){
					if( 0<count($mailmagazine_category) ){
						$where.= " and (";
						for($n=0;$n<count($mailmagazine_category);$n++){
							if($n>0){ $where.= " or "; }
							$where.= " ( ";
								$where.= " concat(',',student.mailmagazine_ids,',') LIKE '%,".$mailmagazine_category[$n].",%' ";
							$where.= " ) ";
						}
						$where.= " ) ";
					}
				}
*/
				if( $bar_association_id == "" ){
				} else {
					$where.= " and student.bar_association_id='".$bar_association_id."' ";
				}
				if( $start_regist_date != "" ){
					$where.= " and student.regist_date>='".substr($start_regist_date,0,10)."' ";
				}
				if( $end_regist_date != "" ){
					$where.= " and student.regist_date<='".substr($end_regist_date,0,10)."' ";
				}

				if( $start_lawyer_number != "" ){
					$where.= " and student.lawyer_number>='".$start_lawyer_number."' ";
				}
				if( $end_lawyer_number != "" ){
					$where.= " and student.lawyer_number<='".$end_lawyer_number."' ";
				}
			}

			$arr_member = $objDbConnect->query_fetch_arr($sql.$where);
/* */ //print_r("\n++++++++++++++++++++++++++++++++++++++++++\n"); /* */
/* */ //var_dump($sql.$where); /* */
/* */ //var_dump($arr_member); /* */
/* */ //print_r("\n++++++++++++++++++++++++++++++++++++++++++\n"); /* */
			if(!$arr_member){
				//@error_log(date("Y-m-d H:i:s").':'.'配信対象の取得に失敗しました。['.$sql.']'."\n", 3, realpath( dirname(__FILE__)."/logs/" ).'/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
				@error_log(date("Y-m-d H:i:s").':'.'配信対象の取得に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
			} else {
				$to = "";
				$subject = "";
				$body = "";
				$from = "";
				for($n=0;$n<count($arr_member);$n++){
					$to      = $arr_member[$n]["student_email"];
					$subject = str_replace('@name@',$arr_member[$n]["student_name"],$mail_title);
					$body    = str_replace('@name@',$arr_member[$n]["student_name"],$mail_body);
					$body   .= ""."\n";
					$body   .= "「JFBA総合研修サイトは『日本弁護士連合会』が運営しております。"."\n";
					$body   .= "このメールマガジンは KENSHUmaster@nichibenren.or.jp からお送りしていますが、このメールアドレスにご返信いただくことはできません。"."\n";
					$body   .= "お問い合わせはサイト内『お問い合わせ』 迄お願いいたします。"."\n";

					$from    = mb_encode_mimeheader(mb_convert_encoding(FROMNAME,"JIS","UTF-8"))."<".FROMADDRESS.">";
					//@mb_send_mail($to,$subject,$body,"From:".$from);
					//mb_send_mail($to,$subject,$body,"From:".$from,"-fKENSHUmaster@nichibenren.or.jp");
					$add_header = "\n";
					$add_header.= "Reply-to: KENSHUmaster@nichibenren.or.jp\n";
					$add_header.= "X-Mailer: PHP/". phpversion();
					$opt = '-f'.'KENSHUmaster@nichibenren.or.jp';
					$ret_mail = mb_send_mail($to,$subject,$body,"From:".$from.$add_header,$opt);
					sleep(1);

					$sql = "";
					$sql.= "INSERT INTO tbl_mailmagazine_sended ( ";
					$sql.= "mailmagazine_id, ";
					$sql.= "member_id, ";
					$sql.= "member_name, ";
					$sql.= "member_mail, ";
					$sql.= "status ";
					$sql.= ") VALUES ( ";
					$sql.= "'".$arr_mail[$i]["mailmagazine_id"] ."',";
					$sql.= "'".$arr_member[$n]["student_id"] ."',";
					$sql.= "'".$arr_member[$n]["student_name"] ."',";
					$sql.= "'".$arr_member[$n]["student_email"] ."',";
					if($ret_mail){
						$sql.= "'0' ";
					} else {
						$sql.= "'1' ";
					}
					$sql.= ") ";
					$ret = $objDbConnect->execute($sql);
				}
			}
/* */ //print_r("Test3\n"); /* */
		}
		$sql = "";
		$sql.= "update tbl_mailmagazine set ";
		$sql.= " submit_status='2' ";
		$sql.= " ,update_date='".date("Y-m-d H:i:00")."' ";
		$sql.= " ,target_number='".count($arr_member)."' ";
		$sql.= " where mailmagazine_id='".mysqli_real_escape_string($objDbConnect->connect, $arr_mail[$i]["mailmagazine_id"])."' ";
		$ret = $objDbConnect->execute($sql);
		if(!$ret){
			//@error_log(date("Y-m-d H:i:s").':'.'更新に失敗しました。['.$sql.']'."\n", 3, realpath( dirname(__FILE__)."/logs/" ).'/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
			@error_log(date("Y-m-d H:i:s").':'.'更新に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//@error_log(date("Y-m-d H:i:s").':'.'配信終了'."\n", 3, realpath( dirname(__FILE__)."/logs/" ).'/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
@error_log(date("Y-m-d H:i:s").':'.'配信終了'."\n", 3, '/alflearning-data/alfproduct/logs/mailmagazine_submit.log');
exit();
?>
