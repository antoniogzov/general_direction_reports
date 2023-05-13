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
	<div class="card">
		<div class="card-body">

			<div class="row">
				<div class="col-md-12 container-list-students">
					<h3 class="ml-2">REPORTE DE CALIFICACIONES POR PERIODO</h3>
					<h4 id="group_period" class="ml-2"></h4>
					<h5 class="heading mb-0 ml-2">Grupos: <?= count($allGroups) ?></h5>
					<script>
						var txt_period = $('#id_period_teacher option:selected').text();
						$('#group_period').text('');
						$('#group_period').text(' Periodo: ' + txt_period);
					</script>
					<?php foreach ($allGroups as $group) :
						$promedios_materias = array();
						$prom_materia = 0;
						$Assignments = $groupsReports->getAssignmentsByIDGroupAndTeacher($group->id_group, $_GET['id_academic_area'], $_SESSION['colab']);
						$listStudent = $attendance->getListStudentByGroup($group->id_group);
						$matriz_calificaciones = 0;
						foreach ($Assignments as $subjects_std) {
							array_push($promedios_materias, $subjects_std->id_assignment);
						}
					?>
						<br>
						<hr>


						<div class="table-responsive">
							<table class="table align-items-center table-flush" id="ReporteCalificaciones">
								<thead class="thead-light">
									<tr>
										<th colspan="100%" style=" text-align: center; font-size: 15px; font-weight: bold;">GRUPO: <?= $group->group_code ?></th>
									</tr>
									<tr>
										<th colspan="100%" style=" text-align: center; font-size: 10px; font-weight: bold;">Alumnos: <?= count($listStudent) ?></th>
									</tr>
									<tr>
										<th style="max-width:50px !important; min-width:50px !important; width:50px !important; padding-left: 1px !important; padding-right:1px !important;   font-size:10px !important; " class="text-center font-weight-bold ">CÓD. ALUMNO</th>
										<th style="max-width:50px !important; min-width:50px !important; width:50px !important; padding-left: 1px !important; padding-right:1px !important;   font-size:10px !important; " class="text-center font-weight-bold ">NOMBRE</th>
										<th style="max-width:25px !important; min-width:25px !important; width:25px !important; padding-left: 1px !important; padding-right:1px !important;   font-size:10px !important; " title="Promedio Reporte" class="text-center font-weight-bold ">P.R.</th>
										<?php foreach ($Assignments as $assignment) :

										?>
											<th width="15px" style=" border: 1px solid black;" title="<?= strtoupper($assignment->name_subject) ?> | <?= strtoupper($assignment->spanish_name_teacher) ?>" style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center"><?= strtoupper($assignment->name_subject) ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<?php
								$matriz_calificaciones = array();
								if (!empty($listStudent)) :
									$calificaciones_alumnos = array();
								?>
									<tbody class="list">
										<?php foreach ($listStudent as $student) : ?>
											<tr>
												<td style="text-align: center"><?= $student->student_code; ?></td>
												<td style="text-align: center"><?= $student->name_student; ?></td>

												<?php

												$promedio = 0;
												$suma_promedio = 0;
												$valid_assignments = 0;
												foreach ($Assignments as $subjects_std) {
													$getStudentQualificationList = $groupsReports->getStudentQualificationList($student->id_student, $group->id_group, $subjects_std->id_assignment, $id_academic_area, $id_period);
													if (!empty($getStudentQualificationList)) {
														if ($getStudentQualificationList[0]->average_show == 0 or $getStudentQualificationList[0]->average_show == NULL) {
															$suma_promedio = $suma_promedio + 0;
														} else {
															$valid_assignments++;
															$suma_promedio = $suma_promedio + $getStudentQualificationList[0]->average_show;
														}
													} else {

														$promedio = "-"; ?>

													<?php
													}

													?>
													<?php if ($suma_promedio == 0) {

														$promedio = 0; ?>
														<!-- <td class="text-center" style="background-color:#BBDEFB !important;"><?= ("-") ?></td> -->
													<?php } else {
														$promedio = number_format(($suma_promedio / $valid_assignments), 1, '.', ' ');
														number_format($promedio, 1, '.', '');
													?>
														<!-- <td class="text-center" style="background-color:#BBDEFB !important;"><?= number_format($promedio, 1, '.', ' ') ?></td> -->
												<?php }
												} ?>
												<td width="15px" class="text-center" style="background-color:#BBDEFB !important;"><?= ($promedio) ?></td>

												<?php

												foreach ($Assignments as $subjects_std) {



													$getStudentQualificationList = $groupsReports->getStudentQualificationList($student->id_student, $group->id_group, $subjects_std->id_assignment, $id_academic_area, $id_period);

													if (!empty($getStudentQualificationList)) {
														# code...

														if ($getStudentQualificationList[0]->average_show == 0 or $getStudentQualificationList[0]->average_show == NULL) {
															/* $sum_per = $sum_per + 0;
												$prom_per = $prom_per + 0; */

												?>
															<td width="15px" style="padding-left: 1px !important; background-color: #fcba03 !important; padding-right:1px !important; font-size:10px !important;" class="text-center">SIN CALIFICAR</td>
														<?php
															$calif = 0;
														} else {
															$calif = number_format($getStudentQualificationList[0]->average_show, 1, '.', ' ');
															$id_grade_period = $getStudentQualificationList[0]->id_grade_period;
															$prom_materia = $prom_materia + $calif;
															if ($calif >= 9.6) {
																$note_color = '#32b800';
															} else if ($calif >= 6 and $calif <= 9.5) {
																$note_color = '#000';
															} else if ($calif < 6) {
																$note_color = '#FF0000';
															}


															$css_tdGP = "";
															$checkIfExistCommentary = $groupsReports->checkIfExistCommentary($id_grade_period);
															if (!empty($checkIfExistCommentary)) {
																$checkedCommentary = $checkIfExistCommentary[0]->checked;
																if ($checkedCommentary == 1) {
																	$css_tdGP = "border: 2px solid #0330fc;";
																} else {
																	$css_tdGP = "border: 2px solid red;";
																}
															}
														?>

															<td id="tdGP<?= $id_grade_period ?>" width="15px" style="padding-left: 1px !important; padding-right:1px !important; <?=$css_tdGP?>" class="text-center"><button type="button" onclick="getCriteriaDetails('<?= $id_grade_period ?>', '<?= $student->id_student ?>','<?= $subjects_std->id_assignment ?>','<?= $calif ?>', '-')" class="btn btn-outline-secondary" style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important; color:<?= $note_color ?> !important"> <?= $calif ?> </button></td>

														<?php
															/* $sum_per = $sum_per + $getStudentQualificationList[0]->calificacion;
												$prom_per = $prom_per + $getStudentQualificationList[0]->calificacion; */
														}
													} else {
														$calif = 0;
														?>
														<td style="padding-left: 1px !important; background-color: #fcba03 !important; padding-right:1px !important; font-size:10px !important;" class="text-center">SIN CALIFICAR</td>
												<?php
													}
													array_push($calificaciones_alumnos, $calif);
												}
												array_push($matriz_calificaciones, $calificaciones_alumnos);
												$calificaciones_alumnos = array();
												?>
											</tr>
										<?php endforeach;

										?>

									<?php endif;
								if ($prom_materia == 0) {
									$prom_materia = '-';
								} else {
									$prom_materia = number_format(($prom_materia / count($listStudent)), 1, '.', ' ');
								}

									?>
									<tr>
										<th colspan="3" style="max-width:50px  !important; font-size:20px; background-color:#4388a8 !important; color:white !important;  text-align:center !important; min-width:50px !important; width:50px !important;">P R O M E D I O</th>

										<?php
										$estrucutra_promedios = array();
										for ($as = 0; $as < count($Assignments); $as++) {
											$prom_materia = 0;
											for ($i = 0; $i < count($matriz_calificaciones); $i++) {
												$prom_materia = $prom_materia + $matriz_calificaciones[$i][$as];
											}
											if ($prom_materia == 0) {
												$prom_materia = '-';
											} else {
												$prom_materia = number_format(($prom_materia / count($listStudent)), 1, '.', ' ');
											}
											array_push($estrucutra_promedios, $prom_materia);
										}
										foreach ($estrucutra_promedios as $promedio) {
											if ($promedio == '-') {
												$promedio = '-';
											} else {
												$promedio = number_format($promedio, 1, '.', ' ');
											}
										?>
											<th width="15px" style="color:#000 !important; background-color:#BBDEFB !important; padding-left: 1px !important; text-align: center !important; padding-right:1px !important; fint-weight:bold !important; font-size:12px !important;"><?= $promedio ?></th>
										<?php
										}
										?>

									</tr>
									</tbody>
							</table>
						</div>
					<?php endforeach; ?>


				</div>
			</div>
		</div>
	</div>
	<script>
		console.log('kdv ewrlvn3oirtv3');
		var txt_group = $('#id_group option:selected').text();
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
		});
	</script>
	<script>
		Swal.close();
	</script>
<?php endif; ?>