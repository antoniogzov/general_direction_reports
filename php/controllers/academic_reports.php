<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getAcademicGradeByArea()
{
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    $stmt = "SELECT DISTINCT degree, id_academic_level, alg.id_level_grade
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
        WHERE rma.no_teacher = '$_SESSION[colab]' ORDER BY alg.id_level_grade";

    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getGroups
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene materias para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getSectionAndCampus()
{
    $grants = $_SESSION['grantsITEQ'];
    $id_level_grade = $_POST['id_level_grade'];

    $groups = new Groups;

    $stmt = "SELECT DISTINCT gps.id_section, cam.id_campus, campus_name,
        CASE
            WHEN id_section = 1 THEN CONCAT(campus_name, ' - ', 'HOMBRES')
            WHEN id_section = 2 THEN CONCAT(campus_name, ' - ', 'MUJERES')
            WHEN id_section = 3 THEN CONCAT(campus_name, ' - ', 'MIXTO')
            END  AS seccion_campus
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cam ON gps.id_campus = cam.id_campus
        WHERE gps.id_level_grade = '$id_level_grade' AND rma.no_teacher = '$_SESSION[colab]'";

    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getGroups
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene materias para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getIdsLevelCombination()
{
    $grants = $_SESSION['grantsITEQ'];
    $id_level_grade = $_POST['id_level_grade'];

    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_section = $_POST['id_section'];
    $id_campus = $_POST['id_campus'];

    $groups = new Groups;

    $stmt = "SELECT * FROM school_control_ykt.level_combinations
    WHERE id_academic_area = '$id_academic_area'
    AND id_academic_level = '$id_academic_level'
    AND id_section = '$id_section'
    AND id_campus = '$id_campus'";

    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getGroups
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene materias para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getPeriodsByIdLevelCombination()
{
    $grants = $_SESSION['grantsITEQ'];
    $id_level_combination = $_POST['id_level_combination'];

    $groups = new Groups;

    $stmt = "SELECT * FROM iteach_grades_quantitatives.period_calendar
    WHERE id_level_combination = '$id_level_combination'";

    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getGroups
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene materias para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
