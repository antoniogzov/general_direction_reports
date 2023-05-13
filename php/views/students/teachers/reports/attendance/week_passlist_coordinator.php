<?php
include dirname(__DIR__, 1) . '/card_select_passlist_coordinator.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_academic']) && isset($_GET['id_teacher'])) {
    $id_academic = $_GET['id_academic'];
    $id_teacher = $_GET['id_teacher'];
    $getSubjects = $attendance->getAllSubjectsFromMyTeachers($id_academic, $id_teacher);
    /* echo count($getSubjects); */
    $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $arr_week = explode("-", $_GET['week']);
    $arr_init_date = explode("/", $arr_week[0]);
    $arr_final_date = explode("/", $arr_week[1]);

    $init_date = $arr_init_date[2] . "-" . $arr_init_date[0] . "-" . $arr_init_date[1];
    $final_date = $arr_final_date[2] . "-" . $arr_final_date[0] . "-" . $arr_final_date[1];
    $std_number = 0;
    $dif = ((strtotime($init_date) - strtotime($final_date)) / 86400);
    $partes = explode("-", $init_date);

    $mes = $arr_init_date[0];
    $year = $arr_init_date[2];
}
?>

<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<?php if (!empty($getSubjects)) : ?>
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
                    <!-- <h3 class="ml-2">ASISTENCIA PARA EL DÍA: <?= date('Y-m-d') ?></h3> -->
                    <!-- <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3> -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">Materia</th>
                                    <?php
                                    for ($i = 1; $i < 6; $i++) {
                                        $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                        $fecha = date("Y-m-d", $dia);
                                        $dia_arr = explode('-', $fecha);
                                    ?>

                                        <th><?= $days[$i] ?> | <?= $dia_arr[2] ?></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php foreach ($getSubjects as $subjects) :

                                ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $subjects->name_subject ?></th>
                                            <?php for ($i = 1; $i < 6; $i++) {
                                                $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                                $fecha = date("Y-m-d", $dia);
                                                $pases_de_lista = 0;
                                                $groups_ids = "";
                                                $groups_ids_block = "";
                                                
                                                $indexs_groups = array();
                                                $getGroupsAss = $attendance->getListGroupAss($id_teacher, $subjects->id_subject);


                                                if (!empty($getGroupsAss)) {
                                                    foreach ($getGroupsAss as $groups_ass) {
                                                        $id_assignment_gps = $groups_ass->id_assignment;
                                                        $getAttendance = $attendance->getAttendanceIndexReportAssignment($id_assignment_gps, $fecha);

                                                        if (!empty($getAttendance)) {
                                                            foreach ($getAttendance as $attendancess) {
                                                                $pases_de_lista++;
                                                                $groups_ids .= $groups_ass->id_group . ",";
                                                                $groups_ids_block .= $groups_ass->id_group."class_block".$attendancess->class_block . "class_block".$attendancess->apply_date . ",";
                                                               
                                                            }
                                                        }
                                                    }
                                                }




                                            ?>
                                        <td class="font-weight-bold"><button type="button" onclick="getAssistanceDetails('<?= $groups_ids_block ?>')" class="btn btn-outline-secondary btn_std_attendance" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $pases_de_lista ?> </button> </th>
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

<?php endif; ?>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/week_passlist_report.js"></script>