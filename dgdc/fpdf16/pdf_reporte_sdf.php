<?php
require('fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();

$var=8;
include("conecta.php");
if($conexion=mysql_connect("$servidor","$usuarios","$clave","$base"))
	{
	  mysql_select_db($base,$conexion);
		$busq="select * from tbl_servfitosanitario where id_contrato='$var';";
		$tabla=mysql_query($busq);
		while($registro=mysql_fetch_array($tabla, MYSQL_ASSOC))
			{
				$txt_remitente = $registro['txt_remitente'];
				$txt_acta = $registro['txt_acta'];
				$txt_inspectoria = $registro['txt_inspectoria'];
				$txt_remision = $registro['txt_remision'];
				$txt_uso = $registro['txt_uso'];
				$txt_producto = $registro['txt_producto'];
				$txt_subproducto = $registro['txt_subproducto'];
				$num_cantidad  = $registro['num_cantidad'];
				$num_volumen = $registro['num_volumen'];
				$txt_cultivoafectado = $registro['txt_cultivoafectado'];
				$txt_paisorigen = $registro['txt_paisorigen'];
				$txt_sintomas = $registro['txt_sintomas'];
				$txt_procedencia = $registro['txt_procedencia'];
				$txt_parte = $registro['txt_parte'];
				$txt_destino = $registro['txt_destino'];
				$txt_variedad = $registro['txt_variedad'];
				$txt_lotes = $registro['txt_lotes'];
				$fecha_ingreso = $registro['fecha_ingreso'];
				$id_servicio = $registro['id_servicio'];

				
			}
		mysql_free_result($tabla);


$pdf->SetFont('times','',10);
$pdf->Ln(20);
$pdf->Cell(190,8,'INFORME DE RESULTADOS DE ANÁLISIS FITOSANITARIO',0,0,'C');
$pdf->Ln();
$pdf->Cell(25,4,'Tipo de muestra:','TLR',0,'C');
$pdf->Cell(30,4,'Nacional:','TLR',0,'C');
$pdf->Cell(31,4,'Importada:','TLR',0,'C');
$pdf->Cell(34,4,'No. de muestras:','TLR',0,'C');
$pdf->Cell(35,4,'No. de remisión::','TLR',0,'C');
$pdf->Cell(35,4,'No. servicio','TLR',0,'C');
$pdf->Ln();
$pdf->Cell(25,4,'','BLR',0,'C');
$pdf->Cell(30,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(31,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(34,4,'('.$num_cantidad.')','BLR',0,'C');
$pdf->Cell(35,4,'('.$txt_remision.')','BLR',0,'C');
$pdf->Cell(35,4,'('.$id_contrato.')','BLR',0,'C');
$pdf->Ln();
$pdf->Cell(55,4,'Fecha y hora de recepción: ','TLR',0,'C');
$pdf->Cell(65,4,'No. Consecutivo de muestras:','TLR',0,'C');
$pdf->Cell(70,4,'Identificación de muestras y o lotes:','TLR',0,'C');
$pdf->Ln();
$pdf->Cell(55,4,'('.$fecha_ingreso.')','BLR',0,'C');
$pdf->Cell(65,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(70,4,'('.$txt_lotes.')','BLR',0,'C');

$pdf->Ln();
$pdf->Cell(25,4,'Virus:','TLR',0,'C');
$pdf->Cell(30,4,'Bacterias:','TLR',0,'C');
$pdf->Cell(31,4,'Hongos:','TLR',0,'C');
$pdf->Cell(34,4,'Nematodos:','TLR',0,'C');
$pdf->Cell(35,4,'Plagas insectiles:','TLR',0,'C');
$pdf->Cell(35,4,'Otro especificar:','TLR',0,'C');
$pdf->Ln();
$pdf->Cell(25,4,'muestra','BLR',0,'C');
$pdf->Cell(30,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(31,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(34,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(35,4,'('.$var1.')','BLR',0,'C');
$pdf->Cell(35,4,'('.$var1.')','BLR',1,'C');
$pdf->Ln();
$pdf->Cell(190,4,'DATOS DE LA MUESTRA',0,1,'L');
$pdf->Cell(25,4,'Remitente:','TL',0,'R');
$pdf->Cell(30,4,$txt_remitente,'T',0,'L');
$pdf->Cell(31,4,'Uso:','T',0,'R');
$pdf->Cell(34,4,$txt_uso,'T',0,'L');
$pdf->Cell(35,4,'Sintomas:','T',0,'R');
$pdf->Cell(35,4,$txt_sintomas,'TR',0,'L');
$pdf->Ln();
$pdf->Cell(25,4,'Pais de origen:','L',0,'R');
$pdf->Cell(30,4,$txt_paisorigen,'',0,'L');
$pdf->Cell(31,4,'Producto:','',0,'R');
$pdf->Cell(34,4,$txt_producto,'',0,'L');
$pdf->Cell(35,4,'Parte u organo:','',0,'R');
$pdf->Cell(35,4,$txt_parte,'R',0,'L');
$pdf->Ln();
$pdf->Cell(25,4,'Procedencia:','L',0,'R');
$pdf->Cell(30,4,$txt_procedencia,'',0,'L');
$pdf->Cell(31,4,'Cultivo:','',0,'R');
$pdf->Cell(34,4,$txt_cultivo,'',0,'L');
$pdf->Cell(35,4,'Parte u organo:','',0,'R');
$pdf->Cell(35,4,$txt_parte,'R',0,'L');
$pdf->Ln();
$pdf->Cell(25,4,'Localidad:','L',0,'R');
$pdf->Cell(30,4,$txt_localidad,'',0,'L');
$pdf->Cell(31,4,'Variedad:','',0,'R');
$pdf->Cell(34,4,$txt_variedad,'',0,'L');
$pdf->Cell(35,4,'Cantidad recibida:','',0,'R');
$pdf->Cell(35,4,$txt_cantidad,'R',0,'L');
$pdf->Ln();
$pdf->Cell(25,4,'Destino:','BL',0,'R');
$pdf->Cell(30,4,$txt_destino,'B',0,'L');
$pdf->Cell(31,4,'','B',0,'R');
$pdf->Cell(34,4,'','B',0,'L');
$pdf->Cell(35,4,'Condiciones:','B',0,'R');
$pdf->Cell(35,4,$txt_condiciones,'BR',1,'L');
$pdf->Ln();
$pdf->Cell(190,4,'DATOS DEL ANÁLISIS',0,1,'L');
$pdf->Cell(190,4,'Detecciones específicas:',0,0,'L');
$pdf->Ln();
$pdf->MultiCell(190,4,$txt_detecciones,1,'C');
$pdf->Cell(190,4,'Técnicas de detección usadas:',0,0,'L');
$pdf->Ln();
$pdf->MultiCell(190,4,$txt_detecciones,1,'C');
$pdf->Ln();
$pdf->Cell(190,4,'A) Revisión de muestras al microscopio esteroscopio:',0,0,'L');
$pdf->Ln();
$pdf->Cell(45,4,'Observaciones en lesiones:',0,0,'L');
$pdf->MultiCell(145,4,$txt_observaciones,1,'C');
$pdf->Cell(45,4,'Raspado con ahuja:',0,0,'L');
$pdf->MultiCell(145,4,$txt_raspado,1,'L');
$pdf->Cell(45,4,'Cinta Transparente:',0,0,'L');
$pdf->MultiCell(145,4,$txt_cinta,1,'L');
$pdf->Cell(45,4,'Cortes histológicos:',0,0,'L');
$pdf->MultiCell(145,4,$txt_cortes,1,'L');
$pdf->Ln();
$pdf->Cell(190,4,'B) Aislamiento y purificación en medio de cultivo:',0,0,'L');
$pdf->Ln();
$pdf->Cell(47,4,'Medio PDA a 30ºC/72 Hrs:',0,0,'L');
$pdf->MultiCell(143,4,'ddddddddddddddddddddddddddddddddddddddddddddddd'.$txt_medio.'vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv',1,'J');
$pdf->Cell(47,4,'Cámara húmeda a 30ºC/72 Hrs:',0,0,'L');
$pdf->MultiCell(143,4,$txt_camara,1,'L');
$pdf->Cell(47,4,'Otro medio:',0,0,'L');
$pdf->MultiCell(143,4,$txt_otromedio,1,'L');
$pdf->Ln();
$pdf->Cell(190,4,'REGISTRO DE RESULTADOS Y OBSERVACIONES',0,1,'L');
$pdf->Ln();
$pdf->Cell(50,4,'Identificación de muestras y lotes:',0,0,'L');
$pdf->MultiCell(140,4,$txt_identificacion,1,'C');
$pdf->Cell(45,4,'Resultados:',0,0,'L');
$pdf->MultiCell(145,4,$txt_resultados,1,'L');
$pdf->Cell(45,4,'Observaciones:',0,1,'L');
$pdf->MultiCell(190,4,$txt_ob,1,'L');
$pdf->MultiCell(190,4,'*Este documento es solo ampara los resultados de la(s) muestra(s) indicada(s)',0,'R');

$pdf->Ln(10);
$pdf->Cell(190,4,'Atentamente.',0,1,'L');
$pdf->Ln(15);
$pdf->Cell(190,4,'Ing Cesar Wilians García González.',0,1,'L');
$pdf->Cell(190,4,'Responsable del laboratorio.',0,1,'L');
/*

$pdf->Cell(190,4,'Fecha y hora de ingreso al laboratorio:','TBLR',0,'L');
$pdf->Ln();
$pdf->Cell(190,4,'No. consecutivo de muestras:','BLR',0,'L');
$pdf->Ln();
$pdf->Cell(190,4,'Identificación de muestras y/o lotes:','BLR',0,'L');
$pdf->Ln();
$pdf->Cell(190,4,'Datos de la muestra:','BLR',0,'L');
$pdf->Ln();
$pdf->Cell(55,4,'Cultivo o producto:',1,0,'R');
$pdf->MultiCell(135,4,'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm',1,'J');
$pdf->Cell(55,4,'Síntomas presentes:',1,0,'R');
$pdf->MultiCell(135,4,'',1,'J');
$pdf->Cell(55,4,'Uso:',1,0,'R');
$pdf->MultiCell(135,4,'',1,'J');
$pdf->Cell(55,4,'Procedencia:',1,0,'R');
$pdf->MultiCell(135,4,'',1,'J');

$pdf->Cell(55,4,'Cantidad y condiciones','LTR',0,'R');
$pdf->MultiCell(135,4,'ooooooooooo,.................','LTR','J');
$pdf->Cell(55,4,'de muestra de trabajo:','LRB',0,'R');
$pdf->MultiCell(135,4,'','LRB','J');
$pdf->Ln();
*/
$pdf->Output();
} 

mysql_close($conexion);
?>
