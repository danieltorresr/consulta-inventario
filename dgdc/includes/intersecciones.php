<?php 
include("FsFiles.php");

$gridFS = new FsFiles();

$datos=$_POST; //RECIBO ARREGLO DE FRONTEND EN LA VARIABLE DATOS

$datos['tipoSolucion']['idtipo']=(int)$datos['tipoSolucion']['idtipo'];

$datos['coordenadas'][0] = (double)$datos['coordenadas'][0];
$datos['coordenadas'][1] = (double)$datos['coordenadas'][1];
$intersecciones= array(
	'rubro'			=>$datos['rubro'],
	'tramo'			=>$datos['tramo'],
	'sentido'		=>(int)$datos['sentido'],
	'carril'		=>(int)$datos['carril'],
	
	'cadCarretera'	=>(float)$datos['cadCarretera'],
	'coordenadas'=>$datos['coordenadas'],
	'coordenadasGeo'=>array(
		'type'=>'Point',
		'coordinates'=>$datos['coordenadas']
		),

	'tipo'=>$datos['tipo'],
	'tipoSolucion'=>$datos['tipoSolucion'],
	'destinos'=>$datos['destinos'],
	'time' => new MongoDate(time()),
	'cadenamiento' => (int)$datos['cadenamiento'],
	'idEstacion' => $datos['idEstacion']
	);


//update record by id
if(isset($datos['id'])){
	$id = new MongoId($datos['id']);
	$intersecciones['time'] = $gridFS->getOne($datos['id'])->{'file'}['time'];
	$intersecciones['_id'] = $id;		
	$intersecciones['updateTime'] = new MongoDate(time());
	$gridFS->removeById($id);
}


$consulta=$gridFS->saveOne($datos['screenshot'],$intersecciones);
$consulta=$gridFS->getOne($consulta); 
echo(json_encode($consulta));



?>