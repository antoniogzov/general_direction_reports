<?php
/* include '/card_select_edit_week_attendance.php'; */
$listStudent = array();
function decrypt($string, $key)
{
    $result = '';
    $string = base64_decode($string);
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
    }
    return $result;
}
$valid_colab = 0;
$md5_colab = $_GET['colabs'];
$arr_colabs = explode('-', decrypt($md5_colab, 'wykt2022'));

foreach ($arr_colabs as $colabs_for) {
    if ($colabs_for == $_SESSION['colab']) {
        $valid_colab++;
    }
}
if (isset($_GET['id_terapheutic_card'])) {




    $id_terapheutic_card = $_GET['id_terapheutic_card'];
    $GetStudentTerapeuticCards = $psychopedagogy->GetStudentTerapeuticCardsByID($id_terapheutic_card);
    $id_student = $GetStudentTerapeuticCards[0]->id_student;
    $listStudent = $psychopedagogy->getStudentInfo($id_student);
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


    $dias = explode("-", $tfecha, 3);
    $dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
    $edad = (int)((time() - $dias) / 31556926);
}

?>

<?php $dir_syle = 'php/views/students/psychology/challenges/dist/style.css'; ?>
<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://kit.fontawesome.com/e568464256.js" crossorigin="anonymous"></script>
<?php if ($valid_colab > 0) : ?>
    <div class="card">
        <div class="card-body">
            <?php if (!empty($listStudent)) : ?>
                <div class="row">
                    <div class="col-md-9 container-list-students">
                        <a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" hidden data-id-card="<?= $id_terapheutic_card ?>" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a>
                        <div class="modal fade" id="seguimientoInterview" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="seguimientoInterviewLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role=" document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="seguimientoInterviewLabel">Seguimiento de intervención terapéutica</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h3>Alumno: <?= $listStudent->student_code ?> | <?= $listStudent->name_student ?></h3>
                                        <hr>
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header bg-transparent">
                                                    <h3 class="mb-0">Seguimiento</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="timeline timeline-one-side trackingInterview" data-timeline-content="axis" data-timeline-axis-style="dashed">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer divAddArchive" id="" style="display:none">
                <div class="custom-file">
                    
                    <label class="btn btn-secondary " id="lblArchiveTracking" for="archiveTtrtacking"><i class="fa-solid fa-folder-plus"></i></label>
                </div>
            </div> -->
                                    <div class="modal-footer">
                                        <input type="hidden" id="id_teacher_tracking" value="<?= $_SESSION['colab'] ?>">
                                        <input type="hidden" id="teacher_name_registered_tracking" value="<?= $infoCol->name ?>">
                                        <label for="comentario_seguimientos">Comentario:</label>
                                        <textarea class="form-control" id="comentario_seguimientos" rows="3"></textarea>
                                        <input type="file" accept="application/pdf, image/png, image/jpg, image/jpeg" style="display:none" id="archiveTtrtacking" lang="es">
                                        <label class="btn btn-secondary " id="lblArchiveTracking" for="archiveTtrtacking"><i class="fa-solid fa-folder-plus"></i></label>
                                        <button type="button" class="btn btn-success commentaryTracingInterview" id="">Enviar</button>
                                        <button type="button" class="btn btn-primary closeTracking" data-dismiss="modal">Cerrar</button>
                                        <br>
                                    </div>
                                    <div>
                                        <a href="#" id="lblArchivo" style="display:none;" class="badge badge-pill badge-primary"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <script>
        
        Swal.fire({
            title: 'Error!',
            text: 'Al parecer su usuario no tiene permisos para acceder a esta sección',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            loading();
            if (result.isConfirmed) {
                window.location.href = 'alumnos.php';
            }
        });

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
    </script>
<?php endif; ?>
<script src="js/functions/psichopedagogy/psichopedagogy.js"></script>
<script src="js/functions/psichopedagogy/psichopedagogy_shared.js"></script>

<script src="js/functions/psichopedagogy/select2.js"></script>
<script src="js/functions/student_archive/student_archive.js"></script>