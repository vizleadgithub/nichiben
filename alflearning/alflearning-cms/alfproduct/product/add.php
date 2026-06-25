<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$template = new Template();
$objDbConnect = new DbConnect();
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
}
if (!$nichibenren_flg){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("商品登録");
$template->admin_comment("商品を登録します。");

$sidemenu_html = '<ul>';
if ($nichibenren_flg){
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">会場研修*</a></li>
<li class="selected"><a href="./../product/index.php" style="font-size:13px">eラーニング*</a></li>
<li><a href="./../product_ethics/index.php" style="font-size:13px">倫理代替措置研修*</a></li>
';
//<li><a href="./../product_passport/index.php" style="font-size:13px">パスポート*</a></li>
} else {
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">自会主催研修</a></li>
<li><a href="./../product_live_branch/index.php" style="font-size:10px">日弁連主催研修・他会主催研修</a></li>
';
}
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);

//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('section_open_period', MAX_OPEN_PERIOD + 1);
$template->assign('section_contents', MAX_CONTENTS + 1);
$template->assign('section_contents_download', MAX_CONTENTS_DOWNLOAD + 1);
$template->assign('section_related_products', MAX_RELATED_PRODUCTS + 1);
$template->assign('section_free_html_area', MAX_FREE_HTML_AREA + 1);
$template->assign('section_contents_free_time', MAX_CONTENTS_FREE_TIME + 1);
$template->assign('thumbnail_path', THUMBNAIL_PATH);
$template->assign('contents_thumbnail_path', CONTENTS_THUMBNAIL_PATH);
$template->assign('page_name', 'product');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  T1.term_id,";
$sql.= "  T1.name,";
$sql.= "  T2.parent";
$sql.= " FROM";
$sql.= "  wp_terms AS T1";
$sql.= "   JOIN";
$sql.= "  wp_term_taxonomy AS T2";
$sql.= "   ON T1.term_id = T2.term_id";
$sql.= "";
$sql.= " ORDER BY T1.slug ASC";
$ret = $objDbConnect->query_fetch_arr($sql);
$template->assign('arr_cat_list', $ret);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21' ORDER BY wp_terms.slug ASC ";
$arr_category = $objDbConnect->query_fetch_arr($sql);
for($i=0;$i<count($arr_category);$i++){
	$temp = array();
	$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$arr_category[$i]["term_id"]."' ORDER BY wp_terms.slug ASC";
	$temp = $objDbConnect->query_fetch_arr($sql);
	if(0<count($temp)){
		$arr_category[$i]["categorys"] = $temp;
		for($n=0;$n<count($temp);$n++){
			$temp2 = array();
			$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp[$n]["term_id"]."' ORDER BY wp_terms.slug ASC";
			$temp2 = $objDbConnect->query_fetch_arr($sql);
			if(0<count($temp2)){
				$arr_category[$i]["categorys"][$n]["categorys"] = $temp2;
				for($m=0;$m<count($temp2);$m++){
					$temp3 = array();
					$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp2[$m]["term_id"]."' ORDER BY wp_terms.slug ASC";
					$temp3 = $objDbConnect->query_fetch_arr($sql);
					if(0<count($temp3)){
						$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = $temp3;
					} else {
						$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = [];
					}
				}
			} else {
				$arr_category[$i]["categorys"][$n]["categorys"] = [];
			}
		}
		
	} else {
		$arr_category[$i]["categorys"] = [];
	}
}
$template->assign('arr_category', $arr_category);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品フラグ配列
$arr_product_flg = get_mtb_product_flg();
$template->assign('arr_product_flg', $arr_product_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 注意文言の掲載箇所配列
$arr_product_disp_warning_word = array(
	'1' => '商品説明下（条件：要全問正解）',
	'2' => '商品説明下（条件：全問正解不要）',
	'3' => '商品資料下',
);
$template->assign('arr_product_disp_warning_word', $arr_product_disp_warning_word);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 問題配列
$exam_list = get_exam_list($login_bar_association_id);
$template->assign('exam_list', $exam_list);
$exam2_list = get_exam2_list($login_bar_association_id);
$template->assign('exam2_list', $exam2_list);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ボタン選択配列
$btn_type_list = array('1' => 'テストを受ける', '2' => 'アンケートに回答する');
$template->assign('btn_type_list', $btn_type_list);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array(
		//'product_type' => "2",
		'product_kind_flg' => "1",
		'product_name' => "",
		'product_code' => "",
		'price' => "",
		'discount_code' => "",
		'start_date' => "",
		'end_date' => "",
		//'open_period' => "",
		'memo' => "",
		'play_time' => "",
		'teacher' => "",
		'teacher_student_id' => "0",
		'teacher_student_name' => "",
		'search_word' => "",
		'live_training_product_id' => "",
		'live_training_product_name' => "",
		'product_flg' => [],
		'product_disp_warning_word' => "",

		'exam2_id' => "33",

		'arr_term_id' => [],
		'term_id' => "",
		'str_term_id' => "",
	);
	for($i=1; $i<=MAX_CONTENTS; $i++){
		$arr_input["disp_warning_word".$i] = "";
	}

	$template->assign('arr_input', $arr_input);

	$template->assign('productcategory_list', get_product_category());
	$template->admin_layout('product/add.tpl');
	
// 初期表示以外
} else {
	//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
	$arr_input = array(
		//'product_type' => $_POST["product_type"] ?? "",
		'product_kind_flg' => $_POST["product_kind_flg"] ?? "",
		'product_name' => $_POST["product_name"] ?? "",
		'product_code' => $_POST["product_code"] ?? "",
		'price' => $_POST["price"] ?? "",
		'discount_code' => $_POST["discount_code"] ?? "",
		'start_date' => $_POST["start_date"] ?? "",
		'end_date' => $_POST["end_date"] ?? "",
		//'open_period' => $_POST["open_period"] ?? "",
		'memo' => $_POST["memo"] ?? "",
		'play_time' => $_POST["play_time"] ?? "",
		'teacher' => $_POST["teacher"] ?? "",
		'teacher_student_id' => $_POST["teacher_student_id"] ?? "",
		'teacher_student_name' => "",
		'search_word' => $_POST["search_word"] ?? "",
		'live_training_product_id' => $_POST["live_training_product_id"] ?? "",
		'live_training_product_name' => $_POST["live_training_product_name"] ?? "",
		'product_flg' => $_POST["product_flg"] ?? "",
		'product_disp_warning_word' => $_POST["product_disp_warning_word"] ?? "",

		'exam2_id' => $_POST["exam2_id"] ?? "",

		'arr_term_id' => [],
		'term_id' => $_POST["term_id"] ?? "",
		'str_term_id' => "",
	);
	for($i=1; $i<=MAX_CONTENTS; $i++){
		$arr_input["disp_warning_word".$i] = ( isset($_POST["disp_warning_word".$i]) && $_POST["disp_warning_word".$i]=="3" ) ?? "";
	}

	// 講師受講者の取得
	$arr_input["teacher_student"] = false;
	if ($arr_input["teacher_student_id"] >0){
		$sql = "select student.* from student where student_id='".$arr_input["teacher_student_id"]."' limit 1";
		$ret2 = $objDbConnect->query_fetch($sql);
		if ($ret2){
			$arr_input["teacher_student"] = $ret2;
			$arr_input["teacher_student_name"] = "[".$ret2["lawyer_number"]."]".$ret2["student_name"];

		}
	}

	
	if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["thumbnail"];
	} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
	} else {
		$arr_input["thumbnail"] = '';
	}
	if( isset($_POST["thumbnail1"]) && $_POST["thumbnail1"] != '' ){
		$arr_input["thumbnail1"] = $_POST["thumbnail1"];
	} elseif(isset($_POST["hid_thumbnail1"]) && $_POST["hid_thumbnail1"] != '' ){
		$arr_input["thumbnail1"] = $_POST["hid_thumbnail1"];
	} else {
		$arr_input["thumbnail1"] = '';
	}
	if( isset($_POST["thumbnail2"]) && $_POST["thumbnail2"] != '' ){
		$arr_input["thumbnail2"] = $_POST["thumbnail2"];
	} elseif(isset($_POST["hid_thumbnail2"]) && $_POST["hid_thumbnail2"] != '' ){
		$arr_input["thumbnail2"] = $_POST["hid_thumbnail2"];
	} else {
		$arr_input["thumbnail2"] = '';
	}
	if( isset($_POST["thumbnail3"]) && $_POST["thumbnail3"] != '' ){
		$arr_input["thumbnail3"] = $_POST["thumbnail3"];
	} elseif(isset($_POST["hid_thumbnail3"]) && $_POST["hid_thumbnail3"] != '' ){
		$arr_input["thumbnail3"] = $_POST["hid_thumbnail3"];
	} else {
		$arr_input["thumbnail3"] = '';
	}
	if( isset($_POST["thumbnail4"]) && $_POST["thumbnail4"] != '' ){
		$arr_input["thumbnail4"] = $_POST["thumbnail4"];
	} elseif(isset($_POST["hid_thumbnail4"]) && $_POST["hid_thumbnail4"] != '' ){
		$arr_input["thumbnail4"] = $_POST["hid_thumbnail4"];
	} else {
		$arr_input["thumbnail4"] = '';
	}
	if( isset($_POST["thumbnail5"]) && $_POST["thumbnail5"] != '' ){
		$arr_input["thumbnail5"] = $_POST["thumbnail5"];
	} elseif(isset($_POST["hid_thumbnail5"]) && $_POST["hid_thumbnail5"] != '' ){
		$arr_input["thumbnail5"] = $_POST["hid_thumbnail5"];
	} else {
		$arr_input["thumbnail5"] = '';
	}
	if( isset($_POST["thumbnail6"]) && $_POST["thumbnail6"] != '' ){
		$arr_input["thumbnail6"] = $_POST["thumbnail6"];
	} elseif(isset($_POST["hid_thumbnail6"]) && $_POST["hid_thumbnail6"] != '' ){
		$arr_input["thumbnail6"] = $_POST["hid_thumbnail6"];
	} else {
		$arr_input["thumbnail6"] = '';
	}
	if( isset($_POST["thumbnail7"]) && $_POST["thumbnail7"] != '' ){
		$arr_input["thumbnail7"] = $_POST["thumbnail7"];
	} elseif(isset($_POST["hid_thumbnail7"]) && $_POST["hid_thumbnail7"] != '' ){
		$arr_input["thumbnail7"] = $_POST["hid_thumbnail7"];
	} else {
		$arr_input["thumbnail7"] = '';
	}
	if( isset($_POST["thumbnail8"]) && $_POST["thumbnail8"] != '' ){
		$arr_input["thumbnail8"] = $_POST["thumbnail8"];
	} elseif(isset($_POST["hid_thumbnail8"]) && $_POST["hid_thumbnail8"] != '' ){
		$arr_input["thumbnail8"] = $_POST["hid_thumbnail8"];
	} else {
		$arr_input["thumbnail8"] = '';
	}
	
	for($i=1; $i<=MAX_CONTENTS; $i++){
		if( isset($_POST["contents_thumbnail$i"]) && $_POST["contents_thumbnail$i"] != ''){
			$arr_input["contents_thumbnail$i"] = $_POST["contents_thumbnail$i"];
		} elseif( isset($_POST["hid_contents_thumbnail$i"]) && $_POST["hid_contents_thumbnail$i"] != ''){
			$arr_input["contents_thumbnail$i"] = $_POST["hid_contents_thumbnail$i"];
		} else {
			$arr_input["contents_thumbnail$i"] = '';
		}
		$arr_input["contents_contents$i"] = $_POST["contents_contents$i"];
		$arr_input["contents_contents$i".'_name'] = $_POST["contents_contents$i".'_name'];
		if( trim($arr_input["contents_contents$i".'_name']) == ""){
			$arr_input["contents_contents$i"] = "";
		}

		$arr_input["contents_contents$i"."so"] = $_POST["contents_contents$i"."so"];
		$arr_input["contents_contents$i".'so_name'] = $_POST["contents_contents$i".'so_name'];
		if( trim($arr_input["contents_contents$i".'so_name']) == ""){
			$arr_input["contents_contents$i"."so"] = "";
		}

		$arr_input["contents_free_time$i"] = $_POST["contents_free_time$i"];
		$arr_input["contents_start_date$i"] = $_POST["contents_start_date$i"];
		if($arr_input["contents_start_date$i"] == "0000-00-00 00:00:00"){
			$arr_input["contents_start_date$i"] = "";
		}
		$arr_input["contents_end_date$i"] = $_POST["contents_end_date$i"];
		if($arr_input["contents_end_date$i"] == "0000-00-00 00:00:00"){
			$arr_input["contents_end_date$i"] = "";
		}
		$arr_input["contents_memo$i"] = $_POST["contents_memo$i"];
		$arr_input["contents_teacher$i"] = $_POST["contents_teacher$i"];
		
		$arr_input["exam_id_test$i"] = $_POST["exam_id_test$i"];
		$arr_input["exam_id_question$i"] = $_POST["exam_id_question$i"];
		$arr_input["btn_type$i"] = $_POST["btn_type$i"];
		$arr_input["disp_warning_word$i"] = $_POST["disp_warning_word$i"] ?? "";

		for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
			if($_POST['contents_download'.$i.'_'.$j] != ''){
				$arr_input['contents_download'.$i.'_'.$j] = $_POST['contents_download'.$i.'_'.$j];
				$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
			} elseif(isset($_POST['hid_contents_download'.$i.'_'.$j])){
				$arr_input['contents_download'.$i.'_'.$j] = $_POST['hid_contents_download'.$i.'_'.$j];
				$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
			} else {
				$arr_input['contents_download'.$i.'_'.$j] = '';
			}
		}
	}
	for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
		$arr_input["related_products$i"] = $_POST["related_products$i"];
		$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'];
	}
/*
	for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
		$arr_input["free_html_area$i"] = stripslashes($_POST["free_html_area$i"]);
	}
	for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
		$arr_input["free_html_area$i"."_sp"] = stripslashes($_POST["free_html_area$i"."_sp"]);
	}
*/
	
	if( isset($_POST["all_contents_download"]) && $_POST["all_contents_download"] != '' ){
		$arr_input["all_contents_download"] = $_POST["all_contents_download"] ?? "";
	} elseif(isset($_POST["hid_all_contents_download"]) && $_POST["hid_all_contents_download"] != '' ){
		$arr_input["all_contents_download"] = $_POST["hid_all_contents_download"] ?? "";
	} else {
		$arr_input["all_contents_download"] = '';
	}
	
	if( isset($_POST["all_contents_download_before"]) && $_POST["all_contents_download_before"] != '' ){
		$arr_input["all_contents_download_before"] = $_POST["all_contents_download_before"];
	} else {
		$arr_input["all_contents_download_before"] = '';
	}
	
	// カテゴリ
	$term_id = '';
	$arr_input["term_id"] = (isset($_POST['term_id']) ) ? $_POST['term_id'] : "";
	$arr_input["arr_term_id"] = (isset($_POST['arr_term_id']) && is_array($_POST['arr_term_id'])) ? $_POST['arr_term_id'] : [];
	$str_term_id = "";
	if(isset($arr_input["arr_term_id"]) && is_array($arr_input["arr_term_id"])){
		$cnt = 1;
		foreach($arr_input["arr_term_id"] as $val){
			$str_term_id .= $cnt.','.get_product_category_name_html($val).'<br />';
			$term_id .= $val.',';
			$cnt++;
		}
		$arr_input['term_id'] = rtrim($term_id, ',');
		$arr_input['str_term_id'] = rtrim($str_term_id, ',');
	} else {
		$arr_input["arr_term_id"] = [];
		$arr_input['term_id'] = "";
		$arr_input['str_term_id'] = "";
	}

	//var_dump($_POST['mid']);

	// 修正時の対象ID
	if (isset($_POST['mid'])){
		$arr_input['mid'] = $_POST['mid'];
	}

	$template->assign('arr_input', $arr_input);
	$template->assign('arr_term_id', $arr_input["arr_term_id"]);
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	// 処理分岐
	switch($_POST['act']){
		// 確認
		case 'confirm':
			$err_msg = err_check($template, $arr_input);

			// 入力エラーなし
			if(empty($err_msg)){
				//var_dump($arr_input);
				$template->assign('csrf_token', csrf_token_get());
				$template->admin_layout('product/add_confirm.tpl');
			// 入力エラーあり
			} else {
				$template->assign('err_style', 'style="background-color:red;"');
				$template->assign('productcategory_list', get_product_category());
				$template->assign('csrf_token', csrf_token_get());
				$template->admin_layout('product/add.tpl');
			}
			break;
			
		// 完了
		case 'complete':
			csrf_token_verify();
			$err_flag = 0;
			$err_msg = err_check($template, $arr_input);
			// 改竄なし
			if(empty($err_msg)){
				// トランザクション開始
				$objDbConnect->tran_begin();
				
				// 商品修正
				if(isset($arr_input["mid"])){
					$sql = "UPDATE";
					$sql.= "  tbl_product";
					$sql.= " SET";
					//$sql.= "  product_type = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_type"])."',";
					$sql.= "  product_type = '2',";
					$sql.= "  product_name = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					$sql.= "  product_code = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_code"])."',";
					$sql.= "  price = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					$sql.= "  discount_code = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["discount_code"])."',";

					if( $arr_input["start_date"] != '' && $arr_input["start_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  start_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
					} else {
						$sql.= "  start_date = null,";
					}
					if( $arr_input["end_date"] != '' && $arr_input["end_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  end_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
					} else {
						$sql.= "  end_date = null,";
					}

					//$sql.= "  open_period = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["open_period"])."',";
					$sql.= "  open_period = '0',";
					$sql.= "  thumbnail = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
					$sql.= "  thumbnail1 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail1"])."',";
					$sql.= "  thumbnail2 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail2"])."',";
					$sql.= "  thumbnail3 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail3"])."',";
					$sql.= "  thumbnail4 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail4"])."',";
					$sql.= "  thumbnail5 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail5"])."',";
					$sql.= "  thumbnail6 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail6"])."',";
					$sql.= "  thumbnail7 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail7"])."',";
					$sql.= "  thumbnail8 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail8"])."',";
					$sql.= "  memo = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo"])."',";
					$sql.= "  play_time = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["play_time"])."',";
					$sql.= "  teacher = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["teacher"])."',";
					$sql.= "  teacher_student_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["teacher_student_id"])."',";

					if( $arr_input["exam2_id"] != '' ){
						$sql.= "  exam2_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam2_id"])."',";
					}

					for($i=1; $i<=MAX_CONTENTS; $i++){
					//	if($arr_input["contents_thumbnail$i"] != ''
					//	&& $arr_input["contents_contents$i"] != ''
					//	&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  contents_thumbnail$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_thumbnail$i"])."',";
							$sql.= "  contents_contents$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents$i"])."',";
							$sql.= "  contents_contents".$i."_name = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents".$i."_name"])."',";

							$sql.= "  contents_contents".$i."so = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents".$i."so"])."',";
							$sql.= "  contents_contents".$i."so_name = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents".$i."so_name"])."',";

							$sql.= "  contents_free_time$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_free_time$i"])."',";

							if( $arr_input["contents_start_date$i"] != '' && $arr_input["contents_start_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "  contents_start_date$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_start_date$i"])."',";
							} else {
								$sql.= "  contents_start_date$i = null,";
							}
							if( $arr_input["contents_end_date$i"] != '' && $arr_input["contents_end_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "  contents_end_date$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_end_date$i"])."',";
							} else {
								$sql.= "  contents_end_date$i = null,";
							}

							$sql.= "  contents_memo$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_memo$i"])."',";
							$sql.= "  contents_teacher$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_teacher$i"])."',";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= "  contents_download".$i."_".$j." = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_download".$i."_".$j])."',";
								$sql.= "  contents_download_before".$i."_".$j." = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_download_before".$i."_".$j])."',";
							}
					//	}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  related_products$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["related_products$i"])."',";
					}
				/*
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  free_html_area$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"])."',";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  free_html_area$i"."_sp = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"."_sp"])."',";
					}
				*/
					$sql.= "  term_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["term_id"])."',";
					$sql.= "  update_date = '".date('Y-m-d H:i:s')."'";
					$sql.= " WHERE";
					$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
					$ret = $objDbConnect->execute($sql);
					if(!$ret){
						$err_flag = 1;
					} else {
						// tbl_product_addのデータ更新
						$sql = "UPDATE";
						$sql.= "  tbl_product_add";
						$sql.= " SET";
						$sql.= "  all_contents_download = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download"])."',";
						$sql.= "  all_contents_download_before = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download_before"])."'";
						$sql.= " WHERE";
						$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
						$ret = $objDbConnect->execute($sql);
						if(!$ret){
							$err_flag = 1;
						} else {
							// tbl_product_elearningにデータ登録
							$str_product_flg = '';
							if ( is_array($arr_input["product_flg"]) ){
								$str_product_flg = '|';
								foreach ($arr_input["product_flg"] as $val){
									$str_product_flg.= $val.'|';
								}
							}
							
							$str_product_disp_warning_word = '';
							if ( is_array($arr_input["product_disp_warning_word"]) ){
								$str_product_disp_warning_word = '';
								foreach ($arr_input["product_disp_warning_word"] as $val){
									if($str_product_disp_warning_word == ''){
										$str_product_disp_warning_word = $val;
									} else {
										$str_product_disp_warning_word.= ','.$val;
									}
								}
							}
							
							$sql = "UPDATE";
							$sql.= "  tbl_product_elearning";
							$sql.= " SET";
							$sql.= "  product_kind_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_kind_flg"])."',";
							$sql.= "  live_training_product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["live_training_product_id"])."',";
							$sql.= "  product_flg = '".mysqli_real_escape_string($objDbConnect->connect,$str_product_flg)."',";
							$sql.= "  product_disp_warning_word = '".mysqli_real_escape_string($objDbConnect->connect,$str_product_disp_warning_word)."',";
							$sql.= "  search_word = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["search_word"])."'";
							$sql.= " WHERE";
							$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
							$ret = $objDbConnect->execute($sql);
							if(!$ret){
								$err_flag = 1;
							} else {
								// 設問付きeラーニングの情報(rel_product_contents)を削除
								$sql = "DELETE FROM rel_product_contents WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
								$ret = $objDbConnect->execute($sql);
								if(!$ret){
									$err_flag = 1;
								} else {
									// 設問付きeラーニングの場合はrel_product_contentsにデータ登録
									if($arr_input["product_kind_flg"] == '3'){
										for($i=1; $i<=MAX_CONTENTS; $i++){
											$str_disp_warning_word = '';
											if ($arr_input["disp_warning_word$i"] != ''){
												foreach ($arr_input["disp_warning_word$i"] as $val){
													if($str_disp_warning_word == ''){
														$str_disp_warning_word.= $val;
													} else {
														$str_disp_warning_word.= ','.$val;
													}
												}
											}
											
											if($arr_input["exam_id_test$i"]!='' || $arr_input["exam_id_question$i"]!=''){
												$sql = "INSERT INTO rel_product_contents";
												$sql.= "  (";
												$sql.= "   product_id,";
												$sql.= "   contents_no,";
												$sql.= "   exam_id_test,";
												$sql.= "   exam_id_question,";
												$sql.= "   btn_type,";
												$sql.= "   disp_warning_word";
												$sql.= "  )";
												$sql.= " VALUES";
												$sql.= "  (";
												$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."',";
												$sql.= "   '".$i."',";
												$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam_id_test$i"])."',";
												$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam_id_question$i"])."',";
												$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["btn_type$i"])."',";
												$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_disp_warning_word)."'";
												$sql.= "  )";
												$ret = $objDbConnect->execute($sql);
												if(!$ret){
													$err_flag = 1;
													break;
												}
											}
										}
									}
								}
							}
						}
						
					}
					
				// 商品新規登録
				} else {
					$sql = "INSERT INTO tbl_product";
					$sql.= " (";
					$sql.= "  product_type,";
					$sql.= "  product_name,";
					$sql.= "  product_code,";
					$sql.= "  price,";
					$sql.= "  discount_code,";
					if( $arr_input["start_date"] != '' && $arr_input["start_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  start_date,";
					}
					if( $arr_input["end_date"] != '' && $arr_input["end_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  end_date,";
					}

					if( $arr_input["exam2_id"] != '' ){
						$sql.= "  exam2_id,";
					}

					$sql.= "  open_period,";
					$sql.= "  thumbnail,";
					$sql.= "  thumbnail1,";
					$sql.= "  thumbnail2,";
					$sql.= "  thumbnail3,";
					$sql.= "  thumbnail4,";
					$sql.= "  thumbnail5,";
					$sql.= "  thumbnail6,";
					$sql.= "  thumbnail7,";
					$sql.= "  thumbnail8,";
					$sql.= "  memo,";
					$sql.= "  play_time,";
					$sql.= "  teacher,";
					$sql.= "  teacher_student_id,";
					for($i=1; $i<=MAX_CONTENTS; $i++){
					//	if($arr_input["contents_thumbnail$i"] != ''
					//	&& $arr_input["contents_contents$i"] != ''
					//	&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  contents_thumbnail$i,";
							$sql.= "  contents_contents".$i.",";
							$sql.= "  contents_contents".$i."_name,";

							$sql.= "  contents_contents".$i."so,";
							$sql.= "  contents_contents".$i."so_name,";

							$sql.= "  contents_free_time$i,";

							if( $arr_input["contents_start_date$i"] != '' && $arr_input["contents_start_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "contents_start_date$i,";
							}
							if( $arr_input["contents_end_date$i"] != '' && $arr_input["contents_end_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "contents_end_date$i,";
							}
							$sql.= "  contents_memo$i,";
							$sql.= "  contents_teacher$i,";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= " contents_download".$i."_".$j.",";
								$sql.= " contents_download_before".$i."_".$j.",";
							}
					//	}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= " related_products$i,";
					}
				/*
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= " free_html_area$i,";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= " free_html_area$i"."_sp,";
					}
				*/
					$sql.= "  term_id,";
					$sql.= "  regist_date";
					$sql.= " )";
					$sql.= " VALUES";
					$sql.= " (";
					//$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_type"])."',";
					$sql.= "  '2',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_code"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["discount_code"])."',";
					if( $arr_input["start_date"] != '' && $arr_input["start_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
					}
					if( $arr_input["end_date"] != '' && $arr_input["end_date"] != '0000-00-00 00:00:00' ){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
					}

					if( $arr_input["exam2_id"] != '' ){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam2_id"])."',";
					}

					//$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["open_period"])."',";
					$sql.= "  '0',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail1"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail2"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail3"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail4"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail5"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail6"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail7"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail8"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["play_time"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["teacher"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["teacher_student_id"])."',";
					for($i=1; $i<=MAX_CONTENTS; $i++){
					//	if($arr_input["contents_thumbnail$i"] != ''
					//	&& $arr_input["contents_contents$i"] != ''
					//	&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_thumbnail$i"])."',";
							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents$i"])."',";
							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents".$i."_name"])."',";

							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents$i"."so"])."',";
							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_contents".$i."so_name"])."',";

							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_free_time$i"])."',";

							if( $arr_input["contents_start_date$i"] != '' && $arr_input["contents_start_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_start_date$i"])."',";
							}
							if( $arr_input["contents_end_date$i"] != '' && $arr_input["contents_end_date$i"] != '0000-00-00 00:00:00' ){
								$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_end_date$i"])."',";
							}

							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_memo$i"])."',";
							$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents_teacher$i"])."',";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input['contents_download'.$i.'_'.$j])."',";
								$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input['contents_download_before'.$i.'_'.$j])."',";
							}
					//	}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["related_products$i"])."',";
					}
				/*
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"])."',";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"."_sp"])."',";
					}
				*/
					$sql.= "  '".$arr_input["term_id"]."',";
					$sql.= "  '".date('Y-m-d H:i:s')."'";
					$sql.= " )";
					$ret = $objDbConnect->execute($sql);
					if(!$ret){
						$err_flag = 1;
					} else {
						$product_id = mysqli_insert_id($objDbConnect->connect);
						
						// tbl_product_addにデータ登録
						$sql = "INSERT INTO tbl_product_add";
						$sql.= "  (";
						$sql.= "   product_id,";
						$sql.= "   product_type_add,";
						$sql.= "   all_contents_download,";
						$sql.= "   all_contents_download_before";
						$sql.= "  )";
						$sql.= " VALUES";
						$sql.= "  (";
						$sql.= "   '".$product_id."',";
						$sql.= "   '1',";
						$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download"])."',";
						$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download_before"])."'";
						$sql.= "  )";
						$ret = $objDbConnect->execute($sql);
						if(!$ret){
							$err_flag = 1;
						} else {
							// tbl_product_elearningにデータ登録
							$str_product_flg = '';
							if ($arr_input["product_flg"] != ''){
								$str_product_flg = '|';
								foreach ($arr_input["product_flg"] as $val){
									$str_product_flg.= $val.'|';
								}
							}
							
							$str_product_disp_warning_word = '';
							if ($arr_input["product_disp_warning_word"] != ''){
								$str_product_disp_warning_word = '';
								foreach ($arr_input["product_disp_warning_word"] as $val){
									if($str_product_disp_warning_word == ''){
										$str_product_disp_warning_word = $val;
									} else {
										$str_product_disp_warning_word.= ','.$val;
									}
								}
							}
							
							$sql = "INSERT INTO tbl_product_elearning";
							$sql.= "  (";
							$sql.= "   product_id,";
							$sql.= "   product_kind_flg,";
							$sql.= "   live_training_product_id,";
							$sql.= "   product_flg,";
							$sql.= "   product_disp_warning_word,";
							$sql.= "   search_word";
							$sql.= "  )";
							$sql.= " VALUES";
							$sql.= "  (";
							$sql.= "   '".$product_id."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_kind_flg"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["live_training_product_id"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_product_flg)."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_product_disp_warning_word)."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["search_word"])."'";
							$sql.= "  )";
							$ret = $objDbConnect->execute($sql);
							if(!$ret){
								$err_flag = 1;
							} else {
								// 設問付きeラーニングの場合はrel_product_contentsにデータ登録
								if($arr_input["product_kind_flg"] == '3'){
									for($i=1; $i<=MAX_CONTENTS; $i++){
										$str_disp_warning_word = '';
										if ($arr_input["disp_warning_word$i"] != ''){
											foreach ($arr_input["disp_warning_word$i"] as $val){
												if($str_disp_warning_word == ''){
													$str_disp_warning_word.= $val;
												} else {
													$str_disp_warning_word.= ','.$val;
												}
											}
										}
										
										if($arr_input["exam_id_test$i"]!='' || $arr_input["exam_id_question$i"]!=''){
											$sql = "INSERT INTO rel_product_contents";
											$sql.= "  (";
											$sql.= "   product_id,";
											$sql.= "   contents_no,";
											$sql.= "   exam_id_test,";
											$sql.= "   exam_id_question,";
											$sql.= "   btn_type,";
											$sql.= "   disp_warning_word";
											$sql.= "  )";
											$sql.= " VALUES";
											$sql.= "  (";
											$sql.= "   '".$product_id."',";
											$sql.= "   '".$i."',";
											$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam_id_test$i"])."',";
											$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["exam_id_question$i"])."',";
											$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["btn_type$i"])."',";
											$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_disp_warning_word)."'";
											$sql.= "  )";
											$ret = $objDbConnect->execute($sql);
											if(!$ret){
												$err_flag = 1;
												break;
											}
										}
									}
								}
								
							}
						}
					}
				}
				
			// 改竄あり
			} else {
				$err_flag = 1;
			}
			
			if ($err_flag){
				$objDbConnect->rollback();
			} else {
				$objDbConnect->commit();
				
				// 新着情報の記事を投稿する
				if(isset($arr_input["mid"])){
					insert_wp_posted_article_product($arr_input["mid"], 1);
				} else {
					insert_wp_posted_article_product($product_id);
				}
			}
			
			$template->assign('err_flag', $err_flag);
			$template->admin_layout('product/add_complete.tpl');
			break;
			
		// 修正初期表示
		case 'edit':
			$template->assign('productcategory_list', get_product_category());
			$template->assign('csrf_token', csrf_token_get());
			$template->admin_layout('product/add.tpl');
			break;

		// 戻る
		case 'back':
			$template->assign('productcategory_list', get_product_category());
			$template->assign('csrf_token', csrf_token_get());
			$template->admin_layout('product/add.tpl');
			break;

		// サムネイル画像アップロード
		case 'upload':
			$template->assign('productcategory_list', get_product_category());
			$err_msg = err_check($template, $arr_input);
			$template->assign('csrf_token', csrf_token_get());
			$template->admin_layout('product/add.tpl');
			break;
			
		default:
	}
	
}

function err_check($template, $arr_input){
	$err_msg = array();
	// ファイルアップロード時のエラー
	if(isset($_POST['err_msg'])){
		$err_msg['file_err_msg'] = $_POST['err_msg'];
		
	// 確認時のエラー
	} else {
		if(!isset($_POST['fileupload'])){
		/*
			if(cmCheckInput($arr_input['product_type'], 'CK_NUM')){
				$err_msg['product_type'] = '商品種別は必須です。';
			}
		*/
			if(cmCheckInput($arr_input['product_kind_flg'], 'CK_NUM')){
				$err_msg['product_kind_flg'] = '商品種別は必須です。';
			}
			if($arr_input['product_kind_flg']=='2' && cmCheckInput($arr_input['live_training_product_id'], 'CK_NUM') && cmCheckInput($arr_input['live_training_product_name'], 'CK_KARA')){
				$err_msg['product_kind_flg'] = 'e-ライブ商品を登録する場合は商品設定が必須です。';
			}
			if(cmCheckInput($arr_input['product_name'], 'CK_KARA')){
				$err_msg['product_name'] = '商品名は必須です。';
			}
			if(cmCheckInput($arr_input['product_code'], 'CK_KARA')){
				$err_msg['product_code'] = '商品コードは必須です。';
			}
			if(cmCheckInput($arr_input['price'], 'CK_KARA')){
				$err_msg['price'] = '商品価格は必須です。';
			} else {
				if(cmCheckInput($arr_input['price'], 'CK_NUM')){
					$err_msg['price'] = '商品価格は半角数字で入力してください。';
				}
			}
			if(cmCheckInput($arr_input['term_id'], 'CK_KARA')){
				$err_msg['term_id'] = '商品カテゴリは必須です。';
			}
		/*
			if(cmCheckInput($arr_input['open_period'], 'CK_KARA')){
				$err_msg['open_period'] = '購入後公開期間日数は必須です。';
			}
		*/
			// コンテンツは以下の3種類が入力された場合に登録
			// ・コンテンツ○サムネイル画像
			// ・コンテンツ○コンテンツ
			// ・コンテンツ○無料公開範囲(秒)
			// ※コンテンツ1のみ登録必須項目
		/*
			if(cmCheckInput($arr_input['contents_thumbnail1'], 'CK_KARA')){
				$err_msg['contents_thumbnail1'] = 'コンテンツ1サムネイルは必須です。';
			}
		*/
			if( isset(($arr_input["product_flg"])) && is_array($arr_input["product_flg"]) ){
			} else {
				$arr_input["product_flg"] = [];
			}
			for( $i=0;$i<count($arr_input["product_flg"]);$i++ ){
				if( $arr_input["product_flg"][$i]=="3" ){
					$objDbConnect = new DbConnect();
					$sql = "";
					$sql.= "SELECT ";
					$sql.= " tbl_product_elearning.product_id ";
					$sql.= "FROM ";
					$sql.= " tbl_product_elearning ";
					$sql.= " LEFT JOIN tbl_product ON tbl_product_elearning.product_id=tbl_product.product_id ";
					$sql.= "WHERE ";
					$sql.= " tbl_product.del_flg<>1 ";
					$sql.= " AND tbl_product_elearning.product_flg LIKE '%3%' ";
					$arr_temp = $objDbConnect->query_fetch_arr($sql);
					if( count($arr_temp)>=8 ){
						$err_msg['product_flg'] = '「おすすめ（固定）」が設定されている件数が8件を超えています。';
					}
				}
			}

			if(cmCheckInput($arr_input['contents_contents1'], 'CK_KARA')){
				$err_msg['contents_contents1'] = 'コンテンツ1コンテンツは必須です。';
			}
			if(cmCheckInput($arr_input['contents_free_time1'], 'CK_NUM')){
				$err_msg['contents_free_time1'] = 'コンテンツ1無料公開範囲(秒)は必須です。';
			}
			
			if(isset($arr_input['mid'])){
				// 商品修正時の商品IDのエラーチェック実装する。
			}
		}
	}
	$template->assign('err_msg', $err_msg);
	
	return $err_msg;
}
?>
