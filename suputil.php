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
		$M = new MConf;
		$sql = "DELETE FROM $M->tablaut WHERE id=$id";
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br></br><div class='alerte'>L'utilisateur a bien été supprimé dans la base de données </div>";
	    else echo "</br></br><div class='alerte'>L'utilisateur n'a pas pu être supprimé dans la base de données !!!</div>";
	?>

</body>
</html>
