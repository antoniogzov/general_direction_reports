<!-- Modal -->
<?php
$id_student = $_GET["student"];
$AllHebSubjects = $archives->GetHebAllSubjectsStudentReport($id_student);
$AllSpanSubjects = $archives->GetSpanAllSubjectsStudentReport($id_student);

$id_group = $StudentGroups[0]->id_group;
$level_combination = $archives->getLevelCombinationByGroupID($id_group);
$level_combinationheb = $archives->getLevelCombinationByGroupIDHeb($id_group);
$id_level_combination = $level_combination[0]->id_level_combination;
$id_level_combinationheb = $level_combinationheb[0]->id_level_combination;
$periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
$periods_heb = $helpers->getAllPeriodsByLevelCombination($id_level_combinationheb);

$t_student_indexs_esp = 0;
$t_student_attends_esp = 0;
$t_student_absences_esp = 0;

$t_student_indexs_heb = 0;
$t_student_attends_heb = 0;
$t_student_absences_heb = 0;

$total_std_percentage = "S/D";
?>
<div class="modal fade" id="infoAsistencia" tabindex="-1" role="dialog" aria-labelledby="infoAsistencia" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoAsistencia">Asistencia de: <?= mb_strtoupper($listStudent->name_student) ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reporteGralAsistencia" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body opacity-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        Reporte de Asistencia General (Español)
                                        <div class="table-responsive">
                                            <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="padding-left: 1px !important; padding-right:1px !important;">Materia</th>
                                                        <th style="padding-left: 1px !important; padding-right:1px !important;">Grupo</th>

                                                        <?php $style_head_period = "padding-left: 1px !important; padding-right:1px !important;"; ?>
                                                        <?php foreach ($periods as $period) : ?>
                                                            <th colspan="4" style="<?= $style_head_period ?>">Periodo <?= $period->no_period ?></th>
                                                        <?php endforeach; ?>
                                                        <th colspan="4" style="<?= $style_head_period ?>">Total</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <?php $style_heads = "padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"; ?>
                                                        <?php foreach ($periods as $period) : ?>
                                                            <th style="<?= $style_heads ?> border-left: 3px solid rgba(194, 194, 194) !important;">C.D.</th>
                                                            <th style="<?= $style_heads ?>">C.A.</th>
                                                            <th style="<?= $style_heads ?>">C.F.</th>
                                                            <th style="<?= $style_heads ?>">P.A.</th>
                                                        <?php endforeach; ?>
                                                        <th style="<?= $style_heads ?> border-left: 3px solid rgba(194, 194, 194) !important;">C.D.</th>
                                                        <th style="<?= $style_heads ?>">C.A.</th>
                                                        <th style="<?= $style_heads ?>">C.F.</th>
                                                        <th style="<?= $style_heads ?>">P.A.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $style_results = "padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;";
                                                    foreach ($AllSpanSubjects as $subject) :
                                                        $assignment_indexs = 0;
                                                        $assignment_absences = 0;
                                                        $assignment_attends = 0;
                                                        $assignment_percentage = 0;
                                                    ?>
                                                        <tr>
                                                            <th style="padding-left: 1px !important; padding-right:1px !important;"><?= $subject->name_subject ?></th>
                                                            <th style="padding-left: 1px !important; padding-right:1px !important;"><?= $subject->group_code ?></th>

                                                            <?php foreach ($periods as $period) : ?>

                                                                <?php
                                                                $period_absences = 0;
                                                                $period_attends = 0;
                                                                $period_percentage = 0;

                                                                $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);
                                                                $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);
                                                                $getStudentAttendsArchive = $archives->getStudentAttendsArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);

                                                                $clases_student  = count($AttendanceIndex);
                                                                if ($clases_student > 0) {
                                                                    $str_period_class = $clases_student;
                                                                    $assignment_indexs = $assignment_indexs + $clases_student;
                                                                } else {
                                                                    $str_period_class = "-";
                                                                }

                                                                $period_attends  = count($getStudentAttendsArchive);
                                                                if ($period_attends > 0) {
                                                                    $str_period_attends = $period_attends;
                                                                    $assignment_attends = $assignment_attends + $period_attends;
                                                                } else {
                                                                    $str_period_attends = "-";
                                                                }


                                                                $period_absences_if  = count($getStudentAbsencesArchive);
                                                                if ($period_absences_if > 0) {
                                                                    foreach ($getStudentAbsencesArchive as $std_absences) {
                                                                        if ($std_absences->double_absence == 1) {
                                                                            $period_absences++;
                                                                            $assignment_absences++;
                                                                        }
                                                                        $period_absences++;
                                                                        $assignment_absences++;
                                                                    }
                                                                    $str_period_absences = $period_absences;
                                                                } else if (count($AttendanceIndex) > 0) {
                                                                    $str_period_absences = "0";
                                                                } else {
                                                                    $str_period_absences = "-";
                                                                }


                                                                if (count($AttendanceIndex) > 0) {
                                                                    $period_percentage = (number_format((((count($AttendanceIndex) - $period_absences) / count($AttendanceIndex) * 100)), 0));
                                                                    $str_period_percentage = $period_percentage . "%";
                                                                } else {
                                                                    $str_period_percentage = "-";
                                                                }
                                                                ?>
                                                                <td style="<?= $style_results ?> color:#0341fc !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_period_class ?></td>
                                                                <td style="<?= $style_results ?> color:rgb(28, 212, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;">
                                                                    <button type="button" onclick="getAssistanceDetailsReportsStudent('<?= $period->start_date ?>', '<?= $period->end_date ?>', '<?= $subject->id_assignment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;">
                                                                        <?= $str_period_attends ?>
                                                                    </button>
                                                                </td>
                                                                <td style="<?= $style_results ?> color:rgb(194, 23, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;">
                                                                    <button type="button" onclick="getAttendanceReportDetailsAbsences('<?= $period->start_date ?>', '<?= $period->end_date ?>', '<?= $subject->id_assignment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary" style="color: #ff0011 !important; padding-left: 1px !important; padding-right:1px !important;">
                                                                        <?= $str_period_absences ?>
                                                                    </button>
                                                                </td>
                                                                <td style="<?= $style_results ?> color:rgb(0, 0, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_period_percentage ?></td>
                                                            <?php endforeach; ?>
                                                            <?php

                                                            if (($assignment_indexs) > 0) {
                                                                $assignment_percentage = (number_format((((($assignment_indexs) - $assignment_absences) / ($assignment_indexs) * 100)), 0));
                                                                $str_assignment_percentage = $assignment_percentage . "%";
                                                            } else {
                                                                $str_assignment_percentage = "-";
                                                            }
                                                            ?>

                                                            <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $assignment_indexs ?></td>
                                                            <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $assignment_attends ?></td>
                                                            <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $assignment_absences ?></td>
                                                            <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_assignment_percentage ?></td>

                                                            <?php
                                                            $t_student_indexs_esp  = $t_student_indexs_esp + $assignment_indexs;
                                                            $t_student_attends_esp = $t_student_attends_esp + $assignment_attends;
                                                            $t_student_absences_esp = $t_student_absences_esp + $assignment_absences;
                                                            ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <!-- <h1 id="total_attendance_percentage_esp_string"><?= $total_attendance_percentage_esp_string ?></h1> -->
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <br><br>
                                        Reporte de Asistencia General (Hebreo)
                                        <div class="table-responsive">
                                            <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="padding-left: 1px !important; padding-right:1px !important;">Materia</th>
                                                        <th style="padding-left: 1px !important; padding-right:1px !important;">Grupo</th>

                                                        <?php $style_head_period = "padding-left: 1px !important; padding-right:1px !important;"; ?>
                                                        <?php foreach ($periods as $period) : ?>
                                                            <th colspan="4" style="<?= $style_head_period ?>">Periodo <?= $period->no_period ?></th>
                                                        <?php endforeach; ?>
                                                        <th colspan="4" style="<?= $style_head_period ?>">Total</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <?php $style_heads = "padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"; ?>
                                                        <?php foreach ($periods as $period) : ?>
                                                            <th style="<?= $style_heads ?> border-left: 3px solid rgba(194, 194, 194) !important;">C.D.</th>
                                                            <th style="<?= $style_heads ?>">C.A.</th>
                                                            <th style="<?= $style_heads ?>">C.F.</th>
                                                            <th style="<?= $style_heads ?>">P.A.</th>
                                                        <?php endforeach; ?>
                                                        <th style="<?= $style_heads ?> border-left: 3px solid rgba(194, 194, 194) !important;">C.D.</th>
                                                        <th style="<?= $style_heads ?>">C.A.</th>
                                                        <th style="<?= $style_heads ?>">C.F.</th>
                                                        <th style="<?= $style_heads ?>">P.A.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($AllHebSubjects)) : ?>
                                                        <?php
                                                        $style_results = "padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;";
                                                        foreach ($AllHebSubjects as $subject) :
                                                            $assignment_indexs = 0;
                                                            $assignment_absences = 0;
                                                            $assignment_attends = 0;
                                                            $assignment_percentage = 0;
                                                        ?>
                                                            <tr>
                                                                <th style="padding-left: 1px !important; padding-right:1px !important;"><?= $subject->name_subject ?></th>
                                                                <th style="padding-left: 1px !important; padding-right:1px !important;"><?= $subject->group_code ?></th>

                                                                <?php foreach ($periods as $period) : ?>

                                                                    <?php
                                                                    $period_absences = 0;
                                                                    $period_attends = 0;
                                                                    $period_percentage = 0;

                                                                    $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);
                                                                    $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);
                                                                    $getStudentAttendsArchive = $archives->getStudentAttendsArchive($subject->id_assignment, $period->start_date, $period->end_date, $id_student);

                                                                    $clases_student  = count($AttendanceIndex);
                                                                    if ($clases_student > 0) {
                                                                        $str_period_class = $clases_student;
                                                                        $assignment_indexs = $assignment_indexs + $clases_student;
                                                                    } else {
                                                                        $str_period_class = "-";
                                                                    }

                                                                    $period_attends  = count($getStudentAttendsArchive);
                                                                    if ($period_attends > 0) {
                                                                        $str_period_attends = $period_attends;
                                                                        $assignment_attends = $assignment_attends + $period_attends;
                                                                    } else {
                                                                        $str_period_attends = "-";
                                                                    }


                                                                    $period_absences_if  = count($getStudentAbsencesArchive);
                                                                    if ($period_absences_if > 0) {
                                                                        foreach ($getStudentAbsencesArchive as $std_absences) {
                                                                            if ($std_absences->double_absence == 1) {
                                                                                $period_absences++;
                                                                                $assignment_absences++;
                                                                            }
                                                                            $period_absences++;
                                                                            $assignment_absences++;
                                                                        }
                                                                        $str_period_absences = $period_absences;
                                                                    } else if (count($AttendanceIndex) > 0) {
                                                                        $str_period_absences = "0";
                                                                    } else {
                                                                        $str_period_absences = "-";
                                                                    }


                                                                    if (count($AttendanceIndex) > 0) {
                                                                        $period_percentage = (number_format((((count($AttendanceIndex) - $period_absences) / count($AttendanceIndex) * 100)), 0));
                                                                        $str_period_percentage = $period_percentage . "%";
                                                                    } else {
                                                                        $str_period_percentage = "-";
                                                                    }
                                                                    ?>
                                                                    <td style="<?= $style_results ?> color:#0341fc !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_period_class ?></td>
                                                                    <td style="<?= $style_results ?> color:rgb(28, 212, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;">
                                                                        <button type="button" onclick="getAssistanceDetailsReportsStudent('<?= $period->start_date ?>', '<?= $period->end_date ?>', '<?= $subject->id_assingment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary btn_std_attendance" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;">
                                                                            <?= $str_period_attends ?>
                                                                        </button>
                                                                    </td>
                                                                    <td style="<?= $style_results ?> color:rgb(194, 23, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_period_absences ?></td>
                                                                    <td style="<?= $style_results ?> color:rgb(0, 0, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_period_percentage ?></td>
                                                                <?php endforeach; ?>
                                                                <?php

                                                                if (($assignment_indexs) > 0) {
                                                                    $assignment_percentage = (number_format((((($assignment_indexs) - $assignment_absences) / ($assignment_indexs) * 100)), 0));
                                                                    $str_assignment_percentage = $assignment_percentage . "%";
                                                                } else {
                                                                    $str_assignment_percentage = "-";
                                                                }
                                                                ?>

                                                                <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $assignment_indexs ?></td>
                                                                <td style="<?= $style_results ?> color:rgb(28, 212, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;">
                                                                    <button type="button" onclick="getAssistanceDetailsReportsStudent('<?= $period->start_date ?>', '<?= $period->end_date ?>', '<?= $subject->id_assignment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;">
                                                                        <?= $str_period_attends ?>
                                                                    </button>
                                                                </td>
                                                                <td style="<?= $style_results ?> color:rgb(194, 23, 0) !important; border-left: 3px solid rgba(194, 194, 194) !important;">
                                                                    <button type="button" onclick="getAttendanceReportDetailsAbsences('<?= $period->start_date ?>', '<?= $period->end_date ?>', '<?= $subject->id_assignment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary" style="color: #ff0011 !important; padding-left: 1px !important; padding-right:1px !important;">
                                                                        <?= $str_period_absences ?>
                                                                    </button>
                                                                </td>
                                                                <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_assignment_percentage ?></td>
                                                            </tr>
                                                        <?php endforeach;

                                                        $t_student_indexs_heb = $t_student_indexs_heb + $assignment_indexs;
                                                        $t_student_attends_heb = $t_student_attends_heb + $assignment_attends;
                                                        $t_student_absences_heb = $t_student_absences_heb + $assignment_absences;
                                                        ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="100">NO SE ENCONTRARON REGISTROS</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <!-- <h1 id="total_attendance_percentage_esp_string"><?= $total_attendance_percentage_esp_string ?></h1> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $tais = $t_student_indexs_heb + $t_student_indexs_esp;
            $tatts = $t_student_attends_heb + $t_student_attends_esp;
            $tabs = $t_student_absences_heb + $t_student_absences_esp;

            if (($tais) > 0) {
                $total_std_percentage = (number_format((((($tais) - $tabs) / ($tais) * 100)), 0));
                $str_total_std_percentage = $total_std_percentage . "%";
            } else {
                $str_total_std_percentage = "-";
            }

            ?>
            <p id="general_student_attendance_percentage" style="display: none;"><?= $total_std_percentage ?></p>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>