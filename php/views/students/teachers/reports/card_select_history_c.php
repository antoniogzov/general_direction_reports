<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_group = 0;
$no_colaborador = 0;
$id_subject = 0;
$getGroups = array();
$id_academic_area = "0";
$id_academic = "0";
//--- --- ---//
if (isset($_GET['id_academic'])) {
    $id_academic_area = $_GET['id_academic'];
    $submodule = $_GET['submodule'];
?>

<?php
}
if (isset($_GET['id_academic'])) {
    $id_academic = $_GET['id_academic'];
}

if (isset($_GET['id_period'])) {
    $id_period = $_GET['id_period'];
}
//--- --- ---//

if (isset($_GET['id_teacher'])) {
    $no_colaborador = $_GET['id_teacher'];
}


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

    SELECT  sbj.id_academic_area, aca.name_academic_area, rel_coord_aca.no_teacher
    FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
    INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
    INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
    INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
    INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade

    UNION 

    SELECT  sbj.id_academic_area, aca.name_academic_area, asg.no_teacher
    FROM school_control_ykt.assignments AS asg 
    INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
    INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)

    AS u

    WHERE no_teacher = '$no_teacher'";
    /* if (isset($_GET['id_academic'])&&isset($_GET['id_period'])&&isset($_GET['id_teacher'])&&isset($_GET['week'])) { */
    $stmt_groups = "SELECT * FROM 

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
    INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)

    AS u

    WHERE no_teacher = '$_SESSION[colab]' AND id_academic_area = '$id_academic' ORDER BY teacher_name ASC";
}

//--- --- ---//
$getTeachers = $groups->getGroupFromTeachers($stmt_groups);

$GetAcademicArea = $assignments->getSubjectsFromTeachers($stmt_section);




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
                <?php if (isset($_GET['report_type'])) : ?>
                    <?php if ($_GET['report_type'] == 2) : ?>
                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" id="customRadio1" value="1" name="report_type" checked class="custom-control-input">
                            <label class="custom-control-label" for="customRadio1">Registros por profesor</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" checked id="customRadio2" value="2" name="report_type" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio2">Registros por asignatura</label>
                        </div>
                    <?php else : ?>
                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" id="customRadio1" value="1" name="report_type" checked class="custom-control-input">
                            <label class="custom-control-label" for="customRadio1">Registros por profesor</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio2" value="2" name="report_type" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio2">Registros por asignatura</label>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="custom-control custom-radio mb-3">
                        <input type="radio" id="customRadio1" value="1" name="report_type"  class="custom-control-input">
                        <label class="custom-control-label" for="customRadio1">Registros por profesor</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" value="2" name="report_type" checked class="custom-control-input">
                        <label class="custom-control-label" for="customRadio2">Registros por asignatura</label>
                    </div>
                <?php endif; ?>
            </div>
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
                            <option disabled selected value="">Elija una opción</option>
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
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="id_teacher">* Elija un profesor</label>
                    <form>
                        <select class="form-control" name="id_teacher" id="id_teacher">
                            <option selected disabled value="" disabled>Elija una opción</option>
                            <?php if (!empty($getTeachers)) : ?>
                                <?php foreach ($getTeachers as $teachers) : ?>
                                    <?php if ($no_colaborador == $teachers->no_colaborador) : ?>
                                        <option selected value="<?= $teachers->no_colaborador ?>"><?= $teachers->teacher_name ?></option>
                                    <?php else : ?>
                                        <option value="<?= $teachers->no_colaborador ?>"><?= $teachers->teacher_name ?></option>
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