<?php
/* include '/card_select_edit_week_attendance.php'; */
$listStudent = array();
if (isset($_GET['student'])) {
    $id_student = $_GET['student'];
    $listStudent = $psychopedagogy->getStudentInfo($id_student);
    $GetStudentTerapeuticCards = $psychopedagogy->GetStudentTerapeuticCards($id_student);
    $getKindsInterviews = $psychopedagogy->getKindsInterviews('0');
    $reasonsWhyConclused = $psychopedagogy->reasonsWhyConclused('0');
    $getWhoRefered = $psychopedagogy->getWhoRefered('0');
    $general_avg = '';
    $avg_spanish = '';
    $avg_hebrew = '';
    /* getMotivos(); */


    //$GetStudentAVG_Esp
    $listStudent = $listStudent[0];
    $GetStudentTerapeuticCardsInscriptions = $psychopedagogy->GetStudentTerapeuticCardsInscription($id_student);
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
            <div class="row">
                <div class="col-xl-3 ">
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
                                <!-- <a style="color:white" id-student="<?= $listStudent->id_student ?>" data-toggle="modal" data-target="#modalDesgloseIncidencias" class="btn btn-sm btn-warning float-right btnBreakdownIncidents">Ficha de Inscripción</a> -->
                                <!-- <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                            <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <br>
                            <br>
                           <div class="text-center">
                                <h5 class="h3">
                                    <span class="font-weight-light"> <?= $listStudent->student_code ?></span>| <?= strtoupper($listStudent->name_student) ?> S| <span class="font-weight-light"> <?= $edad ?> años </span>
                                    <br> ( <i class="fa-solid fa-cake-candles"></i>        <?=$fecha_formato?>)
                                </h5>
                               
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
                    <h2 class="text-uppercase">Intervenciones psicológicas</h2>
                    <p hidden id="name_colab"><?= $infoCol->name ?></p>
                    <br>
                    <?php if (!empty($GetStudentTerapeuticCardsInscriptions)) : ?>
                        <a style="color:white !important;" data-toggle="modal" data-target="#seeTerapeuticCardInscription" class="btn btn-primary"><i class="fa-regular fa-file-lines"></i> Ver ficha de la inscripción</a>
                    <?php endif; ?>
                    <a style="color:white !important;" data-toggle="modal" data-target="#addNewInterview" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo registro </a>
                    <a style="color:white !important;" href="?submodule=parents_tracking&student=<?= $listStudent->id_student ?>" target="_blank" class="btn btn-primary"><i class="fa-solid fa-people-roof"></i> Seguimientos</a>
                    <a style="color:white !important;" data-toggle="modal" data-target="#studentDocuments" data-id-student="<?= $listStudent->id_student ?>"  class="btn btn-primary getStudentsDocuments"><i class="fa-regular fa-folder-open"></i> Anexos de alumno </a>
                    <br>
                    <br>
                    <br>
                    <div class="table-responsive" style="height:400px !important;">
                    <a title=" Histórico completo " style="color:white !important;" href="?submodule=psichopedagogy_students_historical&student=<?= $listStudent->id_student ?>" class="btn btn-info"><i class="fa-solid fa-clock-rotate-left"></i></a>
                    <br>
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="color:white !important;" scope="col">Terapeuta</th>
                                    <th style="color:white !important;" scope="col">T. intervención</th>
                                    <th style="color:white !important;" scope="col">Referido por:</th>
                                    <th style="color:white !important;" scope="col">F. de inicio</th>
                                    <th style="color:white !important;" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($GetStudentTerapeuticCards)) : ?>
                                    <?php foreach ($GetStudentTerapeuticCards as $card) :


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
                                            $who_reffered = $card->who_reffered;

                                            if ($who_reffered == "1" || $who_reffered == "2") {
                                                $getWhoRefered1 = $psychopedagogy->getWhoRefered1($card->who_reffered);
                                                if (!empty($getWhoRefered1)) {
                                                    $who_reffered = $getWhoRefered1[0]->description;
                                                } else {
                                                    $who_reffered = "Sin info.";
                                                }
                                            }
                                            /* 
                                            $getWhoRefered2 = $psychopedagogy->getWhoRefered($card->who_reffered);
                                            if (!empty($getWhoRefered2)) {
                                                $who_reffered = $getWhoRefered2[0]->description;
                                            } else {
                                                $who_reffered = "Sin info.";
                                            } */
                                        }

                                    ?>
                                        <tr id="tr<?= $card->id_therapeutic_cards ?>" data-reason_why_reffered="<?= $card->reason_why_reffered ?>" data-fecha_registro_format="<?= $card->fecha_registro_format ?>" data-start_date="<?= $card->start_date ?>" data-end_date="<?= $card->end_date ?>" data-colaborador_registro="<?= $card->colaborador_registro ?>" data-cause_why_conclused="<?= br2nl($card->cause_why_conclused) ?>">
                                            <td><?= $card->name_of_proffessional ?></td>
                                            <td><?= $kind_of_interview ?></td>
                                            <td><?= $who_reffered ?></td>
                                            <!-- <td><?= $card->reason_why_reffered ?></td> -->
                                            <!-- <td><?= $card->fecha_registro_format ?></td> -->
                                            <td><?= $card->start_date ?></td>
                                            <!-- <td><?= $card->end_date ?></td> -->
                                            <!-- <td style="white-space: normal !important;"><?= br2nl($cause_why_conclused) ?></td> -->
                                            <!-- <td><?= $card->colaborador_registro ?></td> -->
                                            <td>
                                                <a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a>
                                                <a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a>
                                                <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a>
                                                <a title="Editar" href="#" data-toggle="modal" data-target="#editInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-primary editInterview"><i class="fa-solid fa-edit"></i></a>
                                                <a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-danger deleteInterview"><i class="fa-regular fa-trash-alt"></i></a>
                                            <td>
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
        <?php endif; ?>
    </div>
</div>

<?php

function br2nl($string)
{
    return str_replace("\n", '<br>', $string);
}

include 'addNewInterview.php';
include 'segimientoInterview.php';
include 'editInterview.php';
include 'shareInterview.php';
include 'infoInterview.php';
include 'studentDocuments.php';


$GetPsychopedagogicalData = $psychopedagogy->GetPsychopedagogicalData($id_student);
if (!empty($GetPsychopedagogicalData)) {
    include 'seeTerapeuticCardInscription.php';
}

?>

<script src="js/functions/psichopedagogy/psichopedagogy.js"></script>
<script src="js/functions/psichopedagogy/select2.js"></script>
<script src="js/functions/student_archive/student_archive.js"></script>
<script>
    Swal.close();
</script>