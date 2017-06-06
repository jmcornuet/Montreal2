
<?php
header('Content-Type: text/html; charset=utf-8');
ob_implicit_flush(true);
     include("MGENconfig.php");
     function dbf2mysql($file) {
          echo "début de dbf2mysql<br>";
          $table = 'tadhimport';
          $iconvFrom  = '866';
          $iconvTo    = 'UTF-8';
          $delimetr   = ',';
          $rep=$file; echo $rep."<br>";
          $db = dbase_open($file, 0);
          if (!$db) echo "Impossible d'ouvrir la table $file <br>";
          if ($db) {
               $info = dbase_get_header_info($db); print_r($info); 
               $fields = dbase_numfields($db);
               $records = dbase_numrecords($db);
               $sql = array();
               $sql[] = 'CREATE TABLE `' . $table . '` (';
               $columns = array();
               foreach ($info as $i) {
                    if ($i['type'] == 'character') {
                      $type = 'VARCHAR('. $i['length'] . ')';
                    } elseif ($i['type'] == 'number') {
                      $type = 'INT(10)';
                    } elseif ($i['type'] == 'date') {
                      $type = 'DATETIME';
                    } elseif ($i['type'] == 'memo') {
                      $type = 'VARCHAR(500)';
                    }
                    $columns[] = '  `' . strtolower($i['name']) . '` ' . $type;
               }
               $sql[] = implode(',' . PHP_EOL, $columns);
               $sql[] = ');' . PHP_EOL;
               $rep = implode(PHP_EOL, $sql);
          }
          return $rep;  
     }
     include("menu.php");
     $nomtable = $_POST['nomtable'];
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
         <div class="titre1">Importation d'une table dans la base de données</div>
     <div class="champ">
          <fieldset class="champemprunteurs">

     <?php     
     if (PHP_OS == "Darwin") $dossier = 'upload/';
     else $dossier = '/var/www/html/Montreal/upload/';
     $fichier = basename($_FILES['filetable']['name']);
     $taille_maxi = 500000;
     $taille = filesize($_FILES['filetable']['tmp_name']);
     $extensions = array('.dbf', '.csv','.sql');
     $extension = strrchr($_FILES['filetable']['name'], '.'); 
     //Début des vérifications de sécurité...
     if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
     {
          $erreur = 'Vous devez uploader un fichier de type .dbf, .csv ou .sql';
          echo "</br></br><div class='alerte'>Vous devez uploader un fichier de type .dbf, .csv ou .sql </div>";
     }
     if($taille>$taille_maxi)
     {
          $erreur = 'Le fichier est trop gros...';
          echo "</br></br><div class='alerte'>Le fichier est trop gros...</div>";
     }
     if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
     {
          //On formate le nom du fichier ici...
          $fichier = strtr($fichier, 
               'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
               'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
          $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
          if(move_uploaded_file($_FILES['filetable']['tmp_name'], $dossier . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
               if ($extension == '.sql') {
                    $fp = fopen($dossier.$fichier,"r");
                    if ($fp) echo "ouverture du fichier ".$dossier.$fichier."<br>";
                    else echo "problème à l'ouverture du fichier<br>";
                    $sql = fread($fp,$taille);//on lit tout le fichier d'un seul coup
                    fclose($fp);
                    $i=strpos($sql,"`");//echo "i=".$i."<br>";
                    $oldtable = substr($sql,$i+1);
                    $i=strpos($oldtable,"`");//echo "i=".$i."<br>";
                    $oldtable = substr($oldtable,0,strpos($oldtable,"`"));
                    //echo "ancienne table : ".$oldtable."<br>";
                    $sql = str_replace($oldtable,$nomtable,$sql);
                    $M = new MConf;
                    $reponse = $M->querydb($sql);
                    $M->close();
                    if ($reponse) echo "</br></br><div class='alerte'>La table $nomtable a bien été insérée dans la base de données </div>";
                    else echo "</br></br><div class='alerte'>La table $nomtable n'a pas pu être insérée dans la base de données !!!</div>";

               }
               if ($extension == '.csv') {
                    $lf = "\n";
                    $sql  = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
                    $sql .= $lf.'SET time_zone = "+00:00";';
                    $sql .= $lf.'CREATE TABLE `'.$nomtable.'` (';
                    $ligne = file($dossier.$fichier);//echo $ligne[0]."<br>";
                    $nomcol = explode(";",$ligne[0]);$nl = count($ligne);
                    $typcol = array();
                    echo '<script>resizemenu()</script>';
                    echo '<span style="font-variant: normal">Importation de la table <span style="font-weight: bold">'.$nomtable."</span></span><br>";
                    //echo '<progress id="progres" value="0" max="100" > </progress>';

                    for ($i=0;$i<count($nomcol);$i++) {
                         $nomcol[$i] = strtoupper(substr($nomcol[$i],1,strlen($nomcol[$i])-2));
                         if ($i == count($nomcol)-1) $nomcol[$i] = substr($nomcol[$i], 0, strlen($nomcol[$i])-1);
                         switch ($nomcol[$i]) {
                              case "ID"         : $sql .= $lf."`id` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "TITRE"      : $sql .= $lf."`titre` varchar(5) NOT NULL,";array_push($typcol,"T");break;
                              case "NOM"        : $sql .= $lf."`nom` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "NOMJF"      : $sql .= $lf."`nomjf` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "PRENOM"     : $sql .= $lf."`prenom` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "COMPADRESSE": $sql .= $lf."`compadresse` text NOT NULL,";array_push($typcol,"T");break;
                              case "PRECISIONS" : $sql .= $lf."`compadresse` text NOT NULL,";array_push($typcol,"T");break;
                              case "ADRESSE"    : $sql .= $lf."`adresse` text NOT NULL,";array_push($typcol,"T");break;
                              case "CODEPOSTAL" : $sql .= $lf."`codepostal` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "CODPOST"    : $sql .= $lf."`codepostal` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "VILLE"      : $sql .= $lf."`ville` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "TELEPHONE"  : $sql .= $lf."`telephone` varchar(20) NOT NULL,";array_push($typcol,"T");break;
                              case "PORTABLE"   : $sql .= $lf."`portable` varchar(20) NOT NULL,";array_push($typcol,"T");break;
                              case "COURRIEL"   : $sql .= $lf."`courriel` varchar(50) NOT NULL,";array_push($typcol,"T");break;
                              case "NUMEROSS"   : $sql .= $lf."`numeroSS` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "SECUSOC"    : $sql .= $lf."`numeroSS` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "ASSURANCE"  : $sql .= $lf."`assurance` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "NUMMGEN"    : $sql .= $lf."`numMGEN` varchar(6) NOT NULL,";array_push($typcol,"T");break;
                              case "NOCART"     : $sql .= $lf."`numMGEN` varchar(6) NOT NULL,";array_push($typcol,"T");break;
                              case "QUALITE"    : $sql .= $lf."`qualite` varchar(1) NOT NULL,";array_push($typcol,"T");break;
                              case "COTISATION" : $sql .= $lf."`cotisation` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "COTIS"      : $sql .= $lf."`cotisation` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "PREMANNEE"  : $sql .= $lf."`premannee` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "PREMINSCRI" : $sql .= $lf."`premannee` int(11) NOT NULL,";array_push($typcol,"N");break;
                              case "PROFESSION" : $sql .= $lf."`profession` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "ACTIVITES"  : $sql .= $lf."`activites` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                              case "SORTIE"     : $sql .= $lf."`sortie` varchar(30) NOT NULL,";array_push($typcol,"T");break;
                         }
                    }
                    //for ($j=0;$j<count($typcol);$j++) echo $typcol[$j]."  ";echo $lf;
                    $sql  = substr($sql, 0, strlen($sql)-1); // pour retirer la dernière virgule
                    $sql = $sql.$lf.") ENGINE=InnoDB DEFAULT CHARSET=utf8;".$lf;
                    $sql .=$lf."INSERT INTO `".$nomtable."` (";
                    for ($i=0;$i<count($nomcol);$i++) {$a = strtolower($nomcol[$i]);$sql .= "`$a`,";}
                    $sql  = substr($sql, 0, strlen($sql)-1); // pour retirer la dernière virgule
                    $sql .= ")".$lf." VALUES ".$lf; 
                    for ($i=1;$i<count($ligne);$i++) {
                         $p = strpos($ligne[$i],';";');
                         if ($p) {
                              $s1=substr($ligne[$i],0,$p+2);
                              $s2=substr($ligne[$i],$p+2,strlen($ligne[$i]));
                              $s3=substr($s2,0,strpos($s2,'"'));
                              $s3=str_replace(";","=",$s3);
                              $s4=substr($s2,strpos($s2,'"'),strlen($s2));
                              //if ($i<10) echo $s1.$s3.$s4."<br>";
                              $ligne[$i]=$s1.$s3.$s4;
                         }
                         $valcol = explode(";",$ligne[$i]);
                         $sql1 = "(";
                         for ($j=0;$j<count($valcol);$j++) {
                              $valcol[$j] = substr($valcol[$j],1,strlen($valcol[$j])-2);
                              if ($j == 0) $lastid = $valcol[$j]+1;
                              if ($j == count($valcol)-1) $valcol[$j] = substr($valcol[$j], 0, strlen($valcol[$j])-1);
                              if ($typcol[$j] == "N") $sql1 .= $valcol[$j].",";
                              else $sql1 .= "'".addslashes($valcol[$j])."',";
                         }
                         $sql1  = substr($sql1, 0, strlen($sql1)-1);
                         if ($i == count($ligne)-1)    $sql .= $sql1.");".$lf.$lf ;
                         else $sql .= $sql1."),".$lf;
                         //if (($i%10 == 0)or($i == $nl-1)) echo '<script>document.getElementById("progres").value="'.$i/2.'";</script>';
                    }
                    //echo '<script>document.getElementById("progres").value="50";</script>';
                    $sql .= "ALTER TABLE `".$nomtable."`".$lf;
                    $sql .= "  ADD PRIMARY KEY (`id`);".$lf;
                    $sql .= "ALTER TABLE `".$nomtable."`".$lf;
                    $sql .= "  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=".$lastid.";".$lf;                    
                    $M = new MConf;
                    $reponse = $M->querydb($sql);
                    $M->close();
                    //echo '<script>document.getElementById("progres").value="100";</script>';
                    if ($reponse) echo "</br></br><div class='alerte'>La table $nomtable a bien été insérée dans la base de données </div>";
                    else echo "</br></br><div class='alerte'>La table $nomtable n'a pas pu être insérée dans la base de données !!!</div>";
                    /*
                    $fp = fopen("bidon2.sql","w");
                    $a = fwrite($fp,$sql);
                    fclose($fp);
                    */
               }
          }
          else //Sinon (la fonction renvoie FALSE).
          {
               echo 'Echec de l\'upload !';
          }
     }
     else
     {
          echo $erreur;
     }
?>

          </fieldset>
     </div>
 </body>
 </html>
