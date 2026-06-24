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
if ($nichibenren_flg){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
$template->assign('nichibenren_flg', $nichibenren_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("商品詳細");
$template->admin_comment("商品を管理します。");

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
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
$mtb_bar_association = get_mtb_bar_association();
if(empty($mtb_bar_association)){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
$template->assign('arr_bar_association', get_bar_association());

/*
$bar_association_name = '';
foreach ($mtb_bar_association as $key => $val){
	if ($key == $login_bar_association_id){
		$bar_association_name = $val;
		break;
	}
}
$template->assign('bar_association_name', $bar_association_name);
*/

$tmp = get_mtb_live_training_type();
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
$mid = '';
if(isset($_GET["mid"])){
	$mid = intval($_GET["mid"]);
}
$template->assign('mid', $mid);

// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array();
	
$sql ="
SELECT
  tbl_product.product_id,
  tbl_product.product_name,
  tbl_product.product_code,
  tbl_product.price,
  tbl_product.term_id,
  DATE_FORMAT(tbl_product.start_date, '%Y/%m/%d %H:%i') AS start_date,
  DATE_FORMAT(tbl_product.end_date, '%Y/%m/%d %H:%i') AS end_date,
  tbl_product.thumbnail,
  tbl_product.related_products1,
  tbl_product.related_products2,
  tbl_product.related_products3,
  tbl_product.related_products4,
  tbl_product.related_products5,
  tbl_product.related_products6,
  tbl_product.related_products7,
  tbl_product.related_products8,
  tbl_product.related_products9,
  tbl_product.related_products10,
  tbl_product_live_training.training_kind_flg,
  tbl_product_live_training.ethic_flg,
  DATE_FORMAT(tbl_product_live_training.limit_date, '%Y/%m/%d %H:%i') AS limit_date,
  tbl_product_live_training.memo1,
  tbl_product_live_training.memo2,
  tbl_product_live_training.memo3,
  tbl_product_live_training.memo4,
  tbl_product_live_training.memo5,
  DATE_FORMAT(tbl_product_live_training.live_start_date, '%Y/%m/%d %H:%i') AS live_start_date,
  tbl_product_live_training.download_flg,
  tbl_product_live_training.target_flg,
  tbl_product_live_training.sponsor,
  tbl_product_live_training.target,
  RPBA.capacity,
  RPBA.hall,
  DATE_FORMAT(RPBA.receptionist_start_date, '%Y/%m/%d %H:%i') AS receptionist_start_date,
  DATE_FORMAT(RPBA.receptionist_end_date, '%Y/%m/%d %H:%i') AS receptionist_end_date,
  RPBA.contents,
  DATE_FORMAT(RPBA.dates, '%Y/%m/%d %H:%i') AS dates,
  RPBA.web_flg
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
    INNER JOIN
  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA
      ON tbl_product.product_id = RPBA.product_id
WHERE
  tbl_product.del_flg = 0
  AND tbl_product.product_id = ".mysqli_real_escape_string($objDbConnect->connect,$mid)."
  AND tbl_product_add.product_type_add = 2
";
	$arr_input = $objDbConnect->query_fetch($sql);
	
	if ($arr_input){
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
		// 主催
		$str_sponsor = trim($arr_input['sponsor'], '|');
		$arr_sponsor = explode('|', $str_sponsor);
		foreach ($mtb_bar_association as $key => $val){
			// select
			if (array_search($key, $arr_sponsor) !== false){
				$arr_input['bar_association_sponsor'][$key] = $val;
			// unselect
			} else {
				$arr_input['bar_association_sponsor_unselect'][$key] = $val;
			}
		}
		// 受講対象
		$str_target = trim($arr_input['target'], '|');
		$arr_target = explode('|', $str_target);
		// すべての弁護士会が対象の場合
		if (count($mtb_bar_association) == count($arr_target)){
			$arr_input['all_bar_association_target'] = '1';
			foreach ($mtb_bar_association as $key => $val){
				// unselect
				$arr_input['bar_association_target_unselect'][$key] = $val;
			}
		} else {
			$arr_input['all_bar_association_target'] = '';
			foreach ($mtb_bar_association as $key => $val){
				// select
				if (array_search($key, $arr_target) !== false){
					$arr_input['bar_association_target'][$key] = $val;
				// unselect
				} else {
					$arr_input['bar_association_target_unselect'][$key] = $val;
				}
			}
		}
		// 実施弁護士会
$sql = "
SELECT
  T1.bar_association_branch_id,
  T1.bar_association_branch_name,
  T2.capacity,
  T2.hall,
  T2.receptionist_start_date,
  T2.receptionist_end_date,
  T2.contents,
  T2.dates,
  T2.web_flg
FROM
  (SELECT * FROM mtb_bar_association_branch WHERE bar_association_id = $login_bar_association_id) AS T1
    LEFT JOIN
  (SELECT * FROM rel_product_bar_association_branch WHERE product_id = $mid) AS T2
      ON T1.bar_association_branch_id = T2.bar_association_branch_id
";
		$ret = $objDbConnect->query_fetch_arr($sql);
		if ($ret){
			$branch_info = array();
			foreach ($ret as $val){
				$bar_association_branch_id = $val['bar_association_branch_id'];
				if ($val['capacity'] == 0){
					$arr_input['capacity'.$bar_association_branch_id] = '';
				} else {
					$arr_input['capacity'.$bar_association_branch_id] = $val['capacity'];
				}
				$arr_input['hall'.$bar_association_branch_id] = $val['hall'];
				if ($val['receptionist_start_date'] == '0000-00-00 00:00:00'){
					$arr_input['receptionist_start_date'.$bar_association_branch_id] = '';
				} else {
					$arr_input['receptionist_start_date'.$bar_association_branch_id] = $val['receptionist_start_date'];
				}
				if ($val['receptionist_end_date'] == '0000-00-00 00:00:00'){
					$arr_input['receptionist_end_date'.$bar_association_branch_id] = '';
				} else {
					$arr_input['receptionist_end_date'.$bar_association_branch_id] = $val['receptionist_end_date'];
				}
				if ($val['dates'] == '0000-00-00 00:00:00'){
					$arr_input['dates'.$bar_association_branch_id] = '';
				} else {
					$arr_input['dates'.$bar_association_branch_id] = $val['dates'];
				}
				$arr_input['web_flg'.$bar_association_branch_id] = $val['web_flg'];
				$arr_input['contents'.$bar_association_branch_id] = $val['contents'];
				
				$branch_info[$bar_association_branch_id]['bar_association_branch_name'] = $val['bar_association_branch_name'];
				if ($val['capacity'] == 0){
					$branch_info[$bar_association_branch_id]['capacity'] = '';
				} else {
					$branch_info[$bar_association_branch_id]['capacity'] = $val['capacity'];
				}
				$branch_info[$bar_association_branch_id]['hall'] = $val['hall'];
				if ($val['receptionist_start_date'] == '0000-00-00 00:00:00'){
					$branch_info[$bar_association_branch_id]['receptionist_start_date'] = '';
				} else {
					$branch_info[$bar_association_branch_id]['receptionist_start_date'] = $val['receptionist_start_date'];
				}
				if ($val['receptionist_end_date'] == '0000-00-00 00:00:00'){
					$branch_info[$bar_association_branch_id]['receptionist_end_date'] = '';
				} else {
					$branch_info[$bar_association_branch_id]['receptionist_end_date'] = $val['receptionist_end_date'];
				}
				if ($val['dates'] == '0000-00-00 00:00:00'){
					$branch_info[$bar_association_branch_id]['dates'] = '';
				} else {
					$branch_info[$bar_association_branch_id]['dates'] = $val['dates'];
				}
				$branch_info[$bar_association_branch_id]['web_flg'] = $val['web_flg'];
				$branch_info[$bar_association_branch_id]['contents'] = $val['contents'];
				$branch_info[$bar_association_branch_id]['entry_number'] = get_entry_number($bar_association_branch_id, $mid);
			}
		}
	}
	
	$template->assign('arr_input', $arr_input);
	$template->assign('branch_info', $branch_info);
	$template->assign('arr_term_id', $arr_term_id);
	$template->assign('term_name', $term_name);
	$template->admin_layout('product_live_branch/info.tpl');
	
// 削除
} elseif($_POST['act'] == 'delete') {
	//$sql = "update tbl_product set del_flg = '1' where product_id = '$mid'";
	//$objDbConnect->execute($sql);
	
	header('Location: index.php');
	exit;
}
?>
