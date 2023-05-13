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

    if (isset($_GET['id_academic'])  && isset($_GET['id_teacher_sbj'])  && isset($_GET['id_academic_level'])  && isset($_GET['id_period'])) {
        $id_academic_level = $_GET['id_academic_level'];
        $id_period = $_GET['id_period'];
        $id_academic = $_GET['id_academic'];

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

        $stmt_teachers = "SELECT * FROM 

        (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
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
    
        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT asg.no_teacher, sbj.id_academic_area, colab.no_colaborador,  CONCAT(colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group
        )
    
        AS u
    
        WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = '$id_academic_area' ORDER BY teacher_name ASC
        ";

        $getTeachers = $groups->getGroupFromTeachers($stmt_teachers);


        $id_teacher_sbj = $_GET['id_teacher_sbj'];
        $stmt_assignments_teachers = "SELECT DISTINCT asg.id_assignment, gps.group_code, sbj.name_subject FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE asg.no_teacher = $id_teacher_sbj
        ";

        $getAssignmentsByTeacher = $groups->getGroupFromTeachers($stmt_assignments_teachers);


        $stmt_academic_levels = "SELECT DISTINCT acl.*
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = alg.id_academic_level
        WHERE asg.no_teacher = $id_teacher_sbj
        ORDER BY academic_level
        ";

        $getAcademicLevels = $groups->getGroupFromTeachers($stmt_academic_levels);

        $stmt_get_periods = "SELECT DISTINCT percal.*
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = alg.id_academic_level
        INNER JOIN school_control_ykt.level_combinations AS lvl_comb 
            ON lvl_comb.id_academic_area = sbj.id_academic_area
            AND lvl_comb.id_academic_level = alg.id_academic_level
            AND lvl_comb.id_campus = gps.id_campus
            AND gps.id_section = lvl_comb.id_section
            
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_level_combination = lvl_comb.id_level_combination
        WHERE asg.no_teacher = $id_teacher_sbj
        AND acl.id_academic_level = $id_academic_level
        AND sbj.id_academic_area = $id_academic
        ORDER BY percal.no_period
        ";

        $getPeriods = $groups->getGroupFromTeachers($stmt_get_periods);


        $stmt_get_aulas = "SELECT *
        FROM class_schedule.classrooms
        ";

        $getAulas = $groups->getGroupFromTeachers($stmt_get_aulas);
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
            <div class="col-md-3">
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
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_teacher_sbj">* Elija un profesor</label>
                    <form>
                        <select class="form-control" name="id_teacher_sbj" id="id_teacher_sbj">
                            <option selected disabled value="">Elija una opción</option>
                            <?php if (!empty($getTeachers)) : ?>
                                <?php foreach ($getTeachers  as $teachers) : ?>
                                    <option <?= ($_GET['id_teacher_sbj'] == $teachers->no_colaborador) ? 'selected' : '' ?> value="<?= $teachers->no_colaborador ?>"><?= $teachers->teacher_name ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_academic_level">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="id_academic_level" id="id_academic_level">
                            <option selected disabled value="">Elija una opción</option>
                            <?php if (!empty($getAcademicLevels)) : ?>
                                <?php foreach ($getAcademicLevels  as $academic_levels) : ?>
                                    <option <?= ($_GET['id_academic_level'] == $academic_levels->id_academic_level) ? 'selected' : '' ?> value="<?= $academic_levels->id_academic_level ?>"><?= $academic_levels->academic_level ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="col-md-3">
                <div class="form-group">
                    <?php if (isset($_GET['id_period'])) : ?>
                        <input id="id_sbjt" type="hidden" value="<?= $_GET['id_period'] ?>">
                    <?php endif; ?>

                    <label class="form-control-label" for="id_period">* Elija un periodo</label>
                    <form>
                        <select class="form-control" name="id_period" id="id_period">
                            <option selected disabled value="">Elija una opción</option>
                            <?php if (!empty($getPeriods)) : ?>
                                <?php if (($_GET['id_period']) == 'all') : ?>
                                    <option selected value="all">TODOS LOS PERIODOS</option>
                                    <?php foreach ($getPeriods  as $periods) : ?>
                                        <option <?= ($_GET['id_period'] == $periods->id_period_calendar) ? 'selected' : '' ?> value="<?= $periods->id_period_calendar ?>"><?= $periods->no_period ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="all">TODOS LOS PERIODOS</option>
                                    <?php foreach ($getPeriods  as $periods) : ?>
                                        <option <?= ($_GET['id_period'] == $periods->id_period_calendar) ? 'selected' : '' ?> value="<?= $periods->id_period_calendar ?>"><?= $periods->no_period ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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