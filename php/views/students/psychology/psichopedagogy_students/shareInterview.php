<?php $GetColaboradores = $psychopedagogy->GetColaboradores(); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="modal fade" id="shareInterview" role="dialog" aria-labelledby="shareInterviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role=" document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="shareInterviewLabel">Compartir información y seguimiento de intervención</h5>
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
                        <div class="card-body" id="bodyShareInterview">

                        </div>
                        <div class="card-body">
                            <select class="form-control" data-toggle="select" multiple data-placeholder="Elegir destinatarios ..." id="colaboradores_mails">
                                <?php foreach ($GetColaboradores as $colaborador) : ?>
                                    <option value="<?= $colaborador->no_colaborador ?>"><?= $colaborador->colaborador ?> (<?= $colaborador->correo_institucional ?>)</option>
                                <?php endforeach; ?>
                            </select>
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
                <button title="Cancelar" type="button" class="btn btn-danger " data-dismiss="modal"><i class="fa-solid fa-rectangle-xmark"></i></button>
                <button title="Enviar invitación" type="button" class="btn btn-primary sendInterviewMails" id=""><i class="fa-solid fa-paper-plane"></i></button>
                <br>
            </div>
            <div>
                <a href="#" id="lblArchivo" style="display:none;" class="badge badge-pill badge-primary"></a>
            </div>
        </div>
    </div>
</div>
<style>
    .select2-selection__choice {
        background-color: #5e72e4 !important;
        border-color: #5e72e4 !important;
        color: #fff !important;
    }
</style>