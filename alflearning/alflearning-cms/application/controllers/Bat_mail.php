<?
#[AllowDynamicProperties]
class Bat_mail extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->library('email');
		$this->load->helper('unit');

		ini_set('display_errors', 'On');
		ini_set('log_errors', 'On');
		ini_set('error_reporting', E_ALL);
	}

	public function index(){
	}

	public function class_notification($nextStr = ''){
		$NOW = time();
		$nextStrToDateTime_start	= '';
		$nextStrToDateTime_end		= '';
		switch($nextStr){
			case 'week':	//１日一回実行
				$nextStrToDateTime_start	= date("Y-m-d 00:00:00", $NOW + (60 * 60 * 24 * 7));
				$nextStrToDateTime_end		= date("Y-m-d 23:59:59", $NOW + (60 * 60 * 24 * 7));
				break;
			case 'day':		//１日一回実行
				$nextStrToDateTime_start	= date("Y-m-d 00:00:00", $NOW + (60 * 60 * 24 * 1));
				$nextStrToDateTime_end		= date("Y-m-d 23:59:59", $NOW + (60 * 60 * 24 * 1));
				break;
			case 'hour':	//10分に一回実行（5, 15, 25, 35, 45, 55にして欲しい）
				$i = (int)date("i", $NOW + (60 * 60 * 1));
				$_start = date("Y-m-d H:".(floor($i/10)*10).":00", $NOW + (60 * 60 * 1));
				$nextStrToDateTime_start	= $_start;
				$nextStrToDateTime_end		= date("Y-m-d H:i:s", strtotime($_start)+(60*10-1));
				break;
		}
		if(!$nextStrToDateTime_start){
			exit('nextStr not found!!');
		}

		$query = $this->db->query(
			' SELECT class.*, teacher.*'.
			' FROM class'.
			' LEFT JOIN teacher USING (teacher_id)'.
			' WHERE 1 = 1'.
			' AND class.class_open BETWEEN ? AND ?'.
			' AND class.status <> 9',
			array(
				$nextStrToDateTime_start,
				$nextStrToDateTime_end,
			)
		);

		$classLists = array();
		foreach($query->result_array() as $class){
			$query_students = $this->db->query(
				' SELECT student.*'.
				' FROM student'.
				' LEFT JOIN student_lecture_class USING (student_id)'.
				' WHERE 1 = 1'.
				' AND student_lecture_class.class_id = ?'.
				' AND student.status <> 9',
				array(
					$class['class_id']
				)
			);

			foreach($query_students->result_array() as $student){
				$body = $this->load->view('mail_templates/class_notification', array(
					'nextStr'	=> $nextStr,
					'class'		=> $class,
					'student'	=> $student,
				), true);
				$this->email->clear();
				$this->email->initialize(array(
					'charset'	=> 'ISO-2022-JP',
				));
				$this->email->to($student['student_email']);
				$this->email->from('info@alflearning.com');
				$this->email->subject($class['teacher_name'].'講師の授業「'.$class['class_name'].'」が控えています');
				$this->email->message(mb_convert_encoding($body, "ISO-2022-JP", "UTF-8"));
				$this->email->send();
			}
		}
	}
}
?>
