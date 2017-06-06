<?php
    session_start();
    $tadh = $_SESSION['tadh'];
    $tact = $_SESSION['tact'];
    $tani = $_SESSION['tani'];
    $prenom=$_SESSION['prenom'];
    $niveau=$_SESSION['niveau'];
    if (!$prenom) die();
	include("liOptions.php");
    include("adherents.inc");
    include("gract.inc");
    include("animateurs.inc");
    $N = new MConf;
    $sql = "SELECT * FROM $tadh ORDER BY nom";
    $ad = new Adherents;
    $ad->cherche($sql);
    setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
    $nomfichieradh="export/tadhexport_".strftime("%y%m%d").".csv";
    if (PHP_OS == "Darwin") $fp=fopen($nomfichieradh,"w");
    else $fp=fopen("/var/www/html/adh/".$nomfichieradh,"w");
    if (!$fp) echo "problème à l'ouverture du fichier $nomfichieradh<br>";
    $ligne='"ID";"TITRE";"NOM";"PRENOM";"NOMJF";"ADRESSE";"COMPLEMENT";"CODEPOSTAL";"VILLE";"TELEPHONE";"PORTABLE";"COURRIEL"';
    $ligne.=';"NUMMGEN";"QUALITE";"NUMEROSS";"PROFESSION";"ASSURANCE";"COTISATION";"PREMANNEE";"SORTIE";"ACTIVITES"';
    $ligne.="\n";
    fwrite($fp,$ligne);
    for ($i=0;$i<$ad->n;$i++) {
        $ligne='"'.$ad->adh[$i]->id.'"'.";";
        $ligne.='"'.$ad->adh[$i]->titre.'"'.";";
        $ligne.='"'.$ad->adh[$i]->nom.'"'.";";
        $ligne.='"'.$ad->adh[$i]->prenom.'"'.";";
        $ligne.='"'.$ad->adh[$i]->nomjf.'"'.";";
        $ligne.='"'.$ad->adh[$i]->adresse.'"'.";";
        $ligne.='"'.$ad->adh[$i]->compadresse.'"'.";";
        $ligne.='"'.$ad->adh[$i]->codepostal.'"'.";";
        $ligne.='"'.$ad->adh[$i]->ville.'"'.";";
        $ligne.='"'.$ad->adh[$i]->telephone.'"'.";";
        $ligne.='"'.$ad->adh[$i]->portable.'"'.";";
        $ligne.='"'.$ad->adh[$i]->courriel.'"'.";";
        $ligne.='"'.$ad->adh[$i]->numMGEN.'"'.";";
        $ligne.='"'.$ad->adh[$i]->qualite.'"'.";";
        $ligne.='"'.$ad->adh[$i]->numeroSS.'"'.";";
        $ligne.='"'.$ad->adh[$i]->profession.'"'.";";
        $ligne.='"'.$ad->adh[$i]->assurance.'"'.";";
        $ligne.='"'.$ad->adh[$i]->cotisation.'"'.";";
        $ligne.='"'.$ad->adh[$i]->premannee.'"'.";";
        $ligne.='"'.$ad->adh[$i]->sortie.'"'.";";
        $ligne.='"'.$ad->adh[$i]->activites.'"'.";";
        $ligne.="\n";
        fwrite($fp,$ligne);
    }
    fclose ($fp);
    $sql = "SELECT * FROM $tact ORDER BY activite";
    $ga = new Gracts;
    $ga->cherche($sql);
    $nomfichiergra="export/tgractexport_".strftime("%y%m%d").".csv";
    if (PHP_OS == "Darwin") $fp=fopen($nomfichiergra,"w");
    else $fp=fopen("/var/www/html/adh/".$nomfichiergra,"w");
    if (!$fp) echo "problème à l'ouverture du fichier $nomfichieradh<br>";
    $ligne='"ID";"ACTIVITE";"CODACTIVITE";"GROUPE";"TARIFA";"TARIFC";"LIEU";"JOUR";"DEBUT";"FIN";"IDANIMATEUR"';
    $ligne.="\n";
    fwrite($fp,$ligne);
    for ($i=0;$i<$ga->n;$i++) {
        $ligne='"'.$ga->gract[$i]->id.'"'.";";
        $ligne.='"'.$ga->gract[$i]->activite.'"'.";";
        $ligne.='"'.$ga->gract[$i]->codactivite.'"'.";";
        $ligne.='"'.$ga->gract[$i]->groupe.'"'.";";
        $ligne.='"'.$ga->gract[$i]->tarifA.'"'.";";
        $ligne.='"'.$ga->gract[$i]->tarifC.'"'.";";
        $ligne.='"'.$ga->gract[$i]->lieu.'"'.";";
        $ligne.='"'.$ga->gract[$i]->jour.'"'.";";
        $ligne.='"'.$ga->gract[$i]->debut.'"'.";";
        $ligne.='"'.$ga->gract[$i]->fin.'"'.";";
        $ligne.='"'.$ga->gract[$i]->idanimateur.'"'.";";
        $ligne.="\n";
        fwrite($fp,$ligne);
    }
    fclose ($fp);
    $sql = "SELECT * FROM $tani ORDER BY nom";
    $an = new Animateurs;
    $an->cherche($sql);
    $nomfichierani="export/tanimexport_".strftime("%y%m%d").".csv";
    if (PHP_OS == "Darwin") $fp=fopen($nomfichierani,"w");
    else $fp=fopen("/var/www/html/adh/".$nomfichierani,"w");
    $ligne='"ID";"NOM";"PRENOM";"TELEPHONE";"PORTABLE";"COURRIEL";"BENEVOLE";"ANIMATEUR"';
    $ligne.="\n";
    fwrite($fp,$ligne);
    for ($i=0;$i<$ga->n;$i++) {
        $ligne='"'.$an->ani[$i]->id.'"'.";";
        $ligne.='"'.$an->ani[$i]->nom.'"'.";";
        $ligne.='"'.$an->ani[$i]->prenom.'"'.";";
        $ligne.='"'.$an->ani[$i]->telephone.'"'.";";
        $ligne.='"'.$an->ani[$i]->portable.'"'.";";
        $ligne.='"'.$an->ani[$i]->courriel.'"'.";";
        $ligne.='"'.$an->ani[$i]->benevole.'"'.";";
        $ligne.='"'.$an->ani[$i]->animateur.'"'.";";
        $ligne.="\n";
        fwrite($fp,$ligne);
    }
    fclose ($fp);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="bibglobal0.css" type="text/css" rel="stylesheet" media="screen"/>
    <link href="msgBoxLight.css" rel="stylesheet">
    <script src="jquery-2.1.4.min.js"></script>
    <script src="jquery.msgBox.js"></script>
    <script src="fonctions.js"></script>
    <script type="text/javascript"></script>
</head>
<body onload="resizemenu0()" onresize="resizemenu()">
    <?php 
        include("menus.php");
    ?>
    <br> <br>
    <form name="formulaire" action="downloadfile.php" method="post">
        <input type="hidden" name="filename" value="<?php echo $nomfichieradh ?>">
        <input type="submit" value="Télécharger le fichier des adhérents">
    </form>
    <br> <br> 
    <form name="formulaire" action="downloadfile.php" method="post">
        <input type="hidden" name="filename" value="<?php echo $nomfichiergra ?>">
        <input type="submit" value="Télécharger le fichier des activités">
    </form> 
    <br> <br> 
    <form name="formulaire" action="downloadfile.php" method="post">
        <input type="hidden" name="filename" value="<?php echo $nomfichierani ?>">
        <input type="submit" value="Télécharger le fichier des animateurs">
    </form> 
</body>
</html>

