<?php

$listStudent = array();
$listIncidents = array();
if (($grants & 8)) {
    $getIncidents = $attendance->getIncidentsCoordinator();
} else if (($grants & 4)) {
    $getIncidents = $attendance->getIncidentsTeacher();
}
/* var_dump($getIncidents);
echo "aaaaaaaaaaa"; */
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
<script src="vendor/tablefilter/tablefilter.js"></script>
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
                                    <th class="font-weight-bold col-md-2 text-center">Nomre de Alumno</th>
                                    <th class="font-weight-bold col-sm-2 text-center">Grupo</th>
                                    <th class="font-weight-bold col-sm-2 text-center">Fecha</th>
                                    <th class="font-weight-bold col-sm-2 text-center">Subclasificación</th>
                                    <th class="font-weight-bold col-sm-2 text-center">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php foreach ($getIncidents as $incidents) :
                                    $date = explode(" ", $incidents->incident_date);
                                    $date_formatted = date("d-m-Y", strtotime($date[0]));
                                    $class_color = $incidents->clasification_color_html;
                                ?>
                                    <tr style="color: <?= $class_color ?> !important;">
                                        <td class="font-weight-bold text-center" title="<?= $incidents->student_code ?>"><?= mb_strtoupper($incidents->name_student) ?></td>
                                        <td class="font-weight-bold text-center"><?= $incidents->group_code ?></td>
                                        <td class="font-weight-bold text-center"><?= $date_formatted ?></td>
                                        <td class="font-weight-bold text-center"><?= $incidents->clasification_degree ?></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only infoIncidentStudent" id="<?= $incidents->id_student_incidents_log ?>" href="#" role="button">
                                                    <i class="fas fa-info"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <!--  <td class="font-weight-bold"><?= $incidents->incident_description ?></td> -->
                                        <!--  <td class="font-weight-bold"><?= $incidents->incident_commit ?></td> -->
                                        <!--  <td class="font-weight-bold"><?= $incidents->name ?></td> -->

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
<script>
    if ($('#tStudents').length > 0) {
       /*  var tf = new TableFilter('tStudents', {
            base_path: '../general/js/vendor/tablefilter/tablefilter/',

        });
        tf.init();
 */

        var filtersConfig = {
            base_path: '../general/js/vendor/tablefilter/tablefilter/',
            paging: {
                results_per_page: ['Resultados: ', [10, 25, 50, 100]]
            },
            state: {
                types: ['local_storage'],
                filters: true,
                page_number: true,
                page_length: true,
                sort: true
            },
            alternate_rows: true,
            btn_reset: true,
            rows_counter: true,
            col_0: '',
            col_1: 'select',
            col_3: 'select',
            col_4: 'none',
            btn_reset: true,
            extensions: [{
                name: 'sort'
            }]
        };
        var tf = new TableFilter('tStudents', filtersConfig);
        tf.init();
    }
</script>