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
    <link href="msgBoxLight.css" rel="stylesheet">
    <script src="jquery-2.1.4.min.js"></script>
    <script src="jquery.msgBox.js"></script>
    <script type="text/javascript">
    	var cheque=[];
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
    			cheque.push(document.forms['bordereau']['id'+String(origine)].value);
    		} else {
    			total -=montant;
    			nbch--;
    			var cheque0=[];
    			var bidon=document.forms['bordereau']['id'+String(origine)].value;
    			for (i=0;i<cheque.length;i++) {
    				if (cheque[i]!=bidon) cheque0.push(cheque[i]);
    			}
    			cheque=cheque0;
    		}
    		document.getElementById('total').innerHTML = String(total);
    		document.getElementById('nbcheques').innerHTML = String(nbch);
    		//document.getElementById('log').innerHTML = cheque.toString();
    	}
    	function imprim() {
    		var stotal=document.getElementById('total').innerHTML;
    		var total = Number(stotal);
			if (total==0) {
				jQuery.msgBox({ type:"info",title:"Préparation de bordereau",
					content: "Sélectionnez au moins un chèque"
				});
			} else {
	    		jQuery.msgBox({ type: "confirm",
					title: "Impression d'un bordereau",
					content: "Impression prête",
					buttons: [{ value: "Annuler" },{ value: "Imprimer"}],
		            success: function(result)  {
			        	if (result!="Annuler") {
	 						var formulaire = document.createElement('form');
				    		formulaire.setAttribute('target','_blank');
				    		formulaire.setAttribute('action','imprimbordereau.php');
				    		formulaire.setAttribute('method', 'post');
				    		var numbordereau = document.getElementById('numbordereau').innerHTML;
				    		var input0 = document.createElement('input');
				    		input0.setAttribute('type','hidden');input0.setAttribute('name','numbordereau');input0.setAttribute('value',numbordereau);
				    		formulaire.appendChild(input0);
				    		var dateremise = document.forms['bordereau']['dateremise'].value;
				    		var input1 = document.createElement('input');
				    		input1.setAttribute('type','hidden');input1.setAttribute('name','dateremise');input1.setAttribute('value',dateremise);
				    		formulaire.appendChild(input1);
				    		var nbcheques = document.getElementById('nbcheques').innerHTML;
				    		var input2 = document.createElement('input');
				    		input2.setAttribute('type','hidden');input2.setAttribute('name','nbcheques');input2.setAttribute('value',nbcheques);
				    		formulaire.appendChild(input2);
				    		var total = document.getElementById('total').innerHTML;
				    		var input3 = document.createElement('input');
				    		input3.setAttribute('type','hidden');input3.setAttribute('name','total');input3.setAttribute('value',total);
				    		formulaire.appendChild(input3);
				    		var input4 = document.createElement('input');  
				    		input4.setAttribute('type','hidden');input4.setAttribute('name','tabid');input4.setAttribute('value',cheque.toString());
				    		formulaire.appendChild(input4);
					   		document.body.appendChild(formulaire);
				    		formulaire.submit();
				    		setTimeout(function(){document.location.reload();},3000);
			        	}
			        }
			    });
			}    		
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
		$sql = "SELECT * FROM $tcheq";
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
		echo "<table  id='tableau' class='tablepart' width=80%><tr><th>DATE</th><th>NUMERO</th><th>&nbsp;&nbsp;BANQUE&nbsp;&nbsp;</th><th>ADHERENT</th><th>MATRICULE</th><th>MONTANT</th><th>OK</th></tr>";
		for ($i=0;$i<count($ligne);$i++) {
			$mes ="<tr>";
			$mes .="<input type='hidden' name='id".$i."' value=".$ligne[$i]->id.">";
			$mes .="<td>".$ligne[$i]->datecheque."</td><td>".$ligne[$i]->numcheque."</td>";
			$mes .= "<td>".$ligne[$i]->banque."</td><td>".$ligne[$i]->nom." ".$ligne[$i]->prenom."</td>";
			$mes .= "<td>".$ligne[$i]->matricule."</td><td style='text-align:right'><span id='montant".$i."'>".$ligne[$i]->montant."</span>.00 €</td>";
			$mes .= "<td><input name='OK".$i."' type='checkbox' value='' onclick='updatetotal(".$i.")'>"."</td>";
			$mes .="</tr>";
			echo $mes;
		}
		echo "</table>";
	?>
	<br>
	<table class='tablepart' width=80%>
		<tr>
			<td style='padding:5px'>Bordereau n° <span id='numbordereau'><?php echo $numbordereau ?></span></td> 
			<td style='padding:5px'>Date de remise = <input name='dateremise' type='text' width=10 value="<?php echo $today ?>"></td>
			<td style='padding:5px'>Nombre de chèques = <span id='nbcheques'>0</span></td>
			<td style='padding:5px'>Total = <span id='total'>0</span>.00 €</td>

		</tr>
	</table>
	<br>
</form>
	<button id="bouton0" class="bouton"  onclick="imprim()">IMPRIMER</button>
	<div id="log"></div>
 </body>
 </html>