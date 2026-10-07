<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//=======================================================================//
// form_dropdown()に渡す選択肢の表示名(ラベル)を画面用にエスケープする
//  - CodeIgniter標準のform_dropdown()は、選択肢のラベルをエスケープしない
//  - フレームワーク本体(system/)は変更せず、呼び出し側で渡す前にエスケープする
//  - すでにエスケープ済みの文字(&amp;など)は二重にエスケープしない
//  - 配列のキー(option value)はform_dropdown()側でエスケープされるため変更しない
//    (ただしoptgroupのラベルになる配列のキーはエスケープされないため、ここで変換する)
//=======================================================================//
if ( ! function_exists('dropdown_escape'))
{
	function dropdown_escape($options)
	{
		if ( ! is_array($options))
		{
			return $options;
		}

		$escaped = array();
		foreach ($options as $key => $val)
		{
			if (is_array($val))
			{
				// optgroup: キーがラベルとして出力されるためエスケープする
				$key = htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8', FALSE);
				$escaped[$key] = dropdown_escape($val);
			}
			else
			{
				$escaped[$key] = htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8', FALSE);
			}
		}

		return $escaped;
	}
}

//=======================================================================//
// CSVインジェクション(数式の実行)対策
// 先頭が = + - @ タブ 改行 の文字列は、Excel等で数式として実行されるおそれがあるため、先頭に ' を付けて文字列として扱わせる。
// 数値(-5・+3 など)と、1文字だけの - + はそのまま出力する。CSVに書き出す直前に適用すること(fputcsv の行に array_map で適用)。
//=======================================================================//
if ( ! function_exists('csv_formula_safe'))
{
	function csv_formula_safe($value)
	{
		if ( ! is_string($value) || $value === '')
		{
			return $value;
		}
		if (preg_match('/^[=+\-@\t\r\n]/', $value) && ! is_numeric($value) && ! ($value === '-' || $value === '+'))
		{
			return "'" . $value;
		}
		return $value;
	}
}
