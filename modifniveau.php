<?php
	session_start();
    require_once("session.php");
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
		$id = $_POST['id'];
		$niveau = $_POST['niveau'];
		$presence = $_POST['presence'];
		$pres="";
		foreach($_POST["presence"] as $value) {
			$pres .=$value;
		}
		echo $pres."<br>";
		$M = new MConf;
		$sql = "UPDATE $M->tablaut SET niveau=$niveau,presence=$pres WHERE id=$id";
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br></br><div class='alerte'>Le niveau de l'utilisateur a bien été modifié dans la base de données </div>";
	    else echo "</br></br><div class='alerte'>Le niveau de l'utilisateur n'a pas pu être modifié dans la base de données !!!</div>";
	    $M->close();
	?>

</body>
</html>
