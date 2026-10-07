<?
//=======================================================================//
// fputcsv のファイルポインタ未使用版（CSV形式にした値を返す）
// [参考サイト]http://spelunker2.wordpress.com/2012/10/23/【php】fputcsvとmb_str_replace/
// [必要ファイル]mb_str_replace.php (HiNa氏作成：http://fetus.k-hsu.net/document/programming/php/mb_str_replace.html)
// fputcsv との違い：ファイルポインタ不要。
//                   項目は全てダブルクォーテーションで括られる。
//                   項目内のダブルクォーテーションはダブルクォーテーションでエスケープされる。
//=======================================================================//
//=======================================================================//
// CSVインジェクション(数式の実行)対策
// 先頭が = + - @ タブ 改行 の文字列は、Excel等で数式として実行されるおそれがあるため、
// 先頭に ' を付けて文字列として扱わせる。ただし数値(-5・+3 など)はそのまま出力する。
// 文字コード変換の前後どちらでも、判定する先頭1バイトはASCIIのため同じ結果になる。
//=======================================================================//
if ( ! function_exists('csv_formula_safe')) {
	function csv_formula_safe($value) {
		if ( ! is_string($value) || $value === '') {
			return $value;
		}
		if (preg_match('/^[=+\-@\t\r\n]/', $value) && ! is_numeric($value) && ! ($value === '-' || $value === '+')) {
			return "'" . $value;
		}
		return $value;
	}
}

function get_csv_format($data, $encoding = "") {
	// ダブルクォーテーションエスケープに使用
	require_once 'mb_str_replace.php';

	$csv = '';
	foreach ($data as $col) {
		$col  = csv_formula_safe($col);							// 数式として実行されないようにする
		$col  = mb_str_replace('"', '""', $col, $encoding);		// エスケープ処理
		$csv .= "\"$col\",";									// 囲み記号・区切り記号付きで出力
	}
	$csv = preg_replace("/,$/", "", $csv);						// 項目末尾の区切り記号を削除
	
	return $csv;
}
?>
