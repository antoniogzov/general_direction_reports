<div class="card">
	<!-- Card header -->
	<div class="card-header border-0">
		<h3 class="mb-0">Alumnos</h3>
	</div>
	<!-- Light table -->
	<div class="table-responsive tbl-students">
		<table class="table align-items-center table-flush table-striped">
			<thead class="thead-light">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Código alumno</th>
					<th scope="col">Nombre</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody class="list">
				<?php $contador = 1; ?>
				<?php foreach($listStudent as $student): ?>
					<?php $statusFormStudent = $cualitatives->checkIfEvaluationMPA($ascc_lm_assgn, $assc_mpa_id, $no_installment, $student->id_student); ?>
					<tr id="<?= $student->id_student ?>">
						<td><?= $contador; ?></td>
						<td><?= $student->student_code ?></td>
						<td><?= mb_strtoupper($student->name_student) ?></td>
						<td>
							<?php if($assc_mpa_id != 'comments'): ?>
								<?php if($statusFormStudent == 0): ?>
									<button type="button" class="btn-qualify btn btn-primary">Calificar</button>
								<?php else: ?>
									<button type="button" class="btn-update-mpa btn btn-<?= $statusFormStudent == 1 ? 'warning' : 'success'; ?>" data-id-historical-map="<?= $cualitatives->getInfoIndexMDA($ascc_lm_assgn, $assc_mpa_id, $no_installment, $student->id_student) ?>">Editar</button>
								<?php endif; ?>
							<?php else: ?>
								<?php if(!empty($infoComments = $cualitatives->getFinalCommentsMPA($ascc_lm_assgn, $no_installment, $student->id_student))): ?>
									<button type="button" class="btn-update-comments btn btn-success" data-id-comments="<?= $infoComments->id_comments ?>">Editar Comentarios</button>
								<?php else: ?>
									<button type="button" class="btn-nw-comments btn btn-primary">Agregar Comentarios</button>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					</tr>
					<?php $contador++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>