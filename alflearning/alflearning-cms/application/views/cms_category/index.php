<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "category";
	$this->load->view('header/header',$data);?>
</head>

<body class="<?= getenv('URL_SERVICE'); ?>">
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','カテゴリー管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','カテゴリーを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_category/_submenu', array(
				'selected'	=> 'category',
			));?>

			<div id="contents_main">
<?php /*
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_category/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<!--<a class="btn_add" href="/cms_category/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>-->
				</div>
				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_category", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th><?= $this->lang->line_or_def('common_category_name','カテゴリ名') ?></th>
							<td >
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_search.png' />
					</div>
				</form>
				<br />
*/ ?>
				<div id="list">
					<table class="list">
						<tr>
							<th style="width:76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th><?= $this->lang->line_or_def('common_category_name' ,'カテゴリー名') ?></th>
						</tr>
						<?php if(isset($category_list)) { ?>
							<?php foreach($category_list as $index=>$category) { ?>
								<tr class="">
									<td><a href="/cms_category/detail/<?= htmlspecialchars( $category['term_id'], ENT_QUOTES, 'UTF-8')  ?>/"><?= htmlspecialchars( $category['term_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= htmlspecialchars( $category['name'], ENT_QUOTES, 'UTF-8') ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
