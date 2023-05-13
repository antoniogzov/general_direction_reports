<?php
if (($grants & 8)) {
    $admisions = new Admisions;
}

$today_date = date('Y-m-d');
$dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


include 'card_select_students.php';
?>

<?php if ($grants & 8) :
    if (isset($_GET['id_academic'])) {

        $students = $admisions->getActiveStudents($_GET['id_academic']);
?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" herf="http.//fonts.googleapis.com/cssfamily=Tangerine">
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="vendor/tablefilter/tablefilter.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <div class="card mb-4">
            <div class="row">
                <div class="col">
                    <div class="card col-md-12">
                        <div class="card-header border-0">
                            <h3 id="title_horario">ALUMNOS PARA ADMISIÓN CICLO ESCOLAR <?= $students[0]->next_school_year ?></h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tableAdmisionsCoordinator" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">CÓD. ALUMNO</th>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">NOMBRE</th>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">NIVEL ACADÉMICO</th>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">GRUPO</th>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">EDAD</th>
                                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">REPITE CICLO ACTUAL</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($students as $student) :
                                        $getRepitients = $admisions->getRepitients($student->student_code);
                                        if (empty($getRepitients)) {
                                            $check = '';
                                        } else {
                                            $check = 'checked';
                                        }
                                        $arr_fecha = explode("-", $student->birthdate);
                                        $mp = date('n', strtotime($student->birthdate));

                                        $mes_pase = $meses[$mp];
                                        $ds = date('N', strtotime($student->birthdate));
                                        $yearFecha = $arr_fecha[0];

                                        $fecha_nacimiento = $arr_fecha[2] . ' de <br>' . $mes_pase . ' de ' . $yearFecha;

                                        $ahora = new DateTime(date("Y-m-d"));
                                        $edad = $ahora->diff(new DateTime($student->birthdate));
                                        $edad = $edad->format("%y");

                                    ?>
                                        <tr>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <?= $student->student_code ?>
                                            </td>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <?= $student->student_name ?>
                                            </td>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <?= mb_strtoupper($student->degree) ?>
                                            </td>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <?= $student->group_code ?>
                                            </td>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <?= $edad ?> años
                                            </td>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                <div align="center">
                                                    <label class="custom-toggle">
                                                        <input <?= $check ?> type="checkbox" class="repeatSchoolCycle" data-id-student="<?= $student->id_student ?>" data-student-code="<?= $student->student_code ?>">
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                var tf = new TableFilter(document.querySelector("#tableAdmisionsCoordinator"), {
                    base_path: "../general/js/vendor/tablefilter/tablefilter/",

                    col_2: "select",
                    col_3: "select",
                    paging: {
                        results_per_page: ['Records: ', [50, 100]]
                    },
                    rows_counter: true,
                    btn_reset: true,
                    responsive: true,
                });
                tf.init();

                $('#tableAdmisionsCoordinator').DataTable({
                    dom: 'Bfrtip',
                    "ordering": false,
                    "paging": false,
                    buttons: [{
                            extend: 'excelHtml5',
                            filename: 'ALUMNOS PARA ADMISIÓN CICLO ESCOLAR <?= $students[0]->school_year ?>'
                        },
                        {
                            extend: 'pdfHtml5',
                            filename: 'ALUMNOS PARA ADMISIÓN CICLO ESCOLAR <?= $students[0]->school_year ?>',
                            title: 'ALUMNOS PARA ADMISIÓN CICLO ESCOLAR <?= $students[0]->school_year ?>',
                            orientation: 'landscape'
                        }
                    ]
                });
            });
        </script>

<?php
    }
endif;
function replaceString($string)
{
    $str_final = "";
    $count = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $count++;
        if ($count > 18) {

            $count = 0;
            if ($string[$i] == " ") {
                $str_final .= "<br>";
            } else {
                $str_final .= $string[$i] . "-" . "<br>";
            }
        } else {
            $str_final .= $string[$i];
        }
    }
    return $str_final;
}

?>
<script src="js\functions\admisions\admisions.js">
</script>