<?php if (!empty($listStudent)) : ?>
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
					<h5 class="heading mb-0 ml-2">Alumnos: <?= $num_students ?></h5>
					<script>
						var txt_group = $('#id_group option:selected').text();
						var txt_period = $('#id_period option:selected').text();
						$('#group_period').text('');
						$('#group_period').text(txt_group + ' | Periodo:' + txt_period);
					</script>

					<div class="table-responsive">
						<table class="table align-items-center table-flush" id="tQualifications">
							<thead class="thead-light">
								<tr>
									<th style="padding-left: 1px !important; padding-right:1px !important;  font-size:10px !important; " class="text-center font-weight-bold ">CÓD. ALUMNO</th>
									<th style="padding-left: 1px !important; padding-right:1px !important;  font-size:10px !important; " class="text-center font-weight-bold ">NOMBRE</th>
									<th style="padding-left: 1px !important; padding-right:1px !important;  font-size:10px !important; " title="Promedio Reporte" class="text-center font-weight-bold ">P.R.</th>
									<th style="padding-left: 1px !important; padding-right:1px !important;  font-size:10px !important; " title="Promedio por Periodo" class="text-center font-weight-bold ">P x P.</th>
									<?php foreach ($SubjectsInfo as $subjects) :
										$attendance->generateAssignmentAverage($subjects->id_assignment, $_GET['id_period']);
									?>
										<th title="<?= $subjects->id_assignment . ' | ' . strtoupper($subjects->name_subject) ?> | <?= strtoupper($subjects->spanish_name_teacher) ?>" style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center"><?= strtoupper($subjects->short_name) ?></th>
									<?php endforeach; ?>


								</tr>
							</thead>
							<tbody class="list">
								<?php
								$arr_pormedios_materias = [];
								$materias = array();

								foreach ($SubjectsInfo as $subjects_std) {
									$promedios_materias["id_assignment"] = $subjects_std->id_assignment;
									$materias = array();
									foreach ($listStudent as $student) {

										$averages = array();
										$materia = array(
											'id_student' => $student->id_student,
											'averages' => $averages,
										);
										array_push($materias, $materia);
									}

									$promedios_materias["assignemnts"] = $materias;
									// echo json_encode($materias);
									//$promedios_periodo[$no_period][$period->id_period_calendar] = $materias;
									//echo json_encode($promedios_periodo[$no_period][$period->id_period_calendar]);
									array_push($arr_pormedios_materias, $promedios_materias);
								}
								//echo json_encode($arr_pormedios_materias);
								$arr_no_student = 0;
								foreach ($listStudent as $student) :
									$sum_per = 0;
									$prom_per = 0;
									$note_color = '';

								?>
									<tr id="<?= $student->id_student ?>">
										<td style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center"><?= strtoupper($student->student_code) ?></td>
										<td style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center"><?= strtoupper($student->name_student) ?></td>
										<!-- PROMEDIOS -->
										<?php
										if ($average_type == 'normal') {
											$studentAverage = $groupsReports->getStudentAverageReport($student->id_student, $id_period);
										} else if ($average_type == 'dinamyc') {
											$studentAverage = $groupsReports->getStudentAverageReportDinamyc($student->id_student, $id_period);
										}

										$promedio = ($studentAverage['average']);
										$general_sum  = $general_sum + $promedio;
										$promedio = number_format($promedio, 1, '.', ' ');
										$arr_promedio = explode('.', $promedio);
										$entero_promedio = $arr_promedio[0];
										$decimal_promedio = $arr_promedio[1];
										if ($decimal_promedio >= 6) {
											$entero_promedio++;
										}
										$promedio_redondeado = $entero_promedio;
										?>
										<td class="text-center" style="background-color:#FFF9C4 !important;"><?= number_format($promedio_redondeado) ?></td>
										<td class="text-center" style="background-color:#BBDEFB !important;"><?= number_format($promedio, 1, '.', ' ') ?></td>

										<!-- FIN PROMEDIOS -->

										<!-- CALIFICACIONES POR MATERIA -->
										<?php
										$arr_no_subjects = 0;

										foreach ($SubjectsInfo as $subjects_std) {
											if ($average_type == 'normal') {
												$getStudentQualificationList = $groupsReports->getStudentQualificationList($student->id_student, $id_group, $subjects_std->id_assignment, $id_academic_area, $id_period);
											} else if ($average_type == 'dinamyc') {
												$getStudentQualificationList = $groupsReports->getStudentQualificationListDinamyc($student->id_student, $id_group, $subjects_std->id_assignment, $id_academic_area, $id_period);
											}


											if (!empty($getStudentQualificationList)) {
												# code...

												if ($getStudentQualificationList[0]->average_show == 0 or $getStudentQualificationList[0]->average_show == NULL) {
													/* $sum_per = $sum_per + 0;
													$prom_per = $prom_per + 0; */
													array_push($arr_pormedios_materias[$arr_no_subjects]["assignemnts"][$arr_no_student]["averages"], "");

										?>
													<td style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center">-</td>
												<?php
												} else {
													$calif = number_format($getStudentQualificationList[0]->average_show, 1, '.', ' ');
													$id_grade_period = $getStudentQualificationList[0]->id_grade_period;
													$note_color = '';
													$bck_color = '';
													if($getStudentQualificationList[0]->grade_extraordinary){
														$bck_color = '#FFBFF5';
													}
													if ($calif >= 9.6) {
														$note_color = '#32b800';
													} else if ($calif >= 6 and $calif <= 9.5) {
														$note_color = '#000';
													} else if ($calif < 6) {
														$note_color = '#FF0000';
													}

													if ($getStudentQualificationList[0]->grade_period_calc == 0 or $getStudentQualificationList[0]->grade_period_calc == NULL) {
														$grade_period_calc = "-";
													} else {
														$grade_period_calc = number_format($getStudentQualificationList[0]->grade_period_calc, 1, '.', ' ');
													}

													//

													//$promedios_materias["id_period_calendar"][$no_period][$subjects->id_assignment] = $calification_stud;
													//var_dump($arr_pormedios_materias[$arr_no_student]["assignemnts"][$arr_no_subjects]["averages"]);
													//echo $arr_no_student.'<br/><br/>';
													array_push($arr_pormedios_materias[$arr_no_subjects]["assignemnts"][$arr_no_student]["averages"], $calif);

													$arr_no_subjects++;
													//array_push($arr_pormedios_materias[$arr_no_student]["assignemnts"][$arr_no_subjects]["averages"], $calif);

													if ($average_type == 'normal') {
														$calif_principal_sweet = $calif;
														$calif_second_sweet = $grade_period_calc;
													} else if ($average_type == 'dinamyc') {
														$calif_principal_sweet = $grade_period_calc;
														$calif_second_sweet = $calif;
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
													<td id="tdGP<?= $id_grade_period ?>" width="15px" style="padding-left: 1px !important; padding-right:1px !important; <?= $css_tdGP ?>" class="text-center"><button type="button" onclick="getCriteriaDetails('<?= $id_grade_period ?>', '<?= $student->id_student ?>','<?= $subjects_std->id_assignment ?>','<?= $calif_principal_sweet ?>', '<?= $calif_second_sweet ?>')" class="btn btn-outline-secondary" style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important; background-color: <?= $bck_color ?> !important; color:<?= $note_color ?> !important"> <?= $calif ?> </button></td>
												<?php
													/* $sum_per = $sum_per + $getStudentQualificationList[0]->calificacion;
													$prom_per = $prom_per + $getStudentQualificationList[0]->calificacion; */
												}
											} else {
												$arr_no_subjects++;
												?>
												<td style="padding-left: 1px !important; padding-right:1px !important; font-size:10px !important;" class="text-center">-</td>
										<?php
											}
										}
										$arr_no_student++;

										?>

										<!-- CALIFICACIONES POR MATERIA -->

									</tr>
								<?php endforeach; ?>
								<?php $general_prom = $general_sum / $num_students; ?>
								<thead class="thead-light">
									<?php
									//echo json_encode($arr_pormedios_materias);
									$arr_pormedios_materias = array($arr_pormedios_materias);
									$promedios_tabla = array();
									for ($i = 0; $i < count($arr_pormedios_materias); $i++) {


										for ($j = 0; $j < count($arr_pormedios_materias[$i]); $j++) {
											//var_dump($arr_pormedios_materias[$i][$j]);
											$id_assignment = $arr_pormedios_materias[$i][$j]["id_assignment"];
											$final_grade_assg = 0;
											$count1 = 0;
											//echo '<strong>id_assignment:</strong> ' . $id_assignment . '<br>';

											for ($p = 0; $p < count($arr_pormedios_materias[$i][$j]["assignemnts"]); $p++) {
												for ($q = 0; $q < count($arr_pormedios_materias[$i][$j]["assignemnts"][$p]["averages"]); $q++) {
													$averages = $arr_pormedios_materias[$i][$j]["assignemnts"][$p]["averages"][$q];
													//echo "averages: " . $averages . '<br>';
													if ($averages != '') {
														$final_grade_assg = $final_grade_assg + $averages;

														$count1++;
													}
												}
											}
											if ($count1 > 0) {
												$final_grade_assg = $final_grade_assg / $count1;
												$final_grade_assg = round($final_grade_assg, 1);
											} else {
												$final_grade_assg = '-';
											}
											//	echo 'final_grade_assg: ' . $final_grade_assg . '<br>';
											array_push($promedios_tabla, $final_grade_assg);
										}
									}
									//echo json_encode($promedios_tabla);
									?>

									<tr>
										<td colspan="4" align="center" valign="middle"><strong>PROMEDIO POR ASIGNATURAS</strong></td>
										<?php foreach ($SubjectsInfo as $subjects) : ?>
											<?php $avg_assignments = $attendance->getAssignmentAverage($subjects->id_assignment, $_GET['id_period'], $_GET['average_type']); ?>
											<?php if (!empty($avg_assignments)) : ?>
												<?php $avg_assignments = $avg_assignments[0] ?>
												<?php $avg_assignment = round($avg_assignments->avg_assignment, 1); ?>
												<td align="CENTER" valign=""><strong><?= $avg_assignment ?></strong></td>
											<?php else : ?>
												<td align="CENTER" valign=""><strong>-</strong></td>
											<?php endif ?>
										<?php endforeach ?>
									</tr>
									<tr>
										<th colspan="100%" style="font-size:14px; font-weight:bold !important;  " class="text-center font-weight-bold col-md-2">PROMEDIO GRUPAL</th>
									</tr>
									<tr>
										<th colspan="100%" style="font-size:14px; font-weight:bold !important;  " class="text-center font-weight-bold col-md-2"><?= number_format($general_prom, 1, '.', ' '); ?></th>
									</tr>
								</thead>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>