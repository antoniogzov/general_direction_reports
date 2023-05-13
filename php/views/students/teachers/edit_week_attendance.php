<?php
include dirname(__DIR__, 1) . '/card_select_edit_week_attendance.php';

$listStudent = array();
$listIncidents = array();
$today = date('Y-m-d');
if (isset($_GET['id_subject']) && isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudent($id_group, $id_subject);
    $getAssignment = $attendance->GetIdAssignmentByIdGroupAndSubject($id_group, $id_subject);
    foreach ($getAssignment as $get_assignment) {
        $id_assignment = $get_assignment->id_assignment;
    }
    $listIncidents = $attendance->getListIncidents();

    $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $arr_fecha = explode("-", $_GET['week']);
    $class_block = $_GET['class_block'];
    $inicio = $arr_fecha[0];
    $fin = $arr_fecha[1];

    $arr_week = explode("-", $_GET['week']);
    $arr_init_date = explode("/", $arr_week[0]);
    $arr_final_date = explode("/", $arr_week[1]);

    $init_date = $arr_init_date[2] . "-" . $arr_init_date[0] . "-" . $arr_init_date[1];
    $final_date = $arr_final_date[2] . "-" . $arr_final_date[0] . "-" . $arr_final_date[1];
    $std_number = 0;
    $dif = ((strtotime($init_date) - strtotime($final_date)) / 86400);
    $partes = explode("-", $init_date);
}



?>
<?php if (!empty($listStudent)) : ?>

    <script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
    <script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">ASISTENCIA ENTRE EL DÍA: <?= $inicio ?> AL: <?= $fin ?></h3>
                    <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold">Cód. alumno</th>
                                    <th class="font-weight-bold">Nombre</th>
                                    <?php
                                    for ($i = 1; $i < 6; $i++) {
                                        $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                        $fecha = date("Y-m-d", $dia);
                                        $dia_arr = explode('-', $fecha);
                                    ?>
                                        <th colspan="2" class="font-weight-bold"><?= $days[$i] ?> | <?= $dia_arr[2] ?></th>
                                    <?php } ?>

                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($listStudent as $student) : ?>
                                    <tr>
                                        <td><?= strtoupper($student->student_code) ?></td>
                                        <td><?= ucfirst($student->name_student) ?></td>
                                        <?php
                                        for ($i = 1; $i < 6; $i++) {
                                            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                            $fecha = date("Y-m-d", $dia);
                                            $disabled = "";
                                            if ($fecha > $today) {
                                                $disabled = "disabled";
                                            }
                                            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                            $fecha_sql = date("Y-m-d", $dia);
                                            $getStudentAttend = $attendance->getStudentAttendWeek($id_assignment, $fecha_sql, $student->id_student, $class_block);
                                            if (!empty($getStudentAttend)) {
                                                foreach ($getStudentAttend as $get_student_attend) {
                                                    $id_attendance_record = $get_student_attend->id_attendance_record;
                                                    $attend = $get_student_attend->attend;
                                                    $class = "check-student-update";
                                                }
                                                if ($attend == 1) {
                                                    $check = "checked";
                                                } else {
                                                    $check = "";
                                                }
                                        ?>


                                                <td class="text-center">

                                                    <label class="custom-toggle custom-toggle-success">
                                                        <input type="checkbox" <?= $disabled; ?> class="check-student <?= $class; ?>" id="<?= $id_attendance_record ?>" <?= $check ?>>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                                    </label>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-warning btn_new_incident" <?= $disabled; ?> data-toggle="tooltip" data-original-title="Agregar incidencia" id="<?= $id_attendance_record ?>" data-toggle="modal" data-target="#addIncident">

                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </button>
                                                </td>
                                            <?php
                                            } else {
                                                $check = "";
                                                $class = "check-student-insert";
                                            ?>
                                                <td class="text-center">
                                                </td>

                                                <td class="text-center">

                                                    <label class="custom-toggle custom-toggle-success">
                                                        <input type="checkbox" data-target="<?= $fecha_sql; ?>" <?= $disabled; ?> class="check-student  <?= $class; ?>" id="<?= $id_assignment . "/" . $fecha_sql . "/" . $student->id_student; ?>" <?= $check ?>>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                                    </label>

                                                </td>
                                            <?php
                                            }

                                            ?>

                                        <?php } ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addIncident" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="modal-title-default">Agregar Incidencia </h3>
                    <h6 class="modal-title text-muted" id="datos_alumno"></h6>
                    <button type="button" id="cerrar_mdl_criterio" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="evaluation" class="form-label text-dark">Tipo de Incidencia</label>
                            <div class="input-group">
                                <h6 id="incidencia_seleccion"></h6>
                                <select class="form-control" id="id_incident" name="evaluation" required="required">
                                    <option value="">ELiga una opción</option>
                                    <?php
                                    $getListIncidents = $attendance->getListIncidents();
                                    foreach ($getListIncidents as $get_list_incidents) {
                                    ?>
                                        <option value="<?= $get_list_incidents->incident_id ?>"><?= strtoupper($get_list_incidents->incident) ?></option>
                                    <?php }  ?>




                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-info btn_add_incident" id="">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script src="js/functions/students/teachers/edit_week_attendance.js"></script>
<!-- DATE PICKER COORDINADOR -->
<?php if($grants == 15) : ?>
<script>
    Date.prototype.addDays = function(noOfDays) {
        var tmpDate = new Date(this.valueOf());
        tmpDate.setDate(tmpDate.getDate() + noOfDays);
        return tmpDate;
    }

    var myDate = new Date(); //today
    var today_date = myDate.addDays(-15); //add 1 day
    console.log(today_date);

    startDate1 = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate() - myDate.getDay());
    endDate1 = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate() - myDate.getDay() + 6);


    $('#week_picker').datepicker({
        autoclose: true,
        format: 'YYYY-MM-DD',
        forceParse: false,
        todayHighlight: true,
        toggleActive: true,
        startDate: new Date(startDate1),
        endDate: new Date(endDate1),
    }).on("changeDate", function(e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
        //$('#week_picker').datepicker("setDate", startDate);
        $('#week_picker').datepicker('update', startDate);
        //$('#week_picker').datepicker('maxDate', new Date());
        $('#week_picker').val((startDate.getMonth() + 1) + '/' + startDate.getDate() + '/' + startDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '/' + endDate.getDate() + '/' + endDate.getFullYear());
        $('#get_week_attendance').show();
    });
</script>
<?php endif; ?>
<!-- DATE PICKER DIRECTOR -->
<?php if($grants == 31) : ?>
    <script>
    Date.prototype.addDays = function(noOfDays) {
        var tmpDate = new Date(this.valueOf());
        tmpDate.setDate(tmpDate.getDate() + noOfDays);
        return tmpDate;
    }

    var myDate = new Date(); //today
    var today_date = myDate.addDays(-15); //add 1 day
    console.log(today_date);

    endDate1 = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate() - myDate.getDay() + 6);


    $('#week_picker').datepicker({
        autoclose: true,
        format: 'YYYY-MM-DD',
        forceParse: false,
        todayHighlight: true,
        toggleActive: true,
        endDate: new Date(endDate1),
    }).on("changeDate", function(e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
        //$('#week_picker').datepicker("setDate", startDate);
        $('#week_picker').datepicker('update', startDate);
        //$('#week_picker').datepicker('maxDate', new Date());
        $('#week_picker').val((startDate.getMonth() + 1) + '/' + startDate.getDate() + '/' + startDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '/' + endDate.getDate() + '/' + endDate.getFullYear());
        $('#get_week_attendance').show();
    });
</script>
<?php endif; ?>