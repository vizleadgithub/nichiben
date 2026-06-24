<?
//=======================================================================//
// fputcsv のファイルポインタ未使用版（CSV形式にした値を返す）
// [参考サイト]http://spelunker2.wordpress.com/2012/10/23/【php】fputcsvとmb_str_replace/
// [必要ファイル]mb_str_replace.php (HiNa氏作成：http://fetus.k-hsu.net/document/programming/php/mb_str_replace.html)
// fputcsv との違い：ファイルポインタ不要。
//                   項目は全てダブルクォーテーションで括られる。
//                   項目内のダブルクォーテーションはダブルクォーテーションでエスケープされる。
//=======================================================================//
function get_csv_format($data, $encoding = "") {
	// ダブルクォーテーションエスケープに使用
	require_once 'mb_str_replace.php';
	
	$csv = '';
	foreach ($data as $col) {
		$col  = mb_str_replace('"', '""', $col, $encoding);		// エスケープ処理
		$csv .= "\"$col\",";									// 囲み記号・区切り記号付きで出力
	}
	$csv = preg_replace("/,$/", "", $csv);						// 項目末尾の区切り記号を削除
	
	return $csv;
}
?>
