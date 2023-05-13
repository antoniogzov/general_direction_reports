<?php
/* RECIBE PLAN DE EVALUACION POR ID DE ASIGNATURA */
require_once '../../../general/php/models/Connection.php';
include_once '../models/queries.php';
$db = new data_conn;
$num = new queries;

$conn = $db->dbConn();

$sql = $_POST['sql'];
$exect = $conn->query($sql);

foreach ($exect as $row) : ?>
    <?php $number = $row['id_evaluation_plan'] ?>
    <div class="card-body">
        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
            <div class="timeline-block">
                <span class="timeline-step badge-danger">
                    <i class="fas fa-bookmark"></i>
                </span>
                <div class="timeline-content">
                    <span class="badge badge-pill badge-danger"><?= $row['percentage'] ?>%</span>
                    <?php
                    if ($row['value_input_type'] == 0) {
                        $tipo = 'Manual'; ?>
                        <span class="badge badge-pill badge-danger"><?= $tipo ?></span>
                    <?php
                    } else {
                        $tipo = 'Subcriterio'; ?>
                        <span class="badge badge-pill badge-success"><?= $tipo ?></span>
                    <?php
                    }  ?>

                    <!-- Modal DELETE -->
                    <button class="badge badge-pill btn-danger btn btn-sm float-right" data-toggle="modal" data-target="#delete<?= $number ?>"><i class="far fa-minus-square"></i> Eliminar</button>

                    <div class="modal fade" id="delete<?= $number ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                            <div class="modal-content bg-gradient-white">
                                <div class="modal-header">
                                    <h6 class="modal-title text-dark text-lg-center" id="modal-title-default">¿Desea eliminar criterio de evaluación <?= $number ?>?</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="php/models/delete.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $number ?>">
                                        <input type="hidden" name="subject" value="<?= $sj ?>">
                                        <div class="modal-footer justify-content-center">
                                            <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <input type="submit" class="btn btn-danger" value="Eliminar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $evaluation_type = $num->getEvaluationName();
                    $selected = $num->evName($number);
                    $percentage = $num->evPercentage($number);
                    $input = $num->evInput($number);
                    ?>
                    <!-- MODAL UPDATE -->
                    <button class="badge badge-pill btn btn-light btn-sm float-right" data-toggle="modal" data-target="#updatePlan<?= $number ?>"><i class="far fa-edit"></i> Editar</button>

                    <div class="modal fade" id="updatePlan<?= $number ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                            <div class="modal-content" style="background: #E38C5D">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">Modificar criterio de evaluación <?= $number ?></h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="php/models/update.php" method="POST" enctype="multipart/form-data">
                                        <label for="evaluation" class="form-label text-dark"> Nombre actual</label>
                                        <select class="form-control" name="evaluation" onchange="habilitar(this.value);" required="required">
                                            <option value=""><?= $selected ?></option>
                                            <?= $evaluation_type ?>
                                        </select>
                                        <label for="AditionalName" id="LabelAditionalName" class="form-label text-dark" style="display: none">Nuevo nombre</label>
                                        <input type="text" class="form-control" id="AditionalName" name="AditionalName" style="display: none">
                                        <label for="percentage" class="form-label text-dark  mt-2"> Porcentaje asignado (%)</label>
                                        <input type="number" class="form-control" name="percentage" placeholder="<?= $percentage ?>" required="required">
                                        <label for="type" class="form-label text-dark mt-2">Método de captura de dato</label>
                                        <select class="form-control" name="type">
                                            <option value=""><?= $input ?></option>
                                            <option value="0">Manual</option>
                                            <option value="1">Subcriterio</option>
                                        </select>
                                        <input type="hidden" name="assignment" value="<?= $sj ?>">
                                        <input type="hidden" name="id_plan" value="<?= $number ?>">
                                        <label for="fechaFin" class="form-label text-dark mt-2">Fecha de cumplimiento</label>
                                        <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                                        <div class="modal-footer justify-content-center">
                                            <input type="submit" class="btn btn-primary" value="Actualizar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-white mt-2 mb-0"><?= $row['evaluation_name'] ?></h5>
                </div>
            </div>
        </div>
    </div>
    <!-- <div data-toggle="notify" data-placement="top" data-align="center" data-type="success" data-icon="ni ni-bell-55"> Success</div>-->
<?php
endforeach;

?>