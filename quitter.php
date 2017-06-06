<?php
    session_start();
    include_once("session.php");
    if (!$prenom) die();
    include("MGENconfig.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="bibglobal.css" type="text/css" rel="stylesheet" media="screen"/>
</head>
<body>
	<?php 
		echo "A bientôt, $prenom !";
		session_destroy(); 
	?> 
</body>
</html>