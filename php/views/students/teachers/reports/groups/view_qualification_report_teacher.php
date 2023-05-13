<?php
$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_level_combination'])) {
	$allGroups = $groupsReports->getAllGroupsByIdLevelCombinationByTeacher($id_level_combination, $_SESSION['colab']);
	$id_period = $_GET['id_period'];
	$general_sum = 0;
}

if(isset($_GET['id_period'], $_GET['id_level_combination'])){
	if($_GET['id_period'] == 'all_periods'){
		include 'view_grade_report_all_periods_by_teacher.php';
	} else {
		include 'view_qualification_report_teacher_by_periods.php';
	}
}

?>