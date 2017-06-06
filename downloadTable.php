<?php
	session_start();
    require_once("session.php");
	if (!$prenom) die();
	include("MGENconfig.php");
    setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
    $jour=strftime("%y%m%d");
	//$nomfichieradh="export/tadhexport_".strftime("%y%m%d").".csv";
	$nomtable=$_POST['table'];
	if ($_POST['type'] == "CSV") {
		$nomfi = $nomtable."_".$jour.".csv";
		//echo $nomfi."<br>";
	    if (PHP_OS == "Darwin") $fp=fopen("../Montreal/export/".$nomfi,"w");
	    else $fp=fopen("/var/www/html/Montreal/export/".$nomfi,"w");
	    if ($fp) {
			$sql ="SELECT column_name FROM information_schema.columns WHERE table_name = '$nomtable' AND table_schema= 'clubmgen'";
			//echo $sql."<br>";
			$nomcol = array();
			$M = new MConf;
			$reponse = $M->querydb($sql);
			while ($donnees = $reponse->fetch()) {
				array_push($nomcol,$donnees['column_name']);
			}
			$ligne='';
			for ($i=0;$i<count($nomcol);$i++) if ($i<count($nomcol)-1) $ligne .='"'.$nomcol[$i].'";';else $ligne .='"'.$nomcol[$i].'"';
			fwrite($fp,$ligne."\n");
			$sql = "SELECT * FROM $nomtable";
			$reponse = $M->querydb($sql);
			while ($donnees = $reponse->fetch()) {
				$ligne = "";
				for ($i=0;$i<count($nomcol);$i++) if ($i<count($nomcol)-1) $ligne .='"'.$donnees[$i].'";';else $ligne .='"'.$donnees[$i].'"';
				fwrite($fp,$ligne."\n");
			}
		}
		fclose($fp);
	}
	if ($_POST['type'] == "SQL") {
		$nomfi = $nomtable."_".$jour.".sql";
		//echo $nomfi."<br>";
	    if (PHP_OS == "Darwin") $fp=fopen("../Montreal/export/".$nomfi,"w");
	    else $fp=fopen("/var/www/html/Montreal/export/".$nomfi,"w");
	    if ($fp) {
	    	fwrite($fp,'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'."\n");
	    	fwrite($fp,'SET time_zone = "+00:00";'."\n\n");
	    	fwrite($fp,'CREATE TABLE `'.$nomtable.'` ('."\n");

			$sql ="SELECT column_name,data_type,character_maximum_length FROM information_schema.columns WHERE table_name = '$nomtable' AND table_schema= 'clubmgen'";
			//echo $sql."<br>";
			$nomcol = array();$typcol = array();
			$M = new MConf;
			$reponse = $M->querydb($sql);
			$nenr = $reponse->rowCount();
			$row=0;
			while ($donnees = $reponse->fetch()) {
				array_push($nomcol,$donnees['column_name']);
				array_push($typcol,$donnees['data_type']);
				$dtype = $donnees['data_type']; 
				if ($dtype== "varchar") $lon = "(".$donnees['character_maximum_length'].")";
				else if ($dtype == "text") $lon='';
				else if ($dtype == "int") $lon='(11)';
				$row++;
				if ($row<$nenr) fwrite($fp,'`'.$donnees['column_name']."` ".$donnees['data_type'].$lon." NOT NULL,"."\n");
				else fwrite($fp,'`'.$donnees['column_name']."` ".$donnees['data_type'].$lon." NOT NULL"."\n");
			}
			fwrite($fp,") ENGINE=InnoDB DEFAULT CHARSET=utf8;"."\n\n");
			$mes = 'INSERT INTO `'.$nomtable.'` (';
			for ($i=0;$i<count($nomcol);$i++) if ($i<count($nomcol)-1) $mes .="`".addslashes($nomcol[$i])."`, "; else $mes .="`".addslashes($nomcol[$i])."`) VALUES";
			fwrite($fp,$mes."\n");

			$sql = "SELECT * FROM $nomtable";
			$reponse = $M->querydb($sql);
			$nenr = $reponse->rowCount();
			$row=0;
			while ($donnees = $reponse->fetch()) {
				$ligne ="(";
				for ($i=0;$i<count($nomcol);$i++) {
					if ($typcol[$i] == "int") $ligne .= $donnees[$i];
					else $ligne .=" '".addslashes($donnees[$i])."'";
					if ($i<count($nomcol)-1) $ligne .=", ";
					else $ligne .=")";	
				}					
				$row++;
				if ($row<$nenr) $ligne .=",";
				else {$ligne .=";\n";$lastid=$donnees[0]+1;}
				fwrite($fp,$ligne."\n");
			}
			fwrite($fp,"ALTER TABLE `".$nomtable."`"."\n");
			fwrite($fp,"  ADD PRIMARY KEY (`id`);"."\n\n");
			fwrite($fp,"ALTER TABLE `".$nomtable."`"."\n");
			fwrite($fp,"  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=".$lastid.";\n");
		fclose($fp);
		}
	}		
  	$mes  = '<form name="formtable" method="post" action="downloadfile.php">';
  	$mes = $mes.'<input type="hidden" name="filename" value="'.$nomfi.'"" >';
  	$mes = $mes.'</form>';
  	$mes = $mes.'<script type="text/javascript">document.formtable.submit();</script>';
  	echo $mes;
?>
</body>
</html>