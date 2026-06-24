<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
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
if(isset($_POST["pid"])){
	$pid = $_POST["pid"];
}
if (strlen($pid) == 0) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報を取得
$arr_input_2 = array();
$sql = "SELECT product_id, product_name FROM tbl_product WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'";
$arr_input_2 = $objDbConnect->query_fetch($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// モード＆結果
$mode = '';
if(isset($_POST["mode"])){
	$mode = $_POST["mode"];
}
$res = '';
if(isset($_POST["res"])){
	$res = $_POST["res"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$res_msg = '';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期表示
if($mode == ""){

}

// 代替倫理研修権限を付与する
elseif ($mode == "add") {
	if (isset($_POST['lawyer_numbers'])){
		//テキストエリアの値
		$textarea = $_POST['lawyer_numbers'];
		//改行コード置換用配列
		$cr = array("\r\n", "\r");
		//文頭文末の空白を削除
		$textarea = trim($textarea);
		//改行コードを統一
		$text = str_replace($cr, "\n", $textarea);
		//改行コードで分割
		$lawyer_numbers = explode("\n", $textarea);
		
		$res_cnt = 0;
		foreach ($lawyer_numbers as $lawyer_number){
			$lawyer_number = trim($lawyer_number);
			
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
					$sql.= "     '".date('Y-m-d H:i:s')."', ";
					$sql.= "     '".date('Y-m-d H:i:s')."' ";
					$sql.= "    ) ";
//print_r($sql."<br>\n");
					$ret = $objDbConnect->execute($sql);
					
					$res_cnt += 1;
				}
			}
		}
		
		$res_msg = '合計'.$res_cnt.'名に代替倫理研修権限を付与しました。';
		
	} else {
		$res_msg = '登録番号を入力してください。';
	}
}

// 追試×をレポートにする
elseif ($mode == "report") {
	if (isset($_POST['lawyer_numbers'])){
		//テキストエリアの値
		$textarea = $_POST['lawyer_numbers'];
		//改行コード置換用配列
		$cr = array("\r\n", "\r");
		//文頭文末の空白を削除
		$textarea = trim($textarea);
		//改行コードを統一
		$text = str_replace($cr, "\n", $textarea);
		//改行コードで分割
		$lawyer_numbers = explode("\n", $textarea);
		
		$res_cnt = 0;
		foreach ($lawyer_numbers as $lawyer_number){
			$lawyer_number = trim($lawyer_number);
			
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
			}
		}
		
		$res_msg = '合計'.$res_cnt.'名をレポートにしました。';
	}
}

// 完了を済とする
elseif ($mode == "end") {
	if (isset($_POST['lawyer_numbers'])){
		//テキストエリアの値
		$textarea = $_POST['lawyer_numbers'];
		//改行コード置換用配列
		$cr = array("\r\n", "\r");
		//文頭文末の空白を削除
		$textarea = trim($textarea);
		//改行コードを統一
		$text = str_replace($cr, "\n", $textarea);
		//改行コードで分割
		$lawyer_numbers = explode("\n", $textarea);
		
		$res_cnt = 0;
		foreach ($lawyer_numbers as $lawyer_number){
			$lawyer_number = trim($lawyer_number);
			
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
			}
		}
		
		$res_msg = '合計'.$res_cnt.'名を完了にしました。';
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
$template->admin_sidemenu($sidemenu_html);

//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('pid', $pid);
$template->assign('aid', $aid);
$template->assign('atype', $atype);
$template->assign('res', $res);
//$template->assign('arr_input', $arr_input);
$template->assign('arr_input_2', $arr_input_2);
$template->assign('res_msg', $res_msg);

$template->assign('page_name', 'product_lecture_ethics');
$template->admin_layout('product_lecture_ethics/info_user_regist.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
