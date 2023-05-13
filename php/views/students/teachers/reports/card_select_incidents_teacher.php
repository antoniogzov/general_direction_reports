<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
$id_teacher = 0;
$id_subject = 0;
$getGroups = array();
//--- --- ---//

if (isset($_GET['id_teacher'])) {
    $id_teacher = $_GET['id_teacher'];
}
//--- --- ---//
if (($grants & 8)) {
    $stmt_teacher = "SELECT DISTINCT no_colaborador, CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador ,' ', apellido_materno_colaborador) AS name 
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON rma.id_assignment = asg.id_assignment
    INNER JOIN colaboradores_ykt.colaboradores  AS colab ON asg.no_teacher = colab.no_colaborador
    WHERE rma.no_teacher =  '$_SESSION[colab]' AND no_colaborador != 0 ORDER BY name ASC";

} else if ($grants & 4) {
    //--- --- ---//
   
    //--- --- ---//
}


$getTeacher = $groups->getGroupFromTeachers($stmt_teacher);

//--- --- ---//
if (isset($_GET['id_teacher'])) {
    //--- --- ---//
    $id_group = $_GET['id_teacher'];
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
                        <label class="form-control-label" for="id_teacher">* Elija un profesor</label>
                        <form>
                            <select class="form-control" name="id_teacher" id="id_teacher">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php if (!empty($getTeacher)) : ?>
                                    <?php foreach ($getTeacher as $teacher) : ?>
                                        <?php if ($_GET['id_teacher'] == $teacher->no_colaborador) : ?>
                                            <option selected value="<?= $teacher->no_colaborador ?>"><?= $teacher->name ?></option>
                                        <?php else : ?>
                                            <option value="<?= $teacher->no_colaborador ?>"><?= $teacher->name ?></option>
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
                        <label class="form-control-label" for="id_teacher">* Elija un profesor</label>
                        <form>
                            <select class="form-control" name="id_teacher" id="id_teacher">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php if (!empty($getTeacher)) : ?>
                                    <?php foreach ($getTeacher as $teacher) : ?>
                                        <?php if ($_GET['id_teacher'] == $teacher->no_colaborador) : ?>
                                            <option selected value="<?= $teacher->no_colaborador ?>"><?= $teacher->name ?></option>
                                        <?php else : ?>
                                            <option value="<?= $teacher->no_colaborador ?>"><?= $teacher->name ?></option>
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