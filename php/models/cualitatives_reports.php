<?php
class CualitativesReports extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    //--- WITHOUT MDA ---//
    public function getGroupsQuestionsWithoutMDA($group_id, $id_academic_area){
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.learning_map_types_id != 2 AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsWithoutMDA($group_id, $id_academic_area, $id_student){
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.inscriptions AS insc ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE lmp.learning_map_types_id != 2 AND lvl_com.id_academic_area = $id_academic_area  AND insc.id_student = $id_student
            ");

        //print_r($query);
        
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

      //--- WITHOUT MDA PADRES ---//
    public function getGroupsQuestionsWithoutMDAParents($group_id, $id_academic_area){
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.learning_map_types_id != 2 AND (lmp.id_learning_map = 21) AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ORDER BY lmp.id_learning_map DESC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    //--- WITHOUT MDA PADRES INGLES ---//
    public function getGroupsQuestionsWithoutMDAParentsEnglish($group_id, $id_academic_area){
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.learning_map_types_id != 2 AND (lmp.id_learning_map = 20) AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ORDER BY lmp.id_learning_map DESC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
       //--- WITHOUT MDA PADRES ---//
       public function getGroupsQuestionsWithoutMDAParentsHebrew($group_id, $id_academic_area){
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.learning_map_types_id != 2 AND (lmp.id_learning_map = 19) AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ORDER BY lmp.id_learning_map DESC
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsWithoutMDAParentsEnglish($group_id, $id_academic_area, $id_student){
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.inscriptions AS insc ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE lmp.learning_map_types_id != 2 AND (lmp.id_learning_map = 20) AND lvl_com.id_academic_area = $id_academic_area  AND insc.id_student = $id_student
            ");

        //print_r($query);
        
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsWithoutMDAParents($group_id, $id_academic_area, $id_student){
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.inscriptions AS insc ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE lmp.learning_map_types_id != 2 AND lvl_com.id_academic_area = $id_academic_area  AND insc.id_student = $id_student
            ");

        //print_r($query);
        
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }



    //--- WITHOUT 1 MDA ---//
    public function getGroupsQuestionsMDAWithout1($group_id, $id_learning_map, $id_academic_area){
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.id_learning_map != $id_learning_map AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsMDAWithout1($group_id, $id_learning_map, $id_academic_area){
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE lmp.id_learning_map != $id_learning_map AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ");
        
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }    
    //--- --- ---//
    //--- --- ---//


    //--- GENERAL ---//
    //--- JUST A 1 MDA  ---//
    public function getGroupsQuestionsMDA1($group_id, $id_learning_map, $id_academic_area){
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT assasgmpa.assc_mpa_id, qg.*, lmp.id_learning_map
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            INNER JOIN iteach_grades_qualitatives.associate_lm_eg_eq AS assasgmpa ON lmp.id_learning_map = assasgmpa.id_learning_map
            INNER JOIN iteach_grades_qualitatives.question_groups AS qg ON assasgmpa.id_question_group = qg.id_question_group
            WHERE lmp.id_learning_map = $id_learning_map AND groups.id_group = $group_id AND lvl_com.id_academic_area = $id_academic_area
            ");

        //print_r($query);

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAssignmentsMDA1($group_id, $id_learning_map, $id_academic_area){
        $results = array();

        $query = $this->conn->query("
            SELECT assc_assg_lmp.ascc_lm_assgn, assc_assg_lmp.id_assignment, sbj.id_subject, sbj.name_subject, assg.no_teacher
            FROM school_control_ykt.groups AS groups
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade =  acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assg_lmp ON assg.id_assignment = assc_assg_lmp.id_assignment
            INNER JOIN iteach_grades_qualitatives.learning_maps AS lmp ON assc_assg_lmp.id_learning_map = lmp.id_learning_map
            WHERE lmp.id_learning_map = $id_learning_map AND groups.id_group = $group_id AND lvl_com.id_academic_area = 2
            ");
        
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    //--- --- ---//
    //--- --- ---//


    public function getFinalCommentsMDAByStudent($id_learning_map, $id_assignment, $installment, $id_student){
        $results = array();

        $query = $this->conn->query("
            SELECT fnlcomm.*
            FROM iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assgn_lmp
            INNER JOIN iteach_grades_qualitatives.final_comments AS fnlcomm ON assc_assgn_lmp.ascc_lm_assgn = fnlcomm.ascc_lm_assgn
            WHERE assc_assgn_lmp.id_learning_map = $id_learning_map AND assc_assgn_lmp.id_assignment = $id_assignment AND fnlcomm.no_installment = $installment AND fnlcomm.id_student = $id_student;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAnswersMDAByStudent($assc_mpa_id, $ascc_lm_assgn, $no_installment, $id_student){
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

    public function getDataFormMPA($assc_mpa_id){
        $questions = array();
        $evaluations = array();

        //--- PREGUNTAS ---//
        $query = $this->conn->query("
            SELECT qb.*
            FROM iteach_grades_qualitatives.associate_lm_eg_eq AS assc
            INNER JOIN iteach_grades_qualitatives.match_question_group_questions AS gq ON assc.id_question_group = gq.id_question_group
            INNER JOIN iteach_grades_qualitatives.question_bank AS qb ON gq.id_question_bank = qb.id_question_bank
            WHERE assc.assc_mpa_id = '$assc_mpa_id'
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

        $results = array('questions' => $questions,
            'evaluations' => $evaluations);

        return $results;
    }

    public function getCommentsDirectorMDAByStudent($id_learning_map, $installment, $id_student){
        $results = array();

        $name_director = '';

        $query = $this->conn->query("
            SELECT fnlcomm.*, fnlcomm.directors_comment AS comment
            FROM iteach_grades_qualitatives.associate_assignment_learning_map AS assc_assgn_lmp
            INNER JOIN iteach_grades_qualitatives.final_comments AS fnlcomm ON assc_assgn_lmp.ascc_lm_assgn = fnlcomm.ascc_lm_assgn
            INNER JOIN school_control_ykt.assignments AS assg ON assc_assgn_lmp.id_assignment = assg.id_assignment
            INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = 309
            WHERE assc_assgn_lmp.id_learning_map = $id_learning_map AND fnlcomm.no_installment = $installment AND fnlcomm.id_student = $id_student;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- OBTENEMOS EL NOMBRE DEL DIRECTOR ---//
            $no_director_comment = $row->no_director_comment;
            if($no_director_comment != null && $no_director_comment != '' && $no_director_comment != 'null'){
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
        /* CÓDIGO CORRECTO 

        $results = array();

        $name_director = '';

        $query = $this->conn->query("
            SELECT comm_dir.no_director_comment_add, comm_dir.comment, col.nombre_corto AS director_name
            FROM iteach_grades_qualitatives.directors_comment AS comm_dir
            LEFT JOIN colaboradores_ykt.colaboradores AS col ON col.no_colaborador = comm_dir.no_director_comment_add
            WHERE comm_dir.id_student = $id_student AND comm_dir.id_learning_map = $id_learning_map AND comm_dir.installment = $installment;
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
        //--- --- ---//*/
    }
}
