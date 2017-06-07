<?php
	require_once("MGENconfig.php");
	if (PHP_OS == "Darwin") $basedir=""; else $basedir="/var/www/html/adh/";
    function transprenom($p) {
        $fp=explode(" ",$p);
        for ($j=0;$j<count($fp);$j++) {
            $p=strtolower($fp[$j]);
            $dp=explode("-",$p);
            for ($i=0;$i<count($dp);$i++) {$a=substr($dp[$i],0,1);$b=substr($dp[$i],1,100);$a=strtoupper($a);$dp[$i]=$a.$b;}
            $fp[$j]=implode("-",$dp);
        }
        $p=implode(" ",$fp);
        return $p;
    }
    function compacte($s0) {
    	$s1="";
    	for ($i=0;$i<strlen($s0);$i++) {
    		$c=substr($s0,$i,1);
    		if ($c != " ") $s1 .=$c;
    	}
    	return $s1;
    }
    function formatel($s0) {
		if (strlen($s0)==10) {
			$s1 = substr($s0,-10,2);
			for ($i=4;$i>0;$i--) $s1 .=" ".substr($s0,-2*$i,2); 
		} else $s1=$s0;
		return $s1;
    }
    function formatSS($s0) {
    	if (strlen($s0)==13) {
    		$s1=substr($s0,-13,1)." ".substr($s0,-12,2)." ".substr($s0,-10,2)." ".substr($s0,-8,2)." ".substr($s0,-6,3)." ".substr($s0,-3,3);
    	} else if (strlen($s0)==15) {
    		$s1=substr($s0,-15,1)." ".substr($s0,-14,2)." ".substr($s0,-12,2)." ".substr($s0,-10,2)." ".substr($s0,-8,3)." ".substr($s0,-5,3)." ".substr($s0,-2,2);
    	} else $s1=$s0;
    	return $s1;
    }
    function writefileadh($tadh) {
    	$M1 = new MConf;
    	$sql="SELECT * FROM $tadh ORDER BY nom";
    	$repons = $M1->querydb($sql);
    	$id1=array();$nom1=array();$prenom1=array();
    	while ($donnees = $repons->fetch()) {
        	array_push($id1,$donnees['id']);
        	array_push($nom1,$donnees['nom']);
        	array_push($prenom1,$donnees['prenom']); 
    	}
    	$optionspersonne="";
    	for($i=0;$i<count($id1);$i++) {
        	$optionspersonne = $optionspersonne."<option value=".$id1[$i].">".$nom1[$i]." ".$prenom1[$i]."</option>";
    	}
    	file_put_contents($basedir."listadh.txt",$optionspersonne);
    	$M1 = null;
    }

	class Adherent {
		public $id;
		public $titre;
		public $qualite;
		public $nom;
		public $nomjf;
		public $prenom;
		public $adresse;
		public $compadresse;
		public $codepostal;
		public $ville;
		public $telephone;
		public $portable;
		public $courriel;
		public $numMGEN;
		public $numeroSS;
		public $profession;
		public $assurance;
		public $cotisation;
		public $premannee;
		public $sortie;
		public $specialite;
		public $prenomnom;
		public $activites;
		public $activite1;
		public $activite2;
		public $activite3;
		public $activite4;
		public $activite5;
		public $activite6;
		public $groupe1;
		public $groupe2;
		public $groupe3;
		public $groupe4;
		public $groupe5;
		public $groupe6;
		public $particip1;
		public $particip2;
		public $particip3;
		public $particip4;
		public $particip5;
		public $particip6;
		public $particip1a;
		public $particip2a;
		public $particip3a;
		public $particip4a;
		public $particip5a;
		public $particip6a;

		private function arrayunique($a) {
				$b=array();
				for ($i=0;$i<count($a);$i++) {
					if ($i==0) array_push($b,$a[0]);
					$trouve=false;$j=0;
					while ((!$trouve)and($j<count($b))) {
						$trouve=($b[$j]==$a[$i]);
						if (!$trouve) $j++;
					}
					if (!$trouve) array_push($b,$a[$i]);
				}
				return $b;
			}

		public function getadh($tadh) {
			$M = new MConf;
			$sql = "SELECT * FROM $tadh WHERE id=$this->id";
		    $reponse=$M->querydb($sql);
			$donnees=$reponse->fetch();
			if ($donnees) {
				$this->id=$donnees['id'];
				$this->titre=$donnees['titre'];
				$this->qualite=$donnees['qualite'];
				$this->nom=$donnees['nom'];
				$this->nomjf=$donnees['nomjf'];
				$this->prenom=$donnees['prenom'];
				$this->adresse=$donnees['adresse'];
				$this->compadresse=$donnees['compadresse'];
				$this->codepostal=$donnees['codepostal'];
				$this->ville=$donnees['ville'];
				$this->telephone=formatel($donnees['telephone']);
				$this->portable=formatel($donnees['portable']);
				$this->courriel=$donnees['courriel'];
				$this->numMGEN=$donnees['numMGEN'];
				$this->numeroSS=formatSS($donnees['numeroSS']);
				$this->profession=$donnees['profession'];
				$this->specialite=$donnees['specialite'];
				$this->assurance=$donnees['assurance'];
				$this->cotisation=$donnees['cotisation'];
				$this->premannee=$donnees['premannee'];
				$this->sortie=$donnees['sortie'];
				$this->activites=$donnees['activites'];
				$this->prenomnom=$donnees['prenomnom'];
			}
			$M->close();			
		}

		public function supprime($tadh) {
			$M = new MConf;
			$sql = "DELETE FROM $tadh WHERE id=$this->id";
		    $reponse=$M->querydb($sql);
		    $M->close();
		 	return $reponse;
		}
		public function modifie($tadh) {
			$M = new MConf;
			$sql = "SELECT * FROM $tadh WHERE id=$this->id";
		    $reponse=$M->querydb($sql);
			$donnees=$reponse->fetch();
			$filemodif=false;
			$sql = "UPDATE $tadh SET";
			$this->nom = transprenom($this->nom);
			$this->prenom = transprenom($this->prenom);
			$this->nomjf = transprenom($this->nomjf);
			$this->numeroSS = compacte($this->numeroSS);
			$this->telephone = compacte($this->telephone);
			$this->portable = compacte($this->portable);
			if ($this->titre != $donnees['titre']) $sql .=" titre='$this->titre',";
			if ($this->qualite != $donnees['qualite']) $sql .=" qualite='$this->qualite',";
			if ($this->nom != $donnees['nom']) {$sql .=" nom='".addslashes($this->nom)."',";$filemodif=true;}
			if ($this->prenom != $donnees['prenom']) {$sql .=" prenom='".addslashes($this->prenom)."',";$filemodif=true;}
			if ($this->nomjf != $donnees['nomjf']) $sql .=" nomjf='".addslashes($this->nomjf)."',";
			if ($this->adresse != $donnees['adresse']) $sql .=" adresse='".addslashes($this->adresse)."',";
			if ($this->compadresse != $donnees['compadresse']) $sql .=" compadresse='".addslashes($this->compadresse)."',";
			if ($this->ville != $donnees['ville']) $sql .=" ville='".addslashes($this->ville)."',";
			if ($this->codepostal != $donnees['codepostal']) $sql .=" codepostal='$this->codepostal',";
			if ($this->assurance != $donnees['assurance']) $sql .=" assurance='".addslashes($this->assurance)."',";
			if ($this->profession != $donnees['profession']) $sql .=" profession='".addslashes($this->profession)."',";
			if ($this->specialite != $donnees['specialite']) $sql .=" specialite='".addslashes($this->specialite)."',";
			if ($this->numMGEN !== $donnees['numMGEN']) $sql .=" numMGEN='$this->numMGEN',";
			if ($this->numeroSS != $donnees['numeroSS']) $sql .=" numeroSS='$this->numeroSS',";
			if ($this->cotisation != $donnees['cotisation']) $sql .=" cotisation=$this->cotisation,";
			if ($this->premannee != $donnees['premannee']) $sql .=" premannee=$this->premannee,";
			if ($this->activites != $donnees['activites']) $sql .=" activites='$this->activites',";
			if ($this->telephone != $donnees['telephone']) $sql .=" telephone='$this->telephone',";
			if ($this->portable != $donnees['portable']) $sql .=" portable='$this->portable',";
			if ($this->courriel != $donnees['courriel']) $sql .=" courriel='$this->courriel',";
			if ($this->sortie != $donnees['sortie']) $sql .=" sortie='$this->sortie',";
			if ($this->prenomnom != $donnees['prenomnom']) $sql .=" prenomnom='$this->prenomnom',";
			if ($sql != "UPDATE $tadh SET") {
		    	$sql=substr($sql,0,-1)." WHERE id=$this->id";
		    	//echo $sql;
			    $reponse=$M->querydb($sql);
			    if (($filemodif)&&($reponse)) writefileadh($tadh);
			    $M->close();
			 	return $reponse;
			 } else return true;
		}
		public function insere($tadh) {
			$MM = new MConf;
			$this->premannee=$MM->annee;
			$this->nom = addslashes(transprenom($this->nom));
	    	$this->prenom = addslashes(transprenom($this->prenom));
	    	$this->nomjf = addslashes(transprenom($this->nomjf));
	    	$this->adresse = addslashes($this->adresse);
	    	$this->compadresse = addslashes($this->compadresse);
	    	$this->ville = addslashes($this->ville);
	    	$this->assurance = addslashes($this->assurance);
	    	$this->profession = addslashes($this->profession);
	    	$this->specialite = addslashes($this->specialite);
	    	$this->prenomnom = $this->prenom." ".$this->nom;
	    	$this->cotisation = 1;
	    	//echo $this->activites."<br>";
	    	$sql0 = "INSERT INTO $tadh (";
	    	$sql1 = " VALUES (";
	    	$sql0 .="titre,qualite,nom,prenom,adresse,codepostal,ville,";
	    	$sql1 .="'$this->titre','$this->qualite','$this->nom','$this->prenom','$this->adresse',$this->codepostal,'$this->ville',";
	    	$sql0 .="profession,premannee,activites,prenomnom,cotisation,";
	    	$sql1 .="'$this->profession',$this->premannee,'$this->activites','$this->prenomnom',$this->cotisation,";
			if (strlen($this->numMGEN)>0) {$sql0 .="numMGEN,";$sql1 .="'$this->numMGEN',";}
			if (strlen($this->assurance)>0) {$sql0 .="assurance,";$sql1 .="'$this->assurance',";}
			if (strlen($this->numeroSS)>0) {$sql0 .="numeroSS,";$sql1 .="'".compacte($this->numeroSS)."',";}
	    	if (strlen($this->nomjf)>0) {$sql0 .="nomjf,";$sql1 .="'$this->nomjf',";}
	    	if (strlen($this->telephone)>0) {$sql0 .="telephone,";$sql1 .="'".compacte($this->telephone)."',";}
	    	if (strlen($this->portable)>0) {$sql0 .="portable,";$sql1 .="'".compacte($this->portable)."',";}
	    	if (strlen($this->compadresse)>0) {$sql0 .="compadresse,";$sql1 .="'$this->compadresse',";}
	    	if (strlen($this->courriel)>0) {$sql0 .="courriel,";$sql1 .="'$this->courriel',";}
	    	if (strlen($this->specialite)>0) {$sql0 .="specialite,";$sql1 .="'$this->specialite',";}
	    	$sql0=substr($sql0,0,-1).")";
	    	$sql1=substr($sql1,0,-1).")";
			$sql=$sql0.$sql1;
			echo $sql."<br>";
		    $retour=$MM->querydb($sql);
		    if ($retour) writefileadh($tadh);
			$this->id=$MM->lastId($tadh);
		    $MM->close();
		    return $retour;
		}
		public function getpost() {
			$this->id = $_POST['id'];
			$this->titre = $_POST['titre'];
			$this->qualite = $_POST['qualite'];
			$this->nom = $_POST['nom'];
			$this->nomjf = $_POST['nomjf'];
			$this->prenom = $_POST['prenom'];
			$this->numMGEN = $_POST['numMGEN'];
			$this->telephone = $_POST['telephone'];
			$this->portable = $_POST['portable'];
			$this->adresse = $_POST['adresse'];
			$this->compadresse = $_POST['compadresse'];
			$this->codepostal = $_POST['codepostal'];
			$this->ville = $_POST['ville'];
			$this->courriel = $_POST['courriel'];
			$this->profession = $_POST['profession'];
			$this->specialite = $_POST['specialite'];
			$this->assurance = $_POST['assurance'];
			$this->numeroSS = $_POST['numeroSS'];
			$this->cotisation = $_POST['cotisation'];
			$this->premannee = $_POST['premannee'];
			$this->activites = $_POST['activites'];
			$this->prenomnom = $_POST['prenom']." ".$_POST['nom'];
			$this->activite1=$_POST['activite1'];
			$this->activite2=$_POST['activite2'];
			$this->activite3=$_POST['activite3'];
			$this->activite4=$_POST['activite4'];
			$this->activite5=$_POST['activite5'];
			$this->activite6=$_POST['activite6'];
			$this->groupe1=$_POST['groupe1'];
			$this->groupe2=$_POST['groupe2'];
			$this->groupe3=$_POST['groupe3'];
			$this->groupe4=$_POST['groupe4'];
			$this->groupe5=$_POST['groupe5'];
			$this->groupe6=$_POST['groupe6'];
			$this->particip1=$_POST['particip1'];
			$this->particip2=$_POST['particip2'];
			$this->particip3=$_POST['particip3'];
			$this->particip4=$_POST['particip4'];
			$this->particip5=$_POST['particip5'];
			$this->particip6=$_POST['particip6'];
/*
			if (isset($_POST['p1p'])) $this->particip1="P"; else if (isset($_POST['p1a'])) $this->particip1="A";else $this->particip1="";
			if (isset($_POST['p2p'])) $this->particip2="P"; else if (isset($_POST['p2a'])) $this->particip2="A";else $this->particip2="";
			if (isset($_POST['p3p'])) $this->particip3="P"; else if (isset($_POST['p3a'])) $this->particip3="A";else $this->particip3="";
			if (isset($_POST['p4p'])) $this->particip4="P"; else if (isset($_POST['p4a'])) $this->particip4="A";else $this->particip4="";
			if (isset($_POST['p5p'])) $this->particip5="P"; else if (isset($_POST['p5a'])) $this->particip5="A";else $this->particip5="";
			if (isset($_POST['p6p'])) $this->particip6="P"; else if (isset($_POST['p6a'])) $this->particip6="A";else $this->particip6="";
			if ((isset($_POST['affich']))&&($_POST['affich']=="on")) {
				if (isset($_POST['particip1'])) $this->particip1="P";else $this->particip1="A";
				if (isset($_POST['particip2'])) $this->particip2="P";else $this->particip2="A";
				if (isset($_POST['particip3'])) $this->particip3="P";else $this->particip3="A";
				if (isset($_POST['particip4'])) $this->particip4="P";else $this->particip4="A";
				if (isset($_POST['particip5'])) $this->particip5="P";else $this->particip5="A";
				if (isset($_POST['particip6'])) $this->particip6="P";else $this->particip6="A";
			}
*/		}
		public function getcodes($tact) {
			$M = new MConf;
		 	$sql = "SELECT * FROM $tact";
		    $reponse=$M->querydb($sql);
		    $nomactivite=array();$codactivite=array();
		    while($donnees = $reponse->fetch()) {
		    	array_push($nomactivite,$donnees['activite']);
		    	array_push($codactivite,$donnees['codactivite']);
		    }
		    $M->close();
		    $nomactivite=$this->arrayunique($nomactivite);
		    $codactivite=$this->arrayunique($codactivite);
    		$act=array();$gro=array();$par=array();$pp="";
    		if ($this->activite1 != "Pas d'activité") {
    			array_push($act,$this->activite1);array_push($gro,$this->groupe1);array_push($par,$this->particip1);
    			if (strstr($this->activite1,"Informatique")) $pp .=$this->particip1;
    		}
    		if ($this->activite2 != "Pas d'activité") {
    			array_push($act,$this->activite2);array_push($gro,$this->groupe2);array_push($par,$this->particip2);
    			if (strstr($this->activite2,"Informatique")) $pp .=$this->particip2;
    		}
    		if ($this->activite3 != "Pas d'activité") {
    			array_push($act,$this->activite3);array_push($gro,$this->groupe3);array_push($par,$this->particip3);
    			if (strstr($this->activite3,"Informatique")) $pp .=$this->particip3;
    		}
    		if ($this->activite4 != "Pas d'activité") {
    			array_push($act,$this->activite4);array_push($gro,$this->groupe4);array_push($par,$this->particip4);
    			if (strstr($this->activite4,"Informatique")) $pp .=$this->particip4;
    		}
    		if ($this->activite5 != "Pas d'activité") {
    			array_push($act,$this->activite5);array_push($gro,$this->groupe5);array_push($par,$this->particip5);
    			if (strstr($this->activite5,"Informatique")) $pp .=$this->particip5;
    		}
    		if ($this->activite6 != "Pas d'activité") {
    			array_push($act,$this->activite6);array_push($gro,$this->groupe6);array_push($par,$this->particip6);
    			if (strstr($this->activite6,"Informatique")) $pp .=$this->particip6;
    		}
    		if (strstr($pp,"P")) {
    			if (strstr($this->activite1,"Informatique")) $par[0]="P";
    			if (strstr($this->activite2,"Informatique")) $par[1]="P";
    			if (strstr($this->activite3,"Informatique")) $par[2]="P";
    			if (strstr($this->activite4,"Informatique")) $par[3]="P";
    			if (strstr($this->activite5,"Informatique")) $par[4]="P";
    			if (strstr($this->activite6,"Informatique")) $par[5]="P";
    		}
    		//print_r($act);echo"<br>";
    		//print_r($gro);echo"<br>";
    		//print_r($par);echo"<br>";
    		$nact=count($act);
    		$this->activites="";
		    for ($i=0;$i<$nact;$i++) {
      			$trouve = false;$j=0;
      			while ((!$trouve)and($j<count($nomactivite))) {
        			$trouve=($act[$i] == $nomactivite[$j]);
			        if (!$trouve) $j++;
    			}
	      		if ($trouve) {
	      			if (strlen($codactivite[$j])<2) $this->activites .="=0".$codactivite[$j]."-".$gro[$i]."-".$par[$i];
	      			else $this->activites .="=".$codactivite[$j]."-".$gro[$i]."-".$par[$i];
	      		}
	      		 
    		}
    		if (strlen($this->activites)<1) $this->activites="=00";
		}
		public function getactivites($tact) {
			$M = new MConf;
		 	$sql = "SELECT * FROM $tact";
		    $reponse=$M->querydb($sql);
		    $nomactivite=array();$codactivite=array();
		    while($donnees = $reponse->fetch()) {
		    	array_push($nomactivite,$donnees['activite']);
		    	array_push($codactivite,$donnees['codactivite']);
		    }
		    $M->close();
		    $nomactivite=$this->arrayunique($nomactivite);
		    $codactivite=$this->arrayunique($codactivite);

		    $this->activite1="Pas d'activité";$this->activite2="Pas d'activité";$this->activite3="Pas d'activité";
		    $this->activite4="Pas d'activité";$this->activite5="Pas d'activité";$this->activite6="Pas d'activité";
		    $this->groupe1="0";$this->groupe2="0";$this->groupe3="0";
		    $this->groupe4="0";$this->groupe5="0";$this->groupe6="0";
    		if (substr($this->activites,0,1)=="=") $this->activites=substr($this->activites,1);
    		$c=explode("=",$this->activites);
   			if ($c[0]!="00") {
	    		for ($i=0;$i<count($c);$i++) {
	    			$trouve=false;$j=0;
	    			if (strpos($c[$i],"-")>0) {
	    				$aa=explode("-",$c[$i]);
	    				$a=$aa[0];$g=$aa[1];
	    				if (count($aa)>2) $p=$aa[2];
	    				else $p="A";
	    			} else {$a=$c[$i];$g="0";$p="A";}
	    			//echo "a=".$a."<br>";
	    			while ((!$trouve)and($j<count($codactivite))) {
	    				$trouve = ($a == $codactivite[$j]);
	    				if (!$trouve) $j++;
	    			}
	    			//echo "j=".$j."<br>";
	    			//echo $nomactivite[$j]."-".$g."<br>";
	    			switch($i) {
	    				case 0 : $this->activite1 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe1 = $g;
	    						 $this->particip1 = $p; 
	    						 break;
	    				case 1 : $this->activite2 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe2 = $g;
	    						 $this->particip2 = $p; 
	    						 break;
	    				case 2 : $this->activite3 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe3 = $g;
	    						 $this->particip3 = $p; 
	    						 break;
	    				case 3 : $this->activite4 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe4 = $g;
	    						 $this->particip4 = $p; 
	    						 break;
	    				case 4 : $this->activite5 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe5 = $g;
	    						 $this->particip5 = $p; 
	    						 break;
	    				case 5 : $this->activite6 = $nomactivite[$j];
	    						 if ($g != "0") $this->groupe6 = $g;
	    						 $this->particip6 = $p; 
	    						 break;
	    			}
	    		}
	    		$pp="";
	    		if (strstr($this->activite1,"Informatique")) $pp .=$this->particip1;
	    		if (strstr($this->activite2,"Informatique")) $pp .=$this->particip2;
	    		if (strstr($this->activite3,"Informatique")) $pp .=$this->particip3;
	    		if (strstr($this->activite4,"Informatique")) $pp .=$this->particip4;
	    		if (strstr($this->activite5,"Informatique")) $pp .=$this->particip5;
	    		if (strstr($this->activite6,"Informatique")) $pp .=$this->particip6;
	    		if (strstr($pp,"P")) {
		    		if (strstr($this->activite1,"Informatique")) $this->particip1="P";
		    		if (strstr($this->activite2,"Informatique")) $this->particip2="P";
		    		if (strstr($this->activite3,"Informatique")) $this->particip3="P";
		    		if (strstr($this->activite4,"Informatique")) $this->particip4="P";
		    		if (strstr($this->activite5,"Informatique")) $this->particip5="P";
		    		if (strstr($this->activite6,"Informatique")) $this->particip6="P";
	    		}
	    	}
    	}
	}

	class Adherents {
		public $adh;
		public $n;
		private $a;

		public function cherche($sql,$tact) {
			$M = new MConf;
	        $reponse=$M->querydb($sql);
	        $this->n=0;$this->adh=array();
	        while ($donnees = $reponse->fetch()) {
				$a = new Adherent;
				$a->id=$donnees['id'];
				$a->titre=$donnees['titre'];
				$a->qualite=$donnees['qualite'];
				$a->nom=$donnees['nom'];
				$a->nomjf=$donnees['nomjf'];
				$a->prenom=$donnees['prenom'];
				$a->adresse=$donnees['adresse'];
				$a->compadresse=$donnees['compadresse'];
				$a->codepostal=$donnees['codepostal'];
				$a->ville=$donnees['ville'];
				$a->telephone=formatel($donnees['telephone']);
				$a->portable=formatel($donnees['portable']);
				$a->courriel=$donnees['courriel'];
				$a->numMGEN=$donnees['numMGEN'];
				$a->numeroSS=formatSS($donnees['numeroSS']);
				$a->profession=$donnees['profession'];
				$a->specialite=$donnees['specialite'];
				$a->assurance=$donnees['assurance'];
				$a->cotisation=$donnees['cotisation'];
				$a->premannee=$donnees['premannee'];
				$a->sortie=$donnees['sortie'];
				$a->activites=$donnees['activites'];
				$a->getactivites($tact);
				array_push($this->adh, $a);
				$this->n++;
			}
			$M->close();
		}
	}

?>