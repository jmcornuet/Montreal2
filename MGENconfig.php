<?php
	class MConf {
		public $annee = '2017';
		private $host = 'localhost';
		private $dbase = 'clubmgen';
		private $ident = 'root';
		private $passmac = 'root';
		private $passlinux = '182Villette';
		//public $tabladh = $tadh;
		//public $tablani = $tani;
		//public $tablgra = $tact;
		public $tablaut = 'authentification';
		private $tablog = 'tlog2016';
		private $bdd;
		private $pass;

		public function querydb($sql) {
	    	$noselect =strpos($sql,"SELECT")+strpos($sql,"FROM");
			if (PHP_OS == "Darwin") $this->pass = $this->passmac;
			else $this->pass = $this->passlinux;
			$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$this->dbase,$this->ident,$this->pass);
			$this->bdd->exec("SET NAMES 'utf8'");
	    	$reponse=$this->bdd->query($sql);
	    	if ($noselect<1) {
				setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
				$jour=strftime("%d/%m/%Y");
				$heure=strftime("%T");
				$prenom=$_SESSION['prenom'];
				$nom=$_SESSION['nom'];
				$utilisateur=$prenom." ".$nom;
				$sql=addslashes($sql);
				$sqq = "INSERT INTO $this->tablog (jour,heure,utilisateur,requete) VALUES('$jour','$heure','$utilisateur','$sql')";
				$r=$this->bdd->query($sqq);
			}
	    	return $reponse;
	    }

	    public function lastId() {
	    	return $this->bdd->lastInsertId();
	    }
	    public function close() {
	    	$this->bdd = null;
	    }
	    public function connexion() {
			if (PHP_OS == "Darwin") $this->pass = $this->passmac;
			else $this->pass = $this->passlinux;
			$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$this->dbase,$this->ident,$this->pass);
			$this->bdd->exec("SET NAMES 'utf8'");
			setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
			$jour=strftime("%d/%m/%Y");
			$heure=strftime("%T");
			$prenom=$_SESSION['prenom'];
			$nom=$_SESSION['nom'];
			$utilisateur=$prenom." ".$nom;
			$r=$this->bdd->query("INSERT INTO $this->tablog (jour,heure,utilisateur,requete) VALUES('$jour','$heure','$utilisateur','DEBUT DE CONNEXION')");
			if (!$r) die ("connexion impossible");			
	    }
	    public function deconnexion() {
			if (PHP_OS == "Darwin") $this->pass = $this->passmac;
			else $this->pass = $this->passlinux;
			$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$this->dbase,$this->ident,$this->pass);
			$this->bdd->exec("SET NAMES 'utf8'");
			setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
			$jour=strftime("%d/%m/%Y");
			$heure=strftime("%T");
			$prenom=$_SESSION['prenom'];
			$nom=$_SESSION['nom'];
			$utilisateur=$prenom." ".$nom;
			$r=$this->bdd->query("INSERT INTO $this->tablog (jour,heure,utilisateur,requete) VALUES('$jour','$heure','$utilisateur','FIN DE CONNEXION')");			
	    }
	    public function getlog() {
	    	return $this->tablog;
	    }
	}
?>