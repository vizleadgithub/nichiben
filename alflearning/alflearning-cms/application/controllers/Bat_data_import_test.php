<?php
//$this->input->is_cli_request()
#[AllowDynamicProperties]
class Bat_data_import_test extends CI_Controller {
	function __construct(){
		parent::__construct();

		// 最大実行時間を無制限に設定
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@ini_set('MAX_EXECUTION_TIME', -1);
			@set_time_limit(0);
			@ini_set('memory_limit', -1);
		}

		// データインポートのモジュール
		// $this->load->model('model_data_import');
		$this->load->model('model_data_import_test');
	}

	public function index(){
	}

	public function start($type = "", $all_del = 0){
		$file_name_1 = "";
		$file_name_2 = "";
		$file_name_3 = "";
		$file_name_4 = "";

		// テーブルデータ 全削除
		if ($type == "delete") {
		}

		// ユーザー インポート
		elseif ($type == "MS_USER") {
			$file_name_1 = "MS_USER.csv";
		}

		// eラーニング インポート
		elseif ($type == "MS_COURSE") {
			$file_name_1 = "MS_COURSE.csv";

		}

		// eラーニング動画 インポート
		elseif ($type == "MS_CONTENTS") {
			$file_name_1 = "MS_CONTENTS.csv";
		}

		// 会場研修 インポート
		elseif ($type == "KENSHU_20110516") {
			$file_name_1 = "KENSHU_20110516.csv";
			$file_name_2 = "KENSHU_OWNER1.csv";
			$file_name_3 = "KENSHU_OWNER2.csv";
			$file_name_4 = "KENSHU_TARGET.csv";
		}
		elseif ($type == "KENSHU") {
			$file_name_1 = "KENSHU.csv";
			$file_name_2 = "KENSHU_OWNER1.csv";
			$file_name_3 = "KENSHU_OWNER2.csv";
			$file_name_4 = "KENSHU_TARGET.csv";
		}

		// 倫理研修－テスト インポート
		elseif ($type == "tMORAL_EXAM_2009") {
			$file_name_1 = "tMORAL_EXAM.csv";
		}
		elseif ($type == "tMORAL_EXAM_2010") {
			$file_name_1 = "tMORAL_EXAM.csv";
		}
		elseif ($type == "tMORAL_EXAM_2011") {
			$file_name_1 = "tMORAL_EXAM.csv";
		}
		elseif ($type == "tMORAL_EXAM_2012") {
			$file_name_1 = "tMORAL_EXAM.csv";
		}
		elseif ($type == "tMORAL_EXAM") {
			$file_name_1 = "tMORAL_EXAM.csv";
		}

		// 倫理研修－動画 インポート
		elseif ($type == "tMORAL_MOVIE") {
			$file_name_1 = "tMORAL_MOVIE.csv";
		}

		// 倫理研修解答結果 インポート
		elseif ($type == "tMORAL_RESULT_2009") {
			$file_name_1 = "tMORAL_RESULT_2009.csv";
		}
		elseif ($type == "tMORAL_RESULT_2010") {
			$file_name_1 = "tMORAL_RESULT_2010.csv";
		}
		elseif ($type == "tMORAL_RESULT_2011") {
			$file_name_1 = "tMORAL_RESULT_2011.csv";
		}
		elseif ($type == "tMORAL_RESULT_2012") {
			$file_name_1 = "tMORAL_RESULT_2012.csv";
		}
		elseif ($type == "tMORAL_RESULT") {
			$file_name_1 = "tMORAL_RESULT.csv";
		}

		// AVIS動画 インポート
		elseif ($type == "video") {
			$file_name_1 = "videoo.csv";
		}

		// 商品カテゴリ インポート
		elseif ($type == "category") {
			$file_name_1 = "category.csv";
		}

		else {
			echo "[NG] import not type\n";
			return;
		}

		if ($type == "delete") {
			$file_path_1 = '';
		}
		else {
			$file_path_1 = '/alflearning-data/__import_data__/CSV/'.$file_name_1;
			if (!@file_exists($file_path_1)) {
				echo "[NG] import file not found ".$file_name_1."\n";
				return;
			}

			if ($file_name_2) {
				$file_path_2 = '/alflearning-data/__import_data__/CSV/'.$file_name_2;
				if (!@file_exists($file_path_2)) {
					echo "[NG] import file not found ".$file_name_2."\n";
					return;
				}
			}

			if ($file_name_3) {
				$file_path_3 = '/alflearning-data/__import_data__/CSV/'.$file_name_3;
				if (!@file_exists($file_path_3)) {
					echo "[NG] import file not found ".$file_name_3."\n";
					return;
				}
			}

			if ($file_name_4) {
				$file_path_4 = '/alflearning-data/__import_data__/CSV/'.$file_name_4;
				if (!@file_exists($file_path_4)) {
					echo "[NG] import file not found ".$file_name_4."\n";
					return;
				}
			}
		}

		echo "[info] data_import start -> type:".$type." / file_name_1:".$file_name_1." / file_name_2:".$file_name_2." / file_name_3:".$file_name_3." / file_name_4:".$file_name_4."\n";

		// テーブルデータ 全削除
		if ($type == "delete") {
			// $res = $this->model_data_import->import_delete();
			$res = $this->model_data_import_test->import_delete();
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// ユーザー インポート
		elseif ($type == "MS_USER") {
			// $res = $this->model_data_import->import_ms_user($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_ms_user($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// eラーニング インポート
		elseif ($type == "MS_COURSE") {
			// $res = $this->model_data_import->import_ms_course($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_ms_course($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// eラーニング動画 インポート
		elseif ($type == "MS_CONTENTS") {
			// $res = $this->model_data_import->import_ms_contents($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_ms_contents($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// 会場研修 インポート
		elseif ($type == "KENSHU_20110516" || $type == "KENSHU") {
			// $res = $this->model_data_import->import_kenshu($file_path_1, $file_path_2, $file_path_3, $file_path_4, $all_del);
			$res = $this->model_data_import_test->import_kenshu($file_path_1, $file_path_2, $file_path_3, $file_path_4, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// 倫理研修－テスト インポート
		elseif ($type == "tMORAL_EXAM_2009" || $type == "tMORAL_EXAM_2010" || $type == "tMORAL_EXAM_2011" || $type == "tMORAL_EXAM_2012" || $type == "tMORAL_EXAM") {
			// $res = $this->model_data_import->import_tmoral_exam($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_tmoral_exam($file_path_1, $type, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// 倫理研修－動画 インポート
		elseif ($type == "tMORAL_MOVIE") {
			// $res = $this->model_data_import->import_tmoral_movie($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_tmoral_movie($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// 倫理研修解答結果 インポート
		elseif ($type == "tMORAL_RESULT_2009" || $type == "tMORAL_RESULT_2010" || $type == "tMORAL_RESULT_2011" || $type == "tMORAL_RESULT_2012" || $type == "tMORAL_RESULT") {
			// $res = $this->model_data_import->import_tmoral_result($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_tmoral_result($file_path_1, $type, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// AVIS動画 インポート
		elseif ($type == "video") {
			// $res = $this->model_data_import->import_video($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_video($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		// 商品カテゴリ インポート
		elseif ($type == "category") {
			// $res = $this->model_data_import->import_category($file_path_1, $all_del);
			$res = $this->model_data_import_test->import_category($file_path_1, $all_del);
			if ($res) {
				echo "[OK] import run complete -> all:".number_format($res[0])."(regist:".number_format($res[1])." update:".number_format($res[2]).")\n";
			}
			else {
				echo "[NG] import run error\n";
			}
		}

		else {
			echo "[NG] import run error\n";
		}
	}
}
?>
