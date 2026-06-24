<!--{if $next_contents_flg}-->
<form name="playerForm" action="/player/index.php?term=pc" method="post">
	<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
	<input type="hidden" name="vid" id="hid_vid" value="<!--{$next_vid|escape}-->" />
	<input type="hidden" name="vid2" id="hid_vid2" value="<!--{$next_vid2|escape}-->" />
	<input type="hidden" name="ftn" id="hid_ftn" value="<!--{$next_ftn|escape}-->" />
	<input type="hidden" name="ccno" id="hid_ccno" value="<!--{$next_ccno|escape}-->" />
	<input type="hidden" name="view_btn" id="hid_view_btn" value="start" />
	<input type="hidden" name="codec" id="hid_codec" value="<!--{$codec|escape}-->" />
</form>
<!--{/if}-->

<style type="text/css">
ul#player_list{
list-style:none;
width:640px;
height:120px;
overflow:auto;
padding:0;
margin:10px;
border:1px solid #d9d9d9;
}
#player_list li{

}
#player_list li a{
background-color:#f0f0ef;
padding:0px 0px 0px 20px;
display:block;
color:#666666;
	text-decoration: none;
font-family: "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS PGothic", sans-serif;

}

#player_list li a:hover{
background: url(/img/ar4.png) no-repeat 1% 50%;
}
#player_list li a:active{
background: url(/img/ar4.png) no-repeat 1% 50%;
}

.time{font-weight:bold;font-size:12px;}
.chapter_name{margin-left:30px;font-size:12px;}


.btn input:hover {
cursor:pointer;
opacity:0.7;
filter: alpha(opacity=70);        /* ie lt 8 */
-ms-filter: "alpha(opacity=70)";  /* ie 8 */
-moz-opacity:0.7;                 /* FF lt 1.5, Netscape */
-khtml-opacity: 0.7;              /* Safari 1.x */
}

</style>

<!--{if $view_flg}-->
	<script type="text/javascript">
		<!--{* // 初期化 *}-->
		//+++++++++++++++++++++++++++
		var video_codec_type ="<!--{$codec}-->";
		//+++++++++++++++++++++++++++
		var pop_up_time = 1200;
		var pop_stop_time = 1260;
		//+++++++++++++++++++++++++++
		var time3 = <!--{$time3}-->;
		var pause_time = 0;
		var video_pop_ok = false;
		var video_pop_time = 0;
		var video_pop_time_before = 0;
		//+++++++++++++++++++++++++++
		var ak_initialized_flg = false;

		function ak_initialized() {
			<!--{* // プレイヤーボタンの制御 *}-->
			<!--{if $set_btn_flg}-->
				<!--{if $video_id2!=''}-->
					ak_setButton({
						// 'Prev' : 'visible',
						// 'Stop' : 'visible',
						// 'Pause' : 'visible',
						// 'Play' : 'visible',
						'Next' : 'disable',
						// 'back': 'visible',
						'skip': 'disable',
						// 'Speedbar': 'disable',
						'Seekbar': 'visible'
					});
				<!--{else}-->
					ak_setButton({
						// 'Prev' : 'visible',
						// 'Stop' : 'visible',
						// 'Pause' : 'visible',
						// 'Play' : 'visible',
						'Next' : 'disable',
						// 'back': 'visible',
						'skip': 'disable',
						// 'Speedbar': 'disable',
						'Seekbar': 'disable'
					});
				<!--{/if}-->
			<!--{else}-->
				<!--{if $video_id2!=''}-->
					ak_setButton({
						'Seekbar': 'visible',
					});
				<!--{else}-->
					ak_setButton({
						'Seekbar': 'disable',
					});
				<!--{/if}-->

				<!--{if $next_contents_flg}-->
					// 次に進むリンクの表示設定
					ak_hasNextPlaylistLink('Post:playerForm');
				<!--{/if}-->
			<!--{/if}-->
		
			<!--{* // ボリュームを設定(0～1) *}-->
			ak_volume(0.5);
		
			<!--{* // 再生スピード間隔の設定 *}-->
			ak_setPlayRateInterval(0.2);
		
			<!--{* // 再生位置の設定 *}-->
			if (!ak_initialized_flg) {
				ak_initialized_flg = true;
				<!--{if $ak_initialized_param!=""}-->
					ak_setSeek(0, [<!--{$ak_initialized_param|escape}-->]);
				<!--{/if}-->

				<!--{* // chromeの場合はエラーになるので自動再生させない *}-->
				<!--{if $ischrome}-->
				<!--{else}-->
					<!--{if $view_btn=='start'}-->
						ak_playAndSeek(0, 0);
					<!--{elseif $view_btn=='bookmark'}-->
						<!--{if $bookmark_time_sec!=""}-->
							ak_playAndSeek(0, <!--{$bookmark_time_sec|escape}-->);//bookmark_time_sec
						<!--{else}-->
							ak_playAndSeek(0, 0);//non_bookmark_time_sec
						<!--{/if}-->
					<!--{/if}-->
				<!--{/if}-->
			}
	}
	
	var ak_seekCount = 2;
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
					console.log("[" + ak_eventStaus + "]");
					ak_event_all("timeupdate", 0, jwplayer().getPosition());
				}
				else {
					ak_eventStaus = "Play";
					console.log("[" + ak_eventStaus + "]");
					ak_event_all("timeupdate", 0, 0);
				}
			});
			jwplayer().onPause(function(){
				if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
				ak_eventStaus = "Pause";
				console.log("[" + ak_eventStaus + "]");
			});
			jwplayer().onIdle(function(){
				if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
				ak_eventStaus = "Idle";
				console.log("[" + ak_eventStaus + "]");
			});
			jwplayer().onComplete(function(){
				if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
				ak_eventStaus = "Complete";
				console.log("[" + ak_eventStaus + "]");
				ak_event_all("complete", 0, jwplayer().getPosition());
			});
			jwplayer().onSeek(function(evt){
				// シークが無効の場合、警告を出してシークする前に戻す
				<!--{if $set_btn_flg && $video_id2==''}-->
					if (timeSeek == 0) {
						if (evt.position < evt.offset) {
							jwplayer().seek(evt.position).pause(true);
							alert("初回の再生については先に進めません");
							timeBeforeFlg = false;
						}
					}
				<!--{/if}-->
				<!--{if $video_id2==''}-->
					if( video_pop_ok == true ){
						if (timeSeek == 0) {
							if (evt.position < evt.offset) {
								jwplayer().seek(evt.position).pause(true);
								alert("再生については先に進めません");
								timeBeforeFlg = false;
							}
						}
					}
				<!--{/if}-->
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
				console.log("[" + ak_eventStaus + "]");
				ak_event_all("timeupdate", 0, alfplayer().currentTime());
			});
			alfplayer().on("pause", function(){
				if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
				ak_eventStaus = "Pause";
				console.log("[" + ak_eventStaus + "]");
			});
			alfplayer().on("complete", function(){
				if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
				ak_eventStaus = "Complete";
				console.log("[" + ak_eventStaus + "]");
				ak_event_all("complete", 0, alfplayer().currentTime());
			});
			alfplayer().on("fullscreenchange", function(){
				if(!document.fullScreen && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msRequestFullscreen) {
					if ($('.fullscreen').length == 0) {
						resizeTo(695, 725);
						moveTo(50, 50);
						focus();
					}
					else {
						moveTo(0, 0);
						resizeTo(screen.width, screen.height);
						focus();
					}
				}
			});

			return;
		}

	        setTimeout(function(){
	            ak_initialized_on();
	        }, 50);
	}

	// 前のシーク再生時間
	<!--{if $view_btn=='bookmark' && $bookmark_time_sec!=""}-->
	var timeSeek = <!--{$bookmark_time_sec|escape}-->;
	var timeBefore = 0;
	<!--{else}-->
	var timeSeek = 0;
	var timeBefore = 0;
	<!--{/if}-->
	var timeBeforeFlg = false;
	
	var resume = 0;
	var insert_report_user_video_viewed_flg = 1;
	function ak_event_all(type, idx, time) {
		if (type == "timeupdate") {
			<!--{if $set_btn_flg}-->
			// シークが無効の場合、警告を出してシークする前に戻す
			if (ak_eventStaus == "Play") {
				if (!timeBeforeFlg) {
					// 2.5秒以上進んでいたら前のシーク再生時間に戻る
					console.log("[timeSeek:" + timeSeek + "][time:" + time + "][timeBefore:" + timeBefore + "]12");
					if (timeSeek == 0 && time - timeBefore > 2.5) {
						timeBeforeFlg = true;
						if (typeof(alfplayer) == "function") {

							<!--{if $video_id2==''}-->
								alfplayer().pause();
								setTimeout(function(){
									alfplayer().seek(timeBefore - 0.5);
									var timeBeforeTimer = setInterval(function() {
										var _time = alfplayer().currentTime();
										if (_time - timeBefore <= 2.5) {
											clearInterval(timeBeforeTimer);
											alfplayer().pause();
											setTimeout(function(){
												alert("初回の再生については先に進めません");
												timeBeforeFlg = false;
											}, 100);
										}
									}, 500);
								}, 100);
							<!--{/if}-->

						}
						else if (typeof(jwplayer) == "function") {
						}
					}
					else {
						console.log("[time:" + time + "][timeBefore:" + timeBefore + "]AAA");
						timeBefore = time;
					}

					if (timeSeek > 0) {
						if (time >= timeSeek) { timeSeek = 0; }
					}
				}
			}
			<!--{/if}-->
			if( video_pop_ok == true ){
				if (ak_eventStaus == "Play") {
					if (!timeBeforeFlg) {
						// 2.5秒以上進んでいたら前のシーク再生時間に戻る
						if (timeSeek == 0 && time - timeBefore > 2.5) {
							timeBeforeFlg = true;
							//====================================
							//PC版
							if (typeof(alfplayer) == "function") {
								<!--{if $video_id2==''}-->
									alfplayer().pause();
									setTimeout(function(){
										alfplayer().seek(timeBefore - 0.5);
										var timeBeforeTimer = setInterval(function() {
											var _time = alfplayer().currentTime();
											if (_time - timeBefore <= 2.5) {
												clearInterval(timeBeforeTimer);
												alfplayer().pause();
												setTimeout(function(){
													alert("再生については先に進めません");
													timeBeforeFlg = false;
												}, 100);
											}
										}, 500);
									}, 100);
								<!--{/if}-->
							}
							else if (typeof(jwplayer) == "function") {
							}
							//====================================
						}
						else {
	                                                console.log("[time:" + time + "][timeBefore:" + timeBefore + "]AAAA");
							timeBefore = time;
						}

						if (timeSeek > 0) {
							if (time >= timeSeek) { timeSeek = 0; }
						}
					}
				}
			}
		}

		<!--{* // 再生後1度だけ、視聴履歴確認＆登録 *}-->
		if (insert_report_user_video_viewed_flg == 1){
			insert_report_user_video_viewed_flg = 0;
			<!--{if $user_id!='' && $video_id!='' && $video_id2==''}-->
				var url = "insert_report_user_video_viewed.php";
				var data = {};
				data["student_id"] = "<!--{$user_id}-->";
				data["movie_id"] = "<!--{$video_id}-->";
				
				$.ajax({
					type		: "GET",
					cache		: false,
					url			: url,
					data		: data,
					success		: function(res){
						<!--{* // console.log(res); *}-->
					},
					error		: function(XMLHttpRequest, textStatus, errorThrown) {
						<!--{* // console.log("error:"+XMLHttpRequest); *}-->
					}
				});
			<!--{/if}-->
		}
		
		if(ak_seekCount<2){
			ak_seekCount+=1;
		}
		<!--{* // しおり記憶を2回に1回実行させる判断用 *}-->
		resume = resume + 1;
		var ans = resume % 2;
		if (ans==0){
			var resume_flg = false;
		} else {
			var resume_flg = true;
		}
		
		if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
		
		<!--{* // 再生時間が変わった *}-->
		if (type == "timeupdate") {
			if (time - video_pop_time_before > 2.5) {
				video_pop_time = 0;
			} else {
				if(time - video_pop_time_before > 0){
					video_pop_time += time - video_pop_time_before;
				} else {
					video_pop_time = 0;
				}
			}
			video_pop_time_before = time;
			//console.log("[timeupdate:"+time+":"+video_pop_time_before+":"+time3+":"+video_pop_ok+"]");
			//console.log("[timeupdate:"+time+":"+video_pop_time_before+":"+time3+":"+video_pop_ok+"]");
			if( _isSp()=="sp" ){ //SP
			} else {
				//if( video_pop_time <=100 || video_pop_time >=1100 ){
					console.log("[timeSeek:" + timeSeek + "][time:" + time + "][timeBefore:" + timeBefore + "]AA");
					console.log("[timeupdate:video_pop_time="+video_pop_time+"]");
				//}
			}
			if (time >= 5){
				<!--{if $video_popup==1}-->
					//+++++++++++++++++++++++++++
					//if( time3>=(pop_up_time+60) ){
						if( video_pop_time >=pop_up_time  && !video_pop_ok ){
							pause_time = time-1;
							console.log("pause_time="+pause_time);
							video_pop_ok = true;
							video_check_pop();
						}
						if( video_pop_time >=pop_stop_time  && video_pop_ok ){
							video_check_stop();
						}
					//}
					//+++++++++++++++++++++++++++
				<!--{/if}-->
				if (resume_flg){
					<!--{* // しおりを記憶 *}-->
					<!--{if $user_id!='' && $video_id!=''}-->
						var temp_time = time;
						var url = "bookmark.php";
						var data = {};
						data["student_id"] = "<!--{$user_id}-->";
						data["movie_id"] = "<!--{$video_id}-->";
						<!--{if $video_id2!=''}-->
							data["movie_id"] = "<!--{$video_id2}-->";
						<!--{/if}-->
						data["time"] = temp_time;
						data["time2"] = "<!--{$time2}-->";
						data["time3"] = "<!--{$time3}-->";
						data["pid"] = "<!--{$pid}-->";
						
						$.ajax({
							type		: "GET",
							cache		: false,
							url			: url,
							data		: data,
							success		: function(res){
								<!--{* // console.log(res); *}-->
								if (typeof(jwplayer) == "function") {
									$("#player_type").text("player_type:jwplayer");
								} else if (typeof(alfplayer) == "function") {
									$("#player_type").text("player_type:alfplayer");
								}
								$("#video_codec_type").text("video_codec_type:" + video_codec_type);
								$("#player_output_time").text("player_output_time:" + time);
								$("#bookmark_time").text("bookmark_time:" + temp_time);
							},
							error		: function(XMLHttpRequest, textStatus, errorThrown) {
								<!--{* // console.log("error:"+XMLHttpRequest); *}-->
							}
						});
					<!--{/if}-->
				}
			}
			
			<!--{* // 有料動画の無料範囲が設定されているか判断(秒をセットする) *}-->
			<!--{if $contents_free_time!='0' && !$buy_flg}-->
				if (idx == 0 && time >= <!--{$contents_free_time|escape}-->) {
					
					<!--{* // 無料商品の場合は最後まで閲覧可能 *}-->
					<!--{if !$free_product_flg}-->
						if (typeof(jwplayer) == "function") {
							<!--{* // ブックマークの削除 *}-->
							<!--{if $user_id!='' && $video_id!=''}-->
								var url = "bookmark_delete.php";
								var data = {};
								data["student_id"] = "<!--{$user_id}-->";
								data["movie_id"] = "<!--{$video_id}-->";
								<!--{if $video_id2!=''}-->
									data["movie_id"] = "<!--{$video_id2}-->";
								<!--{/if}-->
								
								$.ajax({
									type		: "GET",
									cache		: false,
									url			: url,
									data		: data,
									success		: function(res){
										<!--{* // console.log(res); *}-->
									},
									error		: function(XMLHttpRequest, textStatus, errorThrown) {
										<!--{* // console.log("error:"+XMLHttpRequest); *}-->
									}
								});
							<!--{/if}-->
							if (ak_eventStaus == "Play") {
								ak_seekCount=-10;
								jwplayer().stop();
								setTimeout("buyConfirm()", 1000);
							}
							
						}
						else if (typeof(alfplayer) == "function") {
							<!--{* // ブックマークの削除 *}-->
							<!--{if $user_id!='' && $video_id!=''}-->
								var url = "bookmark_delete.php";
								var data = {};
								data["student_id"] = "<!--{$user_id}-->";
								data["movie_id"] = "<!--{$video_id}-->";
								<!--{if $video_id2!=''}-->
									data["movie_id"] = "<!--{$video_id2}-->";
								<!--{/if}-->
								
								$.ajax({
									type		: "GET",
									cache		: false,
									url			: url,
									data		: data,
									success		: function(res){
										<!--{* // console.log(res); *}-->
									},
									error		: function(XMLHttpRequest, textStatus, errorThrown) {
										<!--{* // console.log("error:"+XMLHttpRequest); *}-->
									}
								});
							<!--{/if}-->
							ak_seekCount=-10;
							alfplayer().stop();
							setTimeout("buyConfirm()", 1000);
						}
					<!--{/if}-->
					
					<!--{* // ブックマークの削除 *}-->
					<!--{if $user_id!='' && $video_id!=''}-->
						var url = "bookmark_delete.php";
						var data = {};
						data["student_id"] = "<!--{$user_id}-->";
						data["movie_id"] = "<!--{$video_id}-->";
						<!--{if $video_id2!=''}-->
							data["movie_id"] = "<!--{$video_id2}-->";
						<!--{/if}-->
						
						$.ajax({
							type		: "GET",
							cache		: false,
							url			: url,
							data		: data,
							success		: function(res){
								<!--{* // console.log(res); *}-->
							},
							error		: function(XMLHttpRequest, textStatus, errorThrown) {
								<!--{* // console.log("error:"+XMLHttpRequest); *}-->
							}
						});
					<!--{/if}-->
					
				}
			<!--{/if}-->
			
			<!--{* // リストのどこを再生しているか *}-->
			$('.idxall').each(function(index){
				var id_all = $(this).attr('id');
				$('#'+id_all).text("");
			});
			
			var seeks = [];
			var seeks_id = [];
			$('.idx'+idx).each(function(index){
				var id = $(this).attr('id');
				var id_time = id.replace("seek"+idx+"_", "");
				seeks.push(id);
				seeks_id.push(id_time);
			});
			
			<!--{* // 再生位置の背景色変更 *}-->
			for (var i = seeks.length - 1; i >= 0; i--) {

				if (time >= seeks_id[i] && time <= seeks_id[i + 1] ) {

					$('#'+seeks[i]+'_2').css("color","#333333");
					//$('#'+seeks[i]).css("color","#9932cc");
					$('#'+seeks[i]+'_2').css("background-color", "#ffa200");

					//$('#'+seeks[i]).text("＞");
					var check_time_data = seeks_id[i];

					break;
				}else{
					if (time >= seeks_id[i] && !seeks_id[i + 1] ){
						$('#'+seeks[i]+'_2').css("color","#333333");
						//$('#'+seeks[i]).css("color","#9932cc");
						$('#'+seeks[i]+'_2').css("background-color", "#ffa200");

						//$('#'+seeks[i]).text("＞");
						var check_time_data = seeks_id[i];

						break;
					}
				}
			}
			
			<!--{* // 再生位置以外の背景色戻し *}-->
			for (var i = 0 ; i <= seeks.length - 1; i++) {

				if (check_time_data != seeks_id[i]) {

					$('#'+seeks[i]+'_2').css("color","rgb(102, 102, 102)");
					$('#'+seeks[i]+'_2').css("background-color", "rgb(240, 240, 239)");
					//$('#'+seeks[i]).css("color","#9932cc");
					//$('#'+seeks[i]).css("background-color", "#000000");

					//$('#'+seeks[i]).text(time);
				}
			}
		}
		
		<!--{* // 再生終了 *}-->
		else if (type == "complete"){
			<!--{* // ブックマークの削除 *}-->
			<!--{if $user_id!='' && $video_id!=''}-->
				var url = "bookmark_delete.php";
				var data = {};
				data["student_id"] = "<!--{$user_id}-->";
				data["movie_id"] = "<!--{$video_id}-->";
				<!--{if $video_id2!=''}-->
					data["movie_id"] = "<!--{$video_id2}-->";
				<!--{/if}-->
				
				$.ajax({
					type		: "GET",
					cache		: false,
					url			: url,
					data		: data,
					success		: function(res){
						<!--{* // console.log(res); *}-->
					},
					error		: function(XMLHttpRequest, textStatus, errorThrown) {
						<!--{* // console.log("error:"+XMLHttpRequest); *}-->
					}
				});
			<!--{/if}-->
			
			<!--{* // 次のコンテンツが設定されている場合には自動ジャンプ *}-->
			<!--{if $next_contents_flg}-->
				document.playerForm.submit();
			<!--{/if}-->
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
	        // alfplayer().play();
		alfplayer().playItem(0);
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
		if(ak_seekCount==2){
		    ak_seekCount=0;
		    if (typeof(jwplayer) == "function") {
console.log("[jwplayer]");
			var time2 = (Math.floor(time / 5) + 1) * 5;
		        jwplayer().seek(time2);

		        var po = jwplayer().getPosition();
		        if (ak_eventStaus != "Play" && po == 0) { jwplayer().play(); }

		        setTimeout(function() {
		           ak_playAndSeekCheck(idx, time);
		        }, 1000);
		    }
		    else if (typeof(alfplayer) == "function") {
console.log("[alfplayer]");
		        alfplayer().playAndSeek(idx, time);
		    }
		    else{
console.log("[else]");
		        setTimeout(function(){
		            ak_playAndSeek(idx, time);
		        }, 1000);
		    }
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
		video_pop_time = 0;
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

	function ak_volume(val) {
	    if (typeof(jwplayer) == "function") {
	        jwplayer().setVolume(val * 100);
	    }
	    else if (typeof(alfplayer) == "function") {
	        alfplayer().volume(val);
	    }
	    else {
	        setTimeout(function(){
	            ak_volume();
	        }, 100);
	    }
	}

	function ak_setButton(conf) {
		if (typeof(jwplayer) == "function") {
			<!--{* // jwplayerはシークの制御ができない *}-->
		}
		else if (typeof(alfplayer) == "function") {
			alfplayer().setButton(conf);
		}
		else{
			setTimeout(function(){
				ak_setBtn(conf);
			}, 100);
		}
	}

	function ak_setPlayRateInterval(val) {
		if (typeof(jwplayer) == "function") {
			// jwplayer().stop();
		}
		else if (typeof(alfplayer) == "function") {
			alfplayer().setPlayRateInterval(val);
		}
		else{
			setTimeout(function(){
				ak_setPlayRateInterval(val);
			}, 100);
		}
	}

	// 前に戻るリンクの表示設定
	function ak_hasPrevPlaylistLink(url) {
	    if (typeof(jwplayer) == "function") {
	    }
	    else if (typeof(alfplayer) == "function") {
	        alfplayer().hasPrevPlaylistLink(url);
	    }
	    else{
	        setTimeout(function(){
	            ak_hasPrevPlaylistLink(url);
	        }, 100);
	    }
	}

	// 次に進むリンクの表示設定
	function ak_hasNextPlaylistLink(url) {
	    if (typeof(jwplayer) == "function") {
	    }
	    else if (typeof(alfplayer) == "function") {
	        alfplayer().hasNextPlaylistLink(url);
	    }
	    else{
	        setTimeout(function(){
	            ak_hasNextPlaylistLink(url);
	        }, 100);
	    }
	}

	$(function() {
		ak_initialized_on();
	});
	
	
	<!--{* // 以下、alfstreamと関連のないスクリプト *}-->
	function buy_exe(){
	    window.opener.document.buyForm.submit();
	    window.close();
	}
	function buyConfirm(){
		alert("続きは有料になります。");
		<!--{if $buy_wait_flg}-->
			window.close();
		<!--{else}-->
			if (confirm("購入しますか？")){
				buy_exe();
			} else {
				window.close();
			}
		<!--{/if}-->
	}

	function _isSp(){
		var ua = navigator.userAgent;
		if (ua.indexOf('iPhone') > -1 || (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') > -1)) {
			// スマートフォン
			return "sp";
		} else if (ua.indexOf('iPad') > -1 || ua.indexOf('Android') > -1) {
			// タブレット
			return "sp";
		} else {
			// PC
			return "";
		}
	}
	function video_check_pop(){
		if( _isSp()=="sp" ){ //SP
		} else {
			let KEvent = new KeyboardEvent( "keydown", { keyCode: 27 });
			document.dispatchEvent( KEvent );
			//++++++++++++++++++++++++++++++++++++++++++
			if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.msExitFullscreen) {
				document.msExitFullscreen();
			} else if (document.exitFullscreen) {
				document.exitFullscreen();
			}
			if (typeof(jwplayer) == "function") {
			} else if (typeof(alfplayer) == "function") {
				if ($('.fullscreen').length == 0) {
					resizeTo(695, 725);
					moveTo(50, 50);
					focus();
				}
			}
			//++++++++++++++++++++++++++++++++++++++++++
		}
		$('.pop_alert').show();
	}


	function video_check_stop(){
		if( video_pop_ok == true ){
			if (ak_eventStaus == "Play") {
				if (typeof(jwplayer) == "function") {
					jwplayer().pause();
				}
				else if (typeof(alfplayer) == "function") {
					alfplayer().pause();
				}
				else{
				}
			}
			if( _isSp()=="sp" ){ //SP
				alert("視聴を続ける場合は、画面をタップしてください。");
			} else {
			}
		}
	}


	function video_check_restart(){
		video_pop_time = 0;
		$('.pop_alert').hide();
		//ak_playAndSeek(0, pause_time);
		if (ak_eventStaus == "Pause") {
			if (typeof(jwplayer) == "function") {
				jwplayer().play();
			}
			else if (typeof(alfplayer) == "function") {
				alfplayer().play();
			}
			else{
			}
		}
		video_pop_ok = false;
	}
	</script>
	
	<!-- <!--{$get_alf_player_msg1}--> -->
	<!-- <!--{$get_alf_player_msg2}--> -->
	
	<!--{$player_msg}-->
	
	<div style="text-align:left;font-weight:bold;"><!--{$video_logic_name|escape}--></div>
	
	<!--{$player}-->
	
	<!--{if !empty($arr_chapter_list)}-->

	<ul id="player_list">
	<!--{foreach name=chapter_list from=$arr_chapter_list item=val}-->
		<li><!--{* 0はチャプターリストの番号 *}-->
			<a href="javascript:void(0)" onClick="ak_playAndSeek(0, <!--{$val.chapter_time_sec|escape}-->);return false;" id="seek0_<!--{$val.chapter_time_sec|escape}-->_2"><span id="seek0_<!--{$val.chapter_time_sec|escape}-->" class="idxall idx0"></span><span class="time"><!--{$val.chapter_time|escape}--></span>&nbsp;<span class="chapter_name"><!--{$val.chapter_name|escape}--></span></a>
			
		</li>
	<!--{/foreach}-->
	</ul>
	<!--{/if}-->
	
<!--{else}-->
	エラーが発生しました。[999]<br />
<!--{/if}-->

<div style="text-align:center;padding-top:10px;" class="btn">
<!--{if $isSP=="pc"}-->
	<input type="image" src="/img/btn/close_b.png" alt="閉じる" onclick="reload_parent_window()" />
	<!--<input type="image" src="/img/btn/close_b.png" alt="閉じる" onclick="window.opener.location.reload();window.close();" />-->
<!--{else}-->
	<a href="/product/detail.php?pid=<!--{$pid|escape}-->">戻る</a>
<!--{/if}-->
<!--{if $buy_wait_flg}-->
<!--{else}-->
	<!--{if !$buy_flg}-->
	<input type="image" src="/img/btn/buy.png" alt="購入する" onclick="buy_exe();" />
	<!--{/if}-->
<!--{/if}-->
</div>


<!--{if $isSP=="pc"}-->
	<div style="padding-top:10px;">
		<span style="color:red;">※動画の視聴完了後，「閉じる」ボタンを押してください<br />&nbsp;&nbsp;（プレーヤーを閉じないと視聴履歴が確定しません）</span>
		<br />
		※全画面表示を解除する場合は，キーボードの「ESC」ボタンを押します
	</div>
<!--{/if}-->


<div id="pop_alert" class="pop_alert" style="display:none;" onclick="video_check_restart()">
	<div id="msg_area" class="msg_area">
		引き続き視聴しますか？
	</div>
	<div id="btn_area" class="btn_area">
		<img id="btn_movie_restart" class="btn_movie_restart" src="/img/movie_start.png">
	</div>
</div>

<div id="debag_area" style="display:none;">
	<div id="video_codec_type"></div>
	<div id="player_type"></div>
	<div id="player_output_time"></div>
	<div id="bookmark_time"></div>
</div>
<style type="text/css">
	.pop_alert{
		display: none;
		position: fixed;
		width: 120%;
		height: 120%;
		background-color:#000000;
		top: -10%;
		left: -10%;
		opacity: 0.5;
		z-index: 9999;
	}
	.pop_alert > .msg_area{
		width: 100%;
		color:#ffffff;
		text-align: center;
		margin: 20% auto auto auto;
		height: auto;
	}
	.pop_alert > .btn_area{
		width: 100%;
		color:#ffffff;
		text-align: center;
		margin: 0 auto;
		height: auto;
	}
	.pop_alert > .btn_area > .btn_movie_restart{
		width: 128px;
		height: 128px;
	}

	<!--{if $isSP=="pc" && $video_id2!=''}-->
		/*
		.alfplayer_base{
			top: -370px;
			position: absolute;
		}
		*/
	<!--{/if}-->
</style>
<script type="text/javascript">
	<!--{if $isSP=="pc" && $video_id2==''}-->
		seekbarDisplayCh();
		function seekbarDisplayCh(){
			<!--{if $set_btn_flg}-->
			//if (!ak_initialized_flg) {
				if( $('.alfplayer_base').length ){
					$(".alfplayer_base").css('height','430px');
				}
				if( $('.baseSeekBarElem').length ){
					$(".baseSeekBarElem").css('display','none');
				}
				setTimeout(function(){
					seekbarDisplayCh();
				}, 500);
			//}
			<!--{/if}-->
		}
	<!--{/if}-->

	function reload_parent_window(){
		//自身を開いたウィンドウが存在する場合
		if((window.opener && !window.opener.closed)){
			window.opener.location.reload();
			window.close();
		//自身がiframeの子である場合
		}else if(window!=window.parent){
			window.parent.location.reload();
		}
	}

	document.onkeydown = function(e) {
		var keyCode = false;
		if (e) event = e;
		if (event) {
			if (event.keyCode) {
				keyCode = event.keyCode;
			} else if (event.which) {
				keyCode = event.which;
			}
		}
		if(keyCode==13 || keyCode==32 || keyCode==229){
			if ($('#pop_alert').css('display') == 'block') {
				video_check_restart();
			}
		}
	};
</script>
