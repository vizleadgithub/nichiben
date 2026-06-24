<?php if ( ! defined( 'BASEPATH' ))  exit( 'No direct script access allowed' );
// wget http://apt.sw.be/redhat/el5/en/i386/rpmforge/RPMS/xpdf-3.02-8.el5.rf.i386.rpm
// yum install xpdf-3.02-8.el5.rf.i386.rpm
#[AllowDynamicProperties]
class Material_convert extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $supportExts  = array(				//対応拡張子とタイプ
					'pdf'		=> 'pdf',
					'jpg'		=> 'image',
					'jpeg'		=> 'image',
					'png'		=> 'image',
					'gif'		=> 'image',
					'ppt'		=> 'doc',
					'pptx'		=> 'doc',
					'doc'		=> 'doc',
					'docx'		=> 'doc',
					'xls'		=> 'doc',
					'xlsx'		=> 'doc',
					'txt'		=> 'doc',
					'rtf'		=> 'doc',
					'odt'		=> 'doc',
					'sxw'		=> 'doc',
					'wpd'		=> 'doc',
					'csv'		=> 'doc',
					'tsv'		=> 'doc',
					'ods'		=> 'doc',
					'sxc'		=> 'doc',
					'odp'		=> 'doc',
					'sxi'		=> 'doc',
				);

	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
	}
	
	//----------------------------------------------
	//index->変換実行
	//----------------------------------------------
	function index(){
		$this->convert_exec_material();
	}
	
	//----------------------------------------------
	//変換実行
	//----------------------------------------------
	function convert_exec_material(){
		exec('ps auxw | grep convert_exec_material | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}
	//	exec('ps auxw | grep convert_exec | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
	//	$_count = 0;
	//	foreach($outputs as $output){
	//		if(!preg_match('/convert_exec_book_library/i', $output)){
	//			$_count++;
	//		}
	//		if(!preg_match('/convert_exec_class_material/i', $output)){
	//			$_count++;
	//		}
	//	}
	//	if($_count > 1){
	//		echo "前回バッチが起動中でした\n";
	//		return;
	//	}

		$material_dir = $this->config->item('material_dir').'/';

		//資料マスタから未変換の一覧取得
		$this->load->model('model_material');
		$material_list = $this->model_material->get_unchanged_material();
		
		if(count($material_list) == 0){
			echo "変換が必要な資料はありませんでした。\n";
			return;
		}
		
		//変換実行
		foreach($material_list as $material){
			//開始ログ出力
			echo "▽▼▽▼変換開始▽▼▽▼\n";
			echo "学校名:{$material['school_name']}\n";
			echo "講座名:{$material['cource_name']}\n";
			echo "授業名:{$material['class_name']}\n";
			echo "資料名:{$material['material_name']}\n\n";
			
			// 変換失敗フラグ初期設定
			$convert_error_flag = 0;
			
			//ファイル名と拡張子に分離
			$ext  = strtolower(pathinfo($material['material_name'],PATHINFO_EXTENSION));
			$name = mb_ereg_replace('.'.$ext,'',$material['material_name'],'i');
			//パス設定
//			$path         = $material_dir.$material['class_id'].'/'.$material['material_id'];
			$path         = $material_dir.$material['material_id'].'/';
			$orgFilePath  = $path.'/'.$material['material_name'];
			$convFilePath = $orgFilePath;
			$convTempPath = $path.'/converttemp';
			$pdfFilePath  = '';
			
			echo "ファイル名:{$name}\n";
			echo "拡張子　　:{$ext}\n";
			echo "フルパス　:{$orgFilePath}\n";
			
			//形式のチェック
			if(isset($this->supportExts[$ext])){
				//オフィスドキュメント系はPDFに一旦変換
				if($this->supportExts[$ext] == 'doc'){
					echo "pdfファイルへ変換\n";
					$res = array();
					chdir('/usr/local/bin/jodconverter-2.2.2/lib');
					exec('java -jar jodconverter-cli-2.2.2.jar -f pdf '.$convFilePath.' 2>&1', $res);
					$convFilePath = mb_ereg_replace('.'.$ext, '.pdf', $convFilePath,'i');
					$pdfFilePath  = $convFilePath;
					
					//処理経過をログに出力
					foreach($res as $r){
						echo $r."\n";
					}
					echo "'{$orgFilePath}' pdf convert to '{$convFilePath}'\n";
					echo "\n";
				}
				
				//画像へ変換
				echo "画像ファイルへの変換とコピー\n";
				//一時保存フォルダの作成
				if(!is_dir($convTempPath)){
					mkdir($convTempPath);
				}
				$imgTempFiles = array();
				if($this->supportExts[$ext] == 'doc' || $this->supportExts[$ext] == 'pdf'){
					exec("nice -n 19 pdftoppm $convFilePath $convTempPath/output.ppm");
					exec("nice -n 19 mogrify -format jpg $convTempPath/output*.ppm");
				//  Version: ImageMagick 6.7.9-1 2012-08-23 Q16 http://www.imagemagick.org の場合、下記を使用
				//	exec('convert -density 100 +antialias '.$convFilePath.' '.$convTempPath.'/convert.jpg');
				}
				else{
					exec('convert '.$convFilePath.' '.$convTempPath.'/convert.jpg');
				}
				exec('find '.$convTempPath.'/ -name *.jpg | sort -n -k 2 -t "-"', $imgTempFiles);
				if(!count($imgTempFiles)){
					$convert_error_flag = 1;		// 変換失敗フラグ
					echo "画像変換エラー\n";
				} else {
					//画像変換済ファイルをページ単位のディレクトリに保存
					$page = 0;
					foreach($imgTempFiles as $imgFile){
						//ページ単位フォルダの設定と作成
						$page++;
						$pagePath = $path."/Page{$page}/";
						if(!is_dir($pagePath)){
							mkdir($pagePath);
							chmod($pagePath, 0777);
						}
						//一時保存フォルダからページ単位フォルダへ画像のコピー
						$distFilePath = "{$pagePath}{$name}-Page{$page}.jpg";
						copy($imgFile, $distFilePath);
						chmod($distFilePath, 0777);
						echo "'{$imgFile}' copy to '{$distFilePath}'\n";
					}
					echo "\n";
					
					//変換したpdfファイルは削除
					if($pdfFilePath != ''){
						unlink($pdfFilePath);
					}
					
					//一時保存フォルダの削除
					delete_files($convTempPath);
					rmdir($convTempPath);
					
					//変換済みへ更新
					//データ更新用引数設定
					$data_param = array(
									'material_id' => $material['material_id'],
									'page_num' => count($imgTempFiles),
									'status'   => 1,
								);
					//データ更新
					$retdata = $this->model_material->update_material_convert_status($data_param);
					echo "資料マスタのステータスを変換済みに更新しました\n";
					
					// 正常登録終了の通知
					$this->load->model('model_notification');
					$notice_result = $this->model_notification->insert_notification(array(
						'notice_kind'  => 'cms-material',
						'id'           => $material['material_id'],
						'notice_judge' => 'OK',
					));
				}
			} else {
				//未サポート形式
				$convert_error_flag = 1;		// 変換失敗フラグ
				echo "変換できない形式です。\n";
			}
			//終了ログ出力
			echo "△▲△▲変換終了△▲△▲\n\n";

			// 変換失敗フラグ有の場合、資料テーブルのステータスに11を立てる
			if($convert_error_flag == 1){
				$data_param = array(
								'material_id' => $material['material_id'],
								'page_num' => count($imgTempFiles),
								'status'   => 11,
							);
				//データ更新
				$retdata = $this->model_material->update_material_convert_status($data_param);
				$convert_error_flag = 0;

				// 異常登録終了の通知
				$this->load->model('model_notification');
				$notice_result = $this->model_notification->insert_notification(array(
					'notice_kind'  => 'cms-material',
					'id'           => $material['material_id'],
					'notice_judge' => 'NG',
				));
			}
		}
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	function convert_exec_book_library(){
		exec('ps auxw | grep convert_exec_book_library | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$book_library_dir = $this->config->item('book_library_dir');

		//資料マスタから未変換の一覧取得
		$this->load->model('model_book_library');
		$book_library_list = $this->model_book_library->get_unchanged_material();
		
		if(count($book_library_list) == 0){
			echo "変換が必要な資料はありませんでした。\n";
			return;
		}

		//変換実行
		foreach($book_library_list as $material){
			//開始ログ出力
			echo "▽▼▽▼変換開始▽▼▽▼\n";
			echo "学校名:{$material['school_name']}\n";
			echo "講座名:{$material['cource_name']}\n";
			echo "資料名:{$material['book_library_name']}\n\n";
			
			// 変換失敗フラグ初期設定
			$convert_error_flag = 0;
			
			//ファイル名と拡張子に分離
			$ext  = strtolower(pathinfo($material['book_library_name'],PATHINFO_EXTENSION));
			$name = mb_ereg_replace('.'.$ext,'',$material['book_library_name'],'i');
			//パス設定
			$path         = $book_library_dir.'/'.$material['book_library_id'];
			$orgFilePath  = $path.'/'.$material['book_library_name'];
			$convFilePath = $orgFilePath;
			$convTempPath = $path.'/converttemp';
			$pdfFilePath  = '';
			
			echo "ファイル名:{$name}\n";
			echo "拡張子　　:{$ext}\n";
			echo "フルパス　:{$orgFilePath}\n";
			
			//形式のチェック
			if(isset($this->supportExts[$ext])){
				//オフィスドキュメント系はPDFに一旦変換
				if($this->supportExts[$ext] == 'doc'){
					echo "pdfファイルへ変換\n";
					$res = array();
					chdir('/usr/local/bin/jodconverter-2.2.2/lib');
					exec('java -jar jodconverter-cli-2.2.2.jar -f pdf '.$convFilePath.' 2>&1', $res);
					$convFilePath = mb_ereg_replace('.'.$ext, '.pdf', $convFilePath,'i');
					$pdfFilePath  = $convFilePath;
					
					//処理経過をログに出力
					foreach($res as $r){
						echo $r."\n";
					}
					echo "'{$orgFilePath}' pdf convert to '{$convFilePath}'\n";
					echo "\n";
				}
				
				//画像へ変換
				echo "画像ファイルへの変換とコピー\n";
				//一時保存フォルダの作成
				if(!is_dir($convTempPath)){
					mkdir($convTempPath);
				}
				$imgTempFiles = array();
				if($this->supportExts[$ext] == 'doc' || $this->supportExts[$ext] == 'pdf'){
					exec("nice -n 19 pdftoppm $convFilePath $convTempPath/output.ppm");
					exec("nice -n 19 mogrify -format jpg $convTempPath/output*.ppm");
				}
				else{
					exec('convert '.$convFilePath.' '.$convTempPath.'/convert.jpg');
				}
				exec('find '.$convTempPath.'/ -name *.jpg | sort -n -k 2 -t "-"', $imgTempFiles);
				if(!count($imgTempFiles)){
					$convert_error_flag = 1;		// 変換失敗フラグ
					echo "画像変換エラー\n";
				} else {
					//画像変換済ファイルをページ単位のディレクトリに保存
					$page = 0;
					foreach($imgTempFiles as $imgFile){
						//ページ単位フォルダの設定と作成
						$page++;
						$pagePath = $path."/Page{$page}/";
						if(!is_dir($pagePath)){
							mkdir($pagePath);
							chmod($pagePath, 0777);
						}
						//一時保存フォルダからページ単位フォルダへ画像のコピー
						$distFilePath = "{$pagePath}{$name}-Page{$page}.jpg";
						copy($imgFile, $distFilePath);
						chmod($distFilePath, 0777);
						echo "'{$imgFile}' copy to '{$distFilePath}'\n";
					}
					echo "\n";

					// サムネイル画像の生成（最大140x140）
					// Jun Add 2013/02/05
					$distFilePathBase = "{$path}/Page1/{$name}-Page1.jpg";
					$distFilePathThum = "{$path}/Page1/{$name}-Page1-thum.jpg";
					exec('convert -define jpeg:size=140x140 -resize 140x140 '.$distFilePathBase.' '.$distFilePathThum);
					chmod($distFilePathThum, 0777);
					
					//図書室、本ファイルのパスワード付きZIPファイルの作成
					//doc系→pdf化したファイルを圧縮
					//image系→imageファイルを圧縮
					$zip_password = $per_page = $this->config->item('zip_password');
					$zip_file     = '';
					$zip_in_file  = '';
					if($pdfFilePath != ''){
						$zip_file    = mb_ereg_replace('.pdf', '.zip', $pdfFilePath,'i');
						$zip_in_file = $pdfFilePath;
					}elseif($ext == 'pdf'){
						$zip_file    = mb_ereg_replace('.'.$ext, '.zip', $convFilePath,'i');
						$zip_in_file = $convFilePath;
					}else{
						$temp_pdf_file  = mb_ereg_replace('.'.$ext, '.pdf', $convFilePath,'i');
						exec('/usr/bin/convert '.$convFilePath.' -compress zip '.$temp_pdf_file);
						$ret = chmod($temp_pdf_file, 0777);
						
						$pdfFilePath = $temp_pdf_file;
						$zip_file    = mb_ereg_replace('.pdf', '.zip', $pdfFilePath,'i');
						$zip_in_file = $pdfFilePath;
					
					}
					exec('zip -P '.$zip_password.' -j '.$zip_file.' '.$zip_in_file);
					$ret = chmod($zip_file, 0777);
					
					//変換したpdfファイルは削除
					if($pdfFilePath != ''){
						unlink($pdfFilePath);
					}
					
					//一時保存フォルダの削除
					delete_files($convTempPath);
					rmdir($convTempPath);
					
					//変換済みへ更新
					//データ更新用引数設定
					$data_param = array(
									'book_library_id' => $material['book_library_id'],
									'page_num'        => count($imgTempFiles),
									'status'          => 1,
								);
					//データ更新
					$retdata = $this->model_book_library->update_book_library_convert_status($data_param);
					echo "資料マスタのステータスを変換済みに更新しました\n";
				}
			} else {
				//未サポート形式
				$convert_error_flag = 1;		// 変換失敗フラグ
				echo "変換できない形式です。\n";
			}
			//終了ログ出力
			echo "△▲△▲変換終了△▲△▲\n\n";

			// 変換失敗フラグ有の場合、資料テーブルのステータスに11を立てる
			// Streamフラグに、11を立てる
			if($convert_error_flag == 1){
				$data_param = array(
								'book_library_id' => $material['book_library_id'],
								'page_num'    => count($imgTempFiles),
								'status'      => 11,
							);
				//データ更新
				$retdata = $this->model_book_library->update_book_library_convert_status($data_param);
				
				$data_param = array(
								'book_library_id' => $material['book_library_id'],
								'stream_flag'     => 11,
							);
				//データ更新
				$retdata = $this->model_book_library->update_book_library_transmission_status($data_param);
				$convert_error_flag = 0;

				// 異常登録終了の通知
				$this->load->model('model_notification');
				$notice_result = $this->model_notification->insert_notification(array(
					'notice_kind'  => 'cms-book-library',
					'id'           => $material['book_library_id'],
					'notice_judge' => 'NG',
				));
			}

		}
	}

	//----------------------------------------------
	// 授業資料変換
	//----------------------------------------------
	function convert_exec_class_material(){
		exec('ps auxw | grep convert_exec_class_material | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$class_material_dir = $this->config->item('class_material_dir').'/';

		//授業資料マスタから未変換の一覧取得
		$this->load->model('model_class_material');
		$class_material_list = $this->model_class_material->get_unchanged_class_material();
		
		if(count($class_material_list) == 0){
			echo "変換が必要な資料はありませんでした。\n";
			return;
		}
		
		//変換実行
		foreach($class_material_list as $class_material){
			//開始ログ出力
			echo "▽▼▽▼変換開始▽▼▽▼\n";
			echo "授業ID　　:{$class_material['class_id']}\n";
			echo "授業資料ID:{$class_material['class_material_id']}\n\n";
			
			// 変換失敗フラグ初期設定
			$convert_error_flag = 0;
			
			//ファイル名と拡張子に分離
			$ext  = strtolower(pathinfo($class_material['material_name'],PATHINFO_EXTENSION));
			$name = mb_ereg_replace('.'.$ext,'',$class_material['material_name'],'i');
			//パス設定
			$path         = $class_material_dir.$class_material['class_id'].'/teacher_'.$class_material['teacher_id'].'/'.$class_material['class_material_id'].'/';
			$orgFilePath  = $path.$class_material['material_name'];
			$convFilePath = $orgFilePath;
			$convTempPath = $path.'/converttemp';
			$pdfFilePath  = '';
			
			echo "ファイル名:{$name}\n";
			echo "拡張子　　:{$ext}\n";
			echo "フルパス　:{$orgFilePath}\n";
			
			//形式のチェック
			if(isset($this->supportExts[$ext])){
				//オフィスドキュメント系はPDFに一旦変換
				if($this->supportExts[$ext] == 'doc'){
					echo "pdfファイルへ変換\n";
					$res = array();
					chdir('/usr/local/bin/jodconverter-2.2.2/lib');
					exec('java -jar jodconverter-cli-2.2.2.jar -f pdf '.$convFilePath.' 2>&1', $res);
					$convFilePath = mb_ereg_replace('.'.$ext, '.pdf', $convFilePath,'i');
					$pdfFilePath  = $convFilePath;
					
					//処理経過をログに出力
					foreach($res as $r){
						echo $r."\n";
					}
					echo "'{$orgFilePath}' pdf convert to '{$convFilePath}'\n";
					echo "\n";
				}
				
				//画像へ変換
				echo "画像ファイルへの変換とコピー\n";
				//一時保存フォルダの作成
				if(!is_dir($convTempPath)){
					mkdir($convTempPath);
				}
				$imgTempFiles = array();
				if($this->supportExts[$ext] == 'doc' || $this->supportExts[$ext] == 'pdf'){
					exec("nice -n 19 pdftoppm $convFilePath $convTempPath/output.ppm");
					exec("nice -n 19 mogrify -format jpg $convTempPath/output*.ppm");
				}
				else{
					exec('convert '.$convFilePath.' '.$convTempPath.'/convert.jpg');
				}
				exec('find '.$convTempPath.'/ -name *.jpg | sort -n -k 2 -t "-"', $imgTempFiles);
				if(!count($imgTempFiles)){
					$convert_error_flag = 1;		// 変換失敗フラグ
					echo "画像変換エラー\n";
				} else {
					//画像変換済ファイルをページ単位のディレクトリに保存
					$page = 0;
					foreach($imgTempFiles as $imgFile){
						//ページ単位フォルダの設定と作成
						$page++;
						$pagePath = $path."/Page{$page}/";
						if(!is_dir($pagePath)){
							mkdir($pagePath);
							chmod($pagePath, 0777);
						}
						//一時保存フォルダからページ単位フォルダへ画像のコピー
						$distFilePath = "{$pagePath}{$name}-Page{$page}.jpg";
						copy($imgFile, $distFilePath);
						chmod($distFilePath, 0777);
						echo "'{$imgFile}' copy to '{$distFilePath}'\n";
					}
					echo "\n";
					
					//変換したpdfファイルは削除
					if($pdfFilePath != ''){
						unlink($pdfFilePath);
					}
					
					//一時保存フォルダの削除
					delete_files($convTempPath);
					rmdir($convTempPath);
					
					//変換済みへ更新
					//データ更新用引数設定
					$data_param = array(
									'class_material_id' => $class_material['class_material_id'],
									'page_num'    => count($imgTempFiles),
									'status'      => 1,
								);
					//データ更新
					$retdata = $this->model_class_material->update_class_material_convert_status($data_param);
					echo "授業資料マスタのステータスを変換済みに更新しました\n";

					// 正常登録終了の通知
					$this->load->model('model_notification');
					$notice_result = $this->model_notification->insert_notification(array(
						'notice_kind'  => 'cms-class-material',
						'id'           => $class_material['class_material_id'],
						'notice_judge' => 'OK',
					));
				}
			} else {
				//未サポート形式
				$convert_error_flag = 1;		// 変換失敗フラグ
				echo "変換できない形式です。\n";
			}
			//終了ログ出力
			echo "△▲△▲変換終了△▲△▲\n\n";

			// 変換失敗フラグ有の場合、授業資料テーブルのステータスに11を立てる
			if($convert_error_flag == 1){
				$data_param = array(
								'class_material_id' => $class_material['class_material_id'],
								'page_num' => count($imgTempFiles),
								'status'   => 11,
							);
				//データ更新
				$retdata = $this->model_class_material->update_class_material_convert_status($data_param);
				$convert_error_flag = 0;

				// 異常登録終了の通知
				$this->load->model('model_notification');
				$notice_result = $this->model_notification->insert_notification(array(
					'notice_kind'  => 'cms-class-material',
					'id'           => $class_material['class_material_id'],
					'notice_judge' => 'NG',
				));
			}
		}
	}

} 

/*End of File program.php*/
