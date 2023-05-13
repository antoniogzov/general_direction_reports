<?php
include 'card_select_wsr.php';

$groups = array();
if (isset($_GET['id_academic_level'])) {




    $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script>
        function loading() {
            Swal.fire({
                text: "Cargando...",
                html: '<img src="images/loading_iteach.gif" width="300" height="300">',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
        }
        loading();
    </script>
    <script src="vendor/tablefilter/tablefilter.js"></script>
    <style>

    </style>
<?php

    if (isset($_GET['id_academic_level']) && isset($_GET['id_group']) && isset($_GET['id_period']) && isset($_GET['id_academic_area'])) {

        if (($grants & 8)) {

            //include 'generate_report_object.php' 
            $groups = $attendance->getAllMyGroupsByAcademicLevelAndAcademicArea($_GET['id_academic_level'], $_GET['id_group'], $no_teacher);
            $assignments = $attendance->getAssignmentsByGroup($_GET['id_academic_level'], $_GET['id_group'], $no_teacher, $_GET['no_period']);
            $getStudentsByGroup = $attendance->getStudentsByGroup($_GET['id_group']);
            $periods = $attendance->getPeriodCalendar($_GET['id_period']);
            if (!empty($periods)) {
                $periodo = $periods[0];
            }
        } else if (($grants & 4)) {
            $groups = $attendance->getAllMyGroupsByAcademicLevelAndAcademicAreaTeacher($_GET['id_academic_level'], $_GET['id_group'], $no_teacher);
            $assignments = $attendance->getAssignmentsByGroupTeacher($_GET['id_academic_level'], $_GET['id_group'], $no_teacher, $_GET['no_period']);
            $getStudentsByGroup = $attendance->getStudentsByGroup($_GET['id_group']);
            $periods = $attendance->getPeriodCalendar($_GET['id_period']);
            if (!empty($periods)) {
                $periodo = $periods[0];
            }
        }
    }
}
?>

<?php if (!empty($groups)) : ?>
    <div class="card-body">

        <div class="card">

            <div class="card-body">
                <h2 class="mb-0">GRUPO: <?= ($groups[0]->group_code) ?></h2>
                <h2 class="mb-0">PERIODO: <?= ($_GET['no_period']) ?></h2>
                <br>
                <br>
                <div class="table-responsive">
                    <br>
                    <br>
                    <table style="text-align: center;" class="table align-items-center table-striped table-flush tableAttReport" id="tdResults">
                        <thead class="thead-light">
                            <tr>
                                <th>CÓD. ALUMNO</th>
                                <th>NOMBRE</th>
                                <?php foreach ($assignments as $assignment) : ?>
                                    <th title="ID Assignment: <?= $assignment->id_assignment ?> | <?= $assignment->name_subject ?> | <?= $assignment->assg_teacher ?> "><?= $assignment->short_name ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                        <?php if (!empty($getStudentsByGroup)) : ?>
                            <tbody class="list">
                                <?php foreach ($getStudentsByGroup as $student) : ?>
                                    <tr>
                                        <td><?= $student->student_code ?></td>
                                        <td><?= $student->student_name ?></td>
                                        <?php foreach ($assignments as $assignment) : ?>

                                            <?php
                                            $period_absences = 0;
                                            $period_attends = 0;
                                            $period_percentage = 0;
                                            $id_student = $student->id_student;


                                            $AttendanceIndex = $archives->getAttendanceIndexStudentArchive($assignment->id_assignment, $periodo->start_date, $periodo->end_date, $student->id_student);
                                            $getStudentAbsencesArchive = $archives->getStudentAbsencesArchive($assignment->id_assignment, $periodo->start_date, $periodo->end_date, $student->id_student);
                                            $getStudentAttendsArchive = $archives->getStudentAttendsArchive($assignment->id_assignment, $periodo->start_date, $periodo->end_date, $student->id_student);


                                            $clases_student  = count($AttendanceIndex);
                                            if ($clases_student > 0) {
                                                $str_period_class = $clases_student;
                                            } else {
                                                $str_period_class = "-";
                                            }

                                            $period_attends  = count($getStudentAttendsArchive);
                                            if ($period_attends > 0) {
                                                $str_period_attends = $period_attends;
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
                                                $color_tr = "";

                                                if ($period_percentage == 100) {
                                                    $color_tr = "#b0f0a3";
                                                } else if ($period_percentage <= 90 && $period_percentage >= 85) {
                                                    $color_tr = "#f0d59c";
                                                } else if ($period_percentage < 85) {
                                                    $color_tr = "#fc9595";
                                                } else {
                                                    $color_tr = "";
                                                }
                                            } else {
                                                $str_period_percentage = "-";
                                            }

                                            ?>
                                            <td class="trDetailsAttend" data-start-date="<?= $periodo->start_date; ?>" data-end-date="<?= $periodo->end_date; ?>" data-id-student="<?= $student->id_student; ?>" data-id-assignment="<?= $assignment->id_assignment; ?>" style="background-color:<?= $color_tr ?> !important; border-right: 1px solid rgba(194, 194, 194) !important; border-left: 3px solid rgba(194, 194, 194) !important;"><?= ($str_period_percentage) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    Swal.close();

    var name_report = $('#id_group option:selected').text();
    var id_period = $('#id_period option:selected').text();
    name_archive = "ASISTENCIA GRUPAL  " + name_report + " Periodo " + id_period;

    if ($('#tdResults').length > 0) {

        $('#tdResults').DataTable({
            dom: 'Bfrtip',
            "paging": false,
            buttons: [{
                extend: 'excelHtml5',
                filename: name_archive
            }],
            "ordering": false
        });

    }
</script>
<script src="js/functions/students/teachers/period_group_att_report.js"></script>