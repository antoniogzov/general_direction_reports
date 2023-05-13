<?php

include 'php/models/evaluations.php';
include 'card_select_pda.php';


$array_level_combinations = array();
if (isset($_GET['id_assignment'])) {
    if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assignment))) {
        $id_level_combination = $id_level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}


$id_period_calendar = '';
if (isset($_GET['id_period'])) {
    $id_period_calendar = $_GET['id_period'];
}
?>
<?php
if (isset($_GET['id_period'])) {
    //--- --- ---//
    $evaluations         = new Evaluations;
    $aux_evaluations         = new AuxEvaluations;
    //--- --- ---//
    $aux_evaluations->checkInitialStructureDataFinalGradesAssignment($id_assignment);
    $aux_evaluations->checkInitialStructureDataGradesPeriods($id_assignment, $id_period_calendar);
    $aux_evaluations->checkInitialStructureDataGradesEvaluationsCriteria($id_assignment, $id_period_calendar);
    //--- --- ---//
    $infoPeriod = $helpers->getPeriodByID($id_period_calendar);
    $infoPeriod = $infoPeriod[0];
    $students            = $evaluations->GetStudentsFromEvaluation($id_assignment, $infoPeriod);
    $evaluation_criteria = $evaluations->getCriteriaFromConfigurationEvaluation($id_assignment, $id_period_calendar);
    //--- --- ---//
    /*print_r($students);
    echo '<br/><br/>';
    print_r($evaluation_criteria);*/
    if (count($evaluation_criteria) > 0) { ?>
        <div class="card card-table-evaluations">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="tStudents">
                        <thead class="thead-light">
                            <tr>
                                <th>Cód. alumno</th>
                                <th>Nombre</th>
                                <?php foreach ($evaluation_criteria as $criteria) : ?>
                                    <th scope="col" class="text-center" data-toggle="tooltip" data-placement="top" title="Sólo admite caracteres: <?= $criteria->evaluation_scale ?>"><?= $criteria->manual_name == ''  ? $criteria->evaluation_name : $criteria->manual_name; ?><br /><small>%<?= $criteria->percentage ?></small></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php foreach ($students as $student) : ?>
                                <tr>
                                    <td style="width: 10px"><?= strtoupper($student->student_code) ?></td>
                                    <td><?= ucfirst($student->name_student) ?></td>
                                    <?php foreach ($evaluation_criteria as $criteria_conf) : ?>
                                        <?php $criteria_found = false; ?>
                                        <?php foreach ($student->evaluation_criteria_student as $criteria_student) : ?>
                                            <?php if ($criteria_conf->id_evaluation_plan == $criteria_student->id_evaluation_plan) : ?>
                                                <?php $criteria_found = true; ?>
                                                <?php if ($criteria_conf->gathering) : ?>
                                                    <td class="text-center" style="background-color: #FFF2EF">
                                                        <?= $criteria_student->is_averaged == '1' ? $criteria_student->grade_evaluation_criteria_teacher : 'L'; ?>
                                                    </td>

                                                <?php else : ?>

                                                    <?php if ($criteria_conf->group_id == '1' || $criteria_conf->group_id == 1) : ?>
                                                        <td class="text-center td-grade-evaluation" id="<?= $criteria_student->id_grades_evaluation_criteria ?>"><?= $criteria_student->grade_evaluation_criteria_teacher ?></td>
                                                    <?php elseif ($criteria_conf->group_id == '2' || $criteria_conf->group_id == 2) : ?>
                                                        <?php $arr_opt_scale = explode(",", $criteria_conf->evaluation_scale); ?>
                                                        <td class="text-center">
                                                            <?php foreach ($arr_opt_scale as $value) : ?>
                                                                <?php if ($criteria_student->grade_evaluation_criteria_teacher == $value) : ?><?= $value; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                        </td>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if (!$criteria_found) : ?>
                                            <td class="text-center"></td>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card card-evaluations-gathering">
        </div>
    <?php } else {
    ?>
        <div class="card">
            <h2 class="text-center p-4">Aún no hay una configuración para este periodo</h2>
        </div>

<?php }
} ?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/func_pda_report.js"></script>