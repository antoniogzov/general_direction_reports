<?php

class Helpers extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function StudentDataByIdFinalGrade($id_final_grade)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, CONCAT(student.name,' ',student.lastname) AS student_name
            FROM iteach_grades_quantitatives.final_grades_assignment AS fg
            INNER JOIN school_control_ykt.inscriptions AS inscription ON fg.id_inscription = inscription.id_inscription
            INNER JOIN school_control_ykt.students AS student ON inscription.id_student = student.id_student
            WHERE fg.id_final_grade = '$id_final_grade'
            ");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
    
    public function getInfoSubjectAndGroupByIdAssignment($id_assignment){
        $results = array();

        $get_results = $this->conn->query("
        SELECT subject.*, gps.*,CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS name 
        
        FROM school_control_ykt.subjects AS subject
        INNER JOIN school_control_ykt.assignments AS assignment ON subject.id_subject = assignment.id_subject
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON assignment.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.groups AS gps ON assignment.id_group = gps.id_group
        WHERE assignment.id_assignment = '$id_assignment'");
        if ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
    
    public function getAllPeriodsByLevelCombination($id_level_combination)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'");

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
    public function getAllAssignmentByGroup($group_id)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT asg.id_assignment, gps.group_code, sbj.name_subject, asg.no_teacher, rma.no_teacher AS coordinador 
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE rma.no_teacher = '$_SESSION[colab]' AND gps.id_group = '$group_id'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getIdLevelCombinationByAssignment($id_assignment)
    {
        $results = array();

        $query = $this->conn->query("SELECT lc.id_level_combination
        FROM school_control_ykt.level_combinations AS lc
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
        INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
        INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
        WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment' LIMIT 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAllAssignmentByGroupSection($group_id, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, asgms.no_teacher FROM 

        (SELECT groups.id_group, groups.group_code, assg.id_assignment, sbj.name_subject, rel_coord_aca.no_teacher AS coordinator, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT gps.id_group, gps.group_code, asgm.id_assignment, sbj.name_subject, rel_coord_aca.no_teacher AS coordinator, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT groups.id_group, groups.group_code, asg.id_assignment, sbj.name_subject, asg.no_teacher AS coordinator, sbj.id_academic_area
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
        INNER JOIN school_control_ykt.assignments AS asgms ON u.id_assignment = asgms.id_assignment

        WHERE u.coordinator = '$_SESSION[colab]' AND u.id_group = '$group_id' AND u.id_academic_area = '$id_academic_area'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentByGroup($group_id)
    {
        $results = array();
       
        $query = $this->conn->query(" SELECT * FROM 

        (SELECT  assg.id_assignment, sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code, assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT asgm.id_assignment,  sbj.name_subject, sbj.id_subject, gps.id_group, gps.group_code, asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT  asg.id_assignment, sbj.name_subject, sbj.id_subject, groups.id_group, groups.group_code, asg.print_school_report_card, asg.assignment_active, asg.no_teacher, groups.group_type_id
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4 AND u.id_group = '$group_id'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentByGroupAc($group_id, $id_academic_area)
    {
        $results = array();

        /*$query = $this->conn->query("SELECT asg.id_assignment, gps.group_code, sbj.name_subject, asg.no_teacher, rma.no_teacher AS coordinador 
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE rma.no_teacher = '$_SESSION[colab]' AND gps.id_group = '$group_id' AND  sbj.id_academic_area = '$id_academic_area'");*/

        $query = $this->conn->query("
            SELECT * FROM 

            (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, cmp.campus_name, acdlvldg.degree, groups.id_group, groups.group_code, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            
            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, cmp.campus_name, aclg.degree, gps.id_group, gps.group_code, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            
            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, cmp.campus_name, aclg.degree,
             gps.id_group, gps.group_code, asgm.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
            FROM school_control_ykt.assignments AS asgm
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            )

            AS u

            WHERE u.no_teacher = $_SESSION[colab] AND id_group = $group_id AND u.id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getPeriodByLevelCombinationAndDate($id_level_combination, $date) {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = $id_level_combination AND '$date' >= DATE(start_date) AND '$date' <= DATE(end_date)");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupInfo($id_group) {
        $results = array();

        $query = $this->conn->query("SELECT * FROM school_control_ykt.groups WHERE id_group = '$id_group'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getIdsLevelCombination($id_assignment) {
        $results = array();

        $query = $this->conn->query("
            SELECT lc.id_level_combination
            FROM school_control_ykt.level_combinations AS lc
            INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
            INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
            INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
            WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment'
            ");
        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        } else {

            $results = "";
        }

        return $results;
    }

    public function getGroupsFromTeacher($stmt)
    {
        $results = array();

        try {

            $query = $this->conn->query($stmt);
        } catch (Exception $e) {
            echo 'Exception -> ' . $query;
            var_dump($e->getMessage());
        }

        return $results;
    }

    public function GetGrants($no_teacher) {
        $get_grants = $this->conn->query("SELECT t1.grants, t2.nombres_colaborador, t2.apellido_paterno_colaborador 
        FROM permisos_intranet_ykt.permisos_modulos AS t1 
        INNER JOIN colaboradores_ykt.colaboradores AS t2 ON t2.no_colaborador = t1.no_colaborador 
        WHERE t1.no_colaborador = '$_SESSION[colab]' AND t1.id_modulo = '$module'");
        $results    = false;
        if ($grants_row = $get_grants->fetch(PDO::FETCH_OBJ)) {
            $results = $grants_row;
        }

        return $results;
        // see = 1, add = 2, update = 4, delete = 8, su = 16
    }

    public function GetInfoFamilyByStudent($id_student) {

        $sql = "SELECT t1.family_code, t1.family_name, t2.mail AS father_mail, t3.mail AS mother_mail, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student
        FROM families_ykt.families AS t1
        LEFT JOIN families_ykt.fathers AS t2 ON t2.id_family = t1.id_family
        LEFT JOIN families_ykt.mothers AS t3 ON t3.id_family = t1.id_family
        LEFT JOIN school_control_ykt.students AS student ON t1.id_family = student.id_family
        WHERE student.id_student = $id_student";

        $get_families = $this->conn->query($sql);
        $results      = null;

        if ($family_row = $get_families->fetch(PDO::FETCH_OBJ)) {
            $results = $family_row;
        }
        return $results;
    }
}
