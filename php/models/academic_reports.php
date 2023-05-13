<?php

class AcademicReports extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getGroupsAcademicPerformance($id_section, $id_campus, $id_level_grade)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("SELECT DISTINCT gps.*
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        WHERE rma.no_teacher = '$no_teacher' AND gps.id_section = '$id_section' 
        AND id_level_grade = '$id_level_grade' AND gps.id_campus = '$id_campus'");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicGradeByArea()
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("SELECT DISTINCT degree, id_academic_level, alg.id_level_grade
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
        WHERE rma.no_teacher = '$no_teacher' ORDER BY alg.id_level_grade");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSectionAndCampus($id_level_grade)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("SELECT DISTINCT gps.id_section, cam.id_campus, campus_name,
        CASE
            WHEN id_section = 1 THEN CONCAT(campus_name, ' - ', 'HOMBRES')
            WHEN id_section = 2 THEN CONCAT(campus_name, ' - ', 'MUJERES')
            WHEN id_section = 3 THEN CONCAT(campus_name, ' - ', 'MIXTO')
            END  AS seccion_campus, gps.id_level_grade
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cam ON gps.id_campus = cam.id_campus
        WHERE gps.id_level_grade = '$id_level_grade' AND rma.no_teacher = '$_SESSION[colab]'");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodsByIdLevelCombination($id_level_combination)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar
        WHERE id_level_combination = '$id_level_combination'");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentByGroupCoordinator($id_group)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("SELECT * FROM 

            (SELECT rel_coord_aca.no_teacher, groups.*, assg.id_assignment, print_school_report_card, assignment_active, sbj.name_subject
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

            SELECT rel_coord_aca.no_teacher, gps.*, asgm.id_assignment, print_school_report_card, assignment_active, sbj.name_subject
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

            WHERE u.no_teacher = $no_teacher AND u.id_group = $id_group AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.group_code ASC
        ");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPEASQUAL($id_student, $id_assignment, $id_period_calendar)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("
        SELECT grape.* FROM  iteach_grades_quantitatives.final_grades_assignment AS fga 
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
        WHERE fga.id_assignment='$id_assignment' AND fga.id_student = '$id_student'");



        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllPQUAL($id_student, $id_period_calendar)
    {

        $no_teacher = $_SESSION['colab'];

        $results = array();

        $query = $this->conn->query("
        SELECT grape.* FROM  iteach_grades_quantitatives.final_grades_assignment AS fga 
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
        WHERE grape.id_period_calendar='$id_period_calendar' AND fga.id_student = '$id_student' ORDER BY id_period_calendar");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
