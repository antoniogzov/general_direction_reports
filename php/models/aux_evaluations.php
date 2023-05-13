<?php
class AuxEvaluations extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function insertGradeGathering($id_conf_grade_gathering, $id_student)
    {

        $error = false;

        $query = $this->conn->query("
            SELECT conf_gg.id_conf_grade_gathering, conf_gg.id_evaluation_plan, gec.id_final_grade, gec.id_grades_evaluation_criteria
            FROM iteach_grades_quantitatives.grades_evaluation_criteria AS gec
            INNER JOIN iteach_grades_quantitatives.conf_grade_gathering AS conf_gg ON gec.id_evaluation_plan = conf_gg.id_evaluation_plan
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment fga ON gec.id_final_grade = fga.id_final_grade
            WHERE conf_gg.id_conf_grade_gathering = $id_conf_grade_gathering AND fga.id_student = $id_student
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $id_conf_grade_gathering = $row->id_conf_grade_gathering;
            $id_evaluation_plan      = $row->id_evaluation_plan;
            $id_final_grade          = $row->id_final_grade;
            $id_grades_evaluation_criteria = $row->id_grades_evaluation_criteria;

            try {
                $this->conn->beginTransaction();
                $this->conn->query("INSERT INTO iteach_grades_quantitatives.grade_gathering (id_conf_grade_gathering, id_evaluation_plan, id_grades_evaluation_criteria, id_final_grade) VALUES ('$id_conf_grade_gathering', '$id_evaluation_plan', '$id_grades_evaluation_criteria', '$id_final_grade')");
                $this->conn->commit();
            } catch (Exception $e) {
                $error = true;
                echo "Ha habido algún error: " . $e;
                $this->conn->rollback();
            }
        }

        return !$error;
    }

    public function checkInitialStructureDataFinalGradesAssignment($id_assignment){

        //--- PROCESO PARA VERIFICAR SI TODOS TIENEN LA ESTRUCTURA PARA ALMACENAR LAS CALIFICACIONES FINALES ---//
        $query = $this->conn->query("
            SELECT ins.id_inscription, ins.id_student, student.student_code
            FROM school_control_ykt.assignments AS assignment
            INNER JOIN school_control_ykt.inscriptions AS ins ON assignment.id_group = ins.id_group
            INNER JOIN school_control_ykt.students AS student ON ins.id_student = student.id_student AND assignment.id_group = ins.id_group
            LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON fg.id_inscription = ins.id_inscription AND ins.id_student = fg.id_student AND assignment.id_assignment = fg.id_assignment
            WHERE assignment.id_assignment = $id_assignment AND student.status = 1 AND fg.id_student IS NULL");
            
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $id_inscription = $row->id_inscription;
            $id_student = $row->id_student;
            $student_code = $row->student_code;

            $stmt = ("INSERT INTO iteach_grades_quantitatives.final_grades_assignment (id_inscription, id_assignment, id_student, student_code) VALUES ($id_inscription, $id_assignment, $id_student, '$student_code')");
            //print_r($stmt);
            $this->conn->query($stmt);
        }

        //--- PROCESO PARA VERIFICAR SI HAY QUE MOSTRAR ALGUNA MATERIA ADICIONAL ---//
        $query = $this->conn->query("
            SELECT ins.id_inscription, adt_std_assg.additional_registration_id, ins.id_student, student.student_code
            FROM school_control_ykt.additional_registration_std_assg AS adt_std_assg
            INNER JOIN school_control_ykt.students AS student ON adt_std_assg.id_student = student.id_student
            INNER JOIN school_control_ykt.inscriptions AS ins ON adt_std_assg.id_group = ins.id_group AND adt_std_assg.id_student = ins.id_student AND student.group_id = adt_std_assg.id_group
            LEFT JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ins.id_student = fg.id_student AND adt_std_assg.id_assignment = fg.id_assignment
            WHERE adt_std_assg.id_assignment = $id_assignment AND student.status = 1 AND fg.id_student IS NULL");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $id_inscription = $row->id_inscription;
            $additional_registration_id = $row->additional_registration_id;
            $id_student = $row->id_student;
            $student_code = $row->student_code;

            $stmt = ("INSERT INTO iteach_grades_quantitatives.final_grades_assignment (id_inscription, additional_registration_id, id_assignment, id_student, student_code) VALUES ($id_inscription, $additional_registration_id, $id_assignment, $id_student, '$student_code')");
            //print_r($stmt);
            $this->conn->query($stmt);
        }
    }

    public function checkInitialStructureDataGradesPeriods($id_assignment, $id_period_calendar){

        //--- PROCESO PARA VERIFICAR SI TODOS TIENEN LA ESTRUCTURA PARA ALMACENAR LOS PROMEDIOS POR PERIODOS ---//
        $query = $this->conn->query("
            SELECT fg.id_final_grade
            FROM iteach_grades_quantitatives.final_grades_assignment AS fg
            INNER JOIN school_control_ykt.assignments AS assignment ON fg.id_assignment = assignment.id_assignment
            INNER JOIN school_control_ykt.inscriptions AS ins ON fg.id_inscription = ins.id_inscription AND assignment.id_group = ins.id_group
            INNER JOIN school_control_ykt.students AS student ON ins.id_student = student.id_student
            LEFT JOIN iteach_grades_quantitatives.grades_period AS gp ON fg.id_final_grade = gp.id_final_grade AND gp.id_period_calendar = $id_period_calendar
            WHERE fg.id_assignment = $id_assignment AND student.status = 1 AND gp.id_final_grade IS NULL");

        $get_no_period = "SELECT no_period FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = $id_period_calendar";

        $nRows = $this->conn->query("select count(*) FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = $id_period_calendar")->fetchColumn();

        if(intval($nRows) > 0){
            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $id_final_grade = $row->id_final_grade;

                $stmt = ("INSERT INTO iteach_grades_quantitatives.grades_period (id_final_grade, id_period_calendar, no_period) VALUES ($id_final_grade, $id_period_calendar, ($get_no_period))");                
                $this->conn->query($stmt);
            }
        }
    }

    public function checkInitialStructureDataGradesExtraExam($id_assignment, $id_period_calendar){

        //--- PROCESO PARA VERIFICAR SI TODOS TIENEN LA ESTRUCTURA PARA ALMACENAR LOS PROMEDIOS POR PERIODOS ---//
        $query = $this->conn->query("
            SELECT fg.id_final_grade
            FROM iteach_grades_quantitatives.final_grades_assignment AS fg
            INNER JOIN school_control_ykt.assignments AS assgn ON fg.id_assignment = assgn.id_assignment
            INNER JOIN school_control_ykt.inscriptions AS ins ON fg.id_inscription = ins.id_inscription
            INNER JOIN school_control_ykt.students AS student ON ins.id_student = student.id_student
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fg.id_final_grade = gp.id_final_grade AND gp.id_period_calendar = $id_period_calendar
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ex_ex ON gp.id_grade_period = ex_ex.id_grade_period AND fg.id_final_grade = ex_ex.id_final_grade
            WHERE fg.id_assignment = $id_assignment AND assgn.enable_extra_grade = 1 AND student.status = 1 AND ex_ex.id_extraordinary_exams IS NULL");

        $get_id_gp = "SELECT gp.id_grade_period
        FROM iteach_grades_quantitatives.grades_period AS gp
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
        WHERE gp.id_period_calendar = $id_period_calendar AND fg.id_assignment = $id_assignment AND fg.id_final_grade";

        $nRows = $this->conn->query("select count(*) FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = $id_period_calendar")->fetchColumn();

        if(intval($nRows) > 0){
            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                //--- --- ---//
                $id_final_grade = $row->id_final_grade;
                //--- --- ---//
                $get_id_gp = "SELECT gp.id_grade_period
                FROM iteach_grades_quantitatives.grades_period AS gp
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
                WHERE gp.id_period_calendar = $id_period_calendar AND fg.id_assignment = $id_assignment AND fg.id_final_grade = $id_final_grade";
                //--- --- ---//

                $stmt = ("INSERT INTO iteach_grades_quantitatives.extraordinary_exams (id_final_grade, id_grade_period) VALUES ($id_final_grade, ($get_id_gp))");
                $this->conn->query($stmt);
            }
        }
    }


    public function checkInitialStructureDataGradesEvaluationsCriteria($id_assignment, $id_period_calendar){

        //--- PROCESO PARA VERIFICAR SI TODOS TIENEN LA ESTRUCTURA PARA ALMACENAR LOS PROMEDIOS POR PERIODOS ---//
        $query = $this->conn->query("
            SELECT gp.id_grade_period, evp.id_evaluation_plan, fg.id_final_grade
            FROM iteach_grades_quantitatives.final_grades_assignment AS fg 
            INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON fg.id_assignment = evp.id_assignment 
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON gp.id_final_grade = fg.id_final_grade AND gp.id_period_calendar = evp.id_period_calendar
            LEFT JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON evp.id_evaluation_plan = gec.id_evaluation_plan AND gec.id_final_grade = fg.id_final_grade
            WHERE evp.id_period_calendar = '$id_period_calendar' AND fg.id_assignment = $id_assignment  AND gec.id_final_grade IS NULL
            ORDER BY fg.id_final_grade, evp.id_evaluation_plan");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $id_grade_period = $row->id_grade_period;
            $id_evaluation_plan = $row->id_evaluation_plan;
            $id_final_grade = $row->id_final_grade;

            $stmt = ("INSERT INTO iteach_grades_quantitatives.grades_evaluation_criteria (id_grade_period, id_evaluation_plan, id_final_grade) VALUES ($id_grade_period, $id_evaluation_plan, $id_final_grade)");
            //print_r($stmt);
            $this->conn->query($stmt);
        }
    }

    public function calculateFinalAverageByAssignment($id_assignment){

        //--- OBETENEMOS TODOS LOS ID DE LAS CALIFICACIONES POR ASIGANTURAS ---//
        $query = $this->conn->query("
            SELECT id_final_grade
            FROM iteach_grades_quantitatives.final_grades_assignment
            WHERE id_assignment = $id_assignment");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_final_grade = $row->id_final_grade;
            $average = NULL;
            //--- --- ---//
            $query1 = $this->conn->query("
                SELECT grade_period
                FROM iteach_grades_quantitatives.grades_period
                WHERE id_final_grade = $id_final_grade");

            if($query1->rowCount() > 0){
                $no_perdiods = $query1->rowCount();
                while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                    if($row1->grade_period != '' && $row1->grade_period != NULL){
                        $average += $row1->grade_period;
                    }
                }

                $average = $average / $no_perdiods;

                if($average != NULL){
                    $stmt = ("UPDATE iteach_grades_quantitatives.final_grades_assignment SET final_grade = $average WHERE id_final_grade = $id_final_grade");
                    $this->conn->query($stmt);
                }
            }
        }
    }
}
