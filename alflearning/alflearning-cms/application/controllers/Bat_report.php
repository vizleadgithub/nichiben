<?
#[AllowDynamicProperties]
class Bat_report extends CI_Controller{
	private $nowTime;

	function __construct(){
		parent::__construct();

		//DB接続
		$this->load->database();

		$this->load->library('ftp');
		$this->load->helper('json');
		$this->load->library('LibAmount');
		$this->load->model('modelschoolcontract');

		$this->nowTime = time();
	}

	//======================================================================//
	//= functions
	//======================================================================//
	//----------------------------------------------------------------------//
	//- 学校一覧取得
	//----------------------------------------------------------------------//
	private function _getSchools(){
		$schools = array();

		$query = $this->db->query(
			' SELECT * FROM school',
			array(
			)
		);
		foreach($query->result_array() as $school){
			$school['contract_param'] = obj2arr(json_decode($school['contract_param']));
			array_push($schools, $school);
		}

		return $schools;
	}

	//----------------------------------------------------------------------//
	//- 次に取得すべきレポートの日付を取得する
	//----------------------------------------------------------------------//
	private function _getNextReportDate($param){
		$param = array_merge(array(
			'school_id'	=> 1,
			'tableName'	=> 'report_live',
			'type'		=> 'time',
		), $param);

		//保存されている一番新しいレポートを探す
		$query = $this->db->query(
			' SELECT * FROM '.$param['tableName'].
			' WHERE school_id = ?'.
			' AND type = ?'.
			' ORDER BY date DESC'.
			' LIMIT 0, 1',
			array(
				(int) $param['school_id'],
				$param['type'],
			)
		);
		$newestReport = $query->row_array();
		if(!$newestReport){	//なければ登録してる一番古い授業がある月の始めでいく
			$query = $this->db->query(
				' SELECT * FROM class'.
				' WHERE school_id = ?'.
				' AND status <> 9'.
				' ORDER BY class_open ASC'.
				' LIMIT 0, 1',
				array(
					(int) $param['school_id'],
				)
			);
			$oldestClass = $query->row_array();
			if(!$oldestClass){	//それもなければ今月頭
				$createReportDate = date('Y-m-01', $this->nowTime);
			}
			else{
				$createReportDate = date('Y-m-01', strtotime($oldestClass['class_open']));
			}
		}
		else{
			$createReportDate = date('Y-m-d', strtotime($newestReport['date']) + (24*60*60));	//次の日
		}

		return $createReportDate;
	}

	//----------------------------------------------------------------------//
	//- 生授業レポート（授業時間） 日次
	//----------------------------------------------------------------------//
	private function _setSchoolLiveReport_daily_time($param){
		$param = array_merge(array(
			'school_id'	=> 1,
		), $param);

		//レポート取得日付
		$createReportDate = $this->_getNextReportDate(array(
			'school_id'	=> $param['school_id'],
			'tableName'	=> 'report_live',
			'type'		=> 'time',
		));

		//見つかったのが今日だったら終了
		if($createReportDate == date('Y-m-d', $this->nowTime)){
			return;
		}

		//日付ごとにループ
		$_reportTime = strtotime($createReportDate);
		for(;date('Y-m-d', $_reportTime) != date('Y-m-d', $this->nowTime); $_reportTime += (24*60*60)){
			$report = $this->libamount->getClassTimeAmountReserved(array(
				'school_id'	=> $param['school_id'],
				'startdate'	=> date('Y/m/d 00:00:00', $_reportTime),
				'enddate'	=> date("Y/m/d 23:59:59", $_reportTime),
			));

			$res = $this->db->query($this->db->insert_string('report_live', array(
				'school_id'	=> $param['school_id'],
				'type'		=> 'time',
				'date'		=> date("Y-m-d", $_reportTime),
				'value'		=> $report['total']['time'],
			)));
		}
	}

	//----------------------------------------------------------------------//
	//- 生授業レポート（ストレージ使用量）
	//----------------------------------------------------------------------//
	private function _setSchoolLiveReport_daily_strage($param){
		$param = array_merge(array(
			'school_id'	=> 1,
		), $param);

		//前日以降のレポートがすでにあればスルー
		$query = $this->db->query(
			' SELECT * FROM report_live'.
			' WHERE school_id = ?'.
			' AND type = ?'.
			' AND date >= ?'.
			' LIMIT 0, 1',
			array(
				(int) $param['school_id'],
				'strage',
				date("Y-m-d", $this->nowTime-(24*60*60)),
			)
		);

		if($query->row_array()){
			return;
		}

		$res = $this->db->query($this->db->insert_string('report_live', array(
			'school_id'	=> $param['school_id'],
			'type'		=> 'strage',
			'date'		=> date("Y-m-d", $this->nowTime-(24*60*60)),	//前日分として記録（朝４時とかに実行する想定）
			'value'		=> $this->libamount->getClassMaterialAmount(array(
				'school_id'	=> $param['school_id'],
			)),
		)));
	}

	//----------------------------------------------------------------------//
	//- 生授業レポート（授業時間） 分単位
	//----------------------------------------------------------------------//
	private function _setSchoolLiveReport_min($param){
		$param = array_merge(array(
			'school'			=> array(),
		), $param);

		$this->modelschoolcontract->initialize($param['school']);

		$contract_param = $this->modelschoolcontract->getContractParam(array('serviceKey' => 'live'));
		if(!$this->modelschoolcontract->enableService(array('serviceKey'=>'live'))){
			return;
		}
//		if($contract_param['contract'] != 'fixation'){
//			return;
//		}

		$nowAmountTime = 0;

		//履歴から
		$report = $this->libamount->getClassHistoryReport(array(
			'school_id'	=> $param['school']['school_id'],
			'startdate'	=> date('Y/m/1', $this->nowTime),	//今月頭
			'enddate'	=> date("Y/m/t", $this->nowTime),	//今月末（今日以降は帰ってこない前提）
		));
		$nowAmountTime += $report['total']['time'];

		//予約分
		$report = $this->libamount->getClassTimeAmountReserved(array(
			'school_id'	=> $param['school']['school_id'],
			'startdate'	=> date('Y/m/d 00:00:00', $this->nowTime),	//今日
			'enddate'	=> date("Y/m/t 23:59:59", $this->nowTime),	//今月末
		));
		$nowAmountTime += $report['total']['time'];

		//time and strage
		$this->modelschoolcontract->updateContractParam(array(
			'serviceKey'	=> 'live',
			'contractParam'	=> array(
				'time_now'		=> $nowAmountTime,
				'strage_now'	=> $this->libamount->getClassMaterialAmount(array(
					'school_id'	=> $param['school']['school_id'],
				)),
			),
		));
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	private function _getStreamReport($param){
		$param = array_merge(array(
			'school'		=> array(),
			'ym'			=> date("Y-m"),
		), $param);

		$this->modelschoolcontract->initialize($param['school']);
		$alfstreamParam = $this->modelschoolcontract->getAlfstreamParam();

		$this->load->library('Curl');
		$this->load->helper('json');

		$content = $this->curl->simple_get('http://'.$this->config->item('video_stream_api_domain_name').'/v1/usage/', array(
			'authkey'		=> $alfstreamParam['auth_key'],
			'ym'			=> $param['ym'],
		));

		$decodedContent = json_decode($content);
		return $decodedContent->dat;
	}

	//----------------------------------------------
	//
	//----------------------------------------------
	private function _getFtpDirSize($param){
	}

	//======================================================================//
	//= methods
	//======================================================================//
	public function index(){
	}

	//----------------------------------------------------------------------//
	//- 生授業レポート（日単位）
	//----------------------------------------------------------------------//
	public function daily_live(){
		$schools = $this->_getSchools();
		foreach($schools as $school){
			//月額以外はスルー
//			if($school['contract'] != 'fixation'){
//				continue;
//			}
			//月額以外はスルー
			if($school['contract_param']['live']['contract'] != 'fixation'){
				continue;
			}

			$this->_setSchoolLiveReport_daily_time(array(
				'school_id'	=> $school['school_id'],
			));
			$this->_setSchoolLiveReport_daily_strage(array(
				'school_id'	=> $school['school_id'],
			));
		}
	}
	//----------------------------------------------------------------------//
	//- 生授業レポート（分単位）
	//----------------------------------------------------------------------//
	public function min_live(){
		exec('ps auxw | grep min_live | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$schools = $this->_getSchools();
		foreach($schools as $school){
			//月額以外はスルー
//			if($school['contract'] != 'fixation'){
//				continue;
//			}
			//月額以外はスルー
			if($school['contract_param']['live']['contract'] != 'fixation'){
				continue;
			}

			$this->_setSchoolLiveReport_min(array(
				'school'			=> $school,
			));
		}
	}

	//----------------------------------------------------------------------//
	//- ビデオ授業レポート（日単位）
	//----------------------------------------------------------------------//
	public function daily_video(){
		$schools = $this->_getSchools();
		foreach($schools as $school){
			//月額以外はスルー
//			if($school['contract'] != 'fixation'){
//				continue;
//			}
			//月額以外はスルー
			if($school['contract_param']['video']['contract'] != 'fixation'){
				continue;
			}

			//レポート取得日付
			$createReportDate = $this->_getNextReportDate(array(
				'school_id'	=> $school['school_id'],
				'tableName'	=> 'report_video',
				'type'		=> 'stream',
			));

			//見つかったのが今日だったら終了
			if($createReportDate == date('Y-m-d', $this->nowTime)){
				return;
			}

			//月毎
			$_getReportDate = date('Y-m-1', strtotime($createReportDate));
			while(date('Y-m', strtotime($_getReportDate)) != date("Y-m", strtotime(date("Y-m-01", $this->nowTime) . "+1 month"))){
				$reports = $this->_getStreamReport(array(
					'school'	=> $school,
					'ym'		=> date('Y-m',strtotime($_getReportDate)),
				));

				$_trafficTotal = 0;	//取り溜めといて、前日のログが来たらcontractに入れる
				$_start = 0;
				foreach($reports->log as $_log){
					//あたりを引くまでループして、あたったら書き込みスタート
					$_trafficTotal += ($_log->traffic ? $_log->traffic : 0);
					if($_log->day == $createReportDate){
						$_start = 1;
					}
					if($_log->day == date("Y-m-d", $this->nowTime-(24*60*60))){	//昨日が来たら、そこまで貯めてた今月分のログをcontractに入れる
						$this->modelschoolcontract->initialize($school);
						$this->modelschoolcontract->updateContractParam(array(
							'serviceKey'	=> 'video',
							'contractParam'	=> array(
								'strage_now'	=> ($_log->storage ? $_log->storage : 0),
								'stream_now'	=> $_trafficTotal,
							)
						));
					}
					if(strtotime("$_log->day 00:00:00") >= strtotime(date('Y-m-d 00:00:00', $this->nowTime))){	//今日以降は履歴じゃないので取らない
						continue;
					}
					if($_start){
						$res = $this->db->query($this->db->insert_string('report_video', array(
							'school_id'	=> $school['school_id'],
							'type'		=> 'stream',
							'date'		=> $_log->day,
							'value'		=> ($_log->traffic ? $_log->traffic : 0),
						)));
						$res = $this->db->query($this->db->insert_string('report_video', array(
							'school_id'	=> $school['school_id'],
							'type'		=> 'strage',
							'date'		=> $_log->day,
							'value'		=> ($_log->storage ? $_log->storage : 0),
						)));
					}
				}
				$_getReportDate = date("Y-m-1", strtotime($_getReportDate . "+1 month"));
			}

			// ○stream・strage 合計値をDBに保存された値で再計算してshcoolテーブルに再設置する
			// report_video に保存された stream・strage を取得
			$query_report_video = $this->db->query(
				'SELECT * '.
				'  FROM report_video '.
				' WHERE report_video.school_id = ? '.
				'   AND report_video.date LIKE ? '.
				' ORDER BY report_video.date ASC ',
				array(
					$school['school_id'],
					"".date('Y-m',strtotime($createReportDate))."%",
				)
			);
			// ○今月分の streamの合計値、今月最終日のstrage を計算
			$sum_stream = 0;
			$sum_strage = 0;
			foreach($query_report_video->result_array() as $row){
				if($row['type']=='stream'){
					$sum_stream += $row['value'];
				}
				if($row['type']=='strage'){
					$sum_strage  = $row['value'];
				}
			}
			// ○school の contract_param に再設置
			$this->modelschoolcontract->initialize($school);
			$this->modelschoolcontract->updateContractParam(array(
				'serviceKey'	=> 'video',
				'contractParam'	=> array(
					'stream_now'	=> $sum_stream,
					'strage_now'	=> $sum_strage,
				)
			));

		}
	}

	//----------------------------------------------------------------------//
	//- 図書室レポート（日単位）
	//- リクエストされて日時(Y-m-d)を取得してログ解析してそれぞれの学校にinsert
	//----------------------------------------------------------------------//
	public function daily_book_library($date = ''){
		if(!$date){
			$date = date('Y-m-d', $this->nowTime-(24*60*60));	//前日
		}

		//stream
		$_alfStreamServerURL = $this->config->item('stream_get_url');
		$_alfStreamServerURL_path = preg_replace('/^http:\/\/.+?\//i', '', $_alfStreamServerURL);
		if(!$_alfStreamServerURL_path){
			return;
		}

		$this->ftp->connect(array(
			'hostname' => $this->config->item('stream_ftp'),
			'username' => $this->config->item('stream_ftp_user'),
			'password' => $this->config->item('stream_ftp_pass'),
			'port'     => 21,
			'passive'  => false,
			'debug'    => false,
		));

		$serverLogFile = "/logs/dl/access_log.".date('Ymd', strtotime($date));
		$localTempFile = '/tmp/alflearning-book_library_log_'.time();
		$this->ftp->download($serverLogFile, $localTempFile, 'binary');

		if(!file_exists($localTempFile)){
			die("ftp file get error -> $serverLogFile");
		}

		$schools = array();
		$fp = fopen($localTempFile, 'r');
		while(feof($fp) == false) {
			$line = fgets($fp);
			if(preg_match('/\[(.+?)\].+?'.preg_quote($_alfStreamServerURL_path, '/').'.*school_([0-9]+).+? HTTP\/1.1" 200 ([0-9]+) /', $line, $matches)){
				list($matcheStr, $matchDateTime, $matchSchoolId, $matchSize) = $matches;
				if(!isset($schools[$matchSchoolId])){
					$schools[$matchSchoolId] = 0;
				}
				$schools[$matchSchoolId] += $matchSize;
			}
		}

		$this->db->CI_DB_driver(array('db_debug'=>false));	//duplicate error前提で叩くと、これ入れとかないと止まる
		foreach($schools as $schoolId => $value){
			$res = $this->db->query($this->db->insert_string('report_book_library', array(
				'school_id'	=> $schoolId,
				'type'		=> 'stream',
				'date'		=> $date,
				'value'		=> $value,
			)));

			$query = $this->db->query(
				' SELECT SUM(report_book_library.value) AS total FROM report_book_library'.
				' WHERE school_id = ?'.
				' AND type = ?'.
				' AND date LIKE ?'.
				' GROUP BY school_id',
				array(
					(int) $schoolId,
					'stream',
					date("Y-m%", $this->nowTime),
				)
			);
			$row = $query->row_array();
			if($row){
				$this->modelschoolcontract->initialize($schoolId);
				$this->modelschoolcontract->updateContractParam(array(
					'serviceKey'	=> 'book_library',
					'contractParam'	=> array(
					'stream_now'	=> $row['total'],	//dayでする
//						'strage_now'	=> $size,	//minでする
					),
				));
			}
		}

		$this->ftp->close();

		unlink($localTempFile);

		//strage
		//前日以降のレポートがすでにあればスルー
		$schools = $this->_getSchools();
		foreach($schools as $school){
			//月額以外はスルー
//			if($school['contract'] != 'fixation'){
//				continue;
//			}
			//月額以外はスルー
			if($school['contract_param']['book_library']['contract'] != 'fixation'){
				continue;
			}
			$query = $this->db->query(
				' SELECT * FROM report_book_library'.
				' WHERE school_id = ?'.
				' AND type = ?'.
				' AND date >= ?'.
				' LIMIT 0, 1',
				array(
					(int) $school['school_id'],
					'strage',
					date("Y-m-d", $this->nowTime-(24*60*60)),
				)
			);
			if($query->row_array()){
				continue;
			}

			$res = $this->db->query($this->db->insert_string('report_book_library', array(
				'school_id'	=> $school['school_id'],
				'type'		=> 'strage',
				'date'		=> date("Y-m-d", $this->nowTime-(24*60*60)),	//前日分として記録（朝４時とかに実行する想定）
				'value'		=> $this->libamount->getBookLibraryAmount(array(
					'school_id'	=> $school['school_id'],
				)),
			)));
		}
	}
	//----------------------------------------------------------------------//
	//- 図書室レポート（分単位）
	//- ストレージ使用量のみ
	//----------------------------------------------------------------------//
	public function min_book_library(){
		exec('ps auxw | grep min_book_library | grep -v " grep " | grep -v "/bin/sh" ', $outputs);
		if(count($outputs) > 1){
			echo "前回バッチが起動中でした\n";
			return;
		}

		$schools = $this->_getSchools();
		foreach($schools as $school){
			//月額以外はスルー
//			if($school['contract'] != 'fixation'){
//				continue;
//			}
			//月額以外はスルー
			if($school['contract_param']['book_library']['contract'] != 'fixation'){
				continue;
			}

			$this->modelschoolcontract->initialize($school);
			$size = $this->libamount->getBookLibraryAmount(array(
				'school_id'	=> $school['school_id'],
			));

			$this->modelschoolcontract->updateContractParam(array(
				'serviceKey'	=> 'book_library',
				'contractParam'	=> array(
//					'stream_now'	=> 0,	//dayでする
					'strage_now'	=> $size,
				),
			));
		}
	}
}
?>
