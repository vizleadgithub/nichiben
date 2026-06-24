<?php if ( ! defined( 'BASEPATH' ))  exit( 'No direct script access allowed' );
// wget http://apt.sw.be/redhat/el5/en/i386/rpmforge/RPMS/xpdf-3.02-8.el5.rf.i386.rpm
// yum install xpdf-3.02-8.el5.rf.i386.rpm
#[AllowDynamicProperties]
class Stream_ftp extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------

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
		$this->transmission_exec();
	}
	
	//----------------------------------------------
	//変換実行
	//----------------------------------------------
	function transmission_exec(){
		exec('ps auxw | grep transmission_exec | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		$_count = 0;
		foreach($outputs as $output){
			if(!preg_match('/stream_ftp/i', $output)){
				$_count++;
			}
		}
		if($_count > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		# get config
		$book_library_dir = $this->config->item('book_library_dir');
		$stream_ftp       = $this->config->item('stream_ftp');
		$stream_ftp_dir   = $this->config->item('stream_ftp_dir');
		$stream_ftp_user  = $this->config->item('stream_ftp_user');
		$stream_ftp_pass  = $this->config->item('stream_ftp_pass');

		//図書室マスタから未送信の一覧取得
		$this->load->model('model_book_library');
		$material_list = $this->model_book_library->get_untransmission_material();
		
		if(count($material_list) == 0){
			echo "送信が必要な図書はありませんでした。\n";
			return;
		}

		//送信実行
		foreach($material_list as $material){
			//開始ログ出力
			echo "▽▼▽▼送信開始▽▼▽▼\n";
			echo "学校ID:{$material['school_id']}\n";
			echo "図書ID:{$material['book_library_id']}\n";
			echo "ファイル名:{$material['book_library_logic_name']}\n";

/*
			#ローカル側ディレクトリ・ファイルの存在確認
			$local_dir      = $book_library_dir.'/'.$material['book_library_id'];
			$loacl_dir_list = array();
			$dh = opendir($local_dir);
			while (false !== ($filename = readdir($dh))) {
				$loacl_dir_list[] = $filename;
			}
print_r($loacl_dir_list);
print "\n--------------\n";
*/			
			$error_message = '';
			try {
				# load FTP
				$this->load->library('ftp');

				$config['hostname'] = $stream_ftp;
				$config['username'] = $stream_ftp_user;
				$config['password'] = $stream_ftp_pass;
				$config['port']     = 21;
				$config['passive']  = FALSE;
				$config['debug']    = TRUE;
				
				# connect FTP
				$this->ftp->connect($config);

				#FTP側ディレクトリ確認（/school_{num}）
				$ftp_dir        = $this->ftp->list_files($stream_ftp_dir.'/');
				$hosts_dir_path = $stream_ftp_dir.'/school_'.$material['school_id'];
				if(!in_array($hosts_dir_path, $ftp_dir)){
					$this->ftp->mkdir($hosts_dir_path);
				}

				#FTP側ディレクトリ確認（/school_{num}/book_library_{num}）
				$ftp_dir         = $this->ftp->list_files($hosts_dir_path.'/');
				$hosts_dir_path .= '/book_library_'.$material['book_library_id'];
				if(!in_array($hosts_dir_path, $ftp_dir)){
					$this->ftp->mkdir($hosts_dir_path);
				}

				#local・host側ディレクトリ指定
				$local_dir_path = $book_library_dir.'/'.$material['book_library_id'].'/';
				$hosts_dir_path = $hosts_dir_path.'/';

				# Mirrorによる同期処理
				$this->ftp->mirror($local_dir_path, $hosts_dir_path);

				# unconnect FTP
				$this->ftp->close(); 
			} catch (Exception $e) {
				echo "例外キャッチ：", $e->getMessage(), "\n";
				$error_message = $e->getMessage();
			}

			//終了ログ出力
			echo "△▲△▲送信終了△▲△▲\n\n";

			// 送信成功の場合、stream_flag=1
			// 送信失敗の場合、stream_flag=11
			$stream_flag = 1;
			$notice_judge = 'OK';
			if( strlen($error_message) == 0 ){
				$stream_flag = 1;
				$notice_judge = 'OK';
			}else{
				$stream_flag = 11;
				$notice_judge = 'NG';
			}
			
			// stream_flag の更新
			$data_param = array(
							'book_library_id' => $material['book_library_id'],
							'stream_flag'     => $stream_flag,
						);
			//データ更新
			$retdata = $this->model_book_library->update_book_library_transmission_status($data_param);

			// 正常登録終了・異常登録終了の通知
			$this->load->model('model_notification');
			$notice_result = $this->model_notification->insert_notification(array(
				'notice_kind'  => 'cms-book-library',
				'id'           => $material['book_library_id'],
				'notice_judge' => $notice_judge,
			));
		}
	}


} 


/*End of File program.php*/
