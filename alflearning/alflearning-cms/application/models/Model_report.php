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
		
		// 表示レコード取得 - SQL生成
		$sql = '';
		$sql = "SELECT 
		 * 
		FROM 
		 tbl_student_report 
		WHERE 1=1 
		 AND student_id=?
		 AND product_type_add = '1' 
		 AND duration_reading_sec>0
		 AND duration_sec>0
		";
		$sql = "SELECT 
		  tbl_student_report.* 
		 ,tbl_product_live_training.sponsor
		FROM 
		 tbl_student_report 
		 LEFT JOIN tbl_product_live_training ON tbl_student_report.product_id=tbl_product_live_training.product_id 
		WHERE 1=1 
		 AND student_id=?
		 AND product_type_add = '1' 
		 AND duration_reading_sec>0
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
			$tmp_result_data = $query_record->result_array();
			foreach ($tmp_result_data as $key => $val){
				$result_data['show_record'][$key] = $val;
				$result_data['show_record'][$key]["web_flg"] = 1;
				$result_data['show_record'][$key]["max_reading_date"] = $val["last_viewd_date"];
				$result_data['show_record'][$key]["all_alfstream_duration_sec"] = $val["duration_sec"];
				$result_data['show_record'][$key]["all_duration_reading"] = $val["duration_reading_sec"];

				$product_video_count  = 0; // 商品に紐づいた動画数
				$complete_video_count = 0; // 動画視聴完了数
				$in_video_id = '';
				$all_duration_reading = 0;
					
				// 商品に紐づいた動画数の取得
				$result_data['show_record'][$key]['product_video_count'] = 0;
				if( !empty($val["video_ids"]) ){
					$result_data['show_record'][$key]['product_video_count'] = count(explode ( ",", $val["video_ids"]));
				}
				$result_data['show_record'][$key]['complete_video_count'] = 0;
				if( !empty($val["viewd_video_ids"]) ){
					$result_data['show_record'][$key]['complete_video_count'] = count(explode ( ",", $val["viewd_video_ids"]));
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
					$result_data['show_record'][$key]['max_percent_video'] = 0;
					if( intval($result_data['show_record'][$key]['complete_video_count'])>0 && intval($result_data['show_record'][$key]['product_video_count'])>0 ){
						$result_data['show_record'][$key]['max_percent_video'] = intval(intval($result_data['show_record'][$key]['complete_video_count']) / intval($result_data['show_record'][$key]['product_video_count']) * 100);
					}
					$result_data['show_record'][$key]['max_val_exam']  = 0;
					if( !empty($result_data['show_record'][$key]['exam_id_test']) ){
						$result_data['show_record'][$key]['max_val_exam']  = count(explode ( ",", $result_data['show_record'][$key]['exam_id_test']));
					}
					$result_data['show_record'][$key]['answer_val_exam'] = 0;
					if( !empty($result_data['show_record'][$key]['passing_exam_id']) ){
						$result_data['show_record'][$key]['answer_val_exam'] = count(explode ( ",", $result_data['show_record'][$key]['passing_exam_id']));
					}

					$result_data['show_record'][$key]['gouhi'] = '-';
					if($result_data['show_record'][$key]['max_val_exam']>0 && $result_data['show_record'][$key]['answer_val_exam']==0){
						$result_data['show_record'][$key]['gouhi'] = '未実施';
					} elseif($result_data['show_record'][$key]['max_val_exam']==$result_data['show_record'][$key]['answer_val_exam']){
						$result_data['show_record'][$key]['gouhi'] = '合格';
					} else {
						$result_data['show_record'][$key]['gouhi'] = '不合格';
					}
					
				}
			}
		} else {
			$result_data['all_count'] = 0;
			$result_data['show_record'] = array();
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     tbl_ethic_question_history.student_id=?  
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
			     report_user_video_viewed.student_id=? /* 対象のユーザIDの指定 */
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
			   tbl_ethic_question_history.student_id=? /* 対象のユーザIDの指定 */
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
			   tbl_order_detail.member_id=? /* 対象のユーザIDの指定 */
			   AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 )
			   AND tbl_product_add.product_type_add='2' 
			   AND tbl_product_live_training.ethic_flg='1' 
			 ) AS arr_pid 
			 LEFT JOIN tbl_ethic_question_history ON arr_pid.product_id=tbl_ethic_question_history.product_id AND tbl_ethic_question_history.student_id=? /* 対象のユーザIDの指定 */ 
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
								$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id WHERE tbl_ethic_question.failure_flg=0 AND tbl_product_ethic_training.product_id=?";
								$question_count = 0;
								$query_question = $this->db->query($sql_sub, [ $arr_temp[$i]["product_id"] ]);
								if ($query_question->num_rows() > 0) {
									$question_count = $query_question->num_rows();
								}
								$arr_temp[$i]["question_count1"] = $question_count;

								$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id WHERE tbl_ethic_question.failure_flg=1 AND tbl_product_ethic_training.product_id=?";
								$question_count = 0;
								$query_question = $this->db->query($sql_sub, [ $arr_temp[$i]["product_id"] ]);
								if ($query_question->num_rows() > 0) {
									$question_count = $query_question->num_rows();
								}
								$arr_temp[$i]["question_count2"] = $question_count;
								$sql_sub = "
								    SELECT
								     report_user_video_viewed.video_id
								    FROM
								     report_user_video_viewed
								     LEFT JOIN tbl_ethic_question ON report_user_video_viewed.video_id=tbl_ethic_question.video_id
								     LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id
								    WHERE
								         report_user_video_viewed.student_id=? 
								     AND tbl_product_ethic_training.product_id=?
								    GROUP BY
								     report_user_video_viewed.video_id
								";
								$ethic_video_viewed_count = 0;
								$query_video_viewed_count = $this->db->query($sql_sub, [ (int)$param['student_id'], $arr_temp[$i]["product_id"] ]);
								if ($query_video_viewed_count->num_rows() > 0) {
									$ethic_video_viewed_count = $query_video_viewed_count->num_rows();
								}
								$arr_temp[$i]["ethic_video_viewed_count"] = $ethic_video_viewed_count;
								//--------------------------------------------------------------------------------
							}

						} else {
							//新問題
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
								$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id WHERE tbl_ethic_question.failure_flg=0 AND tbl_product_ethic_training.product_id=?";
								$question_count = 0;
								$query_question = $this->db->query($sql_sub, [ $arr_temp[$i]["product_id"] ]);
								if ($query_question->num_rows() > 0) {
									$question_count = $query_question->num_rows();
								}
								$arr_temp[$i]["question_count1"] = $question_count;


								$sql_sub = "SELECT tbl_ethic_question.ethic_question_id FROM tbl_ethic_question LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id WHERE tbl_ethic_question.failure_flg=1 AND tbl_product_ethic_training.product_id=?";
								$question_count = 0;
								$query_question = $this->db->query($sql_sub, [ $arr_temp[$i]["product_id"] ]);
								if ($query_question->num_rows() > 0) {
									$question_count = $query_question->num_rows();
								}
								$arr_temp[$i]["question_count2"] = $question_count;


								$sql_sub = "
								    SELECT
								     report_user_video_viewed.video_id
								    FROM
								     report_user_video_viewed
								     LEFT JOIN tbl_ethic_question ON report_user_video_viewed.video_id=tbl_ethic_question.video_id
								     LEFT JOIN tbl_product_ethic_training ON tbl_ethic_question.ethic_group_id=tbl_product_ethic_training.ethic_group_id
								    WHERE
								         report_user_video_viewed.student_id=? 
								     AND tbl_product_ethic_training.product_id=?
								    GROUP BY
								     report_user_video_viewed.video_id
								";
								$ethic_video_viewed_count = 0;
								$query_video_viewed_count = $this->db->query($sql_sub, [ (int)$param['student_id'], $arr_temp[$i]["product_id"] ]);
								if ($query_video_viewed_count->num_rows() > 0) {
									$ethic_video_viewed_count = $query_video_viewed_count->num_rows();
								}
								$arr_temp[$i]["ethic_video_viewed_count"] = $ethic_video_viewed_count;
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
	function get_user_all($param)
	{
	    // 引数設定
	    $param = array_merge(
	        array(
	            'student_id' => 0,
	            'offset'     => 0,
	            'rowcount'   => 0,
	        ),
	        $param
	    );

	    $result_data = array(
	        'show_record' => array(),
	        'all_count'   => 0,
	    );

	    $student_id = (int)$param['student_id'];
	    if ($student_id <= 0) {
	        return $result_data;
	    }

	    // 「全て」は他タブと同じ結果を結合する（＝定義を一致させる）
	    // ※各メソッドが LIMIT を必ず付ける作りなので、十分大きい rowcount で全件取得してから PHP でページング
	    $fetch_param = $param;
	    $fetch_param['offset']   = 0;
	    $fetch_param['rowcount'] = 1000000;

	    $all = array();

	    // 0: eラーニング
	    $tmp = $this->get_user_elearning($fetch_param);
	    foreach (($tmp['show_record'] ?? array()) as $row) {
	        $row['__detail_type'] = 0;
	        $all[] = $this->_normalize_all_row($row);
	    }

	    // 1: ライブ実務
	    $tmp = $this->get_user_live_training($fetch_param);
	    foreach (($tmp['show_record'] ?? array()) as $row) {
	        $row['__detail_type'] = 1;
	        $all[] = $this->_normalize_all_row($row);
	    }

	    // 2: 日弁連以外主催
	    $tmp = $this->get_user_nichibenren_except_host($fetch_param);
	    foreach (($tmp['show_record'] ?? array()) as $row) {
	        $row['__detail_type'] = 2;
	        $all[] = $this->_normalize_all_row($row);
	    }

	    // 3: 倫理研修
	    $tmp = $this->get_user_ethic_training($fetch_param);
	    foreach (($tmp['show_record'] ?? array()) as $row) {
	        $row['__detail_type'] = 3;
	        $all[] = $this->_normalize_all_row($row);
	    }

	    // 並び順（現状の「全て」に近い感じで “新しい順”）
	    // 優先：create_date → max_reading_date → dates → product_id
	    usort($all, function($a, $b) {
	        $ad = $this->_sort_datetime($a);
	        $bd = $this->_sort_datetime($b);

	        if ($ad !== $bd) {
	            return ($ad < $bd) ? 1 : -1; // 降順
	        }

	        $apid = (int)($a['product_id'] ?? 0);
	        $bpid = (int)($b['product_id'] ?? 0);
	        return $bpid <=> $apid; // 降順
	    });

	    $result_data['all_count'] = count($all);

	    // ページング（controller の offset/rowcount と一致）
	    $offset   = (int)$param['offset'];
	    $rowcount = (int)$param['rowcount'];
	    if ($rowcount <= 0) {
	        // 念のため
	        $rowcount = 50;
	    }

	    $result_data['show_record'] = array_slice($all, $offset, $rowcount);

	    return $result_data;
	}

	/**
	 * 「全て」ビューで参照されがちなキーを揃える（無いものはデフォルトを入れる）
	 */
	private function _normalize_all_row($row)
	{
	    // デフォルト枠（ビューで未定義 Notice を出さない）
	    $defaults = array(
	        'product_id'                => null,
	        'product_name'              => '',
	        'product_type_add'          => null,

	        'order_detail_id'           => null,
	        'create_date'               => null,
	        'web_flg'                   => null,
	        'participation_flg'         => null,

	        'bar_association_id'        => null,
	        'bar_association_name'      => '',
	        'bar_association_branch_id' => null,
	        'bar_association_branch_name' => '',

	        'dates'                     => null,
	        'max_reading_date'          => null,

	        'all_alfstream_duration_sec'=> 0,
	        'all_duration_reading'      => 0,

	        'product_video_count'       => 0,
	        'complete_video_count'      => 0,

	        'ethic_flg'                 => null,
	        'sponsor'                   => null,
	        'answer_count'              => 0,
	        '2nd_answer_count'          => 0,
	        'video_viewed_count'        => 0,
	        'status'                    => null,
	    );

	    // 既存値で上書き
	    $row = array_merge($defaults, (array)$row);

	    // 別名で入っている値を寄せる（元メソッド差異吸収）
	    // eラーニング系
	    if (!empty($row['duration_sec']) && (int)$row['all_alfstream_duration_sec'] === 0) {
	        $row['all_alfstream_duration_sec'] = (int)$row['duration_sec'];
	    }
	    if (!empty($row['duration_reading_sec']) && (int)$row['all_duration_reading'] === 0) {
	        $row['all_duration_reading'] = (int)$row['duration_reading_sec'];
	    }
	    if (!empty($row['last_viewd_date']) && empty($row['max_reading_date'])) {
	        $row['max_reading_date'] = $row['last_viewd_date'];
	    }

	    // 倫理研修系
	    if (isset($row['all_training_video_count']) && (int)$row['product_video_count'] === 0) {
	        $row['product_video_count'] = (int)$row['all_training_video_count'];
	    }
	    if (isset($row['all_training_video_viewed_count']) && (int)$row['complete_video_count'] === 0) {
	        $row['complete_video_count'] = (int)$row['all_training_video_viewed_count'];
	    }

	    // get_user_elearning 側で付けているキーがあるなら尊重（無ければそのまま）
	    // 例: web_flg / max_reading_date / all_duration_reading など

	    return $row;
	}

	/**
	 * ソート用：日時っぽいものを epoch にする
	 */
	private function _sort_datetime($row)
	{
	    $candidates = array(
	        $row['create_date'] ?? null,
	        $row['max_reading_date'] ?? null,
	        $row['dates'] ?? null,
	    );
	    foreach ($candidates as $dt) {
	        if (!empty($dt)) {
	            $t = strtotime($dt);
	            if ($t !== false) return $t;
	        }
	    }
	    return 0;
	}

}
?>
