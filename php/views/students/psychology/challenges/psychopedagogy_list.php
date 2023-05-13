<?php
/* include '/card_select_edit_week_attendance.php'; */

$listStudent = array();
$today = date('Y-m-d');


$listStudent = $psychopedagogy->getAllStudentsCoordinator();

?>
<?php if (!empty($listStudent)) : ?>
    <script>
        function loading() {
            Swal.fire({
                text: "Cargando...",
                html: '<img src="images/loading_iteach.gif" width="300" height="300">',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
        }
        loading();
    </script>

    <script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
    <script src="vendor/tablefilter/tablefilter.js"></script>
    <script src="https://kit.fontawesome.com/2baa365664.js" crossorigin="anonymous"></script>
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">TODOS LOS ALUMNOS</h3>
                    <h3 class="heading mb-0 ml-2">Alumnos: <?= count($listStudent) ?></h3>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold">CÓD. ALUMNO</th>
                                    <th class="font-weight-bold">NOMBRE</th>
                                    <th class="font-weight-bold">GUPO</th>
                                    <th class="font-weight-bold">MÓDULO DE PSICOPEDAGOGÍA</th>
                                    <th class="font-weight-bold"></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($listStudent as $student) :
                                    $GetIncidents = $psychopedagogy->GetIncidents($student->id_student);
                                    $GetPsicoInfo = $psychopedagogy->GetPsicoInfo($student->id_student);
                                ?>
                                    <tr>
                                        <td><?= strtoupper($student->student_code) ?></td>
                                        <td><?= strtoupper($student->name_student) ?></td>
                                        <td><?= ucfirst($student->group_code) ?></td>
                                        <td>
                                            <div>
                                                <a href="alumnos.php?submodule=student_info_module&student=<?= $student->id_student ?>" class="btn btn-sm btn-primary">VER INFO</a>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <?php if (count($GetIncidents) > 0) : ?>
                                                <span data-toggle="tooltip" data-placement="top" title="Tiene incidencias registradas" style="color: red;"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                            <?php endif; ?>
                                            <?php if (count($GetPsicoInfo) > 0) : ?>
                                                <span data-toggle="tooltip" data-placement="top" title="Tiene intervenciones psicopedagógicas registradas" style="color: pink;"><i class="fa-solid fa-brain"></i></span>
                                            <?php endif; ?>
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
<?php endif; ?>
<script>
    var tf = new TableFilter(document.querySelector("#tStudents"), {
        base_path: "../general/js/vendor/tablefilter/tablefilter/",
        col_2: "select",
        col_3: "none",
        paging: {
            results_per_page: ["Records: ", [10, 25, 50, 100]],
        },
        rows_counter: true,
        btn_reset: true,
    });
    tf.init();
</script>
<script>
    Swal.close();
</script>