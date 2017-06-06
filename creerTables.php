<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	ob_implicit_flush(true);
    require_once("session.php");
	if (!$prenom) die();
	include("MGENconfig.php");
	$raz = $_POST['razadh'];
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
	$tadherr=false;
	$M = new MConf;
	//clonage de la table des adhérents
	$ntable = $_POST['ntadh'];
	$sql = "CREATE TABLE $ntable LIKE $tadh";
	$reponse = $M->querydb($sql);
	if (!$reponse) {echo "</br><div class='alerte'>ATTENTION Impossible de créer la table $ntable</div>";$tadherr=true;}
	else {
		$sql = "INSERT $ntable SELECT * FROM $tadh";
		$reponse = $M->querydb($sql);
		if ($raz == "raz") {
			$sql = "SELECT id FROM $ntable ORDER BY id DESC LIMIT 1";
			$reponse = $M->querydb($sql);
			$donnees = $reponse->fetch();
			$maxid = $donnees['id'];
			for ($i=0;$i<=$maxid;$i++) {
				$sql = "SELECT * FROM $ntable WHERE id=$i";
				$reponse = $M->querydb($sql);
			    if($donnees = $reponse->fetch()) {
			    	$activites = $donnees['activites'];
			    	$nactivites = str_replace("P", "A", $activites);
			    	$sql = "UPDATE $ntable SET cotisation=1, activites='$nactivites' WHERE id=$i";
			    	$reponse = $M->querydb($sql);
			    	//if (($i>600)&&($i<650))echo "mise à zéro de l'enregistrement $i $nactivites  <br>";
			    }
			}
			echo "</br><div class='alerte'>La table $ntable a bien été créée, copiée et les cotisations ont été réinitalisées</div>";
		} else echo "</br><div class='alerte'>La table $ntable a bien été créée, copiée mais les cotisations n'ont pas été réinitalisées</div>";
	}
	//clonage de la table des animateurs
	$tanierr=false;
	$ntable = $_POST['ntani'];
	$sql = "CREATE TABLE $ntable LIKE $tani";
	$reponse = $M->querydb($sql);
	if (!$reponse) {echo "</br><div class='alerte'>ATTENTION Impossible de créer la table $ntable</div>";$tanierr=true;}
	else {
		$sql = "INSERT $ntable SELECT * FROM $tani";
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br><div class='alerte'>La table $ntable a bien été créée et copiée</div>";
		else echo "</br><div class='alerte'>ATTENTION La table $ntable n'a pas pu être copiée (elle est vide)</div>";
	}
	//clonage de la table des animateurs
	$tacterr=false;
	$ntable = $_POST['ntgra'];
	$sql = "CREATE TABLE $ntable LIKE $tact";
	$reponse = $M->querydb($sql);
	if (!$reponse) {echo "</br><div class='alerte'>ATTENTION Impossible de créer la table $ntable</div>";$tacterr=true;}
	else {
		$sql = "INSERT $ntable SELECT * FROM $tact";
		$reponse = $M->querydb($sql);
		if ($reponse) echo "</br><div class='alerte'>La table $ntable a bien été créée et copiée</div>";
		else echo "</br></br><div class='alerte'>ATTENTION La table $ntable n'a pas pu être copiée (elle est vide)</div>";
	}

	if (!$tadherr) $tadh = $_POST['ntadh'];
	if (!$tanierr) $tani = $_POST['ntani'];
	if (!$tacterr) $tact = $_POST['ntgra'];
	$_SESSION['tadh'] = $tadh;
	$_SESSION['tani'] = $tani;
	$_SESSION['tact'] = $tact;
	$ff = fopen("../conf/tables.conf","w");
	if ($ff) {
		fwrite($ff,"adh=".$tadh."\n");
		fwrite($ff,"ani=".$tani."\n");
		fwrite($ff,"act=".$tact."\n");
//		echo "</br><div class='alerte'>Les tables ont bien été modifiées</div>";
	} else echo "</br><div class='alerte'>Les tables n'ont pas pu être modifiées !!!</div>";
	fclose($ff);

	echo "</br><div class='alerte'>Les nouvelles tables ont bien été inscrites dans le fichier de configuration</div>";
?>
