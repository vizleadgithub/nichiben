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
			
			// 重複タグを除外
			if( strpos($return_tags, $_tag) === false ){
				if(strlen($return_tags) == 0){
					$return_tags = $_tag;
				}else{
					$return_tags = $return_tags.','.$_tag;
				}
			}
		}
	}
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
?>
