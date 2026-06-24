<?
//=======================================================================//
// [2012/10/03]ビデオタグ・図書室タグ・お知らせタグの内容精査（空白文字除外、重複除外）
//=======================================================================//
function tags_inspection($tags){
	$return_tags = '';
	
	$tags = str_replace('\\', '￥', $tags);	// \マークはstr_replaceで変換
	$tags = preg_replace('/\*/u', '＊', $tags);
	$tags = preg_replace('/\+/u', '＋', $tags);
	$tags = preg_replace('/\./u', '．', $tags);
	$tags = preg_replace('/\?/u', '？', $tags);

	$tags = preg_replace('/\{/u', '｛', $tags);
	$tags = preg_replace('/\}/u', '｝', $tags);
	$tags = preg_replace('/\(/u', '（', $tags);
	$tags = preg_replace('/\)/u', '）', $tags);
	$tags = preg_replace('/\[/u', '［', $tags);
	$tags = preg_replace('/\]/u', '］', $tags);

	$tags = preg_replace('/\^/u', '＾', $tags);
	$tags = preg_replace('/\$/u', '＄', $tags);
	$tags = preg_replace('/\-/u', '－', $tags);
	$tags = preg_replace('/\|/u', '｜', $tags);
	$tags = preg_replace('/\//u', '／', $tags);

	$tags = trim_over($tags);								// 前後空白削除
	$tags = preg_replace('/[、，､,]/u', ',', $tags);		// 全角読点・半角読点・全角カンマの変換
	
	// 文字列長ゼロなら空文字を返す
	if(strlen($tags) > 0){
		// カンマ区切りのループ
		foreach(explode(",", $tags) as $_tag){
			// 文字列長ゼロは取得しない
			$_tag = trim_over($_tag);
			if(strlen($_tag) == 0) continue;
			
			// 重複タグを除外（各単語を半角カンマで囲む形にして、重複有無確認）
			if( strpos($return_tags, ','.$_tag.',') === false ){
				if(strlen($return_tags) == 0){
					$return_tags = ','.$_tag.',';
				}else{
					$return_tags = $return_tags.$_tag.',';
				}
			}
		}
	}
	
	// 文字列前後の「,」を削除
	$return_tags = ltrim($return_tags, ",");
	$return_tags = rtrim($return_tags, ",");
	
	return $return_tags;
}
//=======================================================================//
// [2012/10/03]前後にある半角・全角空白を取り除く
//=======================================================================//
function trim_over($str){
	$str = preg_replace('/^[ 　]+/u', '', $str);		// 前空白削除
	$str = preg_replace('/[ 　]+$/u', '', $str);		// 後空白削除
	return $str;
}
//=======================================================================//
// 配列型対応、値チェック
//   戻り値：true 例
//     【0】   【'1'】   【array()】   【array(1)】
//     【array('0')】
//     【array(0,'91', 101,'1000')】
//     【array( array('91', 101,'1000'), array('a'=>'101', 'b'=>'201', 'c'=>'202') )】
//
//   戻り値：false 例
//     【】   【''】   【'a'】   【array('')】   【array(-1)】   【array(0, 1, 'a')】
//     【array('a'=>0, 'b'=>1, 'c'=>'z1')】
//
//   動作確認サンプル
//     $this->load->helper('string_inspection_helper');
//     print "【0】-> ".((check_array_data_num( 0 ))?"True":"False")."<br/>";
//     print "【'1'】-> ".((check_array_data_num( '1' ))?"True":"False")."<br/>";
//     print "【array()】-> ".((check_array_data_num( array() ))?"True":"False")."<br/>";
//     print "【array(1)】-> ".((check_array_data_num( array(1) ))?"True":"False")."<br/>";
//     print "【array('0')】-> ".((check_array_data_num( array('0') ))?"True":"False")."<br/>";
//     print "【array(0,'91', 101,'1000')】-> ".((check_array_data_num( array(0,'91', 101,'1000') ))?"True":"False")."<br/>";
//     print "【array( array('91', 101,'1000'), array('a'=>'101', 'b'=>'201', 'c'=>'202') )】-> ".((check_array_data_num(array(array('91',101,'1000'),array('a'=>'101','b'=>'201','c'=>'202'))))?"True":"False")."<br/>";
//     print "<hr/>";
//     print "【】-> ".((check_array_data_num())?"True":"False")."<br/>";
//     print "【''】-> ".((check_array_data_num( '' ))?"True":"False")."<br/>";
//     print "【'a'】-> ".((check_array_data_num( 'a' ))?"True":"False")."<br/>";
//     print "【array('')】-> ".((check_array_data_num( array('') ))?"True":"False")."<br/>";
//     print "【array(-1)】-> ".((check_array_data_num( array(-1) ))?"True":"False")."<br/>";
//     print "【array(0, 1, 'a')】-> ".((check_array_data_num( array(0, 1, 'a') ))?"True":"False")."<br/>";
//     print "【array('a'=>0, 'b'=>1, 'c'=>'z1')】-> ".((check_array_data_num( array('a'=>0, 'b'=>1, 'c'=>'z1') ))?"True":"False")."<br/>";
//
//=======================================================================//
function check_array_data_num($param = 'NULL'){
	// 戻り値初期化
	$result_data = true;
	
	// 引数値なしはエラー（0及び'0'はOKとする）
	if($param != 0){
		if($param == 'NULL') {
			$result_data = false;
			return $result_data;
		}
	}
	
	// 引数が配列でない場合、配列型に変換
	$check_param = NULL;
	if(is_array($param)){
		$check_param = $param;
	}else{
		$check_param = array($param);
	}

	// 配列内値の確認
	foreach($check_param as $check_data) {
		if( !_check_data_num($check_data) ){
			$result_data = false;
			break;
		}
	}
	// 戻り値返却
	return $result_data;
}
function _check_data_num($param){
	// 戻り値初期化
	$check_result_data = true;

	// 配列であればループ処理
	if(is_array($param)){
		// 配列ループ
		foreach($param as $value){
			if( is_array($value) ){
				// 配列ならば再帰呼び出し
				$check_result_data = _check_data_num($value);
			}else{
				// 配列でなければ「数値」確認
				if( !is_numeric($value) ){
					$check_result_data = false;
					break;
				}
				// 自然数（ゼロを含む）確認  // 【^[0-9]+$】
				if( !preg_match("/^\d+$/", $value) ){
					$check_result_data = false;
					break;
				}
			}
		}
	}else{
		// 配列でなければ「数値」確認
		// 自然数（ゼロを含む）確認  // 【^[0-9]+$】
		if( (!is_numeric($param)) || (!preg_match("/^\d+$/", $param)) ){
			$check_result_data = false;
		}
	}
	
	// 戻り値返却
	return $check_result_data;
}
?>
