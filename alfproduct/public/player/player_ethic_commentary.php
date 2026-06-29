<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
csrf_token_verify();
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (strpos($_SERVER['HTTP_REFERER'], '/ethic_treaning') === false){
	echo 'エラーが発生しました。[001]';
	$objDbConnect->close();
	exit;
}
$term = $_GET['term'];
if ($term!='pc' && $term!='sp'){
	echo 'エラーが発生しました。[002]';
	$objDbConnect->close();
	exit;
}

$view_btn = 'bookmark';

$user_id = '';
$vid = $_POST['vid']; // ビデオID
$pid = $_POST['pid']; // 商品ID

if (cmCheckInput($vid, 'CK_NUM')){
	echo '不正[001]';
	$objDbConnect->close();
	exit;
}

if (!isset($_POST['back_type'])){
	echo '不正[002]';
	$objDbConnect->close();
	exit;
}
$back_type = $_POST['back_type'];

$buy_flg = true;
$view_flg = true;
$free_product_flg = true;
$bookmark_time_sec = 0;
$player = ''; // プレイヤータグ
$player_msg = ''; // ユーザー表示用メッセージ
$get_alf_player_msg1 = ''; // デフォルトプレイヤー取得エラーメッセージ
$get_alf_player_msg2 = ''; // 低解像度取得エラーメッセージ
$set_btn_flg = true; // 先送り系ボタンの不可判断

$time2 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)
$time3 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)

$video_logic_name = '';
$video_popup = 0;

$st_login_check = st_login_check();

if ($st_login_check){
	$user_id = $_SESSION['user']['id'];
	
	if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
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
						"coder" => '',
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
				
				if ($get_video_flg){
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
	} else {
		echo '不正[002]';
		exit;
	}
} else {
	echo '不正[003]';
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$html_head_title = '倫理研修解説動画';
$template->assign('html_head_title', $html_head_title);

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
$template->assign('pid', $pid);
$template->assign('back_type', $back_type);

$template->assign('time2', $time2);
$template->assign('time3', $time3);

$template->assign('video_logic_name', $video_logic_name);
$template->assign('video_popup', $video_popup);

$template->assign('isSP', $term);

$template->layout_alfstream('player/player_ethic_commentary.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>