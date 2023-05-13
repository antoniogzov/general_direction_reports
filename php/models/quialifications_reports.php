<?php
include_once 'groups.php';
include_once 'attendance.php';

class QualificationsReports extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getGroupsBySubject($no_teacher, $id_subject, $id_level_grade)
    {

        $results = array();
        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT sbj.id_subject, groups.group_code, groups.id_group, rel_coord_aca.no_teacher, assg.id_assignment
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = '$id_subject'
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            UNION
            SELECT  sbj.id_subject, gps.group_code, gps.id_group, rel_coord_aca.no_teacher, asgm.id_assignment
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id AND asgm.id_subject = '$id_subject'
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupsExtraordinaryReports($no_teacher, $id_level_grade, $id_period)
    {

        $results = array();
        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT  groups.group_code, groups.id_group, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group 
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            UNION
            SELECT   gps.group_code, gps.id_group, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id 
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON asgm.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher
            ORDER BY group_code ASC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentsExtraordinaryReports($id_period, $id_group)
    {
        $no_teacher = $_SESSION['colab'];

        $results = array();
        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT rel_coord_aca.no_teacher, UPPER(CONCAT(std.lastname, ' ', std.name) )AS student_name, std.student_code, std.id_student, inc.id_inscription, std.status
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section AND groups.id_group = '$id_group'
            INNER JOIN school_control_ykt.assignments AS assg ON assg.id_group = '$id_group'
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            INNER JOIN school_control_ykt.inscriptions AS inc ON groups.id_group = inc.id_group
            INNER JOIN school_control_ykt.students AS std ON std.id_student = fga.id_student AND std.status = 1 AND std.id_student = inc.id_student
            UNION
            SELECT rel_coord_aca.no_teacher, CONCAT(std.lastname, ' ', std.name) AS student_name, std.student_code, std.id_student, inc.id_inscription, std.status
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id 
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON asgm.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = '$id_group'
            INNER JOIN school_control_ykt.inscriptions AS inc ON  gps.id_group = inc.id_group
            INNER JOIN school_control_ykt.students AS std ON  inc.id_student = std.id_student AND std.id_student = fga.id_student AND std.status = 1
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher 
            ORDER BY student_name ASC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentsExtraordinaryQualifications($id_period, $id_group, $id_student, $id_subject)
    {
        $no_teacher = $_SESSION['colab'];

        $results = array();
        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT  grade_extraordinary_examen, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus
            INNER JOIN school_control_ykt.assignments AS assg ON assg.id_group = '$id_group' AND assg.id_subject = '$id_subject'
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN school_control_ykt.students AS std ON std.id_student = '$id_student' AND fga.id_student = '$id_student'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            UNION
            SELECT  grade_extraordinary_examen, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id 
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON asgm.id_assignment = fga.id_assignment  AND asgm.id_subject = '$id_subject'
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN school_control_ykt.students AS std ON std.id_student = '$id_student' AND fga.id_student = '$id_student'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = '$id_group'
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsExtraordinaryReports($id_period, $id_group)
    {
        $no_teacher = $_SESSION['colab'];

        $results = array();
        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT  rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject, sbj.short_name
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON assg.id_group = '$id_group'
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            UNION
            SELECT  rel_coord_aca.no_teacher, sbj.name_subject, sbj.id_subject, sbj.short_name
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id 
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON asgm.id_assignment = fga.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade AND grape.id_period_calendar = '$id_period'
            INNER JOIN iteach_grades_quantitatives.extraordinary_exams AS extra_exam ON extra_exam.id_final_grade = fga.id_final_grade AND extra_exam.id_grade_period = grape.id_grade_period
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = '$id_group'
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher 
            ORDER BY name_subject ASC
            ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
    public function getPeriodByID($id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = '$id_period_calendar'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getPeriodByGroup($id_group, $id_academic_area, $id_academic_level, $no_period)
    {
        $results = array();

        $query = $this->conn->query("SELECT pc.id_period_calendar FROM school_control_ykt.groups AS gps
        INNER JOIN school_control_ykt.level_combinations AS lvc ON gps.id_section = lvc.id_section AND gps.id_campus = lvc.id_campus
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON lvc.id_level_combination = pc.id_level_combination
        WHERE gps.id_group = '$id_group' AND lvc.id_academic_area = '$id_academic_area' AND lvc.id_academic_level = '$id_academic_level' AND pc.no_period = '$no_period'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getNoPeriodByID($id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = '$id_period_calendar'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
