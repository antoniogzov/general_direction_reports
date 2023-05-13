<div class="modal fade" id="newIncident" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-default">GENERAR REPORTE DE INCIDENCIA </h3>
                <h6 class="modal-title text-muted" id="txt_modal_incidence">GRUPO | ALUMNO</h6>
                <button type="button" class="btn-close btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>

            <div class="modal-body">
                <form id="form_incidents">
                    <div class="form-group">
                        <label for="evaluation" class="form-label text-dark">Clasificación</label>


                        <div style="height:200px; width:95%; overflow:auto;">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <?php foreach ($IncidentsClasif as $clasification) : ?>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-heading<?= $clasification->id_incident_clasification ?>">
                                            <button class="btn btn-outline-<?= $clasification->bootstrap_class ?> btn-lg btn-block accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clas_<?= $clasification->id_incident_clasification ?>" aria-expanded="false" aria-controls="clas_<?= $clasification->id_incident_clasification ?>">
                                                <?= $clasification->incident_subclasification ?>
                                            </button>
                                        </h2>
                                        <div id="clas_<?= $clasification->id_incident_clasification ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $clasification->id_incident_clasification ?>" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <?php
                                                $listByClasification = $attendance->getListIncidentsClasif($clasification->incident_subclasification, $clasification->id_incident_clasification);
                                                ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($listByClasification as $clasif_list) : ?>
                                                        <li class="list-group-item">
                                                            <div class="custom-control custom-radio mb-3">
                                                                <input name="incident_list" class="custom-control-input" value="<?= $clasif_list->id_incidence_code ?>" data-id="li<?= $clasif_list->id_incidence_code ?>" id="li<?= $clasif_list->id_incidence_code ?>" type="radio">
                                                                <label class="custom-control-label" for="li<?= $clasif_list->id_incidence_code ?>"><?= $clasif_list->incident_description ?> | <?= $clasif_list->incident_description_detail ?></label>
                                                            </div>
                                                        </li>
                                                    <?php endforeach ?>
                                                    <br>
                                                    <br>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fechaFin" class="form-label text-dark">¿Ocurrió durante una clase?</label>
                        <div class="input-group">
                            <label class="custom-toggle">
                                <input type="checkbox" id="check_subject_incident">
                                <span class="custom-toggle-slider rounded-circle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="div_materia_incidente" style="display:none;">
                        <label class="form-control-label" for="id_group">* Elija una materia</label>
                        <form>
                            <select class="form-control" name="id_subject_incident" id="id_subject_incident">
                                <option selected value="" disabled>Elija una opción</option>
                            </select>
                        </form>
                    </div>

                    <div class="form-group">
                        <label for="fechaFin" class="form-label text-dark">Fecha de incidencia</label>
                        <div class="input-group">
                            <input type="date" name="date" value="<?= date('Y-m-d') ?>" id="incident_date" class="form-control">
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="fechaFin" class="form-label text-dark">Descripción de lo ocurrido</label>
                        <span class="badge badge-warning" id="lbl_longitud">Dispone de 500 caracteres para describir lo ocurrido</span>
                        <br>

                        <textarea class="form-control" aria-label="With textarea" id="incident_commit" maxlength="500"></textarea>

                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-info btn_save_incident" id="">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>