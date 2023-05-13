<script src="js/weekPickerJS.js"></script>
<script src="js/weekPickerJS.min.js"></script>
<script src="vendor/tablefilter/tablefilter.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<?php
include dirname(__DIR__, 1) . '/card_select_week_incidents.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['week_range'])) {
    if (($grants & 8)) {

        $getIncidents = $attendance->getIncidentsCoordinatorDateRange($week_range_start_date, $week_range_end_date);
    } else if (($grants & 4)) {
        $getIncidents = $attendance->getIncidentsTeacherDateRange($week_range_start_date, $week_range_end_date);
    }

    //echo "getIncidentsCoordinatorDateRange($week_range_start_date, $week_range_end_date)"."<br>";
?>

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
                                        <th class="font-weight-bold col-md-2">Código de Alumno</th>
                                        <th class="font-weight-bold col-sm-2">Fecha</th>
                                        <th class="font-weight-bold col-sm-2">Subclasificación</th>
                                        <th class="font-weight-bold col-sm-2">Descripción</th>
                                        <th class="font-weight-bold col-sd-2">Comentario</th>
                                        <th class="font-weight-bold col-md-2">Profesor</th>
                                    </tr>
                                </thead>
                                <tbody class="list">

                                    <?php foreach ($getIncidents as $incidents) :
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
            </div>
        </div>

    <?php else : ?>
        <div class="card">
            <div class="card-body">
                <h1>NO SE ENCONTRARON REGISTROS EN LOS PARÁMETROS ESTABLECIDOS</h1>
            </div>

        </div>
    <?php endif; ?>

<?php
}
?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/incidents_reports.js"></script>
<script>
    if ($('#tStudents').length > 0) {
        var tf = new TableFilter('tStudents', {
            base_path: '../general/js/vendor/tablefilter/tablefilter/',
            col_0: '',
            col_1: '',
            col_2: 'select',
            col_3: '',
            col_4: '',
            col_5: 'select',
            col_6: 'select',
            auto_filter: {
                delay: 100
            },
            btn_reset: true,
        });
        tf.init();
    }
</script>