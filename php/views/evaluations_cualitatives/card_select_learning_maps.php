<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Mapas de aprendizaje</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_learning_map">* Elija un mapa de aprendizaje</label>
                    <form>
                        <select class="form-control" name="slct_learning_map" id="slct_learning_map">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php foreach ($learningMaps as $map) : ?>
                                <?php if ($ascc_lm_assgn == $map->ascc_lm_assgn) : ?>
                                    <option selected value="<?= $map->ascc_lm_assgn ?>"><?= $map->learning_map_name ?></option>
                                <?php else : ?>
                                    <option value="<?= $map->ascc_lm_assgn ?>"><?= $map->learning_map_name ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_topic">* Elija un tema</label>
                    <form>
                        <select class="form-control" name="slct_topic" id="slct_topic">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($listGroupQuestions)) : ?>
                                <?php foreach ($listGroupQuestions as $group) : ?>
                                    <?php if ($assc_mpa_id == $group->assc_mpa_id) : ?>
                                        <option selected value="<?= $group->assc_mpa_id ?>"><?= mb_strtoupper($group->name_question_group) ?></option>
                                    <?php else : ?>
                                        <option value="<?= $group->assc_mpa_id ?>"><?= mb_strtoupper($group->name_question_group) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($assc_mpa_id == 'comments'): ?>
                                    <option value="comments" selected>COMENTARIOS FINALES</option>
                                <?php else: ?>
                                    <option value="comments">COMENTARIOS FINALES</option>
                                <?php endif; ?>
                            <?php endif; ?>
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
                            <?php if ($installment > 0) : ?>
                                <?php for ($i=1; $i<$installment; $i++) : ?>
                                    <?php if ($no_installment == $i) : ?>
                                        <option selected value="<?= $i ?>"><?= $i ?></option>
                                    <?php else : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>