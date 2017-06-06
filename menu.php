<div id="datedujour"> &nbsp;&nbsp;
	<?php 
	//setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
	setlocale(LC_TIME,'fr_FR.utf8','fra','fr_FR.ISO8859-1');
	echo utf8_encode(strftime("%A %d %B %Y"));
	$annee = utf8_encode(strftime("%Y"));
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$prenom." ".$nom." aux commandes !";
	$anencours = strftime("%Y");
	//echo $_COOKIE['PHPSESSID'];
	?>
</div>
<span id="fenetre"></span>
 <div  id='topPage'> Administration de la gestion du Club des retraités de la MGEN (section de Paris)<div>
 	<div id="menugeneral">
	<ul class="niveau1">
		<li>
			<a href="club0.php">Accueil</a>
		</li>
		<li>
			<a href="#">Gestionnaires</a>
			<ul>
				<li><a href="supprimUtilisateur.php">Supprimer</a></li>
				<li><a href="modifdroits.php">Modifier les droits</a></li>
				<li><a href="modiflogpass.php">Modifier identifiant/mot de passe</a></li>
				<li><a href="nouvelUtilisateur.php">Créer</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Tables</a>
			<ul>
				<li><a href="choisirTables.php">Choisir</a></li>
				<li><a href="exportTable.php">Exporter</a></li>
				<li><a href="importTable.php">Importer</a></li>
				<li><a href="newyear.php">Nouvelle année</a></li>
				<li><a href="litlog.php">Interventions</a></li>
			</ul>
		</li>
		<li>
			<a href="quitter.php">Quitter</a>
		</li>
	</ul>
</div>
</br></br>
