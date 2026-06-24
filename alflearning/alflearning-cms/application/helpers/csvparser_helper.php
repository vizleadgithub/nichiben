<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!class_exists('CsvParser')) {
	require_once(APPPATH.'helpers/CsvParser.php');

	function readStrCsv($str, $modifiers) {
		$CsvParser = new CsvParser();
		return $CsvParser->readStr($str, $modifiers);
	}

	function readFileCsv($filename, $to_encoding, $from_encoding, $modifiers) {
		$CsvParser = new CsvParser();
		return $CsvParser->readFile($filename, $to_encoding, $from_encoding, $modifiers, $terminated);
	}
}
?>