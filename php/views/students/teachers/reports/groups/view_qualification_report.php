<?php
//$listStudent = array();
//$listIncidents = array();
if (isset($_GET['id_group']) && isset($_GET['id_period'])) {
	//$listStudent = $attendance->getListStudentByGroup($id_group);
	$infoGroup = $helpers->getGroupInfo($id_group);
	$group = $infoGroup[0];
	$id_period = $_GET['id_period'];
	$SubjectsInfo = $groupsReports->getAssignmentsByIDGroup($id_group, $id_academic_area);
	$num_subjects = count($SubjectsInfo);
	$num_students = count($listStudent);
	$general_prom = 0;
	$general_sum = 0;

	if($_GET['id_period'] == 'all_periods'){
		include 'view_grade_report_all_periods.php';
	} else {
		include 'view_grade_report_by_period.php';
	}
}
?>