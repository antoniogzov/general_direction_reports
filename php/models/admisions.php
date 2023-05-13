<?php

class Admisions extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getDays()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM class_schedule.days WHERE status = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAdmisions($id_academic)
    {
        $results = array();

        $query = $this->conn->query("SELECT stud.status,csy.school_year, stud.birthdate, stud.id_student,  stud.student_code, CONCAT(stud.lastname, ' ', stud.name) as student_name, u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        )
        AS u
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id 
        AND csy.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)

    
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 0 AND group_type_id = 4 AND stud.id_status_type = 3
        ORDER BY student_name
                        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getActiveStudents($id_academic)
    {
        $results = array();

        $query = $this->conn->query("SELECT csy.school_year, stud.birthdate, stud.id_student, csy2.school_year AS next_school_year, 
        stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student AND u.id_group  = insc.id_group 
        LEFT JOIN control_escolar.alumnos_repitientes AS alre ON stud.student_code = alre.codigo_alumno
        LEFT JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg2 ON ((u.id_level_grade)) = aclg2.id_level_grade
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 1 AND alre.codigo_alumno IS NULL
        ORDER BY group_code
                        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllStudents($id_academic_level)
    {
        $results = array();

        $query = $this->conn->query("SELECT csy2.school_year, CASE WHEN stud.status IS NOT NULL THEN 'A' END AS origen, csy.school_year AS incoming_school_year, csy2.school_year AS next_school_year, 
        stud.birthdate, stud.id_student,  stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.degree AS degree_proyection, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade AND lvl_com.id_academic_level = $id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1))
    
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 0 AND group_type_id = 4 AND stud.id_status_type = 3
        ORDER BY student_name
                        ");

        $query2 = $this->conn->query("SELECT csy2.school_year, aclg2.degree AS degree_proyection, CASE WHEN stud.status IS NOT NULL THEN 'N' END AS origen, csy.school_year AS incoming_school_year, csy2.school_year AS next_school_year,
        stud.status, stud.birthdate, stud.id_student,  stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, 
        u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.id_academic_level, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade AND lvl_com.id_academic_level = $id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg_rest ON aclg_rest.id_level_grade = (
            SELECT acdlvldg.id_level_grade
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade AND lvl_com.id_academic_level = $id_academic_level
        WHERE no_teacher = '$_SESSION[colab]'
        ORDER BY id_level_grade DESC LIMIT 1
        )+1
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student AND u.id_group  = insc.id_group 
        LEFT JOIN control_escolar.alumnos_repitientes AS alre ON stud.student_code = alre.codigo_alumno
        LEFT JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg2 ON ((u.id_level_grade)+1) = ((aclg2.id_level_grade)) AND aclg2.id_level_grade != aclg_rest.id_level_grade
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 1 AND alre.codigo_alumno IS NULL
        ORDER BY group_code ");

        $query3 = $this->conn->query("SELECT csy2.school_year, aclg2.degree AS degree_proyection, CASE WHEN stud.status IS NOT NULL THEN 'R' END AS origen, csy.school_year AS incoming_school_year, csy2.school_year AS next_school_year,
        stud.status, stud.birthdate, stud.id_student,  stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, 
        u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade AND lvl_com.id_academic_level = $id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg2 ON (u.id_level_grade) = aclg2.id_level_grade
        LEFT JOIN control_escolar.alumnos_repitientes AS alre ON stud.student_code = alre.codigo_alumno
        LEFT JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 1 AND alre.codigo_alumno IS NOT NULL
        ORDER BY group_code
                ");


        $incoming_students = $this->conn->query("SELECT DISTINCT stud.gender, csy2.school_year, CASE WHEN stud.status IS NOT NULL THEN 'N' END AS origen, csy.school_year AS incoming_school_year, csy2.school_year AS next_school_year, aclg2.degree AS degree_proyection,
        stud.birthdate, stud.id_student,  stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, gps.group_code, u.* FROM 

        (SELECT  rel_coord_aca.no_teacher, acdlvldg.id_academic_level, lvl_com.id_section, CASE WHEN lvl_com.id_section = 1 THEN 0  WHEN lvl_com.id_section = 2 THEN 1 END AS gender_section
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade AND lvl_com.id_academic_level = $id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON aclg.id_level_grade = (SELECT lvg.id_level_grade FROM school_control_ykt.academic_levels_grade AS lvg WHERE id_academic_level = ((u.id_academic_level)-1) ORDER BY id_level_grade DESC LIMIT 1)
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_level_grade = aclg.id_level_grade AND ((gps.id_section = u.id_section) OR gps.id_section = 3)
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = gps.id_group  
        INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = stud.id_student AND gps.id_group  = insc.id_group AND stud.gender = u.gender_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg2 ON ((aclg.id_level_grade)+1) = ((aclg2.id_level_grade))
        LEFT JOIN control_escolar.alumnos_repitientes AS alre ON stud.student_code = alre.codigo_alumno
        LEFT JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)
        WHERE no_teacher = $_SESSION[colab] AND stud.status = 1 AND alre.codigo_alumno IS NULL
                ORDER BY group_code");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        while ($row2 = $query2->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row2;
        }

        while ($row3 = $query3->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row3;
        }

        while ($incoming_students_row = $incoming_students->fetch(PDO::FETCH_OBJ)) {
            $results[] = $incoming_students_row;
        }
        sort($results);
        return $results;
    }

    public function getDayBlockAssignment($id_days, $id_class_block, $id_period_calendar)
    {
        $results = array();
        $no_teacher = $_GET['id_teacher_sbj'];
        $query = $this->conn->query("SELECT DISTINCT sbj.name_subject, gps.group_code, sbj.color_hex
                         FROM class_schedule.relationship_assignments_class_block AS rcab
                         INNER JOIN school_control_ykt.assignments AS asg ON rcab.id_assignment = asg.id_assignment
                         INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
                         INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
                         WHERE rcab.id_days = $id_days AND rcab.id_class_block = $id_class_block AND rcab.no_teacher = $no_teacher AND rcab.id_period_calendar = '$id_period_calendar'
                         ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getDayBlockClassroom($id_days, $id_class_block, $id_period_calendar)
    {
        $results = array();
        $no_teacher = $_GET['id_teacher_sbj'];
        $query = $this->conn->query("SELECT cls.*
                         FROM class_schedule.relationship_assignments_class_block AS rcab
                         INNER JOIN class_schedule.classrooms AS cls ON rcab.id_classrooms = cls.id_classrooms
                         WHERE rcab.id_days = $id_days AND rcab.id_class_block = $id_class_block AND rcab.no_teacher = $no_teacher AND rcab.id_period_calendar = '$id_period_calendar'
                         ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getRepitients($student_code)
    {
        $results = array();
        $query = $this->conn->query("SELECT *
                         FROM control_escolar.alumnos_repitientes
                         WHERE codigo_alumno = '$student_code'
                         ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getRepitientsStudents($id_academic)
    {
        $results = array();

        $query = $this->conn->query("SELECT aclg2.degree AS degree_proyection, csy2.school_year, CASE WHEN stud.status IS NOT NULL THEN 'R' END AS origen, csy.school_year AS incoming_school_year, csy2.school_year AS next_school_year,
        stud.status, stud.birthdate, stud.id_student,  stud.student_code, UPPER(CONCAT(stud.lastname, ' ', stud.name)) as student_name, 
        u.* FROM 

        (SELECT DISTINCT rel_coord_aca.no_teacher, acdlvldg.degree, acdlvldg.id_level_grade, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        )
        AS u
        INNER JOIN school_control_ykt.students AS stud ON stud.group_id = u.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg2 ON (u.id_level_grade) = aclg2.id_level_grade
        LEFT JOIN control_escolar.alumnos_repitientes AS alre ON stud.student_code = alre.codigo_alumno
        LEFT JOIN school_control_ykt.current_school_year AS csy ON csy.id_school_year = stud.incoming_school_year_id
        INNER JOIN school_control_ykt.current_school_year AS csy2 ON csy2.id_school_year = ((SELECT id_school_year FROM school_control_ykt.current_school_year WHERE current_school_year = 1)+1)
        WHERE no_teacher = '$_SESSION[colab]' AND stud.status = 1 AND alre.codigo_alumno IS NOT NULL
        ORDER BY group_code
                        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
