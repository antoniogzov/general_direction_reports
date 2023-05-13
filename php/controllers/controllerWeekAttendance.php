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
function getWeekAttendance()
{

    $id_group = $_POST['id_group'];
    $id_subject = $_POST['id_subject'];

    $config = new ConfigurationController;
    //$dataConfig = $config->updateEvaluationConfig($id_group, $id_subject);

    // echo json_encode($dataConfig);
}

function exportSubjectConfig()
{

    $assignment_from = $_POST['assignment_from'];
    $import_on_assignment = $_POST['import_on_assignment'];
    $config = new ConfigurationController;
    $dataConfig = $config->exportEvaluationConfig($assignment_from, $import_on_assignment);

    echo json_encode($dataConfig);
}
