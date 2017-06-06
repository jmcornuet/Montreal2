<?php
	session_start();
	$prenom=$_SESSION['prenom'];
	$niveau=$_SESSION['niveau'];
	if (!$prenom) die();
	include("MGENconfig.php");
    require_once("session.php");
    function crypte($s,$n) {
	    exec('./cryptb "'.$s.'" '.$n,$resultat,$ret);
	    return $resultat[0];
    }
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
		$presence = $_POST['presence'];
		$pres="";
		foreach($_POST["presence"] as $value) {
			$pres .=$value;
		}
		echo $pres."<br>";
		$login = $_POST['login'];
		$password = crypte($_POST['password'],40);
		echo $password."<br>";
		$M = new MConf;
		$sql = "INSERT INTO $M->tablaut (login,password,niveau,nom,prenom,presence) VALUES ('".$login."','".$password."',".$niveau.",'".$nom."','".$prenom."','".$pres."')" ;
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br></br><div class='alerte'>L'utilisateur ".$prenom." ".$nom."  a bien été ajouté dans la base de données </div>";
	    else echo "</br></br><div class='alerte'>L'utilisateur ".$prenom." ".$nom."  n'a pas pu être ajouté dans la base de données !!!</div>";
	?>

</body>
</html>
