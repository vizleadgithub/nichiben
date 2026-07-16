<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
// 日弁連フラグ
$login_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
if ($login_bar_association_id == 1){
	$nichibenren_flg = true;  // 日弁連
} else {
	$nichibenren_flg = false; // 日弁連以外の弁護士会
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
$template->assign('nichibenren_flg', $nichibenren_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品ID
$pid = '';
if(isset($_REQUEST["pid"])){
	$pid = $_REQUEST["pid"];
}
if (strlen($pid) == 0) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報（弁護士会）を取得
$arr_input_2 = array();
$sql = "";
$sql.= "SELECT ";
$sql.= " product_id, ";
$sql.= " product_name, ";
$sql.= " product_code ";
$sql.= "FROM ";
$sql.= " tbl_product ";
$sql.= "WHERE ";
$sql.= " product_id='".$pid."' ";
$arr_input_2 = $objDbConnect->query_fetch($sql);
//$temp = $objDbConnect->query_fetch_arr($sql);
if( count($arr_input_2)==0 ){
	//$arr_input_2 = $temp[0];
	header('Location: info.php?pid='.$pid);
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// モード＆CSVファイル＆結果
$mode = '';
if(isset($_POST["mode"])){
	$mode = $_POST["mode"];
}
$oid = '';
if(isset($_POST["oid"])){
	$oid = $_POST["oid"];
}
$res = '';
if(isset($_POST["res"])){
	$res = $_POST["res"];
}
$res_cnt = '';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_list = array();
$new_name = "";
$err_msg = "";
$res_msg = "";
$upload_dir_name ="/alflearning-data/alfproduct/csv_upload";
$upload_file_new_name = ''; // 作成ファイル名

// アップロード
if ($mode == "upload") {
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
		
		// アップロードされたファイルかどうかの確認
		if (is_uploaded_file($_FILES["csv_upload"]['tmp_name'])){
			$file_name        = $_FILES["csv_upload"]['tmp_name'];
			$file_name_before = $_FILES["csv_upload"]['name'];

			// 拡張子チェック
			$extension = substr(strrchr($file_name_before, '.') ,1);
			$upload_file_new_name = date("YmdHis").uniqid(rand());
			$new_name = $upload_file_new_name.'.'.$extension;

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
		
		if( $err_msg=="" ){
			$file = $upload_dir_name.'/'.$new_name;
			$fp   = fopen($file, "r");
			$i = 1;
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				$data = array_map(function($value) {
					return mb_convert_encoding($value, 'UTF-8', 'SJIS-win');
				}, $data);
				// -----ヘッダ行を飛ばす-----
				if ($i == 1){
					$i++;
					continue;
				}
				// --------------------------
				$lawyer_number = $data[0];      // 登録番号
				$student_name  = '';            // 氏名
				$entry_date    = $data[1];      // 申込日
				$take_date     = date('Y/m/d'); // 取込日
				//========================================
				if ($lawyer_number==""){
					$err_msg .= "\n".$i.'行目：登録番号が記載されていません。';
				} else {
					if(is_numeric($lawyer_number)){
						// ユーザー情報取得
						$sql = "SELECT student_id, student_name FROM student WHERE lawyer_number = '".mysqli_real_escape_string($objDbConnect->connect, $lawyer_number)."'";
						$result = $objDbConnect->query_fetch($sql);
						if ($result){
							// 氏名
							$student_name = $result['student_name'];
							
						} else {
							$err_msg .= "\n".$i.'行目：ユーザー登録されていない登録番号です。';
						}
					} else {
						$err_msg .= "\n".$i.'行目：登録番号は半角数字で記載してください。';
					}
				}
				//========================================
				if($entry_date==""){
					$err_msg .= "\n".$i.'行目：申込日が記載されていません。';
				} else {
					$arr_date = explode("|", str_replace(":", "|", str_replace(" ", "|", str_replace("-", "|", str_replace("/", "|", trim($entry_date))))) );
					if( checkdate( intval($arr_date[1]), intval($arr_date[2]), intval($arr_date[0]) ) ){
						//$entry_date = $arr_date[0]."-".$arr_date[1]."-".$arr_date[2]."";
					} else {
						$err_msg .= "\n".$i.'行目：申込日は「YYYY/MM/DD」の形式で記載してください。['.$entry_date.']';
					}
				}
				//========================================
				
				// CSVの内容を格納する
				$arr_list[$i] = array(
					'lawyer_number' => $lawyer_number,
					'student_name'  => $student_name,
					'entry_date'    => $entry_date,
					'take_date'     => $take_date
				);
				
				$i += 1;
			}
			fclose($fp);
			
			if ($err_msg != ''){
				unlink($file);
			}
		}
	}
}
// 代替倫理研修権限を付与する
elseif ($mode == "add") {
	if (isset($_POST['ufnn'])){
		$file = $upload_dir_name.'/'.$_POST['ufnn'].'.csv';
		if (file_exists($file)){
			$fp   = fopen($file, "r");
			$res_cnt = 0;
			
			//$already_flg = false; // 既に登録済みユーザーがいた場合にtrueになる
			
			$head_flg = true;
			
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				// -----ヘッダ行を飛ばす-----
				if ($head_flg){
					$head_flg = false;
					continue;
				}
				// --------------------------
				$lawyer_number = $data[0];      // 登録番号
				$entry_date    = $data[1];      // 申込日
				$take_date     = date('Y/m/d'); // 取込日
				$menber_id     = '';
				$temp_no       = '';
				$temp_date     = '';
				
				// member_idの取得
				$sql = "SELECT student_id FROM student WHERE lawyer_number = '".mysqli_real_escape_string($objDbConnect->connect, $lawyer_number)."'";
//print_r($sql."<br>\n");
				$result = $objDbConnect->query_fetch($sql);
				if ($result){
					$menber_id = $result['student_id'];

					$sql = "";
					$sql.= "UPDATE ";
					$sql.= " student ";
					$sql.= "SET ";
					$sql.= " sub_auth_ethic_training='1' ";
					$sql.= "WHERE ";
					$sql.= " student_id='".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' ";
					$ret = $objDbConnect->execute($sql);
//print_r($sql."<br>\n");
					$sql = "";
					$sql.= "SELECT ";
					$sql.= " COUNT(*) AS c ";
					$sql.= "FROM ";
					$sql.= " tbl_ethic_question_history ";
					$sql.= "WHERE ";
					$sql.= " student_id = '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' ";
					$sql.= " AND product_id = '".$arr_input_2['product_id']."' ";
//print_r($sql."<br>\n");
					$result2 = $objDbConnect->query_fetch($sql);
					if ($result2['c'] >= 1){
					} else {
						$sql = "";
						$sql.= "INSERT INTO ";
						$sql.= " tbl_ethic_question_history ";
						$sql.= "    ( ";
						$sql.= "     student_id, ";
						$sql.= "     product_id, ";
						$sql.= "     status, "; //ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）
						$sql.= "     create_date, "; //作成日時
						$sql.= "     start_date1 "; //1次開始日時
						$sql.= "    ) ";
						$sql.= "VALUES ";
						$sql.= "    ( ";
						$sql.= "     '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."', ";
						$sql.= "     '".$arr_input_2['product_id']."', ";
						$sql.= "     '0', ";
						$sql.= "     '".$take_date."', ";
						$sql.= "     '".$entry_date."' ";
						$sql.= "    ) ";
//print_r($sql."<br>\n");
						$ret = $objDbConnect->execute($sql);
					}
				} else {
					$res_msg = '登録ユーザーが削除されましたので、お手数ですが再度CSVファイルアップロードからの手順を実行してください。';
					break;
				}
				$res_cnt += 1;
			}
			fclose($fp);
			unlink($file);
			
			if ($res_msg==''){
				$res_msg = '合計'.$res_cnt.'名に代替倫理研修権限を付与しました。';
			}
			
		} else {
			$err_msg .= "登録に失敗しました。\nファイルを再アップロードしてください。";
		}
		
	} else {
		$err_msg .= '登録に失敗しました。';
	}
}
// 追試×をレポートにする
elseif ($mode == "report") {
	if (isset($_POST['ufnn'])){
		$file = $upload_dir_name.'/'.$_POST['ufnn'].'.csv';
		if (file_exists($file)){
			$fp   = fopen($file, "r");
			$res_cnt = 0;
			
			//$already_flg = false; // 既に登録済みユーザーがいた場合にtrueになる
			
			$head_flg = true;
			
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				// -----ヘッダ行を飛ばす-----
				if ($head_flg){
					$head_flg = false;
					continue;
				}
				// --------------------------
				$lawyer_number = $data[0];      // 登録番号
				$entry_date    = $data[1];      // 申込日
				$take_date     = date('Y/m/d'); // 取込日
				$menber_id     = '';
				$temp_no       = '';
				$temp_date     = '';
				
				// member_idの取得
				$sql = "SELECT student_id FROM student WHERE lawyer_number = '".mysqli_real_escape_string($objDbConnect->connect, $lawyer_number)."'";
//print_r($sql."<br>\n");
				$result = $objDbConnect->query_fetch($sql);
				if ($result){
					$menber_id = $result['student_id'];

					//status ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）
					$sql = "SELECT ";
					$sql.= "student_id, ";
					$sql.= "product_id ";
					$sql.= "FROM ";
					$sql.= "tbl_ethic_question_history ";
					$sql.= "WHERE ";
					$sql.= " student_id = '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' ";
					$sql.= " AND product_id = '".$arr_input_2['product_id']."' ";
					// $sql.= " AND status = '6' "; 仕様変更で追試×だけでなく、どのステータスでもレポートに変更可にするためコメントアウト
//print_r($sql."<br>\n");
					$result2 = $objDbConnect->query_fetch($sql);
					if ($result2){
						$sql = "";
						$sql.= "REPLACE INTO ";
						$sql.= " tbl_status_change_ethic ";
						$sql.= "  (student_id, product_id, status) ";
						$sql.= " VALUES ";
						$sql.= "  ('".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."', '".$arr_input_2['product_id']."', '7') ";
//print_r($sql."<br>\n");
						$ret = $objDbConnect->execute($sql);
						$res_cnt += 1;
					}
				} else {
					$res_msg = '登録ユーザーが削除されましたので、お手数ですが再度CSVファイルアップロードからの手順を実行してください。';
					break;
				}
			}
			fclose($fp);
			unlink($file);
			
			if ($res_msg==''){
				$res_msg = '合計'.$res_cnt.'名の追試×をレポートにしました。';
				$res_msg = '合計'.$res_cnt.'名を研修登録しました。';
			}
			
		} else {
			$err_msg .= "登録に失敗しました。\nファイルを再アップロードしてください。";
		}
		
	} else {
		$err_msg .= '登録に失敗しました。';
	}
}
// 完了を済とする
elseif ($mode == "end") {
	if (isset($_POST['ufnn'])){
		$file = $upload_dir_name.'/'.$_POST['ufnn'].'.csv';
		if (file_exists($file)){
			$fp   = fopen($file, "r");
			$res_cnt = 0;
			
			//$already_flg = false; // 既に登録済みユーザーがいた場合にtrueになる
			
			$head_flg = true;
			
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				// -----ヘッダ行を飛ばす-----
				if ($head_flg){
					$head_flg = false;
					continue;
				}
				// --------------------------
				$lawyer_number = $data[0];      // 登録番号
				$entry_date    = $data[1];      // 申込日
				$take_date     = date('Y/m/d'); // 取込日
				$menber_id     = '';
				$temp_no       = '';
				$temp_date     = '';
				
				// member_idの取得
				$sql = "SELECT student_id FROM student WHERE lawyer_number = '".mysqli_real_escape_string($objDbConnect->connect, $lawyer_number)."'";
//print_r($sql."<br>\n");
				$result = $objDbConnect->query_fetch($sql);
				if ($result){
					$menber_id = $result['student_id'];
					//status ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）
					$sql = "SELECT ";
					$sql.= "student_id, ";
					$sql.= "product_id ";
					$sql.= "FROM ";
					$sql.= "tbl_ethic_question_history ";
					$sql.= "WHERE ";
					$sql.= " student_id = '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' ";
					$sql.= " AND product_id = '".$arr_input_2['product_id']."' ";
//print_r($sql."<br>\n");
					$result2 = $objDbConnect->query_fetch($sql);
					if ($result2){
						$sql = "";
						$sql.= "UPDATE ";
						$sql.= " tbl_ethic_question_history ";
						$sql.= "SET ";
						$sql.= " complete_flg='1' ";
						$sql.= "WHERE ";
						$sql.= " student_id = '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' ";
						$sql.= " AND product_id = '".$arr_input_2['product_id']."' ";
//print_r($sql."<br>\n");
						$ret = $objDbConnect->execute($sql);
						$res_cnt += 1;
					}
				} else {
					$res_msg = '登録ユーザーが削除されましたので、お手数ですが再度CSVファイルアップロードからの手順を実行してください。';
					break;
				}
			}
			fclose($fp);
			unlink($file);
			
			if ($res_msg==''){
				$res_msg = '合計'.$res_cnt.'名を完了にしました。';
			}
			
		} else {
			$err_msg .= "登録に失敗しました。\nファイルを再アップロードしてください。";
		}
		
	} else {
		$err_msg .= '登録に失敗しました。';
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("講座管理");
$template->admin_comment("講座の参加情報を管理します。");
$sidemenu_html ='<ul>
<li><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
<li><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
<li class="selected"><a href="/alfproduct/product_lecture_ethics/index.php" style="font-size:13px">倫理代替措置研修状況</a></li>
</ul>';
$template->admin_sidemenu($sidemenu_html);

//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('mode', $mode);
$template->assign('pid', $pid);
$template->assign('atype', $atype);
$template->assign('res', $res);
$template->assign('res_cnt', $res_cnt);
//$template->assign('arr_input', $arr_input);
$template->assign('arr_input_2', $arr_input_2);
$template->assign('arr_list', $arr_list);
$template->assign('err_msg', $err_msg);
$template->assign('res_msg', $res_msg);
$template->assign('upload_file_new_name', $upload_file_new_name);

$template->assign('page_name', 'product_lecture_ethics');
$template->admin_layout('product_lecture_ethics/info_user_import.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
