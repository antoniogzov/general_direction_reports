<div class="modal fade" id="studentDocuments" tabindex="-1" role="dialog" aria-labelledby="studentDocumentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentDocumentsLabel">Anexos de alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Alumno: <?= $listStudent->student_code ?> | <?= strtoupper($listStudent->name_student) ?></h3>
                <hr>
                <form>
                    <div class="table-responsive" id="divTableDocuments">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <label for="descripcion_documento_alumno">Descripción del archivo:</label>
                <input type="text" class="form-control" id="descripcion_documento_alumno" placeholder="Descripción del archivo">
                <input type="file" accept="application/pdf, image/png, image/jpg, image/jpeg" style="display:none" id="studentDocument" lang="es">
                <label class="btn btn-secondary " id="lblArchiveTracking" for="studentDocument"><i class="fa-solid fa-folder-plus"></i></label>
                <button type="button" class="btn btn-primary saveStudentDocument" data-id-student="<?= $listStudent->id_student ?>" >Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

            </div>
            <div>
                <a href="#" id="lblStudentDocument" style="display:none;" class="badge badge-pill badge-primary"></a>
            </div>
        </div>
    </div>
</div>