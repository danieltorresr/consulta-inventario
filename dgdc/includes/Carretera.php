<?php
require_once("ConexionMongodb.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class Estaciones extends ConexionMongodb
{
	protected $estaciones;
	function __construct(){
		 parent::__construct();
		 $this->estaciones = $this->db->estaciones;
	}

	function getCarretera()
	{			
			$response = $this->estaciones->find(array(),array('_id'=>0,'latitud'=>1, 'longitud'=>1,'cadGeometrico'=>1 ))->sort(array('cadGeometrico'=>1));
			
			return iterator_to_array($response);
	}

	function cadMasCercano($lng,$lat,$tramo,$sentido,$carril)
	{
	$punto = array(
				  //'tramo'=>$tramo,
				  'loc'=>array(
				    '$nearSphere'=>array(
 				     '$geometry'=>array(
				        'type' => 'Point',
				      'coordinates' => array( (float)$lng,  (float)$lat) 
				      ),
				      '$maxDistance'=>(100)
				    )
				  ),
				  'tramo'=>$tramo,
				  'sentido'=>(int)$sentido,
				  'carril'=>(int)$carril
				  );
		$response = $this->estaciones->find($punto)->limit(2);
		
	return $response;

	}
}
