<?php
session_start();
include '../../../general/php/models/Connection.php';
include '../../../general/php/models/GeneralModel.php';
include '../models/cualitatives.php';
include '../models/cualitatives_reports.php';
include '../models/students.php';

date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function reportHighSchoolBangueoloMDAhebrew()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_learning_map_mejanejet = 11;
	$id_academic_area = 2;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_normal = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_evc_normal[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}


	//--- EVC MEJANEJET ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_mejanejet = array();
	$topics_data = array();
	$final_comments_mejanejet = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_mejanejet[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_mejanejet = $topics_data;
	}

	//--- GET COMMENT FINAL ---//
	$comment_director = $cualitatives->getCommentsDirectorMDAByStudent($id_learning_map_mejanejet, $installment, $id_student);

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'results_evc_mejanejet' => $results_evc_mejanejet,
		'comment_director' => $comment_director,
		'final_comments_evc_normal' => $final_comments_evc_normal,
		'final_comments_mejanejet' => $final_comments_mejanejet
	);

	echo json_encode($results);
}

function reportHighSchoolInterlomasMDAhebrew()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_learning_map_mejanejet = 5;
	$id_academic_area = 2;

	$results = false;

	$groups = array();

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_normal = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_evc_normal[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}


	//--- EVC MEJANEJET ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_mejanejet = array();
	$topics_data = array();
	$final_comments_mejanejet = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_mejanejet[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_mejanejet = $topics_data;
	}

	//--- GET COMMENT FINAL ---//
	$comment_director = $cualitatives->getCommentsDirectorMDAByStudent($id_learning_map_mejanejet, $installment, $id_student);

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'results_evc_mejanejet' => $results_evc_mejanejet,
		'comment_director' => $comment_director,
		'final_comments_evc_normal' => $final_comments_evc_normal,
		'final_comments_mejanejet' => $final_comments_mejanejet
	);

	echo json_encode($results);
}

function reportPreparatoryBangueoloMDAhebrew()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_learning_map_mejanejet = 11;
	$id_academic_area = 2;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDAWithout1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_normal = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = "$info_teacher->titleInSchool $info_teacher->name";
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_evc_normal[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}


	//--- EVC MEJANEJET ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_mejanejet = array();
	$topics_data = array();
	$final_comments_mejanejet = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_mejanejet[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_mejanejet = $topics_data;
	}

	//--- GET COMMENT FINAL ---//
	$comment_director = $cualitatives->getCommentsDirectorMDAByStudent($id_learning_map_mejanejet, $installment, $id_student);

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'results_evc_mejanejet' => $results_evc_mejanejet,
		'comment_director' => $comment_director,
		'final_comments_evc_normal' => $final_comments_evc_normal,
		'final_comments_mejanejet' => $final_comments_mejanejet
	);

	echo json_encode($results);
}

function reportPreparatoryBangueoloMDAspanish()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_learning_map_mejanejet = 11;
	$id_academic_area = 1;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsWithoutMDA($group_id, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsWithoutMDA($group_id, $id_academic_area, $id_student);

	$results_evc_normal = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			foreach ($array_assgs as $assg) {
				//print_r($assg);
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_evc_normal[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}


	//--- EVC MEJANEJET ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsMDA1($group_id, $id_learning_map_mejanejet, $id_academic_area);

	$results_evc_mejanejet = array();
	$topics_data = array();
	$final_comments_mejanejet = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}

				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($topic->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_mejanejet[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$results_evc_mejanejet = $topics_data;
	}

	//--- GET COMMENT FINAL ---//
	$comment_director = $cualitatives->getCommentsDirectorMDAByStudent($id_learning_map_mejanejet, $installment, $id_student);

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'results_evc_mejanejet' => $results_evc_mejanejet,
		'comment_director' => $comment_director,
		'final_comments_evc_normal' => $final_comments_evc_normal,
		'final_comments_mejanejet' => $final_comments_mejanejet
	);

	echo json_encode($results);
}

function reportHighSchoolBangueoloMDASpanish()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_academic_area = 1;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsWithoutMDA($group_id, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsWithoutMDA($group_id, $id_academic_area, $id_student);

	$results_evc_normal = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	//--- --- ---//
	$conection = new data_conn;
	$conn = $conection->dbConn();
	//--- --- ---//

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			foreach ($array_assgs as $assg) {
				//print_r($assg);
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$final_comments_evc_normal = array();

		foreach ($array_assgs as $assg) {
			$query = $conn->query("
				SELECT DISTINCT lmp.id_learning_map
				FROM school_control_ykt.groups AS groups
				INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
				INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
				INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
				INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
				INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
				INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
				INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
				INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
				WHERE lmp.learning_map_types_id != 2 AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area AND assc_assg_lmp.id_assignment = $assg->id_assignment
				");

			while ($row = $query->fetch(PDO::FETCH_OBJ)) {
				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($row->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				$final_comments_evc_normal[] = array(
					'id_assignment' => $assg->id_assignment,
					'comments' => $comments
				);
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'assgns' => $array_assgs,
		'final_comments_evc_normal' => $final_comments_evc_normal
	);

	echo json_encode($results);
}

function reportPrimaryBangueoloMDASpanishAndEnglish()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_academic_area = 1;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsWithoutMDAParents($group_id, $id_academic_area);
	$topics_eng = $cualitatives->getGroupsQuestionsWithoutMDAParentsEnglish($group_id, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsWithoutMDA($group_id, $id_academic_area, $id_student);

	$results_evc_normal = array();
	$results_evc_normal_eng = array();
	$topics_data = array();
	$eng_topics_data = array();
	$final_comments_evc_normal = array();
	$final_comments_evc_normal_eng = array();

	//--- --- ---//
	$conection = new data_conn;
	$conn = $conection->dbConn();
	//--- --- ---//

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			foreach ($array_assgs as $assg) {
				//print_r($assg);
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$final_comments_evc_normal = array();

		foreach ($array_assgs as $assg) {
			$query = $conn->query("
				SELECT DISTINCT lmp.id_learning_map
				FROM school_control_ykt.groups AS groups
				INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
				INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
				INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
				INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
				INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
				INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map  AND lmp.id_learning_map = 21
				INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
				INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
				WHERE lmp.learning_map_types_id != 2 AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area AND assc_assg_lmp.id_assignment = $assg->id_assignment
				");

			while ($row = $query->fetch(PDO::FETCH_OBJ)) {
				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($row->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				if (!empty($comments) && !in_array($comments, $final_comments_evc_normal)) {
					$final_comments_evc_normal[] = array(
						'id_assignment' => $assg->id_assignment,
						'comments' => $comments
					);
				}
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}

	if (count($topics_eng) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics_eng as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			foreach ($array_assgs as $assg) {
				//print_r($assg);
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$eng_topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		

		foreach ($array_assgs as $assg) {
			$query = $conn->query("
				SELECT DISTINCT lmp.id_learning_map
				FROM school_control_ykt.groups AS groups
				INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
				INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
				INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = 35
				INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
				INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
				INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map AND lmp.id_learning_map = 20
				INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
				INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
				WHERE lmp.learning_map_types_id != 2 AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area AND assc_assg_lmp.id_assignment = $assg->id_assignment
				");

			while ($row = $query->fetch(PDO::FETCH_OBJ)) {
				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($row->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				if (!empty($comments) && !in_array($comments, $final_comments_evc_normal_eng)) {
					$final_comments_evc_normal_eng[] = array(
						'id_assignment' => $assg->id_assignment,
						'comments' => $comments
					);
				}
				//--- --- ---//
			}
		}
		$results_evc_normal_eng = $eng_topics_data;
	}

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'results_evc_normal_eng' => $results_evc_normal_eng,
		'assgns' => $array_assgs,
		'final_comments_evc_normal' => $final_comments_evc_normal,
		'final_comments_evc_normal_eng' => $final_comments_evc_normal_eng
	);

	echo json_encode($results);
}

function reportPrimaryBangueoloMDAHebrew()
{
	//--- --- ---//
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];
	$id_academic_area = 2;

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new CualitativesReports;
	$student = new Students;
	$teacher = new users;

	$students = $student->getInfoStudent($id_student);

	//--- EVC NORMAL ---//
	$topics = array();
	$array_assgs = array();

	$topics = $cualitatives->getGroupsQuestionsWithoutMDAParentsHebrew($group_id, $id_academic_area);
	$array_assgs = $cualitatives->getAssignmentsWithoutMDA($group_id, $id_academic_area, $id_student);

	$results_evc_normal = array();
	$results_evc_normal_eng = array();
	$topics_data = array();
	$final_comments_evc_normal = array();

	//--- --- ---//
	$conection = new data_conn;
	$conn = $conection->dbConn();
	//--- --- ---//

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//

		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			foreach ($array_assgs as $assg) {
				//print_r($assg);
				//--- --- ---//
				$answers = $cualitatives->getAnswersMDAByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaboratorHeb($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}
			}

			//--- --- ---//
			if (!empty($assgs)) {
				$data = array(
					'questions_evaluations' => $questions_evaluations,
					'assgs' => $assgs
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}
		}

		$final_comments_evc_normal = array();

		foreach ($array_assgs as $assg) {
			$query = $conn->query("
				SELECT DISTINCT lmp.id_learning_map
				FROM school_control_ykt.groups AS groups
				INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
				INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
				INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
				INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
				INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
				INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map  AND lmp.id_learning_map = 19
				INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
				INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
				WHERE lmp.learning_map_types_id != 2 AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area AND assc_assg_lmp.id_assignment = $assg->id_assignment
				");

			while ($row = $query->fetch(PDO::FETCH_OBJ)) {
				//--- OBTENEMOS COMENTARIOS DE MPA Y ASIGNATURA ---//
				$comments = $cualitatives->getFinalCommentsMDAByStudent($row->id_learning_map, $assg->id_assignment, $installment, $id_student);
				//--- --- ---//
				if (!empty($comments) && !in_array($comments, $final_comments_evc_normal)) {
					$final_comments_evc_normal[] = array(
						'id_assignment' => $assg->id_assignment,
						'comments' => $comments
					);
				}
				//--- --- ---//
			}
		}

		$results_evc_normal = $topics_data;
	}

	$results = array(
		'response' => true,
		'students' => $students,
		'results_evc_normal' => $results_evc_normal,
		'assgns' => $array_assgs,
		'final_comments_evc_normal' => $final_comments_evc_normal,
	);

	echo json_encode($results);
}
function reportFinalCommentsMDA()
{
	$lmp_id = $_POST['lmp_id'];
	$group_id = $_POST['id_group'];
	$installment = $_POST['installment'];

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;
	$student = new Students;
	$teacher = new users;

	$topics = array();
	$array_assgs = array();
	if ($lmp_id === 15 || $lmp_id === "15") {
		$topics = $cualitatives->getGroupsQuestionsCombinatedMPACoordinator($no_teacher, " 15 AND 16 ", $group_id);
		$array_assgs = $cualitatives->getAssignmentsCombinatedMPACoordinator($no_teacher, " 15 AND 16 ", $group_id);
	} else {
		$topics = $cualitatives->getGroupsQuestionsMPACoordinator($no_teacher, $lmp_id, $group_id);
		$array_assgs = $cualitatives->getAssignmentsMPACoordinator($no_teacher, $lmp_id, $group_id);
	}
	$results = array();
	$topics_data = array();
	$final_comments = array();

	if (count($topics) > 0 && count($array_assgs) > 0) {
		//--- Preguntas del tema ---//
		$students = $student->getListStudentsByIDgroup($group_id);
		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id); //45
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMPACoordinator($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment);
				//--- --- ---//
				$info_teacher = $teacher->GetInfoCollaborator($assg->no_teacher);
				$assg->teacher_name = $info_teacher->name;
				//--- --- ---//
				if (!empty($answers)) {
					$assgs[] = array(
						'assg' => $assg,
						'answers' => $answers
					);
				}
				//--- --- ---//
			}
			//--- --- ---//
			$data = array(
				'questions_evaluations' => $questions_evaluations,
				'assgs' => $assgs
			);
			//--- --- ---//
			$topics_data[] = array(
				'topic' => $topic,
				'data' => $data
			);
			//--- --- ---//
		}

		//--- --- ---//
		foreach ($array_assgs as $assg) {
			//--- --- ---//
			$comments = $cualitatives->getFinalCommentsMPA1($lmp_id, $assg->id_assignment, $installment);
			//--- --- ---//
			$final_comments[] = array(
				'assg' => $assg,
				'comments' => $comments
			);
			//--- --- ---//

		}
		//--- --- ---//

		$results = array(
			'response' => true,
			'students' => $students,
			'topics' => $topics_data,
			'final_comments' => $final_comments
		);
	}

	echo json_encode($results);
}