<?php if (isset($_GET['id_period'])) :

    foreach ($getGroupsSubject as $group) :
        include_once dirname(__FILE__, 4) . '/models/evaluations.php';
        $evaluations        = new Evaluations;
        $aux_evaluations    = new AuxEvaluations;
        //--- --- ---//

        $getNoPeriod = $qualifications_reports->getNoPeriodByID($id_period);
        $getNoPeriod = $getNoPeriod[0];
        $no_period = $getNoPeriod->no_period;

        $getIdsLevelCombination = $qualifications_reports->getPeriodByGroup($group->id_group, $id_academic_area, $id_academic_level, $no_period);
        if (!empty($getIdsLevelCombination)) {
            $getIdsLevelCombination = $getIdsLevelCombination[0];
            $id_period_calendar2 = $getIdsLevelCombination->id_period_calendar;
            $infoPeriod = $qualifications_reports->getPeriodByID($id_period_calendar2);
        }


        $infoPeriod = $infoPeriod[0];

        $dynamicCalc = $evaluations->checkDynamicCalculationByAssg($group->id_assignment);
        $anyCriteriaOp = $evaluations->checkAnyCriteriaOperational($group->id_assignment, $id_period_calendar2);

        $extraExamEnabled = $evaluations->checkExtraexamEnabled($group->id_assignment);
        $extraExamEnabled = $extraExamEnabled->enable_extra_grade;
        //--- --- ---//
        $aux_evaluations->checkInitialStructureDataFinalGradesAssignment($group->id_assignment);
        $aux_evaluations->checkInitialStructureDataGradesPeriods($group->id_assignment, $id_period_calendar2);
        //--- --- ---//
        if ($extraExamEnabled) {
            $aux_evaluations->checkInitialStructureDataGradesExtraExam($group->id_assignment, $id_period_calendar2);
        }

        $addColDynCalc = false;
        if (!empty($dynamicCalc) || !empty($anyCriteriaOp)) {
            $addColDynCalc = true;
        }

        //--- --- ---//
        $aux_evaluations->checkInitialStructureDataGradesEvaluationsCriteria($group->id_assignment, $id_period_calendar2);
        //--- --- ---//
        $students            = $evaluations->GetStudentsFromEvaluation($group->id_assignment, $infoPeriod);
        $evaluation_criteria = $evaluations->getCriteriaFromConfigurationEvaluation($group->id_assignment, $id_period_calendar2);

        if (count($evaluation_criteria) > 0) { ?>
            <div class="card card-table-evaluations content-wrapper wide" id="content<?= $group->id_group ?>">
                <div class="card-body">
                    <h1>Grupo: <?= $group->group_code ?> </h1>
                    <br>
                    <div>
                        <!-- 
                        <?php if ($dynamicCalc) : ?>
                            <button type="button" data-toggle="tooltip" data-placement="bottom" data-link-img="<?= $dynamicCalc->operation_model_img ?>" title="Modelo de cálculo" class="btn btn-primary btn-show-mi mb-3"><i class="fas fa-image"></i></i></button>
                        <?php endif; ?> -->
                        <div class="sticky-table sticky-ltr-cells">
                            <table class="table align-items-center table-flush" id="tbl<?= $group->id_group ?>">
                                <thead class="thead-light">
                                    <tr class="sticky-header">
                                        <th class="sticky-cell p-1 td-hd-600" style="width: 10px !important; font-size: x-small !important;">Cód.</th>
                                        <th class="sticky-cell text-center p-1" style="font-size: x-small !important;">Nombre</th>
                                        <th class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE;"><b>Promedio por periodo</b></th>
                                        <?php if ($addColDynCalc) : ?>
                                            <th class="text-center font-weight-bold"><b>Promedio dinámico</b></th>
                                        <?php endif; ?>
                                        <?php if ($extraExamEnabled) : ?>
                                            <th class="text-center font-weight-bold"><b>Extraordinario</b></th>
                                        <?php endif; ?>
                                        <?php foreach ($evaluation_criteria as $criteria) : ?>
                                            <th scope="col" class="text-center" data-toggle="tooltip" data-placement="top"><?= $criteria->manual_name == ''  ? $criteria->evaluation_name : $criteria->manual_name; ?><br /><small>%<?= $criteria->percentage ?></small></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php
                                    $prom = 0;
                                    $valid_prom = 0;
                                    foreach ($students as $student) : ?>
                                        <tr>
                                            <td style="width: 10px !important; font-size: x-small !important;" class="sticky-cell p-1 td-hd-600"><?= strtoupper($student->student_code) ?></td>
                                            <td class="sticky-cell p-1" style="font-size: x-small !important;"><?= strtoupper($student->name_student) ?></td>
                                            <?php
                                            if ($evaluations->getGradePeriod($student->id_grade_period)->grade_period != "") {
                                                $prom = $prom + $evaluations->getGradePeriod($student->id_grade_period)->grade_period;
                                                $valid_prom++;
                                            } ?>
                                            <td class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE;"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period; ?></td>
                                            <?php if ($addColDynCalc) : ?>
                                                <td class="text-center"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period_calc; ?></td>
                                            <?php endif; ?>
                                            <?php if ($extraExamEnabled) : ?>
                                                <td class="text-center"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_extraordinary_examen; ?></td>
                                            <?php endif; ?>
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
                                                                <?php
                                                                $bg_color = "";
                                                                if ($criteria_student->grade_evaluation_criteria_teacher == "") {
                                                                    $bg_color =  "rgba(252, 3, 3, 0.2)";
                                                                } ?>
                                                                <td style="background-color:  <?= $bg_color; ?>"><?= $criteria_student->grade_evaluation_criteria_teacher ?></td>
                                                            <?php elseif ($criteria_conf->group_id == '2' || $criteria_conf->group_id == 2) : ?>
                                                                <?php $arr_opt_scale = explode(",", $criteria_conf->evaluation_scale); ?>
                                                                <td class="text-center">
                                                                    <?php foreach ($arr_opt_scale as $value) : ?>
                                                                        <?php if ($criteria_student->grade_evaluation_criteria_teacher == $value) : ?>
                                                                            <?= $value; ?>
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
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Promedio</td>
                                        <?php if ($valid_prom > 0) : ?>
                                            <td class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE;"><?= $valid_prom > 0 ? round($prom / $valid_prom, 1) : 0; ?></td>
                                        <?php else : ?>
                                            <td class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE;">0</td>
                                        <?php endif; ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#tbl<?= $group->id_group ?>').DataTable({
                        dom: 'Bfrtip',
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
            <div class="card card-evaluations-gathering">
            </div>
        <? } else {
        ?>
            <!-- <div class="card">
            <h1>Grupo: <?= $group->group_code ?> </h1>
                <h2 class="text-center p-4">Aún no hay una configuración para este periodo</h2>
            </div> -->
        <?php
        }
        ?>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    Swal.close();
</script>