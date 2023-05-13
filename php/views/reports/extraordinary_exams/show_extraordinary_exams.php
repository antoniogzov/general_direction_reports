<div id="content">
    <div class="card mb-4">
        <div class="card-body">
            <?php
            foreach ($getGroupsExtraordinaryReports as $group) :
                $getSubjectsExtraordinaryReports = $qualifications_reports->getSubjectsExtraordinaryReports($_GET['id_period'], $group->id_group);
                $getStudentsExtraordinaryReports = $qualifications_reports->getStudentsExtraordinaryReports($_GET['id_period'], $group->id_group);

                //--- --- ---//
            ?>
                <div class="card card-table-evaluations content-wrapper wide" id="content<?= $group->id_group ?>">
                    <div class="card-body">
                        <h1>Grupo: <?= $group->group_code ?> </h1>
                        <br>
                        <table class="table align-items-center table-flush" border="1" id="tbl<?= $group->id_group ?>">
                            <thead class="thead-dark">
                                <tr class="sticky-header">
                                    <th class=" td-hd-600" style="width: 10px !important; color:white">CÓD.</th>
                                    <th class=" text-center" style="color:white">NOMBRE</th>
                                    <?php foreach ($getSubjectsExtraordinaryReports as $subject) : ?>
                                        <th class=" text-center" style="color:white" title="<?= $subject->name_subject ?>"><?= $subject->short_name ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($getStudentsExtraordinaryReports as $student) : ?>
                                    <tr>
                                        <td class=" text-center"><?= ($student->student_code) ?></td>
                                        <td class=" text-center"><?= ($student->student_name) ?></td>
                                        <?php foreach ($getSubjectsExtraordinaryReports as $subject) :
                                            $getStudentsExtraordinaryQualifications = $qualifications_reports->getStudentsExtraordinaryQualifications($_GET['id_period'], $group->id_group, $student->id_student, $subject->id_subject);
                                            $grade_extraordinary_examen = '-';
                                            if (!empty($getStudentsExtraordinaryQualifications)) {
                                                $getStudentsExtraordinaryQualifications = $getStudentsExtraordinaryQualifications[0];
                                                $grade_extraordinary_examen = $getStudentsExtraordinaryQualifications->grade_extraordinary_examen;
                                            }
                                        ?>
                                            <th class=" text-center" style="color:black"><?= $grade_extraordinary_examen ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#tbl<?= $group->id_group ?>').DataTable({
                            dom: 'Bfrtip',
                            "ordering": false,
                            "pageLength": 25,
                            buttons: [{
                                    extend: 'excelHtml5',
                                    title: '<?= $group->group_code ?>',
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: '<?= $group->group_code ?>',
                                }
                            ]
                        });
                    });
                </script>

            <?php endforeach; ?>

        </div>
    </div>
</div>
<script>
    Swal.close();
</script>