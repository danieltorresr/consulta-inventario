<?php 
include("FsFiles.php");
$FsFiles = new FsFiles();
$datos=$_GET;

$result=$FsFiles->getOne($datos["id"]);

$result->{'file'}['screenshot'] = $FsFiles->getChunks($result->{'file'}['_id']->{'$id'});

echo json_encode($result->{'file'});	
?>