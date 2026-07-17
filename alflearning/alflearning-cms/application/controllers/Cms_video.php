<?php
#[AllowDynamicProperties]
class Cms_video extends CI_Controller {
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $day_second            = 86400;			//日数計算用、一日秒数
	private $video_dir      = '';			//資料アップロードディレクトリ
													//アップロード可能ファイルタイプ
//	private $upload_types          = 'xls|xlsm|doc|docx|ppt|pptx|pdf|jpg|jpeg|gif|png';
	private $upload_types          = '*';	//パワポがうまく取れない
//	private $upload_error_messages = array( 		//アップロードエラー一覧
//										1=> 'ファイルサイズが大きすぎます', 
//											'ファイルサイズが大きすぎます', 
//											'ファイルが途中までしかアップロードされていません', 
//											'ファイルを指定してください', 
//										6=> 'テンポラリフォルダがありません', 
//											'書き込みに失敗しました', 
//											'内部エラーのため、アップロードを中止しました'
//										);
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct() {
		//Controllerクラスのコンストラクタ実行
		parent::__construct();
		
		// [2012/10/03]
		$this->load->helper('string_inspection_helper');
		
		//資料アップロードディレクトリの指定（appconfig.phpより取得）
		$this->video_dir = $this->config->item('video_dir');
		
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
			//権限が無い場合はトップページにリダイレクト(トップページの場合はログアウト）
			$work_auth = $this->libauth->get_teacher_auth();
			if($work_auth['video'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理のビデオ：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'video'))){
			redirect('admin_top');
		}

		// [20131118-NICHIBENREN_KENSHU-106]コンテンツ（本家的ビデオ）は、管理者（日弁連）以外表示しないように修正
		if($this->libauth->get_bar_association_id() != 1){
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	//一覧表示
	// [2012/10/12]検索条件、講座・タグの追加
	// [2012/10/12]ページ移動時の検索条件が維持できるよう修正
	// [2012/11/15]ALF Stream statusをテーブルから取得するように変更
	//----------------------------------------------
	function index($offset=0){
		// load language
		$this->lang->load('common');
		
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');

	//	$this->form_validation->set_rules('s_cource'    , $this->lang->line_or_def('common_course_name','講座名')    , 'trim|xss_clean');
		$this->form_validation->set_rules('s_tag'       , $this->lang->line_or_def('common_tag','タグ')              , 'trim|xss_clean');
		$this->form_validation->set_rules('s_free_word' , $this->lang->line_or_def('common_freeword','フリーワード') , 'trim|xss_clean');
		$this->form_validation->run();

		//資料モデル読み込み
		$this->load->model('model_video');

		if ( !$this->input->post() ){
			$data = $this->session->userdata('video_search_cond') ?: array(
				's_cource' => 0,
				's_tag'=>'',
				's_free_word' => '',
			);
		} else {
			//データ取得
			$data['s_cource']    = 0; //$this->input->get('s_cource');
			$data['s_tag']       = ($this->input->post('s_tag') ?? '');
			$data['s_free_word'] = ($this->input->post('s_free_word') ?? '');
			$this->session->set_userdata('video_search_cond', $data);
		}

		// 初期表示時のリスト非表示対応
		$first_show_cource = $data['s_cource'];
		if( ($data['s_tag']===FALSE) && ($data['s_free_word']===FALSE) ){
			$first_show_cource = 99999;	// 0より大きい場合のみ条件付加対象のため
		}

		$material_list = $this->model_video->get_material_list(array(
			'school_id' 	=> $this->libauth->get_school_id(),
			'offset'    	=> $offset,
			'rowcount'  	=> $per_page,
			's_cource'		=> $first_show_cource,		//search
			's_tag'			=> $data['s_tag'],			//search
			's_free_word'	=> $data['s_free_word'],	//search
		));

		$data['video_list'] = $material_list['items'];

		// streamステータス取得  [2012/11/15]取得先変更のため無効化
	//	for($i = 0; isset($data['video_list'][$i]); $i++){
	//		$data['video_list'][$i]['streamStatus'] = $this->model_video->getStreamStatus(array(
	//			'idkey'			=> $data['video_list'][$i]['idkey'],
	//		));
	//	}

		//ページネーション設定
		$config['base_url']		= base_url().'/cms_video/index';
		$config['per_page']		= $per_page;
		$config['total_rows']	= $material_list['cnt'];
		$config['first_link']	= '&lt;&lt;';
		$config['last_link']	= '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		// [2012/10/12]
		if(!empty($_GET)){
			$data['pagination'] = preg_replace('/(href=".+?)(")/i', '$1?'.http_build_query($_GET).'$2', $data['pagination']);
		}
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_video/index',
						'submenu_idx' => 2,
						'view_data'   => $data,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//新規フォーム表示
	// [2012/08/20]ビデオ講座マスタの取得処理の追加
	//----------------------------------------------
	function newdata($video_id = 0){
		//表示用変数の初期化
		$data = array();
		
		//モデル読み込み
		$this->load->model('model_video');

		// 権限確認
		$auth_param = array(
			'login_teacher_id' => $this->libauth->get_teacher_id(),
		);
		//データ削除
//		$auth_data = $this->model_video->material_newdata_auth_check($auth_param);
//
//		// 権限がない場合、エラー画面を表示
//		if($auth_data['auth_newdata'] == 0){
//			//戻り先設定
//			$data['returnurl']     = site_url('cms_video');
//			$data['error_message'] = '新規登録権限がありません<br />ログインし直してください';
//			
//			//ビュー設定引数設定
//			$disp_param = array(
//							'view_name'   => 'material_error',
//							'submenu_idx' => 4,
//							'view_data'   => $data,
//						);
//			//確認フォーム表示
//			$this->_display_view($disp_param);
//		}else{
//		}

		//データ取得用引数設定[2012/08/06]
		$data_param = array(
						'video_id' => (intval($video_id) ?? 0),
					);
		//データ取得
		$db_data = $this->model_video->get_material($data_param);
		
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
			
		// [2012/12/07]専属タグの初期化
		$exclusive_tag          = array('', '', '', '', '');
		$exclusive_book_library = array(-1, -1, -1, -1, -1);
		$exclusive_page_number  = array(-1, -1, -1, -1, -1);
		$exclusive_status       = array( 9,  9,  9,  9,  9);
		
		// [2012/08/06]
		if(!empty($db_data)){
			$data['video'] = $db_data;
			$data['video']['update_flg']               = 1;
			$data['video']['local_file']               = '';

			//[2012/08/20]ビデオ講座マスタの取得処理
			$data['video']['video_lectures']       = array();
			//  ビデオ講座マスタから講座ID取得
			$lectures = $this->model_video->get_video_lectures($data['video']);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['video']['video_lectures'][$idx] = $lecture['cource_id'];
				}
			}

			// [2012/12/07]専属タグの情報取得
			$exclusive_db_data = $this->model_video->get_exclusive_book_library_video($data_param);
			if(!empty($exclusive_db_data)){
				//$index_no = -1;
				foreach($exclusive_db_data as $index_no => $exclusive_data){
					//$index_no = $index_no + 1;
					$exclusive_tag[$index_no]          = $exclusive_data['exclusive_tag'];
					$exclusive_book_library[$index_no] = $exclusive_data['book_library_id'];
					$exclusive_page_number[$index_no]  = $exclusive_data['page_num'];
					$exclusive_status[$index_no]       = $exclusive_data['status'];
				}
			}
			// [2012/12/07]専属タグの情報格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;
		}else{
			//初期表示設定
			$data['video']['update_flg']               = 0;
			$data['video']['video_id']                 = 0;
			$data['video']['video_name']               = '';
			$data['video']['video_logic_name']         = '';
			$data['video']['video_lectures']           = array();	//[2012/08/20]
			$data['video']['video_caption']            = '';
			$data['video']['video_tags']               = '';
			$data['video']['local_reading_flag']       = '0';
			$data['video']['local_reading_open']       = '2000/01/01 00:00:00';
			$data['video']['local_reading_close']      = date("Y/m/d H:i:s",strtotime("+1 month"));
			$data['video']['local_reading_close_flag'] = '0';
			$data['video']['local_file']               = '';

			$data['video']['sound_only']               = '0';
			$data['video']['video_popup']              = '1';
			$data['video']['type_x15']                 = '0';

			// [2012/12/07]専属タグの情報格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;
		}
		
		//セッションへDB取得データを書き込み
		$this->session->set_userdata('edit_form_data',serialize($data['video']));
		
		//ビュー設定引数設定[2012/08/06]edit ⇒ newdata1
		$disp_param = array(
						'view_name'   => 'cms_video/newdata1',
						'submenu_idx' => 3,
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
			redirect('/cms_video/');
		}

		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		// video_id あり
		//if(isset($edit_form_data['video_id']) &&  $edit_form_data['video_id'] <> ''){
		if( (isset($edit_form_data['video_id'])) && ($edit_form_data['video_id'] <> '') ){
			$edit_form_data['video_id'] = intval($edit_form_data['video_id']) ?? 0;
			//画面表示用データ設定
			$data['video'] = $edit_form_data;

			$this->load->model('model_video');
			$stream_param = array(
							'video_id'		=> $data['video']['video_id'],
							'contract_param'	=> $this->libauth->get_login_school_contract_param($data['video']['school_id']),
							'idkey'			=> $data['video']['idkey'],
						);
			// チャプターの取得（ONLINE時のみ）
			if ($edit_form_data['alfstream_status'] == 'ONLINE') {
				$data['chapter_data'] = $this->model_video->get_video_chapter($stream_param);
			} else {
				$data['chapter_data'] = [];
			}

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/edit',
							'submenu_idx' => ($data['video']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_video');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	// 切り出しフォーム表示
	//----------------------------------------------
	function edit_moviecut(){
		//セッションデータ取得
		$edit_form_data = unserialize($this->session->userdata('edit_form_data'));
		
		// video_id あり、alfstream_status ONLINE、parent_idkey is NULL
		//if(isset($edit_form_data['video_id']) &&  $edit_form_data['video_id'] <> ''){
		if( (isset($edit_form_data['video_id'])) && ($edit_form_data['video_id'] <> '')  && ($edit_form_data['alfstream_status'] == 'ONLINE') && (empty($edit_form_data['parent_idkey'])) ){
			$edit_form_data['video_id'] = intval($edit_form_data['video_id']) ?? 0;

			//画面表示用データ設定
			$data['video'] = $edit_form_data;
			$data['video']['video_logic_name'] = date('Ymd').'_'.$data['video']['video_logic_name'];

			// 既存のデータを使用して新たにレコード作成
			$data['video']['update_flg'] = 0;
			$data['video']['video_id']   = 0;

			$data['video']['sound_only'] = 0;
			$data['video']['video_popup'] = 0;
			$data['video']['type_x15'] = 0;

			// 専属タグの初期化
			$data['video']['exclusive_tag']          = array('', '', '', '', '');
			$data['video']['exclusive_book_library'] = array(-1, -1, -1, -1, -1);
			$data['video']['exclusive_page_number']  = array(-1, -1, -1, -1, -1);
			$data['video']['exclusive_status']       = array( 9,  9,  9,  9,  9);
			
			// 各ドロップダンリストの初期化
			$data['video']['cut_start_hour']   = 0;
			$data['video']['cut_start_minute'] = 0;
			$data['video']['cut_start_second'] = 0;
			$data['video']['cut_end_hour']     = 0;
			$data['video']['cut_end_minute']   = 0;
			$data['video']['cut_end_second']   = 0;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/edit_moviecut',
							'submenu_idx' => ($data['video']['update_flg']==0 ? 2 : 3),
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
			
		}else{
			//セッションデータ無しはエラーフォーム表示
			//戻り先設定
			$data['returnurl'] = site_url('cms_video');
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'session_error',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}
	}
	
	//----------------------------------------------
	//詳細表示フォーム表示
	// [2012/08/20]ビデオ講座マスタの取得処理を追加
	//----------------------------------------------
	function detail($video_id = 0){
		//セッションデータのクリア
		$this->session->unset_userdata('edit_form_data');
		
		//モデル読み込み
		$this->load->model('model_video');
		
		//データ取得用引数設定
		$data_param = array(
			'video_id' => (intval($video_id) ?? 0),
		);
		//データ取得
		$db_data = $this->model_video->get_material($data_param);
		
		if(!empty($db_data)){
			//データ有り時
			//ボタン切り替えフラグ設定
			$data['btn_kirikae_flg'] = 2;
			
			//表示用データ設定
			$data['video'] = $db_data;
			$data['video']['update_flg'] = 1;

			// StreamStatusから動画情報（ビデオ時間・親idkey・親ビデオID・親ビデオ論理名）
			$stream_param = array(
				'video_id'		=> $data['video']['video_id'],
				'contract_param'	=> $this->libauth->get_login_school_contract_param($data['video']['school_id']),
				'idkey'			=> $data['video']['idkey'],
			);
			$alfstream_status = $this->model_video->get_video_alfstream_status($stream_param);	//00:00:28

			// 親ビデオIDの取得（親がいない場合は、0が入る）
			$data['video']['parent_video_id'] = $alfstream_status['parent_video_id'];

			$data['video']['video_time'] = $alfstream_status['alfstream_duration'];
			//list($hour_data, $minute_data, $second_data) = explode(":", $alfstream_status['alfstream_duration']);
			if (!empty($alfstream_status['alfstream_duration'])) {
				list($hour_data, $minute_data, $second_data) = explode(":", $alfstream_status['alfstream_duration']);
			} else {
				$hour_data = $minute_data = $second_data = 0; // デフォルト値を設定
			}			/*  [時 = 0]のとき
					時＝0
					[分 = 0]ならば
						分＝0、秒＝取得した秒
					[分 > 0]ならば
						分＝取得した分、秒＝59
				[時 > 0]ならば
					時＝取得した時、分＝59、秒＝59 */
			if(intval($hour_data) == 0){
				$data['video']['max_hour']   = 0;
				
				if(intval($minute_data) == 0){
					$data['video']['max_minute'] = 0;
					$data['video']['max_second'] = intval($second_data);
				}else{
					$data['video']['max_minute'] = intval($minute_data);
					$data['video']['max_second'] = 59;
				}
			}else{
				$data['video']['max_hour']   = intval($hour_data);
				$data['video']['max_minute'] = 59;
				$data['video']['max_second'] = 59;
			}
			//print $data['video']['max_hour']."--".$data['video']['max_minute']."--".$data['video']['max_second'];

			//チャプターの取得
			$data['chapter_data'] = $this->model_video->get_video_chapter($stream_param);

			//[2012/08/20]ビデオ講座マスタの取得処理
			$data['video']['video_lectures']       = array();
			//  ビデオ講座マスタから講座ID取得
			$lectures = $this->model_video->get_video_lectures($data_param);
			$idx = -1;
			if(isset($lectures)){
				foreach($lectures as $lecture){
					$idx++;
					$data['video']['video_lectures'][$idx] = $lecture['cource_id'];
				}
			}
			//  講座IDの講座名の取得
			$data['video']['video_lectures_name'] = array();
			$this->load->model('model_cource');
			if($data['video']['video_lectures']){
				foreach($data['video']['video_lectures'] as $idx => $lecture){
					//データ取得用引数設定
					$name_param = array(
									'cource_id' => $lecture,
								);
				//	$data['video']['video_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					if($this->model_cource->get_name($name_param)){
						$data['video']['video_lectures_name'][$idx] = $this->model_cource->get_name($name_param);
					}
				}
				asort($data['video']['video_lectures_name']);
			}

			// [2012/12/07]専属タグの初期化
			$exclusive_tag          = array('', '', '', '', '');
			$exclusive_book_library = array(-1, -1, -1, -1, -1);
			$exclusive_page_number  = array(-1, -1, -1, -1, -1);
			$exclusive_status       = array( 9,  9,  9,  9,  9);

			// [2012/12/07]専属タグの情報取得
			$exclusive_db_data = $this->model_video->get_exclusive_book_library_video($data_param);
			if(!empty($exclusive_db_data)){
				//$index_no = -1;
				foreach($exclusive_db_data as $index_no => $exclusive_data){
					//$index_no = $index_no + 1;
					$exclusive_tag[$index_no]          = $exclusive_data['exclusive_tag'];
					$exclusive_book_library[$index_no] = $exclusive_data['book_library_id'];
					$exclusive_page_number[$index_no]  = $exclusive_data['page_num'];
					$exclusive_status[$index_no]       = $exclusive_data['status'];
				}
			}

			// [2012/12/07]専属タグの情報格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;

			// 日弁連
			//if( $_SERVER['HTTP_REFERER']){
				//$data['video']['history_back_url'] = $_SERVER['HTTP_REFERER'];
				$data['video']['history_back_url'] = "/cms_video/";
			//}else{
			//	$data['video']['history_back_url'] = 'history.back()';
			//}

			//セッションへDB取得データを書き込み
			$this->session->set_userdata('edit_form_data',serialize($data['video']));
			
			//[2012/07/24]
			$hData['idkey'] = $data['video']['idkey'];
//print var_Dump($hData);
//			$data['history_data'] = $this->model_video->get_video_history($hData);
			$data['history_data'] = $this->model_video->get_video_reading_history(array(
				'student_id'	=> 0,
				'video_id'		=> $data['video']['video_id'],
			));
//print var_Dump($history_data);
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/confirm',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//確認フォーム表示
			$this->_display_view($disp_param);
		}else{
			//データ無し時
			//一覧に戻る
			//$this->index();
			header("Location:/cms_video/");
			exit();
		}
	}

	//----------------------------------------------
	// [2012/08/06]新規登録（テーブル）
	// [2012/08/20]ビデオ講座マスタ更新処理を追加
	// [2012/10/03]ビデオタグ精査の追加・ビデオ検索インデックス更新処理を追加
	//----------------------------------------------
	function new_table(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'       , $this->lang->line_or_def('common_flg','flg')              , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('material_id'      , $this->lang->line_or_def('common_id','ID')                , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('video_logic_name' , $this->lang->line_or_def('common_file_name','ファイル名') , 'trim|xss_clean|required'); // 論理ファイル名
		$this->form_validation->set_rules('video_caption'    , $this->lang->line_or_def('common_caption','説明')         , 'trim|xss_clean');
		$this->form_validation->set_rules('video_tags'       , $this->lang->line_or_def('common_tag','タグ')             , 'trim|xss_clean');
		$this->form_validation->set_rules('video_lectures[]'   , $this->lang->line_or_def('common_position_course','所属講座')  , 'callback_check_required_checkbox');

		$this->form_validation->set_rules('sound_only'       , $this->lang->line_or_def('common_sound_only','音声のみ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('video_popup'      , $this->lang->line_or_def('common_video_popup','確認ポップアップ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('type_x15'         , $this->lang->line_or_def('common_type_x15','1.5倍速')  , 'trim|xss_clean');

		$this->form_validation->set_rules('local_reading_flag'      , $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧')     , 'trim|xss_clean|required');
		$this->form_validation->set_rules('local_reading_open'      , $this->lang->line_or_def('common_local_reading_start','ローカル閲覧開始') , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('local_reading_close'     , $this->lang->line_or_def('common_local_reading_end','ローカル閲覧終了') , 'trim|xss_clean|callback_datetime_check|callback_period_check[local_reading_open]');

		// [2012/12/07]選択された「専属タグ」の取得
		$exclusive_tag          = $this->input->post('exclusive_tag') ? $this->input->post('exclusive_tag'):array();
		$exclusive_book_library = $this->input->post('exclusive_book_library') ? $this->input->post('exclusive_book_library'):array();
		$exclusive_page_number  = $this->input->post('exclusive_page_number') ? $this->input->post('exclusive_page_number'):array();
		$temp_exclusive_status  = $this->input->post('exclusive_status') ? $this->input->post('exclusive_status'):array();
		$exclusive_status = array(9, 9, 9, 9, 9);
		if(!empty($temp_exclusive_status)){
			foreach($temp_exclusive_status as $temp){
				$exclusive_status[$temp] = 0;
			}
		}
		$overlap_error = $this->_check_exclusive_tag(array(
			'video_id'               => (intval($this->input->post('video_id')) ?? 0),
			'exclusive_tag'          => $exclusive_tag,
			'exclusive_book_library' => $exclusive_book_library,
			'exclusive_page_number'  => $exclusive_page_number,
			'exclusive_status'       => $exclusive_status,
		));
		
		//検証
	//	if($this->form_validation->run() == FALSE){
		if($this->form_validation->run() == FALSE || ($overlap_error != "") ){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['video']['update_flg']       = $this->input->post('update_flg');
			$data['video']['video_id']         = (intval($this->input->post('video_id')) ?? 0);
			$data['video']['material_id']      = $this->input->post('material_id');
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = '';
			$data['video']['video_tags']       = '';
			$data['video']['video_lectures']           = $this->input->post('video_lectures')?$this->input->post('video_lectures'):array();
			$data['video']['local_reading_flag']       = '';
			$data['video']['local_reading_open']       = '';
			$data['video']['local_reading_close']      = '';
			$data['video']['local_reading_close_flag'] = '';

			$data['video']['sound_only']               = $this->input->post('sound_only');
			$data['video']['video_popup']              = $this->input->post('video_popup');
			$data['video']['type_x15']                 = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}
			
			// [2012/12/07]選択された「専属タグ」の格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;
			
			//エラーメッセージ設定
			$data['overlap_error'] = $overlap_error;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/newdata1',
							'submenu_idx' => ($data['video']['update_flg']==0 ? 3 : 4),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
			return;
		}else{
			//モデル読み込み
			$this->load->model('model_video');
			
			//更新用データ設定
			$data['video']['update_flg']       = $this->input->post('update_flg');
			$data['video']['video_id']         = (intval($this->input->post('video_id')) ?? 0);
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = $this->input->post('video_caption');
		//	$data['video']['video_tags']       = preg_replace('/[、，,]/u', ',', $this->input->post('video_tags'));
			$data['video']['video_tags']       = tags_inspection($this->input->post('video_tags'));
			$data['video']['video_lectures']   = $this->input->post('video_lectures');

			$data['video']['sound_only']       = $this->input->post('sound_only');
			$data['video']['video_popup']      = $this->input->post('video_popup');
			$data['video']['type_x15']         = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}

			$data['video']['local_reading_flag']      = $this->input->post('local_reading_flag');
			if($this->input->post('local_reading_flag') == '0'){
				// ローカル閲覧：禁止
				$data['video']['local_reading_open']  = '2000/01/01 00:00:00';
				$data['video']['local_reading_close'] = NULL;
			}else{
				// ローカル閲覧：許可
				$data['video']['local_reading_open']  = $this->input->post('local_reading_open');
				if($this->input->post('local_reading_close_flag') == '1'){
					//閲覧期限なし
					$data['video']['local_reading_close']     = NULL;
				}else{
					//閲覧期限あり
					$data['video']['local_reading_close']     = $this->input->post('local_reading_close');
				}
			}

			$data['video']['local_file']       = $this->input->post('local_file');
			$data['video']['school_id']        = $this->libauth->get_school_id();
			$data['video']['idkey']            = $this->input->post('idkey');

			//データ更新 
			$retdata = $this->model_video->insert_video($data['video']);
//echo var_Dump($retdata);
			$data['video']['idkey']            = $retdata['idkey'];
			$data['video']['upload_url']       = $retdata['upload_url'];
			//[2012/08/20]ビデオ講座マスタ更新処理
			$data_param = array(
							'video_id' => $retdata['lastInsertId'],
							'data'     => $data['video'],
						);
			$this->model_video->update_video_lectures($data_param);
			$this->model_video->update_video_search_index($data_param);

			// [2012/12/07]選択された「専属タグ」の保存処理
			$exclusive_data_param = array(
							'video_id' => $retdata['lastInsertId'],
							'exclusive_tag'          => $exclusive_tag,
							'exclusive_book_library' => $exclusive_book_library,
							'exclusive_page_number'  => $exclusive_page_number,
							'exclusive_status'       => $exclusive_status,
						);
			$this->model_video->update_exclusive_book_library_video($exclusive_data_param);

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/newdata2',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
//var_dump($disp_param);
//exit();
			//新規登録（ビデオ）フォーム表示
			$this->_display_view($disp_param);
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}

	//----------------------------------------------
	// [2012/08/06]更新完了フォーム表示（streamからの戻り先）
	//----------------------------------------------
	function new_commit(){

		//ビュー設定引数設定
		$disp_param = array(
			'view_name'   => 'cms_video/commit',
			'submenu_idx' => 4,
			'view_data'   => [],
		);

		//完了フォーム表示
		$this->_display_view($disp_param);
		
		//セッションデータのクリア
	//	$this->session->unset_userdata('edit_form_data');
	}

	//----------------------------------------------
	//更新確認フォーム表示
	// [2012/08/20]ビデオ講座マスタ更新処理を追加
	// [2012/10/03]ビデオタグ精査の追加・ビデオ検索インデックス更新処理を追加
	//----------------------------------------------
	function commit(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//アップロードエラー一覧
		$upload_error_messages = array( 
			1=> $this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
			    $this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
			    $this->lang->line_or_def('error_file_stop_upload','ファイルが途中までしかアップロードされていません'), 
			    $this->lang->line_or_def('error_file_no_select','ファイルを指定してください'), 

			6=> $this->lang->line_or_def('error_file_no_folder','テンポラリフォルダがありません'), 
			    $this->lang->line_or_def('error_file_no_write','書き込みに失敗しました'), 
			    $this->lang->line_or_def('error_file_internal','内部エラーのため、アップロードを中止しました')
		);
		
		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'       , $this->lang->line_or_def('common_flg','flg')              , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('material_id'      , $this->lang->line_or_def('common_id','ID')                , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('video_logic_name' , $this->lang->line_or_def('common_file_name','ファイル名') , 'trim|xss_clean|required'); // 論理ファイル名
		$this->form_validation->set_rules('video_caption'    , $this->lang->line_or_def('common_caption','説明')         , 'trim|xss_clean');
		$this->form_validation->set_rules('video_tags'       , $this->lang->line_or_def('common_tag','タグ')             , 'trim|xss_clean');
		$this->form_validation->set_rules('video_lectures[]'   , $this->lang->line_or_def('common_position_course','所属講座')  , 'callback_check_required_checkbox');

		$this->form_validation->set_rules('sound_only'       , $this->lang->line_or_def('common_sound_only','音声のみ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('video_popup'      , $this->lang->line_or_def('common_video_popup','確認ポップアップ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('type_x15'         , $this->lang->line_or_def('common_type_x15','1.5倍速')  , 'trim|xss_clean');

		$this->form_validation->set_rules('local_reading_flag'      , $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧')     , 'trim|xss_clean|required');
		$this->form_validation->set_rules('local_reading_open'      , $this->lang->line_or_def('common_local_reading_start','ローカル閲覧開始') , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('local_reading_close'     , $this->lang->line_or_def('common_local_reading_end','ローカル閲覧終了') , 'trim|xss_clean|callback_datetime_check|callback_period_check[local_reading_open]');


		$this->form_validation->set_rules('local_file'       , $this->lang->line_or_def('common_file','ファイル')        , 'trim|xss_clean');
		
		// [2012/12/07]選択された「専属タグ」の取得
		$exclusive_tag          = $this->input->post('exclusive_tag')?$this->input->post('exclusive_tag'):array();
		$exclusive_book_library = $this->input->post('exclusive_book_library')?$this->input->post('exclusive_book_library'):array();
		$exclusive_page_number  = $this->input->post('exclusive_page_number')?$this->input->post('exclusive_page_number'):array();
		$temp_exclusive_status  = $this->input->post('exclusive_status')?$this->input->post('exclusive_status'):array();
		$exclusive_status = array(9, 9, 9, 9, 9);
		if(!empty($temp_exclusive_status)){
			foreach($temp_exclusive_status as $temp){
				$exclusive_status[$temp] = 0;
			}
		}
		$overlap_error = $this->_check_exclusive_tag(array(
			'video_id'               => (intval($this->input->post('video_id') ?? 0)),
			'exclusive_tag'          => $exclusive_tag,
			'exclusive_book_library' => $exclusive_book_library,
			'exclusive_page_number'  => $exclusive_page_number,
			'exclusive_status'       => $exclusive_status,
		));

		// チャプターの取得
		$chapter_data = array();
		$chapter_list = $this->input->post('chapter_list')?$this->input->post('chapter_list'):array();		// 【99:99:99　】【99:99:99　文字列】
		foreach($chapter_list as $chapter){
			$temp['chapter_time'] = mb_substr($chapter, 0, 8);
			$temp['chapter_name'] = mb_substr($chapter, 9, 100);
			array_push($chapter_data, $temp);
		}
		$data['chapter_data'] = $chapter_data;
		//print var_dump($chapter_list);

		// ビデオ再生時間・選択可能最大時・分・秒 の取得
		$data['video']['video_time'] = $this->input->post('video_time');
		$data['video']['max_hour']   = $this->input->post('max_hour');
		$data['video']['max_minute'] = $this->input->post('max_minute');
		$data['video']['max_second'] = $this->input->post('max_second');
		
		//検証
	//	if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0) || ($overlap_error != "") ){
		if($this->form_validation->run() == FALSE || ($overlap_error != "") ){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['video']['update_flg']       = $this->input->post('update_flg');
			$data['video']['video_id']         = (intval($this->input->post('video_id') ?? 0));
			$data['video']['material_id']      = $this->input->post('material_id');
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = '';
			$data['video']['video_tags']       = '';
			$data['video']['video_lectures']           = $this->input->post('video_lectures')?$this->input->post('video_lectures'):array();
			$data['video']['local_reading_flag']       = '';
			$data['video']['local_reading_open']       = '';
			$data['video']['local_reading_close']      = '';
			$data['video']['local_reading_close_flag'] = '';
			$data['video']['local_file']       = '';

			$data['video']['sound_only']     = $this->input->post('sound_only');
			$data['video']['video_popup']    = $this->input->post('video_popup');
			$data['video']['type_x15']       = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}

			// [2012/12/07]選択された「専属タグ」の格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;

			//エラーメッセージ設定
		//	$data['upload_error'] = $this->upload_error_messages[$_FILES['local_file']['error']];
			//$data['upload_error']  = $upload_error_messages[$_FILES['local_file']['error']];
			$data['overlap_error'] = $overlap_error;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/edit',
							'submenu_idx' => ($data['video']['update_flg']==0 ? 3 : 4),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
			return;
		}else{
			//モデル読み込み
			$this->load->model('model_video');
			
			//更新用データ設定
			$data['video']['update_flg']       = $this->input->post('update_flg');
			$data['video']['video_id']         = (intval($this->input->post('video_id') ?? 0));
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = $this->input->post('video_caption');
		//	$data['video']['video_tags']       = preg_replace('/[、，,]/u', ',', $this->input->post('video_tags'));
			$data['video']['video_tags']       = tags_inspection($this->input->post('video_tags'));
			$data['video']['video_lectures']   = $this->input->post('video_lectures');

			$data['video']['sound_only']       = $this->input->post('sound_only');
			$data['video']['video_popup']      = $this->input->post('video_popup');
			$data['video']['type_x15']         = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}

			$data['video']['local_reading_flag']      = $this->input->post('local_reading_flag');
			if($this->input->post('local_reading_flag') == '0'){
				// ローカル閲覧：禁止
				$data['video']['local_reading_open']  = '2000/01/01 00:00:00';
				$data['video']['local_reading_close'] = NULL;
			}else{
				// ローカル閲覧：許可
				$data['video']['local_reading_open']  = $this->input->post('local_reading_open');
				if($this->input->post('local_reading_close_flag') == '1'){
					//閲覧期限なし
					$data['video']['local_reading_close']     = NULL;
				}else{
					//閲覧期限あり
					$data['video']['local_reading_close']     = $this->input->post('local_reading_close');
				}
			}

			$data['video']['local_file']       = $this->input->post('local_file');
			$data['video']['school_id']        = $this->libauth->get_school_id();
			$data['video']['idkey']            = $this->input->post('idkey');

			//データ更新
			$retdata = $this->model_video->update_video($data['video']);
			//[2012/08/20]ビデオ講座マスタ更新処理
			$data_param = array(
							'video_id' => $retdata['lastInsertId'],
							'data'     => $data['video'],
						);
			$this->model_video->update_video_lectures($data_param);
			$this->model_video->update_video_search_index($data_param);

			// [2012/12/07]選択された「専属タグ」の保存処理
			$exclusive_data_param = array(
							'video_id' => $retdata['lastInsertId'],
							'exclusive_tag'          => $exclusive_tag,
							'exclusive_book_library' => $exclusive_book_library,
							'exclusive_page_number'  => $exclusive_page_number,
							'exclusive_status'       => $exclusive_status,
						);
			$this->model_video->update_exclusive_book_library_video($exclusive_data_param);

			// チャプターの保存
			$chapter_data_param = array(
							'video_id' => $retdata['lastInsertId'],
							'data'     => $data['chapter_data'],
						);
			$this->model_video->update_video_chapter($chapter_data_param);

			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/commit',
							'submenu_idx' => 4,
							'view_data'   => $data,
						);
			//完了フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}
	
	//----------------------------------------------
	// 〓ビデオ切り出し用登録処理
	//----------------------------------------------
	function commit_moviecut(){
		// load language
		$this->lang->load('common');
		$this->lang->load('error');
		
		//アップロードエラー一覧
		$upload_error_messages = array( 
			1=> $this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
			    $this->lang->line_or_def('error_file_size_over','ファイルサイズが大きすぎます'), 
			    $this->lang->line_or_def('error_file_stop_upload','ファイルが途中までしかアップロードされていません'), 
			    $this->lang->line_or_def('error_file_no_select','ファイルを指定してください'), 
			6=> $this->lang->line_or_def('error_file_no_folder','テンポラリフォルダがありません'), 
			    $this->lang->line_or_def('error_file_no_write','書き込みに失敗しました'), 
			    $this->lang->line_or_def('error_file_internal','内部エラーのため、アップロードを中止しました')
		);

		//検証ルールの設定
		$this->form_validation->set_rules('update_flg'       , $this->lang->line_or_def('common_flg','flg')              , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('material_id'      , $this->lang->line_or_def('common_id','ID')                , 'trim|xss_clean|numeric');
		$this->form_validation->set_rules('video_logic_name' , $this->lang->line_or_def('common_file_name','ファイル名') , 'trim|xss_clean|required'); // 論理ファイル名
		$this->form_validation->set_rules('video_caption'    , $this->lang->line_or_def('common_caption','説明')         , 'trim|xss_clean');
		$this->form_validation->set_rules('video_tags'       , $this->lang->line_or_def('common_tag','タグ')             , 'trim|xss_clean');
		$this->form_validation->set_rules('video_lectures[]'   , $this->lang->line_or_def('common_position_course','所属講座')  , 'callback_check_required_checkbox');

		$this->form_validation->set_rules('sound_only'       , $this->lang->line_or_def('common_sound_only','音声のみ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('video_popup'      , $this->lang->line_or_def('common_video_popup','確認ポップアップ')  , 'trim|xss_clean|numeric|required');
		$this->form_validation->set_rules('type_x15'         , $this->lang->line_or_def('common_type_x15','1.5倍速')  , 'trim');


		$this->form_validation->set_rules('local_reading_flag'      , $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧')     , 'trim|xss_clean|required');
		$this->form_validation->set_rules('local_reading_open'      , $this->lang->line_or_def('common_local_reading_start','ローカル閲覧開始') , 'trim|xss_clean|required|callback_datetime_check');
		$this->form_validation->set_rules('local_reading_close'     , $this->lang->line_or_def('common_local_reading_end','ローカル閲覧終了') , 'trim|xss_clean|callback_datetime_check|callback_period_check[local_reading_open]');


		$this->form_validation->set_rules('local_file'       , $this->lang->line_or_def('common_file','ファイル')        , 'trim|xss_clean');
		
		// [2012/12/07]選択された「専属タグ」の取得
		$exclusive_tag          = $this->input->post('exclusive_tag')?$this->input->post('exclusive_tag'):array();
		$exclusive_book_library = $this->input->post('exclusive_book_library')?$this->input->post('exclusive_book_library'):array();
		$exclusive_page_number  = $this->input->post('exclusive_page_number')?$this->input->post('exclusive_page_number'):array();
		$temp_exclusive_status  = $this->input->post('exclusive_status')?$this->input->post('exclusive_status'):array();
		$exclusive_status = array(9, 9, 9, 9, 9);
		if(!empty($temp_exclusive_status)){
			foreach($temp_exclusive_status as $temp){
				$exclusive_status[$temp] = 0;
			}
		}
		$overlap_error = $this->_check_exclusive_tag(array(
			'video_id'               => (intval($this->input->post('video_id')) ?? 0),
			'exclusive_tag'          => $exclusive_tag,
			'exclusive_book_library' => $exclusive_book_library,
			'exclusive_page_number'  => $exclusive_page_number,
			'exclusive_status'       => $exclusive_status,
		));

		// ビデオ再生時間・選択可能最大時・分・秒 の取得
		$data['video']['video_time'] = $this->input->post('video_time');
		$data['video']['max_hour']   = $this->input->post('max_hour');
		$data['video']['max_minute'] = $this->input->post('max_minute');
		$data['video']['max_second'] = $this->input->post('max_second');
		
		// ビデオ再生時間の秒変換取得
		$video_time_array = explode(":", $data['video']['video_time']);
		$video_time = ($video_time_array[0] * 60 * 60) + ($video_time_array[1] * 60) + ($video_time_array[2] * 1);
		
		// 取得した切り出し部分の取得
		$data['video']['cut_start_hour']   = $this->input->post('cut_start_hour');
		$data['video']['cut_start_minute'] = $this->input->post('cut_start_minute');
		$data['video']['cut_start_second'] = $this->input->post('cut_start_second');
		$data['video']['cut_end_hour']     = $this->input->post('cut_end_hour');
		$data['video']['cut_end_minute']   = $this->input->post('cut_end_minute');
		$data['video']['cut_end_second']   = $this->input->post('cut_end_second');

		$cut_start  = $data['video']['cut_start_hour'] * 60 * 60;
		$cut_start += $data['video']['cut_start_minute'] * 60;
		$cut_start += $data['video']['cut_start_second'] * 1;

		$cut_end  = $data['video']['cut_end_hour'] * 60 * 60;
		$cut_end += $data['video']['cut_end_minute'] * 60;
		$cut_end += $data['video']['cut_end_second'] * 1;

		$cut_error_msg = "";
		if( ($cut_start == 0) || ($cut_end == 0) ){
			$cut_error_msg = "切り出し時間を指定してください";
		}elseif($cut_start >= $cut_end){
			$cut_error_msg = "切り出し時間の前後が逆転しています";
		}elseif( ($cut_start > $video_time) || ($cut_end > $video_time) ){
			$cut_error_msg = "切り出し時間の指定が再生時間を超過しています";
		}

		if(empty($overlap_error)){
			$overlap_error = $cut_error_msg;
		}else{
			$overlap_error = $overlap_error.'<br/>'.$cut_error_msg;
		}

		//検証
	//	if($this->form_validation->run() == FALSE || (isset($_FILES['local_file']) && $_FILES['local_file']['error'] != 0) || ($overlap_error != "") ){
		if($this->form_validation->run() == FALSE || ($overlap_error != "") ){
			//失敗
			//受け渡し変数初期化（未定義エラー回避の為）
			$data['video']['update_flg']       = (intval($this->input->post('video_id')) ?? 0);
			$data['video']['video_id']         = $this->input->post('video_id');
			$data['video']['material_id']      = $this->input->post('material_id');
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = '';
			$data['video']['video_tags']       = '';
			$data['video']['video_lectures']           = $this->input->post('video_lectures')?$this->input->post('video_lectures'):array();
			$data['video']['local_reading_flag']       = '';
			$data['video']['local_reading_open']       = '';
			$data['video']['local_reading_close']      = '';
			$data['video']['local_reading_close_flag'] = '';
			$data['video']['local_file']       = '';

			$data['video']['sound_only']       = $this->input->post('sound_only');
			$data['video']['video_popup']      = $this->input->post('video_popup');
			$data['video']['type_x15']         = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}

			// [2012/12/07]選択された「専属タグ」の格納
			$data['video']['exclusive_tag']          = $exclusive_tag ;
			$data['video']['exclusive_book_library'] = $exclusive_book_library;
			$data['video']['exclusive_page_number']  = $exclusive_page_number;
			$data['video']['exclusive_status']       = $exclusive_status;
		
			//エラーメッセージ設定
		//	$data['upload_error'] = $this->upload_error_messages[$_FILES['local_file']['error']];
			//$data['upload_error']  = $upload_error_messages[$_FILES['local_file']['error']];
			$data['overlap_error'] = $overlap_error;
			
			//ビュー設定引数設定
			$disp_param = array(
							'view_name'   => 'cms_video/edit_moviecut',
							'submenu_idx' => ($data['video']['update_flg']==0 ? 3 : 4),
							'view_data'   => $data,
						);
			//編集フォーム再表示
			$this->_display_view($disp_param);
			
			return;
		}else{
			//モデル読み込み
			$this->load->model('model_video');
			
			//更新用データ設定
			$data['video']['update_flg']       = $this->input->post('update_flg');
			$data['video']['video_id']         = (intval($this->input->post('video_id')) ?? 0);
			$data['video']['video_logic_name'] = $this->input->post('video_logic_name');
			$data['video']['video_caption']    = $this->input->post('video_caption');
			$data['video']['video_tags']       = tags_inspection($this->input->post('video_tags'));
			$data['video']['video_lectures']   = $this->input->post('video_lectures');

			$data['video']['sound_only']       = $this->input->post('sound_only');
			$data['video']['video_popup']      = $this->input->post('video_popup');
			$data['video']['type_x15']         = $this->input->post('type_x15');
			if( $data['video']['type_x15']=="" ){
				$data['video']['type_x15']                 = "0";
			}

			$data['video']['local_reading_flag']      = $this->input->post('local_reading_flag');
			if($this->input->post('local_reading_flag') == '0'){
				// ローカル閲覧：禁止
				$data['video']['local_reading_open']  = '2000/01/01 00:00:00';
				$data['video']['local_reading_close'] = NULL;
			}else{
				// ローカル閲覧：許可
				$data['video']['local_reading_open']  = $this->input->post('local_reading_open');
				if($this->input->post('local_reading_close_flag') == '1'){
					//閲覧期限なし
					$data['video']['local_reading_close']     = NULL;
				}else{
					//閲覧期限あり
					$data['video']['local_reading_close']     = $this->input->post('local_reading_close');
				}
			}

			$data['video']['local_file']       = $this->input->post('local_file');
			$data['video']['school_id']        = $this->libauth->get_school_id();
			$data['video']['idkey']            = $this->input->post('idkey');

			//データ更新
			$retdata = $this->model_video->update_video_moviecut($data['video']);
			//[2012/08/20]ビデオ講座マスタ更新処理
			$data_param = array(
				'video_id' => $retdata['lastInsertId'],
				'data'     => $data['video'],
			);
			$this->model_video->update_video_lectures($data_param);
			$this->model_video->update_video_search_index($data_param);

			// [2012/12/07]選択された「専属タグ」の保存処理
			$exclusive_data_param = array(
				'video_id'               => $retdata['lastInsertId'],
				'exclusive_tag'          => $exclusive_tag,
				'exclusive_book_library' => $exclusive_book_library,
				'exclusive_page_number'  => $exclusive_page_number,
				'exclusive_status'       => $exclusive_status,
			);
			$this->model_video->update_exclusive_book_library_video($exclusive_data_param);

			//ビュー設定引数設定
			$disp_param = array(
				'view_name'   => 'cms_video/commit',
				'submenu_idx' => 4,
				'view_data'   => $data,
			);
			//完了フォーム表示
			$this->_display_view($disp_param);
			
			//セッションデータのクリア
			$this->session->unset_userdata('edit_form_data');
		}
	}
	
	//----------------------------------------------
	//データ削除（論理削除）
	//----------------------------------------------
	function delete_item($videoId){
		//モデル読み込み
		$this->load->model('model_video');

		//データ削除
		$data = $this->model_video->delete_material($videoId);

		$this->session->unset_userdata('edit_form_data');

		redirect('/cms_video/');
	}
	
	//----------------------------------------------
	//サブメニュー作成
	//----------------------------------------------
	function _create_sub_menu($param){
		//引数設定
		$param = array_merge(
			array(
				'submenu_idx' => 0,
			),
			$param
		);
		
		//メニュー表示用配列初期化
		$sub_menu = array();
		
		switch($param['submenu_idx']){
			case 1://授業検索
				$sub_menu[1] = "検索";
				break;
			
			case 2://資料一覧
				$sub_menu[1] = anchor("cms_video", "検索");
				$sub_menu[2] = "一覧";
				$sub_menu[3] = anchor("cms_video/newdata/", "新規登録");
				break;
			
			case 3://資料新規登録
				$sub_menu[1] = anchor("cms_video/", "検索");;
				$sub_menu[2] = anchor("cms_video/", "一覧");
				$sub_menu[3] = "新規登録";
				break;
			
			default://上記以外
				$sub_menu[1] = anchor("cms_video/", "検索");;
				$sub_menu[2] = anchor("cms_video/", "一覧");
				$sub_menu[3] = anchor("cms_video/newdata/", "新規登録");
		}
		return $sub_menu;
	}
	
	//----------------------------------------------
	//ビュー表示
	// [2012/08/20]講座チェックボックス用データ取得を追加
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
		// 講座ドロップダウン用データ取得
		$param['view_data']['cources_dropdown']  = $this->_get_cource_list_array($drop_param);
		
		//講師ドロップダウン用データ取得
		$param['view_data']['teachers_dropdown'] = $this->_get_teacher_list_array($drop_param);
		
		//タグドロップダウン用データ取得
		$param['view_data']['tags_dropdown']  = $this->_get_tag_list_array($drop_param);
		
		//受講者チェックボックス用データ取得
		$this->load->model('model_student');
		$param['view_data']['students'] = $this->model_student->get_student_checkbox_list($drop_param);
		
		//授業名設定
		//モデル読み込み
		$this->load->model('model_class');
		//データ取得用引数設定
		$data_param = array(
					);
		//データ取得
		$param['view_data']['class_name'] = $this->model_class->get_name($param);
		
		// [2012/08/20]講座チェックボックス用データ取得
		$this->load->model('model_cource');
		$param['view_data']['lecture_cources'] = $this->model_cource->get_cource_checkbox_list($drop_param);

		// [2012/12/07]講座所属の図書室（図書室ID・図書室論理名・ページ数）を取得
		if( isset($param['view_data']['video']) ){
			$temp_cource_id = implode(",", $param['view_data']['video']['video_lectures']);
			
			$exclusive_param = array(
						"school_id"			=>	$this->libauth->get_school_id(),
						"cource_id"			=>	$temp_cource_id,
						);
			$book_library_exclusive = $this->_get_course_book_library_list_array($exclusive_param);
			
			$param['view_data']['book_library_exclusive']     = $book_library_exclusive['book_library_exclusive'];
			$param['view_data']['book_library_exclusive_num'] = $book_library_exclusive['book_library_exclusive_num'];
		}
		
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
		$this->load->model('model_video');
		
		//一覧ドロップダウン生成
		$tags         = $this->model_video->get_tag_dropdown_list($param);
		$data['tags'] = array();

		if( !empty($tags) ) {
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
	//講座一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_cource_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_cource');
		
		//一覧ドロップダウン生成
		$data['cource_list'] = $this->model_cource->get_cource_dropdown_list($param);
		$data['cources'] = array();
		if( !empty($data['cource_list']) ) {
			$data['cources'][''] = '';
			foreach ( $data['cource_list'] as $cource ) {
				$data['cources'][$cource['cource_id']] = $cource['cource_name'];
			}
		}
		
		return $data['cources'];
	}
	
	//----------------------------------------------
	//担当講師一覧ドロップダウン用配列取得
	//----------------------------------------------
	function _get_teacher_list_array($param){
		//引数設定
		$param = array_merge(
						array(
							'school_id'   => 0,
						),
						$param
					);
		//モデル読み込み
		$this->load->model('model_teacher');
		
		//一覧ドロップダウン生成
		$data['teacher_list'] = $this->model_teacher->get_teacher_dropdown_list($param);
		$data['teachers'] = array();
		if( !empty($data['teacher_list']) ) {
			$data['teachers'][''] = '';
			foreach ( $data['teacher_list'] as $teacher ) {
				$data['teachers'][$teacher['teacher_id']] = htmlspecialchars($teacher['teacher_name'], ENT_QUOTES, 'UTF-8');
			}
		}
		
		return $data['teachers'];
	}

	//----------------------------------------------
	// [2012/12/07]講座所属図書室一覧ドロップダウン要配列取得
	//----------------------------------------------
	function _get_course_book_library_list_array($param){
		//引数設定
		$param = array_merge(
					array(
						"school_id"			=>	0,
						"cource_id"			=>	'',
					),
					$param
				);
		// 値取得
		$this->load->model('model_book_library');
		$book_library_exclusive = $this->model_book_library->get_cource_book_library_exclusive($param);
		
		// 変数設定
		$data['book_library_exclusive']     = array();
		$data['book_library_exclusive_num'] = array();
		$data['book_library_exclusive'][-1] = '----';
		$data['book_library_exclusive_num'][-1] = '---';
		
		if( !empty($book_library_exclusive) ) {
			foreach($book_library_exclusive as $book_library){
				$data['book_library_exclusive'][$book_library['book_library_id']]     = $book_library['book_library_logic_name'];
				$data['book_library_exclusive_num'][$book_library['book_library_id']] = $book_library['page_num'];
			}
		}		
		return array(
			'book_library_exclusive' => $data['book_library_exclusive'],
			'book_library_exclusive_num' => $data['book_library_exclusive_num'],
			);
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
		
		//空白文字列がきた場合はTRUEを返す
		if (empty($date)) {
			return TRUE;
		}
		
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
	//時刻形式チェック
	//----------------------------------------------
	function time_check($date){
		// load language
		$this->lang->load('error');
		
		//エラーメッセージ設定
		$this->form_validation->set_message('datetime_check', $this->lang->line_or_def('error_time','%sの時刻の形式が間違っています。') );
		
		//入力パターン正規表現設定("99:99:99")
		$reg_pat = "^([012]?[0-9])[:\.]([0-6]?[0-9])[:\.]([0-6]?[0-9])$";
		
		//入力パターン判定
		if( mb_ereg($reg_pat, $date, $parts) ) {
			//日付形式判定
			return $this->_checktime($parts[1], $parts[2], $parts[3]);
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
		
		//空白文字列がきた場合はTRUEを返す
		if (empty($eddate)) {
			return TRUE;
		}
		
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

	//----------------------------------------------
	// [2012/11/30] [Ajax用]講座所属の図書室を取得
	//----------------------------------------------
	function get_cource_book_library_exclusive(){

//		$this->session->sess_update();	// 画面更新なしでセッション有効時間延長のため（しないとサムネイル取得ができない）
		//通常ではlast_activityをupdateしてくれない
//		$this->db->query($this->db->update_string('ci_sessions', array(
//				'last_activity'	=> time() + 3600,
//			), 'session_id="'.$this->session->userdata('session_id').'"'
//		));

		$this->load->helper('json');

		$drop_param = array(
					"school_id"			=>	$this->libauth->get_school_id(),
					"cource_id"			=>	$this->input->post('cource_id'),
					"book_library_id"	=>	$this->input->post('book_library_id'),
					);

		// 学校に属する図書室ID・図書室論理名を取得
		$this->load->model('model_book_library');
		$book_librarys = $this->model_book_library->get_cource_book_library_exclusive($drop_param);

		$this->output->set_header("HTTP/1.0 200 OK");
		$this->output->set_content_type('application/json; charset=utf-8');
		$this->output->set_output(json_encode($book_librarys));
	}
	
	//----------------------------------------------
	// [2013/01/07]専属タグ重複チェック
	//----------------------------------------------
	function _check_exclusive_tag($param){
		//引数設定
		$param = array_merge(
			array(
				'video_id'               => 0,		// ビデオID
				'exclusive_tag'          => array(),	// 専属タグ
				'exclusive_book_library' => array(),	// 対象図書室ID
				'exclusive_page_number'  => array(),	// 対象図書室内ページNo
				'exclusive_status'       => array(),	// 状態
			),
			$param
		);
		$exclusive_tag          = $param['exclusive_tag'];				// 専属タグ
		$exclusive_book_library = $param['exclusive_book_library'];		// 対象図書室ID
		$exclusive_page_number  = $param['exclusive_page_number'];		// 対象図書室内ページNo
		$exclusive_status       = $param['exclusive_status'];			// 状態
		
		$this->load->model('model_video');
		
		$overlap_msg = '';
		$index_no    = -1;
		foreach($exclusive_tag as $exclusive_tag_data){
			$index_no = $index_no + 1;
			
			$drop_param = array(
				"video_id"               => (intval($param['video_id']) ?? 0),
				'exclusive_tag'          => $exclusive_tag_data,
				'exclusive_book_library' => $exclusive_book_library[$index_no],
				'exclusive_page_number'  => $exclusive_page_number[$index_no],
				'exclusive_status'       => $exclusive_status[$index_no],
				'school_id'              => $this->libauth->get_school_id(),
			);
			$check_data = $this->model_video->check_exclusive_book_library_video($drop_param);
			
			if($check_data == true){
				if($overlap_msg == ''){
					$overlap_msg  = (string)($index_no + 1)."行目の専属タグが、他のビデオの専属タグと重複しています";
				}else{
					$overlap_msg .= "<br/>".(string)($index_no + 1)."行目の専属タグが、他のビデオの専属タグと重複しています";
				}
			}
		}
		
		if($overlap_msg != ''){
			return $overlap_msg;
		}else{
			return "";
		}
	}

	// コールバック関数
	public function check_required_checkbox($input) {
		if (!is_array($input)) {
			if( !empty($input) ){
				$temp = [$input];
				$input = $temp;
			}
		}
		if (!is_array($input) || count($input) == 0) {
			$this->form_validation->set_message('check_required_checkbox', '所属講座 欄は必須です。');
			return false;
		}
		return true;
	}
	
//	//----------------------------------------------
//	//資料ダウンロード
//	//----------------------------------------------
//	function download_file($class_id, $material_id){
//		// load language
//		$this->lang->load('common');
//		$this->lang->load('error');
//		
//		//データ取得用引数設定
//		$param = array(
//			'login_teacher_id' => $this->libauth->get_teacher_id(),
//			'class_id'         => $class_id,
//			'material_id'      => $material_id,
//		);
//
//		//モデル読み込み
//		$this->load->model('model_video');
//		//データ取得
//		$db_data = $this->model_video->get_material_filename($param);
//		
//		// 物理資料ファイル名なし→権限なし
//		if($db_data['video_name'] == ''){
//			//戻り先設定
//			$data['returnurl']     = site_url('cms_video');
//			$data['error_message'] = $this->lang->line_or_def('error_download_auth','ダウンロード権限がありません<br />ログインし直してください');
//			
//			//ビュー設定引数設定
//			$disp_param = array(
//							'view_name'   => 'material_error',
//							'submenu_idx' => 4,
//							'view_data'   => $data,
//						);
//			//確認フォーム表示
//			$this->_display_view($disp_param);
//		}else{
//			// ダウンロード時のファイル名（拡張子なし）の作成
//			// └ 論理資料ファイル名
//			//    論理資料ファイル名がない場合は、物理資料ファイル名（拡張子なし）
//			if($db_data['video_logic_name'] == ''){
//				$position  = strrpos($db_data['video_name'], '.');
//				$extension = substr($db_data['video_name'], 0, $position - 1);
//				$db_data['video_logic_name'] = $extension;
//			}
//			
//			// ファイルフルパス設定
//			$file;
//			if($db_data['material_path'] == 'cms'){
//				$file = $this->video_dir.'/'.$class_id.'/'.$material_id.'/'.$db_data['video_name'];
//			}elseif($db_data['material_path'] == 'teacher'){
//				$file = $this->video_dir.'/'.$class_id.'/'.$material_id.'/'.$db_data['video_name'];
//			}elseif($db_data['material_path'] == 'student'){
//				$file = $this->video_dir.'/'.$class_id.'/'.$db_data['video_name'];
//			}
//			
//			// ダウンロード処理（ファイル存在確認必須）
//			if (file_exists($file)) {
//				// 圧縮形式にてダウンロード
//				$result_data = $this->_make_zip_data(
//											$class_id, 
//											$material_id, 
//											$db_data['video_name'], 
//											$db_data['video_logic_name'], 
//											$db_data['material_path']);
//			}else{
//				//戻り先設定
//				$data['returnurl']     = site_url('cms_video');
//				$data['error_message'] = $this->lang->line_or_def('error_not_file','ファイルが見つかりません<br />管理者に問い合わせてください')
//										.'<br /><br />'.$this->lang->line_or_def('common_file_name','ファイル名').'&nbsp;:&nbsp;'.$db_data['video_logic_name']
//										.'<br />File Name&nbsp;:&nbsp;'.$db_data['video_name'];
//				//ビュー設定引数設定
//				$disp_param = array(
//								'view_name'   => 'material_error',
//								'submenu_idx' => 4,
//								'view_data'   => $data,
//							);
//				//確認フォーム表示
//				$this->_display_view($disp_param);
//			}
//		}
//	}
//	
//	//----------------------------------------------
//	//資料ダウンロード（ZIP）
//	//----------------------------------------------
//	function _make_zip_data($class_id, $material_id, $video_name, $video_logic_name, $material_path){
//		
//		//アーカイブ予定ファイルリスト初期化
//		$zipfile_list = array();
//		
//		//ファイル一覧取得
//		$file_dir;
//		if(($material_path == 'cms') or ($material_path == 'teacher')){
//			$file_dir  = $this->video_dir.'/'.$class_id.'/'.$material_id.'/';
//			$file_list = get_filenames($file_dir, TRUE);							// [codeigniter.get_filenames]
//
//			//ファイル一覧からアーカイブ対象ファイルを取得
//			foreach($file_list as $file){
//				//ファイル情報取得 [codeigniter.get_file_info]
//				$info = get_file_info($file);
//				
//				//ファイルパス取得 [php.mb_ereg_replace]
//				$path = mb_ereg_replace($info['name'],'',$file);
//				
//				//対象追加用ワーク設定
//				$zipfile = array(
//								'name' => $info['name'],
//								'date' => $info['date'],
//							);
//				
//				//対象一覧を検索
//				if(!array_key_exists($path,$zipfile_list)){
//					//対象一覧に無ければ追加
//					$zipfile_list[$path] = $zipfile;
//				} else {
//					//対象一覧にあった場合は更新日時の新しいものに変更
//					if($zipfile_list[$path]['date'] < $info['date']){
//						$zipfile_list[$path] = $zipfile;
//					}
//				}
//			}
//		}elseif($material_path == 'student'){
//			$file_dir  = $this->video_dir.'/'.$class_id.'/';
//
//			//ファイル情報取得 [codeigniter.get_file_info]
//			$file = $this->video_dir.'/'.$class_id.'/'.$video_name;
//			
//			$info = get_file_info($file);
//
//			//ファイルパス取得 [php.mb_ereg_replace]
//			$path = mb_ereg_replace($info['name'],'',$file);
//			
//			//対象追加用ワーク設定
//			$zipfile = array(
//							'name' => $info['name'],
//							'date' => $info['date'],
//						);
//			
//			//アーカイブ予定ファイルリスト追加
//			$zipfile_list[$path] = $zipfile;
//		}
//		
//		//zipライブラリのロード
//		$this->load->library('zip');
//		
//		//対象ファイルをzipに追加
//		foreach($zipfile_list as $path => $file){
//			//対象データ読み込み
//			$data = read_file($path.$file['name']);
//			//保存ディレクトリ、ファイル名設定
//			$filename = mb_convert_encoding($file['name'], 'SJIS', 'UTF-8');
//			$name     = mb_ereg_replace($file_dir,'',$path);
//			$name     = mb_ereg_replace('/','',$name);
//			
//			if($name != ''){
//				$name .= '_'.$filename;
//			} else {
//				$name = $filename;
//			}
//			
//			//zipファイルに追加
//			$this->zip->add_data($name, $data);
//		}
//		
//		//zipのダウンロード
//		$this->zip->download($video_logic_name.'.zip');
//		
//		//キャッシュのクリア
//		$this->zip->clear_data();
//	}
} 

/*End of File program.php*/
