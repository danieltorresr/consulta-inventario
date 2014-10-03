<?php 
require_once("ConexionMongodb.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class FsFiles extends ConexionMongodb
{
	protected $gridFS;
	function __construct()
	{
		 parent::__construct();
		 $this->gridFS = $this->db->getGridFS();
	}

	function getAll()
	{			
			$response = $this->gridFS->find();
			return iterator_to_array($response);
	}

	function getOne($consulta)
	{			
		    $response = $this->gridFS->findOne($consulta);
			return $base64 = base64_encode( $response->getBytes() );
	}

/*
	function getBytes($f)
	{
		return $f->getBytes();
	}
*/

	function saveOne($bytesB64)
	{
		return $this->gridFS->storeBytes($bytesB64);
	}

	function storeFile($file,$estacion)
	{ 
		return $this->gridFS->storeFile($file,$estacion);
		//return $this->gridFS->storeFile($file['tmp_name'],array('filename'=>$file['name'])); <- no borrar
	}

	function getFirst()
	{
		$elementos = array('tramo' => "BC-001-01",'sentido' =>2,'carril' =>2,'camara'=>"central");
		$sort = array('cadGeometrico'=> 1);
		//$elementos =  array("metadata.tramo"=>"BC-001-01");
		$resultado = $this->db->fs->files->find($elementos)->sort($sort)->limit(1);

		foreach ($resultado as $value) {
		    return $cadFinal = $value['cadGeometrico'];
		}

//		$resultado = $this->gridFS->find($elementos);
		//$resultado = $this->gridFS->sort($elementos);
	}

	function getLast()
	{
		$elementos = array('tramo' => "BC-001-01",'sentido' =>2,'carril' =>2,'camara'=>"izquierda");
		$sort = array('cadGeometrico'=> -1);
		//$elementos =  array("metadata.tramo"=>"BC-001-01");
		$resultado = $this->db->fs->files->find($elementos)->sort($sort)->limit(1);

		 foreach ($resultado as $value) {
		    return $cadFinal = $value['cadGeometrico'];
		}
	}

	function getIdEstacion($idEstacion)
	{
		return $this->db->estaciones->findOne($idEstacion);
    }   

    function getFoto($consulta)
	{			
		    $response = $this->gridFS->findOne($consulta);
			return $base64 = base64_encode( $response->getBytes() );
	}

	function getImagenes($datPertenencia,$cadGeometrico)
	{
		return $this->gridFS->find($datPertenencia)->sort($cadGeometrico)->limit(3);	
    }


    function getCarretera($datosCarretera)
		{
			return $this->db->estaciones->findOne($datosCarretera);
		}
}
