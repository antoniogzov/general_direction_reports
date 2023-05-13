<!-- Modal -->
<div class="modal fade" id="infoAcademica" tabindex="-1" role="dialog" aria-labelledby="infoAcademicaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoAcademicaLabel">Información académica de: <?= ucfirst($listStudent->student_code) ?> | <?= ucfirst($listStudent->name_student) ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <hr>
                    <?php foreach ($StudentGroups as $groups) : ?>
                        <?php
                        $EStudentSubjects = $archives->GetSubjectsStudent($id_student, '1', $groups->id_group);
                        $HStudentSubjects = $archives->GetSubjectsStudent($id_student, '2', $groups->id_group);
                        ?>
                        <div class="row">
                            <?php if (!empty($EStudentSubjects) && empty($HStudentSubjects)) : ?>
                                <div class="col-12">
                                    <h2>Materias y profesores de Español</h2>
                                    <div class="table-responsive">
                                        <div>
                                            <table class="table align-items-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="border-left: 3px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="name">Materia</th>
                                                        <th style="border-right: 3px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="budget">Profesor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list">
                                                    <?php foreach ($EStudentSubjects as $subjects_spanish) : ?>

                                                        <tr>
                                                            <th style="border-left: 3px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="row"><?= $subjects_spanish->name_subject ?></th>
                                                            <td style="border-right: 3px solid rgba(194, 194, 194) !important;" class="budget"><?= $subjects_spanish->teacher_name ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif (!empty($EStudentSubjects) && !empty($HStudentSubjects)) : ?>
                                <div class="col-6">
                                    <h2>Materias y profesores de Español</h2>
                                    <div class="table-responsive">
                                        <div>
                                            <table class="table align-items-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="border-left: 3px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="name">Materia</th>
                                                        <th style="border-right: 3px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="budget">Profesor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list">
                                                    <?php foreach ($EStudentSubjects as $subjects_spanish) : ?>

                                                        <tr>
                                                            <th style="border-left: 3px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="row"><?= $subjects_spanish->name_subject ?></th>
                                                            <td style="border-right: 3px solid rgba(194, 194, 194) !important;" class="budget"><?= $subjects_spanish->teacher_name ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h2>Materias y profesores de Hebreo</h2>
                                    <div class="table-responsive">
                                        <div>
                                            <table class="table align-items-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="border-left: 4px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="name">Materia</th>
                                                        <th style="border-right: 3px solid rgba(194, 194, 194) !important;" scope="col" class="sort" data-sort="budget">Profesor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list">
                                                    <?php foreach ($HStudentSubjects as $subjects_hebrew) : ?>

                                                        <tr>
                                                            <th style="border-left: 4px solid rgba(194, 194, 194) !important; border-right: 1px solid rgba(194, 194, 194) !important;" scope="row"><?= $subjects_hebrew->name_subject ?></th>
                                                            <td style="border-right: 3px solid rgba(194, 194, 194) !important;" class="budget"><?= $subjects_hebrew->teacher_name ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>