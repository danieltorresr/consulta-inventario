<?php 
include("FsFiles.php");
$FsFiles = new FsFiles();
$datos=$_GET; 
/*$datos=array(
			"rubro"=>"construcciones",
			"tramo"=>"BC-001-01",
			"sentido"=>2,
			"carril"=>2
	);*/

$datos["sentido"] = (int)$datos["sentido"];
$datos["carril"] = (int)$datos["carril"];

$consulta=$FsFiles->getRubro($datos);
echo json_encode($consulta);
	
?>