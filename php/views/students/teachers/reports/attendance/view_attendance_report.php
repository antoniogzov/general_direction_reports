<?php
if (isset($_GET['id_assignment'])) {
    $id_assingment = $_GET['id_assignment'];
    if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
        $id_level_combination = $id_level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
        $att_type = $_GET['att_type'];
    }
}
?>
<?php if (!empty($listStudent)) : ?>
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
    <script src="../general/js/vendor/tablefilter/tablefilter/tablefilter.js" async></script>

    <div class="row">
        <div class="col">
            <h3 id="txt_grupo"></h3>
            <h4 id="">Número de alumnos: <?= count($listStudent) ?></h4>
        </div>
        <div class="col">
            <p align="right" style="padding-bottom:1px !important; font-size:12px; color:blue !important;" class="font-weight-bold">CD: Clases Datas</p>
            <p align="right" style="padding-bottom:1px !important; font-size:12px; color:green !important;" class="font-weight-bold">CA: Clases Asistidas</p>
            <p align="right" style="padding-bottom:1px !important; font-size:12px; color:red !important;" class="font-weight-bold">CF: Clases Faltantes</p>
            <p align="right" style="padding-bottom:1px !important; font-size:12px;" class="font-weight-bold">PA: Porcentaje de Asistencia</p>
        </div>
        <div class="w-100"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>

    <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tStudents">
        <thead class="thead-light">
            <tr>
                <th>CÓD. ALUMNO</th>
                <th>NOMBRE</th>
                <?php foreach ($periods as $periodo) : ?>
                    <th colspan="4">PERIODO <?= $periodo->no_period ?></th>
                <?php endforeach; ?>
                <th colspan="4">TOTAL</th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <?php foreach ($periods as $periodo) : ?>
                    <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;">CD</th>
                    <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">CA</th>
                    <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">CF</th>
                    <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">PI</th>
                <?php endforeach; ?>
                <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;">CD</th>
                <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">CA</th>
                <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">CF</th>
                <th style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important;">PI</th>
            </tr>
        </thead>
        <tbody class="list">
            <?php foreach ($listStudent as $student) : ?>
                <?php

                $student_indexs = 0;
                $student_absences = 0;
                $student_attends = 0;
                $student_percentage = 0;



                ?>
                <tr>
                    <td style=""><?= strtoupper($student->student_code) ?></td>
                    <td style=""><?= strtoupper($student->name_student) ?></td>
                    <?php
                    $style_tr = 'padding-left:1px !important; padding-right:1px !important;';
                    ?>
                    <?php foreach ($periods as $periodo) : ?>
                        <?php
                        $period_absences = 0;
                        $period_attends = 0;
                        $period_percentage = 0;
                        $id_student = $student->id_student;
                        $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($_GET['id_assignment'], $periodo->start_date, $periodo->end_date, $student->id_student);
                        $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($_GET['id_assignment'], $periodo->start_date, $periodo->end_date, $student->id_student);
                        $getStudentAttendsArchive = $archives->getStudentAttendsArchive($_GET['id_assignment'], $periodo->start_date, $periodo->end_date, $student->id_student);

                        $clases_student  = count($AttendanceIndex);
                        if ($clases_student > 0) {
                            $str_period_class = $clases_student;
                            $student_indexs = $student_indexs + $clases_student;
                        } else {
                            $str_period_class = "-";
                        }

                        $period_attends  = count($getStudentAttendsArchive);
                        if ($period_attends > 0) {
                            $str_period_attends = $period_attends;
                            $student_attends = $student_attends + $period_attends;
                        } else {
                            $str_period_attends = "-";
                        }


                        $period_absences_if  = count($getStudentAbsencesArchive);
                        if ($period_absences_if > 0) {
                            foreach ($getStudentAbsencesArchive as $std_absences) {
                                if ($std_absences->double_absence == 1) {
                                    $period_absences++;
                                    $student_absences++;
                                }
                                $period_absences++;
                                $student_absences++;
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
                        <td style=" <?= $style_tr ?> color:#0341fc !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= count($AttendanceIndex) ?></td>
                        <td style=" <?= $style_tr ?> color:#00ff44 !important; border-right: 1px solid rgba(194, 194, 194) !important; "><button type="button" onclick="getAssistanceDetailsReports('<?= $periodo->start_date ?>', '<?= $periodo->end_date ?>', '<?= $id_assingment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary btn_std_attendance" style="color: #00ff44 !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $period_attends ?> </button></td>
                        <td style=" <?= $style_tr ?> color:#ff0000 !important; border-right: 1px solid rgba(194, 194, 194) !important; "><button type="button" onclick="getAttendanceReportDetailsAbsences('<?= $periodo->start_date ?>', '<?= $periodo->end_date ?>', '<?= $id_assingment ?>', '<?= $id_student ?>')" class="btn btn-outline-secondary btn_std_attendance" style="color: red !important; padding-left: 1px !important; padding-right:1px !important;"> <?= $period_absences ?> </button></td>
                        <td style=" <?= $style_tr ?> border-right: 1px solid rgba(194, 194, 194) !important; "><?= $str_period_percentage ?></td>
                        <!-- <td style="padding-left:1px !important; padding-right:1px !important; border-right: 1px solid rgba(194, 194, 194) !important; "><?= $attendance_period_percentage ?> %</td> -->
                    <?php endforeach; ?>
                    <?php
                    $style_results = 'padding-left:1px !important; padding-right:1px !important; ';
                    if (($student_indexs) > 0) {
                        $student_percentage = (number_format((((($student_indexs) - $student_absences) / ($student_indexs) * 100)), 0));
                        $str_student_percentage = $student_percentage . "%";
                    } else {
                        $str_student_percentage = "-";
                    }
                    ?>

                    <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $student_indexs ?></td>
                    <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $student_attends ?></td>
                    <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $student_absences ?></td>
                    <td style="<?= $style_results ?> color:rgb(110, 110, 110) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= $str_student_percentage ?></td>
                </tr>
            <?php endforeach; ?>
            <thead class="thead-light">
                <tr class="table-active">
                    <!-- <th class="col-md-2" colspan="100%">PORCENTAJE DE INASISTENCIA GRUPAL TOTAL</th> -->
                </tr>
                <tr>

                </tr>
            </thead>
        </tbody>
    </table>
    <script>
        var std_number = $('#std_number').val();
        $('#txt_nmb_std').text("Número de alumnos: " + std_number);
        console.log(std_number);
        Swal.close();
    </script>
<?php endif; ?>