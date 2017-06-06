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
		function putSelected($opt,$sel) {
			$f=strpos($opt,$sel)+strlen($sel)+1;
			$s1=substr($opt,0,$f);
			$s2=substr($opt,$f,strlen($opt));
			return $s1." selected".$s2;
		}
		$M = new MConf;
		$reponse = $M->querydb("SHOW TABLES");
		$nomtable = array();
		while ($donnees = $reponse->fetch()) {
			$table = $donnees['Tables_in_clubmgen'];//echo $table."<br>";
			if (($table != "authentification")&&(!strstr($table,"log"))) array_push($nomtable,$table);
		}
		$M->close();
		//for ($i=0;$i<count($nomtable);$i++) echo $nomtable[$i]."<br>";
		$optionstable="";
		for ($i=0;$i<count($nomtable);$i++) $optionstable = $optionstable."<option value='$nomtable[$i]'>$nomtable[$i]</option>";
		$optionstableAd = putSelected($optionstable,$tadh);
		$optionstableAn = putSelected($optionstable,$tani);
		$optionstableAc = putSelected($optionstable,$tact);

	?>
	<div class="titre1" style="text-align:center">Choix des tables de la base de données</span></div>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form action="modifTables.php" method="post">
				<table class="saisie">
					<tr><th>Type de table</th><th>&nbsp;&nbsp;</th><th>Nom de la table</th></tr>
					<tr>
						<td>Table des Adhérents</td>
						<td>&nbsp;&nbsp;</td>
						<td><select name="tadherent" ><?php echo $optionstableAd ?></select></td>
					<tr>
					<tr>
						<td>Table des Activités</td>
						<td>&nbsp;&nbsp;</td>
						<td><select name="tactivite" ><?php echo $optionstableAc ?></select></td>
					<tr>
					<tr>
						<td>Table des Animateurs</td>
						<td>&nbsp;&nbsp;</td>
						<td><select name="tanimateur" ><?php echo $optionstableAn ?></select></td>
					<tr>
				</table>
				<br>
				<input type="submit" value="VALIDER">
			</form>
		</fieldset>
	</div>

</body>
</html>