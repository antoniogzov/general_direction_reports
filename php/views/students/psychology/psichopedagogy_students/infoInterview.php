<?php $GetColaboradores = $psychopedagogy->GetColaboradores(); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="modal fade" id="infoInterview" role="dialog" aria-labelledby="infoInterviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role=" document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="infoInterviewLabel">Compartir información y seguimiento de intervención</h5>
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
                            <h3 class="mb-0 text-uppercase" style=" color:white !important;">Información de intervención</h3>
                        </div>
                        <div class="card-body" id="bodyinfoInterview2">

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