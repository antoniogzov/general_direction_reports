<!-- Modal -->
<div class="modal fade" id="newAE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">REGISTRAR APRENDIZAJE ESPERADO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="example-text-input" id="lbl_learning_name" class="form-control-label">Nombre de aprendizaje</label>
                    <input class="form-control" type="text" value="" id="learning_name">
                </div>

                <div class="form-group">
                    <label for="learning_description">Descripción de aprendizaje esperado</label>
                    <textarea class="form-control" id="learning_description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                <button type="button" class="btn btn-primary saveLearning" data-dismiss="modal">GUARDAR APRENDIZAJE</button>
            </div>
        </div>
    </div>
</div>