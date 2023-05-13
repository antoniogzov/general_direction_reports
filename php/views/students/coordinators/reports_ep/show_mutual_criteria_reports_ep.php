<?php
$catalogue_exist = $expected_learnings->getMutualExistCatalogCoordinatorEP($no_teacher, $_GET['id_level_grade'], $criteria, $id_period);
?>
<?php
if (!empty($catalogue_exist)) : ?>
    <div class="card card-table-evaluations">
        <div class="card-body">
            <?php foreach ($catalogue_exist as $catalogue) :
                $getAssignmentsGroup = $expected_learnings->getAssignmentsGroup($no_teacher, $catalogue->id_group, $id_academic_area);
            ?>
                <h1 id="lbl_<?= $catalogue->id_group ?>"><?= mb_strtoupper($catalogue->group_code) ?></h1>
                <script>
                    $(document).ready(function() {
                        Swal.close();
                        var periodo = $('#slct_period option:selected').text();
                        var criterio = $('#slct_criteria option:selected').text();

                        $('#lbl_<?= $catalogue->id_group ?>').append(' PERIODO: ' + periodo + '   | CRITERIO EVALUADO: ' + criterio);
                    });
                </script>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="tStudents">
                        <thead>
                            <tr class="sticky-header">
                                <th class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">CÓD. ALUMNO</th>
                                <th class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">NOMBRE</th>
                                <th class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">PROMEDIO</th>
                                <?php foreach ($getAssignmentsGroup as $assignment) : ?>
                                    <th title="<?= $assignment->name_subject ?> | <?= $assignment->teacher_name ?>" class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;"><?= $assignment->short_name ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="list">

                            <?php

                            $studentList = $expected_learnings->getListStudentsByIDgroup($catalogue->id_group);
                            $general_average = 0;
                            $general_sum = 0;
                            $general_count = 0;
                            foreach ($studentList as $student) : ?>
                                <tr>
                                    <td class="sticky-cell text-center"><?= $student->student_code ?></td>
                                    <td class="sticky-cell text-center"><?= mb_strtoupper($student->student_name) ?></td>
                                    <td class="sticky-cell text-center">
                                        <h4 id="avg_stud<?= $student->id_student ?>"></h4>
                                    </td>
                                    <?php
                                    $student_average = "0";
                                    $student_sum = "0";
                                    $student_count = "";
                                    foreach ($getAssignmentsGroup as $assg_groups) : ?>
                                        <?php
                                        $getMutualCriteriaAveragesEPStudent = $expected_learnings->getMutualCriteriaAveragesEPStudent($student->id_student, $assg_groups->id_assignment, $criteria, $id_period, $student->id_student);

                                        $evaluation_average = "";
                                        if (!empty($getMutualCriteriaAveragesEPStudent)) {
                                            $getMutualCriteriaAveragesEPStudent = $getMutualCriteriaAveragesEPStudent[0];
                                            $evaluation_average = $getMutualCriteriaAveragesEPStudent->grade_evaluation_criteria_teacher;
                                            if (is_numeric($evaluation_average)) {
                                                $evaluation_average = number_format($evaluation_average, 1);
                                                $student_sum = $student_sum + $evaluation_average;
                                                $student_count++;
                                            }
                                        } else {
                                            $getAssignmentsGroupCriteria = $expected_learnings->getAssignmentsGroupCriteria($assg_groups->id_assignment, $criteria, $id_period);
                                            if (empty($getAssignmentsGroupCriteria)) {
                                                $evaluation_average = "N/A";
                                            } else {
                                                $evaluation_average = "-";
                                            }
                                        }
                                        ?>
                                        <td class="sticky-cell text-center"><?= $evaluation_average ?></td>
                                    <?php endforeach;
                                    if ($student_count > 0) {
                                        $student_average = $student_sum / $student_count;
                                        $student_average = number_format($student_average, 1);
                                    } else {
                                        $student_average = "-";
                                    }
                                    ?>
                                    <script>
                                        $(document).ready(function() {
                                            Swal.close();
                                            $('#avg_stud<?= $student->id_student ?>').text('<?= $student_average ?>');
                                        });
                                    </script>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="card-body">
            <div class="table-responsive">
                <h1 class="">No hay aprendizajes esperados para este nivel académico</h1>
            </div>
        </div>
    <?php endif; ?>
    </div>