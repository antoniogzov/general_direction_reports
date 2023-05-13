<?php foreach ($StudentInfo as $student_info) : ?>
    <h4><?= ucfirst($student_info->student_code) ?> | <?= ucfirst($student_info->name_student) ?></h4>
<?php endforeach ?>
<hr>
<?php foreach ($StudentGroups as $groups) : ?>
    <?php
    $EStudentSubjects = $archives->GetSubjectsStudent($id_student, '1', $groups->id_group);
    $HStudentSubjects = $archives->GetSubjectsStudent($id_student, '2', $groups->id_group);
    ?>
    <h1><?= $groups->group_code ?></h1>
    <h4>(<?= $groups->group_type ?>)</h4>
    <br>
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
            </div>
            <br>
        <?php endif; ?>
    </div>
    <br>
    <br>


    <!--  <?php if (!empty($HStudentSubjects) && empty($HStudentSubjects)) : ?>

    <?php endif; ?> -->
<?php endforeach; ?>