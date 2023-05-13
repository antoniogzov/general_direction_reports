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

        if (($allow_editing_grades == 0) && ($today_date > $grade_closing_date)) {
            $editable = 0;
        }
        $td_class = '';
        $select_prop = 'disabled';
        if ($editable == 1) {
            $td_class = 'contenteditable="true"';
            $select_prop = '';
        }
    }
}

include 'card_select_periods.php';
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
    if (count($evaluation_criteria) > 0) {
        include 'show_students_ev_plan.php';
    } else {
?>
        <div class="card">
            <h2 class="text-center p-4">Aún no hay una configuración para este periodo</h2>
        </div>
<?php
    }
}
?>