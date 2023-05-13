<?php
include 'card_select_wsr.php';



$groups = array();
if (isset($_GET['id_academic_level'])) {
    $groups = $attendance->getAllMyGroupsByAcademicLevelAndAcademicArea($_GET['id_academic_level'], $_GET['id_group'], $no_teacher);
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
    $dia_inicio = $arr_init_date[1];
    $fecha_titulo1 = $dia_inicio . " de " . $months[$mes] . " de " . $year;

    $mes_fin = $arr_final_date[0];
    $year_fin = $arr_final_date[2];
    $fecha_titulo2 = $arr_final_date[1] . " de " . $months[$mes_fin] . " de " . $year_fin;
}

?>

<?php if (!empty($groups)) : ?>
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
    include 'generate_report_object.php' ?>
    <div class="accordion" id="accordionGroups">
        <div class="card">

            <?php
            $select_struct = 0;
            foreach ($groups as $group) :

                $total_clases_dadas = 0;
                /* $getAssignmnetsByTeacher = $attendance->getAssignmnetsByTeacherAndArea($teacher->no_colaborador, $id_academic_level, $teacher->id_academic_area); */
            ?>
                <div class="card-header" id="heading<?= $group->id_group ?>" data-toggle="collapse" data-target="#collapse<?= $group->id_group ?>" aria-controls="collapse<?= $group->id_group ?>">
                    <h5 class="mb-0"><?= ucfirst($group->group_code) ?></h5>
                </div>
                <div id="collapse<?= $group->id_group ?>" class="collapse" aria-labelledby="heading<?= $group->id_group ?>" data-parent="#accordionGroups">
                    <div class="card-body">
                        <div class="card-body">
                            <h2 class="mb-0">GRUPO: <?= ucfirst($group->group_code) ?></h2>
                            <h2 class="mb-0"> <?= mb_strtoupper($fecha_titulo1) ?> al <?= mb_strtoupper($fecha_titulo2) ?></h2>
                            <br>
                            <br>
                            <div class="table-responsive">

                                <?php foreach ($structure[$select_struct]['students'] as $student) :
                                    /* $getRowspanWASR = $attendance->getRowspanWASR($student['student_info']->id_student, $init_date, $final_date); */
                                    /* 
                        $valid = 0;
                        if (!empty($getRowspanWASR)) {
                            $rowspan = ($getRowspanWASR[0]->rowspan);
                        } else {
                            $rowspan = 1;
                        } */
                                ?>
                                    <br>
                                    <br>
                                    <table style="text-align: center;" class="table align-items-center table-striped table-flush tableAttReport" id="tdResults">
                                        <thead class="thead-light">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th colspan="10">DÍAS</th>
                                            </tr>
                                            <tr>
                                                <th>CÓD. ALUMNO</th>
                                                <th>NOMBRE</th>
                                                <?php
                                                for ($i = 1; $i < 6; $i++) {
                                                    $dia = mktime(0, 0, 0, $partes[1], $partes[2] + $i, $partes[0]);
                                                    $fecha = date("Y-m-d", $dia);
                                                    $dia_arr = explode('-', $fecha);
                                                ?>
                                                    <th colspan="2"><?= $days[$i] ?> | <?= $dia_arr[2] ?></th>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody class="list">

                                            <?php

                                            for ($e = 0; $e < ($student['student_attend']['rowspan']); $e++) {
                                                if ($e == 0) {

                                                    $id_student = $student['student_info']->id_student;
                                                    $rowspan_tr = $student['student_attend']['rowspan'];
                                                    $student_code = $student['student_info']->student_code;
                                                    $student_name = $student['student_info']->student_name;

                                                    echo "<tr style='border-top: 2px solid black !important' id='trStud" . $id_student . "'>";
                                                    echo '<td rowspan="' . $rowspan_tr . '"><strong>' . $student_code . '</strong></td>';
                                                    echo '<td rowspan="' . $rowspan_tr . '"><strong>' . $student_name . '</strong></td>';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                for ($i = 1; $i < 6; $i++) {

                                                    if (isset($student['student_attend']['std_days'][0][$i]["tr_html"][$e])) {

                                                        $tr_11s = str_replace("-tds", "<td", $student['student_attend']['std_days'][0][$i]["tr_html"][$e]);
                                                        $tr_11s2 = str_replace("-s", ">", $tr_11s);
                                                        $tr_12 = str_replace("--td", "<td", $tr_11s2);
                                                        $tr_13 = str_replace("-t", ">", $tr_12);
                                                        $tr_22 = str_replace("+td+", "</td>", $tr_13);
                                                    } else {
                                                        $tr_22 = "<td>-</td><td>-</td>";
                                                    }
                                                    echo $tr_22;
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    <?php
                                endforeach; ?>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
                $select_struct++;
            endforeach; ?>
        </div>
    </div>

<?php endif; ?>

<script>
    Swal.close();
    /*  if ($('#tdResults').length > 0) {
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

    } */
</script>
<script src="js/functions/students/teachers/week_attendance_report.js"></script>
<script src="js/functions/students/teachers/week_attendance_report_students.js"></script>