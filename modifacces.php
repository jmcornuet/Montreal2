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
        function putSelected2($opt,$sel) {
        	if ($sel=='0') return $opt;
            $f=strpos($opt,$sel)+strlen($sel);
            $s1=substr($opt,0,$f);
            $s2=substr($opt,$f,strlen($opt));
            return $s1." selected".$s2;
        }
        function putSelected3($opt,$sel) {
        	for ($i=0;$i<count($sel);$i++) {
	            $f=strpos($opt,"value='".$sel[$i])+strlen("value='".$sel[$i])+1;
	            $s1=substr($opt,0,$f);
	            $s2=substr($opt,$f,strlen($opt));
	            $opt=$s1." selected".$s2;
	        }
            return $opt;
        }
		$id = $_POST['id'];
		$M = new MConf;
		$sql = "SELECT * FROM $M->tablaut WHERE id=$id";
		$reponse = $M->querydb($sql);
		$donnees = $reponse->fetch();
		$nom = $donnees['nom'];
		$prenom = $donnees['prenom'];
		$niveau = $donnees['niveau'];
		$presence = $donnees['presence'];
		$pres = array();
		$dj=["lum","lua","mam","maa","mem","mea","jem","jea","vem","vea"];
		for ($i=0;$i<count($dj);$i++) {
			if (strstr($presence,$dj[$i])) array_push($pres,$dj[$i]);
		}
//		print_r($pres);echo "<br>";
		$optionsdroit = "";
		for ($i=0;$i<6;$i++) $optionsdroit = $optionsdroit."<option value=$i>$i</option>";
		$optionsdroit = putSelected2($optionsdroit,$niveau);
		$p=["lundi matin","lundi après-midi","mardi matin","mardi après-midi","mercredi matin","mercredi après-midi","jeudi matin","jeudi après-midi","vendredi matin","vendredi après-midi"];
		$optionspresence = "<option value='0'>Pas de service d'accueil</option>";
		for ($i=0;$i<10;$i++) {$val=$dj[$i];$optionspresence = $optionspresence."<option value='$val'>$p[$i]</option>";}
		$optionspresence = putSelected3($optionspresence,$pres);
	?>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form name="nouvelAd" action="modifniveau.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id ?>">
				<table  class="saisie">
					<tr><th>Utilisateur</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Niveau de droits</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Présence à l'accueil</th></tr>
					<tr>
						<td><?php echo $prenom." ".$nom ?>   </td> 
						<td></td>
						<td style="text-align:center"><select name="niveau"> <?php echo $optionsdroit ?></select></td>
						<td></td>
						<td><select multiple name="presence[]"><?php echo $optionspresence ?></select></td>
					</tr>
				</table>
				<input type="submit" value="VALIDER">
			</form>
		</fieldset>
	</div>

</body>
</html>
