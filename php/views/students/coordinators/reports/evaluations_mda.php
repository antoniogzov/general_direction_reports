<?php
include dirname(__DIR__, 4) . '/models/cualitatives.php';
$cualitatives  = new Cualitatives;

$no_teacher = $_SESSION['colab'];

$lmps = array();
$lmps = $cualitatives->getLearningMapsCoordinator($no_teacher);
$installment = 5;

include 'card_select_learning_map.php';

?>
<script src="js/functions/evaluations_cualitatives/getReportCoordinator.js"></script>
<script src="../general/js/vendor/jsPDF/jspdf.js"></script>
<script src="js/functions/evaluations_cualitatives/generateJSPDF.js"></script>
