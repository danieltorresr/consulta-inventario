<?php 
require_once("ConexionMongodbInventario.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class FsFiles extends ConexionMongodbInventario
{
	protected $gridFS;
	function __construct(){
		 parent::__construct();
		 $this->gridFS = $this->db->getGridFS();
	}

	function getAll()
	{			
			$response = $this->gridFS->find();
			return iterator_to_array($response);
	}

	function getOne($id)
	{				
			$fileId = new MongoId($id);
			return $this->gridFS->findOne(array('_id'=> $fileId));			
	}

	function getBytes($f)
	{
		return $f->getBytes();
	}

	function saveOne($bytesB64,$meta){
		return $this->gridFS->storeBytes($bytesB64,$meta);
	}
	
	function getRubro($rubro)
	{
		$response = $this->gridFS->find($rubro);
		return iterator_to_array($response);
	}

	function getInventario($q){
		$response = $this->gridFS->find($q);
		return iterator_to_array($response);
	}

	function getScrenshootByFile($f){
		return $f->getBytes();
	}

	function getChunks($id){
		$fileId = new MongoId($id);	
		$chunks = $this->gridFS->chunks->find(array("files_id" => $fileId));
		
		$path = null;
		foreach ($chunks as $chunk) {
			$path .= $chunk['data']->bin;			
		}

		return $path;
	}
	
	function removeById($id){
		
		$response = $this->gridFS->remove(array("_id" => $id));
		return $response;
	}
}

