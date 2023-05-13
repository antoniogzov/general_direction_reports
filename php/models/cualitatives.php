<?php
class Cualitatives extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getLearningMaps($id_assignment)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT lm.*, assc.*
            FROM iteach_grades_qualitatives.learning_maps AS lm
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map
            WHERE assc.id_assignment = '$id_assignment'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsQuestionsMPA($ascc_lm_assgn)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT assasgmpa.assc_mpa_id, qg.id_question_group, qg.name_question_group
            FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS ass_assgn_lm ON assasgmpa.id_learning_map = ass_assgn_lm.id_learning_map
            WHERE ass_assgn_lm.ascc_lm_assgn = '$ascc_lm_assgn'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function checkIfEvaluationMPA($ascc_lm_assgn, $assc_mpa_id, $no_installment, $id_student)
    {
        // 0 NO LLENADO
        // 1 PENDIENTE DE LLENAR
        // 2 LLENADO COMPLETAMENTE

        $result = 0;

        $query = $this->conn->query("
            SELECT id_historical_learning_maps
            FROM iteach_grades_qualitatives.learning_maps_log 
            WHERE ascc_lm_assgn = '$ascc_lm_assgn' AND assc_mpa_id = '$assc_mpa_id' AND no_installment = '$no_installment' AND id_student = '$id_student';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $result = 1;
            $id_historical_learning_maps = $row->id_historical_learning_maps;

            //--- VERIFICAMOS SI HAY ALGÚN NULO EN LAS RESPUESTAS, LO QUE QUIERE DECIR QUE NO LO HAN LLENADO POR COMPLETO ---//
            $nRows = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_qualitatives.questions_log_learning_maps WHERE id_historical_learning_maps = $id_historical_learning_maps AND id_evaluation_bank = 0")->fetchColumn();

            if ($nRows <= 0) {
                $result = 2;
            }
        }

        return (int)$result;
    }

    public function getInfoIndexMDA($ascc_lm_assgn, $assc_mpa_id, $no_installment, $id_student)
    {
        $result = 0;

        $query = $this->conn->query("
            SELECT id_historical_learning_maps
            FROM iteach_grades_qualitatives.learning_maps_log 
            WHERE ascc_lm_assgn = '$ascc_lm_assgn' AND assc_mpa_id = '$assc_mpa_id' AND no_installment = '$no_installment' AND id_student = '$id_student';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $result = $row->id_historical_learning_maps;
        }

        return (int)$result;
    }

    public function getFinalCommentsMPA($ascc_lm_assgn, $no_installment, $id_student)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.final_comments 
            WHERE ascc_lm_assgn = '$ascc_lm_assgn' AND no_installment = '$no_installment' AND id_student = '$id_student';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getFinalCommentsMPA1($id_learning_map, $id_assignment, $installment)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT fnlcomm.*
            FROM iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assgn_lmp
            INNER JOIN iteach_grades_qualitatives.final_comments AS fnlcomm ON assc_assgn_lmp.ascc_lm_assgn = fnlcomm.ascc_lm_assgn
            WHERE assc_assgn_lmp.id_learning_map = $id_learning_map AND assc_assgn_lmp.id_assignment = $id_assignment AND fnlcomm.no_installment = $installment;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getFinalCommentsMPAByStudent($id_learning_map, $id_assignment, $installment, $id_student)
    {
        $results = array();

        $name_director = '';

        $query = $this->conn->query("
            SELECT fnlcomm.*
            FROM iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assgn_lmp
            INNER JOIN iteach_grades_qualitatives.final_comments AS fnlcomm ON assc_assgn_lmp.ascc_lm_assgn = fnlcomm.ascc_lm_assgn
            WHERE assc_assgn_lmp.id_learning_map = $id_learning_map AND assc_assgn_lmp.id_assignment = $id_assignment AND fnlcomm.no_installment = $installment AND fnlcomm.id_student = $id_student;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- OBTENEMOS EL NOMBRE DEL DIRECTOR ---//
            $no_director_comment = $row->no_director_comment;
            if ($no_director_comment != null && $no_director_comment != '' && $no_director_comment != 'null') {
                $get_user = $this->conn->query("SELECT nombre_corto
                    FROM colaboradores_ykt.colaboradores
                    WHERE no_colaborador = '$no_director_comment'");

                while ($user_row = $get_user->fetch(PDO::FETCH_OBJ)) {
                    $name_director = $user_row->nombre_corto;
                }
            }

            $row->director_name = $name_director;
            $results[] = $row;
        }

        return $results;
    }

    public function getFinalCommentsMPAByIDComm($id_comments)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.final_comments 
            WHERE id_comments = '$id_comments';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getIDsAssociateLmEgEq($assc_mpa_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.associate_lm_eg_eq
            WHERE assc_mpa_id = '$assc_mpa_id'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }


        return $results;
    }

    public function getInfoQuestion($id_question)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.question_bank
            WHERE id_question_bank = '$id_question'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }


        return $results;
    }

    public function getInfoEvaluation($id_evaluation)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.evaluation_bank
            WHERE id_evaluation_bank = '$id_evaluation'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }


        return $results;
    }

    public function getDataFormMPA($assc_mpa_id)
    {
        $questions = array();
        $evaluations = array();

        //--- PREGUNTAS ---//
        $query = $this->conn->query("
            SELECT qb.*
            FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assc
            INNER JOIN iteach_grades_qualitatives.match_question_group_questions AS gq ON assc.id_question_group = gq.id_question_group
            INNER JOIN iteach_grades_qualitatives.question_bank AS qb ON gq.id_question_bank = qb.id_question_bank
            WHERE assc.assc_mpa_id = '$assc_mpa_id' AND qb.question_active = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $questions[] = $row;
        }

        //--- RESPUESTAS ---//
        $query = $this->conn->query("
            SELECT eb.*
            FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assc
            INNER JOIN iteach_grades_qualitatives.match_evaluation_group_evaluations AS ge ON assc.id_evaluation_group = ge.id_evaluation_group
            INNER JOIN iteach_grades_qualitatives.evaluation_bank AS eb ON ge.id_evaluation_bank = eb.id_evaluation_bank
            WHERE assc.assc_mpa_id = '$assc_mpa_id'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $evaluations[] = $row;
        }

        $results = array(
            'questions' => $questions,
            'evaluations' => $evaluations
        );

        return $results;
    }
    public function getDataFormMPAHebrew($id_evaluation_source, $no_teacher, $installment, $group_id)
    {
        $questions = array();
        $evaluations = array();

        //--- PREGUNTAS ---//
        $query = $this->conn->query("SELECT DISTINCT gec.*, fga.id_student, CONCAT(student.lastname , ' ', student.name) AS student_name, CASE 
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
        ELSE gec.grade_evaluation_criteria_teacher
        END 
        AS 'grade_evaluation_criteria_teacher',
        CASE 
        WHEN gec.grade_evaluation_criteria_teacher  ='E' THEN '#A6F5AE'
        WHEN gec.grade_evaluation_criteria_teacher  ='MB' THEN '#C8DDF9'
        WHEN gec.grade_evaluation_criteria_teacher  ='B' THEN '#F9FF95'
        WHEN gec.grade_evaluation_criteria_teacher  ='RM' THEN '#FFAF98'
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '#dbdbdb'
        END 
        AS 'html_color'
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca 
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination 
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section 
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = 416 
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment
        INNER JOIN school_control_ykt.students AS student ON student.id_student = fga.id_student
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade 
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_grade_period = grape.id_grade_period
         INNER JOIN  iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan AND ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = grape.id_period_calendar 
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
        WHERE groups.id_group = $group_id AND rel_coord_aca.no_teacher = $no_teacher AND grape.no_period = $installment AND esou.id_evaluation_source = $id_evaluation_source
        ORDER BY esou.evaluation_name
            ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $evaluations[] = $row;
        }

        $results = array(
            'evaluations' => $evaluations
        );

        return $results;
    }
    public function getDataFormMPAHebrewSudent($id_evaluation_source, $no_teacher, $installment, $group_id, $id_student)
    {
        $questions = array();
        $evaluations = array();

        //--- PREGUNTAS ---//
        $query = $this->conn->query("SELECT DISTINCT gec.*, fga.id_student, CONCAT(student.lastname , ' ', student.name) AS student_name, CASE 
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '-'
        ELSE gec.grade_evaluation_criteria_teacher
        END 
        AS 'grade_evaluation_criteria_teacher',
        CASE 
        WHEN gec.grade_evaluation_criteria_teacher  ='E' THEN '#A6F5AE'
        WHEN gec.grade_evaluation_criteria_teacher  ='MB' THEN '#C8DDF9'
        WHEN gec.grade_evaluation_criteria_teacher  ='B' THEN '#F9FF95'
        WHEN gec.grade_evaluation_criteria_teacher  ='RM' THEN '#FFAF98'
        WHEN gec.grade_evaluation_criteria_teacher  IS NULL THEN '#dbdbdb'
        END 
        AS 'html_color'
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca 
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination 
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section 
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = 416 
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment
        INNER JOIN school_control_ykt.students AS student ON student.id_student = fga.id_student
        INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade 
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_grade_period = grape.id_grade_period
         INNER JOIN  iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan AND ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = grape.id_period_calendar 
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source 
        WHERE groups.id_group = $group_id AND rel_coord_aca.no_teacher = $no_teacher AND grape.no_period = $installment AND esou.id_evaluation_source = $id_evaluation_source AND fga.id_student = $id_student
        ORDER BY esou.evaluation_name
            ");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $evaluations[] = $row;
        }

        $results = array(
            'evaluations' => $evaluations
        );

        return $results;
    }
    public function StudentsInfo($id_student)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, UPPER(CONCAT(student.lastname , ' ', student.name)) AS student_name
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            WHERE inscription.id_student = '$id_student' AND student.status = 1
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function saveEvaluationMPA($stmt)
    {
        $result = 0;

        try {

            if ($this->conn->query($stmt)) {
                $result = $this->conn->lastInsertId();
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $stmt;
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function getInfoLearningMapsLog($id_historical_learning_maps)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.learning_maps_log 
            WHERE id_historical_learning_maps = '$id_historical_learning_maps';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getAnswersMPA($id_historical_learning_maps)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_qualitatives.questions_log_learning_maps 
            WHERE id_historical_learning_maps = '$id_historical_learning_maps';
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function updateEvaluationMPA_PDO($sql, $data)
    {

        $stmt = $this->conn->prepare($sql);
        $result = false;

        try {
            if ($stmt->execute($data)) {
                $result = true;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $stmt;
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function updateEvaluationMPA($stmt)
    {
        $result = false;

        try {

            if ($this->conn->query($stmt)) {
                $result = true;
            }
        } catch (Exception $e) {
            echo 'Exception -> ' . $stmt;
            var_dump($e->getMessage());
        }

        return $result;
    }

    public function getLearningMapsCoordinator($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT lmp.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getLearningMapsCoordinatorHebPrim($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT lmp.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map = 24
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupsLMPCoordinator($no_teacher, $lmpID)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT groups.id_group, groups.group_code, CONCAT(acdlvldg.degree, ' - ', groups.letter) AS string_group
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map = $lmpID
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsQuestionsMPACoordinator($no_teacher, $lmpID, $group_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map = $lmpID AND assasgmpa.id_learning_map = $lmpID AND groups.id_group = $group_id
            ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupsQuestionsCombinatedMPACoordinator($no_teacher, $maps_combinated, $group_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map BETWEEN $maps_combinated AND assasgmpa.id_learning_map BETWEEN $maps_combinated AND groups.id_group = $group_id
            ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsQuestionsCombinatedMPACoordinatorHebrew($no_teacher, $installment, $group_id)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT
                esou.* 
                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca 
                INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination 
                INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section 
                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade 
                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND assg.id_subject = 416 
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON assg.id_assignment = fga.id_assignment 
                INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON grape.id_final_grade = fga.id_final_grade 
                INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON gec.id_grade_period = grape.id_grade_period 
                INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ep ON gec.id_evaluation_plan = ep.id_evaluation_plan AND ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = grape.id_period_calendar 
                INNER JOIN iteach_grades_quantitatives.evaluation_source AS esou ON esou.id_evaluation_source = ep.id_evaluation_source
                WHERE groups.id_group = $group_id AND rel_coord_aca.no_teacher = $no_teacher AND grape.no_period = $installment
                ORDER BY esou.evaluation_name
            ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsMPACoordinator($no_teacher, $lmpID, $group_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map = $lmpID AND groups.id_group = $group_id
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsMPACoordinatorHebrew($no_teacher, $lmpID, $group_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map = $lmpID AND groups.id_group = $group_id
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    
    public function checkIfHaveHebrew($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT sbj.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            WHERE rel_coord_aca.no_teacher = $no_teacher AND sbj.id_subject = 416
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsCombinatedMPACoordinator($no_teacher, $maps_combinated, $group_id)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE rel_coord_aca.no_teacher = $no_teacher AND lmp.id_learning_map BETWEEN $maps_combinated AND groups.id_group = $group_id
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAnswersMPACoordinator($assc_mpa_id, $ascc_lm_assgn, $no_installment)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT log_lmp.*, log_questions.*, bq.colorHTML AS bckg
            FROM iteach_grades_qualitatives.learning_maps_log AS log_lmp 
            INNER JOIN iteach_grades_qualitatives.questions_log_learning_maps AS log_questions ON log_lmp.id_historical_learning_maps = log_questions.id_historical_learning_maps
            LEFT JOIN iteach_grades_qualitatives.evaluation_bank AS bq ON log_questions.id_evaluation_bank = bq.id_evaluation_bank
            WHERE log_lmp.ascc_lm_assgn = $ascc_lm_assgn AND log_lmp.assc_mpa_id = $assc_mpa_id AND log_lmp.no_installment = $no_installment;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAnswersMPACoordinatorByStudent($assc_mpa_id, $ascc_lm_assgn, $no_installment, $id_student)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT log_lmp.*, log_questions.*, bq.colorHTML AS bckg
            FROM iteach_grades_qualitatives.learning_maps_log AS log_lmp 
            INNER JOIN iteach_grades_qualitatives.questions_log_learning_maps AS log_questions ON log_lmp.id_historical_learning_maps = log_questions.id_historical_learning_maps
            LEFT JOIN iteach_grades_qualitatives.evaluation_bank AS bq ON log_questions.id_evaluation_bank = bq.id_evaluation_bank
            WHERE log_lmp.ascc_lm_assgn = $ascc_lm_assgn AND log_lmp.assc_mpa_id = $assc_mpa_id AND log_lmp.no_installment = $no_installment AND log_lmp.id_student = $id_student;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsFromCoordinatorWhitReportMDA($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT groups.*
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca_areas
            INNER JOIN iteach_grades_qualitatives.qualitative_reports AS qr ON rel_coord_aca_areas.id_level_combination = qr.id_level_combination
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca_areas.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            WHERE rel_coord_aca_areas.no_teacher = '$no_teacher' AND groups.group_type_id = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsFromTeacherWhitReportMDA($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT groups.*
            FROM school_control_ykt.assignments AS assg
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.groups AS groups ON assg.id_group = groups.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS aclv ON acdlvldg.id_academic_level = aclv.id_academic_level
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_academic_area = sbj.id_academic_area AND lvl_com.id_academic_level = aclv.id_academic_level AND lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN iteach_grades_qualitatives.qualitative_reports AS qr ON lvl_com.id_level_combination = qr.id_level_combination
            WHERE assg.no_teacher = '$no_teacher'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }

    public function getListReports($id_group)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT qlt_reports.qualitative_report_id, qlt_reports.qualitative_report_name, qlt_reports.function_js
            FROM iteach_grades_qualitatives.qualitative_reports AS qlt_reports
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON qlt_reports.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            WHERE groups.id_group = '$id_group'
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
