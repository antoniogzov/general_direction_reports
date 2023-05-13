<div class="modal fade" id="newParentTracking" tabindex="-1" role="dialog" aria-labelledby="newParentTrackingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newParentTrackingLabel">Registrar seguimiento con padres</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Alumno: <?= $listStudent->student_code ?> | <?= $listStudent->name_student ?></h3>
                <hr>
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Seguimiento a: <span class="badge badge-warning">Obligatorio</span></label>

                        <br>
                        <br>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="PADRES" id="seg_padres" name="seguimiento_a" class="custom-control-input" checked>
                            <label class="custom-control-label" for="seg_padres">Padres</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="ALUMNOS" id="seg_alumnos" name="seguimiento_a" class="custom-control-input">
                            <label class="custom-control-label" for="seg_alumnos">Alumnos</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Motivo del seguimiento: <span class="badge badge-warning">Obligatorio</span></label>
                        <input type="text" class="form-control" id="motivo" placeholder="Ingrese su respuesta">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Responsable de seguimiento: <span class="badge badge-warning">Obligatorio</span></label>
                        <input type="text" class="form-control" id="responsable_seguimiento" placeholder="Ingrese su respuesta">
                    </div>
                    <div class="form-group">
                        <label for="tipo_seguimiento">Tipo de seguimiento <span class="badge badge-warning">Obligatorio</span></label>
                        <select class="form-control" id="tipo_seguimiento">
                            <option selected disabled value="">Seleccione una opción</option>
                            <?php foreach ($getKindsTracking as $kind_tracking) : ?>
                                <option value="<?= $kind_tracking->id_tracking_type ?>"><?= $kind_tracking->description_tracking_type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">Fecha de contacto: <span class="badge badge-warning">Obligatorio</span></label>
                                <input type="date" class="form-control" id="fecha_contacto" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Descripción: <span class="badge badge-primary">Opcional</span></label>
                        <textarea class="form-control" id="descripcion" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Acuerdos: <span class="badge badge-primary">Opcional</span></label>
                        <textarea class="form-control" id="acuerdos" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" data-id-student="<?= $listStudent->id_student ?>" data-dismiss="modal" class="btn btn-primary savePaternsTracking">Guardar</button>
            </div>
        </div>
    </div>
</div>