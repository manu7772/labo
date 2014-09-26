<?php
###########################################
### VERSION	1.00						###
### NOM		AePages 					###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AePages						         */
/* ************************************* */
CLASS AePages {

	private $_Site;			// Objet data_site
	private $_SitePtfm;		// Site selon plateforme active
	//Noms de fichiers
	private $_ExtTypes = array("CSS","JS");	// Types de fichiers externes
	private $_FileCSS = "css.xml";			// Fichier XML pour CSS
	private $_FileJS = "js.xml";			// Fichier XML pour JS
	private $_AutorisationFiles = 0777;		// Valeur CHMOD

	function __construct() {
		AeGlobalDev::notice("Création des pages et menus");
		// Récupération plateforme client si existante
		if($_SESSION[SSDATA]['USER']['membre_prefs']["plateforme"]) $_SESSION[SSDATA]["Plateforme"] = $_SESSION[SSDATA]['USER']['membre_prefs']["plateforme"];
		// Sélection du site selon plateforme
		$this->_Site = AeQuickXML::loadSXD(XMLPLAN);
		AeGlobalDev::notice("/SITE/plateforme[@screen='".$_SESSION[SSDATA]["Plateforme"]."']");
		$this->_SitePtfm = $this->_Site->xpath("/SITE/plateforme[@screen='".$_SESSION[SSDATA]["Plateforme"]."']");
		if(sizeof($this->_SitePtfm) < 1) $this->_SitePtfm = $this->_Site->xpath("/SITE/plateforme[@defaut='defaut']");
		// Chargement des pages
		$this->_SSLoad_Pages();
		// Chargement des menus
		$this->_SSLoad_Menus();
		// Chargement des données nécessaires à la page actuelle
		$this->_SSLoad_Data();
		// Charge les fichiers externes (CSS, JS)
		$this->_SSLoad_Ext();
	}

	### Chargement des fichiers externes (CSS/JS/...)
	### en fonctions de l'attribut "plateforme"
	private function _SSLoad_Ext() {
		foreach($this->_ExtTypes as $ext) {
			$n = "_File".$ext;
			$nomf = DATAXML.$this->$n;
			$fx = AeQuickXML::loadSXD($nomf, "/".$ext."/fichier[@actif='on']");
			$_SESSION[TMPDATA]["ext"][$ext] = array();
			foreach($fx as $file) {
				$att = $file->attributes();
				$ptf = AeTools::xmlstr($att["plateforme"]);
				if($att["option"]) $opt = AeTools::xmlstr($att["option"]); else $opt = "ok";
				if(($ptf == $_SESSION[SSDATA]["Plateforme"]) || ($ptf == "all")) {
					if(in_array($opt, $_SESSION[TMPDATA]["pageactuelle"]["extJSCSS"]) || ($opt == "ok")) {
						$_SESSION[TMPDATA]["ext"][$ext][] = DATA.$ext."/".AeTools::xmlstr($file);
					}
				}
			}
		}
	}

	### Chargement des noms de pages du site selon :
	### 1. autorisations d'accès
	private function _SSLoad_Pages() {
		// PAGES
		AeGlobalDev::notice("Chargement de la liste des pages disponibles");
		foreach($this->_SitePtfm[0]->pages->children() as $nom => $page) {
			$att = $page->attributes();
			$IDpage = AeTools::xmlstr($att["id"]);
			$acces = AeTools::xmlstr($page->acces);
			// AeTools::dumpdata($_SESSION["USER"]);
			// AeGlobalDev::notice("Page : ".strval(bindec($acces))." / User : ".strval(bindec($_SESSION[SSDATA]["USER"]["membre_accessite"]))." / Résultat : ".strval(bindec($acces) & bindec($_SESSION[SSDATA]["USER"]["membre_accessite"]))."<br />");
			if((bindec($acces) & bindec($_SESSION[SSDATA]["USER"]["membre_accessite"])) != 0) $_SESSION[SSDATA]["pages"]["liste"][$IDpage] = $acces;
			// Attribution des pages par défaut
			if($att["defaut"]) $_SESSION[SSDATA]["pages"][AeTools::xmlstr($att["defaut"])] = $IDpage;
		}
		// Fichier simple .htpl
		if(strtolower(substr($_SESSION[TMPDATA]["ACTIONS"]["page"], -5)) == ".htpl") {
			$_SESSION[TMPDATA]["pageactuelle"]["template"] = $_SESSION[TMPDATA]["ACTIONS"]["page"];
		} else {
		// PAGE ACTUELLE
		// page par défaut si aucune page renseignée
			if(!$_SESSION[TMPDATA]["ACTIONS"]["page"]) {
				AeGlobalDev::notice("Pas de page demandée... création de la page par défaut : ".$_SESSION[SSDATA]["pages"]["defaut"]);
				$_SESSION[TMPDATA]["ACTIONS"]["page"] = $_SESSION[SSDATA]["pages"]["defaut"];
			} else {
				AeGlobalDev::notice("Une page spécifique a été demandée : ".$_SESSION[TMPDATA]["ACTIONS"]["page"]);
			}
			$_SESSION[TMPDATA]["pageactuelle"] = $this->_InfosPage($_SESSION[TMPDATA]["ACTIONS"]["page"]);
			if($_SESSION[TMPDATA]["pageactuelle"] == null) $_SESSION[TMPDATA]["pageactuelle"] = $this->_InfosPage($_SESSION[SSDATA]["pages"]["erreur"]);
		}
	}

	### Chargement des menus de la page selon :
	### 1. autorisations d'accès
	private function _SSLoad_Menus() {
		// MENUS
		AeGlobalDev::notice("Chargement des menus de la page<br />");
		$listemenus = $this->_ListeMenus();
		foreach($listemenus as $nom) $_SESSION[TMPDATA]["menus"][$nom] = $this->_ItemsMenu($nom);
		//AeTools::dumpdata($_SESSION[TMPDATA]["menus"], "Menus de la page", "auto");
	}

	### Renvoie la liste des menus du site (selon Plateforme) dans un tableau (array)
	### $Plateforme = nom de la plateforme / Si $Plateforme = null, utilise la plateforme par défaut
	private function _ListeMenus($Plateforme = null) {
		$r = array();
		if(is_string($Plateforme)) {
			$ptf = $this->_Site->xpath("/SITE/plateforme[@screen='".$Plateforme."']");
		} else {
			$ptf = $this->_SitePtfm;
		}
		foreach($ptf[0]->menus->children() as $menu) {
			$att = $menu->attributes();
			$r[] = AeTools::xmlstr($att["id"]);
		}
		return $r;
	}

	### Construction du menu $nm ($nm = [string] nom du menu)
	### $auth = true pour charger l'item même si non autorisé / Sinon false par défaut
	### !!! il faut que la liste des pages soit chargée
	private function _ItemsMenu($nm, $auth = false) {
		$menu = $this->_Site->xpath("/SITE/plateforme[@screen='".$_SESSION[SSDATA]["Plateforme"]."']/menus/menu[@id='".$nm."']");
		//AeTools::dumpdata($menu[0]);
		if(sizeof($menu) > 0) $LSmenu = $this->_RecursItemsMenu($menu[0]);
			else $LSmenu = null;
		return $LSmenu;
	}
	private function _RecursItemsMenu($menu) {
		$LSitem = array();
		$i = 0;
		foreach($menu as $nom => $ssmenu) {
			//AeTools::dumpdata($ssmenu);
			$LSitem[$i]["nom"] = AeTools::xmlstr($ssmenu);
			$att = $ssmenu->attributes();
			$idpage = AeTools::xmlstr($att["page"]);
			foreach($att as $nomatt => $valatt) {
				$vt = AeTools::xmlstr($valatt);
				if($vt[0] == "@") {
					$rch = substr($valatt, 1);//echo("Recherche : ".$rch." ( "."pages/page[@id='".$idpage."']/".$rch." )<br />");
					$look = $this->_SitePtfm[0]->xpath("pages/page[@id='".$idpage."']/".$rch);
					$vt = AeTools::xmlstr($look[0]);
				}
				$LSitem[$i][$nomatt] = $vt;
			}
			if($ssmenu->count() > 0) $LSitem[$i]["items"] = $this->_RecursItemsMenu($ssmenu);
			$i++;
			//
		}
		return $LSitem;
	}

	### Renvoie les informations à propos d'une page
	### $nom = nom de la page
	### $restriction = true pour vérifier si dans la liste des pages autorisées / sinon false
	### Renvoie null si page non trouvée ou non autorisée
	private function _InfosPage($nom, $restriction = true) {
		$child = array(
			"options",
			"Pmenus",
			"SQL",
			"tables",
			"zoneXML",
			"extJSCSS"
		);
		$test = $this->_SitePtfm[0]->xpath("pages/page[@id='".$nom."']");
		if(sizeof($test) > 0) {
			if(($restriction == false) || (array_key_exists($nom, $_SESSION[SSDATA]["pages"]["liste"]))) {
				$att = $test[0]->attributes();
				foreach($att as $nom => $val) $r[$nom] = AeTools::xmlstr($val);
				foreach($test[0] as $nom => $val) {
					if(in_array($nom, $child)) {
						$r[$nom] = array();
						$cpt = 0;
						foreach($val->children() as $nom2 => $val2) {
							$att2 = $val2->attributes();
							if($att2["nom"]) $cc = AeTools::xmlstr($att2["nom"]); else $cc = $cpt;
							if(sizeof($att2) > 0) {
								$r[$nom][$cc]["val"] = AeTools::xmlstr($val2);
								foreach($att2 as $nomatt2 => $valatt2) $r[$nom][$cc][$nomatt2] = AeTools::xmlstr($valatt2);
							} else $r[$nom][$cc] = AeTools::xmlstr($val2);
							$cpt++;
						}
					} else $r[$nom] = AeTools::xmlstr($val);
					// if($nom == "plateforme") {
					// 	$screen = explode(":", AeTools::xmlstr($val));
					// 	$r[$nom] = $screen[1];
					// }
				}
			} else $r = null; // Non autorisée
		} else $r = null; // Non trouvée
		return $r;
	}

	### Charge les données relatives à la page actuelle
	### Données SQL
	### Données XML...
	private function _SSLoad_Data() {
		if(is_array($_SESSION[TMPDATA]['pageactuelle']['zoneXML'])) {
			foreach($_SESSION[TMPDATA]['pageactuelle']['zoneXML'] as $nom => $xml) {
				$_SESSION[TMPDATA]['XML'][$nom] = AeQuickXML::loadSXD(DATAXML.$xml['val'], $xml['motif']);
			}
		}
		if(is_array($_SESSION[TMPDATA]['pageactuelle']['SQL'])) {
			$bdd = new AeSmartBDD();
			foreach($_SESSION[TMPDATA]['pageactuelle']['SQL'] as $val) {
				$_SESSION[TMPDATA]['SQL'][$val['nom']] = $bdd->request($val["val"]);
			}
		}
	}
}
?>