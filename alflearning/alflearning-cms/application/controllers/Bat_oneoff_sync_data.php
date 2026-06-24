<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//set_time_limit(3600); // ファイル数多いため
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
@ini_set('memory_limit', -1);

#[AllowDynamicProperties]
class Bat_oneoff_sync_data extends CI_Controller {
	//----------------------------------------------
	// 定数（各ID増加値）
	//----------------------------------------------
	public $_before_convert_url = 'https://kenshu.nichibenren.or.jp';       //　置換対象文字列
	public $_after_convert_url  = 'http://nichibenren-stg.alfredcore.net';  //　置換文字列
	
	public $_pro_video_thumbnail_path = '/exports/video_thumbnail/';           // 本番data1側、 　ビデオサムネイル格納パス
	public $_stg_video_thumbnail_path = '/alflearning-data/video_thumbnail/';  // ステージング側、ビデオサムネイル格納パス
	
	public $_pro_alfproduct_path = '/exports/alfproduct/';           // 本番data1側、 　alflearning-data 内 alfproduct 格納パス
	public $_stg_alfproduct_path = '/alflearning-data/alfproduct/';  // ステージング側、alflearning-data 内 alfproduct 格納パス

	
	//----------------------------------------------
	// コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();

		//DB接続
		$this->load->database();

		// テスト時使用。
		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	//----------------------------------------------
	// メイン処理
	//----------------------------------------------
	public function index(){
		echo "[".date('Y-m-d H:i:s')."]"."【日弁連】本番情報のステージングへのコピー処理"."\n";
		echo "[".date('Y-m-d H:i:s')."]"."　実行するには、sync_start を末尾に追加してください"."\n";
		exit();
	}

	//----------------------------------------------
	//
	// 【日弁連】本番環境→ステージングへのDB・必要ファイルのコピー処理
	// ※全ＤＢのdump・dumpファイルの文字列置換を行うため、処理が重くなるので実行時間に注意！
	//
	//----------------------------------------------
	public function sync_start(){
		
		// 標準入力により実行再確認
		echo "[".date('Y-m-d H:i:s')."]"."start"."\n";
		echo "[".date('Y-m-d H:i:s')."]"."【日弁連】本番環境のデータベース・ビデオサムネイル・「/alflearning-data/alfproduct/」配下一式の情報を、ステージングにコピーします。\n";
		
		while (1) {
			echo "[".date('Y-m-d H:i:s')."]".'よろしいですか（ yes / no ）? ';
			
			// 標準入力から取得
			$line = trim(fgets(STDIN));	// 入力後エンターキーが押されるまで待ち状態
			
			if($line === 'yes') {
				// yes なら無限ループを抜けて、処理実行
				break;
			}else if ($line === 'no') {
				// no なら無限ループを抜けて、処理せず終了
				echo "[".date('Y-m-d H:i:s')."]"."end\n";
				exit();
			}else{
				// yes no 以外はループ継続
			}
		}

		// 現在のユーザが【root】以外は終了。
		$exec_output = array();
		$return_var  = -1;
		exec('whoami', $exec_output, $return_var);
		
		if($exec_output[0]==='root'){
		}else{
			echo "[".date('Y-m-d H:i:s')."]"."root 権限で実行してください\n";
			echo "[".date('Y-m-d H:i:s')."]"."end\n";
			exit();
		}
		//print "    output：".var_dump($exec_output)."\n";  // [0]処理実行ユーザ名
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 作成ファイル名の作成"."\n";
		$file_date_time    = date('Ymd_His');
		
		$stg_dump_filename = "/root/alflearning_DB_stg_".$file_date_time.".sql";
		
		$pro_dump_temp     = "/tmp/alflearning_DB_pro_".$file_date_time.".sql";
		$pro_dump_filename = "/root/alflearning_DB_pro_".$file_date_time.".sql";
		
		$pro_dump_convert  = "/root/alflearning_DB_pro_convert_".$file_date_time.".sql";
		
		print "[".date('Y-m-d H:i:s')."]"."-- -- Stg_DUMP    : ".$stg_dump_filename."\n";
		print "[".date('Y-m-d H:i:s')."]"."-- -- Pro_DUMP    : ".$pro_dump_filename."\n";
		print "[".date('Y-m-d H:i:s')."]"."-- -- Pro_convert : ".$pro_dump_convert."\n";
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- ステージング側、alfredcore データベースの dump 作成"."\n";
		$stg_dump_tgzname = str_replace('.sql',   '.tgz', $stg_dump_filename);
		$stg_dump_tgzname = str_replace('/root/', '',     $stg_dump_tgzname);
		$exec_output = array();
		$return_var  = -1;
		//print "ディレクトリ１：".getcwd()."\n";
	//	exec('mysqldump -u root    --password=tOs4nHa0gITw alflearning            > '.$stg_dump_filename.' ', $exec_output, $return_var);
		exec('cd /root && mysqldump -u root -t --password=tOs4nHa0gITw alflearning wp_options > '.$stg_dump_filename.' && tar czvf '.$stg_dump_tgzname.' '.str_replace('/root/', '', $stg_dump_filename).' && rm -f '.str_replace('/root/', '', $stg_dump_filename).' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 戻り値なし
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		//print "ディレクトリ２：".getcwd()."\n";
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番db2側、alfredcore データベースの dump 作成"."\n";
		$exec_output = array();
		$return_var  = -1;
	//	exec("ssh root@db2 'mysqldump -u root    --password=tOs4nHa0gITw alflearning            > ".$pro_dump_temp." && mv ".$pro_dump_temp." ".$pro_dump_filename."' ", $exec_output, $return_var);
		exec("ssh root@db2 'mysqldump -u root -t --password=tOs4nHa0gITw alflearning wp_options > ".$pro_dump_temp." && mv ".$pro_dump_temp." ".$pro_dump_filename."' ", $exec_output, $return_var);
		//  
		//  exec("ssh root@db2 'mysqldump -u root -t --password=tOs4nHa0gITw alflearning mtb_bar_association > ".$pro_dump_filename."' ", $exec_output, $return_var);
		//  exec('ssh root@db2 mysqldump -u root -t --password=tOs4nHa0gITw alflearning wp_options > '.$pro_dump_filename.' ', $exec_output, $return_var);
		//  ssh root@db2 mysqldump -u root -t -p alflearning mtb_bar_association > root@db2:/root/alflearning_DB_pro_mtb_bar_association.sql
		// コマンドをシングルコーテーションで囲わないと、命令文実行サーバにファイルが作成されてしまうので注意
		//print "    output：".var_dump($exec_output)."\n";  // 戻り値なし
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番db2側dumpファイルを、ステージング側へコピー"."\n";
		$exec_output = array();
		$return_var  = -1;
		exec('rsync -avz root@db2:'.$pro_dump_filename.'  '.$pro_dump_filename.''.' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 処理ログあり（ファイル名等）
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番db2側dumpファイルに対して、URL の置換"."\n";
		$exec_output = array();
		$return_var  = -1;
		exec('sed -e s%'.$this->_before_convert_url.'%'.$this->_after_convert_url.'%g '.$pro_dump_filename.' > '.$pro_dump_convert.' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 戻り値なし
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		// 違うファイル名にするなら、こう。
		// $ sed -e s/[置換対象]/[置換後文字列]/g [入力ファイル名] > [出力ファイル名]
		// 同じファイル名に上書きするなら、こう。
		// 今回はこれがやりたかった。
		// $ sed -i s/[置換対象]/[置換後文字列]/g [ファイル名]
		// sed -e s%https://kenshu.nichibenren.or.jp%http://nichibenren-stg.alfredcore.net%g alflearning_DB_pro_batch_test_20140110.sql > alflearning_DB_pro_batch_test_20140110_a.sql
		// sed -i s%https://kenshu.nichibenren.or.jp%http://nichibenren-stg.alfredcore.net%g alflearning_DB_pro_batch_test_20140110_b.sql
		
		

//tar czvf [圧縮ファイル名].tgz [圧縮対象ファイル名、デイレクトリ名]
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番db2側dumpファイルに対して、tgz圧縮及びsqlファイルの削除"."\n";
		$pro_dump_tgzname =str_replace('.sql', '.tgz', $pro_dump_filename);
		$exec_output = array();
		$return_var  = -1;
		exec("ssh root@db2 'cd /root/ && tar czvf ".$pro_dump_tgzname." ".str_replace('/root/', '', $pro_dump_filename)." && rm -f ".str_replace('/root/', '', $pro_dump_filename)."' ", $exec_output, $return_var);
		
		
		
		
echo "[".date('Y-m-d H:i:s')."]"."end\n";
exit();
		
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番側から、ビデオサムネイルのコピー"."\n";
		$exec_output = array();
		$return_var  = -1;
	//	exec('rsync -avz  --delete root@data1:'.$this->_pro_video_thumbnail_path.'  '.$this->_stg_video_thumbnail_path.''.' ', $exec_output, $return_var);
		exec('rsync -avzn --delete root@data1:'.$this->_pro_video_thumbnail_path.'  '.$this->_stg_video_thumbnail_path.''.' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 処理ログあり（ファイル名等）
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番側から、alflearning-data 配下の alfproduct ディレクトリのコピー"."\n";
		$exec_output = array();
		$return_var  = -1;
	//	exec('rsync -avz  --delete root@data1:'.$this->_pro_alfproduct_path.'  '.$this->_stg_alfproduct_path.''.' ', $exec_output, $return_var);
		exec('rsync -avzn --delete root@data1:'.$this->_pro_alfproduct_path.'  '.$this->_stg_alfproduct_path.''.' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 処理ログあり（ファイル名等）
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本番側置換 dump を、ステージング DB-alflearning に反映"."\n";
		$exec_output = array();
		$return_var  = -1;
	//	exec('mysql -u root --password=tOs4nHa0gITw alflearning < '.$pro_dump_convert.' ', $exec_output, $return_var);
		//print "    output：".var_dump($exec_output)."\n";  // 戻り値なし
		//print "return_val：".$return_var."\n";             // 正常処理ならゼロ
		
		
		
		print "\n"."[".date('Y-m-d H:i:s')."]"."-- 本ステージング DB-alflearning に対して、更新処理"."\n";
		try{
			$this->db->trans_start();
			// student テーブル全レコードに対して、パスワードの変更（alfredcore123：be70190a52363172be5cd909597f5fda2fc2719517608e8453c408d6f20059c2）
			$this->db->query("UPDATE student SET student_password_encrypt = 'be70190a52363172be5cd909597f5fda2fc2719517608e8453c408d6f20059c2' ");
			
			// video テーブル全レコードに対して、idkeyの変更（idkey：6NbcqC1ogBRv）
			$this->db->query("UPDATE video   SET idkey                    = '6NbcqC1ogBRv' ");
			
			$this->db->trans_commit();
		}catch(Exception $e){ 
			$this->db->trans_rollback();
			print "[".date('Y-m-d H:i:s')."]"."-- -- DB 更新処理で例外発生"."\n";
		}
		
		
		
		echo "[".date('Y-m-d H:i:s')."]"."end\n";
		exit();
	}
}

