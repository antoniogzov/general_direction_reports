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

    $id_student = $_POST['id_student'];
    $indexs = explode(",", $_POST['indexs']);
    $indexs = array_reverse($indexs);


    $rows = "";
    $name_student = "";
    $student_code = "";
    for ($i = 0; $i < (count($indexs)); $i++) {
        $stmt = "SELECT DISTINCT
        CONCAT(stu.name, ' ', stu.lastname) AS name_student, stu.student_code,
        CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
        class_block, apply_date, inc.incident,
        sbj.name_subject
         FROM attendance_records.attendance_index AS ati
         LEFT JOIN attendance_records.attendance_record AS atr ON atr.id_attendance_index = ati.id_attendance_index
         INNER JOIN attendance_records.incidents_attendance AS inc ON inc.incident_id = atr.incident_id
         INNER JOIN school_control_ykt.students AS stu ON stu.id_student 
         INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = ati.id_assignment
         INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
         INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
         WHERE stu.id_student =  '$id_student' AND ati.id_attendance_index = '$indexs[$i]' AND ati.valid_assistance = 1
         
         ";
        $getAttendanceInfo = $groups->getGroupFromTeachers($stmt);
        if (!empty($getAttendanceInfo)) {
            $counter++;
            $name_subject = $getAttendanceInfo[0]->name_subject;
            $teacher_name = $getAttendanceInfo[0]->teacher_name;
            $class_block = $getAttendanceInfo[0]->class_block;
            $apply_date = explode(" ", $getAttendanceInfo[0]->apply_date);
            $incident = $getAttendanceInfo[0]->incident;
            $time_apply = $apply_date[1];


            $rows .= "<tr>";
            $rows .= '<td>' . $name_subject . '</td>';
            $rows .= '<td>' . $teacher_name . '</td>';
            $rows .= '<td>' . $class_block . '</td>';
            $rows .= '<td>' . $incident . '</td>';
            $rows .= '<td>' . $getAttendanceInfo[0]->apply_date . '</td>';
            $rows .= '</tr>';
            $name_student = $getAttendanceInfo[0]->name_student;
            $student_code = $getAttendanceInfo[0]->student_code;
        }
    }
    $html_table = "<h2>" . $student_code . " | " . $name_student . "</h2>";
    $html_table .= '<table class="table table-striped">';
    $html_table .= '<thead>';
    $html_table .= '<tr>';
    $html_table .= '<th>MATERIA</th>';
    $html_table .= '<th>PROFESOR</th>';
    $html_table .= '<th>BLOQUE</th>';
    $html_table .= '<th>TIPO DE INCIDENCIA</th>';
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
