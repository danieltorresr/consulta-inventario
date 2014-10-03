<?php
class ConexionMongodbInventario 
{			
		protected $conexion;

		/*Produccion*/
		//const DB='inventario';

		/*Desarrollo*/
		const DB='devInventario';

		//out server
		//const SERVER='semicvaca1.dyndns.org:27021';

		//local server
		const SERVER='mongodb://jayroserver-pc:27021';

		protected $db;

		function __construct() 
		{
			$this->conexion  = new MongoClient(self::SERVER);
			$this->db = $this->conexion->selectDB(self::DB);
		}

		function close(){
			return $this->db->close();	
		}
		
}