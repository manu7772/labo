<?php
// src/AcmeGroup/services/aetools/aetools.php

namespace AcmeGroup\services\aetools;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AcmeGroup\services\aetools\aeReponse;

class aetools {
	protected $currentAeReponse = null;	// Réponse de la dernière opération
	protected $currentPath;
	protected $slash;
	protected $rootPath;
	protected $recursiveTree;
	protected $router;
	protected $allRoutes = array();
	protected $nofiles = array('.', '..', '.DS_Store');
	protected $liste;
	protected $service = array();
	protected $controllerPath;		// chemin complet du controller
	protected $groupeName;			// nom du groupe
	protected $bundleName;			// nom du bundle
	protected $ctrlFolder;			// dossier du controller
	protected $controllerName;		// nom du controller
	protected $methodeName;			// nom de la méthode appelée
	protected $singleMethodeName;	// nom de la méthode appelée, sans "Action"

	protected $modeFixtures = false; // true pour mode fixtures actif

	protected $listP = array("groupeName", "bundleName", "ctrlFolder", "controllerName");

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->serviceSess = $this->container->get('request')->getSession();
		$this->router = $this->container->get('router');
		$this->setWebPath();

		$win=false; // mettre à "true" si serveur windows !!!
		switch($win) {
			case true: $this->slash = '\\'; break;
			default: $this->slash = '/';
		}
		// définition automatique du mode fixtures
		$this->controllerPath = $this->container->get("request")->attributes->get('_controller');
		if($this->controllerPath === null) {
			$this->modeFixtures = true;
		} else {
			$this->modeFixtures = false;
			$d = explode("::", $this->controllerPath."");
			if(count($d) < 2)
				$d = explode(":", $this->controllerPath."");
			$this->methodeName = $d[1];
			$this->singleMethodeName = str_replace("Action", "", $d[1]);
			$e = explode("\\", $d[0]);
			if(count($e) < 2) $e = explode(".", $d[0]);
			foreach($e as $idx => $nom) {
				if($idx < (count($this->listP) + 1)) {
					if(isset($this->listP[$idx])) $nP = $this->listP[$idx];
					$this->$nP = $nom;
				}
			}
		}
		return $this;
	}

	/**
	* getAereponse
	* Renvoie la réponse de la dernière opération effectuée
	* @return aeReponse
	*/
	public function getAereponse() {
		return $this->currentAeReponse;
	}

	private function setAeReponse($result, $data = null, $message = "") {
		$this->currentAeReponse = new aeReponse($result, $data, $message);
	}

	public function getModeFixtures() {
		return $this->modeFixtures;
	}

	public function setWebPath($path = "") {
		$rootPath = __DIR__.'/../../../../web/'.$path;
		if(file_exists($rootPath)) {
			$this->close();
			$this->rootPath = $rootPath;
			$this->currentPath = $rootPath;
			$this->recursiveTree = array(dir($this->rootPath));
			$this->rewind();
			return $this;
		} else return false;
	}

	public function setRootPath($path = "") {
		$rootPath = __DIR__.'/../../../../'.$path;
		if(file_exists($rootPath)) {
			$this->close();
			$this->rootPath = $rootPath;
			$this->currentPath = $rootPath;
			$this->recursiveTree = array(dir($this->rootPath));
			$this->rewind();
			return $this;
		} else return false;
	}	

	public function __destruct() {
		$this->close();
	}

	/**
	 * serviceEventInit
	 * Initialise le service - attention : cette méthode est appelée en requête principale par EventListener !!!
	 * 
	 * @param FilterControllerEvent $event
	 * @param boolean $reLoad
	 */
	public function serviceEventInit(FilterControllerEvent $event, $reLoad = false) {
		// paramètres URL et route
		$this->service['actuelpath'] = $this->getURL();
		$this->service['baseURL'] = $this->getBaseUrl();
		$this->service['URL'] = $this->getURLentier();
		$this->service['route'] = $this->getRoute();
		$this->service['parameters'] = $this->getParameters();
		$this->service['controller'] = $this->getController();
		$this->service['methodeName'] = $this->getMethodeName();
		$this->service['groupeName'] = $this->getGroupeName();
		$this->service['bundleName'] = $this->getBundleName();
		$this->service['controllerName'] = $this->getControllerName();
		$this->service['environnement'] = $this->getEnv();
		$this->service['clientIP'] = $this->getIP();
		$this->siteListener_InSession();
	}
	/**
	* siteListener_InSession
	* dépose les informations de l'entité dans la session
	*
	*/
	public function siteListener_InSession() {
		$this->serviceSess->set("aetools", $this->service);
		return $this;
	}



	private function close() {
		if(is_array($this->recursiveTree)) while(true === ($d = array_pop($this->recursiveTree))) {
			$d->close();
		}
	}

	private function closeChildren() {
		while(count($this->recursiveTree)>1 && false !== ($d = array_pop($this->recursiveTree))) {
			$d->close();
			return true;
		}
		return false;
	}

	/**
	 * getRootPath
	 * Renvoie le dossier racine
	 * @return string
	 */
	public function getRootPath() {
		if(isset($this->rootPath)) {
			return $this->rootPath;
		}
		return false;
	}

	/**
	 * getCurrentPath
	 * Renvoie le dossier courant
	 * @return string
	 */
	public function getCurrentPath() {
		if(isset($this->currentPath)) {
			return $this->currentPath;
		}
		return false;
	}
	
	/**
	 * read
	 * Recherche un fichier $type dans le dossier courant ou ses enfants
	 * !!! insensible à la casse par défaut
	 * renvoie un tableau : 
	 * 		["path"]	= chemin
	 * 		["nom"]		= nom du fichier
	 * 		["full"]	= chemin + nom
	 * @param string $type (expression régulière)
	 * @return array
	 */
	public function read($type = null, $casseSensitive = false) {
		if($casseSensitive === false) $sens = "i"; else $sens = "";
		while(count($this->recursiveTree)>0) {
			$d = end($this->recursiveTree);
			if((false !== ($entry = $d->read()))) {
				if(!in_array($entry, $this->nofiles)) {
					$path["path"] = $d->path;
					$path["nom"]  = $entry;
					$path["full"] = $d->path.$entry;
					
					if(is_file($d->path.$entry)) {
						if($type !== null) $r=preg_match("/".$type."/".$sens, $entry); else $r = true;
						if($r == true || $r == 1) return $path;
					}
					elseif(is_dir($d->path.$entry.$this->slash)) {
						$this->currentPath = $d->path.$entry.$this->slash;
						if($child = @dir($d->path.$entry.$this->slash)) {
							$this->recursiveTree[] = $child;
						}
					}
				}
			} else {
				array_pop($this->recursiveTree)->close();
			}
		}
		return false;
	}

	/**
	 * readAll
	 * renvoie la liste de tous les fichiers contenus dans le dossier et ses enfants
	 * !!! insensible à la casse par défaut
	 * renvoie un tableau : 
	 * 		["path"]	= chemin
	 * 		["nom"]		= nom du fichier
	 * 		["full"]	= chemin + nom
	 * @return array
	 */
	public function readAll($type = null, $path = null, $casseSensitive = true) {
		if(null !== $path) $this->setWebPath($path);
			else $this->setWebPath($this->rootPath); // réinitialise
		$this->liste = array();
		// echo "<span style='color:white;'> Path : ".$this->getRootPath()."</span><br /><br />";
		while (false !== ($entry = $this->read($type, $casseSensitive))) {
			// echo $entry["path"]."<span style='color:pink;'>".$entry["nom"]."</span><br />";
			$this->liste[] = $entry;
		}
		$this->close();
		return $this->liste;
	}

	private function rewind() {
		$this->closeChildren();
		$this->rewindCurrent();
	}

	private function rewindCurrent() {
		return end($this->recursiveTree)->rewind();
	}

	/**
	 * deleteFileEverywhere
	 * retrouve et efface les fichiers $file dans le dossier courant et tous les dossiers enfants
	 *
	 * @param array $files (peut être des expressions régulières => voir la méthode "read()")
	 */
	public function deleteFilesEverywhere($files) {
		$r = array();
		if(is_string($files)) { $f = $files; $files = array(); $files[0] = $f; }
		foreach($files as $file) {
			$search = $this->readAll($file, null, false);
			if(count($search) > 0) foreach($search as $erase) {
				$t = $this->deleteFile($erase["full"]);
				if($t !== false) $r[] = $t;
			}
		}
		return $r;
	}

	/**
	 * deleteFile
	 * Efface le fichier $file s’il est dans le dossier courant (ou préciser le chemin !)
	 *
	 * @param $file
	 */
	public function deleteFile($file) {
		if(is_string($file)) {
			$r = array();
			if(file_exists($file)) {
				if(@unlink($file)) $r = $file;
					else $r = false;
			} else $r = false;
		} else $r = false;
		return $r;
	}

	/**
	 * deleteFiles
	 * Efface tous les fichiers contenus dans le tableau $files, depuis le dossier courant (ou préciser les chemins !)
	 *
	 * @param array $files
	 */
	public function deleteFiles($files) {
		$r = array();
		if(is_string($files)) { $f = $files; $files = array(); $files[0] = $f; }
		$err = 0;
		foreach($files as $file) {
			$res = $this->deleteFile($file);
			if($res === false) $err++; else $r[] = $res;
		}
		if($err > 0) $r = false;
		return $r;
	}

	/**
	 * deletedir
	 * efface le dossier $dir (préciser le chemin !)
	 *
	 * @param array $files
	 */
	public function deletedir($dir) {
		$r = false;
		if((file_exists($dir)) && (is_dir($dir))) {
			if(@rmdir($dir)) $r = true;
				else $r = false;
		} else $r = false;
		return $r;
	}

	/**
	 * findAndDeleteFiles
	 * Recherche et efface tous les fichiers contenus dans $files
	 * (préciser le chemin de départ ou utilise la valeur de $rootPath)
	 *
	 * @param array/string $files
	 */
	public function findAndDeleteFiles($files, $path = null) {
		$r = array();
		if(null !== $path) $this->setWebPath($path);
			else $this->setWebPath($this->rootPath); // réinitialise
		if(is_string($files)) $files = array($files);
		$err = 0;
		foreach($files as $file) {
			// $this->readAll("^".$file."$");
			$this->readAll($file);
			if(count($this->liste) > 0) foreach($this->liste as $fichier) {
				$res = $this->deleteFile($fichier['full']);
				if($res === false) $err++; else $r[] = $res;
			}
		}
		if($err > 0) $r = false;
		return $r;
	}

	/**
	 * verifDossierAndCreate
	 * Crée un dossier s'il n'existe pas
	 * 
	 * @param $dossier
	 * @param $chmode = 0755
	 * @return boolean / string
	 */
	public function verifDossierAndCreate($dossier, $chmode = 0755) {
		$r = false;
		$doss = $this->getCurrentPath().$dossier;
		if(!file_exists($doss)) {
			if(!is_dir($doss)) {
				if (mkdir($doss, $chmode, true)) {
    				$r = $dossier;
				}
			}
		}
		return $r;
	}

	/**
	* changeExt
	* Change l'extension du nom d'un fichier
	* Si l'extension n'est pas valide, retourne le nom du fichier $nom
	* 
	* @param string $ext (sans le point "." !!!)
	* @param string $nom
	* @return string (nom du fichier avec la nouvelle extension)
	*/
	public function changeExt($ext, $nom) {
		if(preg_match('`([[:alpha:]]{1})([[:alnum:]]{2,3})$`', $ext)) {
			$memnom = $nom;
			$nom = preg_replace('`\.([[:alnum:]]+)$`' , ".".$ext, $nom);
			if($memnom !== $nom) $this->setAeReponse(true, $nom, "Extension changée : ".$nom);
				else $this->setAeReponse(false, $nom, "L'extension n'a pu être modifiée. Nom de fichier non conforme.");
		} else {
			$this->setAeReponse(false, $nom, "Extension non valide");
		}
		return $nom;
	}



	/**
	 * getAllRoutes
	 * Renvoie un array des routes contenant le préfixe $prefix
	 * @param $prefix
	 * @return array
	 */
	public function getAllRoutes($prefix = null) {
		if(is_string($prefix)) $pattern = '/^'.$prefix.'/'; // commence par $prefix
			else $pattern = '/.*/';
		$this->allRoutes = array();
		foreach($this->router->getRouteCollection()->all() as $nom => $route) {
			if(preg_match($pattern, $nom)) $this->allRoutes[] = $nom;
		}
		return $this->allRoutes;
	}

	/**
	 * getIP
	 * Renvoie l'adresse IP utilisateur
	 *
	 */
	public function getIP() {
		return $this->container->get('request')->getClientIp();
	}

	/**
	 * getPathInfo
	 * Renvoie la route actuelle
	 *
	 */
	public function getRoute() {
		return $this->container->get("request")->attributes->get('_route');
	}

	/**
	 * getController
	 * Renvoie le controller (string)
	 *
	 */
	public function getController() {
		if($this->modeFixtures === false)
			return $this->controllerPath;
			else return null;
	}

	/**
	 * getGroupeName
	 * Renvoie le nom du groupeName
	 *
	 */
	public function getGroupeName() {
		if($this->modeFixtures === false)
			return $this->groupeName;
			else return null;
	}

	/**
	 * getCtrlFolder
	 * Renvoie le dossier du controller
	 *
	 */
	public function getCtrlFolder() {
		if($this->modeFixtures === false)
			return $this->ctrlFolder;
			else return null;
	}

	/**
	 * getControllerName
	 * Renvoie le nom du controller
	 *
	 */
	public function getControllerName() {
		if($this->modeFixtures === false)
			return $this->controllerName;
			else return null;
	}

	/**
	 * getEnv
	 * Renvoie mode d'environnement (dev, test, prod…)
	 *
	 */
	public function getEnv() {
		if($this->modeFixtures === false)
			return $this->container->get('kernel')->getEnvironment();
			else return null;
	}

	/**
	 * getBundleName
	 * Renvoie le nom du bundle
	 *
	 */
	public function getBundleName() {
		if($this->modeFixtures === false)
			return $this->bundleName;
			else return null;
	}

	/**
	 * getMethodeName
	 * Renvoie le nom de la méthode appelée dans le controller
	 *
	 */
	public function getMethodeName() {
		if($this->modeFixtures === false)
			return $this->methodeName;
			else return null;
	}

	/**
	 * getSingleMethodeName
	 * Renvoie le nom de la méthode, sans "Action" appelée dans le controller
	 *
	 */
	public function getSingleMethodeName() {
		if($this->modeFixtures === false)
			return $this->singleMethodeName;
			else return null;
	}

	/**
	 * getBaseUrl
	 * Renvoie l'url de base (string)
	 *
	 */
	public function getBaseUrl() {
		if($this->modeFixtures === false)
			return $this->container->get("request")->getBaseUrl();
			else return null;
	}

	/**
	 * getURL
	 * Renvoie le path (string)
	 *
	 */
	public function getURL() {
		if($this->modeFixtures === false)
			return $this->container->get("request")->getPathInfo();
			else return null;
	}

	/**
	 * getURLentier
	 * Renvoie l'URL entier (string)
	 *
	 */
	public function getURLentier() {
		if($this->modeFixtures === false)
			return $this->container->get("request")->getUri();
			else return null;
	}

	/**
	 * getParameters
	 * Renvoie un array des paramètres de route
	 * @return array ou null si aucun paramètre
	 */
	public function getParameters() {
		if($this->modeFixtures === false) {
			$r = array();
			$params = explode("/", $this->getURL());
			foreach($params as $nom => $pr) if(strlen($pr) > 0) $r[$nom] = $pr;
			// return $this->container->get("request")->attributes->all();
			if(count($r) == 0) $r = null;
			return $r;
		} else return null;
	}

}
?>
