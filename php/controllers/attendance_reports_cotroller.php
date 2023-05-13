<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';
include '../models/evaluations.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getAttends()
{
    $groups = new Groups;
    $attendance = new Attendance;

    $counter = 0;

    $indexs = explode(",", $_POST['indexs']);
    $indexs = array_reverse($indexs);


    $rows = "";
    $name_student = "";
    $student_code = "";
    for ($i = 0; $i < (count($indexs)); $i++) {
        $stmt = "SELECT 
        CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
        class_block, apply_date,
        sbj.name_subject
         FROM attendance_records.attendance_index AS ati
         INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = ati.id_assignment
         INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
         INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
         WHERE  ati.id_attendance_index = '$indexs[$i]' AND ati.valid_assistance = 1
         
         ";
        $getAttendanceInfo = $groups->getGroupFromTeachers($stmt);
        if (!empty($getAttendanceInfo)) {
            $counter++;
            $name_subject = $getAttendanceInfo[0]->name_subject;
            $teacher_name = $getAttendanceInfo[0]->teacher_name;
            $class_block = $getAttendanceInfo[0]->class_block;
            $apply_date = explode(" ", $getAttendanceInfo[0]->apply_date);
            $time_apply = $apply_date[1];


            $rows .= "<tr>";
            $rows .= '<td>' . $name_subject . '</td>';
            $rows .= '<td>' . $teacher_name . '</td>';
            $rows .= '<td>' . $class_block . '</td>';
            $rows .= '<td>' . $getAttendanceInfo[0]->apply_date . '</td>';
            $rows .= '</tr>';
        }
    }
    /* $html_table = "<h2>" . $student_code . " | " . $name_student . "</h2>"; */
    $html_table = '<table class="table table-striped">';
    $html_table .= '<thead>';
    $html_table .= '<tr>';
    $html_table .= '<th>MATERIA</th>';
    $html_table .= '<th>PROFESOR</th>';
    $html_table .= '<th>BLOQUE</th>';
    $html_table .= '<th>FECHA Y HORA DE REGISTRO</th>';
    $html_table .= '</tr>';
    $html_table .= '</thead>';
    $html_table .= '<tbody>';
    $html_table .= $rows;
    $html_table .= '</tbody>';
    $html_table .= '</table>';



    if ($counter > 0) {
        $data = array(
            'response' => true,
            'html' => $html_table,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no se encontraron registros para el periodo epecificado.',
        );
    }

    echo json_encode($data);
}

function getAttendanceReportDetails2()
{
    $groups = new Groups;
    $attendance = new Attendance;

    $counter = 0;

    $indexs = explode(",", $_POST['indexs']);
    $indexs = array_reverse($indexs);


    $rows = "";
    $name_student = "";
    $student_code = "";
    for ($i = 0; $i < (count($indexs)); $i++) {
        $stmt = "SELECT 
        CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
        class_block, apply_date,
        sbj.name_subject
         FROM attendance_records.attendance_index AS ati
         INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = ati.id_assignment
         INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
         INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
         WHERE  ati.id_attendance_index = '$indexs[$i]' AND ati.valid_assistance = 1
         
         ";
        $getAttendanceInfo = $groups->getGroupFromTeachers($stmt);
        if (!empty($getAttendanceInfo)) {
            $counter++;
            $name_subject = $getAttendanceInfo[0]->name_subject;
            $teacher_name = $getAttendanceInfo[0]->teacher_name;
            $class_block = $getAttendanceInfo[0]->class_block;
            $apply_date = explode(" ", $getAttendanceInfo[0]->apply_date);
            $time_apply = $apply_date[1];


            $rows .= "<tr>";
            $rows .= '<td>' . $name_subject . '</td>';
            $rows .= '<td>' . $teacher_name . '</td>';
            $rows .= '<td>' . $class_block . '</td>';
            $rows .= '<td>' . $getAttendanceInfo[0]->apply_date . '</td>';
            $rows .= '</tr>';
        }
    }
    /* $html_table = "<h2>" . $student_code . " | " . $name_student . "</h2>"; */
    $html_table = '<table class="table table-striped">';
    $html_table .= '<thead>';
    $html_table .= '<tr>';
    $html_table .= '<th>MATERIA</th>';
    $html_table .= '<th>PROFESOR</th>';
    $html_table .= '<th>BLOQUE</th>';
    $html_table .= '<th>FECHA Y HORA DE REGISTRO</th>';
    $html_table .= '</tr>';
    $html_table .= '</thead>';
    $html_table .= '<tbody>';
    $html_table .= $rows;
    $html_table .= '</tbody>';
    $html_table .= '</table>';



    if ($counter > 0) {
        $data = array(
            'response' => true,
            'html' => $html_table,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no se encontraron registros para el periodo epecificado.',
        );
    }

    echo json_encode($data);
}

function getAttendanceReportDetails()
{
    $groups = new Groups;

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $id_assignment = $_POST['id_assignment'];
    $id_student = $_POST['id_student'];


    $stmt = "SELECT  stud.student_code, CONCAT(stud.name, ' ',stud.lastname) AS student_name, gps.group_code
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        WHERE stud.id_student =  '$id_student'";
    $getGroups = $groups->getGroupFromTeachers($stmt);

    $html_sweet_alert = '';
    $student_code = $getGroups[0]->student_code;
    $student_name = mb_strtoupper($getGroups[0]->student_name);
    $group_code = $getGroups[0]->group_code;

    $html_sweet_alert .= '<b>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</b><br><br>';
    $html_sweet_alert .= '<div style="height: 500px; overflow: auto" >';

    $stmt = "SELECT atr.id_attendance_record, ati.apply_date, ati.class_block, atr.attend,
    stud.student_code,atr.incident_id, CONCAT(stud.name, ' ',stud.lastname) AS student_name, 
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    sbj.name_subject, gps.group_code, iat.incident
        FROM school_control_ykt.students AS stud 
        INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        WHERE 
        stud.id_student =$id_student
        AND ati.id_assignment =$id_assignment 
        AND ati.apply_date >= '$start_date' 
        AND ati.apply_date <= '$end_date'
        AND  ((atr.attend = 1) OR (atr.apply_justification = 1 OR iat.apply_justification = 1))
        AND ati.valid_assistance = 1
        AND ati.obligatory = 1
        ORDER BY ati.apply_date DESC
    ";


    $getAttendances = $groups->getGroupFromTeachers($stmt);

    foreach ($getAttendances as $attends) {

        $teacher_name = $attends->teacher_name;
        $name_subject = $attends->name_subject;
        $incident_id = $attends->incident_id;
        $apply_date = $attends->apply_date;
        $class_block = $attends->class_block;
        $attend = $attends->attend;
        $incident = strtoupper($attends->incident);
        $class_attend = "";
        if (($attends->iat_apply_justification == 1) || ($attends->atr_apply_justification == 1)) {
            $class_attend = "dark";
        } else if ($attend == 1) {
            $class_attend = "success";
        } else {
            $class_attend = "danger";
        }

        $html_sweet_alert .= '<li class="list-group-item list-group-item-' . $class_attend . '">';
        $html_sweet_alert .= '<p>' . $name_subject . ' | ' . $apply_date . ' | BLOQUE: ' . $class_block . '</p>';
        $html_sweet_alert .= '<p> Registró: ' . $teacher_name . ' <br> Tipo de incidencia: ' . $incident . '</p>';
        $html_sweet_alert .= '</li><br>';
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

function getAttendanceReportDetailsAbsences()
{
    $groups = new Groups;

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $id_assignment = $_POST['id_assignment'];
    $id_student = $_POST['id_student'];


    $stmt = "SELECT  stud.student_code, CONCAT(stud.name, ' ',stud.lastname) AS student_name, gps.group_code
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        WHERE stud.id_student =  '$id_student'";
    $getGroups = $groups->getGroupFromTeachers($stmt);

    $html_sweet_alert = '';
    $student_code = $getGroups[0]->student_code;
    $student_name = mb_strtoupper($getGroups[0]->student_name);
    $group_code = $getGroups[0]->group_code;

    $html_sweet_alert .= '<b>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</b><br><br>';
    $html_sweet_alert .= '<div style="height: 500px; overflow: auto" >';

    $stmt = "SELECT atr.id_attendance_record, ati.apply_date, ati.class_block, atr.attend,
    stud.student_code,atr.incident_id, CONCAT(stud.name, ' ',stud.lastname) AS student_name, 
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    sbj.name_subject, gps.group_code, iat.incident
        FROM school_control_ykt.students AS stud 
        INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        WHERE 
        stud.id_student =$id_student
        AND ati.id_assignment =$id_assignment 
        AND ati.apply_date >= '$start_date' 
        AND ati.apply_date <= '$end_date'
        AND  ((atr.attend = 0) AND (atr.apply_justification = 0 OR iat.apply_justification = 0))
        AND ati.valid_assistance = 1
        AND ati.obligatory = 1
        ORDER BY ati.apply_date DESC
    ";


    $getAttendances = $groups->getGroupFromTeachers($stmt);

    foreach ($getAttendances as $attends) {

        $teacher_name = $attends->teacher_name;
        $name_subject = $attends->name_subject;
        $incident_id = $attends->incident_id;
        $apply_date = $attends->apply_date;
        $class_block = $attends->class_block;
        $attend = $attends->attend;
        $incident = strtoupper($attends->incident);
        $class_attend = "";
        if (($attends->iat_apply_justification == 1) || ($attends->atr_apply_justification == 1)) {
            $class_attend = "dark";
        } else if ($attend == 1) {
            $class_attend = "success";
        } else {
            $class_attend = "danger";
        }

        $html_sweet_alert .= '<li class="list-group-item list-group-item-' . $class_attend . '">';
        $html_sweet_alert .= '<p>' . $name_subject . ' | ' . $apply_date . ' | BLOQUE: ' . $class_block . '</p>';
        $html_sweet_alert .= '<p> Registró: ' . $teacher_name . ' <br> Tipo de incidencia: ' . $incident . '</p>';
        $html_sweet_alert .= '</li><br>';
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


function getAttendanceReportDetailsAll()
{
    $groups = new Groups;

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $id_assignment = $_POST['id_assignment'];
    $id_student = $_POST['id_student'];


    $stmt = "SELECT  stud.student_code, CONCAT(stud.name, ' ',stud.lastname) AS student_name, gps.group_code
        FROM school_control_ykt.students AS stud
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = stud.group_id
        WHERE stud.id_student =  '$id_student'";
    $getGroups = $groups->getGroupFromTeachers($stmt);

    $html_sweet_alert = '';
    $student_code = $getGroups[0]->student_code;
    $student_name = mb_strtoupper($getGroups[0]->student_name);
    $group_code = $getGroups[0]->group_code;

    $html_sweet_alert .= '<b>' . $student_code . ' | ' . $student_name . ' | ' . $group_code . '</b><br><br>';
    $html_sweet_alert .= '<div style="height: 400px; overflow: auto" >';

    $stmt = "SELECT atr.id_attendance_record, ati.apply_date, ati.class_block, atr.attend,
    stud.student_code,atr.incident_id, CONCAT(stud.name, ' ',stud.lastname) AS student_name,
    CASE 
    WHEN (atr.attend = 0) AND (atr.apply_justification = 0 OR iat.apply_justification = 0) THEN 'Ausente'
    WHEN (atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1) THEN 'Falta Justificada'
    WHEN (atr.attend = 1) THEN 'Presente'
    END
    AS student_att,
    iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    sbj.name_subject, gps.group_code, iat.incident
        FROM school_control_ykt.students AS stud 
        INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        WHERE 
        stud.id_student =$id_student
        AND ati.id_assignment =$id_assignment 
        AND ati.apply_date >= '$start_date' 
        AND ati.apply_date <= '$end_date'
        AND ati.valid_assistance = 1
        AND ati.obligatory = 1
        ORDER BY ati.apply_date DESC
    ";


    $getAttendances = $groups->getGroupFromTeachers($stmt);
    $faltas = 0;
    $asistencias = 0;
    $faltas_justificadas = 0;
    foreach ($getAttendances as $attends) {

        $teacher_name = $attends->teacher_name;
        $name_subject = $attends->name_subject;
        $student_att = $attends->student_att;
        $incident_id = $attends->incident_id;
        $apply_date = $attends->apply_date;
        $class_block = $attends->class_block;
        $attend = $attends->attend;
        $incident = strtoupper($attends->incident);
        $class_attend = "";
        if (($attends->iat_apply_justification == 1) || ($attends->atr_apply_justification == 1)) {
            $class_attend = "dark";
            $faltas_justificadas++;
        } else if ($attend == 1) {
            $class_attend = "success";
            $asistencias++;
        } else {
            $class_attend = "danger";
            $faltas++;
        }

        $html_sweet_alert .= '<li class="list-group-item list-group-item-' . $class_attend . '">';
        $html_sweet_alert .= '<p> ' . $name_subject . ' | ' . $apply_date . ' | BLOQUE: ' . $class_block . '</p>';
        $html_sweet_alert .= '<p> Registró: ' . $teacher_name . ' <br> Tipo de incidencia: ' . $incident . '</p>';
        $html_sweet_alert .= '</li><br>';
    }

    $html_sweet_alert .= '</div>';
    $html_sweet_alert .= "<p>Asistencias: <strong>" . $asistencias . "</strong></p>";
    $html_sweet_alert .= "<p>Faltas Justificadas: <strong>" . $faltas_justificadas . "</strong></p>";
    $html_sweet_alert .= "<p>Inasistencias: <strong>" . $faltas . "</strong></p>";

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
