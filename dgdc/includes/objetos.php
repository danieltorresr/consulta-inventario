<?php
require_once("ConexionMongodb.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class Objetos extends ConexionMongodb
{
	protected $objetos;
	function __construct(){
		 parent::__construct();
		 $this->objetos = $this->db->objetos;//collection objetos que contiene los documentos con diferente rubro
	}	

//insertar en la collection objetos
    function setObjeto($consulta)//guarda los documentos a la coleccion objeto
    {
    	//echo "accesos";
    	//var_dump($consulta);
    return $this->db->objetos->save($consulta);
    }
}

