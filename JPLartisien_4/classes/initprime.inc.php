<?php
###########################################
### VERSION	1.20						###
### NOM		initprime					###
### DATE	06/03/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* Initialisation des données de base    */
/* ************************************* */
define("CLASSES", 'classes/');				// Dossier contenant les classes
define("PERSOCLASS", 'persoclass/');		// Dossier contenant les classes propres au site
define("DATA", 'data/');					// Dossier contenant les données
define('DATAXML',DATA.'xml_textes/');		// Dossier contenant les données XML
define('XMLPLAN',DATAXML.'data_site.xml');	// Fichier des données de plan du site : accès, etc.
define('XMLBASE',DATAXML.'xmlbase.xml');	// Fichier des données de base XML
define('XMLBDD',DATAXML.'data_BDD.xml');	// Fichier des données de connexion à la BDD
define('XMLDEV',DATAXML.'data_DEV.xml');	// Fichier des données de mode développement/débugg
define('XMLPTF',DATAXML.'plateformes.xml');	// Fichier des données de plateformes
define('XMLKEYS',DATAXML.'motscles.xml');	// Fichier des données de mots clés

define('SSDATA', "SSDATA");					// Données de sauvegarde de session
define('TMPDATA', "TMPDATA");				// Données de sauvegarde de session

spl_autoload_register(function($class) {
	if(strtolower(substr($class, 0, 2)) == "ae") include_once CLASSES.strtolower($class).'.class.php';
	if(strtolower(substr($class, 0, 2)) == "fp") {
		// echo("<br />".PERSOCLASS.strtolower($class).'.class.php'."<br />");
		include_once PERSOCLASS.strtolower($class).'.class.php';
	}
	// if(strtolower(substr($class, 0, 4)) != "twig") include_once CLASSES.strtolower($class).'.class.php';
});

?>