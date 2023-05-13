<?php
$grants = $_SESSION['grantsITEQ'];
?>
<div class="modal fade" id="newPlan" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-default">NUEVO CRITERIO DE EVALUACIÓN </h3>
                <h6 class="modal-title text-muted" id="modal-title-default"><?= $getSubject->group_code . ' ' . $getSubject1->name_subject; ?></h6>
                <button type="button" id="cerrar_mdl_criterio" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="evaluation" class="form-label text-dark">Nombre</label>
                        <div class="input-group">
                            <select class="form-control" id="select_eval_name" name="evaluation" required="required">
                                <option value="">Seleccione un nombre de criterio</option>
                                <?= $queries->getEvaluationName($conn) ?>
                            </select>
                        </div>
                        <label for="AditionalName" id="LabelAditionalName" style="display: none" class="form-label text-dark">Nuevo nombre</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="AditionalName" style="display: none" name="AditionalName">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="percentage" id="" class="form-label text-dark">Asignar porcentaje</label>
                        <div class="input-group">
                            <input type="hidden" id="percentage_asigned" class="form-control">
                            <input type="number" name="percentage" id="percentage" class="form-control" placeholder="Ingrese un porcentaje...">
                        </div>
                        <h6 id="txt_percentage_asigned" style="color:#fb6340"></h6>
                    </div>
                    <div class="form-group">
                        <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                        <div class="input-group">
                            <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label text-dark">Método de captura de evaluación:</label>
                        <div class="input-group">
                            <select class="form-control" id="id_criterio">
                                <!-- <option value="1">Automático</option> -->
                                <option value="0" selected>Manual</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="evaluation" class="form-label text-dark">Tipo de evaluación</label>
                        <div class="input-group">
                            <select class="form-control" id="select_eval_type" name="evaluation" required="required">
                                <?= $queries->getEvaluationTypes() ?>
                            </select>
                        </div>
                        <label for="AditionalName" id="LabelAditionalName" style="display: none" class="form-label text-dark">Nuevo nombre</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="AditionalName" style="display: none" name="AditionalName">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" id="" class="form-label text-dark">¿Tomar en cuenta para la calificación final?</label>

                        <ul class="list-group list-group-horizontal list-group-flush">
                            <li class="list-group-item">
                                <label class="custom-toggle">
                                    <input type="checkbox" id="check_afectar_calificacion" checked>
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                </label>
                            </li>
                            <li class="list-group-item"></li>
                            <li class="list-group-item"></li>
                        </ul>
                        <div class="input-group">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subcriterios" id="lbl_check_subcriterios" class="form-label text-dark">¿Crear subcriterios?</label>

                        <ul class="list-group list-group-horizontal list-group-flush">
                            <li class="list-group-item">
                                <label class="custom-toggle">
                                    <input type="checkbox" id="check_subcriterios">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                </label>
                            </li>
                            <li class="list-group-item"></li>
                            <li class="list-group-item"></li>
                        </ul>
                        <div class="input-group">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subcriterios" id="lbl_subcriterios" class="form-label text-dark" style="display: none">Número de sub-criterios</label>

                        <div class="input-group">
                            <input type="number" class="form-control" id="subcriterios" name="subcriterios" onkeypress="return isNumberKey(event)" style="display: none" placeholder="Número de sub-criterios...">

                            <input type="hidden" id="assignment" name="assignment" value="<?= $sj ?>">
                            <input type="hidden" id="period" name="period" value="<?= $id_period ?>">
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-info" id="btn_guardar_criterio">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_elm" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-white">
            <div class="modal-header">
                <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar"></h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="id_ev_eliminar" value="">
                    <input type="hidden" name="subject" value="">

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="cerrar_m_eliminar">Cancelar</button>
                        <button type="button" class="btn btn-success" id="cont_delete">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_subcriterios" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h3 class="modal-title" id="modal-title-default">Editar subcriterios de evaluación.</h3>
            </div>
            <div class="modal-body">
                <h3 class="text-center">Asignar nuevo nombre:</h3><br />
                <form>
                    <div id="div_subcriterios"></div>
                </form>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn_actualizar_subcriterios">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar_b_actualizar">Volver</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-white">
            <div class="modal-header">
                <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar">Importar configuración de otro periodo</h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="evaluation" class="form-label text-dark">Periodo desde el que desa importar la configuración</label>
                    <select class="form-control" id="select_import_period" required="required">
                        <option value="">Seleccione el periodo</option>
                        <?= $queries->periodsWithEvPlan($id_level_combination, $sj); ?>
                    </select>
                    <input type="hidden" id="import_on_period" value="<?= $id_period ?>" />
                    <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                            <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_import_per_config">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_import_per_config">Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="export_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-white">
            <div class="modal-header">
                <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar">Exportar configuración a otro periodo</h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="evaluation" class="form-label text-dark">Seleccione los periodos a los se importarrá la configuración:</label>
                    <br>

                    <ul class="list-group">
                        <?= $queries->periodsWithoutEvPlan($id_period, $sj); ?>
                    </ul>
                    <input type="hidden" id="export_from_period" value="<?= $id_period ?>">
                    <input type="hidden" id="id_assignment_export" value="<?= $sj ?>">
                    <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_export_per_config">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_export_per_config">Exportar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modify_evaluation_plan" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h3 class="modal-title" id="modal-title-default">Editar plan de evaluación.</h3>
            </div>
            <div class="modal-body">
                <h3 class="text-center"></h3><br />
                <form>
                    <div id="div_subcriterios">
                        <div class="input-group">
                        </div>
                        <?php if (($grants & 8)) {
                            $visibility = "block";
                        } else {
                            $visibility = "none";
                        }
                        ?>
                        <div style="display: <?= $visibility ?>" class="form-group">
                            <label for="evaluation" class="form-label text-dark">Nombre</label>
                            <h5 class="form-label text-grey" id="txt_evaluation_nameOG"><em>Usted selecconó: </em></h5>
                            <div class="input-group">
                                <div class="input-group">
                                    <select class="form-control" id="name_edit_criteria" name="evaluation" required="required">
                                        <option value="">Seleccione un nombre de criterio</option>
                                        <?= $queries->getEvaluationName() ?>
                                    </select>
                                </div>
                                <h6 class="form-label text-red">*Obligatorio</h6>
                            </div>
                        </div>

                        <div class="form-group" id="div_manual_name_edit" style="display: none">
                            <label class="form-label text-dark">Nombre Manual</label>
                            <h5 class="form-label text-grey" id="txt_manual_nameOG"><em>Usted ingresó: </em></h5>
                            <div class="input-group">
                                <input type="text" class="form-control new_name_subcr" id="manual_name" required value="">
                            </div>
                            <h6 class="form-label text-red">*Obligatorio</h6>
                        </div>

                        <div style="display: <?= $visibility ?>" class="form-group">
                            <label for="evaluation" class="form-label text-dark">Tipo de evaluación </label>
                            <div class="input-group">
                                <select class="form-control" id="eval_type" name="evaluation">
                                    <?= $queries->getEvaluationTypes() ?>
                                </select>
                                <!-- <input type="text" data-original-name="' + name_og + '" class="form-control new_name_subcr" id="ep_X" required value="<?= $row->value_input_type ?>"> -->
                            </div>
                        </div>

                        <div style="display: <?= $visibility ?>" class="form-group">
                            <label for="evaluation" class="form-label text-dark">Porcentaje</label>
                            <h5 class="form-label text-grey" id="txt_percentageOG"><em>Usted ingresó: %</em></h5>
                            <div class="input-group">
                                <input type="hidden" id="edit_percentage_asigned" class="form-control">
                                <input type="number" class="form-control new_name_subcr" id="edit_percentage" required>
                            </div>
                            <h6 id="txt_percentage_asigned_edit" style="color:#fb6340"></h6>
                        </div>

                        <div class="form-group" id="div_gathering">

                        </div>

                        <div style="display: <?= $visibility ?>" class="form-group">
                            <label for="" id="" class="form-label text-dark">¿Tomar en cuenta para la calificación final?</label>

                            <ul class="list-group list-group-horizontal list-group-flush">
                                <li class="list-group-item">
                                    <label class="custom-toggle">
                                        <input type="checkbox" id="affect_final_calification">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                    </label>
                                </li>
                                <li class="list-group-item"></li>
                                <li class="list-group-item"></li>
                            </ul>
                        </div>

                        <div style="display: <?= $visibility ?>" class="form-group">
                            <label for="evaluation" class="form-label text-dark">Fecha de cierre</label>
                            <h5 class="form-label text-grey" id="txt_deadline"><em>Usted seleccionó: </em></h5>
                            <div class="input-group">
                                <input type="date" id="in_deadline" class="form-control new_name_subcr" required>
                            </div>
                        </div>

                    </div>
                    <div id="buttons">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="export_subject_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
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

                    <ul class="list-group">
                        <?= $queries->SubjectsWithoutEvPlan($academic_area); ?>
                    </ul>
                    <!-- <input type="hidden" id="export_from_period" value="<?= $id_period ?>">
                    <input type="hidden" id="id_assignment_export" value="<?= $sj ?>"> -->
                    <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_export_sbj_config" data-dismiss="export_subject_plan">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_export_sbj_config">Exportar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>