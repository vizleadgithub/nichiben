<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "report";
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
			<div class="title"><?= $this->lang->line_or_def('common_heading_report','レポート') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_report_comment','月毎の集計レポートを表示します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_report/_submenu', array(
				'selected'	=> 'cms_user',
			)); ?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_report/cms_user/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
				</div>
				
				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_report/cms_user", array('method'=>'post'))?>
					<? // ID昇順降順の情報 ?>
					<input type="hidden" name="order_by" value='<?= set_value('order_by', $order_by); ?>'>
					<? // 代替権限の情報 ?>
					<input type="hidden" name="s_sub_auth_ethic_training_on"  value='<?= set_value('s_sub_auth_ethic_training_on',  $s_sub_auth_ethic_training_on); ?>'>
					<input type="hidden" name="s_sub_auth_ethic_training_off" value='<?= set_value('s_sub_auth_ethic_training_off', $s_sub_auth_ethic_training_off); ?>'>
					
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
											<option value="<?= $index ?>" <?= $select_option; ?>><?= $val ?></option>
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
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_search.png'>
					</div>
				</form>
				<br />
				<? if($total_rows > 0): ?>
					<div style="height: 30px;line-height: 30px;text-align: center;">
						<?=$start_rows;?>～<?=$end_rows;?>件を表示中（全<?=$total_rows;?>件）
					</div>
				<? endif; ?>
				
				<table class="list">
					<tr>
						<th style="width:96px;"><? //76px ?>
							<?= $this->lang->line_or_def('common_','登録番号') ?> <a href="<?= $order_by_asc; ?>" class="order_by_link">▲</a> <a href="<?= $order_by_desc; ?>" class="order_by_link">▼</a>
						</th>
						<th style=""><?= $this->lang->line_or_def('common_','氏名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','FP') ?></th>
					<!--<th style=""><?= '';//$this->lang->line_or_def('common_','代替権限') ?></th>-->
						<th style=""><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($student_list)) { ?>
						<?php foreach($student_list as $student) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc"><a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>"><?=$student['lawyer_number']?></td>
								<td class="tdc"><?=$student['student_name']?></td>
								<td class="tdc"><?=$student['student_email']?></td>
								<td class="tdc">
									<?= ($student['presence_passport']==1) ? '○' : '－' ; ?>
								</td>
							<!--<td class="tdc"><?= '';//($student['sub_auth_ethic_training']==1) ? '○' : '－' ; ?></td>-->
								<td class="tdc">
									<?php if(isset($student['bar_association_id'])): ?>
										<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
											<?= $mtb_bar_association[$student['bar_association_id']]; ?>
										<?php else: ?>
											<?= ''; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="6"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
