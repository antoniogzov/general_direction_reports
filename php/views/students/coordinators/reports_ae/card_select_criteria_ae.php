<?php
$getSubjects = array();
if (isset($_GET['id_academic_area']) && isset($_GET['id_academic_level']) && isset($_GET['id_subject'])) {
    $id_subject = $_GET['id_subject'];
    $id_academic_level = $_GET['id_academic_level'];
    $id_academic_area = $_GET['id_academic_area'];
    $getSubjects = $expected_learnings->getSubjectsLG($id_academic_area, $id_academic_level, $no_teacher);
    
}
$getAcademicLevel = array();
if (isset($_GET['id_academic_level'])) {
    $id_academic_level = $_GET['id_academic_level'];
    $getAcademicLevel = $expected_learnings->getAcademicLevel($id_academic_area, $no_teacher);
}
?>

<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Reportes AE</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <!-- Form groups used in grid -->
        <div class="row">
            <!-- -->
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="slct_subject">* Elija una materia</label>
                    <form>
                        <select class="form-control" name="slct_subject" id="slct_subject">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getSubjects)) : ?>
                                <?php foreach ($getSubjects as $subjects) : ?>
                                    <?php if (isset($id_subject)) : ?>
                                        <?php if ($id_subject == $subjects->id_subject) : ?>
                                            <option selected value="<?= $subjects->id_subject ?>"><?= mb_strtoupper($subjects->name_subject) ?></option>
                                        <?php else : ?>
                                            <option value="<?= $subjects->id_subject ?>"><?= mb_strtoupper($subjects->name_subject) ?></option>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <option value="<?= $subjects->id_subject ?>"><?= mb_strtoupper($subjects->name_subject) ?></option>
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
if (isset($_GET['id_academic_area']) && isset($_GET['id_academic_level'])) {
    include_once 'show_criteria_report_ae.php';
}
?>