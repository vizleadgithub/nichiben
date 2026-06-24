<?php
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
$template->admin_title("商品詳細");
$template->admin_comment("商品を管理します。");

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
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
$template->assign('document_path', DOCUMENT_PATH);
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
$btn_type_list = array('1' => '解答する', '2' => '回答する');
$template->assign('btn_type_list', $btn_type_list);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = '';
if(isset($_GET["mid"])){
	$mid = intval($_GET["mid"]);
}
$template->assign('mid', $mid);

// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array();
	$sql = "select *, (SELECT TP.product_name FROM tbl_product AS TP WHERE TP.product_id = tbl_product_elearning.live_training_product_id) AS live_training_product_name from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_elearning ON tbl_product.product_id = tbl_product_elearning.product_id where tbl_product.product_id='".$mid."'";
	$arr_input = $objDbConnect->query_fetch($sql);
	if ($arr_input){
		// コンテンツ名の取得
		//for ($i=1; $i<=MAX_CONTENTS; $i++){
		//	if ($arr_input["contents_contents$i"] != ''){
		//		$sql = "select video_logic_name from video where video_id='".$arr_input["contents_contents$i"]."'";
		//		$ret1 = $objDbConnect->query_fetch($sql);
		//		if ($ret1){
		//			$arr_input["contents_contents$i".'_name'] = $ret1["video_logic_name"];
		//		}
		//	}
		//}

		for ($i=1; $i<=MAX_CONTENTS; $i++){
			if ($arr_input["contents_start_date$i"] == '0000-00-00 00:00:00'){
				$arr_input["contents_start_date$i"] = "";
			}
		}
		for ($i=1; $i<=MAX_CONTENTS; $i++){
			if ($arr_input["contents_end_date$i"] == '0000-00-00 00:00:00'){
				$arr_input["contents_end_date$i"] = "";
			}
		}

		// 講師受講者の取得
		if ($arr_input["teacher_student_id"] >0){
			$sql = "select student.* from student where student_id='".$arr_input["teacher_student_id"]."' limit 1";
			$ret2 = $objDbConnect->query_fetch($sql);
			if ($ret2){
				$arr_input["teacher_student"] = $ret2;
				$arr_input["teacher_student_name"] = "[".$ret2["lawyer_number"]."]".$ret2["student_name"];

			}
		}

		// 関連商品名の取得
		for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
			if ($arr_input["related_products$i"] != ''){
				$sql = "select product_name from tbl_product where product_id='".$arr_input["related_products$i"]."'";
				$ret2 = $objDbConnect->query_fetch($sql);
				if ($ret2){
					$arr_input["related_products".$i."_name"] = $ret2["product_name"];
				}
			}
		}
		// カテゴリ名の取得
		$term_name = '';
		if (strpos($arr_input['term_id'], ',') !== false){
			$arr_term_id = explode(',', $arr_input['term_id']);
			$cnt = 1;
			foreach($arr_term_id as $val){
				$term_name.= $cnt.','.get_product_category_name_html($val).'<br />';
				$cnt++;
			}
			
		} else {
			$arr_term_id[] = $arr_input['term_id'];
			$term_name = get_product_category_name_html($arr_input['term_id']);
		}
		// フラグ管理
		if ($arr_input["product_flg"] != ''){
			$arr_input["product_flg"] = explode('|', trim($arr_input["product_flg"], '|'));
		}
		// 注意文言の掲載箇所
		if ($arr_input["product_disp_warning_word"] != ''){
			$arr_input["product_disp_warning_word"] = explode(',', $arr_input["product_disp_warning_word"]);
		}
		// 設問付きeラーニング情報
		if($arr_input['product_kind_flg']=='3'){
			$product_contents_info = get_product_contents_info($mid);
			if(!empty($product_contents_info)){
				$pci = array();
				foreach($product_contents_info as $val){
					$pci[$val['contents_no']] = $val;
				}
				for ($i=1; $i<=MAX_CONTENTS; $i++){
					if(isset($pci[$i])){
						$arr_input["exam_id_test$i"] = $pci[$i]['exam_id_test'];
						$arr_input["exam_id_question$i"] = $pci[$i]['exam_id_question'];
						$arr_input["btn_type$i"] = $pci[$i]['btn_type'];
						$arr_input["disp_warning_word$i"] = explode(',', $pci[$i]['disp_warning_word']);
					} else {
						$arr_input["exam_id_test$i"] = "";
						$arr_input["exam_id_question$i"] = "";
						$arr_input["btn_type$i"] = "";
						$arr_input["disp_warning_word$i"] = '';
					}
				}
			}
		} else {
			for ($i=1; $i<=MAX_CONTENTS; $i++){
				$arr_input["exam_id_test$i"] = "";
				$arr_input["exam_id_question$i"] = "";
				$arr_input["btn_type$i"] = "";
				$arr_input["disp_warning_word$i"] = '';
			}
		}
	}
	
	$template->assign('arr_input', $arr_input);
	$template->assign('arr_term_id', $arr_term_id);
	$template->assign('term_name', $term_name);
	$template->admin_layout('product/info.tpl');
	
// 削除
} elseif($_POST['act'] == 'delete') {
	$sql = "update tbl_product set del_flg = '1' where product_id = '$mid'";
	$objDbConnect->execute($sql);
	
	header('Location: index.php');
	exit;
}
?>
