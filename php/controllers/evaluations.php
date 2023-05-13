<?php
include '../../../general/php/models/Connection.php';
include '../models/evaluations.php';
include '../models/save_logs.php';
include '../models/helpers.php';

date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function saveGradeGathering() {

    $id_grade_gathering = $_POST['id_grade_gathering'];
    $grade              = $_POST['grade'];
    $is_averaged        = $_POST['is_averaged'];

    session_start();

    $previous_value = null;
    $new_value = $grade;

    $evaluation = new Evaluations;
    $logs = new ActionsLogs;
    $conn = $evaluation->dbConn();

    //--- OBTENEMOS EL ID DE ASSG ---//
    $id_assignment = 0;
    $sql = $conn->query("
        SELECT evp.id_assignment, g_gat.grade_item
        FROM iteach_grades_quantitatives.grade_gathering AS g_gat
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON g_gat.id_evaluation_plan = evp.id_evaluation_plan
        WHERE g_gat.id_grade_gathering = $id_grade_gathering");

    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        $id_assignment = $row['id_assignment'];
        $previous_value = $row['grade_item'];
    }

    $sql = "UPDATE iteach_grades_quantitatives.grade_gathering SET grade_item = ";

    if ($grade == '') {
        $sql .= 'NULL';
    } else {
        $sql .= "'$grade'";
    }

    $sql .= " WHERE id_grade_gathering = '$id_grade_gathering'";

    if ($evaluation->updateEvaluation($sql)) {
        if ($is_averaged == '1') {
            $conn               = $evaluation->dbConn();
            $id_student         = null;
            $id_period_calendar = null;
            $id_grade_period    = null;

            $sql = $conn->query("
                SELECT gg.id_grades_evaluation_criteria, fg.id_final_grade, inscription.id_student, gp.id_period_calendar, ec.id_grade_period, evs.evaluation_name, std.student_code
                FROM iteach_grades_quantitatives.grade_gathering AS gg
                INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS ec
                INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON ec.id_evaluation_plan = evp.id_evaluation_plan
                INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON evp.id_evaluation_source = evs.id_evaluation_source
                ON gg.id_grades_evaluation_criteria = ec.id_grades_evaluation_criteria
                INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ec.id_final_grade = fg.id_final_grade
                INNER JOIN school_control_ykt.inscriptions AS inscription ON fg.id_inscription = inscription.id_inscription
                INNER JOIN school_control_ykt.students AS std ON inscription.id_student = std.id_student AND fg.id_student = std.id_student
                INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON ec.id_grade_period = gp.id_grade_period
                WHERE gg.id_grade_gathering = '$id_grade_gathering'
                ");


            if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                $id_grades_evaluation_criteria = $row['id_grades_evaluation_criteria'];
                $evaluation->calculateAverageGathering($id_grades_evaluation_criteria);

                $id_final_grade     = $row['id_final_grade'];
                $id_period_calendar = $row['id_period_calendar'];
                $id_grade_period    = $row['id_grade_period'];
                $student_code       = $row['student_code'];
                $evaluation_name    = $row['evaluation_name'];

                $evaluation->calculateAveragePerPeriod($id_final_grade, $id_period_calendar);
                $grade_period = $evaluation->getGradePeriod($id_grade_period);

                //--- AGREGAMOS EL REGISTRO DE LOG ---//
                $additional_comments = 'id_grade_gathering=' . $id_grade_gathering . ' | evaluation_name=' . $evaluation_name . ' | grade_period=' . $grade_period->grade_period;

                $logs->save_log($_SESSION['colab'], 'iteach_grades_quantitatives.grade_gathering', 'grade_item', $id_grade_gathering, 'change grade gathering', $previous_value, $new_value, $id_assignment, $id_period_calendar, $student_code, $additional_comments);
                //--- --- ---//

                if (!empty($evaluation->checkDynamicCalculationByAssg($id_assignment))) {
                    $evaluation->calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period->grade_period, $id_period_calendar);
                    $grade_period = $evaluation->getGradePeriod($id_grade_period);
                }


                $someCriteriaOperational = $evaluation->checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

                foreach ($someCriteriaOperational as $info_operational) {
                    $grade_period = $evaluation->getGradePeriod($id_grade_period);
                    $evaluation->calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period->grade_period_calc);
                }

                $grade_period = $evaluation->getGradePeriod($id_grade_period);
                //--- OBTENEMOS EL PROMEDIO CALCULADO AUTOMÁTICAMENTE ---//
                $sql1 = $conn->query("
                    SELECT grade_evaluation_criteria_teacher
                    FROM iteach_grades_quantitatives.grades_evaluation_criteria 
                    WHERE id_grades_evaluation_criteria = '$id_grades_evaluation_criteria'
                    ");

                if ($row1 = $sql1->fetch(PDO::FETCH_ASSOC)) {
                    $grade_evaluation_criteria_teacher     = $row1['grade_evaluation_criteria_teacher'];
                }

                $data = array(
                    'response' => true,
                    'grade_period' => $grade_period->grade_period,
                    'grade_period_calc' => $grade_period->grade_period_calc,
                    'grade_evaluation_criteria_teacher' => $grade_evaluation_criteria_teacher,
                    'message'                => 'Calificación agregada correctamente'
                );
                //--- --- ---//
            } else {
                $data = array(
                    'response' => false,
                    'message'                => 'Ocurrió un error al intentar calcular el promedio por periodo, intentelo nuevamente'
                );
            }
        } else {
            //--- --- ---//
            $data = array(
                'response' => true,
                'grade_period' => $grade,
                'grade_period_calc' => '',
                'grade_evaluation_criteria_teacher' => $grade,
                'message'                => 'Calificación agregada correctamente'
            );
            //--- --- ---//
        }
    } else {
        $data = array(
            'response' => false,
            'message'                => 'Ocurrió un problema, intentelo nuevamente'
        );
    }

    echo json_encode($data);
}

function getAveragesPeriods() {

    $id_assignment = $_POST['id_assignment'];
    $id_student = $_POST['id_student'];
    $id_period_calendar = $_POST['id_period_calendar'];

    $student_code = $_POST['student_code'];
    $name_student = $_POST['name_student'];
    $group_and_subject = $_POST['group_and_subject'];

    $evaluation = new Evaluations;
    $conn       = $evaluation->dbConn();

    $query_periods = $conn->query("SELECT t2.* 
    FROM iteach_grades_quantitatives.period_calendar AS t1
    INNER JOIN iteach_grades_quantitatives.period_calendar as t2 ON t1.id_level_combination = t2.id_level_combination
    WHERE t1.id_period_calendar = $id_period_calendar");

    $html = '';
    $html .= '<h3 class="text-center">CALIFICACIONES DEL CICLO ESCOLAR</h3>';
    $html .= '<h4 class="text-center">'.$student_code.' | '.strtoupper($name_student).'</h4>';
    $html .= '<h5 class="text-center">'.$group_and_subject.'</h5>';
    $html .= '<div class="table-responsive">';
    $html .= '<table class="table table-striped">';
    $html .= '<thead class="table-dark">';
    $html .= '<tr>';
    $html .= '<th></th>';
    while ($periods = $query_periods->fetch(PDO::FETCH_OBJ)) {
        $html .= '<th>P. ' . $periods->no_period . ' </th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td>PROMEDIO POR <br>PERIODO.</td>';

    $query_periods = $conn->query("SELECT t2.* 
    FROM iteach_grades_quantitatives.period_calendar AS t1
    INNER JOIN iteach_grades_quantitatives.period_calendar as t2 ON t1.id_level_combination = t2.id_level_combination
    WHERE t1.id_period_calendar = $id_period_calendar");
    while ($periods = $query_periods->fetch(PDO::FETCH_OBJ)) {
        $id_period_calendar_row = $periods->id_period_calendar;
        $period_avg = '-';

        $query_period_qualification = $conn->query("SELECT grape.* 
                FROM iteach_grades_quantitatives.final_grades_assignment AS fga
                INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
                WHERE grape.id_period_calendar = $id_period_calendar_row AND fga.id_student = $id_student AND fga.id_assignment = $id_assignment
                ");
        if ($per_avg = $query_period_qualification->fetch(PDO::FETCH_ASSOC)) {
            if ($per_avg['grade_period'] != "") {
                $period_avg = $per_avg['grade_period'];
                $html .= '<td>' . $period_avg . '</td>';
            } else {
                $html .= '<td>-</td>';
            }
        } else {
            $html .= '<td>-</td>';
        }
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<td>PROMEDIO <br> DINÁMICO.</td>';

    $query_periods = $conn->query("SELECT t2.* 
    FROM iteach_grades_quantitatives.period_calendar AS t1
    INNER JOIN iteach_grades_quantitatives.period_calendar as t2 ON t1.id_level_combination = t2.id_level_combination
    WHERE t1.id_period_calendar = $id_period_calendar");
    while ($periods = $query_periods->fetch(PDO::FETCH_OBJ)) {
        $id_period_calendar_row = $periods->id_period_calendar;
        $period_avg = '-';

        $query_period_qualification = $conn->query("SELECT grape.* 
                FROM iteach_grades_quantitatives.final_grades_assignment AS fga
                INNER JOIN iteach_grades_quantitatives.grades_period AS grape ON fga.id_final_grade = grape.id_final_grade
                WHERE grape.id_period_calendar = $id_period_calendar_row AND fga.id_student = $id_student AND fga.id_assignment = $id_assignment
                ");
        if ($per_avg = $query_period_qualification->fetch(PDO::FETCH_ASSOC)) {
            if ($per_avg['grade_period_calc'] != "") {
                $period_avg = $per_avg['grade_period_calc'];
                $html .= '<td>' . $period_avg . '</td>';
            } else {
                $html .= '<td>-</td>';
            }
        } else {
            $html .= '<td>-</td>';
        }
    }
    $html .= '</tr>';
    
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
    //--- --- ---//
    $data = array(
        'response' => true,
        'message'                => 'Al parecer no hay alumnos inscritos en esta materia, intentelo nuevamente',
        'html' => $html
    );
    //--- --- ---//


    echo json_encode($data);
}

function getGatheringEvaluation() {
    $id_evaluation_plan = $_POST['id_evaluation_plan'];

    $evaluation = new Evaluations;
    $conn       = $evaluation->dbConn();

    $sql = $conn->query("
        SELECT gec.id_evaluation_plan, gec.id_final_grade, evp.manual_name, source.evaluation_name, evp.id_period_calendar, evp.id_assignment
        FROM iteach_grades_quantitatives.grades_evaluation_criteria AS gec
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON gec.id_evaluation_plan = evp.id_evaluation_plan
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS source ON evp.id_evaluation_source = source.id_evaluation_source
        WHERE evp.id_evaluation_plan = $id_evaluation_plan");

    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        $id_evaluation_plan = $row['id_evaluation_plan'];
        $id_final_grade     = $row['id_final_grade'];
        $id_assignment      = $row['id_assignment'];
        $id_period_calendar = $row['id_period_calendar'];
        $name_criteria      = $row['evaluation_name'];
        if ($row['manual_name'] != '') {
            $name_criteria = $row['manual_name'];
        }

        //--- --- ---//
        $students = $evaluation->GetListStudentsFromAssignment($id_assignment, $id_period_calendar);
        //--- --- ---//

        if (!empty($students)) {
            $confGathering = $evaluation->getGatheringConfig($id_evaluation_plan);
            foreach ($students as $student) {
                if (!empty($confGathering)) {
                    //--- --- ---//
                    foreach ($confGathering as $cnfGth) {
                        $dataGatheringStudent[] = $evaluation->getGatheringStudent($cnfGth->id_conf_grade_gathering, $student->id_student);
                    }
                    //--- --- ---//
                }
            }

            $data = array(
                'response' => true,
                'name_criteria' => $name_criteria,
                'students'      => $students,
                'confGathering' => $confGathering,
                'studentsGathering' => $dataGatheringStudent,
            );
        } else {
            //--- --- ---//
            $data = array(
                'response' => false,
                'message'                => 'Al parecer no hay alumnos inscritos en esta materia, intentelo nuevamente'
            );
            //--- --- ---//
        }
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Al parecer no hay alumnos inscritos en esta materia, intentelo nuevamente'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function saveGradeCriteria() {
    session_start();
    $data = array();

    $id_grades_evaluation_criteria = $_POST['id_grade_evaluation_criteria'];
    $grade                        = $_POST['grade'];
    $is_averaged                  = $_POST['is_averaged'];

    $new_value = $grade;

    if ($grade == '') {
        $grade = 'NULL';
    } else {
        $grade = "'$grade'";
    }


    $evaluation = new Evaluations;
    $logs = new ActionsLogs;
    $conn = $evaluation->dbConn();

    //--- OBTENEMOS LA CALIFICACIÓN ORIGINAL ---//
    $previous_value = 'No obtenido';
    $stmt0 = "SELECT grade_evaluation_criteria_teacher FROM iteach_grades_quantitatives.grades_evaluation_criteria WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";
    $sql0 = $conn->query($stmt0);
    if ($row0 = $sql0->fetch(PDO::FETCH_OBJ)) {
        $previous_value = $row0->grade_evaluation_criteria_teacher;
    }

    $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_teacher = $grade WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";

    //--- --- ---//
    $stmt = "
    SELECT fg.id_final_grade, inscription.id_student, gp.id_period_calendar, assg.id_assignment, ec.id_grade_period, evs.criteria_set_id, evp.id_evaluation_plan, std.student_code, evs.id_evaluation_source, evs.evaluation_name
    FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ec
    INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON ec.id_evaluation_plan = evp.id_evaluation_plan
    INNER JOIN iteach_grades_quantitatives.evaluation_source AS evs ON evp.id_evaluation_source = evs.id_evaluation_source
    INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ec.id_final_grade = fg.id_final_grade
    INNER JOIN school_control_ykt.assignments AS assg ON fg.id_assignment = assg.id_assignment
    INNER JOIN school_control_ykt.inscriptions AS inscription ON fg.id_inscription = inscription.id_inscription AND assg.id_group = inscription.id_group
    INNER JOIN school_control_ykt.students AS std ON inscription.id_student = std.id_student AND fg.id_student = std.id_student
    INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON ec.id_grade_period = gp.id_grade_period
    WHERE ec.id_grades_evaluation_criteria = '$id_grades_evaluation_criteria'
    ";

    $id_period_calendar = null;
    $id_grade_period    = null;
    //--- --- ---//

    if ($evaluation->updateEvaluation($sql)) {
        $sql1 = $conn->query($stmt);

        if ($row = $sql1->fetch(PDO::FETCH_ASSOC)) {
            $id_final_grade     = $row['id_final_grade'];
            $id_period_calendar = $row['id_period_calendar'];
            $id_grade_period    = $row['id_grade_period'];
            $id_assignment      = $row['id_assignment'];
            $id_evaluation_plan = $row['id_evaluation_plan'];
            $student_code = $row['student_code'];
            $id_evaluation_source = $row['id_evaluation_source'];
            $evaluation_name = $row['evaluation_name'];

            $evaluation->calculateAveragePerPeriod($id_final_grade, $id_period_calendar);
            $grade_period = $evaluation->getGradePeriod($id_grade_period);

            //--- AGREGAMOS EL REGISTRO DE LOG ---//
            $additional_comments = 'id_evaluation_source=' . $id_evaluation_source . ' | evaluation_name=' . $evaluation_name . ' | grade_period=' . $grade_period->grade_period;

            $logs->save_log($_SESSION['colab'], 'iteach_grades_quantitatives.grades_evaluation_criteria', 'grade_evaluation_criteria_teacher', $id_grades_evaluation_criteria, 'change grade criteria', $previous_value, $new_value, $id_assignment, $id_period_calendar, $student_code, $additional_comments);
            //--- --- ---//

            if (!empty($evaluation->checkDynamicCalculationByAssg($id_assignment))) {
                $evaluation->calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period->grade_period, $id_period_calendar);
                $grade_period = $evaluation->getGradePeriod($id_grade_period);
            }


            $someCriteriaOperational = $evaluation->checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

            foreach ($someCriteriaOperational as $info_operational) {
                $grade_period = $evaluation->getGradePeriod($id_grade_period);
                $evaluation->calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period->grade_period_calc);
            }

            $grade_period = $evaluation->getGradePeriod($id_grade_period);

            //--- --- ---//
            $data = array(
                'response' => true,
                'grade_period'      => $grade_period->grade_period,
                'grade_period_calc' => $grade_period->grade_period_calc,
                'message'           => 'Calificación agregada correctamente'
            );
            //--- --- ---//

        } else {
            $data = array(
                'response' => false,
                'message'                => 'Ocurrió un error al intentar calcular el promedio por periodo, intentelo nuevamente'
            );
        }
        //--- --- ---//
    } else {
        $data = array(
            'response'  => false,
            'message'   => 'Ocurrió un problema, intentelo nuevamente'
        );
    }

    echo json_encode($data);
}

function saveTitleGathering() {
    $data = array();

    $id_conf_grade_gathering = $_POST['id_conf_grade_gathering'];
    $new_title  = $_POST['new_title'];

    if ($new_title == '') {
        $new_title = 'NULL';
    } else {
        $new_title = "'$new_title'";
    }

    $sql = "UPDATE iteach_grades_quantitatives.conf_grade_gathering SET name_item = $new_title WHERE id_conf_grade_gathering = '$id_conf_grade_gathering'";

    $evaluation = new Evaluations;
    //--- --- ---//

    if ($evaluation->updateEvaluation($sql)) {
        //--- --- ---//
        $data = array(
            'response' => true,
            'message'                => 'Calificación agregada correctamente'
        );
        //--- --- ---//
    } else {
        $data = array(
            'response'  => false,
            'message'   => 'Ocurrió un problema, intentelo nuevamente'
        );
    }

    echo json_encode($data);
}

function saveGradeExtraExam() {
    $data = array();

    $id_extraordinary_exams = $_POST['id_extraordinary_exams'];
    $grade                        = $_POST['grade'];
    if ($grade == '') {
        $grade = 'NULL';
    } else {
        $grade = "'$grade'";
    }

    $sql = "UPDATE iteach_grades_quantitatives.extraordinary_exams SET grade_extraordinary_examen = $grade  WHERE id_extraordinary_exams = '$id_extraordinary_exams'";

    $evaluation = new Evaluations;

    if ($evaluation->updateEvaluation($sql)) {
        $data = array(
            'response' => true,
            'grade_period' => $grade,
            'grade_evaluation_criteria_teacher' => $grade,
            'message'                => 'Calificación agregada correctamente'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'Ocurrió un problema, intentelo nuevamente'
        );
    }

    echo json_encode($data);
}

function getAveragesByIdAssignments() {
    $helpers = new Helpers;
    $id_assignment = $_POST['id_assignment'];

    $id_level_combination = $helpers->getIdsLevelCombination($id_assignment);
    $id_level_combination = $id_level_combination->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    $subject_info = $helpers->getInfoSubjectAndGroupByIdAssignment($id_assignment);



    $name_subject = $subject_info->name_subject;
    $name_group = $subject_info->group_code;
    $name_teacher = $subject_info->name;
    $id_group = $subject_info->id_group;

    $students = getListStudentsByIDgroup($id_group);
    $students = count($students);



    $promedios = array();
    $periodos = array();
    $html_sweet_alert = "<h2>Materia: " . $name_subject . "</h2>";
    $html_sweet_alert .= "<h3>Grupo: " . $name_group . " (Alumnos: " . $students . ") </h3>";
    $html_sweet_alert .= "<h3>Profesor: " . $name_teacher . "</h3><br>";
    $html_sweet_alert .= "<div class='table-responsive'>";
    $html_sweet_alert .= "<table class='table table-bordered table-striped table-hover'>";
    $html_sweet_alert .= "<thead class='thead-dark'>";
    $html_sweet_alert .= "<tr>";
    foreach ($periods as $period) {
        $id_period_calendar = $period->id_period_calendar;
        $html_sweet_alert .= "<th style='color:white !important;'>Periodo " . $period->no_period . "</th>";
    }
    $html_sweet_alert .= "</tr>";
    $html_sweet_alert .= "</thead>";
    $html_sweet_alert .= "<tr>";

    foreach ($periods as $period) {
        $id_period_calendar = $period->id_period_calendar;
        $period_average = getGatheringGradesPeriod($id_assignment, $id_period_calendar);
        $html_sweet_alert .= "<th>" . $period_average . "</th>";
    }
    $html_sweet_alert .= "</tr>";
    $html_sweet_alert .= "</table>";
    $html_sweet_alert .= "</div>";

    if (!empty($periods)) {
        $data = array(
            'response' => true,
            'data'                => $periodos,
            'html_sweet_alert'    => $html_sweet_alert,
            'message'                => 'Periodos obtenidos correctamente'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'Ocurrió un problema, intentelo nuevamente'
        );
    }




    echo json_encode($data);
}

function getGatheringGradesPeriod($id_assignment, $id_period_calendar) {

    $evaluation = new Evaluations;
    $conn       = $evaluation->dbConn();

    $sql_count = $conn->query("
        SELECT count(*) AS reg_number FROM 
        iteach_grades_quantitatives.`grades_period` AS gp
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = gp.id_final_grade 
        INNER JOIN school_control_ykt.students AS studs ON studs.id_student = fga.id_student 
        WHERE fga.id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar' AND studs.status = 1 AND gp.grade_period IS NOT NULL
        ");
    if ($row = $sql_count->fetch(PDO::FETCH_ASSOC)) {
        $reg_number = $row['reg_number'];
    }
    $count_grades_period = $reg_number;

    $sql_sum = $conn->query("
        SELECT SUM(gp.grade_period) AS total_sum FROM 
        iteach_grades_quantitatives.`grades_period` AS gp
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = gp.id_final_grade  
        INNER JOIN school_control_ykt.students AS studs ON studs.id_student = fga.id_student 
        WHERE fga.id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar' AND studs.status = 1
        ");
    if ($row = $sql_sum->fetch(PDO::FETCH_ASSOC)) {
        $total_sum = $row['total_sum'];
    }
    $sum_grades_period = $total_sum;

    if ($sum_grades_period != 0 && $count_grades_period != 0 && $sum_grades_period != '' && $count_grades_period != '') {
        $total = $sum_grades_period / $count_grades_period;
        $average_grades_period = number_format($total, 1, '.', '');
    } else {
        $average_grades_period = 'S/I';
    }



    return $average_grades_period;
}

function getListStudentsByIDgroup($group_id) {
    $evaluation = new Evaluations;
    $conn       = $evaluation->dbConn();
    $results = array();
    $query = $conn->query("
        SELECT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name
        FROM school_control_ykt.students AS student
        INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
        WHERE inscription.id_group = '$group_id' AND student.status = 1
        ORDER BY student.lastname
        ");

    while ($row = $query->fetch(PDO::FETCH_OBJ)) {
        $results[] = $row;
    }

    return $results;
}

function recalculateDynamicModelAverages(){
    $id_assignment = $_POST['id_assignment'];
    $id_period_calendar = $_POST['id_period_calendar'];

    $response = false;

    $evaluations    = new Evaluations;
    if($evaluations->recalculateDynamicModelAverages($id_assignment, $id_period_calendar)){
        $response = true;
    }

    $data = array(
        'response'  => $response
    );

    echo json_encode($data);
}

function recalculateGeneralAverages(){
    $id_assignment = $_POST['id_assignment'];
    $id_period_calendar = $_POST['id_period_calendar'];

    $response = true;

    $evaluations    = new Evaluations;
    $students = $evaluations->GetListStudentsFromAssignment($id_assignment, $id_period_calendar);

    foreach($students AS $student){
        $evaluations->calculateAveragePerPeriod($student->id_final_grade, $id_period_calendar);
    }

    $data = array(
        'response'  => $response
    );

    echo json_encode($data);
}