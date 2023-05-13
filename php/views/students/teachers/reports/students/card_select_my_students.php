<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_group = 0;
$id_subject = 0;
$getGroups = array();
//--- --- ---//
if (isset($_GET['id_subject'])) {
    $id_subject = $_GET['id_subject'];
    $submodule = $_GET['submodule'];
}
if (isset($_GET['id_group'])) {
    $id_group = $_GET['id_group'];
}
//--- --- ---//
if ($submodule == "students_subject") {
    if (($grants & 8)) {
        $stmt_subjects = " SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area

        UNION 

        SELECT sbj.name_subject, sbj.id_subject
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade)

        AS u

        WHERE no_teacher = $no_teacher ORDER BY name_subject ASC";
    } else if ($grants & 4) {
        //--- --- ---//
        $stmt_subjects = "SELECT DISTINCT sbj.id_subject, sbj.name_subject
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        WHERE asg.no_teacher = '$no_teacher' ORDER BY name_subject ASC";
    }

    $subjects = $assignments->getSubjectsFromTeachers($stmt_subjects);
} else {

    if (($grants & 8)) {
        $stmt_subjects = "SELECT DISTINCT  asgm.id_assignment, sbj.name_subject, sbj.id_subject,  CONCAT(col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS nombre_colaborador
        FROM iteach_academic.relationship_managers_assignments AS mnass
        INNER JOIN school_control_ykt.assignments AS asgm ON mnass.id_assignment = asgm.id_assignment 
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
        WHERE mnass.no_teacher = '$no_teacher' AND gps.id_group = '$id_group'";

        $stmt_groups = "SELECT * FROM 

        (SELECT groups.id_group, groups.group_code, rel_coord_aca.no_teacher 
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area

        UNION 

        SELECT gps.id_group, gps.group_code, rel_coord_aca.no_teacher 
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade)

        AS u

        WHERE no_teacher = $no_teacher ORDER BY group_code ASC";
    } else if ($grants & 4) {
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
    }


    $getGroups = $groups->getGroupFromTeachers($stmt_groups);
}

//--- --- ---//
if (isset($_GET['id_group'])) {
    //--- --- ---//
    $id_group = $_GET['id_group'];
    //--- --- ---//

    //--- --- ---//
}
//--- --- ---//

/*echo '<br/><br/><br/><br/><br/><br/><br/>';
print_r($getGroups);*/
if ($submodule == "students_subject") { ?>
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label" for="id_subject">* Elija una materia</label>
                        <form>
                            <select class="form-control" name="id_subject" id="id_subject">
                                <option selected value="">Elija una opción</option>
                                <?php foreach ($subjects as $subject) : ?>
                                    <?php if ($_GET['id_subject'] == $subject->id_subject) : ?>
                                        <option selected id="<?= $subject->id_assignment ?>" value="<?= $subject->id_subject ?>"><?= $subject->name_subject ?></option>
                                    <?php else : ?>
                                        <option id="<?= $subject->id_assignment ?>" value="<?= $subject->id_subject ?>"><?= $subject->name_subject ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <!-- -->
            </div>
        </div>
    </div>
<?php
} else {
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
                        <label class="form-control-label" for="id_group">* Elija un grupo</label>
                        <form>
                            <select class="form-control" name="id_group" id="id_group">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php if (!empty($getGroups)) : ?>
                                    <?php foreach ($getGroups as $group) : ?>
                                        <?php if ($_GET['id_group'] == $group->id_group) : ?>
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


            </div>
        </div>
    </div>
<?php
}
?>