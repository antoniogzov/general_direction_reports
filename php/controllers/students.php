<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';
include '../models/students.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getGroups()
{
    $id_subject = $_POST['id_subject'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN iteach_academic.relationship_managers_assignments AS mnass ON asg.id_assignment = mnass.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.id_subject = '$id_subject' AND mnass.no_teacher = '$_SESSION[colab]'";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.id_subject = '$id_subject' AND asg.no_teacher = '$_SESSION[colab]'";
    }

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

function getGroupByAcademicArea()
{
    $id_academic = $_POST['id_academic'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {

        $stmt = "SELECT * FROM 

        (SELECT assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, gps.id_group, gps.group_code, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4
        ORDER BY group_code ASC
        ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'";
    }

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
function getGroupByAcademicAreaPDA()
{
    $id_academic = $_POST['id_academic'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN iteach_academic.relationship_managers_assignments AS mnass ON asg.id_assignment = mnass.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND mnass.no_teacher = '$_SESSION[colab]' AND (sbj.id_subject=417 OR sbj.id_subject= 416)";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'";
    }

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
function getStudentInfo()
{
    $id_attendance_record = $_POST['id_attendance_record'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name, iat.incident, iat.incident_id
        FROM attendance_records.attendance_record AS atr
        INNER JOIN  school_control_ykt.students AS student ON student.id_student = atr.id_student
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        WHERE atr.id_attendance_record = '$id_attendance_record'";
    } else if ($grants & 4) {
        /* $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }

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

function UpdateWeekAttendanceStudents()
{
    $id_attendance_record = $_POST['id_attendance_record'];
    $attend = $_POST['attend'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "UPDATE attendance_records.attendance_record SET attend = $attend
        WHERE id_attendance_record = '$id_attendance_record'";
    } else if ($grants & 4) {
        /*  $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }
    $Attendance = new Attendance;



    if ($attendance = $Attendance->saveAttendance($stmt)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'message'                => 'Se actualizó la asistencia correctamente'
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'UPDATE attendance_records.attendance_record SET attend = $attend
            WHERE id_attendance_record = $id_attendance_record'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function UpdateWeekAttendanceStudentsIncident()
{
    $id_attendance_record = $_POST['id_attendance_record'];
    $incident_id = $_POST['incident_id'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "UPDATE attendance_records.attendance_record SET incident_id = $incident_id
        WHERE id_attendance_record = '$id_attendance_record'";
    } else if ($grants & 4) {
        /*  $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }
    $Attendance = new Attendance;



    if ($attendance = $Attendance->saveAttendance($stmt)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'message'                => 'Se actualizó la incidencia correctamente'
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'UPDATE attendance_records.attendance_record SET attend = $attend
            WHERE id_attendance_record = $id_attendance_record'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function InsertWeekAttendanceStudents()
{
    $id_assignment = $_POST['id_assignment'];
    $date = $_POST['date'];
    $id_student = $_POST['id_student'];
    $present = $_POST['present'];
    $id_group = $_POST['id_group'];
    $class_block = $_POST['class_block'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;




    if (($grants & 8)) {
        $Attendance = new Attendance;

        $sql_getAssignmentInfo = "SELECT * FROM school_control_ykt.assignments WHERE id_assignment = '$id_assignment'";
        $getAssignmentInfo = $groups->getGroupFromTeachers($sql_getAssignmentInfo);
        if (!empty($getAssignmentInfo)) {
            $no_teacher_assg = $getAssignmentInfo[0]->no_teacher;
        }

        $stmt_update = "UPDATE attendance_records.attendance_index SET valid_assistance = 0 WHERE id_assignment = '$id_assignment' AND DATE(apply_date) = '$date'";
        $attendance = $Attendance->saveAttendance($stmt_update);

        $stmt = "INSERT INTO  attendance_records.attendance_index(id_assignment,obligatory,apply_date,teacher_passed_attendance, class_block, valid_assistance, Origen) 
        VALUES(
        '$id_assignment',
        '1',
        '$date',
        '$no_teacher_assg',
        '$class_block',
        '1',
        '$_SESSION[colab]'
        )";
    } else if ($grants & 4) {
        /*  $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$_SESSION[colab]'"; */
    }


    if ($attendance = $Attendance->saveAttendance($stmt)) {

        $get_group = "SELECT DISTINCT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name
        FROM school_control_ykt.students AS student
        INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
        WHERE inscription.id_group = '$id_group' AND student.status = 1
        ORDER BY student.lastname";

        $last_id = $attendance = $Attendance->getLastID();

        $getStudents = $groups->getGroupFromTeachers($get_group);
        foreach ($getStudents as  $std) {
            $stmt = "INSERT INTO attendance_records.attendance_record(id_attendance_index,incident_id,id_student,attend)
            VALUES(
            '$last_id',
            '1',
            '$std->id_student',
            '1'
            )";
            $attendance = $Attendance->saveAttendance($stmt);
        }
        $update_student = "UPDATE attendance_records.attendance_record SET attend = $present
        WHERE id_attendance_record = '$last_id' AND id_student = '$id_student'";

        if ($attendance = $Attendance->saveAttendance($update_student)) {
            //--- --- ---//
            $data = array(
                'response' => true,
                'message'                => 'Se generó la estructura de asistencia correctamente'
            );
            //--- --- ---//
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'message'                => 'UPDATE attendance_records.attendance_record SET attend = $attend
        WHERE id_attendance_record = $id_attendance_record'
            );
            //--- --- ---//
        }
    }



    echo json_encode($data);
}

function getGroups2()
{
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE rma.no_teacher  = '$_SESSION[colab]'";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher  = '$_SESSION[colab]'";
    }

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

function getSubjectsByTeacher()
{
    $id_group = $_POST['id_group'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {

        $stmt = "
        SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, groups.id_group
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, gps.id_group
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

        UNION 

        SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, asg.no_teacher, sbj.id_academic_area, asg.print_school_report_card, asg.assignment_active, groups.id_group
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        )

        AS u

        WHERE no_teacher = $_SESSION[colab] AND id_group = $id_group AND print_school_report_card = 1 AND assignment_active = 1
        ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.id_subject, sbj.name_subject, groups.id_group
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group'  AND asg.assignment_active = 1";
    }

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
function getGsroupsAttendance()
{
    $id_group = $_POST['id_group'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    $stmt = "SELECT gps.*
        FROM  school_control_ykt.groups AS gps
        WHERE gps.id_group = '$id_group'";

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
function getGroupsAttendance()
{

    $id_group = $_POST['id_group'];
    $strUL = "";
    $groups = new Groups;
    $data = array();



    $arr_groups = explode(",", $id_group);

    $c = (count($arr_groups) - 1);
    $strUL .= '<ul class="list-group">';

    for ($i = 0; $i < $c; $i++) {
        $arr_group_info = explode("class_block", $arr_groups[$i]);
        $id_grupo = $arr_group_info[0];
        $class_block = $arr_group_info[1];
        $apply_date = $arr_group_info[2];
        $arr_apply_date = explode(" ", $arr_group_info[2]);
        $apply_date_hour = $arr_apply_date[1];

        $sql_attendance = "SELECT gps.*
        FROM  school_control_ykt.groups AS gps
        WHERE gps.id_group = '$arr_groups[$i]' ORDER BY group_code DESC";

        $getDetails = $groups->getAssistanceDetails($sql_attendance);

        foreach ($getDetails as $group) {

            $strUL .= '<li class="list-group-item list-group-item-success">' . $group->group_code . ' | Bloque: ' . $class_block . ' | Hora de registro: ' . $apply_date_hour . '</li>';
        }
    }
    $strUL .= '</ul>';
    if (!empty($strUL)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $strUL
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getAssignmentByGroupSubject()
{
    $id_group = $_POST['id_group'];
    $id_subject = $_POST['id_subject'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment
        FROM  iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE rma.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group' AND asg.id_subject = '$id_subject'";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.id_subject, sbj.name_subject, groups.id_group
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group'";
    }

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
function getAssignmentByGroup()
{
    $id_group = $_POST['id_group'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment
        FROM  iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE rma.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group' LIMIT 1";
    } /* else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.id_subject, sbj.name_subject, groups.id_group
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group'";
    } */

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
            'message'                => 'Que extraño, parace que no tiene asignaturas para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getPeriodsGroupAcademic()
{

    $id_group = $_POST['id_group'];
    $id_academic = $_POST['id_academic'];

    $groups = new Groups;

    $stmt = "SELECT pc.*
        FROM school_control_ykt.level_combinations AS lvl_com
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lvl_com.id_campus AND groups.id_section = lvl_com.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level AND lvl_com.id_academic_level = ac_le.id_academic_level
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON lvl_com.id_level_combination = pc.id_level_combination
        WHERE lvl_com.id_academic_area = $id_academic AND groups.id_group = $id_group
        ";

    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        foreach ($getGroups as $level_combinations) {
            $id_level_combination = $level_combinations->id_level_combination;
        }
        $sql_level_combinations = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'";
        $getIdsLevelCombination = $groups->getGroupFromTeachers($sql_level_combinations);

        if (!empty($getIdsLevelCombination)) {
            $data = array(
                'response' => true,
                'data'                => $getIdsLevelCombination
            );
        } else {
            $data = array(
                'response' => false,
                'data'                => "error 2"
            );
        }

        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene periodos para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getAssignmentByGroupAcademic()
{
    $id_group = $_POST['id_group'];
    $id_academic = $_POST['id_academic'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT * FROM 

            (SELECT assg.print_school_report_card, assg.assignment_active, assg.id_assignment, groups.id_group
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador

            UNION 

            SELECT asgm.print_school_report_card, asgm.assignment_active, asgm.id_assignment, gps.id_group
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

            AS u

            WHERE  id_group = $id_group LIMIT 1
        ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment,asg.no_teacher
        FROM  school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]' AND sbj.id_academic_area ='$id_academic' AND groups.id_group = '$id_group' LIMIT 1";
    }

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
            'message'                => 'Que extraño, parace que no tiene asignaturas para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getAssignmentsPDA()
{
    $id_group = $_POST['id_group'];
    $id_academic = $_POST['id_academic'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;

    if (($grants & 8)) {

        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.name_subject
        FROM  iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE rma.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group' AND (sbj.id_subject=417 OR sbj.id_subject= 416) AND sbj.id_academic_area = '$id_academic'";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.name_subject
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]' AND groups.id_group = '$id_group' AND (sbj.id_subject=417 OR sbj.id_subject= 416) AND sbj.id_academic_area = '$id_academic'";
    }

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
            'message'                => 'Que extraño, parace que no tiene asignaturas para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getAllMyTeachersByAcademic()
{
    $groups = new Groups;
    $results = array();
    $id_academic = $_POST['id_academic'];
    $stmt = "SELECT * FROM 

    (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    
    UNION 

    SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade

    UNION 

    SELECT asg.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)

    AS u

    WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = '$id_academic' ORDER BY teacher_name ASC
            ";

    $getTeachers = $groups->getGroupFromTeachers($stmt);

    if (!empty($getTeachers)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getTeachers
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene profesores para esta sección'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getPeriodsByAssignment()
{
    $id_assignment = $_POST['id_assignment'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;

    $stmt = "SELECT lc.id_level_combination
        FROM school_control_ykt.level_combinations AS lc
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
        INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
        INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
        WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment' LIMIT 1";


    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        //--- --- ---//
        foreach ($getGroups as $level_combinations) {
            $id_level_combination = $level_combinations->id_level_combination;
        }
        $sql_level_combinations = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'";
        $getIdsLevelCombination = $groups->getGroupFromTeachers($sql_level_combinations);

        if (!empty($getIdsLevelCombination)) {
            $data = array(
                'response' => true,
                'data'                => $getIdsLevelCombination
            );
        } else {
            $data = array(
                'response' => false,
                'data'                => "error 2"
            );
        }

        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Que extraño, parace que no tiene periodos para este grupo'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getPeriodsByIdLevelCombination()
{
    $id_level_combination = $_POST['id_level_combination'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;

    $stmt = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'";


    $getIdsLevelCombination = $groups->getGroupFromTeachers($stmt);

    if (!empty($getIdsLevelCombination)) {
        $data = array(
            'response' => true,
            'data'                => $getIdsLevelCombination
        );
    } else {
        $data = array(
            'response' => false,
            'data'                => "error 2"
        );
    }


    echo json_encode($data);
}

function SaveIncident()
{
    $grants = $_SESSION['grantsITEQ'];
    $id_student = $_POST['id_student'];
    $incident = $_POST['incident'];
    $commit = $_POST['commit'];
    $date = $_POST['date'];
    $id_assignment = $_POST['id_assignment'];

    if ($id_assignment == "" or $id_assignment == 0) {
        $id_assignment = 'NULL';
    }

    $groups = new Groups;

    $sqlGetCurrentSchoolYear = "SELECT school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1";
    $getCurrentSchoolYear = $groups->getGroupFromTeachers($sqlGetCurrentSchoolYear);
    $school_year = $getCurrentSchoolYear[0]->school_year;
    $arr_school_year = explode("-", $school_year);
    $last_school_year = $arr_school_year[0];

    if (($grants & 8)) {

        $stmt = "INSERT INTO student_incidents.student_incidents_log (
            id_student_incidents_log,
            id_incidence_code,
            id_student,
            id_assignment,
            no_teacher_registered,
            incident_date,
            date_registered,
            incident_commit,
            school_year
        ) VALUES(
            NULL,
            '$incident',
            '$id_student',
            '$id_assignment',
            '$_SESSION[colab]',
            '$date',
            NOW(),
            '$commit',
            '$school_year'
        )";
    } else if ($grants & 4) {
        $stmt = "INSERT INTO student_incidents.student_incidents_log (
            id_student_incidents_log,
            id_incidence_code,
            id_student,
            id_assignment,
            no_teacher_registered,
            incident_date,
            date_registered,
            incident_commit
        ) VALUES(
            NULL,
            '$incident',
            '$id_student',
            '$id_assignment',
            '$_SESSION[colab]',
            '$date',
            NOW(),
            '$commit'
        )";
    }


    $insertData = $groups->getGroupFromTeachers($stmt);
    $data = array(
        'response' => true
    );
    //--- --- ---//


    echo json_encode($data);
}


function getGroupsByNoTeacher()
{

    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN iteach_academic.relationship_managers_assignments AS mnass ON asg.id_assignment = mnass.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE mnass.no_teacher =  '$_SESSION[colab]'";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT asg.id_assignment, groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]'";
    }

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

function getGroupsByIdAcademicLevel()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_academic_level = $_POST['id_academic_level'];
    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT * FROM 

        (SELECT groups.group_code, groups.id_group, assg.print_school_report_card, assg.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT gps.group_code, gps.id_group, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_level = $id_academic_level AND u.print_school_report_card = 1 AND u.assignment_active = 1
        ORDER BY u.group_code ASC
        ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCTgroups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]'";
    }

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

function getGroupsByIdAcademicLevelAndAcademicArea()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_academic_area = $_POST['id_academic_area'];
    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT * FROM 

        (SELECT groups.group_code, groups.id_group, assg.print_school_report_card, assg.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT gps.group_code, gps.id_group, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_level = $id_academic_level AND id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1
        ORDER BY u.group_code ASC
        ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$_SESSION[colab]'";
    }

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

function StudentInfoByID()
{

    $id_student = $_POST['id_student'];
    $groups = new Groups;

    $stmt = "SELECT id_student, student_code, CONCAT(name, ' ',lastname) AS student_name  
        FROM school_control_ykt.`students` 
        WHERE id_student = '$id_student'";


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

function getAttendanceDetailsJSON()
{
    $groups = new Groups;

    $ids_attendance_index = $_POST['ids_attendance_index'];
    $id_student = $_POST['id_student'];


    $arr_ids = explode("-", $ids_attendance_index);

    $stmt = "SELECT  stud.student_code, CONCAT(stud.name, ' ',stud.lastname) AS student_name, gps.group_code
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        WHERE stud.id_student =  '$id_student'";
    $getGroups = $groups->getGroupFromTeachers($stmt);
    $html_sweet_alert = '';
    $student_code = $getGroups[0]->student_code;
    $student_name = $getGroups[0]->student_name;
    $group_code = $getGroups[0]->group_code;

    $html_sweet_alert .= '<b>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</b><br><br>';
    $html_sweet_alert .= '<div style="height: 500px; overflow: auto" >';

    for ($i = 1; $i < count($arr_ids); $i++) {
        $id_attendance_index = $arr_ids[$i];

        $stmt = "SELECT DISTINCT atr.id_attendance_record, ati.apply_date, ati.class_block, atr.attend, stud.student_code,atr.incident_id, CONCAT(stud.name, ' ',stud.lastname) AS student_name, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name, sbj.name_subject, gps.group_code, iat.incident
        FROM attendance_records.attendance_index AS ati
        INNER JOIN attendance_records.attendance_record AS atr ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN attendance_records.incidents_attendance AS iat ON atr.incident_id = iat.incident_id
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = atr.id_student 
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
        WHERE ati.id_attendance_index = '$id_attendance_index' AND stud.id_student =  '$id_student' AND ati.valid_assistance = 1";

        $getGroups = $groups->getGroupFromTeachers($stmt);
        if (!empty($getGroups)) {

            $teacher_name = $getGroups[0]->teacher_name;
            $name_subject = $getGroups[0]->name_subject;
            $incident_id = $getGroups[0]->incident_id;
            $apply_date = $getGroups[0]->apply_date;
            $class_block = $getGroups[0]->class_block;
            $attend = $getGroups[0]->attend;
            $incident = strtoupper($getGroups[0]->incident);
            $class_attend = "";
            if ($incident_id == 3) {
                $class_attend = "dark";
            } else if ($attend == 1) {
                $class_attend = "success";
            } else {
                $class_attend = "danger";
            }

            $html_sweet_alert .= '<li class="list-group-item list-group-item-' . $class_attend . '">';
            $html_sweet_alert .= '<p>' . $name_subject . ' | ' . $apply_date . ' | Bloque: ' . $class_block . '</p>';
            $html_sweet_alert .= '<p> Registró: ' . $teacher_name . ' <br> Tipo de incidencia: ' . $incident . '</p>';
            $html_sweet_alert .= '</li>';
        }
    }
    $html_sweet_alert .= '</div>';


    if (!empty($html_sweet_alert)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $html_sweet_alert
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
function getStudentAttendanceHistoric()
{
    $groups = new Groups;

    $id_subject = $_POST['id_subject'];
    $id_group = $_POST['id_group'];
    $id_student = $_POST['id_student'];
    $student_code = $_POST['student_code'];
    $name_student = $_POST['name_student'];
    $pases_lsita = 0;
    $porcentaje_asistencia = 0;
    $faltas = 0;
    $asistencias = 0;

    $sqlGetAssignmentInfo = "SELECT asg.id_assignment, gps.group_code, sbj.name_subject FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE asg.id_subject = '$id_subject' AND asg.id_group = '$id_group'";
    $getAssignmentInfo = $groups->getGroupFromTeachers($sqlGetAssignmentInfo);

    $id_assignment = $getAssignmentInfo[0]->id_assignment;
    $group_code = $getAssignmentInfo[0]->group_code;
    $name_subject = $getAssignmentInfo[0]->name_subject;

    $sqlGetStudentInfo = "SELECT CONCAT(lastname, ' ', name) AS student_name, student_code FROM school_control_ykt.students where id_student = '$id_student'";
    $getStudentInfo = $groups->getGroupFromTeachers($sqlGetStudentInfo);
    $student_name = $getStudentInfo[0]->student_name;




    $today_date = date('Y-m-d');
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


    $sqPeriods = "SELECT percal.*, DATE(percal.start_date) start_date_date, DATE (percal.end_date) end_date_date
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_academic_area = sbj.id_academic_area AND lvc.id_academic_level = alg.id_academic_level
    AND lvc.id_campus = gps.id_campus AND lvc.id_section = gps.id_section
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
    WHERE asg.id_assignment = $id_assignment AND percal.start_date <= '$today_date' ";
    //echo $sqPeriods;
    $GetPeriods = $groups->getGroupFromTeachers($sqPeriods);


    $sqlGetCurrentPeriod = "SELECT percal.*, DATE(percal.start_date) start_date_date, DATE (percal.end_date) end_date_date
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_academic_area = sbj.id_academic_area AND lvc.id_academic_level = alg.id_academic_level
    AND lvc.id_campus = gps.id_campus AND lvc.id_section = gps.id_section
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
    WHERE asg.id_assignment = $id_assignment AND percal.start_date <= '$today_date' AND percal.end_date >= '$today_date'";
    //echo $sqlGetCurrentPeriod;
    $GetCurrentPeriod = $groups->getGroupFromTeachers($sqlGetCurrentPeriod);

    $start_date = $GetCurrentPeriod[0]->start_date;
    $end_date = $GetCurrentPeriod[0]->end_date;
    $no_period = $GetCurrentPeriod[0]->no_period;
    $end_date_date = $GetCurrentPeriod[0]->end_date_date;
    $start_date_date = $GetCurrentPeriod[0]->start_date_date;
    $id_period_calendar = $GetCurrentPeriod[0]->id_period_calendar;

    $arr_fecha_end = explode("-", $end_date_date);
    $arr_fecha_start = explode("-", $start_date_date);

    $m_s = date('n', strtotime($start_date_date));
    $mes_pase_start = $meses[$m_s];
    $yearFecha_start = $arr_fecha_start[0];

    $fecha_formato_start = $arr_fecha_start[2] . ' de ' . $mes_pase_start . ' de ' . $yearFecha_start;

    $m_e = date('n', strtotime($end_date_date));
    $mes_pase_end = $meses[$m_e];
    $yearFecha_end = $arr_fecha_end[0];

    $fecha_formato_end = $arr_fecha_end[2] . ' de ' . $mes_pase_end . ' de ' . $yearFecha_end;



    $html = "";

    $html .= '<h2>Registros de asistencia para:</h2>';
    $html .= '<h4>' . $student_code . ' | ' . mb_strtoupper($student_name) . '</h4>';
    $html .= '<h4>' . $group_code . ' | ' . $name_subject . ' | PERIODO: ' . $no_period . '</h4>';
    $html .= '<h5> Inicio de periodo: ' . $fecha_formato_start . ' <br>Fin de periodo: ' . $fecha_formato_end . '</h6>';


    $html .= '<br><div class="form-group">';
    $html .= '<label for="periodAttendanceSwal">Seleccionar otro periodo</label>';
    $html .= '<select data-id-subject="' . $id_subject . '" data-id-group="' . $id_group . '" data-id-student="' . $id_student . '" class="form-control periodAttendanceSwal" id="periodAttendanceSwal">';
    foreach ($GetPeriods as $periods) {
        if ($id_period_calendar == $periods->id_period_calendar) {
            $html .= '<option value="' . $periods->id_period_calendar . '" selected>' . $periods->no_period . '</option>';
        } else {
            $html .= '<option value="' . $periods->id_period_calendar . '">' . $periods->no_period . '</option>';
        }
    }
    $html .= '</select>';
    $html .= '</div>';
    $html .= '<div class="table-responsive" style="height:350px; overflow:auto;">';
    $html .= '<table class="table table-striped table-hover">';
    $html .= '<thead class="thead-dark">';
    $html .= '<tr>';
    $html .= '<th style="color:white" class="table-dark">FECHA</th>';
    $html .= '<th style="color:white" class="table-dark">BLOQUE</th>';
    $html .= '<th style="color:white" class="table-dark">STATUS</th>';
    $html .= '<th style="color:white" class="table-dark">T. INCIDENCIA</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';


    $sqlGetStudentPassList = "SELECT *, DATE(ati.apply_date) AS pass_date, CONCAT('(',aex.teacher_commit,')') AS excuse_comment,
    CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident
                                FROM school_control_ykt.students AS stud 
                                INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                LEFT JOIN absence_excuse.absences_excuse AS aex ON aex.id_student = atr.id_student 
                                    AND ( (DATE(ati.apply_date) >= DATE(aex.start_date)) AND ( DATE(ati.apply_date) <= DATE(aex.end_date)) AND aex.active_excuse)
                                WHERE stud.id_student =$id_student AND ati.id_assignment =$id_assignment AND ati.apply_date >= '$start_date' 
                                AND ati.apply_date <= '$end_date' AND ati.valid_assistance = 1 AND ati.obligatory = 1 order by pass_date DESC";
    //echo $sqlGetStudentPassList;
    $getStudentsPassList = $groups->getGroupFromTeachers($sqlGetStudentPassList);

    if (!empty($getStudentsPassList)) {
        foreach ($getStudentsPassList as $student_list) {
            $fecha = $student_list->pass_date;
            $teacher_name = $student_list->teacher_name;
            $class_block = $student_list->class_block;
            $attend = $student_list->attend;
            $iat_apply_justification = $student_list->iat_apply_justification;
            $double_absence = $student_list->double_absence;
            $atr_apply_justification = $student_list->atr_apply_justification;
            $incident = $student_list->incident;



            $html_status = '<span class=" badge-lg badge badge-dot mr-4"><i class="bg-success"></i><span class="status">Asistió</span></span>';
            if ($attend == 0 && $atr_apply_justification == 0 && $iat_apply_justification == 0) {
                $faltas++;
                $html_status = '<span class=" badge-lg badge badge-dot mr-4"><i class="bg-danger"></i><span class="status">Faltó</span></span>';
                if ($double_absence == 1) {
                    $faltas++;
                    $html_status = '<span class=" badge-lg badge badge-dot mr-4"><i class="bg-danger"></i><span class="status">Faltó (Falta doble)</span></span>';
                }
            } else if ($attend == 0 && ($atr_apply_justification == 1 || $iat_apply_justification == 1)) {
                $asistencias++;
                $html_status = '<span class=" badge-lg badge badge-dot mr-4"><i class="bg-info"></i><span class="status">Falta justificada</span></span>';
            } else if ($attend == 1) {
                $asistencias++;
                $html_status = '<span class=" badge-lg badge badge-dot mr-4"><i class="bg-success"></i><span class="status">Asistió</span></span>';
            }




            $arr_fecha = explode("-", $fecha);
            $mp = date('n', strtotime($fecha));

            $mes_pase = $meses[$mp];
            $ds = date('N', strtotime($fecha));
            $dia_semana = $dias[$ds];
            $yearFecha = $arr_fecha[0];

            $fecha_formato = $dia_semana . " " . $arr_fecha[2] . ' de <br>' . $mes_pase . ' de ' . $yearFecha;

            $str_comment_coord = "";
            if ($student_list->excuse_comment) {
                $str_comment_coord = "<br>" . $student_list->excuse_comment;
            }

            $html .= '<tr>';
            $html .= '<td>' . $fecha_formato . '</td>';
            $html .= '<td>' . $class_block . '</td>';
            $html .= '<td>' . $html_status . '</td>';
            $html .= '<td>' . $incident . $str_comment_coord . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr>';
        $html .= '<td colspan="100%">SIN REGISTROS</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    if (count($getStudentsPassList) > 0) {
        $porcentaje_asistencia = (number_format((((count($getStudentsPassList) - $faltas) / count($getStudentsPassList) * 100)), 0));
        $pl = $faltas + $asistencias;
    } else {
        $porcentaje_asistencia = "-";
        $pl = "-";
    }
    $html .= '<h3>Porcentaje de asistencia: ' . $porcentaje_asistencia . '%</h3>';
    $html .= '<h4>Pases de lista: ' . $pl . '</h4>';


    $data = array(
        'response' => true,
        'html'                => $html
    );

    echo json_encode($data);
}
function getStudentAttendanceHistoricSwal()
{
    $groups = new Groups;

    $id_subject = $_POST['id_subject'];
    $id_group = $_POST['id_group'];
    $id_student = $_POST['id_student'];
    $id_period_calendar_ajx = $_POST['id_period_calendar'];
    $pases_lsita = 0;
    $porcentaje_asistencia = 0;
    $faltas = 0;
    $asistencias = 0;

    $sqlGetAssignmentInfo = "SELECT asg.id_assignment, gps.group_code, sbj.name_subject FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE asg.id_subject = '$id_subject' AND asg.id_group = '$id_group'";
    $getAssignmentInfo = $groups->getGroupFromTeachers($sqlGetAssignmentInfo);

    $id_assignment = $getAssignmentInfo[0]->id_assignment;
    $group_code = $getAssignmentInfo[0]->group_code;
    $name_subject = $getAssignmentInfo[0]->name_subject;

    $sqlGetStudentInfo = "SELECT CONCAT(lastname, ' ', name) AS student_name, student_code FROM school_control_ykt.students where id_student = '$id_student'";
    $getStudentInfo = $groups->getGroupFromTeachers($sqlGetStudentInfo);
    $student_name = $getStudentInfo[0]->student_name;
    $student_code = $getStudentInfo[0]->student_code;




    $today_date = date('Y-m-d');
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");



    $sqlGetCurrentPeriod = "SELECT percal.*, DATE(percal.start_date) start_date_date, DATE (percal.end_date) end_date_date
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_academic_area = sbj.id_academic_area AND lvc.id_academic_level = alg.id_academic_level
    AND lvc.id_campus = gps.id_campus AND lvc.id_section = gps.id_section
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
    WHERE asg.id_assignment = $id_assignment AND percal.start_date <= '$today_date'";

    $GetCurrentPeriod = $groups->getGroupFromTeachers($sqlGetCurrentPeriod);

    $id_period_calendar_swal = $GetCurrentPeriod[0]->id_period_calendar;


    $sqPeriods = "SELECT percal.*, DATE(percal.start_date) start_date_date, DATE (percal.end_date) end_date_date
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_academic_area = sbj.id_academic_area AND lvc.id_academic_level = alg.id_academic_level
    AND lvc.id_campus = gps.id_campus AND lvc.id_section = gps.id_section
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvc.id_level_combination
    WHERE asg.id_assignment = $id_assignment AND percal.id_period_calendar = '$id_period_calendar_ajx' ";
    //echo $sqPeriods;
    $GetPeriods = $groups->getGroupFromTeachers($sqPeriods);


    $start_date = $GetPeriods[0]->start_date;
    $end_date = $GetPeriods[0]->end_date;
    $no_period = $GetPeriods[0]->no_period;
    $end_date_date = $GetPeriods[0]->end_date_date;
    $start_date_date = $GetPeriods[0]->start_date_date;
    $id_period_calendar = $GetPeriods[0]->id_period_calendar;

    $arr_fecha_end = explode("-", $end_date_date);
    $arr_fecha_start = explode("-", $start_date_date);

    $m_s = date('n', strtotime($start_date_date));
    $mes_pase_start = $meses[$m_s];
    $yearFecha_start = $arr_fecha_start[0];

    $fecha_formato_start = $arr_fecha_start[2] . ' de ' . $mes_pase_start . ' de ' . $yearFecha_start;

    $m_e = date('n', strtotime($end_date_date));
    $mes_pase_end = $meses[$m_e];
    $yearFecha_end = $arr_fecha_end[0];

    $fecha_formato_end = $arr_fecha_end[2] . ' de ' . $mes_pase_end . ' de ' . $yearFecha_end;



    $html = "";

    $html .= '<h2>Registros de asistencia para:</h2>';
    $html .= '<h4>' . $student_code . ' | ' . mb_strtoupper($student_name) . '</h4>';
    $html .= '<h4>' . $group_code . ' | ' . $name_subject . ' | PERIODO: ' . $no_period . '</h4>';
    $html .= '<h5> Inicio de periodo: ' . $fecha_formato_start . ' <br>Fin de periodo: ' . $fecha_formato_end . '</h6>';


    $html .= '<br><div class="form-group">';
    $html .= '<label for="periodAttendanceSwal">Seleccionar otro periodo</label>';
    $html .= '<select data-id-subject="' . $id_subject . '" data-id-group="' . $id_group . '" data-id-student="' . $id_student . '" class="form-control periodAttendanceSwal" id="periodAttendanceSwal">';
    foreach ($GetCurrentPeriod as $periods) {
        if ($id_period_calendar_ajx == $periods->id_period_calendar) {
            $html .= '<option value="' . $periods->id_period_calendar . '" selected>' . $periods->no_period . '</option>';
        } else {
            $html .= '<option value="' . $periods->id_period_calendar . '">' . $periods->no_period . '</option>';
        }
    }
    $html .= '</select>';
    $html .= '</div>';
    $html .= '<div class="table-responsive" style="height:350px; overflow:auto;">';
    $html .= '<table class="table table-striped table-hover">';
    $html .= '<thead class="thead-dark">';
    $html .= '<tr>';
    $html .= '<th style="color:white" class="table-dark">FECHA</th>';
    $html .= '<th style="color:white" class="table-dark">BLOQUE</th>';
    $html .= '<th style="color:white" class="table-dark">STATUS</th>';
    $html .= '<th style="color:white" class="table-dark">T. INCIDENCIA</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';


    $sqlGetStudentPassList = "SELECT *, DATE(ati.apply_date) AS pass_date, aex.teacher_commit AS excuse_comment,
    CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident
                                FROM school_control_ykt.students AS stud 
                                INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                 LEFT JOIN absence_excuse.absences_excuse AS aex ON aex.id_student = atr.id_student 
                                    AND ( (DATE(ati.apply_date) >= DATE(aex.start_date)) AND ( DATE(ati.apply_date) <= DATE(aex.end_date)) AND aex.active_excuse)
                                WHERE stud.id_student =$id_student AND ati.id_assignment =$id_assignment AND ati.apply_date >= '$start_date' 
                                AND ati.apply_date <= '$end_date' AND ati.valid_assistance = 1 AND ati.obligatory = 1 order by pass_date DESC";
    //echo $sqlGetStudentPassList;
    $getStudentsPassList = $groups->getGroupFromTeachers($sqlGetStudentPassList);

    if (!empty($getStudentsPassList)) {
        foreach ($getStudentsPassList as $student_list) {
            $fecha = $student_list->pass_date;
            $teacher_name = $student_list->teacher_name;
            $class_block = $student_list->class_block;
            $attend = $student_list->attend;
            $iat_apply_justification = $student_list->iat_apply_justification;
            $double_absence = $student_list->double_absence;
            $atr_apply_justification = $student_list->atr_apply_justification;
            $incident = $student_list->incident;



            $html_status = '<span class="badge badge-dot mr-4"><i class="bg-success"></i><span class="status">Asistió</span></span>';
            if ($attend == 0 && $atr_apply_justification == 0 && $iat_apply_justification == 0) {
                $faltas++;
                $html_status = '<span class="badge badge-dot mr-4"><i class="bg-danger"></i><span class="status">Faltó</span></span>';
                if ($double_absence == 1) {
                    $faltas++;
                    $html_status = '<span class="badge badge-dot mr-4"><i class="bg-danger"></i><span class="status">Faltó (Falta doble)</span></span>';
                }
            } else if ($attend == 0 && ($atr_apply_justification == 1 || $iat_apply_justification == 1)) {
                $asistencias++;
                $html_status = '<span class="badge badge-dot mr-4"><i class="bg-info"></i><span class="status">Falta justificada</span></span>';
            } else if ($attend == 1) {
                $asistencias++;
                $html_status = '<span class="badge badge-dot mr-4"><i class="bg-success"></i><span class="status">Asistió</span></span>';
            }




            $arr_fecha = explode("-", $fecha);
            $mp = date('n', strtotime($fecha));

            $mes_pase = $meses[$mp];
            $ds = date('N', strtotime($fecha));
            $dia_semana = $dias[$ds];
            $yearFecha = $arr_fecha[0];

            $fecha_formato = $dia_semana . " " . $arr_fecha[2] . ' de <br>' . $mes_pase . ' de ' . $yearFecha;



            $html .= '<tr>';
            $html .= '<td>' . $fecha_formato . '</td>';
            $html .= '<td>' . $class_block . '</td>';
            $html .= '<td>' . $html_status . '</td>';
            $html .= '<td>' . $incident . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr>';
        $html .= '<td colspan="100%">SIN REGISTROS</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    if (count($getStudentsPassList) > 0) {
        $porcentaje_asistencia = (number_format((((count($getStudentsPassList) - $faltas) / count($getStudentsPassList) * 100)), 0));
        $pl = $faltas + $asistencias;
    } else {
        $porcentaje_asistencia = "-";
        $pl = "-";
    }
    $html .= '<h3>Porcentaje de asistencia: ' . $porcentaje_asistencia . '%</h3>';
    $html .= '<h4>Pases de lista: ' . $pl . '</h4>';


    $data = array(
        'response' => true,
        'html'                => $html
    );

    echo json_encode($data);
}

function getPassListDetails()
{
    $groups = new Groups;

    $id_attendance_index = $_POST['id_attendance_index'];



    $stmt = "SELECT colab.no_colaborador, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name,
    sbj.name_subject, gps.group_code, ati.apply_date
    FROM attendance_records.attendance_index AS ati
    INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    WHERE ati.id_attendance_index =  '$id_attendance_index'";

    $getGroups = $groups->getGroupFromTeachers($stmt);
    $html_sweet_alert = '';
    $name_subject = $getGroups[0]->name_subject . " <br> " . $getGroups[0]->group_code;
    $teacher_name = $getGroups[0]->teacher_name;
    $arr_apply_date = explode(" ", $getGroups[0]->apply_date);
    $arr_time = (explode(":", $arr_apply_date[1]));
    $time = $arr_time[0] . ":" . $arr_time[1];


    $today_date = date('Y-m-d');
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");




    $arr_fecha_start = explode("-", $arr_apply_date[0]);

    $m_s = date('n', strtotime($arr_apply_date[0]));
    $mes_pase_start = $meses[$m_s];
    $yearFecha_start = $arr_fecha_start[0];

    $fecha_formato_start = "Fecha de registro: " . $arr_fecha_start[2] . ' de ' . $mes_pase_start . ' de ' . $yearFecha_start . " a las: " . $time . " hrs.";



    $html_sweet_alert .= '<b> ' . $name_subject . ' | ' . $teacher_name . '</b><br><br>' . $fecha_formato_start . '<br><br>';
    $html_sweet_alert .= '<div class="table-response" style="height: 500px; overflow: auto" >';
    $html_sweet_alert .= '<table class="table table-striped">';
    $html_sweet_alert .= '<thead class="table-dark">';
    $html_sweet_alert .= '<tr>';
    $html_sweet_alert .= '<th>CÓD. ALUMNO</th>';
    $html_sweet_alert .= '<th>NOMBRE</th>';
    $html_sweet_alert .= '<th>STATUS</th>';
    $html_sweet_alert .= '<th>DETALLE</th>';
    $html_sweet_alert .= '</tr>';
    $html_sweet_alert .= '</thead>';
    $html_sweet_alert .= '<tbody>';

    $stmt = "SELECT ati.apply_date, ati.class_block, sbj.name_subject, gps.group_code,
    stud.id_student, stud.student_code, UPPER(CONCAT(stud.lastname , ' ', stud.name)) AS student_name,
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident,
                sbj.name_subject, sbj.short_name, 
                CASE 
                WHEN ((aex.teacher_commit IS NOT NULL) AND (aex.teacher_commit != '')) THEN CONCAT('<br>(',aex.teacher_commit,')') 
                ELSE ''
                END
                AS excuse_comment,
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN 'Falta Justificada'
                WHEN atr.attend = 0 THEN 'Ausente'
                WHEN atr.attend = 1 THEN 'Presente'
                END 
                AS std_attend_text,
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN '#ffcd7d'
                WHEN atr.attend = 0 THEN '#ff7e75'
                WHEN atr.attend = 1 THEN '#b8ffb0'
                END 
                AS std_attend_color
        FROM attendance_records.attendance_index AS ati
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN attendance_records.attendance_record AS atr ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN school_control_ykt.students AS stud ON atr.id_student = stud.id_student
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student AND gps.id_group = insc.id_group
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        LEFT JOIN absence_excuse.absences_excuse AS aex ON aex.id_student = atr.id_student 
            AND ( (DATE(ati.apply_date) >= DATE(aex.start_date)) AND ( DATE(ati.apply_date) <= DATE(aex.end_date)) AND aex.active_excuse)
        WHERE ati.id_attendance_index = '$id_attendance_index' AND ati.valid_assistance = 1 AND ati.obligatory = 1 order by student_name ASC
        ";
    $getGroups = $groups->getGroupFromTeachers($stmt);
    if (!empty($getGroups)) {
        foreach ($getGroups as $att_detail) {

            $html_sweet_alert .= '<tr>';
            $html_sweet_alert .= '<td>' . $att_detail->student_code . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->student_name . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->std_attend_text . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->incident . $att_detail->excuse_comment . '</td>';
            $html_sweet_alert .= '</tr>';
        }
    } else {

        $html_sweet_alert .= '<tr>';
        $html_sweet_alert .= '<td colspan="100%">SIN REGISTROS</td>';
        $html_sweet_alert .= '</tr>';
    }

    $html_sweet_alert .= '</tbody>';
    $html_sweet_alert .= '</table>';
    $html_sweet_alert .= '</div>';


    if (!empty($html_sweet_alert)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $html_sweet_alert
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

function getAttendanceRecordDetails()
{
    $groups = new Groups;

    $id_attendance_record = $_POST['id_attendance_record'];



    $stmt = "SELECT colab.no_colaborador, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name,
    sbj.name_subject, gps.group_code, ati.apply_date
    FROM attendance_records.attendance_record AS atr
    INNER JOIN attendance_records.attendance_index AS ati ON atr.id_attendance_index = ati.id_attendance_index
    INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    WHERE atr.id_attendance_record =  '$id_attendance_record'";

    $getGroups = $groups->getGroupFromTeachers($stmt);
    $html_sweet_alert = '';
    $name_subject = $getGroups[0]->name_subject . " <BR> " . $getGroups[0]->group_code;
    $teacher_name = $getGroups[0]->teacher_name;

    $arr_apply_date = explode(" ", $getGroups[0]->apply_date);
    $arr_time = (explode(":", $arr_apply_date[1]));
    $time = $arr_time[0] . ":" . $arr_time[1];


    $today_date = date('Y-m-d');
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");




    $arr_fecha_start = explode("-", $arr_apply_date[0]);

    $m_s = date('n', strtotime($arr_apply_date[0]));
    $mes_pase_start = $meses[$m_s];
    $yearFecha_start = $arr_fecha_start[0];

    $fecha_formato_start = "Fecha de registro: " . $arr_fecha_start[2] . ' de ' . $mes_pase_start . ' de ' . $yearFecha_start . " a las: " . $time . " hrs.";



    $html_sweet_alert .= '<b> ' . $name_subject . ' | ' . $teacher_name . '</b><br><br>' . $fecha_formato_start . '<br><br>';
    $html_sweet_alert .= '<div class="table-response" style="height: 500px; overflow: auto" >';
    $html_sweet_alert .= '<table class="table table-striped">';
    $html_sweet_alert .= '<thead class="table-dark">';
    $html_sweet_alert .= '<tr>';
    $html_sweet_alert .= '<th>CÓD. ALUMNO</th>';
    $html_sweet_alert .= '<th>NOMBRE</th>';
    $html_sweet_alert .= '<th>STATUS</th>';
    $html_sweet_alert .= '<th>DETALLE</th>';
    $html_sweet_alert .= '</tr>';
    $html_sweet_alert .= '</thead>';
    $html_sweet_alert .= '<tbody>';

    $stmt = "SELECT ati.apply_date, ati.class_block, sbj.name_subject, gps.group_code,
    stud.id_student, stud.student_code, UPPER(CONCAT(stud.lastname , ' ', stud.name)) AS student_name,
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident,
                sbj.name_subject, sbj.short_name, 
                CASE 
                WHEN ((aex.teacher_commit IS NOT NULL) AND (aex.teacher_commit != '')) THEN CONCAT('<br>(',aex.teacher_commit,')') 
                ELSE ''
                END
                AS excuse_comment,
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN 'Falta Justificada'
                WHEN atr.attend = 0 THEN 'Ausente'
                WHEN atr.attend = 1 THEN 'Presente'
                END 
                AS std_attend_text,
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN '#ffcd7d'
                WHEN atr.attend = 0 THEN '#ff7e75'
                WHEN atr.attend = 1 THEN '#b8ffb0'
                END 
                AS std_attend_color
        FROM attendance_records.attendance_record AS atr
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.students AS stud ON atr.id_student = stud.id_student
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student AND gps.id_group = insc.id_group
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        LEFT JOIN absence_excuse.absences_excuse AS aex ON aex.id_student = atr.id_student 
            AND ( (DATE(ati.apply_date) >= DATE(aex.start_date)) AND ( DATE(ati.apply_date) <= DATE(aex.end_date)) AND aex.active_excuse)
        WHERE atr.id_attendance_record = '$id_attendance_record' AND ati.valid_assistance = 1 AND ati.obligatory = 1 order by student_name ASC
        ";
    $getGroups = $groups->getGroupFromTeachers($stmt);
    if (!empty($getGroups)) {
        foreach ($getGroups as $att_detail) {

            $html_sweet_alert .= '<tr>';
            $html_sweet_alert .= '<td>' . $att_detail->student_code . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->student_name . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->std_attend_text . '</td>';
            $html_sweet_alert .= '<td>' . $att_detail->incident . $att_detail->excuse_comment . '</td>';
            $html_sweet_alert .= '</tr>';
        }
    } else {

        $html_sweet_alert .= '<tr>';
        $html_sweet_alert .= '<td colspan="100%">SIN REGISTROS</td>';
        $html_sweet_alert .= '</tr>';
    }

    $html_sweet_alert .= '</tbody>';
    $html_sweet_alert .= '</table>';
    $html_sweet_alert .= '</div>';


    if (!empty($html_sweet_alert)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $html_sweet_alert
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
function getTeacherAttendanceDetailsJSON()
{
    $groups = new Groups;

    $ids_attendance_index = $_POST['ids_attendance_index'];
    $no_teacher = $_POST['no_teacher'];


    $arr_ids = explode(",", $ids_attendance_index);

    $stmt = "SELECT colab.no_colaborador, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM colaboradores_ykt.colaboradores AS colab 
    WHERE colab.no_colaborador =  '$no_teacher'";
    $getGroups = $groups->getGroupFromTeachers($stmt);
    $html_sweet_alert = '';
    $id_teacher = $getGroups[0]->no_colaborador;
    $teacher_name = $getGroups[0]->teacher_name;

    $html_sweet_alert .= '<b> N° Colab: ' . $id_teacher . ' | ' . $teacher_name . '</b><br><br>';
    $html_sweet_alert .= '<div style="height: 500px; overflow: auto" >';

    for ($i = 0; $i < count($arr_ids); $i++) {
        $id_attendance_index = $arr_ids[$i];

        $stmt = "SELECT ati.apply_date, ati.class_block, sbj.name_subject, gps.group_code
        FROM attendance_records.attendance_index AS ati
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        WHERE ati.id_attendance_index = '$id_attendance_index'";
        $getGroups = $groups->getGroupFromTeachers($stmt);
        if (!empty($getGroups)) {

            $name_subject = $getGroups[0]->name_subject;
            $apply_date = $getGroups[0]->apply_date;
            $class_block = $getGroups[0]->class_block;
            $group_code = $getGroups[0]->group_code;

            $html_sweet_alert .= '<li class="list-group-item list-group-item-info">';
            $html_sweet_alert .= '<p>' . $name_subject . ' | ' . $group_code . ' | <strong>' . $apply_date . '</strong> | Bloque: ' . $class_block . '</p>';
            $html_sweet_alert .= '</li>';
        }
    }
    $html_sweet_alert .= '</div>';


    if (!empty($html_sweet_alert)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $html_sweet_alert
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


function StudentIncidentInfo()
{

    $id_incident = $_POST['id_incident'];
    $groups = new Groups;

    $stmt = "SELECT DISTINCT sil.*, 
    CASE 
    WHEN sil.id_assignment = 0 THEN 'N/A'
    WHEN sil.id_assignment != 0 THEN sbj.name_subject
    WHEN sbj.name_subject IS NULL THEN 'N/A'
    END AS name_subject,
    isb.incident_description_detail, isb.incidence_consequences, gps.group_code,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
    CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
    FROM  student_incidents.student_incidents_log AS sil
    INNER JOIN student_incidents.incidence_code AS isb ON sil.id_incidence_code = isb.id_incidence_code
    INNER JOIN school_control_ykt.assignments AS  asg ON asg.no_teacher = sil.no_teacher_registered
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
    INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
    INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
    INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
    INNER JOIN school_control_ykt.groups AS gps ON student.group_id = gps.id_group
    LEFT JOIN  school_control_ykt.assignments AS asg1 ON sil.id_assignment = asg1.id_assignment
    LEFT JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg1.id_subject
    WHERE `id_student_incidents_log` =  '$id_incident'";


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

function getAssistanceDetails()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_student = $_POST['id_student'];
    $group_id = $_POST['id_group'];
    $date = $_POST['date'];
    $strUL = "";
    $groups = new Groups;

    $sql_attendance = "SELECT  assignment.id_assignment, assignment.id_subject, subject.name_subject, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM school_control_ykt.inscriptions AS ins 
    INNER JOIN school_control_ykt.assignments AS assignment ON ins.id_group = assignment.id_group
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = assignment.no_teacher
    INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
    WHERE ins.id_student ='$id_student' AND ins.id_group = '$group_id'";


    $getDetails = $groups->getAssistanceDetails($sql_attendance);

    if (!empty($getDetails)) {
        $strUL .= '<ul class="list-group">';
        foreach ($getDetails as $matters) {
            $id_assignment = $matters->id_assignment;
            $name_subject = $matters->name_subject;
            $teacher_name = $matters->teacher_name;
            //--- --- ---//
            $sql_attendance1 = "SELECT id_attendance_index, class_block, apply_date FROM attendance_records.attendance_index
            WHERE id_assignment = '$id_assignment' AND obligatory = 1 AND apply_date LIKE '$date%' AND valid_assistance = 1
            ORDER BY id_attendance_index";

            $getDetails1 = $groups->getAssistanceDetails($sql_attendance1);

            foreach ($getDetails1 as $attend) {
                $id_attendance_index = $attend->id_attendance_index;
                $class_block = $attend->class_block;
                $arr_apply_date = explode(" ", $attend->apply_date);
                $apply_date = $arr_apply_date[1];

                $sql_attendance2 = "SELECT count(*) AS result FROM attendance_records.attendance_record
                WHERE id_attendance_index = '$id_attendance_index' AND id_student = '$id_student' AND attend = 1";

                $sql_attendance3 = "SELECT * FROM attendance_records.attendance_record
                WHERE id_attendance_index = '$id_attendance_index' AND id_student = '$id_student'";

                $getDetails2 = $groups->getAssistanceDetails($sql_attendance2);
                $getDetails3 = $groups->getAssistanceDetails($sql_attendance3);
                foreach ($getDetails3 as  $arr_incident_id) {
                    $incident_id = $arr_incident_id->incident_id;
                    $id_attendance_record = $arr_incident_id->id_attendance_record;
                }
                foreach ($getDetails2 as $count) {
                    $res_count = $count->result;
                    if ($res_count > 0) {
                        $strUL .= '<li class="list-group-item list-group-item-success">' . $name_subject . ' - ' . $teacher_name . ' | Bloque: ' . $class_block . ' | Hora: ' . $apply_date . '</li>';
                    } else {
                        switch ($incident_id) {
                            case 1:
                                $strUL .= '<li class="list-group-item list-group-item-danger">' . $name_subject . '</li>';
                                break;
                            case 3:
                                $strUL .= '<li class="list-group-item list-group-item-dark">' . $name_subject . '</li>';
                                break;
                            default:
                                $strUL .= '<li class="list-group-item list-group-item-danger">' . $name_subject . '</li>';
                                break;
                        }
                    }
                }
            }
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $strUL
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function getCriteriaDetails()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_grade_period = $_POST['id_grade_period'];
    $id_student = $_POST['id_student'];
    $id_assignment = $_POST['id_assignment'];
    $promedio = $_POST['promedio'];
    $grade_period_calc = $_POST['grade_period_calc'];
    $strUL = "";

    $groups = new Groups;

    $sql_student_details = "SELECT CONCAT(name,' ',lastname) AS student_name, student_code FROM school_control_ykt.students AS student WHERE student.id_student = '$id_student'";
    $studentDetails = $groups->getAssistanceDetails($sql_student_details);

    $student_code = $studentDetails[0]->student_code;
    $student_name = $studentDetails[0]->student_name;

    $sql_get_assignment_details = "
    SELECT groups.group_code, subject.name_subject, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM school_control_ykt.assignments AS assignment 
    INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON assignment.id_group = groups.id_group
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = assignment.no_teacher
    WHERE assignment.id_assignment = '$id_assignment'";

    $getAssignmentDetails = $groups->getAssistanceDetails($sql_get_assignment_details);
    $group_code = $getAssignmentDetails[0]->group_code;
    $subject_name = $getAssignmentDetails[0]->name_subject;
    $teacher_name = $getAssignmentDetails[0]->teacher_name;

    $sql_criteria_details = "SELECT CASE
    WHEN ep.manual_name IS NULL THEN evs.evaluation_name
    WHEN ep.manual_name = '' THEN evs.evaluation_name
    ELSE ep.manual_name
    END
    AS criteria_name, gec.grade_evaluation_criteria_teacher, percal.no_period , percal.id_period_calendar, percentage,
    grade_extraordinary_examen
    FROM iteach_grades_quantitatives.grades_evaluation_criteria as gec
    INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan
    INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON ep.id_evaluation_source = evs.id_evaluation_source
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON ep.id_period_calendar = percal.id_period_calendar
    LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON gec.id_final_grade = extra.id_final_grade AND gec.id_grade_period = extra.id_grade_period
    WHERE gec.`id_grade_period` = '$id_grade_period' AND ep.evaluation_type_id = 1";


    $criteriaDetails = $groups->getAssistanceDetails($sql_criteria_details);

    if (!empty($criteriaDetails)) {
        $id_period_calendar = $criteriaDetails[0]->id_period_calendar;
$grade_extraordinary_examen=$criteriaDetails[0]->grade_extraordinary_examen;
        $sqlcheckIfExist = "SELECT * FROM iteach_grades_quantitatives.grade_period_commentary WHERE id_grade_period = '$id_grade_period' AND active = 1";
        $checkIfExist = $groups->getAssistanceDetails($sqlcheckIfExist);
        if (!empty($checkIfExist)) {
            $commentary = $checkIfExist[0]->commentary;
            $checked = $checkIfExist[0]->checked;
            if ($checked == 1) {
                $attr_chcbx = "checked";
            } else {
                $attr_chcbx = "";
            }
        } else {
            $commentary = "";
        }

        $period = $criteriaDetails[0]->no_period;
        $strUL .= '<h4><strong>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</strong></h4>';
        $strUL .= '<h5><strong>' . $subject_name . ' | ' . $teacher_name . ' | Periodo: ' . $period . '</strong></h5>';
        $strUL .= '<br>';
        $strUL .= '<br>';
        $strUL .= "<div class='table-responsive'>";
        $strUL .= "<table class='table table-bordered table-striped table-hover'>";
        $strUL .= "<thead class='thead-dark'>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:white !important;'>Criterio</th>";
        $strUL .= "<th style='color:white !important;'>Porcentaje</th>";
        $strUL .= "<th style='color:white !important;'>Calificación</th>";
        $strUL .= "</tr>";
        $strUL .= "</thead>";
        $strUL .= "<tbody>";

        foreach ($criteriaDetails as $criteria) {
            $criteria_name = $criteria->criteria_name;
            $grade_evaluation_criteria_teacher = $criteria->grade_evaluation_criteria_teacher;
            $percentage = $criteria->percentage;
            //--- --- ---//
            $strUL .= "<tr>";
            $strUL .= "<th>" . $criteria_name . "</th>";

            $strUL .= "<th>" . $percentage . "</th>";
            $strUL .= "<td>" . $grade_evaluation_criteria_teacher . "</td>";
            $strUL .= "</tr>";
        }

        $strUL .= "</tbody>";
        $strUL .= "<tfoot>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:black !important;'>Promedio</th>";
        $strUL .= "<th bgcolor='#d4d4d4'></th>";
        $strUL .= "<th style='color:black !important;'>" . $promedio . "</th>";
        $strUL .= "</tr>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:black !important;'>Promedio Dinámico</th>";
        $strUL .= "<th bgcolor='#d4d4d4'></th>";
        $strUL .= "<th style='color:black !important;'>" . $grade_period_calc . "</th>";
        $strUL .= "</tr>";
        $strUL .= "</tr>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:black !important;'>Exámen Extraoridnario</th>";
        $strUL .= "<th bgcolor='#d4d4d4'></th>";
        $strUL .= "<th style='color:black !important;'>" . $grade_extraordinary_examen . "</th>";
        $strUL .= "</tr>";
        $strUL .= "</tfoot>";
        $strUL .= "</table>";
        if ($grants > 14) {
            $strUL .= "<br>";
            $strUL .= "<br>";
            $strUL .= '<div class="form-group">';
            $strUL .= '<label for="exampleFormControlInput1">Agregar comentario</label>';
            $strUL .= '<div>';
            $strUL .= '<input type="email" data-id-period="' . $id_grade_period . '" value="' . $commentary . '" class="form-control" id="PeriodGradeCommentary"  placeholder="Ingrese un comentario">';
            $strUL .= '<a title="Ver y/o editar calificaciones" href="evaluaciones.php?id_assignment=' . $id_assignment . '&id_period_calendar=' . $id_period_calendar . '" target="_blank" data-id-grade-period="' . $id_grade_period . '" class="btn btn-default seeQualifications"><i class="fas fa-edit"></i></a>';
            $strUL .= '<button title="Guardar cambios" type="button" data-id-grade-period="' . $id_grade_period . '" class="btn btn-success addPeriodGradeCommentary"><i class="fas fa-save"></i></button>';
            $strUL .= '<button title="Eliminar observación" type="button" data-id-grade-period="' . $id_grade_period . '" class="btn btn-danger deletePeriodGradeCommentary"><i class="fas fa-trash-alt"></i></button><br><br>';
            $strUL .= '</div>';
            $strUL .= '</div>';
        } else {
            if (!empty($checkIfExist)) {
                $strUL .= "<br>";
                $strUL .= "<br>";
                $strUL .= '<div class="form-group">';

                $strUL .= '<div class="custom-control custom-checkbox">';
                $strUL .= '<input type="checkbox" ' . $attr_chcbx . ' class="custom-control-input checkEPCommentary" id="checkEPCommentary" data-id-grade-period="' . $id_grade_period . '">';
                $strUL .= '<label  data-toggle="tooltip" data-placement="top" title="Marque esta casilla si ya atendió la observación" class="custom-control-label" for="checkEPCommentary"><strong>Observación: </strong>' . $commentary . '</label>';
                $strUL .= '</div><br>';
                $strUL .= '<div>';
                $strUL .= '<a title="Ver y/o editar calificaciones" href="evaluaciones.php?id_assignment=' . $id_assignment . '&id_period_calendar=' . $id_period_calendar . '" target="_blank" data-id-grade-period="' . $id_grade_period . '" class="btn btn-default seeQualifications"><i class="fas fa-edit"></i></a><br><br>';
                $strUL .= '</div>';
                $strUL .= '</div>';
                # code...
            }
        }
        $strUL .= "</div>";
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $strUL
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getCriteriaDetailsArchive()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_student = $_POST['id_student'];
    $id_assignment = $_POST['id_assignment'];
    $id_period_calendar = $_POST['id_period_calendar'];
    $id_group = $_POST['id_group'];

    $strUL = "";

    $groups = new Groups;

    $sql_student_details = "SELECT CONCAT(name,' ',lastname) AS student_name, student_code FROM school_control_ykt.students AS student WHERE student.id_student = '$id_student'";
    $studentDetails = $groups->getAssistanceDetails($sql_student_details);

    $student_code = $studentDetails[0]->student_code;
    $student_name = $studentDetails[0]->student_name;


    $sqlGetAVG = "SELECT 
    CASE 
   WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NULL  THEN '-'
   WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
   WHEN grape.grade_period IS NOT NULL AND extra.grade_extraordinary_examen IS NULL  THEN grape.grade_period
   WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NOT NULL  THEN extra.grade_extraordinary_examen
    END
    AS 'grade_period'
     FROM school_control_ykt.assignments AS assgn
    INNER JOIN school_control_ykt.inscriptions AS insc
    INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = insc.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
    INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period_calendar
    INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assgn.id_assignment = fga.id_assignment AND fga.id_student = insc.id_student
    INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
    LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON extra.id_grade_period = grape.id_grade_period
    WHERE fga.id_assignment = assgn.id_assignment
     AND assgn.id_assignment = $id_assignment
    AND insc.id_student = $id_student
    AND groups.id_group = $id_group
    AND ((grape.grade_period IS  NOT NULL) OR (extra.grade_extraordinary_examen IS NOT NULL))
        ";
    $GetAVG = $groups->getAssistanceDetails($sqlGetAVG);
    if (!empty($GetAVG)) {
        $promedio = $GetAVG[0]->grade_period;
    } else {
        $promedio = "-";
    }

    $sqlGetAVG2 = "SELECT 
CASE 
WHEN grape.grade_period_calc IS NULL AND extra.grade_extraordinary_examen IS NULL  THEN '-'
WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
WHEN grape.grade_period_calc IS NOT NULL AND extra.grade_extraordinary_examen IS NULL  THEN grape.grade_period_calc
WHEN grape.grade_period_calc IS NULL AND extra.grade_extraordinary_examen IS NOT NULL  THEN extra.grade_extraordinary_examen
END
AS 'grade_period_calc'
 FROM school_control_ykt.assignments AS assgn
INNER JOIN school_control_ykt.inscriptions AS insc
INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = insc.id_group
INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period_calendar
INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assgn.id_assignment = fga.id_assignment AND fga.id_student = insc.id_student
INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON extra.id_grade_period = grape.id_grade_period
WHERE fga.id_assignment = assgn.id_assignment
 AND assgn.id_assignment = $id_assignment
AND insc.id_student = $id_student
AND groups.id_group = $id_group
AND ((grape.grade_period IS  NOT NULL) OR (extra.grade_extraordinary_examen IS NOT NULL))
    ";
    $GetAVG2 = $groups->getAssistanceDetails($sqlGetAVG2);
    if (!empty($GetAVG2)) {
        $grade_period_calc = $GetAVG2[0]->grade_period_calc;
    } else {
        $grade_period_calc = "-";
    }
    $sql_get_assignment_details = "
    SELECT groups.group_code, subject.name_subject, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
    FROM school_control_ykt.assignments AS assignment 
    INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON assignment.id_group = groups.id_group
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = assignment.no_teacher
    WHERE assignment.id_assignment = '$id_assignment'";

    $getAssignmentDetails = $groups->getAssistanceDetails($sql_get_assignment_details);
    $group_code = $getAssignmentDetails[0]->group_code;
    $subject_name = $getAssignmentDetails[0]->name_subject;
    $teacher_name = $getAssignmentDetails[0]->teacher_name;

    $sql_getGradePeriod = "SELECT grape.*
    FROM iteach_grades_quantitatives.final_grades_assignment AS fga
    INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade
    WHERE grape.id_period_calendar = '$id_period_calendar' AND fga.id_assignment = $id_assignment AND fga.id_student = $id_student";
    $criteriaDetailsInfo = $groups->getAssistanceDetails($sql_getGradePeriod);
    if (!empty($criteriaDetailsInfo)) {
        $id_grade_period = $criteriaDetailsInfo[0]->id_grade_period;
    } else {
        $id_grade_period = "0";
    }

    $criteriaDetails = $groups->getAssistanceDetails($sql_getGradePeriod);


    $sql_criteria_details = "SELECT CASE
    WHEN ep.manual_name IS NULL THEN evs.evaluation_name
    WHEN ep.manual_name = '' THEN evs.evaluation_name
    ELSE ep.manual_name
    END
    AS criteria_name, gec.grade_evaluation_criteria_teacher, percal.no_period , percal.id_period_calendar, percentage
    FROM iteach_grades_quantitatives.grades_evaluation_criteria as gec
    INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan
    INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON ep.id_evaluation_source = evs.id_evaluation_source
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON ep.id_period_calendar = percal.id_period_calendar
    WHERE `id_grade_period` = '$id_grade_period' AND ep.evaluation_type_id = 1";


    $criteriaDetails = $groups->getAssistanceDetails($sql_criteria_details);

    if (!empty($criteriaDetails)) {
        $id_period_calendar = $criteriaDetails[0]->id_period_calendar;

        $sqlcheckIfExist = "SELECT * FROM iteach_grades_quantitatives.grade_period_commentary WHERE id_grade_period = '$id_grade_period' AND active = 1";
        $checkIfExist = $groups->getAssistanceDetails($sqlcheckIfExist);
        if (!empty($checkIfExist)) {
            $commentary = $checkIfExist[0]->commentary;
            $checked = $checkIfExist[0]->checked;
            if ($checked == 1) {
                $attr_chcbx = "checked";
            } else {
                $attr_chcbx = "";
            }
        } else {
            $commentary = "";
        }

        $period = $criteriaDetails[0]->no_period;
        $strUL .= '<h4><strong>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</strong></h4>';
        $strUL .= '<h5><strong>' . $subject_name . ' | ' . $teacher_name . ' | Periodo: ' . $period . '</strong></h5>';
        $strUL .= '<br>';
        $strUL .= '<br>';
        $strUL .= "<div class='table-responsive'>";
        $strUL .= "<table class='table table-bordered table-striped table-hover'>";
        $strUL .= "<thead class='thead-dark'>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:white !important;'>Criterio</th>";
        $strUL .= "<th style='color:white !important;'>Porcentaje</th>";
        $strUL .= "<th style='color:white !important;'>Calificación</th>";
        $strUL .= "</tr>";
        $strUL .= "</thead>";
        $strUL .= "<tbody>";

        foreach ($criteriaDetails as $criteria) {
            $criteria_name = $criteria->criteria_name;
            $grade_evaluation_criteria_teacher = $criteria->grade_evaluation_criteria_teacher;
            $percentage = $criteria->percentage;
            //--- --- ---//
            $strUL .= "<tr>";
            $strUL .= "<th>" . $criteria_name . "</th>";

            $strUL .= "<th>" . $percentage . "</th>";
            $strUL .= "<td>" . $grade_evaluation_criteria_teacher . "</td>";
            $strUL .= "</tr>";
        }

        $strUL .= "</tbody>";
        $strUL .= "<tfoot>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:black !important;'>Promedio</th>";
        $strUL .= "<th bgcolor='#d4d4d4'></th>";
        $strUL .= "<th style='color:black !important;'>" . $promedio . "</th>";
        $strUL .= "</tr>";
        $strUL .= "<tr>";
        $strUL .= "<th style='color:black !important;'>Promedio Dinámico</th>";
        $strUL .= "<th bgcolor='#d4d4d4'></th>";
        $strUL .= "<th style='color:black !important;'>" . $grade_period_calc . "</th>";
        $strUL .= "</tr>";
        $strUL .= "</tfoot>";
        $strUL .= "</table>";
        if ($grants > 14) {
            $strUL .= "<br>";
            $strUL .= "<br>";
            $strUL .= '<div class="form-group">';
            $strUL .= '<label for="exampleFormControlInput1">Agregar comentario</label>';
            $strUL .= '<div>';
            $strUL .= '<input type="email" data-id-period="' . $id_grade_period . '" value="' . $commentary . '" class="form-control" id="PeriodGradeCommentary"  placeholder="Ingrese un comentario">';
            $strUL .= '<a title="Ver y/o editar calificaciones" href="evaluaciones.php?id_assignment=' . $id_assignment . '&id_period_calendar=' . $id_period_calendar . '" target="_blank" data-id-grade-period="' . $id_grade_period . '" class="btn btn-default seeQualifications"><i class="fas fa-edit"></i></a>';
            $strUL .= '<button title="Guardar cambios" type="button" data-id-grade-period="' . $id_grade_period . '" class="btn btn-success addPeriodGradeCommentary"><i class="fas fa-save"></i></button>';
            $strUL .= '<button title="Eliminar observación" type="button" data-id-grade-period="' . $id_grade_period . '" class="btn btn-danger deletePeriodGradeCommentary"><i class="fas fa-trash-alt"></i></button><br><br>';
            $strUL .= '</div>';
            $strUL .= '</div>';
        } else {
            if (!empty($checkIfExist)) {
                $strUL .= "<br>";
                $strUL .= "<br>";
                $strUL .= '<div class="form-group">';

                $strUL .= '<div class="custom-control custom-checkbox">';
                $strUL .= '<input type="checkbox" ' . $attr_chcbx . ' class="custom-control-input checkEPCommentary" id="checkEPCommentary" data-id-grade-period="' . $id_grade_period . '">';
                $strUL .= '<label  data-toggle="tooltip" data-placement="top" title="Marque esta casilla si ya atendió la observación" class="custom-control-label" for="checkEPCommentary"><strong>Observación: </strong>' . $commentary . '</label>';
                $strUL .= '</div><br>';
                $strUL .= '<div>';
                $strUL .= '<a title="Ver y/o editar calificaciones" href="evaluaciones.php?id_assignment=' . $id_assignment . '&id_period_calendar=' . $id_period_calendar . '" target="_blank" data-id-grade-period="' . $id_grade_period . '" class="btn btn-default seeQualifications"><i class="fas fa-edit"></i></a><br><br>';
                $strUL .= '</div>';
                $strUL .= '</div>';
                # code...
            }
        }
        $strUL .= "</div>";
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $strUL
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function saveGradePeriodCommentary()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_grade_period = $_POST['id_grade_period'];
    $commentary = $_POST['commentary'];

    $Attendance = new Attendance;
    $groups = new Groups;

    $sqlcheckIfExist = "SELECT * FROM iteach_grades_quantitatives.grade_period_commentary WHERE id_grade_period = '$id_grade_period' AND active = 1";
    $checkIfExist = $groups->getAssistanceDetails($sqlcheckIfExist);

    if (empty($checkIfExist)) {
        $sqlInsertCommentary = "INSERT INTO iteach_grades_quantitatives.grade_period_commentary 
    (id_grade_period,
    commentary,
    active,
    datelog,
    no_teacher)
    VALUES ('$id_grade_period', '$commentary', 1,
    NOW(), $_SESSION[colab]
    )";

        if ($Attendance->saveAttendance($sqlInsertCommentary)) {
            $data = array(
                'response' => true,
                'message'                => 'Comentario guardado correctamente',
            );
        } else {
            $data = array(
                'response' => false,
                'message'                => 'Error al guardar comentario',
            );
        }
    } else {
        $sqlInsertCommentary = "UPDATE iteach_grades_quantitatives.grade_period_commentary SET
    commentary = '$commentary',
    active = 1,
    datelog = NOW(),
    no_teacher = $_SESSION[colab],
    checked = 0
    WHERE id_grade_period = '$id_grade_period' AND active = 1";

        if ($Attendance->saveAttendance($sqlInsertCommentary)) {
            $data = array(
                'response' => true,
                'message'                => 'Comentario actualizado correctamente',
            );
        } else {
            $data = array(
                'response' => false,
                'message'                => 'Error al actualizar comentario',
            );
        }
    }


    //--- --- ---//

    //--- --- ---//


    echo json_encode($data);
}
function deleteGradePeriodCommentary()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_grade_period = $_POST['id_grade_period'];

    $Attendance = new Attendance;
    $groups = new Groups;


    $sqlInsertCommentary = "UPDATE iteach_grades_quantitatives.grade_period_commentary SET
    active = 0,
    datelog = NOW(),
    no_teacher = $_SESSION[colab],
    checked = 0
    WHERE id_grade_period = '$id_grade_period'";

    if ($Attendance->saveAttendance($sqlInsertCommentary)) {
        $data = array(
            'response' => true,
            'message'                => 'Comentario eliminado correctamente',
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'Error al eliminar comentario',
        );
    }


    //--- --- ---//

    //--- --- ---//


    echo json_encode($data);
}
function updateCheckedGradePeriodCommentary()
{

    $grants = $_SESSION['grantsITEQ'];
    $id_grade_period = $_POST['id_grade_period'];
    $checked = $_POST['checked'];

    $Attendance = new Attendance;
    $groups = new Groups;


    $sqlInsertCommentary = "UPDATE iteach_grades_quantitatives.grade_period_commentary SET
    datelog = NOW(),
    no_teacher = $_SESSION[colab],
    checked = $checked
    WHERE id_grade_period = '$id_grade_period'";

    if ($Attendance->saveAttendance($sqlInsertCommentary)) {
        $data = array(
            'response' => true,
            'message'                => 'Comentario eliminado correctamente',
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'Error al eliminar comentario',
        );
    }


    //--- --- ---//

    //--- --- ---//


    echo json_encode($data);
}

function getGroupAssistanceDetails()
{

    $grants = $_SESSION['grantsITEQ'];
    $str_id_attendance = $_POST['str_id_attendance'];
    $arr_ids = explode(",", $str_id_attendance);
    $html = "";
    $groups = new Groups;

    for ($i = 0; $i < ((count($arr_ids) - 1)); $i++) {
        $sql_attendance = "SELECT DISTINCT sbj.name_subject, gps.group_code, CONCAT(std.name, ' ',std.lastname) AS student_name, att.apply_date
        FROM attendance_records.attendance_record AS atr
        INNER JOIN  attendance_records.attendance_index AS att ON atr.id_attendance_index = att.id_attendance_index
        INNER JOIN school_control_ykt.assignments AS asg ON att.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.students AS std ON atr.id_student =std.id_student
        WHERE atr.id_attendance_record = '$arr_ids[$i]'";


        $getDetails = $groups->getAssistanceDetails($sql_attendance);

        if (!empty($getDetails)) {
            foreach ($getDetails as $details) {
                $date = explode(" ", $details->apply_date);
                $date_str = $date[0];
                $html .= '<div class="card"><div class="card" style="width: 18rem;"><div class="card-body">';
                $html .= '<h5 class="card-title">' . $details->student_name . '</h5>';
                $html .= '<p class="card-text">' . $details->group_code . '</p>';
                $html .= '<p class="card-text">' . $details->name_subject . '</p>';
                $html .= '<p class="card-text">' . $date_str . '</p>';
                $html .= '</div>';
            }
        } else {
        }
    }
    $strUL = "";



    $data = array(
        'response' => true,
        'html'                => $html
    );
    //--- --- ---//

    //--- --- ---//


    echo json_encode($data);
}

function saveAttendance()
{

    $data = $_POST['data'];
    $compulsory_class = $_POST['compulsory_class'];
    $class_block = $_POST['class_block'];
    $id_assignment = $_POST['id_assignment'];
    $today = date('Y-m-d H:i:s');
    $today_day = date('Y-m-d');
    $grants = $_SESSION['grantsITEQ'];

    if (!empty($data)) {
        $Attendance = new Attendance;
        $groups = new Groups;
        $another_passlist = $Attendance->updateAnotherPasslist($id_assignment, $today_day, $class_block);

        if (($grants & 8)) {
            $sql_getAssignmentInfo = "SELECT * FROM school_control_ykt.assignments WHERE id_assignment = '$id_assignment'";
            $getAssignmentInfo = $groups->getGroupFromTeachers($sql_getAssignmentInfo);
            if (!empty($getAssignmentInfo)) {
                $no_teacher = $getAssignmentInfo[0]->no_teacher;
            }
            $stmt = "INSERT INTO attendance_records.attendance_index 
        (id_assignment, obligatory, apply_date, teacher_passed_attendance, class_block, valid_assistance, Origen) 
        VALUES ('$id_assignment', $compulsory_class, '$today', '$no_teacher', $class_block, '1', '$_SESSION[colab]' )";
        } else {
            $stmt = "INSERT INTO attendance_records.attendance_index 
        (id_assignment, obligatory, apply_date, teacher_passed_attendance, class_block, valid_assistance, Origen) 
        VALUES ('$id_assignment', $compulsory_class, '$today', '$_SESSION[colab]', $class_block, '1', 'True' )";
        }

        $attendance = $Attendance->saveAttendance($stmt);

        if ($attendance) {
            $lastID = $Attendance->getLastIDAttendanceIndex();
            foreach ($data as $value) {
                $id_student = $value['id_student'];
                $present = $value['present'];
                $incident_id = $value['incident_id'];

                $stmt1 = "INSERT INTO attendance_records.attendance_record (id_attendance_index, attend, id_student, incident_id) VALUES ('$lastID', $present, '$id_student', '$incident_id')";

                $attendance = $Attendance->saveAttendance($stmt1);
            }
        }

        //--- --- ---//
        $response = array(
            'response' => true,
            'message'                => 'Se guardó la asistencia correctamente <br/> <b style="color: #F96647">ID: ' . $lastID . '</b>'
        );
        //--- --- ---//
    }

    echo json_encode($response);
}

function searchAttendance()
{
    $date = $_POST['date'];
    $id_assignment = $_POST['id_assignment'];
    $class_block = $_POST['class_block'];
    $data = array();

    $Attendance = new Attendance;
    $infoGeneral = $Attendance->getInfoGeneralLastAttendanceDate($id_assignment, $date, $class_block);
    if (!empty($infoGeneral)) {
        //--- --- ---//
        $res = $Attendance->getRecordsAttendance($infoGeneral->id_attendance_index);
        if (!empty($res)) {
            //--- --- ---//
            $today = date('Y-m-d');
            $editable = false;
            if (strtotime($today) == strtotime($date)) {
                $editable = true;
            }
            //--- --- ---//
            $records = array();
            foreach ($res as $value) {
                $records[] = array(
                    'id_student' => $value->id_student,
                    'student_code' => $value->student_code,
                    'incident_id' => $value->incident_id,
                    'name_student' => $value->name_student,
                    'present' => $value->attend
                );
            }
            //--- --- ---//
            $data = array(
                'response' => true,
                'id_attendance_index' => $infoGeneral->id_attendance_index,
                'obligatory' => $infoGeneral->obligatory,
                'editable' => $editable,
                'listIncidents' => $Attendance->getListIncidents(),
                'records' => $records
            );
            //--- --- ---//
        }
        //--- --- ---//
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron asistencias :('
        );
    }

    echo json_encode($data);
}

function updateAttendance()
{

    $data = $_POST['data'];
    $compulsory_class = $_POST['compulsory_class'];
    $id_attendance_index = $_POST['id_attendance_index'];

    if (!empty($data)) {
        $Attendance = new Attendance;

        $stmt = "UPDATE attendance_records.attendance_index SET obligatory = $compulsory_class WHERE id_attendance_index = $id_attendance_index";

        $attendance = $Attendance->saveAttendance($stmt);

        if ($attendance) {
            foreach ($data as $value) {
                $id_student = $value['id_student'];
                $present = $value['present'];
                $incident_id = $value['incident_id'];

                $stmt1 = "UPDATE attendance_records.attendance_record SET incident_id = $incident_id, attend = $present WHERE id_attendance_index = $id_attendance_index AND id_student = $id_student";

                $Attendance->saveAttendance($stmt1);
            }
        }

        //--- --- ---//
        $response = array(
            'response' => true,
            'message'                => 'Se guardó la asistencia correctamente'
        );
        //--- --- ---//
    }

    echo json_encode($response);
}

function StudentContactInfo()
{

    $id_student = $_POST['id_student'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT student.id_student,  
        student.id_family, student.student_code,
        CONCAT(student.lastname, ' ', student.name) AS name_student,
        CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS mail,
        CASE WHEN fam.attorney = 1 THEN (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS cell_phone,
        CASE 
        WHEN fam.attorney = 1 THEN (SELECT CONCAT(lastname, ' ', name) AS name_student FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT CONCAT(lastname, ' ', name) AS name_student FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS attorney_name,
        (SELECT CONCAT(street, ' ', ext_number, (CASE WHEN int_number IS NULL THEN ',' WHEN int_number = 0 THEN ',' ELSE CONCAT(' int. ',int_number,', ') END),  colony, ', ', delegation, '. ', postal_code) 
        FROM families_ykt.addresses_families WHERE id_family_address = fam.id_family_address) AS direction 

        FROM school_control_ykt.students AS student
        INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
        WHERE student.id_student = $id_student";
    //echo $sql_check;
    $html_student_list = '';
    $html_student_list .= '<h2>INFORMACIÓN DE CONTÁCTO DE ESTUDIANTE</h2>';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        //$dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');

        $name_student = $result_check[0]->name_student;
        $student_code = $result_check[0]->student_code;
        $student_addrress = $result_check[0]->direction;
        $html_student_list .= '<h3>' . $student_code . ' | ' . $name_student . '</h3>';
        $html_student_list .= '<h5>Dirección: ' . $student_addrress . '</h5>';
        $html_student_list .= '<div class="table-responsive" id="tabResults">';
        $html_student_list .= '<table class="table align-items-center table-flush" id="tStudents">';
        $html_student_list .= '<thead class="thead-dark">';
        $html_student_list .= '<tr>';
        $html_student_list .= '<th style="color:white">NOMBRE DE CONTÁCTO</th>';
        $html_student_list .= '<th style="color:white">NÚMERO TELEFÓNICO</th>';
        $html_student_list .= '<th style="color:white">CORREO ELECTRÓNICO</th>';

        $html_student_list .= '</tr>';
        $html_student_list .= '</thead>';
        $html_student_list .= '<tbody class="list">';

        foreach ($result_check as $students) {

            $html_student_list .= '<tr>';
            $html_student_list .= '<td>' . $students->attorney_name . '</td>';
            $html_student_list .= '<td>' . $students->cell_phone . '</td>';
            $html_student_list .= '<td>' . $students->mail . '</td>';
            $html_student_list .= '</tr>';
        }
        $html_student_list .= '</tbody>';
        $html_student_list .= '</table>';
        $html_student_list .= '</div>';
        $html_student_list .= '<script> var tf = new TableFilter(document.querySelector("#tabResults");tf.init();</script>';
        $data = array(
            'response' => true,
            'message' => '',
            'data' => $html_student_list
        );
    } else {
        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<h1>No se encontró información de contacto :(</h1>';
        $html_student_list .= '</div>';
        $data = array(
            'response' => true,
            'message' => '',
            'data' => $html_student_list
        );
    }




    echo json_encode($data);
}

function updateGroupStudent()
{
    $id_student = $_POST['id_student'];
    $id_group = $_POST['id_group'];

    $students = new Students;

    $students->updateGroupStudent($id_student, $id_group);

    //--- --- ---//
    $response = array(
        'response' => true,
        'message'                => 'Se actualizó el grupo correctamente'
    );
    //--- --- ---//


    echo json_encode($response);
}
