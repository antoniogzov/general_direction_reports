<?php

class Attendance extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getAttendanceIndex($id_assignment, $fecha)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM attendance_records.attendance_index WHERE obligatory = 1 
            AND apply_date like '$fecha%' AND obligatory='1' AND id_assignment ='$id_assignment' AND valid_assistance = 1
            ORDER BY id_attendance_index");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIndexReportCoordinator($id_assignment, $fecha_min, $fecha_max)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM attendance_records.attendance_index WHERE obligatory = 1 
            AND DATE(apply_date) >= '$fecha_min%' AND DATE(apply_date) <= '$fecha_max%' AND obligatory='1' AND id_assignment ='$id_assignment' AND valid_assistance = 1
            ORDER BY id_attendance_index");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIndex4($id_assignment, $fecha)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT t1.* 
        FROM attendance_records.attendance_index AS t1
        WHERE DATE(apply_date) LIKE '$fecha%' AND t1.obligatory = 1 AND t1.id_assignment = $id_assignment AND id_attendance_index = 
        (SELECT id_attendance_index
         FROM attendance_records.attendance_index AS t2
         WHERE t1.id_assignment = t2.id_assignment AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block
        ORDER BY t2.id_attendance_index DESC LIMIT 1)");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIncidents()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM attendance_records.incidents_attendance ORDER BY incident ASC");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsByTeacher($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM school_control_ykt.assignments AS asg
            WHERE asg.no_teacher = '$no_teacher'");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceIndexBasedStudent($id_assignment,  $fechaInicio, $fechaMaxima, $id_student)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT t1.* 
            FROM attendance_records.attendance_index AS t1
            INNER JOIN attendance_records.attendance_record AS t2 ON t1.id_attendance_index = t2.id_attendance_index
            WHERE DATE(apply_date) >= '$fechaInicio'
                AND DATE(apply_date) <= '$fechaMaxima'
                AND t1.obligatory = 1
                AND t1.valid_assistance = 1
                AND t1.id_assignment = $id_assignment
                AND t2.id_student = $id_student
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceIndexTeacherReport($id_assignment,  $fechaInicio, $fechaMaxima)
    {
        $parameters_report_type = '';

        $results = array();

        $query = $this->conn->query("
            SELECT t1.* 
            FROM attendance_records.attendance_index AS t1
            WHERE DATE(apply_date) >= '$fechaInicio'
                AND DATE(apply_date) <= '$fechaMaxima' $parameters_report_type
                AND t1.obligatory = 1
                AND t1.valid_assistance = 1
                AND t1.id_assignment = $id_assignment
                AND id_attendance_index =
                (SELECT id_attendance_index
                 FROM attendance_records.attendance_index AS t2
                 WHERE t1.id_assignment = t2.id_assignment AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block
                ORDER BY t2.id_attendance_index DESC LIMIT 1)
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceIndex2($id_assignment,  $fechaInicio, $fechaMaxima)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT t1.* 
            FROM attendance_records.attendance_index AS t1
            WHERE DATE(apply_date) >= '$fechaInicio' AND DATE(apply_date) <= '$fechaMaxima' AND t1.obligatory = 1 AND t1.id_assignment = $id_assignment AND id_attendance_index = 
            (SELECT id_attendance_index
             FROM attendance_records.attendance_index AS t2
             WHERE t1.id_assignment = t2.id_assignment AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block
            ORDER BY t2.id_attendance_index DESC LIMIT 1)
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceIndex3($no_teacher,  $fecha)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT t1.* 
        FROM attendance_records.attendance_index AS t1
        WHERE DATE(apply_date) LIKE '$fecha%'  AND t1.obligatory = 1 AND t1.`teacher_passed_attendance` = '$no_teacher' AND id_attendance_index = 
        (SELECT id_attendance_index
        FROM attendance_records.attendance_index AS t2
        WHERE t1.`teacher_passed_attendance` = t2.`teacher_passed_attendance` AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block AND t1.`id_assignment` = t2.id_assignment
        ORDER BY t2.id_attendance_index DESC LIMIT 1)
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceAcademicLevel($no_teacher,  $fecha, $id_academic_level)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT t1.* 
        FROM attendance_records.attendance_index AS t1
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = t1.id_assignment AND asg.no_teacher = '$no_teacher'
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acl ON acl.id_level_grade = gps.id_level_grade
        WHERE DATE(apply_date) LIKE '$fecha%' AND t1.valid_assistance = 1 AND acl.id_academic_level = $id_academic_level AND t1.obligatory = 1  AND id_attendance_index = 
        (SELECT id_attendance_index
        FROM attendance_records.attendance_index AS t2
        WHERE t1.`teacher_passed_attendance` = t2.`teacher_passed_attendance` AND DATE(t2.apply_date) = DATE(t1.apply_date) AND t1.class_block = t2.class_block AND t1.`id_assignment` = t2.id_assignment
        ORDER BY t2.id_attendance_index DESC LIMIT 1)
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmnetsByTeacher($no_teacher,  $id_academic_level)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT asg.id_assignment, sbj.name_subject, gps.group_code, colab.no_colaborador, 
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
        WHERE asg.no_teacher ='$no_teacher' AND al.id_academic_level = '$id_academic_level'  order by group_code
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmnetsByTeacherAndArea($no_teacher,  $id_academic_level, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT asg.id_assignment, sbj.name_subject, gps.group_code, colab.no_colaborador, 
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
        WHERE asg.no_teacher ='$no_teacher' AND al.id_academic_level = '$id_academic_level'  AND sbj.id_academic_area = $id_academic_area  order by group_code
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceStudent($id_attendance_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM attendance_records.attendance_record WHERE id_attendance_index ='$id_attendance_index' AND id_student ='$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetIdAssignmentByIdGroupAndTeacher($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

        (SELECT sbj.id_academic_area, assg.id_assignment, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code, assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 

        SELECT  sbj.id_academic_area, asgm.id_assignment, sbj.name_subject, sbj.id_subject, gps.id_group, gps.group_code, asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade

        UNION 

        SELECT  sbj.id_academic_area, asg.id_assignment, sbj.name_subject, sbj.id_subject, groups.id_group, groups.group_code, asg.print_school_report_card, asg.assignment_active, asg.no_teacher, groups.group_type_id
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)

        AS u

        WHERE no_teacher = '$_SESSION[colab]' AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4 AND u.id_group = '$id_group'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentAttendWeek($id_assignment, $day, $id_student, $class_block)
    {
        $results = array();

        $query = $this->conn->query(" SELECT atr.* 
        FROM attendance_records.attendance_index AS ati 
        INNER JOIN attendance_records.attendance_record AS atr ON (SELECT id_attendance_index FROM attendance_records.attendance_index WHERE class_block = '$class_block' AND id_assignment = '$id_assignment' AND apply_date LIKE '$day%' ORDER BY id_attendance_index DESC LIMIT 1) = atr.id_attendance_index
        WHERE ati.class_block = '$class_block' AND ati.id_assignment = '$id_assignment' AND atr.id_student = '$id_student' AND apply_date LIKE '$day%'  ORDER BY ati.id_attendance_index DESC LIMIT 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function GetIdAssignmentByIdGroupAndSubject($id_group, $id_subject)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT asg.* 
        FROM school_control_ykt.assignments AS asg
        WHERE id_group = '$id_group' AND id_subject = '$id_subject'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getRegisteredClasses($id_assignment, $fecha_min, $fecha_max)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM attendance_records.attendance_index WHERE id_attendance_index =
            (SELECT id_attendance_index FROM attendance_records.attendance_index WHERE obligatory = 1 
            AND apply_date > '$fecha_min' AND apply_date < '$fecha_max' AND id_assignment ='$id_assignment' 
            ORDER BY id_attendance_index DESC LIMIT 1)");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIndexDate($id_att_index)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT apply_date, id_assignment, class_block, teacher_passed_attendance FROM attendance_records.attendance_index WHERE id_attendance_index = '$id_att_index';");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsByGroup($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT asg.id_assignment, sbj.name_subject
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        WHERE id_group = '$id_group' AND asg.no_teacher = '$_SESSION[colab]'");

        $query2 = $this->conn->query("SELECT * FROM 

        (SELECT assg.id_assignment, sbj.name_subject, groups.id_group, rel_coord_aca.no_teacher, print_school_report_card, assignment_active
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 

        SELECT asgm.id_assignment, sbj.name_subject, gps.id_group, rel_coord_aca.no_teacher, print_school_report_card, assignment_active
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade)

        AS u

        WHERE id_group = '$id_group' AND no_teacher = '$_SESSION[colab]' AND print_school_report_card = 1 AND assignment_active = 1 order by name_subject ASC
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        while ($row = $query2->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllStudentsTeacher()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, student.id_student,  student.id_family, student.group_id, student.student_code, gps.group_code, cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
                                AS name_student, student.id_status_type, CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
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
                                
                                FROM 

                                (SELECT groups.id_group, col.no_teacher
                                FROM school_control_ykt.groups AS groups
                                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
                                INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
                                INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
                                
                                UNION 

                                SELECT  gps.id_group, col.no_teacher
                                FROM  school_control_ykt.assignments AS asgm 
                                INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
                                INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
                                )

                                AS u
                                INNER JOIN school_control_ykt.students AS student ON u.id_group = student.group_id
                                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = u.id_group
                                INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
                                INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
                                INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus

                                WHERE no_teacher = $_SESSION[colab]  AND student.status = 1
                                ORDER BY academic_level
                                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllStudentsCoordinator()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, student.id_student,  student.id_family, student.group_id, student.student_code, gps.group_code, cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
                                AS name_student, student.id_status_type, CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
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
                                
                                FROM 

                                (SELECT groups.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
                                INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
                                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
                                INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
                                INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
                                
                                UNION 

                                SELECT  gps.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
                                INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
                                INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
                                )

                                AS u
                                INNER JOIN school_control_ykt.students AS student
                                INNER JOIN school_control_ykt.inscriptions AS insc ON student.id_student = insc.id_student AND insc.id_group = u.id_group
                                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = u.id_group AND insc.id_group = gps.id_group AND gps.group_type_id = 1
                                INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
                                INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
                                INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus

                                WHERE no_teacher = $_SESSION[colab]  AND student.status = 1
                                ORDER BY academic_level
                                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectInfo($id_assignment)
    {
        $results = array();

        $query = $this->conn->query("SELECT sbj.name_subject 
            FROM school_control_ykt.assignments as asg
            INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
            WHERE asg.id_assignment = '$id_assignment';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getTeacherInfo($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador ,' ', apellido_materno_colaborador) AS name 
        FROM colaboradores_ykt.colaboradores 
        WHERE no_colaborador =  '$no_teacher';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsCoordinator()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, student.id_student,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree, u.*

        FROM 

        (SELECT rel_coord_aca.no_teacher AS no_coordinator, groups.id_group, group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 

        SELECT  rel_coord_aca.no_teacher AS no_coordinator, gps.id_group, group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        )

        AS u
        INNER JOIN school_control_ykt.students AS student ON u.id_group = student.group_id
        INNER JOIN student_incidents.student_incidents_log AS sil ON sil.id_student = student.id_student
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = sil.no_teacher_registered
        WHERE no_coordinator = '$_SESSION[colab]' 
        ORDER BY sil.incident_date DESC
            ");

        //INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getIncidentsTeacher()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM  school_control_ykt.assignments AS asg 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE asg.no_teacher =  '$_SESSION[colab]'
            ");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsCoordinatorDateRange($date_start, $date_end)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE rma.no_teacher =  '$_SESSION[colab]' AND sil.incident_date BETWEEN '$date_start' AND '$date_end'
            ");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsTeacherDateRange($date_start, $date_end)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM  school_control_ykt.assignments AS asg 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE asg.no_teacher =  '$_SESSION[colab]' AND sil.incident_date BETWEEN '$date_start' AND '$date_end'
            ");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getIncidentsByGroupCoordinator($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = sil.id_student
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE rma.no_teacher =  '$_SESSION[colab]' AND ins.id_group = '$id_group';
            ");
        $query2 = $this->conn->query("SELECT DISTINCT sil.*, 
            CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
            CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
            FROM student_incidents.student_incidents_log AS sil 
            INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = sil.no_teacher_registered
            INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = sil.id_student
            INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
            INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
            WHERE sil.no_teacher_registered =  '$_SESSION[colab]' AND ins.id_group = '$id_group';
                ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        while ($row = $query2->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsByGroupTeacher($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = sil.id_student
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE asg.no_teacher =  '$_SESSION[colab]' AND ins.id_group = '$id_group';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsByTeacher($id_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sil.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name, 
        CONCAT(student.lastname, ' ', student.name) AS name_student, student.student_code, ic.incident_description, icla.clasification_color_html, icla.clasification_degree
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        INNER JOIN student_incidents.student_incidents_log AS sil ON asg.no_teacher = sil.no_teacher_registered
        INNER JOIN student_incidents.incidence_code as ic ON ic.id_incidence_code = sil.id_incidence_code
        INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_student = sil.id_student
        INNER JOIN student_incidents.incident_clasification AS icla ON icla.id_incident_clasification = ic.id_incident_clasification
        INNER JOIN school_control_ykt.students AS student ON sil.id_student = student.id_student
        WHERE rma.no_teacher =  '$_SESSION[colab]' AND sil.no_teacher_registered = '$id_teacher';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getStudentAttendance($id_att_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT COUNT(*) AS student_base 
            FROM attendance_records.attendance_record
            WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student' AND attend='1';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentAttendance2($id_att_index, $id_student)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT * FROM attendance_records.attendance_record WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student' AND attend = '1';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentAttendanceDetail2($id_att_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT atr.*, iat.double_absence, iat.apply_justification
        FROM attendance_records.attendance_record AS atr
        INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
            WHERE atr.id_attendance_index = '$id_att_index' AND atr.id_student = '$id_student';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentAttendanceByTypes($id_att_index, $id_student, $att_types)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT COUNT(*) AS student_base 
            FROM attendance_records.attendance_record AS t1
            INNER JOIN attendance_records.incidents_attendance AS t2 ON t1.incident_id = t2.incident_id
            INNER JOIN attendance_records.attendance_index AS t3 ON t1.id_attendance_index = t3.id_attendance_index
            INNER JOIN school_control_ykt.assignments AS t4 ON t3.id_assignment = t4.id_assignment
            INNER JOIN school_control_ykt.inscriptions AS t5 ON t4.id_group = t5.id_group AND t5.id_student = $id_student
            WHERE t1.id_attendance_index = $id_att_index AND t1.id_student = $id_student  AND (t1.attend = 1 $att_types);
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAttendanceByTypes2($id_att_index, $id_student, $att_types)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT COUNT(*) AS student_base 
            FROM attendance_records.attendance_record AS t1
            INNER JOIN attendance_records.incidents_attendance AS t2 ON t1.incident_id = t2.incident_id
            WHERE t1.id_attendance_index = $id_att_index AND t1.id_student = $id_student AND t1.apply_justification = 1 AND (t1.attend = 1 $att_types);
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function generateAssignmentAverage($id_assignment, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT AVG(grape.grade_period) AS average_period_assignment
            FROM iteach_grades_quantitatives.final_grades_assignment AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
            WHERE fga.id_assignment = '$id_assignment' AND grape.id_period_calendar = '$id_period_calendar' AND grape.grade_period IS NOT NULL;
            ");

        if (!empty($query)) {
            $row = $query->fetchAll(PDO::FETCH_OBJ);
            $row = $row[0];

            $query_avg = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.assignments_averages_period 
                    WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar';
                    ");
            $query_avg = $query_avg->fetchAll(PDO::FETCH_OBJ);

            if (count($query_avg) > 0) {
                $query = $this->conn->query("UPDATE iteach_grades_quantitatives.assignments_averages_period 
                        SET assignment_average = '$row->average_period_assignment'
                        WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar';
                        ");
            } else {
                $query = $this->conn->query("INSERT INTO iteach_grades_quantitatives.assignments_averages_period (id_assignment, id_period_calendar, assignment_average) 
                    VALUES ('$id_assignment', '$id_period_calendar', '$row->average_period_assignment')");
            }
        }



        $query2 = $this->conn->query("SELECT AVG(grape.grade_period_calc) AS assignment_average_calc
            FROM iteach_grades_quantitatives.final_grades_assignment AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
            WHERE fga.id_assignment = '$id_assignment' AND grape.id_period_calendar = '$id_period_calendar' AND grape.grade_period_calc IS NOT NULL;
            ");

        if (!empty($query2)) {
            $row2 = $query2->fetchAll(PDO::FETCH_OBJ);
            $row2 = $row2[0];
            $query2 = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.assignments_averages_period 
                    WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar';
                    ");
            $query2 = $query2->fetchAll(PDO::FETCH_OBJ);
            if (!empty($query2)) {
                $query2 = $this->conn->query("UPDATE iteach_grades_quantitatives.assignments_averages_period 
                        SET assignment_average_calc = '$row2->assignment_average_calc'
                        WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar';
                        ");
            } else {
                $query2 = $this->conn->query("INSERT INTO iteach_grades_quantitatives.assignments_averages_period (id_assignment, id_period_calendar, assignment_average_calc) 
                    VALUES ('$id_assignment', '$id_period_calendar', '$row2->assignment_average_calc');
                    ");
            }
        }
    }
    public function getAssignmentAverage($id_assignment, $id_period_calendar, $average_type)
    {
        $results = array();

        if ($average_type == 'normal') {
            $query_avg = $this->conn->query("SELECT assignment_average AS avg_assignment FROM iteach_grades_quantitatives.assignments_averages_period 
                    WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar' AND assignment_average IS NOT NULL AND assignment_average != 0;
                    ");

            while ($row = $query_avg->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } else {
            $query_avg = $this->conn->query("SELECT assignment_average_calc AS avg_assignment FROM iteach_grades_quantitatives.assignments_averages_period 
                    WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar' AND assignment_average_calc != 0 AND assignment_average_calc IS NOT NULL
                    ");

            while ($row = $query_avg->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function getStudentIncidentCount($id_att_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT count(*) AS absence_attendance
                FROM attendance_records.attendance_record as atr
                INNER JOIN  attendance_records.incidents_attendance AS inc ON atr.incident_id = inc.incident_id
                WHERE
                    id_attendance_index = '$id_att_index'
                    AND id_student ='$id_student'
                    AND (atr.attend = 0 OR ((atr.attend = 0 AND atr.apply_justification != 1) OR (atr.attend = 0 AND inc.apply_justification != 1)))
                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListStudent($id_group, $id_subject)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT student.id_student, student.id_family, student.group_id, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student, student.id_status_type
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
            INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
            WHERE asg.id_group = '$id_group' AND asg.id_subject = '$id_subject' AND student.status = 1
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getInfoListStudent()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, student.id_student,  student.id_family, student.group_id, student.student_code, gps.group_code, cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
                                AS name_student, student.id_status_type, CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
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
                                
                                FROM 

                                (SELECT groups.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
                                INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
                                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
                                INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
                                INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
                                
                                UNION 

                                SELECT  gps.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
                                INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
                                INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
                                )

                                AS u
                                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = u.id_group
                                INNER JOIN school_control_ykt.inscriptions AS ins ON gps.id_group = ins.id_group
                                INNER JOIN school_control_ykt.students AS student ON student.id_student = ins.id_student
                                INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
                                INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
                                INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus

                                WHERE no_teacher = $_SESSION[colab]  AND student.status = 1
                                ORDER BY student.lastname
                                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListGroupAss($teacher, $id_subject)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT gps.id_group, asg.id_assignment 
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE asg.no_teacher = '$teacher' AND sbj.id_subject = '$id_subject'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListStudentByGroup($id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT student.id_student, student.id_family, student.group_id, student.student_code, UPPER(CONCAT(student.lastname, ' ', student.name)) AS name_student, student.id_status_type, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS tutor, gps.group_code
        FROM school_control_ykt.students AS student
        INNER JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = ins.id_group
        LEFT JOIN colaboradores_ykt.colaboradores AS colab ON gps.no_tutor = colab.no_colaborador
        WHERE ins.id_group = '$id_group' AND student.status = 1
        ORDER BY student.lastname ASC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function breakdownJustify($id_student, $today_time)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT * FROM absence_excuse.absences_excuse WHERE id_student = '$id_student' AND active_excuse = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function absencesHistorical($id_student, $type_incident)
    {
        $results = array();

        $query = $this->conn->query("SELECT atr.*
        FROM attendance_records.attendance_record AS atr
        INNER JOIN attendance_records.attendance_index AS ati ON atr.id_attendance_index = ati.id_attendance_index
        WHERE atr.id_student = '$id_student' AND atr.incident_id = '$type_incident' AND ati.valid_assistance = 1 AND atr.attend = 0");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function breakdownIncidents($id_student, $today_time)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT * FROM student_incidents.student_incidents_log WHERE id_student = '$id_student' AND active_incident = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function breakdownJustifyDetail($id_student, $id_absences_excuse)
    {
        $results = array();

        $query = $this->conn->query("SELECT 
        ext.excuse_description,
        ae.*, 
        student.id_student, 
        student.student_code, 
        CONCAT(student.lastname , ' ', student.name) AS student_name,
        exbr.day_absence_comment, 
        exbr.active_excuse, 
        exbr.apply_excuse,
        exbr.absence_day,
        exbr.id_absences_excuse_breakdown 
        FROM absence_excuse.absences_excuse AS ae
        INNER JOIN absence_excuse.absences_excuse_breakdown AS exbr ON exbr.id_absences_excuse = ae.id_absences_excuse
        INNER JOIN school_control_ykt.students AS student ON student.id_student = ae.id_student
        INNER JOIN absence_excuse.excuse_types ext ON ae.id_excuse_types = ext.id_excuse_types
        WHERE ae.id_student = $id_student AND ae.id_absences_excuse = '$id_absences_excuse' AND ae.active_excuse = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function breakdownIncidences($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT * FROM student_incidents.student_incidents_log WHERE id_student = '$id_student' AND active_incident = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllJustifyTypes()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM absence_excuse.excuse_types ORDER BY excuse_description");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListGroupByAcademicLevel($id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
                SELECT DISTINCT gps.* 
                FROM school_control_ykt.groups AS gps
                INNER JOIN school_control_ykt.assignments AS asg ON gps.id_group = asg.id_group
                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = aclg.id_academic_level
                WHERE aclg.id_academic_level = $id_academic_level AND asg.no_teacher = $no_teacher
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListGroupByAcademicLevelCoordinator($id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
                SELECT DISTINCT gps.* 
                FROM iteach_academic.relationship_managers_assignments AS rma
                INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
                INNER JOIN school_control_ykt.groups AS gps  ON gps.id_group = asg.id_group
                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = aclg.id_academic_level
                WHERE aclg.id_academic_level = $id_academic_level AND asg.no_teacher = $no_teacher
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllMyTeachersByAcademicLevel($id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

            (SELECT col.no_colaborador, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS teacher_name, rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, acl.id_academic_level, sbj.id_academic_area, aca.name_academic_area
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

            SELECT col.no_colaborador, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS teacher_name, rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level, sbj.id_academic_area, aca.name_academic_area
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

            WHERE u.no_teacher = $no_teacher AND u.id_academic_level = $id_academic_level AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.teacher_name ASC
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllMyTeachersByAcademicLevelAndAcademicArea($id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

            (SELECT col.no_colaborador, UPPER(acl.academic_level) AS academic_level, (CASE WHEN groups.id_section = 1 THEN 'VARONES' WHEN groups.id_section = 2 THEN 'MUJERES' ELSE 'MIXTO' END) as section,  CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS teacher_name, rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, acl.id_academic_level, sbj.id_academic_area, aca.name_academic_area
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

            SELECT col.no_colaborador, UPPER(acl.academic_level) AS academic_level, (CASE WHEN gps.id_section = 1 THEN 'VARONES' WHEN gps.id_section = 2 THEN 'MUJERES' ELSE 'MIXTO' END) as section, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS teacher_name, rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level, sbj.id_academic_area, aca.name_academic_area
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

            WHERE u.no_teacher = $no_teacher AND u.id_academic_level = $id_academic_level AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.teacher_name ASC
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllMyGroupsByAcademicLevelAndAcademicArea($id_academic_level, $id_group, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

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

            WHERE u.no_teacher = $no_teacher AND u.id_academic_level = $id_academic_level AND id_group = $id_group AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.group_code ASC
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllMyGroupsByAcademicLevelAndAcademicAreaTeacher($id_academic_level, $id_group, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT  groups.group_code, groups.id_group, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level, asgm.no_teacher
            FROM school_control_ykt.assignments AS asgm
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS groups ON asgm.id_group = groups.id_group 
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
            WHERE asgm.no_teacher = $no_teacher AND aclg.id_academic_level = $id_academic_level AND groups.id_group = $id_group AND print_school_report_card = 1 AND assignment_active = 1
            ORDER BY group_code ASC
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsByGroup($id_academic_level, $id_group, $no_teacher, $no_period)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

            (SELECT assg.print_school_report_card,  groups.id_group, assg.id_assignment, assg.show_list_teacher, sbj.name_subject, sbj.short_name, assg.assignment_active, acl.id_academic_level,  rel_coord_aca.no_teacher, CONCAT(col.nombres_colaborador, ' ', col.apellido_paterno_colaborador ,' ', col.apellido_materno_colaborador) AS assg_teacher
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

            SELECT asgm.print_school_report_card, gps.id_group, asgm.id_assignment, asgm.show_list_teacher, sbj.name_subject, sbj.short_name,  asgm.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher, CONCAT(col.nombres_colaborador, ' ', col.apellido_paterno_colaborador ,' ', col.apellido_materno_colaborador) AS assg_teacher
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

            WHERE u.no_teacher = $no_teacher AND u.id_academic_level = $id_academic_level AND (show_list_teacher = $no_period OR show_list_teacher = 0) AND id_group = $id_group AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.name_subject ASC
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsByGroupTeacher($id_academic_level, $id_group, $no_teacher, $no_period)
    {
        $results = array();

        $query = $this->conn->query("SELECT asgm.print_school_report_card, gps.id_group, asgm.id_assignment, asgm.show_list_teacher, sbj.name_subject, sbj.short_name,  asgm.assignment_active, acl.id_academic_level, CONCAT(col.nombres_colaborador, ' ', col.apellido_paterno_colaborador ,' ', col.apellido_materno_colaborador) AS assg_teacher
            FROM school_control_ykt.assignments AS asgm
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
            
            WHERE asgm.no_teacher = $no_teacher AND acl.id_academic_level = $id_academic_level AND (show_list_teacher = $no_period OR show_list_teacher = 0) AND gps.id_group = $id_group AND print_school_report_card = 1 AND assignment_active = 1
            ORDER BY name_subject ASC
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicAreasCoordinator($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 
    
        (SELECT assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE no_teacher = $no_teacher  AND print_school_report_card = 1 AND assignment_active = 1 
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodCalendar($id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT *
            FROM iteach_grades_quantitatives.period_calendar
            WHERE id_period_calendar = '$id_period_calendar'");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentsByGroup($group_id)
    {
        $results = array();

        $query = $this->conn->query(" SELECT student.id_student, student.student_code, UPPER(CONCAT(student.lastname , ' ', student.name)) AS student_name
        FROM school_control_ykt.students AS student
        INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
        WHERE inscription.id_group = '$group_id' AND student.status = 1
        ORDER BY student.lastname
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getRowspanWASR($id_student, $init_date, $final_date, $parms_academic_area)
    {
        $results = array();

        $query = $this->conn->query(" SELECT DATE(ati.apply_date),  COUNT(*) AS rowspan 
        FROM attendance_records.attendance_record AS atr
        INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
        INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        WHERE atr.id_student = '$id_student' AND ati.valid_assistance = 1 AND ati.obligatory = 1
        AND DATE(ati.apply_date) BETWEEN '$init_date' AND '$final_date' $parms_academic_area
        GROUP BY DATE(ati.apply_date)
        ORDER BY rowspan DESC LIMIT 1
        ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIndexStudentReport($id_student, $date, $parms_academic_area)
    {
        $results = array();

        $query = $this->conn->query("SELECT asg.id_assignment, DATE(ati.apply_date) AS pass_date, sbj.id_academic_area, ati.class_block, UPPER(iat.incident) AS incident_absence,
                CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name, ati.id_attendance_index, atr.id_attendance_record,
                iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident,
                sbj.name_subject, sbj.short_name, 
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN 'FJ'
                WHEN atr.attend = 0 THEN 'A'
                WHEN atr.attend = 1 THEN 'P'
                END 
                AS std_attend_text,
                CASE 
                
                WHEN ((atr.attend = 0) AND (atr.apply_justification = 1 OR iat.apply_justification = 1)) THEN '#ffcd7d'
                WHEN atr.attend = 0 THEN '#ff7e75'
                WHEN atr.attend = 1 THEN '#b8ffb0'
                END 
                AS std_attend_color
                                          FROM school_control_ykt.students AS stud
                                            INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                            INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                            INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
                                            INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
                                            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                            INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                            WHERE
                                            stud.id_student =$id_student
                                            AND DATE(ati.apply_date) = '$date'
                                            AND ati.valid_assistance = 1
                                            AND ati.obligatory = 1
                                            $parms_academic_area
                                            order by ati.apply_date ASC
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getLevelCombinationByGroupID($id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT lc.id_level_combination
        FROM school_control_ykt.level_combinations AS lc
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
        INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
        INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
        WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = subject.id_academic_area 
        AND assignment.id_group = '$id_group' LIMIT 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsByIDGroup($id_group, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT asg.*
        FROM school_control_ykt.assignments AS asg
        INNER JOIN  school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        WHERE asg.no_teacher = '$no_teacher' AND gps.id_group = '$id_group'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getIncidentsClasifications($id_group)
    {
        $results = array();

        $query = $this->conn->query(
            " SELECT DISTINCT in_code.incident_subclasification, in_clasi.id_incident_clasification, in_clasi.bootstrap_class
            FROM school_control_ykt.groups AS gps
            INNER JOIN school_control_ykt.academic_levels_grade AS acl ON gps.id_level_grade = acl.id_level_grade
            INNER JOIN student_incidents.incident_combinations AS in_comb ON in_comb.id_academic_level = acl.id_academic_level OR in_comb.id_academic_level = 999
            INNER JOIN student_incidents.incident_clasification AS in_clasi ON in_clasi.id_incident_combinations = in_comb.id_incident_combinations
            INNER JOIN student_incidents.incidence_code AS in_code on in_code.id_incident_clasification = in_clasi.id_incident_clasification AND in_comb.id_incident_combinations = in_code.id_incident_combinations
            WHERE gps.id_group = '$id_group'  ORDER BY incident_subclasification ASC"
        );

        /* "
           SELECT DISTINCT in_code.incident_subclasification, in_clasi.bootstrap_class
        FROM school_control_ykt.groups AS gps
        INNER JOIN school_control_ykt.academic_levels_grade AS acl ON gps.id_level_grade = acl.id_level_grade
        INNER JOIN student_incidents.incident_combinations AS in_comb ON in_comb.id_academic_level = acl.id_academic_level
        INNER JOIN student_incidents.incident_clasification AS in_clasi ON in_clasi.id_incident_combinations = in_comb.id_incident_combinations
        INNER JOIN student_incidents.incidence_code AS in_code on in_code.id_incident_clasification = in_clasi.id_incident_clasification
        ORDER BY incident_subclasification ASC
                " */

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListIncidentsClasif($incident_subclasification, $id_incident_clasification)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT in_code.* 
        FROM student_incidents.incidence_code AS in_code
        WHERE in_code.incident_subclasification = '$incident_subclasification' AND id_incident_clasification = '$id_incident_clasification' ORDER BY incident_description ASC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getIncidentsCatalog($id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT in_desc.incident_description 
        FROM school_control_ykt.groups AS gps
        INNER JOIN school_control_ykt.academic_levels_grade AS acl ON gps.id_level_grade = acl.id_level_grade
        INNER JOIN student_incidents.incident_combinations AS in_comb ON in_comb.id_academic_level = acl.id_academic_level
        INNER JOIN student_incidents.incidence_code AS in_desc ON in_desc.id_incident_combinations = in_comb.id_incident_combinations
        WHERE gps.id_group  = '$id_group' 
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getCountStudentByGroup($id_group)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT COUNT(*) AS students
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
            WHERE ins.id_group = '$id_group'  AND student.status = 1
            ORDER BY student.lastname ASC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getTeacherSection()
    {
        $results = array();

        $query = $this->conn->query("
        SELECT  DISTINCT aca.id_academic_area, aca.name_academic_area
        FROM iteach_academic.relationship_managers_assignments as rma
        INNER JOIN school_control_ykt.assignments as asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.subjects as sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$_SESSION[colab]'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllMyTeachersByAcademic($id_academic)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT  DISTINCT colab.no_colaborador, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM iteach_academic.relationship_managers_assignments as rma
        INNER JOIN school_control_ykt.assignments as asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects as sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$_SESSION[colab]' AND sbj.id_academic_area = '$id_academic'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllSubjectsFromMyTeachers($id_academic, $id_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

        (SELECT assg.no_teacher, sbj.id_academic_area, sbj.name_subject, sbj.id_subject,  colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
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
    
        SELECT asgm.no_teacher, sbj.id_academic_area, sbj.name_subject, sbj.id_subject,  colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.no_teacher, sbj.id_academic_area, sbj.name_subject, sbj.id_subject,  colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
    
        WHERE no_teacher = '$id_teacher' AND id_academic_area = '$id_academic' ORDER BY teacher_name ASC
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllIndexesCoord($id_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT att.*, u.* FROM 

        (SELECT assg.id_assignment, colab.no_colaborador, rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT asgm.id_assignment, colab.no_colaborador, rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject,  gps.id_group, gps.group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.id_assignment, colab.no_colaborador, asg.no_teacher, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
        INNER JOIN attendance_records.attendance_index AS att ON u.id_assignment = att.id_assignment 
        WHERE no_colaborador = $id_teacher AND att.valid_assistance = 1  AND no_teacher = '$_SESSION[colab]' ORDER BY id_attendance_index DESC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllIndexesCoordReport($id_teacher, $report_type)
    {
        $results = array();
        $parameters_report_type = '';
        if ($report_type == 1) {
            $parameters_report_type = " AND att.`teacher_passed_attendance` = '$id_teacher' ";
        }
        $query = $this->conn->query("SELECT att.*, u.* FROM 

        (SELECT assg.id_assignment, colab.no_colaborador, rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT asgm.id_assignment, colab.no_colaborador, rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject,  gps.id_group, gps.group_code
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.id_assignment, colab.no_colaborador, asg.no_teacher, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
        INNER JOIN attendance_records.attendance_index AS att ON u.id_assignment = att.id_assignment 
        WHERE no_colaborador = $id_teacher AND att.valid_assistance = 1  AND no_teacher = '$_SESSION[colab]' $parameters_report_type ORDER BY id_attendance_index DESC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getHistoryByID($id_index)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT  iat.incident, atr.attend, atr.id_student, st.student_code, CONCAT(st.lastname, ' ', st.name) AS name_student
        FROM attendance_records.attendance_record AS atr
        INNER JOIN school_control_ykt.students AS st ON atr.id_student = st.id_student
        INNER JOIN attendance_records.incidents_attendance AS iat ON atr.incident_id = iat.incident_id
        WHERE atr.id_attendance_index =  '$id_index';
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getHistoryDetails($id_index)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT att.apply_date, att.class_block, CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name, sbj.name_subject, gps.group_code 
        FROM attendance_records.attendance_index AS att
        INNER JOIN school_control_ykt.assignments AS asg ON att.id_assignment = asg.id_assignment
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = att.teacher_passed_attendance
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        WHERE att.id_attendance_index =  '$id_index';
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllIndexesTeacher($id_teacher)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT att.*, gps.group_code, sbj.name_subject 
        FROM  school_control_ykt.assignments AS asg 
        INNER JOIN attendance_records.attendance_index AS att ON asg.id_assignment = att.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        WHERE asg.no_teacher  = '$id_teacher' AND att.valid_assistance = 1 ORDER BY id_attendance_index DESC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupsByCoordinator()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

        (
        SELECT groups.id_group, groups.group_code, assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT gps.id_group, gps.group_code, asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT groups.id_group, groups.group_code, asg.print_school_report_card, asg.assignment_active, asg.no_teacher, groups.group_type_id
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllGroupsFromMyTeachers($id_academic, $teacher_number, $id_subject)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT  DISTINCT gps.id_group, gps.group_code, asg.id_assignment
        FROM iteach_academic.relationship_managers_assignments as rma
        INNER JOIN school_control_ykt.assignments as asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects as sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$_SESSION[colab]' AND sbj.id_academic_area = '$id_academic' AND asg.no_teacher = '$teacher_number' AND sbj.id_subject = '$id_subject'
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListOfGroups()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 
        (SELECT rel_coord_aca.no_teacher, groups.id_group, groups.group_code, CONCAT(cmp.campus_name, ' | ',acdlvldg.degree, ' | ', 
        CASE WHEN groups.id_section = 0 THEN 'VARONES'
        WHEN groups.id_section = 1 THEN 'MUJERES' ELSE 'MIXTO' END, ' | ', groups.letter ) as desglose
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
    
        SELECT rel_coord_aca.no_teacher, gps.id_group, gps.group_code, CONCAT(cmp.campus_name, ' | ',aclg.degree, ' | ', 
        CASE WHEN gps.id_section = 0 THEN 'VARONES'
        WHEN gps.id_section = 1 THEN 'MUJERES' ELSE 'MIXTO' END, ' | ', gps.letter ) as desglose
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    )
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' ORDER BY desglose ASC
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getRegisteredExcuses()
    {
        $results = array();

        $query = $this->conn->query("
        SELECT aex.*, ext.excuse_description, 
        CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name, 
        CONCAT(student.name,' ',student.lastname) AS student_name,
        student.student_code
        FROM absence_excuse.absences_excuse AS aex
        INNER JOIN absence_excuse.excuse_types AS ext ON aex.id_excuse_types = ext.id_excuse_types
        INNER JOIN school_control_ykt.students AS student ON aex.id_student = student.id_student
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = aex.no_teacher_registered
        WHERE aex.no_teacher_registered =  '$_SESSION[colab]'
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListOfGroupsByType($group_type_id, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("


        SELECT u.*, CONCAT(cam.campus_name, ' | ',aclg.degree, ' | ', 
        CASE WHEN u.id_section = 0 THEN 'VARONES'
        WHEN u.id_section = 1 THEN 'MUJERES' ELSE 'MIXTO' END, ' | ', u.letter ) as desglose FROM 

        (SELECT sbj.id_academic_area, groups.id_group, groups.group_code, acdlvldg.id_level_grade,  groups.group_type_id, groups.id_section, groups.letter, groups.id_campus, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT sbj.id_academic_area, gps.id_group, gps.group_code, aclg.id_level_grade,  gps.id_section, gps.group_type_id, gps.letter, gps.id_campus, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    )
        AS u
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON u.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cam ON cam.id_campus = u.id_campus

    
        WHERE u.no_teacher = '$_SESSION[colab]'  AND u.group_type_id = '$group_type_id' AND id_academic_area = '$id_academic_area'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupType()
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT * FROM 

        (SELECT rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, gtp.group_type, gtp.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.group_types AS gtp ON gtp.group_type_id = groups.group_type_id
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, gtp.group_type, gtp.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.group_types AS gtp ON gtp.group_type_id = gps.group_type_id
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.print_school_report_card = 1 AND u.assignment_active = 1        
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIndexReport($id_academic, $teacher_number, $id_subject, $fecha)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT   COUNT(*) AS cont_asistencias
        FROM iteach_academic.relationship_managers_assignments as rma
        INNER JOIN school_control_ykt.assignments as asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN attendance_records.attendance_index AS att ON att.id_assignment = asg.id_assignment
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects as sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$_SESSION[colab]' AND sbj.id_academic_area = '$id_academic' AND att.apply_date LIKE '$fecha%' AND asg.no_teacher = '$teacher_number' AND sbj.id_subject = '$id_subject'
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAttendanceIndexReportAssignment($id_assignment, $fecha)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT   * FROM attendance_records.attendance_index
       WHERE id_assignment = '$id_assignment' AND apply_date LIKE '$fecha%' AND valid_assistance = '1'
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getInfoAbscence($id_attendance_record)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT iat.incident, sbj.name_subject, gps.group_code, std.student_code, CONCAT(std.name, ' ',std.lastname) AS student_name, att.apply_date, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name 
    FROM attendance_records.attendance_record AS atr
    INNER JOIN  attendance_records.attendance_index AS att ON atr.id_attendance_index = att.id_attendance_index
    INNER JOIN attendance_records.incidents_attendance AS iat ON atr.incident_id = iat.incident_id
    INNER JOIN school_control_ykt.assignments AS asg ON att.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON  colab.no_colaborador = asg.no_teacher
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.students AS std ON atr.id_student =std.id_student
    WHERE atr.id_attendance_record = '$id_attendance_record'
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getListStudentBySubject($id_subject)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT student.id_student, student.id_family, student.group_id, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student, student.id_status_type, sbj.id_subject, gps.group_code, sbj.name_subject
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
            INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
            INNER JOIN school_control_ykt.assignments AS asg ON gps.id_group = asg.id_group
            INNER JOIN iteach_academic.relationship_managers_assignments AS rma ON rma.id_assignment = asg.id_assignment
            INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
            WHERE sbj.id_subject = '$id_subject' AND rma.no_teacher = '$_SESSION[colab]' AND student.status = 1  
            ORDER BY `name_student`  ASC;
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStatusDescription($id_status_type)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT status_type, html_color
            FROM school_control_ykt.status_type
            WHERE id_status_type='$id_status_type'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getListIncidents()
    {
        $results = array();

        $query = $this->conn->query("
        SELECT *
        FROM attendance_records.incidents_attendance AS incidents ORDER BY incident ASC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentJustify($id_student)
    {
        $results = array();
        $today_time = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $today_end_date = date('Y-m-d 23:59:59');

        $query = $this->conn->query("
        SELECT aeb.absence_day, 
        aex.*, 
        aeb.apply_excuse AS break_apply, 
        aeb.active_excuse AS break_active, 
        excuse_description, 
        double_absence,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name 
        FROM absence_excuse.absences_excuse AS aex 
        INNER JOIN absence_excuse.excuse_types AS exc ON aex.id_excuse_types = exc.id_excuse_types 
        INNER JOIN absence_excuse.absences_excuse_breakdown AS aeb ON aex.id_absences_excuse = aeb.id_absences_excuse 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = aex.no_teacher_registered 
        WHERE aex.active_excuse= 1 AND aeb.apply_excuse =1 AND aeb.active_excuse =1 AND exc.double_absence = 0  AND aex.id_student = '$id_student'  AND aex.end_date >='$today_time' AND start_date <='$today_time' AND DATE(aeb.absence_day) = '$today'
            ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentUnJustify($id_student)
    {
        $results = array();
        $today_time = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $today_end_date = date('Y-m-d 23:59:59');

        $query = $this->conn->query("
        SELECT aeb.absence_day, 
        aex.*, 
        aeb.apply_excuse AS break_apply, 
        aeb.active_excuse AS break_active, 
        excuse_description, 
        double_absence,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name 
        FROM absence_excuse.absences_excuse AS aex 
        INNER JOIN absence_excuse.excuse_types AS exc ON aex.id_excuse_types = exc.id_excuse_types 
        INNER JOIN absence_excuse.absences_excuse_breakdown AS aeb ON aex.id_absences_excuse = aeb.id_absences_excuse 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = aex.no_teacher_registered 
        WHERE aex.active_excuse= 1 AND aeb.active_excuse =1 AND (aeb.apply_excuse =0 OR exc.double_absence = 1) AND aex.id_student = '$id_student' AND aex.end_date >='$today_time' AND start_date <='$today_time' AND DATE(aeb.absence_day) = '$today'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function saveAttendance($stmt)
    {
        $result = false;

        try {

            if ($this->conn->query($stmt)) {
                $result = true;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $this->conn->query($stmt);
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function saveAE($query, $values)
    {

        $result = false;

        try {

            $stmt = $this->conn->prepare($query);

            if ($stmt->execute($values)) {
                $result = true;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $this->conn->query($stmt);
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function checkCriteriaPE($id_student, $id_period_calendar, $id_assignment)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT evp.id_evaluation_plan, gec.id_grades_evaluation_criteria, fga.id_final_grade, gp.id_grade_period
            FROM iteach_grades_quantitatives.final_grades_assignment AS fga
            INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON fga.id_final_grade = gec.id_final_grade
            INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON gec.id_evaluation_plan = evp.id_evaluation_plan AND fga.id_assignment = evp.id_assignment
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON evp.id_evaluation_source = ev_source.id_evaluation_source
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON evp.id_period_calendar = gp.id_period_calendar AND fga.id_final_grade = gp.id_final_grade
            WHERE fga.id_student = $id_student AND fga.id_assignment = $id_assignment AND gp.id_period_calendar = $id_period_calendar AND ev_source.id_evaluation_source = 34
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getLastID()
    {
        $result = 0;

        try {

            if ($this->conn->lastInsertId() > 0) {
                $result = $this->conn->lastInsertId();
            }
        } catch (Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function getLastIDAttendanceIndex()
    {
        return $this->conn->lastInsertId();
    }

    public function getInfoGeneralLastAttendanceDate($id_assignment, $date, $class_block)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM attendance_records.attendance_index
            WHERE id_assignment = '$id_assignment' AND apply_date LIKE '$date%' AND class_block = '$class_block'
            ORDER BY id_attendance_index DESC LIMIT 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getRecordsAttendance($id_attendance_index)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT rec.*, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student
            FROM attendance_records.attendance_record AS rec
            INNER JOIN school_control_ykt.students AS student ON rec.id_student = student.id_student
            WHERE id_attendance_index = '$id_attendance_index'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function updateAnotherPasslist($id_assignment, $today_day, $class_block)
    {
        $results = array();

        $query = $this->conn->exec("UPDATE attendance_records.attendance_index 
        SET valid_assistance  = 0
        WHERE id_assignment = $id_assignment AND DATE(apply_date) = '$today_day' AND class_block = $class_block 
            ");
    }
}
