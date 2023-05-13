<?php
include dirname(__DIR__, 1) . '/card_select_week_teacher.php';



$teacherList = array();
if (isset($_GET['id_academic_level'])) {
    $teacherList = $attendance->getAllMyTeachersByAcademicLevelAndAcademicArea($_GET['id_academic_level'], $no_teacher);
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

<?php if (!empty($teacherList)) : ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script src="vendor/tablefilter/tablefilter.js"></script>
    <div class="card">
        <div class="card-body">
            <input type="hidden" id="academic_level" value="<?= $id_academic_level ?>">
            <h2><?= ucfirst($fecha_titulo) ?></h2>
            <div class="table-responsive">
                <table style="text-align: center;" class="table align-items-center table-striped table-flush" id="tdResults">
                    <thead class="thead-light">
                        <tr>
                            <th>N° COLAB.</th>
                            <th>PROFESOR</th>
                            <th>Á. ACADÉMICA</th>
                            <th>MATERIAS ASIGNADAS</th>
                            <th>NIVEL ACADÉMICO</th>
                            <th>SECCIÓN</th>
                            <?php
                            for ($i = 1; $i < 6; $i++) {
                                $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                $fecha = date("Y-m-d", $dia);
                                $dia_arr = explode('-', $fecha);
                            ?>

                                <th><?= $days[$i] ?> | <?= $dia_arr[2] ?> <br />CD</th>
                            <?php
                            }
                            ?>
                            <th>TOTAL</th>
                        </tr>
                    </thead>

                    <tbody class="list">
                        <?php foreach ($teacherList as $teacher) :
                            $total_clases_dadas = 0;
                            $getAssignmnetsByTeacher = $attendance->getAssignmnetsByTeacherAndArea($teacher->no_colaborador, $id_academic_level, $teacher->id_academic_area);

                        ?>

                            <tr id="<?= $teacher->no_colaborador; ?>">
                            <th><?= $teacher->no_colaborador ?></th>
                                <th><?= $teacher->teacher_name; ?></th>
                                <th><?= mb_strtoupper($teacher->name_academic_area); ?></th>
                                <th><button type="button" class="btn btn-outline-secondary btn_teacher_assignments" data-id-academic-area="<?=$teacher->id_academic_area?>" id-teacher="<?= $teacher->no_colaborador ?>" style="padding-left: 1px !important; padding-right:1px !important;"> <?= count($getAssignmnetsByTeacher) ?> </button></th>
                                <th><?= mb_strtoupper($teacher->academic_level); ?></th>
                                <th><?= mb_strtoupper($teacher->section); ?></th>
                                <?php

                                for ($i = 1; $i < 6; $i++) {
                                    $clases_dadas = 0;
                                    $ids_attendance_index = "";
                                    $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                    $fecha = date("Y-m-d", $dia);
                                    $dia_arr = explode('-', $fecha);
                                    $TeacherClassess = $attendance->getAttendanceAcademicLevel($teacher->no_colaborador, $fecha, $id_academic_level);
                                    //
                                    if (!empty($TeacherClassess)) {
                                        $clases_dadas = $clases_dadas + count($TeacherClassess);
                                        $total_clases_dadas = $total_clases_dadas + count($TeacherClassess);
                                        foreach ($TeacherClassess as $TeacherClass) {
                                            $ids_attendance_index .= $TeacherClass->id_attendance_index . ",";
                                        }
                                    }

                                ?>

                                    <th><button type="button" onclick="getPassedAttendanceDetails('<?= $ids_attendance_index ?>', '<?= $teacher->no_colaborador ?>')" class="btn btn-outline-secondary btn_teacher_attendance" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $clases_dadas ?> </button></th>
                                <?php
                                }
                                ?>
                                <th><?= $total_clases_dadas ?></th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<?php endif; ?>
<script>
    if ($('#tdResults').length > 0) {
        var tf = new TableFilter('tdResults', {
            base_path: '../general/js/vendor/tablefilter/tablefilter/',
            col_0: 'select',
            col_1: 'select',
            col_2: 'none',
            col_3: 'select',
            col_4: 'select',
            col_5: 'none',
            col_6: 'none',
            col_7: 'none',
            col_8: 'none',
            col_9: 'none',
            auto_filter: {
                delay: 100
            },
            btn_reset: true,
        });
        tf.init();



        let table = new DataTable('#tdResults', {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                }, 'print'
            ]
        });
        table.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));

    }
</script>
<script src="js/functions/students/teachers/week_attendance_report.js"></script>