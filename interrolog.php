<?php
	setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
	$jour=strftime("%d/%m/%Y");
	require_once("MGENconfig.php");
	$M = new MConf;
	$tlog = $M->getlog();	
	$sql = "SELECT * FROM $tlog WHERE jour='$jour' ORDER BY id DESC";
	$reponse = $M->querydb($sql);
	$message = "";
	while ($donnees=$reponse->fetch()) {
		$message .= $donnees['id']."&".$donnees['utilisateur']."&".$donnees['requete']."&".$donnees['sessionid']."$";
	}
	$M->close();
	$message = substr($message,0,strlen($message)-1);
	echo $message;
?>