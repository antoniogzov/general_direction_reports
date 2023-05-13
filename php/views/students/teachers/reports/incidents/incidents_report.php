<?php
include dirname(__DIR__, 1) . '/card_select_incidents.php';

$listStudent = array();
$listIncidents = array();

$getIncidents = $attendance->getIncidents();

$days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
/* $arr_week = explode("-", $_GET['week']);
    $arr_init_date = explode("/", $arr_week[0]);
    $arr_final_date = explode("/", $arr_week[1]);

    $init_date = $arr_init_date[2] . "-" . $arr_init_date[0] . "-" . $arr_init_date[1];
    $final_date = $arr_final_date[2] . "-" . $arr_final_date[0] . "-" . $arr_final_date[1];
    $std_number = 0;
    $dif = ((strtotime($init_date) - strtotime($final_date)) / 86400);
    $partes = explode("-", $init_date);

    $mes = $arr_init_date[0];
    $year = $arr_init_date[2]; */
?>

<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<?php if (!empty($getIncidents)) : ?>
    <div class="card">
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-3">

                </div>
                <div class="div-button-refresh col-md-3" style="display: none">
                    <input type="button" class="btn btn-warning float-right" onClick="window.location.reload();" value="Tomar nueva asistencia" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">REPORTE DE INCIDENCIAS</h3>
                    <!-- <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3> -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">ID Incidencia</th>
                                    <th class="font-weight-bold col-md-2">ID Alumno</th>
                                    <th class="font-weight-bold col-md-2">Fecha</th>
                                    <th class="font-weight-bold col-md-2">Comentario</th>
                                    <th class="font-weight-bold col-md-2">Profesor</th>
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php foreach ($getIncidents as $incidents) :

                                ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $incidents->id_incidence_code ?></td>
                                        <td class="font-weight-bold"><?= $incidents->id_student ?></td>
                                        <td class="font-weight-bold"><?= $incidents->incident_date ?></td>
                                        <td class="font-weight-bold"><?= $incidents->incident_commit ?></td>
                                        <td class="font-weight-bold"><?= $incidents->name ?></td>

                                    </tr>
                                <?php endforeach; ?>
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
          <h1>NO SE ENCONTRARON REGISTROS EN LOS PARÁMETROS ESTABLECIDOS</h1>  
        </div>

    </div>
<?php endif; ?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/incidents_reports.js"></script>