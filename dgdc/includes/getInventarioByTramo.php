<?php 
//set_time_limit (240);
ini_set('memory_limit', '600M');

include("FsFiles.php");
$FsFiles = new FsFiles();
$datos=$_GET; 

$datos["sentido"] = (int)$datos["sentido"];
$datos["carril"] = (int)$datos["carril"];

unset($datos["_"]);

$datos['$or'] = array(array('rubro' => 'construcciones'),array('rubro' => 'accesos irregulares'));

$result=$FsFiles->getInventario($datos);
$response = array();
foreach ($result as $key => $value) {
	if(!isset($value->{'file'}['cadenamiento'])){
		$value->{'file'}['cadenamiento'] = 0;	
	}
	array_push($response, $value->{'file'});	
}	

echo json_encode($response);
	
?>