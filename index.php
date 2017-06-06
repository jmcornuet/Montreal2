<?php 
    session_start();
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
</head>
<style>
    body {background-image: url("parispetit.jpg");background-repeat:no-repeat;background-size:cover;}

</style>
<script type="text/javascript">

</script>
<body>
   <div id="messa" class="alerte" style="visibility:hidden"> </div> 
<script type="text/javascript">
$.msgBox({ type: "prompt",
    title: "Gestion administrative du club MGEN Paris",
    inputs: [
    { header: "Identifiant", type: "text", name: "ident" },
    { header: "Mot de passe", type: "password", name: "pass" }],
    buttons: [{ value: "Connexion" }, {value:"Annuler"}],
    success: function (result, values) {
        if (result=="Connexion") {
            var formulaire = document.createElement('form');
            formulaire.setAttribute('action', 'authen.php');
            formulaire.setAttribute('method', 'post');
            var elem1,elem2;
            var i=0;
            $(values).each(function (index, input) {
                if (i==0) {
                    elem1 = document.createElement('input');
                    elem1.setAttribute('type','hidden');
                    elem1.setAttribute('name',input.name);
                    elem1.setAttribute('value',input.value);
                    formulaire.appendChild(elem1);
                    i++;
                } else {
                    elem2 = document.createElement('input');
                    elem2.setAttribute('type','hidden');
                    elem2.setAttribute('name',input.name);
                    elem2.setAttribute('value',input.value);
                    formulaire.appendChild(elem2);

                }
            });
            document.body.appendChild(formulaire);
            formulaire.submit();

        } else 
            document.getElementById("messa").innerHTML="A plus tard, peut-être...";
            document.getElementById("messa").style.visibility = "visible";
    }
});
</script>



</body>
</html>

