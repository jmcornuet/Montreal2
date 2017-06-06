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
		$tadh=$_POST['tadherent'];//echo $tadh."<br>";
		$tani=$_POST['tanimateur'];//echo $tani."<br>";
		$tact=$_POST['tactivite'];//echo $tact."<br>";
		$_SESSION['tadh'] = $tadh;
		$_SESSION['tani'] = $tani;
		$_SESSION['tact'] = $tact;
		$ff = fopen("../conf/tables.conf","w");
		if ($ff) {
			fwrite($ff,"adh=".$tadh."\n");
			fwrite($ff,"ani=".$tani."\n");
			fwrite($ff,"act=".$tact."\n");
			echo "</br></br><div class='alerte'>Les tables ont bien été modifiées</div>";
		} else echo "</br></br><div class='alerte'>Les tables n'ont pas pu être modifiées !!!</div>";
		fclose($ff);
	?>
</body>
</html>
