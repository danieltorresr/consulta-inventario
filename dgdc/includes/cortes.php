<?php 
include("FsFiles.php");
$gridFS = new FsFiles();

$datos=$_POST; //RECIBO ARREGLO DE FRONTEND EN LA VARIABLE DATOS

$datos['cadCarretera']['inicial'] = (double)$datos['cadCarretera']['inicial']; 
$datos['cadCarretera']['final'] = (double)$datos['cadCarretera']['final'];

$datos['coordenadas']['inicial'][0] = (double)$datos['coordenadas']['inicial'][0]; 
$datos['coordenadas']['inicial'][1] = (double)$datos['coordenadas']['inicial'][1]; 

$datos['coordenadas']['final'][0] = (double)$datos['coordenadas']['final'][0];
$datos['coordenadas']['final'][1] = (double)$datos['coordenadas']['final'][1];

$cortes= array(
	'rubro'=>$datos['rubro'],
	'tramo'=>$datos['tramo'],
	'sentido'		=>(int)$datos['sentido'],
	'carril'		=>(int)$datos['carril'],
	//'screenshot'=>$datos['screenshot'],

	'cadCarretera'	=>$datos['cadCarretera'],
	'coordenadas'=>$datos['coordenadas'],

	'coorGeoIni'	=>array(
		'type'		=>"Point",
		'coordinates'=>$datos['coordenadas']['inicial']
		),

	'coorGeoFin'=>array(
		'type'=>"Point",
		'coordinates'=>$datos['coordenadas']['final']
		),
		
	'ubicacionLado'=>$datos['ubicacionLado'],
	'time' => new MongoDate(time()),
	'cadenamiento' => (int)$datos['cadenamiento'],
	'idEstacion' => $datos['idEstacion'],
	'medida' => (double)$datos['medida']		
	);

//update record by id
if(isset($datos['id'])){
	$id = new MongoId($datos['id']);
	$cortes['time'] = $gridFS->getOne($datos['id'])->{'file'}['time'];
	$cortes['_id'] = $id;		
	$cortes['updateTime'] = new MongoDate(time());
	$gridFS->removeById($id);
}

$consulta=$gridFS->saveOne($datos['screenshot'],$cortes);
$consulta=$gridFS->getOne($consulta); 
echo(json_encode($consulta));

?>