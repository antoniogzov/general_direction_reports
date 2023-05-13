<!-- Modal -->
<div class="modal fade" id="absenceDocumentList" tabindex="-1" role="dialog" aria-labelledby="absenceDocumentLabelList" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="absenceDocumentLabelList">Lista de documentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 id="info_student_name"></h2>
                <h3 id="info_student_code"></h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Documentos</h3>
                                <button type="button" class="btn btn-primary btnAddDocumentList" data-dismiss="modal">Agregar documento</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="documentList">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>