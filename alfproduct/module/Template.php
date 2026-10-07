<?php
require_once( dirname(__FILE__) .'./../smarty/Smarty.class.php' );
//require_once( dirname(__FILE__) .'./../public/wordpress/wp-load.php' );
require_once('/srv/alfproduct/public/custom_pages/cp-load.php' );

/**
 * テンプレートの |escape(html)を、管理画面(CMS)と同じ「すでにエスケープ済みの文字(&amp;等)は二重にエスケープしない」方式にそろえる。
 * 管理画面は htmlspecialchars(..., ENT_QUOTES, 'UTF-8', false) で表示しているため、受講者サイト・商品管理も同じ見え方にする(No.38)。
 * html 以外の種別(javascript・url など)は Smarty 標準の escape にそのまま委譲する。
 */
function alf_smarty_modifier_escape($string, $esc_type = 'html', $char_set = 'UTF-8')
{
	if ($esc_type === 'html') {
		return htmlspecialchars((string) $string, ENT_QUOTES, $char_set, false);
	}
	require_once(dirname(__FILE__) . '/../smarty/plugins/modifier.escape.php');
	return smarty_modifier_escape($string, $esc_type, $char_set);
}

class Template extends Smarty {

	public $template_dir_old;
	public $compile_dir_old;
	/**
	* コンストラクタ
	* ディレクトリ指定やデフォルト値の初期設定を行う
	*/
	public function __construct() {
		parent::__construct(); //親クラス初期化//php8 update

		// |escape を、二重エスケープしない方式に統一(No.38)
		$this->register_modifier('escape', 'alf_smarty_modifier_escape');

		$this->template_dir_old = $this->template_dir;
		$this->compile_dir_old = $this->compile_dir;
		if ($this->is_mobile()) {
			$this->template_dir =  $this->template_dir.'/smartphone/';
			$this->compile_dir  =  $this->compile_dir.'/smartphone/';
		}else {
			$agent = $_SERVER['HTTP_USER_AGENT']; 
			if(preg_match("/^DoCoMo/", $agent)){//docomo
				header("Location: /unsupported.html");
				exit;
				
//			  $this->template_dir =  $this->template_dir.'/mobile/';
//			  $this->compile_dir  =  $this->compile_dir.'/mobile/';
			}else if(preg_match("/^J-PHONE|^Vodafone|^SoftBank/", $agent)){//SB
				header("Location: /unsupported.html");
				exit;
				
//			  $this->template_dir =  $this->template_dir.'/mobile/';
//			  $this->compile_dir  =  $this->compile_dir.'/mobile/';
			}else if(preg_match("/^UP.Browser|^KDDI/", $agent)){//au
				header("Location: /unsupported.html");
				exit;
				
//			  $this->template_dir =  $this->template_dir.'/mobile/';
//			  $this->compile_dir  =  $this->compile_dir.'/mobile/';
			}else if(preg_match("/iPhone/", $agent)){//iPhone
				$this->template_dir =  $this->template_dir.'/smartphone/';
				$this->compile_dir  =  $this->compile_dir.'/smartphone/';
			}else if(preg_match("/Android/", $agent)){//android
				if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
					$this->template_dir =  $this->template_dir.'/default/';
					$this->compile_dir  =  $this->compile_dir.'/default/';
				}elseif(preg_match("/mobile/", $agent)){
					$this->template_dir =  $this->template_dir.'/smartphone/';
					$this->compile_dir  =  $this->compile_dir.'/smartphone/';
				} elseif(preg_match("/Mobile/", $agent)){
					$this->template_dir =  $this->template_dir.'/smartphone/';
					$this->compile_dir  =  $this->compile_dir.'/smartphone/';
				} elseif(preg_match("/Mobi/", $agent)){
					$this->template_dir =  $this->template_dir.'/smartphone/';
					$this->compile_dir  =  $this->compile_dir.'/smartphone/';
				} elseif(preg_match("/Tablet/", $agent)){
					$this->template_dir =  $this->template_dir.'/default/';
					$this->compile_dir  =  $this->compile_dir.'/default/';
				} else {
					$this->template_dir =  $this->template_dir.'/default/';
					$this->compile_dir  =  $this->compile_dir.'/default/';
				}
			}else{
				$this->template_dir =  $this->template_dir.'/default/';
				$this->compile_dir  =  $this->compile_dir.'/default/';
			}
			//$this->template_dir   =  $this->template_dir.'/default';
		}

		$this->left_delimiter  =  '<!--{';
		$this->right_delimiter =  '}-->';
		$this->compile_check   =  true;

		//テンプレート側でデフォルトで使いたい変数の設定
		//$this->assign('SYS_NAME',SYS_NAME);
	}

	function admin_title($admin_main_title){
		$this->assign( 'admin_main_title', $admin_main_title );
	}
	function admin_comment($admin_main_comment){
		$this->assign( 'admin_main_comment', $admin_main_comment );
	}
	function admin_name($admin_main_name){
		$this->assign( 'admin_main_name', $admin_main_name );
	}
	function admin_school($admin_main_school){
		$this->assign( 'admin_main_school', $admin_main_school );
	}
	function admin_sidemenu($admin_main_side_menu){
		$this->assign( 'admin_main_side_menu', $admin_main_side_menu );
	}

	function admin_layout($include_template_file, $pop_flg=false){
		$this->template_dir =  $this->template_dir_old.'/admin/';
		$this->compile_dir  =  $this->compile_dir_old.'/admin/';
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		if ($pop_flg){
			$this->display('pop_frame.tpl');
		} else {
			$this->display('main_frame.tpl');
		}
	}
	function admin_layout_non($include_template_file){
		$this->template_dir	=  $this->template_dir_old.'/admin/';
		$this->compile_dir	=  $this->compile_dir_old.'/admin/';
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame_non.tpl');
	}


	function layout($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame.tpl');
	}
	function layout_noside($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame_noside.tpl');
	}
	function layout_oneside($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame_oneside.tpl');
	}
	function layout_non($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame_non.tpl');
	}
	function layout_pop($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('main_frame_pop.tpl');
	}
	function layout_alfstream($include_template_file){
		$this->assign( 'include_template_file', $this->template_dir.$include_template_file );
		$this->display('alfstream_frame.tpl');
	}

	function is_mobile () {
		$ret = false;
		$agent = $_SERVER['HTTP_USER_AGENT'];
//var_dump($agent);
		if(preg_match("/Android/", $agent)){//android
//print_r("[Test1]");
			if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
//print_r("[Test2]");
				$ret = false;
			}elseif(preg_match("/mobile/", $agent)){
				$ret = true;
			} elseif(preg_match("/Mobile/", $agent)){
				$ret = true;
			} elseif(preg_match("/Mobi/", $agent)){
				$ret = true;
			} elseif(preg_match("/Tablet/", $agent)){
				$ret = false;
			} else {
				$ret = false;
			}
		} else {
			$useragents = array(
				'iPhone', // Apple iPhone
				'iPod', // Apple iPod touch
				'Android', // 1.5+ Android
				'dream', // Pre 1.5 Android
				'CUPCAKE', // 1.5+ Android
				'blackberry9500', // Storm
				'blackberry9530', // Storm
				'blackberry9520', // Storm v2
				'blackberry9550', // Storm v2
				'blackberry9800', // Torch
				'webOS', // Palm Pre Experimental
				'incognito', // Other iPhone browser
				'webmate' // Other iPhone browser
			);
			$pattern = '/'.implode('|', $useragents).'/i';
			$ret = preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
		}
		return $ret;
	}

}
?>
