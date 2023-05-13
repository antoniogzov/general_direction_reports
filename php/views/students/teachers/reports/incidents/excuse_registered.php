<?php

if (($grants & 8)) {
    $getRegisteredExcuses = $attendance->getRegisteredExcuses();
}
?>
<div class="card mb-4">
    <div class="card-body">

        <?php if (!empty($getRegisteredExcuses)) : ?>
            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">REPORTE DE JUSTIFICACIONES</h3>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">Cód. Alumno</th>
                                    <th class="font-weight-bold col-md-2">Nombre de Alumno</th>
                                    <th class="font-weight-bold col-sm-2">Descripción de Justificación</th>
                                    <th class="font-weight-bold col-sm-2">Fecha de Inicio</th>
                                    <th class="font-weight-bold col-sm-2">Fecha de Fin</th>
                                    <th class="font-weight-bold col-sd-2">Comentario</th>
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php foreach ($getRegisteredExcuses as $excuse) : ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $excuse->student_code ?></td>
                                        <td class="font-weight-bold"><?= $excuse->student_name ?></td>
                                        <td class="font-weight-bold"><?= $excuse->excuse_description ?></td>
                                        <td class="font-weight-bold"><?= $excuse->start_date ?></td>
                                        <td class="font-weight-bold"><?= $excuse->end_date ?></td>
                                        <td class="font-weight-bold"><?= $excuse->teacher_commit ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="card">
                <div class="card-body">
                    <h1>NO SE ENCONTRARON REGISTROS EN LOS PARÁMETROS ESTABLECIDOS</h1>
                </div>

            </div>
        <?php endif; ?>

    </div>
</div>


<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/incidents_reports.js"></script>