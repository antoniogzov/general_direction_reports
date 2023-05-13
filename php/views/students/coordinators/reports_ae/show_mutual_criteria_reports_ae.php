<?php
$catalogue_exist = $expected_learnings->getMutualExistCatalogCoordinator($no_teacher, $_GET['id_academic_level']);
?>
<div class="card card-table-evaluations">
    <?php if (!empty($catalogue_exist)) : ?>
        <div class="card-body">
            <?php foreach ($catalogue_exist as $catalogue) : ?>
                <h1><?= mb_strtoupper($catalogue->group_code) ?></h1>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="tStudents">
                        <thead>
                            <tr class="sticky-header">
                                <th class="sticky-cell text-center" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">PROMEDIO</th>
                                <th colspan="100%" class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">MATERIAS</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <tr>
                                <td class="text-center">
                                    <h1 id="avg_group<?= $catalogue->id_group ?>"></h1>
                                </td>
                                <?php
                                $getAssignmentsGroup = $expected_learnings->getAssignmentsGroup($no_teacher, $catalogue_exist[0]->id_group, $_GET['id_academic_area']);
                                $general_average = 0;
                                $general_sum = 0;
                                $general_count = 0;
                                foreach ($getAssignmentsGroup as $assg_groups) :
                                    $id_assignment = $assg_groups->id_assignment;
                                    $getMutualCriteriaAverages = $expected_learnings->getMutualCriteriaAverages($no_teacher, $id_assignment, mb_strtoupper($criteria));
                                    $average_criteria = "-";
                                    if (!empty($getMutualCriteriaAverages)) {
                                        $average_criteria = $getMutualCriteriaAverages[0]->learning_average;
                                        $average_criteria = number_format($average_criteria, 1);
                                        $general_sum = $general_sum + $average_criteria;
                                        $general_count = $general_count + 1;
                                    }

                                ?>
                                    <td><?= $assg_groups->name_subject ?> <br>
                                        <h3><?= $average_criteria ?></h3>
                                    </td>
                                <?php endforeach;
                                if ($general_count > 0) {
                                    $general_average = $general_sum / $general_count;
                                    $general_average = number_format($general_average, 1);
                                } else {
                                    $general_average = "-";
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <script>
                    $(document).ready(function() {
                        Swal.close();
                        $('#avg_group<?= $catalogue->id_group ?>').text("<?= $general_average ?>");
                    });
                </script>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="card-body">
            <div class="table-responsive">
                <h1 class="">No hay aprendizajes esperados para este nivel académico</h1>
            </div>
        </div>
    <?php endif; ?>
</div>