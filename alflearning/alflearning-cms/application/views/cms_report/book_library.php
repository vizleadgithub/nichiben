<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$this->load->view('header/header', array(
		'callview'	=> 'report',
	));?>
	<style type="text/css">
		#contents_main LI{
			float		: left;
			margin		: 5px 10px;
		}
	</style>
</head>

<body>
	<? $this->load->view('header/body_header', array()); ?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_report','レポート') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_report_comment','月毎の集計レポートを表示します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_report/_submenu', array(
				'selected'	=> 'cms_book_library',
			)); ?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_report_month_select','表示する月を選択してください') ?></h2>
				<ul>
					<? foreach($monthList as $month): ?>
						<li><? if($month == $selectMonth): ?><?= $month; ?><? else: ?><a href="/cms_report/cms_book_library/<?= $month; ?>"><?= $month; ?></a><? endif; ?></li>
					<? endforeach; ?>
				</ul>
				<div class="clear"></div>

				<? if($selectMonth == date('Y-m')): ?>
					<?
						$this->load->model('modelschoolcontract');
						$contractParam = $this->modelschoolcontract->getContractParam(array(
							'serviceKey'	=> 'book_library',
						));
					?>
					<h3><?= $this->lang->line_or_def('msg_report_this_month','今月分') ?>&nbsp;<?= date('[Y年m月d日 H時i分s秒更新]', $contractParam['updated_at']); ?></h3>
					<table class="list">
						<tr>
							<th width="140">対象</th>
							<th>契約</th>
							<th>契約内容</th>
							<th>使用状況</th>
							<th>残り</th>
						</tr>
						<tr>
							<th>転送量（バイト）</th>
							<td><?= ($contractParam['contract'] == 'fixation' ? '月額課金' : 'その他'); ?></td>
							<td><?= ConvertUnit($contractParam['stream'], 2); ?></td>
							<td><?= ConvertUnit($contractParam['stream_now'], 2); ?></td>
							<? if($contractParam['stream'] - $contractParam['stream_now'] < 0): ?>
								<td style="color:red;font-weight:bold;">オーバーしています</td>
							<? else: ?>
								<td><?= ConvertUnit($contractParam['stream'] - $contractParam['stream_now'], 2); ?></td>
							<? endif; ?>
						</tr>
						<tr>
							<th>ストレージ使用量（バイト）</th>
							<td><?= ($contractParam['contract'] == 'fixation' ? '月額課金' : 'その他'); ?></td>
							<td><?= ConvertUnit($contractParam['strage'], 2); ?></td>
							<td><?= ConvertUnit($contractParam['strage_now'], 2); ?></td>
							<? if($contractParam['strage'] - $contractParam['strage_now'] < 0): ?>
								<td style="color:red;font-weight:bold;">オーバーしています</td>
							<? else: ?>
								<td><?= ConvertUnit($contractParam['strage'] - $contractParam['strage_now'], 2); ?></td>
							<? endif; ?>
						</tr>
					</table>
				<? endif; ?>

				<table class="list">
					<tr>
						<th><?= $this->lang->line_or_def('msg_report_year_month','年月') ?></th>
						<th><?= $this->lang->line_or_def('msg_report_total_traffic','転送量') ?></th>
						<th><?= $this->lang->line_or_def('msg_report_total_storage','ストレージ使用量') ?></th>
					</tr>
					<? foreach($reports as $date => $report): ?>
						<tr>
							<td><?= $date; ?></td>
							<td><?= (isset($report['stream']) ? ConvertUnit($report['stream'], 2) : '-'); ?></td>
							<td><?= (isset($report['strage']) ? ConvertUnit($report['strage'], 2) : '-'); ?></td>
						</tr>
					<? endforeach; ?>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
