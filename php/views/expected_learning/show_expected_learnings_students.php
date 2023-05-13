<?php
$today_date = date('Y-m-d');
$non_qualified = 0;
?>
<input type="hidden" id="id_assignment" value="<?= $_GET['id_assignment'] ?>">
<div class="card card-table-evaluations">
    <div class="card-body">
        <div>
            <div class="sticky-table sticky-ltr-cells">
                <h1>Número de Alumnos: <?= count($listStudents) ?></h1>
                <button data-id-assignment="<?= $_GET['id_assignment'] ?>" data-id-period-calendar="<?= $_GET['id_period_calendar'] ?>" class="btn btn-primary" id="syncAEEVAL" title="Sincronizar calificaciones con plan de evaluación"><i class="fas fa-sync"></i> Sincronizar con P.E.</button>
                <button data-toggle="modal" data-target="#modalAddComentaryAE" class="btn btn-danger" id="btnAddComentaryAE" style="display:none">Agregar comentario</button>
                <button data-toggle="modal" data-target="#modalEditComentaryAE" class="btn btn-primary" id="btnEditComentaryAE" style="display:none">Editar comentario</button>
                <br>
                <br>
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead>

                        <tr>
                            <th class=" text-center p-1 td-hd-600" style="width: 10% !important; font-size: x-small !important; color:#fff !important; background-color: #191d4d !important;">Cód.</th>
                            <th class=" text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">Nombre</th>
                            <th style="width: 10% !important; color:#fff !important; background-color: #191d4d !important;" class="text-center font-weight-bold"><b>Promedio</b></th>
                            <?php foreach ($getExistCatalogAssignments as $catalog) : ?>
                                <?php $getCatalogArchives = $expected_learnings->getCatalogArchives($catalog->id_expected_learning_catalog); ?>
                                <th scope="col" title="<?= $catalog->short_description ?>" class=" text-center p-1" style="font-size: x-small !important; color:#fff !important; background-color: #191d4d !important; width: 5% !important;">

                                    <?php echo $catalog->abbr_lena; ?>
                                    <div class="dropdown" style="z-index: 100; width:50px !important">
                                        <a class="btn btn-sm btn-icon-only" style="color:#fff" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <div class="container">
                                                <div class="row" id="menu_archivos<?= $catalog->id_expected_learning_catalog ?>">
                                                    <?php if (empty($getCatalogArchives)) : ?>
                                                        <div class="col"></div>
                                                        <div class="col"><a class="dropdown-item" id="link_<?= $catalog->id_expected_learning_catalog ?>" target="_blank"><button type="button" title="Adjuntar evidencia" id="btnEvidence_<?= $catalog->id_expected_learning_catalog ?>" data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" class="btn btn-primary btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-upload"></i></span></button></a></div>
                                                        <div class="col"></div>
                                                    <?php else : ?>
                                                        <?php if ($getCatalogArchives[0]->link_type == 2) : ?>
                                                            <div class="col"><a href="../iTeach<?= $getCatalogArchives[0]->url_archive ?>" target="_blank"><button type="button" title="Ver evidencia" data-toggle="modal" data-target="#showCatalogDocument" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" class="btn btn-primary btn-sm btnVerEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-eye"></i></span></button></a></div>
                                                            <div class="col"><a><button type="button" title="Sustituir evidencia" data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" data-toggle="modal" data-target="#addCatalogDocument" class="btn btn-info btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_change_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-exchange-alt"></i></span></button></a></div>
                                                            <div class="col"><a><button type="button" title="Eliminar evidencia" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" class="btn btn-danger btn-sm deleteCatalogEvidence"><span class="btn-inner--icon"><i id="icon_delete_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-trash-alt"></i></span></button></a></div>
                                                            <?php if (($grade_closing_date >= $today_date) || ($allow_editing_grades != 0)) : ?>

                                                            <?php endif; ?>
                                                        <?php else : ?>

                                                            <div class="col"><a id="link_<?= $catalog->id_expected_learning_catalog ?>" href="<?= $getCatalogArchives[0]->url_archive ?>" target="_blank"><button type="button" title="Ver evidencia" data-toggle="modal" data-target="#showCatalogDocument" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" class="btn btn-primary btn-sm btnVerEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-eye"></i></span></button></a></div>
                                                            <div class="col"><a><button type="button" title="Sustituir evidencia" data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" data-toggle="modal" data-target="#addCatalogDocument" class="btn btn-info btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_change_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-exchange-alt"></i></span></button></a></div>
                                                            <div class="col"><a><button type="button" title="Eliminar evidencia" data-id-catalog="<?= $catalog->id_expected_learning_catalog ?>" class="btn btn-danger btn-sm deleteCatalogEvidence"><span class="btn-inner--icon"><i id="icon_delete_<?= $catalog->id_expected_learning_catalog ?>" class="fas fa-trash-alt"></i></span></button></a></div>
                                                            <?php if (($grade_closing_date >= $today_date) || ($allow_editing_grades != 0)) : ?>

                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php foreach ($listStudents as $student) : ?>
                            <tr>
                                <td class=" text-center p-1 td-hd-600"><?= strtoupper($student->student_code) ?></td>
                                <td class=" text-center p-1"><?= ucfirst($student->student_name) ?></td>
                                <td class="td-grade-period text-center font-weight-bold" style="color: black;">
                                    <?php
                                    $student_average = "-";
                                    $get_student_average = $expected_learnings->getStudentAverage($student->id_student, $_GET['id_assignment'], $id_period_calendar);
                                    if (!empty($get_student_average)) {
                                        $student_average =  $get_student_average[0]->student_average;
                                        if ($student_average == "") {
                                            $student_average = "-";
                                        } else {
                                            $student_average = number_format($student_average, 1);
                                        }
                                    }
                                    echo $student_average;
                                    ?>
                                </td>
                                <!-- <td class="td-grade-period text-center font-weight-bold" style="color: black;"></td> -->
                                <?php foreach ($getExistCatalogAssignments as $catalog) : ?>
                                    <?php if (($grade_closing_date >= $today_date) || ($allow_editing_grades != 0)) : ?>
                                        <td style="width: 55px;" class=" text-center p-1 td-grade-evaluation" id="<?= $expected_learnings->getQualificationTeacher($student->id_student, $catalog->id_expected_learning_catalog, $id_period_calendar)[0]->id_expected_learning_deliverables; ?>" contenteditable="true" onkeyup="evaluate_character('rank', '1-10', this, event)">
                                            <?= $expected_learnings->getQualificationTeacher($student->id_student, $catalog->id_expected_learning_catalog, $id_period_calendar)[0]->teacher_evidence_quailification; ?>
                                        </td>
                                    <?php else :
                                        $color_non_qualify =  'background-color: rgba(255, 0, 0, 0.3);';
                                        if ($expected_learnings->getQualificationTeacher($student->id_student, $catalog->id_expected_learning_catalog, $id_period_calendar)[0]->teacher_evidence_quailification != "") {
                                            $color_non_qualify = '';
                                        } else {
                                            $non_qualified++;
                                        }
                                    ?>
                                        <td style="width: 55px; <?= $color_non_qualify ?>" class=" text-center p-1" id="<?= $expected_learnings->getQualificationTeacher($student->id_student, $catalog->id_expected_learning_catalog, $id_period_calendar)[0]->id_expected_learning_deliverables; ?>"">
                                            <?= $expected_learnings->getQualificationTeacher($student->id_student, $catalog->id_expected_learning_catalog, $id_period_calendar)[0]->teacher_evidence_quailification; ?>
                                        </td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class=" text-center p-1 td-hd-600" colspan="2">PROMEDIOS</td>
                                        <td id="group_avg" title="Promedio grupal" style="font-size: x-large !important; color:#fff !important; background-color: #191d4d !important; width: 5% !important;">
                                            <?php
                                            $learning_avg = "-";
                                            $get_group_avg = $expected_learnings->getGroupAverage($id_assignment, $id_period_calendar);
                                            if (!empty($get_group_avg)) {
                                                $group_average =  $get_group_avg[0]->group_average;
                                                if ($group_average == "") {
                                                    $group_average = "-";
                                                } else {
                                                    $group_average = number_format($group_average, 1);
                                                }
                                            }
                                            echo $group_average;
                                            ?>
                                        </td>
                                        <?php foreach ($getExistCatalogAssignments as $catalog) : ?>
                                            <td id="tf_<?= $catalog->id_expected_learning_catalog ?>" title="<?= $catalog->short_description ?>" style="font-size: x-small !important; color:#fff !important; background-color: #191d4d !important; width: 5% !important;">
                                                <?php
                                                $learning_avg = "-";
                                                $get_learning_avg = $expected_learnings->getLearningAverage($catalog->id_expected_learning_catalog);
                                                if (!empty($get_learning_avg)) {
                                                    $learning_avg =  $get_learning_avg[0]->learning_avg;
                                                    if ($learning_avg == "") {
                                                        $learning_avg = "-";
                                                    } else {
                                                        $learning_avg = number_format($learning_avg, 1);
                                                    }
                                                }
                                                echo $learning_avg;
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                            </tr>
                            </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>