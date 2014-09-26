<?php
###########################################
### VERSION	1.00						###
### NOM		AeSessions 					###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeSessions					         */
/* ************************************* */
CLASS AeSessions {
	private $_SessionName = 'AequaRENDER2';		// Nom de la session
	private $_Plateforme;						// Type de plateforme utilisateur (mobile / PC...)
	private $_Base;								// Objet brut simpleXML du fichier xmlbase.xml -> pour hydratation (heu... artificielle)
	private $_Site;								// Objet brut simpleXML du fichier data_site.xml
	private $_AeUser;							// Données utilisateur
	private $_AeRequetes;						// Merge de POST et GET

	//Liste des variables accessibles en lecture seule
	private $_AutorizeVAR = array(
		"PlateformeEnCours",
		"SessionName",
		"AutorizeVAR",
		"AutorizeFUNCTION");
	//Liste des fonctions accessibles comme variables
	private $_AutorizeFUNCTION = array();
	//Noms de fichiers
	private $_ExtTypes = array("CSS","JS");	// Types de fichiers externes
	private $_FileCSS = "css.xml";			// Fichier XML pour CSS
	private $_FileJS = "js.xml";			// Fichier XML pour JS
	private $_AutorisationFiles = 0777;		// Valeur CHMOD

	private $_DefautTemplatePage; 			// Templage page public par défaut
	private $_ErreurTemplatePage; 			// Templage page public par défaut


	function __construct() {

		$this->_Base = AeQuickXML::loadSXD(XMLBASE);
		$this->_Site = AeQuickXML::loadSXD(XMLPLAN);
		$SN = AeTools::xmlstr($this->_Base->session->name);
		if(is_string($SN) && (strlen($SN) > 3)) $this->_SessionName = $SN;
		session_name($this->_SessionName);
		session_start();
		// Réinitialisation et Logout
		if(isset($_GET["init"])) if($_GET["init"] == "init") $this->Ferme();
		if($_POST["LOGOUT"]) $this->Ferme();
		$this->_OuvreSession();
	}

	function __destruct() {
		// $_SESSION[SSDATA] = serialize($_SESSION[SSDATA]);
	}

	### Ouverture de session et récupération des données $_SESSION
	private function _OuvreSession($ID = null) {
		// Efface les données temporaires (ne conserve que SSDATA)
		if (is_array($_SESSION)) foreach($_SESSION as $nom => $contain) if($nom != SSDATA) unset($_SESSION[$nom]);
		// Initialisations
		AeGlobalDev::init();
		AeMessages::init();
		// désérialise les données $_SESSION
		if (is_string($_SESSION[SSDATA])) {
			$_SESSION[SSDATA] = unserialize($_SESSION[SSDATA]);
		} else if (!is_array($_SESSION[SSDATA])) {
		// Sinon crée les données
			AeGlobalDev::notice("Initialisation ou première connexion au site... Création des données");
			$this->_HydrateDATA();
		}
		// Rassemble les requêtes dans un objet (merge de $_Post et $_Get)
		// $_AeRequetes = false si aucune requête
		AeGlobalDev::notice("Tri des requêtes");
		$this->_AeRequetes = new AeRequetes();
		// Crée/restitue l'utilisateur
		AeGlobalDev::notice("Création utilisateur");
		$this->_AeUser = new AeUser($_SESSION[SSDATA]['USER']['membre_ID']);
		// lance les actions selon requêtes
		AeGlobalDev::notice("Traitement/exécution des requêtes");
		$this->_Actions();
	}


	### Lance les actions selon requêtes
	private function _Actions() {
		if(is_array($_SESSION[TMPDATA]["ACTIONS"]) && sizeof($_SESSION[TMPDATA]["ACTIONS"]) > 0) {
			foreach($_SESSION[TMPDATA]["ACTIONS"] as $nom => $action) {
				switch($nom) {
					// Login
					case "LOGIN" :
						$this->_AeUser->connect($_SESSION[TMPDATA]["ACTIONS"]["LOGIN"]["user"], $_SESSION[TMPDATA]["ACTIONS"]["LOGIN"]["clef"]);
					break;
					// Pas d'affichage (AJAX)
					case "nopage" :
						$_SESSION[TMPDATA]["nopage"] = true;
					break;
					// Change de plateforme
					case "chgplateforme" :
						// $_SESSION[TMPDATA]["chgplateforme"] = true;
						$this->_ChgPlateforme();
					break;
					// Tri des domaines
					case "ajaxcall" :
						if($_SESSION[TMPDATA]["ACTIONS"]["callfonction"]) {
							$f = new FPtrisbdd();
							$funct = $_SESSION[TMPDATA]["ACTIONS"]["callfonction"];
							$f->$funct($_POST);
						}
					break;
				}
			}
		}
	}

	### Fermeture de session et effacement des données $_SESSION
	### $reload = réouvre une nouvelle session ensuite
	### $ID = si $reload = true, ouvre une nouvelle session avec l'utilisateur $ID
	public function Ferme($reload = false, $ID = null) {
		$_SESSION = array();
		unset($_SESSION);
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();
		session_name($this->_SessionName);
		session_start();
		if($reload == true && $ID != null) $this->_OuvreSession($ID);
	}

	### Récupère toutes les données de session
	private function _HydrateDATA() {
		// Détection du support (PC / mobile)
		$this->_Plateforme = $this->_GetPlateforme();
		// Chargement des données de site : pages / menus
		$_SESSION[SSDATA]["session_name"] = $this->_SessionName;
		$this->_URLfind();
		$this->_SSLoad_Acces();
		$this->_SSLoad_Twigpath();
		$this->_SSLoad_Twigparam();
		$this->_SSLoad_Twigdoss();
	}

	private function _GetPlateforme() {
		if (stristr($_SERVER['HTTP_USER_AGENT'], "Android") || strpos($_SERVER['HTTP_USER_AGENT'], "iPod") || strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") ) {
			$_SESSION[SSDATA]["Plateforme"] = "little";
		} else {
			$_SESSION[SSDATA]["Plateforme"] = "wide"; //wide
		}
		return $_SESSION[SSDATA]["Plateforme"];
	}

	private function _ChgPlateforme() {
		// Change de plateforme dans les données sauvegardées...
		if($_SESSION[SSDATA]["Plateforme"] == "wide") $_SESSION[SSDATA]["Plateforme"] = "little"; else $_SESSION[SSDATA]["Plateforme"] = "wide";
		if($_SESSION[SSDATA]["USER"]["membre_ID"]) {
			echo("Changement sur USER...");
			// Si l'USER n'a pas de préférences, attribue la plateforme actuelle
			if(!$_SESSION[SSDATA]["USER"]["membre_prefs"]["plateforme"]) $_SESSION[SSDATA]["USER"]["membre_prefs"]["plateforme"] = $_SESSION[SSDATA]["Plateforme"];
			$bdd = new AeSmartBDD();
			$bdd->saveUSERprefs(array(
				"Prefs_plateforme" => $_SESSION[SSDATA]["Plateforme"],
				"USER" => $_SESSION[SSDATA]["USER"]["membre_ID"])
			);
			// $bdd->SQLcmd("UPDATE", "aequa_membre", array("membre_prefs" => $_SESSION[SSDATA]["Plateforme"]));
		}
	}



	#####################################################################
	### Modules d'hydratation
	#####################################################################

	### Charge les paramètres d'URL
	private function _URLfind() {
		$data = AeQuickXML::LoadSXD(XMLBDD, '/BDD/serveur[@nom="'.$_SERVER["SERVER_NAME"].'"]');
		if(is_array($data)) $data = $data[0];
		if(is_object($data)) {
			$_SESSION[SSDATA]["URL"] = AeTools::xmlstr($data->URL);
		}		
	}

	### Niveaux d'accès et niveau par défaut
	private function _SSLoad_Acces() {
		$acces = $this->_Base->xpath("/xmldata/acces/user");
		foreach($acces as $nom => $val) {
			$att = $val->attributes();
			$_SESSION[SSDATA]["acces"]["nom"][AeTools::xmlstr($att["name"])] = AeTools::xmlstr($val[0]);
			$_SESSION[SSDATA]["acces"]["bit"][AeTools::xmlstr($val[0])] = AeTools::xmlstr($att["name"]);
			if($att["defaut"] == "defaut") {
				$_SESSION[SSDATA]["acces"]["defaut"]["nom"] = AeTools::xmlstr($att["name"]);
				$_SESSION[SSDATA]["acces"]["defaut"]["bit"] = AeTools::xmlstr($val[0]);
			}
		}
		$admin = $this->_Base->xpath("/xmldata/acces/admin");
		foreach($admin as $nom => $val) {
			$att = $val->attributes();
			$_SESSION[SSDATA]["acces"]["admin"]["nom"] = AeTools::xmlstr($att["name"]);
			$_SESSION[SSDATA]["acces"]["admin"]["bit"] = AeTools::xmlstr($val[0]);
			break(1);
		}
	}

	### Path pour Twig
	private function _SSLoad_Twigpath() {
		$_SESSION[SSDATA]['twigparam']["twigpath"] = AeTools::xmlstr($this->_Base->twigparam->twigpath);
	}

	### Dossiers de templates Twig
	private function _SSLoad_Twigdoss() {
		$_SESSION[SSDATA]['twigparam']["dossiers"] = array();
		foreach($this->_Base->twigparam->children() as $nom => $val) {
			if(AeTools::xmlstr($nom) == "dosstwig") $_SESSION[SSDATA]['twigparam']["dossiers"][] = AeTools::xmlstr($val);
		}
	}

	### Paramètres Twig
	private function _SSLoad_Twigparam() {
		$cache = $this->_Base->twigparam->cache;
		if(strtolower($cache == "false")) $_SESSION[SSDATA]['twigparam']["dossiers"]["cache"] = false;
			else $_SESSION[SSDATA]['twigparam']["options"]["cache"] = true;
		$_SESSION[SSDATA]['twigparam']["options"]["charset"] = AeTools::xmlstr($this->_Base->general->charset);
		$_SESSION[SSDATA]['twigparam']["options"]["language"] = AeTools::xmlstr($this->_Base->general->language);
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

	public function __toString() {
		return $this->_SessionName;
	}
	
}
?>