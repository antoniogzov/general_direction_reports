<?php
include 'card_select_academic_performance.php';
$getGroups = array();
if (isset($_GET['id_section']) && isset($_GET['id_campus']) && isset($_GET['id_level_grade'])) {
    $id_section = $_GET['id_section'];
    $id_campus = $_GET['id_campus'];
    $id_level_grade = $_GET['id_level_grade'];
    //$limit = $_GET['limit'];
    $limit = $muestra;

    $getGroups = $academicReports->getGroupsAcademicPerformance($id_section, $id_campus, $id_level_grade);
?>
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

                        <table id="performanceTable" class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="font-weight-bold col-md-2" style="color:white">CÓD. ALUMNO</th>
                                    <th class="font-weight-bold col-md-2" style="color:white">NOMBRE</th>
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
                                    if ($_GET['id_period_calendar'] != 'all') {
                                        foreach ($getPeriodsByIdLevelCombination as $period) {
                                            if ($period->id_period_calendar == $_GET['id_period_calendar']) {
                                                $id_student = $student->id_student;
                                                $id_period_calendar = $period->id_period_calendar;
                                                $final_period_prom = 0;
                                                foreach ($Assignments as $assignment) {
                                                    $id_assignment = $assignment->id_assignment;

                                                    $getPeriodAssigmentQualification = $academicReports->getPEASQUAL($id_student, $id_assignment, $id_period_calendar);
                                                    if (!empty($getPeriodAssigmentQualification)) {
                                                        $final_period_prom = $final_period_prom + $getPeriodAssigmentQualification[0]->grade_period;
                                                    }
                                                }
                                                $final_period_prom = $final_period_prom / count($Assignments);
                                                $final_period_prom = round($final_period_prom, 2);
                                            }
                                        }
                                        if ($final_period_prom >= $min && $final_period_prom <= $max) {
                                            $rank_students[$student->id_student] = $final_period_prom;
                                        }
                                        arsort($rank_students);
                                    } else {

                                        $id_student = $student->id_student;
                                        $periods_prom = array();

                                        $final_prom = 0;
                                        $count_periods = 0;

                                        foreach ($getPeriodsByIdLevelCombination as $period) {
                                            $id_period_calendar = $period->id_period_calendar;
                                            $final_period_prom = 0;
                                            $count_valid_qualifications = 0;
                                            $getPeriodQualification = $academicReports->getAllPQUAL($id_student, $id_period_calendar);
                                            if (!empty($getPeriodQualification)) {
                                                foreach ($getPeriodQualification as $period_qualification) {
                                                    $grape_assgn  = $period_qualification->grade_period;
                                                    if ($grape_assgn != 0 && $grape_assgn != '') {
                                                        $final_period_prom = $final_period_prom + $grape_assgn;
                                                        $count_valid_qualifications++;
                                                    }
                                                }
                                            }
                                            if ($final_period_prom != 0) {
                                                $final_period_prom = $final_period_prom / $count_valid_qualifications;
                                                $final_period_prom = number_format($final_period_prom, 1);
                                                $count_periods++;
                                                $final_prom = $final_prom + $final_period_prom;
                                            }
                                            $periods_prom[$id_period_calendar] = $final_period_prom;
                                        }
                                        //push a arr promedios de cada periodo a un array general
                                        //$periods_student_calif[$id_student] = $periods_prom;
                                        //                                        echo json_encode($periods_prom);
                                        if ($final_prom != 0) {
                                            $final_prom = $final_prom / $count_periods;
                                            $final_prom = number_format($final_prom, 1);
                                        }
                                        /* echo "<h1>PROMEDIO FINAL: " . $final_prom . '</h1>'; */
                                        if ($final_prom >= $min && $final_prom <= $max) {
                                            $rank_students[$student->id_student] = $periods_prom;
                                            $prom_students[$student->id_student] = $final_prom;
                                        }
                                    }
                                }

                                /* echo json_encode($rank_students); */
                                arsort($prom_students);
                                //echo json_encode($prom_students);
                                ?>
                                <?php
                                $i = 0;
                                if ($_GET['id_period_calendar'] != 'all') {
                                    foreach ($rank_students as $key => $value) :
                                        $star_html = '';

                                        $id_student = $key;
                                        $promedio = $value;
                                        $promedio = number_format($promedio, 1);

                                        if ($promedio >= $min && $promedio <= $max) {
                                            if ($promedio == $max) {
                                                $star_html = '<i class="fa-solid fa-star"  style="color:#efb810;"></i>';
                                            } else if ($i == 0) {

                                                $star_html = '<i class="fa-solid fa-star"  style="color:#efb810;"></i>';
                                            }


                                            foreach ($studentList as $student) {
                                                if ($student->id_student == $id_student) {
                                                    $name_student = $student->name_student;
                                                    $code_student = $student->student_code;
                                                }
                                            }
                                ?>
                                            <tr>
                                                <td><?= $code_student; ?></td>
                                                <td><?= $name_student; ?></td>
                                                <td><?= $promedio  . "    " . $star_html; ?></td>
                                            </tr>
                                        <?php

                                        }
                                        $i++;
                                        if ($i == $limit) {
                                            break;
                                        }
                                    endforeach;
                                } else {
                                    // echo json_encode($rank_students);

                                    foreach ($prom_students as $key => $value) :
                                        $student_final_period = $value;
                                        $id_student = $key;
                                        //echo json_encode($value);
                                        $periods_std = $rank_students[$id_student];

                                        foreach ($studentList as $student) {
                                            if ($student->id_student == $key) {
                                                $name_student = $student->name_student;
                                                $code_student = $student->student_code;
                                            }
                                        }
                                        /*  echo '<h1>'.$id_student."-".$name_student.'</h1>';
                                        echo json_encode($periods_std);
                                        echo '<br>'; */

                                        ?>
                                        <tr>
                                            <td><?= $code_student; ?></td>
                                            <td><?= $name_student; ?></td>
                                            <?php
                                            $promedio_final = 0;
                                            $count_periods = 0;
                                            foreach ($periods_std as $key => $promedio) :
                                                $promedio_final = $promedio_final + $promedio;

                                                if ($promedio == 0) {
                                                    $promedio = "-";
                                                } else {
                                                    $count_periods++;
                                                }
                                            ?>
                                                <td><?= $promedio; ?></td>
                                            <?php endforeach;
                                            $star_html = '';

                                            $promedio_final = $promedio_final / $count_periods;
                                            $promedio_final = number_format($promedio_final, 1);
                                            if ($promedio_final == number_format($max, 1)) {
                                                $star_html = '<i class="fa-solid fa-star"  style="color:#efb810;"></i>';
                                            } else if ($i == 0) {

                                                $star_html = '<i class="fa-solid fa-star"  style="color:#efb810;"></i>';
                                            }
                                            ?>
                                            <th><?= number_format($promedio_final, 1) . "    " . $star_html; ?></th>
                                        </tr>
                                    <?php
                                        $i++;
                                        if ($i == $limit) {
                                            break;
                                        }
                                    endforeach; ?>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
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
<script src="js/functions/reports/academic_performance/academic_performance.js"></script>