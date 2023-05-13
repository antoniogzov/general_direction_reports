<!-- Modal -->
<div class="modal fade" id="addAbsenceDocument" tabindex="-1" role="dialog" aria-labelledby="addAbsenceDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="addAbsenceDocumentLabel">Agregar comprobante de inasistencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 id="info_student_name"></h2>
                <h3 id="info_student_code"></h3>
                <br>
                <input type="hidden" id="moduleDocument" value="absences_voucher">
                    <div class="custom-file">
                        <input type="file" class="inputAddStudentDocument" accept="application/pdf, image/png, image/jpg, image/jpeg" id="comprobante_inasistencia" lang="es">
                        <label class="custom-file-label" for="comprobante_inasistencia">Elegir un archivo</label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary uploadStudentDocument" data-dismiss="modal" >Guardar</button>
            </div>
        </div>
    </div>
</div>