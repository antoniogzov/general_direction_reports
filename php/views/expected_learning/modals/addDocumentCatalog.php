<!-- Modal -->
<div class="modal fade" id="addCatalogDocument" tabindex="-1" role="dialog" aria-labelledby="addCatalogDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCatalogDocumentLabel">Agregar evidencia de aprendizaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Adjuntar evidencia para: </h3>
                <h1 id="info_catalog_item_name"></h1>
                <br>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio_upload_archive" value="radio_upload_archive" name="radio_upload" class="custom-control-input">
                    <label class="custom-control-label" for="radio_upload_archive">Subir Archivo</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio_upload_url" value="radio_upload_url" name="radio_upload" class="custom-control-input">
                    <label class="custom-control-label" for="radio_upload_url">Adjuntar URL</label>
                </div>
                <hr>
                <div id="div_archivo" style="display:none;">
                    <input type="hidden" id="moduleDocument" value="absences_voucher">
                    <div class="custom-file">
                        <input type="file" class="inputAddStudentDocument" accept="application/pdf, image/png, image/jpg, image/jpeg" id="comprobante_catalogo" lang="es">
                        <label class="custom-file-label" for="comprobante_catalogo">Elegir un archivo</label>
                    </div>

                </div>
                <div id="div_url" style="display:none;">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Ingresar URL</label>
                        <input type="text" class="form-control" id="url" placeholder="URL">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary uploadCatalogDocument" data-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>