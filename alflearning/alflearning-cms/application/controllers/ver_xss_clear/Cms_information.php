<?php
#[AllowDynamicProperties]
class Cms_information extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $day_second         = 86400;		//日数計算用、一日秒数
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		$this->load->helper('string_inspection_helper');
		
		//headerへブラウザのキャッシュ無効化設定
		$this->output->set_header ("Cache-Control: no-store, no-cache, must-revalidate" );
		$this->output->set_header ("Cache-Control: post-check=0, pre-check=0", false );
		
		//プロファイラ（TRUEでデバッグ）
		$this->output->enable_profiler(FALSE);
		
		//ログインチェック
		if ( ! $this->libauth->is_logged_in()) {
			//非ログイン時はログインページへ
			redirect('/login_page?backurl='.$_SERVER['REQUEST_URI']);
		} else {
			//ログイン済みはteacher_id、teacher_nameを取得
			
			//権限が無い場合はトップページにリダイレクト(トップページの場合はログアウト）
			$work_auth = $this->libauth->get_teacher_auth();
			if($work_auth['information'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
	}
	
	//----------------------------------------------
	//一覧表示
	// [2012/10/12]検索条件、タグ・フリーワードの追加
	// [2012/10/12]ページ移動時の検索条件が維持できるよう修正
	//----------------------------------------------
	function index(/*$sel_month = 0, */$offset=0){
		// load language
		$this->lang->load('common');
		
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');
		
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_information_wp');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('information_search_cond') ?: array(
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_free_word'] = ($this->input->post('s_free_word', TRUE) ?? '');
			$this->session->set_userdata('information_search_cond', $data);
		}

		try {
			$information_list = $this->model_information_wp->get_information_search_list(array(
				'school_id' 	=> $this->libauth->get_school_id(),
				'offset'    	=> $offset,
				'rowcount'  	=> $per_page,
				's_free_word'	=> $data['s_free_word'],	//search
			));
		} catch(Exception $e) {
			echo $e->getMessage() ."<br>";
			exit();
		}
		$information_table_list = $information_list['items'];
		$data['information_list'] = $information_table_list;

		//ページネーション設定
		$config['base_url']	= base_url().'/cms_information/index';
		$config['per_page']	= $per_page;
		$config['total_rows']	= $information_list['cnt'];
		$config['first_link']	= '&lt;&lt;';
		$config['last_link']	= '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		// [2012/10/12]
		if(count($_GET)){
			$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1?'.http_build_query($_GET).'$2', $data['pagination']);
		}
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_information/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/10/05]お知らせタグ項目追加
	//----------------------------------------------
	function newdata(){
		//表示用変数の初期化
		$data = array();
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//初期表示設定
		$data['information']['update_flg']     = 0;

		$data['information']['information_id']      = 0;
		$data['information']['information_date']    = date('Y/m/d');
		$data['information']['information_title']   = '';
		$data['information']['information_caption'] = '';
		$data['information']['information_tags']        = '';

		$data['information']['information_open']    = '1900/01/01 00:00:00';
		$data['information']['information_close']   = '2100/12/31 23:59:59';
		$data['information']['information_type']    = 0;
		$data['information']['information_url']     = "";
		$data['information']['information_topfit']  = "";

		$data['information']['school_id']      = $this->libauth->get_school_id();
		$data['information']['show_teacher']   = 0;
		$data['information']['show_student']   = 0;
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_information/edit',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//修正フォーム表示
	//----------------------------------------------
	function edit(){
		if( $this->session->userdata('edit_form_data')==null && empty($this->session->userdata('edit_form_data')) ){
			redirect('/cms_information/');
		}
		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['information_id']) &&  $edit_form_data['information_id'] <> ''){
			//画面表示用データ設定
			$data['information'] = $edit_form_data;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_information/edit',
							'submenu_idx' => ($data['information']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('information');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => ($data['information']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view('session_error', ($data['information']['update_flg']==0 ? 2 : 3), $data);
		}
	}
	
	//----------------------------------------------
	//詳細表示フォーム表示
	// [2012/10/05]お知らせタグ項目追加
	//----------------------------------------------
	function detail($information_id){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		
		//モデル読み込み
		$this->load->model('model_information_wp');
		
		//データ取得用引数設定
		$select_param = array(
			'information_id' => $information_id,
		);
		//データ取得
		$db_data = $this->model_information_wp->get_information($select_param);
		
		if(count($db_data) > 0){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['information']['update_flg']          = 1;
			$data['information']['information_id']      = $db_data['ID'];
			$data['information']['information_date']    = date("Y/m/d", strtotime($db_data['post_date']));
			$data['information']['information_title']   = $db_data['post_title'];
			$data['information']['information_caption'] = $db_data['post_content'];
			$data['information']['information_tags']    = "";

			$data['information']['information_open']    = date("Y/m/d H:i:s", strtotime($db_data['open_date']));
			$data['information']['information_close']   = date("Y/m/d H:i:s", strtotime($db_data['close_date']));
			$data['information']['information_type']    = $db_data['status'];//$db_data['product_type_add'];
			$data['information']['information_url']     = $db_data['url'];
			$data['information']['information_topfit']  = $db_data['topfit'];

			$data['information']['school_id']           = 1;
			$data['information']['show_teacher']        = 0;
			$data['information']['show_student']        = [];
			
			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['information']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_information/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			header("Location:/cms_information/");
			exit();
		}
	}
	
	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/10/05]お知らせタグ項目追加
	//----------------------------------------------
	function confirm(){
//var_dump( $_POST["information_url"] );
//var_dump( "test1" );
//exit();
		// load language
		$this->lang->load('common');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'          , $this->lang->line_or_def('common_flg','flg')                          , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('information_id'      , $this->lang->line_or_def('common_id','ID')                            , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('information_date'    , $this->lang->line_or_def('common_date','日付')                        , 'trim|xss_clean|required|callback_date_check');
		$this->form_validation->set_rules('information_type'    , $this->lang->line_or_def('common_information_type','種別')            , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('information_title'   , $this->lang->line_or_def('common_title','タイトル')                   , 'trim|xss_clean|required');
		$this->form_validation->set_rules('information_caption' , $this->lang->line_or_def('common_caption','説明')                     , 'trim|xss_clean');
		//$this->form_validation->set_rules('information_url'     , $this->lang->line_or_def('common_url','URL')                          , '');
		$this->form_validation->set_rules('information_open'    , $this->lang->line_or_def('common_public_period_start','公開期間開始') , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('information_close'   , $this->lang->line_or_def('common_public_period_end','公開期間終了')   , 'trim|xss_clean|required|callback_datetime_check|callback_period_check[information_open]');
		$this->form_validation->set_rules('information_topfit'  , $this->lang->line_or_def('common_information_topfit','先頭枠への固定表示')   , 'trim|xss_clean|numeric');
		//検証
//var_dump( $_POST["information_url"] );
//var_dump( "test2" );
//exit();
		if($this->form_validation->run() == FALSE){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['information']['update_flg']          = ($this->input->post('update_flg')) ?? 0;
			$data['information']['information_id']      = ($this->input->post('information_id')) ?? 0;
			$data['information']['information_date']    = ($this->input->post('information_date')) ?? date("Y/m/d 00:00:00");
			$data['information']['information_type']    = ($this->input->post('information_type')) ?? 0;
			$data['information']['information_title']   = ($this->input->post('information_title')) ?? '';
			$data['information']['information_caption'] = ($this->input->post('information_caption')) ?? '';
			$data['information']['information_url']     = ($this->input->post('information_url')) ?? '';
			$data['information']['information_open']    = ($this->input->post('information_open')) ?? '1900/01/01 00:00:00';
			$data['information']['information_close']   = ($this->input->post('information_close')) ?? '2100/12/31 00:00:00';
			$data['information']['information_topfit']  = ($this->input->post('information_topfit')) ?? 0;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_information/edit',
							'submenu_idx' => ($data['information']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
		}else{
			//成功
			//ボタン表示設定を編集確認に設定
			$data['btn_kirikae_flg'] = 1;
			
			//確認画面用データ設定
			$data['information']['update_flg']          = ($this->input->post('update_flg') ?? 0);
			$data['information']['information_id']      = ($this->input->post('information_id') ?? 0);
			$data['information']['information_date']    = ($this->input->post('information_date') ?? date("Y/m/d 00:00:00"));
			$data['information']['information_type']    = ($this->input->post('information_type') ?? 0);
			$data['information']['information_title']   = ($this->input->post('information_title') ?? '');
			$data['information']['information_caption'] = ($this->input->post('information_caption') ?? '');
			$data['information']['information_url']     = ($this->input->post('information_url') ?? '');
			$data['information']['information_open']    = ($this->input->post('information_open') ?? '1900/01/01 00:00:00');
			$data['information']['information_close']   = ($this->input->post('information_close') ?? '2100/12/31 00:00:00');
			$data['information']['information_topfit']  = ($this->input->post('information_topfit') ?? 0);
			
			//セッションへ検証済みデータを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['information']));
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_information/confirm',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ更新及び更新完了フォーム表示
	//----------------------------------------------
	function commit(){
		
		//検証済みセッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		if(isset($edit_form_data['information_id']) && $edit_form_data['information_id'] != ""){
			$data = array();
			//ID値有りで登録処理
			//検証済データに学校IDを追加
//			$edit_form_data['school_id'] = $this->libauth->get_school_id();
			
			//モデル読み込み
			$this->load->model('model_information_wp');
			
			//更新用引数設定
			$update_param = array(
				'data'   => $edit_form_data,
			);
			//データ更新
			$data = $this->model_information_wp->update_information($update_param);
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_information/commit',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_information');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 3,
							'view_data'   => $data,
						);
			//エラーフォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($information_id){
		//モデル読み込み
		$this->load->model('model_information_wp');
		
		//削除用引数設定
		$delete_param = array(
			'information_id' => $information_id,
		);
		//データ削除
		$data = $this->model_information_wp->delete_information($delete_param);
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//リスト画面表示
		header("Location:/cms_information/");
		exit();
	}
	
	//----------------------------------------------
	//サブメニュー作成
	//----------------------------------------------
	function _create_sub_menu($param){
		//引数設定
		$param = array_merge(
						array(
							"submenu_idx" => 0,
							"sel_month"   => 0,
						),
						$param
					);
		//メニュー表示用配列初期化
		$sub_menu = array();
		
		//当月・前月・前々月リンク設定
		//現在日時取得
		$now = time();
		//当月・前月の1日を取得
		$thismonth  = strtotime(date('Y/m/1', $now));
		$prevmonth  = strtotime(date('Y/m/1', $thismonth - (1 * $this->day_second)));
		$prev2month = strtotime(date('Y/m/1', $prevmonth - (1 * $this->day_second)));
		//各月のパラメータ用数値を設定(yyyymm形式）
		$thismonth_pram  = date('Ym', $thismonth);
		$prevmonth_pram  = date('Ym', $prevmonth);
		$prev2month_pram = date('Ym', $prev2month);
		
		//アンカー設定
		//各ページ共通
		$sub_menu[2] = $param['sel_month'] != $thismonth_pram ? 
							anchor("cms_information/index/" . $thismonth_pram, date('Y年m月', $thismonth)) :
							date('Y年m月', $thismonth);
		$sub_menu[3] = $param['sel_month'] != $prevmonth_pram ? 
							anchor("cms_information/index/" . $prevmonth_pram, date('Y年m月', $prevmonth)) :
							date('Y年m月', $prevmonth);
		$sub_menu[4] = $param['sel_month'] != $prev2month_pram ? 
							anchor("cms_information/index/" . $prev2month_pram, date('Y年m月', $prev2month)) :
							date('Y年m月', $prev2month);
		//ページ別
		switch($param['submenu_idx']){
			case 1://お知らせ一覧
				$sub_menu[1] = anchor("cms_information/newdata", "新規登録");
				break;
			
			case 2://新規登録
				$sub_menu[1] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_information/newdata", "新規登録");
				break;
		}
		return $sub_menu;
	}
	
	//----------------------------------------------
	//ビュー表示
	// [2012/10/12]タグドロップダウン用データ取得を追加
	//----------------------------------------------
	function _display_view($param) {
		//引数設定
		$param = array_merge(
						array(
							'view_name'   => '',
							'submenu_idx' => 0,
							'view_data'   => array(),
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//ドロップダウン作成用引数設定
		$drop_param = array(
					"school_id" => $this->libauth->get_school_id(),
					);
		//タグドロップダウン用データ取得
		$param['view_data']['tags_dropdown']  = $this->_get_tag_list_array($drop_param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		//ビューファイル読み込み
		$this->load->view($param['view_name'], $param['view_data']);
		
		//初回訪問判定用データ設定
		$this->session->set_flashdata(get_class($this), TRUE);
		
	}

	//----------------------------------------------
	// [2012/10/12]タグ一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_tag_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_information_wp');
		
		//一覧ドロップダウン生成
		$tags         = $this->model_information_wp->get_tag_dropdown_list($param);
		$data['tags'] = array();

		if( count($tags) != 0 ) {
			$data['tags']['']='';
			if(isset($tags['タグなし'])){
				$data['tags']['タグなし'] = 'タグなし';
				unset($tags['タグなし']);
			}
			foreach($tags as $tagKey => $cnt){
				$data['tags'][$tagKey] = $tagKey;
			}
		}

		return $data['tags'];
	}
	
	//----------------------------------------------
	//日付形式チェック
	//----------------------------------------------
	function date_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('date_check', $this->lang->line_or_def('error_date','%sの日付の形式が間違っています。') );
		
		//入力パターン正規表現設定("9999/99/99")
		$reg_pat = "^([0-9]{4})[-/ \.]([01]?[0-9])[-/ \.]([0123]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return checkdate($parts[2], $parts[3], $parts[1]);
		} else {
			return FALSE;
		}
	}
	
	//----------------------------------------------
	//日付時刻形式チェック
	//----------------------------------------------
	function datetime_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_datetime','%sの日時の形式が間違っています。') );
		
		//入力パターン正規表現設定("9999/99/99 99:99:99")
		$reg_pat = "^([0-9]{4})[-/ \.]([01]?[0-9])[-/ \.]([0123]?[0-9])[ ]([012]?[0-9])[:\.]([0-6]?[0-9])[:\.]([0-6]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return checkdate($parts[2], $parts[3], $parts[1]) && $this->_checktime($parts[4], $parts[5], $parts[6]);
		} else {
			return FALSE;
		}
	}
	
	//----------------------------------------------
	//期間チェック
	//----------------------------------------------
	function period_check($eddate, $stdate){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('period_check', $this->lang->line_or_def('error_period','期間の指定が間違っています。') );
		
		//削除セパレータ文字設定("/:- ")
		$rep_str = array('/', ':', '-', ' ');
		
		//入力文字列を取得
		$start_date = $this->input->post($stdate);
		$end_date   = $eddate;
		
		//セパレータを削除
		$start_date = str_replace($rep_str,'',$start_date);
		$end_date   = str_replace($rep_str,'',$end_date);
		
		//判定
		if ($start_date > $end_date){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	//----------------------------------------------
	//時間チェック
	//----------------------------------------------
	function _checktime($hour, $min, $sec){
		if ($hour < 0 || $hour > 23){
			return FALSE;
		}
		if ($min < 0 || $min > 59){
			return FALSE;
		}
		if ($sec < 0 || $sec > 59){
			return FALSE;
		}
		return TRUE;
	}
} 

/*End of File program.php*/
