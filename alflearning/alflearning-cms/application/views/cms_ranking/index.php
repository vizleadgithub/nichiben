<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "ranking";
	$this->load->view('header/header',$data);?>
	
	<style type="text/css">
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_ranking','ランキング管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_ranking_comment','ランキングを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_ranking/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
				</div>

				<!--<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>-->
				<?=validation_errors('<div class="error">', '</div>'); ?>
				<?= (isset($error_msg) && $error_msg ? '<div class="error">'.htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8').'</div>' : ''); ?>

				<table class="list___">
					<tr style="border-bottom: solid 1px #666666;">
						<th style="width: 76px;"><?= $this->lang->line_or_def('common_rank','ランキング') ?></th>
						<th><?= $this->lang->line_or_def('common_product_name','商品名') ?></th>
					</tr>
					<script type="text/javascript">
						function fnc_pop_search_form(rank_i){
							window.open("/alfproduct/product/search_product_ranking.php?rank_i=" + rank_i, "pop_search_form", "scrollbars=yes,width=1024,height=980");
						}
						function fnc_del_ranking(rank_i){
							$("#product_name_"+rank_i+"_disp").text("");
							$("#product_name_"+rank_i).val("");
							$("#product_id_"+rank_i).val("");
						}

						function submit_ranking_form(){
							$("#file_text1").val( $("#file_text1_disp").val() );
							$("#file_text2").val( $("#file_text2_disp").val() );
							$("#file_text3").val( $("#file_text3_disp").val() );
							document.getElementById('ranking_form').submit();
						}

function upload_ranking_form(no) {
    var upload_token = '';

    // 1. アップロードトークンを取得
    $.ajax({
        url: "/cms_ranking/get_token",
        type: "POST",
        data: { no: no },  // ★ここではFormDataは必要なし
        dataType: 'json'
    }).done(function(data) {
        if (data.upload_token) {
            upload_token = data.upload_token;

            // 2. FormDataの作成
            var fd = new FormData($('#file_form' + no).get(0));
            fd.append('no', no);
            fd.append('upload_token', upload_token);

            // 3. ファイルアップロード
            $.ajax({
                url: "/cms_ranking/set_upload_file/",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json'
            }).done(function(data) {
                if (typeof data.file_path !== 'undefined') {
                    $('#file_path' + no).val(data.file_path);
                }
                if (typeof data.file_name !== 'undefined') {
                    $('#file_name' + no).val(data.file_name);
                }
                if (typeof data.file_path !== 'undefined') {
                    $('#file_path' + no + '_disp').text(data.file_path);
                }
                if (typeof data.file_name !== 'undefined') {
                    $('#file_name' + no + '_disp').text(data.file_name);
                }
                $('#uploader' + no).val("");
                alert("ファイル" + no + "をアップロードしました。");
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("アップロードに失敗しました。ステータスコード: " + jqXHR.status + "\nエラー: " + errorThrown);
            });
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        alert("トークンの取得に失敗しました。");
    });
}
					</script>
					<?=form_open("cms_ranking/confirm", array('method'=>'post', 'id'=>'ranking_form'))?>
						<input type="hidden" id="file_text1" name="file_text1" value="<?php print( htmlspecialchars($file_text1) ); ?>">
						<input type="hidden" id="file_name1" name="file_name1" value="<?php print( htmlspecialchars($file_name1) ); ?>">
						<input type="hidden" id="file_path1" name="file_path1" value="<?php print( htmlspecialchars($file_path1) ); ?>">
						<input type="hidden" id="file_text2" name="file_text2" value="<?php print( htmlspecialchars($file_text2) ); ?>">
						<input type="hidden" id="file_name2" name="file_name2" value="<?php print( htmlspecialchars($file_name2) ); ?>">
						<input type="hidden" id="file_path2" name="file_path2" value="<?php print( htmlspecialchars($file_path2) ); ?>">
						<input type="hidden" id="file_text3" name="file_text3" value="<?php print( htmlspecialchars($file_text3) ); ?>">
						<input type="hidden" id="file_name3" name="file_name3" value="<?php print( htmlspecialchars($file_name3) ); ?>">
						<input type="hidden" id="file_path3" name="file_path3" value="<?php print( htmlspecialchars($file_path3) ); ?>">
						<?php
						for($i=1;$i<=10;$i++){
							$product_id = "";
							$product_name = "";
							if(isset($ranking_list)) {
								foreach($ranking_list as $ranking) {
									if($ranking["rank"]==$i){
										$product_id = $ranking["product_id"];
										$product_name = $ranking["product_name"];
									}
								}
							}
							?>
							<tr style="border-bottom: solid 1px #666666;">
								<td><?php print($i); ?></td>
								<td>
									商品名:<span id="product_name_<?php print($i); ?>_disp"><?php print($product_name); ?></span>
									<input type="hidden" id="product_id_<?php print($i); ?>" name="product_id_<?php print($i); ?>" value="<?php print(htmlspecialchars($product_id)); ?>">
									<input type="hidden" id="product_name_<?php print($i); ?>" name="product_name_<?php print($i); ?>" value="<?php print(htmlspecialchars($product_name)); ?>">
									<br>
									<input type="button" id="search_button_<?php print($i); ?>" name="search_button_<?php print($i); ?>" value="　検索　" onclick="fnc_pop_search_form('<?php print($i); ?>')">
									<input type="button" id="delete_button_<?php print($i); ?>" name="delete_button_<?php print($i); ?>" value="　削除　" onclick="fnc_del_ranking('<?php print($i); ?>')">
								</td>
							</tr>
							<?php
						}
						?>
					</form>
					<tr style=""><td colspan="2">&nbsp;</td></tr>
					<tr style=""><td colspan="2">&nbsp;</td></tr>
					<tr style=""><td colspan="2">PDFファイル</td></tr>
					<tr style="">
						<td>ファイル1</td>
						<td>
							<form id="file_form1" name="file_form1" method="post" enctype="multipart/form-data">
								テキスト：<input type="text" id="file_text1_disp" name="file_text1_disp" value="<?php print( htmlspecialchars($file_text1) ); ?>"><br>
								ファイル：<span id="file_name1_disp"><?php print( htmlspecialchars($file_name1) ); ?></span><br>
								<input type="file" id="uploader1" name="file_data" />
								<a href="javascript:void(0);" onclick="upload_ranking_form(1)">アップロード</a>
							</form>
						</td>
					</tr>
					<tr style="">
						<td>ファイル2</td>
						<td>
							<form id="file_form2" name="file_form2" method="post" enctype="multipart/form-data">
								テキスト：<input type="text" id="file_text2_disp" name="file_text2_disp" value="<?php print( htmlspecialchars($file_text2) ); ?>"><br>
								ファイル：<span id="file_name2_disp"><?php print( htmlspecialchars($file_name2) ); ?></span><br>
								<input type="file" id="uploader2" name="file_data" />
								<a href="javascript:void(0);" onclick="upload_ranking_form(2)">アップロード</a>
							</form>
						</td>
					</tr>
					<tr style="">
						<td>ファイル3</td>
						<td>
							<form id="file_form3" name="file_form3" method="post" enctype="multipart/form-data">
								テキスト：<input type="text" id="file_text3_disp" name="file_text3_disp" value="<?php print( htmlspecialchars($file_text3) ); ?>"><br>
								ファイル：<span id="file_name3_disp"><?php print( htmlspecialchars($file_name3) ); ?></span><br>
								<input type="file" id="uploader3" name="file_data" />
								<a href="javascript:void(0);" onclick="upload_ranking_form(3)">アップロード</a>
							</form>
						</td>
					</tr>
				</table>
				<div class="submit">
					<a href="javascript:void(0);" onclick="submit_ranking_form()"><img src='/static/image/btn_register.png' /></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
	<script type="text/javascript">
		<?php if( $get_result=="ok" ){ ?>
			alert("ランキングを更新しました。");
		<?php } ?>
	</script>
</body>
</html>
