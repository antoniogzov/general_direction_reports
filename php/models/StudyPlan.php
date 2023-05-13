<?php
class StudyPlan extends data_conn
{
	private $conn;
	public function __construct() {
		$this->conn = $this->dbConn();
	}

	public function getAssgCoordinator($no_teacher){
		$results = array();

		$query = $this->conn->query("
			SELECT * FROM 

			(SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, cmp.campus_name, acdlvldg.degree, groups.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active
				FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
				INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
				INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
				INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
				INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
				INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
				INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
				INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador

				UNION 

				SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, cmp.campus_name, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
				FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
				INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
				INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
				INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
				INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
				INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
				INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

				UNION 

				SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, cmp.campus_name, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, asg.no_teacher, sbj.id_academic_area, asg.print_school_report_card, asg.assignment_active
				FROM school_control_ykt.assignments AS asg
				INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group 
				INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
				INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
				INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
				INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

			AS u

			WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
			ORDER BY u.group_code
			");

		while ($row = $query->fetch(PDO::FETCH_OBJ)) {
			$results[] = $row;
		}

		return $results;

	}

	public function getcommentsByIdMonthYearAssg($month, $year, $id_assignment) {
		$results = array();

		$stmt = $this->conn->prepare("SELECT * FROM iteach_grades_quantitatives.work_diary WHERE MONTH(comment_to_date) = $month AND YEAR(comment_to_date) = $year AND active_comment = 1 AND id_assignment = $id_assignment");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($rows AS $row){
			$results[] = $row;
		}

		return $results;

	}

	public function getAllcomments() {
		$results = array();

		$stmt = $this->conn->prepare("SELECT t1.*, t3.group_code, t4.name_subject, t4.color_hex, CONCAT(t5.apellido_paterno_colaborador, ' ', t5.apellido_materno_colaborador, ' ', t5.nombres_colaborador) AS teacher_name
			FROM iteach_grades_quantitatives.work_diary AS t1
			INNER JOIN school_control_ykt.assignments AS t2 ON t1.id_assignment = t2.id_assignment
			INNER JOIN school_control_ykt.groups AS t3 ON t2.id_group = t3.id_group
			INNER JOIN school_control_ykt.subjects AS t4 ON t2.id_subject = t4.id_subject
			INNER JOIN colaboradores_ykt.colaboradores AS t5 ON t2.no_teacher = t5.no_colaborador
			WHERE t1.active_comment = 1");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($rows AS $row){
			$results[] = $row;
		}

		return $results;

	}

	public function getCommentsByAssignment($no_teacher) {
		$results = array();

		$stmt = $this->conn->prepare("SELECT t1.*, t3.group_code, t4.name_subject, t4.color_hex, CONCAT(t5.apellido_paterno_colaborador, ' ', t5.apellido_materno_colaborador, ' ', t5.nombres_colaborador) AS teacher_name
			FROM iteach_grades_quantitatives.work_diary AS t1
			INNER JOIN school_control_ykt.assignments AS t2 ON t1.id_assignment = t2.id_assignment
			INNER JOIN school_control_ykt.groups AS t3 ON t2.id_group = t3.id_group
			INNER JOIN school_control_ykt.subjects AS t4 ON t2.id_subject = t4.id_subject
			INNER JOIN colaboradores_ykt.colaboradores AS t5 ON t2.no_teacher = t5.no_colaborador
			WHERE t1.active_comment = 1 AND t2.no_teacher = $no_teacher");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($rows AS $row){
			$results[] = $row;
		}

		return $results;

	}

	public function getInfoCommentByID($eventId) {
		$results = array();

		$stmt = $this->conn->prepare("SELECT t1.*, t3.group_code, t4.name_subject, t4.color_hex, CONCAT(t5.apellido_paterno_colaborador, ' ', t5.apellido_materno_colaborador, ' ', t5.nombres_colaborador) AS teacher_name
			FROM iteach_grades_quantitatives.work_diary AS t1
			INNER JOIN school_control_ykt.assignments AS t2 ON t1.id_assignment = t2.id_assignment
			INNER JOIN school_control_ykt.groups AS t3 ON t2.id_group = t3.id_group
			INNER JOIN school_control_ykt.subjects AS t4 ON t2.id_subject = t4.id_subject
			INNER JOIN colaboradores_ykt.colaboradores AS t5 ON t2.no_teacher = t5.no_colaborador
			WHERE t1.work_diary_id = $eventId");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($rows AS $row){
			$results = $row;
		}

		return $results;

	}

	public function InsertCommentTeacher($sql, $data) {

		$idComment = 0;

		$query = $sql;
		$stmt = $this->conn->prepare($query);
		
		if($stmt->execute($data)){
			$idComment = $this->conn->lastInsertId();
		}

		return $idComment;

	}

	public function updateDeleteCommentTeacher($sql, $data) {

		$result = false;

		$query = $sql;
		$stmt = $this->conn->prepare($query);
		
		if($stmt->execute($data)){
			$result = true;
		}

		return $result;

	}
}