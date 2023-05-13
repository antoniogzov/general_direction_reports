<?php
include 'aux_evaluations.php';

class Evaluations extends data_conn {
    private $conn;
    public function __construct() {
        $this->conn = $this->dbConn();
    }

    public function getGatheringConfig($id_evaluation_plan) {

        $results = array();

        $query = $this->conn->query("
            SELECT conf_gat.*
            FROM iteach_grades_quantitatives.conf_grade_gathering AS conf_gat
            INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON conf_gat.id_evaluation_plan = evp.id_evaluation_plan
            WHERE evp.id_evaluation_plan = $id_evaluation_plan AND evp.gathering = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;

    }

    public function getGatheringStudent($id_conf_grade_gathering, $id_student) {

        $results = array();

        $continue = false;

        $nRows = $this->conn->query("
            SELECT COUNT(*)
            FROM iteach_grades_quantitatives.grades_evaluation_criteria AS gec
            INNER JOIN iteach_grades_quantitatives.conf_grade_gathering AS conf_gg ON gec.id_evaluation_plan = conf_gg.id_evaluation_plan
            INNER JOIN iteach_grades_quantitatives.grade_gathering AS gg ON conf_gg.id_conf_grade_gathering = gg.id_conf_grade_gathering
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment fga ON gg.id_final_grade = fga.id_final_grade
            WHERE gg.id_conf_grade_gathering = $id_conf_grade_gathering AND fga.id_student = $id_student AND gec.id_grades_evaluation_criteria = gg.id_grades_evaluation_criteria
            ")->fetchColumn();

        if (intval($nRows) < 1) {
            $auxEvaluations = new AuxEvaluations;
            if ($auxEvaluations->insertGradeGathering($id_conf_grade_gathering, $id_student)) {
                $continue = true;
            }
        } else {
            $continue = true;
        }

        if ($continue) {
            $query = $this->conn->query("
                SELECT gg.id_grade_gathering, gg.id_conf_grade_gathering, conf_gg.name_item, gg.grade_item, fga.id_student, gg.id_grades_evaluation_criteria
                FROM iteach_grades_quantitatives.grades_evaluation_criteria AS gec
                INNER JOIN iteach_grades_quantitatives.conf_grade_gathering AS conf_gg ON gec.id_evaluation_plan = conf_gg.id_evaluation_plan
                INNER JOIN iteach_grades_quantitatives.grade_gathering AS gg ON conf_gg.id_conf_grade_gathering = gg.id_conf_grade_gathering
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment fga ON gg.id_final_grade = fga.id_final_grade
                WHERE conf_gg.id_conf_grade_gathering = $id_conf_grade_gathering AND fga.id_student = $id_student AND gec.id_grades_evaluation_criteria = gg.id_grades_evaluation_criteria
                ");

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results = $row;
            }
        }

        return $results;
    }

    public function getGradePeriod($id_grade_period) {
        $results = array();

        $query = $this->conn->query("SELECT grade_period, id_extraordinary_exams, grade_extraordinary_examen, gp.grade_period_calc
            FROM iteach_grades_quantitatives.grades_period AS gp
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ex_ex ON gp.id_grade_period = ex_ex.id_grade_period AND gp.id_final_grade = ex_ex.id_final_grade
            WHERE gp.id_grade_period = $id_grade_period");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
    
    public function checkIfExistCommentary($id_grade_period){
        $results = array();

        $query = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.grade_period_commentary WHERE id_grade_period = $id_grade_period AND active = 1");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function checkExtraexamEnabled($id_assignment){
        $results = array();

        $query = $this->conn->query("SELECT enable_extra_grade  
            FROM school_control_ykt.assignments
            WHERE id_assignment = $id_assignment");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function checkDynamicCalculationByAssg($id_assignment){
        $results = array();

        $query = $this->conn->query("SELECT om.operation_model_img
            FROM iteach_dynamic_calculations.operation_model_assignment AS omass
            INNER JOIN iteach_dynamic_calculations.operations_models AS om ON omass.operation_model_id = om.operation_model_id
            WHERE omass.id_assignment = $id_assignment LIMIT 1");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function checkAnyCriteriaOperational($id_final_grade, $id_grade_period){

        $results = array();

        $query = $this->conn->query("
            SELECT gec.grade_evaluation_criteria_teacher AS note_criteria, ev_source.id_evaluation_source, ev_source.criteria_set_id, ev_plan.id_evaluation_plan
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON ev_plan.id_evaluation_source = ev_source.id_evaluation_source
            INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON ev_plan.id_evaluation_plan = gec.id_evaluation_plan
            WHERE gec.id_final_grade = $id_final_grade AND gec.id_grade_period = $id_grade_period AND ev_source.criteria_set_id = 2
            ");

        $criteria_discount_find = 0;

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            if($row->id_evaluation_source == 37 || $row->id_evaluation_source == 38){
                if($criteria_discount_find != 37 && $criteria_discount_find != 38){
                    if(($row->id_evaluation_source == 37 && $row->note_criteria == 'C') || ($row->id_evaluation_source == 38 && floatval($row->note_criteria) < 86)){
                        $criteria_discount_find = $row->id_evaluation_source;
                    }
                    $results[] = $row;
                }
            } else {
                $results[] = $row;
            }
        }
        
        return $results;
    }

    public function getCriteriaFromConfigurationEvaluation($id_assignment, $id_period_calendar) {

        $results = array();

        $query = $this->conn->query("
            SELECT ev_plan.id_evaluation_plan, ev_plan.id_evaluation_source, ev_source.id_evaluation_source, ev_source.evaluation_name, ev_plan.percentage, ev_plan.manual_name, ev_plan.gathering, ev_tp.evaluation_scale, ev_tp.group_id
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON ev_plan.id_evaluation_source = ev_source.id_evaluation_source
            INNER JOIN iteach_grades_quantitatives.evaluation_type AS ev_tp ON ev_plan.evaluation_type_id = ev_tp.evaluation_type_id
            WHERE ev_plan.id_assignment = $id_assignment AND ev_plan.id_period_calendar = $id_period_calendar
            ORDER BY ev_plan.evaluation_type_id, ev_source.evaluation_name, ev_plan.manual_name
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        
        return $results;
    }

    public function GetStudentsFromEvaluation($id_assignment, $infoPeriod) {
        $id_period_calendar = $infoPeriod->id_period_calendar;
        $results = array();

        //--- --- ---//
        $get_ins_add_rsa = "SELECT
        CASE WHEN t3.id_inscription IS NULL THEN 0
        ELSE t3.id_inscription
        END AS id_inscription
        FROM school_control_ykt.assignments AS t1
        LEFT JOIN school_control_ykt.additional_registration_std_assg AS t2 ON t1.id_assignment = t2.id_assignment AND t2.add_remove = 1
        LEFT JOIN school_control_ykt.inscriptions AS t3 ON t2.id_group = t3.id_group AND t2.id_student = t3.id_student
        WHERE t1.id_assignment = $id_assignment";
        //--- --- ---//
        $get_std_rem_rsa = "SELECT
        CASE WHEN t2.id_student IS NULL THEN 0
        ELSE t2.id_student
        END AS id_student
        FROM school_control_ykt.assignments AS t1
        LEFT JOIN school_control_ykt.additional_registration_std_assg AS t2 ON t1.id_assignment = t2.id_assignment AND t2.add_remove = 0
        WHERE t1.id_assignment = $id_assignment";
        //--- --- ---//

        $query = $this->conn->query("
            SELECT final_grade.id_final_grade, grade_period.id_grade_period, grade_period.no_period, grade_period.grade_period, student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS name_student
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.assignments AS assignment ON inscription.id_group = assignment.id_group OR inscription.id_inscription = ($get_ins_add_rsa)
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS final_grade ON inscription.id_inscription = final_grade.id_inscription AND assignment.id_assignment = final_grade.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON final_grade.id_final_grade = grade_period.id_final_grade
            WHERE assignment.id_assignment = $id_assignment AND grade_period.id_period_calendar = $id_period_calendar AND student.status = 1 AND student.id_student != ($get_std_rem_rsa)
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $evaluation_criteria_student = array();

            $id_final_grade  = $row->id_final_grade;
            $id_grade_period = $row->id_grade_period;
            $id_student = $row->id_student;
            $original_grade_period = $row->grade_period;

            $query1 = $this->conn->query("
                SELECT ev_critera.*, evtyp.*, ev_plan.id_evaluation_source, ev_plan.id_assignment
                FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ev_critera
                INNER JOIN iteach_grades_quantitatives.evaluation_plan AS ev_plan ON ev_critera.id_evaluation_plan = ev_plan.id_evaluation_plan
                INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON ev_plan.id_evaluation_source = ev_source.id_evaluation_source
                INNER JOIN iteach_grades_quantitatives.evaluation_type AS evtyp ON ev_plan.evaluation_type_id = evtyp.evaluation_type_id
                WHERE ev_critera.id_grade_period = $id_grade_period AND ev_critera.id_final_grade = $id_final_grade
                ");

            while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {

                //--- OBTENER DE MANER AUTOMÁTICA LA ASISTENCIA DEL ALUMNO ---//
                if($row1->id_evaluation_source == 38){
                    //--- --- ---//
                    $attendance = new Attendance;
                    $total_classes = 0;
                    //--- --- ---//
                    $id_grades_evaluation_criteria = $row1->id_grades_evaluation_criteria;
                    $start_date = $infoPeriod->start_date;
                    $end_date = $infoPeriod->end_date;
                    $id_evaluation_plan = $row1->id_evaluation_plan;
                    //--- --- ---//
                    $AttendanceIndex = $attendance->getAttendanceIndexBasedStudent($id_assignment, $start_date, $end_date, $id_student);

                    $total_classes = count($AttendanceIndex);

                    if ($total_classes > 0) {
                        $total_classes_attended = 0;
                        foreach ($AttendanceIndex AS $att_index) {
                            $id_attendance_index = $att_index->id_attendance_index;

                            $sql_where = " OR ((t1.attend = 0 AND t1.apply_justification = 1) OR (t1.attend = 0 AND t2.apply_justification = 1))";

                            $getStudentAttendance = $attendance->getStudentAttendanceByTypes($id_attendance_index, $id_student, $sql_where);

                            if($getStudentAttendance[0]->student_base > 0){
                                $total_classes_attended += $getStudentAttendance[0]->student_base;
                            }
                        }

                        //--- --- ---//
                        $percentage_attendance = ($total_classes_attended * 100) / $total_classes;
                        $percentage_attendance = round($percentage_attendance, 0, PHP_ROUND_HALF_UP);
                        //--- --- ---//
                    } else {
                        $percentage_attendance = 100;
                    }

                    //--- --- ---//
                    //--- --- ---//
                    $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_system = ?, grade_evaluation_criteria_teacher = ? WHERE id_grades_evaluation_criteria = ?";
                    $stmt= $this->conn->prepare($sql);
                    $stmt->execute([$percentage_attendance, $percentage_attendance, $id_grades_evaluation_criteria]);

                    $row1->grade_evaluation_criteria_system = $percentage_attendance;
                    $row1->grade_evaluation_criteria_teacher = $percentage_attendance;
                }

                //--- OBTENER DE MANER AUTOMÁTICA LA INASISTENCIA DEL ALUMNO ---//
                if($row1->id_evaluation_source == 53){
                    //--- --- ---//
                    $attendance = new Attendance;
                    $total_classes = 0;
                    //--- --- ---//
                    $id_grades_evaluation_criteria = $row1->id_grades_evaluation_criteria;
                    $start_date = $infoPeriod->start_date;
                    $end_date = $infoPeriod->end_date;
                    $id_evaluation_plan = $row1->id_evaluation_plan;
                    //--- --- ---//
                    $AttendanceIndex = $attendance->getAttendanceIndexBasedStudent($id_assignment, $start_date, $end_date, $id_student);

                    $total_classes = count($AttendanceIndex);

                    $total_classes_attended = 0;
                    $absences_student = 0;

                    if ($total_classes > 0) {
                        foreach ($AttendanceIndex AS $att_index) {
                            $id_attendance_index = $att_index->id_attendance_index;

                            $sql_where = " OR ((t1.attend = 0 AND t1.apply_justification = 1) OR (t1.attend = 0 AND t2.apply_justification = 1))";

                            $getStudentAttendance = $attendance->getStudentAttendanceByTypes($id_attendance_index, $id_student, $sql_where);

                            if($getStudentAttendance[0]->student_base > 0){
                                $total_classes_attended += $getStudentAttendance[0]->student_base;
                            }
                        }

                        //--- --- ---//
                        $absences_student = ($total_classes - $total_classes_attended);
                        //--- --- ---//
                    }

                    //--- --- ---//
                    //--- --- ---//
                    $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_system = ?, grade_evaluation_criteria_teacher = ? WHERE id_grades_evaluation_criteria = ?";
                    $stmt= $this->conn->prepare($sql);
                    $stmt->execute([$absences_student, $absences_student, $id_grades_evaluation_criteria]);

                    $row1->grade_evaluation_criteria_system = $absences_student;
                    $row1->grade_evaluation_criteria_teacher = $absences_student;
                }

                $evaluation_criteria_student[] = $row1;
            }

            //--- Recalculamos ---//
            //--- VERIFICAMOS SI PERTENECE A UN MODELO DINÁMICO PARA APLICAR LA FÓRMULA DEL CRITERIO OPERACIONAL ---//
            /*if($original_grade_period > 0){
                $existModel = $this->conn->query("
                    SELECT count(*)
                    FROM iteach_dynamic_calculations.operation_model_assignment AS t1
                    INNER JOIN iteach_dynamic_calculations.operations_models AS t2 ON t1.operation_model_id = t2.operation_model_id
                    WHERE t1.id_assignment = $id_assignment
                    ")->fetchColumn();

                if($existModel > 0){

                    $evaluation = new Evaluations;
                    $grade_period = $evaluation->getGradePeriod($id_grade_period);

                    if(!empty($evaluation->checkDynamicCalculationByAssg($id_assignment))){
                        $evaluation->calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period->grade_period, $id_period_calendar);
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                    }


                    $someCriteriaOperational = $evaluation->checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

                    foreach($someCriteriaOperational AS $info_operational){
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                        $evaluation->calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period->grade_period_calc);
                    }
                    
                }
            }*/

            $row->evaluation_criteria_student = $evaluation_criteria_student;
            $results[]                        = $row;

        }

        return $results;
    }

    public function recalculateDynamicModelAverages($id_assignment, $id_period_calendar) {
        $result = true;

        //--- --- ---//
        $get_ins_add_rsa = "SELECT
        CASE WHEN t3.id_inscription IS NULL THEN 0
        ELSE t3.id_inscription
        END AS id_inscription
        FROM school_control_ykt.assignments AS t1
        LEFT JOIN school_control_ykt.additional_registration_std_assg AS t2 ON t1.id_assignment = t2.id_assignment AND t2.add_remove = 1
        LEFT JOIN school_control_ykt.inscriptions AS t3 ON t2.id_group = t3.id_group AND t2.id_student = t3.id_student
        WHERE t1.id_assignment = $id_assignment";
        //--- --- ---//
        $get_std_rem_rsa = "SELECT
        CASE WHEN t2.id_student IS NULL THEN 0
        ELSE t2.id_student
        END AS id_student
        FROM school_control_ykt.assignments AS t1
        LEFT JOIN school_control_ykt.additional_registration_std_assg AS t2 ON t1.id_assignment = t2.id_assignment AND t2.add_remove = 0
        WHERE t1.id_assignment = $id_assignment";
        //--- --- ---//

        $query = $this->conn->query("
            SELECT final_grade.id_final_grade, grade_period.id_grade_period, grade_period.no_period, grade_period.grade_period, student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS name_student
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.assignments AS assignment ON inscription.id_group = assignment.id_group OR inscription.id_inscription = ($get_ins_add_rsa)
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS final_grade ON inscription.id_inscription = final_grade.id_inscription AND assignment.id_assignment = final_grade.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON final_grade.id_final_grade = grade_period.id_final_grade
            WHERE assignment.id_assignment = $id_assignment AND grade_period.id_period_calendar = $id_period_calendar AND student.status = 1 AND student.id_student != ($get_std_rem_rsa)
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $evaluation_criteria_student = array();

            $id_final_grade  = $row->id_final_grade;
            $id_grade_period = $row->id_grade_period;
            $id_student = $row->id_student;
            $original_grade_period = $row->grade_period;

            //--- Recalculamos ---//
            //--- VERIFICAMOS SI PERTENECE A UN MODELO DINÁMICO PARA APLICAR LA FÓRMULA DEL CRITERIO OPERACIONAL ---//
            if($original_grade_period > 0){
                $existModel = $this->conn->query("
                    SELECT count(*)
                    FROM iteach_dynamic_calculations.operation_model_assignment AS t1
                    INNER JOIN iteach_dynamic_calculations.operations_models AS t2 ON t1.operation_model_id = t2.operation_model_id
                    WHERE t1.id_assignment = $id_assignment
                    ")->fetchColumn();

                if($existModel > 0){

                    $evaluation = new Evaluations;
                    $grade_period = $evaluation->getGradePeriod($id_grade_period);

                    if(!empty($evaluation->checkDynamicCalculationByAssg($id_assignment))){
                        $evaluation->calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period->grade_period, $id_period_calendar);
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                    }


                    $someCriteriaOperational = $evaluation->checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

                    foreach($someCriteriaOperational AS $info_operational){
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                        $evaluation->calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period->grade_period_calc);
                    }
                    
                }
            }
        }

        return $result;
    }

    public function GetListStudentsFromAssignment($id_assignment, $id_period_calendar) {
        $results = array();

        $query = $this->conn->query("
            SELECT final_grade.id_final_grade, grade_period.id_grade_period, grade_period.no_period, grade_period.grade_period, student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS name_student
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.assignments AS assignment ON inscription.id_group = assignment.id_group OR inscription.id_inscription
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS final_grade ON inscription.id_inscription = final_grade.id_inscription AND assignment.id_assignment = final_grade.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON final_grade.id_final_grade = grade_period.id_final_grade
            WHERE assignment.id_assignment = $id_assignment AND grade_period.id_period_calendar = $id_period_calendar AND student.status = 1
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {

            $evaluation_criteria_student = array();

            $id_final_grade  = $row->id_final_grade;
            $id_grade_period = $row->id_grade_period;
            $id_student = $row->id_student;

            $results[] = $row;

        }

        return $results;
    }

    public function updateEvaluation($sql){
        $results = false;

        try {

            if ($this->conn->query($sql)) {
                $results = true;
            }

        } catch (Exception $e) {
            echo 'Exception -> ' . $sql;
            var_dump($e->getMessage());
        }

        return $results;
    }

    public function calculateAveragePerPeriod($id_final_grade, $id_period_calendar){
        $results              = true;
        $divide_percentage    = false;
        $percentage_calculate = 0;

        $id_grade_period = null;

        $stmt = "
        SELECT ev_plan.id_evaluation_plan, ev_plan.percentage, grade_period.id_grade_period
        FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
        INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
        INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
        WHERE fg.id_final_grade = $id_final_grade AND grade_period.id_period_calendar = $id_period_calendar AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
        ";

        $query = $this->conn->query($stmt);

        //--- PROCESO PARA VERIFICAR SI TODOS ESTÁN EN 0% Y DIVIDIR EL PORCENTAJE EN PARTES IGUALES ---//
        $totalResult = count($query->fetchAll());

        $query1 = $this->conn->query("
            SELECT ev_plan.id_evaluation_plan, ev_plan.percentage, grade_period.id_grade_period
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
            INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
            INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
            WHERE fg.id_final_grade = $id_final_grade AND grade_period.id_period_calendar = $id_period_calendar AND (ev_plan.percentage = '0' OR ev_plan.percentage = '' OR ev_plan.percentage = null) AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
            ");

        $totalResult0 = count($query1->fetchAll());
        if ($totalResult == $totalResult0) {
            $divide_percentage    = true;
            $percentage_calculate = 100 / $totalResult;
        }
        //--- --- ---//

        $final_grade_period = null;

        $query = $this->conn->query($stmt);
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_evaluation_plan = $row->id_evaluation_plan;

            if ($divide_percentage) {
                $percentage = $percentage_calculate;
            } else {
                $percentage = $row->percentage;
            }
            $id_grade_period = $row->id_grade_period;
            $grade           = 0;
            //--- --- ---//
            $query1 = $this->conn->query("
                SELECT ev_critera.id_grades_evaluation_criteria, ev_critera.grade_evaluation_criteria_teacher
                FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ev_critera
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg on ev_critera.id_final_grade = fg.id_final_grade
                INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON ev_critera.id_grade_period = gp.id_grade_period
                WHERE ev_critera.id_evaluation_plan = $id_evaluation_plan AND fg.id_final_grade = $id_final_grade AND ev_critera.id_grade_period = $id_grade_period
                ");

            while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                $grade                         = $row1->grade_evaluation_criteria_teacher;
                $id_grades_evaluation_criteria = $row1->id_grades_evaluation_criteria;
                //--- --- ---//
                $fragment_set_sql = "";

                if ($grade != null) {
                    //--- OBTENEMOS A QUE PORCENTAJE EQUIVALE LA CALIFIFACIÓN ---//
                    $final_percentage_criteria = ($grade * $percentage) / 10;

                    //--- OBTENEMOS A QUE CALIFICACIÓN REAL EQUIVALE ---//
                    $grade_real_criteria = ($final_percentage_criteria * 10) / 100;
                    $grade_real_criteria = number_format($grade_real_criteria, 1);
                    $final_grade_period += $grade_real_criteria;
                    //--- --- ---//

                    $fragment_set_sql = "SET grade_evaluation_criteria_system = '$grade_real_criteria', percentage_evaluation_criteria = '$final_percentage_criteria'";

                } else {
                    $grade_real_criteria       = 0;
                    $final_percentage_criteria = 0;

                    $fragment_set_sql = "SET grade_evaluation_criteria_system = NULL, percentage_evaluation_criteria = NULL";
                }

                //--- ACTUALIZAMOS EL PROMEDIO Y CALIFICACION CALCULADAS POR SISTEMA ---//
                $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria " . $fragment_set_sql . " WHERE id_grades_evaluation_criteria = '$id_grades_evaluation_criteria'";
                $this->conn->query($sql);
                //--- --- ---//
            }
        }

        if ($final_grade_period != null) {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $final_grade_period = number_format($final_grade_period, 1);
            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period = $final_grade_period WHERE id_grade_period = $id_grade_period";
            $this->conn->query($sql);

        } else {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period = NULL WHERE id_grade_period = $id_grade_period";
            $this->conn->query($sql);

        }

        $this->calculateFinalGrade($id_final_grade);
    }

    public function calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period_base, $id_period_calendar){
        $op_model_assg_id = 0;

        $query1 = $this->conn->query("
            SELECT op_model_assg_id
            FROM iteach_dynamic_calculations.operation_model_assignment 
            WHERE id_assignment = $id_assignment
            ORDER BY op_model_assg_id DESC LIMIT 1");

        while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
            $op_model_assg_id = $row1->op_model_assg_id;
        }

        //--- OBTENEMOS TODOS LOS CRITERIO BASE PARA BUSCAR SU ID EN RELLOCATION ---//
        $query = $this->conn->query("
            SELECT ev_plan.id_evaluation_plan, ev_plan.percentage AS base_percentage, fg.final_grade
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
            INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
            INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
            INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
            WHERE grade_period.id_grade_period = $id_grade_period AND grade_period.id_period_calendar = $id_period_calendar AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
            ");
        //--- --- ---//

        $calculated_percentage = 0;
        $final_grade_period = null;

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_evaluation_plan = $row->id_evaluation_plan;
            $final_grade = $row->final_grade;
            $calculated_percentage = $row->base_percentage;
            //--- --- ---//
            $query2 = $this->conn->query("
                SELECT condition_grade, percentage
                FROM iteach_dynamic_calculations.reallocation_percentages
                WHERE id_evaluation_plan = $id_evaluation_plan AND op_model_assg_id = $op_model_assg_id
                ");

            while ($row2 = $query2->fetch(PDO::FETCH_OBJ)) {

                $condition_grade = $row2->condition_grade;
                $validation_final = null;

                //--- Buscamos gradeP ---//
                $position = strpos($condition_grade, ':gradeP:');
                if ($position !== false) {
                    $validation_final = str_replace(":gradeP:", $grade_period_base, $condition_grade);
                }

                //--- Buscamos gradeFG ---//
                $position = strpos($condition_grade, ':gradeFG:');
                if ($position !== false) {
                    $validation_final = str_replace(":gradeFG:", $final_grade, $condition_grade);
                }

                if($grade_period_base != null && $grade_period_base != ''){
                    if($final_grade != null && $final_grade != ''){
                        if($validation_final != null){
                            //echo $validation_final;
                            eval("\$fn_validation = $validation_final;");
    
                            if($fn_validation){
                                $calculated_percentage = $row2->percentage;
                            }
                        }
                    }
                }
            }

            $query1 = $this->conn->query("
                SELECT ev_critera.grade_evaluation_criteria_teacher
                FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ev_critera
                WHERE ev_critera.id_evaluation_plan = $id_evaluation_plan AND ev_critera.id_grade_period = $id_grade_period
                ");

            while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                $grade  = $row1->grade_evaluation_criteria_teacher;
                //--- --- ---//
                $fragment_set_sql = "";

                if ($grade != null) {
                    //--- OBTENEMOS A QUE PORCENTAJE EQUIVALE LA CALIFIFACIÓN ---//
                    $final_percentage_criteria = ($grade * $calculated_percentage) / 10;

                    //--- OBTENEMOS A QUE CALIFICACIÓN REAL EQUIVALE ---//
                    $grade_real_criteria = ($final_percentage_criteria * 10) / 100;
                    $grade_real_criteria = number_format($grade_real_criteria, 1);
                    $final_grade_period += $grade_real_criteria;
                    //--- --- ---//
                }
            }

        }

        if ($final_grade_period != null) {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $final_grade_period = number_format($final_grade_period, 1);

            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = ? WHERE id_grade_period = ?";

            $stmt= $this->conn->prepare($sql);
            $stmt->execute([$final_grade_period, $id_grade_period]);

        } else {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = ? WHERE id_grade_period = ?";

            $stmt= $this->conn->prepare($sql);
            $stmt->execute([NULL, $id_grade_period]);

        }
    }

    public function calculateAveragePeriodByCriteriaDynamic($id_grade_period, $note_criteria, $id_evaluation_plan, $grade_period_base){

        //--- OBTENEMOS TODOS LOS CRITERIO BASE PARA BUSCAR SU ID EN RELLOCATION ---//
        $query = $this->conn->query("
            SELECT fml.formulation_operations
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            INNER JOIN iteach_grades_quantitatives.formulation_operations AS fml ON ev_plan.formulation_operations_id = fml.formulation_operations_id
            WHERE ev_plan.id_evaluation_plan = $id_evaluation_plan AND fml.formulation_operations_id != 1
            ");
        //--- --- ---//

        $calculated_percentage = 0;
        $final_grade_period = null;

        if(is_float($note_criteria)){
            $note_criteria = floatval($note_criteria);
        } else {
            $note_criteria = strval($note_criteria);
        }

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $arr_data = array();
            $arr_data = $row->formulation_operations;
            $arr_data = explode(";", $arr_data);

            //intval(':value:') < 85;:gradeP:-(:gradeP:*.15)

            if(count($arr_data) == 2){
                $condition_grade = $arr_data[0];
                $formulation_grade = $arr_data[1];
                $final_grade_period = $grade_period_base;            
                //--- --- ---//
                $validation_final = null;

                //--- Buscamos :value: ---//
                $position = strpos($condition_grade, ':value:');
                if ($position !== false) {
                    $validation_final = str_replace(":value:", $note_criteria, $condition_grade);
                }

                //--- Buscamos :gradeP: ---//
                $position = strpos($validation_final, ':gradeP:');
                if ($position !== false) {
                    $validation_final = str_replace(":gradeP:", $grade_period_base, $validation_final);
                }

                if($validation_final != null){
                    try{
                        eval("\$fn_validation = $validation_final;");
                    }  catch (ParseError $e) {
                        echo 'Errorsito';
                        echo $validation_final;
                        echo '<br/>';
                        print_r($e);
                    }

                    if($fn_validation && $grade_period_base != ''){
                        //--- Re-calculamos el valor de GradePeriod ---//
                        //--- Buscamos :value: ---//
                        $position = strpos($formulation_grade, ':value:');
                        if ($position !== false) {
                            $formulation_grade = str_replace(":value:", $note_criteria, $formulation_grade);
                        }

                        $position = strpos($formulation_grade, ':gradeP:');
                        if ($position !== false) {
                            $f_grade_period = str_replace(":gradeP:", $grade_period_base, $formulation_grade);
                            try{
                                eval("\$final_grade_period = $f_grade_period;");
                            }  catch (ParseError $e) {
                                echo 'Errorsito';
                                echo $f_grade_period;
                                echo '<br/>';
                                print_r($e);
                            }
                            $final_grade_period = $final_grade_period;
                        }
                    }
                }
            }

        }

        if ($final_grade_period != null) {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $final_grade_period = number_format($final_grade_period, 1);

            $final_grade_period = $final_grade_period > 10 ? 10 : $final_grade_period;

            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = ? WHERE id_grade_period = ?";

            $stmt= $this->conn->prepare($sql);
            $stmt->execute([$final_grade_period, $id_grade_period]);

        } else {
            //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = ? WHERE id_grade_period = ?";

            $stmt= $this->conn->prepare($sql);
            $stmt->execute([NULL, $id_grade_period]);

        }
    }

    public function calculateFinalGrade($id_final_grade) {
        $final_grade = null;

        $query = $this->conn->query("
            SELECT gp.grade_period
            FROM iteach_grades_quantitatives.grades_period AS gp
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
            WHERE fg.id_final_grade = $id_final_grade AND gp.grade_period != '0' AND gp.grade_period != ''
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $final_grade += floatval($row->grade_period);
        }

        $query = $this->conn->query("
            SELECT gp.grade_period
            FROM iteach_grades_quantitatives.grades_period AS gp
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
            WHERE fg.id_final_grade = $id_final_grade AND gp.grade_period != '0' AND gp.grade_period != ''
            ");

        $fragment_set_sql = '';
        if ($final_grade > 0) {
            $final_grade      = $final_grade / count($query->fetchAll());
            $fragment_set_sql = "SET final_grade = '$final_grade'";
        } else if ($final_grade == null) {
            $fragment_set_sql = "SET final_grade = NULL";
        }

        //--- ACTUALIZAMOS EL PROMEDIO FINAL DE LA ASIGNATURA ---//
        $sql = "UPDATE iteach_grades_quantitatives.final_grades_assignment " . $fragment_set_sql . " WHERE id_final_grade = $id_final_grade";
        $this->conn->query($sql);
    }

    public function calculateAverageGathering($id_grades_evaluation_criteria){
        $grade_evaluation_criteria_teacher = null;
        $total_items = 0;

        $query = $this->conn->query("
            SELECT grade_item
            FROM iteach_grades_quantitatives.grade_gathering AS gg WHERE gg.id_grades_evaluation_criteria = $id_grades_evaluation_criteria");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            if($row->grade_item != null && $row->grade_item != ''){
                $grade_evaluation_criteria_teacher += floatval($row->grade_item);
                $total_items ++;
            }
        }

        if($total_items > 0){
            $grade_evaluation_criteria_teacher = $grade_evaluation_criteria_teacher / $total_items;
            $grade_evaluation_criteria_teacher = number_format($grade_evaluation_criteria_teacher, 1);

            //--- ACTUALIZAMOS EL PROMEDIO EN EL CRITERIO PRINCIPAL CORRESPONDIENTE ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_teacher = '$grade_evaluation_criteria_teacher' WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";
            $this->conn->query($sql);
        } else {
            //--- ACTUALIZAMOS EL PROMEDIO EN EL CRITERIO PRINCIPAL CORRESPONDIENTE ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_teacher = NULL WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";
            $this->conn->query($sql);
        }
    }

    public function getAllGradeStudentsByPeriod($id_assignment){
        $results = array();

        $query = $this->conn->query("
            SELECT ins.id_inscription, student.student_code, student.id_student, CONCAT(student.name,' ',student.lastname) AS student_name
            FROM school_control_ykt.assignments AS assignment
            INNER JOIN school_control_ykt.inscriptions AS ins ON assignment.id_group = ins.id_group
            INNER JOIN school_control_ykt.students AS student ON ins.id_student = student.id_student
            WHERE assignment.id_assignment = $id_assignment
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $id_inscription = $row->id_inscription;
            $student_name   = $row->student_name;
            //--- --- ---//
            $query1 = $this->conn->query("
                SELECT pc.id_period_calendar, gp.id_grade_period, gp.grade_period, pc.no_period
                FROM iteach_grades_quantitatives.grades_period AS gp
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
                INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON gp.id_period_calendar = pc.id_period_calendar
                WHERE fg.id_inscription = $id_inscription
                ORDER BY pc.no_period
                ");

            while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                //--- --- ---//
                $row->grades[] = $row1;
                //--- --- ---//
                $results[] = $row;
                //--- --- ---//
            }
        }

        return $results;
    }

    public function getFinalGradeAssignmentStudent($id_assignment, $id_inscription){

        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_grades_quantitatives.final_grades_assignment 
            WHERE id_assignment = $id_assignment AND id_inscription = $id_inscription
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            //--- --- ---//
            $results = $row;
            //--- --- ---//
        }

        return $results;
    }
}
