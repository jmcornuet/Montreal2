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
			$table = $donnees['Tables_in_clubmgen'];
			if (($table != "authentification")&&(!strstr($table,"log"))) array_push($nomtable,$table);
		}
		$M->close();
		$optionstable="";
		for ($i=0;$i<count($nomtable);$i++) $optionstable = $optionstable."<option value='$nomtable[$i]'>$nomtable[$i]</option>";
		$optionstype="<option value=''></option><option value='CSV'>CSV</option><option value='SQL'>SQL</option>";
	?>
	<div class="titre1" style="text-align:center">Exportation d'une table de la base de données</span></div>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form action="downloadTable.php" method="post">
				<table class="saisie">
					<tr>
						<td>Choisissez la table que vous voulez exporter : </td>
						<td>&nbsp;&nbsp;</td>
						<td><select name="table" <?php echo $optionstable ?></select></td>
					</tr>
					<br>
					<tr>
						<td>Format d'exportation : </td>
						<td>&nbsp;&nbsp;</td>
						<td><select name="type" <?php echo $optionstype ?></select></td>
					</tr>
				</table>
				<br>
				<input type="submit" value="VALIDER">
			</form>
		</fieldset>
	</div>

</body>
</html>