<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';
include '../models/students.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function getTeachersByGroup()
{
    $id_academic = $_POST['id_academic'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
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

function getAviablesAssignments()
{
    $id_day = $_POST['id_day'];
    $id_block = $_POST['id_block'];
    $id_teacher_sbj = $_POST['id_teacher_sbj'];
    $id_period_calendar = $_POST['id_period_calendar'];
    $id_academic_level = $_POST['id_academic_level'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT asg.id_assignment, sbj.name_subject, gps.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON aclg.id_academic_level = $id_academic_level
        LEFT JOIN class_schedule.relationship_assignments_class_block AS racb
             ON racb.id_assignment = asg.id_assignment AND racb.id_days = $id_day
             AND racb.id_class_block = $id_block 
             AND racb.id_period_calendar = '$id_period_calendar'

        WHERE asg.no_teacher = $id_teacher_sbj AND racb.id_rel_assg_cb IS NULL
        ORDER BY group_code
        ";
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


function saveRelationShipCBAS()
{
    $id_day = $_POST['id_day'];
    $id_block = $_POST['id_block'];
    $id_teacher_sbj = $_POST['id_teacher_sbj'];
    $id_assignment = $_POST['id_assignment'];
    $id_period_calendar = $_POST['id_period_calendar'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $Attendance = new Attendance;

    $stmt = "SELECT DISTINCT asg.*
    FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.assignments AS asg2 ON asg2.id_group = gps.id_group AND asg2.id_assignment != $id_assignment
    INNER JOIN class_schedule.relationship_assignments_class_block AS racb ON asg2.id_assignment = racb.id_assignment
    WHERE id_days = $id_day AND id_class_block = $id_block AND asg.id_assignment = $id_assignment AND racb.no_teacher != $id_teacher_sbj
        AND racb.id_period_calendar = '$id_period_calendar'
    ";

    $getExist = $groups->getGroupFromTeachers($stmt);

    if (empty($getExist)) {
        ///// CHECK IF EXISTS REGS TEACHER

        $stmt = "SELECT *
    FROM class_schedule.relationship_assignments_class_block AS racb
    WHERE id_days = $id_day AND id_class_block = $id_block AND no_teacher = $id_teacher_sbj AND racb.id_period_calendar = '$id_period_calendar'
    ";

        $getGroups = $groups->getGroupFromTeachers($stmt);
        if (empty($getGroups)) {
            $stmt = "INSERT INTO class_schedule.relationship_assignments_class_block 
            (id_class_block, id_days, id_assignment, status, no_teacher, id_period_calendar)
            VALUES (
                    $id_block,
                    $id_day,
                    $id_assignment,
                    1,
                    $id_teacher_sbj,
                    '$id_period_calendar'
                    )
            ";
        } else {
            $stmt = "UPDATE class_schedule.relationship_assignments_class_block SET  id_assignment = $id_assignment
            WHERE id_days = $id_day AND id_class_block = $id_block AND no_teacher = $id_teacher_sbj AND id_period_calendar = '$id_period_calendar'
            ";
        }


        if ($Attendance->saveAttendance($stmt)) {
            //--- --- ---//
            $data = array(
                'response' => true,
                'icon' => 'success',
                'message'                => 'Se asignó la materia correctamente!!'
            );
            //--- --- ---//
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'icon' => 'error',
                'message'                => 'Ocurrió un error al asignar la materia!'
            );
            //--- --- ---//
        }
    } else {

        $stmt = "SELECT DISTINCT concat(gps.group_code, ' | ', sbj.name_subject) AS assignment_description
    FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.assignments AS asg2 ON asg2.id_group = gps.id_group AND asg2.id_assignment != $id_assignment
    INNER JOIN class_schedule.relationship_assignments_class_block AS racb ON asg2.id_assignment = racb.id_assignment
    WHERE id_days = $id_day AND id_class_block = $id_block AND asg.id_assignment = $id_assignment AND racb.no_teacher != $id_teacher_sbj
        AND racb.id_period_calendar = '$id_period_calendar'
    ";

        $getExist = $groups->getGroupFromTeachers($stmt);
        $assignment_description = $getExist[0]->assignment_description;

        $data = array(
            'response' => false,
            'icon' => 'error',
            'message'                => 'Esté horario ya no se encuentra disponible!',
            'text' => 'La siguiente asignatura ocupa este horario: <br>' . $assignment_description
        );
    }
    echo json_encode($data);
}

function getAviablesClassrooms()
{
    $id_day = $_POST['id_day'];
    $id_block = $_POST['id_block'];
    $id_teacher_sbj = $_POST['id_teacher_sbj'];
    $id_period_calendar = $_POST['id_period_calendar'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT cls.*
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN class_schedule.classrooms AS cls
        LEFT JOIN class_schedule.relationship_assignments_class_block AS racb
             ON cls.id_classrooms = racb.id_classrooms AND racb.id_days = $id_day AND racb.id_class_block = $id_block  AND racb.id_period_calendar = '$id_period_calendar'
        WHERE racb.id_rel_assg_cb IS NULL
        ORDER BY name_classroom
        ";
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

function saveRelationShipCBASClassroom()
{
    $id_day = $_POST['id_day'];
    $id_block = $_POST['id_block'];
    $id_teacher_sbj = $_POST['id_teacher_sbj'];
    $id_classroom = $_POST['id_classroom'];
    $id_period_calendar = $_POST['id_period_calendar'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $Attendance = new Attendance;

    //CHECK IF EXIST GENERAL
    $stmt = "SELECT *
        FROM class_schedule.relationship_assignments_class_block AS racb
        WHERE id_days = $id_day AND id_class_block = $id_block AND id_classrooms = $id_classroom AND racb.id_period_calendar = $id_period_calendar
        ";

    $getExist = $groups->getGroupFromTeachers($stmt);
    if (empty($getExist)) {
        ///// CHECK IF EXISTS REGS TEACHER

        $stmt = "SELECT *
    FROM class_schedule.relationship_assignments_class_block AS racb
    WHERE id_days = $id_day AND id_class_block = $id_block AND no_teacher = $id_teacher_sbj AND racb.id_period_calendar = $id_period_calendar
    ";

        $getGroups = $groups->getGroupFromTeachers($stmt);
        if (empty($getGroups)) {
            $stmt = "INSERT INTO class_schedule.relationship_assignments_class_block 
    (id_class_block, id_days, id_classrooms, status, no_teacher, id_period_calendar)
    VALUES (
            $id_block,
            $id_day,
            $id_classroom,
            1,
            $id_teacher_sbj,
            '$id_period_calendar'
            )
    ";
        } else {
            $stmt = "UPDATE class_schedule.relationship_assignments_class_block SET id_classrooms = $id_classroom
    WHERE id_days = $id_day AND id_class_block = $id_block AND no_teacher = $id_teacher_sbj AND id_period_calendar = $id_period_calendar
    ";
        }



        if ($Attendance->saveAttendance($stmt)) {
            //--- --- ---//
            $data = array(
                'response' => true,
                'icon' => 'success',
                'message'                => 'Se asignó el aula correctamente!!'
            );
            //--- --- ---//
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'icon' => 'error',
                'message'                => 'Ocurrió un error al asignar el aula!'
            );
            //--- --- ---//
        }
    } else {
        $data = array(
            'response' => false,
            'icon' => 'error',
            'message'                => 'Está aula ya no está disponible en este horario!'
        );
    }


    echo json_encode($data);
}


function getAcademicLevels()
{
    $id_academic = $_POST['id_academic'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT * FROM 

        (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, acdlvldg.degree, acdlvldg.id_level_grade
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
    
        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, aclg.degree, aclg.id_level_grade
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.no_teacher, sbj.id_academic_area, aclg.degree, aclg.id_level_grade
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
        )
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = '$id_academic'
        ORDER BY degree
        ";
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

function getStudentsInfo()
{
    $id_student = $_POST['id_student'];

    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


    if (($grants & 8)) {
        $stmt = "SELECT fam.family_code, fam.family_name, student.birthdate, student.student_code, CONCAT(student.lastname, ' ', student.name) as student_name, CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS mail, 

        CASE WHEN fam.attorney = 1 THEN (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS cell_phone,

        (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_name,
        (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_cell_phone,
        (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_mail,

        (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_name,
        (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_cell_phone,
        (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_mail,


        (SELECT CONCAT(street, ' ', ext_number, (CASE WHEN int_number IS NULL THEN ',' WHEN int_number = 0 THEN ',' ELSE CONCAT(' int. ',int_number,', ') END),  colony, ', ', delegation, '. ', postal_code) 
        FROM families_ykt.addresses_families WHERE id_family_address = fam.id_family_address) AS direction 
        FROM school_control_ykt.students AS student
        INNER JOIN  families_ykt.families  AS fam ON student.id_family = fam.id_family
        WHERE student.id_student = $id_student
        ";
    }
    $getGroups = $groups->getGroupFromTeachers($stmt);

    if (!empty($getGroups)) {
        $getGroups = $getGroups[0];
        $family_name = $getGroups->family_name;
        $father_name = $getGroups->father_name;
        $mother_name = $getGroups->mother_name;
        $father_cell_phone = $getGroups->father_cell_phone;
        $mother_cell_phone = $getGroups->mother_cell_phone;
        $father_mail = $getGroups->father_mail;
        $mother_mail = $getGroups->mother_mail;

        $principal_mail = $getGroups->mail;
        $principal_cell_phone = $getGroups->cell_phone;
        $direction = $getGroups->direction;

        $student_name = $getGroups->student_name;
        $student_code = $getGroups->student_code;
        //--- --- ---//

        $arr_fecha = explode("-", $getGroups->birthdate);
        $mp = date('n', strtotime($getGroups->birthdate));

        $mes_pase = $meses[$mp];
        $ds = date('N', strtotime($getGroups->birthdate));
        $yearFecha = $arr_fecha[0];

        $fecha_nacimiento = $arr_fecha[2] . ' de ' . $mes_pase . ' de ' . $yearFecha;


        $html = "";
        $html .= "<div class='table-responsive table'>";
        $html .= "<div class='table-responsive table'>";
        $html .= '<img class="avatar_stud" src="../control_escolar/students_archives/' . $student_code . '.jpg">';
        $html .= '<h2>' . mb_strtoupper($student_code) . ' | ' . mb_strtoupper($student_name) . '</h2>';
        $html .= '<h3> F. NACIMIENTO: ' . mb_strtoupper($fecha_nacimiento) . '</h3><br>';
        $html .= '<table class="table table-striped table-bordered" id="tableAdmisionsCoordinator" style="table-layout: fixed;">';
        $html .= '<thead>
                    <tr  style="background-color: #18214d !important; color: #ffffff !important;">
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">FAMILIA</th>
                    </tr>
                </thead>
                <tbody class="list">';
        $html .= '
                 <tr>

                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($family_name) . '</td>
                </tr>
            </tbody>
            </table>
            ';
        $html .= '<table class="table table-striped table-bordered" id="tableAdmisionsCoordinator" style="table-layout: fixed;">';
        $html .= '<thead>
                        <tr  style="background-color: #18214d !important; color: #ffffff !important;">
                            <th style="text-align: center; font-size: 15px; vertical-align: middle;">DIRECCIÓN</th>
                        </tr>
                    </thead>
                    <tbody class="list">';
        $html .= '
                     <tr>
                        <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($direction) . '</td>
                    </tr>
                </tbody>
                </table>
                <br><br>';
        $html .= '<table class="table table-striped table-bordered" id="tableAdmisionsCoordinator" style="table-layout: fixed;">';
        $html .= '<thead>
                    <tr  style="background-color: #18214d !important; color: #ffffff !important;">
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">TELÉFONO</th>
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">CORREO ELECTRÓNICO</th>
                    </tr>
                </thead>
                <tbody class="list">';
        $html .= '
                 <tr>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . $principal_cell_phone . '</td>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . $principal_mail . '</td>
                </tr>
            </tbody>
            </table>
            <br><br>';

        $html .= '<table class="table table-striped table-bordered" id="tableAdmisionsCoordinator" style="table-layout: fixed;">';
        $html .= '<thead>
                    <tr  style="background-color: #18214d !important; color: #ffffff !important;">
                    <th style="text-align: center; font-size: 15px; vertical-align: middle;"></th>
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">NOMBRE</th>
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">TELÉFONO</th>
                        <th style="text-align: center; font-size: 15px; vertical-align: middle;">CORREO ELECTRÓNICO</th>
                    </tr>
                </thead>
                <tbody class="list">';
        $html .= '
                 <tr>
                 <th style="text-align: center; font-size: 15px; vertical-align: middle;"><strong>PADRE</strong></th>
                 <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($father_name) . '</td>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($father_cell_phone) . '</td>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . ($father_mail) . '</td>
                </tr>
                <tr>
                 <th style="text-align: center; font-size: 15px; vertical-align: middle;"><strong>MADRE</strong></th>
                 <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($mother_name) . '</td>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . mb_strtoupper($mother_cell_phone) . '</td>
                    <td style="text-align: center; font-size: 15px; vertical-align: middle;">' . ($mother_mail) . '</td>
                </tr>
            </tbody>
            </table>
            <br><br>';

        $data = array(
            'response' => true,
            'html' => $html
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
function getPeriods()
{
    $id_academic_level = $_POST['id_academic_level'];
    $id_teacher = $_POST['id_teacher'];
    $id_academic = $_POST['id_academic'];

    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;

    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT percal.*
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = alg.id_academic_level
        INNER JOIN school_control_ykt.level_combinations AS lvl_comb 
            ON lvl_comb.id_academic_area = sbj.id_academic_area
            AND lvl_comb.id_academic_level = alg.id_academic_level
            AND lvl_comb.id_campus = gps.id_campus
            AND gps.id_section = lvl_comb.id_section
            
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvl_comb.id_level_combination
        WHERE asg.no_teacher = $id_teacher
        AND acl.id_academic_level = $id_academic_level
        AND sbj.id_academic_area = $id_academic
        ORDER BY percal.no_period
        ";
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

function setRepeatCycle()
{

    $id_student = $_POST['id_student'];
    $checked = $_POST['checked'];
    $student_code = $_POST['student_code'];


    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $Attendance = new Attendance;

    //CHECK IF EXIST GENERAL

    if ($checked == 0) {
        $stmt = "DELETE FROM control_escolar.alumnos_repitientes WHERE codigo_alumno = '$student_code'
    ";
    } else {
        $stmt = "INSERT INTO control_escolar.alumnos_repitientes (codigo_alumno, repite_anio)
        VALUES(
            '$student_code',
            'true'
        )
    ";
    }


    if ($Attendance->saveAttendance($stmt)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'icon' => 'success',
            'message'                => 'Se actualizó la información correctamente!!'
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'icon' => 'error',
            'message'                => 'Ocurrió un error al modificar la información!'
        );
        //--- --- ---//
    }



    echo json_encode($data);
}
