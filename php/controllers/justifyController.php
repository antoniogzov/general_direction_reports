<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function saveJustify()
{
    $id_student = $_POST['id_student'];
    $id_excuse_types = $_POST['id_excuse_types'];
    $arr_dates_apply = explode(" - ", $_POST['dates_apply']);
    $teacher_commit = $_POST['teacher_commit'];
    $justifyed = $_POST['justifyed'];

    $date_time_start = $arr_dates_apply[0];
    $date_time_end = $arr_dates_apply[1];

    $arr_date_time_start = explode(" ", $date_time_start);
    $arr_date_time_end = explode(" ", $date_time_end);
    $str_date_start = explode("/", $arr_date_time_start[0]);
    $str_time_start = $arr_date_time_start[1] . " " . $arr_date_time_start[2];
    $time_start = date("H:i", strtotime($str_time_start));

    $str_date_end = explode("/", $arr_date_time_end[0]);
    $str_time_end = $arr_date_time_end[1] . " " . $arr_date_time_end[2];
    $time_end = date("H:i", strtotime($str_time_end));

    $date_start = $str_date_start[2] . "-" . $str_date_start[1] . "-" . $str_date_start[0];
    $date_end = $str_date_end[2] . "-" . $str_date_end[1] . "-" . $str_date_end[0];

    $date_time_start_sql = $date_start . " " . $time_start;
    $date_time_end_sql = $date_end . " " . $time_end;



    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT ae.active_excuse AS active, aeb.*
     FROM absence_excuse.absences_excuse_breakdown as aeb 
     INNER JOIN absence_excuse.absences_excuse AS ae ON ae.id_absences_excuse = aeb.id_absences_excuse
     WHERE  ae.active_excuse = 1 AND absence_day >= '$date_time_start_sql' AND absence_day <= '$date_time_end_sql' AND id_student = $id_student ";
    //echo $sql_check;
    $result_check = $groups->getGroupFromTeachers($sql_check);
    if (count($result_check) == 0) {
        $stmt = "INSERT INTO absence_excuse.absences_excuse (
            id_excuse_types,
            id_student,
            no_teacher_registered,
            start_date,
            end_date,
            logdate,
            teacher_commit
            ) VALUES (
            $id_excuse_types,
            $id_student,
            '$_SESSION[colab]',
            '$date_time_start_sql',
            '$date_time_end_sql',
            NOW(),
            '$teacher_commit'
            )";

        if (!empty($attendance->saveAttendance($stmt))) {
            $last_id = $attendance->getLastId();
            // echo 'last_id: ' . $last_id;
            $begin = new DateTime($date_time_start_sql);
            $end = new DateTime($date_time_end_sql);


            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $arr_dias = array();

            foreach ($period as $dt) {

                $arr_dias[] = $dt->format("Y-m-d H:i:s");
            }
            //print_r($arr_dias);

            for ($i = 0; $i < (count($arr_dias) - 1); $i++) {
                $stmt = "INSERT INTO absence_excuse.absences_excuse_breakdown (
                    id_absences_excuse,
                    absence_day,
                    apply_excuse,
                    active_excuse,
                    logdate
                    ) VALUES (
                    $last_id,
                    '$arr_dias[$i]',
                    '$justifyed',
                    '1',
                    NOW()
                    )";
                $attendance->saveAttendance($stmt);
            }
            $stmt = "INSERT INTO absence_excuse.absences_excuse_breakdown (
                    id_absences_excuse,
                    absence_day,
                    apply_excuse,
                    active_excuse,
                    logdate
                    ) VALUES (
                    $last_id,
                    '$date_time_end_sql',
                    '$justifyed',
                    '1',
                    NOW()
                    )";
            $attendance->saveAttendance($stmt);

            $data = array(
                'response' => true,
                'last_id' => $last_id
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
    } else {
        $html_fechas = "<h3>Las siguientes fechas se sobreponen con las que intenta agregar</h3><br>";
        $html_fechas .= '<ul class="list-group">';

        $html_fechas .= '</ul>';
        foreach ($result_check as $result) {
            $str_date = explode(" ", $result->absence_day);
            $str_date_day = explode("-", $str_date[0]);
            $date = $str_date_day[2] . "/" . $str_date_day[1] . "/" . $str_date_day[0];
            $html_fechas .= '<li class="list-group-item">' . $date . '</li>';
        }
        $html_fechas .= '</ul>';
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Las siguientes fechas se sobreponen con las que intenta agregar',
            'fechas_sobrepuestas'                => $html_fechas
        );
        //--- --- ---//
    }


    echo json_encode($data);
}
function breakdownJustify()
{
    $id_student = $_POST['id_student'];
    $today_date = $_POST['today_date'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT ext.excuse_description, ae.*, student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name,
    CONCAT(colab.nombres_colaborador , ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS teacher_name,
    CASE 
    WHEN ae.teacher_commit = '' THEN '-' 
    WHEN ae.teacher_commit IS NULL THEN '-' 
    ELSE teacher_commit
    END AS teacher_commit2

    FROM absence_excuse.absences_excuse AS ae
    INNER JOIN school_control_ykt.students AS student ON student.id_student = ae.id_student
    INNER JOIN absence_excuse.excuse_types ext ON ae.id_excuse_types = ext.id_excuse_types
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ae.no_teacher_registered
    WHERE ae.id_student = $id_student AND active_excuse = 1";
    $getBreakdown = $groups->getGroupFromTeachers($stmt);
    if (!empty($getBreakdown)) {
        $student_name = mb_strtoupper($getBreakdown[0]->student_name);
        $student_code = $getBreakdown[0]->student_code;
        $id_student = $getBreakdown[0]->id_student;

        $html_sweet_alert = '<h3 class="text-uppercase">Alumno: ' . $student_name . '</h3>';
        $html_sweet_alert .= '<h3 class="text-uppercase">Código de Alumno: ' . $student_code . '             <button class="btn btn-icon btn-info btn-sm getStudentContactInfo" data-id_student="' . $id_student . '" type="button"><span class="btn-inner--icon"><i class="fa-solid fa-phone"></i></span></button></h3>';
        $html_sweet_alert .= '<div style="height: 600px; overflow: auto;"><table class="table align-items-center table-flush" id="tablaDesgloseAusencias">';
        $html_sweet_alert .= '<thead class="thead-dark">';
        $html_sweet_alert .= "<tr>";
        $html_sweet_alert .= "<th style='color:white !important;'>Motivo de ausencia</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Comentario</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>Fecha de inicio</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Fecha de Fin</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>Seguimiento</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Editar Días</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Borrar</th>";
        $html_sweet_alert .= "</tr>";
        $html_sweet_alert .= "</thead>";
        $html_sweet_alert .= "<tbody class='list'>";
        for ($i = 0; $i < count($getBreakdown); $i++) {
            $active_excuse = $getBreakdown[$i]->active_excuse;
            $teacher_name = $getBreakdown[$i]->teacher_name;
            $id_absences_excuse = $getBreakdown[$i]->id_absences_excuse;
            $stmt = "SELECT * FROM absence_excuse.absence_vouchers WHERE id_absences_excuse = $id_absences_excuse";
            $getAbsenceDocuments = $groups->getGroupFromTeachers($stmt);
            if (count($getAbsenceDocuments) > 0) {
                $btn_add_document = '<button title="Lista de comprobantes" class="btn btn-icon btn-success btn-sm trackingDocumentList" data-id_absences_excuse="' . $id_absences_excuse . '" data-id_student="' . $id_student . '" type="button"  data-toggle="modal" data-target="#absenceDocumentList"><span class="btn-inner--icon"><i class="fa-solid fa-folder-open"></i></span></button>';
            } else {
                $btn_add_document = '<button title="Adjuntar comprobante" class="btn btn-icon btn-success btn-sm addTrackingDocument" id="addTrackingDocument_' . $id_absences_excuse . '" data-id_absences_excuse="' . $id_absences_excuse . '" data-id_student="' . $id_student . '" type="button"  data-toggle="modal" data-target="#addAbsenceDocument"><span class="btn-inner--icon"><i id="iconAddTrackingDocument_' . $id_absences_excuse . '" class="fas fa-folder"></i></span></button>';
            }

            $html_sweet_alert .= "<tr>";
            $html_sweet_alert .= "<td>" . $getBreakdown[$i]->excuse_description . "</td>";
            $html_sweet_alert .= "<td  style='padding: 3px; white-space: normal;' title='" . $teacher_name . "'>" . $getBreakdown[$i]->teacher_commit2 . "</td>";
            $html_sweet_alert .= "<td>" . $getBreakdown[$i]->start_date . "</td>";
            $html_sweet_alert .= "<td>" . $getBreakdown[$i]->end_date . "</td>";
            $html_sweet_alert .= '<td class="text-center" ><button class="btn btn-icon btn-secondary btn-sm addTrackingComment" type="button" id="' . $id_absences_excuse . '" data-toggle="modal" data-target="#seguimientoInasistencia"><span class="btn-inner--icon"><i class="fa fa-comment-dots"></i></span></button>' . $btn_add_document . '</td>';
            $html_sweet_alert .= '<td><a href="?submodule=student_excuse_breakdown&id_student=' . $getBreakdown[$i]->id_student . '&id_absences_excuse=' . $id_absences_excuse . '" target="_blank" ><button class="btn btn-icon btn-info btn-sm" type="button"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></span></button></a></td>';
            $html_sweet_alert .= '<td><button class="btn btn-icon btn-danger btn-sm deleteJustifyAbsence" type="button" id="' . $id_absences_excuse . '"><span class="btn-inner--icon"><i class="ni ni-basket"></i></span></button></td>';
            $html_sweet_alert .= "</tr>";
        }
        $html_sweet_alert .= "</tbody>";
        $html_sweet_alert .= "</table>";
        $html_sweet_alert .= "<script>";
        $html_sweet_alert .= "$('#tablaDesgloseAusencias').DataTable( {dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print']} );";
        $html_sweet_alert .= "</script>";

        $data = array(
            'response' => true,
            'html_sweet_alert' => $html_sweet_alert
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
function breakdownAbsencesHistorical()
{
    $id_student = $_POST['id_student'];
    $type_incident = $_POST['type_incident'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT DISTINCT iat.incident, DATE(ati.apply_date) AS apply_date, class_block, sbj.name_subject, gps.group_code,
     student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name,
    CONCAT(colab.nombres_colaborador , ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS teacher_name
    FROM attendance_records.attendance_index AS ati
    INNER JOIN attendance_records.attendance_record AS atr ON ati.id_attendance_index = atr.id_attendance_index
    INNER JOIN attendance_records.incidents_attendance AS iat ON atr.incident_id = iat.incident_id
    INNER JOIN  school_control_ykt.students AS student ON atr.id_student = student.id_student
    INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON ati.teacher_passed_attendance = colab.no_colaborador
    WHERE atr.id_student = $id_student AND iat.incident_id = $type_incident AND ati.valid_assistance = 1 AND attend = 0
    order by apply_date DESC
    ";
    //echo $stmt;
    $getBreakdown = $groups->getGroupFromTeachers($stmt);
    if (!empty($getBreakdown)) {
        $student_name = mb_strtoupper($getBreakdown[0]->student_name);
        $student_code = $getBreakdown[0]->student_code;
        $id_student = $getBreakdown[0]->id_student;

        $html_sweet_alert = '<h3 class="text-uppercase">Alumno: ' . $student_name . '</h3>';
        $html_sweet_alert .= '<h3 class="text-uppercase">Código de Alumno: ' . $student_code . '             <button class="btn btn-icon btn-info btn-sm getStudentContactInfo" data-id_student="' . $id_student . '" type="button"><span class="btn-inner--icon"><i class="fa-solid fa-phone"></i></span></button></h3>';
        $html_sweet_alert .= '<div style="height: 600px; overflow: auto;"><table class="table align-items-center table-flush" id="tablaDesgloseAusencias">';
        $html_sweet_alert .= '<thead class="thead-dark">';
        $html_sweet_alert .= "<tr>";
        $html_sweet_alert .= "<th style='color:white !important; '>FECHA DE REGISTRO</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>MATERIA</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>GRUPO</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>BLOQUE</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>TIPO DE JUSTIFICACIÓN</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>PROFESOR QUE REGISTRÓ</th>";
        $html_sweet_alert .= "</tr>";
        $html_sweet_alert .= "</thead>";
        $html_sweet_alert .= "<tbody class='list'>";
        for ($i = 0; $i < count($getBreakdown); $i++) {
            $teacher_name = $getBreakdown[$i]->teacher_name;

            $incident = $getBreakdown[$i]->incident;
            $class_block = $getBreakdown[$i]->class_block;
            $apply_date = $getBreakdown[$i]->apply_date;
            $name_subject = $getBreakdown[$i]->name_subject;
            $group_code = $getBreakdown[$i]->group_code;

            $html_sweet_alert .= "<tr>";
            $html_sweet_alert .= "<td>" . $apply_date . "</td>";
            $html_sweet_alert .= "<td>" . $name_subject . "</td>";
            $html_sweet_alert .= "<td>" . $group_code . "</td>";
            $html_sweet_alert .= "<td>" . $class_block . "</td>";
            $html_sweet_alert .= "<td>" . $incident . "</td>";

            $html_sweet_alert .= "<td>" . $teacher_name . "</td>";
            $html_sweet_alert .= "</tr>";
        }
        $html_sweet_alert .= "</tbody>";
        $html_sweet_alert .= "</table>";
        $html_sweet_alert .= "<script>";
        $html_sweet_alert .= "$('#tablaDesgloseAusencias').DataTable( {dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print']} );";
        $html_sweet_alert .= "</script>";

        $data = array(
            'response' => true,
            'html_sweet_alert' => $html_sweet_alert
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
function breakdownincidents()
{
    $id_student = $_POST['id_student'];
    //$today_date = $_POST['today_date'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT
    sil.id_student_incidents_log, sil.incident_commit, icc.clasification_degree, incident_description, DATE(sil.incident_date) AS incident_date, DATE (sil.date_registered) AS date_registered,school_year,
    ic.incident_description_detail, ic.incidence_consequences, icc.bootstrap_class,
    student.student_code, ic.SUSPENTION AS apply_suspention,
    CONCAT(student.lastname , ' ', student.name) AS student_name,
    CONCAT(colab.nombres_colaborador , ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS teacher_name
    FROM student_incidents.student_incidents_log AS sil
    LEFT JOIN school_control_ykt.assignments AS ass ON ass.id_assignment = sil.id_assignment
    LEFT JOIN school_control_ykt.subjects AS sub ON sub.id_subject = ass.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = sil.no_teacher_registered
    INNER JOIN student_incidents.incidence_code AS ic ON ic.id_incidence_code = sil.id_incidence_code
    INNER JOIN student_incidents.incident_clasification AS icc ON icc.id_incident_clasification = ic.id_incident_clasification
    INNER JOIN school_control_ykt.students AS student ON student.id_student = sil.id_student
    WHERE sil.id_student = $id_student AND active_incident = 1 ORDER BY sil.id_student_incidents_log DESC";

    $getBreakdown = $groups->getGroupFromTeachers($stmt);
    if (!empty($getBreakdown)) {

        $sqlGetCurrentSchoolYear = "SELECT school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1";
        $getCurrentSchoolYear = $groups->getGroupFromTeachers($sqlGetCurrentSchoolYear);
        $school_year = $getCurrentSchoolYear[0]->school_year;
        $arr_school_year = explode("-", $school_year);
        $last_school_year = $arr_school_year[0];



        $student_name = mb_strtoupper($getBreakdown[0]->student_name);
        $student_code = $getBreakdown[0]->student_code;
        $html_sweet_alert = '<h3 class="text-uppercase">Alumno: ' . $student_name . '</h3>';
        $html_sweet_alert .= '<h3 class="text-uppercase">Código de Alumno: ' . $student_code . '</h3>';
        $html_sweet_alert .= '<div style="height: 600px; overflow: auto;"><table class="table align-items-center table-flush" id="tablaDesgloseIncidencias">';
        $html_sweet_alert .= '<thead class="thead-dark">';
        $html_sweet_alert .= "<tr>";
        $html_sweet_alert .= "<th style='color:white !important; '>Fecha de Incidencia</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Ciclo Escolar</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Clasificación</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Identificador</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>Descripción de incidencia</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Consecuencias</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>Comentario</th>";
        $html_sweet_alert .= "<th style='color:white !important; '>Seguimiento</th>";
        $html_sweet_alert .= "<th style='color:white !important;'>Borrar</th>";
        $html_sweet_alert .= "</tr>";
        $html_sweet_alert .= "</thead>";
        $html_sweet_alert .= "<tbody class='list'>";
        for ($i = 0; $i < count($getBreakdown); $i++) {
            $apply_suspention = $getBreakdown[$i]->apply_suspention;
            $teacher_name = $getBreakdown[$i]->teacher_name;
            $icon = "";
            if ($apply_suspention == '1') {
                $icon = '<i class="fa fa-triangle-exclamation" style=" color: red;"></i>';
            } else {
                $icon = "";
            }
            $arr_incident_date = explode("-", $getBreakdown[$i]->incident_date);
            $incident_year = $arr_incident_date[0];

            $nota_year = "";
            if ($incident_year < $last_school_year) {
                $nota_year = "<br><p style='color: red; font-size:10px;'><em >Nota: Esta incidencia se <br>registró en ciclos escolares pasados.</em></p>";
            } else {
                $nota_year = "";
            }



            $id_student_incidents_log = $getBreakdown[$i]->id_student_incidents_log;
            $html_sweet_alert .= "<tr>";
            $html_sweet_alert .= "<td data-toggle='tooltip' data-placement='top' title='Fecha de registro: " . $getBreakdown[$i]->date_registered . "'>" . $getBreakdown[$i]->incident_date . "</td>";
            $html_sweet_alert .= "<td style=''>" . $getBreakdown[$i]->school_year . "</td>";
            $html_sweet_alert .= '<td style=""><span class="badge badge-dot mr-4"><i class="bg-' . $getBreakdown[$i]->bootstrap_class . '"></i><span class="status">' . $getBreakdown[$i]->clasification_degree . '</span></span></td>';
            $html_sweet_alert .= '<td style="">' . $icon . '     ' . $getBreakdown[$i]->incident_description . ' <br></td>';
            $html_sweet_alert .= "<td style='color: white; padding: 3px; white-space: normal; background-color:rgb(199, 199, 199)'>" . $getBreakdown[$i]->incident_description_detail . "</td>";
            $html_sweet_alert .= "<td style='color: white; padding: 3px; white-space: normal; background-color:rgb(199, 199, 199)'>" . $getBreakdown[$i]->incidence_consequences . "</td>";
            $html_sweet_alert .= "<td style='padding: 3px; white-space: normal;' title='" . $teacher_name . "'>" . $getBreakdown[$i]->incident_commit . "</td>";
            $html_sweet_alert .= '<td class="text-center" ><button class="btn btn-icon btn-secondary btn-sm addTrackingIncidentComment" type="button" id="' . $id_student_incidents_log . '" data-toggle="modal" data-target="#seguimientoIncidencias"><span class="btn-inner--icon"><i class="fa fa-comment-dots"></i></span></button></td>';
            $html_sweet_alert .= '<td><button class="btn btn-icon btn-danger btn-sm deleteIncident" type="button" id="' . $id_student_incidents_log . '"><span class="btn-inner--icon"><i class="ni ni-basket"></i></span></button></td>';
            $html_sweet_alert .= "</tr>";
        }
        $html_sweet_alert .= "</tbody>";
        $html_sweet_alert .= "</table>";
        $html_sweet_alert .= "<script>";
        $html_sweet_alert .= "$('#tablaDesgloseIncidencias').DataTable( {dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'], 'ordering': false} );";
        $html_sweet_alert .= "</script>";
        $data = array(
            'response' => true,
            'html_sweet_alert' => $html_sweet_alert
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

function deleteJustify()
{
    $id_absences_excuse = $_POST['id_absences_excuse'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "UPDATE absence_excuse.absences_excuse_breakdown SET active_excuse = 0, apply_excuse = 0 WHERE id_absences_excuse = $id_absences_excuse";
    $deleteBreakdown = $attendance->saveAttendance($stmt);

    $stmt_index = "UPDATE absence_excuse.absences_excuse SET active_excuse = 0 WHERE id_absences_excuse = $id_absences_excuse";
    $deleteIndex = $attendance->saveAttendance($stmt_index);
    $stmt_index = "UPDATE absence_excuse.absence_tracking SET comment_active = 0 WHERE id_absences_excuse = $id_absences_excuse";
    $deleteIndex = $attendance->saveAttendance($stmt_index);

    $data = array(
        'response' => true
    );
    //--- --- ---//


    echo json_encode($data);
}

function deleteIncident()
{
    $id_student_incidents_log = $_POST['id_student_incidents_log'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "UPDATE student_incidents.student_incidents_log SET active_incident =  0 WHERE id_student_incidents_log = $id_student_incidents_log";
    $deleteBreakdown = $attendance->saveAttendance($stmt);

    /*  $stmt_index = "UPDATE absence_excuse.absences_excuse SET active_excuse = 0 WHERE id_absences_excuse = $id_absences_excuse";
    $deleteIndex = $attendance->saveAttendance($stmt_index);*/
    $stmt_index = "UPDATE student_incidents.incident_tracking SET comment_active = 0 WHERE id_student_incidents_log = $id_student_incidents_log";
    $deleteIndex = $attendance->saveAttendance($stmt_index);

    $data = array(
        'response' => true
    );
    //--- --- ---//


    echo json_encode($data);
}

function updateBreakdownJustify()
{

    $id_absences_excuse_breakdown = $_POST['id_absences_excuse_breakdown'];
    $column = $_POST['column'];
    $aplica = $_POST['aplica'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "UPDATE absence_excuse.absences_excuse_breakdown SET $column = '$aplica' WHERE id_absences_excuse_breakdown = $id_absences_excuse_breakdown";
    $deleteBreakdown = $attendance->saveAttendance($stmt);


    $data = array(
        'response' => true
    );
    //--- --- ---//


    echo json_encode($data);
}

function getBreakdownDetails()
{
    $id_absences_excuse_breakdown = $_POST['id_absences_excuse_breakdown'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT ext.excuse_description, ae.*, aeb.day_absence_comment, student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name
    FROM absence_excuse.absences_excuse_breakdown AS aeb
    INNER JOIN absence_excuse.absences_excuse AS ae ON ae.id_absences_excuse = aeb.id_absences_excuse
    INNER JOIN school_control_ykt.students AS student ON student.id_student = ae.id_student
    INNER JOIN absence_excuse.excuse_types ext ON ae.id_excuse_types = ext.id_excuse_types
    WHERE aeb.id_absences_excuse_breakdown = $id_absences_excuse_breakdown";
    $getBreakdown = $groups->getGroupFromTeachers($stmt);

    if (!empty($getBreakdown)) {
        $student_name = mb_strtoupper($getBreakdown[0]->student_name);
        $student_code = $getBreakdown[0]->student_code;
        $day_absence_comment = $getBreakdown[0]->day_absence_comment;
        $html_sweet_alert = '<h3 class="text-uppercase">Alumno: ' . $student_name . '</h3>';
        $html_sweet_alert .= '<h3 class="text-uppercase">Código de Alumno: ' . $student_code . '</h3><br><br>';
        $html_sweet_alert .= '<div style="height: 600px; overflow: auto;"><table class="table align-items-center table-flush" id="">';
        $html_sweet_alert .= '<div class="form-group">';
        $html_sweet_alert .= '<label for="day_absence_comment">Comentario de ausencia</label>';
        $html_sweet_alert .= '<textarea class="form-control" id="day_absence_comment" rows="3" value="' . $day_absence_comment . '" >' . $day_absence_comment . '</textarea>';
        $html_sweet_alert .= '</div>';


        $data = array(
            'response' => true,
            'html_sweet_alert' => $html_sweet_alert
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
function getStudentsSubjects()
{
    $id_student = $_POST['id_student'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT DISTINCT sbj.name_subject, sbj.id_subject
    FROM school_control_ykt.students AS student 
    INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = student.id_student
    INNER JOIN school_control_ykt.groups AS grp ON grp.id_group = ins.id_group
    INNER JOIN school_control_ykt.assignments AS asg ON asg.id_group = grp.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE student.id_student = $id_student";
    $getSubjects = $groups->getGroupFromTeachers($stmt);

    if (!empty($getSubjects)) {
        $data = array(
            'response' => true,
            'data' => $getSubjects
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
function getStudentsSubjectsTeachers()
{
    $id_student = $_POST['id_student'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT DISTINCT sbj.name_subject, sbj.id_subject
    FROM school_control_ykt.students AS student 
    INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = student.id_student
    INNER JOIN school_control_ykt.groups AS grp ON grp.id_group = ins.id_group
    INNER JOIN school_control_ykt.assignments AS asg ON asg.id_group = grp.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE student.id_student = $id_student AND asg.no_teacher = '$_SESSION[colab]'";
    $getSubjects = $groups->getGroupFromTeachers($stmt);

    if (!empty($getSubjects)) {
        $data = array(
            'response' => true,
            'data' => $getSubjects
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
function updateBreakdownJustifyAttendance()
{
    $id_absences_excuse_breakdown = $_POST['id_absences_excuse_breakdown'];
    $justifyed = $_POST['justifyed'];
    $apply_excuse = 0;
    if ($justifyed == 1) {
        $justifyed = 3;
        $apply_excuse = 1;
    } else {
        $justifyed = 1;
    }


    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT aeb.*, ae.id_student
    FROM absence_excuse.absences_excuse_breakdown AS aeb 
    INNER JOIN absence_excuse.absences_excuse AS ae ON ae.id_absences_excuse = aeb.id_absences_excuse
    WHERE aeb.id_absences_excuse_breakdown = $id_absences_excuse_breakdown";
    $getBreakdown = $groups->getGroupFromTeachers($stmt);



    if (!empty($getBreakdown)) {
        $id_student = $getBreakdown[0]->id_student;
        $absence_day = $getBreakdown[0]->absence_day;
        $str_absence_day = explode(' ', $absence_day);
        $absence_day = $str_absence_day[0];
        $limit_day = $absence_day . " 23:59:59";

        $stmt = "UPDATE attendance_records.attendance_record AS atr
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
         SET incident_id = '$justifyed', apply_justification = $apply_excuse
        WHERE atr.id_student = $id_student 
        AND ati.apply_date >= '$absence_day' AND ati.apply_date <= '$limit_day'";
        //echo $stmt;
        $getBreakdown = $attendance->saveAttendance($stmt);

        $data = array(
            'response' => true,
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

function updateBreakdownJustifyAttendanceNew()
{
    $id_absences_excuse = $_POST['id_absences_excuse_breakdown'];
    $justifyed = $_POST['justifyed'];
    $apply_excuse = 0;
    if ($justifyed == 1) {
        $justifyed = 3;
        $apply_excuse = 1;
    } else {
        $justifyed = 1;
    }


    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT aeb.*, ae.id_student
    FROM absence_excuse.absences_excuse_breakdown AS aeb 
    INNER JOIN absence_excuse.absences_excuse AS ae ON ae.id_absences_excuse = aeb.id_absences_excuse
    WHERE aeb.id_absences_excuse = $id_absences_excuse";
    $getBreakdown = $groups->getGroupFromTeachers($stmt);



    if (!empty($getBreakdown)) {
        foreach ($getBreakdown as $breakdown) {
            $id_absences_excuse_breakdown = $breakdown->id_absences_excuse_breakdown;
            $id_student = $breakdown->id_student;
            $absence_day = $breakdown->absence_day;
            $str_absence_day = explode(' ', $absence_day);
            $absence_day = $str_absence_day[0];
            $limit_day = $absence_day . " 23:59:59";

            $stmt = "UPDATE attendance_records.attendance_record AS atr
            INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
             SET incident_id = '$justifyed', apply_justification = $apply_excuse
            WHERE atr.id_student = $id_student 
            AND ati.apply_date >= '$absence_day' AND ati.apply_date <= '$limit_day'";
            /* echo $stmt; */
            $getBreakdown = $attendance->saveAttendance($stmt);

            $data = array(
                'response' => true,
            );
            //--- --- ---//
        }
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
function saveCommentaryTracing()
{

    $txt_commentary = $_POST['txt_commentary'];
    $id_absences_excuse = $_POST['id_absences_excuse'];
    $id_teacher_tracking = $_POST['id_teacher_tracking'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "INSERT INTO absence_excuse.absence_tracking (id_absences_excuse, 
    comment_tracing,
    techer_create,
    datelog,
    comment_active
    ) VALUES ('$id_absences_excuse', 
    '$txt_commentary',
    '$id_teacher_tracking',
    NOW(),
    1)";
    //echo $sql_check;
    $result_check = $attendance->saveAttendance($sql_check);

    $last_id = $attendance->getLastId();
    $data = array(
        'response' => true,
        'message' => 'Comentario guardado correctamente',
        'last_id' => $last_id
    );





    echo json_encode($data);
}
function saveCommentaryTracingIncident()
{

    $txt_commentary = $_POST['txt_commentary'];
    $id_student_incidents_log     = $_POST['id_student_incidents_log'];
    $id_teacher_tracking = $_POST['id_teacher_tracking'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "INSERT INTO student_incidents.incident_tracking (id_student_incidents_log,
    comment_tracking,
    teacher_create,
    datelog,
    comment_active
    ) VALUES ('$id_student_incidents_log', 
    '$txt_commentary',
    '$_SESSION[colab]',
    NOW(),
    1)";
    //echo $sql_check;
    if ($result_check = $attendance->saveAttendance($sql_check)) {
        $last_id = $attendance->getLastId();
        $data = array(
            'response' => true,
            'message' => 'Comentario guardado correctamente',
            'last_id' => $last_id
        );
    }




    echo json_encode($data);
}
function getCommentaryTracing()
{

    $id_absences_excuse = $_POST['id_absences_excuse'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT at.*,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    CONCAT(st.name, ' ', st.lastname) AS student_name, st.student_code  
     FROM absence_excuse.absence_tracking AS at
     INNER JOIN absence_excuse.absences_excuse AS ae ON ae.id_absences_excuse = at.id_absences_excuse
     INNER JOIN school_control_ykt.students AS st ON st.id_student = ae.id_student
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = at.techer_create
    WHERE at.id_absences_excuse = $id_absences_excuse AND comment_active = 1";
    //echo $sql_check;
    $html_timeline = '';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        $dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');



        foreach ($result_check as $timeline) {

            $date_log_sql = explode(" ", $timeline->datelog);
            $arr_date_timeline = explode("-", $date_log_sql[0]);
            $dia_semana = $dias[date('N', strtotime($date_log_sql[0]))];
            $date_timeline = $arr_date_timeline[2] . " de " . $arr_date_timeline[1] . " de " . $arr_date_timeline[0];
            $time_timeline = $date_log_sql[1];
            $html_timeline .= '<div class="timeline-block" id="div' . $timeline->id_absence_tracking . '">';
            $html_timeline .= '<span class="timeline-step badge-success">';
            $html_timeline .= '<i class="ni ni-email-83"></i>';
            $html_timeline .= "</span>";
            $html_timeline .= '<div class="timeline-content">';
            $html_timeline .=
                '<small class="text-muted font-weight-bold">' .
                $dia_semana . ", " . $date_timeline . " a las " . $time_timeline .
                "</small>";
            $html_timeline .= '<h5 class=" mt-3 mb-0">' . $timeline->comment_tracing . "</h5>";
            $html_timeline .=
                '<p class=" text-sm mt-1 mb-0">' . $timeline->teacher_name . '</p>';
            $html_timeline .= '<div class="mt-3">';
            $html_timeline .=
                '<button type="button" id="' . $timeline->id_absence_tracking . '" class="btn btn-dribbble btn-icon-only rounded-circle btnDeleteTrackingCommit">';
            $html_timeline .=
                '<span class="btn-inner--icon"><i class="ni ni-basket"></i></span>';
            $html_timeline .= "</button>";
            $html_timeline .= "</div>";
            $html_timeline .= "</div>";
            $html_timeline .= "</div>";
        }
        $student_name_code = $result_check[0]->student_name;
        $data = array(
            'response' => true,
            'message' => 'Comentario guardado correctamente',
            'html' => $html_timeline,
            'student_name_code' => $student_name_code,
        );
    } else {
        $sql_name = "SELECT DISTINCT CONCAT(st.name, ' ', st.lastname) AS student_name, st.student_code 
        FROM school_control_ykt.students AS st
        INNER JOIN absence_excuse.absences_excuse AS ae ON st.id_student = ae.id_student
        WHERE ae.id_absences_excuse = $id_absences_excuse";
        $result_name = $groups->getGroupFromTeachers($sql_name);
        if (!empty($result_name)) {
            $student_name_code = $result_name[0]->student_name;
        } else {
            $student_name_code = "-";
        }

        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_timeline,
            'student_name_code' => $student_name_code,
        );
    }




    echo json_encode($data);
}
function getCommentaryTracingIncidents()
{

    $id_student_incidents_log = $_POST['id_student_incidents_log'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT inct.*,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    CONCAT(st.name, ' ', st.lastname) AS student_name, st.student_code  
     FROM student_incidents.incident_tracking AS inct
     INNER JOIN student_incidents.student_incidents_log AS sil ON sil.id_student_incidents_log = inct.id_student_incidents_log
     INNER JOIN school_control_ykt.students AS st ON st.id_student = sil.id_student
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = inct.teacher_create
    WHERE inct.id_student_incidents_log = $id_student_incidents_log AND comment_active = 1";
    //echo $sql_check;
    $html_timeline = '';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        $dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');



        foreach ($result_check as $timeline) {

            $date_log_sql = explode(" ", $timeline->datelog);
            $arr_date_timeline = explode("-", $date_log_sql[0]);
            $dia_semana = $dias[date('N', strtotime($date_log_sql[0]))];
            $date_timeline = $arr_date_timeline[2] . " de " . $arr_date_timeline[1] . " de " . $arr_date_timeline[0];
            $time_timeline = $date_log_sql[1];
            $html_timeline .= '<div class="timeline-block" id="div' . $timeline->id_incident_tracking . '">';
            $html_timeline .= '<span class="timeline-step badge-success">';
            $html_timeline .= '<i class="ni ni-email-83"></i>';
            $html_timeline .= "</span>";
            $html_timeline .= '<div class="timeline-content">';
            $html_timeline .=
                '<small class="text-muted font-weight-bold">' .
                $dia_semana . ", " . $date_timeline . " a las " . $time_timeline .
                "</small>";
            $html_timeline .= '<h5 class=" mt-3 mb-0">' . $timeline->comment_tracking . "</h5>";
            $html_timeline .=
                '<p class=" text-sm mt-1 mb-0">' . $timeline->teacher_name . '</p>';
            $html_timeline .= '<div class="mt-3">';
            $html_timeline .=
                '<button type="button" id="' . $timeline->id_incident_tracking . '" class="btn btn-dribbble btn-icon-only rounded-circle btnDeleteTrackingCommitIncident">';
            $html_timeline .=
                '<span class="btn-inner--icon"><i class="ni ni-basket"></i></span>';
            $html_timeline .= "</button>";
            $html_timeline .= "</div>";
            $html_timeline .= "</div>";
            $html_timeline .= "</div>";
        }
        $student_name_code = $result_check[0]->student_name;
        $data = array(
            'response' => true,
            'message' => 'Comentario guardado correctamente',
            'html' => $html_timeline,
            'student_name_code' => $student_name_code,
        );
    } else {
        $sql_name = "SELECT DISTINCT CONCAT(st.name, ' ', st.lastname) AS student_name, st.student_code
         FROM school_control_ykt.students AS st 
         INNER JOIN student_incidents.student_incidents_log AS sil ON sil.id_student = st.id_student
         WHERE sil.id_student_incidents_log = $id_student_incidents_log";
        $result_name = $groups->getGroupFromTeachers($sql_name);
        if (!empty($result_name)) {
            $student_name_code = $result_name[0]->student_name;
        } else {
            $student_name_code = "-";
        }

        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_timeline,
            'student_name_code' => $student_name_code,
        );
    }




    echo json_encode($data);
}
function deleteCommentaryTracing()
{

    $id_absences_excuse = $_POST['id_absences_excuse'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "UPDATE absence_excuse.absence_tracking SET comment_active = 0 WHERE id_absence_tracking = $id_absences_excuse";
    //echo $sql_check;
    if ($result_check = $attendance->saveAttendance($sql_check)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'message' => 'Se eliminó correctamente!!!'
        );
    } else {

        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el comentario'
        );
    }




    echo json_encode($data);
}
function deleteCommentaryTracingIncident()
{

    $id_incident_tracking = $_POST['id_incident_tracking'];


    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "UPDATE student_incidents.incident_tracking SET comment_active = 0 WHERE id_incident_tracking = $id_incident_tracking";
    //echo $sql_check;
    if ($result_check = $attendance->saveAttendance($sql_check)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'message' => 'Se eliminó correctamente!!!'
        );
    } else {

        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el comentario'
        );
    }




    echo json_encode($data);
}

function getAbsencesByDate()
{

    $date_search = $_POST['date_search'];
    $no_teacher = $_SESSION['colab'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT DISTINCT st.id_student, DATE(apply_date) AS apply_date, u.group_code, CONCAT (st.name, ' ', st.lastname) AS name_student, st.student_code, u.academic_level 

    FROM 

    (SELECT groups.group_code, rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, al.academic_level, assg.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT gps.group_code, rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, al.academic_level, asgm.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

    UNION 

    SELECT gps.group_code, asg.no_teacher, asg.print_school_report_card, asg.assignment_active, al.academic_level, asg.id_assignment
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

    AS u

    INNER JOIN attendance_records.attendance_index AS ati ON ati.id_assignment = u.id_assignment
    INNER JOIN attendance_records.attendance_record AS atr ON atr.attend = 0 AND ati.id_attendance_index  = atr.id_attendance_index
    INNER JOIN school_control_ykt.students AS st ON st.id_student = atr.id_student

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1 AND DATE(ati.apply_date) = '$date_search'";
    //echo $sql_check;
    $html_student_list = '';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        //$dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');


        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<table class="table align-items-center table-flush" id="tabResults">';
        $html_student_list .= '<thead class="thead-light">';
        $html_student_list .= '<tr>';
        $html_student_list .= '<th>CÓD. ALUMNO</th>';
        $html_student_list .= '<th>NOMBRE</th>';
        $html_student_list .= '<th>GRUPO</th>';
        $html_student_list .= '<th>NIVEL ACADÉMICO</th>';
        $html_student_list .= '<th>CONTÁCTO</th>';
        $html_student_list .= '<th>FALTAS</th>';
        $html_student_list .= '<th>REGISTROS</th>';
        $html_student_list .= '<th>MATERIAS</th>';
        $html_student_list .= '</tr>';
        $html_student_list .= '</thead>';
        $html_student_list .= '<tbody class="list">';

        foreach ($result_check as $students) {
            $check_registered_breakdown = $attendance->breakdownJustify($students->id_student, $students->apply_date);

            $html_student_list .= '<tr>';
            $html_student_list .= '<td>' . $students->student_code . '</td>';
            $html_student_list .= '<td>' . $students->name_student . '</td>';
            $html_student_list .= '<td>' . $students->group_code . '</td>';
            $html_student_list .= '<td>' . $students->academic_level . '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button class="btn btn-icon btn-info btn-sm getStudentContactInfo" data-id_student="' . $students->id_student . '" type="button"><span class="btn-inner--icon"><i class="fa-solid fa-phone"></i></span></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button type="button" class="btn btn-primary btn-sm btnJustifyJS" id-student="' . $students->id_student . '" code-student="' . $students->student_code . '" name-student="' . $students->name_student . '" data-toggle="modal" data-target="#modalJustify" data-id_student="' . $students->id_student . '"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            if (!empty($check_registered_breakdown)) {
                $html_student_list .= '<button type="button" class="btn btn-primary btn-sm btnBreakdownAbsence" id-student="' . $students->id_student . '" data-today-date="' . $students->apply_date . '" data-toggle="modal" data-target="#modalDesgloseFaltas"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>';
            } else {
                $html_student_list .= 'N/A';
            }
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button type="button" class="btn btn-info btn-sm btnSubjectsAbsences" id-student="' . $students->id_student . '" code-student="' . $students->student_code . '" name-student="' . $students->name_student . '" data-absence-date="' . $students->apply_date . '"  data-id_student="' . $students->id_student . '"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '</tr>';
        }
        $html_student_list .= '</tbody>';
        $html_student_list .= '</table>';
        $html_student_list .= '</div>';
        $html_student_list .= '<script>var filtersConfig = {base_path: "../general/js/vendor/tablefilter/tablefilter/", paging: {results_per_page: ["Records: ", [10, 25, 50, 100]]}, rows_counter:true, col_2: "select",col_3: "select", col_4: "none", col_5: "none", col_6: "none", col_7: "none"}; var tf = new TableFilter((document.querySelector("#tabResults")), filtersConfig);tf.init();</script>';
        /* $html_student_list .= '<script>$(document).ready(function () {  rows = $(".tot").text(); console.log(rows); let result = rows.replace("Rows", "Resultados"); $("#total_alumnos").text("").text(result);});</script>';
        $html_student_list .= '<script> $(document).ready(function () { $(document).on("change", ".tot", function () { console.log("2");  }); });</script> '; */
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    } else {
        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<h1>No hay faltas para este día</h1>';
        $html_student_list .= '</div>';
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    }




    echo json_encode($data);
}

function getAbsencesByDateAndType()
{

    $date_search = $_POST['date_search'];
    $type_incident = $_POST['type_incident'];
    $no_teacher = $_SESSION['colab'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT DISTINCT st.id_student, DATE(apply_date) AS apply_date, u.group_code, CONCAT (st.name, ' ', st.lastname) AS name_student, st.student_code, u.academic_level 

    FROM 

    (SELECT groups.group_code, rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, al.academic_level, assg.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT gps.group_code, rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, al.academic_level, asgm.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

    UNION 

    SELECT gps.group_code, asg.no_teacher, asg.print_school_report_card, asg.assignment_active, al.academic_level, asg.id_assignment
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

    AS u

    INNER JOIN attendance_records.attendance_index AS ati ON ati.id_assignment = u.id_assignment
    INNER JOIN attendance_records.attendance_record AS atr ON atr.attend = 0 AND ati.id_attendance_index  = atr.id_attendance_index
    INNER JOIN school_control_ykt.students AS st ON st.id_student = atr.id_student

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1 AND DATE(ati.apply_date) = '$date_search' AND atr.incident_id = '$type_incident'";
    //echo $sql_check;
    $html_student_list = '';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        //$dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');


        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<table class="table align-items-center table-flush" id="tabResults">';
        $html_student_list .= '<thead class="thead-light">';
        $html_student_list .= '<tr>';
        $html_student_list .= '<th>CÓD. ALUMNO</th>';
        $html_student_list .= '<th>NOMBRE</th>';
        $html_student_list .= '<th>GRUPO</th>';
        $html_student_list .= '<th>NIVEL ACADÉMICO</th>';
        $html_student_list .= '<th>CONTÁCTO</th>';
        $html_student_list .= '<th>REGISTROS</th>';
        $html_student_list .= '<th>MATERIAS</th>';
        $html_student_list .= '</tr>';
        $html_student_list .= '</thead>';
        $html_student_list .= '<tbody class="list">';

        foreach ($result_check as $students) {
            $check_registered_breakdown = $attendance->absencesHistorical($students->id_student, $type_incident);

            $html_student_list .= '<tr>';
            $html_student_list .= '<td>' . $students->student_code . '</td>';
            $html_student_list .= '<td>' . $students->name_student . '</td>';
            $html_student_list .= '<td>' . $students->group_code . '</td>';
            $html_student_list .= '<td>' . $students->academic_level . '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button class="btn btn-icon btn-info btn-sm getStudentContactInfo" data-id_student="' . $students->id_student . '" type="button"><span class="btn-inner--icon"><i class="fas fa-phone-square"></i></span></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            if (!empty($check_registered_breakdown)) {
                $html_student_list .= '<button type="button" class="btn btn-primary btn-sm btnBreakdownAbsence" id-student="' . $students->id_student . '" data-today-date="' . $students->apply_date . '" data-toggle="modal" data-target="#modalDesgloseFaltas"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>';
            } else {
                $html_student_list .= 'N/A';
            }
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button type="button" class="btn btn-info btn-sm btnSubjectsAbsences" id-student="' . $students->id_student . '" code-student="' . $students->student_code . '" name-student="' . $students->name_student . '" data-absence-date="' . $students->apply_date . '"  data-id_student="' . $students->id_student . '"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '</tr>';
        }
        $html_student_list .= '</tbody>';
        $html_student_list .= '</table>';
        $html_student_list .= '</div>';
        $html_student_list .= '<script>var filtersConfig = {base_path: "../general/js/vendor/tablefilter/tablefilter/", paging: {results_per_page: ["Records: ", [10, 25, 50, 100]]}, rows_counter:true, col_2: "select",col_3: "select", col_4: "none", col_5: "none", col_6: "none", col_7: "none"}; var tf = new TableFilter((document.querySelector("#tabResults")), filtersConfig);tf.init();</script>';
        /* $html_student_list .= '<script>$(document).ready(function () {  rows = $(".tot").text(); console.log(rows); let result = rows.replace("Rows", "Resultados"); $("#total_alumnos").text("").text(result);});</script>';
        $html_student_list .= '<script> $(document).ready(function () { $(document).on("change", ".tot", function () { console.log("2");  }); });</script> '; */
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    } else {
        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<h1>No hay faltas para este día</h1>';
        $html_student_list .= '</div>';
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    }




    echo json_encode($data);
}

function getSubjectsByAbsence()
{

    $id_student = $_POST['id_student'];
    $absence_date = $_POST['absence_date'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT DISTINCT sbj.name_subject, DATE(ati.apply_date) AS apply_date, ati.apply_date AS apply_date_log, class_block,
    CONCAT(stu.name, ' ', stu.lastname) AS name_student, stu.student_code,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name
    FROM attendance_records.attendance_record AS atr
    INNER JOIN attendance_records.attendance_index AS ati ON atr.id_attendance_index = ati.id_attendance_index 
    INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.students AS stu ON stu.id_student = atr.id_student
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
    WHERE atr.id_student=$id_student AND DATE(ati.apply_date) = '$absence_date'  AND atr.attend = 0 AND ati.valid_assistance = 1";
    //echo $sql_check;
    $html_student_list = '';
    $html_student_list .= '<h2>Inasistencias de Estudiante</h2>';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        //$dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');

        $name_student = $result_check[0]->name_student;
        $student_code = $result_check[0]->student_code;


        $arr_apply_date = explode('-', $absence_date);
        $apply_date = $arr_apply_date[2] . '-' . $arr_apply_date[1] . '-' . $arr_apply_date[0];
        $html_student_list .= '<h3>' . $student_code . ' | ' . $name_student . ' | ' . $apply_date . '</h3>';
        $html_student_list .= '<div class="table-responsive" id="tabResults">';
        $html_student_list .= '<table class="table align-items-center table-flush" id="tStudents">';
        $html_student_list .= '<thead class="thead-dark">';
        $html_student_list .= '<tr>';
        $html_student_list .= '<th style="color:white">MATERIAS</th>';
        $html_student_list .= '<th style="color:white">BLOQUE Y HORA DE REGISTRO</th>';
        $html_student_list .= '<th style="color:white">REGISTRADO POR</th>';

        $html_student_list .= '</tr>';
        $html_student_list .= '</thead>';
        $html_student_list .= '<tbody class="list">';

        foreach ($result_check as $students) {
            $apply_date_log = explode(" ", $students->apply_date_log);
            $hour_pass_list = $apply_date_log[1];
            $class_block = $students->class_block;
            $html_student_list .= '<tr>';
            $html_student_list .= '<td>' . $students->name_subject . '</td>';
            $html_student_list .= '<td>Bloque: ' . $class_block . ' | Hora: ' . $hour_pass_list . '</td>';
            $html_student_list .= '<td>' . $students->teacher_name . '</td>';
            $html_student_list .= '</tr>';
        }
        $html_student_list .= '</tbody>';
        $html_student_list .= '</table>';
        $html_student_list .= '</div>';
        $html_student_list .= '<script> var tf = new TableFilter(document.querySelector("#tabResults");tf.init();</script>';
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    } else {
        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<h1>No hay registros para este día</h1>';
        $html_student_list .= '</div>';
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    }




    echo json_encode($data);
}

function getIncidentsByDate()
{

    $date_search = $_POST['date_search'];
    $no_teacher = $_SESSION['colab'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT DISTINCT  st.id_student, std_gps.group_code, CONCAT (st.name, ' ', st.lastname) AS name_student, st.student_code, std_al.academic_level

    FROM 

    (SELECT groups.group_code, rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, al.academic_level, assg.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT gps.group_code, rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, al.academic_level, asgm.id_assignment
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

    UNION 

    SELECT gps.group_code, asg.no_teacher, asg.print_school_report_card, asg.assignment_active, al.academic_level, asg.id_assignment
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

    AS u
    INNER JOIN student_incidents.student_incidents_log AS sil 
    INNER JOIN school_control_ykt.students AS st ON st.id_student = sil.id_student
    INNER JOIN iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca ON rel_coord_aca.no_teacher = $no_teacher
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = st.group_id  AND  lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.groups AS std_gps ON std_gps.id_group = st.group_id
    INNER JOIN school_control_ykt.academic_levels_grade AS std_acdlvldg ON std_gps.id_level_grade = std_acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS std_al ON std_al.id_academic_level = std_acdlvldg.id_academic_level

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1  AND DATE(incident_date) = '$date_search'
    ORDER BY `sil`.`id_student_incidents_log` ASC";
    //echo $sql_check;
    $html_student_list = '';
    if ($result_check = $groups->getGroupFromTeachers($sql_check)) {
        //$last_id = $attendance->getLastId();
        //$dias = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');


        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<table class="table align-items-center table-flush" id="tStudents">';
        $html_student_list .= '<thead class="thead-light">';
        $html_student_list .= '<tr>';
        $html_student_list .= '<th>CÓD. ALUMNO</th>';
        $html_student_list .= '<th>NOMBRE</th>';
        $html_student_list .= '<th>GRUPO</th>';
        $html_student_list .= '<th>NIVEL ACADÉMICO</th>';
        $html_student_list .= '<th>INCIDENCIAS</th>';
        $html_student_list .= '<th>REGISTROS</th>';
        $html_student_list .= '</tr>';
        $html_student_list .= '</thead>';
        $html_student_list .= '<tbody class="list">';

        foreach ($result_check as $students) {
            $breakdownIncidences = $attendance->breakdownIncidences($students->id_student);

            $html_student_list .= '<tr>';
            $html_student_list .= '<td>' . $students->student_code . '</td>';
            $html_student_list .= '<td>' . $students->name_student . '</td>';
            $html_student_list .= '<td>' . $students->group_code . '</td>';
            $html_student_list .= '<td>' . $students->academic_level . '</td>';
            $html_student_list .= '<td>';
            $html_student_list .= '<button type="button" class="btn btn-warning btn-sm addStudentIncident" id-student="' . $students->id_student . '" code-student="' . $students->student_code . '" name-student="' . $students->name_student . '" data-id_student="' . $students->id_student . '" <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></button>';
            $html_student_list .= '</td>';
            $html_student_list .= '<td>';
            if (!empty($breakdownIncidences)) {
                $html_student_list .= '<button type="button" class="btn btn-primary btn-sm btnBreakdownIncidents" id-student="' . $students->id_student . '" data-toggle="modal" data-target="#modalDesgloseIncidencias"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>';
            } else {
                $html_student_list .= 'N/A';
            }
            $html_student_list .= '</td>';
            $html_student_list .= '</tr>';
        }
        $html_student_list .= '</tbody>';
        $html_student_list .= '</table>';
        $html_student_list .= '</div>';
        $html_student_list .= '<script>var filtersConfig = {base_path: "../general/js/vendor/tablefilter/tablefilter/", paging: {results_per_page: ["Records: ", [10, 25, 50, 100]]}, rows_counter:true, col_2: "select",col_3: "select", col_4: "none", col_5: "none", col_6: "none", col_7: "none"}; var tf = new TableFilter((document.querySelector("#tabResults")), filtersConfig);tf.init();</script>';

        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    } else {
        $html_student_list .= '<div class="table-responsive">';
        $html_student_list .= '<h1>No hay incidencias para este día</h1>';
        $html_student_list .= '</div>';
        $data = array(
            'response' => true,
            'message' => '',
            'html' => $html_student_list
        );
    }




    echo json_encode($data);
}

function uploadStudentFiles()
{
    $response = 0;
    $id_absences_excused = $_POST['id_absences_excused'];
    $sql_db_table = $_POST['sql_db_table'];
    $folder = $_POST['folder'];
    $student_id = $_POST['student_code'];
    $id_absences_excused = $_POST['id_absences_excused'];
    $sql_db_table = $_POST['sql_db_table'];
    $folder = $_POST['folder'];
    $module_name = $_POST['module_name'];
    //$file_name = $_POST['name'];
    $extension_file = basename($_FILES["formData"]["type"]);
    $file_name = $module_name . "_" . $student_id . "_" . time() . ".$extension_file";
    //$route = '/xampp/htdocs/documentos_alumnos/' . $_POST['student'] . '/' . $folder;
    $route2 =  dirname(__DIR__ . '', 2) . '/uploads_documents/' . $folder . "/";
    $route =  dirname(__DIR__ . '', 2) . '/uploads_documents/' . $folder . "/" . $file_name;
    $route_db = '/uploads_documents/' . $folder . "/" . $file_name;
    if (!file_exists($route2)) {
        mkdir($route2, 0777, true);
    }
    if (move_uploaded_file($_FILES["formData"]["tmp_name"], $route)) {
        $attendance = new Attendance;
        //$response = 1;
        //$attendance = new Attendance();
        //echo "The file ". $route_db. " has been uploaded.";
        $stmt = "INSERT INTO absence_excuse.absence_vouchers (
            id_absences_excuse,
            file_name,
            file_route,
            file_type,
            upload_date,
            no_teacher_uplodaded
        ) VALUES
        (
            '$id_absences_excused',
            '$file_name',
            '$route_db',
            '$extension_file',
            NOW(),
            '$_SESSION[colab]')";

        if ($attendance->saveAttendance($stmt)) {
            $response = 1;
            $data = array(
                'response' => true,
                'message' => 'Se cargó correctamente el archivo',
            );
        } else {
            $response = 0;
            $data = array(
                'response' => false,
                'message' => 'No se pudo cargar el archivo',
            );
        }
    } else {
        //$response = 0;
        $data = array(
            'response' => false,
            'message' => 'No se pudo cargar el archivo',
        );
    }
    // echo $file_name;

    //$move = '';

    /* 
    $file = $route . '/' . $file_name;

    if (!file_exists($route)) {
        mkdir($route, 0777, true);
    }

    if (move_uploaded_file($_FILES['formData']['tmp_name'], $file)) {

        $response = 1;

        $movement = array(
            'movimiento'        => $move,
            'curp'              => $_POST['student'],
            'documento'         => $file_name
        );
        $movement = json_encode($movement);
        setLog(module, $movement, $_SESSION['colab']);
    }

    $data['response'] = $response;
     */
    echo json_encode($data);
}

function getStudentInfo()
{

    $id_student = $_POST['id_student'];

    $groups = new Groups;
    $attendance = new Attendance;

    $sql_check = "SELECT 
        id_student,
        student_code,
        CONCAT(name, ' ', lastname) AS name_student
    FROM school_control_ykt.students 
    WHERE id_student = '$id_student'";
    $StudentInfo = $groups->getGroupFromTeachers($sql_check);
    //echo $sql_check;
    $html_student_list = '';
    if (!empty($StudentInfo)) {
        $data = array(
            'response' => true,
            'data' => $StudentInfo
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron datos'
        );
    }




    echo json_encode($data);
}
function trackingDocumentList()
{
    $id_absences_excuse = $_POST['id_absences_excuse'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT av.*,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS teacher_name
    FROM absence_excuse.absence_vouchers AS av
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = av.no_teacher_uplodaded
    WHERE av.id_absences_excuse = $id_absences_excuse AND active = 1";
    $getBreakdown = $groups->getGroupFromTeachers($stmt);
    $html_table = '';
    if (!empty($getBreakdown)) {
        $html_table .= '<table class="table table-striped">';
        $html_table .= '<thead>';
        $html_table .= '<tr>';
        $html_table .= '<th>Nombre</th>';
        $html_table .= '<th>Fecha</th>';
        $html_table .= '<th>Acciones</th>';
        $html_table .= '<th>Subido por: </th>';
        $html_table .= '</tr>';
        $html_table .= '</thead>';
        $html_table .= '<tbody>';

        for ($i = 0; $i < count($getBreakdown); $i++) {

            $file_name = $getBreakdown[$i]->file_name;
            $sql_upload_date = $getBreakdown[$i]->upload_date;
            $array_upload_date = explode(" ", $sql_upload_date);
            $arr_upload_date = explode("-", $array_upload_date[0]);
            $upload_date = $arr_upload_date[2] . "/" . $arr_upload_date[1] . "/" . $arr_upload_date[0];

            $file_route = $getBreakdown[$i]->file_route;
            $file_type = $getBreakdown[$i]->file_type;
            $teacher_name = $getBreakdown[$i]->teacher_name;
            $id_absence_vouchers = $getBreakdown[$i]->id_absence_vouchers;
            $file = "C:/xampp/htdocs/wykt/interno/erp_realtime/iTeach" . $file_route;


            $html_table .= '<td>' . $file_name . '</td>';
            $html_table .= '<td>' . $upload_date . '</td>';
            $html_table .= '<td>';
            if (file_exists($file)) {
                $html_table .= '<a href="http://servykt.homeip.net:8083/wykt/interno/erp_realtime/iTeach' . $file_route . '" target="_blank"><button type="button" class="btn btn-primary btn-sm">';
                $html_table .= '<i class="fas fa-download"></i>';
                $html_table .= '</button></a>';
            } else {
                $html_table .= '<button title="El archivo no existe" type="button" class="btn btn-warning btn-sm">';
                $html_table .= '<i class="fas fa-exclamation-triangle"></i>';
                $html_table .= '</button></a>';
            }

            $html_table .= '<button type="button" id="tr' . $id_absence_vouchers . '" data-id-archive="' . $id_absence_vouchers . '" class="btn btn-danger btn-sm deleteAbsenceDocument">';
            $html_table .= '<i class="fas fa-trash"></i>';
            $html_table .= '</button>';
            $html_table .= '</td>';
            $html_table .= '<td>' . strtoupper($teacher_name) . '</td>';
            $html_table .= '</tr>';
        }
        $html_table .= '</tbody>';
        $html_table .= '</table>';

        $data = array(
            'response' => true,
            'html_table' => $html_table
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
function deleteAbsenceDocument()
{
    $id_absence_vouchers = $_POST['id_absence_vouchers'];



    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "UPDATE absence_excuse.absence_vouchers SET active = 0 WHERE id_absence_vouchers = $id_absence_vouchers";
    $deleteBreakdown = $attendance->saveAttendance($stmt);


    $data = array(
        'response' => true
    );
    //--- --- ---//


    echo json_encode($data);
}
