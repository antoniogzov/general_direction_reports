<?php
//--- --- ---//
$learningMaps = $cualitatives->getLearningMaps($id_assignment);
$listGroupQuestions = array();
$installment = 0;
//--- --- ---//
$assc_mpa_id = '';
$ascc_lm_assgn = '';
$id_question_group = '';
$no_installment = '';
if(isset($_GET['ascc_lm_assgn']) && isset($_GET['assc_mpa_id']) && isset($_GET['no_installment'])){
	//--- --- ---//
	$assc_mpa_id = $_GET['assc_mpa_id'];
	$no_installment = $_GET['no_installment'];
	$ascc_lm_assgn = $_GET['ascc_lm_assgn'];
	$id_question_group = '';
	//--- --- ---//
	if($assc_mpa_id != 'comments'){
		//--- --- ---//
		$info = $cualitatives->getIDsAssociateLmEgEq($assc_mpa_id);
		$id_question_group = $info->id_question_group;
		//--- --- ---//
	} else {
		$id_question_group = 'comments';
	}
	//--- --- ---//
	$listGroupQuestions = $cualitatives->getGroupsQuestionsMPA($ascc_lm_assgn);
	$installment = 5;
	//--- --- ---//
}
//--- --- ---//
if(!empty($learningMaps)){
	include 'card_select_learning_maps.php';
	if(isset($_GET['ascc_lm_assgn']) && isset($_GET['assc_mpa_id']) && isset($_GET['no_installment'])){
		$listStudent = $students->getListStudentsByAssignment($id_assignment);
		if(!empty($listStudent))
		include 'students_mpa.php';
	}
}
//--- --- ---//
