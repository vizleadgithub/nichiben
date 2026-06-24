<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>


<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>

	<!--<script type="text/javascript" src="/static/js/jquery.upload-1.0.2.js"></script>-->

	<style>
	#s_birthday_start{
		width: 76px;
	}
	#s_birthday_end{
		width: 76px;
	}
	</style>

	<script type="text/javascript"><!--
		var filePath = "";
		
		var change_list;
		
		/**
		 * 選択したファイルのパスをセットする
		 * @param fileElem inputタグ(file)の要素
		 */
		function setFilePath(fileElem) {
			filePath = fileElem.value;
		}

		/**
		 *  CSVファイル取り込み処理
		 * @param status_flag ON:許可　OFF:禁止
		 */
		function csv_upload(status_flag){
			// ファイルチェック
			var fileInput = document.getElementById('uploadfile');
			if (!fileInput.files || !fileInput.files[0]) {
				alert("ファイルを選択してください");
				return false;
			}

			var fd = new FormData();
			fd.append('uploadfile', fileInput.files[0]);
			fd.append('status_flag', status_flag);

			$.ajax({
				url: '/cms_student_sub_auth/csv_change_sub_auth',
				method: 'POST',
				data: fd,
				processData: false,
				contentType: false,
				dataType: 'json' // サーバがJSON文字列を返す想定
			}).done(function(res){
				// 以降はあなたの元コードほぼそのまま
				$('.list').find('tr:gt(0)').remove();

				var record_count = 0;
				let list_tr = "";

				if (res) {
					let parsed;
					//try {
						//parsed = JSON.parse(res);
						change_list = res;
					//} catch (err) {
						//console.error('JSON parse error:', res);
						//alert('サーバ応答の形式が不正です。');
						//return;
					//}
					change_list = res;

					for (let i = 0; i < change_list.length; i++) {
						record_count++;
						const tr_class = (record_count % 2 === 0) ? 'line_color' : '';
						list_tr	= "";
						list_tr += '<tr class="'+tr_class+'">';
						list_tr += '<td class="tdc">'+change_list[i].lawyer_number+'</td>';
						list_tr += '<td class="tdc">'+change_list[i].student_name+'</td>';
						list_tr += '<td class="tdc">'+change_list[i].student_email+'</td>';
						list_tr += '<td class="tdc">'+change_list[i].presence_passport+'</td>';
						list_tr += '<td class="tdc">'+change_list[i].sub_auth_ethic_training+'</td>';
						list_tr += '<td class="tdc">'+change_list[i].bar_association+'</td>';
						list_tr += '</tr>';
						$(".list").append(list_tr);
					}

					$(".order_by_link").css({ display: 'inline' });
				} else {
					$(".order_by_link").css({ display: 'none' });
				}

				$(".list").append('<tr><th colspan="7" style="height: 30px;background: #72726E;color:#FEFEFE;"></th></tr>');

				const output_status_flag = (status_flag === 'OFF') ? '剥奪' : '付与';
				const answer_msg	= "全"+record_count+"名に代替倫理研修の権限を"+output_status_flag+"しました。";
				$(".upload_result").html(answer_msg);

				$(".upload_msg").css({ display: 'block' });
				$(".upload_result").css({ display: 'block' });
				$(".upload_list").css({ display: 'table' });

				return false;
			}).fail(function(xhr){
				console.error(xhr);
				alert('アップロードに失敗しました。');
			});
		}
		
		// 処理結果詳細の昇順・降順
		function list_sort(sort_kinds){
			
			// ヘッダ以外の全行を削除
			$( '.list' ).find("tr:gt(0)").remove();
			
			var record_count = 0;
			var list_tr      = "";
			if(sort_kinds=="ASC"){
				for( i=0; i<change_list.length; i++ ) {
					// 行数カウント
					record_count = record_count + 1;
					
					// tr class の指定（偶数行のみ）
					var tr_class = '';
					if(record_count % 2 == 0){
						var tr_class = 'line_color';
					}
								
					list_tr  = "";
					list_tr += '<tr class="'+tr_class+'">';
					list_tr += '<td class="tdc">'+change_list[i].lawyer_number+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].student_name+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].student_email+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].presence_passport+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].sub_auth_ethic_training+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].bar_association+'</td>';
					list_tr += '</tr>';
					$(".list").append(list_tr);
				}
			}else{
				var max_c = change_list.length - 1;
				for( i=max_c; i > -1; i--) {
					// 行数カウント
					record_count = record_count + 1;
					
					// tr class の指定（偶数行のみ）
					var tr_class = '';
					if(record_count % 2 == 0){
						var tr_class = 'line_color';
					}
								
					list_tr  = "";
					list_tr += '<tr class="'+tr_class+'">';
					list_tr += '<td class="tdc">'+change_list[i].lawyer_number+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].student_name+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].student_email+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].presence_passport+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].sub_auth_ethic_training+'</td>';
					list_tr += '<td class="tdc">'+change_list[i].bar_association+'</td>';
					list_tr += '</tr>';

					$(".list").append(list_tr);
				}
			}
			$(".list").append('<tr><th colspan="7" style="height: 30px;background: none repeat scroll 0 0 #72726E;color: #FEFEFE;"></th></tr>');
		}


	// --></script> 
	
	<style type="text/css">
	<!--
		.line_color{
			background-color	: #F6F6F3
		}
		
		.sub_auth_on{
			display			: inline-block;
			margin			: 0 30px 0 15px;
			text-decoration	: none;
			font-weight		: bold;
			color			: white;
			text-align		: center;
			vertical-align	: middle;
			width			: 130px;
			height			: 30px;
			background		: url('/static/image/btn_red2.png') no-repeat;
			font-size		: 14px;
			line-height		: 30px;
			background-size	: 130px 30px;
		}
		.sub_auth_on:hover{
			color			: white;
		}

		.sub_auth_off{
			display			: inline-block;
			margin			: 0 15px 0 30px;
			text-decoration	: none;
			font-weight		: bold;
			color			: white;
			text-align		: center;
			vertical-align	: middle;
			width			: 130px;
			height			: 30px;
			background		: url('/static/image/btn_blue2.png') no-repeat;
			font-size		: 14px;
			line-height		: 30px;
			background-size	: 130px 30px;
		}
		.sub_auth_off:hover{
			color			: white;
		}

		.upload_msg{
			background-color	: #F6F6F3;
			border				: 1px solid #72726E;
			font-size			: 13px;
			line-height			: 30px;
			text-align			: center;
			
			display				: none;	
		}
		.upload_result{
			font-size			: 13px;
			line-height			: 45px;
			text-align			: center;
			
			display				: none;
		}

		.upload_list{
			display				: none;
		}
		.upload_list tr:hover{
			background-color	: #00A4E2;
			color				: #FFFFFF;
		}
	
		.order_by_link{
			text-decoration: none;
			color:#FFFFFF;
		}
		.order_by_link hove{
			color:#00A4E2;
		}
		.order_by_link:hover{
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
				
				<h2><?= $this->lang->line_or_def('msg_','csvをアップロードして登録します') ?></h2>
				
				<table class="form">
					<tr>
						<th style="height: 48px;line-height: 48px;">
							<?= $this->lang->line_or_def('common_file','ファイル') ?>
						</th>
						<td>
							<input type="file" onchange="setFilePath(this)" id="uploadfile" name="uploadfile" size="30" value="">
						</td>
					</tr>
					
				</table>
				
				<div id="select_button" style="height: 50px;line-height: 50px;text-align: center;">
					<a class="sub_auth_on"  onclick="csv_upload('ON');return false;" href="#">一括権限付与</a>
					<a class="sub_auth_off" onclick="csv_upload('OFF');return false;" href="#">一括権限剥奪</a>
					
				</div>
				<br/>
				
				<? // 処理結果の表示 ?>
				<div class="upload_msg">取り込み結果</div>
				<div class="upload_result">全20名に代替倫理研修の権限を付与（剥奪）しました。</div>
				<div class="upload_msg">付与詳細</div>
				
				<table class="list upload_list">
					<tr>
						<th style="width:96px;"><? //76px ?>
							<?= $this->lang->line_or_def('common_','登録番号') ?> <a href="#" onClick="list_sort('ASC');return false;" class="order_by_link">▲</a> <a href="#" onClick="list_sort('DESC');return false;" class="order_by_link">▼</a>
						</th>
						<th style=""><?= $this->lang->line_or_def('common_','氏名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','FP') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','代替権限') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
					</tr>
				</table>
				
			</div>
			<div class="clear"></div>
		</div>
	</div>













	<?php $this->load->view('header/body_footer');?>
</body>
</html>
