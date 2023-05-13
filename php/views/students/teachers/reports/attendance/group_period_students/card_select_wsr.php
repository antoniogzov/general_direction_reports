<?php

$id_academic_level = '';

if (isset($_GET['id_academic_level'])) {
    $id_academic_level = $_GET['id_academic_level'];
}

$no_teacher = $_SESSION['colab'];

if (($grants & 8)) {
    /*$stmt_ac_levels = "SELECT DISTINCT acl.* 
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN  school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON aclg.id_academic_level = acl.id_academic_level 
    WHERE rma.no_teacher = '$no_teacher'";*/


    $stmt_ac_area = "SELECT * FROM 
    
    (SELECT assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, UPPER(acar.name_academic_area) AS name_academic_area
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS acar ON sbj.id_academic_area = acar.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, UPPER(acar.name_academic_area) AS name_academic_area
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_areas AS acar ON sbj.id_academic_area = acar.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

    AS u

    WHERE no_teacher = $no_teacher  AND print_school_report_card = 1 AND assignment_active = 1 

    ";

    $getAcademicArea = $assignments->getSubjectsFromTeachers($stmt_ac_area);

    $stmt_ac_levels = "  SELECT * FROM 

    (SELECT rel_coord_aca.no_teacher, assg.print_school_report_card, assg.assignment_active, acl.* 
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON acdlvldg.id_academic_level = acl.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT rel_coord_aca.no_teacher, asgm.print_school_report_card, asgm.assignment_active, acl.* 
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON aclg.id_academic_level = acl.id_academic_level
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

    AS u

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
    ";

    $getAcademicLevels = $assignments->getSubjectsFromTeachers($stmt_ac_levels);



    if (isset($_GET['id_academic_level'])) {

        $stmt_ac_groups = " SELECT * FROM 

    (SELECT groups.group_code, groups.id_group, assg.print_school_report_card, assg.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT gps.group_code, gps.id_group, asgm.print_school_report_card, asgm.assignment_active, acl.id_academic_level,   rel_coord_aca.no_teacher
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = acdlvldg.id_academic_level
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

    AS u

    WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_level = $_GET[id_academic_level] AND u.print_school_report_card = 1 AND u.assignment_active = 1
    ORDER BY u.group_code ASC
    ";

        $getAcademicLevelsGroups = $assignments->getSubjectsFromTeachers($stmt_ac_groups);
    }

    if (isset($_GET['id_period'])) {

        $stmt_periods = " SELECT per.* 
        FROM iteach_grades_quantitatives.period_calendar AS percal
        INNER JOIN iteach_grades_quantitatives.period_calendar as per ON per.id_level_combination = percal.id_level_combination
        WHERE percal.id_period_calendar = $_GET[id_period]
    ";

        $getAcademicLevelsPeriods = $assignments->getSubjectsFromTeachers($stmt_periods);
    }
} else if (($grants & 4)) {
    /*$stmt_ac_levels = "SELECT DISTINCT acl.* 
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN  school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON aclg.id_academic_level = acl.id_academic_level 
    WHERE rma.no_teacher = '$no_teacher'";*/


    $stmt_ac_area = "SELECT DISTINCT sbj.id_academic_area, UPPER(acar.name_academic_area) AS name_academic_area
    FROM school_control_ykt.assignments AS assg 
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_areas AS acar ON sbj.id_academic_area = acar.id_academic_area
    WHERE assg.no_teacher = $no_teacher  AND print_school_report_card = 1 AND assignment_active = 1 
    ";

    $getAcademicArea = $assignments->getSubjectsFromTeachers($stmt_ac_area);

    $stmt_ac_levels = " SELECT DISTINCT assg.print_school_report_card, assg.assignment_active, acl.* 
    FROM school_control_ykt.assignments AS assg
    INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON acdlvldg.id_academic_level = acl.id_academic_level
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    WHERE assg.no_teacher = $no_teacher AND print_school_report_card = 1 AND assignment_active = 1
    ";
    $getAcademicLevels = $assignments->getSubjectsFromTeachers($stmt_ac_levels);



    if (isset($_GET['id_academic_level'])) {

        $stmt_ac_groups = "SELECT DISTINCT groups.id_group, groups.group_code
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acl ON groups.id_level_grade = acl.id_level_grade
        WHERE asg.no_teacher = '$_SESSION[colab]' AND acl.id_academic_level = $_GET[id_academic_level]
    ";

        $getAcademicLevelsGroups = $assignments->getSubjectsFromTeachers($stmt_ac_groups);
    }

    if (isset($_GET['id_period'])) {

        $stmt_periods = " SELECT per.* 
        FROM iteach_grades_quantitatives.period_calendar AS percal
        INNER JOIN iteach_grades_quantitatives.period_calendar as per ON per.id_level_combination = percal.id_level_combination
        WHERE percal.id_period_calendar = $_GET[id_period]
    ";

        $getAcademicLevelsPeriods = $assignments->getSubjectsFromTeachers($stmt_periods);
    }
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
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_academic_area">* Elija un área académica</label>
                    <form>
                        <select class="form-control" name="id_academic_area" id="id_academic_area">
                            <option selected value="" disabled>Elija una opción</option>

                            <?php if (!empty($getAcademicArea)) : ?>
                                <?php foreach ($getAcademicArea as $ac_area) : ?>
                                    <?php if (isset($_GET['id_academic_area'])) : ?>
                                        <?php if ($_GET['id_academic_area'] == $ac_area->id_academic_area) : ?>
                                            <option selected id="<?= $ac_area->id_academic_area ?>" value="<?= $ac_area->id_academic_area ?>"><?= $ac_area->name_academic_area ?></option>
                                        <?php else : ?>
                                            <option id="<?= $ac_area->id_academic_area ?>" value="<?= $ac_area->id_academic_area ?>"><?= $ac_area->name_academic_area ?></option>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <option id="<?= $ac_area->id_academic_area ?>" value="<?= $ac_area->id_academic_area ?>"><?= $ac_area->name_academic_area ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_academic__level">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="id_academic__level" id="id_academic__level">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getAcademicLevels)) : ?>
                                <?php foreach ($getAcademicLevels as $ac_level) : ?>
                                    <?php if ($id_academic_level == $ac_level->id_academic_level) : ?>
                                        <option selected id="<?= $ac_level->id_academic_level ?>" value="<?= $ac_level->id_academic_level ?>"><?= $ac_level->academic_level ?></option>
                                    <?php else : ?>
                                        <option id="<?= $ac_level->id_academic_level ?>" value="<?= $ac_level->id_academic_level ?>"><?= $ac_level->academic_level ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
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

                            <?php if (!empty($getAcademicLevelsGroups)) : ?>
                                <?php foreach ($getAcademicLevelsGroups as $ac_group) : ?>
                                    <?php if ($_GET['id_group'] == $ac_group->id_group) : ?>
                                        <option selected id="<?= $ac_group->id_group ?>" value="<?= $ac_group->id_group ?>"><?= $ac_group->group_code ?></option>
                                    <?php else : ?>
                                        <option id="<?= $ac_group->id_group ?>" value="<?= $ac_group->id_group ?>"><?= $ac_group->group_code ?></option>
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

                            <?php if (!empty($getAcademicLevelsPeriods)) : ?>
                                <?php foreach ($getAcademicLevelsPeriods as $periods) : ?>
                                    <?php if ($_GET['id_period'] == $periods->id_period_calendar) : ?>
                                        <option data-no-period="<?= $periods->no_period ?>" selected id="<?= $periods->id_period_calendar ?>" value="<?= $periods->id_period_calendar ?>"><?= $periods->no_period ?></option>
                                    <?php else : ?>
                                        <option data-no-period="<?= $periods->no_period ?>" id="<?= $periods->id_period_calendar ?>" value="<?= $periods->id_period_calendar ?>"><?= $periods->no_period ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>

        </div>
        <button id="get_week_attendance_teacher" style="display:none;" type="button" class="btn btn-success">Consultar</button>
    </div>
</div>