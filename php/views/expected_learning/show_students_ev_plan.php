<!--<div class="card">
    <div class="card-body">
      <a class="btn btn-primary" href="promedios_generales.php?id_assignment=<?=$id_assignment?>" role="button">Ver promedios generales</a>
    </div>
</div>-->
<div class="card card-table-evaluations">
    <div class="card-body">
        <div class="table-responsive">
            <input type="hidden" id="input_editable" value="<?=$editable?>">
            <input type="hidden" id="grade_closing_date" value="<?=$date_grade_closing_date?>">
            <table class="table align-items-center table-flush" id="tStudents">
                <thead class="thead-light">
                    <tr>
                        <th>Cód. alumno</th>
                        <th>Nombre</th>
                        <?php if ($extraExamEnabled): ?>
                        <th class="text-center font-weight-bold"><b>Extraordinario</b></th>
                        <?php endif;?>
                        <th class="text-center font-weight-bold" style="color: black;"><b>Promedio por periodo</b></th>
                        <?php foreach ($evaluation_criteria as $criteria): ?>
                            <th scope="col" class="text-center" data-toggle="tooltip" data-placement="top" title="Sólo admite caracteres: <?=$criteria->evaluation_scale?>"><?= $criteria->manual_name == ''  ? $criteria->evaluation_name : $criteria->manual_name; ?><br/><small>%<?=$criteria->percentage?></small></th>
                        <?php endforeach;?>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td style="width: 10px"><?=strtoupper($student->student_code)?></td>
                            <td><?=ucfirst($student->name_student)?></td>
                            <?php if ($extraExamEnabled): ?>
                                <td <?php if($editable==1){echo 'contenteditable="true"';}else{echo '';} ?> id="<?= $evaluations->getGradePeriod($student->id_grade_period)->id_extraordinary_exams; ?>" class="text-center td-grade-extra" onkeyup="evaluate_character('rank', '1-10', this, event)"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_extraordinary_examen; ?></td>
                            <?php endif;?>
                            <td class="td-grade-period text-center font-weight-bold" style="color: black;"><?= $evaluations->getGradePeriod($student->id_grade_period)->grade_period; ?></td>
                            <?php foreach ($evaluation_criteria as $criteria_conf): ?>
                                <?php $criteria_found = false;?>
                                <?php foreach ($student->evaluation_criteria_student as $criteria_student): ?>
                                    <?php if ($criteria_conf->id_evaluation_plan == $criteria_student->id_evaluation_plan): ?>
                                        <?php $criteria_found = true;?>

                                        <?php if ($criteria_conf->gathering): ?>
                                            <td id="<?=$criteria_student->id_grades_evaluation_criteria?>" class="text-center td-grade-evaluation td-gathering" style="background-color: #FFF2EF" onClick="clk_td_gathering(this, <?=$criteria_student->id_grades_evaluation_criteria?>, <?=$criteria_student->is_averaged?>, '<?=$criteria_student->classification?>', '<?=$criteria_student->evaluation_scale?>', '<?=$editable?>')">

                                                <?=$criteria_student->is_averaged == '1' ? $criteria_student->grade_evaluation_criteria_teacher : 'L';?>

                                            </td>

                                        <?php else: ?>

                                            <?php if ($criteria_conf->group_id == '1' || $criteria_conf->group_id == 1): ?>
                                                <td  <?php if($editable==1){echo 'contenteditable="true"';}else{echo '';} ?> class="text-center td-grade-evaluation" id="<?=$criteria_student->id_grades_evaluation_criteria?>" onkeyup="evaluate_character('<?=$criteria_student->classification?>', '<?=$criteria_student->evaluation_scale?>', this, event)" data-is-averaged="<?=$criteria_student->is_averaged?>" ><?=$criteria_student->grade_evaluation_criteria_teacher?></td>
                                            <?php elseif ($criteria_conf->group_id == '2' || $criteria_conf->group_id == 2): ?>
                                                <?php $arr_opt_scale = explode(",", $criteria_conf->evaluation_scale); ?>
                                                <td class="text-center">
                                                    <select id="<?=$criteria_student->id_grades_evaluation_criteria?>" class="slct-opt-scale" data-is-averaged="<?=$criteria_student->is_averaged?>" <?=$select_prop?>>
                                                        <option></option>
                                                        <?php foreach ($arr_opt_scale as $value): ?>
                                                            <?php if ($criteria_student->grade_evaluation_criteria_teacher == $value): ?>
                                                                <option value="<?=$value; ?>" selected>
                                                                    <?=$value; ?>
                                                                </option>
                                                            <?php else: ?>
                                                                <option value="<?=$value; ?>">
                                                                    <?=$value; ?>
                                                                </option>
                                                            <?php endif;?>
                                                        <?php endforeach;?>
                                                    </select>
                                                </td>
                                            <?php endif;?>
                                        <?php endif;?>

                                    <?php endif;?>
                                <?php endforeach;?>
                                <?php if (!$criteria_found): ?>
                                    <td class="text-center"></td>
                                <?php endif;?>
                            <?php endforeach;?>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card card-evaluations-gathering">
</div>

<?php include 'modal_criterios.php';?>