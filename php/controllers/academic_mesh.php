<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getAcademicLevelsByAcademicArea()
{
    $id_academic_area = $_POST['id_academic_area'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {

        $stmt = "SELECT * FROM 

        (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, al.academic_level, acdlvldg.id_academic_level
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, al.academic_level, acdlvldg.id_academic_level
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1
        ORDER BY u.academic_level
        ";

    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.id_subject = '$id_subject' AND asg.no_teacher = '$_SESSION[colab]'";
    }

    $getAcademicLevels = $groups->getGroupFromTeachers($stmt);

    if (!empty($getAcademicLevels)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getAcademicLevels
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

function getTeacherAssignments()
{
    $id_teacher = $_POST['id_teacher'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_academic_area = $_POST['id_academic_area'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.name_subject, gps.group_code, colab.no_colaborador, 
        CASE
        WHEN colab.no_colaborador = 0 THEN 'SIN ASIGNAR'
        ELSE CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) 
        END AS teacher_name
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN colaboradores_ykt.colaboradores as colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = aclg.id_academic_level
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE asg.no_teacher ='$id_teacher' AND al.id_academic_level = '$id_academic_level' AND aca.id_academic_area = '$id_academic_area' order by group_code";
    } else if ($grants & 4) {
        /*  $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.id_subject = '$id_subject' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }
    
    $getTeacherAssignments = $groups->getGroupFromTeachers($stmt);

    if (!empty($getTeacherAssignments)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getTeacherAssignments
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

function getTeacherAssignmentsAttReport()
{
    $id_teacher = $_POST['id_teacher'];
    $id_academic_level = $_POST['id_academic_level'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.name_subject, gps.group_code, colab.no_colaborador, 
        CASE
        WHEN colab.no_colaborador = 0 THEN 'SIN ASIGNAR'
        ELSE CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) 
        END AS teacher_name
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN colaboradores_ykt.colaboradores as colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = aclg.id_academic_level
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE asg.no_teacher ='$id_teacher' AND al.id_academic_level = '$id_academic_level'  order by group_code";
    } else if ($grants & 4) {
        /*  $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.id_subject = '$id_subject' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }
    
    $getTeacherAssignments = $groups->getGroupFromTeachers($stmt);

    if (!empty($getTeacherAssignments)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getTeacherAssignments
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

