<?php
// src/AcmeGroup/services/entitiesServices/pageweb.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
// use Symfony\Component\Form\FormFactoryInterface;

class pageweb extends entitiesGeneric {

	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		if(($this->init["pageweb"] === true) || ($this->modeFixtures === true)) $this->defineEntity("pageweb");
	}

	/**
	* serviceEventInit
	* Initialise le service - attention : cette méthode est appelée en requête principale par EventListener !!!
	* 
	* @param FilterControllerEvent $event
	* @param boolean $reLoad
	*/
	public function serviceEventInit(FilterControllerEvent $event, $reLoad = false) {
		$this->defineEntity("pageweb");
		if($this->init["pageweb"] === false) {
			$this->init["pageweb"] = true;
			$serviceData = false;
			// post
			$pagewebSlug = $event->getRequest()->request->get('pagewebSlug');
			$categorieSlug = $event->getRequest()->request->get('categorieSlug');
			// get
			if($pagewebSlug === null) $pagewebSlug = $event->getRequest()->query->get('pagewebSlug');
			if($categorieSlug === null) $categorieSlug = $event->getRequest()->query->get('categorieSlug');
			// initialisation
			if($pagewebSlug !== null) {
				$serviceData = $this->getRepo()->findOneBySlug($pagewebSlug);
				if(true === is_object($serviceData)) {
					$this->service['nom'] = $serviceData->getNom();
					$this->service['slug'] = $serviceData->getSlug();
					$this->service['title'] = $serviceData->getTitle();
					$this->service['titreh1'] = $serviceData->getTitreh1();
					$this->service['metatitle'] = $serviceData->getMetatitle();
					$this->service['metadescription'] = $serviceData->getMetadescription();
					$this->service['fichierhtml'] = $serviceData->getFichierhtml();
					$this->service['route'] = $serviceData->getRoute();
					// Sérialisation pour mise en session
					// var_dump($this->service);
					$this->siteListener_InSession();
				} else {
					// $this->container->get("session")->getFlashBag()->add('info', "Page web définie (".$pagewebSlug."), mais non trouvée.");
					$this->siteListener_OutSession();
				}
			} else {
				// $this->container->get("session")->getFlashBag()->add('info', "Page web non définie.");
				$this->siteListener_OutSession();
			}
		}
		return $this;
	}

	/**
	 * getListByTag
	 * @param mixed (string/array)
	 */
	public function getListByTag($tags) {
		$t = $this->repo->findListByTag($tags);
		if(count($t) > 0) {
			return $t;
		} else return false;
	}

	/**
	 * getListByCategorie
	 * @param mixed (string/array)
	 */
	public function getListByCategorie($categorieSlug) {
		$serviceCat = $this->container->get("AcmeGroup.categorie");
		$elem = $serviceCat->getRepo()->findBySlug($categorieSlug);
		$r = $serviceCat->getRepo()->getChildren($elem[0], false);
		foreach ($r as $cat) {
			// récupère les pages
			$rr[] = $cat->getPage();
		}
		return $rr;
	}

	/**
	 * getDynPages
	 * @param string $pageSlug
	 * @return array
	 */
	public function findBySlugWithTextes($pageSlug) {
		return $this->getRepo()->findWithRichtexts($pageSlug);
	}

	/**
	 * getDynPages
	 * @param string $pageSlug
	 * @param array $elements
	 * @return array
	 */
	public function getDynPages($pageSlug) {
		$pageweb = array();
		if(is_string($pageSlug)) $pageSlug = array($pageSlug);
		// récupération de toutes les pages demandées
		foreach ($pageSlug as $key => $page) {
			$r = $this->findBySlugWithTextes($page);
			if($r !== null) $pageweb[] = $r;
		}
		// if(count($pageweb) < 1) $pageweb = null;
		return $pageweb;
	}


}

?>
