<?php
###########################################
### VERSION	1.00						###
### NOM		AeRequetes 					###
### DATE	06/03/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeRequetes					         */
/* ************************************* */
CLASS AeRequetes {
	private $_PostGet;			// merge de POST et GET
	private $_Exist = false;	// true si requêtes présentes, sinon false
	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"Exist",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();

	function __construct() {
		$this->_PostGet		= array_merge($_GET, $_POST);
		if(sizeof($this->_PostGet) > 0) $this->_Exist = true;
		// AeGlobalDev::printdata($this->_PostGet, "Données POST & GET", 120);
		if($this->_Exist) $this->_Analyse();
		$_SESSION[TMPDATA]["PostGet"] = $this->_PostGet;
	}

	private function _Analyse() {
		if(is_array($this->_PostGet) && sizeof($this->_PostGet) > 0) {
			$this->_Exist = true;
			foreach($_POST as $nom => $val)
			switch($nom) {
				// Log/délogg
				case "LOGIN" :
					$_SESSION[TMPDATA]["ACTIONS"]["LOGIN"]["user"] = $_POST["user"];
					$_SESSION[TMPDATA]["ACTIONS"]["LOGIN"]["clef"] = $_POST["clef"];
				break;
				case "LOGOUT" :
					$_SESSION[TMPDATA]["ACTIONS"]["LOGOUT"] = true;
				break;

				// Mises à jour
				case "MAJ" :
				break;

				// Signalisation d'un appel Ajax = action à réaliser
				case "ajaxcall" :
					$_SESSION[TMPDATA]["ACTIONS"]["ajaxcall"] = $val;
				break;
				// Signalisation d'un appel Ajax = action à réaliser
				case "callfonction" :
					$_SESSION[TMPDATA]["ACTIONS"]["callfonction"] = $val;
				break;
				
			}
			foreach($this->_PostGet as $nom => $val)
			switch($nom) {
				// Page en cours
				case "page" :
					$_SESSION[TMPDATA]["ACTIONS"]["page"] = $val;
				break;
				// pas d'affichage de page (pour Ajax)
				case "nopage" :
					$_SESSION[TMPDATA]["ACTIONS"]["nopage"] = $val;
				break;
				// Statistiques on/off
				case "NOSTAT" :
					if($val == "true") $v = false; else $v = true;
					$_SESSION[TMPDATA]["STAT"] = $v;
				break;
				// change de plateforme utilisateur
				case "chgplateforme" :
					$_SESSION[TMPDATA]["ACTIONS"]["chgplateforme"] = $val;
				break;
			}
		} else $this->_Exist = false;
	}

	########################################################################################################################
	### METHODE MAGIQUES #################################################################################################
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