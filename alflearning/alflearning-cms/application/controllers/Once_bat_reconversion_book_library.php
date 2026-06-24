<?php if ( ! defined( 'BASEPATH' ))  exit( 'No direct script access allowed' );
// wget http://apt.sw.be/redhat/el5/en/i386/rpmforge/RPMS/xpdf-3.02-8.el5.rf.i386.rpm
// yum install xpdf-3.02-8.el5.rf.i386.rpm
#[AllowDynamicProperties]
class Once_bat_reconversion_book_library extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $temp_dir = '/tmp/al_booklibrary';										// 一時ファイル格納場所
	private $temp_log = '/tmp/al_booklibrary/reconversion_exec_book_library.log';	// 確認用処理ログ

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
		$this->reconversion_exec_book_library();
	}
	
	//----------------------------------------------
	//変換実行
	//----------------------------------------------
	function reconversion_exec_book_library(){
		$this->load->helper('file');
		$log_data = '';

		exec('ps auxw | grep reconversion_exec_book_library | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "[".date('Y/m/d H:i:s')."]前回バッチが起動中でした\n";
			write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]前回バッチが起動中でした\n");
			return;
		}
		
		echo "[".date('Y/m/d H:i:s')."]▽▼▽▼処理開始▽▼▽▼\n";
		$log_data .= "[".date('Y/m/d H:i:s')."]▽▼▽▼処理開始▽▼▽▼\n";
		
		# get config
		$book_library_dir = $this->config->item('book_library_dir');
		$stream_ftp       = $this->config->item('stream_ftp');
		$stream_ftp_dir   = $this->config->item('stream_ftp_dir');
		$stream_ftp_user  = $this->config->item('stream_ftp_user');
		$stream_ftp_pass  = $this->config->item('stream_ftp_pass');
		$zip_password     = $this->config->item('zip_password');

		//図書室マスタから再変換一覧取得
		$this->load->model('model_book_library');
		$material_list = $this->model_book_library->get_reconversion_book_library();
		
		if(count($material_list) == 0){
			echo "[".date('Y/m/d H:i:s')."]再変換対象の図書はありませんでした。\n";
			write_file($this->temp_log, $log_data."[".date('Y/m/d H:i:s')."]再変換対象の図書はありませんでした。\n", 'w');
			return;
		}
		// 作業用一時ディレクトリ確認
		if( file_exists($this->temp_dir) ){
			// ある場合は中身全削除
			delete_files($this->temp_dir);
			echo "[".date('Y/m/d H:i:s')."]作業用一時ディレクトリのクリア\n";
			write_file($this->temp_log, $log_data."[".date('Y/m/d H:i:s')."]作業用一時ディレクトリのクリア\n", 'a');
		}else{
			// ない場合は作成
			mkdir($this->temp_dir, 0777);
			echo "[".date('Y/m/d H:i:s')."]作業用一時ディレクトリの新規作成\n";
			write_file($this->temp_log, $log_data."[".date('Y/m/d H:i:s')."]作業用一時ディレクトリの新規作成\n", 'a');
		}

		// 01.FTPファイルの取得 ----------------------------------------------------------------------------------------
		$error_message = '';
		try {
			// load FTP
			$this->load->library('ftp');
			
			$config['hostname'] = $stream_ftp;
			$config['username'] = $stream_ftp_user;
			$config['password'] = $stream_ftp_pass;
			$config['port']     = 21;
			$config['passive']  = FALSE;
			$config['debug']    = TRUE;
			
			# connect FTP
			$this->ftp->connect($config);

			// ファイルの取得
			foreach($material_list as $material){
				# FTP側ディレクトリ+ファイル名設定
				$ftp_dir   = $stream_ftp_dir.'/school_'.$material['school_id'].'/book_library_'.$material['book_library_id'].'/'.$material['book_library_name'];

				# LOCAL側ディレクトリ+ファイル名設定（再変換対象ファイル）
				$local_dir = $this->temp_dir.'/'.$material['school_id'].'_'.$material['book_library_id'].'_'.$material['book_library_name'];

				# FTPからLOCALへファイルコピー
				$this->ftp->download($ftp_dir, $local_dir, 'auto');
				
				echo "[".date('Y/m/d H:i:s')."]01.FTP File Get : {$ftp_dir} -> {$local_dir}\n";
				write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]01.FTP File Get : {$ftp_dir} -> {$local_dir}\n", 'a');
			}
			
			// unconnect FTP
			$this->ftp->close(); 
		} catch (Exception $e) {
			echo "[".date('Y/m/d H:i:s')."]例外キャッチ01 : ".$e->getMessage()."\n";
			write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]例外キャッチ01 : ", $e->getMessage()."\n", 'a');
			return;
		}

		// 02.取得したファイルのPDF再変換。及びzipファイルの作成 -------------------------------------------------------
		$error_message = '';
		try {
			// ファイルの取得
			foreach($material_list as $material){
				$ext  = strtolower(pathinfo($material['book_library_name'],PATHINFO_EXTENSION));
				$name = mb_ereg_replace('.'.$ext,'',$material['book_library_name'],'i');

				# LOCAL側ディレクトリ+ファイル名設定（再変換対象ファイル）
				$local_dir     = $this->temp_dir.'/'.$material['school_id'].'_'.$material['book_library_id'].'_'.$material['book_library_name'];

				# LOCAL側ディレクトリ+ファイル名設定（再変換PDFファイル）[/***/master.pdf]
				$local_dir_pdf_1 = $this->temp_dir.'/'.$material['school_id'].'_'.$material['book_library_id'].'_'.$name.'.pdf';
				$local_dir_pdf_2 = $this->temp_dir.'/'.$name.'.pdf';

				#LOCAL側ディレクトリ+ファイル名設定（ZIPファイル）[/***/*_*_master.zip]
				$local_dir_zip = $this->temp_dir.'/'.$material['school_id'].'_'.$material['book_library_id'].'_'.$name.'.zip';

				# 取得ファイルに対してPDF変換
				$res = array();
				chdir('/usr/local/bin/jodconverter-2.2.2/lib');
				exec('java -jar jodconverter-cli-2.2.2.jar -f pdf '.$local_dir.' 2>&1', $res);
				
				foreach($res as $r){
					echo "***".$r."\n";	// 変換履歴出力
					write_file($this->temp_log, "***".$r."\n", 'a');
				}

				# PDFファイルのリネーム
				$rename_result = rename( $local_dir_pdf_1, $local_dir_pdf_2 );

				# リネームPDFファイルのZIP圧縮 + パーティション変更
				exec('zip -P '.$zip_password.' -j '.$local_dir_zip.' '.$local_dir_pdf_2);
				$ret = chmod($local_dir_zip, 0777);

				# PDFファイルの削除
				unlink($local_dir_pdf_2);

				echo "[".date('Y/m/d H:i:s')."]02.Reconversion : {$local_dir} -> {$local_dir_zip}\n\n";
				write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]02.Reconversion : {$local_dir} -> {$local_dir_zip}\n\n", 'a');
			}
		} catch (Exception $e) {
			echo "[".date('Y/m/d H:i:s')."]例外キャッチ02 : ".$e->getMessage()."\n";
			write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]例外キャッチ02 : ", $e->getMessage()."\n", 'a');
			return;
		}

		// 03.FTPへファイル送信 ----------------------------------------------------------------------------------------
		$error_message = '';
		try {
			// load FTP
			$this->load->library('ftp');
			
			$config['hostname'] = $stream_ftp;
			$config['username'] = $stream_ftp_user;
			$config['password'] = $stream_ftp_pass;
			$config['port']     = 21;
			$config['passive']  = FALSE;
			$config['debug']    = TRUE;
			
			# connect FTP
			$this->ftp->connect($config);

			// ファイルの取得
			foreach($material_list as $material){
				$ext  = strtolower(pathinfo($material['book_library_name'],PATHINFO_EXTENSION));
				$name = mb_ereg_replace('.'.$ext,'',$material['book_library_name'],'i');

				#LOCAL側ディレクトリ+ファイル名設定（ZIPファイル）[/***/*_*_master.zip]
				$local_dir_zip = $this->temp_dir.'/'.$material['school_id'].'_'.$material['book_library_id'].'_'.$name.'.zip';

				# FTP側ディレクトリ+ファイル名設定
				$ftp_dir_zip   = $stream_ftp_dir.'/school_'.$material['school_id'].'/book_library_'.$material['book_library_id'].'/'.$name.'.zip';

				# FTP 送信
				// $this->ftp->upload('/local/path/to/myfile.html', '/public_html/myfile.html', 'ascii', 0775);
				$this->ftp->upload($local_dir_zip, $ftp_dir_zip, 'auto');

				echo "[".date('Y/m/d H:i:s')."]03.FTP File Set : {$local_dir_zip} -> {$ftp_dir_zip}\n";
				write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]03.FTP File Set : {$local_dir_zip} -> {$ftp_dir_zip}\n", 'a');
			}
			
			// unconnect FTP
			$this->ftp->close(); 
		} catch (Exception $e) {
			echo "[".date('Y/m/d H:i:s')."]例外キャッチ03：", $e->getMessage(), "\n";
			write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]例外キャッチ03 : ", $e->getMessage()."\n", 'a');
			return;
		}

		// 終了ログ出力
		echo "[".date('Y/m/d H:i:s')."]△▲△▲処理終了△▲△▲\n\n";
		write_file($this->temp_log, "[".date('Y/m/d H:i:s')."]△▲△▲処理終了△▲△▲\n\n", 'a');
		return;
	}
} 


/*End of File program.php*/
