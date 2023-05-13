<!-- Modal -->
<div class="modal fade" id="infoCalificaciones" tabindex="-1" role="dialog" aria-labelledby="infoCalificacionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoCalificacionesLabel">Información General de: <?= mb_strtoupper($listStudent->name_student) ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1>Calificaciones</h1>
                <h2>Todas las calificaciones por periodo</h2>

                <?php
                /* var_dump($GetGroupsStudentSubject); */
                foreach ($GetGroupsStudentSubject as $groups) :

                    $id_area_Aca = $groups->id_academic_area;
                    if ($id_area_Aca == 1) {
                        $str_aca = "esp";
                    } else {
                        $str_aca = "heb";
                    }
                ?>
                    <hr>
                    <br>
                    <?php
                    $StudentSubjects = $archives->GetSubjectsStudent($id_student, $groups->id_academic_area, $groups->id_group);
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">

                                <div class="col-md-12">
                                    <ul class="nav nav-pills nav-pills-success nav-pills-circle mb-3" id="tabs_3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link rounded-circle  active" id="99" data-toggle="tab" href="#califGral" role="tab" aria-selected="false">
                                                <span class="nav-link-icon d-block">Total</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="card card-plain">
                                        <div class="tab-content tab-space">
                                            <div class="card card-nav-tabs">
                                                <div class="card-header card-header-warning">
                                                    <?= mb_strtoupper($groups->name_academic_area) ?> | <?= $groups->group_code ?>
                                                </div>
                                                <?php
                                                $level_combination = array();
                                                $id_level_combination = "";
                                                $periods = array();
                                                $level_combinationheb = array();
                                                $id_level_combinationheb = "";
                                                $periods_heb = array();

                                                if ($groups->id_academic_area == '1') {
                                                    $level_combination = $archives->getLevelCombinationByGroupID($groups->id_group);
                                                    $id_level_combination = $level_combination[0]->id_level_combination;
                                                    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
                                                } else {
                                                    $level_combination = $archives->getLevelCombinationByGroupIDHeb($groups->id_group);
                                                    $id_level_combination = $level_combination[0]->id_level_combination;
                                                    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);/* 
                                                    $level_combinationheb = $archives->getLevelCombinationByGroupIDHeb($groups->id_group);
                                                    $id_level_combinationheb = $level_combinationheb[0]->id_level_combination;
                                                    $periods_heb = $helpers->getAllPeriodsByLevelCombination($id_level_combinationheb); */
                                                } ?>

                                                <div class="card-body">
                                                    <div class="tab-pane fade active show" id="califGral">
                                                        <div class="table-responsive">
                                                            <table class="table align-items-center">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <?php foreach ($periods as $period) :

                                                                            $link = "/periodo_" . $period->no_period . "/" . $str_aca . "/" . "cuantitativa/" . strtoupper($listStudent->student_code) . ".pdf";
                                                                            $archivo_pdf = dirname(__DIR__, 10) . "/boletas" . $link;
                                                                            $href = "/boletas/" . $link;
                                                                        ?>
                                                                            <td>
                                                                                <?php
                                                                                if (file_exists($archivo_pdf)) : ?>
                                                                                    <a href="<?= $href ?>" target="_blank" class="btn btn-outline-danger"><i class="fas fa-file-pdf"></i></a>
                                                                                <?php else : ?>
                                                                                    <a disabled target="_blank" class="btn btn-outline-secondary"><i class="fas fa-file-pdf"></i></a>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        <?php endforeach ?>
                                                                        <th></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Materia</th>
                                                                        <th>Profesor</th>
                                                                        <?php
                                                                        $averages_period = array();
                                                                        ?>
                                                                        <?php foreach ($periods as $period) : ?>
                                                                            <?php
                                                                            $period_avg = 0;
                                                                            $valid_avg = 0;
                                                                            ?>
                                                                            <th>P. <?= $period->no_period ?></th>
                                                                        <?php endforeach ?>
                                                                        <th>Promedio Final</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="list">
                                                                    <?php

                                                                    ?>
                                                                    <?php foreach ($StudentSubjects as $subjects) : ?>
                                                                        <?php
                                                                        $subject_avg = 0;
                                                                        $valid_sbj_avg = 0;
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= $subjects->name_subject ?></td>
                                                                            <td><?= $subjects->teacher_name ?></td>

                                                                            <?php foreach ($periods as $period) : ?>
                                                                                <?php
                                                                                $studentAverages = $archives->getStudentAVGAssignmentPeriod($subjects->id_assignment, $period->id_period_calendar, $id_student, $groups->id_group);
                                                                                if (!empty($studentAverages)) {
                                                                                    $grade_period = $studentAverages[0]->grade_period;
                                                                                    $valid_avg++;
                                                                                    $period_avg = $period_avg + $grade_period;

                                                                                    $subject_avg = $subject_avg + $grade_period;
                                                                                    $valid_sbj_avg++;
                                                                                } else {
                                                                                    $grade_period = "-";
                                                                                }
                                                                                ?>
                                                                                <td>
                                                                                    <button type="button" onclick="getCriteriaDetailsStudents('<?= $id_student ?>','<?= $subjects->id_assignment ?>', '<?= $period->id_period_calendar ?>', '<?= $grade_period ?>', '<?= $groups->id_group ?>')" class="btn btn-outline-secondary"> <?= $grade_period ?> </button>
                                                                                </td>
                                                                            <?php endforeach; ?>
                                                                            <?php
                                                                            if ($subject_avg > 0 and $valid_sbj_avg > 0) {
                                                                                $avg_subject = number_format(($subject_avg / $valid_sbj_avg), 1);
                                                                            } else {
                                                                                $avg_subject  = "-";
                                                                            }
                                                                            ?>
                                                                            <td><strong><?= $avg_subject  ?></strong></td>

                                                                        <?php endforeach; ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="2">PROMEDIOS</th>

                                                                        <?php
                                                                        $avg_final = 0;
                                                                        $valid_avg = 0;
                                                                        foreach ($periods as $period) :
                                                                            $period_average = $archives->getStudentAVGPeriod($subjects->id_assignment, $period->id_period_calendar, $id_student, $groups->id_group);
                                                                            if (!empty($period_average)) {
                                                                                if ($period_average[0]->period_avg) {
                                                                                    $period_avg = $period_average[0]->period_avg;
                                                                                    $period_avg = number_format($period_avg, 1);

                                                                                    $avg_final = $avg_final + $period_avg;
                                                                                    $valid_avg++;
                                                                                } else {
                                                                                    $period_avg = "-";
                                                                                }
                                                                            } else {
                                                                                $period_avg = "-";
                                                                            }
                                                                        ?>
                                                                            <th><strong><?= $period_avg ?></strong></th>
                                                                        <?php endforeach ?>
                                                                        <?php
                                                                        if ($avg_final > 0 and $valid_avg > 0) {
                                                                            $final_avg = number_format(($avg_final / $valid_avg), 1);
                                                                        } else {
                                                                            $final_avg = "-";
                                                                        }
                                                                        ?>
                                                                        <th><strong><?= $final_avg ?></strong></th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>