<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_POST['pid'])){
	echo 'エラーが発生しました。[001]';
	$objDbConnect->close();
	exit;
}
if (strpos($_SERVER['HTTP_REFERER'], '/product/detail.php') !== false
 && strpos($_SERVER['HTTP_REFERER'], '/player/index.php') !== false
){
	echo 'エラーが発生しました。[002]';
	$objDbConnect->close();
	exit;
}
$term = $_GET['term'];
if ($term!='pc' && $term!='sp'){
	echo 'エラーが発生しました。[003]';
	$objDbConnect->close();
	exit;
}
$view_btn = $_POST['view_btn'];
if ($view_btn!='start' && $view_btn!='bookmark'){
	echo 'エラーが発生しました。[004]';
	$objDbConnect->close();
	exit;
}

$user_id = '';
$pid = $_POST['pid']; // 商品ID
$vid = $_POST['vid']; // ビデオID
$ftn = $_POST['ftn']; // コンテンツ無料時間カラム名
$ccno = $_POST['ccno']; // コンテンツコンテンツのNO.

if (cmCheckInput($pid, 'CK_NUM')){
	echo '不正[001]';
	$objDbConnect->close();
	exit;
}
if (cmCheckInput($vid, 'CK_NUM')){
	echo '不正[002]';
	$objDbConnect->close();
	exit;
}
if (strpos($ftn, 'contents_free_time') === false){
	echo '不正[003]';
	$objDbConnect->close();
	exit;
}
if (cmCheckInput($ccno, 'CK_NUM')){
	echo '不正[006]';
	$objDbConnect->close();
	exit;
}

$buy_flg = true;
$view_flg = true;
$free_product_flg = true;
$bookmark_time_sec = 0;
$player = ''; // プレイヤータグ
$player_msg = ''; // ユーザー表示用メッセージ
$get_alf_player_msg1 = ''; // デフォルトプレイヤー取得エラーメッセージ
$get_alf_player_msg2 = ''; // 低解像度取得エラーメッセージ
$set_btn_flg = true; // 先送り系ボタンの不可判断

$next_vid = ''; // 次のビデオID
$next_ftn = ''; // 次のコンテンツ無料時間カラム名
$next_ccno = ''; // 次のコンテンツコンテンツのNO.
$next_contents_flg = false; // 次のコンテンツが存在するか

$time2 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)
$time3 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)

$video_logic_name = '';
$video_popup = 0;

$st_login_check = st_login_check();

if ($st_login_check){
	$user_id = $_SESSION['user']['id'];
	
	if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
		// 商品情報取得
		$sql = "SELECT product_name FROM tbl_product WHERE del_flg='0' AND product_id='$pid'";
		$product_list = $objDbConnect->query_fetch($sql);

		if ($product_list){
			// ビデオ閲覧履歴確認
			$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '$vid' AND complete_flag = '1'";
			$ret_view_history = $objDbConnect->query_fetch($sql);
			if ($ret_view_history){
				if ($ret_view_history["c"] >= 1){
					$set_btn_flg = false;
				}
			}
			
			// しおり機能
			$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$user_id."' AND video_id='".$vid."'";
			$ret_bookmark = $objDbConnect->query_fetch($sql);
			if ($ret_bookmark){
				$bookmark_time_sec = $ret_bookmark['bookmark_time_sec'];
			}
			
			$arr_chapter_list = array();
			$arr_ckey_list = array();
			
			// チャプターリストの取得
			$ret = '';
			$sql = "SELECT chapter_time,chapter_name,TIME_TO_SEC(chapter_time) AS chapter_time_sec FROM video_chapter WHERE video_id='".$vid."'";
			$arr_chapter_list = $objDbConnect->query_fetch_arr($sql);
			if ($arr_chapter_list){
				// 動画初期化用パラメータの作成
				$ak_initialized_param = '';
				foreach ($arr_chapter_list as $val){
					if ($val['chapter_time_sec']!=0){
						$ak_initialized_param.= $val['chapter_time_sec'].',';
					}
				}
				if ($ak_initialized_param != ''){
					$ak_initialized_param = rtrim($ak_initialized_param, ',');
				}
			}
			
			// 動画取得用ckeyの取得
			$ret1 = '';
			$sql = "SELECT idkey, video_logic_name, video_popup FROM video WHERE video_id='".$vid."'";
			if( $vid2!="" ){
				$sql = "SELECT idkey, video_logic_name, video_popup FROM video WHERE video_id='".$vid2."'";
			}
			$arr_ckey_list = $objDbConnect->query_fetch($sql);
			if ($arr_ckey_list){
				// 動画名の設定
				$video_logic_name = $arr_ckey_list['video_logic_name'];
				$video_popup = $arr_ckey_list['video_popup'];
				
				// -----------------
				// プレイヤーの取得
				// -----------------
				$get_alf_player = '';
				
				if ($st_login_check){
					$uid = $_SESSION['user']['id'];
				} else {
					$uid = 0;
				}
				
				// PC用
				if ($term=='pc'){
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder"=> "480p",
						"uid" => $uid,
					);
				// スマホ用
				} elseif ($term=='sp'){
				//	$data = array(
				//		"ckey" => $arr_ckey_list['idkey'],
						//"coder" => '180p',
						//"player.width" => '180',
						//"player.height" => '135',
				//		"player.controller" => 'none',
				//		"uid" => $uid,
				//	);
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder"=> "480p",
						"uid" => $uid,
					);
				} else {
					$data = array();
				}
				
				if (!empty($data)){
					//=================================
					//tkp
					$data["player"] = "JWPLAYER8";
					$data["player.type"] = "tkplayer";
					$clientIP = "";
					if($_SERVER["HTTP_X_FORWARDED_FOR"]){
						$clientIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
					}
					else if($_SERVER["REMOTE_ADDR"]){
						$clientIP = $_SERVER["REMOTE_ADDR"];
					}
					$data["client_ip"] = $clientIP;
					$data["client_ua"] = $_SERVER['HTTP_USER_AGENT'];
					//=================================
					$get_alf_player = get_alf_player($data);
					
					$get_video_flg = true;
					
					// デフォルトプレイヤーで取得できなかった場合は低解像度版の取得
					if ($get_alf_player['err']==1){
						$player_msg = 'この動画は高画質版が存在しないので、低解像度版で表示しております。';
						$get_alf_player_msg1 = '1:'.$get_alf_player['message'];
						$get_alf_player = '';
						
						$data = array(
							"ckey" => $arr_ckey_list['idkey'],
							"coder" => '480p',
							"type" => '',
							"player" => 'akplayerlist',
							"player.type" => '',
							//"player.width" => '180',
							//"player.height" => '135',
							//"player.controller" => 'none',
							"uid" => $uid,
						);
						$data = array(
							"ckey" => $arr_ckey_list['idkey'],
							"coder" => '480p',
							"type" => '',
							"player" => 'JWPLAYER8',
							"player.type" => 'tkplayer',
							//"player.width" => '180',
							//"player.height" => '135',
							//"player.controller" => 'none',
							"uid" => $uid,
						);
						
						$get_alf_player = get_alf_player($data);
						if ($get_alf_player['err']==0){
							$player = $get_alf_player['player'];
						} else {
							$player_msg = '動画ファイルは存在しません。';
							$get_alf_player_msg2 = '2:'.$get_alf_player['message'];
						}
						
					} elseif ($get_alf_player['err']==0){
						$player = $get_alf_player['player'];
						
					} else {
						$get_video_flg = false;
						$player_msg = '動画ファイルは存在しません。';
						
					}
					
					// 動画が取得できた場合は次のコンテンツがあるか調べる(あれば再生終了後、次のコンテンツに飛ぶ)
					if ($get_video_flg){
						if ($ccno < MAX_CONTENTS){
							$next_ccno = $ccno + 1;
							$next_contents_column = 'contents_contents'.$next_ccno;
							$sql = "SELECT $next_contents_column FROM tbl_product WHERE del_flg='0' AND product_id='$pid'";
							$res = $objDbConnect->query_fetch($sql);
							if ($res){
								if ($res["$next_contents_column"] != ''){
									$next_vid = $res["$next_contents_column"];
									$next_ftn = 'contents_free_time'.$next_ccno;
									$next_contents_flg = true;
								}
							}
						}
						
						// 動画の再生完了判断時間の算出(bookmark.phpの中で使用)
						$sql = "SELECT TIME_TO_SEC(alfstream_duration) AS alfstream_duration_sec FROM video_alfstream_status WHERE video_id = '$vid'";
						$res = $objDbConnect->query_fetch($sql);
						if ($res){
							if ($res['alfstream_duration_sec'] <= PLAYER_ALL_READING_JUDGE_TIME){
								// 設定時間以下の場合は1秒として、講座受講完了とする
								$time2 = 1;
								$time3 = 1;
							} else {
								$time2 = $res['alfstream_duration_sec'] - PLAYER_ALL_READING_JUDGE_TIME;
								$time3 = $res['alfstream_duration_sec'] - PLAYER_ALL_READING_JUDGE_TIME;
							}
						}
					}
					
				} else {
					$player_msg = '動画ファイルは存在しません。';
				}
			}
		}
	} else {
		echo '不正[004]';
		exit;
	}
} else {
	echo '不正[005]';
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$html_head_title = '倫理研修動画';
if (isset($product_list['product_name'])){
	if ($product_list['product_name']!=''){
		$html_head_title = $product_list['product_name'];
	}
}
$template->assign('html_head_title', $html_head_title);

$template->assign('pid', $pid);
$template->assign('buy_flg', $buy_flg);
$template->assign('view_flg', $view_flg);
$template->assign('arr_chapter_list', $arr_chapter_list);
$template->assign('ak_initialized_param', $ak_initialized_param);
$template->assign('player', $player);
$template->assign('player_msg', $player_msg);
$template->assign('get_alf_player_msg1', $get_alf_player_msg1);
$template->assign('get_alf_player_msg2', $get_alf_player_msg2);
$template->assign('user_id', $user_id);
$template->assign('video_id', $vid);
$template->assign('bookmark_time_sec', $bookmark_time_sec);
$template->assign('contents_free_time', $contents_free_time);
$template->assign('view_btn', $view_btn);
$template->assign('st_login_check', $st_login_check);
$template->assign('free_product_flg', $free_product_flg);
$template->assign('set_btn_flg', $set_btn_flg);

$template->assign('next_vid', $next_vid);
$template->assign('next_ftn', $next_ftn);
$template->assign('next_ccno', $next_ccno);
$template->assign('next_contents_flg', $next_contents_flg);

$template->assign('time2', $time2);
$template->assign('time3', $time3);

$template->assign('video_logic_name', $video_logic_name);
$template->assign('video_popup', $video_popup);

$template->assign('isSP', $term);

$template->layout_alfstream('player/player_ethic.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>