<?php


class ConfigurationController extends data_conn
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }



    public function getEvaluationConfig($id_criterio)
    {

        $results = array();

        $config_select = $this->conn->query("SELECT ep.id_evaluation_plan, ep.evaluation_type_id, es.id_evaluation_source, es.evaluation_name, ep.`manual_name`, ep.`value_input_type`,ep.`percentage`, ep.`gathering`, ep.`affects_evaluation`, ep.`deadline`, COUNT(cfg.id_evaluation_plan) AS gathering_configured
        FROM iteach_grades_quantitatives.evaluation_plan AS ep 
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.`id_evaluation_source` = ep.`id_evaluation_source` 
        LEFT JOIN iteach_grades_quantitatives.conf_grade_gathering AS cfg ON cfg.`id_evaluation_plan` = ep.`id_evaluation_plan`
        WHERE ep.id_evaluation_plan  = $id_criterio");
        if ($config_select) {
            while ($row = $config_select->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
            return $results;
        }
    }
    public function updateEvaluationConfig($id_criterio, $criteria_name, $manual_name, $eval_type, $edit_percentage, $affect_final_calification, $in_deadline, $nmb_gathering, $original_gathering_configured)
    {
        $set_deadline = "";
        if ($in_deadline != "") {
            $set_deadline = ", deadline = '$in_deadline'";
        }

        $results = array();

        $config_select = $this->conn->query("UPDATE iteach_grades_quantitatives.evaluation_plan
        SET id_evaluation_source= '$criteria_name', evaluation_type_id='$eval_type', percentage='$edit_percentage', manual_name = '$manual_name', affects_evaluation = '$affect_final_calification' $set_deadline
        WHERE id_evaluation_plan  = $id_criterio");

        if ($config_select) {

            if ($nmb_gathering != 0 && $original_gathering_configured != 0) {
                if ($nmb_gathering > $original_gathering_configured) {
                    $get_name =  $this->conn->query("SELECT CASE 
                                                        WHEN ep.id_evaluation_source = 1 THEN ep.manual_name
                                                       WHEN ep.id_evaluation_source != 1 THEN es.evaluation_name
                                                        END AS name_item
                                                        FROM iteach_grades_quantitatives.evaluation_plan AS ep
                                                        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
                                                        WHERE ep.id_evaluation_plan = $id_criterio");
                    $name_item = $get_name->fetch(PDO::FETCH_OBJ);
                    $name_item = $name_item->name_item;

                    $cylce = $nmb_gathering - $original_gathering_configured;
                    for ($i = 0; $i < $cylce; $i++) {
                        $i2 = $i + 1;
                        $name_new_gathering = $name_item . " Adcn. " . $i2;
                        $config_select = $this->conn->query("INSERT INTO iteach_grades_quantitatives.conf_grade_gathering (id_evaluation_plan, name_item) VALUES($id_criterio, '$name_new_gathering')");
                    }
                }
            }
            $results[] = array(
                'resultado' => "correcto",
                'mensaje' => "Se actualizó correctamente el criterio de evaluación"
            );
            return $results;
        }
    }
    public function getWeekAttendance($id_group, $id_subject)
    {

        /*   $results = array();

        $config_select = $this->conn->query("UPDATE iteach_grades_quantitatives.evaluation_plan
        SET id_evaluation_source= '$criteria_name', percentage='$edit_percentage', manual_name = '$manual_name', affects_evaluation = '$affect_final_calification', deadline = '$in_deadline'
        WHERE id_evaluation_plan  = $id_criterio");
        if ($config_select) {
            $results[] = array(
                'resultado' => "correcto",
                'mensaje' => "Se actualizó correctamente el criterio de evaluación"
            );
            return $results;
        } */
    }
    public function exportEvaluationConfig($assignment_from, $import_on_assignment)
    {
        set_time_limit(0);
        $results = array();
        $exported_on = array();
        $valores = "";
        $import_on = substr($import_on_assignment, 0, -1);
        $arr_assignments = explode(",", $import_on);
        $arr_lenght = count($arr_assignments);
        $id_academic_level_og = "0";
        $id_section_og = "";

        $query_Ac_level = $this->conn->query("SELECT asgm.id_assignment, acl.academic_level, acl.id_academic_level, gps.id_section 
        FROM       school_control_ykt.assignments as asgm 
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asgm.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade as aclg ON gps.id_level_grade = aclg.id_level_grade 
        INNER JOIN school_control_ykt.academic_levels as acl ON aclg.id_academic_level = acl.id_academic_level 
        WHERE asgm.id_assignment = '$assignment_from'");

        while ($ac_lev = $query_Ac_level->fetch(PDO::FETCH_OBJ)) {
            $id_academic_level_og = $ac_lev->id_academic_level;
            $id_section_og = $ac_lev->id_section;
        }

        for ($i = 0; $i < $arr_lenght; $i++) {
            $id_destino = $arr_assignments[$i];
            $info_exported = array();
            $id_academic_level_dest = "0";
            $id_section_dest = "";

            $query_Ac_level_dest = $this->conn->query("SELECT asgm.id_assignment, acl.academic_level, acl.id_academic_level, gps.id_section 
            FROM       school_control_ykt.assignments as asgm 
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asgm.id_group 
            INNER JOIN school_control_ykt.academic_levels_grade as aclg ON gps.id_level_grade = aclg.id_level_grade 
            INNER JOIN school_control_ykt.academic_levels as acl ON aclg.id_academic_level = acl.id_academic_level 
            WHERE asgm.id_assignment = '$id_destino'");

            while ($ac_lev_dest = $query_Ac_level_dest->fetch(PDO::FETCH_OBJ)) {
                $id_academic_level_dest = $ac_lev_dest->id_academic_level;
                $id_section_dest = $ac_lev_dest->id_section;
            }
            if (($id_academic_level_og == $id_academic_level_dest) && ($id_section_og == $id_section_dest)) {
                $config_select = $this->conn->query("SELECT *
                FROM iteach_grades_quantitatives.evaluation_plan
                WHERE id_assignment  = $assignment_from");


                if ($config_select) {
                    $patch = 0;
                    while ($row = $config_select->fetch(PDO::FETCH_OBJ)) {
                        $group = "";
                        $degree = "";
                        $subject = "";
                        $txt_gender = "";
                        $color = "";

                        $id_period_og = "$row->id_period_calendar";

                        $duplicate_select = $this->conn->query("SELECT COUNT(*)
                        FROM iteach_grades_quantitatives.evaluation_plan
                        WHERE id_assignment  = '$id_destino' AND id_period_calendar = '$id_period_og'");

                        $already_registered = $duplicate_select->fetchColumn();

                        $inserted_info = $this->conn->query("SELECT gps.group_code, gps.id_section, aclg.degree, name_subject, 
                        CASE  WHEN id_section = 1 THEN 'VARONES' WHEN id_section = 2 THEN 'MUJERES' WHEN id_section = 3 THEN 'MIXTO' END AS txt_gender
                        FROM school_control_ykt.assignments AS asgm
                        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asgm.id_group 
                        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asgm.id_subject 
                        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON aclg.id_level_grade = gps.id_level_grade 
                        WHERE asgm.id_assignment = '$id_destino'");

                        if ($row_info_sbj = $inserted_info->fetch(PDO::FETCH_OBJ)) {
                            $group = $row_info_sbj->group_code;
                            $degree = $row_info_sbj->degree;
                            $subject = $row_info_sbj->name_subject;
                            $txt_gender = $row_info_sbj->txt_gender;
                            $color = "success";
                        }

                        if ($already_registered == 0) {
                            $patch = 1;
                        }
                        if ($patch == 1) {
                            $already_registered = 0;
                            if ($already_registered == 0) {
                                $patch = 1;
                                $config_export = $this->conn->query("INSERT INTO iteach_grades_quantitatives.evaluation_plan 
                                (id_evaluation_source,
                                id_period_calendar,
                                evaluation_type_id,
                                formulation_operations_id,
                                id_assignment,
                                value_input_type,
                                percentage,
                                manual_name,
                                gathering,
                                affects_evaluation,
                                deadline,
                                no_teacher_created_evp,
                                date_created_evp)
                                VALUES(
                                '$row->id_evaluation_source',
                                '$row->id_period_calendar',
                                '$row->evaluation_type_id',
                                '$row->formulation_operations_id',
                                '$id_destino',
                                '$row->value_input_type',
                                '$row->percentage',
                                '$row->manual_name',
                                '$row->gathering',
                                '$row->affects_evaluation',
                                '$row->deadline',
                                '$row->no_teacher_created_evp',
                                NOW()
                                )");
                                if ($config_export) {
                                    $text_exported = "Se exportó correctamente";
                                    array_push($info_exported, $group, $degree, $subject, $txt_gender, $text_exported, $color);

                                    $stmt = $this->conn->query("SELECT LAST_INSERT_ID()");
                                    $lastId = $stmt->fetchColumn();
                                    if ($row->gathering > 0) {

                                        $gathering_select = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.conf_grade_gathering WHERE id_evaluation_plan = '$row->id_evaluation_plan'");
                                        if ($gathering_select) {
                                            while ($gathering_row = $gathering_select->fetch(PDO::FETCH_OBJ)) {
                                                $gathering_export = $this->conn->query("INSERT INTO iteach_grades_quantitatives.conf_grade_gathering
                                                VALUES(
                                                null, 
                                                '$lastId',
                                                '$gathering_row->name_item'
                                                )");
                                                if ($gathering_export) {
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $text_exported = "Sin exportar. Ya existía una configuración.";
                            $color = "default";
                            array_push($info_exported, $group, $degree, $subject, $txt_gender, $text_exported, $color);
                        }
                    }
                }
            } else {
                $inserted_info = $this->conn->query("SELECT gps.group_code, gps.id_section, aclg.degree, name_subject, 
                CASE  WHEN id_section = 1 THEN 'VARONES' WHEN id_section = 2 THEN 'MUJERES' WHEN id_section = 3 THEN 'MIXTO' END AS txt_gender
                FROM school_control_ykt.assignments AS asgm
                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asgm.id_group 
                INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asgm.id_subject 
                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON aclg.id_level_grade = gps.id_level_grade 
                WHERE asgm.id_assignment = '$id_destino'");

                if ($row_info_sbj = $inserted_info->fetch(PDO::FETCH_OBJ)) {
                    $group = $row_info_sbj->group_code;
                    $degree = $row_info_sbj->degree;
                    $subject = $row_info_sbj->name_subject;
                    $txt_gender = $row_info_sbj->txt_gender;
                    $color = "danger";

                    $text_exported = "Sin exportar. Las características del grupo no son compatibles para exportar esta configuración.";
                    array_push($info_exported, $group, $degree, $subject, $txt_gender, $text_exported, $color);
                }
            }
            array_push($exported_on, $info_exported);
            $info_exported = [];
        }
        $results[] = array(
            'resultado' => 'correcto',
            'mensaje' => 'Se exportó correctamente la configuración!!',
            'info' => $exported_on
        );
        return $results;
    }

    public function deletePeriodEvaluationConfig($id_assignment, $id_period)
    {
        set_time_limit(0);


        $results = array();


        $count_evaluation_plan = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.`evaluation_plan` 
        WHERE `id_assignment` = '$id_assignment' AND `id_period_calendar` = '$id_period';")->fetchColumn();

        if ($count_evaluation_plan > 0) {
            $get_evaluation_plan = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.`evaluation_plan` 
            WHERE `id_assignment` = '$id_assignment' AND `id_period_calendar` = '$id_period';");


            while ($row_evaluation_plan = $get_evaluation_plan->fetch(PDO::FETCH_OBJ)) {

                $count_grade_ctriteria = $this->conn->query("SELECT 
                CASE
                    WHEN SUM(ec.grade_evaluation_criteria_teacher) is NULL THEN '0'
                END AS teacher_eval
                FROM iteach_grades_quantitatives.evaluation_plan AS ep 
                INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS ec ON ep.`id_evaluation_plan` = ec.`id_evaluation_plan`
                WHERE ep.id_evaluation_plan = $row_evaluation_plan->id_evaluation_plan;")->fetchColumn();


                if ($count_grade_ctriteria == 0) {

                    $delete_grade_gathering = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`grade_gathering` 
                    WHERE `id_evaluation_plan` = '$row_evaluation_plan->id_evaluation_plan'");

                    if ($delete_grade_gathering) {
                    }

                    $count_grade_gathering = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.`conf_grade_gathering` 
                    WHERE `id_evaluation_plan` = '$row_evaluation_plan->id_evaluation_plan'")->fetchColumn();

                    if ($count_grade_gathering > 0) {
                        $get_grade_gathering = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.`conf_grade_gathering` 
                        WHERE `id_evaluation_plan` = '$row_evaluation_plan->id_evaluation_plan'");

                        while ($row_grade_gathering = $get_grade_gathering->fetch(PDO::FETCH_OBJ)) {
                            $delete_grade_gathering = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`conf_grade_gathering` 
                            WHERE `id_evaluation_plan` = '$row_evaluation_plan->id_evaluation_plan'");
                            if ($delete_grade_gathering) {
                                # code...
                            }
                        }
                    }


                    $count_grades_assignment = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.`final_grades_assignment` 
                 WHERE `id_assignment` = '$row_evaluation_plan->id_assignment'")->fetchColumn();

                    if ($count_grades_assignment == 0) {

                        $get_grade_assignment = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.`final_grades_assignment` 
                     WHERE `id_assignment` = '$row_evaluation_plan->id_assignment'");

                        while ($row_grade_assignment = $get_grade_assignment->fetch(PDO::FETCH_OBJ)) {


                            $count_grades_period = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.`grades_period` 
                        WHERE `id_final_grade` = '$row_grade_assignment->id_final_grade'")->fetchColumn();

                            if ($count_grades_period > 0) {

                                $get_grades_period = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.`grades_period` 
                             WHERE `id_final_grade` = '$row_grade_assignment->id_final_grade'");

                                while ($row_grades_period = $get_grades_period->fetch(PDO::FETCH_OBJ)) {

                                    $count_eval_criteria = $this->conn->query("SELECT COUNT(*) FROM iteach_grades_quantitatives.`grades_evaluation_criteria` 
                                    WHERE `id_final_grade` = '$row_grade_assignment->id_final_grade' AND id_evaluation_plan = '$row_evaluation_plan->id_evaluation_plan' AND id_grade_period = '$row_grades_period->id_grade_period'")->fetchColumn();

                                    if ($count_eval_criteria != 0) {

                                        $get_eval_criteria = $this->conn->query("SELECT * FROM iteach_grades_quantitatives.`grades_evaluation_criteria` 
                                     WHERE `id_final_grade` = '$row_grade_assignment->id_final_grade' AND id_evaluation_plan = '$row_evaluation_plan->id_evaluation_plan'  AND id_grade_period = '$row_grades_period->id_grade_period'");

                                        while ($row_eval_criteria = $get_eval_criteria->fetch(PDO::FETCH_OBJ)) {

                                            $delete_eval_criteria = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`grades_evaluation_criteria` 
                                        WHERE id_grades_evaluation_criteria= '$row_eval_criteria->id_grades_evaluation_criteria'");
                                            if ($delete_eval_criteria) {
                                            }
                                        }
                                    }
                                }
                                $delete_grades_period = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`grades_period` 
                                  WHERE `id_final_grade` = '$row_grade_assignment->id_final_grade'");
                                if ($delete_grades_period) {
                                }
                            }
                        }
                        $delete_grade_assignment = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`final_grades_assignment` 
                        WHERE `id_final_grade` = '$row_evaluation_plan->id_assignment'");
                        if ($delete_grade_assignment) {
                        }
                        $delete_eval_plan = $this->conn->query("DELETE FROM iteach_grades_quantitatives.`evaluation_plan` 
                        WHERE `id_assignment` = '$id_assignment' AND `id_period_calendar` = '$id_period'");
                        if ($delete_eval_plan) {
                            # code...
                        }
                    }
                    $error_sql = "no error";
                    $results[] = array(
                        'resultado' => 'correcto',
                        'mensaje' => 'Se eliminó correctamente la configuración!!',
                        'info' => $error_sql
                    );
                } else {
                    $error_sql = $this->conn->errorInfo();
                    $results[] = array(
                        'resultado' => 'error',
                        'mensaje' => 'Ya se asignó calificaciones a la configuración!!',
                        'info' => $error_sql
                    );
                }
            }
        }

        return ($results);
    }
}
