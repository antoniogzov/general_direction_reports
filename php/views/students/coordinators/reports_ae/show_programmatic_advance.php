<?php
/* $catalogue_subindex = $expected_learnings->getSubindexByLevelGrade($no_teacher, $_GET['id_academic_level'], $_GET['id_level_grade'], $_GET['no_period']); */
$catalogue_subindex = $expected_learnings->getSubindexByLevelGradePROGR($no_teacher, $_GET['id_academic_level'], $_GET['id_level_grade'], $_GET['no_period'], $_GET['id_period']);
$level_avg = 0;
$count_level_avg = 0;

$total_ae_registered = 0;
$total_ae_qualified_count = 0;
?>
<div class="card card-table-evaluations">
    <?php if (!empty($catalogue_subindex)) : ?>
        <div class="card-body">
            <h1 id="avg_level"></h1>
            <div class="table-responsive">
                <table class="table align-items-center table-flush" id="tStudents">
                    <thead>
                        <tr class="sticky-header">
                            <th class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">NIVEL</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">GRUPO</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">CÓD. iTEACH</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">PROFESOR</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">MATERIA</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">PROMEDIO GRUPAL <br> DE AE</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">AVANCE DE COBERTURA</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">APRENDIZAJES ESPERADOS</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">A.E. ALCANZADOS</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">MOTIVO POR EL CUAL <BR> NO SE LOGRÓ EL ALCANCE</th>
                            <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">DETALLES A.E.</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php foreach ($catalogue_subindex as $catalogue) :
                            $catalog_subindex = $expected_learnings->getCatalogFromSubindex($catalogue->id_expected_learning_subindex);
                            $getPeriodsGroupAcademic = $expected_learnings->getPeriodsGroupAcademic($_GET['id_academic_area'], $catalogue->id_group, $_GET['no_period']);
                            $no_pp = $_GET['no_period'] - 1;
                            $getPeriodsGroupAcademic = $getPeriodsGroupAcademic[$no_pp];
                            $id_period_cal = $getPeriodsGroupAcademic->id_period_calendar;
                            $getGroupAVG = $expected_learnings->getGroupAVGAE($catalogue->id_assignment, $id_period_cal);
                            $catalog_plan = count($catalog_subindex);
                            $qualified_learnings = 0;
                            $qualified_percent = 0;
                            $group_avg = 0;
                            $color_rgba = "";
                            if ($catalog_plan == 0) {
                                $color_rgba = "rgba(255, 0, 0, 0.5)";
                            } else {
                                $color_rgba = "rgba(255, 255, 255, 1)";
                            }
                            if (!empty($getGroupAVG)) {
                                $getGroupAVG = $getGroupAVG[0];
                                $group_avg = $getGroupAVG->group_average;
                                $group_avg = number_format($group_avg, 1);
                                $level_avg = $level_avg + $group_avg;
                                $count_level_avg++;
                            }
                            if (count($catalog_subindex) > 0) {
                                foreach ($catalog_subindex as $catalog) {
                                    $qualified = $expected_learnings->getCatalogQualifiquied($catalog->id_expected_learning_catalog);
                                    if (!empty($qualified)) {
                                        $qualified_learnings++;
                                    }
                                }
                            }
                            $not_qualifiquied = $catalog_plan - $qualified_learnings;

                            $total_ae_registered = $total_ae_registered + $catalog_plan;
                            if ($qualified_learnings > 0) {
                                $total_ae_qualified_count = $total_ae_qualified_count + $qualified_learnings;
                                $qualified_percent = number_format((($qualified_learnings * 100) / $catalog_plan), 0);
                            }
                            if ($qualified_percent == 0) {
                                $color_rgba = "rgba(255, 0, 0, 0.5)";
                            } else if ($qualified_percent == 100) {
                                $color_rgba = "rgba(92, 255, 108, 0.5)";
                            } else {
                                $color_rgba = "rgba(255, 255, 255, 1)";
                            }

                        ?>
                            <tr style="background-color: <?= $color_rgba ?>;">
                                <td class="text-center">
                                    <h4><?= mb_strtoupper($catalogue->level_grade_write) ?></h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $catalogue->letter ?></h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $catalogue->group_code ?></h4>
                                </td>

                                <td class="text-center">
                                    <h4><?= $catalogue->teacher_assignment ?></h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $catalogue->name_subject ?></h4>
                                </td>

                                <td class="text-center">
                                    <h4><?= $group_avg ?></h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $qualified_percent ?> %</h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $catalog_plan ?></h4>
                                </td>
                                <td class="text-center">
                                    <h4><?= $qualified_learnings ?></h4>
                                </td>
                                <?php if ($not_qualifiquied > 0) : ?>
                                    <td class="text-center">
                                        <button type="button" data-id="<?= $catalogue->id_expected_learning_subindex ?>" id="btnShow<?= $catalogue->id_expected_learning_subindex ?>" class="btn btn-secondary btnShowCommentary"><i id="iconSubind<?= $catalogue->id_expected_learning_subindex ?>" class="fa-solid fa-eye"></i></button>
                                        <div style="display:none; white-space: pre-wrap;" id="div<?= $catalogue->id_expected_learning_subindex ?>">
                                            <?php $getNotQualifiquiedComment = $expected_learnings->getNotQualifiquiedComment($catalogue->id_assignment, $_GET['id_period']);
                                            $commentary = "";
                                            if (!empty($getNotQualifiquiedComment)) {
                                                $getNotQualifiquiedComment = $getNotQualifiquiedComment[0];
                                                $commentary = $getNotQualifiquiedComment->comment;
                                            }
                                            ?>
                                            <p class="text-center"><?= $commentary ?></p>
                                        </div>
                                    </td>
                                <?php else : ?>
                                    <td class="text-center">
                                        <h4>N/A</h4>
                                    </td>
                                <?php endif ?>
                                <td class="text-center">
                                    <button type="button" data-group="<?= $catalogue->group_code ?>" data-subject="<?= $catalogue->name_subject ?>" data-id="<?= $catalogue->id_expected_learning_subindex ?>" class="btn btn-secondary btnShowCatalogue"><i class="fa-solid fa-list-check"></i></button>
                                </td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br>
            <br>
            <script>
                var group_avg = <?= $level_avg ?>;
                var count_level_avg = <?= $count_level_avg ?>;
                var level_avg = group_avg / count_level_avg;
                level_avg = level_avg.toFixed(1);

                var level_name = $('#slct_grade option:selected').text();

                var total_ae_registered = <?= $total_ae_registered ?>;
                var total_ae_qualified_count = <?= $total_ae_qualified_count ?>;
                var total_ae_qualified_percent = (total_ae_qualified_count * 100) / total_ae_registered;

                if (isNaN(level_avg)) {
                    level_avg = 0;
                }
                if (isNaN(total_ae_qualified_percent)) {
                    total_ae_qualified_percent = 0;
                }


                $('#avg_level').text(level_name + " | PROMEDIO: " + level_avg + " | AVANCE POR SECCIÓN: " + total_ae_qualified_percent.toFixed(0) + " %");

                var name_report = $('#slct_grade option:selected').text();
                name_report = "AVANCE PROGRAMÁTICO " + name_report;
                var tf = new TableFilter(document.querySelector("#tStudents"), {
                    base_path: "../general/js/vendor/tablefilter/tablefilter/",
                    col_0: "select",
                    col_1: "select",
                    col_2: "select",
                    col_3: "select",
                    col_4: "select",
                    col_5: "none",
                    col_6: "none",
                    col_7: "none",
                    col_8: "none",
                    col_9: "none",
                    col_10: "none",
                    paging: {
                        results_per_page: ['Records: ', [10, 25, 50, 100]]
                    },
                    rows_counter: true,
                    btn_reset: true,
                });
                tf.init();

                $('#tStudents').DataTable({
                    dom: 'Bfrtip',
                    "ordering": false,
                    "pageLength": 25,
                    buttons: [{
                            extend: 'excelHtml5',
                            title: name_report,
                        },
                        {
                            extend: 'pdfHtml5',
                            title: name_report,
                        }
                    ],
                    "paging": false
                });
            </script>
        </div>
    <?php else : ?>
        <div class="card-body">
            <div class="table-responsive">
                <h1 class="">No hay aprendizajes esperados para este nivel académico</h1>
            </div>
        </div>
    <?php endif; ?>
</div>