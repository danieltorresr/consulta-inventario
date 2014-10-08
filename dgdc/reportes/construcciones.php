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
		$this->Cell(90,8,'Desarrollo de Carreteras',0,0,'C');
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
//---------------------CONSTRUCCION O GRUPO DE CONSTRUCCIONES----------------------------------------------
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',10);//MYRIADPROREGULAR
	$pdf->SetTextColor(57,97,176);
	$pdf->Cell(50,5,'Construcción',0,0,'L');
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(50,5,'Unidad',0,0,'L');
	$pdf->Cell(50,5,'Grupo de construcciones',0,0,'L');
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
	$pdf->Cell(18,5,'',1,0,'C',true);
	$pdf->Cell(27,5,'Cadenamiento',1,0,'C',true);
	$pdf->Cell(20,5,'Latitud',1,0,'C',true);
	$pdf->Cell(20,5,'Longitud',1,0,'C',true);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(2,5,' ','RL',0,'C',true);
	//$pdf->Cell(27,5,'Altitud',1,0,'C',true);
//---------medida de la construccion----------------------------------------------
	$pdf->SetFillColor(230,230,230);
	$pdf->Cell(44,5,'Medida de la construccion','TRL',0,'C',true);
	$pdf->Cell(59,5,'Distancia del hombro de la carretera','TRL',null,'C',true);
//--------------------------------------------------------------------------------
	$pdf->Ln();
	$pdf->SetFillColor(230,230,230);
	$pdf->SetLineWidth(.3);
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->Cell(18,5,'Inicio',1,0,'C',true);
	$pdf->SetFillColor(255,255,255);

	$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
	$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
	$pdf->Cell(27,5,round(($consulta['cadCarretera']['inicial']),4),1,0,'C',true);
	$pdf->Cell(20,5,round(($consulta['coorGeoIni']['coordinates'][1]),4),1,0,'C',true);//latitud
	$pdf->Cell(20,5,round(($consulta['coorGeoIni']['coordinates'][0]),4),'RBLT',0,'C',true);//longitud
	//$pdf->Cell(27,5,'',1,0,'C',true);
	$pdf->SetFillColor(230,230,230);
//---------medida de la construccion----------------------------------------------
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(2,5,' ','RL',0,'C',true);
	$pdf->Cell(44,5,$consulta['medidaConstruccion'],'RBLT,',0,'C',true);
	$pdf->Cell(59,5,round($consulta['distanciaMedia'],4),'RBLT',null,'C',true);
//--------------------------------------------------------------------------------
	$pdf->SetFillColor(255,255,255);
	$pdf->Ln();
	$pdf->SetFillColor(230,230,230);
	$pdf->SetLineWidth(.3);
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->Cell(18,5,'Fin',1,0,'C',true);
	$pdf->SetFillColor(255,255,255);

	$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
	$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
	$pdf->Cell(27,5,round(($consulta['cadCarretera']['final']),4),1,0,'C',true);
	$pdf->Cell(20,5,round(($consulta['coorGeoFin']['coordinates'][1]),4),1,0,'C',true);//latitud
	$pdf->Cell(20,5,round(($consulta['coorGeoFin']['coordinates'][0]),4),'RBLT',0,'C',true);//longitud
	
	$pdf->Ln(10);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetLineWidth(.3);
//-------------------------------------------ubicación--------------------------------------------
	$pdf->SetFillColor(230,230,230);
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->Cell(18,5,'Ubicacion','TLR',0,'C',true);
	$pdf->Cell(9,5,'Pisos','RLT',0,'C',true);
	$pdf->Cell(25,5,'Nivel respecto','RLT',0,'C',true);
	$pdf->Cell(33,5,'Tipo de construcción','LRT',0,'C',true);
	$pdf->Cell(42,5,'Curvatura','LRT',0,'C',true);
	$pdf->Cell(23,5,'Acotamiento','LRT',0,'C',true);
	$pdf->Cell(21,5,'Dispositivos','LRT',0,'C',true);
	$pdf->Cell(20,5,'Bandas de','LRT',0,'C',true);
	$pdf->Ln();

	$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
	$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
	$pdf->SetFillColor(230,230,230);
	$pdf->Cell(18,5,'','LRB',0,'C',true);
	$pdf->Cell(9,5,'','LRB',0,'C',true);
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
	$pdf->Cell(25,5,'a la vía','LRB',0,'C',true);
	$pdf->Cell(33,5,'','LRB',0,'C',true);
	$pdf->Cell(42,5,'','LRB',0,'C',true);
	$pdf->Cell(23,5,'','LRB',0,'C',true);
	$pdf->Cell(21,5,'','LRB',0,'C',true);
	$pdf->Cell(20,5,'alerta','LRB',0,'C',true);
	$pdf->Ln();

	$pdf->SetFillColor(255,255,255);
	$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
	$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
	$pdf->Cell(18,5,$consulta['ubicacionLado'],1,0,'C',true);
	$pdf->Cell(9,5,$consulta['niveles'],1,0,'C',true);
	$pdf->Cell(25,5,$consulta['posicionNivel'],1,0,'C',true);

	if ($consulta['grupo']==1) {
		$pdf->Cell(33,5,'grupo',1,0,'C',true);	
	}else{
		$pdf->Cell(33,5,'construccion',1,0,'C',true);	
	}
	$pdf->Cell(42,5,'Recta o ligeramente curva',1,0,'C',true);
	$pdf->Cell(23,5,$consulta['acotamiento']['descripcion'],1,0,'C',true);
	

	if(isset($consulta['dispositivos'])){
		$pdf->Cell(21,5,utf8_decode($consulta['dispositivos']['descripcion']),1,0,'C',true);
	}else{
		$pdf->Cell(21,5,utf8_decode('Ninguno'),1,0,'C',true);
	}
		

	if(isset($consulta['bandasDeAlerta']) && $consulta['bandasDeAlerta']=="true"){
		$pdf->Cell(20,5,"Si",1,0,'C',true);
	}
	else{
		$pdf->Cell(20,5,"No",1,0,'C',true);
	}
	$pdf->Ln(7);
	if($consulta['noAbatibles']==NULL)
		{//no mostrar nada cuando no existen abatibles 
		}
	else{
		$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',10);//MYRIADPROREGULAR
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(57,97,176);
		$pdf->Cell(50,5,'Elementos no Abatibles',0,0,'L');
		$pdf->Ln();
		$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
		$pdf->SetTextColor(0,0,0);
		$j=0;
		$i=0;
		$totalnoAbatibles=0;
		foreach ($consulta['noAbatibles'] as $clave => $contar) {
			$totalnoAbatibles=$totalnoAbatibles+1;
		}
		foreach($consulta['noAbatibles'] as $k => $noAbatibles) {
			if($i<=$totalnoAbatibles)
			{
				if($j<=2)
					{
					if(($noAbatibles['nombre'])=="Barrera de protecciÃ³n"|| $noAbatibles['nombre']=="Muros")
						{
						$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
						$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
						$pdf->SetFillColor(230,230,230);
						$pdf->Cell(53,5,utf8_decode($noAbatibles['nombre']),1,0,'L',true); //nombre
						
						$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
						$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
						$pdf->SetFillColor(255,255,255);
						$pdf->Cell(8,5,$noAbatibles['porcentaje'].'%',1,0,'C',true); //cantidad o porcentaje
							if($j==3 || $j==4)
							{
								$pdf->Cell(3,5,'','L',0,'C',true);
								$j++;
									if($j>=3){
										$j=0;
										$pdf->Ln(7);
									}
							}
							else
							{
								$pdf->Cell(3,5,'','L',0,'C',true);
								$j++;
									if($j>=3){
										$j=0;
										$pdf->Ln(7);
									}
							}
						}	
						else
						{
						$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
						$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
						$pdf->SetFillColor(230,230,230);
						$pdf->Cell(53,5,utf8_decode($noAbatibles['nombre']),1,0,'L',true); //nombre
						$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
						$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
						$pdf->SetFillColor(255,255,255);
						$pdf->Cell(8,5,$noAbatibles['cantidad'],1,0,'C',true); //cantidad o porcentaje
						if ($j==2)
							{
								$pdf->Cell(3,5,'','L',0,'C',true);
								$j++;
								if($j>=3){
									$j=0;
									$pdf->Ln(7);
								}
							}
							else{
								$pdf->Cell(3,5,'','L',0,'C',true);
								$j++;
								if($j>=3){
									$j=0;
									$pdf->Ln(7);
									}
								}
							}
						}
				$i++;
			}
		}//fin del foreach
		$pdf->Ln(6);
		} //fin del else
	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
	$pdf->SetFont('century-gothic-bold-1361531615','',10);//MYRIADPROREGULAR
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(57,97,176);
	
	$pdf->Cell(50,5,'Estudios**',0,0,'L');
	$pdf->Ln();
	$pdf->Cell(50,5,'analisis**',0,0,'L');

//--------------------------------------------------Estudios------------------------------------------------

	if (array_key_exists('estudios', $consulta)) {
   		//$pdf->Cell(50,5,'Estudios existe',0,0,'L');
    	//echo "estudios se encuentra";
    	$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',10);//MYRIADPROREGULAR
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(57,97,176);
		$pdf->Cell(50,5,'Estudios',0,0,'L');
		$pdf->Ln(5);

		$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFillColor(230,230,230);
		$pdf->Cell(38,5,'Sección',1,0,'C',true);
		$pdf->Cell(38,5,'Pendiente',1,0,'C',true);
		$pdf->Cell(38,5,'IRI PROM ',1,0,'C',true);
		$pdf->Cell(38,5,'TDPA',1,0,'C',true);
		$pdf->Cell(38,5,'Velocidad',1,0,'C',true);
		$pdf->Ln(5);
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFillColor(255,255,255);
		$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
		$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
		$pdf->Cell(38,5,'',1,0,'C',true);
		$pdf->Cell(38,5,'',1,0,'C',true);
		$pdf->Cell(38,5,'',1,0,'C',true);
		$pdf->Cell(38,5,'',1,0,'C',true);
		$pdf->Cell(38,5,'',1,0,'C',true);
		$pdf->Ln(6);
	}	
	else{
		 //$pdf->Cell(50,5,'Estudios no existe',0,0,'L');
		//echo "la variable no existe";
	}

	//--------------------------------------------analisis de riesgo---------------------------------------------
	if (array_key_exists('analisis', $consulta)) {
		//$pdf->Cell(50,5,'Estudios existe',0,0,'L');
	    //echo "estudios se encuentra";
		$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',10);//MYRIADPROREGULAR
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(57,97,176);
		$pdf->Cell(190,5,'Analisis de riesgo',0,0,'L');
		$pdf->Ln(5);

		$pdf-> AddFont('century-gothic-bold-1361531615');//MYRIADPROREGULAR
		$pdf->SetFont('century-gothic-bold-1361531615','',9);//MYRIADPROREGULAR
		$pdf-> AddFont('gothic_0');//MYRIADPROREGULAR
		$pdf->SetFont('gothic_0','',9);//MYRIADPROREGULAR
		$pdf->Cell(190,20,'',1,0,'C',true);
		$pdf->Ln();
	}
	else{
		//$pdf->Cell(50,5,'Estudios no existe',0,0,'L');
	    //echo "estudios se encuentra";
	}
//-----------------------------------fin del codigo del cuerpo de la pagina--------------------------------------------------
$pdf->Output();
?>
