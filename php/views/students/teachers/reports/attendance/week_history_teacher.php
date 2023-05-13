<?php
include dirname(__DIR__, 1) . '/card_select_week_teacher.php';



$listGroups = array();
if (isset($_GET['id_academic_level'])) {
    $listGroups = $attendance->getListGroupByAcademicLevel($_GET['id_academic_level'], $no_teacher);
}


$days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

if (isset($_GET['week'])) {
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
}

?>

<?php if (!empty($listGroups)) : ?>

    <div class="card">
        <div class="card-body">
            <h2><?= ucfirst($fecha_titulo) ?></h2>
            <div class="table-responsive">
                <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr>
                            <th>GRUPO</th>
                            <?php
                            for ($i = 1; $i < 6; $i++) {
                                $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                $fecha = date("Y-m-d", $dia);
                                $dia_arr = explode('-', $fecha);
                            ?>

                                <th ><?= $days[$i] ?> | <?= $dia_arr[2] ?></th>
                            <?php
                            }
                            ?>
                            <th colspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <th></th>
                            <?php
                            for ($i = 1; $i < 6; $i++) { ?>
                                <th style="padding-left: 1px !important; padding-right:1px !important;">CD</th>
                            <?php
                            }
                            ?>
                            <th style="padding-left: 1px !important; padding-right:1px !important;">CD</th>
                            <th style="padding-left: 1px !important; padding-right:1px !important;">HC</th>
                        </tr>
                    </thead>

                    <tbody class="list">
                        <?php foreach ($listGroups as $group) :
                        $total_clases_dadas = 0;
                            $groupAssignments = $attendance->getAssignmentsByIDGroup($group->id_group, $no_teacher);
                        ?>
                            <tr id="<?= $group->id_group; ?>">
                                <th><?= $group->group_code; ?></th>
                                <?php
                                for ($i = 1; $i < 6; $i++) {
                                    $registered_assistance = 0;
                                    $ids_attendance_index = "";
                                    $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                    $fecha = date("Y-m-d", $dia);

                                    foreach ($groupAssignments as $assignments) {
                                        $id_assignment = $assignments->id_assignment;

                                        $TeacherClassess = $attendance->getAttendanceIndex4($id_assignment, $fecha);
                                        foreach ($TeacherClassess as $teacher_classes) {
                                            $registered_assistance++;
                                            $total_clases_dadas++;

                                            $ids_attendance_index .= $teacher_classes->id_attendance_index . ",";
                                            //$registered_assistance_total++;
                                            //$id_attendance_index = $teacher_classes->id_attendance_index;
                                            //array_push($indexes, $id_attendance_index);
                                        }
                                    }
                                ?>
                                    <th><button type="button" onclick="getPassedAttendanceDetails('<?= $ids_attendance_index ?>', '<?= $no_teacher ?>')" class="btn btn-outline-secondary btn_teacher_attendance" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $registered_assistance ?> </button></th>
                                <?php
                                }
                                ?>
                                <th><?= $total_clases_dadas ?></th>
                                <th></th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<?php endif; ?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/week_attendance_report.js"></script>