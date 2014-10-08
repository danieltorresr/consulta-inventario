<?php
ini_set('max_execution_time', 300);
ini_set('memory_limit', '600M');
require('../fpdf16/fpdf.php');
//$pdf->AddPage();
//conexion a la bd

//-------------------consulta a estaciones-------------------
include ('../includes/gridfsController.php');
$tramos = new FsFiles();
//-------------------consulta a fsfiles---------------------
include ('../includes/gridfsControllerFoto.php');
$fsFiles = new FsFilesFoto();

$datos= array(
	'id'		   =>New MongoId($_GET['id']),
	'clave'			=>$_GET['id'],
	'tramo'        => $_GET['tramo'],
	'sentido'      =>(int)$_GET['sentido'],
	'carril'       =>(int)$_GET['carril']
	);
/*
$datos= array(
	'id'=>New MongoId('54120f76d57623100b00005c'),
	'clave'			=>'54120f76d57623100b00005c',
	'tramo'        =>"BC-130-09",
	'sentido'      =>1,
	'carril'       =>1
	);
	*/
//datos de pertenencia

$datPertenencia= array('_id'=>$datos['id']);

$consulta=$fsFiles->getAllInfo($datPertenencia);
$imagen = $fsFiles->getImagen(array('_id'=> New MongoId($datos['clave'])));

$datosCarretera=array('tramo'=>$datos['tramo'],'sentido'=>$datos['sentido'],'carril'=>$datos['carril']);
$nombreCarretera=$tramos->getCarretera($datosCarretera);

$nombreCarret=$nombreCarretera['carretera'];

$id=$datos['clave'];
//var_dump($id);

	$consulta['carretera']=$nombreCarret;
	$carretera=$consulta['carretera'];
	$carretera=$carretera;
	$carretera =explode("-",$carretera);
	$tramode=$carretera[0];
	$tramoa=$carretera[1];

//--------------------------------------------codigo para la clase-------------------------------
class PDF extends FPDF
{
//Page header
	function Header()
		{
	    //Logo
		//$this->Cell(100,5, $this->Image('../img/dgdc.jpg', 10, 8, 100,20,'jpg'),0,0,'R');//medidas originales
		$this->Cell(100,5, $this->Image('../img/dgdc.jpg', 10, 8, 70,20,'jpg'),0,0,'R');
		$this->SetFont('Arial','B',16);
		$this->Cell(90,5,'Dirección General de',0,0,'C');	
		$this->Ln();
		$this->Cell(100,5,'',0,0,'R');
		$this->Cell(90,8,'Desarrollo Carretero',0,0,'C');
		//Line break
		$this->Ln(15);
	//-----------------------------descripción del estudio----------------------------------------------------------------------------------------------------------------------
		$this-> AddFont('AdobeCaslonPro');
		$this->SetFont('AdobeCaslonPro','',14);

		$this->Image("../img/linea.jpg",null, null, 185,1,'jpg');
		$this->Cell(190,8,'Inventario de aprovechamiento del derecho de vías',0,0,'C');
		//$this->Ln();
		//$this->Cell(190,8,'',0,0,'C');
		$this->Ln();
		$this->Image("../img/linea.jpg",null, null, 185,1,'jpg');
		$this->Ln(4);
		$this-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$this->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR


		}
//Page footer
	function Footer()
		{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-11);
	    //Arial italic 8
	    $this->SetTextColor(0,0,0);
	    $this->SetFillColor(255,255,255);
	    $this->SetFont('Arial','B',8);
	    //Page number
	    $this->Line(10, 285, 200, 285); 
	    $this->SetLineWidth(1.5); 
	    $this->Cell(0,5,' '.$this->PageNo().'/{nb}',0,0,'C');
	}
}//fin de la clase


/*//------------------------codigo para poner una imagen de fondo-----------------------
$this->Cell(valor1, valor2, $this->Image('ruta-imagen/imagen', $this->GetX(),$this->GetY()),'LR',0,'R');
*/

//$pdf=new PDF();
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
//-----------------------------------------codigo de cuerpo de la pagina---------------------------

	//-------------------------------valores de pertenencia---------------------------
	$pdf->SetFillColor(230,230,230);
	$pdf->SetLineWidth(1);
	$pdf->SetDrawColor(111,114,115);
	$pdf->SetLineWidth(.3);
	$pdf->Cell(18,5,'',1,0,'C',true);
	$pdf->Cell(60,5,'Nombre',1,0,'C',true);
	$pdf->Cell(36,5,'de',1,0,'C',true);
	$pdf->Cell(36,5,'a',1,0,'C',true);
	$pdf->Cell(27,5,'Clave tramo',1,0,'C',true);
	$pdf->Cell(13,5,'Sentido',1,0,'C',true);
	$pdf->Ln();


//------------------------------------valores de pertenencia consulta----------------
	$pdf->Cell(18,5,'Carretera',1,0,'C',true);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(60,5,$consulta['carretera'],1,0,'C',true);
	//$pdf->Cell(60,5,$carretera,1,0,'C',true);
	$pdf->Cell(36,5,$tramode,1,0,'C',true);
	$pdf->Cell(36,5,$tramoa,1,0,'C',true);
	$pdf->Cell(27,5,$consulta['tramo'],1,0,'C',true);
	$pdf->Cell(13,5,$consulta['sentido'],1,0,'C',true);
	$pdf->Ln(10);
//--------------------------------------IMAGEN------------------------------------------
	$pdf->Image("http://localhost/DGDC/reportes/sinAlpha.php?id=$id",null, null, 190, 95,'png');
	$pdf->Ln();
		//--------------------------tabla de coordenadas y tabla de medidas----------------------------------------
//valores de pertenencia
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->SetFillColor(230,230,230);
	$pdf->SetLineWidth(1);
	$pdf->SetDrawColor(111,114,115);
	$pdf->SetLineWidth(.3);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(45,5,'Cadenamiento',1,0,'C',true);
	$pdf->Cell(45,5,'Latitud',1,0,'C',true);
	$pdf->Cell(45,5,'Longitud',1,0,'C',true);
	$pdf->Ln();

	$pdf->SetFillColor(255,255,255);	
	$pdf->Cell(45,5,$consulta['cadCarretera'],1,0,'C',true);
	$pdf->Cell(45,5,$consulta['coordenadas'][1],1,0,'C',true);
	$pdf->Cell(45,5,$consulta['coordenadas'][0],1,0,'C',true);
	$pdf->Ln(10);

	$pdf->SetFillColor(230,230,230);
	//$pdf->SetLineWidth(1);
	$pdf->Cell(30,5,'Tipo',1,0,'C',true);
	$pdf->Cell(30,5,'Tipo de solucion',1,0,'C',true);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(3,5,'','RL',0,'C',true);

	$pdf->SetFillColor(230,230,230);
	$pdf->SetDrawColor(111,114,115);
	$pdf->Cell(60,5,'Poblacion de destino a la izquierda',1,0,'C',true);
	$pdf->Cell(60,5,'Poblacion de destino a la derecha',1,0,'C',true);

	$pdf->Ln();
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(30,5,$consulta['tipo'],1,0,'C',true);
	$pdf->Cell(30,5,$consulta['tipoSolucion']['descripcion'],1,0,'C',true);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(3,5,'','RL',0,'C',true);

	$pdf->Cell(60,5,$consulta['destinos'][0]['poblacion'],1,0,'C',true);
	$pdf->Cell(60,5,$consulta['destinos'][1]['poblacion'],1,0,'C',true);

$pdf->Output();
?>
