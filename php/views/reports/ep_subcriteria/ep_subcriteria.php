<?php

include "card_select_ep_subcriteria.php";


if (isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudentByGroup($id_group);
}

if (isset($_GET['id_assignment'])) {
    $id_assingment = $_GET['id_assignment'];
    if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
        $id_level_combination = $id_level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}

?>

<?php if (!empty($listStudent)) : ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="div_tabla">
                <?php

                //$listStudent = array();
                //$listIncidents = array();
                if (isset($_GET['id_group']) && isset($_GET['id_period'])) {
                    //$listStudent = $attendance->getListStudentByGroup($id_group);
                    $infoGroup = $helpers->getGroupInfo($id_group);
                    $group = $infoGroup[0];
                    $id_period = $_GET['id_period'];

                    if (($grants & 8)) {
                        $Assingments = $groupsReports->getAssignmentsByIDGroupAndPeriod($id_group, $id_academic_area, $_GET['id_period']);
                    } else {
                        $Assingments = $groupsReports->getAssignmentsByIDGroupAndPeriodByTeacher($id_group, $id_academic_area, $_GET['id_period']);
                    }
                    $num_subjects = count($Assingments);
                    $num_students = count($listStudent);
                    $general_prom = 0;
                    $general_sum = 0;

                    include 'view_ep_subcriteria.php';
                }
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>




<script src="js/functions/ep_subcriteria/ep_subcriteria.js"></script>