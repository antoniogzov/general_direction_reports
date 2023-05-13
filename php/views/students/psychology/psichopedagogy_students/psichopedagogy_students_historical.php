<?php
/* include '/card_select_edit_week_attendance.php'; */
$listStudent = array();
if (isset($_GET['student'])) {
    $id_student = $_GET['student'];
    $listStudent = $psychopedagogy->getStudentInfo($id_student);
    $GetStudentTerapeuticCards = $psychopedagogy->GetStudentTerapeuticCards($id_student);
    $GetStudentAVG_Esp = $psychopedagogy->GetStudentAVG($id_student, '1');
    $GetStudentAVG_Heb = $psychopedagogy->GetStudentAVG($id_student, '2');
    $general_avg = '';
    $avg_spanish = '';
    $avg_hebrew = '';

    if (!empty($GetStudentAVG_Heb) && ($GetStudentAVG_Heb[0]->average != '')) {
        $avg_hebrew = $GetStudentAVG_Heb[0]->average;
        $avg_hebrew = number_format($avg_hebrew, 2);
    } else {
        $avg_hebrew = 'S/D';
    }
    if (!empty($GetStudentAVG_Esp) && ($GetStudentAVG_Esp[0]->average != '')) {
        $avg_spanish = $GetStudentAVG_Esp[0]->average;
        $avg_spanish = number_format($avg_spanish, 2);
    } else {
        $avg_spanish = 'S/D';
    }
    if ($avg_spanish != 'S/D' && $avg_hebrew != 'S/D') {
        $general_avg = ($avg_spanish + $avg_hebrew) / 2;
    } else if ($avg_spanish != 'S/D') {
        $general_avg = $avg_spanish;
    } else if ($avg_hebrew != 'S/D') {
        $general_avg = $avg_hebrew;
    } else {
        $general_avg = 'S/D';
    }

    if ($general_avg != 'S/D') {
        $general_avg = number_format($general_avg, 2);
    }

    //$GetStudentAVG_Esp
    $listStudent = $listStudent[0];
    $StudentGroups = $archives->GetGroupsStudent($id_student);

    $fch = explode("-", $listStudent->birthdate);
    $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];



    $meses = array(
        "01" => "Enero",
        "02" => "Febrero",
        "03" => "Marzo",
        "04" => "Abril",
        "05" => "Mayo",
        "06" => "Junio",
        "07" => "Julio",
        "08" => "Agosto",
        "09" => "Septiembre",
        "10" => "Octubre",
        "11" => "Noviembre",
        "12" => "Diciembre"
    );

    $fecha_formato  = $fch[2] . " de " . $meses[$fch[1]] . " de " . $fch[0];



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
                                    <div class="card-profile-stats d-flex justify-content-center">
                                        <div>
                                            <span class="heading"><?= $avg_spanish ?></span>
                                            <span class="description">Promedio Esp.</span>
                                        </div>
                                        <div>
                                            <span class="heading"><?= $general_avg ?></span>
                                            <span class="description">Prmedio Gral.</span>
                                        </div>
                                        <div>
                                            <span class="heading"><?= $avg_hebrew ?></span>
                                            <span class="description">Promedio Heb.</span>
                                        </div>
                                    </div>
                                    <div class="card-profile-stats d-flex justify-content-center">
                                        <div>
                                            <span class="heading" id="main_general_student_attendance_percentage">...</span>
                                            <span class="description">Promedio Asistencia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="h3">
                                    <span class="font-weight-light"> <?= $listStudent->student_code ?></span>| <?= $listStudent->name_student ?> | <span class="font-weight-light"> <?= $edad ?> años </span>
                                    <br> ( <i class="fa-solid fa-cake-candles"></i> <?= $fecha_formato ?>)
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
                    <h2 class="text-uppercase">Histórico de intervenciones psicológicas</h2>

                    <p hidden id="name_colab"><?= $infoCol->name ?></p>
                    <br>
                    <br>
                    <br>
                    <div class="table-responsive" style="height:400px !important;">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="color:white !important;" scope="col">Terapeuta</th>
                                    <th style="color:white !important;" scope="col">T. intervención</th>
                                    <th style="color:white !important;" scope="col">Quién lo refirió</th>
                                    <th style="color:white !important;" scope="col">Motivo</th>
                                    <th style="color:white !important;" scope="col">F. de registro</th>
                                    <th style="color:white !important;" scope="col">F. de inicio</th>
                                    <th style="color:white !important;" scope="col">F. de fin</th>
                                    <th style="color:white !important;" scope="col">Causa por la que concluyó</th>
                                    <th style="color:white !important;" scope="col">Colaborador que registró</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($GetStudentTerapeuticCards)) : ?>
                                    <?php foreach ($GetStudentTerapeuticCards as $card) :
                                        if ($card->fecha_registro_format == "0000-00-00") {
                                            $fecha_registro_format = "Sin fecha";
                                        } else {
                                            $fecha_registro_format = $card->fecha_registro_format;
                                        }

                                        if ($card->start_date == "0000-00-00") {
                                            $start_date = "Sin fecha";
                                        } else {
                                            $start_date = $card->start_date;
                                        }
                                        if ($card->end_date == "0000-00-00") {
                                            $end_date = "Sin fecha";
                                        } else {
                                            $end_date = $card->end_date;
                                        }

                                        if ($card->kind_of_interview == "") {
                                            $kind_of_interview = "Sin info.";
                                        } else {
                                            $getKindsInterviews1 = $psychopedagogy->getKindsInterviews($card->kind_of_interview);
                                            if (!empty($getKindsInterviews1)) {
                                                $kind_of_interview = $getKindsInterviews1[0]->description;
                                            } else {
                                                $kind_of_interview = "Sin info.";
                                            }
                                        }

                                        if ($card->cause_why_conclused == "") {
                                            $cause_why_conclused = "Sin info.";
                                        } else {
                                            $reasonsWhyConclused2 = $psychopedagogy->reasonsWhyConclused($card->cause_why_conclused);
                                            if (!empty($reasonsWhyConclused2)) {
                                                $cause_why_conclused = $reasonsWhyConclused2[0]->description;
                                            } else {
                                                $cause_why_conclused = "Sin info.";
                                            }
                                        }
                                        if ($card->who_reffered == "") {
                                            $who_reffered = "Sin info.";
                                        } else {
                                            if($card->who_reffered == "1"){
                                                $who_reffered = "Referido por el colegio";
                                            }else if($card->who_reffered == "2"){
                                                $who_reffered = "Referido por los padres";
                                            }else{
                                                $who_reffered = $card->who_reffered;
                                                }
                                        }


                                    ?>
                                        <tr id="tr<?= $card->id_therapeutic_cards ?>" data-reason_why_reffered="<?= $card->reason_why_reffered ?>" data-fecha_registro_format="<?= $fecha_registro_format ?>" data-start_date="<?= $start_date ?>" data-end_date="<?= $end_date ?>" data-colaborador_registro="<?= $card->colaborador_registro ?>" data-cause_why_conclused="<?= br2nl($card->cause_why_conclused) ?>">
                                            <td><?= $card->name_of_proffessional ?></td>
                                            <td><?= $kind_of_interview ?></td>
                                            <td><?= $who_reffered ?></td>
                                            <td><?= $card->reason_why_reffered ?></td>
                                            <td><?= $fecha_registro_format ?></td>
                                            <td><?= $start_date ?></td>
                                            <td><?= $end_date ?></td>
                                            <td style="white-space: normal !important;"><?= br2nl($cause_why_conclused) ?></td>
                                            <td style="white-space: normal !important;"><?= $card->colaborador_registro ?></td>
                                            <!-- <td>
                                                <a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a>
                                                <a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a>
                                                <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a>
                                                <a title="Editar" href="#" data-toggle="modal" data-target="#editInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-primary editInterview"><i class="fa-solid fa-edit"></i></a>
                                                <a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-danger deleteInterview"><i class="fa-regular fa-trash-alt"></i></a>
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
include 'addNewInterview.php';
include 'segimientoInterview.php';
include 'editInterview.php';
include 'shareInterview.php';
include 'infoInterview.php';

?>

<script src="js/functions/psichopedagogy/psichopedagogy.js"></script>
<script src="js/functions/psichopedagogy/select2.js"></script>
<script src="js/functions/student_archive/student_archive.js"></script>
<script>
    Swal.close();
</script>