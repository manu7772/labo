<?php
###########################################
### VERSION	1.00						###
### NOM		AeTwigPrepar				###
### DATE	06/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeTwigPREPAR - Gestion de Twig        */
/* ************************************* */
// $_SESSION[SSDATA]

CLASS AeTwigPREPAR {

	function __construct() {
		if(!$_SESSION[TMPDATA]["pageactuelle"]["nopage"]) {
			// Charge TWIG
			AeGlobalDev::notice("Chargement de twig");
			@include_once($_SESSION[SSDATA]['twigparam']['twigpath']);
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($_SESSION[SSDATA]['twigparam']['dossiers']);
			$twig = new Twig_Environment($loader, $_SESSION[SSDATA]['twigparam']['options']);
			@include_once(CLASSES."extension_filters.twig.php");
			@include_once(CLASSES."extension_fonctions.twig.php");
			// Affichage de la page du site
			if($_SESSION[TMPDATA]["pageactuelle"]["template"] != null) {
				if(!is_array($_SESSION[TMPDATA])) $_SESSION[TMPDATA] = array();
				AeGlobalDev::notice("Affichage du template... (".$_SESSION[TMPDATA]["pageactuelle"]["template"].")");
				echo $twig->render($_SESSION[TMPDATA]["pageactuelle"]["template"] , array_merge($_SESSION[SSDATA], $_SESSION[TMPDATA]));
			}
			AeGlobalDev::printdata(array_merge($_SESSION[SSDATA], $_SESSION[TMPDATA]), "Données pour Twig", auto);
		}
	}

}
?>