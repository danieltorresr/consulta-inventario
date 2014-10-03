<?php
ini_set("memory_limit","500M");
require("ConexionMongodb.php");

class getGroupCarreteras extends ConexionMongodb
{	

	function getGroupEst()
	{
		$consulta= array('$group' =>array('_id' =>array('carretera'=>'$carretera','tramo'=>'$tramo','sentido'=>'$sentido','carril'=>'$carril')));
		$carreteras=$this->db->estaciones->aggregate($consulta)['result'];
		$all = array();

		foreach ($carreteras as $key => $value) {
			$value['_id']['estado']	= "Morelos"; 
			$value['_id']['ejeCarretero']	= 1; 
			$value['_id']['concesionario']	= "CL"; 		
			array_push($all, $value['_id']);

		}

		return json_encode($all);
	} 

}

$carretera = new getGroupCarreteras();
$result = $carretera->getGroupEst();
echo $result;

