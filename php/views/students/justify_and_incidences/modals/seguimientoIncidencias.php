<!-- Modal -->
<div class="modal fade" id="seguimientoIncidencias" data-bs-backdrop="false" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="seguimientoIncidenciasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="seguimientoIncidenciasLabel">Seguimiento de Incidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="mb-0" id="header_lbl_tracking_incidents"></h3>
                    </div>
                    <div class="card-body" style="height:500px; overflow: auto;">
                        <div id="div_timeline_incidents" class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">

                        </div>
                    </div>
                </div>
            </div>
            <?php /* var_dump($_SESSION); */ ?>
            <div class="modal-footer">
                <input type="hidden" id="id_teacher_tracking" value="<?= $_SESSION['colab'] ?>">
                <input type="hidden" id="teacher_name_registered_tracking" value="<?= $_SESSION['user_name'] ?>">
                <label class="form-control-label" for="comentario_seguimiento">Comentario:</label>
                <input type="text" class="form-control" id="comentario_seguimiento" placeholder="Ingrese un nuevo comentario">
                <button type="button" class="btn btn-success commentaryTracingIncidents">Enviar</button>
                <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Important part */
    .modal-dialog {
        overflow-y: initial !important
    }

    .modal-body {
        height: 80vh;
        overflow-y: auto;
    }
</style>