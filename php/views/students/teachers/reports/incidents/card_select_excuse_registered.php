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



$stmt_academic_levels = "SELECT DISTINCT al.academic_level, al.id_academic_level 
FROM iteach_academic.relationship_managers_assignments AS rma
INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = aclg.id_academic_level
INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
WHERE rma.no_teacher = '$_SESSION[colab]'";


$getAcademicLevels = $groups->getGroupFromTeachers($stmt_academic_levels);
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
                    <label class="form-control-label" for="academic_level_excuse">* Elija un nivel académico</label>
                    <form>
                        <select class="form-control" name="academic_level_excuse" id="academic_level_excuse">
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