<?php

$structure = array();
$groups = array();
if (isset($_GET['id_academic_level']) && isset($_GET['id_group'])) {
    $groups = $attendance->getAllMyGroupsByAcademicLevelAndAcademicArea($_GET['id_academic_level'], $_GET['id_group'], $no_teacher);
    $getAcademicAreasCoordinator = $attendance->getAcademicAreasCoordinator($no_teacher);
    $parms_academic_area = "";
    if (count($getAcademicAreasCoordinator) > 1) {
        $parms_academic_area = "AND (sbj.id_academic_area = 1 OR sbj.id_academic_area = 2)";
    }else{
        $id_academic_area = $getAcademicAreasCoordinator[0]->id_academic_area;
        $parms_academic_area = "AND (sbj.id_academic_area = $id_academic_area)";
    }
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

<?php if (!empty($groups)) : ?>
    <?php foreach ($groups as $group) :
        $group_array = array();
        $students = array();
        $group_array['group_info'] = $group;

        $getStudentsByGroup = $attendance->getStudentsByGroup($group->id_group);
        $total_clases_dadas = 0;
    ?>
        <?php
        for ($i = 1; $i < 6; $i++) {
            $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
            $fecha = date("Y-m-d", $dia);
            $dia_arr = explode('-', $fecha);
        ?>
        <?php
        }
        ?>
        <?php foreach ($getStudentsByGroup as $student) :

            $getRowspanWASR = $attendance->getRowspanWASR($student->id_student, $init_date, $final_date, $parms_academic_area);


            $valid = 0;
            if (!empty($getRowspanWASR)) {
                $rowspan = ($getRowspanWASR[0]->rowspan);
            } else {
                $rowspan = 1;
            }

        ?>

            <?php
            $std_days = array();
            for ($i = 1; $i < 6; $i++) : ?>
                <?php
                $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                $fecha = date("Y-m-d", $dia);
                $dia_arr = explode('-', $fecha);
                $getAttendanceIndexStudentReport = $attendance->getAttendanceIndexStudentReport($student->id_student, $fecha, $parms_academic_area);

                $str_td = '';
                $td_items = array();
                ?>
                <?php if (!empty($getAttendanceIndexStudentReport)) : ?>
                    <?php
                    foreach ($getAttendanceIndexStudentReport as $stud_index) :
                        $td_items[] = '-tds class="PasslistDetail" data-id-index="' . $stud_index->id_attendance_index . '" title="' . $stud_index->name_subject . ' | ' . $stud_index->teacher_name . ' | B. ' . $stud_index->class_block . ' | ' . $stud_index->incident_absence . '" -s' . $stud_index->short_name . '+td+' . '--td style="background-color:' . $stud_index->std_attend_color . ' !important;" class="attendanceStdDetail" data-id-attendance-record="' . $stud_index->id_attendance_record . '"  -t  ' . $stud_index->std_attend_text . '+td+';

                    ?>
                    <?php
                    endforeach; ?>
                <?php else : ?>
                    <?php
                    $td_items[] = '-tds - -s --td - +td+';
                    ?>
                <?php endif; ?>

                <?php
                $days_results[$i] = array(
                    'tr_html' => $td_items

                );
                ?>
            <?php endfor; ?>
            <?php
            $std_days[] = $days_results;

            $stud_attendance = array(
                'rowspan' => $rowspan,
                'std_days' => $std_days
            );

            ?>
        <?php
            $student_content = array(
                'student_info' => $student,
                'student_attend' => $stud_attendance
            );
            $students[] = $student_content;
        endforeach; ?>

        <?php

        $data = array(
            'group_info' => $group,
            'students'                => $students
        );

        $structure[] = $data; ?>
    <?php endforeach; ?>
    
<?php endif; ?>