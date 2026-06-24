<?
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 

$isPad = false;
if(preg_match("/iPad/", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(preg_match("/iPhone/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPad/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPod/", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(preg_match("/Android/", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$err_flg = false;
$free_product_flg = false;
$st_login_check = st_login_check(); // ログインチェック
$android_ver_flg = get_android_ver_flg(); // android version flg
$player = '';
$player_msg = '';

if (isset($_GET['pid']) && isset($_GET['vid']) && isset($_GET['cftname'])){
	$pid = $_GET['pid'];
	$vid = $_GET['vid'];
	$cftname = $_GET['cftname'];
	$uid = '';
	$bookmark_time_sec = 0;
	
	if(!cmCheckInput($pid, 'CK_NUM') && !cmCheckInput($vid, 'CK_NUM')){
		if (strpos($cftname, 'contents_free_time') !== false){
			// プレイヤーの取得
			$sql = "SELECT idkey FROM video WHERE video_id='$vid'";
			$arr_ckey_list = $objDbConnect->query_fetch($sql);
			
			if ($isApple){
				$type = 'iphone';
			} elseif ($isAndroid){
				$type = 'android';
			} else {
				$type = '';
			}
			
			if ($st_login_check){
				$uid = $_SESSION['user']['id'];
			} else {
				$uid = 0;
			}
			
			if ($android_ver_flg){
				$coder = '180p';
			} else {
				$coder = '';
			}
			
			if ( $isApple || $isAndroid ){
				if( $isAndroidTablet ){
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder" => $coder,
						"type" => $type,
						"player" => 'akplayerlist',
						"player.type" => '',
						"player.width" => '180',
						"player.height" => '135',
						"player.controller" => 'none',
						"uid" => $uid,
					);
				} elseif( $isPad ){
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder" => $coder,
						"type" => $type,
						"player" => 'akplayerlist',
						"player.type" => '',
						"player.width" => '180',
						"player.height" => '135',
						"player.controller" => 'none',
						"uid" => $uid,
					);
				} else {
					$data = array(
						"ckey" => $arr_ckey_list['idkey'],
						"coder" => $coder,
						"type" => $type,
						"player" => 'akplayerlist',
						"player.type" => '',
						"player.width" => '240',
						"player.height" => '180',
						"player.controller" => 'none',
						"uid" => $uid,
					);
				}
			} else {
				$data = array(
					"ckey" => $arr_ckey_list['idkey'],
					"coder" => $coder,
					"type" => $type,
					"player" => 'akplayerlist',
					"player.type" => '',
					"player.width" => '180',
					"player.height" => '135',
					"player.controller" => 'none',
					"uid" => $uid,
				);
			}
			
			$get_alf_player = get_alf_player($data);
			
			if ($get_alf_player['err']==0){
				$player = $get_alf_player['player'];
			} else {
				$player_msg = '動画ファイルは存在しません。';
			}
			
			// 有料動画無料部分ありチェック
			$free_check = true;
			$sql = "SELECT $cftname, price FROM tbl_product WHERE product_id='$pid'";
			$ret = $objDbConnect->query_fetch($sql);
			if ($ret){
				if ($ret['price']=='0'){
					$free_product_flg = true;
				}
				$free_time = $ret["$cftname"];
				
				// ログインチェック
				if (st_login_check()){
					$uid = $_SESSION['user']['id'];
					
					// しおり機能
					//$sql = "SELECT TIME_TO_SEC(bookmark_time) AS bookmark_time_sec FROM tbl_bookmark WHERE student_id='".$uid."' AND video_id='".$vid."'";
					// 視聴ログから視聴済み時間の最大値を読んで、その位置から再生する(仕様変更)
					$sql = "SELECT TIME_TO_SEC(duration_reading) AS bookmark_time_sec FROM report_user_video_viewed WHERE student_id='".$uid."' AND video_id='".$vid."'";
					$ret_bookmark = $objDbConnect->query_fetch($sql);
					if ($ret_bookmark){
						$bookmark_time_sec = $ret_bookmark['bookmark_time_sec'];
					}
					
					// 購入済み・視聴期間超え確認
					if (buy_and_open_period_date_check_detail($objDbConnect, $pid, $_SESSION['user']['id'])){
						$free_check = false;
					}
				}
			}
			
		} else {
			$err_flg = true;
			echo 'エラー[003]';
		}
		
	} else {
		$err_flg = true;
		echo 'エラー[002]';
	}
	
} else {
	$err_flg = true;
	echo 'エラー[001]';
}
?>

<?php if (!$err_flg){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Pragma" content="no-cache">
	<title>JFBA総合研修サイト</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="UTF-8" />
	<title></title>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-store">
	<meta http-equiv="Expires" content="-1">
	<!--<meta http-equiv="Expires" content="Thu, 24 Jan 2013 16:00:00 GMT"> -->
	<script type="text/javascript" src="/js/alfstream/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="/js/alfstream/jquery-ui-1.8.14.custom.min.js"></script>
	<script type="text/javascript" src="/js/alfstream/jquery.form.js"></script>
	<script type="text/javascript" src="/js/alfstream/jquery.json-2.2.min.js"></script>
	<script type="text/javascript" src="/js/alfstream/sha1.js"></script>
	<script type="text/javascript">
		<?php // 初期化 ?>
		var ak_initialized_flg = false;
		function ak_initialized() {
			if (!ak_initialized_flg) {
				ak_initialized_flg = true;
			}
		}
		
		var ak_eventStaus = null;
		var ak_eventTime = null;
		function ak_initialized_on() {
			if (typeof(jwplayer) == "function") {
				jwplayer().onReady(function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					if (!ak_initialized_flg) {
						ak_initialized(null);
					}
				});
				jwplayer().onPlay(function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					if (ak_eventStaus != "Idle") {
						ak_eventStaus = "Play";
						ak_event_all("timeupdate", 0, jwplayer().getPosition());
					}
					else {
						ak_eventStaus = "Play";
						ak_event_all("timeupdate", 0, 0);
					}
				});
				jwplayer().onPause(function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Pause";
				});
				jwplayer().onIdle(function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Idle";
				});
				jwplayer().onComplete(function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Complete";
					ak_event_all("complete", 0, jwplayer().getPosition());
				});

				return;
			}
			else if (typeof(alfplayer) == "function") {
				alfplayer().on("ready", function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					if (!ak_initialized_flg) {
						ak_initialized(null);
					}
				});
				alfplayer().on("play", function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Play";
					ak_event_all("timeupdate", 0, alfplayer().currentTime());
				});
				alfplayer().on("pause", function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Pause";
				});
				alfplayer().on("complete", function(){
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Complete";
					ak_event_all("complete", 0, alfplayer().currentTime());
				});

				return;
			}

		        setTimeout(function(){
		            ak_initialized_on();
		        }, 50);
		}
		
		var resume = 0;
		var comp_flg = false;
		var first_flg = true;
		function ak_event_all(type, idx, time) {
			// しおり記憶を2回に1回実行させる判断用
			resume = resume + 1;
			var ans = resume % 2;
			if (ans==0){
				var resume_flg = false;
			} else {
				var resume_flg = true;
			}
			
			if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
			
			<?php // 初回のみしおり位置での再生 ?>
			<?php if($isApple == true){ ?>
				if (time >= 1){
					if (first_flg){
						first_flg = false;
						<?php if ($bookmark_time_sec!=0){ ?>
							alfplayer().seek(<?php echo $bookmark_time_sec; ?>);
						<?php } ?>
					}
				}
			<?php } else { ?>
				if (time >= 1){
					if (first_flg){
						first_flg = false;
						<?php if ($bookmark_time_sec!=0){ ?>
							ak_playAndSeek(0, <?php echo $bookmark_time_sec; ?>);
						<?php } ?>
					}
				}
			<?php } ?>
			
			<?php if($isApple == true){ ?>
			if (comp_flg){
				type = "complete";
			}
			<?php } ?>
			
			<?php // 再生時間が変わった ?>
			if (type == "timeupdate") {
				if (time >= 5){
					if (resume_flg){
						<?php
						// しおりを記憶
						if ($uid!='' && $vid!=''){ ?>
							var url = "./../player/bookmark.php";
							var data = {};
							data["student_id"] = "<?php echo $uid; ?>";
							data["movie_id"] = "<?php echo $vid; ?>";
							data["time"] = time;
							
							$.ajax({
								type		: "GET",
								cache		: false,
								url			: url,
								data		: data,
								success		: function(res){
									console.log(res);
								},
								error		: function(XMLHttpRequest, textStatus, errorThrown) {
									console.log("error:"+XMLHttpRequest);
								}
							});
						<?php } ?>

					}
				}
				<?php
				// 有料動画の無料範囲が設定されているか判断(秒をセットする)
				if ($free_time!='0' && $free_check){ ?>
					if (idx == 0 && time > <?php echo $free_time; ?>) {
						<?php
						// 無料商品の場合は最後まで閲覧可能
						if (!$free_product_flg){ ?>
							if (typeof(jwplayer) == "function") {
								jwplayer().stop();
								<?php
								// ブックマークの削除
								if ($uid!='' && $vid!=''){ ?>
									var url = "./../player/bookmark_delete.php";
									var data = {};
									data["student_id"] = "<?php echo $uid; ?>";
									data["movie_id"] = "<?php echo $vid; ?>";
									
									$.ajax({
										type		: "GET",
										cache		: false,
										url			: url,
										data		: data,
										success		: function(res){
											// console.log(res);
										},
										error		: function(XMLHttpRequest, textStatus, errorThrown) {
											// console.log("error:"+XMLHttpRequest);
										}
									});
								<?php } ?>
								if (ak_eventStaus == "Play") {
									alert("続きは有料になります。");
									if (confirm("購入しますか？")){
										buy_exe();
									} else {
										window.parent.document.location.reload();
										<?php if($isApple == true){ ?>
										location.reload();
										<?php } ?>
									}
								}
							}
							else if (typeof(alfplayer) == "function") {
								<?php if($isApple == true){ ?>
									//alfplayer().pause();
									//Sleep( 1 );
									alfplayer().seek(99999999);
									Sleep( 2 );
									setTimeout(iPhoneBuy(), 4000);

									//alfplayer().pause();
								<?php } else { ?>
									alfplayer().stop();
								<?php } ?>


								<?php if($isApple == true){ ?>
								<?php } else { ?>
								alert("続きは有料になります。");
								alfplayer().pause();
								<?php
								// ブックマークの削除
								if ($uid!='' && $vid!=''){ ?>
									var url = "./../player/bookmark_delete.php";
									var data = {};
									data["student_id"] = "<?php echo $uid; ?>";
									data["movie_id"] = "<?php echo $vid; ?>";
									
									$.ajax({
										type		: "GET",
										cache		: false,
										url			: url,
										data		: data,
										success		: function(res){
											// console.log(res);
										},
										error		: function(XMLHttpRequest, textStatus, errorThrown) {
											// console.log("error:"+XMLHttpRequest);
										}
									});
								<?php } ?>
								<?php if($isApple == true){ ?>
								comp_flg = true;
								Sleep( 2 );
								<?php } ?>
								
								if (confirm("購入しますか？")){
									buy_exe();
								} else {
									window.parent.document.location.reload();
									<?php if($isApple == true){ ?>
									<?php } else { ?>
										location.reload();
									<?php } ?>
								}
								<?php } ?>
								
							}
						<?php } ?>
					}
				<?php } ?>
			}
			<?php // 再生終了 ?>
			else if (type == "complete"){
				<?php
				// ブックマークの削除
				if ($uid!='' && $vid!=''){ ?>
					var url = "./../player/bookmark_delete.php";
					var data = {};
					data["student_id"] = "<?php echo $uid; ?>";
					data["movie_id"] = "<?php echo $vid; ?>";
					
					$.ajax({
						type		: "GET",
						cache		: false,
						url			: url,
						data		: data,
						success		: function(res){
							// console.log(res);
						},
						error		: function(XMLHttpRequest, textStatus, errorThrown) {
							// console.log("error:"+XMLHttpRequest);
						}
					});
				<?php } ?>
			}
			
			if (typeof(alfplayer) == "function") {
				if (ak_eventStaus == "Play") {
				        ak_eventTime = setTimeout(function(){
				            ak_event_all(type, idx, alfplayer().currentTime());
				        }, 500);
				}
			}
			else if (typeof(jwplayer) == "function") {
				if (ak_eventStaus == "Play") {
				        ak_eventTime = setTimeout(function(){
				            ak_event_all(type, idx, jwplayer().getPosition());
				        }, 500);
				}
			}
		}

		function ak_play() {
		    if (typeof(jwplayer) == "function") {
		        jwplayer().play();
		    }
		    else if (typeof(alfplayer) == "function") {
		        alfplayer().play();
		    }
		    else {
		        setTimeout(function(){
		            ak_play();
		        }, 100);
		    }
		}

		function ak_playRate(rate) {
		    if (typeof(alfplayer) == "function") {
		        alfplayer().setPlayRate(rate);
		    }
		    else{
		        setTimeout(function(){
		            ak_playRate(rate);
		        }, 100);
		    }
		}

		function ak_playAndSeek(idx, time) {
		    if (typeof(jwplayer) == "function") {
			var time2 = (Math.floor(time / 5) + 1) * 5;
		        jwplayer().seek(time2);

		        var po = jwplayer().getPosition();
		        if (ak_eventStaus != "Play" && po == 0) { jwplayer().play(); }

		        setTimeout(function() {
		           ak_playAndSeekCheck(idx, time);
		        }, 1000);
		    }
		    else if (typeof(alfplayer) == "function") {
		        alfplayer().playAndSeek(idx, time);
		    }
		    else{
		        setTimeout(function(){
		            ak_playAndSeek(idx, time);
		        }, 100);
		    }
		}

		function ak_playAndSeekCheck(idx, time) {
		    if (typeof(jwplayer) == "function") {
			var time2 = (Math.floor(time / 5) + 1) * 5;
		        var pos = jwplayer().getPosition();
			var pos2 = (Math.floor(pos / 5) + 1) * 5;
		        if (pos2 < time2) {
		            jwplayer().seek(time2);
		        }
		        else {
		            return;
		        }

		        setTimeout(function() {
		           ak_playAndSeekCheck(idx, time);
		        }, 1000);
		    }
		}

		function ak_setSeek(idx, seeks) {
		    if (typeof(jwplayer) == "function") {
		    }
		    else if (typeof(alfplayer) == "function") {
		        alfplayer().setSeek(idx, seeks);
		    }
		    else{
		        setTimeout(function(){
		            ak_setSeek(idx, seeks);
		        }, 100);
		    }
		}

		function ak_pause() {
		    if (typeof(jwplayer) == "function") {
		        jwplayer().stop();
		    }
		    else if (typeof(alfplayer) == "function") {
		        alfplayer().pause();
		    }
		    else{
		        setTimeout(function(){
		            ak_pause();
		        }, 100);
		    }
		}
		
		$(function() {
			ak_initialized_on();
		});
		
		//$(window).unload();
		//window.onunload = function(){location.reload();}
		
		// 以下、alfstreamと関連のないスクリプト
		function buy_exe(){
			<?php if($isApple == true){ ?>
				//ak_playAndSeek(0, 99999999);
				alfplayer().seek(99999999);
				window.close();
			<?php } ?>
			window.parent.document.buyForm.submit();
			<?php if($isApple == true){ ?>
				//ak_playAndSeek(0, 99999999);
				alfplayer().seek(99999999);
			<?php } ?>
		}
		function frame_reload(){
			location.reload();
		}
function Sleep( T ){ 
var d1 = new Date().getTime(); 
var d2 = new Date().getTime(); 
while( d2 < d1+1000*T ){    //T秒待つ 
d2=new Date().getTime(); 
} 
return; 
}

function iPhoneBuy(){
	alert("続きは有料になります。");
	alfplayer().pause();
	<?php
	// ブックマークの削除
	if ($uid!='' && $vid!=''){ ?>
		var url = "./../player/bookmark_delete.php";
		var data = {};
		data["student_id"] = "<?php echo $uid; ?>";
		data["movie_id"] = "<?php echo $vid; ?>";
		
		$.ajax({
			type		: "GET",
			cache		: false,
			url			: url,
			data		: data,
			success		: function(res){
				// console.log(res);
			},
			error		: function(XMLHttpRequest, textStatus, errorThrown) {
				// console.log("error:"+XMLHttpRequest);
			}
		});
	<?php } ?>
	<?php if($isApple == true){ ?>
	comp_flg = true;
	Sleep( 2 );
	<?php } ?>
	
	if (confirm("購入しますか？")){
		buy_exe();
	} else {
		window.parent.document.location.reload();
		<?php if($isApple == true){ ?>
		<?php } else { ?>
			location.reload();
		<?php } ?>
	}
}

	</script>
</head>
<body onunload="" style="margin:0;padding:0;">
	<form name="buyForm" action="https://<?php echo $_SERVER['SERVER_NAME']; ?>/settlement/index.php" method="post">
		<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
	</form>
	
	<?php echo $player_msg; ?>
	
	<?php echo $player; ?>
</body>
</html>
<?php } ?>
<?php
$objDbConnect->close();
exit();
?>