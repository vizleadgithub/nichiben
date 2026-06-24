<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "teacher";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_teacher','講師管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_teacher_comment','講師を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_teacher/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_teacher/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_teacher/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_teacher", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<input type="text" name="s_name" size="45" value="<?=set_value('s_name',$s_name)?>">
								<p style="color:red;">※名前は姓と名の間にスペースを入力してください。</p>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<input type="text" name="s_email" size="45" value="<?=set_value('s_email',$s_email)?>">
							</td>
						</tr>

						<? if( $this->config->item('language') != 'alfsales' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td >
								<input type="text" name="s_id" size="45" value="<?=set_value('s_id',$s_id)?>">
							</td>
						</tr>
						<? endif; ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td >
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_search.png' />
					</div>
				</form>

				<div id="list">
					<table class="list">
						<tr>
							<th style="width:76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<th style="width: 200px;"><?= $this->lang->line_or_def('common_note','備考') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($teacher_list)) { ?>
							<?php foreach($teacher_list as $teacher) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td class="tdc"><a href="/cms_teacher/detail/<?= htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td class="tdc"><?= htmlspecialchars( $teacher['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td class="tdc">
										<?= htmlspecialchars( $teacher['teacher_email'], ENT_QUOTES, 'UTF-8') ?>
										<?php
										if($teacher['lock']==1){
											print('<span style="color:red;">アカウントロック中</span>');
										}
										?>
									</td>
									<td class="tdc">
										<div style="overflow:hidden;height: 22px;line-height: 22px;">
											<? 
												if(!$teacher['teacher_note']){
													print "　";
												}else{
													print str_replace(array("\r\n","\r","\n"), '　', htmlspecialchars( $teacher['teacher_note'], ENT_QUOTES, 'UTF-8') );
												}
											?>
										</div>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr>
							<th colspan="4" class="pager"><?=$pagination?></th>
						</tr>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
