<?php
set_time_limit(0);
if (isset($_GET['id_assignment'])) {
    $id_assingment = $_GET['id_assignment'];
    $id_group = $_GET['id_group'];



    if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
        $id_level_combination = $id_level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}
$group_name = "";
$group_info = $helpers->getGroupInfo($id_group);

foreach ($group_info as $group) {
    $group_name = $group->group_code;
}

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
$fecha_titulo = $months[$mes] . " de " . $year;
?>
<?php if (!empty($listStudent)) : ?>
    <script src="../general/js/vendor/tablefilter/tablefilter/tablefilter.js" async></script>


    <div class="row">
        <div class="col">
            <h2><?= ucfirst($fecha_titulo) ?></h2>
            <h3 id="txt_grupo"><?= $group_name ?></h3>
            <h4 id="txt_nmb_std"></h4>
        </div>
        <div class="col">
            <p align="right" style="padding-bottom:1px !important; font-size:12px;" class="font-weight-bold">CD: Clases Datas</p>
            <p align="right" style="padding-bottom:1px !important; font-size:12px;" class="font-weight-bold">CA: Clases Asistidas</p>
        </div>
        <div class="w-100"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <div class="table-responsive">
        <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
            <thead class="thead-light">
                <tr>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">CÓD. ALUMNO</th>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">NOMBRE</th>
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
                    <th colspan="4">TOTAL</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <?php
                    for ($i = 1; $i < 6; $i++) { ?>
                        <th style="padding-left: 1px !important; padding-right:1px !important;">CD</th>
                        <th style="padding-left: 1px !important; padding-right:1px !important;">CA</th>
                    <?php
                    }
                    ?>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">CD</th>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">CA</th>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">CF</th>
                    <th style="padding-left: 1px !important; padding-right:1px !important;">PA</th>
                </tr>

            </thead>

            <tbody class="list">
                <?php
                $Assigments = $attendance->GetIdAssignmentByIdGroupAndTeacher($id_group);

                $group_pecentage = 0;
                $porcentaje_periodos = array();

                foreach ($listStudent as $student) :
                    $id_student = $student->id_student;
                    $std_number++;
                    $student_attendance_total = 0;
                    $registered_assistance_total = 0;
                    $attendance_dif_total = 0;
                    $std_percentage = 0;
                ?>
                    <tr id="<?= $student->id_student; ?>">

                        <td style="padding-left: 3px !important; padding-right:1px !important; max-width:60px !important;"><?= strtoupper($student->student_code) ?></td>
                        <td style="padding-left: 1px !important; padding-right:3px !important;"><?= ucfirst($student->name_student) ?></td>

                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $indexes = array();
                            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                            $fecha = date("Y-m-d", $dia);
                            $registered_assistance = 0;
                            $student_attendance = 0;
                            $attendance_dif = 0;
                            foreach ($Assigments as $assignments) {
                                $group_assignment = $assignments->id_assignment;

                                $TeacherClassess = $attendance->getAttendanceIndex($group_assignment, $fecha);
                                foreach ($TeacherClassess as $teacher_classes) {
                                    $registered_assistance++;
                                    $registered_assistance_total++;
                                    $id_attendance_index = $teacher_classes->id_attendance_index;
                                    array_push($indexes, $id_attendance_index);
                                }
                            }
                            for ($a = 0; $a < count($indexes); $a++) {
                                $id_att_index = $indexes[$a];
                                $StudentClass = $attendance->getStudentAttendance($id_att_index, $id_student);
                                foreach ($StudentClass as $std_class) {

                                    if (($std_class->student_base) > 0) {
                                        $student_attendance++;
                                        $student_attendance_total++;
                                    }
                                }
                            }


                        ?>

                            <td style="padding-left: 1px !important; padding-right:1px !important; color:blue; border-right: 1px solid grey; border-left: 3px solid grey;"> <?= $registered_assistance ?></td>
                            <td style="padding-left: 1px !important; padding-right:1px !important; color:green; border-right: 1px solid grey;"><button type="button" onclick="getAssistanceDetails('<?= $id_student ?>','<?= $fecha ?>','<?= $student->student_code ?>','<?= $student->name_student ?>','<?= $id_group ?>')" id="<?= $id_student ?>" class="btn btn-outline-secondary btn_std_attendance" style="font-weight:bold; color: green !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $student_attendance ?> </button></td>
                        <?php
                        }
                        $attendance_dif_total = $registered_assistance_total - $student_attendance_total;
                        if ($student_attendance_total > 0) {
                            $std_percentage = ($student_attendance_total / $registered_assistance_total) * 100;
                        }

                        ?>

                        <td style="font-weight:bold; color: blue !important; padding-left: 1px !important; padding-right:1px !important; color:black; background-color:rgba(105, 105, 105, 0.3); border-right: 1px solid grey; border-left: 3px solid grey;"> <?= $registered_assistance_total ?></td>
                        <td style="font-weight:bold; color: green !important; padding-left: 1px !important; padding-right:1px !important; color:black; background-color:rgba(105, 105, 105, 0.3); border-right: 1px solid grey;"> <?= $student_attendance_total ?></td>
                        <td style="font-weight:bold; color: red !important; padding-left: 1px !important; padding-right:1px !important; color:black; background-color:rgba(105, 105, 105, 0.3); border-right: 1px solid grey;"> <?= $attendance_dif_total ?></td>
                        <td style="font-weight:bold; color: black !important; padding-left: 1px !important; padding-right:1px !important; color:black; background-color:rgba(105, 105, 105, 0.3); border-right: 1px solid grey;"> <?= number_format($std_percentage, 0, '.', '') ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="std_number" value="<?= $std_number; ?>">

    <script>
        var std_number = $('#std_number').val();
        $('#txt_nmb_std').text("Número de alumnos: " + std_number);
        console.log(std_number);
    </script>
<?php endif; ?>