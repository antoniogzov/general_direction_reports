<?php
include 'php/views/header.php';

if (isset($_GET['submodule'])) {
    $submodule = $_GET['submodule'];
    switch ($submodule) {
        case 'reportes_direccion':
            $include_file = 'php\views\reports\reportes_direccion\direction_reports.php';
            /* $info_header_module = array(
                'module_name'     => 'Malla Académica',
                'link_module'     => 'malla_academica.php',
                'sub_module_name' => 'Mis Profesores',
                'some_text' => 'Mis Profesores'
            ); */
            break;
        default:
            header('Location: index.php');
            exit();
    }
    include $include_file;
} else {

    include 'php/views/main_menu/card_select_main_menu.php';
}



include 'php/views/footer.php';
