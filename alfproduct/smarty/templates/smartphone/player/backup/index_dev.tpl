<!--{if $next_contents_flg}-->
<form name="playerForm" action="/player/index.php?term=pc" method="post">
	<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
	<input type="hidden" name="vid" id="hid_vid" value="<!--{$next_vid|escape}-->" />
	<input type="hidden" name="ftn" id="hid_ftn" value="<!--{$next_ftn|escape}-->" />
	<input type="hidden" name="ccno" id="hid_ccno" value="<!--{$next_ccno|escape}-->" />
	<input type="hidden" name="view_btn" id="hid_view_btn" value="start" />
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
		var pop_up_time = 1200;
		var pop_stop_time = 1260;
		//+++++++++++++++++++++++++++
		var time3 = <!--{$time3}-->;
		var pause_time = 0;
		var video_pop_ok = false;
		var video_pop_time = 0;
		var video_pop_time_before = 0;
		//+++++++++++++++++++++++++++
		var initialized_flg = false;

		function initialized() {
			<!--{* // ボリュームを設定(0～1) *}-->
			volume(0.5);
		
			<!--{* // 再生スピード間隔の設定 *}-->
			//setPlayRateInterval(0.2);
		
			<!--{* // 再生位置の設定 *}-->
			if (!initialized_flg) {
				initialized_flg = true;
				<!--{if $initialized_param!=""}-->
					setSeek(0, [<!--{$initialized_param|escape}-->]);
				<!--{/if}-->

				<!--{* // chromeの場合はエラーになるので自動再生させない *}-->
				<!--{if $ischrome}-->
				<!--{else}-->
					<!--{if $view_btn=='start'}-->
						//playAndSeek(0, 0);
						play();
					<!--{elseif $view_btn=='bookmark'}-->
						<!--{if $bookmark_time_sec!=""}-->
							playAndSeek(0, (<!--{$bookmark_time_sec|escape}-->/<!--{if $codec=='baisoku'}-->2<!--{elseif $codec=='x11'}-->1.1<!--{elseif $codec=='x12'}-->1.2<!--{elseif $codec=='x13'}-->1.3<!--{elseif $codec=='x14'}-->1.4<!--{elseif $codec=='x15'}-->1.5<!--{elseif $codec=='x16'}-->1.6<!--{elseif $codec=='x17'}-->1.7<!--{elseif $codec=='x18'}-->1.8<!--{elseif $codec=='x19'}-->1.9<!--{elseif $codec=='x20'}-->2<!--{elseif $codec=='aux11'}-->1.1<!--{elseif $codec=='aux12'}-->1.2<!--{elseif $codec=='aux13'}-->1.3<!--{elseif $codec=='aux14'}-->1.4<!--{elseif $codec=='aux15'}-->1.5<!--{elseif $codec=='aux16'}-->1.6<!--{elseif $codec=='aux17'}-->1.7<!--{elseif $codec=='aux18'}-->1.8<!--{elseif $codec=='aux19'}-->1.9<!--{elseif $codec=='aux20'}-->2<!--{else}-->1<!--{/if}-->));//bookmark_time_sec
						<!--{else}-->
							//playAndSeek(0, 0);//non_bookmark_time_sec
							play();
						<!--{/if}-->
					<!--{/if}-->
				<!--{/if}-->
			}
	}
	
	var seekCount = 2;
	var eventStaus = null;
	var eventTime = null;
	function initialized_on() {
		if (typeof(jwplayer) == "function") {
			var playerStatus = jwplayer().getState();
			var _p = jwplayer().getPosition();
			var _d = jwplayer().getDuration();

			// ready
			if(playerStatus=='idle' && _p==0 && _d==0){
				if (eventTime != null) { clearTimeout(eventTime); }
				if (!initialized_flg) {
					initialized(null);
				}

			// play
			}else if(playerStatus=='playing'){
				eventStaus = "Play";
				//console.log("[" + eventStaus + "]");
				event_all("timeupdate", 0, jwplayer().getPosition());

			// pause
			}else if(playerStatus=='paused'){
				eventStaus = "Pause";
				//console.log("[" + eventStaus + "]");

			// complete
			}else if(playerStatus=='complete'){
				if (eventTime != null) { clearTimeout(eventTime); }
				eventStaus = "Complete";
				//console.log("[" + eventStaus + "]");
				event_all("complete", 0, jwplayer().getPosition());
				return;
			}
		}

		setTimeout(function(){
			initialized_on();
		}, 500);
	}

	// 前のシーク再生時間
	<!--{if $view_btn=='bookmark' && $bookmark_time_sec!=""}-->
	var timeSeek = <!--{$bookmark_time_sec|escape}--> + 2.5;
	var timeBefore = 0;
	<!--{else}-->
	var timeSeek = 0;
	var timeBefore = 0;
	<!--{/if}-->
	var timeBeforeFlg = false;
	
	var resume = 0;
	var insert_report_user_video_viewed_flg = 1;
	function event_all(type, idx, time) {
		if (type == "timeupdate") {
			<!--{if $set_btn_flg}-->
			// シークが無効の場合、警告を出してシークする前に戻す
			if (eventStaus == "Play") {
				if (!timeBeforeFlg) {
					// 2.5秒以上進んでいたら前のシーク再生時間に戻る
					if (timeSeek == 0 && time - timeBefore > 2.5) {
						timeBeforeFlg = true;
						if (typeof(jwplayer) == "function") {

							<!--{if $video_id2==''}-->
								jwplayer().pause();
								setTimeout(function(){
									jwplayer().seek(timeBefore - 0.5);
									var timeBeforeTimer = setInterval(function() {
										var _time = jwplayer().getPosition();
										if (_time - timeBefore <= 2.5) {
											clearInterval(timeBeforeTimer);
											jwplayer().pause();
											setTimeout(function(){
												alert("初回の再生については先に進めません");
												timeBeforeFlg = false;
											}, 100);
										}
									}, 500);
								}, 100);
							<!--{/if}-->

						}
					}
					else {
						timeBefore = time;
					}

					if (timeSeek > 0) {
						if (time >= timeSeek) { timeSeek = 0; }
					}
				}
			}
			<!--{/if}-->
			if( video_pop_ok == true ){
				if (eventStaus == "Play") {
					if (!timeBeforeFlg) {
						// 2.5秒以上進んでいたら前のシーク再生時間に戻る
						if (timeSeek == 0 && time - timeBefore > 2.5) {
							timeBeforeFlg = true;
							if (typeof(jwplayer) == "function") {
								<!--{if $video_id2==''}-->
									jwplayer().pause();
									setTimeout(function(){
										jwplayer().seek(timeBefore - 0.5);
										var timeBeforeTimer = setInterval(function() {
											var _time = jwplayer().getPosition();
											if (_time - timeBefore <= 2.5) {
												clearInterval(timeBeforeTimer);
												jwplayer().pause();
												setTimeout(function(){
													alert("再生については先に進めません");
													timeBeforeFlg = false;
												}, 100);
											}
										}, 500);
									}, 100);
								<!--{/if}-->
							}
						}
						else {
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
		
		if(seekCount<2){
			seekCount+=1;
		}
		<!--{* // しおり記憶を2回に1回実行させる判断用 *}-->
		resume = resume + 1;
		var ans = resume % 2;
		if (ans==0){
			var resume_flg = false;
		} else {
			var resume_flg = true;
		}
		
		if (eventTime != null) { clearTimeout(eventTime); }
		
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
				if( video_pop_time <=100 || video_pop_time >=1100 ){
					//console.log("[timeupdate:video_pop_time="+video_pop_time+"]");
				}
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
						<!--{if $codec=='baisoku'}-->
							temp_time = temp_time * 2;
						<!--{elseif $codec=='x11'}-->
							temp_time = temp_time * 1.1;
						<!--{elseif $codec=='x12'}-->
							temp_time = temp_time * 1.2;
						<!--{elseif $codec=='x13'}-->
							temp_time = temp_time * 1.3;
						<!--{elseif $codec=='x14'}-->
							temp_time = temp_time * 1.4;
						<!--{elseif $codec=='x15'}-->
							temp_time = temp_time * 1.5;
						<!--{elseif $codec=='x16'}-->
							temp_time = temp_time * 1.6;
						<!--{elseif $codec=='x17'}-->
							temp_time = temp_time * 1.7;
						<!--{elseif $codec=='x18'}-->
							temp_time = temp_time * 1.8;
						<!--{elseif $codec=='x19'}-->
							temp_time = temp_time * 1.9;
						<!--{elseif $codec=='x20'}-->
							temp_time = temp_time * 2;
						<!--{elseif $codec=='aux11'}-->
							temp_time = temp_time * 1.1;
						<!--{elseif $codec=='aux12'}-->
							temp_time = temp_time * 1.2;
						<!--{elseif $codec=='aux13'}-->
							temp_time = temp_time * 1.3;
						<!--{elseif $codec=='aux14'}-->
							temp_time = temp_time * 1.4;
						<!--{elseif $codec=='aux15'}-->
							temp_time = temp_time * 1.5;
						<!--{elseif $codec=='aux16'}-->
							temp_time = temp_time * 1.6;
						<!--{elseif $codec=='aux17'}-->
							temp_time = temp_time * 1.7;
						<!--{elseif $codec=='aux18'}-->
							temp_time = temp_time * 1.8;
						<!--{elseif $codec=='aux19'}-->
							temp_time = temp_time * 1.9;
						<!--{elseif $codec=='aux20'}-->
							temp_time = temp_time * 2;
						<!--{/if}-->
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
							if (eventStaus == "Play") {
								seekCount=-10;
								jwplayer().stop();
								setTimeout("buyConfirm()", 1000);
							}
							
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
		
		if (typeof(jwplayer) == "function") {
			if (eventStaus == "Play") {
			        eventTime = setTimeout(function(){
			            event_all(type, idx, jwplayer().getPosition());
			        }, 500);
			}
		}
	}

	function play() {
	    if (typeof(jwplayer) == "function") {
	        jwplayer().play();
	    }
	    else {
	        setTimeout(function(){
	            play();
	        }, 100);
	    }
	}

	function playRate(rate) {
	    if (typeof(jwplayer) == "function") {
	        jwplayer().setPlayRate(rate);
	    }
	    else{
	        setTimeout(function(){
	            playRate(rate);
	        }, 100);
	    }
	}

	function playAndSeek(idx, time) {
		if(seekCount==2){
		    seekCount=0;
		    if (typeof(jwplayer) == "function") {
				var time2 = (Math.floor(time / 5) + 1) * 5;
		        jwplayer().seek(time2);

		        var po = jwplayer().getPosition();
		        if (eventStaus != "Play" && po == 0) { jwplayer().play(); }

		        setTimeout(function() {
		           playAndSeekCheck(idx, time);
		        }, 1000);
		    }
		    else{
		        setTimeout(function(){
		            playAndSeek(idx, time);
		        }, 1000);
		    }
		}

	}

	function playAndSeekCheck(idx, time) {
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
	           playAndSeekCheck(idx, time);
	        }, 1000);
	    }
	}

	function setSeek(idx, seeks) {
	    if (typeof(jwplayer) == "function") {
	        jwplayer().setSeek(idx, seeks);
	    }
	    else{
	        setTimeout(function(){
	            setSeek(idx, seeks);
	        }, 100);
	    }
	}

	function pause() {
		video_pop_time = 0;
		if (typeof(jwplayer) == "function") {
	        jwplayer().pause();
	    }
	    else{
	        setTimeout(function(){
	            pause();
	        }, 100);
	    }
	}

	function volume(val) {
	    if (typeof(jwplayer) == "function") {
	        jwplayer().setVolume(val * 100);
	    }
	    else {
	        setTimeout(function(){
	            volume();
	        }, 100);
	    }
	}

	function setPlayRateInterval(val) {
		if (typeof(jwplayer) == "function") {
			jwplayer().setPlayRateInterval(val);
		}
		else{
			setTimeout(function(){
				setPlayRateInterval(val);
			}, 100);
		}
	}
	
	function playAndPause(){
		if (typeof(jwplayer) == "function") {
			s=jwplayer().getState();
			if(s=="playing"){
				jwplayer().pause();
				$('.basePlayElem').css('display','block');
			}else{
				jwplayer().play();
				$('.basePlayElem').css('display','none');
			}
		} else {
			setTimeout(function(){
				playAndPause();
			}, 100);
		}
	}
	
	
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
			if (eventStaus == "Play") {
				if (typeof(jwplayer) == "function") {
					jwplayer().pause();
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
		//playAndSeek(0, pause_time);
		if (eventStaus == "Pause") {
			if (typeof(jwplayer) == "function") {
				jwplayer().play();
			}
			else{
			}
		}
		video_pop_ok = false;
	}

	// ビデオ制御
	$(function(){
		$('.basePrevElem').click(function(){
			if (typeof(jwplayer) == "function") {
				jwplayer().stop();
				jwplayer().seek(0);
				jwplayer().play();
			}
		});
		
		$('.basePauseElem').click(function(){
			playAndPause();
		});
		$('.baseStopElem').click(function(){
			if (typeof(jwplayer) == "function") {
				jwplayer().stop();
			}
		});
		$('.basePlayElem').click(function(){
			playAndPause();
		});
		$('.baseNextElem').click(function(){});
		$('.base10backElem').click(function(){
			if (typeof(jwplayer) == "function") {
				t=jwplayer().getPosition() - 10;
				if(t>0){
					jwplayer().seek(t);
				} else {
					jwplayer().seek(0);
				}
			}
		});
		$('.base30backElem').click(function(){
			if (typeof(jwplayer) == "function") {
				t=jwplayer().getPosition() - 30;
				if(t>0){
					jwplayer().seek(t);
				} else {
					jwplayer().seek(0);
				}
			}
		});
		$('.baseFullElem').click(function(){
			fullScreen();
		});
		
		initialized_on();
		function _convertSec(a){
			t=parseInt(""+a);
			h=""+(t/36000|0)+(t/3600%10|0);
			m=""+(t%3600/600|0)+(t%3600/60%10|0);
			s=""+(t%60/10|0)+(t%60%10);
			return h+":"+m+":"+s;
		}

		function fullScreen() {
			var videoObj = document.getElementsByTagName('video');
			if (!document.fullscreenElement) {
				videoObj[0].requestFullscreen();
			} else {
				if (document.exitFullscreen) {
					videoObj[0].exitFullscreen();
				}
			}
		}

		setInterval(function(){
			if (typeof(jwplayer) == "function") {
				// 再生時間表示制御
				var _p = jwplayer().getPosition();
				var _d = jwplayer().getDuration();
				var _pos = _convertSec(_p);
				var _dur = _convertSec(_d);
				
				$(".baseCurrentTimeElem").html(_pos);
				$(".baseDurationTimeElem").html("/ "+_dur);
				
				var _r = Math.ceil((_p/_d) * 100);
				
				// 再生ボタン制御
				var state=jwplayer().getState();
				if(state == "playing"){
					$('.basePlayElem').css('display','none');
				} else {
					$('.basePlayElem').css('display','block');
				}
				
				// シークバー制御
				if(seekobj == false){
					document.getElementById("seekrate").value = _r;
				}

			}
		},100);
	});
	
	var seekobj = false;
	
	function rateChange(){
		var r = document.getElementById("playbackrate").value;
		var rate = 1 + (r / 100);
		if (typeof(jwplayer) == "function") {
			jwplayer().setPlaybackRate(rate);
		}
	}
	
	function volumeChange() {
		var r = document.getElementById("volumerate").value;
		if (typeof(jwplayer) == "function"){
			jwplayer().setVolume(r);
		}
	}
	
	function seekChange() {
		seekobj = false;
		var r = document.getElementById("seekrate").value;
		if (typeof(jwplayer) == "function"){
			var _dur = jwplayer().getDuration();
			var _pos = Math.ceil(_dur * (r / 100));
			jwplayer().seek(_pos);
		}
	}
	function seekInput() {
		seekobj = true;
	}
	</script>
	
	<!-- <!--{$get_alf_player_msg1}--> -->
	<!-- <!--{$get_alf_player_msg2}--> -->
	
	<!--{$player_msg}-->
	
	<div style="text-align:left;font-weight:bold;"><!--{$video_logic_name|escape}--></div>

	<center>
		<div id="alfplayer_base" class="alfplayer_base">
			<!--{$player}-->
			<div id="alfplayer_base_controls" width="660" height="86" style="position:relative; top:10px; width:660px; height:86px;">
			<div class="basePrevElem"></div>
			<div class="basePauseElem"></div>
			<div class="baseStopElem"></div>
			<div class="basePlayElem"></div>
			<div class="baseNextElem"></div>
			<div class="base10backElem"></div>
			<div class="base30backElem"></div>
			<div class="baseFullElem"></div>
			<div class="baseSpeedbarElem">
				<input style="background:none;" type="range" id="playbackrate" name="playbackrate" min="0" max="100" onchange="rateChange()" value="0">
			</div>
			<div class="baseSpeedbarElemDis"></div>
			<div class="baseDurationTimeElem"> / 00:00:00</div>
			<div class="baseCurrentTimeElem">00:00:00</div>
			<div class="baseVolumeElem"></div>
			<div class="baseVolumeBarElem">
				<input type="range" id="volumerate" name="volumerate" min="0" max="100" onchange="volumeChange()" value="50">
			</div>
				
			<div class="baseSeekBarElem">
				<input type="range" id="seekrate" name="seekrate" min="0" max="100" onchange="seekChange()" oninput="seekInput()" value="0">
			</div>
		</div>
	</center>
	
	<!--{if !empty($arr_chapter_list)}-->

	<ul id="player_list">
	<!--{foreach name=chapter_list from=$arr_chapter_list item=val}-->
		<li><!--{* 0はチャプターリストの番号 *}-->
			<a href="javascript:void(0)" onClick="playAndSeek(0, <!--{$val.chapter_time_sec|escape}-->);return false;" id="seek0_<!--{$val.chapter_time_sec|escape}-->_2"><span id="seek0_<!--{$val.chapter_time_sec|escape}-->" class="idxall idx0"></span><span class="time"><!--{$val.chapter_time|escape}--></span>&nbsp;<span class="chapter_name"><!--{$val.chapter_name|escape}--></span></a>
			
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
<style type="text/css">
	.jwplayer {
		
	}
	#alfplayer_base {
		background-image: url(//res.alfstream.com/akplayer/skin/images/bg_player.png);
		background-position: center;
		background-repeat:no-repeat;
	}

	#alfplayer_base_controls .basePrevElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 167px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_prev_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .basePrevElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_prev_act.png);
	}
	#alfplayer_base_controls .basePauseElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 257px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_pause_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .basePauseElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_pause_act.png);
	}
	#alfplayer_base_controls .baseStopElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 212px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_stop_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .baseStopElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_stop_act.png);
	}
	#alfplayer_base_controls .basePlayElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 257px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_play_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .basePlayElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_play_act.png);
	}
	#alfplayer_base_controls .baseNextElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 302px;
	    /* background-image: url(//res.alfstream.com/akplayer/skin/images/btn_next_def.png); */
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_next_dis.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	/*
	#alfplayer_base_controls .baseNextElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_next_act.png);
	}
	*/
	#alfplayer_base_controls .base10backElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 347px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_10back_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .base10backElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_10back_act.png);
	}
	#alfplayer_base_controls .base30backElem {
	    position: absolute;
	    width: 45px;
	    height: 55px;
	    top: 0px;
	    left: 392px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_30back_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .base30backElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_30back_act.png);
	}
	#alfplayer_base_controls .baseFullElem {
	    position: absolute;
	    width: 58px;
	    height: 55px;
	    top: 0px;
	    left: 591px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_fullscreen_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .baseFullElem:hover {
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_fullscreen_act.png);
	}

	/* playratebar Start */

	#alfplayer_base_controls .baseSpeedbarElem {
	    position: absolute;
	    width: 154px;
	    height: 60px;
	    top: 3px;
	    left: 437px;
		background-position: 0px 0px;
	}

	.baseSpeedbarElem input[type="range"] {
		-webkit-appearance: none;
		width: 80%;
		height: 100%;
		background: transparent;
		margin:0;
		padding:0;
	}
	.baseSpeedbarElem input[type="range"]:focus {
		outline: none;
	}
	.baseSpeedbarElem input[type="range"]::-webkit-slider-thumb {
		-webkit-appearance: none;
		height: 12px;
		width: 12px;
		border-radius: 50%;
		background: #ffa200;
		margin-top: -5px;
		box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
		cursor: pointer;
	}
	.baseSpeedbarElem input[type="range"]::-webkit-slider-runnable-track {
		width: 60%;
		height: 9px;
		background: transparent;
		border-radius: 3em;
		-webkit-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseSpeedbarElem input[type="range"]:hover::-webkit-slider-runnable-track {
		background: none;
	}
	.baseSpeedbarElem input[type="range"]::-ms-track {
		width: 60%;
		cursor: pointer;
		height: 9px;
		-ms-transition: all 0.5s;
		transition: all 0.5s;
		/* Hides the slider so custom styles can be added */
		background: transparent;
		border-color: transparent;
		color: transparent;
	}
	.baseSpeedbarElem input[type="range"]::-ms-thumb {
		height: 12px;
		width: 12px;
		border-radius: 50%;
		background: #ffa200;
		margin-top: -5px;
		box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
		cursor: pointer;
	}
	.baseSpeedbarElem input[type="range"]::-ms-fill-lower {
		background: transparent;
		border-radius: 3rem;
	}
	.baseSpeedbarElem input[type="range"]:focus::-ms-fill-lower {
		background: none;
	}
	.baseSpeedbarElem input[type="range"]::-ms-fill-upper {
		background: transparent;
		border-radius: 3rem;
	}
	.baseSpeedbarElem input[type="range"]:focus::-ms-fill-upper {
		background: none;
	}
	.baseSpeedbarElem input[type="range"]::-moz-range-thumb {
		height: 12px;
		width: 12px;
		border-radius: 50%;
		background: #ffa200;
		margin-top: -5px;
		box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
		cursor: pointer;
	}
	.baseSpeedbarElem input[type="range"]::-moz-range-track {
		width: 60%;
		height: 9px;
		background: transparent;
		border-radius: 3rem;
		-moz-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseSpeedbarElem input[type="range"]:hover::-moz-range-track {
		background: none;
	}
	#alfplayer_base_controls .baseSpeedbarElemDis {
	    position: absolute;
	    width: 154px;
	    height: 60px;
	    top: 0px;
	    left: 437px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/speed_invariable.png);
	    visibility: hidden;
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}

	/* playratebar END */


	#alfplayer_base_controls .baseDurationTimeElem {
	    position: absolute;
	    width: 75px;
	    height: 16px;
	    top: 10px;
	    left: 89px;
	    font-size: 10px;
	    color: rgb(136, 136, 136);
	}
	#alfplayer_base_controls .baseCurrentTimeElem {
	    position: absolute;
	    width: 75px;
	    height: 16px;
	    top: 10px;
	    left: 40px;
	    font-size: 10px;
	    color: rgb(85, 85, 85);
	}

	/* volumebar Start*/
	#alfplayer_base_controls .baseVolumeElem {
	    position: absolute;
	    width: 18px;
	    height: 18px;
	    top: 30px;
	    left: 18px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_volume_on_def.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	#alfplayer_base_controls .baseVolumeBarElem {
	    position: absolute;
	    width: 150px;
	    height: 5px;
	    top: 27px;
	    left: 24px;
	    background-position: 0px 0px;
	}

	.baseVolumeBarElem input[type="range"] {
		-webkit-appearance: none;
		width: 80%;
		height: 9px;
		background: #aaa;
		position: relative;
		margin:0;
		padding:0;
	}
	.baseVolumeBarElem input[type="range"]::-webkit-slider-thumb {
		-webkit-appearance: none;
		height: 20px;
		width: 6px;
		background: #858585;
		margin-top: -5px;
		border-radius: 0 !important;
		box-shadow: -100vm 0 0 100vw #ffa200;
		box-sizing: border-box;
	}
	.baseVolumeBarElem input[type="range"]::-webkit-slider-runnable-track {
		width: 60%;
		height: 9px;
		background: #aaa;
		border-radius: 3em;
		-webkit-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseVolumeBarElem input[type="range"]:hover::-webkit-slider-runnable-track {
		background: none;
	}
	.baseVolumeBarElem input[type="range"]::-ms-track {
		width: 60%;
		cursor: pointer;
		height: 11px;
		-ms-transition: all 0.5s;
		transition: all 0.5s;
		/* Hides the slider so custom styles can be added */
		background: #aaa;
		border-color: #aaa;
		color: #aaa;
	}
	.baseVolumeBarElem input[type="range"]::-ms-thumb {
		height: 20px;
		width: 8px;
		background: #858585;
		margin-top: -5px;
		box-shadow: -100vw 0 0 100vw #ffa200;
		cursor: pointer;
	}
	.baseVolumeBarElem input[type="range"]::-ms-fill-lower {
		background: #ffa200;
		border-radius: 3rem;
	}
	.baseVolumeBarElem input[type="range"]:focus::-ms-fill-lower {
		background: none;
	}
	.baseVolumeBarElem input[type="range"]::-ms-fill-upper {
		background: #aaa;
		border-radius: 3rem;
	}
	.baseVolumeBarElem input[type="range"]:focus::-ms-fill-upper {
		background: none;
	}
	.baseVolumeBarElem input[type="range"]::-moz-range-thumb {
		height: 20px;
		width: 6px;
		background: #858585;
		margin-top: -5px;
		cursor: pointer;
	}
	.baseVolumeBarElem input[type="range"]::-moz-range-track {
		width: 60%;
		height: 9px;
		background: none;
		border-radius: 3rem;
		-moz-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseVolumeBarElem input[type="range"]:hover::-moz-range-track {
		background: none;
	}
	#alfplayer_base_controls .baseVolumeHandleElem {
	    position: absolute;
	    width: 6px;
	    height: 20px;
	    top: 32px;
	    left: 40px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_volume_handle.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}
	/* volumebar End */

	/* seekbar Start */
	#alfplayer_base_controls .baseSeekBarElem {
	    position: absolute;
	    width: 640px;
	    height: 7px;
	    top: 68px;
	    left: 10px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/seek_bar.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}

	.baseSeekBarElem input[type="range"] {
		-webkit-appearance: none;
		width: 99%;
		height: 7px;
		background: none;
		position: relative;
		bottom: 10px;
		margin-top: 3px;
		margin:0;
		padding:0;
	}
	.baseSeekBarElem input[type="range"]::-webkit-slider-thumb {
		-webkit-appearance: none;
		height: 10px;
		width: 10px;
		background: #ffa200;
		margin-top: -5px;
		border-radius: 0 !important;
		box-shadow: -100vm 0 0 100vw #ffa200;
		box-sizing: border-box;
	}
	.baseSeekBarElem input[type="range"]::-webkit-slider-runnable-track {
		width: 94%;
		height: 3px;
		background: #aaa;
		border-radius: 3em;
		-webkit-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseSeekBarElem input[type="range"]:hover::-webkit-slider-runnable-track {
		background: none;
	}
	.baseSeekBarElem input[type="range"]::-ms-track {
		cursor: pointer;
		width: 99%;
		height: 11px;
		-ms-transition: all 0.5s;
		transition: all 0.5s;
		/* Hides the slider so custom styles can be added */
		background: #aaa;
		border-color: #aaa;
		color: #aaa;
	}
	.baseSeekBarElem input[type="range"]::-ms-thumb {
		height: 10px;
		width: 10px;
		background: #ffa200;
		margin-top: -5px;
		box-shadow: -100vw 0 0 100vw #ffa200;
		cursor: pointer;
	}
	.baseSeekBarElem input[type="range"]::-ms-fill-lower {
		background: #ffa200;
		border-radius: 3rem;
	}
	.baseSeekBarElem input[type="range"]:focus::-ms-fill-lower {
		background: none;
	}
	.baseSeekBarElem input[type="range"]::-ms-fill-upper {
		background: #aaa;
		border-radius: 3rem;
	}
	.baseSeekBarElem input[type="range"]:focus::-ms-fill-upper {
		background: none;
	}
	.baseSeekBarElem input[type="range"]::-moz-range-thumb {
		height: 10px;
		width: 10px;
		background: #ffa200;
		margin-top: -5px;
		cursor: pointer;
	}
	.baseSeekBarElem input[type="range"]::-moz-range-track {
		width: 99%;
		height: 9px;
		background: none;
		border-radius: 3rem;
		-moz-transition: all 0.5s;
		transition: all 0.5s;
		cursor: pointer;
	}
	.baseSeekBarElem input[type="range"]:hover::-moz-range-track {
		background: none;
	}

	/* seekbar End */

	#alfplayer_base_controls .baseSeekHandleElem {
	    position: absolute;
	    width: 16px;
	    height: 16px;
	    top: 65px;
	    left: 2px;
	    background-image: url(//res.alfstream.com/akplayer/skin/images/btn_seek_handle.png);
	    background-repeat: no-repeat;
	    background-position: 0px 0px;
	}

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
			//if (!initialized_flg) {
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
