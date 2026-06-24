<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
</head>

<body class="<?= getenv('URL_SERVICE'); ?>">
	<div id="wrapper" style="width:100%;">
		<div id="main" style="width:100%;">
			<div id="contents_main" style="width: auto;min-width: 1280px;">
				<div class="toolbar clearfix">
				</div>
				<div id="list" style="overflow: auto;">
					<table class="list" style="width:100%;">
<?php
print("\n<!--[\n");
var_dump($export_data);
print("\n]-->\n");
?>

<script type="text/javascript">
	const variables = {};
</script>

						<?php if(count($export_data)>0) { ?>
							<tr>
								<th style="min-width:10px;"></th>
								<th style="width:;">公開するレビュー内容</th>
								<th style="min-width:100px;">登録番号</th>
								<!--
								<th style="width:;">氏名</th>
								<th style="width:;">登録番号</th>
								<th style="width:;">会員区分</th>
								<th style="width:;">所属弁護士会</th>
								-->
								<?php
								for($i2=0;$i2<count($export_data[0]["exam2_problem"]);$i2++){
								?>
									<th style="width:;"><?php print( $export_data[0]["exam2_problem"][$i2]["exam2_problem_name"] ); ?></th>
								<?php
								}
								?>
							</tr>
						<?php } ?>

						<tr id="error_message">
							<td colspan="13"></td>
						</tr>

						<form name="set_answer_form" id="set_answer_form">
						<input type="hidden" name="exam2_id" value="<?php print( $exam2_id ); ?>">
						<input type="hidden" name="product_id" value="<?php print( $product_id ); ?>">
						<?php
						$line=0;
						if( isset($export_data[0]["student"]) ) {
							foreach( $export_data[0]["student"] as $student ) {
								 ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td style="width:;">
										<?php
										$checked = 0;
										for($i2=0;$i2<count($student["answer"]);$i2++){
											if( isset($student['answer'][$i2]["open_review_max"]) ){
												if( $student['answer'][$i2]["open_review_max"]==1 ){
													$checked = 1;
												}
											}
										}
										?>
										<input type="checkbox" name="student_id[]" id="student_id_<?php print($student["student_id"]); ?>" value="<?php print($student["student_id"]); ?>" <?php if($checked == 1){ print("checked"); } ?> >
									</td>
									<td style="width:;">
										<?php if( isset($student["info"]) ) { ?>
											<?php if( isset($student["info"][0]) ) { ?>
												<textarea id="exam2_answer_review_contents_<?= $student['info'][0]['student_id']; ?>" name="exam2_answer_review_contents_<?= $student['info'][0]['student_id']; ?>"><?php if(isset($export_data_review[$student['info'][0]['student_id']])){ ?><?= $export_data_review[$student['info'][0]['student_id']] ?><?php } ?></textarea>
												<input type="button" id="btn_exam2_answer_review_contents_<?= $student['info'][0]['student_id']; ?>" name="btn_exam2_answer_review_contents_<?= $student['info'][0]['student_id']; ?>" onclick="update_exam2_answer_review_contents('<?= $student['info'][0]['student_id']; ?>')" value="更新">
											<?php } ?>
										<?php } ?>
									</td>
									<td style="width:;"><?php if( isset($student["info"]) ) { ?><?php if( isset($student["info"][0]) ) { ?><?= $student["info"][0]['lawyer_number']; ?><?php } ?><?php } ?></td>
									<?php
									for($i2=0;$i2<count($student["answer"]);$i2++){
										if( isset($export_data[0]["exam2_problem"][$i2]) ){
									?>
											<td>
											<?php
											//解答種類 1:単一形式、2:複数形式、3:フリー回答
											if( $export_data[0]["exam2_problem"][$i2]["answer_kind"] == 1 ){
												print(  "<!--[answer_kind:".$export_data[0]["exam2_problem"][$i2]["answer_kind"]."]-->"  );
												$arr_temp = json_decode( $export_data[0]["exam2_problem"][$i2]["answer_contents"],true );
												print(  "<!--["  );
												var_dump($arr_temp);
												print(  "]-->"  );
												if( isset($arr_temp->answer_contents) && !empty($arr_temp->answer_contents) ){
													for($i3=0;$i3<count($arr_temp->answer_contents);$i3++){
														$temp = $arr_temp->answer_contents[$i3];
														if( $temp->no ==$student['answer'][$i2]["exam2_answer_contents"] ){
															print(  "・".$temp->word  );
														}
													}
												}
											} elseif( $export_data[0]["exam2_problem"][$i2]["answer_kind"] == 2 ){
												print(  "<!--[".$export_data[0]["exam2_problem"][$i2]["answer_kind"]."]-->"  );
												$arr_answer = explode(",",$student['answer'][$i2]["exam2_answer_contents"]);
												print(  "<!--["  );
												var_dump($arr_temp);
												print(  "]-->"  );
												$arr_temp = json_decode( $export_data[0]["exam2_problem"][$i2]["answer_contents"],true );

												for($i4=0;$i4<count($arr_answer);$i4++){
													if( isset($arr_temp->answer_contents) && !empty($arr_temp->answer_contents) ){
														for($i3=0;$i3<count($arr_temp->answer_contents);$i3++){
															$temp = $arr_temp->answer_contents[$i3];
															if( $temp->no == $arr_answer[$i4] ){
																print(  "・".$temp->word."<br>"  );
															}
														}
													}
												}

												//$arr_temp = json_decode( $export_data[0]["exam2_problem"][$i2]["answer_contents"],true );
												//print(  $student['answer'][$i2]["exam2_answer_contents"]  );
											} elseif( $export_data[0]["exam2_problem"][$i2]["answer_kind"] == 3 ){
												print(  "<!--[".$export_data[0]["exam2_problem"][$i2]["answer_kind"]."]-->"  );
												$arr_temp = json_decode( $export_data[0]["exam2_problem"][$i2]["answer_contents"] );
												print(  "<!--["  );
												var_dump($arr_temp);
												print(  "]-->"  );
												print(  '<textarea id="exam2_answer_'.$student['answer'][$i2]["exam2_answer_id"].'">'  );
												print(  $student['answer'][$i2]["exam2_answer_contents"]  );
												if($student['answer'][$i2]["exam2_answer_contents_old"] != ""){
													print(  "\r\n\r\n".$student['answer'][$i2]["exam2_answer_contents_old"]  );
												}
												print(  '</textarea>'  );
												print(  '<input type="button" name="btn_exam2_answer_'.$student['answer'][$i2]["exam2_answer_id"].'" onclick="update_exam2_answer_problem('.$student['answer'][$i2]["exam2_answer_id"].', variables['.$student['answer'][$i2]["exam2_answer_id"].'])" value="更新">'  );
											}
											?>

											<script type="text/javascript">
												variables['<?php print( $student['answer'][$i2]["exam2_answer_id"] ); ?>'] = <?php print( $student['answer'][$i2]["update_count"] ); ?>;
											</script>


											</td>
									<?php
										}
									}
									?>
								</tr>
								<?php 
							}
						}
						 ?>
						<tr>
							<th class="pager" colspan="5"><?php //=$pagination ?></th>
						</tr>
					</table>
				</div>
				<div class="toolbar clearfix">
					<a href="javascript:void(0);" onclick="set_answer_list()" id="exam2_btn">レビューを設定</a>
<script type="text/javascript">
function update_exam2_answer_review_contents(student_id){
	var hostUrl= '/cms_exam2/update_exam2_answer_review_contents';
	var param1 = student_id;
	var param2 = <?= $exam2_id ?>;
	var param3 = <?= $product_id ?>;
	var param4 = $("#exam2_answer_review_contents_"+student_id).val();
	$.ajax({
		url: hostUrl,
		type:'POST',
		dataType: 'json',
		data : {student_id : param1, exam2_id : param2, product_id : param3, exam2_answer_review_contents : param4 },
		timeout:3000,
	}).done(function(data) {
		alert("公開するレビュー内容を更新しました");
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		alert("公開するレビュー内容の更新に失敗しました");
	})
}
function update_exam2_answer_problem(exam2_answer_id, update_count){
	//alert("[" + exam2_answer_id + ":" + update_count + "]");
	var hostUrl= '/cms_exam2/update_exam2_answer_problem';
	var param1 = exam2_answer_id;
	var param2 = $("#exam2_answer_"+exam2_answer_id).val();
	var param3 = update_count;
	$.ajax({
		url: hostUrl,
		type:'POST',
		dataType: 'json',
		data : {exam2_answer_id : param1, exam2_answer_value : param2, update_count : param3 },
		timeout:3000,
	}).done(function(data) {
		if(data['msg']=='update_count_error'){
			alert("受講者側で修正登録されました。\n画面を一度閉じて、再度登録処理をしてください。");
			variables[exam2_answer_id+''] = data['count'];
		}else{
			
			alert("解答内容を更新しました");
			variables[exam2_answer_id+''] = data['count'];
		}
	}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
		alert("解答内容の更新に失敗しました");
	})
	console.log(variables);
}
function set_answer_list(){
	// エラーチェック
	<?php if( isset($export_data[0]["student"]) ) { ?>
		<?php foreach( $export_data[0]["student"] as $student ) { ?>
			if($("#student_id_<?= $student['info'][0]['student_id']; ?>").prop("checked")){
				if($("#exam2_answer_review_contents_<?= $student['info'][0]['student_id']; ?>").val()==""){
					$("#error_message td").html("公開するレビュー内容が空欄です。");
					$("#error_message").show();
					location.href = "#error_message";
					return false;
				}
			}
		<?php } ?>
	<?php } ?>

	var $form = $("#set_answer_form");
	$.ajax({
		url: "/cms_exam2/exam2_set_answer",
		type: "post",
		data: $form.serialize(),
		timeout: 10000,  // 単位はミリ秒
		// 送信前
		//beforeSend: function(xhr, settings) {
		//	// ボタンを無効化し、二重送信を防止
		//	$button.attr('disabled', true);
		//},
		// 応答後
		//complete: function(xhr, textStatus) {
		//	// ボタンを有効化し、再送信を許可
		//	$button.attr('disabled', false);
		//},
		// 通信成功時の処理
		success: function(result, textStatus, xhr) {
			// 入力値を初期化
			//$form[0].reset();
			alert('レビューを設定しました');
			location.href = location.href.replace(/#.*/, "");
		},
		// 通信失敗時の処理
		error: function(xhr, textStatus, error) {
			//alert('NG...');
			location.href = location.href.replace(/#.*/, "");
		}
	});
}
</script>
<style type="text/css">
#exam2_btn {
	display: inline-block;
	line-height: 24px;
	height: 24px;
	color: #fff;
	background-color: #756B6B;
	text-decoration: none;
	width: 140px;
	text-align: center;
	border-radius: 4px;
	margin: 4px;
}

#error_message {
	display: none;
}
#error_message td {
	color: #ff0000;
	font-weight: bold;
}
table.list th {
	min-width: 200px;
	word-break: keep-all;
}
</style>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php /* $this->load->view('header/body_footer'); */ ?>
</body>
</html>
