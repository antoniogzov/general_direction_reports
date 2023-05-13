<?php
/* require_once '/general/php/models/Connection.php';
 */
require_once '../../../general/php/models/Connection.php';


$cn = new Connect();
$conexion = $cn -> doConnection();
date_default_timezone_set('America/Mexico_City');


$id_criterio = $_POST['id_criterio'];
//$id_criterio = $_GET['id_criterio'];
$items=array();
$sql="SELECT * FROM iteach_grades_quantitatives.conf_grade_gathering WHERE id_evaluation_plan = $id_criterio";
$result = mysqli_query($conexion,$sql);
while ($row = $result->fetch_assoc()) {
    $id_subcriterio = $row['id_conf_grade_gathering'];
    $id_ev_plan = $row['id_evaluation_plan'];
    $nombre = $row['name_item'];
    $registros[]=array('id_subcriterio'=>$id_subcriterio,
        'item'=>$nombre,
        'id_ep'=>$id_ev_plan);
    
}
$datos[] = array('resultado' => 'correcto',
   'registros' => $registros,
   'mensaje' => 'Se ubtuvieron los sub-criterios de evaluación.');


echo json_encode($datos);