<?php
include_once dirname(__DIR__, 4) . '/models/expected_learnings.php';
$expected_learnings = new ExpectedLearnings;

$no_teacher = $_SESSION['colab'];


if($grants & 8){
	$academicArea = $expected_learnings->getAcademicArea($no_teacher);
} else if ($grants & 4) {
//	$groups = $cualitatives->getGroupsFromTeacherWhitReportMDA($no_teacher);
}

$installment = 5;

include 'card_select_criteria_ae.php';

?>
<script src="js/functions/expected_learning/expected_learning_reports.js"></script>
<!-- <script src="js/functions/evaluations_cualitatives/getReportsMDA.js"></script> -->