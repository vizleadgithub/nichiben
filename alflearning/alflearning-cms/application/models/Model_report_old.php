<?php
#[AllowDynamicProperties]
class Model_report extends CI_Model  
{
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	//private $_private    = '1';
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
		
		// load language
		$this->lang->load('common');
	}
	
	//----------------------------------------------
	// [日弁連]レポート-ユーザ、eラーニング
	//----------------------------------------------
	function get_user_elearning($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'  => 0,
				'offset'      => 0,
				'rowcount'    => 0,
			),
			$param
		);
		
		// 戻り値設定
		$result_data['show_record'] = array();
		$result_data['all_count']   = -1;
		
		try{
			// 視聴履歴があり購入済みの商品IDを取得
			$pid_list = "0";
			$sql = '';
			$sql = "
			SELECT 
			 group_concat( pl.product_id separator ',') as pid_list 
			FROM 
			 (
			   SELECT 
			    CAST( tbl_product.product_id AS CHAR) as product_id 
			   FROM 
			    report_user_video_viewed 
			    LEFT JOIN tbl_product ON 
			     ( 
			      tbl_product.contents_contents1 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
			      OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
			     )
			    LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
			   WHERE 
			    report_user_video_viewed.student_id=?
			    AND tbl_product_add.product_type_add = '1' 
			   GROUP BY 
			    tbl_product.product_id 
			   UNION 
			   SELECT 
			    CAST( tbl_order_detail.product_id AS CHAR) as product_id 
			   FROM 
			    tbl_order_detail 
			    LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id 
			   WHERE 
			    tbl_order_detail.member_id=?
			    AND tbl_order_detail.payment_status='2' 
			    AND tbl_product_add.product_type_add = '1' 
			 ) as pl
			";
			$query_record = $this->db->query($sql, 
					array(
						(int)$param['student_id'],
						(int)$param['student_id'],
					)
			);
			if ($query_record->num_rows() > 0){
				$result_array = $query_record->result_array();
				if ($result_array){
					$pid_list = $result_array[0]['pid_list'];
				}
			}
//var_dump($pid_list);
			
			// 表示レコード取得 - SQL生成
			$sql = '';
            $sql = "SELECT 
			 tbl_product.product_id, 
			 tbl_product.product_name,
			 tbl_product.contents_contents1,
			 tbl_product.contents_contents2,
			 tbl_product.contents_contents3,
			 tbl_product.contents_contents4,
			 tbl_product.contents_contents5,
			 tbl_product.contents_contents6,
			 tbl_product.contents_contents7,
			 tbl_product.contents_contents8,
			 tbl_product.contents_contents9,
			 tbl_product.contents_contents10,
			 tbl_product.contents_contents11,
			 tbl_product.contents_contents12,
			 tbl_product.contents_contents13,
			 tbl_product.contents_contents14,
			 tbl_product.contents_contents15,
			 tbl_product.contents_contents16,
			 tbl_product.contents_contents17,
			 tbl_product.contents_contents18,
			 tbl_product.contents_contents19,
			 tbl_product.contents_contents20,
			 tbl_product.contents_contents21,
			 tbl_product.contents_contents22,
			 tbl_product.contents_contents23,
			 tbl_product.contents_contents24,
			 tbl_product.contents_contents25,
			 (
			  SELECT 
			   MAX( report_user_video_viewed.reading_date ) AS max_reading_date 
			  FROM 
			   report_user_video_viewed 
			  WHERE 
			   report_user_video_viewed.student_id=?
			   AND ( 
			    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
			   )
			 ) AS max_reading_date,

 /* ------------------------------------- */
 (
  SELECT
   SUM(TIME_TO_SEC(video_alfstream_status.alfstream_duration)) AS all_alfstream_duration
  FROM
   video_alfstream_status
  WHERE
   (
    tbl_product.contents_contents1 = video_alfstream_status.video_id
    OR tbl_product.contents_contents2 = video_alfstream_status.video_id
    OR tbl_product.contents_contents3 = video_alfstream_status.video_id
    OR tbl_product.contents_contents4 = video_alfstream_status.video_id
    OR tbl_product.contents_contents5 = video_alfstream_status.video_id
    OR tbl_product.contents_contents6 = video_alfstream_status.video_id
    OR tbl_product.contents_contents7 = video_alfstream_status.video_id
    OR tbl_product.contents_contents8 = video_alfstream_status.video_id
    OR tbl_product.contents_contents9 = video_alfstream_status.video_id
    OR tbl_product.contents_contents10 = video_alfstream_status.video_id
    OR tbl_product.contents_contents11 = video_alfstream_status.video_id
    OR tbl_product.contents_contents12 = video_alfstream_status.video_id
    OR tbl_product.contents_contents13 = video_alfstream_status.video_id
    OR tbl_product.contents_contents14 = video_alfstream_status.video_id
    OR tbl_product.contents_contents15 = video_alfstream_status.video_id
    OR tbl_product.contents_contents16 = video_alfstream_status.video_id
    OR tbl_product.contents_contents17 = video_alfstream_status.video_id
    OR tbl_product.contents_contents18 = video_alfstream_status.video_id
    OR tbl_product.contents_contents19 = video_alfstream_status.video_id
    OR tbl_product.contents_contents20 = video_alfstream_status.video_id
    OR tbl_product.contents_contents21 = video_alfstream_status.video_id
    OR tbl_product.contents_contents22 = video_alfstream_status.video_id
    OR tbl_product.contents_contents23 = video_alfstream_status.video_id
    OR tbl_product.contents_contents24 = video_alfstream_status.video_id
    OR tbl_product.contents_contents25 = video_alfstream_status.video_id
   )
 ) AS all_alfstream_duration_sec, /* 総時間(秒) */
 /* ------------------------------------- */
 (
  SELECT
   SUM(TIME_TO_SEC(report_user_video_viewed.duration_reading)) AS all_duration_reading
  FROM
   report_user_video_viewed
  WHERE
   report_user_video_viewed.student_id=? 
   AND (
    tbl_product.contents_contents1 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents20= report_user_video_viewed.video_id
    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id
    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id
   )
 ) AS all_duration_reading, /* 視聴済時間(秒) */
 /* ------------------------------------- */







			 (
			  SELECT
			   SEC_TO_TIME(SUM(TIME_TO_SEC(report_user_video_viewed.duration_reading))) AS all_duration_reading
			  FROM
			   report_user_video_viewed
			  WHERE
			   report_user_video_viewed.student_id=?
			   AND (
			    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
			   )
			 ) AS all_duration_reading,
			 (
			  SELECT
			   SUM(TIME_TO_SEC(report_user_video_viewed.duration_reading)) AS all_duration_reading
			  FROM
			   report_user_video_viewed
			  WHERE
			   report_user_video_viewed.student_id=?
			   AND (
			    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
			    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
			   )
			 ) AS all_duration_reading,
			 ( 
			  SELECT 
			   MAX(tbl_order.web_flg)
			  FROM 
			   tbl_order_detail 
			   LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id 
			  WHERE 
			   tbl_order_detail.member_id=?
			   AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 )
			   AND tbl_order_detail.product_id=tbl_product.product_id 
			 ) AS web_flg
			FROM 
			 tbl_product 
			 LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
			WHERE 
			 tbl_product.product_id IN (".rtrim($pid_list,",").")
			 AND tbl_product_add.product_type_add = '1' 
			";
			
			if( trim($pid_list)!="" ){
				$query_count = $this->db->query($sql, 
						array(
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							//$pid_list,
						)
				);
				$result_data['all_count'] = $query_count->num_rows();
				
				// 表示レコード取得 - SQL生成
				$sql_limit  = " LIMIT {$param['offset']}, {$param['rowcount']} ";
				$query_record = $this->db->query($sql.$sql_limit, 
						array(
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							(int)$param['student_id'],
							//$pid_list,
						)
				);
				if ($query_record->num_rows() > 0){
					//$result_data['show_record'] = $query_record->result_array();
					$tmp_result_data = $query_record->result_array();
					
					foreach ($tmp_result_data as $key => $val){
						$result_data['show_record'][$key] = $val;
						
						$product_video_count  = 0; // 商品に紐づいた動画数
						$complete_video_count = 0; // 動画視聴完了数
						$in_video_id = '';
						$all_duration_reading = 0;
						
						// 商品に紐づいた動画数の取得
						if ($val['contents_contents1']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents1'].',';}
						if ($val['contents_contents2']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents2'].',';}
						if ($val['contents_contents3']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents3'].',';}
						if ($val['contents_contents4']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents4'].',';}
						if ($val['contents_contents5']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents5'].',';}
						if ($val['contents_contents6']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents6'].',';}
						if ($val['contents_contents7']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents7'].',';}
						if ($val['contents_contents8']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents8'].',';}
						if ($val['contents_contents9']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents9'].',';}
						if ($val['contents_contents10']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents10'].',';}
						if ($val['contents_contents11']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents11'].',';}
						if ($val['contents_contents12']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents12'].',';}
						if ($val['contents_contents13']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents13'].',';}
						if ($val['contents_contents14']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents14'].',';}
						if ($val['contents_contents15']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents15'].',';}
						if ($val['contents_contents16']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents16'].',';}
						if ($val['contents_contents17']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents17'].',';}
						if ($val['contents_contents18']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents18'].',';}
						if ($val['contents_contents19']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents19'].',';}
						if ($val['contents_contents20']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents20'].',';}
						if ($val['contents_contents21']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents21'].',';}
						if ($val['contents_contents22']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents22'].',';}
						if ($val['contents_contents23']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents23'].',';}
						if ($val['contents_contents24']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents24'].',';}
						if ($val['contents_contents25']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents25'].',';}
						$result_data['show_record'][$key]['product_video_count'] = $product_video_count;
						
						// 動画視聴完了数の取得
						if ($in_video_id != ''){
							$query_record = array();
							$arr_report_user_video_viewed = array();
							$sql = "SELECT COUNT(*) AS complete_video_count FROM report_user_video_viewed WHERE student_id = ? AND video_id IN(" . rtrim($in_video_id, ',') . ") AND complete_flag = 1";
							$query_record = $this->db->query($sql, 
									array(
										(int)$param['student_id']
									)
							);
							if ($query_record->num_rows() > 0){
								$arr_report_user_video_viewed = $query_record->result_array();
								$complete_video_count = (int)$arr_report_user_video_viewed[0]['complete_video_count'];
							}
						}
						$result_data['show_record'][$key]['complete_video_count'] = $complete_video_count;
						
						// 全て動画を見終わっていない場合、視聴済み時間の更新
						// complete_flag=0：視聴済み時間を使用(duration_reading)
						// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
						if ($product_video_count != $complete_video_count){
							$result_data['show_record'][$key]['all_duration_reading'] = 0;
							
							$query_record = array();
							$arr_report_user_video_viewed = array();
							$sql = "SELECT TIME_TO_SEC(duration) AS duration_sec, TIME_TO_SEC(duration_reading) AS duration_reading_sec, complete_flag FROM report_user_video_viewed WHERE student_id = ? AND video_id IN(" . rtrim($in_video_id, ',') . ")";
							$query_record = $this->db->query($sql, 
									array(
										(int)$param['student_id']
									)
							);
							if ($query_record->num_rows() > 0){
								$arr_report_user_video_viewed = $query_record->result_array();
								foreach ($arr_report_user_video_viewed as $aruvv_val){
									if ($aruvv_val['complete_flag'] == '1'){
										$result_data['show_record'][$key]['all_duration_reading'] += $aruvv_val['duration_sec'];
									} else {
										$result_data['show_record'][$key]['all_duration_reading'] += $aruvv_val['duration_reading_sec'];
									}
								}
							}
						}
						
						// 設問付きeラーニングの場合
						$result_data['show_record'][$key]['product_kind_flg'] = '0';
						$result_data['show_record'][$key]['max_percent_video'] = '0';
						$result_data['show_record'][$key]['max_val_exam'] = '0';
						$result_data['show_record'][$key]['answer_val_exam'] = '0';
						$result_data['show_record'][$key]['gouhi'] = '-';
						
						$test_flg = false;
						$query_record_1 = array();
						$arr_result_1 = array();
						$sql = "SELECT product_kind_flg FROM tbl_product_elearning WHERE product_id = ?";
						$query_record_1 = $this->db->query($sql,
							array(
								(int)$val['product_id'],
							)
						);
						if ($query_record_1->num_rows() > 0){
							$arr_result_1 = $query_record_1->result_array();
							foreach ($arr_result_1 as $ar_val){
								$result_data['show_record'][$key]['product_kind_flg'] = $ar_val['product_kind_flg'];
								if($ar_val['product_kind_flg'] == '3'){
									$test_flg = true;
								}
							}
						}
						if($test_flg){
							// 研修動画のパーセントを取得(視聴完了していない動画の最大値)
							if ($in_video_id != ''){
								$query_record_1 = array();
								$arr_result_1 = array();
								$sql = "
								SELECT
								  MAX(percent) AS max_percent_video
								FROM
								  report_user_video_viewed
								WHERE
								  student_id = ?
								  AND video_id IN(" . rtrim($in_video_id, ',') . ")
								  AND complete_flag = 0
								";
								$query_record_1 = $this->db->query($sql,
									array(
										(int)$param['student_id'],
									)
								);
								if ($query_record_1->num_rows() > 0){
									$arr_result_1 = $query_record_1->result_array();
									foreach ($arr_result_1 as $ar_val){
										if(!is_null($ar_val['max_percent_video'])){
											$result_data['show_record'][$key]['max_percent_video'] = $ar_val['max_percent_video'];
										}
									}
								}
							}
							
							// 全設問数を取得
							$query_record_1 = array();
							$arr_result_1 = array();
							$sql = "
							SELECT
							  COUNT(*) AS max_val_exam
							FROM
							  rel_product_contents
							WHERE
							  product_id = ?
							";
							$query_record_1 = $this->db->query($sql,
								array(
									(int)$val['product_id'],
								)
							);
							if ($query_record_1->num_rows() > 0){
								$arr_result_1 = $query_record_1->result_array();
								foreach ($arr_result_1 as $ar_val){
									$result_data['show_record'][$key]['max_val_exam']  = $ar_val['max_val_exam'];
								}
							}
							
							// 設問の解答数を取得
							$query_record_1 = array();
							$arr_result_1 = array();
							$sql = "
							SELECT
							  passing_flg,
							  criteria_type,
							  criteria_value
							FROM
							  exam_answer
							WHERE
							  status=0
							  AND product_id = ?
							  AND student_id = ?
							GROUP BY
							  contents_no
							";
							$query_record_1 = $this->db->query($sql,
								array(
									(int)$val['product_id'],
									(int)$param['student_id'],
								)
							);
							if ($query_record_1->num_rows() > 0){
								$result_data['show_record'][$key]['answer_val_exam'] = (string)$query_record_1->num_rows();
							}
							
							// 合否
							$query_record_1 = array();
							$arr_result_1 = array();
							$sql = "
							SELECT
							  *
							FROM
							  rel_product_contents
							WHERE
							  product_id = ?
							";
							$query_record_1 = $this->db->query($sql,
								array(
									(int)$val['product_id'],
								)
							);
							if ($query_record_1->num_rows() > 0){
								$arr_result_1 = $query_record_1->result_array();
								foreach ($arr_result_1 as $ar_val){
									// 判定基準が設定されているか確認
									if($ar_val['exam_id_test']>0){
										$query_record_2 = array();
										$arr_result_2 = array();
										$sql = "SELECT criteria_type FROM exam WHERE exam_id = ?";
										$query_record_2 = $this->db->query($sql,
											array(
												(int)$ar_val['exam_id_test'],
											)
										);
										if ($query_record_2->num_rows() > 0){
											$arr_result_2 = $query_record_2->result_array();
											foreach ($arr_result_2 as $ar_val2){
												if($ar_val2['criteria_type']>0){
													$result_data['show_record'][$key]['gouhi'] = '未実施';
													break;
												}
											}
										}
									}
								}
								if($result_data['show_record'][$key]['gouhi']!='-'){
									// すべてのテストを受講済みかどうかチェック
									$query_record_1 = array();
									$arr_result_1 = array();
									$product_contents_total = 0;
									$sql = "SELECT COUNT(*) AS count FROM rel_product_contents WHERE product_id = ? AND exam_id_test > 0";
									$query_record_1 = $this->db->query($sql,
										array(
											(int)$val['product_id'],
										)
									);
									if ($query_record_1->num_rows() > 0){
										$arr_result_1 = $query_record_1->result_array();
										foreach ($arr_result_1 as $ar_val){
											$product_contents_total = $ar_val['count'];
										}
									}
									
									$query_record_1 = array();
									$arr_result_1 = array();
									$exam_answer_total = 0;
									$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id = ? AND student_id = ? GROUP BY contents_no";
									$query_record_1 = $this->db->query($sql,
										array(
											(int)$val['product_id'],
											(int)$param['student_id'],
										)
									);
									if ($query_record_1->num_rows() > 0){
										$exam_answer_total = (string)$query_record_1->num_rows();
										$arr_result_1 = $query_record_1->result_array();
									}
									
									// すべて受講済みの場合に合否判定する
									if($product_contents_total>0 && $exam_answer_total>0 && ($product_contents_total==$exam_answer_total)){
										$result_data['show_record'][$key]['gouhi'] = '合格';
										foreach($arr_result_1 as $ar_val){
											if($ar_val['passing_flg']=='0'){
												$result_data['show_record'][$key]['gouhi'] = '不合格';
												break;
											}
										}
									}
								}
							}
						}
						
					}
				}else{
					$result_data['show_record'] = array();
				}
			} else {
				$result_data['all_count'] = 0;
				$result_data['show_record'] = array();
			}
			
		} catch (Exception $e) {
			return $result_data;
		}
		
		return $result_data;
	}
	
	//----------------------------------------------
	// [日弁連]レポート-ユーザ、ライブ実務
	//----------------------------------------------
	function get_user_live_training($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'  => 0,
				'offset'      => 0,
				'rowcount'    => 0,
			),
			$param
		);
		
		// 戻り値設定
		$result_data['show_record'] = array();
		$result_data['all_count']   = -1;
		
		try{
			// 表示レコード取得 - SQL生成
			$sql = '';
            $sql = "SELECT
			 tbl_order_detail.order_detail_id,
			 tbl_product.product_name, /* 商品名 */
			 tbl_product_add.product_type_add, /* 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート) */
			 ( SELECT mtb_bar_association.name FROM mtb_bar_association WHERE mtb_bar_association.id = tbl_order_detail.bar_association_id ) AS bar_association_branch_name, /* 弁護士会名 */
			 tbl_order.web_flg, /* 申込(0：web以外 1：web申込) */
			( SELECT rel_product_bar_association_branch.dates FROM rel_product_bar_association_branch WHERE rel_product_bar_association_branch.product_id = tbl_order_detail.product_id AND rel_product_bar_association_branch.bar_association_branch_id = tbl_order_detail.bar_association_branch_id ) AS dates, /* 実施日 */
			 tbl_order_detail.participation_flg, /* 進捗(0:未受講 1:受講) */
			 tbl_order_detail.create_date, /* 作成日 */
			 tbl_order_detail.product_id /* 商品ID */
			FROM
			 tbl_order_detail 
			 LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
			 LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
			 LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id 
			 LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id 
			 LEFT JOIN mtb_bar_association ON tbl_order_detail.bar_association_id=mtb_bar_association.id 
			 LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id 
			 LEFT JOIN rel_product_bar_association_branch ON tbl_order_detail.product_id=rel_product_bar_association_branch.product_id and tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch.bar_association_branch_id 
			WHERE 
			 tbl_order_detail.member_id=? /* ユーザ指定 */ 
			 AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
			 AND tbl_product_add.product_type_add ='2' 
			 AND tbl_product_live_training.training_kind_flg='1'
			GROUP BY 
			 tbl_order_detail.product_id, 
			 tbl_product.product_name, 
			 mtb_bar_association.name, 
			 tbl_order.web_flg, /* 申込(0：web以外 1：web申込) */
			 rel_product_bar_association_branch.dates, /* 実施日 */
			 tbl_order_detail.participation_flg /* 進捗(0:未受講 1:受講) */
			";
			
			$query_count = $this->db->query($sql, 
					array(
						(int)$param['student_id'],
					)
			);
			$result_data['all_count'] = $query_count->num_rows();
			
			// 表示レコード取得 - SQL生成
			$sql_limit  = " LIMIT {$param['offset']}, {$param['rowcount']} ";
			$query_record = $this->db->query($sql.$sql_limit, 
					array(
						(int)$param['student_id'],
					)
			);
			if ($query_record->num_rows() > 0){
				//$result_data['show_record'] = $query_record->result_array();
				
				$tmp_result_data = $query_record->result_array();
				if ($tmp_result_data){
					foreach ($tmp_result_data as $key => $val){
						$result_data['show_record'][$key] = $val;
						// echo $val['product_id']." ".$val["web_flg"]."<br>";

						// 会場研修の場合、実施日を取得
						if ($val['product_type_add'] == '2'){
							$product_id = $val['product_id'];
							
							// 旧システムから移行されてきたデータの場合
							if (strtotime($val['create_date']) < strtotime('2013-12-03 00:00:00')){
								if ($product_id != ''){
									$sql = "SELECT dates FROM rel_product_bar_association_branch WHERE product_id = '$product_id' LIMIT 1";
									$query_record_rel_product_bar_association = $this->db->query($sql);
									if ($query_record_rel_product_bar_association->num_rows() > 0){
										$result_data_rel_product_bar_association = array();
										$result_data_rel_product_bar_association = $query_record_rel_product_bar_association->result_array();
										$result_data['show_record'][$key]['dates'] = $result_data_rel_product_bar_association[0]['dates'];
									}
								}
							}
						}
					}
				} else {
					$result_data['show_record'] = $query_record->result_array();
				}
				
			}else{
				$result_data['show_record'] = array();
			}
			
		} catch (Exception $e) {
			return $result_data;
		}
		
		return $result_data;
	}
	
	//----------------------------------------------
	// [日弁連]レポート-ユーザ、日弁連以外主催
	//----------------------------------------------
	function get_user_nichibenren_except_host($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'  => 0,
				'offset'      => 0,
				'rowcount'    => 0,
			),
			$param
		);
		
		// 戻り値設定
		$result_data['show_record'] = array();
		$result_data['all_count']   = -1;
		
		try{
			// 表示レコード取得 - SQL生成
			$sql = '';
            $sql = "SELECT
			 tbl_order_detail.order_detail_id,
			 tbl_product.product_name, /* 商品名 */
			 tbl_product_add.product_type_add, /* 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート) */
			 mtb_bar_association.name as bar_association_branch_name, /* 弁護士会名 */
			 tbl_order.web_flg, /* 申込(0：web以外 1：web申込) */
			 ( SELECT rel_product_bar_association_branch.dates FROM rel_product_bar_association_branch WHERE rel_product_bar_association_branch.product_id = tbl_order_detail.product_id AND rel_product_bar_association_branch.bar_association_branch_id = tbl_order_detail.bar_association_branch_id ) AS dates, /* 実施日 */
			 tbl_order_detail.participation_flg, /* 進捗(0:未受講 1:受講) */
			 tbl_order_detail.create_date, /* 作成日 */
			 tbl_order_detail.product_id, /* 商品ID */
			 tbl_product_live_training.sponsor
			FROM
			 tbl_order_detail 
			 LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
			 LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
			 LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id 
			 LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id 
			 LEFT JOIN mtb_bar_association ON tbl_order_detail.bar_association_id=mtb_bar_association.id 
			 LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id 
			 LEFT JOIN rel_product_bar_association_branch ON tbl_order_detail.product_id=rel_product_bar_association_branch.product_id and tbl_order_detail.bar_association_branch_id=rel_product_bar_association_branch.bar_association_branch_id 
			WHERE 
			 tbl_order_detail.member_id=? /* ユーザ指定 */ 
			 AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
			 AND tbl_product_add.product_type_add ='2' 
			 AND tbl_product_live_training.training_kind_flg NOT IN ('1','3')
			 AND tbl_product_live_training.ethic_flg='0'
			GROUP BY 
			 tbl_order_detail.product_id, 
			 tbl_product.product_name, 
			 mtb_bar_association.name, 
			 tbl_order.web_flg, /* 申込(0：web以外 1：web申込) */
			 rel_product_bar_association_branch.dates, /* 実施日 */
			 tbl_order_detail.participation_flg /* 進捗(0:未受講 1:受講) */
			";
			
			$query_count = $this->db->query($sql, 
					array(
						(int)$param['student_id'],
					)
			);
			$result_data['all_count'] = $query_count->num_rows();
			
			// 表示レコード取得 - SQL生成
			$sql_limit  = " LIMIT {$param['offset']}, {$param['rowcount']} ";
			$query_record = $this->db->query($sql.$sql_limit, 
					array(
						(int)$param['student_id'],
					)
			);
			if ($query_record->num_rows() > 0){
				//$result_data['show_record'] = $query_record->result_array();
				
				$tmp_result_data = $query_record->result_array();
				
				// 弁護士会マスタ取得
				$mtb_bar_association = array();
				$sql = "SELECT id, name FROM mtb_bar_association";
				$query_record_mtb_bar_association = $this->db->query($sql);
				if ($query_record_mtb_bar_association->num_rows() > 0){
					$result_data_mtb_bar_association = $query_record_mtb_bar_association->result_array();
					foreach ($result_data_mtb_bar_association as $val){
						$mtb_bar_association[$val['id']] = $val['name'];
					}
				}
				// 弁護士会マスタが設定されている場合
				if (!empty($mtb_bar_association)){
					foreach ($tmp_result_data as $key => $val){
						$result_data['show_record'][$key] = $val;
						
						// 表示用の弁護士会を取得
						$result_data['show_record'][$key]['bar_association_branch_name'] = '';
						if ($val['sponsor'] != ''){
							$arr_sponsor = array();
							$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
							foreach ($arr_sponsor as $sponsor){
								$result_data['show_record'][$key]['bar_association_branch_name'].= $mtb_bar_association[$sponsor] . '<br />';
							}
							$result_data['show_record'][$key]['bar_association_branch_name'] = trim($result_data['show_record'][$key]['bar_association_branch_name'], '<br />');
						}
						
						// 会場研修の場合、実施日を取得
						if ($val['product_type_add'] == '2'){
							$product_id = $val['product_id'];
							
							// 旧システムから移行されてきたデータの場合
							if (strtotime($val['create_date']) < strtotime('2013-12-03 00:00:00')){
								if ($product_id != ''){
									$sql = "SELECT dates FROM rel_product_bar_association_branch WHERE product_id = '$product_id' LIMIT 1";
									$query_record_rel_product_bar_association = $this->db->query($sql);
									if ($query_record_rel_product_bar_association->num_rows() > 0){
										$result_data_rel_product_bar_association = array();
										$result_data_rel_product_bar_association = $query_record_rel_product_bar_association->result_array();
										$result_data['show_record'][$key]['dates'] = $result_data_rel_product_bar_association[0]['dates'];
									}
								}
							}
						}
						
					}
				} else {
					$result_data['show_record'] = $query_record->result_array();
				}
				
			}else{
				$result_data['show_record'] = array();
			}
			
		} catch (Exception $e) {
			return $result_data;
		}
		
		return $result_data;
	}
	
	//----------------------------------------------
	// [日弁連]レポート-ユーザ、倫理研修
	//----------------------------------------------
	function get_user_ethic_training($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'  => 0,
				'offset'      => 0,
				'rowcount'    => 0,
			),
			$param
		);
		
		// 戻り値設定
		$result_data['show_record'] = array();
		$result_data['all_count']   = -1;
		
		try{
			// 表示レコード取得 - SQL生成
			$sql = '';
            $sql = "
			SELECT 
			 /* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
			 arr_pid.product_id, 
			 arr_pid.participation_flg, /* 会場研修参加フラグ(0:会場 1:受講済) */
			 /* ------------------------------------- */
			 tbl_product_add.product_type_add, /* 2:会場研修 3:代替倫理研修 */
			 /* ------------------------------------- */
			 tbl_product.product_name, 
			 /* ------------------------------------- */
			 (
			  SELECT
			   COUNT(video.video_id) AS all_training_video_count
			  FROM
			   video
			  WHERE
			   (
			    tbl_product.contents_contents1 = video.video_id
			    OR tbl_product.contents_contents2 = video.video_id
			    OR tbl_product.contents_contents3 = video.video_id
			    OR tbl_product.contents_contents4 = video.video_id
			    OR tbl_product.contents_contents5 = video.video_id
			    OR tbl_product.contents_contents6 = video.video_id
			    OR tbl_product.contents_contents7 = video.video_id
			    OR tbl_product.contents_contents8 = video.video_id
			    OR tbl_product.contents_contents9 = video.video_id
			    OR tbl_product.contents_contents10 = video.video_id
			    OR tbl_product.contents_contents11 = video.video_id
			    OR tbl_product.contents_contents12 = video.video_id
			    OR tbl_product.contents_contents13 = video.video_id
			    OR tbl_product.contents_contents14 = video.video_id
			    OR tbl_product.contents_contents15 = video.video_id
			    OR tbl_product.contents_contents16 = video.video_id
			    OR tbl_product.contents_contents17 = video.video_id
			    OR tbl_product.contents_contents18 = video.video_id
			    OR tbl_product.contents_contents19 = video.video_id
			    OR tbl_product.contents_contents20 = video.video_id
			    OR tbl_product.contents_contents21 = video.video_id
			    OR tbl_product.contents_contents22 = video.video_id
			    OR tbl_product.contents_contents23 = video.video_id
			    OR tbl_product.contents_contents24 = video.video_id
			    OR tbl_product.contents_contents25 = video.video_id
			   )
			 ) AS all_training_video_count,
			 /* ------------------------------------- */
			 (
			  SELECT
			   COUNT(report_user_video_viewed.video_id) AS all_training_video_viewed_count
			  FROM
			   report_user_video_viewed
			  WHERE
			   report_user_video_viewed.student_id = tbl_ethic_question_history.student_id
			   AND (
			    tbl_product.contents_contents1 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id
			    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id
			   )
			 ) AS all_training_video_viewed_count,
			 /* ------------------------------------- */
			 (
			  SELECT 
			   SUM(t.c) as answer_count 
			  FROM 
			   (
			   SELECT 
			      product_id, 
			      ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id1>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date1 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id2>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date2 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id3>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date3 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id4>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date4 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id5>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date5 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id6>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date6 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id7>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date7 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id8>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date8 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id9>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date9 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id10>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date10 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			   ) as t 
			  WHERE 
			   t.product_id=arr_pid.product_id 
			 ) AS answer_count, 
			 /* ------------------------------------- */
			 (
			  SELECT 
			   SUM(t.c) as answer_count 
			  FROM 
			   (
			   SELECT 
			      product_id, 
			      ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id11>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date11 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id12>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date12 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id13>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date13 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id14>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date14 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id15>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date15 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id16>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date16 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			   ) as t 
			  WHERE 
			   t.product_id=arr_pid.product_id 
			 ) AS 2nd_answer_count,
			 /* ------------------------------------- */
			 tbl_product_ethic_training.ethic_group_id,
			 /* ------------------------------------- */
			 arr_pid.status,
			 /* ------------------------------------- */
			 (
			  SELECT
			   COUNT(*) AS c
			  FROM
			   (
			    SELECT
			     report_user_video_viewed.video_id as c1,
			     tbl_ethic_question.ethic_group_id as c2
			    FROM
			     report_user_video_viewed
			     LEFT JOIN tbl_ethic_question ON report_user_video_viewed.video_id=tbl_ethic_question.video_id
			    WHERE
			     report_user_video_viewed.student_id='?' /* 対象のユーザIDの指定 */
			    GROUP BY
			     report_user_video_viewed.video_id,
			     tbl_ethic_question.ethic_group_id
			   ) as t
			  WHERE
			   c2=tbl_product_ethic_training.ethic_group_id
			 ) AS video_viewed_count
			 /* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
			FROM 
			 (
			  SELECT 
			   tbl_ethic_question_history.product_id as product_id, 
			   tbl_ethic_question_history.status, 
			   NULL as participation_flg 
			  FROM 
			   tbl_ethic_question_history 
			  WHERE
			   tbl_ethic_question_history.student_id='?' /* 対象のユーザIDの指定 */
			  UNION 
			  SELECT 
			   tbl_order_detail.product_id, 
			   null as status,
			   tbl_order_detail.participation_flg 
			  FROM 
			   tbl_order_detail 
			   LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id 
			   LEFT JOIN tbl_product_live_training ON tbl_order_detail.product_id=tbl_product_live_training.product_id 
			  WHERE 
			   tbl_order_detail.member_id='?' /* 対象のユーザIDの指定 */
			   AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
			   AND tbl_product_add.product_type_add='2' 
			   AND tbl_product_live_training.ethic_flg='1' 
			 ) AS arr_pid 
			 LEFT JOIN tbl_ethic_question_history ON arr_pid.product_id=tbl_ethic_question_history.product_id AND tbl_ethic_question_history.student_id='?' /* 対象のユーザIDの指定 */ 
			 LEFT JOIN tbl_product ON arr_pid.product_id=tbl_product.product_id 
			 LEFT JOIN tbl_product_add ON arr_pid.product_id=tbl_product_add.product_id 
			 LEFT JOIN tbl_product_ethic_training ON arr_pid.product_id=tbl_product_ethic_training.product_id
			";
			
			$query_count = $this->db->query($sql, 
					array(
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
					)
			);
			$result_data['all_count'] = $query_count->num_rows();
			
			// 表示レコード取得 - SQL生成
			$sql_limit  = " LIMIT {$param['offset']}, {$param['rowcount']} ";
			$query_record = $this->db->query($sql.$sql_limit, 
					array(
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
					)
			);

			if ($query_record->num_rows() > 0){
				$result_data['show_record'] = $query_record->result_array();
				//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
				$arr_temp = $result_data['show_record'];
				for($i=0;$i<count($arr_temp);$i++){
					if( $arr_temp[$i]["product_type_add"]=="3" ) {
						$arr_temp[$i]["answer_count"] = 0;
						$arr_temp[$i]["2nd_answer_count"] = 0;
						$arr_answer_ethic_branch_id = array();

						//var_dump( "product_id:".$arr_temp[$i]["product_id"]."\n" );
						//var_dump( "student_id:".(int)$param['student_id']."\n" );
						if ( 
							$arr_temp[$i]["product_id"]=="19234" // 1
							 || $arr_temp[$i]["product_id"]=="19235" // 2
							 || $arr_temp[$i]["product_id"]=="19236" // 3
							 || $arr_temp[$i]["product_id"]=="19237" // 4
							 || $arr_temp[$i]["product_id"]=="19238" // 5
						 ) {
							//旧問題
							//var_dump( "旧問題\n" );

							$int_temp = 0; //問題の系数
							if ( $arr_temp[$i]["product_id"]=="19234" ){ $int_temp = 1; }
							elseif ( $arr_temp[$i]["product_id"]=="19235" ){ $int_temp = 2; }
							elseif ( $arr_temp[$i]["product_id"]=="19236" ){ $int_temp = 3; }
							elseif ( $arr_temp[$i]["product_id"]=="19237" ){ $int_temp = 4; }
							elseif ( $arr_temp[$i]["product_id"]=="19238" ){ $int_temp = 5; }
							//elseif ( $arr_temp[$i]["product_id"]=="19239" ){ $int_temp = 6; }

							$sql_sub = "select * FROM tbl_ethic_question_history WHERE student_id='".(int)$param['student_id']."'and product_id='".$arr_temp[$i]["product_id"]."'";
							$obj_temp = $this->db->query( $sql_sub, array() );
							$arr_temp_sub = $obj_temp->result_array();

							//var_dump( $arr_temp_sub );

							if( count($arr_temp_sub)>0 ){
								//--------------------------------------------------------------------------------
								$arr_temp_sub[0]["answer_ethic_branch_id1"] = $arr_temp_sub[0]["answer_ethic_branch_id1"] + (5 * 0) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id2"] = $arr_temp_sub[0]["answer_ethic_branch_id2"] + (5 * 1) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id3"] = $arr_temp_sub[0]["answer_ethic_branch_id3"] + (5 * 2) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id4"] = $arr_temp_sub[0]["answer_ethic_branch_id4"] + (5 * 3) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id5"] = $arr_temp_sub[0]["answer_ethic_branch_id5"] + (5 * 4) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id6"] = $arr_temp_sub[0]["answer_ethic_branch_id6"] + (5 * 5) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id7"] = $arr_temp_sub[0]["answer_ethic_branch_id7"] + (5 * 6) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id8"] = $arr_temp_sub[0]["answer_ethic_branch_id8"] + (5 * 7) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id9"] = $arr_temp_sub[0]["answer_ethic_branch_id9"] + (5 * 8) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id10"] = $arr_temp_sub[0]["answer_ethic_branch_id10"] + (5 * 9) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id11"] = $arr_temp_sub[0]["answer_ethic_branch_id11"] + (5 * 10) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id12"] = $arr_temp_sub[0]["answer_ethic_branch_id12"] + (5 * 11) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id13"] = $arr_temp_sub[0]["answer_ethic_branch_id13"] + (5 * 12) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id14"] = $arr_temp_sub[0]["answer_ethic_branch_id14"] + (5 * 13) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id15"] = $arr_temp_sub[0]["answer_ethic_branch_id15"] + (5 * 14) + (80 * ($int_temp - 1) );
								$arr_temp_sub[0]["answer_ethic_branch_id16"] = $arr_temp_sub[0]["answer_ethic_branch_id16"] + (5 * 15) + (80 * ($int_temp - 1) );
								//--------------------------------------------------------------------------------
								$str_temp = "";
								if( $arr_temp_sub[0]["answer_ethic_branch_id1"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id1"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id2"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id2"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id3"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id3"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id4"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id4"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id5"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id5"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id6"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id6"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id7"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id7"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id8"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id8"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id9"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id9"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id10"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id10"].",";
								}
								$str_temp = trim( $str_temp, "," );
								if( $str_temp != "" ){
									$sql_sub2 = "select count(answer_flg) as c from tbl_ethic_branch where ethic_branch_id IN ( ".$str_temp." ) AND answer_flg=1 ";

									$obj_temp2 = $this->db->query( $sql_sub2, array() );
									$arr_temp_sub2 = $obj_temp2->result_array();
									$arr_temp[$i]["answer_count"] = $arr_temp_sub2[0]["c"];
								} else {
									$arr_temp[$i]["answer_count"] = 0;
								}
								//--------------------------------------------------------------------------------
								$str_temp = "";
								if( $arr_temp_sub[0]["answer_ethic_branch_id11"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id11"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id12"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id12"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id13"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id13"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id14"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id14"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id15"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id15"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id16"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id16"].",";
								}
								$str_temp = trim( $str_temp, "," );
								if( $str_temp != "" ){
									$sql_sub2 = "select count(answer_flg) as c from tbl_ethic_branch where ethic_branch_id IN ( ".$str_temp." ) AND answer_flg=1 ";

									$obj_temp2 = $this->db->query( $sql_sub2, array() );
									$arr_temp_sub2 = $obj_temp2->result_array();
									$arr_temp[$i]["2nd_answer_count"] = $arr_temp_sub2[0]["c"];
								} else {
									$arr_temp[$i]["2nd_answer_count"] = 0;
								}
								//--------------------------------------------------------------------------------
							}

						} else {
							//新問題
							//var_dump( "新問題\n" );

							$sql_sub = "select * FROM tbl_ethic_question_history WHERE student_id='".(int)$param['student_id']."'and product_id='".$arr_temp[$i]["product_id"]."'";
							$obj_temp = $this->db->query( $sql_sub, array() );
							$arr_temp_sub = $obj_temp->result_array();

							if( count($arr_temp_sub)>0 ){
								//--------------------------------------------------------------------------------
								$arr_temp_sub[0]["answer_ethic_branch_id1"] = $arr_temp_sub[0]["answer_ethic_branch_id1"];
								$arr_temp_sub[0]["answer_ethic_branch_id2"] = $arr_temp_sub[0]["answer_ethic_branch_id2"];
								$arr_temp_sub[0]["answer_ethic_branch_id3"] = $arr_temp_sub[0]["answer_ethic_branch_id3"];
								$arr_temp_sub[0]["answer_ethic_branch_id4"] = $arr_temp_sub[0]["answer_ethic_branch_id4"];
								$arr_temp_sub[0]["answer_ethic_branch_id5"] = $arr_temp_sub[0]["answer_ethic_branch_id5"];
								$arr_temp_sub[0]["answer_ethic_branch_id6"] = $arr_temp_sub[0]["answer_ethic_branch_id6"];
								$arr_temp_sub[0]["answer_ethic_branch_id7"] = $arr_temp_sub[0]["answer_ethic_branch_id7"];
								$arr_temp_sub[0]["answer_ethic_branch_id8"] = $arr_temp_sub[0]["answer_ethic_branch_id8"];
								$arr_temp_sub[0]["answer_ethic_branch_id9"] = $arr_temp_sub[0]["answer_ethic_branch_id9"];
								$arr_temp_sub[0]["answer_ethic_branch_id10"] = $arr_temp_sub[0]["answer_ethic_branch_id10"];
								$arr_temp_sub[0]["answer_ethic_branch_id11"] = $arr_temp_sub[0]["answer_ethic_branch_id11"];
								$arr_temp_sub[0]["answer_ethic_branch_id12"] = $arr_temp_sub[0]["answer_ethic_branch_id12"];
								$arr_temp_sub[0]["answer_ethic_branch_id13"] = $arr_temp_sub[0]["answer_ethic_branch_id13"];
								$arr_temp_sub[0]["answer_ethic_branch_id14"] = $arr_temp_sub[0]["answer_ethic_branch_id14"];
								$arr_temp_sub[0]["answer_ethic_branch_id15"] = $arr_temp_sub[0]["answer_ethic_branch_id15"];
								$arr_temp_sub[0]["answer_ethic_branch_id16"] = $arr_temp_sub[0]["answer_ethic_branch_id16"];
								//--------------------------------------------------------------------------------
								$str_temp = "";
								if( $arr_temp_sub[0]["answer_ethic_branch_id1"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id1"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id2"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id2"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id3"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id3"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id4"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id4"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id5"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id5"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id6"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id6"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id7"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id7"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id8"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id8"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id9"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id9"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id10"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id10"].",";
								}
								$str_temp = trim( $str_temp, "," );
								if( $str_temp != "" ){
									$sql_sub2 = "select count(answer_flg) as c from tbl_ethic_branch where ethic_branch_id IN ( ".$str_temp." ) AND answer_flg=1 ";

									$obj_temp2 = $this->db->query( $sql_sub2, array() );
									$arr_temp_sub2 = $obj_temp2->result_array();
									$arr_temp[$i]["answer_count"] = $arr_temp_sub2[0]["c"];
								} else {
									$arr_temp[$i]["answer_count"] = 0;
								}
								//--------------------------------------------------------------------------------
								$str_temp = "";
								if( $arr_temp_sub[0]["answer_ethic_branch_id11"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id11"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id12"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id12"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id13"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id13"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id14"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id14"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id15"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id15"].",";
								}
								if( $arr_temp_sub[0]["answer_ethic_branch_id16"]>0 ){
									$str_temp .= "".$arr_temp_sub[0]["answer_ethic_branch_id16"].",";
								}
								$str_temp = trim( $str_temp, "," );
								if( $str_temp != "" ){
									$sql_sub2 = "select count(answer_flg) as c from tbl_ethic_branch where ethic_branch_id IN ( ".$str_temp." ) AND answer_flg=1 ";

									$obj_temp2 = $this->db->query( $sql_sub2, array() );
									$arr_temp_sub2 = $obj_temp2->result_array();
									$arr_temp[$i]["2nd_answer_count"] = $arr_temp_sub2[0]["c"];
								} else {
									$arr_temp[$i]["2nd_answer_count"] = 0;
								}
								//--------------------------------------------------------------------------------
							}
						}
					}
				}
				$result_data['show_record'] = $arr_temp;
				//LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
			}else{
				$result_data['show_record'] = array();
			}
			
		} catch (Exception $e) {
			return $result_data;
		}
		
		return $result_data;
	}
	
	//----------------------------------------------
	// [日弁連]レポート-ユーザ、全て
	//----------------------------------------------
	function get_user_all($param){
		//引数設定
		$param = array_merge(
			array(
				'student_id'  => 0,
				'offset'      => 0,
				'rowcount'    => 0,
			),
			$param
		);
		
		// 戻り値設定
		$result_data['show_record'] = array();
		$result_data['all_count']   = -1;
		
		try{
			// 表示レコード取得 - SQL生成
			$sql = '';
            $sql = "
SELECT 
 /* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
 arr_pid.product_id,
 /* ------------------------------------- */
 arr_pid.product_name, 
 /* ------------------------------------- */
 arr_pid.order_detail_id, 
 /* ------------------------------------- */
 tbl_order_detail.create_date, 
 /* ------------------------------------- */
 tbl_product_add.product_type_add, /* 商品種別 */ 
 /* ------------------------------------- */
 tbl_order_detail.bar_association_id, 
 mtb_bar_association.name as bar_association_name, /* 弁護士会名 */ 
 /* ------------------------------------- */
 tbl_order_detail.bar_association_branch_id, 
 mtb_bar_association_branch.bar_association_branch_name as bar_association_branch_name, /* 弁護士会名 */ 
 /* ------------------------------------- */
 tbl_product.contents_contents1,
 tbl_product.contents_contents2,
 tbl_product.contents_contents3,
 tbl_product.contents_contents4,
 tbl_product.contents_contents5,
 tbl_product.contents_contents6,
 tbl_product.contents_contents7,
 tbl_product.contents_contents8,
 tbl_product.contents_contents9,
 tbl_product.contents_contents10,
 tbl_product.contents_contents11,
 tbl_product.contents_contents12,
 tbl_product.contents_contents13,
 tbl_product.contents_contents14,
 tbl_product.contents_contents15,
 tbl_product.contents_contents16,
 tbl_product.contents_contents17,
 tbl_product.contents_contents18,
 tbl_product.contents_contents19,
 tbl_product.contents_contents20,
 tbl_product.contents_contents21,
 tbl_product.contents_contents22,
 tbl_product.contents_contents23,
 tbl_product.contents_contents24,
 tbl_product.contents_contents25,
 /* ------------------------------------- */
 ( 
  SELECT 
   MAX( report_user_video_viewed.reading_date ) AS max_reading_date 
  FROM 
   report_user_video_viewed 
  WHERE 
   report_user_video_viewed.student_id='?' /* ユーザ指定 */ 
   AND ( 
    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
   ) 
 ) AS max_reading_date, /* ビデオ最終受講日 */
 /* ------------------------------------- */
 (
  SELECT 
   rel_product_bar_association_branch.dates /* 会場研修実施日 */
  FROM 
   rel_product_bar_association_branch 
  WHERE 
   rel_product_bar_association_branch.product_id=arr_pid.product_id 
   AND rel_product_bar_association_branch.bar_association_branch_id=tbl_order_detail.bar_association_branch_id 
 ) AS dates, 
 /* ------------------------------------- */
 ( 
  SELECT 
   SUM(TIME_TO_SEC(video_alfstream_status.alfstream_duration)) AS all_alfstream_duration 
  FROM 
   video_alfstream_status 
  WHERE 
   ( 
    tbl_product.contents_contents1 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents2 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents3 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents4 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents5 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents6 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents7 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents8 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents9 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents10 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents11 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents12 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents13 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents14 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents15 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents16 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents17 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents18 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents19 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents20 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents21 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents22 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents23 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents24 = video_alfstream_status.video_id 
    OR tbl_product.contents_contents25 = video_alfstream_status.video_id 
   ) 
 ) AS all_alfstream_duration_sec, /* 総時間(秒) */
 /* ------------------------------------- */
 (
  SELECT
   SUM(TIME_TO_SEC(report_user_video_viewed.duration_reading)) AS all_duration_reading 
  FROM 
   report_user_video_viewed 
  WHERE 
   report_user_video_viewed.student_id='?' /* ユーザ指定 */ 
   AND ( 
    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
   ) 
 ) AS all_duration_reading, /* 視聴済時間(秒) */
 /* ------------------------------------- */
 tbl_order_detail.participation_flg, /* 進捗(0:未受講 1:受講) */
 /* ------------------------------------- */
 tbl_product_live_training.ethic_flg, /* 倫理フラグ */
 /* ------------------------------------- */
 tbl_product_live_training.sponsor, /* 主催 */
 /* ------------------------------------- */
 (
  SELECT
   COUNT(video.video_id) AS all_training_video_count
  FROM
   video 
  WHERE 
   ( 
    tbl_product.contents_contents1 = video.video_id 
    OR tbl_product.contents_contents2 = video.video_id 
    OR tbl_product.contents_contents3 = video.video_id 
    OR tbl_product.contents_contents4 = video.video_id 
    OR tbl_product.contents_contents5 = video.video_id 
    OR tbl_product.contents_contents6 = video.video_id 
    OR tbl_product.contents_contents7 = video.video_id 
    OR tbl_product.contents_contents8 = video.video_id 
    OR tbl_product.contents_contents9 = video.video_id 
    OR tbl_product.contents_contents10 = video.video_id 
    OR tbl_product.contents_contents11 = video.video_id 
    OR tbl_product.contents_contents12 = video.video_id 
    OR tbl_product.contents_contents13 = video.video_id 
    OR tbl_product.contents_contents14 = video.video_id 
    OR tbl_product.contents_contents15 = video.video_id 
    OR tbl_product.contents_contents16 = video.video_id 
    OR tbl_product.contents_contents17 = video.video_id 
    OR tbl_product.contents_contents18 = video.video_id 
    OR tbl_product.contents_contents19 = video.video_id 
    OR tbl_product.contents_contents20 = video.video_id 
    OR tbl_product.contents_contents21 = video.video_id 
    OR tbl_product.contents_contents22 = video.video_id 
    OR tbl_product.contents_contents23 = video.video_id 
    OR tbl_product.contents_contents24 = video.video_id 
    OR tbl_product.contents_contents25 = video.video_id 
   ) 
 ) AS all_training_video_count, /* 研修動画数 */
 /* ------------------------------------- */
 ( 
  SELECT 
   COUNT(report_user_video_viewed.video_id) AS all_training_video_viewed_count 
  FROM 
   report_user_video_viewed 
  WHERE 
   report_user_video_viewed.student_id='?' /* ユーザ指定 */ 
   AND ( 
    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
   ) 
 ) AS all_training_video_viewed_count, /* 視聴済研修動画数 */
 /* ------------------------------------- */
 arr_pid.answer_ethic_branch_id1, 
 arr_pid.answer_ethic_branch_id2, 
 arr_pid.answer_ethic_branch_id3, 
 arr_pid.answer_ethic_branch_id4, 
 arr_pid.answer_ethic_branch_id5, 
 arr_pid.answer_ethic_branch_id6, 
 arr_pid.answer_ethic_branch_id7, 
 arr_pid.answer_ethic_branch_id8, 
 arr_pid.answer_ethic_branch_id9, 
 arr_pid.answer_ethic_branch_id10, 
 arr_pid.answer_ethic_branch_id11, 
 arr_pid.answer_ethic_branch_id12, 
 arr_pid.answer_ethic_branch_id13, 
 arr_pid.answer_ethic_branch_id14, 
 arr_pid.answer_ethic_branch_id15, 
 arr_pid.answer_ethic_branch_id16, 
 /* ------------------------------------- */
			 /* ------------------------------------- */
			 (
			  SELECT 
			   SUM(t.c) as answer_count 
			  FROM 
			   (
			   SELECT 
			      product_id, 
			      ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id1>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date1 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id2>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date2 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id3>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date3 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id4>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date4 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id5>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date5 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id6>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date6 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id7>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date7 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id8>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date8 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id9>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date9 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id10>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date10 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			   ) as t 
			  WHERE 
			   t.product_id=arr_pid.product_id 
			 ) AS answer_count, 
			 /* ------------------------------------- */
			 (
			  SELECT 
			   SUM(t.c) as answer_count 
			  FROM 
			   (
			   SELECT 
			      product_id, 
			      ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id11>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date11 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id12>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date12 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id13>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date13 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id14>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date14 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id15>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date15 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			    UNION ALL 
			    SELECT
			      product_id, 
			     ( 
			      CASE 
			       WHEN tbl_ethic_question_history.answer_ethic_branch_id16>0 THEN 1
			       ELSE null END 
			     ) as c, 
			     answer_date16 as answer_date 
			    FROM
			     tbl_ethic_question_history 
			    WHERE 
			     tbl_ethic_question_history.student_id='?'  
			   ) as t 
			  WHERE 
			   t.product_id=arr_pid.product_id 
			 ) AS 2nd_answer_count,
			 /* ------------------------------------- */
 /* ------------------------------------- */
 ( 
  SELECT 
   COUNT(*) AS c 
  FROM 
   (
    SELECT 
     report_user_video_viewed.video_id as c1, 
     tbl_ethic_question.ethic_group_id as c2 
    FROM 
     report_user_video_viewed 
     LEFT JOIN tbl_ethic_question ON report_user_video_viewed.video_id=tbl_ethic_question.video_id 
    WHERE 
     report_user_video_viewed.student_id='?' /* ユーザ指定 */ 
    GROUP BY 
     report_user_video_viewed.video_id, 
     tbl_ethic_question.ethic_group_id 
   ) as t
  WHERE 
   c2=tbl_product_ethic_training.ethic_group_id 
 ) AS video_viewed_count, 
 /* ------------------------------------- */
 arr_pid.status 
 /* ------------------------------------- */
 /* LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL */
FROM 
 ( 
	/* e-ラーニング */
	SELECT 
	 product_id,
	 product_name, 
	 order_detail_id, 
	 NULL as answer_ethic_branch_id1, 
	 NULL as answer_ethic_branch_id2, 
	 NULL as answer_ethic_branch_id3, 
	 NULL as answer_ethic_branch_id4, 
	 NULL as answer_ethic_branch_id5, 
	 NULL as answer_ethic_branch_id6, 
	 NULL as answer_ethic_branch_id7, 
	 NULL as answer_ethic_branch_id8, 
	 NULL as answer_ethic_branch_id9, 
	 NULL as answer_ethic_branch_id10, 
	 NULL as answer_ethic_branch_id11, 
	 NULL as answer_ethic_branch_id12, 
	 NULL as answer_ethic_branch_id13, 
	 NULL as answer_ethic_branch_id14, 
	 NULL as answer_ethic_branch_id15, 
	 NULL as answer_ethic_branch_id16, 
	 NULL as status 
	FROM 
	(
	 SELECT 
	  tbl_product.product_id, 
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id 
	 FROM 
	  report_user_video_viewed 
	  LEFT JOIN tbl_product ON 
	   ( 
	    tbl_product.contents_contents1 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents2 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents3 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents4 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents5 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents6 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents7 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents8 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents9 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents10 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents11 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents12 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents13 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents14 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents15 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents16 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents17 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents18 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents19 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents20 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents21 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents22 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents23 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents24 = report_user_video_viewed.video_id 
	    OR tbl_product.contents_contents25 = report_user_video_viewed.video_id 
	   )
	  LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
	  LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id AND tbl_order_detail.member_id='?' 
	 WHERE 
	  report_user_video_viewed.student_id='?' /* ユーザ指定 */ 
	  AND tbl_product_add.product_type_add = '1' 
	 GROUP BY 
	  tbl_product.product_id, 
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id 
	 UNION ALL 
	 SELECT 
	  tbl_order_detail.product_id, 
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id 
	 FROM 
	  tbl_order_detail 
	  LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
	  LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id 
	 WHERE 
	  tbl_order_detail.member_id='?' /* ユーザ指定 */ 
	  AND tbl_order_detail.payment_status='2' 
	  AND tbl_product_add.product_type_add = '1' 
	) as t1 


	/* ライブ実務 */
	UNION ALL 
	SELECT 
	 product_id,
	 product_name, 
	 order_detail_id, 
	 NULL as answer_ethic_branch_id1, 
	 NULL as answer_ethic_branch_id2, 
	 NULL as answer_ethic_branch_id3, 
	 NULL as answer_ethic_branch_id4, 
	 NULL as answer_ethic_branch_id5, 
	 NULL as answer_ethic_branch_id6, 
	 NULL as answer_ethic_branch_id7, 
	 NULL as answer_ethic_branch_id8, 
	 NULL as answer_ethic_branch_id9, 
	 NULL as answer_ethic_branch_id10, 
	 NULL as answer_ethic_branch_id11, 
	 NULL as answer_ethic_branch_id12, 
	 NULL as answer_ethic_branch_id13, 
	 NULL as answer_ethic_branch_id14, 
	 NULL as answer_ethic_branch_id15, 
	 NULL as answer_ethic_branch_id16, 
	 NULL as status 
	FROM 
	(
	 SELECT 
	  tbl_order_detail.product_id,
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id 
	 FROM
	  tbl_order_detail 
	  LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
	  LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
	  LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id 
	 WHERE 
	  tbl_order_detail.member_id='?' /* ユーザ指定 */ 
	  AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
	  AND tbl_product_add.product_type_add ='2' 
	  AND tbl_product_live_training.training_kind_flg='1'
	  AND tbl_product_live_training.sponsor='|1|'
	 GROUP BY 
	  tbl_order_detail.product_id, 
	  tbl_product.product_name 
	) as t2 


	/* 日弁連以外 */
	UNION ALL 
	SELECT 
	 product_id,
	 product_name, 
	 order_detail_id, 
	 NULL as answer_ethic_branch_id1, 
	 NULL as answer_ethic_branch_id2, 
	 NULL as answer_ethic_branch_id3, 
	 NULL as answer_ethic_branch_id4, 
	 NULL as answer_ethic_branch_id5, 
	 NULL as answer_ethic_branch_id6, 
	 NULL as answer_ethic_branch_id7, 
	 NULL as answer_ethic_branch_id8, 
	 NULL as answer_ethic_branch_id9, 
	 NULL as answer_ethic_branch_id10, 
	 NULL as answer_ethic_branch_id11, 
	 NULL as answer_ethic_branch_id12, 
	 NULL as answer_ethic_branch_id13, 
	 NULL as answer_ethic_branch_id14, 
	 NULL as answer_ethic_branch_id15, 
	 NULL as answer_ethic_branch_id16, 
	 NULL as status 
	FROM 
	(
	 SELECT 
	  tbl_order_detail.product_id,
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id 
	 FROM
	  tbl_order_detail 
	  LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
	  LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id 
	  LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id 
	 WHERE 
	  tbl_order_detail.member_id='?' /* ユーザ指定 */ 
	  AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
	  AND tbl_product_add.product_type_add ='2' 
	  AND tbl_product_live_training.training_kind_flg='1'
	  AND tbl_product_live_training.sponsor<>'|1|'
	 GROUP BY 
	  tbl_order_detail.product_id, 
	  tbl_product.product_name 
	) as t3 


	/* 倫理研修 */
	UNION ALL 
	SELECT 
	 product_id,
	 product_name, 
	 order_detail_id, 
	 answer_ethic_branch_id1, 
	 answer_ethic_branch_id2, 
	 answer_ethic_branch_id3, 
	 answer_ethic_branch_id4, 
	 answer_ethic_branch_id5, 
	 answer_ethic_branch_id6, 
	 answer_ethic_branch_id7, 
	 answer_ethic_branch_id8, 
	 answer_ethic_branch_id9, 
	 answer_ethic_branch_id10, 
	 answer_ethic_branch_id11, 
	 answer_ethic_branch_id12, 
	 answer_ethic_branch_id13, 
	 answer_ethic_branch_id14, 
	 answer_ethic_branch_id15, 
	 answer_ethic_branch_id16, 
	 status 
	FROM 
	(
	 SELECT 
	  tbl_ethic_question_history.product_id as product_id, 
	  tbl_product.product_name, 
	  NULL as order_detail_id, 
	  tbl_ethic_question_history.answer_ethic_branch_id1, 
	  tbl_ethic_question_history.answer_ethic_branch_id2, 
	  tbl_ethic_question_history.answer_ethic_branch_id3, 
	  tbl_ethic_question_history.answer_ethic_branch_id4, 
	  tbl_ethic_question_history.answer_ethic_branch_id5, 
	  tbl_ethic_question_history.answer_ethic_branch_id6, 
	  tbl_ethic_question_history.answer_ethic_branch_id7, 
	  tbl_ethic_question_history.answer_ethic_branch_id8, 
	  tbl_ethic_question_history.answer_ethic_branch_id9, 
	  tbl_ethic_question_history.answer_ethic_branch_id10, 
	  tbl_ethic_question_history.answer_ethic_branch_id11, 
	  tbl_ethic_question_history.answer_ethic_branch_id12, 
	  tbl_ethic_question_history.answer_ethic_branch_id13, 
	  tbl_ethic_question_history.answer_ethic_branch_id14, 
	  tbl_ethic_question_history.answer_ethic_branch_id15, 
	  tbl_ethic_question_history.answer_ethic_branch_id16, 
	  tbl_ethic_question_history.status 
	 FROM 
	  tbl_ethic_question_history 
	  LEFT JOIN tbl_product ON tbl_ethic_question_history.product_id=tbl_product.product_id 
	 WHERE
	  tbl_ethic_question_history.student_id='?' /* ユーザ指定 */ 
	 UNION ALL 
	 SELECT 
	  tbl_order_detail.product_id, 
	  tbl_product.product_name, 
	  tbl_order_detail.order_detail_id, 
	  NULL as answer_ethic_branch_id1, 
	  NULL as answer_ethic_branch_id2, 
	  NULL as answer_ethic_branch_id3, 
	  NULL as answer_ethic_branch_id4, 
	  NULL as answer_ethic_branch_id5, 
	  NULL as answer_ethic_branch_id6, 
	  NULL as answer_ethic_branch_id7, 
	  NULL as answer_ethic_branch_id8, 
	  NULL as answer_ethic_branch_id9, 
	  NULL as answer_ethic_branch_id10, 
	  NULL as answer_ethic_branch_id11, 
	  NULL as answer_ethic_branch_id12, 
	  NULL as answer_ethic_branch_id13, 
	  NULL as answer_ethic_branch_id14, 
	  NULL as answer_ethic_branch_id15, 
	  NULL as answer_ethic_branch_id16, 
	  NULL as status 
	 FROM 
	  tbl_order_detail 
	  LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id 
	  LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id 
	  LEFT JOIN tbl_product_live_training ON tbl_order_detail.product_id=tbl_product_live_training.product_id 
	 WHERE 
	  tbl_order_detail.member_id='?' /* ユーザ指定 */
	  AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
	  AND tbl_product_add.product_type_add='2' 
	  AND tbl_product_live_training.ethic_flg='1' 
	) as t4 
 ) as arr_pid 
 LEFT JOIN tbl_product ON arr_pid.product_id=tbl_product.product_id 
 LEFT JOIN tbl_product_add ON arr_pid.product_id=tbl_product_add.product_id 
 LEFT JOIN tbl_order_detail ON arr_pid.order_detail_id=tbl_order_detail.order_detail_id 
 LEFT JOIN mtb_bar_association ON tbl_order_detail.bar_association_id=mtb_bar_association.id 
 LEFT JOIN mtb_bar_association_branch ON tbl_order_detail.bar_association_branch_id=mtb_bar_association_branch.bar_association_branch_id 
 LEFT JOIN tbl_product_live_training ON arr_pid.product_id=tbl_product_live_training.product_id 
 LEFT JOIN tbl_product_ethic_training ON tbl_product.product_id=tbl_product_ethic_training.product_id 
group by product_id /* 重複表示されるから一つにまるめる */
ORDER BY 
 arr_pid.product_id DESC ,
 tbl_order_detail.create_date DESC 
 ";
			
			$query_count = $this->db->query($sql, 
					array(
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],

						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
					)
			);
			$result_data['all_count'] = $query_count->num_rows();
			
			// 表示レコード取得 - SQL生成
			$sql_limit  = " LIMIT {$param['offset']}, {$param['rowcount']} ";
			$query_record = $this->db->query($sql.$sql_limit, 
					array(
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],

						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
						(int)$param['student_id'],
					)
			);
			if ($query_record->num_rows() > 0){
				//$result_data['show_record'] = $query_record->result_array();
				
				$tmp_result_data = $query_record->result_array();
				if ($tmp_result_data){
					// 弁護士会マスタ取得
					$mtb_bar_association = array();
					$sql = "SELECT id, name FROM mtb_bar_association";
					$query_record_mtb_bar_association = $this->db->query($sql);
					if ($query_record_mtb_bar_association->num_rows() > 0){
						$result_data_mtb_bar_association = $query_record_mtb_bar_association->result_array();
						foreach ($result_data_mtb_bar_association as $val){
							$mtb_bar_association[$val['id']] = $val['name'];
						}
					}
					
					foreach ($tmp_result_data as $key => $val){
						$result_data['show_record'][$key] = $val;
						
						// eラーニングの場合、商品の動画数と動画完了数を取得
						if ($val['product_type_add'] == '1'){
							$product_video_count  = 0; // 商品に紐づいた動画数
							$complete_video_count = 0; // 動画視聴完了数
							$in_video_id = '';
							
							// 商品に紐づいた動画数の取得
							if ($val['contents_contents1']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents1'].',';}
							if ($val['contents_contents2']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents2'].',';}
							if ($val['contents_contents3']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents3'].',';}
							if ($val['contents_contents4']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents4'].',';}
							if ($val['contents_contents5']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents5'].',';}
							if ($val['contents_contents6']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents6'].',';}
							if ($val['contents_contents7']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents7'].',';}
							if ($val['contents_contents8']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents8'].',';}
							if ($val['contents_contents9']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents9'].',';}
							if ($val['contents_contents10']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents10'].',';}
							if ($val['contents_contents11']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents11'].',';}
							if ($val['contents_contents12']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents12'].',';}
							if ($val['contents_contents13']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents13'].',';}
							if ($val['contents_contents14']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents14'].',';}
							if ($val['contents_contents15']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents15'].',';}
							if ($val['contents_contents16']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents16'].',';}
							if ($val['contents_contents17']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents17'].',';}
							if ($val['contents_contents18']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents18'].',';}
							if ($val['contents_contents19']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents19'].',';}
							if ($val['contents_contents20']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents20'].',';}
							if ($val['contents_contents21']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents21'].',';}
							if ($val['contents_contents22']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents22'].',';}
							if ($val['contents_contents23']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents23'].',';}
							if ($val['contents_contents24']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents24'].',';}
							if ($val['contents_contents25']!=''){$product_video_count += 1;$in_video_id .= $val['contents_contents25'].',';}
							$result_data['show_record'][$key]['product_video_count'] = $product_video_count;
							
							// 動画視聴完了数の取得
							if ($in_video_id != ''){
								$query_record = array();
								$arr_report_user_video_viewed = array();
								$sql = "SELECT COUNT(*) AS complete_video_count FROM report_user_video_viewed WHERE student_id = ? AND video_id IN(" . rtrim($in_video_id, ',') . ") AND complete_flag = 1";
								$query_record = $this->db->query($sql, 
										array(
											(int)$param['student_id']
										)
								);
								if ($query_record->num_rows() > 0){
									$arr_report_user_video_viewed = $query_record->result_array();
									$complete_video_count = (int)$arr_report_user_video_viewed[0]['complete_video_count'];
								}
							}
							$result_data['show_record'][$key]['complete_video_count'] = $complete_video_count;
							
							// 全て動画を見終わっていない場合、視聴済み時間の更新
							// complete_flag=0：視聴済み時間を使用(duration_reading)
							// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
							if ($product_video_count != $complete_video_count){
								$result_data['show_record'][$key]['all_duration_reading'] = 0;
								
								$query_record = array();
								$arr_report_user_video_viewed = array();
								$sql = "SELECT TIME_TO_SEC(duration) AS duration_sec, TIME_TO_SEC(duration_reading) AS duration_reading_sec, complete_flag FROM report_user_video_viewed WHERE student_id = ? AND video_id IN(" . rtrim($in_video_id, ',') . ")";
								$query_record = $this->db->query($sql, 
										array(
											(int)$param['student_id']
										)
								);
								if ($query_record->num_rows() > 0){
									$arr_report_user_video_viewed = $query_record->result_array();
									foreach ($arr_report_user_video_viewed as $aruvv_val){
										if ($aruvv_val['complete_flag'] == '1'){
											$result_data['show_record'][$key]['all_duration_reading'] += $aruvv_val['duration_sec'];
										} else {
											$result_data['show_record'][$key]['all_duration_reading'] += $aruvv_val['duration_reading_sec'];
										}
									}
								}
							}
							
							// 設問付きeラーニングの場合
							$result_data['show_record'][$key]['product_kind_flg'] = '0';
							$result_data['show_record'][$key]['max_percent_video'] = '0';
							$result_data['show_record'][$key]['max_val_exam'] = '0';
							$result_data['show_record'][$key]['answer_val_exam'] = '0';
							$result_data['show_record'][$key]['gouhi'] = '-';
							
							$test_flg = false;
							$query_record_1 = array();
							$arr_result_1 = array();
							$sql = "SELECT product_kind_flg FROM tbl_product_elearning WHERE product_id = ?";
							$query_record_1 = $this->db->query($sql,
								array(
									(int)$val['product_id'],
								)
							);
							if ($query_record_1->num_rows() > 0){
								$arr_result_1 = $query_record_1->result_array();
								foreach ($arr_result_1 as $ar_val){
									$result_data['show_record'][$key]['product_kind_flg'] = $ar_val['product_kind_flg'];
									if($ar_val['product_kind_flg'] == '3'){
										$test_flg = true;
									}
								}
							}
							if($test_flg){
								// 研修動画のパーセントを取得(視聴完了していない動画の最大値)
								if ($in_video_id != ''){
									$query_record_1 = array();
									$arr_result_1 = array();
									$sql = "
									SELECT
									  MAX(percent) AS max_percent_video
									FROM
									  report_user_video_viewed
									WHERE
									  student_id = ?
									  AND video_id IN(" . rtrim($in_video_id, ',') . ")
									  AND complete_flag = 0
									";
									$query_record_1 = $this->db->query($sql,
										array(
											(int)$param['student_id'],
										)
									);
									if ($query_record_1->num_rows() > 0){
										$arr_result_1 = $query_record_1->result_array();
										foreach ($arr_result_1 as $ar_val){
											if(!is_null($ar_val['max_percent_video'])){
												$result_data['show_record'][$key]['max_percent_video'] = $ar_val['max_percent_video'];
											}
										}
									}
								}
								
								// 全設問数を取得
								$query_record_1 = array();
								$arr_result_1 = array();
								$sql = "
								SELECT
								  COUNT(*) AS max_val_exam
								FROM
								  rel_product_contents
								WHERE
								  product_id = ?
								";
								$query_record_1 = $this->db->query($sql,
									array(
										(int)$val['product_id'],
									)
								);
								if ($query_record_1->num_rows() > 0){
									$arr_result_1 = $query_record_1->result_array();
									foreach ($arr_result_1 as $ar_val){
										$result_data['show_record'][$key]['max_val_exam']  = $ar_val['max_val_exam'];
									}
								}
								
								// 設問の解答数を取得
								$query_record_1 = array();
								$arr_result_1 = array();
								$sql = "
								SELECT
								  passing_flg,
								  criteria_type,
								  criteria_value
								FROM
								  exam_answer
								WHERE
								  status=0
								  AND product_id = ?
								  AND student_id = ?
								GROUP BY
								  contents_no
								";
								$query_record_1 = $this->db->query($sql,
									array(
										(int)$val['product_id'],
										(int)$param['student_id'],
									)
								);
								if ($query_record_1->num_rows() > 0){
									$result_data['show_record'][$key]['answer_val_exam'] = (string)$query_record_1->num_rows();
								}
								
								// 合否
								$query_record_1 = array();
								$arr_result_1 = array();
								$sql = "
								SELECT
								  *
								FROM
								  rel_product_contents
								WHERE
								  product_id = ?
								";
								$query_record_1 = $this->db->query($sql,
									array(
										(int)$val['product_id'],
									)
								);
								if ($query_record_1->num_rows() > 0){
									$arr_result_1 = $query_record_1->result_array();
									foreach ($arr_result_1 as $ar_val){
										// 判定基準が設定されているか確認
										if($ar_val['exam_id_test']>0){
											$query_record_2 = array();
											$arr_result_2 = array();
											$sql = "SELECT criteria_type FROM exam WHERE exam_id = ?";
											$query_record_2 = $this->db->query($sql,
												array(
													(int)$ar_val['exam_id_test'],
												)
											);
											if ($query_record_2->num_rows() > 0){
												$arr_result_2 = $query_record_2->result_array();
												foreach ($arr_result_2 as $ar_val2){
													if($ar_val2['criteria_type']>0){
														$result_data['show_record'][$key]['gouhi'] = '未実施';
														break;
													}
												}
											}
										}
									}
									if($result_data['show_record'][$key]['gouhi']!='-'){
										// すべてのテストを受講済みかどうかチェック
										$query_record_1 = array();
										$arr_result_1 = array();
										$product_contents_total = 0;
										$sql = "SELECT COUNT(*) AS count FROM rel_product_contents WHERE product_id = ? AND exam_id_test > 0";
										$query_record_1 = $this->db->query($sql,
											array(
												(int)$val['product_id'],
											)
										);
										if ($query_record_1->num_rows() > 0){
											$arr_result_1 = $query_record_1->result_array();
											foreach ($arr_result_1 as $ar_val){
												$product_contents_total = $ar_val['count'];
											}
										}
										
										$query_record_1 = array();
										$arr_result_1 = array();
										$exam_answer_total = 0;
										$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id = ? AND student_id = ? GROUP BY contents_no";
										$query_record_1 = $this->db->query($sql,
											array(
												(int)$val['product_id'],
												(int)$param['student_id'],
											)
										);
										if ($query_record_1->num_rows() > 0){
											$exam_answer_total = (string)$query_record_1->num_rows();
											$arr_result_1 = $query_record_1->result_array();
										}
										
										// すべて受講済みの場合に合否判定する
										if($product_contents_total>0 && $exam_answer_total>0 && ($product_contents_total==$exam_answer_total)){
											$result_data['show_record'][$key]['gouhi'] = '合格';
											foreach($arr_result_1 as $ar_val){
												if($ar_val['passing_flg']=='0'){
													$result_data['show_record'][$key]['gouhi'] = '不合格';
													break;
												}
											}
										}
									}
								}
							}
							
						// 会場研修の場合、実施日を取得
						} else if ($val['product_type_add'] == '2'){
							$product_id = $val['product_id'];
							
							// 旧システムから移行されてきたデータの場合
							if (strtotime($val['create_date']) < strtotime('2013-12-03 00:00:00')){
								if ($product_id != ''){
									// 弁護士会マスタが設定されている場合
									if (!empty($mtb_bar_association)){
										$result_data['show_record'][$key]['bar_association_name'] = '';
										
										// 主催弁護士会を更新
										$product_id_old = $product_id - 10000;
										if (strpos($product_id_old, '-') === false){
											$sql = "SELECT ROOT_ID FROM import_kenshu_count WHERE KENSHU_ID = '$product_id_old'";
											$query_record_root_id = $this->db->query($sql);
											if ($query_record_root_id->num_rows() > 0){
												$result_data_root_id = array();
												$result_data_root_id = $query_record_root_id->result_array();
												$root_id = $result_data_root_id[0]['ROOT_ID'];
												// 親が存在した場合は、親の主催を表示
												if ($root_id != '0'){
													// 表示用の弁護士会を取得
													$now_product_id = $root_id + 10000;
													$sql = "SELECT sponsor FROM tbl_product_live_training WHERE product_id = '$now_product_id'";
													$query_record_sponsor = $this->db->query($sql);
													if ($query_record_sponsor->num_rows() > 0){
														$result_data_sponsor = array();
														$result_data_sponsor = $query_record_sponsor->result_array();
														$val['sponsor'] = $result_data_sponsor[0]['sponsor'];
														$arr_sponsor = array();
														$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
														foreach ($arr_sponsor as $sponsor){
															$result_data['show_record'][$key]['bar_association_name'].= $mtb_bar_association[$sponsor] . '<br />';
														}
														$result_data['show_record'][$key]['bar_association_name'] = trim($result_data['show_record'][$key]['bar_association_name'], '<br />');
													}
													
												// 親が存在しない場合は、自身の主催を表示
												} else {
													$arr_sponsor = array();
													$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
													foreach ($arr_sponsor as $sponsor){
														$result_data['show_record'][$key]['bar_association_name'].= $mtb_bar_association[$sponsor] . '<br />';
													}
													$result_data['show_record'][$key]['bar_association_name'] = trim($result_data['show_record'][$key]['bar_association_name'], '<br />');
												}
											}
										}
									}
									
									// 開催日を更新
									$sql = "SELECT dates FROM rel_product_bar_association_branch WHERE product_id = '$product_id' LIMIT 1";
									$query_record_rel_product_bar_association = $this->db->query($sql);
									if ($query_record_rel_product_bar_association->num_rows() > 0){
										$result_data_rel_product_bar_association = array();
										$result_data_rel_product_bar_association = $query_record_rel_product_bar_association->result_array();
										$result_data['show_record'][$key]['dates'] = $result_data_rel_product_bar_association[0]['dates'];
									}
									
								}
								
							// 新システム作成のデータの場合
							} else {
								// 弁護士会マスタが設定されている場合
								if (!empty($mtb_bar_association)){
									// 表示用の弁護士会を取得
									$result_data['show_record'][$key]['bar_association_name'] = '';
									if ($val['sponsor'] != ''){
										$arr_sponsor = array();
										$arr_sponsor = explode('|', trim($val['sponsor'], '|'));
										foreach ($arr_sponsor as $sponsor){
											$result_data['show_record'][$key]['bar_association_name'].= $mtb_bar_association[$sponsor] . '<br />';
										}
										$result_data['show_record'][$key]['bar_association_name'] = trim($result_data['show_record'][$key]['bar_association_name'], '<br />');
									}
								}
							}
						}
					}
				} else {
					$result_data['show_record'] = $query_record->result_array();
				}
				
			}else{
				$result_data['show_record'] = array();
			}
			
		} catch (Exception $e) {
			return $result_data;
		}
		
		return $result_data;
	}
}
?>
