<?php 
require_once("ConexionMongodb.php");
/**
* Seguridad y autorizacion de usuarios
*/
class Auth extends ConexionMongodb
{
	protected $colUsers;
	function __construct()
	{
		parent::__construct();
		$this->colUsers = $this->db->users;
	}

	// REGISTRO
	/*
		Recibe un array  con los datos del usuario
		y lo regustra en la coleccion usuarios
	*/
	function setUser($user)
	{	
		/*$userCoder = array(
			'nombres'=>"Ted",
			'aPaterno'=>"Mosby",
			'aMaterno'=>"Martinez",
			'usuario'=>"ted",
			'pass'=>"1234pass",
			'level'=>"coder"
			);
        */
		$this->colUsers->insert($user);
		return $user;
	}

	//STATUS
	function isLoggedIn(){
		return (isset( $_SESSION['_id']))? true : false ;
	}

	// AUTHENTICATION
	/*
		Verifica que el usuario se encuentre en la base de datos
		y coincida con la contrasenya
	*/
	function authenticate($user)
	{
		$elements = array('pass'=>0);
		return $this->colUsers->findOne($user,$elements);
	}

	// AUTHORIZATION
	function authorizate($user){
		$_SESSION = $user;
		return $_SESSION;
	}
		//cerrar session
	function logOut(){
		unset($_SESSION);
		return session_destroy();
	}


	function getUsers(){
		return $this->colUsers->find();
	}


}