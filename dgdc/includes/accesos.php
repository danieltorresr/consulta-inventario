<?php 
include("FsFiles.php");

$gridFS = new FsFiles();

$datos=$_POST; //RECIBO ARREGLO DE FRONTEND EN LA VARIABLE DATOS

$datos['coordenadas'][0] = (double)$datos['coordenadas'][0];
$datos['coordenadas'][1] = (double)$datos['coordenadas'][1];
$accesos= array(
	'rubro'=>$datos['rubro'],
	'tramo'=>$datos['tramo'],
	'sentido'		=>(int)$datos['sentido'],
	'carril'		=>(int)$datos['carril'],
	//'screenshot'=>$datos['screenshot'],

	'cadCarretera'	=>(float)$datos['cadCarretera'],
	'coordenadas'=>$datos['coordenadas'],

	'coordenadasGeo'=>array(
		'type'=>'Point',
		'coordinates'=>$datos['coordenadas']
		),
	
	'tipo'=>$datos['tipo'],
	'destinos'=>$datos['destinos'],
	'time' => new MongoDate(time()),
	'cadenamiento' => (int)$datos['cadenamiento'],
	'idEstacion' => $datos['idEstacion'],	
	'tipoAcceso' => $datos['tipoAcceso'], //new fields
	'propiedades' => $datos['propiedades'],
	'viaVisible' => (int)$datos['viaVisible']	
	);

//update record by id
if(isset($datos['id'])){
	$id = new MongoId($datos['id']);
	$accesos['time'] = $gridFS->getOne($datos['id'])->{'file'}['time'];
	$accesos['_id'] = $id;		
	$accesos['updateTime'] = new MongoDate(time());
	$gridFS->removeById($id);
}

$consulta=$gridFS->saveOne($datos['screenshot'],$accesos);
$consulta=$gridFS->getOne($consulta); 
echo(json_encode($consulta));


?>