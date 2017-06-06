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
		$M = new MConf;
		$reponse = $M->querydb("SHOW TABLES");
		$nomtable = array();
		while ($donnees = $reponse->fetch()) {
			$table = $donnees['Tables_in_clubmgen'];
			if (($table != "authentification")&&(!strstr($table,"log"))) array_push($nomtable,$table);
		}
		$M->close();
		$annee1 = $annee;
		$annee0 = strval(intval($annee)-1);
		$annee2 = strval(intval($annee)+1);
		$saison0 = $annee0."-".$annee1;
		$saison1 = $annee1."-".$annee2;
		$ntable = array();
		for ($i=0;$i<count($nomtable);$i++) {
			if ((strstr($nomtable[$i],"tadh")) && (strlen($nomtable[$i])==8)) array_push($ntable,"tadh".$annee1);
			if (strstr($nomtable[$i],"tanimateurs")) array_push($ntable,"tanimateurs".$annee1);
			if (strstr($nomtable[$i],"tgract")) array_push($ntable,"tgract".$annee1);
		}
		asort($ntable); 
	?>
	<div class="titre1" style="text-align:center">Passage de <?echo $saison0." à ".$saison1 ?> </span></div>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form action="creerTables.php" method="post">
				<table class="saisie">
					<tr>
						<td>Mise à zéro de la table des adhérents : </td>
						<td>&nbsp;&nbsp;</td>
						<td><input name="razadh" type="checkbox" value="raz"></td>
					</tr>
					<tr><td></td><td></td><td></td></tr>
					<tr>
						<td>Nom de la nouvelle table des adhérents : </td>
						<td>&nbsp;&nbsp;</td>
						<td><input name="ntadh" type="text" size=20 value="<?php echo $ntable[0] ?>"></td>
					</tr>
					<tr><td></td><td></td><td></td></tr>
					<tr>
						<td>Nom de la nouvelle table des animateurs : </td>
						<td>&nbsp;&nbsp;</td>
						<td><input name="ntani" type="text" size=20 value="<?php echo $ntable[1] ?>"></td>
					</tr>
					<tr><td></td><td></td><td></td></tr>
					<tr>
						<td>Nom de la nouvelle table des activités : </td>
						<td>&nbsp;&nbsp;</td>
						<td><input name="ntgra" type="text" size=20 value="<?php echo $ntable[2] ?>"></td>
					</tr>
				</table>
				<br>
				<?php 
					if ($niveau<2) echo '<input type="submit" value="VALIDER">';
					else echo "</br><div class='alerte'>Désolé, vous n'avez pas les droits suffisants pour cette opération</div>";
				?>
				
			</form>
		</fieldset>
	</div> 
</body>
</html>