<script>
    Swal.fire({
        text: "Cargando...",
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
    });
</script>
<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_academic_area = "";
$getAcademicGradeByArea = array();
$getSectionAndCampus = array();
$getPeriodsByIdLevelCombination = array();


if (isset($_GET['id_academic_area']) && isset($_GET['id_level_grade']) && isset($_GET['id_academic_area']) && isset($_GET['id_level_combination'])) {
    $id_level_grade = $_GET['id_level_grade'];
    $id_academic_area = $_GET['id_academic_area'];
    $id_level_combination = $_GET['id_level_combination'];
    $muestra = $_GET['muestra'];
    $arr_muestra = explode('.', $muestra);
    if (count($arr_muestra) >= 2) {
        $muestra = $arr_muestra[0];
    }

    $min = $_GET['min'];
    $max = $_GET['max'];


    $getAcademicGradeByArea = $academicReports->getAcademicGradeByArea();
    $getSectionAndCampus = $academicReports->getSectionAndCampus($id_level_grade);
    $getPeriodsByIdLevelCombination = $academicReports->getPeriodsByIdLevelCombination($id_level_combination);
}
$stmt_section = "SELECT DISTINCT sbj.id_academic_area, aca.name_academic_area
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$no_teacher'";

$GetAcademicArea = $assignments->getSubjectsFromTeachers($stmt_section);
?>
<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Opciones</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">

                    <label class="form-control-label" for="id_academic">* Elija un área académica</label>
                    <form>

                        <select class="form-control" name="id_academic" id="id_academic">
                            <option selected disabled value="">Elija una opción</option>
                            <?php foreach ($GetAcademicArea as $section) : ?>
                                <?php if ($id_academic_area == $section->id_academic_area) : ?>
                                    <option selected value="<?= $section->id_academic_area ?>"><?= mb_strtoupper($section->name_academic_area) ?></option>
                                <?php else : ?>
                                    <option id="<?= $section->id_academic_area ?>" value="<?= $section->id_academic_area ?>"><?= mb_strtoupper($section->name_academic_area) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_level_grade">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="id_level_grade" id="id_level_grade">
                            <option selected disabled value="" disabled>Elija una opción</option>
                            <?php if (!empty($getAcademicGradeByArea)) : ?>
                                <?php foreach ($getAcademicGradeByArea as $aca_grade) : ?>
                                    <?php if ($_GET['id_level_grade'] == $aca_grade->id_level_grade) : ?>
                                        <option selected value="<?= $aca_grade->id_level_grade ?>" data-id-academic-level="<?= $aca_grade->id_academic_level ?>"><?= mb_strtoupper($aca_grade->degree) ?></option>
                                    <?php else : ?>
                                        <option value="<?= $aca_grade->id_level_grade ?>" data-id-academic-level="<?= $aca_grade->id_academic_level ?>"><?= mb_strtoupper($aca_grade->degree) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_level_grade">* Elija una sección</label>
                    <form>
                        <select class="form-control" name="id_section" id="id_section">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getSectionAndCampus)) : ?>
                                <?php foreach ($getSectionAndCampus as $section) : ?>
                                    <?php if ($_GET['id_level_grade'] == $section->id_level_grade) : ?>
                                        <option selected data-id-section="<?= $section->id_section ?>" data-id-campus="<?= $section->id_campus ?>"><?= mb_strtoupper($section->seccion_campus) ?></option>
                                    <?php else : ?>
                                        <option data-id-section="<?= $section->id_section ?>" data-id-campus="<?= $section->id_campus ?>"><?= mb_strtoupper($section->seccion_campus) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_period">* Elija un periodo</label>
                    <form>
                        <select class="form-control" name="id_period" id="id_period">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getPeriodsByIdLevelCombination)) : ?>
                                <?php if ($_GET['id_period_calendar'] == 'all') : ?>
                                    <?php foreach ($getPeriodsByIdLevelCombination as $period) : ?>
                                        <option data-id-level-combination="<?= $period->id_level_combination ?>" value="<?= $period->id_period_calendar ?>"><?= mb_strtoupper($period->no_period) ?></option>
                                    <?php endforeach; ?>
                                    <option selected data-id-level-combination="<?= $period->id_level_combination ?>" value="all"><?= mb_strtoupper('TODOS LOS PERIODOS') ?></option>
                                <?php else : ?>
                                    <?php foreach ($getPeriodsByIdLevelCombination as $period) : ?>
                                        <?php if ($_GET['id_period_calendar'] == $period->id_period_calendar) : ?>
                                            <option selected data-id-level-combination="<?= $period->id_level_combination ?>" value="<?= $period->id_period_calendar ?>"><?= mb_strtoupper($period->no_period) ?></option>
                                        <?php else : ?>
                                            <option data-id-level-combination="<?= $period->id_level_combination ?>" value="<?= $period->id_period_calendar ?>"><?= mb_strtoupper($period->no_period) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <option data-id-level-combination="<?= $period->id_level_combination ?>" value="all"><?= mb_strtoupper('TODOS LOS PERIODOS') ?></option>
                                <?php endif; ?>
                            <?php endif; ?>

                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="example-number-input" class="form-control-label">Muestra</label>
                    <?php if (isset($_GET['muestra'])) : ?>
                        <input class="form-control" type="number" pattern="^[0-9]" onkeypress="return isNumberKey(this);" value="<?= $muestra ?>" min="1" max="10" id="muestra">
                    <?php else : ?>
                        <input class="form-control" type="number" pattern="^[0-9]" onkeypress="return isNumberKey(this);" value="3" min="1" max="10" id="muestra">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="example-number-input" class="form-control-label">Prom. Mínimo</label>
                    <?php if (isset($_GET['min'])) : ?>
                        <input class="form-control" type="number" value="<?= $_GET['min'] ?>" min="1" max="10" id="min">
                    <?php else : ?>
                        <input class="form-control" type="number" value="8" min="1" max="10" id="min">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="example-number-input" class="form-control-label">Prom. Máximo</label>
                    <?php if (isset($_GET['max'])) : ?>
                        <input class="form-control" type="number" value="<?= $_GET['max'] ?>" min="1" max="10" id="max">
                    <?php else : ?>
                        <input class="form-control" type="number" value="10" min="1" max="10" id="max">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" class="btn btn-success btnSearchRank">Buscar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    Swal.close();
</script>