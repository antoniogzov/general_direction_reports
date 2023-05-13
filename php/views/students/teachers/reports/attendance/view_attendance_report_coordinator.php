<script src="../general/js/vendor/tablefilter/tablefilter/tablefilter.js" async></script>

<div class="row">
    <div class="col">
        <h3 id="txt_grupo"></h3>
        <h4 id="txt_nmb_std">Número de alumnos: 0</h4>
    </div>
    <div class="col">
        <p align="right" style="padding-bottom:1px !important; font-size:12px; color:green !important;" class="font-weight-bold">CA: Clases Asistidas</p>
        <p align="right" style="padding-bottom:1px !important; font-size:12px; color:red !important;" class="font-weight-bold">CF: Clases Faltantes</p>
        <p align="right" style="padding-bottom:1px !important; font-size:12px;" class="font-weight-bold">PI: Porcentaje de Inasistencia</p>
    </div>
    <div class="w-100"></div>
    <div class="col"></div>
    <div class="col"></div>
</div>
<table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
    <thead class="thead-light">
        <tr class="table-active">
            <th scope="col" class="font-weight-bold" style="padding-left: 1px !important; padding-right:1px !important; max-width:60px !important;">Cód. alumno</th>
            <th scope="col" class="font-weight-bold" style="padding-left: 1px !important; padding-right:1px !important; max-width:20px !important;">Nombre</th>
            <?php
            $periodos = array();
            $std_periods = array();
            $suma_periodos = array();

            foreach ($periods as $period) :
                $data_list = array();
                $clases_dadas = 0;
                $clases_std = 0;
                $fecha_min = $period->start_date;
                $fecha_max = $period->end_date;
                $arr_index = array();
                $periodo = array();
                $clases = array();
                $fechaInicio = strtotime($fecha_min);
                $fechaMaxima = strtotime($fecha_max);
                $dia = 86400;

                $coordinator_assignments = $attendance->GetIdAssignmentByIdGroupAndTeacher($id_group);
                if (!empty($coordinator_assignments)) {
                    foreach ($coordinator_assignments as $coor_assignments) {
                        $AttendanceIndex = $attendance->getAttendanceIndexReportCoordinator($coor_assignments->id_assignment, $fecha_min, $fecha_max);

                        if (!empty($AttendanceIndex)) {
                            foreach ($AttendanceIndex as $attend_index) {
                                $att_index = $attend_index->id_attendance_index;
                                array_push($arr_index, $att_index);
                                $clases_dadas++;
                            }

                            //$AttendanceRecords = $attendance->getStudentAttendance($id_assingment, $fechaActual);

                        }
                    }

                    array_push($clases, $clases_dadas);
                    array_push($periodo, $clases);
                }

                $periodo = array(
                    'periodo' => $period->no_period,
                    'teacher_class' => $clases,
                    'indexes' => $arr_index
                );
                /* echo json_encode($periodo); */
                array_push($periodos, $periodo);
                array_push($std_periods, $periodo);
            ?>
                <th colspan="4">Periodo <?= $period->no_period ?></th>
            <?php endforeach;

            /*  
                echo '<br>';
                echo json_encode($std_periods);  */ ?>
            <th class="col-md-2" colspan="4" style=" padding-left:1px !important; padding-right:1px !important;">TOTAL</th>
        </tr>
        <tr>
            <th class="font-weight-bold"></th>
            <th class="font-weight-bold"></th>
            <?php

            $total_teacher_class = 0;
            foreach ($periods as $period) :
                $no_p = $period->no_period;
                $no_p = $no_p - 1;
                $teacher_class = ($clases_dp = $periodos[$no_p]['teacher_class'][0]);
                $total_teacher_class = $total_teacher_class + $teacher_class;
            ?>
                <th colspan="4" style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">CLASES DADAS: <?= $teacher_class ?></th>
            <?php endforeach; ?>

            <th colspan="4" style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">CLASES DADAS: <?= $total_teacher_class ?></th>
        </tr>
        <tr>
            <th class="font-weight-bold"></th>
            <th class="font-weight-bold"></th>
            <?php foreach ($periods as $period) : ?>
                <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">CA</th>
                <th style=" padding-left:1px !important; color:red;  padding-right:1px !important;" class="font-weight-bold">CF</th>
                <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">FJ</th>
                <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">P.I</th>
            <?php endforeach; ?>

            <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">CA</th>
            <th style=" padding-left:1px !important; color:red;  padding-right:1px !important;" class="font-weight-bold">CF</th>
            <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">FJ</th>
            <th style=" padding-left:1px !important; padding-right:1px !important;" class="font-weight-bold">P.I</th>
        </tr>

    </thead>

    <tbody class="list">
        <?php

        $std_number = 0;
        $group_pecentage = 0;
        $porcentaje_periodos = array();

        foreach ($listStudent as $student) :

            $std_number++;
            $total_day_list = array();
            $teacher_class_total = 0;
            $std_class_total = 0;
            $std_justificated_total = 0;
            $std_absence_total = 0;
            $std_percentage_total = 0; ?>
            <tr id="<?= $student->id_student; ?>">

                <td style="padding-left: 1px !important; padding-right:1px !important; max-width:60px !important;"><?= strtoupper($student->student_code) ?></td>
                <td style="padding-left: 1px !important; padding-right:1px !important;"><?= ucfirst($student->name_student) ?></td>
                <?php foreach ($periods as $period) :
                    $periodo_real = $period->no_period;
                    $no_p = $period->no_period;
                    $no_p = $no_p - 1;
                    $std_percentage = 0;
                    $suma_periodo = 0;
                    $std_class = 0;
                    $std_justificated = 0;
                    $std_absence = 0;
                    $period_day_list = array();
                    $period_day_list_justifed = array();
                    $absence_days = "";
                    $bloques = "";
                    $justified_days = "";

                    $teacher_class = ($clases_dp = $periodos[$no_p]['teacher_class'][0]);
                    $teacher_class_total = $teacher_class_total + $teacher_class;
                    if (!empty($periodos[$no_p]['indexes'][0])) {
                        for ($i = 0; $i < count($periodos[$no_p]['indexes']); $i++) {

                            $id_att_index = $periodos[$no_p]['indexes'][$i];
                            $id_std = $student->id_student;
                            $ab_type = "";
                            if (isset($_GET['att_type'])) {
                                if ($_GET['att_type'] == "1") {
                                    $ab_type = ' OR ((t1.attend = 0 AND t1.apply_justification = 1) OR (t1.attend = 0 AND t2.apply_justification = 1))';
                                }
                            }
                            $listStudentAttend = $attendance->getStudentAttendanceByTypes($id_att_index, $id_std, $ab_type);

                            foreach ($listStudentAttend as $attend_student) {
                                $std_results = $attend_student->student_base;
                            }
                            if ($std_results > 0) {
                                $std_class++;
                            } else {
                                $getIndexDate = $attendance->getIndexDate($id_att_index);

                                foreach ($getIndexDate as $apply_date) {
                                    $array_absence_date = explode(" ", $apply_date->apply_date);
                                    $id_ass_absence_date = $apply_date->id_assignment;
                                    $teacher_passed_attendance = $apply_date->teacher_passed_attendance;
                                    $class_block = $apply_date->class_block;
                                }
                                $getSubjectInfo = $attendance->getSubjectInfo($id_ass_absence_date);
                                $subject = $getSubjectInfo[0]->name_subject;

                                $getTeacherInfo = $attendance->getTeacherInfo($teacher_passed_attendance);
                                $teach_name = $getTeacherInfo[0]->name;

                                $array_apply_date = explode("-", $array_absence_date[0]);
                                $absence_date = $array_apply_date[2] . "/" . $array_apply_date[1] . "/" . $array_apply_date[0] . " | " . $subject . " | " . $teach_name . " | " . $periodo_real . "/" . $class_block;
                                $std_absence++;
                                
                                $get_incident_student = $attendance->getStudentIncidentCount($id_att_index, $id_std);
                                $absence_attendance = 0;
                                //
                                foreach ($get_incident_student as $incident_student) {
                                    $absence_attendance = $incident_student->absence_attendance;
                                }
                                if ($absence_attendance > 0) {
                                    $std_justificated++;
                                    $std_justificated_total++;
                                    array_push($period_day_list_justifed, $absence_date);
                                } else {
                                    // $absence_days .= $periodos[$no_p]['days'][$i] . " ";
                                }


                                $absence_date = $array_apply_date[2] . "/" . $array_apply_date[1] . "/" . $array_apply_date[0] . "/" . $subject . "/" . $teach_name . "/" . $periodo_real . "/" . $class_block;;
                                array_push($period_day_list, $absence_date);
                            }
                        }
                        for ($i = 0; $i < count($period_day_list); $i++) {
                            $absence_days .= $period_day_list[$i] . "-";
                        }
                        for ($i = 0; $i < count($period_day_list_justifed); $i++) {
                            $justified_days .= $period_day_list_justifed[$i] . "-";
                        }
                        foreach ($listStudentAttend as $student_attend) :
                            if ($std_class > 0) {
                                if ($_GET['att_type'] == "1") {
                                    $std_class = $std_class + $std_justificated;
                                }
                                $std_percentage = ($std_class * 100) / $teacher_class;
                            } else {
                                $std_percentage = 100;
                            }
                            $suma_periodo = $suma_periodo + $std_percentage;
                            $std_absence_total = $std_absence_total + $std_absence;
                            $std_class_total = $std_class_total + $std_class;
                ?>
                            <td style="padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_class ?></td>
                            <td style="padding-left: 1px !important; color:red; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><button type="button" onclick="getAssistanceDetails('<?= $absence_days ?>','<?= $id_std ?>')" class="btn btn-outline-secondary btn_std_attendance" style="color: red !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $std_absence ?> </button></td>
                            <td style="padding-left: 1px !important; color:rgba(204, 153, 0); padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><button type="button" onclick="getAssistanceDetailsJustified('<?= $justified_days ?>','<?= $id_std ?>')" class="btn btn-outline-secondary btn_std_attendance" style="color:rgba(204, 153, 0); !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $std_justificated ?> </button></td>
                            <td style="padding-left: 1px !important; color:white !important;  padding-right:1px !important; border-right: 3px solid rgba(194, 194, 194) !important; background-color: rgba(122, 122, 122) !important;"><?= number_format((100 - ($std_percentage)), '0', '.', '') ?>%</td>

                        <?php endforeach;
                    } else { ?>
                        <td style="padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_class ?></td>
                        <td style="padding-left: 1px !important; color:red;  padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_absence ?></td>
                        <td style="padding-left: 1px !important; color:rgba(204, 153, 0);  padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_justificated ?></td>
                        <td style="padding-left: 1px !important;  color:white !important; padding-right:1px !important; border-right: 3px solid rgba(194, 194, 194) !important; background-color: rgba(122, 122, 122) !important;"><?= number_format((($std_percentage)), '0', '.', '') ?>%</td>


                <?php
                    }

                endforeach;

                if ($std_class_total > 0) {
                    if ($_GET['att_type'] == "1") {
                        $std_class_total = $std_class_total - $std_justificated_total;
                    }
                    $std_percentage_total = ($std_class_total * 100) / $teacher_class_total;
                }
                $group_pecentage = $group_pecentage + $std_percentage_total;
                ?>

                <td style="padding-left: 1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_class_total; ?></td>
                <td style="padding-left: 1px !important; color:red;  padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_absence_total; ?></td>
                <td style="padding-left: 1px !important; color:rgba(204, 153, 0);  padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;"><?= $std_justificated_total; ?></td>
                <td style="padding-left: 1px !important;  color:white !important; padding-right:1px !important; border-right: 3px solid rgba(194, 194, 194) !important; background-color: rgba(122, 122, 122) !important;"><?= number_format((100 - ($std_percentage_total)), 0, '.', ''); ?>%</td>

            </tr>

        <?php endforeach;
        array_push($suma_periodos, $suma_periodo);
        $gp = $group_pecentage / $std_number;; ?>

        <!-- <tr>
                <td></td>
                <td></td>
                <?php foreach ($periods as $period) : ?>
                    <td colspan="4" style="border-left: 3px solid rgba(194, 194, 194) !important; border-right: 3px solid rgba(194, 194, 194) !important; background-color: rgb(225, 247, 236)">0%</td>
                <?php endforeach; ?>
                <td colspan="3"></td>
            </tr> -->
        <thead class="thead-light">
            <tr class="table-active">
                <th class="col-md-2" colspan="100%">PORCENTAJE DE INASISTENCIA GRUPAL TOTAL</th>
            </tr>
            <tr>
                <th class="font-weight-bold" colspan="100%"> <?= number_format((100 - ($gp)), 0, '.', '') ?>%</th>
            </tr>
        </thead>
    </tbody>
</table>
<input type="hidden" id="std_number" name="std_number" value="<?= $std_number ?>"> </input>
<script>
    var std_number = $('#std_number').val();
    $('#txt_nmb_std').text("Número de alumnos: " + std_number);
    console.log(std_number);
</script>