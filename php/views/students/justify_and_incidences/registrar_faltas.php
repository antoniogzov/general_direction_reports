<div id="student_list_container">
    <div class="table-responsive">
        <table class="table align-items-center table-flush" id="tStudents">
            <thead class="thead-light">
                <tr>
                    <th>CÓD. ALUMNO</th>
                    <th>NOMBRE</th>
                    <th>GRUPO</th>
                    <th>CONTÁCTO</th>
                    <th>FALTAS</th>
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
                            <button type="button" class="btn btn-primary btn-sm btnJustify" id-student="<?= $student->id_student ?>" code-student="<?= $student->student_code ?>" name-student="<?= $student->name_student ?>" data-toggle="modal" data-target="#modalJustify" data-id_student="<?= $student->id_student ?>" data-id_group="<?= $id_group ?>"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></button>
                        </td>
                        <td>
                            <?php $breakdownJustify = $attendance->breakdownJustify($student->id_student, $today_date_time); ?>
                            <?php if (count($breakdownJustify) > 0) : ?>
                                <button type="button" class="btn btn-primary btn-sm btnBreakdownAbsence" id-student="<?= $student->id_student ?>" data-today-date="<?= $today_date_time ?>" data-toggle="modal" data-target="#modalDesgloseFaltas"><span class="btn-inner--icon"><i class="ni ni-bullet-list-67"></i></button>
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
<?php

