<?php
// src/AcmeGroup/services/entitiesServices/directeditor.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class directeditor extends \Twig_Extension {

	protected $serviceNom = "directeditor"; // nom du service
	protected $container;
	protected $serviceSess;
	protected $directeditor;
	protected $ctrl;			// Controller du FilterControllerEvent

	public function __construct(ContainerInterface $container) {
		// parent::__construct($container);
		$this->container	= $container;
		$this->router 		= $this->container->get('router');
		// $this->aetools 		= $this->container->get('acmeGroup.aetools');
		// $this->serviceSess = $this->container->get('request')->getSession();
	}

	public function getFunctions() {
		return array(
			'generateDirectEditor'		=> new \Twig_Function_Method($this, 'generateDirectEditor'),
			'convertDirecteditorText'	=> new \Twig_Function_Method($this, 'convertDirecteditorText'),
			);
	}


	public function getName() {
		return $this->serviceNom;
	}

	/**
	* serviceEventInit
	* Initialise le service - attention : cette méthode est appelée en requête principale par EventListener !!!
	* 
	* @param FilterControllerEvent $event
	* @param boolean $reLoad
	*/
	public function serviceEventInit(FilterControllerEvent $event, $reLoad = false) {
		$this->serviceSess = $event->getRequest()->getSession();
		// $this->autorize = $event->getController()->get('security.context')->isGranted('ROLE_EDITOR');
		// Vérifie si la directeditor existe : dans ce cas, on définit la valeur par défaut en session
		if($this->serviceSess->get($this->serviceNom) === null) {
			$this->DeDefStatut();
		}
		$serviceChange = $event->getRequest()->request->get($this->serviceNom."Define"); // POST en priorité
		if(null === $serviceChange) $serviceChange = $event->getRequest()->query->get($this->serviceNom."Define"); // GET
		if($serviceChange !== null) {
			// rechargement…
			$this->DeDefStatut($serviceChange);
		}
		return $this;
	}

	/**
	* DeDefStatut
	* Définit à on/off directeditor
	* 
	* @param string ("on"/"off")
	* @return string valeur appliquée ("on"/"off")
	*/
	private function DeDefStatut($statut = "off") {
		if(!in_array(strtolower($statut), array("on", "off"))) $statut = "off";
		$this->serviceSess->set($this->serviceNom, array("statut" => $statut));
		$this->directeditor = $this->serviceSess->get($this->serviceNom);
		return $statut;
	}

	/**
	* generateDirectEditor
	* renvoie le code pour l'affichage de la balise directeditor
	* 
	* @param string $html
	* @param string $champ
	* @param string $style
	* @param string $autresClasses
	* @param string $balise
	* @return string
	*/
	public function generateDirectEditor($entite, $champ = "texte", $style = "editable", $autresClasses = "", $balise = "div", $short = null) {
		if(!$this->container->get('security.context')->isGranted('ROLE_EDITOR')) {
			$this->DeDefStatut("off");
		}
		// si est un tableau
		if(is_array($autresClasses)) $autresClasses = implode(" ", $autresClasses);
		$chprix = array("prix", "prixHT");
		if(in_array($champ, $chprix)) $style = "editablePrix"; // --> champs prix : forcé à édition spéciale (simple !!)
		// test sur l'attribut éventuel directEditable == false
		if(method_exists($entite, "getDirectEditable")) {
			$directE = $entite->getDirectEditable();
		} else $directE = true;
		$methode = "get".ucfirst($champ);
		if(method_exists($entite, $methode)) {
			$a = $this->serviceSess->get($this->serviceNom);
			if(($a["statut"] == "on") && ($directE === true)) {
				// ATTENTION : la méthode "getNameSpace()" doit être précisée dans la classe de chaque entité utilisée ici !!!
				// data-prototype : 3 paths séparés par "___" :
				//		path de modif des données + "___" + path de récupération des données (raccourcies) + "___" + path de récupération des données
				$path = ' data-prototype="'
					.$this->router->generate('labo_entite_save', array("entiteNom" => $entite->getNameSpace(), "id" => $entite->getId(), "champ" => $champ)).'___'
					.$this->router->generate('labo_entite_htmlget', array("entiteNom" => $entite->getNameSpace(), "id" => $entite->getId(), "champ" => $champ, "short" => $short)).'___'
					.$this->router->generate('labo_entite_htmlget', array("entiteNom" => $entite->getNameSpace(), "id" => $entite->getId(), "champ" => $champ)).'"';
				$styleEditor = ' class="'.$style.' '.$autresClasses.'"';
			} else {
				$path = '';
				if(strlen($autresClasses) > 0) {
					$styleEditor = ' class="'.$autresClasses.'"';
				} else $styleEditor = "";
			}
			$texte = $entite->$methode();
			$texte .= "";
			$decimale = null;
			if(in_array($champ, $chprix)) $decimale = "prix";
			if($short !== null) {
				$aetext = $this->container->get('acmeGroup.textutilities');
				$texte = $aetext->phraseCut($texte, $short);
			}
			// if(in_array($champ, $chprix)) $texte = number_format($texte, 2, ",", ""); // --> format prix : numérique à 2 décimale et virgule (FR)
			$html = "<".$balise.$styleEditor.$path.">".$this->convertDirecteditorText($texte, $decimale)."</".$balise.">";
		} else {
			if(strlen($autresClasses) > 0) {
				$styleEditor = ' class="'.$autresClasses.'"';
			} else $styleEditor = '';
			$html = "<".$balise.$styleEditor.">???</".$balise.">";
		}
		return $html;
	}

	/**
	* afficheDEText
	* renvoie le code pour l'affichage du texte dans la balise directeditor
	* 
	* @param string $html
	* @param string $type : texte | prix
	* @return string
	*/
	public function convertDirecteditorText($html, $type = "texte") {
		// $base = str_replace("app_dev.php", "", $this->container->get("request")->getBaseUrl());
		// $html = str_replace('src="', 'src="'.$base, $html);
		return $html;
	}

}

?>
