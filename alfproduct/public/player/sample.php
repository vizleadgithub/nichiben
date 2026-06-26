<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$term = $_GET['term'];
if ($term!='pc' && $term!='sp'){
	echo 'エラーが発生しました。[001]';
	$objDbConnect->close();
	exit;
}


//$idkey = 'QzPRF8UKr6Fc'; // 動画識別キー(idkey)
$idkey = 'v9pr5fXtehB3'; // 動画識別キー(idkey)
$video_logic_name = 'このサイトの使い方（動画）'; // titleタグ用＆動画名


$user_id = '';
$player = ''; // プレイヤータグ
$player_msg = ''; // ユーザー表示用メッセージ
$get_alf_player_msg1 = ''; // デフォルトプレイヤー取得エラーメッセージ
$get_alf_player_msg2 = ''; // 低解像度取得エラーメッセージ
$arr_chapter_list = array(); // チャプターリスト
$ak_initialized_param = ''; // 動画初期化用パラメータ

$st_login_check = st_login_check();

// ログイン済み
if ($st_login_check){
	// チャプターリストの取得
	$sql = "SELECT";
	$sql.= "  video_chapter.chapter_time,";
	$sql.= "  video_chapter.chapter_name,";
	$sql.= "  TIME_TO_SEC(video_chapter.chapter_time) AS chapter_time_sec";
	$sql.= " FROM";
	$sql.= "  video_chapter";
	$sql.= "    INNER JOIN";
	$sql.= "  video";
	$sql.= "    ON video_chapter.video_id = video.video_id";
	$sql.= " WHERE";
	$sql.= "  video.idkey='".$idkey."'";
	$arr_chapter_list = $objDbConnect->query_fetch_arr($sql);
	if ($arr_chapter_list){
		// 動画初期化用パラメータの作成
		foreach ($arr_chapter_list as $val){
			if ($val['chapter_time_sec']!=0){
				$ak_initialized_param.= $val['chapter_time_sec'].',';
			}
		}
		if ($ak_initialized_param != ''){
			$ak_initialized_param = rtrim($ak_initialized_param, ',');
		}
	}
	
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
			"ckey" => $idkey,
			"uid" => $uid,
		);
	// スマホ用
	} elseif ($term=='sp'){
		$data = array(
			"ckey" => $idkey,
			"uid" => $uid,
		);
	} else {
		$data = array();
	}
	
	if (!empty($data)){
		$get_alf_player = get_alf_player($data);
		
		// デフォルトプレイヤーで取得できなかった場合は低解像度版の取得
		if ($get_alf_player['err']==1){
			$player_msg = 'この動画は高画質版が存在しないので、低解像度版で表示しております。';
			$get_alf_player_msg1 = '1:'.$get_alf_player['message'];
			$get_alf_player = '';
			
			$data = array(
				"ckey" => $idkey,
				"coder" => '',
				"type" => '',
				"player" => 'DEFAULT',
				"player.type" => '',
				"uid" => $uid,
			);
			
			$get_alf_player = get_alf_player($data);
			
			if ($get_alf_player['err']==0){
				$player = $get_alf_player['player'];
			} else {
				$player_msg = '動画ファイルは存在しません。';
				$get_alf_player_msg2 = '2:'.$get_alf_player['message'];
			}
			
		} elseif ($player['err']==0){
			$player = $get_alf_player['player'];
			
		} else {
			$player_msg = '動画ファイルは存在しません。';
			
		}
		
	} else {
		$player_msg = '動画ファイルは存在しません。';
	}
} else {
	echo 'エラーが発生しました。[002]';
	$objDbConnect->close();
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('html_head_title', $video_logic_name);
$template->assign('arr_chapter_list', $arr_chapter_list);
$template->assign('ak_initialized_param', $ak_initialized_param);
$template->assign('player', $player);
$template->assign('player_msg', $player_msg);
$template->assign('get_alf_player_msg1', $get_alf_player_msg1);
$template->assign('get_alf_player_msg2', $get_alf_player_msg2);
$template->assign('user_id', $user_id);
$template->assign('video_id', $vid);
$template->assign('st_login_check', $st_login_check);
$template->assign('video_logic_name', $video_logic_name);
$template->assign('back_url', get_back_url());
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->layout_alfstream('player/sample.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>
