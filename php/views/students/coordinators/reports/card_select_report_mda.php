

<script src="https://kit.fontawesome.com/2baa365664.js" crossorigin="anonymous"></script>
<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Reportes MDA</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row" id="card_select_body">
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_group">* Elija un grupo</label>
                    <form>
                        <select class="form-control" name="slct_group" id="slct_group">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?= $group->id_group ?>"><?= $group->group_code ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_report">* Elija un reporte</label>
                    <form>
                        <select class="form-control" name="slct_report" id="slct_report">
                            <option selected value="" disabled>Elija una opción</option>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_student">* Elija un alumno</label>
                    <form>
                        <select class="form-control" data-toggle="select" name="slct_student" id="slct_student">
                            <option value="">Elija una opción</option>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_installment">* Elija una entrega</label>
                    <form>
                        <select class="form-control" name="slct_installment" id="slct_installment">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php for ($i=1; $i<$installment; $i++) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div id="div_button" class="col-md-4"><div class="form-group"></div></div>
        </div>
    </div>
</div>
<div id="div_content_mda" class="div_content_mda card-wrapper"></div>