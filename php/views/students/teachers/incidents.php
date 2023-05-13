<?php
include dirname(__DIR__, 1) . '/card_select_incidents.php';

$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudentByGroup($id_group);
    $IncidentsClasif = $attendance->getIncidentsClasifications($id_group);
    $getSubjectsByGroup = $attendance->getSubjectsByGroup($id_group);
    //$incidentsCatalog = $attendance->getIncidentsCatalog($id_group);
    
}
?>
<?php if (!empty($listStudent)) : ?>
    <div class="card">
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-3">
                </div>
                <div class="div-button-refresh col-md-3" style="display: none">
                    <input type="button" class="btn btn-warning float-right" onClick="window.location.reload();" value="Tomar nueva asistencia" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">REGISTRAR INCIDENCIA POR ALUMNO</h3>
                    <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold col-md-2">Cód. alumno</th>
                                    <th class="font-weight-bold col-md-4">Nombre</th>
                                    <th class="font-weight-bold col-md-2">REPORTAR</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($listStudent as $student) : ?>
                                    <tr>
                                        <td><?= strtoupper($student->student_code) ?></td>
                                        <td><?= ucfirst($student->name_student) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-icon btn-primary new_incident new_incident_teacher" id="<?= $student->id_student ?>" type="button">
                                                <span class="btn-inner--icon"><i class="fas fa-exclamation-triangle"></i></i></span>
                                                <span class="btn-inner--text">Reportar</span>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'incident_modal.php';  ?>
<?php endif; ?>


<script src="js/functions/students/teachers/incidents.js" async></script>