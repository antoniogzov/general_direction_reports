<?php
//--- --- ---//
$teachers = new teachers;
$accessAreasAcademicas = $teachers->getAccessAcademicAreas($_SESSION['colab']);
//--- --- ---//
$id_aca = 0;
if (isset($_GET['id_academic_area'])) {
    $id_aca = $_GET['id_academic_area'];
}
//--- --- ---//
$grants = $_SESSION['grantsITEQ'];
$module = '';
if (isset($info_header_module['sub_module_name'])) {
    $module = $info_header_module['sub_module_name'];
}
//--- --- ---//
?>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-gradient-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="./">
                <img src="../general/img/imgs/logo.png" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">ACADÉMICO</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $module_active == "alumnos" ? "active" : ""?>" href="alumnos.php">
                            <i class="fas fa-user-friends"></i>
                            <span class="nav-link-text">Alumnos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $module_active == "reportes" ? "active" : ""?>" href="reportes.php">
                            <i class="fas fa-file-invoice"></i>
                            <span class="nav-link-text">Reportes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $module_active == "mis_clases" ? "active" : ""?>" href="mis_clases.php">
                        <i class="fas fa-chalkboard"></i>
                            <span class="nav-link-text">Mis clases</span>
                        </a>
                    </li>
                    <?php if ($grants==15 || $grants==31) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $module_active == "malla_academica" ? "active" : ""?>" href="malla_academica.php">
                        <i class="ni ni-world-2"></i>
                            <span class="nav-link-text">Malla Académica</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">EVALUACIONES</h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                    <?php foreach ($accessAreasAcademicas as $aca): ?>
                        <li class="nav-item">
                            <?php if($aca->id_academic_area == 1): ?>
                            <a class="nav-link <?= $module_active == "espanol" ? "active" : ""?>" href="index.php?id_academic_area=<?=$aca->id_academic_area?>">
                            <?php else: ?>
                            <a class="nav-link <?= $module_active == "hebreo" ? "active" : ""?>" href="index.php?id_academic_area=<?=$aca->id_academic_area?>">
                            <?php endif; ?>
                                <i class="fas fa-language text-default"></i>
                                <span class="nav-link-text"><?=ucfirst($aca->name_academic_area)?></span>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
                <!-- Divider -->
                <!-- <hr class="my-3"> -->
                <!-- Heading -->
                <!--<h6 class="navbar-heading p-0 text-muted">Subtexto</h6> -->
                <!-- Navigation -->
                <!--<ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/components/alerts.html" target="_blank">
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text">Componente 1</span>
                        </a>
                    </li>
                </ul>-->
            </div>
        </div>
    </div>
</nav>
<!-- Main content -->
<div class="main-content" id="panel">
