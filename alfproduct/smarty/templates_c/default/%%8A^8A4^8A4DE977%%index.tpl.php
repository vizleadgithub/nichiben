<?php /* Smarty version 2.6.31, created on 2025-05-27 14:10:26
         compiled from /srv/alfproduct/smarty/templates/default/player/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/player/index.tpl', 4, false),)), $this); ?>
<!--[]-->
<?php if ($this->_tpl_vars['next_contents_flg']): ?>
<form name="playerForm" action="/player/index.php?term=<?php echo $this->_tpl_vars['isSP']; ?>
" method="post">
	<input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="hidden" name="vid" id="hid_vid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['next_vid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="hidden" name="vid2" id="hid_vid2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['next_vid2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="hidden" name="ftn" id="hid_ftn" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['next_ftn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="hidden" name="ccno" id="hid_ccno" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['next_ccno'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="hidden" name="view_btn" id="hid_view_btn" value="start" />
	<input type="hidden" name="codec" id="hid_codec" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['codec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
<?php endif; ?>

<style type="text/css">
html {
	overflow:hidden;
}
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
filter: alpha(opacity=70);		/* ie lt 8 */
-ms-filter: "alpha(opacity=70)";  /* ie 8 */
-moz-opacity:0.7;				 /* FF lt 1.5, Netscape */
-khtml-opacity: 0.7;			  /* Safari 1.x */
}

</style>

<?php if ($this->_tpl_vars['view_flg']): ?>
	<script type="text/javascript">
		if( _isSp()!="sp" ){ //SP
			if (window.opener) {
			} else {
				location.href = "/?_=1";
			}
		}

				//+++++++++++++++++++++++++++
		var video_codec_type ="<?php echo $this->_tpl_vars['codec']; ?>
";
		//+++++++++++++++++++++++++++
		var pop_up_time = 1200;
		var pop_stop_time = 1260;
		//+++++++++++++++++++++++++++
		var time3 = <?php echo $this->_tpl_vars['time3']; ?>
;
		var pause_time = 0;
		var video_pop_ok = false;
		var video_pop_time = 0;
		var video_pop_time_before = 0;
		//+++++++++++++++++++++++++++
		var ak_initialized_flg = false;

		function ak_initialized() {
						<?php if ($this->_tpl_vars['set_btn_flg']): ?>
				<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
					ak_setButton({
						'Next' : 'visible',
						'5skip': 'visible',
						'Seekbar': 'visible'
					});
				<?php else: ?>
					ak_setButton({
						'Next' : 'disable',
						'5skip': 'disable',
						'Seekbar': 'disable'
					});
				<?php endif; ?>
			<?php else: ?>
				<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
					ak_setButton({
						'Next' : 'visible',
						'5skip': 'visible',
						'Seekbar': 'visible'
					});
				<?php else: ?>
					ak_setButton({
						'Seekbar': 'visible'
					});
				<?php endif; ?>

				<?php if ($this->_tpl_vars['next_contents_flg']): ?>
					// 次に進むリンクの表示設定
					ak_hasNextPlaylistLink('Post:playerForm');
				<?php endif; ?>
			<?php endif; ?>
		
						ak_volume(0.5);
		
						ak_setPlayRateInterval(0.2);
		
						if (!ak_initialized_flg) {
				ak_initialized_flg = true;
				<?php if ($this->_tpl_vars['ak_initialized_param'] != ""): ?>
					ak_setSeek(0, [<?php echo ((is_array($_tmp=$this->_tpl_vars['ak_initialized_param'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]);
				<?php endif; ?>

																	<?php if ($this->_tpl_vars['view_btn'] == 'start'): ?>
						ak_playAndSeek(0, 0);
					<?php elseif ($this->_tpl_vars['view_btn'] == 'bookmark'): ?>
						<?php if ($this->_tpl_vars['bookmark_time_sec'] != ""): ?>
							ak_playAndSeek(0, <?php echo ((is_array($_tmp=$this->_tpl_vars['bookmark_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
);
						<?php else: ?>
							ak_playAndSeek(0, 0);
						<?php endif; ?>
					<?php endif; ?>
							}
		}

		var ak_seekCount = 2;
		var ak_eventStaus = null;
		var ak_eventTime = null;
		function ak_initialized_on() {
			if (typeof(jwplayer) == "function") {
				//jwplayer().onReady(function(){
				//jwplayer().on("ready", function(){//jw8
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					if (!ak_initialized_flg) {
						ak_initialized(null);
					}
				//});
				//jwplayer().onPlay(function(){
				jwplayer().on("play", function(){//jw8
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
				//jwplayer().onPause(function(){
				jwplayer().on("pause", function(){//jw8
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Pause";
				});
				//jwplayer().onIdle(function(){
				jwplayer().on("idle", function(){//jw8
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Idle";
				});
				//jwplayer().onComplete(function(){
				jwplayer().on("complete", function(){//jw8
					if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
					ak_eventStaus = "Complete";
					ak_event_all("complete", 0, jwplayer().getPosition());
				});
				//jwplayer().onSeek(function(evt){
				jwplayer().on("seek", function(evt){//jw8
					// シークが無効の場合、警告を出してシークする前に戻す（音声の時は許す）
					<?php if ($this->_tpl_vars['set_btn_flg'] && $this->_tpl_vars['video_id2'] == ''): ?>
						//if (timeSeek == 0) {
							if (evt.position < evt.offset && evt.position>0) {
								if( timeBeforeFlg == false ){
									timeBeforeFlg = true;
									console.log("[" + evt.position + ":" +evt.offset + "]");
									jwplayer().seek(evt.position);
									evt.offset = evt.position;
									jwplayer().pause();
									alert("初回の再生については先に進めません");
									timeBeforeFlg = false;
								}
							}
						//}
					<?php endif; ?>
				});
			} else {
				setTimeout(function(){
					ak_initialized_on();
				}, 100);
			}
		}

		// 前のシーク再生時間
		<?php if ($this->_tpl_vars['view_btn'] == 'bookmark' && $this->_tpl_vars['bookmark_time_sec'] != ""): ?>
		var timeSeek = <?php echo ((is_array($_tmp=$this->_tpl_vars['bookmark_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
;
		var timeBefore = 0;
		<?php else: ?>
		var timeSeek = 0;
		var timeBefore = 0;
		<?php endif; ?>
		var timeBeforeFlg = false;
		
		var resume = 0;
		var insert_report_user_video_viewed_flg = 1;
		function ak_event_all(type, idx, time) {
			//console.log("[ak_event_all:"+type+"]");
						if (insert_report_user_video_viewed_flg == 1){
				insert_report_user_video_viewed_flg = 0;
				<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != '' && $this->_tpl_vars['video_id2'] == ''): ?>
					var url = "insert_report_user_video_viewed.php";
					var data = {};
					data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
					data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
					
					$.ajax({
						type		: "GET",
						cache		: false,
						url			: url,
						data		: data,
						success		: function(res){
													},
						error		: function(XMLHttpRequest, textStatus, errorThrown) {
													}
					});
				<?php endif; ?>
			}
			
			if(ak_seekCount<2){
				ak_seekCount+=1;
			}
						resume = resume + 1;
			var ans = resume % 2;
			if (ans==0){
				var resume_flg = false;
			} else {
				var resume_flg = true;
			}
			
			if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
			
						if (type == "timeupdate") {
				//++++++++++++++++++++++++++++++++++++++
				if (time - video_pop_time_before > 2.5) {
					//シークしたらポップアップのタイマーを0にする
					video_pop_time = 0;
				} else {
					if(time - video_pop_time_before > 0){
						video_pop_time += time - video_pop_time_before;
					} else {
						//巻き戻したらしたらポップアップのタイマーを0にする
						video_pop_time = 0;
					}
				}
				video_pop_time_before = time;//前回の再生時間を取っとく
				if( _isSp()=="sp" ){ //SP
				} else {
					//console.log("[timeSeek:" + timeSeek + "][time:" + time + "][timeBefore:" + timeBefore + "]AA");
					//console.log("[timeupdate:video_pop_time="+video_pop_time+"]");
				}
				//++++++++++++++++++++++++++++++++++++++
				if (time >= 5){
					<?php if ($this->_tpl_vars['video_popup'] == 1): ?>
						//ポップアップが有効な動画の場合
						//+++++++++++++++++++++++++++
						if( video_pop_time >=pop_up_time  && !video_pop_ok ){
							//ポップアップ表示時間超過及びポップアップ未表示
							pause_time = time-1;
							video_pop_ok = true;
							video_check_pop();
						}
						if( video_pop_time >=pop_stop_time  && video_pop_ok ){
							//ポップアップ停止時間超過及びポップアップ表示
							video_check_stop();
						}
						//+++++++++++++++++++++++++++
					<?php endif; ?>
					if (resume_flg){
												<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != ''): ?>
							var temp_time = time;
							var url = "bookmark.php";
							var data = {};
							data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
							data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
							<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
								data["movie_id"] = "<?php echo $this->_tpl_vars['video_id2']; ?>
";
							<?php endif; ?>
							data["time"] = temp_time;
							data["time2"] = "<?php echo $this->_tpl_vars['time2']; ?>
";
							data["time3"] = "<?php echo $this->_tpl_vars['time3']; ?>
";
							data["pid"] = "<?php echo $this->_tpl_vars['pid']; ?>
";
							
							$.ajax({
								type		: "GET",
								cache		: false,
								url			: url,
								data		: data,
								success		: function(res){
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
																	}
							});
						<?php endif; ?>
					}
				}
				
								<?php if ($this->_tpl_vars['contents_free_time'] != '0' && ! $this->_tpl_vars['buy_flg']): ?>
					if (idx == 0 && time >= <?php echo ((is_array($_tmp=$this->_tpl_vars['contents_free_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
) {
						
												<?php if (! $this->_tpl_vars['free_product_flg']): ?>
							if (typeof(jwplayer) == "function") {
																<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != ''): ?>
									var url = "bookmark_delete.php";
									var data = {};
									data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
									data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
									data["pid"] = "<?php echo $this->_tpl_vars['pid']; ?>
";
									<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
										data["movie_id"] = "<?php echo $this->_tpl_vars['video_id2']; ?>
";
									<?php endif; ?>
									
									$.ajax({
										type		: "GET",
										cache		: false,
										url			: url,
										data		: data,
										success		: function(res){
																					},
										error		: function(XMLHttpRequest, textStatus, errorThrown) {
																					}
									});
								<?php endif; ?>
								if (ak_eventStaus == "Play") {
									ak_seekCount=-10;
									jwplayer().stop();
									setTimeout("buyConfirm()", 1000);
								}
								
							}
							else if (typeof(alfplayer) == "function") {
																<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != ''): ?>
									var url = "bookmark_delete.php";
									var data = {};
									data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
									data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
									data["pid"] = "<?php echo $this->_tpl_vars['pid']; ?>
";
									<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
										data["movie_id"] = "<?php echo $this->_tpl_vars['video_id2']; ?>
";
									<?php endif; ?>
									
									$.ajax({
										type		: "GET",
										cache		: false,
										url			: url,
										data		: data,
										success		: function(res){
																					},
										error		: function(XMLHttpRequest, textStatus, errorThrown) {
																					}
									});
								<?php endif; ?>
								ak_seekCount=-10;
								alfplayer().stop();
								setTimeout("buyConfirm()", 1000);
							}
						<?php endif; ?>
						
												<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != ''): ?>
							var url = "bookmark_delete.php";
							var data = {};
							data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
							data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
							<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
								data["movie_id"] = "<?php echo $this->_tpl_vars['video_id2']; ?>
";
							<?php endif; ?>
							
							$.ajax({
								type		: "GET",
								cache		: false,
								url			: url,
								data		: data,
								success		: function(res){
																	},
								error		: function(XMLHttpRequest, textStatus, errorThrown) {
																	}
							});
						<?php endif; ?>
						
					}
				<?php endif; ?>
				
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
			
						else if (type == "complete"){
								<?php if ($this->_tpl_vars['user_id'] != '' && $this->_tpl_vars['video_id'] != ''): ?>
					var url = "bookmark_delete.php";
					var data = {};
					data["student_id"] = "<?php echo $this->_tpl_vars['user_id']; ?>
";
					data["movie_id"] = "<?php echo $this->_tpl_vars['video_id']; ?>
";
					<?php if ($this->_tpl_vars['video_id2'] != ''): ?>
						data["movie_id"] = "<?php echo $this->_tpl_vars['video_id2']; ?>
";
					<?php endif; ?>
					
					$.ajax({
						type		: "GET",
						cache		: false,
						url			: url,
						data		: data,
						success		: function(res){
													},
						error		: function(XMLHttpRequest, textStatus, errorThrown) {
													}
					});
				<?php endif; ?>
				
								<?php if ($this->_tpl_vars['next_contents_flg']): ?>
					document.playerForm.submit();
				<?php endif; ?>
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
				if(time>0){
					var time2 = (Math.floor(time / 5) + 1) * 5;
						jwplayer().seek(time2);
				} else {
					jwplayer().seek(0);
				}

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
		
	
				function buy_exe(){
			window.opener.document.buyForm.submit();
			window.close();
		}
		function buyConfirm(){
			alert("続きは有料になります。");
			<?php if ($this->_tpl_vars['buy_wait_flg']): ?>
				window.close();
			<?php else: ?>
				if (confirm("購入しますか？")){
					buy_exe();
				} else {
					window.close();
				}
			<?php endif; ?>
		}

		function _isSp(){
			var ua = navigator.userAgent.toLowerCase();
			if (
				ua.indexOf('iphone') > -1
				 || (ua.indexOf('android') > -1 && ua.indexOf('mobile') > -1)
			) {
				// スマートフォン
				return "sp";
			} else if (
				ua.indexOf('ipad') > -1
				 || ua.indexOf('android') > -1
			) {
				// タブレット
				return "sp";
			} else {
				// PC
				return "";
			}
		}

		function video_check_pop(){
			console.log("[video_check_pop]");
			if( _isSp()=="sp" ){ //SP
			} else {
				//let KEvent = new KeyboardEvent( "keydown", { keyCode: 27 });
				//document.dispatchEvent( KEvent );
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
						//focus();
					}
				}
				//++++++++++++++++++++++++++++++++++++++++++
			}
			$('.pop_alert').show();
		}

		function video_check_stop(){
			console.log("[video_check_stop]");
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
	
	<!-- <?php echo $this->_tpl_vars['get_alf_player_msg1']; ?>
 -->
	<!-- <?php echo $this->_tpl_vars['get_alf_player_msg2']; ?>
 -->
	
	<?php echo $this->_tpl_vars['player_msg']; ?>

	
	<div style="text-align:left;font-weight:bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['video_logic_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>

	<?php echo $this->_tpl_vars['player']; ?>

	
	<?php if (! empty ( $this->_tpl_vars['arr_chapter_list'] )): ?>

	<ul id="player_list">
	<?php $_from = $this->_tpl_vars['arr_chapter_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['chapter_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['chapter_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['chapter_list']['iteration']++;
?>
		<li>			<a href="javascript:void(0)" onClick="ak_playAndSeek(0, <?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
);return false;" id="seek0_<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
_2"><span id="seek0_<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="idxall idx0"></span><span class="time"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>&nbsp;<span class="chapter_name"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></a>
			
		</li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
	<?php endif; ?>
	
<?php else: ?>
	エラーが発生しました。[999]<br />
<?php endif; ?>

<div style="text-align:center;padding-top:10px;" class="btn">
	<?php if ($this->_tpl_vars['isSP'] == 'pc'): ?>
		<input type="image" src="/img/btn/close_b.png" alt="閉じる" onclick="reload_parent_window()" />
		<!--<input type="image" src="/img/btn/close_b.png" alt="閉じる" onclick="window.opener.location.reload();window.close();" />-->
	<?php else: ?>
		<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">戻る</a>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['buy_wait_flg']): ?>
	<?php else: ?>
		<?php if (! $this->_tpl_vars['buy_flg']): ?>
			<input type="image" src="/img/btn/buy.png" alt="購入する" onclick="buy_exe();" />
		<?php endif; ?>
	<?php endif; ?>
</div>


<?php if ($this->_tpl_vars['isSP'] == 'pc'): ?>
	<div style="padding-top:10px;">
		<span style="color:red;">※動画の視聴完了後，「閉じる」ボタンを押してください<br />&nbsp;&nbsp;（プレーヤーを閉じないと視聴履歴が確定しません）</span>
		<br />
		※全画面表示を解除する場合は，キーボードの「ESC」ボタンを押します
	</div>
<?php endif; ?>


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

	<?php if ($this->_tpl_vars['isSP'] == 'pc' && $this->_tpl_vars['video_id2'] != ''): ?>
		/*
		.alfplayer_base{
			top: -370px;
			position: absolute;
		}
		*/
	<?php endif; ?>

	<?php if ($this->_tpl_vars['set_btn_flg']): ?>
		/*
		.baseSpeedbarElem {
			pointer-events:none;
		}
		*/
		.baseSeekAreaElem {
			pointer-events:none;
		}
		.baseSeekBarElem {
			pointer-events:none;
		}
	<?php endif; ?>
</style>
<script type="text/javascript">
	//初回再生＋プレイヤーが準備できてたら
	//$(".baseSpeedbarElem").css('pointer-events','none');
	//$(".baseSeekAreaElem").css('pointer-events','none');


	<?php if ($this->_tpl_vars['isSP'] == 'pc' && $this->_tpl_vars['video_id2'] == ''): ?>
		//seekbarDisplayCh();
		function seekbarDisplayCh(){
			console.log("seekbarDisplayCh");
			<?php if ($this->_tpl_vars['set_btn_flg']): ?>
			console.log("["+ak_initialized_flg+"]");
			//if (!ak_initialized_flg) {
				console.log("["+$('.alfplayer_base').length+"]");
				if( $('.alfplayer_base').length ){
					$(".alfplayer_base").css('height','430px');
				}
				console.log("["+$('.baseSeekBarElem').length+"]");
				if( $('.baseSeekBarElem').length ){
					$(".baseSeekBarElem").css('display','none');
				}
				setTimeout(function(){
					seekbarDisplayCh();
				}, 500);
			//}
			<?php endif; ?>
		}
	<?php endif; ?>

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
	//+++++++++++++++++++++++
	setPrevEvent();
	function setPrevEvent(){
		var classPrevElements = document.getElementsByClassName( "basePrevElem" );
		console.log("[classPrevElements.length:"+classPrevElements.length+"]");
		if( classPrevElements.length==0 ){
			setTimeout(function(){
				setPrevEvent();
			}, 1000);
		} else {
			for( var i = 0; i < classPrevElements.length; i++ ) {
				classPrevElements[i].onclick = function () {
					if (typeof(jwplayer) == "function") {
						jwplayer().seek(0);
					}
				}
			}
		}
	}

	setNextEvent();
	function setNextEvent(){
		var classNextElements = document.getElementsByClassName( "baseNextElem" );
		console.log("[classNextElements.length:"+classNextElements.length+"]");
		if( classNextElements.length==0 ){
			setTimeout(function(){
				setNextEvent();
			}, 1000);
		} else {
			for( var i = 0; i < classNextElements.length; i++ ) {
				classNextElements[i].onclick = function () {
					<?php if ($this->_tpl_vars['next_contents_flg']): ?>
						document.playerForm.submit();
					<?php endif; ?>
				}
			}
		}
	}
	//+++++++++++++++++++++++
</script>