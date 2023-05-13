<div class="modal fade" id="new-event" tabindex="-1" role="dialog" aria-labelledby="new-event-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-secondary" role="document">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <form class="new-event--form">
          <div class="form-group">
            <label class="form-control-label">* Elija una asignatura</label>
            <select class="form-control" id="slct-assg" data-toggle="select">
              <option value="" disabled selected>Elija una opción</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-control-label">* Agregar un comentario</label>
            <textarea class="form-control form-control-alternative new-event--title textarea-autosize" rows="4" placeholder="Agregue un comentario"></textarea>
            <i class="form-group--bar"></i>
            <p class="text-muted mt-2 word-counter-new">Palabras: 0/300</p>
          </div>
          <div class="form-group">
            <label class="form-control-label">Anexar evidencia (url)</label>
            <input type="text" class="form-control form-control-alternative new-event--evidence-attached" placeholder="URL">
          </div>
          <input type="hidden" class="new-event--start" />
          <input type="hidden" class="new-event--end" />
        </form>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary new-event--add">Guardar<span class="spinner"></button>
          <button type="button" class="btn btn-link ml-auto" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- -->
  <div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-secondary" role="document">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
          <form class="edit-event--form">
            <div class="form-group mt-3">
              <label class="form-control-label">Asignatura</label>
              <input type="text" class="form-control form-control-alternative assignment-name-edit" disabled placeholder="Asignatura">
            </div>
            <div class="form-group">
              <label class="form-control-label">* Editar comentario</label>
              <textarea class="form-control form-control-alternative edit-event--title textarea-autosize" placeholder="Comentario"></textarea>
              <i class="form-group--bar"></i>
              <p class="text-muted mt-2 word-counter-edit">Palabras: 0/300</p>
            </div>
            <div class="form-group mt-3">
              <label class="form-control-label">Evidencia anexada (url)</label>
              <input type="text" class="form-control form-control-alternative edit-event--evidence-attached" placeholder="URL">
            </div>
            <input type="hidden" class="edit-event--id">
          </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button class="btn btn-primary update-event">Actualizar<span class="spinner"></button>
            <button class="btn btn-danger delete-event">Eliminar<span class="spinner"></span></button>
            <button class="btn btn-link ml-auto" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>