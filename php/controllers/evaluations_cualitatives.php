<?php
session_start();
include '../../../general/php/models/Connection.php';
include '../../../general/php/models/GeneralModel.php';
include '../models/cualitatives.php';
include '../models/students.php';

date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getGroupsQuestionsMPA()
{
	//--- --- ---//
	$groupsQuestions = array();

	$ascc_lm_assgn = $_POST['ascc_lm_assgn'];
	$cualitatives = new Cualitatives;
	$groupsQuestions = $cualitatives->getGroupsQuestionsMPA($ascc_lm_assgn);
	//--- --- ---//
	$response = false;
	if (!empty($groupsQuestions)) {
		$response = true;
	}
	//--- --- ---//
	$datos = array(
		'response' => $response,
		'groupsQuestions' => $groupsQuestions
	);

	echo json_encode($datos);
}

function getFormMPA()
{
	$dataForm = array();

	$assc_mpa_id = $_POST['assc_mpa_id'];
	$cualitatives = new Cualitatives;

	$data = $cualitatives->getDataFormMPA($assc_mpa_id);

	if (!empty($data['questions']) && !empty($data['evaluations'])) {
		$dataForm = array(
			'response' => true,
			'data' => $data
		);
	} else {
		$dataForm = array('response' => false);
	}

	echo json_encode($dataForm);
}
//--- --- ---//
function saveEvaluationMPA()
{

	$data = array();

	$ascc_lm_assgn = $_POST['ascc_lm_assgn'];
	$assc_mpa_id = $_POST['assc_mpa_id'];
	$id_student = $_POST['id_student'];
	$no_installment = $_POST['no_installment'];
	$dataMPA = $_POST['dataMPA'];

	$incomplete = false;

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;

	$sql_save = "INSERT INTO iteach_grades_qualitatives.learning_maps_log(ascc_lm_assgn, assc_mpa_id, id_student, no_installment, fill_date, no_teacher) VALUES ('$ascc_lm_assgn', '$assc_mpa_id', '$id_student', '$no_installment', '$today', '$no_teacher')";

	$id_historical_learning_maps = $cualitatives->saveEvaluationMPA($sql_save);
	if ($id_historical_learning_maps > 0) {
		foreach ($dataMPA as $evaluation) {
			//--- --- ---//
			$id_question_bank = $evaluation['id_question'];
			$info_question = $cualitatives->getInfoQuestion($id_question_bank);
			$question = $info_question->question;
			$id_evaluation_bank = $evaluation['id_evaluation'];
			//--- --- ---//
			if ($id_evaluation_bank != NULL) {
				$info_evaluation = $cualitatives->getInfoEvaluation($id_evaluation_bank);
				$symbol = "'$info_evaluation->symbol'";
				$evaluation = "'$info_evaluation->evaluation'";
				$colorHTML = "'$info_evaluation->colorHTML'";
			} else {
				$incomplete = true;
				$symbol = "NULL";
				$evaluation = "NULL";
				$colorHTML = "NULL";
			}
			//--- --- ---//
			$sql_save_detail = "INSERT INTO iteach_grades_qualitatives.questions_log_learning_maps(id_historical_learning_maps, id_question_bank, question, id_evaluation_bank, symbol, evaluation, colorHTML) VALUES ('$id_historical_learning_maps', '$id_question_bank', '$question', '$id_evaluation_bank', $symbol, $evaluation, $colorHTML)";
			$cualitatives->saveEvaluationMPA($sql_save_detail);
			//--- --- ---//
		}
		$data = array(
			'response' => true, 'message' => 'Se guardó la evaluación correctamente :D', 'id_historical_learning_maps' => $id_historical_learning_maps,
			'incomplete' => $incomplete
		);
	} else {
		$data = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
	}

	echo json_encode($data);
}

function getFormMPAFilled()
{
	$dataForm = array();

	$id_historical_learning_maps = $_POST['id_historical_learning_maps'];
	$cualitatives = new Cualitatives;

	$infoMPALog = $cualitatives->getInfoLearningMapsLog($id_historical_learning_maps);

	$assc_mpa_id = $infoMPALog->assc_mpa_id;
	$cualitatives = new Cualitatives;

	$data = $cualitatives->getDataFormMPA($assc_mpa_id);

	if (!empty($data['questions']) && !empty($data['evaluations'])) {
		//--- --- ---//
		$answers = $cualitatives->getAnswersMPA($id_historical_learning_maps);
		//--- --- ---//
		$dataForm = array(
			'response' => true,
			'data' => $data,
			'answers' => $answers
		);
	} else {
		$dataForm = array('response' => false);
	}

	echo json_encode($dataForm);
}

function updateEvaluationMPA()
{
	$data = array();

	$id_historical_learning_maps = $_POST['id_historical_learning_maps'];
	$dataMPA = $_POST['dataMPA'];

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];
	$incomplete = false;

	$cualitatives = new Cualitatives;

	$sql_update = "UPDATE iteach_grades_qualitatives.learning_maps_log SET update_date = '$today', updated_no_teacher = '$no_teacher' WHERE id_historical_learning_maps = '$id_historical_learning_maps'";

	$result_update = $cualitatives->updateEvaluationMPA($sql_update);
	if ($result_update) {
		//--- --- ---//
		$sql_delete = "DELETE FROM iteach_grades_qualitatives.questions_log_learning_maps WHERE id_historical_learning_maps = '$id_historical_learning_maps'";

		$result_delete = $cualitatives->updateEvaluationMPA($sql_delete);
		//--- --- ---//
		if ($result_delete) {
			foreach ($dataMPA as $evaluation) {
				//--- --- ---//
				$id_question_bank = $evaluation['id_question'];
				$info_question = $cualitatives->getInfoQuestion($id_question_bank);
				$question = $info_question->question;
				$id_evaluation_bank = $evaluation['id_evaluation'];
				//--- --- ---//
				if ($id_evaluation_bank != NULL) {
					$info_evaluation = $cualitatives->getInfoEvaluation($id_evaluation_bank);
					$symbol = "'$info_evaluation->symbol'";
					$evaluation = "'$info_evaluation->evaluation'";
					$colorHTML = "'$info_evaluation->colorHTML'";
				} else {
					$incomplete = true;
					$symbol = "NULL";
					$evaluation = "NULL";
					$colorHTML = "NULL";
				}
				//--- --- ---//
				//--- --- ---//
				$sql_save_detail = "INSERT INTO iteach_grades_qualitatives.questions_log_learning_maps(id_historical_learning_maps, id_question_bank, question, id_evaluation_bank, symbol, evaluation, colorHTML) VALUES ('$id_historical_learning_maps', '$id_question_bank', '$question', '$id_evaluation_bank', $symbol, $evaluation, $colorHTML)";
				$cualitatives->saveEvaluationMPA($sql_save_detail);
				//--- --- ---//
			}
			$data = array(
				'response' => true, 'message' => 'Se actualizó la evaluación correctamente :D', 'id_historical_learning_maps' => $id_historical_learning_maps,
				'incomplete' => $incomplete
			);
		}
	} else {
		$data = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
	}

	echo json_encode($data);
}

function saveCommentsMPA()
{
	$ascc_lm_assgn = $_POST['ascc_lm_assgn'];
	$id_student = $_POST['id_student'];
	$no_installment = $_POST['no_installment'];
	$comments1 = $_POST['comment1'];
	$comments2 = $_POST['comment2'];

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;

	$sql_save = "INSERT INTO iteach_grades_qualitatives.final_comments(ascc_lm_assgn, id_student, no_installment, fill_date_comments, comments1, comments2, no_teacher_fill) VALUES ('$ascc_lm_assgn', '$id_student', '$no_installment', '$today', '$comments1', '$comments2', '$no_teacher')";

	$idComments = $cualitatives->saveEvaluationMPA($sql_save);
	if ($idComments > 0) {
		$data = array('response' => true, 'id_comments' => $idComments, 'message' => 'Se guardaron los comentarios correctamente');
	} else {
		$data = array('response' => false);
	}

	echo json_encode($data);
}

function getCommentsMPAFilled()
{
	$id_comments = $_POST['id_comments'];

	//--- --- ---//
	$infoComments = array();

	$cualitatives = new Cualitatives;
	$infoComments = $cualitatives->getFinalCommentsMPAByIDComm($id_comments);
	//--- --- ---//
	$response = false;
	if (!empty($infoComments)) {
		$response = true;
	}
	//--- --- ---//
	$datos = array(
		'response' => $response,
		'comments1' => $infoComments->comments1,
		'comments2' => $infoComments->comments2
	);

	echo json_encode($datos);
}

function updateCommentsMPA()
{
	$id_comments = $_POST['id_comments'];
	$comments1 = $_POST['comment1'];
	$comments2 = $_POST['comment2'];

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;

	if ($comments1 == '' and $comments2 == '') {
		//--- --- ---//
		$sql_update = "DELETE FROM iteach_grades_qualitatives.final_comments WHERE id_comments = '$id_comments'";

		$result_update = $cualitatives->updateEvaluationMPA($sql_update);
		if ($result_update) {
			$datos = array('response' => true, 'message' => 'Se actualizaron los comentarios correctamente :D');
		} else {
			$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
		}
		//--- --- ---//
	} else {
		$sql_update = "UPDATE iteach_grades_qualitatives.final_comments SET comments1 = ?, comments2 = ?, update_date_comments = ?, no_teacher_update = ? WHERE id_comments = ?";

		$data = array($comments1, $comments2, $today, $no_teacher, $id_comments);

		$result_update = $cualitatives->updateEvaluationMPA_PDO($sql_update, $data);
		if ($result_update) {
			$datos = array('response' => true, 'message' => 'Se actualizaron los comentarios correctamente :D');
		} else {
			$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
		}
	}

	echo json_encode($datos);
}

function deleteEvaluationMPA()
{
	$id_historical_learning_maps = $_POST['id_historical_learning_maps'];

	$cualitatives = new Cualitatives;

	$sql_delete = "DELETE FROM iteach_grades_qualitatives.questions_log_learning_maps WHERE id_historical_learning_maps = '$id_historical_learning_maps'";

	$result_delete = $cualitatives->updateEvaluationMPA($sql_delete);
	if ($result_delete > 0) {
		//--- --- ---//
		$sql_delete1 = "DELETE FROM iteach_grades_qualitatives.learning_maps_log WHERE id_historical_learning_maps = '$id_historical_learning_maps'";

		$result_delete1 = $cualitatives->updateEvaluationMPA($sql_delete1);
		if ($result_delete1 > 0) {
			$datos = array('response' => true, 'message' => 'Se eliminó la evaluación correctamente :D');
		} else {
			$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
		}
		//--- --- ---//
	} else {
		$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
	}

	echo json_encode($datos);
}

//--- COORDINATORS  ---//
function getGroupsMDA()
{
	$lmp_id = $_POST['lmp_id'];
	$groups = array();

	$no_teacher = $_SESSION['colab'];
	$cualitatives = new Cualitatives;
	$groups = $cualitatives->getGroupsLMPCoordinator($no_teacher, $lmp_id);

	if (count($groups) > 0) {
		$datos = array('response' => true, 'groups' => $groups);
	} else {
		$datos = array('response' => false, 'message' => 'No se encontraron grupos :/');
	}

	echo json_encode($datos);
}

function getReportMDAGeneral()
{
	$lmp_id = $_POST['lmp_id'];
	$group_id = $_POST['group_id'];
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

function getReportMDAStudent()
{
	$lmp_id = $_POST['lmp_id'];
	$group_id = $_POST['group_id'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];

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
		$students = $student->getInfoStudent($id_student);
		foreach ($topics as $topic) {
			//--- --- ---//
			$data = array();
			$assgs = array();
			$questions_evaluations = $cualitatives->getDataFormMPA($topic->assc_mpa_id);
			//--- --- ---//
			foreach ($array_assgs as $assg) {
				//--- --- ---//
				$answers = $cualitatives->getAnswersMPACoordinatorByStudent($topic->assc_mpa_id, $assg->ascc_lm_assgn, $installment, $id_student);
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
			$comments = $cualitatives->getFinalCommentsMPAByStudent($lmp_id, $assg->id_assignment, $installment, $id_student);
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


function getReportMDAGeneralHebrew()
{
	$group_id = $_POST['group_id'];
	$installment = $_POST['installment'];

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;
	$student = new Students;
	$teacher = new users;

	$topics = array();
	$array_assgs = array();


	$checkIfHaveHebrew = $cualitatives->checkIfHaveHebrew($no_teacher);
	if (!empty($checkIfHaveHebrew)) {

		$topics = $cualitatives->getGroupsQuestionsCombinatedMPACoordinatorHebrew($no_teacher, $installment, $group_id);

		$results = array();
		$topics_data = array();
		$final_comments = array();
		if (count($topics) > 0) {
			//--- Preguntas del tema ---//
			$students = $student->getListStudentsByIDgroup($group_id);
			foreach ($topics as $topic) {
				//--- --- ---//
				$data = array();
				$assgs = array();
				$questions_evaluations = $cualitatives->getDataFormMPAHebrew($topic->id_evaluation_source, $no_teacher, $installment, $group_id); //45
				//--- --- ---//

				//--- --- ---//
				$data = array(
					'questions_evaluations' => $questions_evaluations
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}

			//--- --- ---//
			/* foreach ($array_assgs as $assg) {
				//--- --- ---//
				$comments = $cualitatives->getFinalCommentsMPA1($lmp_id, $assg->id_assignment, $installment);
				//--- --- ---//
				$final_comments[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//

			} */
			//--- --- ---//

			$results = array(
				'response' => true,
				'students' => $students,
				'topics' => $topics_data,
				'final_comments' => $final_comments
			);
		}
	} else {
		$results = array(
			'response' => false,
		);
	}

	echo json_encode($results);
}
function getReportMDAGeneralHebrewStudent()
{
	$group_id = $_POST['group_id'];
	$installment = $_POST['installment'];
	$id_student = $_POST['id_student'];

	$results = false;

	$groups = array();

	$no_teacher = $_SESSION['colab'];

	$cualitatives = new Cualitatives;
	$student = new Students;
	$teacher = new users;

	$topics = array();
	$array_assgs = array();


	$checkIfHaveHebrew = $cualitatives->checkIfHaveHebrew($no_teacher);
	if (!empty($checkIfHaveHebrew)) {

		$topics = $cualitatives->getGroupsQuestionsCombinatedMPACoordinatorHebrew($no_teacher, $installment, $group_id);

		$results = array();
		$topics_data = array();
		$final_comments = array();
		if (count($topics) > 0) {
			//--- Preguntas del tema ---//
			$students = $cualitatives->StudentsInfo($id_student);
			foreach ($topics as $topic) {
				//--- --- ---//
				$data = array();
				$assgs = array();
				$questions_evaluations = $cualitatives->getDataFormMPAHebrewSudent($topic->id_evaluation_source, $no_teacher, $installment, $group_id, $id_student); //45
				//--- --- ---//

				//--- --- ---//
				$data = array(
					'questions_evaluations' => $questions_evaluations
				);
				//--- --- ---//
				$topics_data[] = array(
					'topic' => $topic,
					'data' => $data
				);
				//--- --- ---//
			}

			//--- --- ---//
			/* foreach ($array_assgs as $assg) {
				//--- --- ---//
				$comments = $cualitatives->getFinalCommentsMPA1($lmp_id, $assg->id_assignment, $installment);
				//--- --- ---//
				$final_comments[] = array(
					'assg' => $assg,
					'comments' => $comments
				);
				//--- --- ---//

			} */
			//--- --- ---//

			$results = array(
				'response' => true,
				'students' => $students,
				'topics' => $topics_data,
				'final_comments' => $final_comments
			);
		}
	} else {
		$results = array(
			'response' => false,
		);
	}

	echo json_encode($results);
}


function updateDirectorsComments()
{
	$id_comments = $_POST['id_comments'];
	$colum = $_POST['colum'];
	$text = $_POST['text'];

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];

	$data_conn = new data_conn;
	$conn = $data_conn->dbConn();

	$cualitatives = new Cualitatives;

	$sql_update = "UPDATE iteach_grades_qualitatives.final_comments SET $colum = '$text', date_update_directors_comment = '$today', no_director_comment = $no_teacher WHERE id_comments = $id_comments";

	$result_update = $cualitatives->updateEvaluationMPA($sql_update);

	//--- --- ---//
	$sql = "SELECT * FROM iteach_grades_qualitatives.final_comments WHERE id_comments = '$id_comments'";
	$statement = $conn->query($sql);
	// get all publishers
	$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

	if ($comments) {
		foreach ($comments as $comm) {
			$comments1 = $comm['comments1'];
			$comments2 = $comm['comments2'];
			$directors_comment = $comm['directors_comment'];
		}

		if ($comments1 == '' && $comments2 == '' && $directors_comment == '') {
			$sql_update1 = "DELETE FROM iteach_grades_qualitatives.final_comments WHERE id_comments = $id_comments";
			$result_update = $cualitatives->updateEvaluationMPA($sql_update1);
		}
	}
	//--- --- ---//

	if ($result_update) {
		$datos = array('response' => true, 'message' => 'Se actualizaron los comentarios correctamente :D');
	} else {
		$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
	}


	echo json_encode($datos);
}

function addDirectorsComments()
{
	$id_student = $_POST['id_student'];
	$ascc_lm_assgn = $_POST['ascc_lm_assgn'];
	$installment = $_POST['installment'];
	$colum = $_POST['colum'];
	$text = $_POST['text'];

	$today = date('Y-m-d H:i:s');
	$no_teacher = $_SESSION['colab'];

	$data_conn = new data_conn;
	$conn = $data_conn->dbConn();

	$cualitatives = new Cualitatives;

	$sql = "SELECT * FROM iteach_grades_qualitatives.final_comments WHERE ascc_lm_assgn = '$ascc_lm_assgn' AND id_student = '$id_student' AND no_installment = '$installment'";
	$statement = $conn->query($sql);
	// get all publishers
	$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

	$id_comments = 0;

	if ($comments) {
		foreach ($comments as $comm) {
			$id_comments = $comm['id_comments'];
		}

		$sql_update = "UPDATE iteach_grades_qualitatives.final_comments SET $colum = '$text', date_update_directors_comment = '$today', no_director_comment = $no_teacher WHERE id_comments = '$id_comments'";
	} else {
		$sql_update = "INSERT INTO iteach_grades_qualitatives.final_comments(ascc_lm_assgn, id_student, no_installment, $colum, date_update_directors_comment, no_director_comment) VALUES ($ascc_lm_assgn, $id_student, $installment, '$text', '$today', $no_teacher)";
	}

	$result_update = $cualitatives->updateEvaluationMPA($sql_update);

	//--- --- ---//
	$sql = "SELECT * FROM iteach_grades_qualitatives.final_comments WHERE id_comments = $id_comments";
	$statement = $conn->query($sql);
	// get all publishers
	$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

	if ($comments) {
		foreach ($comments as $comm) {
			$comments1 = $comm['comments1'];
			$comments2 = $comm['comments2'];
			$directors_comment = $comm['directors_comment'];
		}

		if ($comments1 == '' && $comments2 == '' && $directors_comment == '') {
			$sql_update1 = "DELETE FROM iteach_grades_qualitatives.final_comments WHERE id_comments = $id_comments";
			$result_update = $cualitatives->updateEvaluationMPA($sql_update1);
		}
	}
	//--- --- ---//


	if ($result_update) {
		$datos = array('response' => true, 'message' => 'Se actualizaron los comentarios correctamente :D');
	} else {
		$datos = array('response' => false, 'message' => 'Ocurrió un error, intentelo nuevamente porfavor');
	}


	echo json_encode($datos);
}

function getStudentByGroup()
{
	$group_id = $_POST['group_id'];

	$students = new Students;
	$studentList = $students->getListStudentsByIDgroup($group_id);

	if (count($studentList)) {
		$datos = array(
			'response' => true,
			'students' => $studentList
		);
	} else {
		$datos = array(
			'response' => false,
			'students' => $studentList,
			'message' => ''
		);
	}

	echo json_encode($datos);
}

function getReports()
{
	$id_group = $_POST['id_group'];

	$reports = array();
	$students =  array();

	$no_teacher = $_SESSION['colab'];
	$cualitatives = new Cualitatives;
	$students = new Students;

	$reports = $cualitatives->getListReports($id_group);
	$lstudents = $students->getListStudentsByIDgroup($id_group);

	if (count($reports) > 0) {
		$datos = array('response' => true, 'reports' => $reports, 'students' => $lstudents);
	} else {
		$datos = array('response' => false, 'message' => 'No se encontraron reportes :/');
	}

	echo json_encode($datos);
}

function getStudList()
{
	$id_group = $_POST['id_group'];

	$reports = array();
	$students =  array();

	$no_teacher = $_SESSION['colab'];
	$cualitatives = new Cualitatives;
	$students = new Students;

	$lstudents = $students->getListStudentsByIDgroup($id_group);

	if (count($lstudents) > 0) {
		$datos = array('response' => true, 'students' => $lstudents);
	} else {
		$datos = array('response' => false, 'message' => 'No se encontraron estudiantes :/');
	}

	echo json_encode($datos);
}
