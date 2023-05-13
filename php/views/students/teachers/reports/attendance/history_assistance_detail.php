<?php


$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_index'])) {
    $id_index = $_GET['id_index'];
    $getAttendanceDetails = $attendance->getHistoryDetails($id_index);
    $getAttendance = $attendance->getHistoryByID($id_index);

    $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
}
?>
<div class="card">
    <div class="card-body">
        <?php foreach ($getAttendanceDetails as $details) :
        $arr_fecha_pr = explode(" ", $details->apply_date);
        $hora_pase = $arr_fecha_pr[1];
            $str_profesor = "Profesor que realizó el pase de lista: " . $details->teacher_name;
            $str_grupo = "Grupo: " . $details->group_code . " | Materia: " . $details->name_subject;
            $str_block = "Hora: " . $hora_pase . " | Bloque: " . $details->class_block;
        ?>
        <?php endforeach; ?>
        <h2><?= $str_profesor ?></h2>
        <h2><?= $str_grupo ?></h2>
        <h2><?= $str_block ?></h2>
        <h2>ID de registro: ASIST - <?= $id_index ?></h2>
        <div class="row mb-5">
            <div class="col-md-3">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 container-list-students">

                <!-- <h3 class="ml-2">ASISTENCIA PARA EL DÍA: <?= date('Y-m-d') ?></h3> -->
                <!-- <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3> -->
                <div class="table-responsive">
                    <?php if (!empty($getAttendance)) : ?>

                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">CÓD. ALUMNO</th>
                                    <th class="font-weight-bold col-md-2">NOMBRE COMPLETO</th>
                                    <th class="font-weight-bold col-md-2">ASISTENCIA</th>
                                    <th class="font-weight-bold col-md-2">INCIDENCIA</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($getAttendance as $attendances) :
                                    $color = "#6abd80";
                                    $texto_asist = "PRESENTE";
                                    if ($attendances->attend == 0) {
                                        $color = "#ff8c7a";
                                        $texto_asist = "AUSENTE";
                                    }
                                ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $attendances->student_code ?></th>
                                        <td class="font-weight-bold"><?= $attendances->name_student ?></th>
                                        <td class="font-weight-bold" style="background-color:<?= $color ?> !important"><?= $texto_asist ?></th>
                                        <td class="font-weight-bold"><?= strtoupper($attendances->incident) ?></th>
                                        
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/attendance_history.js"></script>