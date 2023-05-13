<?php
include '../../../general/php/models/Connection.php';
include '../models/learning_tracking.php';

date_default_timezone_set('America/Mexico_City');
$function = $_POST['func'];
$function();

function getListStudentsKetana() {
	$lt = new LearningTracking;
	$students = $lt->getListStudentsKetana();

	echo json_encode($students);
}

function getDataDafKesher(){
	$id_inscription = $_POST['id_inscription'];
	$initialDate = $_POST['initialDate'];
	$finalDate = $_POST['finalDate'];

	$initialDate = date("Y-m-d", strtotime($initialDate));
	$finalDate = date("Y-m-d", strtotime($finalDate));
	

	$lt = new LearningTracking;
	$comments = $lt->getDataDafKesherKetana($id_inscription, $initialDate, $finalDate);

	$response = false;
	$info_group = null;
	$data = null;
	if($comments != null){
		$response = true;
		$info_group = $lt->getinfoStudentByInscription($id_inscription);
		$data = array(
			'comments' => $comments,
			'info_student' => $info_group);
	}

	$results = array(
		'response' => $response,
		'data' => $data
	);

	echo json_encode($results);
}

function getAllDataStudentsDafKesher(){
	$initialDate = $_POST['initialDate'];
	$finalDate = $_POST['finalDate'];
	
	$lt = new LearningTracking;
	$arr_students = $lt->getListStudentsKetana();

	$students = array();
	foreach($arr_students AS $std){
		$comments = $lt->getDataDafKesherKetana($std->id_inscription, $initialDate, $finalDate);
		if($comments != null){
			$std->comments = $comments;
			$students[] = $std;
		}
	}

	echo json_encode($students);
}