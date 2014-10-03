<?php 

include("objetos.php");
$objetos = new Objetos();

//ficticio
$terraplenes= array(
	'rubro'=>"terraplenes",
	'tramo'=>"A-027-01",
	'sentido'=>2,
	'carril'=>1,
	'screenshot'=>'idMongo',
	'cadCarretera'=>array(
		'inicial'=>'44100',
		'final'=>'44120'
		),

	'coordenadas'=>array(
		'inicial'=>[-103.933170,18.942158],
		'final'=>[-103.937046,18.938255]
		),

	'coorGeoIni'=>array(
		'type'=>"Point",
		'coordinates'=>[-103.933170,18.942158]
		),

	'coorGeoFin'=>array(
		'type'=>'Point',
		'coordinates'=>[-103.933170,18.942158]
		),

	'ubicacionLado'=>'izquierdo',
	'time' => new MongoDate(time())
	);

echo json_encode($terraplenes);
$consulta=$terraplenes;
//var_dump($consulta);
$objetos->setObjeto($consulta);
?>