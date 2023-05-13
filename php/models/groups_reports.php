<?php

class GroupsReports extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getStudentAverage($id_student, $period)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT SUM(gp.grade_period) AS average
            FROM iteach_grades_quantitatives.`final_grades_assignment`  AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade
            INNER JOIN school_control_ykt.students AS std ON std.student_code = fga.student_code
            WHERE std.id_student = '$id_student' AND gp.id_period_calendar = $period");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function checkIfExistCommentary($id_grade_period)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.grade_period_commentary WHERE id_grade_period = '$id_grade_period' AND active = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentGradeByAssignment($id_student, $id_period_calendar, $id_assignment)
    {
        $results = array();
        $query = $this->conn->query("
            SELECT gp.id_grade_period, gp.grade_period AS average, gp.grade_period_calc
            FROM iteach_grades_quantitatives.final_grades_assignment AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade
            INNER JOIN school_control_ykt.assignments AS asg ON fga.id_assignment = asg.id_assignment
            WHERE fga.id_student = '$id_student' AND gp.id_period_calendar = $id_period_calendar AND fga.id_assignment = '$id_assignment' AND asg.assignment_active = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getStudentAverageReport($id_student, $period)
    {
        $results = array();
        $count = 0;
        $countD = 0;
        $average = 0;
        $averageDinamyc = 0;
        $query = $this->conn->query("
            SELECT gp.grade_period_calc,
            CASE
            WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
            ELSE gp.grade_period
            END AS average
            FROM iteach_grades_quantitatives.final_grades_assignment AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade
            INNER JOIN school_control_ykt.students AS std ON std.student_code = fga.student_code
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON fga.id_final_grade = extra.id_final_grade AND gp.id_grade_period = extra.id_grade_period
            WHERE std.id_student = '$id_student' AND gp.id_period_calendar = $period");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            if ($row->average != null && $row->average != '') {
                $count++;
                $average += floatval($row->average);
            }

            if ($row->grade_period_calc != null && $row->grade_period_calc != '') {
                $countD++;
                $averageDinamyc += floatval($row->grade_period_calc);
            }
        }

        if ($count > 0) {
            $average = $average / $count;
        }

        if ($countD > 0) {
            $averageDinamyc = $averageDinamyc / $countD;
        }

        $results['average'] = $average;
        $results['grade_period_calc'] = $averageDinamyc;

        return $results;
    }

    public function getStudentAverageReportDinamyc($id_student, $period)
    {
        $results = array();
        $count = 0;
        $countD = 0;
        $average = 0;
        $averageDinamyc = 0;
        $query = $this->conn->query("
            SELECT gp.grade_period_calc AS average, gp.grade_period_calc
            FROM iteach_grades_quantitatives.`final_grades_assignment` AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade
            INNER JOIN school_control_ykt.students AS std ON std.student_code = fga.student_code
            WHERE std.id_student = '$id_student' AND gp.id_period_calendar = $period");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            if ($row->average != null && $row->average != '') {
                $count++;
                $average += floatval($row->average);
            }

            if ($row->grade_period_calc != null && $row->grade_period_calc != '') {
                $countD++;
                $averageDinamyc += floatval($row->grade_period_calc);
            }
        }

        if ($count > 0) {
            $average = $average / $count;
        }

        if ($countD > 0) {
            $averageDinamyc = $averageDinamyc / $countD;
        }

        $results['average'] = $average;
        $results['grade_period_calc'] = $averageDinamyc;

        return $results;
    }

    public function getStudentQualificationListDinamyc($id_student, $id_group, $id_assignment, $id_academic_area, $id_period)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT
            stud.lastname,
            stud.name,
            sbj.*,
            grape.id_final_grade,
            grape.id_grade_period,
            grape.grade_period,
            colb.nombre_hebreo AS hebrew_name_teacher,
            grape.grade_period_calc,
            CONCAT(
                colb.apellido_paterno_colaborador,
                ' ',
                colb.nombres_colaborador
                ) AS spanish_name_teacher,
            CASE
            WHEN extra.grade_extraordinary_examen IS NOT NULL THEN 1
            ELSE 0
            END AS grade_extraordinary,
            CASE
            WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
            ELSE grape.grade_period_calc
            END AS 'average_show'
            FROM
            school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = $id_student
            INNER JOIN school_control_ykt.students AS stud ON stud.id_student = insc.id_student
            INNER JOIN school_control_ykt.groups AS groups
            ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment AND insc.id_student = asscassglmp.id_student
            LEFT JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = asscassglmp.id_final_grade
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON asscassglmp.id_final_grade = extra.id_final_grade AND grape.id_grade_period = extra.id_grade_period
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = $id_group AND sbj.id_academic_area = $id_academic_area AND  insc.id_student = $id_student
            AND grape.id_period_calendar = $id_period AND assgn.id_assignment = $id_assignment
            ORDER BY short_name");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getStudentQualificationList($id_student, $id_group, $id_assignment, $id_academic_area, $id_period)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT
            stud.lastname,
            stud.name,
            sbj.*,
            grape.id_final_grade,
            grape.id_grade_period,
            grape.grade_period,
            grape.grade_period_calc,
            extra.id_extraordinary_exams,
            extra.grade_extraordinary_examen,
            colb.nombre_hebreo AS hebrew_name_teacher,
            CONCAT(
                colb.apellido_paterno_colaborador,
                ' ',
                colb.nombres_colaborador
                ) AS spanish_name_teacher,
            CASE
            WHEN extra.grade_extraordinary_examen IS NOT NULL THEN 1
            ELSE 0
            END AS grade_extraordinary,
            CASE
            WHEN extra.grade_extraordinary_examen IS NOT NULL THEN extra.grade_extraordinary_examen
            ELSE grape.grade_period
            END AS 'average_show'
            FROM
            school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = $id_student
            INNER JOIN school_control_ykt.students AS stud ON stud.id_student = insc.id_student
            INNER JOIN school_control_ykt.groups AS groups
            ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment AND insc.id_student = asscassglmp.id_student
            LEFT JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = asscassglmp.id_final_grade
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON asscassglmp.id_final_grade = extra.id_final_grade AND grape.id_grade_period = extra.id_grade_period
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = $id_group AND sbj.id_academic_area = $id_academic_area AND  insc.id_student = $id_student
            AND grape.id_period_calendar = $id_period AND assgn.id_assignment = $id_assignment
            ORDER BY short_name");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllStudentQualificationList($id_student, $id_group, $id_subject, $id_academic_area, $id_period)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT
            stud.lastname,
            stud.name,
            sbj.*,
            grape.id_final_grade,
            grape.grade_period AS 'calificacion',
            colb.nombre_hebreo AS hebrew_name_teacher,
            CONCAT(
                colb.apellido_paterno_colaborador,
                ' ',
                colb.nombres_colaborador
                ) AS spanish_name_teacher
            FROM
            school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.inscriptions AS insc ON insc.id_student = $id_student
            INNER JOIN school_control_ykt.students AS stud ON stud.id_student = insc.id_student
            INNER JOIN school_control_ykt.groups AS groups
            ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS asscassglmp ON assgn.id_assignment = asscassglmp.id_assignment AND insc.id_student = asscassglmp.id_student
            LEFT JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = asscassglmp.id_final_grade
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = $id_group AND sbj.id_academic_area = $id_academic_area AND  insc.id_student = $id_student
            AND grape.id_period_calendar = $id_period AND sbj.id_subject != 417 OR sbj.id_subject != 416
            ORDER BY short_name");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsByIDGroup($id_group, $id_academic)
    {
        $results = array();
        $order_by = "";

        $info_group = $this->getInfoGroupByIDGroup($id_group);

        if ($info_group->id_level_grade == 12) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 305, 35, 10, 28, 18, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 13) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 306, 35, 11, 28, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 14) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 307, 35, 12, 28, 359, 6, 15, 14)";
        }



        $query = $this->conn->query("
            SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic'
            AND sbj.id_subject != 416
            AND sbj.id_subject != 417
            AND sbj.id_subject != 418
            AND sbj.id_subject != 309
            AND print_school_report_card = 1 $order_by
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsByIDGroupAndPeriod($id_group, $id_academic, $id_period)
    {
        $results = array();
        $order_by = "";

        $info_group = $this->getInfoGroupByIDGroup($id_group);

        if ($info_group->id_level_grade == 12) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 305, 35, 10, 28, 18, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 13) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 306, 35, 11, 28, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 14) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 307, 35, 12, 28, 359, 6, 15, 14)";
        }



        $query = $this->conn->query("SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher, 
         CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
            WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic'
            AND sbj.id_subject != 416
            AND sbj.id_subject != 417
            AND sbj.id_subject != 418
            AND sbj.id_subject != 309
            
            AND (assgn.show_list_teacher = 0 OR show_list_teacher = percal.no_period)
            AND print_school_report_card = 1 $order_by
            
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsByIDGroupAndPeriodByTeacher($id_group, $id_academic, $id_period)
    {
        $results = array();
        $order_by = "";

        $info_group = $this->getInfoGroupByIDGroup($id_group);

        if ($info_group->id_level_grade == 12) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 305, 35, 10, 28, 18, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 13) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 306, 35, 11, 28, 359, 6, 15, 14)";
        }
        if ($info_group->id_level_grade == 14) {
            $order_by = "ORDER BY FIELD(sbj.id_subject, 17, 307, 35, 12, 28, 359, 6, 15, 14)";
        }



        $query = $this->conn->query("SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher, 
         CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
            WHERE assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic'
            AND sbj.id_subject != 416
            AND sbj.id_subject != 417
            AND sbj.id_subject != 418
            AND sbj.id_subject != 309
            AND assgn.no_teacher = $_SESSION[colab]
            AND (assgn.show_list_teacher = 0 OR show_list_teacher = percal.no_period)
            AND print_school_report_card = 1 $order_by
            
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentCritera($id_period, $id_assignment)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT evalplan.id_evaluation_plan, evalplan.percentage, evalplan.gathering, evalplan.value_input_type, evalplan.id_evaluation_source,
        CASE 
            WHEN evalplan.id_evaluation_source = 1 THEN evalplan.manual_name
            WHEN evalplan.id_evaluation_source != 1  THEN evalsource.evaluation_name
        END
        AS criteria_name
            FROM iteach_grades_quantitatives.evaluation_plan AS evalplan
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS evalsource ON evalplan.id_evaluation_source = evalsource.id_evaluation_source
            WHERE evalplan.id_period_calendar = $id_period AND evalplan.id_assignment = $id_assignment
            
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentSubCritera($id_period, $id_assignment, $id_evaluation_plan)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT DISTINCT evalplan.id_evaluation_plan, gath.id_conf_grade_gathering, gath.name_item
            FROM iteach_grades_quantitatives.evaluation_plan AS evalplan
            INNER JOIN iteach_grades_quantitatives.conf_grade_gathering AS gath ON gath.id_evaluation_plan = evalplan.id_evaluation_plan
            INNER JOIN iteach_grades_quantitatives.grade_gathering AS gr_gath ON gr_gath.id_evaluation_plan = evalplan.id_evaluation_plan 
                AND gr_gath.id_conf_grade_gathering = gath.id_conf_grade_gathering
            WHERE evalplan.id_period_calendar = $id_period AND evalplan.id_assignment = $id_assignment 
                AND evalplan.id_evaluation_plan = $id_evaluation_plan AND gr_gath.grade_item IS NOT NULL
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentSubCriteraAE($id_period, $id_assignment, $id_evaluation_plan)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT DISTINCT elc.short_description, elc.id_expected_learning_catalog
            FROM expected_learning.relationship_expected_learning_assignments AS ela
            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = ela.id_expected_learning_index
            INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
            INNER JOIN expected_learning.expected_learning_deliverables AS eld ON eld.id_expected_learning_catalog = elc.id_expected_learning_catalog
            WHERE eld.id_period_calendar = $id_period AND ela.id_assignment = $id_assignment 
                AND eld.teacher_evidence_quailification IS NOT NULL
                ORDER BY elc.no_position ASC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentSubCriteraAEGrade($id_period, $id_expected_learning_catalog, $id_student)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT eld.teacher_evidence_quailification
            FROM expected_learning.expected_learning_deliverables AS eld
            WHERE eld.id_period_calendar = $id_period AND eld.id_expected_learning_catalog = $id_expected_learning_catalog 
            AND eld.id_student = $id_student
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodAVG($id_period, $id_assignment, $id_student)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT grape.id_grade_period, grape.grade_period, extra.grade_extraordinary_examen
            FROM iteach_grades_quantitatives.grades_period AS grape
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = grape.id_final_grade
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS extra ON fga.id_final_grade = extra.id_final_grade AND grape.id_grade_period = extra.id_grade_period
            WHERE grape.id_period_calendar = $id_period AND fga.id_assignment = $id_assignment AND fga.id_student = $id_student
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodAVGDyn($id_period, $id_assignment, $id_student)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT grape.id_grade_period, grape.grade_period_calc
            FROM iteach_grades_quantitatives.grades_period AS grape
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = grape.id_final_grade
            WHERE grape.id_period_calendar = $id_period AND fga.id_assignment = $id_assignment AND fga.id_student = $id_student
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCriteriaGrade($id_period, $id_assignment, $id_student, $id_evaluation_plan)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT gec.id_grades_evaluation_criteria, gec.grade_evaluation_criteria_teacher, grade_evaluation_criteria_system
            FROM iteach_grades_quantitatives.grades_evaluation_criteria AS gec
            INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_grade_period = gec.id_grade_period
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = grape.id_final_grade AND fga.id_final_grade = gec.id_final_grade
            WHERE grape.id_period_calendar = $id_period AND fga.id_assignment = $id_assignment AND fga.id_student = $id_student AND gec.id_evaluation_plan = $id_evaluation_plan
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubCriteriaGrade($id_grades_evaluation_criteria, $id_evaluation_plan, $id_conf_grade_gathering)
    {
        $results = array();
        $order_by = "";



        $query = $this->conn->query("SELECT gr_gath.grade_item
            FROM iteach_grades_quantitatives.grade_gathering AS gr_gath
            WHERE gr_gath.id_grades_evaluation_criteria = $id_grades_evaluation_criteria 
            AND  gr_gath.id_evaluation_plan = $id_evaluation_plan AND gr_gath.id_conf_grade_gathering = $id_conf_grade_gathering
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getAssignmentsByIDGroupAndTeacher($id_group, $id_academic, $id_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT assgn.*, sbj.*, colb.nombre_hebreo AS hebrew_name_teacher,  CONCAT(colb.apellido_paterno_colaborador, ' ', colb.nombres_colaborador) AS spanish_name_teacher, colb.nombre_corto, sbj_tp.*
            FROM school_control_ykt.assignments AS assgn
            INNER JOIN school_control_ykt.groups AS groups ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assgn.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.subjects_types AS sbj_tp ON sbj.subject_type_id = sbj_tp.subject_type_id
            INNER JOIN colaboradores_ykt.colaboradores AS colb ON assgn.no_teacher = colb.no_colaborador
            WHERE  assgn.no_teacher = '$id_teacher' AND assgn.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic' 
            AND sbj.id_subject != 416
            AND sbj.id_subject != 417
            AND sbj.id_subject != 418
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getInfoGroupByIDGroup($group_id)
    {
        $results = null;

        $query = $this->conn->query("
            SELECT groups.*
            FROM school_control_ykt.groups AS groups
            WHERE groups.id_group  = '$group_id'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getAllGroupsByIdLevelCombinationByTeacher($id_level_combination, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT groups.id_group,groups.group_code,lvl_comb.id_academic_area, aclvg.degree,CONCAT (colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS tutor_name
        	FROM school_control_ykt.level_combinations AS lvl_comb
        	INNER JOIN school_control_ykt.groups AS groups ON lvl_comb.id_campus = groups.id_campus AND lvl_comb.id_section = groups.id_section
            INNER JOIN school_control_ykt.assignments AS assgn ON assgn.id_group = groups.id_group
            INNER JOIN school_control_ykt.academic_levels AS aclv ON lvl_comb.id_academic_level = aclv.id_academic_level
            INNER JOIN school_control_ykt.academic_levels_grade AS aclvg ON groups.id_level_grade = aclvg.id_level_grade AND lvl_comb.id_academic_level = aclvg.id_academic_level
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = groups.no_tutor
            WHERE lvl_comb.id_level_combination = '$id_level_combination' AND assgn.no_teacher = '$no_teacher'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjects($id_group, $id_academic)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT sbj.id_subject, sbj.short_name, sbj.name_subject, CONCAT(col.nombres_colaborador , ' ' ,col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS colab_name
            FROM school_control_ykt.subjects AS sbj
            INNER JOIN school_control_ykt.assignments AS asg ON sbj.id_subject = asg.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
            INNER JOIN colaboradores_ykt.colaboradores AS col ON col.no_colaborador = asg.no_teacher
            WHERE gps.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic' ORDER BY short_name ASC;");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsCoordinator($id_group, $id_academic)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT sbj.*, CONCAT(col.nombres_colaborador, ' ', col.apellido_paterno_colaborador ,' ', col.apellido_materno_colaborador) AS colab_name
            FROM iteach_academic.relationship_managers_assignments AS rma
            INNER JOIN school_control_ykt.assignments AS asgm  ON rma.id_assignment = asgm.id_assignment
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
            WHERE asgm.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic' AND rma.no_teacher = $_SESSION[colab]");

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
            AND assignment.id_group = '$id_group' LIMIT 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAttendanceIndex($id_assignment, $fecha)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT * FROM attendance_records.attendance_index WHERE obligatory = 1 
            AND apply_date like '$fecha%' AND obligatory='1' AND id_assignment ='$id_assignment' 
            ORDER BY id_attendance_index DESC LIMIT 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetIdAssignmentByIdGroupAndTeacher($id_group)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT asgm.id_assignment FROM school_control_ykt.`assignments` AS asgm
            INNER JOIN iteach_academic.relationship_managers_assignments AS mnass ON asgm.id_assignment = mnass.id_assignment
            WHERE id_group ='$id_group' AND  mnass.no_teacher =  '$_SESSION[colab]'");

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
            SELECT apply_date, id_assignment, teacher_passed_attendance FROM attendance_records.attendance_index WHERE id_attendance_index = '$id_att_index';");

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


    public function getStudentAttendance($id_att_index, $id_student)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT COUNT(*) AS student_base FROM attendance_records.attendance_record WHERE id_attendance_index = '$id_att_index' AND id_student ='$id_student' AND attend='1';
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
            SELECT student.id_student, student.id_family, student.group_id, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student, student.id_status_type
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

    public function getListStudentByGroup($id_group)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.id_family, student.group_id, student.student_code, CONCAT(student.lastname, ' ', student.name) AS name_student, student.id_status_type
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
            FROM attendance_records.incidents_attendance AS incidents
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

    public function getLastIDAttendanceIndex()
    {
        return $this->conn->lastInsertId();
    }

    public function getInfoGeneralLastAttendanceDate($id_assignment, $date)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM attendance_records.attendance_index
            WHERE id_assignment = '$id_assignment' AND apply_date LIKE '$date%'
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
}
