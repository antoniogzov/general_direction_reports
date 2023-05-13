<?php if (!empty($listStudent)) : ?>
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
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">DESGLOSE DETALLADO DE EVALUACIÓN</h3>
                    <h4 id="group_period" class="ml-2"></h4>
                    <h5 class="heading mb-0 ml-2">Alumnos: <?= $num_students ?></h5>
                    <br>
                    <br>


                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

                    <?php foreach ($Assingments as $assignment) : ?>

                        <br>
                        <br>
                        <div class="table-responsive">
                            <?php $getAssignmentCritera = $groupsReports->getAssignmentCritera($id_period, $assignment->id_assignment); ?>
                            <h2><?= $assignment->name_subject ?></h2>
                            <table class="table align-items-center table-flush" id="table<?= $assignment->id_assignment ?>">
                                <thead class="thead-light">
                                    <tr>
                                        <th>CÓD. ALUMNO</th>
                                        <th>NOMBRE</th>
                                        <th>PROM. P.</th>
                                        <th>PROM. D.</th>
                                        <th>EXTRA.</th>
                                        <?php foreach ($getAssignmentCritera as $criteria) : ?>

                                            <?php if ($criteria->id_evaluation_source == 34) : ?>
                                                <th style="background-color: #5081f2 !important; color: #fff !important;"><?= mb_strtoupper($criteria->criteria_name) ?><br><?= ($criteria->percentage) ?>%</th>
                                                <?php $getAssignmentSubCriteraAE = $groupsReports->getAssignmentSubCriteraAE($id_period, $assignment->id_assignment, $criteria->id_evaluation_plan); ?>
                                                <?php foreach ($getAssignmentSubCriteraAE as $sub_criteria_ae) : ?>
                                                    <?php $short_title = substr($sub_criteria_ae->short_description, 0, 20); ?>
                                                    <th style="background-color: #94b4ff !important; color: #fff !important;"><?= mb_strtoupper($short_title) ?>.</th>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <?php if ($criteria->gathering == 1) : ?>
                                                    <th style="background-color: #5081f2 !important; color: #fff !important;"><?= mb_strtoupper($criteria->criteria_name) ?><br><?= ($criteria->percentage) ?>%</th>
                                                    <?php $getAssignmentSubCritera = $groupsReports->getAssignmentSubCritera($id_period, $assignment->id_assignment, $criteria->id_evaluation_plan); ?>
                                                    <?php foreach ($getAssignmentSubCritera as $sub_criteria) : ?>
                                                        <th style="background-color: #94b4ff !important; color: #fff !important;"><?= mb_strtoupper($sub_criteria->name_item) ?></th>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <th style="background-color: #168016 !important; color: #fff !important;"><?= mb_strtoupper($criteria->criteria_name) ?><br><?= ($criteria->percentage) ?>%</th>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($listStudent as $student) : ?>
                                        <?php
                                        $getPeriodAVG = $groupsReports->getPeriodAVG($id_period, $assignment->id_assignment, $student->id_student);
                                        $getPeriodAVGDyn = $groupsReports->getPeriodAVGDyn($id_period, $assignment->id_assignment, $student->id_student);
                                        $color_grade = "";
                                        $color_grade_dyn = "";
                                        $color_grade_extr = "";
                                        if (!empty($getPeriodAVG)) {
                                            $grade_period = $getPeriodAVG[0]->grade_period;
                                            $grade_extraordinary_examen = $getPeriodAVG[0]->grade_extraordinary_examen;

                                            if ($grade_period < 6) {
                                                $color_grade = "color: #cc0c02 !important;";
                                            } else if ($grade_period == 10) {
                                                $color_grade = "color: #02ba0b !important;";
                                            }

                                            if ($grade_extraordinary_examen < 6) {
                                                $color_grade_extr = "color: #cc0c02 !important;";
                                            } else if ($grade_extraordinary_examen == 10) {
                                                $color_grade_extr = "color: #02ba0b !important;";
                                            }
                                        } else {
                                            $grade_period = "-";
                                            $grade_extraordinary_examen = "-";
                                        }

                                        if (!empty($getPeriodAVGDyn)) {
                                            $grade_period_calc = $getPeriodAVGDyn[0]->grade_period_calc;


                                            if ($grade_period_calc < 6) {
                                                $color_grade_dyn = "color: #cc0c02 !important;";
                                            } else if ($grade_period_calc == 10) {
                                                $color_grade_dyn = "color: #02ba0b !important;";
                                            }
                                        } else {
                                            $grade_period_calc = "-";
                                        }

                                        ?>
                                        <tr>
                                            <td><?= mb_strtoupper($student->student_code) ?></td>
                                            <td><?= mb_strtoupper($student->name_student) ?></td>
                                            <td style="background-color: #c9c7c7 !important; <?= $color_grade ?>"><strong><?= $grade_period ?></strong></td>
                                            <td style="background-color: #c9c7c7 !important; <?= $color_grade_dyn ?>"><strong><?= $grade_period_calc ?></strong></td>
                                            <td style="background-color: #c9c7c7 !important; <?= $color_grade_extr ?>"><strong><?= $grade_extraordinary_examen ?></strong></td>

                                            <?php foreach ($getAssignmentCritera as $criteria) : ?>
                                                <?php

                                                $getCriteriaGrade = $groupsReports->getCriteriaGrade($id_period, $assignment->id_assignment, $student->id_student, $criteria->id_evaluation_plan);
                                                if (!empty($getCriteriaGrade)) {
                                                    $id_grades_evaluation_criteria = $getCriteriaGrade[0]->id_grades_evaluation_criteria;
                                                    if ($criteria->value_input_type == 0) {
                                                        $grade_criteria = $getCriteriaGrade[0]->grade_evaluation_criteria_teacher;
                                                    } else {
                                                        $grade_criteria = $getCriteriaGrade[0]->grade_evaluation_criteria_system;
                                                    }
                                                } else {
                                                    $grade_criteria = "-";
                                                }
                                                ?>

                                                <td><strong><?= $grade_criteria ?></strong></td>
                                                <?php if ($criteria->id_evaluation_source == 34) : ?>
                                                    <?php $getAssignmentSubCriteraAE = $groupsReports->getAssignmentSubCriteraAE($id_period, $assignment->id_assignment, $criteria->id_evaluation_plan); ?>
                                                    <?php foreach ($getAssignmentSubCriteraAE as $sub_criteria_ae) : ?>
                                                        <?php
                                                        $color_td = "";
                                                        $getAssignmentSubCriteraAE = $groupsReports->getAssignmentSubCriteraAEGrade($id_period, $sub_criteria_ae->id_expected_learning_catalog, $student->id_student);
                                                        if (!empty($getAssignmentSubCriteraAE)) {

                                                            $teacher_evidence_quailification = $getAssignmentSubCriteraAE[0]->teacher_evidence_quailification;
                                                            if ($teacher_evidence_quailification == "") {
                                                                $color_td = "background-color: #ffbdbd !important";
                                                                $teacher_evidence_quailification = "-";
                                                            }
                                                        } else {
                                                            $teacher_evidence_quailification = "-";
                                                            $color_td = "background-color: #ffbdbd !important";
                                                        }
                                                        ?>
                                                        <td style="<?= $color_td ?>"><?= ($teacher_evidence_quailification) ?></td>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <?php if ($criteria->gathering == 1) : ?>
                                                        <?php $getAssignmentSubCritera = $groupsReports->getAssignmentSubCritera($id_period, $assignment->id_assignment, $criteria->id_evaluation_plan); ?>
                                                        <?php foreach ($getAssignmentSubCritera as $sub_criteria) :

                                                            $color_td = "";
                                                            $getSubCriteriaGrade = $groupsReports->getSubCriteriaGrade($id_grades_evaluation_criteria, $criteria->id_evaluation_plan, $sub_criteria->id_conf_grade_gathering);
                                                            if (!empty($getSubCriteriaGrade)) {
                                                                $grade_item = $getSubCriteriaGrade[0]->grade_item;

                                                                if ($grade_item == "") {
                                                                    $color_td = "background-color: #ffbdbd !important";
                                                                    $grade_item = "-";
                                                                }
                                                            } else {
                                                                $grade_item = "-";
                                                                $color_td = "background-color: #ffbdbd !important";
                                                            }
                                                        ?>
                                                            <td style="<?= $color_td ?>"><?= ($grade_item) ?></td>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <br>
                            <br>
                        </div>

                        <script>
                            var name_group = $("#id_group option:selected").text();
                            var no_period = $("#id_period option:selected").text();
                            $(document).ready(function() {
                                $('#table<?= $assignment->id_assignment ?>').DataTable({
                                    dom: 'Bfrtip',
                                    "ordering": false,
                                    "paging": false,
                                    buttons: [{
                                        extend: 'excelHtml5',
                                        filename: 'Reporte de subcriterios | <?= $assignment->name_subject ?> | ' + name_group +' | Periodo '+no_period
                                    }, ]
                                });
                            });
                        </script>
                    <?php endforeach; ?>

                    <br>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>