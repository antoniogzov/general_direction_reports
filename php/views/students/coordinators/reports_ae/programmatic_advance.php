<script src="https://kit.fontawesome.com/2baa365664.js" crossorigin="anonymous"></script>
<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/tablefilter/tablefilter.js"></script>
<?php
include_once dirname(__DIR__, 4) . '/models/expected_learnings.php';
$expected_learnings = new ExpectedLearnings;

$no_teacher = $_SESSION['colab'];


if ($grants & 8) {
	$academicArea = $expected_learnings->getAcademicArea($no_teacher);
} else if ($grants & 4) {
	//	$groups = $cualitatives->getGroupsFromTeacherWhitReportMDA($no_teacher);
}

$installment = 5;

include 'card_select_programatic_advance.php';

?>
<script src="js/functions/expected_learning/programmatic_advance.js"></script>
<!-- <script src="js/functions/evaluations_cualitatives/getReportsMDA.js"></script> -->