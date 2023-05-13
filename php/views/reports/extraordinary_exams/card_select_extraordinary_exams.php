<?php
$getMutualCriteria = array();
if (isset($_GET['id_academic_area']) && isset($_GET['id_academic_level']) && isset($_GET['id_period']) && isset($_GET['id_level_grade'])) {

    $id_period = $_GET['id_period'];
    $id_academic_level = $_GET['id_academic_level'];
    $id_level_grade = $_GET['id_level_grade'];
    $id_academic_area = $_GET['id_academic_area'];

    $getGroupsExtraordinaryReports = $qualifications_reports->getGroupsExtraordinaryReports($no_teacher, $id_level_grade, $id_period);
}
$getAcademicLevel = array();
if (isset($_GET['id_academic_level'])) {
    $id_academic_level = $_GET['id_academic_level'];
    $getAcademicLevel = $expected_learnings->getAcademicLevel($id_academic_area, $no_teacher);
    $getAcademicLevelGrade = $expected_learnings->getAcademicLevelGrade($id_academic_area, $id_academic_level, $no_teacher);
    $getPeriods = $expected_learnings->getAcademicPeriods($id_academic_area, $id_academic_level, $no_teacher);
}
?>
<script src="https://kit.fontawesome.com/2baa365664.js" crossorigin="anonymous"></script>
<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Consultar exámenes extraordinarios </h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="slct_academic_area">* Elija un área académica</label>
                    <form>
                        <select class="form-control" name="slct_academic_area" id="slct_academic_area">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php foreach ($academicArea as $academic_area) : ?>
                                <?php if (isset($id_academic_area)) : ?>
                                    <?php if ($id_academic_area == $academic_area->id_academic_area) : ?>
                                        <option selected value="<?= $academic_area->id_academic_area ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                    <?php else : ?>
                                        <option value="<?= $academic_area->id_academic_area ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <option value="<?= $academic_area->id_academic_area ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="slct_academic_level">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="slct_academic_level" id="slct_academic_level">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getAcademicLevel)) : ?>
                                <?php foreach ($getAcademicLevel as $academic_level) : ?>
                                    <?php if (isset($id_academic_level)) : ?>
                                        <?php if ($id_academic_level == $academic_level->id_academic_level) : ?>
                                            <option selected value="<?= $academic_level->id_academic_level ?>"><?= mb_strtoupper($academic_level->academic_level) ?></option>
                                        <?php else : ?>
                                            <option value="<?= $academic_level->id_academic_level ?>"><?= mb_strtoupper($academic_level->academic_level) ?></option>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <option value="<?= $academic_level->id_academic_level ?>"><?= mb_strtoupper($academic_level->academic_level) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label" for="slct_grade">* Elija una grado</label>
                    <form>
                        <select class="form-control" name="slct_grade" id="slct_grade">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getAcademicLevelGrade)) : ?>
                                <?php foreach ($getAcademicLevelGrade as $level_grade) : ?>
                                    <?php if (isset($id_level_grade)) : ?>
                                        <?php if ($id_level_grade == $level_grade->id_level_grade) : ?>
                                            <option selected value="<?= $level_grade->id_level_grade ?>"><?= mb_strtoupper($level_grade->level_grade_write) ?></option>
                                        <?php else : ?>
                                            <option value="<?= $level_grade->id_level_grade ?>"><?= mb_strtoupper($level_grade->level_grade_write) ?></option>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <option value="<?= $level_grade->id_level_grade ?>"><?= mb_strtoupper($level_grade->level_grade_write) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label class="form-control-label" for="slct_period">* Periodo</label>
                    <form>
                        <select class="form-control" name="slct_period" id="slct_period">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getPeriods)) : ?>
                                <?php foreach ($getPeriods as $periods) : ?>
                                    <?php if (isset($id_period)) : ?>
                                        <?php if ($id_period == $periods->id_period_calendar) : ?>
                                            <option selected value="<?= $periods->id_period_calendar ?>"><?= mb_strtoupper($periods->no_period) ?></option>
                                        <?php else : ?>
                                            <option value="<?= $periods->id_period_calendar ?>"><?= mb_strtoupper($periods->no_period) ?></option>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <option value="<?= $periods->id_period_calendar ?>"><?= mb_strtoupper($periods->no_period) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_GET['id_academic_area']) && isset($_GET['id_period']) && isset($_GET['id_level_grade'])) {
?>
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
    include_once 'show_extraordinary_exams.php';
}
?>