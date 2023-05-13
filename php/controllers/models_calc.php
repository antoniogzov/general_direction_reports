<?php
include dirname(__FILE__, 2) . '/models/models_calculation.php';
require_once dirname(__FILE__, 2) . '/models/helpers.php';

date_default_timezone_set('America/Mexico_City');
if(isset($_POST['mod'])){
    $function = $_POST['mod'];
    $function();
}

function verifyExistingModelsConfig($id_assignment, $id_period_calendar){

    $models = new ModelCalculations;
    $existingConf = $models->getConfiguredModels($id_assignment, $id_period_calendar);

    return $existingConf;
}

function getCalculationmodels(){

    $models = new ModelCalculations;
    $modelsCalc = $models->getModelsCalculation();

    return $modelsCalc;
}

function getInfoModel(){
    $operation_model_id = $_POST['operation_model_id'];
    $id_assignment      = $_POST['id_assignment'];

    $helpers = new Helpers;
    $infoAssg = $helpers->getInfoSubjectAndGroupByIdAssignment($id_assignment);
    $name_subject = $infoAssg->name_subject;

    $models_calc = new ModelCalculations;
    $infoModelC = $models_calc->getInfoModelCalc($operation_model_id);
    $nameModel = $infoModelC->name_operation_model;

    $response = array(
        'response' => true,
        'subject' => $name_subject,
        'name_model' => $nameModel,
    );

    echo json_encode($response);
}

//--- Evaluación para secundaria por periodo 1---//
function addDynamicEvaluationSecondaryMen1(){

    session_start();

    $operation_model_id = $_POST['operation_model_id'];
    $id_assignment      = $_POST['id_assignment'];

    $helpers = new Helpers;
    $level_combinations = $helpers->getIdLevelCombinationByAssignment($id_assignment);

    $periods = array();

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }

    $models = new ModelCalculations;

    $configurations;
    $additional_settings;

    //--- --- ---//
    $icon = 'success';
    $message = '';
    $response = true;

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Portafolio //
    $conf['id_evaluation_source'] = 34;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 15;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Portafolio 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 15;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 20;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Proyecto //
    $conf['id_evaluation_source'] = 4;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 40;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 8;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Proyecto 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 35;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Trabajo //
    $conf['id_evaluation_source'] = 39;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 45;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 15;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Trabajo 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 50;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 50;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 50;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 50;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Disciplina //
    $conf['id_evaluation_source'] = 37;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 2;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 2;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Asistencia //
    $conf['id_evaluation_source'] = 38;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 3;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 3;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- AGREGAMOS LA ASOCACIÓN DEL MODELO CON LA ASIGNATURA ---//
    $sql = "INSERT INTO iteach_dynamic_calculations.operation_model_assignment (operation_model_id, id_assignment) VALUES (:operation_model_id, :id_assignment)";

    $data = [':operation_model_id' => $operation_model_id, ':id_assignment' => $id_assignment];

    $op_model_assg_id = $models->applymodel($sql, $data);


    if($op_model_assg_id != 0){
        //--- AGREGAMOS LOS CRITERIOS PRINCIPALES EN PLAN DE EVALUACIÓN ---//
        $sql_evp = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (id_evaluation_source, id_period_calendar, evaluation_type_id, id_assignment, value_input_type, percentage, gathering, affects_evaluation, formulation_operations_id, no_teacher_created_evp, date_created_evp) VALUES (:id_evaluation_source, :id_period_calendar, :evaluation_type_id, :id_assignment, :value_input_type, :percentage, :gathering, :affects_evaluation, :formulation_operations_id, :no_teacher_created_evp, :date_created_evp)";

        $sql_rellocation = "INSERT INTO iteach_dynamic_calculations.reallocation_percentages (op_model_assg_id,id_evaluation_plan, condition_grade, percentage) VALUES (:op_model_assg_id, :id_evaluation_plan, :condition_grade, :percentage)";

        foreach($periods AS $period){
            $id_period_calendar = $period->id_period_calendar;
            $no_period = $period->no_period;

            //--- VERIFICAMOS SI ESTA MATERIA EN ESTE PERIODO YA TIENE ALGÚN CRITERIO CONFIGURADO ---//
            $anyCriteriaConfAssg = $models->checkAnyCriteriaConfAssg($id_assignment, $id_period_calendar);

            if((int) $anyCriteriaConfAssg <= 0){
                foreach($configurations AS $data){
                    $reallocation_percentages = array();
                    $evaluation_plan = array();
                    $no_gathering = 0;
                    foreach($data AS $key => $dt){
                        if($key != 'additional'){
                            if($key != 'no_gathering'){
                                $evaluation_plan[$key] = $dt;
                            } else {
                                $no_gathering = $dt;
                            }
                        } else {
                            $reallocation_percentages = $dt;
                        }
                    }

                    $evaluation_plan['id_period_calendar'] = $id_period_calendar;

                    $id_evaluation_plan = $models->applymodel($sql_evp, $evaluation_plan);
                    //--- --- ---//
                    if($id_evaluation_plan != 0){
                        //--- AGREGAMOS LOS GATHERING ---//
                        if($no_gathering > 0){
                            $models->addGatherings($no_gathering, $id_evaluation_plan);
                        }
                        //--- AGREGAMOS LOS VALORS FALTANTES PARA RELLOCATION---//
                        foreach($reallocation_percentages AS $dr){
                            $dr['op_model_assg_id'] = $op_model_assg_id;
                            $dr['id_evaluation_plan'] = $id_evaluation_plan;
                            $reallocation_percentages_id = $models->applymodel($sql_rellocation, $dr);
                        }
                        //--- --- ---//
                   }
                    //--- --- ---//
                    $evaluation_plan = array();
                    //--- --- ---//
                }

            } else {
                $message .= 'El siguiente periodo ya tiene criterios configurados, por lo que NO se pudo agregar el modelo de cálculo: Periodo - ' . $no_period . '<br/><br/>';
                $icon = 'warning';
            }
        }
    } else {
        $response = false;
        $message = 'Ocurrió un error al intentar realizar la configuración, intentelo nuevamente porfavor';
        $icon = 'error';
    }

    if($message == ''){
        $message = 'Se ha guardado el modelo de cáculo de manera correcta en todas los periodos';
    }

    //--- --- ---//
    $data_response = array(
        'response' => $response,
        'icon'      => $icon,
        'message' => $message
    );
    //--- --- ---//

    echo json_encode($data_response);
}

//--- Evaluación para secundaria por periodo 2---//
function addDynamicEvaluationSecondaryMen2(){

    session_start();

    $operation_model_id = $_POST['operation_model_id'];
    $id_assignment      = $_POST['id_assignment'];

    $helpers = new Helpers;
    $level_combinations = $helpers->getIdLevelCombinationByAssignment($id_assignment);

    $periods = array();

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }

    $models = new ModelCalculations;

    $configurations;
    $additional_settings;

    //--- --- ---//
    $icon = 'success';
    $message = '';
    $response = true;

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Portafolio //
    $conf['id_evaluation_source'] = 34;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 10;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Portafolio 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Proyecto //
    $conf['id_evaluation_source'] = 4;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 35;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 8;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Proyecto 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 20;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Trabajo //
    $conf['id_evaluation_source'] = 39;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 40;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 15;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Trabajo 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 40;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 35;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 35;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Exámenes //
    $conf['id_evaluation_source'] = 3;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 15;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 3;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Exámenes 1//
        $conf_add['condition_grade'] = ':gradeP: >= 8 && :gradeP: <= 8.9';
        $conf_add['percentage'] = 20;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Exámenes 2//
        $conf_add['condition_grade'] = ':gradeP: >= 7.5 && :gradeP: <= 7.9';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Exámenes 3//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Exámenes 4//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 40;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();


    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Disciplina //
    $conf['id_evaluation_source'] = 37;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 2;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 2;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Asistencia //
    $conf['id_evaluation_source'] = 38;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 3;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 3;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- AGREGAMOS LA ASOCACIÓN DEL MODELO CON LA ASIGNATURA ---//
    $sql = "INSERT INTO iteach_dynamic_calculations.operation_model_assignment (operation_model_id, id_assignment) VALUES (:operation_model_id, :id_assignment)";

    $data = [':operation_model_id' => $operation_model_id, ':id_assignment' => $id_assignment];

    $op_model_assg_id = $models->applymodel($sql, $data);


    if($op_model_assg_id != 0){
        //--- AGREGAMOS LOS CRITERIOS PRINCIPALES EN PLAN DE EVALUACIÓN ---//
        $sql_evp = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (id_evaluation_source, id_period_calendar, evaluation_type_id, id_assignment, value_input_type, percentage, gathering, affects_evaluation, formulation_operations_id, no_teacher_created_evp, date_created_evp) VALUES (:id_evaluation_source, :id_period_calendar, :evaluation_type_id, :id_assignment, :value_input_type, :percentage, :gathering, :affects_evaluation, :formulation_operations_id, :no_teacher_created_evp, :date_created_evp)";

        $sql_rellocation = "INSERT INTO iteach_dynamic_calculations.reallocation_percentages (op_model_assg_id,id_evaluation_plan, condition_grade, percentage) VALUES (:op_model_assg_id, :id_evaluation_plan, :condition_grade, :percentage)";

        foreach($periods AS $period){
            $id_period_calendar = $period->id_period_calendar;
            $no_period = $period->no_period;

            //--- VERIFICAMOS SI ESTA MATERIA EN ESTE PERIODO YA TIENE ALGÚN CRITERIO CONFIGURADO ---//
            $anyCriteriaConfAssg = $models->checkAnyCriteriaConfAssg($id_assignment, $id_period_calendar);

            if((int) $anyCriteriaConfAssg <= 0){
                foreach($configurations AS $data){
                    $reallocation_percentages = array();
                    $evaluation_plan = array();
                    $no_gathering = 0;
                    foreach($data AS $key => $dt){
                        if($key != 'additional'){
                            if($key != 'no_gathering'){
                                $evaluation_plan[$key] = $dt;
                            } else {
                                $no_gathering = $dt;
                            }
                        } else {
                            $reallocation_percentages = $dt;
                        }
                    }

                    $evaluation_plan['id_period_calendar'] = $id_period_calendar;

                    $id_evaluation_plan = $models->applymodel($sql_evp, $evaluation_plan);
                    //--- --- ---//
                    if($id_evaluation_plan != 0){
                        //--- AGREGAMOS LOS GATHERING ---//
                        if($no_gathering > 0){
                            $models->addGatherings($no_gathering, $id_evaluation_plan);
                        }
                        //--- AGREGAMOS LOS VALORS FALTANTES PARA RELLOCATION---//
                        foreach($reallocation_percentages AS $dr){
                            $dr['op_model_assg_id'] = $op_model_assg_id;
                            $dr['id_evaluation_plan'] = $id_evaluation_plan;
                            $reallocation_percentages_id = $models->applymodel($sql_rellocation, $dr);
                        }
                        //--- --- ---//
                   }
                    //--- --- ---//
                    $evaluation_plan = array();
                    //--- --- ---//
                }

            } else {
                $message .= 'El siguiente periodo ya tiene criterios configurados, por lo que NO se pudo agregar el modelo de cálculo: Periodo - ' . $no_period . '<br/><br/>';
                $icon = 'warning';
            }
        }
    } else {
        $response = false;
        $message = 'Ocurrió un error al intentar realizar la configuración, intentelo nuevamente porfavor';
        $icon = 'error';
    }

    if($message == ''){
        $message = 'Se ha guardado el modelo de cáculo de manera correcta en todas los periodos';
    }

    //--- --- ---//
    $data_response = array(
        'response' => $response,
        'icon'      => $icon,
        'message' => $message
    );
    //--- --- ---//

    echo json_encode($data_response);
}

//--- Evaluación para secundaria por periodo mujeres 1---//
function addDynamicEvaluationSecondaryWoman1(){

    session_start();

    $operation_model_id = $_POST['operation_model_id'];
    $id_assignment      = $_POST['id_assignment'];

    $helpers = new Helpers;
    $level_combinations = $helpers->getIdLevelCombinationByAssignment($id_assignment);

    $periods = array();

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }

    $models = new ModelCalculations;

    $configurations;
    $additional_settings;

    //--- --- ---//
    $icon = 'success';
    $message = '';
    $response = true;

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Portafolio //
    $conf['id_evaluation_source'] = 34;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 20;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Portafolio 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio //
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Proyecto //
    $conf['id_evaluation_source'] = 4;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 20;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 8;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Proyecto 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Proyectos y trabajos //
    $conf['id_evaluation_source'] = 35;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 60;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 20;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Proyectos y trabajos 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 50;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyectos y trabajos 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 40;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Disciplina //
    $conf['id_evaluation_source'] = 37;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 2;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 2;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Asistencia //
    $conf['id_evaluation_source'] = 38;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 3;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 3;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- AGREGAMOS LA ASOCACIÓN DEL MODELO CON LA ASIGNATURA ---//
    $sql = "INSERT INTO iteach_dynamic_calculations.operation_model_assignment (operation_model_id, id_assignment) VALUES (:operation_model_id, :id_assignment)";

    $data = [':operation_model_id' => $operation_model_id, ':id_assignment' => $id_assignment];

    $op_model_assg_id = $models->applymodel($sql, $data);


    if($op_model_assg_id != 0){
        //--- AGREGAMOS LOS CRITERIOS PRINCIPALES EN PLAN DE EVALUACIÓN ---//
        $sql_evp = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (id_evaluation_source, id_period_calendar, evaluation_type_id, id_assignment, value_input_type, percentage, gathering, affects_evaluation, formulation_operations_id, no_teacher_created_evp, date_created_evp) VALUES (:id_evaluation_source, :id_period_calendar, :evaluation_type_id, :id_assignment, :value_input_type, :percentage, :gathering, :affects_evaluation, :formulation_operations_id, :no_teacher_created_evp, :date_created_evp)";

        $sql_rellocation = "INSERT INTO iteach_dynamic_calculations.reallocation_percentages (op_model_assg_id,id_evaluation_plan, condition_grade, percentage) VALUES (:op_model_assg_id, :id_evaluation_plan, :condition_grade, :percentage)";

        foreach($periods AS $period){
            $id_period_calendar = $period->id_period_calendar;
            $no_period = $period->no_period;

            //--- VERIFICAMOS SI ESTA MATERIA EN ESTE PERIODO YA TIENE ALGÚN CRITERIO CONFIGURADO ---//
            $anyCriteriaConfAssg = $models->checkAnyCriteriaConfAssg($id_assignment, $id_period_calendar);

            if((int) $anyCriteriaConfAssg <= 0){
                foreach($configurations AS $data){
                    $reallocation_percentages = array();
                    $evaluation_plan = array();
                    $no_gathering = 0;
                    foreach($data AS $key => $dt){
                        if($key != 'additional'){
                            if($key != 'no_gathering'){
                                $evaluation_plan[$key] = $dt;
                            } else {
                                $no_gathering = $dt;
                            }
                        } else {
                            $reallocation_percentages = $dt;
                        }
                    }

                    $evaluation_plan['id_period_calendar'] = $id_period_calendar;

                    $id_evaluation_plan = $models->applymodel($sql_evp, $evaluation_plan);
                    //--- --- ---//
                    if($id_evaluation_plan != 0){
                        //--- AGREGAMOS LOS GATHERING ---//
                        if($no_gathering > 0){
                            $models->addGatherings($no_gathering, $id_evaluation_plan);
                        }
                        //--- AGREGAMOS LOS VALORS FALTANTES PARA RELLOCATION---//
                        foreach($reallocation_percentages AS $dr){
                            $dr['op_model_assg_id'] = $op_model_assg_id;
                            $dr['id_evaluation_plan'] = $id_evaluation_plan;
                            $reallocation_percentages_id = $models->applymodel($sql_rellocation, $dr);
                        }
                        //--- --- ---//
                   }
                    //--- --- ---//
                    $evaluation_plan = array();
                    //--- --- ---//
                }

            } else {
                $message .= 'El siguiente periodo ya tiene criterios configurados, por lo que NO se pudo agregar el modelo de cálculo: Periodo - ' . $no_period . '<br/><br/>';
                $icon = 'warning';
            }
        }
    } else {
        $response = false;
        $message = 'Ocurrió un error al intentar realizar la configuración, intentelo nuevamente porfavor';
        $icon = 'error';
    }

    if($message == ''){
        $message = 'Se ha guardado el modelo de cáculo de manera correcta en todas los periodos';
    }

    //--- --- ---//
    $data_response = array(
        'response' => $response,
        'icon'      => $icon,
        'message' => $message
    );
    //--- --- ---//

    echo json_encode($data_response);
}

//--- Evaluación para secundaria por periodo mujeres 2---//
function addDynamicEvaluationSecondaryWoman2(){

    session_start();

    $operation_model_id = $_POST['operation_model_id'];
    $id_assignment      = $_POST['id_assignment'];

    $helpers = new Helpers;
    $level_combinations = $helpers->getIdLevelCombinationByAssignment($id_assignment);

    $periods = array();

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }

    $models = new ModelCalculations;

    $configurations;
    $additional_settings;

    //--- --- ---//
    $icon = 'success';
    $message = '';
    $response = true;

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Portafolio //
    $conf['id_evaluation_source'] = 34;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 40;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Portafolio 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 40;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Portafolio 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Proyecto //
    $conf['id_evaluation_source'] = 4;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 30;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 8;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Proyecto 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 20;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Proyecto 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 25;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Trabajo //
    $conf['id_evaluation_source'] = 39;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 10;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 15;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Trabajo 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Trabajo 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 10;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Exámenes //
    $conf['id_evaluation_source'] = 3;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 1;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 20;
    $conf['affects_evaluation'] = 1;
    $conf['formulation_operations_id'] = 1;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 1;
    $conf['no_gathering'] = 3;
        //--- CONFIGURACIONES SECUNDARIAS ---//
        // Exámenes 1//
        $conf_add['condition_grade'] = ':gradeP: >= 6 && :gradeP: <= 7.4';
        $conf_add['percentage'] = 30;
        $configurations_add[] = $conf_add;
        $conf_add = array();
        // Exámenes 2//
        $conf_add['condition_grade'] = ':gradeP: >= 0 && :gradeP: <= 5.9';
        $conf_add['percentage'] = 35;
        $configurations_add[] = $conf_add;
        $conf_add = array();

    $conf['additional'] = $configurations_add;
    $configurations_add = array();

    $configurations[] = $conf;
    $conf = array();


    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Disciplina //
    $conf['id_evaluation_source'] = 37;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 2;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 2;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- CONFIGURACIÓN PRINCIPAL ---//
    // Asistencia //
    $conf['id_evaluation_source'] = 38;
    //$conf['id_period_calendar'] = $id_period_calendar;
    $conf['evaluation_type_id'] = 3;
    $conf['id_assignment'] = $id_assignment;
    $conf['value_input_type'] = 0;
    $conf['percentage'] = 0;
    $conf['affects_evaluation'] = 0;
    $conf['formulation_operations_id'] = 3;
    $conf['no_teacher_created_evp'] = $_SESSION['colab'];;
    $conf['date_created_evp'] = date('Y-m-d H:i:s');
    $conf['gathering'] = 0;
    $conf['no_gathering'] = 0;
    $configurations[] = $conf;
    $conf = array();

    //--- AGREGAMOS LA ASOCACIÓN DEL MODELO CON LA ASIGNATURA ---//
    $sql = "INSERT INTO iteach_dynamic_calculations.operation_model_assignment (operation_model_id, id_assignment) VALUES (:operation_model_id, :id_assignment)";

    $data = [':operation_model_id' => $operation_model_id, ':id_assignment' => $id_assignment];

    $op_model_assg_id = $models->applymodel($sql, $data);


    if($op_model_assg_id != 0){
        //--- AGREGAMOS LOS CRITERIOS PRINCIPALES EN PLAN DE EVALUACIÓN ---//
        $sql_evp = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (id_evaluation_source, id_period_calendar, evaluation_type_id, id_assignment, value_input_type, percentage, gathering, affects_evaluation, formulation_operations_id, no_teacher_created_evp, date_created_evp) VALUES (:id_evaluation_source, :id_period_calendar, :evaluation_type_id, :id_assignment, :value_input_type, :percentage, :gathering, :affects_evaluation, :formulation_operations_id, :no_teacher_created_evp, :date_created_evp)";

        $sql_rellocation = "INSERT INTO iteach_dynamic_calculations.reallocation_percentages (op_model_assg_id,id_evaluation_plan, condition_grade, percentage) VALUES (:op_model_assg_id, :id_evaluation_plan, :condition_grade, :percentage)";

        foreach($periods AS $period){
            $id_period_calendar = $period->id_period_calendar;
            $no_period = $period->no_period;

            //--- VERIFICAMOS SI ESTA MATERIA EN ESTE PERIODO YA TIENE ALGÚN CRITERIO CONFIGURADO ---//
            $anyCriteriaConfAssg = $models->checkAnyCriteriaConfAssg($id_assignment, $id_period_calendar);

            if((int) $anyCriteriaConfAssg <= 0){
                foreach($configurations AS $data){
                    $reallocation_percentages = array();
                    $evaluation_plan = array();
                    $no_gathering = 0;
                    foreach($data AS $key => $dt){
                        if($key != 'additional'){
                            if($key != 'no_gathering'){
                                $evaluation_plan[$key] = $dt;
                            } else {
                                $no_gathering = $dt;
                            }
                        } else {
                            $reallocation_percentages = $dt;
                        }
                    }

                    $evaluation_plan['id_period_calendar'] = $id_period_calendar;

                    $id_evaluation_plan = $models->applymodel($sql_evp, $evaluation_plan);
                    //--- --- ---//
                    if($id_evaluation_plan != 0){
                        //--- AGREGAMOS LOS GATHERING ---//
                        if($no_gathering > 0){
                            $models->addGatherings($no_gathering, $id_evaluation_plan);
                        }
                        //--- AGREGAMOS LOS VALORS FALTANTES PARA RELLOCATION---//
                        foreach($reallocation_percentages AS $dr){
                            $dr['op_model_assg_id'] = $op_model_assg_id;
                            $dr['id_evaluation_plan'] = $id_evaluation_plan;
                            $reallocation_percentages_id = $models->applymodel($sql_rellocation, $dr);
                        }
                        //--- --- ---//
                   }
                    //--- --- ---//
                    $evaluation_plan = array();
                    //--- --- ---//
                }

            } else {
                $message .= 'El siguiente periodo ya tiene criterios configurados, por lo que NO se pudo agregar el modelo de cálculo: Periodo - ' . $no_period . '<br/><br/>';
                $icon = 'warning';
            }
        }
    } else {
        $response = false;
        $message = 'Ocurrió un error al intentar realizar la configuración, intentelo nuevamente porfavor';
        $icon = 'error';
    }

    if($message == ''){
        $message = 'Se ha guardado el modelo de cáculo de manera correcta en todas los periodos';
    }

    //--- --- ---//
    $data_response = array(
        'response' => $response,
        'icon'      => $icon,
        'message' => $message
    );
    //--- --- ---//

    echo json_encode($data_response);
}