<div class="modal fade" id="seguimientoPadresChat" tabindex="-1" role="dialog" aria-labelledby="seguimientoPadresChatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role=" document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seguimientoPadresChatLabel">Seguimiento con padres de familia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Alumno: <?= $listStudent->student_code ?> | <?= mb_strtoupper($listStudent->name_student) ?></h3>
                <hr>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Seguimiento</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-one-side trackingWithParents" data-timeline-content="axis" data-timeline-axis-style="dashed">
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
                <input type="hidden" id="id_teacher_tracking_parents" value="<?= $_SESSION['colab'] ?>">
                <input type="hidden" id="teacher_name_registered_tracking" value="<?= $infoCol->name ?>">
                <label for="comentario_seguimientos">Comentario:</label>
                <textarea class="form-control" id="comentario_seguimientos_padres" rows="3"></textarea>
                <input type="file" accept="application/pdf, image/png, image/jpg, image/jpeg" style="display:none" id="archiveTtrtackingParents" lang="es">
                <label class="btn btn-secondary " id="lblArchiveTrackingParents" for="archiveTtrtackingParents"><i class="fa-solid fa-folder-plus"></i></label>
                <button type="button" class="btn btn-success saveComentaryParents" id="">Enviar</button>
                <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
                <br>
            </div>
            <div>
                <a href="#" id="lblArchivoParents" style="display:none;" class="badge badge-pill badge-primary"></a>
            </div>
        </div>
    </div>
</div>