<?php
include(dirname(__FILE__) ."./../../module/module.php");

if (isset($_SESSION['user']['token_id']) && isset($_SESSION['user']['login_result'])){
	$objDbConnect = new DbConnect();
	
	$sql = "SELECT";
	$sql.= "  T1.student_id,";
	$sql.= "  T1.student_name,";
	$sql.= "  T1.bar_association_id,";
	$sql.= "  T1.exp_date_passport,";
	$sql.= "  DATE_SUB(T1.exp_date_passport,INTERVAL 1 MONTH) AS prev_exp_date_passport,";
	$sql.= "  T1.regist_date,";
	$sql.= "  T1.presence_passport,";
	$sql.= "  T1.sub_auth_ethic_training,";
	$sql.= "  T1.lawyer_number,";
	$sql.= "  T2.passport_pop_flg";
	$sql.= " FROM";
	$sql.= "  student AS T1";
	$sql.= "    LEFT JOIN";
	$sql.= "  student_add AS T2";
	$sql.= "      ON T1.student_id = T2.student_id";
	$sql.= " WHERE";
	$sql.= "  T1.status='0'";
	$sql.= "  AND T1.student_email='".$_SESSION['user']['login_result']->mail."'";
	$sql.= "  AND T1.lawyer_number='".$_SESSION['user']['login_result']->lawyer_number."'";
	$ret = $objDbConnect->query_fetch($sql);
	if ($ret){
		$_SESSION['user']['id'] = $ret['student_id'];
		$_SESSION['user']['name'] = $ret['student_name'];
		$_SESSION['user']['bar_association_id'] = $ret['bar_association_id'];
		$_SESSION['user']['exp_date_passport'] = $ret['exp_date_passport'];
		$_SESSION['user']['presence_passport'] = $ret['presence_passport'];
		$_SESSION['user']['sub_auth_ethic_training'] = $ret['sub_auth_ethic_training'];
		$_SESSION['user']['passport_pop_flg'] = $ret['passport_pop_flg'];
		$_SESSION['user']['lawyer_number'] = $ret['lawyer_number'];
		$_SESSION['user']['bar_association_duty_year'] = _get_bar_association_duty_year($ret['regist_date']);
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
		
		//setcookie('login_email', $email, 0, '/');
		
		// パスポート価格の書き換え
		$passport_price = get_passport_price($objDbConnect, $_SESSION['user']['year']);
		$sql = "UPDATE student SET target_passport = '".$passport_price."円' WHERE student_id = '".$_SESSION['user']['id']."'";
		$objDbConnect->execute($sql);
		
		$objDbConnect->close();
		
		unset($_SESSION['user']['login_result']);
		
		//header("Location: /");
		//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
		$temp_url = trim($_SERVER['HTTP_REFERER']);
		//$arr_temp_url = parse_url($temp_url);
		//if( $arr_temp_url["host"] == "kenshu.nichibenren.or.jp" ){
		//	header("Location: ".$temp_url);
		//} else {
		//	header("Location: /");
		//} 
		$burl = isset($_GET["burl"]) ? trim($_GET["burl"]) : "";
                if( $burl !== "" && substr($burl, 0, 1) === '/' && substr($burl, 0, 2) !== '//' ){
			header("Location: ".$burl);
                } else {
			header("Location: /");
		}
		//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
		exit;
	}
	
	$objDbConnect->close();
}
echo 'Bad Request';
header('HTTP', true, 400);
exit;


/**
 * 倫理義務年の取得
 * @param  $regist_date 弁護士登録日
 * @return $bar_association_duty_year 倫理義務年
 */
function _get_bar_association_duty_year($regist_date){
	$bar_association_duty_year = false;
	
	// 1.弁護士登録日から、年月を取得
	$arr_regist_date = explode('-', $regist_date);
	$regist_year  = (int) $arr_regist_date[0];
	$regist_month = (int) ltrim($arr_regist_date[1], '0');
	
	// 2.弁護士登録月が
	// 1～3月の場合、取得した登録年度から1年引く
	// 4～12月の場合、取得した登録年度のまま
	if ($regist_month==1 || $regist_month==2 || $regist_month==3){
		$regist_year -= 1;
	}
	
	// 3.今現在の日付から、年月を取得
	$arr_now_date = explode('-', date('Y-m-d'));
	$now_year  = (int) $arr_now_date[0];
	$now_month = (int) ltrim($arr_now_date[1], '0');
	
	// 4.今現在の月が
	// 1～3月の場合、取得した年度から1年引く
	// 4～12月の場合、取得した年度のまま
	if ($now_month==1 || $now_month==2 || $now_month==3){
		$now_year -= 1;
	}
	
	// 5.登録年度 = 今年度であるか比較する
	// 同一年度の場合、今年度が義務年度と判断
	if ($regist_year == $now_year){
		$bar_association_duty_year = $now_year;
		
	// 6.登録年度を5月1日～4月30日の範囲として再設定するため、以下の方法で再計算する
	// 弁護士登録月が
	// 1～4月の場合、取得した登録年度から1年引く
	// 5～12月の場合、取得した登録年度のまま
	} else {
		$regist_year = (int) $arr_regist_date[0]; // 登録年度の再取得
		
		if ($regist_month==1 || $regist_month==2 || $regist_month==3 || $regist_month==4){
			$regist_year -= 1;
		}
		
		// 7.今年度 - 登録年度を計算した値が、
		// 4年以下の場合、       登録年度 + 4
		// 5～6年の範囲の場合、  登録年度 + 6
		// 7～11年の範囲の場合、 登録年度 + 11
		// 12～16年の範囲の場合、登録年度 + 16
		// …～… (以降5年ごと)
		$cal_year = $now_year - $regist_year;
		if ($cal_year <= 4){
			$bar_association_duty_year = $regist_year + 4;
		} else if ($cal_year >= 5 && $cal_year <= 6){
			$bar_association_duty_year = $regist_year + 6;
		} else if ($cal_year >= 7 && $cal_year <= 11){
			$bar_association_duty_year = $regist_year + 11;
		} else {
			$from_year = 7;
			$to_year   = 11;
			for ($i=0; $i<100; $i++){
				$from_year += 5;
				$to_year   += 5;
				if ($cal_year >= $from_year && $cal_year <= $to_year){
					$bar_association_duty_year = $regist_year + $to_year;
					break;
				}
			}
		}
	}
	
	if ($bar_association_duty_year){
		$bar_association_duty_year.= '年';
	}
	
	return $bar_association_duty_year;
}
?>
