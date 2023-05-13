<?php
set_time_limit(0);

include 'card_select_attendance_ranking.php';
?>
<?php
$getGroups = array();
if (isset($_GET['id_section']) && isset($_GET['id_campus']) && isset($_GET['id_level_grade'])) {
    $id_section = $_GET['id_section'];
    $id_campus = $_GET['id_campus'];
    $id_level_grade = $_GET['id_level_grade'];
    //$limit = $_GET['limit'];

    $getGroups = $academicReports->getGroupsAcademicPerformance($id_section, $id_campus, $id_level_grade);
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script>
        Swal.fire({
            text: "Cargando...",
            html: '<img src="images/loading_iteach.gif" width="300" height="300">',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
        });
    </script>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="div_tabla">
                <?php if (!empty($getGroups)) : ?>
                    <?php foreach ($getGroups as $group) : ?>
                        <?php
                        $id_group = $group->id_group;
                        $studentList = $attendance->getListStudentByGroup($id_group);
                        $Assignments = $academicReports->getAssignmentByGroupCoordinator($id_group);
                        ?>
                        <h1><?= $group->group_code ?></h1>

                        <table id="table<?= $group->id_group ?>" class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="font-weight-bold col-md-2" style="color:white">CÓD. ALUMNO</th>
                                    <th class="font-weight-bold col-md-2" style="color:white">NOMBRE</th>
                                    <th class="font-weight-bold col-md-2" style="color:white">ASIGNATURA</th>
                                    <?php if ($_GET['id_period_calendar'] == 'all') : ?>
                                        <?php foreach ($getPeriodsByIdLevelCombination as $period) : ?>
                                            <th class="font-weight-bold col-md-1" style="color:white">P. <?= $period->no_period ?></th>
                                        <?php endforeach; ?>
                                        <th class="font-weight-bold col-md-1" style="color:white">FINAL</th>
                                    <?php else : ?>
                                        <?php foreach ($getPeriodsByIdLevelCombination as $period) : ?>
                                            <?php if ($period->id_period_calendar == $_GET['id_period_calendar']) : ?>
                                                <th class="font-weight-bold col-md-2" style="color:white">P. <?= $period->no_period ?></th>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!-- <th>RANK</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rank_students = array();
                                $prom_students = array();
                                foreach ($studentList as $student) {
                                    $periods_student_calif = array();

                                    $period_absences = 0;
                                    $period_attends = 0;
                                    $period_percentage = 0;
                                    $clases_student = 0;

                                    $id_student = $student->id_student;

                                    if ($_GET['id_period_calendar'] != 'all') {
                                        foreach ($Assignments as $assignment) {
                                            $id_assignment = $assignment->id_assignment;
                                            $name_subject = $assignment->name_subject;

                                            foreach ($getPeriodsByIdLevelCombination as $period) {
                                                if ($period->id_period_calendar == $_GET['id_period_calendar']) {
                                                    $id_period_calendar = $period->id_period_calendar;

                                                    $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);
                                                    $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);
                                                    $getStudentAttendsArchive = $archives->getStudentAttendsArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);

                                                    $clases_student  = $clases_student + count($AttendanceIndex);
                                                    $period_attends  = $period_attends + count($getStudentAttendsArchive);
                                                    $period_absences_if  = count($getStudentAbsencesArchive);
                                                    if ($period_absences_if > 0) {
                                                        foreach ($getStudentAbsencesArchive as $std_absences) {
                                                            if ($std_absences->double_absence == 1) {
                                                                $period_absences++;
                                                            }
                                                            $period_absences++;
                                                        }
                                                    }

                                                    if (($clases_student) > 0) {
                                                        $period_percentage = (number_format((((($clases_student) - $period_absences) / ($clases_student) * 100)), 0));
                                                        $str_period_percentage = $period_percentage . "%";

                                                        if ($period_percentage >= $min && $period_percentage <= $max) {
                                                            $rank_students[$student->id_student][] =
                                                                array(
                                                                    "subject" => $name_subject,
                                                                    "period_percentage" => $period_percentage
                                                                );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {

                                        $id_student = $student->id_student;
                                        $periods_prom = array();

                                        foreach ($Assignments as $assignment) {


                                            $id_assignment = $assignment->id_assignment;
                                            $name_subject = $assignment->name_subject;


                                            $total_absences = 0;
                                            $total_attends = 0;
                                            $total_percentage = 0;
                                            $total_clases_student = 0;
                                            $apply_add_array = 0;
                                            $total_percentage = 0;

                                            $id_student = $student->id_student;

                                            $final_prom = 0;
                                            $count_periods = 0;
                                            $sum_period_percentage = 0;

                                            foreach ($getPeriodsByIdLevelCombination as $period) {

                                                $period_absences = 0;
                                                $period_attends = 0;
                                                $period_percentage = 0;
                                                $clases_student = 0;


                                                $id_period_calendar = $period->id_period_calendar;

                                                $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);
                                                $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);
                                                $getStudentAttendsArchive = $archives->getStudentAttendsArchive($assignment->id_assignment, $period->start_date, $period->end_date, $student->id_student);

                                                $clases_student  = $clases_student + count($AttendanceIndex);


                                                $period_attends  = $period_attends + count($getStudentAttendsArchive);

                                                $period_absences_if  = count($getStudentAbsencesArchive);
                                                if ($period_absences_if > 0) {
                                                    foreach ($getStudentAbsencesArchive as $std_absences) {
                                                        if ($std_absences->double_absence == 1) {
                                                            $period_absences++;
                                                            $total_absences++;
                                                        }
                                                        $period_absences++;
                                                        $total_absences++;
                                                    }
                                                }

                                                if (($clases_student) > 0) {

                                                    $total_clases_student = $total_clases_student + count($AttendanceIndex);
                                                    $total_attends = $total_attends + count($getStudentAttendsArchive);
                                                    $period_percentage = (number_format((((($clases_student) - $period_absences) / ($clases_student) * 100)), 0));
                                                    if ($period_percentage >= $min && $period_percentage <= $max) {
                                                        $sum_period_percentage = $sum_period_percentage + $period_percentage;
                                                        $periods_prom[$id_period_calendar] = $period_percentage;
                                                    } else {
                                                        $periods_prom[$id_period_calendar] = 0;
                                                    }
                                                } else {
                                                    $periods_prom[$id_period_calendar] = 0;
                                                }
                                            }


                                            if (($total_clases_student) > 0) {
                                                $total_percentage = (number_format((((($total_clases_student) - $total_absences) / ($total_clases_student) * 100)), 0));

                                                if ($total_percentage >= $min && $total_percentage <= $max) {
                                                    if ($total_percentage > 0) {
                                                        if ($sum_period_percentage > 0) {

                                                            $rank_students[$student->id_student][] =
                                                                array(
                                                                    "subject" => $name_subject,
                                                                    "periods_prom" => $periods_prom,
                                                                    "total_percentage" => $total_percentage
                                                                );

                                                            $prom_students[$student->id_student] = $total_percentage;
                                                        }
                                                    }
                                                }
                                            } else {
                                                $total_percentage = 0;
                                            }
                                        }
                                    }
                                }

                                arsort($prom_students);
                                ?>
                                <?php
                                $i = 0;
                                if ($_GET['id_period_calendar'] != 'all') {
                                    foreach ($rank_students as $key => $value) :
                                        $star_html = '';

                                        $id_student = $key;
                                        foreach ($studentList as $student) {
                                            if ($student->id_student == $id_student) {
                                                $name_student = $student->name_student;
                                                $code_student = $student->student_code;
                                            }
                                        }
                                        foreach ($value as $percentages) {

                                            $name_subject = $percentages['subject'];
                                            $promedio = $percentages['period_percentage'];
                                            $promedio = number_format($promedio, 1);

                                            if ($promedio >= $min && $promedio <= $max) {
                                                if ($promedio == $max) {
                                                    $star_html = '<i class="fas fa-arrow-alt-circle-up"  style="color:#324ea8;"></i>';
                                                } else if ($i == 0) {

                                                    $star_html = '<i class="fas fa-arrow-alt-circle-up"  style="color:#324ea8;"></i>';
                                                }



                                ?>
                                                <tr>
                                                    <td><?= $code_student; ?></td>
                                                    <td><?= $name_student; ?></td>
                                                    <td><?= $name_subject; ?></td>
                                                    <td><?= $promedio  . "%    " ?></td>
                                                </tr>
                                        <?php

                                            }
                                        }

                                        ?>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <?php
                                    endforeach;
                                } else {
                                    /* echo json_encode($rank_students); */
                                    foreach ($rank_students as $key => $value) :
                                        $student_final_period = $value;
                                        $id_student = $key;

                                        $periods_std = $rank_students[$id_student];

                                        foreach ($studentList as $student) {
                                            if ($student->id_student == $key) {
                                                $name_student = $student->name_student;
                                                $code_student = $student->student_code;
                                            }
                                        }

                                        foreach ($value as $percentages) {

                                            $name_subject = $percentages['subject'];

                                        ?>
                                            <tr>
                                                <td><?= $code_student; ?></td>
                                                <td><?= $name_student; ?></td>
                                                <td><?= $name_subject; ?></td>
                                                <?php
                                                $promedio_final = 0;
                                                $count_periods = 0;
                                                foreach ($percentages['periods_prom'] as $key => $promedio) :
                                                    $promedio_final = $promedio_final + $promedio;

                                                    if ($promedio == 0) {
                                                        $promedio = "-";
                                                    } else {
                                                        $count_periods++;
                                                        $promedio = $promedio . "%";
                                                    }
                                                ?>
                                                    <td><?= $promedio; ?></td>
                                                <?php endforeach;
                                                $star_html = '';
                                                if ($count_periods > 0) {
                                                    $promedio_final = $promedio_final / $count_periods;
                                                    $promedio_final = number_format($promedio_final, 1);
                                                } else {
                                                    $promedio_final = 0;
                                                }
                                                if ($promedio_final == number_format($max, 1)) {
                                                    $star_html = '<i class="fas fa-arrow-alt-circle-up"  style="color:#324ea8;"></i>';
                                                } else if ($i == 0) {

                                                    $star_html = '<i class="fas fa-arrow-alt-circle-up"  style="color:#324ea8;"></i>';
                                                }
                                                ?>
                                                <th><?= number_format($promedio_final, 1) . "%    "  ?></th>
                                            </tr>
                                        <?php
                                        }

                                        /*  echo '<h1>'.$id_student."-".$name_student.'</h1>';
                                        echo json_encode($periods_std);
                                        echo '<br>'; */

                                        ?>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <?php foreach ($getPeriodsByIdLevelCombination as $period) : ?>
                                                <th></th>
                                            <?php endforeach; ?>
                                            <th></th>
                                        </tr>
                                    <?php
                                        $i++;
                                    endforeach; ?>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>

                        <script>
                            $(document).ready(function() {
                                $('#table<?= $group->id_group ?>').DataTable({
                                    dom: 'Bfrtip',
                                    "ordering": false,
                                    "paging": false,
                                    buttons: [{
                                            extend: 'excelHtml5',
                                            filename: 'Ranking de asistencia | <?= $group->group_code ?>'
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            filename: 'Ranking de asistencia | <?= $group->group_code ?>',
                                            title: 'Ranking de asistencia | <?= $group->group_code ?>',
                                            orientation: 'landscape'
                                        }
                                    ]
                                });
                            });
                        </script>
                    <?php endforeach; ?>
                    <!-- <h1>No hay grupos</h1> -->
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        Swal.close();
    </script>
<?php
}
?>
<script src="js/functions/reports/attendance_ranking/attendance_ranking.js"></script>