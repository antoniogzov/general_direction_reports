<!-- Modal -->
<div class="modal fade" id="editAE" tabindex="-1" role="dialog" aria-labelledby="editAELabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAELabel">EDITAR APRENDIZAJE ESPERADO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Nombre de aprendizaje</label>
                    <input class="form-control" type="text" id="learning_name_edit">
                </div>

                <div class="form-group">
                    <label for="learning_description_edit">Descripción de aprendizaje esperado</label>
                    <textarea class="form-control" id="learning_description_edit" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                <button type="button" class="btn btn-primary saveChangesLearning" data-dismiss="modal">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </div>
</div>