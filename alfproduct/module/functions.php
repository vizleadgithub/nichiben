<?php
//error_reporting(E_ALL & ~E_WARNING);
error_reporting(1);
/*
if($_SERVER['HTTPS']=="on"){
	if( 
		$_SERVER['SCRIPT_NAME']=='/index.php'
		 || strstr($_SERVER['SCRIPT_NAME'],'/search/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/product/')
	 ){
		header("Location: "."http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		exit();
	}
} else {
	if( 
		strstr($_SERVER['SCRIPT_NAME'],'/member/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/payment/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/settlement/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/login/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/reminder/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/inquiry/')
		 || strstr($_SERVER['SCRIPT_NAME'],'/mypage/')
	 ){
		header("Location: "."https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		exit();
	}
}
*/

/*
if(strstr($_SERVER['SCRIPT_NAME'],'/ethic_treaning/')
	|| ($_SERVER['SCRIPT_NAME']=='/product/detail.php' && $_GET['pid']=='19447')
){
	header("Location: /maintenance/ethic_treaning.php");
	exit();
}
*/

/**
 * ログインチェック(一般・生徒側)
 * @return bool true:ログイン済 false:未ログイン
 */
function st_login_check(){
	date_default_timezone_set('Asia/Tokyo');

	// フロントログインセッションチェック
	if(isset($_SESSION['user'])){
		// ユーザー情報を取得し、セッションを更新
		$objDbConnect = new DbConnect();
		$sql = "SELECT";
		$sql.= "  student_id,";
		$sql.= "  student_name,";
		$sql.= "  bar_association_id,";
		$sql.= "  exp_date_passport,";
		$sql.= "  DATE_SUB(exp_date_passport,INTERVAL 1 MONTH) AS prev_exp_date_passport,";
		$sql.= "  regist_date,";
		$sql.= "  presence_passport,";
		$sql.= "  sub_auth_ethic_training,";
		$sql.= "  lawyer_number";
		$sql.= " FROM";
		$sql.= "  student";
		$sql.= " WHERE";
		$sql.= "  status='0'";
		$sql.= "  AND student_id='".$_SESSION['user']['id']."'";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret){
			$_SESSION['user']['id'] = $ret['student_id'];
			$_SESSION['user']['name'] = $ret['student_name'];
			$_SESSION['user']['bar_association_id'] = $ret['bar_association_id'];
			$_SESSION['user']['exp_date_passport'] = $ret['exp_date_passport'];
			$_SESSION['user']['presence_passport'] = $ret['presence_passport'];
			$_SESSION['user']['sub_auth_ethic_training'] = $ret['sub_auth_ethic_training'];
			$_SESSION['user']['lawyer_number'] = $ret['lawyer_number'];
			// 何年目の弁護士か
			$now_date = strtotime(date('Y-m-d'));
			$regist_date = strtotime($ret['regist_date']);
			$deff_date = $now_date - $regist_date;
			$bar_year = floor($deff_date / (60 * 60 * 24 * 365));
			$_SESSION['user']['year'] = (int)$bar_year + 1;
			// パスポート有効期限アラート
			$prev_exp_date_passport = strtotime($ret['prev_exp_date_passport']);
			if ($now_date >= $prev_exp_date_passport){
				$_SESSION['user']['passport_alert'] = true;
			} else {
				$_SESSION['user']['passport_alert'] = false;
			}
		}
		
		return true;
	}
	
	return false;
}

/**
 * 戻り先URL取得
 * @return $back_url
 */
function get_back_url(){
	if ($_SERVER['HTTP_REFERER'] != ''){
		$back_url = $_SERVER['HTTP_REFERER'];
	} else {
		$back_url = '/';
	}
	
	return $back_url;
}

/**
 * マスタデータ取得
 * @param  $mtb_name マスタテーブル名
 * @return $mtb_data マスタデータ配列
 */
function get_mtb_data($mtb_name){
	$objDbConnect = new DbConnect();
	
	$mtb_data = array();
	
	$sql = "SELECT id, name FROM $mtb_name";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		//while (($row = $objDbConnect->fetch($ret))){
		foreach ($ret as $row){
			$mtb_data[$row['id']] = $row;
		}
	}
	
	return $mtb_data;
}

/**
 * 当サイトを知ったきっかけ配列取得
 * @return 
 */
function get_mtb_media(){
	$objDbConnect = new DbConnect();
	
	$mtb_media = array();
	
	$sql = "SELECT id,name FROM mtb_media_parent ORDER BY `rank` ASC";
	$mtb_media_parent = $objDbConnect->query_fetch_arr($sql);
	if ($mtb_media_parent){
		$i = 0;
		foreach ($mtb_media_parent as $val){
			$mtb_media[$i]['parent_id'] = $val['id'];
			$mtb_media[$i]['parent_name'] = $val['name'];
			$sql = "SELECT id,name,parent FROM mtb_media WHERE parent='".$val['id']."' ORDER BY `rank` ASC";
			$mtb_media_child = array();
			$mtb_media_child = $objDbConnect->query_fetch_arr($sql);
			if ($mtb_media_child){
				$mtb_media[$i]['media_child'] = $mtb_media_child;
			}
			$i++;
		}
	}
	
	return $mtb_media;
}

/**
 * 商品フラグ配列取得
 * @return 
 */
function get_mtb_product_flg(){
	$objDbConnect = new DbConnect();
	
	$mtb_product_flg = array();
	
	$sql = "SELECT id, name FROM mtb_product_flg ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_product_flg[$row['id']] = $row['name'];
		}
	}
	
	return $mtb_product_flg;
}

/**
 * アイコン名配列取得
 * @return 
 */
function get_mtb_product_flg_icon(){
	$objDbConnect = new DbConnect();
	
	$mtb_product_flg = array();
	
	$sql = "SELECT id, name, icon FROM mtb_product_flg";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_product_flg[$row['id']]['name'] = $row['name'];
			$mtb_product_flg[$row['id']]['icon'] = $row['icon'];
		}
	}
	
	return $mtb_product_flg;
}

/**
 * 研修種別配列取得
 * @return 
 */
function get_mtb_live_training_type(){
	$objDbConnect = new DbConnect();
	
	$mtb_live_training_type = array();
	
	$sql = "SELECT id, name FROM mtb_live_training_type ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_live_training_type[$row['id']] = $row['name'];
		}
	}
	
	return $mtb_live_training_type;
}

/**
 * パスポート対象配列取得
 * @return 
 */
function get_mtb_passport_target(){
	$objDbConnect = new DbConnect();
	
	$mtb_passport_target = array();
	
	$sql = "SELECT * FROM mtb_passport_target ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_passport_target[] = $row;
		}
	}
	
	return $mtb_passport_target;
}

/**
 * パスポート対象配列取得(チェックボックス)
 * @return 
 */
function get_mtb_passport_target_checkbox(){
	$objDbConnect = new DbConnect();
	
	$mtb_passport_target = array();
	
	$sql = "SELECT passport_target_id, passport_target_name FROM mtb_passport_target ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_passport_target[$row['passport_target_id']] = $row['passport_target_name'];
		}
	}
	
	return $mtb_passport_target;
}

/**
 * 弁護士会マスタ配列取得
 * @return 
 */
function get_mtb_bar_association(){
	$objDbConnect = new DbConnect();
	
	$mtb_bar_association = array();
	
	$sql = "SELECT id, name FROM mtb_bar_association ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		foreach ($ret as $index=>$row) {
			$mtb_bar_association[$row['id']] = $row['name'];
		}
	}
	
	return $mtb_bar_association;
}

/**
 * 弁護士会支部マスタ配列取得
 * @return 
 */
function get_mtb_bar_association_branch(){
	$objDbConnect = new DbConnect();
	
	$mtb_bar_association_branch = array();
	
	$sql = "SELECT * FROM mtb_bar_association_branch";
	$ret = $objDbConnect->query_fetch_arr($sql);
	
	if($ret){
		$mtb_bar_association_branch = $ret;
	}
	
	return $mtb_bar_association_branch;
}

/**
 * 実施弁護士会用の配列を取得する
 * @return 
 */
function get_bar_association(){
	$objDbConnect = new DbConnect();
	
	$arr_bar_association = array();
	
	$sql = "SELECT T1.*, T2.* FROM mtb_bar_association_branch AS T1 LEFT JOIN mtb_bar_association AS T2 ON T1.bar_association_id = T2.id ORDER BY T2.`rank` ASC, T1.`rank` ASC";
	$res = $objDbConnect->query_fetch_arr($sql);
	if ($res){
		$count = 0;
		$prev_id = $res[0][0]['id'];
		foreach ($res as $val){
			if ($val['id'] != $prev_id){
				$count = 0;
			}
			$arr_bar_association[$val['id']]['name'] = $val['name'];
			$arr_bar_association[$val['id']]['branch_info'][$count]['id']   = $val['bar_association_branch_id'];
			$arr_bar_association[$val['id']]['branch_info'][$count]['name'] = $val['bar_association_branch_name'];
			$prev_id = $val['id'];
			$count++;
		}
	}
	
	return $arr_bar_association;
}

/**
 * 弁護士会支部情報の取得(商品ID)
 * @return 
 */
function get_rel_product_bar_association_branch($product_id=''){
	$objDbConnect = new DbConnect();
	
	$bar_association_branch = array();
	
	$sql = "SELECT * FROM rel_product_bar_association_branch";
	
	$where = "";
	if ($product_id != ''){
		$where.= " WHERE product_id = $product_id";
	}
	
	$ret = $objDbConnect->query_fetch_arr($sql.$where);
	
	if($ret){
		$bar_association_branch = $ret;
	}
	
	return $bar_association_branch;
}

/**
 * 倫理問題グループの取得
 * @return 
 */
function get_ethic_group(){
	$objDbConnect = new DbConnect();
	
	$ethic_group = array();
	
	$sql = "SELECT ethic_group_id, question_group FROM tbl_ethic_group ORDER BY `rank` ASC";
	$ret = $objDbConnect->query_fetch_arr($sql);
	if($ret){
		foreach($ret as $row){
			$ethic_group[$row['ethic_group_id']] = $row['question_group'];
		}
	}
	
	return $ethic_group;
}

/**
 * 弁護士年数セレクトボックス
 */
function get_bar_association_year_select(){
	$bar_association_year = array('' => '選択してください');
	
	for ($i=1; $i<=50; $i++){
		$bar_association_year[$i] = $i;
	}
	
	return $bar_association_year;
}

/**
 * 商品カテゴリを取得する
 * @return $productcategory_list 商品カテゴリ
 *         array([0] => ['big']
 *                      ['small'] => [0]);
 */
function get_product_category(){
	$objDbConnect = new DbConnect();
	
	$productcategory_list = array();
	
	// ベースになるSQL
	$sql = "SELECT";
	$sql.= "  T1.term_id,";
	$sql.= "  T1.name,";
	$sql.= "  T2.parent,";
	$sql.= "  T1.term_group";
	$sql.= " FROM";
	$sql.= "  wp_terms AS T1";
	$sql.= "   JOIN";
	$sql.= "  wp_term_taxonomy AS T2";
	$sql.= "   ON T1.term_id = T2.term_id";
	
	$where = "";
	
	$order = " ORDER BY T1.term_group ASC, T1.slug ASC";
	
	// 大カテゴリを取得
	$ret = array();
	$where = " WHERE T2.parent = '21'";
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order);
	
	if (!empty($ret)){
		// 商品カテゴリ配列の作成
		$count_ret = count($ret);
		for ($i=0; $i<$count_ret; $i++){
			$productcategory_list[$i]['big'] = $ret[$i];  // 大カテゴリ
			
			// 小カテゴリ一覧を取得
			$ret2 = array();
			$where = " WHERE T2.parent = '".$ret[$i]['term_id']."'";
			$ret2 = $objDbConnect->query_fetch_arr($sql.$where.$order);
			if (!empty($ret2)){
				$count_ret2 = count($ret2);
				for ($j=0; $j<$count_ret2; $j++){
					// 小カテゴリ詳細を取得
					$ret3= array();
					$where = " WHERE T1.term_id = '".$ret2[$j]['term_id']."'";
					$ret3 = $objDbConnect->query_fetch($sql.$where.$order);
					if (!empty($ret3)){
						$productcategory_list[$i]['small'][$j] = $ret3;  // 小カテゴリ
					}
				}
			}
		}
	}
	
	return $productcategory_list;
}

/**
 * 商品カテゴリ名(HTML)を取得する
 * @return $term_name カテゴリ名HTML
 */
function get_product_category_name_html($term_id){
	$term_name = '';
	
	$productcategory_list = get_product_category();
	
	if (!empty($productcategory_list)){
		foreach ($productcategory_list as $val){
			// 大カテゴリ
			if ($val['big']['term_id'] == $term_id){
				$term_name = '▼'.$val['big']['name'];
				break;
			}
			if( isset($val['small']) ){
				// 小カテゴリ
				foreach ($val['small'] as $sval){
					if ($sval['term_id'] == $term_id){
						$term_name = '▼'.$val['big']['name'].'<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→'.$sval['name'];
						break 2;
					}
				}
			}
		}
	}
	
	return $term_name;
}

/**
 * 商品パンくずリストの取得
 * @param  $objDbConnect DbConnectオブジェクト
 * @param  $pcid 商品カテゴリID
 * @param  $pid  商品ID
 * @return $pankuzu パンくずHTML
 */
function get_product_pankuzu($objDbConnect, $pcid='', $pid=''){
	$pankuzu = '';
	$term_info = array();
	$arr_category = array();
	//if( isset($memcache) ){
	//} else {
	//	$memcache = new Memcache();
	//	$memcache->connect('localhost', 11211);
	//}
	//$results = $memcache->get("get_product_pankuzu");
	$results = false;
	//if( $results ){
	//	$arr_category = $results;
	//} else {
		$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='21' ORDER BY wp_terms.slug ASC";
		$arr_category = $objDbConnect->query_fetch_arr($sql);
		if( !empty($arr_category) ){
			for($i=0;$i<count($arr_category);$i++){
				$temp = array();
				$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$arr_category[$i]["term_id"]."' ORDER BY wp_terms.slug ASC";
				$temp = $objDbConnect->query_fetch_arr($sql);
				if( !empty($temp) ){
					$arr_category[$i]["categorys"] = $temp;
					if( !empty($temp) ){
						for($n=0;$n<count($temp);$n++){
							$temp2 = array();
							$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp[$n]["term_id"]."' ORDER BY wp_terms.slug ASC";
							$temp2 = $objDbConnect->query_fetch_arr($sql);
							if( !empty($temp2) ){
								$arr_category[$i]["categorys"][$n]["categorys"] = $temp2;
								for($m=0;$m<count($temp2);$m++){
									$temp3 = array();
									$sql = "SELECT wp_term_taxonomy.term_id,wp_term_taxonomy.parent,wp_terms.name FROM wp_term_taxonomy left join wp_terms on wp_term_taxonomy.term_id=wp_terms.term_id where taxonomy='category' and parent='".$temp2[$m]["term_id"]."' ORDER BY wp_terms.slug ASC";
									$temp3 = $objDbConnect->query_fetch_arr($sql);
									if( !empty($temp3) ){
										$arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"] = $temp3;
									}
								}
							}
						}
					}
				}
			}
		}
		//$memcache->set( "get_product_pankuzu", $arr_category, false, 28800 );
	//}

	// 商品詳細
	if ($pid != ''){
		$arr_term_id = array();
		$sql = "SELECT product_name,term_id FROM tbl_product WHERE product_id = '$pid'";
		$ret = $objDbConnect->query_fetch($sql);
		//var_dump($ret);
		if ($ret){
			$product_name = $ret['product_name'];
			// カテゴリ(単一の場合)
			if (strpos($ret['term_id'], ',') === false){
				$arr_term_id[] = $ret['term_id'];
				$pcid = $ret['term_id'];
				
			// カテゴリ(複数の場合)
			} else {
				$arr_term_id = explode(',', $ret['term_id']);
				$pcid = $arr_term_id[0];
			}
//var_dump($arr_term_id);
			//$term_info = _get_pankuzu_term_info($objDbConnect, $pcid);

		}

		$arr_pan_cat = array(); // 初期化
		if (!empty($arr_category)) {
		    for ($i = 0; $i < count($arr_category); $i++) {
		        if (in_array($arr_category[$i]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[0]) || count($arr_pan_cat[0]) == 0)) {
		            $arr_pan_cat[0] = [
		                "id" => $arr_category[$i]["term_id"],
		                "name" => $arr_category[$i]["name"]
		            ];

		            if (!empty($arr_category[$i]["categorys"])) {
		                for ($n = 0; $n < count($arr_category[$i]["categorys"]); $n++) {
		                    if (in_array($arr_category[$i]["categorys"][$n]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[1]) || count($arr_pan_cat[1]) == 0)) {
		                        $arr_pan_cat[1] = [
		                            "id" => $arr_category[$i]["categorys"][$n]["term_id"],
		                            "name" => $arr_category[$i]["categorys"][$n]["name"]
		                        ];

		                        if (!empty($arr_category[$i]["categorys"][$n]["categorys"])) {
		                            for ($m = 0; $m < count($arr_category[$i]["categorys"][$n]["categorys"]); $m++) {
		                                if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[2]) || count($arr_pan_cat[2]) == 0)) {
		                                    $arr_pan_cat[2] = [
		                                        "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["term_id"],
		                                        "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["name"]
		                                    ];

		                                    if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"])) {
		                                        for ($x = 0; $x < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"]); $x++) {
		                                            if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[3]) || count($arr_pan_cat[3]) == 0)) {
		                                                $arr_pan_cat[3] = [
		                                                    "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["term_id"],
		                                                    "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["name"]
		                                                ];

		                                                if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"])) {
		                                                    for ($y = 0; $y < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"]); $y++) {
		                                                        if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[4]) || count($arr_pan_cat[4]) == 0)) {
		                                                            $arr_pan_cat[4] = [
		                                                                "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["term_id"],
		                                                                "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["name"]
		                                                            ];

		                                                            if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"])) {
		                                                                for ($z = 0; $z < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"]); $z++) {
		                                                                    if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[5]) || count($arr_pan_cat[5]) == 0)) {
		                                                                        $arr_pan_cat[5] = [
		                                                                            "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["term_id"],
		                                                                            "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["name"]
		                                                                        ];
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
		                        }
		                    }
		                }
		            }
		        }
		    }
		}

		if ($_SERVER['HTTPS'] != ''){
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$pankuzu.= '<div class="pankuzu" style="color:#4b3921;font-size:14px;"><ul>';
		$pankuzu.= '<li><a href="'.$protocol.$_SERVER['HTTP_HOST'].'">TOP</a></li>';
		if (!empty($arr_pan_cat) && is_array($arr_pan_cat)) {
		    for ($i = 0; $i < count($arr_pan_cat); $i++) {
		        if (isset($arr_pan_cat[$i]['id']) && isset($arr_pan_cat[$i]['name'])) {
		            $pankuzu .= '<li>&nbsp;<img src="/img/c_ar_2.png" alt="＞" style="height:10px;" />&nbsp;</li>';
		            $pankuzu .= '<li><a href="'.$protocol.$_SERVER['HTTP_HOST'].'/product/list.php?pcid='.$arr_pan_cat[$i]['id'].'">'.htmlspecialchars( $arr_pan_cat[$i]['name'], ENT_QUOTES, 'UTF-8').'</a></li>';
		        }
		    }
		}

		if (!empty($product_name)) {
		    $pankuzu .= '<li>&nbsp;<img src="/img/c_ar_2.png" alt="＞" style="height:10px;" />&nbsp;</li>';
		    $pankuzu .= '<li>'.$product_name.'</li>';
		}
		$pankuzu.= '</ul><br style="clear;both;"></div>';

	// 商品一覧
	} else {
		//$term_info = _get_pankuzu_term_info($objDbConnect, $pcid);
		/*-----------------------------------------------------------------------------------------------------------------------------*/
		//if (!empty($term_info)){
		//	if ($_SERVER['HTTPS'] != ''){
		//		$protocol = 'https://';
		//	} else {
		//		$protocol = 'http://';
		//	}
		//	
		//	$pankuzu.= '<div style="clear:both;text-align:left;">';
		//	$pankuzu.= '<a href="'.$protocol.$_SERVER['HTTP_HOST'].'">TOP</a>';
		//	if ($term_info['parent_id']!=''){
		//		$pankuzu.= '&nbsp;＞&nbsp;';
		//		$pankuzu.= '<a href="'.$protocol.$_SERVER['HTTP_HOST'].'/product/list.php?pcid='.$term_info['parent_id'].'">'.$term_info['parent_name'].'</a>';
		//	}
		//	if ($term_info['name']!=''){
		//		$pankuzu.= '&nbsp;＞&nbsp;';
		//		$pankuzu.= '<a href="'.$protocol.$_SERVER['HTTP_HOST'].'/product/list.php?pcid='.$pcid.'">'.$term_info['name'].'</a>';
		//	}
		//	if (isset($product_name)){
		//		$pankuzu.= '&nbsp;＞&nbsp;';
		//		$pankuzu.= $product_name;
		//	}
		//	$pankuzu.= '</div>';
		//}
		/*-----------------------------------------------------------------------------------------------------------------------------*/
		$arr_term_id = array();
		//$sql = "SELECT product_name,term_id FROM tbl_product WHERE product_id = '$pid'";
		//$ret = $objDbConnect->query_fetch($sql);
		//if ($ret){
		//	$product_name = $ret['product_name'];
		//	// カテゴリ(単一の場合)
		//	if (strpos($ret['term_id'], ',') === false){
		//		$arr_term_id[] = $ret['term_id'];
		//		$pcid = $ret['term_id'];
		//		
		//	// カテゴリ(複数の場合)
		//	} else {
		//		$arr_term_id = explode(',', $ret['term_id']);
		//		$pcid = $arr_term_id[0];
		//	}
		//}

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
		$temp_category_list = $ret;

		$pcatid = "";
		$loop = 0;
		$arr_term_id[0] = $pcid;
		if($pcid != ""){

			while( $pcatid!="21" && $pcatid!="0" && $loop<10){
				for($i=0;$i<count($temp_category_list);$i++){
					if($temp_category_list[$i]["term_id"]==$arr_term_id[0+$loop]){
						if($temp_category_list[$i]["parent"]!="21" && $temp_category_list[$i]["parent"]!="0"){
							$pcatid = trim($temp_category_list[$i]["parent"]);
							$arr_term_id[] = $pcatid;
							$loop += 1;
						} else {
							$pcatid = trim($temp_category_list[$i]["parent"]);
							$loop += 10;
						}
					}
				}
			}

			$arr_pan_cat = array(); // 初期化
			if (!empty($arr_category)) {
			    for ($i = 0; $i < count($arr_category); $i++) {
			        if (in_array($arr_category[$i]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[0]) || count($arr_pan_cat[0]) == 0)) {
			            $arr_pan_cat[0] = [
			                "id" => $arr_category[$i]["term_id"],
			                "name" => $arr_category[$i]["name"]
			            ];

			            if (!empty($arr_category[$i]["categorys"])) {
			                for ($n = 0; $n < count($arr_category[$i]["categorys"]); $n++) {
			                    if (in_array($arr_category[$i]["categorys"][$n]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[1]) || count($arr_pan_cat[1]) == 0)) {
			                        $arr_pan_cat[1] = [
			                            "id" => $arr_category[$i]["categorys"][$n]["term_id"],
			                            "name" => $arr_category[$i]["categorys"][$n]["name"]
			                        ];

			                        if (!empty($arr_category[$i]["categorys"][$n]["categorys"])) {
			                            for ($m = 0; $m < count($arr_category[$i]["categorys"][$n]["categorys"]); $m++) {
			                                if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[2]) || count($arr_pan_cat[2]) == 0)) {
			                                    $arr_pan_cat[2] = [
			                                        "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["term_id"],
			                                        "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["name"]
			                                    ];

			                                    if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"])) {
			                                        for ($x = 0; $x < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"]); $x++) {
			                                            if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[3]) || count($arr_pan_cat[3]) == 0)) {
			                                                $arr_pan_cat[3] = [
			                                                    "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["term_id"],
			                                                    "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["name"]
			                                                ];

			                                                if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"])) {
			                                                    for ($y = 0; $y < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"]); $y++) {
			                                                        if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[4]) || count($arr_pan_cat[4]) == 0)) {
			                                                            $arr_pan_cat[4] = [
			                                                                "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["term_id"],
			                                                                "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["name"]
			                                                            ];

			                                                            if (!empty($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"])) {
			                                                                for ($z = 0; $z < count($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"]); $z++) {
			                                                                    if (in_array($arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["term_id"], $arr_term_id) && (!isset($arr_pan_cat[5]) || count($arr_pan_cat[5]) == 0)) {
			                                                                        $arr_pan_cat[5] = [
			                                                                            "id" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["term_id"],
			                                                                            "name" => $arr_category[$i]["categorys"][$n]["categorys"][$m]["categorys"][$x]["categorys"][$y]["categorys"][$z]["name"]
			                                                                        ];
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
			                        }
			                    }
			                }
			            }
			        }
			    }
			}
		}

		if ($_SERVER['HTTPS'] != ''){
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$pankuzu.= '<div class="pankuzu" style="clear:both;text-align:left;color:#4b3921;font-size:14px;"><ul>';
		$pankuzu.= '<li><a href="'.$protocol.$_SERVER['HTTP_HOST'].'">TOP</a></li>';
		if (!empty($arr_pan_cat) && is_array($arr_pan_cat)) {
		    for ($i = 0; $i < count($arr_pan_cat); $i++) {
		        if (isset($arr_pan_cat[$i]['id']) && isset($arr_pan_cat[$i]['name'])) {
		            $pankuzu .= '<li>&nbsp;<img src="/img/c_ar_2.png" alt="＞" style="height:10px;" />&nbsp;</li>';
		            $pankuzu .= '<li><a href="'.$protocol.$_SERVER['HTTP_HOST'].'/product/list.php?pcid='.$arr_pan_cat[$i]['id'].'">'.htmlspecialchars( $arr_pan_cat[$i]['name'], ENT_QUOTES, 'UTF-8').'</a></li>';
		        }
		    }
		}

		if (!empty($product_name)) {
		    $pankuzu .= '<li>&nbsp;<img src="/img/c_ar_2.png" alt="＞" style="height:10px;" />&nbsp;</li>';
		    $pankuzu .= '<li>'.$product_name.'</li>';
		}

		$pankuzu .= '</ul><br style="clear:both;"></div>';
	}
	return $pankuzu;
}

/**
 * 商品パンくずリストのカテゴリ名取得
 * @param  $objDbConnect DbConnectオブジェクト
 * @param  $pcid 商品カテゴリID
 * @return $term_info カテゴリ情報配列
 */
function _get_pankuzu_term_info($objDbConnect, $pcid){
	$term_info = array(
		'name'        => '', // カテゴリ名
		'parent_id'   => '', // 親カテゴリID
		'parent_name' => '', // 親カテゴリ名
	);
	
	$sql = "SELECT T1.name, T2.parent FROM wp_terms AS T1 JOIN wp_term_taxonomy AS T2 ON T1.term_id = T2.term_id WHERE T1.term_id = '$pcid'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		if ($ret['parent'] == 21){
			$term_info['name'] = $ret['name'];
		} else {
			$sql = "SELECT name FROM wp_terms WHERE term_id = '".$ret['parent']."'";
			$ret2 = $objDbConnect->query_fetch($sql);
			$term_info['name']        = $ret['name'];
			$term_info['parent_id']   = $ret['parent'];
			$term_info['parent_name'] = $ret2['name'];
		}
	}
	
	return $term_info;
}



/**
 * 決済時の商品情報取得
 * @praam  $objDbConnect DbConnectオブジェクト
 * @param  $pid 商品ID
 * @return $arr_list 商品情報配列
 */
function get_settlement_product($objDbConnect, $pid){
	date_default_timezone_set('Asia/Tokyo');

	$arr_list = array();
	
	$sql = "SELECT * FROM tbl_product WHERE product_id = '$pid' AND del_flg='0' AND ( (start_date<='".date("Y-m-d")."' AND end_date>='".date("Y-m-d")."') OR (start_date<='".date("Y-m-d")."' AND end_date IS NULL) OR (start_date IS NULL AND end_date>='".date("Y-m-d")."') OR (start_date IS NULL AND end_date IS NULL) )";
	$arr_list = $objDbConnect->query_fetch($sql);
	if (!empty($arr_list)){
		$arr_list['disp_start_date'] = date('Y-m-d H:i', strtotime($arr_list['start_date']));
		$arr_list['disp_end_date'] = date('Y-m-d H:i', strtotime($arr_list['end_date']));
		$arr_list['tax'] = tax_cal_yen($arr_list['price']);
		$arr_list['subtotal'] = $arr_list['price'] + $arr_list['tax'];
		$arr_list['total'] = $arr_list['subtotal'] + COMMISSION_YEN;
		$arr_list['commission'] = COMMISSION_YEN;
	}
	
	return $arr_list;
}

/**
 * 消費税の計算
 * @param  $price 金額
 * @return $tax 消費税
 */
function tax_cal_yen($price){
	$tax = 0;
	$tax_temp = $price * TAX_RATE_YEN / 100;
	if (is_int($tax_temp)){
		$tax = $tax_temp;
	} else {
		switch (TAX_FLAG_YEN){
			case 0:
				$tax = 0;
				break;
			case 1:
				$tax = round($tax_temp);
				break;
			case 2:
				$tax = ceil($tax_temp);
				break;
			case 3:
				$tax = floor($tax_temp);
				break;
			default:
		}
	}
	
	return $tax;
}

/**
 * 購入済み商品かどうかチェックする
 * @param  $objDbConnect DbConnectオブジェクト
 * @param  $pid 商品ID
 * @param  $user_id ユーザーID
 * @return bool true:購入済み false:未購入
 */
function buy_check($objDbConnect, $pid, $user_id){
	$sql = "SELECT";
	$sql.= "  T1.order_id";
	$sql.= " FROM";
	$sql.= "  tbl_order AS T1";
	$sql.= "   LEFT JOIN";
	$sql.= "  tbl_order_detail AS T2";
	$sql.= "   ON T1.order_id = T2.order_id";
	$sql.= " WHERE";
	$sql.= "  T1.del_flg = '0'";
	$sql.= "  AND T1.payment_status = '2'";
	$sql.= "  AND T2.member_id = '".$user_id."'";
	$sql.= "  AND T2.product_id = '".$pid."'";
	$sql.= " ORDER BY T1.order_id DESC";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		return true;
	} else {
		return false;
	}
}

/**
 * 購入済み商品かどうか及び、公開終了日を迎えたかチェックする(視聴可能かどうか)
 * @param  $objDbConnect DbConnectオブジェクト
 * @param  $pid 商品ID
 * @param  $user_id ユーザーID
 * @param  $live_flg 会場研修商品フラグ
 * @return bool true:視聴可能 false:視聴不可
 */
function buy_and_open_period_date_check($objDbConnect, $pid, $user_id, $live_flg=false){
	date_default_timezone_set('Asia/Tokyo');

	$flg = true;
	
	$sql = "SELECT";
/*
	$sql.= "  T1.order_id,";
	$sql.= "  T2.order_detail_id,";
	$sql.= "  T3.product_id,";
	$sql.= "  DATE_FORMAT(T1.payment_date, '%Y%m%d') AS payment_date,";
*/
	$sql.= "  T3.open_period,";
	$sql.= "  DATE_FORMAT(DATE_ADD(T1.payment_date, INTERVAL T3.open_period DAY), '%Y%m%d') AS open_period_date";
	$sql.= " FROM";
	$sql.= "  tbl_order AS T1";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_order_detail AS T2";
	$sql.= "     ON T1.order_id = T2.order_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product AS T3";
	$sql.= "     ON T2.product_id = T3.product_id";
	$sql.= " WHERE";
	$sql.= "  T1.del_flg = '0'";
	if ($live_flg){
	$sql.= "  AND T2.payment_status <> '9'";
	} else {
	$sql.= "  AND T2.payment_status = '2'";
	}
	$sql.= "  AND T2.member_id = '".$user_id."'";
	$sql.= "  AND T2.product_id = '".$pid."'";
	$sql.= " ORDER BY T2.order_detail_id DESC"; // 二度目以降の購入判断のため最新の購入履歴を取得
	
	$ret = $objDbConnect->query_fetch($sql);
	// 戻りがあった場合は購入履歴あり
	if ($ret){
		// 公開終了期間が設定されていて、現在日付が公開終了日を超えている
		if ($ret['open_period']!=0 && date('Ymd') > $ret['open_period_date']){
			$flg = false;
		}
	} else {
		$flg = false;
	}
	
	return $flg;
}
function buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id, $live_flg=false){
	date_default_timezone_set('Asia/Tokyo');

	$flg = true;
	
	$arr_pids = array();
	$arr_pids[] = $pid;
	$sql_check = "";
	$sql_check.= "SELECT ";
	$sql_check.= " tbl_product.product_id, ";
	$sql_check.= " tbl_product_elearning.live_training_product_id ";
	$sql_check.= "FROM ";
	$sql_check.= " tbl_product ";
	$sql_check.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
	$sql_check.= "WHERE ";
	$sql_check.= " tbl_product.product_id='".$pid."' ";
//var_dump($sql_check);
//exit();
	$ret_check = $objDbConnect->query_fetch($sql_check);
//var_dump($ret_check);
//exit();
	if ($ret_check){
		if( trim($ret_check['live_training_product_id'])!="" ){
			// 紐付いている会場研修商品が購入済みかチェック
			// sano //$sql_buy_check = "SELECT COUNT(order_detail_id) AS c FROM tbl_order_detail WHERE product_id = '".$ret_check['live_training_product_id']."' AND member_id = '$user_id' AND payment_status = 2 ORDER BY order_detail_id DESC LIMIT 1";
			// sano //$ret_buy_check = $objDbConnect->query_fetch($sql_buy_check);
			// sano //if ($ret_buy_check['c']>0){
				$arr_pids[] = $ret_check['live_training_product_id'];
				if( (int)$ret_check['live_training_product_id']>10000 ){
					$sql_check = "";
					$sql_check.= "SELECT ";
					$sql_check.= " ( KENSHU_ID + 10000 ) as new_product_id ";
					$sql_check.= "FROM ";
					$sql_check.= " import_kenshu_count ";
					$sql_check.= "WHERE ";
					$sql_check.= " KENSHU_ID='".((int)$ret_check['live_training_product_id'] - 10000)."' ";
					$sql_check.= " OR ROOT_ID='".((int)$ret_check['live_training_product_id'] - 10000)."' ";
					$ret_check2 = $objDbConnect->query_fetch_arr($sql_check);
//var_dump($sql_check);
//exit();
					if ($ret_check2){
						$count_check = count($ret_check2);
						for ($n=0; $n<$count_check; $n++){
							$arr_pids[] = $ret_check2[$n]['new_product_id'];
						}
					}
				}
			// sano //}
		}
	}
	$arr_pids2 = array_unique($arr_pids);

	$sql = "SELECT ";
/*
	$sql.= "  T1.order_id,";
	$sql.= "  T2.order_detail_id,";
	$sql.= "  T3.product_id,";
	$sql.= "  DATE_FORMAT(T1.payment_date, '%Y%m%d') AS payment_date,";
*/
	$sql.= "  T3.open_period,";
	$sql.= "  T4.product_type_add,";

	$sql.= "  DATE_FORMAT(DATE_ADD(T1.payment_date, INTERVAL T3.open_period DAY), '%Y%m%d') AS open_period_date";
	$sql.= " FROM";
	$sql.= "  tbl_order AS T1";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_order_detail AS T2";
	$sql.= "     ON T1.order_id = T2.order_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product AS T3";
	$sql.= "     ON T2.product_id = T3.product_id";
	$sql.= "    LEFT JOIN";
	$sql.= "  tbl_product_add AS T4";
	$sql.= "     ON T3.product_id = T4.product_id";
	$sql.= " WHERE";
	$sql.= "  T1.del_flg = '0'";
	//if ($live_flg){
	//	$sql.= "  AND T2.payment_status <> '9' ";
	//} else {
	//	$sql.= "  AND T2.payment_status = '2'";
	//}
	//$sql.= "  AND T2.payment_status <> '9' ";
	//$sql.= "  AND T2.payment_status = '2'";

	//$sql.= "  AND ( ( T2.payment_status <> '9' and T4.product_type_add='2' ) OR ( T2.payment_status ='2' and T4.product_type_add<>'2' ) ) ";
	$sql.= "  AND ( ( T2.payment_status='2' and T4.product_type_add='2' ) OR ( T2.payment_status ='2' and T4.product_type_add<>'2' ) ) ";

	$sql.= "  AND T2.member_id = '".$user_id."'";
	//$sql.= "  AND T2.product_id = '".$pid."'";
	$sql.= "  AND T2.product_id IN (".trim(implode(",",$arr_pids2),",").") ";
	$sql.= " ORDER BY T2.order_detail_id DESC"; // 二度目以降の購入判断のため最新の購入履歴を取得
//var_dump($sql);
//exit();
	$ret = $objDbConnect->query_fetch($sql);
	// 戻りがあった場合は購入履歴あり
	if ($ret){
		// 公開終了期間が設定されていて、現在日付が公開終了日を超えている
		if ($ret['open_period']!=0 && date('Ymd') > $ret['open_period_date']){
			$flg = false;
		}
	} else {
		$flg = false;
	}
	return $flg;
}

/**
 * プレイヤーの取得(alfredcore api)
 * @param  $arr_data 動画取得必要情報(初期化部分の形で渡す)
 * @return $player プレイヤーHTMLタグ
 */
function get_alf_player($arr_data){
	$player = array();
	
	// ssl初期値設定
	// (呼び出し元で$arr_data['ssl']をセットすればページごとで強制できる)
	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
		$_SERVER['HTTPS'] = 'on';
	}
	$_SERVER['HTTPS'] = 'on';
	if ($_SERVER['HTTPS']!=''){
		$ssl = 1;
	} else {
		$ssl = 0;
	}
	$ssl = 1;
	
	// 初期化
	$data = array(
		"authkey"		=> ALFSTREAM_AUTHKEY,
		"ckey"			=> '',
		// "coder"		=> '360p',
		"coder"			=> '480p',
		"type"			=> 'dl',
		"player"		=> 'DEFAULT',
		"player.type"		=> 'akplayer',
		"player.width"		=> '640',
		"player.height"		=> '360',
		// "player.controller" => 'seekbar',
		"player.controller" => 'nichibenren',
		"client_ip"		=> '211.129.78.63',
		"client_ua"		=> '',
		"count_limit"		=> '',
		"timeout_last"		=> '',
		"timeout_first" 	=> '',
		"timeout_interval"	=> '',
		"uid"			=> '0',
		"ssl"			=> $ssl,
		"playrate"		=> '',
	);
	
	// 引数のセット
	if(isset($arr_data['authkey'])){
		$data['authkey'] = $arr_data['authkey'];
	}
	if(isset($arr_data['ckey'])){
		$data['ckey'] = $arr_data['ckey'];
	}
	if(isset($arr_data['coder'])){
		$data['coder'] = $arr_data['coder'];
	}
	if(isset($arr_data['type'])){
		$data['type'] = $arr_data['type'];
	}
	if(isset($arr_data['player'])){
		$data['player'] = $arr_data['player'];
	}
	if(isset($arr_data['player.type'])){
		$data['player.type'] = $arr_data['player.type'];
	}
	if(isset($arr_data['player.width'])){
		$data['player.width'] = $arr_data['player.width'];
	}
	if(isset($arr_data['player.height'])){
		$data['player.height'] = $arr_data['player.height'];
	}
	if(isset($arr_data['player.controller'])){
		$data['player.controller'] = $arr_data['player.controller'];
	}
	if(isset($arr_data['client_ip'])){
		$data['client_ip'] = $arr_data['client_ip'];
	}
	if(isset($arr_data['client_ua'])){
		$data['client_ua'] = $arr_data['client_ua'];
	}
	if(isset($arr_data['count_limit'])){
		$data['count_limit'] = $arr_data['count_limit'];
	}
	if(isset($arr_data['timeout_last'])){
		$data['timeout_last'] = $arr_data['timeout_last'];
	}
	if(isset($arr_data['timeout_first'])){
		$data['timeout_first'] = $arr_data['timeout_first'];
	}
	if(isset($arr_data['timeout_interval'])){
		$data['timeout_interval'] = $arr_data['timeout_interval'];
	}
	if(isset($arr_data['uid'])){
		$data['uid'] = $arr_data['uid'];
	}
	if(isset($arr_data['ssl'])){
		$data['ssl'] = $arr_data['ssl'];
	}
	
	if(isset($arr_data['playrate'])){
		$data['playrate'] = $arr_data['playrate'];
	}

	$data['client_ua'] = $_SERVER['HTTP_USER_AGENT'];

	$url = ALFSTREAM_PLAYER_URL;
	
	$data = http_build_query($data, "", "&");
	$header = array(
	        "Content-Type: application/x-www-form-urlencoded",
	        "Content-Length: ".strlen($data)
	    );
	$options =array(
	    'http' =>array(
	            'method' => 'POST',
	            'header' => implode("\r\n", $header),
	            'content' => $data
	        )
	    );
//var_dump($url);
//print_r("<!-- ");
//var_dump($data);
//print_r(" -->\n");
//var_dump($options);
	$contents =file_get_contents($url, false, stream_context_create($options));
//print_r("<!-- ");
//var_dump($contents);
//print_r(" -->\n");

//print("<textarea>");
//var_dump($contents);
//print("</textarea>");
	$res_dat = json_decode($contents);
	// プレイヤー取得成功
	if($res_dat->stat == 200) {
		$player['err'] = 0;
		$player['player'] = $res_dat->dat->code;
	} else {
		$player['err'] = 1;
		$player['message'] = $res_dat->message;
	}
	
	return $player;
}
/**
 * 動画サムネイルの取得(アルフストリームから)
 */
/*
function get_alf_thumbnail($idkey){
	$query_params = array(
		'authkey' => ALFSTREAM_AUTHKEY
	);
	
	$url_path_query = ALFSTREAM_ASSET_PATH . $idkey.'?' . http_build_query($query_params);
	
	$url = ALFSTREAM_API_URL_HTTP . $url_path_query;
	
	$res = file_get_contents($url, false, stream_context_create(array('https' => array(
		'method' => 'GET',
		'header' => "Content-Type: application/x-www-form-urlencode"
	))));
	
	$res_decode = json_decode($res);
	
	if ($res_decode->stat == 200){
		return $res_decode->dat->posters[0]->url;
	}
	
	return false;
}
*/

/**
 * 動画サムネイルの取得(DBから)
 */
function get_alf_thumbnail_db($video_id){
	$objDbConnect = new DbConnect();
	
	// サムネイルの取得
	$ret = '';
	$sql = "SELECT * FROM video_alfstream_status WHERE video_id='".$video_id."'";
	
	$ret = $objDbConnect->query_fetch($sql);
	
	if ($ret){
		$arr_player_thumbnail['alfstream_duration'] = $ret['alfstream_duration'];
		
		$arr_player_thumbnail_temp["contents_contents$i"] = $ret;
		$arr_player_thumbnail_temp["contents_contents$i"] = json_decode($arr_player_thumbnail_temp["contents_contents$i"]["alfstream_thumbnail"],true);
		// 取得画像名にパスを設定する
		$img_no = 0;
		$p180 = '180p';
		$p270 = '270p';
		$p360 = '360p';
		foreach ($arr_player_thumbnail_temp["contents_contents$i"] as $contents_thumbnail){
			$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p180'] = $idkey.'/'.$contents_thumbnail->$p180;
			$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['imov'] = $idkey.'/'.$contents_thumbnail->imov;
			$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['imov_mpeg4'] = $idkey.'/'.$contents_thumbnail->imov_mpeg4;
			$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p270'] = $idkey.'/'.$contents_thumbnail->$p270;
			$arr_player_thumbnail["contents_thumbnail$i"][$img_no]['p360'] = $idkey.'/'.$contents_thumbnail->$p360;
			$img_no++;
		}
		
		return $arr_player_thumbnail;
	}
	
	return false;
}

/**
 * headerのtitleを設定する(wordpressのget_headerを呼んでいる箇所)
 */
function set_page_wp_title(){
	if (isset($_SESSION['wp_page_head_title'])){
		$title.= $_SESSION['wp_page_head_title'];
	}
	$title.= ' | ';
	
	return $title;
}

/**
 * metaタグkeywords用文字列の取得(商品カテゴリ)
 * @param  $objDbConnect DbConnectオブジェクト
 * @param  $pid 商品ID
 * @return $meta_keywords metaタグkeywords用文字列
 */
function get_product_gategory_meta_keywords($objDbConnect, $pid){
	$meta_keywords = '';
	
	$sql = "SELECT product_name,term_id FROM tbl_product WHERE product_id = '$pid'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$product_name = $ret['product_name'];
		// カテゴリ(単一の場合)
		if (strpos($ret['term_id'], ',') === false){
			$term_id = $ret['term_id'];
			$sql = "SELECT name FROM wp_terms WHERE term_id = '$term_id'";
			$arr_term_name = $objDbConnect->query_fetch($sql);
			if ($arr_term_name){
				$meta_keywords = $arr_term_name['name'];
			}
			
		// カテゴリ(複数の場合)
		} else {
			$arr_term_id = explode(',', $ret['term_id']);
			foreach ($arr_term_id as $term_id){
				$sql = "SELECT name FROM wp_terms WHERE term_id = '$term_id'";
				$arr_term_name = $objDbConnect->query_fetch($sql);
				if ($arr_term_name){
					$meta_keywords.= $arr_term_name['name'].',';
				}
			}
			$meta_keywords = rtrim($meta_keywords, ',');
		}
	}
	
	return $meta_keywords;
}

/**
 * 動画サムネイルの取得(alfredcore api)
 * ※仕様変更のため未使用
 * @param  $arr_data 動画サムネイル取得必要情報(初期化部分の形で渡す)
 * @return $thumbnail stdClassObject配列
 */
/*
function get_alf_player_thumbnail($arr_data){
	$player_thumbnail = array();
	
	// 初期化
	$data = array(
		"authkey"		=> ALFSTREAM_AUTHKEY,
//		"ckey"				=> '',
"ckey" => '1TO6uMMsFZsM', // テスト用ckey
	);
	
	// 引数のセット
	if(isset($arr_data['authkey'])){
		$data['authkey'] = $arr_data['authkey'];
	}
	if(isset($arr_data['ckey'])){
		$data['ckey'] = $arr_data['ckey'];
	}
	
	
	$data = array(
		"authkey"		=> "",
		"ckey"			=> "1TO6uMMsFZsM",
	);
	
	$url = 'http://api3.alfstr-test.alfredcore.net/v1/asset/3KDryICYYBSM';
//	$url = 'http://api3.alfstr-test.alfredcore.net/v1/asset/'.$data['ckey'];
	
	$data = http_build_query($data, "", "&");
	$header = array(
	        "Content-Type: application/x-www-form-urlencoded",
	        "Content-Length: ".strlen($data)
	    );
	$options =array(
	    'http' =>array(
	            'method' => 'GET',
	            'header' => implode("\r\n", $header),
	            'content' => $data
	        )
	    );
	$contents =file_get_contents($url, false, stream_context_create($options));
	$res_dat = json_decode($contents);
	if($res_dat->stat == 200) {
		foreach ($res_dat->dat->posters as $k1 => $v1) {
			$player_thumbnail[] = $v1;
		}
	}
	
	return $player_thumbnail;
}
*/

/**
 * 動画サムネイルの取得(alfredcore api)
 * ※仕様変更のため未使用
 * @param  $arr_data 動画サムネイル取得必要情報(初期化部分の形で渡す)
 * @return $player_thumbnail_arr stdClassObject配列
 */
/*
function get_alf_player_thumbnail_arr($arr_data){
	$player_thumbnail_arr = array();
	
	// 初期化
	$data = array(
		"authkey"		=> ALFSTREAM_AUTHKEY,
//		"q"				=> "",
"q"			=> "idkey:Z5zsuhAR5JPx",
		"thumbnail"		=> "*",
	);
	
	// 引数のセット
	if(isset($arr_data['authkey'])){
		$data['authkey'] = $arr_data['authkey'];
	}
	if(isset($arr_data['q'])){
		$data['q'] = 'idkey:'.$arr_data['q'];
	}
	if(isset($arr_data['thumbnail'])){
		$data['thumbnail'] = $arr_data['thumbnail'];
	}
	
	$url = 'http://api3.alfstr-test.alfredcore.net/v1/asset/';
	
	$data = http_build_query($data, "", "&");
	$header = array(
	        "Content-Type: application/x-www-form-urlencoded",
	        "Content-Length: ".strlen($data)
	    );
	$options =array(
	    'http' =>array(
	            'method' => 'GET',
	            'header' => implode("\r\n", $header),
	            'content' => $data
	        )
	    );
	$contents =file_get_contents($url, false, stream_context_create($options));
	$res_dat = json_decode($contents);
	if($res_dat->stat == 200) {
		foreach ($res_dat->dat->items[0]->thumbnail as $k1 => $v1) {
			$player_thumbnail_arr[] = $v1;
		}
	}
	
	return $player_thumbnail_arr;
}
*/

/**
 * android2.3以下の判断
 */
function get_android_ver_flg(){
	$android_ver_flg = false;
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'Mozilla/5.0 (Linux; U; Android 1.') !== false){
		$android_ver_flg = true;
	} elseif (strpos($user_agent, 'Mozilla/5.0 (Linux; U; Android 2.0') !== false){
		$android_ver_flg = true;
	} elseif (strpos($user_agent, 'Mozilla/5.0 (Linux; U; Android 2.1') !== false){
		$android_ver_flg = true;
	} elseif (strpos($user_agent, 'Mozilla/5.0 (Linux; U; Android 2.2') !== false){
		$android_ver_flg = true;
	} elseif (strpos($user_agent, 'Mozilla/5.0 (Linux; U; Android 2.3') !== false){
		$android_ver_flg = true;
	}
	
	return $android_ver_flg;
}

if(!function_exists('mb_str_replace')) {
    /**
     * マルチバイト対応 str_replace()
     * 
     * @param   mixed   $search     検索文字列（またはその配列）
     * @param   mixed   $replace    置換文字列（またはその配列）
     * @param   mixed   $subject    対象文字列（またはその配列）
     * @param   string  $encoding   文字列のエンコーディング(省略: 内部エンコーディング)
     *
     * @return  mixed   subject 内の search を replace で置き換えた文字列
     *
     * この関数の $search, $replace, $subject は配列に対応していますが、
     * $search, $replace が配列の場合の挙動が PHP 標準の str_replace() と異なります。
     */
    function mb_str_replace($search, $replace, $subject, $encoding = 'auto') {
        if(!is_array($search)) {
            $search = array($search);
        }
        if(!is_array($replace)) {
            $replace = array($replace);
        }
        if(strtolower($encoding) === 'auto') {
            $encoding = mb_internal_encoding();
        }

        // $subject が複数ならば各要素に繰り返し適用する
        if(is_array($subject) || $subject instanceof Traversable) {
            $result = array();
            foreach($subject as $key => $val) {
                $result[$key] = mb_str_replace($search, $replace, $val, $encoding);
            }
            return $result;
        }

        $currentpos = 0;    // 現在の検索開始位置
        while(true) {
            // $currentpos 以降で $search のいずれかが現れる位置を検索する
            $index = -1;    // 見つけた文字列（最も前にあるもの）の $search の index
            $minpos = -1;   // 見つけた文字列（最も前にあるもの）の位置
            foreach($search as $key => $find) {
                if($find == '') {
                    continue;
                }
                $findpos = mb_strpos($subject, $find, $currentpos, $encoding);
                if($findpos !== false) {
                    if($minpos < 0 || $findpos < $minpos) {
                        $minpos = $findpos;
                        $index = $key;
                    }
                }
            }

            // $search のいずれも見つからなければ終了
            if($minpos < 0) {
                break;
            }

            // 置換実行
            $r = array_key_exists($index, $replace) ? $replace[$index] : '';
            $subject =
                mb_substr($subject, 0, $minpos, $encoding) .    // 置換開始位置より前
                $r .                                            // 置換後文字列
                mb_substr(                                      // 置換終了位置より後ろ
                    $subject,
                    $minpos + mb_strlen($search[$index], $encoding),
                    mb_strlen($subject, $encoding),
                    $encoding);

            // 「現在位置」を $r の直後に設定
            $currentpos = $minpos + mb_strlen($r, $encoding);
        }
        return $subject;
    }
}

/**
 * 弁護士会主催研修の更新日取得
 */
function get_bar_association_live_update_date(){
	date_default_timezone_set('Asia/Tokyo');
	$objDbConnect = new DbConnect();
	
$sql = "
SELECT
  DATE_FORMAT(MAX(tbl_product.update_date), '%Y年%m月%d日') AS start_date
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
WHERE
  tbl_product.del_flg = 0
  AND tbl_product_add.product_type_add = 2
  AND tbl_product_live_training.sponsor LIKE '%|".$_SESSION['user']['bar_association_id']."|%'
LIMIT 1
 ";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		return $ret['start_date'];
	}
	
	return false;
}

/**
 * 動画idkeyの取得
 */
function get_idkey($video_id){
	$objDbConnect = new DbConnect();
	
	$sql = "SELECT idkey FROM video WHERE video_id = $video_id";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		return $ret['idkey'];
	}
	
	return false;
}

/**
 * time型のデータの加算
 * @param $a time型の時間(00:00:00形式)
 * @param $b time型の時間(00:00:00形式)
 */
function getTimeAddition($a,$b){
	$a_array = explode(":",$a);
	$b_array = explode(":",$b);
	
	// 時、分、秒の合計
	$h_array = $a_array[0]+$b_array[0];
	$i_array = $a_array[1]+$b_array[1];
	$s_array = $a_array[2]+$b_array[2];
	
	$s = $s_array-(floor($s_array/60)*60);  // 秒の計算
	$i = ($i_array+floor($s_array/60))-(floor(($i_array+floor($s_array/60))/60)*60);  // 分の計算
	$h = $h_array+floor(($i_array+floor($s_array/60))/60); // 時間の計算
	
	// 一桁だったら'0'を付加
	if($h<10){$h='0'.$h;}
	if($i<10){$i='0'.$i;}
	if($s<10){$s='0'.$s;}
	
	return $h.":".$i.":".$s;
}

/**
 * time型のデータの減算
 * @param $a time型の時間(00:00:00形式)
 * @param $b time型の時間(00:00:00形式)
 */
function getTimeSubtraction($a,$b){
	$a_array = explode(":",$a);
	$b_array = explode(":",$b);
	
	// 時、分、秒の合計
	$h_array = $a_array[0]-$b_array[0];
	$i_array = $a_array[1]-$b_array[1];
	$s_array = $a_array[2]-$b_array[2];
	
	$s = $s_array-(floor($s_array/60)*60);  // 秒の計算
	$i = ($i_array+floor($s_array/60))-(floor(($i_array+floor($s_array/60))/60)*60);  // 分の計算
	$h = $h_array+floor(($i_array+floor($s_array/60))/60); // 時間の計算
	
	// 一桁だったら'0'を付加
	if($h<10){$h='0'.$h;}
	if($i<10){$i='0'.$i;}
	if($s<10){$s='0'.$s;}
	
	return $h.":".$i.":".$s;
}

/**
 * time型のデータを変換する(0時間0分0秒)
 */
function time_format_product_list($time){
	$arr_time = explode(":",$time);
	return $arr_time[0].'時間'.$arr_time[1].'分'.$arr_time[2].'秒';
}

/**
 * ソート用セレクトボックス
 */
function get_sort_selectbox(){
	//return array('1'=>'新しい順', '2'=>'古い順', '3'=>'掲載終了間近順');
	return array('1'=>'新しい順', '2'=>'古い順');
}

/**
 * 曜日の取得
 */
function get_japan_youbi(){
	return array('日', '月', '火', '水', '木', '金', '土');
}

/**
 * 掲載終了間近の研修の有無チェック
 */
function contents_limit_user_check(){
	date_default_timezone_set('Asia/Tokyo');

	if (st_login_check()){
		$objDbConnect = new DbConnect();
		
		$sql = "SELECT";
		$sql.= "  COUNT(*) AS c";
		$sql.= " FROM";
		$sql.= "  tbl_order_detail AS T1";
		$sql.= "    LEFT JOIN";
		$sql.= "  tbl_product AS T2";
		$sql.= "      ON T1.product_id = T2.product_id";
		$sql.= " WHERE";
		$sql.= "  T1.payment_status = '2'";
		$sql.= "  AND T1.product_type_add IN(1,2)";
		$sql.= "  AND T1.member_id = '".$_SESSION['user']['id']."'";
		$sql.= "  AND T2.del_flg = '0'";
		$sql.= "  AND DATE_SUB(T2.end_date, INTERVAL 1 MONTH) <= '".date("Y-m-d")."'";
		$sql.= "  AND ( (T2.start_date<='".date("Y-m-d")."' AND T2.end_date>='".date("Y-m-d")."') OR (T2.start_date<='".date("Y-m-d")."' AND T2.end_date IS NULL) OR (T2.start_date IS NULL AND T2.end_date>='".date("Y-m-d")."') OR (T2.start_date IS NULL AND T2.end_date IS NULL) )";
		$ret = $objDbConnect->query_fetch($sql);
		if ($ret){
			$count = $ret["c"];
		} else {
			$count = 0;
		}
		
		if ($count>0){
			return true;
		}

	}
	
	return false;
}

/**
 * 支払名称の取得
 */
function get_str_payment_type($payment_type){
	switch ($payment_type){
		case 1:
			$str_payment_type = 'カード決済';
			break;
		case 2:
			$str_payment_type = 'モバイルSuica決済';
			break;
		case 3:
			$str_payment_type = '楽天Edy決済';
			break;
		case 4:
			$str_payment_type = 'コンビニ決済';
			break;
		case 5:
			$str_payment_type = 'Pay-easy';
			break;
		case 6:
			$str_payment_type = 'PayPal';
			break;
		case 7:
			$str_payment_type = 'iDネット';
			break;
		case 8:
			$str_payment_type = 'WebMoney';
			break;
		case 9:
			$str_payment_type = 'auかんたん決済';
			break;
		case 10:
			$str_payment_type = 'ドコモケータイ払い';
			break;
		case 11:
			$str_payment_type = 'ソフトバンクケータイ支払い';
			break;
		case 12:
			$str_payment_type = '銀行振り込み';
			break;
		default:
			$str_payment_type = '';
	}
	
	return $str_payment_type;
}

/**
 * 決済状況名称の取得
 */
function get_str_payment_status($payment_status){
	switch ($payment_status){
		case 1:
			$str_payment_status = '入金待ち';
			break;
		case 2:
			$str_payment_status = '入金済み';
			break;
		case 9:
			$str_payment_status = 'キャンセル';
			break;
		default:
			$str_payment_status = '未入金';
	}
	
	return $str_payment_status;
}

/**
 * 決済状況名称の取得(バックオフィス講座管理)
 */
function get_str_payment_status_kouza($payment_status){
	switch ($payment_status){
		case 1:
			$str_payment_status = '未入金';
			break;
		case 2:
			$str_payment_status = '支払済';
			break;
		case 3:
			$str_payment_status = '仮入金';
			break;
		case 9:
			$str_payment_status = 'キャンセル';
			break;
		default:
			$str_payment_status = '未入金';
	}
	
	return $str_payment_status;
}

/**
 * 決済状況名称色の取得(バックオフィス講座管理)
 */
function get_str_payment_status_kouza_color($payment_status){
	switch ($payment_status){
		case 1:
			$str_payment_status = '#ff0000';
			break;
		case 2:
			$str_payment_status = '#0000ff';
			break;
		case 3:
			$str_payment_status = '#00ff00';
			break;
		case 9:
			$str_payment_status = '#000000';
			break;
		default:
			$str_payment_status = '#00a4e2';
	}
	
	return $str_payment_status;
}

/**
 * 倫理研修受講ステイタス名称の取得(バックオフィス講座管理)
 */
function get_str_ethic_status_kouza($ethic_status){
	switch ($ethic_status){
		case 1:
			$str_ethic_status = '受講中';
			break;
		case 2:
			$str_ethic_status = '一次○';
			break;
		case 3:
			$str_ethic_status = '一次×';
			break;
		case 4:
			$str_ethic_status = '一次×';
			break;
		case 5:
			$str_ethic_status = '追試○';
			break;
		case 6:
			$str_ethic_status = '追試×';
			break;
		case 7:
			$str_ethic_status = 'レポート';
			break;
		case 8:
			$str_ethic_status = '会場';
			break;
		default:
			$str_ethic_status = '未受講';
	}
	
	return $str_ethic_status;
}

/**
 * 領収書発行名称の取得
 */
function get_str_receipt_flg($receipt_flg){
	switch ($receipt_flg){
		case 1:
			$str_receipt_flg = '発行済';
			break;
		default:
			$str_receipt_flg = '未発行';
	}
	
	return $str_receipt_flg;
}

/**
 * 倫理研修問題の商品IDを取得
 */
function get_ethic_product_id(){
	$objDbConnect = new DbConnect();
	
	$sql = "
	SELECT
	  T1.product_id
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_add AS T2
	      ON T1.product_id = T2.product_id
	    INNER JOIN
	  tbl_product_ethic_training AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  AND T2.product_type_add = 3
	  AND T3.publish_flg = 1
	ORDER BY T1.product_id DESC
	LIMIT 1
	 ";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['product_id'];
	}
	
	return false;
}

/**
 * 会場研修商品の申込者数を取得
 */
function get_entry_number($bar_association_branch_id, $product_id, $training_kind_flg = ""){
	$objDbConnect = new DbConnect();

	// 旧データの確認
	if ($product_id <= 19233) {
		$product_id_old = $product_id - 10000;
		if ($training_kind_flg == "2" || $training_kind_flg == "3") {
			$sql = "
			SELECT
			  SUM(KENSHU_COUNT) AS c
			FROM
			  import_kenshu_count
			WHERE
			  KENSHU_ID = '$product_id_old'
			";
		}
		else {
			if ($bar_association_branch_id == 1) {
				$sql = "
				SELECT
				  SUM(KENSHU_COUNT) AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = '$product_id_old' AND
				  FROM_ID = 0
				";
			}
			else {
				$sql = "
				SELECT
				  KENSHU_COUNT AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = 0 AND
				  KENSHU_ID = '$product_id_old' AND
				  bar_association_branch_id = '$bar_association_branch_id'
				";
			}
		}
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			if ($res['c'] > 0) {
				return $res['c'];
			}
		}
		else {
			$sql = "
			SELECT
			  KENSHU_COUNT AS c
			FROM
			  import_kenshu_count
			WHERE
			  ROOT_ID = '$product_id_old' AND
			  bar_association_branch_id = '$bar_association_branch_id'
			";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				if ($res['c'] > 0) {
					return $res['c'];
				}
			}
		}
	}
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail
	WHERE
	  ( payment_status = 1 OR payment_status = 2 OR payment_status = 3 )
	  AND product_type_add = 2
	  AND bar_association_branch_id = '$bar_association_branch_id'
	  AND product_id = '$product_id'
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}
function get_entry_number2($bar_association_branch_id, $product_id, $training_kind_flg = ""){
	$objDbConnect = new DbConnect();

	// 旧データの確認
/*
	if ($product_id <= 19233) {
		$product_id_old = $product_id - 10000;
		if ($training_kind_flg == "2" || $training_kind_flg == "3") {
			$sql = "
			SELECT
			  SUM(KENSHU_COUNT) AS c
			FROM
			  import_kenshu_count
			WHERE
			  KENSHU_ID = '$product_id_old'
			";
		}
		else {
			if ($bar_association_branch_id == 1) {
				$sql = "
				SELECT
				  SUM(KENSHU_COUNT) AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = '$product_id_old' AND
				  FROM_ID = 0
				";
			}
			else {
				$sql = "
				SELECT
				  KENSHU_COUNT AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = 0 AND
				  KENSHU_ID = '$product_id_old' AND
				  bar_association_branch_id = '$bar_association_branch_id'
				";
			}
		}
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			if ($res['c'] > 0) {
				return $res['c'];
			}
		}
		else {
			$sql = "
			SELECT
			  KENSHU_COUNT AS c
			FROM
			  import_kenshu_count
			WHERE
			  ROOT_ID = '$product_id_old' AND
			  bar_association_branch_id = '$bar_association_branch_id'
			";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				if ($res['c'] > 0) {
					return $res['c'];
				}
			}
		}
	}
*/
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail
	WHERE
	  ( payment_status = 1 OR payment_status = 2 OR payment_status = 3 )
	  AND product_type_add = 2
	  AND bar_association_branch_id = '$bar_association_branch_id'
	  AND product_id = '$product_id'
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}

/**
 * 会場研修商品の完了数を取得
 */
function get_comp_number($bar_association_branch_id, $product_id, $training_kind_flg = ""){
	$objDbConnect = new DbConnect();

	// 旧データの確認
	if ($product_id <= 19233) {
		$product_id_old = $product_id - 10000;
		if ($training_kind_flg == "2" || $training_kind_flg == "3") {
			$sql = "
			SELECT
			  SUM(KENSHU_COUNT) AS c
			FROM
			  import_kenshu_count
			WHERE
			  KENSHU_ID = '$product_id_old'
			";
		}
		else {
			if ($bar_association_branch_id == 1) {
				$sql = "
				SELECT
				  SUM(KENSHU_COUNT) AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = '$product_id_old' AND
				  FROM_ID = 0
				";
			}
			else {
				$sql = "
				SELECT
				  KENSHU_COUNT AS c
				FROM
				  import_kenshu_count
				WHERE
				  ROOT_ID = 0 AND
				  KENSHU_ID = '$product_id_old' AND
				  bar_association_branch_id = '$bar_association_branch_id'
				";
			}
		}
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			if ($res['c'] > 0) {
				return $res['c'];
			}
		}
		else {
			$sql = "
			SELECT
			  KENSHU_COUNT AS c
			FROM
			  import_kenshu_count
			WHERE
			  ROOT_ID = '$product_id_old' AND
			  bar_association_branch_id = '$bar_association_branch_id'
			";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				if ($res['c'] > 0) {
					return $res['c'];
				}
			}
		}
	}
	
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail
	WHERE
	  ( payment_status = 1 OR payment_status = 2 OR payment_status = 3 )
	  AND product_type_add = 2
	  AND bar_association_branch_id = '$bar_association_branch_id'
	  AND product_id = '$product_id'
	  AND participation_flg = 1
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}

/**
 * 会場研修商品のパスポートによる申込者数を取得
 */
function get_entry_number_passport($bar_association_branch_id, $product_id){
	$objDbConnect = new DbConnect();
	
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail AS T1
	    LEFT JOIN
	  tbl_order AS T2
	      ON T1.order_id = T2.order_id
	WHERE
	  ( T1.payment_status = 1 OR T1.payment_status = 2 OR T1.payment_status = 3 )
	  AND T1.product_type_add = 2
	  AND T1.bar_association_branch_id = '$bar_association_branch_id'
	  AND T1.product_id = '$product_id'
	  AND T2.payment_type = 99
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}

/**
 * 会場研修商品の受講者数を取得
 */
function get_attend_number($bar_association_branch_id, $product_id){
	$objDbConnect = new DbConnect();
	
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail
	WHERE
	  ( payment_status = 1 OR payment_status = 2 OR payment_status = 3 )
	  AND product_type_add = 2
	  AND bar_association_branch_id = '$bar_association_branch_id'
	  AND product_id = '$product_id'
	  AND participation_flg = 1
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}

/**
 * 会場研修商品のパスポートによる受講者数を取得
 */
function get_attend_number_passport($bar_association_branch_id, $product_id){
	$objDbConnect = new DbConnect();
	
	$sql = "
	SELECT
	  COUNT(*) AS c
	FROM
	  tbl_order_detail AS T1
	    LEFT JOIN
	  tbl_order AS T2
	      ON T1.order_id = T2.order_id
	WHERE
	  ( T1.payment_status = 1 OR T1.payment_status = 2 OR T1.payment_status = 3 )
	  AND T1.product_type_add = 2
	  AND T1.bar_association_branch_id = '$bar_association_branch_id'
	  AND T1.product_id = '$product_id'
	  AND T1.participation_flg = 1
	  AND T2.payment_type = 99
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res['c'];
	}
	
	return false;
}

/**
 * 弁護士会情報の取得(弁護士会支部IDから)
 */
function get_bar_association_info($bar_association_branch_id){
	$objDbConnect = new DbConnect();
	
	$sql = "
	SELECT
	  T1.*,
	  T2.*
	FROM
	  mtb_bar_association AS T1
	    LEFT JOIN
	  mtb_bar_association_branch AS T2
	      ON T1.id = T2.bar_association_id
	WHERE
	  T2.bar_association_branch_id = '".$bar_association_branch_id."'
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		return $res;
	}
	
	return false;
}

/**
 * TOP画面の新着情報にwordpressの記事を投稿する(商品情報)
 * @param $product_id 商品ID
 * @param $status     ステータス
 *                    0 = お知らせ
 *                    1 = 更新情報
 */
function insert_wp_posted_article_product($product_id, $status=0){
	date_default_timezone_set('Asia/Tokyo');

	$objDbConnect = new DbConnect();
	
	if (!is_numeric($status)){
		$status = 0;
	}
	
	// 商品情報の取得
	$sql = "
	SELECT
	  T1.*,
	  T2.product_type_add,
	  T4.memo1,
	  T4.target
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_add AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  tbl_product_elearning AS T3
	      ON T1.product_id = T3.product_id
	    LEFT JOIN
	  tbl_product_live_training AS T4
	      ON T1.product_id = T4.product_id
	WHERE
	  T1.product_id = '".$product_id."'
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		// スラッグ名(新着情報：固定)
		$slug = 'news';
		// カテゴリID(新着情報：固定)
		$term_id = 20;
		// 投稿日時
		$posttime = date('Y-m-d H:i:s');
		// タイトル
		$title = '';
		if ($res['product_name'] != ''){
			$title.= '「' . $res['product_name'] . '」';
			if ($status == 1){
				$title.= 'を更新しました。';
			} else {
				$title.= 'を登録しました。';
			}
		}
		// 本文
		$content = '';
		if ($res['product_type_add'] == 1){
			$content = $res['memo'];
		} else if ($res['product_type_add'] == 2){
			$content = $res['memo1'];
		}
		
		//$post = array(
		//	'post_author' => 1, // 作成者のユーザーID。
		//	'post_category' => array(20), // カテゴリーを追加。
		//	'post_content' => $content, // 投稿の全文。
		//	'post_date' => $posttiome, // 投稿の作成日時。
		//	'post_name' => $slug.'-product', // 投稿スラッグ。(※商品自動投稿判断に使用している)
		//	'post_status' => 'publish', // 公開ステータス。
		//	'post_title' => $title, // 投稿のタイトル。
		//	'post_type' => 'post' ,// カスタム投稿タイプ名。
		//	'tax_input' => array() // 投稿タグ名。
		//);
		
		//$objDbConnect->tran_begin();
		
		// 記事情報の登録
		$sql = "
		INSERT INTO wp_posts
		(
		  post_author,
		  post_date,
		  post_content,
		  post_title,
		  post_status,
		  comment_status,
		  ping_status,
		  post_name,
		  post_modified,
		  post_parent,
		  post_type,
		  product_type_add,
		  status,
		  product_id,
		  target
		)
		VALUES
		(
		  '1',
		  '".$posttime."',
		  '".$content."',
		  '".$title."',
		  'publish',
		  'open',
		  'open',
		  '".$slug.'-product'."',
		  '".$posttime."',
		  '0',
		  'post',
		  '".$res['product_type_add']."',
		  '$status',
		  '".$res['product_id']."',
		  '".$res['target']."'
		)
		";
		$ret = $objDbConnect->execute($sql.$where);
		if ($ret){
			$object_id = mysqli_insert_id($objDbConnect->connect);
			
			// パーマリンクの登録
			$sql = "
			UPDATE wp_posts SET
			  guid = 'http://".$_SERVER['SERVER_NAME']."/archives/$object_id'
			WHERE
			  ID = '$object_id'
			";
			$ret = $objDbConnect->execute($sql.$where);
			if ($ret){
				// 登録した記事のカテゴリを登録
				$sql = "
				INSERT INTO wp_term_relationships
				(
				  object_id,
				  term_taxonomy_id
				)
				VALUES
				(
				  '$object_id',
				  '$term_id'
				)
				";
				$ret = $objDbConnect->execute($sql.$where);
				if ($ret){
					//$objDbConnect->commit();
					return true;
				}
			}
		}
		
		//$objDbConnect->rollback();
	}
	
	return false;
}

/**
 * 商品種別名称の取得
 */
function get_str_product_type_add($product_type_add){
	switch ($product_type_add){
		case 1:
			$str_product_type_add = 'eラーニング';
			break;
		case 2:
			$str_product_type_add = '会場研修';
			break;
		case 3:
			$str_product_type_add = '倫理代替措置研修';
			break;
		case 4:
			$str_product_type_add = 'パスポート';
			break;
		default:
			$str_product_type_add = '';
	}
	
	return $str_product_type_add;
}

/**
 * 弁護士会名の取得
 */
function get_bar_association_name($bar_association_id){
	$bar_association_name = '';
	
	$objDbConnect = new DbConnect();
	
	$sql = "SELECT name FROM mtb_bar_association WHERE id = '".$bar_association_id."'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$bar_association_name = $ret['name'];
		if ($bar_association_id != 1){
			$bar_association_name = $bar_association_name . '弁護士会';
		}
	}
	
	return $bar_association_name;
}

/**
 * Android、iPad、iPhoneの判断
 */
function is_sp () {
	$ret = false;
	$agent = $_SERVER['HTTP_USER_AGENT'];

	if(preg_match("/Android/i", $agent)){//android
		$ret = true;
	} else {
		$useragents = array(
			'iPhone', // Apple iPhone
			'iPad', // Apple iPad
			'iPod', // Apple iPod touch
			'Android', // 1.5+ Android
			'dream', // Pre 1.5 Android
			'CUPCAKE', // 1.5+ Android
			'blackberry9500', // Storm
			'blackberry9530', // Storm
			'blackberry9520', // Storm v2
			'blackberry9550', // Storm v2
			'blackberry9800', // Torch
			'webOS', // Palm Pre Experimental
			'incognito', // Other iPhone browser
			'webmate' // Other iPhone browser
		);
		$pattern = '/'.implode('|', $useragents).'/i';
		$ret = preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
	}
	return $ret;
}

/**
 * 弁護士の登録年数に対応したパスポート価格を取得する
 */
function get_passport_price($objDbConnect, $bar_association_year){
	$passport_price = 0;
	
	if ($bar_association_year > 5){
		$passport_target = 3;
	} else if ($bar_association_year>=3 && $bar_association_year<=5){
		$passport_target = 2;
	} else {
		$passport_target = 1;
	}
	
	$sql = "SELECT";
	$sql.= "  tbl_product.price";
	$sql.= " FROM";
	$sql.= "  tbl_product_passport";
	$sql.= "    INNER JOIN";
	$sql.= "  tbl_product";
	$sql.= "      ON tbl_product_passport.product_id = tbl_product.product_id";
	$sql.= " WHERE";
	$sql.= "  tbl_product_passport.passport_target LIKE '%|".$passport_target."|%'";
	$sql.= "  AND tbl_product.del_flg = 0";
	$sql.= " ORDER BY";
	$sql.= "  tbl_product_passport.product_id DESC";
	$sql.= " LIMIT 1";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$passport_price = $ret['price'];
	}
	
	return $passport_price;
}


/**
 * 問題一覧の取得
 */
function get_exam_list($bar_association_id){
	$objDbConnect = new DbConnect();
	
	$exam_list = array();
	
	$sql = 
	' SELECT SQL_CALC_FOUND_ROWS '.
	'        exam.exam_id          AS exam_id '.
	'       ,exam.exam_name        AS exam_name '.
	'       ,exam.exam_caption     AS exam_caption '.
	"       ,DATE_FORMAT(exam.exam_open  , '%Y%m%d%H%i%s') AS exam_open ".
	"       ,DATE_FORMAT(exam.exam_close , '%Y%m%d%H%i%s') AS exam_close ".
	'       ,exam.public_flag      AS public_flag '.
	'       ,exam.school_id        AS school_id '.
	'       ,exam.teacher_id       AS teacher_id '.
	'       ,exam.display_format   AS display_format '.
	'       ,exam.status           AS status '.
	'       ,exam.update_at        AS update_at '.
	'       ,teacher.teacher_name  AS teacher_name '.
	'   FROM exam LEFT JOIN teacher ON teacher.teacher_id = exam.teacher_id '.
	'  WHERE exam.school_id = 1 '.
	'    AND exam.status = 0 '.
	($bar_association_id > 1 ?
		" AND teacher.bar_association_id = '".$bar_association_id."' "
		: ''
	).
	' ORDER BY exam.exam_id DESC';
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		$exam_list = $res;
	}
	
	return $exam_list;
}
function get_exam2_list($bar_association_id){
	$objDbConnect = new DbConnect();
	
	$exam2_list = array();
	
	$sql = 
	' SELECT SQL_CALC_FOUND_ROWS '.
	'        exam2.exam2_id          AS exam2_id '.
	'       ,exam2.exam2_name        AS exam2_name '.
	'       ,exam2.exam2_caption     AS exam2_caption '.
	"       ,DATE_FORMAT(exam2.exam2_open  , '%Y%m%d%H%i%s') AS exam2_open ".
	"       ,DATE_FORMAT(exam2.exam2_close , '%Y%m%d%H%i%s') AS exam2_close ".
	'       ,exam2.public_flag      AS public_flag '.
	'       ,exam2.school_id        AS school_id '.
	'       ,exam2.teacher_id       AS teacher_id '.
	'       ,exam2.display_format   AS display_format '.
	'       ,exam2.status           AS status '.
	'       ,exam2.update_at        AS update_at '.
	'       ,teacher.teacher_name  AS teacher_name '.
	'   FROM exam2 LEFT JOIN teacher ON teacher.teacher_id = exam2.teacher_id '.
	'  WHERE exam2.school_id = 1 '.
	'    AND exam2.status = 0 '.
	($bar_association_id > 1 ?
		" AND teacher.bar_association_id = '".$bar_association_id."' "
		: ''
	).
	' ORDER BY exam2.exam2_id DESC';
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		$exam2_list = $res;
	}
	
	return $exam2_list;
}

/**
 * 設問付きeラーニング情報の取得
 */
function get_product_contents_info($product_id){
	$objDbConnect = new DbConnect();
	
	$product_contents_info = array();
	
	$sql = "SELECT * FROM rel_product_contents WHERE product_id = '$product_id'";
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		$product_contents_info = $res;
	}
	
	return $product_contents_info;
}

/**
 * すべての問題に解答済みかどうかのフラグを取得
 */
function get_all_exam_answered($exam_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	// 問題の設問数を取得
	$problem_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='$exam_id'";
	$rel_exam_problem = $objDbConnect->query_fetch($sql);
	if($rel_exam_problem){
		$problem_count = $rel_exam_problem['count'];
	}
	
	// 解答済みの件数を取得
	$answered_count = 0;
	$sql = "SELECT COUNT(exam_answer_id) AS count FROM exam_answer WHERE status=0 AND exam_id='$exam_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no'";
	$exam_answer = $objDbConnect->query_fetch($sql);
	if($exam_answer){
		$answered_count = $exam_answer['count'];
	}
	
	if($problem_count>0 && $answered_count>0 && ($problem_count<=$answered_count)){
		return true;
	} else {
		return false;
	}
}
function get_all_exam2_answered($exam2_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	// 問題の設問数を取得
	$problem_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM rel_exam2_problem WHERE exam2_id='$exam2_id'";
	$rel_exam2_problem = $objDbConnect->query_fetch($sql);
	if($rel_exam2_problem){
		$problem_count = $rel_exam2_problem['count'];
	}
	
	// 解答済みの件数を取得
	$answered_count = 0;
	$sql = "SELECT COUNT(exam2_answer_id) AS count FROM exam2_answer WHERE status=0 AND exam2_id='$exam2_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no'";
	$exam2_answer = $objDbConnect->query_fetch($sql);
	if($exam2_answer){
		$answered_count = $exam2_answer['count'];
	}
	
	if($problem_count>0 && $answered_count>0 && ($problem_count<=$answered_count)){
		return true;
	} else {
		return false;
	}
}

/**
 * すべての問題に解答済みかどうかのフラグを取得
 */
function get_all_exam_answered_retry($exam_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	// すべての問題に解答しているかどうかチェック
	$problem_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM rel_exam_problem WHERE exam_id='$exam_id'";
	$rel_exam_problem = $objDbConnect->query_fetch($sql);
	if($rel_exam_problem){
		$problem_count = $rel_exam_problem['count'];
	}
	
	// 解答済みの件数を取得
	$answered_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM exam_answer_retry WHERE exam_id='$exam_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no'";
	$exam_answer = $objDbConnect->query_fetch($sql);
	if($exam_answer){
		$answered_count = $exam_answer['count'];
	}
	
	if($problem_count>0 && $answered_count>0 && ($problem_count<=$answered_count)){
		return true;
	} else {
		return false;
	}
}
function get_all_exam2_answered_retry($exam2_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	// すべての問題に解答しているかどうかチェック
	$problem_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM rel_exam2_problem WHERE exam2_id='$exam2_id'";
	$rel_exam2_problem = $objDbConnect->query_fetch($sql);
	if($rel_exam2_problem){
		$problem_count = $rel_exam2_problem['count'];
	}
	
	// 解答済みの件数を取得
	$answered_count = 0;
	$sql = "SELECT COUNT(*) AS count FROM exam2_answer_retry WHERE exam2_id='$exam2_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no'";
	$exam2_answer = $objDbConnect->query_fetch($sql);
	if($exam2_answer){
		$answered_count = $exam2_answer['count'];
	}
	
	if($problem_count>0 && $answered_count>0 && ($problem_count<=$answered_count)){
		return true;
	} else {
		return false;
	}
}

/**
 * 問題の取得
 */
function get_exam($exam_id, $user_id){
	date_default_timezone_set('Asia/Tokyo');

	$objDbConnect = new DbConnect();
	
	$lives_exam = array();

	// 全ての問題の取得（講座の公開期間が過ぎても）
	$sql = " 
SELECT 
 C.cource_id, 
 C.cource_name, 
 C.cource_open, 
 C.cource_close, 
 EL.exam_id, 
 E.*, 
 DATE_FORMAT(E.exam_open, '%Y/%m/%d') AS exam_open_str_d, 
 DATE_FORMAT(E.exam_open, '%w') AS exam_open_str_w, 
 DATE_FORMAT(E.exam_open, '%H:%i:%s') AS exam_open_str_t, 
 DATE_FORMAT(E.exam_close, '%Y/%m/%d') AS exam_close_str_d, 
 DATE_FORMAT(E.exam_close, '%w') AS exam_close_str_w, 
 DATE_FORMAT(E.exam_close, '%H:%i:%s') AS exam_close_str_t, 
 E.resubmit_flag AS resubmit_flag 
FROM 
 (rel_exam_lecture EL INNER JOIN cource C ON EL.cource_id = C.cource_id)
 INNER JOIN exam E ON EL.exam_id = E.exam_id 
WHERE 
 E.exam_id='$exam_id' 
 AND E.status = 0 
 AND E.public_flag = 0 
 AND C.status = 0 
";
	$result = $objDbConnect->query_fetch_arr($sql);
	if($result){
		$now = date('Y-m-d H:i:s');
		$weekday = array( "日", "月", "火", "水", "木", "金", "土" );

		$lives_exam_tmp = array();
		foreach($result as $arr) {
			// 問題IDをユニークにする
			if (in_array($arr['exam_id'], $lives_exam_tmp)) { continue; }
			$lives_exam_tmp[] = $arr['exam_id'];

			// 受講者チェックがある時
			$sql2 = "SELECT * FROM rel_exam_student WHERE exam_id = '".$arr['exam_id']."'";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				if (count($result2) > 0) {
					$skip_flg = true;
					foreach ($result2 as $k2 => $v2) {
						// 受講者チェックが有効かどうか
						$sql3 = "SELECT * FROM rel_exam_student WHERE exam_id = '".$arr['exam_id']."' AND student_id = '$user_id'";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$skip_flg = false;
								break;
							}
						}
					}

					// スキップする
					if ($skip_flg) {
						continue;
					}
				}
			}

			// 曜日の変換
			if (isset($weekday[$arr['exam_open_str_w']])) {
				$arr['exam_open_str'] = $arr['exam_open_str_d']."(".$weekday[$arr['exam_open_str_w']].") ".$arr['exam_open_str_t'];
			}
			else {
				$arr['exam_open_str'] = $arr['exam_open_str_d']." ".$arr['exam_open_str_t'];
			}
			if (isset($weekday[$arr['exam_close_str_w']])) {
				$arr['exam_close_str'] = $arr['exam_close_str_d']."(".$weekday[$arr['exam_close_str_w']].") ".$arr['exam_close_str_t'];
			}
			else {
				$arr['exam_close_str'] = $arr['exam_close_str_d']." ".$arr['exam_close_str_t'];
			}

			// 設問の取得
			$arr['problem'] = array();
			$sql2 = "
			SELECT *
			FROM rel_exam_problem REP INNER JOIN exam_problem EP
				ON REP.exam_problem_id = EP.exam_problem_id
			WHERE REP.exam_id = '".$arr['exam_id']."'
				AND EP.status = 0
			ORDER BY exam_no ASC 
			";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				$answer_count = 0;
				if (count($result2) > 0) {
					$x = 0;
					foreach($result2 as $arr2) {
						$arr['problem'][$x] = $arr2;
						$arr['problem'][$x]['answer_contents_select'] = array();
						$arr_answer_contents = array();
						if ($arr['problem'][$x]) {
							$arr['problem'][$x]['answer_contents_select'] = @(array) json_decode($arr2['answer_contents'], true);
							for($z=0;$z<count($arr['problem'][$x]['answer_contents_select']['answer_contents']);$z++){
								$arr_answer_contents[$z] = @(array)$arr['problem'][$x]['answer_contents_select']['answer_contents'][$z];
							}
							$arr['problem'][$x]['answer_contents_select']['answer_contents'] = $arr_answer_contents;
						}

						// 回答結果の取得
						$arr['problem'][$x]['student_answer'] = array();
						$sql3 = "
						SELECT *
						FROM exam_answer EA
						WHERE EA.student_id = '$user_id'
						  AND EA.exam_id = '".$arr['exam_id']."'
						  AND EA.exam_problem_id = '".$arr['problem'][$x]['exam_problem_id']."'
						  AND EA.status = 0
						ORDER BY EA.exam_answer_no DESC LIMIT 0, 1
						";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$answer_count += 1;
								$arr3 = $result3;
								$arr['problem'][$x]['student_answer'] = $arr3[0];
							}
						}
						// 設問.講師の取得
						$arr['problem'][$x]['teacher'] = array();
						$sql3 = "
						SELECT *
						FROM teacher
						WHERE teacher_id = '".$arr['problem'][$x]['teacher_id']."'
						  AND status = 0
						LIMIT 0, 1
						";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$arr3 = $result3;
								$arr['problem'][$x]['teacher'] = $arr3[0];
							}
						}
						$x += 1;
					}
				}
			}

			// teacher情報の取得
			$arr["teacher"] = array();
			$sql2 = "
			SELECT *
			FROM teacher
			WHERE teacher_id = '".$arr['teacher_id']."'
			  AND status = 0
			";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				if (count($result2) > 0) {
					$arr2 = $result2;
					$arr["teacher"] = $arr2[0];
				}
			}

			$lives_exam = $arr;
		}
	}

	return $lives_exam;
}
function get_exam2($exam2_id, $user_id){
	$objDbConnect = new DbConnect();
	
	$lives_exam2 = array();

	// 全ての問題の取得（講座の公開期間が過ぎても）
	$sql = " 
SELECT 
 C.cource_id, 
 C.cource_name, 
 C.cource_open, 
 C.cource_close, 
 EL.exam2_id, 
 E.*, 
 DATE_FORMAT(E.exam2_open, '%Y/%m/%d') AS exam2_open_str_d, 
 DATE_FORMAT(E.exam2_open, '%w') AS exam2_open_str_w, 
 DATE_FORMAT(E.exam2_open, '%H:%i:%s') AS exam2_open_str_t, 
 DATE_FORMAT(E.exam2_close, '%Y/%m/%d') AS exam2_close_str_d, 
 DATE_FORMAT(E.exam2_close, '%w') AS exam2_close_str_w, 
 DATE_FORMAT(E.exam2_close, '%H:%i:%s') AS exam2_close_str_t, 
 E.resubmit_flag AS resubmit_flag 
FROM 
 (rel_exam2_lecture EL INNER JOIN cource C ON EL.cource_id = C.cource_id)
 INNER JOIN exam2 E ON EL.exam2_id = E.exam2_id 
WHERE 
 E.exam2_id='$exam2_id' 
 AND E.status = 0 
 AND E.public_flag = 0 
 AND C.status = 0 
";
	$result = $objDbConnect->query_fetch_arr($sql);
	if($result){
		$now = date('Y-m-d H:i:s');
		$weekday = array( "日", "月", "火", "水", "木", "金", "土" );

		$lives_exam2_tmp = array();
		foreach($result as $arr) {
			// 問題IDをユニークにする
			if (in_array($arr['exam2_id'], $lives_exam2_tmp)) { continue; }
			$lives_exam2_tmp[] = $arr['exam2_id'];

			// 受講者チェックがある時
			$sql2 = "SELECT * FROM rel_exam2_student WHERE exam2_id = '".$arr['exam2_id']."'";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				if (count($result2) > 0) {
					$skip_flg = true;
					foreach ($result2 as $k2 => $v2) {
						// 受講者チェックが有効かどうか
						$sql3 = "SELECT * FROM rel_exam2_student WHERE exam2_id = '".$arr['exam2_id']."' AND student_id = '$user_id'";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$skip_flg = false;
								break;
							}
						}
					}

					// スキップする
					if ($skip_flg) {
						continue;
					}
				}
			}

			// 曜日の変換
			if (isset($weekday[$arr['exam2_open_str_w']])) {
				$arr['exam2_open_str'] = $arr['exam2_open_str_d']."(".$weekday[$arr['exam2_open_str_w']].") ".$arr['exam2_open_str_t'];
			}
			else {
				$arr['exam2_open_str'] = $arr['exam2_open_str_d']." ".$arr['exam2_open_str_t'];
			}
			if (isset($weekday[$arr['exam2_close_str_w']])) {
				$arr['exam2_close_str'] = $arr['exam2_close_str_d']."(".$weekday[$arr['exam2_close_str_w']].") ".$arr['exam2_close_str_t'];
			}
			else {
				$arr['exam2_close_str'] = $arr['exam2_close_str_d']." ".$arr['exam2_close_str_t'];
			}

			// 設問の取得
			$arr['problem'] = array();
			$sql2 = "
			SELECT *
			FROM rel_exam2_problem REP INNER JOIN exam2_problem EP
				ON REP.exam2_problem_id = EP.exam2_problem_id
			WHERE REP.exam2_id = '".$arr['exam2_id']."'
				AND EP.status = 0
			ORDER BY exam2_no ASC 
			";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				$answer_count = 0;
				if (count($result2) > 0) {
					$x = 0;
					foreach($result2 as $arr2) {
						$arr['problem'][$x] = $arr2;
						$arr['problem'][$x]['answer_contents_select'] = array();
						$arr_answer_contents = array();
						if ($arr['problem'][$x]) {
							$arr['problem'][$x]['answer_contents_select'] = @(array) json_decode($arr2['answer_contents'], true);
							for($z=0;$z<count($arr['problem'][$x]['answer_contents_select']['answer_contents']);$z++){
								$arr_answer_contents[$z] = @(array)$arr['problem'][$x]['answer_contents_select']['answer_contents'][$z];
							}
							$arr['problem'][$x]['answer_contents_select']['answer_contents'] = $arr_answer_contents;
						}

						// 回答結果の取得
						$arr['problem'][$x]['student_answer'] = array();
						$sql3 = "
						SELECT *
						FROM exam2_answer EA
						WHERE EA.student_id = '$user_id'
						  AND EA.exam2_id = '".$arr['exam2_id']."'
						  AND EA.exam2_problem_id = '".$arr['problem'][$x]['exam2_problem_id']."'
						  AND EA.status = 0
						ORDER BY EA.exam2_answer_no DESC LIMIT 0, 1
						";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$answer_count += 1;
								$arr3 = $result3;
								$arr['problem'][$x]['student_answer'] = $arr3[0];
							}
						}
						// 設問.講師の取得
						$arr['problem'][$x]['teacher'] = array();
						$sql3 = "
						SELECT *
						FROM teacher
						WHERE teacher_id = '".$arr['problem'][$x]['teacher_id']."'
						  AND status = 0
						LIMIT 0, 1
						";
						$result3 = $objDbConnect->query_fetch_arr($sql3);
						if($result3){
							if (count($result3) > 0) {
								$arr3 = $result3;
								$arr['problem'][$x]['teacher'] = $arr3[0];
							}
						}
						$x += 1;
					}
				}
			}

			// teacher情報の取得
			$arr["teacher"] = array();
			$sql2 = "
			SELECT *
			FROM teacher
			WHERE teacher_id = '".$arr['teacher_id']."'
			  AND status = 0
			";
			$result2 = $objDbConnect->query_fetch_arr($sql2);
			if($result2){
				if (count($result2) > 0) {
					$arr2 = $result2;
					$arr["teacher"] = $arr2[0];
				}
			}

			$lives_exam2 = $arr;
		}
	}

	return $lives_exam2;
}

/**
 * 問題の解答が判定基準を満たしているかどうか
 */
function get_exam_answer_passing_flg($exam_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	$passing_flg = 0;
	
	$sql = "SELECT passing_flg FROM exam_answer WHERE exam_id='$exam_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no' LIMIT 1";
	$exam_answer = $objDbConnect->query_fetch($sql);
	if($exam_answer){
		$passing_flg = $exam_answer['passing_flg'];
	}
	
	return $passing_flg;
}
function get_exam2_answer_passing_flg($exam2_id, $student_id, $product_id){
	$objDbConnect = new DbConnect();
	
	$passing_flg = 0;
	
	$sql = "SELECT passing_flg FROM exam2_answer WHERE exam2_id='$exam2_id' AND student_id='$student_id' AND product_id='$product_id' LIMIT 1";
	$exam2_answer = $objDbConnect->query_fetch($sql);
	if($exam2_answer){
		$passing_flg = $exam2_answer['passing_flg'];
	}
	
	return $passing_flg;
}

/**
 * 解答済みデータの取得
 */
function get_exam_answerd($exam_id, $student_id, $product_id, $contents_no){
	$objDbConnect = new DbConnect();
	
	$exam_answerd = array();
	
	$sql = "SELECT * FROM exam_answer WHERE status=0 AND exam_id='$exam_id' AND student_id='$student_id' AND product_id='$product_id' AND contents_no='$contents_no'";
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		$exam_answerd = $res;
	}
	
	return $exam_answerd;
}
function get_exam2_answerd($exam2_id, $student_id, $product_id){
	$objDbConnect = new DbConnect();
	$exam2_answerd = array();
	$sql = "SELECT * FROM exam2_answer WHERE status=0 AND exam2_id='$exam2_id' AND student_id='$student_id' AND product_id='$product_id'";
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		$exam2_answerd = $res;
	}
	return $exam2_answerd;
}

/**
 * 指定半角文字を全角に変換
 */
function convert_symbole($value){
	$value = str_replace("\&" , "＆"  , $value);
	$value = str_replace("\"" , "”"  , $value);
	$value = str_replace("'"  , "‘"  , $value);
	$value = str_replace("<"  , "＜"  , $value);
	$value = str_replace(">"  , "＞"  , $value);
	$value = str_replace("("  , "（"  , $value);
	$value = str_replace(")"  , "）"  , $value);
	
	return $value;
}

function array_equal_set( $a, $b ){
	$diff_a_to_b = array_diff($a, $b);
	$diff_b_to_a = array_diff($b, $a);
	return empty($diff_a_to_b) && empty($diff_b_to_a);
}

/**
 * シングルサインオンに使用するtransactionIdを生成
 */
function create_sso_transaction_id(){
	//return sha1(uniqid(mt_rand(), true));
	return date("YmdHis").mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
}
?>
