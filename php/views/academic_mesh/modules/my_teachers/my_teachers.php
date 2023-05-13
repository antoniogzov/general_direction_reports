<?php
if (($grants & 8)) {
    include 'card_select_my_teachers.php';
} else if (($grants & 4)) {
    //include 'card_select_teacher_qr.php';
}
?>

<?php if (isset($id_academic_area) && isset($id_academic_area)) : ?>
    <?php if (isset($getTeachers)) : ?>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12 container-list-students">
                        <h3 class="ml-2">MIS PROFESORES</h3>
                        <h4 id="total_teachers" class="ml-2">Total: <?= count($getTeachers) ?></h4>
                        <br>

                        <div id="tableTeachers" class="table-responsive">
                            <table class="table align-items-center table-flush" id="teacherTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>N° Colab.</th>
                                        <th>Nombre</th>
                                        <th>Correo Inst.</th>
                                        <th>Asignaturas</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($getTeachers as $teacher) : ?>
                                        <tr>
                                            <td><?= $teacher->no_colaborador; ?></td>
                                            <td><?= mb_strtoupper($teacher->teacher_name); ?></td>
                                            <td><?= mb_strtolower($teacher->correo_institucional); ?></td>
                                            <td class="td-actions text-center">
                                                <button type="button" rel="tooltip" id-teacher="<?= $teacher->no_colaborador; ?>" class="btn btn-secondary btn-icon btn-sm btn_teacher_assignments" data-original-title="" title="">
                                                    <i class="ni ni-book-bookmark pt-1"></i>
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
        <script>
            $('#teacherTable').DataTable({
                dom: 'Bfrtip',
                "bSort": false,
                "paging": false,
                buttons: [
                    'excel', 'pdf'
                ]
            });
        </script>
        <style>
            .swal-wide {
                width: 850px !important;
            }
        </style>
    <?php endif; ?>
<?php endif; ?>
<script src="js\functions\academic_mesh\my_teachers.js"></script>