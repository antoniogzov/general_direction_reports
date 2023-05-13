<?php

class Subjects extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getInfoSubjectByIdSubject($id_subject)
    {
        $results = array();

        $get_results = $this->conn->query("SELECT * FROM school_control_ykt.subjects WHERE id_subject = '$id_subject'");
        if ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getInfoSubjectByIdAssignment($id_assignment)
    {
        $results = array();

        $get_results = $this->conn->query("
            SELECT subject.*
            FROM school_control_ykt.subjects AS subject
            INNER JOIN school_control_ykt.assignments AS assignment ON subject.id_subject = assignment.id_subject
            WHERE assignment.id_assignment = '$id_assignment'");
        if ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getSubjectsGroupsFromTeacherByAcademicArea($no_teacher, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, aclg.degree, gps.group_code 
        FROM school_control_ykt.subjects AS sbj 
        INNER JOIN school_control_ykt.assignments AS asgm ON sbj.id_subject = asgm.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        WHERE asgm.no_teacher = '$no_teacher' AND sbj.id_academic_area = '$id_academic_area' AND asgm.print_school_report_card = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getSubjectsGroupsFromManagerByAcademicArea($no_teacher, $id_academic_area) {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM 

            (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, acdlvldg.degree, groups.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
            
            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, asg.no_teacher, sbj.id_academic_area, asg.print_school_report_card, asg.assignment_active
            FROM school_control_ykt.assignments AS asg
            INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

            AS u

            WHERE u.no_teacher = $no_teacher AND u.id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.group_code
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getSubjectsGroupsFromManagerAllAcademicArea($no_teacher) {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM 

            (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, acdlvldg.degree, groups.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
            
            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asg.id_assignment, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, asg.no_teacher, sbj.id_academic_area, asg.print_school_report_card, asg.assignment_active
            FROM school_control_ykt.assignments AS asg
            INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

            AS u

            WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
            ORDER BY u.group_code
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupByIDAssignment($id_assignment)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT gps.group_code 
        FROM school_control_ykt.groups AS gps
        INNER JOIN school_control_ykt.assignments AS asgm ON gps.id_group = asgm.id_group
         WHERE id_assignment ='$id_assignment'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
    public function getHebrewSubjectsGroupsFromTeacherByAcademicArea($no_teacher, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT sbj.name_subject, sbj.id_subject,  asgm.id_assignment, cmp.campus_name, aclg.degree, gps.group_code 
        FROM school_control_ykt.subjects AS sbj 
        INNER JOIN school_control_ykt.`assignments`AS asgm ON sbj.id_subject = asgm.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        WHERE asgm.no_teacher = '$no_teacher' AND sbj.id_academic_area='$id_academic_area'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriods($id_level_combination)
    {
        $results = array();
        //echo 'kjhkjhjkhjkhjkh';
        //print_r($id_level_combination);
        if (!is_array($id_level_combination)) {
            $count_periods=$this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '".$id_level_combination."';")->fetchColumn();
        //echo "SELECT COUNT(*) FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '".$id_level_combination."';";
        if ($count_periods > 0){
            $get_results = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination';");
        
            while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        }
        
        }else{
            $results[] = "";
        }
        

        return $results;
    }
    public function getPeriodsWithEvaluationPlan($id_level_combination, $sj)
    {
        $id_assignment = $sj;
        $results = array();
        $get_results = $this->conn->query("
        SELECT DISTINCT pc.id_period_calendar,pc.no_period 
        FROM iteach_grades_quantitatives.period_calendar AS pc
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON pc.id_period_calendar = ep.id_period_calendar  
        WHERE id_level_combination = '$id_level_combination' AND ep.id_assignment ='$id_assignment'");
        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getPeriodsWithoutEvaluationPlan($id_period, $sj)
    {
        $id_assignment = $sj;
        $results = array();
        $resultado = [];
        $get_results = $this->conn->query("
        SELECT pc.no_period, pc.id_period_calendar
        FROM iteach_grades_quantitatives.period_calendar AS pc
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON evp.id_assignment = '$id_assignment' AND pc.id_period_calendar = evp.id_period_calendar
        WHERE pc.id_level_combination = (SELECT id_level_combination FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = '$id_period') AND evp.id_period_calendar IS NULL");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }

    public function getSubjectsWithoutEvaluationPlan($no_teacher, $id_academic_area)
    {

        $results = array();
        $get_results = $this->conn->query("
        SELECT sbj.name_subject, sbj.id_subject, ep.id_assignment AS ep_assignment, asgm.id_assignment, cmp.campus_name, aclg.degree, gps.group_code 
        FROM school_control_ykt.subjects AS sbj 
        INNER JOIN school_control_ykt.assignments AS asgm ON sbj.id_subject = asgm.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON ep.id_assignment = asgm.id_assignment 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
         WHERE asgm.no_teacher = '$no_teacher' AND sbj.id_academic_area='$id_academic_area' AND ep.id_assignment IS null");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }
    public function getSubjectsWithoutEvaluationPlanCoordinators($no_teacher, $id_academic_area)
    {

        $results = array();
        $array_id_calendar_period = array();

        $array_period_calendar = array();


        $get_results = $this->conn->query("
            SELECT * FROM 

            (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, cmp.campus_name, acdlvldg.degree, groups.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, lvl_com.id_academic_level, lvl_com.id_campus, lvl_com.id_section
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
            
            UNION 

            SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, cmp.campus_name, aclg.degree, gps.group_code, CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, aclg.id_academic_level, cmp.id_campus, gps.id_section
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

            AS u

            WHERE no_teacher = $no_teacher AND id_academic_area = $id_academic_area AND print_school_report_card = 1 AND assignment_active = 1
            ");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $array_period_calendar = array();
            $array_assignments = array();
            //--- --- ---//
            $stmt = $this->conn->query("
            SELECT pc.id_period_calendar
            FROM iteach_grades_quantitatives.period_calendar AS pc
            INNER JOIN school_control_ykt.level_combinations AS lc ON pc.id_level_combination  = lc.id_level_combination
            WHERE lc.id_academic_area = $id_academic_area AND lc.id_academic_level = $row->id_academic_level AND lc.id_campus = $row->id_campus AND lc.id_section = $row->id_section");

            while ($row1 = $stmt->fetch(PDO::FETCH_OBJ)) {
                //--- --- ---//
                array_push($array_period_calendar, $row1->id_period_calendar);
                //--- --- ---//
            }

            //print_r($array_period_calendar);

            $stmt1 = $this->conn->query("
            SELECT ep.id_assignment, ep.id_period_calendar
            FROM iteach_grades_quantitatives.evaluation_plan AS ep
            WHERE ep.id_assignment = $row->id_assignment");

       
            if(!empty($stmt1) > 0){
                $exist = false;
                while ($row2 = $stmt1->fetch(PDO::FETCH_OBJ)) {
                    //echo 'id_period_calendar: ' . $row2->id_period_calendar . 'Existe: ' .in_array($row2->id_period_calendar, $array_period_calendar) . '<br/>';
                    if(in_array($row2->id_period_calendar, $array_period_calendar)){
                        $exist = true;
                    }
                }
                if(!$exist){
                    $results[] = $row;
                }
            } else {
                $results[] = $row;
            }

            //--- --- ---//
        }

        /*$get_results = $this->conn->query("
        SELECT sbj.name_subject, sbj.id_subject, ep.id_assignment AS ep_assignment, asgm.id_assignment, cmp.campus_name, aclg.degree, gps.group_code 
        FROM school_control_ykt.subjects AS sbj 
        INNER JOIN school_control_ykt.assignments AS asgm ON sbj.id_subject = asgm.id_subject 
        INNER JOIN iteach_academic.relationship_managers_assignments AS rma ON rma.id_assignment =  asgm.id_assignment 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        LEFT JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON ep.id_assignment = asgm.id_assignment 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
         WHERE rma.no_teacher = '$no_teacher' AND sbj.id_academic_area='$id_academic_area' AND ep.id_assignment IS null");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }*/


        return $results;
    }
    public function getPercentage($id_assignment, $id_period_c)
    {

        $results = array();
        $get_results = $this->conn->query("SELECT SUM(percentage) AS asignado FROM iteach_grades_quantitatives.evaluation_plan WHERE id_assignment='$id_assignment' AND id_period_calendar = '$id_period_c'");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }

    public function getCountEvalPlanSBJ($id_assignment, $id_period_calendar)
    {

        $results = array();
        $get_results = $this->conn->query("SELECT COUNT(*) AS n_evaluations FROM iteach_grades_quantitatives.evaluation_plan  WHERE `id_assignment` = '$id_assignment' AND id_period_Calendar='$id_period_calendar'");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }
    public function getSubjectsScore($id_pc, $id_assignment)
    {

        $results = array();
        $get_results = $this->conn->query("SELECT COUNT(*) AS n_evaluations FROM iteach_grades_quantitatives.final_grades_assignment as fga INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade WHERE fga.`id_assignment` = '$id_assignment' AND final_grade != '' AND gp.id_period_calendar = '$id_pc'");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }

    public function getButtonImport($sj, $id_period)
    {
        $id_period = $id_period;
        $id_assignment = $sj;
        $results = array();

        $query = $this->conn->query("SELECT COUNT(*) AS config FROM iteach_grades_quantitatives.evaluation_plan WHERE id_period_calendar = '$id_period' AND id_assignment = '$id_assignment'");

        while ($nRows = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $nRows;
        }

        return $results;
    }

    public function getEvaluation()
    {
        $results = array();
        $get_results = $this->conn->query("
        SELECT * FROM iteach_grades_quantitatives.evaluation_source ORDER BY evaluation_name ASC");
        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getEvaluationTypes()
    {
        $results = array();
        $get_results = $this->conn->query("
        SELECT * FROM iteach_grades_quantitatives.evaluation_type");
        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getPlans($periodC, $id_assignment)
    {
        $results = array();


        $query = $this->conn->query("SELECT DISTINCT es.evaluation_name, ep.id_evaluation_source, ep.manual_name, ep.id_evaluation_plan, ep.percentage, asgm.id_assignment, 
        ep.deadline, ep.deadline, gps.group_code, 
        CASE WHEN ep.value_input_type = 0 THEN 'Manual' WHEN ep.value_input_type THEN 'Automático' END AS tipo 
        FROM iteach_grades_quantitatives.`evaluation_plan` AS ep INNER JOIN school_control_ykt.assignments AS asgm ON asgm.id_assignment = ep.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asgm.id_group 
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON ep.id_evaluation_source = es.id_evaluation_source
        WHERE ep.id_assignment = '$id_assignment' AND ep.id_period_calendar= '$periodC'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubcriter($id_evaluation_plan)
    {
        $results = array();

        $query = $this->conn->query("SELECT COUNT(*) AS subcriterios FROM iteach_grades_quantitatives.conf_grade_gathering WHERE id_evaluation_plan = $id_evaluation_plan");

        while ($nRows = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $nRows;
        }

        return $results;
    }
    public function getEvaluationScore($id_evaluation_plan)
    {
        $results = array();

        $query = $this->conn->query("SELECT SUM(ec.grade_evaluation_criteria_teacher) AS teacher_eval
        FROM iteach_grades_quantitatives.evaluation_plan AS ep
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS ec ON ep.`id_evaluation_plan` = ec.`id_evaluation_plan`
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ep.id_assignment = fg.id_assignment AND ec.id_final_grade = fg.id_final_grade
        WHERE ep.id_evaluation_plan = $id_evaluation_plan;");

        while ($nRows = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $nRows;
        }

        return $results;
    }
    public function getSubjectByName($id_assignment)
    {
        $results = '';

        $get_results = $this->conn->query("
        SELECT sbj.name_subject 
        FROM school_control_ykt.subjects AS sbj
        INNER JOIN school_control_ykt.assignments AS asgm ON asgm.id_subject = sbj.id_subject
        WHERE asgm.id_assignment = '$id_assignment'
        ");

        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
}
