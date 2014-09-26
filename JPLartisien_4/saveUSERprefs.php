<?php
// données de base
include("classes/initprime.inc.php");
include("classes/aesmartbdd.class.php");
$AeSessions = new AeSessions();

// Enregistrement des préfs en BDD (pour USER)
$_BDD = new AeSmartBDD();
if($_POST["USER"]) $liste = $_BDD->saveUSERprefs($_POST);
	else echo("Pas d'utilisateur... sauvegarde non effectuée<br />");

// Enregistrement des préfs en mémoire (pour non connectés)
foreach($_POST as $nom => $val) {
	if(substr($nom, 0, 6) == "Prefs_") $_SESSION[SSDATA]["TMPprefs"][str_replace("Prefs_", "", $nom)] = $val;
}

?>