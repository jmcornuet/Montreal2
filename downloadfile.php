<?php 

	if (PHP_OS == "Darwin") $path="../Montreal/export/".$_POST['filename'];
	//else $path="192.168.1.44/Montreal/export/".$_POST['filename'];
	else $path="export/".$_POST['filename'];
	header("Content-Type: application/octet-stream");    //
   	header("Content-Length: " . filesize($path));    
    header('Content-Disposition: attachment; filename='.$_POST['filename']);
    readfile($path);
?>