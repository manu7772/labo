<?php
###########################################
### VERSION	1.00						###
### NOM		AequaRender2				###
### DATE	06/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AequaRENDER - classe générale         */
/* ************************************* */

/* NOTES
ATTENTION : pour CHMOD sous Linux il faut accorder les droits des dossiers et fichiers à tous les utilisateurs

$_SESSION
	[SSDATA] -> Données sauvegardées
		-> USER --> Données utilisateur
		-> Plateforme
		-> session_name
		-> acces
		-> twigparam
		-> ext (extentions CSS / JS / ...)
		-> pages (liste des pages valides pour l'utilisateur)
		-> menus (liste des menus valides pour l'utilisateur)

	[TMPDATA] -> Données de page / renouvelées à chaque nouvelle page
		-> messages
		-> alertes
		-> ACTIONS --> Actions à effectuer en fonction des requêtes

*/

CLASS AequaRENDER {

	private $_AeSessions;	// Objet de session
	private $_AeStat;		// Objet de statistiques
	private $_AePages;		// Objet plan du site (fichier data_site.xml)

	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"SSDATA",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();

### ------------------------------------------------------------------
	
	//Utilitaire de mise à jour
	private $_Majfolder = "http://www.aequation-webdesign.fr/AequaRENDERmaj/txt_sources/";
	private $_MajtypeFiles = '(\.class\.php|\.inc\.php|\.twig\.php)';
	private $_MajtypeDist = '(\.class\.txt|\.inc\.txt|\.twig\.txt)';


	############################################################
	############################################################
	############################################################

	### Construction de classe
	function __construct() {
		// Initialise la session / Récupère les données de session dans $_SESSION
		// Gère les données utilisateur
		$this->_AeSessions = new AeSessions();

		// Construit la carte temporaire du site en fonction de l'utilisateur
		$this->_CarteTmp = new AeCarte();
		$this->_CarteTmp->ConstructTmp($this->_AeSessions->UserID);

		// Charge les pages et menus pour l'utilisateur
		// $_AePages = false si aucune page nécessaire (info récup. dans $_PostGet["page"] = true/false)
		AeGlobalDev::notice("Chargement de page : ".$_SESSION[TMPDATA]["nopage"]);
		if(!$_SESSION[TMPDATA]["nopage"]) $this->_AePages = new AePages();
		AeGlobalDev::notice("Fin de chargement de page");

		/**********************/
		/* OPÉRATIONS FINALES */
		/**********************/

		// Rassemble/synthétise toutes les données nécessaires à Twig
	}


	############################
	### MISES A JOUR / ADMIN ###
	############################

	### Mises à jour en fonctions des requêtes
	private function _MAJALL() {

	}

	########################################################################################################################
	###  METHODE MAGIQUES  #################################################################################################
	########################################################################################################################
	
	public function __get($var) {
		if(in_array($var, $this->_AutorizeVAR)) {
			$nom = "_".$var;
			return $this->$nom;
		} else if(in_array($var, $this->_AutorizeFUNCTION)) {
			return $this->$var();
		}
	}

}
?>