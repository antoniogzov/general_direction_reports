<?php
include dirname(__DIR__, 1) . '/card_select_history_c.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_academic']) && isset($_GET['id_teacher'])) {
    $id_academic = $_GET['id_academic'];
    $id_teacher = $_GET['id_teacher'];
    $getIndexes = $attendance->getAllIndexesCoordReport($id_teacher,$_GET['report_type']);
    /* echo count($getIndexes); */
    $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
}
?>
<?php if (isset($_GET['id_academic']) && isset($_GET['id_teacher'])) : ?>
<?php if (!empty($getIndexes)) : ?>
    <div class="card">
        <div class="card-body">
            <h2>Registros encontrados</h2>
            <div class="row mb-5">
                <div class="col-md-3">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 container-list-students">
                    <!-- <h3 class="ml-2">ASISTENCIA PARA EL DÍA: <?= date('Y-m-d') ?></h3> -->
                    <!-- <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3> -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">Fecha</th>
                                    <th class="font-weight-bold col-md-2">Hora</th>
                                    <th class="font-weight-bold col-md-2">Bloque</th>
                                    <th class="font-weight-bold col-md-2">Grupo</th>
                                    <th class="font-weight-bold col-md-2">Materia</th>
                                    <th class="font-weight-bold col-md-2">TIPO</th>
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php
                                $fecha_comparar = "";
                                $id_assignment = "";
                                foreach ($getIndexes as $attendances) :
                                    $arr_fecha_pr = explode(" ", $attendances->apply_date);
                                    $arr_fecha = explode("-", $arr_fecha_pr[0]);
                                    $hora = $arr_fecha_pr[1];
                                    // $fecha = $arr_fecha[2] . " de " . $months[1] . " de " . $arr_fecha[0];
                                    $tipo_lista = "OBLIGATORIA";
                                    if ($attendances->obligatory < 1) {
                                        $tipo_lista = "OPCIONAL";
                                    }
                                    


                                ?>
                                        <tr>
                                            <td class="font-weight-bold">
                                                <a style="font-weight:bold; color:black !important; font-weight:normal; padding-left: 1px !important; padding-right:1px !important;" href="?submodule=history_assistance_detail&id_index=<?= $attendances->id_attendance_index ?>" target="_blank"><?= $arr_fecha_pr[0] ?>
                                                    <button type="button" id="<?= $attendances->id_attendance_index ?>" class="btn btn-outline-secondary btn_std_attendance" style="{font-weight:bold; color:black !important; font-weight:normal; padding-left: 1px !important; padding-right:1px !important;}">
                                                    </button>
                                                </a>
                                            </td>
                                            <td class="font-weight-bold"><?= $hora ?> hrs.</th>
                                            <td class="font-weight-bold"><?= $attendances->class_block ?></th>
                                            <td class="font-weight-bold"><?= $attendances->group_code ?></th>
                                            <td class="font-weight-bold"><?= $attendances->name_subject ?></th>
                                            <td class="font-weight-bold"><?= $tipo_lista ?></th>
                                        </tr>

                                <?php
                                    
                                    $id_assignment = $attendances->id_assignment;
                                    $fecha_comparar = $arr_fecha_pr[0];
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="card">
        <div class="card-body">
            <h2>NO SE ENCONTRARON REGISTROS</h2>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/attendance_history.js"></script>