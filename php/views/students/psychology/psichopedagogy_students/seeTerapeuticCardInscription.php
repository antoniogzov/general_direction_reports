<?php $GetColaboradores = $psychopedagogy->GetColaboradores();
$GetPsychopedagogicalData = $GetPsychopedagogicalData[0];
$int_recourse_year = $GetPsychopedagogicalData->recourse_year;
if ($int_recourse_year == 1) {
    $recourse_year = 'Si';
} else {
    $recourse_year = 'No';
}

$int_have_had_previous_treatments = $GetPsychopedagogicalData->have_had_previous_treatments;
if ($int_have_had_previous_treatments == 1) {
    $have_had_previous_treatments = 'Si';
} else {
    $have_had_previous_treatments = 'No';
}


$social_performance = $GetPsychopedagogicalData->social_performance;
$schoolar_performance = $GetPsychopedagogicalData->schoolar_performance;
$behavior_performance = $GetPsychopedagogicalData->behavior_performance;

if (strlen($social_performance . $schoolar_performance . $behavior_performance) > 0) {
}
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="modal fade" id="seeTerapeuticCardInscription" role="dialog" aria-labelledby="infoInterviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role=" document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="infoInterviewLabel">Ficha de la inscripción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Alumno: <?= $listStudent->student_code ?> | <?= $listStudent->name_student ?></h3>
                <hr>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-transparent" style="background-color:#161541 !important;">
                            <h3 class="mb-0 text-uppercase" style=" color:white !important;">Información de la inscripción</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                </div>
                                <div class="col"></div>
                                <div class="col"></div>
                            </div>
                            <div class="row">

                                <div class="col">
                                    <p class="lead"><strong>¿Ha repetido año? : </strong> <?= $recourse_year ?></p>
                                    <p class="lead"><strong>Desempeño social del alumno : </strong> <?= $social_performance ?></p>

                                    <p class="lead"><strong>Desempeño académico : </strong> <?= $schoolar_performance ?></p>
                                </div>
                                <div class="col">
                                    <p class="lead"><strong>Desempeño conductual : </strong> <?= $behavior_performance ?></p>
                                    <p class="lead"><strong>¿Ha tenido alguna intervención terapéutica antes? : </strong> <?= $behavior_performance ?></p>
                                </div>

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
                <button title="Cancelar" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                <br>
            </div>
        </div>
    </div>
</div>