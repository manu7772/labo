<?php
###########################################
### VERSION	1.05						###
### NOM		AeUser  					###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeUser	                             */
/* Profil utilisateur en cours           */
/* ************************************* */
CLASS AeUser {

	private $_BDD;			// Objet SQL liée à la BDD
	private $_ID = false;	// ID de l'utilisateur / si non connecté, renvoie false;
	private $_User = false;	// Objet user

	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"ID",
		"User",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();


	function __construct($ID=null) {
		$this->_BDD = new AeSmartBDD();
		if(($ID == null) || (!isset($ID))) {
			// echo("Récupération ID<br />");
			$this->_MemUser($_SESSION[SSDATA]['USER']['membre_ID']);
		}
		else {
			// echo("Utilisateur ".$ID." (".gettype($ID).")<br />");
			$this->load_user($ID);
		}
	}

	### charge les données utilisateur de l'$ID
	private function load_user($ID) {
		// $sql = "SELECT * FROM `aequa_membre` WHERE `membre_ID` = ".$ID." AND `membre_activation` = 'on' LIMIT 1;";
		$sql = "SELECT * FROM `aequa_membre` WHERE `membre_ID` = ".$ID." LIMIT 1;";
		$r = $this->_BDD->request($sql);
		$this->_MemUser($r[0]);
	}

	### Tente une connexion avec $login et mot de $pass
	### !!!!!! $pass doit être codé en MD5 !!!!!!
	### Si succès, charge les données de l'utilisateur
	public function connect($login, $pass) {
		$sql = "SELECT * FROM `aequa_membre` WHERE `membre_login` = '".$login."' AND `membre_passe` = '".$pass."' AND `membre_activation` = 'on' LIMIT 1;";
		$r = $this->_BDD->request($sql);
		$this->_MemUser($r[0]);
		if($_SESSION[SSDATA]['USER']['membre_ID'] != null) AeMessages::message("Bonjour ".$_SESSION[SSDATA]['USER']['membre_nom']." ! Vous êtes connecté sur votre compte");
			else AeMessages::alerte("Login ou mot de passe incorrect...");
		// return $this->_User;
	}

	### Déconnecte l'utilisateur
	public function unconnect() {
		$this->_MemUser(null);
		AeMessages::message("Vous êtes déconnecté...");
	}

	### Charge l'User en mémoire (ou efface User si $r ne contient de membre ID)
	### $r = données user (array)
	private function _MemUser($r) {
		if($r["membre_ID"]) {
			$this->_ID = $this->_User["membre_ID"];
			$this->_User = $_SESSION[SSDATA]['USER'] = $r;
			$_SESSION[SSDATA]["USER"]["membre_prefs"] = unserialize(urldecode($r["membre_prefs"]));
		} else {
			$this->_ID = false;
			$this->_User = false;
			$_SESSION[SSDATA]['USER'] = array();
			$_SESSION[SSDATA]['USER']['membre_accessite'] = $_SESSION[SSDATA]['acces']['defaut']['bit'];
			$_SESSION[SSDATA]['USER']['membre_ID'] = null;
		}
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
