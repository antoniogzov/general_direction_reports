<div id="student_list_container">
    <div class="table-responsive">
        <table class="table align-items-center table-flush" id="tStudents">
            <thead class="thead-light">
                <tr>
                    <th>CÓD. ALUMNO</th>
                    <th>NOMBRE</th>
                    <th>FALTAS</th>
                    <th>REGISTROS</th>
                </tr>
            </thead>
            <tbody class="list">
                <?php foreach ($listStudent as $student) : ?>
                    <tr>
                        <td><?= $student->student_code ?></td>
                        <td><?= $student->name_student ?></td>
                        <td>
                            <?php if ($_GET['type_report'] == '1') : ?>
                                <button type="button" class="btn btn-primary btn-sm btnJustify" id-student="<?= $student->id_student ?>" code-student="<?= $student->student_code ?>" name-student="<?= $student->name_student ?>" data-toggle="modal" data-target="#modalJustify" data-id_student="<?= $student->id_student ?>" data-id_group="<?= $id_group ?>"><span class="btn-inner--icon"><i class="ni ni-fat-add"></i></button>
                            <?php else : ?>
                                <button type="button" id-student="<?= $student->id_student ?>" code-student="<?= $student->student_code ?>" name-student="<?= $student->name_student ?>" class="btn btn-primary btn-sm btnSuspend" data-toggle="modal" data-target="#modalSuspend" data-id_student="<?= $student->id_student ?>" data-id_group="<?= $id_group ?>">Suspender</button>
                            <?php endif; ?>
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