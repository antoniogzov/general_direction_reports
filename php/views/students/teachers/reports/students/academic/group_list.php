<?php
$listGroups = array();
    $listGroups = $attendance->getListOfGroups();
$std_number = 0;
?>
<?php if (!empty($listGroups)) : ?>
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <h3 id="txt_nmb_std">Número de grupos: 0</h3>
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold col-md-2">Cód. Grupo</th>
                            <th class="font-weight-bold col-md-4">Número de alumnos</th>
                            <th class="font-weight-bold col-md-2">Desglose</th>
                            <!-- <th class="font-weight-bold col-md-2">Titular de grupo</th> -->
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php foreach ($listGroups as $groups) :
                            $std_number++;
                            $std_bygp = '0';
                            $CountStudents = $attendance->getCountStudentByGroup($groups->id_group);
                            foreach ($CountStudents as $count){
                                $std_bygp = $count->students;
                            }
                        ?>
                            <tr id="<?= $groups->id_group; ?>">
                                <td><a href="?submodule=students_group&id_group=<?= $groups->id_group; ?>" target="_blank"><?= mb_strtoupper($groups->group_code) ?></a></td>
                                <td><?= $std_bygp ?></td>
                                <td><?= ucfirst($groups->desglose) ?></td>
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
        $('#txt_nmb_std').text("Número de grupos: " + std_number);
        console.log(std_number);
    </script>
    
    <script src="js/functions/students/teachers/student_lsit_group.js" async></script>