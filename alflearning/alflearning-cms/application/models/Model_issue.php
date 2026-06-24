<?php
#[AllowDynamicProperties]
class Model_issue extends CI_Model  
{
	//----------------------------------------------
	//プライベート変数宣言
	//----------------------------------------------
	private $supportExts  = array(				//対応拡張子とタイプ
					'pdf'		=> 'pdf',
					'jpg'		=> 'image',
					'jpeg'		=> 'image',
					'png'		=> 'image',
					'gif'		=> 'image',
					'ppt'		=> 'doc',
					'pptx'		=> 'doc',
					'doc'		=> 'doc',
					'docx'		=> 'doc',
					'xls'		=> 'doc',
					'xlsx'		=> 'doc',
					'txt'		=> 'doc',
					'rtf'		=> 'doc',
					'odt'		=> 'doc',
					'sxw'		=> 'doc',
					'wpd'		=> 'doc',
					'csv'		=> 'doc',
					'tsv'		=> 'doc',
					'ods'		=> 'doc',
					'sxc'		=> 'doc',
					'odp'		=> 'doc',
					'sxi'		=> 'doc',
				);
	
	//----------------------------------------------
	//コンストラクタ
	//----------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		//DB接続
		$this->load->database();
	}
	//----------------------------------------------
	//課題一覧取得
	//----------------------------------------------
	function get_issue_list($param) {
		// load language
		$this->lang->load('common');
		
		//引数設定
		$param = array_merge(
			array(
				'school_id'		=> 0,
				'offset'		=> 0,
				'rowcount'		=> 10,
				's_cource'		=> 0,
				's_free_word'	=> '',
			),
			$param
		);

		//SQL生成
		$query = $this->db->query(
			' SELECT SQL_CALC_FOUND_ROWS '.
			'        issue.issue_id        AS issue_id '.
			'       ,issue.issue_name      AS issue_name '.
			'       ,issue.issue_caption   AS issue_caption '.
			"       ,DATE_FORMAT(issue.issue_open  , '%Y%m%d%H%i%s') AS issue_open ".
			"       ,DATE_FORMAT(issue.issue_close , '%Y%m%d%H%i%s') AS issue_close ".
			'       ,issue.public_flag     AS public_flag '.
			'       ,issue.school_id       AS school_id '.
			'       ,issue.teacher_id      AS teacher_id '.
			'       ,issue.status          AS status '.
			'       ,issue.update_at       AS update_at '.
			'       ,teacher.teacher_name  AS teacher_name '.
			'   FROM issue LEFT JOIN teacher ON teacher.teacher_id = issue.teacher_id '.
			'  WHERE issue.school_id = ? '.
			'    AND issue.status <> 9 '.
			($param['s_free_word'] ?
				' AND ('.
				' 	issue.issue_name LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				'	OR issue.issue_caption LIKE '.'"%'.$this->db->escape_like_str($param['s_free_word']).'%"'.
				' )'
				: ''
			).
			($param['s_cource'] > 0 ?
				' AND issue.issue_id IN (SELECT issue_id FROM rel_issue_lecture WHERE cource_id = '.$this->db->escape($param['s_cource']).' )'
				: ''
			).
			' ORDER BY issue.update_at DESC'.
			' LIMIT ?, ?',
			array(
				(int) $param['school_id'],
				(int) $param['offset'],
				(int) $param['rowcount'],
			)
		);

		//データリターン
		if ($query->num_rows() > 0) {
			$cnt = $this->db->query('SELECT FOUND_ROWS() as rowcount');
			$cnt = $cnt->row_array();
			return array(
				'cnt'	=> $cnt['rowcount'],
				'items'	=> $query->result_array(),
			);
		} else {
			return array(
				'cnt'	=> 0,
				'items'	=> array(),
			);
		}
	}

	//----------------------------------------------
	//一件取得
	//----------------------------------------------
	function get_issue($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
						),
						$param
					);
		
		//SQL生成
		$query = $this->db->query(
			' SELECT issue.issue_id        AS issue_id '.
			'       ,issue.issue_name      AS issue_name '.
			'       ,issue.issue_caption   AS issue_caption '.
			"       ,DATE_FORMAT(issue.issue_open  , '%Y/%m/%d %H:%i:%s') AS issue_open ".
			"       ,DATE_FORMAT(issue.issue_close , '%Y/%m/%d %H:%i:%s') AS issue_close ".
			'       ,issue.public_flag     AS public_flag '.
			'       ,issue.school_id       AS school_id '.
			'       ,issue.teacher_id      AS teacher_id '.
			'       ,issue.status          AS status '.
			'       ,issue.update_at       AS update_at '.
			'       ,teacher.teacher_name  AS teacher_name '.
			'   FROM issue LEFT JOIN teacher ON teacher.teacher_id = issue.teacher_id '.
			"  WHERE issue.issue_id = {$this->db->escape($param['issue_id'])} ".
			'    AND issue.status <> 9 '
		);
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//新規登録・更新処理
	//----------------------------------------------
	function update_issue($param){
		//引数設定
		$param = array_merge(
						array(
							'data' => array(),
						),
						$param
					);
		$data = $param['data'];

		if ($data['update_flg'] == 0){
			//新規
			$data = array_merge(array(
					'issue_name'		=> 'no value',
					'issue_caption'		=> '',
					'issue_open'		=> date("Y/m/d H:i:s"),
					'issue_close'		=> date("Y/m/d H:i:s"),
					'public_flag'		=> 0,
					'school_id'			=> 0,
					'teacher_id'		=> 0,
					'status'			=> 0,
					'update_at'			=> date("Y/m/d H:i:s"),
			), $data);
			
			$res = $this->db->query($this->db->insert_string('issue', array(
				//	'issue_id'			=> '',
					'issue_name'		=> $data['issue_name'],
					'issue_caption'		=> $data['issue_caption'],
					'issue_open'		=> $data['issue_open'],
					'issue_close'		=> $data['issue_close'],
					'public_flag'		=> $data['public_flag'],
					'school_id'			=> $data['school_id'],
					'teacher_id'		=> $data['teacher_id'],
					'status'			=> $data['status'],
					'update_at'			=> $data['update_at'],
				)
			));
			
			$lastInsertId = $this->db->insert_id();
			
			return array(
				'lastInsertId'	=> $lastInsertId,
			);
		}else{
			//修正
			$wDate = date('Y/m/d H:i:s');
			$sql = "UPDATE
						issue
					SET 
						issue_name     = ?,
						issue_caption  = ?,
						issue_open     = ?,
						issue_close    = ?,
						public_flag    = ?,
						school_id      = ?,
						teacher_id     = ?,
						update_at      = ?
					WHERE
						issue_id = ?";
			$this->db->trans_start();
			$this->db->query($sql, 
								array(
									$data['issue_name'],
									$data['issue_caption'],
									$data['issue_open'],
									$data['issue_close'],
									$data['public_flag'],
									$data['school_id'],
									$data['teacher_id'],
									$wDate,
									$data['issue_id']
								));
			$this->db->trans_complete();
			
			return array(
				'lastInsertId'	=> $data['issue_id'],
			);
		}
	}

	//----------------------------------------------
	//削除処理
	//----------------------------------------------
	function delete_item($param){
		//引数設定
		$param = array_merge(
			array(
				'issue_id' => 0,
			),
			$param
		);

		# テーブルへの論理削除（正常なら1）
		$res = $this->db->query($this->db->update_string('issue', array(
				'status'	=> 9,
				'update_at'	=> date('Y/m/d H:i:s'),
			),'issue_id='.$param['issue_id']
		));
		# ファイルの物理削除（論理削除成功が条件）
	//	if($res==1){
	//		$this->load->helper('file');
	//		$command_text = 'rm -rf '.$this->config->item('material_dir').'/'.$param['material_id'];
	//		exec($command_text);
	//	}
		return $res;
	}

	//----------------------------------------------
	// 課題講座テーブルから講座ID取得
	//----------------------------------------------
	function get_issue_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
						),
						$param
					);
		//SQL投入
		$query = $this->db->query("
									SELECT
										cource_id
									FROM
										rel_issue_lecture
									WHERE
										issue_id = {$this->db->escape($param['issue_id'])}
									ORDER BY
										cource_id
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// 課題講座テーブルの登録更新処理
	//----------------------------------------------
	function update_issue_lectures($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		$this->db->trans_start();
		
		//一旦すべて削除
		$sql = "DELETE FROM rel_issue_lecture
				WHERE
					issue_id = ?
				;";
		$this->db->query($sql, 
							array(
								$param['issue_id'],
							));
		
		//選択受講講座を登録
		foreach($data['issue_lectures'] as $cource_id) {
			$sql = "INSERT INTO
						rel_issue_lecture
					(
						issue_id,
						cource_id,
						update_at
					)
					VALUES(?,?,?)
					";
			$this->db->query($sql, 
								array(
									$param['issue_id'],
									$cource_id,
									$wDate
								));
		}
		$this->db->trans_complete();
	}

	//----------------------------------------------
	// 課題雛形テーブルから情報取得
	//----------------------------------------------
	function get_issue_template($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
						),
						$param
					);
		
		$query = $this->db->query("
									SELECT
										issue_template.issue_temp_id         AS issue_temp_id
									   ,issue_template.issue_id              AS issue_id
									   ,issue_template.issue_temp_name       AS issue_temp_name
									   ,issue_template.issue_temp_logic_name AS issue_temp_logic_name
									   ,issue_template.convert_flag          AS convert_flag
									   ,issue_template.status                AS status
									   ,issue_template.update_at             AS update_at
									FROM
										issue_template
									WHERE
										issue_template.status <> 9
									AND
										issue_template.issue_id = {$this->db->escape($param['issue_id'])}
									ORDER BY
										issue_template.issue_temp_id ASC 
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	// 課題雛形テーブルの登録更新処理
	//   雛形ファイルの変換処理を含む
	//----------------------------------------------
	function update_issue_template($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
							'data'       => array(),
						),
						$param
					);
		$data = $param['data'];
		
		//現在日時取得
		$wDate = date('Y/m/d H:i:s');
		
		//課題雛形ファイルの保管ディレクトリの確認及び作成（0777 / apache:apache）
		//  $this->config->item('issue_file_dir') = '/alflearning-data/issue'
		// 【/alflearning-data/issue】【[/teacher】【[/school_{school_id}】【/{issue_id}】【/{issue_temp_id}】【/master-Page1.png】
		$temp_path = $this->config->item('issue_file_dir');
		if(!is_dir($temp_path)){
			mkdir($temp_path, 0777, TRUE);
			chmod($temp_path, 0777);
		}
		$temp_path = $temp_path.'/teacher';
		if(!is_dir($temp_path)){
			mkdir($temp_path, 0777, TRUE);
			chmod($temp_path, 0777);
		}
		$temp_path = $temp_path.'/school_'.$this->libauth->get_school_id();
		if(!is_dir($temp_path)){
			mkdir($temp_path, 0777, TRUE);
			chmod($temp_path, 0777);
		}
		$temp_path = $temp_path.'/'.$param['issue_id'];
		if(!is_dir($temp_path)){
			mkdir($temp_path, 0777, TRUE);
			chmod($temp_path, 0777);
		}

		//print var_dump($data);
		//exit();
		//$a1 = getcwd();
		//print "a1:".$a1.":<br/>";
		$count = -1;
		foreach($data['issue_temp_logic_name'] as $no => $issue_temp_logic_name){
			$count++;

			// 新規⇒課題雛形IDが0以下　　更新・削除⇒課題雛形IDが0より大きい
			if($data['issue_temp_id'][$no] < 0){
				// 新規

				// 課題論理名がない場合、次ループ処理へ
				if( empty($issue_temp_logic_name) ) {
					continue;
				}
				
				// 課題ファイルの指定がない場合、次ループ処理へ
				if($_FILES['issue_temp_file_'.$count]['name']){
				}else{
					continue;
				}
				
				// 課題雛形テーブルへ新規登録 ------------------------------------------------
				//'convert_flag` int(1) NOT NULL default '0' COMMENT '変換フラグ 0:未変換 1:変換済 11:変換失敗',
				$temp_data = array(
						'issue_id'					=> $param['issue_id'],
						'issue_temp_name'			=> 'no value',
						'issue_temp_logic_name'		=> $issue_temp_logic_name,
						'convert_flag'				=> 0,
						'status'					=> 0,
						'update_at'					=> date("Y/m/d H:i:s"),
				);
				$res = $this->db->query($this->db->insert_string('issue_template', array(
						'issue_id'					=> $temp_data['issue_id'],
						'issue_temp_name'			=> $temp_data['issue_temp_name'],
						'issue_temp_logic_name'		=> $temp_data['issue_temp_logic_name'],
						'convert_flag'				=> $temp_data['convert_flag'],
						'status'					=> $temp_data['status'],
						'update_at'					=> $temp_data['update_at'],
					)
				));
				$issue_temp_id = $this->db->insert_id();
				
				// ファイル保存先ディレクトリの作成
				$save_Path = $temp_path.'/'.$issue_temp_id;
				if(!is_dir($save_Path)){
					mkdir($save_Path, 0777, TRUE);
					chmod($save_Path, 0777);
				}
				
				// ファイルのアップロード ----------------------------------------------------
				if($_FILES['issue_temp_file_'.$count]['name']){
					$this->load->library('upload');
					$this->upload->initialize(array(
						'upload_path'   => $save_Path,
						'allowed_types' => '*',
						'overwrite'     => TRUE,
						'remove_spaces' => TRUE,
						'file_name'     => 'master',
					));
					if($this->upload->do_upload('issue_temp_file_'.$count)){
						// アップロード成功時、ファイル名をテーブルに更新
						$upload_data = $this->upload->data();
						chmod($save_Path.'/'.$upload_data['file_name'],0777);
						
						$temp_data['issue_temp_name'] = $upload_data['file_name'];
						$res = $this->db->query($this->db->update_string('issue_template', array(
								'issue_temp_name'	=> $temp_data['issue_temp_name'],
							),'issue_temp_id='.$issue_temp_id
						));
					}else{
						// アップロード失敗時、次ループへ移動
						$res = $this->db->query($this->db->update_string('issue_template', array(
								'convert_flag'	=> 11,
							),'issue_temp_id='.$issue_temp_id
						));
						continue;
					}
				}
				
				// ファイル変換処理 ----------------------------------------------------------
				
				// 変換失敗フラグ初期設定
				$convert_error_flag = 0;

				//ファイル名と拡張子に分離
				$ext  = strtolower(pathinfo($temp_data['issue_temp_name'],PATHINFO_EXTENSION));
				$name = mb_ereg_replace('.'.$ext,'',$temp_data['issue_temp_name'],'i');
				
				//形式のチェック
				if(isset($this->supportExts[$ext])){
					// ディレクトリチェック
					$path         = $save_Path;
					$orgFilePath  = $path.'/'.$temp_data['issue_temp_name'];
					$convFilePath = $orgFilePath;
					$convTempPath = $path.'/converttemp';
					$pdfFilePath  = '';
					
					// 一時保存フォルダの作成
					if(!is_dir($convTempPath)){
						mkdir($convTempPath, 0777, TRUE);
						chmod($convTempPath, 0777);
					}
					
					//オフィスドキュメント系はPDFに一旦変換
					if($this->supportExts[$ext] == 'doc'){
						$currentDir = getcwd();
						
						$res = array();
						chdir('/usr/local/bin/jodconverter-2.2.2/lib');
						exec('java -jar jodconverter-cli-2.2.2.jar -f pdf '.$convFilePath.' 2>&1', $res);
						$convFilePath = mb_ereg_replace('.'.$ext, '.pdf', $convFilePath,'i');
						$pdfFilePath  = $convFilePath;
						
						chdir($currentDir);
					}
					
					//画像へ変換
					$imgTempFiles = array();
					if($this->supportExts[$ext] == 'doc' || $this->supportExts[$ext] == 'pdf'){
					//	exec("nice -n 19 pdftoppm $convFilePath $convTempPath/output.ppm");	// 一括変換、下は１頁～１頁の間を変換
						exec("nice -n 19 pdftoppm -f 1 -l 1 $convFilePath $convTempPath/output.ppm");
						exec("nice -n 19 mogrify -format png $convTempPath/output*.ppm");
					//  Version: ImageMagick 6.7.9-1 2012-08-23 Q16 http://www.imagemagick.org の場合、下記を使用
					//	exec('convert -density 100 +antialias '.$convFilePath.' '.$convTempPath.'/convert.jpg');
					}
					else{
						exec('convert '.$convFilePath.' '.$convTempPath.'/convert.png');
					}
					exec('find '.$convTempPath.'/ -name *.png | sort -n -k 2 -t "-"', $imgTempFiles);
					
					//画像のコピー
					if(!count($imgTempFiles)){
						$convert_error_flag = 1;
					}else{
						// 一時保存フォルダから、ファイルのコピー（その際、リサイズする）
						$distFilePath = "{$path}/{$name}-Page1.png";
						exec('convert -define png:size=680x660 -resize 680x660 '.$imgTempFiles[0].' '.$distFilePath);
						chmod($distFilePath, 0777);

						// 画像サイズ取得
						list($width, $height, $type, $attr) = getimagesize($distFilePath);

						// height不足（横長）に余白追加
						if($height < 660){
							$north_FilePath = "{$path}/{$name}-Page1-north.png";
							$south_FilePath = "{$path}/{$name}-Page1-south.png";
							$north_splice   = 0;
							$south_splice   = 0;
							
							$short_height = 660 - $height;
							$rest_data = $short_height % 2;	// 0 or 1
							if($rest_data == 0){
								$north_splice = $short_height / 2;
								$south_splice = $short_height / 2;
							}else{
								$north_splice =  ($short_height - 1) / 2;
								$south_splice = (($short_height - 1) / 2) + 1;
							}
							
							// 上部に余白追加
							exec('convert -define -background "#ffffff" -gravity north -splice 0x'.$north_splice.' '.$distFilePath.'   '.$north_FilePath);
							chmod($north_FilePath, 0777);
							// 下部に余白追加
							exec('convert -define -background "#ffffff" -gravity south -splice 0x'.$south_splice.' '.$north_FilePath.' '.$south_FilePath);
							chmod($south_FilePath, 0777);
							
							// 一時ファイルの削除
							if($distFilePath != ''){
								unlink($distFilePath);
							}
							if($north_FilePath != ''){
								unlink($north_FilePath);
							}
							
							rename($south_FilePath, $distFilePath);
						}
						
						// width不足（縦長）に余白追加
						if($width < 680){
							$west_FilePath = "{$path}/{$name}-Page1-west.png";
							$east_FilePath = "{$path}/{$name}-Page1-east.png";
							$west_splice   = 0;
							$east_splice   = 0;
							
							$short_width = 680 - $width;
							$rest_data = $short_width % 2;	// 0 or 1
							if($rest_data == 0){
								$west_splice = $short_width / 2;
								$east_splice = $short_width / 2;
							}else{
								$west_splice =  ($short_width - 1) / 2;
								$east_splice = (($short_width - 1) / 2) + 1;
							}
							
							// 左部に余白追加
							exec('convert -define -background "#ffffff" -gravity west -splice '.$west_splice.'x0 '.$distFilePath.'  '.$west_FilePath);
							chmod($west_FilePath, 0777);
							// 右部に余白追加
							exec('convert -define -background "#ffffff" -gravity east -splice '.$east_splice.'x0 '.$west_FilePath.' '.$east_FilePath);
							chmod($east_FilePath, 0777);
							
							// 一時ファイルの削除
							if($distFilePath != ''){
								unlink($distFilePath);
							}
							if($west_FilePath != ''){
								unlink($west_FilePath);
							}
							
							rename($east_FilePath, $distFilePath);
						}
						
						//変換したpdfファイルは削除
						if($pdfFilePath != ''){
							unlink($pdfFilePath);
						}
						
						//変換元ファイルは削除
						if($orgFilePath != ''){
							unlink($orgFilePath);
						}
						
						//一時保存フォルダの削除（画像ファイル含む）
						delete_files($convTempPath);
						rmdir($convTempPath);
						
						// 正常変換の情報をテーブルに保存
						$res = $this->db->query($this->db->update_string('issue_template', array(
								'issue_temp_name'	=> "{$name}-Page1.png",
								'convert_flag'		=> 1,
							),'issue_temp_id='.$issue_temp_id
						));
					}
				}else{
					// 変換対象外のファイルの場合、ファイル削除＋次ループ処理へ
					unlink($save_Path.'/'.$temp_data['issue_temp_name']);
					
					$res = $this->db->query($this->db->update_string('issue_template', array(
							'convert_flag'	=> 11,
						),'issue_temp_id='.$issue_temp_id
					));
				}
			}else{
				if( isset($data['issue_temp_delete'][$no]) ){
				// 削除（テーブル論理削除）（ファイル物理削除）
					$res = $this->db->query($this->db->update_string('issue_template', array(
							'status'	=> 9,
						),'issue_temp_id='.$data['issue_temp_delete'][$no]
					));
				// 削除（雛形ファイル物理削除）
					$deleteFilePath = $temp_path.'/'.$data['issue_temp_delete'][$no];
					delete_files($deleteFilePath);
					rmdir($deleteFilePath);
				}else{
				// 更新（雛形論理名のみ更新）
					$res = $this->db->query($this->db->update_string('issue_template', array(
							'issue_temp_logic_name'	=> $issue_temp_logic_name,
							'update_at'				=> date("Y/m/d H:i:s"),
						),'issue_temp_id='.$data['issue_temp_id'][$no]
					));
				}
			}
		}
		//$a2 = getcwd();
		//print "a2:".$a2.":<br/>";
		//exit();
	}

	//----------------------------------------------
	// 課題提出テーブルから情報取得
	//----------------------------------------------
	function get_issue_submit($param){
		//引数設定
		$param = array_merge(
						array(
							'issue_id' => 0,
						),
						$param
					);
		
		$query = $this->db->query("
									SELECT 
										issue_submit.issue_submit_id         AS issue_submit_id
									   ,issue_submit.issue_id                AS issue_id
									   ,issue_submit.student_id              AS student_id
									   ,issue_submit.issue_submit_name       AS issue_submit_name
									   ,issue_submit.issue_submit_logic_name AS issue_submit_logic_name
									   ,issue_submit.issue_submit_caption    AS issue_submit_caption
									   ,DATE_FORMAT(issue_submit.issue_submit_date, '%Y/%m/%d<br/>%H:%i:%s') AS issue_submit_date 
									   ,issue_submit.status                  AS status
									   ,issue_submit.update_at               AS update_at
									   ,student.student_name                 AS student_name
									FROM 
										issue_submit LEFT JOIN student ON issue_submit.student_id = student.student_id
									WHERE 
										issue_submit.status <> 9
									AND 
										issue_submit.issue_id = {$this->db->escape($param['issue_id'])}
									ORDER BY
										issue_submit.issue_submit_date DESC
								");
		
		//データリターン
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return [];
		}
	}

	//----------------------------------------------
	//課題の権限確認
	// Super User:フルアクセス
	// 学校管理者:学校内資料フルアクセス
	// 講師      :課題管理の権限のある講師
	//----------------------------------------------
	function issue_edit_delete_auth_check($param){
		try{ 
			//引数設定
			$param = array_merge(
				array(
					'login_teacher_id' => $this->session->userdata['cms_master.login.teacher_id'],
					'issue_id'  => 0,
				),
				$param
			);
			
			// Super User権限(-1)：フルアクセスのため、権限有効
			if($param['login_teacher_id'] <= 0){
				$return_data['auth_edit_delete'] = 1;
				$return_data['remarks']          = '';
				return $return_data;
			}
			
			//SQL文作成
			$sql = '';
			$sql .= "SELECT issue.teacher_id     AS teacher_id ";
			$sql .= "      ,teacher.teacher_auth AS teacher_auth ";
			$sql .= "  FROM issue INNER JOIN teacher ON issue.school_id = teacher.school_id ";
			$sql .= " WHERE issue.status        <> 9 ";
			$sql .= "   AND teacher.status      =  0 ";
			$sql .= "   AND issue.issue_id      =  ? ";
			$sql .= "   AND teacher.teacher_id  =  ? ";
			
			//Query実行
			$query = $this->db->query($sql, array(
					$param['issue_id'],
					$param['login_teacher_id']
				)); 
			
			//Data Return
			if ($query->num_rows() > 0){
				// レコードあり
				$row_array = $query->row_array();
				
				// 権限（新）より権限有無を確認
				$work_auth  = unserialize($row_array['teacher_auth']);

				// 学校管理者権限：学校内フルアクセスのため、権限有効
				if($work_auth['school_admin'] == 1){
					$return_data['auth_edit_delete'] = 1;
				}else{
			//	// 一般講師：課題権限あり＋作成した講師の場合に、権限有効
			//		if(($work_auth['issue'] == 1) && ($row_array['teacher_id'] == $param['login_teacher_id'])){
				// 一般講師：課題権限を持つ講師の場合に、権限有効
					if($work_auth['issue'] == 1){
						$return_data['auth_edit_delete'] = 1;
					}else{
						$return_data['auth_edit_delete'] = 0;
					}
				}
				
				$return_data['remarks'] = '';
				return $return_data;
			}else{
				// レコードなし：権限無効
				$return_data['auth_edit_delete']   = 0;
				$return_data['remarks']       = 'no-data';
				return $return_data;
			}
		
		}catch(Exception $e){ 
			// 例外発生：権限無効
			$return_data['auth_edit_delete'] = 0;
			$return_data['remarks']     = $e;
			return $return_data;
		//	throw new Exception();
		}
	}
}
?>
