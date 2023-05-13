<!--<div class="card">
    <div class="card-body">
      <a class="btn btn-primary" href="promedios_generales.php?id_assignment=<?= $id_assignment ?>" role="button">Ver promedios generales</a>
    </div>
</div>-->
<div class="card card-table-evaluations">
    <div class="card-body">
        <div>
            <?php if ($dynamicCalc) : ?>
                <button type="button" data-toggle="tooltip" data-placement="bottom" data-link-img="<?= $dynamicCalc->operation_model_img ?>" title="Modelo de cálculo" class="btn btn-primary btn-show-mi mb-3"><i class="fas fa-image"></i></button>
            <?php endif; ?>
            <?php if ($editable == 1) : ?>
                <?php if ($dynamicCalc) : ?>
                    <button type="button" data-toggle="tooltip" data-placement="bottom" title="Recalcular promedios modelo dinámico" class="btn btn-warning btn-re-calculate-dm-averages mb-3" onclick="recalculateAverageModelDynamic(<?= $id_assignment ?>, <?= $id_period_calendar ?>)"><i class="fas fa-sync"></i></button>
                <?php endif; ?>
                <button type="button" data-toggle="tooltip" data-placement="bottom" title="Recalcular promedios" class="btn btn-success mb-3" onclick="recalculateAverages(<?= $id_assignment ?>, <?= $id_period_calendar ?>)"><i class="fas fa-calculator"></i></button>
            <?php endif; ?>
            
            <div class="sticky-table sticky-ltr-cells">
                <input type="hidden" id="input_editable" value="<?= $editable ?>">
                <input type="hidden" id="grade_closing_date" value="<?= $date_grade_closing_date ?>">
                <a href="save_evaluation.php?id_assignment=<?= $_GET['id_assignment'] ?>&id_period_calendar=<?= $_GET['id_period_calendar'] ?>" target="_blank" class="btn btn-primary" id="btn_ExportData">Descargar</a>

                <br>
                <br>
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead class="thead-light">
                        <tr class="sticky-header">
                            <th class="sticky-cell p-1 td-hd-600" style="width: 10px !important; font-size: x-small !important;">Cód.</th>
                            <th class="sticky-cell text-center p-1" style="font-size: x-small !important;">Nombre</th>
                            <th class="text-center font-weight-bold" style="color: black; background-color: #EFEEEE;"><b>Promedio por periodo</b></th>
                            <?php if ($addColDynCalc) : ?>
                                <th class="text-center font-weight-bold"><b>Promedio dinámico</b></th>
                            <?php endif; ?>
                            <?php if ($extraExamEnabled) : ?>
                                <th class="text-center font-weight-bold"><b>Extraordinario</b></th>
                            <?php endif; ?>
                            <?php foreach ($evaluation_criteria as $criteria) : ?>
                                <th scope="col" class="text-center" data-toggle="tooltip" data-placement="top" title="Sólo admite caracteres: <?= $criteria->evaluation_scale ?>"><?= $criteria->manual_name == ''  ? $criteria->evaluation_name : $criteria->manual_name; ?><br /><small>%<?= $criteria->percentage ?></small></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php foreach ($students as $student) :
                            $css_tdGP = "";
                            $checkIfExistCommentary = $evaluations->checkIfExistCommentary($student->id_grade_period);
                            if (!empty($checkIfExistCommentary)) {
                                $checkedCommentary = $checkIfExistCommentary->checked;
                                if ($checkedCommentary == 1) {
                                    $css_tdGP = "border: 2px solid #0330fc;";
                                } else {
                                    $css_tdGP = "border: 2px solid red;";
                                }
                            }
                        ?>
                            <tr id="tr-<?= $student->id_student ?>">
                                <td style="width: 10px !important; font-size: x-small !important;" class="sticky-cell p-1 td-hd-600">
                                    <button class="btn btnGetStudentQualifications" data-student-code="<?= $student->student_code ?>" data-name-student="<?= $student->name_student ?>" data-id-student="<?= $student->id_student ?>" data-id-assignment="<?= $_GET['id_assignment'] ?>" data-id-period-calendar="<?= $_GET['id_period_calendar'] ?>">
                                        <?= mb_strtoupper($student->student_code) ?>
                                    </button>
                                </td>
                                <td class="sticky-cell p-1" style="font-size: x-small !important;"><?= mb_strtoupper($student->name_student) ?></td>
                                <td class="td-grade-period text-center font-weight-bold" style="color: black; background-color: #EFEEEE; <?= $css_tdGP ?>"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period; ?></td>
                                <?php if ($addColDynCalc) : ?>
                                    <td class="text-center td-grade-dynCalc"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period_calc; ?></td>
                                <?php endif; ?>
                                <?php if ($extraExamEnabled) : ?>
                                    <td <?= $editable == 1 ? 'contenteditable = "true"' : ''; ?> id="<?= $evaluations->getGradePeriod($student->id_grade_period)->id_extraordinary_exams; ?>" class="text-center td-grade-extra" onkeyup="evaluate_character('rank', '1-10', this, event)"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_extraordinary_examen; ?></td>
                                <?php endif; ?>
                                <?php foreach ($evaluation_criteria as $criteria_conf) : ?>
                                    <?php $criteria_found = false; ?>
                                    <?php foreach ($student->evaluation_criteria_student as $criteria_student) : ?>
                                        <?php if ($criteria_conf->id_evaluation_plan == $criteria_student->id_evaluation_plan) : ?>
                                            <?php $criteria_found = true; ?>

                                            <?php if ($criteria_conf->gathering) : ?>
                                                <td id="<?= $criteria_student->id_grades_evaluation_criteria ?>" class="text-center td-grade-evaluation td-gathering td-gathering-ev-<?= $criteria_conf->id_evaluation_plan ?>" style="background-color: #FFF2EF" onClick="clk_td_gathering(this, <?= $criteria_conf->id_evaluation_plan ?>, <?= $criteria_student->is_averaged ?>, '<?= $criteria_student->classification ?>', '<?= $criteria_student->evaluation_scale ?>', '<?= $editable ?>')">

                                                    <?= $criteria_student->is_averaged == '1' ? $criteria_student->grade_evaluation_criteria_teacher : 'L'; ?>

                                                </td>

                                            <?php else : ?>

                                                <?php if ($criteria_conf->group_id == '1' || $criteria_conf->group_id == 1) : ?>
                                                    <td <?= $editable == 1 && $criteria_conf->id_evaluation_source != 34 && $criteria_conf->id_evaluation_source != 38 && $criteria_conf->id_evaluation_source != 54 ? 'contenteditable = "true"' : ''; ?> data-ides="<?= $criteria_conf->id_evaluation_source ?>" class="text-center td-grade-evaluation" id="<?= $criteria_student->id_grades_evaluation_criteria ?>" onkeyup="evaluate_character('<?= $criteria_student->classification ?>', '<?= $criteria_student->evaluation_scale ?>', this, event)" data-is-averaged="<?= $criteria_student->is_averaged ?>"><?= $criteria_student->grade_evaluation_criteria_teacher ?></td>
                                                <?php elseif ($criteria_conf->group_id == '2' || $criteria_conf->group_id == 2) : ?>
                                                    <?php $arr_opt_scale = explode(",", $criteria_conf->evaluation_scale); ?>
                                                    <td class="text-center">
                                                        <select id="<?= $criteria_student->id_grades_evaluation_criteria ?>" class="slct-opt-scale" data-is-averaged="<?= $criteria_student->is_averaged ?>" <?= $select_prop ?>>
                                                            <option></option>
                                                            <?php foreach ($arr_opt_scale as $value) : ?>
                                                                <?php if ($criteria_student->grade_evaluation_criteria_teacher == $value) : ?>
                                                                    <option value="<?= $value; ?>" selected>
                                                                        <?= $value; ?>
                                                                    </option>
                                                                <?php else : ?>
                                                                    <option value="<?= $value; ?>">
                                                                        <?= $value; ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (!$criteria_found) : ?>
                                        <td class="text-center"></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="card card-evaluations-gathering">
</div>

<?php include 'modal_criterios.php'; ?>