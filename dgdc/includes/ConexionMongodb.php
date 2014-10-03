<?php
class ConexionMongodb 
{			
		protected $conexion;
		const DB='tramos';

		//semicvaca1.dyndns.org:
		//out server
		//const SERVER='semicvaca1.dyndns.org:27020';


		//local server
		const SERVER='mongodb://jayroserver-pc:27020';
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