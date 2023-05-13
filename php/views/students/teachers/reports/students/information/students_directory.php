<?php

$listStudent = array();
$listIncidents = array();
$listStudent = $attendance->getInfoListStudent();
    $listIncidents = $attendance->getListIncidents();
$std_number = 0;
?>
<?php if (!empty($listStudent)) : ?>
    <div class="card">
        <div class="card-body">
        <script src="../general/js/vendor/tablefilter/tablefilter/tablefilter.js" async></script>
            <div class="table-responsive">
                <h3 id="txt_grupo"></h3>
                <h3 id="txt_nmb_std">Número de alumnos: 0</h3>
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold col-md-2">Cód. alumno</th>
                            <th class="font-weight-bold col-md-4">Nombre</th>
                            <th class="font-weight-bold col-md-2">Grupo</th>
                            <th class="font-weight-bold col-md-2">Campus</th>
                            <th class="font-weight-bold col-md-2">Agrupación</th>
                            <th class="font-weight-bold col-md-2">Nivel Académico</th>
                            <th class="font-weight-bold col-md-2">Género</th>
                            <th class="font-weight-bold col-md-2">Padre</th>
                            <th class="font-weight-bold col-md-2">Teléfono P.</th>
                            <th class="font-weight-bold col-md-2">Mail P.</th>
                            <th class="font-weight-bold col-md-2">Madre</th>
                            <th class="font-weight-bold col-md-2">Teléfono M.</th>
                            <th class="font-weight-bold col-md-2">Mail M.</th>
                            <th class="font-weight-bold col-md-2">Dirección</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php foreach ($listStudent as $student) :
                            $std_number++;
                            $status_type = $student->id_status_type;
                            $style_cell = "";
                            $StatusDescription = $attendance->getStatusDescription($status_type);
                            foreach ($StatusDescription as $StatusType) {
                                $status_description = $StatusType->status_type;
                                $color_html = $StatusType->html_color;
                            }
                            if ($status_type > 1) {
                                $style_cell = "style='color:white;background-color:$color_html;'";
                            }
                        ?>
                            <tr <?= $style_cell; ?> id="<?= $student->id_student; ?>">
                                <td><?= mb_strtoupper($student->student_code) ?></td>
                                <td><?= ucfirst($student->name_student) ?></td>
                                <td><?=$student->group_code?></td>
                                <td><?=$student->campus_name?></td>
                                <td><?=$student->academic_level?></td>
                                <td><?=$student->degree?></td>
                                <td><?=$student->sexo?></td>
                                <td><?=$student->father_name?></td>
                                <td><?=$student->father_cell_phone?></td>
                                <td><?=$student->father_mail?></td>
                                <td><?=$student->mother_name?></td>
                                <td><?=$student->mother_cell_phone?></td>
                                <td><?=$student->mother_mail?></td>
                                <td><?=$student->direction?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="std_number" value="<?= $std_number; ?>">
        <script src="js/functions/students/teachers/export_table.js"></script>
    <?php endif; ?>
    <script>
        var std_number = $('#std_number').val();
        $('#txt_nmb_std').text("Número de alumnos: " + std_number);
        console.log(std_number);
    
        if ($('#tDirectory').length > 0) {
    var tf = new TableFilter('tDirectory', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        alternate_rows: true,
        rows_counter: true,
        btn_reset: true,
        col_3: 'select',
        col_4: 'select',
        col_5: 'select',
        col_6: 'select',
        col_7: 'select',
        loader: true,
        status_bar: true,
        responsive: true,
        extensions: [{
            name: 'sort'
        }]
    });
    tf.init();
}
    </script>