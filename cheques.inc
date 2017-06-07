<?php
	require_once("MGENconfig.php");

	class Cheque {
		public $id;
		public $datecheque;
		public $numcheque;
		public $banque;
		public $montant;
		public $titulaire;
		public $idbeneficiaire;
		public $observations;
		public $nom;
		public $prenom;
		public $matricule;


		public function getcheque($tcheq) {
			$M = new MConf;
			$sql = "SELECT * FROM $tcheq WHERE id=$this->id";
		    $reponse=$M->querydb($sql);
			$donnees=$reponse->fetch();
			if ($donnees) {
				$this->id=$donnees['id'];
				$this->datecheque=$donnees['datecheque'];
				$this->numcheque=$donnees['numcheque'];
				$this->banque=$donnees['banque'];
				$this->montant=$donnees['montant'];
				$this->titulaire=$donnees['titulaire'];
				$this->idbeneficiaire=$donnees['idbeneficiaire'];
				$this->observations=$donnees['observations'];
			}
			$M->close();			
		}

		public function insere($tcheq) {
			$MM = new MConf;
			$this->banque = addslashes($this->banque);
			$this->titulaire = addslashes($this->titulaire);
	    	$sql = "INSERT INTO $tcheq (datecheque,numcheque,banque,montant,titulaire,idbeneficiaire,observations)";
	    	$sql .= " VALUES ('$this->datecheque','$this->numcheque','$this->banque',$this->montant,'$this->titulaire',$this->idbeneficiaire,'$this->observations')";
		    $retour=$MM->querydb($sql);
			$this->id=$MM->lastId($tcheq);
		    $MM->close();
		    return $retour;
		}
}
?>
