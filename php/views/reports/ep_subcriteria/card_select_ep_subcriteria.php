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
}

if (isset($_GET['id_group'])) {
    $id_group = $_GET['id_group'];

    $id_academic_area = $_GET['id_academic'];
    $getIDAssignment = $helpers->getAssignmentByGroupAc($id_group, $id_academic_area);
    $group_id_assignment = $getIDAssignment[0]->id_assignment;


    $level_combinations = $helpers->getIdLevelCombinationByAssignment($group_id_assignment);

    foreach ($level_combinations as $level_combination) {
        $id_level_combination = $level_combination->id_level_combination;
        $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
    }
}

if (isset($_GET['id_period'])) {
    $id_period = $_GET['id_period'];
}
//--- --- ---//
if (($grants & 8)) {

    $stmt_section = "SELECT u.id_academic_area, u.name_academic_area FROM 

    (SELECT DISTINCT rel_coord_aca.no_teacher, sbj.id_academic_area, aca.name_academic_area, assg.print_school_report_card, assg.assignment_active
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area                
    UNION 

    SELECT DISTINCT rel_coord_aca.no_teacher, sbj.id_academic_area, aca.name_academic_area, asgm.print_school_report_card, asgm.assignment_active
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade)

    AS u

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
    ORDER BY u.name_academic_area
    ";



    /*$stmt_groups = "SELECT DISTINCT groups.id_group, groups.group_code
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
    WHERE rma.no_teacher = '$no_teacher'";*/

    if (isset($_GET['id_academic']) && isset($_GET['id_period'])) {

        $stmt_groups = "SELECT * FROM 

        (SELECT assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, groups.id_group, groups.group_code, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, sbj.id_academic_area, gps.id_group, gps.group_code, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE no_teacher = $no_teacher AND id_academic_area = $id_academic_area AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4
        ORDER BY group_code ASC
        ";

        $getGroups = $groups->getGroupFromTeachers($stmt_groups);
    }


    $stmt_assignments = "SELECT DISTINCT asg.id_assignment
    FROM  iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
    WHERE rma.no_teacher = '$no_teacher' AND groups.id_group = '$id_group' AND sbj.id_academic_area = '$id_academic_area' LIMIT 1";
} else if ($grants & 4) {
    //--- --- ---//
    $stmt_section = "SELECT DISTINCT sbj.id_academic_area, aca.name_academic_area, assg.print_school_report_card, assg.assignment_active
    FROM school_control_ykt.assignments AS assg
    INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    WHERE assg.no_teacher = $no_teacher AND assg.print_school_report_card = 1 AND assg.assignment_active = 1
    ORDER BY aca.name_academic_area
    ";

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

    if (isset($_GET['id_academic']) && isset($_GET['id_period'])) {

        $stmt_groups = "SELECT assg.print_school_report_card, assg.assignment_active, assg.no_teacher, sbj.id_academic_area, groups.id_group, groups.group_code, groups.group_type_id
        FROM school_control_ykt.assignments AS assg 
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND  sbj.id_academic_area = $_GET[id_academic]
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        WHERE no_teacher = $no_teacher AND id_academic_area = $id_academic_area AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4
        ORDER BY group_code ASC
        ";

        $getGroups = $groups->getGroupFromTeachers($stmt_groups);
    }
}

//--- --- ---//
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
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_academic">* Elija una sección</label>
                    <form>

                        <select class="form-control" name="id_academic" id="id_academic">
                            <option selected value="" disabled>Elija una opción</option>
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

            <!-- -->
            <div class="col-md-4">
                <div class="form-group">
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_period">* Elija un periodo</label>
                    <form>
                        <select class="form-control" name="id_period" id="id_period">
                            <option selected disabled value="">Elija una opción</option>
                            <?php if (!empty($periods)) : ?>
                                <?php foreach ($periods  as $periods_sc) : ?>
                                    <option <?= ($id_period == $periods_sc->id_period_calendar) ? 'selected' : '' ?> value="<?= $periods_sc->id_period_calendar ?>"><?= $periods_sc->no_period ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <!-- -->
        </div>
    </div>
</div>