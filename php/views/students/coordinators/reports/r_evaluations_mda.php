<?php
include dirname(__DIR__, 4) . '/models/cualitatives.php';
$cualitatives  = new Cualitatives;

$no_teacher = $_SESSION['colab'];

$groups = array();
if($grants & 8){
	$groups = $cualitatives->getGroupsFromCoordinatorWhitReportMDA($no_teacher);
} else if ($grants & 4) {
	$groups = $cualitatives->getGroupsFromTeacherWhitReportMDA($no_teacher);
}

$installment = 5;

include 'card_select_report_mda.php';

?>
<script src="js/kendoUi/js/jquery.min.js"></script>
<script src="js/kendoUi/js/jszip.min.js"></script>
<script src="js/kendoUi/js/kendo.all.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2017.3.913/js/pako_deflate.min.js"></script>
<script src="js/functions/evaluations_cualitatives/generate_reports.js"></script>
<script src="js/vendor/jsPDF/jspdf.umd.min.js"></script>
<script src="js/vendor/jsPDF/jspdf.plugin.autotable.js"></script>
<script src="js/functions/evaluations_cualitatives/downloadReports.js"></script>
<!-- <script src="js/functions/evaluations_cualitatives/generate_reports_tests.js"></script> -->
<script src="js/functions/evaluations_cualitatives/getReportsMDA.js"></script>
<script src="js/functions/evaluations_cualitatives/getLogos.js"></script>