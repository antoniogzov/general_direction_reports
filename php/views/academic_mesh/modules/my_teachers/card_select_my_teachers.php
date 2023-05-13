<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_group = 0;
$no_colaborador = 0;
$id_subject = 0;
$getGroups = array();
$id_academic_area = "0";
$id_academic = "0";
$submodule = $_GET['submodule'];
//--- --- ---//
if (isset($_GET['id_academic_area'])) {
    $id_academic_area = $_GET['id_academic_area'];
}



if (($grants & 8)) {

    $stmt_section = "SELECT * FROM 

    (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, aca.name_academic_area
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
    INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
    INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
    INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
    INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
    
    UNION 

    SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, aca.name_academic_area
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

    AS u

    WHERE u.no_teacher = $no_teacher AND u.print_school_report_card = 1 AND u.assignment_active = 1
    ORDER BY u.name_academic_area
    ";

    $GetAcademicArea = $assignments->getSubjectsFromTeachers($stmt_section);

    if (isset($_GET['id_academic_area'])) {
        $academic_area = $_GET['id_academic_area'];

        /*$stmt_academic_levels = "SELECT DISTINCT al.academic_level, al.id_academic_level 
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level AND lvl_com.id_academic_level = al.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND aca.id_academic_area = $academic_area";*/


        $stmt_academic_levels = "SELECT * FROM 

        (SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, al.academic_level, al.id_academic_level
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, al.academic_level, al.id_academic_level
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1
        ORDER BY u.academic_level
        ";

        $getAcademicLevels = $groups->getGroupFromTeachers($stmt_academic_levels);
    }

    if (isset($_GET['id_academic_area']) && isset($_GET['id_academic_level'])) {

        $id_academic_level = $_GET['id_academic_level'];

        $stmt_teachers = "

        SELECT DISTINCT u.no_colaborador,
        CASE
        WHEN u.no_colaborador = 0 THEN 'SIN ASIGNAR'
        ELSE CONCAT(u.apellido_paterno_colaborador ,' ', u.apellido_materno_colaborador, ' ', u.nombres_colaborador) 
        END AS teacher_name,
        u.correo_institucional

        FROM

        (SELECT sbj.name_subject, sbj.id_subject, assg.id_assignment, groups.group_code, col.nombres_colaborador, col.apellido_paterno_colaborador, col.apellido_materno_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active, lvl_com.id_academic_level, col.no_colaborador, col.correo_institucional
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT sbj.name_subject, sbj.id_subject, asgm.id_assignment, gps.group_code, col.nombres_colaborador, col.apellido_paterno_colaborador, col.apellido_materno_colaborador, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active, al.id_academic_level, col.no_colaborador, col.correo_institucional
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON gps.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_area = $id_academic_area AND u.id_academic_level = $id_academic_level AND u.print_school_report_card = 1 AND u.assignment_active = 1
        ORDER BY u.apellido_paterno_colaborador ASC
        ";

        $getTeachers = $assignments->getSubjectsFromTeachers($stmt_teachers);
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
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_academic">* Elija una sección</label>
                    <form>
                        <select class="form-control" name="id_academic_area" id="id_academic_area">
                            <option selected value="">Elija una opción</option>
                            <?php foreach ($GetAcademicArea as $section) : ?>
                                <?php if ($_GET['id_academic_area'] == $section->id_academic_area) : ?>
                                    <option selected  value="<?= $section->id_academic_area ?>"><?= mb_strtoupper($section->name_academic_area) ?></option>
                                <?php else : ?>
                                    <option value="<?= $section->id_academic_area ?>"><?= mb_strtoupper($section->name_academic_area) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="id_teacher">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="academic_level" id="academic_level">
                            <option selected value="" disabled>Elija una opción</option>
                            <?php if (!empty($getAcademicLevels)) : ?>
                                <?php foreach ($getAcademicLevels as $academic_level) : ?>
                                    <?php if ($_GET['id_academic_level'] == $academic_level->id_academic_level) : ?>
                                        <option selected value="<?= $academic_level->id_academic_level ?>"><?= mb_strtoupper($academic_level->academic_level) ?></option>
                                    <?php else : ?>
                                        <option value="<?= $academic_level->id_academic_level ?>"><?= mb_strtoupper($academic_level->academic_level) ?></option>
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