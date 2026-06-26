<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
csrf_token_verify();
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_POST['pid'])){
	echo 'エラーが発生しました。[001]';
	print('<script type="text/javascript">if (window.opener) { } else { location.href = "/"; } </script>');
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
$vid2 = $_POST['vid2']; // ビデオID
$codec = $_POST['codec']; // ビデオID
$ftn = $_POST['ftn']; // コンテンツ無料時間カラム名
$ccno = $_POST['ccno']; // コンテンツコンテンツのNO.

if (cmCheckInput($pid, 'CK_NUM')){
	echo '不正[001]';
	$objDbConnect->close();
	exit;
}
if ($vid!=""){
	if (cmCheckInput($vid, 'CK_NUM')){
		echo '不正[002]';
		$objDbConnect->close();
		exit;
	}
}
if ($vid2!=""){
	if (cmCheckInput($vid2, 'CK_NUM')){
		echo '不正[002]';
		$objDbConnect->close();
		exit;
	}
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

$buy_flg = false;
$view_flg = false;
$free_product_flg = false;
$bookmark_time_sec = 0;
$player = ''; // プレイヤータグ
$player_msg = ''; // ユーザー表示用メッセージ
$get_alf_player_msg1 = ''; // デフォルトプレイヤー取得エラーメッセージ
$get_alf_player_msg2 = ''; // 低解像度取得エラーメッセージ
$set_btn_flg = true; // 先送り系ボタンの不可判断

$next_vid = ''; // 次のビデオID
$next_vid2 = ''; // 次のビデオID
$next_ftn = ''; // 次のコンテンツ無料時間カラム名
$next_ccno = ''; // 次のコンテンツコンテンツのNO.
$next_contents_flg = false; // 次のコンテンツが存在するか

$time2 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)
$time3 = 0; // 動画の総再生時間(秒) - 再生完了判断時間(秒)

$buy_wait_flg = false;

$video_logic_name = '';
$video_popup = 0;
$ak_initialized_param = '';

$st_login_check = st_login_check();

// 商品情報取得
$sql = "SELECT price,product_type,product_name FROM tbl_product WHERE del_flg='0' AND product_id='$pid'";
$product_list = $objDbConnect->query_fetch($sql);

if ($product_list){
	if ($product_list['price']=='0'){
		$free_product_flg = true;
	}
	// -----------------
	// 視聴可能チェック
	// -----------------
	$contents_free_time = 0;
	$sql = "SELECT $ftn FROM tbl_product WHERE del_flg='0' AND product_id='$pid'";
	$ret_free_time = $objDbConnect->query_fetch($sql);
	if ($ret_free_time){
		$contents_free_time = $ret_free_time["$ftn"];
		// コンテンツ無料部分あり
		if ($contents_free_time!=0 && $contents_free_time!=''){
			$view_flg = true;
		}

		// ログイン済み
		if ($st_login_check){
			$user_id = $_SESSION['user']['id'];
			
			// ビデオ閲覧履歴確認
			$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '$vid' AND complete_flag = '1'";
			//if( $vid2!="" ){
			//	$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '$vid2' AND complete_flag = '1'";
			//}
			$ret_view_history = $objDbConnect->query_fetch($sql);
			if ($ret_view_history){
				if ($ret_view_history["c"] >= 1){
					$set_btn_flg = false;
				}
			}
			// ユーザー情報取得
			$sql = "SELECT * FROM student WHERE status='0' AND student_id='$user_id'";
			$user_list = $objDbConnect->query_fetch($sql);
			// パスポートを所持している場合
			if ($_SESSION['user']['presence_passport']==1){
				$view_flg = true;
				$buy_flg = true;
				
			} else {
				// 無料パッケージ商品
				if ($product_list['price']==0){
					$view_flg = true;
					$buy_flg = true;
				}
				// 月額課金ユーザ
				if ($user_list['member_type']==2){
					$view_flg = true;
				}
				// 購入済み・視聴期間超え確認
				if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $user_id)){
					$view_flg = true;
					$buy_flg = true;
				}
			}
			
			//入金待ちの有無
			$sql = "";
			$sql.= "SELECT ";
			$sql.= " COUNT(tbl_order_detail.order_detail_id) as c ";
			$sql.= "FROM ";
			$sql.= " tbl_order_detail ";
			$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
			$sql.= "WHERE ";
			$sql.= " tbl_order_detail.member_id='".$_SESSION['user']['id']."' "; 
			$sql.= " AND tbl_order_detail.product_id='".$pid."' ";
			$sql.= " AND tbl_order_detail.payment_status='1' ";
			$sql.= " AND tbl_order.payment_type='12' ";
			$sql.= " AND (tbl_order.payment_status='1' OR tbl_order.payment_status='3') ";
			//$sql.= " AND tbl_order_detail.create_date>='".date('Y-m-d H:i:s',strtotime("-10 day"))."' ";
			//print_r("<!--[".$sql."]-->");
			$ret = $objDbConnect->query_fetch($sql);
			if ($ret['c']==0){
			} else {
				$buy_wait_flg = true;
			}
			
			// しおり機能
			$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$user_id."' AND video_id='".$vid."'";
			if( $vid2!="" ){
				$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$user_id."' AND video_id='".$vid2."'";
			}
			//print("<!--[1:".$sql."]-->");
			$ret_bookmark = $objDbConnect->query_fetch($sql);
			if ($ret_bookmark){
				$bookmark_time_sec = $ret_bookmark['bookmark_time_sec'];
			}
			//print("<!--[bookmark_time_sec:".$bookmark_time_sec."]-->");

			if( $vid2=="" ){
				// 視聴ログから視聴済み時間の最大値を読んで、その位置から再生する(仕様変更)
				$sql = "SELECT TIME_TO_SEC(duration_reading) AS bookmark_time_sec FROM report_user_video_viewed WHERE complete_flag<>1 and student_id='".$user_id."' AND video_id='".$vid."'";
				if( $vid2!="" ){
					$sql = "SELECT TIME_TO_SEC(duration_reading) AS bookmark_time_sec FROM report_user_video_viewed WHERE complete_flag<>1 and student_id='".$user_id."' AND video_id='".$vid2."'";
				}
				//print("<!--[2:".$sql."]-->");
				$ret_bookmark_sub = $objDbConnect->query_fetch($sql);
				if ($ret_bookmark_sub){
					if( $ret_bookmark_sub['bookmark_time_sec']>0 && $ret_bookmark_sub['bookmark_time_sec']>$bookmark_time_sec){
						$bookmark_time_sec = $ret_bookmark_sub['bookmark_time_sec'];
					}
				}
				//print("[DB bookmark_time_sec:".$bookmark_time_sec."]");
			}

			
		// 未ログイン
		} else {
			// 無料かつ一般公開商品
			if ($product_list['price']==0 && $product_list['product_type']==2){
				$view_flg = true;
				$buy_flg = true;
			}
			
		}
		
		if ($view_flg){
			$arr_chapter_list = array();
			$arr_ckey_list = array();
			
			// チャプターリストの取得
			$ret = '';
			$sql = "SELECT chapter_time,chapter_name,TIME_TO_SEC(chapter_time) AS chapter_time_sec FROM video_chapter WHERE video_id='".$vid."'";
			if( $vid2!="" ){
				$sql = "SELECT chapter_time,chapter_name,TIME_TO_SEC(chapter_time) AS chapter_time_sec FROM video_chapter WHERE video_id='".$vid2."'";
			}
			$arr_chapter_list = $objDbConnect->query_fetch_arr($sql);
			if ($arr_chapter_list){
				// 動画初期化用パラメータの作成
				$ak_initialized_param = '';
				foreach ($arr_chapter_list as $val){
/*					if ($val['chapter_time_sec']!=0){
						$arr_chapter_list['ak_initialized_param'].= $val['chapter_time_sec'].',';
					}
				}
				if ($arr_chapter_list['ak_initialized_param']!=''){
					$arr_chapter_list['ak_initialized_param'] = rtrim($arr_chapter_list['ak_initialized_param'], ',');
				}*/
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
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder"=> "480p",
						"uid" => $uid,
						//"player" => "DEFAULTNO",
					);
				} else {
					$data = array();
				}
				if($codec==''){
					$data["coder"] = "480p";
					$data["playrate"] = "";
				}
				if($codec=='x13'){
					$data["coder"] = "x13";
					$data["playrate"] = "1.3";
				}
				if($codec=='x15'){
					$data["coder"] = "x15";
					$data["playrate"] = "1.5";
				}
				if($codec=='aux'){
					$data["coder"] = "aux10";
					$data["playrate"] = "";
				}
				if($codec=='aux13'){
					$data["coder"] = "aux13";
					$data["playrate"] = "1.3";
				}
				if($codec=='aux15'){
					$data["coder"] = "aux15";
					$data["playrate"] = "1.5";
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
					(array)$get_alf_player = get_alf_player($data);
					$get_video_flg = true;

					// デフォルトで取得できなかった場合は別取得
					if( isset($get_alf_player['err']) ){
					} else  {
						$get_alf_player['err']=0;
					}
					if ($get_alf_player['err']==0){
					} else {
						if($codec==''){
							$data["coder"] = "480px10";
							$data["playrate"] = "";
						}
						if($codec=='x13'){
							$data["coder"] = "480px13";
							$data["playrate"] = "1.3";
						}
						if($codec=='x15'){
							$data["coder"] = "480px15";
							$data["playrate"] = "1.5";
						}
						if($codec=='aux'){
							$data["coder"] = "aux";
							$data["playrate"] = "";
						}
						if($codec=='aux13'){
							$data["coder"] = "aux13";
							$data["playrate"] = "1.3";
						}
						if($codec=='aux15'){
							$data["coder"] = "aux15";
							$data["playrate"] = "1.5";
						}
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
					}
					
					// デフォルトプレイヤーで取得できなかった場合は低解像度版の取得
					if( isset($get_alf_player['err']) ){
					} else  {
						$get_alf_player['err']=0;
					}
					if ($get_alf_player['err']==1){
						if(
							    $codec=='aux'
							 || $codec=='aux10'
							 || $codec=='aux13'
							 || $codec=='aux15'
						){
							$get_video_flg = false;
							$player_msg = '動画ファイルは存在しません。';
						} else {
							$player_msg = 'この動画は高画質版が存在しないので、低解像度版で表示しております。';
							$get_alf_player_msg1 = '1:'.$get_alf_player['message'];
							$get_alf_player = '';
							
							$data = array(
								"ckey" => $arr_ckey_list['idkey'],
								"coder" => '180p',
								"type" => '',
								// "player" => 'akplayerlist',
								"player" => 'DEFAULT',
								"player.type" => '',
								//"player.width" => '180',
								//"player.height" => '135',
								//"player.controller" => 'none',
								"uid" => $uid,
							);

							// デフォルトで取得できなかった場合は別取得
							if (isset($get_alf_player['err']) && $get_alf_player['err']==0){
							} else {
								if($codec==''){
									$data["coder"] = "180p";
									$data["playrate"] = "";
								}
								if($codec=='x13'){
									$data["coder"] = "180px13";
									$data["playrate"] = "1.3";
								}
								if($codec=='x15'){
									$data["coder"] = "180px15";
									$data["playrate"] = "1.5";
								}
							}
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
							(array)$get_alf_player = get_alf_player($data);

							if( isset($get_alf_player['err']) ){
							} else  {
								$get_alf_player['err']=0;
							}
							if ($get_alf_player['err']==0){
								$player = $get_alf_player['player'];
							} else {
								$player_msg = '動画ファイルは存在しません。';
								$get_alf_player_msg2 = '2:'.$get_alf_player['message'];
							}
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
							$next_contents_column_so = 'contents_contents'.$next_ccno."so";
							$sql = "SELECT $next_contents_column, $next_contents_column_so FROM tbl_product WHERE del_flg='0' AND product_id='$pid'";
							$res = $objDbConnect->query_fetch($sql);
							if ($res){
								if ($res["$next_contents_column"] != ''){
									$next_vid = $res["$next_contents_column"];
									$next_ftn = 'contents_free_time'.$next_ccno;
									$next_contents_flg = true;
									if ($res["$next_contents_column_so"] != ''){
										if(strpos($codec,'aux') !== false){
											$next_vid2 = $res["$next_contents_column_so"];
										}
									} else {
										if(strpos($codec,'aux') !== false){
											$next_contents_flg = false;
										}
									}
								}
							}
							
							// テストの設定がある場合は次のコンテンツに飛ばない
							$sql = "SELECT product_kind_flg FROM tbl_product_elearning WHERE product_id='$pid'";
							$res = $objDbConnect->query_fetch($sql);
							if ($res){
								if($res['product_kind_flg']=='3'){
									$sql = "SELECT exam_id_test FROM rel_product_contents WHERE product_id='$pid' AND contents_no='$ccno'";
									$res = $objDbConnect->query_fetch($sql);
									if ($res){
										if($res['exam_id_test']!=''){
											$next_contents_flg = false;
										}
									}
								}
							}
						}
						
						// 動画の再生完了判断時間の算出(bookmark.phpの中で使用)
						$sql = "SELECT TIME_TO_SEC(alfstream_duration) AS alfstream_duration_sec FROM video_alfstream_status WHERE video_id = '$vid'";
						if($vid2!=''){
							$sql = "SELECT TIME_TO_SEC(alfstream_duration) AS alfstream_duration_sec FROM video_alfstream_status WHERE video_id = '$vid2'";
						}
						$res = $objDbConnect->query_fetch($sql);
						if ($res){
							if ($res['alfstream_duration_sec'] <= PLAYER_ALL_READING_JUDGE_TIME){
								// 設定時間以下の場合は1秒として、講座受講完了とする
								$time2 = 1;
								$time3 = 1;
							} else {
								$time2 = $res['alfstream_duration_sec'] - PLAYER_ALL_READING_JUDGE_TIME;
								$time3 = $res['alfstream_duration_sec'];
							}
						}
						
						// 動画名を取得
						$sql = "SELECT ";
					}
					
				} else {
					$player_msg = '動画ファイルは存在しません。';
				}
				
			}
			
		}
	} else {
		echo 'エラーが発生しました。[005]';
		$objDbConnect->close();
		exit;
	}
}

$bookmark_time_sec_rate = 0;
$rate = 1;
if($codec=='x13'){
	$rate = 1.3;
}
if($codec=='x15'){
	$rate = 1.5;
}
if($codec=='aux'){
	$rate = 1;
}
if($codec=='aux13'){
	$rate = 1.3;
}
if($codec=='aux15'){
	$rate = 1.5;
}
$bookmark_time_sec_rate = intval($bookmark_time_sec / $rate);

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$html_head_title = '講座動画';
if (isset($product_list['product_name'])){
	if ($product_list['product_name']!=''){
		$html_head_title = $product_list['product_name'];
	}
}
$template->assign('html_head_title', $html_head_title);

$template->assign('pid', $pid);
$template->assign('buy_flg', $buy_flg);
$template->assign('buy_wait_flg', $buy_wait_flg);
$template->assign('view_flg', $view_flg);
$template->assign('arr_chapter_list', $arr_chapter_list);
$template->assign('ak_initialized_param', $ak_initialized_param);
$template->assign('player', $player);
$template->assign('player_msg', $player_msg);
$template->assign('get_alf_player_msg1', $get_alf_player_msg1);
$template->assign('get_alf_player_msg2', $get_alf_player_msg2);
$template->assign('user_id', $user_id);
$template->assign('video_id', $vid);
$template->assign('video_id2', $vid2);
$template->assign('codec', $codec);
$template->assign('bookmark_time_sec', $bookmark_time_sec);
$template->assign('bookmark_time_sec_rate', $bookmark_time_sec_rate);
$template->assign('contents_free_time', $contents_free_time);
$template->assign('view_btn', $view_btn);
$template->assign('st_login_check', $st_login_check);
$template->assign('free_product_flg', $free_product_flg);
$template->assign('set_btn_flg', $set_btn_flg);

$template->assign('next_vid', $next_vid);
$template->assign('next_vid2', $next_vid2);
$template->assign('next_ftn', $next_ftn);
$template->assign('next_ccno', $next_ccno);
$template->assign('next_contents_flg', $next_contents_flg);

$template->assign('time2', $time2);
$template->assign('time3', $time3);

$template->assign('video_logic_name', $video_logic_name);
$template->assign('video_popup', $video_popup);

$template->assign('isSP', $term);
$template->assign('csrf_token', csrf_token_get());

$template->layout_alfstream('player/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>
