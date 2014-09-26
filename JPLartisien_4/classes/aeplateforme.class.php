<?php
###########################################
### VERSION	1.00						###
### NOM		AePlateforme  				###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AePlateforme                          */
/* Profil de plateforme utilisateur      */
/* ************************************* */
CLASS AePlateforme {

	private $_Plateformes;		// Liste des plateformes

	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"PlateformeEnCours",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();

	function __construct() {
		$this->_Plateformes = AeQuickXML::ValAllFirst(XMLPTF, "/PLATEFORMES/screen");
		// $this->_AffSERV();
	}

	private function _AffSERV() {
		AeGlobalDev::dumpdata($_SERVER, "Données de serveur (SERVER)", 70);
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
		} else return false;
	}

}

?>
