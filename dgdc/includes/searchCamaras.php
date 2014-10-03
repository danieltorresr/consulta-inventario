<?php
ini_set('max_execution_time', 300);
require('gridfsController.php');
$fsFiles = new FsFiles();
$fotos = new stdClass();
$consulta= array(
	'cadGeometrico'=>(float)$_GET['cad'],
	'tramo'        => $_GET['tramo'],
	'sentido'      =>(int)$_GET['sentido'],
	'carril'       =>(int)$_GET['carril']
	);
$datPertenencia= array('cadGeometrico'=> $consulta['cadGeometrico'],'tramo'=>$consulta['tramo'],'sentido'=>$consulta['sentido'],'carril'=>$consulta['carril']);
$cadGeometrico=array('cadGeometrico'=> 1);

$tresCamaras = $fsFiles->getImagenes($datPertenencia,$cadGeometrico);
$tresCamaras = iterator_to_array($tresCamaras);
function unaves(){

}
$primerFoto= true;
foreach ($tresCamaras as $key =>$foto)
	{
	$fotos->{$foto->file['camara']} = "data:image/jpg;base64,".base64_encode($foto->getBytes());
		if($primerFoto){
			$fotos->meta = $foto->file;	
		}
	}


echo json_encode( $fotos );