<div class="modal fade" id="setSubject" tabindex="-1" role="dialog" aria-labelledby="setSubjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setSubjectLabel">Asignar materia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 id="title_modal_select"></h3>
                <br>
                <form>
                    
                    <select id="select_assignment" class="form-control" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Buscar ...">
                    <option selected disabled>Seleccione una opción</option>    
                    <?php foreach ($getAssignmentsByTeacher as $assignment) : ?>
                            <option id="<?= $assignment->id_assignment ?>"><?= $assignment->group_code ?> | <?= $assignment->name_subject ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary btnSetAssignment">Asignar</button>
            </div>
        </div>
    </div>
</div>