<?php
$getSubjects = $subjects->getSubjectsGroupsFromTeacherByAcademicArea($no_teacher, $id_academic_area);
?>
<?php if (!empty($getSubjects)) : ?>
	<div class="container-fluid mt--6">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header border-0">
						<h3 class="mb-0">Materias</h3>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-bordered table-flush" id="tableSubjectsTeachers">
							<thead class="thead-dark">
								<tr>
									<th scope="col" class="font-weight-bold">Asignaturas</th>
									<th scope="col" class="font-weight-bold">Nivel académico</th>
									<th scope="col" class="font-weight-bold">Grado y grupo</th>
									<th scope="col" class="font-weight-bold"></th>
									<th scope="col" class="font-weight-bold"></th>
								</tr>
							</thead>
							<tbody class="list">
								<?php foreach ($getSubjects as $row) : ?>
									<tr>
										<th scope="row" style="overflow: hidden !important; white-space: nowrap;">
											<span class="name mb-0 text-sm"><?= $row->name_subject ?></span>
										</th>
										<td scope="row">
											<?= $row->degree ?>
										</td>
										<td scope="row">
											<?= $row->group_code ?>
										</td>
										<td scope="row" class="text-center">
											<div class="avatar-group">
												<a href="evaluaciones.php?id_assignment=<?= $row->id_assignment ?>" class="avatar avatar-sm rounded-circle hover mr-2" data-toggle="tooltip" data-original-title="Calificar P.E.">
													<i class="ni ni-paper-diploma"></i>
												</a>
												<?php if ($assignments->hasAssociatedLearningMap($row->id_assignment) > 0) : ?>
													<a href="evaluaciones_cualitativas.php?id_assignment=<?= $row->id_assignment ?>" class="avatar avatar-sm rounded-circle bg-warning hover mr-2" data-toggle="tooltip" data-original-title="MDA">
														<i class="ni ni-ruler-pencil"></i>
													</a>
												<?php endif; ?>
												<?php if(count($expected_learnings->getExistCatalogAssignmentsIndex($row->id_assignment))>0): ?>
												<a href="aprendizajes_esperados.php?id_assignment=<?= $row->id_assignment ?>" class="avatar avatar-sm rounded-circle hover" style="background-color:#0059d5 !important;" data-toggle="tooltip" data-original-title="AE">
													<i class="ni ni-trophy"></i>
												</a>
												<?php endif; ?>
											</div>
										</td>
										<?php

													$visib_btn_xprt = "none";
													$count_visib = 0;
													$conf_color= "";
													if (!empty($id_level_combination = $helpers->getIdsLevelCombination($row->id_assignment))) {
														$id_level_combination = $id_level_combination->id_level_combination;
													}
													$getPeriods = $subjects->getPeriods($id_level_combination);
													foreach ($getPeriods as $row_period) {
														$id_pcal = $row_period->id_period_calendar;

														$getSsbjWOPlan = $subjects->getCountEvalPlanSBJ($row->id_assignment, $id_pcal);
														foreach ($getSsbjWOPlan as $row2) {

															if ($row2->n_evaluations > 0) {

																$count_visib++;

															}
														}
														if ($count_visib > 0) {
															$visib_btn_xprt = "";
															$conf_color= "rgba(34, 163, 39, 0.6)";
														}
													}

													?>
										<td class="text-right" style=" background-color:<?= $conf_color ?> !important;">
											<div class="dropdown">
												<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fas fa-ellipsis-v"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
													<a title="Configuración de Plan de Evaluacón" class="dropdown-item" href="configuracion_evaluaciones.php?ac_ar=<?= $id_academic_area ?>&id_assignment=<?= $row->id_assignment ?>"><i class="ni ni-settings-gear-65"></i> Configuración P.E.</a>
													<a title="Configuración de Aprendizajes Esperados" class="dropdown-item" href="configuracion_aprendizajes.php?id_academic_area=<?= $id_academic_area ?>&id_assignment=<?= $row->id_assignment ?>"><i class="ni ni-settings-gear-65"></i> Configuración A.E.</a>
													<?php
													$visib_btn_xprt = "none";
													if ($grants & 8) {
														$getSsbjWOPlan = $subjects->getCountEvalPlanSBJ($row->id_assignment);
														$visib_btn_xprt = "none";
														foreach ($getSsbjWOPlan as $row2) {

															if ($row2->n_evaluations > 0) {
																$visib_btn_xprt = "";
															}
														}
													}
													?>
													<a class="dropdown-item btn_export_subject_config" style="display:<?= $visib_btn_xprt ?>;" id="<?= $row->id_assignment ?>" href="#"><i class="ni ni-cloud-upload-96"></i> Exportar Configuración </a>
													<a class="dropdown-item btnAveragePeriod" id="<?= $row->id_assignment ?>" href="#"><i class="ni ni-check-bold"></i> Resumen de Promedio Grupal</a>
												</div>
											</div>
										</td>
									</tr>
								<?php endforeach;
								include 'php/views/index/modal_export_config.php';
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="card">
			<h2 class="text-center p-4">Al parecer no tiene materias asociadas :(</h2>
		</div>
	<?php endif; ?>
	<script src="js\functions\evaluations\index_averages.js"></script>