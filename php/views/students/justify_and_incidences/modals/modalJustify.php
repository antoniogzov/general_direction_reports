<!-- Modal -->
<?php
$justifyTypes = $attendance->getAllJustifyTypes();
?>

<div class="modal fade" id="modalJustify" tabindex="-1" role="dialog" aria-labelledby="modalJustifyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-uppercase" style="color: #FFFFFF; " id="modalJustifyLabel">Agregar ausencias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 id="lbl_std_name"></h3>
                <br>

                <div class="form-group">
                    <label for="txt_justify">FECHA A APLICAR</label>
                    <h6 style="color:red !important;">CAMPO OBLIGATORIO *</h6>
                    <br>
                    <input type="text" class="form-control" id="dates_apply" name="datetimes" />
                </div>
                <div class="form-group">
                    <label for="txt_justify">MOTIVO DE AUSENCIA</label>
                    <h6 style="color:red !important;">CAMPO OBLIGATORIO *</h6>
                    <select class="form-control" id="id_excuse_types">
                        <option value="" disabled selected>Seleccione una opción</option>
                        <?php foreach ($justifyTypes as $justifyType) : ?>
                            <option value="<?php echo $justifyType->id_excuse_types; ?>"><?php echo $justifyType->excuse_description; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="txt_justify">¿JUSTIFICADA?</label>
                    <br>
                    <br>
                    <label class="custom-toggle">
                        <input type="checkbox" id="checknIndexJustified" checked>
                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Si"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label for="txt_justify">COMENTARIO (OPCIONAL)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Comentario</span>
                        </div>
                        <textarea class="form-control" id="teacher_commit" aria-label="With textarea"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn btn-success btn_SaveJustify">GUARDAR</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        $('input[name="datetimes"]').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'DD/M/YYYY hh:mm A'
            }
        });

    });
</script>