<?php

if (isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudentByGroup($id_group);
    $IncidentsClasif = $attendance->getIncidentsClasifications($id_group);
    $getSubjectsByGroup = $attendance->getSubjectsByGroup($id_group);
    //$incidentsCatalog = $attendance->getIncidentsCatalog($id_group);

}
?>
<div id="student_list_container">
    <div class="table-responsive">
        <table class="table align-items-center table-flush" id="tStudents">
            <thead class="thead-light">
                <tr>
                    <th>CÓD. ALUMNO</th>
                    <th>NOMBRE</th>
                    <th>GRUPO</th>
                    <th>CONTÁCTO</th>
                    <th>INCIDENCIAS</th>
                    <th>REGISTROS</th>
                </tr>
            </thead>
            <tbody class="list">
                <?php foreach ($listStudent as $student) : ?>
                    <tr>
                        <td><?= $student->student_code ?></td>
                        <td><?= mb_strtoupper($student->name_student) ?></td>
                        <td><?= $student->group_code ?></td>
                        <td>
                        <button class="btn btn-icon btn-info btn-sm getStudentContactInfo" data-id_student="<?= $student->id_student ?>" type="button"><span class="btn-inner--icon"><i class="fa-solid fa-phone"></i></span></button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm addStudentIncident new_incident" id-student="<?= $student->id_student ?>" code-student="<?= $student->student_code ?>" name-student="<?= $student->name_student ?>" data-id_student="<?= $student->id_student ?>"  data-bs-toggle="modal" data-bs-target="#newIncident"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></button>
                        </td>
                        <td>
                            <?php
                             $breakdownIncidences = $attendance->breakdownIncidences($student->id_student); ?>
                            <?php if (count($breakdownIncidences) > 0) : ?>
                                <button type="button" class="btn btn-primary btn-sm btnBreakdownIncidents" id-student="<?= $student->id_student ?>" data-today-date="<?= $today_date_time ?>" data-toggle="modal" data-target="#modalDesgloseIncidencias"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>
                            <?php else : ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>