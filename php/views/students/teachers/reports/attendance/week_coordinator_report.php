<?php
include dirname(__DIR__, 1) . '/card_select_group_assignment.php';



$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_group'])) {
	$listStudent = $attendance->getListStudentByGroup($id_group);
}

?>
<?php if (!empty($listStudent)) : ?>

	<div class="card">
		<div class="card-body">

			<?php include  'view_week_attendance_report.php'; ?>

		</div>
	</div>
<?php endif; ?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/week_attendance_report.js"></script>
