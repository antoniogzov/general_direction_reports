<?php


$listStudent = array();
$group_code = "";
$listIncidents = array();
$days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$months = array("-", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

if (isset($_GET['id_index'])) {
    $arr_id_index = explode(",", $_GET['id_index']);
    $id_academic = $_GET['id_academic'];
    if ($id_academic == "1") {
        $str_academic = "Español";
    } else {
        $str_academic = "Hebreo";
    }
    $week = $_GET['week'];
    $arr_week = explode("-", $_GET['week']);
    $arr_week2 = explode("/", $arr_week[1]);
    $arr_week = explode("/", $arr_week[0]);
}

?>
<script src="vendor/tablefilter/tablefilter.js"></script>
<div class="card">
    <div class="card-body">
        <!-- <?php


                foreach ($getAttendanceDetails as $details) :

                    $str_profesor = "Profesor que realizó el pase de lista: " . $details->teacher_name;
                    $str_grupo = "Grupo: " . $details->group_code . " | Materia: " . $details->name_subject;
                ?>
        <?php endforeach; ?>
        <h2><?= $str_profesor ?></h2>
        <h2><?= $str_grupo ?></h2>
        <h2>ID de registro: ASIST - <?= $id_index ?></h2> -->
        <div class="row mb-5">
            <div class="col-md-3">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 container-list-students">
                <script>
                    Swal.fire({
                        text: 'Cargando...',
                        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCloseButton: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    })
                </script>


                <h2 class="ml-2">Área académica: <?= $str_academic ?></h2>
                <?php if (count($arr_id_index) > 1) {

                ?>
                    <h2 class="ml-2" id="txt_grupo"> </h2>
                <?php
                }
                ?>

                <h2 class="heading mb-0 ml-2">INCIDENCIAS del <?= ($arr_week[1]) ?> de <?= $months[$arr_week[0]] ?> de <?= $arr_week[2] ?> AL <?= ($arr_week2[1]) ?> de <?= $months[$arr_week2[0]] ?> de <?= $arr_week2[2] ?></h2>
                <?php
                if (count($arr_id_index) > 1) {

                ?>
                    <h2 class="ml-2">Registros: <?= (count($arr_id_index)-1) ?></h2>
                    <div class="table-responsive">
                    <?php
                }
                    ?>

                    <table class="table align-items-center table-flush" id="tStudents">
                        <thead class="thead-light">
                            <tr>
                            <th class="font-weight-bold col-md-2">FECHA</th>
                            <th class="font-weight-bold col-md-2">OBSERVACIONES</th>
                                <th class="font-weight-bold col-md-2">CÓD. ALUMNO</th>
                                <th class="font-weight-bold col-md-2">NOMBRE COMPLETO</th>
                                <th class="font-weight-bold col-md-2">MATERIA</th>
                                <th class="font-weight-bold col-md-2">PROFESOR</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php for ($i = 0; $i < (count($arr_id_index) - 1); $i++) {

                                $infoAbscence = $attendance->getInfoAbscence($arr_id_index[$i]);
                                foreach ($infoAbscence as $info) {
                                    $arr_date = explode(' ', $info->apply_date);
                                    $apply_date = $arr_date[0];
                            ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $apply_date ?></th>
                                        <td class="font-weight-bold"><?= $info->incident ?></th>
                                        <td class="font-weight-bold"><?= $info->student_code ?></th>
                                        <td class="font-weight-bold"><?= $info->student_name ?></th>
                                        <td class="font-weight-bold"><?= $info->name_subject ?></th>
                                        <td class="font-weight-bold"><?= $info->teacher_name ?></th>



                                    </tr>
                            <?php
                                    $group_code = $info->group_code;
                                }
                            } ?>
                        </tbody>
                    </table><!--  -->
                    </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="grupo" value="<?= $group_code ?>">
</div>
<script>
    if ($('#tStudents').length > 0) {
        var tf = new TableFilter('tStudents', {
            base_path: '../general/js/vendor/tablefilter/tablefilter/',
            col_0: '',
            col_1: '',
            col_2: '',
            col_3: 'select',
            col_4: 'select',
            col_5: 'select',
            col_6: 'select',
            auto_filter: {
                delay: 100
            },
            btn_reset: true,
        });
        tf.init();
        var grupo = $('#grupo').val();
        $('#txt_grupo').text('Grupo: ' + grupo);

    }
</script>

<script>
    Swal.close();
</script>
<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/attendance_history.js"></script>