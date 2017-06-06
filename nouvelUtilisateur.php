<?php
	session_start();
	$prenom=$_SESSION['prenom'];
	$niveau=$_SESSION['niveau'];
	if (!$prenom) die();
	include("liOptions.php");
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
		$optionsdroit = "";
		for ($i=$niveau+1;$i<6;$i++) $optionsdroit = $optionsdroit."<option value=$i>$i</option>";
		$p=["lundi matin","lundi après-midi","mardi matin","mardi après-midi","mercredi matin","mercredi après-midi","jeudi matin","jeudi après-midi","vendredi matin","vendredi après-midi"];
		$dj=["lum","lua","mam","maa","mem","mea","jem","jea","vem","vea"];
		$optionspresence = "<option value=0>Pas de service d'accueil</option>";
		for ($i=0;$i<10;$i++) $optionspresence = $optionspresence."<option value=$dj[$i]>$p[$i]</option>";
	?>
	<div class="titre1">Création d'un nouvel Utilisateur</div>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form method="post" action="ajoutUtilisateur.php">
			<table  class="saisie">
				<tr>
					<td style="line-height : 200%">Nom : </td>
					<td><input type="text" size=40 name="nom" </td>
				</tr>
				<tr>
					<td style="line-height : 200%">Prénom : </td>
					<td><input type="text" size=40 name="prenom" ></td>
				</tr>
				<tr>
					<td style="line-height : 200%">Niveau : </td>
					<td><select name="niveau"><?php echo $optionsdroit ?> </select></td>
				</tr>
				<tr>
					<td style="line-height : 200%">Présence à l'accueil: </td>
					<td><select name="presence[]" multiple><?php echo $optionspresence ?> </select></td>
				</tr>
				<tr>
					<td style="line-height : 200%">Identifiant : </td>
					<td><input type="text" size=40 name="login" ></td>
				</tr>
				<tr>
					<td style="line-height : 200%">Mot de passe : </td>
					<td><input type="password" size=40 name="password"></td>
				</tr>
			</table>
			<br>
			<input type="submit" value="VALIDER">
			</form>	
		</fieldset>
	</div>
	<div id="message"></div>
 </body>
 </html>
