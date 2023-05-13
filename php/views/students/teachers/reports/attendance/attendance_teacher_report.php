<?php
include dirname(__DIR__, 1) . '/card_select_at_report.php';



$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_subject']) && isset($_GET['id_group'])) {
	$listStudent = $attendance->getListStudent($id_group, $id_subject);
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
		<div class="card-body" id="table_attendance_report_teacher">
			<div class="table-responsive">
				<?php include 'view_attendance_report.php'; ?>

			</div>
		</div>
	</div>
<?php endif; ?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/attendance_report.js"></script>
<script>
	
</script>