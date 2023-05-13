<?php
if (!empty($id_level_combination = $helpers->getIdsLevelCombination($sj))) {
    $id_level_combination = $id_level_combination->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    $catalogue_exist = $expected_learnings->getExistCatalog($_GET['id_assignment']);
    $catalogue_exist_count = $expected_learnings->getExistCatalogAssignmentsIndex($_GET['id_assignment']);
}
$academic_area = $_GET['id_academic_area'];
?>
<input type="hidden" id="no_colabt" value="<?= $_SESSION['colab'] ?>">
<input type="hidden" id="id_assignment" value="<?= $_GET['id_assignment'] ?>">
<input type="hidden" id="id_academic_area" value="<?= $academic_area ?>">
<!-- -->
<!-- -->

<script type="text/javascript" charset="utf-8" src="js/vendor/jquery-ui/jquery-ui.min.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="card-wrapper" id="div_criterios">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0">Aprendizajes Esperados</h5>
                    <br>
                    <?php if (isset($catalogue_exist_count)) : ?>
                        <?php if (!empty($catalogue_exist_count)) :
                            $today_gral = date("Y-m-d");
                            $end_date_gral = $periods[0]->grade_closing_date;
                        ?>
                            <?php if ($end_date_gral >= $today_gral) : ?>

                                <button class="btn btn-icon btn-warning deleteStructure" id="<?= $_GET['id_assignment'] ?>" type="button" title="Borrar todo">
                                    <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <br>
                    <br>
                    <?php if (isset($catalogue_exist_count)) : ?>
                        <?php if (empty($catalogue_exist_count)) : ?>
                            <?php if (!empty($catalogue_exist)) : ?>
                                <button class="btn btn-icon btn-info" type="button" title="Importar configuración de otras materias" data-toggle="modal" data-target="#importConfig">
                                    <span class="btn-inner--icon"><i class="fas fa-cloud-download-alt"></i></span>
                                </button>
                                <br>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php foreach ($periods as $period) :
                        $today = date("Y-m-d");
                        $end_date = $period->grade_closing_date;
                        $catalogue = $expected_learnings->getPeriodCatalog($_GET['id_assignment'], $period->id_period_calendar);
                        //echo $period->id_period_calendar;
                    ?>
                        <div id="div_learning_items" class="row">
                            <div class="col-8">
                                <div class="card mb-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card-header">
                                                <h1 class="card-title">Periodo <?= $period->no_period ?></h1>
                                            </div>
                                            <div class="card-body">
                                                <?php if (count($catalogue) == 0) : ?>
                                                    <?php if ($end_date >= $today) : ?>
                                                        <?php if ($period->no_period > 1) : ?>
                                                            <button title="Importar A.E. desde otra materia" class="btn btn-icon btn-info exportExpectedLearningToAnotherSubject" data-id-assignment="<?= $_GET['id_assignment'] ?>" data-id-period="<?= $period->id_period_calendar ?>" data-no-period="<?= $period->no_period ?>" data-id-academic-area="<?= $_GET['id_academic_area'] ?>" data-toggle="modal" data-target="#exportAEToAnotherSubject" type="button">
                                                                <span class="btn-inner--icon"><i class="fas fa-arrow-down"></i></span>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if ($end_date >= $today) : ?>
                                                    <button title="" class="btn btn-icon btn-primary addExpectedLearning" data-id-period="<?= $period->id_period_calendar ?>" data-no-period="<?= $period->no_period ?>" data-toggle="modal" data-target="#newAE" type="button">
                                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-8 sortable desglosePeriodo_<?= $period->id_period_calendar ?>" id="drop-items" style="background-color:rgba(122, 122, 122, 0.1)">
                                            <?php foreach ($catalogue as $catalogue) : ?>
                                                <div class="card-body" data-id-expected-learning-catalog="<?= $catalogue->id_expected_learning_catalog ?>" id="itemCatalog<?= $catalogue->id_expected_learning_catalog ?>" data-position="<?= $catalogue->no_position ?>">
                                                    <div class="card text-white bg-primary mb-3">
                                                        <div class="card-header bg-primary">
                                                            <strong> <?= $catalogue->short_description ?> </strong>
                                                            <?php if ($end_date >= $today) : ?>
                                                                <button title="Eliminar AE" id="<?= $catalogue->id_expected_learning_catalog ?>" class="btn btn-danger btn-sm deleteLearning" data-no-period="<?= $period->no_period ?>" style=" float: right;" type="button"><i class="fas fa-trash-alt"></i></button>
                                                                <button title="Editar AE" id="<?= $catalogue->id_expected_learning_catalog ?>" class="btn btn-info btn-sm editLearning" style=" float: right;" data-toggle="modal" data-target="#editAE" type="button"><i class="fas fa-edit"></i></button>
                                                                <button title="Cambiar de periodo" id="<?= $catalogue->id_expected_learning_catalog ?>" data-id-period-calendar="<?= $period->id_period_calendar ?>" class="btn btn-success btn-sm changePeriodLearning" style=" float: right;" data-toggle="modal" data-target="#changePeriodAE" type="button"><i class="fas fa-sync"></i></button>
                                                                <button title="Detalle de AE" id="<?= $catalogue->id_expected_learning_catalog ?>" data-no-period="<?= $period->no_period ?>" class="btn btn-success btn-sm infoCatalog" style=" float: right;" type="button"><i class="fas fa-info-circle"></i></button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'modals/createAE.php';
include 'modals/changePeriodAE.php';
include 'modals/infoModal.php';
include 'modals/editExpectedLearning.php';
include 'modals/importConfig.php';
include 'modals/exportAEToAnotherSubject.php';
?>

<script src="js/functions/evaluation_plan/exportAECriteria.js"></script>