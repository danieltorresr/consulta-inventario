<?php 
include("FsFiles.php");

$gridFS = new FsFiles();

$datos=$_POST; //RECIBO ARREGLO DE FRONTEND EN LA VARIABLE DATOS

$datos['tipo']['idtipo']=(int)$datos['tipo']['idtipo'];
$datos['CoordInicial'][0] = (double)$datos['CoordInicial'][0];
$datos['CoordInicial'][1] = (double)$datos['CoordInicial'][1];

$datos['CoordFinal'][0] = (double)$datos['CoordFinal'][0];
$datos['CoordFinal'][1] = (double)$datos['CoordFinal'][1];

$datos['orillas'][0] = (double)$datos['orillas'][0];
$datos['orillas'][1] = (double)$datos['orillas'][1];

$datos['curvatura']['id'] = (int)$datos['curvatura']['id'];
$datos['acotamiento']['id'] = (int)$datos['acotamiento']['id'];

if(!isset($datos['noAbatibles'])){
	$datos['noAbatibles'] = null;	
}else{
	foreach ($datos['noAbatibles'] as $key => $item) {	
		//var_dump($item);
		foreach ($item as $k => $value) {
			if($k != "nombre"){
				$datos['noAbatibles'][$key][$k] = (int)$value; 			
			}	
		}
	}	
}


$construcciones= array(
	'rubro'			=>$datos['rubro'],
	'tramo'			=>$datos['tramo'],
	'sentido'		=>(int)$datos['sentido'],
	'carril'		=>(int)$datos['carril'],	

	'cadCarretera'	=>array(
		'inicial'	=>(Float)$datos['cadInicial'],
		'final'		=>(Float)$datos['cadFinal']
		),

	'coordenadas'	=>array(
		'inicial'=>$datos['CoordInicial'],
		'final'=>$datos['CoordFinal'],
		'orillas'=>$datos['orillas']
		),

	

	'coorGeoIni'	=>array(
		'type'		=>"Point",
		'coordinates'=>$datos['CoordInicial']
		),

	'coorGeoFin'=>array(
		'type'=>"Point",
		'coordinates'=>$datos['CoordFinal']
		),
	'noAbatibles'=>$datos['noAbatibles'],

	'posicionNivel'=>$datos['posicionNivel'],
	'ubicacionLado'=>$datos['ubicacionLado'],
	'niveles'=>(int)$datos['niveles'],
	'tipo'=>$datos['tipo'],
	'distanciaMedia'=>(double)$datos['distanciaMedia'],
	'grupo'=>$datos['grupo'] === 'true'? true : false,
	'medidaConstruccion'=>(Float)$datos['medidaConstruccion'],
	'curvatura' => $datos['curvatura'],
	'acotamiento' => $datos['acotamiento'],
	'dispositivos' => $datos['dispositivos'],
	'bandasDeAlerta' => $datos['bandasDeAlerta'] === 'true'? true : false,
	'time' => new MongoDate(time()),
	'cadenamiento' => (int)$datos['cadenamiento'],
	'idEstacion' => $datos['idEstacion']
		);

/*$consulta=json_encode($construcciones2);*/

//update record by id
if(isset($datos['id'])){
	$id = new MongoId($datos['id']);
	$construcciones['time'] = $gridFS->getOne($datos['id'])->{'file'}['time'];
	$construcciones['_id'] = $id;		
	$construcciones['updateTime'] = new MongoDate(time());
	$gridFS->removeById($id);
}

$consulta=$gridFS->saveOne($datos['screenshot'],$construcciones);
$consulta=$gridFS->getOne($consulta); 

echo(json_encode($consulta));

?>