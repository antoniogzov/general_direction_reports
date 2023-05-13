<div class="modal fade" id="export_subject_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-white">
            <div class="modal-header">
                <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar">Exportar configuración a otras asignaturas</h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="evaluation" class="form-label text-dark">Seleccione las asignaturas a las que se importará la configuración:</label>
                    <br>
                    <input type="hidden" id="export_from_ass">
                    <ul class="list-group">
                        <?= $queries->SubjectsWithoutEvPlan($id_academic_area); ?>
                    </ul>
                    <!-- <input type="hidden" id="export_from_period" value="<?= $id_period ?>">
                    <input type="hidden" id="id_assignment_export" value="<?= $sj ?>"> -->
                    <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_export_sbj_config">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_export_sbj_config">Exportar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>