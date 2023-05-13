<?php
if (!empty($id_level_combination = $helpers->getIdsLevelCombination($sj))) {
    $id_level_combination = $id_level_combination->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
}
$academic_area = $_GET['ac_ar'];
?>
<input type="hidden" id="no_colabt" value="<?=$_SESSION['colab']?>">
<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Periodos</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    <label class="form-control-label" for="id_period_calendar">* Elija un periodo</label>
                    <select name="" class="form-control col-6" id="select_periodo">
                        <option value="">Periodo:</option>
                        <?= $queries->periods($id_level_combination); ?>
                    </select>
                </div>
            </div>
            <!-- -->
            <?php if ($grants & 8): ?>
            <div class="col-md" id="btn_agregar_criterio" style="display:none;">
                <!-- <button  class="btn btn-sm btn-neutral" id="btn_new_plan" data-toggle="modal" data-target="#newPlan" >
            <i class="fas fa-book"></i>
                    Calificaciones
                </button> -->
                <?= $queries->visibilityImportButton($sj, $id_period) ?>

                <button class="btn btn-sm btn-success" id="btn_new_plan" data-toggle="modal" data-target="#newPlan">
                    <i class="fas fa-plus"></i>
                    Añadir criterio de evaluación
                </button>

                <?php if(isset($_GET['id_assignment']) && $academic_area != '' && isset($_GET['id_period'])): ?>
                <a href="?ac_ar=<?= $academic_area; ?>&id_assignment=<?= $_GET['id_assignment']; ?>&id_period=<?= $_GET['id_period']; ?>&model_calc=1" class="btn btn-sm btn-warning">
                    <i class="fab fa-buromobelexperte"></i>
                    Modelos de cálculo
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- -->
<!-- -->
<div class="row">
    <div class="col-md-12">
        <div class="card-wrapper" id="div_criterios" style="display: none">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0">Criterios de Evaluación</h5>
                    <?= $queries->percentageBar($sj, $id_period) ?>
                </div>

                <div id="div_planes" class="card-body">
                    <div id="div_s_and_ass">
                        <input type="hidden" id="id_period_selected" value="<?= $id_period ?>">
                        <input type="hidden" id="id_assignment" value="<?= $sj ?>">
                        <input type="hidden" id="id_academic_area" value="<?= $academic_area ?>">
                    </div>
                    <div class="row">
                        <?= $queries->getPlan($sj, $id_period) ?>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--<div class="col-lg-6">
        <div class="card bg-gradient-default shadow" id="div_grafica" style="display:none;">
            <div class="card-header bg-transparent">
                Título
            </div>
            <div class="chart">
                <canvas id="chart-pie" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>-->
</div>
<?php
include 'modals.php';
include 'modals/modalExportToAnotherSubject.php';
?>

<script src="js/functions/evaluation_plan/exporEvalPlan.js"></script>