var idlog,idlog0=0;


function prepare(sactivite) {
	console.log("début de prepare");
	activite = sactivite.split(",");
	console.log(activite[1]);
	var monSelect1 = document.getElementById("activite1");
	monSelect1.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect1.options[i]=new Option(activite[i],i);

	var monSelect2 = document.getElementById("activite2");
	monSelect2.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect2.options[i]=new Option(activite[i],i);

	var monSelect3 = document.getElementById("activite3");
	monSelect3.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect3.options[i]=new Option(activite[i],i);

	var monSelect4 = document.getElementById("activite4");
	monSelect4.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect4.options[i]=new Option(activite[i],i);

	var monSelect5 = document.getElementById("activite5");
	monSelect5.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect5.options[i]=new Option(activite[i],i);

	var monSelect6 = document.getElementById("activite6");
	monSelect6.options.length = 0;
	for (var i=0;i<activite.length;i++) monSelect6.options[i]=new Option(activite[i],i);
}

function resizemenu() {
		var w = window.innerWidth; // || document.documentElement.clientWidth || document.body.clientWidth;
		var wl=[100,105,110,120,125,130,135,140,145,150,155,160,165,170,175,180,185,190,195,200,205,210,215,220,225];
		var j=Math.floor((w-800)/20);
		if (j<0) j=0;
		if (j>24) j=24;
		var ml=Math.floor((w-4*wl[j])/2);
		//console.log="  largeur = "+w+"  j="+j+"  marge gauche = "+ml;
		//window.document.getElementById("log").innerHTML="  largeur = "+w+"  j="+j+"  marge gauche = "+ml;
		var elem1 = window.document.querySelectorAll('ul.niveau1 li');
		for (var i=0;i<elem1.length;i++) 
			elem1[i].style.width = String(wl[j])+"px";
		var elem2 = window.document.querySelectorAll("ul.niveau1 li ul");
		for (var i=0;i<elem2.length;i++) 
			elem2[i].style.width=String(wl[j])+"px";
		window.document.getElementById("menugeneral").style.marginLeft=String(ml)+"px";
}

var sortie = function(t) {
	document.getElementById("log").innerHTML +="<br />--> " + t;
	};

function arrayunique(a) {
		b=[];
		for (i=0;i<a.length;i++) {
			if (i==0) b.push(a[0]);
			else {
				trouve=false;j=0;
				while ((!trouve)&&(j<b.length)) {
					trouve=(b[j]==a[i]);
					if (!trouve) j++;
				}
				if (!trouve) b.push(a[i]);
			}
		}
		return b;
	}


function utilactifs(rows) {
	var row;
	var util=[],req=[],sessid=[],OK=[];
	for (j=0;j<rows.length;j++) {
		row = rows[j].split("&");
		util.push(row[1]);
		req.push(row[2]);
		sessid.push(row[3]);
		OK.push(true);
	}
	for (i=0;i<util.length-1;i++) {
		for (j=i+1;j<util.length;j++) {
			if ((OK[i])&&(OK[j])&&(sessid[i] == sessid[j])) {
				OK[i]=false;OK[j]=false;
			}
		}	 
	}
	var utilisateur=[];
	for (i=0;i<util.length;i++) if (OK[i]) utilisateur.push(util[i]);
	var utilisateur =  arrayunique(utilisateur);
	return utilisateur;
}

function interrolog() {		//AJAX (Asynchronous JAvascript and Xml)
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
        	var rep=xmlhttp.responseText;
        	//sortie("Dans interroTable rep="+rep);
        	var rows= rep.split("$");
        	var row=rows[0].split("&");
        	//sortie(rows[0]);
        	//sortie(row[0]+" "+row[1]+" "+row[2]);
        	if (idlog0 == 0) {idlog0 = Number(row[0]);}
        	else {
        		idlog = Number(row[0]);
        		if (idlog>idlog0) {
        			var conn=(row[2].includes("DEBUT DE CONNEXION"));
        			var deco=(row[2].includes("FIN DE CONNEXION"));
        			if (conn || deco) {
	        			var mes;
						if (conn) mes = row[1]+" vient de se connecter.";
						if (deco) mes = row[1]+" vient de se déconnecter.";
						var utilisateur=utilactifs(rows);
						if (utilisateur.length == 0) mes +="\nPersonne n'est actuellement connecté.";
						if (utilisateur.length == 1) mes +="\n"+utilisateur[0]+" est connecté(e)";
						if (utilisateur.length>1) {
							for (j=0;j<utilisateur.length;j++) {
								if (j==0) mes +="\n"+utilisateur[j];
								else mes +=", "+utilisateur[j];
							}
							mes +=" sont connecté(e)s.";
						}
						//sortie(mes);
	        			var n = new Notification('Bonjour', {
							body: mes,
							icon: 'coccicon.ico',
							sound: ''
						});
						idlog0 = idlog;
					}
        		}
        	}

        }
    };
    var req="";
    req="interrolog.php";
	xmlhttp.open("GET",req, true);
    xmlhttp.send();
}

function resizemenu0() {
	resizemenu();
	setInterval(interrolog,1000);
}

function blanc(id) {
	switch(id) {
		case 1:document.getElementById("nom").style.backgroundColor ="white";break;
		case 2:document.getElementById("prenom").style.backgroundColor ="white";break;
		case 3:document.getElementById("numMGEN").style.backgroundColor ="white";break;
		case 4:document.getElementById("telephone").style.backgroundColor ="white";break;
		case 5:document.getElementById("adresse").style.backgroundColor ="white";break;
		case 6:document.getElementById("codepostal").style.backgroundColor ="white";break;
		case 7:document.getElementById("assurance").style.backgroundColor ="white";break;
		case 8:document.getElementById("numeroSS").style.backgroundColor ="white";break;
	}
}

function test(id) {
	var elem;
	switch(id) {
		case 1: elem = document.forms["nouvelAd"]["nom"];break;
		case 2: elem = document.forms["nouvelAd"]["prenom"];break;
		case 3: elem = document.forms["nouvelAd"]["numMGEN"];break;
		case 4: elem = document.forms["nouvelAd"]["telephone"];break;
		case 5: elem = document.forms["nouvelAd"]["adresse"];break;
		case 6: elem = document.forms["nouvelAd"]["codepostal"];break;
		case 7: elem = document.forms["nouvelAd"]["assurance"];break;
		case 8: elem = document.forms["nouvelAd"]["numeroSS"];break;
	}
	if (elem.value=="") elem.style.backgroundColor = "pink"; 
	else elem.style.backgroundColor = "lime";
}

function vraitelephone(s) {
	var pat3=/^([0-9]{10})$/
	return pat3.test(s);
}
function vraimail(s) {
	var pat4=/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|paris|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
	return pat4.test(s);
}
function vrainumSS(s) {
	var pat0;
	if (s.length==13) pat0=/^([0-9]{13})$/;
	else if (s.length==15) {
		var cle = Number(s.substring(13,15));
		var num1=Number(s.substring(0,13));
		//console.log(cle);
		//console.log(num1);
		//console.log(num1 % 97);
		if (num1 % 97 != 97-cle) return false;
		pat0=/^([0-9]{15})$/;
	} 
	else return false;  
	return pat0.test(s); 
}
function valideAnimateur() {
	document.getElementById("message").innerHTML="Début de la validation";
	nb=0;
	var msg="Vous avez oublié d'indiquer : </br>";
	if (document.forms["nouvelAnimateur"]["nom"].value=="") {msg+="- le nom</br>";nb++;}
	if (document.forms["nouvelAnimateur"]["prenom"].value=="") {msg+="- le prénom</br>";nb++;}
	if (document.forms["nouvelAnimateur"]["telephone"].value=="")	{msg+="- le téléphone</br>";nb++;}
	var a=document.forms["nouvelAnimateur"]["titre"].selectedIndex;
	if (a==0) {msg+="- le titre</br>";nb++;} 
	var a=document.forms["nouvelAnimateur"]["benevole"].selectedIndex;
	if (a==0) {msg+="- la qualité (bénévole ou payé)</br>";nb++;}
		document.getElementById("message").innerHTML="nb ="+nb;
	if (nb>0) {
	    $.msgBox({
    		title:"Création d'une fiche Animateur",
    		content:"La fiche est incomplète. "+msg
    	});
	}
	if (nb<1) {	
		var telephone = document.forms["nouvelAnimateur"]["telephone"].value;
		var s=telephone.split(" ");telephone=s.join("");
		if (!vraitelephone(telephone)) {
		    $.msgBox({
	    		title:"Création d'une fiche Animateur",
	    		content:"Le numéro de téléphone ne paraît pas correct"
	    	});	
	    	nb++;	
		}
	} 
	if (nb<1) {
		if (document.forms["nouvelAnimateur"]["portable"].value!="") {
			var portable = document.forms["nouvelAnimateur"]["portable"].value;
			var s=portable.split(" ");portable=s.join("");
			if (!vraitelephone(portable)) {
			    $.msgBox({
		    		title:"Création d'une fiche Animateur",
		    		content:"Le numéro de portable ne paraît pas correct"
		    	});		
		    	nb++;	
			}
		} 
	}
	if (nb<1) {
		if (document.forms["nouvelAnimateur"]["courriel"].value!=""){
			var courriel = document.forms["nouvelAnimateur"]["courriel"].value;
			if (courriel !="") {
				if (!vraimail(courriel)) {
				    $.msgBox({
			    		title:"Création d'une fiche Animateur",
			    		content:"Le courriel ne paraît pas correct"
			    	});		
			    	nb++;	
				}
			}
		}
	}
	if (nb<1) {
		document.getElementById("message").innerHTML="Saisie validée";
		document.getElementById("go").type="submit";
		document.getElementById("bouton0").style.visibility="hidden";

		//submitform();
	}
}

function valide() {
	document.getElementById("message").innerHTML="Début de la validation";
	nb=0;
	var msg="Vous avez oublié d'indiquer : </br>";
	if (document.forms["nouvelAd"]["nom"].value=="") {msg+="- le nom</br>";nb++;}
	if (document.forms["nouvelAd"]["prenom"].value=="") {msg+="- le prénom</br>";nb++;}
	if (document.forms["nouvelAd"]["numMGEN"].value=="") {msg+="- le numéro MGEN</br>";nb++;}
	if (document.forms["nouvelAd"]["telephone"].value=="")	{msg+="- le téléphone</br>";nb++;}
	if (document.forms["nouvelAd"]["adresse"].value=="") {msg+="- l'adresse</br>";nb++;}
	if (document.forms["nouvelAd"]["codepostal"].value=="") {msg+="- le code postal</br>";nb++;}
	if (document.forms["nouvelAd"]["assurance"].value=="") {msg+="- l'assurance</br>";nb++;}
	if (document.forms["nouvelAd"]["numeroSS"].value=="") {msg+="- le numéro SS</br>";nb++;}
	var a=document.forms["nouvelAd"]["titre"].selectedIndex;
	if (a==0) {msg+="- le titre</br>";nb++;} 
	var a=document.forms["nouvelAd"]["qualite"].selectedIndex;
	if (a==0) {msg+="- la qualité</br>";nb++;}
	var a=document.forms["nouvelAd"]["profession"].selectedIndex;
	if (a==0) {msg+="- la profession</br>";nb++;}
		document.getElementById("message").innerHTML="nb ="+nb;
	if ((!document.forms["nouvelAd"]["cotisationP"].checked)&&(!document.forms["nouvelAd"]["cotisationA"].checked)) {msg+="- l'état de la cotisation</br>";nb++;}
	if (nb>0) {
	    $.msgBox({
    		title:"Création d'une fiche Adhérent",
    		content:"La fiche est incomplète. "+msg
    	});
	}
	if (nb<1) {	
		var telephone = document.forms["nouvelAd"]["telephone"].value;
		var s=telephone.split(" ");telephone=s.join("");
		if (!vraitelephone(telephone)) {
		    $.msgBox({
	    		title:"Création d'une fiche Adhérent",
	    		content:"Le numéro de téléphone ne paraît pas correct"
	    	});	
	    	nb++;	
		}
	} 
	if (nb<1) {
		if (document.forms["nouvelAd"]["portable"].value!="") {
			var portable = document.forms["nouvelAd"]["portable"].value;
			var s=portable.split(" ");portable=s.join("");
			if (!vraitelephone(portable)) {
			    $.msgBox({
		    		title:"Création d'une fiche Adhérent",
		    		content:"Le numéro de portable ne paraît pas correct"
		    	});		
		    	nb++;	
			}
		} 
	}
		document.getElementById("message").innerHTML="nb2 ="+nb;
	if (nb<1) {
		if (document.forms["nouvelAd"]["numeroSS"].value!="") {
			var numSS = document.forms["nouvelAd"]["numeroSS"].value;
			var s=numSS.split(" ");numSS=s.join("");document.getElementById("message").innerHTML=numSS;
			if (!vrainumSS(numSS)) {
			    $.msgBox({
		    		title:"Création d'une fiche Adhérent",
		    		content:"Le numéro de Sécurité Sociale ne paraît pas correct"
		    	});		
		    	nb++;	
			} 
		} 
	}
		document.getElementById("message").innerHTML="nb3 ="+nb;
	if (nb<1) {
		if (document.forms["nouvelAd"]["courriel"].value!=""){
			var courriel = document.forms["nouvelAd"]["courriel"].value;
			if (courriel !="") {
				if (!vraimail(courriel)) {
				    $.msgBox({
			    		title:"Création d'une fiche Adhérent",
			    		content:"Le courriel ne paraît pas correct"
			    	});		
			    	nb++;	
				}
			}
		}
	}
	if (nb<1) {
		document.getElementById("message").innerHTML="Saisie validée";
		document.getElementById("go").type="submit";
		document.getElementById("bouton0").style.visibility="hidden";

		//submitform();
	}
}
