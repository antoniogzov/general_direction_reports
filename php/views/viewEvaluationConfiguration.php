<?php

/* include_once 'php/models/hekpers.php'; */
class evaluationConfig
{




    public function getEvaluationCriteria()
    {
        $evCriteria    = new ConfigurationController;
        $queries = new queries;
        $id_criterio1 = 1;
        $dataEvCriteria = $evCriteria->getEvaluationConfig($id_criterio1);


        foreach ($dataEvCriteria as $row) : ?>
            <div class="form-group">
                <label for="evaluation" class="form-label text-dark">Id de plan de evaluación</label>
                <div class="input-group">
                    <input type="text" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->id_evaluation_plan ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="evaluation" class="form-label text-dark">Nombre</label>
                <h5 class="form-label text-grey"><em>Usted selecconó: <?= $row->evaluation_name ?></em></h5>
                <div class="input-group">
                    <div class="input-group">
                        <select class="form-control" id="name_edit_criteria" name="evaluation" required="required">
                            <option value="">Seleccione un nombre de criterio</option>
                            <?= $queries->getEvaluationName() ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" id="div_manual_name_edit">
                <label class="form-label text-dark">Nombre Manual</label>
                <h5 class="form-label text-grey"><em>Usted ingresó: <?= $row->manual_name ?></em></h5>
                <div class="input-group">
                    <input type="text" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->manual_name ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="evaluation" class="form-label text-dark">Tipo de evaluación </label>
                <div class="input-group">
                    <select class="form-control" id="select_eval_name" name="evaluation" required="required">
                        <option value="" selected>Numerica (1-10)</option>
                    </select>
                    <!-- <input type="text" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->value_input_type ?>"> -->
                </div>
            </div>

            <div class="form-group">
                <label for="evaluation" class="form-label text-dark">Porcentaje</label>
                <h5 class="form-label text-grey"><em>Usted ingresó: <?= $row->percentage ?>%</em></h5>
                <div class="input-group">
                    <input type="number" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->percentage ?>">
                </div>
            </div>

            <!-- <div class="form-group">
                <label for="evaluation" class="form-label text-dark">gathering</label>
                <div class="input-group">
                    <input type="text" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->gathering ?>">
                </div>
            </div> -->
            <?php $affect = "";
            if ($row->affects_evaluation == 1) {
                $affect = "checked";
            }
            $arr_datetime = explode(" ", $row->deadline);
            $arr_f = explode("-", $arr_datetime[0]);
            $fecha_fin = $arr_f[2] . "/" . $arr_f[1] . "/" . $arr_f[0];

            ?>
            <div class="form-group">
                <label for="" id="" class="form-label text-dark">¿Tomar en cuenta para la calificación final?</label>

                <ul class="list-group list-group-horizontal list-group-flush">
                    <li class="list-group-item">
                        <label class="custom-toggle">
                            <input type="checkbox" <?= $affect ?> id="check_afectar_calificacion">
                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                        </label>
                    </li>
                    <li class="list-group-item"></li>
                    <li class="list-group-item"></li>
                </ul>
                <div class="input-group">
                </div>
            </div>



            <div class="form-group">
                <label for="evaluation" class="form-label text-dark">Fecha de cierre</label>
                <h5 class="form-label text-grey"><em>Usted seleccionó: <?= $fecha_fin ?></em></h5>
                <div class="input-group">
                    <input type="date" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required>
                </div>
            </div>

        <?php
        endforeach;
    }


    public function getPeriods()
    {

        $id_assignment = '149';
        $sql     = "SELECT DISTINCT pc.no_period FROM iteach_grades_quantitatives.period_calendar AS pc 
        INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_level_combination = pc.id_level_combination 
        INNER JOIN school_control_ykt.assignments AS asgm 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = asgm.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade 
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = aclg.id_academic_level 
        WHERE asgm.id_assignment = '$id_assignment' AND lvc.id_academic_level = acl.id_academic_level";
        $getPeriods   = $this->conn->query($sql);
        $periods = '';

        foreach ($getPeriods as $row) {
            $periods .= "<option value=" . $row['no_period'] . ">" . $row['no_period'] . "</option>";
        }
        return $periods;
    }

    public function getPlan($sj, $id_period)
    {
        $subjects    = new Subjects;
        $id_assignment = $sj;
        $periodC = $id_period;

        $getSubjects = $subjects->getPlans($periodC, $id_assignment);

        foreach ($getSubjects as $row) : if (($row->id_evaluation_source) == 1) {
                $ev_name = $row->manual_name;
            } else {
                $ev_name = $row->evaluation_name;
            }
            $id_ev = $row->id_evaluation_plan;
            $getSubCr = $subjects->getSubcriter($id_ev);
            foreach ($getSubCr as $nRows) {
                $boton = "";
                if (($nRows->subcriterios) < 1) {
                    $boton = "none";
                }
            } ?>
            <!-- Members list group card -->

            <!-- Card body -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <!-- List group -->
                        <ul class="list-group list-group-flush list my--3">
                            <li class="list-group-item px-0">
                            </li>
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <!-- <a href="#" class="avatar rounded-circle">
                              <img alt="Image placeholder" src="../../assets/img/theme/team-4.jpg">
                            </a> -->
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">
                                            <a href="#!"><?= $ev_name ?></a>
                                        </h4>
                                        <h5 class="mb-0">
                                            <a href="#!"><?= $row->percentage ?>%</a>
                                        </h5>
                                        <?php if (intval($nRows->subcriterios) > 0) : ?>
                                            <span class="text-success">●</span>
                                            <small>Subcriterios: <?= $nRows->subcriterios ?></small>
                                        <?php else : ?>
                                            <br />
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-auto">
                                        <button id="<?= $row->id_evaluation_plan ?>" type="button" style="display: <?= $boton ?>" class="btn btn-sm btn-primary btn_editar_sub_criterios">Editar subcriterios</button>
                                        <button id="<?= $row->id_evaluation_plan ?>" data-toggle="tooltip" data-original-title="Editar plan" type="button" class="btn btn-sm btn-info modify_ev_plan"><i class="fas fa-edit"></i></i></button>
                                        <button id="<?= $row->id_evaluation_plan ?>" data-toggle="tooltip" data-original-title="Eliminar plan" type="button" class="btn btn-sm btn-danger delete_ev_plan"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- <div class="card-body">
        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
            <div class="timeline-block">
                <span class="timeline-step badge-danger">
                    <i class="fas fa-bookmark"></i>
                </span>
                <div class="timeline-content">
                    <span class="badge badge-pill badge-danger"></span>
                    <span class="badge badge-pill badge-danger"></span>
                    <span class="badge badge-pill badge-danger"></span>
                    <span class="badge badge-pill badge-danger"></span>
                    
                    <button class="badge badge-pill btn-danger btn btn-sm float-right delete_ev_plan" data-toggle="modal"  data-target="#delete"><i class="far fa-minus-square"></i> Eliminar</button>
                   
                
               
            </div>
        </div>
        </div>
        </div> -->

        <?php
        endforeach;
    }
    /* RECIBE LOS PERIODOS */

    public function periods($id_level_combination)
    {
        $subjects    = new Subjects;
        $getPeriods = $subjects->getPeriods($id_level_combination);

        foreach ($getPeriods as $row) : ?>
            <option value="<?= $row->id_period_calendar ?>">Periodo <?= $row->no_period ?></option>
        <?php
        endforeach;
    }
    public function periodsWithEvPlan($id_level_combination, $sj)
    {
        $subjects    = new Subjects;
        $getPeriods = $subjects->getPeriodsWithEvaluationPlan($id_level_combination, $sj);

        foreach ($getPeriods as $row) : ?>
            <option value="<?= $row->id_period_calendar ?>">Periodo <?= $row->no_period ?></option>
        <?php
        endforeach;
    }

    public function periodsWithoutEvPlan($id_period, $sj)
    {
        $subjects    = new Subjects;
        $results = $subjects->getPeriodsWithoutEvaluationPlan($id_period, $sj);

        foreach ($results as $row2) : ?>


            <li class="list-group-item">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checks_periodos" id="<?= $row2->id_period_calendar ?>">
                    <label class="custom-control-label" for="<?= $row2->id_period_calendar ?>">Periodo <?= $row2->no_period ?></label>
                </div>
            </li>

        <?php
        endforeach;
    }

    public function visibilityImportButton($sj, $id_period)
    {
        $subjects    = new Subjects;
        $getPeriods = $subjects->getButtonImport($sj, $id_period);

        foreach ($getPeriods as $nRows) : $importar = "";
            $exportar = "none";
            if (($nRows->config) > 0) {
                $importar = "none";
                $exportar = "";
            } ?>
            <button class="btn btn-sm btn-neutral" id="btn_import_plan" style="display:<?= $importar ?>;" data-toggle="modal" data-target="#import_plan">
                <i class="fas fa-file-download"></i>
                Importar
            </button>
            <button class="btn btn-sm btn-neutral" id="btn_export_plan" style="display:<?= $exportar ?>;" data-toggle="modal" data-target="#export_plan">
                <i class="fas fa-file-upload"></i>
                Exportar
            </button>
<?php
        endforeach;
    }
    public function getGroupCode($id_assignment)
    {
        $subjects    = new Subjects;
        $getSubject = $subjects->getGroupByIDAssignment($id_assignment);

        return $getSubject->group_code;
    }
    // VALIDAR PORCENTAJE DE FORMULARIOS
    public function percentageValidate($num)
    {
        $cont = 0;
        $sql  = $this->conn->query("SELECT percentage FROM iteach_grades_quantitatives.evaluation_plan WHERE id_assignment=1 AND id_period_calendar = $num");
        foreach ($sql as $row) {
            $cont = $cont + $row['percentage'];
        }
        return $cont;
    }

    public function lessValidate($num)
    {
        $less = 100 - $num;
        return $less;
    }

    // QUERIES UPDATE

    public function evName($num)
    {
        $name = "";
        $sql  = "SELECT es.evaluation_name FROM iteach_grades_quantitatives.evaluation_source es
                INNER JOIN iteach_grades_quantitatives.evaluation_plan ep ON es.id_evaluation_source = ep.id_evaluation_source
                WHERE ep.id_evaluation_plan = $num";
        $res = $this->conn->query($sql);
        foreach ($res as $row) {
            $name .= $row['evaluation_name'];
        }
        return $name;
    }
    public function evPercentage($num)
    {
        $name = "";
        $sql  = "SELECT percentage FROM iteach_grades_quantitatives.evaluation_plan WHERE id_evaluation_plan = $num";
        $res  = $this->conn->query($sql);
        foreach ($res as $row) {
            $name .= $row['percentage'];
        }
        return $name;
    }
    public function evInput($num)
    {
        $sql = "SELECT value_input_type FROM iteach_grades_quantitatives.evaluation_plan WHERE id_evaluation_plan = $num";
        $res = $this->conn->query($sql);
        foreach ($res as $row) {
            $value = $row['value_input_type'];
        }
        if ($value == 0) {
            $name = 'Manual';
        } else {
            $name = 'Subcriterio';
        }
        return $name;
    }
    /* PLANES ACTUALES */
    public function tablePlans($num)
    {
        $item = "";
        $sql  = "SELECT es.evaluation_name, ep.percentage, if(ep.value_input_type = 0, 'Manual', 'Subcriterio') as criterio
                FROM iteach_grades_quantitatives.evaluation_plan ep
                INNER JOIN iteach_grades_quantitatives.evaluation_source es
                ON ep.id_evaluation_source = es.id_evaluation_source
                WHERE ep.id_assignment = $num";
        $res = $this->conn->query($sql);
        if ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $item = $row;
        }
        return $item;
    }
}
