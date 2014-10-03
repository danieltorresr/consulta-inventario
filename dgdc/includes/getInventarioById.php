<?php 
include("FsFiles.php");
$FsFiles = new FsFiles();
$datos=$_GET;

$result=$FsFiles->getOne($datos["id"]);

echo json_encode($result->{'file'});	
?>