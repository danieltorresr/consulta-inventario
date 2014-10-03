<?php
require_once("../includes/ConexionMongodb.php");
/**
* Operaciones de entrada ys alida de archivos en  mongodb
*/
class Estaciones extends ConexionMongodb
{
	protected $estaciones;
	function __construct(){
		 parent::__construct();
		 $this->estaciones = $this->db->estaciones;//estaciones2
	}	
	//consulta para la lista de Estaciones en un arreglo[lat,long]
	function getEstaciones($consulta)
		{	
		return $this->estaciones->find($consulta)->sort(array('cadCarretera'=>-1));	
		}

	function getValorContinuo($valor,$valor2)
		{
			return $this->estaciones->find($valor)->sort($valor2)->limit(1);	
        }  

    function getValorAnterior($valor,$valor2)
		{
			return $this->db->estaciones->find($valor)->sort($valor2)->limit(1);	
        }    
    function getUltimoValor($valor2)
		{
			return $this->db->estaciones->find()->sort($valor2)->limit(1);	
        }  
    function getPrimerValor($valor2)
		{
			return $this->db->estaciones->find()->sort($valor2)->limit(1);	
        }  

}
