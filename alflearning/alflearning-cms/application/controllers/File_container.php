<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class File_container extends CI_Controller {
	function __construct() {
		parent::__construct();

		if(!$this->libauth->is_logged_in()){
			exit();
		}
	}

	public function index(){
	}

	//------------------------------------------------------
	// 図書室管理 - 登録済み図書のサムネイルURLを取得
	//   図書はALF Streamから取得するため、ここは未使用予定
	//------------------------------------------------------
	public function get_book_library_thubmnail($book_library_id){
		$rootDir       = preg_replace('/\/$/i', '', $this->config->item('book_library_dir'));
		$readfile_path = "$rootDir/$book_library_id/Page1/master-Page1.jpg";
		$readfile_path2 = "$rootDir/$book_library_id/Page1/master.jpg";

		if(file_exists($readfile_path)){
		//	header('Content-Type: image/jpeg');
		//	ob_clean();	// 追加処理
		//	flush();	// 追加処理
		//	readfile($readfile_path);
			$this->_readfile($readfile_path);
		}
		else if(file_exists($readfile_path2)){
		//	header('Content-Type: image/jpeg');
		//	ob_clean();	// 追加処理
		//	flush();	// 追加処理
		//	readfile($readfile_path);
			$this->_readfile($readfile_path2);
		}else{
		//	redirect('./'.$this->config->item('images_dir').'no_image.png');
			redirect('');
			return;
		}
	}
	
	//------------------------------------------------------
	// 資料管理 - 登録済み資料のサムネイルURLを取得
	//------------------------------------------------------
	public function get_material_thubmnail($material_id){
		$rootDir       = preg_replace('/\/$/i', '', $this->config->item('material_dir'));
		$readfile_path = "$rootDir/$material_id/Page1/master-Page1.jpg";
		$readfile_path2 = "$rootDir/$material_id/Page1/master.jpg";

		if(file_exists($readfile_path)){
			$this->_readfile($readfile_path);
		}
		else if(file_exists($readfile_path2)){
			$this->_readfile($readfile_path2);
		}else{
			redirect('');
			return;
		}
	}

	//------------------------------------------------------
	// 授業管理 - 登録済み授業資料のサムネイルURLを取得
	// [2012/08/08]講師ノート取得時、最新履歴ファイルを取得するように修正
	// [2012/08/08]ファイルが見つからない場合、404を返すように修正
	//------------------------------------------------------
	public function get_class_material_thubmnail($class_id, $teacher_id, $student_id, $class_material_id){
		$rootDir = preg_replace('/\/$/i', '', $this->config->item('class_material_dir'));

		// サムネイルパス設定（授業資料、講師ノート（最新のファイル）、受講者ノート、受講者提出ノート）
		$readfile_path_material = "$rootDir/$class_id/teacher_$teacher_id/$class_material_id/Page1/master-Page1.jpg";
		$readfile_path_note     = "$rootDir/$class_id/teacher_$teacher_id/$class_material_id/Page1/master.jpg";
		$readfile_path_submit   = "$rootDir/$class_id/student_$student_id/$class_material_id/submit/Page1/master.jpg";
		$readfile_path_memo     = "$rootDir/$class_id/student_$student_id/$class_material_id/my/Page1/master.jpg";

		if(file_exists($readfile_path_material)){
			$this->_readfile($readfile_path_material);
		}elseif(file_exists($readfile_path_note)){
			// 最新の講師ノートを取得（※master.jpg => 白一色のファイルのため）
			
			// ディレクトリ内ファイル名の取得
			$search_dir = "$rootDir/$class_id/teacher_$teacher_id/$class_material_id/Page1/";
			$dir_h = opendir( $search_dir ) ;
			while (false !== ($file_list[] = readdir($dir_h))) ;
			closedir( $dir_h ) ;
			
			// ファイル名(降順)にする（master.jpg、1343636056345.jpg、1343636056336.jpg…）
			rsort($file_list) ;
			
			// 数字のみの名称且つ、数字が一番大きい数字のファイル名を取得
			$last_file_name = '';
			foreach ( $file_list as $file_name ){
				//ファイルのみを表示
				if( is_file( $search_dir . $file_name) ){
					if (preg_match("/^[0-9]+\.jpg$/", $file_name)) {
						$last_file_name = $file_name;
						break;
					}
				}
			}
		//	$this->_readfile($readfile_path_note);
			$this->_readfile($search_dir.$last_file_name);
		}elseif(file_exists($readfile_path_submit)){
			$this->_readfile($readfile_path_submit);
		}elseif(file_exists($readfile_path_memo)){
			$this->_readfile($readfile_path_memo);
		}else{
			// return 404 Not Found 
			show_404();
			return;
		}
	}

	//------------------------------------------------------
	// [ver2.0]課題管理 - 登録済み課題雛形のサムネイルURLを取得
	//     /alflearning-data/issue /teacher/school_{school_id} /{issue_id} /{issue_temp_id}/{issue_temp_name}
	//------------------------------------------------------
	public function get_issue_template_thubmnail($school_id, $issue_id, $issue_temp_id, $issue_temp_name){
		$rootDir       = preg_replace('/\/$/i', '', $this->config->item('issue_file_dir'));
		$readfile_path = "{$rootDir}/teacher/school_{$school_id}/{$issue_id}/{$issue_temp_id}/{$issue_temp_name}";
	
		if(file_exists($readfile_path)){
			$this->_readfile($readfile_path);
		}else{
			redirect('');
			return;
		}
	}

	//------------------------------------------------------
	// [ver2.0]課題管理 - 受講者提出された課題のサムネイルURLを取得
	//     /alflearning-data/issue /student /student_{student_id} /{issue_submit_id} /{issue_submit_name}
	//------------------------------------------------------
	public function get_issue_submit_thubmnail($student_id, $issue_submit_id, $issue_submit_name){
		$rootDir       = preg_replace('/\/$/i', '', $this->config->item('issue_file_dir'));
		$readfile_path = "{$rootDir}/student/student_{$student_id}/{$issue_submit_id}/{$issue_submit_name}";
		
		if(file_exists($readfile_path)){
			$this->_readfile($readfile_path);
		}else{
			redirect('');
			return;
		}
	}

	//----------------------------------------------------------------------//
	// 画像ファイルの読み込み
	//   ※メモリに入れると遅いらしいのでこれに差し替え
	// [2012/08/24]リアルパスに対してソフトリンク作ってone time urlとしてリダイレクトさせるように変更
	//----------------------------------------------------------------------//
	private function _readfile($f){
		
		$rootDir = $this->config->item('voice_file_dir');
		if(!$rootDir){
			show_error('setting not found', 404);
		}
		
		$pathRealFile = $f;
		if(!file_exists($pathRealFile)){
			show_error('file not found', 404);
		}
		
		$htdocsFile = "$rootDir/_htdocs-onetime";
		if(!file_exists($htdocsFile)){
			mkdir($htdocsFile);
		}
		
		$onetimeName     = md5(time().uniqid()).'.jpg';
		$onetimeRealPath = "$rootDir/_htdocs-onetime/$onetimeName";
		exec("ln -s $pathRealFile $onetimeRealPath");

		redirect("/voice_onetime/$onetimeName");

/*
		$buf = (ob_get_length() ? ob_get_length() : 4096);
		header('Content-Type: image/jpeg');
		flush();
		$handle = fopen($f, 'rb');
		while(!feof($handle)){
			echo fread($handle, $buf);
			flush();
		}
		fclose($handle);
*/
	}

/*	public function get_image_new($materialId, $page = 1){
		$rootDir	= preg_replace('/\/$/i', '', $this->config->item('material_dir'));
		$classId	= $this->session->userdata['classId'];

		if(!file_exists("$rootDir/$classId/$materialId/Page$page")){
			redirect('/static/images/no_image.png');
			return;
		}

		exec("ls -l --time-style=long-iso $rootDir/$classId/$materialId/Page$page/ | sort -k 6,7 | tail -1", $f);

		if(!count($f)){
			redirect('/static/images/no_image.png');
			return;
		}

		$split_f = explode(' ', $f[0]);

		$new_f_name = $split_f[count($split_f) - 1];

		header('Content-Type: image/jpeg');
		readfile("$rootDir/$classId/$materialId/Page$page/$new_f_name");
	}	*/
}
