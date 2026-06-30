<?php
/**
 * 受講中の講座
 */
?>
<?php
$objDbConnect = new DbConnect();

// 初期化
$limit= 3; // 表示最大件数
$arr_viewd_video = array();       // ビデオ情報
$arr_rel_video_product = array(); // ビデオに紐付く商品情報
$student_id = $_SESSION['user']['id'];

// 受講した動画のvideo_idを取得
$sql = "
SELECT
  video_id
FROM
  report_user_video_viewed
WHERE
  student_id = '$student_id'
  AND percent >= 1
";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	$in_video_id = '';
	foreach ($res as $val){
		// where文用文字列の作成
		$in_video_id.= $val['video_id'].',';
	}
	
	if ($in_video_id != ''){
		$in_video_id = rtrim($in_video_id, ',');
		
		// 受講中のビデオが設定されている商品をベースに取得
		$where = "
		WHERE
		  TP.del_flg = 0
		  AND (
		    TP.contents_contents1 IN($in_video_id)
		    OR TP.contents_contents2 IN($in_video_id)
		    OR TP.contents_contents3 IN($in_video_id)
		    OR TP.contents_contents4 IN($in_video_id)
		    OR TP.contents_contents5 IN($in_video_id)
		    OR TP.contents_contents6 IN($in_video_id)
		    OR TP.contents_contents7 IN($in_video_id)
		    OR TP.contents_contents8 IN($in_video_id)
		    OR TP.contents_contents9 IN($in_video_id)
		    OR TP.contents_contents10 IN($in_video_id)
		    OR TP.contents_contents11 IN($in_video_id)
		    OR TP.contents_contents12 IN($in_video_id)
		    OR TP.contents_contents13 IN($in_video_id)
		    OR TP.contents_contents14 IN($in_video_id)
		    OR TP.contents_contents15 IN($in_video_id)
		    OR TP.contents_contents16 IN($in_video_id)
		    OR TP.contents_contents17 IN($in_video_id)
		    OR TP.contents_contents18 IN($in_video_id)
		    OR TP.contents_contents19 IN($in_video_id)
		    OR TP.contents_contents20 IN($in_video_id)
		    OR TP.contents_contents21 IN($in_video_id)
		    OR TP.contents_contents22 IN($in_video_id)
		    OR TP.contents_contents23 IN($in_video_id)
		    OR TP.contents_contents24 IN($in_video_id)
		    OR TP.contents_contents25 IN($in_video_id)
		  )
		  AND TPA.product_type_add = '1'
		 ";
		
		$sql = "SELECT";
		for($i=1; $i<=MAX_CONTENTS; $i++){
			$sql.= "   TP.contents_contents$i,";
		}
		$sql.= "   TP.product_id,";
		$sql.= "   TP.product_name,";
		$sql.= "   TP.start_date,";
		$sql.= "   TP.end_date,";
		$sql.= "   TP.thumbnail,";
		$sql.= "   TP.contents_thumbnail1,";
		$sql.= "   ( SELECT MAX(reading_date) FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id IN( 
				TP.contents_contents1,
				TP.contents_contents2,
				TP.contents_contents3,
				TP.contents_contents4,
				TP.contents_contents5,
				TP.contents_contents6,
				TP.contents_contents7,
				TP.contents_contents8,
				TP.contents_contents9,
				TP.contents_contents10,
				TP.contents_contents11,
				TP.contents_contents12,
				TP.contents_contents13,
				TP.contents_contents14,
				TP.contents_contents15,
				TP.contents_contents16,
				TP.contents_contents17,
				TP.contents_contents18,
				TP.contents_contents19,
				TP.contents_contents20,
				TP.contents_contents21,
				TP.contents_contents22,
				TP.contents_contents23,
				TP.contents_contents24,
				TP.contents_contents25 
						 ) AND percent >= 1 AND percent <= 99 ) AS TSUB_reading_date";
		$sql.= " FROM";
		$sql.= "   tbl_product AS TP";
		$sql.= "     LEFT JOIN";
		$sql.= "   tbl_product_add AS TPA";
		$sql.= "       ON TP.product_id = TPA.product_id";
		
		$order = " ORDER BY TSUB_reading_date DESC ";
		
		//$offset = " LIMIT $limit";
		
		$res = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
		
		if ($res){
			foreach ($res as $val){
				$video_id = $val['contents_contents1'];
				
				$res_alfstream_duration = false; // 総再生時間取得用
				$res_duration_reading   = false; // 視聴済み時間取得用
				$all_alfstream_duration = '00:00:00'; // 総再生時間計算用
				$all_duration_reading   = '00:00:00'; // 総視聴済み時間計算用
				$prev_percent = 0; // 最大閲覧率の最大値判断用
				$all_complete_flg = true;
				
				// サムネイル取得
				$arr_video_info = array();
				$arr_video_info = get_alf_thumbnail_db($video_id);
				
				$arr_rel_video_product[$video_id]["video_thumbnail"] = $video_id.$arr_video_info["contents_thumbnail"][0]["p180"];
				
				// 表示フラグ
				$arr_rel_video_product[$video_id]["disp_flg"] = false;
				
				// 商品に紐付いているビデオの各再生時間の計算
				for($i=1; $i<=MAX_CONTENTS; $i++){
					if ($val["contents_contents$i"] != ''){
						// 総再生時間
						$sql = "SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".$val["contents_contents$i"]."'";
						$res_alfstream_duration = $objDbConnect->query_fetch($sql);
						if ($res_alfstream_duration){
							$all_alfstream_duration = getTimeAddition($all_alfstream_duration, $res_alfstream_duration["alfstream_duration"]);
						}
						
						// 視聴済時間の総計
						if ($val["contents_contents$i"] != ''){
							$sql = "SELECT duration_reading, percent, complete_flag FROM report_user_video_viewed WHERE student_id = '$student_id' AND video_id = '".$val["contents_contents$i"]."'";
							$res_duration_reading = $objDbConnect->query_fetch($sql);
							if ($res_duration_reading){
								// 視聴時間
								$all_duration_reading = getTimeAddition($all_duration_reading, $res_duration_reading["duration_reading"]);
								// 最大閲覧率
								//if ($res_duration_reading["complete_flag"]!=1 && $res_duration_reading["percent"]>$prev_percent){
								//	$arr_rel_video_product[$video_id]["disp_flg"] = true;
								//	
								//	$arr_rel_video_product[$video_id]["max_percent"] = $res_duration_reading["percent"];
								//	$prev_percent = $res_duration_reading["percent"];
								//}
							}
						}
						
						// 全ての講座を見たか
						if ($all_complete_flg){
							$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$student_id."' AND video_id = '".$val["contents_contents$i"]."' AND complete_flag = '1'";
							$res_count = $objDbConnect->query_fetch($sql);
							if ($res_count['c']==0){
								$all_complete_flg = false;
							}
						}
						
					} else {
						break;
					}
				}
				
				// 完了済みのデータの場合削除し、ループ続行
				if ($all_complete_flg){
					unset($arr_rel_video_product[$video_id]);
					continue;
				}
				
				$arr_rel_video_product[$video_id]["all_remaining"] = getTimeSubtraction($all_alfstream_duration, $all_duration_reading);
				
				// 受講率計算
				if ($all_complete_flg){
					$arr_rel_video_product[$video_id]["max_percent"] = 100;
				} else {
					$arr_rel_video_product[$video_id]["max_percent"] = 0;
					
					$in_video_id = '';
					$ret_tbl_product = array();
					$sql = "SELECT ";
					for($k=1; $k<=MAX_CONTENTS; $k++){
						$sql.= " contents_contents$k,";
					}
					$sql = rtrim($sql, ',');
					$sql.= " FROM";
					$sql.= "   tbl_product";
					$sql.= " WHERE";
					$sql.= "   product_id = '".$val['product_id']."'";
					$ret_tbl_product = $objDbConnect->query_fetch_arr($sql);
					if ($ret_tbl_product){
						for($l=1; $l<=MAX_CONTENTS; $l++){
							if ($ret_tbl_product[0]["contents_contents$l"] != ''){
								$in_video_id.= $ret_tbl_product[0]["contents_contents$l"] . ',';
							}
						}
						$in_video_id = rtrim($in_video_id, ',');
						
						$ret_report_user_video_viewed = array();
						$sql = "
						SELECT
						  TIME_TO_SEC(duration) AS duration_sec,
						  TIME_TO_SEC(duration_reading) AS duration_reading_sec,
						  complete_flag
						FROM
						  report_user_video_viewed
						WHERE
						  student_id = '$student_id'
						  AND video_id IN($in_video_id)
						";
						$ret_report_user_video_viewed = $objDbConnect->query_fetch_arr($sql);
						if ($ret_report_user_video_viewed){
							// 動画を見終わっているかどうかで、視聴済み時間を変更
							// complete_flag=0：視聴済み時間を使用(duration_reading)
							// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
							$video_duration_reading = 0;
							foreach ($ret_report_user_video_viewed as $aruvv_val){
								if ($aruvv_val['complete_flag'] == '1'){
									$video_duration_reading += $aruvv_val['duration_sec'];
								} else {
									$video_duration_reading += $aruvv_val['duration_reading_sec'];
								}
							}
							
							$arr_all_alfstream_duration = array();
							$sql = "SELECT TIME_TO_SEC('$all_alfstream_duration') AS all_alfstream_duration_sec";
							$arr_all_alfstream_duration = $objDbConnect->query_fetch_arr($sql);
							if ($arr_all_alfstream_duration){
								$percent = $video_duration_reading / $arr_all_alfstream_duration[0]['all_alfstream_duration_sec'] * 100;
								if (!is_int($percent)){
									$arr_rel_video_product[$video_id]["max_percent"] = (int)round($percent);
									$arr_rel_video_product[$video_id]["disp_flg"] = true;
								}
							}
						}
					}
				}
				
				$arr_rel_video_product[$video_id]["product_id"] = $val["product_id"];
				$arr_rel_video_product[$video_id]["product_name"] = $val["product_name"];
				$arr_rel_video_product[$video_id]["start_date"] = $val["start_date"];
				$arr_rel_video_product[$video_id]["end_date"] = $val["end_date"];
				$arr_rel_video_product[$video_id]["thumbnail"] = $val["thumbnail"];
				$arr_rel_video_product[$video_id]["contents_thumbnail1"] = $val["contents_thumbnail1"];
				
				if (count($arr_rel_video_product) == $limit){
					break;
				}
			}
		}
	}
}

//var_dump($arr_rel_video_product);

$disp_count = 0;
foreach ($arr_rel_video_product as $val){
	if ($val['disp_flg']){
		$disp_count++;
	}
}
?>

















<div class="lecture">
<h3></h3>
<div class="lecture_main">
<?php if ($disp_count>0){ ?>
<table style="width:100%;border-top:1px #ededed solid;">
	<?php foreach ($arr_rel_video_product as $video_id => $val){ ?>
		<?php if ($val['disp_flg']){ ?>
		<tr>
			<td class="lecture_title" colspan="2"><h4><a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><?php echo $val['product_name']; ?></a></h4></td>
		</tr>
		<tr>
			<td style="text-align:center;">

			<div class="lecture_img">
			<?php if ($val['thumbnail'] != ''){ ?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_image.php?image=<?php echo $val['thumbnail']; ?>&width=100&height=100" alt="" /></a>
			<?php } else if ($val['contents_thumbnail1'] != ''){ ?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_image.php?image=<?php echo $val['contents_thumbnail1']; ?>&width=100&height=100" alt="" /></a>
			<?php } else if ($val['video_thumbnail'] != ''){ ?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_video_image.php?image=<?php echo $val['video_thumbnail']; ?>&width=100&height=100" alt="" /></a>
			<?php } else { ?>
				<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><img src="/resize_image.php?image=noimage.jpg&width=100&height=100" alt="" /></a>
			<?php } ?>
			</div>
			</td>
			<td style="text-align:center;vertical-align:middle;">
				<div class="lecture_status">受講<?php echo $val['max_percent']; ?>％</div>
				<div class="lecture_status">残り<?php echo $val['all_remaining']; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;padding-bottom:10px;border-bottom:1px #ededed solid;">
				掲載期間：<a href="/product/detail.php?pid=<?php echo $val['product_id']; ?>"><?php if($val['start_date']!=''){echo date('Y/m/d', strtotime($val['start_date'])).'～';}else{echo '未定';} ?></a>
			</td>
		</tr>
		<?php } ?>
	<?php } ?>
</table>
<div style="text-align:right;padding-top:5px;">
	<a href="/mypage/viewing.php"><img src="/img/list_btn01.png" alt="一覧へ" /></a>
</div>
<?php } else { ?>
	受講中の講座はありません。<br /><br />
<?php } ?>
<div style="text-align:left;">
※受講状況（受講率等）の表示は，<br />１日１回更新されます。
</div>
</div>
</div>

<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp_post = $posts;
$posts = get_posts('numberposts=1&category_name=free_html_top5');
if($posts): foreach($posts as $post): setup_postdata($post);
?>
<div style="width:230px;background:#ffffff;color:#000000;margin:0px;padding:0px;font-size:12px;">
<?php echo $post->post_content ?>
</div>
<?php 
endforeach; endif;
$posts=$tmp_post;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>