<?php
#[AllowDynamicProperties]
class Cms_cource_class extends CI_Controller {
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
			if($work_auth['course_class'] == 0){
				redirect('admin_top');
			}
			
			//学校ID非選択時には学校選択へ
			if( $this->libauth->get_school_id() == 0 ){
				redirect('school_select');
			}
		}
		
		// 学校管理の授業：契約形態が未設定（undefined）の場合、トップ画面にリダイレクト
		$this->load->model('Modelschoolcontract');
		if(!$this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))){
			redirect('admin_top');
		}
	}
	
	//----------------------------------------------
	//一覧表示
	//----------------------------------------------
	function index($sel_month = 0, $offset = 0){
		//表示用変数の初期化
		$data = array();
		
		//ページネーションライブラリのロードとオフセット取得
		$this->load->library('pagination');
		$per_page = $this->config->item('pagination_per_page');
		
		//選択月が未設定の場合は当月(yyyymm)を設定
		if($sel_month == 0){
			$sel_month = date('Ym');
		}
		
		//当月、翌月、翌々月を設定
		//当月を取得
		$this_date  = strtotime(mb_substr($sel_month,0,4).'/'.mb_substr($sel_month,4,2).'/01');
		//前月を取得
		$prev_date = strtotime(date('Y/m/1',$this_date - (1 * $this->day_second)));
		//翌月を取得
		$next_date = strtotime(date('Y/m/1',$this_date + (31 * $this->day_second)));
		
		//当月表示年月を設定
		$data['this_month'] = date('Y年m月', $this_date);
		//前月・翌月リンクを作成
		$data['month_prev'] = anchor("cms_cource_class/index/" . date('Ym', $prev_date), date('<n月', $prev_date));
		$data['month_next'] = anchor("cms_cource_class/index/" . date('Ym', $next_date), date('n月>', $next_date));
		
		//授業モデル読み込み
		$this->load->model('model_class');
		//データ更新用引数設定
		$data_param = array(
						'school_id'  => $this->libauth->get_school_id(),
						'teacher_id' => $this->libauth->get_teacher_id(),
						'sel_month'  => $sel_month,
						'offset'     => $offset,
						'rowcount'   => $per_page,
					);
		//データ取得
		$class_list = $this->model_class->get_class_tantou_list($data_param);
	//	$data['class_list'] = $class_list;
		
		// [2012/11/08]公開期間外の講座に所属する授業の判定処理（view側で色有無判定に使用）
		if($class_list){
			$this->load->model('model_cource');
			$class_list_data = array();
			foreach($class_list as $class) {
				$get_cource_name = $this->model_cource->get_name(array('cource_id'  => $class['cource_id']));
				$class['effective_cource'] = 0;
				if($get_cource_name){
					$class['effective_cource'] = 1;
				}
				array_push($class_list_data, $class);
			}
			$data['class_list'] = $class_list_data;
		}else{
			$data['class_list'] = $class_list;
		}
		
		//ページネーション設定
		$config['base_url']   = base_url().'/cms_cource_class/index/'.$sel_month;
		$config['total_rows'] = $this->model_class->get_class_tantou_count($data_param);
		$config['uri_segment'] = 4;
		$config['per_page']   = $per_page;
		$config['first_link'] = '&lt;&lt;';
		$config['last_link']  = '&gt;&gt;';
		$this->pagination->initialize($config); 
		$data['pagination'] =  $this->pagination->create_links();
		
		//ビュー設定引数設定
		$disp_param = array(
						'view_name'   => 'cms_cource_class/index',
						'submenu_idx' => 1,
						'view_data'   => $data,
						'sel_month'   => $sel_month,
					);
		//ビュー設定
		$this->_display_view($disp_param);
	}
	
	//----------------------------------------------
	//サブメニュー作成
	//----------------------------------------------
	function _create_sub_menu($param){
		//引数設定
		$param = array_merge(
						array(
							'submenu_idx' => 0,
							'sel_month'   => 0,
						),
						$param
					);
		
		//メニュー表示用配列初期化
		$sub_menu = array();
		
		//当月・前月リンク設定
		//現在日時取得
		$now = time();
		//当月・前月の1日を取得
		$thismonth = strtotime(date('Y/m/1', $now));
		$prevmonth = strtotime(date('Y/m/1', $thismonth - (1 * $this->day_second)));
		//各月のパラメータ用数値を設定(yyyymm形式）
		$thismonth_pram = date('Ym', $thismonth);
		$prevmonth_pram = date('Ym', $prevmonth);
		
		//現在選択月に応じてアンカー設定
		if($thismonth_pram != $param['sel_month']) {
			$sub_menu[1] = anchor("cms_cource_class/index/" . $thismonth_pram, date('Y年m月', $thismonth));
		} else {
			$sub_menu[1] = date('Y年m月', $thismonth);
		}
		if($prevmonth_pram != $param['sel_month']) {
			$sub_menu[2] = anchor("cms_cource_class/index/" . $prevmonth_pram, date('Y年m月', $prevmonth));
		} else {
			$sub_menu[2] = date('Y年m月', $prevmonth);
		}
		
		switch($param['submenu_idx']){
			case 1://担当授業一覧
				$sub_menu[3] = anchor("cms_class", "検索");;
				$sub_menu[4] = anchor("cms_class/newdata", "新規登録");
				$sub_menu[5] = anchor("cms_cource", "検索");
				$sub_menu[6] = anchor("cms_cource/newdata", "新規登録");
				break;
			
		}
		return $sub_menu;
	}
	
	//----------------------------------------------
	//ビュー表示
	//----------------------------------------------
	function _display_view($param) {
		//引数設定
		$param = array_merge(
						array(
							'view_name'   => '',
							'submenu_idx' => 0,
							'view_data'   => array(),
							'sel_month'   => 0,
						),
						$param
					);

		//サブメニュー生成
		$param['view_data']['sub_menu'] = $this->_create_sub_menu($param);
		
		//自ページ名設定
		$param['view_data']['thispage'] = strtolower(get_class($this));
		
		$this->load->view($param['view_name'], $param['view_data']);
		
	}
} 

/*End of File program.php*/
