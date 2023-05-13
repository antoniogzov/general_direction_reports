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
}

if (isset($_GET['id_period'])) {
    $id_period = $_GET['id_period'];
}
//--- --- ---//
if (($grants & 8)) {
    $stmt_section = "SELECT * FROM 

        (SELECT sbj.id_academic_area, aca.name_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        
        UNION 
    
        SELECT sbj.id_academic_area, aca.name_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area)
    
        AS u
    
        WHERE no_teacher = $no_teacher
        ";

$stmt_groups = "SELECT * FROM 

(SELECT assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, groups.id_group, groups.group_code
FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador

UNION 

SELECT asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, gps.id_group, gps.group_code
FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

AS u

WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = $id_academic_area ";


//--- --- ---//

        $stmt_assignments = "SELECT * FROM 

        (SELECT assg.print_school_report_card, assg.assignment_active, assg.id_assignment, groups.id_group, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador

        UNION 

        SELECT asgm.print_school_report_card, asgm.assignment_active, asgm.id_assignment, gps.id_group, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE no_teacher = '$no_teacher' AND id_group = $id_group AND id_academic_area = $id_academic_area LIMIT 1";

} else if ($grants & 4) {
    //--- --- ---//
    $stmt_section = "SELECT DISTINCT sbj.id_academic_area, aca.name_academic_area
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE asg.no_teacher = '$no_teacher'";

    $stmt_groups = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM  school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        WHERE asg.no_teacher = '$no_teacher'";

    $stmt_assignments = "SELECT DISTINCT asg.id_assignment
    FROM  school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
    WHERE asg.no_teacher = '$no_teacher' AND groups.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' LIMIT 1";
    //--- --- ---//
}

//--- --- ---//
$getGroups = $groups->getGroupFromTeachers($stmt_groups);

$GetAcademicArea = $assignments->getSubjectsFromTeachers($stmt_section);

$GetAssignment = $assignments->getSubjectsFromTeachers($stmt_assignments);

$getCicles = $groups->getCicles();


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
    AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment' LIMIT 1";
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
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    //echo $stmt_level_combination;
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
                                    <option id="<?= $section->id_assignment ?>" value="<?= $section->id_academic_area ?>"><?= mb_strtoupper($section->name_academic_area) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_group">* Elija un grupo</label>
                    <form>
                        <select class="form-control" name="id_group" id="id_group">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getGroups)) : ?>
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
            <!-- <div class="col-md-3">
                <div class="form-group">
                    <?php
                    if (isset($_GET['id_ciclo'])) {
                    ?>
                        <input id="id_cycle" type="hidden" value="<?= $_GET['id_ciclo'] ?>">
                    <?php
                    }
                    ?>

                    <label class="form-control-label" for="id_ciclo">Elija un ciclo</label>
                    <form>
                        <select class="form-control" name="id_ciclo" id="id_ciclo">
                            <option selected value="">Elija una opción</option>
                            <?php if (!empty($getCicles)) :
                                foreach ($getCicles as $cicle) : ?>
                                    <?php if (isset($_GET['id_ciclo']) &&  $_GET['id_ciclo'] == $cicle->id_ciclo) : ?>
                                        <option selected id="<?= $cicle->id_ciclo ?>" value="<?= $cicle->id_ciclo ?>"><?= $cicle->ciclo ?></option>
                                    <?php else : ?>
                                        <option id="<?= $cicle->id_ciclo ?>" value="<?= $cicle->id_ciclo ?>"><?= $cicle->ciclo ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div> -->

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
                        <select class="form-control" name="id_period" id="id_period">
                            <option selected value="">Elija una opción</option>
                            <?php if (!empty($getPeriods)) :
                                foreach ($getPeriods as $periods) : ?>
                                    <?php if ($id_period == $periods->no_period) : ?>
                                        <option selected value="<?= $periods->no_period ?>"><?= $periods->no_period ?></option>
                                    <?php else : ?>
                                        <option id="<?= $periods->no_period ?>" value="<?= $periods->no_period ?>"><?= $periods->no_period ?></option>
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