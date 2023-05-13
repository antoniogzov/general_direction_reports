<?php
if (($grants & 8)) {
	$average_type = 'normal';
	if (isset($_GET['average_type'])) {
		$average_type = $_GET['average_type'];
	}
	include dirname(__DIR__, 1) . '/card_select_groups.php';
} else if (($grants & 4)) {
	include dirname(__DIR__, 1) . '/card_select_teacher_qr.php';
}

$allGroups = array();
$listStudent = array();
$listIncidents = array();

if (($grants & 8)) { 
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
					<?php include 'view_qualification_report.php'; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

<?php }  else if (($grants & 4)) {  ?>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive" id="div_tabla">
				<?php include 'view_qualification_report_teacher.php'; ?>
			</div>
		</div>
	</div>
	

<?php } ?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/group_qualifications.js"></script>
