<?php if (!empty($allGroups)) : ?>
	<script>
		Swal.fire({
			text: 'Cargando...',
			html: '<img src="images/loading_iteach.gif" width="300" height="300">',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showCloseButton: false,
			showCancelButton: false,
			showConfirmButton: false,
		})
	</script>
	<?php
	//include '/card_select_at_report.php';
	include dirname(__FILE__,6) . '/controllers/cuantitatives_reports.php';
	$calculateFinalAverages = new CalculateFinalAverages;
	?>
		<?php foreach($allGroups AS $group): ?>
			<?php
				$listStudent = $attendance->getListStudentByGroup($group->id_group);
				$num_students = count($listStudent);
				$SubjectsInfo = $groupsReports->getAssignmentsByIDGroupAndTeacher($group->id_group, $_GET['id_academic_area'], $_SESSION['colab']);
				foreach($listStudent AS $student){
					$student->averages = getAveragesByAssignmentPeriods($student->id_student, $SubjectsInfo, $periods, $calculateFinalAverages);
				}
			?>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 container-list-students">
							<div class="row">
								<div class="col">
									<h3 class="ml-2">REPORTE DE CALIFICACIONES DE TODOS LOS PERIODOS</h3>
								</div>
								<div class="col">
									<button type="button" class="btn btn-info float-right" onclick="downloadTable('xlsx', '<?= $group->group_code ?>')"><i class="fas fa-file-download fa-lg"></i></button>
								</div>
							</div>
							<h4 id="group_code" class="ml-2"><?= $group->group_code ?></h4>
							<h5 class="heading mb-0 ml-2">Alumnos: <?= $num_students ?></h5>

							<div class="sticky-table sticky-ltr-cells">
								<table class="table table-hover table-sm" border="1" id="<?= $group->group_code ?>">
									<thead>
										<tr class="sticky-header">
											<th rowspan="2" class="text-center align-middle sticky-header sticky-cell" style="font-weight: bold !important; color: black !important;">CÓD. ALUMNO</th>
											<th rowspan="2" class="text-center sticky-header sticky-cell align-middle" style="font-weight: bold !important; color: black !important;">NOMBRE</th>
											<th rowspan="2" class="align-middle text-center" data-toggle="tooltip" data-placement="top" title="Promedio general redondeado" style="font-weight: bold !important; color: black !important;">P G R</th>
											<th rowspan="2" class="align-middle text-center" data-toggle="tooltip" data-placement="top" title="Promedio general" style="font-weight: bold !important; color: black !important;">P G</th>
											<?php foreach ($SubjectsInfo as $subjects): ?>
												<th colspan="<?= count($periods) + 1; ?>" title="<?= $subjects->id_assignment . ' | '. strtoupper($subjects->name_subject) ?> | <?= strtoupper($subjects->spanish_name_teacher) ?>" class="text-center" data-toggle="tooltip" data-placement="top" style="border-left: 3.2px double black; border-bottom: 1.5px dashed black; font-weight: bold; color: black;"><?= strtoupper($subjects->short_name) ?></th>
											<?php endforeach; ?>
										</tr>
										<tr class="sticky-header">
											<?php foreach ($SubjectsInfo as $subjects): ?>
												<th class="text-center" style="border-left: 3.2px double black; font-weight: bold; color: black; border-bottom: 1.1px dashed black;" data-toggle="tooltip" data-placement="top" title="Promedio materia">P M</th>
												<?php foreach ($periods AS $periods_sc) : ?>
													<th style="font-weight: bold; color: black; border-bottom: 1.1px dashed black;" class="text-center"><?= $periods_sc->no_period ?></th>
												<?php endforeach; ?>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody style="color: black !important;">
										<?php foreach ($listStudent as $student): ?>
											<tr id="<?= $student->id_student ?>">
												<td class="text-center sticky-cell"><?= strtoupper($student->student_code) ?></td>
												<td class="text-center sticky-cell"><?= ucfirst($student->name_student) ?></td>
												<td class="text-center"><?= $student->averages->final_all_grade_rounded ?></td>
												<td class="text-center"><?= $student->averages->final_all_grade ?></td>

												<?php foreach($student->averages->assgs AS $std_assg): ?>
													<td style="border-left: 3.2px double black; font-weight: bold; color: black; font-size: small; background-color: <?= getBCGCByAverage($std_assg->final_grade_periods); ?>" class="text-center"><?= $std_assg->final_grade_periods ?></td>
													
														<?php foreach ($std_assg->periods AS $assg_periods) : ?>
															<td style="background-color: <?= getBCGCByAverage($assg_periods->average); ?>" class="text-center" onclick="getCriteriaDetailsww('<?= $assg_periods->id_grade_period ?>', '<?=$student->id_student?>','<?=$std_assg->id_assignment?>','<?= $assg_periods->average ?>','<?= $assg_periods->grade_period_calc ?>')"><?= $assg_periods->average ?></td>
															<?php endforeach; ?>
														<?php endforeach ?>
													</tr>	
												<?php endforeach; ?>
												<tr>
													<td colspan="2" class="text-center sticky-cell">PROMEDIOS</td>
													<td class="text-center"><?= roundAverage6($calculateFinalAverages->getAverageFinal()) ?></td>
													<td class="text-center"><?= $calculateFinalAverages->getAverageFinal() ?></td>
													<?php foreach ($SubjectsInfo as $subjects): ?>
														<td class="text-center"><?= $calculateFinalAverages->getAverageassgFinal($subjects->id_assignment); ?></td>
														<?php foreach ($periods AS $periods_sc) : ?>
															<td class="text-center"><?= $calculateFinalAverages->getAveragesByAssgPeriod($subjects->id_assignment, $periods_sc->id_period_calendar); ?></td>
														<?php endforeach ?>
													<?php endforeach ?>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<script>
				/*var txt_group = $('#id_group option:selected').text();
				var txt_period = $('#id_period option:selected').text();
				$('#tQualifications').DataTable({
					colReorder: false,
					dom: 'Bfrtip',
					lengthMenu: [
						[40, 25, 50, -1],
						['10 rows', '25 rows', '50 rows', 'Show all']
					],
					buttons: [{
							extend: 'excel',
							text: 'Excel',
							className: 'exportExcel',
							filename: txt_group + ' | Periodo:' + txt_period,
							exportOptions: {
								modifier: {
									page: 'all'
								}
							}
						},
						{
							extend: 'csv',
							text: 'CSV',
							className: 'exportExcel',
							filename: txt_group + ' | Periodo:' + txt_period,
							exportOptions: {
								modifier: {
									page: 'all'
								}
							}
						},
						{
							extend: 'pdfHtml5',
							text: 'PDF',
							messageTop: 'Reporte de Calificaciones - ' + txt_group + ' | Periodo:' + txt_period,
							className: 'exportExcel',
							filename: txt_group + ' | Periodo:' + txt_period,

							orientation: 'landscape',
							pageSize: 'LEGAL',
							exportOptions: {
								modifier: {
									page: 'all'
								}
							}
						}
					]
				});*/
			</script>
		<?php endforeach ?>
	<script>
		Swal.close();
	</script>
<?php endif; ?>