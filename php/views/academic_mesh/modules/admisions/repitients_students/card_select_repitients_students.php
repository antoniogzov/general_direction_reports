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

    $stmt_section = "SELECT DISTINCT u.id_academic_level, u.academic_levels FROM 

    (SELECT DISTINCT rel_coord_aca.no_teacher, sbj.id_academic_area, aca.name_academic_area, assg.print_school_report_card, assg.assignment_active, academic_level AS academic_levels, acl.id_academic_level
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.academic_levels AS acl ON acdlvldg.id_academic_level = acl.id_academic_level

    UNION 

    SELECT DISTINCT rel_coord_aca.no_teacher, sbj.id_academic_area, aca.name_academic_area, asgm.print_school_report_card, asgm.assignment_active, academic_level AS academic_levels, acl.id_academic_level
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN school_control_ykt.academic_levels AS acl ON aclg.id_academic_level = acl.id_academic_level
    )

    AS u

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
    ORDER BY u.name_academic_area
    ";


if (isset($_GET['id_academic'])) {
    $id_academic_level = $_GET['id_academic'];
}
    /*$stmt_groups = "SELECT DISTINCT groups.id_group, groups.group_code
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
    WHERE rma.no_teacher = '$no_teacher'";*/

    /*
        $stmt_academic_levels = "SELECT * FROM 

        (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, acdlvldg.degree, acdlvldg.id_level_grade
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        
        UNION 
    
        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, aclg.degree, aclg.id_level_grade
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.no_teacher, sbj.id_academic_area, aclg.degree, aclg.id_level_grade
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
        )
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = '$id_academic'
        ORDER BY degree
        ";

        $getAcademicLevels = $groups->getGroupFromTeachers($stmt_academic_levels);
    } */
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
            <div class="col-md-3">
                <div class="form-group">
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_academic">* Elija un nivel académico</label>
                    <form>

                        <select class="form-control" name="id_academic" id="id_academic">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php foreach ($GetAcademicArea as $section) : ?>
                                <?php if ($id_academic_level == $section->id_academic_level) : ?>
                                    <option selected value="<?= $section->id_academic_level ?>"><?= mb_strtoupper($section->academic_levels) ?></option>
                                <?php else : ?>
                                    <option id="<?= $section->id_assignment ?>" value="<?= $section->id_academic_level ?>"><?= mb_strtoupper($section->academic_levels) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <!--  <div class="col-md-3">
                <div class="form-group">
                    <?php if (isset($_GET['id_academic_level'])) : ?>
                        <input id="id_academic_level" type="hidden" value="<?= $_GET['id_academic_level'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_academic_level">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="id_academic_level" id="id_academic_level">
                            <option selected disabled value="">Elija una opción</option>
                            <?php if (!empty($getAcademicLevels)) : ?>
                                <?php foreach ($getAcademicLevels  as $academic_levels) : ?>
                                    <option <?= ($_GET['id_academic_level'] == $academic_levels->id_level_grade) ? 'selected' : '' ?> value="<?= $academic_levels->id_level_grade ?>"><?= $academic_levels->degree ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div> -->
            <!-- -->
        </div>
    </div>
</div>