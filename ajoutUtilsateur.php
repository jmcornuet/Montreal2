<?php
	session_start();
	$prenom=$_SESSION['prenom'];
	$niveau=$_SESSION['niveau'];
	if (!$prenom) die();
	include("MGENconfig.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="bibglobal0.css" type="text/css" rel="stylesheet" media="screen"/>
    <script src="fonctions.js"></script>
</head>
<body onload="resizemenu0()" onresize="resizemenu()">

	<?php
		include("menu.php");
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$niveau = $_POST['niveau'];
		$login = $_POST['login'];
		$password = $_POST['password'];

		$M = new MConf;
		$sql = "INSERT INTO $M->tablaut (login,password,niveau,nom,prenom) VALUES ('".$login."','".$password."',".$niveau.",'".$nom."','".$prenom."')" ;
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br></br><div class='alerte'>L'utilisateur ".$prenom." ".$nom."  a bien été ajouté dans la base de données </div>";
	    else echo "</br></br><div class='alerte'>L'utilisateur ".$prenom." ".$nom."  n'a pas pu être ajouté dans la base de données !!!</div>";
	?>

</body>
</html>
