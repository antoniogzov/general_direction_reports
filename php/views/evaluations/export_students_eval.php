<?php
$array_level_combinations = array();

if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assignment))) {
    $id_level_combination = $id_level_combination->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
}

$id_period_calendar = '';
if (isset($_GET['id_period_calendar'])) {
    $id_period_calendar = $_GET['id_period_calendar'];
}

if (!empty($periods)) {
    $infoPeriod = $helpers->getPeriodByID($id_period_calendar);
    if (!empty($infoPeriod)) {
        $infoPeriod = $infoPeriod[0];
        $allow_editing_grades = $infoPeriod->allow_editing_grades;
        $grade_closing_date = $infoPeriod->grade_closing_date;
        $arr_grade_closing_date = explode(" ", $grade_closing_date);
        $arr_date_grade_closing_date = explode("-", $arr_grade_closing_date[0]);
        $date_grade_closing_date = $arr_date_grade_closing_date[2] . "/" . $arr_date_grade_closing_date[1] . "/" . $arr_date_grade_closing_date[0];

        $today_date = date('Y-m-d H:i:s');
        $editable = 1;
        if ($allow_editing_grades == 0) {
            if ($today_date > $grade_closing_date) {
                $editable = 0;
            }
        }
        $td_class = '';
        $select_prop = 'disabled';
        if ($editable == 1) {
            $td_class = 'contenteditable="true"';
            $select_prop = '';
        }
    }
}
if (isset($_GET['id_period_calendar'])) {
    //--- --- ---//
    $evaluations        = new Evaluations;
    $aux_evaluations    = new AuxEvaluations;
    //--- --- ---//
    $dynamicCalc = $evaluations->checkDynamicCalculationByAssg($id_assignment);
    $anyCriteriaOp = $evaluations->checkAnyCriteriaOperational($id_assignment, $id_period_calendar);

    $extraExamEnabled = $evaluations->checkExtraexamEnabled($id_assignment);
    $extraExamEnabled = $extraExamEnabled->enable_extra_grade;
    //--- --- ---//
    $aux_evaluations->checkInitialStructureDataFinalGradesAssignment($id_assignment);
    $aux_evaluations->checkInitialStructureDataGradesPeriods($id_assignment, $id_period_calendar);
    //--- --- ---//
    if ($extraExamEnabled) {
        $aux_evaluations->checkInitialStructureDataGradesExtraExam($id_assignment, $id_period_calendar);
    }

    $addColDynCalc = false;
    if (!empty($dynamicCalc) || !empty($anyCriteriaOp)) {
        $addColDynCalc = true;
    }
    //--- --- ---//
    $aux_evaluations->checkInitialStructureDataGradesEvaluationsCriteria($id_assignment, $id_period_calendar);
    //--- --- ---//
    $students            = $evaluations->GetStudentsFromEvaluation($id_assignment, $infoPeriod);
    $evaluation_criteria = $evaluations->getCriteriaFromConfigurationEvaluation($id_assignment, $id_period_calendar);

    //--- --- ---//
    /*print_r($students);
    echo '<br/><br/>';
    print_r($evaluation_criteria);*/
    if (count($evaluation_criteria) > 0) { ?>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
        <div class="card card-table-evaluations">
            <div class="card-body">
                <div>
                    <?php if ($dynamicCalc) : ?>
                        <button type="button" data-toggle="tooltip" data-placement="bottom" data-link-img="<?= $dynamicCalc->operation_model_img ?>" title="Modelo de cálculo" class="btn btn-primary btn-show-mi mb-3"><i class="fas fa-image"></i></i></button>
                    <?php endif; ?>
                    <div class="sticky-table sticky-ltr-cells">
                        <input type="hidden" id="input_editable" value="<?= $editable ?>">
                        <input type="hidden" id="grade_closing_date" value="<?= $date_grade_closing_date ?>">
                        <table class="table align-items-center table-flush" id="tStudents">
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
                                        <th scope="col" class="text-center" data-toggle="tooltip" data-placement="top" title="Sólo admite caracteres: <?= $criteria->evaluation_scale ?>"><?= $criteria->manual_name == ''  ? $criteria->evaluation_name : $criteria->manual_name; ?><br /><small>%<?= $criteria->percentage ?></small></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($students as $student) :

                                    $css_tdGP = "";
                                    $checkIfExistCommentary = $evaluations->checkIfExistCommentary($student->id_grade_period);
                                    if (!empty($checkIfExistCommentary)) {
                                        $checkedCommentary = $checkIfExistCommentary->checked;
                                        if ($checkedCommentary == 1) {
                                            $css_tdGP = "border: 2px solid #0330fc;";
                                        } else {
                                            $css_tdGP = "border: 2px solid red;";
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td style="width: 10px !important; font-size: x-small !important;" class="sticky-cell p-1 td-hd-600"><?= mb_strtoupper($student->student_code) ?></td>
                                        <td class="sticky-cell p-1" style="font-size: x-small !important;"><?= mb_strtoupper($student->name_student) ?></td>
                                        <td class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE; <?= $css_tdGP ?>"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period; ?></td>
                                        <?php if ($addColDynCalc) : ?>
                                            <td class="text-center td-grade-dynCalc"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period_calc; ?></td>
                                        <?php endif; ?>
                                        <?php if ($extraExamEnabled) : ?>
                                            <td id="<?= $evaluations->getGradePeriod($student->id_grade_period)->id_extraordinary_exams; ?>" class="text-center td-grade-extra" onkeyup="evaluate_character('rank', '1-10', this, event)"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_extraordinary_examen; ?></td>
                                        <?php endif; ?>
                                        <?php foreach ($evaluation_criteria as $criteria_conf) : ?>
                                            <?php $criteria_found = false; ?>
                                            <?php foreach ($student->evaluation_criteria_student as $criteria_student) : ?>
                                                <?php if ($criteria_conf->id_evaluation_plan == $criteria_student->id_evaluation_plan) : ?>
                                                    <?php $criteria_found = true; ?>

                                                    <?php if ($criteria_conf->gathering) : ?>
                                                        <td id="<?= $criteria_student->id_grades_evaluation_criteria ?>" class="text-center td-grade-evaluation td-gathering" style="background-color: #FFF2EF" onClick="clk_td_gathering(this, <?= $criteria_student->id_grades_evaluation_criteria ?>, <?= $criteria_student->is_averaged ?>, '<?= $criteria_student->classification ?>', '<?= $criteria_student->evaluation_scale ?>', '<?= $editable ?>')">

                                                            <?= $criteria_student->is_averaged == '1' ? $criteria_student->grade_evaluation_criteria_teacher : 'L'; ?>

                                                        </td>

                                                    <?php else : ?>

                                                        <?php if ($criteria_conf->group_id == '1' || $criteria_conf->group_id == 1) : ?>
                                                            <td data-ides="<?= $criteria_conf->id_evaluation_source ?>" class="text-center td-grade-evaluation" id="<?= $criteria_student->id_grades_evaluation_criteria ?>" onkeyup="evaluate_character('<?= $criteria_student->classification ?>', '<?= $criteria_student->evaluation_scale ?>', this, event)" data-is-averaged="<?= $criteria_student->is_averaged ?>"><?= $criteria_student->grade_evaluation_criteria_teacher ?></td>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var name_archive = 'CALIFICACIONES <?= $info_header_module['some_text'] ?> | <?= mb_strtoupper($_SESSION['user_name']) ?>';
            $('#tStudents').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'pdfHtml5',
                    title: name_archive,
                    orientation: 'landscape',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                    }
                }],
                searching: false,
                paging: false,
                info: false
            });
        </script>
        <div class="card card-evaluations-gathering">
        </div>
    <?php } else {
    ?>
        <div class="card">
            <h2 class="text-center p-4">Aún no hay una configuración para este periodo</h2>
        </div>
<?php
    }
}
?>