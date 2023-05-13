<?php
include dirname(__DIR__, 1) . '/card_select_certificate.php';



$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_group'])) {
	$listStudent = $attendance->getListStudentByGroup($id_group);
}
if (isset($_GET['id_assignment'])) {
	$id_assingment = $_GET['id_assignment'];
	if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
		$id_level_combination = $id_level_combination->id_level_combination;
		$periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
	}
}
?>
<?php if (!empty($listStudent)) : ?>
	<div class="card">
		<div class="card-body">
			<div class="table-responsive" id="div_tabla">
				<?php include 'view_certificate_report.php'; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/certificate_report.js"></script>
