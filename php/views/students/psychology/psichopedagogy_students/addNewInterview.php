<div class="modal fade" id="addNewInterview" tabindex="-1" role="dialog" aria-labelledby="addNewInterviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewInterviewLabel">Registrar nueva intervención terapéutica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Alumno: <?= $listStudent->student_code ?> | <?= $listStudent->name_student ?></h3>
                <hr>
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Terapeuta: <span class="badge badge-warning">Obligatorio</span></label>
                        <input type="text" class="form-control" id="terapeuta" placeholder="Ingrese su respuesta">
                    </div>
                    <div class="form-group">
                        <label for="tipo_intervencion">Tipo de intervención <span class="badge badge-warning">Obligatorio</span></label>
                        <select class="form-control" id="tipo_intervencion">
                            <option selected disabled value="">Seleccione una opción</option>
                            <?php foreach ($getKindsInterviews as $kindInterview) : ?>
                                <option value="<?= $kindInterview->id_kinds_interview ?>"><?= $kindInterview->description ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        
                    <label for="exampleFormControlInput1">Quién lo refirió: <span class="badge badge-primary">Opcional</span></label>
                        <input type="text" class="form-control" id="referido_por" placeholder="Ingrese su respuesta">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Motivo: </label>
                        <input type="text" id="motivo" class="form-control" placeholder="Ingrese su respuesta">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">Fecha de inicio: <span class="badge badge-warning">Obligatorio</span></label>
                                <input type="date" class="form-control" id="fecha_inicio" placeholder="Ingrese su respuesta">
                            </div>
                            <div class="col">
                                <label for="exampleFormControlInput1">Fecha de fin: <span class="badge badge-primary">Opcional</span></label>
                                <input type="date" class="form-control" id="fecha_fin" placeholder="Ingrese su respuesta">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="causa_cocluyo">Causa por la que concluyó: <span class="badge badge-primary">Opcional</span></label>
                        <select class="form-control" id="causa_cocluyo">
                            <option selected disabled value="">Seleccione una opción</option>
                            <?php foreach ($reasonsWhyConclused as $reason_why_conclused) : ?>
                                <option value="<?= $reason_why_conclused->id_reason ?>"><?= $reason_why_conclused->description ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" data-id-student="<?= $listStudent->id_student ?>" data-dismiss="modal" class="btn btn-primary saveInterview">Guardar</button>
            </div>
        </div>
    </div>
</div>