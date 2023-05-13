<?php

/* include_once 'php/models/hekpers.php'; */
class queries
{



    /* TRAER NOMBRE DE MATERIAS POR ID */

    public function subject_name_id($id)
    {
        $sql = "SELECT sj.name_subject FROM school_control_ykt.subjects sj
                INNER JOIN school_control_ykt.assignments am
                    ON am.id_subject = sj.id_subject
                WHERE id_assignment=$id";
        $exect = $this->conn->query($sql);
        foreach ($exect as $row) {
            $name = $row['name_subject'];
        }
        return $name;
    }

    /* LISTAR MATERIAS EN ESPAÑOL */

    public function subject_spanish($id_subject)
    {
        $subjects    = new Subjects;
        $no_teacher = 32;
        //$getSubjects = $subjects->getSubjectsGroupsFromTeacherByAcademicArea($_SESSION['colab'], $id_subject);
        $getSubjects = $subjects->getSubjectsGroupsFromTeacherByAcademicArea($no_teacher, $id_subject);
        //print_r($getSubjects);
        foreach ($getSubjects as $row) : ?>
            <tr>
                <th scope="row">
                    <!-- <div class="avatar rounded-circle mr-3 bg-light">
                            <img alt="Image placeholder" src="../general/img/imgs/logo.png">
                        </div> -->
                    <div class="media align-items-center">

                        <div class="media-body">
                            <span class="name mb-0 text-sm"><?= $row->name_subject ?></span>
                        </div>
                    </div>
                </th>
                <td scope="row">
                    <?= $row->degree ?>
                </td>
                <td scope="row">
                    <?= $row->campus_name ?>
                </td>
                <td scope="row">
                    <?= $row->group_code ?>
                </td>
                <td scope="row">
                    <div class="avatar-group">
                        <a href="evaluaciones.php?id_assignment=<?= $row->id_assignment ?>" class="avatar avatar-sm rounded-circle hover" data-toggle="tooltip" data-original-title="Evaluación">
                            <i class="ni ni-paper-diploma"></i>
                        </a>
                        <a href="#" class="avatar avatar-sm rounded-circle hover" data-toggle="tooltip" data-original-title="Asistencia">
                            <i class="ni ni-check-bold"></i>
                        </a>
                    </div>
                </td>
                <td class="text-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="?evaluation=<?= $row->id_assignment ?>"><i class="ni ni-settings-gear-65"></i> Configuración</a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        endforeach;
    }

    /* LISTAR MATERIAS EN HEBREO */

    public function subject_hebrew($id_subject)
    {
        $subjects    = new Subjects;
        $no_teacher = 32;
        $getSubjects = $subjects->getHebrewSubjectsGroupsFromTeacherByAcademicArea($no_teacher, $id_subject);

        /* $sql   = "SELECT * FROM school_control_ykt.subjects WHERE id_academic_area = 2";
        $exect = $this->conn->query($sql); */

        foreach ($getSubjects as $row) : ?>
            <tr>
                <th scope="row">
                    <!-- <div class="avatar rounded-circle mr-3 bg-light">
                            <img alt="Image placeholder" src="../general/img/imgs/logo.png">
                        </div> -->
                    <div class="media align-items-center">

                        <div class="media-body">
                            <span class="name mb-0 text-sm"><?= $row->name_subject ?></span>
                        </div>
                    </div>
                </th>
                <td scope="row">
                    <?= $row->degree ?>
                </td>
                <td scope="row">
                    <?= $row->campus_name ?>
                </td>
                <td scope="row">
                    <?= $row->group_code ?>
                </td>
                <td scope="row">
                    <div class="avatar-group">
                        <a href="evaluaciones.php?id_assignment=<?= $row->id_assignment ?>" class="avatar avatar-sm rounded-circle hover" data-toggle="tooltip" data-original-title="Evaluación">
                            <i class="ni ni-paper-diploma"></i>
                        </a>
                        <a href="#" class="avatar avatar-sm rounded-circle hover" data-toggle="tooltip" data-original-title="Asistencia">
                            <i class="ni ni-check-bold"></i>
                        </a>
                    </div>
                </td>
                <td class="text-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="?evaluation=<?= $row->id_assignment ?>"><i class="ni ni-settings-gear-65"></i> Configuración</a>
                            <a class="dropdown-item" href="#">Link 2</a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        endforeach;
    }

    /* RECIBE NOMBRE DE EVALUACIÓN PARA MODAL */
    public function getEvaluationName()
    {
        $evaluationName    = new Subjects;
        $getEvaluationName = $evaluationName->getEvaluation();
        $options = '';


        foreach ($getEvaluationName as $row) : ?>
            <option value="<?= $row->id_evaluation_source ?>"><?= $row->evaluation_name ?></option>
        <?php
        endforeach;
    }

    public function getEvaluationTypes()
    {
        $evaluation    = new Subjects;
        $getEvaluationTypes = $evaluation->getEvaluationTypes();
        $options = '';


        foreach ($getEvaluationTypes as $row) : ?>
            <option value="<?= $row->evaluation_type_id ?>"><?= $row->evaluation_type ?> (<?= $row->name_scale ?>)</option>
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
            $getEvaluationScore = $subjects->getEvaluationScore($id_ev);
            foreach ($getEvaluationScore as $sum_eval_Score) {
                $delete_button = "";
                if (($sum_eval_Score->teacher_eval) > 0) {
                    $delete_button = "none";
                }
            }

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
                                            <div style="padding: 5px 0px 20px 0;">
                                                <span class="text-success">●</span>
                                                <small>Subcriterios: <?= $nRows->subcriterios ?></small>
                                            </div>

                                        <?php else : ?>
                                            <br />
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-auto">
                                        <button id="<?= $row->id_evaluation_plan ?>" type="button" style="display: <?= $boton ?>" data-toggle="modal" data-target="#edit_subcriterios" class="btn btn-sm btn-primary btn_editar_sub_criterios">Editar subcriterios</button>
                                        <button id="<?= $row->id_evaluation_plan ?>" data-toggle="tooltip" data-original-title="Editar plan" type="button" class="btn btn-sm btn-info modify_ev_plan"><i class="fas fa-edit"></i></i></button>
                                        <?php if ($_SESSION['grantsITEQ'] >= 8) :; ?>
                                            <button id="<?= $row->id_evaluation_plan ?>" data-toggle="tooltip" data-original-title="Eliminar plan" type="button" style="display:<?= $delete_button ?>" class="btn btn-sm btn-danger delete_ev_plan"><i class="fas fa-trash-alt"></i></button>
                                        <?php endif; ?>
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
    public function SubjectsWithoutEvPlan($academic_area)
    {
        $subjects    = new Subjects;
        $no_teacher = $_SESSION['colab'];
        $results = $subjects->getSubjectsWithoutEvaluationPlan($no_teacher, $academic_area);

        foreach ($results as $row2) : ?>


            <li class="list-group-item">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checks_export_subject_conf" id="<?= $row2->id_assignment ?>">
                    <label class="custom-control-label" for="<?= $row2->id_assignment ?>">Materia: <?= $row2->name_subject ?> | Grupo: <?= $row2->group_code ?></label>
                </div>
            </li>

        <?php
        endforeach;
    }

    public function SubjectsWithoutEvPlanCoordinator($academic_area)
    {
        $subjects    = new Subjects;
        $no_teacher = $_SESSION['colab'];
        $results = $subjects->getSubjectsWithoutEvaluationPlanCoordinators($no_teacher, $academic_area);

        foreach ($results as $row2) : ?>


            <li class="list-group-item">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checks_export_subject_conf" id="<?= $row2->id_assignment ?>">
                    <label class="custom-control-label" for="<?= $row2->id_assignment ?>">Materia: <?= $row2->name_subject ?> | Grupo: <?= $row2->group_code ?></label>
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
            <button class="btn btn-sm btn-info" id="btn_import_plan" style="display:<?= $importar ?>;" data-toggle="modal" data-target="#import_plan">
                <i class="fas fa-file-download"></i>
                Importar
            </button>
            <button class="btn btn-sm btn-info" id="btn_export_plan" style="display:<?= $exportar ?>;" data-toggle="modal" data-target="#export_plan">
                <i class="fas fa-file-upload"></i>
                Exportación interna
            </button>
            <button style="display:none;" class="btn btn-sm btn-danger" id="btn_delete_period_plan" style="display:<?= $exportar ?>;" data-toggle="tooltip" data-original-title="Eliminar toda la configuración de periodo">
                <i class="fas fa-calendar-times"></i>
                Eliminar
            </button>

            <button class="btn btn-sm btn-primary export_planAnotherSubject" data-id-assignment="<?=$_GET['id_assignment']?>" data-id-period="<?=$_GET['id_period']?>" data-id-academic-area="<?=$_GET['ac_ar']?>" style="display:<?= $exportar ?>;" data-toggle="modal" data-target="#export_planAnotherSubject">
                <i class="fas fa-file-upload"></i>
                Exportar a otra materia
            </button>
        <?php
        endforeach;
    }

    public function percentageBar($sj, $id_period)
    {
        $subjects    = new Subjects;
        $getPercentage = $subjects->getPercentage($sj, $id_period);
        $valor = 0;
        foreach ($getPercentage as $row) : $valor = $valor + $row->asignado; ?>
            <div class="progress" style="height: 20px;">

                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $valor ?>%;" aria-valuenow="<?= $valor ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="text-dark">El porcentaje distribuido es: <?= $valor ?>% </p>
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
    /* public function ($id_period)
    {
        $name = "";
        $sql  = "";
        $res  = $this->conn->query($sql);
        foreach ($res as $row) {
            $name .= $row['percentage'];
        }
        echo $name;
    } */
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
