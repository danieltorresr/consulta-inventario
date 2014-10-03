<?php
ini_set('max_execution_time', 300);

include('../controller/carreteras.php');
$client = new Estaciones;

//dinamico recibo valores
$tramo=$_GET;
$tramo['sentido'] = (int)$tramo['sentido']; 
$tramo['carril'] = (int)$tramo['carril']; 

$listEstaciones=$client->getEstaciones($tramo);
$i=0;
foreach ($listEstaciones as $value) {
	//var_dump($value);
	//var_dump('cadCarretera'.$value['cadCarretera'].'latitud   '.(float)$value['latitud'].'   '.'longitud'.(float)$value['longitud']);
	$path[$i]=array('cadCarretera'=>$value['cadCarretera'],'cadGeometrico'=>$value['cadGeometrico'],'latitud'=>$value['latitud'],'longitud'=>$value['longitud']);
$i++;
}
//var_dump($path);
echo json_encode($path);

?>