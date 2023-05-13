<?php foreach ($StudentInfo as $student_info) : ?>
    <h4><?= ucfirst($student_info->student_code) ?> | <?= ucfirst($student_info->name_student) ?></h2>
    <?php endforeach ?>
    <hr>
    <h1>Calificaciones</h1>
    <h2>Todas las calificaciones por periodo</h2>

    <?php foreach ($StudentGroups as $groups) : ?>
        <?php

        $EStudentSubjects = $archives->GetSubjectsStudent($id_student, '1', $groups->id_group);
        $HStudentSubjects = $archives->GetSubjectsStudent($id_student, '2', $groups->id_group);
        ?>
        <hr>
        <br>
        <?php
        $id_group = $StudentGroups[0]->id_group;
        $level_combination = $archives->getLevelCombinationByGroupID($id_group);
        $level_combinationheb = $archives->getLevelCombinationByGroupIDHeb($id_group);
        $id_level_combination = $level_combination[0]->id_level_combination;
        $id_level_combinationheb = $level_combinationheb[0]->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
        $periods_heb = $helpers->getAllPeriodsByLevelCombination($id_level_combinationheb);
        ?>

        <div class="row">
            <div class="col-12">
                <div class="row">

                    <div class="col-md-12">
                        <ul class="nav nav-pills nav-pills-success nav-pills-circle mb-3" id="tabs_3" role="tablist">
                            <!--  <?php foreach ($periods as $period) : ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="<?= $period->id_period_calendar ?>" data-toggle="tab" href="#califGral" role="tab" aria-selected="false">
                                                        <span class="nav-link-icon d-block"><?= $period->no_period ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach ?> -->
                            <li class="nav-item">
                                <a class="nav-link rounded-circle  active" id="99" data-toggle="tab" href="#califGral" role="tab" aria-selected="false">
                                    <span class="nav-link-icon d-block">Total</span>
                                </a>
                            </li>
                        </ul>
                        <div class="card card-plain">
                            <div class="tab-content tab-space">
                                <div class="card card-nav-tabs">
                                    <div class="card-header card-header-warning">
                                        Español
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-pane fade active show" id="califGral">
                                            <div class="table-responsive">
                                                <table class="table align-items-center">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Materia</th>
                                                            <th>Profesor</th>
                                                            <?php foreach ($periods as $period) : ?>
                                                                <th>P. <?= $period->no_period ?></th>
                                                            <?php endforeach ?>
                                                            <th>Promedio Final</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="list">
                                                        <?php
                                                        $arr_pormedios_periodos = [];
                                                        //echo json_encode($promedios_periodo);
                                                        foreach ($periods as $period) {

                                                            $promedios_periodos["id_period_calendar"] = $period->id_period_calendar;
                                                            $materias = array();
                                                            foreach ($EStudentSubjects as $subjects) {
                                                                $averages = array();
                                                                $materia = array(
                                                                    'id_assignment' => $subjects->id_assignment,
                                                                    'averages' => $averages,
                                                                );
                                                                array_push($materias, $materia);
                                                            }
                                                            $promedios_periodos["assignemnts"] = $materias;
                                                            // echo json_encode($materias);
                                                            //$promedios_periodo[$no_period][$period->id_period_calendar] = $materias;
                                                            //echo json_encode($promedios_periodo[$no_period][$period->id_period_calendar]);
                                                            array_push($arr_pormedios_periodos, $promedios_periodos);
                                                        }

                                                        $no_arr_sbj = 0;
                                                        foreach ($EStudentSubjects as $subjects) : ?>
                                                            <?php
                                                            $promedio = 0;
                                                            $valid_subjects = 0;
                                                            ?>
                                                            <tr>
                                                                <td><?= $subjects->name_subject ?></td>
                                                                <td><?= $subjects->teacher_name ?></td>
                                                                <?php foreach ($periods as $period) :


                                                                    $no_period = ($period->no_period) - 1;
                                                                ?>
                                                                    <?php

                                                                    $calification = $archives->getStudentQualificationPeriod($subjects->id_assignment, $id_student, $period->id_period_calendar);
                                                                    if (!empty($calification)) {
                                                                        $calification_stud = $calification[0]->grade_period;
                                                                        $id_grade_period = $calification[0]->id_grade_period;
                                                                        $promedio = $promedio + $calification_stud;
                                                                        $grade_period_calc = $calification[0]->grade_period_calc;

                                                                        $promedios_periodo["id_period_calendar"][$no_period][$subjects->id_assignment] = $calification_stud;
                                                                        array_push($arr_pormedios_periodos[$no_period]["assignemnts"][$no_arr_sbj]["averages"], $calification_stud);
                                                                        $valid_subjects++;
                                                                        //$promedios_periodo[$no_period][$period->id_period_calendar][$subjects->id_assignment] = $calification_stud;

                                                                    } else {
                                                                        $calification_stud = '-';
                                                                        $id_grade_period = '-';
                                                                    }
                                                                    ?>
                                                                    <td><button type="button" onclick="getCriteriaDetails('<?= $id_grade_period ?>', '<?= $id_student ?>','<?= $subjects->id_assignment ?>','<?= $calification_stud ?>','<?= $grade_period_calc ?>')" class="btn btn-outline-secondary" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $calification_stud ?> </button></td>
                                                                <?php endforeach;


                                                                ?>

                                                                <?php
                                                                if ($valid_subjects > 0) {
                                                                    $promedio = $promedio / $valid_subjects;
                                                                    $promedio = round($promedio, 1);
                                                                }
                                                                $no_arr_sbj++;
                                                                ?>
                                                                <td><strong><?= $promedio ?></strong></td>
                                                            </tr>
                                                        <?php endforeach;
                                                        $arr_pormedios_periodos = array($arr_pormedios_periodos);
                                                        $promedios_tabla = array();
                                                        for ($i = 0; $i < count($arr_pormedios_periodos); $i++) {


                                                            for ($j = 0; $j < count($arr_pormedios_periodos[$i]); $j++) {
                                                                //var_dump($arr_pormedios_periodos[$i][$j]);
                                                                $id_period_calendar = $arr_pormedios_periodos[$i][$j]["id_period_calendar"];
                                                                $final_grade_assg = 0;
                                                                $count1 = 0;
                                                                // echo 'id_period_calendar: ' . $id_period_calendar . '<br>';
                                                                for ($p = 0; $p < count($arr_pormedios_periodos[$i][$j]["assignemnts"]); $p++) {
                                                                    for ($q = 0; $q < count($arr_pormedios_periodos[$i][$j]["assignemnts"][$p]["averages"]); $q++) {
                                                                        $averages = $arr_pormedios_periodos[$i][$j]["assignemnts"][$p]["averages"][$q];
                                                                        // echo "averages: " . $averages . '<br>';
                                                                        $final_grade_assg = $final_grade_assg + $averages;
                                                                        if ($averages != '') {
                                                                            $count1++;
                                                                        }
                                                                    }
                                                                }
                                                                if ($count1 > 0) {
                                                                    $final_grade_assg = $final_grade_assg / $count1;
                                                                    $final_grade_assg = round($final_grade_assg, 1);
                                                                } else {
                                                                    $final_grade_assg = '-';
                                                                }
                                                                // echo 'final_grade_assg: ' . $final_grade_assg . '<br>';
                                                                array_push($promedios_tabla, $final_grade_assg);
                                                            }
                                                        }
                                                        //echo json_encode($promedios_tabla);
                                                        //echo json_encode($);


                                                        ?>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" align="center" valign="middle"><strong>PROMEDIO POR PERIODO</strong></td>
                                                            <?php for ($i = 0; $i < count($promedios_tabla); $i++) { ?>
                                                                <td align="left" valign="middle"><strong><?php echo $promedios_tabla[$i] ?></strong></td>
                                                            <?php } ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <?php  ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-nav-tabs">
                                    <div class="card-header card-header-warning">
                                        Hebreo
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-pane fade active show" id="califGral">
                                            <div class="table-responsive">
                                                <table class="table align-items-center">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Materia</th>
                                                            <th>Profesor</th>
                                                            <?php foreach ($periods as $period) : ?>
                                                                <th>P. <?= $period->no_period ?></th>
                                                            <?php endforeach ?>
                                                            <th>Promedio Final</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="list">
                                                        <?php
                                                        $arr_pormedios_periodos = [];
                                                        //echo json_encode($promedios_periodo);
                                                        foreach ($periods_heb as $period) {

                                                            $promedios_periodos["id_period_calendar"] = $period->id_period_calendar;
                                                            $materias = array();
                                                            foreach ($HStudentSubjects as $subjects) {
                                                                $averages = array();
                                                                $materia = array(
                                                                    'id_assignment' => $subjects->id_assignment,
                                                                    'averages' => $averages,
                                                                );
                                                                array_push($materias, $materia);
                                                            }
                                                            $promedios_periodos["assignemnts"] = $materias;
                                                            // echo json_encode($materias);
                                                            //$promedios_periodo[$no_period][$period->id_period_calendar] = $materias;
                                                            //echo json_encode($promedios_periodo[$no_period][$period->id_period_calendar]);
                                                            array_push($arr_pormedios_periodos, $promedios_periodos);
                                                        }

                                                        $no_arr_sbj = 0;
                                                        foreach ($HStudentSubjects as $subjects) : ?>
                                                            <?php
                                                            $promedio = 0;
                                                            $valid_subjects = 0;
                                                            ?>
                                                            <tr>
                                                                <td><?= $subjects->name_subject ?></td>
                                                                <td><?= $subjects->teacher_name ?></td>
                                                                <?php foreach ($periods_heb as $period) :


                                                                    $no_period = ($period->no_period) - 1;
                                                                ?>
                                                                    <?php

                                                                    $calification = $archives->getStudentQualificationPeriod($subjects->id_assignment, $id_student, $period->id_period_calendar);
                                                                    if (!empty($calification)) {
                                                                        $calification_stud = $calification[0]->grade_period;
                                                                        $promedio = $promedio + $calification_stud;
                                                                        $id_grade_period = $calification[0]->id_grade_period;
                                                                        $grade_period_calc = $calification[0]->grade_period_calc;
                                                                        $promedios_periodo["id_period_calendar"][$no_period][$subjects->id_assignment] = $calification_stud;
                                                                        array_push($arr_pormedios_periodos[$no_period]["assignemnts"][$no_arr_sbj]["averages"], $calification_stud);
                                                                        $valid_subjects++;
                                                                        //$promedios_periodo[$no_period][$period->id_period_calendar][$subjects->id_assignment] = $calification_stud;

                                                                    } else {
                                                                        $calification_stud = '-';
                                                                        $id_grade_period = '-';
                                                                    }
                                                                    ?>
                                                                    <td><button type="button" onclick="getCriteriaDetails('<?= $id_grade_period ?>', '<?= $id_student ?>','<?= $subjects->id_assignment ?>','<?= $calification_stud ?>','<?= $grade_period_calc ?>')" class="btn btn-outline-secondary" style="padding-left: 1px !important; padding-right:1px !important;"> <?= $calification_stud ?> </button></td>
                                                                <?php endforeach;
                                                                ?>

                                                                <?php
                                                                if ($valid_subjects > 0) {
                                                                    $promedio = $promedio / $valid_subjects;
                                                                    $promedio = round($promedio, 1);
                                                                }
                                                                $no_arr_sbj++;
                                                                ?>
                                                                <td><strong><?= $promedio ?></strong></td>
                                                            </tr>
                                                        <?php endforeach;
                                                        $arr_pormedios_periodos = array($arr_pormedios_periodos);
                                                        $promedios_tabla = array();
                                                        for ($i = 0; $i < count($arr_pormedios_periodos); $i++) {


                                                            for ($j = 0; $j < count($arr_pormedios_periodos[$i]); $j++) {
                                                                //var_dump($arr_pormedios_periodos[$i][$j]);
                                                                $id_period_calendar = $arr_pormedios_periodos[$i][$j]["id_period_calendar"];
                                                                $final_grade_assg = 0;
                                                                $count1 = 0;
                                                                // echo 'id_period_calendar: ' . $id_period_calendar . '<br>';
                                                                for ($p = 0; $p < count($arr_pormedios_periodos[$i][$j]["assignemnts"]); $p++) {
                                                                    for ($q = 0; $q < count($arr_pormedios_periodos[$i][$j]["assignemnts"][$p]["averages"]); $q++) {
                                                                        $averages = $arr_pormedios_periodos[$i][$j]["assignemnts"][$p]["averages"][$q];
                                                                        // echo "averages: " . $averages . '<br>';
                                                                        $final_grade_assg = $final_grade_assg + $averages;
                                                                        if ($averages != '') {
                                                                            $count1++;
                                                                        }
                                                                    }
                                                                }
                                                                if ($count1 > 0) {
                                                                    $final_grade_assg = $final_grade_assg / $count1;
                                                                    $final_grade_assg = round($final_grade_assg, 1);
                                                                } else {
                                                                    $final_grade_assg = '-';
                                                                }
                                                                // echo 'final_grade_assg: ' . $final_grade_assg . '<br>';
                                                                array_push($promedios_tabla, $final_grade_assg);
                                                            }
                                                        }
                                                        //echo json_encode($promedios_tabla);
                                                        //echo json_encode($);


                                                        ?>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2" align="center" valign="middle"><strong>PROMEDIO POR PERIODO</strong></td>
                                                            <?php for ($i = 0; $i < count($promedios_tabla); $i++) { ?>
                                                                <td align="left" valign="middle"><strong><?php echo $promedios_tabla[$i] ?></strong></td>
                                                            <?php } ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    <?php endforeach ?>