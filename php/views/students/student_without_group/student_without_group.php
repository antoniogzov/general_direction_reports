<?php
include 'php/models/students.php';
$Cstudent = new Students;
$students_without_group = $Cstudent->getStudentWithoutGroup($_SESSION['colab']);
?>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table align-items-center table-flush" id="tStudents">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th class="font-weight-bold col-md-2">Cód. alumno</th>
						<th class="font-weight-bold col-md-4">Nombre</th>
						<th class="font-weight-bold col-md-2">N. académico</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="list">
					<?php foreach ($students_without_group as $key=>$student) : ?>
						<tr id="<?= $student->id_student ?>">
							<th><?= ($key+1) ?></th>
							<th scope="row"><?= strtoupper($student->student_code) ?></th>
							<td><?= strtoupper($student->student_name) ?></td>
							<td><?= strtoupper($student->degree) ?></td>
							<td>
								<select class="slct-enroll">
									<option value="0" selected> Elija una opción</option>
									<?php foreach ($Cstudent->getGroupsByALG($student->id_level_grade, $student->id_campus) AS $grade) : ?>
										<option value="<?= $grade->id_group ?>"><?= strtoupper($grade->group_code) . ' - ' .  strtoupper($grade->collaborator_name)?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="js/functions/students/enroll_group/enroll.js"></script>