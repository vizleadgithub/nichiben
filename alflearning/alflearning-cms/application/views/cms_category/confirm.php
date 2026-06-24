<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "category";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		$(function(){
		});
		
		//--------------------------------------------------
		//詳細確認画面　削除ボタン押下
		//--------------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_category/delete_item/" + id ;
			}
		}
		//--------------------------------------------------
		//詳細確認画面　修正ボタン押下
		//--------------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_category/edit/";
		}
	</script>
	<style type="text/css">
		/* グレーボタン */
		#btn_gray_button,#gray_button{
			display         :inline-block;
			margin          : 0 15px;
			text-decoration : none;
			font-weight     : bold;
			color           : white;
			text-align      : center;
			vertical-align  : middle;
			width           : 80px;
			height          : 28px;
			font-size       : 13px;
			line-height     : 30px;
			background-size : 80px 28px;
			background      : url("/static/image/btn_gray.png") no-repeat;
		}
		/* ブルーボタン */
		#btn_blue_button,#blue_button{
			display         :inline-block;
			margin          : 0 15px;
			text-decoration : none;
			font-weight     : bold;
			color           : white;
			text-align      : center;
			vertical-align  : middle;
			width           : 80px;
			height          : 28px;
			font-size       : 13px;
			line-height     : 30px;
			background-size : 80px 28px;
			background      : url("/static/image/btn_blue.png") no-repeat;
		}
	</style>
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
					<!--<a class="btn_add" href="/cms_category/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>-->
				</div>

				<h2>
					<?php 
						if( $category['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_category_detail','カテゴリ情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_category_confirm','カテゴリ情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_category_detail','カテゴリ情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_category/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_name','カテゴリ名') ?></th>
							<td>
								<?= htmlspecialchars( $category['name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<!--
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_slug','スラッグ') ?></th>
							<td>
								<?= htmlspecialchars( $category['slug'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						-->
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_category_parent','親カテゴリ') ?></th>
							<td>
								<?php if($category['parent']==21){ ?>
									root
								<?php } else { ?>
									<?php foreach( $parent_root_category_list AS $row ){ ?>
										<?php if($row["term_id"]==$category['parent']){ print( htmlspecialchars( $row["name"], ENT_QUOTES, 'UTF-8') ); } ?>
									<?php } ?>
								<?php } ?>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_category')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									//print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$category['term_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
									break;
							}
						?>
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
