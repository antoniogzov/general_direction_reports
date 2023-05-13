<?php
include dirname(__DIR__, 1) . '/card_select_my_students.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_subject'])) {
    $listStudent = $attendance->getListStudentBySubject($id_subject);
    $listIncidents = $attendance->getListIncidents();
}
$std_number = 0;
?>
<?php if (!empty($listStudent)) : ?>
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <h3 id="txt_nmb_std">Número de alumnos: 0</h3>
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold col-md-2">Cód. alumno</th>
                            <th class="font-weight-bold col-md-4">Nombre</th>
                            <th class="font-weight-bold col-md-4">Grupo</th>
                            <th class="font-weight-bold col-md-2">Observaciones</th>
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
                                <td><?= ucfirst($student->group_code) ?></td>
                                <td><?= mb_strtoupper($status_description) ?></td>
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
    </script>
    <script src="js/functions/students/teachers/student_lsit_group.js" async></script>