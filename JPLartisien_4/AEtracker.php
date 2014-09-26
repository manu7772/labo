<?php
// données de base
include("classes/initprime.inc.php");
include("classes/aesmartbdd.class.php");
$AeSessions = new AeSessions();

// Enregistrement des préfs en BDD (pour USER)
$_BDD = new AeSmartBDD();
$_BDD->AEtracker(
	$_SESSION[SSDATA]["USER"]["membre_ID"],
	$_SESSION[SSDATA]["IP"],
	$_POST["code"],
	$_POST["prov"]
);

?>