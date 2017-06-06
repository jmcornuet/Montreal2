
<?php 
	session_start();
	include("MGENconfig.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bibliothèque</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="bibglobal0.css" type="text/css" rel="stylesheet" media="screen"/>
</head>
<body>
<?php

$login=$_POST['ident'];
$pass=$_POST['pass'];
$lien="index.php";
$bib="bib0.php";

if ($login!="" AND $pass!="") {
	$M = new MConf;
	$sql="SELECT * FROM $M->tablaut WHERE login='$login' AND niveau<2";
	$reponse=$M->querydb($sql);
	$donnees = $reponse->fetch();
	//$passcode = $donnees['password'];
	//exec('./decrypta "'.$passcode.'"',$passdecode);
	//if ($pass==$passdecode[0]) {
	if ($donnees['login']) {
		$passcrypt=$donnees['password'];
		exec('./decryptb "'.$passcrypt.'"',$c,$ret);
		if ($c[0]==$pass) {
			$ff = fopen("../conf/tables.conf","r");
			while (!feof($ff)) {
				$a= trim(utf8_encode(fgets($ff)));
				$b=explode("=",$a);
				switch ($b[0]) {
					case "adh" : $_SESSION['tadh'] = $b[1];break;
					case "ani" : $_SESSION['tani'] = $b[1];break;
					case "act" : $_SESSION['tact'] = $b[1];break;
				}
			} 
			fclose($ff);		
			$_SESSION['prenom']=$donnees['prenom'];
			$_SESSION['nom']=$donnees['nom'];
			$_SESSION['niveau']=$donnees['niveau'];
			setlocale(LC_TIME,'fr_FR.utf8','fra','fr_FR.ISO8859-1');
			$_SESSION['debut']=utf8_encode(strftime("%d/%m/%g_%H:%M:%S"));
			$M->close();
		}
?>	
	<form name='demarre' method='post' action='club0.php'>
		<input type='hidden' name='prenom' value=<?php echo $donnees['prenom'] ?> >
	</form>
	<script type='text/javascript'>
		document.demarre.submit();
	</script>	
<?php		

	}
	else {
		 die("Désolé, vos droits d'accès sont insuffisants");
	}
}
else die("Désolé, vous n'avez pas été reconnu(e). <a href=$lien>Essayer encore</a>");
?>
</body>
</html>

