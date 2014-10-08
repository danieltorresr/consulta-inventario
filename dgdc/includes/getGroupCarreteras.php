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
		$states = array();

		foreach ($carreteras as $key => $value) {
			
			/*temp data*/
			$value['_id']['estado']	= explode('-',$value['_id']['carretera'])[0]; 
			$value['_id']['ejeCarretero']	= 1; 
			$value['_id']['concesionario']	= "CL"; 		
			array_push($all, $value['_id']);
			array_push($states, $value['_id']['estado']);

		}
		
		$response = array('states'=>array_unique($states),'data'=>$all);
		return json_encode($response);
	} 

}

$carretera = new getGroupCarreteras();
$result = $carretera->getGroupEst();
echo $result;

