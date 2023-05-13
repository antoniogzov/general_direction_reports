<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();


function getMutualCriteria()
{

    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_level_grade = $_POST['id_level_grade'];
    $id_period = $_POST['id_period'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT es.*, groups.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        UNION
        SELECT  es.*, gps.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_level_grade = $id_level_grade AND id_evaluation_source !=1 ";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "<strong>Descripción de aprendizaje esperado:</strong> <br><br>";
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}

function getExportableSubjects()
{

    $id_assignment = $_POST['id_assignment'];
    $id_period = $_POST['id_period'];
    $id_academic_area = $_POST['id_academic_area'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, sbj.name_subject, ep.id_evaluation_plan, sbj.id_subject
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination AND percal.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level

        
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
        UNION
        SELECT  rel_coord_aca.no_teacher, sbj.id_academic_area, sbj.name_subject, ep.id_evaluation_plan, sbj.id_subject
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_evaluation_plan IS NULL  ORDER BY name_subject";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'exportableSubjects' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function getExportableGroups()
{

    $id_subject = $_POST['id_subject'];
    $id_period = $_POST['id_period'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT rel_coord_aca.no_teacher, ep.id_evaluation_plan,  groups.group_code, groups.id_group
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination AND percal.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = $id_subject
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level

        
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
        UNION
        SELECT  rel_coord_aca.no_teacher, ep.id_evaluation_plan, gps.group_code, gps.id_group
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group   AND asgm.id_subject = $id_subject
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_evaluation_plan IS NULL  ORDER BY group_code";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'exportableSubjects' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function getExportablePeriods()
{

    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_level_grade = $_POST['id_level_grade'];
    $id_period = $_POST['id_period'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT es.*, groups.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        UNION
        SELECT  es.*, gps.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_level_grade = $id_level_grade AND id_evaluation_source !=1 ";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "<strong>Descripción de aprendizaje esperado:</strong> <br><br>";
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function exportToAnotherAssignment()
{

    $id_subject = $_POST['id_subject'];
    $id_period = $_POST['id_period'];
    $id_academic_area = $_POST['id_academic_area'];
    $id_assignment = $_POST['id_assignment'];
    $id_group = $_POST['id_group'];

    $groups = new Groups;
    $attendance = new Attendance;

    $id_assignment_destiny = 0;
    $sqlGetssignmentDestiny = "SELECT * FROM school_control_ykt.assignments WHERE id_subject = $id_subject AND id_group = $id_group";
    $assignmentDestiny = $groups->getGroupFromTeachers($sqlGetssignmentDestiny);
    if (!empty($assignmentDestiny)) {
        $id_assignment_destiny = $assignmentDestiny[0]->id_assignment;
    }

    if ($id_assignment_destiny != 0) {
        $checkIfEPExist = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan WHERE id_assignment = $id_assignment_destiny AND id_period_calendar = $id_period";
        $EPExist = $groups->getGroupFromTeachers($checkIfEPExist);
        if (empty($EPExist)) {



            /* INSERTAR CATALOGO DE AE  */
            $stmt = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan AS ep
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON ep.id_evaluation_source = es.id_evaluation_source
        WHERE ep.id_assignment = $id_assignment AND ep.id_period_calendar = $id_period";
            $evaluationPlanOrigin = $groups->getGroupFromTeachers($stmt);
            if (!empty($evaluationPlanOrigin)) {
                $countEvalPlan = count($evaluationPlanOrigin);
                $count = 0;
                foreach ($evaluationPlanOrigin as $evalplan_origin) {
                    if ($evalplan_origin->gathering > 0) {

                        $sqlCheckIfExists = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan 
                        WHERE id_evaluation_source = '$evalplan_origin->id_evaluation_source' AND manual_name = '$evalplan_origin->manual_name'
                         AND id_period_calendar = '$id_period' AND id_assignment = '$id_assignment_destiny'";
                        $CheckIfExists = $groups->getGroupFromTeachers($sqlCheckIfExists);

                        if (empty($CheckIfExists)) {

                            $sqlInsertNewEvalPlan = "INSERT INTO iteach_grades_quantitatives.evaluation_plan
                        (
                            id_evaluation_source, 
                            id_period_calendar,
                            evaluation_type_id,
                            formulation_operations_id,
                            id_assignment,
                            value_input_type,
                            percentage,
                            manual_name,
                            gathering,
                            affects_evaluation,
                            deadline,
                            no_teacher_created_evp,
                            date_created_evp
                        )
                        VALUES(
                            '$evalplan_origin->id_evaluation_source',
                            '$id_period',
                            '$evalplan_origin->evaluation_type_id',
                            '$evalplan_origin->formulation_operations_id',
                            '$id_assignment_destiny',
                            '$evalplan_origin->value_input_type',
                            '$evalplan_origin->percentage',
                            '$evalplan_origin->manual_name',
                            '$evalplan_origin->gathering',
                            '$evalplan_origin->affects_evaluation',
                            '$evalplan_origin->deadline',
                            '$_SESSION[colab]',
                            NOW()
                        )
                        ";
                            if ($attendance->saveAttendance($sqlInsertNewEvalPlan)) {
                                $id_new_evalplan = $attendance->getLastId();
                                $sqlGetGathering = "SELECT * FROM iteach_grades_quantitatives.conf_grade_gathering
                            WHERE id_evaluation_plan = $evalplan_origin->id_evaluation_plan";
                                $gathering = $groups->getGroupFromTeachers($sqlGetGathering);
                                if (!empty($gathering)) {
                                    foreach ($gathering as $gather) {
                                        $sqlInsertNewGathering = "INSERT INTO iteach_grades_quantitatives.conf_grade_gathering
                                      (
                                        id_evaluation_plan,
                                        name_item
                                      )
                                      VALUES(
                                        '$id_new_evalplan',
                                        '$gather->name_item'
                                      )
                                      ";
                                        $attendance->saveAttendance($sqlInsertNewGathering);
                                    }
                                    $count++;
                                } else {
                                    $count++;
                                }
                            }
                        }
                    } else {
                        $sqlCheckIfExists = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan 
                        WHERE id_evaluation_source = '$evalplan_origin->id_evaluation_source' AND manual_name = '$evalplan_origin->manual_name'
                         AND id_period_calendar = '$id_period' AND id_assignment = '$id_assignment_destiny'";
                        $CheckIfExists = $groups->getGroupFromTeachers($sqlCheckIfExists);

                        if (empty($CheckIfExists)) {
                            $sqlInsertNewEvalPlan = "INSERT INTO iteach_grades_quantitatives.evaluation_plan
                        (
                            id_evaluation_source, 
                            id_period_calendar,
                            evaluation_type_id,
                            formulation_operations_id,
                            id_assignment,
                            value_input_type,
                            percentage,
                            manual_name,
                            gathering,
                            affects_evaluation,
                            deadline,
                            no_teacher_created_evp,
                            date_created_evp
                        )
                        VALUES(
                            '$evalplan_origin->id_evaluation_source',
                            '$id_period',
                            '$evalplan_origin->evaluation_type_id',
                            '$evalplan_origin->formulation_operations_id',
                            '$id_assignment_destiny',
                            '$evalplan_origin->value_input_type',
                            '$evalplan_origin->percentage',
                            '$evalplan_origin->manual_name',
                            '$evalplan_origin->gathering',
                            '$evalplan_origin->affects_evaluation',
                            '$evalplan_origin->deadline',
                            '$_SESSION[colab]',
                            NOW()
                        )
                        ";
                            if ($insertNewEvalPlan = $attendance->saveAttendance($sqlInsertNewEvalPlan)) {
                                $count++;
                            }
                        }
                    }
                }


                if ($count == $countEvalPlan) {

                    $data = array(
                        'response' => true,
                        'message' => 'Se ha exportado el plan de evaluación exitosamente'
                    );
                } else {
                    $data = array(
                        'response' => false,
                        'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
                    );
                }


                /////////////////
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'No hay un plan de evaluación para este periodo'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ya existe un plan de evaluación para este periodo'
            );
            echo json_encode($data);
            return;
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se pudo obtener el id de la asignación'
        );
    }


    echo json_encode($data);
}

function get_percentage()
{

    $periodo = $_POST['periodo'];
    $assignment = $_POST['assignment'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sqlGetPeriodPercentage = "SELECT SUM(percentage) as suma_percentage FROM iteach_grades_quantitatives.evaluation_plan WHERE id_period_calendar = '$periodo' AND id_assignment = '$assignment'";
    $GetPeriodPercentage = $groups->getGroupFromTeachers($sqlGetPeriodPercentage);
    if (!empty($GetPeriodPercentage)) {
        $suma_percentage = $GetPeriodPercentage[0]->suma_percentage;

        $data = array(
            'response' => false,
            'suma_percentage' => $suma_percentage
        );
    } else {

        $data = array(
            'response' => false
        );
    }


    echo json_encode($data);
}

function createEvalCriteria()
{

    $evaluation = $_POST['id_evaluation'];
    $type = $_POST['id_metodo'];
    $percentage = $_POST['percentage'];
    $id_evaluation_type = $_POST['id_evaluation_type'];
    $fechaFin = $_POST['fechaFin'];
    $formulation_operations_id = 1;

    if (!empty($_POST['add_name'])) {
        $AditionalName = $_POST['add_name'];
    } else {
        $AditionalName = '';
    }

    $subcriterios = $_POST['subcriterios'];
    $affects_evaluation = $_POST['check_afectar_calificacion'];

    if ($affects_evaluation == 0) {
        $percentage = 0;
    }

    $gathering = 0;
    if ($subcriterios != "" && $subcriterios != "0" && $subcriterios != 0) {
        $gathering = 1;
    }

    //--- --- ---//
    if ($evaluation == 41) {
        $id_evaluation_type = 5;
        $formulation_operations_id = 4;
        $percentage = 0;
        $type = 1;
    }


    $period = $_POST['periodo'];
    $assignment = $_POST['assignment'];
    $no_teacher_created_evp = $_SESSION['colab'];

    $periodo = $_POST['periodo'];
    $assignment = $_POST['assignment'];

    $groups = new Groups;
    $attendance = new Attendance;
    $sqlCheckIfExists = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan 
    WHERE id_evaluation_source = '$evaluation' AND manual_name = '$AditionalName' AND id_period_calendar = '$period' AND id_assignment = '$assignment'";
    $CheckIfExists = $groups->getGroupFromTeachers($sqlCheckIfExists);

    if (empty($CheckIfExists)) {

        $createEvalCriteria = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (
        id_evaluation_plan,
        id_evaluation_source,
        id_period_calendar,
        evaluation_type_id,
        formulation_operations_id,
        id_assignment,
        value_input_type,
        percentage,
        manual_name,
        gathering,
        affects_evaluation,
        deadline,
        no_teacher_created_evp,
        date_created_evp
        )
        VALUES (
        null,
        '$evaluation', 
        '$period', 
        '$id_evaluation_type',
        '$formulation_operations_id',
        '$assignment', 
        '$type', 
        '$percentage', 
        '$AditionalName', 
        '$gathering', 
        '$affects_evaluation', 
        '$fechaFin', 
        '$no_teacher_created_evp', 
        NOW())";

        $createEvalCriteria = $attendance->saveAttendance($createEvalCriteria);
        if (!empty($createEvalCriteria)) {
            $contador = 0;
            $last_id = $attendance->getLastId();
            //$last_id ="38";
            if ($subcriterios != "" && $subcriterios != "0" && $subcriterios != 0) {
                for ($i = 1; $i <= $subcriterios; $i++) {
                    if ($AditionalName != "") {
                        $items_nm = $AditionalName . " " . $i;
                    } else if ($evaluation != "") {
                        $sql_ev_source = "SELECT evaluation_name FROM iteach_grades_quantitatives.evaluation_source WHERE id_evaluation_source = $evaluation;";
                        $res_eval = $groups->getGroupFromTeachers($sql_ev_source);
                        foreach ($res_eval as $eval_nm) {
                            $ev_nm = $eval_nm->evaluation_name;
                            $items_nm = $ev_nm . " " . $i;
                        }
                    }
                    $sql_items = "INSERT INTO iteach_grades_quantitatives.conf_grade_gathering VALUES(
                        null,
                        '$last_id',
                        '$items_nm' )";

                    if ($attendance->saveAttendance($sql_items)) {
                        $contador++;
                    }
                }
            }

            if ($contador > 0) {
                $datos[] = array(
                    'resultado' => 'correcto',
                    'mensaje' => 'Se agregó el nuevo criterio de evaluación (con subcriterios)!!'
                );
            } else {
                $datos[] = array(
                    'resultado' => 'correcto',
                    'mensaje' => 'Se agregó el nuevo criterio de evaluación!!'
                );
            }
        } else {

            $datos[] = array('resultado' => 'error');
        }
    } else {

        $datos[] = array('resultado' => 'error');
    }


    echo json_encode($datos);
}


function deleteEvalCriteria()

{
    $id = $_POST['id_eliminar'];
    $colaborator = $_POST['colaborator'];

    $groups = new Groups;
    $attendance = new Attendance;



    $result_grade_gathering = $groups->getGroupFromTeachers("SELECT * FROM iteach_grades_quantitatives.`grade_gathering` 
        WHERE `id_evaluation_plan` = $id");

    $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
    fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- ELIMINACIÓN DE CRITERIOS DE EVALUACIÓN ---- N° COLAB. ---- " . $colaborator . "\n" . "ID ELIMINADO -> " . $id) or die("Error escribiendo en el archivo");
    fclose($logFile);
    if (!empty($result_grade_gathering)) {
        $row_cnt_c = count($result_grade_gathering);
        if ($row_cnt_c > 0) {
            $delete_criteria = "DELETE FROM iteach_grades_quantitatives.`grade_gathering` WHERE `id_evaluation_plan` = '$id'";
            $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
            fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- ELIMINACIÓN DE CRITERIOS DE EVALUACIÓN ---- N° COLAB. ---- " . $colaborator . "\n" . "CRITERIO ELIMIADO -> " . $delete_criteria) or die("Error escribiendo en el archivo");
            fclose($logFile);
            $res_delete_c = $attendance->saveAttendance($delete_criteria);
        }
    }

    $result_criteria = $groups->getGroupFromTeachers("SELECT * FROM iteach_grades_quantitatives.`grades_evaluation_criteria` 
WHERE `id_evaluation_plan` = $id");

    if (!empty($result_criteria)) {
        $row_cnt_c = count($result_criteria);
        if ($row_cnt_c > 0) {
            $delete_criteria = "DELETE FROM iteach_grades_quantitatives.`grades_evaluation_criteria` 
    WHERE `id_evaluation_plan` = $id";
            $res_delete_c = $attendance->saveAttendance($delete_criteria);
            $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
            fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- ELIMINACIÓN DE CRITERIOS DE grades_evaluation_criteria ---- N° COLAB. ---- " . $colaborator . "\n" . "SQL ELIMIADO -> " . $delete_criteria) or die("Error escribiendo en el archivo");
            fclose($logFile);
        }
    }

    $result_gathering = $groups->getGroupFromTeachers("SELECT * FROM iteach_grades_quantitatives.`conf_grade_gathering` 
        WHERE iteach_grades_quantitatives.`conf_grade_gathering`.`id_evaluation_plan` = $id");


    if (!empty($result_gathering)) {
        $row_cnt_g = count($result_gathering);
        if ($row_cnt_g > 0) {
            $delete_gathering = "DELETE FROM iteach_grades_quantitatives.`conf_grade_gathering` 
    WHERE iteach_grades_quantitatives.`conf_grade_gathering`.`id_evaluation_plan` = $id";
            $res_delete_g = $attendance->saveAttendance($delete_gathering);
            $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
            fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- ELIMINACIÓN DE CRITERIOS DE conf_grade_gathering ---- N° COLAB. ---- " . $colaborator . "\n" . "SQL ELIMIADO -> " . $delete_gathering) or die("Error escribiendo en el archivo");
            fclose($logFile);
        }
    }

    $sql_ev_plan = "DELETE FROM iteach_grades_quantitatives.evaluation_plan WHERE id_evaluation_plan = $id";
    $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
    fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- ELIMINACIÓN DE CRITERIOS DE evaluation_plan ---- N° COLAB. ---- " . $colaborator . "\n" . "SQL ELIMIADO -> " . $sql_ev_plan) or die("Error escribiendo en el archivo");
    fclose($logFile);
    if ($delete_ev_plan = $attendance->saveAttendance($sql_ev_plan)) {
        $datos[] = array(
            'resultado' => "correcto",
            'mensaje' => "Se eliminó el criterio de evaluación correctamente."
        );
    } else {
        $datos[] = array(
            'resultado' => "error",
            'mensaje' => "Ocurrió un error al intentar eliminar el criterio de evaluación.",
        );
    }


    echo json_encode($datos);
}

function internalExport()
{


    $groups = new Groups;
    $attendance = new Attendance;

    $period_from = $_POST['period_from'];
    $import_on_period = $_POST['import_on_period'];
    $id_assignment = $_POST['id_assignment'];
    $colaborator = $_POST['colaborator'];
    //$id_assignment=1312;
    //$period_from=39;
    //$import_on_period="40,41";
    $valores = "";


    $import_on_period = substr($import_on_period, 0, -1);
    $arr_periodos = explode(",", $import_on_period);

    $recorrer_periodos = count($arr_periodos);

    for ($i = 0; $i < $recorrer_periodos; $i++) {
        $id_destino = $arr_periodos[$i];

        $sql = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan WHERE id_period_calendar = '$period_from' AND id_assignment='$id_assignment'";
        $result = $groups->getGroupFromTeachers($sql);
        $logFile = fopen("../../log_iteach.txt", 'a') or die("Error creando archivo");
        fwrite($logFile, "\n" . date("d/m/Y H:i:s") . "--- EXPORTAR CRITERIOS DE EVALUACIÓN ---- N° COLAB. ---- " . $colaborator . "\n" . $sql . "\n" . "ID PERIODO DESTINO -> " . $import_on_period) or die("Error escribiendo en el archivo");
        fclose($logFile);

        foreach ($result as $row) {
            $sql = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan
              WHERE id_evaluation_source = '$row->id_evaluation_source'
               AND manual_name = '$row->manual_name' AND id_period_calendar = '$id_destino' AND id_assignment = '$row->id_assignment'";
            $result = $groups->getGroupFromTeachers($sql);

            $id_evaluation_plan = $row->id_evaluation_plan;

            $valores = $valores . " '" . $row->id_evaluation_source . "',";
            $valores = $valores . " '" . $id_destino . "',";
            $valores = $valores . " '" . $row->evaluation_type_id . "',";
            $valores = $valores . " '" . $row->id_assignment . "',";
            $valores = $valores . " '" . $row->value_input_type . "',";
            $valores = $valores . " '" . $row->percentage . "',";
            $valores = $valores . " '" . $row->manual_name . "',";
            $valores = $valores . " '" . $row->gathering . "',";
            $valores = $valores . " '" . $row->affects_evaluation . "',";
            $valores = $valores . " '" . $row->deadline . "',";
            $valores = $valores . " '" . $_SESSION['colab'] . "',";
            $valores = substr($valores, 0, -1);

            $export = "INSERT INTO iteach_grades_quantitatives.evaluation_plan (
        id_evaluation_source,
         id_period_calendar,
         evaluation_type_id,
         id_assignment,
         value_input_type,
         percentage,
         manual_name,
         gathering,
         affects_evaluation,
         deadline,
         no_teacher_created_evp,
         date_created_evp) 
        VALUES ($valores,
        NOW())";
        
            if ($attendance->saveAttendance($export)) {
                $last_id = $attendance->getLastId();
                $sql_count = "SELECT * FROM iteach_grades_quantitatives.conf_grade_gathering WHERE id_evaluation_plan = '$id_evaluation_plan'";
                $result_count = $groups->getGroupFromTeachers($sql_count);

                if (!empty($result_count)) {
                    foreach ($result_count as $row_count) {
                        $item = $row_count->name_item;
                        $copy_items = "INSERT INTO iteach_grades_quantitatives.conf_grade_gathering VALUES(
                    null,
                    '$last_id',
                    '$item'
                )";
                        $attendance->saveAttendance($copy_items);
                    }
                }

                $datos[] = array(
                    'resultado' => 'correcto',
                    'mensaje' => 'Se exportó correctamente la configuración!!'
                );
            } else {
                $datos[] = array(
                    'resultado' => 'error',
                    'mensaje' => 'Ocurrió un error al exportar la configuración!!'
                );
            }

            $valores = "";
        }
    }
    echo json_encode($datos);
}

function getSubcriterios()
{
    $id_criterio = $_POST['id_criterio'];

    $groups = new Groups;
    $attendance = new Attendance;

    $items = array();
    $sql = "SELECT * FROM iteach_grades_quantitatives.conf_grade_gathering WHERE id_evaluation_plan = $id_criterio";
    $result = $groups->getGroupFromTeachers($sql);
    foreach ($result as $row) {
        $id_subcriterio = $row->id_conf_grade_gathering;
        $id_ev_plan = $row->id_evaluation_plan;
        $nombre = $row->name_item;
        $registros[] = array(
            'id_subcriterio' => $id_subcriterio,
            'item' => $nombre,
            'id_ep' => $id_ev_plan
        );
    }
    $datos[] = array(
        'resultado' => 'correcto',
        'registros' => $registros,
        'mensaje' => 'Se ubtuvieron los sub-criterios de evaluación.'
    );


    echo json_encode($datos);
}

function updateSubcriterios()
{

    $groups = new Groups;
    $attendance = new Attendance;

    $array_names = $_POST['array_names'];
    $id_evaluation_plan = $_POST['id_criterio'];
    $name_item = "";
    $errors = false;

    foreach ($array_names as $value) {
        if (!empty($value['new_name'])) {
            $name_item = $value['new_name'];
        }

        if ($name_item != "") {
            $id_conf_grade_gathering = $value['id_input'];
            $sql = "UPDATE iteach_grades_quantitatives.conf_grade_gathering SET name_item = '$name_item' WHERE id_evaluation_plan = '$id_evaluation_plan' AND id_conf_grade_gathering = '$id_conf_grade_gathering'";
            $attendance->saveAttendance($sql);
        }
    }

    if (!$errors) {
        $datos[] = array(
            'resultado' => 'correcto',
            'mensaje' => 'Se actualizarón los títulos de los subcriterios.'
        );
    } else {
        $datos[] = array('resultado' => 'error');
    }

    echo json_encode($datos);
}
