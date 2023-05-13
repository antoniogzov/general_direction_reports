<?php
include dirname(__DIR__, 1) . '/card_select_group_att_report.php';

$listStudent = array();
$listIncidents = array();

if (isset($_GET['id_group'])) {
	$listStudent = $attendance->getListStudentByGroup($id_group);
    $id_gp = $_GET['id_group'];
    if (!empty($id_assingment = $helpers->getAssignmentByGroup($id_gp))) {
    foreach ($id_assingment AS $assignment) {
		$id_assingment = $assignment->id_assignment;
	}
    
    
    if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
        $id_level_combination = $id_level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}
}

?>
<?php if (!empty($listStudent)) : ?>
	<div class="card">
		<div class="card-body" id="attendance_report_coordinator">
			<div class="table-responsive">
				<?php include 'view_attendance_report_coordinator.php'; 
				
				?>

			</div>
		</div>
	</div>
	<script src="js/functions/students/teachers/export_table.js"></script>
<?php endif; 
?>

<script src="js/functions/students/teachers/attendance_report_coordinator.js"></script>