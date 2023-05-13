        <?php
$listStudent = $attendance->getListStudentByGroup($_GET['id_group']);
        function agregar_zip($dir, $zip,$listStudent)

        {
            
          if (is_dir($dir)) {
            if ($da = opendir($dir)) {
                foreach ($listStudent as $student) {
                    $boleta=$student->student_code.".pdf";      
                          if (file_exists($dir . $boleta)) {
                           // echo "Agregando archivo: $dir$boleta <br/>";
                            $zip->addFile($dir . $boleta, $boleta);
                          }
                          
                        }
                      }
                }
                
              closedir($da);
            }
          

        ini_set("memory_limit", "5756M");
        $zip = new ZipArchive();
        //$dir = 'uploads/MAN-R5-2108-04/';
        //dirname(__DIR__, 10) . "/boletas/periodo_" . $id_period . "/" . $str_aca . "/" . "cuantitativa/" . strtoupper($student->student_code) . ".pdf";
        $id_period = $_GET['id_period'];
        
        $id_area_Aca = $_GET['id_academic'];
        if ($id_area_Aca == "esp") {
            $str_aca = "esp/cuantitativa";
            $str_view="Español";
        } else {
            $str_aca = "heb/cuantitativa";
            $str_view="Hebreo";
        }
        $nombre = "boletas";
        $dir = dirname(__DIR__, 10) . "/boletas/periodo_".$id_period."/".$str_aca."/";
        $rutaFinal = "boletas/";
        $archivoZip = $nombre . "__" . time() . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) !== TRUE) {
          $error .= "ERROR AL CREAR";
        } else {
                agregar_zip($dir, $zip,$listStudent);
          
          $zip->close();
        }


?>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive" id="div_tabla">
<?php
foreach ($listStudent as $student) {
    $group_code=$student->group_code;
}
if (file_exists($archivoZip)) {    ?>
    <h1>INFORMACIÓN DE ARCHIVO GENERADO</h1>
    <h2>Grupo: <?=$group_code ?></h2>
    <h2>Área académica: <?=$str_view ?></h2>
    <h2>Periodo: <?=$_GET['id_period'] ?> </h2>
    <h3>Nombre del archivo: <?=$archivoZip ?> </h3>
    <br>
    <a href="<?=$archivoZip ?>" target="_blank"><button class="btn btn-icon btn-primary" type="button" id="export_zip" data-toggle="tooltip" data-placement="top" title="Archivo *.zip">
                            <span class="btn-inner--icon">
                            <i class="fas fa-download"></i>
                            </span>
                            <span class="btn-inner--text">Descargar</span>
                        </button></a>
    <?php
    
    
    }else{ ?>
        <h1>NO SE ENCONTRARON ARCHIVOS PARA LA SIGUIENTE INFORMACIÓN</h1>
        <h2>Grupo: <?=$group_code ?></h2>
        <h2>Área académica: <?=$str_view ?></h2>
        <h2>Periodo: <?=$_GET['id_period'] ?> </h2>
        <?php
    
    }
?>
			</div>
		</div>
	</div>


