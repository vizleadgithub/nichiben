<? if (!defined('BASEPATH')) exit('No direct script access allowed');

//=======================================================================//
//= サービス使用量とかを調査する
//=======================================================================//
#[AllowDynamicProperties]
class LibAmount{
	private $ci;

	function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->library('ftp');
	}

	//======================================================================//
	//= functions
	//======================================================================//
	//----------------------------------------------------------------------//
	//- リスト分のディレクトリサイズを返す
	//----------------------------------------------------------------------//
	private function _getDirSize($param){
		$param = array_merge(array(
			'path'	=> '.',
			'names'	=> array(),
		), $param);

		$size = 0;
		foreach($param['names'] as $name){
			if(file_exists("{$param['path']}/$name")){
				$return_var = array();
				exec("du -s {$param['path']}/$name", $return_var);
				if($return_var && preg_match('/^[0-9]+/', $return_var[0], $matches)){
					$size += $matches[0];
				}
			}
		}

		return $size;
	}

	//======================================================================//
	//= public
	//======================================================================//
	//----------------------------------------------------------------------//
	//- 学校全体で使われている図書室資料の現在のストレージ容量
	//- このメソッド自体がループするよ
	//----------------------------------------------------------------------//
	function getBookLibraryAmount($param){
		$param = array_merge(array(
			'school_id'	=> 1,
			'path'		=> '',
			'conn_id'	=> '',
		), $param);

		if(!$param['path']){
			$param['path'] = $this->ci->config->item('stream_ftp_dir').'/school_'.$param['school_id'];
		}

		if(!$param['conn_id']){
			$this->ci->ftp->connect(array(
				'hostname' => $this->ci->config->item('stream_ftp'),
				'username' => $this->ci->config->item('stream_ftp_user'),
				'password' => $this->ci->config->item('stream_ftp_pass'),
				'port'     => 21,
				'passive'  => false,
				'debug'    => false,
			));
			$param['conn_id'] = $this->ci->ftp->conn_id;
		}

		$retSize = 0;

		$items = ftp_rawlist($param['conn_id'], $param['path']);
		if(is_array($items)){
			foreach($items as $item){
				list($permission, $n, $user, $group, $size) = preg_split('/[\s\t]+/', $item);
				$fName = substr($item, 58);
				if(preg_match('/^d/', $permission)){	//directory
					$retSize += $this->getBookLibraryAmount(array(
						'school_id'	=> $param['school_id'],
						'path'		=> $param['path'].'/'.$fName,
						'conn_id'	=> $param['conn_id'],
					));
				}
				else{
					$retSize += $size;
				}
			}
		}
		return $retSize;
	}

	//----------------------------------------------------------------------//
	//- 学校全体で授業に使われている現在のストレージ容量
	//----------------------------------------------------------------------//
	function getClassMaterialAmount($param){
		$param = array_merge(array(
			'school_id'	=> 1,
		), $param);

		$size = 0;

		//material
		$query = $this->ci->db->query(
			' SELECT * FROM material'.
			' WHERE school_id = ?'.
			' AND status <> 9',
			array(
				(int) $param['school_id'],
			)
		);
		$materialIds = array();
		foreach($query->result_array() as $material){
			array_push($materialIds, $material['material_id']);
		}
		$size += $this->_getDirSize(array(
			'path'	=> $this->ci->config->item('material_dir'),
			'names'	=> $materialIds,
		));

		//class_material
		$query = $this->ci->db->query(
			' SELECT * FROM class'.
			' WHERE school_id = ?'.
			' AND status <> 9',
			array(
				(int) $param['school_id'],
			)
		);
		$classIds = array();
		foreach($query->result_array() as $class){
			array_push($classIds, $class['class_id']);
		}
		$size += $this->_getDirSize(array(
			'path'	=> $this->ci->config->item('class_material_dir'),
			'names'	=> $classIds,
		));

		return $size;
	}

	//----------------------------------------------------------------------//
	//- 指定した期間の使用授業レポートを返す
	//----------------------------------------------------------------------//
	function getClassHistoryReport($param){
		$param = array_merge(array(
			'school_id'	=> 1,
			'startdate'	=> date('Y/m/1', time()),	//今月頭
			'enddate'	=> date("Y/m/t", time()),	//今月末
		), $param);

		$query = $this->ci->db->query(
			' SELECT * FROM report_live'.
			' WHERE report_live.school_id = ? '.
			' AND date BETWEEN ? AND ?'.
			' ORDER BY date ASC',
			array(
				$param['school_id'],
				$param['startdate'],
				$param['enddate'],
			)
		);

		$total		= array(
			'time'		=> 0,
			'strage'	=> 0,
		);
		$reportList	= array();
		foreach($query->result_array() as $row){
			if(!isset($reportList[$row['date']])){
				$reportList[$row['date']] = array();
			}
			if(!isset($total[$row['type']])){
				$total[$row['type']] = 0;
			}

			$reportList[$row['date']][$row['type']] = $row['value'];
			$total[$row['type']] += $row['value'];
		}

		return array(
			'total'			=> $total,
			'reportList'	=> $reportList,
		);
	}

	//----------------------------------------------------------------------//
	//- 指定した期間で学校全体で予約している授業一覧と総時間
	//----------------------------------------------------------------------//
	function getClassTimeAmountReserved($param){
		$param = array_merge(array(
			'school_id'	=> 1,
			'startdate'	=> date('Y/m/d 00:00:00', time()),	//今日以降
			'enddate'	=> date("Y/m/t 23:59:59", time()),	//今月月末
		), $param);

		$reserved = array(
			'classList'	=> array(),
			'total'	=> array(
				'time'		=> 0,
			),
		);

		$query = $this->ci->db->query(
			' SELECT class.*, (SELECT COUNT(*) FROM student_lecture_class WHERE student_lecture_class.class_id = class.class_id) AS student_num FROM class'.
			' WHERE class.school_id = ? '.
			' AND class.status = 0'.
			' AND class.class_open >= ? AND class.class_open <= ?'.
			' ORDER BY class.class_open ASC',
			array(
				$param['school_id'],
				$param['startdate'],
				$param['enddate'],
			)
		);

		foreach($query->result_array() as $row){
			array_push($reserved['classList'], $row);
			$reserved['total']['time']	+= (strtotime($row['class_close']) - strtotime($row['class_open'])) * $row['student_num'];
		}

		return $reserved;
	}
}
?>
