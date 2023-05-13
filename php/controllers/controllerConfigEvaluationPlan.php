<?php

require_once '../../../general/php/models/Connection.php';
require_once '../models/modelConfigEvaluationPlan.php';
date_default_timezone_set('America/Mexico_City');
$function = $_POST['fun'];
$function();

function getEvaluationConfig()
{

    $id_criterio = $_POST['id_criterio'];

    $config = new ConfigurationController;
    $dataConfig = $config->getEvaluationConfig($id_criterio);

    echo json_encode($dataConfig);
}
function updateEvaluationConfig()
{

    $id_criterio = $_POST['id_criterio'];
    $criteria_name = $_POST['criteria_name'];
    $manual_name = $_POST['manual_name'];
    $eval_type = $_POST['eval_type'];
    $edit_percentage = $_POST['edit_percentage'];
    $affect_final_calification = $_POST['affect_final_calification'];
    $in_deadline = $_POST['in_deadline'];
    $nmb_gathering = $_POST['nmb_gathering'];
    $original_gathering_configured = $_POST['original_gathering_configured'];

    $config = new ConfigurationController;
    $dataConfig = $config->updateEvaluationConfig($id_criterio, $criteria_name, $manual_name, $eval_type, $edit_percentage, $affect_final_calification, $in_deadline, $nmb_gathering, $original_gathering_configured);

    echo json_encode($dataConfig);
}

function exportSubjectConfig()
{

    $assignment_from = $_POST['assignment_from'];
    $import_on_assignment = $_POST['import_on_assignment'];
    $config = new ConfigurationController;
    $dataConfig = $config->exportEvaluationConfig($assignment_from, $import_on_assignment);

    echo json_encode($dataConfig);
}

function deletePeriodSubjectConfig()
{

    $id_assignment = $_POST['id_assignment'];
    $id_period = $_POST['id_period'];
    $config = new ConfigurationController;
    $dataConfig = $config->deletePeriodEvaluationConfig($id_assignment, $id_period);

    echo json_encode($dataConfig);
}
