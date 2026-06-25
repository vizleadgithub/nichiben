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
			width		: 65px;
			text-align	: center;
			height		: 20px;
			line-height	: 20px;
		}
		#contents_main LI SPAN{
			vertical-align	: middle;
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
				'selected'	=> 'cms_class',
			)); ?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_report_month_select','表示する月を選択してください') ?></h2>
				<ul>
					<? foreach($monthList as $month): ?>
						<li><? if($month == $selectMonth): ?><span><?= htmlspecialchars( $month, ENT_QUOTES, 'UTF-8') ?></span><? else: ?><a href="/cms_report/cms_class/<?= htmlspecialchars( $month, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $month, ENT_QUOTES, 'UTF-8') ?></a><? endif; ?></li>
					<? endforeach; ?>
				</ul>
				<div class="clear"></div>

				<? if($thisMonth): ?>
					<?
						$this->load->model('modelschoolcontract');
						$contractParam = $this->modelschoolcontract->getContractParam(array(
							'serviceKey'	=> 'live',
						));
					?>
					<h3>
						<? if($contractParam['updated_at']): ?>	
							<?= $this->lang->line_or_def('msg_report_this_month','今月分') ?>&nbsp;<?= date('[Y年m月d日 H時i分s秒更新]', $contractParam['updated_at']); ?>
						<? else: ?>
							ご契約されていません
						<? endif; ?>
					</h3>
					<table class="list">
						<tr>
							<th width="140">対象</th>
							<th>契約</th>
							<th>契約内容</th>
							<th>使用状況</th>
							<th>残り</th>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_class_unit_time','総授業時間') ?></th>
							<td><?= ($contractParam['contract'] == 'fixation' ? '月額課金' : 'その他'); ?></td>
							<td><?= Sec2Disp($contractParam['time'], array('dd' => false, 'mm' => false, 'ss' => false)); ?></td>
							<td><?= Sec2Disp($thisMonth['total']['time'], array('dd' => false)); ?></td>
							<? if($contractParam['time'] - $thisMonth['total']['time'] < 0): ?>
								<td style="color:red;font-weight:bold;">オーバーしています</td>
							<? else: ?>
								<td><?= Sec2Disp($contractParam['time'] - $thisMonth['total']['time'], array('dd' => false)); ?></td>
							<? endif; ?>
						</tr>
						<tr>
							<th>ストレージ使用量（バイト）</th>
							<td><?= ($contractParam['contract'] == 'fixation' ? '月額課金' : 'その他'); ?></td>
							<td><?= ConvertUnit($contractParam['strage'], 2); ?></td>
							<td><?= ConvertUnit($thisMonth['total']['strage'], 2); ?></td>
							<? if($contractParam['strage'] - $thisMonth['total']['strage'] < 0): ?>
								<td style="color:red;font-weight:bold;">オーバーしています</td>
							<? else: ?>
								<td><?= ConvertUnit($contractParam['strage'] - $thisMonth['total']['strage'], 2); ?></td>
							<? endif; ?>
						</tr>
					</table>
					<table class="list">
						<tr>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_id','ID') ?></th>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_name','授業名') ?></th>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_open','授業開始時間') ?></th>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_close','授業時間') ?></th>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_student','受講生人数') ?></th>
							<th><?= $this->lang->line_or_def('msg_report_contract_class_time','授業時間計') ?></th>
						</tr>
						<? foreach($thisMonth['classList'] as $_thisMonth): ?>
							<tr>
								<td><?= htmlspecialchars( $_thisMonth['class_id'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $_thisMonth['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= date("Y-m-d H:i", strtotime($_thisMonth['class_open'])); ?></td>
								<td><?= Sec2Disp((strtotime($_thisMonth['class_close']) - strtotime($_thisMonth['class_open'])), array('dd' => false)); ?></td>
								<td><?= htmlspecialchars( $_thisMonth['student_num'], ENT_QUOTES, 'UTF-8') ?>人</td>
								<td><?= Sec2Disp((strtotime($_thisMonth['class_close']) - strtotime($_thisMonth['class_open'])) * $_thisMonth['student_num'], array('dd' => false)); ?></td>
							</tr>
						<? endforeach; ?>
					</table>
				<? endif; ?>

				<h3><?= $this->lang->line_or_def('msg_report_this_month_history','履歴一覧') ?></h3>
				<table class="list">
					<tr>
						<th><?= $this->lang->line_or_def('common_yearmonth','月日') ?></th>
						<th><?= $this->lang->line_or_def('common_class_unit_num','総授業数') ?></th>
						<th><?= $this->lang->line_or_def('common_student_unit_num','総生徒数') ?></th>
						<th><?= $this->lang->line_or_def('common_class_unit_time','総授業時間') ?></th>
						<th><?= $this->lang->line_or_def('common_class_strage','ストレージ使用量') ?></th>
					</tr>
					<? foreach($reports['reportList'] as $year_month => $report): ?>
						<tr>
							<td><?= htmlspecialchars( $year_month, ENT_QUOTES, 'UTF-8') ?></td>
							<td><?= htmlspecialchars( (isset($report['class']) && $report['class'] ? $report['class'] : '-'), ENT_QUOTES, 'UTF-8') ?></td>
							<td><?= htmlspecialchars( (isset($report['student']) && $report['student'] ? $report['student'] : '-'), ENT_QUOTES, 'UTF-8') ?></td>
							<td><?= (isset($report['time']) && $report['time'] ? Sec2Disp($report['time'], array('dd' => false)) : '-'); ?></td>
							<td><?= (isset($report['strage']) && $report['strage'] ? ConvertUnit($report['strage'], 2) : '-'); ?></td>
						</tr>
					<? endforeach; ?>
					<tr>
						<th><?= $this->lang->line_or_def('msg_report_total','合計') ?></th>
						<th><?= htmlspecialchars( (isset($reports['total']['class']) && $reports['total']['class'] ? $reports['total']['class'] : '-'), ENT_QUOTES, 'UTF-8') ?></th>
						<th><?= htmlspecialchars( (isset($reports['total']['student']) && $reports['total']['student'] ? $reports['total']['student'] : '-'), ENT_QUOTES, 'UTF-8') ?></th>
						<th><?= (isset($reports['total']['time']) && $reports['total']['time'] ? Sec2Disp($reports['total']['time'], array('dd' => false)) : '-'); ?></th>
						<th>-</th>
					</tr>
				</table>

			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
