<?php
/** 
 * CSV ファイルか文字列を読み込んでカンマ区切りで分割し配列にして返す。 
 */  
#[AllowDynamicProperties]
class CsvParser {
    /** 
     * CSV 形式の文字列をカンマ区切りで分割して配列にして返す。 
     *  
     * @param  string $str       CSV 文字列。 
     * @param  string $modifiers パターン修飾子。 
     * @return array   
     */ 
    public static function readStr($str, $modifiers) {
        $pattern = '/(,|\r?\n|^)([^",\r\n]+|"(?:[^"]|"")*")?/' . $modifiers;
        $str = substr($str,0,1) === ',' ? '""' . $str : $str;
        preg_match_all($pattern, $str, $matches);
        
        $values = $matches[2];
        foreach ($values as &$val) {
            $val = preg_replace('/^"|"$/' . $modifiers, '', $val);
            $val = preg_replace('/""/' . $modifiers, '"', $val);
        }
        return $values;
    }

    /** 
     * CSV ファイルを読み込んで２次元配列にしてかえす。 
     * 
     * @param  string $filename      読み込むファイル名。 
     * @param  string $to_encoding   変換後の文字コード。 
     * @param  string $from_encoding 変換前の文字コード。 
     * @param  string $modifiers     パターン修飾子。 
     * @return array  ２次元配列。 
     */  
    public static function readFile($filename, $to_encoding, $from_encoding, $modifiers) {
        $data = array();
        
        $fp = fopen($filename, 'r');
        while (($line = fgets($fp)) !== false) {
            $line_2 .= mb_convert_encoding($line, $to_encoding, $from_encoding);
            // fgets はフィールド内の改行コードにもひっかかる。  
            // 取得した文字列のダブルクォーテーションの数が奇数なら行末まで  
            // とれていないので、取得と結合を繰り返す。
            if ((substr_count($line_2, '"') % 2) === 0) {
                $values = self::readStr(rtrim($line_2), $modifiers);
                $data[] = $values;
                $line_2 = '';
            }
        }
        fclose($fp);
        return $data;
    }
}
?>