<?php
/* include '/card_select_edit_week_attendance.php'; */
$listStudent = array();
if (isset($_GET['student'])) {
    $id_student = $_GET['student'];
    $listStudent = $psychopedagogy->getStudentInfo($id_student);
    $GetStudentParentsTracking = $psychopedagogy->GetStudentParentsTracking($id_student);
    $getKindsTracking = $psychopedagogy->getKindsTracking();
    $listStudent = $listStudent[0];
    $StudentGroups = $archives->GetGroupsStudent($id_student);

    $fch = explode("-", $listStudent->birthdate);
    $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];


    $dias = explode("-", $tfecha, 3);
    $dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
    $edad = (int)((time() - $dias) / 31556926);
}

?>

<?php $dir_syle = 'php/views/students/psychology/challenges/dist/style.css'; ?>
<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://kit.fontawesome.com/e568464256.js" crossorigin="anonymous"></script>
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
<div class="card">
    <div class="card-body">
        <?php if (!empty($listStudent)) : ?>


            <script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
            <script src="vendor/tablefilter/tablefilter.js"></script>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/datatables.min.css" />

            <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/datatables.min.js"></script>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-profile">
                        <img src="images/school_background2.jpg" alt="Image placeholder" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a>
                                        <?php
                                        $ruta_avatar = "../control_escolar/students_archives/" . $listStudent->student_code . ".jpg";
                                        if (file_exists($ruta_avatar)) : ?>
                                            <img src="<?= $ruta_avatar ?>" class="rounded-circle">
                                        <?php else : ?>
                                            <img src="images/user.png" class="rounded-circle">
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <!-- <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                            <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col">
                                    <br>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="h3">
                                    <span class="font-weight-light"> <?= $listStudent->student_code ?></span>| <?= $listStudent->name_student ?> | <span class="font-weight-light"> <?= $edad ?> años </span>
                                </h5>
                                <div class="h5 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>Grupos inscritos:
                                </div>
                                <?php foreach ($StudentGroups as $groups) : ?>
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin mr-2"></i><?= $groups->group_code ?>
                                    </div>
                                <?php endforeach; ?>
                                <!-- <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div> -->
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-9 container-list-students">
                    <h2 class="text-uppercase">Seguimientos</h2>

                    <p hidden id="name_colab"><?= $infoCol->name ?></p>
                    <br>

                    <a style="color:white !important;" data-toggle="modal" data-target="#newParentTracking" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo registro </a>
                    <br>
                    <br>
                    <div class="table-responsive" style="height:400px !important;">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="color:white !important;" scope="col">SEGUIMIENTO A</th>
                                    <th style="color:white !important;" scope="col">Motivo del seguimiento</th>
                                    <th style="color:white !important;" scope="col">Acuerdos</th>
                                    <th style="color:white !important;" scope="col" title="Responsable de Seguimiento">Resp. de Seguimiento</th>
                                    <th style="color:white !important;" scope="col" title="Tipo de Seguimiento">T. de Seguimiento</th>
                                    <th style="color:white !important;" scope="col" title="Fecha de Contacto">F. de contacto</th>
                                    <th style="color:white !important;" scope="col">Descripción</th>
                                    <th style="color:white !important;" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($GetStudentParentsTracking)) : ?>
                                    <?php foreach ($GetStudentParentsTracking as $tracking) :
                                        $str_agreements = substr($tracking->agreements, 0, 20);
                                        $str_agreements = $str_agreements . "...";

                                        $str_description = substr($tracking->descripcion, 0, 20);
                                        $str_description = $str_description . "...";

                                        if ($tracking->fecha_registro_format == "0000-00-00") {
                                            $fecha_registro_format = "Sin fecha";
                                        } else {
                                            $fecha_registro_format = $tracking->fecha_registro_format;
                                        }


                                    ?>
                                        <tr id="tr<?= $tracking->id_parents_tracking ?>" data-agreements="<?= $tracking->agreements ?>" data-monitoring_manager="<?= $tracking->monitoring_manager ?>" data-contact_date="<?= $tracking->contact_date ?>" data-descripcion="<?= $tracking->descripcion ?>" data-id_tracking_type="<?= $tracking->id_tracking_type ?>">
                                            <td><?= $tracking->tracing_to ?></td>
                                            <td><?= $tracking->reason ?></td>
                                            <td class="td-agreements" data-agreements="<?= $tracking->agreements ?>" style="white-space: normal; !important;"><?= $str_agreements ?></td>
                                            <td><?= $tracking->monitoring_manager ?></td>
                                            <td><?= $tracking->description_tracking_type ?></td>
                                            <td><?= $tracking->contact_date ?></td>
                                            <td class="td-description" data-descripcion="<?= $tracking->descripcion ?>" style="white-space: normal; !important;"><?= $str_description ?></td>
                                            <td>
                                                <a title="Editar" href="#" data-toggle="modal" data-target="#editParentTracking" data-id-tracking="<?= $tracking->id_parents_tracking ?>" class="btn btn-sm btn-primary editParentTracking"><i class="fa-solid fa-edit"></i></a>
                                                <a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoPadresChat" data-id-parents-tracking="<?= $tracking->id_parents_tracking ?>" class="btn btn-sm btn-success seeTrackingParents"><i class="fa-regular fa-comment-dots"></i></a>
                                                <a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteParentTracking" data-id-tracking="<?= $tracking->id_parents_tracking ?>" class="btn btn-sm btn-danger deleteParentTracking"><i class="fa-regular fa-trash-alt"></i></a>
                                            </td>
                                            <!-- <td>
                                                <a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-tracking="<?= $tracking->id_therapeutic_cards ?>" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a>
                                                <a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-tracking="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a>
                                                <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-tracking="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a>
                                            <td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="trEmptyResults">
                                        <td class="text-center" colspan="100%">
                                            <h1> No se encontraron registros</h1>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script>
                var tf = new TableFilter(document.querySelector("#tStudents"), {
                    base_path: "../general/js/vendor/tablefilter/tablefilter/",
                    col_1: "select",
                    paging: {
                        results_per_page: ["Registros por página: ", [10, 25, 50, 100]],
                    },
                    rows_counter: true,
                    btn_reset: true,
                });
                tf.init();

                $('#tStudents').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'colvis',
                        'excel',
                        'print'
                    ]
                });
            </script>
        <?php endif; ?>
    </div>
</div>

<?php

function br2nl($string)
{
    return str_replace("\n", '<br>', $string);
}

include dirname(__DIR__ . '', 1) . '/challenges/modals/infoGeneral.php';
include dirname(__DIR__ . '', 1) . '/challenges/modals/calificaciones.php';
include dirname(__DIR__ . '', 1) . '/challenges/modals/infoAsistencias.php';
include dirname(__DIR__ . '', 1) . '/challenges/modals/infoAcademica.php';
include 'newParentTracking.php';
include 'editParentTracking.php';
include 'seguimientoPadresChat.php';

?>
<script>
    Swal.close();
</script>
<script src="js/functions/psichopedagogy/psichopedagogy.js"></script>
<script src="js/functions/psichopedagogy/select2.js"></script>
<script src="js/functions/student_archive/student_archive.js"></script>