<?php
class LearningTracking extends data_conn {
    private $conn;
    public function __construct() {
        $this->conn = $this->dbConn();
    }

    public function getListStudentsKetana() {

        $results = null;

        $query = $this->conn->query("SELECT DISTINCT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name, inscription.id_inscription, alg.degree, gps.*
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = inscription.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
            WHERE inscription.id_group = 80 AND student.status = 1
            ORDER BY student.lastname");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getDataDafKesherKetana($id_inscription, $initialDate, $finalDate) {

        $results = null;

        $arr_assgs = array();

        $query = $this->conn->query("SELECT assg.id_assignment
            FROM iteach_grades_quantitatives.work_diary AS wd
            INNER JOIN school_control_ykt.assignments AS assg ON wd.id_assignment = assg.id_assignment
            INNER JOIN school_control_ykt.groups AS groups ON assg.id_group = groups.id_group
            INNER JOIN school_control_ykt.inscriptions AS insc ON groups.id_group = insc.id_group
            INNER JOIN school_control_ykt.additional_registration_std_assg AS add_reg ON assg.id_assignment = add_reg.id_assignment AND insc.id_student = add_reg.id_student
            WHERE (wd.comment_to_date >= '$initialDate' AND wd.comment_to_date <= '$finalDate') AND insc.id_inscription = $id_inscription AND wd.active_comment = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            array_push($arr_assgs, $row->id_assignment);
        }

        $arr_assgs = array_unique($arr_assgs);

        if(count($arr_assgs) > 0){
            foreach($arr_assgs AS $assg){
                $query = $this->conn->query("SELECT assg.id_assignment, sbj.id_subject, CONCAT(sbj.name_subject, ' | ', sbj.hebrew_name) AS name_subject, wd.comments, wd.comment_to_date, colab.nombre_hebreo
                    FROM iteach_grades_quantitatives.work_diary AS wd
                    INNER JOIN school_control_ykt.assignments AS assg ON wd.id_assignment = assg.id_assignment
                    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
                    INNER JOIN school_control_ykt.groups AS groups ON assg.id_group = groups.id_group
                    INNER JOIN school_control_ykt.inscriptions AS insc ON groups.id_group = insc.id_group
                    INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
                    WHERE (wd.comment_to_date >= '$initialDate' AND wd.comment_to_date <= '$finalDate') AND insc.id_inscription = $id_inscription AND assg.id_assignment = $assg AND wd.active_comment = 1
                    ORDER BY wd.comment_to_date DESC LIMIT 1
                    ");

                while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                    $results[] = $row;
                }
            }


        }

        return $results;
    }

    public function getinfoStudentByInscription($id_inscription) {

        $results = null;

        /*$query = $this->conn->query("SELECT aclg.degree, groups.letter
                    FROM school_control_ykt.inscriptions AS insc
                    INNER JOIN school_control_ykt.groups AS groups ON insc.id_group = groups.id_group
                    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
                    WHERE insc.id_inscription = $id_inscription
                    ");*/

        /*$query = $this->conn->query("SELECT aclg.degree, groups.letter
                    FROM school_control_ykt.inscriptions AS insc
                    INNER JOIN school_control_ykt.students AS std ON insc.id_student = std.id_student
                    INNER JOIN school_control_ykt.groups AS groups ON std.group_id = groups.id_group
                    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
                    WHERE insc.id_inscription = $id_inscription
                    ");*/

        $query = $this->conn->query("SELECT DISTINCT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name, inscription.id_inscription, alg.degree, gps.*
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = inscription.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
            WHERE inscription.id_inscription = $id_inscription
            ORDER BY student.lastname");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
}