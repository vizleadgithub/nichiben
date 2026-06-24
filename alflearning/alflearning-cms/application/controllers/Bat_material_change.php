<?
#[AllowDynamicProperties]
class Bat_material_change extends CI_Controller{
	var $db_old;
	var $db_new;

	var $old_dir_class	= '/alflearning-data-from-data/class';
	var $new_dir_class	= '/alflearning-data/class';

	var $new_dir_material	= '/alflearning-data/material';

	function __construct(){
		parent::__construct();

		$this->db = null;

		$this->db_old = $this->load->database('mysql://ace:8epxja2ran@localhost/alflearning', true);
		$this->db_new = $this->load->database('mysql://ace:8epxja2ran@localhost/alflearning_new', true);

		//初期化
		$this->db_new->query('TRUNCATE TABLE material');
		$this->db_new->query('TRUNCATE TABLE class_material');
	}

	public function index(){
	}

	public function start(){
		print "start";

		$query = $this->db_old->query(
			' SELECT material.*, class.teacher_id as class_teacher_id FROM material'.
			' LEFT JOIN class USING(class_id)'.
			' WHERE material.status = 1',
			array(
			)
		);
		foreach($query->result_array() as $row){
			print "process -> {$row['material_id']}\n";

			$masterFiles = array();
			exec("ls {$this->old_dir_class}/{$row['class_id']}/{$row['material_id']}/master.*", $masterFiles);
			$_exsistsMasterFile = count($masterFiles);

			//所有者チェック
			$_fileOwner = '';
			if($row['teacher_id']){
				if(!$_exsistsMasterFile){
					$_fileOwner = 'teacher';
				}
				else{
					$_fileOwner = 'teacher_submit';
				}
			}
			else if($row['student_id']){
				$_fileOwner = 'student';
			}
			else{
				$_fileOwner = 'super_user';
			}

			$res = $this->db_new->query($this->db_new->insert_string('material', array(
				'material_name'			=> $row['material_name'],
				'material_logic_name'	=> $row['material_logic_name'],
				'material_caption'		=> $row['material_caption'],
				'school_id'				=> $row['school_id'],
				'teacher_id'			=> ($_fileOwner == 'super_user' ? $row['class_teacher_id'] : $row['teacher_id']),
				'page_num'				=> $row['page_num'],
				'status'				=> $row['status'],
				'added_at'				=> $row['added_at'],
				'update_at'				=> $row['update_at'],
			)));
			$materialId = $this->db_new->insert_id();
			$res = $this->db_new->query($this->db_new->insert_string('class_material', array(
				'class_id'				=> $row['class_id'],
				'material_name'			=> $row['material_name'],
				'material_logic_name'	=> $row['material_logic_name'],
				'teacher_id'			=> ($_fileOwner == 'super_user' ? $row['class_teacher_id'] : $row['teacher_id']),
				'student_id'			=> $row['student_id'],
				'page_num'				=> $row['page_num'],
				'submit_flag'			=> ($_fileOwner == 'teacher_submit' ? 1 : 0),	//生徒は全部0
				'status'				=> $row['status'],
				'added_at'				=> $row['added_at'],
				'update_at'				=> $row['update_at'],
			)));
			$classMaterialId = $this->db_new->insert_id();

			$_newPath = "{$this->new_dir_class}/{$row['class_id']}";
			$_oldPath = "{$this->old_dir_class}/{$row['class_id']}";
			if(!file_exists($_newPath)){
				mkdir($_newPath);
			}
			`cp {$_oldPath}/whiteboard.jpg $_newPath/`;

			$_oldPath .= "/{$row['material_id']}";
			if($_fileOwner == 'student'){
				//生徒は全部0
				$_newPath .= "/student_{$row['student_id']}";
				if(!file_exists($_newPath)){
					mkdir($_newPath);
				}
				$_newPath .= "/$classMaterialId";
				if(!file_exists($_newPath)){
					mkdir($_newPath);
				}
				$_newPath .= "/my";
				if(!file_exists($_newPath)){
					mkdir($_newPath);
				}
			}
			else{
				$_newPath .= "/teacher_{$row['class_teacher_id']}";
				if(!file_exists($_newPath)){
					mkdir($_newPath);
				}
				$_newPath .= "/$classMaterialId";
				if(!file_exists($_newPath)){
					mkdir($_newPath);
				}
			}

			exec("cp -R {$_oldPath}/* {$_newPath}/");

			//master material
			$_new_dir_material = "{$this->new_dir_material}/{$materialId}";
			if(!file_exists($_new_dir_material)){
				mkdir($_new_dir_material);
			}
			exec("cp -R {$_oldPath}/* {$_new_dir_material}/");
		}

		print "\n";
		exec("find {$this->new_dir_material}/ -name '[0-9]????????????.jpg' | xargs rm -f");
	}
}
?>
