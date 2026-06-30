<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//print("<!--[test1]-->");
include(dirname(__FILE__) ."./../../module/module.php");
//print("<!--[test2]-->");
$_SESSION['wp_page_head_title'] = '検索';
$objDbConnect = new DbConnect();

//ini_set('display_errors', 1);

$objPager = new Pager();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$st_login_check = st_login_check();
$mtb_bar_association = get_mtb_bar_association();
$mtb_product_flg = get_mtb_product_flg_icon();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_search_training = array(
	'1' => 'eラーニング',
	'2' => '日弁連主催会場研修(ライブ実務研修等)',
	'3' => '弁護士会主催会場研修'
);
$template->assign('arr_search_training', $arr_search_training);

//$arr_search_sponsor = array(
//	'1' => '日弁連',
//	'2' => '弁護士会',
//	'3' => 'ロースクール',
//	'4' => '法務研究財団',
//	'5' => 'その他',
//);
//$template->assign('arr_search_sponsor', $arr_search_sponsor);

$arr_search_state = array(
	'1' => '受講中の講座',
	'2' => '未受講の講座',
	'3' => '受講完了の講座',
);
$template->assign('arr_search_state', $arr_search_state);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT";
$sql.= "  wp_terms.term_id,";
$sql.= "  wp_terms.name,";
$sql.= "  wp_term_taxonomy.parent";
$sql.= " FROM";
$sql.= "  wp_terms ";
$sql.= "   JOIN";
$sql.= "  wp_term_taxonomy AS wp_term_taxonomy";
$sql.= "   ON wp_terms.term_id = wp_term_taxonomy.term_id";
$sql.= "";
$sql.= " WHERE ";
$sql.= "  wp_terms.term_id <> 300 ";
$sql.= "";
$sql.= " ORDER BY wp_terms.slug ASC";
//print("<!--[1timecheck:start".date("H:i:s")."]-->\n");
$ret = $objDbConnect->query_fetch_arr($sql);
//print("<!--[1timecheck:end".date("H:i:s")."]-->\n");
$temp_category_list = $ret;
$template->assign('arr_cat_list', $ret);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_category = array();
$search_keyword = array();
$search_type = "OR";
$search_price_free = "1";
$search_training = array();
//$search_sponsor = array();
$search_state = array();
$search_start_date = "";
$search_end_date = "";
$search_start_contents_date = "";
$search_end_contents_date = "";

$pagemax = 5;

//$msg_flg  = false; // メッセージの表示フラグ
$disp_flg = false; // 初回表示フラグ

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
	$disp_flg = true;
}

//var_dump($_SERVER["REQUEST_METHOD"]);


if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	//print("<!--[".$_SERVER["REQUEST_METHOD"]."]-->\n");
	//$msg_flg  = true;
	$disp_flg = true;

	$search_type = $_POST["search_type"];
	$search_category = array_filter((is_array($_POST["search_category"]) ? $_POST["search_category"] : []), 'strlen');
	$search_keyword = array_filter(
		explode(
			" ",
			preg_replace(
				'/\s+/', ' ', 
				mb_str_replace(
					"　", 
					" ", 
					trim($_POST["search_keyword"])
				)
			)
		), 
		'strlen'
	);
	//var_dump($_POST["search_keyword"]);
	//var_dump($search_keyword);
	//exit();
	$search_price_free = $_POST["search_price_free"];
	$search_training = $_POST["search_training"];
	//$search_sponsor = $_POST["search_sponsor"];
	$search_state = $_POST["search_state"];
//var_dump($search_state);
	$search_start_date = $_POST["search_start_date"];
	$search_end_date = $_POST["search_end_date"];
	$search_start_contents_date = $_POST["search_start_contents_date"];
	$search_end_contents_date = $_POST["search_end_contents_date"];

	$page = 1;
	if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
		$page = $_GET["page"];
	}

	$pagemax = 5;
	if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
		$pagemax = $_GET["pagemax"];
	}

	$sort = 1;
	if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
		$sort = $_GET["sort"];
	}

} else {
	//print("<!--[".$_SERVER["REQUEST_METHOD"]."]-->\n");
	//$msg_flg  = true;
	$disp_flg = true;

	$search_type = $_GET["search_type"];
	$search_category = array_filter( explode(  "|",  trim($_GET["search_category"])  ), 'strlen' );
	$search_keyword = array_filter(
		explode(
			" ",
			preg_replace(
				'/\s+/', 
				' ', 
				mb_str_replace(
					"　", 
					" ", 
					trim($_GET["search_keyword"])
				)
			)
		), 
		'strlen'
	);
	//var_dump($_GET["search_keyword"]);
	//var_dump($search_keyword);
	//exit();
	$search_price_free = $_GET["search_price_free"];
	$search_training = $_GET["search_training"];
	$search_state = $_GET["search_state"];
	$search_start_date = $_GET["search_start_date"];
	$search_end_date = $_GET["search_end_date"];
	$search_start_contents_date = $_GET["search_start_contents_date"];
	$search_end_contents_date = $_GET["search_end_contents_date"];


	$page = 1;
	if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
		$page = $_GET["page"];
	}

	$pagemax = 5;
	if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
		$pagemax = $_GET["pagemax"];
	}

	$sort = 1;
	if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
		$sort = $_GET["sort"];
	}
}

$url_plam = "";
$url_plam.= "&search_type=".urlencode($search_type);
$url_plam.= "&search_category=".urlencode(   implode(  '|', ( is_array($search_category) ? $search_category : [$search_category] )  )   );
$url_plam.= "&search_keyword=".urlencode(   implode(  ' ', ( is_array($search_keyword) ? $search_keyword : [$search_keyword] )  )   );
$url_plam.= "&search_price_free=".urlencode($search_price_free);
if( is_array($search_training) ){
	foreach($search_training as $row){
		$url_plam.= "&search_training[]=".urlencode(   $row  );
	}
}
if( is_array($search_state) ){
	foreach($search_state as $row){
		$url_plam.= "&search_state[]=".urlencode(   $row  );
	}
}
$url_plam.= "&search_start_date=".urlencode($search_start_date);
$url_plam.= "&search_end_date=".urlencode($search_end_date);
$url_plam.= "&search_start_contents_date=".urlencode($search_start_contents_date);
$url_plam.= "&search_end_contents_date=".urlencode($search_end_contents_date);

//$url_plam.= "&pagemax=".urlencode($pagemax);
//$url_plam.= "&sort=".urlencode($sort);

if (isset($_GET['pagemax']) || isset($_GET['page']) || isset($_GET['sort'])){
	$disp_flg = true;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( count($search_category)==1 ){
	$pcatid = "";
	$loop = 0;
	while( $pcatid!="21" && $pcatid!="0" && $loop<10){
		for($i=0;$i<count($temp_category_list);$i++){
			if($temp_category_list[$i]["term_id"]==$search_category[0+$loop]){
				if($temp_category_list[$i]["parent"]!="21" && $temp_category_list[$i]["parent"]!="0"){
					$pcatid = trim($temp_category_list[$i]["parent"]);
					$search_category[] = $pcatid;
					$loop += 1;
				} else {
					$pcatid = trim($temp_category_list[$i]["parent"]);
					$loop += 10;
				}
			}
		}
	}
	$_SESSION["product_search_category"] = $search_category;
} 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//print("<!--[2timecheck:start".date("H:i:s")."]-->\n");
$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21' and wp_terms.term_id<>'300' ORDER BY wp_terms.slug ASC";
$arr_category = $objDbConnect->query_fetch_arr($sql);
for($i=0;$i<count($arr_category);$i++){
	$temp = array();
	$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$arr_category[$i]["term_id"]."' ORDER BY wp_terms.slug ASC";
	$temp = $objDbConnect->query_fetch_arr($sql);
	if(!empty($temp)){
		$arr_category[$i]["categorys"] = $temp;
		for($n=0;$n<count($temp);$n++){
			$temp2 = array();
			$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp[$n]["term_id"]."' ORDER BY wp_terms.slug ASC";
			$temp2 = $objDbConnect->query_fetch_arr($sql);
			if(!empty($temp2)){
				$arr_category[$i]["categorys"][$n]["categorys"] = $temp2;
				for($m=0;$m<count($temp2);$m++){
					$temp3 = array();
					$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp2[$m]["term_id"]."' ORDER BY wp_terms.slug ASC";
					$temp3 = $objDbConnect->query_fetch_arr($sql);
					if(!empty($temp3)){
						$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = $temp3;
					}
				}
			}
		}
		
	}
}
//print("<!--[2timecheck:end".date("H:i:s")."]-->\n");
//print("<!--[");
//var_dump($arr_category);
//print("]-->");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$select_cat = array();
$prev_select_cat = array();
if( 0<count($search_category) ){
	for($i=0;$i<count($search_category);$i++){
		$select_cat[] = $search_category[$i];
	}

	while( count($select_cat)!=count($prev_select_cat) ){
		$cat_where = implode(",",$select_cat);
		$sql = "SELECT term_id,parent FROM wp_term_taxonomy where taxonomy='category' and term_id<>'21' and term_id<>'0' and term_id<>'' and ( term_id in (".$cat_where.") or parent in(".$cat_where.") )";
		$arr_cat = $objDbConnect->query_fetch_arr($sql);
		$prev_select_cat = $select_cat;
		$select_cat = array();
		for($i=0;$i<count($arr_cat);$i++){
			$select_cat[] = $arr_cat[$i]["term_id"];
		}
	}
}
*/
//var_dump( $select_cat );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//-------------------------------------
$where = " WHERE tbl_product.del_flg = '0'";
$where.= " AND ( tbl_product_add.product_type_add IN (1,2) )";
$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";
$where.= " AND ( tbl_product_live_training.target LIKE '%|".$_SESSION['user']['bar_association_id']."|%' OR tbl_product_live_training.target IS NULL )";
$where.= " AND ( tbl_product_live_training.ethic_flg = '0' OR tbl_product_live_training.ethic_flg IS NULL OR (tbl_product_live_training.ethic_flg = '1' AND tbl_product_live_training.app_flg = '1') )";
//-------------------------------------
$where.= "\n\n";
//キーワード
$keyword_where = "";
$arr_search_point = array();
$arr_search_product_id = array();
for($i=0;$i<count($search_keyword);$i++){
	/*
	if($keyword_where !="" ){ 
		if( $search_type =="OR" ){
			$keyword_where.= " OR "; 
		} else {
			$keyword_where.= " AND "; 
		}
	}
	$keyword_where.= " ( ";
	$keyword_where.= "     tbl_product.product_name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
	$keyword_where.= "  OR tbl_product.product_code LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
	$keyword_where.= "  OR tbl_product.memo         LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
	$keyword_where.= "  OR tbl_product.teacher      LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
	$keyword_where.= "  OR tbl_product.teacher_student_id IN ( SELECT student.student_id FROM student WHERE student.student_name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ) ";
	$keyword_where.= "  OR tbl_product_elearning.search_word LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";

	$keyword_where.= "  OR  CONCAT(',',tbl_product.term_id,',') REGEXP (  ";
	$keyword_where.= "   SELECT CONCAT('(^.*,', ( ";
	$keyword_where.= "     SELECT GROUP_CONCAT(wp_terms.term_id SEPARATOR ',.*)|(^.*,') ";
	$keyword_where.= "     FROM wp_terms ";
	$keyword_where.= "     WHERE wp_terms.name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ";
	$keyword_where.= "   ), ',.*)') ";
	$keyword_where.= "  ) ";
	$keyword_where.= " ) ";
	*/

	$sql = "SELECT"."\n";
	$sql.= "  tbl_product.product_id "."\n";
	$sql.= " FROM"."\n";
	$sql.= "  tbl_product "."\n";
	$sql.= "  LEFT JOIN "."\n";
	$sql.= "  tbl_product_elearning "."\n";
	$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id "."\n";
	$sql.= " WHERE 1=1 "."\n";
	$sql.= " AND "."\n";
	$sql.= " ( "."\n";
	$sql.= "     tbl_product.product_name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";
	$sql.= "  OR tbl_product.product_code LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";
	$sql.= "  OR tbl_product.memo         LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";
	$sql.= "  OR tbl_product.teacher      LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";
	$sql.= "  OR tbl_product.teacher_student_id IN ( SELECT student.student_id FROM student WHERE student.student_name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci ) "."\n";
	$sql.= "  OR tbl_product_elearning.search_word LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";

	$sql.= "  OR  CONCAT(',',tbl_product.term_id,',') REGEXP (  "."\n";
	$sql.= "   SELECT CONCAT('(^.*,', ( "."\n";
	$sql.= "     SELECT GROUP_CONCAT(wp_terms.term_id SEPARATOR ',.*)|(^.*,') "."\n";
	$sql.= "     FROM wp_terms "."\n";
	$sql.= "     WHERE wp_terms.name LIKE '%".mysqli_real_escape_string($objDbConnect->connect,mb_convert_kana($search_keyword[$i],'KV'))."%' collate utf8_unicode_ci "."\n";
	$sql.= "   ), ',.*)') "."\n";
	$sql.= "  ) "."\n";
	$sql.= " ) "."\n";
	//print("\n<!--[ \n ".$sql." \n ]-->\n");
//print("<!--[3timecheck:start".date("H:i:s")."]-->\n");
	$res = $objDbConnect->query_fetch_arr($sql);
//print("<!--[3timecheck:end".date("H:i:s")."]-->\n");
	if ($res){

		$arr_temp_product_ids = array();
		foreach ($res as $row){
			$arr_search_product_id[$row["product_id"]] += 1;
			$arr_temp_product_ids[] = $row["product_id"];
		}
		if( !empty($arr_temp_product_ids) ){
			if($keyword_where !="" ){ 
				if( $search_type =="OR" ){
					$keyword_where.= " OR "; 
				} else {
					$keyword_where.= " AND "; 
				}
			}
			$keyword_where.= " ( ";
			$keyword_where.= "     tbl_product.product_id IN (".implode(",",$arr_temp_product_ids).") ";
			$keyword_where.= " ) ";
		}

	}
}
$select_search_point = "";
if( !empty($arr_search_product_id)){
	$select_search_point.= " ( ";
	$select_search_point.= "     CASE ";

	foreach ($arr_search_product_id as $key => $row2){
		$select_search_point.= "         WHEN tbl_product.product_id='".$key."' THEN ".$row2." ";
	}

	$select_search_point.= "         ELSE 0 ";
	$select_search_point.= "     END ";
	$select_search_point.= " ) AS search_point, ";

} else {
	$select_search_point.= " 0 AS search_point, ";
}
if($keyword_where !="" ){
	$where.= " and ( ";
	$where.= $keyword_where;
	$where.= " ) ";
} else {
	if( !empty($search_keyword) ){
		$where.= " and 0=1 ";
	}
}
//-------------------------------------
$where.= "\n\n";
//講座種別
//if( $search_type =="OR" ){
		$search_training_where = "";
		if (!empty($search_training)){
			foreach ($search_training as $val){
				if ($val == 1){
					if( $search_training_where != "" ){
						$search_training_where.= " OR ";
					}
					$search_training_where.= " ( tbl_product_add.product_type_add='1' ) ";
				} else if ($val == 2){
					if( $search_training_where != "" ){
						$search_training_where.= " OR ";
					}
					$search_training_where.= " ( tbl_product_add.product_type_add='2' AND tbl_product_live_training.sponsor = '|1|' AND tbl_product_live_training.ethic_flg = '0' ) ";
				} else if ($val == 3){
					if( $search_training_where != "" ){
						$search_training_where.= " OR ";
					}
					$search_training_where.= " ( tbl_product_add.product_type_add='2' AND tbl_product_live_training.sponsor <> '|1|' ) ";
				}
			}
		}
		if($search_training_where !="" ){
			$where.= " and ( ";
			$where.= $search_training_where;
			$where.= " ) ";
		}
//print("<!--search_training_where:[".$search_training_where."]-->\n");
//}
//-------------------------------------
$where.= "\n\n";
//eラーニング受講状況
// 受講状況
//if( $search_type =="OR" ){
		$search_state_where = "";
		$tmp_product_ids = array();
		if (!empty($search_state)){
			$arr_product_ids = array();
			$str_product_ids = '';
			
			foreach ($search_state as $val){
//print("<!--[search_state:".$val."]-->");
				$jukoutyu_product_ids = array();
				$kounyuzumi_product_ids = array();
				$tmp_product = array();
				$tmp_tmp_product = array();
				$str_product_id = '';
				$str_jukou_product_id = '';
				$str_kounyuzumi_product_id = '';
				
				// 受講中
				if ($val == 1){
					$jukoutyu_product_ids = _get_jukoutyu_product_ids($objDbConnect);
					if ($jukoutyu_product_ids){
						foreach ($jukoutyu_product_ids as $product){
							array_push($tmp_product_ids, $product['product_id']);
						}
					}
					//print("<!--[tmp_product_ids:\n");
					//var_dump($tmp_product_ids);
					//print("\n]-->");
				// 未購入未受講
				} else if ($val == 2){
					$jukoutyu_product_ids = _get_jukoutyu_product_ids($objDbConnect);
					if ($jukoutyu_product_ids){
						foreach ($jukoutyu_product_ids as $product){
							$tmp_product[] = $product['product_id'];
						}
					}
					//print("<!--[tmp_product:\n");
					//var_dump($tmp_product);
					//print("\n]-->");
					
					$kounyuzumi_product_ids = _get_kounyuzumi_product_ids($objDbConnect);
					if ($kounyuzumi_product_ids){
						foreach ($kounyuzumi_product_ids as $product){
							$tmp_product[] = $product;
						}
					}
					//print("<!--[tmp_product:\n");
					//var_dump($tmp_product);
					//print("\n]-->");
					
					if (!empty($tmp_product)){
						$tmp_tmp_product = array_unique($tmp_product);
						foreach ($tmp_tmp_product as $product_id){
							$str_product_id.= mysqli_real_escape_string($objDbConnect->connect,$product_id).',';
						}
						$str_product_id = rtrim($str_product_id, ',');
						
						if ($str_product_id != ''){
							$sql = "SELECT";
							$sql.= "  T1.product_id";
							$sql.= " FROM";
							$sql.= "  tbl_product AS T1";
							$sql.= "    INNER JOIN";
							$sql.= "  tbl_product_add AS T2";
							$sql.= "      ON T1.product_id = T2.product_id";
							$sql.= " WHERE";
							$sql.= "  T1.del_flg = 0";
							$sql.= "  AND T1.product_id NOT IN($str_product_id)";
							$sql.= "  AND T2.product_type_add = 1";
//print("<!--[4timecheck:start".date("H:i:s")."]-->\n");
							$res = $objDbConnect->query_fetch_arr($sql);
//print("<!--[4timecheck:end".date("H:i:s")."]-->\n");

							if ($res){
								foreach ($res as $product){
									array_push($tmp_product_ids, $product["product_id"]);
								}
							}
						}
					}
					//print("<!--[tmp_product_ids:\n");
					//var_dump($tmp_product_ids);
					//print("\n]-->");
					
				// 受講完了
				} else if ($val == 3){
					$kanryou_product_ids = _get_kanryou_product_ids($objDbConnect);
					if ( !empty($kanryou_product_ids) ){
						foreach ($kanryou_product_ids as $product){
							array_push($tmp_product_ids, $product);
						}
					}
					//print("<!--[tmp_product_ids:\n");
					//var_dump($tmp_product_ids);
					//print("\n]-->");
				}
			}
			if (!empty($tmp_product_ids)){
				$arr_product_ids = array_unique($tmp_product_ids);
				foreach ($arr_product_ids as $product_id){
					$str_product_ids.= mysqli_real_escape_string($objDbConnect->connect,$product_id).',';
				}
				$str_product_ids = rtrim($str_product_ids, ',');
				if ($str_product_ids != ''){
					$search_state_where.= " tbl_product.product_id IN ($str_product_ids) ";
				}
				
			} else {
			}
		}
		if($search_state_where !="" ){
			$where.= " and ( ";
			$where.= $search_state_where;
			$where.= " ) ";
		}
//}
//-------------------------------------
$where.= "\n\n";
//eラーニング掲載期間
// 公開期間
//if( $search_type =="OR" ){
		$search_opendate_where = "";
		if( $search_start_date != "" && $search_end_date != "" ){
			if( $search_opendate_where != "" ){
				$search_opendate_where.= " OR ";
			}
			$search_opendate_where.= " ( tbl_product_add.product_type_add = '1' ";
			$search_opendate_where.= " and ( ";
				$search_opendate_where.= " ('".mysqli_real_escape_string($objDbConnect->connect,$search_start_date)."' between  start_date and end_date OR start_date IS NULL) ";
				$search_opendate_where.= " OR ('".mysqli_real_escape_string($objDbConnect->connect,$search_end_date)."' between  start_date and end_date OR end_date IS NULL) ";
			$search_opendate_where.= " ) ";
			$search_opendate_where.= " ) ";
		} elseif( $search_start_date != "" ){
			if( $search_opendate_where != "" ){
				$search_opendate_where.= " OR ";
			}
			$search_opendate_where.= " ( tbl_product_add.product_type_add = '1' ";
			$search_opendate_where.= " and (start_date<='".mysqli_real_escape_string($objDbConnect->connect,$search_start_date)."' OR start_date IS NULL) ";
			$search_opendate_where.= " and (end_date>='".mysqli_real_escape_string($objDbConnect->connect,$search_start_date)."' OR end_date IS NULL) ";
			$search_opendate_where.= " ) ";
		} elseif( $search_end_date != "" ){
			if( $search_opendate_where != "" ){
				$search_opendate_where.= " OR ";
			}
			$search_opendate_where.= " ( tbl_product_add.product_type_add = '1' ";
			$search_opendate_where.= " and (start_date<='".mysqli_real_escape_string($objDbConnect->connect,$search_end_date)."' OR start_date IS NULL) ";
			$search_opendate_where.= " and (end_date>='".mysqli_real_escape_string($objDbConnect->connect,$search_end_date)."' OR end_date IS NULL) ";
			$search_opendate_where.= " ) ";
		}
		if($search_opendate_where !="" ){
			$where.= " and ( ";
			$where.= $search_opendate_where;
			$where.= " ) ";
		}
//}
//-------------------------------------
$where.= "\n\n";
//研修開催日
// 研修開催日
//if( $search_type =="OR" ){
	$search_contentsdate_where = "";
	if( $search_start_contents_date != "" && $search_end_contents_date != "" ){
		if( $search_contentsdate_where != "" ){
			$search_contentsdate_where.= " OR ";
		}
		$search_contentsdate_where.= " (tbl_product_add.product_type_add = '2' AND '".mysqli_real_escape_string($objDbConnect->connect,$search_start_contents_date)."' <= RPBA.dates AND '".mysqli_real_escape_string($objDbConnect->connect,$search_end_contents_date)."' >= RPBA.dates AND RPBA.dates IS NOT NULL) ";
	} elseif( $search_start_contents_date != "" ){
		if( $search_contentsdate_where != "" ){
			$search_contentsdate_where.= " OR ";
		}
		$search_contentsdate_where.= " (tbl_product_add.product_type_add = '2' AND '".mysqli_real_escape_string($objDbConnect->connect,$search_start_contents_date)."' <= RPBA.dates AND RPBA.dates IS NOT NULL) ";
	} elseif( $search_end_contents_date != "" ){
		if( $search_contentsdate_where != "" ){
			$search_contentsdate_where.= " OR ";
		}
		$search_contentsdate_where.= " (tbl_product_add.product_type_add = '2' AND '".mysqli_real_escape_string($objDbConnect->connect,$search_end_contents_date)."' >= RPBA.dates AND RPBA.dates IS NOT NULL) ";
	}
	if($search_contentsdate_where !="" ){
		$where.= " and ( ";
		$where.= $search_contentsdate_where;
		$where.= " ) ";
	}
//}
//-------------------------------------
$where.= "\n\n";
//カテゴリ
//if( $search_type =="OR" ){
		$cat_where = "";
		$temp_arr_cat = array();
		for($i=0;$i<count($search_category);$i++){
			//print_r("[".$search_category[$i]."]<br>");
			$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent FROM wp_term_taxonomy where taxonomy='category' and parent='".mysqli_real_escape_string($objDbConnect->connect,$search_category[$i])."'";
//print("<!--[5timecheck:start".date("H:i:s")."]-->\n");
			$temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[5timecheck:end".date("H:i:s")."]-->\n");
			//print_r("[");
			//var_dump($temp);
			//print_r("]<br>");
			if( empty($temp) ){
				//print_r("[add_category:".$search_category[$i]."]<br>");
				$temp_arr_cat[] = $search_category[$i];
			} else {
				$add_cat = 1;
				for($n=0;$n<count($temp);$n++){
					for($m=0;$m<count($search_category);$m++){
						//print_r("[".$temp[$n]["term_id"].":".$search_category[$m]."]<br>");
						if( $temp[$n]["term_id"]==$search_category[$m] ){
							$add_cat = 0;
						}
					}
				}
				if( $add_cat==1 ){
					$temp_arr_cat[] = $search_category[$i];
					//print_r("[add_category:".$search_category[$i]."]<br>");
				}
			}
		}
		for($i=0;$i<count($temp_arr_cat);$i++){
			if($cat_where !="" ){ $cat_where.= " OR "; }
			if( $temp_arr_cat[$i]=="519" ){ //カテゴリID変更時は、修正の必要あり
				//$cat_where.= " ( concat(',',tbl_product.term_id,',') LIKE '%,".$temp_arr_cat[$i].",%' AND tbl_product_live_training.ethic_flg=1 ) ";
				//$cat_where.= "  tbl_product_live_training.ethic_flg=1 ";
				$cat_where.= " concat(',',tbl_product.term_id,',') LIKE '%,".mysqli_real_escape_string($objDbConnect->connect,$temp_arr_cat[$i]).",%' ";
			} else {
				$cat_where.= " concat(',',tbl_product.term_id,',') LIKE '%,".mysqli_real_escape_string($objDbConnect->connect,$temp_arr_cat[$i]).",%' ";
			}
		}
		$arr_search_category_disp = array();
		for($i=0;$i<count($temp_arr_cat);$i++){
			$parent = $temp_arr_cat[$i];
			$loop = 0;
			while( $parent!="21" && $parent!="0" && $loop<10){
				$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and wp_term_taxonomy.term_id='".mysqli_real_escape_string($objDbConnect->connect,$parent)."' ORDER BY wp_terms.slug ASC";
				//var_dump($sql);
//print("<!--[6timecheck:start".date("H:i:s")."]-->\n");
				$temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[6timecheck:end".date("H:i:s")."]-->\n");
				if( !empty($temp) ) {
					$arr_search_category_disp[$i] = " > ".$temp[0]["name"].$arr_search_category_disp[$i];
					$parent = $temp[0]["parent"];
				}
				$loop +=1;
			}
			$arr_search_category_disp[$i] = trim($arr_search_category_disp[$i]," > ");
		}
		if($cat_where !="" ){
			$where.= " AND ( ";
			$where.= $cat_where;
			$where.= " ) ";
		}
//}
//-------------------------------------
$where.= "\n\n";
//研修料金
//if( $search_type =="OR" ){
	$search_price_where = "";
	if ($search_price_free=="1"){
	} elseif ($search_price_free=="2"){
		if( $search_price_where != "" ){
			$search_price_where.= " OR ";
		}
		$search_price_where.= " (tbl_product.price='0') ";
	} elseif ($search_price_free=="3"){
		if( $search_price_where != "" ){
			$search_price_where.= " OR ";
		}
		$search_price_where.= " (tbl_product.price>'0') ";
	} else {
	}
	if($search_price_where !="" ){
		$where.= " AND ( ";
		$where.= $search_price_where;
		$where.= " ) ";
	}
//}
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
// 主催
/*
if (!empty($search_sponsor)){
	$count = count($search_sponsor);
	// チェックが単数
	if ($count == 1){
		if ($search_sponsor[0] == 1){
			if( $where_or != "" ){
				$where_or.= " OR ";
			}
			$where_or.= " (tbl_product_live_training.sponsor LIKE '%|1|%') ";
		} else if ($search_sponsor[0] == 2){
			if( $where_or != "" ){
				$where_or.= " OR ";
			}
			$where_or.= " (tbl_product_live_training.sponsor NOT LIKE '%|1|%') ";
		}
		
	// チェックが複数
	} else {
	}
}
*/
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
//-------------------------------------
$where.= "\n\n";
if (!$st_login_check){
	$where.= " AND tbl_product.product_type='2'";
}
//-------------------------------------
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$sql = "SELECT COUNT(*) AS c FROM tbl_product AS tbl_product JOIN wp_term_taxonomy AS wp_term_taxonomy ON tbl_product.term_id = wp_term_taxonomy.term_id";

if( $disp_flg ){
	$sql = "SELECT";
	$sql.= "  COUNT(tbl_product.product_id) AS c";
	$sql.= " FROM";
	$sql.= "  tbl_product";
	$sql.= "    INNER JOIN";
	$sql.= "  wp_term_taxonomy";
	$sql.= "      ON tbl_product.term_id = wp_term_taxonomy.term_id";
	$sql.= "    INNER JOIN";
	$sql.= "  tbl_product_add";
	$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product_elearning";
	$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product_live_training";
	$sql.= "      ON tbl_product.product_id = tbl_product_live_training.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA";
	$sql.= "      ON tbl_product.product_id = RPBA.product_id";
//print("<!--[7timecheck:start".date("H:i:s")."]-->\n");
	$ret = $objDbConnect->query_fetch($sql.$where);
//print("<!--[7timecheck:end".date("H:i:s")."]-->\n");
	if ($ret){
		$all_count = $ret["c"];
	} else {
		$all_count = 0;
	}
	$objPager->setListMax($all_count);
	//$objPager->setPagerUrl("?pcid=".$pcid."&page=","#search_form");
	//$objPager->setPagerUrl("?search=paging".$url_plam."&page=","#search_form");
	$objPager->setPagerUrl("?search=paging".$url_plam."&pagemax=".$pagemax."&sort=".$sort."&page=","#main");
	$pager = $objPager->getPager();
	$offset = $objPager->getOffset();
	if ($sort == 1){
		$order = " ORDER BY search_point DESC, tbl_product.regist_date DESC";
	} else if ($sort == 2){
		$order = " ORDER BY search_point DESC, tbl_product.regist_date ASC";
	} else if ($sort == 3){
		$order = " ORDER BY search_point DESC, CASE WHEN tbl_product.end_date IS NULL THEN 1 ELSE 0 END, tbl_product.end_date ASC";
	} else {
		$order = " ORDER BY search_point DESC, tbl_product.regist_date DESC";
		$sort = 1;
	}

	//$sql = "SELECT * FROM tbl_product AS tbl_product JOIN wp_term_taxonomy AS wp_term_taxonomy ON tbl_product.term_id = wp_term_taxonomy.term_id";

	$sql = "SELECT "."\n";

	//$sql.= "  tbl_product.*,"."\n";
	$sql.= "  tbl_product.product_id, "."\n";
	$sql.= "  tbl_product.product_name, "."\n";
	$sql.= "  tbl_product.product_code, "."\n";
	$sql.= "  tbl_product.term_id, "."\n";
	$sql.= "  tbl_product.del_flg, "."\n";
	$sql.= "  tbl_product.start_date, "."\n";
	$sql.= "  tbl_product.end_date, "."\n";
	$sql.= "  tbl_product.memo, "."\n";
	$sql.= "  tbl_product.teacher, "."\n";
	$sql.= "  tbl_product.teacher_student_id, "."\n";
	$sql.= "  tbl_product.price, "."\n";
	$sql.= "  tbl_product.product_type, "."\n";
	$sql.= "  tbl_product.regist_date, "."\n";
	$sql.= "  tbl_product.contents_contents1, "."\n";
	$sql.= "  tbl_product.contents_contents2, "."\n";
	$sql.= "  tbl_product.contents_contents3, "."\n";
	$sql.= "  tbl_product.contents_contents4, "."\n";
	$sql.= "  tbl_product.contents_contents5, "."\n";
	$sql.= "  tbl_product.contents_contents6, "."\n";
	$sql.= "  tbl_product.contents_contents7, "."\n";
	$sql.= "  tbl_product.contents_contents8, "."\n";
	$sql.= "  tbl_product.contents_contents9, "."\n";
	$sql.= "  tbl_product.contents_contents10, "."\n";
	$sql.= "  tbl_product.contents_contents11, "."\n";
	$sql.= "  tbl_product.contents_contents12, "."\n";
	$sql.= "  tbl_product.contents_contents13, "."\n";
	$sql.= "  tbl_product.contents_contents14, "."\n";
	$sql.= "  tbl_product.contents_contents15, "."\n";
	$sql.= "  tbl_product.contents_contents16, "."\n";
	$sql.= "  tbl_product.contents_contents17, "."\n";
	$sql.= "  tbl_product.contents_contents18, "."\n";
	$sql.= "  tbl_product.contents_contents19, "."\n";
	$sql.= "  tbl_product.contents_contents20, "."\n";
	$sql.= "  tbl_product.contents_contents21, "."\n";
	$sql.= "  tbl_product.contents_contents22, "."\n";
	$sql.= "  tbl_product.contents_contents23, "."\n";
	$sql.= "  tbl_product.contents_contents24, "."\n";
	$sql.= "  tbl_product.contents_contents25, "."\n";

	$sql.= "  DATE_FORMAT(tbl_product.regist_date,'%Y%m%d') as regist_date_ymd,"."\n";
	$sql.= "  tbl_product_add.product_type_add,"."\n";
	$sql.= "  tbl_product_elearning.product_flg,"."\n";
	$sql.= "  tbl_product_live_training.memo1,"."\n";
	$sql.= "  tbl_product_live_training.memo2,"."\n";
	$sql.= $select_search_point."\n";
	$sql.= "  tbl_product_live_training.sponsor,"."\n";
	$sql.= "  RPBA.dates"."\n";
	$sql.= " FROM"."\n";
	$sql.= "  tbl_product"."\n";
	$sql.= "    INNER JOIN";
	$sql.= "  wp_term_taxonomy";
	$sql.= "      ON tbl_product.term_id = wp_term_taxonomy.term_id";
	$sql.= "    INNER JOIN";
	$sql.= "  tbl_product_add";
	$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product_elearning";
	$sql.= "      ON tbl_product.product_id = tbl_product_elearning.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product_live_training";
	$sql.= "      ON tbl_product.product_id = tbl_product_live_training.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA";
	$sql.= "      ON tbl_product.product_id = RPBA.product_id";

	//echo $sql.$where.$order.$offset;
//print("<!--[8timecheck:start".date("H:i:s")."]-->\n");
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//print("<!--[8timecheck:end".date("H:i:s")."]-->\n");
	//print("\n<!--[ \n ".$sql.$where.$order.$offset." \n ]-->\n");
	//print("\n<!--[ \n ");
	//var_dump( $ret );
	//print(" \n ]-->\n");
}

// 主催総数取得
if ($mtb_bar_association){
	$sponsor_count = count($mtb_bar_association);
}

if( !empty($ret) ){
	//print("<!--[ret count:".count($ret)."]-->\n");
}

$arr_list = array();
if( $disp_flg ){




	$sql = "SELECT product_id FROM tbl_favorite WHERE member_id='".mysqli_real_escape_string($objDbConnect->connect,$_SESSION['user']['id'])."' AND del_flg='0'";
//print("<!--[9timecheck:start".date("H:i:s")."]-->\n");
	$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[9timecheck:end".date("H:i:s")."]-->\n");
	$favorite_list = [];
	foreach ($arr_temp as $favorite_row){
		$favorite_list[] = $favorite_row["product_id"];
	}

	$sql = "SELECT term_id, name FROM wp_terms";
//print("<!--[10timecheck:start".date("H:i:s")."]-->\n");
	$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[10timecheck:end".date("H:i:s")."]-->\n");
	$category_list = [];
	foreach ($arr_temp as $terms_row){
		$category_list[] = $terms_row;
	}

	$sql= "SELECT product_id FROM ranking  ";
//print("<!--[11timecheck:start".date("H:i:s")."]-->\n");
	$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[11timecheck:end".date("H:i:s")."]-->\n");
	$ranking_list = [];
	foreach ($arr_temp as $ranking_row){
		$ranking_list[] = $ranking_row["product_id"];
	}

	$sql= "SELECT term_id  FROM wp_term_taxonomy WHERE (parent=572 OR term_id=572) and taxonomy='category'";
//print("<!--[12timecheck:start".date("H:i:s")."]-->\n");
	$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[12timecheck:end".date("H:i:s")."]-->\n");
	$syokyulist = [];
	foreach ($arr_temp as $syokyu_row){
		$syokyu_list[] = $syokyu_row["term_id"];
	}

	$sql= "SELECT term_id  FROM wp_term_taxonomy WHERE (parent=523 OR term_id=523) and taxonomy='category'";
//print("<!--[13timecheck:start".date("H:i:s")."]-->\n");
	$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[13timecheck:end".date("H:i:s")."]-->\n");
	$etcmovie_list = [];
	foreach ($arr_temp as $etcmovie_row){
		$etcmovie_list[] = $etcmovie_row["term_id"];
	}

//print("<!--[looptimecheck:start".date("H:i:s")."]-->\n");
	foreach ($ret as $key => $val){
//print("<!--[rowtimecheck:start".date("H:i:s")."]-->\n");
		//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
		$arr_list[$key] = $val;
		//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
		// -----
		// 共通
		// -----
		// 掲載期間
		$arr_list[$key]['disp_start_date'] = ($val['start_date']!=null) ? date('Y年m月d日', strtotime($val['start_date'])) : "";
		$arr_list[$key]['disp_end_date'] = ($val['end_date']!=null) ? date('Y年m月d日', strtotime($val['end_date'])) : "";
		
		// 消費税込み価格の設定
		$arr_list[$key]['price_intax'] = $arr_list[$key]['price'] + tax_cal_yen($arr_list[$key]['price']);
		
		// お気に入り
		$arr_list[$key]['favorite_flg'] = false;      // お気に入りボタン表示フラグ
		$arr_list[$key]['favorite_icon_flg'] = false; // お気に入りアイコン表示フラグ
		if (in_array($val['product_id'], $favorite_list) ){
			$arr_list[$key]['favorite_icon_flg'] = true;
		} else {
			$arr_list[$key]['favorite_flg'] = true;
		}

		//Category
		$arr_list[$key]['css_icon_cat'] = [];
		if( trim($arr_list[$key]['term_id'])!="" ){
			$arr_term_id = explode(",", $arr_list[$key]['term_id']);
			foreach($arr_term_id as $term_id){
				foreach($category_list as $terms_row){
					if( $term_id==$terms_row["term_id"]){
						$arr_list[$key]['css_icon_cat'][] = $terms_row;
					}
				}
			}
		}

		//New
		$arr_list[$key]['css_icon_new'] = 0;
		if( intval($arr_list[$key]['regist_date_ymd'])+30>=intval(date("Ymd")) ){
			$arr_list[$key]['css_icon_new'] = 1;
		}

		//人気
		$arr_list[$key]['css_icon_ninki'] = 0;
		if (in_array($val['product_id'], $ranking_list) ){
			$arr_list[$key]['css_icon_ninki'] = 1;
		}

		//初級
		$arr_list[$key]['css_icon_syokyu'] = 0;
		if (in_array($val['term_id'], $syokyu_list) ){
			$arr_list[$key]['css_icon_syokyu'] = 1;
		}

		//その他動画
		$arr_list[$key]['css_icon_etcmovie'] = 0;
		if (in_array($val['term_id'], $etcmovie_list) ){
			$arr_list[$key]['css_icon_etcmovie'] = 1;
		}
		//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL


		// 商品フラグ
		$icon = '';
		$arr_icon = array();
		$arr_icon = explode('|', trim($val['product_flg'], '|'));
		$osusume_flg = 0;
		foreach ($arr_icon as $icon){
			if( $mtb_product_flg[$icon]['icon']=="status002.png" && $osusume_flg == 0 ){
				$osusume_flg = 1;
				$arr_list[$key]['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
				$arr_list[$key]['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
			} elseif( $mtb_product_flg[$icon]['icon']=="status002.png" && $osusume_flg == 1 ){
			} else {
				$arr_list[$key]['icon_img'][$icon]['src'] = $mtb_product_flg[$icon]['icon'];
				$arr_list[$key]['icon_img'][$icon]['alt'] = $mtb_product_flg[$icon]['name'];
			}
		}

		// ---------------
		// e-ラーニング用
		// ---------------
		if ($val['product_type_add'] == 1){
			$all_play_time = '00時間00分00秒';
			$video_ids = [];
			for($i=1; $i<=MAX_CONTENTS; $i++){
				if( intval($val["contents_contents$i"])>0 ){
					$video_ids[] = intval($val["contents_contents$i"]);
				}
			}
			if( count($video_ids)>0 ){
				$sql = "SELECT SEC_TO_TIME(sum(TIME_TO_SEC(alfstream_duration))) as alfstream_duration FROM video_alfstream_status WHERE video_id IN (".implode(",", $video_ids).")";
//print("<!--[14timecheck:start".date("H:i:s")."]-->\n");
				$arr_temp = $objDbConnect->query_fetch_arr($sql);
//print("<!--[14timecheck:start".date("H:i:s")."]-->\n");
				foreach ($arr_temp as $row_temp){
					//$all_play_time = time_format_product_list($row_temp["alfstream_duration"]);
					$all_play_time = time_format_product_list($row_temp["alfstream_duration"]);
				}
			}
			$arr_list[$key]['all_play_time'] = $all_play_time;
		// -----------
		// 会場研修用
		// -----------
		} elseif ($val['product_type_add'] == 2){
			// 研修開催日
			$arr_list[$key]['disp_dates'] = date('Y年m月d日', strtotime($val['dates']));
			
			// 主催
			$disp_sponsor = '';
			if ($val['sponsor'] != ''){
				$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
				if (count($arr_sponsor) == $sponsor_count){
					$disp_sponsor = 'すべての弁護士会';
				} else {
					foreach ($arr_sponsor as $sponsor){
						$disp_sponsor.= htmlspecialchars($mtb_bar_association[$sponsor], ENT_QUOTES, 'UTF-8').'<br />';
					}
					$disp_sponsor = rtrim($disp_sponsor, '<br />');
				}
			}
			$arr_list[$key]['disp_sponsor'] = $disp_sponsor;
			
		} else {
		}
//print("<!--[rowtimecheck:endt".date("H:i:s")."]-->\n");
	}
//print("<!--[looptimecheck:end".date("H:i:s")."]-->\n");


}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$template->assign('arr_category', $arr_category);
$template->assign('search_category', $search_category);
$template->assign('arr_search_category_disp', $arr_search_category_disp);
$template->assign('arr_keyword', $search_keyword );
$template->assign('search_type', $search_type );
$template->assign('search_keyword', implode(' ', $search_keyword) );
$template->assign('search_price_free', $search_price_free );
$template->assign('search_training', $search_training);
$template->assign('search_sponsor', $search_sponsor);
$template->assign('search_state', $search_state);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('search_start_contents_date', $search_start_contents_date);
$template->assign('search_end_contents_date', $search_end_contents_date);

$template->assign('url_plam', $url_plam);

//var_dump($ret);
$template->assign('arr_list', $arr_list);
$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('THUMBNAIL_PATH', THUMBNAIL_PATH);
$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('mtb_product_flg', $mtb_product_flg);
$template->assign('sort_select', get_sort_selectbox());
$template->assign('sort', $sort);
//$template->assign('msg_flg', $msg_flg);
$template->assign('disp_flg', $disp_flg);

//$template->assign('pankuzu', get_product_pankuzu($pcid));

$template->assign('csrf_token', csrf_token_get());
$template->layout('search/index.tpl');
$objDbConnect->close();
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

/**
 * 受講中の商品IDを取得する
 */
function _get_jukoutyu_product_ids($objDbConnect){

	$student_id = $_SESSION['user']['id'];
	$arr_list = [];
	if( intval($student_id)>0 ){
		$sql = "
		SELECT 
		  student_product_history.product_id 
		 ,tbl_product.product_name 
		 ,tbl_product.start_date 
		 ,tbl_product.end_date 
		 ,student_product_history.viewed_time
		 ,student_product_history.total_time
		 ,ROUND( (TIME_TO_SEC(student_product_history.viewed_time) / TIME_TO_SEC(student_product_history.total_time))*100 ) AS max_percent 
		 ,TIME_FORMAT( TIMEDIFF(student_product_history.total_time,student_product_history.viewed_time),'%H:%i:%s') AS all_remaining 
		 ,student_product_history.last_activity AS reading_date 
		FROM 
		 student_product_history 
		 LEFT JOIN tbl_product ON tbl_product.product_id=student_product_history.product_id 
		WHERE 
		 1=1 
		 AND student_product_history.student_id='$student_id' 
		 AND student_product_history.total_time<>student_product_history.viewed_time 
		 AND ROUND( (TIME_TO_SEC(student_product_history.viewed_time) / TIME_TO_SEC(student_product_history.total_time))*100 )<100
		 AND (tbl_product.start_date<='".date("Y-m-d H:i:s")."' OR tbl_product.start_date IS null) 
		 AND (tbl_product.end_date >='".date("Y-m-d H:i:s")."' OR tbl_product.end_date IS null ) 
		";
//print("<!--[14timecheck:start".date("H:i:s")."]-->\n");
		$arr_list = $objDbConnect->query_fetch_arr($sql);
//print("<!--[14timecheck:end".date("H:i:s")."]-->\n");
		return $arr_list;
	}
	return [];
	/*



	// video_idを取得する
	$sql = "SELECT";
	$sql.= "   video_id";
	$sql.= " FROM";
	$sql.= "   report_user_video_viewed";
	$sql.= " WHERE";
	$sql.= "   student_id = '".$_SESSION['user']['id']."'";
	$sql.= "   AND percent >= 1";
//print("<!--[14timecheck:start".date("H:i:s")."]-->\n");
	$arr_view_video_ids = $objDbConnect->query_fetch_arr($sql);
//print("<!--[14timecheck:end".date("H:i:s")."]-->\n");
	if ($arr_view_video_ids){
		// SQLのIN条件作成
		$in_view_video_ids = '';
		foreach ($arr_view_video_ids as $val){
			$in_view_video_ids.= $val['video_id'] . ',';
		}
		if ($in_view_video_ids != ''){
			$in_view_video_ids = rtrim($in_view_video_ids, ',');
			
			// 受講中のビデオが設定されている商品をベースに全件取得
			$sql = "SELECT";
			for($i=1; $i<=MAX_CONTENTS; $i++){
				$sql.= " T1.contents_contents$i,";
			}
			$sql.= "   T1.product_id";
			$sql.= " FROM";
			$sql.= "   tbl_product AS T1";
			$sql.= "     INNER JOIN";
			$sql.= "   tbl_product_add AS T2";
			$sql.= "       ON T1.product_id = T2.product_id";
			$sql.= " WHERE";
			$sql.= "   T1.del_flg = '0'";
			$sql.= "   AND T2.product_type_add = '1'";
			$sql.= "   AND ( ";
			for($i=1; $i<=MAX_CONTENTS; $i++){
				$sql.= " T1.contents_contents$i IN($in_view_video_ids) OR";
			}
			$sql = rtrim($sql, 'OR');
			$sql.= " )";
//print("<!--[15timecheck:start".date("H:i:s")."]-->\n");
			$arr_product_ids = $objDbConnect->query_fetch_arr($sql);
//print("<!--[15timecheck:end".date("H:i:s")."]-->\n");
			if ($arr_product_ids){
				foreach ($arr_product_ids as $key => $val){
					$arr_list[$key] = $val;
					
					$all_complete_flg = true;
					for($i=1; $i<=MAX_CONTENTS; $i++){
						if ($val["contents_contents$i"] != ''){
							// 全ての講座を見たか
							if ($all_complete_flg){
								$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$val["contents_contents$i"]."' AND complete_flag = '1'";
//print("<!--[16timecheck:start".date("H:i:s")."]-->\n");
								$res_count = $objDbConnect->query_fetch($sql);
//print("<!--[16timecheck:end".date("H:i:s")."]-->\n");
								if ($res_count['c']==0){
									$all_complete_flg = false;
								}
							}
						} else {
							break;
						}
					}
					
					// 完了済みのデータの場合削除し、ループ続行
					if ($all_complete_flg){
						unset($arr_list[$key]);
						continue;
					}
				}
				
				return $arr_list;
			}
		}
	}
	
	return false;
	*/
}

/**
 * 購入済み商品IDを取得する
 */
function _get_kounyuzumi_product_ids($objDbConnect){
	$arr_product_ids = array();
	$tmp_arr_product_ids = array();
	
	$sql = "SELECT";
	$sql.= "  T1.product_id";
	$sql.= " FROM";
	$sql.= "  tbl_order_detail AS T1";
	$sql.= "    INNER JOIN";
	$sql.= "  tbl_product_add AS T2";
	$sql.= "      ON T1.product_id = T2.product_id";
	$sql.= " WHERE";
	$sql.= "  T1.member_id = '".$_SESSION['user']['id']."'";
	$sql.= "  AND T1.payment_status = '2'";
	//$sql.= "  AND T1.video_complete_flg = '1'";
	$sql.= "  AND T2.product_type_add = '1'";
	
//print("<!--[17timecheck:start".date("H:i:s")."]-->\n");
	$res = $objDbConnect->query_fetch_arr($sql);
//print("<!--[17timecheck:end".date("H:i:s")."]-->\n");
	if ($res){
		foreach ($res as $product){
			array_push($tmp_arr_product_ids, $product["product_id"]);
		}
		$arr_product_ids = array_unique($tmp_arr_product_ids);
		
		return $arr_product_ids;
	}
	return false;
}
function _get_kanryou_product_ids($objDbConnect){
	$arr_product_ids = array();
	$arr_product_ids1 = array();
	$arr_product_ids2 = array();

	$sql = "";
	$sql.= "SELECT ";
	$sql.= " product_id ";
	$sql.= "FROM ";
	$sql.= " exam_answer ";
	$sql.= "WHERE ";
	$sql.= " student_id='".$_SESSION['user']['id']."'";
	$sql.= " AND passing_flg=1 ";
//print("<!--[18timecheck:start".date("H:i:s")."]-->\n");
	$res = $objDbConnect->query_fetch_arr($sql);
//print("<!--[18timecheck:end".date("H:i:s")."]-->\n");
	if ($res){
		foreach ($res as $product){
			array_push($arr_product_ids1, $product["product_id"]);
		}
	}

	$sql = "";
	$sql.= "SELECT ";
	$sql.= " product_id ";
	$sql.= "FROM ";
	$sql.= " tbl_order_detail ";
	$sql.= "WHERE ";
	$sql.= " member_id='".$_SESSION['user']['id']."'";
	$sql.= " AND video_complete_flg=1 ";
	$sql.= " AND product_id NOT IN ( SELECT product_id FROM rel_product_contents WHERE exam_id_test>0 GROUP BY product_id ) ";
//print("<!--[19timecheck:start".date("H:i:s")."]-->\n");
	$res = $objDbConnect->query_fetch_arr($sql);
//print("<!--[19timecheck:end".date("H:i:s")."]-->\n");
	if ($res){
		foreach ($res as $product){
			array_push($arr_product_ids2, $product["product_id"]);
		}
	}
	$arr_product_ids = array_merge( $arr_product_ids1, $arr_product_ids2 );
	return $arr_product_ids;
}
?>