<?php
include dirname(__DIR__, 1) . '/card_select_incidents.php';

$listStudent = array();
$listIncidents = array();
$getIncidentsByGroup = array();
if (isset($_GET['id_group'])) {

    if (($grants & 8)) {
        $getIncidentsByGroup = $attendance->getIncidentsByGroupCoordinator($_GET['id_group']);
    } else if (($grants & 4)) {
        $getIncidentsByGroup = $attendance->getIncidentsByGroupTeacher($_GET['id_group']);
    }
}


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

<?php if (!empty($getIncidentsByGroup)) : ?>
    <div class="row">
        <div class="col-md-12 container-list-students">
            <h3 class="ml-2">REPORTE DE INCIDENCIAS</h3>
            <div class="table-responsive">
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold col-md-2">Nombre de Alumno</th>
                            <th class="font-weight-bold col-sm-2">Fecha</th>
                            <th class="font-weight-bold col-sm-2">Subclasificación</th>
                            <th class="font-weight-bold col-sm-2">Descripción</th>
                            <th class="font-weight-bold col-sd-2">Comentario</th>
                            <th class="font-weight-bold col-md-2">Profesor</th>
                        </tr>
                    </thead>
                    <tbody class="list">

                        <?php foreach ($getIncidentsByGroup as $incidents) :
                            $date = explode(" ", $incidents->incident_date);
                            $class_color = $incidents->clasification_color_html;
                        ?>
                            <tr style="color: <?= $class_color ?> !important;">
                                <td class="font-weight-bold" title="<?= $incidents->student_code ?>"><?= $incidents->name_student ?></td>
                                <td class="font-weight-bold"><?= $date[0] ?></td>
                                <td class="font-weight-bold"><?= $incidents->clasification_degree ?></td>
                                <td class="font-weight-bold"><?= $incidents->incident_description ?></td>
                                <td class="font-weight-bold"><?= $incidents->incident_commit ?></td>
                                <td class="font-weight-bold"><?= $incidents->name ?></td>

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
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/incidents_reports.js"></script>