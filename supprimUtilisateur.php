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
	?>
	<div class="titre1" style="text-align:center">Suppression d'un utilisateur du programme de gestion</span></div>

	<?php
		$M = new MConf;
		$sql = "SELECT * FROM $M->tablaut WHERE niveau>$niveau ORDER BY nom";
		$reponse = $M->querydb($sql);
		$util=array();$niveau=array();$id=array();
		while ($donnees = $reponse->fetch()) {
			array_push($id,$donnees["id"]);
			array_push($util,$donnees['prenom']." ".$donnees['nom']);
			array_push($niveau,$donnees['niveau']);
		}
		$optionsutil="";
		for($i=0;$i<count($util);$i++) $optionsutil = $optionsutil."<option value='$id[$i]'>$util[$i]</option>";		
	?>
    <div class="champ">
        <fieldset class="champemprunteurs">
            <form name="nouvelAd" method="post" action="suputil.php">
                Choisissez la personne dont vous voulez supprimer l'accès au programme : <br><br>
                <select name="id"><?php echo $optionsutil ?></select></td>
                 <input type="submit" value="VALIDER">
            </form>
        </fieldset>
    </div>


</body>
</html>
