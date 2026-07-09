<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
ini_set('display_errors', 1);
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
if ($nichibenren_flg){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
$template->assign('login_bar_association_id', $login_bar_association_id);
$template->assign('nichibenren_flg', $nichibenren_flg);

// webフラグ入力項目表示フラグ
$disp_web_flg = false;
if (isset($_POST["mid"])){
	if (is_numeric($_POST["mid"])){
		$sql = "
		SELECT
		  COUNT(*) AS c
		FROM
		  tbl_product_live_training AS T1
		    INNER JOIN
		  ( SELECT product_id FROM rel_product_bar_association WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$_POST["mid"])."' AND atype = '1' AND web_flg = '1' ) AS T2
		      ON T1.product_id = T2.product_id
		WHERE
		  T1.sponsor = '|1|'
		";
		$res = $objDbConnect->query_fetch_arr($sql);
		if ($res){
			if ($res[0]['c'] > 0){
				$disp_web_flg = true;
			}
		}
	}
}
$template->assign('disp_web_flg', $disp_web_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("商品登録");
$template->admin_comment("商品を登録します。");

$sidemenu_html = '<ul>';
if ($nichibenren_flg){
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">会場研修*</a></li>
<li><a href="./../product/index.php" style="font-size:13px">eラーニング*</a></li>
<li><a href="./../product_ethics/index.php" style="font-size:13px">倫理代替措置研修*</a></li>
';
//<li><a href="./../product_passport/index.php" style="font-size:13px">パスポート*</a></li>
} else {
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">自会主催研修</a></li>
<li class="selected"><a href="./../product_live_branch/index.php" style="font-size:10px">日弁連主催研修・他会主催研修</a></li>
';
}
$sidemenu_html.= '</ul>';
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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
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

$tmp = get_mtb_live_training_type();
$mtb_live_training_type = array('' => '選択してください');
foreach ($tmp as $key => $val){
	$mtb_live_training_type[$key] = $val;
}
$template->assign('mtb_live_training_type', $mtb_live_training_type);

$tmp = get_mtb_data('mtb_live_target_flg');
foreach ($tmp as $val){
	$mtb_live_target_flg[$val['id']] = $val['name'];
}
$template->assign('mtb_live_target_flg', $mtb_live_target_flg);
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
$tmp_mtb_bar_association = get_mtb_bar_association();
foreach ($tmp_mtb_bar_association as $key => $val){
	// 自弁護士会を除く
	if ($key != $login_bar_association_id){
		$mtb_bar_association[$key] = $val;
	}
}

$mtb_bar_association = get_mtb_bar_association();
$mtb_bar_association_branch = get_mtb_bar_association_branch();
if(empty($mtb_bar_association) || empty($mtb_bar_association_branch)){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
$template->assign('arr_bar_association', get_bar_association());
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array(
		'training_kind_flg' => "",
		'ethic_flg' => "0",
		'web_flg' => "0",
		'live_start_date' => "",
		'start_date' => "",
		'end_date' => "",
		'limit_date' => "",
		'dates' => "",
		'download_flg' => "1",
		'target_flg' => "1",
		'product_name' => "",
		'product_code' => "",
		'memo1' => "",
		'memo2' => "",
		'memo3' => "",
		'memo4' => "",
		'memo5' => "",
		'contents' => "",
		'hall' => "",
		'capacity' => "",
		'price' => "",
		'all_bar_association_target' => "",

		'arr_term_id' => [],
		'term_id' => "",
		'str_term_id' => "",
	);
	
	$arr_input["bar_association_sponsor_unselect"] = $mtb_bar_association; // 主催
	$arr_input["bar_association_target_unselect"]  = $mtb_bar_association; // 受講対象
	

	$arr_input["thumbnail"] = '';
	for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
		$arr_input["related_products$i"] = "";
		$arr_input["related_products$i".'_name'] = "";
	}
	// 実施弁護士会
	foreach ($mtb_bar_association_branch as $val){
		$bar_association_branch_id = $val["bar_association_branch_id"];
		// 定員
		$arr_input["capacity".$bar_association_branch_id] = "";
		// 会場
		$arr_input["hall".$bar_association_branch_id] = "";
		// 受付(開始)
		$arr_input["receptionist_start_date".$bar_association_branch_id] = "";
		// 受付(終了)
		$arr_input["receptionist_end_date".$bar_association_branch_id] = "";
		// 実施日
		$arr_input["dates".$bar_association_branch_id] = "";
		// Web申込
		$arr_input["web_flg".$bar_association_branch_id] = "";
		// 備考
		$arr_input["contents".$bar_association_branch_id] = "";
		// 現状申込数
		$arr_input["entry_number".$bar_association_branch_id] = "";
	}
	
	$template->assign('arr_input', $arr_input);
	
	$template->assign('productcategory_list', get_product_category());
	$template->admin_layout('product_live_branch/add.tpl');
	
// 初期表示以外
} else {
	//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
	$arr_input = array(
		'training_kind_flg' => $_POST["training_kind_flg"] ?? "",
		'ethic_flg' => $_POST["ethic_flg"] ?? "",
		'web_flg' => $_POST["web_flg"] ?? "",
		'live_start_date' => $_POST["live_start_date"] ?? "",
		'start_date' => $_POST["start_date"] ?? "",
		'end_date' => $_POST["end_date"] ?? "",
		'limit_date' => $_POST["limit_date"] ?? "",
		'dates' => $_POST["dates"] ?? "",
		'download_flg' => $_POST["download_flg"] ?? "",
		'target_flg' => $_POST["target_flg"] ?? "",
		'product_name' => $_POST["product_name"] ?? "",
		'product_code' => $_POST["product_code"] ?? "",
		'memo1' => $_POST["memo1"] ?? "",
		'memo2' => $_POST["memo2"] ?? "",
		'memo3' => $_POST["memo3"] ?? "",
		'memo4' => $_POST["memo4"] ?? "",
		'memo5' => $_POST["memo5"] ?? "",
		'contents' => $_POST["contents"] ?? "",
		'hall' => $_POST["hall"] ?? "",
		'capacity' => $_POST["capacity"] ?? "",
		'price' => $_POST["price"] ?? "",
		'all_bar_association_target' => $_POST["all_bar_association_target"] ?? "",

		'arr_term_id' => $_POST["arr_term_id"] ?? [],
		'term_id' => $_POST["term_id"] ?? "",
		'str_term_id' => $_POST["str_term_id"] ?? "",
	);
	
	// 修正時の対象ID
	if (isset($_POST['mid'])){
		$arr_input['mid'] = $_POST['mid'];
	}
	
	if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["thumbnail"];
	} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
	} else {
		$arr_input["thumbnail"] = '';
	}
/*
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
*/
	for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
		$arr_input["related_products$i"] = $_POST["related_products$i"] ?? "";
		$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'] ?? "";
	}
/*
	for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
		$arr_input["free_html_area$i"] = stripslashes($_POST["free_html_area$i"]);
	}
	for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
		$arr_input["free_html_area$i"."_sp"] = stripslashes($_POST["free_html_area$i"."_sp"]);
	}
*/
	// 主催(select)
	if (isset($_POST["bar_association_sponsor"])){
		foreach ($_POST["bar_association_sponsor"] as $val){
			$arr_input["bar_association_sponsor"][$val] = $mtb_bar_association[$val];
		}
	}
	// 主催(unselect)
	if (isset($_POST["bar_association_sponsor_unselect"])){
		foreach ($_POST["bar_association_sponsor_unselect"] as $val){
			$arr_input["bar_association_sponsor_unselect"][$val] = $mtb_bar_association[$val];
		}
	} else {
		$arr_input["bar_association_sponsor_unselect"] = $mtb_bar_association;
	}
	// 受講対象(select)
	if (isset($_POST["bar_association_target"])){
		foreach ($_POST["bar_association_target"] as $val){
			$arr_input["bar_association_target"][$val] = $mtb_bar_association[$val];
		}
	}
	// 受講対象(unselect)
	if (isset($_POST["bar_association_target_unselect"])){
		foreach ($_POST["bar_association_target_unselect"] as $val){
			$arr_input["bar_association_target_unselect"][$val] = $mtb_bar_association[$val];
		}
	} else {
		$arr_input["bar_association_target_unselect"] = $mtb_bar_association;
	}
	// 実施弁護士会
	foreach ($mtb_bar_association_branch as $val){
		$bar_association_branch_id = $val["bar_association_branch_id"];
		// 定員
		if (isset($_POST["capacity".$bar_association_branch_id])){
			$arr_input["capacity".$bar_association_branch_id] = $_POST["capacity".$bar_association_branch_id];
		}
		// 会場
		if (isset($_POST["hall".$bar_association_branch_id])){
			$arr_input["hall".$bar_association_branch_id] = $_POST["hall".$bar_association_branch_id];
		}
		// 受付(開始)
		if (isset($_POST["receptionist_start_date".$bar_association_branch_id])){
			$arr_input["receptionist_start_date".$bar_association_branch_id] = $_POST["receptionist_start_date".$bar_association_branch_id];
		}
		// 受付(終了)
		if (isset($_POST["receptionist_end_date".$bar_association_branch_id])){
			$arr_input["receptionist_end_date".$bar_association_branch_id] = $_POST["receptionist_end_date".$bar_association_branch_id];
		}
		// 実施日
		if (isset($_POST["dates".$bar_association_branch_id])){
			$arr_input["dates".$bar_association_branch_id] = $_POST["dates".$bar_association_branch_id];
		}
		// Web申込
		if (isset($_POST["web_flg".$bar_association_branch_id])){
			$arr_input["web_flg".$bar_association_branch_id] = $_POST["web_flg".$bar_association_branch_id];
		}
		// 備考
		if (isset($_POST["contents".$bar_association_branch_id])){
			$arr_input["contents".$bar_association_branch_id] = $_POST["contents".$bar_association_branch_id];
		}
		// 現状申込数
		if (isset($_POST['entry_number'])){
			$arr_input["entry_number".$bar_association_branch_id] = get_entry_number($bar_association_branch_id, $_POST['entry_number']);
		}
	}
	
	// カテゴリ
	$term_id = '';
	$arr_input["term_id"] = (isset($_POST['term_id']) ) ? $_POST['term_id'] : "";
	$arr_input["arr_term_id"] = (isset($_POST['arr_term_id']) && is_array($_POST['arr_term_id'])) ? $_POST['arr_term_id'] : [];
	$str_term_id  = "";
	if(!is_null($arr_input["arr_term_id"]) && is_array($arr_input["arr_term_id"])){
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
				$template->admin_layout('product_live_branch/add_confirm.tpl');
				
			// 入力エラーあり
			} else {
				$template->assign('err_style', 'style="background-color:red;"');
				$template->assign('productcategory_list', get_product_category());
				$template->admin_layout('product_live_branch/add.tpl');
			}
			break;
			
		// 完了
		case 'complete':
			$err_flag = 0;
			$err_msg = err_check($template, $arr_input);
			// 改竄なし
			if(empty($err_msg)){
				// 受講対象の設定
				//if ($arr_input["all_bar_association_target"] == 1){
				//	$bar_association_target = $mtb_bar_association;
				//} else {
				//	$bar_association_target = $arr_input["bar_association_target"];
				//}
				
				// トランザクション開始
				$objDbConnect->tran_begin();
				
				// 商品修正
				if(isset($arr_input["mid"])){
					//$sql = "UPDATE tbl_product SET";
					//$sql.= "  product_type = '2',";
					//$sql.= "  product_name = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					//$sql.= "  product_code = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_code"])."',";
					//$sql.= "  price = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					//$sql.= "  start_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
					//$sql.= "  end_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
					//$sql.= "  open_period = '0',";
					//$sql.= "  thumbnail = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
				/*
					$sql.= "  thumbnail1 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail1"])."',";
					$sql.= "  thumbnail2 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail2"])."',";
					$sql.= "  thumbnail3 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail3"])."',";
					$sql.= "  thumbnail4 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail4"])."',";
					$sql.= "  thumbnail5 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail5"])."',";
					$sql.= "  thumbnail6 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail6"])."',";
					$sql.= "  thumbnail7 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail7"])."',";
					$sql.= "  thumbnail8 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail8"])."',";
				*/
					//for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
					//	$sql.= " related_products$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["related_products$i"])."',";
					//}
				/*
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= " free_html_area$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"])."',";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= " free_html_area$i"."_sp = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["free_html_area$i"."_sp"])."',";
					}
				*/
					//$sql.= "  term_id = '".$arr_input["term_id"]."'";
					//$sql.= " WHERE";
					//$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
					//$ret = $objDbConnect->execute($sql);
					//if(!$ret){
					//	$err_flag = 1;
					//} else {
						// tbl_product_addのデータ更新
						//$sql = "UPDATE tbl_product_add SET";
						//$sql.= "  (";
						//$sql.= "   product_type_add";
						//$sql.= "  )";
						//$sql.= " VALUES";
						//$sql.= "  (";
						//$sql.= "   '2'";
						//$sql.= "  )";
						//$ret = $objDbConnect->execute($sql);
						//if(!$ret){
						//	$err_flag = 1;
						//} else {
							// tbl_product_live_trainingのデータ更新
							//$str_sponsor = '|'; // 主催
							//foreach ($arr_input["bar_association_sponsor"] as $key => $val){
							//	$str_sponsor.= $key.'|';
							//}
							//$str_target = '|'; // 受講対象
							//foreach ($bar_association_target as $key => $val){
							//	$str_target.= $key.'|';
							//}
							
							//$sql = "UPDATE tbl_product_live_training SET";
							//$sql.= "  training_kind_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["training_kind_flg"])."',";
							//if ($nichibenren_flg && $arr_input["ethic_flg"]=='1'){
							//	$sql.= " app_flg = '1',";
							//}
							//$sql.= "  ethic_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["ethic_flg"])."',";
							//$sql.= "  limit_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["limit_date"])."',";
							//$sql.= "  memo1 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo1"])."',";
							//$sql.= "  memo2 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo2"])."',";
							//$sql.= "  memo3 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo3"])."',";
							//$sql.= "  memo4 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo4"])."',";
							//$sql.= "  memo5 = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo5"])."',";
							//$sql.= "  live_start_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["live_start_date"])."',";
							//$sql.= "  download_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["download_flg"])."',";
							//$sql.= "  target_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["target_flg"])."',";
							//$sql.= "  sponsor = '".mysqli_real_escape_string($objDbConnect->connect,$str_sponsor)."',";
							//$sql.= "  target = '".mysqli_real_escape_string($objDbConnect->connect,$str_target)."'";
							//$sql.= " WHERE";
							//$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
							//$ret = $objDbConnect->execute($sql);
							//if(!$ret){
							//	$err_flag = 1;
							//} else {
								// rel_product_bar_associationのデータ更新(主催(登録ユーザーの弁護士会))
								//$sql = "UPDATE rel_product_bar_association SET";
								//$sql.= "  capacity = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["capacity"])."',";
								//$sql.= "  hall = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["hall"])."',";
								//$sql.= "  receptionist_start_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
								//$sql.= "  receptionist_end_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
								//$sql.= "  contents = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents"])."',";
								//$sql.= "  update_at = '".date('Y-m-d H:i:s')."',";
								//$sql.= "  dates = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["dates"])."',";
								//$sql.= "  web_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["web_flg"])."'";
								//$sql.= " WHERE";
								//$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
								//$sql.= "  AND bar_association_id = '".$login_bar_association_id."'";
								//$sql.= "  AND atype = '1'";
								//$ret = $objDbConnect->execute($sql);
								//if(!$ret){
								//	$err_flag = 1;
								//} else {
									// rel_product_bar_associationのデータ削除(受講対象のみ)
									//$sql = "DELETE FROM rel_product_bar_association WHERE";
									//$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
									//$sql.= "  AND atype = '3'";
									//$ret = $objDbConnect->execute($sql);
									//if(!$ret){
									//	$err_flag = 1;
									//} else {
										// rel_product_bar_associationのデータ再登録(受講対象のみ)
										//foreach ($bar_association_target as $key => $val){
										//	$sql = "INSERT INTO rel_product_bar_association";
										//	$sql.= "  (";
										//	$sql.= "   product_id,";
										//	$sql.= "   bar_association_id,";
										//	$sql.= "   atype,";
										//	$sql.= "   update_at";
										//	$sql.= "  )";
										//	$sql.= " VALUES";
										//	$sql.= "  (";
										//	$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."',";
										//	$sql.= "   '".$key."',";
										//	$sql.= "   '3',";
										//	$sql.= "   '".date('Y-m-d H:i:s')."'";
										//	$sql.= "  )";
										//	$ret = $objDbConnect->execute($sql);
										//	if(!$ret){
										//		$err_flag = 1;
										//		break;
										//	}
										//}
										
										//if ($nichibenren_flg){
										//	if ($err_flag != 1){
										
												// rel_product_bar_association_branchのデータ登録or更新(実施弁護士会情報)
												$sql = "
												SELECT
												  T1.bar_association_branch_id,
												  T1.bar_association_branch_name
												FROM
												  (SELECT * FROM mtb_bar_association_branch WHERE bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$login_bar_association_id)."') AS T1
												    LEFT JOIN
												  (SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."') AS T2
												      ON T1.bar_association_branch_id = T2.bar_association_branch_id
												";
												$arr_login_bar_association_branch = $objDbConnect->query_fetch_arr($sql);
												if(!$arr_login_bar_association_branch){
													$err_flag = 1;
												} else {
													foreach ($arr_login_bar_association_branch as $val){
														$bar_association_branch_id = $val["bar_association_branch_id"];
														if (($arr_input["capacity".$bar_association_branch_id] !== ""
														 && $arr_input["hall".$bar_association_branch_id] !== ""
														 && $arr_input["receptionist_start_date".$bar_association_branch_id] !== ""
														 && $arr_input["receptionist_end_date".$bar_association_branch_id] !== ""
														 && $arr_input["dates".$bar_association_branch_id] !== ""
														 && $arr_input["web_flg".$bar_association_branch_id] !== ""
														 ) || $arr_input["web_flg".$bar_association_branch_id] === "2"){
														 	$sql = "SELECT";
															$sql.= "  count(*) AS c";
															$sql.= " FROM";
															$sql.= "  rel_product_bar_association_branch";
															$sql.= " WHERE";
															$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
															$sql.= "  AND bar_association_branch_id = '".$bar_association_branch_id."'";
														 	$ret = $objDbConnect->query_fetch($sql);
														 	// 登録
														 	if ($ret["c"] == 0){
														 		$sql = "INSERT INTO rel_product_bar_association_branch";
																$sql.= "  (";
																$sql.= "   product_id,";
																$sql.= "   bar_association_branch_id,";
																$sql.= "   capacity,";
																$sql.= "   hall,";
																$sql.= "   receptionist_start_date,";
																$sql.= "   receptionist_end_date,";
																$sql.= "   contents,";
																$sql.= "   update_at,";
																$sql.= "   dates,";
																$sql.= "   web_flg";
																$sql.= "  )";
																$sql.= " VALUES";
																$sql.= "  (";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."',";
																$sql.= "   '".$bar_association_branch_id."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["capacity".$bar_association_branch_id])."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["hall".$bar_association_branch_id])."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_start_date".$bar_association_branch_id])."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_end_date".$bar_association_branch_id])."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents".$bar_association_branch_id])."',";
																$sql.= "   '".date('Y-m-d H:i:s')."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["dates".$bar_association_branch_id])."',";
																$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["web_flg".$bar_association_branch_id])."'";
																$sql.= "  )";
																$ret = $objDbConnect->execute($sql);
																if(!$ret){
																	$err_flag = 1;
																	break;
																}
														 	// 更新
														 	} else {
														 		$sql = "UPDATE rel_product_bar_association_branch SET";
																$sql.= "  capacity = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["capacity".$bar_association_branch_id])."',";
																$sql.= "  hall = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["hall".$bar_association_branch_id])."',";
																$sql.= "  receptionist_start_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_start_date".$bar_association_branch_id])."',";
																$sql.= "  receptionist_end_date = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_end_date".$bar_association_branch_id])."',";
																$sql.= "  contents = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents".$bar_association_branch_id])."',";
																$sql.= "  update_at = '".date('Y-m-d H:i:s')."',";
																$sql.= "  dates = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["dates".$bar_association_branch_id])."',";
																$sql.= "  web_flg = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["web_flg".$bar_association_branch_id])."'";
																$sql.= " WHERE";
																$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
																$sql.= "  AND bar_association_branch_id = '".$bar_association_branch_id."'";
																$ret = $objDbConnect->execute($sql);
																if(!$ret){
																	$err_flag = 1;
																	break;
																}
														 	}
														}
													}
												}
												
											//}
										//}
										
									//}
								//}
							//}
						//}
					//}
					
				// 商品新規登録
				} else {
if (false){
					$sql = "INSERT INTO tbl_product";
					$sql.= " (";
					$sql.= "  product_type,";
					$sql.= "  product_name,";
					$sql.= "  product_code,";
					$sql.= "  price,";
					$sql.= "  start_date,";
					$sql.= "  end_date,";
					$sql.= "  open_period,";
					$sql.= "  thumbnail,";
				/*
					$sql.= "  thumbnail1,";
					$sql.= "  thumbnail2,";
					$sql.= "  thumbnail3,";
					$sql.= "  thumbnail4,";
					$sql.= "  thumbnail5,";
					$sql.= "  thumbnail6,";
					$sql.= "  thumbnail7,";
					$sql.= "  thumbnail8,";
				*/
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
					$sql.= "  term_id";
					$sql.= " )";
					$sql.= " VALUES";
					$sql.= " (";
					$sql.= "  '2',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_code"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
					$sql.= "  '0',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
				/*
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail1"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail2"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail3"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail4"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail5"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail6"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail7"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail8"])."',";
				*/
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
					$sql.= "  '".$arr_input["term_id"]."'";
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
						$sql.= "   product_type_add";
						$sql.= "  )";
						$sql.= " VALUES";
						$sql.= "  (";
						$sql.= "   '".$product_id."',";
						$sql.= "   '2'";
						$sql.= "  )";
						$ret = $objDbConnect->execute($sql);
						if(!$ret){
							$err_flag = 1;
						} else {
							// tbl_product_live_trainingにデータ登録
							$str_sponsor = '|'; // 主催
							foreach ($arr_input["bar_association_sponsor"] as $key => $val){
								$str_sponsor.= $key.'|';
							}
							$str_target = '|'; // 受講対象
							foreach ($bar_association_target as $key => $val){
								$str_target.= $key.'|';
							}
							
							$sql = "INSERT INTO tbl_product_live_training";
							$sql.= "  (";
							$sql.= "   product_id,";
							$sql.= "   training_kind_flg,";
							if ($nichibenren_flg && $arr_input["ethic_flg"]=='1'){
								$sql.= " app_flg,";
							}
							$sql.= "   ethic_flg,";
							$sql.= "   limit_date,";
							$sql.= "   memo1,";
							$sql.= "   memo2,";
							$sql.= "   memo3,";
							$sql.= "   memo4,";
							$sql.= "   memo5,";
							$sql.= "   live_start_date,";
							$sql.= "   download_flg,";
							$sql.= "   target_flg,";
							$sql.= "   sponsor,";
							$sql.= "   target";
							$sql.= "  )";
							$sql.= " VALUES";
							$sql.= "  (";
							$sql.= "   '".$product_id."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["training_kind_flg"])."',";
							if ($nichibenren_flg && $arr_input["ethic_flg"]=='1'){
								$sql.= " '1',";
							}
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["ethic_flg"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["limit_date"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo1"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo2"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo3"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo4"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo5"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["live_start_date"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["download_flg"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["target_flg"])."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_sponsor)."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_target)."'";
							$sql.= "  )";
							$ret = $objDbConnect->execute($sql);
							if(!$ret){
								$err_flag = 1;
							} else {
								// rel_product_bar_associationにデータ登録(主催(登録ユーザーの弁護士会))
								$sql = "INSERT INTO rel_product_bar_association";
								$sql.= "  (";
								$sql.= "   product_id,";
								$sql.= "   bar_association_id,";
								$sql.= "   atype,";
								$sql.= "   capacity,";
								$sql.= "   hall,";
								$sql.= "   receptionist_start_date,";
								$sql.= "   receptionist_end_date,";
								$sql.= "   contents,";
								$sql.= "   update_at,";
								$sql.= "   dates,";
								$sql.= "   web_flg";
								$sql.= "  )";
								$sql.= " VALUES";
								$sql.= "  (";
								$sql.= "   '".$product_id."',";
								$sql.= "   '".$login_bar_association_id."',";
								$sql.= "   '1',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["capacity"])."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["hall"])."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["start_date"])."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["end_date"])."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents"])."',";
								$sql.= "   '".date('Y-m-d H:i:s')."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["dates"])."',";
								$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["web_flg"])."'";
								$sql.= "  )";
								$ret = $objDbConnect->execute($sql);
								if(!$ret){
									$err_flag = 1;
								} else {
									// rel_product_bar_associationにデータ登録(受講対象)
									foreach ($bar_association_target as $key => $val){
										$sql = "INSERT INTO rel_product_bar_association";
										$sql.= "  (";
										$sql.= "   product_id,";
										$sql.= "   bar_association_id,";
										$sql.= "   atype,";
										$sql.= "   update_at";
										$sql.= "  )";
										$sql.= " VALUES";
										$sql.= "  (";
										$sql.= "   '".$product_id."',";
										$sql.= "   '".$key."',";
										$sql.= "   '3',";
										$sql.= "   '".date('Y-m-d H:i:s')."'";
										$sql.= "  )";
										$ret = $objDbConnect->execute($sql);
										if(!$ret){
											$err_flag = 1;
											break;
										}
									}
									
									if ($nichibenren_flg){
										if ($err_flag != 1){
											// rel_product_bar_association_branchにデータ登録(実施弁護士会情報)
											foreach ($mtb_bar_association_branch as $val){
												$bar_association_branch_id = $val["bar_association_branch_id"];
												if (($arr_input["capacity".$bar_association_branch_id] !== ""
												 && $arr_input["hall".$bar_association_branch_id] !== ""
												 && $arr_input["receptionist_start_date".$bar_association_branch_id] !== ""
												 && $arr_input["receptionist_end_date".$bar_association_branch_id] !== ""
												 && $arr_input["dates".$bar_association_branch_id] !== ""
												 && $arr_input["web_flg".$bar_association_branch_id] !== ""
												 ) || $arr_input["web_flg".$bar_association_branch_id] === "2"){
													$sql = "INSERT INTO rel_product_bar_association_branch";
													$sql.= "  (";
													$sql.= "   product_id,";
													$sql.= "   bar_association_branch_id,";
													$sql.= "   capacity,";
													$sql.= "   hall,";
													$sql.= "   receptionist_start_date,";
													$sql.= "   receptionist_end_date,";
													$sql.= "   contents,";
													$sql.= "   update_at,";
													$sql.= "   dates,";
													$sql.= "   web_flg";
													$sql.= "  )";
													$sql.= " VALUES";
													$sql.= "  (";
													$sql.= "   '".$product_id."',";
													$sql.= "   '".$bar_association_branch_id."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["capacity".$bar_association_branch_id])."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["hall".$bar_association_branch_id])."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_start_date".$bar_association_branch_id])."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["receptionist_end_date".$bar_association_branch_id])."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["contents".$bar_association_branch_id])."',";
													$sql.= "   '".date('Y-m-d H:i:s')."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["dates".$bar_association_branch_id])."',";
													$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["web_flg".$bar_association_branch_id])."'";
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
			}
			
			$template->assign('err_flag', $err_flag);
			$template->admin_layout('product_live_branch/add_complete.tpl');
			break;
			
		// 修正初期表示
		case 'edit':
			$template->assign('productcategory_list', get_product_category());
			$template->admin_layout('product_live_branch/add.tpl');
			break;
			
		// 戻る
		case 'back':
			$template->assign('productcategory_list', get_product_category());
			$template->admin_layout('product_live_branch/add.tpl');
			break;
			
		// サムネイル画像アップロード
		case 'upload':
			$template->assign('productcategory_list', get_product_category());
			$err_msg = err_check($template, $arr_input);
			$template->admin_layout('product_live_branch/add.tpl');
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
			if(cmCheckInput($arr_input['training_kind_flg'], 'CK_NUM')){
				$err_msg['training_kind_flg'] = '研修種別は必須です。';
			}
			if(cmCheckInput($arr_input['ethic_flg'], 'CK_NUM')){
				$err_msg['ethic_flg'] = '倫理研修は必須です。';
			}
			if(cmCheckInput($arr_input['web_flg'], 'CK_NUM')){
				$err_msg['web_flg'] = 'Web申込は必須です。';
			}
			if(cmCheckInput($arr_input['live_start_date'], 'CK_KARA')){
				$err_msg['live_start_date'] = '公開開始日は必須です。';
			}
			if(cmCheckInput($arr_input['start_date'], 'CK_KARA')){
				$err_msg['start_date'] = '受付期間(開始)は必須です。';
			}
			if(cmCheckInput($arr_input['end_date'], 'CK_KARA')){
				$err_msg['end_date'] = '受付期間(終了)は必須です。';
			}
			if(cmCheckInput($arr_input['limit_date'], 'CK_KARA')){
				$err_msg['limit_date'] = '受講振り込み期限は必須です。';
			}
			if(cmCheckInput($arr_input['dates'], 'CK_KARA')){
				$err_msg['dates'] = '開催日は必須です。';
			}
			if(cmCheckInput($arr_input['download_flg'], 'CK_NUM')){
				$err_msg['download_flg'] = '受講票ダウンロードは必須です。';
			}
			if(cmCheckInput($arr_input['target_flg'], 'CK_NUM')){
				$err_msg['target_flg'] = '受講対象者は必須です。';
			}
			if(cmCheckInput($arr_input['all_bar_association_target'], 'CK_NUM')){
				if(empty($arr_input['bar_association_target'])){
					$err_msg['bar_association_target'] = '受講対象を選択してください。';
				}
			}
			if(cmCheckInput($arr_input['product_name'], 'CK_KARA')){
				$err_msg['product_name'] = '商品名は必須です。';
			}
			if(cmCheckInput($arr_input['product_code'], 'CK_KARA')){
				$err_msg['product_code'] = '商品コードは必須です。';
			}
			if(cmCheckInput($arr_input['capacity'], 'CK_KARA')){
				$err_msg['capacity'] = '定員は必須です。';
			} else {
				if(cmCheckInput($arr_input['capacity'], 'CK_NUM')){
					$err_msg['capacity'] = '定員は半角数字で入力してください。';
				}
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
			if(isset($arr_input['mid'])){
				// 商品修正時の商品IDのエラーチェック実装する。
			}
		*/
		}
	}
	$template->assign('err_msg', $err_msg);
	
	return $err_msg;
}
?>
