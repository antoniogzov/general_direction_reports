<?php
include dirname(__DIR__, 1) . '/card_select_group_assignment.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_subject']) && isset($_GET['id_group'])) {
	$listStudent = $attendance->getListStudent($id_group, $id_subject);
	$listIncidents = $attendance->getListIncidents();
}
?>
<?php if (!empty($listStudent)) : ?>
	<style>
		.swal-wide{
    width:850px !important;
}
	</style>
	<div class="card">
		<div class="card-body card-attendance">
			<div class="row mb-5">
				<div class="col-md-3">
					<div class="custom-control custom-radio mb-3">
						<input type="radio" id="customRadio1" value="1" name="class_block" checked class="custom-control-input">
						<label class="custom-control-label" for="customRadio1">Primer bloque</label>
					</div>
					<div class="custom-control custom-radio">
						<input type="radio" id="customRadio2" value="2" name="class_block" class="custom-control-input">
						<label class="custom-control-label" for="customRadio2">Segundo bloque</label>
					</div>
				</div>
				<div class="col-md-6"></div>
				<div class="col-md-3">
					<div class="input-group mb-3">
						<input type="text" class="form-control date-input" placeholder="Buscar por fecha">
						<div class="input-group-append">
							<?php if ($grants == 15 || $grants == 31) : ?>
								<button class="btn btn-outline-info" type="button" id="btn_search_attendance_coordinator"><i class="fas fa-search"></i></button>
							<?php else : ?>
								<button class="btn btn-outline-info" type="button" id="btn_search_attendance"><i class="fas fa-search"></i></button>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="div-button-refresh col-md-3" style="display: none">
					<input type="button" class="btn btn-warning float-right" onClick="window.location.reload();" value="Tomar nueva asistencia" />
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 container-list-students">
					<h3 class="ml-2">ASISTENCIA PARA EL DÍA: <?= date('Y-m-d') ?></h3>
					<h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3>
					<div class="table-responsive">
						<table class="table align-items-center table-flush" id="tStudents">
							<thead class="thead-light">
								<tr>
									<th></th>
									<th class="font-weight-bold col-md-2">Cód. alumno</th>
									<th class="font-weight-bold col-md-4">Nombre</th>
									<th class="font-weight-bold col-md-2">Presente</th>
									<th class="col-md-4">Observaciones</th>
									<th class="col-md-4">Comentario</th>
									<th class="col-md-4" style="display:none">Aplicar justificación</th>
								</tr>
							</thead>
							<tbody class="list">
								<?php foreach ($listStudent as $student) : ?>
									<?php
									$getStudentJustify = $attendance->getStudentJustify($student->id_student);
									$getStudentUnJustify = $attendance->getStudentUnJustify($student->id_student);
									$attr_check = "checked";
									$id_incident_just = 1;
									$excuse_description = "";
									$attr_select = "";
									$attr_apply_justification = '';
									$html_btn_comentario = '<button class="btn btn-icon btn-info btn-sm btnViewCommentary"  type="button" data-comentario="NINGÚN COMENTARIO"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>';
									if (!empty($getStudentJustify)) {
										$id_incident_just = 3;
										$excuse_description = $getStudentJustify[0]->excuse_description;
										$break_apply = $getStudentJustify[0]->break_apply;
										$break_active = $getStudentJustify[0]->break_active;
										$attr_apply_justification = '';
										$teacher_commentary = $getStudentJustify[0]->teacher_commit;
										if ($teacher_commentary == '') {
											$html_btn_comentario = '<button class="btn btn-icon btn-info btn-sm btnViewCommentary"  type="button" data-comentario="NINGÚN COMENTARIO"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>';
										} else {
											$html_btn_comentario = '<button class="btn btn-icon btn-info btn-sm btnViewCommentary"  type="button" data-comentario="' . $teacher_commentary . '"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>';
										}
										if ($break_apply == 1) {
											$attr_apply_justification = "checked";
										}

										//$justify = $getStudentJustify[0]->justify;
										$attr_check = "";
										$attr_select = "disabled";
									} else if (!empty($getStudentUnJustify)) {
										$id_incident_just = 1;
										$excuse_description = $getStudentUnJustify[0]->excuse_description;
										$double_absence = $getStudentUnJustify[0]->double_absence;
										$break_apply = $getStudentUnJustify[0]->break_apply;
										$break_active = $getStudentUnJustify[0]->break_active;
										$attr_apply_justification = '';
										$teacher_commentary = $getStudentUnJustify[0]->teacher_commit;
										if ($teacher_commentary == '') {
											$html_btn_comentario = '<button class="btn btn-icon btn-info btn-sm btnViewCommentary"  type="button" data-comentario="NINGÚN COMENTARIO"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>';
										} else {
											$html_btn_comentario = '<button class="btn btn-icon btn-info btn-sm btnViewCommentary"  type="button" data-comentario="' . $teacher_commentary . '"><span class="btn-inner--icon"><i class="fa fa-eye"></i></span></button>';
										}
										if ($break_apply == 1) {
											$attr_apply_justification = "checked";
										}

										//$justify = $getStudentJustify[0]->justify;
										$attr_check = "";
										$attr_select = "disabled";
									}
									?>
									<tr>
										<th scope="row">
											<div class="media align-items-center">
												<a class="avatar rounded-circle mr-3 imgStudent">
													<?php if (file_exists("../control_escolar/students_archives/" . $student->student_code . ".jpg")) : ?>
														<img alt="Image placeholder" src="../control_escolar/students_archives/<?= $student->student_code ?>.jpg">
													<?php else : ?>
														<img alt="Image placeholder" src="../control_escolar/students_archives/default.png">
													<?php endif; ?>
												</a>
											</div>
										</th>

										<td>
											<button class="btn btnGetStudentAttedanceHistoric" data-student-code="<?= $student->student_code ?>" data-name-student="<?= $student->name_student ?>" data-id-student="<?= $student->id_student ?>" data-id-subject="<?= $_GET['id_subject'] ?>" data-id-group="<?= $_GET['id_group'] ?>">
												<?= mb_strtoupper($student->student_code) ?>
											</button>
										</td>
										<td><?= strtoupper($student->name_student) ?></td>
										<td class="text-center">
											<label class="custom-toggle custom-toggle-success">
												<input type="checkbox" class="check-student" id="<?= $student->id_student; ?>" <?= $attr_check ?> <?= $attr_select ?>>
												<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
											</label>
										</td>
										<td class="td-incidents-rollcall">
											<div class="form-group">
												<select class="form-control-sm select-incidents-rollcall" <?= $attr_select ?> id="select_observation" data-id-student="<?= $student->id_student; ?>">
													<?php foreach ($listIncidents as $incident) : ?>
														<?php if (!empty($getStudentJustify)) : ?>
															<?php if ($incident->incident_id == $id_incident_just) : ?>
																<option selected value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
															<?php else : ?>
																<?php if ($incident->incident_id == '1') : ?>
																	<option selected value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																<?php else : ?>
																	<?php if ($_SESSION['grantsITEQ'] <= 7) : ?>
																		<?php if ($incident->show_teacher == 1) : ?>
																			<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																		<?php endif; ?>
																	<?php else : ?>
																		<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>

														<?php else : ?>

															<?php if (!empty($getStudentUnJustify)) : ?>

																<?php if ($double_absence == '1') : ?>

																	<?php if ($incident->incident_id == '10') : ?>
																		<option selected value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php else : ?>
																		<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php endif; ?>
																<?php else : ?>
																	<?php if ($incident->incident_id == '1') : ?>
																		<option selected value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php else : ?>
																		<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php endif; ?>
																<?php endif; ?>

															<?php else : ?>
																<?php if ($incident->incident_id == '1') : ?>
																	<option selected value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																<?php else : ?>
																	<?php if ($_SESSION['grantsITEQ'] <= 7) : ?>
																		<?php if ($incident->show_teacher == 1) : ?>
																			<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																		<?php endif; ?>
																	<?php else : ?>
																		<option value="<?= $incident->incident_id; ?>"><?= $incident->incident; ?></option>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
												<br>
												<em style="color:red"><?= $excuse_description ?></em>
											</div>
										</td>
										<td class="text-center">
											<?= $html_btn_comentario ?>
										</td>
										<td class="text-center" style="display:none">
											<label class="custom-toggle custom-toggle-success">
												<input type="checkbox" class="check-apply-justification" id="check_justification<?= $student->id_student; ?>" <?= $attr_apply_justification ?>>
												<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
											</label>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="col mt-4 d-flex">
						<!-- -->
						<div class="col float-left">
							<label>¿Lección obligatoria?</label>
							<label class="custom-toggle custom-toggle-info">
								<input type="checkbox" class="compulsory-class" checked>
								<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
							</label>
						</div>
						<!-- -->
						<input type="button" class="btn btn-primary float-right mr-5" id="btn_guardar_asistencia" value="Guardar Asistencia" />
						<!-- -->
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>