<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_group = 0;
$id_subject = 0;
$getGroups = array();
$id_academic_area = "0";
//--- --- ---//
if (isset($_GET['id_academic'])) {
    $id_academic_area = $_GET['id_academic'];
    $submodule = $_GET['submodule'];
?>

<?php

}
if (isset($_GET['id_group'])) {
    $id_group = $_GET['id_group'];

    $level_combinations = $attendance->getLevelCombinationByGroupID($id_group);

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}

if (isset($_GET['id_assignment'])) {
$id_assignment = $_GET['id_assignment'];
}

if (isset($_GET['id_period'])) {
    $id_period = $_GET['id_period'];
}
//--- --- ---//
if (($grants & 8)) {
    $stmt_section = "SELECT DISTINCT sbj.id_academic_area, aca.name_academic_area
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rma.no_teacher = '$no_teacher'";

    $stmt_groups = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE rma.no_teacher = '$no_teacher' AND (sbj.id_subject=417 OR sbj.id_subject= 416)";

    $stmt_assignments = "SELECT DISTINCT asg.id_assignment, sbj.name_subject
    FROM  iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
    WHERE rma.no_teacher = '$no_teacher' AND groups.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' AND sbj.id_subject=417";
} /* else if ($grants & 4) {
        //--- --- ---//
        $stmt_subjects = "SELECT DISTINCT sbj.id_subject, sbj.name_subject,  asg.id_assignment
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        WHERE asg.no_teacher = '$no_teacher' AND gps.id_group = '$id_group'";
        //--- --- ---//
        $stmt_groups = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher  = '$no_teacher'";
        //--- --- ---//
    } */

//--- --- ---//
$getGroups = $groups->getGroupFromTeachers($stmt_groups);

$GetAcademicArea = $assignments->getSubjectsFromTeachers($stmt_section);

$GetAssignment = $assignments->getSubjectsFromTeachers($stmt_assignments);




if (!empty($GetAssignment)) {
    foreach ($GetAssignment as $assignment) {
        $id_assignment = $assignment->id_assignment;
    }
    $stmt_level_combination = "SELECT lc.id_level_combination
    FROM school_control_ykt.level_combinations AS lc
    INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
    INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
    INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
    WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus 
    AND lc.id_academic_level = ac_le.id_academic_level 
    AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment'";
    $getLevel = $assignments->getSubjectsFromTeachers($stmt_level_combination);

    if (!empty($getLevel)) {
        //--- --- ---//
        foreach ($getLevel as $level_combinations) {
            $id_level_combination = $level_combinations->id_level_combination;
        }

        $sql_level_combinations = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination'";
        $getPeriods = $assignments->getSubjectsFromTeachers($sql_level_combinations);
    }
}

/*echo '<br/><br/><br/><br/><br/><br/><br/>';
print_r($getGroups);*/

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
            <div class="col-md-3">
                <div class="form-group">
                    <?php
                    if (isset($_GET['id_period'])) {
                    ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php
                    }
                    ?>

                    <label class="form-control-label" for="id_academic">* Elija una sección</label>
                    <form>

                        <select class="form-control" name="id_academic" id="id_academic">
                            <option selected value="">Elija una opción</option>
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
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_group">* Elija un grupo</label>
                    <form>
                        <select class="form-control" name="id_group" id="id_group">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getGroups) AND (isset($_GET['id_group']))) : ?>
                                <?php foreach ($getGroups as $group) : ?>
                                    <?php if ($id_group == $group->id_group) : ?>
                                        <option selected value="<?= $group->id_group ?>"><?= $group->group_code ?></option>
                                    <?php else : ?>
                                        <option value="<?= $group->id_group ?>"><?= $group->group_code ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_materia">* Elija una materia</label>
                    <form>
                        <select class="form-control" name="id_materia" id="id_materia">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($GetAssignment)) : ?>
                                <?php foreach ($GetAssignment as $assignments) : ?>
                                    <?php if ($id_assignment == $assignments->id_assignment) : ?>
                                        <option selected value="<?= $assignments->id_assignment ?>"><?= $assignments->name_subject ?></option>
                                    <?php else : ?>
                                        <option value="<?= $assignments->id_assignment ?>"><?= $assignments->name_subject ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-3">
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
                        <select class="form-control" name="id_period" id="id_period">
                            <option selected value="">Elija una opción</option>
                            <?php if (!empty($periods)) :
                                foreach ($periods  as $periods_sc) : ?>
                                    <?php if ($id_period == $periods_sc->id_period_calendar) : ?>
                                        <option selected value="<?= $periods_sc->id_period_calendar ?>"><?= $periods_sc->no_period ?></option>
                                    <?php else : ?>
                                        <option id="<?= $periods_sc->id_period_calendar ?>" value="<?= $periods_sc->id_period_calendar ?>"><?= $periods_sc->no_period ?></option>
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