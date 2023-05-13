<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_group = 0;
$id_subject = 0;
$getGroups = array();
//--- --- ---//
if (isset($_GET['submodule'])) {
    $submodule = $_GET['submodule'];
}

if (isset($_GET['id_group'])) {
    $id_group = $_GET['id_group'];
}
//--- --- ---//
if ($submodule == "attendance_report_coordinator") {
    if (($grants & 8)) {
        $stmt_subjects = "SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject,  groups.id_group, groups.group_code, assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT  sbj.name_subject, sbj.id_subject, gps.id_group, gps.group_code, asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT  sbj.name_subject, sbj.id_subject, groups.id_group, groups.group_code, asg.print_school_report_card, asg.assignment_active, asg.no_teacher, groups.group_type_id
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
    
        WHERE no_teacher = $no_teacher AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4 AND u.id_group = '$id_group'";

        $stmt_groups = "SELECT * FROM 

        (SELECT groups.id_group, groups.group_code, assg.print_school_report_card, assg.assignment_active, rel_coord_aca.no_teacher, groups.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        
        UNION 
    
        SELECT gps.id_group, gps.group_code, asgm.print_school_report_card, asgm.assignment_active, rel_coord_aca.no_teacher, gps.group_type_id
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
    
        UNION 
    
        SELECT groups.id_group, groups.group_code, asg.print_school_report_card, asg.assignment_active, asg.no_teacher, groups.group_type_id
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS groups ON asg.id_group = groups.id_group)
    
        AS u
    
        WHERE no_teacher = $no_teacher AND print_school_report_card = 1 AND assignment_active = 1 AND group_type_id != 4";
        //--- --- ---//
    }
} else {
}

//--- --- ---//
if (isset($_GET['id_group'])) {
    //--- --- ---//
    $id_group = $_GET['id_group'];
    //--- --- ---//

    //--- --- ---//
}
//--- --- ---//
$getGroups = $groups->getGroupFromTeachers($stmt_groups);

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
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="att_type">* Elija el tipo de asistencia</label>
                    <form>
                        <?php if (!isset($_GET['att_type'])) : ?>
                            <select class="form-control" name="att_type" id="att_type" disabled>
                            <?php else : ?>
                                <select class="form-control" name="att_type" id="att_type">
                                <?php endif; ?>
                                <option selected value="" disabled>Elija una opción</option>
                                <option value="1">Excluyendo Justificadas</option>
                                <option value="2">Incluyendo Justificadas</option>
                                </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>