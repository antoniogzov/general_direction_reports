<?php
$getAttendanceIncidents = $attendance->getAttendanceIncidents();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-html5-2.2.3/datatables.min.css" />

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-html5-2.2.3/datatables.min.js"></script>

<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Opciones</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_group">* Elija un tipo de incidencia</label>
                    <form>
                        <?php if (isset($_GET['type_report'])) : ?>

                            <select class="form-control" name="id_group" id="type_incident">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php if (!empty($getAttendanceIncidents)) : ?>
                                    <?php foreach ($getAttendanceIncidents as $incidents) : ?>
                                        <?php if ($type_incident == $incidents->incident_id) : ?>
                                            <option selected id="<?= $incidents->incident_id ?>" value="<?= $incidents->incident_id ?>"><?= $incidents->incident ?></option>
                                        <?php else : ?>
                                            <option id="<?= $incidents->incident_id ?>" value="<?= $incidents->incident_id ?>"><?= $incidents->incident ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php else : ?>
                            <select class="form-control" name="type_incident" id="type_incident">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php if (!empty($getAttendanceIncidents)) : ?>
                                    <?php foreach ($getAttendanceIncidents as $incidents) : ?>
                                        <option id="<?= $incidents->incident_id ?>" value="<?= $incidents->incident_id ?>"><?= $incidents->incident ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- <div class="col-md-3">
            </div> -->
            <div class="col-md-3">
                <label class="form-control-label" for="type_report">* Buscar registros por día</label>
                <div class="input-group mb-3">
                    <?php if (isset($_GET['type_report'])) : ?>
                        <input type="text" class="form-control date-input" id="search_date" placeholder="Buscar por fecha">
                        <div class="input-group-append">
                            <?php if (($_GET['type_report']) == 1) : ?>
                                <button class="btn btn-outline-info search_absences" id="btn_search" type="button"><i class="fas fa-search"></i></button>
                            <?php elseif (($_GET['type_report']) == 3) : ?>
                                <button class="btn btn-outline-info search_incidents" id="btn_search" type="button"><i class="fas fa-search"></i></button>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <input type="text" class="form-control date-input" id="search_date" placeholder="Buscar por fecha">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info" id="btn_search" type="button"><i class="fas fa-search"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">

        <div class="row">
            <div id="student_list_container">

            </div>
        </div>
    </div>
</div>
<?php


$listStudent = array();
$listIncidents = array();

if (isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudentByGroup($id_group);
    $id_gp = $_GET['id_group'];
    if (!empty($id_assingment = $helpers->getAssignmentByGroup($id_gp))) {
        foreach ($id_assingment as $assignment) {
            $id_assingment = $assignment->id_assignment;
        }


        if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assingment))) {
            $id_level_combination = $id_level_combination->id_level_combination;
            $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
        }
    }
}

?>
<?php if (!empty($listStudent)) : ?>
    <div class="card">
        <div class="card-body" id="attendance_report_coordinator">
            <div class="table-responsive">
                <?php include 'view_attendance_report_coordinator.php';

                ?>

            </div>
        </div>
    </div>
    <script src="js/functions/students/teachers/export_table.js"></script>
<?php endif;
?>

<script src="js/functions/reports/academic_performance/attendance/incidents_attendance_reports.js"></script>
<script src="js/functions/students/teachers/attendance_report_coordinator.js"></script>