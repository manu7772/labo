<?php
###########################################
### VERSION	1.00						###
### NOM		AeCarte 					###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeCarte						         */
/* ************************************* */
CLASS AeCarte {

	private $_Motscles;		// array mots clés du site

	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"Motscles",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();

	function __construct($langue = null) {
		if($langue != null) {
			$langue = "[@langue='".$langue."']";
		} else $langue = "";
		$this->_Pages = AeQuickXML::loadSXD(XMLKEYS, "/KEYS/langue".$langue."/mot");
		// AeTools::dumpdata($this->_Pages);
	}

	public function ConstructTmp($ID) {
		
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