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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品ID
$pid = '';
if(isset($_POST["pid"])){
	$pid = intval($_POST["pid"]);
}
if (strlen($pid) == 0) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報を取得
/*
$arr_input = array();
$sql = "select TP.*, DATE_FORMAT(TP.start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(TP.end_date,'%Y/%m/%d %H:%i') as end_date,DATE_FORMAT(TPLT.live_start_date,'%Y/%m/%d') as live_start_date from ((tbl_product TP LEFT JOIN tbl_product_add TPA ON (TP.product_id = TPA.product_id)) LEFT JOIN tbl_product_live_training TPLT ON (TP.product_id = TPLT.product_id)) LEFT JOIN rel_product_bar_association RPBA ON (TP.product_id = RPBA.product_id) where TP.del_flg=0 ";
$where = ' AND TP.product_id = "'.mysqli_real_escape_string($objDbConnect->connect,$pid).'"';
$group = ' GROUP BY TP.product_id';

// echo "[".$sql.$where.$group."]";

$arr_input = $objDbConnect->query_fetch($sql.$where.$group);

if (!$arr_input) {
	header('Location: index.php');
	exit;
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 弁護士会ID
$aid = '';
//$atype = '';
if(isset($_POST["aid"])){
	$aid = $_POST["aid"];
}
//if(isset($_POST["atype"])){
//	$atype = $_POST["atype"];
//}
//if (strlen($aid) == 0 || strlen($atype) == 0) {
if (strlen($aid) == 0) {
	header('Location: info.php?pid='.$pid);
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報（弁護士会）を取得
$arr_input_2 = array();
//$sql = "select MBA.*, RPBA.bar_association_id, RPBA.atype, RPBA.capacity, RPBA.hall, DATE_FORMAT(RPBA.receptionist_start_date, '%Y/%m/%d') AS receptionist_start_date, DATE_FORMAT(RPBA.receptionist_end_date, '%Y/%m/%d') AS receptionist_end_date, RPBA.contents from rel_product_bar_association RPBA INNER JOIN mtb_bar_association MBA ON (RPBA.bar_association_id = MBA.id) where RPBA.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND RPBA.bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."' AND RPBA.atype = '".mysqli_real_escape_string($objDbConnect->connect,$atype)."' AND RPBA.atype IN (1,2) ORDER BY RPBA.atype, MBA.id";
// 旧データの確認
if ($pid <= 19233) {
	$arr_association_branch_id = array();
	
	$product_id_old = $pid - 10000;
	
	$sql = "
	SELECT
	  bar_association_branch_id
	FROM
	  import_kenshu_count
	WHERE
	  KENSHU_ID = '$product_id_old'
	";
	$res = $objDbConnect->query_fetch_arr($sql);
	if ($res) {
		foreach ($res as $k1 => $v1) {
			$arr_association_branch_id[] = $v1["bar_association_branch_id"];
		}
	}
	
	$sql = "
	SELECT
	  T1.product_id,
	  T1.product_name,
	  T1.product_code,
	  T1.open_period,
	  T1.start_date,
	  T1.end_date,
	  T1.price,
	  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
	  T3.capacity,
	  T3.web_flg
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_live_training AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND bar_association_branch_id IN ('".implode(",", $arr_association_branch_id)."' )) AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  and  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' 
	";
	
} else {
	$sql = "
	SELECT
	  T1.product_id,
	  T1.product_name,
	  T1.product_code,
	  T1.open_period,
	  T1.start_date,
	  T1.end_date,
	  T1.price,
	  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
	  T3.capacity,
	  T3.web_flg
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_live_training AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ) AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  AND T3.bar_association_branch_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."'
	  and  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' 
	";
}
$arr_input_2 = $objDbConnect->query_fetch($sql);

if (!$arr_input_2) {
	header('Location: info.php?pid='.$pid);
	exit;
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 旧システムデータの場合
if ($pid <= 19233) {
	if ($aid == '119' || $aid == '120' || $aid == '121' || $aid == '122' || $aid == '123' || $aid == '124' || $aid == '125' || $aid == '126'){
		$old_pid = $pid - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$aid = $res_bar_association_branch_id[0]['bar_association_branch_id'];
		} else {
			$aid = '1';
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$arr_input_2['entry_number'] = get_entry_number($aid, $pid);
$bar_association_info = get_bar_association_info($aid);
if ($bar_association_info){
	$arr_input_2['bar_association_id'] = $bar_association_info['id'];
	$arr_input_2['bar_association_name'] = $bar_association_info['name'];
	$arr_input_2['bar_association_branch_id'] = $bar_association_info['bar_association_branch_id'];
	$arr_input_2['bar_association_branch_name'] = $bar_association_info['bar_association_branch_name'];
} else {
	$arr_input_2['bar_association_id'] = '';
	$arr_input_2['bar_association_name'] = '';
	$arr_input_2['bar_association_branch_id'] = '';
	$arr_input_2['bar_association_branch_name'] = '';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 削除ID＆削除結果
$oid = '';
if(isset($_POST["oid"])){
	$oid = $_POST["oid"];
}
$res = '';
if(isset($_POST["res"])){
	$res = $_POST["res"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$res_msg = '';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期表示
if(!isset($_POST['mode'])){

}

// 登録
elseif($_POST['mode'] == 'regist') {
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
		
		$i = 1;
		$res_cnt = 0;
		foreach ($lawyer_numbers as $lawyer_number){
			$menber_id = '';
			$temp_no   = '';
			$temp_date = '';
			$lawyer_number = trim($lawyer_number);
			
			// 半角数字チェック
			if(is_numeric($lawyer_number)){
				// ユーザーID、パスポート有無の取得
				$sql = "SELECT student_id, presence_passport FROM student WHERE lawyer_number = '".mysqli_real_escape_string($objDbConnect->connect , $lawyer_number)."'";
				$result = $objDbConnect->query_fetch($sql);
				if ($result){
					$menber_id = $result['student_id'];
					$presence_passport = $result['presence_passport'];
					
					// 申込済みの場合は飛ばす
					$sql = "SELECT COUNT(*) AS c FROM tbl_order_detail WHERE member_id = '".mysqli_real_escape_string($objDbConnect->connect, $menber_id)."' AND product_id = '".$arr_input_2['product_id']."' AND payment_status IN(1,2) AND bar_association_branch_id = '".$arr_input_2['bar_association_branch_id']."'";
					$result2 = $objDbConnect->query_fetch($sql);
					if ($result2['c'] >= 1){
						$res_msg .= '<br>'.$i.'行目：申込済みのユーザーです。';
						$i += 1;
						continue;
					}
					
					$err_flg = false;
					
					$regist_date = date('Y-m-d H:i:s');
					
					// パスポート所持ユーザー
					//if ($presence_passport == '1'){
						$price = 0; // 0円で登録
						$payment_type = 99; // パスポート
						$payment_status = 2; // 入金済み
						$payment_date = $regist_date;
						
					// パスポート未所持ユーザー
					//} else {
					//	$price = $arr_input_2['price']; // 商品に設定されている価格
					//	$payment_type = 12; // 銀行振込
					//	$payment_status = 1; // 入金待ち
					//	$payment_date = NULL;
					//}
					
					// トランザクション開始
					$objDbConnect->tran_begin();
					
					// order_noの取得と更新
					$sql = "select create_date, no from tbl_order_no where create_date='".date("Y-m-d")."' ORDER BY no DESC LIMIT 1";
					$ret = $objDbConnect->query_fetch_arr($sql);
					if( count($ret)>0 ){
						$temp_no = $ret[0]["no"] + 1;
						$temp_date = date("Ymd");
					} else {
						$temp_no = 10001;
						$temp_date = date("Ymd");
					}
					
					$sql = "INSERT INTO tbl_order_no( create_date, no ) values('".$temp_date."','".$temp_no."')";
					$ret = $objDbConnect->execute($sql);
					if($ret){
						// 注文情報登録(tbl_order_temp→tbl_orderの順)
						$sql = "
						INSERT INTO tbl_order_temp
						  (
						    order_no,
						    member_id,
						    price,
						    payment_type,
						    payment_status,
						    create_date,
						    order_date,
						    payment_date
						  )
						  VALUES
						  (
						    '".mysqli_real_escape_string($objDbConnect->connect , $temp_date.$temp_no)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $menber_id)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $price)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $payment_type)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $payment_status)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $regist_date)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $regist_date)."',
						    '".mysqli_real_escape_string($objDbConnect->connect , $payment_date)."'
						  )
						";
						$ret = $objDbConnect->execute($sql);
						if ($ret){
							$order_id = mysqli_insert_id($objDbConnect->connect);
							
							// tbl_order_tempテーブルからtbl_orderにコピー
							$sql = "INSERT INTO tbl_order SELECT * FROM tbl_order_temp WHERE order_id = '$order_id'";
							$ret = $objDbConnect->execute($sql);
							if ($ret){
								// tempデータの削除
								//$sql = "DELETE FROM tbl_order_temp WHERE order_id = '$order_id'";
								//$objDbConnect->execute($sql);
								
								// 注文詳細情報登録
								$sql = "
								INSERT INTO tbl_order_detail
								  (
								    order_id,
								    member_id,
								    product_id,
								    price,
								    sell_price,
								    unit,
								    pay_total,
								    create_date,
								    payment_status,
								    product_type_add,
								    bar_association_id,
								    bar_association_branch_id,
								    payment_date,
								    product_name,
								    product_code,
								    open_period,
								    start_date,
								    end_date
								  )
								  VALUES
								  (
								    '".mysqli_real_escape_string($objDbConnect->connect, $order_id)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $menber_id)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['product_id'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $price)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $price)."',
								    '1',
								    '".mysqli_real_escape_string($objDbConnect->connect , $price)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $regist_date)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $payment_status)."',
								    '2',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['bar_association_id'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['bar_association_branch_id'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $payment_date)."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['product_name'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['product_code'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['open_period'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['start_date'])."',
								    '".mysqli_real_escape_string($objDbConnect->connect , $arr_input_2['end_date'])."'
								   )
								";
								$ret = $objDbConnect->execute($sql);
								if ($ret){
								} else {
									$err_flg = true;
								}
								
							} else {
								$err_flg = true;
							}
							
						} else {
							$err_flg = true;
						}
						
					} else {
						$err_flg = true;
					}
					
					if ($err_flg){
						// ロールバック
						$objDbConnect->rollback();
						$res_msg = '申込状況の追加に失敗しました。';
						break;
						
					} else {
						// コミット
						$objDbConnect->commit();
						$res_cnt += 1;
					}
				} else {
					$res_msg .= '<br>'.$i.'行目：登録番号のユーザーが存在しないため、追加することができませんでした。';
				}
				
			} else {
				$res_msg .= '<br>'.$i.'行目：登録番号は半角数字で記載してください。';
			}
			
			$i += 1;
		}
		
		$res_msg .= '<br>'.'【合計'.$res_cnt.'名】を研修登録しました。';
		
		// 登録した人数を申込人数にプラスする
		$arr_input_2['entry_number'] = $arr_input_2['entry_number'] + $res_cnt;
		
		$template->assign('lawyer_numbers', $_POST['lawyer_numbers']);
		
	} else {
		$res_msg = '登録番号を入力してください。';
	}
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("講座管理");
$template->admin_comment("講座の参加情報を管理します。");

if ($nichibenren_flg){
$sidemenu_html ='<ul>
<li><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
<li class="selected"><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
<li><a href="/alfproduct/product_lecture_ethics/index.php" style="font-size:13px">倫理代替措置研修状況</a></li>
</ul>';
} else {
$sidemenu_html ='<ul>
<li><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
<li class="selected"><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
</ul>';
}
$template->admin_sidemenu($sidemenu_html);

$temp_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
$temp_bar_association_name = "管理者";
if( $temp_bar_association_id>1 ){
	$sql  = '';
	$sql .= "SELECT  id ";
	$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
	$sql .= " FROM mtb_bar_association ";
	$sql .= " WHERE id = ".intval($temp_bar_association_id)." ";
	$headret = $objDbConnect->query_fetch($sql);
	if( isset($headret["name"]) ){
		$temp_bar_association_name = '【'.$headret["name"].'】';
	}
}
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('pid', $pid);
$template->assign('aid', $aid);
$template->assign('atype', $atype);
$template->assign('res', $res);
//$template->assign('arr_input', $arr_input);
$template->assign('arr_input_2', $arr_input_2);
$template->assign('res_msg', $res_msg);

$template->assign('page_name', 'product_lecture2');
$template->admin_layout('product_lecture2/info_user_regist.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
