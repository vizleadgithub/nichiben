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
		});
		
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
			<? $this->load->view('cms_student/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_student", array('method'=>'post'))?>
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
							<th><?= $this->lang->line_or_def('common_','会員区分') ?></th>
							<td >
								<?=form_dropdown('s_lawyer_division',$mtb_lawyer_division, set_value('s_lawyer_division',$s_lawyer_division));?>
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
											<option value="<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" <?= $select_option; ?>><?= htmlspecialchars( $val, ENT_QUOTES, 'UTF-8') ?></option>
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
				
				<? if($total_rows > 0): ?>
					<div style="float: left;height: 30px;line-height: 30px;text-align: center;width: 630px;">
						<?=$start_rows;?>～<?=$end_rows;?>件を表示中（全<?=$total_rows;?>件）
					</div>
						<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:130px; height:30px;background: url(/static/image/btn_blue2.png) no-repeat;font-size:13px;line-height: 30px;background-size:130px 30px;" onclick="" href="/cms_student/csv_download">CSVダウンロード</a>
<?php
/*
<a style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:120px; height:28px;
background-image : url(/static/image/btn_blue_trans.png),url(/static/image/btn_blue_trans.png),url(/static/image/btn_blue_trans.png);
background-repeat: no-repeat, no-repeat, no-repeat;
font-size:13px;line-height: 30px;background-size:120px 28px;" onclick="" href="/cms_student/csv_download">CSVダウンロード</a>
*/
?>
					<div style="clear:both;"></div>
				<? endif; ?>
				
				<table class="list">
					<tr>
						<th style="width:96px;"><? //76px ?>
							<?= $this->lang->line_or_def('common_','登録番号') ?> <a href="<?= htmlspecialchars( $order_by_asc, ENT_QUOTES, 'UTF-8') ?>" class="order_by_link">▲</a> <a href="<?= htmlspecialchars( $order_by_desc, ENT_QUOTES, 'UTF-8') ?>" class="order_by_link">▼</a>
						</th>
						<th style=""><?= $this->lang->line_or_def('common_','会員区分') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','氏名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','FP') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','代替権限') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($student_list)) { ?>
						<?php foreach($student_list as $student) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc"><a href="/cms_student/detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $student['lawyer_number'], ENT_QUOTES, 'UTF-8') ?></td>

								<td class="tdc">
									<?php if(isset($student['lawyer_division'])): ?><? // NULL以外の文字。空文字列はＯＫ ?>
										<?php if( isset($mtb_lawyer_division[$student['lawyer_division']]) ): ?><? // 0～5 が対象 ?>

											<?php if($mtb_lawyer_division[$student['lawyer_division']] == ''): ?>
												<?= 'その他'; ?><? //= '－'; ?>
											<?php else: ?>
												<?= htmlspecialchars( $mtb_lawyer_division[$student['lawyer_division']], ENT_QUOTES, 'UTF-8') ?>
											<?php endif; ?>

										<?php else: ?><? // 0～5以外・空の文字列が対象 ?>
											<? //= '－'; ?>
											<?php if($student['lawyer_division'] == ''): ?>
												<?= '事務局'; ?>
											<?php else: ?>
												<?= 'その他'; ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>

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
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="7"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
