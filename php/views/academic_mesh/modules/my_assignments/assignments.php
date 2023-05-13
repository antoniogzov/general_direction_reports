<?php
if (($grants & 8)) {
    $subjects = new Subjects;
    $getSubjects = $subjects->getSubjectsGroupsFromManagerAllAcademicArea($no_teacher);
}
?>

<?php if ($grants & 8) : ?>
    <?php if (!empty($getSubjects)) : ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="vendor/tablefilter/tablefilter.js"></script>
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col">
                    <div class="card col-md-12">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Materias</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tableSubjectsCoordinator" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold text-center">ID</th>
                                        <th class="font-weight-bold text-center">Asignaturas</th>
                                        <th class="font-weight-bold text-center">Nivel académico</th>
                                        <th class="font-weight-bold text-center">Grado y grupo</th>
                                        <th class="font-weight-bold text-center">Profesor</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($getSubjects as $row): ?>
                                        <tr>
                                            <td scope="row" style="overflow: hidden !important; white-space: nowrap;">
                                                <span class="name mb-0 text-sm"><?= $row->id_assignment ?></span>
                                            </td>
                                            <td scope="row" style="overflow: hidden !important; white-space: nowrap;">
                                                <span class="name mb-0 text-sm"><?= $row->name_subject ?></span>
                                            </td>
                                            <td scope="row">
                                                <?= $row->degree ?>
                                            </td>
                                            <td scope="row">
                                                <?= $row->group_code ?>
                                            </td>
                                            <td scope="row" style="overflow: hidden !important; white-space: nowrap;">
                                                <?= strtoupper($row->nombre_colaborador) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Asignaturas</th>
                                        <th>Nivel académico</th>
                                        <th>Grado y grupo</th>
                                        <th>Profesor</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php else : ?>
		<div class="card">
			<h2 class="text-center p-4">Al parecer no tiene materias asignaturas asociadas :(</h2>
		</div>
	<?php endif; ?>
	<script src="js\functions\assignments_coord\my_assignments.js"></script>
<?php endif; ?>