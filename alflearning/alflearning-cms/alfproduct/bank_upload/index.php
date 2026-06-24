<?php
ini_set( 'display_errors', 1 ); 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ファイルアップロード処理開始
$file_form_name = '';
$new_name = "";
$err_msg = "";
$upload_dir_name = "";
if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if (isset($_FILES["csv_upload"])){
		if ($_FILES["csv_upload"]["error"] === 0){
		} elseif ($_FILES["csv_upload"]["error"] === 1){
			$err_msg = 'アップロードされたファイルは、php.ini の upload_max_filesize ディレクティブの値を超えています。';
		} elseif ($_FILES["csv_upload"]["error"] === 2){
			$err_msg = 'アップロードされたファイルは、HTML フォームで指定された MAX_FILE_SIZE を超えています。';
		} elseif ($_FILES["csv_upload"]["error"] === 3){
			$err_msg = 'アップロードされたファイルは一部のみしかアップロードされていません。 ';
		} elseif ($_FILES["csv_upload"]["error"] === 4){
			$err_msg = 'ファイルはアップロードされませんでした。 ';
		} elseif ($_FILES["csv_upload"]["error"] === 6){
			$err_msg = 'テンポラリフォルダがありません。';
		} elseif ($_FILES["csv_upload"]["error"] === 7){
			$err_msg = 'ディスクへの書き込みに失敗しました。';
		} elseif ($_FILES["csv_upload"]["error"] === 8){
			$err_msg = '拡張モジュールがファイルのアップロードを中止しました。';
		} else {
			$err_msg = 'ファイルのアップロードに失敗しました。';
		}
	}


	// アップロードされたファイルかどうかの確認
	if (is_uploaded_file($_FILES["csv_upload"]['tmp_name'])){
		$upload_dir_name ="/alflearning-data/alfproduct/csv_upload";
		$file_name        = $_FILES["csv_upload"]['tmp_name'];
		$file_name_before = $_FILES["csv_upload"]['name'];

		// 拡張子チェック
		$extension = substr(strrchr($file_name_before, '.') ,1);
		$new_name = date("YmdHis").uniqid(rand()).'.'.$extension;

		if ($extension == 'csv'){
			// tmpより指定ディレクトリにファイルを保存
			if (move_uploaded_file($file_name, "$upload_dir_name/$new_name")){
			} else {
				$err_msg = 'ファイルのアップロードに失敗しました。';
			}
		} else {
			$err_msg = 'CSV形式のファイルをアップロードしてください。';
		}
	} else {
		$err_msg = 'ファイルをアップロードしてください。';
	}
} else {
	$err_msg = 'ファイルをアップロードしてください。';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$file = $upload_dir_name.'/'.$new_name;
$csv  = array();
if( $err_msg=="" ){
	$fp   = fopen($file, "r");
	$i = 1;
	while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
		// -----ヘッダ行を飛ばす-----
		if ($i == 1){
			$i++;
			continue;
		}
		// --------------------------
		if( count($data)==4 ){
			@mb_convert_variables('UTF-8', 'SJIS',$data);
			//========================================
			$data["order_no"] = "";
			if( $data[0]=="" ){
				$err_msg .= '<br>'.$i.'行目：注文Noが記載されていません。';
			} else {
				//if(  is_numeric( $data[0] )  ){
					$data["order_no"] = $data[0];
				//} else {
				//	$err_msg .= '<br>'.$i.'行目：注文Noは半角数字で記載してください。';
				//}
			}
			//========================================
			$data["product_id"] = "";
			if( $data[1]=="" ){
				$err_msg .= '<br>'.$i.'行目：商品IDが記載されていません。';
			} else {
				if(  is_numeric( $data[1] )  ){
					$data["product_id"] = $data[1];
				} else {
					$err_msg .= '<br>'.$i.'行目：商品IDは半角数字で記載してください。';
				}
			}
			//========================================
			$data["pay_total"] = "";
			if( $data[2]=="" ){
				$err_msg .= '<br>'.$i.'行目：振込金額が記載されていません。';
			} else {
				if(  is_numeric( $data[2] )  ){
					$data["pay_total"] = $data[2];
				} else {
					$err_msg .= '<br>'.$i.'行目：振込金額は半角数字で記載してください。';
				}
			}
			//========================================
			$data["receipt_date"] = "";
			if( $data[3]=="" ){
				$err_msg .= '<br>'.$i.'行目：入金日が記載されていません。';
			} else {
				$arr_date = explode("|", str_replace(":", "|", str_replace(" ", "|", str_replace("-", "|", str_replace("/", "|", trim($data[3]))))) );
				if( checkdate($arr_date[1], $arr_date[2], $arr_date[0]) ){
					$data["receipt_date"] = $arr_date[0]."-".$arr_date[1]."-".$arr_date[2]."";
				} else {
					$err_msg .= '<br>'.$i.'行目：入金日は「YYYY/MM/DD」の形式で記載してください。';
				}
			}
			//========================================
			$data["order_id"] = "";
			$data["student_name"] = "";
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "tbl_order.order_id,tbl_order.payment_type,student.student_name ";
			$sql.= "FROM ";
			$sql.= "tbl_order ";
			$sql.= "LEFT JOIN student ON tbl_order.member_id=student.student_id ";
			$sql.= "WHERE ";
			$sql.= "tbl_order.order_no='".$data["order_no"]."' ";
			$sql.= " OR lpad(tbl_order.order_no, 13, '0')='".$data["order_no"]."' ";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if( count($ret)>0 ){
				$data["order_id"] = $ret[0]["order_id"];
				$data["student_name"] = $ret[0]["student_name"];
				if($ret[0]["payment_type"]=="12"){
				} else {
					$err_msg .= '<br>'.$i.'行目：該当する注文は銀行振込ではありません。';
				}
			} else {
				$err_msg .= '<br>'.$i.'行目：該当する注文Noが存在しません。';
			}
			//========================================
			$data["order_detail_id"] = "";
			$data["product_name"] = "";
			$data["product_id"] = "";
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "tbl_order_detail.order_detail_id, tbl_order_detail.product_id, tbl_order_detail.pay_total,tbl_product.product_id,tbl_product.product_name ";
			$sql.= "FROM ";
			$sql.= "tbl_order_detail ";
			$sql.= "LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
			$sql.= "WHERE ";
			$sql.= "tbl_order_detail.order_id='".$data["order_id"]."' ";
			$sql.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
			$sql.= " AND tbl_order_detail.product_id='".$data[1]."' ";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if( count($ret)>0 ){
				$data["order_detail_id"] = $ret[0]["order_detail_id"];
				$data["product_name"] = $ret[0]["product_name"];
				$data["product_id"] = $ret[0]["product_id"];
				if($ret[0]["pay_total"]==$data[2]){
				} else {
					$err_msg .= '<br>'.$i.'行目：該当する注文金額と振込金額が一致しません。';
				}
			} else {
				$err_msg .= '<br>'.$i.'行目：該当する商品注文が存在しません。';
			}
			//========================================
			$data["take_date"] = date("Y-m-d");
			//========================================
			$csv[] = $data;
		}
		$i += 1;
	}
	fclose($fp);
	//var_dump($csv);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$update_count = 0;
$ok_msg = "";
if( $err_msg=="" ){
	// メールテンプレート取得
	$str_mail = file_get_contents( "/srv/alfproduct/module/order8.mail" );
	
	$objDbConnect->tran_begin();
	for($i=0;$i<count($csv);$i++){
		$order_detail_id = $csv[$i]["order_detail_id"];
		$receipt_date = $csv[$i]["receipt_date"];
		$take_date = $csv[$i]["take_date"];
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order_detail ";
		$sql.= "SET ";
		$sql.= "payment_status='2', ";//商品種別(0:未入金　1:入金待ち　2:入金済み　9:キャンセル)
		$sql.= "payment_date='".date("Y-m-d H:i:s")."', ";//支払日
		$sql.= "receipt_date='".$receipt_date." 00:00:00"."', ";//入金日
		$sql.= "take_date='".$take_date." 00:00:00"."', ";//取り込み日（権限付与日）
		$sql.= "update_date='".date("Y-m-d H:i:s")."' ";//更新日
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
		$ret = $objDbConnect->execute($sql.$where);

		if( !$ret ){
			$err_msg .= '<br>'.($i+1).'行目：銀行振込の取り込みに失敗しました。';
			//$objDbConnect->rollback();
		} else {
			// パスポート商品の場合は、studentテーブルのデータを更新する
			$sql2 = "SELECT member_id, product_type_add FROM tbl_order_detail WHERE order_detail_id = '$order_detail_id'";
			$ret_sql2 = $objDbConnect->query_fetch_arr($sql2);
			if ($ret_sql2){
				if ($ret_sql2[0]['product_type_add'] == '4'){
					if ($ret_sql2[0]['member_id'] != ''){
						$now_date = date('Y-m-d');
						//--------------------------------------------
						$sql_passport = "SELECT exp_date_passport, presence_passport FROM student WHERE student_id = '".$ret_sql2[0]['member_id']."' AND exp_date_passport>='".date('Y-m-d')."' ";
						$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
						$arr_passport = array();
						foreach ($ret_passport as $key => $val){
							$arr_passport = $val;
						}
						// パスポート更新の場合は、有効期限の起算日を＋1日して算出する
						$update_flg = false;
						if( count($arr_passport)>0 ){
							if ($arr_passport["presence_passport"] == '1'){
								if ( strtotime($arr_passport["exp_date_passport"]) >= strtotime($now_date) ){
									$now_date = $arr_passport["exp_date_passport"];
									$update_flg = true;
								}
							}
						}
						//--------------------------------------------
						$arr_now_date = explode('-', $now_date);
						if ($update_flg){
							$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1], $arr_now_date[2] + 1, $arr_now_date[0] + EXP_DATE_PASSPORT_YEAR));
						} else {
							$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1], $arr_now_date[2], $arr_now_date[0] + EXP_DATE_PASSPORT_YEAR));
						}
						$arr_now_date = explode('-', $exp_date_passport);
						$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1] + 1, 0, $arr_now_date[0]));
						$sql_update_student = "UPDATE student SET presence_passport = '1', exp_date_passport = '".$exp_date_passport."' WHERE student_id = '".$ret_sql2[0]['member_id']."'";
						$objDbConnect->execute($sql_update_student);
					}
				}
			}
			
			// -----------------------------
			// ステータス変更完了メール送信
			// -----------------------------
			$arr_mail = array();
			$sql = "
			SELECT
			  T2.student_name,
			  T2.student_email,
			  T3.product_name
			FROM
			  tbl_order_detail AS T1
			    INNER JOIN
			  student AS T2
			      ON T1.member_id = T2.student_id
			    INNER JOIN
			  tbl_product AS T3
			      ON T1.product_id = T3.product_id
			WHERE
			  T1.order_detail_id = '$order_detail_id'
			  AND T3.del_flg = 0
			  AND ( (T3.start_date<='".date("Y-m-d")."' and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date<='".date("Y-m-d")."' and T3.end_date IS NULL)  OR  (T3.start_date IS NULL and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date IS NULL and T3.end_date IS NULL)  )
			";
			$arr_mail = $objDbConnect->query_fetch_arr($sql);
			if ($arr_mail){
				$name = $arr_mail[0]["student_name"];
				$tomail = $arr_mail[0]["student_email"];
				$product_list = $arr_mail[0]["product_name"]."\n";
				
				$temp_mail = $str_mail;
				$temp_mail = str_replace( '@@name@@', $name, $temp_mail );
				$temp_mail = str_replace( '@@product_list@@', $product_list, $temp_mail );
				
				mb_language("japanese");
				mb_internal_encoding("UTF-8");
				define('FROMADDRESS', 'KENSHUmaster@nichibenren.or.jp');
				define('FROMNAME', '日本弁護士連合会');
				$to      = $tomail;
				$subject = str_replace('@@name@@',$name,'【日本弁護士連合会】ご購入ありがとうございます。');
				$body    = $temp_mail;
				$from    = mb_encode_mimeheader(mb_convert_encoding(FROMNAME,"JIS","UTF-8"))."<".FROMADDRESS.">";
				//@mb_send_mail($to,$subject,$body,"From:".$from);
				//mb_send_mail($to,$subject,$body,"From:".$from,"-fKENSHUmaster@nichibenren.or.jp");
				$add_header = "\n";
				//$add_header.= "Bcc: KENSHUmaster@nichibenren.or.jp\n";
				$add_header.= "Reply-to: KENSHUmaster@nichibenren.or.jp\n";
				$add_header.= "X-Mailer: PHP/". phpversion();
				$opt = '-f'.'KENSHUmaster@nichibenren.or.jp';
				$ret_mail = mb_send_mail($to,$subject,$body,"From:".$from.$add_header,$opt);
			}
			
			// -----------------------------
			// tbl_orderの更新
			// -----------------------------
			//----------------------------------------
			$order_id = $csv[$i]["order_id"];
			//----------------------------------------
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "tbl_order_detail.payment_status ";
			$sql.= "FROM ";
			$sql.= "tbl_order_detail ";
			$where = "";
			$where.= "WHERE ";
			$where.= " tbl_order_detail.order_id='".$order_id."' ";
			$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
			$all_cancel = true;
			for($j=0;$j<count($arr_temp);$j++){
				if($arr_temp[$j]["payment_status"]!="9"){
					$all_cancel = false;
				}
			}
			if( $all_cancel == true ){
				$sql = "";
				$sql.= "UPDATE ";
				$sql.= "tbl_order ";
				$sql.= "SET ";
				$sql.= "tbl_order.payment_status='9', ";
				$sql.= "tbl_order.payment_date=null ";
				$where = "";
				$where.= "WHERE ";
				$where.= " tbl_order.order_id='".$order_id."' ";
				$ret = $objDbConnect->execute($sql.$where);
			}
			//----------------------------------------
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "tbl_order_detail.payment_status ";
			$sql.= "FROM ";
			$sql.= "tbl_order_detail ";
			$where = "";
			$where.= "WHERE ";
			$where.= " tbl_order_detail.order_id='".$order_id."' ";
			$where.= " and tbl_order_detail.payment_status<>'9' ";
			$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
			$all_pay = true;
			if( count($arr_temp)>0 ){
				for($j=0;$j<count($arr_temp);$j++){
					if($arr_temp[$j]["payment_status"]!="2"){
						$all_pay = false;
					}
				}
			} else {
				$all_pay = false;
			}
			if( $all_pay == true ){
				$sql = "";
				$sql.= "UPDATE ";
				$sql.= "tbl_order ";
				$sql.= "SET ";
				$sql.= "tbl_order.payment_status='2', ";
				$sql.= "tbl_order.payment_date='".date("Y-m-d H:i:s")."' ";
				$where = "";
				$where.= "WHERE ";
				$where.= " tbl_order.order_id='".$order_id."' ";
				$ret = $objDbConnect->execute($sql.$where);
			}
			//----------------------------------------
			$sql = "";
			$sql.= "SELECT ";
			$sql.= "tbl_order_detail.payment_status ";
			$sql.= "FROM ";
			$sql.= "tbl_order_detail ";
			$where = "";
			$where.= "WHERE ";
			$where.= " tbl_order_detail.order_id='".$order_id."' ";
			$where.= " and tbl_order_detail.payment_status<>'9' ";
			$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
			$all_nopay = true;
			if( count($arr_temp)>0 ){
				for($j=0;$j<count($arr_temp);$j++){
					if($arr_temp[$j]["payment_status"]!="1"){
						$all_nopay = false;
					}
				}
			} else {
				$all_nopay = false;
			}
			if( $all_nopay == true ){
				$sql = "";
				$sql.= "UPDATE ";
				$sql.= "tbl_order ";
				$sql.= "SET ";
				$sql.= "tbl_order.payment_status='1', ";
				$sql.= "tbl_order.payment_date=null ";
				$where = "";
				$where.= "WHERE ";
				$where.= " tbl_order.order_id='".$order_id."' ";
				$ret = $objDbConnect->execute($sql.$where);
			}
			//----------------------------------------
			if( !$all_cancel && !$all_pay && !$all_nopay ){
				$sql = "";
				$sql.= "UPDATE ";
				$sql.= "tbl_order ";
				$sql.= "SET ";
				$sql.= "tbl_order.payment_status='3', ";
				$sql.= "tbl_order.payment_date=null ";
				$where = "";
				$where.= "WHERE ";
				$where.= " tbl_order.order_id='".$order_id."' ";
				$ret = $objDbConnect->execute($sql.$where);
			}
			//----------------------------------------
		}
		$update_count = $i+1;
	}
	if( $err_msg=="" ){
		$objDbConnect->commit();
		$ok_msg = "合計".$update_count."件の銀行振込を登録しました。";
		
	} else {
		$objDbConnect->rollback();
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("銀行振込取り込み");
$template->admin_comment("ファイルをアップロードして銀行振込csvを取り込みます。");
$template->admin_school($arr_session["cms_master.login.school_name"]);

if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sidemenu_html ='<ul>
<li><a href="./../amount_order/index.php" style="font-size:13px;">受注履歴</a></li>
<li><a href="./../amount_user/index.php" style="font-size:13px;">会員別売上集計</a></li>
<li><a href="./../amount_passport/index.php" style="font-size:13px;">パスポート注文履歴</a></li>
<li><a href="./../amount_product/index.php" style="font-size:13px;">売り上げ分析*</a></li>
<li class="selected"><a href="./../bank_upload/index.php" style="font-size:13px;">銀行振込取込*</a></li>
</ul>';
$template->admin_sidemenu($sidemenu_html);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('bar_association_id', $arr_session["cms_master.login.bar_association_id"]);
$template->assign('post_data', serialize($_POST));

$template->assign('csv', $csv);
$template->assign('err_msg', $err_msg);
$template->assign('ok_msg', $ok_msg);

$template->assign('disp_flg', false);

$template->assign('page_name', 'bank_upload');
$template->admin_layout('bank_upload/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
