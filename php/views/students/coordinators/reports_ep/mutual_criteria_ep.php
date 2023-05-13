
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

include 'card_select_mutual_criteria_ep.php';

?>
<script src="js/functions/evaluation_plan/expected_learning_reports_criteria_ep.js"></script>
<!-- <script src="js/functions/evaluations_cualitatives/getReportsMDA.js"></script> -->