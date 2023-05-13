<?php
set_time_limit(0);

$module_name = 'iTeach';
$link_module = '#';
$sub_module_name = 'inicio';
$some_text = 'inicio';

include 'php/views/head.php';
$no_teacher = $_SESSION['colab'];
$module = 'ITEQ';
if (!GetGrants($module)) {
    header('Location: ../general/logIn.php');
    exit();
}

$grants = $_SESSION['grants' . $module];
?>
<script async>
    Swal.fire({
        text: 'Cargando...',
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
    })
</script>
<?php

include 'php/controllers/queries.php';
include 'php/models/subjects.php';
include 'php/models/helpers.php';
include 'php/models/academic_areas.php';
include 'php/models/assignments.php';
include 'php/models/expected_learnings.php';
$queries = new Queries;
$helpers = new Helpers;
$subjects = new Subjects;
$academicAreas = new AcademicAreas;
$assignments = new Assignments;
$expected_learnings = new ExpectedLearnings;

//--- --- ---//
$id_academic_area = 0;
if (isset($_GET['id_academic_area'])) {
    $module_name = 'Asignaturas';
    $link_module = 'index.php';
    $sub_module_name = ucfirst($academicAreas->getNameAcademicArea($_GET['id_academic_area']));
    $some_text = 'Materias';
    //--- --- ---//
    $id_academic_area = $_GET['id_academic_area'];
    if ($id_academic_area == 1) {
        $module_active = 'espanol';
    } else {
        $module_active = 'hebreo';
    }
    //--- --- ---//
}

$info_header_module = array(
    'module_name'     => $module_name,
    'link_module'     => $link_module,
    'sub_module_name' => $sub_module_name,
    'some_text'       => $some_text
);
//--- --- ---//

include 'php/views/sidebar.php';
include 'php/views/topnav.php';
include 'php/views/header.php';

if (isset($_GET['id_academic_area'])) {
    if (($grants & 8)) {
        include 'php/views/index/coordinator_start.php';
    } else if ($grants & 4) {
        include 'php/views/index/teacher_start.php';
    }
}

include 'php/views/footer1.php';
?>
<script src="js/functions/index/index.js" async></script>
<?php
include 'php/views/endpage.php';
