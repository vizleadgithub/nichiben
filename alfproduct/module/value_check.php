<?php

//////////////////////////////////////////////////////////////
// cmCheckInput
// return 0: no_error exp 0: error
//////////////////////////////////////////////////////////////
function cmCheckInput($input, $n, $args=NULL)
{
	switch ($n)
	{
	//空欄
	case "CK_KARA":
		if (trim($input) == '') return 1;
		break;
	//空欄
	case "CK_KURAN":
//		if (!trim($input)) return 1;
//		if (trim($input) == '') return 1;
		//0は空欄ではない
		//全角スペースも取り除く
		if (trim(mb_convert_kana($input, "s", "UTF-8")) == '') return 1;
		
		break;
	//NULL
	case "CK_NULL":
		if ($input === NULL) return 1;
		break;
	//NONE
	case "CK_NONE":
		if (!$input) return 1;
		break;
	//半角数字
	case "CK_NUM":
		if (!preg_match("/^\d+$/", $input)) return 1;
		//負数対応
		//if (!preg_match("/^-?\d+$/", $input)) return 1;
		break;
	//半角英数字(_abc_123_)
	case "CK_EISUJI":
		if (!preg_match("/^\w+$/", $input)) return 1;
		break;
	//アカウント名
	case "CK_ACCOUNT_NAME":
		if (!preg_match("/^\w([\w\-\~\.])*\w$/", $input)) return 1;
		break;
	//メールアドレス
	case "CK_EMAIL":
		if (!preg_match("/^\w([\w\-\~\.])*@\w([\w\-\~])*(\.([\w\-\~])+)+$/", $input)) return 1;
		break;
	//日付
	case "CK_DATE":
		if (!preg_match("/^\d{8}$/", $input)) return 1;
		//month, day, year
		if (!checkdate(substr($input,4,2), substr($input,6,2), substr($input,0,4))) return 1;
		break;
	//全角文字
	case "CK_ZENKAKU":
		if (trim($input)!=""){
			if (mb_convert_kana($input, "ASKV", "UTF-8") != $input) return 1;
			break;
		}
		break;
	//全角カタカナ
	case "CK_ZEN_KATAKANA":
		if (trim($input)!=""){
			mb_regex_encoding('UTF-8');
			if (!mb_ereg("^[ア-ンー]+$", $input)) return 1;
			break;
		}
		break;
	//全角ひらがな
	case "CK_ZEN_HIRAGANA":
		mb_regex_encoding('UTF-8');
		if (!mb_ereg("^[あ-ん]+$", $input)) return 1;
		break;
	//半角文字
	case "CK_HANKAKU":
		if (mb_convert_kana($input, "askh", "UTF-8") != $input) return 1;
		break;

//$args使用
	//半角数字と桁
	case "CK_NUM_LENGTH":
		if (count($args) < 2) return 1;
		if (!preg_match("/^\d+$/", $input)) return 1;
		if (strlen($input) < $args[0] || strlen($input) > $args[1]) return 1;
		break;
	}
	return 0;
}

function efw_strlen($str_temp){
	return strlen(bin2hex(mb_convert_encoding($str_temp, OUTPUT_CODE, INTERNAL_CODE))) / 2;
}

/**
 * 入力フォーム値％の範囲が0～100かどうかのチェック
 */
function renge_check($str_num){
	if (ctype_digit($str_num)){
		$num = intval($str_num);
		if ($num >= 0 && $num <= 100){
			return 0;
		}
	}
	return 1;
}
?>