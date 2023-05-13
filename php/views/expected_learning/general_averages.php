<?php
$evaluations         = new Evaluations;
$aux_evaluations         = new AuxEvaluations;

$aux_evaluations->calculateFinalAverageByAssignment($id_assignment);
$data_averages = $evaluations->getAllGradeStudentsByPeriod($id_assignment);

$allPeriods = array();

if(!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assignment))){
    $id_level_combination = $id_level_combination->id_level_combination;
    $allPeriods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
}
?>
<?php if (!empty($allPeriods)): ?>
<div class="card card-table-evaluations">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-items-center table-flush" id="tGradesStudents">
                <thead class="thead-light">
                    <tr>
                        <th>Cód. alumno</th>
                        <th>Nombre</th>
                        <th class="text-center font-weight-bold">Promedio final</th>
                        <?php foreach ($allPeriods as $period): ?>
                            <th scope="col" class="text-center">Periodo: <?=$period->no_period ?></th>
                        <?php endforeach;?>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php foreach ($data_averages as $data): ?>
                        <tr>
                            <td><?=strtoupper($data->student_code)?></td>
                            <td><?=ucfirst($data->student_name) ?></td>
                            <td class="text-center"><?= $evaluations->getFinalGradeAssignmentStudent($id_assignment, $data->id_inscription)->final_grade ?></td>
                            <?php foreach ($allPeriods as $period): ?>
                                <?= $period_find = false; ?>
                                <?php foreach ($data->grades as $gradesStudents): ?>
                                    <?php if ($gradesStudents->id_period_calendar == $period->id_period_calendar): ?>
                                        <?php $period_find = true; ?>
                                        <td class="text-center"><?= $gradesStudents->grade_period ?></td>
                                    <?php endif; ?>
                                <?php endforeach;?>
                                <?php if (!$period_find): ?>
                                    <td class="text-center"></td>
                                <?php endif; ?>
                            <?php endforeach;?>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>