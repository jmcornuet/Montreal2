<?php
	session_start();
    require_once("session.php");
	if (!$prenom) die();
	include("MGENconfig.php");
    function decrypte($s) {
	    exec('./decryptb "'.$s.'"',$resultat,$ret);
	    return $resultat[0];
    } 
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
		$id = $_POST['id'];
		$M = new MConf;
		$sql = "SELECT * FROM $M->tablaut WHERE id=$id";
		$reponse = $M->querydb($sql);
		$donnees = $reponse->fetch();
		$nom = $donnees['nom'];
		$prenom = $donnees['prenom'];
		$login = $donnees['login'];
		$password = decrypte($donnees['password']);
		$optionsdroit = "";
		for ($i=0;$i<6;$i++) $optionsdroit = $optionsdroit."<option value=$i>$i</option>";
		$optionsdroit = putSelected2($optionsdroit,$niveau);
	?>
	<div class="titre1">Modification de l'identifiant et/ou du mot de passe de <?php echo $prenom." ".$nom ?></div>
	<div class="champ">
		<fieldset class="champemprunteurs">
			<form name="nouvelAd" action="modlp.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id ?>">
				<table  class="saisie">
					<tr><th>Identifiant</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Mot de passe</th></tr>
					<tr>
						<td><input type="text" name="login" size=30 value="<?php echo $login ?> " > </td> 
						<td></td>
						<td><input type="text" name="password" size=30 value="<?php echo $password ?> "></td>
					</tr>
				</table>
				<br>
				<input type="submit" value="VALIDER">
			</form>
		</fieldset>
	</div>
</body>
</html>
