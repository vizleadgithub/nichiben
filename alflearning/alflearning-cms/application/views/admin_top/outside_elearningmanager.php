<?php
	$this->lang->load('common');
	$this->lang->load('msg');
	$this->lang->load('error');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		#contents_main UL.list{
			min-height	: 200px;
		}

		/* [2012/10/05]サブメニュー用 */
		#wrapper #main #menu_sub UL .sub {
			background:center transparent;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		#wrapper #main #menu_sub UL .selected_sub {
			background:center white;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		
		#information_table{
			width: 100%;
		}
		#information_table .information_title{
			text-align: right;
			height: 25px;
			line-height: 25px;
			width: 10%;
		}
		#information_table .information_detail{
			text-align: left;
			width: 90%;
		}
	}
	
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_outside_corporation','外部連携') ?></div>
			<div class="comment">eLearning Manager</div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'		=> 'outside',
				'select_tag'	=> 'elearningmanager',
			));?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_outside_corporation_caption_elm','eLearning Managerの設定が可能です（要eLearning Manager契約）') ?></h2>
				<? if($error==1){ print '<div class="error">'.$this->lang->line_or_def('error_apiurl_not_url_form','Api URLがURL形式ではありません').'</div>'; } ?>
				<? if($error==2){ print '<div class="error">'.$error_msg.'</div>'; } ?>
				<? if($error==3){ print '<div class="error">'.$this->lang->line_or_def('error_apiurl_overlap','Api URLが他学校と同一のため登録できません').'</div>'; } ?>
			
				<?=form_open_multipart("/admin_top/outside_elearningmanager/")?>
					<table class="form">
						<tr></tr>
						<tr>
							<th width="100px">Api Key</th>
							<td><input type="text" name="api_key" size="50" value="<?=set_value('api_key',$api_key)?>"></td>
						</tr>
						<tr>
							<th width="100px">Api URL</th>
							<td><input type="text" name="api_url" size="50" value="<?=set_value('api_url',$api_url)?>">
							<div style="margin-top: 5px;">※入力例：https://*****.elmstarz.com/ , http://*****.gingerapp.co.jp/</div></td>
						</tr>
					</table>
					
					<div class="error" style="margin-left: 100px; margin-top: 10px;">
						<?= $this->lang->line_or_def('msg_outside_corporation_attention_elm','注意：既に登録されている講座・受講者については、eLearning Managerへの自動同期は行われません') ?>
					</div>
					
					<div class="submit">
						<input type='image' src='/static/image/btn_register.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
