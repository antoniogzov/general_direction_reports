<?php
include dirname(__DIR__, 1) . '/card_select_general.php';



$listStudent = array();
$listIncidents = array();


$days = array();
$months = array();
$arr_week = array();
$arr_init_date = array();
$arr_final_date = array();
$init_date = array();
$final_date = array();
$std_number = array();
$dif = array();
$partes = array();
$mes = array();
$year = array();


if (isset($_GET['week'])) {
    $getSections = $attendance->getGroupType();
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

if (!empty($listGroups)) {
    foreach ($listGroups as $gp) {
        if (!empty($id_assingment = $helpers->getAssignmentByGroup($gp->id_group))) {
            foreach ($id_assingment as $assignment) {
                $id_assingment = $assignment->id_assignment;
            }
        }


        if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
            $id_level_combination = $id_level_combination->id_level_combination;
            $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
        }
    }
}
?>
<?php
if (!empty($getSections)) {
    foreach ($getSections as $section) {

        $listGroups = $attendance->getListOfGroupsByType($section->group_type_id, $_GET['id_academic']);
        if (!empty($listGroups)) : ?>
            <script>
                Swal.fire({
                    text: 'Cargando...',
                    html: '<img src="images/loading_iteach.gif" width="300" height="300">',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                })
            </script>
            



            <div class="card">
                <div class="card-body">

                    <?php if (!empty($listGroups)) : ?>
                        <script src="../general/js/vendor/tablefilter/tablefilter/tablefilter.js" async></script>

                        <div class="row">
                            <div class="col">
                                <h3 id="txt_grupo"></h3>
                                <h1 id="txt_nmb_std"><?= strtoupper($section->group_type) ?></h1>
                            </div>
                            <div class="w-100"></div>
                            <div class="col"></div>
                            <div class="col"></div>
                        </div>
                        <div class="table-responsive">
                            <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
                                <thead class="thead-light">
                                    <tr>
                                        <th colspan="2">GRUPO</th>
                                        <?php
                                        for ($i = 1; $i < 6; $i++) {
                                            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                            $fecha = date("Y-m-d", $dia);
                                            $dia_arr = explode('-', $fecha);
                                        ?>
                                            <th colspan="2"><?= $days[$i] ?> | <?= $dia_arr[2] ?></th>
                                        <?php
                                        }
                                        ?>
                                        <th>INASISTENCIAS</th>
                                        <th colspan="2">INCIDENCIAS</th>
                                        <th>P.A.</th>
                                    </tr>
                                    <tr style="background:rgba(179, 179, 179,0.4) !important;">
                                        <td>C. iTeach</td>
                                        <td>Alumnos Inscritos</td>
                                        <?php
                                        for ($i = 1; $i < 6; $i++) {
                                            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                            $fecha = date("Y-m-d", $dia);
                                            $dia_arr = explode('-', $fecha);
                                        ?>
                                            <td title="Clases Dadas">C.D.</td>
                                            <td title="Promedio de Asistencia">P.A.</td>
                                        <?php
                                        }
                                        ?>
                                        <td title="Inasistencias semanales">I.S.</td>
                                        <td title="Faltas justificadas">F.J.</td>
                                        <td title="Otras">Ot.</td>
                                        <td title="Porcentaje de asistencia">P.A.</td>
                                    </tr>
                                </thead>

                                <tbody class="list">
                                    <?php
                                    $section_percentage = 0;
                                    foreach ($listGroups as $groups) {
                                        $getStudents = $attendance->getListStudentByGroup($groups->id_group);
                                        $semanal_faltas = 0;
                                        $faltas_justificadas = 0;
                                        $otras_incidencias = 0;
                                        $semanal_asistencias = 0;
                                        $semanal_esperado = 0;
                                        $percentage_semanal = 0;
                                        $str_inasistencias = "";
                                        $str_otras_incidencias = "";
                                        $str_faltas_justificadas = "";
                                    ?>

                                        <tr>
                                            <td title="<?= $groups->desglose ?>"><?= $groups->group_code ?></td>
                                            <td><?= count($getStudents) ?></td>
                                            <?php for ($i = 1; $i < 6; $i++) {
                                                $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                                $fecha = date("Y-m-d", $dia);
                                                $dia_arr = explode('-', $fecha);

                                                $asg = "";
                                                $asistance_day = 0;
                                                $student_asistance_day = 0;
                                                $student_asistance_day_percentage = 0;
                                                $group_asistance_day = 0;
                                                $indexs_attendance = "";
                                                $getGroupAssignment = $helpers->getAllAssignmentByGroupSection($groups->id_group, $_GET['id_academic']);
                                                foreach ($getGroupAssignment as $group_assignment) {
                                                    $id_assignment = $group_assignment->id_assignment;

                                                    $getAttendance = $attendance->getAttendanceIndex($id_assignment, $fecha);


                                                    if (!empty($getAttendance)) {
                                                        $id_attendance_index = $getAttendance[0]->id_attendance_index;
                                                        $asistance_day++;
                                                        $semanal_esperado++;
                                                        $indexs_attendance .= $id_attendance_index.',';
                                                        foreach ($getStudents as $student) {
                                                            $getAttendanceStudent = $attendance->getAttendanceStudent($id_attendance_index, $student->id_student);
                                                            if (!empty($getAttendanceStudent)) {
                                                                $attend = $getAttendanceStudent[0]->attend;
                                                                if ($attend == 1) {
                                                                    $student_asistance_day++;
                                                                    $semanal_asistencias++;
                                                                } else {
                                                                    $semanal_faltas++;
                                                                    $str_inasistencias .= $getAttendanceStudent[0]->id_attendance_record . "-";
                                                                }
                                                                $sql_incident = $getAttendanceStudent[0]->incident_id;
                                                                switch ($sql_incident) {
                                                                    case 3:
                                                                        $faltas_justificadas++;
                                                                        $str_faltas_justificadas .= $getAttendanceStudent[0]->id_attendance_record . ",";
                                                                        break;
                                                                    case '2':
                                                                        $otras_incidencias++;
                                                                        $str_otras_incidencias .= $getAttendanceStudent[0]->id_attendance_record . ",";
                                                                        break;
                                                                    case '4':
                                                                        $otras_incidencias++;
                                                                        $str_otras_incidencias .= $getAttendanceStudent[0]->id_attendance_record . ",";
                                                                        break;
                                                                    case '5':
                                                                        $otras_incidencias++;
                                                                        $str_otras_incidencias .= $getAttendanceStudent[0]->id_attendance_record . ",";
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            } else {
                                                            }
                                                        }
                                                    }
                                                }
                                                $student_asistance_day_percentage_num = 0;
                                                $group_asistance_day = ($asistance_day * count($getStudents));
                                                if ($group_asistance_day > 0) {
                                                    $student_asistance_day_percentage_num = ($student_asistance_day * 100)  / $group_asistance_day;
                                                    $student_asistance_day_percentage = $student_asistance_day  / $asistance_day;
                                                }

                                            ?>
                                                <td>
                                                <button type="button" data-ids-index="<?= $indexs_attendance ?>" class="btn btn-outline-secondary btn_attendance_indexs" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $asistance_day ?> </button>
                                                </td>
                                                <td title="<?= number_format($student_asistance_day_percentage_num, 0) ?> %"><?= number_format($student_asistance_day_percentage, 0) ?></td>

                                            <?php }
                                            $group_asistance_week = ($semanal_esperado * count($getStudents));
                                            //80
                                            //echo $group_asistance_day;
                                            if ($group_asistance_week > 0) {

                                                $percentage_semanal = ($semanal_asistencias/$group_asistance_week) * 100 ;
                                                $section_percentage += $percentage_semanal;
                                            }
                                            ?>
                                            <td><a style="{font-weight:bold; color:black !important; font-weight:normal; padding-left: 1px !important; padding-right:1px !important;}" href="?submodule=detalle_inasistencia&id_index=<?= $str_inasistencias ?>&week=<?= $_GET['week'] ?>&id_academic=<?= $_GET['id_academic'] ?>" target="_blank"><?= $semanal_faltas ?></a></td>
                                            <td><a href="?submodule=faltas_justificadas&id_index=<?= $str_faltas_justificadas ?>&week=<?= $_GET['week'] ?>&id_academic=<?= $_GET['id_academic'] ?>" target="_blank"><?= $faltas_justificadas ?></a></td>
                                            <td><a href="?submodule=otras_incidencias&id_index=<?= $str_otras_incidencias ?>&week=<?= $_GET['week'] ?>&id_academic=<?= $_GET['id_academic'] ?>" target="_blank"><?= $otras_incidencias ?></a></td>
                                            <td><?= number_format($percentage_semanal, 0) ?> %</td>

                                        </tr>
                                    <?php   }
                                    if ($section_percentage > 0) {
                                        $section_percentage = $section_percentage / count($listGroups);
                                    }

                                    ?>
                                    <thead class="thead-light">
                                        <tr class="table-active">
                                            <th class="col-md-2" colspan="100%">PORCENTAJE DE ASISTENCIA GRUPAL TOTAL</th>
                                        </tr>
                                        <tr>
                                            <td colspan="100%"><?= number_format($section_percentage, 0) ?> %</td>
                                        </tr>
                                    </thead>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
           
        <?php endif; ?>
        <script>
            Swal.close();
        </script>
<?php endif;
    }
}
?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/general_attendance_report.js"></script>