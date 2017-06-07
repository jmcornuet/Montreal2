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
    <script type="text/javascript">
    	function updatetotal(origine){
    		var stotal=document.getElementById('total').innerHTML;
    		var total = Number(stotal);
    		var snbch =document.getElementById('nbcheques').innerHTML;
    		var nbch = Number(snbch);
    		var smontant=document.getElementById('montant'+String(origine)).innerHTML;
    		var montant = Number(smontant);
    		if (document.forms['bordereau']['OK'+String(origine)].checked) {
    			total +=montant;
    			nbch++;
    		} else {
    			total -=montant;
    			nbch--;
    		}
    		document.getElementById('total').innerHTML = String(total);
    		document.getElementById('nbcheques').innerHTML = String(nbch);
    	}
    </script>
</head>
<body onload="resizemenu0()" onresize="resizemenu()">
	<?php 	
		include("menu.php");
		include("cheques.inc");
		include("adherents.inc");
		setlocale(LC_TIME, 'fr', 'fr_FR','fr_FR.UTF-8');
		$today = strftime("%d/%m/%Y");
		$sql = "SELECT * FROM $tcheq ORDER BY bordereau";
		$M = new MConf;
		$reponse = $M->querydb($sql);
		$ligne=array();$numbordereau=0;
		while ($donnees=$reponse->fetch()) {
			if ($donnees['bordereau']==0) {
				$li = new Cheque;
				$li->id = $donnees['id'];
				$li->datecheque = $donnees['datecheque'];
				$li->numcheque = $donnees['numcheque'];
				$li->banque = $donnees['banque'];
				$li->montant = $donnees['montant'];
				$li->idbeneficiaire = $donnees['idbeneficiaire'];
				$ad = new Adherent;
				$ad->id = $li->idbeneficiaire;
				$ad->getadh($tadh);
				$li->nom = $ad->nom;
				$li->prenom = $ad->prenom;
				$li->matricule = $ad->numMGEN." ".$ad->qualite;
				$ad = null;
				array_push($ligne,$li);
			} else if ($donnees['bordereau']>$numbordereau) $numbordereau=$donnees['bordereau'];
		} 
		$M->close();
		$numbordereau++;
		echo "<div class='titre1'>Préparation d'un BORDEREAU de CHEQUES à l'ENCAISSEMENT</div><br>";

		echo '<form name="bordereau" action="imprimbordereau.php" method="post">';
		echo "<table  class='tablepart' width=80%><tr><th>DATE</th><th>NUMERO</th><th>&nbsp;&nbsp;BANQUE&nbsp;&nbsp;</th><th>ADHERENT</th><th>MATRICULE</th><th>MONTANT</th><th>OK</th></tr>";
		for ($i=0;$i<count($ligne);$i++) {
			$mes ="<tr>";
			$mes .="<td>".$ligne[$i]->datecheque."</td><td>".$ligne[$i]->numcheque."</td>";
			$mes .= "<td>".$ligne[$i]->banque."</td><td>".$ligne[$i]->nom." ".$ligne[$i]->prenom."</td>";
			$mes .= "<td>".$ligne[$i]->matricule."</td><td style='text-align:right'><span id='montant".$i."'>".$ligne[$i]->montant."</span>.00 €</td>";
			$mes .= "<td><input id='OK".$i."' type='checkbox' value='' onclick='updatetotal(".$i.")'>"."</td>";
			$mes .="</tr>";
			echo $mes;
		}
		echo "</table>";
	?>
	<br>
	<table class='tablepart' width=80%>
		<tr>
			<td style='padding:5px'>Bordereau n° <?php echo $numbordereau ?></td> 
			<td style='padding:5px'>Date de remise = <input name='dateremise' type='text' width=10 value="<?php echo $today ?>"></td>
			<td style='padding:5px'>Nombre de chèques = <span id='nbcheques'>0</span></td>
			<td style='padding:5px'>Total = <span id='total'>0</span>.00 €</td>

		</tr>
	</table>
	<br>
	<input type="submit" class="bouton" value="IMPRIMER">
</form>
	<div id="log"></div>
 </body>
 </html>