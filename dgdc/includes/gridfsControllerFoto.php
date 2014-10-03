<?php 
require_once("ConexionMongodbInventario.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class FsFilesFoto extends ConexionMongodbInventario
{
	protected $gridFS;
	function __construct()
	{
		 parent::__construct();
		 $this->gridFS = $this->db->getGridFS();
	}

//-------------------------------------------consulta para reporte pdf-----------------------
    function getAllInfo($datPertenencia)
	{
		//$elementos = array('tramo' => "BC-001-01",'sentido' =>2,'carril' =>2);
		//$sort = array('cadGeometrico'=> -1);
		//$elementos =  array("metadata.tramo"=>"BC-001-01");

		$resultado = $this->db->fs->files->findOne($datPertenencia);
		return $resultado;		 

	}
	function getInfo()
	{
		$resultado=$this->db->fs->files->find()->limit(2);
		return $resultado;
	}
//-------------------------------------------consulta para la foto---------------------------
  
  	/*function getImagenes($imagenes)
		{
			//return $this->db->fs->files->find($imagenes);
			return $this->gridFS->find($imagenes);
        }   
	*/
    function getImagen($consulta)
	{			
		$response = $this->gridFS->findOne($consulta);
		return $base64 = $response->getBytes();
	}

	function getImagenfpdf($consulta)
	{
		return $this->gridFS->findOne($consulta);
	}

    
    }//llave final de la clase
