<?php

$id=$_GET['id'];

//conexion a la bd
include ('../includes/gridfsControllerFoto.php');
$fsFiles = new FsFilesFoto();

//var_dump($id);
$imagen = $fsFiles->getImagen(array('_id'=> New MongoId($id)));
//$imagen = $fsFiles->getImagen(array('_id'=> New MongoId('540da7b763d5267012000042')));

$imagen =explode(",",$imagen);
$data=$imagen[1];

$data = base64_decode($data);

//$data = base64_decode($data);
// Cargar una imagen png con canales alfa
$im = imagecreatefromstring($data);
if ($im !== false) {
    header('Content-Type: image/png');
    imagepng($im);
    imagedestroy($im);
}
else {
    echo 'Ocurrió un error.';
}
?>