<?php foreach ($StudentInfo as $student_info) : ?>
    <h4><?= ucfirst($student_info->student_code) ?> | <?= ucfirst($student_info->name_student) ?></h2>
    <?php endforeach ?>
    <?php
    $AllHebSubjects = $archives->GetHebAllSubjectsStudentReport($id_student);
    $AllSpanSubjects = $archives->GetSpanAllSubjectsStudentReport($id_student);

    $id_group = $StudentGroups[0]->id_group;
    $level_combination = $archives->getLevelCombinationByGroupID($id_group);
    $level_combinationheb = $archives->getLevelCombinationByGroupIDHeb($id_group);
    $id_level_combination = $level_combination[0]->id_level_combination;
    $id_level_combinationheb = $level_combinationheb[0]->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    $periods_heb = $helpers->getAllPeriodsByLevelCombination($id_level_combinationheb);
    ?>
    <hr>
    <h1>Asistencias</h1>
    <h2>Reporte de asistencias</h2>


    <hr>
    <br>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="accordion-1">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 ml-auto">
                                    <div class="accordion my-3" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link w-100 text-primary text-left" type="button" data-toggle="collapse" data-target="#reporteGralAsistencia" aria-expanded="true" aria-controls="reporteGralAsistencia">
                                                        Reporte General
                                                    </button>
                                                </h5>
                                            </div>

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
                                                                                foreach ($AllSpanSubjects as $subject) { ?>
                                                                                    <tr>
                                                                                        <td><?= $subject->name_subject ?></td>
                                                                                        <td><?= $subject->group_code ?></td>
                                                                                        <?php
                                                                                        $total_clases_dadas = 0;
                                                                                        $total_clases_faltantes = 0;
                                                                                        $total_clases_asistidas = 0;
                                                                                        $arr_attend_class_total = "";
                                                                                        $arr_absent_class_total = "";
                                                                                        $total_attendance_percentage = 0;

                                                                                        ?>
                                                                                        <?php foreach ($periods as $period) {
                                                                                            $arr_attend_class = "";
                                                                                            $arr_absent_class = "";
                                                                                            $attendance_period_percentage = 0;
                                                                                            $student_attend_class = 0;
                                                                                            $student_absent_class = 0;
                                                                                            $fecha_min = $period->start_date;
                                                                                            $fecha_max = $period->end_date;

                                                                                            $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($subject->id_assignment, $fecha_min, $fecha_max, $id_student);
                                                                                            //echo $subject->id_assignment . "/";
                                                                                            //echo $fecha_min . "/";
                                                                                            //echo $fecha_max . "/--/";
                                                                                            $clases_dadas = count($AttendanceIndex);
                                                                                            $total_clases_dadas = $total_clases_dadas + $clases_dadas;

                                                                                            if ($clases_dadas > 0) {
                                                                                                foreach ($AttendanceIndex as $att_index) {
                                                                                                    $id_attendance_index = $att_index->id_attendance_index;
                                                                                                    $getStudentAttendance = $archives->getStudentAttendanceArchive($id_attendance_index, $id_student);
                                                                                                    $getStudentAttendanceIncident = $archives->getStudentAttendanceDetail2($id_attendance_index, $id_student);
                                                                                                    if (count($getStudentAttendance) > 0) {
                                                                                                        $student_attend_class++;
                                                                                                        $total_clases_asistidas++;

                                                                                                        $arr_attend_class .= $id_attendance_index . ',';
                                                                                                    } else {
                                                                                                        $total_clases_faltantes++;
                                                                                                        $arr_absent_class .= $id_attendance_index . ',';
                                                                                                        $incident_id = $getStudentAttendanceIncident[0]->incident_id;
                                                                                                        if ($incident_id == 3) {
                                                                                                            $student_justified_class++;
                                                                                                            $student_justified_class_total++;
                                                                                                        }
                                                                                                        $student_absent_class++;
                                                                                                    }
                                                                                                }
                                                                                                $student_absent_class = $clases_dadas - $student_attend_class;
                                                                                                /*  if ($att_type == 1) {
                                                                                                                        $attendance_period_percentage = (($student_attend_class + $student_justified_class) * 100) / $clases_dadas;
                                                                                                                    } else {
                                                                                                                        $attendance_period_percentage = ($student_attend_class * 100) / $clases_dadas;
                                                                                                                    } */
                                                                                                $attendance_period_percentage = ($student_attend_class * 100) / $clases_dadas;
                                                                                                $attendance_period_percentage = number_format($attendance_period_percentage, 0);
                                                                                            }

                                                                                            if ($attendance_period_percentage > 0) {
                                                                                                $attendance_period_percentage = $attendance_period_percentage;
                                                                                                //$attendance_period_percentage = 100 - $attendance_period_percentage;
                                                                                            }

                                                                                        ?>
                                                                                            <td style="<?= $style_results ?> color:#0341fc !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $clases_dadas ?></td>
                                                                                            <td style="<?= $style_results ?>">
                                                                                                <button type="button" data-ids-index="<?= $arr_attend_class ?>" data-id-student="<?= $id_student ?>" class="btn btn-outline-secondary btn_std_attendance" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $student_attend_class ?> </button>
                                                                                            </td>
                                                                                            <td style="<?= $style_results ?>">
                                                                                                <button data-ids-index="<?= $arr_absent_class ?>" data-id-student="<?= $id_student ?>" type="button" class="btn btn-outline-secondary btn_std_absneces" style="color: red !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $student_absent_class ?> </button>
                                                                                            </td>
                                                                                            <td style="<?= $style_results ?>"><?= $attendance_period_percentage ?> %</td>

                                                                                        <?php }
                                                                                        if ($total_clases_dadas > 0) {
                                                                                            $total_attendance_percentage = (($total_clases_asistidas) * 100) / $total_clases_dadas;
                                                                                            $total_attendance_percentage = number_format($total_attendance_percentage, 0);
                                                                                        }
                                                                                        if ($total_attendance_percentage > 0) {
                                                                                            $total_attendance_percentage = $total_attendance_percentage;
                                                                                            //$total_attendance_percentage = 100 - $total_attendance_percentage;
                                                                                        }


                                                                                        ?>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#0341fc !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $total_clases_dadas ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#00ff44 !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_clases_asistidas ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#ff0000 !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_clases_faltantes ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_attendance_percentage ?> %</td>
                                                                                    </tr>
                                                                                <?php } ?>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    Reporte de Asistencia General (Hebreo)
                                                                    <div class="table-responsive">
                                                                        <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th style="padding-left: 1px !important; padding-right:1px !important;">Materia</th>
                                                                                    <th style="padding-left: 1px !important; padding-right:1px !important;">Grupo</th>
                                                                                    <?php $style_head_period = "padding-left: 1px !important; padding-right:1px !important;"; ?>
                                                                                    <?php foreach ($periods_heb as $period) : ?>
                                                                                        <th colspan="4" style="<?= $style_head_period ?>">Periodo <?= $period->no_period ?></th>
                                                                                    <?php endforeach; ?>
                                                                                    <th colspan="4" style="<?= $style_head_period ?>">Total</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th style="padding-left: 1px !important; padding-right:1px !important;"></th>
                                                                                    <th></th>
                                                                                    <?php $style_heads = "padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"; ?>
                                                                                    <?php foreach ($periods_heb as $period) : ?>
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
                                                                                <?php foreach ($AllHebSubjects as $subject) { ?>
                                                                                    <tr>
                                                                                        <td><?= $subject->name_subject ?></td>
                                                                                        <td><?= $subject->group_code ?></td>
                                                                                        <?php
                                                                                        $total_clases_dadas = 0;
                                                                                        $total_clases_faltantes = 0;
                                                                                        $total_clases_asistidas = 0;
                                                                                        $arr_attend_class_total = "";
                                                                                        $arr_absent_class_total = "";
                                                                                        $total_attendance_percentage = 0;

                                                                                        ?>
                                                                                        <?php foreach ($periods_heb as $period) {
                                                                                            $arr_attend_class = "";
                                                                                            $arr_absent_class = "";
                                                                                            $attendance_period_percentage = 0;
                                                                                            $student_attend_class = 0;
                                                                                            $student_absent_class = 0;
                                                                                            $fecha_min = $period->start_date;
                                                                                            $fecha_max = $period->end_date;

                                                                                            $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($subject->id_assignment, $fecha_min, $fecha_max, $id_student);
                                                                                            //echo $subject->id_assignment . "/";
                                                                                            //echo $fecha_min . "/";
                                                                                            //echo $fecha_max . "/--/";
                                                                                            $clases_dadas = count($AttendanceIndex);
                                                                                            $total_clases_dadas = $total_clases_dadas + $clases_dadas;

                                                                                            if ($clases_dadas > 0) {
                                                                                                foreach ($AttendanceIndex as $att_index) {
                                                                                                    $id_attendance_index = $att_index->id_attendance_index;
                                                                                                    $getStudentAttendance = $archives->getStudentAttendanceArchive($id_attendance_index, $id_student);
                                                                                                    $getStudentAttendanceIncident = $archives->getStudentAttendanceDetail2($id_attendance_index, $id_student);
                                                                                                    if (count($getStudentAttendance) > 0) {
                                                                                                        $student_attend_class++;
                                                                                                        $total_clases_asistidas++;

                                                                                                        $arr_attend_class .= $id_attendance_index . ',';
                                                                                                    } else {
                                                                                                        $total_clases_faltantes++;
                                                                                                        $arr_absent_class .= $id_attendance_index . ',';
                                                                                                        $incident_id = $getStudentAttendanceIncident[0]->incident_id;
                                                                                                        if ($incident_id == 3) {
                                                                                                            $student_justified_class++;
                                                                                                            $student_justified_class_total++;
                                                                                                        }
                                                                                                        $student_absent_class++;
                                                                                                    }
                                                                                                }
                                                                                                $student_absent_class = $clases_dadas - $student_attend_class;
                                                                                                /*  if ($att_type == 1) {
                                                                                                                        $attendance_period_percentage = (($student_attend_class + $student_justified_class) * 100) / $clases_dadas;
                                                                                                                    } else {
                                                                                                                        $attendance_period_percentage = ($student_attend_class * 100) / $clases_dadas;
                                                                                                                    } */
                                                                                                $attendance_period_percentage = ($student_attend_class * 100) / $clases_dadas;
                                                                                                $attendance_period_percentage = number_format($attendance_period_percentage, 0);
                                                                                            }

                                                                                            if ($attendance_period_percentage > 0) {
                                                                                                $attendance_period_percentage = $attendance_period_percentage;
                                                                                                //$attendance_period_percentage = 100 - $attendance_period_percentage;
                                                                                            }

                                                                                        ?> <td style="<?= $style_results ?> color:#0341fc !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $clases_dadas ?></td>
                                                                                            <td style="<?= $style_results ?>">
                                                                                                <button type="button" data-ids-index="<?= $arr_attend_class ?>" data-id-student="<?= $id_student ?>" class="btn btn-outline-secondary btn_std_attendance" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $student_attend_class ?> </button>
                                                                                            </td>
                                                                                            <td style="<?= $style_results ?>">
                                                                                                <button data-ids-index="<?= $arr_absent_class ?>" data-id-student="<?= $id_student ?>" type="button" class="btn btn-outline-secondary btn_std_absneces" style="color: red !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $student_absent_class ?> </button>
                                                                                            </td>
                                                                                            <td style="<?= $style_results ?>"><?= $attendance_period_percentage ?> %</td>

                                                                                        <?php }
                                                                                        if ($total_clases_dadas > 0) {
                                                                                            $total_attendance_percentage = (($total_clases_asistidas) * 100) / $total_clases_dadas;
                                                                                            $total_attendance_percentage = number_format($total_attendance_percentage, 0);
                                                                                        }
                                                                                        if ($total_attendance_percentage > 0) {
                                                                                            $total_attendance_percentage = $total_attendance_percentage;
                                                                                            //$total_attendance_percentage = 100 - $total_attendance_percentage;
                                                                                        }


                                                                                        ?>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#0341fc !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $total_clases_dadas ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#00ff44 !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_clases_asistidas ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; color:#ff0000 !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_clases_faltantes ?></td>
                                                                                        <td style="padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $total_attendance_percentage ?> %</td>
                                                                                    </tr>
                                                                                <?php } ?>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>