<?php

class ArchiveInfo extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getStudentInfo($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT student.id_student,  student.id_family, student.group_id, student.student_code, student.birthdate,  cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
        AS name_student, student.id_status_type, 

        CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

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
        FROM school_control_ykt.students AS student
        INNER JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
        INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
        INNER JOIN iteach_academic.relationship_managers_assignments AS rma ON rma.id_assignment = asg.id_assignment

        WHERE student.id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetGroupsStudentSubject($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT gps.id_group, gps.group_code, gt.group_type , name_academic_area, aa.id_academic_area
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.group_types AS gt ON gps.group_type_id = gt.group_type_id
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_areas AS aa ON sbj.id_academic_area = aa.id_academic_area
        WHERE std.id_student = '$id_student' ORDER BY gt.group_type_id ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetGroupsStudent($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT gps.id_group, gps.group_code, gt.group_type
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.group_types AS gt ON gps.group_type_id = gt.group_type_id
        WHERE std.id_student = '$id_student' ORDER BY gt.group_type_id ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function GetSubjectsStudent($id_student, $id_academic_area, $id_group)
    {
        $results = array();

        $query = $this->conn->query("SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, gps.id_group, gps.group_code, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.groups AS gps 
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student AND ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        WHERE std.id_student =  '$id_student' AND sbj.id_academic_area= '$id_academic_area' AND gps.id_group = '$id_group' and sbj.id_subject != '416' and sbj.id_subject != '417' and sbj.id_subject != '418'
        ORDER BY sbj.name_subject
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetHebAllSubjectsStudent($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT gps.group_code, asg.id_assignment, sbj.name_subject
        FROM school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS gps ON insc.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE insc.id_student = '$id_student' AND sbj.id_academic_area ='2' AND sbj.id_subject != '416' and sbj.id_subject != '417' and sbj.id_subject != '418'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetHebAllSubjectsStudentReport($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT gps.group_code, asg.id_assignment,
        sbj.name_subject
        FROM attendance_records.attendance_index AS ati
        INNER JOIN attendance_records.attendance_record AS atr 
            ON atr.id_attendance_index = ati.id_attendance_index AND atr.id_student = $id_student
            INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE atr.id_student = '$id_student' AND sbj.id_academic_area ='2' AND sbj.id_subject != '416' and sbj.id_subject != '417' and sbj.id_subject != '418'");

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
            WHERE DATE(apply_date) >= '$fechaInicio' AND DATE(apply_date) <= '$fechaMaxima' AND t1.obligatory = 1 AND t1.id_assignment = '$id_assignment' AND id_attendance_index = 
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
    public function getAttendanceIndexStudentArchive($id_assignment,  $fechaInicio, $fechaMaxima, $id_student)
    {
        $results = array();
        $query = $this->conn->query(" SELECT *, DATE(ati.apply_date) AS pass_date, 
                CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
                iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident
                                            FROM school_control_ykt.students AS stud 
                                            INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                            INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                            INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                            WHERE 
                                            stud.id_student =$id_student
                                            AND ati.id_assignment =$id_assignment 
                                            AND ati.apply_date >= '$fechaInicio' 
                                            AND ati.apply_date <= '$fechaMaxima'
                                            AND ati.valid_assistance = 1
                                            AND ati.obligatory = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAbsencesArchive($id_assignment,  $fechaInicio, $fechaMaxima, $id_student)
    {
        $results = array();
        $query = $this->conn->query("SELECT *, DATE(ati.apply_date) AS pass_date,
                CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
                iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident
                                            FROM school_control_ykt.students AS stud
                                            INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                            INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                            INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                            WHERE
                                            stud.id_student =$id_student
                                            AND ati.id_assignment =$id_assignment
                                            AND ati.apply_date >= '$fechaInicio'
                                            AND ati.apply_date <= '$fechaMaxima'
                                            AND  ((atr.attend = 0) AND (atr.apply_justification = 0 OR iat.apply_justification = 0))
                                            AND ati.valid_assistance = 1
                                            AND ati.obligatory = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAttendsArchive($id_assignment,  $fechaInicio, $fechaMaxima, $id_student)
    {
        $results = array();
        $query = $this->conn->query(" SELECT *, DATE(ati.apply_date) AS pass_date, 
                CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name,
                iat.apply_justification AS iat_apply_justification, iat.double_absence, atr.apply_justification AS atr_apply_justification, iat.incident
                                            FROM school_control_ykt.students AS stud 
                                            INNER JOIN attendance_records.attendance_record AS atr ON atr.id_student = stud.id_student
                                            INNER JOIN attendance_records.attendance_index AS ati ON ati.id_attendance_index = atr.id_attendance_index
                                            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ati.teacher_passed_attendance
                                            INNER JOIN attendance_records.incidents_attendance AS iat ON iat.incident_id = atr.incident_id
                                            WHERE 
                                            stud.id_student =$id_student
                                            AND ati.id_assignment =$id_assignment 
                                            AND ati.apply_date >= '$fechaInicio' 
                                            AND ati.apply_date <= '$fechaMaxima'
                                            AND  ((atr.attend = 1) OR (atr.apply_justification = 1 OR iat.apply_justification = 1))
                                            AND ati.valid_assistance = 1
                                            AND ati.obligatory = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAttendanceArchive($id_att_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM 
            attendance_records.attendance_record 
            WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student' AND attend='1';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAttendanceDetail2($id_att_index, $id_student)
    {
        $results = array();
        $query = $this->conn->query("
            SELECT * 
            FROM attendance_records.attendance_record 
            WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student';
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
            SELECT * FROM attendance_records.attendance_record WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student' AND attend='1';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetSpanAllSubjectsStudent($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT gps.group_code, asg.id_assignment, sbj.name_subject
        FROM school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS gps ON insc.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE insc.id_student = '$id_student' AND sbj.id_academic_area ='1' AND sbj.id_subject != '416' and sbj.id_subject != '417' and sbj.id_subject != '418'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetSpanAllSubjectsStudentReport($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT gps.group_code, asg.id_assignment,
        sbj.name_subject
        FROM attendance_records.attendance_index AS ati
        INNER JOIN attendance_records.attendance_record AS atr 
            ON atr.id_attendance_index = ati.id_attendance_index AND atr.id_student = $id_student
            INNER JOIN school_control_ykt.assignments AS asg ON ati.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
            INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_group = gps.id_group AND insc.id_student = $id_student
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE atr.id_student = '$id_student' AND sbj.id_academic_area ='1' 
            AND sbj.id_subject != '416' and sbj.id_subject != '417' and sbj.id_subject != '418'
        ORDER BY group_code ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetSubjectsStudentForQualification($id_student, $id_academic_area, $id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, gps.id_group, gps.group_code, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.groups AS gps 
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student AND ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject 
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = asg.no_teacher
        WHERE std.id_student =  '$id_student' AND sbj.id_academic_area= '$id_academic_area' AND gps.id_group = '$id_group'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetSubjectsStudentGps($id_student, $id_group)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT sbj.name_subject, sbj.id_subject, asg.id_assignment 
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject 
        WHERE std.id_student =  '$id_student' AND ins.id_group = '$id_group'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetAssignmentStudent($id_student, $id_academic_area)

    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT asg.id_assignment 
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject 
        WHERE std.id_student =  '$id_student' AND sbj.id_academic_area= '$id_academic_area'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetAssignmentIndexes($id_student, $id_assignment, $fecha_min, $fecha_max)
    {
        $results = array();
        $f1 = explode(" ", $fecha_min);
        $f2 = explode(" ", $fecha_max);
        $query = $this->conn->query("
        SELECT att.id_attendance_index, att.apply_date, att.id_assignment, attr.id_student, attr.attend 
        FROM attendance_records.`attendance_index` AS att
        INNER JOIN attendance_records.attendance_record AS attr ON att.id_attendance_index = attr.id_attendance_index
        WHERE id_student='$id_student'  AND id_assignment='$id_assignment' AND obligatory='1' AND `apply_date` BETWEEN '$f1[0]' AND '$f2[0]' ORDER BY id_attendance_index ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function GetAllAssignmentStudent($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT DISTINCT asg.id_assignment 
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        WHERE std.id_student =  '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getLevelCombinationByGroupIDHeb($id_group)
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
        WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = 2
        AND assignment.id_group = '$id_group' LIMIT 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAVGAssignmentPeriod($id_assignment, $id_period_calendar, $id_student, $id_group)
    {
        $results = array();

        $query = $this->conn->query(" SELECT 
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
            ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAVGPeriod($id_assignment, $id_period_calendar, $id_student, $id_group)
    {
        $results = array();

        $query = $this->conn->query(" SELECT 
       AVG( CASE 
       WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NULL  THEN '-'
       WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
       WHEN grape.grade_period IS NOT NULL AND extra.grade_extraordinary_examen IS NULL  THEN grape.grade_period
       WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NOT NULL  THEN extra.grade_extraordinary_examen
        END)
        AS 'period_avg'
        FROM  school_control_ykt.inscriptions AS insc
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = insc.id_group
        INNER JOIN school_control_ykt.assignments AS assgn ON assgn.id_group = groups.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = assgn.id_subject
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period_calendar
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assgn.id_assignment = fga.id_assignment AND fga.id_student = insc.id_student
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND percal.id_period_calendar = grape.id_period_calendar
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON extra.id_grade_period = grape.id_grade_period
        WHERE fga.id_assignment = assgn.id_assignment
        AND insc.id_student = $id_student 
        AND groups.id_group = $id_group
        AND ((grape.grade_period IS  NOT NULL) OR (extra.grade_extraordinary_examen IS NOT NULL))
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
        WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = 1
        AND assignment.id_group = '$id_group' LIMIT 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentQualificationPeriod($id_assignment, $id_student, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT grape.id_grade_period, grade_period_calc,
        CASE 
       WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NULL  THEN '-'
       WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
       WHEN grape.grade_period IS NOT NULL AND extra.grade_extraordinary_examen IS NULL  THEN grape.grade_period
       WHEN grape.grade_period IS NULL AND extra.grade_extraordinary_examen IS NOT NULL  THEN extra.grade_extraordinary_examen
        END
        AS 'grade_period'
        FROM iteach_grades_quantitatives.grades_period AS grape
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON grape.id_final_grade = fga.id_final_grade
        LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON extra.id_grade_period = grape.id_grade_period
        WHERE fga.id_assignment = '$id_assignment' AND fga.id_student = '$id_student' AND grape.id_period_calendar = '$id_period_calendar'
            ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
