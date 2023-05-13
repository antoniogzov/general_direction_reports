<!-- Modal -->
<div class="modal fade" id="changePeriodAE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CAMBIAR AE DE PERIODO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Periodo destino</label>
                    <select class="form-control" id="selectChangePeriodAE">
                        <option selected value="" disabled>Elija una opción</option>
                        <?php foreach ($periods as $period) : ?>
                            <option value="<?= $period->id_period_calendar; ?>"><?= $period->no_period; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                <button type="button" class="btn btn-primary bntChangePeriodAE" data-dismiss="modal">CAMBIAR</button>
            </div>
        </div>
    </div>
</div>