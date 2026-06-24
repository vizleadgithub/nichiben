<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "category";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 設問グループ 説明 */
		TEXTAREA{
			width  : 100%;
			height : 70px;
		}
		/* 設問 ローディングバー */
		#exam_problem_ul .li_loading_bar{
			border-bottom  : 0px none;
			height         : 40px;
			vertical-align : middle;
			line-height    : 40px;
			text-align     : left;
			margin-left    : 75px;
		}

		#category_name_count{
			float : right;
		}
	</style>
	<script type="text/javascript"><!--
		$(function(){
			category_name_length = $('#category_name').val().length;
			document.getElementById('category_name_count').innerHTML = "(" + category_name_length + "/40文字)";
		});
		//入力文字数をリアルタイムでカウント
		function ShowLength( idn, str, max_count ) {
			var count_length = 0;
			//if(str.length < max_count){
				count_length = str.length;
			//}else{
			//	count_length = max_count;
			//}
   			document.getElementById(idn).innerHTML = "(" + count_length + "/" + max_count + "文字)";
		}
	// --></script> 
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','カテゴリ管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','カテゴリを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_category/_submenu', array(
				'selected'	=> 'category',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<!--<a class="btn_seach selected" href="/cms_category/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>-->
					<!--<a class="btn_add" href="/cms_ecategory/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>-->
				</div>

				<h2><?= $this->lang->line_or_def('msg_category_input','カテゴリの情報を入力してください') ?></h2>

				<?=form_open_multipart("cms_category/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($overlap_error_msg)?'<div class="error">'.$overlap_error_msg.'</div>':'')?>
					<input type="hidden" name="update_flg"            value='<?=set_value('update_flg'                , $category['update_flg'])?>'>
					<input type="hidden" name="term_id" value='<?=set_value('cms_category_id' , $category['term_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_name','カテゴリ名') ?></th>
							<td>
								<input type=text name="name" maxlength="256" size="30" value='<?=set_value('name',$category['name'])?>' id="category_name" onkeyup="ShowLength( 'category_name_count' , value , 40);" maxlength="40"><br><span id="category_name_count" >(0/40文字)</span>
							</td>
						</tr>
						<!--<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_slug','スラッグ') ?></th>
							<td>-->
								<input type="hidden" name="slug" maxlength="256" size="30" value='<?=set_value('slug',$category['slug'])?>'>
							<!--</td>
						</tr>-->
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_parent','親カテゴリ') ?></th>
							<td>
								<select name="parent" id="parent">
									<option value="21">root</option>
									<?php foreach( $parent_root_category_list AS $row ){ ?>
										<option value="<?php print($row["term_id"]); ?>" <?php if($row["term_id"]==$category['parent']){ ?> selected <?php } ?> ><?php print($row["name"]); ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php if( $category['term_id']>0 ){ ?>
							<input type='image' src='/static/image/btn_back.png' onClick='location.href = "<?php print(site_url('cms_category')) ?>/detail/<?=set_value('cms_category_id' , $category['term_id'])?>";return false;' />
						<?php } ?>
						<input type='image' src='/static/image/btn_confirm.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
