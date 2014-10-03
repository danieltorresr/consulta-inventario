<?php 
ini_set('max_execution_time', 300);
ini_set('memory_limit', '600M');
include("FsFiles.php");
$FsFiles = new FsFiles();
$datos=$_GET; 

//$result=$FsFiles->getOne($datos['id']);
//$result->{'file'}['screenshot'] = $result->getBytes();
//method to get chunks

$data = array('screenshot'=>$FsFiles->getChunks($datos['id']));
echo json_encode($data);	
?>