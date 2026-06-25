<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
	<style>
	#s_birthday_start{
		width: 76px;
	}
	#s_birthday_end{
		width: 76px;
	}
	</style>
	<script type="text/javascript">
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datepicker設定
			$('#s_birthday_start').datepicker(datepickeroption);
			$('#s_birthday_end'  ).datepicker(datepickeroption);
			
			
			$(".list TR").click(function (){
				if( $(this).children(".tdc").children("INPUT").prop('checked') ){
					$(this).children(".tdc").children("INPUT").attr("checked", false);
				}else{
					$(this).children(".tdc").children("INPUT").attr("checked", true);
				}
			});
			
			$(".sub_auth_ethic_training").click(function (){
//		//	$("*[name=sub_auth_ethic_training]").click(function (){
				if( $(this).prop('checked') ){
					$(this).attr("checked", false);
				}else{
					$(this).attr("checked", true);
				}
			});
			
		});
		
		// 登録番号を選択した場合、詳細画面へ遷移
		function edit_item(id){
			location.href ="<?=base_url()?>cms_student/detail/" + id;
		}

		// 権限付与チェックボックスALL-ON or ALL-OFF
		function select_all_sub_auth(){
			// 全体数・チェック数を取得
			var all_count = 0;
			var chk_count = 0;
			$(".list input:checkbox").map(function() {
				all_count = all_count + 1;
				if( $(this).attr('checked') ) {
					chk_count = chk_count + 1;
				}
			//	alert($(this).attr('checked'));
			//	if( $(this).attr('checked') ) {
			//		students_checked[$(this).val()] = true;
			//	}else{
			//		delete students_checked[$(this).val()];
			//	}
			});
			
			// 全てチェックありならば全てのチェックをはずす。それ以外は全てのチェックをいれる。
			if(all_count == chk_count){
				$(".list input:checkbox").removeAttr('checked');
			}else{
				$(".list input:checkbox").attr('checked','checked');
			}



		//	var a = ('.list input.sub_auth_ethic_training:checked').length;
		//	alert(a);


		//	if($('#student_list input:checked').length){
		//		$('#student_list input').removeAttr('checked');
		//		$(".select_all_affiliation.select_student").css("background-position", "center top");
		//	}else{
		//		$('#student_list input').attr('checked','checked');
		//		$(".select_all_affiliation.select_student").css("background-position", "center bottom");
		//	}
		//	
		//	// [2012/11/30]値の初期化
		//	$("#student_list input:checkbox").map(function() {
		//		if( $(this).attr('checked') ) {
		//			students_checked[$(this).val()] = true;
		//		}else{
		//			delete students_checked[$(this).val()];
		//		}
		//	});
		//	return false;
		}


		
	</script>
	
	<style type="text/css">
	<!--
		.order_by_link{
			text-decoration: none;
			color:#FFFFFF;
		}
		.order_by_link hove{
			color:#00A4E2;
		}
	-->
	</style>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_student','受講者管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_student_comment','受講者を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_student_sub_auth/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_student_sub_auth", array('method'=>'post'))?>
					<? // ID昇順降順の情報 ?>
					<input type="hidden" name="order_by" value='<?= set_value('order_by', $order_by); ?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<input type="text" name="s_name" size="45" value="<?=set_value('s_name',$s_name)?>">
								<p style="color:red;">※名前は姓と名の間にスペースを入力してください。</p>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','登録番号') ?></th>
							<td >
								<input type="text" name="s_lawyer_number" size="20" value="<?=set_value('s_lawyer_number',$s_lawyer_number)?>">
								&nbsp;<font color="#ff0000"><?= $this->lang->line_or_def('msg_','※半角入力') ?></font>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<input type="text" name="s_email" size="45" value="<?=set_value('s_email',$s_email)?>">
							</td>
						</tr>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
							<td>
								<select name="s_bar_association">
									<?
										$select_option = "";
										if( (isset($s_bar_association)) && ($s_bar_association == 0) ){
											$select_option = "selected";
										}
									?>
									<?php if( count($mtb_bar_association) > 1 ): ?>
										<option value="" <?= $select_option; ?>></option>
									<?php endif; ?>
									<?php foreach($mtb_bar_association as $index => $val): ?>
										<?php //if($index > 1): ?>
											<?php
												$select_option = "";
												if( (isset($s_bar_association)) && ($s_bar_association == $index) ){
													$select_option = "selected";
												}
											?>
											<option value="<?= $index ?>" <?= $select_option; ?>><?= htmlspecialchars( $val, ENT_QUOTES, 'UTF-8') ?></option>
										<?php //endif; ?>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td >
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
						<tr>
							<th style="line-height: 30px;vertical-align: top;"><?= $this->lang->line_or_def('common_','代替権限') ?></th>
							<td >
								<?
									$on_flag = false;
									if($s_sub_auth_ethic_training_on) $on_flag = true;
									$off_flag = false;
									if($s_sub_auth_ethic_training_off) $off_flag = true;
								?>
							
								<div style="line-height:30px;width:200px;float:left;"><?= form_checkbox('s_sub_auth_ethic_training_on',  '1', $on_flag); ?>&nbsp;あり</div>
								<div style="line-height:30px;width:200px;float:left;"><?= form_checkbox('s_sub_auth_ethic_training_off', '1', $off_flag); ?>&nbsp;なし</div>
								<div style="clear:both;"></div>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_search.png'>
					</div>
				</form>
				<br />
				
					<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_blue_trans.png) no-repeat;font-size:13px;line-height: 30px;background-size:80px 28px;" onclick="" href="/cms_student_sub_auth/index_upload">CSVアップ</a><br/>
				<? if($total_rows > 0): ?>
					<div style="float: left;height: 30px;line-height: 30px;text-align: center;width: 540px;">
						（全<?=$total_rows;?>件）
					</div>
					<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_red9028.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('ON');return false;" href="#">一括権限付与</a>
					<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_blue9028.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('OFF');return false;" href="#">一括権限剥奪</a>
					<div style="clear:both;"></div>
				<? endif; ?>
<!--				
				<div style="float: left;height: 30px;line-height: 30px;text-align: center;width: 540px;">
					<? if($total_rows > 0): ?>
						（全<?=$total_rows;?>件）
					<? endif; ?>
				</div>
				<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_red.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('ON');return false;" href="#">一括権限付与</a>
				<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('OFF');return false;" href="#">一括権限剥奪</a>
				<div style="clear:both;"></div>
 -->				
<!--
<a href="#" onclick="cource_submenu_popup()" >ポップアップテスト</a><br/>
<br/>
<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:40px; height:20px;background: url(/static/image/btn_blue.png) no-repeat;font-size:12px;line-height: 20px;background-size:40px 20px;" onclick="select_all_sub_auth();return false;" href="#">全員</a>
<br/>
<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="select_all_sub_auth();return false;" href="#">一括権限付与</a>
<br/>
<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_red.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="select_all_sub_auth();return false;" href="#">一括権限剥奪</a>
<br/>
<br/><br/>
 -->


				
				<table class="list">
					<tr>
						<th style="width:96px;"><? //76px ?>
							<?= $this->lang->line_or_def('common_','登録番号') ?> <a href="<?= htmlspecialchars( $order_by_asc, ENT_QUOTES, 'UTF-8') ?>" class="order_by_link">▲</a> <a href="<?= htmlspecialchars( $order_by_desc, ENT_QUOTES, 'UTF-8') ?>" class="order_by_link">▼</a>
						</th>
						<th style=""><?= $this->lang->line_or_def('common_','氏名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','FP') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','代替権限') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
						<th style="">
							<?= $this->lang->line_or_def('common_','権限付与チェック') ?><a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:35px; height:18px;background: url(/static/image/btn_blue3518.png) no-repeat;font-size:12px;line-height: 18px;background-size:35px 18px;margin: 0 15px 1px; background-color: transparent; " onclick="select_all_sub_auth();return false;" href="#">全員</a>
						</th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($student_list)) { ?>
						<?php foreach($student_list as $student) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
							<!--<td class="tdc"><a href="/cms_student/detail/<?= $student['student_id'] ?>"><?=$student['lawyer_number']?></td> -->
								<td class="tdc"><a onclick='edit_item("<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>");return false;' style="text-decoration: underline;"><?= htmlspecialchars( $student['lawyer_number'], ENT_QUOTES, 'UTF-8') ?></a></td>
								<td class="tdc"><?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td class="tdc"><?= htmlspecialchars( $student['student_email'], ENT_QUOTES, 'UTF-8') ?></td>
								<td class="tdc">
									<?= ($student['presence_passport']==1) ? '○' : '－' ; ?>
								</td>
								<td class="tdc">
									<?= ($student['sub_auth_ethic_training']==1) ? '○' : '－' ; ?>
								</td>
								<td class="tdc">
									<?php if(isset($student['bar_association_id'])): ?>
										<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
											<?= htmlspecialchars( $mtb_bar_association[$student['bar_association_id']], ENT_QUOTES, 'UTF-8') ?>
										<?php else: ?>
											<?= ''; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>

								<td class="tdc">
									<input type="checkbox" class="sub_auth_ethic_training" name="sub_auth_ethic_training[]" value='<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>' />
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th colspan="7" style="height: 30px;background: none repeat scroll 0 0 #72726E;color: #FEFEFE;"></th>
						</tr>
					<?php } ?>
				<!--<tr>
						<th class="pager" colspan="6"><?=$pagination?></th>
					</tr>
				 -->



<!--
					<tr>
						<? //$this->lang->line_or_def('common_date_of_birth','生年月日') ?>
						<th style="width:56px;"><?= $this->lang->line_or_def('common_id','ID') ?> <a href="<?= $order_by_asc; ?>" class="order_by_link">▲</a> <a href="<?= $order_by_desc; ?>" class="order_by_link">▼</a></th><? //76px ?>
						<th style=""><?= $this->lang->line_or_def('common_name','名前') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','弁護士番号') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','パスポートの有無') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','権限付与チェック') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($student_list)) { ?>
						<?php foreach($student_list as $student) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
							<!--<td class="tdc"><a href="/cms_student/detail/<?= $student['student_id'] ?>"><?=$student['student_id']?></td>
								<td class="tdc"><?=$student['student_id']?></td>
								<td class="tdc"><?=$student['student_name']?></td>
								<td class="tdc"><?=$student['student_email']?></td>
								<td class="tdc"><?=$student['lawyer_number']?></td>
								<td class="tdc"><?=$student['presence_passport']?></td>
								<td class="tdc">
									<?php if(isset($student['bar_association_id'])): ?>
										<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
											<?= $mtb_bar_association[$student['bar_association_id']]; ?>
										<?php else: ?>
											<?= ''; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<td class="tdc">
									<input type="checkbox" class="sub_auth_ethic_training" name="sub_auth_ethic_training[]" value="1" <? ( $student['sub_auth_ethic_training'] == "1" ) ? 'checked' : ''; ?>
									<!--<?=$student['sub_auth_ethic_training']?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>

					<tr>
						<th class="pager" colspan="6"><?=$pagination?></th>
					</tr>
				-->
				</table>

				<? if($total_rows > 0): ?>
<br/>
				<div style="float: left;height: 30px;line-height: 30px;text-align: center;width: 540px;"> </div>
				<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_red9028.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('ON');return false;" href="#">一括権限付与</a>
				<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:90px; height:28px;background: url(/static/image/btn_blue9028.png) no-repeat;font-size:13px;line-height: 30px;background-size:90px 28px;" onclick="cource_submenu_popup('OFF');return false;" href="#">一括権限剥奪</a>
				<div style="clear:both;"></div>
					<? endif; ?>


			</div>
			<div class="clear"></div>


<!-- 講座内タブ選択画面 -->
<div id="cource_submenu">
	<div id="question"><!-- style="display:block;" -->
		<div class="question_message">※問い合わせ文書</div>
		<div class="submit">
			<a href="#" onclick="cource_submenu_run();return false;" class="question_button blue">は い</a>
			<a href="#" onclick="cource_submenu_popup_close();return false;" class="question_button gray">いいえ</a>
		</div>
	</div>
	<div id="wait"><!-- style="display:block;" -->
		<div class="question_message" style="margin-top: 60px;">処理中<br/><img class="" style="margin-top: 20px;" src="/static/image/loading.gif"></div>
	</div>
	<div id="answer"><!-- style="display:none;" -->
		<div class="question_message">※処理結果文書</div>
		<div class="submit">
			<a href="#" onclick="cource_submenu_popup_close();return false;" class="question_button blue">OK</a>
		</div>
	</div>
</div>

<!--
				<div class="submit">
					<?php
						switch($btn_kirikae_flg){
							case 1://修正画面
								print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$cource['cource_id'].");return false;' />";
								print "<input type='image' src='/static/image/btn_ok.png' />";
								break;
								
							case 2://詳細画面
								print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_cource')."\";return false;' />";
								//print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$cource['cource_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
								print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
								break;
						}
					?>
				</div>


<a href="#" onclick="window.open(&quot;/cms_issue/submit_list/11/&quot;)" style="display:inline-block;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; margin: 0 15px;width:80px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:14px;line-height: 30px; text-shadow: 1px 1px 1px #777777; ">一 覧</a>
-->


		</div>
	</div>










<script type="text/javascript">
// メッセージボックス表示
var check_count;
var check_student_id;
var check_kinds;

function cource_submenu_popup(status_flag){
	
	check_kinds      = status_flag;
	check_count      = 0;
	check_student_id = "0";
	$(".list input:checkbox").map(function() {
		if( $(this).prop('checked') ) {
			check_count       = check_count + 1;
			check_student_id += "-" + $(this).val();
		}
	});
	console.log(check_count);

	// タグ選択画面非表示なら表示
	if( $("#cource_submenu").css('display')=='none' ){
		
		_lightbox_base('show', function(){
			$("#cource_submenu").css({
				'display'	: 'block'
			});
		});
		
		// 付与or剥奪の切り替え
		var kinds = "付与";
		if(status_flag == "OFF"){
			kinds = "剥奪";
		}



		
		// 質問文の作成
		var question_msg  = "";
		    question_msg += "代替倫理研修の権限を" + kinds + "します。<br/>";
		    question_msg += "権限" + kinds + "人数は" + check_count + "名です。<br/>";
		    question_msg += "よろしいですか？" ;
		$("#question .question_message").html(question_msg);
			
		// 処理結果文の作成
		var answer_msg  = "";
		    answer_msg += "代替倫理研修の権限を" + kinds + "しました。<br/>";
		    answer_msg += "権限" + kinds + "人数は" + check_count + "名です。<br/>";
		    answer_msg += "　";
		$("#answer .question_message").html(answer_msg);

		
		
//		var question_msg = $("#question .question_message").text();
//		    question_msg = question_msg.split("付与（剥奪）").join(kinds);
//		
//		$("#question .question_message").text(question_msg);
//		question_msg.replace('付与', '123');
//		alert(question_msg);
		//付与（剥奪）
		//20
		
		if(check_count>0){
			$("#question").css({
				'display'	: 'block'
			});
			$("#wait").css({
				'display'	: 'none'
			});
			$("#answer").css({
				'display'	: 'none'
			});
			
		//	$("#cource_submenu").css({
		//		'display'	: 'block'
		//	});
		}else{
			// 処理結果文の作成
			answer_msg  = "";
			answer_msg += "　<br/>";
			answer_msg += "権限付与チェックのチェックがありません。<br/>";
			answer_msg += "　";
			$("#answer .question_message").html(answer_msg);
			
			$("#question").css({
				'display'	: 'none'
			});
			$("#wait").css({
				'display'	: 'none'
			});
			$("#answer").css({
				'display'	: 'block'
			});
		}


	}
}

// はい押下時
function cource_submenu_run(){

//alert("[check_count="+check_count+"][check_student_id="+check_student_id+"][check_kinds="+check_kinds+"]");
//return false;



	$("#question").css({
		'display'	: 'none'
	});
	$("#wait").css({
		'display'	: 'block'
	});
	
	// ajax による処理
//	ajax_sub_auth_change(check_student_id, check_kinds);
	
	// 処理中画面表示確認用、
	$(function(){
		setTimeout(function(){
			ajax_sub_auth_change(check_student_id, check_kinds);
			
				$(".list input:checkbox").removeAttr('checked');

			// ～ここに処理を記載～
			
			
			$("#wait").css({
				'display'	: 'none'
			});
			$("#answer").css({
				'display'	: 'block'
			});
		},1500);
	});
//	$("#wait").css({
//		'display'	: 'none'
//	});
//	$("#answer").css({
//		'display'	: 'block'
//	});
}

// メッセージボックス閉じる（いいえ押下時、ＯＫ押下時）
function cource_submenu_popup_close(input_get_select_cource, input_get_select_tag){
	_lightbox_base('hide');


	// z-indexを調整する
	// Jun Add 2013/06/17
	// hikari統合
	$("#cource_submenu").css({
		'display'	: 'none',
		'z-index'	: ''
	});
	

}

function ajax_sub_auth_change(select_student_id, change_kinds){
	$.ajax({
		url: "/cms_student_sub_auth/change_sub_auth",
		type: "POST",
		data: "select_student_id="+select_student_id+"&change_kinds="+change_kinds+"",
		
		success: function(response) {
			if(response){
				return true;
			}else{
				return true;
			}
		}
	});
	return false;
}


//=======================================================================//
//= lightbox
//=======================================================================//
function _lightbox_base(f, func){
	if(f == 'hide'){
		$('#___lightbox_base___').css({
			'opacity'	: 0
		});
		$('#___lightbox_base___').hide();
		$('#___lightbox_base___').unbind();
	}
	else if(f == 'show'){
		if(!$('#___lightbox_base___').length){
			$('body').append('<div id="___lightbox_base___"></div>');
			$('#___lightbox_base___').css({
				'position'		: 'fixed',
				'top'			: 0,
				'background'	: 'black',
				'z-index'		:  999998,
				'width'			: '100%',
				'height'		: '100%',
				'opacity'		: 0
			});
		}
		$('#___lightbox_base___').show().animate({
			'opacity'		: 0.6
			}, 350, function(){
				func();
			}
		);
	}
}

function lightbox_close(){
	$('.___lightbox_target___').hide().removeClass('___lightbox_target___');
	_lightbox_base('hide');
}
function lightbox(target, param){
	var param = $.extend({
		'close'		: 1
	}, param);

	$(target).hide();
	_lightbox_base('show', function(){
		$(target).addClass('___lightbox_target___');
		$(target).css({
			'position'		: 'absolute',
			'z-index'		:  999999,
			'top'			: $($.browser.safari ? 'body' : 'html').scrollTop() + (($(window).height() / 2) - ($(target).height() / 2)),
			'left'			: ($(window).width() / 2) - ($(target).width() / 2)
		}).show();
		if(param['close']){
			$(target).find('.close').unbind().bind('click', function(){
				lightbox_close();
			});
		}
	});
}
</script>

<style type="text/css">
<!--
	/* 講座内タグ選択画面 - 本体 */
	#cource_submenu{
		background-color	: #F4F4F4;
		background-repeat	: no-repeat;
		border-radius		: 8px 8px 8px 8px;
		display				: none;
		position			: absolute;
		z-index				: 999999;
		font-size			: 13px;
		line-height			: 17px;
		margin				: 0px auto;
		padding				: 13px; 
		width				: 550px; 
		height				: 200px;
		top					: 250px;
		left				: 0px; 
		right				: 0px; 
		box-shadow			: 1px 1px 5px #000000;
		-o-box-shadow		: 1px 1px 5px #000000;
		-ms-box-shadow		: 1px 1px 5px #000000;
		-moz-box-shadow		: 1px 1px 5px #000000;
		-webkit-box-shadow	: 1px 1px 5px #000000;
	}

	/* メッセージ本文 */
	.question_message{
		font-size		: 20px;
		height			: 96px;
		line-height		: 35px;
		margin			: 10px 0px 10px 0px;
		text-align		: center;
	}

	/* ボタンの設定 */
	.question_button{
		display				: inline-block;
		text-decoration		: none;
		font-weight			: bold;
		color				: white;
		text-align			: center;
		vertical-align		: middle;
		margin				: 23px 25px 0px;
		width				: 80px;
		height				: 28px;
		font-size			: 15px;
		line-height			: 30px;
		text-shadow			: 1px 1px 1px #777777;
	}

	/* ボタンの設定（背景色のみ）*/
	.question_button.blue{
		background			: url('/static/image/btn_blue.png') no-repeat;
	}
	.question_button.gray{
		background			: url('/static/image/btn_gray.png') no-repeat;
	}

	/* ボタンの設定（選択・HOVER状態の文字色）*/
	.question_button:hover, #cource_submenu .question_button:active{
		color				: white;
	}
-->
</style>





	<?php $this->load->view('header/body_footer');?>
</body>
</html>
