<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//=======================================================================//
//= 数字をKB、MB、GBなどの補助単位をつけた形に変換して出力します。
//= 1024以下の数を指定した場合には単位をつけず、それ以上のときは適切な単位を判断して付加し「123M」「456G」などのようになります。
//= 「キロ、メガ、ギガ、テラ」に対応しています。
//= データ量B(バイト)の場合、正確にはKiB,MiBと書くべきですが、表示するときはKB,MBと書くことの方が多いためこのようにしています。
//=
//= 入力する数値は12345678、小数点以下2桁まで単位をつけて出力したいとき
//= print ConvertUnit(12345678, 2) . "B";
//=======================================================================//
function ConvertUnit($int, $digit){
	if($int >= pow(1024, 4)){
		$int_t = round($int / pow(1024, 4), $digit);
		$int_t .= "T";
	}elseif($int >= pow(1024, 3)){
		$int_t = round($int / pow(1024, 3), $digit);
		$int_t .= "G";
	}elseif($int >= pow(1024, 2)){
		$int_t = round($int / pow(1024, 2), $digit);
		$int_t .= "M";
	}elseif($int >= 1024){
		$int_t = round($int / 1024, $digit);
		$int_t .= "K";
	}elseif($int < 1024){
		$int_t = round($int, $digit);
	}
	return $int_t;
}

//=======================================================================//
//= 秒数から○時間○分○秒を求める
//=======================================================================//
function Sec2Disp($sec, $param=array(), $zerofill = false){
	$param = array_merge(array(
		'dd' => "日と",
		'hh' => "時間",
		'mm' => "分",
		'ss' => "秒",
	), $param);

//	if($sec == 0) return "0{$param['ss']}";
	$d = 60 * 60 * 24;
	$h = 60 * 60;
	$out = "";
	$sec = round($sec);

	if($sec >= $d && $param['dd']){
		$out .= sprintf("%02d", floor($sec / $d)) . $param['dd'];
		$sec = $sec % $d;
	}
	else if($zerofill && $param['dd']){
		$out .= '0' . $param['dd'];
	}

	if($sec >= $h && $param['hh']){
		$out .= sprintf("%02d", floor($sec / $h)) . $param['hh'];
		$sec = $sec % $h;
	}
	else if($zerofill && $param['hh']){
		$out .= '00' . $param['hh'];
	}

	if($sec >= 60 && $param['mm']){
		$out .= sprintf("%02d", floor($sec / 60)) . $param['mm'];
		$sec = $sec % 60;
	}
	else if($zerofill && $param['mm']){
		$out .= '00' . $param['mm'];
	}

	if($sec && $param['ss']){
		$out .= sprintf("%02d", $sec) . $param['ss'];
	}
	else if($zerofill && $param['ss']){
		$out .= '00' . $param['ss'];
	}

	return $out;
}
?>
