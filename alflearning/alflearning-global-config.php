<?
//----------------------------------------------
// ALF Learning Version
//----------------------------------------------
$config['alf_learning_ver'] = '1.6.0';

//----------------------------------------------
// service basic auth
//----------------------------------------------
$config['service_basic_auth'] = 'alfredcore:alfredcore123@';

//----------------------------------------------
// follower domain
//----------------------------------------------
//$config['domain_follower'] = 'dev-follower.alflearning.com';
if(getenv('URL_SERVICE') == 'alfsales'){
	$config['domain_follower'] = 'dev-follower.alfsales.net';
}elseif(getenv('URL_SERVICE') == 'conference'){
	$config['domain_follower'] = 'dev-follower.alfconference.com';
}else{
	$config['domain_follower'] = 'dev-follower.alflearning.com';
}

//----------------------------------------------
// master domain
//----------------------------------------------
//$config['domain_name_master'] = 'dev-master.alflearning.com';
if(getenv('URL_SERVICE') == 'alfsales'){
	$config['domain_name_master'] = 'dev-master.alfsales.net';
}elseif(getenv('URL_SERVICE') == 'conference'){
	$config['domain_name_master'] = 'dev-master.alfconference.com';
}else{
	$config['domain_name_master'] = 'dev-master.alflearning.com';
}

//----------------------------------------------
// api domain
//----------------------------------------------
$config['domain_name_api'] =  'api.nichibenren-stg.alfcloud.com';
//$config['domain_name_api'] =  'api.nichibenren-stg.alfredcore.net';

//----------------------------------------------
// cms domain
//----------------------------------------------
$config['domain_name_cms'] =  'cms.nichibenren-stg2.alfcloud.com';
//  //$config['domain_name_cms'] = 'dev-cms.alflearning.com';
//  if(getenv('URL_SERVICE') == 'alfsales'){
//      $config['domain_name_cms'] = 'dev-cms.alfsales.net';
//  }elseif(getenv('URL_SERVICE') == 'conference'){
//      $config['domain_name_cms'] = 'dev-cms.alfconference.com';
//  }else{
//  //  $config['domain_name_cms'] = 'dev-cms.alflearning.com';
//      $config['domain_name_cms'] = 'cms.dev-hogakukan.alfredcore.net';
//  }

//----------------------------------------------
// mypage domain
//----------------------------------------------
$config['domain_name_myapge'] = 'dev-mypage.alflearning.com';

//----------------------------------------------
// ログイン画像ディレクトリ
//----------------------------------------------
$config['login_image_dir'] = '/alflearning-data/loginimg';

//----------------------------------------------
// メニュー画像ディレクトリ[2012/07/31]
//----------------------------------------------
$config['menu_image_dir'] = '/alflearning-data/menuimg';

//----------------------------------------------
// websocket
//----------------------------------------------
$config['websocket_url'] = 'ws://dev-follower.alflearning.com:8828';

//----------------------------------------------
//資料管理（material）
//----------------------------------------------
$config['material_dir']       = '/alflearning-data/material';	//資料アップロードディレクトリ
$config['class_material_dir'] = '/alflearning-data/class';		//授業資料アップロードディレクトリ

//----------------------------------------------
// 図書室（book_library）
//----------------------------------------------
$config['book_library_dir'] = '/alflearning-data/book_libraly';	//資料アップロードディレクトリ
$config['zip_password']     = '8l4Pg6GrvOvVKu';					//zip用パスワード

$config['stream_ftp']       = 'ftp3.smartstream.ne.jp';
$config['stream_ftp_dir']   = '/dl/alflearning-dev';
$config['stream_ftp_user']  = 'alfstr2';
$config['stream_ftp_pass']  = 'omlLnWSyn';

$config['stream_get_url']  = 'http://dl2.alfstream.com/alfstr2/alflearning-dev';

//----------------------------------------------
// 音声ファイル
//----------------------------------------------
$config['voice_file_dir']       = '/alflearning-data/voice';

//----------------------------------------------
// 講師管理（teacher）
//----------------------------------------------
$config['teacher_dir'] = '/alflearning-data/teacher';			//講師写真アップロードディレクトリ

//----------------------------------------------

//----------------------------------------------
// ノートファイル
//----------------------------------------------
$config['note_file_dir']       = '/alflearning-data/note';

//----------------------------------------------
// 課題（レポート）ファイル
//----------------------------------------------
$config['issue_file_dir']       = '/alflearning-data/issue';

//----------------------------------------------
// デモ用アカウント（プロトタイプアカウント）
//----------------------------------------------
$config['prototype_account'] = array('demo','demo2','demo3');

//----------------------------------------------
//ビデオサムネイル設定
//----------------------------------------------
$config['video_thumbnail_dir']           = '/alflearning-data/video_thumbnail';	// サムネイル格納ディレクトリ
$config['video_thumbnail_number']        = 1;	// 4 サムネイル作成数
$config['video_thumbnail_make_interval'] = 60;	// 10 ALFStream にPoster作成させるための待ち時間（秒）設定可能範囲[1-600]

//----------------------------------------------
// video
//----------------------------------------------
//----------------------------------------------
// video
//----------------------------------------------
$config['video_dir']                            = '/alflearning-data/video';    //資料アップロードディレクトリ
$config['video_stream_api_domain_name']         = 'api.alfstream.com';
$config['video_stream_api_domain_name_admin']   = 'admin.alfstream.com';
$config['video_stream_api_pull_uri']            = 'http://alflearning.com/video_files';
$config['video_stream_api_basic_id']            = 'alflearning';
$config['video_stream_api_basic_pw']            = '2pF81NBr';
$config['video_stream_api_new_service_param']   = array(
        'cn'                    => 'REAL',
        'default_player_id'     => '5',
        'default_processor_id'  => '5',
        'default_security_id'   => '5',
        'distserver_id'         => '2', //開発、ステージは1, 本番は2
        'email'                 => 'koyama@alfredcore.com',
//      'name'                  => 'learntest1',        //呼び出し元で決める
        'storage'               => '',
);

//----------------------------------------------
// 【日弁連】OpenAM サーバーURL
//----------------------------------------------
$config['openam_server_url'] = 'https://www.nichibenren-member-sso.jp/openam';

//----------------------------------------------
// 【日弁連】学校ID
//----------------------------------------------
$config['nichibenren_school_id'] = 1;

//----------------------------------------------
// 【日弁連】講座ID
//----------------------------------------------
$config['nichibenren_cource_id'] = 1;

//----------------------------------------------
// 【日弁連】受講者用パスワード
//----------------------------------------------
$config['nichibenren_student_password'] = 'Nichibenren_Learning_Password';

//----------------------------------------------
// 受講者管理（student）
//----------------------------------------------
$config['student_dir'] = '/alflearning-data/student';    //受講者CSVファイルアップロードディレクトリ

//----------------------------------------------
// アカウントロック設定
//----------------------------------------------
$config['lock_time_sec'] = 600;//秒
$config['lock_login_count'] = 5;//ロックまでの回数
?>
