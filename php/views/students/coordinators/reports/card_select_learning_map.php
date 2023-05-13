<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Mapas de aprendizaje</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_learning_map">* Elija un mapa de aprendizaje</label>
                    <form>
                        <select class="form-control" name="slct_learning_map" id="slct_learning_map">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php foreach ($lmps as $map) : ?>
                                <option value="<?= $map->id_learning_map ?>"><?= $map->learning_map_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_group">* Elija un grupo</label>
                    <form>
                        <select class="form-control" name="slct_group" id="slct_group">
                            <option selected value="" disabled>Elija una opción</option>
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
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_option_student">* Elija una opción</label>
                    <form>
                        <select class="form-control" data-toggle="select" name="slct_option_student" id="slct_option_student">
                            <option selected disabled value="0">Elija una opción</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="div_content_mda card-wrapper"></div>