<?php
$no_teacher = $_SESSION['colab'];
$id_academic_level = 0;
if (isset($_GET['id_academic_level'])) {
    $id_academic_level = $_GET['id_academic_level'];
}
$sql_academic_area = "SELECT DISTINCT acar.id_academic_area, acar.name_academic_area 
FROM school_control_ykt.assignments AS asgm
INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
INNER JOIN school_control_ykt.academic_areas AS acar ON sbj.id_academic_area =acar.id_academic_area
WHERE asgm.no_teacher = '$no_teacher'";
$getAcademicArea = $groups->getGroupFromTeachers($sql_academic_area);

if (count($getAcademicArea) == 1) {
    $id_academic_area = $getAcademicArea[0]->id_academic_area;
    $smtp_academic_level = "SELECT DISTINCT acl.id_academic_level, acl.academic_level, sbj.id_academic_area, gps.id_section, lvc.id_level_combination, lvc.id_campus 
    FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group 
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade 
    INNER JOIN school_control_ykt.academic_levels AS acl ON aclg.id_academic_level = acl.id_academic_level 
    INNER JOIN school_control_ykt.level_combinations AS lvc 
        ON lvc.id_academic_area = sbj.id_academic_area AND lvc.id_academic_level = acl.id_academic_level AND lvc.id_section = gps.id_section AND lvc.id_campus = gps.id_campus
    WHERE  asg.no_teacher ='$no_teacher' AND sbj.id_academic_area = '$id_academic_area'";

    $getAcadmeicLevels = $groups->getGroupFromTeachers($smtp_academic_level);
}


if (isset($_GET['id_level_combination'])) {
    $id_level_combination = $_GET['id_level_combination'];
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
}



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

                        <select class="form-control" name="id_academic_area" id="id_academic_area">
                            <option selected value="">Elija una opción</option>
                            <?php if (count($getAcademicArea) == 1) : ?>
                                <?php foreach ($getAcademicArea as $academic_area) : ?>
                                    <option selected value="<?php echo $academic_area->id_academic_area; ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                <?php endforeach; ?>
                                <<?php else : ?> <?php foreach ($getAcademicArea as $academic_area) : ?> <?php if ($id_academic_area == $academic_area->id_academic_area) : ?> <option selected value="<?= $academic_area->id_academic_area ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                <?php else : ?>
                                    <option id="<?= $academic_area->id_academic_area ?>" value="<?= $academic_area->id_academic_area ?>"><?= mb_strtoupper($academic_area->name_academic_area) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_academic_level">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control id_academic_level" name="id_academic_level">
                            <option selected disabled value="">Elija una opción</option>
                            <?php foreach ($getAcadmeicLevels as $level) : ?>
                                <?php if (isset($_GET['id_academic_level'])) : ?>

                                    <?php if ($_GET['id_academic_level'] == $level->id_academic_level) : ?>
                                        <option selected id="<?= $level->id_level_combination ?>" value="<?= $level->id_academic_level ?>"><?= mb_strtoupper($level->academic_level) ?></option>
                                    <?php else : ?>
                                        <option id="<?= $level->id_level_combination ?>" value="<?= $level->id_academic_level ?>"><?= mb_strtoupper($level->academic_level) ?></option>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <option id="<?= $level->id_level_combination ?>" value="<?= $level->id_academic_area ?>"><?= mb_strtoupper($level->academic_level) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    if (isset($_GET['id_period'])) {
                    ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php
                    }
                    ?>

                    <label class="form-control-label" for="id_period">* Elija un periodo</label>
                    <form>
                        <select class="form-control" name="id_period_teacher" id="id_period_teacher">
                            <option selected value="">Elija una opción</option>
                            <?php if (!empty($periods)) :
                                foreach ($periods  as $periods_sc) : ?>
                                    <?php if ($_GET['id_period'] == $periods_sc->id_period_calendar) : ?>
                                        <option selected value="<?= $periods_sc->id_period_calendar ?>"><?= $periods_sc->no_period ?></option>
                                    <?php else : ?>
                                        <option id="<?= $periods_sc->id_period_calendar ?>" value="<?= $periods_sc->id_period_calendar ?>"><?= $periods_sc->no_period ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (isset($_GET['submodule']) && $_GET['submodule'] == 'qualifications' && !empty($periods)): ?>
                                <option <?= ($_GET['id_period'] == 'all_periods') ? 'selected' : '' ?> value="all_periods">TODOS PERIODOS</option>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>