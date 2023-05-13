<?php
/* include '/card_select_edit_week_attendance.php'; */
$listStudent = array();
if (isset($_GET['student'])) {
    $id_student = $_GET['student'];
    $listStudent = $psychopedagogy->getStudentInfo($id_student);
    $GetStudentAVG_Esp = $psychopedagogy->GetStudentAVG($id_student, '1');
    $GetStudentAVG_Heb = $psychopedagogy->GetStudentAVG($id_student, '2');
    $getJustify = $psychopedagogy->GetJustify($id_student);
    $getIncidents = $psychopedagogy->GetIncidents($id_student);
    $getMedicalInfo = $psychopedagogy->getMedicalInfo($id_student);
    $getMedicalInfo = $getMedicalInfo[0];
    $general_avg = '';
    $avg_spanish = '';
    $avg_hebrew = '';

    if (!empty($GetStudentAVG_Heb) && ($GetStudentAVG_Heb[0]->average != '')) {
        $avg_hebrew = $GetStudentAVG_Heb[0]->average;
        $avg_hebrew = number_format($avg_hebrew, 1);
    } else {
        $avg_hebrew = 'S/D';
    }
    if (!empty($GetStudentAVG_Esp) && ($GetStudentAVG_Esp[0]->average != '')) {
        $avg_spanish = $GetStudentAVG_Esp[0]->average;
        $avg_spanish = number_format($avg_spanish, 1);
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
        $general_avg = number_format($general_avg, 1);
    }

    //$GetStudentAVG_Esp
    $listStudent = $listStudent[0];
    $StudentGroups = $archives->GetGroupsStudent($id_student);
    $GetGroupsStudentSubject = $archives->GetGroupsStudentSubject($id_student);

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
                <div class="col-xl-6 ">
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
                                <?php if (!empty($getJustify)) : ?>
                                    <a href="#" id-student="<?= $listStudent->id_student ?>" data-today-date="<?php date('Y-m-d')?>" data-toggle="modal" data-target="#modalDesgloseFaltas" class="btn btn-sm btn-info  mr-4 btnBreakdownAbsence">Seguimiento de faltas</a>
                                <?php endif; ?>
                                <?php if (!empty($getIncidents)) : ?>
                                    <a style="color:white" id-student="<?= $listStudent->id_student ?>" data-toggle="modal" data-target="#modalDesgloseIncidencias" class="btn btn-sm btn-warning float-right btnBreakdownIncidents">Histórico de incidencias</a>
                                <?php endif; ?>
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
                                    <span class="font-weight-light"> <?= $listStudent->student_code ?></span>| <?= mb_strtoupper($listStudent->name_student) ?> | <span class="font-weight-light"> <?= $edad ?> años </span>
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
                <div class="col-md-6 container-list-students menuStudent">
                    <div class="table-responsive" style="height:400px !important;">
                        <link rel="stylesheet" href="<?= $dir_syle ?>">

                        <nav class="menu">
                            <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open" />
                            <label class="menu-open-button" for="menu-open">
                                <img src="images/mental.png" alt="" width="100px">
                            </label>
                            <a data-toggle="modal" data-target="#infoGeneral" data-placement="top" title="Información general" class="menu-item purple"> <img src="images/gral_info.png" width="70px"></a>
                            <a data-toggle="modal" data-target="#infoAcademica" data-placement="top" title="Información académica" class="menu-item purple"> <img src="images/student_academic_info.png" width="70px"> </a>
                            <a data-toggle="modal" data-target="#infoCalificaciones" data-placement="top" title="Calificaciones" class="menu-item purple"><img src="images/certificate_info.png" width="70px"></a>
                            <a data-toggle="modal" data-target="#infoAsistencia" data-placement="top" title="Asistencias" class="menu-item purple"> <img src="images/stud_attendance.png" width="70px"> </a>
                            <a data-toggle="modal" data-target="#medicalInfo" data-placement="top" title="Información médica" class="menu-item purple"><img src="images/medica_info.png" width="70px"></a>
                            <a href="alumnos.php?submodule=psichopedagogy_students&student=<?= $listStudent->id_student ?>" target="_blank" data-placement="top" title="Fichas psicopedagógica" class="menu-item purple"> <img src="images/psychology.png" width="70px"> </a>


                        </nav>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'modals/infoGeneral.php';
include 'modals/calificaciones.php';
include 'modals/infoAsistencias.php';
include 'modals/infoAcademica.php';
include 'modals/medicalInfo.php';

include dirname(__DIR__ . '', 2) . '/justify_and_incidences/modals/modalIncidentsBreakdown.php';
include dirname(__DIR__ . '', 2) . '/justify_and_incidences/modals/modalBreakdown.php';
include dirname(__DIR__ . '', 2) . '/justify_and_incidences/modals/editBreakDown.php';
include dirname(__DIR__ . '', 2) . '/justify_and_incidences/modals/seguimientoIncidencias.php';
include dirname(__DIR__ . '', 2) . '/justify_and_incidences/modals/seguimientoInasistencia.php';

?>

<script src="js/functions/students/teachers/edit_week_attendance.js"></script>
<script src="js/functions/psichopedagogy/psichopedagogy.js"></script>
<script src="js\functions\student_archive\student_archive.js"></script>
<script>
    Swal.close();
</script>