<?php /* Smarty version 2.6.27, created on 2014-07-17 13:37:54
         compiled from /srv/alfproduct/smarty/templates/smartphone/player/sample.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/player/sample.tpl', 61, false),)), $this); ?>
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


<script type="text/javascript">
var ak_initialized_flg = false;
function ak_initialized() {
		ak_volume(0.5);
	
		ak_setPlayRateInterval(0.2);
	
		if (!ak_initialized_flg) {
		ak_initialized_flg = true;
		<?php if ($this->_tpl_vars['ak_initialized_param'] != ""): ?>
		    ak_setSeek(0, [<?php echo ((is_array($_tmp=$this->_tpl_vars['ak_initialized_param'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]);
		<?php endif; ?>
		ak_playAndSeek(0, 0);
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

function ak_event_all(type, idx, time) {
	if(ak_seekCount<2){
		ak_seekCount+=1;
	}
	
	if (ak_eventTime != null) { clearTimeout(ak_eventTime); }
	
		if (type == "timeupdate") {
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
<?php $_from = $this->_tpl_vars['arr_chapter_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['chapter_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['chapter_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['chapter_list']['iteration']++;
?>
	<li>		<a href="javascript:void(0)" onClick="ak_playAndSeek(0, <?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
);return false;" id="seek0_<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
_2"><span id="seek0_<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time_sec'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="idxall idx0"></span><span class="time"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_time'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>&nbsp;<span class="chapter_name"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['chapter_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></a>
		
	</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
<?php endif; ?>

<div style="text-align:left;padding-top:50px;" class="btn">
<a href="<?php echo $this->_tpl_vars['back_url']; ?>
">戻る</a>
</div>

<div style="padding-top:10px;">
※全画面表示を解除する場合は，キーボードの「ESC」ボタンを押します
</div>